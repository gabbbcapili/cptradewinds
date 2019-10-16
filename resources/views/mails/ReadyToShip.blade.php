@extends('layouts.mail')

@section('title', 'Orders Waiting!')


@section('content')

Hello,<br>
<br>
As always, please ensure that the exterior of the boxes and all markings are always nondescript - with no indication of what's inside. This is to avoid unwanted interest during random inspections.<br>
<br>
The addresses of our warehouse in China are as follows, the supplier can ship to either one:<br>
<ul>
	<li>Guangzhou: 广州市白云区石门街石井大道滘心路段自编1号。	
		中北仓储园N1-15 (导航：中北仓储园)<br>
		Contact: 王先生, Phone: 13660734555
	</li>
	<li>
		Yiwu: 义乌江东山口小区115栋2号 <br>
		Contact: 曾焕助, Phone: 13325795059
	</li>
</ul>
<br>
Please make sure the shipping mark we issued is attached to each piece of your cargo. <br>
<br>
<label style="color:red">Anything found without the shipping mark will be confiscated during inspection in Manila. Please make sure your boxes are always marked.</label>

<p>Your Order <b>Reference No</b>: <a href="{{ action('OrderController@index') }}">#{{ $order->id }} </a></p>


@endsection
