<?php
/**
 * Composite.php
 *
 * Holds the Composite interface
 *
 * PHP Version: PHP 5
 *
 * @category File
 * @package  Interfaces
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
 * The Composite interface notes a Composite type
 *
 * PHP Version: PHP 5
 *
 * @category Interface
 * @package  Interfaces
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
interface Composite
{


    /**
     * Adds an item to the collection
     *
     * @param CompositeItem $item The item to add
     *
     * @return string
     */
    public function addItem(CompositeItem $item);


    /**
     * Retrieves a named item
     *
     * @param string $name The name of the CompositeItem
     *
     * @return CompositeItem
     */
    public function getItem($name);


    /**
     * Checks if the named CompositeItem exists in the collection
     *
     * @param string $name The name of the CollectionItem
     *
     * @return bool
     */
    public function hasItem($name);


    /**
     * Removes the named CompositeItem from the collection
     *
     * @param string $name The name of the CompositeItem
     *
     * @return bool
     */
    public function deleteItem($name);


    /**
     * Lists all items in the Composite
     *
     * @return array
     */
    public function listItems();


    /**
     * Calls the given callback on each of the items.
     * The given callback will be called with 2 parameters.
     * * CompositeItem referring the current CompositeItem
     * * Composite referring the current composite object
     *
     * @param callback $callback The callback to call
     * @param array    $params   The params to give to the callback
     *
     * @return void
     */
    public function each($callback, array $params=array());


}//end interface

?>