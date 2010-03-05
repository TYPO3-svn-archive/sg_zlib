IMPORTANT !!!!!

Make sure, that field parent in cats-table is NOT NULL and default 0 !!!!!


#(1)###################################################################### ext_tables.sql
#
# Table structure for recursive cat tables
#
CREATE TABLE tx_demo_cat (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	...

	listtitle varchar(255) DEFAULT '' NOT NULL,
	tmpsort int(11) DEFAULT '0' NOT NULL,
	parent int(11) DEFAULT '0' NOT NULL,
	level int(11) DEFAULT '0' NOT NULL,
	maincat text NOT NULL,
	subcat text NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);

#(2)###################################################################### tca.php
...
		"listtitle" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:sg_demo/locallang_db.xml:tx_sgdemo_cat.title",		
			"config" => Array (
				"type" => "none",
				"size" => "40",	
			)
		),
		"parent" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:demo_plugin/locallang_db.xml:tx_demo_cat.parent",		
			"config" => Array (
				'type' => 'select',	
				'form_type' => 'user',	
				'userFunc' => 'tx_demo_treeview->displayCategoryTree',
				'treeView' => 1,	
				'foreign_table' => 'tx_demo_cat',	
				'autoSizeMax' => 22,
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 2,	
			)
		),
		"level" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:demo_plugin/locallang_db.xml:tx_demo_cat.level",		
			"config" => Array (
				"type" => "none",
				"size" => "40",	
			)
		),
		"maincat" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:demo_plugin/locallang_db.xml:tx_demo_cat.maincat",		
			"config" => Array (
				"type" => "none",
				"size" => "40",	
			)
		),
		"subcat" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:demo_plugin/locallang_db.xml:tx_demo_cat.subcat",		
			"config" => Array (
				"type" => "none",
				"size" => "40",	
			)
		),
...
	"types" => array (
		"0" => array("showitem" => "--palette--;;1;;1-1-1, title;;;;2-2-2, description, parent;;;;3-3-3, image;;;;3-3-3,level;;;;3-3-3, maincat, subcat")
	),

#(3)###################################################################### locallang_db.xml
...
			<label index="tx_demo_cat.parent">Parent Category</label>
			<label index="tx_demo_cat.level">Level</label>
			<label index="tx_demo_cat.maincat">Main Categories</label>
			<label index="tx_demo_cat.subcat">Sub Categories</label>
...
			<label index="tx_demo_cat.parent">Übergeordnete Kategorie</label>
			<label index="tx_demo_cat.level">Ebene</label>
			<label index="tx_demo_cat.maincat">Übergeordnete Kategorien</label>
			<label index="tx_demo_cat.subcat">Untergeordnete Kategorien</label>

#(4)###################################################################### ext_tables.php
...
include_once(t3lib_extMgm::extPath($_EXTKEY).'class.tx_demo_treeview.php');
...
$TCA["tx_demo_cat"] = array (
	"ctrl" => array (
		'label'     => 'listtitle',	
		...
		'treeParentField' => 'parent',
		...

#(5)###################################################################### ext_localconf.php
...
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
	'EXT:demo_plugin/class.tx_demo_tcemainprocdm.php:tx_demo_tcemainprocdm';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] =
	'EXT:demo_plugin/class.tx_demo_tcemainprocdm.php:tx_demo_tcemainprocdm';


#(6)###################################################################### classes/class.tx_sgdemo_treeview.php
require_once(t3lib_extMgm::extPath('sg_zlib').'classes/trees/class.tx_sgzlib_treeview.php');

class tx_sgdemo_treeview extends tx_sgzlib_treeview {
	var $mainTable = 'tx_sgdemo';
	var $catTable = 'tx_sgdemo_class';
	var $extKey = 'sg_demo';	// The extension key.

}

#(7)###################################################################### classes/class.tx_sgdemo_tcemainprocdm.php
//copy class.tx_demo_tcemainprocdm.php and adjust $mainTable and $catTable;
