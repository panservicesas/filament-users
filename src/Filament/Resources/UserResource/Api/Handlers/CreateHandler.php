<?php

namespace Panservice\FilamentUsers\Filament\Resources\UserResource\Api\Handlers;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Panservice\FilamentUsers\Filament\Resources\UserResource;
use Panservice\FilamentUsers\Filament\Resources\UserResource\Api\Requests\CreateUserRequest;
use Rupadana\ApiService\Http\Handlers;

#[Group('Users')]
class CreateHandler extends Handlers
{
    public static ?string $uri = '/';

    public static ?string $resource = UserResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel()
    {
        return static::$resource::getModel();
    }

    /**
     * Create User
     *
     * @return JsonResponse
     */
    public function handler(CreateUserRequest $request)
    {
        $model = new (static::getModel());

        $attributes = $request->all(array_keys($request->rules()));
        $roles = $attributes['roles'];

        unset($attributes['roles']);

        $model->fill($attributes);

        $model->email_verified_at = now();
        $model->remember_token = Str::random(60);

        $model->assignRole($roles);

        $model->save();

        return static::sendSuccessResponse($model, 'Successfully Create Resource');
    }
}
