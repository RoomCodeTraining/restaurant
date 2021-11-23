<?php

namespace App\Models;

use App\States\Order\Cancelled;
use App\States\Order\OrderState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;

class Order extends Model
{
    use HasFactory;

    use HasStates;

    protected $guarded = [];

    protected $casts = [
        'state' => OrderState::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function canBeCancelled()
    {
        return $this->state->canTransitionTo(Cancelled::class);
    }

    public function canBeUpdated()
    {
        return $this->canBeCancelled();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
