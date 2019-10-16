<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dollar extends Model
{
    protected $table = 'dollar';

    protected $fillable = ['rate'];
}
