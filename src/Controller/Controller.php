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
     * Controller instance.
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
     * Get controller instance.
     *
     * @since 1.0.0
     *
     * @return object → controller instance
     */
    public static function getInstance() {

        $instance = self::$instance;

        $controller = get_called_class();

        self::$view = self::getViewInstance();

        if (is_null($instance) || $controller !== get_class($instance)) { 

            self::$instance = new $controller;
        }

        return self::$instance;
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
}
