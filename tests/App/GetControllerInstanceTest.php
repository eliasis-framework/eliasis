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
namespace Eliasis\Framework\App;

use Eliasis\Framework\App;
use PHPUnit\Framework\TestCase;

/**
 * Tests class for predefined constants.
 */
final class GetControllerInstanceTest extends TestCase
{
    /**
     * App instance.
     *
     * @var object
     */
    protected $app;

    /**
     * Root path.
     *
     * @var string
     */
    protected $root;

    /**
     * Set up.
     */
    public function setUp()
    {
        parent::setUp();

        $this->app = new App;
        
        $this->root = $_SERVER['DOCUMENT_ROOT'];
    }

    /**
     * Check if it is an instance of App.
     */
    public function testIsInstanceOf()
    {
        $this->assertInstanceOf('Eliasis\Framework\App', $this->app);
    }

    /**
     * Run multiple applications.
     */
    public function testRunMultipleApplications()
    {
        $app = $this->app;

        $this->assertTrue(
            $app::run($this->root, 'app', 'MyApplicationOne')
        );

        $this->assertTrue(
            $app::run($this->root, 'app', 'MyApplicationTwo')
        );
    }

    /**
     * Get controller instance from magic static method.
     */
    public function testGetControllerInstanceFromMagicStaticMethod()
    {
        $app = $this->app;

        $this->assertInstanceOf(
            'App\Controller\Home',
            $app::getControllerInstance('Home')
        );

        $this->assertInstanceOf(
            'App\Controller\Home',
            $app::getControllerInstance('Home', 'controller')
        );
    }

    /**
     * Get controller instance from specific application.
     */
    public function testGetControllerInstanceFromSpecificApplication()
    {
        $app = $this->app;

        $this->assertInstanceOf(
            'App\Controller\Home',
            $app::MyApplicationOne()->getControllerInstance('Home')
        );

        $this->assertInstanceOf(
            'App\Controller\Home',
            $app::MyApplicationTwo()->getControllerInstance('Home', 'controller')
        );
    }

    /**
     * Get unknown controller instance.
     */
    public function testGetUnknownControllerInstance()
    {
        $app = $this->app;

        $this->assertFalse(
            $app::getControllerInstance('Unknown')
        );

        $this->assertFalse(
            $app::MyApplicationOne()->getControllerInstance('Unknown')
        );
    }
}
