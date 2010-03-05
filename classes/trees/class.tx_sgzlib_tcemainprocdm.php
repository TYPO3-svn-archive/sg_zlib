<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 Stefan Geith <typo3dev2006@geithware.de>
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


class tx_sgzlib_tcemainprocdm {

	function setValues ($table,$id=0) {
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery($table,'1=1',Array('level'=>0, 'maincat'=>'','subcat'=>''));
		$ll = $this->setRecLevels ($table,$id);
		$maxLevel = count($ll)-1;
		if ($maxLevel>0) {
			$subCats = $this->setRekSubCats ($table,$id,$maxLevel);
		}
		$this->sorter = 0;
		$this->setRekMainCats ($table,0,$maxLevel);
		$res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery($table, '', array('tmpsort' => 'sorting'), array('tmpsort') );
	}

	function setRecLevels ($table,$id,$level=0,$parentList='0',$ll=Array(),$maxRek=50) {
		$query = 'parent IN ('.$parentList.')'.' AND deleted=0 AND hidden=0';
		$order = 'uid';
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid',$table,$query,'',$order);
		// t3lib_div::debug(Array('count($rows)'=>count($rows), '$table'=>$table, '$query'=>$query, '$order'=>$order, 'File:Line'=>__FILE__.':'.__LINE__));
		if (count($rows)) {
			for ($i=0;$i<count($rows);$i++) {
				$ll[$level][] = $rows[$i]['uid'];
			}
			$pl = implode(',',$ll[$level]);
			$concat = 'CONCAT(\''.str_repeat ('· ', $level).'\',title)';
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery($table,'uid in ('.$pl.')',	Array('level'=>$level, 'listtitle'=>$concat),Array('listtitle'));
			//$query = 'UPDATE '.$table.' SET level='.$level.', listtitle='.$concat.' WHERE uid in ('.$pl.')';
			//$GLOBALS['TYPO3_DB']->sql_query ($query);
			$ll = $this->setRecLevels ($table,$id,$level+1,$pl,$ll,$maxRek-1);
		}
		return ($ll);
	}

	function setRekSubCats ($table,$id,$level,$subCats=Array()) {
		if (count($subCats)) {
			for (reset($subCats);$key=key($subCats);next($subCats)) {
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery($table,'uid='.intval($key),Array('subcat'=>implode(',',$subCats[$key])));
			}
		}
		$oldSubCats = $subCats;
		$subCats = Array();
		$query = 'level='.intval($level).' AND deleted=0 AND hidden=0';
		$order = 'uid';
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid,parent',$table,$query,'',$order);
		if (count($rows)) {
			for ($i=0;$i<count($rows);$i++) {
				$subCats[$rows[$i]['parent'].'.'][] = $rows[$i]['uid'];
				if (is_array($oldSubCats[$rows[$i]['uid'].'.'])) {
					$subCats[$rows[$i]['parent'].'.'] = array_merge($subCats[$rows[$i]['parent'].'.'],$oldSubCats[$rows[$i]['uid'].'.']);
				}
			}
		}
		if ($level>0){
			$subCats = $this->setRekSubCats ($table,$parent,$level-1,$subCats);
		}
		return ($subCats);
	}

	function setRekMainCats ($table,$parent,$levelmax,$level=0,$mainCat='') {
		$query = 'parent='.intval($parent).' AND deleted=0 AND hidden=0';
		$order = 'tmpsort';
		$rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid,parent',$table,$query,'',$order);
		if (is_array($rows) && count($rows)) {
			foreach ($rows as $key=>$row) {
				$this->sorter += 64;	
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery($table,'uid='.$row['uid'],Array('maincat'=>$mainCat, 'sorting'=>$this->sorter));
				//$GLOBALS['TYPO3_DB']->exec_UPDATEquery($table,'uid='.$row['uid'],Array('maincat'=>$mainCat));
				$nextMainCat = $mainCat ? $mainCat.','.$row['uid'] : $row['uid'];
				$this->setRekMainCats ($table,$row['uid'],$levelmax,$level,$nextMainCat);
			}
		}
	}

	function processMoveRecord($command, $table, $id, $value, &$ref) {
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
	}
	

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/classes/trees/class.tx_sgzlib_tcemainprocdm.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/classes/trees/class.tx_sgzlib_tcemainprocdm.php']);
}
?>