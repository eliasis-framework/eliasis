<?php
/**
 * Eliasis PHP Framework
 *
 * @author     Josantonius - hola@josantonius.com
 * @copyright  Copyright (c) 2017
 * @license    https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link       https://github.com/Eliasis-Framework/Eliasis
 * @since      1.0.0
 */

$versionFile = file(CORE . 'VERSION.txt');

return [

    'eliasis' => [

        'version' => trim(array_pop($versionFile)),
    ],
];