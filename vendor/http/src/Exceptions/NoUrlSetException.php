<?php
/**
 * NoUrlSetException.php
 *
 * Holds the NoUrlSetException class
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
 * The NoUrlSetException happens, when no url was set for the request
 *
 * PHP Version: 5
 *
 * @category Class
 * @package  Http
 * @author   meza <meza@meza.hu>
 * @license  GPLv3 <http://www.gnu.org/licenses/>
 * @link     http://www.assembla.com/spaces/p-pex
 */
class NoUrlSetException extends Exception
{

    /**
     * @var string exception message
     */
    protected $message = 'No URL set for the request.';

}//end class

?>
