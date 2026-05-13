<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
   
    protected $fillable = ['order_id', 'amount', 'status', 'payment_method', 'reference_number', 'account_info'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}