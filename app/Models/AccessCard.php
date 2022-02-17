<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessCard extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const TYPE_PRIMARY = 'primary';
    public const TYPE_TEMPORARY = 'temporary';

    protected $guarded = [];

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

    public function createReloadHistory(string $type_quota){
        if($type_quota == 'lunch'){
          $this->increment('lunch_reload_count');
        }
        elseif($type_quota == 'breakfast'){
          $this->increment('breakfast_reload_count');
        }

        $this->save();
    }
}
