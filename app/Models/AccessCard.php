<?php

namespace App\Models;

use App\Support\ActivityHelper;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class AccessCard extends Model
{
    use HasFactory;
    use SoftDeletes, LogsActivity;

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
}
