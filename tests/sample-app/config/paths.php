<?php
/**
 * Eliasis PHP Framework application.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2017 - 2018 (c) Josantonius - Eliasis Framework
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/eliasis-framework/eliasis
 * @since     1.1.2
 */
use Eliasis\Framework\App;

$DS = App::DS;
$rootPath = App::ROOT();

return [
    'path' => [
        'layout' => $rootPath . 'src' . $DS . 'template' . $DS . 'layout' . $DS,
        'page' => $rootPath . 'src' . $DS . 'template' . $DS . 'page' . $DS,
    ],
];
