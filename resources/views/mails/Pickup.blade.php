@extends('layouts.mail')

@section('title', 'Orders Waiting!')


@section('content')
<!-- tondo -->
@if($order->pickup_location == 'tondo')
Hello! <br>
<p>
	We have confirmed your payment. Please text us the name of the person picking up your shipment and please make sure they bring any form of ID. Afterwards, you may pickup your shipment at the address below:
</p>
<br>
<p align="center"><b>
	Warehouse P2, #1613 A. Rivera Street, Corner Bambang Extension, <br>
	Tondo, Manila
</p></b><br>
<b>Pickup Instructions:</b>
<ul>
	<li>When you get to the compound gate, tell the guard that you are there to see Jimmy at Warehouse P2</li>
	<li>Go inside and it will be the first warehouse on the right. Look for Jimmy.</li>
	<li>Tell Jimmy that you’re a client of ImportAnything.ph and tell him you're there to pickup your shipment. </li>
	<li>Give him the name you specified on the Shipping Mark</li>
</ul>
<br>
<b>3rd Party Shipping Instructions:</b>
<ul>
	<li>Contact your shipping company of choice and have them follow the pickup instructions above.</li>
	<li>For shipments within Metro Manila, we recommend Transportify and Lalamove. You can download their app and arrange the pickup and delivery on your phone.</li>
	<li>For shipments outside of Metro Manila, we recommend AP Cargo and 2Go. </li>
	<li>For shipments outside of Metro Manila, we recommend AP Cargo and 2Go. </li>
</ul>
<br>
<p>You won’t have to sign anything or provide any other documentation. Just claim your package and you’re good to go.</p><br>
<p>We look forward to serving you again for all your future importations from China, Taiwan, and Thailand.</p><br>




<!-- tondo -->
@elseif($order->pickup_location == 'cubao')
Hello! <br>
<p>
	We have confirmed your payment. Please text us the name of the person picking up your shipment and please make sure they bring any form of ID. Afterwards, you may pickup your shipment at the address below:
</p>
<br>
<p align="center"><b>
	HBK Global Trading. <br>
	Minnesota Mansion, Unit 106, #267 Ermin Garcia Avenue, Cubao, QC
</p></b><br>
<i>Directions: Ermin Garcia extends from Nepo Q-Mart all the way to Aurora Blvd. We are closer to Aurora Blvd. This stretch of E. Garcia is one-way so you're going to have to come from Aurora.  After turning, the building will be on the right in just a few meters. </i>
<p>The storage is open Monday to Friday 9am-5pm and Saturdays 9am-2pm. <b>Please call/txt Virginia at 09365223922 to schedule your pickup. </b></p>
<br>
<b>3rd Party Shipping Instructions:</b>
<ul>
	<li>Contact your shipping company of choice and have them pickup at the address above. Please mind the business hours :)</li>
	<li>For shipments within Metro Manila, we recommend Transportify and Lalamove. You can download their app and arrange the pickup and delivery on your phone.</li>
	<li>For shipments outside of Metro Manila, we recommend AP Cargo and 2Go. </li>
	<li>The contact person for 3rd party pickup is Virginia, 0936 522 3922</li>
	<li><b>PLEASE ENSURE SOMEONE WILL BE THERE TO LIFT THE BOXES AND LOAD THEM INTO THE VEHICLE. THE SECRETARY WILL NOT BE RESPONSIBLE FOR CARRYING BOXES. </b></li>
</ul>
<br>
We look forward to serving you again for all your future importations from China, Hong Kong, Taiwan, and Thailand.
@endif
@endsection
