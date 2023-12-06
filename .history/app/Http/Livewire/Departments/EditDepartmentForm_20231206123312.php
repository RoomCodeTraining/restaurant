<?php

namespace App\Http\Livewire\Departments;

use Livewire\Component;
use Filament\Forms\Form;
use App\Models\Department;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Actions\Department\UpdateDepartmentAction;

class EditDepartmentForm extends Component implements HasForms
{
    use InteractsWithForms;
    public $department;
    public ?array $datInteractsWithFormsa = [];


    public $state = [
        'name' => null,
    ];

    public function mount(Department $department)
    {
        $this->department = $department;

        //dd($department);

        $this->form->fill([
            'state.name' => $department->name,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Modification des informations liées au département')
                    ->description('Veuillez saisir des noms de départements corrects pour une meilleure affiliation au personnel')
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
