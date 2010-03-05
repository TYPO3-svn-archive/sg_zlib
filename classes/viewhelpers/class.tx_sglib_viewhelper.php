<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2002-2007 Stefan Geith (typo3devYYYY@geithware.de)
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
 *  168:     public function getDescriptions($table='', $markers=array())
 *  187:     public function getTtContent($ttConf,$markers=array())
 *  205:     public function getRefValues($record, $table='', $markers=array())
 *
 * TOTAL FUNCTIONS: 8
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_viewhelper {
	protected $designator;
	protected $factoryObj;
	protected $confObj;
	protected $paramsObj;
	protected $langObj;
	protected $jsonObj;

	protected $table;
	protected $row;
	protected $parameters;
	protected $params;

	protected $formName = '';
	protected $defaultKey = '';

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$tx_sglib_config $confObj, tx_sglib_debug $debugObj: ...
	 * @param	[type]		$cObj: ...
	 * @return	[type]		...
	 */
	public function init(tx_sglib_factory $factoryObj, $formName='', $type='', $settings=Array()) {
		$this->factoryObj = $factoryObj;
		$this->confObj = $factoryObj->confObj;
		$this->paramsObj = $factoryObj->paramsObj;
		$this->langObj = $factoryObj->langObj;
		$this->jsonObj = $factoryObj->jsonObj;
		$this->mainTable = $this->confObj->getTCAname();
		$this->designator = $this->factoryObj->getDesignator();
		$this->type = $type;
		$this->formName = $formName;
		$this->params = NULL;
		$this->settings = $settings;

		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
	}



	public function render($table, $row, $parameters) {
		$content = '';
		$this->table = $table;
		$this->row = $row;
		$this->parameters = $parameters;
		$this->cObj->start($row,$table);
		$this->params = $this->getJsonParams($parameters,$this->defaultKey);
		if ($this->params['dbg']) {
			t3lib_div::debug(Array('$table'=>$table, '$this->type'=>$this->type, '$parameters'=>$parameters, '$this->params'=>$this->params, 'File:Line'=>__FILE__.':'.__LINE__));
		}

		$content = $this->renderContent();
		return ($content) ;
		}



	protected function getJsonParams ($parameter,$defaultFieldName='') {
		$this->params = $this->jsonObj->decodeString($parameter);
		if ($defaultFieldName) {
			if (!isset($this->params[$defaultFieldName])) {
				$this->params[$defaultFieldName] = $this->params[0];
				unset ($this->params[0]);
			}
		}
		return ($this->params);
	}

	protected function getLabel ($fieldName='label') { //, $labelFieldName='') {
		$label = '';

		$label = $fieldName ? $this->params[$fieldName] : '';
		if (is_array($this->params[$fieldName.'.'])) {
			$label = $this->cObj->stdWrap($label,$this->params[$fieldName.'.']);
		}

		return ($label);
	}

}




?>