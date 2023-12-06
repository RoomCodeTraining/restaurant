<?php

namespace App\Http\Livewire\Profil;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Livewire\Component;

class UserProfilView extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.profil.user-profil-view');
    }
}
