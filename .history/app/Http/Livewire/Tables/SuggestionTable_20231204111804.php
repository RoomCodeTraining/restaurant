<?php

namespace App\Http\Livewire\Tables;

use App\Models\SuggestionBox;
use App\Models\SuggestionType;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class SuggestionTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    private static function getTableQuery() : Builder
    {
        if(auth()->user()->hasRole(\App\Models\Role::ADMIN_TECHNICAL)) {
            return SuggestionBox::query()->with('suggestionType')->whereHas('suggestionType', function ($query) {
                $query->whereId(SuggestionType::IMPROVEMENT_APPLICATION)->orWhere('user_id', auth()->user()->id);
            });
        }

        if(auth()->user()->hasRole(\App\Models\Role::ADMIN) || auth()->user()->hasRole(\App\Models\Role::ADMIN_RH)) {
            return SuggestionBox::query()
                    //  ->when($this->getFilter('created_at'), fn ($query, $created_at) => $query->whereDate('created_at', $created_at))
                    //  ->when($this->getFilter('suggestion_type_id'), fn ($query, $suggestion_type_id) => $query->where('suggestion_type_id', $suggestion_type_id))
                     ->with('suggestionType')->latest();
        }

        return SuggestionBox::query()->with('suggestionType')->whereUserId(auth()->user()->id)->latest();
    }

    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->query(self::getTableQuery())
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('created_at')->label('Date de création')->searchable()->sortable()->dateTime('d/m/Y'),
                \Filament\Tables\Columns\TextColumn::make('suggestion')->label('Suggestions'),
                \Filament\Tables\Columns\TextColumn::make('suggestionType.name')->label('Type'),
                \Filament\Tables\Columns\TextColumn::make('user.full_name')->label('Utilisateur'),
            ]);
    }
    public function render()
    {
        return view('livewire.tables.suggestion-table');
    }
}