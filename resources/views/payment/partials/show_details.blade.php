<div class="modal-header" >
    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="modalTitle"> Payment Service   Details(<b>(Reference No:</b> #{{ $payment->id }})
    </h4>
</div>
<div class="modal-body" >
  <div class="row">
    <div class="col-sm-12">
      <p class="pull-right"><b>Date:</b> {{ $payment->created_at }}</p>
    </div>
  </div>
  <div class="row invoice-info">
    <div class="col-sm-4 invoice-col">
      <div class="form-group pull-left">
     Supplier: <strong>{{ $payment->supplier_name }}</strong>
      <address>
        Email: {{ $payment->supplier_email }}
      </address>
      </div>
    </div>
    <div class="col-sm-4 invoice-col">
      @if($payment->ordered_by)
        <div class="form-group">
        Customer: <strong>{{ $payment->ordered_by->name }}</strong>
      <address> Email: {{ $payment->ordered_by->email }} </address>
      </div>
      @endif
    </div>
    
    <div class="col-sm-4 invoice-col">
        <div class="form-group pull-right">
      <b>Reference No:</b> #{{ $payment->id }}<br/>
      <b>Date:</b> {{ $payment->created_at }}<br/>
      @if($payment->amount != 0)
      <b>Amount:</b> PHP {{ number_format($payment->total_amount(), 2) }}  <br>
      @endif
      <b>Rate:</b> $1 = PHP {{ number_format($payment->rate, 2) }}<br>
      <b>Payment Status:</b> {{ $payment->getStatusDisplay() }}<br>
        @if ($payment->order)
      <b class="no-print">Order: <a href="#" class="modal_button " data-href="{{ action('OrderController@show', [$payment->order->id] ) }}">View Order</a></b>
      @endif
    </div>
  </div>
  </div>

@if(request()->user()->isAdmin())
  <div class="row invoice-info">
    <div class="col-sm-8 invoice-col">
      <div class="form-group pull-left">
     <b>Supplier Bank Details</b> <br>
     <b>Bank Name:</b> {{ $payment->bank_name }}<br>
     <b>Account Name:</b> {{ $payment->account_name }}<br>
     <b>Swift Code:</b> {{ $payment->swift_code }}<br>
     <b>Bank Address:</b> {{ $payment->bank_address }}<br>
     <b>Supplier Address:</b> {{ $payment->supplier_address }}<br>
      </div>
    </div>
  </div>
@endif


  @if($payment->invoice)
  <div class="row invoice-info no-print">
    <div class="col-sm-8 invoice-col">
      <div class="form-group pull-left">
        <label>Proforma Invoice:</label>
        <a target="_blank" href="{{ $payment->getInvoiceUrl() }}">View Invoice</a>
      </div>
    </div>
  </div>
  @endif
  @if($payment->deposit)
  <div class="row invoice-info no-print">
    <div class="col-sm-8 invoice-col">
      <div class="form-group pull-left">
        <label>Proof of Deposit:</label><br>
        @foreach($payment->deposits() as $img)
          <a target="_blank" href="{{ $payment->getDepositUrl($img) }}">View Deposit {{ $loop->iteration }}</a><br>
        @endforeach
      </div>
    </div>
  </div>
  @endif
  @if($payment->ssDeposit)
  <div class="row invoice-info no-print">
    <div class="col-sm-8 invoice-col">
      <div class="form-group pull-left">
        <label>Screenshot of Deposit:</label>
        <a target="_blank" href="{{ $payment->getSSDeposit() }}">View Deposit Screenshot</a>
      </div>
    </div>
  </div>
  @endif

</div>
