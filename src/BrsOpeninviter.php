<?php
/**
 * BrsOpeninviter.php
 *
 * Holds the BrsOpeninviter class
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
 * The BrsOpeninviter class enhances openinviter with configuration injection
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
class BrsOpeninviter extends openinviter implements OpenInviterInterface
{


    /**
     * Enhanced constructor
     *
     * @param array $config The system config
     *
     * @return openinviter
     */
    public function __construct(array $config=array())
    {
        parent::__construct();
        $this->settings = array_merge($this->settings, $config);

    }//end __construct()


}//end class

?>