<?php

namespace Panservice\FilamentUsers\Filament\Resources\UserResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit_user');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $allowMultipleRoles = config('filament-users.resource.roles.multiple', false);

        return [
            'name' => 'required|min:5|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'roles' => 'required|min:1|max:255|exists:roles,name|'.($allowMultipleRoles ? 'array' : 'string'),
        ];
    }
}
