<?php
/**
 * Holds the classes needed to amend PHPUnit's awkward memory-hogging behaviour,
 * namely that it tries to dump all the actual parameters of a mocked method
 * using print_r() when an expectation for said method has not been met.
 * This behaviour causes OutOfMemory errors when an object with a large recursive
 * structure is among said actual parameters, like e.g. an Exception with a huge
 * stack trace. Who would have thought, that Exceptions, like any other objects
 * could be passed around? And that stack traces of these Exceptions could become HUGE
 * because they are usually invoked from a test, with HUGE, HEAVILY RECURSIVE objects
 * like other mock objects are all around?
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

/**
 * This class is used to wrap a PHPUnit_Framework_MockObject_InvocationMocker
 * instance to provide a way to inject wrapped instances of
 * PHPUnit_Framework_MockObject_Matcher_Invocation to it.
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
 */
class InvocationMockerWrapper
implements PHPUnit_Framework_MockObject_Stub_MatcherCollection
{

    /**
     * @var PHPUnit_Framework_MockObject_InvocationMocker the wrapped object
     */
    private $_delegate;


    /**
     * Constructor
     *
     * @param PHPUnit_Framework_MockObject_InvocationMocker $delegate the object
     *                                                                to wrap
     *
     * @return InvocationMockerWrapper
     */
    public function __construct(PHPUnit_Framework_MockObject_InvocationMocker $delegate)
    {
        $this->_delegate = $delegate;

    }//end __construct()


    /**
     * Wraps the passed matcher instance in a MatcherWrapper and passes it to the delegate
     *
     * @param PHPUnit_Framework_MockObject_Matcher_Invocation $matcher the matcher to wrap
     *
     * @return void
     */
    public function addMatcher(PHPUnit_Framework_MockObject_Matcher_Invocation $matcher)
    {
        $newMatcher = new MatcherWrapper($matcher);
        $this->_delegate->addMatcher($newMatcher);

    }//end addMatcher()


}//end class

/**
 * This class is used to wrap a PHPUnit_Framework_MockObject_MockObject instance
 * to provide a way to inject wrapped instances of InvocationMockerWrapper
 * to the PHPUnit_Framework_MockObject_Builder_InvocationMocker instance so that
 * after a call to expects() an amended expectation builder can be used by test
 * method that uses the wrapped mock object.
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
 */
class MockObjectWrapper implements PHPUnit_Framework_MockObject_MockObject
{

    /**
     * @var PHPUnit_Framework_MockObject_MockObject the wrapped mock object
     */
    public $mock;


    /**
     * Constructor
     *
     * @param PHPUnit_Framework_MockObject_MockObject $mock the mock object to wrap
     *
     * @return MockObjectWrapper
     */
    public function __construct(PHPUnit_Framework_MockObject_MockObject $mock)
    {
        $this->mock = $mock;

    }//end __construct()


    /**
     * Uses the passed matcher to create an amended expectation builder to be
     * used by the test method that is the client of the wrapped mock object
     *
     * @param PHPUnit_Framework_MockObject_Matcher_Invocation $matcher the matcher
     *
     * @see PHPUnit_Framework_MockObject_MockObject::expects()
     * @see PHPUnit_Framework_MockObject_Matcher_Invocation
     *
     * @return PHPUnit_Framework_MockObject_Builder_InvocationMocker the amended
     *                                                               expectation
     *                                                               builder
     */
    public function expects(PHPUnit_Framework_MockObject_Matcher_Invocation $matcher)
    {
        $wrapper = new InvocationMockerWrapper(
            $this->mock->__phpunit_getInvocationMocker());
        $mocker  = new PHPUnit_Framework_MockObject_Builder_InvocationMocker(
            $wrapper,
            $matcher
        );
        return $mocker;

    }//end expects()


    /**
     * Simply delegates
     *
     * @return PHPUnit_Framework_MockObject_InvocationMocker
     */
    public function __phpunit_getInvocationMocker()
    {
        return $this->mock->__phpunit_getInvocationMocker();

    }//end __phpunit_getInvocationMocker() 


    /**
     * Simply delegates
     *
     * @return void
     * @throws PHPUnit_Framework_ExpectationFailedException
     */
    public function __phpunit_verify() {
        return $this->mock->__phpunit_verify();

    }//end __phpunit_verify()


}//end class


/**
 * This class is used to wrap a PHPUnit_Framework_MockObject_Matcher_Invocation instance
 * to provide a way to inject wrapped instances of PHPUnit_Framework_MockObject_Invocation
 * to the wrapped object.
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
 */
class MatcherWrapper implements PHPUnit_Framework_MockObject_Matcher_Invocation
{

    /**
     * @var PHPUnit_Framework_MockObject_Matcher_Invocation the wrapped matcher
     */
    private $_delegate;


    /**
     * Constructor
     *
     * @param PHPUnit_Framework_MockObject_Matcher_Invocation $delegate the matcher to wrap
     *
     * @return MatcherWrapper
     */
    public function __construct(PHPUnit_Framework_MockObject_Matcher_Invocation $delegate)
    {
        $this->_delegate = $delegate;

    }//end __construct()


    /**
     * This method is used to inject wrapped invocations (instances of
     * PHPUnit_Framework_MockObject_Invocation wrapped with InvocationWrapper)
     * to the delegate
     *
     * @param PHPUnit_Framework_MockObject_Invocation $invocation the invocation
     *
     * @override
     *
     * @return mixed
     */
    public function invoked(PHPUnit_Framework_MockObject_Invocation $invocation)
    {
        $wrapper = new InvocationWrapper($invocation);
        return $this->_delegate->invoked($wrapper);

    }//end invoked()


    /**
     * This method is used to inject wrapped invocations (instances of
     * PHPUnit_Framework_MockObject_Invocation wrapped with InvocationWrapper)
     * to the delegate
     *
     * @param PHPUnit_Framework_MockObject_Invocation $invocation the invocation
     *
     * @override
     *
     * @return bool
     */
    public function matches(PHPUnit_Framework_MockObject_Invocation $invocation)
    {
        $wrapper = new InvocationWrapper($invocation);
        return $this->_delegate->matches($wrapper);

    }//end matches()


    /**
     * Just a simple delegating method
     *
     * @return string
     */
    public function toString()
    {
        return $this->_delegate->toString();

    }//end toString()


    /**
     * Just a simple delegating method
     *
     * @return void
     */
    public function verify()
    {
        return $this->_delegate->verify();

    }//end verify()


}//end class

/**
 * Wraps an instance of PHPUnit_Framework_MockObject_Invocation to provide a way
 * to intercept calls to its toString() method which would indirectly cause
 * out of memory errors when any of the actual parameters of the invocation represented
 * by the wrapped object is too complex to simply print_r() them.
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
 */
class InvocationWrapper extends PHPUnit_Framework_MockObject_Invocation
{

    /**
     * @var mixed the object
     */
    public $object;

    /**
     * @var string the class name
     */
    public $className;

    /**
     * @var string the name of the method
     */
    public $methodName;

    /**
     * @var array the actual parameters of the invocation
     */
    public $parameters;


    /**
     * Constructor
     *
     * @param PHPUnit_Framework_MockObject_Invocation $delegate the object to wrap
     *
     * @return InvocationWrapper
     */
    public function __construct(PHPUnit_Framework_MockObject_Invocation $delegate)
    {
        $this->object     = $delegate->object;
        $this->className  = $delegate->className;
        $this->methodName = $delegate->methodName;
        $this->parameters = $delegate->parameters;

    }//end __construct()


    /**
     * Prints a SHORT, human readable summary of the invocation.
     *
     * @return string
     */
    public function toString()
    {
        return sprintf('%s::%s(%s)',
            $this->className,
            $this->methodName,
            join(
            ', ',
            array_map(
            array($this, 'shortenedExport'),
            $this->parameters
            )
            )
        );

    }//end toString()


    /**
     * Copypasted and amended version of PHPUnit_Util_Type::shortenedExport()
     * In this version, every call to PHPUnit_Util_Type::toString() is called
     * with true passed to second parameter ($short) which PHPUnit's version of this
     * method does not do.
     *
     * @param mixed $variable the variable to output
     *
     * @return string
     */
    public function shortenedExport($variable)
    {
        if (is_string($variable)) {
            return PHPUnit_Util_Type::shortenedString($variable);
        } elseif (is_array($variable)) {
            return $this->shortenedExportArray($variable);
        } else if (is_object($variable)) {
            return get_class($variable) . '(...)';
        }

        return PHPUnit_Util_Type::toString($variable, true);

    }//end shortenedExport()


    /**
     * Exports an array variable
     *
     * @param array $variable the variable to export
     *
     * @return string
     */
    private function shortenedExportArray(array $variable)
    {
        if (count($variable) == 0) {
            return 'array()';
        }

        $a1 = array_slice($variable, 0, 1, true);
        $k1 = key($a1);
        $v1 = $a1[$k1];

        if (is_string($v1)) {
            $v1 = PHPUnit_Util_Type::shortenedString($v1);
        } elseif (is_array($v1)) {
            $v1 = 'array(...)';
        } else {
            $v1 = PHPUnit_Util_Type::toString($v1, true);
        }

        $a2 = false;

        if (count($variable) > 1) {
            $a2 = array_slice($variable, -1, 1, true);
            $k2 = key($a2);
            $v2 = $a2[$k2];

            if (is_string($v2)) {
                $v2 = PHPUnit_Util_Type::shortenedString($v2);
            } elseif (is_array($v2)) {
                $v2 = 'array(...)';
            } else {
                $v2 = PHPUnit_Util_Type::toString($v2, true);
            }
        }

        $text = 'array( ' . PHPUnit_Util_Type::toString($k1, true) . ' => ' . $v1;

        if ($a2 !== false) {
            $text .= ', ..., ' . PHPUnit_Util_Type::toString($k2, true) . ' => ' . $v2 . ' )';
        } else {
            $text .= ' )';
        }

        return $text;

    }//end shortenedExportArray()


}//end class

?>