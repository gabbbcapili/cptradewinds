@extends('layouts.mail')

@section('title', 'Shipping Mark')


@section('content')

Hi!<br>
Please follow these next steps in the <b>specific order:</b>
	<ol>
		<li>
			Find the attached <b>shipping mark</b> and instruct your supplier to affix it to <b>each cargo piece</b>. <font style="color:red">Anything found without the shipping mark will be confiscated during inspection in Manila. Please make sure your boxes are always marked.</font>
		</li>
		<li>
			For quotation purposes, our addresses in China are as follows:
			<ul>
				<li>
					Guangzhou: 广州市白云区石门街石井大道滘心路段自编1号。
						中北仓储园N1-15 (导航：中北仓储园)
				</li>
				<li>
					Yiwu: 义乌江东山口小区115栋2号
				</li>
			</ul>
		</li>
		<li>
			To get the full warehouse details, please have your supplier email us the following (the email must originate from your supplier):
			<ul>
				<li>Pictures of your cargo with the shipping mark</li>
				<li>A copy of the Proforma Invoice</li>
				<li>Your proof of payment</li>
			</ul>
		</li>
	</ol>
<p>Please make sure to reply to this same email thread without changing the subject line. </p>
<p>Your Order <b>Reference No</b>: <a href="{{ action('OrderController@GetStorePhotos', $order->id) }}">#{{ $order->id }} </a></p>
@endsection
