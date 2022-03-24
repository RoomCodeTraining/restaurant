<?php

namespace App\Models;

use App\Support\ActivityHelper;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $guarded = [];

    

    protected $casts = [
        'served_at' => 'date',
    ];

    protected $dates = [
        'served_at' => 'Y-m-d',
    ];



    public function getStarterAttribute()
    {
        return $this->dishes()->starter()->first();
    }

    public function scopeToday($query)
    {
        return $query->whereDate('served_at', today());
    }

    public function getMainDishAttribute()
    {
        return $this->dishes()->main()->orderBy('id', 'asc')->first();
    }


    public function mainDishes()
    {
        return $this->dishes()->main();
    }

    public function getSecondDishAttribute()
    {
        $dishes = $this->dishes()->main()->orderBy('id', 'desc')->get();

        if ($dishes->count() > 1) {
            return $dishes->first();
        }
    }

    public function getDessertAttribute()
    {
        return $this->dishes()->dessert()->first();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function dishes()
    {
        return $this->belongsToMany(Dish::class);
    }

    public function canBeOrdered(): bool
    {
        return $this->served_at->greaterThanOrEqualTo(\Illuminate\Support\Carbon::today());
    }

    public function canBeUpdated(): bool
    {
        if ($this->served_at->greaterThan(today())) {
            return true;
        }

        if ($this->served_at->isCurrentDay() && now()->hour < config('cantine.menu.locked_at')) {
            return true;
        }

        return false;
    }


}
