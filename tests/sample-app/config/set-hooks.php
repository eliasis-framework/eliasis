<?php
/**
 * Eliasis PHP Framework application.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2017 - 2018 (c) Josantonius - Eliasis Framework
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Eliasis-Framework/Eliasis
 * @since     1.1.2
 */

use Eliasis\Framework\App;

$homeClass = App::namespaces('controller') . 'Home';

return [

    'hooks' => [

        ['header', [$homeClass, 'header'], 8, 0],
        ['footer', [$homeClass, 'footer'], 8, 0],
        ['css', [$homeClass, 'css'], 8, 0],
        ['js',  [$homeClass, 'js'], 8, 0],
    ],
];
