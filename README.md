# SOAP analogue of Laravel HTTP client

## Installation

You can install the package via composer:

```bash
composer require cubesystems/soap-client
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="soap-client-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="soap-client-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="soap-client-views"
```

## Usage

```php
$soapClient = new CubeSystems\SoapClient();
echo $soapClient->echoPhrase('Hello, Soap Client!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.
