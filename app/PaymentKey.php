<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Payment;

class PaymentKey extends Model
{
    protected $table = 'payment_key';
    
    protected $fillable = ['token', 'payment_id', 'consumed', 'type'];

    public function payment(){
    	return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }
}
