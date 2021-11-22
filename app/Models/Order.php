<?php

namespace App\Models;

use App\Models\Dish;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use App\States\Order\OrderState;
use Spatie\ModelStates\HasStates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
