<?php

namespace App\Models;

use App\States\Order\Cancelled;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use App\States\Order\OrderState;
use App\Support\DateTimeHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelStates\HasStates;

class Order extends Model
{
    use HasFactory;

    use HasStates;

    use SoftDeletes;
    protected static $recordEvents = [];


    protected $guarded = [];


    protected $casts = [
        'state' => OrderState::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_decrement' => 'boolean',
    ];

    public const LUNCH = "Commande pour midi";
    public const EVENING = "Commande pour le soir";



    protected static function booted()
    {
        static::addGlobalScope('lunch', function (Builder $builder) {
            $builder->whereIn('type', ['lunch']);
        });
    }

    public function scopeToday($query)
    {
        return $query->whereHas('menu', fn ($query) => $query->whereDate('served_at', today()));
    }

    public function scopeWeekly($query)
    {
        return $query->whereHas('menu', fn ($query) => $query->whereBetween('served_at', [now()->startOfWeek(), now()->endOfWeek()]));
    }

    public function scopeFuturOrder($query)
    {
        return $query->whereHas('menu', fn ($query) => $query->whereDate('served_at', '>=', today()));
    }


    public function scopeMonthly($query)
    {
        return $query->whereHas('menu', fn ($query) => $query->whereBetween('served_at', [now()->startOfMonth(), now()->endOfMonth()]));
    }



    public function scopeFilter($query, $period)
    {
        return $query->whereHas('menu', fn ($query) => $query->whereBetween('served_at', DateTimeHelper::inThePeriod($period)));
    }

    public function scopeBreakfastPeriodFilter($query, $period)
    {
        return $query->where('created_at', DateTimeHelper::inThePeriod($period));
    }

    public function canBeCancelled()
    {
        return $this->state->canTransitionTo(Cancelled::class);
    }

    public function canBeUpdated()
    {
        if ($this->menu->served_at->greaterThan(today()) && $this->canBeCancelled()) {
            return true;
        }

        if ($this->menu->served_at->isCurrentDay() && now()->hour < config('cantine.order.locked_at') && $this->canBeCancelled()) {
            return true;
        }

        return false;
    }

    public function markAsCompleted()
    {
        $this->state->transitionTo(Completed::class);
    }

    public function markAsConfirmed()
    {
        $this->state->transitionTo(Confirmed::class);
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

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function accessCard()
    {
        return $this->belongsTo(AccessCard::class);
    }


    public function getOrderTypeAttribute()
    {
        return $this->type == 'lunch' ? 'Dejeuner' :  'Pétit dejeuner';
    }

    public function isToday()
    {
        return $this->menu->served_at->isCurrentDay();
    }

}