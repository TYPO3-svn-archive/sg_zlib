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
class tx_sglib_viewhelpers_search extends tx_sglib_viewhelper {
	protected $defaultKey = 'field';
	protected $searchParams;
	protected $myType = '';
	protected $fieldConf;
	protected $searchFieldConf;
	protected $formFieldName;
	protected $searchFieldParams;

	protected function renderContent() {
		$content = '';

		if (strcasecmp($this->params['field'],'FORMSTART')==0) {
			$content = $this->getFormStart();
		} elseif (strcasecmp($this->params['field'],'FORMEND')==0) {
			$content = $this->getFormEnd();
		} elseif (strcasecmp($this->params['field'],'SUBMIT')==0) {
			$content = $this->getSubmitButton();
		} elseif ($this->params['field']) {
			$content = $this->getFormField();
		}
		return ($content);
	}


	protected function getFormStart () {
		$id = $this->params['id']; 
		$nameOfFieldListPage = $this->params['ff'] ? $this->params['ff'] : 'fieldListPage';
		$fieldListPage = $this->confObj->getFFvalue($nameOfFieldListPage,'sDefault');
		// t3lib_div::debug(Array('$nameOfFieldListPage'=>$nameOfFieldListPage, '$fieldListPage'=>$fieldListPage, 'File:Line'=>__FILE__.':'.__LINE__));

		if ($fieldListPage) {
			$id = $fieldListPage;
		} elseif (!$id) {
			$id = $GLOBALS['TSFE']->id;
		}
		$formName = 'searchform_'.$this->designator;
		if ($this->formName) {
			$formName .= '_'.$this->formName;
		}
		$content = '<form method="get" name="'.$formName.'" id="'.$formName.'" action="index.php">'."\n";
		$content .= '<input type="hidden" name="'.$this->designator.'[search][c][name]" value="'.$formName.'" />'."\n";
		$content .= '<input type="hidden" name="'.$this->designator.'[search][c][mode]" value="1" />'."\n";
		$content .= '<input type="hidden" name="id" value="'.$id.'" />'."\n";
		// t3lib_div::debug(Array('$this->paramsObj->getPluginParams()'=>$this->paramsObj->getPluginParams(), 'File:Line'=>__FILE__.':'.__LINE__));

		return ($content);
	}


	protected function getFormEnd () {
		$content = '</form>';

		return ($content);
	}


	protected function getFormField () {
		$this->searchParams = $this->paramsObj->getPluginParams();
		$searchFieldName = $this->params['field'];
		$fieldConf = (array) $this->confObj[$this->table.'.']['conf.'][$searchFieldName.'.'];
		$searchFieldConf = (array) $this->confObj[$this->table.'.']['search.'][$searchFieldName.'.'];
		if ($searchFieldConf['formtype']) {
			$searchFieldConf['type'] = $searchFieldConf['formtype'];
		}
		$this->params = t3lib_div::array_merge_recursive_overrule(t3lib_div::array_merge_recursive_overrule($fieldConf,$searchFieldConf),$this->params);
		if ($searchFieldConf['field']) {
			$this->params['field'] = $searchFieldConf['field'];
		}
		// if ($searchFieldName=='region') t3lib_div::debug(Array('$params'=>$this->params, 'File:Line'=>__FILE__.':'.__LINE__));

		$tmp = t3lib_div::trimExplode('/',$this->params['field']);
		if (count($tmp)>1) {
			$field = $tmp[count($tmp)-1];
			unset ($tmp[count($tmp)-1]);
			$this->formFieldName = $this->designator.'[search]';
			$this->searchFieldParams = $this->searchParams['search'];

			$cnt = 0;
			$table = $this->table;
			while (isset($tmp[$cnt])) {
				$myRef = $this->confObj->getReferences($table);
				if (!isset($myRef['table'][$tmp[$cnt]])) {
					t3lib_div::debug(Array('ERROR:'=>'RefTabel not found', '$fieldRef'=>$tmp[$cnt], '$myRef'=>$myRef['table'], 'File:Line'=>__FILE__.':'.__LINE__));
					break;
				} else {
					$table = $myRef['table'][$tmp[$cnt]];
				}
				$this->formFieldName = $this->formFieldName.'[t]['.$tmp[$cnt].']';
				$this->searchFieldParams = $this->searchFieldParams['t'][$tmp[$cnt]];
				$cnt++;
			}



			$this->formFieldName = $this->formFieldName.'[f]';
			$this->searchFieldParams = $this->searchFieldParams['f'];

		} else {
			$field = $this->params['field'];
			$table = $this->table;
			$this->formFieldName = $this->designator.'[search][f]';
			$this->searchFieldParams = $this->searchParams['search']['f'];
		}
		$this->fieldConf = $this->confObj[$table.'.']['conf.'][$field.'.'];
		$this->searchFieldConf = $this->confObj[$table.'.']['search.'][$field.'.'];
		$this->myType = $this->fieldConf['type'];
		if (isset($this->searchFieldConf['formtype'])) {
			$this->myType = $this->searchFieldConf['formtype'];
		}
		if (isset($this->params['type'])) {
			$this->myType = $this->params['type'];
		}

		switch($this->myType)	{
			case 'from':
			case 'to':
			case '1':
			case 'input':
				$content = $this->getInputFormField($table,$field);
			break;
			case '2':
			case 'select':
			case 'check':
			case 'checklist':
				$content = $this->getSelectFormField($table,$field);
			break;
			default:
				// t3lib_div::debug(Array('$this->params'=>$this->params, '$field'=>$field, '$this->fieldConf'=>$this->fieldConf, 'File:Line'=>__FILE__.':'.__LINE__));
				$content = '[getFormField for "'.$field.'"; type="'.$this->myType.'" - NOT YET SUPPORTED]';
			break;
		}

		return ($content);
	}

	protected function getInputFormField ($table,$field) {
		$value = '';
		if (strcmp($this->params['type'],'from')==0) {
			$content = '<input type="text"  name="'.$this->formFieldName.'['.$field.'][from]"';
			$value = $this->searchFieldParams[$field]['from'];
		} elseif (strcmp($this->params['type'],'to')==0) {
			$content = '<input type="text"  name="'.$this->formFieldName.'['.$field.'][to]"';
			$value = $this->searchFieldParams[$field]['to'];
		} else {
			$content = '<input type="text"  name="'.$this->formFieldName.'['.$field.'][v]"';
			$value = $this->searchFieldParams[$field]['v'];
		}

		if ($this->params['classname']) {
			$content .= ' class="'.$this->params['classname'].'"';
		}
		if ($this->params['size']) {
			$content .= ' size="'.intval($this->params['size']).'"';
		}
		$content .= ' value="'.htmlspecialchars($value).'" />';

		return ($content);
	}

	protected function getSelectFormField ($table,$field) {
		$type = $this->myType;
		if ($this->params['type']) {
			$type = $this->params['type'];
		}

		$this->factoryObj->itemsObj->prepareItems($table,$field,SGZLIB_SEARCH,$this->row);
		$items = $this->factoryObj->itemsObj->getItemList($table,$field,SGZLIB_SEARCH);
		if (isset($this->params['anyitem'])) {
			$myItems = $items;
			$items = Array('NULL'=>$this->params['anyitem']);
			foreach ($myItems as $myKey=>$myItem) {
				$items[$myKey] = $myItem;
			}
		}
		// t3lib_div::debug(Array('$items'=>$items, 'File:Line'=>__FILE__.':'.__LINE__));

		if ($type=='select') {
			$content = $this->getSelectNormalFormField ($table,$field,$items);
		} elseif ($type=='check' || $type=='checklist') {
			$content = $this->getSelectCheckFormField ($table,$field,$items);
		} else {
			$content = '[getSelectFormField for '.$field.'; type="'.$this->fieldConf['type'].'/'.$type.'"]';
		}

		return ($content);
	}


	protected function getSelectNormalFormField ($table,$field,$items) {
		$content = ''; //'[getSelectFormField for '.$field.'; type="'.$this->fieldConf['type'].'/CHECK"]';
		$wrap = explode('|',$this->params['wrap']);
		$selectedWasSet = FALSE;

		$options = Array();
		foreach ($items as $itemKey=>$itemValue) {
			// t3lib_div::debug(Array('$itemKey'=>$itemKey, 'File:Line'=>__FILE__.':'.__LINE__));
			$optionValue = (strcmp($itemKey,'NULL')==0 ? 'NULL' : intval($itemKey));
			if (strlen($optionValue)==1 && $optionValue==0 && isset($items['NULL'])) {
				$optionValue = '00';
			}
			$option = '<option value="'.$optionValue.'"';
			if (!$selectedWasSet) {
				if (strcmp($itemKey,'NULL')==0) {
					if ((strlen($this->searchFieldParams[$field]['v'])<1 || strcmp($this->searchFieldParams[$field]['v'],'NULL')==0)) {
						$option .= ' selected="selected"';
						$selectedWasSet = TRUE;
					}
				} elseif (intval($itemKey)==intval($this->searchFieldParams[$field]['v'])) {
					$option .= ' selected="selected"';
					$selectedWasSet = TRUE;
				}
			}
			$option .= '>'.$itemValue.'</option>';
			$options[] = $option;
		}

		if (count($options)) {
			$content = $wrap[0].'<select name="'.$this->formFieldName.'['.$field.'][v]">'.implode("\n",$options).'</select>'.$wrap[1];
		} else {
			// TODO
		}
		return ($content);
	}


	protected function getSelectCheckFormField ($table,$field,$items) {
		unset ($items['0.']);
		$content = ''; //'[getSelectFormField for '.$field.'; type="'.$this->fieldConf['type'].'/CHECK"]';
		$wrap = explode('|',$this->params['wrap']);
		$wrapAll = explode('|',$this->params['wrapAll']);

		$checkBoxes = Array();
		foreach ($items as $itemKey=>$itemValue) {
			$checkBox = '<input type="checkbox" name="'.$this->formFieldName.'['.$field.'][v]['.intval($itemKey).']" value="'.intval($itemKey).'"';
			if (intval($itemKey)==intval($this->searchFieldParams[$field]['v'][intval($itemKey)])) {
				$checkBox .= ' checked="checked"';
			}
			$checkBox .= '>'.$itemValue.'</input>';
			$checkBoxes[] = $wrap[0].$checkBox.$wrap[1];
		}

		if (count($checkBoxes)) {
			$content = $wrapAll[0].implode($this->params['implode'],$checkBoxes).$wrapAll[1];
		} else {
			// TODO
		}


		return ($content);
	}


	protected function getSubmitButton () {
		// t3lib_div::debug(Array('$this->params(submit)'=>$this->params, 'File:Line'=>__FILE__.':'.__LINE__));
		$content = '<input type="submit" value="'.$this->getLabel().'">';

		return ($content);
	}



	/*****

	<form name="searchform_tx_sgaddress_pi1" id="searchform_tx_sgaddress_pi1" action="index.php">
	<input type="hidden" name="tx_sgaddress_pi1[searchformname]" value="form_tx_sgaddress_pi1" />
	<input type="hidden" name="id" value="299" />
	<input type="hidden" name="tx_sgaddress_pi1[searchmode]" value="1" />

		<td>Name:</td>
			<td><input type="text"  name="tx_sgaddress_pi1[search][company]" class="form_medium_text" size="40" value="" /></td>
		<td>Land:</td>
		<td><select name="tx_sgaddress_pi1[search][country]"  class="form_xsmall_select">
			<option selected="selected"  value="0" class="form_xsmall_select"></option>
			<option  value="54" class="form_xsmall_select">DE</option>

			<option  value="41" class="form_xsmall_select">CH</option>
			<option  value="104" class="form_xsmall_select">IT</option>
		</select></td>

		<a href="300.0.html?&no_cache=1&no_cache=1&type=0&dE=1&dN=1" onclick="var bw=''; bw = window.open('300.0.html?&no_cache=1&no_cache=1&type=0&dE=1&dN=1','AddressEdit','height=680,width=560,status=0,menubar=0,location=0,resizable=1,scrollbars=1'); bw.focus(); return false;">
			<img src="typo3temp/GB/d2028cb82a.gif" width="100" height="18" border="0" alt="Neuen Eintrag hinzufügen" title="Neuen Eintrag hinzufügen" />
		</a></td>

		<input type="image" src="typo3temp/GB/12888f3686.gif" />
	****/

}




?>