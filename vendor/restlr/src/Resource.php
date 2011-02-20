<?php
/**
 * Resource.php
 *
 * Holds the Resource class
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
 * The Resource class is responsible for ...
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
 * of this license document, but chGETanging it is not allowed.
 * @link     http://www.meza.hu
 */
abstract class Resource implements Options
{


    /**
     * Handles OPTION requests
     *
     * @return void
     */
    public function executeOptions()
    {
        $methods = $this->_getImplementedMethods();
        $allowed = implode(', ', $methods);
        header('Allow: '.strtoupper($allowed));


    }//end executeOptions()


    /**
     * Determines that a HttpClientRequest is
     * authorized on this Resource
     *
     * @param HttpClientRequest $request The request to check
     *
     * @return bool
     */
    public function isAuthorized(HttpClientRequest $request)
    {
        return true;

    }//end isAuthorized()


    /**
     * Determines that a method is implemented Resource
     *
     * @param string $requestMethod The request method to check
     *
     * @return bool
     */
    public function isImplemented($requestMethod)
    {
        return in_array(
            strtolower($requestMethod),
            $this->_getImplementedMethods()
        );

    }//end isImplemented()


    /**
     * Get all implemented http methods of a resource
     *
     * @return array
     */
    protected function _getImplementedMethods()
    {
        $methods = array(
                    'Get',
                    'Post',
                    'Put',
                    'Delete',
                    'Head',
                    'Options',
                    );

        $implemented = array();
        foreach ($methods as $method) {
            if ($this instanceof $method) {
                $implemented[] = strtolower($method);
            }
        }

        return $implemented;

    }//end _getImplementedMethods()


}//end class

?>