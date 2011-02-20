<?php
/**
 * RouteCollection.php
 *
 * Holds the RouteCollection class
 *
 * PHP Version: PHP 5
 *
 * @category File
 * @package  Handlr
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
 * The RouteCollection class holds all instances of the Route objects.
 * It acts as a Composite
 *
 * PHP Version: PHP 5
 *
 * @category Class
 * @package  Handlr
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
class RouteCollection implements Composite
{

    /**
     * @var CompositeItem[] list of the items
     */
    protected $items = array();


    /**
     * Adds an item to the collection
     *
     * @param CompositeItem $item The item to add
     *
     * @return string The item's name
     */
    public function addItem(CompositeItem $item)
    {
        $this->items[$item->getName()] = $item;

        return $item->getName();

    }//end addItem()


    /**
     * Retrieves a named item
     *
     * @param string $name The name of the CompositeItem
     *
     * @return CompositeItem
     */
    public function getItem($name)
    {
        if (false === $this->hasItem($name)) {
            return false;
        }

        return $this->items[$name];

    }//end getItem()


    /**
     * Checks if the named CompositeItem exists in the collection
     *
     * @param string $name The name of the CollectionItem
     *
     * @return bool
     */
    public function hasItem($name)
    {
        return isset($this->items[$name]);

    }//end hasItem()


    /**
     * Removes the named CompositeItem from the collection
     *
     * @param string $name The name of the CompositeItem
     *
     * @return bool
     */
    public function deleteItem($name)
    {
        unset($this->items[$name]);
        return true;

    }//end deleteItem()


    /**
     * List all routes
     *
     * @return array
     */
    public function listItems()
    {
        return $this->items;

    }//end listItems()


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
    public function each($callback, array $params=array())
    {
        foreach ($this->items as $item) {
            $tmpArgs = $params;
            array_unshift($tmpArgs, $item, $this);
            call_user_func_array(
                $callback,
                $tmpArgs
            );
        }

    }//end each()


}//end class

?>