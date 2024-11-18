# Laravel Oneclick

![Laravel oneclick](https://sextanet.sfo2.cdn.digitaloceanspaces.com/packages/laravel-oneclick/logo.webp)

Laravel, Transbank, Webpay and SextaNet products and logos are property of their respective companies.

The easiest way to use Oneclick in your projects

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sextanet/laravel-oneclick.svg?style=flat-square)](https://packagist.org/packages/sextanet/laravel-oneclick)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/sextanet/laravel-oneclick/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/sextanet/laravel-oneclick/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/sextanet/laravel-oneclick/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/sextanet/laravel-oneclick/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/sextanet/laravel-oneclick.svg?style=flat-square)](https://packagist.org/packages/sextanet/laravel-oneclick)

## Installation

You can install the package via composer:

```bash
composer require sextanet/laravel-oneclick
```

Publish and run migrations

```bash
php artisan vendor:publish --tag="oneclick-migrations"
php artisan migrate
```

Copy these keys in your `.env` file

```dotenv
ONECLICK_IN_PRODUCTION=false
ONECLICK_COMMERCE_CODE=
ONECLICK_SECRET_KEY=
ONECLICK_DEBUG=true
```

## Usage

For convenience, you can set custom pages for each status before calling `payWithOnepay() method`

### Cancelled

```php
LaravelOneclick::setCancelledUrl('/cancelled-page');
```

### Rejected

```php
LaravelOneclick::setRejectedUrl('/rejected-page');
```

## Testing cards

|Type        |Numbers            |Result  |
|------------|-------------------|--------|
|VISA        |4051 8856 0044 6623|Approved|
|Mastercard  |5186 0595 5959 0568|Rejected|
|Redcompra   |4051 8842 3993 7763|Approved|
|VISA Prepaid|4051 8860 0005 6590|Approved|

- Expire date: (Any valid date)
- RUT: 11111111-1
- Password: 123

Source: [Official Transbank Developers website](https://www.transbankdevelopers.cl/documentacion/como_empezar#tarjetas-de-prueba)

## Production mode

When you are ready to be in production, you need to set `WEBPAY_IN_PRODUCTION` to `true`, and specify `WEBPAY_COMMERCE_CODE` and `WEBPAY_SECRET_KEY`.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
