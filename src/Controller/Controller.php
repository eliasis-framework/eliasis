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

use Eliasis\App\App,
    Eliasis\View\View,
    Eliasis\App\Exception\AppException;

/**
 * Controller class.
 *
 * @since 1.0.0
 */
abstract class Controller {

    /**
     * View object.
     *
     * @since 1.0.0
     *
     * @var object
     */
    protected static $view;

    /**
     * Model object.
     *
     * @since 1.0.0
     *
     * @var object
     */
    protected static $model;

    /**
     * Controller instance.
     *
     * @since 1.0.0
     *
     * @var object
     */
    protected static $instance;

    /**
     * Ensure that view and model are instantiated when loading controllers.
     *
     * @since 1.0.0
     */
    public function __construct() {

        static::$view  = static::getView(get_class($this));
        static::$model = static::getModel(get_class($this));
    }

    /**
     * Get controller instance.
     *
     * @since 1.0.0
     *
     * @param string $className    → controller class name
     *
     * @throws ControllerException → controller not found
     * @return object              → controller instance
     */
    public static function getInstance($className) {

        $instance = static::$instance;

        if (class_exists($className)) {

            if (is_null($instance) || get_class($instance) !== $className) { 

                static::$instance = new $className;
            }

            return static::$instance;
        }

        throw new ControllerException('Controller not found', 804);
    }

    /**
     * Validate the model and return it to accessed from the controller.
     *
     * @since 1.0.0
     *
     * @param string $className    → model class name
     *
     * @throws ControllerException → model not found
     * @return object              → model instance
     */
    public static function getModel($className) {

        $model = static::$model;

        $className = str_replace('Controller', 'Model', $className);

        if (class_exists($className)) {

            if (!is_object($model) || get_class($model) !== $className) { 

                static::$model = new $className;
            }

            return static::$model;
        }

        throw new ModelException('Model not found', 805);
    }

    /**
     * Validate the view and return it to accessed from the controller.
     *
     * @since 1.0.0
     *
     * @param string $className    → view class name
     *
     * @throws ControllerException → view not found
     * @return object              → view instance
     */
    public static function getView($className) {

        $view = static::$view;

        $className = str_replace('Controller', 'View', $className);

        if (class_exists($className)) {

            if (!is_object($view) || get_class($view) !== $className) { 

                static::$view = new $className;
            }

            return static::$view;
        }

        throw new ModelException('View not found', 806);
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
