<?php

namespace Panservice\FilamentUsers\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Cache;
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
                ->closeModalByClickingAway(false)
                ->mutateFormDataUsing(function (array $data) {
                    if (! config('filament-users.resource.roles.multiple', false)) {
                        unset($data['roles']);
                    }

                    return $data;
                })
                ->after(function () {
                    Cache::tags(config('filament-users.resource.class')::ADMIN_WIDGETS_DASHBOARD_TAG_KEY)->flush();
                }),
        ];
    }
}
