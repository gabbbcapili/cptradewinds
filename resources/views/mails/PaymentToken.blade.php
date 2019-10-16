@extends('layouts.mail')

@section('title', 'New Payment Service!')


@section('content')

Hi there! You've just been listed by {{ request()->user()->name }} as the supplier for payment service transaction #{{ $details['payment_id'] }}
<br><br>
Buyer details:
	<ul>
		<li>{{ request()->user()->name }}</li>
		<li>{{ request()->user()->email }}</li>
	</ul>
<br>
Click <a href="{{ $details['url'] }}"> here </a> to access the status of this transaction
<br>
@endsection
