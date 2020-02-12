<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Order;

class OrderDetails extends Model
{
    //
     protected $table = 'orderdd';

     protected $fillable = ['length', 'width','height','weight','measurement','photo','qty', 'type'];

     public function header(){
     	return $this->belongsTo(Order::class, 'order_id', 'id');
     }

     public function get_dimensional_weight(){
     	$value = $this->length +$this->width + $this->height;
        $value = $value / 139;
        $value = $value * $this->qty;
     	return $value;
     }

     public static function get_dimensional_weight_static($length, $width, $height){
        $value = $length + $width + $height / 139;
        // $value = $value * $this->qty;
        return $value;
     }

     public function get_img_url(){
         return url('/images/' . $this->photo);
      }

      public static function get_type_select(){
        return [
            "Pallet" => "Pallet",
            "Box" => "Box",
            "Sacks" => "Sacks",
            "Drums" => "Drums",
            "Wooden Crates" => "Wooden Crates",
            "Other" => "Other",
        ];


      }
}
