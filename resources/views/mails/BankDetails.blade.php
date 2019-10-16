@extends('layouts.mail')

@section('title', 'Payment Services Bank Details')


@section('content')
	Hello there! Please deposit a total of <b>{{ number_format($payment->total_amount(), 2)}}</b> to: <br>
	BDO Account Number 0020-7007-2653, HBK Global Trading <br>
	
	For your payment service transaction <a href="{{ action('PaymentController@index') }}">#{{ $payment->id }}</a>
	<br>
@endsection
