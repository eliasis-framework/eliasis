<?php
/**
 * Tests for Eliasis PHP Framework.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2017 - 2018 (c) Josantonius - Eliasis Framework
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Eliasis-Framework/Eliasis
 * @since     1.1.2
 */
namespace Eliasis\Framework\Model;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Error;
use App\Model\Home;

/**
 * Tests class for Model class.
 */
final class ModelTest extends TestCase
{
    /**
     * Home model instance.
     *
     * @var object
     */
    protected $model;

    /**
     * Set up.
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Force error when instantiate a abstract class.
     *
     * @expectedException Error
     */
    public function testErrorWhenInstantiateAbstractClass()
    {
        new Model;
    }

    /**
     * Get model instance.
     */
    public function testGetModelInstance()
    {
        $this->model = Home::getInstance();

        $this->assertInstanceOf('App\Model\Home', $this->model);
    }

    /**
     * Check if set Database library.
     */
    public function testCheckIfSetDatabaseLibrary()
    {
        $this->assertTrue(
            class_exists('Josantonius\Database\Database')
        );
    }

    /**
     * Error to access database instance directly.
     *
     * @expectedException Error
     */
    public function testErrorToAccessDatabaseInstanceDirectly()
    {
        $model = Home::getInstance();

        $this->assertInstanceOf('Josantonius\Database\Database', $model->db);
    }
}
