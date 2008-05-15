<?php
/**
 *
 * PHP versions 5
 *
 *  (c) 2007-2008 Stefan Geith (typo3devYYYY@geithware.de)
 *
 * LICENSE:
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 * *
 * @package    TYPO3
 * @subpackage sg_lib
 * @author     Stefan Geith (typo3devYYYY@geithware.de)
 * @copyright  2008 Stefan Geith
 * @license    http://www.opensource.org/licenses/lgpl-license.php LGPL
 *
 * -----------------------------------------------------------------------
 *
 * On Creation of this Object, we need the following Data:
 * - Information, if parent is cached (=1) or not
 * - plugin.tx_myplugin.tx_mytable - Information
 *
 * Then we need optional information:
 * - Search-Parameters (default empty)
 * - Order-Parameters (default empty)
 * - Group-Parameters (default empty)
 * - Pager-Parameters: maxPP (default 10000), pg (default 1)
 * - Should we enable detailsbrowser (only when uncached)
 * - Should we list *all*, if no searchquery is given (default no)
 * If we are uncached, then check this:
 * - Do we have to perform any hide/unhide queries
 * - Do we have to perform any delete-queries
 * - Any other operations on the table ?
 *
 * Then we can perform the Search
 *
 * Then we need to provide the folowing Information:
 * - Total count of results
 * - Count of result on active Page (pager)
 * - The Row-Headers (= Names of the fields)
 * - The Result-List (= data object)
 * - Objects for all (used) references
 *
 */



/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  115: class tx_sglib_modelbase extends tx_sglib_data
 *  143:     function __construct ($designator, $factoryObj, $cached)
 *  163:     protected function init()
 *
 *              SECTION: Setters
 *  179:     function showAllIfEmptySearch($mode)
 *  190:     function setActivePage($value)
 *  203:     function setResultsPerPage($value)
 *  216:     function setSearchMode($mode)
 *  227:     function setSearchParams($params)
 *  238:     function setListMode($params)
 *  250:     function setWhereRestrict($params)
 *  261:     function allowFrom(array $paramsArray)
 *  283:     function clearExtraParams($params)
 *
 *              SECTION: Main Functions
 *  301:     function readReferenceTables($tables,$mode='')
 *  323:     protected function readTable($table,$data=NULL,$mode='',$where='1=1')
 *  379:     function getRefItems($table,$field,$em,$row)
 *  426:     function performSearch()
 *  487:     protected function readResultList($res,$data)
 *  500:     protected function clearResult()
 *
 *              SECTION: Getters
 *  516:     public function getResult()
 *  529:     public function getSingleRecord($uid=-1)
 *  557:     public function getUid($uid=-1)
 *  570:     public function getDescriptions($table='')
 *  600:     public function __get($nm)
 *
 *              SECTION: Query Generation Functions
 *  632:     protected function createWhere($table='',$excludeFields='')
 *  697:     protected function createWhereFromParams($q=array(), $table='',$excludeFields='')
 *  760:     function getDbBuildSingleQuery ($table,$key,$text)
 *  891:     protected function createOrder($table='')
 *  915:     protected function createAddSelect()
 *  930:     protected function createGroup()
 *  946:     protected function getLabelField($table)
 *
 *              SECTION: Functions for References
 *  968:     protected function includeSortingReferences(&$q)
 *
 *              SECTION: Low Level Functions
 * 1003:     function defaultTable($field)
 *
 * TOTAL FUNCTIONS: 31
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_modelbase extends tx_sglib_data {
	protected $designator;
	protected $factoryObj;
	protected $confObj;
	protected $debugObj;
	protected $cObj;

	protected $mainTable;
	protected $mainConf;
	protected $cached;

	protected $flagShowAllIfEmptySearch;
	protected $resultsPerPage = 10000;
	protected $activePage = 1;
	protected $searchMode = 0;
	protected $searchParams = Array();
	protected $listMode;
	protected $myWhereRestrict;
	protected $globalAllowed = false;
	protected $storagePageAllowed = true;
	protected $samePageAllowed = false;
	protected $anyPageAllowed = false;

	protected $resultData = NULL;
	protected $resultParams = Array();
	protected $refData;
	protected $refItems;

	function __construct ($designator, $factoryObj, $cached) {
		$this->designator = $designator;
		$this->factoryObj = $factoryObj;
		$this->confObj = $factoryObj->confObj;
		$this->debugObj = $factoryObj->debugObj;
		$this->paramsObj = $factoryObj->paramsObj;
		$this->cObj = $factoryObj->cObj;

		$this->mainTable = $this->confObj->getTCAname();
		$this->cached = $cached;

		$this->init();

	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function init() {
		$this->searchParams = $this->paramsObj->getSearch();
	}

	/***********************************************************************************************
	 *
	 * Setters
	 *
	 ***********************************************************************************************/

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$mode: ...
	 * @return	[type]		...
	 */
	function showAllIfEmptySearch($mode) {
		$this->flagShowAllIfEmptySearch = $mode;
		$this->clearResult();
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$value: ...
	 * @return	[type]		...
	 */
	function setActivePage($value) {
		if ($value>0) {
			$this->activePage = $value;
			$this->clearResult();
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$value: ...
	 * @return	[type]		...
	 */
	function setResultsPerPage($value) {
		if ($value>0) {
			$this->resultsPerPage = $value;
			$this->clearResult();
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$mode: ...
	 * @return	[type]		...
	 */
	function setSearchMode($mode) {
		$this->searchMode = $mode;
		$this->clearResult();
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$params: ...
	 * @return	[type]		...
	 */
	function setSearchParams($params) {
		$this->searchParams = is_array($params) ? $params : Array();
		$this->clearResult();
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$params: ...
	 * @return	[type]		...
	 */
	function setListMode($params) {
		$this->listMode = $params;
		$this->clearResult();
	}


	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$params: ...
	 * @return	[type]		...
	 */
	function setWhereRestrict($params) {
		$this->myWhereRestrict = $params;
		$this->clearResult();
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$array $paramsArray: ...
	 * @return	[type]		...
	 */
	function allowFrom(array $paramsArray) {
		if (isset($paramsArray['globalPage'])) {
			$this->globalAllowed = $paramsArray['globalPage'];
		}
		if (isset($paramsArray['storagePage'])) {
			$this->storagePageAllowed = $paramsArray['storagePage'];
		}
		if (isset($paramsArray['samePage'])) {
			$this->samePageAllowed = $paramsArray['samePage'];
		}
		if (isset($paramsArray['anyPage'])) {
			$this->anyPageAllowed = $paramsArray['anyPage'];
		}
	}


	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$params: ...
	 * @return	[type]		...
	 */
	function clearExtraParams($params) {
		$this->myWhereRestrict = NULL;
		$this->clearResult();
	}

	/***********************************************************************************************
	 *
	 * Main Functions
	 *
	 ***********************************************************************************************/

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$tables: ...
	 * @param	[type]		$mode: ...
	 * @return	[type]		...
	 */
	function readReferenceTables($tables,$mode='') {
		if (strcmp($tables,'*')==0) {
			$tables = $this->confObj->references['table'];
		} else if (!is_array($tables)) {
			$tables = t3lib_div::trimExplode(',',$tables);
		}

		foreach ($tables as $table) {
			$this->refData[$table] = $this->readTable($table,NULL,$mode);
		}
		// t3lib_div::debug(Array('$this->references'=>$this->references, 'File:Line'=>__FILE__.':'.__LINE__));
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$mode: ...
	 * @param	[type]		$mode: ...
	 * @param	[type]		$where: ...
	 * @return	[type]		...
	 */
	protected function readTable($table,$data=NULL,$mode='',$where='1=1') {
		$data = (isset($data)) ? $data : $this->factoryObj->getData();

		if (strcmp($mode,'*')==0) {
			$q['select'] = $table.'.*';
			$q['table'] = $table.' LEFT JOIN '.$this->mainTable.' ON '.$this->mainTable.'.'.$this->confObj->references['field'][$table].'='.$table.'.uid  ';
			$q['where'] = $this->mainTable.'.uid>0 AND '.$where.$this->cObj->enableFields($table);
			$q['order'] = $this->createOrder($table);
			$q['group'] = '';
			$q['limit'] = '';
		} else if (strlen($mode)>=2) {
			$searchType = explode(',',$this->confObj->mainSearch[$mode.'.']['type']);
			$q['select'] = $table.'.*';
			$q['table'] = $table.' LEFT JOIN '.$this->mainTable.' ON '.$this->mainTable.'.'.$this->confObj->references['field'][$table].'='.$table.'.uid  ';
			$q['where'] = $this->mainTable.'.uid>0 AND '.$where.$this->cObj->enableFields($table);
			if (is_array($this->searchParams))foreach ($this->searchParams as $key=>$value) {
				if (strcmp($key,$mode) && $value) {
					if (strcmp($searchType[1],'*')==0 || in_array($key,$searchType)) {
						$q['where'] .= ' AND '.$this->mainTable.'.'.$key.'='.$value.' ';
					}
				}
			}
			$q['order'] = $this->createOrder($table);
			$q['group'] = '';
			$q['limit'] = '';
		} else {
			$q['select'] = $table.'.*';
			$q['table'] = $table;
			$q['where'] = $where.$this->cObj->enableFields($table);
			$q['order'] = $this->createOrder($table);
			$q['group'] = '';
			$q['limit'] = '';
		}

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($q['select'], $q['table'], $q['where'], $q['group'], $q['order'], $q['limit']);
		$myError = $GLOBALS['TYPO3_DB']->sql_error();
		if ($myError) {
			t3lib_div::debug(Array('$q'=>$q, '$myError'=>$myError, 'File:Line'=>__FILE__.':'.__LINE__));
		} else {
			$cnt = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
			$this->debugObj->debugIf('sql',Array('query'=>$q, 'res /  count'=>$res.' / '.$cnt, 'FILE:LINE='=>__FILE__.':'.__LINE__ ));
			// $this->debugObj->debug(Array('$q'=>$q, 'res /  count'=>$res.' / '.$cnt, 'File:Line'=>__FILE__.':'.__LINE__ ));
			// read result to data
			$this->readResultList($res,$data);
		}

		//t3lib_div::debug(Array('read table = '.$table=>'Mode="'.$mode.'"', '$q'=>$q, '$data'=>$data, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($data);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$em: ...
	 * @param	[type]		$row: ...
	 * @return	[type]		...
	 */
	function getRefItems($table,$field,$em,$row) {

		if ($em<=SGZLIB_SEARCHALL) {
			$myName = $table.'.'.$field.'.all';
			$myMode = '';
		} else if ($em==SGZLIB_SEARCHUSED) {
			$myName = $table.'.'.$field.'.used';
			$myMode = '*';
		} else  {
			$myName = $table.'.'.$field.'.search';
			$myMode = $field;
		}

		if (!isset($this->refItems[$myName])) {
			$data = $this->factoryObj->getData();
			$tmp = $this->confObj[$table.'.']['search.'][$field.'.']['preItems.'];
			if ($em>=SGZLIB_SEARCHALL && is_array($tmp)) {
				foreach ($tmp as $key=>$value) {
					$xy = $this->cObj->cObjGetSingle($value['text'],$value['text.']) ;

					$text = $this->confObj->TSObj($value['text'],$value['text.']);
					$data[intval($value['id'])] = Array('title'=>$text, 'short'=>$text, 'text'=>$text, 'uid'=>$value['id']);
				}
				// Add PreItems
			}

		    $data = $this->readTable($this->confObj->references['table'][$field],$data,$myMode);

			$tmp = $this->confObj[$table.'.']['search.'][$field.'.']['postItems.'];
			if ($em>=SGZLIB_SEARCHALL && is_array($tmp)) {
				// Add PostItems
				foreach ($tmp as $key=>$value) {
					$data[intval($value['id'])] = $value['text'];
				}
			}
			$this->refItems[$myName] = $data;
		}

		// t3lib_div::debug(Array('Read Items for'=>$myName, '$em'=>$em, '$row'=>$row, '$data'=>$this->refItems[$myName], 'File:Line'=>__FILE__.':'.__LINE__));
		return ($this->refItems[$myName]);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function performSearch() {
		$this->clearResult();
		$this->resultData = $this->factoryObj->getData();
		$q['select'] = $this->defaultTable('*').$this->createAddSelect();
		$q['UIDonly'] = $this->defaultTable('uid');
		$q['table'] = $this->mainTable.' '.implode(' ',$this->confObj->references['join']);

		$q['where'] = $this->createWhere();
		$q['order'] = $this->createOrder();
		$q['group'] = $this->createGroup();
		$q['limit'] = '';

		$this->includeSortingReferences($q);

		// perform search
		$this->resultParams = Array('query'=>$q);
		// t3lib_div::debug(Array('$q'=>$q, 'File:Line'=>__FILE__.':'.__LINE__)); return;

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($q['UIDonly'], $q['table'], $q['where'], $q['group'], $q['order']);
		// save total-count
		$myError = $GLOBALS['TYPO3_DB']->sql_error();
		if ($myError) {
			$this->resultParams['ERROR'] = $myError;
			$this->resultParams['total']=0;
			$this->resultParams['cnt']=0;
			t3lib_div::debug(Array('$q'=>$q, '$myError'=>$myError, 'File:Line'=>__FILE__.':'.__LINE__));
		} else {
			$this->resultParams['total'] = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
			$this->debugObj->debugIf('sql',Array('query'=>$q, 'res /  count'=>$res.' / '.$this->resultParams['total'], 'FILE:LINE='=>__FILE__.':'.__LINE__ ));
			$GLOBALS['TYPO3_DB']->sql_free_result($res);
		}

		$q['limit'] = (($this->activePage>0 ? $this->activePage-1 : 0) * $this->resultsPerPage).','.$this->resultsPerPage;
		// perform search
		$this->resultParams['query'] = $q;
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($q['select'], $q['table'], $q['where'], $q['group'], $q['order'], $q['limit']);
		$myError = $GLOBALS['TYPO3_DB']->sql_error();
		if ($myError) {
			$this->resultParams['ERROR'] = $myError;
			$this->resultParams['total']=0;
			$this->resultParams['cnt']=0;
			t3lib_div::debug(Array('$q'=>$q, '$myError'=>$myError, 'File:Line'=>__FILE__.':'.__LINE__));
		} else {
			$cnt = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
			$this->resultParams['cnt']=$cnt;
			$this->resultParams['res']=$res;
			$this->debugObj->debugIf('sql',Array('query'=>$q, 'res /  count'=>$res.' / '.$this->resultParams['total'], 'FILE:LINE='=>__FILE__.':'.__LINE__ ));
			// $this->debugObj->debug(Array('result'=>$this->resultParams, 'File:Line'=>__FILE__.':'.__LINE__ ));
			// read result to data
			$this->readResultList($res,$this->resultData);
		}
		return ($this->resultParams);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$data: ...
	 * @param	[type]		$data: ...
	 * @return	[type]		...
	 */
	protected function readResultList($res,$data) {
		if ($res) {
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$data[$row['uid']] = $row;
			}
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function clearResult() {
		unset ($this->resultData);
		$this->resultParams = Array();
	}

	/***********************************************************************************************
	 *
	 * Getters
	 *
	 ***********************************************************************************************/

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	public function getResult() {
		if (!isset($this->resultData)) {
			$this->performSearch();
		}
		return ($this->resultData);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$uid: ...
	 * @return	[type]		...
	 */
	public function getSingleRecord($uid=-1) {
		$record = NULL;
		$uid = $this->getUid($uid);

		$q['select'] = $this->mainTable.'.*';
		$q['table'] = $this->mainTable;
		$q['where'] = $this->mainTable.'.uid='.$uid.$this->cObj->enableFields($this->mainTable);
		$q['order'] = '';
		$q['group'] = '';
		$q['limit'] = '';

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($q['select'], $q['table'], $q['where'], $q['group'], $q['order'], $q['limit']);
		if ($res) {
			$cnt = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
			if ($cnt) {
				$record = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			}
		}

		return ($record);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$uid: ...
	 * @return	[type]		...
	 */
	public function getUid($uid=-1) {
		if ($uid<0) {
			$uid=$this->paramsObj->getUid();
		}
		return (intval($uid));
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @return	[type]		...
	 */
	public function getDescriptions($table='') {
		$descriptions = Array();
		if (!$table) {
			$table = $this->mainTable;
		}
		$descriptions['MAINTABLE'] = $table;
		$descriptions['uid'] = 'UID';
		$descriptions['hidden'] = $this->confObj->text['hidden'];
		$descriptions['disabled'] = $this->confObj->text['disabled'];

		$tmp = $this->confObj->$table;
		if (is_array($tmp['conf.'])) foreach ($tmp['conf.'] as $key=>$fieldConf) {
			$fieldName = substr($key,0,-1);
			$descriptions[$fieldName] = $fieldConf['label'];
			if (isset($fieldConf['label.'])) {
				$descriptions[$fieldName] = array('name'=>$fieldConf['label'],'conf'=>$fieldConf['label.']);
			} else {
				$descriptions[$fieldName] = $fieldConf['label'];
			}
		}

		return ($descriptions);
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
			case 'refData':
			case 'refdata':
				return ($this->refData);
			case 'data':
				return ($this->getResult());
			case 'totalCount':
				return ($this->resultParams['total']);
			default:
				$error = 'get("'.$nm.'") failed ... Variable unknown !!';
				$this->debugObj->showError(0,$error,0,'','',1);
				return ('');
		}
		return ('');
    }


	/***********************************************************************************************
	 *
	 * Query Generation Functions
	 *
	 ***********************************************************************************************/

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$excludeFields: ...
	 * @return	[type]		...
	 */
	protected function createWhere($table='',$excludeFields='') {
		$table = $table ? $table : $this->mainTable;
		$q = Array();

//		// first: always set default where-clause (set in TS) (if any)
//		if (isset($PCA['ctrl']['defaultWhere'])) {
//			$q['defaultWhere'] = $this->replaceArray($this->cObj->insertData(
//				str_replace('###val###',trim($PCA['ctrl']['defaultWhereVal']),
//				   str_replace('###feuser_id###',$this->permitObj->getFeUid(),$PCA['ctrl']['defaultWhere']))
//				) , $this->globalReplace);
//		}
//
//		// second: always set default where-clause from listmode, set in TS (if any)
//		if (isset($PCA['listmode'][$piVarSearch['listmode']])) {
//			if (isset($PCA['listmode'][$piVarSearch['listmode']]['where'])) {
//				$q['listmode'] = $this->cObj->insertData($PCA['listmode'][$piVarSearch['listmode']]['where']);
//			}
//		}

		if (!$this->anyPageAllowed) {
			$pidQuery = Array();
			if ($this->globalAllowed) {
				$pidQuery[] = $table.'.pid=0';
			}
			if ($this->storagePageAllowed) {
				if ($this->confObj->getPidList()) {
					$pidQuery[] = $table.'.pid IN ('.$this->confObj->getPidList().')';
				} else {
					$pidQuery[] = $table.'.pid>=0';
				}
			}
			if ($this->samePageAllowed) {
				$pidQuery[] = $table.'.pid='.intval($GLOBALS['TSFE']->id);
			}
			$q['pid'] = '('.implode(' OR ',$pidQuery).')';
		}

		// third: set all defined queries
		if (strcmp($excludeFields,'*')) {
			$q = $this->createWhereFromParams($q,$table,$excludeFields);
		}

		// fourth: do another restrict
		if ($this->myWhereRestrict) {
			$q['restrict'] = $this->myWhereRestrict;
		}

		// last: if no query yet: set 'find all'
		$where = implode (' AND ',$q);
		$where = ' ( '.($where ? $where : '1=1').' ) ';


		$where .= $this->cObj->enableFields($this->mainTable);
		// t3lib_div::debug(Array('$where'=>$where, '$q'=>$q, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($where);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$q: ...
	 * @param	[type]		$table: ...
	 * @param	[type]		$excludeFields: ...
	 * @return	[type]		...
	 */
	protected function createWhereFromParams($q=array(), $table='',$excludeFields='') {
		$table = $table ? $table : $this->mainTable;
		if (is_array($this->searchParams)) for (reset($this->searchParams);$key=key($this->searchParams);next($this->searchParams)) {
			if (strcmp($key,'listmode')==0) {
//			} else if (strcmp($key,$PCA['ctrl']['enablecolumns']['disabled'])==0) {
//			} else if (strncmp($key,'restrict_',9)==0) {
//				if (strlen($piVarSearch[$key])>0) {
//					$q['restrict'] = $table.'.'.substr($key,9).' IN ('.$piVarSearch[$key].')';
//				} else {
//					$q['restrict'] = '1=2 ';
//				}
//			} else if (strcmp($key,'idlist')==0) {
//				if (strlen($piVarSearch[$key])>0) {
//					$q['idlist'] = $table.'.uid IN ('.$piVarSearch[$key].')';
//				} else {
//					$q['idlist'] = '1=2 ';
//				}
//			} else if (strcmp($key,'abc')==0) {
//				...
			} else {
				// Warning : $this->searchParams[$key] may be an array !!!
				$doit = true;
				$searchConfKey = $this->confObj[$table.'.']['search.'][$key.'.'];
				if (is_array($this->searchParams[$key])) {
					$tmp = Array();
					while (list ($sKey, $val) = each ($this->searchParams[$key])) {
					//no! this doesnt work with index 0,1,2) for (reset($this->searchParams[$key]);...;next($this->searchParams[$key])) {
						//$text = urldecode(trim($val));
						$text = $GLOBALS['TYPO3_DB']->quoteStr(trim($val),$table);
						$tmp[] =  '( '.implode(' AND ',$this->getDbBuildSingleQuery($table,$key,$text)).' )';
					}
					if (count($tmp)) {
						$q['searches_'.$key] = '( '.implode(' OR ',$tmp).' )';
					}
				} else {
					$text = $GLOBALS['TYPO3_DB']->quoteStr(urldecode(trim($this->searchParams[$key])),$table);
					if (intval($searchConfKey['searchZero'])>0) {
						if (strcmp($text,'-1')==0) { $doit=FALSE; }
					} else {
						if (strcmp($text,'0')==0) { $doit=FALSE; }
					}
					if ($doit) {
						if (strlen($this->searchParams[$key])>0) {
							$q = array_merge ($q, $this->getDbBuildSingleQuery($table,$key,($text=="''" ? '' : $text)));
						}
					}
				}
			}


		}
		return ($q);
	}


	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$key: ...
	 * @param	[type]		$text: ...
	 * @return	[type]		...
	 */
	function getDbBuildSingleQuery ($table,$key,$text) {
		$searchConfKey = $this->confObj[$table.'.']['search.'][$key.'.'];
		$confKey = $this->confObj[$table.'.']['conf.'][$key.'.'];

		$q = Array();
		$specialMatch = false;
		if (is_array($searchConfKey['special'])) {
			for (reset($searchConfKey['special']);$sKey=key($searchConfKey['special']);next($searchConfKey['special'])) {
				if (strcmp($searchConfKey['special'][$sKey]['value'],$text)==0) {
					$q['special_'.$key] = $this->cObj->insertData(
						str_replace('###time###',time(),$searchConfKey['special'][$sKey]['query']));
					$specialMatch = true;
				}
			}
		}

		if (!$specialMatch) {
			if (is_array($confKey['foreign']) && strlen($confKey['foreign_table'])<1) {
				$myField = $confKey['foreign']['field'] ? $confKey['foreign']['field'] : $key;
				$ini = '(';
				if (strlen($confKey['foreign']['where'])>0) {
					$ini = '('.$this->cObj->insertData($confKey['foreign']['where']).' AND ';
				}
				if (strlen($text)>0 && strcmp($confKey['foreign']['mode'],'text')==0) {
					$myQ = $ini.$myField.' LIKE '.QT.addslashes(str_replace('*','%',$text)).QT.')';
				} else if ((strcasecmp($text,'null')==0) || intval($text)>0) {
					if (strcmp($confKey['mode'],'selectmulti')==0) {
						$ini.$myField.' IN ( '.addslashes($text).' ) '.')';
					} else {
						$myQ = $ini.$myField.( (strcasecmp($text,'null')==0) ? ' is NULL' : '='.intval($text)).')';
					}
				}
				$q['foreign_'.$key] = $myQ;
				//t3lib_div::debug(Array($key=>$confKey, '$myQ'=>$myQ, 'File:Line'=>__FILE__.':'.__LINE__));
			} else if (strlen($confKey['type'])<1 || $confKey['type']=='input' || $confKey['type']=='none' || $confKey['type']=='text') {
				// Field seems to be a text-type field
				$fieldList = explode(',',  (isset($searchConfKey['fields'])) ? $searchConfKey['fields'] : $table.'.'.$key ) ;
					for ($i=0;$i<count($fieldList);$i++) {
					if (strpos($fieldList[$i],'.')<1) {
						$fieldList[$i] = $table.'.'.$fieldList[$i];
					}
				}

				if (isset($searchConfKey['query'])) {
					$this->globalReplace['###val###'] = $text;
					$fieldList[0] = $this->replaceArray($this->cObj->insertData($searchConfKey['query']),$this->globalReplace);
					$fieldList[0] = str_replace('*','%',$fieldPrepend.$fieldList[0].$fieldAppend);
					$q['inputq_'.$key] = $fieldList[0];
				} else {
					// check if range is given
					$p = explode ('...',$text,2);
					if (count($p)>1) {
						for ($i=0;$i<count($fieldList);$i++) {
							$fieldList[$i] = ' ('.$fieldList[$i].' >= "'.$p[0].'" '.
											 ' AND '.$fieldList[$i].' <= "'.$p[1].'") ';
						}
						$q['inputs_'.$key] = '('.implode($fieldMode,$fieldList).')';
					} else {
						$fieldComp =   (isset($searchConfKey['comp'])) ? $searchConfKey['comp'] : 'LIKE'   ;
						$fieldMode =  (isset($searchConfKey['mode'])) ? $searchConfKey['mode'] : 'OR'  ;
						$fieldAppend =  (isset($searchConfKey['append'])) ? $searchConfKey['append'] : ''  ;
						$fieldPrepend =  (isset($searchConfKey['prepend'])) ? $searchConfKey['prepend'] : ''  ;
						for ($i=0;$i<count($fieldList);$i++) {
							$fieldList[$i] = ' '.$fieldList[$i].' '.$fieldComp.
												' "'.str_replace('*','%',$fieldPrepend.$text.$fieldAppend).'" ';
						}
						$q['input_'.$key] = '('.implode($fieldMode,$fieldList).')';
					}
				}
			} else if (substr($confKey['type'],0,4)=='date') {
				// Field is of type 'date'
				$fieldList = explode(',',  (isset($searchConfKey['fields'])) ? $searchConfKey['fields'] : $table.'.'.$key   ) ;
				$fieldComp =   (isset($searchConfKey['comp'])) ? $searchConfKey['comp'] : '='   ;
				$fieldMode =  (isset($searchConfKey['mode'])) ? $searchConfKey['mode'] : 'OR'  ;
				if (isset($searchConfKey['query'])) {
					$this->globalReplace['###val###'] = $text;
					$fieldList[0] = $this->replaceArray($this->cObj->insertData($searchConfKey['query']),$this->globalReplace);
					$fieldList[0] = str_replace('*','%',$fieldPrepend.$fieldList[0].$fieldAppend);
				} else {
					if (strcmp($text,'0')==0 || strcmp($text,'-')==0) {
						$fieldList[0] = ' '.$fieldList[0].'=0 ';
					} else {
						$fieldList[0] = ' ('.$this->dateCompareString($text,$fieldList[0]).') ';
					}
				}
				//t3lib_div::debug(Array('$myComp'=>$myComp, '$fieldList[0]'=>$fieldList[0], 'File:Line'=>__FILE__.':'.__LINE__));
				$q['date_'.$key] = $fieldList[0];
			} else if ($confKey['MM']) {
				$q['MM_'.$key] = ' ( '.$confKey['MM'].'.uid_foreign='.intval($text).') ';
				if ($searchConfKey['foreign']['subSearch']) {
					$this->itemsObj->prepareItems($table,$key,0,Array());
					if ($tmp = $this->itemsObj->getItemsSub('',$key,intval($text).'.')) {
						$q['MM_'.$key] = '('.$q['MM_'.$key].' OR '.$confKey['MM'].'.uid_foreign IN ('.$tmp.'))';
					}
				}
			} else if ($confKey['type']=='select' ||
					$confKey['type']=='radio' || $confKey['type']=='check' ||
					   $confKey['type']=='checklist' ||
					   $confKey['type']=='selectmulti' || $confKey['type']=='selectsingle') {
				$fieldList = explode(',',  (isset($searchConfKey['fields'])) ? $searchConfKey['fields'] : $table.'.'.$key   ) ;
				$fieldComp =   (isset($searchConfKey['comp'])) ? $searchConfKey['comp'] : '='   ;
				$fieldMode =  (isset($searchConfKey['mode'])) ? $searchConfKey['mode'] : 'OR'  ;
				$fieldAppend =  (isset($searchConfKey['append'])) ? $searchConfKey['append'] : ''  ;
				$fieldPrepend =  (isset($searchConfKey['prepend'])) ? $searchConfKey['prepend'] : ''  ;

				if (isset($searchConfKey['query'])) {
					$this->globalReplace['###val###'] = $text;
					$fieldList[0] = $this->replaceArray($this->cObj->insertData($searchConfKey['query']),$this->globalReplace);
					$fieldList[0] = str_replace('*','%',$fieldPrepend.$fieldList[0].$fieldAppend);
				} else {
					$fieldList[0] = ' '.$fieldList[0].$fieldComp.QT.str_replace('*','%',$fieldPrepend.$text.$fieldAppend).QT.' ';
				}
				if ($fieldList[0]) {
					$q['select_'.$key] = $fieldList[0];
				}
			}

		}

		//t3lib_div::debug(Array('$q('.$key.','.$text.')'=>$q, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($q);
	}



	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @return	[type]		...
	 */
	protected function createOrder($table='') {
		$order = '';

		if (!$table) {
			$order = ($this->confObj->list['listmode.'][$this->listMode.'.']['order']);
			$order = $order ? $order : ($this->confObj->listmode[$this->listMode.'.']['order']);
			$order = $order ? $order : ($this->confObj->mainCtrl['defaultOrder']);
		} else {
			$ctrl = $this->confObj[$table.'.']['ctrl.'];
			if (is_array($ctrl)) {
				$order = (strcmp(substr($ctrl['default_sortby'],0,9),'ORDER BY ')==0) ? substr($ctrl['default_sortby'],9) : '';
				$order = ($ctrl['sortby']) ? $ctrl['sortby'] : $order;
				$order = ($ctrl['defaultOrder']) ? $ctrl['defaultOrder'] : $order;
			}
		}

		return ($order);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function createAddSelect() {
		$addSelect = '';

		$addSelect = ($this->confObj->list['listmode.'][$this->listMode.'.']['addToSelect']);
		$addSelect = $addSelect ? $addSelect : ($this->confObj->listmode[$this->listMode.'.']['addToSelect']);
		$addSelect = $addSelect ? $addSelect : ($this->confObj->mainCtrl['addToSelect']);

		return ($addSelect);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function createGroup() {
		$group = '';

		$group = ($this->confObj->list['listmode.'][$this->listMode.'.']['group']);
		$group = $group ? $group : ($this->confObj->listmode[$this->listMode.'.']['group']);
		$group = $group ? $group : ($this->confObj->mainCtrl['defaultGroup']);

		return ($group);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @return	[type]		...
	 */
	protected function getLabelField($table) {
		$labelField = '';

		$labelField = $this->confObj[$table.'.']['ctrl.']['label'];
		$labelField = $labelField ? $labelField : 'uid';

		return ($labelField);
	}


	/***********************************************************************************************
	 *
	 * Functions for References
	 *
	 ***********************************************************************************************/

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$$q: ...
	 * @return	[type]		...
	 */
	protected function includeSortingReferences(&$q) {
		$order = Array();
		if ($q['where']) {
			$tmp = t3lib_div::trimExplode(',',$q['order']);
			foreach ($tmp as $orderPart) {
				if (strcmp(substr($orderPart,0,1),'(')==0) {
					$order[] = $orderPart;
				} else {
					$line = t3lib_div::trimExplode(' ', $orderPart,2);
					$field =  t3lib_div::trimExplode('.', $line[0],2);
					if (!$field[1]) {
						$field[1] = $field[0];
						$field[0] = $this->mainTable;
						$line[0] = $field[0].'.'.$field[1];
					}
					$order[] = implode(' ',$line);
				}
			}
		}
		$q['order'] = implode(', ',$order);
	}


	/***********************************************************************************************
	 *
	 * Low Level Functions
	 *
	 ***********************************************************************************************/

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$field: ...
	 * @return	[type]		...
	 */
	function defaultTable($field) {
		return( ((strpos($field,'.')>0 || !trim($field) ) ? $field : $this->mainTable.'.'.$field) );
	}


}

?>