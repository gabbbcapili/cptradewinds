@extends('layouts.mail')

@section('title', $subject)


@section('content')
	
	Your Customs Clearance Transaction <a href="{{ action('ClearanceController@index') }}"> <b>Reference No</b>: #{{ $data->id }}</a> is completed.
	-- template here
	<br> 
	
</p>

@endsection
