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
 * Tests class for App::getOption() and App::setOption() method.
 */
final class OptionsTest extends TestCase
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
     * Set string option.
     *
     * @depends testRunMultipleApplications
     */
    public function testSetStringOption()
    {
        $app = $this->app;

        $string = 'test-value';

        $this->assertEquals(
            $string,
            $app::setOption('test', $string)
        );
    }

    /**
     * Set array option.
     *
     * @depends testRunMultipleApplications
     */
    public function testSetArrayOption()
    {
        $app = $this->app;

        $array = ['array' => 'test-value'];

        $this->assertEquals(
            $array,
            $app::setOption('test', $array)
        );
    }

    /**
     * Set boolean option.
     *
     * @depends testRunMultipleApplications
     */
    public function testSetBooleanOption()
    {
        $app = $this->app;

        $value = true;

        $this->assertEquals(
            $value,
            $app::setOption('test', $value)
        );
    }

    /**
     * Set int option.
     *
     * @depends testRunMultipleApplications
     */
    public function testSetIntOption()
    {
        $app = $this->app;

        $value = 8;

        $this->assertEquals(
            $value,
            $app::setOption('test', $value)
        );
    }

    /**
     * Set options from multiple applications.
     *
     * @depends testRunMultipleApplications
     */
    public function testSetOptionsFromMultipleApplications()
    {
        $app = $this->app;

        $value = 'Hello from MyApplicationOne application';

        $this->assertEquals(
            $value,
            $app::MyApplicationOne()->setOption('test', $value)
        );

        $value = 'Hello from MyApplicationTwo application';

        $this->assertEquals(
            $value,
            $app::MyApplicationTwo()->setOption('test', $value)
        );

        $value = 'Hello from MyApplicationThree application';

        $this->assertEquals(
            $value,
            $app::MyApplicationThree()->setOption('test', $value)
        );
    }

    /**
     * Get default options from get method.
     *
     * FROM PATHS: "/Eliasis/config/" & "/Eliasis/tests/sample-app/config/".
     *
     * @depends testRunMultipleApplications
     */
    public function testGetDefaultOptionsFromGetMethod()
    {
        $app = $this->app;

        $this->assertEquals(
            'first-application',
            $app::getOption('slug')
        );

        $this->assertEquals(
            'App\\Controller\\',
            $app::getOption('namespaces', 'controller')
        );

        $this->assertEquals(
            'App\\Modules\\',
            $app::getOption('namespaces', 'modules')
        );

        $this->assertEquals(
            $this->root . 'src/template/layout/',
            $app::getOption('path', 'layout')
        );

        $this->assertEquals(
            $this->root . 'src/template/page/',
            $app::getOption('path', 'page')
        );
    }

    /**
     * Get default options from magic static method.
     *
     * FROM PATHS: "/Eliasis/config/" & "/Eliasis/tests/sample-app/config/".
     *
     * @depends testRunMultipleApplications
     */
    public function testGetDefaultOptionsFromMagicStaticMethod()
    {
        $app = $this->app;

        $this->assertEquals(
            'first-application',
            $app::slug()
        );

        $this->assertEquals(
            'App\\Controller\\',
            $app::namespaces('controller')
        );

        $this->assertEquals(
            'App\\Modules\\',
            $app::namespaces('modules')
        );

        $this->assertEquals(
            $this->root . 'src/template/layout/',
            $app::path('layout')
        );

        $this->assertEquals(
            $this->root . 'src/template/page/',
            $app::path('page')
        );
    }

    /**
     * Get options from multiple applications and get method.
     *
     * FROM PATHS: '/Eliasis/config/' & '/Eliasis/tests/sample-app/config/'.
     *
     * @depends testRunMultipleApplications
     */
    public function testGetOptionsFromMultipleApplicationsAndGetMethod()
    {
        $app = $this->app;

        $this->assertEquals(
            'Hello from MyApplicationOne application',
            $app::MyApplicationOne()->getOption('test')
        );

        $this->assertEquals(
            'Hello from MyApplicationTwo application',
            $app::MyApplicationTwo()->getOption('test')
        );

        $this->assertEquals(
            'Hello from MyApplicationThree application',
            $app::MyApplicationThree()->getOption('test')
        );
    }

    /**
     * Get options from multiple applications and magic static method.
     *
     * FROM PATHS: '/Eliasis/config/' & '/Eliasis/tests/sample-app/config/'.
     *
     * @depends testRunMultipleApplications
     */
    public function testGetOptionsFromMultipleApplicationsAndMagicStaticMethod()
    {
        $app = $this->app;

        $app::setCurrentID('MyApplicationOne');

        $this->assertEquals(
            'Hello from MyApplicationOne application',
            $app::getOption('test')
        );

        $app::setCurrentID('MyApplicationTwo');

        $this->assertEquals(
            'Hello from MyApplicationTwo application',
            $app::getOption('test')
        );

        $app::setCurrentID('MyApplicationThree');

        $this->assertEquals(
            'Hello from MyApplicationThree application',
            $app::getOption('test')
        );
    }
}
