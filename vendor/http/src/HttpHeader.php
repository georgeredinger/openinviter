<?php
/**
 * HttpHeader.php
 *
 * Holds the HttpHeader class
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
 * The HttpHeader class is the type of the headers
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
class HttpHeader implements CompositeItem
{

    /**
     * @var string The name of the header
     */
    public $name;

    /**
     * @var string The value of the header
     */
    public $value;


    /**
     * Creates a HttpHeader
     *
     * @param string $name  The name of the header
     * @param string $value The value of the header
     */
    public function __construct($name, $value)
    {
        $this->setName($name);
        $this->value = $value;

    }//end __construct()


    /**
     * All CompositeItem objects should have a reference name that the
     * Composite can refer to.
     *
     * @return string The name of the CompositeItem
     */
    public function getName()
    {
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


}//end class

?>