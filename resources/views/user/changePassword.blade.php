<div class="modal-dialog modal-xl" role="document">
	<form action="{{ action('UserController@changePasswordUpdate') }}" id="changePW" method="POST" enctype="multipart/form-data">
	@method('put')
		@csrf	
  <div class="modal-content">
    <div class="modal-header">
	    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <h4 class="modal-title" id="modalTitle"> User - Change Password
	    </h4>
	</div>
		<div class="modal-body" >
		 <div class="row invoice-info">
		    <div class="col-sm-4 invoice-col">
		      <div class="form-group">
		      	<label for="payment">Current Password:</label>
		      	<input type="password" name="CurrentPassword" id="CurrentPassword" class="form-control">		      		
		      </div>
		    </div>
		  </div>
		  <div class="row invoice-info">
		    <div class="col-sm-4 invoice-col">
		      <div class="form-group">
		      	<label for="payment">New Password:</label>
		      	<input type="password" name="NewPassword" id="NewPassword" class="form-control">		      		
		      </div>
		    </div>
		    <div class="col-sm-4 invoice-col">
		      <div class="form-group">
		      	<label for="payment">Confirm Password:</label>
		      	<input type="password" name="ConfirmPassword" id="ConfirmPassword" class="form-control">		      		
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
	$("#changePW").submit(function(e) {
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
				data : $(this).serialize(),
				success: function(data){
					console.log(data);
					if(data.status){
						toastr.error(data.status);
					}
					if (data.success){
						toastr.success(data.success);
						$('.view_modal ').modal('hide');
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
						    timer: 300
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
