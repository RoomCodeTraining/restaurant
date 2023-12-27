<?php

namespace App\Http\Livewire\Suggestions;

use Livewire\Component;
use Filament\Forms\Form;
use App\Models\SuggestionBox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;

class EditSuggestionForm extends Component implements HasForms
{
    use \Filament\Forms\Concerns\InteractsWithForms;
    public $suggestion;
    public $suggestionContent;
    public $suggestion_type_id;


    public function mount(int $suggestion)
    {
        $this->suggestion = SuggestionBox::find($suggestion);
        $this->suggestionContent = $this->suggestion->suggestion;
        $this->suggestion_type_id = $this->suggestion->suggestion_type_id;
        $this->form->fill(
            [
                'suggestion_type_id' => $this->suggestion_type_id,
                'suggestionContent' => $this->suggestionContent,
            ]
        );
    }

    //   protected function getFormSchema(): array
    //   {
    //     return [
    //       \Filament\Forms\Components\Select::make('suggestion_type_id')
    //         ->label('Objet')
    //         ->required()
    //         ->placeholder('Choisissez un type de suggestion')
    //         ->options(\App\Models\SuggestionType::all()->pluck('name', 'id')),
    //       \Filament\Forms\Components\Textarea::make('suggestionContent')
    //         ->label('Suggestion')
    //         ->required()
    //         ->placeholder('Votre suggestion'),
    //     ];
    //   }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Soumettre une suggestion')
                    ->description('Vos suggestions nous permettrons d\'apporter des corrections pour une meilleure utilisation')
                    ->icon('heroicon-o-light-bulb')
                    ->columns(1)
                    ->schema([
                        Select::make('suggestion_type_id')
                            ->label('Objet')
                            ->required()
                            ->placeholder('Choisissez un type de suggestion')
                            ->options(\App\Models\SuggestionType::all()->pluck('name', 'id')),
                        Textarea::make('suggestionContent')
                            ->label('Suggestion')
                            ->required()
                            ->placeholder('Votre suggestion'),
                    ])
                // ...
            ]);
    }

    public function saveSuggestion()
    {
        $this->validate([
            'suggestionContent' => ['required', 'string', 'min:20']
        ]);

        $this->suggestion->update([
            'suggestion' => $this->suggestionContent,
            'suggestion_type_id' => $this->suggestion_type_id,
        ]);

        session()->flash('success', 'Votre suggestion a été modifiée avec succès');
        return redirect()->route('suggestions-box.index');
    }


    public function render()
    {
        return view('livewire.suggestions.edit-suggestion-form');
    }
}
