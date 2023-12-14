<?php

namespace App\Http\Livewire\Roles;

use App\Models\Role;
use Livewire\Component;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class ViewRole extends Component
{

    use InteractsWithForms, InteractsWithInfolists;

    public Role $role;

    public function mount(Role $role)
    {
        $this->role = $role;

        // dd(Auth::user()->isFromLunchroom());
    }

    public function ViewRole(Infolist $infolist): Infolist
    {

        return $infolist->record($this->user)->schema([
            Section::make('Les informations de votre compte')
                ->description('Ces informations concernent vos données personnelles ')
                ->schema([
                    Tabs::make('Information de votre ')->tabs([
                        Tabs\Tab::make('informations de mon compte')
                            ->icon('heroicon-m-user')
                            ->schema([])->columns(2),
                    ]),
                ]),
        ]);
    }
    public function render()
    {
        return view('livewire.roles.view-role');
    }
}
