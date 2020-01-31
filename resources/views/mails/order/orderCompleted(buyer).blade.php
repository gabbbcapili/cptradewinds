@extends('layouts.mail')

@section('title', 'Shipment Completed')


@section('content')

<p>Shipment: {{ $data->shipment_id }}</p>
<br>
<p>
	Thank you for using our service. We look forward to serving you again for your future. If you’re happy with our service, we’d like to offer you a 5% rebate based on the amount you paid when you leave a positive review on our FB page.<a href="{{ env('FB_PAGE') }}">Click here</a>
</p>
<p>View the shipment details<a href="{{ action('OrderController@show', [$data->id] ) }}"> here </a></p>

@endsection