@inject('request', 'Illuminate\Http\Request')
@extends('layouts.base')
@section('title', 'Orders Create')


@section('content')

   <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
  		 {{ $title }} #{{ $order->shipment_id }}
      </h1>
    </section>
    <section class="content">
    	<div class="box box-solid">
    		<div class="container-fluid">
    			<h3>{{ $title }} Details:</h3>
    		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-sm-4">
					<label>Supplier Name:</label>
					{{ $order->supplier_by != null  ? $order->supplier_by->name . ' ' . $order->supplier_by->last_name : '--' }}
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
					@if($order->pickup_type != null)
						<br><label>Pick-up Type: </label>
						{{ $order->pickup_type }}
					@endif
					@if($order->notes != null && $order->status <= 5)
						<br><label>Admin Notes:</label>
						{{ $order->notes }}
					@endif

					@if($order->pickup_type == 'pickup')
						@if($order->pickup_location != null)
						<br><label>Pickup Location:</label>
						{{ $order->pickup_location }}
						@endif
						<br><label>Pickup Person/Company:</label>
						{{ $order->pickup_person }}
						<br><label>Pickup Approx Time:</label>
						{{ $order->pickup_time }}
					@endif

					@if($order->pickup_type == 'deliver')
						<br><label>Delivery address:</label>
						{{ $order->delivery_address }}
						<br><label>Approximate Local Transport Fee:</label>
						{{ number_format($order->delivery_price, 2) }}
					@endif


					
					@if($order->payment != null)
					<br><label>Proof of Payment:</label>
					<a target="_blank" href="{{ $order->get_payment_url() }}"> {{ $order->payment }}</a>
					@endif
					@if($order->extra_charges != null)
					<br><label>Extra Charges(Free Storage Penalty):</label>
					 {{ $order->extra_charges ?  $order->extra_charges : 0}}
					@endif
					@if(request()->user()->isAdmin() && $order->status == 12)
					<br><br>
					 <a href="#" class="btn btn-primary confirmation" data-title="Are you sure to approve this transaction payment?" data-text="If yes, you will not be able undo this action!" data-href="{{ action('OrderController@approvePayment', $order->id) }}"><i class="fa fa-check-square-o"></i> Approve Payment</a>
					@endif
					@if($order->status == 13 && auth()->user()->isAdmin())
							@if($order->pickup_type == 'pickup')
                            <a href="#" class="btn btn-primary confirmation" data-title="Are you sure to complete this transaction?" data-text="If yes, you will not be able undo this action!" data-href="{{ action('OrderController@completeTransaction', $order->id) }}"><i class="fa fa-check-square-o"></i>Picked up?</a>
                            @elseif($order->pickup_type == 'deliver')
                            <a href="#" class="btn btn-primary modal_button" data-href="{{ action('OrderController@deliverForm', $order->id) }}"><i class="fa fa-check-square-o"></i> Delivered?</a>
                            @endif

                    @endif
				</div>
				<div class="col-sm-4">
					<label>Date:</label>
					{{ date_format(date_create($order->created_at), 'M, d Y H:i') }}
					<!-- <label>Importing:</label> -->
					<!-- <font style="word-wrap: break-word;">{{ $order->import_details }}</font> -->
					@if(request()->user()->isAdmin() || request()->user()->isSupplier())
						@if($order->warehouse)
						<br><label>Warehouse:</label>
						{{ $order->warehouse }}
						@endif
					@endif
					<br><label>Invoice Reference Number:</label>
					#{{ $order->invoice_no }}
					@if($order->price != null)
					<br><label>{{ $order->withQuote == true ? 'Quotation' : '' }} Importation Cost:</label>
					{{ number_format($order->price, 2) }} &nbsp&nbsp {{ date_format(date_create($order->price_date), 'M, d Y H:i') }}
					@endif
					@if($order->delivery_price != null)
					<br><label>Delivery Price: </label> {{  number_format($order->delivery_price, 2) }}
					<br><label>Delivery Address: </label> {{  $order->delivery_address }}
					@endif
					@if(request()->user()->isAdmin())
						@if($order->boxes_received != null)
						<br><label>Total Boxes CBM Received:</label>
							{{ $order->boxes_received }}
						@endif
					@endif
					<br><label style="font-size:20px">{{ $order->withQuote == false ? 'Shipment' : ''}} Status:</label>
					<b>
					{{ $order->get_status_display() }}
					@if($order->withQuote == true && (auth()->user()->isCustomer() || auth()->user()->isSupplier() ))
						<a href="{{ route('shipmentcreate') }}" style="margin-left:10px" class="btn btn-success">Start a Shipment</a>
					@endif
					</b>
				</div>
				

			</div>
			</div>
		</div> <!--box end-->

@php
$payment = $order->payments->where('status', 7)->first();
@endphp
		@if($payment)
   <div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="container-fluid text-center">
				<h2>Payments: <i class="fa fa-check fa-lg green"></i></h2>
				<h3>Total Amount: {{ number_format($payment->total_amount(), 2) }} <a href="#" class="modal_button btn" data-href="{{ action('PaymentController@show', [$payment->id] ) }}"><i class="fa fa-eye"></i> View details here</a></h3>
			</div>
		</div>	
        </div>
        <!-- /.box-body -->
      </div> <!-- box-end -->
@endif

@if($order->supplier_payment != null)
   <div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="container-fluid text-center">
				<h2>Payments: <i class="fa fa-check fa-lg green"></i></h2>
				<div class="col-sm-4"></div>
				<div class="col-sm-4">
				<h3><a href="{{ $order->get_supplier_payment_url() }}" target="_blank" class="btn"><img src="{{ $order->get_supplier_payment_url() }}" width="100%"></a></h3>
				</div>
			</div>
		</div>	
        </div>
        <!-- /.box-body -->
      </div> <!-- box-end -->
@endif

@if($order->items->count() != 0)
<div class="box box-solid">
	<div class="box-body">
		<div class="row">
			<div class="container-fluid text-center">
				<h2>Items:</i></h2>
			</div>
		</div>
			<div class="table-responsive">
              <table id="item_table" class="table table-bordered table-condensed text-center center-div">
                <thead>
                <tr class="input-table-tr">
                  <th class="input-table-count">#</th>
                  <th class="input-table-qty">Qty</th>
                  <th class="input-table-select">Unit</th>
                  <th class="input-table-select">Name</th>
                </tr>
                </thead>
                <tbody>
                	@foreach($order->items as $item)
                <tr>
                  <td><span class="sr_number">1</span></td>
                  	<td><input type="text" class="form-control input-sm" value="{{ $item->qty }}" style="text-align: center;" readonly="readonly"></td>
                  	<td><input type="text" class="form-control input-sm" value="{{ $item->unit }}" style="text-align: center;" readonly="readonly"></td>
                  	<td><input type="text" class="form-control input-sm" value="{{ $item->name }}" style="text-align: center;" readonly="readonly"></td>
                  	<td> </td>
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
        </div>
        <!-- /.box-body -->
      </div> <!-- box-end -->
@endif

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
	                  <th class="input-table-select">Unit of Measurement</th>
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
            @if(! $request->user()->isCustomer())
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
			@endif
            <input type="hidden" id="rowcount" value="{{ $order->details->count() }}">
            </div>
            <!-- /.box-body -->
          </div> <!-- box-end -->

                 <!-- add type -->
      @if($order->withQuote == false && $order->status >= 6)

       <div class="box box-solid">
		<div class="box-body">
			<div class="row">
				<div class="container-fluid">
					<h3>Importing:</h3>
				</div>
			</div>
				<div class="table-responsive">
	              <table id="orders_table" class="table table-bordered table-condensed text-center center-div">
	                <thead>
	                <tr class="input-table-tr">
	                  <th class="input-table-count">#</th>
	                  <th class="input-table-qty">Qty</th>
	                  <th class="input-table-select">Type</th>
	                </tr>
	                </thead>
	                <tbody>
	                	@foreach($order->types as $detail)
	                <tr>
	                  <td><span class="sr_number">1</span></td>
	                  	<td><input type="text" class="form-control input-sm" value="{{ $detail->qty }}" style="text-align: center;" readonly="readonly"></td>
	                  	<td> <select class="form-control" disabled="true">
	                  		<option></option>
							@foreach($detail->get_type_select() as $key => $value)
								<option value="{{ $key }}" {{ $key == $detail->type ? 'selected' : '' }}>{{ $value }}</option>
							@endforeach
						</select> </td>
	                </tr>
	                @endforeach
	                </tbody>
	              </table>
	            </div>
            </div>
            <!-- /.box-body -->
          </div> <!-- box-end -->
          @endif 


     @if($boxes_images[0] != null)
       <div class="box box-solid">
		<div class="container-fluid">
    			<h3>Boxes Pictures:</h3>
    		</div>
		<div class="box-body">
		@foreach($boxes_images as $box)
		    <div class="col-sm-4">
		<a target="_blank" href="{{ $order->get_box_url($box) }}"><img src="{{ $order->get_box_url($box) }}" width="100%"></a>
		    <div style="height:30px;"></div>
		    </div>
		    @endforeach
            </div>
            <!-- /.box-body -->
          </div> <!-- box-end -->
          @endif

       @if(request()->user()->isCustomer() && $order->status == 61)
       <div class="box box-solid">
		<div class="box-body">
			<div class="row">
				<div class="container-fluid  text-center">
					<h2>Packaging Approval</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					The cargo shown on the picture must:
					<ul>
						<li>The products are adequately packaged to withstand freight and handling. </li>
						<li>The shipping mark attached to each master carton or crate</li>
						<li>The outside of boxes bear no other marking than the shipping mark. There should be no indication of what the contents are to help avoid scrutiny during random inspections or theft.</li>
					</ul>
					By clicking Approve Packaging below, You acknowledges that:
					<ol>
						<li>All conditions above are met</li>
						<li>We are not responsible for missing or damaged items due to inadequate packaging, or the opening of boxes during random inspections which only occur very rarely. <a style="cursor: pointer;" id="showAdditionalInfo"> Click here to learn more.</a></li>
						<p class="hidden" id="learn_more">Random inspections only occur very rarely (less than 1% of the time) and very often only because the exterior of the boxes bear marking (on the box itself or other stickers attached) that attract the scrutiny of inspectors. Remember: unless your cargo is inspected, no one else knows the contents of your cargo apart from us, you and your supplier. </p>
					</ol>
				</div>
			</div>
			<br>
			<a href="#" class="confirmation pull-right btn btn-primary"  data-title="Are you sure to approve packaging of this shipment?" data-text=""  data-href="{{ action('OrderController@ApprovePackage', $order->id) }}"><i class="fa fa-check"></i> Approve Packaging</a>

			<a href="#" class="confirmation pull-right btn btn-danger" style="margin-right:10px" data-title="Are you sure to disapprove packaging of this shipment?" data-text=""  data-href="{{ action('OrderController@DisapprovePackage', $order->id) }}"><i class="fa fa-ban"></i> Disapprove Packaging</a>
		</div>
	</div> <!--box end-->
       @endif



          @if($order->shipment_proof != null)
       <div class="box box-solid">
		<div class="container-fluid">
    			<h3>Shipment Proof:</h3>
    		</div>
		<div class="box-body">
		    <div class="col-sm-4">
		<a target="_blank" href="{{ $order->get_shipment_proof_url() }}"><img src="{{ $order->get_shipment_proof_url() }}" width="100%"></a>
		    <div style="height:30px;"></div>
		    </div>
            </div>
            <!-- /.box-body -->
          </div> <!-- box-end -->
          @endif

          @if($order->delivery_receipt != null)
       <div class="box box-solid">
		<div class="container-fluid">
    			<h3>Shipment Proof:</h3>
    		</div>
		<div class="box-body">
		    <div class="col-sm-4">
		<a target="_blank" href="{{ $order->get_delivery_receipt_url() }}"><img src="{{ $order->get_delivery_receipt_url() }}" width="100%"></a>
		    <div style="height:30px;"></div>
		    </div>
            </div>
            <!-- /.box-body -->
          </div> <!-- box-end -->
          @endif

          

    </section>
@endsection

@section('javascript')
<!-- update sr number on load -->
<script type="text/javascript">

	$('#showAdditionalInfo').click(function(){
		$('#learn_more').removeClass("hidden");
	});


	function update_table_sr_number(){
    var sr_number = 1;
    $('table#orders_table tbody').find('.sr_number').each( function(){
        $(this).text(sr_number);
        sr_number++;
    });
}
	$(document).ready(function(){
		update_table_sr_number();
	});

	$('.confirmation').click(function(){
      swal({
          title: $(this).data('title'),
          text:  $(this).data('text'),
          icon: "warning",
          buttons: true,
          dangerMode: true,
        }).then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "GET",
            url: $(this).data('href'),
            dataType: "json",
            success: function(data){
              console.log(data.error);
              if (data.error){
                toastr.error(data.error);
              }
              if (data.success){
                location.reload();
              }
            },
            error: function(jqXhr, json, errorThrown){
            console.log(jqXhr);
            console.log(json);
            console.log(errorThrown);
            toastr.error('Sorry, Something went wrong. Please try again later.');
          }
          });
        }
      });
    });  
</script>
@endsection


