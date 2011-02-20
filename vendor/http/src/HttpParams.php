<?php
/**
 * HttpParams.php
 *
 * Holds the HttpParams class
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
 * The HttpParams class is a value object for creating http requests
 *
 * PHP Version: 5
 *
 * @category Class
 * @package  Http
 * @author   meza <meza@meza.hu>
 * @license  GPLv3 <http://www.gnu.org/licenses/>
 * @link     http://www.assembla.com/spaces/p-pex
 */
class HttpParams
{

    /**
     * @var string The user agent string the request claims itself
     */
    public $userAgent = 'Mozilla/5.0 (X11; U; Linux x86_64; hu-HU; rv:1.9.1.8) 
    Gecko/20100214 Ubuntu/9.10 (karmic) Firefox/3.5.8';

    /**
     * @var string The custom method to use
     */
    public $customMethod = null;

    /**
     * @var mixed the request data POST/GET
     */
    public $data = null;

    /**
     * @var array the headers of the request;
     */
    public $headers = array();

    /**
     * @var string The Http method to use
     */
    public $httpMethod = 'GET';

    /**
     * @var string The http username
     */
    public $httpUsername = null;

    /**
     * @var string The http password
     */
    public $httpPassword = null;

    /**
     * @var string The referrer url
     */
    public $referrer = null;

    /**
     * @var string The url
     */
    public $url = null;

    /**
     * @var array of url params
     */
    public $urlParams = array();

    /**
     * @var The url that the request will go to
     */
    protected $preparedUrl;


    /**
     * Set the prepared url
     *
     * @param string $url The url
     *
     * @return void
     */
    public function setPreparedUrl($url)
    {
        $this->preparedUrl = $url;
    }

}//end class

?>
