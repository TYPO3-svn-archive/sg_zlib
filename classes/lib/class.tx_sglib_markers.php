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

require_once(t3lib_extMgm::extPath('sg_zlib').'classes/viewhelpers/class.tx_sglib_viewhelpers_search.php');
require_once(t3lib_extMgm::extPath('sg_zlib').'classes/viewhelpers/class.tx_sglib_viewhelpers_input.php');
require_once(t3lib_extMgm::extPath('sg_zlib').'classes/viewhelpers/class.tx_sglib_viewhelpers_image.php');
require_once(t3lib_extMgm::extPath('sg_zlib').'classes/viewhelpers/class.tx_sglib_viewhelpers_file.php');
require_once(t3lib_extMgm::extPath('sg_zlib').'classes/viewhelpers/class.tx_sglib_viewhelpers_link.php');
require_once(t3lib_extMgm::extPath('sg_zlib').'classes/viewhelpers/class.tx_sglib_viewhelpers_email.php');
require_once(t3lib_extMgm::extPath('sg_zlib').'classes/viewhelpers/class.tx_sglib_viewhelpers_const.php');
require_once(t3lib_extMgm::extPath('sg_zlib').'classes/viewhelpers/class.tx_sglib_viewhelpers_lll.php');
require_once(t3lib_extMgm::extPath('sg_zlib').'classes/viewhelpers/class.tx_sglib_viewhelpers_obj.php');
require_once(t3lib_extMgm::extPath('sg_zlib').'classes/viewhelpers/class.tx_sglib_viewhelpers_ref.php');
require_once(t3lib_extMgm::extPath('sg_zlib').'classes/viewhelpers/class.tx_sglib_viewhelpers_wrap.php');

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

	protected $factoryObj = NULL;
	protected $confObj;
	protected $debugObj;
	protected $langObj;
	protected $itemsObj;
	protected $defaultDesignator;
	protected $model;
	protected $cObj;

	protected $conf;
	protected $mainTable;
	protected $mainConf = Array();

	protected $formName = '';
	protected $lastLinkOK = false;

	protected $viewHelpers = array();
	protected $viewHelperSettings = Array();

	protected function __construct() {}

	private function __clone() {}

	/**
	 * Returns a singlton instance of tx_sglib_markers
	 *
	 * @param	string				Designator
	 * @param	tx_sglib_factory	FactoryObj
	 * @return	tx_sglib_markers	Instantiated Object
	 */

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
		$this->itemsObj = $factoryObj->itemsObj;
		$this->mainTable = $this->confObj->getTCAname();
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');

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
		switch (strtolower($nm)) {
			case 'model':
				return ($this->model);
			case 'formname':
				return ($this->formName);
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
		switch (strtolower($nm)) {
			case 'model':
				$this->model = $val;
				break;
			case 'formname':
				$this->formName = $val;
				break;
			default:
				$error = 'set("'.$nm.'") failed ... Variable unknown !!';
				$this->debugObj->showError(0,$error,0,'','',1);
				break;
		}
	}

	public function viewHelperOptions($viewHelper,$name,$value=NULL) {
		if (!is_array($this->viewHelperSettings[$viewHelper])) {
			$this->viewHelperSettings[$viewHelper] = Array();
		}

		if (!is_null($value)) {
			$this->viewHelperSettings[$viewHelper][$name] = $value;
		}

		return ($this->viewHelperSettings[$viewHelper][$name]);
	}
	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$markers: ...
	 * @return	[type]		...
	 */
	public function getDescriptions($table='', $markers=array(), $prefix='') {
		$tmp = $this->confObj->getDescriptions($table);
		if (is_array($tmp) && count($tmp)) foreach ($tmp as $key=>$value) {
			if (is_array($value)) {
				$markers['###DESCR_'.strtoupper(($prefix ? $prefix.'_' : '').$key).'###'] = $this->confObj->TSobj($value['name'],$value['conf']);
			} else {
				$markers['###DESCR_'.strtoupper(($prefix ? $prefix.'_' : '').$key).'###'] = $this->langObj->getLLL($value);
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
				if ($value && $refTable && ($labelField=$this->confObj->references['label'][$refTable])) {
					$data = @$this->model->refData[$refTable];
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

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$record: ...
	 * @param	[type]		$table: ...
	 * @param	[type]		$markers: ...
	 * @return	[type]		...
	 */
	public function getMarkers($record, $table='', $markers=array()) {
		$table = $table ? $table : $this->mainTable;

		if (is_array($record)) {
			foreach ($record as $fieldName=>$value) {
				$this->addMarkersForField($value, $fieldName, $table, $markers);
			}
		} else {
		}

		return ($markers);
	}

	/**
	 * Adds markers for one data-field
	 *
	 * For every field, several markers are created:
	 * DATA_xxx contais the original Data from the Model
	 * TEXT_xxx contains a textual view of the data (means: references replaced to text)
	 * FORM_xxx contains the form-field for entering Data                    @TODO
	 * AUTO_xxx contains Text/Form, depending on the actual Mode of the view @TODO
	 *
	 *
	 * @param	[type]		$fieldName: ...
	 * @param	[type]		$value: ...
	 * @param	[type]		$markers: ...
	 * @return	[type]		...
	 */
	public function addMarkersForField($value, $fieldName, $table, &$markers) {
		$markers['###DATA_'.strtoupper($fieldName).'###'] = $textValue = $value;
		if ($this->confObj->getReferences($table,'table',$fieldName)) {
			$textValue = $this->itemsObj->getReferenceValue($value, $fieldName, $table); //'REF:'.$value;
		}
		$textValue = $this->processValueType ($textValue, $fieldName, $table);
		$markers['###TEXT_'.strtoupper($fieldName).'###'] = $textValue;
	}

	public function processValueType($value, $fieldName, $table) {
		$textValue = $value;

		$type =  strtolower($this->confObj[$table.'.']['conf.'][$fieldName.'.']['type']);
//		if ($em>=SGZ_SEARCH && isset($PCA['search'][$field]['formtype'])) {
//			$myType = strtolower($PCA['search'][$field]['formtype']);
//		}

		switch($type)	{
			case 'date':
				$textValue = $value ? date('d.m.Y',$value) : '';
					break;
			default:
				// $item = '[#ERROR#--getSingleField_SW('.$table.'.'.$field.', Type="'.$myType.'")=UNKNOWN--#]';
				// $item .= t3lib_div::view_array(Array('File:Line='=>__FILE__.':'.__LINE__ ,'Backtrace='=>debug_backtrace()));
				$textValue = $value;
			break;
		}


		return ($textValue);
	}


	public function renderSpecialMarkers(array $markersList,$table,$row,array &$markers=NULL) {
		// t3lib_div::debug(Array('$markersList'=>$markersList, 'File:Line'=>__FILE__.':'.__LINE__));
		if (!is_array($markers)) {
			$markers = Array();
		}
		foreach ($markersList as $specialMarker) {
			$view = explode(':',$specialMarker,2);
			$type = strtolower(trim($view[0]));
			if (strcmp(trim($view[0]),strtoupper($type))==0) {
				$endMode = false;
				if (strncmp($type,'/',1)==0) {
					$type = substr($type,1);
					$endMode = true;
				}
				$parameters = trim($view[1]);

				if ($type && !$this->viewHelpers[$type]) {
					if (class_exists('tx_sglib_viewhelpers_'.$type)) {
						$this->viewHelpers[$type] = t3lib_div::makeInstance('tx_sglib_viewhelpers_'.$type);
						$this->viewHelpers[$type]->init($this->factoryObj,$this->formName,$type,$this->viewHelperSettings[$type]);
					} else if ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sg_zlib']['viewhelpers'][strtoupper($type)]) {
						$this->viewHelpers[$type] = t3lib_div::makeInstance($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sg_zlib']['viewhelpers'][strtoupper($type)]);
						$this->viewHelpers[$type]->init($this->factoryObj,$this->formName,$type,$this->viewHelperSettings[$type]);
					} else {
						t3lib_div::debug(Array('ERROR: Viewhelper missing:'=>$type, 'File:Line'=>__FILE__.':'.__LINE__));
					}
				}

				if ($type) {
					if ($this->viewHelpers[$type]) {
						$markers['###'.$specialMarker.'###'] = $endMode ?
							$this->viewHelpers[$type]->renderClosingTag($table,$row,$parameters):
							$this->viewHelpers[$type]->render($table,$row,$parameters);
					} else {
						$markers['###'.$specialMarker.'###'] = '[Marker ***'.$specialMarker.'*** viewhelper '.$type.' unbekannt]';
					}
				} else {
					$markers['###'.$specialMarker.'###'] = '[Marker ***'.$specialMarker.'*** unbekannt]';
				}

			}
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


}


?>