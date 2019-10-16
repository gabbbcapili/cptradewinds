 <form action="{{ action('OrderController@update', $order->id) }}" class="form" method="POST">
	@method('put')
		@csrf
	<div class="box box-solid">
		<div class="box-body">

			<div class="row">
				<div class="container-fluid text-center">
					<h2>Step 1</h2>
					<h3>Importing:</h3>
				</div>
			</div>

			<div class="row center-div text-center">
					<button type="button" class="btn btn-warning btn-sm" onclick="addRow()"><i class="fa fa-plus"></i> Add Box</button>
			</div>
				<!-- <div class="col-sm-6"> -->
					<div class="table-responsive">
		              <table id="orders_table" class="table table-bordered table-condensed text-center center-div">
		                <thead>
		                <tr class="input-table-tr">
		                  <th class="input-table-count">#</th>
		                  <th class="input-table-qty">Qty</th>
		                  <th class="input-table-select">Type</th>
		                  <th><i class="fa fa-trash"></i></th>
		                </tr>
		                </thead>
		                <tbody>
		                	@foreach($order->details as $detail)
		                <tr>
		                  <td><span class="sr_number">1</span></td>
		                  	<td><input type="text" class="form-control input-sm" value="{{ $detail->qty }}" name="product[{{$loop->index}}][qty]" id="product.{{$loop->index}}.qty" style="text-align: center;"></td>
		                  	<td> <select class="form-control" id="product.{{$loop->index}}.type" name="product[{{$loop->index}}][type]">
		                  		<option></option>
								@foreach($detail->get_type_select() as $key => $value)
									<option value="{{ $key }}" {{ $key == $detail->type ? 'selected' : '' }}>{{ $value }}</option>
								@endforeach
							</select> </td>
		                  <td><i class="fa fa-times remove_order_entry_row text-danger" title="Remove" style="cursor:pointer;"></i></td>
		                </tr>
		                @endforeach
		                </tbody>
		              </table>
		            </div>
		          <!-- </div> -->
		          <br>
		          <div class="row center-div">
					<label>Choose Warehouse:</label>
					<select class="form-control" name="warehouse" id="warehouse" style="word-break: break-word;">
		      			@foreach(config('app.' . $order->location . 'warehouses') as $warehouse)
		      				<option value="{{ $warehouse }}" title="{{ $warehouse }}" {{ $warehouse == $order->warehouse ? 'selected' : '' }}>{{ $warehouse }} </option>
		      			@endforeach
		      		 </select>
				</div>
            <input type="hidden" id="rowcount" value="{{ $order->details->count() }}">
            </div>
            <!-- /.box-body -->
          </div> <!-- box-end -->
    

	<div class="box box-solid">
		<div class="box-body">
			<br>
			<button class="btn btn-primary pull-right btn_save">Step complete</button>
			<a href="#" class="confirmation pull-right btn btn-danger" style="margin-right:10px" data-title="Are you sure to cancel this shipment?" data-text="Once cancelled, you will not be able undo this action!"  data-href="{{ action('OrderController@cancel', $order->id) }}"><i class="fa fa-ban"></i> Cancel Shipment</a>
		</div>
	</div> <!--box end-->
	</form> <!-- adding type and qty-->