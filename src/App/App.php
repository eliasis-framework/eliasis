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
                                                                                     
namespace Eliasis\App;

use Josantonius\Url\Url;

/**
 * Eliasis main class.
 *
 * @since 1.0.0
 */
class App {

    /**
     * App instance.
     *
     * @since 1.0.2
     *
     * @var array
     */
    protected static $instances;

    /**
     * Unique id for the application.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public static $id;

    /**
     * Framework settings.
     *
     * @since 1.0.0
     *
     * @var array
     */
    protected $settings = [];

    /**
     * Set directory separator constant.
     *
     * @since 1.0.1
     *
     * @var string
     */
    const DS = DIRECTORY_SEPARATOR;

    /**
     * Get controller instance.
     *
     * @since 1.0.2
     *
     * @return object → controller instance
     */
    public static function getInstance() {

        if (!isset(self::$instances[self::$id])) { 

            self::$instances[self::$id] = new self;
        }

        return self::$instances[self::$id];
    }

    /**
     * Initializer.
     *
     * @since 1.0.2
     *
     * @param string $baseDirectory → directory where class is instantiated
     * @param string $type          → application type
     * @param string $id            → unique id for the application
     */
    public static function run($baseDirectory, $type = 'app', $id = '0') {

        self::$id = $id;

        $that = self::getInstance();

        $that->_setPaths($baseDirectory, $that);

        $that->_setUrls($baseDirectory, $type, $that);

        $that->_runErrorHandler();

        $that->_runCleaner();

        $that->_getSettings($that);

        $that->_runHooks($that);

        $that->_runModules();

        $that->_runRoutes($that);
    }

    /**
     * Error Handler.
     *
     * @since 1.0.1
     *
     * @link https://github.com/Josantonius/PHP-ErrorHandler
     */
    private function _runErrorHandler() {

        if (class_exists($class='Josantonius\ErrorHandler\ErrorHandler')) {

            new $class;
        }
    }

    /**
     * Cleaning resources.
     *
     * @since 1.0.1
     *
     * @link https://github.com/Josantonius/PHP-Cleaner
     */
    private function _runCleaner() {

        if (class_exists($Cleaner = 'Josantonius\Cleaner\Cleaner')) {

            $Cleaner::removeMagicQuotes();
            $Cleaner::unregisterGlobals();
        }
    }

    /**
     * Set application paths.
     *
     * @since 1.0.1
     *
     * @param string $baseDirectory → directory where class is instantiated
     * @param object $that          → application instance 
     */
    private function _setPaths($baseDirectory, $that) {

        $that->set("ROOT", $baseDirectory . App::DS);
        $that->set("CORE", dirname(dirname(__DIR__)) . App::DS);
        $that->set("MODULES", App::ROOT() . 'modules' . App::DS);
    }

    /**
     * Set url depending where the framework is launched.
     *
     * @since 1.0.1
     *
     * @param string $baseDirectory → directory where class is instantiated
     * @param string $type          → application type
     * @param object $that          → application instance 
     */
    private function _setUrls($baseDirectory, $type, $that) {

        switch ($type) {

            case 'wordpress-plugin':
                $baseUrl = plugins_url(basename($baseDirectory)) . '/';
                break;
            
            default:
                $baseUrl = Url::getBaseUrl();
                break;
        }

        $that->set("MODULES_URL", $baseUrl . 'modules/');
        $that->set("PUBLIC_URL",  $baseUrl . 'public/');
    }

    /**
     * Get settings.
     *
     * @since 1.0.0
     *
     * @param object $that → application instance
     */
    private function _getSettings($that) {

        $path = [

            App::CORE() . 'config' . App::DS,
            App::ROOT() . 'config' . App::DS,
        ];

        foreach ($path as $dir) {

            if (is_dir($dir) && $handle = scandir($dir)) {

                $files = array_slice($handle, 2);

                foreach ($files as $file) {

                    $config = require($dir . $file);

                    $that->settings = array_merge($that->settings, $config);
                }
            }
        }         
    }

    /**
     * Load hooks.
     *
     * @since 1.1.0
     *
     * @param object $that → application instance
     *
     * @link https://github.com/Josantonius/PHP-Hook
     */
    private function _runHooks($that) {

        if (class_exists($Hook = 'Josantonius\Hook\Hook')) {

            $Hook::getInstance(self::$id);

            if (isset($that->settings['hooks'])) {

                $Hook::addActions($that->settings['hooks']);

                unset($that->settings['hooks']);
            }
        }
    }

    /**
     * Load Modules.
     *
     * @since 1.0.1
     *
     * @link https://github.com/Eliasis-Framework/Module
     */
    private function _runModules() {

        if (is_dir($modulesPath = App::ROOT() . 'modules' . App::DS)) {

            if (class_exists($Module = 'Eliasis\Module\Module')) {

                $Module::loadModules($modulesPath);
            }
        }
    } 

    /**
     * Load Routes.
     *
     * @since 1.0.1
     *
     * @param object $that → application instance
     *
     * @link https://github.com/Josantonius/PHP-Router
     */
    private function _runRoutes($that) {

        if (class_exists($Router = 'Josantonius\Router\Router')) {

            if (isset($that->settings['routes'])) {

                $Router::addRoute($that->settings['routes']);

                unset($that->settings['routes']);

                $Router::dispatch();
            }
        }
    }

    /**
     * Define new configuration settings.
     *
     * @since 1.0.9
     *
     * @param string $option → option name or options array
     * @param mixed  $value  → value/s
     *
     * @return mixed
     */
    public static function set($option, $value) {

        $that = self::getInstance();

        if (!is_array($value)) {

            return $that->settings[$option] = $value;
        }

        if (array_key_exists($option, $value)) {

            $that->settings[$option] = array_merge_recursive(

                $that->settings[$option], $value
            );
        
        } else {

            foreach ($value as $key => $value) {
            
                $that->settings[$option][$key] = $value;
            }
        }

        return $that->settings[$option];        
    }

    /**
     * Get options saved.
     *
     * @since 1.0.9
     *
     * @param array $params
     *
     * @return mixed
     */
    public static function get(...$params) {
 
        $that = self::getInstance();

        $key = array_shift($params);

        $col[] = isset($that->settings[$key]) ? $that->settings[$key] : 0;

        if (!count($params)) {

            return ($col[0]) ? $col[0] : '';
        }

        foreach ($params as $param) {

            $col = array_column($col, $param);
        }
        
        return (isset($col[0])) ? $col[0] : '';
    }

    /**
     * Get controller instance.
     *
     * @since 1.0.9
     *
     * @param array $class     → class name
     * @param array $namespace → namespace index
     *
     * @return object|false → class instance or false
     */
    public static function instance($class, $namespace = '') {

        $that = self::getInstance();

        if (isset($that->settings['namespaces'])) {

            if (isset($that->settings['namespaces'][$namespace])) {

                $namespace = $that->settings['namespaces'][$namespace];

                $class = $namespace . $class . '\\' . $class;

                return call_user_func([$class, 'getInstance']);
            }

            foreach ($that->settings['namespaces'] as $key => $namespace) {

                $instance = $namespace . $class . '\\' . $class;
                
                if (class_exists($instance)) {

                    return call_user_func([$instance, 'getInstance']);
                }
            }
        }

        return false;
    }

    /**
     * Define the application id.
     *
     * @since 1.0.1
     *
     * @param string $id → application id
     *
     * @return string → application id
     */
    public static function id($id) {

        return self::$id = $id;
    }

    /**
     * Access the configuration parameters.
     *
     * @since 1.0.0
     *
     * @param string $index
     * @param array  $params
     *
     * @return mixed
     */
    public static function __callstatic($index, $params = false) {

        if (array_key_exists($index, self::$instances)) {

            self::id($index);

            $that = self::getInstance();

            return $that;
        } 

        array_unshift($params, $index);

        return call_user_func_array([__CLASS__, 'get'], $params);
    }
}
