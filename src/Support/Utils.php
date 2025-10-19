<?php

namespace Panservice\FilamentUsers\Support;

use Filament\Contracts\Plugin;
use Filament\Facades\Filament;

class Utils
{
    public static function isFilamentShieldInstalled(): bool
    {

        if (class_exists('\BezhanSalleh\FilamentShield\FilamentShieldServiceProvider')) {
            try {
                return Filament::getPlugin('filament-shield') instanceof Plugin;
            } catch (\Exception $e) {
            }
        }

        return false;
    }

    /**
     * Determines if the 'authentication-log' plugin is installed in the current Filament panel.
     *
     * @return bool True if the 'authentication-log' plugin is installed, false otherwise.
     */
    public static function isFilamentAuthenticationLogInstalled(): bool
    {
        if (class_exists('\Tapp\FilamentAuthenticationLog\FilamentAuthenticationLogServiceProvider')) {
            try {
                return Filament::getPlugin('authentication-log') instanceof Plugin;
            } catch (\Exception $e) {
            }
        }

        return false;
    }

    public static function isFilamentBreezyInstalled(): bool
    {
        if (class_exists('\Jeffgreco13\FilamentBreezy\FilamentBreezyServiceProvider')) {
            try {
                return Filament::getPlugin('filament-breezy') instanceof Plugin;
            } catch (\Exception $e) {
            }
        }

        return false;
    }

    /**
     * Determines if the 'filament-impersonate' plugin is installed in the current Filament panel.
     *
     * @return bool True if the 'filament-impersonate' plugin is installed, false otherwise.
     */
    public static function isFilamentImpersonateInstalled(): bool
    {
        return class_exists('\STS\FilamentImpersonate\FilamentImpersonateServiceProvider');
    }
}
