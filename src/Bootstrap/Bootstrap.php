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

namespace Eliasis\Bootstrap;

use Eliasis\Router\Router,
    Eliasis\Route\Route,
    Eliasis\Hook\Hook,
    Eliasis\App\App,
    Josantonius\Ip\Ip,
    Josantonius\Url\Url,
    Josantonius\Cleaner\Cleaner,
    Josantonius\ErrorHandler\ErrorHandler;

/**
 * Bootstrap library.
 *
 * @since 1.0.0
 */
class Bootstrap {

    /**
     * Initializer.
     *
     * @since 1.0.0
     */
    public static function run() {

        define('DS', DIRECTORY_SEPARATOR);

        define('BS', '\\');

        define("ROOT", getcwd());
        
        Cleaner::removeMagicQuotes();

        Cleaner::unregisterGlobals();

        new ErrorHandler;

        App::getSettings();

        define('GET_IP_USER', Ip::get());

        Url::getUri();

        new Route;

        $hooks = Hook::get();

        $hooks->run('routes');

        Router::dispatch();

        session_write_close();
    }
}
