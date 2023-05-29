<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'family', 'is_entitled_two_dishes', 'description'];

    public const GROUP_1 = 'Famille A';
    public const GROUP_2 = 'Famille B';

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
}
