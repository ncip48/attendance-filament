<?php

namespace App\Filament\Resources\LevelResource\Pages;

use App\Filament\Resources\LevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLevels extends ManageRecords
{
    protected static string $resource = LevelResource::class;

    public static ?string $title = 'Level';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
