<?php

namespace App\Http\Livewire\UserTypes;

use App\Actions\UserType\UpdateUserTypeAction;
use App\Models\UserType;
use Livewire\Component;

class EditUserTypeForm extends Component
{
    public $userType;

    public $state = [
        'name' => null,
        'auto_identifier' => null,
    ];

    public function mount(UserType $userType)
    {
        $this->userType = $userType;
        $this->state = $userType->toArray();
    }

    public function saveUserType(UpdateUserTypeAction $action)
    {
        $this->validate([
            'state.name' => ['required', 'string'],
        ]);

        $action->execute($this->userType, $this->state);

        session()->flash('success', 'Le type d\'utilisateur a été modifié avec succès !');

        return redirect()->route('userTypes.index');
    }

    public function render()
    {
        return view('livewire.user-types.edit-user-type-form');
    }
}
