<?php
/**
 *
 * PHP versions 5
 *
 *  (c) 2007-2008 Stefan Geith (typo3devYYYY@geithware.de)
 *
 * LICENSE:
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 * *
 * @package    TYPO3
 * @subpackage sg_lib
 * @author     Stefan Geith (typo3devYYYY@geithware.de)
 * @copyright  2008 Stefan Geith
 * @license    http://www.opensource.org/licenses/lgpl-license.php LGPL
 *
 * -----------------------------------------------------------------------
 *
 * On Creation of this Object, we need the following Data:
 * - Information, if parent is cached (=1) or not
 * - plugin.tx_myplugin.tx_mytable - Information
 *
 * Then we need this mandatory Information:
 * - The Template(s)
 * - The Model
 *
 * Now the output can be rendered:
 *  - respect ###NO/owneronly###  (this part must be uncached!!)
 *
 */



/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *   67: function __construct ($designator, $factoryObj, $model, $cached)
 *   86: function emptyResultAsSubpart($mode)
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */



abstract class tx_sglib_viewbase  {
	protected $designator;
	protected $factoryObj;
	protected $configObj;
	protected $debugObj;
	protected $markersObj;
	protected $langObj;
	protected $model;
	protected $cached;

	function __construct ($designator, $factoryObj, $model, $cached) {
		$this->designator = $designator;
		$this->factoryObj = $factoryObj;
		$this->configObj = $factoryObj->configObj;
		$this->debugObj = $factoryObj->debugObj;
		$this->constObj = $factoryObj->constObj;
		$this->paramsObj = $factoryObj->paramsObj;
		$this->langObj = $factoryObj->langObj;
		$this->cObj = $factoryObj->cObj;
		$this->model = $model;
		$this->cached = $cached;

		$this->markersObj = $factoryObj->markersObj;
		$this->markersObj->model = $this->model;

		$this->init();
	}

	abstract protected function init();

	function emptyResultAsSubpart($mode) {
		$this->flagEmptyResultAsSubpart = $mode;
	}

	abstract function getOutput();

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
	function getFeSingleField($table,$field,$row,$em,&$fieldConf,&$searchConf,$opt=Array())	{
		$myType = strtolower($fieldConf[$field.'.']['type']); 
		$mySearchType = strtolower($searchConf[$field.'.']['formtype']);
		if ($em>=SGZLIB_SEARCH && $mySearchType) {
			$myType = $mySearchType;
		}
		switch($myType)	{
			case '':
			case 'time':
			case 'date':
			case 'datetime':
			case 'input':
			case 'none':
			case 'password':
//				$item = $this->getSingleField_typeInput($table,$field,$row,$em,$myType,$opt);
			break;
			case 'text':
			case 'textnowrap':
//				$item = $this->getSingleField_typeText($table,$field,$row,$em,$myType,$opt);
			break;
			case 'linklist':
//				if ($em>=SGZLIB_SEARCH) {
//					$item = $this->getSingleField_typeText($table,$field,$row,$em,$myType,$opt);
//			} else if ($em==0) {
//					$item = $this->getSingleField_typeText($table,$field,$row,$em,$myType,$opt);
//				} else {
//					$item = $this->getSingleField_typeText($table,$field,$row,$em,$myType,$opt);
//				}
			break;
			case 'select':
			case 'radio':
			case 'checklist':
			case 'selectlist':
			case 'selectbits':
			case 'selectsingle':
			case 'selectmulti':
				$item = $this->getSingleField_typeSelect($table,$field,$row,$em,$fieldConf,$searchConf,$myType,$opt);
			break;
			case 'check':
				if ($em>=SGZLIB_SEARCH) {
					$item = $this->getSingleField_typeSelect($table,$field,$row,$em,$fieldConf,$searchConf,$myType,$opt);
				} else {
//					$item = $this->getSingleField_typeCheck($table,$field,$row,$em,$myType,$opt);
				}
			break;
			case 'imageres':
//				if ($em>=SGZLIB_SEARCH || $em==0) {
//					$item = $this->getSingleField_typeText($table,$field,$row,$em,$myType,$opt);
//				} else {
//					$item = $this->getSingleField_typeList($table,$field,$row,$em,$myType,$opt);
//				}
			break;
			case 'image':
			case 'imagelist':
			case 'filelist':
			case 'doclist':
			case 'pdflist':
//				$item = $this->getSingleField_typeList($table,$field,$row,$em,$myType,$opt);
			break;
			case 'user':
//				$PA['fieldConf']['config'] = $PCA['conf'][$field];
//				$PA['fieldConf']['config']['form_type'] = $PA['fieldConf']['config']['form_type'] ? $PA['fieldConf']['config']['form_type'] : $PA['fieldConf']['config']['type'];	// Using "form_type" locally in this script
//				$PA['table']=$table;
//				$PA['field']=$field;
//				$PA['row']=$row;
//				$PA['pObj']=&$this;
//				$item = '--user('.$PCA['conf'][$field]['userFunc'].')--';
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
	function getSingleField_typeSelect($table,$field,$row,$em,&$fieldConf,&$searchConf,$myMode,$opt=Array()) {
		global $TCA;

		$fieldConfKey = $fieldConf[$field.'.'];
		$searchConfKey = $searchConf[$field.'.'];

//TODO		$classname = $this->getClassTag($field,'select',$PCA,$em);
//TODO		$classcheck = $this->getClassTag($field,'check',$PCA,$em);
//TODO		$classradio = $this->getClassTag($field,'radio',$PCA,$em);
		$myConf = $this->configObj->get($table.'.conf.'.$field.'.');

		if ($myMode=='selectmulti' && intval($myConf['maxitems'])==1) {
			$myMode='selectsingle';
		}

		//if ($myMode=='group') {
		//	$groupMode = $fieldConfKey['internal_type'];
		//	$groupAllowed = $fieldConfKey['allowed'];
		//}

		$myItems = $this->model->getRefItems($table,$field,$em,$row);

		// Search Reference-List for this field
//		if ($em<SGZLIB_SEARCH && is_array($this->foreign[$em]) && is_array($this->foreign[$em][$table.'.'.$field])) {
//			$myItems = $this->foreign[$em][$table.'.'.$field];
//		} else if ($em>=SGZLIB_SEARCH) {
//			$this->itemsObj->prepareItems($table,$field,$em,$row);
//			$myItems = $this->itemsObj->getItemList($table,$field,$em);
//			if (is_array($opt['options'])) {
//				$myItems = array_merge ($myItems,$opt['options']);
//			}
//			if (!is_array($this->foreign[$em])) { $this->foreign[$em] = Array(); }
//		} else {
//			$this->itemsObj->prepareItems($table,$field,$em,$row);
//			$myItems = $this->itemsObj->getItemList($table,$field,$em);
//			if (!is_array($this->foreign[$em])) { $this->foreign[$em] = Array(); }
//			$this->foreign[$em][$table.'.'.$field] = $myItems;
//		}

		if ($em==SGZLIB_FORM) {
//			if ($myMode=='selectmulti') {
//				$item = '<select multiple="multiple"'.
//					$this->getPAfieldParam($PCA,$field,'rows',Array(' size="','"')).
//					' name="'.$this->designator.'[list]['.$field.']" '.$classname.'>';
//				$myElement = t3lib_div::trimExplode(',',trim($row[$field]));
//				if (strcmp($fieldConfKey['refType'],'title')==0) {
//					if (strlen(trim($row[$field]))>0)  for ($i=0;$i<count($myElement);$i++) {
//						$item .= '<option value="'.$myElement[$i].'">'.$myElement[$i].'</option>';
//					}
//				} else {
//					if (strlen(trim($row[$field]))>0)  for ($i=0;$i<count($myElement);$i++) {
//						$item .= '<option value="'.$myElement[$i].'">'.$myItems[$myElement[$i].'.'].'</option>';
//					}
//				}
//				$item .= '</select>';
//				$item .= '<input type="hidden" name="'.$this->designator.$this->formDataName.'['.$field.']" value="'.$row[$field].'" />';
//			} else if ($myMode=='selectsingle') {
//				$item = '<select multiple="multiple"'.
//					$this->getPAfieldParam($PCA,$field,'rows',Array(' size="','"')).
//					' name="'.$this->designator.'[list]['.$field.']" '.$classname.'>';
//				$myElement = t3lib_div::trimExplode(',',trim($row[$field]));
//				if (strcmp($fieldConfKey['refType'],'title')==0) {
//					if (strlen(trim($row[$field]))>0)  for ($i=0;$i<count($myElement);$i++) {
//						$item .= '<option value="'.$myElement[$i].'">'.$myElement[$i].'</option>';
//					}
//				} else {
//					if (strlen(trim($row[$field]))>0)  for ($i=0;$i<count($myElement);$i++) {
//						$item .= '<option value="'.$myElement[$i].'">'.$myItems[$myElement[$i].'.'].'</option>';
//					}
//				}
//				$item .= '</select>';
//				$item .= '<input type="hidden" name="'.$this->designator.$this->formDataName.'['.$field.']" value="'.$row[$field].'" />';
//			} else if ($myMode=='selectbits') {
//				$item = '<input type="hidden" name="'.$this->designator.$this->formDataName.'['.$field.']" value="'.$row[$field].'" />';
//				$myWrap = Array();
//				$myWrap = explode ('|',$fieldConfKey['textWrap']);
//				$p=1;
//				$tmp = intval($row[$field]);
//				$myMax = (isset($fieldConfKey['max'])) ? intval($fieldConfKey['max']) : 1 ;
//				for ($j=0;$j<31;$j++) {
//					if ($j<$myMax) {
//						$onClick = ' onclick="addFromCheck('.
//								"'".$this->designator."','".$field."'".','.			// Variable Name
//								$p.','.(pow(2,31)-$p-1).','.($j+1).');'.
//								' return true;"'
//								;
//						$item .= $myWrap[0].
//							'<input type="checkbox"  name="'.$this->designator.'[bits]['.$field.']['.($j+1).']" '.
//									$classcheck.$onClick.' value="'.$p.'" '.(($tmp & 1)?'checked="checked"':'').' />'.
//							$myItems[($j+1).'.'].
//							$onCLick.
//							$myWrap[1];
//					}
//					$tmp = intval($tmp / 2);
//					$p = $p * 2;
//				}
//			} else { //select, radio, checklsit
//				$item = '<input type="hidden" name="'.$this->designator.'[old]['.$field.']" value="'.$row[$field].'" />';
//				$item .= $this->getSelectFormList($myItems,$this->designator,$field,$row[$field],$classname,$myMode,$PCA,$em);
//			}
//TODO		} else if ($em==SGZLIB_SEARCHALL || ($em==SGZLIB_SEARCHUSED && strcmp($fieldConfKey['type'],'input')==0)) {
			} else if ($em>=SGZLIB_SEARCHALL) {
			$onc = '';
//			if (strcmp($searchConfKey['mode'],'selectmulti')==0) {
//				$onc = 'onchange="javascript:sgSelectMultiChange('.QT.$this->designator.QT.','.QT.$field.QT.','.QT.$this->sMode.QT.')" ';
//				$item = '<input type="hidden" name="'.$this->designator.'[search]['.$field.']" value="'.$row[$field].'">';
//				$item .= '<select name="'.$this->designator.'[searchmulti]['.$field.']" '.$onc.$classname.' multiple="multiple">';
//				$set = false;
//				$lnr = 0;
//				for (reset($myItems);$key=key($myItems);next($myItems)) {
//					$lnr++;
//					$vValue = urlencode((substr($key,-1)=='.') ? substr($key,0,-1) : $key);
//					$itemText = $this->itemsObj->getItemText('search',$lnr,$field,$myItems,$key,$PCA);
//					if (strlen($row[$field])>0) {
//						$item .= '<option '.((strstr(','.$row[$field].',', ','.$vValue.',')) ? 'selected="selected" ':'').
//								'value="'.$vValue.'">'.$itemText.'</option>';
//					} else {
//						$item .= '<option '.(!$set?'selected="selected" ':'').
//								'value="'.$vValue.'">'.$itemText.'</option>';
//					}
//					$set = true;
//				}
//				$item .= '</select>';
//			} else {
//TODO//				$onc = $this->getOnchangeSelect($PCA,$field);
				$item = $this->getSelectSearchList($myItems,$field,$row[$field],$onc,$classname,$myMode,$em,$fieldConf,$searchConf);
//			}
		} else if ($em==SGZLIB_SEARCHUSED) {
//			$myUsed = Array();
//			if (is_array($myConf['preItems'])) {
//				for (reset($myConf['preItems']);$key=key($myConf['preItems']);next($myConf['preItems'])) {
//					$myUsed[($myConf['preItems'][$key]['id']).'.'] = 1;
//				}
//			}
//			$onc = $this->getOnchangeSelect($PCA,$field);
//			if ($myConf['MM']) {
//				$query = $PCA['table'].'.uid='.$myConf['MM'].'.uid_local';
//				$myQ = $this->getDbEnableColumns($PCA['table'],$PCA,$piVarSearch,Array());
//				if (count($myQ)) {
//					$query .= ' AND ('.implode(' AND ',$myQ).')';
//				}
//				$select = 'uid_foreign AS '.$field.', count(*) AS cnt';
//				$group = 'uid_foreign';
//				$myTable = $PCA['table'].','.$myConf['MM'];
//				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$myTable,$query,$group);
//
//				$this->debugval('getitemdetails',$field,Array('$select'=>$select, '$table(MM)'=>$myConf['MM'], '$query'=>$query, '$group'=>$group,
//					'numRows='=>$GLOBALS['TYPO3_DB']->sql_num_rows($res), 'File:Line'=>__FILE__.':'.__LINE__));
//			} else {
//				$query = '1=1 '.(($searchConfKey['hiddenAlso']) ? ' AND deleted=0 ' : $this->lCObj->enableFields($table));
//				if ($PCA['ctrl']['defaultWhere']) {
//					$query .= str_replace('###val###',$PCA['ctrl']['defaultWhereVal'],' AND ('.$PCA['ctrl']['defaultWhere'].')');
//				}
//				$select = $field.', count(*) AS cnt';
//				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $table,$query,$field);
//				$this->debugObj->debugVal('getitemdetails',$field,Array('$select'=>$select, '$table'=>$table, '$query'=>$query,
//					'numRows='=>$GLOBALS['TYPO3_DB']->sql_num_rows($res), 'File:Line'=>__FILE__.':'.__LINE__));
//			}
//
//			if (!$GLOBALS['TYPO3_DB']->sql_error()) {
//				$this->debugObj->debugIf('sql',Array('query'=>$query, 'res /  count'=>$res.' / '.$GLOBALS['TYPO3_DB']->sql_num_rows($res),
//						'FILE:LINE='=>__FILE__.':'.__LINE__ ));
//				while($myRow=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
//					if ($myMode=='selectmulti') {
//						$tmp = explode (',',$myRow[$field]);
//						for ($j=0;$j<count($tmp);$j++) {
//							$myUsed[$tmp[$j].'.'] = 1;
//							$this->itemsObj->getItemCountAdd('',$field,$tmp[$j].'.',intval($myRow['cnt']));
//						}
//					} else {
//						$myUsed[$myRow[$field].'.'] = 1;
//						$this->itemsObj->getItemCountAdd('',$field,$myRow[$field].'.',intval($myRow['cnt']));
//					}
//				}
//			}
//			if (is_array($myConf['postItems'])) {
//				for (reset($myConf['postItems']);$key=key($myConf['postItems']);next($myConf['postItems'])) {
//					$myUsed[($myConf['postItems'][$key]['id']).'.'] = 1;
//				}
//			}
//
//			if (!is_array($myUsed)) {
//				$item = $this->getSelectSearchList($myItems,$this->designator,$field,$row[$field],$onc,$classname,$myMode,$PCA,$em);
//			} else {
//				$myXItems = Array();
//				for (reset($myItems);$muKey=key($myItems);next($myItems)) if($myUsed[$muKey]) {
//					if (isset($myItems[$muKey])) {
//						$myXItems[$muKey] = $myItems[$muKey];
//					}
//				}
//				$item = $this->getSelectSearchList($myXItems,$this->designator,$field,$row[$field],$onc,$classname,$myMode,$PCA,$em);
//			}
		} else {
//
//			if ($myMode=='selectmulti' || $myMode=='selectsingle' || $myMode=='checklist') {
//				$item = '';
//				$myWrap = Array();
//				$myWrap = explode ('|',$fieldConfKey['textWrap']);
//				$tmp = t3lib_div::trimExplode(',',$row[$field]);
//				$tmpRefType = $fieldConfKey['refType'];
//				if (is_array($fieldConfKey['typoLink'])) {
//					$tlc = $fieldConfKey['typoLink'];
//					$tmpAP = $tlc['additionalParams'].'';
//					if (!$tlc['parameter'] || strcmp($tlc['parameter'],'0')==0) {
//						$tlc['parameter'] = $GLOBALS['TSFE']->id;
//					}
//					$tmpP = $tlc['parameter'];
//					$tmpAPx = '';
//					if ($tla=$fieldConfKey['addSearch']) {
//						$tmpAPx = '&'.$this->designator.'[searchmode]=1';
//						$tmpAPx .= '&'.$this->designator.'[search]['.$tla['searchField'].']=';
//					}
//
//					$this->debugObj->debugIf('typoLink',Array('typoLink ='=>$tlc, 'refType ='=>$tmpRefType, 'File:Line'=>__FILE__.':'.__LINE__));
//					if (strcmp($tmpRefType,'inside')==0) {
//						for ($j=0;$j<count($tmp);$j++)  if (strlen($tmp[$j])) {
//							$this->debugObj->debugIf('typoLink',Array('&title='=>urlencode($tmp[$j]), 'File:Line'=>__FILE__.':'.__LINE__));
//							$tlc['parameter'] = $this->lCObj->substituteMarkerArray($tmpP,
//									$this->itemsObj->getItemsRecord('',$field,$tmp[$j].'.'), '###|###');
//							$name = $this->lCObj->substituteMarkerArray($tlc['item'],
//									$this->itemsObj->getItemsRecord('',$field,$tmp[$j].'.'), '###|###');
//							$tlc['additionalParams'] = $this->lCObj->substituteMarkerArray($tmpAP.$tmpAPx.substr($name,0,1),
//									$this->itemsObj->getItemsRecord('',$field,$tmp[$j].'.'), '###|###');
//							$tl = $this->lCObj->typoLink($name,$tlc);
//							$item .= $myWrap[0].$tl.$myWrap[1];
//						}
//					} else if (strcmp($tmpRefType,'title')==0){
//						for ($j=0;$j<count($tmp);$j++)  if (strlen($tmp[$j])) {
//							$this->debugObj->debugIf('typoLink',Array('&title='=>urlencode($tmp[$j]), 'File:Line'=>__FILE__.':'.__LINE__));
//							$tlc['parameter'] = str_replace('###id###',intval($tmp[$j]),$tmpP);
//							$tlc['additionalParams'] = $tmpAP.$tmpAPx.substr($tmp[$j],0,1).'&title='.urlencode($tmp[$j]);
//							$tl = $this->lCObj->typoLink($tmp[$j],$tlc);
//							$item .= $myWrap[0].$tl.$myWrap[1];
//						}
//					} else if ($tmpRefType=='1') {
//						for ($j=0;$j<count($tmp);$j++) if (intval($tmp[$j])) {
//							$this->debugObj->debugIf('typoLink',Array('&uid='=>intval($tmp[$j]), 'File:Line'=>__FILE__.':'.__LINE__));
//							$tlc['parameter'] = str_replace('###id###',intval($tmp[$j]),$tmpP);
//							$tlc['additionalParams'] = $tmpAP.'&uid='.intval($tmp[$j].'.');
//							$tl = $this->lCObj->typoLink($myItems[$tmp[$j].'.'],$tlc);
//							$item .= $myWrap[0].$tl.$myWrap[1];
//						}
//					} else  {
//						for ($j=0;$j<count($tmp);$j++) if (intval($tmp[$j])) {
//							$tlc['parameter'] = str_replace('###id###',intval($tmp[$j]),$tmpP);
//							$tmpRefType = str_replace('###myuid###',$this->todo['Uid'],$tmpRefType);
//							if (strcmp($tmpRefType,'none')) {
//								$tlc['additionalParams'] = $tmpRefType ? $tmpAP.$tmpRefType.intval($tmp[$j].'.') : '';
//							}
//							$this->debugObj->debugIf('typoLink',Array('[param]'.$tmpRefType=>intval($tmp[$j]), 'typoLink ='=>$tlc, 'File:Line'=>__FILE__.':'.__LINE__));
//							$tl = $this->lCObj->typoLink($myItems[$tmp[$j].'.'],$tlc);
//							$item .= $myWrap[0].$tl.$myWrap[1];
//						}
//					}
//				} else {
//					if (strcmp($tmpRefType,'title')==0) {
//						for ($j=0;$j<count($tmp);$j++) {
//							$item .= $myWrap[0].$tmp[$j].$myWrap[1];
//						}
//					} else {
//
//						for ($j=0;$j<count($tmp);$j++) {
//							$item .= $myWrap[0].$this->itemsObj->getItemText('conf',1,$field,$myItems,$tmp[$j].'.',$PCA,'','viewFormat').
//								$myWrap[1];
//						}
//					}
//				}
//
//			} else if ($myMode=='selectbits') {
//				$item = '';
//				$myWrap = Array();
//				$myWrap = explode ('|',$fieldConfKey['textWrap']);
//				$j=0;
//				$myMax = (isset($fieldConfKey['max'])) ? intval($fieldConfKey['max']) : 1 ;
//				for ($tmp=intval($row[$field]);$tmp>0;$tmp=intval($tmp/2)) {
//					$j=$j+1;
//					if (($tmp & 1) && $j<=$myMax) {
//						$item .= $myWrap[0].$myItems[$j.'.'].$myWrap[1];
//					}
//				}
//			} else {
//				if (is_array($fieldConfKey['second'])) {
//					if (intval($fieldConfKey['second']['value'])==intval($row[$field])) {
//						//t3lib_div::debug(Array('Second='=>$fieldConfKey['second'], 'File:Line'=>__FILE__.':'.__LINE__));
//						$item = $this->getSingleField_typeSelect($table,$fieldConfKey['second']['field'],$row,$em,$PCA,$myMode);
//					} else {
//						$item = $this->itemsObj->getItemText('conf',1,$field,$myItems,$row[$field].'.',$PCA,'','viewFormat');
//					}
//				} else {
//				$item = $this->itemsObj->getItemText('conf',1,$field,$myItems,$row[$field].'.',$PCA,'','viewFormat');
//				}
//			}
		}

		return (strlen($item)>0 ? $item : $this->configObj->get('nbspForSelect'));
	}

		/**
	 * [Describe function...]
	 *
	 * @param	[type]		$myItems: ...
	 * @param	[type]		$name: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$value: ...
	 * @param	[type]		$onc: ...
	 * @param	[type]		$classname: ...
	 * @param	[type]		$myMode: ...
	 * @param	[type]		$em: ...
	 * @return	[type]		...
	 */
	function getSelectSearchList($myItems,$field,$value,$onc,$classname,$myMode,$em,&$fieldConf,&$searchConf) {
		$wrap = t3lib_div::trimExplode('|',$searchConf[$field.'.']['wrap']);
		$wrapAll = t3lib_div::trimExplode('|',$searchConf[$field.'.']['wrapAll']);
		$wrapLabel = t3lib_div::trimExplode('|',$searchConf[$field.'.']['wrapLabel']);
		$wrapInput = t3lib_div::trimExplode('|',$searchConf[$field.'.']['wrapInput']);
		$directLink = $searchConf[$field.'.']['directLink.'];
		$item = '';
		$set = false;

		// if ($field=="category") t3lib_div::debug(Array('$myItems'=>$myItems, 'File:Line'=>__FILE__.':'.__LINE__));
		$this->debugObj->debugIf('searchItems',Array('$myItems'=>$myItems, 'File:Line'=>__FILE__.':'.__LINE__),$field==$this->dodebug['searchItems']);

//		if ($searchConf[$field.'.']['cntOrder']) {
//			$mc = $this->itemsObj->getItemCountAdd('',$field);
//			if ($myMode=='checklist' || $myMode=='selectlist') {
//				unset($mc['0.']);
//			}
//			$max = $searchConf[$field.'.']['maxList']>2 ? $searchConf[$field.'.']['maxList'] : 10;
//			if ($searchConf[$field.'.']['cntOrder']>0) {
//				arsort ($mc);
//			} else {
//				asort ($mc);
//			}
//			$old = $myItems;
//			$myItems = Array();
//			for (reset($mc);$key=key($mc);next($mc)) if ($max>0 && isset($old[$key])) {
//				$myItems[$key] = $old[$key];
//				$max--;
//			}
//		} else if ($searchConf[$field.'.']['alphaOrder']) {
//			asort ($myItems);
//		}

		$this->debugObj->debugIf('searchItems',Array('$myItems'=>$myItems, 'File:Line'=>__FILE__.':'.__LINE__),$field==$this->dodebug['searchItems']);
		if ($myMode=='radio') {
//			$classname = $this->getClassTag($field,$myMode,$PCA,$em);
//			$blockClass = $this->getClassTag($field,$myMode,$PCA,$em,'block');
//			if ($blockClass) {
//				$item .= '<div '.$blockClass.'>'.CRLF;
//			}
//			$item .= $wrapAll[0];
//			$lnr = 0;
//			for (reset($myItems);$key=key($myItems);next($myItems)) {
//				$lnr++;
//				$classname = $this->getClassTag($field,$myMode,$PCA,$em);
//				$vValue = urlencode((substr($key,-1)=='.') ? substr($key,0,-1) : $key);
//				$itemText = $this->itemsObj->getItemText('search',$lnr,$field,$myItems,$key,$PCA);
//				if (strlen($value)>0) {
//					$item .= TAB.$wrap[0].$wrapInput[0].'<input name="'.$this->designator.'[search]['.$field.']" type="radio" '.
//						($vValue==$value?'checked="checked" ':'').
//						$classname.' id="'.$this->designator.'_'.$field.'_'.$vValue.'" '.
//						'value="'.$vValue.'"/>'.$wrapInput[1].CRLF;
//				} else {
//					$item .= TAB.$wrap[0].$wrapInput[0].'<input name="'.$this->designator.'[search]['.$field.']" type="radio" '.
//						(!$set?'checked="checked" ':'').
//						$classname.' id="'.$this->designator.'_'.$field.'_'.$vValue.'" '.
//						'value="'.$vValue.'"/>'.$wrapInput[1].CRLF;
//				}
//				$item .= TAB.$wrapLabel[0].'<label for="'.$this->designator.'_'.$field.'_'.$vValue.'">'.$itemText.'</label>'.$wrapLabel[1].$wrap[1].CRLF;
//				$set = true;
//			}
//			$item .= $wrapAll[1];
//			if ($blockClass) {
//				$item .= '</div>'.CRLF;
//			} else {
//				$item .= CRLF;
//			}
		} else if ($myMode=='checklist') {
//			$classname = $this->getClassTag($field,$myMode,$PCA,$em);
//			$blockClass = $this->getClassTag($field,$myMode,$PCA,$em,'block');
//			if ($blockClass) {
//				$item .= '<div '.$blockClass.'>'.CRLF;
//			}
//			$item .= $wrapAll[0];
//			$lnr = 0;
//			$splitCols = $searchConf[$field.'.']['colNums'];
//			$splitInsert = $searchConf[$field.'.']['colInsert'];
//			$colCnt = $splitCols>1 && count($myItems)>3 ? intval((count($myItems) - 1 + $splitCols) / $splitCols) : 0;
//			$cnt = $colCnt;
//			for (reset($myItems);$key=key($myItems);next($myItems)) {
//				$lnr++;
//				$cnt--;
//				$subField = urlencode(str_replace('=','',((substr($key,-1)=='.') ? substr($key,0,-1) : $key)));
//				$vValue = urlencode(((substr($key,-1)=='.') ? substr($key,0,-1) : $key));
//				$itemText = $this->itemsObj->getItemText('search',$lnr,$field,$myItems,$key,$PCA);
//				if ($vValue!='null' && strlen($itemText)>0)
//				$item .= TAB.$wrap[0].$wrapInput[0].'<input name="'.$this->designator.'[search]['.$field.']['.$subField.']" type="checkbox" '.
//						(isset($value[$vValue])?'checked="checked" ':'').
//						$classname.' id="'.$this->designator.'_'.$field.'_'.$vValue.'" '.
//						'value="'.$vValue.'" />'.$wrapInput[1].CRLF.
//						TAB.$wrapLabel[0].'<label for="'.$this->designator.'_'.$field.'_'.$vValue.'">'.$itemText.'</label>'.$wrapLabel[1].$wrap[1].CRLF;
//				if ($cnt<1 && $splitCols>1 && count($myItems)>3) {
//					$cnt = $colCnt;
//					$item .= $splitInsert;
//				}
//			}
//			$item .= $wrapAll[1];
//			if ($blockClass) {
//				$item .= '</div>'.CRLF;
//			} else {
//				$item .= CRLF;
//			}
		} else if ($myMode=='selectlist') {
//			$classname = $this->getClassTag($field,$myMode,$PCA,$em);
//			$blockClass = $this->getClassTag($field,$myMode,$PCA,$em,'block');
//			if ($blockClass) {
//				$item .= '<div '.$blockClass.'>'.CRLF;
//			}
//			$item .= $wrapAll[0];
//			$lnr = 0;
//			for (reset($myItems);$key=key($myItems);next($myItems)) {
//				$lnr++;
//				$subField = urlencode(str_replace('=','',((substr($key,-1)=='.') ? substr($key,0,-1) : $key)));
//				$vValue = urlencode(((substr($key,-1)=='.') ? substr($key,0,-1) : $key));
//				$itemText = $this->itemsObj->getItemText('search',$lnr,$field,$myItems,$key,$PCA);
//				if ($vValue!='null' && strlen($itemText)>0) {
//					$href = $this->emptyUrl;
//					$onc = ' onclick="sgAbcSubmit('.
//						QT.intval($key).QT.','.QT.$this->designator.QT.','.QT.$this->sMode.QT.",'".$field."'".');return false;"';
//					if (is_array($directLink)) {
//						$onc = '';
//						$myListPage = (intval($directLink['default'])>0 || strcmp($directLink['default'],'0')==0) ?
//							intval($directLink['default']) : $this->listPage;
//						$myListPage = (intval($directLink[$this->itemsObj->getItemsPid('',$field,$key)])>0) ?
//							intval($directLink[$this->itemsObj->getItemsPid('',$field,$key)]) : $myListPage;
//
//						$href = $this->getTypolinkURL($myListPage,
//								'&'.$this->designator.'[searchmode]=1&'.$this->designator.'[search]['.$field.']='.$vValue);
//					}
//					$item .= TAB.$wrap[0].'<a href="'.$href.'"'.$onc.'>'.$itemText.'</a>'.$wrap[1].CRLF;
//				}
//			}
//			if (!is_array($directLink)) {
//				$item .= $wrapAll[1].'<input type="hidden" name="'.$this->designator.'[search]['.$field.']" value="" />';
//			}
//			if ($blockClass) {
//				$item .= '</div>'.CRLF;
//			} else {
//				$item .= CRLF;
//			}
		} else {
			$tmpItems = '';
			$set = false;
			$lnr=0;
			$this->js_array = '';
			$link_options = Array();
			$myConf=array();
			$myConf['parameter'] = $GLOBALS['TSFE']->id;
			$tmp = $this->paramsObj->getPluginParams();
			$defParams = Array($this->designator.'[searchmode]'=>1);
			if (is_array($tmp['search'])) foreach ($tmp['search'] as $key=>$pValue) {
				$defParams[$this->designator.'[search]['.$key.']'] = $pValue;
			}
			//for (reset($myItems);$key=key($myItems);next($myItems)) {
			foreach ($myItems as $key=>$iValue) {
				$lnr++;
				$vValue = urlencode((substr($key,-1)=='.') ? substr($key,0,-1) : $key);
				//TODO// $itemText = $this->itemsObj->getItemText('search',$lnr,$field,$myItems,$key,$PCA);
				$itemText = $iValue['title']; 
				if (strlen($value)>0) {
					$tmpItems .= TAB.$wrap[0].'<option '.($vValue==$value?'selected="selected" ':'').
							' value="'.$vValue.'" '.$classname.'>'.$itemText.'</option>'.$wrap[1].CRLF;
				} else {
					$tmpItems .= TAB.$wrap[0].'<option '.(!$set?'selected="selected" ':'').
							' value="'.$vValue.'" '.$classname.'>'.$itemText.'</option>'.$wrap[1].CRLF;
				}
				$myParams = $defParams;
				$myParams[$this->designator.'[search]['.$field.']'] = $vValue;
				$myConf['additionalParams'] = t3lib_div::implodeArrayForUrl('',$myParams,'',1);
				$link_options[] = QT.$this->cObj->typoLink_URL($myConf).QT;
				$set = true;
			}
			if (count($link_options)) {
				$this->js_array = 'dl_'.$this->designator.' = new Array('.implode(',',$link_options).');';
			}

			$item .= $wrapAll[0];
			if (is_array($directLink)) {
				if ($this->js_array) {
					$onc = $this->js_array." window.location = dl_".$this->designator."[this.selectedIndex]; return false;";
				} else {
					$onc = "document.searchform_".$this->designator.".submit(); return(false)";
				}
				$item .= '<select name="'.$this->designator.'[search]['.$field.']" '.$classname.' onchange="'.$onc.'">'.CRLF;
			} else {
				$item .= '<select name="'.$this->designator.'[search]['.$field.']" '.$onc.' '.$classname.'>'.CRLF;
			}

			$item .= $tmpItems.'</select>'.CRLF;
			$item .= $wrapAll[1];
		}
		return ($item);
	}



}

?>