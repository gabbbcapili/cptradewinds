@extends('layouts.mail')

@section('title', $subject)


@section('content')
<p>
	You may deposit your Customs Clearance Fee via our BDO PHP account: <br>

	HBK GLOBAL TRADING - 2070072653 <br>

	Amount of: {{ number_format($data->get_total_quotation(), 2) }} <br>

	For your Customs Clearance Transaction <a href="{{ action('ClearanceController@index') }}"> <b>Reference No</b>: #{{ $data->id }}</a> <br>
	Next step: Upload your deposit slip.
</p>

@endsection
