<?php
/**
 * PHP library for adding addition of complements for Eliasis Framework.
 *
 * @author     Josantonius - hello@josantonius.com
 * @copyright  Copyright (c) 2017
 * @license    https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link       https://github.com/Eliasis-Framework/Complement
 * @since      1.0.9
 */

namespace Eliasis\Complement\Traits;

use Eliasis\Framework\App;
use Josantonius\Json\Json;

/**
 * Complement requests handler class.
 *
 * @since 1.0.9
 */
trait ComplementRequest
{
                                                          
    /**
     * HTTP request handler.
     *
     * @since 1.0.9
     *
     * @param string $complementType → complement type
     *
     * @uses string App::id() → set application id
     *
     * @return void
     */
    public static function requestHandler($complementType)
    {

        if (!isset(
            $_GET['vue'],
            $_GET['app'],
            $_GET['external'],
            $_GET['request'],
            $_GET['complement']
        )) {
            return;
        }

        if ($_GET['complement'] !== $complementType) {
            return;
        }

        App::id(filter_var($_GET['app'], FILTER_SANITIZE_STRING));

        self::_loadExternalComplements();
        
        switch ($_GET['request']) {
            case 'load-complements':
                self::_complementsLoadRequest();

                break;

            case 'change-state':
                self::_changeStateRequest();

                break;

            case 'install':
                self::_installRequest();

                break;

            case 'uninstall':
                self::_uninstallRequest();

                break;

            default:
                self::$errors[] = [

                    'message' => 'Unknown request: ' . $_GET['request']
                ];

                break;
        }

        die;
    }
                                                                   
    /**
     * Load external complements.
     *
     * @since 1.0.9
     *
     * @uses array  Complement->$instances → complement instances
     * @uses string App::$id               → application id
     * @uses void   Complement::load()     → load complement configuration
     * @uses array  Complement::$errors    → complement errors
     *
     * @return void
     */
    private static function _loadExternalComplements()
    {

        $complement = self::_getType();
        
        $external = json_decode($_GET['external'], true);

        $complements = array_keys(self::$instances[App::$id][$complement]);

        foreach ($external as $complement => $url) {
            if (!in_array($complement, $complements)) {
                if ($url = filter_var($url, FILTER_VALIDATE_URL)) {
                    self::load($url);
                } else {
                    self::$errors[] = [

                        'message' => 'A valid url was not received: ' . $url
                    ];
                }
            }

            self::$complement()->set('config-url', $url);
        }
    }

    /**
     * Complements load request.
     *
     * @since 1.0.9
     *
     * @uses array Complement::getInfo() → get complements info
     * @uses array Complement::$errors   → complement errors
     *
     * @return void
     */
    private static function _complementsLoadRequest()
    {

        $complements = [];

        if (isset($_GET['filter'], $_GET['sort'])) {
            $sort   = filter_var($_GET['sort'], FILTER_SANITIZE_STRING);
            $filter = filter_var($_GET['filter'], FILTER_SANITIZE_STRING);

            $complements = self::getInfo($filter, $sort);
        } else {
            $msg = 'The "filter" or "sort" parameter wasn\'t received.';

            self::$errors[] = ['message' => $msg];
        }

        echo json_encode([

            'complements' => $complements,
            'errors'      => self::$errors
        ]);
    }
                                                          
    /**
     * Change state request.
     *
     * @since 1.0.9
     *
     * @uses object Complement::getInstance()      → get complement instance
     * @uses string ComplementState->changeState() → change complement state
     * @uses array  Complement::$errors            → complement errors
     *
     * @return void
     */
    private static function _changeStateRequest()
    {

        $state = false;

        if (isset($_GET['id'])) {
            self::$id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);

            $that = self::getInstance();

            $state = $that->changeState();
        } else {
            self::$errors[] = [

                'message' => 'The "id" parameter wasn\'t received.'
            ];
        }

        echo json_encode([

            'state'   => $state,
            'errors'  => self::$errors
        ]);
    }
                                                                     
    /**
     * Install request.
     *
     * @since 1.0.9
     *
     * @uses object Complement::getInstance()   → get complement instance
     * @uses string ComplementImport->install() → install complement
     * @uses array  Complement::$errors         → complement errors
     *
     * @return void
     */
    private static function _installRequest()
    {

        $state = false;

        if (isset($_GET['id'])) {
            self::$id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);

            $that = self::getInstance();

            $state = $that->install();
        } else {
            self::$errors[] = [

                'message' => 'The "id" parameter wasn\'t received.'
            ];
        }

        $config = Json::fileToArray($that->complement['config-file']);

        echo json_encode([

            'state'   => $state,
            'version' => $config['version'],
            'errors'  => self::$errors
        ]);
    }

    /**
     * Uninstall request.
     *
     * @since 1.0.9
     *
     * @uses object Complement::getInstance()  → get complement instance
     * @uses string ComplementImport->remove() → remove complement
     * @uses array Complement::$errors         → complement errors
     *
     * @return void
     */
    private static function _uninstallRequest()
    {

        $state = false;

        if (isset($_GET['id'])) {
            self::$id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);

            $that = self::getInstance();

            $state = $that->remove();
        } else {
            self::$errors[] = [

                'message' => 'The "id" parameter wasn\'t received.'
            ];
        }

        echo json_encode([

            'state'  => $state,
            'errors' => self::$errors
        ]);
    }
}
