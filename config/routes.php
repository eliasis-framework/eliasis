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

return [

    'routes' => [

        '/' => App::namespace('controller') . 'Home@render'
    ],
];
