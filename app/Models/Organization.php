<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasFactory;


    protected $fillable = ['name'];


    protected function users()
    {
        return $this->hasMany(User::class);
    }
}
