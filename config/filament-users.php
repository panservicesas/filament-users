<?php

return [
    'resource' => [
        'group' => null,
        'cluster' => null,
        'slug' => 'users',
        'class' => \Panservice\FilamentUsers\Filament\Resources\UserResource::class,
        'model' => \App\Models\User::class,
        'roles' => [
            'multiple' => false,
        ],
        'datetime_format' => 'd/m/Y H:i:s',
        'filters' => [
            'date_format' => 'd/m/Y',
        ],
    ],
];
