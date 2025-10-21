<?php

namespace Panservice\FilamentUsers\Filament\Resources\Api\Handlers;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Panservice\FilamentUsers\Filament\Resources\Api\Requests\CreateUserRequest;
use Panservice\FilamentUsers\Filament\Resources\UserResource;
use Rupadana\ApiService\Http\Handlers;
use function Panservice\FilamentUsers\Filament\Resources\UserResource\Api\Handlers\now;

#[Group('Users')]
class CreateHandler extends Handlers
{
    public static ?string $uri = '/';

    public static ?string $resource = UserResource::class;

    protected static string $permission = 'Create:User';

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
