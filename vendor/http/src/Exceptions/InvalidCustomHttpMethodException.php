<?php
/**
 * InvalidCustomHttpMethodException.php
 *
 * Holds the InvalidCustomHttpMethodException class
 *
 * PHP Version: 5
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
 * 
 * @link     http://www.assembla.com/spaces/p-pex
 */

/**
 * The InvalidCustomHttpMethodException happens when a custom method was set
 * with a standard http method
 *
 * PHP Version: 5
 *
 * @category Class
 * @package  Http
 * @author   meza <meza@meza.hu>
 * @license  GPLv3 <http://www.gnu.org/licenses/>
 * @link     http://www.assembla.com/spaces/p-pex
 */
class InvalidCustomHttpMethodException extends Exception
{


    /**
     * Creates the Exception by setting the message
     *
     * @param string $method The called http method
     *
     * @return InvalidCustomHttpMethodException
     */
    public function  __construct($method)
    {
        $this->message = $method.' is a standard method, don\'t use as custom';

    }//end __construct()


}//end class

?>
