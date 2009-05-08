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
 * Plugin 'sg_zlib'.
 * @author	Stefan Geith <typo3dev2007@geithware.de>
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *  163: class tx_sgzlib
 *
 *              SECTION: CLASS instantiate and init/config
 *  215:     function _fCount ($name=NULL)
 *  238:     function __destruct()
 *  246:     function init($designator, tx_sglib_factory $factoryObj)
 *
 *              SECTION: Some helper functions (should become obsolete...)
 *  325:     function GPdefault($gp,$default='')
 *
 *              SECTION: Typolinks
 *  355:     function getTypoLinkItem ($params)
 *  378:     function getTypolinkURL ($myPageID,$myParams='',$allowCaching=0,$myTarget='',$myDbg='')
 *  409:     function getTypolink ($textLink,$myPageID,$myParams='',$allowCaching=0,$myTarget='',$myDbg='')
 *  434:     function clearCache($PCA)
 *  474:     function myParseUrl ($url,$paramsReplace=Array())
 *
 *              SECTION: Some usefull Wraps
 *  553:     function quote ($text,$komma='')
 *  564:     function dquote ($text,$komma='')
 *  577:     function replaceArray ($content,$markers,$more=FALSE,$wrap='')
 *  608:     function replaceArrayArray ($content,$markers,$more=FALSE,$wrap='')
 *  631:     function sendMail ($mailto,$subject,$mailbody,$hd,$params='')
 *
 *              SECTION: JS functions
 *  665:     function jsInsert ($text)
 *
 *              SECTION: Logging
 *  699:     function writelog($details,$action=1,$data=Array(),$type=10,$details_nr=1,$error=0,$event_pid=1 )
 *
 *              SECTION: Partial implementation of getFeSingleField - functionally like in be
 *  759:     function getPCA ($dbTable,$pluginName='myData',$myPid=0,$pluginConf=Array(),$piVars=Array(),$PCA=Array(),$dbg=0)
 *  939:     function prepareXajax ($prefixId)
 *  968:     function doXajaxFieldWrap($text,$id="test",$em=0)
 *  979:     function mergeTsArray ($dest,$source,$mode=0)
 * 1017:     function getFeSingleField($table,$field,$row,$em=0,$PCA=Array(),$opt=Array())
 * 1031:     function getPAfieldParam ($PCA,$field,$value,$wrap=Array())
 * 1052:     function getFeSingleField_SW($table,$field,$row,$em,&$PCA,$opt=Array())
 * 1149:     function getSingleField_typeInput($table,$field,$row,$em,&$PCA,$myMode,$opt=Array())
 * 1241:     function getSingleField_typeText($table,$field,$row,$em,&$PCA,$myMode,$opt=Array())
 * 1306:     function getSingleField_typeCheck($table,$field,$row,$em,&$PCA,$myMode='',$opt=Array())
 * 1358:     function getSingleField_typeSelect($table,$field,$row,$em,&$PCA,$myMode,$opt=Array())
 * 1660:     function getOnchangeSelect($PCA,$field)
 * 1700:     function getSelectSearchList($myItems,$name,$field,$value,$onc,$classname,$myMode,$PCA,$em=0)
 * 1902:     function getSelectFormList($myItems,$name,$field,$value,$classname,$myMode,$PCA,$em=0)
 * 2037:     function getSingleField_typeList($table,$field,$row,$em,&$PCA,$myMode,$size='s',$mode=0,$opt=Array())
 * 2080:     function getClassTag($field,$classType,$PCA,$mode=0,$submode='')
 * 2134:     function getClassModeTag($field,$classType,$PCA,$mode='',$submode='')
 * 2173:     function getImages ($table,$field,$row,$PCA,$size,$mode,$cpt='',$url='')
 * 2295:     function getFileLinks ($table,$field,$row,$PCA)
 *
 *              SECTION: DB functions
 * 2352:     function getDbList ($q,$dbg=0)
 * 2434:     function getDbPages ($r,$markers=Array(),$myPage='',$pageId=0,$pConf=Array(),$dbg=0)
 * 2467:     function getPageBrowser ($r,$pageId,$params,$pointer='pg',$pConf=Array(),$idlist='')
 * 2684:     function getDbSearchFields ($dbName,$PCA,$piVarSearch,$markers=Array(),$dbg=0)
 * 3027:     function getDbExportSection ($dbName,$PCA,$piVarSearch=Array(),$qa=Array(),$r=Array())
 * 3077:     function getDbBuildQuery ($dbName,$PCA,$piVarSearch,$markers=Array(),$dbg=0)
 * 3230:     function getDbBuildSingleQuery ($dbName,$PCA,$key,$def,$text)
 * 3360:     function getDbEnableColumns ($dbName,$PCA,$piVarSearch,$q)
 * 3494:     function getDbBuildOrder ($dbName,$PCA,$piVarSearch,$markers=Array(),$dbg=0)
 * 3528:     function getDbBuildGroup ($dbName,$PCA,$piVarSearch,$markers=Array(),$dbg=0)
 * 3561:     function getDbHeaders ($dbName,$PCA,$markers=Array(),$dbg)
 * 3600:     function getDbRow ($dbName,$n,$row,&$PCA, $markers=Array(),$em=0,$dbg=0)
 * 4028:     function getDbFieldLink ($PCA,$m,$row,$key)
 * 4133:     function checkDbRowForSave ($dbName,&$row,&$PCA,&$errors)
 * 4354:     function saveRowToDb ($dbName,&$myPiVars,&$PCA)
 * 4570:     function dateTimeStringToTime($myDate)
 * 4595:     function dateStringToTime($myDate,$hour=-1,$minute=0,$second=1)
 * 4625:     function timeStringToTime($myTime)
 * 4647:     function dateCompareString ($myDate,$myFName)
 * 4771:     function checkForLogMail ($mailConf,$mailMode,$dbg)
 * 4834:     function sendLogMail ($PCA,$mailMode,$row,$oldrow=Array(),$dbg=0)
 *
 *              SECTION: Button Functions
 * 4958:     function getAddButton ($jsCode='',$hrefCode='',$bigMode=0,$lockMode=0,$message='')
 * 4985:     function getAddEntryButton ($jsCode='',$hrefCode='',$lockMode=0,$message='')
 * 5017:     function getEditButton ($jsCode='',$hrefCode='',$bigMode=0,$myHiddenState,$myOwner,$myLocked=0,$editAsNew=0,$row=Array(),$isLocked=Array())
 * 5082:     function getXtraEditButton ($jsCode='',$hrefCode='',$bigMode=0,$myHiddenState,$myXtraUser)
 * 5109:     function getViewButton ($jsCode='',$hrefCode='',$bigMode=0,$myHiddenState,$myOwner,$params=Array())
 * 5139:     function getViewTitle ($jsCode='',$hrefCode='',$title,$myHiddenState,$myOwner,$params=Array())
 * 5162:     function getFormSaveButton()
 * 5181:     function getFormReloadButton()
 * 5191:     function getFormUpdateButton()
 * 5204:     function getFormCancelButton($myPage,$entryParam,$closeMode=0)
 * 5230:     function getHiddenStateSwitch ($myState,$myPage,$uid,$wordparam,$myOwner,$row=Array(),$noText=0,$PCA=Array())
 * 5279:     function getDeleteButton($myHiddenState,$myPage,$uid,$wordparam,$myOwner,$myParams='',$row=Array())
 *
 *              SECTION: Functions vor JavaScript FE-Editing and Searchforms
 * 5384:     function addFileLink ($PCA,$field,$listmode='unknown',$mode=0,$row=Array())
 * 5443:     function addDbLink ($PCA,$field,$mode,$row=Array())
 * 5524:     function addListModify ($PCA,$varname,$mode='Up',$idMode=0)
 * 5551:     function autoInsert ()
 *
 *              SECTION: Functions for Import
 * 5747:     function getImportFile ($filename,$mincount,$delim="\t",$concat=1)
 *
 *              SECTION: General Functions
 * 5789:     function getHiddenText($PCA,$row,$mode='')
 *
 * TOTAL FUNCTIONS: 77
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

define ('TAB', "\t");
define ('CRLF', "\r\n");
define ('DQT', '"');
define ('QT', "'");
define ('BR', '<br />');


define ('SGZ_TEXT', 0);
define ('SGZ_FORM', 1);
define ('SGZ_AUTO', 2);
define ('SGZ_AUTOHIDDEN', 3);
define ('SGZ_CMD', 4);
define ('SGZ_LIST', 5);
define ('SGZ_LISTEDIT', 6);
define ('SGZ_SEARCH', 7);
define ('SGZ_SEARCHALL', 7);
define ('SGZ_SEARCHUSED', 8);

define ('SGZ_VERSIONFORADDJS','9.9');

class tx_sgzlib {
	var $prefixId='tx_sgzlib';
	var $scriptRelPath='class.tx_sgzlib.php';
	var $extKey='sg_zlib';

	var $debugField = '-';
	var $debugMMField = '-';

	var $conf = Array();
	var $xajax;
	var $xajaxFieldWrap = Array();
	var $xajaxPrefix = '';
	var $dodebug = Array('cache'=>FALSE);

	var $foreign = Array();

	var $templates = Array();
	var $globalReplace = Array();
	var $lastCheckError = FALSE;
	var $formDataName = '[data]';

	var $lastAbcKey = '';
	var $lastAbcRange = '';
	var $lastAbcRangeText = '';

	var $sMode = '';

	var $const = Array();
	var $sendMailMsg = '';

	var $lastResultList = Array();
	var $confMaxPP = 0;
	var $returnFromDetails = '';
	var $debugCnt = 0;

	var $factoryObj;
	var $confObj;
	var $debugObj;
	var $constObj;
	var $templateObj;
	var $permitObj;
	var $langObj;
	var $itemsObj;
	var $divObj;
	var $dateObj;

	var $designator;

	/**
	 * ****************************************************************************
	 *
	 * CLASS instantiate and init/config
	 *
	 * ******************************************************************************/
	 /**
 * @param	[type]		$name: ...
 * @return	[type]		...
 */
	function _fCount ($name=NULL) {
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
	 * @param	[type]		$tx_sglib_config $confObj,tx_sglib_debug $debugObj: ...
	 * @return	[type]		...
	 */
	function init($designator, tx_sglib_factory $factoryObj)	{
		$this->designator = $designator;
		$this->_fCount(__FUNCTION__);
		$this->dateObj = tx_sgdate::getInstance();
		$this->factoryObj = $factoryObj;
		$this->confObj = $factoryObj->confObj;
		$this->conf = $this->confObj->getConfData();
		$this->debugObj = $factoryObj->debugObj;
		$this->divObj = $factoryObj->divObj;

		if (strlen($this->confObj['debugField'])>1) {
			$this->debugField = $this->confObj['debugField'];
		}
		if (strlen($this->confObj['debugMMField'])>1) {
			$this->debugMMField = $this->confObj['debugMMField'];
		}


		$this->myQuery = t3lib_div::getIndpEnv('TYPO3_REQUEST_URL');

		$this->mSqlConf = $this->conf['mysql.'];
		$this->mSqlConf['addToOrder'] = ' '.$this->mSqlConf['addToOrder'].' ';

		$this->editListWrap = explode('|',$this->conf['form.']['editList.']['wrap'],2);
		$this->editListElementWrap = explode('|',$this->conf['form.']['editList.']['elementWrap'],2);
		$this->editListButtonSeparator = $this->conf['form.']['editList.']['buttonSeparator'];

		$this->emptyUrl = $this->conf['emptyUrl'];

		$this->lCObj = t3lib_div::makeInstance('tslib_cObj');

		if (substr($this->conf['browser'],0,5)=='index') {
			$this->browser = $this->conf['browser'];
		} else {
			$this->browser = t3lib_div::getIndpEnv('TYPO3_SITE_URL').$GLOBALS['TSFE']->tmpl->getFileName($this->conf['browser']).
				($this->conf['browserParams'] ? $this->conf['browserParams'] : '?');
		}

		if (substr($this->conf['exporter'],0,5)=='index') {
			$this->exporter = $this->conf['exporter'];
		} else {
			$this->exporter = $GLOBALS['TSFE']->tmpl->getFileName($this->conf['exporter']);
		}

		if (substr($this->conf['deleter'],0,5)=='index') {
			$this->deleter = $this->conf['deleter'];
		} else {
			$this->deleter = $GLOBALS['TSFE']->tmpl->getFileName($this->conf['deleter']);
		}

		$this->typolink_conf = $this->conf['typolink.'];
		$this->typolink_conf['additionalParams'] =
				$this->lCObj->stdWrap($this->typolink_conf['additionalParams'],$this->typolink_conf['additionalParams.']);
		unset ($this->typolink_conf['additionalParams.']);

		$this->globalMarkers = Array();

		$this->constObj = $factoryObj->constObj;
		$this->globalReplace = $this->constObj->getConst();

		$this->templateObj = $factoryObj->templateObj;

 		$this->itemsObj = $factoryObj->itemsObj;

		$this->permitObj = $factoryObj->permitObj;
		$this->feUser = $this->permitObj->getFeUser();
		$this->beUser = $this->permitObj->getBeUser();
		$this->feGroups = $this->permitObj->getFeGroups();
		$this->beGroups = $this->permitObj->getBeGroups();

		$this->langObj = $factoryObj->langObj;
		$this->langObj->setLocalLangFile('EXT:sg_zlib/locallang.php');
		$this->globalMarkers['###BACK_URL_TEXT###'] = $this->langObj->getLL('BackToResultPage','Back to List');
	}



	/*******************************************************************************
	 *
	 * Some helper functions (should become obsolete...)
	 *
	 *******************************************************************************/

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$gp: ...
	 * @param	[type]		$default: ...
	 * @return	[type]		...
	 */
	function GPdefault($gp,$default='') {
		$pv = $GLOBALS['HTTP_POST_VARS'];
		if (!is_array($pv)) {
			$pv = $GLOBALS['_POST'];
		}
		$gv = $GLOBALS['HTTP_GET_VARS'];
		if (!is_array($gv)) {
			$gv = $GLOBALS['_GET'];
		}
		$value = isset($pv[$gp]) ? $pv[$gp] :
			( isset($gv[$gp]) ? $gv[$gp] :
				 $default  );
		return $value;
	}






	/********************************************************************************
	 *
	 * Typolinks
	 *
	 ********************************************************************************/

	/**
	 * @param	[type]		$params: ...
	 * @return	[type]		...
	 */
	function getTypoLinkItem ($params) {
			$title = (isset($params['item']) ?
				$this->lCObj->cObjGetSingle($params['item'],$params['item.']) : $this->langObj->getLLL($params['title']) );
			if (!$title) {
				$title = '[felib.getTypoLinkItem]';
			}
			if ($params['alert']) {
				$params['alert'] = str_replace('"','',$params['alert']);
				$params['alert'] = str_replace("'",'',$params['alert']);
				$params['ATagParams'] .= ' onclick="javascript:return(sgPromt('.QT.$this->langObj->getLLL($params['alert']).QT.'))"';
			}
			$url = $this->lCObj->typoLink($title, $params);
			return ( $url );
	}

	/**
	 * @param	[type]		$myPageID: ...
	 * @param	[type]		$myParams: ...
	 * @param	[type]		$allowCaching: ...
	 * @param	[type]		$myTarget: ...
	 * @param	[type]		$myDbg: ...
	 * @return	[type]		...
	 */
	function getTypolinkURL ($myPageID,$myParams='',$allowCaching=0,$myTarget='',$myDbg='') {
			if (!$myTarget) {
				$myTarget='_self';
			}
			$this->lCObj->setCurrentVal(($myPageID ? $myPageID : $GLOBALS['TSFE']->id));
			$this->typolink_conf['parameter.']['current'] = 1;
			$this->typolink_conf['useCacheHash'] = $allowCaching;
			$this->typolink_conf['no_cache'] = 0; //!$allowCaching;
			$this->typolink_conf['extTarget'] = $myTarget;
			$this->typolink_conf['target'] = $myTarget;

			$temp_conf = $this->typolink_conf;
			$temp_conf['additionalParams'] .= $myParams;

			$url = $this->lCObj->TypoLink_URL($temp_conf);
			if ($myDbg) t3lib_div::debug(Array('getTypolinkURL'=>$url, '$myPageID'=>$myPageID, '$myDbg'=>$myDbg));
			return ( $url );
	}


	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$textLink: ...
	 * @param	[type]		$myPageID: ...
	 * @param	[type]		$myParams: ...
	 * @param	[type]		$allowCaching: ...
	 * @param	[type]		$myTarget: ...
	 * @param	[type]		$myDbg: ...
	 * @return	[type]		...
	 */
	function getTypolink ($textLink,$myPageID,$myParams='',$allowCaching=0,$myTarget='',$myDbg='') {
			if (!$myTarget) {
				$myTarget='_self';
			}
			$this->lCObj->setCurrentVal(($myPageID ? $myPageID : $GLOBALS['TSFE']->id));
			$this->typolink_conf['parameter.']['current'] = 1;
			$this->typolink_conf['useCacheHash'] = $allowCaching;
			$this->typolink_conf['no_cache'] = 0; //!$allowCaching;
			$this->typolink_conf['extTarget'] = $myTarget;
			$this->typolink_conf['target'] = $myTarget;

			$temp_conf = $this->typolink_conf;
			$temp_conf['additionalParams'] .= $myParams;

			$url =  $this->lCObj->TypoLink($textLink,$temp_conf) ;
			if ($myDbg) t3lib_div::debug(Array('getTypolink'=>$url, '$myDbg'=>$myDbg));
			return ( $url );
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$PCA: ...
	 * @return	[type]		...
	 */
	function clearCache($PCA) {
		$tmp = Array();
		if ($PCA['cacheTables']) {
			$tmp = t3lib_div::trimExplode (',',$PCA['cacheTables']);
			for ($i=0;$i<count($tmp);$i++) {
				$tmp[$i] = QT.$tmp[$i].QT;
			}
			$mySearch =	"list_type in (".implode(',',$tmp).")";
		} else {
			$mySearch =	"list_type in ('unknown')";
		}

		$pids = Array();
		$query = 'deleted=0 AND '.$mySearch;
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('pid ','tt_content',$query,'','pid');
		if ($res) {
			//t3lib_div::debug(Array("query="=>$query,"res="=>$res,"count="=>$GLOBALS['TYPO3_DB']->sql_num_rows($res)));
			while ($myRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$pids[$myRow['pid']] = $myRow['pid'];
			}
		} else {
			t3lib_div::debug(Array("query="=>$query,"res="=>$res,"error="=>$GLOBALS['TYPO3_DB']->sql_error()));
		}
		$pidList = implode(',',$pids);

		if (count($pids)) {
			//t3lib_div::debug(Array('ClearCacheFor'=>$pidList, 'File:Line'=>__FILE__.':'.__LINE__));
			$this->writelog('SGZLIB: clearCache for ('.implode(',',$tmp).'): '.$pidList,2);
			$GLOBALS['TSFE']->clearPageCacheContent_pidList($pidList);
		}

	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$url: ...
	 * @param	[type]		$paramsReplace: ...
	 * @return	[type]		...
	 */
	function myParseUrl ($url,$paramsReplace=Array()) {
		if (strcmp($url,'-')==0) {
			$url = t3lib_div::getIndpEnv('TYPO3_REQUEST_URL');
		}
		$u = @parse_url($url);

		$u['orgQuery'] = $u['query'];
		if ($u['orgQuery'] || count($paramsReplace)>0) {
			$u['plist'] = Array();
			$tmp = t3lib_div::trimExplode ('&',urldecode($u['orgQuery']),1);
			for ($i=0;$i<count($tmp);$i++) {
				list($k,$v) = explode('=',$tmp[$i]);
				$u['plist'][$k] = $v;
			}
			$u['plist'] = t3lib_div::array_merge($u['plist'],$paramsReplace);
			$u['query'] = t3lib_div::implodeArrayForUrl('',$u['plist'],'',1,1) ;
		}

		if (is_array($u)) {
			$u['mailmode'] = 0;
			if (is_array($u) && strlen($u['path'])>2 && strlen($u['host'])<3) {
				$x = explode ('/',$u['path'],2);
				if (strlen($x[1])>2 && substr_count ($x[0],'.')>0) {
					$u['host'] = $x[0];	$u['path'] = $x[1];
				} else if (strcmp(substr($x[0],0,4),'www.')==0) {
					$u['host'] = $x[0];	$u['path'] = $x[1];
				} else if (substr_count($x[0],'.')>1 && substr_count ($x[0],'.htm')<1 && substr_count($x[0],'.php')<1 && substr_count($x[0],'.phtm')<1)  {
					$u['host'] = $x[0];	$u['path'] = $x[1];
				}
			}
			if (strlen($u['scheme'])<3) {
				if (substr_count($url,'@')!=1) {
					$u['scheme']='http';
				} else {
					if (substr_count($url,'/')>0) {
						$u['scheme']='http';
					} else {
						$u['scheme']='mailto';
						$u['mailmode'] = 1;
					}
				}
			}

			if (strcmp($u['scheme'],'mailto')==0) {
				$u['mailmode'] = 1;
				$u['total'] = $u['scheme'].':'.$u['host'].$u['path'];
				$u['fullpath'] = $u['scheme'].':'.$u['host'].$u['path'];
			} else if (strlen($u['host'])<3 && strlen($u['path'])<1) {
				$u['total'] = '';
				$u['fullpath'] = '';
			} else {
				 if (strlen($u['host'])<3) { $u['host']=t3lib_div::getIndpEnv('HTTP_HOST'); }
				 $x = (strlen($u['path'])>0 ? $u['path'] : '').(strlen($u['query'])>0 ? '?'.$u['query'] : '') ;
				 $u['total'] = $u['scheme'].'://'.$u['host'].(strlen($u['port'])>0 ? ':'.$u['port'] :'').(substr($x,0,1)=='/'?'':'/').$x;
				 $u['fullpath'] = $u['scheme'].'://'.$u['host'].(strlen($u['port'])>0 ? ':'.$u['port'] :'').(substr($x,0,1)=='/'?'':'/').$u['path'];
			}
		} else {
			$u = Array('total'=>$url, 'fullpath'=>$url);
		}

		//t3lib_div::debug(Array('$u'=>$u, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($u);
	}


	/******************************************************************************
	 *
	 * Some usefull Wraps
	 *
	 ******************************************************************************/


	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$text: ...
	 * @param	[type]		$komma: ...
	 * @return	[type]		...
	 */
	function quote ($text,$komma='') {
		return ("'".$text."'".($komma==''?'':','));
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$text: ...
	 * @param	[type]		$komma: ...
	 * @return	[type]		...
	 */
	function dquote ($text,$komma='') {
		return ('"'.$text.'"'.($komma==''?'':','));
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$text: ...
	 * @param	[type]		$markers: ...
	 * @param	[type]		$more: ...
	 * @param	[type]		$wrap: ...
	 * @return	[type]		...
	 */
	function replaceArray ($content,$markers,$more=FALSE,$wrap='') {
		if ($content) {
			if (is_array($markers)) {
				reset ($markers);
				$wrapArr=t3lib_div::trimExplode('|',$wrap);
				while(list($mname,$mcontent)=each($markers)) {
					if(strcmp($wrap,'')) {
						$mname=$wrapArr[0].$mname.$wrapArr[1];
					}
					$content = str_replace($mname,$mcontent,$content);
				}
			}
			if ($more) {
				reset ($this->globalReplace);
				while(list($mname,$mcontent)=each($this->globalReplace)) {
					$content = str_replace($mname,$mcontent,$content);
				}
			}
		}
		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$content: ...
	 * @param	[type]		$markers: ...
	 * @param	[type]		$more: ...
	 * @param	[type]		$wrap: ...
	 * @return	[type]		...
	 */
	function replaceArrayArray ($content,$markers,$more=FALSE,$wrap='') {
		if (is_array($content)) {
			reset ($content);
				while(list($cname,$ccontent)=each($content)) {
					$content[$cname] = $this->replaceArray ($ccontent,$markers,$more,$wrap);
				}
			return ($content);
		} else {
			return ($this->replaceArray ($content,$markers,$more,$wrap));
		}
	}


	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$mailto: ...
	 * @param	[type]		$subject: ...
	 * @param	[type]		$mailbody: ...
	 * @param	[type]		$hd: ...
	 * @param	[type]		$params: ...
	 * @return	[type]		...
	 */
	function sendMail ($mailto,$subject,$mailbody,$hd,$params='') {
		$this->sendMailMsg = '';
		$mailbody = $this->lCObj->substituteMarkerArray($mailbody, $GLOBALS['HTTP_SERVER_VARS'], '###SRV_|###');
		$subject = $this->lCObj->substituteMarkerArray($subject, $GLOBALS['HTTP_SERVER_VARS'], '###SRV_|###');
		$ok = mail ($mailto,$subject,$mailbody,$hd); //,$params);
		if ($ok) {
			if ($this->conf['log.']['sendMailOk']) {
				$this->writelog('Mail was sent to "'.$mailto.'"',2,Array(),10,1,0);
			}
			if ($this->conf['log.']['sendMailOkAlert']) {
				$this->sendMailMsg = $this->constObj->getConst('sendMailOk');
			}
		} else {
			if ($this->conf['log.']['sendMailError']) {
				$this->writelog('Mail was NOT sent to "'.$mailto.'"',4,Array(),10,1,1);
			}
			if ($this->conf['log.']['sendMailErrorAlert']) {
				$this->sendMailMsg = $this->constObj->getConst('sendMailError');
			}
		}
		return ($ok);
	}


	/******************************************************************************
	 *
	 * JS functions
	 *
	 *****************************************************************************/

	/**
	 * @param	[type]		$text: ...
	 * @return	[type]		...
	 */
	function jsInsert ($text) {
		return (
			"\r\n".'<script type="text/javascript">'."\r\n\t".
			'/*<![CDATA[*/'."\r\n".
			'<!--'."\r\n".
			$text."\r\n".
			'//-->'."\r\n\t".
			'/*]]>*/'."\r\n".
			'</script>'."\r\n"
			);
	}




	/************************************
	 *
	 * Logging
	 *
	 ************************************/


	/**
	 * Writes an entry in the logfile
	 *
	 * @param	string		$details: Default text that follows the message
	 * @param	integer		$action: denotes which specific operation that wrote the entry: 1=warning 2=message
	 * @param	array		$data: Data that follows the log. Might be used to carry special information.
	 * @param	integer		$type: denotes which module that has submitted the entry. 10 = Dev
	 * @param	integer		$details_nr: The message number. Specific for each $type and $action.
	 * @param	integer		$error: flag. 0 = message, 1 = error (user problem),
	 * @param	integer		$event_pid: The page_uid (pid) where the event occurred. Used to select log-content for specific pages.
	 * @return	void
	 */
	function writelog($details,$action=1,$data=Array(),$type=10,$details_nr=1,$error=0,$event_pid=1 ) {
		/*
		* add to typo3/ext/belog/mod/index.php:
		*		"action" => array(  ...  10 => "Dev", ... )
		* add to typo3/ext/belog/mod/locallang.php
		*		'type_10' => 'DEV',
		*		'action_10_1' => 'Warn',
		*		'action_10_2' => 'Msg',
		*/
		$fields_values = Array (
			'userid' => (is_array($this->feUser)) ? intval($this->feUser['uid']) : 0,
			'type' => intval($type) ,
			'action' => intval($action),
			'error' => intval($error),
			'details_nr' => intval($details_nr),
			'details' => $details,
			'log_data' => serialize($data),
			'tablename' => '',
			'recuid' => 0,
			'recpid' => 0,
			'IP' => t3lib_div::getIndpEnv('REMOTE_ADDR'),
			'tstamp' => $GLOBALS['EXEC_TIME'],
			'event_pid' => intval($event_pid),
			'NEWid' => ''
		);

		$GLOBALS['TYPO3_DB']->exec_INSERTquery('sys_log', $fields_values);
		$retcode = $GLOBALS['TYPO3_DB']->sql_insert_id();

		if ($error>0) {
			$out = fopen('typo3temp/errorlog.txt','a');
			if ($out) {
				fwrite($out,date('Ymd-His').' / '.$error.' - '.$details.CRLF);
				fclose($out);
			} else {
				$fields_values['error'] = 2;
				$fields_values['details'] = 'Fopen failed !!!!!';
				$GLOBALS['TYPO3_DB']->exec_INSERTquery('sys_log', $fields_values);
			}
		}

		return $retcode;
	}

	/******************************************************************************
	 *
	 * Partial implementation of getFeSingleField - functionally like in be
	 *
	 ******************************************************************************/

	/**
	 * @param	[type]		$dbTable: ...
	 * @param	[type]		$pluginName: ...
	 * @param	[type]		$myPid: ...
	 * @param	[type]		$pluginConf: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$dbg: ...
	 * @return	[type]		...
	 */
	function getPCA ($dbTable,$pluginName='myData',$myPid=0,$pluginConf=Array(),$piVars=Array(),$PCA=Array(),$dbg=0) {
		global $TCA,$TSFE;
		$this->dbg = intval($dbg);
		$this->lastPrefixId = $pluginName;

		$PCA['name'] = $pluginName;
		$PCA['table'] = $dbTable;

		if (is_array($myPid)) {
			$PCA['pid_list'] = implode(',',$myPid);
			$PCA['pid'] = intval(reset($myPid));
		} else {
			$myPidList = explode(',',$myPid);
			$PCA['pid'] = $myPidList[0];
			$PCA['pid_list'] = $myPid;
		}

		$PCA['cache'] = (isset($pluginConf['allowCaching'])) ? intval($pluginConf['allowCaching']) : 1;
		if (!$PCA['cache']) {
			$TSFE->set_no_cache();
		}
		$PCA['cacheTables'] = ($pluginConf['cacheTables']) ?
				($pluginConf['cacheTables']) : str_replace('tx_sg','sg_',$pluginName);

		$PCA['dodebug'] = $this->mergeTsArray(array(),$pluginConf['dodebug.']);
		$PCA['error'] = $this->mergeTsArray(array(),$pluginConf['error.']);
		$PCA['class'] = $this->mergeTsArray(array(),$pluginConf['class.']);

		//$PCA['xajax'] = $this->mergeTsArray(array(),$pluginConf['xajax.']);
		//$PCA['view'] = $this->mergeTsArray(array(),$pluginConf['view.']);
		//$PCA['list'] = $this->mergeTsArray(array(),$pluginConf['list.']);
		//$PCA['latest'] = $this->mergeTsArray(array(),$pluginConf['latest.']);
		//$PCA['cat'] = $this->mergeTsArray(array(),$pluginConf['cat.']);

//		$PCA['const'] = Array();
//		if (!$this->indpEnv) {
//			$this->indpEnv = t3lib_div::getIndpEnv('_ARRAY');
//			for (reset($this->indpEnv);$key=key($this->indpEnv);next($this->indpEnv)) {
//				$PCA['const'][$key] = $this->indpEnv[$key];
//			}
//		}
//		$PCA['const']['PAGE_URL'] = $this->getTypolinkURL(0);
//		$PCA['const']['PAGE_URL'] .= (strpos($PCA['const']['PAGE_URL'], '?')>0) ? '' : '?';
//		if (is_array($pluginConf['const.'])) {
//			for (reset($pluginConf['const.']);$key=key($pluginConf['const.']);next($pluginConf['const.'])) {
//				if (substr($key,-1)!='.') {
//					if (isset($pluginConf['const.'][$key.'.'])) {
//						$PCA['const'][$key] =
//							$this->lCObj->cObjGetSingle($pluginConf['const.'][$key],$pluginConf['const.'][$key.'.']);
//					} else {
//						$PCA['const'][$key] = $this->langObj->getLLL($pluginConf['const.'][$key]);
//					}
//				}
//			}
//		}
//

		$PCA['searchclass'] = $this->mergeTsArray(Array(),$pluginConf['searchclass.']);
		$PCA['formclass'] = $this->mergeTsArray(Array(),$pluginConf['formclass.']);
		$PCA['textclass'] = $this->mergeTsArray(Array(),$pluginConf['textclass.']);

		$PCA['format'] = $this->mergeTsArray(Array(),$pluginConf['format.']);
		$PCA['stdwrap'] = $this->mergeTsArray(Array(),$pluginConf['stdWrap.'],1);
		$PCA['mail'] = $this->mergeTsArray(Array(),$pluginConf[$dbTable.'.']['mail.']);
		$PCA['ctrl'] = $this->mergeTsArray(Array(),$pluginConf[$dbTable.'.']['ctrl.']);
		$PCA['listmode'] = $this->mergeTsArray(Array(),$pluginConf[$dbTable.'.']['listmode.']);
		$PCA['search'] = $this->mergeTsArray(Array(),$pluginConf[$dbTable.'.']['search.']);
		$PCA['export'] = $this->mergeTsArray(Array(),$pluginConf[$dbTable.'.']['export.']);
		$PCA['deleteList'] =$this->mergeTsArray(Array(),$pluginConf[$dbTable.'.']['deleteList.']);
		$PCA['undeleteList'] = $this->mergeTsArray(Array(),$pluginConf[$dbTable.'.']['undeleteList.']);
		$PCA['quota'] = $this->mergeTsArray(Array(),$pluginConf['quota.']);
		$PCA['autoInsert'] = $this->mergeTsArray(Array(),$pluginConf['autoInsert.'],1);
		$PCA['autoInsert']['params'] = $PCA['autoInsert']['params.']; unset ($PCA['autoInsert']['params.']);
		$PCA['autoInsert']['globalText'] = $PCA['autoInsert']['globalText.']; unset ($PCA['autoInsert']['globalText.']);
		$PCA['autoInsert']['localText'] = $PCA['autoInsert']['localText.']; unset ($PCA['autoInsert']['localText.']);
		$PCA['autoInsert']['tables'] = Array(); unset ($PCA['autoInsert']['tables.']);
		$PCA['autoInsert']['tables'] = $this->mergeTsArray($PCA['autoInsert']['tables'],$this->conf['autoInsert.']['tables.']);
		$PCA['autoInsert']['tables'] = $this->mergeTsArray($PCA['autoInsert']['tables'],$pluginConf['autoInsert.']['tables.']);

		$PCA['image'] = Array();
		if (is_array($pluginConf['image.'])) for (reset($pluginConf['image.']);$sKey=key($pluginConf['image.']);next($pluginConf['image.'])) {
			$xsKey = str_replace ('.','',$sKey);
			//$PCA['image'][$xsKey] = $this->mergeTsArray($PCA['image'][$xsKey],$pluginConf['image.'][$sKey]);
			for (reset($pluginConf['image.'][$sKey]);$cKey=key($pluginConf['image.'][$sKey]);next($pluginConf['image.'][$sKey])) {
				$xcKey = str_replace ('.','',$cKey);
				if ($xcKey=='conf') {
					$PCA['image'][$xsKey][$xcKey] = $this->mergeTsArray($PCA['image'][$xsKey][$xcKey],$pluginConf['image.'][$sKey][$cKey],1);
				} else {
					$PCA['image'][$xsKey][$xcKey] = $this->mergeTsArray($PCA['image'][$xsKey][$xcKey],$pluginConf['image.'][$sKey][$cKey]);
				}
			}
		}

		$this->todo = Array();
		$this->todo['New'] = intval($this->GPdefault('dN',$piVars['todo']['dN']));
		$this->todo['NewFromUid'] = intval($this->GPdefault('nUid',$piVars['todo']['nUid']));
		$this->todo['Edit'] = intval($this->GPdefault('dE',$piVars['todo']['dE']));
		$this->todo['Save'] = intval($this->GPdefault('dS',$piVars['todo']['dS']));
		$this->todo['Reload'] = intval($this->GPdefault('dR',$piVars['todo']['sR']));
		$this->todo['Hide'] = intval($this->GPdefault('doHide',$piVars['todo']['doHide']));
		$this->todo['UnHide'] = intval($this->GPdefault('doUnHide',$piVars['todo']['doUnHide']));
		$this->todo['Delete'] = intval($this->GPdefault('doDelete',$piVars['todo']['doDelete']));
		$this->todo['Uid'] = intval($this->GPdefault('uid',$piVars['todo']['uid']));
		$this->todo['BackUrl'] = $TSFE->fe_user->getKey('ses',$pluginName.'.lastQuery');
		$this->todo['LastIdList'] = $TSFE->fe_user->getKey('ses',$pluginName.'.lastIdList');
		$this->todo['PrintMode'] = intval($this->GPdefault('pM',$piVars['todo']['printMode']));
		$this->todo['RTL'] = intval($this->GPdefault('rTL',$piVars['todo']['rTL']));
		$PCA['todo'] = $this->todo;

		$PCA['conf'] = $this->mergeTsArray(Array(),$pluginConf[$dbTable.'.']['conf.'],2);
		if ($pluginConf['presetMaxlen']) {
			for (reset($PCA['conf']);$key=key($PCA['conf']);next($PCA['conf'])) if (!$PCA['conf'][$key]['maxlen']) {
				if ($PCA['conf'][$key]['type']=='input' && $PCA['conf'][$key]['config']['max']) {
					$PCA['conf'][$key]['maxlen'] = $PCA['conf'][$key]['config']['max'];
				}
			}
		}

		$PCA['conf'] = $this->mergeTsArray($PCA['conf'],$pluginConf[$dbTable.'.']['moreConf.'],2);

		// get 'pluginName'-piVars
		$PCA['piVars'] = t3lib_div::GParrayMerged($pluginName);

		if ($this->dodebug['pca']) {
			t3lib_div::debug(Array('$PCA'=>$PCA, 'File:Line'=>__FILE__.':'.__LINE__));
		}

		if (!$PCA['ctrl']['enablecolumns']['disabled']) {
			if ($this->dbg) t3lib_div::debug(Array('WARNING'=>'ctrl/enablecolumns/disabled not set in table "'.$dbTable.'"'));
		}
		if (!$PCA['ctrl']['delete']) {
			if ($this->dbg) t3lib_div::debug(Array('WARNING'=>'ctrl/delete not set in table "'.$dbTable.'"'));
		}

		if (intval($PCA['mail']['new']['mode'])<1 && intval($PCA['mail']['change']['mode'])<1
			&& intval($PCA['mail']['delete']['mode'])<1 && intval($PCA['mail']['visibility']['mode'])<1) {
			unset ($PCA['mail']);
		}

		$PCA['ctrl']['calc'] = Array();
		for (reset($PCA['conf']);$key=key($PCA['conf']);next($PCA['conf'])) {
			if (strcmp($PCA['conf'][$key]['mode'],'calc')==0) {
				$PCA['ctrl']['calc'][$key] = $PCA['conf'][$key]['calc'].' AS '.$key;
			}
		}

		// check for mm-tables
		$PCA['mm'] = Array();
		$PCA['mmAll'] = Array('select'=> '', 'join'=> '');
		for (reset($PCA['conf']);$key=key($PCA['conf']);next($PCA['conf'])) if ($PCA['conf'][$key]['MM'] && !$PCA['conf'][$key]['dontsave']) {
			$tmp = $PCA['conf'][$key]['MM'];
			$PCA['mm'][$key] = Array('select'=> ', '.$tmp.'.uid_foreign AS '.$key.'_mm',
				'join'=> ' LEFT JOIN '.$tmp.' ON '.$dbTable.'.uid='.$tmp.'.uid_local');
			if ($PCA['search'][$key]) {
				$PCA['mmAll']['select'] .= $PCA['mm'][$key]['select'];
				$PCA['mmAll']['join'] .= $PCA['mm'][$key]['join'];
			}
		}

		$PCA['ctrl']['disabled'] = $PCA['ctrl']['enablecolumns']['disabled'];
		$PCA['ctrl']['starttime'] = $PCA['ctrl']['enablecolumns']['starttime'];
		$PCA['ctrl']['endtime'] = $PCA['ctrl']['enablecolumns']['endtime'];
		$PCA['ctrl']['fe_group'] = $PCA['ctrl']['enablecolumns']['fe_group'];

		$this->localPCA = $PCA;
		$this->dodebug = ($this->dbg<0) ? Array() : $PCA['dodebug'];
		$this->dbg = ($this->dbg<0) ? 0 : $this->dbg;

		$this->debugObj->debugIf('pid',Array('$myPid'=>$myPid, '$PCA[pid]'=>$PCA['pid'], '$PCA[pid_list]'=>$PCA['pid_list'],
						'File:Line'=>__FILE__.':'.__LINE__));
		$this->debugObj->debugIf('PCA',Array('$PCA'=>$PCA, 'File:Line'=>__FILE__.':'.__LINE__));
		$this->debugObj->debugIf('MM',Array('$PCA[mm]'=>$PCA['mm'], '$PCA[mmAll]'=>$PCA['mmAll'], 'File:Line'=>__FILE__.':'.__LINE__));

		return ($PCA);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$prefixId: ...
	 * @return	[type]		...
	 */
	function prepareXajax ($prefixId) {
		$this->xajaxPrefix = $prefixId;
		require_once(t3lib_extMgm::extPath("xajax").'class.tx_xajax.php');
		$this->xajax = t3lib_div::makeInstance("tx_xajax");
		$this->xajax->decodeUTF8InputOn();
		$encoding = $this->confObj['encoding'];
		$this->xajax->setCharEncoding($encoding ? $encoding : 'utf-8');
		$this->xajax->setWrapperPrefix($this->xajaxPrefix);
		$this->xajax->statusMessagesOn();
		if (intval($this->dodebug['xajax'])>1) {
			$this->xajax->debugOn();
		} else {
			$this->xajax->debugOff();
		}
		if ($this->PCA['xajax']['fieldWrap']) {
			$this->xajaxFieldWrap = t3lib_div::trimExplode('|',$this->PCA['xajax']['fieldWrap'],2);
		} else {
			$this->xajaxFieldWrap = Array('<span id="###id###">','</span>');
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$text: ...
	 * @param	[type]		$id: ...
	 * @param	[type]		$em: ...
	 * @return	[type]		...
	 */
	function doXajaxFieldWrap($text,$id="test",$em=0) {
		return (str_replace('###id###',$id,$this->xajaxFieldWrap[0]).$text.$this->xajaxFieldWrap[1]);
	}
	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$dest: ...
	 * @param	[type]		$source: ...
	 * @param	[type]		$mode: ...
	 * @return	[type]		...
	 */
	function mergeTsArray ($dest,$source,$mode=0) {

		if (is_array($source)) {
			for (reset($source);$key=key($source);next($source)) {
				if (is_array($source[$key])) {
					$theKey = (substr(trim($key),-1)=='.' && $mode!=1) ? substr($key,0,-1) : $key ;
					if ($source[$key]['stdWrap'] || $source[$key]['stdWrap.']) {
						$dest[$theKey] = $this->lCObj->stdWrap($source[$key]['stdWrap'],$source[$key]['stdWrap.']);
					} else {
						if ($mode==2 && $theKey=='typolink') {
							$mode = 1;
						}
						$dest[$theKey] = $this->mergeTsArray((is_array($dest[$theKey])?$dest[$theKey]:Array()),$source[$key],$mode);
					}
				} else {
					$dest[$key] = $source[$key];
				}
			}
		} else if (isset($source)) {
			$dest = $source;
		}
		return ($dest);
	}




	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$em: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$opt: ...
	 * @return	[type]		...
	 */
	function getFeSingleField($table,$field,$row,$em=0,$PCA=Array(),$opt=Array())	{
		$code = $this->getFeSingleField_SW($table,$field,$row,$em,$PCA,$opt);
		return ($code);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$value: ...
	 * @param	[type]		$wrap: ...
	 * @return	[type]		...
	 */
	function getPAfieldParam ($PCA,$field,$value,$wrap=Array()) {
		if (!is_array($wrap)) { $x = $wrap; $wrap = Array(); $wrap[0] = $x; }
		if (isset($PCA['conf'][$field][$value])) {
			$out = $wrap[0].$PCA['conf'][$field][$value].$wrap[1];
		} else {
			$out = '';
		}
		return ($out);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$em: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$opt: ...
	 * @return	[type]		...
	 */
	function getFeSingleField_SW($table,$field,$row,$em,&$PCA,$opt=Array())	{
		$myType = strtolower($PCA['conf'][$field]['type']);
		if ($em>=SGZ_SEARCH && isset($PCA['search'][$field]['formtype'])) {
			$myType = strtolower($PCA['search'][$field]['formtype']);
		}
		switch($myType)	{
			case '':
			case 'time':
			case 'date':
			case 'datetime':
			case 'input':
			case 'none':
			case 'password':
				$item = $this->getSingleField_typeInput($table,$field,$row,$em,$PCA,$myType,$opt);
			break;
			case 'text':
			case 'textnowrap':
				$item = $this->getSingleField_typeText($table,$field,$row,$em,$PCA,$myType,$opt);
			break;
			case 'linklist':
				if ($em>=SGZ_SEARCH) {
					$item = $this->getSingleField_typeText($table,$field,$row,$em,$PCA,$myType,$opt);
			} else if ($em==0) {
					$item = $this->getSingleField_typeText($table,$field,$row,$em,$PCA,$myType,$opt);
				} else {
					$item = $this->getSingleField_typeText($table,$field,$row,$em,$PCA,$myType,$opt);
				}
			break;
			case 'select':
			case 'radio':
			case 'checklist':
			case 'selectlist':
			case 'selectbits':
			case 'selectsingle':
			case 'selectmulti':
				$item = $this->getSingleField_typeSelect($table,$field,$row,$em,$PCA,$myType,$opt);
			break;
			case 'check':
				if ($em>=SGZ_SEARCH) {
					$item = $this->getSingleField_typeSelect($table,$field,$row,$em,$PCA,$myType,$opt);
				} else {
					$item = $this->getSingleField_typeCheck($table,$field,$row,$em,$PCA,$myType,$opt);
				}
			break;
			case 'imageres':
				if ($em>=SGZ_SEARCH || $em==0) {
					$item = $this->getSingleField_typeText($table,$field,$row,$em,$PCA,$myType,$opt);
				} else {
					$item = $this->getSingleField_typeList($table,$field,$row,$em,$PCA,$myType,$opt);
				}
			break;
			case 'image':
			case 'imagelist':
			case 'filelist':
			case 'doclist':
			case 'pdflist':
				$item = $this->getSingleField_typeList($table,$field,$row,$em,$PCA,$myType,$opt);
			break;
			case 'user':
				$PA['fieldConf']['config'] = $PCA['conf'][$field];
				$PA['fieldConf']['config']['form_type'] = $PA['fieldConf']['config']['form_type'] ? $PA['fieldConf']['config']['form_type'] : $PA['fieldConf']['config']['type'];	// Using "form_type" locally in this script
				$PA['table']=$table;
				$PA['field']=$field;
				$PA['row']=$row;
				$PA['pObj']=&$this;
				$item = '--user('.$PCA['conf'][$field]['userFunc'].')--';
				//$item = t3lib_div::callUserFunction($PCA['conf'][$field]['userFunc'],$PA,$this);
				//t3lib_div::debug(Array('$PA'=>$PA, 'File:Line'=>__FILE__.':'.__LINE__));
				//t3lib_div::debug(Array('pca'=>$PCA['conf'][$field]['userFunc'], 'File:Line'=>__FILE__.':'.__LINE__));
			break;
			case 'none':
				$item = '';
			break;
			case 'group':
				$item = '[#ERROR#--getSingleField_SW('.$table.'.'.$field.', Type="'.$myType.'")=UNKNOWN--#]';
				$item .= '[Change to "selectmulti" or "imagelist" !!]';
			break;
			default:
				$item = '[#ERROR#--getSingleField_SW('.$table.'.'.$field.', Type="'.$myType.'")=UNKNOWN--#]';
				$item .= t3lib_div::view_array(Array('File:Line='=>__FILE__.':'.__LINE__ ,'Backtrace='=>debug_backtrace()));
			break;
		}
		return $item;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$em: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$myMode: ...
	 * @param	[type]		$opt: ...
	 * @return	[type]		...
	 */
	function getSingleField_typeInput($table,$field,$row,$em,&$PCA,$myMode,$opt=Array()) {
		$classname = $this->getClassTag($field,$myMode,$PCA,$em);

		switch($em)	{
			case SGZ_FORM:
				if ($myMode=='date') {
					$myDate =  ($this->dateTimeStringToTime($row[$field])>10000) ? $row[$field] : date('d.m.Y H:i',intval($row[$field]))  ;
					$item = '<input type="text" name="'.$PCA['name'].$this->formDataName.'['.$field.']" '.$classname.' value="'.
								(  intval($row[$field])==0 ? '' : $myDate ).'" />';
				} else if ($myMode=='date') {
					$myDate =  ($this->dateStringToTime($row[$field])>10000) ? $row[$field] : date('d.m.Y',intval($row[$field]))  ;
					$item = '<input type="text" name="'.$PCA['name'].$this->formDataName.'['.$field.']" '.$classname.' value="'.
								(  intval($row[$field])==0 ? '' : $myDate ).'" />';
				} else if ($myMode=='time') {
					$myTime =  ($this->timeStringToTime($row[$field]) ? $row[$field] : date('H:i',intval($row[$field])-3600) )  ;
					$item = '<input type="text" name="'.$PCA['name'].$this->formDataName.'['.$field.']" '.$classname.' value="'.
								(  intval($row[$field])==0 ? '' : $myTime ).'" />';
				} else {
					$item = '<input onfocus="lastFocus=this" '.
								($PCA['conf'][$field]['maxlen'] ? 'maxlength="'.$PCA['conf'][$field]['maxlen'].'" ' : '').
								'type="'.( (strtolower($field)=='password' || $myMode=='password' )?'password':'text').'"  name="'.
								$PCA['name'].$this->formDataName.'['.$field.']" '.$classname.' value="'.htmlspecialchars($row[$field]).'" />';
				}
			break;
			case SGZ_SEARCH:
			case SGZ_SEARCHALL:
			case SGZ_SEARCHUSED:
				$item = '<input type="'.(strtolower($field)=='password'?'password':'text').'"  name="'.$PCA['name'].'[search]['.$field.']" '.
							$classname.' value="'.$row[$field].'" />';
			break;
			default:
			if ($myMode=='datetime') {
				$myDate =  ($this->dateTimeStringToTime($row[$field])>10000) ?
					$row[$field] : ($row[$field] > 80000 ? $this->dateObj->formatDateTime($row[$field]) : '') ;
				$item = (intval($row[$field])==0) ? '' : $myDate;
			} else if ($myMode=='date') {
				$myDate =  ($this->dateStringToTime($row[$field])>10000) ?
					$row[$field] : ($row[$field] > 80000 ? $this->dateObj->formatDate($row[$field]) : '') ;
				$item = (intval($row[$field])==0) ? '' : $myDate;
			} else if ($myMode=='time') {
				$myTime =  ($this->timeStringToTime($row[$field]) ? $row[$field] : $this->dateObj->formatTime($row[$field]-3600) )  ;
				$item = (intval($row[$field])==0) ? '' : $myTime;
			} else {
				if ($tmp=$PCA['conf'][$field]['numFormat']) {
					if (strtolower($tmp[0])=='d')	{
							$mt = '.';
							$mk = ',';
					} else if (strtolower($tmp[0])=='u') {
							$mt = ',';
							$mk = '.';
							break;
					} else if (strtolower($tmp[0])=='f') {
							$mt = ' ';
							$mk = ',';
							break;
					} else 	{
							$mt = '';
							$mk = ',';
					}
					$dp = intval($tmp[1]);
					$item = number_format ($row[$field], $dp, $mk,$mt);
				} else {
					$tmp = '';
					if ($PCA['conf'][$field]['pdflink'] && strlen($row[$field])>2 ) {
						$tmp = '<input type="hidden" name="sg_zlib_pdflink" value="'.
								( $PCA['conf'][$field]['uploadfolder'] ? $PCA['conf'][$field]['uploadfolder'].'/' : '').$row[$field].'" />';
					}
					$item = ((strtolower($field)=='password' || $myMode=='password' )? '********' : $row[$field]).$tmp;
				}
			}
			if (!$item) {
				$ifEmpty = $PCA['conf'][$field]['ifempty'];
				$item = $ifEmpty;
			}
			break;
		}

		return $item;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$em: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$myMode: ...
	 * @param	[type]		$opt: ...
	 * @return	[type]		...
	 */
	function getSingleField_typeText($table,$field,$row,$em,&$PCA,$myMode,$opt=Array()) {
		$classname = $this->getClassTag($field,'textarea',$PCA,$em);

		switch($em)	{
			case SGZ_AUTOHIDDEN:
			case SGZ_AUTO:
			case SGZ_FORM:
				$item = '<textarea onfocus="lastFocus=this" name="'.$PCA['name'].$this->formDataName.'['.$field.']" '.$classname;
				//already in $classname!! //$item .= $this->getPAfieldParam($PCA,$field,'rows',Array(' rows="','"'));
				//already in $classname!! //$item .= $this->getPAfieldParam($PCA,$field,'cols',Array(' cols="','"'));
				if (strcmp($myMode,'imageres')==0 || strcmp($myMode,'textnowrap')==0) {
					$item .= ' wrap="off"';
				} else {
					$item .= ' wrap="virtual"';
				}
				$item .= '>'.CRLF.htmlspecialchars($row[$field]).'</textarea>';
			break;
			case SGZ_SEARCH:
			case SGZ_SEARCHALL:
			case SGZ_SEARCHUSED:
				$item = '<input type="text" name="'.$PCA['name'].'[search]['.$field.']" '.
						$classname.' value="'.$row[$field].'" />';
			break;
			default:
				if (strcmp($myMode,'imageres')==0) {
					$item = '';
					if (strlen($row[$field])>0) {
						$item = ( ($PCA['conf'][$field]['uploadfolder']) ? $PCA['conf'][$field]['uploadfolder'].'/' : '').$row[$field];
					}
				} else if (strcmp($myMode,'linklist')==0) {
					$item = 'links: '.$this->divObj->myNl2br($row[$field],($PCA['conf'][$field]['stdWrapName']) ? chr(10) : '');
				} else {
					if ($PCA['conf'][$field]['firstLineWrap']) {
						$wrap = t3lib_div::trimExplode('|',$PCA['conf'][$field]['firstLineWrap']);
						$tmp = explode(chr(10),$this->divObj->myNl2br($row[$field],chr(10)));
						if (count($tmp)>0) {
							$first = $wrap[0].$tmp[0].$wrap[1]; unset($tmp[0]);
							$row[$field] = $first.($PCA['conf'][$field]['firstLineNoBr'] ? '' : chr(10) ).
								implode (chr(10),$tmp);
						}
					}
					$item = $this->divObj->myNl2br($row[$field],($PCA['conf'][$field]['stdWrapName']) ? chr(10) : '');
					// t3lib_div::debug(Array('$row[$field]'=>$row[$field], '$item'=>$item, 'File:Line'=>__FILE__.':'.__LINE__));
					if (!$item) {
						$ifEmpty = $PCA['conf'][$field]['ifempty'];
						$item = $ifEmpty;
					}
				}
			break;
		}

		return $item;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$em: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$myMode: ...
	 * @param	[type]		$opt: ...
	 * @return	[type]		...
	 */
	function getSingleField_typeCheck($table,$field,$row,$em,&$PCA,$myMode='',$opt=Array()) {
		$classname = $this->getClassTag($field,'check',$PCA,$em);
		$iAmEditor = FALSE;
		if ($this->permitObj->allowed('unhideUserlist') && $PCA['ctrl']['crfeuser_id'] &&
			t3lib_div::inList($this->permitObj->allowed('unhideUserlist'),$row[$PCA['ctrl']['crfeuser_id']])) {
			$iAmEditor = TRUE;
		} else if ($this->permitObj->allowed('unhideCatlist') && t3lib_div::inList($this->permitObj->allowed('unhideCatlist'),$row[$this->permitObj->allowed('catlistCatfield')])) {
			$iAmEditor = TRUE;
		}

		switch($em)	{
			case SGZ_FORM:
				if (strcmp($PCA['ctrl']['enablecolumns']['disabled'],$field)==0 &&
					!$this->permitObj->allowed('admin') && !$iAmEditor && !$this->permitObj->allowed('unhideAll') &&
					!($this->permitObj->allowed('unhideOwn') && $this->feUser['uid']==$row[$PCA['ctrl']['crfeuser_id']])
					) {
					//t3lib_div::debug(Array('hidden'=>$this->permitObj->allow, $PCA['ctrl']['crfeuser_id']=>$row[$PCA['ctrl']['crfeuser_id']], 'File:Line'=>__FILE__.':'.__LINE__));
					$item = '<input disabled="disabled" type="checkbox"  name="'.$PCA['name'].$this->formDataName.'['.$field.']" '.
							$classname.' value="1" '.($row[$field]>0?'checked="checked"':'').' />';
				} else {
					$item = '<input type="hidden" name="'.$PCA['name'].$this->formDataName.'['.$field.']" value="0" />'.
							'<input type="checkbox"  name="'.$PCA['name'].$this->formDataName.'['.$field.']" '.
							$classname.' value="1" '.($row[$field]>0?'checked="checked"':'').' />';
				}
			break;
			case SGZ_SEARCH:
			case SGZ_SEARCHALL:
			case SGZ_SEARCHUSED:
				$item = '[[[???]]]';
			break;
			default:
				$trueItem = (is_array($PCA['conf'][$field]['display'])) ? $PCA['conf'][$field]['display']['true']  : 'X';
				$falseItem = (is_array($PCA['conf'][$field]['display'])) ? $PCA['conf'][$field]['display']['false']  :'&nbsp;';
				$item = ($row[$field] > 0) ? $trueItem : $falseItem;
			break;
		}

		return $item;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$em: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$myMode: ...
	 * @param	[type]		$opt: ...
	 * @return	[type]		...
	 */
	function getSingleField_typeSelect($table,$field,$row,$em,&$PCA,$myMode,$opt=Array()) {
		global $TCA;
		$myFieldDef = $PCA['conf'];
		$classname = $this->getClassTag($field,'select',$PCA,$em);
		$classcheck = $this->getClassTag($field,'check',$PCA,$em);
		$classradio = $this->getClassTag($field,'radio',$PCA,$em);
		$myConf = $PCA['conf'][$field];

		if ($myMode=='selectmulti' && intval($myConf['maxitems'])==1) {
			$myMode='selectsingle';
		}

		//if ($myMode=='group') {
		//	$groupMode = $PCA['conf'][$field]['internal_type'];
		//	$groupAllowed = $PCA['conf'][$field]['allowed'];
		//}

		if ($em<SGZ_SEARCH && is_array($this->foreign[$em]) && is_array($this->foreign[$em][$table.'.'.$field])) {
			$myItems = $this->foreign[$em][$table.'.'.$field];
		} else if ($em>=SGZ_SEARCH) {
			$this->itemsObj->prepareItems($table,$field,$em,$row);
			$myItems = $this->itemsObj->getItemList($table,$field,$em);
			if (is_array($opt['options'])) {
				$myItems = array_merge ($myItems,$opt['options']);
			}
			if (!is_array($this->foreign[$em])) { $this->foreign[$em] = Array(); }
		} else {
			$this->itemsObj->prepareItems($table,$field,$em,$row);
			$myItems = $this->itemsObj->getItemList($table,$field,$em);
			if (!is_array($this->foreign[$em])) { $this->foreign[$em] = Array(); }
			$this->foreign[$em][$table.'.'.$field] = $myItems;
		}

		if ($em==SGZ_FORM) {
			if ($myMode=='selectmulti') {
				$item = '<select multiple="multiple"'.
					$this->getPAfieldParam($PCA,$field,'rows',Array(' size="','"')).
					' name="'.$PCA['name'].'[list]['.$field.']" '.$classname.'>';
				$myElement = t3lib_div::trimExplode(',',trim($row[$field]));
				if (strcmp($PCA['conf'][$field]['refType'],'title')==0) {
					if (strlen(trim($row[$field]))>0)  for ($i=0;$i<count($myElement);$i++) {
						$item .= '<option value="'.$myElement[$i].'">'.$myElement[$i].'</option>';
					}
				} else {
					if (strlen(trim($row[$field]))>0)  for ($i=0;$i<count($myElement);$i++) {
						$item .= '<option value="'.$myElement[$i].'">'.$myItems[$myElement[$i].'.'].'</option>';
					}
				}
				$item .= '</select>';
				$item .= '<input type="hidden" name="'.$PCA['name'].$this->formDataName.'['.$field.']" value="'.$row[$field].'" />';
			} else if ($myMode=='selectsingle') {
				$item = '<select multiple="multiple"'.
					$this->getPAfieldParam($PCA,$field,'rows',Array(' size="','"')).
					' name="'.$PCA['name'].'[list]['.$field.']" '.$classname.'>';
				$myElement = t3lib_div::trimExplode(',',trim($row[$field]));
				if (strcmp($PCA['conf'][$field]['refType'],'title')==0) {
					if (strlen(trim($row[$field]))>0)  for ($i=0;$i<count($myElement);$i++) {
						$item .= '<option value="'.$myElement[$i].'">'.$myElement[$i].'</option>';
					}
				} else {
					if (strlen(trim($row[$field]))>0)  for ($i=0;$i<count($myElement);$i++) {
						$item .= '<option value="'.$myElement[$i].'">'.$myItems[$myElement[$i].'.'].'</option>';
					}
				}
				$item .= '</select>';
				$item .= '<input type="hidden" name="'.$PCA['name'].$this->formDataName.'['.$field.']" value="'.$row[$field].'" />';
			} else if ($myMode=='selectbits') {
				$item = '<input type="hidden" name="'.$PCA['name'].$this->formDataName.'['.$field.']" value="'.$row[$field].'" />';
				$myWrap = Array();
				$myWrap = explode ('|',$PCA['conf'][$field]['textWrap']);
				$p=1;
				$tmp = intval($row[$field]);
				$myMax = (isset($PCA['conf'][$field]['max'])) ? intval($PCA['conf'][$field]['max']) : 1 ;
				for ($j=0;$j<31;$j++) {
					if ($j<$myMax) {
						$onClick = ' onclick="addFromCheck('.
								"'".$PCA['name']."','".$field."'".','.			// Variable Name
								$p.','.(pow(2,31)-$p-1).','.($j+1).');'.
								' return true;"'
								;
						$item .= $myWrap[0].
							'<input type="checkbox"  name="'.$PCA['name'].'[bits]['.$field.']['.($j+1).']" '.
									$classcheck.$onClick.' value="'.$p.'" '.(($tmp & 1)?'checked="checked"':'').' />'.
							$myItems[($j+1).'.'].
							$onCLick.
							$myWrap[1];
					}
					$tmp = intval($tmp / 2);
					$p = $p * 2;
				}
			} else { //select, radio, checklsit
				$item = '<input type="hidden" name="'.$PCA['name'].'[old]['.$field.']" value="'.$row[$field].'" />';
				$item .= $this->getSelectFormList($myItems,$PCA['name'],$field,$row[$field],$classname,$myMode,$PCA,$em);
			}
		} else if ($em==SGZ_SEARCHALL || ($em==SGZ_SEARCHUSED && strcmp($PCA['conf'][$field]['type'],'input')==0)) {
			$onc = '';
			if (strcmp($PCA['search'][$field]['mode'],'selectmulti')==0) {
				$onc = 'onchange="javascript:sgSelectMultiChange('.QT.$PCA['name'].QT.','.QT.$field.QT.','.QT.$this->sMode.QT.')" ';
				$item = '<input type="hidden" name="'.$PCA['name'].'[search]['.$field.']" value="'.$row[$field].'" />';
				$item .= '<select name="'.$PCA['name'].'[searchmulti]['.$field.']" '.$onc.$classname.' multiple="multiple">';
				$set = false;
				$lnr = 0;
				for (reset($myItems);$key=key($myItems);next($myItems)) {
					$lnr++;
					$vValue = urlencode((substr($key,-1)=='.') ? substr($key,0,-1) : $key);
					$itemText = $this->itemsObj->getItemText('search',$lnr,$field,$myItems,$key,$PCA);
					if (strlen($row[$field])>0) {
						$item .= '<option '.((strstr(','.$row[$field].',', ','.$vValue.',')) ? 'selected="selected" ':'').
								'value="'.$vValue.'">'.$itemText.'</option>';
					} else {
						$item .= '<option '.(!$set?'selected="selected" ':'').
								'value="'.$vValue.'">'.$itemText.'</option>';
					}
					$set = true;
				}
				$item .= '</select>';
			} else {
				$onc = $this->getOnchangeSelect($PCA,$field);
				$item = $this->getSelectSearchList($myItems,$PCA['name'],$field,$row[$field],$onc,$classname,$myMode,$PCA,$em);
			}
		} else if ($em==SGZ_SEARCHUSED) {
			$myUsed = Array();
			if (is_array($myConf['preItems'])) {
				for (reset($myConf['preItems']);$key=key($myConf['preItems']);next($myConf['preItems'])) {
					$myUsed[($myConf['preItems'][$key]['id']).'.'] = 1;
				}
			}
			$onc = $this->getOnchangeSelect($PCA,$field);
			if ($myConf['MM']) {
				$query = $PCA['table'].'.uid='.$myConf['MM'].'.uid_local';
				$myQ = $this->getDbEnableColumns($PCA['table'],$PCA,$piVarSearch,Array());
				if (count($myQ)) {
					$query .= ' AND ('.implode(' AND ',$myQ).')';
				}
				$select = 'uid_foreign AS '.$field.', count(*) AS cnt';
				$group = 'uid_foreign';
				$myTable = $PCA['table'].','.$myConf['MM'];
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$myTable,$query,$group);

//				$query = '1=1 '; //.(($PCA['search'][$field]['hiddenAlso']) ? ' AND deleted=0 ' : $this->lCObj->enableFields($table));
//				$select = 'uid_foreign AS '.$field.', count(*) AS cnt';
//				$group = 'uid_foreign';
//				$myTable = $myConf['MM'];
//				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$myTable,$query,$group);
				$this->debugObj->debugval('getitemdetails',$field,Array('$select'=>$select, '$table(MM)'=>$myConf['MM'], '$query'=>$query, '$group'=>$group,
					'numRows='=>$GLOBALS['TYPO3_DB']->sql_num_rows($res), 'File:Line'=>__FILE__.':'.__LINE__));
			} else {
				$query = '1=1 '.(($PCA['search'][$field]['hiddenAlso']) ? ' AND deleted=0 ' : $this->lCObj->enableFields($table));
				if ($PCA['ctrl']['defaultWhere']) {
					$query .= str_replace('###val###',$PCA['ctrl']['defaultWhereVal'],' AND ('.$PCA['ctrl']['defaultWhere'].')');
				}
				$select = $field.', count(*) AS cnt';
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $table,$query,$field);
				$this->debugObj->debugVal('getitemdetails',$field,Array('$select'=>$select, '$table'=>$table, '$query'=>$query,
					'numRows='=>$GLOBALS['TYPO3_DB']->sql_num_rows($res), 'File:Line'=>__FILE__.':'.__LINE__));
			}

			if (!$GLOBALS['TYPO3_DB']->sql_error()) {
				$this->debugObj->debugIf('sql',Array('query'=>$query, 'res /  count'=>$res.' / '.$GLOBALS['TYPO3_DB']->sql_num_rows($res),
						'FILE:LINE='=>__FILE__.':'.__LINE__ ));
				while($myRow=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
					if ($myMode=='selectmulti') {
						$tmp = explode (',',$myRow[$field]);
						for ($j=0;$j<count($tmp);$j++) {
							$myUsed[$tmp[$j].'.'] = 1;
							$this->itemsObj->getItemCountAdd('',$field,$tmp[$j].'.',intval($myRow['cnt']));
						}
					} else {
						$myUsed[$myRow[$field].'.'] = 1;
						$this->itemsObj->getItemCountAdd('',$field,$myRow[$field].'.',intval($myRow['cnt']));
					}
				}
			}
			if (is_array($myConf['postItems'])) {
				for (reset($myConf['postItems']);$key=key($myConf['postItems']);next($myConf['postItems'])) {
					$myUsed[($myConf['postItems'][$key]['id']).'.'] = 1;
				}
			}

			if (!is_array($myUsed)) {
				$item = $this->getSelectSearchList($myItems,$PCA['name'],$field,$row[$field],$onc,$classname,$myMode,$PCA,$em);
			} else {
				$myXItems = Array();
				for (reset($myItems);$muKey=key($myItems);next($myItems)) if($myUsed[$muKey]) {
					if (isset($myItems[$muKey])) {
						$myXItems[$muKey] = $myItems[$muKey];
					}
				}
				$item = $this->getSelectSearchList($myXItems,$PCA['name'],$field,$row[$field],$onc,$classname,$myMode,$PCA,$em);
			}
		} else {

			if ($myMode=='selectmulti' || $myMode=='selectsingle' || $myMode=='checklist') {
				$item = '';
				$myWrap = Array();
				$myWrap = explode ('|',$PCA['conf'][$field]['textWrap']);
				$tmp = t3lib_div::trimExplode(',',$row[$field]);
				$tmpRefType = $PCA['conf'][$field]['refType'];
				if (is_array($PCA['conf'][$field]['typoLink'])) {
					$tlc = $PCA['conf'][$field]['typoLink'];
					$tmpAP = $tlc['additionalParams'].'';
					if (!$tlc['parameter'] || strcmp($tlc['parameter'],'0')==0) {
						$tlc['parameter'] = $GLOBALS['TSFE']->id;
					}
					$tmpP = $tlc['parameter'];
					$tmpAPx = '';
					if ($tla=$PCA['conf'][$field]['addSearch']) {
						$tmpAPx = '&'.$PCA['name'].'[searchmode]=1';
						$tmpAPx .= '&'.$PCA['name'].'[search]['.$tla['searchField'].']=';
					}

					$this->debugObj->debugIf('typoLink',Array('typoLink ='=>$tlc, 'refType ='=>$tmpRefType, 'File:Line'=>__FILE__.':'.__LINE__));
					if (strcmp($tmpRefType,'inside')==0) {
						for ($j=0;$j<count($tmp);$j++)  if (strlen($tmp[$j])) {
							$this->debugObj->debugIf('typoLink',Array('&title='=>urlencode($tmp[$j]), 'File:Line'=>__FILE__.':'.__LINE__));
							$tlc['parameter'] = $this->lCObj->substituteMarkerArray($tmpP,
									$this->itemsObj->getItemsRecord('',$field,$tmp[$j].'.'), '###|###');
							$name = $this->lCObj->substituteMarkerArray($tlc['item'],
									$this->itemsObj->getItemsRecord('',$field,$tmp[$j].'.'), '###|###');
							$tlc['additionalParams'] = $this->lCObj->substituteMarkerArray($tmpAP.$tmpAPx.substr($name,0,1),
									$this->itemsObj->getItemsRecord('',$field,$tmp[$j].'.'), '###|###');
							$tl = $this->lCObj->typoLink($name,$tlc);
							$item .= $myWrap[0].$tl.$myWrap[1];
						}
					} else if (strcmp($tmpRefType,'title')==0){
						for ($j=0;$j<count($tmp);$j++)  if (strlen($tmp[$j])) {
							$this->debugObj->debugIf('typoLink',Array('&title='=>urlencode($tmp[$j]), 'File:Line'=>__FILE__.':'.__LINE__));
							$tlc['parameter'] = str_replace('###id###',intval($tmp[$j]),$tmpP);
							$tlc['additionalParams'] = $tmpAP.$tmpAPx.substr($tmp[$j],0,1).'&title='.urlencode($tmp[$j]);
							$tl = $this->lCObj->typoLink($tmp[$j],$tlc);
							$item .= $myWrap[0].$tl.$myWrap[1];
						}
					} else if ($tmpRefType=='1') {
						for ($j=0;$j<count($tmp);$j++) if (intval($tmp[$j])) {
							$this->debugObj->debugIf('typoLink',Array('&uid='=>intval($tmp[$j]), 'File:Line'=>__FILE__.':'.__LINE__));
							$tlc['parameter'] = str_replace('###id###',intval($tmp[$j]),$tmpP);
							$tlc['additionalParams'] = $tmpAP.'&uid='.intval($tmp[$j].'.');
							$tl = $this->lCObj->typoLink($myItems[$tmp[$j].'.'],$tlc);
							$item .= $myWrap[0].$tl.$myWrap[1];
						}
					} else  {
						for ($j=0;$j<count($tmp);$j++) if (intval($tmp[$j])) {
							$tlc['parameter'] = str_replace('###id###',intval($tmp[$j]),$tmpP);
							$tmpRefType = str_replace('###myuid###',$this->todo['Uid'],$tmpRefType);
							if (strcmp($tmpRefType,'none')) {
								$tlc['additionalParams'] = $tmpRefType ? $tmpAP.$tmpRefType.intval($tmp[$j].'.') : '';
							}
							$this->debugObj->debugIf('typoLink',Array('[param]'.$tmpRefType=>intval($tmp[$j]), 'typoLink ='=>$tlc, 'File:Line'=>__FILE__.':'.__LINE__));
							$tl = $this->lCObj->typoLink($myItems[$tmp[$j].'.'],$tlc);
							$item .= $myWrap[0].$tl.$myWrap[1];
						}
					}
				} else {
					if (strcmp($tmpRefType,'title')==0) {
						for ($j=0;$j<count($tmp);$j++) {
							$item .= $myWrap[0].$tmp[$j].$myWrap[1];
						}
					} else {

						for ($j=0;$j<count($tmp);$j++) {
							$item .= $myWrap[0].$this->itemsObj->getItemText('conf',1,$field,$myItems,$tmp[$j].'.',$PCA,'','viewFormat').
								$myWrap[1];
						}
					}
				}

			} else if ($myMode=='selectbits') {
				$item = '';
				$myWrap = Array();
				$myWrap = explode ('|',$PCA['conf'][$field]['textWrap']);
				$j=0;
				$myMax = (isset($PCA['conf'][$field]['max'])) ? intval($PCA['conf'][$field]['max']) : 1 ;
				for ($tmp=intval($row[$field]);$tmp>0;$tmp=intval($tmp/2)) {
					$j=$j+1;
					if (($tmp & 1) && $j<=$myMax) {
						$item .= $myWrap[0].$myItems[$j.'.'].$myWrap[1];
					}
				}
			} else {
				if (is_array($PCA['conf'][$field]['second'])) {
					if (intval($PCA['conf'][$field]['second']['value'])==intval($row[$field])) {
						//t3lib_div::debug(Array('Second='=>$PCA['conf'][$field]['second'], 'File:Line'=>__FILE__.':'.__LINE__));
						$item = $this->getSingleField_typeSelect($table,$PCA['conf'][$field]['second']['field'],$row,$em,$PCA,$myMode);
					} else {
						$item = $this->itemsObj->getItemText('conf',1,$field,$myItems,$row[$field].'.',$PCA,'','viewFormat');
					}
				} else {
				$item = $this->itemsObj->getItemText('conf',1,$field,$myItems,$row[$field].'.',$PCA,'','viewFormat');
				}
			}
		}
		if (strcmp($field,$this->debugField)==0) {
			t3lib_div::debug(Array('$field'=>$field, '$row[$field]'=>$row[$field], '$item'=>$item, 'File:Line'=>__FILE__.':'.__LINE__));
			$item = '[[['.$item.']]]';
		}
		return (strlen($item)>0 ? $item : $this->conf['nbspForSelect']);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$field: ...
	 * @return	[type]		...
	 */
	function getOnchangeSelect($PCA,$field) {
		$onc = '';
		if ($PCA['search'][$field]['onchange']) {
			$delJS = '';
			$zeroFields = strlen($PCA['search'][$field]['changezero'])>1 ? t3lib_div::trimExplode(',',$PCA['search'][$field]['changezero']) : Array();
			for ($j=0;$j<count($zeroFields);$j++) {
				$delJS .= 'sgZeroSearchForm('.QT.$PCA['name'].QT.','.QT.$this->sMode.QT.','.QT.$zeroFields[$j].QT.');';
			}
			$delFields = strlen($PCA['search'][$field]['changedel'])>1 ? t3lib_div::trimExplode(',',$PCA['search'][$field]['changedel']) : Array();
			for ($j=0;$j<count($delFields);$j++) {
				$delJS .= 'sgDelSearchForm('.QT.$PCA['name'].QT.','.QT.$this->sMode.QT.','.QT.$delFields[$j].QT.');';
			}
			$disFields = strlen($PCA['search'][$field]['changedisable'])>1 ? t3lib_div::trimExplode(',',$PCA['search'][$field]['changedisable']) : Array();
			for ($j=0;$j<count($disFields);$j++) {
				$delJS .= 'sgDisableSearchForm('.QT.$PCA['name'].QT.','.QT.$this->sMode.QT.','.QT.$disFields[$j].QT.');';
			}
			if (strcmp('reload',$PCA['search'][$field]['onchange'])==0) {
				$onc = 'onchange="javascript:'.$delJS.'sgReLoadSearchForm('.QT.QT.','.QT.$PCA['name'].QT.','.QT.$this->sMode.QT.','.QT.$GLOBALS['TSFE']->id.QT.')" ';
			} else if (strcmp('xajax',$PCA['search'][$field]['onchange'])==0) {
				$onc = 'onchange="javascript:'.$delJS.$this->xajaxPrefix.'xajax_process_'.$field.'(xajax.getFormValues(\'search'.$this->sMode.'\')); return(false);" ';
			}
		}

		return($onc);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$myItems: ...
	 * @param	[type]		$name: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$value: ...
	 * @param	[type]		$classname: ...
	 * @param	[type]		$myMode: ...
	 * @param	[type]		$myUsed: ...
	 * @param	[type]		$em: ...
	 * @param	[type]		$em: ...
	 * @return	[type]		...
	 */
	function getSelectSearchList($myItems,$name,$field,$value,$onc,$classname,$myMode,$PCA,$em=0) {
		$wrap = t3lib_div::trimExplode('|',$PCA['search'][$field]['wrap']);
		$wrapAll = t3lib_div::trimExplode('|',$PCA['search'][$field]['wrapAll']);
		$wrapLabel = t3lib_div::trimExplode('|',$PCA['search'][$field]['wrapLabel']);
		$wrapInput = t3lib_div::trimExplode('|',$PCA['search'][$field]['wrapInput']);
		$directLink = $PCA['search'][$field]['directLink'];

		$item = '';
		$set = false;

		// if ($field=="category") t3lib_div::debug(Array('$myItems'=>$myItems, 'File:Line'=>__FILE__.':'.__LINE__));
		$this->debugObj->debugIf('searchItems',Array('$myItems'=>$myItems, 'File:Line'=>__FILE__.':'.__LINE__),$field==$this->dodebug['searchItems']);

		if ($PCA['search'][$field]['cntOrder']) {
			$mc = $this->itemsObj->getItemCountAdd('',$field);
			if ($myMode=='checklist' || $myMode=='selectlist') {
				unset($mc['0.']);
			}
			$max = $PCA['search'][$field]['maxList']>2 ? $PCA['search'][$field]['maxList'] : 10;
			if ($PCA['search'][$field]['cntOrder']>0) {
				arsort ($mc);
			} else {
				asort ($mc);
			}
			$old = $myItems;
			$myItems = Array();
			for (reset($mc);$key=key($mc);next($mc)) if ($max>0 && isset($old[$key])) {
				$myItems[$key] = $old[$key];
				$max--;
			}
		} else if ($PCA['search'][$field]['alphaOrder']) {
			asort ($myItems);
		}

		$this->debugObj->debugIf('searchItems',Array('$myItems'=>$myItems, 'File:Line'=>__FILE__.':'.__LINE__),$field==$this->dodebug['searchItems']);
		if ($myMode=='radio') {
			$classname = $this->getClassTag($field,$myMode,$PCA,$em);
			$blockClass = $this->getClassTag($field,$myMode,$PCA,$em,'block');
			if ($blockClass) {
				$item .= '<div '.$blockClass.'>'.CRLF;
			}
			$item .= $wrapAll[0];
			$lnr = 0;
			for (reset($myItems);$key=key($myItems);next($myItems)) {
				$lnr++;
				$classname = $this->getClassTag($field,$myMode,$PCA,$em);
				$vValue = urlencode((substr($key,-1)=='.') ? substr($key,0,-1) : $key);
				$itemText = $this->itemsObj->getItemText('search',$lnr,$field,$myItems,$key,$PCA);
				if (strlen($value)>0) {
					$item .= TAB.$wrap[0].$wrapInput[0].'<input name="'.$name.'[search]['.$field.']" type="radio" '.
						($vValue==$value?'checked="checked" ':'').
						$classname.' id="'.$name.'_'.$field.'_'.$vValue.'" '.
						'value="'.$vValue.'"/>'.$wrapInput[1].CRLF;
				} else {
					$item .= TAB.$wrap[0].$wrapInput[0].'<input name="'.$name.'[search]['.$field.']" type="radio" '.
						(!$set?'checked="checked" ':'').
						$classname.' id="'.$name.'_'.$field.'_'.$vValue.'" '.
						'value="'.$vValue.'"/>'.$wrapInput[1].CRLF;
				}
				$item .= TAB.$wrapLabel[0].'<label for="'.$name.'_'.$field.'_'.$vValue.'">'.$itemText.'</label>'.$wrapLabel[1].$wrap[1].CRLF;
				$set = true;
			}
			$item .= $wrapAll[1];
			if ($blockClass) {
				$item .= '</div>'.CRLF;
			} else {
				$item .= CRLF;
			}
		} else if ($myMode=='checklist') {
			$classname = $this->getClassTag($field,$myMode,$PCA,$em);
			$blockClass = $this->getClassTag($field,$myMode,$PCA,$em,'block');
			if ($blockClass) {
				$item .= '<div '.$blockClass.'>'.CRLF;
			}
			$item .= $wrapAll[0];
			$lnr = 0;
			$splitCols = $PCA['search'][$field]['colNums'];
			$splitInsert = $PCA['search'][$field]['colInsert'];
			$colCnt = $splitCols>1 && count($myItems)>3 ? intval((count($myItems) - 1 + $splitCols) / $splitCols) : 0;
			$cnt = $colCnt;
			for (reset($myItems);$key=key($myItems);next($myItems)) {
				$lnr++;
				$cnt--;
				$subField = urlencode(str_replace('=','',((substr($key,-1)=='.') ? substr($key,0,-1) : $key)));
				$vValue = urlencode(((substr($key,-1)=='.') ? substr($key,0,-1) : $key));
				$itemText = $this->itemsObj->getItemText('search',$lnr,$field,$myItems,$key,$PCA);
				if ($vValue!='null' && strlen($itemText)>0)
				$item .= TAB.$wrap[0].$wrapInput[0].'<input name="'.$name.'[search]['.$field.']['.$subField.']" type="checkbox" '.
						(isset($value[$vValue])?'checked="checked" ':'').
						$classname.' id="'.$name.'_'.$field.'_'.$vValue.'" '.
						'value="'.$vValue.'" />'.$wrapInput[1].CRLF.
						TAB.$wrapLabel[0].'<label for="'.$name.'_'.$field.'_'.$vValue.'">'.$itemText.'</label>'.$wrapLabel[1].$wrap[1].CRLF;
				if ($cnt<1 && $splitCols>1 && count($myItems)>3) {
					$cnt = $colCnt;
					$item .= $splitInsert;
				}
			}
			$item .= $wrapAll[1];
			if ($blockClass) {
				$item .= '</div>'.CRLF;
			} else {
				$item .= CRLF;
			}
		} else if ($myMode=='selectlist') {
			$classname = $this->getClassTag($field,$myMode,$PCA,$em);
			$blockClass = $this->getClassTag($field,$myMode,$PCA,$em,'block');
			if ($blockClass) {
				$item .= '<div '.$blockClass.'>'.CRLF;
			}
			$item .= $wrapAll[0];
			$lnr = 0;
			for (reset($myItems);$key=key($myItems);next($myItems)) {
				$lnr++;
				$subField = urlencode(str_replace('=','',((substr($key,-1)=='.') ? substr($key,0,-1) : $key)));
				$vValue = urlencode(((substr($key,-1)=='.') ? substr($key,0,-1) : $key));
				$itemText = $this->itemsObj->getItemText('search',$lnr,$field,$myItems,$key,$PCA);
				if ($vValue!='null' && strlen($itemText)>0) {
					$href = $this->emptyUrl;
					$onc = ' onclick="sgAbcSubmit('.
						QT.intval($key).QT.','.QT.$PCA['name'].QT.','.QT.$this->sMode.QT.",'".$field."'".');return false;"';
					if (is_array($directLink)) {
						$onc = '';
						$myListPage = (intval($directLink['default'])>0 || strcmp($directLink['default'],'0')==0) ?
							intval($directLink['default']) : $this->listPage;
						$myListPage = (intval($directLink[$this->itemsObj->getItemsPid('',$field,$key)])>0) ?
							intval($directLink[$this->itemsObj->getItemsPid('',$field,$key)]) : $myListPage;

						$href = $this->getTypolinkURL($myListPage,
								'&'.$PCA['name'].'[searchmode]=1&'.$PCA['name'].'[search]['.$field.']='.$vValue);
					}
					$item .= TAB.$wrap[0].'<a href="'.$href.'"'.$onc.'>'.$itemText.'</a>'.$wrap[1].CRLF;
				}
			}
			if (!is_array($directLink)) {
				$item .= $wrapAll[1].'<input type="hidden" name="'.$PCA['name'].'[search]['.$field.']" value="" />';
			}
			if ($blockClass) {
				$item .= '</div>'.CRLF;
			} else {
				$item .= CRLF;
			}
		} else {
			$tmpItems = '';
			$set = false;
			$lnr=0;
			$this->js_array = '';
			$link_options = Array();
			$myConf=array();
			$myConf['parameter'] = $GLOBALS['TSFE']->id;
			for (reset($myItems);$key=key($myItems);next($myItems)) {
				$lnr++;
				$vValue = urlencode((substr($key,-1)=='.') ? substr($key,0,-1) : $key);
				$itemText = $this->itemsObj->getItemText('search',$lnr,$field,$myItems,$key,$PCA);
				if (strlen($value)>0) {
					$tmpItems .= TAB.$wrap[0].'<option '.($vValue==$value?'selected="selected" ':'').
							' value="'.$vValue.'" '.$classname.'>'.$itemText.'</option>'.$wrap[1].CRLF;
				} else {
					$tmpItems .= TAB.$wrap[0].'<option '.(!$set?'selected="selected" ':'').
							' value="'.$vValue.'" '.$classname.'>'.$itemText.'</option>'.$wrap[1].CRLF;
				}
				$myConf['additionalParams'] = t3lib_div::implodeArrayForUrl('',Array(
					$PCA['name'].'[search]['.$field.']'=>$vValue,
					$PCA['name'].'[searchmode]'=>1
					),'',1);
				$link_options[] = QT.$this->lCObj->typoLink_URL($myConf).QT;
				$set = true;
			}
			if (count($link_options)) {
				$this->js_array = 'dl_'.$PCA['name'].' = new Array('.implode(',',$link_options).');';
			}

			$item .= $wrapAll[0];
			if (is_array($directLink)) {
				if ($this->js_array) {
					$onc = $this->js_array." window.location = dl_".$PCA['name']."[this.selectedIndex]; return false;";
				} else {
					$onc = "document.searchform_".$PCA['name'].".submit(); return(false)";
				}
				$item .= '<select name="'.$name.'[search]['.$field.']" '.$classname.' onchange="'.$onc.'">'.CRLF;
			} else {
				$item .= '<select name="'.$name.'[search]['.$field.']" '.$onc.' '.$classname.'>'.CRLF;
			}

			$item .= $tmpItems.'</select>'.CRLF;
			$item .= $wrapAll[1];
		}
		return ($item);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$myItems: ...
	 * @param	[type]		$name: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$value: ...
	 * @param	[type]		$classname: ...
	 * @param	[type]		$myMode: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$em: ...
	 * @return	[type]		...
	 */
	function getSelectFormList($myItems,$name,$field,$value,$classname,$myMode,$PCA,$em=0) {
		$wrap = t3lib_div::trimExplode('|',$PCA['conf'][$field]['wrap']);
		$wrapAll = t3lib_div::trimExplode('|',$PCA['conf'][$field]['wrapAll']);
		$wrapLabel = t3lib_div::trimExplode('|',$PCA['conf'][$field]['wrapLabel']);
		$wrapInput = t3lib_div::trimExplode('|',$PCA['conf'][$field]['wrapInput']);
		$item = '';
		$set = false;

		if ($PCA['conf'][$field]['alphaOrder']) {
			asort ($myItems);
		}

		$onc = '';
		if ($PCA['conf'][$field]['onchange']) {
			$delField = strlen($PCA['conf'][$field]['changedel'])>1 ? $PCA['conf'][$field]['changedel'] : '';
			if (strcmp('reload',$PCA['conf'][$field]['onchange'])==0) {
				$onc = 'onchange="javascript:sgConfReload('.QT.QT.','.QT.$delField.QT.','.QT.QT.','.QT.QT.',0)" ';
			} else if (strcmp('confirmreload',$PCA['conf'][$field]['onchange'])==0) {
				$chtxt = strlen($PCA['conf'][$field]['onchange'])>1 ? $this->langObj->getLLL($PCA['conf'][$field]['changetext']) : 'Change ?';
				$onc = 'onchange="javascript:sgConfReload('.QT.$chtxt.QT.','.QT.$delField.QT.','.QT.$PCA['name'].QT.','.QT.$field.QT.',0)" ';
			} else if (strcmp('xajax',$PCA['conf'][$field]['onchange'])==0) {
				$onc = 'onchange="'.$this->xajaxPrefix.'xajax_process_'.$field.'(xajax.getFormValues(\'sg_editform\')); return(false);" ';
			}
		}

		if ($myMode=='radio') {
			$classname = $this->getClassTag($field,$myMode,$PCA,$em);
			$blockClass = $this->getClassTag($field,$myMode,$PCA,$em,'block');
			if ($blockClass) {
				$item .= '<div '.$blockClass.'>'.CRLF;
			}
			$item .= $wrapAll[0];
			$lnr = 0;
			for (reset($myItems);$key=key($myItems);next($myItems)) {
				$lnr++;
				$classname = $this->getClassTag($field,$myMode,$PCA,$em);
				$vValue = urlencode((substr($key,-1)=='.') ? substr($key,0,-1) : $key);
				$itemText = $this->itemsObj->getItemText('conf',$lnr,$field,$myItems,$key,$PCA);
				if (strlen($value)>0) {
					$item .= TAB.$wrap[0].$wrapInput[0].'<input name="'.$name.$this->formDataName.'['.$field.']" type="radio" '.
						($vValue==$value?'checked="checked" ':'').
						$onc.$classname.' id="'.$name.'_'.$field.'_'.$vValue.'" '.
						'value="'.$vValue.'"/>'.$wrapInput[1].CRLF;
				} else {
					$item .= TAB.$wrap[0].$wrapInput[0].'<input name="'.$name.$this->formDataName.'['.$field.']" type="radio" '.
						(!$set?'checked="checked" ':'').
						$onc.$classname.' id="'.$name.'_'.$field.'_'.$vValue.'" '.
						'value="'.$vValue.'"/>'.$wrapInput[1].CRLF;
				}
				$item .= TAB.$wrapLabel[0].'<label for="'.$name.'_'.$field.'_'.$vValue.'">'.$itemText.'</label>'.$wrapLabel[1].$wrap[1].CRLF;
				$set = true;
			}
			$item .= $wrapAll[1];
			if ($blockClass) {
				$item .= '</div>'.CRLF;
			} else {
				$item .= CRLF;
			}
		} else if ($myMode=='checklist') {
			$classname = $this->getClassTag($field,$myMode,$PCA,$em);
			$blockClass = $this->getClassTag($field,$myMode,$PCA,$em,'block');
			if ($blockClass) {
				$item .= '<div '.$blockClass.'>'.CRLF;
			}
			$item .= $wrapAll[0];
			$item .= '<input type="hidden" '.$onc.' name="'.$name.$this->formDataName.'['.$field.']" value="'.$value.'" />'.CRLF;
			$lnr = 0;
			$tmp = explode(',',$value);
			$myVals = Array();
			for ($i=0;$i<count($tmp);$i++) {
				$myVals[$tmp[$i].'.'] = $tmp[$i];
			}

			for (reset($myItems);$key=key($myItems);next($myItems)) {
				$lnr++;
				$onclick = ' onclick="getListFromArray('.QT.$name.$this->formDataName.'['.$field.']'.QT.','.QT.$name.'[bits]['.$field.'][]'.QT.');"';
				$vValue = urlencode(((substr($key,-1)=='.') ? substr($key,0,-1) : $key));
				$itemText = $this->itemsObj->getItemText('conf',$lnr,$field,$myItems,$key,$PCA);
				if ($vValue!='null' && strlen($itemText)>0) {
					$item .= TAB.$wrap[0].$wrapInput[0].'<input name="'.$name.'[bits]['.$field.'][]" type="checkbox" '.
							($myVals[$vValue.'.']==$vValue ?'checked="checked" ':'').
							$classname.' id="'.$name.'_'.$field.'_'.$vValue.'" '.
							'value="'.$vValue.'"'.$onclick.' />'.$wrapInput[1].CRLF.
							TAB.$wrapLabel[0].'<label for="'.$name.'_'.$field.'_'.$vValue.'">'.$itemText.'</label>'.$wrapLabel[1].$wrap[1].CRLF;
				}
			}
			$item .= $wrapAll[1];
			if ($blockClass) {
				$item .= '</div>'.CRLF;
			} else {
				$item .= CRLF;
			}
		} else {
			$item .= $wrapAll[0];

			$item .= '<select name="'.$name.$this->formDataName.'['.$field.']" '.$onc.$classname.'>'.CRLF;
			//$classname = $this->getClassTag($field,$myMode,$PCA,$em);
			$set = false;
			$lnr=0;
			for (reset($myItems);$key=key($myItems);next($myItems)) {
				$lnr++;
//				$itemText = $this->itemsObj->getItemText('conf',$lnr,$field,$myItems,$key,$PCA);
//				$item .= '<option '.(intval($key)==intval($row[$field])?'selected="selected"':'').
//												'value="'.intval($key).'">'.$itemText.'</option>';
				$vValue = urlencode((substr($key,-1)=='.') ? substr($key,0,-1) : $key);
				$itemText = $this->itemsObj->getItemText('conf',$lnr,$field,$myItems,$key,$PCA);
				if (strlen($value)>0) {
					$item .= TAB.$wrap[0].'<option '.($vValue==$value?'selected="selected" ':'').
							$classname.' value="'.$vValue.'" '.$classname.'>'.$itemText.'</option>'.$wrap[1].CRLF;
				} else {
					$item .= TAB.$wrap[0].'<option '.(!$set?'selected="selected" ':'').
							$classname.' value="'.$vValue.'" '.$classname.'>'.$itemText.'</option>'.$wrap[1].CRLF;
				}
				$set = true;
			}
			$item .= '</select>'.CRLF;
			$item .= $wrapAll[1];
		}
		return ($item);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$em: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$myMode: ...
	 * @param	[type]		$size: ...
	 * @param	[type]		$mode: ...
	 * @param	[type]		$opt: ...
	 * @return	[type]		...
	 */
	function getSingleField_typeList($table,$field,$row,$em,&$PCA,$myMode,$size='s',$mode=0,$opt=Array()) {
		$classname = $this->getClassTag($field,'image',$PCA,$em);
		$myConf = $PCA['conf'][$field];

		if ($em==SGZ_FORM) {
			$item = '<select multiple="multiple"'.
				//$this->getPAfieldParam($PCA,$field,'rows',Array(' size="','"')).
				' name="'.$PCA['name'].'[list]['.$field.']" '.$classname.'>';
			$myElement = t3lib_div::trimExplode(',',trim($row[$field]));
			if (strlen(trim($row[$field]))>0)  for ($i=0;$i<count($myElement);$i++) {
				$item .= '<option value="'.$myElement[$i].'">'.$myElement[$i].'</option>';
			}
			$item .= '</select>';
			if (strlen($PCA['conf'][$field]['unhiddenListWrap'])>1) {
				$classname = $this->getClassTag($field,'textarea',$PCA,$em);
				$w = explode ('|',$PCA['conf'][$field]['unhiddenListWrap']);
				$c = strlen($PCA['conf'][$field]['unhiddenListClass'])>1 ? ' class="'.$PCA['conf'][$field]['unhiddenListClass'].'"' : '';
				// $item .= $w[0].'<input type="text"'.$c.' name="'.$PCA['name'].$this->formDataName.'['.$field.']" value="'.$row[$field].'" />'.$w[1];
				$item .= $w[0].'<textarea '.$classname.' name="'.$PCA['name'].$this->formDataName.'['.$field.']" ';
				//already in $classname!! //$item .= $this->getPAfieldParam($PCA,$field,'size',Array(' rows="','"')); //cols
				$item .= ' wrap="off">'.CRLF.
					str_replace(',',",\r\n",$row[$field]).'</textarea>'.$w[1];
			} else {
				$item .= '<input type="hidden" name="'.$PCA['name'].$this->formDataName.'['.$field.']" value="'.$row[$field].'" />';
			}
		} else if ($em>=SGZ_SEARCH) {
			$item = '[[NO_SEARCH_FOR_LISTS!]]';
		} else {
			$item = '';
		}
		return ($item ? $item : '');
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$field: ...
	 * @param	[type]		$classType: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$mode: ...
	 * @param	[type]		$submode: ...
	 * @return	[type]		...
	 */
	function getClassTag($field,$classType,$PCA,$mode=0,$submode='') {
		$out = '';
		$mySize = (isset($PCA['conf'][$field]['size'])) ? intval($PCA['conf'][$field]['size']) : 0;
		$myCols = (isset($PCA['conf'][$field]['cols'])) ? intval($PCA['conf'][$field]['cols']) : 0;
		$myRows = (isset($PCA['conf'][$field]['rows'])) ? intval($PCA['conf'][$field]['rows']) : $mySize;
		if ($myRows>$mySize) {
			$mySize = $myRows;
		}

		if (intval($PCA['conf'][$field]['maxitems'])==1) {
			if ($mode<SGZ_SEARCH && intval($PCA['conf'][$field]['buttonsMultiLine'])) {
				$mySize = 2;
				$myRows = 2;
			} else {
				$mySize = 1;
				$myRows = 1;
			}
		} else if ($mySize<3 && $myRows<3 && intval($PCA['conf'][$field]['maxitems'])>1 &&
			($classType=='image' || $classType=='select') && !intval($PCA['conf'][$key]['buttonsInSingleLine'])) {
			$mySize = 3;
			$myRows = 3;
		}

		if (intval($mode)>0) {
			if ($mode>=SGZ_SEARCH) {
				$out = $this->getClassModeTag($field,$classType,$PCA,'search',$submode);
			} else if ($mode>0) {
				$out = $this->getClassModeTag($field,$classType,$PCA,'form',$submode);
			} else {
				$out = $this->getClassModeTag($field,$classType,$PCA,'text',$submode);
			}
		}
		if (strlen($out)<1) {
			$out = $this->getClassModeTag($field,$classType,$PCA,'',$submode);
		}
		// 060403sg  // if ($classType!='select' && $classType!='textarea' && $mySize>0) { $out.=' size="'.$mySize.'"'; }
		if (($classType!='select' ||  $mode<SGZ_SEARCH) && $classType!='textarea' && $mySize>0) { $out.=' size="'.$mySize.'"'; }
		if ($myCols>0) { $out.=' cols="'.$myCols.'"'; }
		if ($mode<SGZ_SEARCH && $myRows>0) { $out.=' rows="'.$myRows.'"'; }
		//if ($field=='category') t3lib_div::debug(Array('$mySize'=>$mySize,'$myCols'=>$myCols,'$myRows'=>$myRows,'$out'=>$out, 'File:Line'=>__FILE__.':'.__LINE__));

		return ($out);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$field: ...
	 * @param	[type]		$classType: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$mode: ...
	 * @param	[type]		$submode: ...
	 * @return	[type]		...
	 */
	function getClassModeTag($field,$classType,$PCA,$mode='',$submode='') {
		$out = '';
		$mySize = (isset($PCA['conf'][$field]['size'])) ? intval($PCA['conf'][$field]['size']) : 0;
		$myCols = (isset($PCA['conf'][$field]['cols'])) ? intval($PCA['conf'][$field]['cols']) : 0;
		if (!$submode) {
			$submode = 'default';
		}
		if (isset($PCA['conf'][$field][$mode.'class'])) {
			$out = 'class="'.$PCA['conf'][$field][$mode.'class'].'"';
		} else if (is_array($PCA[$mode.'class'][$classType])) {
			$myWidth = ($mySize>$myCols) ? $mySize : $myCols ;
			$out = (isset($PCA[$mode.'class'][$classType][$submode])) ? 'class="'.$PCA[$mode.'class'][$classType][$submode].'"' : '';
			if ($myWidth>0 && isset($PCA[$mode.'class'][$classType]['max'])) {
				$out = 'class="'.$PCA[$mode.'class'][$classType]['max'].'"';
				$parts = $PCA[$mode.'class'][$classType];
				krsort($parts);
				for (reset($parts);$key=key($parts);next($parts)) {
					if (intval($key)>0 && $myWidth<=intval($key)) {
						$out = 'class="'.$PCA[$mode.'class'][$classType][$key].'"';
					}
				}
			}
		}
		return ($out);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$size: ...
	 * @param	[type]		$mode: ...
	 * @param	[type]		$cpt: ...
	 * @param	[type]		$url: ...
	 * @return	[type]		...
	 */
	function getImages ($table,$field,$row,$PCA,$size,$mode,$cpt='',$url='') {

		$out = '';
		//if (intval($PCA['image'][$size]['detail'])<1) {
			$imgWrap = explode ('|',$PCA['image'][$size]['imgWrap']);
			$capWrap = strlen($PCA['image'][$size]['capWrap'])>0 ? explode ('|',$PCA['image'][$size]['capWrap']) : $imgWrap;
			$allWrap = explode ('|',$PCA['image'][$size]['allWrap']);
			$firstWrap = strlen($PCA['image'][$size]['firstWrap'])>0 ? explode ('|',$PCA['image'][$size]['firstWrap']) : $allWrap;
			$tmpl = strlen($PCA['image'][$size]['tmpl'])>0 ? $PCA['image'][$size]['tmpl'] : ($mode==1 ? '###img###' : '###img######cap###');
			$tmplNoUrl = strlen($PCA['image'][$size]['tmplNoUrl'])>0 ? $PCA['image'][$size]['tmplNoUrl'] : $tmpl; //($mode==1 ? '###img###' : '###img######cap###');
			$defImages = strlen($PCA['image'][$size]['defaultImageList'])>0 ? explode(',',$PCA['image'][$size]['defaultImageList']) : Array();
			$altExtension = strlen($PCA['image'][$size]['alternativeView']['extension'])>0 ? explode(',',$PCA['image'][$size]['alternativeView']['extension']) : Array();
			$tryExtension = strlen($PCA['image'][$size]['alternativeView']['try'])>0 ? explode(',',$PCA['image'][$size]['alternativeView']['try']) : Array();
			$altPath = $PCA['image'][$size]['alternativeView']['path'];
			$placeHolder = $PCA['image'][$size]['placeHolder'];

			$imglist = (strlen($row[$field])>0) ? $imglist = t3lib_div::trimExplode (',',$row[$field]) : Array();
			$maxCount = count($imglist);
			if (count($defImages)>$maxcount) {
				$maxCount = count($defImages);
				for($i=0;$i<$maxCount;$i++) {
					if (!isset($imglist[$i])) {
						$imglist[$i] = $defImages[$i];
					}
				}
			}
			if (($tmp=intval($PCA['image'][$size]['maxCount']))>0) {
				for ($i=count($imglist);$i>$tmp;$i--) {
					unset($imglist[$i-1]);
				}
			}

			if (!is_array($cpt)) { $cpt = Array(); }
			if (!is_array($url)) { $url = Array(); }
			if ($maxCount>0) {
				if ($mode==1) {
					$imglist = Array ($imglist[0]);
				} else if ($mode==2) {
					unset ($imglist[0]);
					//unset ($cpt[0]);
					$imglist = array_merge (array(),(is_array($imglist)? $imglist : array()) );
				}

				// create image-views here
				for ($i=0;$i<count($imglist);$i++) {
					$imgPath = ($PCA['conf'][$field]['uploadfolder'] ? $PCA['conf'][$field]['uploadfolder'].'/' : '').$imglist[$i];
					$origImgPath = $imgPath;
					if (count($altExtension)) {
						$try = FALSE;
						for ($t=0;$t<count($altExtension);$t++) {
							if (strcmp(substr($imgPath,-(strlen($altExtension[$t]))),$altExtension[$t])==0) {
								$try = true;
								$imgPathTmp = substr($imgPath,0,-(strlen($altExtension[$t])));
							}
						}
						if ($try) {
							for ($t=0;$t<count($tryExtension) && $try;$t++) {
								if (file_exists($imgPathTmp.$tryExtension[$t])) {
									$imgPath = $imgPathTmp.$tryExtension[$t];
									$try = FALSE;
								}
							}
						}
						if ($try && strlen($altPath)) {
							$imgPathTmp = $altPath.strrchr ($imgPathTmp, "/");
							for ($t=0;$t<count($tryExtension) && $try;$t++) {
								if (file_exists($imgPathTmp.$tryExtension[$t])) {
									$imgPath = $imgPathTmp.$tryExtension[$t];
									$try = FALSE;
								}
							}
						}
					}
					if (is_array($placeHolder) && count($placeHolder)) {
						foreach ($placeHolder as $extname => $phImage) {
							if (strcasecmp('.'.$extname,substr($imgPath,-(1+strlen($extname))))==0) {
								$imgPath = $phImage;
							}
						}
					}
					$imglist[$i] = $imgPath;
					$conf = $PCA['image'][$size]['conf'];
					$conf['file'] = $imglist[$i];
					$tmpData = $this->lCObj->data;
					$this->lCObj->data = $row;
					$tmpImg = $this->lCObj->IMAGE($conf);
					$this->lCObj->data = $tmpData;
					if ($PCA['image'][$size]['linkToOriginal']) {
						$tmpImg = '<a href="/'.$origImgPath.'" target="_blank">'.$tmpImg.'</a>';
					}
					$tmpImg = str_replace('###uid###',$row['uid'],$imgWrap[0].$tmpImg.$imgWrap[1]);
					$tmpCap = strlen($cpt[($mode==2 ? $i+1 : $i)])>0 ? $capWrap[0].$cpt[($mode==2 ? $i+1 : $i)].$capWrap[1] : '';
					$tmpImg = str_replace('###cap###',strlen($cpt[($mode==2 ? $i+1 : $i)])>0 ? $cpt[($mode==2 ? $i+1 : $i)] : '',$tmpImg);
					$tmpUrl = '';
					$lineUrl = $url[($mode==2 ? $i+1 : $i)];
					$u = $this->myParseUrl($lineUrl);
					$tmpUrl = $u['total'];
					$imglist[$i] = str_replace('###img###',$tmpImg,str_replace('###cap###',$tmpCap,
						str_replace('###url###',$tmpUrl,str_replace('###orgurl###',$lineUrl,
						(strlen($lineUrl)>0 ? $tmpl:$tmplNoUrl) ))));
				}

				if (count($imglist)) {
					if ($mode==1) {
						$out = $firstWrap[0].implode($PCA['image'][$size]['implode'],$imglist).$firstWrap[1];
					} else {
						$out = $allWrap[0].implode($PCA['image'][$size]['implode'],$imglist).$allWrap[1];
					}
				}
			}
		//}
		if (!strlen(trim($out))) {
			$out = $PCA['image'][$size]['ifEmpty'];
		}
		return ($out);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$table: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$PCA: ...
	 * @return	[type]		...
	 */
	function getFileLinks ($table,$field,$row,$PCA) {
		$out = '';
		$tmp = '';
		if (strlen($row[$field])>1) {
			$filelist = t3lib_div::trimExplode (',',$row[$field]);
			$fileWrap = explode ('|',  ( $PCA['conf'][$field]['fileWrap'] ? $PCA['conf'][$field]['fileWrap'] : '|'  )  );
			$allWrap = explode ('|',  ( $PCA['conf'][$field]['allWrap'] ? $PCA['conf'][$field]['allWrap'] : '|<br />'  )  );
			$imploder = $PCA['conf'][$field]['implode'] ? $PCA['conf'][$field]['implode'] : '<br />';
			if ($PCA['conf'][$field]['pdflink'] && strlen($filelist[0])>2) {
				$tmp = '<input type="hidden" name="sg_zlib_pdflink" value="'.
						$PCA['conf'][$field]['uploadfolder'].'/'.$filelist[0].'" />';
			}
			for ($i=0;$i<count($filelist);$i++) {
				$myCaption = $filelist[$i];
				if ($PCA['conf'][$field]['captionfield']) {
					$myCaption = isset($row[$PCA['conf'][$field]['captionfield']])
						? $row[$PCA['conf'][$field]['captionfield']] : $PCA['conf'][$field]['captionfield'];
				}
				$fullFilePath = $PCA['conf'][$field]['uploadfolder'].'/'.$filelist[$i];
				if (!is_readable($fullFilePath) && $PCA['conf'][$field]['ifNotExists']) {
					$filelist[$i] = $fileWrap[0].str_replace
						('###file###',$filelist[$i],$PCA['conf'][$field]['ifNotExists']).$fileWrap[1];
				} else {
					if (is_array($PCA['conf'][$field]['preview'])) {
						$conf = $PCA['image'][$PCA['conf'][$field]['preview']['mode']]['conf'];
						$conf['file'] = $fullFilePath;
						$img = $this->lCObj->IMAGE($conf);
						if (isset($PCA['conf'][$field]['preview']['tmpl'])) {
							$myCaption = str_replace('###cap###',$myCaption,str_replace('###img###',$img,$PCA['conf'][$field]['preview']['tmpl']));
						} else {
							$myCaption = $img.'&nbsp;'.$myCaption;
						}
					}
					$tmpl = ($PCA['conf'][$field]['tmpl']) ? $PCA['conf'][$field]['tmpl'] : '<a target="_blank" href="###filepath###">###text###</a>';
					$s = Array('###filepath###'=>$fullFilePath, '###file###'=>$filelist[$i], '###text###'=>$myCaption);
					$filelist[$i] = $fileWrap[0].$this->lCObj->substituteMarkerArray($this->lCObj->substituteMarkerArray($tmpl,$this->constObj->getMarkers()),$s).$fileWrap[1];
				}
			}
			$out = $allWrap[0].implode($imploder,$filelist).$tmp.$allWrap[1];
		} else {
			$out = $PCA['conf'][$field]['ifempty'];
		}
		return ($out);
	}

	/******************************************************************************
	 *
	 * DB functions
	 *
	 *****************************************************************************/


	/**
	 * @param	[type]		$q: ...
	 * @param	[type]		$dbg: ...
	 * @return	[type]		...
	 */
	function getDbList ($q,$dbg=0) {
		$dbt = t3lib_div::trimExplode(',',$q['FROM']);
		$q['UIDonly'] = $q['UIDonly'] ? $q['UIDonly'] : (strlen($dbt[0])>2 ? $dbt[0].'.': '').'uid';
		$tmpOrder = ($q['doTotalList'] && $q['ORDER']) ? $q['ORDER'] : '';
		$q['FULL_QUERY'] = $GLOBALS['TYPO3_DB']->SELECTquery($q['SELECT'], $q['FROM'],$q['WHERE'],$q['GROUP'],$tmpOrder,$q['LIMIT']);
		$r = Array();
		$this->lastResultList = Array();
		//old//$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($q['SELECT'],$q['FROM'],$q['WHERE'],$q['GROUP'],'','');
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($q['UIDonly'], $q['FROM'],$q['WHERE'],$q['GROUP'],$tmpOrder,$q['LIMIT']);
		$myError = $GLOBALS['TYPO3_DB']->sql_error();
		if ($myError) {
			$r['ERROR'] = $myError;
			$r['total']=0;
			$r['cnt']=0;
			t3lib_div::debug(Array('$q'=>$q, '$myError'=>$myError, 'File:Line'=>__FILE__.':'.__LINE__));
		} else {
			$r['total'] = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
			$this->debugObj->debugIf('sql',Array('query'=>$q, 'res /  count'=>$res.' / '.$r['total'], 'FILE:LINE='=>__FILE__.':'.__LINE__ ));

			if ($q['doTotalList']){
				$nc = $q['doGetMaxList'];
				while ($row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
					$this->lastResultList[] = $row['uid'];
					$nc--;
					if ($nc<1) break;
				}
				if ($r['total']>0) {
					$GLOBALS['TYPO3_DB']->sql_data_seek($res,0);
				}
			}
			$r['maxPP'] = intval($q['maxPP']);
			$r['cnt'] = 0;
			$r['limit'] = '';
			$r['pg'] = intval($q['pg']);
			if ($r['total']>0) {
				if (intval($q['maxPP'])>0 && !$q['LIMIT']) {
					if ($r['total']>$q['maxPP']) {
						$r['maxPage'] = intval( intval($r['total']) / $r['maxPP'] - 0.000000001);
						$r['pg'] = ($r['pg']>$r['maxPage']) ? $r['maxPage'] : $r['pg'];
						$r['pg'] = ($r['pg']<0) ? 0 : $r['pg'];
						$r['start'] = $r['pg'] * $r['maxPP'];
						$r['limit'] = $r['start'].','.$r['maxPP'];
					}
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($q['SELECT'],$q['FROM'],$q['WHERE'],$q['GROUP'],$q['ORDER'],$r['limit']);
					$myError = $GLOBALS['TYPO3_DB']->sql_error();
					if ($myError) {
						$r['ERROR'] = $myError;
						$r['cnt']=0;
					} else {
						$this->debugObj->debugIf('sql',Array('query'=>$q, 'res /  count'=>$res.' / '.$GLOBALS['TYPO3_DB']->sql_num_rows($res),
								'FILE:LINE='=>__FILE__.':'.__LINE__ ));
						$cnt = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
						$r['cnt']=$cnt;
						$r['res']=$res;
					}
				} else {
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery
						($q['SELECT'],$q['FROM'],$q['WHERE'],$q['GROUP'],$q['ORDER'],$q['LIMIT']);
					$r['cnt']=$r['total'];
					$r['res']=$res;
				}
			}
		}

		if ($dbg) {
			t3lib_div::debug(Array('Query:'=>$q,'Result:'=>$r, 'File:Line'=>__FILE__.':'.__LINE__));
		}

		return ($r);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$r: ...
	 * @param	[type]		$markers: ...
	 * @param	[type]		$myPage: ...
	 * @param	[type]		$dbg: ...
	 * @param	[type]		$dbg: ...
	 * @param	[type]		$dbg: ...
	 * @return	[type]		...
	 */
	function getDbPages ($r,$markers=Array(),$myPage='',$pageId=0,$pConf=Array(),$dbg=0) {
		if (!is_array($pConf)) {
			$pConf = Array();
		}
		$u = $this->myparseURL(strlen($myPage)>1 ? $myPage : $this->myQuery);
		$u['params'] = $u['plist'];
		unset ($u['params']['x']);
		unset ($u['params']['y']);

		$tmp = $u['params'];
		$u['params'] = Array();
		if (is_array($tmp)) for (reset($tmp);$key=key($tmp);next($tmp)) {
			$u['params'][str_replace('=','%3D',$key)] = ($tmp[$key]);
		}

		$m = $this->getPageBrowser($r,$pageId,$u['params'],'pg',$pConf);

		if ($dbg) { t3lib_div::debug(Array('DbPages-Markers:'=>$m, 'File:Line'=>__FILE__.':'.__LINE__)); }
		$markers = array_merge ($markers,$m);
		return ($markers);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$r: ...
	 * @param	[type]		$pageId: ...
	 * @param	[type]		$params: ...
	 * @param	[type]		$pointer: ...
	 * @param	[type]		$pConf: ...
	 * @param	[type]		$idlist: ...
	 * @return	[type]		...
	 */
	function getPageBrowser ($r,$pageId,$params,$pointer='pg',$pConf=Array(),$idlist='') {
		if ($params[$this->lastPrefixId.'[searchmode]']==2) {
			$params[$this->lastPrefixId.'[searchmode]']=1;
		}
		$this->debugObj->debugIf('pageBrowser',Array('$r'=>$r, '$pageId'=>$pageId, '$params'=>$params, '$pConf'=>$pConf, 'backtrace(5)'=>$this->debugObj->shortBacktrace(5,1), 'File:Line'=>__FILE__.':'.__LINE__));
		$m = Array();
		unset ($params['resPP']);

		if (is_array($this->returnFromDetails)) {
			$m['###RETURNBUTTON###'] = $this->returnFromDetails['link'];
		} else {
			$m['###RETURNBUTTON###'] = '';
		}

		$divider = intval($pConf['divider']) ? intval($pConf['divider']) : 10;
		$around = intval($pConf['around']) ? intval($pConf['around']) : 4;
		$spacing = $pConf['spacing'] ? $pConf['spacing'] : ' ';
		$numSpacing = $pConf['numSpacing'] ? $pConf['numSpacing'] : ' ';

		$showResultsWrap = t3lib_div::trimExplode('|',$pConf['showResultsWrap']);
		$numbersWrap = t3lib_div::trimExplode('|',$pConf['numbersWrap']);
		$pagebrowserWrap = t3lib_div::trimExplode('|',$pConf['pagebrowserWrap']);

		$r['cnt'] = is_array($idlist) ? count($idlist) : $r['cnt'];
		$active = (is_array($idlist) ? array_search($r[$pointer],$idlist) : $r[$pointer]);

		$m ['###NEXTBUTTON###'] = $m ['###PREVBUTTON###'] = '';

		$m['###PAGES###']  = $pagebrowserWrap[0];
		$r['numlist'] = $r['prev'] = $r['next'] = $r['first'] = $r['last'] = '';
		if ($r['maxPage']>0 || $pConf['pageBrowserAlsoSingle']) {
			//first
			if ($pConf['showFirstLast']) {
				$myWrap = explode('|',$pConf['firstWrap'],2);
				$index = 0;
				$params[$pointer] = (is_array($idlist) ? $idlist[$index] : $index);
				$iParams = t3lib_div::implodeArrayForUrl('',$params,'',1,0);
				$url = $this->getTypolinkURL($pageId,$iParams,!$this->permitObj->useEditMode());
				if (isset($pConf['first'])) {
					$first = $this->lCObj->cObjGetSingle($pConf['first'],$pConf['first.']);
					$firstDis = ($pConf['firstDis']) ? $this->lCObj->cObjGetSingle($pConf['firstDis'],$pConf['firstDis.']) : $first;
				} else {
					$first = $firstDis ='&lt;&lt First';
				}
				$r['first'] = ($active>$index ? $myWrap[0].'<a href="'.$url.'">'.$first.'</a>'.$myWrap[1] : $firstDis);
				$m['###PAGES###'] .= $r['first'].$spacing.CRLF;
			}

			//prev
			$myWrap = explode('|',$pConf['prevWrap'],2);
			$index = $active-1;
			$params[$pointer] = (is_array($idlist) ? $idlist[$index] : $index);
			$iParams = t3lib_div::implodeArrayForUrl('',$params,'',1,0);
			$url = $this->getTypolinkURL($pageId,$iParams,!$this->permitObj->useEditMode());
			if (isset($pConf['prev'])) {
				$prev = $this->lCObj->cObjGetSingle($pConf['prev'],$pConf['prev.']);
				$prevDis = ($pConf['prevDis']) ? $this->lCObj->cObjGetSingle($pConf['prevDis'],$pConf['prevDis.']) : $prev;
			} else {
				$prev = $this->constObj->getButton('prev');
				$prevDis = $this->constObj->getButton('prevDis');
			}
			$r['prev'] = ($index>=0 ? $myWrap[0].'<a href="'.$url.'">'.$prev.'</a>'.$myWrap[1] : $prevDis);
			$m ['###PREVBUTTON###'] .= $r['prev'];
			$m ['###PAGES###'] .= $r['prev'].$spacing.CRLF;

			//numbers
			$r['numlist'] = $numbersWrap[0];
			for ($index=0;$index<=$r['maxPage'];$index++) {
				$params[$pointer] = (is_array($idlist) ? $idlist[$index] : $index);
				$iParams = t3lib_div::implodeArrayForUrl('',$params,'',1,0);
				$url = $this->getTypolinkURL($pageId,$iParams,!$this->permitObj->useEditMode());

				$r['numlist'] .= $index>0 ? $numSpacing : '';
				$text = ($pConf['showRange'] && $r['maxPP'])
					? ($index * $r['maxPP'] + 1).'-'.($index * $r['maxPP'] + $r['cnt'])  : $index+1;
				if ($index==$active) {
					$r['numlist'] .= '<b>'.$text.'</b>'.CRLF;
				} else {
					$r['numlist'] .= '<a href="'.$url.'">'.$text.'</a>'.CRLF;
				}
				if ($r['maxPage']>15 && $index<$r['maxPage']) {
					if (  ($index + intval($r['maxPage'] / $divider)) < ($active-$around-1) || $index>($active+$around-1) ) {
						$index = $index + intval($r['maxPage'] / $divider);
					} else if  ( $index < ($active-$around-1) ) {
						$index = $active-$around-1;
					}
					if ($index>=$r['maxPage']) {
						$index = $r['maxPage'] - 1;
					}
				}
			}
			$r['numlist'] .= $numbersWrap[1];
			$m['###NUMLIST###'] = $r['numlist'];
			$m['###PAGES###'] .= $r['numlist'];
			// next
			$myWrap = explode('|',$pConf['nextWrap'],2);
			$index = $active+1;
			$params[$pointer] = (is_array($idlist) ? $idlist[$index] : $index);
			$iParams = t3lib_div::implodeArrayForUrl('',$params,'',1,0);
			$url = $this->getTypolinkURL($pageId,$iParams,!$this->permitObj->useEditMode());
			if (isset($pConf['next'])) {
				$next = $this->lCObj->cObjGetSingle($pConf['next'],$pConf['next.']);
				$nextDis = ($pConf['nextDis']) ? $this->lCObj->cObjGetSingle($pConf['nextDis'],$pConf['nextDis.']) : $next;
			} else {
				$next = $this->constObj->getButton('next');
				$nextDis = $this->constObj->getButton('nextDis');
			}
			$r['next'] .= ($index<=$r['maxPage'] ? $myWrap[0].'<a href="'.$url.'">'.$next.'</a>'.$myWrap[1] : $nextDis);
			$m['###NEXTBUTTON###'] .= $r['next'];
			$m['###PAGES###'] .= $spacing.$r['next'].CRLF;

			// last
			if ($pConf['showFirstLast']) {
				$myWrap = explode('|',$pConf['lastWrap'],2);
				$index = $r['maxPage'];
				$params[$pointer] = (is_array($idlist) ? $idlist[$index] : $index);
				$iParams = t3lib_div::implodeArrayForUrl('',$params,'',1,0);
				$url = $this->getTypolinkURL($pageId,$iParams,!$this->permitObj->useEditMode());
				if (isset($pConf['last'])) {
					$last = $this->lCObj->cObjGetSingle($pConf['last'],$pConf['last.']);
					$lastDis = ($pConf['lastDis']) ? $this->lCObj->cObjGetSingle($pConf['lastDis'],$pConf['lastDis.']) : $last;
				} else {
					$last = $lastDis ='last &gt;&gt;';
				}
				$r['last'] .= ($active<$index ? $myWrap[0].'<a href="'.$url.'">'.$last.'</a>'.$myWrap[1]:$lastDis);
				$m['###PAGES###'] .= $spacing.$r['last'].CRLF;
			}
		}
		$m['###PAGES###']  .= $pagebrowserWrap[1];
		$r['pagebrowser'] = $m['###PAGES###'];
		$r['backlink'] = '';
		if ($pConf['backlink']) {
			$r['backlink'] = $this->lCObj->cObjGetSingle($pConf['backlink'],$pConf['backlink.']);
			if ($this->todo['BackUrl']) {
				$r['backlink'] = '<a href="'.$this->todo['BackUrl'].'">'.$r['backlink'].'</a>'.CRLF;
			}
		}

		$m ['###MYDESCR_ENTRIES###'] = '';
		$r['fromitem'] = $r['start']+1;
		$r['toitem'] = $r['start'] + $r['cnt'];
		$r['page'] = $active + 1; // // $r['pg'] + 1;
		$r['maxpages'] = $r['maxPage'] + 1;
		if ($pConf['countText'] && $r['cnt']>0) {
			$m ['###COUNT###'] = $this->lCObj->substituteMarkerArray($pConf['countText'], $r, '###|###');
		} else if ($pConf['countNone'] && $r['cnt']<1) {
			$m ['###COUNT###'] = $pConf['countNone'];
		} else {
			$m ['###COUNT###'] = $r['cnt']>0 ?
				sprintf($this->langObj->getLL('showFromToOfTotal'),$r['fromitem'],$r['toitem'],$r['total']) :
				sprintf($this->langObj->getLL('showNoneOfTotal'),$r['total']);
			$m ['###MYDESCR_ENTRIES###'] = $this->langObj->getLL('entries');
		}
		$m['###COUNT###'] = $showResultsWrap[0].$m['###COUNT###'].$showResultsWrap[1];

		if ($pConf['format']) {
			$m['###PAGES###'] = $this->lCObj->substituteMarkerArray($pConf['format'], $r, '###|###');
		}

		$m ['###GOTOPAGE###'] = '';
		if ($pConf['gotoPageAlsoSingle'] || $r['maxpages']>1) {
			$tmp = $pointer=='uid' ? Array('pUid'=>'', $pointer=>'') : Array($pointer=>'');
			$u = $this->myParseUrl('-',$tmp);
			$tmpName = 'Go!';
			if ($pConf['gotoPageButton']) {
				$tmpName = $this->lCObj->cobjGetSingle($pConf['gotoPageButton'],$pConf['gotoPageButton.']);
			}
			$wrap = t3lib_div::trimExplode('|',$pConf['gotoPageWrap']);
			$m ['###GOTOPAGE###'] = $wrap[0].'<input type="text" name="sg_gotopage" '.$pConf['gotoPageParams'].' value="" />'.CRLF.
				'<a href="'.$this->emptyUrl.'" onclick="sgGotoPage('.QT.$u['total'].'&'.
				($pointer=='uid' ? 'pUid' : $pointer).'='.QT.');return(false);">'.$tmpName.'</a>'.$wrap[1].CRLF;
		}

		$m ['###RESULTSPP###'] = '';
		if ($pConf['userResPP']) {
			$u = $this->myParseUrl('-',Array('resPP'=>''));
			$pp = t3lib_div::intExplode(',',$pConf['userResPP']);
			$wrap = t3lib_div::trimExplode('|',$pConf['userResPPwrap']);
			if (count($pp)>0) {
				// safari only accepts onchane here ...
				$m ['###RESULTSPP###'] =  $wrap[0].'<select onchange="window.location='.
						QT.$u['total'].'&resPP='.QT.'+this.options[this.options.selectedIndex].value;">';
				for ($i=0;$i<count($pp);$i++) {
					$m ['###RESULTSPP###'] .= ($this->confMaxPP==$pp[$i]) ?
							'<option selected="selected" value="'.$pp[$i].'">' :
							'<option value="'.$this->confMaxPP.','.$pp[$i].'">';
					$m ['###RESULTSPP###'] .= $pp[$i].'</option>';
					//$m ['###RESULTSPP###'] .= ' <a href="'.$u['total'].'&resPP='.$this->confMaxPP.','.$pp[$i].'">'.$pp[$i].'</a>' .CRLF;
				}
				$m ['###RESULTSPP###'] .=  '</select>'.$wrap[1];
			}
		}

		$m['###PAGEBROWSER###'] = '';
		if ($pConf['pageBrowser']) {
			if ($pConf['pageBrowserAlsoSingle'] || $r['maxpages']>1) {
				$tmp = $this->lCObj->cObjGetSingle($pConf['pageBrowser'],$pConf['pageBrowser.']);
				$wrap = t3lib_div::trimExplode('|',$pConf['pageBrowserWrap']);
				$m['###PAGEBROWSER###'] = $wrap[0].$this->lCObj->substituteMarkerArray($tmp,$m).$wrap[1];
			}
		}

		$this->debugObj->debugIf('pageBrowser',Array('return ($m)'=>$m, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($m);
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
	function getDbSearchFields ($dbName,$PCA,$piVarSearch,$markers=Array(),$dbg=0) {
		GLOBAL $TYPO3_DB,$TSFE;

		$m = Array();
		// get fields for search-form part1: search-queries and submit-button
		for (reset($PCA['search']);$key=key($PCA['search']);next($PCA['search'])) {
			if ($key=='submit') {
				if (intval($PCA['search'][$key]['linkmode'])>0) {
					$m['###SEARCH_SUBMIT###'] = '<a href="#null" onclick="document.search'.$this->sMode.'.submit(); return false">'.
							$this->constObj->getButton('search',$this->langObj->getLLL($PCA['search'][$key]['label'])).'</a>';
				} else if (intval($PCA['search'][$key]['imagemode'])>0 && $this->constObj->buttonExists('search')) {
					$m['###SEARCH_SUBMIT###'] = '<input type="image" src="'.
							$this->constObj->getButton('search',$this->langObj->getLLL($PCA['search'][$key]['label']),1).'" />';
				} else {
					$m['###SEARCH_SUBMIT###'] = '<input type="submit" value="'.$this->langObj->getLLL($PCA['search'][$key]['label']).'" />';
				}
			} else if ($key=='reset') {
				if (intval($PCA['search'][$key]['imagemode'])>0) {
					$m['###SEARCH_RESET###'] = '<a href="'.$this->emptyUrl.
						'" onclick="document.search'.$this->sMode.'.reset(); return false;">'.
						$this->constObj->getButton('reset',$this->langObj->getLLL($PCA['search'][$key]['label'])).'</a>';
				} else {
					$m['###SEARCH_RESET###'] = '<input type="reset" value="'.$this->langObj->getLLL($PCA['search'][$key]['label']).'" />';
				}
			} else if ($key=='clear') {
				$m['###SEARCH_CLEAR###'] = '<a href="'.$this->getTypolinkURL
					(0,'&'.$PCA['name'].'[searchmode]='.($PCA['search'][$key]['showall'] ? 1 : 128).'&type='.$TSFE->type).'">'.
					$this->constObj->getButton('clear',$this->langObj->getLLL($PCA['search'][$key]['label'])).'</a>';
			} else if ($key=='showall') {
				$m['###SEARCH_SHOWALL###'] = '<a href="'.$this->getTypolinkURL
					($this->listPage,'&'.$PCA['name'].'[searchmode]=1&type='.$TSFE->type).'">'.
					$this->constObj->getButton('showall',$this->langObj->getLLL($PCA['search'][$key]['label'])).'</a>';
			} else if ($key=='listmode') {
				$m['###DESCR_LISTMODE###'] = $this->langObj->getLLL($PCA['search'][$key]['label']);
				$m['###TEXT_LISTMODE###'] = $m['###DESCR_LISTMODE###'];
			} else if ($key=='disabled') {
				$disField = (isset($PCA['ctrl']['enablecolumns']['disabled'])) ? $PCA['ctrl']['enablecolumns']['disabled']: '';
				if ($disField) {
					$searchMode = (intval($PCA['search']['disabled']['type']>1)) ? SGZ_SEARCHUSED : SGZ_SEARCH ;
					$m['###SEARCH_'.strtoupper('disabled').'###'] = $this->getFeSingleField_SW($dbName,$disField,$piVarSearch,$searchMode,$PCA);
					$m['###TEXT_'.strtoupper('disabled').'###'] = $this->langObj->getLLL($PCA['search'][$key]['label']);
				}
			} else if ($key=='feowner') {
					if ($this->permitObj->allowed('admin') || !($this->permitObj->allowed('showOnlyOwnAndPublicEntries') || $this->permitObj->allowed('showOnlyOwnEntries'))) {
						// ok, allow selection of fe-owner
						$m['###SEARCH_FEOWNER###'] =
							$this->getFeSingleField_SW($dbName,$PCA['ctrl']['crfeuser_id'],$piVarSearch,SGZ_SEARCHUSED,$PCA);
					} else {
						// no, always restrict to this user
						$tmp = explode ('|',$PCA['search'][$key]['wrapText'],2);
						$m['###SEARCH_FEOWNER###'] =
							( intval($PCA['search'][$key]['hideAll'])<1 ? $tmp[0].$this->feUser['username'].$tmp[1] : '').
							'<input type="hidden" name="'.$PCA['name'].'[search]['.$PCA['ctrl']['crfeuser_id'].']" '.
								'value="'.$this->feUser['uid'].'" />';
					}
			} else if ($key=='abc' && is_array($PCA['search'][$key])) {
				$indexWrapFirst = t3lib_div::trimExplode('|',$PCA['search'][$key]['indexWrapFirst']);
				$indexWrapOther = t3lib_div::trimExplode('|',$PCA['search'][$key]['indexWrapOther']);
				$indexWrapHidden = t3lib_div::trimExplode('|',$PCA['search'][$key]['indexWrapHidden']);
				$indexWrapAllItem = t3lib_div::trimExplode('|',$PCA['search'][$key]['indexWrapAllItem']);
				$indexWrapOwnItem = t3lib_div::trimExplode('|',$PCA['search'][$key]['indexWrapOwn']);
				$indexWrapActiv = t3lib_div::trimExplode('|',$PCA['search'][$key]['indexWrapActiv']);
				$wrapAll = t3lib_div::trimExplode('|',$PCA['search'][$key]['wrapAll']);
				$directLink = $PCA['search'][$key]['directLink'];

				$hrefTpl = $this->emptyUrl;
				$oncTpl = ' onclick="sgAbcSubmit('.QT.'xxx-val-xxx'.QT.','.QT.$PCA['name'].QT.','.QT.$this->sMode.QT.",'abc'".');return false;"';
				if (is_array($directLink)) {
					$oncTpl = '';

					$myListPage = (intval($directLink['default'])>0 || strcmp($directLink['default'],'0')==0) ?
						intval($directLink['default']) : $this->listPage;

					$hrefTpl = $this->getTypolinkURL($myListPage,
							'&'.$PCA['name'].'[searchmode]=1&'.$PCA['name'].'[search]['.$key.']=xxx-val-xxx');
				}

				if (!$PCA['search'][$key]['linkEmptyAlso']) {
					//$select = 'left('.$PCA['search'][$key]['fieldAbc'].',1) AS chars, count(*) as cnt';
					$select = 'CONVERT(SUBSTRING('.$PCA['search'][$key]['fieldAbc'].',1,1) USING `ascii`) AS chars, count(*) as cnt';
					$query = 'pid IN ('.$PCA['pid_list'].') ';
					if (isset($PCA['ctrl']['delete'])) {
						$query .= ' AND '.$PCA['ctrl']['delete'].'=0';
					}
					if (isset($PCA['ctrl']['enablecolumns']['disabled'])) {
						$query .= ' AND '.$PCA['ctrl']['enablecolumns']['disabled'].'=0';
					}
					$group = 'chars';
					$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$dbName,$query,$group,'');
					if (!$res || $TYPO3_DB->sql_error()) {
						t3lib_div::debug(Array('$query'=>$query, '$res'=>$res, 'error'=>$TYPO3_DB->sql_error(),
							'File:Line'=>__FILE__.':'.__LINE__));
					} else {
						$this->debugObj->debugIf('abc', Array('$select'=>$select, '$dbName'=>$dbName, '$query'=>$query, '$res'=>$res, 'error'=>$TYPO3_DB->sql_error(),
							'File:Line'=>__FILE__.':'.__LINE__));
						$abcCount = Array();
						while ($cRow=$TYPO3_DB->sql_fetch_assoc($res)) {
							$c = $cRow['chars']<'A' ? '0' : strtolower($cRow['chars']);
							$abcCount[$c] += $cRow['cnt'];
						}
					}
				}

				$mTmp = $wrapAll[0];
				if ($PCA['search'][$key]['indexHasAllItem']) {
					$onc = str_replace ('xxx-val-xxx','all',$oncTpl);
					$href = str_replace ('xxx-val-xxx','all',$hrefTpl);
					$mTmp .= $indexWrapAllItem[0].'<a href="'.$href.'"'.$onc.'>'.
						$this->langObj->getLLL($PCA['search'][$key]['getAllLabel']).'</a>'.$indexWrapAllItem[1];
				}

				$abc=explode(',',$PCA['search'][$key]['index']);
				for ($i=0;$i<count($abc);$i++) {
					$link = true;
					$abcPart = explode('=',$abc[$i]);
					$part = (isset($abcPart[1]) ? $abcPart[1] : $abcPart[0]);
					$match = $part;
					if (is_array($abcCount)) {
						if (substr($part,0,1)=='0' && $abcCount['0']<1) {
							$link = false;
						} else {
							$link = false;
							for ($c=0;$c<strlen($part);$c++) {
								if ($abcCount[strtolower($part[$c])]>0) {
									$link = true;
								}
							}
						}
					}
					$onc = str_replace ('xxx-val-xxx',$part,$oncTpl);
					$href = str_replace ('xxx-val-xxx',$part,$hrefTpl);
					if ($link) {
						$part = '<a href="'.$href.'"'.$onc.'>'.$abcPart[0].'</a>';
					} else {
						$part = $abcPart[0];
					}

					if ($match==$piVarSearch[$key]) {
						$part = $indexWrapActiv[0].$part.$indexWrapActiv[1];
					}
					if ($i==0) {
						$mTmp .=  $indexWrapFirst[0].$part.$indexWrapFirst[1];
					} else {
						$mTmp .=  $indexWrapOther[0].$part.$indexWrapOther[1];
					}
				}

				if ($this->permitObj->useEditMode() && $PCA['search'][$key]['indexHasHiddenItem']) {
					$onc = str_replace ('xxx-val-xxx','hidden',$oncTpl);
					$href = str_replace ('xxx-val-xxx','hidden',$hrefTpl);
					$mTmp .= $indexWrapHidden[0].'<a href="'.$href.'"'.$onc.'>'.
						$this->langObj->getLLL($PCA['search'][$key]['getHiddenLabel']).'</a>'.$indexWrapHidden[1];
				}

				if ($PCA['search'][$key]['indexHasOwnItem']) {
					$onc = str_replace ('xxx-val-xxx','own',$oncTpl);
					$href = str_replace ('xxx-val-xxx','own',$hrefTpl);
					$mTmp .= $indexWrapOwnItem[0].'<a href="'.$href.'"'.$onc.'>'.
						$this->langObj->getLLL($PCA['search'][$key]['getOwnLabel']).'</a>'.$indexWrapOwnItem[1];
				}

				$mTmp .= '<input type="hidden"  name="'.$PCA['name'].'[search][abc]" value="" />';
				$mTmp .= $wrapAll[1];

				$m['###SEARCH_'.strtoupper($key).'###'] = $mTmp;
			} else if (isset($PCA['conf'][$key]) || $key=='uid') {
				$searchMode = (intval($PCA['search'][$key]['type']>1)) ? SGZ_SEARCHUSED : SGZ_SEARCH ;
				$tmp = $this->getFeSingleField_SW($dbName,$key,$piVarSearch,$searchMode,$PCA);
				$tmp = $this->doXajaxFieldWrap($tmp,'xajax_id_'.$key);
				$m['###SEARCH_'.strtoupper($key).'###'] = $tmp;
			} else if (strcmp($PCA['search'][$key]['mode'],'usedselector')==0) {
				$ret = '---TEST---<br />';
				if (is_array($PCA['search'][$key]['list'])) {
					$fNames = Array();
					for (reset($PCA['search'][$key]['list']);$fKey=key($PCA['search'][$key]['list']);next($PCA['search'][$key]['list'])) {
						$fieldName = $PCA['search'][$key]['list'][$fKey]['field'];
						$fNames[] = $fieldName;
						$counters[$fieldName] = Array('total'=>0);

						$select = 'COUNT(*) as myCount,'.implode(',',$fNames);
						$query = '1=1';
						$group = implode(',',$fNames.$this->mSqlConf['addToGroup']);
						$order = implode(',',$fNames.$this->mSqlConf['addToOrder']);
						if (count($fNames)>1) {
						}
						$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$dbName,$query,$group,$order);
						if (!$res || $TYPO3_DB->sql_error()) {
							t3lib_div::debug(Array('$select'=>$select, '$dbName'=>$dbName, '$query'=>$query, '$res'=>$res,
								'error'=>$TYPO3_DB->sql_error(), 'File:Line'=>__FILE__.':'.__LINE__));
						}
						while ($cRow=$TYPO3_DB->sql_fetch_assoc($res)) {
							//t3lib_div::debug(Array('$cRow'=>$cRow, 'File:Line'=>__FILE__.':'.__LINE__));
							for ($sc=0;$sc<count($fNames);$sc++) {
								$saveName = t3lib_div::intExplode(',',$cRow[$fNames[$sc]]);
								for ($sci=0;$sci<count($saveName);$sci++) {
									$counters[$fNames[$sc]][$saveName[$sci]]['count'] += $cRow['myCount'];
									$counters[$fNames[$sc]][$saveName[$sci]]['where'] = $fNames[$sc].'='.$saveName[$sci];
								}
							}

						}

					break;
					}
				}
				$m['###SEARCH_'.strtoupper($key).'###'] = $ret.'<br />----------<br />';
			} else if (strcmp($PCA['search'][$key]['mode'],'selector')==0) {
				$directLink = $PCA['search'][$key]['directLink'];
				if (intval($PCA['search'][$key]['type'])>1) {
					$tEm = SGZ_SEARCHALL;
				} else {
					$tEm = SGZ_SEARCHUSED;
				}
				$myField = $PCA['search'][$key]['field'];
				$mySubField = $PCA['search'][$key]['subField'];
				if (isset($PCA['conf'][$myField])) {
					$myImplode = $PCA['search'][$key]['imploder'];
					$mySubImplode = $PCA['search'][$key]['subImploder'];
					$myWrap = explode('|',$PCA['search'][$key]['wrap'],2);
					$mySubWrap = explode('|',$PCA['search'][$key]['subWrap'],2);
					$myWrapAll = explode('|',$PCA['search'][$key]['wrapAll'],2);
					$mySubWrapAll = explode('|',$PCA['search'][$key]['subWrapAll'],2);
					$this->itemsObj->prepareItems($dbName,$myField,$tEm,Array());
					$myItems = $this->itemsObj->getItemList($dbName,$myField,$tEm);

					if ($PCA['search'][$key]['cntOrder']) {
						$mc = $this->itemsObj->getItemCountAdd('',$myField);
						unset($mc['0.']);
						$max = $PCA['search'][$key]['maxList']>2 ? $PCA['search'][$key]['maxList'] : 10;
						if ($PCA['search'][$key]['cntOrder']>0) {
							arsort ($mc);
						} else {
							asort ($mc);
						}
						$old = $myItems;
						$myItems = Array();
						for (reset($mc);$mcKey=key($mc);next($mc)) if ($max>0) {
							$myItems[$mcKey] = $old[$mcKey];
							$max--;
						}
					} else if ($PCA['search'][$key]['alphaOrder']) {
						asort ($myItems);
					}

					$myLinks = Array();
					$lnr = 0;
					for (reset($myItems);$iKey=key($myItems);next($myItems)) if (intval($iKey)) {
						$lnr++;
						$itemText = $this->itemsObj->getItemText('search',$lnr,$myField,$myItems,$iKey,$PCA,$key);
						$hrefM = $this->emptyUrl;
						$oncM = ' onclick="sgAbcSubmit('.
								QT.intval($iKey).QT.','.QT.$PCA['name'].QT.','.QT.$this->sMode.QT.",'".$myField."'".');return false;"';
						if (is_array($directLink)) {
							$oncM = '';
							$myListPage = (intval($directLink['default'])>0 || strcmp($directLink['default'],'0')==0) ?
								intval($directLink['default']) : $this->listPage;
							$myListPage = (intval($directLink[$this->itemsObj->getItemsPid('',$myField,$iKey)])>0) ?
								intval($directLink[$this->itemsObj->getItemsPid('',$myField,$iKey)]) : $myListPage;

							$hrefM = $this->getTypolinkURL($myListPage,
									'&'.$PCA['name'].'[searchmode]=1&'.$PCA['name'].'[search]['.$myField.']='.intval($iKey));
						}
						if ($mySubField) {
							$this->itemsObj->prepareItems($dbName,$mySubField,$tEm,Array($myField=>$iKey));
							$subItems = $this->itemsObj->getItemList($dbName,$mySubField,$tEm);
							$mySubLinks = Array();
							$lnr2 = 0;
							for (reset($subItems);$isKey=key($subItems);next($subItems)) if (intval($isKey)) {
								$lnr2++;
								$itemSubText = $this->itemsObj->getItemText
										('search',$lnr2,$mySubField,$subItems,$isKey,$PCA,$key,'textSubFormat','cntSubWrap');
								$href = $this->emptyUrl;
								$onc = ' onclick="sgAbcSubmit('.
									QT.intval($isKey).QT.','.QT.$PCA['name'].QT.','.QT.$this->sMode.QT.",'".$mySubField."'".');return false;"';

								if (is_array($directLink)) {
									$onc = '';
									$myListPage = (intval($directLink['default'])>0 || strcmp($directLink['default'],'0')==0) ?
										intval($directLink['default']) : $this->listPage;
									$myListPage = (intval($directLink[$this->itemsObj->getItemsPid('',$mySubField,$isKey)])>0) ?
										intval($directLink[$this->itemsObj->getItemsPid('',$mySubField,$isKey)]) : $myListPage;

									$href = $this->getTypolinkURL($myListPage,
											'&'.$PCA['name'].'[searchmode]=1&'.$PCA['name'].'[search]['.$mySubField.']='.intval($isKey));
								}
								$mySubLinks[] = $mySubWrap[0].'<a href="'.$href.'"'.$onc.'>'.$itemSubText.'</a>'.$mySubWrap[1];
							}
							$tmp = $mySubWrapAll[0].implode($mySubImplode,$mySubLinks).$mySubWrapAll[1];
							if ($PCA['search'][$key]['onlySubLink']) {
								$myLinks[] = $myWrap[0].$itemText.$myWrap[1].$tmp;
							} else {
								$myLinks[] = $myWrap[0].'<a href="'.$hrefM.'"'.$oncM.'>'.$itemText.'</a>'.$myWrap[1].$tmp;
							}
						} else {
							$myLinks[] = $myWrap[0].'<a href="'.$hrefM.'"'.$oncM.'>'.$itemText.'</a>'.$myWrap[1];
						}
					}
				$m['###SEARCH_'.strtoupper($key).'###'] = $myWrapAll[0].implode($myImplode,$myLinks).
					'<input type="hidden"  name="'.$PCA['name'].'[search]['.$myField.']" value="" />'.
					($mySubField? '<input type="hidden"  name="'.$PCA['name'].'[search]['.$mySubField.']" value="" />' : '').
					$myWrapAll[1];

				//t3lib_div::debug(Array('Key '.$key=>$PCA['search'][$key], '$myItems'=>$myItems, '$m[###SEARCH_'.strtoupper($key).'###]'=>$m['###SEARCH_'.strtoupper($key).'###'], 'File:Line'=>__FILE__.':'.__LINE__));
				}
			}
		}

		// get fields for search-form part2: listmode
		if (is_array($PCA['listmode'])) {
			if (!isset($piVarSearch['listmode'])) {
				if (isset($PCA['ctrl']['defaultListmode'])) {
					$piVarSearch['listmode'] = $PCA['ctrl']['defaultListmode'];
				} else {
					$piVarSearch['listmode'] = 'default';
				}
			}
			$classText = $this->getClassTag('listmode','listmode',$PCA,SGZ_SEARCH);

			$m['###SEARCH_LISTMODE###'] = '<select '.$classText.'name="'.$PCA['name'].'[search][listmode]"]>';
			for (reset($PCA['listmode']);$lmKey=key($PCA['listmode']);next($PCA['listmode'])) {
				$m['###SEARCH_LISTMODE###'] .= '<option '.
					( strcmp($piVarSearch['listmode'],$lmKey)==0 ? 'selected="selected" ' : '' ).
					'value="'.$lmKey.'">'.$PCA['listmode'][$lmKey]['name'].'</option>';
			}
			$m['###SEARCH_LISTMODE###'] .= '</select>';
		}

		if ($dbg) { t3lib_div::debug(Array('DbSearch-Markers:'=>$m, 'File:Line'=>__FILE__.':'.__LINE__)); }

		$markers = array_merge ($markers,$m);
		return ($markers);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$dbName: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$piVarSearch: ...
	 * @param	[type]		$qa: ...
	 * @param	[type]		$r: ...
	 * @return	[type]		...
	 */
	function getDbExportSection ($dbName,$PCA,$piVarSearch=Array(),$qa=Array(),$r=Array()) {
		$ex = '';
		$classText = $this->getClassTag('exportmode','exportmode',$PCA,SGZ_SEARCH);

		$ex .= '<form name="sg_exportform" method="POST">';
		//t3lib_div::debug(Array('$PCA[export]'=>$PCA['export'], 'File:Line'=>__FILE__.':'.__LINE__));
		if (is_array($PCA['export']['modes'])) {
			$qa['modes'] = $PCA['export']['modes'];
			$ex .=  '<select '.$classText.'name="exportmode">';
			for (reset($PCA['export']['modes']);$exKey=key($PCA['export']['modes']);next($PCA['export']['modes'])) {
				$ex .= '<option value="'.urlencode($exKey).'">'.$PCA['export']['modes'][$exKey]['label'].'</option>';
			}
			$ex .= '</select>';
		}
		$ex .= '<input type="hidden" name="qa" value="'.urlencode(serialize($qa)).'" />';

		$md5 = $this->getMd5Link($qa);

		$bText = $this->langObj->getLLL($PCA['export']['submit']['label']);
		$bText = str_replace ('###cnt###',$r['total'],($bText ? $bText : $this->langObj->getLLL('LLL:EXT:sg_zlib/locallang.xml:list.export')));
		$bCode = $this->constObj->getButton('export',$bText);
		$ex .=  ('<a href="'.$this->emptyUrl.'" onclick="exportDataParaSg('.QT.$this->exporter.QT.','.QT.urlencode($md5).QT.'); return false;">'.$bCode.'</a>');

		$ex .= '</form>';

		return ($ex);
	}

	function getDeleteSection ($dbName,$PCA,$piVarSearch=Array(),$qa=Array(),$r=Array()) {
		$contentPart = '';
		$qa['deleteMode'] = 'delete';
		$md5 = $this->getMd5Link($qa);
	
		if ($this->permitObj->allowed('deleteList')) {
			$bText = $this->langObj->getLLL($PCA['delete']['submit']['label']);
			$bText = str_replace ('###cnt###',$r['total'],($bText ? $bText : $this->langObj->getLLL('LLL:EXT:sg_zlib/locallang.xml:list.delete')));
			$bCode = $this->constObj->getButton('delete',$bText);
			$jsAlert = $this->getJsAlert($PCA['deleteList']['jsAlert'],$md5,'delete');

			$contentPart = '<a href="'.$this->emptyUrl.'" '.$jsAlert.'>'.$bCode.'</a>';
		}
		
		return ($contentPart);
	}

	function getUndoDeleteSection ($dbName,$PCA,$piVarSearch=Array(),$qa=Array(),$r=Array()) {
		$contentPart = '';
		$qa['deleteMode'] = 'undelete';
		$md5 = $this->getMd5Link($qa);
	
		if ($this->permitObj->allowed('deleteList')) {
			$bText = $this->langObj->getLLL($PCA['undoDelete']['submit']['label']);
			$bText = str_replace ('###cnt###',$r['total'],($bText ? $bText : $this->langObj->getLLL('LLL:EXT:sg_zlib/locallang.xml:list.undelete')));
			$bCode = $this->constObj->getButton('undelete',$bText);
			$jsAlert = $this->getJsAlert($PCA['undoDelete']['jsAlert'],$md5,'undelete');

			$contentPart = '<a href="'.$this->emptyUrl.'" '.$jsAlert.'>'.$bCode.'</a>';
		}
	
		return ($contentPart);
	}

	function getMd5Link($qa) {
		$data = serialize($qa);
		$md5 = substr(md5($data),0,20);
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('md5hash', 'cache_md5params', 'md5hash='.$GLOBALS['TYPO3_DB']->fullQuoteStr($md5, 'cache_md5params'));
		if (!$GLOBALS['TYPO3_DB']->sql_num_rows($res))	{
			$insertFields = array(
				'md5hash' => $md5,
				'tstamp' => time(),
				'type' => 9,
				'params' => $data
			);
			$GLOBALS['TYPO3_DB']->exec_INSERTquery('cache_md5params', $insertFields);
		}
		return ($md5);
	}

	function getJsAlert ($jsConf,$md5,$mode) {
		$jsAlert = '';

		if (!$jsConf['suppress']) {
			$msg = $this->langObj->getLLL($jsConf['label']);
			$msg = $msg ? $msg : $this->langObj->getLLL('LLL:EXT:sg_zlib/locallang.xml:areyousure');
			
			$jsAlert = 'onclick="deleteDataParaSg('.QT.$this->deleter.QT.','.QT.urlencode($md5).QT.','.QT.$msg.QT.','.QT.urlencode($mode).QT.'); return(false);"';
		}
		// t3lib_div::debug(Array('$jsAlert'=>$jsAlert, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($jsAlert);
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
	function getDbBuildQuery ($dbName,$PCA,$piVarSearch,$markers=Array(),$dbg=0) {
		$q = Array();
		if (!isset($piVarSearch['listmode'])) {
			if (isset($PCA['ctrl']['defaultListmode'])) {
				$piVarSearch['listmode'] = $PCA['ctrl']['defaultListmode'];
			} else {
				$piVarSearch['listmode'] = 'default';
			}
		}

		// first: always set default where-clause (set in TS) (if any)
		if (isset($PCA['ctrl']['defaultWhere'])) {
			$q['defaultWhere'] = $this->replaceArray($this->lCObj->insertData(
				str_replace('###val###',trim($PCA['ctrl']['defaultWhereVal']),
				   str_replace('###feuser_id###',$this->permitObj->getFeUid(),$PCA['ctrl']['defaultWhere']))
				) , $this->globalReplace);
		}

		// second: always set default where-clause from listmode, set in TS (if any)
		if (isset($PCA['listmode'][$piVarSearch['listmode']])) {
			if (isset($PCA['listmode'][$piVarSearch['listmode']]['where'])) {
				$q['listmode'] = $this->lCObj->insertData($PCA['listmode'][$piVarSearch['listmode']]['where']);
			}
		}


		// third: set all defined queries
		if (is_array($piVarSearch)) for (reset($piVarSearch);$key=key($piVarSearch);next($piVarSearch)) {
			if (strcmp($key,'abc')==0) {
				if (strcasecmp($piVarSearch[$key],'hidden')==0) {
					// select all hidden
					$piVarSearch[$key] = 'all';
					$piVarSearch['hidden'] = '1';
				}
			}

		}
		$c1 = 0;
		if (is_array($piVarSearch)) for (reset($piVarSearch);$key=key($piVarSearch);next($piVarSearch)) {
			if (strcmp($key,'listmode')==0) {
			} else if (strcmp($key,$PCA['ctrl']['enablecolumns']['disabled'])==0) {
			} else if (strncmp($key,'restrict_',9)==0) {
				if (strlen($piVarSearch[$key])>0) {
					$q['restrict'] = $dbName.'.'.substr($key,9).' IN ('.$GLOBALS['TYPO3_DB']->quoteStr($piVarSearch[$key],$dbName).')';
				} else {
					$q['restrict'] = '1=2 ';
				}
			} else if (strcmp($key,'idlist')==0) {
				if (strlen($piVarSearch[$key])>0) {
					$q['idlist'] = $dbName.'.uid IN ('.$GLOBALS['TYPO3_DB']->quoteStr($piVarSearch[$key],$dbName).')';
				} else {
					$q['idlist'] = '1=2 ';
				}
			} else if (strcmp($key,'abc')==0) {
				$myRange = $GLOBALS['TYPO3_DB']->quoteStr($piVarSearch[$key],$dbName);
				$myTextRange = '';
				if (strcmp(subStr($myRange,0,1),"0")==0) {
					$myTextRange = '0-9';
					//$query = 'SELECT * FROM tx_sgglossary_entries WHERE hidden=0 AND deleted=0 AND word REGEXP '."'^[^[:alpha:]]'".' ';
					$myRange = ($PCA['search'][$key]['nonZeroChars']) ?
							$PCA['search'][$key]['nonZeroChars'].'#' : 'abcdefghijklmnopqrstuvwxyzäöü';
					if ($PCA['search'][$key]['likeMode']) {
						$sq = Array();
						for ($imr=0;$imr<strlen($myRange);$imr++) {
							$sq[] = ' '.$PCA['search'][$key]['fieldAbc'].' NOT LIKE '."'".$myRange[$imr]."%'".' ';
						}
						$q['abc'] = '('.implode(' AND ',$sq).')';
					} else {
						$q['abc'] = ' '.$PCA['search'][$key]['fieldAbc'].' REGEXP '."'^[^".$myRange."]'".' ';
					}
				} else if (strcasecmp($myRange,'all')==0) {
					$myTextRange = $this->langObj->getLLL($PCA['search'][$key]['getAllLabel']);
					$c1 = 1; //get all !!
				} else if (strcasecmp($myRange,'own')==0) {
					//get all own !!
					$myTextRange = $this->langObj->getLLL($PCA['search'][$key]['getOwnLabel']);
					if (strlen($PCA['ctrl']['crfeuser_id'])>0) {
						$q['abc'] = ' '.$PCA['ctrl']['crfeuser_id'].'='.intval($this->permitObj->getFeUid()).' ';
					} else {
						$q['abc'] = ' 1=2 ';
					}
				} else if (strcasecmp($myRange,'hidden')==0) {
					$myTextRange = $this->langObj->getLLL($PCA['search'][$key]['getHiddenLabel']);
					// select all hidden
				} else if (strlen($myRange)>0) {
					if ($PCA['search'][$key]['likeMode']) {
						$sq = Array();
						for ($imr=0;$imr<strlen($myRange);$imr++) {
							$sq[] = ' '.$PCA['search'][$key]['fieldAbc'].' LIKE '."'".$myRange[$imr]."%'".' ';
						}
						$q['abc'] = '('.implode(' OR ',$sq).')';
					} else {
						$q['abc'] = ' '.$PCA['search'][$key]['fieldAbc'].' REGEXP '."'^[".$myRange."]'".' ';
					}
					$tmp = t3lib_div::trimExplode(',',$PCA['search'][$key]['index']);
					for ($t=0;$t<count($tmp);$t++) {
						$ttmp = explode('=',$tmp[$t],2);
						if (count($ttmp)>0 && strcmp($ttmp[count($ttmp)-1],$myRange)==0) {
							$myTextRange = $ttmp[0];
						}
					}
				}
				$this->lastAbcKey = $key;
				$this->lastAbcRange = $myRange;
				$this->lastAbcRangeText = $myTextRange;
			} else {
				// Warning : $piVarSearch[$key] may be an array !!!
				$doit = true;
				$def = $PCA['search'][$key];
				if (is_array($piVarSearch[$key])) {
					$tmp = Array();
					while (list ($sKey, $val) = each ($piVarSearch[$key])) {
					//no! this doesnt work with index 0,1,2) for (reset($piVarSearch[$key]);...;next($piVarSearch[$key])) {
						//$text = urldecode(trim($val));
						$text = $GLOBALS['TYPO3_DB']->quoteStr(trim($val),$dbName);
						$tmp[] =  '( '.implode(' AND ',$this->getDbBuildSingleQuery($dbName,$PCA,$key,$def,$text)).' )';
					}
					if (count($tmp)) {
						$q['searches_'.$key] = '( '.implode(' OR ',$tmp).' )';
					}
				} else {
					$text = $GLOBALS['TYPO3_DB']->quoteStr(trim($piVarSearch[$key]),$dbName);
					if (intval($def['searchZero'])>0) {
						if (strcmp($text,'-1')==0) { $doit=FALSE; }
					} else {
						if (strcmp($text,'0')==0) { $doit=FALSE; }
					}
					if ($doit) {
						if (strlen($piVarSearch[$key])>0) {
							$q = array_merge ($q, $this->getDbBuildSingleQuery($dbName,$PCA,$key,$def,($text=="''" ? '' : $text)));
						}
					}
				}
			}
		}

		$this->searchParamCount = count($q)+$c1;
		$q = $this->getDbEnableColumns ($dbName,$PCA,$piVarSearch,$q);
		$this->lastGetDbBuildQuery = $q;
		// t3lib_div::debug(Array('$q'=>$q, 'File:Line'=>__FILE__.':'.__LINE__));
		return (implode(' AND ',$q));
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$dbName: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$key: ...
	 * @param	[type]		$def: ...
	 * @param	[type]		$text: ...
	 * @return	[type]		...
	 */
	function getDbBuildSingleQuery ($dbName,$PCA,$key,$def,$text) {
		$q = Array();
		$specialMatch = false;
		if (is_array($def['special'])) {
			for (reset($def['special']);$sKey=key($def['special']);next($def['special'])) {
				if (strcmp($def['special'][$sKey]['value'],$text)==0) {
					$q['special_'.$key] = $this->lCObj->insertData(
						str_replace('###time###',time(),$def['special'][$sKey]['query']));
					$specialMatch = true;
				}
			}
		}

		if (!$specialMatch) {
			if (is_array($PCA['conf'][$key]['foreign']) && strlen($PCA['conf'][$key]['foreign_table'])<1) {
				$myField = $PCA['conf'][$key]['foreign']['field'] ? $PCA['conf'][$key]['foreign']['field'] : $key;
				$ini = '(';
				if (strlen($PCA['conf'][$key]['foreign']['where'])>0) {
					$ini = '('.$this->lCObj->insertData($PCA['conf'][$key]['foreign']['where']).' AND ';
				}
				if (strlen($text)>0 && strcmp($PCA['conf'][$key]['foreign']['mode'],'text')==0) {
					$myQ = $ini.$myField.' LIKE '.QT.addslashes(str_replace('*','%',$text)).QT.')';
				} else if ((strcasecmp($text,'null')==0) || intval($text)>0) {
					if (strcmp($PCA['conf'][$key]['mode'],'selectmulti')==0) {
						$ini.$myField.' IN ( '.addslashes($text).' ) '.')';
					} else {
						$myQ = $ini.$myField.( (strcasecmp($text,'null')==0) ? ' is NULL' : '='.intval($text)).')';
					}
				}
				$q['foreign_'.$key] = $myQ;
				//t3lib_div::debug(Array($key=>$PCA['conf'][$key], '$myQ'=>$myQ, 'File:Line'=>__FILE__.':'.__LINE__));
			} else if (strlen($PCA['conf'][$key]['type'])<1 || $PCA['conf'][$key]['type']=='input' || $PCA['conf'][$key]['type']=='none' || $PCA['conf'][$key]['type']=='text') {
				// Field seems to be a text-type field
				$fieldList = explode(',',  (isset($def['fields'])) ? $def['fields'] : $dbName.'.'.$key ) ;
					for ($i=0;$i<count($fieldList);$i++) {
					if (strpos($fieldList[$i],'.')<1) {
						$fieldList[$i] = $dbName.'.'.$fieldList[$i];
					}
				}

				if (isset($def['query'])) {
					$this->globalReplace['###val###'] = $text;
					$fieldList[0] = $this->replaceArray($this->lCObj->insertData($def['query']),$this->globalReplace);
					$fieldList[0] = str_replace('*','%',$fieldPrepend.$fieldList[0].$fieldAppend);
					$q['inputq_'.$key] = $fieldList[0];
				} else {
					// check if range is given
					$p = explode ('...',$text,2);
					if (count($p)>1) {
						for ($i=0;$i<count($fieldList);$i++) {
							$fieldList[$i] = ' ('.$fieldList[$i].' >= "'.$p[0].'" '.
											 ' AND '.$fieldList[$i].' <= "'.$p[1].'") ';
						}
						$q['inputs_'.$key] = '('.implode($fieldMode,$fieldList).')';
					} else {
						$fieldComp =   (isset($def['comp'])) ? $def['comp'] : 'LIKE'   ;
						$fieldMode =  (isset($def['mode'])) ? $def['mode'] : 'OR'  ;
						$fieldAppend =  (isset($def['append'])) ? $def['append'] : ''  ;
						$fieldPrepend =  (isset($def['prepend'])) ? $def['prepend'] : ''  ;
						for ($i=0;$i<count($fieldList);$i++) {
							$fieldList[$i] = ' '.$fieldList[$i].' '.$fieldComp.
												' "'.str_replace('*','%',$fieldPrepend.$text.$fieldAppend).'" ';
						}
						$q['input_'.$key] = '('.implode($fieldMode,$fieldList).')';
					}
				}
			} else if (substr($PCA['conf'][$key]['type'],0,4)=='date') {
				// Field is of type 'date'
				$fieldList = explode(',',  (isset($def['fields'])) ? $def['fields'] : $dbName.'.'.$key   ) ;
				$fieldComp =   (isset($def['comp'])) ? $def['comp'] : '='   ;
				$fieldMode =  (isset($def['mode'])) ? $def['mode'] : 'OR'  ;
				if (isset($def['query'])) {
					$this->globalReplace['###val###'] = $text;
					$fieldList[0] = $this->replaceArray($this->lCObj->insertData($def['query']),$this->globalReplace);
					$fieldList[0] = str_replace('*','%',$fieldPrepend.$fieldList[0].$fieldAppend);
				} else {
					if (strcmp($text,'0')==0 || strcmp($text,'-')==0) {
						$fieldList[0] = ' '.$fieldList[0].'=0 ';
					} else {
						$fieldList[0] = ' ('.$this->dateCompareString($text,$fieldList[0]).') ';
					}
				}
				//t3lib_div::debug(Array('$myComp'=>$myComp, '$fieldList[0]'=>$fieldList[0], 'File:Line'=>__FILE__.':'.__LINE__));
				$q['date_'.$key] = $fieldList[0];
			} else if ($PCA['conf'][$key]['MM']) {
				$q['MM_'.$key] = ' ( '.$PCA['conf'][$key]['MM'].'.uid_foreign='.intval($text).') ';
				if ($PCA['search'][$key]['foreign']['subSearch']) {
					$this->itemsObj->prepareItems($dbName,$key,0,Array());
					if ($tmp = $this->itemsObj->getItemsSub('',$key,intval($text).'.')) {
						$q['MM_'.$key] = '('.$q['MM_'.$key].' OR '.$PCA['conf'][$key]['MM'].'.uid_foreign IN ('.$tmp.'))';
					}
				}
			} else if ($PCA['conf'][$key]['type']=='select' ||
					$PCA['conf'][$key]['type']=='radio' || $PCA['conf'][$key]['type']=='check' ||
					   $PCA['conf'][$key]['type']=='checklist' ||
					   $PCA['conf'][$key]['type']=='selectmulti' || $PCA['conf'][$key]['type']=='selectsingle') {
				$fieldList = explode(',',  (isset($def['fields'])) ? $def['fields'] : $dbName.'.'.$key   ) ;
				$fieldComp =   (isset($def['comp'])) ? $def['comp'] : '='   ;
				$fieldMode =  (isset($def['mode'])) ? $def['mode'] : 'OR'  ;
				$fieldAppend =  (isset($def['append'])) ? $def['append'] : ''  ;
				$fieldPrepend =  (isset($def['prepend'])) ? $def['prepend'] : ''  ;

				if (isset($def['query'])) {
					$this->globalReplace['###val###'] = $text;
					$fieldList[0] = $this->replaceArray($this->lCObj->insertData($def['query']),$this->globalReplace);
					$fieldList[0] = str_replace('*','%',$fieldPrepend.$fieldList[0].$fieldAppend);
				} else {
					$fieldList[0] = ' '.$fieldList[0].$fieldComp.QT.str_replace('*','%',$fieldPrepend.$text.$fieldAppend).QT.' ';
				}
				if ($fieldList[0]) {
					$q['select_'.$key] = $fieldList[0];
				}
			}

		}

		//t3lib_div::debug(Array('$q('.$key.','.$text.')'=>$q, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($q);
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
	function getDbEnableColumns ($dbName,$PCA,$piVarSearch,$q) {

		$q['enc_delete'] = $dbName.'.'.$PCA['ctrl']['delete'].'=0';
		if (intval($PCA['pid_list']) || strlen($PCA['pid_list'])>1) {
			$q['enc_pidlist'] = $dbName.'.pid IN ('.$PCA['pid_list'].')';
		}

		// Build enable-columns restriction
		$unh = $dbName.'.'.$PCA['ctrl']['enablecolumns']['disabled'].'=0';
		if ($PCA['ctrl']['enablecolumns']['starttime']) {
			$unh .= ' AND '.$dbName.'.'.$PCA['ctrl']['enablecolumns']['starttime'].'<'.time();
		}
		if ($PCA['ctrl']['enablecolumns']['endtime']) {
			$unh .= ' AND ('.$dbName.'.'.$PCA['ctrl']['enablecolumns']['endtime'].'>'.time();
			$unh .= ' OR '.$dbName.'.'.$PCA['ctrl']['enablecolumns']['endtime'].'<86000)';
		}

		if (!$this->permitObj->allowed('admin')  && $PCA['ctrl']['crfeuser_id'] &&
				($this->permitObj->allowed('showOnlyOwnAndPublicEntries') || $this->permitObj->allowed('showOnlyOwnEntries')) ) {
			$q['enc_crfeuser'] = $dbName.'.'.$PCA['ctrl']['crfeuser_id'].
									' IN ('.$this->permitObj->getFeUid().($this->permitObj->allowed('showOnlyOwnAndPublicEntries')? ',0':'').')';
		}

		$accessSearch = $PCA['ctrl']['enablecolumns']['fe_group'];
		if ($accessSearch) {
			$acc = $dbName.'.'.$accessSearch.'=0';
			if ($this->permitObj->getFeUid()) {
				$acc .= ' OR '.$dbName.'.'.$accessSearch.'=-2';
				$acc .= ' OR '.$dbName.'.'.$accessSearch.' IN ('.implode(',',$this->feGroups).')';
			} else {
				$acc .= ' OR '.$dbName.'.'.$accessSearch.'=-1';
			}
			if ($this->permitObj->allowed('admin') || $this->permitObj->allowed('seeAllHidden') ) {
				// may see all hidden records ! no more $q[]
			} else {
				// may only see OWN hidden records
				if ($this->permitObj->getFeUid() && isset($PCA['ctrl']['enablecolumns']['crfeuser_id'])) {
					$q['enc_access_1'] = '('.$acc.' OR '.$dbName.'.'.$PCA['ctrl']['enablecolumns']['crfeuser_id'].'='.$this->permitObj->getFeUid().
						($this->permitObj->getBeUid() ? ' OR '.$dbName.'.cruser_id='.$this->permitObj->getBeUid() : '' ).
						')';
				} else {
					$q['enc_access_2'] = '('.$acc.')';
				}
			}
		}

		$hiddenSearch = $piVarSearch[$PCA['ctrl']['enablecolumns']['disabled']];
		if (strlen($hiddenSearch)<1 || intval($hiddenSearch)<0) {
			// Default is search hidden and unhidden
			// now check, if we have to evaluate the enable-columns
			if ($this->permitObj->allowed('admin') || $this->permitObj->allowed('seeAllHidden') ) {
				// may see all hidden records ! no more $q[]
			} else {
				// may only see OWN hidden records
				if ($this->permitObj->getFeUid() && isset($PCA['ctrl']['enablecolumns']['crfeuser_id'])) {
					$q['enc_hidden_1'] = '('.$unh.' OR '.$dbName.'.'.$PCA['ctrl']['enablecolumns']['crfeuser_id'].'='.$this->permitObj->getFeUid().
						($this->permitObj->getBeUid() ? ' OR '.$dbName.'.cruser_id='.$this->permitObj->getBeUid() : '' ).
						')';
				} else if ($this->permitObj->getBeUid()) {
					$q['enc_hidden_2'] = '('.$unh.' OR '.$dbName.'.cruser_id='.$this->permitObj->getBeUid().')';
				} else {
					$q['enc_hidden_3'] = $unh;
				}
			}
		} else if (intval($hiddenSearch)>0) {
			// Show only Hidden
			// now check, if we have to evaluate the enable-columns
			$unh = '!('.$unh.')';
			if (intval($hiddenSearch)>63) {
				$hiddenMode = $hiddenSearch & 48;
				$timeMode = $hiddenSearch & 67;
				$tmp = Array();

				if ($hiddenMode==16) {
					$tmp[] = $dbName.'.'.$PCA['ctrl']['enablecolumns']['disabled'].'=0';
				} else if ($hiddenMode==32) {
					$tmp[] = $dbName.'.'.$PCA['ctrl']['enablecolumns']['disabled'].'=1';
				}

				if ($PCA['ctrl']['enablecolumns']['starttime'] && $PCA['ctrl']['enablecolumns']['endtime']) {
					if ($timeMode==64 ) {
						$tmp[] = $dbName.'.'.$PCA['ctrl']['enablecolumns']['starttime'].'>'.time();
					} else if ($timeMode==65) {
						$tmp[] = $dbName.'.'.$PCA['ctrl']['enablecolumns']['starttime'].'<='.time();
						$tmp[] = '('.$dbName.'.'.$PCA['ctrl']['enablecolumns']['endtime'].'>='.time().
								' OR '.$dbName.'.'.$PCA['ctrl']['enablecolumns']['endtime'].'<86400)';
					} else if ($timeMode==66) {
						$tmp[] = $dbName.'.'.$PCA['ctrl']['enablecolumns']['endtime'].'<'.time();
						$tmp[] = $dbName.'.'.$PCA['ctrl']['enablecolumns']['endtime'].'>86400';
					}
				}

				$unh = count($tmp) ? '('.implode(' AND ',$tmp).')'  : '';
			}

			if ($this->permitObj->allowed('admin') || $this->permitObj->allowed('seeAllHidden')) {
				// may see all hidden records !
				$q[] = $unh;
			} else if ($unh) {
				// may only see OWN hidden records
				// //$q[] = '(!'.$unh.' AND '.$dbName.'.'.$PCA['ctrl']['crfeuser_id'].'='.$this->feUser['uid'].')';
				if ($this->permitObj->getFeUid()) {
					if ($PCA['ctrl']['crfeuser_id']) {
						if ($this->permitObj->getBeUid()) {
							$q['enc_ownhidden'] = '('.$unh.' AND ('.$dbName.'.'.$PCA['ctrl']['crfeuser_id'].'='.
								$this->permitObj->getFeUid().' OR '.$dbName.'.cruser_id='.$this->permitObj->getBeUid().'))';
						} else {
							$q['enc_ownhidden'] = '('.$unh.' AND '.$dbName.'.'.$PCA['ctrl']['crfeuser_id'].'='.$this->permitObj->getFeUid().')';
						}
					}
				} else if ($this->permitObj->getBeUid()) {
					$q['enc_ownhidden'] = '('.$unh.' AND '.$dbName.'.cruser_id='.$this->permitObj->getBeUid().')';
				} else {
					$q['enc_ownhidden'] = $unh;
				}
			}
		} else {
			// Show only unhidden
			$q['enc_unhidden-9'] = $unh;
		}
		$this->debugObj->debugIf('sql',Array('$q'=>$q, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($q);
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
	function getDbBuildOrder ($dbName,$PCA,$piVarSearch,$markers=Array(),$dbg=0) {
		$order = '';

		if (!isset($piVarSearch['listmode'])) {
			if (isset($PCA['ctrl']['defaultListmode'])) {
				$piVarSearch['listmode'] = $PCA['ctrl']['defaultListmode'];
			} else {
				$piVarSearch['listmode'] = 'default';
			}
		}

		if (isset($PCA['ctrl']['defaultOrder'])) {
			$order = $PCA['ctrl']['defaultOrder'];
		}

		if (isset($PCA['listmode'][$piVarSearch['listmode']])) {
			if (isset($PCA['listmode'][$piVarSearch['listmode']]['order'])) {
				$order = $PCA['listmode'][$piVarSearch['listmode']]['order'];
			}
		}

		return ($order.$this->mSqlConf['addToOrder']);
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
	function getDbBuildGroup ($dbName,$PCA,$piVarSearch,$markers=Array(),$dbg=0) {
		$group = '';

		if (!isset($piVarSearch['listmode'])) {
			if (isset($PCA['ctrl']['defaultListmode'])) {
				$piVarSearch['listmode'] = $PCA['ctrl']['defaultListmode'];
			} else {
				$piVarSearch['listmode'] = 'default';
			}
		}

		if (isset($PCA['ctrl']['defaultGroup'])) {
			$group = $PCA['ctrl']['defaultGroup'];
		}

		if (isset($PCA['listmode'][$piVarSearch['listmode']])) {
			if (isset($PCA['listmode'][$piVarSearch['listmode']]['group'])) {
				$group = $PCA['listmode'][$piVarSearch['listmode']]['group'];
			}
		}

		return ($group.$this->mSqlConf['addToGroup']);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$dbName: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$markers: ...
	 * @param	[type]		$dbg: ...
	 * @return	[type]		...
	 */
	function getDbHeaders ($dbName,$PCA,$markers=Array(),$dbg) {
		$m = Array();

		$m['###MYDESCR_LINE###'] = 'LineNr.';
		$m['###MYDESCR_DB###'] = $dbName;
		$m['###DESCR_UID###'] = 'UID';
		$m['###DESCR_HIDDEN###'] = $this->conf['text.']['hidden'];
		$m['###DESCR_DISABLED###'] = $this->conf['text.']['disabled'];

		$constMarker = $this->constObj->getMarkers();
		$m = array_merge ($m,$constMarker);

		foreach ($this->confObj->mainConf as $key=>$value) {
			$field = (substr($key,-1)=='.') ? substr($key,0,-1) : $key;
			$m['###DESCR_'.strtoupper($field).'###'] = $this->langObj->getLLL($value['label']);
			if (isset($value['slabel'])) { $m['###SDESCR_'.strtoupper($field).'###'] = $value['slabel']; }
			if ($value['bold']) {
				$m['###DESCR_'.strtoupper($field).'###']  = '<b>'.$m['###DESCR_'.strtoupper($field).'###'] .'</b>';
			}
		}

		$this->descr = $m;
		$this->debugObj->debugIf('headerMarkers',Array('DbHeaders-Markers:'=>$m, 'File:Line'=>__FILE__.':'.__LINE__));
		$markers = array_merge ($markers,$m);
		return ($markers);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$dbName: ...
	 * @param	[type]		$n: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$markers: ...
	 * @param	[type]		$em: ...
	 * @param	[type]		$dbg: ...
	 * @return	[type]		...
	 */
	function getDbRow ($dbName,$n,$row,&$PCA, $markers=Array(),$em=0,$dbg=0) {
		GLOBAL $xz;

		if (strlen($dbName)<1) {
			t3lib_div::debug(Array('WARNING'=>'getDbRow: dbName is empty !',
				'Backtrace'=>$this->debugObj->shortBacktrace(5,1),'File:Line'=>__FILE__.':'.__LINE__));
		}
		$m = Array();

		if (intval($row['uid'])<1) {
			$msg = 'SGZLIB: New Record prepared for table "'.$dbName.'"';
			if (strlen($row['category'])>0) {
				$msg.= ' Preset[category]="'.$row['category'].'"';
			}
			$this->writelog($msg.' (F'.$this->permitObj->getFeUid().'/B'.$this->permitObj->getBeUid().')',2,Array(1,2,3));
		}
		if (is_array($row)) for (reset($row);$key=key($row);next($row)) {
			$tmpFT = $this->confObj->mainConf[$key.'.']['foreign_table'] ? 
					$this->confObj->mainConf[$key.'.']['foreign_table'] : $this->confObj->mainConf[$key.'.']['allowed'];
			if ($tmpFT && $this->confObj->mainConf[$key.'.']['MM'] && intval($row['uid'])>0) {
				// this line is has a mm relation to foreign_table
				$new = Array();
//				$res = $this->doSelect ('uid_foreign',$this->confObj->mainConf[$key.'.']['MM'],'uid_local='.intval($row['uid']),'','',500,'',0);
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery
						('uid_foreign',$this->confObj->mainConf[$key.'.']['MM'],'uid_local='.intval($row['uid']),'','sorting','0,1000');
				if ($res) {
					while ($s = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
						$new[] = $s['uid_foreign'];
					}
				}
				if (strcmp($key,$this->debugMMField)==0) {
					t3lib_div::debug(Array('READ MM $key'=>$key, '$row[$key] was'=>$row[$key], '$row[$key]='=>implode (',',$new), 'File:Line'=>__FILE__.':'.__LINE__));
				}
				$row[$key] = implode (',',$new);
				$this->debugObj->debugIf('MM',Array('MM Relation in'=>$key, '$old'=>$old, '$new'=>$row[$key], 'File:Line'=>__FILE__.':'.__LINE__));
			}
		}

		$m['###LINE_NR###'] = $n+1;
		$m['###TEXT_DISABLED###'] = $this->getHiddenText($PCA,$row,'list');
		if (!isset($m['###ALL_ERRORS###'])) {
			$m['###ALL_ERRORS###'] = '';
		}

		if (is_array($row)) {
			// BE-USER
			$myOwner = (strlen($this->confObj->mainCtrl['crfeuser_id'])>0) ? $row [$this->confObj->mainCtrl['crfeuser_id']] :0;
			$myBeOwner = $row [ (strlen($this->confObj->mainCtrl['cruser_id'])>0) ? $this->confObj->mainCtrl['cruser_id']:'cruser_id'];
			$iAmEditor = FALSE;
			if ($this->permitObj->allowed('editUserlist') && t3lib_div::inList($this->permitObj->allowed('editUserlist'),$myOwner)) {
				$iAmEditor = TRUE;
			} else if ($this->permitObj->allowed('editCatlist') && t3lib_div::inList($this->permitObj->allowed('editCatlist'),$row[$this->permitObj->allowed('catlistCatfield')])) {
				$iAmEditor = TRUE;
			}
			$allowEditAll = ($this->permitObj->allowed('editAll') || $iAmEditor ||
				($this->permitObj->allowed('editOwn') && $myOwner==$this->feUser['uid'] && strlen($this->feUser['uid'])>0))? 1:0;
			if (!$allowEditAll && !$row['uid'] && $this->permitObj->allowed('addEntry')) {
				$allowEditAll = 1;
			}
			$myXtra = $row [ (strlen($this->confObj->mainCtrl['xtrauser_id'])>0) ? $this->confObj->mainCtrl['xtrauser_id']:'xtrauser_id'];
			$allowedFields = Array();
			if (!$allowEditAll && $myXtra==$this->feUser['uid'] && strlen($this->feUser['uid'])>0) {
				$tmp = explode(',',$this->confObj->mainCtrl['xtrauser_fields']);
				for ($i1=0;$i1<count($tmp);$i1++) {
					$allowedFields[$tmp[$i1]] = 1;
				}
			}

			//$s = $this->divObj->getMicrosec();
			for (reset($row);$key=key($row);next($row)) {
				if ($em==0 && $row[$key] && $this->confObj->mainConf[$key.'.']['makeEmailLink']) {
					$row[$key] = $this->lCObj->mailto_makelinks('mailto:'.$row[$key],'');
					//t3lib_div::debug(Array($key=>'spamProtect('.$row[$key].')=>'.$tmp, 'File:Line'=>__FILE__.':'.__LINE__));
				}

				$keyU = strtoupper($key);

				$m['###AUTO_'.$keyU.'###'] = $m['###TEXT_'.$keyU.'###'] = $this->getFeSingleField_SW($dbName,$key,$row,SGZ_TEXT,$PCA);
				$m['###ERROR_'.$keyU.'###'] = '';

				if ($em==SGZ_FORM) {
					$m['###FORM_'.$keyU.'###'] = $this->getFeSingleField_SW($dbName,$key,$row,SGZ_FORM,$PCA);
				} else if ($em==SGZ_AUTO || $em==SGZ_AUTOHIDDEN) {
					$m['###FORM_'.$keyU.'###'] = $this->getFeSingleField_SW($dbName,$key,$row,SGZ_FORM,$PCA);
					if ($allowEditAll || isset($allowedFields[strtolower($key)]) || ($PCA['todo']['Edit']>0 && $PCA['todo']['New']>0)) {
						$m['###AUTO_'.$keyU.'###'] = $this->getFeSingleField_SW($dbName,$key,$row,SGZ_FORM,$PCA);
					}
					if ($this->confObj->mainConf[$key.'.']['editEmptyOrNewOnly']) {
						if ($PCA['todo']['Uid'] && strlen(trim($row[$key]))>1) {
							$m['###AUTO_'.$keyU.'###'] = $m['###TEXT_'.$keyU.'###'];
						}
					}

					if ($em==SGZ_AUTOHIDDEN) {
						if ($this->confObj->mainConf[$key.'.']['type']=='datetime') {
							$myDate =  ($this->dateTimeStringToTime($row[$key])>10000) ? $row[$key] : date('d.m.Y H:i',intval($row[$key]))  ;
							$m['###HIDDEN_'.$keyU.'###'] = '<input type="hidden"  name="'.$PCA['name'].$this->formDataName.'['.$key.']" value="'.$myDate.'" />';
						} else if ($this->confObj->mainConf[$key.'.']['type']=='date') {
							$myDate =  ($this->dateStringToTime($row[$key])>10000) ? $row[$key] : date('d.m.Y',intval($row[$key]))  ;
							$m['###HIDDEN_'.$keyU.'###'] = '<input type="hidden"  name="'.$PCA['name'].$this->formDataName.'['.$key.']" value="'.$myDate.'" />';
						} else if ($this->confObj->mainConf[$key.'.']['type']=='time') {
							$myTime =  ($this->timeStringToTime($row[$key]) ? $row[$key] : date('H:i',intval($row[$key])-3600) )  ;
							$m['###HIDDEN_'.$keyU.'###'] = '<input type="hidden"  name="'.$PCA['name'].$this->formDataName.'['.$key.']" value="'.$myTime.'" />';
						} else {
							$m['###HIDDEN_'.$keyU.'###'] = '<input type="hidden"  name="'.$PCA['name'].$this->formDataName.'['.$key.']" value="'.$row[$key].'" />';
						}
					}
				}
			}
			//$xz = $xz+$this->divObj->getMicrodur($s); t3lib_div::debug(Array('$xz'=>$xz, 'File:Line'=>__FILE__.':'.__LINE__));


			foreach ($this->confObj->mainConf as $key=>$fieldConf) {
				$field = (substr($key,-1)=='.') ? substr($key,0,-1) : $key;
				if (isset($fieldConf['mode'])) {
					if (strcasecmp($fieldConf['mode'],'calc')==0) {
						# #todo# do calculation of a formula (e.g. like filemaker)
						//$m['###TEXT_'.strtoupper($field).'###'] = $fieldConf['calc'];
						//t3lib_div::debug(Array('row'=>$row, 'File:Line'=>__FILE__.':'.__LINE__));
					} else if (strcasecmp($fieldConf['mode'],'concat')==0) {
						$concat = explode (',', ( isset($fieldConf['fields']) ? $fieldConf['fields'] : $this->confObj->mainCtrl['label'] ) );
						$myTitle = Array();
						for ($j=0;$j<count($concat);$j++) {
							if (isset($row[$concat[$j]])) {
								$myTitle[$j] = (strcmp($m['###TEXT_'.strtoupper($concat[$j]).'###'],'&nbsp;')==0) ? '' :  $m['###TEXT_'.strtoupper($concat[$j]).'###'];
							} else if (strcasecmp(substr(trim($concat[$j]),0,7),'ifempty')==0 && strstr($concat[$j],'(') && strstr($concat[$j],')')   ) {
								$tmp = explode (';',substr(strstr($concat[$j],'('),1,-1),2);
								$myTitle[$j] = '';
								if ( (intval($row[trim($tmp[0])])==0 && strcmp($row[trim($tmp[0])],'0')==0)  || strlen($row[trim($tmp[0])])<1 ) {
									$myTitle[$j] =  str_replace('comma',',',$tmp[1]);
								}
							} else if (strcasecmp(substr(trim($concat[$j]),0,2),'if')==0 && strstr($concat[$j],'(') && strstr($concat[$j],')')   ) {
								$tmp = explode (';',substr(strstr($concat[$j],'('),1,-1),2);
								$myTitle[$j] = '';
								if ( intval($row[trim($tmp[0])])>0 || (strcmp($row[trim($tmp[0])],'0')!=0  && strlen($row[trim($tmp[0])])>0) ) {
									$myTitle[$j] =  str_replace('comma',',',$tmp[1]);
								}
							} else {
								$myTitle[$j] = str_replace('comma',',',$concat[$j]);
							}
						}
						$m['###TEXT_'.strtoupper($field).'###'] = implode('',$myTitle);
					}
				}

				if (strcmp($fieldConf['type'],'linklist')==0) {
					$cap = $fieldConf['captionfield'];
					$captions = $cap ? explode("\r\n",$row[$cap]) : Array();
					$links = explode("\r\n",$row[$field]);
					if ( ($em==SGZ_AUTO || $em==SGZ_AUTOHIDDEN)  && ($allowEditAll || isset($allowedFields[strtolower($field)])) ) {
						$m['###AUTO_'.strtoupper($field).'###'] = $this->editListWrap[0].
							$this->editListElementWrap[0].$m['###FORM_'.strtoupper($field).'###'].$this->editListElementWrap[1].
								$this->conf['form.']['editList.']['linkListSeparator'];
						if ($cap && intval($fieldConf['autocaption'])>0) {
							$m['###AUTO_'.strtoupper($field).'###'] .= $this->editListElementWrap[0].
								$this->getSingleField_typeText($dbName,$cap,$row,$em,$PCA,'textnowrap').
								$this->editListElementWrap[1];
						}
						$m['###AUTO_'.strtoupper($field).'###'] .= $this->editListWrap[1];
					} else {
						if (count($captions)<count($links)) {
							$captions[count($links)-1] = $captions[count($links)-1];
						} else {
							$links[count($captions)-1] = $links[count($captions)-1];
						}
						$myTmp = Array();
						for ($i=0;$i<count($links);$i++) {
							$myTmp[] = ($links[$i] ? '<a href="'.$links[$i].'" target="_blank">':'').
								$links[$i].($links[$i]&&$captions[$i] ?' - ':'').$captions[$i].
								($links[$i] ? '</a>':'');
						}

						$m['###TEXT_'.strtoupper($field).'###'] = implode ('<br />',$myTmp);;
						$m['###AUTO_'.strtoupper($field).'###'] = $m['###TEXT_'.strtoupper($field).'###'];
					}
				} else if (strcmp(substr($fieldConf['type'],-4),'list')==0 && strcmp($fieldConf['type'],'checklist')) {
					$viewInForm = intval($fieldConf['viewInForm']);
					if (strcmp($fieldConf['type'],'imagelist')==0) {
						$cap = $fieldConf['captionfield'];
						$link = $fieldConf['linkfield'];
						$tmpListMode = 'webimg';
					} else if (strcmp($fieldConf['type'],'filelist')==0) {
						$tmpListMode = strlen($fieldConf['filetypes'])>1 ? $fieldConf['filetypes'] : 'files';
						$cap = '';
						$link = '';
					} else if (strcmp($fieldConf['type'],'pdflist')==0) {
						$tmpListMode = $fieldConf['filetypes'] ? $fieldConf['filetypes'] :  'pdf';
						$cap = $fieldConf['captionfield']; //sg 051211
						$link = '';
					} else if (strcmp($fieldConf['type'],'doclist')==0) {
						$tmpListMode = $fieldConf['filetypes'] ? $fieldConf['filetypes'] : 'pdf,doc,sxw,txt';
						$cap = $fieldConf['captionfield']; //sg 051211
						$link = '';
					} else {
						$tmpListMode = 'unknown';
						$cap = '';
						$link = '';
					}

					$row[$field] = str_replace("\n",',',str_replace("\r",',',str_replace("\r\n",',',
							str_replace(",\r",',',str_replace(",\n",',',str_replace(','."\r\n",',',$row[$field]))))));
					
					$tmp = t3lib_div::trimExplode("\n",$row[$field]);
					$tmp = $tmp[0];
					$m['###FILE_'.strtoupper($field).'###'] = '';
					$m['###FILEREL_'.strtoupper($field).'###'] = '';
					$m['###FILEABS_'.strtoupper($field).'###'] = '';
					if (strlen($tmp)) {
						$path = $PCA['conf'][$field]['uploadfolder'];
						$path = ((substr($path,-1)=='/') ? $path : $path.'/');
						$m['###FILE_'.strtoupper($field).'###'] = $path.$tmp;
						// t3lib_div::debug(Array('$path'=>$path, '$tmp'=>$tmp, 'Rel='=>$m['###FILEREL_'.strtoupper($field).'###'], 'Abs='=>$m['###FILEABS_'.strtoupper($field).'###'], 'File:Line'=>__FILE__.':'.__LINE__));
					}

					$single = (intval($fieldConf['maxitems'])==1);
					$autoSort = (intval($fieldConf['autoSort'])==1);
					$xbr = '';
					if (!$single || intval($fieldConf['buttonsMultiLine'])) { $xbr=$this->editListButtonSeparator; }
					$m['###FORM_ADD_'.strtoupper($field).'###'] = $this->addFileLink ($PCA,$field,$tmpListMode,1,$row);
					$m['###FORM_UP_'.strtoupper($field).'###'] = $single ? '' : $this->addListModify ($PCA,$field,'Up');
					$m['###FORM_DEL_'.strtoupper($field).'###'] = $this->addListModify ($PCA,$field,$single ? 'Removeall' : 'Remove');
					if ( ($em==SGZ_AUTO || $em==SGZ_AUTOHIDDEN)  && ($allowEditAll || isset($allowedFields[strtolower($field)])) ) {
						$m['###AUTO_'.strtoupper($field).'###'] = $this->editListWrap[0].
							$this->editListElementWrap[0].$m['###FORM_'.strtoupper($field).'###'].$this->editListElementWrap[1].
							$this->editListElementWrap[0].$m['###FORM_ADD_'.strtoupper($field).'###'].$xbr.
								(($single || $autoSort) ? '' : $m['###FORM_UP_'.strtoupper($field).'###'].$xbr).
								$m['###FORM_DEL_'.strtoupper($field).'###'].$this->editListButtonSeparator.$this->editListElementWrap[1];
						if ($cap && intval($fieldConf['autocaption'])>0) {
							$m['###AUTO_'.strtoupper($field).'###'] .= $this->editListElementWrap[0].
								$this->getSingleField_typeText($dbName,$cap,$row,$em,$PCA,'textnowrap').
								$this->editListElementWrap[1];
						}
						if ($link && intval($fieldConf['autolink'])>0) {
							$m['###AUTO_'.strtoupper($field).'###'] .= $this->editListElementWrap[0].
								$this->getSingleField_typeText($dbName,$link,$row,$em,$PCA,'textnowrap').
								$this->editListElementWrap[1];
						}
						$m['###AUTO_'.strtoupper($field).'###'] .= $this->editListWrap[1];
						if ($tmpListMode=='webimg') {
							$cpt = $cap ? (isset($this->confObj->mainConf[$cap.'.']) ?
								t3lib_div::trimExplode("\n",$row[$cap]):Array($cap)):Array();
							$url = $link ? t3lib_div::trimExplode("\n",$row[$link]) : Array();
							$imgMrk = '###TEXT_'.strtoupper($field);
							for (reset($PCA['image']);$iKey=key($PCA['image']);next($PCA['image'])) {
								$m[$imgMrk.'_ALL_'.strtoupper($iKey).'###'] = $viewInForm ?
									$this->getImages ($dbName,$field,$row,$PCA,strtolower($iKey),0,$cpt,$url) : '';
								$m[$imgMrk.'_FIRST_'.strtoupper($iKey).'###'] = $viewInForm ?
									$this->getImages ($dbName,$field,$row,$PCA,strtolower($iKey),1,$cpt,$url) : '';
								$m[$imgMrk.'_MORE_'.strtoupper($iKey).'###'] = $viewInForm ?
									$this->getImages ($dbName,$field,$row,$PCA,strtolower($iKey),2,$cpt,$url) : '';
							}
						} else {
							$m['###TEXT_'.strtoupper($field).'###'] = $viewInForm ? $this->getFileLinks ($dbName,$field,$row,$PCA) : '';
						}
					} else {
						if ($tmpListMode=='webimg') {
							$cpt = $cap ? (isset($this->confObj->mainConf[$cap.'.']) ? t3lib_div::trimExplode("\n",$row[$cap]):Array($cap)):Array();
							$url = $link ? t3lib_div::trimExplode("\n",$row[$link]) : Array();
							if (!$link && is_array($fieldConf['typoLink'])) {
								$tlc = $fieldConf['typoLink'];
								$tmpAP = $tlc['additionalParams'].'';
								if (!$tlc['parameter'] || strcmp($tlc['parameter'],'0')==0) {
									$tlc['parameter'] = $GLOBALS['TSFE']->id;
								}
								$tlc['additionalParams'] = $tmpAP.'&uid='.intval($row['uid'].'.');
								$url = Array($this->lCObj->typoLink_URL($tlc));
							}
							$imgMrk = '###TEXT_'.strtoupper($field);
							$m['###AUTO_'.strtoupper($field).'###'] = '';
							for (reset($PCA['image']);$iKey=key($PCA['image']);next($PCA['image'])) {
								$m[$imgMrk.'_ALL_'.strtoupper($iKey).'###'] =
									$this->getImages ($dbName,$field,$row,$PCA,strtolower($iKey),0,$cpt,$url);
								$m[$imgMrk.'_FIRST_'.strtoupper($iKey).'###'] =
									$this->getImages ($dbName,$field,$row,$PCA,strtolower($iKey),1,$cpt,$url);
								$m[$imgMrk.'_MORE_'.strtoupper($iKey).'###'] =
									$this->getImages ($dbName,$field,$row,$PCA,strtolower($iKey),2,$cpt,$url);
							}
						} else {
							$m['###TEXT_'.strtoupper($field).'###'] = $this->getFileLinks ($dbName,$field,$row,$PCA);
							$m['###AUTO_'.strtoupper($field).'###'] = $m['###TEXT_'.strtoupper($field).'###'];
						}
					}
				} else if (strcmp($fieldConf['type'],'selectsingle')==0 ||
					(strcmp($fieldConf['type'],'selectmulti')==0 && intval($fieldConf['maxitems'])==1)) {
					$m['###FORM_ADD_'.strtoupper($field).'###'] = $this->addDbLink ($PCA,$field,1,$row);
					$m['###FORM_UP_'.strtoupper($field).'###'] = '';
					$m['###FORM_DEL_'.strtoupper($field).'###'] = $this->addListModify ($PCA,$field,'Removeall',1);
					if ( ($em==SGZ_AUTO || $em==SGZ_AUTOHIDDEN)  && ($allowEditAll || isset($allowedFields[strtolower($field)])) ) {
						$m['###AUTO_'.strtoupper($field).'###'] = $this->editListWrap[0].
							$this->editListElementWrap[0].$m['###FORM_'.strtoupper($field).'###'].$this->editListElementWrap[1].
							$this->editListElementWrap[0].$m['###FORM_ADD_'.strtoupper($field).'###'].
								$m['###FORM_DEL_'.strtoupper($field).'###'].'<br />'.$this->editListElementWrap[1].
							$this->editListWrap[1];
					} else {
					}
				} else if (strcmp($fieldConf['type'],'selectmulti')==0) {
					$xbr = $this->editListButtonSeparator;
					$autoSort = (intval($fieldConf['autoSort'])==1);
					if (intval($fieldConf['buttonsInSingleLine'])) { $xbr=''; }
					$m['###FORM_ADD_'.strtoupper($field).'###'] = $this->addDbLink ($PCA,$field,1,$row);
					$m['###FORM_UP_'.strtoupper($field).'###'] = $this->addListModify ($PCA,$field,'Up',1);
					$m['###FORM_DEL_'.strtoupper($field).'###'] = $this->addListModify ($PCA,$field,'Remove',1);
					if ( ($em==SGZ_AUTO || $em==SGZ_AUTOHIDDEN)  && ($allowEditAll || isset($allowedFields[strtolower($field)])) ) {
						$m['###AUTO_'.strtoupper($field).'###'] = $this->editListWrap[0].
							$this->editListElementWrap[0].$m['###FORM_'.strtoupper($field).'###'].$this->editListElementWrap[1].
							$this->editListElementWrap[0].$m['###FORM_ADD_'.strtoupper($field).'###'].$xbr.
								($autoSort ? '' : $m['###FORM_UP_'.strtoupper($field).'###'].$xbr).
								$m['###FORM_DEL_'.strtoupper($field).'###'].$xbr.$this->editListElementWrap[1].
							$this->editListWrap[1];
					} else {
					}
				} else if (strcmp($fieldConf['type'],'checklist')==0) {
					// t3lib_div::debug(Array('5'=>5, 'File:Line'=>__FILE__.':'.__LINE__));
				}
			}


			foreach ($this->confObj->mainConf as $key=>$fieldConf) {
				$field = (substr($key,-1)=='.') ? substr($key,0,-1) : $key;
				if (isset($fieldConf['link'])) {
					$m['###LINK_'.strtoupper($field).'###'] = $this->getDbFieldLink($PCA,$m,$row,$field);
					if (strlen($m['###LINK_'.strtoupper($field).'###'])>0 && $em<SGZ_FORM) {
						$m['###AUTO_'.strtoupper($field).'###'] = $m['###LINK_'.strtoupper($field).'###'];
					}
				}
			}

			foreach ($this->confObj->mainConf as $key=>$fieldConf) {
				$field = (substr($key,-1)=='.') ? substr($key,0,-1) : $key;
				// Now possibly wrap
				$toWrap = strlen($m['###LINK_'.strtoupper($field).'###'])>0 ?
						$m['###LINK_'.strtoupper($field).'###'] :$m['###TEXT_'.strtoupper($field).'###'];
				$m['###WRAP_'.strtoupper($field).'###'] = '';

				if ($fieldConf['stdWrapName']) {
					$myStdWrapConf = $PCA['stdwrap'][$fieldConf['stdWrapName'].'.'];
					$toWrap = $this->lCObj->stdWrap($toWrap,$myStdWrapConf);
					$m['###WRAP_'.strtoupper($field).'###'] = $toWrap;
				}

				if (isset($fieldConf['wrap'])) {
					$myWrap = explode ('|',$fieldConf['wrap']);
					$toWrap = $myWrap[0].$toWrap.$myWrap[1];
					$m['###WRAP_'.strtoupper($field).'###'] = $toWrap;
				} else if (isset($fieldConf['wrapIf']) && strlen(trim($toWrap))>0
							&& strcmp('&nbsp;',trim($toWrap)) ) {
					$tmp = $fieldConf['wrapIf'];
					$myWrap = explode ('|', substr($tmp,0,4)=="LLL:" ? $this->langObj->getLLL($tmp) : $tmp );
					$toWrap = $myWrap[0].$toWrap.$myWrap[1];
					$m['###WRAP_'.strtoupper($field).'###'] = $toWrap;
				}

				if (strlen($m['###WRAP_'.strtoupper($field).'###'])>0 && $em<SGZ_FORM) {
					$m['###AUTO_'.strtoupper($field).'###'] = $m['###WRAP_'.strtoupper($field).'###'];
				}
			}

			foreach ($this->confObj->mainConf as $key=>$fieldConf) {
				$field = (substr($key,-1)=='.') ? substr($key,0,-1) : $key;
				$toFormat = strlen($m['###LINK_'.strtoupper($field).'###'])>0 ? 
					$m['###LINK_'.strtoupper($field).'###'] : $m['###TEXT_'.strtoupper($field).'###'];
				// Now evtl. Format
				if (is_array($PCA['format'])) {
					for(reset($PCA['format']);$foKey=key($PCA['format']);next($PCA['format'])) {
						$m['###TEXT'.strtoupper($foKey.'_'.$field).'###'] = '';
						if (strlen(trim($toFormat))>0 && strcmp('&nbsp;',trim($toFormat)) ) {
							$m['###TEXT'.strtoupper($foKey.'_'.$field).'###'] =
								str_replace ('###name###',$this->descr['###DESCR_'.strtoupper($field).'###'],
									str_replace ('###data###',$toFormat,$PCA['format'][$foKey]));
						}
						$m['###AUTO'.strtoupper($foKey.'_'.$field).'###'] = $m['###TEXT'.strtoupper($foKey.'_'.$field).'###'];
						if ($em==SGZ_FORM || $em==SGZ_AUTO || $em==SGZ_AUTOHIDDEN) {
							$m['###FORM'.strtoupper($foKey.'_'.$field).'###'] =
								str_replace ('###name###',$this->descr['###DESCR_'.strtoupper($field).'###'],
									str_replace ('###data###',$m['###FORM_'.strtoupper($field).'###'],$PCA['format'][$foKey]));
						}
						if ($em==SGZ_AUTO || $em==SGZ_AUTOHIDDEN) {
							if ($allowEditAll || isset($allowedFields[strtolower($field)]) || ($PCA['todo']['Edit']>0 && $PCA['todo']['New']>0)) {
								$m['###AUTO'.strtoupper($foKey.'_'.$field).'###'] = $m['###FORM'.strtoupper($foKey.'_'.$field).'###'];
							}
						}
					}
				}
			}

			$txtListTitle = ( isset($this->confObj['listTitle.']['fields']) ?
						$this->confObj['listTitle.']['fields'] : $this->confObj->mainCtrl['label'] );
			$listTitles = explode (',',$txtListTitle);
			$myTitle = '';
			$preValue = false;
			for ($j=0;$j<count($listTitles);$j++) {
				if (strcmp($listTitles[$j],'comma')==0) {
					$myTitle .= ',';
				} else if (isset($row[$listTitles[$j]])) {
					$myTitle .= $row[$listTitles[$j]];
					$preValue = (strlen($row[$listTitles[$j]])>0) ? true : false;
				} else {
					$myTitle .= $listTitles[$j];
				}
			}
		} else {
			t3lib_div::debug(Array('ERROR'=>'$row not set', 'BackTrace'=>debug_backtrace(), 'File:Line'=>__FILE__.':'.__LINE__));
		}

		if (is_array($this->confObj->mainCtrl['printLink'])) {
			$wrap = t3lib_div::trimExplode('|',$this->confObj->mainCtrl['printLink']['wrap']);
			$params = Array('no_cache'=>NULL, 'id'=>$GLOBALS['TSFE']->id, 'type'=>intval($this->confObj->mainCtrl['printLink']['type']));
			$myUrl = t3lib_div::getIndpEnv('TYPO3_REQUEST_HOST').t3lib_div::linkThisScript($params);
			$m['###PRINTLINK###'] = $GLOBALS['TSFE']->type<90 ?
					$wrap[0].'<a target="Print" href="'.$myUrl.'">'.$this->constObj->getButton('printLink').'</a>'.$wrap[1] : '';
		} else {
			$m['###PRINTLINK###'] = '<u>Sorry, no Printlink</u>';
		}

		if (is_array($this->confObj->mainCtrl['pdfLink'])) {
			$wrap = t3lib_div::trimExplode('|',$this->confObj->mainCtrl['pdfLink']['wrap']);
			$myUrl = '';
			if (strlen($this->confObj->mainCtrl['pdfLink']['field'])>2) {
				$tmp = t3lib_div::trimExplode(',',$row[$this->confObj->mainCtrl['pdfLink']['field']]);
				$myUrl = is_array($tmp) ? $tmp[0] : '';
			}
			if (strlen($myUrl)<1) {
				$params = Array('no_cache'=>NULL, 'id'=>$GLOBALS['TSFE']->id, 'type'=>intval($this->confObj->mainCtrl['pdfLink']['type']));
				$myUrl = t3lib_div::getIndpEnv('TYPO3_REQUEST_HOST').t3lib_div::linkThisScript($params);
				$m['###PDFLINK###'] = $GLOBALS['TSFE']->type<90 ?
						$wrap[0].'<a target="PDF" href="'.$myUrl.'">'.$this->constObj->getButton('pdfLink').'</a>'.$wrap[1] : '';
			} else {
				$m['###PDFLINK###'] = $GLOBALS['TSFE']->type<90 ?
						$wrap[0].'<a target="PDF" href="'.$this->confObj->mainCtrl['pdfLink']['path'].$myUrl.'">'.
							$this->constObj->getButton('pdfSpecialLink').'</a>'.$wrap[1] : '';
			}
		} else {
			$m['###PDFLINK###'] = '<u>Sorry, no PDFlink</u>';
		}

		if ($dbg) { t3lib_div::debug(Array('DbRow-Markers:'=>$m, 'File:Line'=>__FILE__.':'.__LINE__)); }
		$markers = array_merge ($markers,$m);
		return ($markers);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$m: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$key: ...
	 * @return	[boolean]		...
	 */
	function getDbFieldLink ($PCA,$m,$row,$key) {

		$l = $PCA['conf'][$key]['link'];
		$m['###LINK_'.strtoupper($key).'###'] = '';
		$myUrl = $m['###TEXT_'.strtoupper($key).'###'];
		$myText = $myUrl;

		if ($PCA['conf'][$key]['type']=='selectmulti' && intval($PCA['conf'][$key]['maxitems'])>1) {
		} else {
			// get url and text
			if (is_array($l['text'])) {
				if (strlen($l['text']['fieldname'])>0) {
					$myText = $m['###TEXT_'.strtoupper($l['text']['fieldname']).'###'];
				}
				if (strlen($l['text']['fieldnameDefault'])>0 && strlen($myText)<2) {
					$myText = $m['###TEXT_'.strtoupper($l['text']['fieldnameDefault']).'###'];
				}
			}
			if (is_array($l['url'])) {
				$urlMode = intval($l['url']['mode']);
				if ($urlMode & 2) {
					$myUrl = $row[$key];
				}
				if (strlen($l['url']['fieldname'])>0) {
					if ($urlMode & 1) {
						$myUrl = $m['###TEXT_'.strtoupper($l['url']['fieldname']).'###'] ;
					} else {
						$myUrl = $row[$l['url']['fieldname']];
						if (strcmp($myUrl,'0')==0) {
							$myUrl = '';
						}
					}
				}

				if (is_array($l['url']['typolink'])>0) {
					$myLastUrl = $myUrl;
					$tmp = $this->lCObj->data;
					$this->lCObj->data = $row;
					$l['url']['typolink'] = $this->replaceArrayArray ($l['url']['typolink'],$row,1,'###row_|###');
					$myUrl = $this->lCObj->typoLink_URL($l['url']['typolink']);
					if (strlen($l['url']['appendWrap'])>0) {
						$myWrap = explode('|',$l['url']['appendWrap']);
						$myUrl .= $myWrap[0].$myLastUrl.$myWrap[1];
					}
					$this->lCObj->data = $tmp;
				} else if (strlen($myUrl)>0 && strlen($l['url']['wrap'])>0) {
					$myWrap = explode('|',$l['url']['wrap']);
					$myUrl = $myWrap[0].$myUrl.$myWrap[1];
				}
				//t3lib_div::debug(Array('$l[url]'=>$l['url'], '$myUrl'=>$myUrl, 'File:Line'=>__FILE__.':'.__LINE__));
			}
			//t3lib_div::debug(Array('$row('.$key.')'=>$row[$key], '$myText'=>$myText, '$myUrl'=>$myUrl, 'File:Line'=>__FILE__.':'.__LINE__));
			// process url
			$u = $this->myParseUrl($myUrl);
			$myUrl = trim($u['total']);
			if (strlen($l['url']['fieldname'])>0) {
				if (! $row[$l['url']['fieldname']]) {
					$myUrl = '';
				}
			}

			if (strlen(trim($myText))<1) { $myText = $myUrl; }

			// no text nor url ?
			if (strlen(trim($myText))<1 && strlen($myUrl)<1) {
				if (strlen($l['tmplEmpty'])>0) {
					$m['###LINK_'.strtoupper($key).'###'] = $l['tmplEmpty'];
				}
			// no url ?
			} else if (strlen($myUrl)<1) {
				if (strlen($l['tmplNoUrl'])>0) {
					$m['###LINK_'.strtoupper($key).'###'] = str_replace('###text###',$myText,$l['tmplNoUrl']);
				} else {
					$m['###LINK_'.strtoupper($key).'###'] = $myText;
				}
			// url AND text ?
			} else {
				$myJS = '';
				if (strlen($l['url']['popup'])>0) {
					$myJS = "var bw=''; bw=window.open('".$myUrl."','View','".$l['url']['popup']."'); bw.focus(); return false;";
				}
				if (strlen($l['tmpl'])>0) {
					$m['###LINK_'.strtoupper($key).'###'] =
						str_replace('###url###',$myUrl,str_replace('###text###',$myText,str_replace('###js###',$myJS,$l['tmpl'])));
				} else {
					$m['###LINK_'.strtoupper($key).'###'] = '<a href="'.$myUrl.'" target="_blank">'.$myText.'</a>';
				}
			}
		}

		return ($m['###LINK_'.strtoupper($key).'###']);
	}




	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$dbName: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$errors: ...
	 * @return	[type]		...
	 */
	function checkDbRowForSave ($dbName,&$row,&$PCA,&$errors) {
		$errorMode = false;
		$myWrap = explode ('|',$PCA['error']['wrap']);

		if (is_array($row)) reset($row);
		if (is_array($PCA['conf']) && is_array($row)) while ( list($key, $val) = each($row) ) {
			$fieldName = $this->langObj->getLLL($PCA['conf'][$key]['label']);
			$fieldHasError = FALSE;
			if (is_array($PCA['conf'][$key])) {
				if (isset($PCA['conf'][$key]['autoSort'])) {
					// t3lib_div::debug(Array('autoSort for '=>$key, 'File:Line'=>__FILE__.':'.__LINE__));
				}
				if (strcmp($PCA['conf'][$key]['type'],'datetime')==0 && strlen($row[$key])>0) {
					if ($this->dateTimeStringToTime($row[$key])==0) {
							$txt = sprintf($this->langObj->getLLL($PCA['error']['datetimeerror']),$row[$key]);
							$errors['###ERROR_'.strtoupper($key).'###'] .= $myWrap[0].$txt.$myWrap[1];
							$errorMode = true; $fieldHasError = true;
					}
				} else if (strcmp($PCA['conf'][$key]['type'],'date')==0 && strlen($row[$key])>0) {
					if ($this->dateStringToTime($row[$key])==0) {
							$txt = sprintf($this->langObj->getLLL($PCA['error']['dateerror']),$row[$key]);
							$errors['###ERROR_'.strtoupper($key).'###'] .= $myWrap[0].$txt.$myWrap[1];
							$errorMode = true; $fieldHasError = true;
					}
				} else if (!$fieldHasError && strcmp($PCA['conf'][$key]['type'],'time')==0 && strlen($row[$key])>0) {
					if (!strlen($this->timeStringToTime($row[$key]))) {
						//t3lib_div::debug(Array('time('.$row[$key].')'=>$this->timeStringToTime($row[$key]), 'File:Line'=>__FILE__.':'.__LINE__));
							$txt = sprintf($this->langObj->getLLL($PCA['error']['timeerror']),$row[$key]);
							$errors['###ERROR_'.strtoupper($key).'###'] .= $myWrap[0].$txt.$myWrap[1];
							$errorMode = true; $fieldHasError = true;
					}
				}
				if (is_array($PCA['conf'][$key]['range']) && $PCA['conf'][$key]['required']['range']) {
					if (strpos('x'.$PCA['conf'][$key]['eval'],'int')>0) {
						if (intval($row[$key]) && (intval($row[$key])<intval($PCA['conf'][$key]['range']['lower']) ||
							intval($row[$key])>intval($PCA['conf'][$key]['range']['upper'])) ) {
							$errorMode = true; $fieldHasError = true;
							$m = Array('###FIELDNAME###'=> $fieldName, '###LOWER###'=>$PCA['conf'][$key]['range']['lower'],
								 '###UPPER###'=>$PCA['conf'][$key]['range']['upper']);
							$txt = $this->langObj->getLLL($PCA['error']['range']);
							$errors['###ERROR_'.strtoupper($key).'###'] .=
								$myWrap[0].$this->lCObj->substituteMarkerArray($txt, $m).$myWrap[1];
						}
					} else {
						if (strlen($row[$key]) && ($row[$key]<$PCA['conf'][$key]['range']['lower'] ||
							$row[$key]>$PCA['conf'][$key]['range']['upper']) ) {
							$errorMode = true; $fieldHasError = true;
							$m = Array('###FIELDNAME###'=> $fieldName, '###LOWER###'=>$PCA['conf'][$key]['range']['lower'],
								 '###UPPER###'=>$PCA['conf'][$key]['range']['upper']);
							$txt = $this->langObj->getLLL($PCA['error']['range']);
							$errors['###ERROR_'.strtoupper($key).'###'] .=
								$myWrap[0].$this->lCObj->substituteMarkerArray($txt, $m).$myWrap[1];
						}
					}
				}
				if (!$fieldHasError && isset($PCA['conf'][$key]['required'])) {
					$testmail = intval($PCA['conf'][$key]['required']['email']);
					$maildata = $row[$key];
					if (stristr($maildata,'<') && strpos($maildata,'>')>strpos($maildata,'<')) {
						$maildata = substr($maildata,strpos($maildata,'<')+1,strpos($maildata,'>')-strpos($maildata,'<')-1);
					}
					if ($testmail && strlen($maildata)) {
						$mailParts = explode('@',$maildata,2);
						$m = Array('###MAILNAME###'=> $mailParts[0], '###MAILDOMAIN###'=>$mailParts[1]);
						if (!checkdnsrr($mailParts[1], 'MX')) if (!checkdnsrr($mailParts[1], 'A')) {
							$txt = $this->langObj->getLLL($PCA['error']['maildomain']);
							$errors['###ERROR_'.strtoupper($key).'###'] .= $myWrap[0].$this->lCObj->substituteMarkerArray($txt, $m).$myWrap[1];
							$errorMode = true; $fieldHasError = true;
						}
						if ( preg_match('/^[a-zA-Z0-9\.\-_]+$/',$mailParts[0]) < 1) {
							$txt = $this->langObj->getLLL($PCA['error']['mailname']);
							$errors['###ERROR_'.strtoupper($key).'###'] .= $myWrap[0].$this->lCObj->substituteMarkerArray($txt, $m).$myWrap[1];
							$errorMode = true; $fieldHasError = true;
						}
					}
					$minLen = intval($PCA['conf'][$key]['required']['len']);
					if (!$fieldHasError && strlen($row[$key])<$minLen) {
						$errorMode = true; $fieldHasError = true;
						$m = Array('###FIELDNAME###'=> $fieldName, '###MINLEN###'=>$minLen);
						$txt = $this->langObj->getLLL($PCA['error']['len']);
						$errors['###ERROR_'.strtoupper($key).'###'] .=
							$myWrap[0].$this->lCObj->substituteMarkerArray($txt, $m).$myWrap[1];
					}
					$maxLen = intval($PCA['conf'][$key]['required']['max']);
					if (!$fieldHasError && $maxLen && strlen($row[$key])>$maxLen) {
						$errorMode = true; $fieldHasError = true;
						$m = Array('###FIELDNAME###'=> $fieldName, '###MAXLEN###'=>$maxLen);
						$txt = $this->langObj->getLLL($PCA['error']['max']);
						$errors['###ERROR_'.strtoupper($key).'###'] .=
							$myWrap[0].$this->lCObj->substituteMarkerArray($txt, $m).$myWrap[1];
					}
					$itemsMinMax = intval($PCA['conf'][$key]['required']['itemsMinMax']);
					$itemsMin = intval($PCA['conf'][$key]['minitems']);
					$itemsMax = intval($PCA['conf'][$key]['maxitems']);
					$c1 = count (explode(',',$row[$key]));
					$c2 = count (explode(CRLF,$row[$key]));
					$ct = strlen($row[$key])<1 ? 0 : ($c1>$c2 ? $c1 : $c2);
					if ($itemsMinMax && $itemsMax>0 && $ct>$itemsMax) {
						$errorMode = true; $fieldHasError = true;
						$m = Array('###FIELDNAME###'=> $fieldName, '###MAXITEMS###'=>$itemsMax);
						$txt = $this->langObj->getLLL($PCA['error']['maxitems']);
						$errors['###ERROR_'.strtoupper($key).'###'] .=
							$myWrap[0].$this->lCObj->substituteMarkerArray($txt, $m).$myWrap[1];
					} else if ($itemsMinMax && $itemsMin>$ct) {
						$errorMode = true; $fieldHasError = true;
						$m = Array('###FIELDNAME###'=> $fieldName, '###MINITEMS###'=>$itemsMin);
						$txt = $this->langObj->getLLL($PCA['error']['minitems']);
						$errors['###ERROR_'.strtoupper($key).'###'] .=
							$myWrap[0].$this->lCObj->substituteMarkerArray($txt, $m).$myWrap[1];
					}
					$notnull = intval($PCA['conf'][$key]['required']['notnull']);
					if (!$fieldHasError && $notnull && intval($row[$key])==0) {
						$errorMode = true; $fieldHasError = true;
						$m = Array('###FIELDNAME###'=> $fieldName);
						$txt = $this->langObj->getLLL($PCA['error']['notnull']);
						$errors['###ERROR_'.strtoupper($key).'###'] .= $myWrap[0].$this->lCObj->substituteMarkerArray($txt, $m).$myWrap[1];
					}
					if (intval($PCA['conf'][$key]['required']['secure'])) {
						$old = $row[$key];
						$row[$key] = str_replace('<','[',$row[$key]);
						$row[$key] = str_replace('>',']',$row[$key]);
						$row[$key] = str_replace('&','+',$row[$key]);
						//$row[$key] = str_replace('"','´',$row[$key]);
						$row[$key] = str_replace('"','&#148;',$row[$key]);
						$row[$key] = str_replace(QT,'´',$row[$key]);
						if (intval($PCA['conf'][$key]['required']['secure'])>1) {
							$row[$key] = eregi_replace('[^a-z0-9,;\.\-_+#!$%/()=\ äöüß]*','',$row[$key]);
							$row[$key] = eregi_replace('[\<\>]*','',$row[$key]);
						} else {
							$row[$key] = $this->lCObj->removeBadHTML($row[$key],'');
						}
						if (strcmp($old,$row[$key])) {
							$errorMode = true; $fieldHasError = true;
							$m = Array('###FIELDNAME###'=> $fieldName);
							$txt = $this->langObj->getLLL($PCA['error']['secure']);
							$errors['###ERROR_'.strtoupper($key).'###'] .= $myWrap[0].$this->lCObj->substituteMarkerArray($txt, $m).$myWrap[1];
						}
					}
					if (isset($PCA['conf'][$key]['required']['bic'])) {
						$m = Array('###FIELDNAME###'=> $fieldName);
						$txt = $this->langObj->getLLL($PCA['error']['account']);
						$tmp = $this->checkAccountBic($row[$key],$row[$PCA['conf'][$key]['required']['bic']],$txt,1);
						if (!$fieldHasError && strlen($tmp)>1) {
							$errorMode = true; $fieldHasError = true;
							$errors['###ERROR_'.strtoupper($key).'###'] .= $myWrap[0].$this->lCObj->substituteMarkerArray($tmp, $m).$myWrap[1];
						}
					}
					if (isset($PCA['conf'][$key]['required']['account'])) {
						$m = Array('###FIELDNAME###'=> $fieldName);
						$txt = $this->langObj->getLLL($PCA['error']['bic']);
						$tmp = $this->checkAccountBic($row[$PCA['conf'][$key]['required']['account']],$row[$key],$txt,2);
						if (!$fieldHasError && strlen($tmp)>1) {
							$errorMode = true; $fieldHasError = true;
							$errors['###ERROR_'.strtoupper($key).'###'] .= $myWrap[0].$this->lCObj->substituteMarkerArray($tmp, $m).$myWrap[1];
						}
					}
					if (isset($PCA['conf'][$key]['required']['ereg'])) {
						$old = $row[$key];
						$row[$key] = ereg_replace('[^'.$PCA['conf'][$key]['required']['ereg'].']*','',$row[$key]);
						if (!$fieldHasError && strcmp($old,$row[$key])) {
							$errorMode = true; $fieldHasError = true;
							$m = Array('###FIELDNAME###'=> $fieldName);
							$txt = $this->langObj->getLLL($PCA['error']['secure']);
							$errors['###ERROR_'.strtoupper($key).'###'] .= $myWrap[0].$this->lCObj->substituteMarkerArray($txt, $m).$myWrap[1];
						}
					}
					$myDup = $PCA['conf'][$key]['required']['dup'];
					if ($myDup) {
						if (!$fieldHasError && strcmp($row[$key],$row[$myDup])) {
							$errorMode = true; $fieldHasError = true;
							$m = Array('###FIELDNAME###'=> $fieldName);
							$txt = $this->langObj->getLLL($PCA['error']['duperror']);
							$errors['###ERROR_'.strtoupper($key).'###'] .= $myWrap[0].$this->lCObj->substituteMarkerArray($txt, $m).$myWrap[1];
						}
					}
					$myUnique = $PCA['conf'][$key]['required']['unique'];
					if ($myUnique) {
						// get count of values ...
						$select = 'uid,'.$key;
						$query = 'deleted=0 AND '.$key.'='.QT.addslashes(trim($row[$key])).QT;
						if (intval($row['uid'])>0) {
							$query .= ' AND uid!='.intval($row['uid']);
						}
						$uCnt = 0;
						$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$PCA['table'],$query,'','');
						if ($GLOBALS['TYPO3_DB']->sql_error()) {
							t3lib_div::debug(Array('ERROR'=>$GLOBALS['TYPO3_DB']->sql_error(),
									'$select'=>$select, '$PCA[table]'=>$PCA['table'], '$query'=>$query, 'File:Line'=>__FILE__.':'.__LINE__));
						} else {
							$uCnt = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
						}

						if (!$fieldHasError &&  $uCnt>0 ) {
							$errorMode = true; $fieldHasError = true;
							$m = Array('###FIELDNAME###'=> $fieldName);
							$txt = $this->langObj->getLLL($PCA['error']['uniqueerror']);
							$errors['###ERROR_'.strtoupper($key).'###'] .= $myWrap[0].$this->lCObj->substituteMarkerArray($txt, $m).$myWrap[1];
						}
					}
				}

			}
		}
		//t3lib_div::debug(Array('$errors'=>$errors, 'File:Line'=>__FILE__.':'.__LINE__));
		$errors['###ALL_ERRORS###'] = implode ('',$errors);
		if (strlen($errors['###ALL_ERRORS###'])>0 && strlen($PCA['error']['wrapAllErrors'])>1) {
			$myWrap = explode ('|',$PCA['error']['wrapAllErrors']);
			$errors['###ALL_ERRORS###'] = $myWrap[0].$errors['###ALL_ERRORS###'].$myWrap[1];
		}

		return ($errorMode);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$dbName: ...
	 * @param	[type]		$myPiVars: ...
	 * @param	[type]		$PCA: ...
	 * @return	[type]		...
	 */
	function saveRowToDb ($dbName,&$myPiVars,&$PCA) {
		$content = '';

		$PCA['todo']['Created'] = 0;
		$PCA['todo']['Saved'] = 0;

		$mySaveVars = Array();
		$myMM = Array();
		$iAmEditor = FALSE;
		if ($this->permitObj->allowed('unhideUserlist') && $PCA['ctrl']['crfeuser_id'] &&
				t3lib_div::inList($this->permitObj->allowed('unhideUserlist'),$myPiVars[$PCA['ctrl']['crfeuser_id']])) {
			$iAmEditor = TRUE;
		} else if ($this->permitObj->allowed('unhideCatlist') && t3lib_div::inList($this->permitObj->allowed('unhideCatlist'),
			$myPiVars[$this->permitObj->allowed('catlistCatfield')])) {
			$iAmEditor = TRUE;
		}
		if (is_array($myPiVars['data'])) for (reset($myPiVars['data']);$key=key($myPiVars['data']);next($myPiVars['data'])) {
			switch($PCA['conf'][$key]['type'])	{
				case 'imagelist':
				case 'filelist':
				case 'doclist':
				case 'pdflist':
					$mySaveVars[$key] =
						str_replace("\n",',',str_replace("\r",',',str_replace("\r\n",',',
							str_replace(",\r",',',str_replace(",\n",',',str_replace(','."\r\n",',',$myPiVars['data'][$key]))))));
				break;
				case 'date':
				case 'datetime':
					$mySaveVars[$key] = ($PCA['conf'][$key]['type']=='datetime') ?
							$this->dateTimeStringToTime($myPiVars['data'][$key]) : $this->dateStringToTime($myPiVars['data'][$key]);
					if (strcmp($key,'crdate')==0) {
						if ($mySaveVars[$key]<10000) {
							$mySaveVars[$key] = time();
						}
					} else if (strcmp($key,'tstamp')==0) {
						$mySaveVars[$key] = time();
					}
				break;
				case 'time':
					$mySaveVars[$key] = $this->timeStringToTime($myPiVars['data'][$key]);
				break;
				default:
					$mySaveVars[$key] = $myPiVars['data'][$key];
				break;
			}

			if (strlen($PCA['conf'][$key]['MM'])>0) {
				$myMM[$key] = $PCA['conf'][$key]['MM'];
			}
		}

		if (isset($PCA['conf']['tstamp'])) {
			$mySaveVars['tstamp'] = time();
		}

		if (intval($myPiVars['uid'])>0) {
			//$content .= 'Datensatz wird gespeichert<br />';
			$PCA['todo']['Uid'] = $myPiVars['uid'];
			$fields_values = Array();
			for (reset($mySaveVars);$key=key($mySaveVars);next($mySaveVars)) {
				if (!isset($PCA['conf'][$key]['dontsave']) || intval($PCA['conf'][$key]['dontsave'])==0) {
					$fields_values[$key] = $mySaveVars[$key];
				}
			}
			if (is_array($PCA['ctrl']['enablecolumns']) && isset($PCA['ctrl']['enablecolumns']['disabled'])) {
				$myHidden = intval($myPiVars['data'][ $PCA['ctrl']['enablecolumns']['disabled'] ]);
				//unset($myPiVars['data'][ $PCA['ctrl']['enablecolumns']['disabled'] ]);
				// Now Check, if user MAY hide/unhide a new record
				if (!$this->permitObj->allowed('admin') && !$iAmEditor && !$this->permitObj->allowed('unhideAll') && !$this->permitObj->allowed('unhideOwn')) {
					unset($myPiVars['data'][ $PCA['ctrl']['enablecolumns']['disabled'] ]);
				} else {
					$fields_values[$PCA['ctrl']['enablecolumns']['disabled']] = $myHidden;
					$myPiVars['data'][ $PCA['ctrl']['enablecolumns']['disabled'] ] = $myHidden;
				}
			}

			if ($this->checkForLogMail($PCA['mail'],1,0)) {
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',$dbName,'uid='.$myPiVars['uid']);
				if ($res) {
					$oldRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
				} else {
					$oldRow = Array();
					$oldRow['uid'] = $myPiVars['uid'];
				}
			}

			// Check if Hide/Unhide has changed
			$cul = $PCA['ctrl']['unhide_log'];
			if (intval($oldRow[$PCA['ctrl']['enablecolumns']['disabled']])!=intval($myPiVars['data'][$PCA['ctrl']['enablecolumns']['disabled']])
				&& isset($mySaveVars[$PCA['ctrl']['enablecolumns']['disabled']]) && is_array($cul) ) {
				$fields_values[$cul['time']] = time();
				$fields_values[$cul['feuser']] = intval($this->permitObj->getFeUid());
				$fields_values[$cul['beuser']] = intval($this->permitObj->getBeUid());
				$this->debugObj->debugIf('unhide_log',Array('$mySaveVars'=>$myPiVars['data'], '$oldRow'=>$oldRow,  'File:Line'=>__FILE__.':'.__LINE__));
			}

			$query =  'uid='.$myPiVars['uid'];
			$res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery($dbName,$query,$fields_values);

			if ($GLOBALS['TYPO3_DB']->sql_error()) {
				$PCA['todo']['Edit'] = 1;
				$PCA['todo']['Save'] = 1;
				t3lib_div::debug (Array('set'=>$fields_values, '$dbName'=>$dbName, 'query'=>$query, 'res'=>$res, 'error'=>$GLOBALS['TYPO3_DB']->sql_error()));
				$PCA['todo']['createerror'] = $GLOBALS['TYPO3_DB']->sql_error();
			} else {
				$this->clearCache($PCA);
				$this->debugObj->debugIf('sql',Array('set'=>$fields_values, '$dbName'=>$dbName, 'query'=>$query, 'res'=>$res, 'FILE:LINE='=>__FILE__.':'.__LINE__ ));
				//}
				//$PCA['todo']['Edit'] = 0;
				$PCA['todo']['Save'] = 0;
				$PCA['todo']['Saved'] = 1;

				$this->writelog('SGZLIB: table "'.$dbName.'" Record "'.$myPiVars['uid'].
					'" changed  (F'.$this->permitObj->getFeUid().'/B'.$this->permitObj->getBeUid().')',2);

				// OK - row was saved; now check if I have to send a log-mail
				if ($this->checkForLogMail($PCA['mail'],1,0)) {
					$this->sendLogMail($PCA,1,$mySaveVars,$oldRow,0);
				}
			}
		} else  {
			//$content .= 'Datensatz wird neu angelegt (File/Line: '.__FILE__.'/'.__LINE__.') !!!<br />';
			$fields_values = Array();
			$fields_values['pid'] = $PCA['pid'];

			unset ($mySaveVars['uid']);
			unset ($mySaveVars['pid']);
			if (isset($PCA['conf']['tstamp'])) {
				$mySaveVars['tstamp'] = time();
			}
			if (isset($PCA['conf']['crdate'])) {
				$mySaveVars['crdate'] = time();
			}

			if (isset($PCA['ctrl']['crfeuser_id']) && !($mySaveVars[$PCA['ctrl']['crfeuser_id']])) {
				$mySaveVars[$PCA['ctrl']['crfeuser_id']] = $this->permitObj->getFeUid();
			}
			if (isset($PCA['ctrl']['cruser_id']) && !$mySaveVars[$PCA['ctrl']['cruser_id']]) {
				$mySaveVars[$PCA['ctrl']['cruser_id']] = $this->permitObj->getBeUid();
			}

			if (is_array($PCA['ctrl']['enablecolumns']) && isset($PCA['ctrl']['enablecolumns']['disabled'])) {
				$myHidden = intval($myPiVars['data'][ $PCA['ctrl']['enablecolumns']['disabled'] ]);
				unset($mySaveVars[ $PCA['ctrl']['enablecolumns']['disabled'] ]);
				// Now Check, if user MAY unhide a new record
				if (!$this->permitObj->allowed('admin') && !$iAmEditor && !$this->permitObj->allowed('unhideAll') && !$this->permitObj->allowed('unhideOwn')) {
					$myHidden = 1;
				}
				$fields_values[$PCA['ctrl']['enablecolumns']['disabled']] = $myHidden;
			}
			for (reset($mySaveVars);$key=key($mySaveVars);next($mySaveVars)) {
				if (!isset($PCA['conf'][$key]['dontsave']) || intval($PCA['conf'][$key]['dontsave'])==0) {
					$fields_values[$key] = $mySaveVars[$key];
				}
			}

			$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery($dbName,$fields_values);
			if ($GLOBALS['TYPO3_DB']->sql_error() || !$res) {
				t3lib_div::debug (Array('$dbName'=>$dbName, '$fields_values'=>$fields_values, 'res'=>$res, 'error'=>$GLOBALS['TYPO3_DB']->sql_error()));
				$PCA['todo']['createerror'] = $GLOBALS['TYPO3_DB']->sql_error();
				$PCA['todo']['Edit'] = 1;
				$PCA['todo']['Save'] = 1;
				$PCA['todo']['Uid'] = 0;
				t3lib_div::debug(Array('error'=>1, 'File:Line'=>__FILE__.':'.__LINE__));
			} else {
				$tmpUid = $GLOBALS['TYPO3_DB']->sql_insert_id();
				$this->clearCache($PCA);
				$this->debugObj->debugIf('sql',Array('query'=>$query, 'res'=>$res,'FILE:LINE='=>__FILE__.':'.__LINE__ ));

				$PCA['todo']['Edit'] = 0;
				$PCA['todo']['Save'] = 0;
				$PCA['todo']['Uid'] = $tmpUid;
				$PCA['todo']['Created'] = 1;

				$this->writelog('SGZLIB: table "'.$dbName.'" NEW Record "'.$PCA['todo']['Uid'].
					'" created  (F'.$this->permitObj->getFeUid().'/B'.$this->permitObj->getBeUid().')',2);

				// OK - row was created; now check if I have to send a log-mail
				if ($this->checkForLogMail($PCA['mail'],0,0)) {
					$mySaveVars['uid'] = $PCA['todo']['Uid'];
					$this->sendLogMail($PCA,0,$mySaveVars,$mySaveVars,0);
				}
			}
		}

		// check if all is saved and MM relations have to be saved
		$myUid = intval($PCA['todo']['Uid']);
		if (intval($PCA['todo']['Edit'])<1 && $myUid>0) {
			for (reset($myMM);$key=key($myMM);next($myMM)) {
				// first delete all references for this uid
				$where = 'uid_local='.$myUid;
				$res = $GLOBALS['TYPO3_DB']->exec_DELETEquery($myMM[$key],$where);
				if ($GLOBALS['TYPO3_DB']->sql_error()) {
					t3lib_div::debug(Array('ERROR'=>$GLOBALS['TYPO3_DB']->sql_error(),
						'DELETE FROM'=>'', 'table'=>$myMM[$key], 'where'=>$where,
						'File:Line'=>__FILE__.':'.__LINE__));
				}
				if (strcmp($key,$this->debugMMField)==0) {
					t3lib_div::debug(Array('DELETE MM $key'=>$key, '$myMM[$key]'=>$myMM[$key], '$where'=>$where, 'File:Line'=>__FILE__.':'.__LINE__));
				}

				// then save all ids
				$tmp = t3lib_div::intExplode (',',$mySaveVars[$key]);
				if (strcmp($key,$this->debugMMField)==0) {
					t3lib_div::debug(Array('INSERT MM $key'=>$key, '$mySaveVars[$key]'=>$mySaveVars[$key], '$tmp'=>$tmp, 'File:Line'=>__FILE__.':'.__LINE__));
				}
				if (count($tmp)>1 || $tmp[0]!=0) {
					for ($i=0;$i<count($tmp);$i++) {
						$values = Array('uid_local'=>$myUid, 'uid_foreign'=>$tmp[$i], 'sorting'=>$i);
						$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery($myMM[$key],$values);
						if ($GLOBALS['TYPO3_DB']->sql_error()) {
							t3lib_div::debug(Array('ERROR'=>$GLOBALS['TYPO3_DB']->sql_error(),
								'DELETE FROM'=>'', 'table'=>$myMM[$key], 'values'=>$values,
								'File:Line'=>__FILE__.':'.__LINE__));
						}
						if (strcmp($key,$this->debugMMField)==0) {
							t3lib_div::debug(Array('$myMM[$key]'=>$myMM[$key], '$values'=>$values, 'File:Line'=>__FILE__.':'.__LINE__));
						}
					}
				}
			}
		}
		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$myDate: ...
	 * @return	[type]		...
	 */
	function dateTimeStringToTime($myDate) {
		$this->lastCheckError = FALSE;

		$out = 0;
		$parts = t3lib_div::trimExplode(' ',$myDate,2);
		$myDate = $this->dateStringToTime($parts[0]);
		if ($myDate) {
			$myTime = $this->timeStringToTime($parts[1]);
			if ($myTime) {
				$out = $myTime + $myDate;
			}
		}

		return ($out);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$myDate: ...
	 * @param	[type]		$hour: ...
	 * @param	[type]		$minute: ...
	 * @param	[type]		$second: ...
	 * @return	[type]		...
	 */
	function dateStringToTime($myDate,$hour=-1,$minute=0,$second=1) {
		$this->lastCheckError = FALSE;

		$out = 0;
		$hour = intval($hour);
		$md = array();
		$ok1 = preg_match('/^([0-9]{1,2})[\.\/-]([0-9]{1,2})[\.\/-]([0-9]{2,4})$/', $myDate, $md);

		$xy = substr($myDate,0,strpos($myDate,$md[0]));

		if ($ok1 && checkdate($md[2],$md[1],$md[3])) {
			if ($hour>=0 && $hour<24) {
				$out = mktime  ($hour,$minute,$second,$md[2],$md[1],$md[3],0);
			} else {
				$out = mktime (intval($this->conf['defaultHour']),0,1,$md[2],$md[1],$md[3],0);
			}
		} else {
			$this->lastCheckError = 'Date Error: '.$myDate;
		}

		//t3lib_div::debug(Array('error'=>$this->lastCheckError, '$myDate'=>$myDate, '$md'=>$md, '$ok1'=>$ok1, 'xy'=>$xy, '$out'=>$out, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($out);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$myTime: ...
	 * @return	[type]		...
	 */
	function timeStringToTime($myTime) {
		$this->lastCheckError = FALSE;
		$out = '';

		$md = array();
		$ok1 = preg_match('/^([0-9]{1,2})[\:]([0-9]{1,2})$/', $myTime, $md);
		if ($ok1 && !($md[1]>23 || $md[2]>59 || $md[1]<0 || $md[2]<0)) {
			$out = mktime  ($md[1]+1,$md[2],0,1,1,1970);
		} else {
			$this->lastCheckError = 'Time Error: '.$myTime;
		}
		//t3lib_div::debug(Array('error'=>$this->lastCheckError, '$myTime'=>$myTime, '$md'=>$md, '$ok1'=>$ok1, '$out'=>$out, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($out);
	}


	function formatDate ($myTime) {
		$format = $this->conf['formats.']['date'] ? $this->conf['formats.']['date'] : 'd.m.Y';
		return (gmdate($format,$myTime+43200));
	}

	function formatDateTime ($myTime) {
		$format = $this->conf['formats.']['datetime'] ? $this->conf['formats.']['datetime'] : 'd.m.Y H:i';
		return (gmdate($format,$myTime));
	}

	function formatTime ($myTime) {
		$format = $this->conf['formats.']['time'] ? $this->conf['formats.']['time'] : 'H:i';
		return (gmdate($format,$myTime));
	}



	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$myDate: ...
	 * @param	[type]		$myFName: ...
	 * @return	[type]		...
	 */
	function dateCompareString ($myDate,$myFName) {
		$out = 0;
		$md = array();
		$ok1 = ereg('([0-9]{1,2})[\.\/-]([0-9]{1,2})[\.\/-]([0-9]{2,4})', $myDate, $md);
		if ($ok1) {
			$xy = trim(substr($myDate,0,strpos($myDate,$md[0])));
			switch($xy)	{
				case '':
				case '=':
					$out = '=';
					$myDate = mktime (0,0,0,$md[2],$md[1],$md[3],0);
					$query = $myFName.'>='.mktime (0,0,0,$md[2],$md[1],$md[3],0).' AND '.$myFName.'<='.mktime (23,59,59,$md[2],$md[1],$md[3],0);
				break;
				case '=<':
				case '<=':
					$out = '<=';
					$myDate = mktime (23,59,59,$md[2],$md[1],$md[3],0);
					$query = $myFName.'<='.mktime (23,59,59,$md[2],$md[1],$md[3],0);
				break;
				case '=>':
				case '>=':
					$out = '>=';
					$myDate = mktime (0,0,0,$md[2],$md[1],$md[3],0);
					$query = $myFName.'>='.mktime (0,0,0,$md[2],$md[1],$md[3],0);
				break;
				case '>':
					$out = '>';
					$myDate = mktime (23,59,59,$md[2],$md[1],$md[3],0);
					$query = $myFName.'>'.mktime (23,59,59,$md[2],$md[1],$md[3],0);
				break;
				case '<':
					$out = '<';
					$myDate = mktime (0,0,0,$md[2],$md[1],$md[3],0);
					$query = $myFName.'<'.mktime (0,0,0,$md[2],$md[1],$md[3],0);
				break;
			}
		} else {
			$ok1 = ereg('([0-9]{1,2})[\.\/-]([0-9]{2,4})', $myDate, $md);
			if ($ok1) {
				$xy = trim(substr($myDate,0,strpos($myDate,$md[0])));
				switch($xy)	{
					case '':
					case '=':
						$out = '=';
						$myDate = mktime (0,0,0,$md[1],1,$md[2],0);
						$query = $myFName.'>='.mktime (0,0,0,$md[1],1,$md[2],0).' AND '.$myFName.'<='.mktime (23,59,59,$md[1]+1,-1,$md[2],0);
					break;
					case '=<':
					case '<=':
						$out = '<=';
						$myDate = mktime (23,59,59,$md[1]+1,-1,$md[2],0);
						$query = $myFName.'<='.mktime (23,59,59,$md[1]+1,-1,$md[2],0);
					break;
					case '=>':
					case '>=':
						$out = '>=';
						$myDate = mktime (0,0,0,$md[1],1,$md[2],0);
						$query = $myFName.'>='.mktime (0,0,0,$md[1],1,$md[2],0);
					break;
					case '>':
						$out = '>';
						$myDate = mktime (23,59,59,$md[1]+1,-1,$md[2],0);
						$query = $myFName.'>'.mktime (23,59,59,$md[1]+1,-1,$md[2],0);
					break;
					case '<':
						$out = '<';
						$myDate = mktime (0,0,0,$md[1],1,$md[2],0);
						$query = $myFName.'<'.mktime (0,0,0,$md[1],1,$md[2],0);
					break;
				}
			} else {
				$ok1 = ereg('([0-9]{4})', $myDate, $md);
				if ($ok1) {
					$xy = trim(substr($myDate,0,strpos($myDate,$md[0])));
					switch($xy)	{
						case '':
						case '=':
							$out = '=';
							$myDate = mktime (0,0,0,1,1,$md[1],0);
							$query = $myFName.'>='.mktime (0,0,0,1,1,$md[1],0).' AND '.$myFName.'<='.mktime (23,59,59,12,31,$md[1],0);
						break;
						case '=<':
						case '<=':
							$out = '<=';
							$myDate = mktime (23,59,59,12,31,$md[1],0);
							$query = $myFName.'<='.mktime (23,59,59,12,31,$md[1],0);
						break;
						case '=>':
						case '>=':
							$out = '>=';
							$myDate = mktime (0,0,0,1,1,$md[1],0);
							$query = $myFName.'>='.mktime (0,0,0,1,1,$md[1],0);
						break;
						case '>':
							$out = '>';
							$myDate = mktime (23,59,59,12,31,$md[1],0);
							$query = $myFName.'>'.mktime (23,59,59,12,31,$md[1],0);
						break;
						case '<':
							$out = '<';
							$myDate = mktime (0,0,0,1,1,$md[1]+1,0);
							$query = $myFName.'<'.mktime (0,0,0,1,1,$md[1]+1,0);
						break;
					}
				}
			}
			$out = '=';
			$myDate = 0;
		}

		if (strlen($query)<3) {
			$query = '1=2';
		}
		return ($query);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$mailConf: ...
	 * @param	[type]		$mailMode: ...
	 * @param	[type]		$dbg: ...
	 * @return	[type]		...
	 */
	function checkForLogMail ($mailConf,$mailMode,$dbg) {
		$retcode = false;
		if (is_array($mailConf)) {
			$sub = '';
			if ($mailMode==0) {
				$sub = 'new';
			} else if ($mailMode==1) {
				$sub = 'change';
			} else if ($mailMode==2) {
				$sub = 'visibility';
			} else if ($mailMode==3) {
				$sub = 'delete';
			}

			if ($sub) {
				$userList = (strlen($mailConf[$sub]['userlist'])>1) ? $mailConf[$sub]['userlist'] :
						(	(strlen($mailConf['userlist'])>1) ? $mailConf['userlist'] : ''  );
				$groupList = (strlen($mailConf[$sub]['grouplist'])>1) ? $mailConf[$sub]['grouplist'] :
						(	(strlen($mailConf['grouplist'])>1) ? $mailConf['grouplist'] : ''  );
				$beUserList = (strlen($mailConf[$sub]['beUserList'])>1) ? $mailConf[$sub]['beUserList'] :
						(	(strlen($mailConf['beUserList'])>1) ? $mailConf['beUserList'] : ''  );

				if (intval($mailConf[$sub]['mode'])==1) {
					$this->debugObj->debugIf('logmail',Array('OK'=>'LogMail because '.$sub.'==1', 'File:Line'=>__FILE__.':'.__LINE__));
					$retcode = true;
				} else if (intval($mailConf[$sub]['mode'])==2) {
					if (!t3lib_div::inList($userList,$this->feUser['username']) && !t3lib_div::inList($userList,$this->permitObj->getFeUid())) {
						if (!t3lib_div::inList($beUserList,$this->beUser['username']) && (!t3lib_div::inList($beUserList,$this->permitObj->getBeUid()) || !$this->permitObj->getBeUid())) {
							$this->debugObj->debugIf('logmail',Array('OK'=>'LogMail because '.$sub.'==2', 'File:Line'=>__FILE__.':'.__LINE__));
							$retcode = true;
						}
					}
				} else if (intval($mailConf[$sub]['mode'])==3) {
					$retcode = false;
					if (t3lib_div::inList($userList,$this->feUser['username']) || t3lib_div::inList($userList,$this->permitObj->getFeUid()) ||
						t3lib_div::inList($beUserList,$this->beUser['username']) || t3lib_div::inList($beUserList,$this->permitObj->getBeUid()) ||
						strcmp($userList,'*')==0) {
							$this->debugObj->debugIf('logmail',Array('OK'=>'LogMail because '.$sub.'==3 (user)'));
							$retcode = true;
					} else if ($this->permitObj->checkFeUserIn('',$groupList) || strcmp($groupList,'*')==0) {
							$this->debugObj->debugIf('logmail',Array('OK'=>'LogMail because '.$sub.'==3 (group)'));
							$retcode = true;
					}
				}
			}
		}

		if (!$retcode) {
			$this->debugObj->debugIf('logmail',Array('$mailConf'=>$mailConf, 'sub='.$sub=>$mailMode, 'username='=>$this->feUser['username'], 'feUid='=>$this->permitObj->getFeUid(), '$userList'=>$userList, '$groupList'=>$groupList , 'File:Line'=>__FILE__.':'.__LINE__));
		}
		return ($retcode);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$mailMode: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$oldrow: ...
	 * @param	[type]		$dbg: ...
	 * @return	[type]		...
	 */
	function sendLogMail ($PCA,$mailMode,$row,$oldrow=Array(),$dbg=0) {
		$retcode = false;
		$mailConf = $PCA['mail'];
		$m = Array();

		if ($dbg) {
			t3lib_div::debug(Array('OK'=>'Sendig Logmail; mailMode='.$mailMode, '$mailConf'=>$mailConf, 'File:Line'=>__FILE__.':'.__LINE__));
		} else {
			$this->debugObj->debugIf('logmail',Array('OK'=>'Sendig Logmail; mailMode='.$mailMode, '$mailConf'=>$mailConf, 'File:Line'=>__FILE__.':'.__LINE__));
		}
		$sub = '';
		if ($mailMode==0) {
			$sub = 'new';
			$cut = intval($mailConf['cut'])>10 ? intval($mailConf['cut']) : 50;
			$cut = intval($mailConf[$sub]['cut'])>10 ? intval($mailConf[$sub]['cut']) : $cut;
			$m['###list###'] = '';
			for (reset($row);$key=key($row);next($row)) {
				$m['###list###'] .= (strlen($key)>16 ? $key : substr($key.'                ',0,16) ).' = "'.substr($row[$key],0,$cut).'"'.CRLF;
			}
		} else if ($mailMode==1) {
			$sub = 'change';
			$cut = intval($mailConf['cut'])>10 ? intval($mailConf['cut']) : 30;
			$cut = intval($mailConf[$sub]['cut'])>10 ? intval($mailConf[$sub]['cut']) : $cut;
			$m['###list###'] = '';
			for (reset($row);$key=key($row);next($row)) if(strcmp($key,'tstamp')) if (strcmp(''.$row[$key],''.$oldrow[$key]))  {
				$m['###list###'] .= (strlen($key)>16 ? $key : substr($key.'                ',0,16) ).' = '.
					substr('"'.substr(''.$row[$key],0,$cut).'"                                           ',0,$cut+2).' <-- "'.substr(''.$oldrow[$key],0,$cut).'"'.CRLF;
			}
		} else if ($mailMode==2) {
			$sub = 'visibility';
		} else if ($mailMode==3) {
			$sub = 'delete';
		}

		if ($this->beEditor>0) {
			$m['###username###'] = '*'.$this->beUser['username'].'*';
			$m['###user###'] = '*'.$this->beUser['realName'].'*';
		} else {
			$m['###username###'] = $this->feUser['username'];
			$m['###user###'] = (strlen($this->feUser['firstname']) ? $this->feUser['firstname'].' ':'').$this->feUser['name'];
		}
		$m['###mode###'] = $sub;
		$m['###table###'] = $PCA['table'];
		$m['###date###'] = date('d.m.Y H:i');
		$m['###datestring###'] = date('Ymd-His');
		for (reset($row);$key=key($row);next($row)) {
			$m['###'.$key.'###'] = $row[$key];
		}

		if ($sub && ($mailMode!=1 || $m['###list###'])) {
			$subject = (strlen($mailConf[$sub]['subject'])>1) ? $mailConf[$sub]['subject'] :
						(	(strlen($mailConf['subject'])>1) ? $mailConf['subject'] :
							'###datestring### - LogMail(###mode###) in ###table### uid=###uid###'  );
			$subject = $this->lCObj->substituteMarkerArray($subject,$m);


			if (strlen($mailConf[$sub]['mailbody']['file'])>1) {
				$mailbody = $this->lCObj->cObjGetSingle('FILE',$mailConf[$sub]['mailbody']);
			} else if (strlen($mailConf[$sub]['mailbody'])>1) {
				$mailbody = $mailConf[$sub]['mailbody'];
			} else if (strlen($mailConf['mailbody']['file'])>1) {
				$mailbody = $this->lCObj->cObjGetSingle('FILE',$mailConf['mailbody']);
			} else if (strlen($mailConf['mailbody'])>1) {
				$mailbody = $mailConf['mailbody'];
			}

			if (!$mailbody) {
				$mailbody = $mailMode==0   ?
				          '###date###'.CRLF.'In Table "###table###" a new Record was created with uid="###uid###"'.CRLF  :
				          '###date###'.CRLF.'In Table "###table###" a Record with uid="###uid###" was changed:'.CRLF.'uid = "###uid###"'.CRLF ;
			}
			$this->debugObj->debugIf('mailmarker',Array('mailmarker logmail:'=>$m, 'File:Line'=>__FILE__.':'.__LINE__));
			$mailbody = $this->lCObj->substituteMarkerArray($mailbody,$m);


			$mailfrom = (strlen($mailConf[$sub]['mailfrom'])>1) ? $mailConf[$sub]['mailfrom'] :
						(	(strlen($mailConf['mailfrom'])>1) ? $mailConf['mailfrom'] : 'unknown@unknown.xy'  );
			$replyto = (strlen($mailConf[$sub]['replyto'])>1) ? $mailConf[$sub]['replyto'] :
						(	(strlen($mailConf['replyto'])>1) ? $mailConf['replyto'] : $mailfrom  );
			$returnpath = (strlen($mailConf[$sub]['returnpath'])>1) ? $mailConf[$sub]['returnpath'] :
						(	(strlen($mailConf['returnpath'])>1) ? $mailConf['returnpath'] : $mailfrom  );
			$mailto = (strlen($mailConf[$sub]['mailto'])>1) ? $mailConf[$sub]['mailto'] :
						(	(strlen($mailConf['mailto'])>1) ? $mailConf['mailto'] : ''  );

			if (strlen($mailto)>0) {
				$hd = "From: ".$mailfrom."\r\n"
					."Reply-To: ".$replyto."\r\n"
					."Return-Path: ".$returnpath."\r\n"
					."X-Mailer: PHP/".phpversion();
				$result = $this->sendMail($mailto,$subject,$mailbody,$hd,"-f".$returnpath	);
				if ($dbg || !$result) {
					t3lib_div::debug(Array('NOTE'=>'LogMail was sent (Result='.$result.')',
						'$mailfrom'=>$mailfrom, '$replyto'=>$replyto ,'$returnpath'=>$returnpath,
						'$subject'=>$subject, '$mailbody'=>$mailbody, '$headers'=>$hd, 'File:Line'=>__FILE__.':'.__LINE__));
				} else {
					$this->debugObj->debugIf('logmail',Array('NOTE'=>'LogMail was sent (Result='.$result.')',
						'$mailfrom'=>$mailfrom, '$replyto'=>$replyto ,'$returnpath'=>$returnpath,
						'$subject'=>$subject, '$mailbody'=>$mailbody, '$headers'=>$hd, 'File:Line'=>__FILE__.':'.__LINE__));
				}
			} else {
				t3lib_div::debug(Array('ERROR'=>'mailto was not set !', '$mailfrom'=>$mailfrom, '$replyto'=>$replyto ,'$returnpath'=>$returnpath,
					'$subject'=>$subject, '$mailbody'=>$mailbody, 'File:Line'=>__FILE__.':'.__LINE__));
			}

		}

		return ($retcode);
	}

	/***********************************************************************************************
	 *
	 * Button Functions
	 *
	 ***********************************************************************************************/


	/**
	 * @param	[type]		$jsCode: ...
	 * @param	[type]		$hrefCode: ...
	 * @param	[type]		$bigMode: ...
	 * @param	[type]		$lockMode: ...
	 * @param	[type]		$message: ...
	 * @return	[type]		...
	 */
	function getAddButton ($jsCode='',$hrefCode='',$bigMode=0,$lockMode=0,$message='') {
		if (!$hrefCode) { $hrefCode = $this->emptyUrl; }

		if ($this->permitObj->allowed('addEntry')) {
			if ($lockMode) {
				$retcode =  $this->constObj->getIcon('add_locked') ;
			} else {
				$icon = $this->constObj->getIcon('add');
				if (strlen($jsCode)>0) {
					$retcode = '<a href="'.$hrefCode.'" onclick="'.$jsCode.' return false;">'.$icon.'</a>';
				} else {
					$retcode = '<a href="'.$hrefCode.'">'.$icon.'</a>';
				}
			}
		} else {
			$retcode = $this->constObj->getIcon('add_disabled');
		}
		return ($retcode);
	}

	/**
	 * @param	[type]		$jsCode: ...
	 * @param	[type]		$hrefCode: ...
	 * @param	[type]		$bigMode: ...
	 * @param	[type]		$message: ...
	 * @return	[type]		...
	 */
	function getAddEntryButton ($jsCode='',$hrefCode='',$lockMode=0,$message='') {
		if (!$hrefCode) { $hrefCode = $this->emptyUrl; }
		if ($this->permitObj->allowed('addEntry')) {
			if ($lockMode) {
				$retcode = $this->constObj->getButton('addEntryLocked');
			} else {
				if (strlen($jsCode)>0) {
					$retcode = '<a href="'.$hrefCode.'" onclick="'.$jsCode.' return false;">'.$this->constObj->getButton('addEntry').'</a>';
				} else {
					$retcode = '<a href="'.$hrefCode.'">'.$this->constObj->getButton('addEntry').'</a>';
				}
			}
		} else {
			$retcode = '';
		}
		return ($retcode);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$jsCode: ...
	 * @param	[type]		$hrefCode: ...
	 * @param	[type]		$bigMode: ...
	 * @param	[type]		$myHiddenState: ...
	 * @param	[type]		$myOwner: ...
	 * @param	[type]		$myLocked: ...
	 * @param	[type]		$editAsNew: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$isLocked: ...
	 * @return	[type]		...
	 */
	function getEditButton ($jsCode='',$hrefCode='',$bigMode=0,$myHiddenState,$myOwner,$myLocked=0,$editAsNew=0,$row=Array(),$isLocked=Array()) {
		if (!$hrefCode) { $hrefCode = $this->emptyUrl; }

		$msg = '';
		if (strlen($isLocked['msg'])) {
			$msg = $this->constObj->getIcon('warning');
			$msg = str_replace('title=""','title="'.$isLocked['msg'].'"',$msg);
		}

		$this->debugObj->debugIf('allow',Array('editAll'=>$this->permitObj->allowed('editAll'),
						'or ( editOwn'=>$this->permitObj->allowed('editOwn'),
						'and myOwner==user(uid))'=>$myOwner.'=='.$this->feUser['uid'],
						'File:Line'=>__FILE__.':'.__LINE__));

		if ($editAsNew) {
			if ($this->permitObj->allowed('addEntry') && ($this->permitObj->allowed('editAll') || ($this->permitObj->allowed('editOwn') && $myOwner==$this->feUser['uid']))) {
				// basically Editing is allowed
				$icon = $this->constObj->getIcon('editAsNew');
				if (strlen($jsCode)>0) {
					$retcode = '<a href="'.$hrefCode.'" onclick="'.$jsCode.' return false;">'.$icon.'</a>';
				} else {
					$retcode = '<a href="'.$hrefCode.'">'.$icon.'</a>';
				}
			} else {
				// Editing is NOT allowed
				$retcode = $this->constObj->getIcon('editAsNew_disabled');
			}
		} else {
			$iAmEditor = FALSE;
			if ($this->permitObj->allowed('editUserlist') && t3lib_div::inList($this->permitObj->allowed('editUserlist'),$myOwner)) {
				$iAmEditor = TRUE;
			} else if ($this->permitObj->allowed('editCatlist') && t3lib_div::inList($this->permitObj->allowed('editCatlist'),$row[$this->permitObj->allowed('catlistCatfield')])) {
				$iAmEditor = TRUE;
			}
			if ($this->permitObj->allowed('editAll') || $iAmEditor || ($this->permitObj->allowed('editOwn') && $myOwner==$this->feUser['uid'])) {
				// basically Editing is allowed
				if (($myLocked || ($this->permitObj->allowed('editOnlyHidden') && !$myHiddenState)) && !$this->permitObj->allowed('admin') && !$iAmEditor) {
					// Editing is NOT allowed, because Record is locked for nonAdmins
					$retcode = $this->constObj->getIcon('edit_locked');
				} else {
					$icon = $this->constObj->getIcon('edit');
					if (strlen($jsCode)>0) {
						$retcode = '<a href="'.$hrefCode.'" onclick="'.$jsCode.' return false;">'.$icon.'</a>'.$msg;
					} else {
						$retcode = '<a href="'.$hrefCode.'">'.$icon.'</a>'.$msg;
					}
				}
			} else {
				// Editing is NOT allowed
				$retcode = $this->constObj->getIcon('edit_disabled');
			}
		}
		return ($retcode);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$jsCode: ...
	 * @param	[type]		$hrefCode: ...
	 * @param	[type]		$bigMode: ...
	 * @param	[type]		$myHiddenState: ...
	 * @param	[type]		$myOwner: ...
	 * @return	[type]		...
	 */
	function getXtraEditButton ($jsCode='',$hrefCode='',$bigMode=0,$myHiddenState,$myXtraUser) {
		if (!$hrefCode) { $hrefCode = $this->emptyUrl; }

		if (strlen($this->feUser['uid'])>0 && $myXtraUser==$this->feUser['uid']) {
			$icon = $this->constObj->getIcon('edit');
			if (strlen($jsCode)>0) {
				$retcode = '<a href="'.$hrefCode.'" onclick="'.$jsCode.' return false;">'.$icon.'</a>';
			} else {
				$retcode = '<a href="'.$hrefCode.'">'.$icon.'</a>';
			}
		} else {
			$retcode = '';
		}
		return ($retcode);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$jsCode: ...
	 * @param	[type]		$hrefCode: ...
	 * @param	[type]		$bigMode: ...
	 * @param	[type]		$myHiddenState: ...
	 * @param	[type]		$myOwner: ...
	 * @param	[type]		$params: ...
	 * @return	[type]		...
	 */
	function getViewButton ($jsCode='',$hrefCode='',$bigMode=0,$myHiddenState,$myOwner,$params=Array()) {
		if (!$hrefCode) { $hrefCode = $this->emptyUrl; }

		if ((!$this->permitObj->allowed('showOnlyOwnDetails') && !$this->permitObj->allowed('showOnlyOwnEntries')) || ($this->feUser['uid'] && $myOwner==$this->feUser['uid'])) {
			$icon = $this->constObj->getIcon('view');
			if (strlen($jsCode)>0) {
				$retcode = '<a href="'.$hrefCode.'" onclick="'.$jsCode.' return false;">'.$icon.'</a>';
			} else {
				$retcode = '<a href="'.$hrefCode.'"'.
					($params['target'] ? ' target="'.$params['target'].'"' : '').
					'>'.$icon.'</a>';
			}
		} else {
			$retcode = $this->constObj->getIcon('view_disabled');
		}

		return ($retcode);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$jsCode: ...
	 * @param	[type]		$hrefCode: ...
	 * @param	[type]		$title: ...
	 * @param	[type]		$myHiddenState: ...
	 * @param	[type]		$myOwner: ...
	 * @param	[type]		$params: ...
	 * @return	[type]		...
	 */
	function getViewTitle ($jsCode='',$hrefCode='',$title,$myHiddenState,$myOwner,$params=Array()) {
		if (!$hrefCode) { $hrefCode = $this->emptyUrl; }
		if ((!$this->permitObj->allowed('showOnlyOwnDetails') && !$this->permitObj->allowed('showOnlyOwnEntries')) || ($this->feUser['uid']>0 && $myOwner==$this->feUser['uid'])) {
			$retcode = '<a href="'.$hrefCode.'"';
			if (strlen($jsCode)>0) {
				$retcode .= ' onclick="'.$jsCode.' return false;"';
			} else {
				$retcode .= ($params['target'] ? ' target="'.$params['target'].'"' : '');
			}
			$retcode .= ($params['ATagParams'] ? ' '.$params['ATagParams'] : '').
						'>'.$title.'</a>';
		} else {
			$retcode = $title;
		}

		return ($retcode);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getFormSaveButton() {
		if ($this->constObj->buttonExists('formsave')) {
			$out = '<a href="#" onclick="document.sg_editform.submit(); return false;">'.$this->constObj->getButton('formsave','Save').'</a>';
		} else {
			if ($this->constObj->isIconTypeText('save')) {
				$out = '<input type="submit" value="'.$this->constObj->getIcon('save').'"/>';
			} else {
				$out = '<input title="'.$this->langObj->getLL('saveexit').'" type="image" src="'.
					$this->constObj->getIconResource('save').'"/>';
			}
		}
		return ($out);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getFormReloadButton() {
		return ('<img title="'.$this->langObj->getLL('reload').'" border="0" type="image" '.
			' src="'.$this->constObj->getIconResource('reload').'"/>' );
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getFormUpdateButton() {
		return ('<img title="'.$this->langObj->getLL('update').'" border="0" type="image" '.
			' src="'.$this->constObj->getIconResource('update').'"/>' );
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$myPage: ...
	 * @param	[type]		$entryParam: ...
	 * @param	[type]		$closeMode: ...
	 * @return	[type]		...
	 */
	function getFormCancelButton($myPage,$entryParam,$closeMode=0) {
		if (!$closeMode) {
			if ($this->constObj->buttonExists('formcancel')) {
				$href = $this->constObj->getButton('formcancel','Cancel');
			} else {
				$href = $this->constObj->getIcon('cancel');
			}
			return ('<a href="'.$myPage.$entryParam.'">'.$href.'</a>');
		} else {
			return ('<a href="#" onclick="javascript:window.close();">'.$this->constObj->getIcon('cancel').'</a>');
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$myState: ...
	 * @param	[type]		$myPage: ...
	 * @param	[type]		$uid: ...
	 * @param	[type]		$wordparam: ...
	 * @param	[type]		$myOwner: ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$noText: ...
	 * @param	[type]		$PCA: ...
	 * @return	[type]		...
	 */
	function getHiddenStateSwitch ($myState,$myPage,$uid,$wordparam,$myOwner,$row=Array(),$noText=0,$PCA=Array()) {
		GLOBAL $TSFE;
		$myFixed = 0;
		$iAmEditor = FALSE;
		if ($this->permitObj->allowed('unhideUserlist') && t3lib_div::inList($this->permitObj->allowed('unhideUserlist'),$myOwner)) {
			$iAmEditor = TRUE;
		} else if ($this->permitObj->allowed('unhideCatlist') && t3lib_div::inList($this->permitObj->allowed('unhideCatlist'),$row[$this->permitObj->allowed('catlistCatfield')])) {
			$iAmEditor = TRUE;
		}
		if (isset($this->localPCA['ctrl']['fixedField'])) {
			if (intval($row[$this->localPCA['ctrl']['fixedField']])>0) {
				$myFixed = 1;
			}
		}
		if ($this->permitObj->allowed('unhideAll') || $iAmEditor
			|| ($this->permitObj->allowed('unhideOwn') && $myOwner==$this->feUser['uid'])
			|| ($this->permitObj->allowed('unhideFixedOwn') && $myOwner==$this->feUser['uid'] && $myFixed) ) {
			if ($myState==1) {
				$retcode = ($noText ? '' : $this->constObj->getWrap('hot',$this->langObj->getLL('hidden')).' ').
					'<a href="'.$myPage.'&type='.$TSFE->type.'&doUnHide=1'.$wordparam.'&uid='.$uid.'">'.$this->constObj->getIcon('dounhide').'</a>';
			} else {
				$retcode = ($noText ? '' : $this->getHiddenText($PCA,$row).' ').
					'<a href="'.$myPage.'&type='.$TSFE->type.'&doHide=1'.$wordparam.'&uid='.$uid.'">'.$this->constObj->getIcon('dohide').'</a>';
			}
		} else {
			if ($myState==1) {
				$retcode = ($noText ? '' : $this->constObj->getWrap('hot',$this->langObj->getLL('hidden')).' ').
					$this->constObj->getIcon('dounhide_disabled');
			} else {
				$retcode = ($noText ? '' : $this->getHiddenText($PCA,$row).' ').
					$this->constObj->getIcon('dohide_disabled').'';
			}
		}

		return ($retcode);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$myHiddenState: ...
	 * @param	[type]		$myPage: ...
	 * @param	[type]		$uid: ...
	 * @param	[type]		$wordparam: ...
	 * @param	[type]		$myOwner: ...
	 * @param	[type]		$myParams: ...
	 * @param	[type]		$row: ...
	 * @return	[type]		...
	 */
	function getDeleteButton($myHiddenState,$myPage,$uid,$wordparam,$myOwner,$myParams='',$row=Array()) {
		GLOBAL $TSFE;
		$iAmEditor = FALSE;
		if ($this->permitObj->allowed('deleteUserlist') && t3lib_div::inList($this->permitObj->allowed('deleteUserlist'),$myOwner)) {
			$iAmEditor = TRUE;
		} else if ($this->permitObj->allowed('deleteCatlist') && t3lib_div::inList($this->permitObj->allowed('deleteCatlist'),$row[$this->permitObj->allowed('catlistCatfield')])) {
			$iAmEditor = TRUE;
		}
		$myFixed = 0;
		if (isset($this->localPCA['ctrl']['fixedField'])) {
			if (intval($row[$this->localPCA['ctrl']['fixedField']])>0) {
				$myFixed = 1;
			}
		}
		$q="'";
		if ($this->permitObj->allowed('deleteEntry') || $iAmEditor || (!$myFixed && $this->permitObj->allowed('deleteOwn') && $myOwner==$this->feUser['uid'])) {
			if ($myHiddenState==1) {
				$retcode = '<a href="'.$this->emptyUrl.'" onclick="doDeleteEntry('.
					$this->quote($myPage.'&type='.$TSFE->type.'&doDelete=1'.$wordparam,1).$uid.','.
						$this->quote($this->langObj->getLL('doreallydelete'),1).$this->quote($myParams,0).
					'); return false;">'.$this->constObj->getIcon('dodelete').'</a>';
			} else {
			$retcode = $this->constObj->getIcon('dodelete_unhidden');
			}
		} else {
			$retcode = $this->constObj->getIcon('dodelete_disabled');
		}

		return ($retcode);
	}



	/***********************************************************************************************
	 *
	 * Functions vor JavaScript FE-Editing and Searchforms
	 *
	 ***********************************************************************************************/

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$listmode: ...
	 * @param	[type]		$mode: ...
	 * @param	[type]		$winparams: ...
	 * @return	[type]		...
	 */
	function addFileLink ($PCA,$field,$listmode='unknown',$mode=0,$row=Array()) {
		// $mode==1 => Delete-Mode ok
		// $mode==2 => No Upload !!

		$q="'";
		if (!$this->permitObj->allowed('uploadImage')) {
			$mode=2;
		}
		if (!$this->permitObj->allowed('deleteImage')) {
			$mode = ($mode & 2);
		}
		if (strlen($PCA['conf'][$field]['uploadfolder'])<2) { return ('??'); }

		$bn = 'add';
		if (intval($PCA['conf'][$field]['maxitems'])==1) {
			$bn = 'set';
		}

		$maxsize = 0;
		$maxsize = intval($PCA['conf'][$field]['max_size']);

		$myBrowser = $this->browser.'&id='.$GLOBALS['TSFE']->id.'&mode=fb';
		if (strlen($PCA['conf'][$field]['browser'])>0) { $myBrowser .= '&set='.$PCA['conf'][$field]['browser']; }
		$myBrowser .= '&ext='.$PCA['name'].'&vn='.$field;
		$winparams = $PCA['conf'][$field]['browserWinParams'];
		$params = Array('uploadfolder'=>$PCA['conf'][$field]['uploadfolder'],
			'maxSize'=>$maxsize,
			'maxItems'=>intval($PCA['conf'][$field]['maxitems']),
			'listmode'=>$listmode, 'imageallow'=>$mode,
			'noAutoClose'=>(intval($PCA['conf'][$field]['noAutoClose'])) ,
			'rename'=>$PCA['conf'][$field]['fileRename'] );
		if ($bn=='set') {
			$params['replace'] = 1;
		}

		if ($this->permitObj->allowed('onlyOwnImages')) {
			$params['own'] = $this->feUser['uid'].'-';
			if (strlen($params['own'])<6) {
				$params['own'] = substr('000000'.$params['own'],-6);
			}
		}
		//t3lib_div::debug(Array('uid'=>$this->feUser['uid'], 'ownerPart'=>$params['own']));
		return ('<a href="'.$this->emptyUrl.'" onclick="addFromBrowser('.
			$q.$myBrowser.$q.','. // this is defined in ts_setup of zlib
			$q.urlencode(serialize($params)).$q.','.
			$q.$winparams.$q.
			');'.
			' return false;">'.$this->constObj->getIcon($bn).'</a>'   );

	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$mode: ...
	 * @param	[type]		$row: ...
	 * @return	[type]		...
	 */
	function addDbLink ($PCA,$field,$mode,$row=Array()) {
		// $mode==1 => Delete-Mode ok
		// $mode==2 => No Upload !!
		global $TCA;

		$bn = 'add';
		if ($PCA['conf'][$field]['type']=='selectsingle' ||
			($PCA['conf'][$field]['type']=='selectmulti' && intval($PCA['conf'][$field]['maxitems'])==1) ) {
			$bn = 'set';
		}
		$myBrowser = $this->browser.'&id='.$GLOBALS['TSFE']->id.'&mode=db';
		if (strlen($PCA['conf'][$field]['browser'])>0) { $myBrowser .= '&set='.$PCA['conf'][$field]['browser']; }
		$myBrowser .= '&ext='.$PCA['name'].'&vn='.$field;

		$winparams = $PCA['conf'][$field]['browserWinParams'];

		$refTable = $PCA['conf'][$field]['foreign_table'];
		if (strlen($refTable)<1) {
					$refTable = $PCA['conf'][$field]['allowed'];
		}
		if ($refTable) {
			t3lib_div::loadTCA($refTable);
		}
		$params = Array('mode' => $mode, 'foreign_table' => $refTable, 'maxItems'=>intval($PCA['conf'][$field]['maxitems']));
		if ($bn=='set') {
			$params['replace'] = 1;
		}
		$params['field'] = $field;
		$params['relation'] = Array();

		if ( ($tmp=$PCA['conf'][$field]['parentField']) ) {
			$params['parent'] = $tmp;
			$params['subtree'] = intval($PCA['conf'][$field]['subtree']);
		}

		if (is_array($PCA['conf'][$field]['relation'])) {
			$params['relation'] = $PCA['conf'][$field]['relation'];
			$tmp = $params['relation']['local'];
			if (strlen($tmp)>0) {
				$params['relation']['localval'] = intval($row[$params['relation']['local']]);
			}
		}
		$params['table'] = is_array($PCA['conf'][$field]['foreign']) ? $PCA['conf'][$field]['foreign'] : Array() ;

		if (strlen($PCA['conf'][$field]['refType'])>0) {
			$params['refType'] = $PCA['conf'][$field]['refType'];
		}

		if ($PCA['conf'][$field]['foreign_pid']) {
			$params['pid'] = $PCA['conf'][$field]['foreign_pid'];
		}

		if (strlen($PCA['conf'][$field]['foreign']['where'])>2){
			$params['where'] = $this->lCObj->insertData($PCA['conf'][$field]['foreign']['where']);
		}


		//t3lib_div::debug(Array('TCA'=>$TCA[$refTable]['ctrl'], 'File:Line'=>__FILE__.':'.__LINE__));
		if (is_array($TCA[$refTable])) {
			$q="'";
			return ('<a href="'.$this->emptyUrl.'" onclick="addFromBrowser('.
				$q.$myBrowser.$q.','. // this is defined in ts_setup of zlib
				$q.urlencode(serialize($params)).$q.','.
				$q.$winparams.$q.
				');'.
				' return false;">'.$this->constObj->getIcon($bn).'</a>'   );
		} else {
			return ('[[Error TCA('.$refTable.') not defined:'.__FILE__.'/'.__LINE__.']]');
		}
	}


	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$PCA: ...
	 * @param	[type]		$varname: ...
	 * @param	[type]		$mode: ...
	 * @param	[type]		$idMode: ...
	 * @return	[type]		...
	 */
	function addListModify ($PCA,$varname,$mode='Up',$idMode=0) {
		$q="'";
		$jsName = $idMode  ? 'modifyIdItem' : 'modifyItem';
		if (strcasecmp($mode,'delete')==0 || strcasecmp($mode,'remove')==0) {
			$insText = '<a href="'.$this->emptyUrl.'" onclick="'.$jsName.'('.
				$q.$PCA['name'].$q.','.$q.$varname.$q.','.$q.'Remove'.$q.');'.
				' return false;">'.$this->constObj->getIcon('delete').'</a>';
		} else if (strcasecmp($mode,'deleteall')==0 || strcasecmp($mode,'removeall')==0) {
			$insText = '<a href="'.$this->emptyUrl.'" onclick="'.$jsName.'('.
				$q.$PCA['name'].$q.','.$q.$varname.$q.','.$q.'Removeall'.$q.');'.
				' return false;">'.$this->constObj->getIcon('delete').'</a>';
		} else if (strcasecmp($mode,'up')==0) {
			$insText = '<a href="'.$this->emptyUrl.'" onclick="'.$jsName.'('.
				$q.$PCA['name'].$q.','.$q.$varname.$q.','.$q.'Up'.$q.');'.
				' return false;">'.$this->constObj->getIcon('up').'</a>';
		} else {
			$insText = '??';
		}
		return ($insText);

	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function autoInsert () {
		$m = Array();
		$s = Array();
		$prm = $this->localPCA['autoInsert'];

		$m['###AUTOINSERT_TEXT###'] = '';
		if (is_array($prm) && $this->permitObj->useEditMode()) {
			$insText = '';
			$classname = strlen($prm['params']['className'])>0 ? ' class="'.$prm['params']['className'].'"': '';
			$warn = $this->lCObj->cObjGetSingle($prm['params']['noLastFocusWarning'],$prm['params']['noLastFocusWarning.']);
			if (is_array($prm['globalText'])) {
				$insText .= trim($this->lCObj->cObjGetSingle($prm['globalText']['data'],$prm['globalText']['data.']));
				if (strlen($insText)>0) {
					$insText .= CRLF;
				}
			}
			if (is_array($prm['localText'])) {
				$insText .= trim($this->lCObj->cObjGetSingle($prm['localText']['data'],$prm['localText']['data.']));
			}
			$list = t3lib_div::trimExplode("<br />",$this->divObj->myNl2br($insText));
			$insText = '';
			if (count($list)>0) {
				$insText = '<select onClick="insertSelect(this.form.sg_zlib_autoInsert,'.QT.$warn.QT.')" '.
					'name="sg_zlib_autoInsert"'.$classname.'><option value="" selected="selected"></option>';
				for ($i=0;$i<count($list);$i++) {
					if (strcmp($list[$i],'-')==0) {
						$insText .= '<option value="-">----------</option>';
					} else if (strlen($list[$i])>0) {
						$insText .= '<option value="'.$list[$i].'">'.htmlspecialchars($list[$i]).'</option>';
					}
				}
				$insText .= '</select>';
				$wrap = t3lib_div::trimExplode('|',$prm['globalText']['wrap'],2);
				$insText = $wrap[0].$insText.$wrap[1];
				$m['###AUTOINSERT_TEXT###'] = $insText;
				if (strcmp($prm['params']['mode'],'all')==0 ||
					(strcmp($prm['params']['mode'],'include')==0 && t3lib_div::inList($prm['params']['list'],'text') ) ||
					(strcmp($prm['params']['mode'],'exclude')==0 && !t3lib_div::inList($prm['params']['list'],'text') )) {
					$s['###AUTOINSERT_TEXT###'] = $insText;
				}
			}

			// now check for tables
			$buttonType = $this->constObj->getButtonType('button');
			$buttonConf = $this->constObj->getButtonConf('button');
			for (reset($prm['tables']);$key=key($prm['tables']);next($prm['tables'])) {
				$m['###AUTOINSERT_'.strtoupper($key).'###'] = '';
				if (t3lib_extmgm::isLoaded($key)) {
					$buttonName = (strlen($prm['tables'][$key]['button'])>0) ? $prm['tables'][$key]['button'] : 'button';
					$buttonText = $this->lCObj->TEXT($prm['tables'][$key]['buttonText']);
					if ($buttonName=='button') {
						$buttonConf['file.']['20.']['text'] = $buttonText;
						$tmp = $this->lCObj->cObjGetSingle($buttonType,$buttonConf);
					} else {
						$tmp = $this->constObj->getButton($buttonName,$buttonText);
					}
					$myBrowser = $this->browser.'&id='.$GLOBALS['TSFE']->id.'&mode=plugin&plugin='.$key;
					$params = Array();
					$winparams = $prm['tables'][$key]['browserWinParams'];
					$tmp = '<a href="'.$this->emptyUrl.'" onclick="insertFromDB('.QT.$warn.QT.','.
						QT.$myBrowser.QT.','. // this is defined in ts_setup of zlib
						QT.urlencode(serialize($params)).QT.','.
						QT.$winparams.QT.
						');return false;">'.$tmp.'</a>';
					$wrap = t3lib_div::trimExplode('|',$prm['tables'][$key]['wrap'],2);
					$tmp = $wrap[0].$tmp.$wrap[1];
					$m['###AUTOINSERT_'.strtoupper($key).'###'] = $tmp;
					if (strcmp($prm['params']['mode'],'all')==0 ||
						(strcmp($prm['params']['mode'],'include')==0 && t3lib_div::inList($prm['params']['list'],$key) ) ||
						(strcmp($prm['params']['mode'],'exclude')==0 && !t3lib_div::inList($prm['params']['list'],$key) )) {
						$s['###AUTOINSERT_'.strtoupper($key).'###'] = $tmp;
					}
				}
			}
		}

		$m['###AUTOINSERT###'] = implode($this->localPCA['autoInsert']['params']['implodeWith'],$s);
		return ($m);
	}




	/***********************************************************************************************
	 *
	 * Functions for Checking Data
	 *
	 ***********************************************************************************************/

	/**
	 * @param	[type]		$account: ...
	 * @param	[type]		$bic: ...
	 * @return	[type]		...
	 */
	function checkAccountBic ($account,$bic,$error,$mode=0) { //,$name,&$errorMsg) {
		$result = $error;

		$ca = $this->conf['checkAccount.'];
		$f = array();
		$myResult = ereg ($ca['numericBic'],$bic,$f);
		if (strlen($f[0])>0 && strcmp($f[0],$bic)==0) {
			$numericBic = TRUE;
		}
		$myResult = ereg ($ca['numericAccount'],$account,$f);
		if (strlen($f[0])>0 && strcmp($f[0],$account)==0) {
			$numericAccount = TRUE;
		}
		$numeric = $numericAccount && $numericBic;

		if ($ca['allowNumeric']) {
			if ($numeric) {
				$result = '';
			} else if ($numericAccount!=$numericBic) {
				if ($numericAccount && $mode==1) {
					$result = '';
				} else if ($numericBic && $mode==2) {
					$result = '';
				}
				// beides Falsch
			}
		} else {
			$numeric = FALSE;
		}

		if (!$numeric && $ca['allowIban']) {
			if (strlen($bic)==8 || strlen($bic)==11) {
				// maybe BIC is OK
				ereg ('[A-Z,0-9,a-z]+',$bic,$f);
				if (strlen($f[0])>0 && strcmp($f[0],$bic)==0) {
					$bicCC = strtoupper(substr($bic,4,2));
					if (strpos(','.$ca['allowIbanCC'],$bicCC)>0) {
						// Yes, BIC is OK
						$bicValid = TRUE;
						if ($mode==2) {
							$result = '';
						}
					}
				}
			}
			if (strlen($account)>=14 && strlen($account)<=34) {
				// maybe Account is OK
				$accountCC = strtoupper(substr($account,0,2));
				if (strpos(','.$ca['allowIbanCC'],$accountCC)>0) {
					// Yes, Account is from Valid Country
					// Now Check Checksum of IBAN
					$tmp = substr($account,4).substr($account,0,4);
					$proof = '';
					for ($i=0;$i<strlen($tmp);$i++) {
						$x = substr($tmp,$i,1);
						$proof .= (strval($x)>='0' && strval($x)<='9') ? $x : (ord(strtoupper($x))-55);

					}
					$rest = '';
					while (strlen($proof)>0) {
						$proof = strval($rest).$proof;
						$rest = intval(substr($proof,0,9)) % 97;
						$proof = substr($proof,9);
					}
					if ($rest==1) {
						// Yes, Account is OK
						$accountValid = TRUE;
						if ($mode==1) {
							$result = '';
						}
					}
				}
			}

			if ($bicValid && $accountValid && $mode==0) {
				$result = '';
			}

			if (!$result && strcmp($bicCC,$accountCC)) {
				$result = $error;
			}
		}



		return ($result);
	}


	/***********************************************************************************************
	 *
	 * Functions for Import
	 *
	 ***********************************************************************************************/

	/**
	 * @param	[type]		$filename: ...
	 * @param	[type]		$mincount: ...
	 * @param	[type]		$delim: ...
	 * @param	[type]		$concat: ...
	 * @return	[type]		...
	 */
	function getImportFile ($filename,$mincount,$delim="\t",$concat=1,$recode='') {
		if (!file_exists($filename)) {
			t3lib_div::debug(Array('ERROR'=> '"'.$filename.'" does not exist!', 'File:Line'=>__FILE__.':'.__LINE__));
		} else {
			// t3lib_div::debug(Array('File'=> '"'.$filename.'"', 'Size'=>filesize($filename), 'File:Line'=>__FILE__.':'.__LINE__));
		}
		$this->impErrors = Array();
		$recodes = t3lib_div::trimExplode(',',$recode);
		$cs = t3lib_div::makeInstance('t3lib_cs');
		
		if (1==0) t3lib_div::debug(Array('t3lib_cs_convMethod'=>$GLOBALS['TYPO3_CONF_VARS']['SYS']['t3lib_cs_convMethod'], 
			'function_exists(recode_string)'=>function_exists('recode_string'),
			'function_exists(mb_convert_encoding)'=>function_exists(mb_convert_encoding),
			'function_exists(iconv)'=>function_exists(iconv),
			'$recodes'=>$recodes,
			'$TSFE->renderCharset'=>$GLOBALS['TSFE']->renderCharset,
			'File:Line'=>__FILE__.':'.__LINE__));
		
		$fArray = Array();
		$lines = file ($filename);
		$lCnt = count($lines);
		// t3lib_div::debug(Array('$lCnt'=>$lCnt, '$lines'=>$lines, 'File:Line'=>__FILE__.':'.__LINE__));
		if ($lCnt==1) { // check if file is in MAC-Format
			$tmp = explode("\r",$lines[0]);
			if (count($tmp)>1) {
				$lines = $tmp;
				$lCnt = count($lines);
				// t3lib_div::debug(Array('WARNING'=>'MAC CRs used.', '$lCnt'=>$lCnt, '$lines'=>$lines, 'File:Line'=>__FILE__.':'.__LINE__));
			}
		}

		for ($i=0;$i<$lCnt;$i++) if(trim($lines[($i)])) {
			$theLine = str_replace("\n",'',str_replace("\r",'',$lines[($i)]));
			// t3lib_div::debug(Array('$i'=>$i, 'File:Line'=>__FILE__.':'.__LINE__));
			if ($concat>0) {
				while (substr_count($theLine,$delim)<($mincount-1) && $i<$lCnt ) {
					$this->impErrors['L-'.$i] = '('.substr_count($lines[$i],$delim).') '.$lines[$i];
					$theLine = $theLine.str_replace("\n",'',str_replace("\r",'',$lines[($i+1)]));
					$i++;
				}
				if (trim($theLine)) {
					if ($recodes[0] && $recodes[1]) {
						$theLine = $cs->conv($theLine,$recodes[0],$recodes[1]);
					}
					$fArray[] = $theLine;
				}
			} else if (intval($concat)==0 && substr_count($theLine,$delim)<($mincount-1)) {
					if ($recodes[0] && $recodes[1]) {
						$theLine = $cs->conv($theLine,$recodes[0],$recodes[1]);
					}
					$this->impErrors['L-'.$i] = '('.substr_count($theLine,$delim).') '.$theLine;
			} else {
				if (trim($theLine)) {
					if ($recodes[0] && $recodes[1]) {
						$theLine = $cs->conv($theLine,$recodes[0],$recodes[1]);
					}
					$fArray[] = $theLine;
				}
			}
		}
		// t3lib_div::debug(Array('$fArray'=>$fArray, '$this->impErrors'=>$this->impErrors,  'File:Line'=>__FILE__.':'.__LINE__));
		return ($fArray);
	}



	/***********************************************************************************************
	 *
	 * General Functions
	 *
	 ***********************************************************************************************/

	/**
	 * @param	[type]		text or array ...
	 * @param	[type]		$row: ...
	 * @param	[type]		$mode: ...
	 * @return	[type]		...
	 */
	function getHiddenText($PCA,$row,$mode='') {
		$retcode = '';

		$pcaCtrl = $PCA['ctrl'];
		$myTime = time();
		if ($pcaCtrl['enablecolumns']['starttime'] && $row[$pcaCtrl['enablecolumns']['starttime']]>$myTime) {
			$d = date('d',$row[$pcaCtrl['enablecolumns']['starttime']]);
			$m = date('m',$row[$pcaCtrl['enablecolumns']['starttime']]);
			$y = date('Y',$row[$pcaCtrl['enablecolumns']['starttime']]);
			$retcode = $this->constObj->getWrap('hot',sprintf($this->langObj->getLL($mode.'startfuture','start'),$d,$m,$y));
		} else if ($pcaCtrl['enablecolumns']['endtime'] && $row[$pcaCtrl['enablecolumns']['endtime']]>86400 && $row[$pcaCtrl['enablecolumns']['endtime']]<$myTime) {
			$d = date('d',$row[$pcaCtrl['enablecolumns']['endtime']]);
			$m = date('m',$row[$pcaCtrl['enablecolumns']['endtime']]);
			$y = date('Y',$row[$pcaCtrl['enablecolumns']['endtime']]);
			$retcode = $this->constObj->getWrap('hot',sprintf($this->langObj->getLL($mode.'endreached','end'),$d,$m,$y));
		} else if ($pcaCtrl['enablecolumns']['disabled'] && $row[$pcaCtrl['enablecolumns']['disabled']]) {
			$retcode = $this->constObj->getWrap('hot',$this->langObj->getLL($mode.'hidden','hidden'));
		} else if (!$mode) {
			if ($pcaCtrl['enablecolumns']['fe_group']) {
				$tmp = $row[$pcaCtrl['enablecolumns']['fe_group']];
				if ($tmp==-1 || ($tmp>0 && $this->felib->grouplist[$tmp]!=$tmp))  {
					$retcode = $this->constObj->getWrap('hot',sprintf($this->langObj->getLL($mode.'noaccess','noaccess'),$tmp));
				} else {
					$retcode = $this->langObj->getLL($mode.'unhidden','unhidden');
				}
			} else {
			}
		}

		return ($retcode);
	}


	function getImageDownloadLink() {
		$url = '';

		return ($url);
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sgzlib.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sgzlib.php']);
}
?>