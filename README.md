# Eliasis PHP Framework

[![Packagist](https://img.shields.io/packagist/v/eliasis-framework/eliasis.svg)](https://packagist.org/packages/eliasis-framework/eliasis)
[![License](https://img.shields.io/packagist/l/eliasis-framework/eliasis.svg)](https://github.com/eliasis-framework/eliasis/blob/master/LICENSE)

[Versión en español](README-ES.md)

![image](resources/eliasis-php-framework.png)

---

- [Requirements](#requirements)
- [Installation](#installation)
- [Documentation](#documentation)
- [Tests](#tests)
- [TODO](#-todo)
- [Sponsor](#Sponsor)
- [License](#license)

---

## Requirements

This framework is supported by **PHP versions 5.6** or higher and is compatible with **HHVM versions 3.0** or higher.

## Installation

You can install **Eliasis PHP Framework** into your project using [Composer](http://getcomposer.org/download/). If you're starting a new project, we
recommend using the [basic app](https://github.com/eliasis-framework/app) as
a starting point. For existing applications you can run the following:

    composer require eliasis-framework/eliasis

The previous command will only install the necessary files, if you prefer to **download the entire source code** you can use:

    composer require eliasis-framework/eliasis --prefer-source

## Documentation

[Documentation and examples of use](https://eliasis-framework.github.io/eliasis/v1.1.3/lang/en/).

## Tests

To run [tests](tests) you just need [composer](http://getcomposer.org/download/) and to execute the following:

    git clone https://github.com/eliasis-framework/eliasis.git
    
    cd eliasis

    bash bin/install-wp-tests.sh wordpress_test root '' localhost latest

    composer install

Run unit tests with [PHPUnit](https://phpunit.de/):

    composer phpunit

Run [PSR2](http://www.php-fig.org/psr/psr-2/) code standard tests with [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer):

    composer phpcs

Run [PHP Mess Detector](https://phpmd.org/) tests to detect inconsistencies in code style:

    composer phpmd

Run all previous tests:

    composer tests

## Sponsor

If this project helps you to reduce your development time,
[you can sponsor me](https://github.com/josantonius#sponsor) to support my open source work :blush:

## License

This repository is licensed under the [MIT License](LICENSE).

Copyright © 2017-2022, [Josantonius](https://github.com/josantonius#contact)
