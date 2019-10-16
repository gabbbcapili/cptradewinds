	<form action="{{ action('OrderController@storePayment', $order->id) }}" class="form" method="POST" enctype='multipart/form-data'>
	@method('put')
		@csrf
	<div class="box box-solid">
		<div class="box-body">
			<div class="row">
				<div class="container-fluid">
					<h3 id="a">Proof of Shipment:</h3>
				</div>
			</div>
			<br>
				<div class="row text-center center-div nextStep">
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


