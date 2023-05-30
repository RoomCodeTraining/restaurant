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
use App\Models\Organization;

class CreateOrganizationForm extends Component implements HasForms
{
  use InteractsWithForms;


  public $state = [
    'name' => null,
    'description' => true,
    'family' => null,
    'is_entitled_two_dishes' => false,
    ];

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

  public function saveOrganization(CreateOrganizationAction $action)
  {
    $this->validate([
      'state.name' => ['required', 'string',  Rule::unique('organizations', 'name')],
      'state.description' => ['nullable', 'string'],
      'state.family' => ['required', 'string'],
      'state.is_entitled_two_dishes' => ['required', 'boolean'],
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
