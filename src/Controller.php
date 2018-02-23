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

use Eliasis\Framework\Exception\ControllerException;

/**
 * Controller class.
 */
abstract class Controller
{
    /**
     * Controller instances.
     *
     * @var object
     */
    protected static $instance;

    /**
     * Model instance.
     *
     * @var object
     */
    protected $model;

    /**
     * View instance.
     *
     * @var object
     */
    protected $view;

    /**
     * Prevent creating a new controller instance.
     */
    protected function __construct()
    {
    }

    /**
     * Prevents the object from being cloned.
     *
     * @throws ControllerException → clone is not allowed
     */
    public function __clone()
    {
        throw new ControllerException('Clone is not allowed in: ' . __CLASS__);
    }

    /**
     * Prevent unserializing.
     */
    private function __wakeup()
    {
    }

    /**
     * Get controller instance.
     *
     * @return object → controller instance
     */
    public static function getInstance()
    {
        $controller = get_called_class();

        if (! isset(self::$instance[$controller])) {
            self::$instance[$controller] = new $controller;
        }

        if (is_null(self::$instance[$controller]->view)) {
            self::getViewInstance(self::$instance[$controller]);
        }

        self::getModelInstance(self::$instance[$controller], $controller);

        return self::$instance[$controller];
    }

    /**
     * Get view instance.
     *
     * @param object $instance → this
     */
    protected static function getViewInstance($instance)
    {
        $instance->view = View::getInstance();
    }

    /**
     * Get model instance.
     *
     * @since 1.0.2
     *
     * @param object $instance   → this
     * @param string $controller → controller namespace
     *
     * @return object → controller instance
     */
    protected static function getModelInstance($instance, $controller = '')
    {
        $controller = empty($controller) ? $controller : get_called_class();

        $model = str_replace('Controller', 'Model', $controller);

        if (class_exists($model)) {
            $instance->model = call_user_func($model . '::getInstance');
        }
    }
}
