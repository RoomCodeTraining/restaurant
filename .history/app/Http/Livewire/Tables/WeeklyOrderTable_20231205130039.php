<?php

namespace App\Http\Livewire\Tables;

use App\Models\Order;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class WeeklyOrderTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {

        $weekly = Order::join('dishes', 'orders.dish_id', 'dishes.id')
            ->join('menus', 'orders.menu_id', 'menus.id')
            ->whereBetween('menus.served_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereNotState('state', [Cancelled::class, Suspended::class])
            ->groupBy('dish_id', 'menu_served_at')
            ->orderBy('menu_served_at', 'DESC')
            ->selectRaw('dish_id, menus.served_at as menu_served_at, COUNT(*) as total_orders');

        return $table
            ->query($weekly)
            ->paginated([10, 25, 50, 100, 'all'])
            ->columns([

                TextColumn::make('Menu du', 'menu_served_at')->format(fn ($row) => Carbon::parse($row)->format('d/m/Y')),
                TextColumn::make('Plat', 'dish_id')->format(fn ($row) => dishName($row)),
                TextColumn::make('Nbr. de commandes', 'total_orders'),
                TextColumn::make('Actions')->format(fn ($val, $col, $row) => view('orders.summary.table-actions', ['row' => $row]))


            ])
            ->actions([
                ViewAction::make()
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->tooltip('Consulter')
                    ->url(function (User $user) {
                        return route('users.show', $user);
                    }),
                EditAction::make()
                    ->label('')
                    ->icon('heroicon-o-pencil-square')
                    ->color('info')
                    ->tooltip('Modifier')
                    ->url(function (User $user) {
                        return route('users.edit', $user);
                    }),
                DeleteAction::make()
                    ->label('')
                    ->icon('heroicon-o-lock-closed')
                    ->action(function (User $user) {
                        $user->is_active = false;
                        $user->save();
                        Notification::make()
                            ->title('Utilisateur désactivé')
                            ->body('L\'utilisateur a été désactivé avec succès.')
                            ->success()
                            ->send();

                        return redirect()->route('users.index');
                    })
                    ->tooltip('Desactiver')
                    ->hidden(function (User $user) {
                        return   !$user->isActive() || $user->id == auth()->user()->id;
                    })
                    ->requiresConfirmation(),
                Action::make('reactivate')
                    ->label('')
                    ->icon('heroicon-o-lock-open')
                    ->tooltip('Activer')
                    ->hidden(function (User $user) {
                        return $user->isActive();
                    })
                    ->requiresConfirmation()
                    ->action(function (User $user) {
                        $user->is_active = true;
                        $user->save();
                        Notification::make()
                            ->title('Utilisateur activé')
                            ->body('L\'utilisateur a été activé avec succès.')
                            ->success()
                            ->send();

                        return redirect()->route('users.index');
                    }),
                DeleteAction::make()
                    ->label('')
                    ->icon('heroicon-o-trash')
                    ->action(function (User $user) {
                        $user->delete();
                        Notification::make()
                            ->title('Utilisateur supprimé')
                            ->body('L\'utilisateur a été supprimé avec succès.')
                            ->success()
                            ->send();

                        return redirect()->route('users.index');
                    })
                    ->tooltip('Supprimer')
                    ->hidden(function (User $user) {
                        return $user->id == auth()->user()->id;
                    })
                    ->requiresConfirmation(),
            ]);
    }


    public function modalsView(): string
    {
        return 'orders.summary.modals';
    }

    public function render()
    {
        return view('livewire.tables.weekly-order-table');
    }
}
