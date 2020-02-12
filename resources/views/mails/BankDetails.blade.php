@extends('layouts.mail')

@section('title', 'Payment Services Bank Details')


@section('content')
	
	Hello there! Please deposit a total of <b>{{ number_format($payment->total_amount(), 2)}}</b> to: <br>
	BDO Account:

	 USD account : HBK Global Trading - 107640001487 <br>

	 PHP account : HBK Global Trading - 2070072653 <br>
		
	For your payment service transaction <a href="{{ action('PaymentController@index') }}">#{{ $payment->id }}</a>

	You can pay us by:<br>
	a.	ALL PHP Deposit. Deposit the amount {{ number_format($payment->total_amount(), 2)}} together to our PHP account <br>
	b.	USD and PHP Deposit. Deposit USD to our dollar account with the amount of ${{ $payment->amount }}. Deposit the P1500 to our PHP account for the wire transfer fee. <br>

	<br>
@endsection
