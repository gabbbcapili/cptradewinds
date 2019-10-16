<div class="modal-dialog modal-xl" role="document">
  <div class="modal-content">
    <div class="modal-header">
	    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <h4 class="modal-title" id="modalTitle"> Insurance - View Attachments(<b>(Reference No:</b> #{{ $insurance->id }})
	    </h4>
	</div>
		<div class="modal-body">
			@if($insurance->deposit)
		 <div class="row invoice-info">
		    <div class="col-sm-6 invoice-col">
		    	<label>Deposit</label>
		<a target="_blank" href="{{ $insurance->getDepositUrl() }}"><img src="{{ $insurance->getDepositUrl() }}" width="100%"></a>
		    <div style="height:30px;"></div>
		    </div>
		</div>
		@endif
		 <div class="row invoice-info">
		    <div class="col-sm-6 invoice-col">
		    	<label>Invoice:</label>
		<a target="_blank" href="{{ $insurance->getInvoiceUrl() }}"><img src="{{ $insurance->getInvoiceUrl() }}" width="100%"></a>
		    <div style="height:30px;"></div>
		    </div>
		</div>
		 <div class="row invoice-info">
		    <div class="col-sm-6 invoice-col">
		    	<label>Insurance Declaration / Application:</label>
		<a target="_blank" href="{{ $insurance->getDeclarationUrl() }}"><img src="{{ $insurance->getDeclarationUrl() }}" width="100%"></a>
		    <div style="height:30px;"></div>
		    </div>
		  </div>
		</div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default no-print" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
