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
 * Tests class for App::getCurrentID() and App::setCurrentID() method.
 */
final class CurrentIdTest extends TestCase
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

        $this->assertTrue(
            $app::run($this->root, 'app', 'MyApplicationThree')
        );
    }

    /**
     * Define the current application ID.
     *
     * @depends testRunMultipleApplications
     */
    public function testSetCurrentID()
    {
        $app = $this->app;
        
        $this->assertTrue(
            $app::setCurrentID('MyApplicationOne')
        );

        $this->assertTrue(
            $app::setCurrentID('MyApplicationThree')
        );

        $this->assertTrue(
            $app::setCurrentID('MyApplicationTwo')
        );
    }

    /**
     * Define the current application ID when the application doesn't exist.
     *
     * @depends testRunMultipleApplications
     */
    public function testSetNonexistentCurrentID()
    {
        $app = $this->app;

        $this->assertFalse(
            $app::setCurrentID('Unknown')
        );
    }

    /**
     * Get the current application ID.
     *
     * @depends testRunMultipleApplications
     */
    public function testGetCurrentID()
    {
        $app = $this->app;

        $this->assertEquals(
            'MyApplicationTwo',
            $app::getCurrentID()
        );
    }
}
