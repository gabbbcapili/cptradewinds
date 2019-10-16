<div class="modal-dialog modal-xl" role="document">
  <form action="{{ action('SettingController@update', $dollar->id) }}" id="ajax_form" method="POST">
  @csrf
  @method('put')
  <div class="modal-content">
    <div class="modal-header">
	    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    <h4 class="modal-title" id="modalTitle"> Dollar Rate
	    </h4>
	</div>
		<div class="modal-body">
		 <div class="row invoice-info">
		    <div class="col-sm-12 invoice-col">
		   		<h1 class="text-center">Current Rate: 1$ =  <b> {{ number_format($dollar->rate, 2) }} PHP</b></h1>
		    </div>
		  </div>
		  <hr>

		  <div class="row invoice-info">
		  	<div class="col-sm-4 invoice-col"></div>
		    <div class="col-sm-4 invoice-col">
		    	<div class="form-group">
			    	<label for="rate">Rate:</label>
			   		<input type="text" name="rate" id="rate" class="form-control">
		   		</div>
		    </div>
		  </div>
		</div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary no-print"><i class="fa fa-edit"></i> Update
      </button>
      <button type="button" class="btn btn-default no-print" data-dismiss="modal">Close</button>
    </div>
  </div>
 </form>
</div>


<script type="text/javascript">
	$("#ajax_form").submit(function(e) {
		e.preventDefault();
		 $('.btn_save').prop('disabled', true);
			window.swal({
				  title: "Checking...",
				  text: "Please wait",
			//	  imageUrl: "images/ajaxloader.gif",
				  button: false,
				  allowOutsideClick: false
				});
		// console.log($(this).serialize());
			$.ajax({
				url : $(this).attr('action'),
				type : 'POST',
				data : $(this).serialize(),
				success: function(data){
					if (data.success){
						console.log(data.success);
						//temporary, it should be $(this).reload(); 
						window.location.replace("{{ action('OrderController@index') }}");
					}
			        if (data.error){
			        	console.log(data.error);
			        	// console.log(data.error);
			        	$('.error').remove();
			        	$.each(data.error, function(index, val){
			        		//console.log(index + '\n' + val)
			        	$('input[id="'+ index +'"]').after('<label class="text-danger error">' + val + '</label>');
			        	$('select[id="'+ index +'"]').after('<label class="text-danger error">' + val + '</label>');
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
			        // success logic
			     },
			    error: function(jqXhr, json, errorThrown){
			    	console.log(jqXhr);
			    	console.log(json);
			    	console.log(errorThrown);
			    	$('.btn_save').prop('disabled', false);
			    	toastr.error('Sorry, something went wrong..')
			    }
			});
	});
</script>
