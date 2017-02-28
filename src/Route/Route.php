<?php
/**
 * Eliasis PHP Framework
 *
 * @author     Josantonius  - hola@josantonius.com
 * @author     daveismyname - dave@daveismyname.com
 * @copyright  Copyright (c) 2017
 * @license    https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link       https://github.com/Eliasis-Framework/Eliasis
 * @since      1.0.0
 */

namespace Eliasis\Route;

use Eliasis\Router\Router;

/**
 * Routes handler.
 *
 * @since 1.0.0
 */
class Route {

    /**
     * Available routes.
     *
     * @since 1.0.0
     *
     * @var object
     */
    public static $routes = [];

    /**
     * Add routes.
     *
     * @param array $route
     *
     * @since 1.0.0
     */
    public static function set($route) {

        self::$routes = array_merge($route, self::$routes);
    }

    /**
     * Get routes.
     *
     * @param array $route
     *
     * @since 1.0.0
     */
    public static function get($route) {

         return isset(self::$routes[$route]) ? self::$routes[$route] : null;
    }

    /**
     * Load routes with regular expressions if the route is not found.
     *
     * @since 1.0.0
     */
    public static function loadRegexRoutes() {

        foreach (self::$routes as $key => $value) {
                
            if (strpos($key, ':') !== false) {

                Router::any($key, $value);
            }
        }
    }
}
