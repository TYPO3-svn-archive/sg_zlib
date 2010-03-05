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
class tx_sglib_viewhelpers_input extends tx_sglib_viewhelper {
	protected $defaultKey = 'field';
	protected $searchParams;
	protected $myType = '';
	protected $fieldConf;
	protected $searchFieldConf;
	protected $formFieldName;

	protected $values = Array();
	protected $preserveFields = Array();
	protected $fieldMessages = Array();
	protected $fieldMessageTemplates = Array(
		'error'=>'###field###<span class="errormessage">###error###</span>', 
		'warning'=>'###field###<span class="warningmessage">###warning###</span>', 
		'info'=>'###field###<span class="infomessage">###info###</span>',
		);

	public function init(tx_sglib_factory $factoryObj, $formName='', $type='', $settings=Array()) {
		parent::init($factoryObj, $formName, $type, $settings);
		$this->formFieldName = $this->designator.'[data]';
		$this->fieldMessages = $this->settings['fieldMessages'];
		if (is_array($this->settings['fieldMessageTemplates'])) {
			$this->fieldMessageTemplates = $this->settings['fieldMessageTemplates'];
		}
		$this->values = t3lib_div::_GP($this->designator);
	}


	protected function renderContent() {
		$content = '';
		if (strcasecmp($this->params['field'],'FORMSTART')==0) {
			$content = $this->getFormStart();
		} elseif (strcasecmp($this->params['field'],'FORMEND')==0) {
			$content = $this->getFormEnd();
		} elseif (strcasecmp($this->params['field'],'SUBMIT')==0) {
			$content = $this->getSubmitButton();
		} elseif (strcasecmp($this->params['field'],'NEXT')==0) {
			$content = $this->getNextButton();
		} elseif (strcasecmp($this->params['field'],'BACK')==0) {
			$content = $this->getBackButton();
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
		$formName = 'inputform_'.$this->designator;
		if ($this->formName) {
			$formName .= '_'.$this->formName;
		}

		$method = 'post';
		if ($this->settings['method']) {
			$method = $this->settings['method'];
		}

		$action = 'index.php';
		if ($this->settings['action']) {
			$action = $this->settings['action'];
		}

		$content = '<form enctype="multipart/form-data" method="' . $method . '" name="'.$formName.'" id="'.$formName.'" action="' . $action .'">'."\n";
		$content .= '<input type="hidden" name="id" value="'.$id.'" />'."\n";

		if (isset($this->settings['step'])) {
			$content .= '<input type="hidden" name="step" value="'.intval($this->settings['step']).'" />'."\n";
		}
		// t3lib_div::debug(Array('$this->paramsObj->getPluginParams()'=>$this->paramsObj->getPluginParams(), 'File:Line'=>__FILE__.':'.__LINE__));

		$this->preserveFields = Array();
		if ($this->settings['preservedata']) {
			foreach ($this->values['data'] as $key=>$value) {
				$this->preserveFields[$key] = $key;
			}
		}

		return ($content);
	}


	protected function getFormEnd () {
		// t3lib_div::debug(Array('$this->preserveFields'=>$this->preserveFields, 'File:Line'=>__FILE__.':'.__LINE__));
		if (is_array($this->preserveFields)) {
			foreach ($this->preserveFields as $key) {
				$content .= '<input type="hidden" name="'.$this->formFieldName.'['.$key.']" value="'.htmlspecialchars($this->values['data'][$key]).'"/>' . "\n";
			}
		}
		$content .= '</form>';

		return ($content);
	}


	protected function getSubmitButton () {
		// t3lib_div::debug(Array('$this->params(submit)'=>$this->params, 'File:Line'=>__FILE__.':'.__LINE__));
		$content = '<input type="submit" name="submit" value="'.$this->getLabel().'">';

		return ($content);
	}

	protected function getNextButton () {
		// t3lib_div::debug(Array('$this->params(submit)'=>$this->params, 'File:Line'=>__FILE__.':'.__LINE__));
		$content = '<input type="submit" name="next" value="'.$this->getLabel().'">';

		return ($content);
	}

	protected function getBackButton () {
		// t3lib_div::debug(Array('$this->params(submit)'=>$this->params, 'File:Line'=>__FILE__.':'.__LINE__));
		$content = '<input type="submit" name="back" value="'.$this->getLabel().'">';

		return ($content);
	}






	protected function getFormField () {
		$this->myType = 'input';
		if ($this->params['type']) {
			$this->myType = $this->params['type'];
		}

		switch($this->myType)	{
			case 'input':
			case 'hidden':
				$content = $this->getInputFormField($this->table,$this->params['field']);
				break;
			case 'text':
				$content = $this->getTextFormField($this->table,$this->params['field']);
				break;
			case 'upload':
				$content = $this->getUploadFormField($this->table,$this->params['field']);
				break;
			case 'uploadinfo':
				$content = $this->getUploadInfoField($this->table,$this->params['field']);
				break;
			case 'select':
				$content = $this->getSelectFormField($this->table,$this->params['field']);
				break;
			default:
				$content = '[getFormField for "'.$this->params['field'].'"; type="'.$this->myType.'" - NOT YET SUPPORTED]';
				break;
		}

		if ($this->fieldMessages['error'][$this->params['field']]) {
			$content = str_replace(Array('###error###','###field###'),Array($this->fieldMessages['error'][$this->params['field']], $content),$this->fieldMessageTemplates['error']);
		} elseif ($this->fieldMessages['warning'][$this->params['field']]) {
			$content = str_replace(Array('###warning###','###field###'),Array($this->fieldMessages['warning'][$this->params['field']], $content),$this->fieldMessageTemplates['warning']);
		} elseif ($this->fieldMessages['info'][$this->params['field']]) {
			$content = str_replace(Array('###info###','###field###'),Array($this->fieldMessages['info'][$this->params['field']], $content),$this->fieldMessageTemplates['info']);
		}

		unset ($this->preserveFields[$this->params['field']]);

		$label = $this->getLabel();
		if ($label && $content) {
			$tmp = explode('|',$label);
			$content = ($tmp[0] ? '<label>'.$tmp[0].'</label>' : '' ).$content.($tmp[1] ? '<label>'.$tmp[1].'</label>' : '' );
		}

		return ($content);
	}

	protected function getInputFormField ($table,$field) {
		$content = '<input type="'.$this->myType.'"  name="'.$this->formFieldName.'['.$field.']"';

		if (strcmp($this->myType,'hidden')) {
			if ($this->params['classname']) {
				$content .= ' class="'.$this->params['classname'].'"';
			}
			if ($this->params['size']) {
				$content .= ' size="'.intval($this->params['size']).'"';
			}
		}
		$content .= ' value="'.htmlspecialchars($this->values['data'][$field]).'" />';

		return ($content);
	}

	protected function getTextFormField ($table,$field) {
		$content = '<textarea type="text"  name="'.$this->formFieldName.'['.$field.']"';

		if ($this->params['classname']) {
			$content .= ' class="'.$this->params['classname'].'"';
		}
		if ($this->params['cols']) {
			$content .= ' cols="'.intval($this->params['cols']).'"';
		}
		if ($this->params['rows']) {
			$content .= ' rows="'.intval($this->params['rows']).'"';
		}
		$content .= '>'.chr(10).htmlspecialchars($this->values['data'][$field]).'</textarea>';

		return ($content);
	}

	protected function getUploadFormField ($table,$field) {
		$content = '<input type="file"  name="'.$this->designator.'['.$field.']"';

		if ($this->params['classname']) {
			$content .= ' class="'.$this->params['classname'].'"';
		}
		if ($this->params['accept']) {
			$content .= ' accept="'.intval($this->params['accept']).'"';
		}
		$content .= ' value="" />';

		return ($content);
	}

	protected function getUploadInfoField ($table,$field) {
		$content = '';
		$uploads = $this->paramsObj->getUploads($field);
		if ($uploads->isMissing()) {
			$content .= '-no file uploaded-';
		} elseif ($uploads->isValid()) {
			$content .= 'filename="'.$uploads->getName().'", size='.$uploads->getSize(TRUE).'';
		} else {
			$content .= 'ERROR: name="'.$uploads->getName().'", error='.$uploads->getError().'';
		}

		return ($content);
	}

	protected function getSelectFormField ($table,$field) {
		$type = $this->myType;
		if ($this->params['type']) {
			$type = $this->params['type'];
		}

		$this->factoryObj->itemsObj->prepareItems($table,$field,SGZ_FORM,$this->row);
		$items = $this->factoryObj->itemsObj->getItemList($table,$field,SGZ_FORM);
//		t3lib_div::debug(Array('$items'=>$items, 'File:Line'=>__FILE__.':'.__LINE__));
//		if (isset($this->params['anyitem'])) {
//			$myItems = $items;
//			$items = Array('NULL'=>$this->params['anyitem']);
//			foreach ($myItems as $myKey=>$myItem) {
//				$items[$myKey] = $myItem;
//			}
//		}
		// t3lib_div::debug(Array('$items'=>$items, 'File:Line'=>__FILE__.':'.__LINE__));

		if ($type=='select') {
			$content = $this->getSelectNormalFormField ($table,$field,$items);
//		} elseif ($type=='check' || $type=='checklist') {
//			$content = $this->getSelectCheckFormField ($table,$field,$items);
		} else {
			$content = '[getSelectFormField for '.$field.'; type="'.$this->fieldConf['type'].'/'.$type.'"]';
		}

		return ($content);
	}

	protected function getSelectNormalFormField ($table,$field,$items) {
		if (is_array($this->settings['items'][$field]) && count($this->settings['items'][$field])) {
			$items = $this->settings['items'][$field];
		}

		$content = ''; //'[getSelectFormField for '.$field.'; type="'.$this->fieldConf['type'].'/CHECK"]';
		$wrap = explode('|',$this->params['wrap']);
		$selectedWasSet = FALSE;

		$options = Array();
		if ($this->params['fixed']) {
			$options[] = '<option value="'.$this->values['data'][$field].'" selected="selected">'.$items[$this->values['data'][$field]].'</option>';
		} else {
			foreach ($items as $itemKey=>$itemValue) {
				// t3lib_div::debug(Array('$itemKey'=>$itemKey, 'File:Line'=>__FILE__.':'.__LINE__));
				$optionValue = (strcmp($itemKey,'NULL')==0 ? 'NULL' : intval($itemKey));
				if (strlen($optionValue)==1 && $optionValue==0 && isset($items['NULL'])) {
					$optionValue = '00';
				}
				$option = '<option value="'.$optionValue.'"';
				if (!$selectedWasSet) {
					if (strcmp($itemKey,'NULL')==0) {
						if ((strlen($this->values['data'][$field])<1 || strcmp($this->values['data'][$field],'NULL')==0)) {
							$option .= ' selected="selected"';
							$selectedWasSet = TRUE;
						}
					} elseif (intval($itemKey)==intval($this->values['data'][$field])) {
						$option .= ' selected="selected"';
						$selectedWasSet = TRUE;
					}
				}
				$option .= '>'.$itemValue.'</option>';
				$options[] = $option;
			}
		}

		if (count($options)) {
			if ($this->params['fixed']) {
				$content = $wrap[0].
					'<select name="'.$this->formFieldName.'['.$field.']" disabled="disabled">'.implode("\n",$options).'</select>'.
					'<input type="hidden" name="'.$this->formFieldName.'['.$field.']" value="'.($this->values['data'][$field]).'" />'.
					$wrap[1];
			} else {
				$content = $wrap[0].'<select name="'.$this->formFieldName.'['.$field.']"'.$disabled.'>'.implode("\n",$options).'</select>'.$wrap[1];
			}
		} else {
			$content = $wrap[0].'<select name="'.$this->formFieldName.'['.$field.']">'.'</select>'.$wrap[1];
		}
		return ($content);
	}






}




?>