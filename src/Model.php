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
     * Change database connection.
     *
     * @since 1.1.3
     *
     * @param string $id → database connection ID
     */
    public function changeDatabaseConnection($id)
    {
        $this->getDatabaseInstance($id);
    }

    /**
     * Get Database connection.
     *
     * This method will only be run if the Database class exists.
     *
     * @since 1.0.6
     *
     * @uses \Josantonius\Database\Database
     *
     * @link https://github.com/Josantonius/PHP-Database
     *
     * @return object → Database instance
     */
    private function getDatabaseInstance($id = null)
    {
        $config = App::getOption('db');
        $Database = 'Josantonius\\Database\\Database';

        if (! class_exists($Database) || ! is_array($config)) {
            return;
        }

        $id = $id ?: array_keys($config)[0];

        $required = ['provider', 'host', 'user', 'name', 'password', 'settings'];

        if (! array_diff($required, array_keys($config[$id]))) {
            $this->db = $Database::getConnection(
                $id,
                $config[$id]['provider'],
                $config[$id]['host'],
                $config[$id]['user'],
                $config[$id]['name'],
                $config[$id]['password'],
                $config[$id]['settings']
            );
        }
    }
}
