<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

class ActivityHelper
{
  public const UPDATE = 'Modification';
  public const CREATE = 'Création';
  public const DELETE = 'Suppression';

  public static function getAction(string $action)
  {
    switch ($action) {
      case 'created':
        return self::CREATE;
        break;
      case 'updated':
        return self::UPDATE;
        break;
      case 'deleted':
        return self::DELETE;
        break;
      default:
        return $action;
        break;
    }
  }


  public static function createdBy($causer_id = null): string
  {
    $user = \App\Models\User::find($causer_id) ?? 'NA';
     return $user->full_name ?? 'NA';
  }



  /*
  *Create a new ActivityLog
  */
  public static function createActivity(Model $someModel, string $event, string $description)
  { 
    activity()
      ->causedBy(Auth()->user() ? Auth()->user()->id : $someModel->id)
      ->performedOn($someModel)
      ->event($event)
      ->log($description);

      return 1;
  }
}
