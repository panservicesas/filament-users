<?php

return [
    'resource' => [
        'slug' => 'users',
        'group' => null,
        'cluster' => null,
        'sub_navigation_position' => \Filament\Pages\Enums\SubNavigationPosition::Start,
        'class' => \Panservice\FilamentUsers\Filament\Resources\UserResource::class,
        'model' => \App\Models\User::class,
        'roles' => [
            'type' => 'select', //can be 'select' or 'checkbox'
            'multiple' => false,
        ],
        'datetime_format' => 'd/m/Y H:i:s',
        'filters' => [
            'date_format' => 'd/m/Y',
        ],
        'global_search' => [
            'title' => '',
            'attributes' => [],
            'enabled' => false,
        ],
    ],
    'email' => [
        'logo' => null,
        'footer_text' => null,
        'layout' => 'filament-users::layouts.email',
    ],
];
