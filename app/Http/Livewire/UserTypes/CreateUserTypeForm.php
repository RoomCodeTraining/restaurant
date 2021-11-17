<?php

namespace App\Http\Livewire\UserTypes;

use App\Actions\UserType\CreateUserTypeAction;
use Livewire\Component;

class CreateUserTypeForm extends Component
{
    public $state = [
        'name' => null,
        'auto_identifier' => false
    ];

    public function saveUserType(CreateUserTypeAction $createUserTypeAction)
    {
        $this->validate([
            'state.name' => ['required'],
            'state.auto_identifier' => ['nullable']
        ]);

        $createUserTypeAction->execute($this->state);

        return redirect()->route('userTypes.index');
    }

    public function render()
    {
        return view('livewire.user-types.create-user-type-form');
    }
}
