<?php
/**
 * Eliasis PHP Framework.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2017 - 2018 (c) Josantonius - Eliasis Framework
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/eliasis-framework/eliasis
 * @since     1.0.0
 */
namespace Eliasis\Framework;

/**
 * View class.
 */
class View
{
    /**
     * View content.
     *
     * @var array
     */
    public static $data = null;

    /**
     * View instance.
     *
     * @var array
     */
    protected static $instance;

    /**
     * HTTP headers.
     *
     * @var array
     */
    private static $headers = [];

    /**
     * Get View instance.
     *
     * @return object → view instance
     */
    public static function getInstance()
    {
        null === self::$instance and self::$instance = new self;

        return static::$instance;
    }

    /**
     * Render screen view.
     *
     * @param string $path → filepath
     * @param string $file → filename
     * @param array  $data → options for view
     *
     * @return bool true
     */
    public function renderizate($path, $file, $data = null)
    {
        $file = $path . $file . '.php';

        if ($data) {
            self::$data[md5($file)] = $data;
        }

        require_once $file;

        return true;
    }

    /**
     * Get options saved.
     *
     * @since 1.0.9
     *
     * @param array  $params → parameters
     * @param string $file   → filepath
     *
     * @return mixed
     */
    public static function getOption(...$params)
    {
        $trace = debug_backtrace(2, 1);

        $id = (isset($trace[0]['file'])) ? md5($trace[0]['file']) : 0;

        $key = array_shift($params);

        $col[] = isset(self::$data[$id][$key]) ? self::$data[$id][$key] : 0;

        if (! count($params)) {
            return ($col[0]) ? $col[0] : self::$data[$id];
        }

        foreach ($params as $param) {
            $col = array_column($col, $param);
        }

        return (isset($col[0])) ? $col[0] : '';
    }

    /**
     * Add HTTP header to headers array.
     *
     * @param string $header → HTTP header text
     *
     * @return bool true
     */
    public static function addHeader($header)
    {
        self::$headers[] = $header;

        return true;
    }

    /**
     * Add an array with headers to the view.
     *
     * @param array $headers
     *
     * @return bool true
     */
    public static function addHeaders($headers = [])
    {
        self::$headers = array_merge(self::$headers, $headers);

        return true;
    }

    /**
     * Send headers.
     *
     * @return bool
     */
    public static function sendHeaders()
    {
        if (! headers_sent()) {
            foreach (self::$headers as $header) {
                header($header, true);
            }

            return true;
        }

        return false;
    }
}
