@extends('layouts.mail')

@section('title', 'Shipment')


@section('content')

<p>The supplier has uploaded pictures of shipment {{ $data['order']->shipment_id }}. </p>

<p>The shipment must be adequately packed for the rough handling of sea-bound cargo. In any case, we will accept everything as-is and load them into the next available container bound for Manila immediately upon arrival.</p>

<p>We suggest you review the pictures before your supplier proceeds to shipping them to our warehouse.</p>

<p>Click<a href="{{ action('OrderController@show', [$data['order']->id] ) }}"> here.</a> to view the pictures </p>
@endsection
