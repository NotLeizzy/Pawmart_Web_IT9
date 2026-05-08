<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $fillable = ['name', 'species', 'breed', 'age', 'price', 'status'];

    public function petOrders()
    {
        return $this->hasMany(PetOrder::class);
    }
}