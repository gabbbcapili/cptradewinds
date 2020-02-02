@extends('layouts.mail')

@section('title', 'Payment Services Bank Details')


@section('content')
	Email Need revision below:
	a.	ALL PHP Deposit. Deposit the converted amount (using the exchange rate set by Admin 2A) plus the P1500 together to our PHP account
	b.	USD and PHP Deposit. Deposit USD to our dollar account for the invoice. Deposit the P1500 to our PHP account for the wire transfer fee. 

	Hello there! Please deposit a total of <b>{{ number_format($payment->total_amount(), 2)}}</b> to: <br>
	BDO Account Number 0020-7007-2653, HBK Global Trading <br>
		
	For your payment service transaction <a href="{{ action('PaymentController@index') }}">#{{ $payment->id }}</a>
	<br>
@endsection
