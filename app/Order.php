<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\OrderDetails;
use App\Payment;
use App\OrderKey;
use App\OrderItems;
use GuzzleHttp\Client;
use App\Source;

class Order extends Model
{
    //
     protected $table = 'orderhh';

     protected $fillable = ['supplier', 'email', 'user_id', 'supplier_id', 'status', 'import_details', 'location', 'cbm', 'weight', 'price', 'price_date' , 'warehouse', 'payment', 'payment_date' , 'quoteFor', 'boxes', 'pickup_location', 'withQuote', 'shipment_id', 'invoice_no', 'shipment_proof','boxes_received', 'source_id', 'token', 'shipped_at', 'delivery_address', 'delivery_price', 'pickup_type', 'extra_charges','delivery_receipt', 'deliver_company_name', 'pickup_person', 'pickup_time', 'notes', 'supplier_payment'];

     public function details(){
     	return $this->hasMany(OrderDetails::class, 'order_id', 'id')->whereNull('type');
     }

     public function payments(){
      return $this->hasMany(Payment::class, 'order_id', 'id');
     }

     public function items(){
      return $this->hasMany(OrderItems::class, 'order_id', 'id');
     }

     public function types(){
      return $this->hasMany(OrderDetails::class, 'order_id', 'id')->whereNotNull('type');
     }

     public function key(){
          return $this->hasOne(OrderKey::class, 'order_id', 'id');
     }

     public function ordered_by(){
          return $this->belongsTo(User::class, 'user_id');
     }

     public function supplier_by(){
          return $this->belongsTo(User::class, 'supplier_id');
     }

     public function source(){
        return $this->belongsTo(Source::class, 'source_id', 'id');
     }

     public function total_boxes(){
     	$total = 0;
     	foreach($this->details as $detail){
     			$total++;
     		}
     		return $total;
     }
     public function total_quantity(){
     	$total = 0;
     	foreach($this->types as $type){
     			$total = $total + $type->qty;
     		}
     		return $total;
     }

     public function get_status_display(){
          if ($this->status == 1){
               return 'Pending';
          }elseif ($this->status == 2){
               return 'Declined / Cancelled';
          }elseif ($this->status == 3){
               return 'Waiting for Supplier Input';
          }elseif ($this->status == 4){
               return 'Waiting for Admin Quotation';
          }elseif ($this->status == 5){
               return 'Wating for Customer Confirmation(FEE)';
          }elseif ($this->status == 6){
               return 'Waiting for supplier boxes pictures';
          }
          elseif ($this->status == 61){
               return 'Waiting for Customer package approval';
          }

          // elseif ($this->status == 7){
          //      return 'Wating for Admin Confirmation.';
          // }
          elseif ($this->status == 7){
               return 'Wating for Shipment';
          }
          elseif ($this->status == 8){
               return 'Processing';
          }elseif ($this->status == 9){
               return 'Arrived(Warehouse) - Waiting for Customer delivery address';
          }elseif ($this->status == 10){
               return 'Waiting for admin\'s shipping cost';
          }elseif ($this->status == 11){
               return 'Arrived(PH) - Waiting for payment';
          }elseif ($this->status == 12){
               return 'Arrived(PH) - Waiting for admin payment confirmation';
          }elseif ($this->status == 13){
                if($this->pickup_type == 'pickup'){
                   return 'Arrived(PH) - Waiting for customer pick-up details';
                }elseif($this->pickup_type == 'deliver'){
                    return 'Arrived(PH) - Waiting for admin delivery details';
                }
          }elseif ($this->status == 14){
               return 'Delivered / Picked up';
          }elseif ($this->status == 15){
               return 'Completed';
          }
     }

     public function get_measurement_display(){
          if ($this->measurement == 'cm'){
               return 'Centimeters';
          }elseif ($this->measurement == 'm'){
               return 'Meters';
          }elseif ($this->measurement == 'ft'){
               return 'Feet';
          }elseif ($this->measurement == 'in'){
               return 'Inches';
          }
     }

      public function get_shipment_proof_url(){
         return url('/images/' . $this->shipment_proof);
      }

      public function get_payment_url(){
         return url('/images/' . $this->payment);
      }

      public function get_box_url($file){
         return url('/images/boxes/' . $file);
      }

      public function get_delivery_receipt_url(){
         return url('/images/delivery_receipt/' . $this->delivery_receipt);
      }

      public function get_supplier_payment_url(){
         return url('/images/supplier_payment/' . $this->supplier_payment);
      }

      

      public function getQuoteForDisplay(){
          if ($this->quoteFor == 1){
               return 'Single or multiple cargo shipment';
          }elseif($this->quoteFor == 2){
               return 'Get quote based on gross CBM and KGS';
          }else{
               return '';
          }
      }
     public function UpdateTotalCBM(){
        $cbm = 0;
        foreach($this->details as $detail){
               $cbm = $cbm + $detail->get_dimensional_weight();
        }
        $this->update(['cbm' => $cbm]);
        return $this;
      }
      public function UpdateTotalWeight(){
          $weight = 0;
        foreach($this->details as $detail){
             $weight = $weight + ($detail->weight * $detail->qty);
        }
        $this->update(['weight' => $weight]);
        return $this;
      }

      public function getShippingMarkPrefix(){
        $prefix = substr($this->location, 0, 2);

        return strtoupper($prefix);
      }

      public function updateShipmentID(){

         $prefix = strtoupper(substr($this->location, 0, 2)) . $this->id;
         $this->update(['shipment_id' => $prefix]);

      }

      public function startedSMS(Client $client, $phone_no){
        $msg = 'This is to inform you that the process for shipment '. $this->shipment_id . ' coming from ' . $this->location . ' has started. Please check your email for details. Thanks!';
         $client = $client->post('https://api.semaphore.co/api/v4/messages',
             [ 'form_params' => 
                [ 'apikey' => env('SEMAPHORE_KEY'), 'number' => $phone_no, 'message' => $msg, 'sendername' => env('SEMAPHORE_FROM_NAME')]
            ]);
         return $client;
      }

      public function shippedSMS(){
        $client = new Client();
        $msg = 'This is to inform you that the process for shipment '. $this->shipment_id . ' coming from ' . $this->location . '. The cargo will be received at the foreign warehouse in 1 to 3 days. Upon arrival, the shipment will be loaded immediately in the next container bound to Manila. No inspections will take place on our end. After 5 days, the cargo will arrive at Philippine customs. At an AVERAGE of 2 weeks, it will be released. Please be informed that we do not guarantee release dates, we only disclose average time frames. Actual releasing sometimes occur sooner or later than expected. Thanks!';
         $client = $client->post('https://api.semaphore.co/api/v4/messages',
             [ 'form_params' => 
                [ 'apikey' => env('SEMAPHORE_KEY'), 'number' => $this->ordered_by->phone_no, 'message' => $msg, 'sendername' => env('SEMAPHORE_FROM_NAME')]
            ]);
         return $client;
      }

      public function automatedDay3SMS(){
        $client = new Client();
        $msg = 'This is to inform you that the process for shipment '. $this->shipment_id . ' coming from ' . $this->location . ' is now loaded in a container bound for Manila. Thanks!';
         $client = $client->post('https://api.semaphore.co/api/v4/messages',
             [ 'form_params' => 
                [ 'apikey' => env('SEMAPHORE_KEY'), 'number' => $this->ordered_by->phone_no, 'message' => $msg, 'sendername' => env('SEMAPHORE_FROM_NAME')]
            ]);
         return $client;
      }

      public function automatedDay4SMS(){
        $client = new Client();
        $msg = 'This is to inform you that the process for shipment '. $this->shipment_id . ' coming from ' . $this->location . ' is now on the way to Manila. It will arrive at Philippine Customs in an average of three days. Thanks!';
         $client = $client->post('https://api.semaphore.co/api/v4/messages',
             [ 'form_params' => 
                [ 'apikey' => env('SEMAPHORE_KEY'), 'number' => $this->ordered_by->phone_no, 'message' => $msg, 'sendername' => env('SEMAPHORE_FROM_NAME')]
            ]);
         return $client;
      }

      public function automatedDay6SMS(){
        $client = new Client();
        $msg = 'This is to inform you that the process for shipment '. $this->shipment_id . ' coming from ' . $this->location . ' has now entered the clearance process. The average clearing time is two weeks. Please visit your dashboard to provide the delivery address. Thanks!';
         $client = $client->post('https://api.semaphore.co/api/v4/messages',
             [ 'form_params' => 
                [ 'apikey' => env('SEMAPHORE_KEY'), 'number' => $this->ordered_by->phone_no, 'message' => $msg, 'sendername' => env('SEMAPHORE_FROM_NAME')]
            ]);
         return $client;
      }

      public function clearedSMS(Client $client){
        $msg = 'Good news! Shipment '. $this->shipment_id . ' has cleared customs and will be ready for pickup as soon as you settle the payment. Please check your email for details. Thank you! ';

        $client = $client->post('https://api.semaphore.co/api/v4/messages',
             [ 'form_params' => 
                [ 'apikey' => env('SEMAPHORE_KEY'), 'number' => $this->ordered_by->phone_no, 'message' => $msg, 'sendername' => env('SEMAPHORE_FROM_NAME')]
            ]);
        return $client;

      }

      public function paymentConfirmedSMS(Client $client){
        $msg = '';
        if($this->pickup_type == 'deliver'){
          $msg = 'Payment for Shipment '. $this->shipment_id . ' has been confirmed. Please visit your account and indicate the person or company picking up, as well as the approximate time of pick up. Thanks!';
        }elseif($this->pickup_type == 'pickup'){
          $msg = 'Payment for Shipment '. $this->shipment_id . ' has been confirmed. You may now pickup your shipment. Please check your email for details.';
        }
        $client = $client->post('https://api.semaphore.co/api/v4/messages',
             [ 'form_params' => 
                [ 'apikey' => env('SEMAPHORE_KEY'), 'number' => $this->ordered_by->phone_no, 'message' => $msg, 'sendername' => env('SEMAPHORE_FROM_NAME')]
            ]);
         return $client;
      }

      public function completeSMS(Client $client){
        $msg = 'This concludes Shipment '. $this->shipment_id . '. Thank you for using our service. We look forward to serving you again for your future. If you’re happy with our service, we’d like to offer you a 5% rebate based on the amount you paid when you leave a positive review on our FB page ('. env('FB_PAGE') .').';
        $client = $client->post('https://api.semaphore.co/api/v4/messages',
             [ 'form_params' => 
                [ 'apikey' => env('SEMAPHORE_KEY'), 'number' => $this->ordered_by->phone_no, 'message' => $msg, 'sendername' => env('SEMAPHORE_FROM_NAME')]
            ]);
         return $client;
      }



}
