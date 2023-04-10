<?php

namespace App\Http\Livewire\Organizations;

use Livewire\Component;
use App\Models\Organization;
use Illuminate\Validation\Rule;
use App\Models\OrganizationType;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Actions\Organization\UpdateOrganizationAction;

class EditOrganizationForm extends Component implements HasForms
{
  use InteractsWithForms;
  public $organization;

  public $state = [
    'name' => null,
    'description' => null,
  ];

  public function mount(Organization $organization)
  {
    $this->organization = $organization;

    $this->form->fill([
      'state.name' => $organization->name,
      'state.description' => $organization->description,
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
        ->label('Description')
    ];
  }

  public function saveOrganization(UpdateOrganizationAction $action)
  {
    $this->validate([
      'state.name' => ['required', 'string',  Rule::unique('users', 'identifier')],
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
