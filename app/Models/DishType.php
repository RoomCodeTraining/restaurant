<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const DESSERT = 1;
    public const STARTER = 2;
    public const MAIN = 3;

    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }
}
