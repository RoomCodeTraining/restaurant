<?php

namespace App\Models;

use App\Models\Department;
use App\Models\Organization;
use App\Models\EmployeeStatus;
use App\Support\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\WelcomeNotification\ReceivesWelcomeNotification;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasRoles,
        HasPermissions,
        ReceivesWelcomeNotification,
        HasProfilePhoto;

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

    protected $appends = [
        'profile_photo_url',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function setIdentifierAttribute($value)
    {
        return $this->attributes['identifier']  = strtoupper($value);
    }

    public function isFromLunchroom(): bool
    {
        return $this->hasRole([Role::ADMIN_LUNCHROOM, Role::OPERATOR_LUNCHROOM]);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

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

    public function accessCards()
    {
        return $this->hasMany(AccessCard::class);
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'current_role_id');
    }

    public function accessCard()
    {
        return $this->belongsTo(AccessCard::class, 'current_access_card_id');
    }
}
