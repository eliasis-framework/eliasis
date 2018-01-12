<?php
/**
 * Eliasis PHP Framework application.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2017 - 2018 (c) Josantonius - Eliasis Framework
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Eliasis-Framework/Eliasis
 * @since     1.1.2
 */

require dirname(__DIR__) . '/lib/vendor/autoload.php';

use Eliasis\Framework\App;
use Josantonius\LoadTime\LoadTime;

LoadTime::start();

App::run(dirname(__DIR__));

/**
 * Show runtime.
 *
 * print_r('Executed in: ' . LoadTime::end() . ' seconds.');
 */
