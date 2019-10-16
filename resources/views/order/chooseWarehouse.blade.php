<div class="modal-dialog modal-xl" role="document">
	<form action="{{ action('OrderController@chooseWarehouseStore', $order->id) }}" id="order_form" method="POST">
	@method('put')
		@csrf	
  <div class="modal-content">
    <div class="modal-header">
	    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <h4 class="modal-title" id="modalTitle"> Order - Choose Warehouse(<b>(Reference No:</b> #{{ $order->id }})
	    </h4>
	</div>
		<div class="modal-body" >
		 <div class="row invoice-info">
		    <div class="col-sm-12 invoice-col">
		      <div class="form-group">
		      	<label for="warehouse"> Warehouse:</label>
		      		<select class="form-control" name="warehouse" style="word-break: break-word;">
		      			@foreach(config('app.' . $order->location . 'warehouses') as $warehouse)
		      				<option value="{{ $warehouse }}" title="{{ $warehouse }}">{{ $warehouse }} </option>
		      			@endforeach
		      		 </select>
		      </div>
		    </div>
		  </div>
		</div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary no-print"><i class="fa fa-save"></i> Save
      </button>
      <button type="button" class="btn btn-default no-print" data-dismiss="modal">Close</button>
    </div>
</form>
  </div>
</div>
