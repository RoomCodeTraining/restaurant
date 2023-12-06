<?php

namespace App\Http\Livewire\Departments;

use App\Actions\Department\CreateDepartmentAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateDepartmentForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $state = ['name' => null];

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
        ];
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
