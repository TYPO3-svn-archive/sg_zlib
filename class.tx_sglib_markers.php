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
 *   44: class tx_sglib_markers
 *   77:     private function init(tx_sglib_factory $factoryObj)
 *   93:     protected function _fCount ($name=NULL)
 *  118:     public function __destruct()
 *  128:     public function __get($nm)
 *  148:     public function __set($nm, $val)
 *  168:     public function getDescriptions($table='', $markers=array())
 *  187:     public function getTtContent($ttConf,$markers=array())
 *  205:     public function getRefValues($record, $table='', $markers=array())
 *
 * TOTAL FUNCTIONS: 8
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_markers {
	private static $instance = Array();

	private $factoryObj = NULL;
	protected $confObj;
	protected $debugObj;
	protected $langObj;
	protected $defaultDesignator;
	protected $model;

	protected $conf;
	protected $mainTable;
	protected $mainConf = Array();

	protected function __construct() {}

	private function __clone() {}

	public static function getInstance($designator, tx_sglib_factory $factoryObj) {
		if (!isset(self::$instance[$designator])) {
			self::$instance[$designator] = new tx_sglib_markers();
			self::$instance[$designator]->init($factoryObj);
		}
		return (self::$instance[$designator]);
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
		$this->mainTable = $this->confObj->getTCAname();
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
				$markers['###DESCR_'.strtoupper($key).'###'] = $this->confObj->TSobj($value['name'],$value['conf']);
			} else {
				$markers['###DESCR_'.strtoupper($key).'###'] = $this->langObj->getLLL($value);
			}
		}
		return ($markers);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$markers: ...
	 * @return	[type]		...
	 */
	public function getTtContent($ttConf,$markers=array()) {
		if (is_array($this->factoryObj->cObj->data)) foreach ($this->factoryObj->cObj->data as $key=>$value) {
			if (is_array($ttConf[$key.'.'])) {
				$value = $this->confObj->TSObj($ttConf[$key],$ttConf[$key.'.']);
			}
			$markers['###TTCONTENT_'.strtoupper($key).'###'] = $value;
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
				$refTable = $this->confObj->references['table'][$key];
				if ($refTable && ($labelField=$this->confObj->references['label'][$refTable])) {
					$data = $this->model->refData[$refTable];
					$textValue = $data[$value][$labelField];
				} else {
					$textValue = $value;
				}
				$markers['###TEXT_'.strtoupper($key).'###'] = $textValue;
				if (is_array($this->confObj->mainConf[$key.'.']['stdWrap.'])) {
					$textValue = $this->factoryObj->cObj->stdWrap($textValue,$this->confObj->mainConf[$key.'.']['stdWrap.']);
				}
				if ($tmp=$this->confObj->mainConf[$key.'.']['linkIfFileExists']) {
					$path = $textValue;
					$textValue = (is_string($tmp) && $record[$tmp]) ? $record[$tmp] : $textValue;
					$textValue = (is_array($tmp)) ? $this->factoryObj->cObj->stdWrap($textValue, $tmp) : $textValue;
					if (file_exists(t3lib_div::getFileAbsFileName($this->confObj->mainConf[$key.'.']['uploadfolder'].'/'.$path))) {
						$myConf = Array('path'=>$this->confObj->mainConf[$key.'.']['uploadfolder'].'/', 'labelStdWrap.'=>array('override'=>$textValue));
						$textValue = $this->factoryObj->cObj->filelink($path,$myConf);
					}
				}
				$markers['###AUTO_'.strtoupper($key).'###'] = $textValue;
			}
		} else {
			$data = $this->model->refData[$table];
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