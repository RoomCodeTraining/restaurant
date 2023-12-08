<?php

namespace App\Models;

use App\Support\ActivityHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AccessCard extends Model
{
    use HasFactory;
    use SoftDeletes, LogsActivity;

    public const LUNCH = 'lunch';
    public const BREAKFAST = 'breakfast';

    public const TYPE_PRIMARY = 'primary';
    public const TYPE_TEMPORARY = 'temporary';

    protected $guarded = [];
    protected static $recordEvents = [];


    public function getDescriptionForEvent(string $eventName): string
    {
        return ActivityHelper::getAction($eventName)." de carte RFID";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
          ->logOnly(['name']);
        // Chain fluent methods for configuration options
    }

    public function getRouteKey()
    {
        return $this->identifier;
    }

    public function getRouteKeyName()
    {
        return 'identifier';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    /*
    * Mettre à jour le nombre de rechargement de la carte(Petit dejeuner et dejeuner)
    */
    public function createReloadHistory(string $type_quota)
    {
        $this->increment($type_quota.'_reload_count');
        $this->save();
    }


    public function reloadAccessCardHistory() : HasMany
    {
        return $this->hasMany(ReloadAccessCardHistory::class)->orderByDesc('created_at');
    }

    public function countLunchReload() : int
    {
        return $this->reloadAccessCardHistory()->where('quota_type', self::LUNCH)->count();
    }

    public function countBreakfastReload() : int
    {
        return $this->reloadAccessCardHistory()->where('quota_type', self::BREAKFAST)->count();
    }


}