	<form action="{{ action('OrderController@StorePhotos', $order->id) }}" class="form" method="POST" enctype='multipart/form-data'>
	@method('put')
		@csrf
	<div class="box box-solid">
		<div class="box-body">
			<div class="row">
				<div class="container-fluid  text-center">
					<h2>Step 2</h2>
					<h3 id="a">Shipping Mark:</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					When you click the button below, we will generate a single or multiple copies of this order’s shipping mark depending on the total number of cargo pieces you indicated in step 1. Each sheet is numbered (example 1/5 meaning one out of five pieces) and is to be <u> attached to every cargo piece.</u>
					<br>
					<font style="font-family:Times New Roman;">
						    INSTRUCTIONS
			        <ol>
			          <li>PLEASE PRINT AND ATTACH THIS MARK <u>ON ALL</u> BOXES BEFORE SHIPPING</li>
			          <li>PLEASE MAKE SURE THE MARK IS SECURELY ATTACHED TO EACH BOX. <u> APPLY CLEAR TAPE IF POSSIBLE FOR ADDED PROTECTION</u></li>
			        </ol>
			      </font>
				</div>
			</div>
			<br>
			<div class="row center-div text-center">
				<!-- <div class="col-sm-4"> -->
						<div class="form-check">
						    <input type="checkbox" class="form-check-input" id="iUnderstand">
						    <label class="form-check-label" for="iUnderstand">I understand</label>
						  </div>
					<!-- </div> -->
			</div>
			<br>
			<div class="row center-div text-center nextStep">
					<a href="#" class="btn btn-primary ShippingMark" data-href="{{ action('OrderController@showMark', $order->id) }}">Generate Shipping Mark</a>	
			</div>
			<br>
		<!-- 	<div class="col-sm-4"> -->
				<div class="row text-center center-div nextStep">
					<label for="invoice">Boxes Picture(s):* </label> &nbsp<i class="fa fa-info-circle text-info hover-q" data-toggle="tooltip" data-placement="top" title="While holding down the Ctrl key, click each of the other files you want to include." style="cursor:help"></i>
					<input type="file" class="form-control" name="boxes[]" multiple id="boxes" multiple="multiple" required>
					<small>While holding down the Ctrl key, click each of the other files you want to include.</small>
					</div>
				<div id="preview"></div>
				<!-- </div> -->
            </div>   <!-- /.box-body -->
          </div> <!-- box-end -->
    

	<div class="box box-solid nextStep">
		<div class="box-body">
			<br>
			<button class="btn btn-primary pull-right btn_save">Step complete</button>
		</div>
	</div> <!--box end-->
	</form> <!-- adding boxes pictures-->



@section('javascript2')
	<script type="text/javascript">
		$(".nextStep").hide();
		$("#iUnderstand").change(function() {
			value = $('#iUnderstand').is(":checked");
		 	if (value == true){
		 		$(".nextStep").show();
		 	}else{
		 		$(".nextStep").hide();
		 	}
		});


$(document).on("click", '.ShippingMark', function(){
        var url = $(this).data("href");
        var container = $(".view_modal");
        document.title = "Shipping Mark - Shipment : {{ $order->shipment_id }}";
        $.ajax({
            method: "GET",
            url: url,
            dataType: "html",
            success: function(result){
              if (result.status){
                toastr.error(result.status);
              }else{
                $(container).html(result).modal("show");
                 history.replaceState(history.state, '', '/Shipping Mark : {{ $order->shipment_id }}');
              }
            },
            error: function(jqXhr, json, errorThrown){
            console.log(jqXhr);
            console.log(json);
            console.log(errorThrown);
          }
        });
    });
	</script>
	<script>
	function previewImages() {

		var $preview = $('#preview').empty();
  if (this.files) $.each(this.files, readAndPreview);

  function readAndPreview(i, file) {
    
    if (!/\.(jpe?g|png|gif)$/i.test(file.name)){
      return alert(file.name +" is not an image");
    } // else...
    
    var reader = new FileReader();

    $(reader).on("load", function() {
      $preview.append($("<img/>", {src:this.result, style:"margin:25px 25px 25px 25px; height:300px; width:330px"}));
    });

    reader.readAsDataURL(file);
    
  }

}

$('#boxes').on("change", previewImages);
	</script>
	
@endsection



