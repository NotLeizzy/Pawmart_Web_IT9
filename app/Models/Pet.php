<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $fillable = ['name', 'species', 'breed', 'age', 'age_unit', 'price', 'status', 'image'];

    public function petOrders()
    {
        return $this->hasMany(PetOrder::class);
    }
}