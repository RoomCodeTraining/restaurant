<?php

namespace App\Http\Livewire\AccessCards;

use Rappasoft\LaravelLivewireTables\DataTableComponent;

class TopUpTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

    public function query()
    {
    }

    public function columns(): array
    {
    }
}
