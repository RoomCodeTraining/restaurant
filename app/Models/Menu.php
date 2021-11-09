<?php

namespace App\Models;

use App\Models\Dish;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'starter_dish_id',
        'main_dish_id',
        'second_dish_id',
        'dessert_id',
        'served_at'
    ];



    protected $dates = [
        'served_at' => 'Y-m-d',
    ];


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

    public function dessertDish(){
        return $this->belongsTo(Dish::class, 'dessert_id');
    }

    public function getServedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->toFormattedDateString();
    }
}
