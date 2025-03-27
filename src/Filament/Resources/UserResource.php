<?php

namespace Panservice\FilamentUsers\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Tables\Columns\RolesList;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 0;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name', 'email',
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-users::filament-users.resource.users');
    }

    public static function getNavigationGroup(): ?string
    {
        return config('filament-users.resource.group') ?? __('filament-users::filament-users.resource.group');
    }

    public static function getBreadcrumb(): string
    {
        return __('filament-users::filament-users.resource.users');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament-users::filament-users.resource.name'))
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label(__('filament-users::filament-users.resource.email'))
                            ->email()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->required(),
                        Forms\Components\TextInput::make('password')
                            ->label(__('filament-users::filament-users.resource.password'))
                            ->password()
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->revealable(),
                        Forms\Components\Select::make('roles')
                            ->label(__('filament-users::filament-users.resource.role'))
                            ->relationship('roles', 'name')
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => Str::headline($record->name))
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required()
                            ->visible(fn (): bool => filamentShieldIsInstalled()),
                    ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-users::filament-users.resource.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('filament-users::filament-users.resource.email'))
                    ->searchable(),
                RolesList::make('roles')
                    ->label(__('filament-users::filament-users.resource.role'))
                    ->visible(fn (): bool => filamentShieldIsInstalled()),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-users::filament-users.resource.created_at'))
                    ->dateTime('d/m/Y H:i:s')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconSize(IconSize::Medium)
                    ->label(false),
                Tables\Actions\DeleteAction::make()
                    ->iconSize(IconSize::Medium)
                    ->label(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->extremePaginationLinks();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            // 'create' => Pages\CreateUser::route('/create'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return parent::canAccess(); // TODO: Change the autogenerated stub
    }
}
