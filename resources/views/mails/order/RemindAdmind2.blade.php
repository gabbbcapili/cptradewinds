@extends('layouts.mail')

@section('title', 'Shipment')


@section('content')

<p>Hello Virginia! Please login to your dashboard to view the proof of payment adn request pickup by client. <br> Click <a href="{{ action('OrderController@show', [$data->id] ) }}">here</a>. Thanks!</p>
@endsection
