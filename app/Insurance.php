<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{

    protected $table = 'insurance';

    protected $fillable = ['order_id', 'amount', 'fee', 'status', 'user_id', 'deposit', 'declaration', 'invoice'];

    public function order(){
    	return $this->belongsTo(Order::class, 'order_id');
    }

    public function ordered_by(){
      return $this->belongsTo(User::class, 'user_id');
    }

    public function getDepositUrl(){
        return url('/images/insurance/deposit/' . $this->deposit);
     }

     public function getDeclarationUrl(){
        return url('/images/insurance/declaration/' . $this->declaration);
     }

     public function getInvoiceUrl(){
        return url('/images/insurance/invoice/'  . $this->invoice);
     }

    public function getStatusDisplay(){
          if($this->status == 1){
            return 'Waiting for admin confirmation';
          }else if ($this->status == 2){
               return 'Rejected';
          }else if ($this->status == 3){
               return 'Waiting for deposit';
          }elseif ($this->status == 4){
               return 'Waiting for Admin';
          }elseif ($this->status == 5){
               return 'Complete';
      }
     }

}
