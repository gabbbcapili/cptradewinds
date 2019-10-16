<?php

namespace App\Http\Controllers;

use App\Clearance;
use App\Utils\ValidatorUtil; 
use Illuminate\Http\Request;
use Validator;
use App\Mail\DynamicEmail;
use Illuminate\Mail\Mailer;

class ClearanceController extends Controller
{

    public function __construct(ValidatorUtil $validatorUtil){
        $this->ValidatorUtil = $validatorUtil;
        $this->middleware('customer', ['only' => ['create', 'store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clearances = Clearance::all();
        return view('clearance.index', compact('clearances'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clearance.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Mailer $mailer)
    {
        $validator = Validator::make($request->all(), $this->ValidatorUtil->clearanceValidation(), $this->ValidatorUtil->clearanceValidationMessages());
        if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()]);
        }
        
        $data = $request->all();
        if($request->hasFile('invoice')){
          $photo = $data['invoice'];
          $new_name = sha1(time()) . '.' . $photo->getClientOriginalExtension();
          $photo->move(public_path('images/clearance/invoice') , $new_name);
          $data['invoice'] = $new_name;
        }

        if($request->hasFile('waybill')){
          $photo = $data['waybill'];
          $new_name = sha1(time()) . '.' . $photo->getClientOriginalExtension();
          $photo->move(public_path('images/clearance/waybill') , $new_name);
          $data['waybill'] = $new_name;
        } 
        $data['user_id'] = $request->user()->id;
        $clearance = Clearance::create($data);
        $mailer->to($data['supplier_email'])->send(new DynamicEmail('','Customs Clearance', 'mails.clearance.supplierNotification'));
        $mailer->to(env('ADMIN3'))->send(new DynamicEmail('','Customs Clearance', 'mails.clearance.admin3NewClearance'));
        request()->session()->flash('status', 'Successfully created customs clearance transaction!');
        return response()->json(['success' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Clearance  $clearance
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Clearance $clearance)
    {
        if(!$request->user()->isAdmin() && !$request->user()->isCustomer()){
             return response()->json(['status' => 'Unauthorized, invalid user.']);
        }
        return view('clearance.show', compact('clearance'));
    }

    public function showAdmin3(Request $request, Clearance $clearance)
    {
        if(!$request->user()->isAdmin3()){
             return response()->json(['status' => 'Unauthorized, invalid user.']);
        }
        return view('clearance.showAdmin3', compact('clearance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Clearance  $clearance
     * @return \Illuminate\Http\Response
     */
    public function edit(Clearance $clearance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Clearance  $clearance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Clearance $clearance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Clearance  $clearance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clearance $clearance)
    {
        //
    }

    public function viewQuotation(Clearance $clearance){
        return view('clearance.viewQuotation', compact('clearance'));
    }
    
    public function addQuotation(Clearance $clearance){
        return view('clearance.addQuotation', compact('clearance'));
    }

    public function addQuotationStore(Request $request ,Clearance $clearance, Mailer $mailer){
        if (!($clearance->status == 1)){
            return response()->json(['status' => 'Unauthorized, this transaction is not for Quatation.']);
        }

        $validator = Validator::make($request->all(), ['admin3_price' => 'required|regex:/^\d*(\.\d{1,2})?$/'],[
            'admin3_price.regex' => 'The field must be formatted as ##.##',
            'admin3_price.required' => 'This field is required.'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
            }
        $clearance->update(['status' => 2, 'admin3_price' => $request->input('admin3_price')]);
        $mailer->to(env('ADMIN'))->send(new DynamicEmail('','Customs Clearance', 'mails.clearance.admin1PutMarkedUpPrice'));

        request()->session()->flash('status', 'Successfully added quotation!');
        return response()->json(['success' => 'success']);
    }

    public function admin1QuotationStore(Request $request ,Clearance $clearance, Mailer $mailer){
        if (!($clearance->status == 2)){
            return response()->json(['status' => 'Unauthorized, this transaction is not for Quatation.']);
        }

        $validator = Validator::make($request->all(), ['admin1_price' => 'required|regex:/^\d*(\.\d{1,2})?$/'],[
            'admin1_price.regex' => 'The field must be formatted as ##.##',
            'admin1_price.required' => 'This field is required.'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
            }
        $clearance->update(['status' => 3, 'admin1_price' => $request->input('admin1_price')]);
        $mailer->to($clearance->email)->send(new DynamicEmail($clearance,'Customs Clearance Payable', 'mails.clearance.CustomerNotifyPayable'));

        request()->session()->flash('status', 'Successfully added quotation!');
        return response()->json(['success' => 'success']);
    }

    public function customerDeposit(Clearance $clearance, Request $request){
        if (!($clearance->status == 3)){
            return response()->json(['status' => 'Unauthorized, this transaction is not for Quatation.']);
        }
        if($clearance->clearance_by->id != $request->user()->id){
            return response()->json(['status' => 'Unauthorized, invalid user.']);
        }
        return view('clearance.customerDeposit', compact('clearance'));
    }

    public function customerDepositStore(Request $request ,Clearance $clearance, Mailer $mailer){
        if (!($clearance->status == 3)){
            return response()->json(['status' => 'Unauthorized, this transaction is not for Quatation.']);
        }
        $validator = Validator::make($request->all(), ['customer_deposit' => ['required', 'mimes:jpeg,png']],[
            'customer_deposit.mimes' => 'The file format should be: jpeg or png.',
            'customer_deposit.required' => 'This field is required.'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $data = $request->all();
        if($request->hasFile('customer_deposit')){
          $photo = $data['customer_deposit'];
          $new_name = sha1(time()) . '.' . $photo->getClientOriginalExtension();
          $photo->move(public_path('images/clearance/customer_deposit') , $new_name);
          $data['customer_deposit'] = $new_name;
        }
        $clearance->update(['status' => 4, 'customer_deposit' => $data['customer_deposit']]);
        $mailer->to(env('ADMIN'))->send(new DynamicEmail($clearance,'Customs Clearance', 'mails.clearance.Admin1NotifyDeposit'));
        request()->session()->flash('status', 'Successfully added deposit slip!');
        return response()->json(['success' => 'success']);
    }
    public function admin1Deposit(Clearance $clearance, Request $request){
        if (!($clearance->status == 4)){
            return response()->json(['status' => 'Unauthorized, this transaction is not for Quatation.']);
        }
        return view('clearance.admin1Deposit', compact('clearance'));
    }

    public function admin1DepositStore(Request $request ,Clearance $clearance, Mailer $mailer){
        if (!($clearance->status == 4)){
            return response()->json(['status' => 'Unauthorized, this transaction is not for Quatation.']);
        }
        $validator = Validator::make($request->all(), ['admin1_deposit' => ['required', 'mimes:jpeg,png']],[
            'admin1_deposit.mimes' => 'The file format should be: jpeg or png.',
            'admin1_deposit.required' => 'This field is required.'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $data = $request->all();
        if($request->hasFile('admin1_deposit')){
          $photo = $data['admin1_deposit'];
          $new_name = sha1(time()) . '.' . $photo->getClientOriginalExtension();
          $photo->move(public_path('images/clearance/admin1_deposit') , $new_name);
          $data['admin1_deposit'] = $new_name;
        }
        $clearance->update(['status' => 5, 'admin1_deposit' => $data['admin1_deposit']]);
         $mailer->to(env('ADMIN3'))->send(new DynamicEmail($clearance,'Customs Clearance', 'mails.clearance.Admin3NotifyDeposit'));
        request()->session()->flash('status', 'Successfully added deposit slip!');
        return response()->json(['success' => 'success']);
    }

    public function addTrackingNo(Clearance $clearance, Request $request){
        if($clearance->status != 5){
              return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        return view('clearance.addTrackingNo', compact('clearance'));
    }

   public function addTrackingNoStore(Clearance $clearance, Request $request, Mailer $mailer){
        if($clearance->status != 5){
              return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        $validator = Validator::make($request->all(), ['tracking_no' => 'required'],[
            'tracking_no.required' => 'This field is required.'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
            }
        $clearance->update(['status' => 6, 'tracking_no' => $request->input('tracking_no')]);
        $mailer->to($clearance->email)->send(new DynamicEmail($clearance,'Customs Clearance Transaction Completed', 'mails.clearance.CustomerCompleted'));
        $mailer->to($clearance->supplier_email)->send(new DynamicEmail($clearance,'Customs Clearance', 'mails.clearance.SupplierCompleted'));
        $request->session()->flash('status', 'Successfully approved deposit slip.');
        return response()->json(['success' => 'success']);
     }
}
