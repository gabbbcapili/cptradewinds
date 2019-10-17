<form action="{{ action('OrderController@updateBoxes', $order->id) }}" class="form" method="POST" enctype='multipart/form-data'>
	@method('put')
		@csrf
	<div class="box box-solid">
		<div class="container-fluid">
			<h3>
				<div class="row">
				<div class="col-sm-10"><b>Boxes:</b></div>
				<div class="col-sm-2">
				<button type="button" class="btn btn-primary pull-right btn-sm" onclick="addBox()"><i class="fa fa-plus"></i> Add Box</button>
				</div>
			</div>
			</h3>
		</div>


	<div class="box-body">
			<div class="table-responsive">
              <table class="table table-bordered table-condensed text-center" id="box-table">
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
                  	<td><input type="text" class="form-control input-sm" value="{{ $detail->qty }}" name="product[{{$loop->index}}][qty]" id="product.{{$loop->index}}.qty" style="text-align: center;"></td>
                  <td> <select class="form-control" id="product.{{$loop->index}}.measurement" name="product[{{$loop->index}}][measurement]">
						<option value="cm" {{ $detail->measurement == 'cm' ? 'selected' : '' }}>Centimeters</option>
						<option value="m" {{ $detail->measurement == 'm' ? 'selected' : '' }}>Meters</option>
						<option value="in" {{ $detail->measurement == 'in' ? 'selected' : '' }}>Inches</option>
						<option value="ft" {{ $detail->measurement == 'ft' ? 'selected' : '' }}>Feet</option>
					</select> </td>
                  <td><input type="text" class="form-control input-sm" name="product[{{$loop->index}}][length]" id="product.{{$loop->index}}.length" value="{{ $detail->length }}"></td>
                  <td><input type="text" class="form-control input-sm" name="product[{{$loop->index}}][width]" id="product.{{$loop->index}}.width" value="{{ $detail->width }}"></td>
                  <td><input type="text" class="form-control input-sm" name="product[{{$loop->index}}][height]" id="product.{{$loop->index}}.height" value="{{ $detail->height }}"></td>
                  <td><input type="text" class="form-control input-sm" name="product[{{$loop->index}}][weight]" id="product.{{$loop->index}}.weight" value="{{ $detail->weight }}"></td>
                  <td><i class="fa fa-times remove_order_entry_row text-danger" title="Remove" style="cursor:pointer;"></i></td></tr>
                </tr>
                @endforeach
                </tbody>
              </table>
        </div>
        <input type="hidden" id="rowcount" value="{{ $order->details->count() }}">
        </div>
    <!-- /.box-body -->
    	<div class="box box-solid nextStep">
			<div class="box-body">
				<br>
				<button class="btn btn-primary pull-right btn_save">Update Boxes</button>
			</div>
		</div> <!--box end-->
  </div> <!-- box-end -->
</form> <!-- update boxes-->