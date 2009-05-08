<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Stefan Geith <typo3devYYYY@geithware.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
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

require_once(t3lib_extMgm::extPath('sg_zlib').'classes/cachedbase/class.txsg_cachedbase.php');

/**
 * Plugin 'Sartorius Mechatronics PDFs' for the 'sartorius_mech_pdf' extension.
 *
 * @author	Stefan Geith <typo3devYYYY@geithware.de>
 * @package	TYPO3
 * @subpackage	tx_sartoriusmechpdf
 */
class txsg_catmenu extends txsg_cached_base {


	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function doCatMenu () {
		$content = '';
		$this->markers = Array();
		$this->lastDelimiter = '';
		$this->hashMarks = Array();

		$this->globalMarkers = $this->felib->globalMarkers;
		$this->globalMarkers['###BACK_URL###'] = $this->felib->todo['BackUrl'];

		$this->clConf = $this->confObj['catMenu.'];
		$this->checkConfiguration();
		$this->setDefaultsInConfiguration();


		//ToDo!! //$this->template = $this->templateObj->getTemplate('catMenu',$this->globalMarkers);

		if (!$this->clConf['template']) {
			if (!$this->clConf['subpart']) {
				$this->clConf['template'] = '###uid###-###title### (###myHasEntries###/###myHasSubEntries###=###myCntEntries###)  ###link###';
			} else {
				$this->clConf['template'] = $this->templateObj->getSubpart($this->template,'###'.$this->clConf['subpart'].'###');
			}
		}

		$this->clConf['url'] = ''; //TODO! $this->felib->getTypolinkURL($this->listPage,'&type='.$GLOBALS['TSFE']->type,$PCA['cache']);
		if (!$this->clConf['link']) {
			$this->clConf['link'] = '<a href="###myUrl######mySearch###">[&gt; ###title###]</a>';
		}
		$this->clConf['order'] = $this->clConf['catTable.']['orderField'];
		$this->clConf['select'] = $this->clConf['catTable'].'.*, 0 AS myHasEntries, 0 AS myCount';
		$this->clConf['tables'] = $this->clConf['catTable'];
		$this->clConf['where'] = $this->cObj->enableFields($this->clConf['catTable']);
		$this->clConf['group'] = ''; 
		$idList = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			$this->clConf['select'],$this->clConf['tables'],'1=1 '.$this->clConf['where'],$this->clConf['group'],$this->clConf['order'],'1000','uid');
		if ($GLOBALS['TYPO3_DB']->sql_error()) {
			t3lib_div::debug(Array('sql_error'=>$GLOBALS['TYPO3_DB']->sql_error(), '$this->clConf'=>$this->clConf, 'File:Line'=>__FILE__.':'.__LINE__));
		} else {
			$this->debugObj->debugIf('catmenuDetails',Array('$clConf'=>$this->clConf, '$idList'=>$idList, 'File:Line'=>__FILE__.':'.__LINE__));
		}

		// Check for MM-Relations
		if (!$this->clConf['catTable.']['MM']) {
			$this->clConf['select2'] = $this->clConf['catTable'].'.*, 1 AS myHasEntries, count(*) AS myCount';
			$this->clConf['tables2'] = $this->clConf['catTable'].', '.$this->clConf['mainTable'];
			$this->clConf['where2'] = ' AND FIND_IN_SET('.$this->clConf['catTable'].'.uid,'.$this->clConf['mainTable'].'.'.$this->clConf['mainTable.']['catField'].') '.
				$this->cObj->enableFields($this->clConf['catTable']).$this->cObj->enableFields($this->clConf['mainTable']);
			$this->clConf['group2'] = $this->clConf['catTable'].'.uid'; 
			$idCntList = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				$this->clConf['select2'],$this->clConf['tables2'],'1=1 '.$this->clConf['where2'],$this->clConf['group2'],$this->clConf['order'],'1000','uid');
			if ($GLOBALS['TYPO3_DB']->sql_error()) {
				t3lib_div::debug(Array('sql_error'=>$GLOBALS['TYPO3_DB']->sql_error(), '$this->clConf'=>$this->clConf, 'File:Line'=>__FILE__.':'.__LINE__));
			} else {
				$this->debugObj->debugIf('catmenuDetails',Array('$clConf'=>$this->clConf, '$idCntList'=>$idCntList, 'File:Line'=>__FILE__.':'.__LINE__));
			}
			$allCats = t3lib_div::array_merge($idList,$idCntList);
		} else { // this has to be checked !! TODO !!!!!
			$this->clConf['select3'] = $this->clConf['catTable'].'.*, count(*) AS myHasEntries, count(*) AS myCount';
			$this->clConf['tables3'] = $this->clConf['catTable'].', '.$this->clConf['catTable.']['MM'].', '.$this->clConf['mainTable'];
			$this->clConf['where3'] = ' AND '.$this->clConf['catTable.']['MM'].'.uid_foreign='.$this->clConf['catTable'].'.uid '.
									  ' AND '.$this->clConf['catTable.']['MM'].'.uid_local='.$this->clConf['mainTable'].'.uid '.
									  $this->cObj->enableFields($this->clConf['catTable']).$this->cObj->enableFields($this->clConf['mainTable']);
			$this->clConf['group3'] = $this->clConf['catTable'].'.uid';

			$query = $this->clConf['catTable.']['listWhere'].' '.($this->clConf['idlist'] ? 'AND uid IN ('.$this->clConf['idlist'].')': '' ).$this->clConf['where'];
			$idCntList = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				$this->clConf['select3'],$this->clConf['tables3'],'1=1 '.$this->clConf['where3'],$this->clConf['group3'],$this->clConf['order'],'30000','uid');
			if ($GLOBALS['TYPO3_DB']->sql_error()) {
				t3lib_div::debug(Array('sql_error'=>$GLOBALS['TYPO3_DB']->sql_error(), 'File:Line'=>__FILE__.':'.__LINE__));
			} else {
				$this->debugObj->debugIf('catmenuDetails',Array('$clConf'=>$this->clConf, '$allCats'=>$idCntList, 'File:Line'=>__FILE__.':'.__LINE__));
			}
			$allCats = t3lib_div::array_merge($idList,$idCntList);
		}
		$this->debugObj->debugIf('catmenu',Array('$this->clConf'=>$this->clConf, '$allCats'=>$allCats, 'File:Line'=>__FILE__.':'.__LINE__));

		$query = $this->clConf['catTable.']['listWhere'].' '.$this->clConf['where'];
		if (!$this->clConf['catTable.']['listWhere'] && $this->clConf['catTable.']['levelField']) {
			$query = $this->clConf['catTable'].'.'.$this->clConf['catTable.']['levelField'].'<1 AND '.$query;
		}
		$idList = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				$this->clConf['catTable'].'.uid',$this->clConf['tables'],$query,$this->clConf['group'],$this->clConf['order'],'30000','uid');
		if ($GLOBALS['TYPO3_DB']->sql_error()) {
				t3lib_div::debug(Array('sql_error'=>$GLOBALS['TYPO3_DB']->sql_error(), 'File:Line'=>__FILE__.':'.__LINE__));
		} else {
			$this->debugObj->debugIf('catmenu',Array('$idList'=>$idList, 'File:Line'=>__FILE__.':'.__LINE__));
		}


		$catMenu = $this->listCatMenu($idList,$allCats,1);
		$wrapAll = explode('|',($this->clConf['level.'][$level.'.']['wrapAll'] ? $this->clConf['level.'][$level.'.']['wrapAll'] : $this->clConf['wrapAll']),2);

		$this->debugObj->debugIf('catmenuDetails',Array('$this->hashMarks'=>$this->hashMarks, 'File:Line'=>__FILE__.':'.__LINE__));
		$content .= $this->createAnchorLinks().$wrapAll[0].CRLF.$catMenu.$wrapAll[1];
		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$$idList: ...
	 * @param	[type]		$level: ...
	 * @return	[type]		...
	 */
	function listCatMenu(&$idList,$allCats,$level) {
		$myCObj = clone($this->cObj);
		if (count($idList)) {
			if ($this->clConf['level.'][$level.'.']['template']) {
				$template = $this->clConf['level.'][$level.'.']['template'];
			} else if ($this->clConf['level.'][$level.'.']['subpart']) {
				$template = $this->templateObj->getSubpart($this->template,'###'.$this->clConf['level.'][$level.'.']['subpart'].'###');
			} else {
				$template = $this->clConf['template'];
			}
			$link = str_replace('###myUrl###',$this->clConf['url'],($this->clConf['level.'][$level.'.']['link'] ?
				$this->clConf['level.'][$level.'.']['link'] : $this->clConf['link']));
			$wrap = explode('|',($this->clConf['level.'][$level.'.']['wrap'] ? $this->clConf['level.'][$level.'.']['wrap'] : $this->clConf['wrap']),2);
			$wrapItemAndSub = explode('|',($this->clConf['level.'][$level.'.']['wrapItemAndSub'] ?
				$this->clConf['level.'][$level.'.']['wrapItemAndSub'] : $this->clConf['wrapItemAndSub']),2);
			$content = $wrapItemAndSub[0].CRLF;

			foreach ($idList as $key=>$entry) {
				$allCats[$key]['myCntEntries'] = intval($allCats[$key]['myCount']);
				$allCats[$key]['subcats'] = Array();
				$allCats[$key]['mySearch'] .= '&'.$this->prefixId.'[searchmode]=1&'.$this->prefixId.'[search]['.$this->clConf['field'].']['.$key.']='.$key;
				$subContent = '';
				$hasSubEntries = 0;
				$cntSubEntries = 0;
				if ($level<$this->clConf['catTable.']['maxLevel'] && $this->clConf['catTable.']['parentField']) {
					$query = $this->clConf['catTable.']['parentField'].'='.$key.' '.$this->clConf['where'];
					$nextList = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
						$this->clConf['catTable'].'.uid',$this->clConf['tables'],$query,$this->clConf['group'],$this->clConf['order'],'1000','uid');
					$this->debugObj->debugIf('catmenuDetails',Array('$query'=>$query, '$nextList'=>$nextList, 'File:Line'=>__FILE__.':'.__LINE__));
					foreach ($nextList as $myKey=>$value) {
						$hasSubEntries += intval($allCats[$myKey]['myCount'])+$value['myHasSubEntries'];
						$allCats[$key]['subcats'][] = $myKey;
						$allCats[$key]['myCntEntries'] += intval($allCats[$myKey]['myCount']);
						if ($this->clConf['linkIfSubcats']) {
							$allCats[$key]['mySearch'] .= '&'.$this->prefixId.'[search]['.$this->clConf['field'].']['.$myKey.']='.$myKey;
						}
					}

					$subContent .= $this->listCatMenu($nextList,$allCats,$level+1);
				}

				$allCats[$key]['myHasSubEntries'] = $hasSubEntries;
				$allCats[$key]['myIcon']='';
				$myCObj->data = $allCats[$key];
				if ($this->clConf['myIcon']) {
					$allCats[$key]['myIcon'] = $myCObj->cObjGetSIngle($this->clConf['myIcon'],$this->clConf['myIcon.']);
				}

				$allCats[$key]['myLink']='';
				$tmpLink = $myCObj->cObjGetSIngle($this->clConf['myLink'],$this->clConf['myLink.']);
				if ($allCats[$key]['myHasEntries']) {
					//$allCats[$key]['myLink']=$this->cObj->substituteMarkerArray($link,$allCats[$key],'###|###');
					$allCats[$key]['myLink']=$tmpLink;
				} else if ($this->clConf['linkIfSubcats'] && $hasSubEntries) {
					//$allCats[$key]['myLink']=$this->cObj->substituteMarkerArray($link,$allCats[$key],'###|###');
					$allCats[$key]['myLink']=$tmpLink;
				} else if ($this->clConf['myNoLink']) {
					$allCats[$key]['myLink'] = $myCObj->cObjGetSIngle($this->clConf['myNoLink'],$this->clConf['myNoLink.']);
				}
				if (!$this->clConf['catTable.']['hideEmpty'] || ( $allCats[$key]['myCntEntries']>0 AND ($hasSubEntries>0 || $allCats[$key]['myHasEntries']>0))) {
					if ($level<2) {
						$content .= $this->checkForDelimiter ($allCats[$key]);
					}
					$content .= $wrap[0].$this->cObj->substituteMarkerArray($template,$allCats[$key],'###|###').$wrap[1].$subContent.CRLF;
					$this->debugObj->debugIf('catmenuDetails',Array($key=>$allCats[$key]['myHasEntries'].'/'.$allCats[$key]['myHasSubEntries'].'='.$allCats[$key]['myCntEntries'], 'File:Line'=>__FILE__.':'.__LINE__));
				} else {
					$this->debugObj->debugIf('catmenuDetails',Array($key.' (empty)'=>$allCats[$key]['myHasEntries'].'/'.$allCats[$key]['myHasSubEntries'].'='.$allCats[$key]['myCntEntries'], 'File:Line'=>__FILE__.':'.__LINE__));
				}
			}
			if ($level<2 && $this->lastDelimiter) {
				if (isset($this->clConf['delimiter.']['headerClose.']['stdWrap.'])) {
					$content .= $this->cObj->stdWrap($hash.$initialKey,$this->clConf['delimiter.']['headerClose.']['stdWrap.']).CRLF;
				} else {
					$content .= '</ul>'.CRLF;
				}
			}
			$content .= $wrapItemAndSub[1].CRLF;
		}

		return ($content);
	}

	function checkForDelimiter($row) {
		$content = '';
		if (is_array($this->clConf['delimiter.']) && $this->clConf['delimiter.']['field']) {
			$initialKey = strtoupper(substr($row[$this->clConf['delimiter.']['field']],0,1));

			if (strcasecmp($this->clConf['delimiter.']['mode'],'any')==0) {
				if (strcasecmp($initialKey,$this->lastDelimiter)) {
					if ($this->lastDelimiter) {
						if (isset($this->clConf['delimiter.']['headerClose.']['stdWrap.'])) {
							$content .= $this->cObj->stdWrap($hash.$initialKey,$this->clConf['delimiter.']['headerClose.']['stdWrap.']).CRLF;
						} else {
							$content .= '</ul>'.CRLF;
						}
					}
					$this->hashMarks[$initialKey] = $initialKey;
					$hash = '<a name="catmenu_'.$initialKey.'_'.$this->prefixId.'"></a>';
					if (isset($this->clConf['delimiter.']['headerOpen.']['stdWrap.'])) {
						$content .= $this->cObj->stdWrap($hash.$initialKey,$this->clConf['delimiter.']['headerOpen.']['stdWrap.']).CRLF;
					} else {
						$content .= '<li>'.$hash.$initialKey.'</li><ul>'.CRLF;
					}
				}
				$this->lastDelimiter = $initialKey;
			}
		}

		return ($content);
	}

	function createAnchorLinks() {
		$content = '';

		if (count($this->hashMarks)>1 && $this->clConf['delimiter.']['anchorLinks']) {
			if ($this->clConf['delimiter.']['anchorLinks.']['group']) {
				$keylist = Array();
				$tmp = t3lib_div::trimExplode(',',$this->clConf['delimiter.']['anchorLinks.']['group']);
				foreach ($tmp as $value) {
					$pair = t3lib_div::trimExplode('=',$value);
					$keylist[$pair[0]] = $pair[1] ? $pair[1] : $pair[0];
				}

				$myHashMarks = $this->hashMarks;
				$this->hashMarks = Array();
				reset($myHashMarks); $firstKey = key($myHashMarks);
				if ($this->clConf['delimiter.']['anchorLinks.']['other'] && strcmp(strtoupper($firstKey),'A')<0 ) {
					$this->hashMarks[strtoupper($firstKey)] = $this->clConf['delimiter.']['anchorLinks.']['other'];
				}
				foreach ($keylist as $key=>$value) {
					if (!isset($myHashMarks[$key])) {
						for (reset($myHashMarks);$xKey=key($myHashMarks);next($myHashMarks)) {
							if (strcmp($xKey,$key)>0) {
								$this->hashMarks[$xKey] = $value;
								break;
							}
						}
					} else {
						$this->hashMarks[$key] = $value;
					}
				}
			}


			foreach ($this->hashMarks as $key=>$value) {
				if (isset($this->clConf['delimiter.']['anchorLinks.']['stdWrap.'])) {
					$this->hashMarks[$key] = $this->cObj->stdWrap($value,$this->clConf['delimiter.']['anchorLinks.']['stdWrap.']).CRLF;
					$this->hashMarks[$key] = str_replace('###hash###','catmenu_'.$key.'_'.$this->prefixId,$this->hashMarks[$key]);
				}
			}
		}
		
		$wrapAll = explode('|',$this->clConf['delimiter.']['anchorLinks.']['wrapAll']);
		$content = $wrapAll[0].CRLF.
			implode( ($this->clConf['delimiter.']['anchorLinks.']['implode'] ? $this->clConf['delimiter.']['anchorLinks.']['implode'] : ' ')  , $this->hashMarks).
			$wrapAll[1].CRLF;
		return ($content);
	}

	function checkConfiguration() {
		if (!is_array($this->clConf)) {
			throw new tx_sglib_exception ('No configuration found',-1,'conf.catMenu is empty');
		} else {
			$errors = '';
			if (!$this->clConf['mainTable']) {
				$errors .= 'catMenu.mainTable; ';
			}
			if (!$this->clConf['mainTable.']['catField']) {
				$errors .= 'catMenu.mainTable.catField; ';
			}
			if (!$this->clConf['catTable']) {
				$errors .= 'catMenu.catTable; ';
			}
			if (!$this->clConf['catTable.']['titleField']) {
				$errors .= 'catMenu.catTable.titleField; ';
			}

			if ($errors) {
				throw new tx_sglib_exception ('Configuration uncomplete',-1,$errors);
			}
		}
	}

	function setDefaultsInConfiguration () {
		if (!$this->clConf['catTable.']['orderField']) {
			$this->clConf['catTable.']['orderField'] = $this->clConf['catTable'].'.'.$this->clConf['catTable.']['titleField'];
		}
		if (!$this->clConf['catTable.']['listWhere']) {
			$this->clConf['catTable.']['listWhere'] = '1=1';
		}
		if ($this->clConf['catTable.']['maxLevel']>12) {
			$this->clConf['catTable.']['maxLevel'] = 12;
		}

		if (!isset($this->clConf['wrap']) && !isset($this->clConf['wrapAll']) && !isset($this->clConf['wrapItemAndSub'])) {
			$this->clConf['wrap'] = '<li>|</li>';
			$this->clConf['wrapItemAndSub'] = '<ul>|</ul>';
			$this->clConf['wrapAll'] = '<div class="txsg-catmenu">|</div>';
		}

	}


}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/classes/cachedbase/class.txsg_catmenu.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/classes/cachedbase/class.txsg_catmenu.php']);
}

?>