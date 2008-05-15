<?php

if (!defined ("TYPO3_MODE"))     die ("Access denied.");

$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sg_zlib']);

$TYPO3_CONF_VARS["BE"]["XCLASS"]["ext/belog/mod/index.php"] = t3lib_extMgm::extPath($_EXTKEY)."class.ux_SC_mod_tools_log_index.php";
if ($confArr['addGeneralPlugin']) {
	$TYPO3_CONF_VARS["BE"]["XCLASS"]["typo3/db_new.php"] = t3lib_extMgm::extPath($_EXTKEY)."class.ux_db_new.php";
}
$TYPO3_CONF_VARS['FE']['eID_include'][$_EXTKEY.'_browse'] = 'EXT:sg_zlib/browser/eid_browser.php';
$TYPO3_CONF_VARS['FE']['eID_include'][$_EXTKEY.'_export'] = 'EXT:sg_zlib/browser/eid_export.php';
$TYPO3_CONF_VARS['FE']['eID_include'][$_EXTKEY.'_paramspop'] = 'EXT:sg_zlib/paramspop/eid_paramspop.php';
$TYPO3_CONF_VARS['FE']['eID_include'][$_EXTKEY.'_downloader'] = 'EXT:sg_zlib/downloader/eid_downloader.php';

if(TYPO3_MODE == 'FE') {
    define ('SGZLIB_FATALERROR', 100);
    define ('SGZLIB_READALL', -1);
    define ('SGZLIB_READUESED', -2);

	require_once(PATH_tslib.'class.tslib_pibase.php');
	require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_factory.php');

	require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_config.php');
	require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_const.php');
	require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_data.php');
	require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_debug.php');
	require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_params.php');
	require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_permit.php');
	require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_lang.php');
	require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_template.php');
	require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_items.php');
	require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_markers.php');
	require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sgzlib.php');
}

?>