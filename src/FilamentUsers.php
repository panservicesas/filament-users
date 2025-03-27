<?php

namespace Panservice\FilamentUsers;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentUsers implements Plugin
{
    public function getId(): string
    {
        return FilamentUsersServiceProvider::$name;
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }
}
