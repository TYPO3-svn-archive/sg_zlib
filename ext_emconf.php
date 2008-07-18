<?php

########################################################################
# Extension Manager/Repository config file for ext: "sg_zlib"
#
# Auto generated 01-04-2008 15:09
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Library for Frontend plugins',
	'description' => 'Library for Frontend plugins with FeUser-Editing. NOTE: Docs can be found separately in extension sg_zlib_doc; NOTE: This refactored/extended version based on sg_zfelib. Some TS will be incompatible to sg_zfelib; see readme.txt for that. When stable, then sg_zfelib will be obsolete.',
	'category' => 'fe',
	'shy' => 0,
	'dependencies' => 'cms,sg_div',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => 0,
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author' => 'Stefan Geith',
	'author_email' => 'typo3devYYYY@geithware.de',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.2.100',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'php' => '5.0.0-5.9.99',
			'typo3' => '4.0.0-4.9.9',
			'sg_div' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:105:{s:9:"ChangeLog";s:4:"cb05";s:25:"class.tx_sglib_config.php";s:4:"a08f";s:24:"class.tx_sglib_const.php";s:4:"dbaf";s:23:"class.tx_sglib_data.php";s:4:"35d2";s:24:"class.tx_sglib_debug.php";s:4:"0803";s:29:"class.tx_sglib_exceptions.php";s:4:"9f83";s:26:"class.tx_sglib_factory.php";s:4:"cc17";s:24:"class.tx_sglib_items.php";s:4:"d967";s:23:"class.tx_sglib_lang.php";s:4:"281c";s:24:"class.tx_sglib_links.php";s:4:"c4db";s:26:"class.tx_sglib_markers.php";s:4:"f920";s:28:"class.tx_sglib_modelbase.php";s:4:"54b7";s:25:"class.tx_sglib_params.php";s:4:"ff25";s:25:"class.tx_sglib_permit.php";s:4:"ce25";s:27:"class.tx_sglib_template.php";s:4:"904d";s:27:"class.tx_sglib_viewbase.php";s:4:"b607";s:30:"class.tx_sglib_viewdetails.php";s:4:"c203";s:27:"class.tx_sglib_viewlist.php";s:4:"64c7";s:33:"class.tx_sglib_viewsearchform.php";s:4:"91eb";s:19:"class.tx_sgzlib.php";s:4:"f8bb";s:19:"class.txsg_base.php";s:4:"ab98";s:26:"class.txsg_base_import.php";s:4:"1134";s:25:"class.txsg_cachedbase.php";s:4:"6f3b";s:29:"class.txsg_cachedstandard.php";s:4:"d485";s:25:"class.txsg_dispatcher.php";s:4:"4f3b";s:35:"class.ux_SC_mod_tools_log_index.php";s:4:"6192";s:19:"class.ux_db_new.php";s:4:"be56";s:21:"ext_conf_template.txt";s:4:"f248";s:12:"ext_icon.gif";s:4:"6ebc";s:17:"ext_localconf.php";s:4:"70d9";s:14:"ext_tables.php";s:4:"ca05";s:28:"ext_typoscript_constants.txt";s:4:"434b";s:24:"ext_typoscript_setup.txt";s:4:"8526";s:13:"locallang.xml";s:4:"3abc";s:20:"locallang_import.xml";s:4:"1534";s:10:"readme.txt";s:4:"6195";s:27:"paramspop/eid_paramspop.php";s:4:"1f3e";s:12:"img/back.gif";s:4:"6ae8";s:14:"img/bt_060.gif";s:4:"6d29";s:14:"img/bt_080.gif";s:4:"8219";s:14:"img/bt_100.gif";s:4:"781f";s:14:"img/bt_160.gif";s:4:"f31d";s:14:"img/bt_add.gif";s:4:"58a0";s:17:"img/bt_cancel.gif";s:4:"fea5";s:16:"img/bt_clear.gif";s:4:"eae7";s:15:"img/bt_next.gif";s:4:"b933";s:18:"img/bt_nextdis.gif";s:4:"2568";s:13:"img/bt_ok.gif";s:4:"044c";s:15:"img/bt_prev.gif";s:4:"ecca";s:18:"img/bt_prevdis.gif";s:4:"7173";s:16:"img/bt_reset.gif";s:4:"f437";s:17:"img/bt_search.gif";s:4:"0d3e";s:13:"img/first.gif";s:4:"4003";s:16:"img/firstdis.gif";s:4:"baad";s:12:"img/last.gif";s:4:"dad2";s:15:"img/lastdis.gif";s:4:"fdcb";s:12:"img/next.gif";s:4:"92dc";s:15:"img/nextdis.gif";s:4:"19d8";s:12:"img/prev.gif";s:4:"c365";s:15:"img/prevdis.gif";s:4:"44b1";s:20:"img/icons/add_16.gif";s:4:"0130";s:29:"img/icons/add_disabled_16.gif";s:4:"fa81";s:27:"img/icons/add_locked_16.gif";s:4:"46ac";s:21:"img/icons/back_16.gif";s:4:"ec9d";s:23:"img/icons/button_16.gif";s:4:"e484";s:23:"img/icons/cancel_16.gif";s:4:"1443";s:22:"img/icons/close_16.gif";s:4:"1443";s:23:"img/icons/delete_16.gif";s:4:"5649";s:25:"img/icons/dodelete_16.gif";s:4:"6de8";s:34:"img/icons/dodelete_disabled_16.gif";s:4:"84f1";s:34:"img/icons/dodelete_unhidden_16.gif";s:4:"84f1";s:23:"img/icons/dohide_16.gif";s:4:"7e4a";s:32:"img/icons/dohide_disabled_16.gif";s:4:"3cf4";s:25:"img/icons/dounhide_16.gif";s:4:"d0c6";s:34:"img/icons/dounhide_disabled_16.gif";s:4:"8424";s:26:"img/icons/editAsNew_16.gif";s:4:"61e2";s:35:"img/icons/editAsNew_disabled_16.gif";s:4:"ffc4";s:21:"img/icons/edit_16.gif";s:4:"ff11";s:30:"img/icons/edit_disabled_16.gif";s:4:"3885";s:28:"img/icons/edit_locked_16.gif";s:4:"7310";s:22:"img/icons/print_16.gif";s:4:"4b4a";s:23:"img/icons/reload_16.gif";s:4:"2eb0";s:21:"img/icons/save_16.gif";s:4:"933e";s:20:"img/icons/set_16.gif";s:4:"b11a";s:19:"img/icons/up_16.gif";s:4:"a932";s:23:"img/icons/update_16.gif";s:4:"24b9";s:21:"img/icons/view_16.gif";s:4:"15c1";s:30:"img/icons/view_disabled_16.gif";s:4:"f614";s:24:"img/icons/warning_16.gif";s:4:"5a05";s:20:"browser/browsedb.php";s:4:"4d4a";s:21:"browser/browsedb.tmpl";s:4:"64d4";s:22:"browser/browsefile.php";s:4:"d1e4";s:23:"browser/browsefile.tmpl";s:4:"b754";s:23:"browser/eid_browser.php";s:4:"9143";s:17:"ex/eid_export.php";s:4:"ab8f";s:29:"downloader/eid_downloader.php";s:4:"e784";s:30:"static/icon_image_ts/setup.txt";s:4:"58e2";s:32:"static/button_image_ts/setup.txt";s:4:"ea11";s:16:"js/popup_func.js";s:4:"fed4";s:17:"js/search_func.js";s:4:"5d81";s:15:"js/user_func.js";s:4:"3b87";s:25:"example/ext_localconf.php";s:4:"bf7f";s:36:"example/ext_typoscript_constants.txt";s:4:"4c14";s:32:"example/ext_typoscript_setup.txt";s:4:"e0b4";s:14:"doc/manual.sxw";s:4:"6263";}',
	'suggests' => array(
	),
	'conflicts' => '',
);

?>