<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAttendances extends BaseWidget
{
    protected static ?string $heading = '5  Karyawan Terakhir';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Employee::query()
                    ->orderByDesc('created_at')
                    ->with('department')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('nip')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('department.name')
                    ->searchable()
                    ->sortable(),
            ]);
    }
}
