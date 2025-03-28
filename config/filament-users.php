<?php

use Panservice\FilamentUsers\Filament\Resources\UserResource;

return [
    'resource' => [
        'group' => 'Admin',
        'class' => UserResource::class,
        'globally_searchable_attributes' => [
            'name',
            'email',
        ],
        'roles' => [
            'multiple' => false,
        ],
    ],
];
