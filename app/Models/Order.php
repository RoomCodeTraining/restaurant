<?php

namespace App\Models;

use App\States\Order\Cancelled;
use App\States\Order\OrderState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\ModelStates\HasStates;

class Order extends Model
{
    use HasFactory;

    use HasStates;

    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'state' => OrderState::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeToday($query)
    {
        return $query->whereHas('menu', fn ($query) => $query->whereDate('served_at', Carbon::today()));
    }

    public function scopeWeekly($query)
    {
        return $query->whereHas('menu', fn ($query) => $query->whereBetween('served_at', [now()->startOfWeek(), now()->endOfWeek()]));
    }

    public function scopeMonthly($query)
    {
        return $query->whereHas('menu', fn ($query) => $query->whereBetween('served_at', [now()->startOfMonth(), now()->endOfMonth()]));
    }

    public function canBeCancelled()
    {
        return $this->state->canTransitionTo(Cancelled::class);
    }

    public function canBeUpdated()
    {
        if ($this->created_at->greaterThan(today())) {
            return true;
        }

        if ($this->created_at->isCurrentDay() && now()->hour < config('cantine.order.locked_at') && $this->canBeCancelled()) {
            return true;
        }

        return false;
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
