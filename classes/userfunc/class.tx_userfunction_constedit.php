<?php
/***************************************************************
* Copyright notice
*
* (c) 2007 Foundation For Evangelism (info@evangelize.org)
* All rights reserved
*
* This file is part of the Web-Empowered Church (WEC)
* (http://webempoweredchurch.org) ministry of the Foundation for Evangelism
* (http://evangelize.org). The WEC is developing TYPO3-based
* (http://typo3.org) free software for churches around the world. Our desire
* is to use the Internet to help offer new life through Jesus Christ. Please
* see http://WebEmpoweredChurch.org/Jesus.
*
* You can redistribute this file and/or modify it under the terms of the
* GNU General Public License as published by the Free Software Foundation;
* either version 2 of the License, or (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This file is distributed in the hope that it will be useful for ministry,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the file!
***************************************************************/

/**
 * Demo class for userFuncs within the Typoscript Constant Editor.
 *
 * @author	Web-Empowered Church Team <developer@webempoweredchurch.org>
 */
class tx_userfunction_constedit {
	
	/**
	 * Builds a selection list of all frontend users.
	 * @param		array		Parameter array.  Contains fieldName and fieldValue.
	 * @return		string		HTML output for form widget.
	 */
	function feUserList($params) {
		/* Pull the current fieldname and value from constants */
		$fieldName = $params['fieldName'];
		$fieldValue = $params['fieldValue'];
		
		/* Construct the SQL query */
		$res = $GLOBALS['TYPO3_DB']->exec_selectQuery('*', 'fe_users', '1=1'.t3lib_beFunc::deleteClause('fe_users'));
		
		/* Build the HTML select tag */
		$content = array();
		$content[] = '<select name="'. $fieldName .'">';
		while($user = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$label = t3lib_beFunc::getRecordTitle('fe_users', $user);
			
			/* If the current user matches the field value, mark it as default */
			if ($user['uid'] == $fieldValue) {
				$selected = 'selected="selected"';
			} else {
				$selected = '';
			}
			
			/* Build the option tag */
			$content[] = '<option value="'. $user['uid'] .'" '. $selected .'>'.$label.'</option>';
		}
		$content[] = '</select>';
		
		return implode(chr(10), $content);
	}
	
	/**
	 * Builds an input form that also includes the link popup wizard.
	 * @param		array		Parameter array.  Contains fieldName and fieldValue.
	 * @return		string		HTML output for form widget.
	 */
	function page($params) {
		/* Pull the current fieldname and value from constants */
		$fieldName = $params['fieldName'];
		$fieldValue = $params['fieldValue'];
		
		$content = array();
		$input = '<input style="margin-right: 3px;" name="'. $fieldName .'" value="'. $fieldValue .'" />';
		
		/* @todo 	Don't hardcode the inclusion of the wizard this way.  Use more backend APIs. */
		$wizard = '<a href="#" onclick="this.blur(); vHWin=window.open(\'../../../../typo3/browse_links.php?mode=wizard&amp;P[field]='. $fieldName .'&amp;P[formName]=editForm&amp;P[itemName]='. $fieldName .'&amp;P[fieldChangeFunc][typo3form.fieldGet]=null&amp;P[fieldChangeFunc][TBE_EDITOR_fieldChanged]=null\',\'popUpID478be36b64\',\'height=300,width=500,status=0,menubar=0,scrollbars=1\'); vHWin.focus(); return false;"><img src="../../../../typo3/sysext/t3skin/icons/gfx/link_popup.gif" width="16" height="15" border="0" alt="Link" title="Link" /></a>';
		
		return $input.$wizard;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/classes/userfunc/class.tx_userfunction_constedit.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/classes/userfunc/class.tx_userfunction_constedit.php']);
}
?>
