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

require_once '/tmp/wordpress-tests-lib/wp-tests-config.php';

return [

    'db' => [
        'app' => [
            'provider' => 'PDOprovider',
            'host' => DB_HOST,
            'user' => DB_USER,
            'name' => DB_NAME,
            'password' => DB_PASSWORD,
            'settings' => ['charset' => DB_CHARSET],
        ],
    ],
];
