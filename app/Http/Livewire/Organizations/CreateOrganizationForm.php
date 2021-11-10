<?php

namespace App\Http\Livewire\Organizations;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Actions\Organization\CreateOrganizationAction;

class CreateOrganizationForm extends Component
{


    public $state = ['name' => null];
    public function saveOrganization(CreateOrganizationAction $action){
        $this->validate([
            'state.name' => ['required', 'string',  Rule::unique('users', 'identifier')],
        ]);

        $action->execute($this->state);
        session()->flash('success', 'La société a été crée avec succès !');
        return redirect()->route('organizations.index');
    }

    public function render()
    {
        return view('livewire.organizations.create-organization-form');
    }
}
