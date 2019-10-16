<div class="modal-dialog modal-xl" role="document">
  <div class="modal-content">
@include('order.partials.show_details')
    <div class="modal-footer">
      <button type="button" class="btn btn-primary no-print" aria-label="Print" 
      onclick="$(this).closest('div.modal-content').printThis();"><i class="fa fa-print"></i> Print
      </button>
      <button type="button" class="btn btn-default no-print" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>

