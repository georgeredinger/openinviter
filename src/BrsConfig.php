<?php
/**
 * Config value object for Brs app
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
 * Configuration value object
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
class BrsConfig
{


    public function realmName()
    {
        return 'BRS System';
    }


    /**
     * Return open inviter config array
     *
     * @return array
     */
    public function getOpenInviterConfigArray()
    {
        return array(
                'username'        => 'myixora',
                'private_key'     => 'b2426a7c739dafa725d7fc50f9b164c3',
                'cookie_path'     => '/tmp',
                'message_body'    => 'You are invited to http://myixora.com',
                'message_subject' => ' is inviting you to http://myixora.com',
                'stats'           => false,
               );

    }//end getOpenInviterConfigArray()


}//end class

?>