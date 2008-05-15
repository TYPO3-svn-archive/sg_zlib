<?php
...

if(TYPO3_MODE == 'FE') {
	require_once(t3lib_extMgm::extPath('my_plugin').'pi1/class.tx_myplugin_main.php');
	require_once(t3lib_extMgm::extPath('my_plugin').'pi1/class.tx_myplugin_import.php');
}
?>