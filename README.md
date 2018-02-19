# Eliasis PHP Framework

[![Packagist](https://img.shields.io/packagist/v/symfony/symfony.svg)](https://github.com/Eliasis-Framework/Eliasis) [![Packagist](https://img.shields.io/packagist/l/doctrine/orm.svg)](https://github.com/Eliasis-Framework/Eliasis) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/d93b5c9ef2784bc7a4d1577f0835c41d)](https://www.codacy.com/app/Josantonius/PHP-eliasis?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Josantonius/PHP-eliasis&amp;utm_campaign=Badge_Grade) [[![Packagist](https://img.shields.io/packagist/dt/doctrine/orm.svg)](https://github.com/Eliasis-Framework/Eliasis) [![Travis](https://travis-ci.org/Josantonius/PHP-eliasis.svg)](https://travis-ci.org/Josantonius/PHP-eliasis) [![PSR2](https://img.shields.io/badge/PSR-2-1abc9c.svg)](http://www.php-fig.org/psr/psr-2/) [![PSR4](https://img.shields.io/badge/PSR-4-9b59b6.svg)](http://www.php-fig.org/psr/psr-4/) [![CodeCov](https://codecov.io/gh/Josantonius/PHP-eliasis/branch/master/graph/badge.svg)](https://codecov.io/gh/Josantonius/PHP-eliasis)

[Versión en español](README-ES.md)

![image](resources/eliasis-php-framework.png)

---

- [Installation](#installation)
- [Requirements](#requirements)
- [Quick Start and Examples](#quick-start-and-examples)
- [Available Methods](#available-methods)
- [Usage](#usage)
- [Tests](#tests)
- [TODO](#-todo)
- [Developed with Eliasis](#developed-with-eliasis)
- [Contribute](#contribute)
- [License](#license)
- [Copyright](#copyright)

---

### Installation

You can install Eliasis PHP Framework into your project using [Composer](http://getcomposer.org/download/). If you're starting a new project, we
recommend using the [basic app](https://github.com/eliasis-framework/app) as
a starting point. For existing applications you can run the following:

    $ composer require Eliasis-Framework/Eliasis

The previous command will only install the necessary files, if you prefer to download the entire source code (including tests, vendor folder, exceptions not used, docs...) you can use:

    $ composer require Eliasis-Framework/Eliasis --prefer-source

Or you can also clone the complete repository with Git:

	$ git clone https://github.com/Eliasis-Framework/Eliasis.git

### Requirements

This framework is supported by PHP versions 5.6 or higher and is compatible with HHVM versions 3.0 or higher.

### Quick Start and Examples

To use this framework, simply:

```php
$DS = DIRECTORY_SEPARATOR;

require __DIR__ . $DS . 'lib' . $DS . 'vendor' . $DS .'autoload.php';

use Eliasis\App\App;

App::run(__DIR__);

// App::run(__DIR__, 'app', 'unique_id');

// App::run(__DIR__, 'wordpress-plugin', 'unique_id');
```

### Available Methods

Available methods in this library:

### Usage

Example of use for this library:

### Tests 

To run [tests](tests/DataType/Test) simply:

    $ git clone https://github.com/Eliasis-Framework/Eliasis.git
    
    $ cd Eliasis

    $ phpunit

### ☑ TODO

- [x] Create tests
- [ ] Improve documentation

## Developed with Eliasis

| Module | Description | Type
| --- | --- | --- |
| [Search Inside](https://github.com/Josantonius/Search-Inside.git) | Easily search text within your pages or blog posts. | WordPress Plugin
| [Extensions For Grifus](https://github.com/Josantonius/Extensions-For-Grifus.git) | Extensions for the Grifus theme. | WordPress Plugin

### Contribute

1. Check for open issues or open a new issue to start a discussion around a bug or feature.
1. Fork the repository on GitHub to start making your changes.
1. Write one or more tests for the new feature or that expose the bug.
1. Make code changes to implement the feature or fix the bug.
1. Send a pull request to get your changes merged and published.

This is intended for large and long-lived objects.

### License

This project is licensed under **MIT license**. See the [LICENSE](LICENSE) file for more info.

### Copyright

2017 Josantonius, [josantonius.com](https://josantonius.com/)

If you find it useful, let me know :wink:

You can contact me on [Twitter](https://twitter.com/Josantonius) or through my [email](mailto:hello@josantonius.com).