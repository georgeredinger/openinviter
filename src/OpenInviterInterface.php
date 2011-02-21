<?php
/**
 * OpenInviterInterface.php
 *
 * Holds the OpenInviterInterface
 *
 * PHP Version: PHP 5
 *
 * @category File
 * @package  brs
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
 * The OpenInviterInterface interface
 *
 * PHP Version: PHP 5
 *
 * @category Class
 * @package  brs
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
interface OpenInviterInterface
{


    /**
	 * Login function
	 *
	 * Acts as a wrapper function for the plugin's
	 * login function.
	 *
	 * @param string $user The username being logged in
	 * @param string $pass The password for the username being logged in
	 * @return mixed FALSE if the login credentials don't match the plugin's
     * requirements or the result of the plugin's login function.
	 */
	public function login($user,$pass);


    /**
	 * Get the current user's contacts
	 *
	 * Acts as a wrapper function for the plugin's
	 * getMyContacts function.
	 *
	 * @return mixed The result of the plugin's getMyContacts function.
	 */
	public function getMyContacts();

}//end class

?>