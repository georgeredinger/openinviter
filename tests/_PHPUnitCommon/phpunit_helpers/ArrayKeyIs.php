<?php
/**
 * Holds the ArrayKeyIs class
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
 * Constraint that asserts that a specific key should be present in an array
 * and the value corresponding to this key should be matched by the other constraint
 * passed in the constructor
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
class ArrayKeyIs extends PHPUnit_Framework_Constraint_ArrayHasKey
{

    /**
     * @var PHPUnit_Framework_Constraint the constraint that gets applied to
     *                                   the element of the array corresponding
     *                                   to the specific $key
     */
    private $_constraint;


    /**
     * Constructor
     *
     * @param int|string                   $arrayKey   the key
     * @param PHPUnit_Framework_Constraint $constraint the constraint
     *
     * @return PHPUnit_Framework_Constraint
     */
    public function __construct(
        $arrayKey,
        PHPUnit_Framework_Constraint $constraint
    ) {
        parent::__construct($arrayKey);
        $this->_constraint = $constraint;

    }//end __construct()


    /**
     * Evaluates the constraint
     *
     * @param mixed $other the variable which is evaluated against this constraint
     *
     * @return bool
     */
    public function evaluate($other)
    {
        $isCorrect = $this->_constraint->evaluate($other[$this->key]);
        return parent::evaluate($other) && $isCorrect;

    }//end evaluate()


    /**
     * A human-readable textual representation of this constraint
     *
     * @return string
     */
    public function toString()
    {
        $value = $this->_constraint->toString();
        return parent::toString().' and the corresponding value '.$value;

    }//end toString()


}//end class

?>