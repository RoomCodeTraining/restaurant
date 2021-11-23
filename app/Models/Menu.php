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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function starterDish()
    {
        return $this->belongsTo(Dish::class, 'starter_dish_id');
    }

    public function mainDish()
    {
        return $this->belongsTo(Dish::class, 'main_dish_id');
    }

    public function secondDish()
    {
        return $this->belongsTo(Dish::class, 'second_dish_id');
    }

    public function dessertDish()
    {
        return $this->belongsTo(Dish::class, 'dessert_id');
    }
}
