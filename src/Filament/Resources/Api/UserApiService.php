<?php

namespace Panservice\FilamentUsers\Filament\Resources\Api;

use Panservice\FilamentUsers\Filament\Resources\UserResource;
use Rupadana\ApiService\ApiService;

class UserApiService extends ApiService
{
    protected static ?string $resource = UserResource::class;

    public static function handlers(): array
    {
        return [
            \Panservice\FilamentUsers\Filament\Resources\Api\Handlers\CreateHandler::class,
            \Panservice\FilamentUsers\Filament\Resources\Api\Handlers\UpdateHandler::class,
            \Panservice\FilamentUsers\Filament\Resources\Api\Handlers\DeleteHandler::class,
            \Panservice\FilamentUsers\Filament\Resources\Api\Handlers\PaginationHandler::class,
            \Panservice\FilamentUsers\Filament\Resources\Api\Handlers\DetailHandler::class,
        ];
    }
}
