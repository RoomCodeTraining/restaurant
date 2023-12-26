<?php

namespace App\Http\Livewire\Orders;

use App\Models\DishRating;
use App\Models\Order;
use App\Support\ActivityHelper;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use IbrahimBougaoua\FilamentRatingStar\Actions\RatingStar;
use Livewire\Component;

class NoteMenuForm extends Component implements HasForms
{
    use InteractsWithForms;

    public Order $order;

    public ?array $data = [];


    public function mount(Order $order)
    {
        $this->order = $order;
        $this->form->fill();
    }

    public function form(Form $form) : Form
    {
        return $form
            ->schema([
               Section::make('Notez le menu du jour')
                ->description('Veuillez noter le plat du menu d\'hier')
                ->schema([
                      RatingStar::make('rating')->label('Note')->required(),
                ])
            ])->statePath('data');

    }

    public function noteDish()
    {
        $this->validate();
        $rating = DishRating::create([
             'order_id' => $this->order->id,
             'rating' => $this->data['rating'],
         ]);

        Notification::make()->title('Note du menu')->body('Votre note a été enregistrée avec succès')->success()->send();
        $log = $this->order->user->full_name . ' a noté le menu du ' . $this->order->menu->served_at->format('d/m/Y') . ' avec la note de ' . $this->data['rating'] . '/5';
        ActivityHelper::createActivity($rating, $log, 'Note du menu');

        return redirect()->route('orders.index');
    }

    public function render()
    {
        return view('livewire.orders.note-menu-form');
    }
}
