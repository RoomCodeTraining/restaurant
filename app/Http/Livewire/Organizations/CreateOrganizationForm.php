<?php

namespace App\Http\Livewire\Organizations;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\OrganizationType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Actions\Organization\CreateOrganizationAction;

class CreateOrganizationForm extends Component implements HasForms
{
  use InteractsWithForms;


  public $state = ['name' => null, 'description' => true];

  public function mount()
  {
    $this->form->fill();
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

  public function saveOrganization(CreateOrganizationAction $action)
  {
    $this->validate([
      'state.name' => ['required', 'string',  Rule::unique('organizations', 'name')],
      'state.description' => ['nullable', 'string'],
    ]);

    $action->execute($this->state);

    flasher("success", "La société a bien été créée.");
    return redirect()->route('organizations.index');
  }

  public function render()
  {
    return view('livewire.organizations.create-organization-form');
  }
}
