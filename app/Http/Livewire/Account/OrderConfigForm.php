<?php

namespace App\Http\Livewire\Account;

use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class OrderConfigForm extends Component implements HasForms
{
    use \Filament\Forms\Concerns\InteractsWithForms;

    public bool $is_for_evening = false;

    public function mount()
    {
        $this->is_for_evening = auth()->user()->order_for_evening;
    }

    public function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\Grid::make()
                ->schema([
                    \Filament\Forms\Components\Toggle::make('is_for_evening')
                        ->label('Configurer automatiquement mes commandes pour le soir. Si vous ne cochez pas cette case, vos commandes seront configurées pour le midi.')
                ]),
        ];
    }

    public function save()
    {
        $this->validate();

        auth()->user()->update([
            'order_for_evening' => $this->is_for_evening,
        ]);

        session()->flash('success', 'Vos préférences ont été mises à jour !');
        return redirect()->route('profile');
    }

    public function render()
    {
        return view('livewire.account.order-config-form');
    }
}
