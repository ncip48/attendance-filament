<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Absensi';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(
                        Employee::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->name('employee_id')
                    ->label('Employee')
                    ->columnSpan(2),
                TimePicker::make('clock_in')
                    ->autofocus()
                    ->placeholder(__('Clock In'))
                    ->rules('required'),
                TimePicker::make('clock_out')
                    ->autofocus()
                    ->placeholder(__('Clock Out'))
                    ->rules('required'),
                TextInput::make('clock_in_location')
                    ->autofocus()
                    ->default('0,0')
                    ->maxLength(255)
                    ->placeholder(__('Clock In Location'))
                    ->autocomplete('off')
                    ->rules('required'),
                TextInput::make('clock_out_location')
                    ->autofocus()
                    ->default('0,0')
                    ->maxLength(255)
                    ->placeholder(__('Clock Out Location'))
                    ->autocomplete('off')
                    ->rules('required'),
                Textarea::make('clock_in_note')
                    ->autofocus()
                    ->maxLength(255)
                    ->placeholder(__('Clock In Note'))
                    ->autocomplete('off'),
                Textarea::make('clock_out_note')
                    ->autofocus()
                    ->maxLength(255)
                    ->placeholder(__('Clock Out Note'))
                    ->autocomplete('off'),
                FileUpload::make('clock_in_image')
                    ->placeholder(__('Clock In Image'))
                    ->image()
                    ->rules('image', 'max:1024')
                    ->imageCropAspectRatio('1:1'),
                FileUpload::make('clock_out_image')
                    ->placeholder(__('Clock Out Image'))
                    ->image()
                    ->rules('image', 'max:1024')
                    ->imageCropAspectRatio('1:1')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->searchable()
                    ->label('Date')
                    //format date to d-m-Y
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        default => Carbon::parse($state)->format('d M Y'),
                    })
                    ->sortable(),
                TextColumn::make('employee.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('clock_in')
                    ->searchable()
                    ->weight('bold')
                    ->sortable(),
                TextColumn::make('clock_out')
                    ->searchable()
                    ->weight('bold')
                    ->sortable(),
                TextColumn::make('clock_in_location')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('clock_out_location')

                    ->searchable()
                    ->sortable(),
                TextColumn::make('clock_in_note')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('clock_out_note')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('clock_in_image')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('clock_out_image')
                    ->searchable()
                    ->sortable(),
            ])
            ->groups([
                // Group::make('created_at')
                //     ->label('Date')
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAttendances::route('/'),
        ];
    }
}
