<?php

namespace Panservice\FilamentUsers\Traits;

use Composer\InstalledVersions;
use Illuminate\Foundation\Console\AboutCommand;

trait HasAboutCommand
{
    public function configureAboutCommand(): void
    {
        AboutCommand::add('Filament Users', [
            'filament-users' => InstalledVersions::getPrettyVersion('panservicesas/filament-users'),
        ]);
    }
}
