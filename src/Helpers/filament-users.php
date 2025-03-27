<?php

use Filament\Facades\Filament;

if (! function_exists('filamentShieldIsInstalled')) {
    /**
     * Check if the radiator is installed in the Laravel application.
     *
     * @return bool True if the radiator is installed, false otherwise.
     */
    function filamentShieldIsInstalled(): bool
    {
        return array_key_exists('filament-shield', Filament::getCurrentPanel()->getPlugins());
    }
}
