<?php

namespace Panservice\FilamentUsers\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Panservice\FilamentUsers\Support\Utils;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $widgets = [];

        $tag = config('filament-users.resource.class')::ADMIN_WIDGETS_DASHBOARD_TAG_KEY;
        $keyPostfix = '_count_UsersWidget';

        $usersCount = Cache::tags($tag)->rememberForever("user$keyPostfix", function () use ($keyPostfix) {
            Log::debug("Cached key \"user$keyPostfix\": is expired fetch data from DB");

            return config('filament-users.resource.model', \App\Models\User::class)::query()->count('id');
        });

        $widgets[] = BaseWidget\Stat::make(__('filament-users::filament-users.widget.users'), $usersCount)
            ->icon('heroicon-o-users');

        if (Utils::isFilamentShieldInstalled()) {
            $rolesCount = Cache::tags($tag)->rememberForever("role$keyPostfix", function () use ($keyPostfix) {
                Log::debug("Cached key \"role$keyPostfix\": is expired fetch data from DB");

                return Role::query()->count('id');
            });

            $widgets[] = BaseWidget\Stat::make(__('filament-users::filament-users.resource.roles'), $rolesCount)
                ->icon('heroicon-o-shield-check');

            $permissionsCount = Cache::tags($tag)->rememberForever("permission$keyPostfix", function () use ($keyPostfix) {
                Log::debug("Cached key \"permission$keyPostfix\": is expired fetch data from DB");

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
