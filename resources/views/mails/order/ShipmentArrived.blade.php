@extends('layouts.mail')

@section('title', 'Shipment')


@section('content')

<p>This is to inform you that Shipment {{ $data->shipment_id }} for {{ $data->ordered_by->name }}  {{ $data->ordered_by->last_name }} has arrived and your client is about to pick it up. </p>	
<p>Thank you for using our services. Because you used tradewinds.ph, your client will not have to deal with any hassles from customs or pay any extra fees. We hope to continue our partnership with you in serving more and more clients in the Philippines. </p>

@endsection
