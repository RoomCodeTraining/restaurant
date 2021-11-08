<?php

namespace App\Models;

use App\Models\DishType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dish extends Model
{
    use HasFactory;


    protected $fillable = [
        'name', 'description', 'dish_type_id', 'image'
    ];

    public function dish_type()
    {
        return $this->belongsTo(DishType::class);
    }
}
