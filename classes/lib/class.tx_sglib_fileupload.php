<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2002-2007 Stefan Geith (typo3devYYYY@geithware.de)
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *
 * TOTAL FUNCTIONS: 13
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_fileupload {
	private static $instance = Array();
	private static $instances = Array();

	protected $factoryObj = NULL;
	protected $confObj;
	protected $debugObj;
	protected $defaultDesignator;
	protected $params;
	protected $fileParams;
	protected $fieldMessages = Array();

	protected $dbg = 0;

	protected $name = '';
	protected $type = '';
	protected $tmp_name = '';
	protected $error = '';
	protected $size = '';

	protected function __construct() {}

	private function __clone() {}

	/**
	 * Returns a singlton instance of tx_sglib_params
	 *
	 * @param	string				Designator
	 * @param	tx_sglib_factory	FactoryObj
	 * @return	tx_sglib_params	Instantiated Object
	 */
	
	public static function getInstance($fieldname, tx_sglib_factory $factoryObj=NULL, $index=NULL) {
		if (isset($index)) {
			if (!is_array(self::$instances[$fieldname])) {
				self::$instances[$fieldname] = Array();
			}
			if (!isset(self::$instances[$fieldname][$index])) {
				self::$instances[$fieldname][$index] = new tx_sglib_fileupload();
				self::$instances[$fieldname][$index]->init($factoryObj);
			}
			return (self::$instances[$desfieldnameignator][$index]);
		} else {
			if (!isset(self::$instance[$fieldname])) {
				self::$instance[$fieldname] = new tx_sglib_fileupload();
				self::$instance[$fieldname]->init($factoryObj);
			}
			return (self::$instance[$fieldname]);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$tx_sglib_config $confObj, tx_sglib_debug $debugObj: ...
	 * @return	[type]		...
	 */
	private function init(tx_sglib_factory $factoryObj=NULL) {
		$this->factoryObj = $factoryObj;
		if (is_object($factoryObj)) {
			$this->confObj = $factoryObj->confObj;
			$this->debugObj = $factoryObj->debugObj;
		}
		// $this->cObj = t3lib_div::makeInstance('tslib_cObj');
	}

	/***********************************************************************************************
	 *
	 * Setters
	 *
	 ***********************************************************************************************/

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$tx_sglib_config $confObj, tx_sglib_debug $debugObj: ...
	 * @return	[type]		...
	 */
	public function set($name, $type, $tmp_name, $error, $size) {
		$this->name = $name;
		$this->type = $type;
		$this->tmp_name = $tmp_name;
		$this->error = $error;
		$this->size = $size;
	}

	/***********************************************************************************************
	 *
	 * Getters
	 *
	 ***********************************************************************************************/

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	public function getName() {
		return ($this->name);
	}
	
	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	public function getType() {
		return ($this->type);
	}
	
	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	public function getError() {
		return ($this->error);
	}
	
	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	public function getSize($readableMode=FALSE) {
		return ($readableMode ? t3lib_div::formatSize($this->size) : $this->size);
	}
	
	
	/***********************************************************************************************
	 *
	 * Check-Functions
	 *
	 ***********************************************************************************************/

	/**
	 * [Describe function...]
	 *
	 * @return	boolean
	 */
	public function isValid() {
		if ($this->name && $this->size && !$this->error) {
			return (TRUE);
		} else {
			return (FALSE);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @return	boolean
	 */
	public function isMissing() {
		if (!$this->name && $this->size<1 && !$this->tmp_name) {
			return (TRUE);
		} else {
			return (FALSE);
		}
	}

	/***********************************************************************************************
	 *
	 * Magic Methods
	 *
	 ***********************************************************************************************/

	public function __call ($name, array $arguments=Array()) {
		t3lib_div::debug(Array('ERROR'=>'Function "$name" not implemented', 'Class'=>get_class($this), 'File:Line'=>__FILE__.':'.__LINE__));
		return ('ERROR: method "'.get_class($this).'->'.$name.'(...)" does not exist. ');
	}

	public function __toString() {
		//$string = get_class ($this) . '(';
		if ($this->isValid()) {
			$string = 'valid (name="'.$this->name.'", type="'.$this->type.'", tmp_name="'.$this->tmp_name.'", error='.$this->error.', size='.$this->size.')';
		} elseif ($this->isMissing()) {
			$string = '-missing-';
		} else {
			$string = 'invalid(name="'.$this->name.'", type="'.$this->type.'", tmp_name="'.$this->tmp_name.'", error='.$this->error.', size='.$this->size.')';
		}
		return $string;
	}





}


?>