<?php

namespace App\Http\Controllers;

use App\Insurance;
use App\Order;
use Illuminate\Http\Request;
use App\Mail\InsuranceDepositMail;
use App\Mail\DynamicEmail;
use Illuminate\Mail\Mailer;
use Validator;

class InsuranceController extends Controller
{
    public function __construct(){
        $this->middleware('customer', ['only' => ['create', 'store']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      if($request->user()->isAdmin3()){
         return response()->json(['error' => 'Unauthorized, invalid user.']);
      }
        $insurances = null;
        if (auth()->user()->isCustomer()){
            $insurances = auth()->user()->customerInsurances;    
        }
        if (auth()->user()->isAdmin()){
            $insurances = Insurance::all();
        }
        return view('insurance.index', compact('insurances'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
                  // ->whereNotIn('id', $request->user()->customerInsurances->pluck('order_id')->toArray())
                  ->where('status', '>=', 3)
                  ->get();
        return view('insurance.create', compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Mailer $mailer)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orderhh,id',
            'amount' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'invoice' => 'required|mimes:jpeg,png',
            'declaration' => 'required|mimes:jpeg,png',
        ],
        [
            'amount.regex' => 'This field must be formatted as ##.##',
        ]);

        if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()]);
        }  

        $order = Order::find($request->order_id);
        if($order->user_id != $request->user()->id){
            return response()->json(['error' => ['order_id' => 'The order transaction should be you listed as a buyer.']]);
        }
        //validation


        $order->update(['is_insured' => true]);
        $data = $request->all();

        if($request->hasFile('invoice')){
          $photo = $data['invoice'];
          $new_name = sha1(time()) . '.' . $photo->getClientOriginalExtension();
          $photo->move(public_path('images/insurance/invoice') , $new_name);
          $data['invoice'] = $new_name;
        }

        if($request->hasFile('declaration')){
          $photo = $data['declaration'];
          $new_name = sha1(time()) . '.' . $photo->getClientOriginalExtension();
          $photo->move(public_path('images/insurance/declaration') , $new_name);
          $data['declaration'] = $new_name;
        }

        $data['fee'] = $data['amount'] * 0.035;
        $data['user_id'] = $request->user()->id;

        $insurance = Insurance::create($data);
        $request->session()->flash('status', 'Successfully created insurance transactions! Please wait for the admin confirmation.');
        $mailer->to(env('ADMIN'))->send(new DynamicEmail('','New Insurance Transaction', 'mails.insurance.NotifyAdmin'));
        return response()->json(['success' => 'validator passes!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Insurance  $insurance
     * @return \Illuminate\Http\Response
     */
    public function show(Insurance $insurance)
    {
        //
    }

    public function approve(Insurance $insurance, Request $request, Mailer $mailer){
      if($insurance->status != 1){
          return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        $insurance->update(['status' => 3]);
        $mailer->to($request->user()->email)->send(new InsuranceDepositMail($insurance));
        $request->session()->flash('status', 'Successfully approved insurance!');
        return response()->json(['success' => 'success']);
    }

    public function reject(Insurance $insurance, Request $request){
      if($insurance->status != 1){
          return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        $insurance->update(['status' => 2]);
        $request->session()->flash('status', 'Successfully rejected insurance!');
        return response()->json(['success' => 'success']);
    }

    public function getDeposit(Insurance $insurance){
        if($insurance->status != 3 && $insurance->status != 4){
          return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        if($insurance->user_id != request()->user()->id){
          return response()->json(['status' => 'Unauthorized, not allowed']);
        }
        return view('insurance.depositModal', compact('insurance'));
    }

    public function storeDeposit(Insurance $insurance, Request $request){
        if($insurance->status != 3 && $insurance->status != 4){
              return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        if($insurance->user_id != request()->user()->id){
          return response()->json(['status' => 'Unauthorized, not allowed']);
        }
        $validator = Validator::make($request->all(), ['deposit' => 'required|mimes:jpeg,bmp,png'],
            ['deposit.mimes' => 'Only jpeg and png is allowed.']
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
            }
        if($request->hasFile('deposit')){
          $image = $request->file('deposit');
          $new_name = sha1(time()) . '.' . $image->getClientOriginalExtension();
          $image->move(public_path('images/insurance/deposit') , $new_name);
        }
        $insurance->update(['deposit' => $new_name, 'status' => 5]);
        $request->session()->flash('status', 'Successfully uploaded deposit!');
        return response()->json(['success' => 'success']);
    }

    public function completeTransaction(Insurance $insurance, Request $request){
        if ($insurance->status != 4){
            return response()->json(['error' => 'Unauthorized, invalid status!']);
        }
        $request->session()->flash('status', 'Successfully completed a transaction!');
        $insurance->update(['status' => 3]);

        return response()->json(['success' => 'success']);
     }

     public function showattachments(Insurance $insurance, Request $request){
        return view('insurance.showAttachments', compact('insurance'));
     }
}
