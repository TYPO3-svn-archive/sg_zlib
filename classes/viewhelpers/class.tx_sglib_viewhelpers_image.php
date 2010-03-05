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
class tx_sglib_viewhelpers_image extends tx_sglib_viewhelper {
	protected $defaultKey = 'field';
	protected $resourceMode = FALSE;

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

//			if (count($fileList)>1) {
//				t3lib_div::debug(Array('count'=>count($fileList), '$start'=>$start, '$end'=>$end, '$this->params'=>$this->params, 'File:Line'=>__FILE__.':'.__LINE__));
//			}

			$row = $this->row;
			$contentList = Array();
			$cnt = $start;
			for ($i=$start;$i<=$end;$i++) {
				$row['i'] = intval($i);
				$row['index'] = 1 + intval($i);
				$row['singlefile'] = $fileList[$i];
				$this->cObj->start($row,$this->table);
				$singleImage = $this->getSingleImage($uploadFolder,$fileList[$i],$this->params);
				if ($singleImage) {
					$contentList[] = $singleImage;
				}
			}

			if (count($contentList)) {
				$tmp = explode('|',$this->params['wrapall']);
				$content = $tmp[0].implode($this->params['implode'],$contentList).$tmp[1];
			}

		}

		return ($content);
	}

	protected function getSingleImage ($uploadFolder,$fileName,$params) {
		$content = '';
		if ($params['alt'] && !is_array($params['alt.'])) {
			$params['alt.'] = Array('value' => $params['alt'], 'insertData' => 1);
			$params['alt'] = 'TEXT';
		} elseif (!$params['alt'] && $params['alt.']) {
			$params['alt'] = 'TEXT';
		}
		if ($params['title'] && !is_array($params['title.'])) {
			$params['title.'] = Array('value' => $params['title'], 'insertData' => 1);
			$params['title'] = 'TEXT';
		} elseif (!$params['title'] && $params['title.']) {
			$params['title'] = 'TEXT';
		}

		$fullPath = $uploadFolder.$fileName;
		$check = t3lib_div::getFileAbsFileName($fullPath);
		if (file_exists($check)) {
			$conf = $this->confObj->get(($params['mode'] ? $params['mode'] : 'defaultImage').'.');
			if (!is_array($conf)) {
				$conf = $this->confObj->get('defaultImage.');
			}
			if (!is_array($conf)) {
				$conf = Array();
			}

			if (is_array($params['alt.'])) {
				$myAlt = $this->cObj->cObjGetSingle($params['alt'],$params['alt.']);
				$conf['altText'] = $myAlt;
			}
			if (is_array($params['title.'])) {
				$myTitle = $this->cObj->cObjGetSingle($params['title'],$params['title.']);
				$conf['titleText'] = $myAlt;
			}

			// calculate settings for rendering object
			$conf['file'] = $fullPath;
			if ($params['x']) {
				$conf['file.']['width'] = $params['x'];
			}
			if ($params['y']) {
				$conf['file.']['height'] = $params['y'];
			}
			// t3lib_div::debug(Array('$table'=>$table, '$params'=>$params, 'File:Line'=>__FILE__.':'.__LINE__));
			// $content = '[IMAGE '.$fullPath.': '.$params['x'].'/'.$params['y'].' ]';
			if ($this->resourceMode) {
				$content = $this->cObj->IMG_RESOURCE($conf);
			} else {
				$content = $this->cObj->IMAGE($conf);
			}

			// and wrap each single item
			if ($content && $params['wrap']) {
				$tmp = explode('|',$params['wrap']);
				$content = $tmp[0].$content.$tmp[1];
			} elseif ($content && $params['dataWrap']) {
				$content = $this->cObj->dataWrap($content,$params['dataWrap']) ;
			}
		}

		return ($content);
	}

}


class tx_sglib_viewhelpers_imageres extends tx_sglib_viewhelpers_image {
	protected $resourceMode = TRUE;
}

?>