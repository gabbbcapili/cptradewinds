@extends('layouts.base')
@section('title', 'Orders Edit')


@section('content')

   <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Order #{{ $order->id }}
      </h1>
    </section>

    <section class="content">
    	<!-- Details -->
		@include('order/forms/supplier/details')
		@if(request()->user()->isSupplier())
			@if($order->status == 3)
	          <!-- Add Type -->
	          @include('order/forms/supplier/addType')
	          <!-- Add Type -->
	         @endif
	         @if($order->status == 6)
	          <!-- Shipping Marks -->
	          @include('order/forms/supplier/shippingMark')
	          <!-- Shipping Marks -->
	         @endif
	         @if($order->status == 7)
	         	<!-- Upload Shipping -->
				@include('order/forms/supplier/uploadShipping')
				<!-- Upload Shipping -->
	         @endif
	    @endif
	    @if(request()->user()->isCustomer())
	    	@if($order->status == 11)
	         	<!-- Upload Shipping -->
				@include('order/forms/customer/addPayment')
				<!-- Upload Shipping -->
	         @endif
	    @endif
    </section>
@endsection

@section('javascript')
<!-- update sr number on load -->
<script type="text/javascript">
	$(document).ready(function(){
		update_table_sr_number();
	});
</script>

<script type="text/javascript">
	function addRow(){
		var row = parseInt($('#rowcount').val()) + 1;
		$('#rowcount').val(row);
		console.log(row);
		var html = '<tr><td><span class="sr_number">'+row+'</span></td>';
		html += '<td><input type="text" class="form-control input-sm" value="1" name="product['+row+'][qty]" id="product.'+row+'.qty" style="text-align: center;"></td>';
		html += '<td> <select class="form-control" id="product.'+row+'.type" name="product['+row+'][type]"><option value="Palletized Cargo">Palletized Cargo</option><option value="Carton Boxes">Carton Boxes</option> <option value="Sacks">Sacks</option> <option value="Drums">Drums</option><option value="Wooden Crates">Wooden Crates</option><option value="Other">Other</option></select></td>'
		html += '<td><i class="fa fa-times remove_order_entry_row text-danger" title="Remove" style="cursor:pointer;"></i></td></tr>';
		$('#orders_table tr:last').after(html);
		update_table_sr_number();
	}
</script>

<script type="text/javascript">
	if('{{ $order->status }}' != 3){
		 $("html, body").animate({ scrollTop: 1000  }, 1500);
	}
$(document).on( 'click', '.remove_order_entry_row', function(){
	$(this).closest('tr').remove();
	update_table_sr_number();
});

function update_table_sr_number(){
    var sr_number = 1;
    $('table#orders_table tbody').find('.sr_number').each( function(){
        $(this).text(sr_number);
        sr_number++;
    });
}

</script>
<script type="text/javascript">
	$(".form").submit(function(e) {
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
					if(data.status){
						toastr.error(data.status);
					}
					if (data.success){
						if(data.redirect){
							window.location.href = data.redirect;
						}else{
							window.location.href = '{{ action("OrderController@edit" , [$order->id]) }}';
						}
					}
			        if (data.error){
			        	console.log(data.error);
			        	toastr.error('Please check for errors.');
			        	$('.error').remove();
			        	$.each(data.error, function(index, val){
			        	$('[id="'+ index +'"]').after('<label class="text-danger error">' + val + '</label>');
			        	});

			        	 setTimeout(() => {
						  window.swal({
						    title: "Something's not right..",
						    button: false,
						    timer: 1000
						  });
						}, 500);
			        }
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

	$('.confirmation').click(function(){
      swal({
          title: $(this).data('title'),
          text:  $(this).data('text'),
          icon: "warning",
          buttons: true,
          dangerMode: true,
        }).then((willDelete) => {
        if (willDelete) {
          $.ajax({
            method: "GET",
            url: $(this).data('href'),
            dataType: "json",
            success: function(data){
              console.log(data.error);
              if (data.error){
                toastr.error(data.error);
              }
              if (data.success){
                window.location.replace("{{ action('OrderController@show', [$order->id] ) }}");
              }
            },
            error: function(jqXhr, json, errorThrown){
            console.log(jqXhr);
            console.log(json);
            console.log(errorThrown);
            toastr.error('Sorry, Something went wrong. Please try again later.');
          }
          });
        }
      });
    });  
</script>

@endsection


