<?php
/**
 * Eliasis PHP Framework
 *
 * @author     Josantonius - hello@josantonius.com
 * @copyright  Copyright (c) 2017
 * @license    https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link       https://github.com/Eliasis-Framework/Eliasis
 * @since      1.0.0
 */

use Eliasis\App\App;

$versionFile = file(dirname(__DIR__) . App::DS . 'VERSION.txt');

return [

    'eliasis' => [

        'version' => trim(array_pop($versionFile)),
    ],
];
