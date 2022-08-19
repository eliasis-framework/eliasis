# Eliasis PHP Framework

[![Packagist](https://img.shields.io/packagist/v/eliasis-framework/eliasis.svg)](https://packagist.org/packages/eliasis-framework/eliasis)
[![License](https://img.shields.io/packagist/l/eliasis-framework/eliasis.svg)](https://github.com/eliasis-framework/eliasis/blob/master/LICENSE)

[English version](README.md)

![image](resources/eliasis-php-framework.png)

---

- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Documentación](#documentation)
- [Tests](#tests)
- [Patrocinar](#patrocinar)
- [Licencia](#licencia)

---

## Requisitos

Este framework es soportada por versiones de **PHP 5.6** o superiores y es compatible con versiones de **HHVM 3.0** o superiores.

## Installation

Puedes instalar **Eliasis PHP Framework** en tu proyecto utilizando [Composer](http://getcomposer.org/download/). Si vas a empezar un nuevo proyecto, recomendamos utilizar nuestra [app básica](https://github.com/eliasis-framework/app) como punto de partida. Para aplicaciones existentes puedes ejecutar lo siguiente:

    composer require eliasis-framework/eliasis

El comando anterior sólo instalará los archivos necesarios, si prefieres **descargar todo el código fuente** puedes utilizar:

    composer require eliasis-framework/eliasis --prefer-source

## Documentación

[Documentación y ejemplos de uso](https://eliasis-framework.github.io/eliasis/v1.1.3/lang/es/).

## Tests

Para ejecutar las [pruebas](tests) necesitarás [Composer](http://getcomposer.org/download/) y seguir los siguientes pasos:

    git clone https://github.com/eliasis-framework/eliasis.git
    
    cd eliasis

    bash bin/install-wp-tests.sh wordpress_test root '' localhost latest

    composer install

Ejecutar pruebas unitarias con [PHPUnit](https://phpunit.de/):

    composer phpunit

Ejecutar pruebas de estándares de código [PSR2](http://www.php-fig.org/psr/psr-2/) con [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer):

    composer phpcs

Ejecutar pruebas con [PHP Mess Detector](https://phpmd.org/) para detectar inconsistencias en el estilo de codificación:

    composer phpmd

Ejecutar todas las pruebas anteriores:

    composer tests

## Patrocinar

Si este proyecto te ayuda a reducir el tiempo de desarrollo,
[puedes patrocinarme](https://github.com/josantonius/lang/es-ES/README.md#patrocinar)
para apoyar mi trabajo :blush:

## Licencia

Este repositorio tiene una licencia [MIT License](LICENSE).

Copyright © 2017-2022, [Josantonius](https://github.com/josantonius/lang/es-ES/README.md#contacto)
