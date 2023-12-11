<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    public function accessCards()
    {
        return $this->hasMany(AccessCard::class);
    }
}