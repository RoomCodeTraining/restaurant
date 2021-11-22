<?php

namespace App\Models;

use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessCard extends Model
{
    use HasFactory;


    protected $fillable = [
        'identifier',
        'quota_breakfast',
        'quota_lunch',
        'user_id',
        'payment_method_id',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
