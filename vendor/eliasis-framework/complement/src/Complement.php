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

namespace Eliasis\Complement;

use Eliasis\Framework\App;
use Josantonius\Json\Json;
use Josantonius\File\File;
use Eliasis\Complement\Exception\ComplementException;

/**
 * Complement class.
 *
 * @since 1.0.9
 */
abstract class Complement
{

    use Traits\ComplementHandler,
        Traits\ComplementAction,
        Traits\ComplementImport,
        Traits\ComplementState,
        Traits\ComplementRequest,
        Traits\ComplementView;

    /**
     * Complement instances.
     *
     * @since 1.0.9
     *
     * @var array
     */
    protected static $instances;

    /**
     * Available complements.
     *
     * @since 1.0.9
     *
     * @var array
     */
    protected $complement = [];

    /**
     * Id of current complement called.
     *
     * @since 1.0.9
     *
     * @var array
     */
    protected static $id = 'unknown';

    /**
     * Complement type.
     *
     * @since 1.0.9
     *
     * @var string
     */
    private static $type = 'unknown';

    /**
     * Errors for file management.
     *
     * @since 1.0.9
     *
     * @var array
     */
    protected static $errors = [];

    /**
     * Get complement instance.
     *
     * @since 1.0.9
     *
     * @uses string App::$id                      → application ID
     * @uses string ComplementHandler::_getType() → get complement type
     *
     * @return object → complement instance
     */
    protected static function getInstance()
    {

        $type = self::_getType();

        $complement = get_called_class();

        if (!isset(self::$instances[App::$id][$type][self::$id])) {
            self::$instances[App::$id][$type][self::$id] = new $complement;
        }

        return self::$instances[App::$id][$type][self::$id];
    }

    /**
     * Load all complements found in the directory.
     *
     * @since 1.0.9
     *
     * @uses string App::DS                             → directory separator
     * @uses string App::COMPLEMENT()                   → complement path
     * @uses string ComplementRequest::requestHandler() → HTTP request handler
     * @uses string ComplementHandler::_getType()       → get complement type
     *
     * @return void
     */
    public static function run()
    {

        $complementType = self::_getType('strtoupper');

        $path = App::$complementType();

        if ($paths = File::getFilesFromDir($path)) {
            foreach ($paths as $path) {
                if (!$path->isDot() && $path->isDir()) {
                    $_path = rtrim($path->getPath(), App::DS) . App::DS;
                    
                    $slug = $path->getBasename();

                    $file = $_path . $slug . App::DS . $slug . '.jsond';

                    if (!File::exists($file)) {
                        continue;
                    }

                    self::load($file, $_path);
                }
            }
        }

        self::requestHandler(self::_getType('strtolower', false));
    }

    /**
     * Load complement configuration from json file and set settings.
     *
     * @since 1.0.9
     *
     * @param string $file → json file name
     * @param string $path → complement path
     *
     * @uses array Json::fileToArray() → convert json file to array
     *
     * @uses string ComplementHandler->_setComplement() → set complement
     *
     * @return void
     */
    public static function load($file, $path = false)
    {

        $complement = Json::fileToArray($file);

        $complement['config-file'] = $file;

        self::$id = isset($complement['id']) ? $complement['id'] : 'unknown';

        $that = self::getInstance();

        $that->_setComplement($complement, $path);
    }

    /**
     * Get components/plugins/modules/templates info.
     *
     * @since 1.0.9
     *
     * @param string $filter → complement category filter
     * @param string $sort   → PHP sorting function to complements sort
     *
     * @uses string ComplementHandler::_getType() → get complement type
     *
     * @uses string App::$id → application ID
     *
     * @return array $data → complements info
     */
    public static function getInfo($filter = 'all', $sort = 'asort')
    {

        $data = [];

        $type = self::_getType();

        $complementID = self::$id;

        $complements = array_keys(self::$instances[App::$id][$type]);

        foreach ($complements as $id) {
            self::$id = $id;

            $that = self::getInstance();

            $complement = $that->complement;

            if (!isset($complement['category'])) {
                continue;
            }

            $skip = ($filter != 'all' && $complement['category'] != $filter);

            if ($skip || $id == 'unknown' || !$complement) {
                continue;
            }

            if ($that->hasNewVersion() && $complement['state'] === 'active') {
                $complement['state'] = 'outdated';

                $that->setState('outdated');
            }

            $data[$complement['id']] = [

                'id'          => $complement['id'],
                'name'        => $complement['name'],
                'version'     => $complement['version'],
                'description' => $complement['description'],
                'state'       => $complement['state'],
                'category'    => $complement['category'],
                'path'        => $complement['path']['root'],
                'uri'         => $complement['uri'],
                'author'      => $complement['author'],
                'author-uri'  => $complement['author-uri'],
                'license'     => $complement['license'],
                'state'       => $complement['state'],
                'slug'        => $complement['slug'],
                'image'       => $img = $complement['image'],
                'image_style' => "background: url(\"$img\") center / cover;",
            ];
        }

        self::$id = $id;

        $that->complement = $complement;

        $sorting = '|asort|arsort|krsort|ksort|natsort|rsort|shuffle|sort|';

        strpos($sorting, $sort) ? $sort($data) : asort($data);

        return $data;
    }

    /**
     * Set and get url script and vue file.
     *
     * @since 1.0.9
     *
     * @param string $pathUrl → url where JS files will be created & loaded
     *
     * @uses string ComplementView::_setFile() → set script files
     *
     * @return array → urls of the scripts
     */
    public static function script($pathUrl = null)
    {

        $that = self::getInstance();

        return $that->_setFile('eliasis-complement-min', 'script', $pathUrl);
    }

    /**
     * Set and get url style.
     *
     * @since 1.0.9
     *
     * @param string $pathUrl → url where CSS files will be created & loaded
     *
     * @uses ComplementView::_setFile() → set style files
     *
     * @return array → urls of the styles
     */
    public static function style($pathUrl = null)
    {

        $that = self::getInstance();

        return $that->_setFile('eliasis-complement-min', 'style', $pathUrl);
    }

    /**
     * Check if complement exists.
     *
     * @since 1.0.9
     *
     * @param string $complementID → complement id
     *
     * @uses string App::$id                      → application ID
     * @uses string ComplementHandler::_getType() → get complement type
     *
     * @return boolean
     */
    public static function exists($complementID)
    {

        $type = self::_getType();

        return array_key_exists(

            $complementID,
            self::$instances[App::$id][$type]
        );
    }

    /**
     * Get library path.
     *
     * @since 1.0.9
     *
     * @uses string App::DS → directory separator
     *
     * @return string → library path
     */
    public static function getLibraryPath()
    {

        return rtrim(dirname(dirname(__FILE__)), App::DS) . App::DS;
    }

    /**
     * Get library version.
     *
     * @since 1.0.9
     *
     * @uses array Json::fileToArray() → convert json file to array
     *
     * @return string
     */
    public static function getLibraryVersion()
    {

        $path = self::getLibraryPath();

        $composer = Json::fileToArray($path . 'composer.json');

        return isset($composer['version']) ? $composer['version'] : '1.0.9';
    }

    /**
     * Receives the name of the complement to execute: Complement::Name();
     *
     * @since 1.0.9
     *
     * @param string $index  → complement name
     * @param array  $params → params
     *
     * @uses string App::$id                      → application ID
     * @uses string ComplementHandler::_getType() → get complement type
     *
     * @throws ComplementException → complement not found
     *
     * @return object
     */
    public static function __callstatic($index, $params = false)
    {

        $type = self::_getType();

        if (!array_key_exists($index, self::$instances[App::$id][$type])) {
            $msg = self::_getType('ucfirst', false) . ' not found';

            throw new ComplementException($msg . ': ' . $index . '.', 817);
        }

        self::$id = $index;

        $that = self::getInstance();

        if (!$params) {
            return $that;
        }

        $method = (isset($params[0])) ? $params[0] : '';
        $args   = (isset($params[1])) ? $params[1] : 0;

        if (method_exists($that, $method)) {
            return call_user_func_array([$that, $method], [$args]);
        }
    }

    /**
     * Get complements view.
     *
     * @since 1.0.9
     *
     * @param string $filter   → complements category to display
     * @param array  $external → urls of the external optional complements
     * @param string $sort     → PHP sorting function to complements sort
     *
     * @uses void ComplementView::_renderizate() → convert json file to array
     *
     * @return void
     */
    public static function render($filter = 'all', $external = 0, $sort = 'asort')
    {

        $that = self::getInstance();

        $that->_renderizate($filter, $external, $sort);
    }
}
