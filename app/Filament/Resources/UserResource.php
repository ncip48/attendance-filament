<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->minLength(8)
                    ->maxLength(255)
                    ->placeholder(__('Password')),
                Forms\Components\Select::make('role_id')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(
                        \App\Models\Role::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->name('role_id')
                    ->label('Role'),
                Forms\Components\Select::make('jenis_kelamin')
                    ->preload()
                    ->searchable()
                    ->options([
                        '0' => 'Laki-laki',
                        '1' => 'Perempuan',
                    ])
                    ->name('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable()->url(fn (User $user) => "mailto:{$user->email}"),
                Tables\Columns\TextColumn::make('role.name')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->successNotificationTitle(fn () => __('User berhasil dihapus')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
