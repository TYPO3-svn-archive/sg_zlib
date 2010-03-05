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
class tx_sglib_viewhelpers_wrap extends tx_sglib_viewhelper {
	protected $defaultKey = '';

	public function renderContent() {
		$value = $this->row[$this->params[0]];
		if ($this->params['unHtml']) {
			$value = htmlspecialchars_decode(str_replace('&nbsp;',' ',$value));
		}

		unset($this->params[0]);
		unset($this->params[1]);

		$content = $this->cObj->stdWrap($value,$this->params); 
		return ($content);
		}

}

class tx_sglib_viewhelpers_wrapne extends tx_sglib_viewhelper {
	protected $defaultKey = '';

	public function renderContent() {
		$value = $this->row[$this->params[0]];
		if ($this->params['unHtml']) {
			$value = htmlspecialchars_decode(str_replace('&nbsp;',' ',$value));
		}

		unset($this->params[0]);
		unset($this->params[1]);
		$this->params['required'] = 1;
		$content = $this->cObj->stdWrap($value,$this->params); 
		// t3lib_div::debug(Array('$value'=>$value, '$this->params'=>$this->params, '$content'=>$content, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($content);
		}

}




?>