<?php
/**
 * Eliasis PHP Framework
 *
 * @author     Daveismyname - dave@daveismyname.com 
 * @author     Josantonius  - hola@josantonius.com
 * @copyright  Copyright (c) 2017
 * @license    https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link       https://github.com/Eliasis-Framework/Eliasis
 * @since      1.0.0
 */

namespace Eliasis\Hook;

use Eliasis\App\App,
    Eliasis\Hook\Exception\HookException;

/**
 * Controller class.
 *
 * @since 1.0.0
 */
class Hook {

    /**
     * Module paths.
     *
     * @since 1.0.0
     *
     * @var array
     */
    private static $_modules = array();

    /**
     * Available hooks.
     *
     * @since 1.0.0
     *
     * @var array
     */
    private static $_hooks = array();

    /**
     * Instances.
     *
     * @since 1.0.0
     *
     * @var array
     */
    private static $_instances = array();

    /**
     * Get instance.
     *
     * @since 1.0.0
     *
     * @param int $id
     *
     * @return object → instance
     */
    public static function get($id = 0) {
        
        if (isset(self::$_instances[$id])) {

            return self::$_instances[$id];
        }

        self::setHooks([
            'meta',
            'css',
            'afterBody',
            'footer',
            'js',
            'routes'
        ]);

        self::loadModules(App::path('modules'));

        return self::$_instances[$id] = new self();
    }

    /**
     * Add hook to hook list.
     *
     * @since 1.0.0
     *
     * @param array $where → hook to add
     */
    public static function setHook($where) {

        self::$_hooks[$where] = '';
    }

    /**
     * Add multiple hooks.
     *
     * @since 1.0.0
     *
     * @param array $where → array of hooks to add
     */
    public static function setHooks($where) {

        foreach ($where as $where) {

            self::setHook($where);
        }
    }

    /**
     * Attach custom function to hook.
     *
     * @since 1.0.0
     *
     * @param array|string $where    → hook to use
     * @param string       $function → function to attach to hook
     *
     * @throws HookException → hook location not defined
     * @return boolean       → success with adding
     */
    public static function addHook($where, $function = '') {

        if (!is_array($where)) {

            $where = [$where => $function];
        }

        foreach ($where as $hook => $function) {

            if (!isset(self::$_hooks[$hook])) {

                $message = 'Hook location not defined: ' . $hook;
                
                throw new HookException($message, 811);
            }

            $theseHooks   = explode('|', self::$_hooks[$hook]);
            $theseHooks[] = $function;

            self::$_hooks[$hook] = implode('|', $theseHooks);
        }

        return true;
    }

    /**
     * Run all hooks attached to the hook.
     *
     * @since 1.0.0
     *
     * @param  string $where → hook to run
     * @param  string $args  → optional arguments
     *
     * @throws HookException → the hook is not yet known
     * @return object|false  → returns the calling function
     */
    public static function run($where, $args = '') {

        if (!isset(self::$_hooks[$where])) {

            $message = 'Hook location not defined: ' . $where;
            
            throw new HookException($message, 811);
        }

        $result = $args;

        $theseHooks = explode('|', self::$_hooks[$where]);
        
        foreach ($theseHooks as $hook) {
       
            if (preg_match("/@/i", $hook)) {

                $parts = explode('/', $hook);

                $last = end($parts);

                $segments = explode('@', $last);

                $instance = $segments[0]::getInstance($segments[0]);

                $result = call_user_func([$instance, $segments[1]], $result);

            } else {

                if (function_exists($hook)) {

                    $result = call_user_func($hook, $result);
                }
            }
        }

        return $result;
    }

    /**
     * Load all modules found in the directory.
     *
     * @since 1.0.0
     *
     * @param string $path   → modules folder path
     *
     * @throws HookException → module configuration file not found
     */
    public static function loadModules($path) {

        if ($handle = opendir($path)) {

            while ($file = readdir($handle)) {

                if ((is_dir($path.$file)) && !strpos("/./../", "$file")) {

                    $namespace = App::namespace('modules') . $file .BS. $file;

                    if (method_exists($namespace, 'routes')) {

                        call_user_func([$namespace, 'routes']);

                    } else {

                        $message = 'Module configuration file not found';
                        
                        throw new HookException($message, 812);
                    }
                    
                    self::$_modules[$file] = [];
                }
            }

            closedir($handle);
        }
    }

    /**
     * Execute hooks attached to run and collect instead of running
     *
     * @since 1.0.0
     *
     * @param string $where → hook
     * @param string $args  → optional arguments
     *
     * @return object → returns output of hook call
     */
    public function collectHook($where, $args = null) {

        ob_start();

        echo $this->run($where, $args);

        return ob_get_clean();
    }
}
