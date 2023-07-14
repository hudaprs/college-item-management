<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'product_name',
        'product_description',
        'product_price',
        'product_image',
        'status',
        'quantity',
        'user_id',
        'total_price'
    ];
}