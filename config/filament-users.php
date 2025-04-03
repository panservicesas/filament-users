<?php

return [
    'resource' => [
        'slug' => 'users',
        'group' => null,
        'cluster' => null,
        'sub_navigation_position' => null,
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
