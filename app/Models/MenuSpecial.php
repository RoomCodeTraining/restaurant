<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MenuSpecial extends Pivot
{
    protected $guarded = [];

    protected $table = 'menu_specal';

    public function dish() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }
}