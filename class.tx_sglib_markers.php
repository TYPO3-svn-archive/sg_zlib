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
 *   43: class tx_sglib_markers
 *   76:     protected function init(tx_sglib_config $confObj, tx_sglib_debug $debugObj, tx_sglib_lang $langObj, $cObj)
 *   97:     protected function _fCount ($name=NULL)
 *  122:     public function __destruct()
 *  132:     public function __get($nm)
 *  152:     public function __set($nm, $val)
 *  172:     public function getDescriptions($table='', $markers=array())
 *  192:     public function getRefValues($record, $table='', $markers=array())
 *
 * TOTAL FUNCTIONS: 7
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_markers {
	private static $instance = NULL;

	private $factoryObj = NULL;
	protected $confObj;
	protected $debugObj;
	protected $langObj;
	protected $cObj;
	protected $defaultDesignator;
	protected $model;

	protected $conf;
	protected $mainTable;
	protected $mainConf = Array();

	protected function __construct() {}

	private function __clone() {}

	public static function getInstance($designator, tx_sglib_factory $factoryObj) {
		if (!isset(self::$instance)) {
			self::$instance = new tx_sglib_markers();
			self::$instance->init($factoryObj);
		}
		return (self::$instance);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$tx_sglib_config $confObj, tx_sglib_debug $debugObj: ...
	 * @param	[type]		$cObj: ...
	 * @return	[type]		...
	 */
	private function init(tx_sglib_factory $factoryObj) {
		$this->_fCount(__FUNCTION__);
		$this->factoryObj = $factoryObj;
		$this->confObj = $factoryObj->confObj;
		$this->defaultDesignator = $this->confObj->getDesignator();
		$this->debugObj = $factoryObj->debugObj;
		$this->langObj = $factoryObj->langObj;
		$this->cObj = $factoryObj->cObj;
		$this->mainTable = $this->confObj->getTCAname();
		$this->conf = $this->confObj->get('');
		foreach ($this->conf[$this->mainTable.'.']['conf.'] as $key=>$value)
		if (is_array($value)) {
			$this->mainConf[substr($key,0,-1)] = $value;
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	protected function _fCount ($name=NULL) {
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
	public function __destruct() {
		if (is_object($this->debugObj)) $this->debugObj->debugIf('callCount',Array('Class '.__CLASS__ => $this->_fCount()));
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
			case 'model':
				return ($this->model);
			default:
				$error = 'get("'.$nm.'") failed ... Variable unknown !!';
				$this->debugObj->showError(0,$error,0,'','',1);
				return ('');
		}
		return ('');
    }

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$nm: ...
	 * @param	[type]		$val: ...
	 * @return	[type]		...
	 */
    public function __set($nm, $val)
    {
		switch ($nm) {
			case 'model':
				$this->model = $val;
				break;
			default:
				$error = 'set("'.$nm.'") failed ... Variable unknown !!';
				$this->debugObj->showError(0,$error,0,'','',1);
				break;
		}
    }

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$markers: ...
	 * @return	[type]		...
	 */
	public function getDescriptions($table='', $markers=array()) {
		$tmp = $this->model->getDescriptions($table);
		if (is_array($tmp) && count($tmp)) foreach ($tmp as $key=>$value) {
			if (is_array($value)) {
				$markers['###DESCR_'.strtoupper($key).'###'] = $this->cObj->cObjGetSingle($value['name'],$value['conf']);
			} else {
				$markers['###DESCR_'.strtoupper($key).'###'] = $this->langObj->getLLL($value);
			}
		}
		return ($markers);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$record: ...
	 * @param	[type]		$table: ...
	 * @param	[type]		$markers: ...
	 * @return	[type]		...
	 */
	public function getRefValues($record, $table='', $markers=array()) {
		if (is_array($record)) {
			$table = $table ? $table : $this->mainTable;
			foreach ($record as $key=>$value) {
				$refTable = $this->model->references['table'][$key];
				if ($refTable && ($labelField=$this->model->references['label'][$refTable])) {
					$data = $this->model->references['data'][$refTable];
					$textValue = $data[$value][$labelField];
				} else {
					$textValue = $value;
				}
				$markers['###TEXT_'.strtoupper($key).'###'] = $textValue;
				if (is_array($this->mainConf[$key]['stdWrap.'])) {
					$textValue = $this->cObj->stdWrap($textValue,$this->mainConf[$key]['stdWrap.']);
				}
				if ($tmp=$this->mainConf[$key]['linkIfFileExists']) {
					$path = $textValue;
					$textValue = (is_string($tmp) && $record[$tmp]) ? $record[$tmp] : $textValue;
					$textValue = (is_array($tmp)) ? $this->cObj->stdWrap($textValue, $tmp) : $textValue;
					if (file_exists(t3lib_div::getFileAbsFileName($this->mainConf[$key]['uploadfolder'].'/'.$path))) {
						$myConf = Array('path'=>$this->mainConf[$key]['uploadfolder'].'/', 'labelStdWrap.'=>array('override'=>$textValue));
						$textValue = $this->cObj->filelink($path,$myConf);
					}
				}
				$markers['###AUTO_'.strtoupper($key).'###'] = $textValue;
			}
		} else {
			$data = $this->model->references['data'][$table];
			// t3lib_div::debug(Array('$data'=>$data, 'File:Line'=>__FILE__.':'.__LINE__));
			$record = $data[$record];
			// t3lib_div::debug(Array('$record'=>$record, 'File:Line'=>__FILE__.':'.__LINE__));
			foreach ($record as $key=>$value) {
				$markers['###REF_'.strtoupper($key).'###'] = $value;
			}
		}
		return ($markers);
	}

}


?>