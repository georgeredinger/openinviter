<?php
/**
 * CommonTestCaseBase.php
 *
 * Holds the CommonTestCaseBase class
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
 * The CommonTestCaseBase class the base for all project tests
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
class CommonTestCaseBase extends MockAmendingTestCaseBase
{

    /**
     * @var string The root dir of the tests
     */
    protected static $testRoot;

    /**
     * @var string The source root dir
     */
    protected static $srcRoot;

    /**
     * @var string The vendor root dir
     */
    protected static $vendorRoot;

    /**
     * @var string The root of the helper files
     */
    protected static $filesRoot;


    /**
     * Gets called before the tests of the child class. Switches error reporting
     * to the (usually more strict) test level
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$testRoot   = realpath(dirname(__FILE__).'/../..');
        self::$srcRoot    = realpath(self::$testRoot.'/../src');
        self::$vendorRoot = realpath(self::$testRoot.'/../vendor');
        self::$filesRoot  = realpath(self::$testRoot.'/_files');

    }//end setUpBeforeClass()


}//end class

?>