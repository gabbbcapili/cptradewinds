<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderLogs extends Model
{
    protected $table = 'order_logs';

    protected $fillable = ['order_id', 'description'];

    public function order(){
    	return $this->belongsTo(Order::class, 'order_id');
    }
}
