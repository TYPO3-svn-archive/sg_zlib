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
 *   72: class tx_sglib_config extends ArrayIterator
 *  110:     private function init($designator, tx_sglib_factory $factoryObj, $conf)
 *  134:     public function setParentObject($parentCObj)
 *  151:     public function setPluginConfig($pluginSubMode, $pluginMode, $cmdMode, $cached)
 *  165:     protected function _initRecursive($recursive)
 *  181:     public function _findAllReferences()
 *  214:     protected function getLabelField($table)
 *  231:     private function _fCount ($name=NULL)
 *  254:     function __destruct()
 *
 *              SECTION: Functions
 *  273:     function setDesignator($string)
 *  283:     function getDesignator()
 *  293:     function getPid()
 *  303:     function getPidList()
 *  316:     function setTCAname($tableName)
 *  326:     function getTCAname()
 *  337:     function setTCAdata($tableName='')
 *  380:     function getTCAdata($tableName='')
 *  391:     function setPreConfData($name, array $conf)
 *  404:     function setConfData(array $conf)
 *  418:     function setLocalConf($localConf=NULL)
 *  440:     function getConfData()
 *  464:     function combineTCAandConf($tableName='')
 *  514:     function getCombined()
 *
 *              SECTION: Getters
 *  533:     public function __get($nm)
 *  593:     function TSObj ($name,$conf)
 *  613:     function getFFvalue($fieldName,$sheet='sDEF',$lang='lDEF',$value='vDEF')
 *  627:     function setDebugObj ($debugObj)
 *
 *              SECTION: Local helpers
 *  649:     public function initFlexForm($field='pi_flexform')
 *  666:     private function _getFFvalueFromSheetArray($sheetArray,$fieldNameArr,$value)
 *  697:     private function _configTCA ($tableName='',$config=NULL)
 *  719:     private function _getDotArray($myValue)
 *
 * TOTAL FUNCTIONS: 30
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

	protected $pid_list,$pid;
	protected $references;

	// protected function __construct() {}

	private function __clone() {}

	public static function getInstance($designator, tx_sglib_factory $factoryObj, $conf) {
		if (!isset(self::$instance[$designator])) {
			self::$instance[$designator] = new tx_sglib_config();
			self::$instance[$designator]->init($designator, $factoryObj, $conf);
		}
		return (self::$instance[$designator]);
	}

	/**
	 * [Describe function...]
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
	 * [Describe function...]
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
	public function _findAllReferences() {
		$references = Array('table'=>array(), 'field'=>array(), 'asName'=>array(), 'join'=>array(), 'joinMain'=>array(), 'label'=>array());
		foreach ($this->mainConf as $key=>$fieldConf) {
			$fieldName = substr($key,0,-1);
			$table = '';
			if ($fieldConf['allowed'] && strcmp($fieldConf['internal_type'],'db')==0) {
				$table = $fieldConf['allowed'];
			} else if ($fieldConf['foreign_table']) {
				$table = $fieldConf['foreign_table'];
			}
			if ($table) {
				$references['table'][$fieldName] = $table;
				$references['field'][$table] = $fieldName;
				$references['asName'][$table] = $fieldName.'#ref';
				$references['join'][$table] = ' LEFT JOIN '.$table.' ON '.$this->mainTable.'.'.$fieldName.'='.$table.'.uid  ';
				$references['joinMain'][$table] = ' LEFT JOIN '.$this->mainTable.' ON '.$this->mainTable.'.'.$fieldName.'='.$table.'.uid  ';
				$references['label'][$table] = $this->getLabelField($table);
			}

			if (strcmp($fieldConf['allowed'],$field[0])==0 || strcmp($fieldConf['foreign_table'],$field[0])==0) {
				$retVal = ' LEFT JOIN '.$field[0].' ON '.$this->mainTable.'.'.$fieldName.'='.$field[0].'.uid  ';
			}
		}
		//t3lib_div::debug(Array('$references'=>$references, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($references);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @return	[type]		...
	 */
	protected function getLabelField($table) {
		$labelField = '';

		$labelField = $this->mainCtrl['label'];
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
			// t3lib_div::debug(Array('check for [ignoreTCAsettings] in '=>$table, 'File:Line'=>__FILE__.':'.__LINE__));
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
			$this[$key] = is_array($value) ?
				(is_array($this[$key]) ? t3lib_div::array_merge_recursive_overrule($this[$key],$value) : $value) :
				$value;
		}
		$this->debugData[] = Array('TCAconfData',Array('$this'=>$this,  'File:Line'=>__FILE__.':'.__LINE__));
		$this->references = $this->_findAllReferences();
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
					return (is_array($this->references) ? $this->references : ($this->references = $this->_findAllReferences()));
				case 'dodebug':
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
	 * Renders singleObject; if $conf is not an array then $name is returned via getData (if LLL:) or via insertData
	 *
	 * @param	string		The content object name, eg. "TEXT" or "USER" or "IMAGE"
	 * @param	array		The array with TypoScript properties for the content object
	 * @return	string		cObject output
	 */
	function TSObj ($name,$conf) {
		if (is_array($conf)) {
			return ($this->cObj->cObjGetSingle($name,$conf));
		} if (strncmp($name,'EXT:',4)==0) {
			return (substr(t3lib_div::getFileAbsFileName($name),strlen(PATH_site)));
		} else {
			return (strncmp($name,'LLL:',4)) ? $this->cObj->insertData($name) : $this->cObj->getData($name,$this->cObj->data);
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
}


?>