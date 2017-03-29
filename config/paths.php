<?php
/**
 * WordPress plugin with Eliasis PHP Framework.
 *
 * @author     Josantonius - hello@josantonius.com
 * @copyright  Copyright (c) 2017
 * @license    https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link       https://github.com/Eliasis-Framework/WordPress-Plugin
 * @since      1.0.0
 */

use Eliasis\App\App;

$DS	  = App::DS;
$ROOT = App::ROOT();

return [

    'path' => [

        'modules'  => $ROOT . 'modules' .$DS,
        'public'   => $ROOT . 'public'  .$DS,
        'layout'   => $ROOT . 'src'     .$DS. 'template' .$DS. 'layout'   .$DS,
        'pages'    => $ROOT . 'src'     .$DS. 'template' .$DS. 'pages'    .$DS,
        'elements' => $ROOT . 'src'     .$DS. 'template' .$DS. 'elements' .$DS,
    ],
];
