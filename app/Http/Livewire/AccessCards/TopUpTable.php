<?php

namespace App\Http\Livewire\AccessCards;

use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class TopUpTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public function query()
    {
    }

    public function columns(): array
    {
    }
}
