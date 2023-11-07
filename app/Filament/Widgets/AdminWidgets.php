<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminWidgets extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    public static function canView(): bool
    {
        return auth()->user()->role_id == 1;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->icon('heroicon-o-user-group'),
            // ->description('32k increase')
            // ->descriptionIcon('heroicon-m-arrow-trending-up')
            // ->chart([7, 2, 10, 3, 15, 4, 17])
            // ->color('success'),
            Stat::make('Total Divisi', Department::count())
                ->icon('heroicon-o-building-library'),
            Stat::make('Total Karyawan', Employee::count())
                ->icon('heroicon-o-users'),
        ];
    }
}
