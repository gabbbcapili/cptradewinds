<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class QuotationController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        return view('order.quotation');
    }


    public function store(Request $request){
        $validation = null;
     if(request()->user()){
        $validation = $this->ValidatorUtil->orderValidation();
     }else{
        //if not logged in
        $validation = $this->ValidatorUtil->guessOrderValidation();
        if ($request->input('user_type') == 'buyer'){
            $validation['buyer_email'] = 'required|email|max:50|unique:users,email';
        }
        if ($request->input('user_type') == 'supplier'){
            $validation['email'] = 'required|email|max:50|unique:users';
        }
     }
    $validator = Validator::make($request->all(), $validation,
        $this->ValidatorUtil->orderValidationMessages());

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()]);
        }
    }

}
