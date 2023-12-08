<?php

namespace App\Http\Livewire\Departments;

use App\Actions\Department\UpdateDepartmentAction;
use App\Models\Department;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class EditDepartmentForm extends Component implements HasForms
{
    use InteractsWithForms;
    public Department $department;
    public ?array $datInteractsWithFormsa = [];


    public $state = [
        'name' => null,
    ];

    public function mount(): void
    {


        //dd($department);

        $this->form->fill([
            'name' => $this->department->name,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->rules('required', 'max:255'),
                    ])
                // ...
            ])->statePath('state');
    }


    // protected function getFormSchema(): array
    // {
    //     return [
    //         TextInput::make('state.name')
    //             ->label('Nom')
    //             ->required()
    //             ->rules('required', 'max:255'),
    //     ];
    // }

    public function saveDepartment(UpdateDepartmentAction $action)
    {
        $this->validate([
            'state.name' => ['required', 'string'],
        ]);

        $action->execute($this->department, $this->state);

        flasher("success", "Le département a bien été modifié.");

        return redirect()->route('departments.index');
    }

    public function render()
    {
        return view('livewire.departments.edit-department-form');
    }
}