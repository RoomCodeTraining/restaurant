<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getRouteKey()
    {
        return $this->identifier;
    }

    public function getRouteKeyName()
    {
        return 'identifier';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
