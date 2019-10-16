<div class="modal-dialog modal-xl" role="document">
  <div class="modal-content">
    <div class="modal-header">
    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="modalTitle"> Order Quotation(<b>(Reference No:</b> #{{ $order->shipment_id }}) </h4>
		</div>
	<div class="modal-body" >
	  <div class="row">
	    	<h1 align="center">PHP {{ number_format($order->price, 2 ) }}</h1>
	  </div>
	</div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default no-print" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
