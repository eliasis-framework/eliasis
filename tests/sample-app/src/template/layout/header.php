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

use Josantonius\Hook\Hook;

use Eliasis\Framework\View;

echo View::getOption('test');
?>
<!DOCTYPE html>

<html lang="es">

    <head>
        <!-- Site meta -->
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta charset="utf-8">
        <?php Hook::doAction('meta') ?> 
        <!-- Title -->
        <title>Eliasis PHP Framework</title>
        <!-- CSS -->
        <?php Hook::doAction('css') ?>
    </head>
