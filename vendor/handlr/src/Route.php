<?php
/**
 * Route.php
 *
 * Holds the Route class
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
 * The Route class is the value object of routing elements. This descripbes
 * the what / where / why, etc.
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
class Route implements CompositeItem
{

    /**
     * @var string The name of the route
     */
    protected $name;

    /**
     * @var string The url pattern that the route listens to
     */
    protected $pattern;

    /**
     * @var The name of the class that hanldes the route
     */
    protected $handler;


    /**
     * Constructs the object
     *
     * @param string $name    The name of the route
     * @param string $pattern The canonical url to listen to
     *
     * @return Route
     */
    public function __construct($name, $pattern)
    {
        $this->setName($name);
        $this->pattern = $pattern;

    }//end __construct()


    /**
     * All CompositeItem objects should have a reference name that the
     * Composite can refer to.
     *
     * @return string The name of the CompositeItem
     *
     * @throws Exception when the name is not set
     */
    public function getName()
    {
        if (null === $this->name) {
            throw new Exception('Name not set for this object');
        }

        return $this->name;

    }//end getName()


    /**
     * The name must be queryable
     *
     * @param string $name The name of the CollectionItem
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;

    }//end setName()


    /**
     * The Route should have a Handler that handles the request
     *
     * @param string $handlerClassName The name of the class that hanldes the
     *                                 route
     *
     * @return void
     */
    public function addHandler($handlerClassName)
    {
        $this->handler = $handlerClassName;

    }//end addHandler()


    /**
     * The handler needs to be retrieved too
     *
     * @return string The name of the class that hanldes the route
     */
    public function getHandler()
    {
        return $this->handler;

    }//end getHandler()


    /**
     * The pattern needs to be recovered
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;

    }//end getPattern()


}//end class

?>