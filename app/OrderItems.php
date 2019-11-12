<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $table = 'order_item';
    
    protected $fillable = ['qty', 'name', 'unit'];
}
