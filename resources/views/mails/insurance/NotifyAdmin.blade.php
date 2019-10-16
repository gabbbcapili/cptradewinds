@extends('layouts.mail')

@section('title', 'Insurance')


@section('content')
<p>
	Hi, you have a new insurance transaction. Please visit <a href="{{ action('InsuranceController@index') }}"> insurance page.</a>
</p>
@endsection
