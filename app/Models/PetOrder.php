<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetOrder extends Model
{
     protected $fillable = ['order_id', 'pet_id', 'price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}