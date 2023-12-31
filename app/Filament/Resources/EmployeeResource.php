<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    // protected static ?string $activeNavigationIcon = 'heroicon-s-document-text';

    protected static ?string $navigationLabel = 'Karyawan';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //nik and nip
                Forms\Components\TextInput::make('nik')
                    ->autofocus()
                    ->maxLength(255)
                    ->placeholder(__('NIK'))
                    ->autocomplete('off')
                    ->rules('required'),
                Forms\Components\TextInput::make('nip')
                    ->autofocus()
                    ->maxLength(255)
                    ->placeholder(__('NIP'))
                    ->autocomplete('off')
                    ->rules('required'),
                Forms\Components\TextInput::make('name')
                    ->autofocus()
                    ->maxLength(255)
                    ->placeholder(__('Name'))
                    ->autocomplete('off')
                    ->rules('required'),
                Forms\Components\TextInput::make('email')
                    ->rules(['required', 'email'])
                    ->maxLength(255)
                    ->placeholder(__('Email'))
                    ->autocomplete('off'),
                Forms\Components\TextInput::make('phone_number')
                    ->autofocus()
                    ->maxLength(255)
                    ->placeholder(__('Phone'))
                    ->rules('required')
                    ->autocomplete('off'),
                //address
                Forms\Components\Textarea::make('address')
                    ->autofocus()
                    ->maxLength(255)
                    ->placeholder(__('Address'))
                    ->autocomplete('off')
                    ->rules('required'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->minLength(8)
                    ->maxLength(255)
                    ->placeholder(__('Password'))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(
                        fn ($state) => filled($state)
                    )
                    ->required(fn (string $context): bool => $context === 'create'),
                Forms\Components\Select::make('gender')
                    ->preload()
                    ->required()
                    ->searchable()
                    ->options([
                        '0' => 'Laki-laki',
                        '1' => 'Perempuan',
                        '2' => 'Lainnya',
                    ])
                    ->label('Jenis Kelamin'),
                Forms\Components\Select::make('position_id')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(
                        \App\Models\Position::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->name('position_id')
                    ->label('Jabatan'),
                Forms\Components\Select::make('last_education')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options([
                        '0' => 'SMA/SMK',
                        '1' => 'D3',
                        '2' => 'S1',
                        '3' => 'S2',
                        '4' => 'S3',
                    ])
                    ->name('last_education')
                    ->label('Pendidikan Terakhir'),
                Forms\Components\FileUpload::make('photo')
                    ->image()
                    ->rules('image', 'max:1024')
                    ->imageCropAspectRatio('1:1')
                    ->optimize('webp')
                    ->resize(50)
                    ->columnSpan(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //nik and nip
                Tables\Columns\TextColumn::make('nik')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->label('NIK'),
                Tables\Columns\TextColumn::make('nip')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->label('NIP'),
                Tables\Columns\TextColumn::make('position.level.name')
                    ->searchable()
                    ->sortable()
                    ->label('Jabatan'),
                Tables\Columns\TextColumn::make('position.department.name')
                    ->searchable()
                    ->sortable()
                    ->label('Divisi'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable()
                    ->sortable()
                    ->label('No HP'),
                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->sortable()
                    ->label('Alamat'),
                Tables\Columns\TextColumn::make('last_education')
                    ->searchable()
                    ->sortable()
                    ->label('Pendidikan Terakhir')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '0' => 'SMA/SMK',
                        '1' => 'D3',
                        '2' => 'S1',
                        '3' => 'S2',
                        '4' => 'S3',
                    }),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'warning',
                        '1' => 'info',
                        '2' => 'danger'
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '0' => 'Laki-laki',
                        '1' => 'Perempuan',
                        '2' => 'Lainnya',
                    }),
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular(),
                Tables\Columns\TextColumn::make('banned_at')
                    ->color(fn (string $state): string => match ($state) {
                        $state => empty($state) ? 'success' : 'danger', // Change color based on condition
                    })
                    //if the banned_at not null then icon will be ban-circle
                    // ->icon(fn (string $state): string => match ($state) {
                    //     $state => !empty($state) ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle',
                    // })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        $state => empty($state) ? 'Active' : 'Banned', // Change label based on condition
                    })
                    ->badge()
                    ->label('Status'),
            ])
            ->groups([
                Group::make('department.name')
                    ->label('Divisi')
            ])
            ->filters([
                Filter::make('Laki-laki')
                    ->query(fn (Builder $query): Builder => $query->where('gender', '0')),
                Filter::make('Perempuan')
                    ->query(fn (Builder $query): Builder => $query->where('gender', '1')),
                Filter::make('Lainnya')
                    ->query(fn (Builder $query): Builder => $query->where('gender', '2')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->successNotificationTitle(fn () => __('Karyawan berhasil dihapus')),
                \Widiu7omo\FilamentBandel\Actions\BanAction::make()
                    ->color('danger')
                    ->label('')
                    ->successNotificationTitle(fn () => __('Karyawan berhasil dibanned')),
                \Widiu7omo\FilamentBandel\Actions\UnbanAction::make()
                    ->color('success')
                    ->label('')
                    ->successNotificationTitle(fn () => __('Karyawan berhasil diunbanned')),
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
            'index' => Pages\ManageEmployees::route('/'),
        ];
    }
}
