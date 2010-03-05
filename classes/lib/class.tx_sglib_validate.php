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
class tx_sglib_validate {
	private static $instance = Array();

	protected $factoryObj = NULL;
	protected $confObj;
	protected $debugObj;
	protected $defaultDesignator;
	protected $params;
	protected $fileParams;
	protected $fieldMessages = Array();

	protected $dbg = 1;

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
			self::$instance[$designator] = new tx_sglib_validate();
			self::$instance[$designator]->init($factoryObj);
		}
		return (self::$instance[$designator]);
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
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
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



	public function setStep ($max,$conf=NULL) {
		$params = t3lib_div::_GP($this->defaultDesignator);
		$data = $params['data'];
		return ($this->setStepByParams (intval(t3lib_div::_GP('step')),$max,t3lib_div::_GP('back'),t3lib_div::_GP('next'),$data,$conf));
	}

	
	public function setStepByParams ($step,$max,$prev,$next,$valid,$conf=NULL) {
		if ($prev) {
			$step--;
		}
		if ($next) {
			if ($valid===TRUE || (is_array($conf) && count($conf)<1)) {
				$step++;
			} elseif (is_array($valid) && is_array($conf)) {
				if ($this->validate($valid, $conf)) {
					$step++;
				}
			}
		}
		if ($step<1) {
			$step = 1;
		}
		if ($step>$max) {
			$step = 0;
		}

		return ($step);
	}

	public function validate (array $data, array $conf) {
		$valid = true;
		$this->fieldMessages = Array('error'=>Array(), 'warning'=>Array(), 'info'=>Array());
		foreach ($conf as $fieldName=>$fieldConf) {
			$errorMessage = '';
			if (is_array($fieldConf)) foreach ($fieldConf as $testName=>$testConf) if (!$errorMessage) {
				$errorLevel = $this->checkFieldFor($errorMessage,$data,substr($fieldName,0,-1),substr($testName,0,-1),$testConf);
				if ($errorLevel==2) {
					$this->fieldMessages['error'][substr($fieldName,0,-1)] = $errorMessage;
					$valid = false;
				} elseif ($errorLevel==1) {
					$this->fieldMessages['warning'][substr($fieldName,0,-1)] = $errorMessage;
				} elseif ($errorMessage) {
					$this->fieldMessages['info'][substr($fieldName,0,-1)] = $errorMessage;
				}
			}
		}
		if (count($this->fieldMessages['error'])) {
			$valid = false;
			// t3lib_div::debug(Array('$this->fieldMessages'=>$this->fieldMessages, 'File:Line'=>__FILE__.':'.__LINE__));
		}
		return ($valid);
	}

	public function getFieldMessages () {
		return ($this->fieldMessages);
	}

	public function checkFieldFor(&$errorMessage, array $data, $fieldName, $testName, $testConf) {
		$errorLevel = 0;
		$value = $data[$fieldName];
		// t3lib_div::debug(Array('$fieldName'=>$fieldName, '$value'=>$value, '$testName'=>$testName, '$testConf'=>$testConf, 'File:Line'=>__FILE__.':'.__LINE__));

		switch($testName) {
			case 'intRange':
				$test = intval($value);
				if ((isset($testConf['min']) && $test<$testConf['min']) || (isset($testConf['max']) && $test>$testConf['max'])) {
					$errorMessage = $this->cObj->TEXT($testConf['error.']);
					$errorLevel = 2;
					// t3lib_div::debug(Array('$fieldName'=>$fieldName, '$testConf'=>$testConf, '$message'=>$message, 'File:Line'=>__FILE__.':'.__LINE__));
				}
				break;
		
			case 'minLenght':
				if (strlen($value)<$testConf['value']) {
					$errorMessage = $this->cObj->TEXT($testConf['error.']);
					$errorLevel = 2;
					// t3lib_div::debug(Array('$fieldName'=>$fieldName, '$message'=>$message, 'File:Line'=>__FILE__.':'.__LINE__));
				}
				break;
		
			case 'isEmail':
				if (!tx_sgdiv::validEmail($value) && $testConf['value']) {
					$errorMessage = $this->cObj->TEXT($testConf['error.']);
					$errorLevel = 2;
					// t3lib_div::debug(Array('$fieldName'=>$fieldName, '$message'=>$message, 'File:Line'=>__FILE__.':'.__LINE__));
				}
				break;
		
			case 'isPhone':
				if (!tx_sgdiv::validPhoneNumber($value) && $testConf['value']) {
					$errorMessage = $this->cObj->TEXT($testConf['error.']);
					$errorLevel = 2;
					// t3lib_div::debug(Array('$fieldName'=>$fieldName, '$value'=>$value, '$message'=>$message, 'File:Line'=>__FILE__.':'.__LINE__));
				}
				break;
		
			case 'pregMatch':
				if (!preg_match($testConf['value'], $value) || strlen(trim($value))<1) {
					$errorMessage = $this->cObj->TEXT($testConf['error.']);
					$errorLevel = 2;
					// t3lib_div::debug(Array('$fieldName'=>$fieldName, '$value'=>$value, '$testConf'=>$testConf, '$message'=>$message, 'File:Line'=>__FILE__.':'.__LINE__));
				}
				break;
		
			default:
				if (is_array($testConf)) {
					if ($this->dbg) t3lib_div::debug(Array('$fieldName'=>$fieldName, '$value'=>$value, '$testName'=>$testName, '$testConf'=>$testConf, 'File:Line'=>__FILE__.':'.__LINE__));
				}

		}
		 
		return ($errorLevel);
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