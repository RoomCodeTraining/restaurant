<?php

namespace App\Http\Livewire\Roles;

use App\Actions\Role\UpdateRoleAction;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditRoleForm extends Component
{
    use AuthorizesRequests;

    public $role;

    public $permissions = [];

    public $rolePermissions = [];

    public $state = [
        'name' => null,
        'description' => null,
        'has_department' => false,
        'permissions' => [],
    ];

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->rolePermissions = $this->role->permissions->pluck('id')->map(fn ($item) => (string) $item)->toArray();
        $this->state = $this->role->withoutRelations()->toArray();
        $this->permissions = Permission::isMaster()
            ->with('children')
            ->get()
            ->sortByDesc(fn ($item) => $item->children->count());
    }

    public function saveRole(UpdateRoleAction $updateRoleAction)
    {
        // $this->authorize('update', $this->role);

        $this->validate([
            'state.name' => ['required', 'string', 'min:3', Rule::unique('roles', 'name')->ignoreModel($this->role)],
            'state.description' => ['nullable', 'string', 'max:255'],
        ]);

        $updateRoleAction->execute($this->role, $this->state + ['permissions' => $this->rolePermissions]);

        session()->flash('success', 'Le rôle à été mis à jour avec succès.');

        return redirect()->route('roles.index');
    }

    public function render()
    {
        return view('livewire.roles.edit-role-form');
    }
}
