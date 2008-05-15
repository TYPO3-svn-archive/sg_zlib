<?php
/**
 *
 * PHP versions 5
 *
 *  (c) 2007-2008 Stefan Geith (typo3devYYYY@geithware.de)
 *
 * LICENSE:
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * Parts copied from tx_cool/tx_cool_Data.php by
 *              Elmar Hinz <elmar.hinz@team-red.net>
 * This class will be replaced by corresponding tx_cool-class, as soon
 * as 'cool' becomes beta.
 *
 * @package    TYPO3
 * @subpackage sg_lib
 * @author     Stefan Geith (typo3devYYYY@geithware.de)
 * @copyright  2008 Stefan Geith
 * @license    http://www.opensource.org/licenses/lgpl-license.php LGPL
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   61: class tx_sglib_data extends ArrayIterator
 *   90:     public function exists($key)
 *  101:     public function contains($value)
 *  112:     public function isEmpty( )
 *  122:     public function isNotEmpty( )
 *  136:     public function clear( )
 *  149:     public function exchange($arrayOrObject)
 *  164:     public function merge($arrayOrObject)
 *  176:     public function remove($key )
 *  188:     public function set($key, $value)
 *  201:     public function get( $key)
 *  215:     public function select($selection)
 *  232:     public function copyToArray( )
 *  242:     public function getDebugArray($maxCount=0)
 *  271:     public function __toString()
 *
 * TOTAL FUNCTIONS: 14
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_data extends ArrayIterator {

	// Iterator methods
	// current();
	// key();
	// next();
	// rewind();
	// valid();
	// count();
	// append();
	// asort();
	// ksort();
	// uasort();
	// uksort();
	// natsort();
	// natcasesort();

	// Status methods

	/**
	 * Does the given key exist?
	 *
	 * Alias for parent::offsetExists();
	 *
	 * @param	[type]		$key: ...
	 * @return	boolean		TRUE if the given key exists.
	 * @access public
	 * @see ArrayAccess::offsetExists()
	 */
	public function exists($key) {
		return parent::offsetExists($key);
	}

	/**
	 * Does the array contain the given value?
	 *
	 * @param	[type]		$value: ...
	 * @return	boolen		TRUE if the given value is within the array.
	 * @access public
	 */
	public function contains($value) {
		return in_array($value, parent::getArrayCopy());
	}


	/**
	 * Is the array empty?
	 *
	 * @return	boolean		TRUE if the array is empty.
	 * @access public
	 */
	public function isEmpty( ) {
		return (parent::count() == 0);
	}

	/**
	 * Is the array not empty?
	 *
	 * @return	boolean		TRUE if the array is not empty.
	 * @access public
	 */
	public function isNotEmpty( ) {
		return (parent::count() > 0);
	}

	// Setters

	/**
	 * Clear the array.
	 *
	 * Unsets the whole array.
	 *
	 * @return	void
	 * @access public
	 */
	public function clear( ) {
		foreach($this->getArrayCopy() as $key => $value) {
			$this->offsetUnset($key);
		}
	}

	/**
	 * Exchange the whole array.
	 *
	 * @param	mixed		The new array or data object.
	 * @return	void
	 * @access public
	 */
	public function exchange($arrayOrObject) {
		self::clear();
		self::merge($arrayOrObject);
	}

	/**
	 * Merge by keys.
	 *
	 * Adds the values of new keys. Overwrites values of existing keys.
	 * Takes an array or an object as argument.
	 *
	 * @param	mixed		The array or data object to merge.
	 * @return	void
	 * @access public
	 */
	public function merge($arrayOrObject) {
		foreach($arrayOrObject as $key => $value)
			$this->offsetSet($key, $value);
	}

	/**
	 * Unset a single element of the array.
	 *
	 * @param	mixed		The key to unset.
	 * @return	void
	 * @access public
	 */
	public function remove($key ) {
		parent::offsetUnset($key);
	}

	/**
	 * Set a single element of the array.
	 *
	 * @param	mixed		The key.
	 * @param	mixed		The value.
	 * @return	void
	 * @access public
	 */
	public function set($key, $value) {
		parent::offsetSet($key, $value);
	}

	// Getters

	/**
	 * Get a value by key.
	 *
	 * @param	mixed		The key.
	 * @return	mixed		The value.
	 * @access public
	 */
	public function get( $key) {
		return parent::offsetGet($key);
	}

	/**
	 * Get a new object with a subset of the values.
	 *
	 * Selectes the requested key value pairs into a new object of the same (inherited) tx_cool_Data class.
	 * If a string is given it has to be a comma separated list of the keys.
	 *
	 * @param	mixed		String, array or data object with list of keys to select.
	 * @return	object		New object with selection of values.
	 * @access public
	 */
	public function select($selection) {
		if(is_string($selection)) $selection = explode(',', $selection);
		$classname = get_class($this);
		$object = new $classname();
		foreach($selection as $key) $object->offsetSet(trim($key), parent::offsetGet(trim($key)));
		return $object;
	}

	/**
	 * Get a copy of the internal array.
	 *
	 * Alias for parent::getArrayCopy().
	 *
	 * @return	array		The copy of the array.
	 * @access public
	 * @see ArrayIterator::getArrayCopy.
	 */
	public function copyToArray( ) {
		return parent::getArrayCopy();
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$maxCount: ...
	 * @return	[type]		...
	 */
	public function getDebugArray($maxCount=0) {
		$tmp = Array(
			'TYPE'=>gettype($this),
			'CLASS'=>get_class($this),
			'count()'=>count($this),
			);
		if ($maxCount) {
			$dTmp = Array();
			foreach ($this as $key=>$value) {
				$dTmp[$key] = $value;
				$maxCount--;
				if ($maxCount<0) {
					break;
					}
			$tmp['data'] = $dTmp;
			}
		}
		$btr = debug_backtrace();
		if (count($btr)>0) {
			$tmp['File:Line'] = $btr[0]['file'].':'.$btr[0]['line'];
		}
		return ($tmp);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	public function __toString() {
			return 'count('.get_class($this).')='.count($this);
		}

}

?>