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
use Josantonius\Json\Json;

/**
 * Complement states handler.
 *
 * @since 1.0.9
 */
trait ComplementState
{

    /**
     * List of complements (status indicators).
     *
     * @since 1.0.9
     *
     * @var array
     */
    protected $states;

    /**
     * States/actions that will be executed when a complement changes state.
     *
     * @since 1.0.9
     *
     * @var array
     */
    protected static $statesHandler = [
        'active' => [
            'action' => 'deactivation',
            'state'  => 'inactive'
        ],
        'inactive' => [
            'action' => 'activation',
            'state'  => 'active'
        ],
        'uninstall' => [
            'action' => 'uninstallation',
            'state'  => 'uninstalled'
        ],
        'uninstalled' => [
            'action' => '',
            'state'  => 'installed'
        ],
        'installed' => [
            'action' => 'installation',
            'state'  => 'inactive'
        ],
        'outdated' => [
            'action' => 'installation',
            'state'  => 'updated'
        ],
        'updated' => [
            'action' => 'activation',
            'state'  => 'active'
        ],
    ];

    /**
     * Default states.
     *
     * @since 1.0.9
     *
     * @var array
     */
    protected static $defaultStates = [

        'component' => 'active',
        'plugin'    => 'active',
        'module'    => 'active',
        'template'  => 'active',
    ];

    /**
     * Set complement state.
     *
     * @since 1.0.9
     *
     * @param string $state → complement state
     *
     * @return string → state
     */
    public function setState($state)
    {

        $this->complement['state'] = $state;

        $this->states['state'] = $state;

        $this->_setStates();

        return $state;
    }

    /**
     * Change complement state.
     *
     * @since 1.0.9
     *
     * @uses void ComplementAction::doAction() → execute action hooks
     *
     * @return string → new state
     */
    public function changeState()
    {

        $this->getStates();

        $actualState = $this->getState();

        $newState = self::$statesHandler[$actualState]['state'];
        $action   = self::$statesHandler[$actualState]['action'];

        $this->setState($newState);

        $this->doAction($action);

        return $newState;
    }

    /**
     * Get complement state.
     *
     * @since 1.0.9
     *
     * @uses string App::complements()            → default state complement
     * @uses string ComplementHandler::_getType() → complement type
     *
     * @return string → complement state
     */
    public function getState()
    {

        if (isset($this->states['state'])) {
            return $this->states['state'];
        } else if (isset($this->complement['state'])) {
            return $this->complement['state'];
        }

        $type = self::_getType();

        return self::$defaultStates[$type];
    }

    /**
     * Get complements states.
     *
     * @since 1.0.9
     *
     * @uses string App::$id        → application ID
     * @uses string Complement::$id → complement ID
     *
     * @return array → complements states
     */
    public function getStates()
    {

        $states = $this->_getStatesFromFile();

        if (isset($states[App::$id][self::$id])) {
            return $this->states = $states[App::$id][self::$id];
        }

        return $this->states = [];
    }

    /**
     * Set complements states.
     *
     * @since 1.0.9
     *
     * @uses string   App::$id            → application ID
     * @uses string   Complement::$id     → complement ID
     * @uses array    Json::arrayToFile() → convert array to json file
     * @uses callable Hook::doAction()    → do action
     *
     * @return void
     */
    private function _setStates()
    {

        if (!is_null($this->states)) {
            $states = $this->_getStatesFromFile();

            if ($this->_stateChanged($states)) {
                $file = $this->_getStatesFilePath();

                $states[App::$id][self::$id] = $this->states;

                Json::arrayToFile($states, $file);

                Hook::doAction('Eliasis/Complement/after_set_states', $states);
            }
        }
    }

    /**
     * Check if complement state has changed.
     *
     * @since 1.0.9
     *
     * @param string $states → complement states
     *
     * @uses string App::$id        → application ID
     * @uses string Complement::$id → complement ID
     *
     * @return boolean
     */
    private function _stateChanged($states)
    {

        if (isset($states[App::$id][self::$id])) {
            $actualStates = $states[App::$id][self::$id];

            if (!count(array_diff_assoc($actualStates, $this->states))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get states from json file.
     *
     * @since 1.0.9
     *
     * @uses array Json::fileToArray() → convert json file to array
     *
     * @return array → complements states
     */
    private function _getStatesFromFile()
    {

        return Json::fileToArray($this->_getStatesFilePath());
    }

    /**
     * Get complements file path.
     *
     * @since 1.0.9
     *
     * @uses string App::COMPLEMENT()             → complement path
     * @uses string ComplementHandler::_getType() → complement type
     *
     * @return string → complements file path
     */
    private function _getStatesFilePath()
    {

        $type = self::_getType();

        $complementType = self::_getType('strtoupper');

        return App::$complementType() . '.' . $type . '-states.jsond';
    }
}
