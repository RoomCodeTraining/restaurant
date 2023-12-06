<?php

namespace App\Http\Livewire\Suggestions;

use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateSuggestionForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $suggestion;
    public $suggestion_type_id;

    public function mount(){
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\Select::make('suggestion_type_id')
                ->label('Objet')
                ->required()
                ->placeholder('Choisissez un type de suggestion')
                ->options(\App\Models\SuggestionType::all()->pluck('name', 'id')),
            \Filament\Forms\Components\Textarea::make('suggestion')
                ->label('Suggestion')
                ->required()
                ->placeholder('Votre suggestion'),
        ];
    }


    public function saveSuggestion(){
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
