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
 *   37: class txsg_dispatcher extends tslib_pibase
 *   49:     function main($content,$conf)
 *
 * TOTAL FUNCTIONS: 1
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class txsg_dispatcher extends tslib_pibase {
	var $prefixId = 'tx_sgzz_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_sgzz_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey = 'sg_zz';	// The extension key.

	/**
	 * [Put your description here]
	 *
	 * @param	[type]		$content: ...
	 * @param	[type]		$conf: ...
	 * @return	[type]		...
	 */
	function main($content,$conf)	{
		// Init FlexForm configuration for plugin:
		$this->pi_initPIflexForm();
		$pluginMode = intval($this->pi_getFFvalue($this->cObj->data['pi_flexform'],'fieldModuleMode','sDefault'));
		$myPluginMode = $pluginMode;
		$myCmdMode = 0;
		$myConf = NULL;
		//t3lib_div::debug(Array('$conf'=>$conf, 'File:Line'=>__FILE__.':'.__LINE__));
		if (is_array($conf['dispatcher.'])) {
			if ($pluginMode && is_array($conf['dispatcher.'][$pluginMode.'.'])) {
				$myConf = $conf['dispatcher.'][$pluginMode.'.'];
			}
			if (!$myConf && $conf['CMD']) {
				foreach ($conf['dispatcher.'] as $key=>$value) {
					if (strcmp($value['cmd'],$conf['CMD'])!=0) {
						$myConf = $value;
						$myCmdMode = 1;
						$myPluginMode = intval($key);
					}
				}
			}
		}

		if (is_array($myConf)) {
			if (!is_array($myConf['conf.'])) {
				$myConf['conf.'] = Array();
			}
			if (!$myConf['conf.']['includeLibs'] && $conf['includeLibs']) {
				$myConf['conf.']['includeLibs'] = $conf['includeLibs'];
			}
			if (!$myConf['conf.']['userFunc'] && $conf['userFunc']) {
				$myConf['conf.']['userFunc'] = $conf['userFunc'];
			}
			unset($conf['dispatcher.']);
			$conf = t3lib_div::array_merge_recursive_overrule($conf,$myConf['conf.']);
			$conf['pluginMode'] = $myPluginMode;
			$conf['cmdMode'] = $myCmdMode;
			$conf['cached'] = (strcmp(substr($myConf['conf'],-4),'_INT')!=0);
			// t3lib_div::debug(Array('$conf'=>$conf, 'File:Line'=>__FILE__.':'.__LINE__));
			$tmp .= '<!-- Dispatcher('.$this->prefixId.',Mode='.$myPluginMode.'/'.$myCmdMode.') Rendered at ('.date('H:i:s').') -->'.CRLF;
			$tmp .= $this->cObj->cObjGetSingle($myConf['conf'],$conf);
			$content .= $this->pi_wrapInBaseClass($tmp);
		} else {
			$content .= $this->pi_wrapInBaseClass('<br /><b>ERROR: Could not find pluginMode='.$pluginMode.' nor CMD="'.$conf['CMD'].'" </b><br /><br />');
		}


		return ($content);
	}


}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/classes/base/class.txsg_dispatcher.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/classes/base/class.txsg_dispatcher.php']);
}
?>