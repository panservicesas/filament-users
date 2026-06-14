<?php

namespace Panservice\FilamentUsers\Facades;

use Illuminate\Support\Facades\Facade;
use Panservice\FilamentUsers\FilamentUsersPlugin;

/**
 * @see \Panservice\FilamentUsers\FilamentUsers
 */
class FilamentUsers extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FilamentUsersPlugin::class;
    }
}
