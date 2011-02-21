<?php
/**
 * HttpTestCaseBase.php
 *
 * Holds the HttpTestCaseBase class
 *
 * PHP Version: PHP 5
 *
 * @category File
 * @package  Restlr
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
 * The HttpTestCaseBase class is responsible for being the base test class
 *
 * PHP Version: PHP 5
 *
 * @category Class
 * @package  Restlr
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
class HttpTestCaseBase extends CommonTestCaseBase
{

    public $hostname = 'http://127.0.0.1';


    /**
     * Get a Http instance
     *
     * @param string $hostname Base url.
     *
     * @return Http
     */
    public function getHttp($hostname=null)
    {
        if (null === $hostname) {
            $hostname = $this->hostname;
        }

        $urlFactory  = new URLFactory($hostname);
        $curlBuilder = new CurlBuilder($urlFactory);
        $http        = new Http($curlBuilder);
        $http->followLocation(false);
        return $http;

    }//end getHttp()


    /**
     * Issue a get
     *
     * @param string $url     The url
     * @param array  $data    The data
     * @param array  $headers The extra headers of the request.
     *
     * @return HttpResponse
     */
    public function get($url, array $data=array(), array $headers=array())
    {
        return $this->_request($url, 'GET', $data, $headers);

    }//end get()


    /**
     * Issue a head
     *
     * @param string $url  The url
     * @param array  $data The data
     *
     * @return HttpResponse
     */
    public function head($url, array $data=array())
    {
        return $this->_request($url, 'HEAD', $data);

    }//end head()


    /**
     * Issue an options
     *
     * @param string $url  The url
     * @param array  $data The data
     *
     * @return HttpResponse
     */
    public function options($url, array $data=array())
    {
        return $this->_request($url, 'OPTIONS', $data);

    }//end options()


    /**
     * Issue a post
     *
     * @param string $url  The url
     * @param array  $data The data
     *
     * @return HttpResponse
     */
    public function post($url, array $data=array())
    {
        return $this->_request($url, 'POST', $data);

    }//end post()


    /**
     * Issue a put
     *
     * @param string $url  The url
     * @param array  $data The data
     *
     * @return HttpResponse
     */
    public function put($url, array $data=array())
    {
        return $this->_request($url, 'PUT', $data);

    }//end put()


    /**
     * Issue a delete
     *
     * @param string $url The url
     *
     * @return HttpResponse
     */
    public function delete($url)
    {
        return $this->_request($url, 'DELETE');

    }//end delete()


    /**
     * Make a request.
     *
     * @param string $url     The url to make the call on.
     * @param string $method  The method to call.
     * @param array  $data    The data of the request.
     * @param array  $headers The extra headers of the request.
     *
     * @return HttpResponse
     */
    private function _request(
        $url,
        $method,
        array $data=array(),
        array $headers=array()
    ) {
        $http             = $this->getHttp();
        $param            = new HttpParams();
        $param->url       = $url;
        $param->data      = $data;
        $param->userAgent = 'phpunit';

        if (true === in_array($method, array('GET', 'POST'))) {
            $param->httpMethod = $method;
        } else {
            if ($method === 'HEAD') {
                $param->headers = array('Connection' => 'close');
                $http->waitForResponse(false);
            }

            $param->httpMethod   = 'POST';
            $param->customMethod = $method;
        }

        $param->headers = array_merge($param->headers, $headers);
        return $http->request($param);

    }//end _request()


}//end class

?>