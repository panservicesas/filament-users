<p class="filament-hidden">
<img src="https://banners.beyondco.de/filament-users.png?theme=light&packageManager=composer+require&packageName=panservicesas%2Ffilament-users&pattern=architect&style=style_1&description=Easily+manage+your+Filament+users&md=1&showWatermark=0&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg" class="filament-hidden">
</p>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/panservicesas/filament-users.svg?style=flat-square)](https://packagist.org/packages/panservicesas/filament-users)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/panservicesas/filament-users/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/panservicesas/filament-users/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/panservicesas/filament-users/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/panservicesas/filament-users/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/panservicesas/filament-users.svg?style=flat-square)](https://packagist.org/packages/panservicesas/filament-users)

Manage your Filament users with integration of filament-shield, filament-authentication-log and filament-impersonate.

## Version Compatibility

| Plugin | Filament | Laravel              | PHP               |
|--------|----------|----------------------|-------------------|
| 1.x    | 3.x      | 10.x \| 11.x \| 12.x | 8.2 \| 8.3 \| 8.4 |

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
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-users-views"
```

## Usage

```php
->plugin(\Panservice\FilamentUsers\FilamentUsersPlugin::make())
```

If you use [filament-authentication-log](https://github.com/TappNetwork/filament-authentication-log) follow this configuration instructions:
- If present remove `AuthenticationLoggable` trait from your `User` model
- Add the dedicated `HasUserAuthenticationLog` trait to your `User` model

## Testing

```bash
composer test
```

## Screenshots

### Users list

<img src="https://raw.githubusercontent.com/panservicesas/filament-users/main/art/table.png" style="border-radius:2%"/>

### Advanced filters

<img src="https://raw.githubusercontent.com/panservicesas/filament-users/main/art/filters.png" style="border-radius:2%"/>

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

## About Panservice

<strong><i>Costruiamo servizi internet su misura da oltre 25 anni</i></strong>

Da oltre venticinque anni ci occupiamo di
telecomunicazioni e soluzioni ICT costruendo e
fornendo servizi digitali che si adattano alle esigenze del
cliente, e ci distinguiamo per la qualità dei servizi e del supporto
offerto ai nostri clienti.

Fin dagli anni ‘90 abbiamo compreso come Internet avrebbe rivoluzionato
le modalità con cui cittadini ed imprese avrebbero interagito fra loro e
con la Pubblica Amministrazione divenendo il cuore del moderno scambio di
informazioni e per questo continuiamo ad investire per realizzare infrastrutture
gestite in proprio.

Panservice è autorizzata alla fornitura di servizi di Comunicazione Elettronica (accesso ad
internet), servizi di Telefonia Vocale, servizi VoIP, servizi di accesso R-Lan (WISP), fornitura di
reti e servizi di comunicazione elettronica ad uso pubblico (installazione ed esercizio di rete di
accesso in fibra ottica e ponti radio), ed è iscritta al Registro degli Operatori di
Comunicazione al numero 8209.

La nostra rete tocca le città di Latina, sede del data center, Roma e Milano (in anello). E’ in corso di attivazione 
un anello N x 400 Gbit/s in fibra fra Latina, Frosinone, Roma.

Grazie a questa topologia il datacenter da cui vengono erogati i servizi, posto sull’anello, è interconnesso ad elevatissima 
capacità con i maggiori punti di interscambio nazionali, il Namex a Roma, il MIX ed il Minap di Via Caldera a Milano ed 
il PCIX di Piacenza, dove avvengono i peering diretti verso quasi quattrocento reti di altri operatori nazionali e 
internazionali nonché le interconnessioni di transito internazionale. Il data center è comunque carrier-neutral.

Il datacenter di Latina è inoltre interconnesso localmente con tratte in fibra ottica a centrali di TIM (3 centrali), Openfiber e Wind.

L’interconnessione verso internet, multihomed e multipath, è gestita tramite protocollo BGP, supporta IPv4 ed IPv6, ed ha 
un AS_Path inferiore a 3 hop verso la maggior parte delle destinazioni nazionali ed internazionali.

La rete è continuamente monitorata e viene gestita proattivamente da personale interno.

* <a href="https://www.panservice.it" target="_blank">https://www.panservice.it</a>
* <a href="https://www.olo2olo.it" target="_blank">https://www.olo2olo.it</a>
* <a href="https://datacenter.panservice.it" target="_blank">https://datacenter.panservice.it</a>
