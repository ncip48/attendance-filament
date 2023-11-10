<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAttendances extends BaseWidget
{
    protected static ?string $heading = '5 Absensi Terakhir Hari Ini';

    protected static ?string $pollingInterval = '10s';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Attendance::query()
                    ->orderByDesc('created_at')
                    ->with('employee')
                    ->limit(5)
                    ->whereDate('created_at', Carbon::today())
            )
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    //format date to d-m-Y
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        default => Carbon::parse($state)->format('d M Y'),
                    }),
                TextColumn::make('clock_in'),
                TextColumn::make('clock_out'),
                TextColumn::make('employee.nip')
                    ->label('NIP'),
                TextColumn::make('employee.name')
                    ->label('Nama'),
            ])
            //size of table
            ->striped()
            ->emptyStateHeading('Tidak ada data')
            ->paginated(false);
    }
}
