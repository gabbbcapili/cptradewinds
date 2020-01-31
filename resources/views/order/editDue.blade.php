<div class="modal-dialog modal-xl" role="document">
	<form action="{{ action('OrderController@updateDue', $order->id) }}" id="updateDue" method="POST" enctype="multipart/form-data">
	@method('put')
		@csrf	
  <div class="modal-content">
    <div class="modal-header">
	    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <h4 class="modal-title" id="modalTitle"> Order - Update Details(<b>(Reference No:</b> #{{ $order->shipment_id }})
	    </h4>
	</div>
		<div class="modal-body" >
			<div class="row invoice-info">
				<div class="col-sm-12 invoice-col">
					<div class="form-group">
						<label>Customer's Delivery Address</label>
						<textarea class="form-control" disabled>{{ $order->delivery_address }}</textarea>
					</div>
				</div>
			</div>
			<hr>
			<div class="row invoice-info">
				<div class="col-sm-12 invoice-col">
					<div class="form-group">
						<h4><b>Received Measurements:</b></h4>
					</div>
				</div>
			</div>
			<div class="row invoice-info">
				<div class="col-sm-4 invoice-col">
					<div class="form-group">
						<label>Total Length:*</label>
						<input type="number" name="length" id="length" class="form-control cbm">
					</div>
				</div>
				<div class="col-sm-4 invoice-col">
					<div class="form-group">
						<label>Total Width:*</label>
						<input type="number" name="width" id="width" class="form-control cbm">
					</div>
				</div>
				<div class="col-sm-4 invoice-col">
					<div class="form-group">
						<label>Total Height:*</label>
						<input type="number" name="height" id="height" class="form-control cbm">
					</div>
				</div>
			</div>
			<div class="row invoice-info">
				<div class="col-sm-4 invoice-col">
					<div class="form-group">
						<label>Total CBM</label>
						<input type="text" name="cbm" id="cbm" class="form-control" readonly value="{{ $order->boxes_received }}">
					</div>
				</div>
			</div>

			<hr>
		  <div class="row invoice-info">
				<div class="col-sm-12 invoice-col">
					<div class="form-group">
						<h4><b>Pick-up Rate:</b></h4>
					</div>
				</div>
			</div>
			<div class="row invoice-info">
		    <div class="col-sm-12 invoice-col">
		      <div class="form-group">
		      	<div class="col-sm-4">
		      	</div>    		
		      </div>
		      <div class="form-group">
		      	<div class="col-sm-4">
		      		<label for="price">Importation Cost:</label>
		      		<input type="text" class="form-control" value="{{ $order->price ? number_format($order->price, 2) : '00.00' }}" name="price" id="price">		  
		      	</div>    		
		      </div>
		      <div class="form-group">
		      	<div class="col-sm-4">
		      	<label>Pick-up Location:*</label>
						<select name="pickup_location" id="pickup_location" class="form-control">
							<option value="tondo" {{ $order->pickup_location == 'tondo' ? 'selected' : '' }}>Tondo</option>
							<option value="cubao" {{ $order->pickup_location == 'cubao' ? 'selected' : '' }}>Cubao</option>
						</select>	  
		      	</div>    		
		      </div>
		    </div>
		  </div>
			<hr>
			<div class="row invoice-info">
				<div class="col-sm-12 invoice-col">
					<div class="form-group">
						<h4><b>Delivery Rate:</b></h4>
					</div>
				</div>
			</div>
			<div class="row invoice-info">
				<div class="form-group">
					<div class="col-sm-4 invoice-col">
						<label>Local Transportation Fee:</label>
						<input type="text" name="delivery_price" id="delivery_price" class="form-control" value="{{ $order->delivery_price ? number_format($order->delivery_price, 2) : '00.00' }}">
					</div>
				</div>
			</div>
		</div>
    <div class="modal-footer">
      <button class="btn btn-primary no-print" type="submit" class="btn_save"><i class="fa fa-save"></i> Save
      </button>
      <button type="button" class="btn btn-default no-print" data-dismiss="modal">Close</button>
    </div>
</form>
  </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.view_modal').on('shown.bs.modal', function() {
	    	$('#length').focus();
	    	// #price
	    	$('#price').mask('000,000,000,000,000.00', {reverse: true});
	    	$('#delivery_price').mask('000,000,000,000,000.00', {reverse: true});

	 	});
		
		$('.cbm').change(function(){

			var total = 0;
			total = parseFloat($('#length').val()) + parseFloat($('#width').val()) + parseFloat($('#height').val());
			total = (total / 139).toFixed(2);
			$('input[name=cbm]').val(total);
		});

	});
	$("#updateDue").submit(function(e) {
		e.preventDefault();
		 $('.btn_save').prop('disabled', true);
			window.swal({
				  title: "Checking...",
				  text: "Please wait",
				  button: false,
				  allowOutsideClick: false
				});
			$.ajax({
				url : $(this).attr('action'),
				type : 'POST',
				data: new FormData(this),
			    processData: false,
			    contentType: false,
				success: function(data){
					console.log(data);
					if(data.status){
						toastr.error(data.status);
					}
					if (data.success){
						window.location.replace("/orders");
					}
			        if (data.error){
			        	$('.error').remove();
			        	$.each(data.error, function(index, val){
			        		console.log(index);
			        	$('[id="'+ index +'"]').after('<label class="text-danger error">' + val + '</label>');
			        	});
			        }
			        setTimeout(() => {
						  window.swal({
						    title: "Something's not right..",
						    button: false,
						    timer: 1000
						  });
						}, 500);
			      	 $('.btn_save').prop('disabled', false);
			     },
			    error: function(jqXhr, json, errorThrown){
			    	console.log(jqXhr);
			    	console.log(json);
			    	console.log(errorThrown);
			    	$('.btn_save').prop('disabled', false);
			    }
			});
	});
</script>
