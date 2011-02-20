<?php
/**
 * Http.php
 *
 * Holds the http comm layer
 *
 * PHP Version: 5
 *
 * @category File
 * @package  Http
 *
 * @author   meza <meza@meza.hu>
 * @license  GPL3.0
 *                    GNU GENERAL PUBLIC LICENSE
 *                       Version 3, 29 June 2007
 *
 * Copyright (C) 2007 Free Software Foundation, Inc. <http://fsf.org/>
 * Everyone is permitted to copy and distribute verbatim copies
 * of this license document, but changing it is not allowed.
 *
 * @link     http://www.assembla.com/spaces/p-pex
 **/

/**
 * The Http class is responsible for Curl based Http requests
 *
 * PHP Version: 5
 *
 * @category Class
 * @package  Http
 * @author   meza <meza@meza.hu>
 * @license  GPLv3 <http://www.gnu.org/licenses/>
 * @link     http://www.assembla.com/spaces/p-pex
 */
class Http
{

    /**
     * @var CurlBuilder The curl builder dependency
     */
    private $_curlBuilder;

    /**
     * @var string The filename of the cookieStore (if set)
     */
    private $_cookieStore = null;

    /**
     * @var bool True to verify ssl host
     */
    private $_sslVerifyHost = false;

    /**
     * @var bool true to verify ssl peer
     */
    private $_sslVerifyPeer = false;

    /**
     * @var bool true to follow redirects
     */
    private $_followLocation = true;

    /**
     * @var bool true to be verbose
     */
    private $_verbose = false;

    /**
     * @var bool to return transfer
     */
    private $_getResponse = true;


    /**
     * Csontructs the object
     *
     * @param CurlBuilder $curlBuilder The curlBuilder object to use
     *
     * @return Http
     */
    public function __construct(CurlBuilder $curlBuilder)
    {
        $this->_curlBuilder = $curlBuilder;

    }//end __construct()


    /**
     * Sets the cookie store of the curl
     *
     * @param string $cookieFile The filename to use as a cookie store
     *
     * @return void
     */
    public function setCookieStore($cookieFile='cookies.txt')
    {
        $this->_cookieStore = $cookieFile;

    }//end setCookieStore()


    /**
     * Sets SSL verification policy
     *
     * @param bool $flag True to verify ssl certs, false to not
     *
     * @return void
     */
    public function verifySSL($flag=false)
    {
        $this->_sslVerifyHost = $flag;
        $this->_sslVerifyPeer = $flag;

    }//end verifySSL()


    /**
     * Sets wether the request should follow redirects
     *
     * @param bool $flag True to follow, false to not
     *
     * @return void
     */
    public function followLocation($flag=true)
    {
        $this->_followLocation = $flag;

    }//end followLocation()


    /**
     * Sets wether the request should be verbose
     *
     * @param bool $flag True to be, false to not
     *
     * @return void
     */
    public function verbose($flag=false)
    {
        $this->_verbose = $flag;

    }//end verbose()


    /**
     * Sets wether the response should be returned or not
     *
     * @param bool $flag True to return transfer
     *
     * @return void
     */
    public function waitForResponse($flag=true)
    {
        $this->_getResponse = $flag;

    }//end waitForResponse()


    /**
     * Makes a http call
     *
     * @param HttpParams $httpParams The HttpParams object to use
     *
     * @return HttpResponse
     */
    public function request(HttpParams $httpParams)
    {
        $curl = $this->_curlBuilder->createCurl(
            $httpParams,
            array(
             'cookieStore'    => $this->_cookieStore,
             'followLocation' => $this->_followLocation,
             'SSLVerifyHost'  => $this->_sslVerifyHost,
             'SSLVerifyPeer'  => $this->_sslVerifyPeer,
             'verbose'        => $this->_verbose,
             'returnTransfer' => $this->_getResponse,
            )
        );
        $response              = $curl->execute();
        $httpResponse          = new HttpResponse();
        $httpResponse->code    = $response['code'];
        $httpResponse->data    = $response['data'];
        $httpResponse->headers = $response['headers'];

        return $httpResponse;

    }//end request()


}//end class

?>