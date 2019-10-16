@extends('layouts.base')
@section('title', 'Create Customs Clearance')
@section('content')
   <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Customs clearance
      </h1>
    </section>
<form action="{{ action('ClearanceController@store') }}" class="clearance_form" method="POST" data-redirect="{{ action('ClearanceController@index') }}" enctype="multipart/form-data">
		@csrf
    <section class="content">

			<div class="box box-solid">
	    		<div class="container-fluid">
	    			<h3>Customer Details:*</h3>
	    		</div>
			<div class="box-body">
				<div class="row">
					<div class="col-sm-4">
						<label for="fullname">Full Name:*</label>
						<input type="text" class="form-control" name="fullname" id="fullname">
					</div>
					<div class="col-sm-4">
						<label for="email">Email Address:*</label>
						<input type="email" class="form-control" name="email" id="email">
					</div>
					<div class="col-sm-4">
						<label for="mobile_number">Mobile Number:*</label>
						<input type="number" class="form-control" id="mobile_number" name="mobile_number" id="mobile_number">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-8">
						<label for="delivery_address">Delivery Address:*</label>
						<textarea class="form-control" rows="5" name="delivery_address" id="delivery_address"></textarea>
					</div>
				</div>
			</div>
		</div> <!--box end-->


		<div class="box box-solid">
	    		<div class="container-fluid">
	    			<h3>Shipment Details:*</h3>
	    		</div>
			<div class="box-body">
				<div class="row">
					<div class="col-sm-4">
						<label for="shipping_company">Shipping Company:*</label>
						<input type="text" class="form-control" name="shipping_company" id="shipping_company">
					</div>
					<div class="col-sm-4">
						<label for="supplier_name">Supplier Name:*</label>
						<input type="text" class="form-control" name="supplier_name" id="supplier_name">
					</div>
					<div class="col-sm-4">
						<label for="supplier_email">Supplier Email:*</label>
						<input type="text" class="form-control" id="supplier_email" name="supplier_email">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-4">
						<label for="supplier">Proforma Invoice:*</label>
						<input type="file" class="form-control" name="invoice" id="invoice">
					</div>
					<div class="col-sm-4">
						<label for="supplier">Waybill:*</label>
						<input type="file" class="form-control" name="waybill" id="waybill">
					</div>
				</div>
				<div style="height:25px"></div>
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
<script src="{{ asset('app/clearance.js') }}"></script>
@endsection


