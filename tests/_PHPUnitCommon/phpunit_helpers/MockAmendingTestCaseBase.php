<?php
/**
 * Holds the MockAmendingTestCase class
 *
 * PHP version: 5.2
 *
 * @category File
 * @package  Testhelper
 *
 * @author   fqqdk <fqqdk@clusterone.hu>
 * @license  GPL3.0
 *                    GNU GENERAL PUBLIC LICENSE
 *                       Version 3, 29 June 2007
 *
 * Copyright (C) 2007 Free Software Foundation, Inc. <http://fsf.org/>
 * Everyone is permitted to copy and distribute verbatim copies
 * of this license document, but changing it is not allowed.
 * @link     http://www.assembla.com/spaces/p-pex
 */

require_once dirname(__file__).'/MockWrapper.php';
require_once dirname(__file__).'/CollectionAllOfThem.php';
require_once dirname(__file__).'/ArrayKeyIs.php';

/**
 * Test case base class that adds extra constraints and utilities. Its main
 * purpose is to override the getMock() method as a workaround for a bug.
 *
 * @category Class
 * @package  Testhelper
 *
 * @author   fqqdk <fqqdk@clusterone.hu>
 * @license  GPL3.0
 *                    GNU GENERAL PUBLIC LICENSE
 *                       Version 3, 29 June 2007
 *
 * Copyright (C) 2007 Free Software Foundation, Inc. <http://fsf.org/>
 * Everyone is permitted to copy and distribute verbatim copies
 * of this license document, but changing it is not allowed.
 * @link     http://www.assembla.com/spaces/p-pex
 * @see      getMock()
 */
abstract class MockAmendingTestCaseBase extends PHPUnit_Framework_TestCase
{

    /**
     * @var int the old error reporting level
     */
    private static $_oldErrorReporting;


    /**
     * Gets called before the tests of the child class. Switches error reporting
     * to the (usually more strict) test level
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        self::$_oldErrorReporting = error_reporting(E_TESTLEVEL);

    }//end setUpBeforeClass()


    /**
     * Gets called after the tests of the child class have run. Restores the
     * (usually less strict) loader error reporting level.
     *
     * @return void
     */
    public static function tearDownAfterClass()
    {
        error_reporting(self::$_oldErrorReporting);

    }//end tearDownAfterClass()


    /**
     * Mocks a certain class and ensures that no methods beyond the given list
     * will be called.
     *
     * @param string        $className         the name of the class to mock
     * @param array(string) $methodsToBeCalled the methods that are expected to be called
     *                                         all the other methods of the object will
     *                                         have a 'never' expectation setup automagically
     *
     * @return object
     */
    protected function mock($className, array $methodsToBeCalled=array())
    {
        $methodsOfObject = array();
        $rc              = new ReflectionClass($className);
        foreach ($rc->getMethods() as $method) {
            if (true === $this->_isMockable($method)) {
                $methodsOfObject[] = $method->getName();
            }
        }

        $mockObject = parent::getMock($className, $methodsOfObject, array(), '', false, false, true);

        $result = new MockObjectWrapper($mockObject);

        if (false === empty($methodsToBeCalled)) {
            $this->expectNoMethodCallsOn($result, $methodsToBeCalled);
        }

        return $result;

    }//end mock()


    /**
     * Checks that a method can be mocked
     *
     * @param ReflectionMethod $method the method being checked
     *
     * @return bool
     */
    private function _isMockable(ReflectionMethod $method)
    {
        return $method->isPublic()
            && !$method->isStatic()
            && !$method->isConstructor();

    }//end _isMockable()


    /**
     * Creates a constraint over a key of an array
     *
     * @param mixed $arrayKey   the key of the array
     * @param mixed $arrayValue a constraint over the value for the key. if it's not
     *                          an instance of PHPUnit_Framework_Constraint, an equality
     *                          constraint is created
     *
     * @return ArrayKeyIs
     */
    protected function arrayKeyIs($arrayKey, $arrayValue)
    {
        if ($arrayValue instanceof PHPUnit_Framework_Constraint) {
            $constraint = $arrayValue;
        } else {
            $constraint = new PHPUnit_Framework_Constraint_IsEqual($arrayValue);
        }

        $result = new ArrayKeyIs($arrayKey, $constraint);
        return $result;

    }//end arrayKeyIs()


    /**
     * Sets up an expectation on the mock object that no methods will be called
     * on it except for those stated explicitly in the second argument
     *
     * @param PHPUnit_Framework_MockObject_MockObject $mock       the mock object
     * @param array(string)                           $exceptions the exceptions
     *
     * @return void
     */
    protected function expectNoMethodCallsOn(
        PHPUnit_Framework_MockObject_MockObject $mock,
        $exceptions=array()
    ) {
        if (false === is_array($exceptions)) {
            $exceptions = array($exceptions);
        }

        $mock->expects($this->never())->method(
            $this->logicalNot(
                call_user_func_array(
                    array(
                     $this,
                     'logicalOr',
                    ),
                    $exceptions
                )
            )
        );

    }//end expectNoMethodCallsOn()
    

    /**
     * Creates a mock object that is not expected to be used at all. The most
     * common usecase for this method is testing factories, constructors, and
     * mocked but typehinted methods.
     *
     * @param string $className the name of class to mock and setup
     *
     * @return object an instance of $className
     */
    protected function dummy($className)
    {
        $mock = $this->mock($className);
        $mock->expects($this->never())->method($this->anything());
        return $mock->mock;

    }//end dummy()


    /**
     * Creates an Constraint that can evaluate an array, by checking that certain
     * keys exists in it and the values corresponding these keys fulfill other
     * constraints.
     *
     * @param array $constraintMap associative array containing constraints
     *                             for the values for certain keys
     *                             of the target array
     *
     * @return PHPUnit_Framework_Constraint
     */
    protected function arrayConstraint(array $constraintMap)
    {
        $result = array();
        foreach ($constraintMap as $arrayKey => $constraint) {
            $result[] = $this->arrayKeyIs($arrayKey, $constraint);
        }

        return call_user_func_array(array($this, 'logicalAnd'), $result);

    }//end arrayConstraint()


    /**
     * Returs the apsolute path of the class in question
     * This method can be used to determine the filepath that gets
     * generated into an SQL query
     *
     * @param string $className the name of the class
     *
     * @return string
     */
    protected function getPathOfClass($className)
    {
        $rc = new ReflectionClass($className);
        return realpath($rc->getFileName());

    }//end getPathOfClass()


    /**
     * Whether we should use local domains *.lh or omit it and use the web
     *
     * @return boolean
     */
    protected function isIntegrationRun()
    {
        return isset($GLOBALS['__testRunType']) && 'integration' == $GLOBALS['__testRunType'];

    }//end isIntegrationRun()


}//end class

?>