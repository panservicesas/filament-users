<?php

namespace Panservice\FilamentUsers\Filament\Resources\UserResource\Api\Handlers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Panservice\FilamentUsers\Filament\Resources\UserResource;
use Rupadana\ApiService\Http\Handlers;

class DeleteHandler extends Handlers
{
    public static ?string $uri = '/{id}';

    public static ?string $resource = UserResource::class;

    public static function getMethod()
    {
        return Handlers::DELETE;
    }

    public static function getModel()
    {
        return static::$resource::getModel();
    }

    /**
     * Delete User
     *
     * @return JsonResponse
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');

        throw_if($id == $request->user()->id, ValidationException::withMessages([
            'id' => ['You cannot delete yourself'],
        ]));

        $model = static::getModel()::find($id);

        if (! $model) {
            return static::sendNotFoundResponse();
        }

        $model->delete();

        return static::sendSuccessResponse($model, 'Successfully Delete Resource');
    }
}
