<?php

namespace Panservice\FilamentUsers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Panservice\FilamentUsers\FilamentUsers
 */
class FilamentUsers extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Panservice\FilamentUsers\FilamentUsersPlugin::class;
    }
}
