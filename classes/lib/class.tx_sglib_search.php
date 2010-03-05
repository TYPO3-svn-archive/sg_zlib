<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2002-2009 Stefan Geith (typo3devYYYY@geithware.de)
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
 *   44: class tx_sglib_markers
 *   77:     private function init(tx_sglib_factory $factoryObj)
 *   93:     protected function _fCount ($name=NULL)
 *  118:     public function __destruct()
 *  128:     public function __get($nm)
 *  148:     public function __set($nm, $val)
 *
 * TOTAL FUNCTIONS: 8
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */


class tx_sglib_search {
	private static $instance = Array();

	protected $factoryObj = NULL;
	protected $confObj;
	protected $debugObj;
	protected $langObj;
	protected $itemsObj;
	protected $defaultDesignator;
	protected $model;
	protected $cObj;

	protected $mainTable;

	protected function __construct() {}

	private function __clone() {}

	/**
	 * Returns a singlton instance of tx_sglib_markers
	 *
	 * @param	string				Designator
	 * @param	tx_sglib_factory	FactoryObj
	 * @return	tx_sglib_markers	Instantiated Object
	 */
	
	public static function getInstance($designator, tx_sglib_factory $factoryObj) {
		if (!isset(self::$instance[$designator])) {
			self::$instance[$designator] = new tx_sglib_search();
			self::$instance[$designator]->init($factoryObj);
		}
		return (self::$instance[$designator]);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$tx_sglib_config $confObj, tx_sglib_debug $debugObj: ...
	 * @param	[type]		$cObj: ...
	 * @return	[type]		...
	 */
	private function init(tx_sglib_factory $factoryObj) {
		$this->_fCount(__FUNCTION__);
		$this->factoryObj = $factoryObj;
		$this->confObj = $factoryObj->confObj;
		$this->defaultDesignator = $this->confObj->getDesignator();
		$this->debugObj = $factoryObj->debugObj;
		$this->langObj = $factoryObj->langObj;
		$this->itemsObj = $factoryObj->itemsObj;
		$this->mainTable = $this->confObj->getTCAname();
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');

	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	protected function _fCount ($name=NULL) {
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
	public function __destruct() {
		if (is_object($this->debugObj)) $this->debugObj->debugIf('callCount',Array('Class '.__CLASS__ => $this->_fCount()));
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$nm: ...
	 * @return	[type]		...
	 */
    public function __get($nm)
    {
		switch ($nm) {
			//case 'model':
			//	return ($this->model);
			default:
				$error = 'get("'.$nm.'") failed ... Variable unknown !!';
				$this->debugObj->showError(0,$error,0,'','',1);
				return ('');
		}
		return ('');
    }

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$nm: ...
	 * @param	[type]		$val: ...
	 * @return	[type]		...
	 */
	public function __set($nm, $val)
	{
		switch ($nm) {
			//case 'model':
			//	$this->model = $val;
			//	break;
			default:
				$error = 'set("'.$nm.'") failed ... Variable unknown !!';
				$this->debugObj->showError(0,$error,0,'','',1);
				break;
		}
	}





	public function resolveSearchReferences($table,&$searchReferences) {
		if (is_array($searchReferences) && count($searchReferences)) {
			// t3lib_div::debug(Array('$table'=>$table, '$searchReferences'=>$searchReferences, 'File:Line'=>__FILE__.':'.__LINE__));
			foreach ($searchReferences as $searchKey=>$searchReference) {
				$myRef = $this->confObj->getReferences($table);
				if (!isset($myRef['table'][$searchKey])) {
					t3lib_div::debug(Array('ERROR:'=>'RefTabel not found', '$table'=>$table, '$searchKey'=>$searchKey, '$myRef'=>$myRef['table'], 'File:Line'=>__FILE__.':'.__LINE__));
					return;
				} else {
					$refTable =$myRef['table'][$searchKey];
					$this->resolveSearchReference($refTable,$searchKey,$searchReferences[$searchKey]);
				}
			}
		}
		// t3lib_div::debug(Array('$searchReferences'=>$searchReferences, 'File:Line'=>__FILE__.':'.__LINE__));
	}

	public function resolveSearchReference($table,$searchKey,&$searchReference) {
			// t3lib_div::debug(Array('$table'=>$table, '$searchKey'=>$searchKey, '$searchReference'=>$searchReference, 'File:Line'=>__FILE__.':'.__LINE__));

			if (is_array($searchReference['t']) && count($searchReference['t'])) {
				// t3lib_div::debug(Array('Processing T'=>$searchReference['t'], 'File:Line'=>__FILE__.':'.__LINE__));
				foreach ($searchReference['t'] as $refKey=>$refReferences) {
					$myRef = $this->confObj->getReferences($table);
					if (!isset($myRef['table'][$refKey])) {
						t3lib_div::debug(Array('ERROR:'=>'RefTabel not found', '$table'=>$table, '$fieldRef'=>$refKey, '$myRef'=>$myRef['table'], 'File:Line'=>__FILE__.':'.__LINE__));
						return;
					} else {
						$refTable =$myRef['table'][$refKey];
						$this->resolveSearchReference($refTable,$refKey,$searchReference['t'][$refKey]);

						// OK - now search for 
						// t3lib_div::debug(Array('$searchReference'=>$searchReference, 'File:Line'=>__FILE__.':'.__LINE__));
						// t3lib_div::debug(Array('Find Records of $table'=>$table, 'where '.$refKey.' in'=>$searchReference['t'][$refKey]['i'], 'File:Line'=>__FILE__.':'.__LINE__));
						if (is_array($searchReference['t'][$refKey]['i']) && count($searchReference['t'][$refKey]['i'])) {
							// todo if MM //
							$conf = $this->confObj->get($table.'.conf.'.$refKey.'.');
							if ($conf['MM']) {
								if ($conf['MM_opposite_field']) {
									$idList = tx_sgdiv::exec_SELECTgetField('uid_foreign','uid_foreign',$conf['MM'],'uid_local IN ('.implode(',',$searchReference['t'][$refKey]['i']).')');
								} else {
									$idList = tx_sgdiv::exec_SELECTgetField('uid_local','uid_local',$conf['MM'],'uid_foreign IN ('.implode(',',$searchReference['t'][$refKey]['i']).')');
								}
							} else {
								$idList = $this->getByIdList($table,$refKey,$searchReference['t'][$refKey]['i']);
							}
							// t3lib_div::debug(Array('$idList'=>$idList, 'File:Line'=>__FILE__.':'.__LINE__));
							unset ($searchReference['t'][$refKey]['i']);
							$searchReference['i'] = $this->logicalAndArrayKeys($searchReference['i'],$idList);
						}
					}
					if (count($searchReference['t'][$refKey])==0) {
						unset ($searchReference['t'][$refKey]);
					}
				}
				if (count($searchReference['t'])==0) {
					unset ($searchReference['t']);
				}
			}
			//if (is_array($searchReference['i']) && count($searchReference['i'])) {
				//t3lib_div::debug(Array('Find Records of $table'=>$table, 'where uid in'=>$searchReference['i'], 'File:Line'=>__FILE__.':'.__LINE__));
			//}
			if (is_array($searchReference['f']) && count($searchReference['f'])) {
				$idList = $this->searchInFields($table,$searchReference['f']);
				if (is_array($idList) && count($idList)) {
					$searchReference['i'] = $this->logicalAndArrayKeys($searchReference['i'],$idList);
					unset ($searchReference['f']);
				}

			}
			// t3lib_div::debug(Array('$table'=>$table, '$searchKey'=>$searchKey, '$searchReference'=>$searchReference, 'File:Line'=>__FILE__.':'.__LINE__));
	}

	protected function searchInFields($table,array &$searchReferenceFields) {
		$this->debugObj->debugIf('libsearch', Array('searchInFields'=>'', 'table='=>$table, '$searchReferenceFields'=>$searchReferenceFields, 'File:Line'=>__FILE__.':'.__LINE__));

		list($tables,$queries) = $this->getQueries($table,$searchReferenceFields);
		if (count($tables) && count($queries)) {
			$idList = tx_sgdiv::exec_SELECTgetField($table.'.uid',$table.'.uid',implode(',',$tables),implode(' AND ',$queries),'','','',0);
			if (!count($idList)) {
				$idList[0] = 0;
			}
		}

		$this->debugObj->debugIf('libsearch', Array('searchInFields'=>'', '$tables'=>$tables, '$queries'=>$queries, '$idList'=>$idList, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($idList); 
	}

	protected function getByIdList($table,$refKey,$idList) {
		$this->debugObj->debugIf('libsearch', Array('getByIdList'=>'', 'table='=>$table, '$refKey'=>$refKey, '$idList'=>$idList, 'File:Line'=>__FILE__.':'.__LINE__));
		$recordIds = tx_sgdiv::exec_SELECTgetField($table.'.uid',$table.'.uid',$table,     $table.'.'.$refKey.' IN ('.implode(',',$idList).')','','','',0);
		return ($recordIds);
	}

	protected function logicalAndArrayKeys($array1,$array2) {
		$result = array();

		if (!is_array($array1) && is_array($array2)) {
			return ($array2);
		} elseif (!is_array($array2) && is_array($array1)) {
			return ($array1);
		} else {
			foreach ($array1 as $key=>$value)
			if (isset($array2[$key])) {
				$result[$key] = $value;
			}
		}


		return ($result);
	}


	public function getQueries($table, array $searchFields, $tables=Array(), $queries=Array()) {

		foreach ($searchFields as $searchKey=>$searchField) {
			$conf = $this->confObj->get($table.'.conf.'.$searchKey.'.');
			$searchConf = $this->confObj->get($table.'.search.'.$searchKey.'.');

			if (is_array($searchField['v']) && count($searchField['v'])) {
				$values = Array();
				if ($conf['MM']) {
					foreach ($searchField['v'] as $key=>$tmp) if (intval($tmp)) {
						$values[$key] = intval($tmp);
					}
					if (count($values)) {
						$tables[$table] = $table;
						$tables[$conf['MM']] = $conf['MM'];
						$queries[] = $table.'.uid='.$conf['MM'].'.'.($conf['MM_opposite_field'] ? 'uid_foreign' : 'uid_local' );
						$queries[] = $conf['MM'].'.'.($conf['MM_opposite_field'] ? 'uid_local' : 'uid_foreign' ).' IN ('.(implode(',',$values)).')';
					}
				} else {
					foreach ($searchField['v'] as $key=>$tmp) {
						if ( ($this->confObj->isNumField($table,$searchKey) && intval($tmp)!=0) || ($this->confObj->isStringField($table,$searchKey) && strlen($tmp))) {
							$values[$key] = $this->confObj->secValueSql($table,$searchKey,$tmp);
						}
					}
					$subQueries = Array();
					foreach ($values as $key=>$value) {
						$subQueries[] = 'FIND_IN_SET('.$value.','.$table.'.'.$searchKey.')';
					}
					if (count($subQueries)) {
						$tables[$table] = $table;
						$queries[] = ' ('.implode(' OR ',$subQueries).') ';
					}
				}
				// t3lib_div::debug(Array('implode('.$searchKey.')'=>implode(',',$values), 'v'=>$searchField, 'File:Line'=>__FILE__.':'.__LINE__));
			} elseif (isset($searchField['v']) && strcmp($searchField['v'],'NULL')) {
				$value = $this->confObj->secValueSql($table,$searchKey,$searchField['v']);
				if ( ($this->confObj->isNumField($table,$searchKey) && ($value!=0 || strcmp($value,'00')==0)) || ($this->confObj->isStringField($table,$searchKey) && strlen($searchField['v']))) {
					$tables[$table] = $table;
					if ($conf['MM']) {
						$tables[$conf['MM']] = $conf['MM'];
						$queries[] = $table.'.uid='.$conf['MM'].'.'.($conf['MM_opposite_field'] ? 'uid_foreign' : 'uid_local' );
						$queries[] = $conf['MM'].'.'.($conf['MM_opposite_field'] ? 'uid_local' : 'uid_foreign' ).'='.intval($searchField['v']).'';
					} else {
						if ($this->confObj->isIntField($table,$searchKey)) {
							$queries[] = $table . '.' . $searchKey . '=' . intval($value);
						} else {
							$this->debugObj->debugIf('searchmodes',Array('$table'=>$table, '$searchKey'=>$searchKey, 'conf.field'=>$this->confObj[$table.'.']['conf.'][$searchKey.'.'], 'isIntListField'=>$this->confObj->isIntListField($table,$searchKey), 'FILE:LINE='=>__FILE__.':'.__LINE__ ));
							$queries[] = $this->getStringQuery($table, $searchKey, $searchField['v'], $searchConf, $this->confObj->isIntListField($table,$searchKey));
						}
					}
				}
			}

			if (is_array($searchField['i'])) {
				$values = Array();
				foreach ($searchField['i'] as $key=>$tmp) if (intval($tmp)) {
					$values[$key] = intval($tmp);
				}

				if ($conf['MM']) {
					if (count($values)) {
						$tables[$table] = $table;
						$tables[$conf['MM']] = $conf['MM'];
						$queries[] = $table.'.uid='.$conf['MM'].'.'.($conf['MM_opposite_field'] ? 'uid_foreign' : 'uid_local' );
						$queries[] = $conf['MM'].'.'.($conf['MM_opposite_field'] ? 'uid_local' : 'uid_foreign' ).' IN ('.(implode(',',$values)).')';
					} else {
						// NOTE: then there was no needed reference found !!!
						$queries[] = '1=0';
					}
				} else {
					if (count($values)) {
						$tables[$table] = $table;
						$queries[] = $table.'.'.$searchKey.' IN ('.(implode(',',$values)).')';
					}
				}
			}

			if (isset($searchField['from']) || isset($searchField['to'])) {
				$from = $this->confObj->secValueSql($table,$searchKey,$searchField['from']);
				$to = $this->confObj->secValueSql($table,$searchKey,$searchField['to']);
				if (!$conf['MM']) {
					$tables[$table] = $table;
					if ( ($this->confObj->isNumField($table,$searchKey) && $from!=0) || ($this->confObj->isStringField($table,$searchKey) && strlen($searchField['from']))) {
						$queries[] = $table.'.'.$searchKey.'>='.$from.'';
					}
					if ( ($this->confObj->isNumField($table,$searchKey) && $to!=0) || ($this->confObj->isStringField($table,$searchKey) && strlen($searchField['to']))) {
						$queries[] = $table.'.'.$searchKey.'<='.$to.'';
					}
				}
			}

		}

		return (Array($tables,$queries));
	}

	public function getStringQuery($table, $searchKey, $value, $searchConf, $listMode=FALSE) {
		$valueList = t3lib_div::trimExplode(' ', $value);
		$queries = Array();
		$fieldlist = strlen($searchConf['fields']) ? t3lib_div::trimExplode(',', $searchConf['fields']) : array($searchKey);
		foreach ($fieldlist as $fieldname) {
			$tmp = Array();
			foreach ($valueList as $key=>$value) {
				if ($searchConf['wordOnly']) {
					$value = str_replace (Array('*','?'), Array('.*', '.?'), $value);
					$value1 = $this->confObj->secValueSql($table,$searchKey,'^'.$value.'[[:punct:],[:space:]]');
					$value2 = $this->confObj->secValueSql($table,$searchKey,'[[:punct:],[:space:]]'.$value.'[[:punct:],[:space:]]');
					$value3 = $this->confObj->secValueSql($table,$searchKey,'[[:punct:],[:space:]]'.$value.'$');
					$tmp[] = '('.$table.'.'.$fieldname.' REGEXP '.$value1 . ' OR ' . $table.'.'.$fieldname.' REGEXP '.$value2 . ' OR ' . $table.'.'.$fieldname.' REGEXP '.$value3. ')';
				} elseif ($searchConf['wordExact']) {
					$value = str_replace (Array('*','?'), Array('%', '_'), $value);
					$value = $this->confObj->secValueSql($table,$searchKey,$value);
					$tmp[] = $table.'.'.$fieldname.' LIKE '.$value.'';
				} else {
					$this->debugObj->debugIf('searchmodes',Array('$table'=>$table, '$searchKey'=>$searchKey, '$listMode'=>$listMode, 'FILE:LINE='=>__FILE__.':'.__LINE__ ));
					if ($listMode) { 
						$value = intval($value);
						$tmp[] = 'FIND_IN_SET('.$value.','.$table.'.'.$fieldname.')';
					} else {
						$value = str_replace (Array('*','?'), Array('%', '_'), $value);
						$value = $this->confObj->secValueSql($table,$searchKey,'%'.$value.'%');
						$tmp[] = $table.'.'.$fieldname.' LIKE '.$value.'';
					}
				}
			}
			$queries[] = '(' . implode (' AND ', $tmp) . ')';
		}
		// t3lib_div::debug(Array('$queries'=>$queries, 'File:Line'=>__FILE__.':'.__LINE__));

		return ('(' . implode (' OR ', $queries) . ')');
	}

	/***********************************************************************************************
	 *
	 * Magic Methods
	 *
	 ***********************************************************************************************/

	public function __call ($name, array $arguments=Array()) {
		t3lib_div::debug(Array('ERROR'=>'Function "$name" not implemented', 'Class'=>get_class($this), 'File:Line'=>__FILE__.':'.__LINE__));
		return ('ERROR: method "'.get_class($this).'->'.$name.'(...)" does not exist. ');
	}


}


?>