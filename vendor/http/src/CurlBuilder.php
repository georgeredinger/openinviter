<?php
/**
 * CurlBuilder.php
 *
 * Holds the CurlBuilder class
 *
 * PHP Version: 5
 *
 * @category File
 * @package  Http
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
 */

/**
 * The CurlBuilder class is responsible for building specific curl instances
 *
 * PHP Version: 5
 *
 * @category Class
 * @package  Http
 * @author   meza <meza@meza.hu>
 * @license  GPLv3 <http://www.gnu.org/licenses/>
 * @link     http://www.assembla.com/spaces/p-pex
 */
class CurlBuilder
{

    /**
     * @var URLFactory instance 
     */
    private $_urlFactory;


    /**
     * Constructs the object
     *
     * @param URLFactory $urlFactory The urlfactory to use
     *
     * @return CurlBuilder
     */
    public function __construct(URLFactory $urlFactory)
    {
        $this->_urlFactory = $urlFactory;

    }//end __construct()


    /**
     * Creates a curl instance from the given arguments
     *
     * @param HttpParams $httpParams The Http config object
     * @param array      $config     Extra config parameters
     *
     * @return Curl
     */
    public function createCurl(HttpParams $httpParams, array $config=array())
    {
        $curl = new Curl();
        $curl = $this->prepareCurl($curl, $httpParams, $config);

        return $curl;

    }//end createCurl()


    /**
     * Sets the default data on a Curl instance
     *
     * @param Curl       $curl       The Curl object to use
     * @param HttpParams $httpParams The HttpParams to use
     * @param array      $config     The extra config to use
     *
     * @return Curl prepared
     *
     * @throws NoUrlSetException when no url was set
     */
    public function prepareCurl(
        Curl $curl,
        HttpParams $httpParams,
        array $config=array()
    ) {
        $curl->setReturnTransfer(true);
        $curl->verbose(false);
        $curl->returnHeaders(false);

        $this->_applyConfig($curl, $config);

        $headers   = $this->_parseHeaders($httpParams->headers);
        $headers[] = 'User-Agent: '.$httpParams->userAgent;
        $curl->setHeaders($headers);


        $httpMethod = $this->_parseHttpMethod($httpParams->httpMethod);
        if ('POST' === $httpMethod) {
            $curl->setPost(true);
        }

        if (null !== $httpParams->referrer) {
            $curl->setReferrer($this->_urlFactory->getUrlFor($httpParams->referrer));
        }

        if (null === $httpParams->url) {
            throw new NoUrlSetException();
        }


        $param_arr = array($httpParams->url);
        foreach ($httpParams->urlParams as $key => $value) {
            $param_arr[] = $value;
        }
        $url = call_user_func_array(
            array($this->_urlFactory, 'getUrlFor'),
            $param_arr
        );
        $curl->setUrl($url);

        $httpParams->setPreparedUrl($url);

        if (null !== $httpParams->data) {
            $curl->setData($httpParams->data);
        }

        if (null !== $httpParams->customMethod) {
            $curl->setCustomMethod(strtoupper($httpParams->customMethod));
        }

        if (true === $this->_hasCredentials($httpParams)) {
            $curl->setAuth(
                $httpParams->httpUsername,
                $httpParams->httpPassword,
                CURLAUTH_BASIC
            );
        }

        return $curl;

    }//end prepareCurl()


    /**
     * Checks if credentials are set or not
     *
     * @param HttpParams $httpParams The HttpParams to use
     *
     * @return bool
     */
    private function _hasCredentials(HttpParams $httpParams)
    {
        if (null === $httpParams->httpUsername) {
            return false;
        }

        if (null === $httpParams->httpPassword) {
            return false;
        }

        return true;

    }//end _hasCredentials()


    /**
     * Applies extra config
     *
     * @param Curl  $curl   The curl instance to work with
     * @param array $config The extra config array
     *
     * @return void
     */
    private function _applyConfig(Curl $curl, array $config=array())
    {
        if (true === isset($config['cookieStore'])) {
            $curl->setCookieStore($config['cookieStore']);
        }

        if (true === isset($config['followLocation'])) {
            $curl->followLocation($config['followLocation']);
        }

        if (true === isset($config['SSLVerifyHost'])) {
            $curl->setSSLVerifyHost($config['SSLVerifyHost']);
        }

        if (true === isset($config['SSLVerifyPeer'])) {
            $curl->setSSLVerifyPeer($config['SSLVerifyPeer']);
        }

        if (true === isset($config['verbose'])) {
            $curl->verbose($config['verbose']);
        }

        if (true === isset($config['returnTransfer'])) {
            $curl->setReturnTransfer($config['returnTransfer']);
        }

    }//end _applyConfig()


    /**
     * Parses the httpMethod variable. It could only be POST or GET
     *
     * @param string $method The given Http method to use
     *
     * @return string uppercased method if valid
     *
     * @throws InvalidHttpMethodException when needed
     */
    private function _parseHttpMethod($method)
    {
        $validMethods = array(
                         'GET',
                         'POST',
                        );

        $method = strtoupper($method);

        if (false === in_array($method, $validMethods)) {
            throw new InvalidHttpMethodException();
        }

        return $method;

    }//end _parseHttpMethod()


    /**
     * Parses the header data
     * 
     * @param array $headers An associative array of headers, where the array
     * keys could be the header names. If the array key is numeric, than the
     * value is added to the headers array. If it is a string, than it is added
     * as $key: $value to the headers array
     * 
     * @return array of parsed headers
     */
    private function _parseHeaders(array $headers=array())
    {
        $retval = array();
        foreach ($headers as $key => $value) {
            if (true === is_numeric($key)) {
                $retval[] = $value;
            } else if (true === is_string($key)) {
                $retval[] = $key.': '.$value;
            }
        }

        return $retval;

    }//end _parseHeaders()


}//end class

?>
