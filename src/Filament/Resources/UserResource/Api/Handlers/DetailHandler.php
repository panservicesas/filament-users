<?php

namespace Panservice\FilamentUsers\Filament\Resources\UserResource\Api\Handlers;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Panservice\FilamentUsers\Filament\Resources\UserResource;
use Panservice\FilamentUsers\Filament\Resources\UserResource\Api\Transformers\UserTransformer;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('Users')]
class DetailHandler extends Handlers
{
    public static ?string $uri = '/{id}';

    public static ?string $resource = UserResource::class;

    /**
     * Show User
     *
     * @return UserTransformer
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');

        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (! $query) {
            return static::sendNotFoundResponse();
        }

        return new UserTransformer($query);
    }
}
