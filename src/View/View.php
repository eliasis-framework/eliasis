<?php
/**
 * Eliasis PHP Framework
 *
 * @author     Josantonius - hola@josantonius.com
 * @author     Daveismyname - dave@daveismyname.com
 * @copyright  Copyright (c) 2017
 * @license    https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link       https://github.com/Eliasis-Framework/Eliasis
 * @since      1.0.0
 */

namespace Eliasis\View;

class View {

    /**
     * View instance.
     *
     * @since 1.0.0
     *
     * @var object
     */
    protected static $instance;

    /**
     * HTTP headers.
     *
     * @since 1.0.0
     *
     * @var array
     */
    private static $headers = [];

    /**
     * View content.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public static $data = null;

    /**
     * Get controller instance.
     *
     * @since 1.0.0
     *
     * @return object → controller instance
     */
    public static function getInstance() {

        NULL === self::$instance and self::$instance = new self;

        return static::$instance;
    }

    /**
     * Render screen view.
     *
     * @since 1.0.0
     *
     * @param string $file → view name
     * @param array  $data → view content
     */
    public function renderizate($file, $data = '') {

        if (is_array($data)) {

            self::$data = $data;
        }

        $data = self::$data;

        $path = $file . '.php';

        require $path;
    }

    /**
     * Add HTTP header to headers array.
     *
     * @since 1.0.0
     *
     * @param  string $header → HTTP header text
     */
    public function addHeader($header) {

        self::$headers[] = $header;
    }

    /**
     * Add an array with headers to the view.
     *
     * @since 1.0.0
     *
     * @param array $headers
     */
    public function addHeaders(array $headers = array()) {

        self::$headers = array_merge(self::$headers, $headers);
    }
    
    /**
     * Send headers
     *
     * @since 1.0.0
     */
    public static function sendHeaders() {

        if (!headers_sent()) {

            foreach (self::$headers as $header) {

                header($header, true);
            }
        }
    }
}
