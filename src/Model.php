<?php
/**
 * Eliasis PHP Framework.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2017 - 2018 (c) Josantonius - Eliasis Framework
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Eliasis-Framework/Eliasis
 * @since     1.0.0
 */
namespace Eliasis\Framework;

use Eliasis\Framework\Exception\ModelException;

/**
 * Model class.
 */
abstract class Model
{
    /**
     * Model instances.
     *
     * @since 1.0.2
     *
     * @var object
     */
    protected static $instance;

    /**
     * Database instance.
     *
     * @since 1.0.6
     *
     * @var object
     */
    protected $db;

    /**
     * Prevent creating a new model instance.
     *
     * @since 1.0.2
     */
    protected function __construct()
    {
    }

    /**
     * Prevents the object from being cloned.
     *
     * @since 1.0.2
     *
     * @throws ModelException → clone is not allowed
     */
    public function __clone()
    {
        throw new ModelException('Clone is not allowed in: ' . __CLASS__);
    }

    /**
     * Prevent unserializing.
     *
     * @since 1.0.2
     */
    private function __wakeup()
    {
    }

    /**
     * Get model instance.
     *
     * @since 1.0.2
     *
     * @return object → controller instance
     */
    public static function getInstance()
    {
        $model = get_called_class();

        if (! isset(self::$instance[$model])) {
            self::$instance[$model] = new $model;

            if (is_null(self::$instance[$model]->db)) {
                self::$instance[$model]->getDatabaseInstance();
            }
        }

        return self::$instance[$model];
    }

    /**
     * Get Database connection.
     *
     * This method will only be used if the Database class exists.
     *
     * @since 1.0.6
     *
     * @uses \Josantonius\Database\Database class
     *
     * @link https://github.com/Josantonius/PHP-Database
     *
     * @return object → controller instance
     */
    private function getDatabaseInstance()
    {
        if (class_exists($Database = 'Josantonius\\Database\\Database')) {
            $config = App::db();
            $id = (is_array($config)) ? array_keys($config)[0] : 'app';
            $this->db = $Database::getConnection($id);
        }
    }
}
