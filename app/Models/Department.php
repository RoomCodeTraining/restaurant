<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
    ];


    public function employees()
    {
        return $this->hasMany(User::class);
    }


    public function getEmployeeCountAttrinbue(){
        return $this->employees->count();
    }


    public function getCreatedAtAttribute(){
        return \Carbon\Carbon::parse($this->attributes['created_at'])->format('d-m-Y');
    }
}
