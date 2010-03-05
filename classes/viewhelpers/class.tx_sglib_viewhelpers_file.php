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
 *  168:     public function getDescriptions($this->table='', $markers=array())
 *  187:     public function getTtContent($ttConf,$markers=array())
 *  205:     public function getRefValues($record, $this->table='', $markers=array())
 *
 * TOTAL FUNCTIONS: 8
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_viewhelpers_file extends tx_sglib_viewhelper {
	protected $defaultKey = 'field';

	protected function renderContent() {
		$content = '';
		if ($this->params['field']) {
			$fileList = t3lib_div::trimExplode(',',$this->row[$this->params['field']]);
			$uploadFolder = '';
			if (strcmp($this->confObj->get($this->table.'.conf.'.$this->params['field'].'.internal_type'),'file_reference')!=0) {
				$uploadFolder = $this->confObj->get($this->table.'.conf.'.$this->params['field'].'.uploadfolder').'/';
			}

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

//			if (count($fileList)>0) {
//				t3lib_div::debug(Array('count'=>count($fileList), '$start'=>$start, '$end'=>$end, '$this->params'=>$this->params, 'File:Line'=>__FILE__.':'.__LINE__));
//			}

			$contentList = Array();
			for ($i=$start;$i<=$end;$i++) {
				$singleFile = $this->getSingleFile($uploadFolder,$fileList[$i],$this->params);
				if ($singleFile) {
					$contentList[] = $singleFile;
				}
			}

			if (count($contentList)) {
				$tmp = explode('|',$this->params['wrapall']);
				$content = $tmp[0].implode($this->params['implode'],$contentList).$tmp[1];
			}

		}

		return ($content);
	}

	protected function getSingleFile ($uploadFolder,$fileName,$params) {
		$content = '';
		// t3lib_div::debug(Array('$this->table'=>$this->table, 'params'=>params;

		$fullPath = $uploadFolder.$fileName;
		$check = t3lib_div::getFileAbsFileName($fullPath);
		if (file_exists($check)) {		
			$conf = $this->confObj->get(($params['mode'] ? $params['mode'] : 'defaultFile').'.');
			if (!is_array($conf)) {
				$conf = $this->confObj->get('defaultFile.');
			}
			if (!is_array($conf)) {
				$conf = Array();
			}
			
			// calculate settings for rendering object
			$label = $this->getLabel('label');
			$conf['value'] = $label ? $label : substr (strrchr ($fileName, '/'), 1);
			$conf['typolink.']['parameter'] = $fullPath;

			$content = $this->cObj->TEXT($conf);

			// and wrap each single item
			if ($content && $params['wrap']) {
				$tmp = explode('|',$params['wrap']);
				$content = $tmp[0].$content.$tmp[1];
			}

			// t3lib_div::debug(Array('$this->table'=>$this->table, '$fileName'=>$fileName, '$content'=>$content, 'File:Line'=>__FILE__.':'.__LINE__));
		}

		return ($content);
	}

}




?>