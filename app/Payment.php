<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $table = 'payment';

    protected $fillable = ['supplier_email', 'supplier_name' , 'bank_address', 'supplier_address', 'bank_name', 'account_name', 'bank_address', 'swift_code', 'invoice', 'amount', 'user_id', 'supplier_id' , 'status', 'rate', 'deposit', 'ssDeposit', 'order_id'];
     public function ordered_by(){
        return $this->belongsTo(User::class, 'user_id');
     }
     public function supplier_by(){
        return $this->belongsTo(User::class, 'supplier_id');
     }
     public function order(){
        return $this->belongsTo(Order::class, 'order_id');
     }
     public function getStatusDisplay(){
     	if ($this->status == 1){
     		return 'Pending';
     	}elseif($this->status == 2){
     		return 'Cancelled';
     	}elseif($this->status == 3){
     		return 'Waiting for Supplier Details';
     	}elseif($this->status == 4){
     		return 'Waiting for Buyer Confirmation';
     	}elseif($this->status == 5){
     		return 'Waiting for Deposit';
     	}elseif($this->status == 6){
     		return 'Waiting Admin Payment Confirmation';
     	}elseif($this->status == 7){
            return 'Completed';
        }
     }
     public function get_img_url(){
        return url('/images/invoice/' . $this->invoice);
     }
     public function getInvoiceUrl(){
        return url('/images/invoice/' . $this->invoice);
     }

     public function deposits(){
        return explode('#', $this->deposit);
     }


     public static function getDepositUrl($url){
        return url('/images/deposit/' . $url);
     }

     public function getSSDeposit(){
        return url('/images/ssDeposit/' . $this->ssDeposit);
     }

     public function total_amount(){
        $total = $this->rate * $this->amount;
        $total += 1500;
        return $total;
     }
     public static function isTimeFrameAllowed(){
            $time = date("Hi");
            $start = '900';
            $end   = '2400';
            if (($time >= $start )&&($time <= $end)) {
                return TRUE;
            } else {
                return FALSE;
            }
     }

     public static function getCutOffString(){
        return 'Sorry our payment services is only available at 9:00am to 2:00pm. Please come back tommorow.';
     }
}

