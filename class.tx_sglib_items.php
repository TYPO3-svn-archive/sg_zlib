<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2002-2007 Stefan Geith (typo3dev2007@geithware.de)
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   50: class tx_sglib_items
 *   88:     private function init(tx_sglib_config $confObj, tx_sglib_debug $debugObj, tx_sglib_const $constObj, tx_sglib_lang $langObj, tx_sglib_permit $permitObj)
 *  117:     private function _fCount ($name=NULL)
 *  140:     function __destruct()
 *  154:     function prepareItems($table,$field,$em,$row)
 *  424:     function getItemList($table,$field,$em)
 *  437:     function getItemPid($table,$field,$index=NULL)
 *  454:     function getItemRecord($table,$field,$index=NULL)
 *  471:     function getItemSub($table,$field,$index=NULL)
 *  489:     function getItemCountAdd($table,$field,$index=NULL,$add=0)
 *  506:     private function _addItemsFromTS($myItems,$itemsTS)
 *  527:     private function _addItemsFromTCA($myItems,$itemsTCA)
 *  549:     private function _addItemsFromFile($myItems,$itemsFileName,$em)
 *  576:     private function _addItemsFromDB($myItems,$table,$field)
 *  624:     function getItemText($mode,$lnr,$field,$myItems,$key,$PCA=Array(),$key1='',$key2='textFormat',$key3='cntWrap')
 *
 * TOTAL FUNCTIONS: 14
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_items {
	private static $instance = Array();

	private $factoryObj = NULL;
	private $confObj;
	private $debugObj;
	private $cObj;
	private $constObj;
	private $permitObj;
	private $defaultDesignator;
	private $emTable=Array();
	private $lastEm = 0;
	private $lastTable = '';

	private $itemListEm=Array();
	private $itemListIcons;
	private $itemListPids;
	private $itemListSubs;
	private $itemListRecords;
	private $itemListCounts;

	protected function __construct() {}

	private function __clone() {}

	public static function getInstance($designator, tx_sglib_factory $factoryObj) {
		if (!isset(self::$instance[$designator])) {
			self::$instance[$designator] = new tx_sglib_items();
			self::$instance[$designator]->init($factoryObj);
		}
		return (self::$instance[$designator]);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$tx_sglib_config $confObj, tx_sglib_debug $debugObj, tx_sglib_const $constObj, tx_sglib_lang $langObj, tx_sglib_permit $permitObj: ...
	 * @return	[type]		...
	 */
	private function init(tx_sglib_factory $factoryObj) {
		$this->_fCount(__FUNCTION__);
		$this->factoryObj = $factoryObj;
		$this->confObj = $factoryObj->confObj;
		$this->defaultDesignator = $this->confObj->getDesignator();
		$this->debugObj = $factoryObj->debugObj;
		$this->constObj = $factoryObj->constObj;
		$this->langObj = $factoryObj->langObj;
		$this->permitObj = $factoryObj->permitObj;

		$this->emTable = Array (
			SGZ_TEXT=>SGZ_TEXT,
			SGZ_FORM=>SGZ_FORM,
			SGZ_AUTO=>SGZ_FORM,
			SGZ_AUTOHIDDEN=>SGZ_TEXT,
			SGZ_CMD=>SGZ_TEXT,
			SGZ_LIST=>SGZ_TEXT,
			SGZ_LISTEDIT=>SGZ_TEXT,
			SGZ_SEARCH=>SGZ_SEARCH,
			SGZ_SEARCHUSED=>SGZ_SEARCHUSED,
		);
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	private function _fCount ($name=NULL) {
		static $callCount = NULL;
		if (!isset($callCount)) {
			$callCount = Array();
			foreach(get_class_methods(__CLASS__) as $key=>$value) {
				$callCount[$value] = 0;
			}
			unset($callCount['_fCount']);
			unset($callCount['__destruct']);
		}
		if (isset($name)) {
			$callCount[$name]++;
			return;
		} else {
			return $callCount;
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function __destruct() {
		if (is_object($this->debugObj)) $this->debugObj->debugIf('callCount',Array('Class '.__CLASS__ => $this->_fCount()));
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$em: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$row: ...
	 * @return	[type]		...
	 */
	function prepareItems($table,$field,$em,$row) {
		GLOBAL $TCA;
		$this->_fCount(__FUNCTION__);

		$this->lastEm = $em;
		$this->lastTable = $table;

		$myItems = Array();
		$mySubs = is_array($this->itemListSubs[$field]) ? $this->itemListSubs[$field] : Array();
		$myIcons = is_array($this->itemListIcons[$field]) ? $this->itemListIcons[$field] : Array();
		$myRecord = is_array($this->itemListRecords[$field]) ? $this->itemListRecords[$field] : Array();
		$myPidVal = is_array($this->itemListPids[$field]) ? $this->itemListPids[$field] :  Array();
		if (!is_array($this->itemListEm[$field])) {
			$this->itemListEm[$field] = Array();
		}
		if (!is_array($this->itemListCounts[$field])) {
			$this->itemListCounts[$field] = Array();
		}

		if (intval($em)>=SGZ_SEARCH) {
			$myItems = $this->_addItemsFromTS($myItems,$this->confObj->get($table.'.conf.'.$field.'.preItems.'));
		}
		$myItems = $this->_addItemsFromTCA($myItems,$this->confObj->get($table.'.conf.'.$field.'.items.'));
		$myItems = $this->_addItemsFromTS($myItems,$this->confObj->get($table.'.conf.'.$field.'.moreItems.'));
		if (intval($em)>=SGZ_SEARCH) {
			$myItems = $this->_addItemsFromTS($myItems,$this->confObj->get($table.'.search.'.$field.'.searchItems.'));
		}
		$myItems = $this->_addItemsFromFile($myItems,$this->confObj->get($table.'.conf.'.$field.'.itemsFromFile'),$em);

		if (intval($em)>=SGZ_SEARCH && strlen($this->confObj->get($table.'.search.'.$field.'.removeItems'))) {
			$tmp = t3lib_div::intExplode(',',$this->confObj->get($table.'.search.'.$field.'.removeItems'));
			if (count($tmp)) foreach ($tmp as $key=>$value) {
				unset ($myItems[$value.'.']);
			}
		}


		$refTable = $this->confObj->get($table.'.conf.'.$field.'.foreign_table');
		$mySubField = $this->confObj->get($table.'.search.'.$field.'.foreign.subSearch');
		if (strlen($refTable)<2 && strcmp($this->confObj->get($table.'.conf.'.$field.'.internal_type'),'db')==0) {
					$refTable = $this->confObj->get($table.'.conf.'.$field.'.allowed');
		}
		if (strlen($refTable)>1) {
			$myClause = Array();
			$alt = '';

			if (!is_array($TCA[$refTable])) {
				t3lib_div::loadTCA($refTable);
			}
			$refTableLangOl = $TCA[$refTable]['ctrl']['lang_ol'];

			$pForeign = $this->confObj->get($table.'.conf.'.$field.'.foreign.');
			$pSForeign = $this->confObj->get($table.'.search.'.$field.'.foreign.');
			if (intval($em)>=SGZ_SEARCH && is_array($pSForeign)) {
				$pForeign = t3lib_div::array_merge_recursive_overrule($pForeign,$pSForeign);
				if ($pForeign['where']) {
					$pForeign['where'] = $this->cObj->substituteMarkerArray($pForeign['where'], $row, '###|###');
					if (strpos($pForeign['where'],'###')>0) {
						$pForeign['where'] = $pForeign['whereDefault'];
					}
				}
			} else if (intval($em)<SGZ_SEARCH && is_array($pForeign)) {
				if ($pForeign['where']) {
					$pForeign['where'] = $this->lCObj->substituteMarkerArray($pForeign['where'], $row, '###|###');
					if (strpos($pForeign['where'],'###')>0) {
						$pForeign['where'] = $pForeign['whereDefault'];
					}
				}
			}

			$myLinkField = $TCA[$refTable]['ctrl']['label'];
			$myLinkAltField = $TCA[$refTable]['ctrl']['label_alt'];
			$myLinkAltForce = $TCA[$refTable]['ctrl']['label_alt_force'];
			$mySortField = $myLinkField;;
			$myIconField = '';

			if (is_array($pForeign)) {
				$mySortField = isset($pForeign['sort']) ? $pForeign['sort'] : $myLinkField;
				$myIconField = $pForeign['iconfield'];
				if (isset($pForeign['label'])){
					$myLinkField = $pForeign['label'];
				}
				$myLinkAltField = isset($pForeign['label_alt']) ? $pForeign['label_alt'] : '';
				$alt = $pForeign['alt'];
				if (strlen($pForeign['where'])>2){
					$myClause[] = $this->cObj->substituteMarkerArray($this->cObj->insertData($pForeign['where']), $row, '###|###');
				}
			}

			if (strlen($myLinkAltField)>2) {
				$mLAF = t3lib_div::trimExplode(',',$myLinkAltField);
			} else {
				$mLAF = '';
			}

			if (is_array($TCA[$refTable]['ctrl']['enablecolumns']) &&
					isset($TCA[$refTable]['ctrl']['enablecolumns']['disabled'])) {
				$myClause[] = $TCA[$refTable]['ctrl']['enablecolumns']['disabled'].'=0';
			}

			if (strlen($TCA[$refTable]['ctrl']['delete'])>0) {
				$myClause[] = $TCA[$refTable]['ctrl']['delete'].'=0';
			}

			$crallow_users = '';
			$crallow_groups = '';
			if ((intval($em)>0 && intval($em)<SGZ_SEARCH && is_array($pForeign['crallow'])) || $pForeign['allwaysCrallow']==1)  {
				if ($pForeign['crallow']['user']) {
					$crallow_users = $pForeign['crallow']['user'];
				}
				if ($pForeign['crallow']['group']) {
					$crallow_groups = $pForeign['crallow']['group'];
				}
			} else if (isset($TCA[$refTable]['ctrl']['enablecolumns']['fe_group'])) {
				$myFeGroup = $TCA[$refTable]['ctrl']['enablecolumns']['fe_group'];
				if ($myFeGroup) {
					if (count($this->permitObj->getFeGroups())>0) {
						$myClause[] = $myFeGroup.' IN (\'\',0,'.(implode(',',$this->permitObj->getFeGroups())).')';
					} else {
						$myClause[] = $myFeGroup.' IN (\'\',0)';
					}
				}
			}

			$myPid = $this->confObj->getPidList();
			if (strlen($this->confObj->get($table.'.conf.'.$field.'.foreign_pid'))) {
				$myPid = $this->confObj->get($table.'.conf.'.$field.'.foreign_pid');
			}
			if (intval($myPid)>0 || strlen($myPid)>1) {
				$tmp = explode(',',$myPid);
				if (!in_array(0,$tmp)) {
					$tmp[] = 0;
				}
				$myClause[] = 'pid in ('.implode(',',$tmp).')';
			}

			// check if another relation
			if (intval($em)==SGZ_TEXT) {
			} else if (intval($em)<SGZ_SEARCH) {
				$tmp = $this->confObj->get($table.'.conf.'.$field.'.relation.foreign');
				if (strlen($tmp)>0) {
					$myClause[] = $tmp.'='.intval($row[$this->confObj->get($table.'.conf.'.$field.'.relation.local')]);
				}
			} else if (intval($em)>=SGZ_SEARCH) {
				$tmp = $this->confObj->get($table.'.search.'.$field.'.relation.foreign');
				if (strlen($tmp)>0) {
					$myClause[] = $tmp.'='.intval($row[$this->confObj->get($table.'.search.'.$field.'.relation.local')]);
				}
			}


			$tmp = ($pForeign['uid']) ? $pForeign['uid'].' AS ' : '';
			if ($pForeign['readFull']) {
				$select = '*'.
					($myLinkField ? ', '.$myLinkField.' AS myDisplayName' : '').
					($myIconField ? ', '.$myIconField.' AS myIcon' : '').
					($crallow_users ? ', '.$crallow_users.' AS myCrUser' : '').
					($crallow_groups ? ', '.$crallow_groups.' AS myCrGroup' : '');
				if (is_array($refTableLangOl) && count($refTableLangOl)) {
					$select .= ','.implode(',',$refTableLangOl);
				}
			} else {
				$select = $tmp.'uid,pid'.
					($mySubField ? ', '.$mySubField.'' : '').
					($myLinkAltField ? ', '.$myLinkAltField.'' : '').
					($myLinkField ? ', '.$myLinkField.' AS myDisplayName' : '').
					($myIconField ? ', '.$myIconField.' AS myIcon' : '').
					($crallow_users ? ', '.$crallow_users.' AS myCrUser' : '').
					($crallow_groups ? ', '.$crallow_groups.' AS myCrGroup' : '');
			}
			if (is_array($refTableLangOl) && isset($refTableLangOl[$myLinkField])) {
				$select .= ','.$refTableLangOl[$myLinkField].' AS myDisplayName_lang_ol';
				$refTableLangOl['myDisplayName'] = 'myDisplayName_lang_ol';
				if (!$pForeign['readFull']) {
					unset($refTableLangOl[$myLinkField]);
				}
			}

			$query = '';
			if (count($myClause)) {
				$query .= implode(' AND ',$myClause);
			}

			$order = ($mySortField ? $mySortField : 'uid').$this->mSqlConf['addToOrder'];
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$refTable,$query,'',$order);

			$this->debugObj->debugVal('getitemdetails',$field,Array('$myClause('.$field.')'=>$myClause,
				'$select'=>$select, '$refTable'=>$refTable, '$query'=>$query, '$order'=>$order,
				'numRows='=>$GLOBALS['TYPO3_DB']->sql_num_rows($res), 'File:Line='=>__FILE__.':'.__LINE__));
			if ($res) {
				$this->debugObj->debugIf('sql',Array('query'=>$query, 'res /  count'=>$res.' / '.$GLOBALS['TYPO3_DB']->sql_num_rows($res)));
				while($myRow=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
					$this->langObj->replaceLangOverlay($myRow,$refTable,$refTableLangOl);

					if ($crallow_users || $crallow_groups) {
						$userOK = t3lib_div::inList($myRow['myCrUser'],$this->permitObj->getFeUid());
						$tmp = explode(',',$myRow['myCrGroup']);
						$groupOK = count(array_intersect ($tmp, $this->permitObj->getFeGroups()));
						if (!$userOK && !$groupOK) {
							unset ($myRow);
						}
					}

					if (is_array($myRow)) {
						if (is_array($mLAF)) {
							$t = Array ();
							if ($myRow['myDisplayName']) {
								$t[] = $myRow['myDisplayName'];
							}
							for ($i=0;$i<count($mLAF);$i++) {
								if ($myRow[$mLAF[$i]]) {
									$t[] = $myRow[$mLAF[$i]];
								}
							}
							if ($myLinkAltForce) {
								$myRow['myDisplayName'] = implode(', ',$t);
							} else {
								$myRow['myDisplayName'] = $t[0];
							}
						}

						$tmpItems[$myRow['uid'].'.'] = $myRow['myDisplayName'];
						$mySubs[$myRow['uid'].'.'] = $myRow[$mySubField];
						$myPidVal[$myRow['uid'].'.'] = $myRow['pid'];
						if (strcmp($this->confObj->get($table.'.conf.'.$field.'.refType'),'inside')==0) {
							$myRecord[$myRow['uid'].'.'] = $myRow;
						}
						$myIcons[$myRow['uid'].'.'] = $myIconField ?
							$TCA[$refTable]['columns'][$myIconField]['config']['uploadfolder'].'/'.$myRow['myIcon'] : '';
						if (strlen($alt)>0 && strlen($myRow[$alt])>0) {
							$tmpItems[$myRow['uid'].'.'] .= ' - ('.$myRow[$alt].')';
						}
					}
				}
			}
		}
		if (is_array($tmpItems)) {
			if ($this->confObj->get($table.'.conf.'.$field.'.sortValues')>0) {
				natcasesort($tmpItems);
			}
			$myItems = array_merge((array)$myItems,$tmpItems);
		}
		$myItems = $this->_addItemsFromDB($myItems,$table,$field);

		if (intval($em)>=SGZ_SEARCH) {
			$myItems = $this->_addItemsFromTS($myItems,$this->confObj->get($table.'.conf.'.$field.'.postItems.'));
		}

		$this->debugObj->debugVal('getitems',$field,Array('$myItems('.$field.')'=>$myItems, 'File:Line'=>__FILE__.':'.__LINE__));

		$this->itemListEm[$field][$em] = $myItems;
		$this->itemListSubs[$field] = $mySubs;
		$this->itemListIcons[$field] = $myIcons;
		$this->itemListRecords[$field] = $myRecord;
		$this->itemListPids[$field] = $myPidVal;
		$this->debugObj->debugIf('itemlist',Array('itemListEm'=>$this->itemListEm, 'itemListSubs'=>$this->itemListSubs,
			'itemListIcons'=>$this->itemListIcons, 'itemListRecords'=>$this->itemListRecords,
			'itemListEm'=>$this->itemListEm, 'File:Line'=>__FILE__.':'.__LINE__));

		return ;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$em: ...
	 * @return	[type]		...
	 */
	function getItemList($table,$field,$em) {
		$table = $table ? $table : $this->lastTable;
		return ($this->itemListEm[$field][$em]);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$index: ...
	 * @return	[type]		...
	 */
	function getItemPid($table,$field,$index=NULL) {
		$table = $table ? $table : $this->lastTable;
		if (!isset($index)) {
			return ($this->itemListPids[$field]);
		} else {
			return ($this->itemListPids[$field][$index]);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$index: ...
	 * @return	[type]		...
	 */
	function getItemRecord($table,$field,$index=NULL) {
		$table = $table ? $table : $this->lastTable;
		if (!isset($index)) {
			return ($this->getItemRecords[$field]);
		} else {
			return ($this->getItemRecords[$field][$index]);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$index: ...
	 * @return	[type]		...
	 */
	function getItemSub($table,$field,$index=NULL) {
		$table = $table ? $table : $this->lastTable;
		if (!isset($index)) {
			return ($this->getItemSubs[$field]);
		} else {
			return ($this->getItemSubs[$field][$index]);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$index: ...
	 * @param	[type]		$add: ...
	 * @return	[type]		...
	 */
	function getItemCountAdd($table,$field,$index=NULL,$add=0) {
		$table = $table ? $table : $this->lastTable;
		if (!isset($index)) {
			return ($this->getItemCounts[$field]);
		} else {
			$this->getItemCounts[$field][$index] += $add;
			return ($this->getItemCounts[$field][$index]);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$myItems: ...
	 * @param	[type]		$itemsTS: ...
	 * @return	[type]		...
	 */
	private function _addItemsFromTS($myItems,$itemsTS) {
		if (!is_array($myItems)) {
			$myItems = Array();
		}
		if (is_array($itemsTS)) {
			for (reset($itemsTS);$key=key($itemsTS);next($itemsTS)) {
				$vValue = isset($itemsTS[$key]['id']) ? $itemsTS[$key]['id'] : ($key=='null' ? 0 : $key);
				$vText = isset($itemsTS[$key]['text']) ? $itemsTS[$key]['text'] : $vValue;
				$myItems[$vValue.'.'] = $this->langObj->getLLL($vText);
			}
		}
		return ($myItems);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$myItems: ...
	 * @param	[type]		$itemsTCA: ...
	 * @return	[type]		...
	 */
	private function _addItemsFromTCA($myItems,$itemsTCA) {
		if (!is_array($myItems)) {
			$myItems = Array();
		}
		if (is_array($itemsTCA)) {
			for (reset($itemsTCA);$key=key($itemsTCA);next($itemsTCA)) {
				if (!isset($myItems[($itemsTCA[$key][1].'.')])) {
					$myItems[$itemsTCA[$key][1].'.'] = $this->langObj->getLLL($itemsTCA[$key][0]);
				}
			}
		}
		return ($myItems);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$myItems: ...
	 * @param	[type]		$itemsFileName: ...
	 * @param	[type]		$em: ...
	 * @return	[type]		...
	 */
	private function _addItemsFromFile($myItems,$itemsFileName,$em) {
		if (!is_array($myItems)) {
			$myItems = Array();
		}
		if ($itemsFileName) {
			$myFile =  $GLOBALS['TSFE']->tmpl->getFileName($itemsFileName);
			if (file_exists($myFile)) {
				$tmp = file ($myFile);
				if (is_array($tmp)) for ($i=0;$i<count($tmp);$i++) if(trim($tmp[$i])) {
					$it = t3lib_div::trimExplode('=',$tmp[$i],3);
					if (!isset($it[1])) { $it[1] = $it[0]; }
					if (!isset($it[2])) { $it[2] = $it[1]; }
					$myItems[$it[0].'.'] = (intval($em)>=SGZ_SEARCH) ? $it[2] : $it[1];
				}
			}
		}
		return ($myItems);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$myItems: ...
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @return	[type]		...
	 */
	private function _addItemsFromDB($myItems,$table,$field) {
		if (!is_array($myItems)) {
			$myItems = Array();
		}
		if (strcmp($this->confObj->get($table.'.conf.'.$field.'.type'),'input')==0 && intval($this->confObj->get($table.'.search.'.$field.'.type'))>1 && intval($em)>=SGZ_SEARCH) {
			$select = $field.', count(*) AS count';

			$dW = $this->confObj->get($table.'.ctrl.defaultWhere');
			if ($dW) {
				$query = $this->replaceArray($this->cObj->insertData(
					str_replace('###val###',trim($this->confObj->get($table.'.ctrl.defaultWhereVal')),
					   str_replace('###feuser_id###',$this->permitObj->getFeUid(),$dW))
					) , $this->constObj->getConst());
			} else {
				$query = '1=1 ';
			}

			$query .= $this->cObj->enableFields($table,0); //'deleted=0 and hidden=0';
			$group = $field;
			$order = $field; //'count DESC,'.$field;
			$items = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select,$table,$query,$group,$order,'');
			$this->debugval('getitemdetails',$field,Array('$select'=>$select, '$table'=>$table, '$query'=>$query, '$group'=>$group,
				'$order'=>$order, 'count='=>(is_array($items) ? count($items) : 0), 'File:Line'=>__FILE__.':'.__LINE__));
			if (is_array($items)) for ($i=0;$i<count($items);$i++) {
				$vValue = $items[$i][$field]; //urlencode($items[$i][$field]);
				if (trim($vValue)) {
					$myItems[$vValue] = htmlspecialchars($items[$i][$field]);
				}
			}
		}
		return ($myItems);
	}


	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$mode: ...
	 * @param	[type]		$lnr: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$myItems: ...
	 * @param	[type]		$key: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$key1: ...
	 * @param	[type]		$key2: ...
	 * @param	[type]		$key3: ...
	 * @return	[type]		...
	 */
	function getItemText($mode,$lnr,$field,$myItems,$key,$PCA=Array(),$key1='',$key2='textFormat',$key3='cntWrap') {
		$this->_fCount(__FUNCTION__);
		if (!$key1) {
			$key1 = $field;
		}
		$format = $PCA[$mode][$key1][$key2];
		if (strlen($format)>6) {
			$id = (intval($key).'.'==$key ? intval($key) : $key);
			$iconWrap = t3lib_div::trimExplode('|',$PCA[$mode][$key1]['iconWrap']);
			$icon = $this->itemListIcons[$field][$key] ?
				$iconWrap[0].$this->itemListIcons[$field][$key].$iconWrap[1] : $PCA[$mode][$key1]['noIcon'];
			$cnt = $this->itemListCounts[$field][$key];
			$cntWrap = t3lib_div::trimExplode('|',$PCA[$mode][$key1][$key3]);
			$cnt = intval($cnt) ? $cntWrap[0].$cnt.$cntWrap[1]: $PCA[$mode][$key1]['cntZero'];
			$text = str_replace (
				Array('###n###', '###text###','###id###','###cnt###'),
				Array($lnr, $myItems[$key], $id, $cnt), str_replace('###icon###', $icon, $format));
		} else {
			$text = $myItems[$key];
		}

		return ($text);
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sglib_items.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sglib_items.php']);
}
?>