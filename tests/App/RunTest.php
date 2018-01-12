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
 * Tests class for App::run() method.
 */
final class RunTest extends TestCase
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
     * Run application.
     */
    public function testRunApplication()
    {
        $app = $this->app;

        $this->assertTrue(
            $app::run($this->root)
        );
    }

    /**
     * Run application with specific type.
     */
    public function testRunApplicationWithSpecificType()
    {
        $app = $this->app;

        $this->assertTrue(
            $app::run($this->root, 'app')
        );
    }

    /**
     * Run application with specific id.
     */
    public function testRunApplicationWithSpecificID()
    {
        $app = $this->app;

        $this->assertTrue(
            $app::run($this->root, 'app', 'FirstApplication')
        );
    }

    /**
     * Run multiple applications with specific id.
     */
    public function testRunMultipleApplicationsWithSpecificID()
    {
        $this->app = new App;

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
}
