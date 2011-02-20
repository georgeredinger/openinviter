<?php
/**
 * InvalidCookieStoreException.php
 *
 * Holds the InvalidCookieStoreException class
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
 * The InvalidCookieStoreException fires, when an invalid cookie store was found
 *
 * PHP Version: 5
 *
 * @category Class
 * @package  Http
 * @author   meza <meza@meza.hu>
 * @license  GPLv3 <http://www.gnu.org/licenses/>
 * @link     http://www.assembla.com/spaces/p-pex
 */
class InvalidCookieStoreException extends Exception
{

    /**
     * @var string exception message
     */
    protected $message = 'The given cookie store is not writable';


    /**
     * Creates the exception
     *
     * @param string $file The file that can't be used as a cookie
     *
     * @return InvalidCookieStoreException
     */
    public function __construct($file)
    {
        $this->message .= '('.realpath($file).')';
    }

}//end class

?>
