<?php
/**
 * RouterFormatter.php
 *
 * Holds the RouterFormatter interface
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
 * The RouterFormatter interface declares a type that's implementations
 * are responsible for formatting the router entries
 *
 * PHP Version: PHP 5
 *
 * @category Interface
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
interface RouteFormatter
{


    /**
     * Formats the router information.
     *
     * @param RouteCollection $router The router to use
     *
     * @return mixed Depends on the implementation
     */
    public function format(RouteCollection $router);


}//end interface

?>