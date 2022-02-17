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

  /*
  * Une carte d'accès est liée à un utilisateur
  */

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /*
  * Une carte est liée a une methode de paiement
  */
  public function paymentMethod()
  {
    return $this->belongsTo(PaymentMethod::class);
  }

  /*
  * Une carte est liée a un ou plusieurs commandes
  */

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
}
