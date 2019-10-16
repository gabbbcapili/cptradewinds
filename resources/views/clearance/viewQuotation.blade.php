<div class="modal-dialog modal-xl" role="document">
@if(request()->user()->isAdmin())
	  <form action="{{ action('ClearanceController@admin1QuotationStore', $clearance->id) }}" id="storeQuotation" method="POST" enctype="multipart/form-data">
	@method('put')
		@csrf	
		@endif
  <div class="modal-content">
    <div class="modal-header">
    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="modalTitle"> Customs Clearance Quotation(<b>(Reference No:</b> #{{ $clearance->id }}) </h4>
		</div>
	<div class="modal-body" >
	  <div class="row">
	    	<h1 align="center">PHP {{ number_format($clearance->admin3_price, 2 ) }}</h1>
	  </div>
	  @if(request()->user()->isAdmin())
	  <br><br>
	  <div class="row invoice-info">
		    <div class="col-sm-12 invoice-col">
		      <div class="form-group">
		      	<div class="col-sm-2"></div>
		      	<div class="col-sm-8">
		      	<label for="payment">Marked Up Amount:</label>
		      	<input type="text" name="admin1_price" id="admin1_price" class="form-control">
		      	</div>		      		
		      </div>
		    </div>
		  </div>
		
		@endif

	</div>
    <div class="modal-footer">
    	@if(request()->user()->isAdmin())
      <button class="btn btn-primary no-print" type="submit" class="btn_save"><i class="fa fa-save"></i> Save
      </button>

      @endif
      <button type="button" class="btn btn-default no-print" data-dismiss="modal">Close</button>
    </div>
  </div>
  @if(request()->user()->isAdmin())
</form>
@endif
</div>



@if(request()->user()->isAdmin())
<script type="text/javascript">
	$("#storeQuotation").submit(function(e) {
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
						window.location.replace("/clearance");
					}
			        if (data.error){
			        	$('.error').remove();
			        	$.each(data.error, function(index, val){
			        		console.log(index);
			        	$('#' + index).after('<label class="text-danger error">' + val + '</label>');
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
@endif
