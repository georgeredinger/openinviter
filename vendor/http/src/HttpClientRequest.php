<?php
/**
 * HttpClientRequest.php
 *
 * Holds the HttpClientRequest class
 *
 * PHP Version: PHP 5
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
 * @link     http://www.meza.hu
 */

/**
 * The HttpClientRequest class is the representation of an incoming request
 *
 * PHP Version: PHP 5
 *
 * @category Class
 * @package  Http
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
class HttpClientRequest
{
    public $method;
    public $uri;
    public $protocol;
    public $protocolVersion;
    
    /**
     * @var HttpHeaderComposite instance
     */
    public $headers;

    public $data = array();

}//end class

?>