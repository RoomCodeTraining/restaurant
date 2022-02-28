<?php

namespace App\Models;

use App\Models\SuggestionBox;
use App\States\Order\Confirmed;
use App\Support\ActivityHelper;
use App\Support\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use App\Notifications\WelcomeNotification;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
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
        HasProfilePhoto,
        LogsActivity;

    use SoftDeletes;

    protected static $logName = 'Utilisateur';
    protected static $recordEvents = [];

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

    public function getDescriptionForEvent(string $eventName): string
    {
        return ActivityHelper::getAction($eventName)." d'utilisateur";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['identifier']);
        // Chain fluent methods for configuration options
    }

    public function isAdmin() : bool {
        return $this->hasRole(\App\Models\Role::ADMIN) ? true : false;
    }

    public function getFullNameAttribute()
    {
        return "{$this->last_name} {$this->first_name}";
    }

    public function setIdentifierAttribute($value)
    {
        return $this->attributes['identifier'] = strtoupper($value);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isFromLunchroom(): bool
    {
        return $this->hasRole([Role::ADMIN_LUNCHROOM, Role::OPERATOR_LUNCHROOM]);
    }

    public function isOperatorLunch(): bool
    {
        return $this->hasRole(Role::OPERATOR_LUNCHROOM);
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

    public function currentAccessCard()
    {
        return $this->belongsTo(AccessCard::class, 'current_access_card_id');
    }

    public function passwordHistories()
    {
        return $this->morphMany(PasswordHistory::class, 'model');
    }

    public function sendWelcomeNotification(\Carbon\Carbon $validUntil)
    {
        $this->notify(new WelcomeNotification($validUntil));
    }

    public function useCard(AccessCard $accessCard)
    {
        $this->current_access_card_id = $accessCard->id;
        $this->save();
    }

    public function suggestions(){
      return $this->hasMany(SuggestionBox::class);
    }

    /*
    * Verifier si la carte du collaborateur est rechargeable
    */
    public function typeAndCategoryCanUpdated(){
      if($this->accessCard && $this->accessCard->quota_breakfast > 0 && $this->accessCard->quota_lunch > 0){
          return true;
      }
    }

    /*
    * Compter le nombre de commande en cours de l'utilisateur en cours
    */

    public function countOrderConfirmed() : int {
       return $this->orders()->weekly()->whereState('state', Confirmed::class)->count();
    }

    /*
    * Verifier si le quota de l'utilisateur est egal au nombre de commande en cours
    */
    public function canCreateOtherOrder() : bool {
        if($this->countOrderConfirmed() < $this->accessCard->quota_lunch){
            return true;
        }
        return false;
    }



}
