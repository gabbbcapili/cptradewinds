<div class="modal-dialog modal-xl" role="document">
	<form action="{{ action('UserController@update') }}" id="edit_form" method="POST">
	@method('put')
		@csrf	
  <div class="modal-content">
    <div class="modal-header">
	    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <h4 class="modal-title" id="modalTitle"> Update profile - <b>#{{ $user->id }}</b>
	    </h4>
	</div>
		<div class="modal-body" >
	<!-- 	 <div class="row invoice-info">
		    <div class="col-sm-6 invoice-col">
		      <div class="form-group">
		      	<label for="warehouse"> Firstname:</label>
		      		<input class="form-control" type="text" name="name" value="{{ $user->name }}">
		      </div>
		    </div>
		    <div class="col-sm-6 invoice-col">
		      <div class="form-group">
		      	<label for="warehouse"> Lastname:</label>
		      		<input class="form-control" type="text" name="last_name" value="{{ $user->last_name }}">
		      </div>
		    </div>
		  </div> -->
		  <div class="row invoice-info">
		  	<div class="col-sm-6 invoice-col">
		      <div class="form-group">
		      	<label for="warehouse"> Mobile Number:</label>
		      		<input class="form-control" type="text" name="phone_no" value="{{ $user->phone_no }}">
		      </div>
		    </div>

		    <div class="col-sm-6 invoice-col">
		      <div class="form-group">
		      	<label for="warehouse"> City:</label>
		      		<input class="form-control" type="text" name="city" value="{{ $user->city }}" list="cities">
		      		<datalist id="cities">
	                    @foreach($cities as $city)
	                        <option value="{{ $city }}">
	                    @endforeach
	                </datalist>
		      </div>
		    </div>


		  </div>

		</div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary no-print"><i class="fa fa-save"></i> Save
      </button>
      <button type="button" class="btn btn-default no-print" data-dismiss="modal">Close</button>
    </div>
</form>
  </div>
</div>


<script type="text/javascript">
	$("#edit_form").submit(function(e) {
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
						window.location.replace("/profile");
					}
			        if (data.error){
			        	$('.error').remove();
			        	$.each(data.error, function(index, val){
			        		console.log(index);
			        	$('[name="'+ index +'"]').after('<label class="text-danger error">' + val + '</label>');
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