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

require_once(t3lib_extMgm::extPath('sg_zlib').'classes/viewhelpers/class.tx_sglib_viewhelper.php');

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
class tx_sglib_viewhelpers_link extends tx_sglib_viewhelper {
	protected $defaultKey = 'id';

	protected function renderContent() {
		$content = '';
		$this->params = $this->getJsonParams($this->parameters,'id');
		if (isset($this->params['id']) || isset($this->params['eid'])) {
			$linksObj = $this->factoryObj->linksObj;
			$linksObj->init();
			$pid = $this->params['id'];
			if (!$pid) {
				$pid = $GLOBALS['TSFE']->id;
			}
			$linksObj->destination($pid);
			$globalParameters = Array();
			$parameters = Array();
			if (is_array($this->params['params.'])) foreach ($this->params['params.'] as $sKey=>$conf) {
				if (substr($conf,0,1)=='!') {
					$element = substr($conf,1);
				} else {
					$element = $this->row[$conf];
				}
				$globalParameters[$sKey] = $element;
				// t3lib_div::debug(Array('$this->table'=>$this->table, '$sConf'=>$sConf, '$pid'=>$pid, 'File:Line'=>__FILE__.':'.__LINE__));
			}
			if (is_array($this->params['piparams.'])) foreach ($this->params['piparams.'] as $sKey=>$conf) {
				$parameters[$sKey] = $this->row[$conf];
				// t3lib_div::debug(Array('$this->table'=>$this->table, '$sConf'=>$sConf, '$pid'=>$pid, 'File:Line'=>__FILE__.':'.__LINE__));
			}
			if (isset($this->params['eid'])) {
				$globalParameters['eID'] = $this->params['eid'];
				$linksObj->noHash();
			}
			if ($this->params['target']) {
				$linksObj->target($this->params['target']);
			}
			if (count($globalParameters)) {
				$linksObj->globalParameters($globalParameters);
			}
			if (count($parameters)) {
				$linksObj->parameters($parameters);
			}
			$linksObj->label($this->getLabel('label'));
			$content = $linksObj->makeTag();
		}
		return ($content);
	}

	public function renderClosingTag($table, $row, $params) {
		$content = $this->lastLinkOK ? '</a>' : '';
		$this->lastLinkOK = FALSE;
		return ($content);
	}

}




?>