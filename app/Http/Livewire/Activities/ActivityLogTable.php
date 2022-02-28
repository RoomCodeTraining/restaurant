<?php

namespace App\Http\Livewire\Activities;

use App\Support\ActivityHelper;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ActivityLogTable extends DataTableComponent
{

    public function columns(): array
    {
        return [
            Column::make('Date', "created_at")->format(fn ($row) => \Carbon\Carbon::parse($row)->locale('FR_fr')->isoFormat('dddd D MMMM YYYY'))->sortable(),
            Column::make('Heure', "created_at")->format(fn ($row) => $row->format('H:i'))->sortable(),
            Column::make('Mener par', 'causer_id')->format(fn ($row) => ActivityHelper::createdBy($row))->searchable(),
            Column::make('Action', 'event')->searchable(),
        ];
    }

    public function query(): Builder
    {
        //dd(Activity::find(49));
        return Activity::query()->orderByDesc('created_at');
    }
}
