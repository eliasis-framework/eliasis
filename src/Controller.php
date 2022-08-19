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
     * Controller instances.
     *
     * @var object
     */
    private static $instances;

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

        if (isset(self::$instances[$controller])) {
            return self::$instances[$controller];
        }

        self::$instances[$controller] = new $controller();

        self::$instances[$controller]->model = self::getModelInstance($controller);

        self::$instances[$controller]->view = new View();

        return self::$instances[$controller];
    }

    /**
     * Get model instance.
     *
     * @since 1.0.2
     *
     * @param string $controller → controller namespace
     *
     * @return object → controller instance
     */
    protected static function getModelInstance($controller)
    {
        $model = str_replace('Controller', 'Model', $controller);

        return class_exists($model) ? call_user_func($model . '::getInstance') : null;
    }
}
