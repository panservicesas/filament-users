<?php

namespace Panservice\FilamentUsers\Filament\Resources\Api\Handlers;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Panservice\FilamentUsers\Filament\Resources\Api\Transformers\UserTransformer;
use Panservice\FilamentUsers\Filament\Resources\UserResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;

use function Panservice\FilamentUsers\Filament\Resources\UserResource\Api\Handlers\request;

#[Group('Users')]
class PaginationHandler extends Handlers
{
    public static ?string $uri = '/';

    public static ?string $resource = UserResource::class;

    protected static string $permission = 'ViewAny:User';

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
