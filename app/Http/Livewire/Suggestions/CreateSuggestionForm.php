<?php

namespace App\Http\Livewire\Suggestions;

use Livewire\Component;

class CreateSuggestionForm extends Component
{

    public $suggestion;


    public function saveSuggestion(){
        $this->validate([
          'suggestion' => ['required', 'string', 'min:20']
        ]);

        auth()->user()->suggestions()->create(['suggestion' => $this->suggestion]);

        $this->suggestion = null;
        session()->flash('success', 'Votre suggestion a été prise en compte');
        return redirect()->route('suggestions-box.index');
    }

    public function render()
    {
        return view('livewire.suggestions.create-suggestion-form');
    }
}
