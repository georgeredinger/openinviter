<?php
/**
 * HttpStatusConstraint.php
 *
 * Holds the HttpStatusConstraint class
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
 * The HttpStatusConstraint class is responsible for ...
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
abstract class HttpStatusConstraint extends PHPUnit_Framework_Constraint
{

    /**
     * @var int The recieved code
     */
    private $_recievedCode;


    /**
     * Evaluates
     *
     * @param HttpResponse $other The response to analyze
     *
     * @return bool
     */
    public final function evaluate($other)
    {
        $this->_recievedCode = $other->code;
        return $other->code === $this->getExpectedCode();

    }//end evaluate()


    /**
     * Return the error message
     *
     * @return string
     */
    public final function toString()
    {
        $msg  = 'The http response code '.$this->_recievedCode;
        $msg .= ' is not '.$this->getExpectedCode();

        return $msg;

    }//end toString()


    /**
     * Return the expected code
     *
     * @return int
     */
    abstract public function getExpectedCode();


}//end class

?>