<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2006-2008 Wolfgang Zenker <zenker@punkt.de>, Rainer Kuhn <kuhn@punkt.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is 
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
* 
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
* 
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/** 
 * Abstract object collection class for pt_tools
 *
 * $Id$
 *
 * @author      Wolfgang Zenker <zenker@punkt.de>, Rainer Kuhn <kuhn@punkt.de>
 * @since       2006-10-24
 */ 
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */



/**
 * Inclusion of extension specific resources
 */
// a concrete implementation would include the object to create the collection of

/**
 * Inclusion of external resources
 */

/**
 * Abstract object collection class
 *
 * @author      Wolfgang Zenker <zenker@punkt.de>, Rainer Kuhn <kuhn@punkt.de>
 * @since       2006-10-24
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
abstract class tx_sgzlib_collection implements IteratorAggregate, Countable {
    
    /**
     * Properties
     */
    protected $itemsArr = array();    // array containing objects as values
    
    
    
    
    /***************************************************************************
     *   CONSTRUCTOR
     **************************************************************************/
     
    /**
     * Class constructor: creates a object collection object
     *
     * @param   
     * @return  
     * @global  
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-10-24
     */
    // the constructor is the minimum you need to implement

    
    
    
    
    
    /***************************************************************************
     *   IMPLEMENTED METHODS: ITERATORAGGREGATE API METHODS
     **************************************************************************/
     
    /**
     * Defined by IteratorAggregate interface: returns an iterator for the object 
     *
     * @param   void 
     * @return  ArrayIterator     object of type ArrayIterator: Iterator for items within this collection
     * @global  
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-10-05 
     */ 
    public function getIterator() {
        
        $itemIterator = new ArrayIterator($this->itemsArr);
        #trace($itemIterator, 0, '$itemIterator');
        
        return $itemIterator;
        
    }
    
    
    
    /***************************************************************************
     *   IMPLEMENTED METHODS: COUNTABLE INTERFACE API METHODS
     **************************************************************************/
     
    /**
     * Defined by Countable interface: Returns the number of items
     *
     * @param   void 
     * @return  integer     number of items in the items array
     * @global  
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2006-03-10 
     */ 
    public function count() {
        
        return count($this->itemsArr);
        
    }
    
    
    
    /***************************************************************************
     *   IMPLEMENTED METHODS: GENERAL METHODS
     **************************************************************************/
    
    /**
     * Adds one item to the collection
     *
     * @param   object      object to add
     * @param   integer     (optional) array key
     * @return  void
     * @global  
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-10-24
     */
    public function addItem($itemObj, $id=0) { 
        
        if ($id == 0) {
            $this->itemsArr[] = $itemObj;
        } else {
            $this->itemsArr[$id] = $itemObj;
        }
        
    }
    
    /**
     * Deletes one item from the collection
     *
     * @param   integer      id of object to remove
     * @return  void
     * @global  
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-10-05
     */
    public function deleteItem($id) {
        
        if (array_key_exists($id, $this->itemsArr)) {
            unset($this->itemsArr[$id]);
        }
        
    }
    
    /**
     * Clears all items of the collection
     *
     * @param   void
     * @return  void
     * @global  
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-10-05
     */
    public function clearItems() {
        
        $this->itemsArr = array();
        
    }
    
    /**
     * get item from collection by Id
     *
     * @param   integer     Id of Collection Item
     * @return  object      item that has been requested
     * @global  
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2005-10-18
     */
    public function getItemById($id) {
        
        return $this->itemsArr[$id];
        
    }

    /**
     * get item from collection by Index
     *
     * @param   integer     index (position in array) of Collection Item
     * @return  object      item that has been requested
     * @remarks index starts with 0 for first element  
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2007-02-26
     */
    public function getItemByIndex($idx) {

        // check parameters
        $idx = intval($idx);
        if (($idx < 0) ||($idx >= $this->count())) {
            throw new tx_pttools_exception('invalid index', 3);
        }
        $itemArr = array_values($this->itemsArr);
        return $itemArr[$idx];
        
    }
    
    
     
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/classes/base/class.tx_sgzlib_collection.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/classes/base/class.tx_sgzlib_collection.php']);
}

?>
