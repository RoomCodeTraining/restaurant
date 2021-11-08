<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeStatus extends Model
{
    use HasFactory;


    public const EXECUTIVE = 'Cadre';
    public const EMPLOYEE = 'Employé';
    public const INTERN = 'Stagaire';
    public const VISITOR = 'Visiteur';
    public const AGENT = 'Agent';
    public const NONE = 'Aucun';


    public function employees()
    {
        return $this->hasMany(Employee::class);
    }


    public static function getAllStatuses() : Array
    {
        return [
            self::EXECUTIVE,
            self::EMPLOYEE,
            self::INTERN,
            self::VISITOR,
            self::AGENT
        ];
    }
}
