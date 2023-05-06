<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Contracts\HasForms;
use App\Support\PasswordHistoryChecker;
use App\Actions\User\UpdateUserPassword;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\Rules\Password;
use Filament\Forms\Concerns\InteractsWithForms;

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

    public function mount()
    {
        $this->form->fill($this->state);
    }

    public function getFormSchema() : array
    {
        return [
            Card::make()->schema([
            Grid::make(2)->schema([
                TextInput::make('state.current_password')
                ->label('Mot de passe actuel')
                ->required()
                ->password()
                ->rules([
                    function(){
                        return function($attribute, $value, $fail){
                            if(! Hash::check($value, auth()->user()->password)){
                                $fail('Le mot de passe actuel est incorrect.');
                            }
                        };
                    }
                ]),
            TextInput::make('state.password')
                ->label('Nouveau mot de passe')
                ->rules([
                    'required',
                    'confirmed',
                    'different:current_password',
                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised(),
                    function(){
                        return function($attribute, $value, $fail){
                            if($this->passwordIsAlreadyUsed($value)){
                                $fail('Vous avez déjà utilisé ce mot de passe.');
                            }
                        };
                    }
                ])
                ->password()
                ->required(),
            TextInput::make('state.password_confirmation')
                ->label('Confirmer le nouveau mot de passe')
                ->rules(['required'])
                ->password()
                ->required()
            ])->columnSpan(2)
            ])
        ];
    }

    public function passwordIsAlreadyUsed($password) : bool
    {
        return (new PasswordHistoryChecker)->validatePassword(auth()->user(), $password);
    }

    public function messages()
    {
        return [
            'state.password.different' => 'Le nouveau mot de passe doit être différent de l\'ancien.',
            'state.password.mix_case' => 'Le mot de passe doit contenir au moins une lettre majuscule et une lettre minuscule.',
            'state.password.symbols' => 'Le mot de passe doit contenir des caractères speciaux.',
            'password.letters' => 'Le mot de passe doit contenir au moins une lettre.',
            'password.numbers' => 'Le mot de passe doit contenir au moins un chiffre.',
            'state.password.uncompromised' => 'Le mot de passe doit être plus sécurisé.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ];
    }


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
