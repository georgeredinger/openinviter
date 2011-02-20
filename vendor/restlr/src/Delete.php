<?php
/**
 * Delete.php
 *
 * Holds the Delete interface
 *
 * PHP Version: PHP 5
 *
 * @category File
 * @package  Restlr
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
 * The Delete interface declares what is needed to implement to handle DELETE
 * requests
 *
 * PHP Version: PHP 5
 *
 * @category Interface
 * @package  Restlr
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
interface Delete
{


    /**
     * Handles DELETE requests
     *
     * @return void
     */
    public function executeDelete();


}//end class

?>