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
            $this->_service->startPlugin($this->_urlData['service']);

            $user = $_SERVER['PHP_AUTH_USER'];
            $pass = $_SERVER['PHP_AUTH_PW'];
            if (false === $this->_isValid($user, $pass)) {
                header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
                echo json_encode(array('message' => 'Bad Username or Password'));
                return;
            }

            $contacts = $this->_service->getMyContacts();
            $accounts = array();
            foreach ($contacts as $email => $name) {
                $accounts[] = array('email' => $email);
            }

            echo json_encode($accounts);

        } catch (Exception $e) {
            header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error');
            echo json_encode($e->getMessage());
            return;
        }//end try

    }//end executeGet()


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


}//end class

?>