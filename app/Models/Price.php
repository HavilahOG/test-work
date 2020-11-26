<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'account_id',
        'user_id',
        'quantity',
        'value',
    ];

}
