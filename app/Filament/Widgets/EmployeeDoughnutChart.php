<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Widgets\ChartWidget;

class EmployeeDoughnutChart extends ChartWidget
{
    protected static ?string $heading = 'Karyawan';

    protected static ?string $pollingInterval = '10s';

    protected static ?array $options = [
        'plugins' => [
            //remove the x and y axis
            'legend' => [
                'display' => false,
            ],
        ],
    ];

    public function getDescription(): ?string
    {
        return 'Jumlah Karyawan berdasarkan jenis kelamin';
    }

    protected function getData(): array
    {
        $male = Employee::where('gender', '0')->count();
        $female = Employee::where('gender', '1')->count();

        return [
            'datasets' => [
                [
                    'data' => [$male, $female],
                ],
            ],
            'labels' => ['Laki-Laki', 'Perempuan'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
