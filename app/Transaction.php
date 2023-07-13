<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'product_name',
        'product_description',
        'product_price',
        'status',
        'quantity',
        'user_id',
        'total_price'
    ];
}