<?php



class tx_sgzlib_tcaObjectAccessor {

    /**
     * Select object data by its uid
     *
     * @param 	int		uid
     * @param	string	table
     * @param	bool	(optional) ignore enable fields
     * @return 	array	dataArray
     * @throws  tx_pttools_exception	if query fails
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     */
    public static function selectByUid($uid, $table, $ignoreEnableFields = false){
        // query preparation
        $select  = '*';
        $from    = $table;
        $where   = 'uid = '.intval($uid);
        if (!$ignoreEnableFields) $where .= ' '.$GLOBALS['TSFE']->cObj->enableFields($from);
        $groupBy = '';
        $orderBy = '';
        $limit   = '';

        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1, $GLOBALS['TYPO3_DB']->sql_error());
        }
        $a_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $GLOBALS['TYPO3_DB']->sql_free_result($res);

        trace($a_row);
        return $a_row;
    }



    /**
     * Selects object data by parent uid
     *
     * @param 	int		parent uid
     * @param 	string	foreign table
     * @param 	string	foreign field
     * @param 	string	foreign sortby
     * @param 	string	(optional) additional where clause
     * @return 	array	array of dataArrays
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since	2008-03-16
     */
    public static function selectByParentUid($parentUid, $foreign_table, $foreign_field, $foreign_sortby, $additionalWhere = ''){
        // query preparation
        $select  = '*';
        $from    = $foreign_table;
        $where   = $foreign_field .' = '.intval($parentUid);
        $where .= ' '.$GLOBALS['TSFE']->cObj->enableFields($from);
        if (!empty($additionalWhere)) {
        	$where .= ' AND '.$additionalWhere;
        }

        $groupBy = '';
        $orderBy = $foreign_sortby;
        $limit   = '';

        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1, $GLOBALS['TYPO3_DB']->sql_error());
        }
        $rows = array();
        while (($a_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) == true) {
            $rows[] = $a_row;
        }
        $GLOBALS['TYPO3_DB']->sql_free_result($res);

        return $rows;
    }



    /**
     * Select collection
     *
     * @param 	string	table
     * @param 	string	(optional) where
     * @param 	string	(optional) limit
     * @param 	string	(optional) orderBy
     * @param	bool	(opiotnal) ignore enable fields, default: false
     * @return	array 	array of data arrays
     * @author 	Fabrizio Branca <mail@fabrizio-branca.de>
     */
    public function selectCollection($table, $where = '', $limit = '', $orderBy = '', $ignoreEnableFields = false) {
        // query preparation
        $select  = '*';
        $from    = $table;
        $where   = ($where != '') ? $where : '1';
        if (!$ignoreEnableFields) {
            $where .= ' '.$GLOBALS['TSFE']->cObj->enableFields($from);
        }
        $groupBy = '';
        // $orderBy = '';
        // $limit   = '';

        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1, $GLOBALS['TYPO3_DB']->sql_error());
        }
        $rows = array();
        while (($a_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) == true) {
            $rows[] = $a_row;
        }
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
        return $rows;
    }
    
    
    
    /**
     * Count collection items 
     *
     * @param 	string	table
     * @param 	string	(optional) where
     * @param	bool	(optional) ignore enable fields, default: false
     * @return	array 	array of data arrays
     * @author 	Fabrizio Branca <mail@fabrizio-branca.de>
     */
    public function selectCollectionCount($table, $where = '', $ignoreEnableFields = false) {
    	
        // query preparation
        $select  = 'count(*) as quantity';
        $from    = $table;
        $where   = ($where != '') ? $where : '1';
        if (!$ignoreEnableFields) {
            $where .= ' '.$GLOBALS['TSFE']->cObj->enableFields($from);
        }
        $groupBy = '';
        $orderBy = '';
        $limit   = '';

        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1, $GLOBALS['TYPO3_DB']->sql_error());
        }
        
        $a_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $GLOBALS['TYPO3_DB']->sql_free_result($res);

        return $a_row['quantity'];
    }



    /**
     * Stores record to an table
     *
     * @param 	string	table
     * @param 	array	dataArray
     * @return 	int		uid of the record
     */
    public function store($table, array $dataArray) {
        if (isset($dataArray['uid'])) {
            // not possible to change these values when updating (reason: policy, not technical!)
            unset($dataArray['crdate']);
            unset($dataArray['cruser_id']);
            // unset($dataArray['pid']); uncommenting allows moving record by changing the pid and storing the record

            return self::update($table, $dataArray);
        } else {
            return self::insert($table, $dataArray);
        }
    }



    /**
     * Insert a new record
     *
     * @param 	string	table
     * @param 	array 	data
     * @return 	int		uid of the new record
     * @throws	tx_pttools_exception	if insert fails
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     */
    public function insert ($table, array $insertFieldsArr) {
        // echo "inserting";
        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery($table, $insertFieldsArr);
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1, $GLOBALS['TYPO3_DB']->sql_error());
        }
        $lastInsertedId = $GLOBALS['TYPO3_DB']->sql_insert_id();

        trace($lastInsertedId);
        return $lastInsertedId;
    }



    /**
     * Delete record
     *
     * @param 	string	table
     * @param 	int		uid
     * @return 	void
     * @author 	Fabrizio Branca <mail@fabrizio-branca.de>
     */
    public function delete ($table, $uid) {
        self::deleteWhere($table, 'uid = '.intval($uid));
    }



    /**
     * ATTENTION: If ($where==true) the whole table will be deleted!
     *
     * @param 	string	table
     * @param 	string	where
     */
    public function deleteWhere($table, $where) {
        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_DELETEquery($table, $where);
        
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1, $GLOBALS['TYPO3_DB']->sql_error());
        }
    }


    /**
     * Updates an existing record
     *
     * @param 	string	table
     * @param 	array 	data (data['uid'] contains the uid of the record to update)
     * @return 	int		uid of the updated record
     * @throws	tx_pttools_exception if uid is empty
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     */
    public function update ($table, array $updateFieldsArr) {

        if (empty($updateFieldsArr['uid'])) {
            throw new tx_pttools_exception('No uid set (needed for updating the record)!');
        }

        $where = 'uid = '.intval($updateFieldsArr['uid']);

        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery($table, $where, $updateFieldsArr);
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1, $GLOBALS['TYPO3_DB']->sql_error());
        }

        trace($res);
        return $updateFieldsArr['uid'];
    }
    
    
    
    public static function selectMoveOneUpPosition($table, $pid, $uid, $sortingFieldName = 'sorting') {
		// query preparation
        $select  = 'b.uid as position';
        $from    = $table.' as a, '.$table.' as b';
        $where   = 'a.uid = '.intval($uid);
        $where  .= ' AND a.pid = '.intval($pid);
        $where  .= ' AND a.pid = b.pid';
        $where  .= ' AND a.'.$sortingFieldName.' >= b.'.$sortingFieldName;
        $where  .= ' '.$GLOBALS['TSFE']->cObj->enableFields($table, 'a');
        $where  .= ' '.$GLOBALS['TSFE']->cObj->enableFields($table, 'b');
        $groupBy = '';
        $orderBy = 'b.'.$sortingFieldName.' desc';
        $limit   = '2,1';

        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1, $GLOBALS['TYPO3_DB']->sql_error());
        }
        $a_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
        
        if (empty($a_row['position'])) {
        	$position = $pid;
        } else {
        	$position = '-'.(string)$a_row['position'];
        }

        return $position;
    	
    }
    
    public static function selectMoveOneDownPosition($table, $pid, $uid, $sortingFieldName = 'sorting') {
    	    	
		// query preparation
        $select  = 'b.uid as position';
        $from    = $table.' as a, '.$table.' as b';
        $where   = 'a.uid = '.intval($uid);
        $where  .= ' AND a.pid = '.intval($pid);
        $where  .= ' AND a.pid = b.pid';
        $where  .= ' AND a.'.$sortingFieldName.' <= b.'.$sortingFieldName;
        $where  .= ' '.$GLOBALS['TSFE']->cObj->enableFields($table, 'a');
        $where  .= ' '.$GLOBALS['TSFE']->cObj->enableFields($table, 'b');
        $groupBy = '';
        $orderBy = 'b.'.$sortingFieldName.' asc';
        $limit   = '1,1';

        // exec query using TYPO3 DB API
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
        if ($res == false) {
            throw new tx_pttools_exception('Query failed', 1, $GLOBALS['TYPO3_DB']->sql_error());
        }
        $a_row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
        $GLOBALS['TYPO3_DB']->sql_free_result($res);
        
        if (empty($a_row['position'])) {
        	$position = false;
        } else {
        	$position = '-'.(string)$a_row['position'];
        }

        return $position;
    }
    
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sgzlib/classes/model/class.tx_sgzlib_tcaObjectAccessor.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sgzlib/classes/model/class.tx_sgzlib_tcaObjectAccessor.php']);
}

?>