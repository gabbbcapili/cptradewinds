@extends('layouts.mail')

@section('title', $subject)


@section('content')

<p>
Hi admin3, customer {{ $data->fullname }} uploaded a deposit slip. --template here <br>
<a href="{{ action('ClearanceController@index') }}"> Link to customs clearance </a>
</p>
@endsection
