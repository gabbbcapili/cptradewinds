<div class="modal-header">
    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </h4>
</div>
<div class="modal-body no-print">
  <div class="row invoice-info">
    <div class="col-md-12">
      <img src="{{ url('images/system/icon.png') }}" class="img-responsive" style="display: block; margin-left: auto; margin-right: auto; width: 50%;">
    </div>
  </div>
  <div class="row invoice-info">
    <div class="col-md-12 text-center">
    <b> <h1>{{ $order->getShippingMarkPrefix() }}{{ $order->id }}</h1></b> 
      <font style="font-family:Times New Roman;">
        <h3>{{ $order->ordered_by->name }}</h3>
        <h5>IMPORTANYTHING.PH</h5>
        <h5>{{ env('EMAIL_PHONE') }}</h5>
        <br>
        <img src="data:image/png;base64,{{DNS1D::getBarcodePNG( $order->id, 'C39')}}" alt="barcode" class="img-responsive" style="display: block; margin-left: auto; margin-right: auto; width: 50%;">
        <h5>PC 1 OF {{ $order->total_quantity() }}</h5>
      </font>
    </div>
  </div>
<!--   <br><br>
  <div class="row invoice-info">
    <div class="col-md-12">
      <font style="font-family:Times New Roman;">
        INSTRUCTIONS TO SHIPPER:
        <ol>
          <li>PLEASE PRINT AND ATTACH THIS MARK <u>ON ALL</u> BOXES BEFORE SHIPPING</li>
          <li>PLEASE MAKE SURE THE MARK IS SECURELY ATTACHED TO EACH BOX. <u> APPLY CLEAR TAPE IF POSSIBLE FOR ADDED PROTECTION</u></li>
        </ol>
      </font>
      </div>
    </div> -->
</div>




<div class="modal-body print">
  @for($i = 1; $i <= $order->total_quantity(); $i++)
  <div class="row invoice-info">
    <div class="col-md-12">
      <img src="{{ url('images/system/icon.png') }}" class="img-responsive" style="display: block; margin-left: auto; margin-right: auto; width: 50%;">
    </div>
  </div>
  <div class="row invoice-info">
    <div class="col-md-12 text-center">
    <b> <h1>{{ $order->getShippingMarkPrefix() }}{{ $order->id }}</h1></b> 
      <font style="font-family:Times New Roman;">
        <h3>{{ $order->ordered_by->name }} {{ $order->ordered_by->last_name }}</h3>
        <h5>IMPORTANYTHING.PH</h5>
        <h5>{{ env('EMAIL_PHONE') }}</h5>
        <br>
        <img src="data:image/png;base64,{{DNS1D::getBarcodePNG( $order->id, 'C39')}}" alt="barcode" class="img-responsive" style="display: block; margin-left: auto; margin-right: auto; width: 50%;">
        <h5>PC {{ $i }} OF {{ $order->total_quantity() }}</h5>
      </font>
    </div>
  </div>
<br><br>
<div class="pagebreak"></div>
@endfor
</div>