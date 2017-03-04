<?php
/**
 * Eliasis PHP Framework
 *
 * @author     Josantonius  - hola@josantonius.com
 * @copyright  Copyright (c) 2017
 * @license    https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link       https://github.com/Eliasis-Framework/Eliasis
 * @since      1.0.0
 */

namespace Eliasis\Module;

/**
 * Abstract class for module handler.
 *
 * @since 1.0.0
 */
abstract class Module {

    /**
     * Full name of the instantiated module controller.
     *
     * @since 1.0.0
     *
     * @var object
     */
    protected static $class;

    /**
     * Initializes routes and hooks when they are not created in the module.
     *
     * @since 1.0.0
     */
    public static function run() {
        
        $namespace = explode(BS, get_called_class());

        $classname = array_pop($namespace);

        $namespace = implode(BS, $namespace);

        self::$class = $namespace . BS . 'Controller' . BS . $classname;

        static::setRoutes();
        static::setHooks();
    }

    /**
     * Add routes.
     *
     * @since 1.0.0
     */
    public static function setRoutes() { }

    /**
     * Add hooks.
     *
     * @since 1.0.0
     */
    public static function setHooks() { }
}
