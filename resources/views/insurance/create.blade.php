	@extends('layouts.base')
@section('title', 'Create Insurance Service')
@section('content')
   <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Insurance Service
      </h1>
    </section>
<form action="{{ action('InsuranceController@store') }}" class="payment_form" method="POST" data-redirect="{{ action('InsuranceController@index') }}" enctype="multipart/form-data">
		@csrf
    <section class="content">
			<div class="box box-solid">
	    		<div class="container-fluid">
	    			<h3>Insurance Details:*</h3>
	    		</div>
			<div class="box-body">
				<div class="row">
					<div class="col-sm-4">
						<label for="bank_name">Choose Transaction:*</label>
						<select class="form-control select2" id="order_id" name="order_id">
							@foreach($orders as $order)
							<option value="{{ $order->id }}"> Transaction: #{{ $order->id }}, &nbsp {{ $order->supplier_by ? $order->supplier_by->name : '' }} </option>
							@endforeach
						</select>
					</div>
					<div class="col-sm-4">
						<label for="bank_name">Invoice:*</label>
						<input type="file" name="invoice" id="invoice">
					</div>
					<div class="col-sm-4">
						<label for="bank_name">Insurance Declaration / Application:*</label>
						<input type="file" name="declaration" id="declaration">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-4">
						<label for="order_id">Amount to Insure:</label>
						<input type="number" class="form-control" name="amount" id="amount">
					</div>
					<div class="col-sm-4">
						<label for="order_id">Total Insurance Fee:</label>
						<input type="text" class="form-control" id="fee" name="fee" disabled>
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
<script src="{{ asset('app/insurance.js') }}"></script>
@endsection


