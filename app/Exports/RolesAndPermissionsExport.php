<?php

namespace App\Exports;

use App\Models\Role;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RolesAndPermissionsExport implements  WithMultipleSheets, WithTitle
{

   use Exportable;
    private $data;

    public function __construct()
    {
        $this->data = Role::query()->with('permissions')->get();
    }

    public function sheets() : Array{
        $sheets = [];
        foreach ($this->data as $role) {
            $sheets[] = new PermissionSheets($role);
        }

        return $sheets;
    }

    public function title(): string
    {
        return "Liste des rôles et permissions";
    }

}
