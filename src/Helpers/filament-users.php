<?php

use Filament\Facades\Filament;

if (! function_exists('filamentShieldIsInstalled')) {
    /**
     * Checks if the 'filament-shield' plugin is installed in the current Filament panel.
     *
     * @return bool True if the 'filament-shield' plugin is installed, false otherwise.
     */
    function filamentShieldIsInstalled(): bool
    {
        return class_exists('\BezhanSalleh\FilamentShield\FilamentShieldServiceProvider');
    }
}

if (! function_exists('filamentAuthenticationLogIsInstalled')) {
    /**
     * Determines if the 'authentication-log' plugin is installed in the current Filament panel.
     *
     * @return bool True if the 'authentication-log' plugin is installed, false otherwise.
     */
    function filamentAuthenticationLogIsInstalled(): bool
    {
        return class_exists('\Rappasoft\LaravelAuthenticationLog\LaravelAuthenticationLogServiceProvider');
    }
}
