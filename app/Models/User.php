<?php

namespace App\Models;

use App\Models\Traits\HasAccessCard;
use App\Notifications\PasswordResetNotification;
use App\Notifications\WelcomeNotification;
use App\Support\ActivityHelper;
use App\Support\HasProfilePhoto;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use InvalidArgumentException;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\WelcomeNotification\ReceivesWelcomeNotification;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasPermissions,
        ReceivesWelcomeNotification,
        HasProfilePhoto,
        LogsActivity;

    use SoftDeletes;
    use HasAccessCard;

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
        'is_entitled_breakfast' => 'boolean',
    ];

    protected $appends = [
        'profile_photo_url',
        'current_card'
    ];

    public function getRouteKey()
    {
        return $this->identifier;
    }
    /**
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'identifier';
    }

    /**
     *
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return ActivityHelper::getAction($eventName) . " d'utilisateur";
    }

    /**
     * Get the options for logging model events.
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['identifier']);
        // Chain fluent methods for configuration options
    }


    /**
     *
     * @return bool
     * @throws BindingResolutionException
     * @throws InvalidArgumentException
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(\App\Enums\Role::ADMIN) ? true : false;
    }


    /**
     *
     * @param mixed $value
     * @return string
     */
    public function setIdentifierAttribute($value)
    {
        return $this->attributes['identifier'] = strtoupper($value);
    }

    /**
     *
     * @param mixed $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     *
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     *
     * @return BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    /**
     * Get the password histories for the model.
     * @return MorphMany
     */
    public function passwordHistories()
    {
        return $this->morphMany(PasswordHistory::class, 'model');
    }

    public function sendWelcomeNotification(\Carbon\Carbon $validUntil)
    {
        $this->notify(new WelcomeNotification($validUntil));
    }
    /**
     *
     * @param string $token
     * @return void
     * @throws BindingResolutionException
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }

    /**
     *
     * @return HasMany
     */
    public function suggestions()
    {
        return $this->hasMany(SuggestionBox::class);
    }


    /**
     *
     * @return bool
     */
    public function isActive() : bool
    {
        return $this->is_active ? true : false;
    }

    public function role() : BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}