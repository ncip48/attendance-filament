<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class STO extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $slug = 'sto';

    protected ?string $heading = 'Struktur Organisasi';
    protected ?string $subheading = 'Struktur Organisasi Perusahaan';

    protected static ?string $navigationLabel = 'Struktur Organisasi';

    protected static ?string $title = 'STO';

    protected static string $view = 'filament.pages.s-t-o';
}
