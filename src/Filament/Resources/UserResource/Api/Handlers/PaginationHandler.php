<?php

namespace Panservice\FilamentUsers\Filament\Resources\UserResource\Api\Handlers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Panservice\FilamentUsers\Filament\Resources\UserResource;
use Panservice\FilamentUsers\Filament\Resources\UserResource\Api\Transformers\UserTransformer;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;

class PaginationHandler extends Handlers
{
    public static ?string $uri = '/';

    public static ?string $resource = UserResource::class;

    /**
     * List of User
     *
     * @return AnonymousResourceCollection
     */
    public function handler(Request $request)
    {
        $query = static::getEloquentQuery();

        $query = QueryBuilder::for($query)
            ->allowedFields($this->getAllowedFields() ?? [])
            ->allowedSorts($this->getAllowedSorts() ?? [])
            ->allowedFilters($this->getAllowedFilters() ?? [])
            ->allowedIncludes($this->getAllowedIncludes() ?? [])
            ->paginate(request()->query('per_page'))
            ->appends(request()->query());

        return UserTransformer::collection($query);
    }
}
