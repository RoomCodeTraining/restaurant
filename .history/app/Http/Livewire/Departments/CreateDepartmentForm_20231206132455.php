<?php

namespace App\Http\Livewire\Departments;

use Livewire\Component;
use Filament\Forms\Form;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Actions\Department\CreateDepartmentAction;

class CreateDepartmentForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $state = ['name' => null];

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ajout d\'un nouveau département ')
                    ->description('Veuillez saisir des modes de paiement corrects pour une meilleure transaction financière')
                    ->aside()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->rules('required', 'max:255'),


                    ])
                // ...
            ])->statePath('state');
    }

    public function saveDepartment(CreateDepartmentAction $action)
    {
        $this->validate([
            'state.name' => ['required', 'string',  Rule::unique('departments', 'name')],
        ]);

        $action->execute($this->state);
        flasher("success", "Le département a bien été créé.");

        return redirect()->route('departments.index');
    }
    public function render()
    {
        return view('livewire.departments.create-department-form');
    }
}
