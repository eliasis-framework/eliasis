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
use Josantonius\Hook\Hook;
use Eliasis\Complement\Type\Plugin\Plugin;
use Eliasis\Complement\Type\Component\Component;
use Eliasis\Complement\Type\Module\Module;
use Eliasis\Complement\Type\Template\Template;
use PHPUnit\Framework\TestCase;

/**
 * Tests class for check load external libraries.
 */
final class ExtenalLibrariesTest extends TestCase
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
     * Check if set IP library.
     */
    public function testCheckIfSetIpLibrary()
    {
        $app = $this->app;

        $this->assertEquals(
            $_SERVER['REMOTE_ADDR'],
            $app::IP()
        );
    }

    /**
     * Validate if hooks were added.
     */
    public function testCheckIfHooksWereAdded()
    {
        $this->assertTrue(
            Hook::isAction('css')
        );

        $this->assertTrue(
            Hook::isAction('js')
        );

        ob_start();

        Hook::doAction('js');

        $js = ob_get_contents();

        Hook::doAction('css');

        $css = ob_get_contents();

        ob_end_clean();

        $this->assertContains('<script></script>', $js);

        $this->assertContains('<style></style>', $css);
    }

    /**
     * Validate if complements were loaded.
     */
    public function testCheckIfComplementsWereLoaded()
    {
        $this->assertTrue(
            Module::exists('SampleModule')
        );

        $this->assertTrue(
            Plugin::exists('SamplePlugin')
        );

        $this->assertTrue(
            Component::exists('SampleComponent')
        );

        $this->assertTrue(
            Template::exists('SampleTemplate')
        );
    }

    /**
     * Validate if routes were added.
     *
     * Simulate https://josantonius.com/my-route/.
     *
     * Response from 'App\Controller\Home->routes()' method.
     *
     * @runInSeparateProcess
     */
    public function testCheckIfRoutesWereAdded()
    {
        $app = $this->app;

        $_SERVER['REQUEST_URI'] = '/my-route/';

        ob_start();

        $app::run($this->root);

        $routeContent = ob_get_contents();

        ob_end_clean();

        $this->assertContains('The routes were loaded correctly', $routeContent);
    }
}
