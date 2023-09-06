<?php

namespace App\Http\Livewire\Organizations;

use App\Actions\Organization\UpdateOrganizationAction;
use App\Models\Organization;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditOrganizationForm extends Component implements HasForms
{
    use InteractsWithForms;
    public $organization;

    public $state = [
      'name' => null,
      'description' => null,
      'family' => null,
      'is_entitled_two_dishes' => null,
    ];

    public function mount(Organization $organization)
    {
        $this->organization = $organization;

        $this->form->fill([
          'state.name' => $organization->name,
          'state.description' => $organization->description,
          'state.family' => $organization->family,
          'state.is_entitled_two_dishes' => $organization->is_entitled_two_dishes,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('state.name')
            ->label('Nom')
            ->required()
            ->rules('required', 'max:255'),
            Textarea::make('state.description')
            ->label('Description'),
            Select::make('state.family')
            ->required()
            ->label('Cette société fait partie de la famille')
            ->options([Organization::GROUP_1 => 'Famille A', Organization::GROUP_2 => 'Famille B']),
            Toggle::make('state.is_entitled_two_dishes')
            ->required()
            ->label('Les collaborateurs de cette société peuvent commander deux plats par jour')
            ->onColor('success')
            ->offColor('danger'),
        ];
    }

    public function saveOrganization(UpdateOrganizationAction $action)
    {
        $this->validate([
          'state.name' => ['required', 'string',  Rule::unique('users', 'identifier')],
          'state.description' => ['nullable', 'string'],
          'state.family' => ['required', 'string', Rule::in([Organization::GROUP_1, Organization::GROUP_2])],
          'state.is_entitled_two_dishes' => ['required', 'boolean'],
        ]);

        $action->execute($this->organization, $this->state);

        flasher("success", "La société a bien été modifiée.");

        return redirect()->route('organizations.index');
    }

    public function render()
    {
        return view('livewire.organizations.edit-organization-form');
    }
}
