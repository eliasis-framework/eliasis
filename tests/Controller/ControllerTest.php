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
namespace Eliasis\Framework\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Error;
use App\Controller\Home;

/**
 * Tests class for Controller class.
 */
final class ControllerTest extends TestCase
{
    /**
     * Home controller instance.
     *
     * @var object
     */
    protected $home;

    /**
     * Set up.
     */
    public function setUp()
    {
        parent::setUp();
        $this->home = Home::getInstance();
    }

    /**
     * Force error when instantiate a abstract class.
     *
     * @expectedException Error
     */
    public function testErrorWhenInstantiateAbstractClass()
    {
        new Controller;
    }

    /**
     * Get controller instance.
     */
    public function testGetControllerInstance()
    {
        $this->home = Home::getInstance();

        $this->assertInstanceOf('App\Controller\Home', $this->home);
    }

    /**
     * Force error when get model intance directly from the controller.
     *
     * @depends testGetControllerInstance
     *
     * @expectedException Error
     */
    public function testErrorWhenGetModelFromControllerInstance()
    {
        $home = $this->home;

        $this->assertInstanceOf('App\Model\Home', $home->model);
    }

    /**
     * Get model intance from controller method.
     *
     * @depends testGetControllerInstance
     */
    public function testGetModelFromControllerMethod()
    {
        $home = $this->home;

        $this->assertInstanceOf('App\Model\Home', $home->getModel());
    }

    /**
     * Force error when get view intance directly from the controller.
     *
     * @depends testGetControllerInstance
     *
     * @expectedException Error
     */
    public function testErrorWhenGetViewFromControllerInstance()
    {
        $home = $this->home;

        $this->assertInstanceOf('App\Model\Home', $home->view);
    }

    /**
     * Get view intance from controller method.
     *
     * @depends testGetControllerInstance
     */
    public function testGetViewFromControllerMethod()
    {
        $home = $this->home;

        $this->assertInstanceOf('Eliasis\Framework\View', $home->getView());
    }
}
