<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function  isOldMenu() : bool
    {
        return $this->served_at->lessThan(today()) ? true : false;
    }

    public function getMainDishAttribute()
    {
        return $this->dishes()->main()->orderBy('id', 'asc')->first();
    }

    public function getSecondDishAttribute()
    {
        return $this->dishes()->main()->orderBy('id', 'desc')->first();
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

    public function canBeOrdered()
    {
        return $this->served_at->greaterThanOrEqualTo(\Illuminate\Support\Carbon::today());
    }
}
