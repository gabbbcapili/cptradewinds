@extends('layouts.mail')

@section('title', 'Welcome')


@section('content')

<i>Congratulations {{ $user->name }} {{ $user->last_name }}!</i> You have successfully created a buyer account with us. Click <a href="{{ action('OrderController@create', ['withQuoute' => 'true']) }}">here</a> to  request a quote.
<br><br>
Our process is simple: 
	<ol>
		<li>You buy from your supplier</li>
		<li>They ship to our warehouse</li>
		<li>We handle the export and import process</li>
		<li>You pay, pickup, no questions asked</li>
	</ol>
<br>
Just like that. No surprise fees and no hassle with customs. To see the entire process, see <a href="https://importanything.ph/how-it-works/">importanything.ph/how-it-works</a>
<br>
Next Step: Get your all-inclusive quotation <br>


@endsection
