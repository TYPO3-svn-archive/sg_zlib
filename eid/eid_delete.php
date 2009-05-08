<?php

define ('CRLF', "\r\n");
define ('DQT', '"');
define ('QT', "'");
$dbg = FALSE;
$myTxPluginName = 'tx_sgzlib';


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
	require_once(PATH_tslib.'class.tslib_content.php');
$TT->pull();

// ***********************************
// Create $TSFE object (TSFE = TypoScript Front End)
// Connecting to database
// ***********************************
$temp_TSFEclassName=t3lib_div::makeInstanceClassName('tslib_fe');
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
$TSFE->connectToMySQL();
if ($TSFE->RDCT)	{$TSFE->sendRedirect();}

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

function GPvar($var)	{
	$value = isset($GLOBALS['HTTP_POST_VARS'][$var]) ? $GLOBALS['HTTP_POST_VARS'][$var] : $GLOBALS['HTTP_GET_VARS'][$var];
	if (isset($value) && is_string($value))	{$value=stripslashes($value);}
	return $value;
}

function reloadParent() {
//				window.opener.location.reload();
		echo '<script type="text/javascript">
			/*<![CDATA[*/
			window.opener.location.reload();
			/*]]>*/
		</script>';
}

$baseDir = t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT'); //$GLOBALS['_SERVER']['DOCUMENT_ROOT'];
$paraSg = addslashes(GPvar('PARASG'));
$p = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'cache_md5params', 'md5hash='.$GLOBALS['TYPO3_DB']->fullQuoteStr($paraSg, 'cache_md5params'));
if (count($p)!=1) {
	die ('DELETE-ERROR !');
}
$params = unserialize(urldecode($p[0]['params']));

$ereg1 = Array();
if ($e1 = ereg('.*(be_user|be_group).*',strtolower($params['FROM']),$ereg1)) {
	die ('ACCESS RESTRICTED! ! ! !');
}
$joinPart = strpos (strtolower($params['FROM']),'join');
$test = $joinPart ? substr($params['FROM'],0,$joinPart): $test;
if ($e1 = ereg('.*(be_user|be_group|fe_user|fe_group).*',strtolower($test),$ereg1)) {
	die ('ACCESS RESTRICTED! !');
}

$cObj = t3lib_div::makeInstance('tslib_cObj');
$deleteField = ($params['mainTable'] ? $params['mainTable'].'.' : '').'deleted';
$marks = Array('###TABLENAME###'=>$params['mainTable']);

if (strcmp($params['deleteMode'],'delete')==0) {
	$confGlobal = $TSFE->tmpl->setup['plugin.'][$myTxPluginName.'.']['deleteList.'];
	$confPlugin = $TSFE->tmpl->setup['plugin.'][$params['prefixId'].'.']['deleteList.'];
	$conf = t3lib_div::array_merge_recursive_overrule((array) $confGlobal, (array) $confPlugin);
	$template = implode ('',(array) (file ($TSFE->tmpl->getFileName($conf['template']))));
	$template = $cObj->getSubpart($template, 'MAIN');
	$myDbg = $conf['dodebug'];
	if ($myDbg) { t3lib_div::debug(Array('$params'=>$params, '$conf'=>$conf, '$template'=>$template, 'File:Line'=>__FILE__.':'.__LINE__)); }

	//preg_match ('%\{literal\}.+?\{/literal\}%six',$template,$literals);
	//t3lib_div::debug(Array('$literals'=>$literals, 'File:Line'=>__FILE__.':'.__LINE__));

	$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',$params['FROM'],$params['WHERE'],$params['GROUP']);
	$cnt = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
	if ($myDbg) { t3lib_div::debug(Array('$res'=>$res, '$cnt'=>$cnt, 'File:Line'=>__FILE__.':'.__LINE__)); }
	if ($cnt==$params['foundRecords']) {
		$marks['###TODELETE###'] = $cObj->stdWrap($cnt,$conf['field.']['todelete.']);
		$marks['###PURGEDBEFORE###'] = '';
		if ($conf['purgeBefore']) {
			$query = $GLOBALS['TYPO3_DB']->DELETEquery($params['FROM'],$deleteField.'>0 AND '.$deleteField.'>='.intval($conf['purgeBefore']));
			if ($conf['simulate']) {
				 t3lib_div::debug(Array('SIMULATIONMODE'=>1, '$query'=>$query, 'File:Line'=>__FILE__.':'.__LINE__));
				$marks['###PURGEDBEFORE###'] = '-unknown-';
			} else {
				$result = $GLOBALS['TYPO3_DB']->sql_query($query);
				$affected = $GLOBALS['TYPO3_DB']->sql_affected_rows();
				$marks['###PURGEDBEFORE###'] = $affected;
				if ($myDbg) { t3lib_div::debug(Array('$query'=>$query, '$result'=>$result, '$affected'=>$affected, 'File:Line'=>__FILE__.':'.__LINE__)); }
			}
			$marks['###PURGEDBEFORE###'] = $cObj->stdWrap($marks['###PURGEDBEFORE###'],$conf['field.']['purgedbefore.']);
		} 
		
		$query = $GLOBALS['TYPO3_DB']->UPDATEquery($params['FROM'],$deleteField.'>10 AND '.$deleteField.'<99',Array($deleteField=>$deleteField.'+1'),Array($deleteField));
			if ($conf['simulate']) {
				 t3lib_div::debug(Array('SIMULATIONMODE'=>1, '$query'=>$query, 'File:Line'=>__FILE__.':'.__LINE__));
				$marks['###OLDDELETEDRECORDS###'] = '-unknown-';
			} else {
				$result = $GLOBALS['TYPO3_DB']->sql_query($query);
				$affected = $GLOBALS['TYPO3_DB']->sql_affected_rows();
				$marks['###OLDDELETEDRECORDS###'] = $affected;
				if ($myDbg) { t3lib_div::debug(Array('$query'=>$query, '$result'=>$result, '$affected'=>$affected, 'File:Line'=>__FILE__.':'.__LINE__)); }
			}
		$marks['###OLDDELETEDRECORDS###'] = $cObj->stdWrap($marks['###OLDDELETEDRECORDS###'],$conf['field.']['olddeletedrecords.']);

		$query = $GLOBALS['TYPO3_DB']->UPDATEquery($params['FROM'],$params['WHERE'],Array($deleteField=>11));
			if ($conf['simulate']) {
				 t3lib_div::debug(Array('SIMULATIONMODE'=>1, '$query'=>$query, 'File:Line'=>__FILE__.':'.__LINE__));
				$marks['###DELTEDRECORDS###'] = '-unknown-';
			} else {
				$result = $GLOBALS['TYPO3_DB']->sql_query($query);
				$affected = $GLOBALS['TYPO3_DB']->sql_affected_rows();
				$marks['###DELTEDRECORDS###'] = $affected;
				if ($myDbg) { t3lib_div::debug(Array('$query'=>$query, '$result'=>$result, '$affected'=>$affected, 'File:Line'=>__FILE__.':'.__LINE__)); }
			}
		$marks['###DELTEDRECORDS###'] = $cObj->stdWrap($marks['###DELTEDRECORDS###'],$conf['field.']['deltedrecords.']);

		reloadParent();
		echo $cObj->substituteMarkerArray($template,$marks);
	} else {
		echo 'ERROR: count of records not matching. Please close windows and retry to delete !.<br />';
	}
} else if (strcmp($params['deleteMode'],'undelete')==0) {
	$confGlobal = $TSFE->tmpl->setup['plugin.'][$myTxPluginName.'.']['undoDeleteList.'];
	$confPlugin = $TSFE->tmpl->setup['plugin.'][$params['prefixId'].'.']['undoDeleteList.'];
	$conf = t3lib_div::array_merge_recursive_overrule((array) $confGlobal, (array) $confPlugin);
	$template = implode ('',(array) (file ($TSFE->tmpl->getFileName($conf['template']))));
	$template = $cObj->getSubpart($template, 'MAIN');
	$myDbg = $conf['dodebug'];
	if ($myDbg) { t3lib_div::debug(Array('$params'=>$params, '$conf'=>$conf, '$template'=>$template, 'File:Line'=>__FILE__.':'.__LINE__)); }

	$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',$params['FROM'],$deleteField.'=11');
	$cnt = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
	$marks['###TOUNDELETE###'] = $cObj->stdWrap($cnt,$conf['field.']['toundelete.']);
	if ($cnt>0) {
		$query = $GLOBALS['TYPO3_DB']->UPDATEquery($params['FROM'],$deleteField.'=11',Array($deleteField=>0));
		if ($conf['simulate']) {
			 t3lib_div::debug(Array('SIMULATIONMODE'=>1, '$query'=>$query, 'File:Line'=>__FILE__.':'.__LINE__));
			$marks['###RESTOREDRECORDS###'] = '-unknown-';
		} else {
			$result = $GLOBALS['TYPO3_DB']->sql_query($query);
			$affected = $GLOBALS['TYPO3_DB']->sql_affected_rows();
			$marks['###RESTOREDRECORDS###'] = $affected;
			if ($myDbg) { t3lib_div::debug(Array('$query'=>$query, '$result'=>$result, '$affected'=>$affected, 'File:Line'=>__FILE__.':'.__LINE__)); }
		}
		$marks['###RESTOREDRECORDS###'] = $cObj->stdWrap($marks['###RESTOREDRECORDS###'],$conf['field.']['restoredrecords.']);

		$query = $GLOBALS['TYPO3_DB']->UPDATEquery($params['FROM'],$deleteField.'>11 && '.$deleteField.'<99',Array($deleteField=>$deleteField.'-1'),Array($deleteField));
		if ($conf['simulate']) {
			 t3lib_div::debug(Array('SIMULATIONMODE'=>1, '$query'=>$query, 'File:Line'=>__FILE__.':'.__LINE__));
			$marks['###STILLDELETEDRECORDS###'] = '-unknown-';
		} else {
			$result = $GLOBALS['TYPO3_DB']->sql_query($query);
			$affected = $GLOBALS['TYPO3_DB']->sql_affected_rows();
			$marks['###STILLDELETEDRECORDS###'] = $affected;
			if ($myDbg) { t3lib_div::debug(Array('$query'=>$query, '$result'=>$result, '$affected'=>$affected, 'File:Line'=>__FILE__.':'.__LINE__)); }
		}
		$marks['###STILLDELETEDRECORDS###'] = $cObj->stdWrap($marks['###STILLDELETEDRECORDS###'],$conf['field.']['stilldeletedrecords.']);

		reloadParent();
		echo $cObj->substituteMarkerArray($template,$marks);
	}
} else {
	echo 'ERROR: Wrong parameters<br />';
}





?>