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
    Josantonius\Url\Url;

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
     * @param string $baseDirectory → directory where class is instantiated.
     *
     * @since 1.0.0
     */
    public function __construct($baseDirectory) {

        $this->_runErrorHandler();

        $this->_runCleaner();

        $this->_setConstants($baseDirectory);

        $this->_getSettings();

        $this->_runHooks();

        $this->_runModules();

        $this->_runRoutes();
    }

    /**
     * Error Handler.
     *
     * @since 1.0.1
     */
    private static function _runErrorHandler() {

        if (class_exists($class = 'Josantonius\ErrorHandler\ErrorHandler')) {

            new $class;
        }
    }

    /**
     * Cleaning resources.
     *
     * @since 1.0.1
     */
    private static function _runCleaner() {

        if (class_exists($Cleaner = 'Josantonius\Cleaner\Cleaner')) {

            $Cleaner::removeMagicQuotes();
            $Cleaner::unregisterGlobals();
        }
    }

    /**
     * Add global constants for the application.
     *
     * @param string $baseDirectory → directory where class is instantiated.
     *
     * @since 1.0.0
     */
    private function _setConstants($baseDirectory) {

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

        $path = [

            CORE . 'config' . DS,
            ROOT . 'config' . DS,
        ];

        foreach ($path as $dir) {

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
     * Load hooks.
     *
     * @since 1.0.1
     */
    private static function _runHooks() {

        if (class_exists($Hook = 'Josantonius\Hook\Hook')) {

            $hooks = $Hook::getInstance();

            $hooks->run('routes');
        }
    }

    /**
     * Load Modules.
     *
     * @since 1.0.1
     */
    private static function _runModules() {

        $Module = 'Eliasis\Module\Module';

        $Module::loadModules(App::path('modules'));
    }

    /**
     * Load Routes.
     *
     * @since 1.0.1
     */
    private static function _runRoutes() {

        if (class_exists($Router = 'Josantonius\Router\Router')) {

            if (isset(self::$settings['routes'])) {

                $Router::addRoute(self::$settings['routes']);

                unset(self::$settings['routes']);

                $Router::dispatch();
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
