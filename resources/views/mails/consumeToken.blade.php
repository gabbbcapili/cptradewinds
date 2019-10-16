@extends('layouts.mail')

@section('title', 'New Order!')


@section('content')

@if($details['type'] == 'customer')
Hi there! You've just been listed by {{ $details['name'] }} as the {{ $details['to'] }} for transaction #{{ $details['order_id'] }}
<br><br>
{{ ucfirst($details['from']) }} details:
	<ul>
		<li>{{ $details['name'] }}</li>
		<li>{{ $details['email'] }}</li>
	</ul>
<br>
Click <a href="{{ $details['url'] }}"> here </a> to access the status of this transaction
<br>
@endif

@if($details['type'] == 'supplier')
<p>
Your client from the Philippines has started a shipment. Click <a href="{{ $details['url'] }}"> here </a> to start printing shipping marks and view the next steps.  This request was initiated by the following person:</p>
<ol>
	<li>	{{$details['order']->ordered_by->name }} {{$details['order']->ordered_by->last_name }}</li>
	<li>	{{$details['order']->ordered_by->email }}</li>
</ol>

@endif
@endsection
