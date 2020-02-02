<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Validator;
//mails
use App\Mail\LoginCredentialsMail;
use App\Mail\ConsumeTokenMail;
use App\Mail\AdminAddQuotationMail;
use App\Mail\AdminRemindMail;
use App\Mail\FeeAccepted;
use App\Mail\ReadyToShip;
use App\Mail\Pickup;
use App\Mail\Payment;
use App\Mail\UserDetails;
use App\Mail\UserCreationMail;
use App\Mail\DynamicEmail;
// models
use App\Order;
use App\OrderDetails;
use App\OrderKey;
use App\User;
use App\OrderLogs;
use App\Source;
//utils
use App\Utils\ValidatorUtil;
use Illuminate\Mail\Mailer;
use Auth;
use GuzzleHttp\Client;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     
*     * @return \Illuminate\Http\Response
     */
    public function __construct(ValidatorUtil $validatorUtil){
        $this->ValidatorUtil = $validatorUtil;
        $this->middleware('auth', ['except' => ['ConsumeToken', 'create', 'store', 'addQuotation', 'addQuotationStore']]);
        // $this->middleware('supplier', ['only' => ['edit', 'update']]);

    }
    public function index(Request $request, Mailer $mailer)
    {
        if($request->user()->isAdmin3()){
         return response()->json(['error' => 'Unauthorized, invalid user.']);
      }

      
        $orders = null;
        if (auth()->user()->isSupplier()){
            $orders = auth()->user()->supplierOrders;    
        }
        if (auth()->user()->isCustomer()){
            $orders = auth()->user()->customerOrders;    
        }
        if (auth()->user()->isAdmin()){
            $orders = Order::all();
        }

        if($request->segment(1) == "quotation"){
            $title = 'Quotations';
            $orders = $orders->where('withQuote', true);
        }else{
            $title = 'Shipments';
            $orders = $orders->where('withQuote', false);
        }


        return view('order.index', compact('orders', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Mailer $mailer)
    {
        if($request->input('clientid') != null){
               if( $request->user()){
                if($request->user()->isSupplier()){
                    $user = User::findOrFail($request->input('clientid'));
                    if (! $user->isCustomer()) {
                        abort(404);
                    }
                }else{
                    abort(401);
                }
            }
        }

        $redirectTo = action('OrderController@create', ['clientid=' . $request->input('clientid')]);
        if(! $request->user()){
            if($request->input('clientid')){
                session(['redirectTo' => $redirectTo]);
                return redirect(action('Auth\LoginController@showLoginForm'));
            }
        }
        if($request->segment(1) == "quotation"){
            $title = 'Submit Quotation';
        }else{
            $title = 'Start a Shipment';
            // if(! $request->user()){
            //      return redirect(action('Auth\LoginController@showLoginForm'));
            // }
        }
        $request->session()->forget('redirectTo');
        return view('order.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Mailer $mailer)
    {
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
        if($request->user()){
            if($request->user()->isCustomer()){
                if($request->input(['item']) == null){
                    return response()->json(['info' => 'Please add item(s) first.']);
                }   
            }else if ($request->user()->isSupplier()){
                if($request->input(['product']) == null){
                    return response()->json(['info' => 'Please add box(es) first.']);
                }  
            }
        }else if($request->input('user_type') == 'supplier'){
            if($request->input(['product']) == null){
                    return response()->json(['info' => 'Please add box(es) first.']);
                }  
        }else if($request->input('user_type') == 'buyer'){
            if($request->input(['item']) == null){
                    return response()->json(['info' => 'Please add item(s) first.']);
                }  
        }       
    if ($validator->passes()){
        $user_id = $request->input('user_id', null);
        $order_type = $request->input('order_type');
        $token = OrderKey::get_available_key();
        $url = action('OrderController@ConsumeToken', [$token]);
        $header = ($request->only(['supplier','email', 'location' , 'import_details', 'warehouse', 'pickup_location', 'invoice_no', 'source']));
        $header['token'] = uniqid();
        if($header['source']){
            $source = Source::where('name', $header['source'])->first();
            if(! $source){
                $source = Source::create(['name' => $header['source']]);
            }
            $header['source_id'] = $source->id;
        }
        if($order_type == 'quotation'){
            $header['withQuote'] = true;
            $header['status'] = 4;
            if($user_id == null){
                $header['status'] = 1;
            }
            if($request->input('user_type') == 'supplier'){
                $header['status'] = 4;    
            }
        }else{
            $header['withQuote'] = false;
            if($user_id == null){
                $header['status'] = 1;
            }else{
                $header['status'] = 6;
            }  
        }
        $details = ($request->input(['product']));
        $items = ($request->input(['item']));
        if ($request->input('user_type') == null){
            // not guess
            if (request()->user()){ 
                if (request()->user()->isSupplier()){
                    $header['supplier_id'] = auth()->user()->id; 
                    $header['user_id'] = $user_id;
                    request()->session()->flash('status', 'Shipment Added Successfully!');
                }
                if (request()->user()->isCustomer()){
                    $header['user_id'] = auth()->user()->id;
                    // $header['status'] = 1;
                }

           $order = Order::create($header);

           $order->updateShipmentID();

             if (request()->user()->isCustomer()){
                $request->session()->flash('status', 'Import process initiated for shipment number #' . $order->id   .'. Your supplier will receive an email instructing them to follow our procedure.');
                $mailTitle = 'Shipment '. $order->shipment_id . ' ' . $order->ordered_by->name . ' ' . $order->ordered_by->last_name;
              }
              if (request()->user()->isSupplier()){
                $mailTitle = 'Shipment '. $order->shipment_id . ' '  . $request->input('buyer_name');
                $order->startedSMS(new Client(), $request->input('buyer_mobile_number'));
              }            
           if (!$details == null){
                foreach ($details as $detail){
                    $order->details()->create($detail);
                }
                $order->UpdateTotalWeight();
                $order->UpdateTotalCBM();    
           }
           if (!$items == null){
                foreach($items as $item){
                    $order->items()->create($item);
                }
            }
            $mail_to = null;
            $mailDetails = ['name' => $request->user()->name, 'email' => $request->user()->email, 'url' => $url, 'order_id' => $order->id, 'order' => $order , 'from' => $request->user()->role, 'to' => request()->user()->getOppositeRole(), 'mailTitle' => $mailTitle];
            if($request->user()->role == 'supplier'){
                $mail_to = $request->input('buyer_email');
                $mailDetails['type'] = 'customer';
            }elseif($request->user()->role == 'customer'){
                $mail_to = $request->input('email');
                $mailDetails['type'] = 'supplier';
            }
            if($order->withQuote == false){
                $mailer->to($request->user()->email)->send(new DynamicEmail($order, $mailTitle , 'mails.order.Instruction'));
                OrderKey::create(['token' => $token , 'order_id' => $order->id, 'type' => request()->user()->getOppositeRole()]);
                $mailer->to(env('ADMIN1'))->send(new DynamicEmail(['order' => $order, 'inputs' => $request->all()], $mailTitle , 'mails.order.OrderDetails'));
                if($user_id == null){
                     $mailer->to($mail_to)->send(new ConsumeTokenMail($mailDetails));
                }
            }else{
                $mailer->to(env('ADMIN1'))->send(new AdminRemindMail(route('addQuotationNoLogin', ['token' => $order->token])));
                if($user_id == null){
                     OrderKey::create(['token' => $token , 'order_id' => $order->id, 'type' => request()->user()->getOppositeRole()]);
                     $mailer->to($mail_to)->send(new ConsumeTokenMail($mailDetails));
                }
            }
            
            OrderLogs::create(['order_id' => $order->id, 'description' => $request->user()->name . ' created this transaction.']);
            return response()->json(['success' => 'validator passes!']);
            }
        }else{
            //guess
            $user = null;
            $order = null;
            $password = mt_rand(1000, 9999);
            if($request->input('user_type') == 'supplier'){
                //supplier
                $user_details['email'] = $request->input('email');
                $user_details['last_name'] = $request->input('supplier_last_name');
                $user_details['name'] = $request->input('supplier');
                $user_details['role'] = 'supplier';
                $user_details['password'] =  Hash::make($password);
                $user = User::create($user_details);
                $header['supplier_id'] = $user->id;
                $order = Order::create($header);
                $order->updateShipmentID();
                $mailTitle = 'Shipment '. $order->shipment_id . ' '  . $request->input('buyer_name'). ' ' . $request->input('buyer_last_name');
                $mailDetails = ['type' => 'customer','name' => $request->input('supplier'), 'email' =>  $request->input('email'), 'url' => $url, 'from' => 'supplier', 'to' => 'buyer', 'order_id' => $order->id , 'mailTitle' => $mailTitle , 'order' => $order ];
                OrderKey::create(['token' => $token , 'order_id' => $order->id, 'type' => 'customer']);
                if($order->withQuote == false){
                   $request->session()->flash('status', 'Import process initiated for shipment number #' . $order->id   .'.');
                   $mailer->to($request->input('buyer_email'))->send(new ConsumeTokenMail($mailDetails));
                }else{
                    $request->session()->flash('status', 'Successfully Submitted a Quotation!');
                }
            }
            if($request->input('user_type') == 'buyer'){
                $user_details = [
                        'email' => $request->input('buyer_email'),
                        'last_name' => $request->input('last_name'),
                        'name' => $request->input('buyer_name'),
                        'role' => 'customer',
                        'password' => Hash::make($password),
                        'phone_no' => $request->input('phone_no'),
                    ];
                $user = User::create($user_details);           
                $header['user_id'] = $user->id;
                $order = Order::create($header);
                $order->updateShipmentID();
                $mailTitle = 'Shipment '. $order->shipment_id . ' '  . $request->input('buyer_name'). ' ' . $request->input('buyer_last_name');
                $mailDetails = ['type' => 'supplier','name' => $request->input('buyer_name'), 'email' =>  $request->input('buyer_email'), 'url' => $url, 'from' => 'buyer', 'to' => 'supplier', 'order_id' => $order->id,  'mailTitle' => $mailTitle , 'order' => $order ];
                OrderKey::create(['token' => $token , 'order_id' => $order->id, 'type' => 'supplier']);
                
                if($order->withQuote == false){
                    $request->session()->flash('status', 'Import process initiated for shipment number #' . $order->id   .'. Your supplier will receive an email instructing them to follow our procedure.');
                    $mailer->to($request->input('email'))->send(new ConsumeTokenMail($mailDetails));
                }else{
                    $mailer->to($request->input('email'))->send(new ConsumeTokenMail($mailDetails));
                    $request->session()->flash('status', 'Successfully Submitted a Quotation!');
                }
            }
            Auth::login($user);           
            $mailer->to($request->user()->email)->queue(new UserCreationMail($user));
           if (!$details == null){
                foreach ($details as $detail){
                    $order->details()->create($detail);
                }
                $order->UpdateTotalWeight();
                $order->UpdateTotalCBM();    
           }
           if (!$items == null){
                foreach($items as $item){
                    $order->items()->create($item);
                }
            }
           $order->updateShipmentID();
            if($order->withQuote == false){
           $mailer->to($request->user()->email)->send(new DynamicEmail($order, $mailTitle , 'mails.order.Instruction'));
           $mailer->to(env('ADMIN1'))->send(new DynamicEmail(['order' => $order, 'inputs' => $request->all()], $mailTitle , 'mails.order.OrderDetails'));
            }else{
                $mailer->to(env('ADMIN1'))->send(new AdminRemindMail(route('addQuotationNoLogin', ['token' => $order->token])));
            }
           OrderLogs::create(['order_id' => $order->id, 'description' => $request->user()->name . ' created this transaction.']);
           $request->session()->flash('success', 'We have sent you an email regarding your login credentials');
           $mailer->to($request->user()->email)->queue(new LoginCredentialsMail($password));
           return response()->json(['success' => 'validator passes!']);
        } //else
    } // validator passes

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
        $boxes_images =  explode('#', $order->boxes);
        if($order->withQuote == true){
            $title = 'Quotation';
        }else{
            $title = 'Shipment';
        }
        return view('order.show', compact('order','boxes_images', 'title'));
    }

    public function showMark(Order $order){
        return view('order.showMark', compact('order'));
    }

    public function viewQuotation(Order $order){
        return view('order.viewQuotation', compact('order'));
    }
    

    public function chooseWarehouse(Order $order){
        if($order->supplier_id != request()->user()->id ){
             abort(401);
        }
        
        return view('order.chooseWarehouse', compact('order'));
    }

    public function getPayment(Order $order){

        if($order->user_id != request()->user()->id){
             abort(401);
        }
        
        return view('order.payment', compact('order'));
    }

    public function getProofOfShipment(Order $order){
        if($order->status != 7){
              return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        if($order->supplier_id != request()->user()->id){
             abort(401);
        }
        
        return view('order.proofShipment', compact('order'));
    }

    public function storeProofOfShipment(Order $order, Request $request, Mailer $mailer){
        if($order->status != 7){
              return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        $validator = Validator::make($request->all(),['shipment_proof' => 'required|mimes:jpeg,bmp,png'],
            ['shipment_proof.mimes' => 'Only jpeg and png is allowed.']
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
            }
        $image = $request->file('shipment_proof');
        $new_name = uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images') , $new_name);
        $order->update(['shipment_proof' => $new_name, 'status' => 8, 'shipped_at' => \Carbon::now()]);
        $order->shippedSMS();
        $request->session()->flash('status', 'Successfully uploaded proof of shipment! Status is now processing.');
        OrderLogs::create(['order_id' => $order->id, 'description' => $request->user()->name . ' uploaded proof of shipment.']);
        return response()->json(['success' => 'The order status should be pending!', 'redirect' => action('OrderController@show', [$order->id])]);
    }


     public function editDeliveryAddress(Order $order){
        if($order->status != 9){
              return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        return view('order.editDeliveryAddress', compact('order'));
    }


    public function updateDeliveryAddress(Order $order, Request $request, Mailer $mailer){
        if($order->status != 9){
              return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        $validator = Validator::make($request->all(),[
            'delivery_address' => 'required',
        ],
            [
                'delivery_address.required' => 'This field is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
            }
        $data = $request->only(['delivery_address']);
        $data['status'] = 10;
        $order->update($data);
        $request->session()->flash('status', 'Successfully updated delivery address! Waiting for admin due amount.');
        OrderLogs::create(['order_id' => $order->id, 'description' => $request->user()->name . ' updated delivery address.']);
        return response()->json(['success' => 'success']);
    }

    public function editDue(Order $order){
        if($order->status != 10){
              return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        return view('order.editDue', compact('order'));
    }


    public function updateDue(Order $order, Request $request, Mailer $mailer){
        if($order->status != 10){
              return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        $validator = Validator::make($request->all(),[
            'cbm' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'price' => 'required',
            'delivery_price' => 'nullable',
            'pickup_location' => 'required',
        ],
            [
                'price.required' => 'This field is required.',
                // 'price.integer' => 'This field contains an invalid format.',
                // 'delivery_price.required' => 'This field is required.',
                // 'delivery_price.integer' => 'This field contains an invalid format.',
                'pickup_location.required' => 'This field is required.',
                'cbm.required' => 'This field is required.',
                'cbm.regex' => 'This field contains an invalid format.',
               
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
            }
        $data = $request->only(['pickup_location']);
        $data['boxes_received'] = $request->input('cbm');
        $data['price'] = str_replace( ',', '', $request->input('price'));
        $data['delivery_price'] = str_replace( ',', '', $request->input('delivery_price'));
        $data['price_date'] = \Carbon::now();

        // $data['status'] = 11;
        $order->update($data);
        if($order->price < 1 || $order->delivery_price < 1){
            $mailer->to(env('ADMIN1'))->send(new DynamicEmail($order, 'Input Delivery Price' , 'mails.order.deliveryPrice'));
        }else{
            $mailer->to($order->ordered_by->email)->send(new Payment($order));
            $order->clearedSMS(new Client());
            $order->update(['status' => 11]);
        }
        $request->session()->flash('status', 'Successfully updated! Waiting for customer payment.');
        OrderLogs::create(['order_id' => $order->id, 'description' => $request->user()->name . ' uploaded proof of shipment.']);
        return response()->json(['success' => 'The order status should be pending!']);
    }

    public function viewPictures(Order $order){
        $boxes_images =  explode('#', $order->boxes);

        return view('order.pictureModal', compact('order', 'boxes_images'));
    }

    public function chooseWarehouseStore(Order $order, Request $request){
        $warehouse = $request->input('warehouse');
        $order->update(['warehouse' => $warehouse]);

        OrderLogs::create(['order_id' => $order->id, 'description' => $request->user()->name . ' chose ' . $warehouse . ' as warehouse.']);

        $request->session()->flash('status', 'Successfully Updated Warehouse! You can now print shipment mark.');

        return back();
    }

    public function storePayment(Order $order, Request $request, Mailer $mailer){
        if($order->status != 11){
              return response()->json(['status' => 'Unauthorized, invalid status.']);
        }

        $validator = Validator::make($request->all(),[
            'payment' => 'required|mimes:jpeg,bmp,png',
            'pickup_person' => 'required_if:pickup_type,==,pickup',
            'pickup_time' => 'required_if:pickup_type,==,pickup',
        ],
            ['payment.mimes' => 'Only jpeg and png is allowed.']
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
            }
        $image = $request->file('payment');
        $new_name = sha1(time()) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images') , $new_name);
        $order->update(['payment' => $new_name, 'pickup_type' => $request->input('pickup_type'), 'pickup_person' => $request->input('pickup_person'),'pickup_time' => $request->input('pickup_time') , 'status' => 12]);
        $request->session()->flash('status', 'Successfully uploaded payment!');
        $mailTitle = 'Proof of payment for Shipment ' . $order->shipment_id;
        $mailer->to(env('ADMIN2b'))->send(new DynamicEmail($order, $mailTitle , 'mails.order.RemindAdmind2'));
        OrderLogs::create(['order_id' => $order->id, 'description' => $request->user()->name . ' added payment photo.']);

        return response()->json(['success' => 'The order status should be pending!', 'redirect' => action('OrderController@show' , [$order->id])]);
    }

    public function approvePayment(Order $order, Request $request, Mailer $mailer){
        if ($order->status != 12){
            return response()->json(['error' => 'Unauthorized, invalid status!']);
        }
        $request->session()->flash('status', 'Successfully approved payment!');
        $order->update(['status' => 13]);

        OrderLogs::create(['order_id' => $order->id, 'description' => 'Admin approved the payment. Shipment is ready for pick up']);
        if($order->pickup_type == 'pickup'){
            $mailer->to($order->ordered_by->email)->send(new Pickup($order));
        }
        
        $mailTitle= 'Shipment ' . $order->shipment_id . ' has arrived.';
        $mailer->to($order->supplier_by->email)->send(new DynamicEmail($order, $mailTitle , 'mails.order.ShipmentArrived'));
        $order->paymentConfirmedSMS(new Client());
        return response()->json(['success' => 'success']);
    }
    
    public function declinePayment(Order $order, Request $request){
        if ($order->status != 12){
            return response()->json(['error' => 'Unauthorized, invalid status!']);
        }
        $request->session()->flash('status', 'Successfully declined payment!');

        OrderLogs::create(['order_id' => $order->id, 'description' => 'Admin declined the payment.']);
        $order->update(['status' => 11]);

        // $mailer->to($order->ordered_by->email)->send(new Payment($order));   di talaga kasama

        return response()->json(['success' => 'success']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {  
        if( $order->user_id != request()->user()->id && $order->supplier_id != request()->user()->id ){
             abort(401);
        }
        // pending 
        if(! ($order->status >= 2  && $order->status <= 11)){
            abort(401, "Sorry, this order can't be edited.");
        }
       
        return view('order.edit', compact('order'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order, Mailer $mailer)
    {
        // pending
        if(!$order->status == 1 || !$order->status == 3 && $order->status != 4 && $order->status != 5){
            return response()->json(['status' => 'Sorry, this order cant be edited.']);
        }
        if($order->details->count() == null){
            return response()->json(['status' => 'Sorry, Please add some boxes first. (Update your boxes first)']);
        }
        if($request->input(['product']) == null){
            return response()->json(['status' => 'Sorry, Please add some importing boxes first.']);
        }

        $validator = Validator::make($request->all(), $this->ValidatorUtil->validateAddType(),
        $this->ValidatorUtil->validateAddTypeMessages());

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
            }
        $updated_detail_ids = [];
        if ($validator->passes()){
            $header = $request->only(['warehouse']);
            $order->update($header);
            $details = $request->input(['product']);
            if(!$details == null ){
                foreach($details as $detail){
                    if(isset($detail['detail_id'])){
                        $orderDetails = OrderDetails::findOrFail($detail['detail_id']);
                        $updated_detail_ids[] = $orderDetails->id;
                        $orderDetails->update($detail);
                    }else{
                        $orderDetail = new OrderDetails($detail);
                        $newOrders[] = $orderDetail;
                    }
                }
                // $deleteOrderDetails = OrderDetails::where('order_id', $order->id)
                //                 ->whereNotIn('id', $updated_detail_ids)
                //                 ->delete();
                if (!empty($newOrders)) {
                 $order->details()->saveMany($newOrders);
             }
         }
         $order->UpdateTotalCBM();
         $order->UpdateTotalWeight();
        request()->session()->flash('status', 'Successfully Updated an Shipment! Next step: Print Shipping Marks.');

        $status = $order->withQuote == false ? 6 : 4;

        $order->update(['status' => $status, 'price' => null]);
        if($order->withQuote == true){
            $mailer->to(env('ADMIN1'))->send(new AdminRemindMail(route('addQuotationNoLogin', ['token' => $order->token])));
        }
        return response()->json(['success' => 'Success']);
        }
    }

    public function updateBoxes(Request $request, Order $order, Mailer $mailer)
    {
        // cancelled
        if($order->status != 2 && $order->status != 3 && $order->status != 4){
            return response()->json(['status' => 'Sorry, this order cant be edited. Invalid Status']);
        }

        // if( $order->user_id != request()->user()->id){
        //      return response()->json(['status' => 'Sorry, Unauthorized user.']);
        // }
        if($request->input(['product']) == null){
            return response()->json(['status' => 'Sorry, Please add some boxes first.']);
        }

        $validator = Validator::make($request->all(), $this->ValidatorUtil->validateBoxes(), $this->ValidatorUtil->orderValidationMessages());

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
            }
        
        $updated_detail_ids = [];
        if ($validator->passes()){
            $details = $request->input(['product']);
            if(!$details == null ){
                foreach($details as $detail){
                    if(isset($detail['detail_id'])){
                        $orderDetails = OrderDetails::findOrFail($detail['detail_id']);
                        $updated_detail_ids[] = $orderDetails->id;
                        $orderDetails->update($detail);
                    }else{
                        $orderDetail = new OrderDetails($detail);
                        $newOrders[] = $orderDetail;
                    }
                }
                $deleteOrderDetails = OrderDetails::where('order_id', $order->id)
                                ->whereNotIn('id', $updated_detail_ids)
                                ->delete();
                if (!empty($newOrders)) {
                 $order->details()->saveMany($newOrders);
             }
         }
         $order = Order::find($order->id);
         $order->UpdateTotalCBM();
         $order->UpdateTotalWeight();
         $count_orders = $order->details->count();
        request()->session()->flash('status', 'Successfully Updated an Shipment! Please wait for admin approval.');

        $status = $order->withQuote == false ? 6 : 4;
        if($order->withQuote == false || $count_orders == 0){
            $status = 3;
        }
        $order->update(['status' => $status, 'price' => null]);
        if($order->withQuote == true){
            $mailer->to(env('ADMIN1'))->send(new AdminRemindMail(route('addQuotationNoLogin', ['token' => $order->token])));
        }
        return response()->json(['success' => 'Success', 'redirect' => action('OrderController@edit', [$order->id])]);
        }
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function delete(Order $order){
        $order->delete();
        request()->session()->flash('status', 'Successfully deleted a shipment.');
        return response()->json(['success' => 'Success']);
    }

    public function ConsumeToken($token, Request $request){
        $orderkey = OrderKey::where('token', $token)->firstOrFail();
        if(!auth()->user()){
            $redirectTo = url('/') . '/absorb/token/' . $token;
            session(['redirectTo' => $redirectTo, 'role' => $orderkey->type]);
            return redirect('register');
        }
        if($orderkey->order->status != 1){
            session()->forget(['role', 'redirectTo']);
            abort(403, 'Sorry this transaction may already been cancelled or completed.');
        }
        if(!auth()->user()->isSupplier() && !auth()->user()->isCustomer()){
            session()->forget(['role', 'redirectTo']);
            abort(403, 'Sorry, you cant receive orders.');
        }
        if($orderkey->consumed == true){
            session()->forget(['role', 'redirectTo']);
            abort(404, 'This order token has already been used.');
        }
        if(auth()->user()->role != $orderkey->type){
            session()->forget(['role', 'redirectTo']);
            abort(401);
        }
        $order = $orderkey->order;
        if($orderkey->type == 'supplier'){
            $order->update(['supplier_id' => request()->user()->id , 'status' => 3]);
            $orderkey->update(['consumed' => true]);
        }
        if($orderkey->type == 'customer'){
            //user_id
            $order->update(['user_id' => request()->user()->id , 'status' => 3]);
            $orderkey->update(['consumed' => true]);
        }
        session()->forget(['role', 'redirectTo']);
        OrderLogs::create(['order_id' => $order->id, 'description' => $request->user()->name . ' accepted as the supplier of this transaction.']);

        if($request->user()->isSupplier()){
            return redirect(action('OrderController@edit', [$order->id]))->with(['status' => "Success! you've got a new order."]);
        }else{
            return redirect(action('OrderController@show', [$order->id]))->with(['status' => "Success! you've got a new order."]);
        }
        
    }

    public function cancelForm(Order $order){
        return view('order.cancel', compact('order'));
    }

    public function cancelSubmit(Order $order, Request $request, Mailer $mailer){
        if ($order->user_id != $request->user()->id && ! $request->user()->isAdmin() && $order->supplier_id != $request->user()->id){
            return response()->json(['error' => 'Unauthorized']);
        }
        // pending
        if ($order->status >= 1 && $order->status >= 8){
            return response()->json(['error' => 'Only pending request can be cancelled!']);
        }
        $validator = Validator::make($request->all(), ['notes' => 'required']);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $data = $request->only(['notes']);
        $data['status'] = 2;
        $order->update($data);
        OrderLogs::create(['order_id' => $order->id, 'description' => $request->user()->name . ' cancelled the transaction.']);
        if($order->ordered_by != null){
            $mailer->to($order->ordered_by->email)->send(new DynamicEmail(['order' => $order], 'Shipment: '. $order->shipment_id . ' was Cancelled' , 'mails.order.CancelledByAdmin'));
       }
        $request->session()->flash('status', 'Successfully cancelled the transaction.');
        return response()->json(['success' => 'success']);
    }



    public function cancel(Order $order, Request $request, Mailer $mailer){
        if ($order->user_id != $request->user()->id && ! $request->user()->isAdmin() && $order->supplier_id != $request->user()->id){
            return response()->json(['error' => 'Unauthorized']);
        }
        // pending
        if ($order->status >= 1 && $order->status >= 8){
            return response()->json(['error' => 'Only pending request can be cancelled!']);
        }

    OrderLogs::create(['order_id' => $order->id, 'description' => $request->user()->name . ' cancelled the transaction.']);
       $order->update(['status' => 2]);
       if($order->ordered_by != null){
            $mailer->to($order->ordered_by->email)->send(new DynamicEmail(['order' => $order], 'Shipment: '. $order->shipment_id . ' was Cancelled' , 'mails.order.CancelledByAdmin'));
       }
       request()->session()->flash('success' , 'Successfully Cancelled Shipment.');

    return response()->json(['success' => 'Successfully canceled!']);

     }

     public function forQuotation(Order $order, Mailer $mailer, Request $request){
        if($order->supplier_id != request()->user()->id){
            return response()->json(['error' => 'Unauthorized, sorry you are not able to do this.']);
        }
        if($order->cbm == null || $order->weight == null){
            return response()->json(['error' => 'Sorry, please complete the Overall Dimension first before proceding.']);
        }
        if ($order->status != 3){
            return response()->json(['error' => 'Unauthorized, invalid status!']);
        }
       $order->update(['status' => 4]);
       $url = action('OrderController@addQuotation', $order->id);

       $mailer->to(env('ADMIN1'))->send(new AdminRemindMail(route('addQuotationNoLogin', ['token' => $order->token])));

       OrderLogs::create(['order_id' => $order->id, 'description' => $request->user()->name . ' requested admin for quotation.']);
        request()->session()->flash('status', 'Success! Please wait for the admin.');
    
        return response()->json(['success' => 'Successfully requested quotation!']);
     }

     public function addQuotation(Order $order, Request $request){
        if($request->segment(4)){
            $order = Order::where('token', $request->segment(4))->first();
            if($order == null){
                abort(404);
            }
        }
            if (!($order->status == 4 || $order->status == 3)){
                abort(401, 'Unauthorized, this transaction is not yet for Quotation.');
            }
            return view('order.addQuotation', compact('order'));
     }

     public function addQuotationStore(Request $request, Order $order, Mailer $mailer){
         if (!($order->status == 4 || $order->status == 3)){
            return response()->json(['status' => 'Unauthorized, this transaction is not yet for Quatation.']);
        }

        $validator = Validator::make($request->all(), ['price' => 'required']);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
            }
        $price = str_replace( ',', '', $request->input('price'));
        $order->update(['status' => 15, 'price' => $price, 'price_date' => \Carbon::now()]);


        $mailer->to($order->ordered_by == null ? $order->supplier_by->email : $order->ordered_by->email)->send(new AdminAddQuotationMail($order));

        OrderLogs::create(['order_id' => $order->id, 'description' => 'Admin quoted this transaction for PHP ' . number_format($price, 2) . ' shipping fee.' ]);
        $request->session()->flash('status', 'Successfully added quotation!');                               
        return response()->json(['success' => 'Success!']);

     }
     public function acceptFee(Order $order, Request $request, Mailer $mailer){

        if ($order->status != 5){ 
            return response()->json(['error' => 'Unauthorized, status should be waiting for customer confirmation..']);
        }
        if ($order->user_id != request()->user()->id){
            return response()->json(['error' => 'Unauthorized, sorry you are not able to do this.']);
        }
        $order->update(['status' => '6']);
        OrderLogs::create(['order_id' => $order->id, 'description' => $request->user()->name . ' accepted shipping fee.']);
        $request->session()->flash('status', 'Successfully accepted fee!');
        $mailer->to($order->supplier_by ? $order->supplier_by->email : $order->email)->send(new FeeAccepted($order));
        return response()->json(['success' => 'success']);
     }


     public function GetStorePhotos(Order $order){
        if($order->status != 6){
            abort(401);
        }
        if($order->supplier_id != auth()->user()->id){
            abort(401);
        }

        return view('order.addPhotos', compact('order'));

     }

     public function StorePhotos(Order $order, Request $request, Mailer $mailer){
     //   dd($request->all());

        if($order->status != 6){
            return response()->json(['status' => 'Unauthorized, invalid status.']);
        }
        if($order->supplier_id != auth()->user()->id){
            return response()->json(['status' => 'Unauthorized, sorry you are not able to do this.']);
        }

        $validator = Validator::make($request->all(), [
            'boxes.*' => ['required', 'mimes:jpeg,bmp,png'],
            'boxes' => 'required'
        ],
        [
            'boxes.*.required' => 'This field is required',
            'boxes.*.mimes' => 'Only jpeg,png is allowed.'
        ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => ['boxes' => $validator->errors()->first()]]);
        }
        // dd($request->boxes);
        $boxes = array();
        foreach($request->boxes as $box){
            $new_name = uniqid() . '.' . $box->getClientOriginalExtension();
            $box->move(public_path('images/boxes') , $new_name);
            $boxes[] = $new_name;
        }
        $boxes = implode('#', $boxes);
        $order->update(['status' => 61, 'boxes' => $boxes]);

        $mailTitle = 'Pictures of Shipment ' . $order->shipment_id;

        $mailer->to($order->ordered_by->email)->send(new DynamicEmail(['order' => $order], $mailTitle , 'mails.order.UploadedPictures'));

        OrderLogs::create(['order_id' => $order->id, 'description' => $request->user()->name . ' added photos Boxes pictures.']);
        $request->session()->flash('status', 'Next Step: Please wait for customers approval of the package.');
        // $request->session()->flash('status', 'Next Step: Ship to our warehouse. Please check your email for shipping instructions.');
        return response()->json(['success' => 'success', 'redirect' => action('OrderController@show', [$order->id])]);
     }

     public function acceptInvoice(Order $order, Request $request, Mailer $mailer){

        // if($order->user_id != request()->user()->id){
        //     return response()->json(['error' => 'Unauthorized, sorry you are not able to do this.']);
        // }
        if ($order->status != 7){
            return response()->json(['error' => 'Unauthorized, invalid status!']);
        }
       $order->update(['status' => 8]);

       OrderLogs::create(['order_id' => $order->id, 'description' => $request->user()->name . ' has approved invoice of this transaction .']);
       $request->session()->flash('status', 'Success!');
       return response()->json(['success' => 'success']);
     }

     public function warehouseArrived(Order $order, Request $request){
        if ($order->status != 8){
            return response()->json(['error' => 'Unauthorized, invalid status!']);
        }

        $request->session()->flash('status', 'Success! status updated.');
        $order->update(['status' => 9]);
        OrderLogs::create(['order_id' => $order->id, 'description' => 'Shipment arrived at ' . $order->warehouse]);
        $mailer->to($order->supplier_by ? $order->supplier_by->email : $order->email)->send(new FeeAccepted($order));
        return response()->json(['success' => 'success']);
     }

     public function OnGoing(Order $order, Request $request){
        if ($order->status != 9){
            return response()->json(['error' => 'Unauthorized, invalid status!']);
        }
        $request->session()->flash('status', 'Success! status updated.');
        OrderLogs::create(['order_id' => $order->id, 'description' => 'Shipment on-going.']);
        $order->update(['status' => 10]);

        return response()->json(['success' => 'success']);
     }

     public function phArrived(Order $order, Request $request, Mailer $mailer){
        if ($order->status != 8){
            return response()->json(['error' => 'Unauthorized, invalid status!']);
        }
        $request->session()->flash('status', 'Success! status updated / payment email sent to customer.');
        $order->update(['status' => 9]);

        OrderLogs::create(['order_id' => $order->id, 'description' => 'Shipment arrived at customs.']);

        $mailer->to($order->ordered_by->email)->send(new Payment($order));

        return response()->json(['success' => 'success']);
     }

     public function completeTransaction(Order $order, Request $request, Mailer $mailer){
        if ($order->status != 13){
            return response()->json(['error' => 'Unauthorized, invalid status!']);
        }
        $request->session()->flash('status', 'Successfully completed a transaction!');
        $order->update(['status' => 15]);
        OrderLogs::create(['order_id' => $order->id, 'description' => 'Transaction complete.']);
        $mailer->to($order->supplier_by->email)->send(new DynamicEmail($order, 'Shipment Successfully Delivered : ' . $order->shipment_id , 'mails.order.orderCompleted(supplier)'));
        $order->completeSMS(new Client());
        return response()->json(['success' => 'success']);
     }

     public function ApprovePackage(Order $order, Mailer $mailer){
        if ($order->status != 61){
            return response()->json(['error' => 'Unauthorized, invalid status!']);
        }
        $order->update(['status' => 7]);
        $mailer->to($order->supplier_by->email)->send(new ReadyToShip($order));
        request()->session()->flash('success' , 'Successfully Approved Package.');
        return response()->json(['success' => 'Successfully Approved Package!']);   
     }

     public function DisapprovePackage(Order $order, Mailer $mailer){
        if ($order->status != 61){
            return response()->json(['error' => 'Unauthorized, invalid status!']);
        }
        $order->update(['status' => 6]);
        $mailTitle= 'Shipment ' . $order->shipment_id . ' : Customer Disapproved Package.';
        $mailer->to($order->supplier_by->email)->send(new DynamicEmail($order, $mailTitle , 'mails.order.DisapprovedPackage'));
        request()->session()->flash('success' , 'Successfully Disapprove Package.');
        return response()->json(['success' => 'Successfully Disapprove Package!']);
     }

     public function deliverForm(Order $order){
        if ($order->status != 13){
            abort(401);
        }
        return view('order.deliverForm', compact('order'));
     }

     public function deliverSubmit(Order $order, Mailer $mailer, Request $request){
        if($order->status != 13){
              return response()->json(['status' => 'Unauthorized, invalid status.']);
        }

        $validator = Validator::make($request->all(),[
            'delivery_receipt' => 'required|mimes:jpeg,bmp,png',
            'deliver_company_name' => 'required',
        ],
            ['delivery_receipt.mimes' => 'Only jpeg and png is allowed.']
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $image = $request->file('delivery_receipt');
        $new_name = sha1(time()) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/delivery_receipt') , $new_name);
        $order->update(['delivery_receipt' => $new_name, 'deliver_company_name' => $request->input('deliver_company_name') , 'status' => 15]);
        $request->session()->flash('status', 'Successfully uploaded delivery receipt!');
        $mailer->to($order->supplier_by->email)->send(new DynamicEmail($order, 'Shipment Successfully Delivered : ' . $order->shipment_id , 'mails.order.orderCompleted(supplier)'));
        $order->completeSMS(new Client());

        // OrderLogs::create(['order_id' => $order->id, 'description' => $request->user()->name . ' added delivery receipt.']);
        return response()->json(['success' => 'success', 'redirect' => action('OrderController@show' , [$order->id])]);
     }
    
    public function paySupplier(Order $order){
        return view('order.paySupplier', compact('order'));
    }

    public function paySupplierStore(Order $order, Request $request, Mailer $mailer){
        $validator = Validator::make($request->all(),[
            'supplier_payment' => 'required|mimes:jpeg,bmp,png',
        ],
            ['supplier_payment.mimes' => 'Only jpeg and png is allowed.']
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $image = $request->file('supplier_payment');
        $new_name = sha1(time()) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/supplier_payment') , $new_name);
        $order->update(['supplier_payment' => $new_name, 'status' => 3, 'withQuote' => false]);
        $mailer->to($order->supplier_by->email)->send(new DynamicEmail($order, 'Customer payment details', 'mails.order.CustomerPayment'));
        $request->session()->flash('status', 'Successfully added payment.');
        return response()->json(['success' => 'success']);
    }
}
