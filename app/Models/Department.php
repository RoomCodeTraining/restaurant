<?php

namespace App\Models;

use App\Support\ActivityHelper;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
  use HasFactory, LogsActivity;


  protected $fillable = [
    'name',
  ];

  public function getDescriptionForEvent(string $eventName): string
  {
    return ActivityHelper::getAction($eventName)." de departement";
  }

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logOnly(['name']);
    // Chain fluent methods for configuration options
  }


  public function users()
  {
    return $this->hasMany(User::class);
  }

  public function getNameAttribute($value)
  {
    return ucfirst($value);
  }
}
