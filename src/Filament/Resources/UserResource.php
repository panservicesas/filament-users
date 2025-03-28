<?php

namespace Panservice\FilamentUsers\Filament\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Panservice\FilamentUsers\Filament\Resources\UserResource\Pages\CreateUser;
use Panservice\FilamentUsers\Filament\Resources\UserResource\Pages\EditUser;
use Panservice\FilamentUsers\Filament\Resources\UserResource\Pages\ListUsers;
use Panservice\FilamentUsers\Tables\Columns\RolesList;

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
                            ->multiple(config('filament-users.resource.roles.multiple', false))
                            ->preload()
                            ->searchable()
                            ->required()
                            ->visible(fn (): bool => filamentShieldIsInstalled()),
                    ]),
            ])->columns(1);
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
                    ->label(false)
                    ->slideOver()
                    ->modalWidth(MaxWidth::Large)
                    ->closeModalByClickingAway(false),
                Tables\Actions\DeleteAction::make()
                    ->iconSize(IconSize::Medium)
                    ->label(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->paginated();
    }

    public static function getRelations(): array
    {
        $relations = [];

        if (filamentAuthenticationLogIsInstalled()) {
            $relations[] = \Tapp\FilamentAuthenticationLog\FilamentAuthenticationLogPlugin::make();
        }

        return $relations;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            //            'create' => CreateUser::route('/create'),
            //            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return parent::canAccess(); // TODO: Change the autogenerated stub
    }
}
