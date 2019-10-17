@extends('layouts.mail')

@section('title', 'Shipment Cancelled')


@section('content')

<p>Shipment: {{ $data['order']->shipment_id }}</p>
<br>
<p>There is a problem with your dimensions, please email us the correct dimensions of the parcels</p>
<p>View the shipment details<a href="{{ action('OrderController@edit', [$data['order']->id] ) }}"> here </a></p>

@endsection