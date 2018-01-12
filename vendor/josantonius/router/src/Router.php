<?php
/**
 * Library for handling routes.
 *
 * @author    Josantonius  - <hello@josantonius.com>
 * @author    Daveismyname - <dave@daveismyname.com>
 * @copyright 2017 - 2018 (c) Josantonius - PHP-Router
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Josantonius/PHP-Router
 * @since     1.0.0
 */
namespace Josantonius\Router;

use Josantonius\Url\Url;

/**
 * Route handler.
 */
class Router
{
    /**
     * If true - do not process other routes when match is found.
     *
     * @var bool
     */
    public static $halts = false;

    /**
     * Array of routes.
     *
     * @var array
     */
    public static $routes = [];

    /**
     * Array of methods.
     *
     * @var array
     */
    public static $methods = [];

    /**
     * Array of callbacks.
     *
     * @var array
     */
    public static $callbacks = [];

    /**
     * Set an error callback.
     *
     * @var bool|int
     */
    public static $errorCallback = false;

    /**
     * Set an uri.
     *
     * @var null
     */
    public static $uri;

    /**
     * Response from called method.
     *
     * @since 1.0.6
     *
     * @var callable
     */
    public static $response;

    /**
     * Set route patterns.
     *
     * @var array
     */
    public static $patterns = [
        ':any' => '[^/]+',
        ':num' => '-?[0-9]+',
        ':all' => '.*',
        ':hex' => '[[:xdigit:]]+',
        ':uuidV4' => '\w{8}-\w{4}-\w{4}-\w{4}-\w{12}',
    ];

    /**
     * Method name to use the singleton pattern and just create an instance.
     *
     * @var string
     */
    private static $singleton = 'getInstance';

    /**
     * Defines a route with or without callback and method.
     *
     * @param string $method
     * @param array  $params
     */
    public static function __callstatic($method, $params)
    {
        $uri = $params[0];

        $callback = $params[1];

        array_push(self::$routes, $uri);
        array_push(self::$methods, strtoupper($method));
        array_push(self::$callbacks, $callback);
    }

    /**
     * Set method name for use singleton pattern.
     *
     * @param string $method → singleton method name
     *
     * @return bool
     */
    public static function setSingletonName($method)
    {
        if (! is_string($method) || empty($method)) {
            return false;
        }

        self::$singleton = $method;

        return true;
    }

    /**
     * Add route/s.
     *
     * @param array $routes → routes to add
     *                      string $routes[0] → route
     *                      string $routes[1] → class@method
     *
     * @uses string Url::addBackSlash → add backslash if it doesn't exist
     *
     * @link https://github.com/Josantonius/PHP-Url
     *
     * @return bool
     */
    public static function add($routes)
    {
        if (! is_array($routes)) {
            return false;
        }

        foreach ($routes as $route => $value) {
            self::$routes[Url::addBackSlash($route)] = $value;
        }

        return true;
    }

    /**
     * Get method to call from URI.
     *
     * @param string $route
     *
     * @uses \string Url::addBackSlash → add backslash if it doesn't exist
     *
     * @return string|null → route or null
     */
    public static function getMethod($route)
    {
        $route = Url::addBackSlash($route);

        return isset(self::$routes[$route]) ? self::$routes[$route] : null;
    }

    /**
     * Defines callback if route is not found.
     *
     * @param callable $callback
     *
     * @return bool true
     */
    public static function error($callback)
    {
        self::$errorCallback = $callback;

        return true;
    }

    /**
     * Continue processing after match (true) or stop it (false).
     *
     * Also can specify the number of total routes to process (int).
     *
     * @since 1.0.4
     *
     * @param bool|int $value
     *
     * @return bool true
     */
    public static function keepLooking($value = true)
    {
        $value = (! is_bool($value) || ! is_int($value)) ? false : true;

        $value = (is_int($value) && $value > 0) ? $value - 1 : $value;

        self::$halts = $value;

        return true;
    }

    /**
     * Runs the callback for the given request.
     *
     * @return response|false
     */
    public static function dispatch()
    {
        self::routeValidator();

        self::$routes = str_replace('//', '/', self::$routes);

        if (in_array(self::$uri, self::$routes, true)) {
            return self::checkRoutes();
        }

        return self::checkRegexRoutes() ?: self::getErrorCallback();
    }

    /**
     * Call object and instantiate.
     *
     * By default it will look for the 'getInstance' method to use singleton
     * pattern and create a single instance of the class. If it does not
     * exist it will create a new object.
     *
     * @see setSingletonName() for change the method name.
     *
     * @param object $callback
     * @param array  $matched  → array of matched parameters
     *
     * @return callable|false
     */
    protected static function invokeObject($callback, $matched = null)
    {
        $last = explode('/', $callback);
        $last = end($last);

        $segments = explode('@', $last);

        $class = $segments[0];
        $method = $segments[1];
        $matched = $matched ? $matched : [];

        if (method_exists($class, self::$singleton)) {
            $instance = call_user_func([$class, self::$singleton]);

            return call_user_func_array([$instance, $method], $matched);
        }

        if (class_exists($class)) {
            $instance = new $class;

            return call_user_func_array([$instance, $method], $matched);
        }

        return false;
    }

    /**
     * Clean resources.
     */
    private static function cleanResources()
    {
        self::$callbacks = [];
        self::$methods = [];
        self::$halts = false;
        self::$response = false;
    }

    /**
     * Validate route.
     *
     * @uses \string Url::getUriMethods → remove subdirectories & get methods
     * @uses \string Url::setUrlParams  → return url without url params
     * @uses \string Url::addBackSlash  → add backslash if it doesn't exist
     */
    private static function routeValidator()
    {
        self::$uri = Url::getUriMethods();

        self::$uri = Url::setUrlParams(self::$uri);

        self::$uri = Url::addBackSlash(self::$uri);

        self::cleanResources();

        if (self::getMethod(self::$uri)) {
            self::any(self::$uri, self::$routes[self::$uri]);
        }
    }

    /**
     * Check if route is defined without regex.
     *
     * @return callable|false
     */
    private static function checkRoutes()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        $route_pos = array_keys(self::$routes, self::$uri, true);

        foreach ($route_pos as $route) {
            $methodRoute = self::$methods[$route];

            if ($methodRoute == $method || $methodRoute == 'ANY') {
                if (! is_object($callback = self::$callbacks[$route])) {
                    self::$response = self::invokeObject($callback);
                } else {
                    self::$response = call_user_func($callback);
                }

                if (! self::$halts) {
                    return self::$response;
                }

                self::$halts--;
            }
        }

        return self::$response;
    }

    /**
     * Check if route is defined with regex.
     *
     * @uses \string Url::addBackSlash → add backslash if it doesn't exist
     *
     * @return callable|false
     */
    private static function checkRegexRoutes()
    {
        $pos = 0;

        self::getRegexRoutes();

        $method = $_SERVER['REQUEST_METHOD'];
        $searches = array_keys(self::$patterns);
        $replaces = array_values(self::$patterns);

        foreach (self::$routes as $route) {
            $route = str_replace($searches, $replaces, $route);
            $route = Url::addBackSlash($route);

            if (preg_match('#^' . $route . '$#', self::$uri, $matched)) {
                $methodRoute = self::$methods[$pos];

                if ($methodRoute == $method || $methodRoute == 'ANY') {
                    $matched = explode('/', trim($matched[0], '/'));

                    array_shift($matched);

                    if (! is_object(self::$callbacks[$pos])) {
                        self::$response = self::invokeObject(
                            self::$callbacks[$pos],
                            $matched
                        );
                    } else {
                        self::$response = call_user_func_array(
                            self::$callbacks[$pos],
                            $matched
                        );
                    }

                    if (! self::$halts) {
                        return self::$response;
                    }

                    self::$halts--;
                }
            }

            $pos++;
        }

        return self::$response;
    }

    /**
     * Load routes with regular expressions if the route is not found.
     *
     * @since 1.0.3
     */
    private static function getRegexRoutes()
    {
        foreach (self::$routes as $key => $value) {
            unset(self::$routes[$key]);

            if (strpos($key, ':') !== false) {
                self::any($key, $value);
            }
        }
    }

    /**
     * Get error callback if route does not exists.
     *
     * @since 1.0.3
     *
     * @return callable
     */
    private static function getErrorCallback()
    {
        $errorCallback = self::$errorCallback;

        self::$errorCallback = false;

        if (! $errorCallback) {
            return false;
        }

        if (! is_object($errorCallback)) {
            return self::invokeObject($errorCallback);
        }

        return call_user_func($errorCallback);
    }
}
