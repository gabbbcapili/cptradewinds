@extends('layouts.mail')

@section('title', 'Payment')


@section('content')

Hello, <br><br>

Importation Cost: {{ $order->price }}<br>
Delivery Price to {{ $order->delivery_address }}: {{ $order->delivery_price }} (COD) + Booking fee: {{ env('BOOKING_FEE') }}<br>

Sir David: Please revise email tempalate below:<br>

Here are the instructions for having us finance 50% of your order. Please make sure the steps are followed in order.
	<ol>
		<li>
		Review your supplier's Proforma Invoice (PI)
		</li>
		<li>
			Deposit to Banco De Oro PHP Account <b> HBK Global Trading 0020-7007-2653 </b> the combined following amounts:
			<ul>
				<li>
					50% of the total amount of the PI 
					<ul>
						<li>
							You will have to convert to peso. Today's exchange rate is: 
						</li>
					</ul>
				</li>
				P1,500 wire transfer fee
			</ul>
		</li>
		<li>
			Email us the following:
			<ul>
				<li>
					A copy of the deposit slip
				</li>
				<li>
					Proforma Invoice (PI) which contains your supplier's wire transfer details
					<ul>
						<li>Supplier's bank account name (must be a company name)</li>
						<li>Supplier's address</li>
						<li>Supplier's bank name</li>
						<li>Supplier's bank address</li>
						<li>Supplier's bank SWIFT code</li>
					</ul>
				</li>
			</ul>
		</li>
	</ol>

<p>
	Wire transfer cut off time is 2pm, Monday to Friday. You may deposit the funds to the account above anytime, but please note that the exchange rate above is valid only for today. 
</p><br>
<p>
	After we wire the payment, we will email you the transaction screenshot which you will then forward to your supplier. This will be their proof of your payment. 
</p><br>

<p>Your Order <b>Reference No</b>: <a href="{{ action('OrderController@edit', [$order->id]) }}">#{{ $order->id }} </a></p>

@endsection
