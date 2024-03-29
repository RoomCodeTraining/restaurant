<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuggestionBox extends Model
{
    use HasFactory;

    protected $fillable = ['suggestion', 'user_id', 'suggestion_type_id'];

    public function user(){
      return $this->belongsTo(User::class);
    }

    public function suggestionType(){
      return $this->belongsTo(SuggestionType::class);
    }

}
