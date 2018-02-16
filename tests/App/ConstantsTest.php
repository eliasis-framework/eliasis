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
final class ConstantsTest extends TestCase
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
     * Root url.
     *
     * @var string
     */
    protected $root_url;

    /**
     * Core path.
     *
     * @var object
     */
    protected $core;

    /**
     * Set up.
     */
    public function setUp()
    {
        parent::setUp();

        $this->app = new App;
        
        $this->root = $_SERVER['DOCUMENT_ROOT'];

        $this->root_url = 'https://' . $_SERVER['SERVER_NAME'] . '/';

        $this->core = dirname(dirname(dirname(dirname(__DIR__)))) . '/';

        $app = $this->app;

        $app::run($this->root);
    }

    /**
     * Check if it is an instance of App.
     */
    public function testIsInstanceOf()
    {
        $this->assertInstanceOf('Eliasis\Framework\App', $this->app);
    }

    /**
     * Validate that application base paths have been generated.
     */
    public function testCheckApplicationBasepathConstants()
    {
        $app = $this->app;

        $this->assertEquals(
            $this->root,
            $app::ROOT()
        );
        
        $this->assertEquals(
            $this->core,
            $app::CORE()
        );
        
        $this->assertEquals(
            $this->root . 'public/',
            $app::PUBLIC()
        );
        
        $this->assertEquals(
            $this->root . 'templates/',
            $app::TEMPLATES()
        );
        
        $this->assertEquals(
            $this->root . 'modules/',
            $app::MODULES()
        );
        
        $this->assertEquals(
            $this->root . 'plugins/',
            $app::PLUGINS()
        );
        
        $this->assertEquals(
            $this->root . 'components/',
            $app::COMPONENTS()
        );
    }

    /**
     * Validate that application base urls have been generated.
     */
    public function testCheckApplicationBaseUrlsConstants()
    {
        $app = $this->app;

        $this->assertEquals(
            $this->root_url . 'public/',
            $app::PUBLIC_URL()
        );
        
        $this->assertEquals(
            $this->root_url . 'templates/',
            $app::TEMPLATES_URL()
        );
        
        $this->assertEquals(
            $this->root_url . 'modules/',
            $app::MODULES_URL()
        );
        
        $this->assertEquals(
            $this->root_url . 'plugins/',
            $app::PLUGINS_URL()
        );
        
        $this->assertEquals(
            $this->root_url . 'components/',
            $app::COMPONENTS_URL()
        );
    }
}
