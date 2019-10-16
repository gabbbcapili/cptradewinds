@extends('layouts.mail')

@section('title', 'Shipment')


@section('content')

<p>This is to confirm that you have initiated the shipment process for shipment {{ $data->shipment_id }} coming from {{ $data->location }}. An email has been sent to your supplier at {{ $data->email }}.</p>

<p>If it’s the first time your supplier is shipping through us, please pay attention to the following steps that they must follow. At any time, you may view the shipment progress <a href="{{ action('OrderController@show', [$data->id] ) }}"> here.</a></p>

<ol>
	<li>Generate shipping mark. This is a piece of paper that they have to attach to all of your boxes.</li>
	<li>Attach the shipping mark to all of your boxes</li>
	<li>Upload pictures of your boxes showing that the shipping marks are securely affixed to all your boxes.</li>
	<li>Ship to our warehouse </li>
	<li>Upload proof of shipment to our warehouse</li>
	<li>At this point, the ball is with your supplier. We take over after step five. However, if they ship anything to our warehouse without a shipping mark, we cannot assume responsibility for it. It will automatically be shipped to the Philippines and then confiscated at customs. <b>PLEASE MAKE SURE ALL BOXES HAVE THE SHIPPING MARK. </b> </li>
	<li>If your supplier has shipped with us before, you can just sit back and relax :)</li>
</ol>
@endsection
