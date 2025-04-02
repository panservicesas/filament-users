<?php

namespace Panservice\FilamentUsers\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $widgets = [];

        $usersCount = Cache::tags(config('filament-users.resource.class')::ADMIN_WIDGETS_DASHBOARD_TAG_KEY)->rememberForever('users_count_UsersWidget', function () {
            Log::debug('Cached key "users_count_UsersWidget": is expired fetch data from DB');

            return config('filament-users.resource.model', \App\Models\User::class)::query()->count('id');
        });

        $widgets[] = BaseWidget\Stat::make(__('filament-users::filament-users.widget.users'), $usersCount)
            ->icon('heroicon-o-users');

        if (filamentShieldIsInstalled()) {
            $rolesCount = Cache::tags(config('filament-users.resource.class')::ADMIN_WIDGETS_DASHBOARD_TAG_KEY)->rememberForever('roles_count_UsersWidget', function () {
                Log::debug('Cached key "roles_count_UsersWidget": is expired fetch data from DB');

                return Role::query()->count('id');
            });

            $widgets[] = BaseWidget\Stat::make(__('filament-users::filament-users.resource.roles'), $rolesCount)
                ->icon('heroicon-o-shield-check');

            $permissionsCount = Cache::tags(config('filament-users.resource.class')::ADMIN_WIDGETS_DASHBOARD_TAG_KEY)->rememberForever('permissions_count_UsersWidget', function () {
                Log::debug('Cached key "permissions_count_UsersWidget": is expired fetch data from DB');

                return Permission::query()->count('id');
            });

            $widgets[] = BaseWidget\Stat::make(__('filament-users::filament-users.resource.permissions'), $permissionsCount)
                ->icon('heroicon-o-lock-closed');
        }

        return $widgets;
    }

    public static function canView(): bool
    {
        return filament()->auth()->user()?->can('widget_UsersWidget');
    }
}
