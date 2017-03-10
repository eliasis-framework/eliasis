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

namespace Eliasis\Controller;

use Eliasis\View\View;

/**
 * Controller class.
 *
 * @since 1.0.0
 */
abstract class Controller {

    /**
     * Controller instances.
     *
     * @since 1.0.0
     *
     * @var object
     */
    protected static $instance;

    /**
     * View instance.
     *
     * @since 1.0.0
     *
     * @var object
     */
    protected static $view;

    /**
     * Prevent creating a new controller instance.
     *
     * @since 1.0.0
     */
    protected function __construct() { }

    /**
     * Get controller instance.
     *
     * @since 1.0.0
     *
     * @return object → controller instance
     */
    public static function getInstance() {

        $controller = get_called_class();

        self::$view = self::getViewInstance();

        if (!isset(self::$instance[$controller])) { 

            self::$instance[$controller] = new $controller;
        }

        return self::$instance[$controller];
    }

    /**
     * Get view instance.
     *
     * @since 1.0.0
     *
     * @return object → view instance
     */
    protected static function getViewInstance() {

        return is_null(self::$view) ? View::getInstance() : self::$view;
    }

    /**
     * Prevents the object from being cloned.
     *
     * @since 1.0.0
     *
     * @throws ControllerException → clone is not allowed
     */
    public function __clone() {

        throw new ModelException('Clone is not allowed in ' . __CLASS__, 807);
    }

    /**
     * Prevent unserializing.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function __wakeup() { }
}
