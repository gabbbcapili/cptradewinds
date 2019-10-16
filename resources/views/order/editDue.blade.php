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
		      	<div class="col-sm-4">
		      		<label for="boxes_received">Number of Boxes Received:</label>
		    	  	<input type="number" name="boxes_received" id="boxes_received">		  
		      	</div>    		
		      </div>
		      <div class="form-group">
		      	<div class="col-sm-4">
		      		<label for="price">Due Amount:</label>
		      		<input type="text" value="{{ $order->price ? number_format($order->price, 2) : '00.00' }}" name="price" id="price">		  
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
	    	$('#boxes_received').focus()
	    	// #price
	    	$('#price').mask('000,000,000,000,000.00', {reverse: true});
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
