<?php

namespace App\Http\Livewire\Users;

use App\Imports\UsersImport;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateUsersByImport extends Component
{
    use WithFileUploads;

    public $file;

    public $showModal = false;

    public function importUsers()
    {

        $this->validate(['file' => 'required|file',]);

$p = new UsersImport())->import($this->file);
        (new UsersImport())->import($this->file);

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.create-users-by-import');
    }
}