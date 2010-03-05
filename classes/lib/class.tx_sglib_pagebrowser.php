<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2002-2007 Stefan Geith (typo3dev2007@geithware.de)
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
 *   63: class tx_sglib_pagebrowser
 *   95:     private function init(tx_sglib_factory $factoryObj)
 *  135:     private function _fCount ($name=NULL)
 *  158:     function __destruct()
 *
 *              SECTION: User Functions
 *  171:     function getActiveFeUser()
 *  192:     function getActiveFeGroups()
 *  209:     function getActiveBeUser()
 *  230:     function getActiveBeGroups()
 *  249:     function getFeUid()
 *  259:     function getBeUid()
 *  270:     function getFeUser($name=NULL)
 *  285:     function getBeUser($name=NULL)
 *  299:     function getFeGroups()
 *  309:     function getBeGroups()
 *  321:     function checkFeUserIn ($chkUserList,$chkGroupList)
 *  368:     function checkBeUserIn ($chkUserList,$chkGroupList)
 *
 *              SECTION: FE-Editing permissons
 *  419:     function allowed($property)
 *  428:     function useEditMode()
 *  437:     function getEditPropertiesAsText ()
 *  447:     function processTemplate($template)
 *
 *              SECTION: Private functions
 *  506:     function _getEditMode ()
 *  814:     private function _getListOfEditors($myAllow)
 *
 * TOTAL FUNCTIONS: 21
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_pagebrowser {
	private static $instance = Array();

	protected $factoryObj = NULL;
	protected $confObj;
	protected $debugObj;
	protected $constObj;
	protected $permitObj;
	protected $langObj;
	protected $cObj;
	protected $conf=Array();
	protected $defaultDesignator;

	protected $countTotalEntires = 'undefined';
	protected $activePage = 0;
	protected $params = array();
	protected $cntTotalPages = 'undefined';
	protected $countEntriesPerPage = 'undefined';
	protected $result = Array();

	protected $showResultsWrap = Array();
	protected $numbersWrap = Array();
	protected $pagebrowserWrap = Array();


	protected function __construct() {}

	private function __clone() {}

	/**
	 * Returns a singlton instance of tx_sglib_pagebrowser
	 *
	 * @param	string				Designator
	 * @param	tx_sglib_factory	FactoryObj
	 * @return	tx_sglib_pagebrowser	Instantiated Object
	 */
	
	public static function getInstance($designator, tx_sglib_factory $factoryObj) {
		if (!isset(self::$instance[$designator])) {
			self::$instance[$designator] = new tx_sglib_pagebrowser();
			self::$instance[$designator]->init($factoryObj);
		}
		return (self::$instance[$designator]);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$tx_sglib_config $confObj, tx_sglib_debug $debugObj, tx_sglib_const $constObj, tx_sglib_lang $langObj: ...
	 * @return	[type]		...
	 */
	private function init(tx_sglib_factory $factoryObj) {
		$this->_fCount(__FUNCTION__);
		$this->factoryObj = $factoryObj;
		$this->confObj = $factoryObj->confObj;
		$this->defaultDesignator = $this->confObj->getDesignator();
		$this->debugObj = $factoryObj->debugObj;
		$this->permitObj = $factoryObj->permitObj;
		$this->constObj = $factoryObj->constObj;
		$this->langObj = $factoryObj->langObj;
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');

		$this->activePage = intval(t3lib_div::_GP('pg'));
		if ($this->activePage<0) {
			$this->activePage = 0;
		}

		$this->conf = Array(
			'pointerName' => 'pg',
			'resPPname' => 'resPP',
			'entriesPerPage' => 20,
			'spacing' => ' ',
			'numSpacing' => ' ',
			'first' => '[First]',
			'last' => '[Last]',
			'prev' => '[Prev]',
			'next' => '[Next]',
			'divider' => 10,
			'around' => 4,
			'countText' => 'Items ###fromitem###-###toitem### of ###cnt### on page ###page### of ###maxpages###.',
			//'pageBrowser' => '###COUNT###<br />###PAGES###<br /><br />',
			'pageBrowser' => '###COUNT###<br />###GOTOPAGE### ###RESULTSPP###<br />###PAGES###<br /><br />',
			// 'pageBrowser' => '###COUNT### ###GOTOPAGE### ###RESULTSPP###<br />###PAGES###<br /><br />',

			//'pageBrowserAlsoSingle' => 1 ,
			'showFirstLast' => 1,
			//'gotoPageAlsoSingle' => 1,
			'userResPP' => '5,10,20,50,100,200,500,1000',
			);
		$this->conf = t3lib_div::array_merge_recursive_overrule($this->conf, $this->confObj->pageBrowser);

		$this->conf['countText'] = $this->confObj->TSObj($this->conf['countText'],$this->conf['countText.']);

		$resPP = intval(t3lib_div::_GP('resPP'));
		if ($resPP>0 && strpos(',,'.$this->conf['userResPP'].',',','.$resPP.',')>0) {
			$this->conf['entriesPerPage'] = $resPP;
			$GLOBALS['TSFE']->fe_user->setKey('ses',$this->defaultDesignator.'_resPP', $resPP);
		} else {
			$resPP = $GLOBALS['TSFE']->fe_user->getKey('ses',$this->defaultDesignator.'_resPP');
			if ($resPP>0 && strpos(',,'.$this->conf['userResPP'].',',','.$resPP.',')>0) {
				$this->conf['entriesPerPage'] = $resPP;
			} else {
				$GLOBALS['TSFE']->fe_user->setKey('ses',$this->defaultDesignator.'_resPP', NULL);
			}
		}
		// get session and set, if there


		$this->countEntriesPerPage = $this->conf['entriesPerPage'];
		$this->showResultsWrap = t3lib_div::trimExplode('|',$this->conf['showResultsWrap']);
		$this->numbersWrap = t3lib_div::trimExplode('|',$this->conf['numbersWrap']);
		$this->pagebrowserWrap = t3lib_div::trimExplode('|',$this->conf['pagebrowserWrap']);

		$this->setParamsFromURL();

		$this->debugObj->debugIf('pagebrowser',Array('$this->conf'=>$this->conf));
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	private function _fCount ($name=NULL) {
		static $callCount = NULL;
		if (!isset($callCount)) {
			$callCount = Array();
			foreach(get_class_methods(__CLASS__) as $key=>$value) {
				$callCount[$value] = 0;
			}
			unset($callCount['_fCount']);
			unset($callCount['__destruct']);
		}
		if (isset($name)) {
			$callCount[$name]++;
			return;
		} else {
			return $callCount;
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function __destruct() {
		if (is_object($this->debugObj)) $this->debugObj->debugIf('callCount',Array('Class '.__CLASS__ => $this->_fCount()));
	}

	/**
	 * ********************************************************************************************
	 *
	 * User Functions
	 *
	 * *********************************************************************************************/

	/**
	 * @return	[type]		...
	 */
	public function setCountTotalEntires($value) {
		$this->countTotalEntires = intval($value);
		$this->calculateOrganisation();
	}

	/**
	 * @return	[type]		...
	 */
	public function getCountTotalEntires() {
		return ($this->countTotalEntires);
	}

	/**
	 * @return	[type]		...
	 */
	public function getActivePage() {
		return ($this->activePage);
	}

	/**
	 * @return	[type]		...
	 */
	public function getCountEntriesPerPage() {
		return ($this->countEntriesPerPage);
	}

	/**
	 * @return	[type]		...
	 */
	public function getLimitString() {
		return ( ($this->countEntriesPerPage * $this->activePage) . ',' . $this->countEntriesPerPage);
	}

	/**
	 * @return	[type]		...
	 */
	protected function setParamsFromURL() {

		$u = tx_sgdiv::parseUrl();
		$u['params'] = $u['plist'];
		unset ($u['params']['x']);
		unset ($u['params']['y']);

		$varname = t3lib_div::trimExplode('|', $this->conf['pointerName']);
		if (count($varname)==1) {
			unset ($u['params'][$varname[0]]);
		} elseif (count($varname)==2) {
			unset ($u['params'][$varname[0]][$varname[1]]);
		}

		$varname = t3lib_div::trimExplode('|', $this->conf['resPPname']);
		if (count($varname)==1) {
			unset ($u['params'][$varname[0]]);
		} elseif (count($varname)==2) {
			unset ($u['params'][$varname[0]][$varname[1]]);
		}

		$tmp = $u['params'];
		$u['params'] = Array();
		if (is_array($tmp)) for (reset($tmp);$key=key($tmp);next($tmp)) {
			$u['params'][str_replace('=','%3D',$key)] = ($tmp[$key]);
		}

		$this->params = $u['params'];
	}



	/**
	 * @return	[type]		...
	 */
	public function calculateOrganisation () {
		$this->activePage = intval(t3lib_div::_GP('pg'));
		if ($this->activePage<0) {
			$this->activePage = 0;
		}
		$this->result['active'] = $this->activePage;

		$this->result['cnt'] = $this->countTotalEntires;
		$this->result['numlist'] = $this->result['prev'] = $this->result['next'] = $this->result['first'] = $this->result['last'] = '';
		$this->result['maxPage'] = intval (($this->result['cnt']-1) / $this->countEntriesPerPage);
		if ($this->activePage > $this->result['maxPage']) {
			$this->activePage = $this->result['maxPage'];
		}
		$this->result['active'] = $this->activePage;

		$this->result['fromitem'] = ($this->activePage * $this->countEntriesPerPage) + 1;
		$this->result['toitem'] = $this->result['fromitem'] + $this->countEntriesPerPage - 1;
		if ($this->result['toitem'] > $this->result['cnt']) {
			$this->result['toitem'] = $this->result['cnt'];
		}
		$this->result['page'] = $this->activePage + 1; // // $this->result['pg'] + 1;
		$this->result['maxpages'] = $this->result['maxPage'] + 1;

		$this->debugObj->debugIf('pagebrowser',Array('$this->result'=>$this->result));
	}

	/**
	 * ********************************************************************************************
	 *
	 * User Functions
	 *
	 * *********************************************************************************************/


	/**
	 * @return	[type]		...
	 */
	public function getPageBrowser() {
		// $content = '--PageBrowser--';
	
		$m = $this->getPageBrowserMarkers();

		return ($m['###PAGEBROWSER###']);
	}

	/**
	 * @return	[type]		...
	 */

	public function getPageBrowserMarkers() {
		$m = Array();
		$this->calculateOrganisation();

		$iParams = t3lib_div::implodeArrayForUrl('',$this->params,'',1,0);
		$this->baseUrl = $this->getTypolinkURL($pageId,$iParams,0);
		if (!strpos($this->baseUrl,'?')) {
			$this->baseUrl .= '?';
		}

//TODO//	$idlist !!!

		if ($this->params[$this->lastPrefixId.'[searchmode]']==2) {
			$this->params[$this->lastPrefixId.'[searchmode]']=1;
		}
		unset ($this->params['resPP']);

		// TODO: $this->returnFromDetails is not yet defined
		if (is_array($this->returnFromDetails)) {
			$m['###RETURNBUTTON###'] = $this->returnFromDetails['link'];
		} else {
			$m['###RETURNBUTTON###'] = '';
		}

//		$active = (is_array($idlist) ? array_search($this->result[$this->conf['pointerName']],$idlist) : $this->result[$this->conf['pointerName']]);
		$m ['###NEXTBUTTON###'] = $m ['###PREVBUTTON###'] = '';

		$m['###COUNT###'] = $m['###PAGEBROWSER###'] = $m['###PAGES###'] = '';
		if ($this->countTotalEntires>0) {
			$m['###PAGES###']  = $this->pagebrowserWrap[0];
			if ($this->result['maxPage']>0 || $this->conf['pageBrowserAlsoSingle']) {
				//first
				if ($this->conf['showFirstLast']) {
					$myWrap = explode('|',$this->conf['firstWrap'],2);
					$index = 0;
					$this->params[$this->conf['pointerName']] = (is_array($idlist) ? $idlist[$index] : $index);
					$iParams = t3lib_div::implodeArrayForUrl('',$this->params,'',1,0);
					$url = $this->getTypolinkURL($pageId,$iParams,!$this->permitObj->useEditMode());
					$first = $this->confObj->TSObj($this->conf['first'],$this->conf['first.']);
					$firstDis = ($this->conf['firstDis']) ? $this->confObj->TSObj($this->conf['firstDis'],$this->conf['firstDis.']) : $first;
					$this->result['first'] = ($this->activePage>$index ? $myWrap[0].'<a href="'.$url.'">'.$first.'</a>'.$myWrap[1] : $firstDis);
					$m['###PAGES###'] .= $this->result['first'].$this->conf['spacing'].CRLF;
				}

				//prev
				$myWrap = explode('|',$this->conf['prevWrap'],2);
				$index = $this->activePage-1;
				$this->params[$this->conf['pointerName']] = (is_array($idlist) ? $idlist[$index] : $index);
				$iParams = t3lib_div::implodeArrayForUrl('',$this->params,'',1,0);
				$url = $this->getTypolinkURL($pageId,$iParams,!$this->permitObj->useEditMode());
				$prev = $this->confObj->TSObj($this->conf['prev'],$this->conf['prev.']);
				$prevDis = ($this->conf['prevDis']) ? $this->confObj->TSObj($this->conf['prevDis'],$this->conf['prevDis.']) : $prev;
				$this->result['prev'] = ($index>=0 ? $myWrap[0].'<a href="'.$url.'">'.$prev.'</a>'.$myWrap[1] : $prevDis);
				$m ['###PREVBUTTON###'] .= $this->result['prev'];
				$m ['###PAGES###'] .= $this->result['prev'].$this->conf['spacing'].CRLF;

				//numbers
				$activeWrap = explode('|',$this->conf['activeWrap'],2);
				$this->result['numlist'] = $this->numbersWrap[0];
				for ($index=0;$index<=$this->result['maxPage'];$index++) {
					$this->params[$this->conf['pointerName']] = (is_array($idlist) ? $idlist[$index] : $index);
					$iParams = t3lib_div::implodeArrayForUrl('',$this->params,'',1,0);
					$url = $this->getTypolinkURL($pageId,$iParams,!$this->permitObj->useEditMode());

					$this->result['numlist'] .= $index>0 ? $this->conf['numSpacing'] : '';
					$text = ($this->conf['showRange'] && $this->result['maxPP'])
						? ($index * $this->result['maxPP'] + 1).'-'.($index * $this->result['maxPP'] + $this->result['cnt'])  : $index+1;
					if ($index==$this->activePage) {
						$this->result['numlist'] .= $activeWrap[0].$text.$activeWrap[1].CRLF;
					} else {
						$this->result['numlist'] .= '<a href="'.$url.'">'.$text.'</a>'.CRLF;
					}
					if ($this->result['maxPage']>($this->conf['divider']+$this->conf['around']+1) && $index<$this->result['maxPage']) {
						if (  ($index + intval($this->result['maxPage'] / $this->conf['divider'])) < ($this->activePage-$this->conf['around']-1) || $index>($this->activePage+$this->conf['around']-1) ) {
							$index = $index + intval($this->result['maxPage'] / $this->conf['divider']);
						} else if  ( $index < ($this->activePage-$this->conf['around']-1) ) {
							$index = $this->activePage-$this->conf['around']-1;
						}
						if ($index>=$this->result['maxPage']) {
							$index = $this->result['maxPage'] - 1;
						}
					}
				}
				$this->result['numlist'] .= $this->numbersWrap[1];
				$m['###NUMLIST###'] = $this->result['numlist'];
				$m['###PAGES###'] .= $this->result['numlist'];

				// next
				$myWrap = explode('|',$this->conf['nextWrap'],2);
				$index = $this->activePage+1;
				$this->params[$this->conf['pointerName']] = (is_array($idlist) ? $idlist[$index] : $index);
				$iParams = t3lib_div::implodeArrayForUrl('',$this->params,'',1,0);
				$url = $this->getTypolinkURL($pageId,$iParams,!$this->permitObj->useEditMode());
				$next = $this->confObj->TSobj($this->conf['next'],$this->conf['next.']);
				$nextDis = ($this->conf['nextDis']) ? $this->confObj->TSobj($this->conf['nextDis'],$this->conf['nextDis.']) : $next;
				$this->result['next'] .= ($index<=$this->result['maxPage'] ? $myWrap[0].'<a href="'.$url.'">'.$next.'</a>'.$myWrap[1] : $nextDis);
				$m['###NEXTBUTTON###'] .= $this->result['next'];
				$m['###PAGES###'] .= $this->conf['spacing'].$this->result['next'].CRLF;

				// last
				if ($this->conf['showFirstLast']) {
					$myWrap = explode('|',$this->conf['lastWrap'],2);
					$index = $this->result['maxPage'];
					$this->params[$this->conf['pointerName']] = (is_array($idlist) ? $idlist[$index] : $index);
					$iParams = t3lib_div::implodeArrayForUrl('',$this->params,'',1,0);
					$url = $this->getTypolinkURL($pageId,$iParams,!$this->permitObj->useEditMode());
					$last = $this->confObj->TSobj($this->conf['last'],$this->conf['last.']);
					$lastDis = ($this->conf['lastDis']) ? $this->confObj->TSobj($this->conf['lastDis'],$this->conf['lastDis.']) : $last;
					$this->result['last'] .= ($this->activePage<$index ? $myWrap[0].'<a href="'.$url.'">'.$last.'</a>'.$myWrap[1]:$lastDis);
					$m['###PAGES###'] .= $this->conf['spacing'].$this->result['last'].CRLF;
				}
			}
			$m['###PAGES###']  .= $this->pagebrowserWrap[1];
			$this->result['pagebrowser'] = $m['###PAGES###'];
			$this->result['backlink'] = '';
			if ($this->conf['backlink']) {
				$this->result['backlink'] = $this->cObj->cObjGetSingle($this->conf['backlink'],$this->conf['backlink.']);
				if ($this->todo['BackUrl']) {
					$this->result['backlink'] = '<a href="'.$this->todo['BackUrl'].'">'.$this->result['backlink'].'</a>'.CRLF;
				}
			}

			$m ['###MYDESCR_ENTRIES###'] = '';
			if ($this->conf['countText'] && $this->result['cnt']>0) {
				$m ['###COUNT###'] = $this->cObj->substituteMarkerArray($this->conf['countText'], $this->result, '###|###');
			} else if ($this->conf['countNone'] && $this->result['cnt']<1) {
				$m ['###COUNT###'] = $this->conf['countNone'];
			} else {
				$m ['###COUNT###'] = $this->result['cnt']>0 ?
					sprintf($this->langObj->getLL('showFromToOfTotal'),$this->result['fromitem'],$this->result['toitem'],$this->result['total']) :
					sprintf($this->langObj->getLL('showNoneOfTotal'),$this->result['total']);
				$m ['###MYDESCR_ENTRIES###'] = $this->langObj->getLL('entries');
			}
			$m['###COUNT###'] = $this->showResultsWrap[0].$m['###COUNT###'].$this->showResultsWrap[1];

			if ($this->conf['format']) {
				$m['###PAGES###'] = $this->cObj->substituteMarkerArray($this->conf['format'], $this->result, '###|###');
			}

			$m ['###GOTOPAGE###'] = '';
			if ($this->conf['gotoPageAlsoSingle'] || $this->result['maxpages']>1) {
				$tmpName = 'Go!';
				if ($this->conf['gotoPageButton']) {
					$tmpName = $this->confObj->TSobj($this->conf['gotoPageButton'],$this->conf['gotoPageButton.']);
				}
				$wrap = t3lib_div::trimExplode('|',$this->conf['gotoPageWrap']);
				$m ['###GOTOPAGE###'] = $wrap[0].'<input type="text" size="4" id="sggotopage" name="sg_gotopage" '.$this->conf['gotoPageParams'].' value="" />'.CRLF.
					'<a href="'.$this->emptyUrl.'" onclick="sgGotoPage('.QT.$this->baseUrl.'&'.$this->conf['pointerName'].'='.QT.');return(false);">'.$tmpName.'</a>'.$wrap[1].CRLF;
	//				'<a href="'.$this->emptyUrl.'" onclick="alert('.QT.'gotoPage'.QT.'+this.parent.sg_gotopage.value);sgGotoPage('.QT.$this->baseUrl.'&'.$this->conf['pointerName'].'='.QT.');return(false);">'.$tmpName.'</a>'.$wrap[1].CRLF;
	//				'<a href="'.$this->emptyUrl.'" onclick="var url=document.sg_gotopage.value ;alert('.QT.'gotoPage'.QT.'+url);return(false);">'.$tmpName.'</a>'.$wrap[1].CRLF;
			}

			$m ['###RESULTSPP###'] = '';
			if ($this->conf['userResPP']) {
				$u = tx_sgdiv::parseUrl('-',Array('resPP'=>''));
				$pp = t3lib_div::intExplode(',',$this->conf['userResPP']);
				$wrap = t3lib_div::trimExplode('|',$this->conf['userResPPwrap']);
				if (count($pp)>0) {
					// safari only accepts onchane here ...
					$m ['###RESULTSPP###'] =  $wrap[0].'<select onchange="window.location='.
							QT.$this->baseUrl.'&pg=0&resPP='.QT.'+this.options[this.options.selectedIndex].value;">';
					for ($i=0;$i<count($pp);$i++) {
						$m ['###RESULTSPP###'] .= (intval($this->conf['entriesPerPage'])==$pp[$i]) ?
								'<option selected="selected" value="'.$pp[$i].'">' :
								'<option value="'.$pp[$i].'">';
						$m ['###RESULTSPP###'] .= $pp[$i].'</option>';
						//$m ['###RESULTSPP###'] .= ' <a href="'.$u['total'].'&resPP='.$this->confMaxPP.','.$pp[$i].'">'.$pp[$i].'</a>' .CRLF;
					}
					$m ['###RESULTSPP###'] .=  '</select>'.$wrap[1];
				}
			}

			$m['###PAGEBROWSER###'] = '';
			if ($this->conf['pageBrowser']) {
				if ($this->conf['pageBrowserAlsoSingle'] || $this->result['maxpages']>1) {
					$tmp = $this->confObj->TSobj($this->conf['pageBrowser'],$this->conf['pageBrowser.']);
					$wrap = t3lib_div::trimExplode('|',$this->conf['pageBrowserWrap']);
					$m['###PAGEBROWSER###'] = $wrap[0].$this->cObj->substituteMarkerArray($tmp,$m).$wrap[1];
				}
			}
		}

		$this->debugObj->debugIf('pagebrowser',Array('return ($m)'=>$m, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($m);
		}


	/**
	 * @param	[type]		$myPageID: ...
	 * @param	[type]		$myParams: ...
	 * @param	[type]		$allowCaching: ...
	 * @param	[type]		$myTarget: ...
	 * @param	[type]		$myDbg: ...
	 * @return	[type]		...
	 */
	function getTypolinkURL ($myPageID, $myParams='', $allowCaching=0, $myTarget='', $myDbg='') {
			if (!$myTarget) {
				$myTarget='_self';
			}
			$this->cObj->setCurrentVal(($myPageID ? $myPageID : $GLOBALS['TSFE']->id));
			$this->typolink_conf['parameter.']['current'] = 1;
			$this->typolink_conf['useCacheHash'] = $allowCaching;
			$this->typolink_conf['no_cache'] = 0; //!$allowCaching;
			$this->typolink_conf['extTarget'] = $myTarget;
			$this->typolink_conf['target'] = $myTarget;

			$temp_conf = $this->typolink_conf;
			$temp_conf['additionalParams'] .= $myParams;

			$url = $this->cObj->TypoLink_URL($temp_conf);
			if ($myDbg) t3lib_div::debug(Array('getTypolinkURL'=>$url, '$myPageID'=>$myPageID, '$myDbg'=>$myDbg));
			return ( $url );
	}




}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sglib_pagebrowser.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sglib_pagebrowser.php']);
}
?>