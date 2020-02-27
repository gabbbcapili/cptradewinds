	<form action="{{ action('OrderController@storePayment', $order->id) }}" class="form" method="POST" enctype='multipart/form-data'>
	@method('put')
		@csrf
	<div class="box box-solid">
		<div class="box-body">
			<div class="row">
				<div class="container-fluid">
					<h3 id="a">Proof of Payment:</h3>
				</div>
			</div>
			<br>

				<div class="row text-center center-div nextStep">
					<p>The approximate local transport fee is P{{ number_format($order->delivery_price, 2) }} to {{ $order->delivery_address }}. This amount is only an estimate and is payable to the shipping company. This booking service is provided by us free of charge. What would you like to do?</p>
					<div class="radio">
					<label><input type="radio" name="pickup_type" value="pickup" checked>Arrange pickup/shipment myself (P{{ number_format($order->price, 2) }})</label>
					</div>
					<div class="radio">
					  <label><input type="radio" name="pickup_type" value="deliver">Avail booking of local transport (P{{ number_format( $order->price + env('BOOKING_FEE'), 2) }}) + (P{{ number_format($order->delivery_price, 2) }} Payable via COD)</label>
					</div>
					<div class="pickup_details">
						<hr>
						<h4>Pick up Details</h4>
						<label>Person/Company to pick up:</label>
						<input type="text" class="form-control" name="pickup_person" id="pickup_person"><br>
						<label>Approximate time of pickup:</label>
						<input type="text" class="form-control" name="pickup_time" id="pickup_time">
					</div>
					<hr>
					<label for="payment">Upload Payment Photo:</label>
		      		<input type="file" class="form-control" name="payment" id="payment">	
					</div>
            </div>   <!-- /.box-body -->
          </div> <!-- box-end -->
    

	<div class="box box-solid nextStep">
		<div class="box-body">
			<br>
			<button class="btn btn-primary pull-right btn_save">Step complete</button>
		</div>
	</div> <!--box end-->
	</form> <!-- adding boxes pictures-->
@section('javascript2')
	<script type="text/javascript">
	$('.form input[name=pickup_type]').on('change', function() {
	   var type = $('input[name=pickup_type]:checked', '.form').val();
	   if(type == "deliver"){
	   		$(".pickup_details").slideUp();
	   }else if(type == "pickup"){
	   	$(".pickup_details").slideDown();
	   }
	});
	</script>
@endsection

