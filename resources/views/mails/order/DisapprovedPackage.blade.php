@extends('layouts.mail')

@section('title', 'DisapprovedPackage')


@section('content')

<p>The customer disapproved the packaging, please submit the new pictures of the package <a href="{{ action('OrderController@edit', [$data->id] ) }}"> here </a></p> 

@endsection
