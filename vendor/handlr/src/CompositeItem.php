<?php
/**
 * CompositeItem.php
 *
 * Holds the CompositeItem interface
 *
 * PHP Version: PHP 5
 *
 * @category File
 * @package  Interfaces
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
 * The CompositeItem interface notes that the element could be added as a
 * composite item
 *
 * PHP Version: PHP 5
 *
 * @category Interface
 * @package  Interfaces
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
interface CompositeItem
{


    /**
     * All CompositeItem objects should have a reference name that the
     * Composite can refer to.
     * 
     * @return string The name of the CompositeItem
     */
    public function getName();


    /**
     * The name must be queryable
     *
     * @param string $name The name of the CollectionItem
     *
     * @return void
     */
    public function setName($name);


}//end interface

?>