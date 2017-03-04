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

        self::addOption('user', ['ip' => Ip::get()]);

        Route::set(['/' => self::namespace('controller') . 'Home@render']);

        $hooks = Hook::getInstance();

        $hooks->loadModules(App::path('modules'));

        $hooks->run('routes');

        Router::dispatch();
    }

    /**
     * Add global constants for the application.
     *
     * @since 1.0.0
     */
    private static function _setConstants($baseDirectory) {

        define('BS', '\\');
        define('DS', DIRECTORY_SEPARATOR);
        define("ROOT", $baseDirectory . DS);
        define("CORE", dirname(dirname(__DIR__)) . DS);
        define("MODULES_URL", Url::getBaseUrl()  . 'modules' . DS);
        define("PUBLIC_URL",  Url::getBaseUrl()  . 'public'  . DS);
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

                    $config = require($dir . $file);

                    self::$settings = array_merge(self::$settings, $config);
                }

                unset($config);
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

        self::$settings[$name] = $value;
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
                return self::$settings[$index][$params[0]];
                break;

            case '2':
                return self::$settings[$index][$params[0]][$params[1]];
                break;

            default:
                throw new AppException('No parameter was received', 800);
                break;
        }
    }
}
