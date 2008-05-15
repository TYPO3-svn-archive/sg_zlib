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
 *   48: class tx_sglib_template
 *   80:     private function init(tx_sglib_factory $factoryObj)
 *  102:     private function _fCount ($name=NULL)
 *  125:     function __destruct()
 *  135:     private function _initTemplateFiles($conf)
 *  170:     function getTemplate ($templateName,$globalMarkers=Array())
 *  205:     function getSubPartName($forWhat)
 *  227:     function getSubpart ($tmpl,$name,$default='')
 *  274:     function get3Subpart ($tmpl,$name,$altName='',$default='')
 *  294:     private function _getASubpart ($tmpl,$list=Array(),$info='',$hideMissing=0)
 *  358:     function getConfSubpart ($tmpl,$subpartname,$PCA='',$extConf='',$typConf='',$defdef='',$hideMissing=0)
 *  462:     function getConfSubpartArray ($tmpl,$subpartname,&$PCA,$defaultdefault='')
 *  522:     function getSubpartFromArray ($spArray,$PCA='',$row='')
 *
 * TOTAL FUNCTIONS: 12
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_template {
	private static $instance = Array();

	private $factoryObj = NULL;
	private $confObj;
	private $debugObj;
	private $cObj;
	private $constObj;
	private $permitObj;
	private $conf=Array();
	private $templateFiles=Array();
	private $defaultDesignator;
	private $subPartNames;

	protected function __construct() {}

	private function __clone() {}

	public static function getInstance($designator, tx_sglib_factory $factoryObj) {
		if (!isset(self::$instance[$designator])) {
			self::$instance[$designator] = new tx_sglib_template();
			self::$instance[$designator]->init($factoryObj);
		}
		return (self::$instance[$designator]);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$tx_sglib_config $confObj, tx_sglib_debug $debugObj, tx_sglib_const $constObj, tx_sglib_lang $langObj, tx_sglib_permit $permitObj: ...
	 * @return	[type]		...
	 */
	private function init(tx_sglib_factory $factoryObj) {
		$this->_fCount(__FUNCTION__);
		$this->factoryObj = $factoryObj;
		$this->confObj = $factoryObj->confObj;
		$this->defaultDesignator = $this->confObj->getDesignator();
		$this->debugObj = $factoryObj->debugObj;
		$this->constObj = $factoryObj->constObj;
		$this->langObj = $factoryObj->langObj;
		$this->permitObj = $factoryObj->permitObj;
		$this->conf = (array) $this->confObj->templates;

		$this->cObj = t3lib_div::makeInstance('tslib_cObj');

		$this->_initTemplateFiles($this->conf['files.']);
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
	 * [Describe function...]
	 *
	 * @param	[type]		$conf: ...
	 * @return	[type]		...
	 */
	private function _initTemplateFiles($conf) {
		$this->_fCount(__FUNCTION__);
		if (is_array($conf)) foreach ($conf as $key=>$value) if(substr($key,-1)!='.') {
			if (isset($conf[$key.'.'])) {
				$this->templateFiles[$key] = $conf[$key];
				$this->templateFiles[$key.'.'] = $conf[$key.'.'];
			} else {
				$this->templateFiles[$key] = 'FILE';
				$this->templateFiles[$key.'.'] = Array('file'=>$value);
			}
		}

		if (!isset($this->templateFiles['catMenu'])) {
				$this->templateFiles['catMenu'] = $this->templateFiles['list'];
				$this->templateFiles['catMenu.'] = $this->templateFiles['list.'];
		}
		if (!isset($this->templateFiles['latestList'])) {
				$this->templateFiles['latestList'] = $this->templateFiles['list'];
				$this->templateFiles['latestList.'] = $this->templateFiles['list.'];
		}
		if (!isset($this->templateFiles['latestView'])) {
				$this->templateFiles['latestView'] = $this->templateFiles['single'];
				$this->templateFiles['latestView.'] = $this->templateFiles['single.'];
		}

		$this->debugObj->debugIf('templateConf',Array('template/files'=>$this->templateFiles, 'File:Line'=>__FILE__.':'.__LINE__));
	}

	/**
	 * @param	[type]		$templateName: ...
	 * @param	[type]		$globalmarkers: ...
	 * @param	[type]		$gConf: ...
	 * @param	[type]		$from: ...
	 * @return	[type]		...
	 */
	function getTemplate ($templateName,$globalMarkers=Array()) {
		static $templates = Array();
		$this->_fCount(__FUNCTION__);

		if (!strlen($templateName)) {
			throw new tx_sglib_templateexception ('No Template Name set',1);
			return ('');
		} else {
			if (!is_array($templates[$this->defaultDesignator])) {
				$templates[$this->defaultDesignator] = Array();
			}

			if (!isset($templates[$this->defaultDesignator][$templateName])) {
				$template = $this->cObj->cObjGetSingle($this->templateFiles[$templateName],$this->templateFiles[$templateName.'.']);
				$template = $this->permitObj->processTemplate($template);

				if (strlen($template)<10) {
					throw new tx_sglib_templateexception ('Templatefile empty or not found',2,'TemplateName = "'.$templateName.'"');
				}

				$template =  $this->cObj->substituteMarkerArray($template, $this->constObj->getMarkers() );
				$template =  $this->cObj->substituteMarkerArray($template, $globalMarkers);
				$this->debugObj->debugIf('templates',Array('name='=>$templateName, '$template'=>$template));
				$templates[$this->defaultDesignator][$templateName] =  $template;
			}

			return ($templates[$this->defaultDesignator][$templateName]);
		}
	}

	/**
	 * ********************************************************************************************
	 *
	 * Functions for Templates
	 *
	 * **********************************************************************************************/

	/*
	 *
	 * @param	[type]		$forWhat: ...
	 * @return	[type]		...
	 */
	function getSubPartName($forWhat) {
		$this->_fCount(__FUNCTION__);
		if (!is_array($this->subPartNames)) {
			$this->subPartNames = Array(
				'select' => 'SELECT_PART',
				'search' => 'SEARCH_PART',
				'list' => 'LIST_PART',
				'entry_header' => 'ENTRY_HEADER',
				'entry_part' => 'ENTRY_PART',
				'single' => 'SINGLE',
				);
		}

		return (strlen(trim($this->conf['subparts.'][$forWhat])>1) ? trim($this->conf['subparts.'][$forWhat]) : $this->subPartNames[$forWhat] );
	}

	/**
	 * @param	[type]		$tmpl: ...
	 * @param	[type]		$name: ...
	 * @param	[type]		$default: ...
	 * @return	[type]		...
	 */
	function getSubpart ($tmpl,$name,$default='') {
		$this->_fCount(__FUNCTION__);
		$retcode = '';
		$n = @substr_count($tmpl,$name);
		if ($n==2 && $name) {
			// OK - found
			$retcode = $this->cObj->getSubpart ($tmpl,$name);
		} else {
			if ($n) {
				// Illegal count of markers !
				$error = str_replace('###count###',$n,str_replace('###marker###',$name,$this->langObj->getLL('getSubpart.countError')));
				t3lib_div::debug(Array('ERROR:'=>$error, 'File:Line'=>__FILE__.':'.__LINE__));
				throw new tx_sglib_templateexception ('Named SubPart of Template not found',5,'$error; SubPartName = "'.$name.'"');
			}
			if ($default) {
				$n = substr_count($tmpl,$default);
				if ($n==2 && $n>1) {
					// OK - found
					$retcode = $this->cObj->getSubpart ($tmpl,$default);
				} else {
					if ($n) {
						$retcode = $this->cObj->getSubpart ($tmpl,$default);
						// Illegal count of markers !
						$error = str_replace('###count###',$n,str_replace('###marker###',$default,$this->langObj->getLL('getSubpart.countError')));
						t3lib_div::debug(Array('ERROR:'=>$error, 'File:Line'=>__FILE__.':'.__LINE__));
					} else {
						t3lib_div::debug(Array('ERROR:'=>$this->langObj->getLL('getSubpart.noMarkers'),
							'$name'=>$name, '$default'=>$default,
							'File:Line'=>__FILE__.':'.__LINE__));
					}
				}
			} else {
				throw new tx_sglib_templateexception ('Named SubPart of Template not found',6,'SubPartName = "'.$name.'"');
			}
		}
		return ($retcode);
	}


	/**
	 * @param	[type]		$tmpl: ...
	 * @param	[type]		$name: ...
	 * @param	[type]		$altName: ...
	 * @param	[type]		$default: ...
	 * @return	[type]		...
	 */
	function get3Subpart ($tmpl,$name,$altName='',$default='') {
		$this->_fCount(__FUNCTION__);
		$retcode = '';
		$n = @substr_count($tmpl,$name);
		if ($n==2 && $name) {
			// OK - found
			$retcode = $this->cObj->getSubpart ($tmpl,$name);
		} else {
			$retcode = $this->getSubpart($tmpl,$altName,$default);
		}
		return ($retcode);
	}

	/**
	 * @param	[type]		$tmpl: ...
	 * @param	[type]		$list: ...
	 * @param	[type]		$info: ...
	 * @param	[type]		$hideMissing: ...
	 * @return	[type]		...
	 */
	private function _getASubpart ($tmpl,$list=Array(),$info='',$hideMissing=0) {
		$this->_fCount(__FUNCTION__);
		$retcode = '';
		$this->getASinfo = '';
		$default = $list['default'];
		$defdef = isset($list['defdef']) ? $list['defdef'] : ' ';
		for (reset($list);$key=key($list);next($list)) if (strcmp('default',$key)) {
			$n = @substr_count($tmpl,$list[$key]);
			if ($n==2 && $list[$key]) {
				// OK - found
				$this->getASinfo .= 'getASubpart ('.$info.') found "'.$list[$key].'" | ';
				$retcode = $this->cObj->getSubpart ($tmpl,$list[$key]);
				break;
			} else if ($n) {
				// Illegal count of markers !
				$error = str_replace('###count###',$n,str_replace('###marker###',$list[$key],$this->langObj->getLL('getSubpart.countError')));
				t3lib_div::debug(Array('ERROR:'=>$error, 'File:Line'=>__FILE__.':'.__LINE__));
			}
		}

		if (strlen($retcode)<1) {
			if ($default || $defdef) {
				$n = substr_count($tmpl,$default);
				$n2 = substr_count($tmpl,$defdef);
				if ($n==2) {
					// OK - found
					$this->getASinfo .= 'getASubpart ('.$info.') found default "'.$default.'"';
					$retcode = $this->cObj->getSubpart ($tmpl,$default);
				} else if ($n2==2) {
					// OK - found
					$this->getASinfo .= 'getASubpart ('.$info.') found default "'.$defdef.'"';
					$retcode = $this->cObj->getSubpart ($tmpl,$defdef);
				} else {
					if ($n) {
						$retcode = $this->cObj->getSubpart ($tmpl,$default);
						// Illegal count of markers !
						$error = str_replace('###count###',$n,str_replace('###marker###',$default,$this->langObj->getLL('getSubpart.countError')));
						t3lib_div::debug(Array('ERROR:'=>$error, 'File:Line'=>__FILE__.':'.__LINE__));
					} else {
						$retcode = '';
						if (!$hideMissing) t3lib_div::debug(Array('ERROR:'=>$this->langObj->getLL('getSubpart.noMarkers'), 'Len of Template'=>strlen($tmpl),
							'$list'=>$list, 'info='=>$info, 'backtrace='=>$this->debugObj->shortBacktrace(5,1), 'File:Line'=>__FILE__.':'.__LINE__));
					}
				}
			} else {
				t3lib_div::debug(Array('ERROR:'=>$this->langObj->getLL('getSubpart.noMarkers'),
					'File:Line'=>__FILE__.':'.__LINE__));
			}
		}

		return ($retcode);
	}


	/**
	 * @param	[type]		$tmpl: ...
	 * @param	[type]		$subpartname: ...
	 * @param	[type]		$mode: if true ==> use '_FORM' Subpart, if available;
	 * @param	[type]		$extConf: ...
	 * @param	[type]		$typConf: ...
	 * @param	[type]		$defdef: ...
	 * @param	[type]		$hideMissing: ...
	 * @return	[type]		...
	 */
	function getConfSubpart ($tmpl,$subpartname,$PCA='',$extConf='',$typConf='',$defdef='',$hideMissing=0) {
		$this->_fCount(__FUNCTION__);
		$retcode = '';
		$spArray = Array();
		$editMode = 0;
		$piVarSearch = Array();
		$lmExt = '';

		if (is_array($PCA)) {
			$editMode = is_array($PCA['todo']) && $this->permitObj->useEditMode()  ? ($PCA['todo']['Edit'] | $PCA['todo']['Reload']) : 0 ;
			$piVarSearch = is_array($PCA['piVars']) ?  $PCA['piVars']['search'] : Array() ;
		}

		if (is_array($PCA['listmode'])) {
			if (!isset($piVarSearch['listmode'])) {
				if (isset($PCA['ctrl']['defaultListmode'])) {
					$piVarSearch['listmode'] = $PCA['ctrl']['defaultListmode'];
				} else {
					$piVarSearch['listmode'] = 'default';
				}
			}
		}

		if (isset($PCA['listmode'][$piVarSearch['listmode']])) {
			if (isset($PCA['listmode'][$piVarSearch['listmode']]['subpart'])) {
				$lmExt = $PCA['listmode'][$piVarSearch['listmode']]['subpart'];
			}
		}

		if ($editMode) {
			if (strlen($typConf)>0) {
				if (strlen($lmExt)>0 && strlen($extConf)>0) {
					$spArray['splmty_form'] = '###'.$subpartname.$typConf.$extConf.$lmExt.'_FORM###';
				}
				if (strlen($extConf)>0) {
					$spArray['spty_form'] = '###'.$subpartname.$typConf.$extConf.'_FORM###';
				}
				if (strlen($lmExt)>0) {
					$spArray['lmty_form'] = '###'.$subpartname.$typConf.$lmExt.'_FORM###';
				}
				$spArray['ty_form'] = '###'.$subpartname.$typConf.'_FORM###';
			}
			if (strlen($lmExt)>0 && strlen($extConf)>0) {
				$spArray['splm_form'] = '###'.$subpartname.$extConf.$lmExt.'_FORM###';
			}
			if (strlen($extConf)>0) {
				$spArray['sp_form'] = '###'.$subpartname.$extConf.'_FORM###';
			}
			if (strlen($lmExt)>0) {
				$spArray['lm_form'] = '###'.$subpartname.$lmExt.'_FORM###';
			}
			$spArray['form'] = '###'.$subpartname.'_FORM###';
		}

		if (strlen($typConf)>0) {
			if (strlen($lmExt)>0 && strlen($extConf)>0) {
				if ($this->permitObj->useEditMode()) { $spArray['splmty_fe'] = '###'.$subpartname.$typConf.$extConf.$lmExt.'_FE###'; }
				$spArray['splmty_default'] = '###'.$subpartname.$typConf.$extConf.$lmExt.'###';
			}
			if (strlen($extConf)>0) {
				if ($this->permitObj->useEditMode()) { $spArray['spty_fe'] = '###'.$subpartname.$typConf.$extConf.'_FE###'; }
				$spArray['spty_default'] = '###'.$subpartname.$typConf.$extConf.'###';
			}
			if (strlen($lmExt)>0) {
				if ($this->permitObj->useEditMode()) { $spArray['lmty_fe'] = '###'.$subpartname.$typConf.$lmExt.'_FE###'; }
				$spArray['lmty_default'] = '###'.$subpartname.$typConf.$lmExt.'###';
			}
			if ($this->permitObj->useEditMode()) { $spArray['ty_fe'] = '###'.$subpartname.$typConf.'_FE###'; }
			$spArray['ty_default'] = '###'.$subpartname.$typConf.'###';
		}

		if (strlen($lmExt)>0 && strlen($extConf)>0) {
			if ($this->permitObj->useEditMode()) { $spArray['splm_fe'] = '###'.$subpartname.$extConf.$lmExt.'_FE###'; }
			$spArray['splm_default'] = '###'.$subpartname.$extConf.$lmExt.'###';
		}
		if (strlen($extConf)>0) {
			if ($this->permitObj->useEditMode()) { $spArray['sp_fe'] = '###'.$subpartname.$extConf.'_FE###'; }
			$spArray['sp_default'] = '###'.$subpartname.$extConf.'###';
		}
		if (strlen($lmExt)>0) {
			if ($this->permitObj->useEditMode()) { $spArray['lm_fe'] = '###'.$subpartname.$lmExt.'_FE###'; }
			$spArray['lm_default'] = '###'.$subpartname.$lmExt.'###';
		}
		if ($this->permitObj->useEditMode()) { $spArray['fe'] = '###'.$subpartname.'_FE###'; }
		$spArray['default'] = '###'.$subpartname.'###';

		if (strlen($defdef)>0) {
			$spArray['defdef'] = '###'.$defdef.'###';
		}

		$ta = $this->_getASubpart($tmpl,$spArray,$subpartname.$typConf.$extConf.$lmExt,$hideMissing);
		$this->debugObj->debugIf('tmpl',Array('spArray('.$subpartname.')'=>$spArray, 'getASinfo='=>$this->getASinfo, 'File:Line'=>__FILE__.':'.__LINE__));
		$this->debugObj->debugIf('tmpl',Array('getASinfo='=>$this->getASinfo, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($ta);
	}


	/**
	 * @param	[type]		$tmpl: ...
	 * @param	[type]		$subpartname: ...
	 * @param	[type]		$mode: if true ==> use '_FORM' Subpart, if available;
	 * @param	[type]		$defaultdefault: ...
	 * @return	[type]		...
	 */
	function getConfSubpartArray ($tmpl,$subpartname,&$PCA,$defaultdefault='') {
		$this->_fCount(__FUNCTION__);
		$spArray = Array();
		$spName = Array();
		$mode = 0;
		$spField = '';
		$spExt = '';
		if (strlen($defaultdefault)>1) {
			$spName['defdef'] = $defaultdefault;
			$spArray['defdef'] = $this->getConfSubpart ($tmpl,$defaultdefault,$PCA);
		}

		$spName['default'] = $subpartname;
		$spArray['default'] = $this->getConfSubpart ($tmpl,$subpartname,$PCA,'','',$defaultdefault);

		// new
		if (is_array($PCA['ctrl']['subPartModes']) && is_array($PCA['ctrl']['typeModes'])) {
			 for (reset($PCA['ctrl']['typeModes']);$tKey=key($PCA['ctrl']['typeModes']);next($PCA['ctrl']['typeModes'])) {
				 for (reset($PCA['ctrl']['subPartModes']);$key=key($PCA['ctrl']['subPartModes']);next($PCA['ctrl']['subPartModes'])) {
					$spArray[$tKey.$key] = $this->getConfSubpart
						($tmpl,$subpartname,$PCA,$PCA['ctrl']['subPartModes'][$key]['subpart'],
								$PCA['ctrl']['typeModes'][$tKey]['subpart'],$defaultdefault);
					$spName[$tKey.$key] = $subpartname.$PCA['ctrl']['typeModes'][$tKey]['subpart'].$PCA['ctrl']['subPartModes'][$key]['subpart'].
						' - Len='.strlen($spArray[$tKey.$key]);
					$PCA['ctrl']['subPartModes'][$key]['mode'] = str_replace('###time###',time(),$PCA['ctrl']['subPartModes'][$key]['mode']);
				 }
				$spArray[$tKey] = $this->getConfSubpart ($tmpl,$subpartname,$PCA,$PCA['ctrl']['typeModes'][$tKey]['subpart'],'',$defaultdefault);
				$spName[$tKey] = $subpartname.$PCA['ctrl']['typeModes'][$tKey]['subpart'].' - Len='.strlen($spArray[$tKey]);
			 }
		} else if (is_array($PCA['ctrl']['subPartModes'])) {
			 for (reset($PCA['ctrl']['subPartModes']);$key=key($PCA['ctrl']['subPartModes']);next($PCA['ctrl']['subPartModes'])) {
				$spArray[$key] = $this->getConfSubpart
					($tmpl,$subpartname,$PCA,$PCA['ctrl']['subPartModes'][$key]['subpart'],'',$defaultdefault);
				$spName[$key] = $subpartname.$PCA['ctrl']['subPartModes'][$key]['subpart'].' - Len='.strlen($spArray[$key]);
				$PCA['ctrl']['subPartModes'][$key]['mode'] = str_replace('###time###',time(),$PCA['ctrl']['subPartModes'][$key]['mode']);
			 }
		// new
		} else if (is_array($PCA['ctrl']['typeModes'])) {
			 for (reset($PCA['ctrl']['typeModes']);$key=key($PCA['ctrl']['typeModes']);next($PCA['ctrl']['typeModes'])) {
				$spArray[$key] = $this->getConfSubpart
					($tmpl,$subpartname,$PCA,'',$PCA['ctrl']['typeModes'][$key]['subpart'],'',$defaultdefault);
				$spName[$key] = $subpartname.$PCA['ctrl']['typeModes'][$key]['subpart'].' - Len='.strlen($spArray[$key]);
			 }
		}

		$this->debugObj->debugIf('tmpl', Array('$spName'=>$spName, 'File:Line'=>__FILE__.':'.__LINE__));
		if ($this->dodebug['tmpl']>6) {
			t3lib_div::debug(Array('keys:'=>implode("\n ",array_keys($spArray)), 'File:Line'=>__FILE__.':'.__LINE__));
			t3lib_div::debug(Array('subPartsArray'=>$spArray, 'File:Line'=>__FILE__.':'.__LINE__));
		}
		return ($spArray);
	}


	/**
	 * @param	[type]		$tmpl: ...
	 * @param	[type]		$subpartname: ...
	 * @param	[type]		$mode: if true ==> use '_FORM' Subpart, if available;
	 * @return	[type]		...
	 */
	function getSubpartFromArray ($spArray,$PCA='',$row='') {
		$this->_fCount(__FUNCTION__);
		$retcode = '';
		if (is_array($row)) {
			$types = Array();
			$types[0] = '';
			if (strlen($PCA['ctrl']['type'])>0 && is_array($PCA['ctrl']['typeModes'])) {
				for (reset($PCA['ctrl']['typeModes']);$key=key($PCA['ctrl']['typeModes']);next($PCA['ctrl']['typeModes'])) {
					$types[intval($PCA['ctrl']['typeModes'][$key]['mode'])] = $key;
				}
			}

			$type = '';
			if (strlen($PCA['ctrl']['type'])>0) {
				$type = $types[($row[$PCA['ctrl']['type']])];
			}
			$this->debugObj->debugIf('tmpl',Array('$types'=>$types, '$type'=>$type, 'File:Line'=>__FILE__.':'.__LINE__));

			if (strlen($PCA['ctrl']['subPartField'])>0 && is_array($PCA['ctrl']['subPartModes'])) {
				$mySpVal = $row[$PCA['ctrl']['subPartField']];
				 for (reset($PCA['ctrl']['subPartModes']);$key=key($PCA['ctrl']['subPartModes']);next($PCA['ctrl']['subPartModes'])) {
					$cmp = $PCA['ctrl']['subPartModes'][$key]['mode'];
					if ($cmp[0]=='<' && $mySpVal<substr($cmp,1)) {
						$retcode = $spArray[$type.$key];
					} else if ($cmp[0]=='>' && $mySpVal>substr($cmp,1)) {
						$retcode = $spArray[$type.$key];
					} else if ($cmp[0]=='=' && $mySpVal==substr($cmp,1)) {
						$retcode = $spArray[$type.$key];
					} else if (strcmp($mySpVal,$cmp)==0) {
						$retcode = $spArray[$type.$key];
					}
					if ($this->dodebug['tmpl']>1 && strcmp($retcode,$spArray[$key])==0) {
						t3lib_div::debug(Array('Selected (Len='.strlen($retcode).'):'=>$type.$key, 'File:Line'=>__FILE__.':'.__LINE__));
					}
				 }
				 if (strlen($retcode)<1) {
					$retcode = $spArray[$type];
					$this->debugObj->debugIf('tmpl',Array('Selected (Len='.strlen($retcode).'):'=>$type, 'File:Line'=>__FILE__.':'.__LINE__));
				 }
			} else {
				$retcode = $spArray[$type];
			}

			if (strlen($retcode)<1 && strlen($PCA['ctrl']['subPartField'])>0 && is_array($PCA['ctrl']['subPartModes'])) {
				$mySpVal = $row[$PCA['ctrl']['subPartField']];
				 for (reset($PCA['ctrl']['subPartModes']);$key=key($PCA['ctrl']['subPartModes']);next($PCA['ctrl']['subPartModes'])) {
					$cmp = $PCA['ctrl']['subPartModes'][$key]['mode'];
					if ($cmp[0]=='<' && $mySpVal<substr($cmp,1)) {
						$retcode = $spArray[$key];
					} else if ($cmp[0]=='>' && $mySpVal>substr($cmp,1)) {
						$retcode = $spArray[$key];
					} else if ($cmp[0]=='=' && $mySpVal==substr($cmp,1)) {
						$retcode = $spArray[$key];
					} else if (strcmp($mySpVal,$cmp)==0) {
						$retcode = $spArray[$key];
					}
					if ($this->dodebug['tmpl']>1 && strcmp($retcode,$spArray[$key])==0) {
						t3lib_div::debug(Array('Selected (Len='.strlen($retcode).'):'=>$key, 'File:Line'=>__FILE__.':'.__LINE__));
					}
				 }
			}

		}

		if (strlen($retcode)<1) {
			if ($spArray['default']) {
				$retcode = $spArray['default'];
				$this->debugObj->debugIf('tmpl',Array('Default Selected (Len='.strlen($retcode).'):'=>'', 'File:Line'=>__FILE__.':'.__LINE__));
			} else {
				$retcode = '[[ERROR: SubPart not found - '.__FILE__.':'.__LINE__.']]';
			}
		}

		return ($retcode);
	}


	/**
	 * @param	[type]		$templateName: ...
	 * @param	[type]		$globalmarkers: ...
	 * @param	[type]		$gConf: ...
	 * @param	[type]		$from: ...
	 * @return	[type]		...
	 */
	public function getNamed ($named,$globalMarkers=Array()) {
		$name = $this->conf['named.'][$named];
		$tmp = t3lib_div::trimExplode(':',$name,2);
		if (!strlen($name) || !strlen($tmp[0]) || ! strlen($tmp[1])) {
			throw new tx_sglib_templateexception ('Named Template/SubPart empty or not found',4,'Name = "'.$named.'"');
		}
		$template = $this->getTemplate ($tmp[0],$globalMarkers);
		$subPart = $this->getSubpart ($template,strtoupper($tmp[1]));
		return ($subPart);
	}

//		$tmp = $this->cObj->fileResource($templateFilename);
//		if (!strlen($tmp)) {
//			throw new tx_sglib_viewexception ('Templatefile empty or not found',2,'TemplateFileName = "'.$templateFilename.'"');
//		}
//		$subpart = $subpart ? $subpart : 'main';
//		$this->template = $this->cObj->getSubpart($tmp,'###'.$subpart.'###');
//		if (!strlen($this->template)) {
//			throw new tx_sglib_viewexception ('Subpart empty or not found',3,'TemplateFileName="'.$templateFilename.'" SubpartName="'.$subpart.'"');
//		}



}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sglib_template.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sglib_template.php']);
}
?>