<?php

namespace App\Models;

use App\Models\Dish;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_confirmed',
        'is_completed',
        'dish_id',
        'menu_id',
    ];


    protected $casts = [
        'is_confirmed' => 'boolean',
        'is_completed' => 'boolean',
    ];


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
