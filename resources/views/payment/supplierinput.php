	<div class="box box-solid">
			<div class="box-body">
			<h2 class="text-center">Our Rate for Today: 1$ = {{ number_format($dollar->rate, 2) }}PHP </h1>
			</div>
		</div> <!--box end-->
			<div class="box box-solid">
	    		<div class="container-fluid">
	    			<h3>Supplier Information:*</h3>
	    		</div>
			<div class="box-body">
				<div class="row">
					<div class="col-sm-4">
						<label for="bank_name">Bank Name:*</label>
						<input type="text" class="form-control" name="bank_name" id="bank_name">
					</div>
					<div class="col-sm-4">
						<label for="account_name">Account Name:*</label>
						<input type="text" class="form-control" name="account_name" id="account_name">
					</div>
					<div class="col-sm-4">
						<label for="swift_code">Bank SWIFT Code:*</label>
						<input type="text" name="swift_code" id="swift_code" class="form-control">

					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-6">
						<label for="bank_address">Bank Address:*</label>
						<textarea class="form-control" rows="5" name="bank_address" id="bank_address" style="resize:vertical;"></textarea>
					</div>
					<div class="col-sm-6">
						<label for="supplier_address">Supplier Address:*</label>
						<textarea class="form-control" rows="5" name="supplier_address" id="supplier_address" style="resize:vertical;"></textarea>
					</div>
				</div>
			</div>
		</div> <!--box end-->

		<div class="box box-solid">
	    		<div class="container-fluid">
	    			<h3>Order Details:*</h3>
	    		</div>
			<div class="box-body">
				<div class="row">
					<div class="col-sm-4">
						<label for="supplier">Proforma Invoice:*</label>
						<input type="file" class="form-control" name="invoice" id="invoice">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
									<label for="supplier">Total Amount:*</label>
									<div class="input-group">
									<span class="input-group-btn">
									<button type="button" class="btn btn-default bg-white btn-flat">$</button>
									</span>
									<input type="text" class="form-control" name="amount" id="amount">
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
									<label for="supplier">Equivalent(+1500 wire transfer fee):*</label>
									<div class="input-group">
									<span class="input-group-btn">
									<button type="button" class="btn btn-default bg-white btn-flat">PHP</button>
									</span>
									<input type="text" class="form-control" name="equivalent" id="equivalent" disabled>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div> <!--box end-->
