<?php
/**
 * Restlr.php
 *
 * Holds the Restlr class
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
 * The Restlr class is responsible for parsing incoming HTTP requests.
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
class Restlr
{


    /**
     * Issue a 404 not found response
     *
     * @return void
     */
    public function notFound()
    {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");

    }//end notFound()


    /**
     * Issue a 501 not implemented resposne
     */
    public function notImplemented()
    {
        header($_SERVER["SERVER_PROTOCOL"]." 501 Not Implemented");

    }//end notImplemented()


    public function run(
        Resource $resource,
        HttpClientRequest $request)
    {
        if (false === $resource->isImplemented($request->method)) {
            $this->notImplemented();
            return false;
        }

        $method = 'execute'.ucwords($request->method);
        $resource->$method();

        return true;
        
    }


}//end class

?>