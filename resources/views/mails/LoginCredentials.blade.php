@extends('layouts.mail')

@section('title', 'Login Credentials')


@section('content')


Hello!	<br>
Your username: {{ request()->user()->email }}<br>
Your password: {{ $password }}<br>
@endsection
