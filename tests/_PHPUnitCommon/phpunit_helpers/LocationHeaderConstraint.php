<?php

/**
 * LocationHeaderConstraint.php
 *
 * Holds the LocationHeaderConstraint class
 *
 * PHP Version: PHP 5
 *
 * @category File
 * @package  Brs
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
 * The LocationHeaderConstraint class is responsible for ...
 *
 * PHP Version: PHP 5
 *
 * @category Class
 * @package  Brs
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
class LocationHeaderConstraint extends HttpHeaderConstraint
{

    protected $expectedLocation = '';


    /**
     * Constructs the object
     *
     * @param string $expectedLocation the expected location
     *
     * @return HttpHeaderConstraint
     */
    public function __construct($expectedLocation)
    {
        parent::__construct('Location');
        $this->expectedLocation = $expectedLocation;

    }//end __construct()


    /**
     * Return the expected value
     *
     * @return string
     */
    public function whatItShouldBe()
    {
        return $this->expectedLocation;

    }//end whatItShouldBe()


    /**
     * Return if the expected value is the actual $headerValue
     *
     * @param string $headerValue The header value.
     *
     * @return bool
     */
    public function isWhatItShouldBe($headerValue)
    {
        return $headerValue === $this->whatItShouldBe();

    }//end isWhatItShouldBe()


}//end class

?>