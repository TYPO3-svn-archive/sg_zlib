<?php
/***************************************************************
*  Copyright notice
*
*  (c) 1999-2005 Kasper Skaarhoj (kasperYYYY@typo3.com)
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
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Module: Log-viewing
 *
 * This module lets you view the changelog.
 *
 * extended by Stefan Geith <typo3dev2006@geithware.de>
 *
 * @author	Kasper Skårhøj <kasperYYYY@typo3.com>
 */
class ux_SC_mod_tools_log_index extends SC_mod_tools_log_index {

	function init()	{
		parent::init();
		$GLOBALS['LOCAL_LANG']['default']['type_10'] = 'DEV';
		$GLOBALS['LOCAL_LANG']['default']['action_10_1'] = 'Warn';
		$GLOBALS['LOCAL_LANG']['default']['action_10_2'] = 'Msg';
		$GLOBALS['LOCAL_LANG']['default']['action_10_3'] = 'ATTEMPT';
		$GLOBALS['LOCAL_LANG']['default']['action_10_4'] = 'ERROR';
		$GLOBALS['LOCAL_LANG']['default']['action_10_5'] = 'Error*';
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function menuConfig()	{
		parent::menuConfig();
		$this->MOD_MENU['action'][10] = 'Dev';
		ksort ($this->MOD_MENU['action']);
		$this->MOD_SETTINGS = t3lib_BEfunc::getModuleData($this->MOD_MENU, t3lib_div::_GP("SET"), $this->MCONF["name"]);
	}


}
if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sg_zlib/class.ux_SC_mod_tools_log_index.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/sg_zlib/class.ux_SC_mod_tools_log_index.php"]);
}

?>