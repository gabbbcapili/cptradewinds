<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Order;

class Source extends Model
{
    protected $table = 'source';

     protected $fillable = ['name','is_deleted'];

     public function quotations(){
     	return $this->hasMany(Order::class, 'source_id', 'id')->where('withQuote', true);
     }
     public function shipments(){
     	return $this->hasMany(Order::class, 'source_id', 'id')->where('withQuote', false);
     }
}
