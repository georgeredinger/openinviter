<?php
/**
 * BrsStubOpeninviter.php
 *
 * Holds the BrsStubOpeninviter class
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
 * The BrsStubOpeninviter class enhances openinviter with configuration injection
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
class BrsStubOpeninviter extends openinviter implements OpenInviterInterface
{

    private $_service;


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
     * @throws BrsStubOpeninviterException
     */
    public function startPlugin($plugin_name,$getPlugins=false)
    {
        $this->_service = $plugin_name;

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
     * @throws BrsStubOpeninviterException
     */
    public function login($user,$pass)
    {
        if ($user === 'clubleads@gmail.com' && $pass === 'baseball') {
            return true;
        }

        return false;

    }//end login()


    /**
     * Get the current user's contacts
     *
     * Acts as a wrapper function for the plugin's
     * getMyContacts function.
     *
     * @return array
     *
     * @throws BrsStubOpeninviterException
     */
    public function getMyContacts()
    {
        if ($this->_service === 'empty') {
            return array();
        }

        $accounts = array(
                     'sue@test.com'  => 'sue doe',
                     'eric@test.com' => 'eric doe',
                    );

        return $accounts;

    }//end getMyContacts()


}//end class

?>