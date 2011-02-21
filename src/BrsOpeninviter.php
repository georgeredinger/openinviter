<?php
/**
 * BrsOpeninviter.php
 *
 * Holds the BrsOpeninviter class
 *
 * PHP Version: PHP 5
 *
 * @category File
 * @package  Brs
 * @author   meza <meza@meza.hu>
 * @license  GPL3.0
 *                    GNU GENERAL PUBLIC LICENSE
 *                       Version 3, 29 June 2007
 *
 * Copyright (C) 2007 Free Software Foundation, Inc. <http://fsf.org/>
 * Everyone is permitted to copy and distribute verbatim copies
 * of this license document, but changing it is not allowed.
 * @link     http://www.meza.hu
 */

/**
 * The BrsOpeninviter class enhances openinviter with configuration injection
 *
 * PHP Version: PHP 5
 *
 * @category Class
 * @package  Brs
 * @author   meza <meza@meza.hu>
 * @license  GPL3.0
 *                    GNU GENERAL PUBLIC LICENSE
 *                       Version 3, 29 June 2007
 *
 * Copyright (C) 2007 Free Software Foundation, Inc. <http://fsf.org/>
 * Everyone is permitted to copy and distribute verbatim copies
 * of this license document, but changing it is not allowed.
 * @link     http://www.meza.hu
 */
class BrsOpeninviter extends openinviter implements OpenInviterInterface
{


    /**
     * Enhanced constructor
     *
     * @param array $config The system config
     *
     * @return openinviter
     */
    public function __construct(array $config=array())
    {
        parent::__construct();
        $this->settings = array_merge($this->settings, $config);

    }//end __construct()


    /**
     * Start internal plugin
     *
     * Starts the internal plugin and
     * transfers the settings to it.
     *
     * @param string $plugin_name The name of the plugin being started
     * @param bool   $getPlugins  get plugins or not
     *
     * @return bool
     *
     * @throws BrsOpenInviterException
     */
    public function startPlugin($plugin_name,$getPlugins=false)
    {
        try {
            $result = parent::startPlugin($plugin_name, $getPlugins);

            if (false === $this->_didPreviousCallSucceed()) {
                throw new BrsOpenInviterException(
                     $this->getInternalError().': \''.$plugin_name.'\''
                );
            }

            if (false === $result) {
                throw new BrsOpenInviterException(
                    'Plugin could not be started: "'.$plugin_name.'"'
                );
            }
        } catch (Exception $e) {
            throw new BrsOpenInviterException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }//end try

    }//end startPlugin()


    /**
     * Login function
     *
     * Acts as a wrapper function for the plugin's
     * login function.
     *
     * @param string $user The username being logged in
     * @param string $pass The password for the username being logged in
     *
     * @return bool
     *
     * @throws BrsOpenInviterException
     */
    public function login($user,$pass)
    {
        try {
            $result = parent::login($user, $pass);

            if (false === $this->_didPreviousCallSucceed()) {
                throw new BrsOpenInviterException($this->getInternalError());
            }

            if (false === $result) {
                return false;
            }

            return true;

        } catch (Exception $e) {
            throw new BrsOpenInviterException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }//end try

    }//end login()


    /**
     * Get the current user's contacts
     *
     * Acts as a wrapper function for the plugin's
     * getMyContacts function.
     *
     * @return array
     *
     * @throws BrsOpenInviterException
     */
    public function getMyContacts()
    {
        try {
            $result = parent::getMyContacts();

            if (false === $this->_didPreviousCallSucceed()) {
                throw new BrsOpenInviterException($this->getInternalError());
            }

            if (false === $result) {
                throw new BrsOpenInviterException(
                    'Plugin could not fetch contacts'
                );
            }

            return $result;

        } catch (Exception $e) {
            throw new BrsOpenInviterException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }//end try

    }//end getMyContacts()


    /**
     * Did the open inviter report an error?
     *
     * @return bool
     */
    private function _didPreviousCallSucceed()
    {
        if (false === $this->getInternalError()) {
            return true;
        }

        return false;

    }//end _didPreviousCallSucceed()


}//end class

?>