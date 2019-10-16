<div class="modal-dialog modal-xl" role="document">
	<form action="{{ action('OrderController@storePayment', $order->id) }}" id="storePayment" method="POST" enctype="multipart/form-data">
	@method('put')
		@csrf	
  <div class="modal-content">
    <div class="modal-header">
	    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <h4 class="modal-title" id="modalTitle"> Order - Add Payment(<b>(Reference No:</b> #{{ $order->shipment_id }})
	    </h4>
	</div>
		<div class="modal-body" >
		 <div class="row invoice-info">
		    <div class="col-sm-12 invoice-col">
		      <div class="form-group">
		      	<div class="col-sm-2"></div>
		      	<div class="col-sm-8">
		      	<label for="payment">Payment Photo:</label>
		      	<input type="file" name="payment" id="payment">		  
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
	$("#storePayment").submit(function(e) {
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
			        	$('input[id="'+ index +'"]').after('<label class="text-danger error">' + val + '</label>');
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
