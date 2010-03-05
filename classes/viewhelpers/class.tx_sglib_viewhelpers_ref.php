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
class tx_sglib_viewhelpers_ref extends tx_sglib_viewhelper {
	protected $defaultKey = 'field';

	public function renderContent() {
		$fieldNames = t3lib_div::trimExplode('/',$this->params['field']);
//		if (strcmp($fieldNames[0],'projectid')==0) foreach ($this->row as $key=>$field) {
//			if (strncmp($key,$fieldNames[0],strlen($fieldNames[0]))==0) {
//				t3lib_div::debug(Array($key=>$field, 'File:Line'=>__FILE__.':'.__LINE__));
//			}
//		}
		$start = 0;
		$end = count($fileList)-1;
		$tmp = t3lib_div::intExplode('-',$this->params['i']);
		if ($tmp[0]>0) {
			$start = $tmp[0]-1;
		}
		if ($tmp[1]>0 && $tmp[1]>$tmp[0]) {
			$end = $tmp[1]-1;
		} elseif (trim($this->params['i'])) {
			$end = $start;
		}

		$result = $this->getFieldRefValue($this->row,$fieldNames);

		$wrap = explode('|',$this->params['wrap']);
		if (is_array($result) && $this->params['i']) {
			$total = array_values($result);
			$result = Array();
			for ($i=$start;$i<=$end;$i++) if ($total[$i]) {
				$result[] = $total[$i];
			}
		}
		if (is_array($result)) {
			$wrapall = explode('|',$this->params['wrapall']);
			if ($this->params['wrap']) foreach ($result as $key=>$value) {
				$result[$key] = $wrap[0].$value.$wrap[1];
			}
			$content = $wrapall[0].implode($this->params['implode'],$result).$wrapall[1];
		} else {
			$content = $wrap[0].$result.$wrap[1];
		}

		return ($content);
		}

	public function getFieldRefValue ($row, array $fieldNames) {
		if (count($fieldNames)) {
			$first = array_shift($fieldNames);
			// if (strcmp($first,'precursor')==0) t3lib_div::debug(Array('$first'=>$first, '$fieldNames'=>$fieldNames, 'File:Line'=>__FILE__.':'.__LINE__));
			if (is_array($row[$first.'_record'])) {
				return ($this->getFieldRefValue($row[$first.'_record'],$fieldNames));
			} elseif (isset($row[$first.'_record'])) {
				return ($row[$first.'_record']);
			} elseif(is_array($row[$first.'_list'])) {
				return ($row[$first.'_list']);
			} elseif(is_array($row[$first.'_array'])) {
				$myResultList = Array();
				foreach ($row[$first.'_array'] as $oneResult) {
					$myResultList[] = $this->getFieldRefValue($oneResult,$fieldNames);
				}
				return ($myResultList);
			} else {
				$index = $row[$first];
				$itemsArray = $GLOBALS['TCA'][$this->table]['columns'][$first]['config']['items'];
				if (is_array($itemsArray)) {
					// t3lib_div::debug(Array('$index'=>$index, '$itemsArray'=>$itemsArray,  'File:Line'=>__FILE__.':'.__LINE__));
					return ($this->findInItemsArray($index,$itemsArray));
				} else {
					return ($index ? $index : '');
				}
			}
		} else {
			return ($row);
		}
	}

	public function findInItemsArray($index,$itemsArray) {
		$value = ($index ? $index : '');

		foreach ($itemsArray as $pair) {
			if ($pair[1]==$index) {
				$value = $pair[0];
				break;
			}
		}

		return ($value);
	}
}




?>