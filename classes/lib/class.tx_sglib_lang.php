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
 *   50: class tx_sglib_lang
 *   79:     private function init(tx_sglib_factory $factoryObj)
 *  104:     private function _fCount ($name=NULL)
 *  127:     function __destruct()
 *  136:     function _initGlobalLangObject ()
 *  156:     function setLocalLangFile($fileName)
 *  171:     function getActiveLanguage ()
 *
 *              SECTION: LocalLang
 *  208:     function getLLL($label,$default='')
 *  226:     function getLL($key,$alt='',$hsc=FALSE)
 *  247:     function getLangArray($fileNameBase,$fileNameExtension)
 *
 *              SECTION: String functions
 *  273:     function replaceLangOverlay(&$row,$table,$refTableLangOl=NULL)
 *
 * TOTAL FUNCTIONS: 10
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_lang {
	private static $instance = Array();

	private $factoryObj = NULL;
	private $confObj;
	private $debugObj;
	private $conf=Array();
	private $defaultDesignator;
	private $langUid;
	private $llKey;

	protected function __construct() {}

	private function __clone() {}

	/**
	 * Returns a singlton instance of tx_sglib_lang
	 *
	 * @param	string				Designator
	 * @param	tx_sglib_factory	FactoryObj
	 * @return	tx_sglib_lang		Instantiated Object
	 */
	
	public static function getInstance($designator, tx_sglib_factory $factoryObj) {
		if (!isset(self::$instance[$designator])) {
			self::$instance[$designator] = new tx_sglib_lang();
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
		$this->conf = (array) $this->confObj->lang;

		$this->_initGlobalLangObject();
		$this->llKey = $this->getActiveLanguage();
		$this->langUid = $GLOBALS['TSFE']->config['config']['sys_language_uid'];
		$override = $this->confObj->lang['override.'];
		if (isset($override['sys_language_uid'])) {
			$this->langUid = intval($override['sys_language_uid']);
		}

		$this->debugObj->debugIf('langConf',Array('getActiveLanguage'=>$this->getActiveLanguage()));
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
	function _initGlobalLangObject () {
		$this->_fCount(__FUNCTION__);
		if (!is_object($GLOBALS['LANG'])) {
				require_once(t3lib_extMgm::extPath('lang').'lang.php');
				$GLOBALS['LANG'] = t3lib_div::makeInstance('language');
				$GLOBALS['LANG']->init('default');
		}
		$GLOBALS['LANG']->lang = $this->getActiveLanguage();
		$GLOBALS['TSFE']->lang = $this->getActiveLanguage();
		if (!is_object($GLOBALS['LANG']->csConvObj)) {
			$GLOBALS['LANG']->csConvObj = t3lib_div::makeInstance('t3lib_cs');
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$fileName: ...
	 * @return	[type]		...
	 */
	function setLocalLangFile($fileName) {
		$this->localLangFile = $fileName;
		$this->_fCount(__FUNCTION__);
		$fileLL = $GLOBALS['TSFE']->tmpl->getFileName($fileName);
		if (!$this->LOCAL_LANG_loaded && is_file($fileLL)) {
			include ('./'.$fileLL);
			$this->LOCAL_LANG = $LOCAL_LANG;
		}
		$this->LOCAL_LANG_loaded = 1;
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getActiveLanguage () {
		$this->_fCount(__FUNCTION__);
		$LLkey = $GLOBALS['TSFE']->pSetup['config.']['language'];
		$LLkey = $LLkey ? $LLkey : $GLOBALS['TSFE']->tmpl->setup['config.']['language'];
		$LLkey = (!$LLkey || strcmp($LLkey,'en')==0) ? 'default' : $LLkey ;
		return ($LLkey);
	}

	function get ($text) { // replaces felib->getLL
		$this->_fCount(__FUNCTION__);
		return '[L[-'.$text.'-]L]';
	}











	/******************************************************************************
	 *
	 * LocalLang
	 *
	 ******************************************************************************/


	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$label: ...
	 * @param	[type]		$default: ...
	 * @return	[type]		...
	 */
	function getLLL($label,$default='') {
		$this->_fCount(__FUNCTION__);
		if (strcmp(substr($label,0,4),'LLL:'))	{
			return ($label);
		} else {	// LOCAL_LANG:
			$out = $GLOBALS['LANG']->sL($label);
			if (!isset($out)) { $out = $default; }
			if (!isset($out) && $label) { $out = '['.$label.']'; }
			return ($out);
		}
	}

	/**
	 * @param	[type]		$key: ...
	 * @param	[type]		$alt: ...
	 * @param	[type]		$hsc: ...
	 * @return	[type]		...
	 */
	function getLL($key,$alt='',$hsc=FALSE)	{
		if ($this->localLangFile) {
			return ($this->getLLL('LLL:'.$this->localLangFile.':'.$key,$alt));
		}
		$this->_fCount(__FUNCTION__);
		if (isset($this->LOCAL_LANG[$this->llKey][$key]))	{
			$word = $this->LOCAL_LANG[$this->llKey][$key];
		} elseif (isset($this->LOCAL_LANG['default'][$key]))	{
			$word = $this->LOCAL_LANG['default'][$key];
		} else {
			$word = $alt;
		}
		// if ($alt=="hidden") t3lib_div::debug(Array('$word'=>$word, '1'=>$this->LOCAL_LANG, 'File:Line'=>__FILE__.':'.__LINE__));
		if ($hsc)	$word = htmlspecialchars($word);
		return $word;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$fileNameBase: ...
	 * @param	[type]		$fileNameExtension: ...
	 * @return	[type]		...
	 */
	function getLangArray($fileNameBase,$fileNameExtension) {
		$this->_fCount(__FUNCTION__);
		$out = array();

		$tempLOCAL_LANG = t3lib_div::readLLfile($fileNameBase,$this->getActiveLanguage());

		$out = $tempLOCAL_LANG['default'];
		for (reset($out);$key=key($out);next($out)) {
			$out[$key] = $tempLOCAL_LANG[$this->llKey][$key];
		}

		return ($out);
	}

	/******************************************************************************
	 *
	 * String functions
	 *
	 *****************************************************************************/

	/**
	 * @param	[type]		$row: data-row; if langUid is not default, then values will be replaced by their aproppriate language-values
	 * @param	[type]		$table: tablename of data-row
	 * @param	[type]		$refTableLangOl: ...
	 * @param	[type]		$refTableLangLol: ...
	 * @return	[type]		$row: modified data-row
	 */
	function replaceLangOverlayArray(&$rows,$table,$refTableLangOl=NULL,$refTableLangLol=NULL) {
		$this->_fCount(__FUNCTION__);
		if (is_array($rows)) foreach ($rows as $key=>$row) {
			$this->replaceLangOverlay(&$rows[$key],$table,$refTableLangOl,$refTableLangLol);
		}
	}

	/**
	 * @param	[type]		$row: data-row; if langUid is not default, then values will be replaced by their aproppriate language-values
	 * @param	[type]		$table: tablename of data-row
	 * @param	[type]		$refTableLangOl: ...
	 * @param	[type]		$refTableLangLol: ...
	 * @return	[type]		$row: modified data-row
	 */
	function replaceLangOverlay(&$row,$table,$refTableLangOl=NULL,$refTableLangLol=NULL,$recursion=0) {
		$this->_fCount(__FUNCTION__);
		if ($this->langUid>0) {
			if (!isset($refTableLangOl)) {
				$refTableLangOl = $GLOBALS['TCA'][$table]['ctrl']['lang_ol'];
			}
			if (!isset($refTableLangLol)) {
				$refTableLangLol = $GLOBALS['TCA'][$table]['ctrl']['lang_lol'];
			}
			
			if (is_array($refTableLangOl)) foreach ($refTableLangOl as $key => $value) {
				$tmp = t3lib_div::trimExplode('|',$row[$value]);
				unset ($row[$value]);
				if (trim($tmp[$this->langUid-1])) {
					$row[$key] = $tmp[$this->langUid-1];
				}
			}
			if (is_array($refTableLangLol)) foreach ($refTableLangLol as $key => $value) {
				if (trim($row[$value.intval($this->langUid)])) {
					$row[$key] = trim($row[$value.intval($this->langUid)]);
				}
			}
			if ($recursion>0) {
				foreach ($row as $key=>$field) {
					if (strcmp(substr($key,-6),'_array')==0 && is_array($field)) {
						$first = reset($field);
						if (is_array($first)) {
							$table = $first['TABLE'];
							// t3lib_div::debug(Array('table='=>$table,'File:Line'=>__FILE__.':'.__LINE__));
							foreach ($field as $sKey=>$sField) if (is_array($sField)) {
								$this->replaceLangOverlay($row[$key][$sKey],$table,$refTableLangOl,$refTableLangLol,$recursion-1);
							}
						}
					} elseif (strcmp(substr($key,-7),'_record')==0 && is_array($field)) {
						$this->replaceLangOverlay($row[$key],$field['TABLE'],$refTableLangOl,$refTableLangLol,$recursion-1);
						// t3lib_div::debug(Array('table='=>$field['TABLE'], $key=>$row[$key], 'File:Line'=>__FILE__.':'.__LINE__));
					}
				}
			}
		}
	}


	public function getLangUid () {
		return ($this->langUid);
	}

	public function getLangOlString($label,$label_ol) {
		if ($this->langUid>0) {
			$tmp = t3lib_div::trimExplode('|',$label_ol);
			if (trim($tmp[$this->langUid-1])) {
				$label = $tmp[$this->langUid-1];
			}
		}
		return ($label);
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


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sglib_lang.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sglib_lang.php']);
}
?>