<p class="filament-hidden">
<img src="https://banners.beyondco.de/filament-users.png?theme=light&packageManager=composer+require&packageName=panservicesas%2Ffilament-users&pattern=architect&style=style_1&description=Easily+manage+your+Filament+users&md=1&showWatermark=0&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg" class="filament-hidden">
</p>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/panservicesas/filament-users.svg?style=flat-square)](https://packagist.org/packages/panservicesas/filament-users)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/panservicesas/filament-users/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/panservicesas/filament-users/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/panservicesas/filament-users/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/panservicesas/filament-users/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/panservicesas/filament-users.svg?style=flat-square)](https://packagist.org/packages/panservicesas/filament-users)

Manage your users with integration of filament-shield and filament-impersonate.

## Version Compatibility

| Plugin  | Filament | Laravel | PHP |
| ------------- | ------------- | ------------- | -------------|
| 1.x  | 3.x  | 10.x | 8.x |
| 1.x  | 3.x  | 11.x \| 12.x | 8.2 \| 8.3 \| 8.4 |

## Installation

You can install the package via composer:

```bash
composer require panservicesas/filament-users
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-users-config"
```

This is the contents of the published config file:

```php
return [
    'resource' => [
        'group' => 'Admin',
        'class' => UserResource::class,
    ],
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-users-views"
```

## Usage

```php
->plugin(\Panservice\FilamentUsers\FilamentUsers::make())
```

## Testing

```bash
composer test
```

## Languages Supported

Filament Users Plugin is translated for:

- English <sup><sub>EN</sub></sup>
- Italian <sup><sub>IT</sub></sup>

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Marco Germani](https://github.com/marcogermani87)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
