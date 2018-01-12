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
use Eliasis\View\View;
use Josantonius\File\File;

/**
 * Complement view handler.
 *
 * @since 1.0.9
 */
trait ComplementView
{

    /**
     * Creating files (css/js/php) in custom locations.
     *
     * @since 1.0.9
     *
     * @param string $filename → file name
     * @param string $type     → script|style
     * @param string $pathUrl  → path url where file will be created
     *
     * @uses string App::DS                         → directory separator
     * @uses string App::get()                      → get option
     * @uses string Complement::getLibraryPath()    → get library path
     * @uses string Complement::getLibraryVersion() → get library version
     * @uses string ComplementImport::_createdir()  → create directory
     *
     * @return string → file url
     */
    private function _setFile($filename, $type, $pathUrl)
    {

        $DS = App::DS;

        $ext = ($type == 'script') ? 'js' : 'css';

        $documentRoot = $_SERVER["DOCUMENT_ROOT"];
        
        $url = $pathUrl ?: App::get('url', $ext);

        $url = empty($url) ? App::PUBLIC_URL() . $ext . '/' : $url;

        $version = str_replace(".", "-", self::getLibraryVersion());

        $path = rtrim($documentRoot.parse_url($url)['path'], $DS) . $DS;

        if (!file_exists($toPath = $path . "$filename-$version.$ext")) {
            File::createDir($path);

            $path = self::getLibraryPath();

            $from = $path.'src'.$DS.'public'.$DS.$ext.$DS."$filename.$ext";

            $file = file_get_contents($from);

            file_put_contents($toPath, $file);
        }

        return rtrim($url, '/') . '/' . "$filename-$version.$ext";
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
     * @uses string App::$id                      → application id
     * @uses string App::DS                       → directory separator
     * @uses string Complement::getLibraryPath()  → get library path
     * @uses string ComplementHandler::_getType() → complement type
     * @uses object View:getInstance()            → View instance
     * @uses void   View:renderizate()            → render view
     *
     * @return void
     */
    private function _renderizate($filter, $external, $sort)
    {

        $data = [

            'app'        => App::$id,
            'complement' => self::_getType('strtolower', false),
            'filter'     => $filter,
            'language'   => $this->_getLanguage(),
            'external'   => urlencode(json_encode($external, true)),
            'sort'       => $sort,
        ];

        $View = View::getInstance();

        $path = self::getLibraryPath();

        $template = $path.'src'.App::DS.'public'.App::DS.'template'.App::DS;

        $View->renderizate($template, 'eliasis-complement', $data);
    }
}
