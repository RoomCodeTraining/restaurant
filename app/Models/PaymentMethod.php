<?php

namespace App\Models;

use App\Enums\UserTypes;
use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public static function getPaymentMethodForUser(User $user)
    {
        switch ($user->user_type) {
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
