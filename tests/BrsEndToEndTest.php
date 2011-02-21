<?php
/**
 * BrsEndToEndTest.php
 *
 * Holds the BrsEndToEndTest class
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
 * The BrsEndToEndTest class is responsible for ...
 *
 * PHP Version: PHP 5
 *
 * @group Acceptance
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
class BrsEndToEndTest extends HttpTestCaseBase
{


    /**
     * Sets up the test
     *
     * @return void
     */
    public function setUp()
    {
        error_reporting(E_ALL);
        $this->hostname = 'http://brs-api.radsoft.com.lh';

        if (true === isset($_ENV['BRS_OPENINVITER_HOST'])) {
            $this->hostname = $_ENV['BRS_OPENINVITER_HOST'];
        }

    }//end setUp()


    /**
     * Tets that 404 is given when resource not found
     * 
     * @return void
     */
    public function test404()
    {
        $resource = $this->hostname.'/unexistingResource';
        $response = $this->get($resource);

        $this->assertEquals(404, $response->code);

    }//end test404()


}//end class

?>