@extends('layouts.mail')

@section('title', 'Shipment Completed')


@section('content')

<p>Shipment: {{ $data->shipment_id }} was successfull and delivered to your customer</p>
<br>
<p>View the shipment details<a href="{{ action('OrderController@show', [$data->id] ) }}"> here </a></p>

@endsection