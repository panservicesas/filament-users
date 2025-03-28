<?php

namespace Panservice\FilamentUsers\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;
use Panservice\FilamentUsers\Filament\Resources\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('filament-users::filament-users.resource.users');
    }

    public static function getResource(): string
    {
        return config('filament-users.resource.class', UserResource::class);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->slideOver()
                ->modalWidth(MaxWidth::Large)
                ->closeModalByClickingAway(false),
        ];
    }
}
