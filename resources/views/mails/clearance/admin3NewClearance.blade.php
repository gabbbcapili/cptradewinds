@extends('layouts.mail')

@section('title', $subject)


@section('content')

<p>
Hi admin3, you have a new customs clearance transaction. --template here <br>
<a href="{{ action('ClearanceController@index') }}"> Link to customs clearance </a>
</p>
@endsection
