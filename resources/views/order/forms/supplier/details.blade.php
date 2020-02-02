<div class="box box-solid">
		<div class="box-body">
			<div class="row">
				<div class="col-sm-4">
					<label>Supplier Name:</label>
					@if($order->supplier_by)
						{{ $order->supplier_by->name }} {{ $order->supplier_by->last_name }}
					@endif
					<br><label>Supplier Email:</label>
					@if($order->supplier_by)
						{{ $order->supplier_by->email }}
					@endif
					<br><label>Supplier Location:</label>
					{{ $order->location }}
				</div>
				<div class="col-sm-4">
					<label>Buyer Name:</label>
					@if($order->ordered_by != null)
					{{ $order->ordered_by->name }} {{ $order->ordered_by->last_name }}
					@endif
					<br><label>Buyer Email:</label>
					@if($order->ordered_by != null)
					{{ $order->ordered_by->email }}
					@endif
					@if($order->pickup_location != null)
						<br><label>Pickup Location:</label>
						{{ $order->pickup_location }}
					@endif
					@if($order->notes != null && $order->status <= 5)
						<br><label>Admin Notes:</label>
						{{ $order->notes }}
					@endif
				</div>
				<div class="col-sm-4">
					<label>Importing:</label>
					<font style="word-wrap: break-word;">{{ $order->import_details }}</font>
					<br><label>Warehouse:</label>
					{{ $order->warehouse }}
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
					<br><label>Shipment Status:</label>
					{{ $order->get_status_display() }}
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
				<h3><a href="{{ $order->get_supplier_payment_url() }}" target="_blank" class="btn"><i class="fa fa-eye"></i> View details here</a></h3>
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
				<h2>Items: <i class="fa fa-check fa-lg green"></i></h2>
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


	@if(! $order->status == 2 || ! request()->user()->isSupplier())
		<div class="box box-solid">
			<div class="container-fluid">
    			<h3>Boxes:</h3>
    		</div>
			<div class="box-body">
					<div class="table-responsive">
		              <table class="table table-bordered table-condensed text-center">
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

          @else
          @include('order/forms/customer/editBoxes')
          @endif
          <!-- add type -->
      @if($order->status >= 6)

       <div class="box box-solid">
		<div class="box-body">
			<div class="row">
				<div class="container-fluid text-center">
					<h2>Step 1 <i class="fa fa-check fa-lg green"></i></h2>
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
	            <br>
	            <div class="row center-div">
	            	<div class="text-center">
	            		<label>Warehouse</label>
	            		<input type="text" class="form-control" value="{{ $order->warehouse }}" readonly>
	            	</div>
	            </div>
            </div>
            <!-- /.box-body -->
          </div> <!-- box-end -->
          @endif


       @if($order->status >= 7)
       <div class="box box-solid">
		<div class="container-fluid text-center">
					<h2>Step 2 <i class="fa fa-check fa-lg green"></i></h2>
					<h3>Boxes Pictures:</h3>
				</div>
		<div class="box-body">
		@foreach(explode('#', $order->boxes) as $box)
		    <div class="col-sm-4">
		<a target="_blank" href="{{ $order->get_box_url($box) }}"><img src="{{ $order->get_box_url($box) }}" width="100%"></a>
		    <div style="height:30px;"></div>
		    </div>
		    @endforeach

            </div>
            <!-- /.box-body -->
          </div> <!-- box-end -->
          @endif