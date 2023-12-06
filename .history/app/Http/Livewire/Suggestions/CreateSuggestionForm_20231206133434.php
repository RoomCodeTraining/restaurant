<?php

namespace App\Http\Livewire\Suggestions;

use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateSuggestionForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $suggestion;
    public $suggestion_type_id;

    public function mount()
    {
        $this->form->fill();
    }

    // protected function getFormSchema(): array
    // {
    //     return [
    //         \Filament\Forms\Components\Select::make('suggestion_type_id')
    //             ->label('Objet')
    //             ->required()
    //             ->placeholder('Choisissez un type de suggestion')
    //             ->options(\App\Models\SuggestionType::all()->pluck('name', 'id')),
    //         \Filament\Forms\Components\Textarea::make('suggestion')
    //             ->label('Suggestion')
    //             ->required()
    //             ->placeholder('Votre suggestion'),
    //     ];
    // }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ajout d\'une suggestion ')
                    ->description('Vos suggestions nous permettrons d\'apporter des corrections pour une meilleure utilisation')
                    ->aside()
                    ->schema([
                        Select::make('suggestion_type_id')
                            ->label('Objet')
                            ->required()
                            ->placeholder('Choisissez un type de suggestion')
                            ->options(\App\Models\SuggestionType::all()->pluck('name', 'id')),
                        Textarea::make('suggestion')
                            ->label('Suggestion')
                            ->required()
                            ->placeholder('Votre suggestion'),

                    ])
                // ...
            ])->statePath('state');
    }


    public function saveSuggestion()
    {
        $this->validate();

        auth()->user()->suggestions()->create([
            'suggestion' => $this->suggestion,
            'suggestion_type_id' => $this->suggestion_type_id,
        ]);

        $this->suggestion = null;
        Notification::make()->title('Nouvelle suggestion')->success()->body('Votre suggestion a été prise en compte')->send();
        return redirect()->route('suggestions-box.index');
    }

    public function render()
    {
        return view('livewire.suggestions.create-suggestion-form');
    }
}
