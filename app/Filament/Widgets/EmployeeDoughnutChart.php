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
        $other = Employee::where('gender', '2')->count();


        return [
            'datasets' => [
                [
                    'data' => [$male, $female, $other],
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                    ],
                    'borderColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                    ]
                ],
            ],
            'labels' => ['Laki-Laki', 'Perempuan', 'Lainnya'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
