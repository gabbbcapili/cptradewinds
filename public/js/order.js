
	function addRow(){
		var row = parseInt($('#rowcount').val()) + 1;
		$('#rowcount').val(row);
		var html = '<tr><td><span class="sr_number">'+row+'</span></td>';
		html += '<td><input type="text" class="form-control input-sm" value="1" name="product['+row+'][qty]" id="product.'+row+'.qty" style="text-align: center;"></td>';
		html += '<td><select class="form-control input-table-select" id="product.'+row+'.measurement" name="product['+row+'][measurement]"><option value="cm">Centimeters</option><option value="m">Meters</option><option value="in">Inches</option><option value="ft">Feet</option></select></td>';
		html += '<td><input type="text" class="form-control input-sm" name="product['+row+'][length]" id="product.'+row+'.length"></td>';
		html += '<td><input type="text" class="form-control input-sm" name="product['+row+'][width]" id="product.'+row+'.width"></td>';
		html += '<td><input type="text" class="form-control input-sm" name="product['+row+'][height]" id="product.'+row+'.height"></td>';
		html += '<td><input type="text" class="form-control input-sm" name="product['+row+'][weight]" id="product.'+row+'.weight"></td>';
		html += '<td><i class="fa fa-times remove_order_entry_row text-danger" title="Remove" style="cursor:pointer;"></i></td></tr>';
		$('#orders_table tr:last').after(html);
		update_table_sr_number();
	}


	$( document ).ready(function() {
  		addRow();
	});

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
					if (data.success){
						console.log(data.success);
						if('{{ $request->segment(1) }}' == 'quotation'){
							window.location.replace("/quotation");
						}else{
							window.location.replace("/orders");
						}
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


	$('input[type=radio][name=user_type]').change(function() {
    if (this.value == 'buyer') {
        $('#import_details_label').text('importing');
    }
    else if (this.value == 'supplier') {
        $('#import_details_label').text('shipping');
    }
});

$("#location").change(function() {
	var warehouse = $('#warehouse'),
	value = this.value;
 	warehouse.find('option').remove().end();
 	console.log(value);
 	 if (value == "China"){
 		warehouse.append('<option value="Guangzhou: 广州市白云区石门街石井大道滘心路段自编1号。中北仓储园N1-15 (导航：中北仓储园)">Guangzhou: 广州市白云区石门街石井大道滘心路段自编1号。中北仓储园N1-15 (导航：中北仓储园)</option>');
 		warehouse.append('<option value="Yiwu:义乌江东山口小区115栋2号">Yiwu:义乌江东山口小区115栋2号</option>');
 	}
 	if (value == "HongKong"){
 		warehouse.append('<option value="North Point">North Point</option>');
 	}
 	if (value == "Taiwan"){
 		warehouse.append('<option value="Taipei">Taipei</option>');
 	}
 	if (value == "Thailand"){
 		warehouse.append('<option value="Rajatevee">Rajatevee</option>');
 		warehouse.append('<option value="Bangkok">Bangkok</option>');
 	}
});
