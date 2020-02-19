<div class="box box-solid">
    		<div class="box-body">
    			<div class="col-sm-4">
					<label for="supplier">Im a:*</label>
					<div class="radio">
						<label><input type="radio" name="user_type" value="buyer"> Buyer </label>
  						<label><input type="radio" name="user_type" value="supplier"> Supplier</label>
  						<input type="hidden" id="user_type">
  					</div>
				</div>
    		</div>
    	</div><!-- boxend -->
    	

    	<div class="box box-solid">
    		<div class="container-fluid">
    			<h3>Buyer Details:*</h3>
    		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-sm-4">
					<label for="supplier">Buyer First Name:*</label>
					<input type="text" class="form-control is-invalid" name="buyer_name" id="buyer_name">
				</div>
				<div class="col-sm-4">
					<label for="supplier">Buyer Last Name:*</label>
					<input type="text" class="form-control is-invalid" name="buyer_last_name" id="buyer_last_name">
				</div>
				<div class="col-sm-4">
					<label for="supplier">Buyer Email:*</label>
					<input type="text" class="form-control" name="buyer_email" id="buyer_email">
				</div>
			</div>
			<br>
			<div class="row">
					<div class="col-sm-4">
						<label for="supplier">Buyer Mobile Number:*</label>
						<input type="text" class="form-control" name="buyer_mobile_number" id="buyer_mobile_number">
					</div>
				</div>
			<br>
		</div>
	</div> <!--box end-->

	<div class="box box-solid">
	    		<div class="container-fluid">
	    			<h3>Supplier Details:*</h3>
	    		</div>
			<div class="box-body">
				<div class="row">
					<div class="col-sm-4">
						<label for="supplier">Supplier Name:*</label>
						<input type="text" class="form-control" name="supplier" id="supplier">
					</div>
					<div class="col-sm-4">
						<label for="supplier">Supplier Last Name:*</label>
						<input type="text" class="form-control" name="supplier_last_name" id="supplier_last_name">
					</div>
				<div class="col-sm-4">
					<label for="supplier">Supplier Email:*</label>
					<input type="text" class="form-control" name="email" id="email">
				</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-4">	
						<label for="invoice">Invoice/Reference Number: </label>
						<input type="text" class="form-control" name="invoice_no" id="invoice_no">
					</div>
					<div class="col-sm-4">
						<label for="location">Supplier Location:*</label>
						<select name="location" id="location" class="form-control">
							<option value="">Please Select</option>
							<option value="China">China</option>
							<option value="HongKong">Hong Kong</option>
							<option value="Taiwan">Taiwan</option>
							<option value="Thailand">Thailand</option>
						</select> 
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-4" style="display:none">
						<label>Warehouse:*</label>
						<select name="warehouse" id="warehouse" class="form-control">
							<option value="">Please Select</option>
						</select>
					</div>
					<div class="col-sm-4" style="display:none">
						<label>Pick-up Location:*</label>
						<select name="pickup_location" id="pickup_location" class="form-control">
							<option value="tondo">Tondo</option>
							<option value="cubao">Cubao</option>
						</select>
					</div>
				</div><br>

				<!-- <div class="row">
					<div class="col-sm-8">
						<label for="address">What are you importing?:*</label>
						<textarea class="form-control" rows="5" name="import_details" id="import_details" style="resize:vertical;" placeholder='Please be specific. For example, you may write “Building Blocks” instead of “Toys”'></textarea>
					</div>
				</div> -->
			</div>
		</div> <!--box end-->
