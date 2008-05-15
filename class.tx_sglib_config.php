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
 *   71: class tx_sglib_config
 *  106:     private function init($designator, $factoryObj, $conf)
 *  129:     public function setParentObject($parentCObj)
 *  140:     private function _initRecursive($recursive)
 *  157:     private function _fCount ($name=NULL)
 *  180:     function __destruct()
 *
 *              SECTION: Functions
 *  199:     function setDesignator($string)
 *  209:     function getDesignator()
 *  219:     function getPid()
 *  229:     function getPidList()
 *  242:     function setTCAname($tableName)
 *  252:     function getTCAname()
 *  263:     function setTCAdata($tableName='')
 *  306:     function getTCAdata($tableName='')
 *  318:     function setConfData($conf,$localConf=NULL)
 *  347:     function mergeWithConfData($conf)
 *  357:     function getConfData()
 *  368:     function combineTCAandConf($tableName='')
 *  411:     function getCombined()
 *  424:     function getBase($name,$tableName='')
 *
 *              SECTION: Getters
 *  448:     function get($name)
 *  466:     function getTbl($name)
 *  485:     function getByArgs()
 *  501:     function getTblByArgs()
 *  520:     function getFFvalue($fieldName,$sheet='sDEF',$lang='lDEF',$value='vDEF')
 *  534:     function setDebugObj ($debugObj)
 *
 *              SECTION: Local helpers
 *  556:     public function initFlexForm($field='pi_flexform')
 *  574:     private function _getFFvalueFromSheetArray($sheetArray,$fieldNameArr,$value)
 *  605:     private function _configTCA ($tableName='',$config=NULL)
 *  627:     private function _getDotArray($myValue)
 *
 * TOTAL FUNCTIONS: 29
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_config {
	private static $instance = Array();

	private $factoryObj = NULL;
	private $config = Array();
	private $confData = Array();
	private $flexFormData;
	private $defaultTableName = '';
	private $debugObj=NULL;
	private $debugData = Array();
	private $defaultDesignator;
	private $cObj;

	private $pid_list,$pid;

	protected function __construct() {}

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
		$this->cObj = $parentCObj;
		$this->initFlexForm();
	}

	/**
	 * Extends the internal pid_list by the levels given by $recursive
	 *
	 * @param	[type]		$recursive: ...
	 * @return	[type]		...
	 */
	private function _initRecursive($recursive)	{
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
	 * [Describe function...]
	 *
	 * @param	array		Array with plugins configuration
	 * @param	string		LocalConf that overwrites $conf; if empty will be searchted in flexform
	 * @return	array		void
	 */
	function setConfData($conf,$localConf=NULL) {
		$this->_fCount(__FUNCTION__);
		if (!isset($localConf)) {
			$localConf = $this->getFFvalue('fieldLocalConf','sLocalConf');
		}

		if (strlen($localConf)>1) {
			$parseObj = t3lib_div::makeInstance('t3lib_TSparser');
			$parseObj->parse($localConf);
			if (is_array($parseObj->setup)) {
				if (is_array($conf)) {
					$conf = t3lib_div::array_merge_recursive_overrule($conf,$parseObj->setup);
				} else {
					$conf = $parseObj->setup;
				}
			}
		} else if (is_array($localConf)) {
			$conf = t3lib_div::array_merge_recursive_overrule($conf,$localConf);
		}
		$this->confData = $conf;
		$this->debugData[] = Array('confData',Array('confData'=>$confData, 'File:Line'=>__FILE__.':'.__LINE__));
	}

	/**
	 * Merge this with existing confData
	 *
	 * @param	array		Array with configuration
	 * @return	array		void
	 */
	function mergeWithConfData($conf) {
		$this->_fCount(__FUNCTION__);
		$this->confData = t3lib_div::array_merge_recursive_overrule($conf,$this->confData);
	}

	/**
	 * Return confData
	 *
	 * @return	[type]		Conf-Data
	 */
	function getConfData() {
		$this->_fCount(__FUNCTION__);
		return($this->confData);
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
		if (strcmp($tableName,'*')==0) {
			foreach ($this->_configTCA($tableName) as $table=>$config) {
			$this->config[$table] = 'TABLEDEF';
			$this->config[$table.'.'] = $config;
			}
		} else {
			$this->config[$tableName] = 'TABLEDEF';
			$this->config[$tableName.'.'] = $this->_configTCA($tableName);
		}

		$confArray = $this->confData;
		foreach ($this->config as $table=>$config) if (strcmp($config,'TABLEDEF')==0) {
			// t3lib_div::debug(Array('check for [ignoreTCAsettings] in '=>$table, 'File:Line'=>__FILE__.':'.__LINE__));
			if ($confArray[$table.'.']['ignoreTCAsettings']) {
				unset($this->config[$table.'.']);
			} else if ($confArray[$table.'.']['ctrl.']['ignoreTCAsettings']) {
				unset($this->config[$table.'.']['ctrl.']);
			} else if ($confArray[$table.'.']['conf.']['ignoreTCAsettings']) {
				unset($this->config[$table.'.']['conf.']);
				unset($confArray[$table.'.']['conf.']['ignoreTCAsettings']);
			} else {
				if (is_array($this->config[$table.'.']['conf.'])) foreach ($this->config[$table.'.']['conf.'] as $key=>$value) {
					// t3lib_div::debug(Array('check for [ignoreTCAsettings] in '=>'['.$table.'.][conf.]['.$key.']', 'File:Line'=>__FILE__.':'.__LINE__));
					if ($confArray[$table.'.']['conf.'][$key]['ignoreTCAsettings']) {
						unset($this->config[$table.'.']['conf.'][$key]);
					} else if ($confArray[$table.'.']['conf.'][$key]['ignoreTCAitems']) {
						unset($this->config[$table.'.']['conf.'][$key]['items.']);
					}
				}
			}
		}
		$this->config = t3lib_div::array_merge_recursive_overrule($this->config,(is_array($confArray) ? $confArray : Array()));
		$this->debugData[] = Array('TCAconfData',Array('config'=>$this->config, 'File:Line'=>__FILE__.':'.__LINE__));
	}

	/**
	 * Return combined TCA- and Conf-Data
	 *
	 * @return	[type]		combined TCA- and Conf-Data
	 */
	function getCombined() {
		$this->_fCount(__FUNCTION__);
		return ($this->config);
	}


	/**
	 * Return value from config-array
	 *
	 * @param	string		$name: Field name to extract
	 * @param	string		$tableName: Tablename
	 * @return	string		Content.
	 */
	function getBase($name,$tableName='') {
		$this->_fCount(__FUNCTION__);
		$tableName = $tableName ? $tableName : $this->defaultTableName;
		if ($name=='conf') {
			return (t3lib_div::array_merge_recursive_overrule($this->config[$tableName][$name],$this->config[$tableName]['moreConf']));
		} else if ($name=='ctrl' || $name=='search' || $name=='export' || $name=='mail' || $name=='listmode') {
			return ($this->config[$tableName][$name]);
		} else {
			return ($this->config[$name]);
		}
	}

	/******************************************************************************
	 *
	 * Getters
	 *
	 ******************************************************************************/

	/**
	 * Return value from config-array
	 *
	 * @param	string		$vars: ArrayKeys e.g. ('conf.cat.value') or ('conf.cat.')
	 * @return	string		Content.
	 */
	function get($name) {
		$this->_fCount(__FUNCTION__);
		$retVal = $this->config;
		$tmp = explode('.',trim($name));
		for ($i=0;$i<count($tmp)-1;$i++) {
			$retVal = $retVal[$tmp[$i].'.'];
		}
		if ($tmp[$i]) {
			$retVal = $retVal[$tmp[$i]];
		}
		return ($retVal);
	}
	/**
	 * Return value from config-array
	 *
	 * @param	string		$vars: ArrayKeys e.g. ('conf.cat.value') or ('conf.cat.')
	 * @return	string		Content.
	 */
	function getTbl($name) {
		$this->_fCount(__FUNCTION__);
		$retVal = $this->config[$this->defaultTableName.'.'];
		$tmp = explode('.',trim($name));
		for ($i=0;$i<count($tmp)-1;$i++) {
			$retVal = $retVal[$tmp[$i].'.'];
		}
		if ($tmp[$i]) {
			$retVal = $retVal[$tmp[$i]];
		}
		return ($retVal);
	}

	/**
	 * Return value from config-array
	 *
	 * @param	string		*	$vars[]: ArrayKeys e.g. ('conf.','cat.','value') or ('conf.','cat.')
	 * @return	string		Content.
	 */
	function getByArgs() {
		$this->_fCount(__FUNCTION__);
		$retVal = $this->config;
		$args = func_get_args();
		for ($i=0;$i<func_num_args();$i++) {
			$retVal = $retVal[$args[$i]];
		}
		return ($retVal);
	}

	/**
	 * Return value from config-array[$this->defaultTableName]
	 *
	 * @param	string		$vars[]: ArrayKeys
	 * @return	string		Content.
	 */
	function getTblByArgs() {
		$this->_fCount(__FUNCTION__);
		$retVal = $this->config[$this->defaultTableName.'.'];
		$args = func_get_args();
		for ($i=0;$i<func_num_args();$i++) {
			$retVal = $retVal[$args[$i]];
		}
		return ($retVal);
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