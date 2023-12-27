<?php

namespace App\Exports;

use App\Models\Menu;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MenusExport implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{
    use Exportable;

    protected $record;

    public function __construct($data)
    {
        $this->data = $data;
        // $this->data =  \App\Models\Order::whereNotState('state', [Suspended::class, Cancelled::class])->OrderBy('dish_id', 'desc')->today()->with('user', 'menu', 'dish')->get();
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Menu::all();
    }
}
