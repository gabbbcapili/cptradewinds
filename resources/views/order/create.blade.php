@inject('request', 'Illuminate\Http\Request')

 @extends(Auth::user()? 'layouts.base' : 'layouts.quotation')
@section('title',  $title)
@section('css')
 <link rel="stylesheet" href="{{ asset('css/order.css') }}" >
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
		<input type="hidden" name="source" value="{{ $request->get('source') }}">
		<input type="hidden" name="order_type" value="{{ $request->segment(1) }}">
    <section class="content">
    	@if (request()->user())
    	@include('order/partials/order/OrderWithUser')
    	@else
    	@include('order/partials/order/OrderWithoutUser')
	@endif 

<!-- units for items -->
<datalist id="units">
  <option value="pc(s)">
  <option value="kg">
  <option value="lbs">
</datalist>

<div class="box box-solid items-div">
	<div class="box-body">
		<div class="row">
			<div class="col-sm-10"><b>Items:</b></div>
			<div class="col-sm-2">
			<button type="button" class="btn btn-primary pull-right btn-sm" onclick="addItem()"><i class="fa fa-plus"></i> Add Box</button>
			</div>
		</div>
		<div class="table-responsive">
          <table id="items_table" class="table table-bordered table-condensed text-center">
            <thead>
            <tr class="input-table-tr">
              <th class="input-table-count">#</th>
              <th class="input-table-qty"> <font  data-toggle="tooltip" data-placement="top" title="The number of item for this measurement">Qty</font></th>
              <th class="input-table-select"> <font data-toggle="tooltip" data-placement="top" title="The unit of each item">Unit</font></th>
              <th class="input-table-select"> <font  data-toggle="tooltip" data-placement="top" title="The name of the item">Name</font></th>
              <th><i class="fa fa-trash"></i></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
            <input type="hidden" id="itemRowCount" name="itemRowCount" value="0">
      </div><!-- /.box-body -->
</div><!-- box end -->

<div class="box box-solid boxes-div">
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
              <th class="input-table-qty"> <font  data-toggle="tooltip" data-placement="top" title="The number of boxes for this measurement">Qty</font></th>
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
	var redirect = '';
	if('{{ $request->segment(1) }}' == 'quotation'){
		redirect = "/quotation";
	}else{
		redirect = "/orders";
	}
</script>
<script src="{{ asset('js/order.js') }}"></script>
<script type="text/javascript">
	@if(! $request->user())
		$('.boxes-div').addClass('hide');
		$('.items-div').addClass('hide');
		$(document).on("change","input[name='user_type']",function() {
    		// Do something interesting here
    		$('.boxes-div').addClass('hide');
			$('.items-div').addClass('hide');
    		if($(this).val() == 'supplier'){
    			$('.boxes-div').removeClass('hide');
    			$(".tableTR").remove();
    			addRow();
    		}
    		if($(this).val() == 'buyer'){
    			$('.items-div').removeClass('hide');
    			$(".tableTR").remove();
    			addItem();
    		}
		});
	@else
		$('.boxes-div').addClass('hide');
		$('.items-div').addClass('hide');
		@if($request->user()->isCustomer())
			$('.items-div').removeClass('hide');
    			addItem();
		@elseif($request->user()->isSupplier())
			$('.boxes-div').removeClass('hide');
    			addRow();
		@endif	
	@endif
</script>

@endsection


