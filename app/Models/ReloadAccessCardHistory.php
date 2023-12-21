<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReloadAccessCardHistory extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function accessCard()
    {
        return $this->belongsTo(AccessCard::class);
    }
}