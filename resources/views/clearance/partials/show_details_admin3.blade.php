<div class="modal-header" >
    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="modalTitle"> Customs Clearance Details(<b>(Reference No:</b> #{{ $clearance->id }})
    </h4>
</div>
<div class="modal-body" >
  <div class="row invoice-info">
    <div class="col-sm-4 invoice-col">
      <div class="form-group pull-left">
        Shipping Company: <b> {{ $clearance->shipping_company }}</b>
        <b>Delivery Address:</b> {{ $clearance->delivery_address }}<br>
      </div>
    </div>
    <div class="col-sm-4 invoice-col">
      <div class="form-group">
     Supplier: <strong>{{ $clearance->supplier_name }}</strong>
      <address>
        Email: {{ $clearance->supplier_email }}
      </address>
      </div>
    </div>
    
    <div class="col-sm-4 invoice-col">
        <div class="form-group pull-right">
      <b>Reference No:</b> #{{ $clearance->id }}<br/>
      <b>Date:</b> {{ $clearance->created_at }}<br/>
      <b>Clearance Status:</b> {{ $clearance->get_status_display() }}<br>
    </div>
  </div>
</div>
@if($clearance->invoice)
  <div class="row invoice-info no-print">
    <div class="col-sm-8 invoice-col">
      <div class="form-group pull-left">
        <label>Proforma Invoice:</label>
        <a target="_blank" href="{{ $clearance->get_invoice_url() }}">View Invoice</a>
      </div>
    </div>
  </div>
@endif

  @if($clearance->waybill)
  <div class="row invoice-info no-print">
    <div class="col-sm-8 invoice-col">
      <div class="form-group pull-left">
        <label>Waybill:</label>
        <a target="_blank" href="{{ $clearance->get_waybill_url() }}">View Waybill</a>
      </div>
    </div>
  </div>
  @endif

@if($clearance->admin1_deposit)
  <div class="row invoice-info no-print">
    <div class="col-sm-8 invoice-col">
      <div class="form-group pull-left">
        <label>Customer Deposit:</label>
        <a target="_blank" href="{{ $clearance->get_admin1_deposit_url() }}">View Customer Deposit</a>
      </div>
    </div>
  </div>
  @endif
</div>






