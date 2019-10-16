<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clearance extends Model
{
    protected $table = 'clearance';

    protected $fillable = ['user_id', 'fullname', 'email', 'mobile_number', 'delivery_address', 'invoice', 'waybill', 'shipping_company', 'supplier_name', 'supplier_email', 'admin3_price' , 'admin1_price', 'customer_deposit', 'admin1_deposit', 'status', 'tracking_no'];

    public function clearance_by(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function get_status_display(){
    	if($this->status == 1){
            return 'pending';
        }else if($this->status == 2){
            return 'processing';
        }else if($this->status == 3){
            return 'processing';
        }
        else if($this->status == 4){
            return 'processing';
        }
        else if($this->status == 5){
            return 'waiting for confirmation';
        }
        else if($this->status == 6){
            return 'Completed';
        }
    }

    public function get_invoice_url(){
        return url('/images/clearance/invoice/' . $this->invoice);
     }

     public function get_waybill_url(){
        return url('/images/clearance/waybill/' . $this->waybill);
     }

     public function get_total_quotation(){
        return $this->admin3_price + $this->admin1_price;
     }
     public function get_customer_deposit_url(){
        return url('/images/clearance/customer_deposit/' . $this->customer_deposit);
     }
     public function get_admin1_deposit_url(){
        return url('/images/clearance/admin1_deposit/' . $this->admin1_deposit);
     }

     
     
}
