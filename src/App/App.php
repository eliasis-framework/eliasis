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
                                                                                     
namespace Eliasis\App;

use Eliasis\App\Exception\AppException,
    Eliasis\Router\Router,
    Eliasis\Route\Route,
    Eliasis\Hook\Hook,
    Eliasis\App\App,
    Josantonius\Ip\Ip,
    Josantonius\Url\Url,
    Josantonius\Cleaner\Cleaner,
    Josantonius\ErrorHandler\ErrorHandler;

/**
 * Eliasis main class.
 *
 * @since 1.0.0
 */
class App {

    /**
     * Framework settings.
     *
     * @since 1.0.0
     *
     * @var array
     */
    protected static $settings = [];

    /**
     * Initializer.
     *
     * @uses Josantonius\ErrorHandler\ErrorHandler->__construct()
     * @uses Josantonius\Cleaner\Cleaner::removeMagicQuotes()
     * @uses Josantonius\Cleaner\Cleaner::unregisterGlobals()
     * @uses Josantonius\Ip\Ip::get()
     * @uses Eliasis\Route\Route::set()
     * @uses Eliasis\Hook\Hook::get()
     * @uses Eliasis\Hook\Hook->run()
     * @uses Eliasis\Router\Router::dispatch()
     *
     * @since 1.0.0
     */
    public function __construct($baseDir) {

        new ErrorHandler;

        Cleaner::removeMagicQuotes();

        Cleaner::unregisterGlobals();

        $this->_setConstants($baseDir);

        $this->_getSettings();

        static::addOption('user', ['ip' => Ip::get()]);

        Route::set(['/' => static::namespace('controller') . 'Home@render']);

        $hooks = Hook::get();

        $hooks->run('routes');

        Router::dispatch();
    }

    /**
     * Add global constants for the application.
     *
     * @since 1.0.0
     */
    private static function _setConstants($baseDir) {

        define('BS', '\\');
        define('DS', DIRECTORY_SEPARATOR);
        define("ROOT", $baseDir . DS);
        define("CORE", dirname(dirname(__DIR__)) . DS);
        define("PUBLIC_URL", Url::getBaseUrl() . 'public' . DS);
    }

    /**
     * Get settings.
     *
     * @since 1.0.0
     */
    private function _getSettings() {

        $configDir = [

            CORE . 'config' . DS,
            ROOT . 'config' . DS,
        ];

        foreach ($configDir as $dir) {

            if (is_dir($dir) && $handle = scandir($dir)) {

                $files = array_slice($handle, 2);

                foreach ($files as $file) {

                    $conf = require($dir . $file);

                    static::$settings = array_merge(static::$settings, $conf);
                }

                unset($conf);
            }
        }
    }

    /**
     * Define new configuration settings.
     *
     * @param string $name
     * @param mixed  $value
     */
    public static function addOption($name, $value) {

        static::$settings[$name] = $value;
    }

    /**
     * Access the configuration parameters.
     *
     * @param string $index
     * @param array  $params
     *
     * @throws AppException → No parameter was received
     * @return mixed
     */
    public static function __callstatic($index, $params) {

        switch (count($params)) {
            case '1':
                return static::$settings[$index][$params[0]];
                break;

            case '2':
                return static::$settings[$index][$params[0]][$params[1]];
                break;

            case '3':
                return static::$settings[$index][$params[0]][$params[1]][$params[2]];
                break;

            default:
                throw new AppException('No parameter was received', 800);
                break;
        }
    }
}
