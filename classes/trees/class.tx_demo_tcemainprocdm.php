<?php

require_once(t3lib_extMgm::extPath('sg_zlib').'classes/trees/class.tx_sgzlib_tcemainprocdm.php');

class tx_demo_tcemainprocdm {
	var $mainTable = 'tx_demo';
	var $catTable = 'tx_demo_cat';


	function processDatamap_postProcessFieldArray ($status, $table, $id, &$fieldArray, &$ref) {
		// GLOBAL $TCA;
		// $confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['demo_plugin']);
		if ($table==$this->catTable) {
			$row = $ref->datamap[$this->catTable][$id];
			if ($fieldArray['parent']==$id) {
				$row['parent'] = $fieldArray['parent'] = 0;
			}
		}
	}

	function processDatamap_afterDatabaseOperations ($status, $table, $id, &$fieldArray, &$ref) {
		if ($table==$this->catTable) {
			$res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery($table, '', array('tmpsort' => 'sorting'), array('tmpsort') );
			$processRecursiv = new tx_sgzlib_tcemainprocdm;
			$processRecursiv->setValues($table,$id);
		}
	}

	function processCmdmap_postProcess($command, $table, $id, $value, &$ref) {
		if ($table==$this->catTable) {
			if (strcmp($command,'move')==0) {
				$from = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid,level,sorting,tmpsort',$table,'uid='.$id,'','');
				$to = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid,level,sorting,tmpsort',$table,'uid='.abs($value),'','');
				if (is_array($from) && count($from) && is_array($to) && count($to)) {
					$level = $from[0]['level'];
					$value = $from[0]['tmpsort']<$to[0]['tmpsort'] ? 1 : -1;
					$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid,sorting,tmpsort',$table,'level='.$level,'','tmpsort','','uid');
					$last = 0; $active = 0;
					$prevnext=Array();
					if (is_array($rows) && count($rows)) foreach($rows as $key=>$row) {
						if ($last) {
							$prevnext[$last.'.']['next'] = $key;
						}
						$prevnext[$key.'.'] = array('prev'=>$last, 'next'=>0);
						$last = $key;
					}
					$tmpSort = ($value>0) ? $prevnext[$id.'.']['next'] : $prevnext[$id.'.']['prev'];
					if ($tmpSort) {
						$newSort = $rows[$tmpSort]['tmpsort']+$value;
						$GLOBALS['TYPO3_DB']->exec_UPDATEquery($table,'uid='.$id,Array('sorting'=>$newSort, 'tmpsort'=>$newSort));
					}
				}
			}
			$processRecursiv = new tx_sgzlib_tcemainprocdm;
			$processRecursiv->setValues($table,$id);
		}
	}

}
?>