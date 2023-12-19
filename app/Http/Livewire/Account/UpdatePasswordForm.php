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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class UpdatePasswordForm extends Component implements HasForms
{
    use InteractsWithForms;

    // /public User $user;
    public ?array $datInteractsWithFormsa = [];
    public ?array $data = [];


    public function mount(): void
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
                                ->rules([
                                    'required',
                                    Password::min(8)
                                        ->letters()
                                        ->numbers()
                                        ->symbols(),
                                ])->password()->label('Nouveau Mot de passe'),

                            TextInput::make('password_confirmation')
                                ->type('password')
                                ->label('Confirmer votre mot de passe')
                                ->rules(['required'])
                                ->password(),
                        ])
                    ])

                // ...
            ])
            ->statePath('data');
    }


    public function updatePassword()
    {
        $p = $this->validate([
            'data.current_password' => ['required',  function ($attribute, $value, $fail) {
                if (! Hash::check($value, Auth::user()->password)) {
                    $fail('Le mot de passe actuel est incorrect.');
                }
            }],

            'data.password' => ['required', 'confirmed', 'min:8', function ($attribute, $value, $fail) {
                if ($value == $this->data['current_password']) {
                    $fail('Le nouveau mot de passe doit être différent du mot de passe actuel.');
                }
            }],
        ]);

        auth()->user()->update([
            'password' => Hash::make($this->data['password']),
            'password_changed_at' => now(),
        ]);

        Notification::make()->title('Mot de passe mis à jour')->success()->body('Votre mot de passe a été mis à jour.')->send();

        $this->redirectRoute('profile');
    }

    public function messages(): array
    {
        return [
            'required' => 'Le champ :attribute est obligatoire.',
            'min' => 'Le champ :attribute doit contenir au moins :min caractères.',
            'letters' => 'Le champ :attribute doit contenir au moins une lettre.',
            'numbers' => 'Le champ :attribute doit contenir au moins un chiffre.',
            'symbols' => 'Le champ :attribute doit contenir au moins un caractère spécial.',
        ];
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