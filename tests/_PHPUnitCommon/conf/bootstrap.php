<?php
/**
 * Bootstrap file for PHPUnit tests
 *
 * PHP VERSION 5.2
 *
 * @category File
 * @package  Testhelper
 *
 * @author fqqdk <fqqdk@clusterone.hu>
 * @author meza <meza@meza.hu>
 * @license  GPL3.0
 *                    GNU GENERAL PUBLIC LICENSE
 *                       Version 3, 29 June 2007
 *
 * Copyright (C) 2007 Free Software Foundation, Inc. <http://fsf.org/>
 * Everyone is permitted to copy and distribute verbatim copies
 * of this license document, but changing it is not allowed.
 */

/**
 * Encapsulates the bootstrapping logic
 *
 * @return void
 */
function __bootstrap()
{
	if (false == isset($GLOBALS['__testLevel'])) {
		print 'Defining E_TESTLEVEL as E_ALL | E_DEPRECATED | E_STRICT '.PHP_EOL;
		print 'You can use a phpunit.xml configuration file to override this' . PHP_EOL;
		$testErrorLevel = E_ALL | E_DEPRECATED | E_STRICT;
	} else {
		$testErrorLevel = $GLOBALS['__testLevel'];
	}

	/**
	 * @final the error_reporting level to use when test code runs.
	 *        this setting is only enforced by MockAmendingTestCaseBase
	 */
	define('E_TESTLEVEL', $testErrorLevel);

	if (false == isset($GLOBALS['__loaderLevel'])) {
		print 'Defining E_LOADERLEVEL as ' .
			'E_ALL &~ E_NOTICE &~ E_WARNING &~ E_DEPRECATED &~ E_STRICT ' . PHP_EOL;
		print 'You can use a phpunit.xml configuration file to override this' . PHP_EOL;
		$loadErrorLevel = E_ALL &~ E_NOTICE &~ E_DEPRECATED &~ E_STRICT;
	} else {
		$loadErrorLevel = $GLOBALS['__loaderLevel'];
	}

	/**
	 * @final the default error_reporting level that is used every other time
	 *        - in the class autoloaders
	 *        - when PHPUnit includes files for coverage report
	 *        - when PHPUnit includes the testcase classes
	 */
	define('E_LOADERLEVEL', $loadErrorLevel);
    chdir(dirname(__file__). '/../../../');
    

    require_once 'PHPUnit/Framework.php';
    require_once 'vfsStream/vfsStream.php';

	
	error_reporting(E_LOADERLEVEL);
	spl_autoload_register(array(new Autoloader, 'loadClass'));
	spl_autoload_register(array(new Autoloader, 'loadClass2'));
}

class Autoloader
{
    public function loadClass($className)
    {
        $dirs = array(
                'tests',
                'tests/src',
                'tests/_PHPUnitCommon',
                'tests/_PHPUnitCommon/conf',
                'tests/_PHPUnitCommon/conf/hudson',
                'tests/_PHPUnitCommon/phpunit_helpers',
                '.',
               );
        foreach ($dirs as $dir) {
            $filename = getcwd().'/'.$dir.'/'.$className.'.php';
            if (file_exists($filename)) {
                require_once $filename;
                return true;
            }
        }
        return false;
    }

    public function loadClass2($className)
    {
        static $classes;
        if (empty($classes)) {
            $classes = require getcwd().'/src/classes.php';
        }
        if (false === array_key_exists($className, $classes)) {
            if (true === array_key_exists('class.'.$className, $classes)) {
                $className = 'class.'.$className;
            } else if (true === array_key_exists('interface.'.$className, $classes)) {
                $className = 'interface.'.$className;
            } else {
                return false;
            }
        }
        $newName = str_replace('\\','/', $className);
        $file = getcwd().'/src'.$classes[$className].basename($newName).'.php';
        return require_once $file;

    }
}

__bootstrap();

?>