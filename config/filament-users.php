<?php

return [
    'resource' => [
        'group' => 'Admin',
        'class' => \Panservice\FilamentUsers\Filament\Resources\UserResource::class,
        'model' => \App\Models\User::class,
        'roles' => [
            'multiple' => false,
        ],
        'datetime_format' => 'd/m/Y H:i:s',
    ],
];
