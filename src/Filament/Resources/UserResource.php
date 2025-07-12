<?php

namespace Panservice\FilamentUsers\Filament\Resources;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Panservice\FilamentUsers\Filament\Resources\UserResource\Pages\EditUser;
use Panservice\FilamentUsers\Filament\Resources\UserResource\Pages\ListUsers;
use Panservice\FilamentUsers\Support\Utils;
use Panservice\FilamentUsers\Tables\Columns\RolesList;

class UserResource extends Resource
{
    const ADMIN_WIDGETS_DASHBOARD_TAG_KEY = 'admin-widgets-dashboard';

    protected static ?int $navigationSort = 0;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getGloballySearchableAttributes(): array
    {
        return config('filament-users.resource.globally_searchable_attributes', [
            'name', 'email',
        ]);
    }

    public static function getSlug(): string
    {
        return config('filament-users.resource.slug', 'admin/users');
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->name;
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-users::filament-users.resource.users');
    }

    public static function getNavigationGroup(): ?string
    {
        return config('filament-users.resource.group');
    }

    public static function getBreadcrumb(): string
    {
        return __('filament-users::filament-users.resource.users');
    }

    public static function getModel(): string
    {
        return config('filament-users.resource.model', \App\Models\User::class);
    }

    public static function getCluster(): ?string
    {
        return config('filament-users.resource.cluster');
    }

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return config('filament-users.resource.sub_navigation_position') ?? self::$subNavigationPosition;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormSchema($form));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getColumns())
            ->filters(self::getFilters())
            ->actions(self::getActions())
            ->bulkActions(self::getBulkActions())
            ->checkIfRecordIsSelectableUsing(fn(Model $record): bool => $record->id !== auth()->user()?->id)
            ->persistFiltersInSession()
            ->paginated();
    }

    private static function getFormSchema(Form $form): array
    {
        $fields = [
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
                ->required(fn(string $context): bool => $context === 'create')
                ->revealable(),
        ];

        if (Utils::isFilamentShieldInstalled()) {
            $fields[] = Forms\Components\Select::make('roles')
                ->label(__('filament-users::filament-users.resource.role'))
                ->relationship('roles', 'name')
                ->getOptionLabelFromRecordUsing(fn(Model $record) => Str::headline($record->name))
                ->multiple(config('filament-users.resource.roles.multiple', false))
                ->preload()
                ->searchable()
                ->required();
        }

        if (Utils::isFilamentBreezyInstalled()) {
            $fields[] = Forms\Components\Toggle::make('ignore_2fa')
                ->label(__('filament-users::filament-users.resource.ignore_2fa'))
                ->onColor('danger')
                ->offColor('success')
                ->onIcon('heroicon-o-shield-exclamation')
                ->offIcon('heroicon-o-shield-check');
        }

        return [
            Forms\Components\Section::make()
                ->schema($fields)
                ->columns($form->getOperation() === 'edit' ? 2 : 1),
        ];
    }

    private static function getColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label(__('filament-users::filament-users.resource.name'))
                ->searchable(),
            Tables\Columns\TextColumn::make('email')
                ->label(__('filament-users::filament-users.resource.email'))
                ->searchable(),
            RolesList::make('roles')
                ->label(__('filament-users::filament-users.resource.role'))
                ->visible(fn(): bool => Utils::isFilamentShieldInstalled()),
            Tables\Columns\ToggleColumn::make('ignore_2fa')
                ->label(__('filament-users::filament-users.resource.ignore_2fa'))
                ->onColor('danger')
                ->offColor('success')
                ->onIcon('heroicon-o-shield-exclamation')
                ->offIcon('heroicon-o-shield-check')
                ->visible(fn(): bool => Utils::isFilamentBreezyInstalled()),
            Tables\Columns\TextColumn::make('last_login_at')
                ->label(__('filament-users::filament-users.resource.last_login_at'))
                ->formatStateUsing(function (string $state): string {
                    return Carbon::parse($state)
                        ->format(config('filament-users.resource.datetime_format', 'Y-m-d H:i:s'));
                })
                ->placeholder(__('filament-users::filament-users.resource.never_logged_in'))
                ->visible(fn(): bool => Utils::isFilamentAuthenticationLogInstalled()),
            Tables\Columns\TextColumn::make('created_at')
                ->label(__('filament-users::filament-users.resource.created_at'))
                ->dateTime(config('filament-users.resource.datetime_format', 'Y-m-d H:i:s')),
        ];
    }

    private static function getFilters(): array
    {
        $dateFormat = config('filament-users.resource.filters.date_format', 'Y-m-d');
        $createdFromLabel = __('filament-users::filament-users.resource.created_from');
        $createdUntilLabel = __('filament-users::filament-users.resource.created_until');

        $filters = [];

        if (Utils::isFilamentShieldInstalled()) {
            $filters[] = Tables\Filters\SelectFilter::make('roles')
                ->label(__('filament-users::filament-users.resource.role'))
                ->relationship('roles', 'name')
                ->getOptionLabelFromRecordUsing(fn(Model $record) => Str::headline($record->name))
                ->searchable()
                ->preload();
        }

        $filters[] = Tables\Filters\Filter::make('created_at')
            ->form([
                Forms\Components\DatePicker::make('created_from')
                    ->label($createdFromLabel)
                    ->closeOnDateSelection()
                    ->displayFormat($dateFormat)
                    ->native(false),
                Forms\Components\DatePicker::make('created_until')
                    ->label($createdUntilLabel)
                    ->closeOnDateSelection()
                    ->displayFormat($dateFormat)
                    ->native(false),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        $data['created_from'],
                        fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                    )
                    ->when(
                        $data['created_until'],
                        fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                    );
            })
            ->indicateUsing(function (array $data) use ($dateFormat, $createdFromLabel, $createdUntilLabel): array {
                $indicators = [];

                if ($data['created_from'] ?? null) {
                    $indicators[] = Tables\Filters\Indicator::make(
                        "$createdFromLabel " . Carbon::parse($data['created_from'])
                            ->format($dateFormat)
                    )->removeField('created_from');
                }

                if ($data['created_until'] ?? null) {
                    $indicators[] = Tables\Filters\Indicator::make(
                        "$createdUntilLabel " . Carbon::parse($data['created_until'])
                            ->format($dateFormat)
                    )->removeField('created_until');
                }

                return $indicators;
            });

        return $filters;
    }

    private static function getActions(): array
    {
        $actions = [];

        if (Utils::isFilamentImpersonateInstalled()) {
            $actions[] = \STS\FilamentImpersonate\Tables\Actions\Impersonate::make()
                ->iconSize(IconSize::Small)
                ->color(Color::Amber)
                ->redirectTo(Filament::getCurrentPanel()->getPath());
        }

        $actions[] = Tables\Actions\EditAction::make()
            ->iconSize(IconSize::Medium)
            ->label(false);

        $actions[] = Tables\Actions\DeleteAction::make()
            ->iconSize(IconSize::Medium)
            ->label(false)
            ->visible(fn(Model $record): bool => $record->id !== auth()->user()?->id)
            ->after(function () {
                Cache::tags(config('filament-users.resource.class')::ADMIN_WIDGETS_DASHBOARD_TAG_KEY)->flush();
            });

        return $actions;
    }

    private static function getBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                    ->after(function () {
                        Cache::tags(config('filament-users.resource.class')::ADMIN_WIDGETS_DASHBOARD_TAG_KEY)->flush();
                    }),
            ]),
        ];
    }

    public static function getRelations(): array
    {
        $relations = [];

        if (Utils::isFilamentAuthenticationLogInstalled()) {
            $relations[] = \Tapp\FilamentAuthenticationLog\RelationManagers\AuthenticationLogsRelationManager::class;
        }

        return $relations;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return parent::canAccess(); // TODO: Change the autogenerated stub
    }
}
