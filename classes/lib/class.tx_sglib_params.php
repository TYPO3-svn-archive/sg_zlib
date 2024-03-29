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
 *   49: class tx_sglib_params
 *   71:     public static function getInstance($designator, tx_sglib_factory $factoryObj)
 *   85:     private function init(tx_sglib_factory $factoryObj)
 *  105:     public function updateParams ($params)
 *  115:     private function _fCount ($name=NULL)
 *  138:     function __destruct()
 *  147:     function getPluginParams()
 *  155:     function getPluginFiles()
 *  164:     function getSearchmode()
 *  173:     function getSearch()
 *  182:     function getUid()
 *  192:     function getListMode()
 *  212:     function getListResultsPerPage()
 *  226:     function getListActivePage()
 *
 * TOTAL FUNCTIONS: 13
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_params {
	private static $instance = Array();

	protected $factoryObj = NULL;
	protected $confObj;
	protected $debugObj;
	protected $defaultDesignator;
	protected $params;
	protected $fileParams;
	protected $uploads = Array();

	protected $conf=Array();

	protected function __construct() {}

	private function __clone() {}

	/**
	 * Returns a singlton instance of tx_sglib_params
	 *
	 * @param	string				Designator
	 * @param	tx_sglib_factory	FactoryObj
	 * @return	tx_sglib_params	Instantiated Object
	 */
	
	public static function getInstance($designator, tx_sglib_factory $factoryObj) {
		if (!isset(self::$instance[$designator])) {
			self::$instance[$designator] = new tx_sglib_params();
			self::$instance[$designator]->init($factoryObj);
		}
		return (self::$instance[$designator]);
	}

	protected static function _GPmerged($parameter) {
		$postParameter = is_array($_POST[$parameter]) ? $_POST[$parameter] : array();
		$getParameter  = is_array($_GET[$parameter])  ? $_GET[$parameter]  : array();

		$mergedParameters = t3lib_div::array_merge_recursive_overrule($getParameter, $postParameter);
		t3lib_div::stripSlashesOnArray($mergedParameters);

		return $mergedParameters;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$tx_sglib_config $confObj, tx_sglib_debug $debugObj: ...
	 * @return	[type]		...
	 */
	private function init(tx_sglib_factory $factoryObj) {
		$this->_fCount(__FUNCTION__);
		$this->factoryObj = $factoryObj;
		$this->confObj = $factoryObj->confObj;
		$this->defaultDesignator = $this->confObj->getDesignator();
		$this->debugObj = $factoryObj->debugObj;

		if ($this->defaultDesignator)	{
			$this->params = $this->_GPmerged($this->defaultDesignator);
			$this->fileParams = (array) $_FILES[$this->defaultDesignator];
			$this->uid = htmlspecialchars(t3lib_div::_GP('uid'));
		}

		if (is_array($this->fileParams) && count($this->fileParams)) {
			// t3lib_div::debug(Array('$this->fileParams'=>$this->fileParams, 'File:Line'=>__FILE__.':'.__LINE__));
			$this->uploads = $this->processUploads($this->fileParams);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$params: ...
	 * @return	[type]		...
	 */
	public function updateParams ($params) {
			$this->params = $params;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	private function _fCount ($name=NULL) {
		static $callCount = NULL;
		if (!isset($callCount)) {
			$callCount = Array();
			foreach(get_class_methods(__CLASS__) as $key=>$value) {
				$callCount[$value] = 0;
			}
			unset($callCount['_fCount']);
			unset($callCount['__destruct']);
		}
		if (isset($name)) {
			$callCount[$name]++;
			return;
		} else {
			return $callCount;
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function __destruct() {
		if (is_object($this->debugObj)) $this->debugObj->debugIf('callCount',Array('Class '.__CLASS__ => $this->_fCount()));
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getPluginParams() {
		return ($this->params);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function addPluginParams(array $myParams) {
		$this->params = t3lib_div::array_merge_recursive_overrule($this->params, $myParams);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getPluginFiles() {
		return ($this->fileParams);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getSearchmode() {
		return ($this->params['searchmode']);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getSearch() {
		return ($this->params['search']);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getUid($name='uid') {
		$uid = htmlspecialchars((intval($this->params[$name])) ? intval($this->params[$name]) : t3lib_div::_GP($name));
		return ($uid);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getListMode() {
		if (isset($this->params['listmode'])) {
			$retVal = $this->params['listmode'];
		} else if (isset($this->params['search']['listmode'])) {
			$retVal = $this->params['search']['listmode'];
		} else {
			if ($this->confObj->mainCtrl['defaultListmode']) {
				$retVal = $this->confObj->mainCtrl['defaultListmode'];
			} else {
				$retVal = 'default';
			}
		}
		return ($retVal);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getListResultsPerPage() {
		//TODO get from TS
		$retVal = $this->confObj->list['maxPerPage'];
		//TODO check if changes via Params possible
		//TODO replace by params, if in range
		$retVal = $retVal<2 ? 20 : $retVal;
		return ($retVal);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getListActivePage() {
		$retVal = isset($this->params['pg']) ? $this->params['pg'] : t3lib_div::_GP('pg');
		$retVal = $retVal<2 ? 1 : $retVal;
		return ($retVal);
	}


	public function getUploads($fieldname) {
		return ($this->uploads[$fieldname]);
	}

	protected function processUploads($fileParams) {
		$uploads = Array();
		
		if (is_array($fileParams['name'])) foreach ($fileParams['name'] as $fieldName=>$fileName) {
			$uploads[$fieldName] = tx_sglib_fileupload::getInstance($fieldName,$this->factoryObj);
			$uploads[$fieldName]->set($fileParams['name'][$fieldName], 
					$fileParams['type'][$fieldName], 
					$fileParams['tmp_name'][$fieldName], 
					$fileParams['error'][$fieldName], 
					$fileParams['size'][$fieldName]);
		}

		return ($uploads);
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


}


?>