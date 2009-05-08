<?php

require_once t3lib_extMgm::extPath('sg_zlib').'classes/base/class.tx_sgzlib_collection.php';
// require_once t3lib_extMgm::extPath('tcaobjects').'res/class.tx_tcaobjects_iPageable.php';


class tx_sgzlib_tcaObjectCollection extends tx_sgzlib_collection implements ArrayAccess, IteratorAggregate, Countable { // , tx_tcaobjects_iPageable {


	protected $tcaObjectName = '';
	protected $table = '';


	public function __construct ($table='') {
		$this->table = $table; 
		$this->tcaObjectName = str_replace('Collection', '', get_class($this)); // assuming that "fooCollection" contains "foo" objects
	}




	public function loadItems($where = '', $limit = '', $order = '') {
		$dataArr = tx_sgzlib_tcaObjectAccessor::selectCollection($this->getTable(), $where, $limit, $order);
		$this->setDataArray($dataArr);
	}

	public function loadItemsByUid($where = '', $limit = '', $order = '') {
		$dataArr = tx_sgzlib_tcaObjectAccessor::selectCollection($this->getTable(), $where, $limit, $order, false);
		$this->setDataArrayById($dataArr,'uid');
	}
	
	protected function getTable() {
		if ($this->table == '') {
			$this->table = str_replace('Collection', '', get_class($this)); // assuming that "fooCollection" contains "foo" objects
		}
		return $this->table;
	}
	
	protected function setDataArray($dataArr) {
		foreach ($dataArr as $row) {
			$this->addItem(new $this->tcaObjectName($this->table, '', $row));
		}
	}

	protected function setDataArrayById($dataArr,$idField) {
		foreach ($dataArr as $row) {
			 $this->itemsArr[$row[$idField]] = new $this->tcaObjectName($this->table, '', $row);
		}
	}

	/***************************************************************************
	 * Methods for the "tx_tcaobjects_iPageable" interface
	 **************************************************************************/
	public function getTotalItemCount($where = '') {
		return tx_sgzlib_tcaobjectAccessor::selectCollectionCount($this->getTable(), $where);
	}
	
	
	public function getItems($where = '', $limit = '', $order = ''){
		$this->loadItems($where, $limit, $order);
		return $this;	
	}
	
	/***************************************************************************
	 * Methods for the "ArrayAccess" interface
	 **************************************************************************/
	public function offsetExists($offset) {
		return array_key_exists($this->itemsArr, $offset);
	}

	public function offsetGet($offset) {
		return $this->itemsArr[$offset];
	}

	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->itemsArr[] = $value;
		} else {
			$this->itemsArr[$offset] = $value;
		}
	}

	public function offsetUnset($offset) {
		unset($this->itemsArr[$offset]);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sgzlib/classes/model/class.tx_sgzlib_tcaObjectCollection.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sgzlib/classes/model/class.tx_sgzlib_tcaObjectCollection.php']);
}

?>