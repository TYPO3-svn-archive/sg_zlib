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
 *   53: class tx_sglib_factory
 *  101:     public function setBaseTables($mainTable, $tables)
 *  140:     public function __get($nm)
 *  185:     public function getConfObj()
 *  213:     protected function getDebugObj()
 *  223:     protected function getConstObj()
 *  235:     protected function getParamsObj()
 *  247:     protected function getMarkersObj()
 *  259:     protected function getLangObj()
 *  271:     protected function getPermitObj()
 *  283:     protected function getTemplateObj()
 *  293:     protected function getItemsObj()
 *  303:     protected function getDivObj()
 *  316:     public function getData($name='tx_sglib_data')
 *  328:     public function getModel($name)
 *  348:     public function getView($name)
 *  367:     function getDesignator()
 *  377:     private function checkForconfObj()
 *
 * TOTAL FUNCTIONS: 17
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_factory {
	private static $instance = Array();
	protected $baseConfig = Array();
	protected $cObj = NULL;
	protected $confObj = NULL;
	protected $debugObj = NULL;
	protected $constObj = NULL;
	protected $langObj = NULL;
	protected $permitObj = NULL;
	protected $paramsObj = NULL;
	protected $markersObj = NULL;
	protected $divObj = NULL;
	protected $myDebugObj = NULL;
	protected $designator = 'tx_sglib_factory';

	protected function __construct() {}

	private function __clone() {}

	public static function getInstance($designator, $parentCObj, $conf) {
		if (self::$instance[$designator]==null) {
			self::$instance[$designator] = new tx_sglib_factory();
			self::$instance[$designator]->designator = $designator;
		}
		if (self::$instance[$designator]->cObj!=$parentCObj) {
			self::$instance[$designator]->cObj = $parentCObj;
			if (is_object(self::$instance[$designator]->confObj)) {
				self::$instance[$designator]->confObj->setParentObject($parentCObj);
				self::$instance[$designator]->confObj->setLocalConf();
				self::$instance[$designator]->confObj->combineTCAandConf('*');
			}
		}
		if (!is_array(self::$instance[$designator]->baseConfig)) {
			self::$instance[$designator]->baseConfig = Array();
		}
		if (!is_array(self::$instance[$designator]->baseConfig['conf'])) {
			self::$instance[$designator]->baseConfig['conf'] = $conf;
		}
		return (self::$instance[$designator]);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$mainTable: ...
	 * @param	[type]		$tables: ...
	 * @return	[type]		...
	 */
	public function setBaseTables($mainTable, $tables) {
		if (strcmp($tables,'*')==0) {
			$tables = Array();
			if (!is_array($GLOBALS['TCA'][$mainTable]['columns'])) {
				$GLOBALS['TSFE']->includeTCA();
			}
			if (!is_array($GLOBALS['TCA'][$mainTable]['columns'])) {
				t3lib_div::loadTCA($mainTable);
				$GLOBALS['TSFE']->includeTCA();
			}
			$tmp = $GLOBALS['TCA'][$mainTable];
			foreach ($tmp['columns'] as $key=>$fieldConf) {
				if ($fieldConf['config']['allowed'] && strcmp($fieldConf['config']['internal_type'],'db')==0) {
					$tables[] = $fieldConf['config']['allowed'];
				} else if ($fieldConf['config']['foreign_table']) {
					$tables[] = $fieldConf['config']['foreign_table'];
				}
			}
			$tables[] = $mainTable;
		}

		if (!$mainTable && is_array($tables) && count($tables)>0) {
			$mainTable = $tables[0];
		}
		if (!in_array($mainTable, $tables)) {
			$tables[] = $mainTable;
		}
		$this->baseConfig['mainTable'] = $mainTable;
		$this->baseConfig['tables'] = is_array($tables) ? $tables : Array($tables);
		// t3lib_div::debug(Array('$mainTable'=>$mainTable, '$tables'=>$tables,  'File:Line'=>__FILE__.':'.__LINE__));
	}


	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$nm: ...
	 * @return	[type]		...
	 */
    public function __get($nm)
    {
		switch ($nm) {
			case 'cObj':
			case 'CObj':
				return ($this->cObj);
			case 'confObj':
			case 'configObj':
				return ($this->getConfObj());
			case 'debugObj':
				return ($this->getDebugObj());
			case 'constObj':
				return ($this->getConstObj());
			case 'paramsObj':
				return ($this->getParamsObj());
			case 'markersObj':
				return ($this->getMarkersObj());
			case 'langObj':
				return ($this->getLangObj());
			case 'permitObj':
				return ($this->getPermitObj());
			case 'templateObj':
				return ($this->getTemplateObj());
			case 'itemsObj':
				return ($this->getItemsObj());
			case 'divObj':
				return ($this->getDivObj());
			default:
				$error = 'get("'.$nm.'") failed ... Variable unknown !!';
				if (is_object($this->debugObj)) {
					$this->debugObj->showError(0,$error,0,'','',1);
					return ('$error');
				} else {
					die ($error);
				}
		}
		return ('');
    }


	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	public function getConfObj() {
		if (!isset($this->confObj)) {
			$conf = $this->baseConfig['conf'];
			$this->confObj = tx_sglib_config::getInstance($this->designator, $this, $conf);
			$tables = $this->baseConfig['tables'];
			if (is_array($tables)) foreach ($tables as $table) {
				$this->confObj->setTCAdata($table);
			}
			$this->confObj->setTCAname($this->baseConfig['mainTable']);
			$this->confObj->setPreConfData('tx_sgzlib', $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_sgzlib.']);
			$this->confObj->setConfData($conf);
			$this->confObj->setLocalConf();
			$this->confObj->combineTCAandConf('*');
		}

		if (!isset($this->debugObj)) {
			$this->debugObj = tx_sglib_debug::getInstance($this->designator, $this);
			$this->myDebugObj = $this->debugObj;
		}

		return ($this->confObj);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function getDebugObj() {
		$confObj = $this->checkForconfObj();
		return ($this->debugObj);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function getConstObj() {
		if (!isset($this->constObj)) {
			$this->constObj = tx_sglib_const::getInstance($this->designator, $this);
		}
		return ($this->constObj);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function getParamsObj() {
		if (!isset($this->paramsObj)) {
			$this->paramsObj = tx_sglib_params::getInstance($this->designator, $this);
		}
		return ($this->paramsObj);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function getMarkersObj() {
		if (!isset($this->markersObj)) {
			$this->markersObj = tx_sglib_markers::getInstance($this->designator, $this);
		}
		return ($this->markersObj);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function getLangObj() {
		if (!isset($this->langObj)) {
			$this->langObj = tx_sglib_lang::getInstance($this->designator, $this);
		}
		return ($this->langObj);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function getPermitObj() {
		if (!isset($this->permitObj)) {
			$this->permitObj = tx_sglib_permit::getInstance($this->designator, $this);
		}
		return ($this->permitObj);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function getTemplateObj() {
		$confObj = $this->checkForconfObj();
		return (tx_sglib_template::getInstance($this->designator, $this));
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function getItemsObj() {
		$confObj = $this->checkForconfObj();
		return (tx_sglib_items::getInstance($this->designator, $this));
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function getDivObj() {
		if ($this->divObj==null) {
			$this->divObj = new tx_sgdiv();
		}
		return ($this->divObj);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	public function getData($name='tx_sglib_data') {
		$obj = NULL;
		$obj = new $name();
		return ($obj);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	public function getModel($name) {
		$obj = NULL;
		$numArgs = func_num_args();
		$argList = func_get_args();
		if ($numArgs==3) {
			$obj = new $name($argList[1],$this,$argList[2]);
		} else {
			$error = 'getModel("'.$name.'",$designator,$cached)<br />... only '.$numArgs.' Arguments given!';
			$this->myDebugObj->showError(0,$error,SGZLIB_FATALERROR);
		}

		return ($obj);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	public function getView($name) {
		$obj = NULL;
		$numArgs = func_num_args();
		$argList = func_get_args();
		if ($numArgs==4) {
			$obj = new $name($argList[1],$this,$argList[2],$argList[3]);
		} else {
			$error = 'getView("'.$name.'",$designator,$model,$cached)<br />... only '.$numArgs.' Arguments given!';
			$this->myDebugObj->showError(0,$error,SGZLIB_FATALERROR);
		}

		return ($obj);
	}

	/**
	 * Return the default designator.
	 *
	 * @return	string		default designator
	 */
	function getDesignator() {
		return $this->designator;                    // explicit given designator
	}


	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	private function checkForconfObj() {
		if (!isset($this->confObj)) {
			$error = 'confObj must be called first ! PrefixId='.$this->designator;
			$this->myDebugObj->showError(0,$error,SGZLIB_FATALERROR);
		}
		return ($this->confObj);
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sglib_factory.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sglib_factory.php']);
}
?>