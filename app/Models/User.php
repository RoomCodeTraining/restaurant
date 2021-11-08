<?php

namespace App\Models;

use App\Models\Department;
use App\Models\Organization;
use App\Models\EmployeeStatus;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasRoles,
        HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'is_external' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }


    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }


    public function employeeStatus()
    {
        return $this->belongsTo(EmployeeStatus::class);
    }



    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAciveAttribute()
    {
        return $this->is_active ? 'Actif' : 'Non actif';
    }

    public function getExternalAttribute()
    {
        return $this->is_external ? 'Collobarateur Ciprel' : 'Collaborateur externe';
    }

    public function getDepartmentNameAttribute()
    {
        return $this->department->name;
    }

    public function getOrganizationNameAttribute()
    {
        return $this->organization->name;
    }

    public function getEmployeeStatusNameAttribute()
    {
        return $this->employeeStatus->name;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


    public function scopeExternal($query)
    {
        return $query->where('is_external', true);
    }


    public function getCreatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['created_at'])->format('d/m/Y');
    }
}
