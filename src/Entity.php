<?php
/**
 * Eliasis PHP Framework.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2017 - 2019 (c) Josantonius - Eliasis Framework
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/eliasis-framework/eliasis
 * @since     1.1.5
 */
namespace Eliasis\Framework;

use Josantonius\Entity\Entity as EntityMannager;

/**
 * Entity class.
 */
abstract class Entity
{
    /**
     * Entity instances.
     *
     * @var object
     */
    private static $_instances;

    /**
     * Entity name.
     *
     * @var string
     */
    private $_name;

    /**
     * Entity rules.
     *
     * @var object
     */
    private $_rules = [];

    /**
     * Get entity instance.
     */
    public static function getInstance() : object
    {
        $class = get_called_class();

        if (isset(self::$_instances[$class])) {
            return self::$_instances[$class];
        }

        self::$_instances[$class] = new $class();

        $ReflectionClass = self::_getReflectionClass($class);

        self::_setName($class, $ReflectionClass);
        self::_setRuleS($class, $ReflectionClass);

        return self::$_instances[$class];
    }

    /**
     * Get entity name.
     */
    public function getEntityName() : ?string
    {
        return $this->_name;
    }

    /**
     * Get entity rules.
     */
    public function getEntityRules() : array
    {
        return $this->_rules ?? [];
    }

    /**
     * Get attribute rules.
     */
    public function getAttrRules(string $attributeName) : array
    {
        return $this->_rules[$attributeName] ?? [];
    }

    /**
     * Get attribute data type.
     */
    public function getAttrDataType(string $attributeName) : ?string
    {
        return $this->_rules[$attributeName]['type'] ?? null;
    }

    /**
     * Reset all attributes.
     */
    public function reset() : void
    {
        $p = new \ReflectionObject($this);

        foreach ($p->getProperties() as $key => $property) {
            $property->setAccessible(true);
            $method = 'set' . ucfirst($property->name);
            $this->$method(null);
            $property->setAccessible(false);
        }
    }

    /**
     * Get Reflection class.
     */
    private static function _getReflectionClass(string $class) : \ReflectionClass
    {
        return new \ReflectionClass(self::$_instances[$class]);
        ;
    }

    /**
     * Set entity name.
     */
    private static function _setName(string $class, \ReflectionClass $ReflectionClass) : void
    {
        self::$_instances[$class]->_name = $ReflectionClass->getShortName();
    }

    /**
     * Set entity rules.
     */
    private static function _setRules(string $class, \ReflectionClass $ReflectionClass) : void
    {
        if (class_exists('Josantonius\Entity\Entity')) {
            $rules = EntityMannager::getColumnRules($ReflectionClass);
            self::$_instances[$class]->_rules = $rules;
        }
    }
}
