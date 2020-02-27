<?php

namespace App\Http\Controllers;
use App\Order;
use App\Payment;
use App\User;

use Illuminate\Http\Request;

class AjaxController extends Controller
{
    //

    public function getNotifications(Request $request){

    	$data = [
    		'shipments' => '',
			'quotations' => '',
			'payments' => '',
    	];

    	if($request->user()->isAdmin()){
    		$data['shipments'] = Order::where('withQuote', 0)->whereNotIn('status', [15, 2])->count();
    		$data['quotations'] = Order::where('withQuote', 1)->whereNotIn('status', [15, 2])->count();
    	}else if($request->user()->isSupplier()){
    		$data['quotations'] = $request->user()->supplierOrders->where('withQuote', 1)->whereNotIn('status', [15, 2])->count();
    		$data['shipments'] = $request->user()->supplierOrders->where('withQuote', 0)->whereNotIn('status', [15, 2])->count();
    		$data['payments'] = $request->user()->supplierPayments->whereNotIn('status', [7, 2])->count();
    	}else if($request->user()->isCustomer()){
    		$data['quotations'] = $request->user()->customerOrders->where('withQuote', 1)->whereNotIn('status', [15, 2])->count();
    		$data['shipments'] = $request->user()->customerOrders->where('withQuote', 0)->whereNotIn('status', [15, 2])->count();
    		$data['payments'] = $request->user()->customerPayments->whereNotIn('status', [7, 2])->count();
    	}
	
		foreach($data as $key => $value){
			if($value == 0){
				$data[$key] = '';
			}
		}

		return response()->json($data);





    }
}
