<?php

namespace App\Models;

use App\Enums\UserTypes;
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

    public static function getPaymentMethodForUser(User $user)
    {
        switch ($user->user_type_id) {
            case UserTypes::CIPREL_AGENT:
                return 'Postpaid';
            case UserTypes::NON_CIPREL_AGENT:
                return 'Cash';
            case UserTypes::GUEST:
                return 'Subvention';
            case UserTypes::INTERN:
                return 'Subvention';
            default:
                break;
        }
    }
}
