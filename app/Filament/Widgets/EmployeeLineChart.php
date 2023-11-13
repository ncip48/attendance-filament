<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use Filament\Widgets\ChartWidget;

class EmployeeLineChart extends ChartWidget
{
    protected static ?string $heading = 'Karyawan';

    public function getDescription(): ?string
    {
        return 'Jumlah Karyawan berdasarkan divisi';
    }

    protected static ?string $pollingInterval = '10s';

    // public static function canView(): bool
    // {
    //     return auth()->user()->role_id == 2;
    // }

    protected static ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => false,
            ],
        ],
    ];

    protected function getData(): array
    {
        $departments = Department::all();
        return [
            'datasets' => [
                [
                    'label' => 'Karyawan',
                    'data' => $departments->map(fn ($department) => $department->employees()->count())->toArray(),
                    'fill' => false,
                    'tension' => 0.5,
                ],
            ],
            'labels' => $departments->pluck('code')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
