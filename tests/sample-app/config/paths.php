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

$rootPath = App::ROOT();

return [

    'path' => [

        'layout' => $rootPath . 'src/template/layout/',
        'page'   => $rootPath . 'src/template/page/',
    ],
];
