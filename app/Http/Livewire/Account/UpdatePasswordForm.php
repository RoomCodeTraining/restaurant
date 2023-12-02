<?php

namespace App\Http\Livewire\Account;

use App\Actions\User\UpdateUserPassword;
use App\Support\PasswordHistoryChecker;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UpdatePasswordForm extends Component implements HasForms
{
    use InteractsWithForms;
    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [
        'current_password' => '',
        'password' => '',
        'password_confirmation' => '',
    ];


    /**
     *
     * @return void
     */
    public function mount()
    {
        $this->form->fill($this->state);
    }


    /**
     *
     * @return array
     * @throws BindingResolutionException
     */
    public function getFormSchema(): array
    {
        return [
            Card::make()->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('state.current_password')
                            ->label('Mot de passe actuel')
                            ->required()
                            ->password()
                            ->rules([
                                function () {
                                    return function ($attribute, $value, $fail) {
                                        if (! Hash::check($value, auth()->user()->password)) {
                                            $fail('Le mot de passe actuel est incorrect.');
                                        }
                                    };
                                },
                            ]),
                        TextInput::make('state.password')
                            ->label('Nouveau mot de passe')
                            ->rules([
                                'required',
                                'confirmed',
                                'different:current_password',
                                Password::min(8)
                                    ->letters()
                                    ->numbers()
                                    ->symbols()
                                    ->uncompromised(),
                                function () {
                                    return function ($attribute, $value, $fail) {
                                        if ($this->passwordIsAlreadyUsed($value)) {
                                            $fail('Vous avez déjà utilisé ce mot de passe.');
                                        }
                                    };
                                },
                            ])
                            ->password()
                            ->required(),
                        TextInput::make('state.password_confirmation')
                            ->label('Confirmer le nouveau mot de passe')
                            ->rules(['required'])
                            ->password()
                            ->required(),
                    ])
                    ->columnSpan(2),
            ]),
        ];
    }

    /**
     *
     * @param mixed $password
     * @return bool
     * @throws BindingResolutionException
     */
    public function passwordIsAlreadyUsed($password): bool
    {
        return (new PasswordHistoryChecker())->validatePassword(auth()->user(), $password);
    }


    /**
     *
     * @return string[]
     */
    public function messages()
    {
        return [
            'state.password.different' => 'Le nouveau mot de passe doit être différent de l\'ancien.',
            'state.password.mix_case' => 'Le mot de passe doit contenir au moins une lettre majuscule et une lettre minuscule.',
            'state.password.symbols' => 'Le mot de passe doit contenir des caractères speciaux.',
            'password.letters' => 'Le mot de passe doit contenir au moins une lettre.',
            'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
            'state.password.uncompromised' => 'Le mot de passe doit être plus sécurisé.',
            'symbols' => 'Le mot de passe doit contenir au moins un caractère spécial.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'state.password_confirmation.same' => 'Les mots de passe ne correspondent pas.',
        ];
    }


    /**
     *
     * @param UpdateUserPassword $updater
     * @return RedirectResponse|void
     * @throws ValidationException
     * @throws BindingResolutionException
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function updatePassword(UpdateUserPassword $updater)
    {
        $this->validate();
        $this->resetErrorBag();
        $passwordExpired = session()->pull('password_expired', false);
        $updater->update(Auth::user(), $this->state, $passwordExpired);

        $this->state = [
            'current_password' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        $this->emit('saved');

        if ($passwordExpired) {
            return redirect()->intended(route('dashboard'));
        }
    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return Auth::user();
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