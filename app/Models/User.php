<?php

namespace App\Models;

use App\Models\Traits\HasAccessCard;
use App\Notifications\PasswordResetNotification;
use App\Notifications\WelcomeNotification;
use App\States\Order\Confirmed;
use App\Support\ActivityHelper;
use App\Support\HasProfilePhoto;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use InvalidArgumentException;
use Laravel\Sanctum\HasApiTokens;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
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
        return $this->hasRole(\App\Models\Role::ADMIN) ? true : false;
    }

    /**
     *
     * @return bool
     * @throws BindingResolutionException
     * @throws InvalidArgumentException
     */
    public function isAdminLunchRoom(): bool
    {
        return $this->hasRole(Role::ADMIN_LUNCHROOM) ? true : false;
    }

    /**
     *
     * @return bool
     * @throws BindingResolutionException
     * @throws InvalidArgumentException
     */
    public function isAdminTechnical(): bool
    {
        return $this->hasRole(\App\Models\Role::ADMIN_TECHNICAL) ? true : false;
    }

    public function currentCard(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->current_access_card_id) {
                    return $this->currentAccessCard->identifier ? $this->currentAccessCard->identifier : 'Aucune carte';
                }
            }
        );
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
     * @return bool
     * @throws BindingResolutionException
     * @throws InvalidArgumentException
     */
    public function isFromLunchroom(): bool
    {
        return $this->hasRole([Role::ADMIN_LUNCHROOM, Role::OPERATOR_LUNCHROOM]);
    }

    /**
     *
     * @return bool
     * @throws BindingResolutionException
     */
    public function isOperatorLunch(): bool
    {
        return $this->hasRole(Role::OPERATOR_LUNCHROOM);
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
    public function department()
    {
        return $this->belongsTo(Department::class);
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
     *
     * @return BelongsTo
     */
    public function employeeStatus()
    {
        return $this->belongsTo(EmployeeStatus::class);
    }

    /**
     *
     * @return BelongsTo
     */
    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    /**
     *
     * @return BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'current_role_id');
    }

    /**
     *
     * @return BelongsTo
     */
    public function currentAccessCard()
    {
        return $this->belongsTo(AccessCard::class, 'current_access_card_id');
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
     * @param AccessCard $accessCard
     * @return void
     * @throws InvalidArgumentException
     * @throws InvalidCastException
     */
    public function useCard(AccessCard $accessCard)
    {
        $this->current_access_card_id = $accessCard->id;
        $this->save();
    }

    /**
     *
     * @return HasMany
     */
    public function suggestions()
    {
        return $this->hasMany(SuggestionBox::class);
    }

    /*
      * Verifier si la carte du collaborateur est rechargeable
      */
    public function typeAndCategoryCanUpdated()
    {
        if ($this->accessCard && $this->accessCard->quota_breakfast > 0 && $this->accessCard->quota_lunch > 0) {
            return true;
        }
    }

    /*
      * Compter le nombre de commande en cours de l'utilisateur en cours
      */
    public function countOrderConfirmed(): int
    {
        return $this->orders()->futurOrder()->whereState('state', Confirmed::class)->count();
    }

    /*
      * Verifier si le quota de l'utilisateur est egal au nombre de commande en cours
      */
    public function canCreateOtherOrder(): bool
    {

        if ($this->countOrderConfirmed() < $this->accessCard->quota_lunch) {
            return true;
        }

        return false;
    }

    /**
     *
     * @return bool
     * @throws BindingResolutionException
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function hasOrderForToday(): bool
    {
        $todayOrder = Order::where('user_id', $this->id)
            ->whereHas('menu', function ($query) {
                $query->whereDate('served_at', today());
            })
            ->whereState('state', Confirmed::class)
            ->exists();

        return (int)config('cantine.order.locked_at') > now()->hour && $todayOrder ? true : false;
    }

    /**
     *
     * @return Attribute
     */
    public function canOrderTwoDishes(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->organization) {
                    return $this->organization->is_entitled_two_dishes ? true : false;
                }
            }
        );
    }

    /**
     *
     * @return bool
     */
    public function canAccessInApp(): bool
    {
        if ($this->organization?->family === Organization::GROUP_1) {
            return true;
        }

        return false;
    }

    /**
     *
     * @return bool
     */
    public function isActive() : bool
    {
        return $this->is_active ? true : false;
    }

    /**
     * Verifier si l'utilisateur peut prendre le petit dejeuner
     * @return bool
     */
    public function canTakeBreakfast(): bool
    {
        if ($this->is_entitled_breakfast) {
            return false;
        }

        return true;
    }
}