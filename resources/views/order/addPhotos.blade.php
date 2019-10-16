@extends('layouts.base')
@section('title', 'Orders Add Photos')
@section('content')
   <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Order Photos #{{ $order->id }}
      </h1>
    </section>
<form action="{{ action('OrderController@StorePhotos', $order->id) }}" id="order_form" method="POST" enctype='multipart/form-data'>
	@method('put')
		@csrf
    <section class="content">
    	<div class="box box-solid">
		<div class="box-body">
				<div class="col-sm-4">
					<div class="row">
						<label for="invoice">Boxes Picture(s):* </label> &nbsp<i class="fa fa-info-circle text-info hover-q" data-toggle="tooltip" data-placement="top" title="While holding down the Ctrl key, click each of the other files you want to include." style="cursor:help"></i>
						<input type="file" class="form-control" name="boxes[]" id="boxes" multiple="multiple">
						<small>While holding down the Ctrl key, click each of the other files you want to include.</small>
						</div>
				</div>
			</div>
		</div> <!--box end-->
		<div class="box box-solid">
		<div class="box-body">
			<br>
			<button class="btn btn-primary pull-right btn_save">Save</button>
		</div>
	</div> <!--box end-->
    </section>
</form>
@endsection

@section('javascript')
<script type="text/javascript">
	$("#order_form").submit(function(e) {
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
			        	$('input[id="'+ index +'"]').after('<label class="text-danger error">' + val + '</label>');
			        	if(index.startsWith("boxes")){
			        		$('#boxes').after('<label class="text-danger error">' + val + '</label>');
			        	}
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
@endsection


