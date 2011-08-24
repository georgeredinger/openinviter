<?php
/**
 * ContactImporter.php
 *
 * Handles the contact importer
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
 * The contact importer action
 *
 * PHP Version: PHP 5
 *
 * @handles /get_contacts/{service:chars}
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
class ContactImporter extends Resource implements Get
{

    const GETPARAM_APIAUTH = 'apiauth';
    const GETPARAM_USERNAME = 'username';
    const GETPARAM_PASSWORD = 'password';

    /**
     * @var BrsConfig configuration object
     */
    private $_config;

    /**
     * @var HttpClientRequest The representation of the request
     */
    private $_request;

    /**
     * @var array The data from the url
     */
    private $_urlData;

    /**
     * @var OpenInviterInterface OI service
     */
    private $_service;


    /**
     * constructs the object
     *
     * @param BrsConfig            $config  The configuration object
     * @param HttpClientRequest    $request The Http request
     * @param OpenInviterInterface $service The OI service to use
     * @param array                $urlData Data from the ulr (if any)
     *
     * @return ContactImporter
     */
    public function  __construct(
        BrsConfig $config,
        HttpClientRequest $request,
        OpenInviterInterface $service,
        array $urlData=array()
    ) {
        $this->_config  = $config;
        $this->_request = $request;
        $this->_service = $service;
        $this->_urlData = $urlData;

    }//end __construct()


    /**
     * Handles the Get request
     *
     * @return void
     */
    public function executeGet()
    {
        try {
            $service = $this->formatService($this->_urlData['service']);
            $this->_service->startPlugin($service);

            if (!$this->_authenticateUser()) {
		exit;
            }

            $contacts = $this->_service->getMyContacts();
            $accounts = array();
            foreach ($contacts as $email => $name) {
                $accounts[] = array('email' => $email);
            }

            array_unique($accounts);
            echo json_encode($accounts);

        } catch (Exception $e) {
            header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error');
            echo json_encode($e->getMessage());
            return;
        }//end try

    }//end executeGet()


    /**
     * Authenticate the request
     *
     * @return bool
     */
    private function _authenticateUser()
    {
	if (!isset($_GET[self::GETPARAM_APIAUTH]))
	{
	
		return $this->_authV01($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
	}
	
	if (isset($_GET[self::GETPARAM_USERNAME]) || isset($_GET[self::GETPARAM_PASSWORD]))
	{
		if (!$this->_isValidClient($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
			$realm = $this->_config->realmName();
			header('WWW-Authenticate: Basic realm="'.$realm.'"');
			header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
			echo json_encode(array('message' => 'Bad API Client Username or Password.'));
			return false;
		}
		return $this->_authV02($_GET[self::GETPARAM_USERNAME], $_GET[self::GETPARAM_PASSWORD]);
	}
	header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
	echo json_encode(array('message' => 'Username and/or password is not given'));
	return false;
    }


    /**
     * Is the client a valid one?
     *
     * @param string $user The client username
     * @param string $pass The client password
     *
     * @return boolean
     */
    private function _isValidClient($user="", $pass="")
    {
	if($user=="brs-client" && md5($pass)=="f3720843492a2c691e161e39a7d9450b") {
		return true;
	}
	return false;
    }


    /**
     * Authenticates the _user_ with HTTP auth
     *
     * @param string $user The username of the service
     * @param string $pass The password to the username of the service
     *
     * @return boolean
     *
     * @deprecated
     */
    private function _authV01($user, $pass)
    {
        if (false === $this->_isValid($user, $pass)) {
            $realm = $this->_config->realmName();
            header('WWW-Authenticate: Basic realm="'.$realm.'"');
            header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
            error_log("Deprecated authentication method.");
            echo json_encode(array('message' => 'Bad Username or Password. This authentication method is deprecated, please use the new version'));
            return false;
        }
        return true;
    }
    
    
    /**
     * Authenticates the user to the service with non HTTP params
     *
     * @param string $user The username of the service
     * @param string $pass The password to the username of the service
     *
     * @return boolean
     */
    private function _authV02($user="", $pass="")
    {
	if (false === $this->_isValid($user, $pass)) {
		header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
		echo json_encode(array('message' => 'Bad Username or Password.'));
		return false;
	}
	return true;
    }

    /**
     * Determines if the user is valid or not.
     *
     * @param string $username The username trying to access a service
     * @param string $password The password for the username
     *
     * @return bool
     */
    private function _isValid($username, $password)
    {
        if (false === $this->_service->login($username, $password)) {
            return false;
        }

        return true;

    }//end _isValid()


    private function formatService($service)
    {
	switch($service) {
		case 'hotmail': return "hotmail";
		case 'live':    return "hotmail";
		case 'msn':     return "hotmail";
		default:
			return $service;
		}
    }

}//end class

?>