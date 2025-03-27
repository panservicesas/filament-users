<?php

namespace Panservice\FilamentUsers\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Panservice\FilamentUsers\Filament\Resources\UserResource;

class EditUser extends EditRecord
{

    public function getHeading(): string|Htmlable
    {
        return __('filament-users::filament-users.resource.edit_user');
    }

    public static function getResource(): string
    {
        return config('filament-users.resource.class', UserResource::class);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
