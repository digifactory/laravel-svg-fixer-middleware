# Fix 'invalid' SVG files

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digifactory/laravel-svg-fixer-middleware.svg?style=flat-square)](https://packagist.org/packages/digifactory/laravel-svg-fixer-middleware)
[![MIT Licensed](https://img.shields.io/github/license/digifactory/laravel-svg-fixer-middleware?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/digifactory/laravel-svg-fixer-middleware/master?style=flat-square)](https://travis-ci.org/digifactory/laravel-svg-fixer-middleware)
[![Quality Score](https://img.shields.io/scrutinizer/g/digifactory/laravel-svg-fixer-middleware/master?style=flat-square)](https://scrutinizer-ci.com/g/digifactory/laravel-svg-fixer-middleware)
[![StyleCI](https://styleci.io/repos/217690645/shield?branch=master)](https://styleci.io/repos/217690645)
[![Total Downloads](https://img.shields.io/packagist/dt/digifactory/laravel-svg-fixer-middleware.svg?style=flat-square)](https://packagist.org/packages/digifactory/laravel-svg-fixer-middleware)

The Laravel `image` validator doesn't validate an SVG if the file is missing the XML declaration on top. This middleware automatically fixes these incorrect SVG files by adding the XML declaration before it is validated.

## Installation

You can install the package via composer:

```bash
composer require digifactory/laravel-svg-fixer-middleware
```

## Usage
Simply register the `SvgFixerMiddleware` class in your middleware stack.
``` php
// app/Http/Kernel.php

class Kernel extends HttpKernel
{
    protected $middleware = [
        // ...
        \DigiFactory\SvgFixer\SvgFixerMiddleware::class,
    ];
    
    // ...
}
```

The middleware only runs for `POST` requests containing SVG files.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email helpdesk@digifactory.nl instead of using the issue tracker.

## Credits

- [DigiFactory Webworks](https://github.com/digifactory)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
