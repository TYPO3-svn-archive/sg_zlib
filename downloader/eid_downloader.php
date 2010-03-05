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
		0,
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

class sg_downloader {

	function init ($conf) {

		$this->conf = is_array($conf[t3lib_div::_GP('settings').'.']) ? $conf[t3lib_div::_GP('settings').'.'] : array();
		$this->params = t3lib_div::array_merge_recursive_overrule($this->conf, $_GET);
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
		$content = '';
		if (intval($this->params['feUser'])==0 || intval($GLOBALS['TSFE']->fe_user->user['uid'])==intval($this->params['feUser'])) {
			if (!$this->params['mime']) {
				$this->params['mime'] = 'text';
			}
			if (!$this->params['submime']) {
				$this->params['submime'] = 'plain';
			}
			if (!$this->params['ftype']) {
				$this->params['ftype'] = 'txt';
			}
			if (!$this->params['file']) {
				$this->params['file'] = 'download';
			}

			if ($this->params['toZip'] && $this->params['myZip']) {
				$content .= $this->getZippedFiles();
			} else {
				$content .= $this->getSingleFile();
			}


		} else {
			$content .= 'ERROR: Access Denied! '.intval($this->params['feUser']).'-'.intval($GLOBALS['TSFE']->fe_user->user['uid']);
		}

		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getSingleFile() {
		$content = '';

		$headerCT = 'Content-type: '.$this->params['mime'].'/'.$this->params['submime'].'';
		$headerCD = 'Content-Disposition: attachment; filename='.$this->params['file'];

		$fullFilePath = $this->params['path'].$this->params['file'];
		$absFilePath = t3lib_div::getFileAbsFileName($fullFilePath);

		$ok = file_exists($fullFilePath);
		if ($this->params['dbg']) {
			t3lib_div::debug(Array('$fullFilePath'=>$fullFilePath, 'PATH_typo3'=>PATH_typo3, 'PATH_site'=>PATH_site, '$absFilePath'=>$absFilePath, 'file_exists='=>$ok, 'File:Line'=>__FILE__.':'.__LINE__));
		}

		if ($ok) {

			if ($this->params['dbg']) {
				$content .= 'HEADER("'.$headerCT.'");<br />';
				$content .= 'HEADER("'.$headerCD.'");<br />';
				$content .= 'This is a Downloader (DebugMode !) !';
			} else {
				header($headerCT);
				header($headerCD);

				readfile($absFilePath);
				exit();
			}

		} else {
			$content .= 'ERROR: File "'.$this->params['file'].'" not found!';
		}

		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getZippedFiles() {
		$content = '';

		if (file_exists($this->params['myZip'])) {
			unlink ($this->params['myZip']);
		}

		$command = 'cat '.$this->params['toZip'].' | '.($this->params['zipPath'] ? $this->params['zipPath'] : 'zip').' -DjqX -n : '.$this->params['myZip'].' -@';
		$returnval = shell_exec ($command);

		// echo 'download: <a href="'.$zipDLname.'">ZIP</a><hr/>';


		if (file_exists($this->params['myZip'])) {
			if ($this->params['dbg']) {
				t3lib_div::debug(Array('toZip'=>$this->params['toZip'], 'myZip'=>$this->params['myZip'],'File:Line'=>__FILE__.':'.__LINE__));
				$content .= 'Created ZipFile "'.$this->params['myZip'].'"';
			} else {
				// Wir werden eine ZIP Datei ausgeben
				header("Content-type: application/zip");
				// Es wird downloads.zip benannt
				header("Content-Disposition: attachment; filename=downloads.zip");
				readfile ($this->params['myZip']);
				exit ();
			}
		} else {
			echo '<html><body>ERROR<br />'."\r\n";
			echo 'File does not exist: "'.$this->params['myZip'].'" ---<br />'."\r\n";
			echo 'Command was :"'.$command.'" ---<br />'."\r\n";
			echo 'Return was :"'.$returnval.'" ---<br />'."\r\n";
			echo '</body></html>';
		}

		return ($content);
	}



}

// ***********************************************************************************************************************************

$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_sgzlib.']['downloader.'];
$me = t3lib_div::makeInstance('sg_downloader');
$me->init($conf);

$content = $me->main('', $conf);

echo $content;

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/downloader/eid_downloader.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/downloader/eid_downloader.php']);
}
?>