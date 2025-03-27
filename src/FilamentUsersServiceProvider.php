<?php

namespace Panservice\FilamentUsers;

use Panservice\FilamentUsers\Commands\FilamentUsersCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentUsersServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-users';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('filament-users')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasCommand(FilamentUsersCommand::class);
    }

    public function bootingPackage(): void {}
}
