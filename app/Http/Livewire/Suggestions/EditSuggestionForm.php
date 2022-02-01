<?php

namespace App\Http\Livewire\Suggestions;

use Livewire\Component;
use App\Models\SuggestionBox;

class EditSuggestionForm extends Component
{

  public $suggestion;
  public $suggestionContent;
  

  public function mount(int $suggestion)
  {
     $this->suggestion = SuggestionBox::find($suggestion);
     $this->suggestionContent = $this->suggestion->suggestion;
     
  }

  public function saveSuggestion(){
    $this->validate([
      'suggestionContent' => ['required', 'string', 'min:20']
    ]);

    $this->suggestion->update(['suggestion' => $this->suggestionContent]);

    session()->flash('success', 'Votre suggestion a été modifiée avec succès');
    return redirect()->route('suggestions-box.index');
}


  public function render()
  {
    return view('livewire.suggestions.edit-suggestion-form');
  }
}
