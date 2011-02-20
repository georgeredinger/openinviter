<?php
/**
 * HttpHeaderComposite.php
 *
 * Holds the HttpHeaderComposite class
 *
 * PHP Version: PHP 5
 *
 * @category File
 * @package  Http
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
 * The HttpHeaderComposite class holds a collection of HttpHeaders
 *
 * PHP Version: PHP 5
 *
 * @category Class
 * @package  Http
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
class HttpHeaderComposite implements Composite
{

    /**
     * @var array the list of HttpHeader objects
     */
    private $_items = array();

    /**
     * Adds an item to the collection
     *
     * @param CompositeItem $item The item to add
     *
     * @return string
     */
    public function addItem(CompositeItem $item)
    {
        $this->_items[$item->getName()] = $item;

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
        if (false === $this->hasItem($item)) {
            return false;
        }

        return $this->_items[$item->getName()];

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
        return isset($this->_items[$item->getName()]);

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
        if (true === $this->hasItem($item)) {
            unset($this->_items[$item->getName()]);
        }

    }//end deleteItem()


    /**
     * Lists all items in the Composite
     *
     * @return array
     */
    public function listItems()
    {
        return $this->_items;

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
        foreach ($this->_items as $item) {
            array_unshift($params, $item);
            call_user_func_array($callback, $params);
        }

    }//end each()


}//end class

?>