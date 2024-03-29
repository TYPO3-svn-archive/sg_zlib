<?php

########################################################################
# Extension Manager/Repository config file for ext "sg_zlib".
#
# Auto generated 05-03-2010 11:54
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
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
	'version' => '0.3.0',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'php' => '5.0.0-5.9.99',
			'typo3' => '4.0.0-4.9.9',
			'sg_div' => '0.4.0-',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:147:{s:9:"ChangeLog";s:4:"3f2f";s:21:"ext_conf_template.txt";s:4:"f248";s:12:"ext_icon.gif";s:4:"6ebc";s:17:"ext_localconf.php";s:4:"bd9f";s:15:"ext_php_api.dat";s:4:"7712";s:14:"ext_tables.php";s:4:"ca05";s:28:"ext_typoscript_constants.txt";s:4:"d799";s:24:"ext_typoscript_setup.txt";s:4:"2641";s:13:"locallang.xml";s:4:"1c1a";s:20:"locallang_import.xml";s:4:"6ce2";s:10:"readme.txt";s:4:"6195";s:20:"browser/browsedb.php";s:4:"1852";s:21:"browser/browsedb.tmpl";s:4:"22bf";s:22:"browser/browsefile.php";s:4:"9c0f";s:23:"browser/browsefile.tmpl";s:4:"22d3";s:23:"browser/eid_browser.php";s:4:"5c2b";s:32:"classes/base/class.txsg_base.php";s:4:"46b6";s:39:"classes/base/class.txsg_base_import.php";s:4:"fce4";s:38:"classes/base/class.txsg_dispatcher.php";s:4:"1b71";s:44:"classes/cachedbase/class.txsg_cachedbase.php";s:4:"c754";s:48:"classes/cachedbase/class.txsg_cachedstandard.php";s:4:"4360";s:41:"classes/cachedbase/class.txsg_catmenu.php";s:4:"fb3a";s:48:"classes/essentials/class.tx_sglib_exceptions.php";s:4:"9f83";s:45:"classes/essentials/class.tx_sglib_factory.php";s:4:"fe7c";s:37:"classes/lib/class.tx_sglib_config.php";s:4:"3297";s:36:"classes/lib/class.tx_sglib_const.php";s:4:"af5b";s:35:"classes/lib/class.tx_sglib_data.php";s:4:"35d2";s:36:"classes/lib/class.tx_sglib_debug.php";s:4:"614c";s:41:"classes/lib/class.tx_sglib_fileupload.php";s:4:"e4cf";s:36:"classes/lib/class.tx_sglib_items.php";s:4:"fa57";s:35:"classes/lib/class.tx_sglib_lang.php";s:4:"b2c9";s:36:"classes/lib/class.tx_sglib_links.php";s:4:"fd67";s:38:"classes/lib/class.tx_sglib_markers.php";s:4:"cec4";s:42:"classes/lib/class.tx_sglib_pagebrowser.php";s:4:"d1a5";s:37:"classes/lib/class.tx_sglib_params.php";s:4:"148e";s:37:"classes/lib/class.tx_sglib_permit.php";s:4:"7849";s:37:"classes/lib/class.tx_sglib_search.php";s:4:"7745";s:39:"classes/lib/class.tx_sglib_template.php";s:4:"b2dd";s:39:"classes/lib/class.tx_sglib_validate.php";s:4:"1e93";s:31:"classes/lib/class.tx_sgzlib.php";s:4:"3d59";s:42:"classes/model/class.tx_sglib_modelbase.php";s:4:"87a6";s:45:"classes/model/class.tx_sglib_modeldefault.php";s:4:"00af";s:37:"classes/tools/class.tx_sglib_json.php";s:4:"3a6f";s:24:"classes/trees/README.txt";s:4:"a659";s:45:"classes/trees/class.tx_demo_tcemainprocdm.php";s:4:"7819";s:47:"classes/trees/class.tx_sgzlib_tcemainprocdm.php";s:4:"7e5d";s:42:"classes/trees/class.tx_sgzlib_treeview.php";s:4:"4e8b";s:52:"classes/userfunc/class.tx_userfunction_constedit.php";s:4:"d9d9";s:40:"classes/view/class.tx_sglib_viewbase.php";s:4:"b1f8";s:43:"classes/view/class.tx_sglib_viewdetails.php";s:4:"011d";s:40:"classes/view/class.tx_sglib_viewlist.php";s:4:"f289";s:46:"classes/view/class.tx_sglib_viewsearchform.php";s:4:"b2b2";s:49:"classes/viewhelpers/class.tx_sglib_viewhelper.php";s:4:"ef01";s:56:"classes/viewhelpers/class.tx_sglib_viewhelpers_const.php";s:4:"b2dc";s:56:"classes/viewhelpers/class.tx_sglib_viewhelpers_email.php";s:4:"082e";s:55:"classes/viewhelpers/class.tx_sglib_viewhelpers_file.php";s:4:"ee35";s:56:"classes/viewhelpers/class.tx_sglib_viewhelpers_image.php";s:4:"281d";s:56:"classes/viewhelpers/class.tx_sglib_viewhelpers_input.php";s:4:"915b";s:55:"classes/viewhelpers/class.tx_sglib_viewhelpers_link.php";s:4:"c884";s:54:"classes/viewhelpers/class.tx_sglib_viewhelpers_lll.php";s:4:"95fa";s:54:"classes/viewhelpers/class.tx_sglib_viewhelpers_obj.php";s:4:"5dd6";s:54:"classes/viewhelpers/class.tx_sglib_viewhelpers_ref.php";s:4:"e007";s:57:"classes/viewhelpers/class.tx_sglib_viewhelpers_search.php";s:4:"1f67";s:55:"classes/viewhelpers/class.tx_sglib_viewhelpers_wrap.php";s:4:"31cc";s:52:"classes/xclasses/class.ux_SC_mod_tools_log_index.php";s:4:"6192";s:36:"classes/xclasses/class.ux_db_new.php";s:4:"7508";s:52:"classes/z_archiv/base/class.tx_sgzlib_collection.php";s:4:"c8fd";s:52:"classes/z_archiv/model/class.tx_sgzlib_tcaObject.php";s:4:"ee07";s:60:"classes/z_archiv/model/class.tx_sgzlib_tcaObjectAccessor.php";s:4:"e425";s:62:"classes/z_archiv/model/class.tx_sgzlib_tcaObjectCollection.php";s:4:"7a64";s:26:"csh/locallang_csh_base.xml";s:4:"6abe";s:29:"csh/locallang_csh_catbase.xml";s:4:"ccdc";s:14:"doc/manual.sxw";s:4:"6263";s:15:"doc/markers.txt";s:4:"de4c";s:29:"downloader/eid_downloader.php";s:4:"cf7a";s:15:"eid/delete.tmpl";s:4:"7efb";s:18:"eid/eid_delete.php";s:4:"989c";s:18:"eid/eid_export.php";s:4:"4ce4";s:19:"eid/undodelete.tmpl";s:4:"9de6";s:25:"example/ext_localconf.php";s:4:"9243";s:36:"example/ext_typoscript_constants.txt";s:4:"4c14";s:32:"example/ext_typoscript_setup.txt";s:4:"0475";s:54:"example/hooks/class.tx_myplugin_tceformsInlineHook.php";s:4:"d144";s:49:"example/hooks/class.tx_myplugin_tcemainprocdm.php";s:4:"93dc";s:38:"example/userfunc/ext_conf_template.txt";s:4:"443e";s:45:"example/userfunc/ext_typoscript_constants.txt";s:4:"d314";s:14:"img/add_12.gif";s:4:"388e";s:12:"img/back.gif";s:4:"6ae8";s:14:"img/bt_060.gif";s:4:"6d29";s:14:"img/bt_080.gif";s:4:"8219";s:14:"img/bt_100.gif";s:4:"781f";s:14:"img/bt_160.gif";s:4:"f31d";s:14:"img/bt_add.gif";s:4:"58a0";s:17:"img/bt_cancel.gif";s:4:"fea5";s:16:"img/bt_clear.gif";s:4:"eae7";s:15:"img/bt_next.gif";s:4:"b933";s:18:"img/bt_nextdis.gif";s:4:"2568";s:13:"img/bt_ok.gif";s:4:"044c";s:15:"img/bt_prev.gif";s:4:"ecca";s:18:"img/bt_prevdis.gif";s:4:"7173";s:16:"img/bt_reset.gif";s:4:"f437";s:17:"img/bt_search.gif";s:4:"0d3e";s:13:"img/first.gif";s:4:"4003";s:16:"img/firstdis.gif";s:4:"baad";s:12:"img/last.gif";s:4:"dad2";s:15:"img/lastdis.gif";s:4:"fdcb";s:12:"img/next.gif";s:4:"92dc";s:15:"img/nextdis.gif";s:4:"19d8";s:12:"img/prev.gif";s:4:"c365";s:15:"img/prevdis.gif";s:4:"44b1";s:20:"img/icons/add_16.gif";s:4:"0130";s:29:"img/icons/add_disabled_16.gif";s:4:"fa81";s:27:"img/icons/add_locked_16.gif";s:4:"46ac";s:21:"img/icons/back_16.gif";s:4:"ec9d";s:23:"img/icons/button_16.gif";s:4:"e484";s:23:"img/icons/cancel_16.gif";s:4:"1443";s:22:"img/icons/close_16.gif";s:4:"1443";s:23:"img/icons/delete_16.gif";s:4:"5649";s:25:"img/icons/dodelete_16.gif";s:4:"6de8";s:34:"img/icons/dodelete_disabled_16.gif";s:4:"84f1";s:34:"img/icons/dodelete_unhidden_16.gif";s:4:"84f1";s:23:"img/icons/dohide_16.gif";s:4:"7e4a";s:32:"img/icons/dohide_disabled_16.gif";s:4:"3cf4";s:25:"img/icons/dounhide_16.gif";s:4:"d0c6";s:34:"img/icons/dounhide_disabled_16.gif";s:4:"8424";s:26:"img/icons/editAsNew_16.gif";s:4:"61e2";s:35:"img/icons/editAsNew_disabled_16.gif";s:4:"ffc4";s:21:"img/icons/edit_16.gif";s:4:"ff11";s:30:"img/icons/edit_disabled_16.gif";s:4:"3885";s:28:"img/icons/edit_locked_16.gif";s:4:"7310";s:22:"img/icons/print_16.gif";s:4:"4b4a";s:23:"img/icons/reload_16.gif";s:4:"2eb0";s:21:"img/icons/save_16.gif";s:4:"933e";s:20:"img/icons/set_16.gif";s:4:"b11a";s:19:"img/icons/up_16.gif";s:4:"a932";s:23:"img/icons/update_16.gif";s:4:"24b9";s:21:"img/icons/view_16.gif";s:4:"15c1";s:30:"img/icons/view_disabled_16.gif";s:4:"f614";s:24:"img/icons/warning_16.gif";s:4:"5a05";s:16:"js/popup_func.js";s:4:"dd8e";s:17:"js/search_func.js";s:4:"6bb7";s:15:"js/user_func.js";s:4:"04fa";s:27:"paramspop/eid_paramspop.php";s:4:"7602";s:32:"static/button_image_ts/setup.txt";s:4:"a219";s:30:"static/icon_image_ts/setup.txt";s:4:"58e2";s:22:"zonst/ext_autoload.php";s:4:"b7f0";s:24:"zonst/z_ext_autoload.php";s:4:"3cf7";}',
	'suggests' => array(
	),
	'conflicts' => '',
);

?>