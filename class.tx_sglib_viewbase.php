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
	protected $confObj;
	protected $debugObj;
	protected $markersObj;
	protected $langObj;
	protected $model;
	protected $cached;

	function __construct ($designator, $factoryObj, $model, $cached) {
		$this->designator = $designator;
		$this->factoryObj = $factoryObj;
		$this->confObj = $factoryObj->confObj;
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
	 * @param	[type]		$opt: ...
	 * @return	[type]		...
	 */
	function getFeSingleField($table,$field,$row,$em,$opt=Array())	{
		$myType = strtolower($this->confObj->mainConf[$field.'.']['type']); 
		$mySearchType = strtolower($this->confObj->mainSearch[$field.'.']['formtype']);
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
				$item = $this->getSingleField_typeSelect($table,$field,$row,$em,$myType,$opt);
			break;
			case 'check':
				if ($em>=SGZLIB_SEARCH) {
					$item = $this->getSingleField_typeSelect($table,$field,$row,$em,$myType,$opt);
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
	 * @param	[type]		$myMode: ...
	 * @param	[type]		$opt: ...
	 * @return	[type]		...
	 */
	function getSingleField_typeSelect($table,$field,$row,$em,$myMode,$opt=Array()) {
		global $TCA;

		$fieldConfKey = $this->confObj->mainConf[$field.'.'];
		$searchConfKey = $this->confObj->mainSearch[$field.'.'];

//TODO		$classname = $this->getClassTag($field,'select',$PCA,$em);
//TODO		$classcheck = $this->getClassTag($field,'check',$PCA,$em);
//TODO		$classradio = $this->getClassTag($field,'radio',$PCA,$em);

		if ($myMode=='selectmulti' && intval($fieldConfKey['maxitems'])==1) {
			$myMode='selectsingle';
		}

		$myItems = $this->model->getRefItems($table,$field,$em,$row);

		if ($em==SGZLIB_FORM) {
		} else if ($em>=SGZLIB_SEARCHALL) {
			$onc = '';
			$item = $this->getSelectSearchList($myItems,$field,$row[$field],$onc,$classname,$myMode,$em);
		} else {
		}

		return (strlen($item)>0 ? $item : $this->confObj->nbspForSelect);
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
	function getSelectSearchList($myItems,$field,$value,$onc,$classname,$myMode,$em) {
		$wrap = t3lib_div::trimExplode('|',$this->confObj->mainSearch[$field.'.']['wrap']);
		$wrapAll = t3lib_div::trimExplode('|',$this->confObj->mainSearch[$field.'.']['wrapAll']);
		$wrapLabel = t3lib_div::trimExplode('|',$this->confObj->mainSearch[$field.'.']['wrapLabel']);
		$wrapInput = t3lib_div::trimExplode('|',$this->confObj->mainSearch[$field.'.']['wrapInput']);
		$directLink = $this->confObj->mainSearch[$field.'.']['directLink.'];
		$item = '';
		$set = false;

		// if ($field=="category") t3lib_div::debug(Array('$myItems'=>$myItems, 'File:Line'=>__FILE__.':'.__LINE__));
		$this->debugObj->debugIf('searchItems',Array('$myItems'=>$myItems, 'File:Line'=>__FILE__.':'.__LINE__),$field==$this->dodebug['searchItems']);

//		if ($this->confObj->mainSearch[$field.'.']['cntOrder']) {
//		} else if ($this->confObj->mainSearch[$field.'.']['alphaOrder']) {
//			asort ($myItems);
//		}

		$this->debugObj->debugIf('searchItems',Array('$myItems'=>$myItems, 'File:Line'=>__FILE__.':'.__LINE__),$field==$this->dodebug['searchItems']);
		if ($myMode=='radio') {
		} else if ($myMode=='checklist') {
		} else if ($myMode=='selectlist') {
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
			if ($this->confObj->mainSearch[$field.'.']['type']==3 && is_array($tmp['search'])) foreach ($tmp['search'] as $key=>$pValue) {
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