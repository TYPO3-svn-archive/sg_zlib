<?php

define ('CRLF', "\r\n");
define ('DQT', '"');
define ('QT', "'");
$dbg = FALSE;


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

//var_dump ($TSFE);

// ***********************************************************************************************************************************

function GPvar($var)	{
	$value = isset($GLOBALS['HTTP_POST_VARS'][$var]) ? $GLOBALS['HTTP_POST_VARS'][$var] : $GLOBALS['HTTP_GET_VARS'][$var];
	if (isset($value) && is_string($value))	{$value=stripslashes($value);}
	return $value;
}

$baseDir = t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT'); //$GLOBALS['_SERVER']['DOCUMENT_ROOT'];
$paraSg = addslashes(GPvar('PARASG'));
$p = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'cache_md5params', 'md5hash='.$GLOBALS['TYPO3_DB']->fullQuoteStr($paraSg, 'cache_md5params'));
if (count($p)!=1) {
	die ('EXPORT-ERROR !');
}
$params = unserialize(urldecode($p[0]['params']));
$ex = urldecode(GPvar('ex'));

echo 'EXPORT.<br /><br />';
$myDbg = 0;

if ($myDbg) { t3lib_div::debug(Array('$params'=>$params, 'exportmode'=>$ex,'File:Line'=>__FILE__.':'.__LINE__)); }
if ($ex && ($tmp=$params['modes'][$ex]['fieldlist'])) {
	$params['SELECT'] = $tmp;
}
// t3lib_div::debug(Array('$params'=>$params, 'exportmode'=>$ex,'File:Line'=>__FILE__.':'.__LINE__));

$ereg1 = Array();
if ($e1 = ereg('.*(be_user|be_group).*',strtolower($params['FROM']),$ereg1)) {
	die ('ACCESS RESTRICTED! !');
}
$ereg2 = Array();
if ($e2 = ereg('.*(pass).*',strtolower($params['SELECT']),$ereg2)) {
	die ('ACCESS RESTRICTED !');
}
$pos = strpos($params['SELECT'],'*');
if (!($pos===FALSE)) {
	die ('ACCESS RESTRICTED!');
}

$r = Array();
$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($params['SELECT'],$params['FROM'],$params['WHERE'],$params['GROUP'],'','');
$myError = $GLOBALS['TYPO3_DB']->sql_error();
if ($myError) {
	$r['ERROR'] = $myError;
	$r['total']=0;
	$r['cnt']=0;
	t3lib_div::debug(Array('$params'=>$params, '$myError'=>$myError, 'File:Line'=>__FILE__.':'.__LINE__));
} else {
	if ($myDbg) { t3lib_div::debug(Array('$params'=>$params, 'count'=>$GLOBALS['TYPO3_DB']->sql_num_rows($res), 'File:Line'=>__FILE__.':'.__LINE__)); }
	$exp = $params['modes'][$ex];
	if (!is_array($exp)) {
		$exp = Array('fieldlist'=>'uid');
	}
	$fl = explode (',',$exp['fieldlist']);


	$exportFileName = 'fileadmin/_temp_/export_'.$params['FROM'].'.txt';
	if (strlen($params['FROM'])>25 || preg_match('/,;: /',$params['FROM'])) {
		$exportFileName = 'fileadmin/_temp_/export_'.t3lib_div::shortMD5($params['FROM']).'.txt';
	}


	if ($myDbg) { t3lib_div::debug(Array('$exp'=>$exp, '$fl'=>$fl,  'ExportFilename='=>$exportFileName,  'File:Line'=>__FILE__.':'.__LINE__)); }
	$out = fopen (t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT').'/'.$exportFileName,'wb');

	$cnt=0;
	if ($res) while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_row($res)) {
		if ($myDbg>1) { t3lib_div::debug(Array('$row'=>$row,  'File:Line'=>__FILE__.':'.__LINE__)); }
		$myOut = '';
		for ($i=0;$i<count($fl);$i++) {
			if ($i) {
				$myOut .= "\t".$row[$i];
			} else {
				$myOut .= $row[$i];
			}
		}
		if (strlen(trim($myOut))) { fwrite ($out,$myOut.CRLF); }
		$cnt++;
	}

	fclose ($out);

	echo ''.$cnt.' Records written: ';
	echo '<a href="'.t3lib_div::getIndpEnv('TYPO3_SITE_URL').$exportFileName.'">Download Exportfile</a><br />';
}



?>