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

    public const IMPROVEMENT_APPLICATION = 1;
    public const IMPROVEMENT_CANTEEN_SERVICE = 2;

    public function suggestions()
    {
        return $this->hasMany(SuggestionBox::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeImprovementApplication($query)
    {
        return $query->where('id', self::IMPROVEMENT_APPLICATION);
    }

    public function scopeImprovementCanteenService($query)
    {
        return $query->where('id', self::IMPROVEMENT_CANTEEN_SERVICE);
    }
}
