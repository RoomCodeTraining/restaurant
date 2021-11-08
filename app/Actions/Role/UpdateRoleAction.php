<?php

namespace App\Actions\Role;

use App\Models\Role;
use App\Models\Permission;

class UpdateRoleAction
{
    public function execute(Role $role, array $data): Role
    {
        $role->update(['name' => $data['name'], 'description' => $data['description']]);
        $role->syncPermissions($data['permissions'] ?? []);

        if (isset($data['permissions'])) {
            $permissions = Permission::whereIn('id', $data['permissions'])
                ->whereHas('children')
                ->with('children')->get();

            foreach ($permissions as $permission) {
                $role->givePermissionTo($permission->children->pluck('name'));
            }
        }

        return $role;
    }
}
