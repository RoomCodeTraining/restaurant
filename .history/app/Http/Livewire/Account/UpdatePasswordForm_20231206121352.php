<?php

namespace App\Http\Livewire\Account;

use App\Models\User;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UpdatePasswordForm extends Component implements HasForms
{
    use InteractsWithForms;

    // /public User $user;
    public ?array $data = [];


    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Modifier votre mot de passe')
                    ->aside()
                    ->description('Mettez à jour les informations de votre compte. Pour des raisons de sécurité, votre mot de passe doit contenir au moins 8 caractères, dont au moins une lettre majuscule, une lettre minuscule et un chiffre.')
                    ->schema([
                        Grid::make(1)->schema([
                            TextInput::make('current_password')
                                ->type('password')
                                ->label('Mot de passe actuel'),
                            TextInput::make('password')
                                ->type('password')
                                ->label('Nouveau Mot de passe'),
                            TextInput::make('password_confirmation')
                                ->type('password')
                                ->label('Confirmer votre mot de passe'),
                        ])
                    ])

                // ...
            ])
            ->statePath('data');
    }

    public function updatePassword()
    {

        $p =  $this->validate([
            'data.current_password' => ['required',  function ($attribute, $value, $fail) {

                dd(Auth::user()->password);

                if (!Hash::check($value, $this->user->password)) {
                    $fail('Le mot de passe actuel est incorrect.');
                }
            }],
            'data.password' => ['required', 'confirmed', 'min:8', function ($attribute, $value, $fail) {
                if ($value == $this->data['current_password']) {
                    $fail('Le nouveau mot de passe doit être différent du mot de passe actuel.');
                }
            }],
        ]);

        dd($p);

        $this->user->update([
            'password' => Hash::make($this->data['password']),
        ]);

        Notification::make()->title('Mot de passe mis à jour')->success()->body('Votre mot de passe a été mis à jour.')->send();

        $this->redirectRoute('profile.edit');
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.account.update-password-form');
    }
}
