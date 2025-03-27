<?php

namespace Panservice\FilamentUsers;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Panservice\FilamentUsers\Filament\Resources\UserResource;

class FilamentUsers implements Plugin
{
    public function getId(): string
    {
        return FilamentUsersServiceProvider::$name;
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            config('filament-users.resource.class', UserResource::class),
        ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }
}
