<?php

namespace App\Http\Livewire\Roles;

use App\Models\Role;
use Livewire\Component;
use Filament\Infolists\Infolist;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class ViewRole extends Component implements HasInfolists, HasForms
{

    use InteractsWithForms, InteractsWithInfolists;

    public Role $role;

    public function mount(Role $role)
    {
        $this->role = $role;
        //dd($this->role);
    }

    public function userViewRole(Infolist $infolist): Infolist
    {

        return $infolist->record($this->role)->schema([
            Section::make('Les informations de votre compte')
                ->description('Ces informations concernent vos données personnelles ')
                ->aside()
                ->schema([
                    Tabs::make('Information de votre ')->tabs([
                        Tabs\Tab::make('informations de mon compte')
                            ->icon('heroicon-m-user')
                            ->schema([
                                TextEntry::make('name'),
                                TextEntry::make('users_count')
                                    ->formatStateUsing(fn (Role $record) => $record->users_count)
                            ])->columns(2),
                    ]),
                ]),
        ]);
    }
    public function render()
    {
        return view('livewire.roles.view-role');
    }
}
