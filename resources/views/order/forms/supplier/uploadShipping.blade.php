	<form action="{{ action('OrderController@storeProofOfShipment', $order->id) }}" class="form" method="POST" enctype='multipart/form-data'>
	@method('put')
		@csrf
	<div class="box box-solid">
		<div class="box-body">
			<div class="row">
				<div class="container-fluid text-center">
					<h2>Step 3</h2>
					<h3 id="a">Proof of Shipment:</h3>
				</div>
			</div>
			<br>
				<div class="row text-center center-div nextStep" id="preview">
					<label for="shipment_proof">Upload Proof of Shipment:</label>
		      		<input type="file" name="shipment_proof" id="shipment_proof" class="form-control" accept="image/*" onchange="loadFile(event)" required>
						<img id="output"/>	
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
	
	<script>
	var loadFile = function(event) {
		var output = document.getElementById('output');
		output.height = 300;
		output.width = 330
		output.src = URL.createObjectURL(event.target.files[0]);
  };
	</script>






