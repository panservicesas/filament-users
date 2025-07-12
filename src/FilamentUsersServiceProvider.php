<?php

namespace Panservice\FilamentUsers;

use Panservice\FilamentUsers\Commands\FilamentUsersCommand;
use Panservice\FilamentUsers\Traits\HasAboutCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentUsersServiceProvider extends PackageServiceProvider
{
    use HasAboutCommand;

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
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishAssets()
                    ->endWith(function (InstallCommand $command) {
                        $command->comment('Running migrations...');
                        $command->call('migrate', [
                            '--force' => true,
                        ]);
                    });
            });
    }

    public function bootingPackage(): void
    {
        parent::packageBooted();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadMigrationsFrom(__DIR__ . '/../database/settings');

        $this->configureAboutCommand();
    }
}
