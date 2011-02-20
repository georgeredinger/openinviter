<?php
/**
 * Contains collection composite constraint class.
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

PHPUnit_Util_Filter::addFileToFilter(__file__);

/**
 * Constraint that asserts that the other constraint passed via the constructor
 * matches every element of a collection
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
class CollectionAllOfThem extends PHPUnit_Framework_Constraint
{

    /**
     * @var PHPUnit_Framework_Constraint constraint on the elements
     */
    protected $constraint;


    /**
     * Constructor
     *
     * @param PHPUnit_Framework_Constraint $constraint constraint on the elements
     *
     * @return PHPUnit_Framework_Constraint
     */
    public function __construct(PHPUnit_Framework_Constraint $constraint)
    {
        $this->constraint = $constraint;

    }//end __construct()


    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other Value or object to evaluate.
     *
     * @return bool
     */
    public function evaluate($other)
    {
        foreach ($other as $item) {
            if (false === $this->constraint->evaluate($item)) {
                return false;
            }
        }

        return true;

    }//end evaluate()


    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        return 'every element of the collection "'.$this->constraint->toString().'"';

    }//end toString()


}//end class

?>