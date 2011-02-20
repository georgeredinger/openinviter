<?php
/**
 * HttpFactory.php
 *
 * Holds the HttpFactory class
 *
 * PHP Version: 5
 *
 * @category File
 * @package  Http
 * @author   meza <meza@meza.hu>
 *
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
 * The HttpFactory class is responsible for creating Http objects
 *
 * PHP Version: 5
 *
 * @category Class
 * @package  Http
 * @author   meza <meza@meza.hu>
 * @license  GPLv3 <http://www.gnu.org/licenses/>
 * @link     http://www.assembla.com/spaces/p-pex
 */
class HttpFactory
{

    const NORMAL  = 0;
    const VERBOSE = 1;
    const PARSER  = 2;

    /**
     * @var CurlBuilder The CurlBuilder instance to use
     */
    private $_curlBuilder;

    /**
     * @var const The type of Http to produce
     */
    private $_type;

    /**
     * @var string SookieStore
     */
    private $_cs = 'cookies.txt';


    /**
     * Constructs the HttpBuilder object, while requiring the dependencies
     *
     * @param CurlBuilder $curlBuilder A CurlBuilder instance to use
     * @param const       $type        The type of Http to produce
     *
     * @return HttpFactory
     */
    public function  __construct(CurlBuilder $curlBuilder, $type=self::NORMAL)
    {
        $this->_curlBuilder = $curlBuilder;
        $this->_type        = $type;

    }//end __construct()


    /**
     * Creates a pre-configured Http object
     *
     * @return Http
     */
    public function createHttp()
    {
        switch ($this->_type) {
        case self::VERBOSE:
            return $this->_createVerbosingHttp();
        }

        return $this->_createDefaultHttp();

    }//end createHttp()


    /**
     * Creates a pre-configured Http object
     *
     * @return Http
     */
    private function _createDefaultHttp()
    {
        $http = new Http($this->_curlBuilder);

        $http->followLocation(true);
        $http->verifySSL(false);
        $http->setCookieStore($this->_cs);

        return $http;

    }//end _createDefaultHttp()


    /**
     * There are times when debugging is needed
     *
     * @return Http
     */
    private function _createVerbosingHttp()
    {
        $http = $this->_createDefaultHttp();
        $http->verbose(true);

        return $http;

    }//end _createVerbosingHttp()


    public function setCookieStore($cs)
    {
        $this->_cs = $cs;
    }


}//end class

?>
