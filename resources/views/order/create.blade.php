@inject('request', 'Illuminate\Http\Request')

 @extends(Auth::user()? 'layouts.base' : 'layouts.quotation')


@section('title',  $title)
@section('css')
<style type="text/css">
     .content-wrapper {
/*  min-height:100%;
  background:url({{ asset('images/system/bground.jpg') }}) rgba(0,0,0,0.4);
  background-size:cover;
  background-blend-mode: multiply;   */  	
     }
	.import-title{
		background: #1498d5;
		color: white;
		padding:10px 15px;
		font-family: roboto;
		font-size: 25px;
		margin-top: 20px;
	}
	.input-table-tr {
    color: white;
    background-color: #1498d5 !important;
	}
	.btn-primary {
		margin-bottom: 10px;
	}
}
</style>
@endsection
@section('content')
   <!-- Content Header (Page header) -->
   <div class="container">
    <section class="content-header">
	    <div class="import-title">
		        {{ $title }}
	     </div>
    </section>
	<form action="{{ action('OrderController@store') }}" id="order_form" method="POST" enctype='multipart/form-data'>
		@csrf
		@if(request()->input('clientid'))
		<input type="hidden" name="user_id" value="{{ request()->input('clientid') }}">
		@endif
		<input type="hidden" name="order_type" value="{{ $request->segment(1) }}">
    <section class="content">
    	@if (request()->user())
    	@include('order/partials/order/OrderWithUser')
    	@else
    	@include('order/partials/order/OrderWithoutUser')
	@endif 
<div class="box box-solid">
		<div class="box-body">
				<div class="row">
					<div class="col-sm-10"><b>Boxes:</b></div>
					<div class="col-sm-2">
					<button type="button" class="btn btn-primary pull-right btn-sm" onclick="addRow()"><i class="fa fa-plus"></i> Add Box</button>
					</div>
				</div>
				<div class="table-responsive">
	              <table id="orders_table" class="table table-bordered table-condensed text-center">
	                <thead>
	                <tr class="input-table-tr">
	                  <th class="input-table-count">#</th>
	                  <th class="input-table-qty"> <font  data-toggle="tooltip" data-placement="top" title="The number of boxes for this measurement"></i>Qty</font></th>
	                  <th class="input-table-select"> <font data-toggle="tooltip" data-placement="top" title="The unit measurement of each boxes">Unit of Measurement</font></th>
	                  <th class="input-table-number"> <font  data-toggle="tooltip" data-placement="top" title="The length of each boxes depending on the measurement">Length</font></th>
	                  <th class="input-table-number"> <font  data-toggle="tooltip" data-placement="top" title="The width of each boxes depending on the measurement">Width</font></th>
	                  <th class="input-table-number"> <font  data-toggle="tooltip" data-placement="top" title="The height of each boxes depending on the measurement">Height</font></th>
	                  <th class="input-table-number"> <font data-toggle="tooltip" data-placement="top" title="The weight of each boxes depending on the measurement" >Weight(kg)</font></th>
	                  <th><i class="fa fa-trash"></i></th>
	                </tr>
	                </thead>
	                <tbody>
	                </tbody>
	              </table>
	            </div>
	            <input type="hidden" id="rowcount" name="rowcount" value="0">
          </div><!-- /.box-body -->
</div><!-- box end -->

	<div class="box box-solid">
		<div class="box-body">
			@if($request->segment(1) == 'orders')
			<br>
			After clicking the button below, a link will be sent to the supplier which will prompt the start of the import process. You must only do this when the supplier is ready to ship. To review the import process,  click<a target="_blank" href="https://importanything.ph/how-it-works/">  here </a>. To avail of our payment service, click <a target="_blank" href="{{ action('PaymentController@create') }}"> here </a> .
			<br><br>
			@endif
			<button class="btn btn-success btn_save">{{ $title }}</button>
		</div>
	</div> <!--box end-->

    </section>
</form>
</div>
@endsection

@section('javascript')

<script type="text/javascript">
	function addRow(){
		var row = parseInt($('#rowcount').val()) + 1;
		$('#rowcount').val(row);
		var html = '<tr><td><span class="sr_number">'+row+'</span></td>';
		html += '<td><input type="text" class="form-control input-sm" value="1" name="product['+row+'][qty]" id="product.'+row+'.qty" style="text-align: center;"></td>';
		html += '<td><select class="form-control input-table-select" id="product.'+row+'.measurement" name="product['+row+'][measurement]"><option value="cm">Centimeters</option><option value="m">Meters</option><option value="in">Inches</option><option value="ft">Feet</option></select></td>';
		html += '<td><input type="text" class="form-control input-sm" name="product['+row+'][length]" id="product.'+row+'.length"></td>';
		html += '<td><input type="text" class="form-control input-sm" name="product['+row+'][width]" id="product.'+row+'.width"></td>';
		html += '<td><input type="text" class="form-control input-sm" name="product['+row+'][height]" id="product.'+row+'.height"></td>';
		html += '<td><input type="text" class="form-control input-sm" name="product['+row+'][weight]" id="product.'+row+'.weight"></td>';
		html += '<td><i class="fa fa-times remove_order_entry_row text-danger" title="Remove" style="cursor:pointer;"></i></td></tr>';
		$('#orders_table tr:last').after(html);
		update_table_sr_number();
	}


	$( document ).ready(function() {
  		addRow();
	});
</script>


<script type="text/javascript">
$(document).on( 'click', '.remove_order_entry_row', function(){
	$(this).closest('tr').remove();
	update_table_sr_number();
});
function update_table_sr_number(){
    var sr_number = 1;
    $('table#orders_table tbody').find('.sr_number').each( function(){
        $(this).text(sr_number);
        sr_number++;
    });
}

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
				data: new FormData(this),
			    processData: false,
			    contentType: false,
				success: function(data){
					if (data.success){
						console.log(data.success);
						if('{{ $request->segment(1) }}' == 'quotation'){
							window.location.replace("/quotation");
						}else{
							window.location.replace("/orders");
						}
					}
					if (data.info){
						toastr.error(data.info);
					}
			        if (data.error){
			        	// console.log(data.error);
			        	$('.error').remove();
			        	$.each(data.error, function(index, val){
			        		//console.log(index + '\n' + val)
			        	$('input[id="'+ index +'"]').after('<label class="text-danger error">' + val + '</label>');
			        	if (index == "import_details"){
			        		$('#import_details').after('<label class="text-danger error">' + val + '</label>');
			        	}
			        	$('select[id="'+ index +'"]').after('<label class="text-danger error">' + val + '</label>');
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
			        // success logic
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

<script type="text/javascript">
	$('input[type=radio][name=user_type]').change(function() {
    if (this.value == 'buyer') {
        $('#import_details_label').text('importing');
    }
    else if (this.value == 'supplier') {
        $('#import_details_label').text('shipping');
    }
});

$("#location").change(function() {
	var warehouse = $('#warehouse'),
	value = this.value;
 	warehouse.find('option').remove().end();
 	console.log(value);
 	 if (value == "China"){
 		warehouse.append('<option value="Guangzhou: 广州市白云区石门街石井大道滘心路段自编1号。中北仓储园N1-15 (导航：中北仓储园)">Guangzhou: 广州市白云区石门街石井大道滘心路段自编1号。中北仓储园N1-15 (导航：中北仓储园)</option>');
 		warehouse.append('<option value="Yiwu:义乌江东山口小区115栋2号">Yiwu:义乌江东山口小区115栋2号</option>');
 	}
 	if (value == "HongKong"){
 		warehouse.append('<option value="North Point">North Point</option>');
 	}
 	if (value == "Taiwan"){
 		warehouse.append('<option value="Taipei">Taipei</option>');
 	}
 	if (value == "Thailand"){
 		warehouse.append('<option value="Rajatevee">Rajatevee</option>');
 		warehouse.append('<option value="Bangkok">Bangkok</option>');
 	}
});
</script>

@endsection


