<?php
/**
 * AllowHeaderConstraint.php
 *
 * Holds the AllowHeaderConstraint class
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
 * The AllowHeaderConstraint class is responsible for ...
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
class AllowHeaderConstraint extends HttpHeaderConstraint
{

    protected $expectedMethods = array();


    /**
     * Constructs the object
     *
     * @param string $expectedMethods The methods expected.
     *
     * @return HttpHeaderConstraint
     */
    public function __construct(array $expectedMethods)
    {
        parent::__construct('Allow');
        sort($expectedMethods);
        $this->expectedMethods = $expectedMethods;

    }//end __construct()


    /**
     * Get the expected value.
     *
     * @return string
     */
    public function whatItShouldBe()
    {
        return strtoupper(implode(', ', $this->expectedMethods));

    }//end whatItShouldBe()


    /**
     * Evaluate.
     *
     * @param string $headerValue The header value to evaluate.
     *
     * @return bool
     */
    public function isWhatItShouldBe($headerValue)
    {
        $m = explode(', ', strtoupper($headerValue));
        sort($m);
        $methods = implode(', ', $m);
        return $methods === $this->whatItShouldBe();

    }//end isWhatItShouldBe()


}//end class

?>