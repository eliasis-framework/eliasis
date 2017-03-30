<?php
/**
 * Eliasis PHP Framework
 *
 * @author     Josantonius - hello@josantonius.com
 * @copyright  Copyright (c) 2017
 * @license    https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link       https://github.com/Eliasis-Framework/Eliasis
 * @since      1.0.0
 */

namespace Eliasis\Model;

use Eliasis\Model\Exception\ModelException;

/**
 * Model class.
 *
 * @since 1.0.0
 */
abstract class Model { 

    /**
     * Model instances.
     *
     * @since 1.0.2
     *
     * @var object
     */
    protected static $instance;

    /**
     * Prevent creating a new model instance.
     *
     * @since 1.0.2
     */
    protected function __construct() { }

    /**
     * Get model instance.
     *
     * @since 1.0.2
     *
     * @return object → controller instance
     */
    public static function getInstance() {

        $model = get_called_class();

        if (!isset(self::$instance[$model])) { 

            self::$instance[$model] = new $model;
        }

        return self::$instance[$model];
    }

    /**
     * Prevents the object from being cloned.
     *
     * @since 1.0.2
     *
     * @throws ModelException → clone is not allowed
     */
    public function __clone() {

        $message = 'Clone is not allowed in';

        throw new ModelException($message . ': ' . __CLASS__, 800);
    }

    /**
     * Prevent unserializing.
     *
     * @since 1.0.2
     *
     * @return void
     */
    private function __wakeup() { }
}
