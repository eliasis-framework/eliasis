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
namespace Eliasis\Framework\Exception;

/**
 * Exception class.
 *
 * You can use an exception and error handler with this library.
 *
 * @link https://github.com/Josantonius/PHP-ErrorHandler
 */
class ControllerException extends \Exception
{
    /**
     * Exception handler.
     *
     * @param string $msg    → message error (Optional)
     * @param int    $status → HTTP response status code (Optional)
     */
    public function __construct($msg = '', $status = 0)
    {
        $this->message = $msg;
        $this->statusCode = $status;
    }
}
