@extends('layouts.mail')

@section('title', 'Shipment')


@section('content')



<p>Process initiated for Shipment {{ $data['order']->shipment_id }}</p>
<b>Shipment Details:</b>
<ol>
<li>	Buyer Name:
		@if($data['order']->ordered_by == null)
			{{ $data['inputs']['buyer_name'] }}
		@else
		{{ $data['order']->ordered_by->name }} {{ $data['order']->last_name }}
		@endif
</li>
<li>	Buyer Email Address:
		@if($data['order']->ordered_by == null)
			{{ $data['inputs']['buyer_email'] }}
		@else
		{{ $data['order']->ordered_by->email }}
		@endif
</li>
<li>	Item(s) to be imported: {{ $data['order']->import_details }} </li>
<li>	Supplier Country: {{ $data['order']->location }}</li>
<li>	Supplier Contact Person: 
	@if($data['order']->supplier_by == null)
			{{ $data['inputs']['supplier'] }}
		@else
		{{ $data['order']->supplier_by->name }} {{ $data['order']->supplier_by->last_name }}
		@endif
</li>
<li>	Supplier Email:
	@if($data['order']->supplier_by == null)
			{{ $data['inputs']['email'] }}
		@else
		{{ $data['order']->supplier_by->email }}
		@endif
</li>
<li>	Invoice No:{{ $data['order']->invoice_no }}</li>
</ol>

<p>View shipment progress <a href="{{ action('OrderController@show', [$data['order']->id] ) }}"> here </a></p>

@endsection