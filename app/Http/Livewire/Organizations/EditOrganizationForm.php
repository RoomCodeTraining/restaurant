<?php

namespace App\Http\Livewire\Organizations;

use Livewire\Component;
use App\Models\Organization;
use Illuminate\Validation\Rule;
use App\Actions\Organization\UpdateOrganizationAction;

class EditOrganizationForm extends Component
{

    public $organization;
    public $state = [
        'name' => null,
    ];

    public function mount(Organization $organization)
    {
        $this->department = $organization;
        $this->state = $organization->toArray();
    }

    public function saveOrganization(UpdateOrganizationAction $action)
    {
        $this->validate([
            'state.name' => ['required', 'string',  Rule::unique('users', 'identifier')],
        ]);

        $action->execute($this->department, $this->state);
        session()->flash('success', 'La société a été modifié avec succès !');
        return redirect()->route('organizations.index');
    }
    public function render()
    {
        return view('livewire.organizations.edit-organization-form');
    }
}
