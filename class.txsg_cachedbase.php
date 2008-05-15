<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2007 Stefan Geith <typo3devYYYY@geithware.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
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

require_once(PATH_tslib.'class.tslib_pibase.php');
require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_modelbase.php');

 	define ('SGZLIB_TEXT', 0);
	define ('SGZLIB_FORM', 1);
	define ('SGZLIB_AUTO', 2);
	define ('SGZLIB_AUTOHIDDEN', 3);
	define ('SGZLIB_CMD', 4);
	define ('SGZLIB_LIST', 5);
	define ('SGZLIB_LISTEDIT', 6);
	define ('SGZLIB_SEARCH', 7);
	define ('SGZLIB_SEARCHALL', 7);
	define ('SGZLIB_SEARCHUSED', 8);
	define ('SGZLIB_SEARCHUSEDPLUS', 9);

/**
 * Plugin 'Sartorius Mechatronics PDFs' for the 'sartorius_mech_pdf' extension.
 *
 * @author	Stefan Geith <typo3devYYYY@geithware.de>
 * @package	TYPO3
 * @subpackage	tx_sartoriusmechpdf
 */
class txsg_cached_base extends tslib_pibase {
	var $prefixId = 'tx_sgzz_pi1';		// Same as class name
	var $callcountname = 'tx_sgzz_pi1_cc';
	var $scriptRelPath = 'pi1/class.tx_sgzz_pi1.php';	// Path to this script relative to the extension dir.
	var $mainTable = '';
	var $mainJoin = '';
	var $mainJoinTable = '';
	var $catTable = '';
	var $subcatTable = '';

	var $pi_checkCHash = TRUE;
	var $pi_USER_INT_obj = 0;

	var $factoryObj;
	var $confObj;
	var $debugObj;
	var $constObj;
	var $paramsObj;
	var $templateObj;
	var $permitObj;
	var $langObj;
	var $itemsObj;
	var $divObj;

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The		content that is displayed on the website
	 */
	function main($content,$conf)	{
		$content = '';

		$this->factoryObj = tx_sglib_factory::getInstance($this->prefixId, $this->cObj, $conf);
		$this->factoryObj->setBaseTables($this->mainTable, '*') ;
		$this->divObj = $this->factoryObj->divObj;
		$ms = $this->divObj->getMicroSec();

		$this->confObj = $this->factoryObj->confObj;
		$this->debugObj = $this->factoryObj->debugObj;
		$this->constObj = $this->factoryObj->constObj;
		$this->paramsObj = $this->factoryObj->paramsObj;

		$this->conf = $this->confObj->getCombined();
		$this->myPage = $this->pi_getPageLink($TSFE->id,'','');
		if (!strstr($this->myPage,'?')) {
			$this->myPage .= '?';
		}

		$this->pluginMode = $this->preprocessPluginMode(intval($conf['pluginMode']));
		$tmp = $this->switchPluginMode($this->pluginMode);
		if (is_array($this->conf['stdWrapAll.'])) {
			$tmp = $this->cObj->stdWrap($tmp,$this->conf['stdWrapAll.']);
		}

		$content .= $tmp.CRLF.'<!-- '.$this->prefixId.' duration='.intval($this->divObj->getMicrodur($ms)).'ms time='.date('Y-m-d/H:i:s').' -->'.CRLF;

		return ($content);

	}


	/**
	 * Sets Pluginmode, if plugin is placed by TS
	 *
	 * @param	[type]		$pluginMode: ...
	 * @return	[type]		...
	 */
	function preprocessPluginMode($pluginMode) {
		return ($pluginMode);
	}


	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$pluginMode: ...
	 * @return	[type]		...
	 */
	function switchPluginMode ($pluginMode) {
		$GLOBALS['_GET'][$this->callcountname]++;
		$content = '';

		if ($this->conf['pluginSubMode']) {
			if (method_exists ($this, $this->conf['pluginSubMode'])) {
				$content = call_user_method ($this->conf['pluginSubMode'], $this);
			} else {
				$content = '<br /><b>ERROR: pluginSubMode "'.$this->conf['pluginSubMode'].'" is not (yet) defined !</b><br /><br />';
			}
		} else {
			$content .= 'No Plugin-Mode set: '.$this->scriptRelPath.'->main('.$pluginMode.')!';
		}
		return ($content);
	}


}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.txsg_cached.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.txsg_cached.php']);
}

?>