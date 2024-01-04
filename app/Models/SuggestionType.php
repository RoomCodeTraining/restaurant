<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuggestionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function suggestions()
    {
        return $this->hasMany(SuggestionBox::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }


}