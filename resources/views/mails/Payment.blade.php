@extends('layouts.mail')

@section('title', 'Payment')


@section('content')


Great news! <br><br>

Your shipment has cleared customs and is now available at our (location) warehouse for pickup and delivery

The importation cost has to be settled via direct bank deposit before pickup or delivery.

To proceed, please login to your account.

<p>Your Order <b>Reference No</b>: <a href="{{ action('OrderController@edit', [$order->id]) }}">#{{ $order->id }} </a></p>

@endsection
