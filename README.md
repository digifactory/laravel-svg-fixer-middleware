# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digifactory/laravel-svg-fixer.svg?style=flat-square)](https://packagist.org/packages/digifactory/laravel-svg-fixer)
[![Build Status](https://img.shields.io/travis/digifactory/laravel-svg-fixer/master.svg?style=flat-square)](https://travis-ci.org/digifactory/laravel-svg-fixer)
[![Quality Score](https://img.shields.io/scrutinizer/g/digifactory/laravel-svg-fixer.svg?style=flat-square)](https://scrutinizer-ci.com/g/digifactory/laravel-svg-fixer)
[![Total Downloads](https://img.shields.io/packagist/dt/digifactory/laravel-svg-fixer.svg?style=flat-square)](https://packagist.org/packages/digifactory/laravel-svg-fixer)

Fixes your uploaded SVG files before validating through the common Laravel validation

## Installation

You can install the package via composer:

```bash
composer require digifactory/laravel-svg-fixer
```

## Usage
By default, the middleware filters all post requests for SVG files and simply fixes them for the Laravel validator when not valid. In most cases this will be caused by the missing XML starting declaration.

Simply register the newly created class in your middleware stack.
``` php
// app/Http/Kernel.php

class Kernel extends HttpKernel
{
    protected $middleware = [
        // ...
        DigiFactory\SvgFixer\SvgFixerMiddleware::class,
    ];
    
    // ...
}
```

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
