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
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'User';

    protected static ?int $navigationSort = 6;

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
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(
                        fn ($state) => filled($state)
                    )
                    ->required(fn (string $context): bool => $context === 'create')
                    ->placeholder(__('Password')),
                Forms\Components\Select::make('role_id')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options([
                        '1' => 'Admin',
                        '2' => 'User',
                    ])
                    ->label('Role')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable()->url(fn (User $user) => "mailto:{$user->email}"),
                Tables\Columns\TextColumn::make('role')
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'warning',
                        '2' => 'primary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '1' => 'Admin',
                        '2' => 'User',
                    })
                    ->badge(),
                Tables\Columns\IconColumn::make('banned_at')
                    ->color(fn (string $state): string => match ($state) {
                        $state => empty($state) ? 'success' : 'danger', // Change color based on condition
                    })
                    //if the banned_at not null then icon will be ban-circle
                    ->icon(fn (string $state): string => match ($state) {
                        $state => !empty($state) ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle',
                    })
                    // ->formatStateUsing(fn (string $state): string => match ($state) {
                    //     $state => empty($state) ? 'Active' : 'Banned', // Change label based on condition
                    // })
                    ->label('Banned'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->successNotificationTitle(fn () => __('User berhasil dihapus')),
                \Widiu7omo\FilamentBandel\Actions\BanAction::make()
                    ->color('danger')
                    ->label('')
                    ->successNotificationTitle(fn () => __('User berhasil dibanned')),
                \Widiu7omo\FilamentBandel\Actions\UnbanAction::make()
                    ->color('success')
                    ->label('')
                    ->successNotificationTitle(fn () => __('User berhasil diunbanned')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    \Widiu7omo\FilamentBandel\Actions\BanBulkAction::make('banned_model'),
                    \Widiu7omo\FilamentBandel\Actions\UnbanBulkAction::make('unbanned_model'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
