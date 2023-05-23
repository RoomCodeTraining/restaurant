<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MenuSpecal extends Pivot
{
    protected $guarded = [];

    public function dish() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }
}
