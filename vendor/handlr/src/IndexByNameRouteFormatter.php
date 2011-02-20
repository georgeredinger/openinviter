<?php
/**
 * IndexByNameRouteFormatter.php
 *
 * Holds the IndexByNameRouteFormatter class
 *
 * PHP Version: PHP 5
 *
 * @category File
 * @package  Handlr
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
 * The IndexByNameRouteFormatter class is responsible for indexing a
 * RouteCollection by name
 *
 * PHP Version: PHP 5
 *
 * @category Class
 * @package  Handlr
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
class IndexByNameRouteFormatter implements RouteFormatter
{


    /**
     * Formats the router information.to be indexed by the name
     *
     * @param RouteCollection $router The router to use
     *
     * @return array
     */
    public function format(RouteCollection $router)
    {
        $routes = $router->listItems();
        $map    = array();

        foreach ($routes as $name => $route) {
            $map[$route->getName()] = $route->getHandler();
        }

        return $map;

    }//end format()


}//end class

?>