@extends('layouts.base')
@section('title', 'Orders Add Quotation')


@section('content')

   <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Quotation Shipment #{{ $order->shipment_id }}
      </h1>
    </section>
<form action="{{ action('OrderController@addQuotationStore', $order->id) }}" id="order_form" method="POST">
	@method('put')
		@csrf
    <section class="content">
    	<div class="box box-solid">
    		<div class="container-fluid">
    			<h3>Order Details:</h3>
    		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-sm-4">
					<label>Supplier Name:</label>
					{{ $order->supplier_by != null  ? $order->supplier_by->name . $order->supplier_by->last_name : '--' }}
					<br><label>Supplier Email:</label>
					{{ $order->supplier_by != null ? $order->supplier_by->email : '--' }}
					<br><label>Supplier Location:</label>
					{{ $order->location }}
				</div>
				<div class="col-sm-4">
					<label>Buyer Name:</label>
					@if($order->ordered_by != null)
					{{ $order->ordered_by->name }} {{ $order->ordered_by->last_name }}
					@else
					--
					@endif
					<br><label>Buyer Email:</label>
					{{$order->ordered_by != null ? $order->ordered_by->email : '--'}}
					<br><label>Pickup Location:</label>
					{{ $order->pickup_location }}
				</div>
				<div class="col-sm-4">
					<label>Importing:</label>
					<font style="word-wrap: break-word;">{{ $order->import_details }}</font>
					@if(request()->user()->isAdmin() || request()->user()->isSupplier())
					<br><label>Warehouse:</label>
					{{ $order->warehouse }}
					@endif
				</div>

			</div>
			</div>
		</div> <!--box end-->

	<div class="box box-solid">
		<div class="container-fluid">
    			<h3>Boxes:</h3>
    		</div>
		<div class="box-body">
				<div class="table-responsive">
	              <table id="orders_table" class="table table-bordered table-condensed text-center">
	                <thead>
	                <tr class="input-table-tr">
	                  <th class="input-table-count">#</th>
	                  <th class="input-table-qty">Qty</th>
	                  <th class="input-table-select">Measurement</th>
	                  <th class="input-table-number">Length</th>
	                  <th class="input-table-number">Width</th>
	                  <th class="input-table-number">Height</th>
	                  <th class="input-table-number">Weight(kg)</th>
	                </tr>
	                </thead>
	                <tbody>
	                	@foreach($order->details as $detail)
	                <tr>
	                  <td><input type="hidden" name="product[{{$loop->index}}][detail_id]" value="{{ $detail->id }}">
	                  	<span class="sr_number">1</span></td>
	                  	<td><input type="text" class="form-control input-sm" value="{{ $detail->qty }}" name="product[{{$loop->index}}][qty]" id="product.{{$loop->index}}.qty" style="text-align: center;" readonly="readonly"></td>
	                  <td> <select class="form-control" id="product.{{$loop->index}}.measurement" name="product[{{$loop->index}}][measurement]" disabled="true">
							<option value="cm" {{ $detail->measurement == 'cm' ? 'selected' : '' }}>Centimeters</option>
							<option value="m" {{ $detail->measurement == 'm' ? 'selected' : '' }}>Meters</option>
							<option value="in" {{ $detail->measurement == 'in' ? 'selected' : '' }}>Inches</option>
							<option value="ft" {{ $detail->measurement == 'ft' ? 'selected' : '' }}>Feet</option>
						</select> </td>
	                  <td><input type="text" class="form-control input-sm" name="product[{{$loop->index}}][length]" id="product.{{$loop->index}}.length" value="{{ $detail->length }}" readonly="readonly"></td>
	                  <td><input type="text" class="form-control input-sm" name="product[{{$loop->index}}][width]" id="product.{{$loop->index}}.width" value="{{ $detail->width }}" readonly="readonly"></td>
	                  <td><input type="text" class="form-control input-sm" name="product[{{$loop->index}}][height]" id="product.{{$loop->index}}.height" value="{{ $detail->height }}" readonly="readonly"></td>
	                  <td><input type="text" class="form-control input-sm" name="product[{{$loop->index}}][weight]" id="product.{{$loop->index}}.weight" value="{{ $detail->weight }}" readonly="readonly"></td>
	                </tr>
	                @endforeach
	                </tbody>
	              </table>
            </div>
            <div class="row">
				<div class="col-sm-4">
					<label for="address">Total CBM:</label>
					<input type="text" name="cbm" id="cbm" value="{{ $order->cbm }}" class="form-control" readonly="readonly">
				</div>
				<div class="col-sm-4">
					<label for="address">Total Weight(kg):</label>
					<input type="text" name="weight" id="weight" value="{{ $order->weight }}" class="form-control" readonly="readonly">
				</div>
			</div>
            <input type="hidden" id="rowcount" value="{{ $order->details->count() }}">
            </div>
            <!-- /.box-body -->
          </div> <!-- box-end -->



	 <div class="box box-solid">
    		<div class="container-fluid">
    			<div class="col-md-3 col-md-offset-4">
    			<h3 style="text-align: center">Quotation:*</h3>
    		</div>
    		</div>
		<div class="box-body">
			<div class="row" >
				<div class="col-md-3 col-md-offset-4">
					<label for="price">Price(PHP):*</label>
					<input type="text" value="00.00" name="price" id="price" class="form-control numeric">
				</div>
			</div>
			<br>
			<button class="btn btn-primary pull-right">Add Quotation</button>
		</div>
	</div>

    </section>
</form>
@endsection

@section('javascript')
<!-- update sr number on load -->
<script type="text/javascript">
	function update_table_sr_number(){
    var sr_number = 1;
    $('table#orders_table tbody').find('.sr_number').each( function(){
        $(this).text(sr_number);
        sr_number++;
    });
}
	$(document).ready(function(){
		$('#price').mask('000,000,000,000,000.00', {reverse: true});
		update_table_sr_number();
	});
</script>
<script type="text/javascript">
	$("#order_form").submit(function(e) {
		e.preventDefault();
		 $('.btn_save').prop('disabled', true);
			window.swal({
				  title: "Checking...",
				  text: "Please wait",
				  button: false,
				  allowOutsideClick: false
				});
			$.ajax({
				url : $(this).attr('action'),
				type : 'POST',
				data : $(this).serialize(),
				success: function(data){
					console.log(data);
					if(data.status){
						toastr.error(data.status);
					}
					if (data.success){
						window.location.replace("/quotation");
					}
			        if (data.error){
			        	$('.error').remove();
			        	$.each(data.error, function(index, val){
			        	$('input[id="'+ index +'"]').after('<label class="text-danger error">' + val + '</label>');
			        	});
			        	setTimeout(() => {
						  window.swal({
						    title: "Something's not right..",
						    button: false,
						    timer: 1000
						  });
						}, 500);
			        }       
			      	 $('.btn_save').prop('disabled', false);
			     },
			    error: function(jqXhr, json, errorThrown){
			    	console.log(jqXhr);
			    	console.log(json);
			    	console.log(errorThrown);
			    	$('.btn_save').prop('disabled', false);
			    }
			});
	});
</script>
@endsection


