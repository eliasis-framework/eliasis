<?php
/**
 * Eliasis PHP Framework application
 *
 * @author     Josantonius - hello@josantonius.com
 * @copyright  Copyright (c) 2017
 * @license    https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link       https://github.com/Eliasis-Framework/App
 * @since      1.0.0
 */

namespace Eliasis\Module;

use Eliasis\App\App,
    Josantonius\Hook\Hook,
    Josantonius\Router\Router,
    Eliasis\Module\Exception\ModuleException;

/**
 * Module class.
 *
 * @since 1.0.0
 */
class Module { 

    /**
     * Module instance.
     *
     * @since 1.0.0
     *
     * @var object
     */
    protected static $instance;

    /**
     * Available modules.
     *
     * @since 1.0.0
     *
     * @var array
     */
    protected $modules = [];

    /**
     * Name of current module called.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public static $moduleName;

    /**
     * Get module instance.
     *
     * @since 1.0.0
     *
     * @return object → module instance
     */
    public static function getInstance() {

        if (is_null(self::$instance)) { 

            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Load all modules found in the directory.
     *
     * @since 1.0.1
     *
     * @param string $path → modules folder path
     */
    public static function loadModules($path) {

        if (is_dir($path) && $handle = opendir($path)) {

            while ($dir = readdir($handle)) {

                $ignore = App::DS . '.' . App::DS . '..' . App::DS;

                if ((is_dir($path . $dir)) && !strpos($ignore, "$dir")) {

                    $file = $path . $dir . App::DS . $dir . '.php';

                    if (file_exists($file)) {

                        $module = require($file);

                        self::add($module, $path . $dir);
                    }
                }
            }

            closedir($handle);
        }
    }

    /**
     * Add module.
     *
     * @since 1.0.0
     *
     * @param string $module → module info
     * @param string $path   → module path
     *
     * @throws ModuleException → module configuration file error
     */
    public static function add($module, $path) {

        $instance = self::getInstance();

        $required = [

            'name',
            'version',
            'description',
            'uri',
            'author',
            'author-uri',
            'license',
        ];

        if (count(array_intersect_key(array_flip($required),$module)) !== 7) {

            $message = 'The module configuration file is not correct. Path';

            throw new ModuleException($message . ': ' . $path . '.', 816);
        }

        self::$moduleName = $module['name'];

        $folder = explode('/', $path);

        $instance->modules[App::$id][self::$moduleName] = [

            'path'   => $path . App::DS,
            'folder' => array_pop($folder) . App::DS,
        ];

        $instance->_getSettings();

        $instance->_addResources();
    }

    /**
     * Get settings.
     *
     * @since 1.0.0
     */
    private function _getSettings() {

        $id = App::$id;

        $name = self::$moduleName;

        $path = $this->modules[$id][$name]['path'] . 'config'. App::DS;

        if (is_dir($path) && $handle = scandir($path)) {

            $files = array_slice($handle, 2);

            foreach ($files as $file) {

                $content = require($path . $file);

                $merge = array_merge($this->modules[$id][$name], $content);

                $this->modules[$id][$name] = $merge;
            }
        }
    }
                                                                               
    /**
     * Add module routes and hooks if exists.
     *
     * @since 1.0.1
     */
    private function _addResources() {

        $config = $this->modules[App::$id][self::$moduleName];

        if (isset($config['hooks']) && count($config['hooks'])) {

            Hook::addHook($config['hooks']);
        } 

        if (isset($config['routes']) && count($config['routes'])) {

            Router::addRoute($config['routes']);
        }
    }

    /**
     * Receives the name of the module to execute: Module::ModuleName();
     *
     * @param string $index  → module name
     * @param array  $params → params
     *
     * @throws ModuleException → Module not found
     *
     * @return mixed
     */
    public static function __callstatic($index, $params = '') {

        $instance = self::getInstance();

        if (!isset($instance->modules[App::$id][$index])) {

            $message = 'Module not found';
            throw new ModuleException($message . ': ' . $index . '.', 817);
        }

        self::$moduleName = $index;

        $column[] = $instance->modules[App::$id][$index];

        if (!count($params)) {

            return (!is_null($column[0])) ? $column[0] : '';
        }

        foreach ($params as $param) {
            
            $column = array_column($column, $param);
        }
        
        return (isset($column[0])) ? $column[0] : '';
    }
}