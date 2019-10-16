@extends('layouts.mail')

@section('title', 'Insurance')


@section('content')

You may deposit insurance fee via our BDO PHP account: <br>

HBK GLOBAL TRADING - 2070072653 <br>

Amount of: {{ number_format($insurance->fee, 2) }}

@endsection
