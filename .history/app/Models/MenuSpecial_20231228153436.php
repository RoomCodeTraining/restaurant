<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MenuSpecial extends Pivot
{
    protected $guarded = [];

    protected $table = 'menu_specal';

    protected $casts = [
        'served_at' => 'date',
    ];

    protected $dates = [
        'served_at' => 'Y-m-d',
    ];

    public function dish(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }
}
