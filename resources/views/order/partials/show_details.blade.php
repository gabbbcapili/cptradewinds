<div class="modal-header" >
    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="modalTitle"> Order Details(<b>(Reference No:</b> #{{ $order->id }})
    </h4>
</div>
<div class="modal-body" >
  <div class="row">
    <div class="col-sm-12">
      <p class="pull-right"><b>Date:</b> {{ $order->created_at }}</p>
    </div>
  </div>
  <div class="row invoice-info">
    <div class="col-sm-4 invoice-col">
      @if($order->supplier_by)
      <div class="form-group pull-left">
     Supplier: <strong>{{ $order->supplier_by->name }}</strong>
      <address>
        Email: {{ $order->supplier_by->email }}
      </address>
      </div>
      @endif
    </div>
    
    <div class="col-sm-4 invoice-col">
      @if($order->ordered_by)
        <div class="form-group">
        Customer: <strong>{{ $order->ordered_by->name }}</strong>
      <address> Email: {{ $order->ordered_by->email }} </address>
      </div>
      @endif
    </div>
    
    <div class="col-sm-4 invoice-col">
        <div class="form-group pull-right">
      <b>Reference No:</b> #{{ $order->id }}<br/>
      <b>Date:</b> {{ $order->created_at }}<br/>
      <b>Order Status:</b> {{ $order->get_status_display() }}<br>
      @if($order->price != null)
      <b>Fee:</b>P {{ number_format($order->price , 2) }}<br>
      @endif
    </div>
  </div>
  </div>
  @if($order->status >= 7)
  <div class="row invoice-info no-print">
    <div class="col-sm-4 invoice-col">
      <div class="form-group pull-left">
        <label>Invoice:</label>
        <a target="_blank" href="{{ $order->get_invoice_url() }}">View Invoice</a>
      </div>
    </div>
  </div>

  <div class="row invoice-info no-print">
    <div class="col-sm-4 invoice-col">
      <div class="form-group pull-left">
        <label>Boxes Pictures:</label>
        <a href="#" data-href="{{ action('OrderController@viewPictures', $order->id) }}" class="modal_button">View Pictures</a>
      </div>
    </div>
  </div>
  @endif
  @if($order->status >= 12 && (auth()->user()->isAdmin() || auth()->user()->isCustomer()))
  <div class="row invoice-info no-print">
    <div class="col-sm-4 invoice-col">
      <div class="form-group pull-left">
        <label>Payment:</label>
        <a target="_blank" href="{{ $order->get_payment_url() }}">View Payment</a>
      </div>
    </div>
  </div>
  @endif
  <br>

  <div class="row">
    <div class="col-sm-12 col-xs-12">
        @if($order->quoteFor == 1)
      <div class="table-responsive">
        <table class="table bg-gray">
          <thead>
            <tr class="bg-green">
              <th>#</th>
              <th>Qty</th>
              <th>Length</th>
              <th>Width</th>
              <th>Height</th>
              <th>Weight</th>
            </tr>
          </thead>
           @foreach($order->details as $detail)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $detail->qty }}</td>
            <td>{{ $detail->length }}{{ $detail->measurement }}</td>
            <td>{{ $detail->width }}{{ $detail->measurement }}</td>
            <td>{{ $detail->height }}{{ $detail->measurement }}</td>
            <td>{{ @number_format($detail->weight, 2) }}kg</td>
          </tr>
          @endforeach
        </table>
      </div>
  @endif
      <div class="row invoice-info">
        <div class="col-sm-12 invoice-col">
          <b>Overall CBM and Weight:</b><br>
          <label>CBM:</label> {{ number_format($order->cbm , 2)}}<br>
          <label>Weight:</label> {{ number_format($order->weight , 2)}}<br>
        </div>
      </div>
    </div>
  </div>
  <br>
</div>
