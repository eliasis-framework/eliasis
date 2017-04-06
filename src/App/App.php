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
     * @var object
     */
    protected static $instance;

    /**
     * Unique id for the application.
     *
     * @since 1.0.0
     *
     * @var object
     */
    public static $id;

    /**
     * Framework settings.
     *
     * @since 1.0.0
     *
     * @var array
     */
    protected static $settings = [];

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

        NULL === self::$instance and self::$instance = new self;

        return static::$instance;
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

        $instance = self::getInstance();

        $instance->_setPaths($baseDirectory);

        $instance->_setUrls($baseDirectory, $type);

        $instance->_runErrorHandler();

        $instance->_runCleaner();

        $instance->_getSettings();

        $instance->_runHooks();

        $instance->_runModules();

        $instance->_runRoutes();
    }

    /**
     * Error Handler.
     *
     * @since 1.0.1
     */
    private function _runErrorHandler() {

        if (class_exists($class='Josantonius\\ErrorHandler\\ErrorHandler')) {

            new $class;
        }
    }

    /**
     * Cleaning resources.
     *
     * @since 1.0.1
     */
    private function _runCleaner() {

        if (class_exists($Cleaner = 'Josantonius\\Cleaner\\Cleaner')) {

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
     */
    private function _setPaths($baseDirectory) {

        $baseUrl = Url::getBaseUrl();

        self::addOption("ROOT", $baseDirectory . App::DS);
        self::addOption("CORE", dirname(dirname(__DIR__)) . App::DS);
    }

    /**
     * Set url depending where the framework is launched.
     *
     * @since 1.0.1
     *
     * @param string $baseDirectory → directory where class is instantiated
     * @param string $type          → application type
     */
    private function _setUrls($baseDirectory, $type) {

        switch ($type) {

            case 'wordpress-plugin':
                $baseUrl = plugins_url(basename($baseDirectory)) . App::DS;
                break;
            
            default:
                $baseUrl = Url::getBaseUrl();
                break;
        }

        self::addOption("MODULES_URL", $baseUrl . 'modules' . App::DS);
        self::addOption("PUBLIC_URL",  $baseUrl . 'public'  . App::DS);
    }

    /**
     * Get settings.
     *
     * @since 1.0.0
     */
    private function _getSettings() {

        $path = [

            App::CORE() . 'config' . App::DS,
            App::ROOT() . 'config' . App::DS,
        ];

        $id = self::$id;

        foreach ($path as $dir) {

            if (is_dir($dir) && $handle = scandir($dir)) {

                $files = array_slice($handle, 2);

                foreach ($files as $file) {

                    $config = require($dir . $file);

                    self::$settings[$id] = array_merge(

                        self::$settings[$id], 
                        $config
                    );
                }
            }
        }         
    }

    /**
     * Load hooks.
     *
     * @since 1.0.1
     */
    private function _runHooks() {

        if (class_exists($Hook = 'Josantonius\\Hook\\Hook')) {

            $Hook::getInstance();
        }
    }

    /**
     * Load Modules.
     *
     * @since 1.0.1
     */
    private function _runModules() {

        $Module = 'Eliasis\\Module\\Module';

        $Module::loadModules(self::path('modules'));
    }

    /**
     * Load Routes.
     *
     * @since 1.0.1
     */
    private function _runRoutes() {

        $id = self::$id;

        if (class_exists($Router = 'Josantonius\\Router\\Router')) {

            if (isset(self::$settings[$id]['routes'])) {

                $Router::addRoute(self::$settings[$id]['routes']);

                unset(self::$settings[$id]['routes']);

                $Router::dispatch();
            }
        }
    }

    /**
     * Define new configuration settings.
     *
     * @since 1.0.0
     *
     * @param string $option → option name or options array
     * @param mixed  $value  → value/s
     *
     * @return
     */
    public static function addOption($option, $value) {

        $id = self::$id;

        if (is_array($value)) {

            foreach ($value as $key => $value) {
            
                self::$settings[$id][$option][$key] = $value;
            }

            return;
        }

        self::$settings[$id][$option] = $value;
    }

    /**
     * Define the application id.
     *
     * @since 1.0.1
     *
     * @param string $id → application id
     *
     * @return
     */
    public static function id($id) {

        self::$id = $id;
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
    public static function __callstatic($index, $params = []) {

        $id = self::$id;

        $settings = self::$settings[$id];

        $column[] = (isset($settings[$index])) ? $settings[$index] : null;

        if (!count($params)) {

            return (!is_null($column[0])) ? $column[0] : '';
        }

        foreach ($params as $param) {
            
            $column = array_column($column, $param);
        }
        
        return (isset($column[0])) ? $column[0] : '';
    }
}
