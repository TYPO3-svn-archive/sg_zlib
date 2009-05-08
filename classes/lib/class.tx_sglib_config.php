<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2002-2007 Stefan Geith (typo3dev2007@geithware.de)
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
 *   81: class tx_sglib_config extends ArrayIterator
 *  113:     public static function getInstance($designator, tx_sglib_factory $factoryObj, $conf)
 *  129:     private function init($designator, tx_sglib_factory $factoryObj, $conf)
 *  153:     public function setParentObject($parentCObj)
 *  170:     public function setPluginConfig($pluginSubMode, $pluginMode, $cmdMode, $cached)
 *  184:     protected function _initRecursive($recursive)
 *  200:     protected function _findAllReferences()
 *  216:     protected function _findReferencesForTable($table)
 *  260:     public function getReferences ($table, $mode='',$key='')
 *  287:     protected function getLabelField($table)
 *  304:     private function _fCount ($name=NULL)
 *  327:     function __destruct()
 *
 *              SECTION: Functions
 *  346:     function setDesignator($string)
 *  356:     function getDesignator()
 *  366:     function getPid()
 *  376:     function getPidList()
 *  389:     function setTCAname($tableName)
 *  399:     function getTCAname()
 *  410:     function setTCAdata($tableName='')
 *  453:     function getTCAdata($tableName='')
 *  464:     function setPreConfData($name, array $conf)
 *  477:     function setConfData(array $conf)
 *  491:     function setLocalConf($localConf=NULL)
 *  513:     function getConfData()
 *  537:     function combineTCAandConf($tableName='')
 *  591:     function getCombined()
 *
 *              SECTION: Getters
 *  610:     public function __get($nm)
 *  674:     public function get($name)
 *  698:     function TSObj ($name,$conf)
 *  717:     function TSConfObj ($conf,$name)
 *  738:     function getFFvalue($fieldName,$sheet='sDEF',$lang='lDEF',$value='vDEF')
 *  752:     function setDebugObj ($debugObj)
 *
 *              SECTION: JS Functions
 *  772:     function addJs ($name)
 *
 *              SECTION: Local helpers
 *  810:     public function initFlexForm($field='pi_flexform')
 *  827:     private function _getFFvalueFromSheetArray($sheetArray,$fieldNameArr,$value)
 *  858:     private function _configTCA ($tableName='',$config=NULL)
 *  880:     private function _getDotArray($myValue)
 *  904:     public function getDescriptions($table='')
 *
 * TOTAL FUNCTIONS: 37
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_config extends ArrayIterator {
	private static $instance = Array();

	private $factoryObj = NULL;
	private $config = Array();
	private $preConf = Array();
	private $pluginConf = Array();
	private $localConf = Array();
	private $flexFormData;
	private $defaultTableName = '';
	private $debugObj=NULL;
	private $debugData = Array();
	private $defaultDesignator;
	private $cObj;

	public $test= Array();

	protected $pid_list,$pid;
	protected $references = Array();
	protected $addedJsFunctions;

	// protected function __construct() {}

	private function __clone() {}

	/**
	 * Get Singleton of this class
	 *
	 * @param	[type]		$designator, tx_sglib_factory $factoryObj: ...
	 * @param	[type]		$conf: ...
	 * @return	[type]		...
	 */
	public static function getInstance($designator, tx_sglib_factory $factoryObj, $conf) {
		if (!isset(self::$instance[$designator])) {
			self::$instance[$designator] = new tx_sglib_config();
			self::$instance[$designator]->init($designator, $factoryObj, $conf);
		}
		return (self::$instance[$designator]);
	}

	/**
	 * Initialize
	 *
	 * @param	[type]		$designator: ...
	 * @param	[type]		$factoryCObj: ...
	 * @param	[type]		$conf: ...
	 * @return	[type]		...
	 */
	private function init($designator, tx_sglib_factory $factoryObj, $conf) {
		$this->_fCount(__FUNCTION__);
		$this->defaultDesignator = $designator;
		$this->factoryObj = $factoryObj;
		$this->setParentObject($factoryObj->cObj);
		$this->initFlexForm();

		$this->pid_list = trim($this->cObj->stdWrap($conf["pid_list"],$conf["pid_list."]));
		$this->pid_list = $this->pid_list ? implode(',',t3lib_div::intExplode(',',$this->pid_list)) : $TSFE->id;
		$recursive = $this->cObj->stdWrap($conf["recursive"],$conf["recursive."]);
		$this->_initRecursive($recursive);

		$myPidList = explode(',',$this->pid_list);
		$this->pid = intval($myPidList[0]);

		//t3lib_div::debug(Array('conf'=>$conf["pid_list"], 'conf'=>$conf["pid_list."], 'pid_list'=>$this->pid_list, '$pid'=>$pid, 'File:Line'=>__FILE__.':'.__LINE__));
	}

	/**
	 * Set or update parentObject
	 *
	 * @param	[type]		$parentCObj: ...
	 * @return	[type]		...
	 */
	public function setParentObject($parentCObj) {
		if ($this->cObj != $parentCObj) {
			// t3lib_div::debug(Array('New ParentObj'=>($this->cObj != $parentCObj), 'File:Line'=>__FILE__.':'.__LINE__));
			$this->cObj = $parentCObj;
			$this->initFlexForm();
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$pluginSubMode: ...
	 * @param	[type]		$pluginMode: ...
	 * @param	[type]		$cmdMode: ...
	 * @param	[type]		$cached: ...
	 * @return	[type]		...
	 */
	public function setPluginConfig($pluginSubMode, $pluginMode, $cmdMode, $cached) {
		$this['pluginSubMode'] = $this->pluginConf['pluginSubMode'] = $pluginSubMode;
		$this['pluginMode'] = $this->pluginConf['pluginMode'] = $pluginMode;
		$this['cmdMode'] = $this->pluginConf['cmdMode'] = $cmdMode;
		$this['cached'] = $this->pluginConf['cached'] = $cached;
	}


	/**
	 * Extends the internal pid_list by the levels given by $recursive
	 *
	 * @param	[type]		$recursive: ...
	 * @return	[type]		...
	 */
	protected function _initRecursive($recursive)	{
		if ($recursive)	{		// get pid-list if recursivity is enabled
			$pid_list_arr = explode(',',$this->pid_list);
			$this->pid_list='';
			while(list(,$val)=each($pid_list_arr))	{
				$this->pid_list.=$val.",".$this->cObj->getTreeList($val,intval($recursive));
			}
			$this->pid_list = ereg_replace(",$","",$this->pid_list);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function _findAllReferences() {
		foreach ($this as $key=>$value) {
			if (strcmp($value,'TABLEDEF')==0) {
				if (!is_array($this->references[$key])) {
					$this->references[$key] = $this->_findReferencesForTable($key);
				}
			}
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @return	[type]		...
	 */
	protected function _findReferencesForTable($table) {
		if (!$table) {
			$table = $table ? $table : $this->defaultTableName;
		}
		$references = Array('table'=>array(), 'field'=>array(), 'asName'=>array(), 'join'=>array(), 'joinMain'=>array(), 'label'=>array());
		$conf = $this[$table.'.']['conf.'];
		if (is_array($conf)) foreach ($conf as $key=>$fieldConf) {
			$fieldName = substr($key,0,-1);
			$refTable = '';
			if ($fieldConf['allowed'] && strcmp($fieldConf['internal_type'],'db')==0) {
				$refTable = $fieldConf['allowed'];
			} else if ($fieldConf['foreign_table']) {
				$refTable = $fieldConf['foreign_table'];
			}
			if ($refTable) {
				if (!is_array($this[$refTable.'.'])) {
					$this->setTCAdata($refTable);
					$this[$refTable] = 'TABLEDEF';
					$this[$refTable.'.'] = $this->_configTCA($refTable);
				}
				$references['table'][$fieldName] = $refTable;
				$references['field'][$refTable] = $fieldName;
				$references['asName'][$refTable] = $fieldName.'#ref';
				$references['join'][$refTable] = ' LEFT JOIN '.$refTable.' ON '.$this->mainTable.'.'.$fieldName.'='.$refTable.'.uid  ';
				$references['joinMain'][$refTable] = ' LEFT JOIN '.$this->mainTable.' ON '.$this->mainTable.'.'.$fieldName.'='.$refTable.'.uid  ';
				$references['label'][$refTable] = $this->getLabelField($refTable);
			}

			if (strcmp($fieldConf['allowed'],$field[0])==0 || strcmp($fieldConf['foreign_table'],$field[0])==0) {
				$retVal = ' LEFT JOIN '.$field[0].' ON '.$this->mainTable.'.'.$fieldName.'='.$field[0].'.uid  ';
			}
		}
		// t3lib_div::debug(Array('findReferencesFor Table "'.$table.'"'=>$references, 'File:Line'=>__FILE__.':'.__LINE__));
		return (count($references['table']) ? $references : Array());
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$mode: ...
	 * @param	[type]		$key: ...
	 * @return	[type]		...
	 */
	public function getReferences ($table, $mode='',$key='') {

//		if (!$this->test[$table]) {
//			$this->test[$table] = 1;
//			t3lib_div::debug(Array('$table'=>$table, 'File:Line'=>__FILE__.':'.__LINE__));
//		}
//		if (!is_array($this[$table.'.'])) {
//			t3lib_div::debug(Array('tableDef missing:'=>$table, $table=>$this[$table], 'File:Line'=>__FILE__.':'.__LINE__));
//		}
		if (!is_array($this->references[$table])) {
			$this->references[$table] = $this->_findReferencesForTable($table);
		}
		if ($mode && $key) {
			return ($this->references[$table][$mode][$key]);
		} else if ($mode) {
			return ($this->references[$table][$mode]);
		} else {
			return ($this->references[$table]);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @return	[type]		...
	 */
	protected function getLabelField($table) {
		$labelField = '';

		$labelField = $this[$table.'.']['ctrl.']['label'];
		$labelField = $labelField ? $labelField : 'uid';

		return ($labelField);
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
	 * ***************************************************************************
	 *
	 * Functions
	 *
	 * *****************************************************************************/

	/**
	 * Set the default designator.
	 *
	 * Usefull for classes of the library tx_lib that are not inherited but directly instantiated.
	 *
	 * @param	string		designator
	 * @return	void
	 */
	function setDesignator($string) {
		$this->_fCount(__FUNCTION__);
		$this->defaultDesignator = $string;
	}

	/**
	 * Return the default designator.
	 *
	 * @return	string		default designator
	 */
	function getDesignator() {
		$this->_fCount(__FUNCTION__);
		return $this->defaultDesignator;                    // explicit given designator
	}

	/**
	 * Return the default PID
	 *
	 * @return	string		default designator
	 */
	function getPid() {
		$this->_fCount(__FUNCTION__);
		return $this->pid;                    // explicit given designator
	}

	/**
	 * Return the Pid-List
	 *
	 * @return	string		default designator
	 */
	function getPidList() {
		$this->_fCount(__FUNCTION__);
		return $this->pid_list;                    // explicit given designator
	}



	/**
	 * Set active default-tablename
	 *
	 * @param	[type]		$tableName: name of mainTable
	 * @return	[type]		void
	 */
	function setTCAname($tableName) {
		$this->_fCount(__FUNCTION__);
		$this->defaultTableName = $tableName;
	}

	/**
	 * Get active default-tablename
	 *
	 * @return	[type]		name of mainTable
	 */
	function getTCAname() {
		$this->_fCount(__FUNCTION__);
		return($this->defaultTableName);
	}

	/**
	 * Get settings from TCA for table (or defaultTable)
	 *
	 * @param	[type]		$tableName
	 * @return	[type]		void
	 */
	function setTCAdata($tableName='') {
		$this->_fCount(__FUNCTION__);
		$tableName = $tableName ? $tableName : $this->defaultTableName;
		if (!is_array($GLOBALS['TCA'][$tableName]['columns'])) {
			$GLOBALS['TSFE']->includeTCA();
		}
		if (!is_array($GLOBALS['TCA'][$tableName]['columns'])) {
			t3lib_div::loadTCA($tableName);
			$GLOBALS['TSFE']->includeTCA();
		}

		$configTCA = $this->_configTCA($tableName);
		if (!is_array($configTCA)) {
			$configTCA = Array('ctrl'=>'TABLECTRL', 'ctrl.'=>$this->_getDotArray($GLOBALS['TCA'][$tableName]['ctrl']),'conf'=>'TABLECONF');
			$myTCA = $GLOBALS['TCA'][$tableName]['columns'];
			if (is_array($myTCA)) for (reset($myTCA);$key=key($myTCA);next($myTCA)) {
				// Not needed for zlib - slows down 80x
				if (strcmp(substr($key,0,4),'l18n')) {
					$configTCA['conf.'][$key.'.']['label'] = $myTCA[$key]['label'];
					if (is_array($myTCA[$key]['config'])) foreach ($myTCA[$key]['config'] as $myKey => $myValue) {
						if ($myKey=='wrap') {
							$configTCA['conf.'][$key.'.']['configWrap'] = $myValue;
						} else if ($myKey!='wizards'){
							if (is_array($myValue)) {
								$configTCA['conf.'][$key.'.'][$myKey.'.'] = $this->_getDotArray($myValue);
							} else {
								$configTCA['conf.'][$key.'.'][$myKey] = $myValue;
							}
						}
					}
				}
			}
			$this->_configTCA($tableName,$configTCA);
		}
		$this->debugData[] = Array('TCAdata',Array('configTCA('.$tableName.')'=>$configTCA, 'File:Line'=>__FILE__.':'.__LINE__));
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$tableName
	 * @return	[type]		TCA-Config
	 */
	function getTCAdata($tableName='') {
		$this->_fCount(__FUNCTION__);
		return ($this->_configTCA($tableName));
	}

	/**
	 * Merge this with existing confData
	 *
	 * @param	array		Array with configuration
	 * @return	array		void
	 */
	function setPreConfData($name, array $conf) {
		$this->_fCount(__FUNCTION__);
		$this->preConf[$name] = $conf;
		$this->debugData[] = Array('confData',Array('preConf['.$name.']='=>$conf, 'File:Line'=>__FILE__.':'.__LINE__));
	}

	/**
	 * [Describe function...]
	 *
	 * @param	array		Array with plugins configuration
	 * @param	string		LocalConf that overwrites $conf; if empty will be searchted in flexform
	 * @return	array		void
	 */
	function setConfData(array $conf) {
		$this->_fCount(__FUNCTION__);
		$this->pluginConf = $conf;
		$this->debugData[] = Array('confData',Array('pluginConf='=>$conf, 'File:Line'=>__FILE__.':'.__LINE__));
	}


	/**
	 * [Describe function...]
	 *
	 * @param	array		Array with plugins configuration
	 * @param	string		LocalConf that overwrites $conf; if empty will be searchted in flexform
	 * @return	array		void
	 */
	function setLocalConf($localConf=NULL) {
		$this->_fCount(__FUNCTION__);
		if (!isset($localConf)) {
			$localConf = $this->getFFvalue('fieldLocalConf','sLocalConf');
		}

		if (!is_array($localConf)) {
			$parseObj = t3lib_div::makeInstance('t3lib_TSparser');
			$parseObj->parse($localConf);
			if (is_array($parseObj->setup)) {
				$localConf = $parseObj->setup;
			}
		}
		$this->localConf = $localConf;
		$this->debugData[] = Array('confData',Array('confData'=>$confData, 'File:Line'=>__FILE__.':'.__LINE__));
	}

	/**
	 * Return confData
	 *
	 * @return	[type]		Conf-Data
	 */
	function getConfData() {
		$this->_fCount(__FUNCTION__);

		$confData = Array();
		foreach ($this->preConf as $name=>$conf) {
			if (is_array($conf)) {
				$confData = t3lib_div::array_merge_recursive_overrule($confData,$conf);
			}
		}
		if (is_array($this->pluginConf)) {
			$confData = t3lib_div::array_merge_recursive_overrule($confData,$this->pluginConf);
		}
		if (is_array($this->localConf)) {
			$confData = t3lib_div::array_merge_recursive_overrule($confData,$this->localConf);
		}
		return($confData);
	}

	/**
	 * Combine TCA-Data with Conf-Data
	 *
	 * @param	[type]		$tableName: ...
	 * @return	[type]		void
	 */
	function combineTCAandConf($tableName='') {
		$this->_fCount(__FUNCTION__);
		$tableName = $tableName ? $tableName : $this->defaultTableName;
		if ($tableName) {
			if (strcmp($tableName,'*')==0) {
				foreach ($this->_configTCA($tableName) as $table=>$config) {
					$this[$table] = 'TABLEDEF';
					$this[$table.'.'] = $config;
				}
			} else {
				$this[$tableName] = 'TABLEDEF';
				$this[$tableName.'.'] = $this->_configTCA($tableName);
			}
		}

		$confArray = $this->getConfData();
		foreach ($this as $table=>$config) if (strcmp($config,'TABLEDEF')==0) {
			//state?// t3lib_div::debug(Array('check for [ignoreTCAsettings] in '=>$table, 'File:Line'=>__FILE__.':'.__LINE__));
			if ($confArray[$table.'.']['ignoreTCAsettings']) {
				unset($this[$table.'.']);
			} else if ($confArray[$table.'.']['ctrl.']['ignoreTCAsettings']) {
				unset($this[$table.'.']['ctrl.']);
			} else if ($confArray[$table.'.']['conf.']['ignoreTCAsettings']) {
				unset($this[$table.'.']['conf.']);
				unset($confArray[$table.'.']['conf.']['ignoreTCAsettings']);
			} else {
				if (is_array($this[$table.'.']['conf.'])) foreach ($this[$table.'.']['conf.'] as $key=>$value) {
					// t3lib_div::debug(Array('check for [ignoreTCAsettings] in '=>'['.$table.'.][conf.]['.$key.']', 'File:Line'=>__FILE__.':'.__LINE__));
					if ($confArray[$table.'.']['conf.'][$key]['ignoreTCAsettings']) {
						unset($this[$table.'.']['conf.'][$key]);
					} else if ($confArray[$table.'.']['conf.'][$key]['ignoreTCAitems']) {
						unset($this[$table.'.']['conf.'][$key]['items.']);
					}
				}
			}
		}
		if (is_array($confArray)) foreach ($confArray as $key=>$value) {
			if (is_array($value['moreConf.']) && count($value['moreConf.'])) {
				$value['conf.'] = t3lib_div::array_merge_recursive_overrule((array) $value['conf.'],$value['moreConf.']) ;
			}

			$this[$key] = is_array($value) ?
				(is_array($this[$key]) ? t3lib_div::array_merge_recursive_overrule($this[$key],$value) : $value) :
				$value;
		}
		$this->debugData[] = Array('TCAconfData',Array('$this'=>$this,  'File:Line'=>__FILE__.':'.__LINE__));
		$this->_findAllReferences();
	}

	/**
	 * Return combined TCA- and Conf-Data
	 *
	 * @return	[type]		combined TCA- and Conf-Data
	 */
	function getCombined() {
		$this->_fCount(__FUNCTION__);
		return ((array) $this);
	}



	/******************************************************************************
	 *
	 * Getters
	 *
	 ******************************************************************************/

	/**
	 * Return value from config-array
	 *
	 * @param	string		$name of $confVar
	 * @return	string		Content.
	 */
	public function __get($nm) {
	    if (is_array($this[$nm.'.'])) {
			return ($this[$nm.'.']);
		} else {
			switch ($nm) {
				case 'full':
					t3lib_div::debug(Array('confObj->full!!!'=>1, 'File:Line'=>__FILE__.':'.__LINE__));
					return ((array) $this );
				case 'main':
					return (is_array(($this[$this->defaultTableName.'.'])) ? ($this[$this->defaultTableName.'.']) : Array() );
				case 'mainconf':
				case 'mainConf':
					return (is_array(($this[$this->defaultTableName.'.']['conf.'])) ?
							($this[$this->defaultTableName.'.']['conf.']) : Array() );
				case 'mainctrl':
				case 'mainCtrl':
					return (is_array(($this[$this->defaultTableName.'.']['ctrl.'])) ?
							($this[$this->defaultTableName.'.']['ctrl.']) : Array() );
					return ($this[$this->defaultTableName.'.']['ctrl.']);
				case 'mainsearch':
				case 'mainSearch':
					return (is_array(($this[$this->defaultTableName.'.']['search.'])) ?
							($this[$this->defaultTableName.'.']['search.']) : Array() );
				case 'maintable':
				case 'mainTable':
					return ($this->defaultTableName);
				case 'references':
					return ($this->getReferences($this->mainTable));
				case 'dodebug':
				case 'templates':
				case 'image':
				case 'format':
				case 'listmode':
				case 'lang':
				case 'list':
				case 'details':
				case 'search':
				case 'permit':
				case 'xajax':
				case 'view':
					return ( (array)$this[$nm] );
				default:
				if (isset($this[$nm]) && !is_array($this[$nm])) {
					return ($this[$nm]);
				} else if (isset($this[$nm.'.']) && is_array($this[$nm.'.'])){
					return ($this[$nm.'.']);
				} else {
					$error = 'variable "'.$nm.'" not defined in confObj !';
					if (is_object($this->debugObj)) {
						$this->debugObj->showError(0,$error,SGZLIB_FATALERROR);
					} else {
						die($error);
					}
				}
			}
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	public function get($name) {
		if (trim($name)) {
			$parts = explode ('.',$name);
			$findVal = $this;
			for ($i=0;$i<count($parts);$i++) {
				if ($i==count($parts)-1) {
					if (strcmp(substr($name,-1),'.')==0) {
						return ($findVal);
					} else {
						return ($findVal[$parts[$i]]);
					}
				} else {
					$findVal = $findVal[$parts[$i].'.'];
				}
			}
		} else {
			return '';
		}

	}

	/**
	 * Renders singleObject; if $conf is not an array then $name is returned via getData (if LLL:) or via insertData
	 *
	 * @param	string		The content object name, eg. "TEXT" or "USER" or "IMAGE"
	 * @param	array		The array with TypoScript properties for the content object
	 * @return	string		cObject output
	 */
	function TSObj ($name,$conf) {
		$this->_fCount(__FUNCTION__);
		if (is_array($conf)) {
			return ($this->cObj->cObjGetSingle($name,$conf));
		} if (strncmp($name,'EXT:',4)==0) {
			return (substr(t3lib_div::getFileAbsFileName($name),strlen(PATH_site)));
		} else {
			return (strncmp($name,'LLL:',4)) ? $this->cObj->insertData($name) : $this->cObj->getData($name,$this->cObj->data);
		}
	}


	/**
	 * Renders singleObject; if $conf is not an array then $name is returned via getData (if LLL:) or via insertData
	 *
	 * @param	array		The array with all TypoScript properties, containing TS for $name
	 * @param	string		The name of the TS Object or TS value
	 * @return	string		cObject output
	 */
	function TSConfObj ($conf,$name) {
		$this->_fCount(__FUNCTION__);
		if (is_array($conf[$name.'.'])) {
			return ($this->cObj->cObjGetSingle($conf[$name],$conf[$name.'.']));
		} if (strncmp($conf[$name],'EXT:',4)==0) {
			return (substr(t3lib_div::getFileAbsFileName($conf[$name]),strlen(PATH_site)));
		} else {
			return (strncmp($conf[$name],'LLL:',4)) ? $this->cObj->insertData($conf[$name]) : $this->cObj->getData($conf[$name],$this->cObj->data);
		}
	}


	/**
	 * Return value from somewhere inside a FlexForm structure
	 *
	 * @param	string		Field name to extract. Can be given like "test/el/2/test/el/field_templateObject" where each part will dig a level deeper in the FlexForm data.
	 * @param	string		Sheet pointer, eg. "sDEF"
	 * @param	string		Language pointer, eg. "lDEF"
	 * @param	string		Value pointer, eg. "vDEF"
	 * @return	string		The content.
	 */
	function getFFvalue($fieldName,$sheet='sDEF',$lang='lDEF',$value='vDEF')	{
		$this->_fCount(__FUNCTION__);
		$sheetArray = is_array($this->flexFormData) ? $this->flexFormData['data'][$sheet][$lang] : '';
		if (is_array($sheetArray))	{
			return $this->_getFFvalueFromSheetArray($sheetArray,explode('/',$fieldName),$value);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$debugObj: ...
	 * @return	[type]		...
	 */
	function setDebugObj ($debugObj) {
		$this->_fCount(__FUNCTION__);
		$this->debugObj = $debugObj;

		for ($i=0;$i<count($this->debugData);$i++) {
			$this->debugObj->debugIf($this->debugData[$i][0],$this->debugData[$i][1]);
		}
		$this->debugData = Array();
	}

	/******************************************************************************
	 *
	 * JS Functions
	 *
	 ******************************************************************************/

	/**
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	function addJs ($name) {
		$content = '';
		if ($name && !$this->addedJsFunctions[$name]) {
			$this->addedJsFunctions[$name] = TRUE;
			switch ($name) {
				case 'userFunctions':
					$fileName = $GLOBALS['TSFE']->tmpl->getFileName($this['userJsFunc']);
					$content = CRLF.'<script src="'.$fileName.'" type="text/javascript">'."\r\n".'</script>'."\r\n";
				break;
				case 'popupFunctions':
					$fileName = $GLOBALS['TSFE']->tmpl->getFileName($this['popupFunc']);
					$content = CRLF.'<script src="'.$fileName.'" type="text/javascript">'."\r\n".'</script>'."\r\n";
				break;
				case 'searchFunctions':
					$fileName = $GLOBALS['TSFE']->tmpl->getFileName($this['searchFunc']);
					$content = CRLF.'<script src="'.$fileName.'" type="text/javascript">'."\r\n".'</script>'."\r\n";
				break;
				default:
					//error;
				break;
			}
		}
		return ($content);
	}


	/******************************************************************************
	 *
	 * Local helpers
	 *
	 ******************************************************************************/

	/**
	 * Converts $this->cObj->data['pi_flexform'] from XML string to flexForm array.
	 *
	 * @param	string		Field name to convert
	 * @return	void
	 */
	public function initFlexForm($field='pi_flexform')	{
		$this->_fCount(__FUNCTION__);
		$this->flexFormData = is_array($this->cObj->data[$field]) ?
					$this->cObj->data[$field] : t3lib_div::xml2array($this->cObj->data[$field]);
		if (!is_array($this->flexFormData))	$this->flexFormData=array();
	}

	/**
	 * Returns part of $sheetArray pointed to by the keys in $fieldNameArray
	 *
	 * @param	array		Multidimensiona array, typically FlexForm contents
	 * @param	array		Array where each value points to a key in the FlexForms content - the input array will have the value returned pointed to by these keys. All integer keys will not take their integer counterparts, but rather traverse the current position in the array an return element number X (whether this is right behavior is not settled yet...)
	 * @param	string		Value for outermost key, typ. "vDEF" depending on language.
	 * @return	mixed		The value, typ. string.
	 * @access private
	 * @see pi_getFFvalue()
	 */
	private function _getFFvalueFromSheetArray($sheetArray,$fieldNameArr,$value)	{
		$this->_fCount(__FUNCTION__);

		$tempArr=$sheetArray;
		foreach($fieldNameArr as $k => $v)	{
			if (t3lib_div::testInt($v))	{
				if (is_array($tempArr))	{
					$c=0;
					foreach($tempArr as $values)	{
						if ($c==$v)	{
							#debug($values);
							$tempArr=$values;
							break;
						}
						$c++;
					}
				}
			} else {
				$tempArr = $tempArr[$v];
			}
		}
		return $tempArr[$value];
	}

	/**
	 * TCA-Table, converted with dots, stored as static to avoid duplicate creation
	 *
	 * @param	string		Tablename
	 * @param	array		If set, then set configTable; if NULL, return configTable
	 * @return	array		configData
	 */
	private function _configTCA ($tableName='',$config=NULL) {
		$this->_fCount(__FUNCTION__);
		static $configTable = Array();

		$tableName = $tableName ? $tableName : $this->defaultTableName;
		if (strcmp($tableName,'*')==0) {
			return($configTable);
		} else {
			if (isset($config)) {
				$configTable[$tableName] = $config;
			}
			return($configTable[$tableName]);
		}
	}


	/**
	 * Converts Array to Array, where key of subarrays are followed by '.'
	 *
	 * @param	mixed		Array/Value to convert
	 * @return	void
	 */
	private function _getDotArray($myValue) {
		$this->_fCount(__FUNCTION__);
		if (is_array($myValue)) {
			$retArray = Array();
			foreach ($myValue as $key=>$value) {
				if (is_array($value)) {
					$retArray[$key.'.'] = $this->_getDotArray($value);
				} else {
					$retArray[$key] = $value;
				}
			}
			return ($retArray);
		} else {
			return ($myValue);
		}
	}


	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @return	[type]		...
	 */
	public function getDescriptions($table='') {
		$descriptions = Array();
		if (!$table) {
			$table = $this->mainTable;
		}
		$descriptions['MAINTABLE'] = $table;
		$descriptions['uid'] = 'UID';
		$descriptions['hidden'] = $this->text['hidden'];
		$descriptions['disabled'] = $this->text['disabled'];

		$tmp = $this->$table;
		if (is_array($tmp['conf.'])) foreach ($tmp['conf.'] as $key=>$fieldConf) {
			$fieldName = substr($key,0,-1);
			$descriptions[$fieldName] = $fieldConf['label'];
			if (isset($fieldConf['label.'])) {
				$descriptions[$fieldName] = array('name'=>$fieldConf['label'],'conf'=>$fieldConf['label.']);
			} else {
				$descriptions[$fieldName] = $fieldConf['label'];
			}
		}

		return ($descriptions);
	}





}


?>