<?php

namespace App\Exports;

use App\Models\Menu;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class MenusExport implements FromCollection
{
    use Exportable;

    protected $record;

    public functions __construct()
    {

    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Menu::all();
    }
}