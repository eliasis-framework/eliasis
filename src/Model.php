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

        $instance = new $model();

        if (is_null($instance->db)) {
            $instance->db = self::getDatabaseInstance();
        }

        return $instance;
    }

    /**
     * Change database connection.
     *
     * @since 1.1.5
     *
     * @param string $id → database connection ID
     */
    public function setDatabaseConnection($id)
    {
        $this->db = $this->getDatabaseInstance($id);
    }

    /**
     * Get Database connection.
     *
     * This method will only be run if the Database class exists.
     *
     * @since 1.0.6
     *
     * @param string $id → database connection ID
     *
     * @uses \Josantonius\Database\Database
     *
     * @link https://github.com/Josantonius/PHP-Database
     *
     * @return object → Database instance
     */
    private static function getDatabaseInstance($id = 'app')
    {
        $Database = 'Josantonius\\Database\\Database';

        $db = App::getOption('db', $id) ?: [];

        if (!class_exists($Database) || count($db) < 6) {
            return null;
        }

        return $Database::getConnection(
            $id,
            isset($db['provider']) ? $db['provider'] : '',
            isset($db['host']) ? $db['host'] : '',
            isset($db['user']) ? $db['user'] : '',
            isset($db['name']) ? $db['name'] : '',
            isset($db['password']) ? $db['password'] : '',
            isset($db['settings']) ? $db['settings'] : []
        );
    }
}
