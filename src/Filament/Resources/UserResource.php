<?php

namespace Panservice\FilamentUsers\Filament\Resources;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Panservice\FilamentUsers\Filament\Resources\UserResource\Pages\EditUser;
use Panservice\FilamentUsers\Filament\Resources\UserResource\Pages\ListUsers;
use Panservice\FilamentUsers\Notifications\NewCredentials;
use Panservice\FilamentUsers\Support\Utils;
use Panservice\FilamentUsers\Tables\Columns\RolesList;

class UserResource extends Resource
{
    const ADMIN_WIDGETS_DASHBOARD_TAG_KEY = 'admin-widgets-dashboard';

    protected static ?int $navigationSort = 0;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-users';

    public static function getGloballySearchableAttributes(): array
    {
        return config('filament-users.resource.globally_searchable_attributes', [
            'name', 'email',
        ]);
    }

    public static function getSlug(?Panel $panel = null): string
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

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema(self::getFormSchema($schema));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getColumns())
            ->filters(self::getFilters())
            ->recordActions(self::getActions())
            ->toolbarActions(self::getBulkActions())
            ->checkIfRecordIsSelectableUsing(fn (Model $record): bool => $record->id !== auth()->user()?->id)
            ->persistFiltersInSession()
            ->paginated();
    }

    private static function getFormSchema(Schema $schema): array
    {
        $fields = [
            Forms\Components\TextInput::make('name')
                ->label(__('filament-users::filament-users.resource.name'))
                ->maxLength(255)
                ->required()
                ->columnSpan(1),
            Forms\Components\TextInput::make('email')
                ->label(__('filament-users::filament-users.resource.email'))
                ->email()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->required()
                ->columnSpan(1),
            Forms\Components\TextInput::make('password')
                ->label(__('filament-users::filament-users.resource.password'))
                ->password()
                ->required(fn (string $context): bool => $context === 'create')
                ->disabled(function (Get $get): bool {
                    return $get('generate_password');
                })
                ->dehydrated(true)
                ->revealable()
                ->columnSpan(1),
        ];

        if (Utils::isFilamentShieldInstalled()) {
            //            $fields[] = Forms\Components\Select::make('roles')
            //                ->label(__('filament-users::filament-users.resource.role'))
            //                ->relationship('roles', 'name')
            //                ->getOptionLabelFromRecordUsing(fn (Model $record) => Str::headline($record->name))
            //                ->multiple(config('filament-users.resource.roles.multiple', false))
            //                ->preload()
            //                ->searchable()
            //                ->required();
            $fields[] = Forms\Components\CheckboxList::make('roles')
                ->label(__('filament-users::filament-users.resource.role'))
                ->relationship('roles', 'name')
                ->getOptionLabelFromRecordUsing(fn (Model $record) => Str::headline($record->name))
                ->minItems(1)
                ->maxItems(function (Get $get): int {
                    return config('filament-users.resource.roles.multiple', false) ?
                        count($get('roles')) : 1;
                })
                ->columns(4)
                ->required();
        }

        $fields[] = Forms\Components\Toggle::make('generate_password')
            ->label(__('filament-users::filament-users.resource.generate_password'))
            ->onColor('success')
            ->offColor('gray')
            ->afterStateUpdated(function (bool $state, Set $set) {
                $set('password', $state ? Str::password(12) : null);
            })
            ->live();

        if (Utils::isFilamentBreezyInstalled()) {
            $fields[] = Forms\Components\Toggle::make('ignore_2fa')
                ->label(__('filament-users::filament-users.resource.ignore_2fa'))
                ->onColor('danger')
                ->offColor('success')
                ->onIcon('heroicon-o-shield-exclamation')
                ->offIcon('heroicon-o-shield-check');
        }

        return [
            Section::make()
                ->schema($fields)
                ->columns(2)
                ->columnSpanFull(),
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
                ->visible(fn (): bool => Utils::isFilamentShieldInstalled()),
            Tables\Columns\ToggleColumn::make('ignore_2fa')
                ->label(__('filament-users::filament-users.resource.ignore_2fa'))
                ->onColor('danger')
                ->offColor('success')
                ->onIcon('heroicon-o-shield-exclamation')
                ->offIcon('heroicon-o-shield-check')
                ->visible(fn (): bool => Utils::isFilamentBreezyInstalled()),
            Tables\Columns\TextColumn::make('last_login_at')
                ->label(__('filament-users::filament-users.resource.last_login_at'))
                ->formatStateUsing(function (string $state): string {
                    return Carbon::parse($state)
                        ->format(config('filament-users.resource.datetime_format', 'Y-m-d H:i:s'));
                })
                ->placeholder(__('filament-users::filament-users.resource.never_logged_in'))
                ->visible(fn (): bool => Utils::isFilamentAuthenticationLogInstalled()),
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
                ->getOptionLabelFromRecordUsing(fn (Model $record) => Str::headline($record->name))
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
                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                    )
                    ->when(
                        $data['created_until'],
                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                    );
            })
            ->indicateUsing(function (array $data) use ($dateFormat, $createdFromLabel, $createdUntilLabel): array {
                $indicators = [];

                if ($data['created_from'] ?? null) {
                    $indicators[] = Tables\Filters\Indicator::make(
                        "$createdFromLabel ".Carbon::parse($data['created_from'])
                            ->format($dateFormat)
                    )->removeField('created_from');
                }

                if ($data['created_until'] ?? null) {
                    $indicators[] = Tables\Filters\Indicator::make(
                        "$createdUntilLabel ".Carbon::parse($data['created_until'])
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

        $actions[] = Action::make('new_password')
            ->hiddenLabel()
            ->icon('heroicon-s-key')
            ->iconSize(IconSize::Medium)
            ->requiresConfirmation()
            ->modalHeading(__('filament-users::filament-users.resource.send_new_password'))
            ->databaseTransaction(true)
            ->action(function (User $record) {
                try {

                    $newPassword = Str::password(12);

                    $record->update([
                        'password' => Hash::make($newPassword),
                    ]);

                    $record->notify(new NewCredentials([
                        'name' => $record->name,
                        'password' => $newPassword,
                    ]));

                    Notification::make()
                        ->title(__('filament-users::filament-users.resource.new_password_sent'))
                        ->success()
                        ->send();

                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    Notification::make()
                        ->title(__('filament-users::filament-users.resource.new_password_not_sent'))
                        ->danger()
                        ->send();
                }
            });

        if (Utils::isFilamentImpersonateInstalled()) {
            $actions[] = \STS\FilamentImpersonate\Actions\Impersonate::make()
                ->hiddenLabel()
                ->iconSize(IconSize::Small)
                ->color(Color::Amber)
                ->redirectTo(Filament::getCurrentPanel()->getPath());
        }

        $actions[] = EditAction::make()
            ->iconSize(IconSize::Medium)
            ->label(false);

        $actions[] = DeleteAction::make()
            ->iconSize(IconSize::Medium)
            ->label(false)
            ->visible(fn (Model $record): bool => $record->id !== auth()->user()?->id)
            ->after(function () {
                Cache::tags(config('filament-users.resource.class')::ADMIN_WIDGETS_DASHBOARD_TAG_KEY)->flush();
            });

        return $actions;
    }

    private static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make()
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
