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

	public $conf;
	protected $myCmdMode;
	protected $myPluginMode;
	protected $memAtStart;

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
		$this->conf = $conf;
		$this->myPluginMode = $pluginMode;
		$this->myCmdMode = 0;
		$myConf = NULL;
		// t3lib_div::debug(Array('$this->conf'=>$this->conf, 'File:Line'=>__FILE__.':'.__LINE__));
		if (is_array($this->conf['dispatcher.'])) {
			if ($pluginMode && is_array($this->conf['dispatcher.'][$pluginMode.'.'])) {
				$myConf = $this->conf['dispatcher.'][$pluginMode.'.'];
			}
			if (!$myConf && $this->conf['CMD']) {
				foreach ($this->conf['dispatcher.'] as $key=>$value) {
					if (strcmp($value['cmd'],$this->conf['CMD'])==0) {
						$myConf = $value;
						$this->myCmdMode = 1;
						$this->myPluginMode = intval($key);
					}
				}
			}
		}

		if ($myConf['execList']) {
			$dispatcherConf = $this->conf['dispatcher.'];
			$idList = t3lib_div::trimExplode(',',$myConf['execList']);
			foreach ($idList as $id) if ($id) {
				$myConf = $dispatcherConf[$id.'.'];
				$content .= $this->executeMyConf($myConf);
			}
			return ($content);
		} else {
			$content = $this->executeMyConf($myConf);
		}
		return ($content);
	}

	protected function executeMyConf(array $myConf) {
		// t3lib_div::debug(Array('$myConf'=>$myConf, 'File:Line'=>__FILE__.':'.__LINE__));
		if (is_array($myConf)) {
			if (!is_array($myConf['conf.'])) {
				$myConf['conf.'] = Array();
			}
			if (!$myConf['conf.']['includeLibs'] && $this->conf['includeLibs']) {
				$myConf['conf.']['includeLibs'] = $this->conf['includeLibs'];
			}
			if (!$myConf['conf.']['userFunc'] && $this->conf['userFunc']) {
				$myConf['conf.']['userFunc'] = $this->conf['userFunc'];
			}
			unset($this->conf['dispatcher.']);
			// t3lib_div::debug(Array('$myConf[conf.]'=>$myConf['conf.'], 'File:Line'=>__FILE__.':'.__LINE__));
			$this->conf = t3lib_div::array_merge_recursive_overrule($this->conf,$myConf['conf.']);
			$this->conf['pluginMode'] = $this->myPluginMode;
			$this->conf['cmdMode'] = $this->myCmdMode;
			$this->conf['cached'] = (strcmp(substr($myConf['conf'],-4),'_INT')!=0);
			// t3lib_div::debug(Array('$this->conf'=>$this->conf, 'File:Line'=>__FILE__.':'.__LINE__));
			if ($this->conf['CMD'] || $this->conf['noComments']) {
				$tmp = trim($this->cObj->cObjGetSingle($myConf['conf'],$this->conf));
			} else {
				$tmp .= "\n".'<!-- Dispatcher('.$this->prefixId.',Mode='.$this->myPluginMode.'/'.$this->myCmdMode.') '.
					'Rendered at ('.date('H:i:s').') '.
					'Cached='.intval($this->conf['cached']).' Mode ('.$myConf['conf'].') -->'.CRLF;
				$tmp .= $this->cObj->cObjGetSingle($myConf['conf'],$this->conf)."\n".'<!-- DispatcherEnd ('.$this->prefixId.',Mode='.$this->myPluginMode.'/'.$this->myCmdMode.') '.
					'Rendered at ('.date('H:i:s').') '.
					'Cached='.intval($this->conf['cached']).' Mode ('.$myConf['conf'].') End -->'."\n";
			}
			//$content .= $this->pi_wrapInBaseClass($tmp);
			$content .= $tmp;
		} else {
			$content .= $this->pi_wrapInBaseClass('<br /><b>ERROR: Could not find pluginMode='.$pluginMode.' nor CMD="'.$this->conf['CMD'].'" </b><br /><br />');
		}

		return ($content);
	}


}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/classes/base/class.txsg_dispatcher.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/classes/base/class.txsg_dispatcher.php']);
}
?>