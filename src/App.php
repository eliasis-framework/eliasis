<?php
/**
 * Eliasis PHP Framework.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2017 - 2018 (c) Josantonius - Eliasis Framework
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/eliasis-framework/eliasis
 * @since     1.0.0
 */
namespace Eliasis\Framework;

use Josantonius\Url\Url;

/**
 * Eliasis main class.
 */
class App
{
    /**
     * Set directory separator constant.
     *
     * @since 1.0.1
     *
     * @var string
     */
    const DS = DIRECTORY_SEPARATOR;

    /**
     * Unique id for the application.
     *
     * @var string
     */
    public static $id;

    /**
     * App instance.
     *
     * @since 1.0.2
     *
     * @var array
     */
    protected static $instances = [];

    /**
     * Framework settings.
     *
     * @var array
     */
    protected $settings = [];

    /**
     * Access the configuration parameters.
     *
     * @param string $index
     * @param array  $params
     *
     * @return mixed
     */
    public static function __callstatic($index, $params = false)
    {
        if (array_key_exists($index, self::$instances)) {
            self::setCurrentID($index);
            $that = self::getInstance();

            return $that;
        }

        array_unshift($params, $index);

        return call_user_func_array([__CLASS__, 'getOption'], $params);
    }

    /**
     * Initializer.
     *
     * @since 1.0.2
     *
     * @param string $baseDirectory → directory where class is instantiated
     * @param string $type          → application type
     * @param string $id            → unique id for the application
     *
     * @return bool true
     */
    public static function run($baseDirectory, $type = 'app', $id = 'Default')
    {
        self::$id = $id;

        $that = self::getInstance();

        $that->setPaths($baseDirectory);
        $that->setUrls($baseDirectory, $type);
        $that->setIp();
        $that->runErrorHandler();
        $that->getSettings();
        $that->runHooks();
        $that->runComplements();
        $that->runRoutes();

        return true;
    }

    /**
     * Get options saved.
     *
     * @since 1.1.2
     *
     * @param array $params
     *
     * @return mixed
     */
    public static function getOption(...$params)
    {
        $that = self::getInstance();

        $key = array_shift($params);

        $col[] = isset($that->settings[$key]) ? $that->settings[$key] : 0;

        if (! count($params)) {
            return ($col[0]) ? $col[0] : '';
        }

        foreach ($params as $param) {
            $col = array_column($col, $param);
        }

        return (isset($col[0])) ? $col[0] : '';
    }

    /**
     * Define new configuration settings.
     *
     * @since 1.1.2
     *
     * @param string $option → option name
     * @param mixed  $value  → value/s
     *
     * @return mixed
     */
    public static function setOption($option, $value)
    {
        $that = self::getInstance();

        if (! is_array($value)) {
            return $that->settings[$option] = $value;
        }

        if (array_key_exists($option, $that->settings)) {
            $that->settings[$option] = array_merge_recursive(
                is_array($that->settings[$option]) ? $that->settings[$option] : [],
                $value
            );
        } else {
            foreach ($value as $key => $value) {
                $that->settings[$option][$key] = $value;
            }
        }

        return $that->settings[$option];
    }

    /**
     * Get controller instance.
     *
     * @since 1.1.2
     *
     * @param string $class     → class name
     * @param string $namespace → namespace index
     *
     * @return object|false → class instance or false
     */
    public static function getControllerInstance($class, $namespace = '')
    {
        $that = self::getInstance();

        if (array_key_exists('namespaces', $that->settings)) {
            if (array_key_exists($namespace, $that->settings['namespaces'])) {
                return call_user_func(
                    [
                        $that->settings['namespaces'][$namespace] . $class,
                        'getInstance',
                    ]
                );
            }

            foreach ($that->settings['namespaces'] as $namespace) {
                $instance = $namespace . $class;
                if (class_exists($instance)) {
                    return call_user_func([$instance, 'getInstance']);
                }
            }
        }

        return false;
    }

    /**
     * Get the current application ID.
     *
     * @since 1.1.2
     *
     * @return string → application ID
     */
    public static function getCurrentID()
    {
        return self::$id;
    }

    /**
     * Define the current application ID.
     *
     * @since 1.1.2
     *
     * @param string $id → application ID
     *
     * @return bool
     */
    public static function setCurrentID($id)
    {
        if (array_key_exists($id, self::$instances)) {
            self::$id = $id;

            return true;
        }

        return false;
    }

    /**
     * Get application instance.
     *
     * @return object → controller app instance
     */
    protected static function getInstance()
    {
        if (! isset(self::$instances[self::$id])) {
            self::$instances[self::$id] = new self();
        }

        return self::$instances[self::$id];
    }

    /**
     * Error Handler.
     *
     * @since 1.0.1
     *
     * @link https://github.com/Josantonius/PHP-ErrorHandler
     */
    private function runErrorHandler()
    {
        if (class_exists($class = 'Josantonius\ErrorHandler\ErrorHandler')) {
            new $class();
        }
    }

    /**
     * Set application paths.
     *
     * @since 1.0.1
     *
     * @param string $baseDirectory → directory where class is instantiated
     */
    private function setPaths($baseDirectory)
    {
        $this->setOption('ROOT', Url::addBackSlash($baseDirectory));
        $this->setOption('CORE', dirname(dirname(dirname(__DIR__))) . '/');
        $this->setOption('PUBLIC', self::ROOT() . 'public/');
        $this->setOption('TEMPLATES', self::ROOT() . 'templates/');
        $this->setOption('MODULES', self::ROOT() . 'modules/');
        $this->setOption('PLUGINS', self::ROOT() . 'plugins/');
        $this->setOption('COMPONENTS', self::ROOT() . 'components/');
    }

    /**
     * Set url depending where the framework is launched.
     *
     * @since 1.0.1
     *
     * @param string $baseDirectory → directory where class is instantiated
     * @param string $type          → application type
     */
    private function setUrls($baseDirectory, $type)
    {
        switch ($type) {
            case 'wordpress-plugin':
                $pluginUrl = plugins_url(basename($baseDirectory));
                $baseUrl = Url::addBackSlash($pluginUrl);
                break;
            default:
                $baseUrl = Url::getBaseUrl();
                break;
        }

        $this->setOption('PUBLIC_URL', $baseUrl . 'public/');
        $this->setOption('MODULES_URL', $baseUrl . 'modules/');
        $this->setOption('PLUGINS_URL', $baseUrl . 'plugins/');
        $this->setOption('TEMPLATES_URL', $baseUrl . 'templates/');
        $this->setOption('COMPONENTS_URL', $baseUrl . 'components/');
    }

    /**
     * Set ip.
     *
     * @since 1.1.0
     *
     * @uses \string Ip::get() → get IP
     *
     * @link https://github.com/Josantonius/PHP-Ip
     */
    private function setIp()
    {
        if (class_exists($Ip = 'Josantonius\Ip\Ip')) {
            $ip = $Ip::get();
            $this->setOption('IP', ($ip) ? $ip : 'unknown');
        }
    }

    /**
     * Get settings.
     */
    private function getSettings()
    {
        $path = [
            self::CORE() . 'config/',
            self::ROOT() . 'config/',
        ];

        foreach ($path as $dir) {
            if (is_dir($dir) && $handle = scandir($dir)) {
                $files = array_slice($handle, 2);
                foreach ($files as $file) {
                    $config = require $dir . $file;
                    $this->settings = array_merge($this->settings, $config);
                }
            }
        }
    }

    /**
     * Load hooks.
     *
     * @since 1.1.0
     *
     * @uses \string Hook::getInstance() → get Hook instance
     * @uses \string Hook::addActions()  → add action hook
     *
     * @link https://github.com/Josantonius/PHP-Hook
     */
    private function runHooks()
    {
        if (class_exists($Hook = 'Josantonius\Hook\Hook')) {
            $Hook::getInstance(self::$id);
            if (isset($this->settings['hooks'])) {
                $Hook::addActions($this->settings['hooks']);
                unset($this->settings['hooks']);
            }
        }
    }

    /**
     * Load complements.
     *
     * @since 1.1.1
     *
     * @uses \void Component::run() → run modules
     * @uses \void Plugin::run()    → run modules
     * @uses \void Module::run()    → run modules
     * @uses \void Template::run()  → run modules
     *
     * @link https://github.com/Eliasis-Framework/Complement
     */
    private function runComplements()
    {
        $complement = 'Eliasis\Complement\\';

        if (class_exists($complement . 'Complement')) {
            call_user_func($complement . 'Type\Component::run');
            call_user_func($complement . 'Type\Plugin::run');
            call_user_func($complement . 'Type\Module::run');
            call_user_func($complement . 'Type\Template::run');
        }
    }

    /**
     * Load Routes.
     *
     * @since 1.0.1
     *
     * @uses \string Router::add()      → add routes
     * @uses \string Router::dispatch() → dispath routes
     *
     * @link https://github.com/Josantonius/PHP-Router
     */
    private function runRoutes()
    {
        if (class_exists($Router = 'Josantonius\Router\Router')) {
            if (isset($this->settings['routes'])) {
                $Router::add($this->settings['routes']);
                unset($this->settings['routes']);
            }
            $Router::dispatch();
        }
    }
}
