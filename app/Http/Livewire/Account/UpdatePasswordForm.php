<?php

namespace App\Http\Livewire\Account;

use App\Actions\User\UpdateUserPassword;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpdatePasswordForm extends Component
{
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

    public function updatePassword(UpdateUserPassword $updater)
    {
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
