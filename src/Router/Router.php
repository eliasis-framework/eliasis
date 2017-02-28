<?php
/**
 * Eliasis PHP Framework
 *
 * @author     Daveismyname - dave@daveismyname.com
 * @author     Josantonius  - hola@josantonius.com
 * @copyright  Copyright (c) 2017
 * @license    https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link       https://github.com/Eliasis-Framework/Eliasis
 * @since      1.0.0
 */

namespace Eliasis\Router;

use Eliasis\Route\Route,
    Josantonius\Url\Url;

class Router {

    /**
     * If true - do not process other routes when match is found.
     *
     * @since 1.0.0
     *
     * @var boolean $halts
     */
    public static $halts = true;

    /**
     * Array of routes.
     *
     * @since 1.0.0
     *
     * @var array $routes
     */
    public static $routes = [];

    /**
     * Array of methods.
     *
     * @since 1.0.0
     *
     * @var array $methods
     */
    public static $methods = [];

    /**
     * Array of callbacks.
     *
     * @since 1.0.0
     *
     * @var array $callbacks
     */
    public static $callbacks = [];

    /**
     * Set an error callback.
     *
     * @since 1.0.0
     *
     * @var null $errorCallback
     */
    public static $errorCallback;

    /**
     * Requested route status.
     *
     * @since 1.0.0
     *
     * @var bool $foundRoute
     */
    public static $foundRoute = false;

    /**
     * Set an uri.
     *
     * @since 1.0.0
     *
     * @var null $uri
     */
    public static $uri;

    /**
     * Set route patterns.
     *
     * @since 1.0.0
     *
     * @var null $uri
     */
    public static $patterns = [
        ':any' => '[^/]+',
        ':num' => '-?[0-9]+',
        ':all' => '.*',
        ':hex' => '[[:xdigit:]]+',
        ':uuidV4' => '\w{8}-\w{4}-\w{4}-\w{4}-\w{12}'
    ];

    /**
     * Defines a route with or without callback and method.
     *
     * @since 1.0.0
     *
     * @param string $method
     * @param array  $params
     */
    public static function __callstatic($method, $params) {

        $uri = $params[0];
        $callback = $params[1];
               
        array_push(static::$routes, $uri);
        array_push(static::$methods, strtoupper($method));
        array_push(static::$callbacks, $callback);
    }

    /**
     * Defines callback if route is not found.
     *
     * @since 1.0.0
     *
     * @param string $callback
     */
    public static function error($callback) {

        static::$errorCallback = $callback;
    }

    /**
     * Don't load any further routes on match.
     *
     * @since 1.0.0
     *
     * @param boolean $flag
     */
    public static function haltOnMatch($flag = true) {

        static::$halts = $flag;
    }

    /**
     * Runs the callback for the given request.
     *
     * @since 1.0.0
     */
    public static function dispatch() {

        static::$uri = Url::addBackslash(Url::getUriMethods());

        static::_parseUrl();

        static::_routeValidator();

        static::$routes = str_replace('//', '/', static::$routes);

        $found_route = false;

        if (in_array(static::$uri, static::$routes)) {

            static::_checkRoutes();

        } else {
            
            Route::loadRegexRoutes();
            
            static::_checkRegexRoutes();
        }

        if (!static::$foundRoute) {

            if (!static::$errorCallback) {

                static::$errorCallback = function () {

                    /* Error page */
                };
            }

            if (!is_object(static::$errorCallback)) {

                static::invokeObject(
                    static::$errorCallback, null, 'No routes found.'
                );

                if (static::$halts) {

                    return;
                }

            } else {

                call_user_func(static::$errorCallback);

                if (static::$halts) {

                    return;
                }
            }
        }
    }

    /**
     * Parse query parameters.
     *
     * @since 1.0.0
     */
    private static function _parseUrl() {

        $query = '';

        $q_arr = array();

        if (strpos(static::$uri, '&') > 0) {

            $query = substr(static::$uri, strpos(static::$uri, '&') + 1);

            static::$uri = substr(static::$uri, 0, strpos(static::$uri, '&'));

            $q_arr = explode('&', $query);

            foreach ($q_arr as $q) {

                $qobj = explode('=', $q);

                $q_arr[] = array($qobj[0] => $qobj[1]);

                if (!isset($_GET[$qobj[0]])) {

                    $_GET[$qobj[0]] = $qobj[1];
                }
            }
        }
    }

    /**
     * Check if route is defined without regex.
     *
     * @since 1.0.0
     *
     * @return 
     */
    private static function _checkRoutes() {

        $method = $_SERVER['REQUEST_METHOD'];

        $route_pos = array_keys(static::$routes, static::$uri);

        foreach ($route_pos as $route) {

            $methodRoute = static::$methods[$route];

            if ($methodRoute == $method || $methodRoute == 'ANY') {

                static::$foundRoute = true;

                if (!is_object(static::$callbacks[$route])) {

                    static::invokeObject(static::$callbacks[$route]);

                } else {

                    call_user_func(static::$callbacks[$route]);
                }

                if (static::$halts) {

                    return;
                }
            }
        }
    }

    /**
     * Check if route is defined with regex.
     *
     * @since 1.0.0
     *
     * @return 
     */
    private static function _checkRegexRoutes() {

        $pos = 0;

        $method = $_SERVER['REQUEST_METHOD'];

        $searches = array_keys(static::$patterns);

        $replaces = array_values(static::$patterns);

        foreach (static::$routes as $route) {

            $route = str_replace($searches, $replaces, $route);

            if (preg_match('#^' . $route . '$#', static::$uri, $matched)) {

                $methodRoute = static::$methods[$pos];

                if (!$methodRoute == $method || !$methodRoute == 'ANY') {

                    $pos++;

                    continue;
                }

                static::$foundRoute = true;

                array_shift($matched);

                if (!is_object(static::$callbacks[$pos])) {

                    static::invokeObject(static::$callbacks[$pos], $matched);

                } else {

                    call_user_func_array(static::$callbacks[$pos], $matched);
                }

               if (static::$halts) {

                    return;
                }
            }

            $pos++;
        }
    }

    /**
     * Call object and instantiate.
     *
     * @since 1.0.0
     *
     * @param object $callback
     * @param array  $matched  → array of matched parameters
     * @param string $msg
     */
    public static function invokeObject($callback, $matched=null, $msg=null) {

        $last = explode('/', $callback);
        $last = end($last);

        $segments = explode('@', $last);

        $controller = $segments[0];
        $method = $segments[1];

        $instance = $controller::getInstance($controller);

        call_user_func_array([$instance, $method], $matched ? $matched : []);
    }
    
    /**
     * Obtain user's language through the browser.
     *
     * @since 1.0.0
     */
    private static function _routeValidator() {

        if (!is_null(Route::get(static::$uri))) {

            static::any(static::$uri, Route::$routes[static::$uri]);
        }  
    }
}
