	$("#order_form").submit(function(e) {
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
						window.location.replace("/orders");
					}
					if (data.info){
						toastr.error(data.info);
					}
			        if (data.error){
			        	// console.log(data.error);
			        	$('.error').remove();
			        	$.each(data.error, function(index, val){
			        		//console.log(index + '\n' + val)
			        	$('input[id="'+ index +'"]').after('<label class="text-danger error">' + val + '</label>');
			        	if (index == "import_details"){
			        		$('#import_details').after('<label class="text-danger error">' + val + '</label>');
			        	}
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
			    }
			});
	});
