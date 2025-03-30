<?php

namespace Panservice\FilamentUsers\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UsersWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $usersCount = Cache::tags(config('filament-users.resource.class')::ADMIN_WIDGETS_DASHBOARD_TAG_KEY)->rememberForever('UsersWidget', function () {
            Log::debug('Cached key "UsersWidget": is expired fetch data from DB');

            return config('filament-users.resource.model', \App\Models\User::class)::query()->count('id');
        });

        return [
            BaseWidget\Stat::make(__('filament-users::filament-users.widget.users'), $usersCount)
                ->icon('heroicon-o-users'),
        ];
    }

    public static function canView(): bool
    {
        return filament()->auth()->user()?->can('UsersWidget');
    }
}
