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

/**
 * Tests class for App::run() method.
 */
final class WordPressTest extends \WP_UnitTestCase
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
            $app::run($this->root, 'wordpress-plugin')
        );
    }

    /**
     * Run application with specific id.
     */
    public function testRunApplicationWithSpecificID()
    {
        $app = $this->app;

        $this->assertTrue(
            $app::run($this->root, 'wordpress-plugin', 'FirstApplication')
        );
    }

    /**
     * Run multiple applications with specific id.
     */
    public function testRunMultipleApplicationsWithSpecificID()
    {
        $app = $this->app;

        $this->assertTrue(
            $app::run($this->root, 'wordpress-plugin', 'MyApplicationOne')
        );

        $this->assertTrue(
            $app::run($this->root, 'wordpress-plugin', 'MyApplicationTwo')
        );

        $this->assertTrue(
            $app::run($this->root, 'wordpress-plugin', 'MyApplicationThree')
        );
    }
}
