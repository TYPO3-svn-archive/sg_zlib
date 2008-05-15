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
 *  109: class tx_sglib_modelbase extends tx_sglib_data
 *  131:     function __construct ($designator, $factoryObj, $cached)
 *  153:     protected function init()
 *
 *              SECTION: Setters
 *  169:     function showAllIfEmptySearch($mode)
 *  180:     function setActivePage($value)
 *  193:     function setResultsPerPage($value)
 *  206:     function setSearchMode($mode)
 *  217:     function setSearchParams($params)
 *  228:     function setListMode($params)
 *
 *              SECTION: Main Functions
 *  247:     function readReferenceTables($tables,$mode)
 *  267:     protected function readTable($table,$mode)
 *  298:     function performSearch()
 *  359:     protected function readResultList($res,$data)
 *  372:     protected function clearResult()
 *
 *              SECTION: Getters
 *  388:     public function getResult()
 *  401:     public function getDescriptions($table='')
 *  431:     public function __get($nm)
 *
 *              SECTION: Query Generation Functions
 *  460:     protected function createWhere()
 *  474:     protected function createWhereFromParams()
 *  486:     protected function createOrder($table='')
 *  510:     protected function createAddSelect()
 *  525:     protected function createGroup()
 *  541:     protected function getLabelField($table)
 *
 *              SECTION: Functions for References
 *  563:     protected function includeSortingReferences(&$q)
 *  590:     protected function findAllReferences()
 *
 *              SECTION: Low Level Functions
 *  628:     function defaultTable($field)
 *
 * TOTAL FUNCTIONS: 25
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_modelbase extends tx_sglib_data {
	protected $designator;
	protected $factoryObj;
	protected $configObj;
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

	protected $resultData = NULL;
	protected $resultParams = Array();
	protected $references;

	function __construct ($designator, $factoryObj, $cached) {
		$this->designator = $designator;
		$this->factoryObj = $factoryObj;
		$this->configObj = $factoryObj->configObj;
		$this->debugObj = $factoryObj->debugObj;
		$this->cObj = $factoryObj->cObj;

		$this->mainTable = $this->configObj->getTCAname();
		$this->mainConf = $this->configObj->get($this->mainTable.'.');
		$this->cached = $cached;

		$this->init();

		// t3lib_div::debug(Array('$designator'=>$designator, '$cached'=>$this->cached, '$mainTable'=>$this->mainTable, '$mainConf'=>$this->mainConf, 'File:Line'=>__FILE__.':'.__LINE__));

	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function init() {
		$this->references = $this->findAllReferences();
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
	function readReferenceTables($tables,$mode) {
		if (strcmp($tables,'*')==0) {
			$tables = $this->references['table'];
		} else if (!is_array($tables)) {
			$tables = t3lib_div::trimExplode(',',$tables);
		}

		foreach ($tables as $table) {
			$this->references['data'][$table] = $this->readTable($table,$mode);
		}
		// t3lib_div::debug(Array('$this->references'=>$this->references, 'File:Line'=>__FILE__.':'.__LINE__));
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$mode: ...
	 * @return	[type]		...
	 */
	protected function readTable($table,$mode) {
		$data = $this->factoryObj->getData();

		$q['select'] = $table.'.*';
		$q['table'] = $table;
		$q['where'] = '1=1'.$this->cObj->enableFields($table);
		$q['order'] = $this->createOrder($table);
		$q['group'] = '';
		$q['limit'] = '';

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

		return ($data);
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
		$q['table'] = $this->mainTable.' '.implode(' ',$this->references['join']);

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
		$descriptions['hidden'] = $this->configObj->get('text.hidden');
		$descriptions['disabled'] = $this->configObj->get('text.disabled');

		$tmp = $this->configObj->get($table.'.conf.');
		if (is_array($tmp)) foreach ($tmp as $key=>$fieldConf) {
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
			case 'references':
				return ($this->references);
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
	 * @return	[type]		...
	 */
	protected function createWhere() {
		$where = $this->createWhereFromParams();
		$where = $where ? $where : '1=1';

		$where .= $this->cObj->enableFields($this->mainTable);

		return ($where);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function createWhereFromParams() {
		$where = '';

		return ($where);
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
			$order = ($this->configObj->get('list.listmode.'.$this->listMode.'.order'));
			$order = $order ? $order : ($this->configObj->get('listmode.'.$this->listMode.'.order'));
			$order = $order ? $order : ($this->configObj->getTbl('ctrl.defaultOrder'));
		} else {
			$ctrl = $this->configObj->get($table.'.ctrl.');
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

		$addSelect = ($this->configObj->get('list.listmode.'.$this->listMode.'.addToSelect'));
		$addSelect = $addSelect ? $addSelect : ($this->configObj->get('listmode.'.$this->listMode.'.addToSelect'));
		$addSelect = $addSelect ? $addSelect : ($this->configObj->getTbl('ctrl.addToSelect'));

		return ($addSelect);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function createGroup() {
		$group = '';

		$group = ($this->configObj->get('list.listmode.'.$this->listMode.'.group'));
		$group = $group ? $group : ($this->configObj->get('listmode.'.$this->listMode.'.group'));
		$group = $group ? $group : ($this->configObj->getTbl('ctrl.defaultGroup'));

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

		$labelField = $this->configObj->get($table.'.ctrl.label');
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

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function findAllReferences() {
		$references = Array('table'=>array(), 'field'=>array(), 'asName'=>array(), 'join'=>array(), 'label'=>array(), 'data'=>array());
		foreach ($this->mainConf['conf.'] as $key=>$fieldConf) {
			$fieldName = substr($key,0,-1);
			$table = '';
			if ($fieldConf['allowed'] && strcmp($fieldConf['internal_type'],'db')==0) {
				$table = $fieldConf['allowed'];
			} else if ($fieldConf['foreign_table']) {
				$table = $fieldConf['foreign_table'];
			}
			if ($table) {
				$references['table'][$fieldName] = $table;
				$references['field'][$table] = $fieldName;
				$references['asName'][$table] = $fieldName.'#ref';
				$references['join'][$table] = ' LEFT JOIN '.$table.' ON '.$this->mainTable.'.'.$fieldName.'='.$table.'.uid  ';
				$references['label'][$table] = $this->getLabelField($table);
			}

			if (strcmp($fieldConf['allowed'],$field[0])==0 || strcmp($fieldConf['foreign_table'],$field[0])==0) {
				$retVal = ' LEFT JOIN '.$field[0].' ON '.$this->mainTable.'.'.$fieldName.'='.$field[0].'.uid  ';
			}
		}
		// t3lib_div::debug(Array('$references'=>$references, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($references);
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