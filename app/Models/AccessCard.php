<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class AccessCard extends Model
{
    use HasFactory;

    // use LogsActivity;

    protected $fillable = [
        'identifier',
        'quota_breakfast',
        'quota_lunch',
        'user_id',
        'payment_method_id',
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['quota_breakfast'])
            ->logOnlyDirty()
            ->useLogName('access_card')
            // ->dontLogIfAttributesChangedOnly(['user_id', 'payment_method_id', 'identifier'])
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->description = "activity.{$eventName}";
        $activity->causer_id = $this->user_id;
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
}
