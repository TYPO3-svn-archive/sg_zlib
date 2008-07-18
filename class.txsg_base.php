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
 *   84: class txsg_base extends tslib_pibase
 *  116:     function main($content,$conf)
 *  230:     function preprocessPluginMode($pluginMode)
 *  260:     function switchPluginMode ($pluginMode)
 *  440:     function setDefaultSearch ()
 *  483:     function DoSearch($pluginMode=1)
 *  521:     function getSearchBox($pluginMode)
 *  579:     function getDbUserSearchFields ($dbName,$PCA,$piVarSearch,$markers=Array(),$dbg=0)
 *  589:     function DoList()
 *  675:     function getResultList($orderBy='',$limitTo='',$restrict='')
 * 1010:     function DoSingle()
 * 1049:     function getShowEdit()
 * 1669:     function doLatestList ()
 * 1701:     function doLatestView ()
 * 1756:     function doCatMenu ()
 * 1826:     function listCatMenu(&$idList,$level)
 * 1891:     function DoSimpleBasket()
 * 1903:     function getRow ($uid)
 * 1978:     function getEditButtons ($mode,$row,&$PCA,$listitle='')
 * 2098:     function processHideDelete ($mode)
 * 2209:     function DoImport()
 * 2226:     function DoInfo()
 * 2269:     function DoServiceLinks()
 * 2282:     function getMoreMenuMarkers($row,$PCA)
 * 2295:     function getMoreMarkers($row,$PCA)
 * 2308:     function getAckMarkers($row,$PCA)
 * 2319:     function checkAck()
 * 2332:     function getListSummaryMarkers($r,$PCA)
 * 2350:     function getLocalDbRow($mainTable,$n,$row,$PCA, $markers=Array(),$em,$dbg=0,$mode=0)
 * 2379:     function getLocalHeaders($table,$PCA,$rowHeaders=Array(),$dbg=0)
 * 2408:     function localCheckDbRowForSave ($dbName,&$row,&$PCA,&$errors)
 * 2421:     function localPrepareDbRow ($dbName,&$row,&$PCA)
 * 2436:     function prepareNewRecord ($dbName,&$row,&$PCA)
 * 2450:     function checkListOutput ($mode,$row,$PCA,$markers)
 * 2460:     function getQuotas ()
 * 2500:     function checkQuotas ($myQuota)
 * 2550:     function checkForIframe($row)
 * 2559:     function registerAjaxFunctions ()
 * 2573:     function preprocessAjax ()
 * 2589:     function xajax_process_DemoData ($data)
 * 2615:     function pi_linkTP_URL($urlParameters=array(),$cache=0,$altPageId=0)
 *
 * TOTAL FUNCTIONS: 40
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class txsg_base extends tslib_pibase {
	var $prefixId = 'tx_sgzz_pi1';		// Same as class name
	var $callcountname = 'tx_sgzz_pi1_cc';
	var $scriptRelPath = 'pi1/class.tx_sgzz_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey = 'sg_zz';	// The extension key.
	var $mainTable = '';
	var $mainJoin = '';
	var $mainJoinTable = '';
	var $catTable = '';
	var $subcatTable = '';
	var $winName = 'Zz';
	var $pi_checkCHash = TRUE;
	var $pi_USER_INT_obj;

	var $factoryObj;
	var $confObj;
	var $debugObj;
	var $constObj;
	var $paramsObj;
	var $templateObj;
	var $permitObj;
	var $langObj;
	var $itemsObj;
	var $divObj;

	/**
	 * [Put your description here]
	 *
	 * @param	[type]		$content: ...
	 * @param	[type]		$conf: ...
	 * @return	[type]		...
	 */
	function main($content,$conf)	{
		GLOBAL $TSFE, $TYPO3_LOADED_EXT;

		$this->pTime = Array();
		list($usec, $sec) = explode(' ',microtime()); $s = (((float)$usec + (float)$sec) * 1000);
		$this->factoryObj = tx_sglib_factory::getInstance($this->prefixId, $this->cObj, $conf);
		$this->factoryObj->setBaseTables($this->mainTable, array()) ;

		$this->confObj = $this->factoryObj->confObj;
		$this->debugObj = $this->factoryObj->debugObj;
		$this->divObj = $this->factoryObj->divObj;
		$this->paramsObj = $this->factoryObj->paramsObj;
		$this->constObj = $this->factoryObj->constObj;
		$this->langObj = $this->factoryObj->langObj;
		$this->permitObj = $this->factoryObj->permitObj;
		$this->templateObj = $this->factoryObj->templateObj;
		$this->itemsObj = $this->factoryObj->itemsObj;

		$this->conf = $this->confObj->getCombined();
		$this->insertDetails = '';
		$this->insertUID = 0;

		$this->pi_USER_INT_obj = 1;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		$this->myPage = $this->pi_getPageLink($TSFE->id,'','');
		if (!strstr($this->myPage,'?')) {
			$this->myPage .= '?';
		}
		$this->linkLink = explode('|',$this->conf['linkLink']);

		$this->felib = t3lib_div::makeInstance('tx_sgzlib');
		$this->felib->init($this->prefixId,$this->factoryObj);

		$this->pid = $this->confObj->getPid();
		$this->pid_list = $this->confObj->getPidList();

		$this->confObj->setParentObject($this->cObj);
		$pluginMode = $this->confObj->getFFvalue('fieldModuleMode','sDefault');
		$dbg = $this->confObj->getFFvalue('fieldDebugMode','sDefault');

		$this->listPage = $this->confObj->getFFvalue('fieldListPage','sDefault');
		if (intval($this->listPage)<1) { $this->listPage = intval($this->conf['PIDlistDisplay']); }
		if (intval($this->listPage)<1) { $this->listPage = $TSFE->id; }
		$this->felib->listPage = $this->listPage;

		$this->editPage = $this->confObj->getFFvalue('fieldEditPage','sDefault');
		if (intval($this->editPage)<1) { $this->editPage = intval($this->conf['PIDitemDisplay']); }
		if (intval($this->editPage)<1) { $this->editPage = $TSFE->id; }

		$this->editPopup = $this->confObj->getFFvalue('fieldEditPopup','sDefault');
		if (intval($this->conf['alwaysViewInPopup'])==1) {
			$this->editPopup = TRUE;
		} else if (intval($this->conf['neverViewInPopup'])==1) {
			$this->editPopup = FALSE;
		}

		$this->popupW = (isset($this->conf['popup.']['width'])) ? $this->conf['popup.']['width'] : 640;
		$this->popupH = (isset($this->conf['popup.']['height'])) ? $this->conf['popup.']['height'] : 480 ;

		$this->useLocking = $this->conf['useLocking'];

		$content .= $this->confObj->addJs('userFunctions');
		$content .= $this->confObj->addJs('searchFunctions');
		if ($this->permitObj->useEditMode()) {
			$content .= $this->confObj->addJs('popupFunctions');
			$TSFE->set_no_cache();
		}

		$this->PCA = $this->felib->getPCA($this->mainTable,$this->prefixId,$this->pid_list,$this->conf,$this->piVars,Array(),$dbg);
		$this->dbg = $this->felib->dbg;
		$this->disableField = (is_array($this->PCA['ctrl']['enablecolumns'])) ? $this->PCA['ctrl']['enablecolumns']['disabled'] : '';
		$this->fixedField = ($this->PCA['ctrl']['fixedField']) ? $this->PCA['ctrl']['fixedField'] : '';
		$this->editMessage = '';
		$this->getQuotas();
		$this->returnEditToList = FALSE;

		$confXajax = $this->confObj->xajax;
		if ($confXajax['try'] || $confXajax['force']) {
			if (isset($TYPO3_LOADED_EXT['xajax']))   {
				$this->felib->prepareXajax($this->prefixId);
				$ret = $this->registerAjaxFunctions ();
				$ret = $this->preprocessAjax();
				$this->debugObj->debugIf('xajax',Array('xaJax(debug)'=>'('.$this->felib->xajax->bDebug.')', 'prefixId='=>$this->prefixId, 'registerAjaxFunctions'=>$ret, 'File:Line'=>__FILE__.':'.__LINE__));
			} else if ($confXajax['force']) {
				//t3lib_div::debug(Array('$TYPO3_LOADED_EXT'=>$TYPO3_LOADED_EXT, 'File:Line'=>__FILE__.':'.__LINE__));
				die ('--- ERROR: xaJax missing or not loaded ! ---');
			}
		}

		$pluginMode = $this->preprocessPluginMode($pluginMode);

		$tmp = $this->switchPluginMode($pluginMode);
		if (is_array($this->conf['stdWrapAll.'])) {
			$tmp = $this->cObj->stdWrap($tmp,$this->conf['stdWrapAll.']);
		}

		$content .= $this->pi_wrapInBaseClass($tmp).
			'<!-- '.$this->prefixId.' duration: '.intval($this->divObj->getMicrodur($s)).' ms ('.implode(', ',$this->pTime).') -->'.CRLF;

		if (is_array($this->piLog) && count($this->piLog)) {
			$content .= t3lib_div::view_array(Array('piLog'=>$this->piLog, 'File:Line'=>__FILE__.':'.__LINE__));
		}

		return ($content);
	}

	/**
	 * Sets Pluginmode, if plugin is placed by TS
	 *
	 * @param	[type]		$pluginMode: ...
	 * @return	[type]		...
	 */
	function preprocessPluginMode($pluginMode) {
		if (strcmp($this->conf['CMD'],'singleView')==0) {
			$pluginMode = 300;
			$this->PCA['todo']['Uid'] = $this->cObj->data['uid'];
		} else if (strcmp($this->conf['CMD'],'listView')==0) {
			$pluginMode = 200;
		} else if (strcmp($this->conf['CMD'],'searchListView')==0) {
			$pluginMode = 301;
		} else if (strcmp($this->conf['CMD'],'searchView')==0) {
			$pluginMode = 100;
		} else if (strcmp($this->conf['CMD'],'selectView')==0) {
			$pluginMode = 110;
		} else if (strcmp($this->conf['CMD'],'latestList')==0) {
			$pluginMode = 400;
		} else if (strcmp($this->conf['CMD'],'latestView')==0) {
			$pluginMode = 410;
		} else if (strcmp($this->conf['CMD'],'catMenu')==0) {
			$pluginMode = 420;
		} else if (strcmp($this->conf['CMD'],'simpleBasket')==0) {
			$pluginMode = 500;
		}
		return ($pluginMode);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$pluginMode: ...
	 * @return	[type]		...
	 */
	function switchPluginMode ($pluginMode) {
		$GLOBALS['_GET'][$this->callcountname]++;
		$content = '';
		if ($GLOBALS['_GET'][$this->callcountname]==1 && $this->conf['static.']['notset']==1) {
				$content .= $this->constObj->getWrap('hot','No Static Template selected nor valid own Template created: '.$this->prefixId.'->conf<hr />');
		}

		$this->pluginMode = $pluginMode;
		$cm = Array('###SEARCHBLOCK###'=>'', '###SELECTBLOCK###'=>'', '###LISTBLOCK###'=>'', '###DETAILBLOCK###'=>'');
		$blockTmpl = '';
		if (intval($pluginMode)==0 || $pluginMode==1 || $pluginMode==100 || $pluginMode==30 || $pluginMode==32 || $pluginMode==301) {
			$s = $this->divObj->getMicroSec();
			$cm['###SEARCHBLOCK###'] = $this->DoSearch($pluginMode);
			$this->pTime[] = 'search='.intval($this->divObj->getMicroDur($s));
		}
		if ($pluginMode==11 || $pluginMode==110 || $pluginMode==33) {
			$s = $this->divObj->getMicroSec();
			$cm['###SELECTBLOCK###'] = $this->DoSearch($pluginMode);
			$this->pTime[] = 'search='.intval($this->divObj->getMicroDur($s));
		}
		if (intval($pluginMode)==0 || $pluginMode==3 || $pluginMode==31 || $pluginMode==32 || $pluginMode==300 ||
			($this->conf['detailsInList']==1 && (intval($pluginMode)==2 || $pluginMode==200)) ) {
			$this->felib->returnFromDetails = $GLOBALS['TSFE']->fe_user->getKey('ses',$this->prefixId.'.returnFromDetails');
			$s = $this->divObj->getMicroSec();

			if ($pluginMode==300) {
				$this->PCA['todo']['Uid'] = $this->cObj->data['uid'];
				if ($this->PCA['templateSingleRecord']) {
					$this->PCA['templateSingle'] = $this->PCA['templateSingleRecord'];
				}
			} else {
				$uid = intval(t3lib_div::GPvar('uid'));
				if (!$uid) {
					$uid = intval($this->piVars['uid']);
					$this->PCA['todo']['Uid'] = $uid;
				}
				$pUid = t3lib_div::GPvar('pUid');
				if ($uid==0 && (strcmp($pUid,'0')==0 || intval($pUid) ) ) {
					$idList = explode(',',$this->PCA['todo']['LastIdList']);
					$pUid = ($pUid>=count($idList)? count($idList)-1 : $pUid);
					$pUid = intval($pUid)<0 ? 0 : intval($pUid);
					$uid = $idList[$pUid];
					$this->PCA['todo']['Uid'] = $uid;
				}
				if ($uid==0 && intval($this->conf['single.']['defaultUid'])) {
					$uid = intval($this->conf['single.']['defaultUid']);
					$this->PCA['todo']['Uid'] = $uid;
				}

				$title = $GLOBALS['TYPO3_DB']->quoteStr(urldecode(trim(t3lib_div::GPvar('title'))),$this->mainTable);
				if (strlen($title)>0 && $uid==0) {
					$this->uniqueTitleField = trim($this->conf['uniqueTitleField']) ? trim($this->conf['uniqueTitleField']) : 'word';
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid',$this->mainTable,$this->uniqueTitleField.' LIKE '.QT.$title.QT,'','','0,1');
					if ($res) {
						$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
						if (!$this->PCA['todo']['New'] && !$this->PCA['todo']['NewFromUid'] &&
							!$this->PCA['todo']['Edit'] && !$this->PCA['todo']['Reload']) {
							$this->langObj->replaceLangOverlay($row,$this->mainTable);
						}
						$this->PCA['todo']['Uid'] = $row['uid'];
					}
				}
			}

			if ($this->conf['detailsInList']==1 && (intval($pluginMode)==2 || $pluginMode==200)) {
				$this->insertUID = $this->PCA['todo']['Uid'];
				$this->insertDetails  = $this->DoSingle();
			} else {
				$cm['###DETAILBLOCK###'] =  $this->DoSingle();
			}
			$this->pTime[] = 'single='.intval($this->divObj->getMicroDur($s));
		}
		if (intval($pluginMode)==0 || intval($pluginMode)==2 || ($pluginMode>=30 && $pluginMode<=33)  ||
				$pluginMode==301 || $pluginMode==200) {
			$s = $this->divObj->getMicroSec();
			$cm['###LISTBLOCK###'] = $this->DoList();
			$this->pTime[] = 'list='.intval($this->divObj->getMicroDur($s));
		}

		switch ($pluginMode) {
			case 1:
			case 100:
				$content .= $cm['###SEARCHBLOCK###'];
			break;
			case 11:
			case 110:
				$content .= $cm['###SELECTBLOCK###'];
			break;
			case 2:
			case 200:
				$content .= $cm['###LISTBLOCK###'];
			break;
			case 300:
			case 3:
				$content .= $cm['###DETAILBLOCK###'];
			break;
			case 30:
			case 301:
					$blockTmpl = '###SEARCHANDLIST###';
			break;
			case 31:
					$blockTmpl = '###LISTANDVIEW###';
			break;
			case 0:
			case 32:
					$blockTmpl = '###SEARCHANDLISTANDVIEW###';
			break;
			case 33:
					$blockTmpl = '###SELECTANDLIST###';
			break;
			case 40:
			case 400:
				$content .= $this->DoLatestList();
			break;
			case 41:
			case 410:
				$content .= $this->DoLatestView();
			break;
			case 42:
			case 420:
				$content .= $this->DoCatMenu();
			break;
			case 50:
			case 500:
				$content .= $this->DoSimpleBasket();
			break;
			case 80:
				$content .= $this->DoServiceLinks();
			break;
			case 1040:
				$content .= $this->DoImport();
			break;
			case 95:
				$GLOBALS['TSFE']->fe_user->setKey('ses',$this->prefixId.'.lastQuery',t3lib_div::getIndpEnv('TYPO3_REQUEST_URL'));
				$content .= '';
			break;
			case 99:
				$content .= $this->DoInfo();
			break;
			default:
				$content .= 'No Plugin-Mode set: '.$this->scriptRelPath.'->main('.$pluginMode.')!';
			break;
		}

		$dbgS = Array('searchBlock'=>strlen($cm['###SEARCHBLOCK###']),
			'selectBlock'=>strlen($cm['###SELECTBLOCK###']),
			'listBlock'=>strlen($cm['###LISTBLOCK###']),
			'detailsBlock'=>strlen($cm['###DETAILBLOCK###']),
			'BlockTemplate='=>$blockTmpl,
			'content'=>strlen($content),
			);
		$this->debugObj->debugIf('pluginMode',Array('$pluginMode'=>$pluginMode, 'L='=>t3lib_div::GPvar('L'), 'strlen()'=>$dbgS, 'File:Line'=>__FILE__.':'.__LINE__));
		$this->debugObj->debugIf('pluginModeFull',Array('$pluginMode'=>$pluginMode, 'L='=>t3lib_div::GPvar('L'), '$cm'=>$cm, 'BlockTemplate='=>$blockTmpl, 'content'=>$content, 'File:Line'=>__FILE__.':'.__LINE__));

		if ($blockTmpl) {
			if (!intval($this->PCA['todo']['Uid']) && !intval($this->PCA['todo']['New']) && !intval($this->PCA['todo']['Reload']) ) {
				$cm['###DETAILBLOCK###'] = '';
			}
			$cm['###DETAILS###'] = $cm['###DETAILBLOCK###'];
			$tmp = $this->templateObj->getTemplate('block');
			$blockTmpl = $this->templateObj->getSubpart ($tmp,$blockTmpl);
			if (!strlen($blockTmpl)) {
				 $blockTmpl = $cm['###LISTBLOCK###'];
				 $cm['###LISTBLOCK###'] = '';
			}
			if (!intval($pluginMode)) {
				$blockTmpl = $this->templateObj->getSubpart ($tmp,'###DEFAULTTEXT###').$blockTmpl;
			}
			$content .= $this->cObj->substituteMarkerArray($blockTmpl,$cm);
		} else {
			$content = str_replace ('###DETAILS###','',$content);
		}
		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function setDefaultSearch () {
		// Get/Put Session Data: remember search-params for next visit of this seach-modul
		if (intval($this->piVars['searchmode'])<1) {
			if (!intval($this->conf['search.']['noDefaultSearch'])) {
				$this->piVars['search'] = $GLOBALS['TSFE']->fe_user->getKey('ses',$this->prefixId.'.piVars.search');
				if (is_array($this->piVars['search'])) {
					$this->piVars['searchmode']=1;
				} else {
					$this->piVars['search'] = Array();
				}
				if (strcasecmp($this->PCA['ctrl']['defaultSearch'],'own')==0 && !$this->permitObj->allowed('admin')) {
					$this->piVars['search'][$this->PCA['ctrl']['crfeuser_id']] = $this->permitObj->getFeUid();
				} else if (strcasecmp($this->PCA['ctrl']['defaultSearchAdmin'],'own')==0 && $this->permitObj->allowed('admin')) {
					$this->piVars['search'][$this->PCA['ctrl']['crfeuser_id']] = $this->permitObj->getFeUid();
				}
			} else {
				$GLOBALS['TSFE']->fe_user->setKey('ses',$this->prefixId.'.piVars.search','');
				$this->piVars['search'] = Array();
			}
		} else {
			if (!is_array($this->piVars['search'])) {
				$this->piVars['search'] = Array();
			}
			$GLOBALS['TSFE']->fe_user->setKey('ses',$this->prefixId.'.piVars.search',$this->piVars['search']);
		}

		if (!$this->piVars['search'][$this->PCA['ctrl']['crfeuser_id']]) {
			if (strcasecmp($this->PCA['ctrl']['defaultSearch'],'own')==0 && !$this->permitObj->allowed('admin')) {
				$this->piVars['search'][$this->PCA['ctrl']['crfeuser_id']] = $this->permitObj->getFeUid();
			} else if (strcasecmp($this->PCA['ctrl']['defaultSearchAdmin'],'own')==0 && $this->permitObj->allowed('admin')) {
				$this->piVars['search'][$this->PCA['ctrl']['crfeuser_id']] = $this->permitObj->getFeUid();
			}
		}
	}



	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$pluginMode: ...
	 * @return	[type]		...
	 */
	function DoSearch($pluginMode=1)	{
		GLOBAL $TSFE;

		if ($pluginMode==11 || $pluginMode==33) {
			$this->felib->sMode = 'form_select_'.$this->prefixId;
		} else {
			$this->felib->sMode = 'form_'.$this->prefixId;
		}

		$TSFE->set_no_cache();
		if ($this->debugObj->isDebug('cache')) {
			$content .= '<H1><font color="red">Set_No_Cache !</font></H1>';
		}

		$content = ''; //
		$this->globalMarkers = Array();
		$this->markers = Array();

		// Get/Put Session Data: remember search-params for next visit of this seach-modul
		$this->setDefaultSearch();
		$this->PCA['piVars'] = $this->piVars;

		$this->rowHeaders = $this->felib->getDbHeaders($this->mainTable,$this->PCA,Array(),$this->debugObj->isDebug('headerMarkers'));
		$this->rowHeaders = $this->getLocalHeaders($this->mainTable,$this->PCA,$this->rowHeaders,$this->debugObj->isDebug('headerMarkers'));
		$this->template = $this->templateObj->getTemplate('list',$this->globalMarkers);

		$this->returnEditToList = $this->confObj->list[returnEditToList];
		$content .= $this->getSearchBox($pluginMode);

		return $content;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$pluginMode: ...
	 * @return	[type]		...
	 */
	function getSearchBox($pluginMode)	{
		GLOBAL $TSFE;

		$content = '';
		$spName = $this->templateObj->getSubPartName(($pluginMode==11 || $pluginMode==33) ? 'select' : 'search');

		$this->mSearch = $this->felib->getDbSearchFields ($this->mainTable,$this->PCA,$this->piVars['search'],$this->markers,0);
		$this->mUSearch = $this->getDbUserSearchFields ($this->mainTable,$this->PCA,$this->piVars['search'],$this->mSearch,0);
		$this->searchTmpl = $this->templateObj->getConfSubpart($this->template,$spName,$this->PCA);
		$m = array_merge ($this->mUSearch,$this->rowHeaders);
		$xm = $this->getEditButtons (4,$row,$this->PCA);
		if (is_array($xm)) { $m = array_merge($m,$xm); }

		$wrapHiddenRL = t3lib_div::trimExplode('|',$this->conf['search.']['wrapHiddenReloadFields']);
		$wrapHiddenSF = t3lib_div::trimExplode('|',$this->conf['search.']['wrapHiddenSearchFields']);

		if (!intval($this->conf['search.']['disableReloadForm'])) {
			$formText = '<form name="reload'.$this->felib->sMode.'" id="reload'.$this->felib->sMode.'">'.$wrapHiddenRL[0].CRLF.
						//'<input type="hidden" name="no_cache" value="1">'.
						'<input type="hidden" name="id" value="'.$TSFE->id.'">'.CRLF.
						(($TSFE->type>0) ?'<input type="hidden" name="type" value="'.$TSFE->type.'">':'').CRLF.
						(t3lib_div::GPvar('L') ? '<input type="hidden" name="L" value="'.t3lib_div::GPvar('L').'">':'').
						(t3lib_div::GPvar('rTL') ? '<input type="hidden" name="rTL" value="1">':'').
						'<input type="hidden" name="doReload" value="'.($this->felib->getTypolinkURL($TSFE->id,'', 0)).'">'.CRLF.
					$wrapHiddenRL[1].'</form>'.CRLF;
		}
		if (!intval($this->conf['search.']['disableFormTag'])) {
			//$formText .='<form name="search'.$this->felib->sMode.'" method="GET" action="'.
			//	$this->felib->getTypolinkURL($this->listPage,'', 0).'">';
			$formText .='<form name="search'.$this->felib->sMode.'" id="search'.$this->felib->sMode.'" action="index.php"'.
				($this->PCA['class']['formSearch'] ?  ' class="'.$this->PCA['class']['formSearch'].'"' : '').
				'>'.CRLF; // better with realurl and so on
			$formText .= '<input type="hidden" name="'.$this->prefixId.'[searchformname]" value="'.$this->felib->sMode.'">'.CRLF;
		}
		$formText .= $wrapHiddenSF[0].'<input type="hidden" name="id" value="'.($this->listPage ? $this->listPage:$TSFE->id).'">'.
				(($TSFE->type>0) ?'<input type="hidden" name="type" value="'.$TSFE->type.'">':'').CRLF.
				//'<input type="hidden" name="no_cache" value="1">'.CRLF.
				(t3lib_div::GPvar('L') ? '<input type="hidden" name="L" value="'.t3lib_div::GPvar('L').'">':'').
				(t3lib_div::GPvar('rTL') ? '<input type="hidden" name="rTL" value="1">':'').
				'<input type="hidden" name="'.$this->prefixId.'[searchmode]" value="1">'.$wrapHiddenSF[1].CRLF.
				$this->cObj->substituteMarkerArray($this->searchTmpl,$m);
		if (!intval($this->conf['search.']['disableFormTag'])) {
					$formText .= CRLF.'</form>'.CRLF;
		}
		$content .= $formText;
		return $content;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$dbName: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$piVarSearch: ...
	 * @param	[type]		$markers: ...
	 * @param	[type]		$dbg: ...
	 * @return	[type]		...
	 */
	function getDbUserSearchFields ($dbName,$PCA,$piVarSearch,$markers=Array(),$dbg=0) {
		return ($markers);
	}


	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function DoList()	{
		GLOBAL $TSFE;

		$this->doTotalList = $this->conf['doTotalList'];

		if (isset($this->PCA['ctrl']['listSubPartField'])) {
			$this->PCA['ctrl']['subPartField'] = $this->PCA['ctrl']['listSubPartField'];
		}
		if (isset($this->PCA['ctrl']['listSubPartModes'])) {
			$this->PCA['ctrl']['subPartModes'] = $this->PCA['ctrl']['listSubPartModes'];
		}

		if (intval($this->piVars['searchmode'])==128) {
			$content = '<!-- searchmode=128; no List -->';
		} else {
			$content = '';
			if (intval($this->piVars['searchmode'])<2) {
				$tmp = Array('link'=>'<a href="'.t3lib_div::getIndpEnv('TYPO3_REQUEST_URL').'">'.$this->constObj->getConst('return_to_result').'</a>',
					'name'=>'Searchresult');
			} else {
				$tmp = $GLOBALS['TSFE']->fe_user->getKey('ses',$this->prefixId.'.returnFromDetails');
				if (!is_array($tmp)) {
					$tmp = Array('name'=>'');
				}
				$tmp['link'] = '<a href="'.t3lib_div::getIndpEnv('TYPO3_REQUEST_URL').'">'.
					sprintf($this->constObj->getConst('return_to_result'),strtolower($tmp['name'])).'</a>';
			}
			$GLOBALS['TSFE']->fe_user->setKey('ses',$this->prefixId.'.returnFromDetails',$tmp);

			$this->globalMarkers = Array();
			$this->markers = Array('###MYDESCR_ENTRIES###'=>'', '###COUNT###'=>'', '###ENTRY###'=>'', '###PAGES###'=>'', '###SEARCHED_ABC_KEY###'=>'');

			if ($this->PCA['todo']['Hide']>0 || $this->PCA['todo']['UnHide']>0 || $this->PCA['todo']['Delete']>0 ) {
				$content .= $this->processHideDelete(0);
				$params = $TSFE->fe_user->getKey('ses',$this->prefixId.'.lastParams');
				$this->activePageInList = is_array($params) ? $params['pg']: 0;
				$this->felib->myQuery = $TSFE->fe_user->getKey('ses',$this->prefixId.'.lastQuery');
				if (strpos($this->felib->myQuery,'?')<1) { $this->felib->myQuery .= '?'; }
			} else if (intval($this->piVars['searchmode'])!=2) { // because of side-col listings !
				$this->felib->myQuery = t3lib_div::getIndpEnv('TYPO3_REQUEST_URL');
				$TSFE->fe_user->setKey('ses',$this->prefixId.'.lastQuery',$this->felib->myQuery);
				$u = $this->felib->myparseURL($this->felib->myQuery);
				$TSFE->fe_user->setKey('ses',$this->prefixId.'.lastParams',$u['plist']);
				$tmp = intval($this->piVars['pg']);
				$this->activePageInList = ($tmp>0) ? $tmp-1 : intval(t3lib_div::GPvar('pg'));
				if (strpos($this->felib->myQuery,'?')<1) { $this->felib->myQuery .= '?'; }
			}

			// Get/Put Session Data: remember search-params for next visit of this seach-modul
			$this->setDefaultSearch();

			if (intval($this->PCA['ctrl']['emptySearch'])>0 && intval($this->piVars['searchmode'])<1) {
				$this->piVars['searchmode'] = intval($this->PCA['ctrl']['emptySearch']);
			}

			$this->PCA['piVars'] = $this->piVars;

		}

		$this->rowHeaders = $this->felib->getDbHeaders($this->mainTable,$this->PCA,Array(),$this->debugObj->isDebug('headerMarkers'));
		$this->rowHeaders = $this->getLocalHeaders($this->mainTable,$this->PCA,$this->rowHeaders,$this->debugObj->isDebug('headerMarkers'));

		if (is_array($xm)) { $this->rowHeaders = array_merge($this->rowHeaders,$xm); }

		$this->template =  $this->templateObj->getTemplate('list',$this->globalMarkers);

		if ( ($this->piVars['searchmode']>0 || $this->activePageInList>0 || $this->pluginMode==31 || $this->pluginMode==32)
			&& intval($this->piVars['searchmode'])!=128 ){
			$content .= $this->getResultList();
		} else if (strpos($this->template,'###EMPTY_PART')>1) {
			$this->listTmpl = $this->templateObj->getConfSubpart($this->template,'EMPTY_PART',$this->PCA);
			$content .= $this->cObj->substituteMarkerArray(
				$this->cObj->substituteMarkerArray($this->listTmpl, $this->rowHeaders), $this->markers);
		}

		return $content;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$orderBy: ...
	 * @param	[type]		$limitTo: ...
	 * @param	[type]		$restrict: ...
	 * @return	[type]		...
	 */
	function getResultList($orderBy='',$limitTo='',$restrict='')	{
		GLOBAL $TSFE;

		$content = '';
		$altMaxCount = intval ($this->confObj->list['altColor.']['count']);
		$altColorName = $this->confObj->list['altColor.']['name'] ? $this->confObj->list['altColor.']['name'] : 'altcolor';
		$this->returnEditToList = $this->confObj->list['returnEditToList'];

		$this->debugObj->debugIf('search',Array('pivars[searchmode]'=>$this->piVars['searchmode'], 'pivars[search]'=>$this->piVars['search'],
			'File:Line'=>__FILE__.':'.__LINE__));

		$lpName = $this->templateObj->getSubPartName('list');
		$ehName = $this->templateObj->getSubPartName('entry_header');
		$epName = $this->templateObj->getSubPartName('entry_part');

		if (isset($this->piVars['listmode'])) {
			$this->piVars['search']['listmode'] = $this->piVars['listmode'];
		}
		$query = $this->felib->getDbBuildQuery ($this->mainTable,$this->PCA,$this->piVars['search'],$this->markers,0);
		$order = $this->felib->getDbBuildOrder ($this->mainTable,$this->PCA,$this->piVars['search'],$this->markers,0);
		$group = $this->felib->getDbBuildGroup ($this->mainTable,$this->PCA,$this->piVars['search'],$this->markers,0);
		$searchParamCount = $this->felib->searchParamCount;
		if ($this->piVars['searchmode']>0) {
			if (!is_array($this->piVars['search'])) {
				$searchParamCount++;
			} else if (count($this->piVars['search'])<1) {
				$searchParamCount++;
			}
		}

		$query = $query.($query && $restrict ? ' AND ' : '').$restrict;

		if ($searchParamCount>0 || intval ($this->PCA['ctrl']['emptySearch']) || $this->activePageInList>0) {
			$qa = Array();
			$qa['SELECT'] = ($this->PCA['ctrl']['listFields'] ? $this->PCA['ctrl']['listFields'] : $this->mainTable.'.*').
				$this->mainJoin.$this->PCA['mmAll']['select'];
			if (count($this->PCA['ctrl']['calc'])>0) {
				$qa['SELECT'] .= ','.implode(',',$this->PCA['ctrl']['calc']);
			}

			$qa['FROM'] =  str_replace('###feuser.uid###',$this->permitObj->getFeUid(),$this->mainTable.$this->mainJoinTable.$this->PCA['mmAll']['join']);
			$qa['UIDonly'] =  $this->mainTable.'.uid';
			$qa['WHERE'] = $query ;
			$qa['GROUP'] = $group ? $group : $this->mainTable.'.uid';
			$qa['ORDER'] = ($orderBy ? $orderBy.', ' : '' ).trim($order);
			$qa['LIMIT'] = $limitTo;
			$qa['q[]'] = $this->felib->lastGetDbBuildQuery;

			$qa['pg'] = $this->activePageInList;

			// get last set from from session
			$tmp = intval($TSFE->fe_user->getKey('ses',$this->prefixId.'.piVars.resPP'));
			$this->felib->confMaxPP = ($tmp>2 && $tmp<500) ? $tmp : intval($this->conf['list.']['maxPerPage']);
			$tmp = t3lib_div::intExplode(',',t3lib_div::GPvar('resPP'),2);
			if (count($tmp)==2) {
				$this->felib->confMaxPP = $tmp[1];
				$oldPg = $qa['pg'];
				$qa['pg'] = intval(($tmp[0] * $qa['pg']) / $tmp[1]);
				$TSFE->fe_user->setKey('ses',$this->prefixId.'.piVars.resPP',$this->felib->confMaxPP);
			} else if (count($tmp)==1 && $tmp[0]) {
				if ($tmp[0]>2 && $tmp[0]<500) {
					$this->felib->confMaxPP = $tmp[0];
					$TSFE->fe_user->setKey('ses',$this->prefixId.'.piVars.resPP',$this->felib->confMaxPP);
				}
			} else if (intval(t3lib_div::GPvar('resTmpPP'))>2) {
				$this->felib->confMaxPP = intval(t3lib_div::GPvar('resTmpPP'));
			}

			$qa['maxPP'] = $this->felib->confMaxPP;
			if (intval($qa['maxPP'])<1) {
				$this->felib->confMaxPP = $qa['maxPP'] = 20;
			}

			$qa['doTotalList'] = $this->doTotalList;
			$qa['doGetMaxList'] = (intval($this->conf['doGetMaxList'])>9 ? intval($this->conf['doGetMaxList']) : 10000);
			$r = $this->felib->getDbList ($qa,$this->debugObj->isDebug('getDbList')); // Debug=0

			$TSFE->fe_user->setKey('ses',$this->prefixId.'.lastIdList',implode(',',$this->felib->lastResultList));
			$this->globalMarkers['###TOTAL_RESULTS###'] = $r['total'];

			$this->markers = $this->felib->getDbPages
				($r,$this->markers,'',$TSFE->id,$this->conf['pagebrowser.'],$this->debugObj->isDebug('markers'));
			$this->markers = array_merge($this->markers, $this->rowHeaders);
			if (count($this->PCA['listmode'])) {
				for (reset($this->PCA['listmode']);$lKey=key($this->PCA['listmode']);next($this->PCA['listmode'])) {
				$this->markers['###LISTMODE_'.strtoupper($lKey).'###'] =
					$this->pi_linkTP_keepPIvars_url(array('listmode'=>$lKey));
				}
			}


			$this->markers['###SEARCHED_ABC_KEY###'] = '';
			$this->markers['###SEARCHED_ABC_RANGE###'] = '';
			if ($this->felib->lastAbcKey) {
				$tmp = t3lib_div::trimExplode('|',$this->PCA['search'][$this->felib->lastAbcKey]['wrapKey'],2);
				$this->markers['###SEARCHED_ABC_KEY###'] = $this->felib->lastAbcRangeText ?
					$tmp[0].$this->felib->lastAbcRangeText.$tmp[1] : '';
				$tmp = t3lib_div::trimExplode('|',$this->PCA['search'][$this->felib->lastAbcKey]['wrapRange'],2);
				$this->markers['###SEARCHED_ABC_RANGE###'] =  $this->felib->lastAbcRange ?
					$tmp[0].$this->felib->lastAbcRange.$tmp[1] : '';
			}

			$this->headerEntry = $this->templateObj->getConfSubpart($this->template,$ehName,$this->PCA);
			$this->markers['###ENTRY###'] = $this->cObj->substituteMarkerArray($this->headerEntry,$this->rowHeaders);

			if ($r['total']>0 || !$this->conf['search.']['emptyResultAsSubpart']) { // Only if something is found
				$this->markers['###EXPORT###'] = '';
				if ($r['total']>0) {
					$this->markers['###EXPORT###'] = $this->felib->getDbExportSection
								($this->mainTable,$this->PCA,$this->piVars['search'],$qa,$r);
				}

				$this->listEntry = $this->templateObj->getConfSubpartArray($this->template,$epName,$this->PCA);
				$this->listEntryNO = Array();
				$this->listEntryOwn = Array();
				for (reset($this->listEntry);$key=key($this->listEntry);next($this->listEntry)) {
					$this->listEntryNO[$key] = $this->felib->lCObj->substituteSubpart ($this->listEntry[$key],'###OWNERONLY###','');
					$this->listEntryOwn[$key] = $this->felib->lCObj->substituteSubpart ($this->listEntry[$key],'###NONOWNERONLY###','');
				}

				$this->dConf = $this->conf['defaultList.'];

				$wrapList = array ("","");
				$wrapSList = array ("","");
				$wrapFirst = Array ();
				$wrapTail = Array ();
				if (is_array($this->dConf['divide.'])) {
					$this->dConf['divide.']['perpart'] = $r['cnt'];
					if (intval($this->dConf['divide.']['parts'])>1) {
						$this->dConf['divide.']['perpart'] =
							intval( ($r['cnt'] + intval($this->dConf['divide.']['parts']) - 1) /
								intval($this->dConf['divide.']['parts']));
					}
					$wrapList = t3lib_div::trimExplode('|',$this->dConf['divide.']['wrap']);
				}
				if (is_array($this->dConf['segment.'])) {
					if (intval($this->dConf['segment.']['parts'])<2) {
						$this->dConf['segment.']['parts'] = 2;
					}
					$wrapSList = t3lib_div::trimExplode('|',$this->dConf['segment.']['wrap']);
					$wrapFirst = t3lib_div::trimExplode('|',$this->dConf['segment.']['wrapFirstPart']);
					$wrapTail = t3lib_div::trimExplode('|',$this->dConf['segment.']['wrapTailPart']);
				}

				$first = TRUE;
				$n = $r['start'];
				if ($r['res']) {
					$s = $this->divObj->getMicroSec();
					$lineCnt = 0;
					$lineSCnt = 0;
					$isBetween = FALSE;
					$isSBetween = FALSE;
					$firstOfSegment = TRUE;
					$row = array();
					$this->markers['###ENTRY###'] .= $this->checkListOutput(0,$row,$this->PCA,Array());
					$altCount=0;
					while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($r['res'])) {
						$altCount++;
						$this->langObj->replaceLangOverlay($row,$this->mainTable);
						if (!$this->listFirstRow) {
							$this->listFirstRow = $row;
						}
						$this->debugObj->debugIf('eachRow',Array('$row'=>$row, 'File:Line'=>__FILE__.':'.__LINE__));
						$this->markers['###ENTRY###'] .= $this->checkListOutput(1,$row,$this->PCA,Array());

						if ($isBetween) {
							if (is_array($this->dConf['segment.'])) {
								if (!$isSBetween) {
									$isBetween=FALSE;
								}
							}
						}

						if ($isSBetween) { $this->markers['###ENTRY###'] .= $wrapSList[1]; }
						if ($isBetween) { $this->markers['###ENTRY###'] .= $wrapList[1]; }

						if ($isBetween) {
							$lineCnt = 0;
							$this->dConf['divide.']['parts']--;
							if ($this->dConf['divide.']['between']) {
								$this->markers['###ENTRY###'] .= $this->dConf['divide.']['between'];
							}
						}
						if ($isSBetween) {
							$lineSCnt = 0;
							$firstOfSegment = TRUE;
							if ($this->dConf['segment.']['between']) {
								$this->markers['###ENTRY###'] .= $this->dConf['segment.']['between'];
							}
						}

						if ($lineCnt<1) { $this->markers['###ENTRY###'] .= $wrapList[0]; }
						if ($lineSCnt<1) { $this->markers['###ENTRY###'] .= $wrapSList[0]; }

						$lineCnt++;
						$lineSCnt++;

						$this->localPrepareDbRow($this->mainTable,$row,$this->PCA);
						// Why it this in FORM mode ????????? 21.03.2006 ?????
						$rowMarkers = $this->felib->getDbRow($this->mainTable,$n,$row,$this->PCA, Array(),SGZ_FORM,($n==$r['start'] ?
							$this->debugObj->isDebug('markers'):0));
						$rowMarkers = $this->getLocalDbRow($this->mainTable,$n,$row,$this->PCA,
								$rowMarkers,SGZ_FORM,($n==$r['start'] ? $this->debugObj->isDebug('markers'):0),1);
						$rowMarkers['###LINK_LINK###'] = $this->linkLink[0].$row['link'].$this->linkLink[1];

						$rowMarkers['###ALTROWCOLOR###'] = $altColorName.$altCount;
						if ($altCount>=$altMaxCount) {
							$altCount = 0;
						}

						$xm = $this->getEditButtons (0,$row,$this->PCA,$rowMarkers['###TEXT_LISTTITLE###']);
						if (is_array($xm)) { $rowMarkers = array_merge($rowMarkers,$xm); }

						if (!$this->permitObj->allowed('admin') && $row[$this->PCA['ctrl']['crfeuser_id']]!=$this->permitObj->feUser['uid'] && intval($row[$this->PCA['ctrl']['crfeuser_id']]) ) {
								$this->myListEntry = $this->listEntryNO;
						} else {
								$this->myListEntry = $this->listEntryOwn;
						}

						$rowMarkers = array_merge($rowMarkers, $this->rowHeaders);
						$rowMarkers['<!-- ###NONOWNERONLY### -->'] = '';
						$rowMarkers['<!-- ###OWNERONLY### -->'] = '';
						$tmpEntry = $this->cObj->substituteMarkerArray(
							$this->templateObj->getSubpartFromArray($this->myListEntry,$this->PCA,$row), $rowMarkers);

						if ($row['uid']>0 && $this->insertUID==$row['uid']) {
							//$this->insertDetails  = $this->DoSingle();
							$dWrap = t3lib_div::trimExplode('|',$this->conf['detailsInList.']['wrap']);
							$tmpEntry .= $dWrap[0].$this->insertDetails.$dWrap[1];
						}


						$this->markers['###ENTRY###'] .= $this->checkListOutput(2,$row,$this->PCA,$rowMarkers);
						if ($firstOfSegment) {
							$this->markers['###ENTRY###'] .= $wrapFirst[0].$tmpEntry.$wrapFirst[1];
						} else {
							$this->markers['###ENTRY###'] .= $wrapTail[0].$tmpEntry.$wrapTail[1];
						}
						$this->markers['###ENTRY###'] .= $this->checkListOutput(4,$row,$this->PCA,$rowMarkers);
						$n++;
						if ($first) {
							$this->pTime[] = 'firstOfList='.intval($this->divObj->getMicroDur($s));
							$first = false;
						}

						$isBetween = FALSE;
						if (is_array($this->dConf['divide.'])) {
							if ($lineCnt>=$this->dConf['divide.']['perpart']) {
								if ($this->dConf['divide.']['parts']>1) {
									$isBetween = TRUE;
								}
							}
						}

						$isSBetween = FALSE;
						if (is_array($this->dConf['segment.'])) {
							if ($lineSCnt>=$this->dConf['segment.']['parts']) {
								$isSBetween = TRUE;
							}
						}

						unset ($this->listLastRow);
						$this->listLastRow = $row;
						$firstOfSegment = FALSE;
						$this->markers['###ENTRY###'] .= $this->checkListOutput(5,$row,$this->PCA,$rowMarkers);
					}
					if (!$this->listFirstRow) {
						$this->listFirstRow = $row;
					}
					$this->markers['###ENTRY###'] .= $this->checkListOutput(8,$row,$this->PCA,$rowMarkers);

					if (is_array($this->dConf['segment.'])) {
						$n = $this->dConf['segment.']['parts'] - $lineSCnt;
						if ($n>0 && $this->dConf['segment.']['fill']) {
							for ($i=0;$i<$n;$i++) {
								$this->markers['###ENTRY###'] .= $this->dConf['segment.']['fill'];
							}
						}
					}

					if ($lineSCnt>0) {
						$this->markers['###ENTRY###'] .= $wrapSList[1];
					}
					if ($lineCnt>0) {
						$this->markers['###ENTRY###'] .= $wrapList[1];
					}

					$this->markers['###ENTRY###'] .= $this->checkListOutput(9,$row,$this->PCA,$rowMarkers);
				} else if ($r['total']>0) {
					t3lib_div::debug(Array('ERROR:'=>$r, 'File:Line'=>__FILE__.':'.__LINE__));
				}

				$xm = $this->getListSummaryMarkers($r,$this->PCA);
					if (is_array($xm)) { $this->markers = array_merge($this->markers,$xm); }
				$xm = $this->getEditButtons (0,'',$this->PCA,'');
					if (is_array($xm)) { $this->markers = array_merge($this->markers,$xm); }


				$this->listTmpl = $this->templateObj->getConfSubpart($this->template,$lpName,$this->PCA);
				$content .= $this->cObj->substituteMarkerArray($this->listTmpl, $this->markers);
			} else {
				$xm = $this->getEditButtons (0,'',$this->PCA,'');
				if (is_array($xm)) { $this->markers = array_merge($this->markers,$xm); }
				$this->listTmpl = $this->templateObj->getConfSubpart($this->template,'EMPTYRESULT_PART',$this->PCA,'','','',1);
				$content .= $this->cObj->substituteMarkerArray($this->cObj->substituteMarkerArray($this->listTmpl, $this->rowHeaders), $this->markers);

			}

		} else {
			$xm = $this->getEditButtons (0,'',$this->PCA,'');
			if (is_array($xm)) { $this->markers = array_merge($this->markers,$xm); }
			if ($this->pluginMode==31 || $this->pluginMode==32) {
				$this->listTmpl = $this->templateObj->getConfSubpart($this->template,$lpName,$this->PCA);
			} else {
				$this->listTmpl = $this->templateObj->getConfSubpart($this->template,'NOLIST_PART',$this->PCA);
			}
			$content .= $this->cObj->substituteMarkerArray($this->cObj->substituteMarkerArray($this->listTmpl, $this->rowHeaders), $this->markers);
		}

		$this->returnEditToList = FALSE;
		return $content;
	}


	/*********************************************************************************
	**
	** Edit
	**
	*********************************************************************************/

	/**
	 * [Put your description here]
	 *
	 * @return	[type]		...
	 */
	function DoSingle()	{
		$content = '';
		$this->markers = Array();

		if (isset($this->PCA['ctrl']['singleSubPartField'])) {
			$this->PCA['ctrl']['subPartField'] = $this->PCA['ctrl']['singleSubPartField'];
		}
		if (isset($this->PCA['ctrl']['singleSubPartModes'])) {
			$this->PCA['ctrl']['subPartModes'] = $this->PCA['ctrl']['singleSubPartModes'];
		}

		$this->globalMarkers = $this->felib->globalMarkers;
		$this->globalMarkers['###BACK_URL###'] = $this->felib->todo['BackUrl'];

		$this->template =  $this->templateObj->getTemplate('single',$this->globalMarkers);
		$this->printMode = Array();
		if ($this->PCA['todo']['PrintMode']) {
			$this->printMode = $this->conf['printViews.'][$this->PCA['todo']['PrintMode'].'.'];
			if ($this->printMode['file']) {
				$this->templatePrint = $this->templateObj->getTemplate($this->printMode['file'],$this->globalMarkers);
			}
			if (!$this->printMode['subpart']) {
				$this->printMode['subpart'] = 'single';
			}
		}
		$content .= $this->getShowEdit();

		//if ($this->printMode && $this->printMode['autoclose']) {
		//	t3lib_div::debug(Array('Autoclose'=>1, 'File:Line'=>__FILE__.':'.__LINE__));
		//}

		return $content;
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getShowEdit()	{
		GLOBAL $TSFE;

		$this->doClearCache = FALSE;
		$content = '';
		$errorMarkers = Array();
		$this->editMessage = '';
		$this->singleName = $this->templateObj->getSubPartName('single');
		$fgd = $this->PCA['ctrl']['fe_group_details'];

		$content .= $this->processHideDelete(2);

		// Check, if record has to be saved   ----------------------------------------------------------------------------
		if ($this->PCA['todo']['Save']) {
			$rowToSave = is_array($this->piVars['data']) ? $this->piVars['data'] : Array();
			// Check for errors in record, before saving
			if (intval($rowToSave['uid'])<1 && intval($this->PCA['todo']['Uid'])>0) {
				$rowToSave['uid'] = $this->PCA['todo']['Uid'] ;
			}
			if ($this->felib->checkDbRowForSave($this->mainTable,$rowToSave,$this->PCA,$errorMarkers)) {
				// $content .= 'Dont save, - errors!<br />';
				$this->PCA['todo']['Edit'] = 1;
				$this->PCA['todo']['Save'] = 1;
				$rowToSave = is_array($this->piVars['data']) ? $this->piVars['data'] : Array();
			} else {
				// Seems that record ist ok for writing to database
				// But check again with private function:
				if ($this->localCheckDbRowForSave($this->mainTable,$rowToSave,$this->PCA,$errorMarkers)) {
					// $content .= 'Dont save, - errors!<br />';
					$this->PCA['todo']['Edit'] = 1;
					$this->PCA['todo']['Save'] = 1;
					$this->piVars['data'] = $rowToSave;
				} else {
					$doAckCheck = (intval($GLOBALS['HTTP_POST_VARS'][$this->prefixId.'_acknowledge'])==753 ||
						$this->permitObj->allowed('admin') ||
						(intval($rowToSave['uid'])<1 && intval($this->conf['ackCreateRecord'])!=1) ||
						(intval($rowToSave['uid'])>0 && intval($this->conf['ackChangeRecord'])!=1)      );
					// check here if acknowledge is OK
					if ($doAckCheck) {
						$doAckCheck = $this->checkAck ();
					}
					// check here if I have to acknowledge the creation/changing of a record
					if ($doAckCheck) {
						$this->piVars['data'] = $rowToSave;
						$content .= $this->felib->saveRowToDb($this->mainTable,$this->piVars,$this->PCA);
						if ($this->useLocking && is_callable(array($TSFE, 'unLockRecords'))) {
							$TSFE->unLockRecords($this->mainTable,$this->PCA['todo']['Uid']);
						}

						if (is_array($this->conf['editMessages.'])) {
							if ($this->conf['editMessages.']['edit'] && intval($rowToSave['uid'])>0) {
								$this->editMessage = $this->cObj->cObjGetSingle
									($this->conf['editMessages.']['edit'],$this->conf['editMessages.']['edit.']);
							} else if ($this->conf['editMessages.']['create'] && intval($rowToSave['uid'])<1) {
								$this->editMessage = $this->cObj->cObjGetSingle
									($this->conf['editMessages.']['create'],$this->conf['editMessages.']['create.']);
							}
						}
						if ($this->editPopup) {
							$content .= $this->felib->jsInsert('openerReload(0);');
						}
						// if rTL is set, return to list and breakt this here
						if ($this->PCA['todo']['RTL']) {
							if ($this->editPopup) {
								$content = $this->felib->jsInsert('openerReload(0); window.close();');
								return ($content);
							} else if (!$content) {
								if ($this->felib->debugCnt==0) {
									$content = '';
									header('Location: '.$this->felib->todo['BackUrl']); // Umleitung des Browsers
									exit;
								}
							} else {
								$content .= $this->felib->jsInsert('location.href="'.$this->felib->todo['BackUrl'].'";');
							}
						}

					} else {
						$this->PCA['todo']['Acknowledge'] = 1;
						$this->PCA['todo']['Save'] = 1;
						$this->piVars['data'] = $rowToSave;
					}
				}
			}
			$this->PCA['todo']['New'] = 0;
		} else if ($this->PCA['todo']['Reload']>0) {
			$this->PCA['todo']['Edit'] = 1;
			$this->PCA['todo']['Save'] = 1;
			$rowToSave = is_array($this->piVars['data']) ? $this->piVars['data'] : Array();
			$row = $this->piVars['data'];
			//t3lib_div::debug(Array('Reload'=>1, 'uid'=>$this->PCA['todo']['Uid'], 'File:Line'=>__FILE__.':'.__LINE__));
		}
		// OK - save/hide/unhide/delete has been done

		if ($this->PCA['todo']['Edit']) {
				// now lock this record
				if ($this->useLocking && is_callable(array($TSFE, 'lockRecords'))) {
					$TSFE->lockRecords($this->mainTable,$this->PCA['todo']['Uid']);
				}
				$this->template = $this->felib->lCObj->substituteSubpart ($this->template,'###VIEWMODEONLY###','');
		} else {
				if ($this->useLocking && is_callable(array($TSFE, 'unLockRecords'))) {
					$TSFE->unLockRecords($this->mainTable,$this->PCA['todo']['Uid']);
				}
				$this->template = $this->felib->lCObj->substituteSubpart ($this->template,'###EDITMODEONLY###','');
		}

		// Now - show/create/edit record
		$this->PCA['todo']['NotFound'] = 0;
		if (intval($this->PCA['todo']['Uid'])==0 && intval($this->PCA['todo']['Save'])==0 && intval($this->PCA['todo']['New'])==0) {
			$this->tmplNoUid = $this->templateObj->getSubpart($this->template,'###NO_UID_GIVEN###');
			if (strlen($this->tmplNoUid)<3) {
				$this->tmplNoUid = 'ERROR - no Record selected !!<br />';
			}
			$content .= $this->cObj->substituteMarkerArray($this->tmplNoUid, $this->rowHeaders);
		} else if ($this->PCA['todo']['Save'] && intval($this->PCA['todo']['Acknowledge']) ) {
			$markers = $this->getAckMarkers($rowToSave,$this->PCA);
			$markers['###ACKMESSAGE###'] =
				intval($GLOBALS['HTTP_POST_VARS'][$this->prefixId.'_acknowledge']) ? $this->ackMessage : '';
			$hiddenData = '<input type="hidden" name="id" value="'.$TSFE->id.'">'.CRLF;
			$hiddenData .= '<input type="hidden" name="type" value="'.$TSFE->type.'">'.CRLF;
			for (reset($rowToSave);$key=key($rowToSave);next($rowToSave)) {
				$hiddenData .= '<input type="hidden" name="'.$this->prefixId.'[data]['.$key.']" value="'.$rowToSave[$key].'">';
			}
			if (intval($this->piVars['uid'])!=0) {
				$hiddenData .= '<input type="hidden" name="'.$this->prefixId.'[uid]" value="'.$this->piVars['uid'].'">';
			}
			$markers = $this->felib->getDbHeaders($this->mainTable,$this->PCA,$markers,$this->debugObj->isDebug('headerMarkers'));
			$markers = $this->getLocalHeaders($this->mainTable,$this->PCA,$markers,$this->debugObj->isDebug('headerMarkers'));
			if ($this->constObj->buttonExists('docancel')) {
				$myCancelButton = '<a href="#null" onclick="ack_cancel_'.$this->prefixId.'.submit(); return false">'.
					$this->constObj->getButton('docancel').'</a>';
			} else {
				if ($this->constObj->isIconTypeText('docancel')) {
					$myCancelButton = '<input type="submit" '.
						' value="'.$this->constObj->getIcon('docancel').'"/>';
				} else {
					$myCancelButton = '<input title="'.$this->langObj->getLL('formcancel').
						'" type="image" src="'.$this->constObj->getIconResource('docancel').'"/>';
				}
			}
			$markers['###DOCANCEL###'] = '<form name="ack_cancel_'.$this->prefixId.'">'.$hiddenData.$myCancelButton.
				'<input type="hidden" name="dR" value="1">'.
				'</form>';
			$doSaveTmpl = $this->templateObj->getSubpart($this->template,'###ACK_SAVE###');
			if ($this->constObj->buttonExists('dosave')) {
				$mySubmitButton = '<a href="#null" onclick="ack_submit_'.$this->prefixId.'.submit(); return false">'.
					$this->constObj->getButton('dosave').'</a>';
			} else {
				if ($this->constObj->isIconTypeText('dosave')) {
					$mySubmitButton = '<input type="submit" '.
						' value="'.$this->constObj->getIcon('dosave').'"/>';
				} else {
					$mySubmitButton = '<input title="'.$this->langObj->getLL('saveexit').
						'" type="image" '.$this->constObj->getIconResource('dosave').'"/>';
				}
			}
			$markers['###SUBMIT###'] = $mySubmitButton;
			$markers['###DOSAVE###'] = '<form name="ack_submit_'.$this->prefixId.'" method="POST">'.$hiddenData.
				'<input type="hidden" name="dS" value="1">'.
				'<input type="hidden" name="'.$this->prefixId.'_acknowledge" value="753">'.
				($doSaveTmpl ? $this->cObj->substituteMarkerArray($doSaveTmpl,$markers) : $markers['###SUBMIT###']).
				'</form>';

			$contAckTmpl = $this->templateObj->getSubpart($this->template,'###'.$this->singleName.'ACK###');
			if (!$contAckTmpl) {
				$contAckTmpl = $this->templateObj->getSubpart($this->template,'###'.$this->singleName.'###');
			}
			if ($this->PCA['ctrl']['crfeuser_id'] && !isset($rowToSave[$this->PCA['ctrl']['crfeuser_id']])) {
				$rowToSave[$this->PCA['ctrl']['crfeuser_id']] = $this->permitObj->getFeUid();
			}
			$markers = $this->felib->getDbRow($this->mainTable,0,$rowToSave,$this->PCA, $markers, SGZ_TEXT,
				$this->debugObj->isDebug('markers'));
			$markers = $this->getLocalDbRow($this->mainTable,0,$rowToSave,$this->PCA, $markers, SGZ_TEXT,
				$this->debugObj->isDebug('markers'),5);
			$xm = $this->getMoreMarkers($rowToSave,$this->PCA);
			if (is_array($xm)) {
				$markers = array_merge($markers,$xm);
			}
//			$xm = $this->getAckMarkers($rowToSave,$this->PCA);
//			if (is_array($xm)) {
//				$markers = array_merge($markers,$xm);
//			}
			$markers['###CONTENT###'] = $this->cObj->substituteMarkerArray($contAckTmpl,$markers);
			$this->tmplAck = $this->templateObj->getSubpart($this->template,'###ACKNOWLEDGE###');
			$content .= $this->cObj->substituteMarkerArray($this->tmplAck,$markers);
			if (!$this->tmplAck) {
				$content .= '-error: subpart acknowledge missing-<br />';
			}
		} else  {
			// ok: here we create a new, edit or show a record
			$row = Array();
			if ($this->PCA['todo']['New']) {
				$this->template = $this->felib->lCObj->substituteSubpart ($this->template,'###NONEWRECORDSONLY###','');
				$this->rowHeaders['<!-- ###NEWRECORDSONLY### -->'] = '';
			} else {
				$this->template = $this->felib->lCObj->substituteSubpart ($this->template,'###NEWRECORDSONLY###','');
				$this->rowHeaders['<!-- ###NONEWRECORDSONLY### -->'] = '';
			}

			if ($this->PCA['todo']['New'] && $this->PCA['todo']['Edit']) {
				// check if new is allowed !!!
				$noNewRecords = $this->checkQuotas('maxRecords');
				$this->rowHeaders['###QUOTA_SINGLEMSG###'] = $this->quota['singlemsg'];

				if ($noNewRecords) {
					$this->tmplError = $this->templateObj->getSubpart($this->template,'###NO_NEW_RECORDS###');
					if (strlen($this->tmplError)<3) {
						$this->tmplError = 'ERROR<br />You cannot create new Records.<br />'.
							'Max-Records: '.$this->quota['maxRecords']['maxCount'].'<br />Your Records: '.$this->quota['maxRecords']['isCount'].'<br />';
					}
					$this->rowHeaders['###TOTALCNT###'] = $this->quota['maxRecords']['isCount'];
					$this->rowHeaders['###LIMITCNT###'] = $this->quota['maxRecords']['maxCount'];
					$this->rowHeaders['###BUTTON_CANCEL###'] = $this->felib->getFormCancelButton($this->felib->todo['BackUrl'],'');
					if ($this->editPopup) {
						$this->rowHeaders['###BUTTON_CANCEL###'] = $this->felib->getFormCancelButton('','',1);
					}

					$content .= $this->cObj->substituteMarkerArray($this->tmplError, $this->rowHeaders);
				} else {
					$query = 'SHOW COLUMNS FROM '.$this->mainTable.' ;';
					$res = $GLOBALS['TYPO3_DB']->sql_query($query);
					//$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$table,$query,$group,$order);
					if (count($this->PCA['ctrl']['calc'])>0) {
						for(reset($this->PCA['ctrl']['calc']);$key=key($this->PCA['ctrl']['calc']);next($this->PCA['ctrl']['calc'])) {
							$row[$key] = '';
						}
					}
					if ($res) {
						while ($defRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
							$row[$defRow['Field']] = '';
						}
					} else {
						t3lib_div::debug(Array('query'=>$query, 'error'=>$GLOBALS['TYPO3_DB']->sql_error() ,'FILE/LINE='=>__FILE__.': '.__LINE__));
					}

					$pre = Array('###datetime###'=>date('d.m.Y H:i',time()), '###date###'=>date('d.m.Y',time()),
						'###date7###'=>date('d.m.Y',time()+604800), '###date14###'=>date('d.m.Y',time()+1209600),
						'###date1m###'=>date('d.m.Y',time()+2592000), '###date2m###'=>date('d.m.Y',time()+5184000));
					for (reset($this->permitObj->feUser);$key=key($this->permitObj->feUser);next($this->permitObj->feUser)) {
						$pre['###feuser_'.$key.'###'] = $this->permitObj->feUser[$key];
					}
					if (count($this->PCA['conf'])>0) {
						for(reset($this->PCA['conf']);$key=key($this->PCA['conf']);next($this->PCA['conf'])) {
							if (isset($this->PCA['conf'][$key]['preset'])) {
								$row[$key] = $this->felib->lCObj->substituteMarkerArray($this->PCA['conf'][$key]['preset'],$pre);
							}
						}
					}

					$preset = $this->piVars['preset'];
					if (is_array($preset)) {
						$row = array_merge($row,$preset);
					}

					if ($this->PCA['ctrl']['crfeuser_id']) {
						$row[$this->PCA['ctrl']['crfeuser_id']] = $this->permitObj->getFeUid();
						unset ($rowToSave[$this->PCA['ctrl']['crfeuser_id']]);
					}
					if ($this->PCA['ctrl']['cruser_id']) {
						$row[$this->PCA['ctrl']['cruser_id']] = $this->permitObj->getBeUid();
						unset ($rowToSave[$this->PCA['ctrl']['cruser_id']]);
					}

					if (intval($this->PCA['todo']['NewFromUid'])>0) {
						$this->localPrepareDbRow($this->mainTable,$row,$this->PCA);
						$sRow = $this->getRow($this->PCA['todo']['NewFromUid']);
						unset ($sRow['uid']);
						if ($this->conf['newFromUidFields']) {
							$tmp = t3lib_div::trimExplode(',',$this->conf['newFromUidFields']);
							for ($i=0;$i<count($tmp);$i++) if ($tmp[$i]) {
								$row[$tmp[$i]] = $sRow[$tmp[$i]];
							}
						} else {
							if ($this->PCA['ctrl']['crfeuser_id']) {
								unset ($sRow[$this->PCA['ctrl']['crfeuser_id']]);
							}
							if ($this->PCA['ctrl']['cruser_id']) {
								unset ($sRow[$this->PCA['ctrl']['cruser_id']]);
							}
							$row = array_merge($row,$sRow);
						}
					}


					$this->prepareNewRecord ($this->mainTable,$row,$this->PCA);
					//t3lib_div::debug(Array('Create new record'=>$row, 'File:Line'=>__FILE__.':'.__LINE__));;
				}
			} else if (intval($this->PCA['todo']['Uid'])>0 ||
					   intval($this->PCA['todo']['Reload'])>0 ||
						($this->PCA['todo']['Save'] && $this->PCA['todo']['Edit'])
						){
				//$content .= 'Edit/view Record '.$this->PCA['todo']['Uid'].' <br />';
				$row = Array();
				if (intval($this->PCA['todo']['Uid'])>0 && intval($this->PCA['todo']['Reload'])==0 &&
						!$this->PCA['todo']['Save'] && !$this->PCA['todo']['Edit'] &&
						(!$this->permitObj->useEditMode() || intval($this->PCA['ctrl']['count_click_mode'])>0)  &&
						$this->PCA['ctrl']['count_click']) {
					$update = 'UPDATE '.$this->mainTable.
						' SET '.$this->PCA['ctrl']['count_click'].'='.$this->PCA['ctrl']['count_click'].'+1'.
						' WHERE uid='.intval($this->PCA['todo']['Uid']).';';

					// check if cookie says 'already clicked in this session ...'
					$tmp = $TSFE->fe_user->getKey('ses',$pluginName.'.alreadyClicked.'.intval($this->PCA['todo']['Uid']));
					if (intval($tmp)==0) {
						$this->debugObj->debugIf('count_click',Array('$update'=>$update, 'File:Line'=>__FILE__.':'.__LINE__));
						$GLOBALS['TYPO3_DB']->sql_query($update);
						//$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$table,$query,$group,$order);
						if ($GLOBALS['TYPO3_DB']->sql_error()) {
							t3lib_div::debug (Array('UPDATE_error'=>$GLOBALS['TYPO3_DB']->sql_error(),
								'$update'=>$update, 'FILE:LINE='=>__FILE__.':'.__LINE__  ));
						} else {
							$tmp = $TSFE->fe_user->setKey('ses',$pluginName.'.alreadyClicked.'.intval($this->PCA['todo']['Uid']),'1');
						}
					}
				}
				if (intval($this->PCA['todo']['Uid'])==0) {
					$query = 'SHOW COLUMNS FROM '.$this->mainTable.' ;';
					$res = $GLOBALS['TYPO3_DB']->sql_query($query);
					//$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$table,$query,$group,$order);
					if ($res) {
						while ($defRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
							$row[$defRow['Field']] = '';
						}
					} else {
						t3lib_div::debug(Array('query'=>$query, 'error'=>$GLOBALS['TYPO3_DB']->sql_error() ,'FILE/LINE='=>__FILE__.': '.__LINE__));
					}
					$row = array_merge($row,$rowToSave);
					if ($this->PCA['ctrl']['crfeuser_id']) {
						$row[$this->PCA['ctrl']['crfeuser_id']] = $this->permitObj->getFeUid();
						unset ($rowToSave[$this->PCA['ctrl']['crfeuser_id']]);
					}
					if ($this->PCA['ctrl']['cruser_id']) {
						$row[$this->PCA['ctrl']['cruser_id']] = $this->permitObj->getBeUid();
						unset ($rowToSave[$this->PCA['ctrl']['cruser_id']]);
					}
				} else {
					$row = $this->getRow($this->PCA['todo']['Uid']);
					if (is_array($rowToSave)) {
						$row = array_merge($row,$rowToSave);
					}
					if (!$this->PCA['todo']['New'] && !$this->PCA['todo']['NewFromUid'] &&
						!$this->PCA['todo']['Edit'] && !$this->PCA['todo']['Reload']) {
						$this->langObj->replaceLangOverlay($row,$this->mainTable);
					}
				}

				if ($this->PCA['todo']['Save'] && $this->PCA['todo']['Edit']) {
					$row = array_merge($row,$rowToSave);
				}
			}
			$this->localPrepareDbRow($this->mainTable,$row,$this->PCA);

			$myDetailsAreAllowed = FALSE;
			if ($fgd) {
				$this->debugObj->debugIf('fe_group_details',Array('$row['.$fgd.']'=>$row[$fgd], 'File:Line'=>__FILE__.':'.__LINE__));
				if (intval($row[$fgd])==-2 && intval($this->permitObj->getFeUid())>0) {
					$this->debugObj->debugIf('fe_group_details',Array('myDetailsAreAllowed'=>'True - Show at any login'));
					$myDetailsAreAllowed = TRUE;
				} else if (intval($row[$fgd])==-1 && intval($this->permitObj->getFeUid())==0) {
					$this->debugObj->debugIf('fe_group_details',Array('myDetailsAreAllowed'=>'True - Hide at any login'));
					$myDetailsAreAllowed = TRUE;
				} else if (intval($row[$fgd])==0) {
					$this->debugObj->debugIf('fe_group_details',Array('myDetailsAreAllowed'=>'True - Show always'));
					$myDetailsAreAllowed = TRUE;
				} else if (intval($row[$fgd])>0 && intval($this->felib->feGroups[intval($row[$fgd])])>0 ) {
					$this->debugObj->debugIf('fe_group_details',Array('myDetailsAreAllowed'=>'True, because in grouplist','user_groups'=>$this->felib->feGroups));
					$myDetailsAreAllowed = TRUE;
				}
			} else {
				$myDetailsAreAllowed = TRUE;
			}

			if ($this->permitObj->allowed('showOnlyOwnDetails') || $this->permitObj->allowed('showOnlyOwnEntries')) {
				if (intval($this->permitObj->getFeUid())==0 || intval($this->permitObj->getFeUid())!=$row['crfeuser_id']) {
				$myDetailsAreAllowed = FALSE;
				}
			}

			if (!$noNewRecords) {
				if (!is_array($row)) {
					t3lib_div::debug(Array('ERROR'=>'$row not set', '$row'=>$row, '$rowToSave'=>$rowToSave, 'File:Line'=>__FILE__.':'.__LINE__));
				}

				// check, if there are other viewblocks to render`
				$viewblocks = Array();
				if (is_array($this->conf['viewblocks.'])) {
					for (reset($this->conf['viewblocks.']);$bKey=key($this->conf['viewblocks.']);next($this->conf['viewblocks.'])) {
						$vb = $this->conf['viewblocks.'][$bKey];
						if (is_array($vb) && strlen($vb['marker'])>1 && strlen($vb['subpart'])>1) {
							$viewblocks['###'.$vb['marker'].'###'] = $this->templateObj->getSubpart($this->template,'###'.$vb['subpart'].'###');
						}
					}
				}
				$this->rowHeaders = $this->felib->getDbHeaders($this->mainTable,$this->PCA,$this->rowHeaders,
					$this->debugObj->isDebug('headerMarkers'));
				$tmp = t3lib_div::trimExplode('|',$this->conf['editMessages.']['wrap']);
				$this->editMessage = $this->editMessage ? $tmp[0].$this->editMessage.$tmp[1].CRLF : '';
				$this->rowHeaders['###EDIT_MESSAGES###'] = $this->editMessage;
				$this->rowHeaders = $this->getLocalHeaders($this->mainTable,$this->PCA,$this->rowHeaders,
					$this->debugObj->isDebug('headerMarkers'));

				if (!$myDetailsAreAllowed && intval($this->PCA['todo']['New'])==0 && intval($this->PCA['todo']['Edit'])==0) {
					$this->debugObj->debugIf('fe_group_details',Array('myDetailsAreAllowed'=>'Access Denied !!!!!'));
					$this->tmplSingle = $this->templateObj->getSubpart($this->template,'###ACCESS_DENIED###');
					if (strlen($this->tmplSingle)<3) {
						$this->tmplSingle = 'SORRY,<br />Access denied for Record "###UID###" !<br />';
					}
					$this->markers['###UID###'] = $this->PCA['todo']['Uid'];
					if (is_array($viewblocks))for (reset($viewblocks);$bKey=key($viewblocks);next($viewblocks)) {
						$this->markers['###'.$vb['marker'].'###'] = $this->cObj->substituteMarkerArray($viewblocks['###'.$vb['marker'].'###'], $this->markers);
					}
					$content .= $this->cObj->substituteMarkerArray
						($this->cObj->substituteMarkerArray($this->tmplSingle, $this->rowHeaders), $this->markers);

				} else {
					// Check, if there is a dircect-url;
					$directUrl = '';
					$ilv = $this->confObj->view['inline.'];
					if (intval($this->PCA['todo']['New'])==0 && intval($this->PCA['todo']['Edit'])==0 && is_array($ilv)) {
						$dlf = $ilv['linkField'];
						if ($dlf && $row[$dlf]) {
							$tmp = t3lib_div::trimExplode ("\n",$row[$dlf]);
							$tmpFileName = $tmp[0];
							$tmpUrl = $this->PCA['conf'][$dlf]['uploadfolder'].'/'.$tmpFileName;
							$filename = t3lib_div::getFileAbsFileName($tmpUrl);
							$ext = strrchr ($filename, ".");
							if (file_exists($filename)) {
								$directUrl = $tmpUrl;
							}
							if ($ilv['allowedExt']) {
								if (strpos ($ilv['allowedExt'],$ext)===false) {
									$directUrl = '';
								}
							}
						}
					}

					if ($directUrl) {
						if ($ilv['iframe']) {
							$from = Array('###filename###','###url###');
							$to = Array($tmpFileName,$directUrl);
							$this->rowHeaders['###IFRAME###'] = str_replace($from,$to,$ilv['iframe']);
						} else {
							$this->rowHeaders['###IFRAME###'] = '<iframe src="'.$directUrl.'" width="100%" height="600" name="iFrame_File">'.
								'<a href="'.$directUrl.'">'.$tmpFileName.'</a>'.'</iframe>';
						}
						if ($ilv['subpart']) {
							$this->singleName = $ilv['subpart'];
						}
					}

					$iframeAllowed = $this->checkForIframe($row);
					$this->debugObj->debugIf('viewinline',Array('config[view.][inline.]'=>$ilv, 'directLinkField'=>$dlf.' ('.$filename.')', '$directUrl'=>$directUrl, "###IFRAME###"=>$this->rowHeaders["###IFRAME###"], 'singleName='=>$this->singleName, '$iframeAllowed'=>$iframeAllowed, 'File:Line'=>__FILE__.':'.__LINE__));

					if ($iframeAllowed && $directUrl && !$ilv['subpart']) {
						$content .= $this->rowHeaders['###IFRAME###'];
					} else {
						// Now row is loaded/prepared;
						if ($this->PCA['todo']['NotFound']) {
							$this->tmplSingle = $this->templateObj->getSubpart($this->template,'###NOT_FOUND###');
							if (strlen($this->tmplSingle)<3) {
								$this->tmplSingle = 'SORRY,<br />Data-Record with ID "###UID###" not found!<br />';
							}
							$this->markers['###UID###'] = $this->PCA['todo']['Uid'];
							if (is_array($viewblocks))for (reset($viewblocks);$bKey=key($viewblocks);next($viewblocks)) {
								$this->markers['###'.$vb['marker'].'###'] = $this->cObj->substituteMarkerArray($viewblocks['###'.$vb['marker'].'###'], $this->markers);
							}
							$content .= $this->cObj->substituteMarkerArray
								($this->cObj->substituteMarkerArray($this->tmplSingle, $this->rowHeaders), $this->markers);
						} else {
							if (!$this->permitObj->allowed('admin') &&
									$row[$this->PCA['ctrl']['crfeuser_id']]!=$this->permitObj->feUser['uid'] &&
									intval($row[$this->PCA['ctrl']['crfeuser_id']]) ) {
								$this->template = $this->felib->lCObj->substituteSubpart ($this->template,'###OWNERONLY###','');
								$this->rowHeaders['<!-- ###NONOWNERONLY### -->'] = '';
							} else {
								$this->template = $this->felib->lCObj->substituteSubpart ($this->template,'###NONOWNERONLY###','');
								$this->rowHeaders['<!-- ###OWNERONLY### -->'] = '';
							}
							$this->rowHeaders['###MENU_LINE###'] = '';

							if (isset($this->PCA['ctrl']['fixedField'])) {
								if (intval($row[$this->PCA['ctrl']['fixedField']])>0) {
									$isNFI = (	$this->PCA['todo']['Edit']==1 &&
												$this->PCA['todo']['New']==1 &&
												$this->PCA['todo']['NewFromUid']>0);
									if (!$this->permitObj->allowed('admin') && !$this->felib->allow['editFixedOwn'] && !$isNFI) {
										$this->PCA['todo']['New']='';
										$this->PCA['todo']['Edit']='';
										$this->PCA['todo']['Save']='';
									}
								}
							}
							$menuMarkers = Array();
							if ($this->confObj->view['setTitleTagTo']) {
								$TSFE->page['title'] = $this->cObj->substituteMarkerArray(
									$this->confObj->view['setTitleTagTo'], $row+Array('TITLETAG'=>$TSFE->page['title']), '###|###');
								$TSFE->indexedDocTitle = $TSFE->page['title'];
							}
							if ($this->PCA['todo']['Edit']>0) {
								$xm = $this->getMoreMenuMarkers($row,$this->PCA);
								if (is_array($xm)) { $menuMarkers = array_merge($menuMarkers,$xm); }
								$xm = $this->getEditButtons (3,$row,$this->PCA);
								if (is_array($xm)) { $menuMarkers = array_merge($menuMarkers,$xm); }

								$tmp = $this->templateObj->getConfSubpart($this->template,'FORM_MENU',$this->PCA);
								$this->rowHeaders['###MENU_LINE###'] = $this->cObj->substituteMarkerArray($tmp, $menuMarkers);
								$rowMarkers = $this->felib->getDbRow($this->mainTable,0,$row,$this->PCA, Array(), SGZ_AUTOHIDDEN,
									$this->debugObj->isDebug('markers'));
								$rowMarkers = $this->getLocalDbRow($this->mainTable,0,$row,$this->PCA, $rowMarkers, SGZ_AUTOHIDDEN,
									$this->debugObj->isDebug('markers'),3);
								$rowMarkers['###BUTTON_UPDATE###'] = $menuMarkers['###BUTTON_UPDATE###'];
								//t3lib_div::debug(Array('$rowMarkers'=>$rowMarkers, 'File:Line'=>__FILE__.':'.__LINE__));
								$xm = $this->getMoreMarkers($row,$this->PCA);
								if (is_array($xm)) {
									$rowMarkers = array_merge($rowMarkers,$xm);
								}
								$tmp = $this->templateObj->getConfSubpart($this->template,'FORM_XTRA_MENU',$this->PCA);
								$this->rowHeaders['###XTRA_LINE###'] = strlen($menuMarkers ['###XTRA_SAVE###'])>0 ?
											$this->cObj->substituteMarkerArray($tmp, $menuMarkers) : '';
							} else {
								$xm = $this->getMoreMenuMarkers($row,$this->PCA);
								if (is_array($xm)) { $menuMarkers = array_merge($menuMarkers,$xm); }
								$xm = $this->getEditButtons (2,$row,$this->PCA);
								if (is_array($xm)) { $menuMarkers = array_merge($menuMarkers,$xm); }

								$u = $this->felib->myparseURL(t3lib_div::getIndpEnv('TYPO3_REQUEST_URL'));
								$u['params'] = $u['plist'];
								unset ($u['params']['x']);
								unset ($u['params']['y']);

								$tmp = $u['params'];
								$u['params'] = Array();
								if (is_array($tmp)) for (reset($tmp);$key=key($tmp);next($tmp)) {
									$u['params'][str_replace('=','%3D',$key)] = ($tmp[$key]);
								}

								$idList = explode(',',$this->PCA['todo']['LastIdList']);
								$r['total'] = is_array($idList) ? count($idList) : 0;
								$r['maxPage'] = $r['total'] -1;
								//$r['uid'] = $u['params']['uid'];
								$r['uid'] = $this->PCA['todo']['Uid'];
								$m = $this->felib->getPageBrowser
										($r,$TSFE->id,$u['params'],'uid',$this->conf['detailsbrowser.'],$idList);
								$menuMarkers = array_merge ($menuMarkers,$m);


								$rowMarkers = $this->felib->getDbRow($this->mainTable,0,$row,$this->PCA, Array(), SGZ_TEXT,
										$this->debugObj->isDebug('markers'));
								$rowMarkers = $this->getLocalDbRow($this->mainTable,0,$row,$this->PCA, $rowMarkers, SGZ_TEXT,
										$this->debugObj->isDebug('markers'),2);
								$xm = $this->getMoreMarkers($row,$this->PCA);
								if (is_array($xm)) {
									$rowMarkers = array_merge($rowMarkers,$xm);
								}
								if ($this->templatePrint) {
									$this->rowHeaders['###MENU_LINE###'] = '';
									$this->rowHeaders['###XTRA_LINE###'] = '';
								} else {
									$tmp = $this->templateObj->getConfSubpart($this->template,'VIEW_MENU',$this->PCA);
									$this->rowHeaders['###MENU_LINE###'] = $this->cObj->substituteMarkerArray($tmp, $menuMarkers);
									$tmp = $this->templateObj->getConfSubpart($this->template,'FORM_XTRA_VIEW',$this->PCA);
									$this->rowHeaders['###XTRA_LINE###'] = strlen($menuMarkers ['###XTRA_EDIT###'])>0 ?
												$this->cObj->substituteMarkerArray($tmp, $menuMarkers) : '';
								}
							}

							$this->markers = array_merge($rowMarkers, $this->rowHeaders);
							$this->markers = array_merge($this->markers, $errorMarkers);
							$this->markers['###LINK_LINK###'] = $this->linkLink[0].$row['link'].$this->linkLink[1];
							if (is_array($viewblocks)) for (reset($viewblocks);$bKey=key($viewblocks);next($viewblocks)) {
								$this->markers['###'.$vb['marker'].'###'] = $this->cObj->substituteMarkerArray($viewblocks['###'.$vb['marker'].'###'], $this->markers);
							}

							$tmp = $this->felib->autoInsert();
							$this->markers = array_merge($this->markers,$tmp);

							if ($this->templatePrint) {
								$this->tmplSingle = $this->templateObj->getSubpart($this->templatePrint,'###'.strtoupper($this->printMode['subpart']).'###');
								$this->markers = array_merge($this->markers, $xm);
								$content .= $this->cObj->substituteMarkerArray( $this->tmplSingle, $this->markers);
								$content .= $this->felib->jsInsert
									('setAutoPrintClose('.intval($this->printMode['autoprint']).','.intval($this->printMode['autoclose']).');');
							} else {
								$this->tmplSingle = $this->templateObj->getConfSubpartArray($this->template,$this->singleName,$this->PCA);

								$content .= '<form name="sg_editform" method="POST" action="'.$this->myPage;
								$content .= '&type='.$TSFE->type;
								if (intval($row['uid'])) {
									$content .= '&uid='.urlencode($row['uid']);
								}
								$content .= '">';
								$content .= '<input type="hidden" name="dS" value="'.($this->PCA['todo']['Edit']>0 ? 1:0).'">';
								$content .= '<input type="hidden" name="dE" value="0">';
								$content .= '<input type="hidden" name="rTL" value="'.(t3lib_div::GPvar('rTL') ? 1:0).'">';
								$content .= '<input type="hidden" name="dR" value="0">';
								$content .= '<input type="hidden" name="'.$this->prefixId.'[uid]" value="'.$row['uid'].'">';

								$tmp = $this->templateObj->getSubpartFromArray($this->tmplSingle,$this->PCA,$row);
								$content .= $this->cObj->substituteMarkerArray($tmp, $this->markers);
								$content .= '</form>';
							}

						//t3lib_div::debug(Array('$row[description]'=>$row['description'], 'marker='=>$this->markers['###TEXT_DESCRIPTION###'], '$content'=>$content,  'File:Line'=>__FILE__.':'.__LINE__));
						}
					}
				}
			}
		}


		return $content;
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function doLatestList () {
		$content = '';
		$this->markers = Array();

		$this->template = $this->templateObj->getTemplate('latestList',$this->globalMarkers);

		$this->rowHeaders = $this->felib->getDbHeaders($this->mainTable,$this->PCA,Array(),$this->debugObj->isDebug('headerMarkers'));
		$this->rowHeaders = $this->getLocalHeaders($this->mainTable,$this->PCA,$this->rowHeaders,$this->debugObj->isDebug('headerMarkers'));

		$this->felib->myQuery = t3lib_div::getIndpEnv('TYPO3_REQUEST_URL');
		$GLOBALS['TSFE']->fe_user->setKey('ses',$this->prefixId.'.lastQuery',$this->felib->myQuery);

		$myConf = $this->confObj->latest['list.'];
		$order = $myConf['order'] ? $myConf['order'] : 'tstamp DESC';
		$limit = $myConf['limit'] ? $myConf['limit'] : '10';
		$restrict = $myConf['restrict'];

		$this->piVars['searchmode'] = 1;
		$this->piVars['search'] = Array();

		$this->conf['search.']['emptyResultAsSubpart'] = 1;
		$this->doTotalList = 1;
		$content .= $this->getResultList($order,$limit,$restrict);

		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function doLatestView () {
		$content = '';
		$this->markers = Array();

		if (isset($this->PCA['ctrl']['singleSubPartField'])) {
			$this->PCA['ctrl']['subPartField'] = $this->PCA['ctrl']['singleSubPartField'];
		}
		if (isset($this->PCA['ctrl']['singleSubPartModes'])) {
			$this->PCA['ctrl']['subPartModes'] = $this->PCA['ctrl']['singleSubPartModes'];
		}

		$this->globalMarkers = $this->felib->globalMarkers;
		$this->globalMarkers['###BACK_URL###'] = $this->felib->todo['BackUrl'];

		$this->template = $this->templateObj->getTemplate('latestView',$this->globalMarkers);

		$this->template .= (!substr_count($this->template, "###ENTRY_HEADER###") ? '###ENTRY_HEADER######ENTRY_HEADER###'.CRLF : '').
			(!substr_count($this->template, "###ENTRY_PART###") ? '###ENTRY_PART######ENTRY_PART###'.CRLF : '').
			(!substr_count($this->template, "###VIEW_MENU###") ? '###VIEW_MENU######ENTRY_PART###'.CRLF : '').
			(!substr_count($this->template, "###LIST_PART###") ? '###LIST_PART######LIST_PART###'.CRLF : '').
			(!substr_count($this->template, "###FORM_XTRA_VIEW###") ? '###FORM_XTRA_VIEW######FORM_XTRA_VIEW###'.CRLF : '');

		$this->rowHeaders = $this->felib->getDbHeaders($this->mainTable,$this->PCA,Array(),$this->debugObj->isDebug('headerMarkers'));
		$this->rowHeaders = $this->getLocalHeaders($this->mainTable,$this->PCA,$this->rowHeaders,$this->debugObj->isDebug('headerMarkers'));

		$this->felib->myQuery = t3lib_div::getIndpEnv('TYPO3_REQUEST_URL');
		$GLOBALS['TSFE']->fe_user->setKey('ses',$this->prefixId.'.lastQuery',$this->felib->myQuery);

		$myConf = $this->confObj->latest['list.'];
		$order = $myConf['order'] ? $myConf['order'] : 'tstamp DESC';
		$limit = $myConf['limit'] ? $myConf['limit'] : '10';

		$this->piVars['searchmode'] = 1;
		$this->piVars['search'] = Array();

		$this->doTotalList = 1;
		$this->getResultList($order,$limit);

		if (count($tmp=$this->felib->lastResultList)) {
			if (!$this->PCA['todo']['Uid']) {
				$this->PCA['todo']['Uid'] = $tmp[0];
			}
			$content .= $this->getShowEdit();
		} else {
			$content .= ''; //'ERROR: nothing found !';
		}

		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function doCatMenu () {
		$content = '';
		$this->markers = Array();

		$this->globalMarkers = $this->felib->globalMarkers;
		$this->globalMarkers['###BACK_URL###'] = $this->felib->todo['BackUrl'];

		$this->template = $this->templateObj->getTemplate('catMenu',$this->globalMarkers);

		$this->clConf = $this->confObj->cat['menu.'];
		$catConf = $this->PCA['conf'][$this->clConf['field']];
		if (!$this->clConf['order']) {
			$this->clConf['order'] = 'sorting';
		}
		if (!$this->clConf['table']) {
			$this->clConf['table'] =  $catConf['foreign_table'];
		}
		if (!$this->clConf['MM']) {
			$this->clConf['MM'] = $catConf['MM'];
		}
		if ($this->clConf['levels']>10) {
			$this->clConf['levels'] = 10;
		}
		if (!isset($this->clConf['wrap']) && !isset($this->clConf['wrapAll']) && !isset($this->clConf['wrapItemAndSub'])) {
			$this->clConf['wrap'] = '<li>|</li>';
			$this->clConf['wrapItemAndSub'] = '<ul>|</ul>';
		}
		if (!$this->clConf['template']) {
			if (!$this->clConf['subpart']) {
				$this->clConf['template'] = '###uid###-###title### (###myHasEntries###/###myHasSubEntries###)  ###link###';
			} else {
				$this->clConf['template'] = $this->templateObj->getSubpart($this->template,'###'.$this->clConf['subpart'].'###');
			}
		}

		$this->clConf['url'] = $this->felib->getTypolinkURL($this->listPage,'&type='.$GLOBALS['TSFE']->type,$PCA['cache']);
		if (!$this->clConf['link']) {
			$this->clConf['link'] = '<a href="###myUrl######mySearch###">[&gt; ###title###]</a>';
		}

		$this->clConf['select'] = $this->clConf['table'].'.*, '.$this->clConf['MM'].'.uid_local, IF('.$this->clConf['MM'].'.uid_foreign>0,1,0) AS myHasEntries';
		$this->clConf['tables'] = $this->clConf['table'].' LEFT JOIN '.$this->clConf['MM'].' ON '.$this->clConf['MM'].'.uid_foreign='.$this->clConf['table'].'.uid';

		$this->clConf['where'] = $this->cObj->enableFields($this->clConf['table']);
		$query = $this->clConf['listwhere'].($this->clConf['idlist'] ? 'AND uid IN ('.$this->clConf['idlist'].')': '' ).$this->clConf['where'];
		$this->clConf['group'] = ''; // $this->clConf['MM'].'.uid_foreign';
		$idList = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			$this->clConf['select'],$this->clConf['tables'],$query,$this->clConf['group'],$this->clConf['order'],'1000','uid');
		$this->debugObj->debugIf('catmenu',Array('$this->clConf'=>$this->clConf, 'File:Line'=>__FILE__.':'.__LINE__));
		$this->debugObj->debugIf('catmenuDetails',Array('$catConf'=>$catConf, '$query'=>$query, '$idList'=>$idList, 'File:Line'=>__FILE__.':'.__LINE__));

		$this->clConf['selectMain'] = 'count('.$this->mainTable.'.uid) as cnt, '.$this->clConf['MM'].'.uid_foreign AS myCat';
		$this->clConf['tablesMain'] = $this->mainTable.' LEFT JOIN '.$this->clConf['MM'].' ON '.$this->clConf['MM'].'.uid_local='.$this->mainTable.'.uid';
		$this->clConf['whereMain'] = $this->cObj->enableFields($this->mainTable);
		$this->clConf['groupMain'] = $this->clConf['MM'].'.uid_foreign';
		$this->clConf['catCount'] = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			$this->clConf['selectMain'],$this->clConf['tablesMain'],'1=1 '.$this->clConf['whereMain'],$this->clConf['groupMain'],'','1000','myCat');

		$content .= $this->listCatMenu($idList,1);

		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$$idList: ...
	 * @param	[type]		$level: ...
	 * @return	[type]		...
	 */
	function listCatMenu(&$idList,$level) {
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
			$wrapAll = explode('|',($this->clConf['level.'][$level.'.']['wrapAll'] ? $this->clConf['level.'][$level.'.']['wrapAll'] : $this->clConf['wrapAll']),2);
			$wrapItemAndSub = explode('|',($this->clConf['level.'][$level.'.']['wrapItemAndSub'] ?
				$this->clConf['level.'][$level.'.']['wrapItemAndSub'] : $this->clConf['wrapItemAndSub']),2);
			$content = $wrapAll[0];


			for (reset($idList);$key=key($idList);next($idList)) {
				$idList[$key]['myCntEntries'] = intval($this->clConf['catCount.'][$key.'.']['cnt']);
				$idList[$key]['subcats'] = Array();
				$idList[$key]['mySearch'] .= '&'.$this->prefixId.'[searchmode]=1&'.$this->prefixId.'[search]['.$this->clConf['field'].']['.$key.']='.$key;
				$subContent = '';
				$hasSubEntries = 0;
				$cntSubEntries = 0;
				if ($level<$this->clConf['levels']) {
					$query = $this->clConf['parent'].'='.$key.' '.$this->clConf['where'];
					$nextList = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
						$this->clConf['select'],$this->clConf['tables'],$query,$this->clConf['group'],$this->clConf['order'],'1000','uid');
					$this->debugObj->debugIf('catmenuDetails',Array('$query'=>$query, '$nextList'=>$nextList, 'File:Line'=>__FILE__.':'.__LINE__));
					foreach ($nextList as $myKey=>$value) {
						$hasSubEntries += $value['myHasEntries']+$value['myHasSubEntries'];
						$idList[$key]['subcats'][] = $myKey;
						$idList[$key]['myCntEntries'] += intval($this->clConf['catCount.'][$myKey.'.']['cnt']);
						if ($this->clConf['linkIfSubcats']) {
							$idList[$key]['mySearch'] .= '&'.$this->prefixId.'[search]['.$this->clConf['field'].']['.$myKey.']='.$myKey;
						}
					}

					$subContent .= $this->listCatMenu($nextList,$level+1);
				}

				$idList[$key]['myHasSubEntries'] = $hasSubEntries;
				$idList[$key]['myLink']='';

				if ($idList[$key]['myHasEntries']) {
					$idList[$key]['myLink']=$this->cObj->substituteMarkerArray($link,$idList[$key],'###|###');
				} else if ($this->clConf['linkIfSubcats'] && $hasSubEntries) {
					$idList[$key]['myLink']=$this->cObj->substituteMarkerArray($link,$idList[$key],'###|###');
				}
				if (!$this->clConf['hideEmpty'] || ($idList[$key]['myCntEntries']>0 AND ($hasSubEntries>0 || $idList[$key]['myHasEntries']>0))) {
					$content .= $wrapItemAndSub[0].$wrap[0].$this->cObj->substituteMarkerArray($template,$idList[$key],'###|###').$wrap[1].$subContent.$wrapItemAndSub[1];
				}
			}
			$content .= $wrapAll[1];
		}

		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function DoSimpleBasket() {
		$content = 'Dummy-Function: DoSimpleBasket';

		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$uid: ...
	 * @return	[type]		...
	 */
	function getRow ($uid) {
		$row = Array();

		$select = $this->mainTable.'.*'.$this->mainJoin.' ';
		if (count($this->PCA['ctrl']['calc'])>0) {
			$select .= ','.implode(',',$this->PCA['ctrl']['calc']).' ';
		}
		$table = str_replace('###feuser.uid###',$this->permitObj->getFeUid(),$this->mainTable.$this->mainJoinTable);
		$query = $this->mainTable.'.deleted=0 and '.$this->mainTable.'.uid='.$uid;
		$group = ($this->groupBy) ? $this->groupBy : '';
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$table,$query,$group,'');
		if ($GLOBALS['TYPO3_DB']->sql_error()) {
			t3lib_div::debug(Array('ERROR:'=>$GLOBALS['TYPO3_DB']->sql_error(), '$select'=>$select, '$table'=>$table, '$query'=>$query, '$res'=>$res, 'File:Line'=>__FILE__.':'.__LINE__));
		} else {
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res)>0) {
				$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
				if ($fgd=$this->PCA['ctrl']['fe_group_details']) {
					$x1 = Array($fgd=>$row[$fgd], $fgd.'_default'=>$row[$fgd.'_default']);
					if (intval($row[$fgd])==0 && intval($row[$fgd.'_default'])) {
						$row[$fgd] = intval($row[$fgd.'_default']);
						$x1['replaced by = '] = $row[$fgd];
					} else if (intval($row[$fgd])<-9) {
						$row[$fgd] = 0;
						$x1['replaced by = '] = $row[$fgd];
					}
					$x1['File:Line'] =__FILE__.':'.__LINE__;
					$this->debugObj->debugIf('fe_group_details',$x1);
				}
				// Now check for restrictions ... (but only, if not own Record)
				$access = true;
				$pcaCtrl = $this->PCA['ctrl'];
				$myTime = time();
				if (!$pcaCtrl['crfeuser_id'] || !$this->permitObj->getFeUid() || ($row[$pcaCtrl['crfeuser_id']]!=$this->permitObj->getFeUid() && !$this->permitObj->allowed('admin'))) {
					if ( ($pcaCtrl['enablecolumns']['starttime'] && $row[$pcaCtrl['enablecolumns']['starttime']]>$myTime) ||
						 ($pcaCtrl['enablecolumns']['endtime'] && $row[$pcaCtrl['enablecolumns']['endtime']]>86400 && $row[$pcaCtrl['enablecolumns']['endtime']]<$myTime) ||
						 ($pcaCtrl['enablecolumns']['disabled'] && $row[$pcaCtrl['enablecolumns']['disabled']])
						) {
						$access = false;
					}
				}
				if ($pcaCtrl['enablecolumns']['fe_group'] && !$this->permitObj->allowed('admin')) {
					$tmp = intval($row[$pcaCtrl['enablecolumns']['fe_group']]);
					if (!$this->permitObj->getFeUid()) {
						if ($tmp!=0 && $tmp!=-1) {
							$access = false;
						}
					} else if (!$pcaCtrl['crfeuser_id'] || $row[$pcaCtrl['crfeuser_id']]!=$this->permitObj->getFeUid() ) {
						if ($tmp!=0 && $tmp!=-2 && $this->felib->feGroups[$tmp]!=$tmp) {
							$access = false;
						}
					}
				}
				if ($access) {
					$this->localPrepareDbRow($this->mainTable,$row,$this->PCA);
				} else {
					$row = Array();
					$this->PCA['todo']['NotFound'] = 1;
				}

			} else {
				$this->PCA['todo']['NotFound'] = 1;
			}
		}
		return ($row);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$mode: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$listitle: ...
	 * @return	[type]		...
	 */
	function getEditButtons ($mode,$row,&$PCA,$listitle='') {
		GLOBAL $TSFE;
		// mode: 0=list/normal 1=list/popup-mode 2=view 3=edit 4=search
		$m = Array ('###BUTTON_ADD###'=>'', '###BUTTON_CANCEL###'=>'', '###BUTTON_UPDATE###'=>'',
			'###BUTTON_SAVE###'=>'', '###XTRA_SAVE###'=>'', '###BUTTON_RELOAD###'=>'');

		$m['###BUTTON_CANCEL###'] = $this->felib->getFormCancelButton($this->felib->todo['BackUrl'],'');
		if ($this->editPopup) {
			$m['###BUTTON_CANCEL###'] = $this->felib->getFormCancelButton('','',1);
		}

		$jsOpen0 = 'var bw='.QT.QT.'; bw = window.open('.QT;
		$jsOpen1 = QT.','.QT.$this->winName.'Edit'.QT.','.QT.'height='.$this->popupH.',width='.$this->popupW.
					',status=0,menubar=0,location=0,resizable=1,scrollbars=1'.QT.'); bw.focus();';

		if ($mode<2) {
			if (is_array($row)) {
				$params = $this->PCA['conf']['listTitle']['ATagParams'] ?
					Array('ATagParams'=>$this->PCA['conf']['listTitle']['ATagParams']) : Array();
				$dlf = $this->PCA['conf']['listTitle']['directLinkField'];
				$myUrl = $this->felib->getTypolinkURL($this->editPage,'&type='.$TSFE->type.'&uid='.urlencode($row['uid']),$PCA['cache']);
//				$myXUrl = $this->felib->getTypolink('abc',$this->editPage,'&uid='.urlencode($row['uid']),$PCA['cache'],0,'',1);
//				t3lib_div::debug(Array('$myXUrl'=>$myXUrl, 'File:Line'=>__FILE__.':'.__LINE__));
				if ($dlf && $row[$dlf]) {
					$tmp = t3lib_div::trimExplode ("\n",$row[$dlf]);
					$tmpUrl = $this->PCA['conf'][$dlf]['uploadfolder'].'/'.$tmp[0];
					$filename = t3lib_div::getFileAbsFileName($tmpUrl);
					if (file_exists($filename)) {
						$myUrl = $tmpUrl;
						if ($this->PCA['conf']['listTitle']['directLinkTarget']) {
							$params['target'] = $this->PCA['conf']['listTitle']['directLinkTarget'];
						}
					}
				}
				$m['###LINK_TO_DETAILS###'] = $myUrl;
				$hrefCode = $this->editPopup ? $myUrl.$this->felib->emptyUrl : $myUrl;
				$jsCode = $this->editPopup ? $jsOpen0.$myUrl.$jsOpen1 : '';
				$m['###JSCODE_TO_DETAILS###'] = $jsCode;
				$m['###BUTTON_VIEW###'] = $this->felib->getViewButton ($jsCode,$hrefCode,0,$row[$this->disableField],$row[$PCA['ctrl']['crfeuser_id']],$params);
				$m['###TITLE_VIEW###'] = $this->felib->getViewTitle ($jsCode,$hrefCode,$listitle,$row[$this->disableField],$row[$PCA['ctrl']['crfeuser_id']],$params);
			} else {
				$m['###BUTTON_VIEW###'] = $m['###TITLE_VIEW###'] = '';
			}
		} else if ($mode==3) {
			$m['###BUTTON_SAVE###'] =  $this->felib->getFormSaveButton();
			$m['###XTRA_SAVE###'] = $this->felib->getFormSaveButton();
			//why - is unused!$chtxt = strlen($PCA['ctrl']['reloadText'])>1 ? $PCA['ctrl']['reloadText'] : '';
			$m['###BUTTON_UPDATE###'] =
					'<a href="#" onclick="javascript:sgUpdateForm('.QT.$this->PCA['ctrl']['updateText'].QT.'); return false;">'.
					$this->felib->getFormUpdateButton().'</a>';
			$m['###BUTTON_RELOAD###'] =
					'<a href="#" onclick="javascript:sgReLoadForm('.QT.$this->PCA['ctrl']['reloadText'].QT.'); return false;">'.
					$this->felib->getFormReloadButton().'</a>';
			if (intval($PCA['todo']['Uid'])>0) {
				if (!$PCA['todo']['RTL']) {
					$m['###BUTTON_CANCEL###'] =	$this->felib->getFormCancelButton($this->myPage,'&type='.$TSFE->type.'&uid='.urlencode($row['uid']));
				}
			}
		}

		// Buttons for list and View-Mode, but only if $row is defined
		if ($mode<3 && is_array($row)) {
			$mq = $mode<2 ? $this->felib->myQuery : $this->myPage ;
			$m['###BUTTON_DOHIDE###'] = $this->felib->getHiddenStateSwitch
					($row[$this->disableField],$mq,$row['uid'],'',$row[$PCA['ctrl']['crfeuser_id']],$row,0,$PCA);
			$m['###BUTTON_ONLYDOHIDE###'] = $this->felib->getHiddenStateSwitch
					($row[$this->disableField],$mq,$row['uid'],'',$row[$PCA['ctrl']['crfeuser_id']],$row,1,$PCA);
			$m['###BUTTON_DODELETE###'] = $this->felib->getDeleteButton
					($row[$this->disableField],$mq,$row['uid'],'',$row[$PCA['ctrl']['crfeuser_id']],'',$row);

			$myUrl = $this->felib->getTypolinkURL($this->editPage,'&type='.$TSFE->type.'&uid='.urlencode($row['uid']).'&dE=1'.
				($this->returnEditToList ? '&rTL=1' : '') ,0);
			$hrefCode = ($this->editPopup && $mode<2) ? $myUrl.$this->felib->emptyUrl : $myUrl;
			$jsCode = ($this->editPopup && $mode<2) ? $jsOpen0.$myUrl.$jsOpen1 : '';

			// Check, if Record is locked ...
			$isLocked = (is_callable(array($TSFE, 'isRecordLocked'))) ? $TSFE->isRecordLocked($this->mainTable,$row['uid']) : Array();
			$m['###BUTTON_EDIT###'] = $this->felib->getEditButton ($jsCode,$hrefCode,
				0,$row[$this->disableField],$row[$PCA['ctrl']['crfeuser_id']],$row[$this->fixedField],0,$row,$isLocked);
			$m['###XTRA_EDIT###'] = $this->felib->getXtraEditButton ($jsCode,$hrefCode,0,$row[$this->disableField],$row['manuser_id']);

			$myUrl = $this->felib->getTypolinkURL($this->editPage,'&type='.$TSFE->type.'&dE=1&dN=1&nUid='.urlencode($row['uid']).
				($this->returnEditToList ? '&rTL=1' : ''),0);
			$hrefCode = ($this->editPopup && $mode<2) ? $myUrl.$this->felib->emptyUrl : $myUrl;
			$jsCode = ($this->editPopup && $mode<2)  ? $jsOpen0.$myUrl.$jsOpen1 : '';
			$m['###BUTTON_NEWFROMUID###'] = $this->felib->getEditButton($jsCode,$hrefCode,
					1,$row[$this->disableField],$row[$PCA['ctrl']['crfeuser_id']],$row[$this->fixedField],1,$row);
		} else {
			$m['###BUTTON_DOHIDE###'] =  $m['###BUTTON_DODELETE###'] = $m['###BUTTON_ONLYDOHIDE###'] = '';
			$m['###BUTTON_EDIT###'] =  $m['###XTRA_EDIT###'] = $m['###BUTTON_NEWFROMUID###'] = '';
		}

		// Always: Button_Add
		// mode: 0=list/normal 1=list/popup-mode 2=view 3=edit 4=search
		$myUrl = $this->felib->getTypolinkURL($this->editPage,'&no_cache=1&type='.$TSFE->type.'&dE=1&dN=1'.
			($this->returnEditToList ? '&rTL=1' : ''), 0);
		$hrefCode = $this->editPopup ? $myUrl.$this->felib->emptyUrl : $myUrl;
		$jsCode = $this->editPopup ? $jsOpen0.$myUrl.$jsOpen1 : '';
		$this->checkQuotas('maxRecords');
		$noNewRecords = $this->quota['noAddButton'];
		$m['###QUOTA_LISTMSG###'] = $this->quota['listmsg'];
		if ($mode<2) { // SG060217 was 3;
			$m['###BUTTON_ADDENTRY###'] = $this->felib->getAddEntryButton($jsCode,$hrefCode,$noNewRecords);
			$m['###BUTTON_ADD###'] = $this->felib->getAddButton($jsCode,$hrefCode,0,$noNewRecords);
		} else if ($mode==4) {
			$m['###BUTTON_ADD###'] = $this->felib->getAddEntryButton($jsCode,$hrefCode,$noNewRecords);
			$m['###BUTTON_ADDONLY###'] = $this->felib->getAddButton($jsCode,$hrefCode,0,$noNewRecords);
		} else {
			$m['###BUTTON_ADDENTRY###'] =  $m['###BUTTON_ADD###'] = $m['###BUTTON_ADDONLY###'] = '';
		}

		return ($m);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$mode: ...
	 * @return	[type]		...
	 */
	function processHideDelete ($mode) {
		// mode: 0=list 2=view
		$content = '';

		// Check, if record has to get hidden / unhidden -------------------------------------------------------------------
		if (($this->PCA['todo']['Hide']==1 || $this->PCA['todo']['UnHide']==1) && $this->PCA['todo']['Uid']>0)  {
			$log = '';
			if (is_array($this->PCA['ctrl']['unhide_log'])) {
				$cul = $this->PCA['ctrl']['unhide_log'];
				$log = $cul['time'].'='.time().', '.
						$cul['feuser'].'='.intval($this->permitObj->getFeUid()).', '.
						$cul['beuser'].'='.intval($this->permitObj->getBeUid()).', ';
			}
			$this->doClearCache = true;
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',$this->mainTable,'uid='.$this->PCA['todo']['Uid']);
			if ($this->PCA['todo']['Hide']==1) {
				$query =  'UPDATE '.$this->mainTable.' SET '.$log.$this->disableField.'=1 WHERE uid='.$this->PCA['todo']['Uid'].';';
				$logText = 'Hide';
				$this->editMessage = $this->cObj->cObjGetSingle
									($this->conf['editMessages.']['hide'],$this->conf['editMessages.']['hide.']);
			} else if ($this->PCA['todo']['UnHide']==1) {
				$query =  'UPDATE '.$this->mainTable.' SET '.$log.$this->disableField.'=0 WHERE uid='.$this->PCA['todo']['Uid'].';';
				$logText = 'Unhide';
				$this->editMessage = $this->cObj->cObjGetSingle
									($this->conf['editMessages.']['unhide'],$this->conf['editMessages.']['unhide.']);
			}
			$res = $GLOBALS['TYPO3_DB']->sql_query($query);
			//$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$table,$query,$group,$order);
			if ($GLOBALS['TYPO3_DB']->sql_error()) {
				t3lib_div::debug (Array("query"=>$query, "res"=>$res, "error"=>$GLOBALS['TYPO3_DB']->sql_error()));
			} else {
				$this->felib->clearCache($this->PCA);
				if ($this->editPopup && $mode==2) {
					$content .= $this->felib->jsInsert('openerReload(0);');
				}
				// OK - row was hidden/unhidden; now check if I have to send a log-mail
				if ($this->felib->checkForLogMail($this->PCA['mail'],2,0)) {
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',$this->mainTable,'uid='.$this->PCA['todo']['Uid']);
 					if ($res) {
						$logRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
					} else {
						$logRow = Array();
						$logRow['uid'] = $this->PCA['todo']['Uid'];
					}
					$logRow['hide'] = $logText;
					$this->felib->sendLogMail($this->PCA,2,$logRow,$logRow,0);
				}
			}
			$this->PCA['todo']['Save'] = 0;
		// Check, if record has to be deleted ----------------------------------------------------------------------------
		} else if ($this->PCA['todo']['Delete']==1 && $this->PCA['todo']['Uid']>0)  {
			$log = '';
			if (is_array($this->PCA['ctrl']['delete_log'])) {
				$cdl = $this->PCA['ctrl']['delete_log'];
				$log = $cdl['time'].'='.time().', '.
						$cdl['feuser'].'='.intval($this->permitObj->getFeUid()).', '.
						$cdl['beuser'].'='.intval($this->permitObj->getBeUid()).', ';
			}
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',$this->mainTable,'uid='.$this->PCA['todo']['Uid']);
			if ($res) {
				$logRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			} else {
				$logRow = Array('uid' => $this->PCA['todo']['Uid']);
			}
			$query =  'UPDATE '.$this->mainTable.' SET '.$log.'deleted=1 WHERE uid='.intval($this->PCA['todo']['Uid']).';';
			$res = $GLOBALS['TYPO3_DB']->sql_query($query);
			//$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$table,$query,$group,$order);
			if ($GLOBALS['TYPO3_DB']->sql_error()) {
				t3lib_div::debug (Array("query"=>$query, "res"=>$res, "error"=>$GLOBALS['TYPO3_DB']->sql_error()));
			} else {
				//t3lib_div::debug (Array("query"=>$query, "res"=>$res, "error"=>$GLOBALS['TYPO3_DB']->sql_error()));
				$this->felib->clearCache($this->PCA);
				if ($mode==2) {
					if ($this->editPopup) {
						$content .= $this->felib->jsInsert('openerReload(1);');
					} else {
						$content .= $this->felib->jsInsert('location.href="'.$this->felib->todo['BackUrl'].'";');
					}
				}
				$this->PCA['todo']['New'] = 0;
				$this->PCA['todo']['Edit'] = 0;
				$this->PCA['todo']['Save'] = 0;
				$this->PCA['todo']['Reload'] = 0;
				$this->PCA['todo']['Uid'] = 0;
				$this->PCA['todo']['Hide'] = 0;
				$this->PCA['todo']['UnHide'] = 0;
				// OK - row was deleted; now check if I have to send a log-mail
				if ($this->felib->checkForLogMail($this->PCA['mail'],3,0)) {
					$this->felib->sendLogMail($this->PCA,3,$logRow,$logRow,0);
				}
			}
		}

		return ($content);




	}

	/*********************************************************************************
	**
	**
	**
	*********************************************************************************/

	/**
	 * [Put your description here]
	 *
	 * @return	[type]		...
	 */
	function DoImport()	{
		$content = '== ERROR: Import not enabled ! ==';

		return ($content);
	}

	/*********************************************************************************
	**
	**
	**
	*********************************************************************************/

	/**
	 * [Put your description here]
	 *
	 * @return	[type]		...
	 */
	function DoInfo()	{
		GLOBAL $TSFE;

		$TSFE->set_no_cache();
		if ($this->debugObj->isDebug('cache')) {
			$content .= '<H1><font color="red">Set_No_Cache !</font></H1>';
		}
		$content = '<kbd>INFO<br />====<br /><br />';

		if ($this->permitObj->useEditMode()) {
			$content .= '- Using WebSite Edit-Templates<br />';
			if ($this->permitObj->getFeUid()) {
				$content .= '&nbsp;  FeUser - UID='.$this->permitObj->getFeUid().', UserName="'.$this->permitObj->getFeUser('username').'"<br />';
			}
			if ($this->permitObj->getBeUid()) {
				$content .= '&nbsp;  BeUser - UID='.$this->permitObj->getBeUid().', UserName="'.$this->permitObj->getBeUser('username').'"<br />';
			}
			for (reset($this->permitObj->allow);$key=key($this->permitObj->allow);next($this->permitObj->allow)) {
				$content .= '&nbsp;  - allow['.$key.']='.($this->permitObj->allow[$key]?'TRUE':'false').'<br />';
			}
		} else {
			$content .= '- Using standard WebSite Templates<br />';
		}

		$content .= '- Startingpoint ID for '.$this->extKey.' = '.$this->pid.'<br />'.
			'&nbsp; MainTable = "'.$this->mainTable.'"<br />'.
			'';
		$content .= '</kbd>';
		return $content;
	}


	/*********************************************************************************
	**
	**
	**
	*********************************************************************************/

	/**
	 * Service-Links (=PluginMode 80)
	 *
	 * @return	[type]		...
	 */
	function DoServiceLinks()	{
		$content = '';
		return $content;
	}


	/**
	 * Get more markers for single view/edit display  menu-part
	 *
	 * @param	[type]		$row: row of data, that has to be checked
	 * @param	[type]		$PCA: definition-array for table
	 * @return	[array]		markers
	 */
	function getMoreMenuMarkers($row,$PCA) {
		$xm = Array();
		return ($xm);
	}


	/**
	 * Get more markers for single view/edit display
	 *
	 * @param	[type]		$row: row of data, that has to be checked
	 * @param	[type]		$PCA: definition-array for table
	 * @return	[array]		markers
	 */
	function getMoreMarkers($row,$PCA) {
		$xm = Array();
		return ($xm);
	}


	/**
	 * Get markers for acknowledge part
	 *
	 * @param	[type]		$row: row of data, that has to be checked
	 * @param	[type]		$PCA: definition-array for table
	 * @return	[array]		markers
	 */
	function getAckMarkers($row,$PCA) {
		$xm = Array();
		return ($xm);
	}


	/**
	 * Check if acknowlegement is OK
	 *
	 * @return	[void]		markers
	 */
	function checkAck() {
		$this->ackMessage = '';
		return (true);
	}


	/**
	 * Get more markers for List (summaries)
	 *
	 * @param	[type]		$row: row of data, that has to be checked
	 * @param	[type]		$PCA: definition-array for table
	 * @return	[array]		markers
	 */
	function getListSummaryMarkers($r,$PCA) {
		$xm = Array();
		return ($xm);
	}

	/**
	 * Gets row of data for list/edit/view-mode
	 *
	 * @param	[type]		$mainTable: ...
	 * @param	[type]		$n: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$markers: ...
	 * @param	[type]		$em: ...
	 * @param	[type]		$dbg: ...
	 * @param	[type]		$mode: ...
	 * @return	[type]		...
	 */
	function getLocalDbRow($mainTable,$n,$row,$PCA, $markers=Array(),$em,$dbg=0,$mode=0) {
		// mode: 0=list/normal 1=list/popup-mode 2=view 3=edit 4=search 5=ack
		if (is_array($this->conf['printViews.'])) {
				$u['params'] = Array();
				$u['params']['id'] = $this->editPage;
				$u['params']['type'] = 98;
				$u['params']['uid'] = $row['uid'];
				$js = 'onclick="openPrintMode('.
					QT.'index.php?'.t3lib_div::implodeArrayForUrl('',$u['params'],'',1,0).QT.','.
					QT.''.QT.','.
					QT.$this->prefixId.'[printmode]'.QT.
					'); return(false);"';
				$markers['###BUTTON_PRINT###'] = '<a href="'.$this->felib->emptyUrl.'" '.$js.'>'.
					$this->constObj->getButton('printButton').'</a>';
			} else {
				$markers['###BUTTON_PRINT###'] = '';
			}
			return ($markers);
		}

	/**
	 * Header-markers
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$rowHeaders: ...
	 * @param	[type]		$dbg: ...
	 * @return	[type]		...
	 */
	function getLocalHeaders($table,$PCA,$rowHeaders=Array(),$dbg=0) {
		$onclick = $this->prefixId.'xajax_process_DemoData(xajax.getFormValues(\'search'.$this->felib->sMode.'\')); return(false); ';
		$rowHeaders['###XAJAXDEMO_SEARCH###'] = '<a href="" onclick="'.$onclick.'">Ajax-Demo</a>';
		//$rowHeaders['###XAJAX_DEMOSUBMIT###'] = '<a href="" onclick="alert(xajax.getFormValues(\'searchform_select_tx_cardata_pi1\')); ">Ajax-Demo</a>';
		$rowHeaders['###XAJAXDEMO_RESULT###'] = '<div id="xajax_id_DemoData">-- Ajax-Demo-Result --</div>';

		if (is_array($this->conf['printViews.'])) {
			$rowHeaders['###PRINTMODE###'] = '<select name="'.$this->prefixId.'[printmode]" class="form_medium_select" size="1">';
			for (reset($this->conf['printViews.']);$key=key($this->conf['printViews.']);next($this->conf['printViews.'])) {
				$rowHeaders['###PRINTMODE###'] .= '<option value="'.$key.'">'.
					($this->conf['printViews.'][$key]['title'] ? $this->conf['printViews.'][$key]['title'] : $key).'</option>';
			}
			$rowHeaders['###PRINTMODE###'] .= '</select>';
		} else {
			$rowHeaders['###PRINTMODE###'] = '';
		}
		return ($rowHeaders);
	}


	/**
	 * [Check $row locally, before saving to table]
	 *
	 * @param	[type]		$dbName: name of table
	 * @param	[type]		$row: row of data, that has to be checked
	 * @param	[type]		$PCA: definition-array for table
	 * @param	[type]		$errors: array of error-markers
	 * @return	[boolean]		TRUE if error was detected
	 */
	function localCheckDbRowForSave ($dbName,&$row,&$PCA,&$errors) {
		$errorMode = false;
		return ($errorMode);
	}

	/**
	 * [Prepare $row directly after load before processing]
	 *
	 * @param	[type]		$dbName: name of table
	 * @param	[type]		$row: row of data, that has to be checked
	 * @param	[type]		$PCA: definition-array for table
	 * @return	[boolean]		TRUE if error was detected
	 */
	function localPrepareDbRow ($dbName,&$row,&$PCA) {
		$errorMode = false;
		return ($errorMode);
	}



	/**
	 * [Prepare $row directly after load before processing]
	 *
	 * @param	[type]		$dbName: name of table
	 * @param	[type]		$row: row of data, that has to be checked
	 * @param	[type]		$PCA: definition-array for table
	 * @return	[boolean]		TRUE if error was detected
	 */
	function prepareNewRecord ($dbName,&$row,&$PCA) {
		$errorMode = false;
		return ($errorMode);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$mode: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$markers: ...
	 * @return	[type]		...
	 */
	function checkListOutput ($mode,$row,$PCA,$markers) {
		$content = '';
		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getQuotas () {
		GLOBAL $TSFE;

		$this->quota = Array();
		if (is_array($this->PCA['quota'])) {
			for (reset($this->PCA['quota']);$key=key($this->PCA['quota']);next($this->PCA['quota'])) {
				$this->quota[$key] = $this->PCA['quota'][$key];
				$lst = t3lib_div::trimExplode('||',$this->PCA['quota'][$key]['maxCount'],2);
				$myMax = 0;
				if (count($lst) && $lst[0]) for ($i=0;$i<count($lst);$i++) {
					$tmp = t3lib_div::trimExplode(':',$lst[$i],2);
					if (strcmp($tmp[0],'TSconfig')==0) {
						$this->TSconf = $TSFE->fe_user->getUserTSconf();
						$myMax = intval($this->TSconf['plugin.'][$this->prefixId.'.'][$tmp[1]]);
					} else if (strcmp($tmp[0],'group')==0) {
						$gr = t3lib_div::trimExplode('=',$tmp[1],2);
						$myMax = (in_array($gr[0],$TSFE->fe_user->groupData['title'])) ? intval($gr[1]) : 0;
					} else if (strcmp($tmp[0],'user')==0) {
						$myMax = $TSFE->fe_user ? intval($TSFE->fe_user->user[$tmp[1]]) : 0;
					} else if (strcmp($tmp[0],'const')==0 || strcmp($tmp[0],'set')==0) {
						$myMax = intval($tmp[1]) ;
					} else {
						$myMax = $this->felib->lCObj->insertData('{'.trim($conf['quota.'][$key]).'}') ;
					}
					if ($myMax) {
						break;
					}
				}
				$this->quota[$key]['maxCount'] = $myMax;
			}
			$this->debugObj->debugIf('quota',Array('$this->quota'=>$this->quota,	'File:Line'=>__FILE__.':'.__LINE__));
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$myQuota: ...
	 * @return	[type]		...
	 */
	function checkQuotas ($myQuota) {
		$this->quota['noAddButton'] = FALSE;
		$this->quota['noNewRecords'] = FALSE;
		$this->quota['singlemsg'] = '';
		$this->quota['listmsg'] = '';

		if (is_array($this->quota[$myQuota])) {
			$from = Array ('###max###','###cnt###','###left###','###act###');
			$to = Array (intval($this->quota[$myQuota]['maxCount']),0,0,0);
			if ($this->quota[$myQuota]['maxCount']>0 && strlen($this->PCA['ctrl']['crfeuser_id'])>1) {
				$query = 'deleted=0 AND '.$this->PCA['ctrl']['crfeuser_id'].'='.$this->permitObj->getFeUid();
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid',$this->mainTable,$query);
				$tmp = $res ? $GLOBALS['TYPO3_DB']->sql_num_rows($res) : 0;
				$this->quota[$myQuota]['isCount'] = $tmp;
				$to[1] = $tmp;
				$to[2] = $to[0]-$to[1];
				$to[3] = $tmp + 1;
				if ($tmp>=$this->quota[$myQuota]['maxCount']) {
					$this->quota['noNewRecords'] = TRUE;
					if ($this->quota[$myQuota]['disableAdd']) {
						$this->quota['noAddButton'] = TRUE;
					}
					$this->quota['singlemsg'] = $this->felib->lCObj->insertData($this->langObj->getLLL($this->quota[$myQuota]['noNewSingleMsg']));
					$this->quota['listmsg'] = $this->felib->lCObj->insertData($this->langObj->getLLL($this->quota[$myQuota]['noNewListMsg']));
				} else {
					$this->quota['singlemsg'] = $this->felib->lCObj->insertData($this->langObj->getLLL($this->quota[$myQuota]['newSingleMsg']));
					$this->quota['listmsg'] = $this->felib->lCObj->insertData($this->langObj->getLLL($this->quota[$myQuota]['newListMsg']));
				}
			} else if ($this->quota[$myQuota]['maxCount']==-1) {
				$this->quota['noNewRecords'] = TRUE;
				if ($this->quota[$myQuota]['disableAdd']) {
					$this->quota['noAddButton'] = TRUE;
				}
				$this->quota['singlemsg'] = $this->felib->lCObj->insertData($this->langObj->getLLL($this->quota[$myQuota]['absNoNewSingleMsg']));
				$this->quota['listmsg'] = $this->felib->lCObj->insertData($this->langObj->getLLL($this->quota[$myQuota]['absNoNewListMsg']));
			}
			$this->quota['singlemsg'] = str_replace ($from,$to,$this->quota['singlemsg']);
			$this->quota['listmsg'] = str_replace ($from,$to,$this->quota['listmsg']);
		}

		$this->debugObj->debugIf('quota',Array('$this->quota'=>$this->quota,	'File:Line'=>__FILE__.':'.__LINE__));
		return ($this->quota['noNewRecords']);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$row: ...
	 * @return	[type]		...
	 */
	function checkForIframe($row) {
		return (TRUE);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function registerAjaxFunctions () {
		if ($this->felib->xajax) {
			$this->felib->xajax->registerFunction(array('xajax_process_DemoData', &$this, 'xajax_process_DemoData'));
			return (TRUE);
		} else {
			return (FALSE);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function preprocessAjax () {
		if ($this->felib->xajax) {
			$this->felib->xajax->processRequests();
			$GLOBALS['TSFE']->additionalHeaderData[$this->prefixId.'_xajax'] = $this->felib->xajax->getJavascript(t3lib_extMgm::siteRelPath('xajax'));
			return (TRUE);
		} else {
			return (FALSE);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$data: ...
	 * @return	[type]		...
	 */
	function xajax_process_DemoData ($data) {
		$encoding = $this->confObj['encoding'];
		$objResponse = new tx_xajax_response($encoding ? $encoding : 'utf-8');
		$objResponse->setCharEncoding($encoding ? $encoding : 'utf-8');
		$response = '--time='.time().'--';
		if ((float)TYPO3_version<4.1) {
			$tmp = array('á'=>'a', 'à'=>'a', 'â'=>'a', 'ä'=>'ae', 'ö'=>'oe', 'ü'=>'ue', 'Ä'=>'Ae', 'Ö'=>'Oe', 'Ü'=>'Ue', 'ß'=>'ss');
			$response = str_replace(array_keys($tmp),Array_values($tmp),$response);
		}
		$objResponse ->addAssign('xajax_id_DemoData','innerHTML',$response);
		//$objResponse ->addAssign('xajax_id_DemoData','innerHTML','--time='.time().'--'.t3lib_div::view_array(Array('$data'=>$data)));
		return ($objResponse->getXML());
	}


	/**
	 * Link string to the current page.
	 * Returns the $str wrapped in <a>-tags with a link to the CURRENT page, but with $urlParameters set as extra parameters for the page.
	 *
	 * @param	string		The content string to wrap in <a> tags
	 * @param	array		Array with URL parameters as key/value pairs. They will be "imploded" and added to the list of parameters defined in the plugins TypoScript property "parent.addParams" plus $this->pi_moreParams.
	 * @param	boolean		If $cache is set (0/1), the page is asked to be cached by a &cHash value (unless the current plugin using this class is a USER_INT). Otherwise the no_cache-parameter will be a part of the link.
	 * @param	integer		Alternative page ID for the link. (By default this function links to the SAME page!)
	 * @return	string		The input string wrapped in <a> tags
	 * @see pi_linkTP_keepPIvars(), tslib_cObj::typoLink()
	 */
	function pi_linkTP_URL($urlParameters=array(),$cache=0,$altPageId=0)	{
		$conf=array();
		$conf['useCacheHash'] = $this->pi_USER_INT_obj ? 0 : $cache;
		$conf['no_cache'] = $this->pi_USER_INT_obj ? 0 : !$cache;
		$conf['parameter'] = $altPageId ? $altPageId : ($this->pi_tmpPageId ? $this->pi_tmpPageId : $GLOBALS['TSFE']->id);
		$conf['additionalParams'] = $this->conf['parent.']['addParams'].t3lib_div::implodeArrayForUrl('',$urlParameters,'',1).$this->pi_moreParams;

		return t3lib_div::getIndpEnv('TYPO3_REQUEST_HOST').'/'.$this->cObj->typoLink_URL($conf);
	}


}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.txsg_base.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.txsg_base.php']);
}
?>