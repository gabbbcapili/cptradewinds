<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Order;
use Illuminate\Mail\Mailer;
use App\Mail\AdminRemindMail;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if(session('redirectTo')){
            return redirect(session('redirectTo'));
        }
        if($request->user()->isAdmin3()){
            return redirect('clearance');
        }
        if($request->user()->isCustomer()){
            return redirect('profile');
        }
        return redirect('orders');
    }

    public function profile(Request $request, Mailer $mailer){
        $orders = null;
        if (auth()->user()->isSupplier()){
            $orders = auth()->user()->supplierOrders;    
        }
        if (auth()->user()->isCustomer()){
            $orders = auth()->user()->customerOrders;    
        }
        if(auth()->user()->isAdmin()){
            return redirect(action('OrderController@index'));
        }
        $withQuote = $orders->where('withQuote', true);
        $shipments = $orders->where('withQuote', false);
        return view('user.profile', compact('withQuote', 'shipments'));
    }
}
