<div class="modal-dialog modal-xl" role="document">
  <div class="modal-content">
    <div class="modal-header">
	    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <h4 class="modal-title" id="modalTitle"> Order - View Pictures(<b>(Reference No:</b> #{{ $order->shipment_id }})
	    </h4>
	</div>
		<div class="modal-body">
		 <div class="row invoice-info">
		 	@foreach($boxes_images as $box)
		    <div class="col-sm-4 invoice-col">
		<a target="_blank" href="{{ $order->get_box_url($box) }}"><img src="{{ $order->get_box_url($box) }}" width="100%"></a>
		    <div style="height:30px;"></div>
		    </div>
		    @endforeach
		  </div>
		</div>
    <div class="modal-footer">
    	<a href="#" data-href="{{ action('OrderController@show', $order->id) }}" class="btn btn-primary no-print modal_button">Back
      </a>
      <button type="button" class="btn btn-default no-print" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
