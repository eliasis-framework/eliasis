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
namespace Eliasis\Framework\View;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Error;
use Eliasis\Framework\View;
use Eliasis\Framework\App;

/**
 * Tests class for View class.
 */
final class ViewTest extends TestCase
{
    /**
     * View instance.
     *
     * @var object
     */
    protected $view;

    /**
     * App instance.
     *
     * @var object
     */
    protected $app;

    /**
     * Set up.
     */
    public function setUp()
    {
        parent::setUp();

        $this->view = new View;

        $this->app = new App;

        $this->ROOT = dirname(__DIR__) . $_SERVER['REQUEST_URI'];
    }

    /**
     * Check View instance.
     */
    public function testIfInstanceOf()
    {
        $this->assertInstanceOf('Eliasis\Framework\View', $this->view);
    }

    /**
     * Run application.
     */
    public function testRunApplication()
    {
        $app = $this->app;

        $this->assertTrue(
            $app::run($this->ROOT)
        );
    }

    /**
     * Get View instance.
     */
    public function testGetViewInstance()
    {
        $this->assertInstanceOf('Eliasis\Framework\View', View::getInstance());
    }

    /**
     * Add header.
     */
    public function testAddHeader()
    {
        $view = $this->view;

        $this->assertTrue(
            $view::addHeader('HTTP/1.0 404 Not Found')
        );
    }

    /**
     * Add headers.
     */
    public function testAddHeaders()
    {
        $view = $this->view;

        $this->assertTrue(
            $view::addHeaders([
                'WWW-Authenticate: Negotiate',
                'HTTP/1.0 404 Not Found'
            ])
        );
    }

    /**
     * Send headers.
     *
     * @runInSeparateProcess
     */
    public function testSendHeaders()
    {
        $view = $this->view;

        $this->assertTrue(
            $view::sendHeaders()
        );
    }

    /**
     * Force error to send headers when they have already been sent.
     */
    public function testSendHeadersWhenAlreadyBeenSent()
    {
        $view = $this->view;

        $this->assertFalse(
            $view::sendHeaders()
        );
    }

    /**
     * Renderizate templates.
     */
    public function testRenderizate()
    {
        $app = $this->app;

        $page = $app::getOption('path', 'page');

        $layout = $app::getOption('path', 'layout');

        $this->assertFileIsReadable($layout . 'header.php');

        ob_start();

        $this->assertTrue(
            $this->view->renderizate(
                $layout,
                'header',
                ['test' => 'Hello from header template.']
            )
        );

        $header = ob_get_contents();

        $this->assertFileIsReadable($page . 'home.php');

        $this->assertTrue(
            $this->view->renderizate(
                $page,
                'home',
                ['test' => 'Hello from home template.']
            )
        );

        $home = ob_get_contents();

        $this->assertFileIsReadable($layout . 'footer.php');

        $this->assertTrue(
            $this->view->renderizate(
                $layout,
                'footer',
                ['test' => 'Hello from footer template.']
            )
        );

        $footer = ob_get_contents();

        ob_end_clean();

        $this->assertContains(
            '<html lang="es">',
            $header
        );

        $this->assertContains(
            'Hello from header template.',
            $header
        );

        $this->assertContains(
            '<body>',
            $home
        );
        
        $this->assertContains(
            'Hello from home template.',
            $home
        );

        $this->assertContains(
            '<footer>',
            $footer
        );

        $this->assertContains(
            'Hello from footer template.',
            $footer
        );
    }
}
