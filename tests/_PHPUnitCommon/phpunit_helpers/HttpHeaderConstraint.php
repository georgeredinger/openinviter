<?php
/**
 * HttpHeaderConstraint.php
 *
 * Holds the HttpHeaderConstraint class
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
 * The HttpHeaderConstraint class is responsible for ...
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
abstract class HttpHeaderConstraint extends PHPUnit_Framework_Constraint
{
    const ERROR_NO_SUCH_HEADER = 1;
    const ERROR_EVAL = 2;

    /**
     * @var const error level
     */
    private $_error = 0;

    /**
     * @var int The recieved code
     */
    private $_recievedValue;

    /**
     * @var string Header name
     */
    protected $headerName;


    /**
     * Constructs the object
     *
     * @param string $headerName The header name to care about
     *
     * @return HttpHeaderConstraint
     */
    public function __construct($headerName)
    {
        $this->headerName = $headerName;

    }//end __construct()


    /**
     * Evaluates
     *
     * @param HttpResponse $other The response to analyze
     *
     * @return bool
     */
    public final function evaluate($other)
    {
        if (false === isset($other->headers[$this->headerName])) {
            $this->_error = self::ERROR_NO_SUCH_HEADER;
            return false;
        }

        $this->_recievedValue = $other->headers[$this->headerName];

        return $this->isWhatItShouldBe($this->_recievedValue);

    }//end evaluate()


    /**
     * Return the error message
     *
     * @return string
     */
    public final function toString()
    {
        if ($this->_error === self::ERROR_NO_SUCH_HEADER) {
            $msg  = 'The http response did not contain the requested header: ';
            $msg .= $this->headerName;
        } else {
            $msg  = 'The http response header value '.$this->_recievedValue;
            $msg .= ' is not '.$this->whatItShouldBe();
        }

        return $msg;

    }//end toString()


    /**
     * Evaluate
     *
     * @param string $headerValue The header value to verify.
     *
     * @return bool
     */
    abstract public function isWhatItShouldBe($headerValue);


    /**
     * Returns the expected value
     *
     * @return string
     */
    abstract public function whatItShouldBe();


}//end class

?>