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
use Josantonius\Hook\Hook;

/**
 * Complement action handler.
 *
 * @since 1.0.9
 */
trait ComplementAction
{

    /**
     * Action hooks.
     *
     * @since 1.0.9
     *
     * @var array
     */
    protected static $hooks = [
        'activation',
        'deactivation',
        'installation',
        'uninstallation',
    ];

    /**
     * Default actions.
     *
     * @since 1.0.9
     *
     * @var array
     */
    protected static $defaultAction = [

        'component' => 'activation',
        'plugin'    => 'activation',
        'module'    => 'activation',
        'template'  => 'activation',
    ];

    /**
     * Get complement action.
     *
     * @since 1.0.9
     *
     * @param string $state → complement state
     *
     * @uses array  ComplementState->$states      → complements states
     * @uses string ComplementHandler::_getType() → complement type
     *
     * @return boolean
     */
    public function getAction($state)
    {

        $type = self::_getType('strtolower', false);

        $action = self::$defaultAction[$type];

        if (isset($this->states['action'])) {
            $action = $this->states['action'];
        }

        return ($state === 'uninstalled') ? '' : $action;
    }

    /**
     * Set complement action.
     *
     * @since 1.0.9
     *
     * @param string $action
     *
     * @uses array ComplementState->$states → complements states
     *
     * @return string → complement action
     */
    public function setAction($action)
    {

        return $this->states['action'] = $action;
    }

    /**
     * Execute action hook.
     *
     * @since 1.0.9
     *
     * @param string $action → action hook to execute
     *
     * @uses object Complement::getInstance()     → Complement instance
     * @uses string ComplementAction::setAction() → set complement action
     *
     * @return boolean
     */
    public function doAction($action)
    {

        $controller = $this->get('hooks-controller');

        $instance = $this->instance($controller, 'controller');

        if (is_object($instance) && method_exists($instance, $action)) {
            $response = call_user_func([$instance, $action]);
        }

        $this->setAction('');

        return isset($response);
    }

    /**
     * Add complement action hooks.
     *
     * @since 1.0.9
     *
     * @uses string App::$id                → application ID
     * @uses array  Complement->$complement → complement settings
     * @uses object Hook::getInstance       → get Hook instance
     * @uses mixed  Hook::addActions        → add complement action hooks
     *
     * @return void
     */
    private function _addActions()
    {

        if (isset($this->complement['hooks'])) {
            Hook::getInstance(App::$id);
                
            return Hook::addActions($this->complement['hooks']);
        }

        return false;
    }

    /**
     * Execute action hooks.
     *
     * @since 1.0.9
     *
     * @param string $action → action hook to execute
     *
     * @uses string App::$id                      → application ID
     * @uses object Hook::getInstance             → get Hook instance
     * @uses mixed  Hook::doAction                → run hooks
     * @uses string ComplementHandler::_getType() → complement type
     *
     * @return void
     */
    private function _doActions($action)
    {

        $type = self::_getType('strtolower', false);

        Hook::getInstance(App::$id);

        Hook::doAction($type . '-load');

        if (in_array($action, self::$hooks)) {
            $this->doAction($action);
        }
    }
}
