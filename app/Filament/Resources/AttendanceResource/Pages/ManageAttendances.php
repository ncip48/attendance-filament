<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ManageAttendances extends ManageRecords
{
    protected static string $resource = AttendanceResource::class;

    public static ?string $title = 'Absensi';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make()->icon('heroicon-o-plus')->label('Tambah Absensi')
            ExportAction::make()->exports([
                // Pass a string
                ExcelExport::make()
                    ->withColumns([
                        Column::make('created_at')->heading('Tanggal')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                default => Carbon::parse($state)->format('d-m-Y'),
                            }),
                        Column::make('employee.name')->heading('Nama'),
                        Column::make('employee.position.name')->heading('Jabatan'),
                        Column::make('clock_in')->heading('Jam Masuk'),
                        Column::make('clock_out')->heading('Jam Keluar'),
                        Column::make('clock_in_location')->heading('Koordinat Masuk'),
                        Column::make('clock_out_location')->heading('Koordinat Keluar'),
                        Column::make('clock_in_note')->heading('Catatan Masuk'),
                        Column::make('clock_out_note')->heading('Catatan Keluar'),
                        Column::make('clock_in_image')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                default => asset('storage/' . $state),
                            })
                            ->heading('Gambar Absen Masuk'),
                        Column::make('clock_out_image')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                default => asset('storage/' . $state),
                            })
                            ->heading('Gambar Absen Keluar'),
                    ])
                    ->withFilename(date('Y-m-d') . ' - export'),
            ])
        ];
    }
}
