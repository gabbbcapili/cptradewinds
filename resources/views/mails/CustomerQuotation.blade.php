@extends('layouts.mail')

@section('title', 'Quotation')


@section('content')
Hello!
<br>
Based on the specs you provided, your <b>discounted</b> total importation cost is <br>
<br>
<p align="center">
<font style="color:rgb(255,153,0);font-weight:bold">P {{ number_format($order->price, 2) }} <br>
when you leave a review on our FB Page
<br>
 </font>
<b>or P {{ number_format($order->price, 2) }} without the review</b>
</p>
<p>This includes everything from freight to customs clearance, and this is the exact amount you'd have to pay if the actual measurements (taken upon your shipment's arrival) match the figures you provided for the quotation. <br>

Please note that your relative shipping cost per unit will go down as your volume increases.</p><br>

<u>Two things required for a successful importation:</u>
<ol>
	<li>Your supplier has to <font style="color:rgb(255,153,0);font-weight:bold"> attach our shipping mark </font>to every piece of your shipment</li>
	<li>Your supplier must <font style="color:rgb(255,153,0);font-weight:bold"> ship to our warehouse</font>. You must inform your supplier that you have an agent (that's us) who will handle the entire export-import process as well as all paperwork. Warehouse Locations:
		<ul>
			<li>China: Guangzhou and Yiwu</li>
			<li>Thailand: Rajatevee, Bangkok</li>
			<li>Taiwan: Taipei</li>
			<li>Hong Kong: North Point </li>
		</ul>
	</li>
</ol>
<u>Next steps:</u><br>
	<ol>
		<li><font style="color:rgb(255,153,0);font-weight:bold">Ask us for a shipping mark</font></li>
		<li>Have your supplier attach it to all your boxes</li>
		<li>Tell your supplier to email us the following
			<ul>
				<li>A copy of the PI (proforma invoice)</li>
				<li>Proof of your payment</li>
				<li>Pictures of the cargo with your shipping marks</li>
			</ul>
		</li>
		<li>When we have visual proof that the shipping mark is attached securely to your boxes, we will send the full warehouse address and contact information to your supplier.
			<ul>
				<li>We do this to prevent unmarked packaged from being shipped. If your shipment has no shipping mark, it's almost guaranteed to get lost in Manila.</li>
			</ul>
		</li>
		<li>Your supplier will ship the cargo to our warehouse. Then we wait an average of two weeks for shipping and customs clearance.</li>
		<li>We'll send you the bill via email, you deposit the payment, you pickup or have your stuff shipped to your door. And we're done! No questions asked :) </li>
		
	</ol>
<p>
	We also offer other hassle-free services such as same-day bank-to-bank currency exchange, wire transfer, financing, etc. You can learn about our other services on <a href="https://importanything.ph/other-services/"> this page. </a>
</p>
<p>
	The process is very simple and there won't be any other requirements other than what is stated above. If there are any questions, you can ask us via email or call us anytime. 
</p>
<br>
Your Order <b>Reference No:</b> <a href="{{ action('OrderController@show', [$order->id]) }}"> #{{$order->id}} </a><br>
@endsection
