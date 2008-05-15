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
 *   79:     private function init(tx_sglib_config $confObj, tx_sglib_debug $debugObj)
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
	private $cObj;
	private $conf=Array();
	private $defaultDesignator;
	private $langUid;
	private $llKey;

	protected function __construct() {}

	private function __clone() {}

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
		$this->cObj = $factoryObj->cObj;
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
		$this->_fCount(__FUNCTION__);
		if (isset($this->LOCAL_LANG[$this->llKey][$key]))	{
			$word = $this->LOCAL_LANG[$this->llKey][$key];
		} elseif (isset($this->LOCAL_LANG['default'][$key]))	{
			$word = $this->LOCAL_LANG['default'][$key];
		} else {
			$word = $this->alt;
		}

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
	 * @param	[type]		$text: ...
	 * @param	[type]		$refTableLangOl: ...
	 * @param	[type]		$langUid: ...
	 * @return	[type]		...
	 */
	function replaceLangOverlay(&$row,$table,$refTableLangOl=NULL) {
		if (!isset($refTableLangOl)) {
			$refTableLangOl = $GLOBALS['TCA'][$table]['ctrl']['lang_ol'];
		}
		$this->_fCount(__FUNCTION__);
		if (is_array($refTableLangOl) && $this->langUid>0) foreach ($refTableLangOl as $key => $value) {
			$tmp = t3lib_div::trimExplode('|',$row[$value]);
			unset ($row[$value]);
			if (trim($tmp[$this->langUid-1])) {
				$row[$key] = $tmp[$this->langUid-1];
			}
		}
	}


}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sglib_lang.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sglib_lang.php']);
}
?>