<?php
/**
 * StaticRouteTable.php
 *
 * Holds the StaticRouteTable class
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
 * The StaticRouteTable class is the value object of the static route table
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
class StaticRouteTable
{

    /**
     * @var array of uri => handler
     */
    public $uris;

    /**
     * @var arary of name => handler
     */
    public $names;

}//end class

?>