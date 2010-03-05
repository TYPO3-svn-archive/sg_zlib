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
$temp_TSFEclassName = 'tslib_fe';
$TSFE = new $temp_TSFEclassName(
		$TYPO3_CONF_VARS,
		t3lib_div::_GP('id'),
		t3lib_div::_GP('type'),
		t3lib_div::_GP('no_cache'),
		t3lib_div::_GP('cHash'),
		t3lib_div::_GP('jumpurl'),
		t3lib_div::_GP('MP'),
		t3lib_div::_GP('RDCT')
	);
$TSFE->connectToDB();
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

class sg_browserbase {

	function init ($conf) {
		$this->brMode = t3lib_div::_GP('mode');
		$this->brSet = strlen(t3lib_div::_GP('set'))>0 ? t3lib_div::_GP('set') : 'default';
		$this->ext = t3lib_div::_GP('ext');
		$this->vn = t3lib_div::_GP('vn');
		$this->myDataName = $ext.'[data]['.$vn.']';
		$this->params = unserialize(urldecode(t3lib_div::_GP('params')));
		$this->owner = t3lib_div::_GP('own');
		$this->baseDir = t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT'); //$GLOBALS['_SERVER']['DOCUMENT_ROOT'];
		$this->tp = $GLOBALS['TSFE']->tmpl->getFileName($conf['template']);

		$this->jsIncludeCode = '<script type="text/javascript">
			/*<![CDATA[*/

		function closing()	{ close(); }

		function browserReload(a,path)	{	//
			document.location = path+"&mFU="+a;
		}

		function setIdTextElement(myExtension,myName,myText,myId,nac)	{	//
			if (window.opener && window.opener.addItem)	{
				// alert ("Adding ID "+myId);
				window.opener.addIdTextItem(myExtension,myName,'.intval($this->params['replace']).',myText,myId);
				window.opener.focus();
				if (nac==0)
					{ close(); }
				else
					{ parent.focus(); }
			} else {
				alert("Error - reference to main window is not set properly!");
			}
		}

		function setElement(myExtension,myName,myText,nac)	{	//
			if (window.opener && window.opener.addItem)	{
				window.opener.addItem(myExtension,myName,'.intval($this->params['replace']).',myText);
				window.opener.focus();
				if (nac==0)
					{ close(); }
				else
					{ parent.focus(); }
			} else {
				alert("Error - reference to main window is not set properly!");
			}
		}

		function setAutoInsert(insText,nac)	{	//
			if (window.opener)	{
				window.opener.insertText (insText+"|","ERROR - nothing selected");
				// window.opener.focus();
				if (nac==0)
					{ close(); }
				else
					{ parent.focus(); }
			} else {
				alert("Error - reference to main window is not set properly!");
			}
		}

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
		return ('sg_browserbase / function main');
	}
}

// ***********************************************************************************************************************************


$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_sgzlib.'][t3lib_div::_GP('mode').'.'];
$br = $GLOBALS['TSFE']->tmpl->getFileName($conf['browseFile']);
//t3lib_div::debug(Array('$br'=>$br, '$conf'=>$conf, 'File:Line'=>__FILE__.':'.__LINE__));
$dbg = Array();
if ($br) {
	$brClassName = $conf['browseClass'] ? $conf['browseClass'] : 'sg_browser';
	$dbg['$brClassFileName'] = $br;
	$dbg['$brClassName'] = $brClassName;
	require_once($br);
	$brClass = t3lib_div::makeInstance($brClassName);
} else {
	$brClass = t3lib_div::makeInstance('sg_browserbase');
}
$brSet = strlen(t3lib_div::_GP('set'))>0 ? t3lib_div::_GP('set') : 'default';
$tmp = $conf[$brSet.'.'] ? $conf[$brSet.'.'] : Array();
$conf = t3lib_div::array_merge_recursive_overrule(is_array($conf['default.'])?$conf['default.']:Array(),$tmp);

$brClass->init($conf);
$dbg['TSFE->id'] = $GLOBALS['TSFE']->id;
$dbg['$brMode'] = $brClass->brMode;
$dbg['$brSet'] = $brClass->brSet;
$dbg['$tp'] = $brClass->tp;
$dbg['$ext'] =$brClass->ext;
$dbg['$conf'] =$conf;
//t3lib_div::debug(Array('BROWSER'=>$dbg, 'File:Line'=>__FILE__.':'.__LINE__));
$content = $brClass->main('', $conf);

echo $content;

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/browser/browser.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/browser/browser.php']);
}
?>