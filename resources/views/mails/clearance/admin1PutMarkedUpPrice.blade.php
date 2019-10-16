@extends('layouts.mail')

@section('title', $subject)


@section('content')

<p>
Hi admin1, please put your marked up price. --template here <br>
<a href="{{ action('ClearanceController@index') }}"> Link to customs clearance </a>
</p>

@endsection
