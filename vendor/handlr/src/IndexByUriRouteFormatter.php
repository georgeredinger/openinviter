<?php
/**
 * IndexByUriRouteFormatter.php
 *
 * Holds the IndexByUriRouteFormatter class
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
 * The IndexByUriRouteFormatter class is responsible for formatting the router
 * information that resolves the data to an uri indexed array
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
class IndexByUriRouteFormatter implements RouteFormatter
{


    /**
     * Formats the router information.to be indexed by the uri
     *
     * @param RouteCollection $router The router to use
     *
     * @return array
     */
    public function format(RouteCollection $router)
    {
        $routes = $router->listItems();
        $map    = array();
        usort($routes, array($this, 'sortMap'));

        foreach ($routes as $route) {
            $map[$route->getPattern()] = $route->getHandler();
        }
        return $map;

    }//end format()


    /**
     *
     * @param Route $a
     * @param Route $b
     * @return <type>
     */
    private function sortMap(Route $a, Route $b=null)
    {
        $alen = strlen($a->getPattern());
        $blen = strlen($b->getPattern());

        if ($alen <= $blen) {
            return 1;
        } else {
            return 0;
        }

    }


}//end class

?>