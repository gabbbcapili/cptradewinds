@extends('layouts.mail')

@section('title', 'Shipment')


@section('content')

<p>Hello please fill the delivery price on {{ $data->shipment_id }} <br> Click <a href="{{ action('OrderController@index') }}">here</a>. Thanks!</p>
@endsection
