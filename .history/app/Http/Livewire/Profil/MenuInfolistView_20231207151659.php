<?php

namespace App\Http\Livewire\Profil;

use Livewire\Component;

class MenuInfolistView extends Component
{
    use InteractsWithForms, InteractsWithInfolists;

    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function userViewProfil(Infolist $infolist): Infolist
    {


        return $infolist->record($this->user)->schema([]);
    }
    public function render()
    {
        return view('livewire.profil.menu-infolist-view');
    }
}
