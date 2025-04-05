<?php

namespace Panservice\FilamentUsers\Filament\Resources\UserResource\Api\Handlers;

use Illuminate\Http\JsonResponse;
use Panservice\FilamentUsers\Filament\Resources\UserResource;
use Panservice\FilamentUsers\Filament\Resources\UserResource\Api\Requests\UpdateUserRequest;
use Rupadana\ApiService\Http\Handlers;

class UpdateHandler extends Handlers
{
    public static ?string $uri = '/{id}';

    public static ?string $resource = UserResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel()
    {
        return static::$resource::getModel();
    }

    /**
     * Update User
     *
     * @return JsonResponse
     */
    public function handler(UpdateUserRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (! $model) {
            return static::sendNotFoundResponse();
        }

        $attributes = $request->all(array_keys($request->rules()));
        $roles = $attributes['roles'];

        unset($attributes['roles']);

        $model->fill($attributes);

        $model->assignRole($roles);

        $model->save();

        return static::sendSuccessResponse($model, 'Successfully Update Resource');
    }
}
