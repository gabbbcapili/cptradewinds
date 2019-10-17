<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\OrderDetails;
use App\OrderKey;
use GuzzleHttp\Client;
use App\Source;

class Order extends Model
{
    //
     protected $table = 'orderhh';

     protected $fillable = ['supplier', 'email', 'user_id', 'supplier_id', 'status', 'import_details', 'location', 'cbm', 'weight', 'price', 'price_date' , 'warehouse', 'payment', 'payment_date' , 'quoteFor', 'boxes', 'pickup_location', 'withQuote', 'shipment_id', 'invoice_no', 'shipment_proof','boxes_received', 'source_id', 'token'];
     
     public function details(){
     	return $this->hasMany(OrderDetails::class, 'order_id', 'id')->whereNull('type');
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
          // elseif ($this->status == 7){
          //      return 'Wating for Admin Confirmation.';
          // }
          elseif ($this->status == 7){
               return 'Wating for Shipment';
          }
          elseif ($this->status == 8){
               return 'Processing';
          }elseif ($this->status == 9){
               return 'Arrived(Warehouse)';
          }elseif ($this->status == 10){
               return 'Shipping On-going';
          }elseif ($this->status == 11){
               return 'Arrived(PH) - Waiting for payment';
          }elseif ($this->status == 12){
               return 'Arrived(PH) - Waiting for admin payment confirmation';
          }elseif ($this->status == 13){
               return 'Arrived(PH) - Ready for pick up';
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
        return $cbm;
      }
      public function UpdateTotalWeight(){
          $weight = 0;
        foreach($this->details as $detail){
             $weight = $weight + ($detail->weight * $detail->qty);
        }
        $this->update(['weight' => $weight]);
        return $weight;
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


      public function clearedSMS(Client $client){
        $msg = 'Good news! Shipment '. $this->shipment_id . ' has cleared customs and will be ready for pickup as soon as you settle the payment. Please check your email for details. Thank you! ';

        $client = $client->post('https://api.semaphore.co/api/v4/messages',
             [ 'form_params' => 
                [ 'apikey' => env('SEMAPHORE_KEY'), 'number' => $this->ordered_by->phone_no, 'message' => $msg, 'sendername' => env('SEMAPHORE_FROM_NAME')]
            ]);
        return $client;

      }

      public function paymentConfirmedSMS(Client $client){
        $msg = 'Payment for Shipment '. $this->shipment_id . ' has been confirmed. You may now pickup your shipment. Please check your email for details.';
        $client = $client->post('https://api.semaphore.co/api/v4/messages',
             [ 'form_params' => 
                [ 'apikey' => env('SEMAPHORE_KEY'), 'number' => $this->ordered_by->phone_no, 'message' => $msg, 'sendername' => env('SEMAPHORE_FROM_NAME')]
            ]);
         return $client;
      }

      public function completeSMS(Client $client){
        $msg = 'This concludes Shipment '. $this->shipment_id . '. We hope to have served you well. Until your next shipment, thank you and God bless your business.';
        $client = $client->post('https://api.semaphore.co/api/v4/messages',
             [ 'form_params' => 
                [ 'apikey' => env('SEMAPHORE_KEY'), 'number' => $this->ordered_by->phone_no, 'message' => $msg, 'sendername' => env('SEMAPHORE_FROM_NAME')]
            ]);
         return $client;
      }



}
