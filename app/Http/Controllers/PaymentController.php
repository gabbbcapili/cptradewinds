<?php

namespace App\Http\Controllers;

use App\Payment;
use App\PaymentKey;
use App\Dollar;
use App\Order;
use Illuminate\Http\Request;
use App\Utils\ValidatorUtil;
use App\Mail\PaymentTokenMail;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Mail\DynamicEmail;

//mails
use App\Mail\BankDetails;

use Validator;


class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(ValidatorUtil $validatorUtil){
        $this->ValidatorUtil = $validatorUtil;
        $this->middleware('customer', ['only' => ['create', 'store', 'approvePayment', 'storeSupplierDetails' , 'addSupplierDetails']]);
        $this->middleware('admin', ['only' => ['getPaymentConfirm', 'storePaymentConfirm']]);
    }

    public function index(Request $request)
    {
      if($request->user()->isAdmin3()){
         return response()->json(['error' => 'Unauthorized, invalid user.']);
      }
        $payments = null;
        $allow_create = true;
        $cutoff_string = Payment::getCutOffString();
        if (Payment::isTimeFrameAllowed() == false){
          $allow_create = false;
        }
        if (auth()->user()->isCustomer()){
            $payments = auth()->user()->customerPayments;    
        }
        if (auth()->user()->isAdmin()){
            $payments = Payment::all();
        }
        if (auth()->user()->isSupplier()){
            $payments = auth()->user()->supplierPayments;    
        }
        return view('payment.index', compact('payments', 'allow_create', 'cutoff_string'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      if (Payment::isTimeFrameAllowed() == false){
          return response()->json(['status' => Payment::getCutOffString()]);
        }
        $dollar = Dollar::first();
        $orders = $request->user()->customerOrders;
        return view('payment.create', compact('dollar', 'orders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Mailer $mailer)
    {
      // dd($request->input('order_id'));
      if($request->input('order_id')){
        $order = Order::find($request->input('order_id'));
          if ($order == null){
            return response()->json(['error' => ['order_id' => 'Order Reference ID not found.']]);
          }
      }
        $paymentValidation = $this->ValidatorUtil->paymentValidation();
        $paymentValidation['invoice'] = 'required|image';
        $paymentValidation['order_id'] = 'required';
        $validator = Validator::make($request->all(), $paymentValidation, $this->ValidatorUtil->paymentValidationMessages());
        if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()]);
        }   
        $data = $request->all();
        if($request->hasFile('invoice')){
          $photo = $data['invoice'];
          $new_name = sha1(time()) . '.' . $photo->getClientOriginalExtension();
          $photo->move(public_path('images/invoice') , $new_name);
          $data['invoice'] = $new_name;
        }
        $data['user_id'] = $request->user()->id;
        $data['rate'] = Dollar::first()->pluck('rate')->toArray()[0];  
        $data['status'] = 5;   
        $payment = Payment::create($data);

        $token = sha1(time());
        $key = PaymentKey::where('token', $token)->get();
        do{
            $token = sha1(time());
            $key = PaymentKey::where('token', $token)->get();
        }while(!$key->count() == 0);
        $url = action('PaymentController@ConsumeToken', $token);    
        PaymentKey::create(['token' => $token , 'payment_id' => $payment->id, 'type' => 'supplier']);
        $mailDetails = ['payment_id' => $payment->id ,'url' => $url];
        
        $mailer->to($request->user()->email)->send(new BankDetails($payment));
        request()->session()->flash('status', 'Successfully created payment service transaction!');
        return response()->json(['success' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        return view('payment.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }

    public function ConsumeToken($token){
         $paymentkey = PaymentKey::where('token', $token)->firstOrFail();
        if(!auth()->user()){
            $redirectTo = action('PaymentController@ConsumeToken', $token);
            session(['redirectTo' => $redirectTo, 'role' => $paymentkey->type]);
            return redirect('register');
        }
        if($paymentkey->consumed == true){
            session()->forget(['role', 'redirectTo']);
            abort(404, 'This payment service token has already been used.');
        }
        if($paymentkey->payment->status != 1){
            session()->forget(['role', 'redirectTo']);
            abort(403, 'Sorry this transaction may already been cancelled or completed.');
        }
        if(!auth()->user()->isSupplier() && !auth()->user()->isCustomer()){
            session()->forget(['role', 'redirectTo']);
            abort(403, 'Sorry, you cant receive payment service.');
        }
        if(auth()->user()->role != $paymentkey->type){
            abort(401);
        }
        $payment = $paymentkey->payment;

        if($paymentkey->type == 'supplier'){
            $payment->update(['supplier_id' => request()->user()->id , 'status' => 3]);
            $paymentkey->update(['consumed' => true]);
        }
        if($paymentkey->type == 'customer'){
            //user_id
            $payment->update(['user_id' => request()->user()->id , 'status' => 3]);
            $paymentkey->update(['consumed' => true]);
        }
        session()->forget(['role', 'redirectTo']);

        return redirect(action('PaymentController@index'))->with(['status' => "Success! you've got a new payment service transaction."]);
    }

    public function addSupplierDetails(Payment $payment, Request $request){ 
        if($payment->status != 5){
              return back()->with(['status' => 'Unauthorized, invalid status.', 'alert-class' => 'error']);
        }
        if($payment->user_id != $request->user()->id){
              return back()->with(['status' => "Unauthorized, not allowed.", 'alert-class' => 'error']);
        }
        $dollar = Dollar::first();
        $orders = $request->user()->customerOrders;
        return view('payment.addSupplierDetails', compact('payment', 'dollar', 'orders'));    
    }

    public function storeSupplierDetails(Payment $payment, Request $request, Mailer $mailer){
        if($payment->status != 5){
              return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        if($payment->user_id != $request->user()->id){
              return response()->json(['status' => 'Unauthorized, not allowed.']);
        }
        $paymentValidation = $this->ValidatorUtil->paymentValidation();
        if($payment->invoice == null){
          $paymentValidation['invoice'] = 'required|image';
        }
        $validator = Validator::make($request->all(), $paymentValidation,
        $this->ValidatorUtil->paymentValidationMessages());
        if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()]);
        }   
        if($request->input('order_id')){
        $order = Order::find($request->input('order_id'));
          if ($order == null){
            return response()->json(['error' => ['order_id' => 'Order Reference ID not found.']]);
          }
        }
        $data = $request->all();
        if($request->hasFile('invoice')){
          $photo = $data['invoice'];
          $new_name = sha1(time()) . '.' . $photo->getClientOriginalExtension();
          $photo->move(public_path('images/invoice') , $new_name);
          $data['invoice'] = $new_name;
        }
        $old_amount = $payment->amount;
        $data['status'] = 5;
        $data['rate'] = Dollar::first()->pluck('rate')->toArray()[0];
        $payment->update($data);
        if ($old_amount != $payment->amount){
          $mailer->to($request->user()->email)->send(new BankDetails($payment));
        }
        request()->session()->flash('status', 'Successfully updated supplier details!');
        return response()->json(['success' => 'success']);
    }

    public function approvePayment(Payment $payment, Request $request, Mailer $mailer){
        if ($payment->isTimeFrameAllowed() == false){
          return response()->json(['status' => $payment->getCutOffString()]);
        }
        if($payment->status != 4){
              return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        if($payment->user_id != $request->user()->id){
              return response()->json(['status' => 'Unauthorized, not allowed.']);
        }
        $request->session()->flash('status', 'Successfully approved payment! We have sent you an email for the next instruction.');
        $payment->update(['status' => 5]);

        $mailer->to($request->user()->email)->send(new BankDetails($payment));

        return response()->json(['success' => 'success']);
    }

    public function getDeposit(Payment $payment){
        if($payment->status != 5){
          return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        if($payment->user_id != request()->user()->id){
          return response()->json(['status' => 'Unauthorized, not allowed']);
        }
        return view('payment.depositModal', compact('payment'));
    }

    public function storeDeposit(Payment $payment, Request $request){
      if ($payment->isTimeFrameAllowed() == false){
          return response()->json(['status' => $payment->getCutOffString()]);
        }
        if($payment->status != 5){
              return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        if($payment->user_id != request()->user()->id){
          return response()->json(['status' => 'Unauthorized, not allowed']);
        }

        $validator = Validator::make($request->all(), [
            'deposits.*' => ['required', 'mimes:jpeg,bmp,png'],
            'deposits' => 'required'
        ],
        [
            'deposits.*.required' => 'This field is required',
            'deposits.*.mimes' => 'Only jpeg,png is allowed.'
        ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
            }

        $deposits = array();
        foreach($request->deposits as $deposit){
            $new_name = uniqid() . '.' . $deposit->getClientOriginalExtension();
            $deposit->move(public_path('images/deposit') , $new_name);
            $deposits[] = $new_name;
        }
        $deposits = implode('#', $deposits);

        $payment->update(['deposit' => $deposits, 'status' => 6]);
        $request->session()->flash('status', 'Successfully uploaded deposit!');
        
        return response()->json(['success' => 'success']);
    }

    public function getPaymentConfirm(Payment $payment, Request $request){
      if ($payment->status != 6){
            return response()->json(['error' => 'Unauthorized, invalid status!']);
        }
      return view('payment.paymentConfirmModal', compact('payment'));
    }

    public function storePaymentConfirm(Payment $payment, Request $request, Mailer $mailer){
      if($payment->status != 6){
              return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        $validator = Validator::make($request->all(), ['ssDeposit' => 'required|mimes:jpeg,bmp,png'],
            ['ssDeposit.mimes' => 'Only jpeg and png is allowed.', 'ssDeposit.required' => 'This field is required']
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        if($request->hasFile('ssDeposit')){
          $image = $request->file('ssDeposit');
          $new_name = sha1(time()) . '.' . $image->getClientOriginalExtension();
          $image->move(public_path('images/ssDeposit') , $new_name);
        }

        if($payment->order){
          $order = $payment->order;
          if($order->status == 15 && $order->withQuote == true){
            $order->update(['status' => 3, 'withQuote' => false]);
            $mailer->to($order->supplier_by->email)->send(new DynamicEmail($order, 'Customer payment details', 'mails.order.CustomerPayment'));
          }
          
        }


        $payment->update(['ssDeposit' => $new_name, 'status' => 7]);
        $request->session()->flash('status', 'Successfully confirmed payment!');
        return response()->json(['success' => 'success']);
    }
}

