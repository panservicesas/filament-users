<?php

namespace Panservice\FilamentUsers\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Panservice\FilamentUsers\Filament\Resources\UserResource;

class CreateUser extends CreateRecord
{
    public static function getResource(): string
    {
        return config('filament-users.resource.class', UserResource::class);
    }

    public function getHeading(): string|Htmlable
    {
        return __('filament-users::filament-users.resource.new_user');
    }
}
