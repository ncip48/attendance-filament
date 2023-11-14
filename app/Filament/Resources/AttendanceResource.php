<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ViewAction;
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

    protected static ?int $navigationSort = 5;

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
                    ->label('Tanggal')
                    //format date to d-m-Y
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        default => Carbon::parse($state)->format('d M Y'),
                    })
                    ->sortable(),
                TextColumn::make('employee.name')
                    ->searchable()
                    ->label('Nama')
                    ->sortable(),
                TextColumn::make('clock_in')
                    ->searchable()
                    ->weight('bold')
                    ->label('Jam Masuk')
                    ->sortable(),
                TextColumn::make('clock_out')
                    ->searchable()
                    ->weight('bold')
                    ->label('Jam Keluar')
                    ->sortable(),
                TextColumn::make('clock_in_location')
                    ->searchable()
                    ->label('Koordinat Masuk')
                    ->sortable(),
                TextColumn::make('clock_out_location')
                    ->searchable()
                    ->label('Koordinat Keluar')
                    ->sortable(),
                TextColumn::make('clock_in_note')
                    ->searchable()
                    ->label('Catatan Masuk')
                    ->sortable(),
                TextColumn::make('clock_out_note')
                    ->searchable()
                    ->label('Catatan Keluar')
                    ->sortable(),
                ImageColumn::make('clock_in_image')
                    ->searchable()
                    ->label('Gambar Absen Masuk')
                    ->sortable(),
                ImageColumn::make('clock_out_image')
                    ->searchable()
                    ->label('Gambar Absen Keluar')
                    ->sortable(),
            ])
            ->groups([
                // Group::make('created_at')
                //     ->label('Date')
            ])
            ->filters([])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\Action::make('detail')
                //     ->label('Detail')
                //     ->icon('heroicon-o-eye')
                //     ->view('filament.detail-employee'),
                ViewAction::make()
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->mutateRecordDataUsing(function (array $data): array {
                        $employee = Employee::find($data['employee_id']);
                        $data['name'] = $employee->name;
                        $data['nip'] = $employee->nip;
                        return $data;
                    })
                    ->form([
                        TextInput::make('created_at')->formatStateUsing(fn (string $state): string => match ($state) {
                            default => Carbon::parse($state)->format('d M Y'),
                        })->label('Date'),
                        TextInput::make('name'),
                        TextInput::make('nip'),
                        TimePicker::make('clock_in'),
                        TimePicker::make('clock_out'),
                        TextInput::make('clock_in_location'),
                        TextInput::make('clock_out_location'),
                        Textarea::make('clock_in_note'),
                        Textarea::make('clock_out_note'),
                        FileUpload::make('clock_in_image'),
                        FileUpload::make('clock_out_image'),
                    ])
                    ->modalAlignment(Alignment::Center),
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
