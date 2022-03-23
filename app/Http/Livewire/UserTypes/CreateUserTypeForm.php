<?php

namespace App\Http\Livewire\UserTypes;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Actions\UserType\CreateUserTypeAction;

class CreateUserTypeForm extends Component
{
    public $state = [
        'name' => null,
        'auto_identifier' => false
    ];

    public function saveUserType(CreateUserTypeAction $createUserTypeAction)
    {
        $this->validate([
            'state.name' => ['required', 'string', Rule::unique('user_types', 'name')],
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
