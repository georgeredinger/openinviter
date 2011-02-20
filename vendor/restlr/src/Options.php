<?php
/**
 * Options.php
 *
 * Holds the Options interface
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
 * The Options interface declares what is needed to implement to handle OPTIONS
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
interface Options
{


    /**
     * Handles OPTIONS requests
     *
     * @return void
     */
    public function executeOptions();


}//end class

?>