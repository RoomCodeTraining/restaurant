<?php

namespace App\Policies;

use App\Models\SuggestionBox;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SuggestionBoxPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */

     public const SUGGESTION_CREATE = 'suggestion.create';
     public const SUGGESTION_DELETE = 'suggestion.delete';
     public const SUGGESTION_MANAGE = 'suggestion.manage';
     public const SUGGESTION_UPDATE = 'suggestion.update';
     public const SUGGESTION_LIST  = 'suggestion.list';


     public function manage(User $loggedInUser)
     {
         if ($loggedInUser->can(self::SUGGESTION_MANAGE)) {
           return true;
         }
     }
 

    public function viewAny(User $loggedInUser)
    {
        if ($loggedInUser->can(self::SUGGESTION_LIST)) {
          return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SuggestionBox  $suggestionBox
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $loggedInUser, SuggestionBox $suggestionBox)
    {
        if($loggedInUser->can(self::SUGGESTION_LIST)){
          return 1;
        } 
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $loggedInUser)
    {
        if($loggedInUser->can(self::SUGGESTION_CREATE)){
           return 1;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SuggestionBox  $suggestionBox
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $loggedInUser, SuggestionBox $suggestionBox)
    {
         if($loggedInUser->can(self::SUGGESTION_UPDATE)){
            return 1;
         }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SuggestionBox  $suggestionBox
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $loggedInUser, SuggestionBox $suggestionBox)
    {
        if($loggedInUser->id == $suggestionBox->user_id){
            return true;
        }
    }

}
