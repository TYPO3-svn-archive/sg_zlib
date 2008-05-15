<?php
/***************************************************************
*  Copyright notice
*
*  (c) 1999-2004 Kasper Skaarhoj (kasperYYYY@typo3.com)
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
 * This is the modified MAIN DOCUMENT of the TypoScript driven standard front-end
 * It is changed to enable browse-popups for FE-User Editing
 *
 * @author		Kasper Skaarhoj <kasperYYYY@typo3.com>
 * @modified	Stefan Geith <typo3dev2006@geithware.de>
 */

// // // OK here we go after index.php init ...
// // //

define ('CRLF', "\r\n");
define ('DQT', '"');
define ('QT', "'");

// *********************
// Libraries included
// *********************
$TT->push('Include Frontend libraries','');
	require_once(PATH_tslib.'class.tslib_fe.php');
	require_once(PATH_t3lib.'class.t3lib_page.php');
	require_once(PATH_t3lib.'class.t3lib_userauth.php');
	require_once(PATH_tslib.'class.tslib_feuserauth.php');
	require_once(PATH_t3lib.'class.t3lib_tstemplate.php');
	require_once(PATH_t3lib.'class.t3lib_cs.php');
$TT->pull();

// ***********************************
// Create $TSFE object (TSFE = TypoScript Front End)
// Connecting to database
// ***********************************
$temp_TSFEclassName=t3lib_div::makeInstanceClassName('tslib_fe');
$TSFE = new $temp_TSFEclassName(
		$TYPO3_CONF_VARS,
		0,
		t3lib_div::_GP('type'),
		t3lib_div::_GP('no_cache'),
		t3lib_div::_GP('cHash'),
		t3lib_div::_GP('jumpurl'),
		t3lib_div::_GP('MP'),
		t3lib_div::_GP('RDCT')
	);
$TSFE->connectToMySQL();
if ($TSFE->RDCT)	{$TSFE->sendRedirect();}


// *******************
// output compression
// *******************
if ($TYPO3_CONF_VARS['FE']['compressionLevel'])	{
	ob_start();
	require_once(PATH_t3lib.'class.gzip_encode.php');
}

// *********
// FE_USER
// *********
$TT->push('Front End user initialized','');
	$TSFE->initFEuser();
$TT->pull();






// *****************************************
// Proces the ID, type and other parameters
// After this point we have an array, $page in TSFE, which is the page-record of the current page, $id
// *****************************************
$TT->push('Process ID','');
	// not needed and doesnot work with realurl // $TSFE->checkAlternativeIdMethods();
	$TSFE->clear_preview();
	$TSFE->determineId();

		// Now, if there is a backend user logged in and he has NO access to this page, then re-evaluate the id shown!
	if ($TSFE->beUserLogin && !$BE_USER->extPageReadAccess($TSFE->page))	{

			// Remove user
		unset($BE_USER);
		$TSFE->beUserLogin = 0;

			// Re-evaluate the page-id.
		$TSFE->checkAlternativeIdMethods();
		$TSFE->clear_preview();
		$TSFE->determineId();
	}
	$TSFE->makeCacheHash();
$TT->pull();


// *******************************************
// Get compressed $TCA-Array();
// After this, we should now have a valid $TCA, though minimized
// *******************************************
$TSFE->getCompressedTCarray();


// ********************************
// Starts the template
// *******************************
$TT->push('Start Template','');
	$TSFE->initTemplate();
	$TSFE->tmpl->getFileName_backPath = PATH_site;
$TT->pull();


// ******************************************************
// Get config if not already gotten
// After this, we should have a valid config-array ready
// ******************************************************
$TSFE->getConfigArray();

//var_dump ($TSFE);

// ***********************************************************************************************************************************

class sg_paramspop {

	function init ($conf) {

		$this->conf = is_array($conf[t3lib_div::_GP('settings').'.']) ? $conf[t3lib_div::_GP('settings').'.'] : array();
		$this->params = t3lib_div::array_merge_recursive_overrule($this->conf, $_GET);
		$this->params['output'] = $this->conf['output'];
		if ($this->params['dbg']) {
			t3lib_div::debug(Array('$this->conf'=>$this->conf, '$this->params'=>$this->params, '$_GET'=>$_GET, 'File:Line'=>__FILE__.':'.__LINE__));
		}

		$this->jsIncludeCode =
		'<script type="text/javascript">
			/*<![CDATA[*/

		function closing()	{ close(); }

			/*]]>*/
		</script>';

	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$content: ...
	 * @param	[type]		$markContentArray: ...
	 * @param	[type]		$wrap: ...
	 * @param	[type]		$uppercase: ...
	 * @return	[type]		...
	 */
	function substituteMarkerArray($content,$markContentArray,$wrap='',$uppercase=0)	{
	if (is_array($markContentArray))	{
		reset($markContentArray);
		$wrapArr=t3lib_div::trimExplode('|',$wrap);
		while(list($marker,$markContent)=each($markContentArray))	{
			if($uppercase)	$marker=strtoupper($marker);
			if(strcmp($wrap,''))		$marker=$wrapArr[0].$marker.$wrapArr[1];
			$content=str_replace($marker,$markContent,$content);
		}
	}
	return $content;
}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$content: ...
	 * @param	[type]		$conf: ...
	 * @return	[type]		...
	 */
	function main ($content, $conf) {
		GLOBAL $TSFE;
		$content = '';


		$tmpIds = (is_array($this->params['IDs'])) ? $this->params['IDs'] :
			(strlen(trim($this->params['IDList'])) ? explode(',',trim($this->params['IDList'])) : Array($this->params['id']) );

		$usermode = ($GLOBALS['TSFE']->fe_user->user['uid'] && $this->params['usermode']) ? 'user' : 'ses' ;
		$key = $this->params['key'];

		$tmp = $GLOBALS['TSFE']->fe_user->getKey($usermode,$key);
		if (!is_array($tmp)) {
			$tmp = Array();
		}
		if ($this->params['dbg']) {
			t3lib_div::debug(Array('user-id'=>$GLOBALS['TSFE']->fe_user->user['uid'], '$usermode'=>$usermode,
				'$key'=>$key, '$tmpIds'=>$tmpIds, 'list before'=>$tmp, 'File:Line'=>__FILE__.':'.__LINE__));
		}

		$cnt = Array('add'=>0, 'replace'=>0, 'all'=>0);
		for ($i=0;$i<count($tmpIds);$i++) if($tmpIds[$i]) {
			if (!is_Array($tmp[$tmpIds[$i].'.'])) {
				$cnt['add']++;
				$cnt['all']++;
				$tmp[$tmpIds[$i].'.'] = Array('id' => $tmpIds[$i], 'mode'=>intval($this->params['mode']));
			} else {
				$cnt['replace']++;
				$cnt['all']++;
				$tmp[$tmpIds[$i].'.']['id'] = $tmpIds[$i];
				$tmp[$tmpIds[$i].'.']['mode'] = intval($this->params['mode']);
			}
		}
		$TSFE->fe_user->setKey($usermode,$key,$tmp);
		$cnt['total'] = count($tmp);
		if ($this->params['dbg']) {
			t3lib_div::debug(Array('list after'=>$tmp, '$cnt'=>$cnt, 'File:Line'=>__FILE__.':'.__LINE__));
		}

		$content .= ( ($this->params['docType']) ?
			$this->params['docType'] : '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">') .CRLF.'<html>'.CRLF;

		// Header:
		$content .= '<head>'.CRLF;
		$content .= ( ($this->params['title']) ? '  <title>'.$this->params['title'].'</title>'.CRLF : '');
		if (is_array($this->params['includeCSS.']))	{
			foreach ($this->params['includeCSS.'] as $key=>$iCSSfile)	{
				if (!is_array($iCSSfile))	{
					$ss=$TSFE->tmpl->getFileName($iCSSfile);
					if ($ss)	{
						$content.='  <link rel="'.($this->params['includeCSS.'][$key.'.']['alternate'] ?
							'alternate stylesheet' : 'stylesheet').'" type="text/css" href="'.htmlspecialchars($ss).'"'.
							($this->params['includeCSS.'][$key.'.']['title'] ? ' title="'.htmlspecialchars($this->params['includeCSS.'][$key.'.']['title']).'"' : '').
							($this->params['includeCSS.'][$key.'.']['media'] ? ' media="'.htmlspecialchars($this->params['includeCSS.'][$key.'.']['media']).'"' : '').
							' />'.CRLF;
					}
				}
			}
		}
		if ($this->params['headerData.'])	{
			$content.= '  '.$TSFE->cObj->cObjGet($this->params['headerData.'],'headerData.').CRLF;
		}
		$content .= '</head>'.CRLF;

		$wrap = explode('|',$this->params['bodyWrap']);
		$content .= '<body>'.CRLF.$wrap[0].$this->substituteMarkerArray( $this->params['output'],$cnt,'###|###').$wrap[1].CRLF.'</body>'.CRLF;
		$content .= '</html>'.CRLF;

		$TSFE->fe_user->storeSessionData();

		return ($content);
	}
}

// ***********************************************************************************************************************************

$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_sgzlib.']['paramspop.'];
$me = t3lib_div::makeInstance('sg_paramspop');
$me->init($conf);

$content = $me->main('', $conf);

echo $content;

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/paramspop/eid_paramspop.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/paramspop/eid_paramspop.php']);
}
?>