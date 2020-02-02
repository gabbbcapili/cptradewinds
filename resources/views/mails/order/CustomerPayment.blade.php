@extends('layouts.mail')

@section('title', 'Shipment')


@section('content')

<p>This is to inform you that your customer paid you please check the <a href="{{ action('OrderController@edit', [$data->id] ) }}"> order details</a> for more information</p>

@endsection
