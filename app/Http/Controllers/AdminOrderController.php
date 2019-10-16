<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;

class AdminOrderController extends Controller
{
    public function Quotation(){
    	$orders = Order::where('status', '3')->orWhere('status', '4')->get();
        return view('order.index', compact('orders'));
    }
    public function Pending(){
    	$orders = Order::whereBetween('status', ['4', '11'])->get();
        return view('order.index', compact('orders'));
    }
    public function Transit(){
    	$orders = Order::whereBetween('status', ['8', '13'])->get();
        return view('order.index', compact('orders'));
    }
    public function Completed(){
        $orders = Order::where('status', '14')->get();
        return view('order.index', compact('orders'));
    }
}
