<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Order;

class OrderKey extends Model
{
    protected $table = 'order_key';
    
    protected $fillable = ['token', 'order_id', 'consumed', 'type'];

    public function order(){
    	return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public static function get_available_key(){
    	$token = sha1(time());
            $key = OrderKey::where('token', $token)->get();
            do{
                //re-token
                $token = sha1(time());
                $key = OrderKey::where('token', $token)->get();
            }while(!$key->count() == 0);
        return $token;
    }
}
