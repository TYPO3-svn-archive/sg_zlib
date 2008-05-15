<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2002-2006 Stefan Geith (typo3dev2006@geithware.de)
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
 *   46: class txsg_base_import extends txsg_base
 *   61:     function DoImport()
 * 1649:     function newsImportHasHref ($text)
 * 1682:     function newsReplace ($params,$text,&$eregList,&$eregCount)
 * 1736:     function newsGetImportFields ($fields,$key,$xKey,$tPar,&$myData,&$mData)
 * 1789:     function importPreProcess(&$myData,$input=Array(),$conf=Array())
 *
 * TOTAL FUNCTIONS: 5
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(t3lib_extMgm::extPath('sg_zlib').'class.txsg_base.php');

class txsg_base_import extends txsg_base {

	/*********************************************************************************
	**
	** Import of tab-separated List of Entries (external file; defined by TS)
	**
	*********************************************************************************/

	/**
	 * [Put your description here]
	 *
	 * @param	[type]		$content: ...
	 * @param	[type]		$conf: ...
	 * @return	[type]		...
	 */
	function DoImport()	{
		GLOBAL $TSFE, $_FILES;

		$basePathLocal = t3lib_extMgm::extPath('sg_zlib').'locallang_import.php';
		$basePathExt = t3lib_extMgm::extPath($this->extKey).dirname($this->scriptRelPath).'/locallang_import.php';

		$tempLOCAL_LANG = t3lib_div::readLLfile($basePathExt,$this->LLkey);
		$this->LOCAL_LANG = t3lib_div::array_merge_recursive_overrule
			(is_array($this->LOCAL_LANG) ? $this->LOCAL_LANG : array(),$tempLOCAL_LANG);

		$this->constText = $this->langObj->getLangArray($basePathLocal,$basePathExt);
		// t3lib_div::debug(Array('$this->constText'=>$this->constText, 'File:Line'=>__FILE__.':'.__LINE__));

		$TSFE->set_no_cache();

		$preset = Array(0, 0,0,0,0,0,0,0,0,0);
		$content = '';
		// ----------------------------------------------------------------------------------------------------------------

		$importName = 'u'.$TSFE->fe_user->user['uid'].'_'.(strlen($this->importName)>1 ? $this->importName : 'z_import');

		$importState = intval(t3lib_div::GPvar('impSt'));
		$fileType = intval(t3lib_div::GPvar('filetype'));
		$myTime = time();
		$this->myTime = $myTime;
		$this->myMaxTime = $myTime + 365 * 24 * 3600;
		$minImportFields = 2;

		$cfImport = $this->confObj->import; //(isset($this->conf['import.']) ? $this->conf['import.'] : Array() );

		$myTypes = Array();
		$myLabels = Array();
		$myTables = Array();
		if (is_array($cfImport))  {
			for (reset($cfImport);$key=key($cfImport);next($cfImport)) {
				$myTypes[] = str_replace('.','',$key);
				// $myLabels[] = $cfImport[$key]['label'];
				$myLabels[] = $this->cObj->stdWrap($cfImport[$key]['label'],$cfImport[$key]['label.']);
				$myTables[] = $cfImport[$key]['table'] ? $cfImport[$key]['table'] : str_replace('.','',$key);
			}
		} else {
			$content = "<br />ERROR: No Imports defined in TS ! <br />";
		}

		if ($importState<1) {
			if (count($myTypes)>1) {
				$content .= '<br /><table border=1 cellspacing=0 cellpadding=3>';
				$content .= '<tr><td colspan="2"><b>Please select Import-Mode</b></td></tr>';
				for ($i=0;$i<count($myTypes);$i++) {
					$content .= '<tr><td align="center" valign="top">'.($i+1).'</td>';
					$content .= '<td align="left" valign="top"><a href="'.$this->myPage.'&filetype='.($i+1).'&impSt=1'.'">'.$myLabels[$i].'</a></td></tr>';
				}
				$content .= '</table>';
				$importState = 0;
			} else if (count($myTypes)>0) {
				$importState = 1;
				$fileType = 1;
			} else {
				$importState = -1;
			}
		} else if ($importState==2 && (!is_array($_FILES) || strlen($_FILES['datei']['name'])<1 ) ) {
			$importState = 1;
		}

		if ($importState<0) {
			$content .= 'ERROR: importstate=0<br />';
		} else if ($importState>0)  {

			$myFile = $myTables[($fileType-1)];
			$cfImport = $cfImport[$myTypes[($fileType-1)].'.'];
			$workOnTable = $cfImport['table'] ? $cfImport['table'] : $this->mainTable;


			$importTmpl =  $this->templateObj->getTemplate(($cfImport['useTemplate']) ? $cfImport['useTemplate'] : 'import',$this->globalMarkers);
			$this->PCA = $this->felib->getPCA($myFile,$this->prefixId,$this->pid,$this->conf,$this->localConf);
			//t3lib_div::debug(Array('$myFile'=>$myFile, '$cfImport'=>$cfImport, '$this->pid'=>$this->pid, 'File:Line'=>__FILE__.':'.__LINE__));

			$settings = isset($cfImport['settings.']) ? $cfImport['settings.'] : '';
			$fields = isset($cfImport) ? $cfImport['fields.'] : '';
			for (reset($fields);$key=key($fields);next($fields)) {
				$fields[$key]['id'] = explode(',',$fields[$key]['id']);
			}

			$totalStates = 5;
			$activeState = $importState;
			if ($settings['skip3']) {
				$totalStates--;
				if ($importState==3) {
					$importState = 4;
				}
				if ($activeState>3) {
					$activeState--;
				}
			}

			if (isset($cfImport) && isset($cfImport['show.']) && count($cfImport['show.'])>0) {
				$show = Array();
				for (reset($cfImport['show.']);$key=key($cfImport['show.']);next($cfImport['show.'])) {
					$show[] = $cfImport['show.'][$key];
				}
			}

			if (isset($cfImport) && isset($cfImport['result.']) && count($cfImport['result.'])>0) {
				$result = Array();
				for (reset($cfImport['result.']);$key=key($cfImport['result.']);next($cfImport['result.'])) {
					$result[] = $cfImport['result.'][$key];
				}
			}
			if (!is_array($result)) {
				$result = $show;
			}

			if (is_array($settings) && is_array($fields)) {
				$mHeaders = Array();
				for (reset($fields);$key=key($fields);next($fields)) {
					$mHeaders['###'.str_replace('.','',$key).'###'] = str_replace('.','',$key);
				}

				$impIdx = Array();
				$imports = Array();
				$setvals = Array();
				for (reset($fields);$key=key($fields);next($fields)) {
					if (count($fields[$key]['id'])>1) {
						for ($i1=0;$i1<count($fields[$key]['id']);$i1++) {
							$imports[$fields[$key]['id'][$i1].'.'] = str_replace('.','',$key);
							$impIdx[$fields[$key]['id'][$i1].'.'] = '('.($i1+1).'/'.count($fields[$key]['id']).')';
							if (intval($fields[$key]['id'][$i1])>intval($minImportFields)) { $minImportFields = intval($fields[$key]['id'][$i1]); }
						}
					} else if (intval($fields[$key]['id'][0])>0) {
						$imports[$fields[$key]['id'][0].'.'] = str_replace('.','',$key);
						$impIdx[$fields[$key]['id'][0].'.'] = '';
						if (intval($fields[$key]['id'][0])>intval($minImportFields)) { $minImportFields = intval($fields[$key]['id'][0]); }
					} else {
						$setvals[str_replace('.','',$key)] = $fields[$key];
					}
				}
				ksort ($imports);

				$sp = $this->templateObj->getSubpart($importTmpl,'###TITLE###');
				$m = Array();
				$m['###TITLETEXT###'] = $this->cObj->stdWrap($settings['title'],$settings['title.']);
				$m['###STATE###'] = $activeState;
				$m['###MAXSTATE###'] = $totalStates;
				$content .= $this->cObj->substituteMarkerArray($sp,$m);

				$this->constText['imp_info_pid'] = sprintf($this->constText['imp_info_pid'],$this->pid);
				$this->constText['imp_info_userid'] =
					sprintf($this->constText['imp_info_userid'],$TSFE->fe_user->user['uid'],$TSFE->fe_user->user['username']);
				$this->constText['imp_info_defaultpid'] =
					sprintf($this->constText['imp_info_defaultpid'],$this->confObj->userStorageID);
				$this->constText['imp_info_usergroup'] =
					sprintf($this->constText['imp_info_usergroup'],$this->confObj->defaultUsergroup);

				$m = Array();
				for (reset($this->constText);$key=key($this->constText);next($this->constText)) {
					$m['###'.$key.'###'] = $this->constText[$key];
				}
				$m['###ERROR_HEADER###'] = $this->constObj->getWrap('hot',$this->constText['imp_error_header']);
				$m['###ERROR_MESSAGE###'] = '';
				$m['###COUNT_DBERROR###'] = 0;
				$m['###COUNT_INSERT###'] = 0;
				$m['###COUNT_REPLACE###'] = 0;
				$m['###LIST_DBERROR###'] = '';

				if ($importState==1) {
					$errors = FALSE;
					$sp = $this->templateObj->getSubpart($importTmpl,'###PART1###');

					$m['###PARTNUMBER###'] = $this->cObj->stdWrap($settings['part1'],$settings['part1.']);
					$m['###ACTION###'] = $this->pi_getPageLink($GLOBALS["TSFE"]->id);
					$m['###HIDDENDATA###'] = '<input type="hidden" name="id" value="'.$GLOBALS["TSFE"]->id.'">'.
							'<input type="hidden" name="import[tstamp]" value="'.time().'">'.
							'<input type="hidden" name="import[pid]" value="'.$this->pid.'">'.
							'<input type="hidden" name="import[feuser]" value="'.$this->permitObj->getFeUid().'">'.
							'<input type="hidden" name="impSt" value="2">'.
							'<input type="hidden" name="MAX_FILE_SIZE" value="20000000">'.
							'<input type="hidden" name="filetype" value="'.$fileType.'">'.
							'';

					$m['###INPUT###'] = '';
					$m['###FILE###'] = '<input name="datei" type="file" size=50 maxlength=20000000 accept="text/*">';
					$m['###FILEINFO###'] = str_replace('###COUNT###',$minImportFields,
							$this->cObj->stdWrap($settings['fieldCountText'],$settings['fieldCountText.']));

					if (is_array($settings['separator.'])) {
						if (count($settings['separator.'])>1) {
							$m['###FILESEPARATOR###'] = '<select name="import[separator]">';
							for (reset($settings['separator.']);$key=key($settings['separator.']);next($settings['separator.'])) {
								$m['###FILESEPARATOR###'] .= '<option value="'.$settings['separator.'][$key]['value'].'">'.
									$this->cObj->stdWrap($settings['separator.'][$key]['text'],$settings['separator.'][$key]['text.'])
									.'</option>';
							}
							$m['###FILESEPARATOR###'] .= '</select';
						} else {
							reset($settings['separator.']);
							$key = key($settings['separator.']);
							$m['###FILESEPARATOR###'] = '<input type="hidden" value="'.
								$settings['separator.'][$key]['value'].'" />'.
								$this->cObj->stdWrap($settings['separator.'][$key]['text'],$settings['separator.'][$key]['text.']);
						}
					} else {
						$m['###FILESEPARATOR###'] = '<input type="hidden" value="tab" />[TAB]';
					}

					$m['###SUBMIT###'] = '<input type="submit" value="'.$this->constText['imp_submit'].'">';


					$m['###STATE###'] = $activeState;
					$m['###MAXSTATE###'] = $totalStates;

					if (intval($settings['pid.']['value'])>(-5) || is_array($settings['input.'])) {
						$m['###INPUT###'] = '<table border=0 cellspacing=0 cellpadding=1>';

						if (intval($settings['pid.']['value'])>(-5)) {
						$m['###INPUT###'] .= '<tr><td>'.$settings['pid.']['label'].': &nbsp;</td>';
							$m['###INPUT###'] .= '<td><input type="text" name="import[pid]" value="'.
											(intval($settings['pid.']['value'])>=0 ? intval($settings['pid.']['value']):intval($this->pid)).'"></td></tr>';
						}
						if (is_array($settings['input.'])) {
							for (reset($settings['input.']);$key=key($settings['input.']);next($settings['input.'])) {
								$m['###INPUT###'] .= '<tr><td>'.$settings['input.'][$key]['label'].': &nbsp;</td>';
								$m['###INPUT###'] .= '<td><input type="text" name="import[input]['.$key.']" value="'.
									( intval($settings['input.'][$key]['value'])>=0 ? $settings['input.'][$key]['value'] : $preset[intval($key)] )
									.'"></td></tr>';
							}
						}
						$m['###INPUT###'] .= '</table>';
					} else {
						$sp = $this->cObj->substituteSubpart($sp,'###PART1INPUT###','');
					}

					if (!is_array($imports) || count($imports)<1) {
						$sp = $this->cObj->substituteSubpart($sp,'###PART1FIELDORDERBLOCK###','');
					} else {
						$fob = '';
						$fobTmpl = $this->templateObj->getSubpart($importTmpl,'###PART1FIELDORDER###');
						for (reset($imports);$key=key($imports);next($imports)) {
							if (intval($key)>0) {
								if (isset($this->PCA['conf'][$imports[$key]]) || strcmp('uid',$imports[$key])==0) {
									$myText = $imports[$key].' '.$impIdx[$key];
									$myLabel = $this->langObj->getLL($this->PCA['conf'][$imports[$key]]['label']);
									if (isset($fields[$myText.'.']['eval']) && strlen($fields[$myText.'.']['eval'])>0) {
										$myText .= ' <i>(eval='.$fields[$myText.'.']['eval'].')</i>';
									}
								} else {
									$myText = $this->constObj->getWrap('hot','ERROR: "'.$imports[$key].'" undefined');
									$errors = TRUE;
									$myLabel = '??';
								}
								$fob .= str_replace('###LABEL###',$myLabel,
										str_replace('###ID###',$key,
										str_replace('###TEXT###',$myText,$fobTmpl)));
							}
						}
						$sp = $this->cObj->substituteSubpart($sp,'###PART1FIELDORDER###',$fob);
					}

					if (is_array($setvals) && count($setvals)>0) {
						$fsb = '';
						$fsbTmpl = $this->templateObj->getSubpart($importTmpl,'###PART1FIELDSET###');
						for (reset($setvals);$key=key($setvals);next($setvals)) {
							if (isset($this->PCA['conf'][$key]) || strcmp($key,'pid')==0  || strcmp($key,'tstamp')==0 || strcmp($key,'crdate')==0 ) {
								$mySet = $setvals[$key]['set'];
								if (strlen($mySet)<1) {
									$mySet = '<font color=#008000><i>--empty--</i></font>';
								} else if (strncmp($mySet,'input',5)==0) {
									$mySet = 'val(<font color=#000080>'.$settings['input.'][intval(substr($mySet,5)).'.']['label'].'</font>)';
								} else if (strcmp($mySet,'pid')==0) {
									$mySet = '<font color=#008000><i>'.$this->constText['imp_act_pid'].' = '.$this->pid.'</i></font>';
								} else if (strcmp($mySet,'feuser')==0) {
									$mySet = '<font color=#008000><i>'.$this->constText['imp_act_feuser'].' = '.
										$this->permitObj->getFeUid().'</i></font>';
								} else if (strcmp($mySet,'time')==0) {
									$mySet = '<font color=#008000><i>'.$this->constText['imp_act_time'].' = '.time().'</i></font>';
								} else if (strcmp($mySet,'empty')==0) {
									$mySet = '""';
								} else if (strncmp($mySet,'CONST:',6)==0) {
									$tmp = str_replace('###time###',date('His',$myTime),str_replace('###date###',date('Ymd',$myTime),
										substr($mySet,6)));
									$mySet = '<font color=#008000><i>'.$this->constText['imp_act_const'].' = '.$tmp.'</i></font>';
								} else {
									$mySet = DQT.$mySet.DQT;
								}
							} else {
								$mySet = $this->constObj->getWrap('hot','ERROR: "'.$key.'" undefined');
								$errors = TRUE;
							}
							$fsb .= str_replace('###FIELD###',$key,str_replace('###TEXT###',$mySet,$fsbTmpl));
						}
						$sp = $this->cObj->substituteSubpart($sp,'###PART1FIELDSET###',$fsb);
					} else {
						$sp = $this->cObj->substituteSubpart($sp,'###PART1FIELDSETBLOCK###','');
					}

					if ($errors) {
						$sp = $this->cObj->substituteSubpart($sp,'###PART1SUBMIT###','');
					}

					$content .= $this->cObj->substituteMarkerArray($sp,$m);

				} else if ($importState==2) { // ##################################################################################
					$errors = FALSE;
					$sp = $this->templateObj->getSubpart($importTmpl,'###PART2###');
					$import = t3lib_div::GPvar('import');
					$m['###PARTNUMBER###'] = $this->cObj->stdWrap($settings['part2'],$settings['part2.']);
					$m['###ACTION###'] = $this->pi_getPageLink($GLOBALS["TSFE"]->id);
					$m['###HIDDENDATA###'] = '<input type="hidden" name="id" value="'.$GLOBALS["TSFE"]->id.'">'.
							'<input type="hidden" name="filetype" value="'.$fileType.'">'.
							'<input type="hidden" name="moreimport" value="'.urlencode(serialize($import)).'">'.
							'';
					$m['###STATE###'] = $activeState;
					$m['###MAXSTATE###'] = $totalStates;

					$separator = "\t";
					if (strlen($import['separator'])>0) {
						if (strcmp($import['separator'],'tab')==0) {
							$separator = "\t";
						} else if (strcmp($import['separator'],'pipe')==0) {
							$separator = "|";
						} else {
							$separator = $import['separator'];
						}
					}

					$m['###TEXT_FILENAME###'] = $_FILES['datei']['name'];
					$m['###STATUS_FILENAME###'] = 'OK';

					$m['###TEXT_FILESIZE###'] = $_FILES['datei']['size']."bytes = ".
						floor(($_FILES['datei']['size']+512)/1024)."kBytes";
					$maxSizeError = intval($settings['maxSizeError']) ? intval($settings['maxSizeError']) : 9000000;
					$minSizeError = intval($settings['minSizeError']) ? intval($settings['minSizeError']) : 2000;
					$minSizeWarn = intval($settings['minSizeWarn']) ? intval($settings['minSizeWarn']) : 200;
					if ($_FILES['datei']['size']>$maxSizeError)	{
						$m['###STATUS_FILESIZE###'] = "<font color=#ff0000>FEHLER: zu gro&szlig;</font>";
					} else if ($_FILES['datei']['size']<$minSizeWarn) {
						$m['###STATUS_FILESIZE###'] ="<font color=#ff0000>WARNUNG: Datei evtl. nicht vollständig</font>";
					} else if ($_FILES['datei']['size']<$minSizeError) {
						$m['###STATUS_FILESIZE###'] ="<font color=#ff0000>FEHLER: Datei vermutlich nicht vollständig</font>";
						$errors = TRUE;
					} else {
						$m['###STATUS_FILESIZE###'] ="<font color=#008000>OK</font>";
					}

					$m['###TEXT_FILETEMP###'] = $_FILES['datei']['tmp_name'];
					$m['###STATUS_FILETEMP###'] = 'OK';

					$m['###TEXT_FILELINES###'] = '';
					$m['###STATUS_FILELINES###'] = '';

					if (is_uploaded_file($_FILES['datei']['tmp_name'])) {
						move_uploaded_file($_FILES['datei']['tmp_name'], 'typo3temp/'.$importName.'.txt');

						//$aLines = file ('typo3temp/'.$importName.'.txt');
						$concat = $settings['concat'];
						$aLines = $this->felib->getImportFile('typo3temp/'.$importName.'.txt',
							$minImportFields,$separator,$concat);

						$maxLineWarn = intval($settings['maxLineWarn']) ? intval($settings['maxLineWarn']) : 20000;
						$minLineWarn = intval($settings['minLineWarn']) ? intval($settings['minLineWarn']) : 5;
						$m['###TEXT_FILELINES###'] = count($aLines);
						if (count($aLines)>$maxLineWarn)
							{ $m['###STATUS_FILELINES###'] = '<font color=#ff0000>FEHLER: Datei vermutlich Fehlerhaft</font>'; }
						else if (count($aLines)<=$minLineWarn)
							{ $m['###STATUS_FILELINES###'] = '<font color=#ff0000>FEHLER: Datei vermutlich Fehlerhaft</font>'; }
						else
							{ $m['###STATUS_FILELINES###'] = '<font color=#008000>OK</font>'; }

						$fsb = '';
						$fsbTmpl = $this->templateObj->getSubpart($importTmpl,'###PART2FIELDSET###');

						for (reset($setvals);$key=key($setvals);next($setvals)) {
							if (isset($this->PCA['conf'][$key]) || strcmp($key,'pid')==0  || strcmp($key,'tstamp')==0 || strcmp($key,'crdate')==0 ) {
								$mySet = $setvals[$key]['set'];
								$myLabel = $this->langObj->getLL($this->PCA['conf'][$key]['label']);
								if (strlen($mySet)<1) {
									$mySet = '<font color=#008000><i>--empty--</i></font>';
								} else if (strncmp($mySet,'input',5)==0) {
									$mySet = $import['input'][intval(substr($mySet,5)).'.'];
								} else if (strcmp($mySet,'pid')==0) {
									$mySet = $import['pid'];
								} else if (strcmp($mySet,'feuser')==0) {
									$mySet = $import['feuser'];
								} else if (strcmp($mySet,'time')==0) {
									$mySet = $import['tstamp'];
								} else if (strcmp($mySet,'empty')==0) {
									$mySet = '""';
								} else if (strncmp($mySet,'CONST:',6)==0) {
									$mySet = str_replace('###time###',date('His',$myTime),str_replace('###date###',date('Ymd',$myTime),
										substr($mySet,6)));
								} else {
									$mySet = $mySet;
								}
							} else {
								$mySet = $this->constObj->getWrap('hot','ERROR: "'.$key.'" undefined');
								$myLabel = '??';
								$errors = TRUE;
							}
							//$content .= '<tr><td align=right>'.$key.' =</td><td>'.$mySet.'</td></tr>';
							$fsb .= str_replace('###TEXT_CONSTFIELD###',$key,
								str_replace('###TEXT_CONSTLABEL###',$myLabel,
								str_replace('###TEXT_CONSTVALUE###',$mySet,
								$fsbTmpl)));

						}
						$sp = $this->cObj->substituteSubpart($sp,'###PART2FIELDSET###',$fsb);

						//<!-- ###PART2FIELDSET### -->
						$listTmpl = $this->templateObj->getSubpart($importTmpl,'###PART2LIST###');
						$listLine = $this->templateObj->getSubpart($importTmpl,'###PART2LISTLINE###');
						$headLine = $this->templateObj->getSubpart($importTmpl,'###PART2HEADERLINE###');

						// Show Headers
						$ct = $this->cObj->substituteMarkerArray($headLine, $mHeaders);

						$dupCheck = Array();
						$dupCheck2 = Array();
						$dupFields = ($settings['replace.']['byFields']) ?
							t3lib_div::trimExplode(',',$settings['replace.']['byFields']) : '';

						if (!is_array($dupFields)) {
							$sp = $this->cObj->substituteSubpart($sp,'###PART2REPLACEBLOCK###','');
						}

						$maxC = count ($aLines);
						for ($i=0;$i<$maxC;$i++) {
							$tPar = explode($separator, $aLines[$i]);

							$myData = Array();
							$mData = Array('###id###'=>($i+1).'.');
							for (reset($fields);$key=key($fields);next($fields)) {
								$xKey = str_replace('.','',$key);
								if (count($fields[$key]['id'])>0 && intval($fields[$key]['id'][0])>0) {
									$myVal = $this->newsGetImportFields ($fields,$key,$xKey,$tPar,$myData,$mData);
								} else {
									$myData[str_replace('.','',$key)] = $fields[$key];
								}
							}

							if (is_array($dupFields)) {
								$tmp = Array();
								$tmp2 = Array();
								for ($k=0;$k<count($dupFields);$k++) {
									$d = $myData[$dupFields[$k]];
									if (is_array($d)) {
										$tmp[] = $dupFields[$k];
									} else {
										$tmp[] = $dupFields[$k].'='.$d;
										$tmp2[] = QT.$d.QT;
									}
								}
								$tmp2Implode = implode ('',$tmp2);
								if (strlen($tmp2Implode)>2) {
									$dupCheck[] = implode (' / ',$tmp);
									$dupCheck2[] = $tmp2Implode;
								}
							}

							$ct .= $this->cObj->substituteMarkerArray($listLine, $mData);

							if ($maxC>19 && $i==6) {
								$i = $maxC - 8;
								$ct .= $this->cObj->substituteMarkerArray($headLine, $mHeaders);
							}
						}

						if (count($dupCheck)>0) {
							$dups = Array();
							for ($k=0;$k<count($dupCheck);$k++) {
								$dups[$dupCheck[$k]]++;
							}

							while ( list($key, $val) = each($dups) ) {
								if ($val<2) {
									unset($dups[$key]);
								}
							}

							//- more checks
							$where = Array();
							$dup2implode = implode(',',$dupCheck2);
							$where[] = $dup2implode ? 'concat('.implode(',',$dupFields).') IN ('.$dup2implode.')' : '1=2';
							$where[] = 'deleted=0';
							if ($settings['replace.']['excludeExpired']) {
								$where[] = '(endtime=0 OR endtime>'.time().')';
							}
							if ($settings['replace.']['restrict']) {
								$tmp = t3lib_div::trimExplode(',',$settings['replace.']['restrict']);
								for ($k=0;$k<count($tmp);$k++) {
									if (strcmp($tmp[$k],'pid')==0) {
										$where[] = 'pid='.intval($import['pid']);
									} else if (strcmp($tmp[$k],'crfeuser_id')==0) {
										$where[] = 'crfeuser_id='.intval($import['feuser']);
									}
								}
							}

							$select = 'count(*) as cnt, uid, concat('.implode(',',$dupFields).') as dupcheck';
							$where = implode (' AND ',$where);
							$group = 'concat('.implode(',',$dupFields).')';
							$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$workOnTable,$where,$group,'','');
							$dupErrors = Array();
							$replaceCount = 0;
							$m['###LIST_DBERROR###'] = '';
							if ($res) {
								while ($s = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
									if ($s['cnt']==1) {
										$replaceCount++;
									} else {
										$dupErrors[] = $s;
										$errors = TRUE;
										$m['###LIST_DBERROR###'] .= implode(',',$dupFields).' = '.$s['dupcheck'].' '.
											$this->constObj->getWrap('hot',sprintf($this->constText['imp_descr_dberrorcount_cnt'],$s['cnt'])).
											' ('.$s['uid'].')<br />';
									}
								}
							}
							//t3lib_div::debug(Array('$replaceCount'=>$replaceCount, 'dupErrors'=>$dupErrors, 'File:Line'=>__FILE__.':'.__LINE__));
							$m['###COUNT_DBERROR###'] = count($dupErrors)<1 ? 0 :
								$this->constObj->getWrap('hot',sprintf($this->constText['imp_descr_dberrorcount_text'],count($dupErrors)));
						}
						$m['###COUNT_INSERT###'] = $m['###TEXT_FILELINES###'] - $replaceCount - count($dupErrors);
						$m['###COUNT_REPLACE###'] = $replaceCount;

						$o2File = fopen ('typo3temp/'.$importName.'_pre.txt','wb');
						if($o2File) {
							for ($i=0;$i<$maxC;$i++) {
								fwrite ($o2File,$aLines[$i].CRLF);
							}
							fclose ($o2File);
						}
						$o2File = fopen ('typo3temp/'.$importName.'_repl.txt','wb');
						if($o2File) {
							for ($i=0;$i<$maxC;$i++) {
								fwrite ($o2File,$aLines[$i].CRLF);
							}
							fclose ($o2File);
						}

						$ct .= $this->cObj->substituteMarkerArray($headLine, $mHeaders);
					} else {
						$m['###TEXT_FILELINES###'] = '-none-';
						$m['###STATUS_FILELINES###'] = 'ERROR !!';
						$errors = TRUE;
					}

					$sepError = false;
					if (count($this->felib->impErrors) && count($aLines)>1) {
						$feb = '';
						$febTmpl = sprintf($this->templateObj->getSubpart($importTmpl,'###PART2_ERROR_INCOMPLETEMESSAGE###'));
						//t3lib_div::debug(Array('$incompLines'=>$incompLines, 'File:Line'=>__FILE__.':'.__LINE__));
						for (reset($this->felib->impErrors);$iKey=key($this->felib->impErrors);next($this->felib->impErrors)) {
							$feb .= str_replace('###TEXT###',$this->felib->impErrors[$iKey],
								str_replace('###NUM###',$iKey,
								$febTmpl));
						}
						if (count($aLines)<2) {
							$errors = 1;
							$m['###INCOMPLETEERROR_HEADER###'] =
								sprintf($this->constObj->getWrap('hot',$this->constText['imp_error_incomplete']),
								count($this->felib->impErrors));
						} else if (!$concat) {
							$m['###INCOMPLETEERROR_HEADER###'] =
								sprintf($this->constObj->getWrap('hot',$this->constText['imp_error_incomplete']),
								count($this->felib->impErrors));
						} else {
							$m['###INCOMPLETEERROR_HEADER###'] =
								sprintf($this->constObj->getWrap('warn',$this->constText['imp_warn_incomplete']),
								count($this->felib->impErrors));
						}
						$sp = $this->cObj->substituteSubpart($sp,'###PART2_ERROR_INCOMPLETEMESSAGE###',$feb);
					} else if (count($this->felib->impErrors) && count($aLines)==1) {
						$errors = 1;
						$sepError = true;
						$m['###STATUS_FILELINES###'] = $this->constObj->getWrap('hot',$this->constText['imp_error_separator']);
						$sp = $this->cObj->substituteSubpart($sp,'###ERROR_BLOCK_INCOMPLETE###','');
					} else {
						$sp = $this->cObj->substituteSubpart($sp,'###ERROR_BLOCK_INCOMPLETE###','');
					}

					if ($sepError) {
						$m['###SHOW_MATCH_LIST###'] =
							$this->constObj->getWrap('warn',$this->constText['imp_warn_sep_info']).'<br />'.
							substr($aLines[0],0,200).'<br />';
					} else {
						$listTmpl = $this->cObj->substituteSubpart($listTmpl,'###PART2LISTLINE###',$ct);
						$listTmpl = $this->cObj->substituteSubpart($listTmpl,'###PART2HEADERLINE###','');
						$m['###SHOW_MATCH_LIST###'] = $this->cObj->substituteMarkerArray($listTmpl, $m);
					}

					if (count($dups)>0) {
						$errors = 1;
						$m['###DUPERROR_HEADER###'] = $this->constObj->getWrap('hot',$this->constText['imp_error_duplicate']);
						$feb = '';
						$febTmpl = $this->templateObj->getSubpart($importTmpl,'###PART2_ERROR_DUPMESSAGE###');
						for (reset($dups);$iKey=key($dups);next($dups)) {
							$feb .= str_replace('###TEXT###',$iKey,
								str_replace('###NUM###',$dups[$iKey],
								$febTmpl));

						}
						$sp = $this->cObj->substituteSubpart($sp,'###PART2_ERROR_DUPMESSAGE###',$feb);
					} else {
						$sp = $this->cObj->substituteSubpart($sp,'###ERROR_BLOCK_DUP###','');
					}

					if ($errors) {
						$m['###SUBMIT###'] = '<input type="hidden" name="impSt" value="1">'.
							'<input type="submit" value="'.$this->constText['imp_prev'].'">';
					} else {
						$m['###SUBMIT###'] = '<input type="hidden" name="impSt" value="3">'.
							'<input type="submit" value="'.$this->constText['imp_next'].'">';
					}

					$content .= $this->cObj->substituteMarkerArray($sp,$m);
				} else if ($importState==3) { // ##################################################################################
						$content .= '<form action="'.$this->pi_getPageLink($GLOBALS["TSFE"]->id).
							'" enctype="multipart/form-data" method="post">';
						$content .= '<input type="hidden" name="id" value="'.$GLOBALS["TSFE"]->id.'">';
						$content .= str_replace('###STATE###',$activeState,str_replace('###MAXSTATE###',$totalStates,
							$this->cObj->stdWrap($settings['part3'],$settings['part3.'])));
						$content .= '<input type="hidden" name="filetype" value="'.$fileType.'">';
						$import = unserialize(urldecode(t3lib_div::GPvar('moreimport')));
						$this->importPid = $import['pid'];
						$content .= '<input type="hidden" name="moreimport" value="'.urlencode(serialize($import)).'">';
						$separator = "\t";
						if (strlen($import['separator'])>0) {
							if (strcmp($import['separator'],'tab')==0) {
								$separator = "\t";
							} else if (strcmp($import['separator'],'pipe')==0) {
								$separator = "|";
							} else {
								$separator = $import['separator'];
							}
						}

						$aLines = $this->felib->getImportFile('typo3temp/'.$importName.'_pre.txt',$minImportFields,$separator);
						$maxC = count($aLines);

						if (is_array($cfImport['global.'])) {
							//t3lib_div::debug(Array('$cfImport[global.]'=>$cfImport['global.'], 'File:Line'=>__FILE__.':'.__LINE__));
							if (count($cfImport['global.']['replaces.'])) {
								$eregList = Array();
								$eregCount = Array();

								$cfIgr = $cfImport['global.']['replaces.'];
								if (is_array($cfIgr['ereg.'])) {
									//t3lib_div::debug(Array('$cfIgr'=>$cfIgr, 'File:Line'=>__FILE__.':'.__LINE__));

									$maxC = count ($aLines);
									for ($i=0;$i<$maxC;$i++) {
										$aLines[$i] = newsReplace($cfIgr,$aLines[$i],$eregList,$eregCount);
									}
								}
								//t3lib_div::debug(Array('$eregList'=>$eregList, '$eregCount'=>$eregCount, 'File:Line'=>__FILE__.':'.__LINE__));

								if (count($eregCount)>0) {
									ksort($eregCount);
									$eregCont = '<table border=1; cellspacing=0 cellpadding=0><tr><td colspan=2><b>Ereg Replace-Count</td></tr>';
									for (reset($eregCount);$eKey=key($eregCount);next($eregCount)) {
										$eregCont .= '<tr><td>'.htmlspecialchars($eKey).'</td><td>'.$eregCount[$eKey].'</td></tr>';
									}
									$eregCont .= '</table><br />';

									$logfile = fopen ('typo3temp/'.$importName.'_eregcount_glob.htm','w');
									if ($logfile) {
										fwrite ($logfile,$eregCont);
										fclose ($logfile);
									}
									$content .= '<br />Show <a  target="_neweregcountlog" href="typo3temp/'.
											$importName.'_eregcount_glob.htm">Logfile for Ereg-Replace Counter</a> in new Window.<br />'.$eregCont;
								}

								if (count($eregList)>0) {
									$eregCont = '<table border=1; cellspacing=0 cellpadding=0><tr><td colspan=2><b>Ereg Replace-List</td></tr>';
									for (reset($eregList);$eKey=key($eregList);next($eregList)) {
										$eregCont .= '<tr><td>'.htmlspecialchars($eKey).'</td><td>'.htmlspecialchars($eregList[$eKey]).'</td></tr>';
									}
									$eregCont .= '</table><br />';

									$logfile = fopen ('typo3temp/'.$importName.'_ereg_glob.htm','w');
									if ($logfile) {
										fwrite ($logfile,$eregCont);
										fclose ($logfile);
									}
									$content .= '<br />Show <a  target="_newereglog" href="typo3temp/'.$importName.'_ereg_glob.htm">Logfile for Ereg-Replaces</a> in new Window.<br />'.$eregCont;
								}


							} else {
								$content .= '<b>No global Replaces to perform.</b><br />';
							}
							$content .= '<br />';
							if (count($cfImport['global.']['checks.'])) {
								$checked = Array();
								for (reset($cfImport['global.']['checks.']);$cKey=key($cfImport['global.']['checks.']);next($cfImport['global.']['checks.'])) {
									if (intval($cfImport['global.']['checks.'][$cKey])) {
										$checked[$cKey]['title'] = 'Checking for "href"';
										$checked[$cKey]['count'] = 0;
										$checked[$cKey]['list'] = Array();

										$maxC = count ($aLines);
										for ($i=0;$i<$maxC;$i++) {
											$tPar = explode($separator, $aLines[$i]);
											$myHref =  newsImportHasHref($aLines[$i]);
											if ($myHref) {
												$checked[$cKey]['count']++;
												$checked[$cKey]['list'][] = Array(($i+1).'.',$tPar[0],$tPar[1],$myHref);
											}
										}

									}

									$content .= '<table border="1" cellspacing="0" cellpadding="2">';
									$content .= '<tr><td colspan="2"><b>Globals Checks</b></td></tr>';
									$content .= '<tr><td><b>Count</b></td><td><b>Type of Check</b></td></tr>';
									for (reset($checked);$cKey=key($checked);next($checked)) {
										$content .= '<tr><td>'.$checked[$cKey]['count'].'</td><td>'.$checked[$cKey]['title'].'</td></tr>';
									}
									$content .= '</table><br />';


									$chkCont .= '<table border="1" cellspacing="0" cellpadding="2">';
									$chkCont .= '<tr><td colspan="3"><b>Globals Checks - Details</b></td></tr>';
									for (reset($checked);$cKey=key($checked);next($checked)) {
										$chkCont .= '<tr><td colspan="3">&nbsp;</td></tr>';
										$chkCont .= '<tr><td colspan="3"><b>'.$checked[$cKey]['title'].' (Count='.$checked[$cKey]['count'].')</b></td></tr>';
										for ($ll=0;$ll<count($checked[$cKey]['list']);$ll++) {
											$chkCont .= '<tr><td>'.$checked[$cKey]['list'][$ll][0].'</td>';
											$chkCont .= '<td>'.$this->divObj->cropHtmlText($checked[$cKey]['list'][$ll][1],10).' - '.
													$this->divObj->cropHtmlText($checked[$cKey]['list'][$ll][2],22).'</td>';
											$chkCont .= '<td>'.$this->divObj->cropHtmlText($checked[$cKey]['list'][$ll][3],68).'</td></tr>';
										}
									}
									$chkCont .= '</table>';

									$logfile = fopen ('typo3temp/'.$importName.'_check_glob.htm','w');
									if ($logfile) {
										fwrite ($logfile,$chkCont);
										fclose ($logfile);
									}
									$content .= '<br />Show <a  target="_newchecklog" href="typo3temp/'.$importName.
										'_check_glob.htm">Logfile for Checks</a> in new Window.<br />'.$chkCont;
								}
							} else {
								$content .= '<b>No global Checks to perform.</b><br />';
							}
						} else {
							$content .= '<b>No global Checks or Replaces</b><br />';
						}

						$o2File = fopen ('typo3temp/'.$importName.'_repl.txt','wb');
						if($o2File) {
							for ($i=0;$i<$maxC;$i++) {
								fwrite ($o2File,$aLines[$i].CRLF);
							}
							fclose ($o2File);
						}

						$content .= '<br /><input type="hidden" name="impSt" value="4">';
						$content .= '<input type="submit" value="Weiter">';
						$content .= '</form>';
				} else if ($importState==4) { // ##################################################################################
					$fatalErrors =  FALSE;
					$detailsFormat = '';
					$detailsLog = '';
					$detailsInfo = '';
					$pnConf = $cfImport['notify.'];
					if (is_array($pnConf)) {
						$detailsFormat = $pnConf['detailsLine'];
						$detailsInfo = $pnConf['detailsInfo'];
					}

					$sp = $this->templateObj->getSubpart($importTmpl,'###PART4SUMMARY###');
					$ep = $this->templateObj->getSubpart($importTmpl,'###PART4ERRORS###');
					$fepBlock = $this->templateObj->getSubpart($importTmpl,'###PART4LISTLINE###');
					$fepHeader = $this->templateObj->getSubpart($importTmpl,'###PART4HEADERLINE###');
					$fep = '';
					$import = unserialize(urldecode(t3lib_div::GPvar('moreimport')));
					$this->importPid = $import['pid'];
					$m['###PARTNUMBER###'] = $this->cObj->stdWrap($settings['part4'],$settings['part4.']);
					$m['###ACTION###'] = $this->pi_getPageLink($GLOBALS["TSFE"]->id);
					$m['###HIDDENDATA###'] = '<input type="hidden" name="id" value="'.$GLOBALS["TSFE"]->id.'">'.
							'<input type="hidden" name="filetype" value="'.$fileType.'">'.
							'<input type="hidden" name="moreimport" value="'.urlencode(serialize($import)).'">'.
							'';
					$m['###STATE###'] = $activeState;
					$m['###MAXSTATE###'] = $totalStates;
					$m['###COUNT_DELETE###'] = 0;

					$separator = "\t";
					if (strlen($import['separator'])>0) {
						if (strcmp($import['separator'],'tab')==0) {
							$separator = "\t";
						} else if (strcmp($import['separator'],'pipe')==0) {
							$separator = "|";
						} else {
							$separator = $import['separator'];
						}
					}

					$of = fopen ('typo3temp/'.$importName.'.sql','wb');
					if ($of) {
						fwrite ($of,'# DELETE FROM '.$myFile.' WHERE comment LIKE'.QT.'import %'.QT.";\r\n");
						$errCount = 0;
						$ewCount = 0;
						$cntArchived = 0;
						$cntNotArchived = 0;
						$cntSaved = 0;
						$cntInsert = 0;
						$cntATag = 0;
						$nfSC = Array();
						$errors = Array('e'=>Array(), 'w'=>Array());

						if ($settings['deleteExpired']) {
							$select = 'uid';
							$where = 'deleted=0 AND endtime>0 AND endtime<'.time();
							$globalWhere = '';
							if ($settings['deleteExpired.']['restrict']) {
								$tmp = t3lib_div::trimExplode(',',$settings['deleteExpired.']['restrict']);
								for ($k=0;$k<count($tmp);$k++) {
									if (strcmp($tmp[$k],'pid')==0) {
										$globalWhere .= ' AND pid='.intval($import['pid']);
									} else if (strcmp($tmp[$k],'crfeuser_id')==0) {
										$globalWhere .= ' AND crfeuser_id='.intval($import['feuser']);
									}
								}
							}
							$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$workOnTable,$where.$globalWhere,'','','');
							$m['###COUNT_DELETE###'] = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
							$tmp = 'UPDATE '.$workOnTable.' SET deleted=1 WHERE '.$where;
							//t3lib_div::debug(Array('$tmp'=>$tmp, 'File:Line'=>__FILE__.':'.__LINE__));
							fwrite ($of,'# '.$tmp.";\r\n");
						}

						// $aLines = file ('typo3temp/'.$importName.'.txt');
						$aLines = $this->felib->getImportFile('typo3temp/'.$importName.'_repl.txt',$minImportFields,$separator);

						//t3lib_div::debug(Array('count($aLines)'=>count($aLines), 'File:Line'=>__FILE__.':'.__LINE__));
						// CHECK for replacing data
						$dupFields = ($settings['replace.']['byFields']) ?
							t3lib_div::trimExplode(',',$settings['replace.']['byFields']) : '';

						$myReplaces = Array();
						$toReplace = Array();
						$noReplace = Array();
						if (is_array($dupFields)) {
							for ($i=0;$i<count($aLines);$i++) {
								$tPar = explode($separator, $aLines[$i]);

								$myData = Array();
								for (reset($fields);$key=key($fields);next($fields)) {
									$xKey = str_replace('.','',$key);
									if (count($fields[$key]['id'])>0 && intval($fields[$key]['id'][0])>0) {
										$myVal = $this->newsGetImportFields ($fields,$key,$xKey,$tPar,$myData,$mData);
									} else {
										$myData[str_replace('.','',$key)] = $fields[$key];
									}
								}
								$tmp = Array();
								for ($k=0;$k<count($dupFields);$k++) {
									$d = $myData[$dupFields[$k]];
									if (!is_array($d)) {
										$tmp[] = $d;
									}
								}
								$tmp = QT.implode ('',$tmp).QT;
								if (strlen($tmp)>2) {
									$myReplaces[] = $tmp;
								}
							}

							$where = Array();
							$myReplacesImplode = implode(',',$myReplaces);
							$where[] = $myReplacesImplode ? 'concat('.implode(',',$dupFields).') IN ('.$myReplacesImplode.')' : '1=2';
							$where[] = 'deleted=0';
							if ($settings['replace.']['excludeExpired']) {
								$where[] = '(endtime=0 OR endtime>'.time().')';
							}
							if ($settings['replace.']['restrict']) {
								$tmp = t3lib_div::trimExplode(',',$settings['replace.']['restrict']);
								for ($k=0;$k<count($tmp);$k++) {
									if (strcmp($tmp[$k],'pid')==0) {
										$where[] = 'pid='.intval($import['pid']);
									} else if (strcmp($tmp[$k],'crfeuser_id')==0) {
										$where[] = 'crfeuser_id='.intval($import['feuser']);
									}
								}
							}

							$select = 'count(*) as cnt, uid, concat('.implode(',',$dupFields).') as dupcheck';
							$where = implode (' AND ',$where);
							$group = 'concat('.implode(',',$dupFields).')';
							$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$workOnTable,$where,$group,'','');
							$replaceCount = 0;
							$dupLines = 0;
							$m['###LIST_DBERROR###'] = '';
							if ($res) {
								while ($s = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
									if ($s['cnt']==1) {
										//$replaceCount++;
										$toReplace[$s['dupcheck']] = $s['dupcheck'];
									} else {
										$dupLines++;
										$noReplace[$s['dupcheck']] = $s['dupcheck'];
									}
								}
							}
							$m['###COUNT_REPLACE###'] = $replaceCount;
							$m['###COUNT_INSERT###'] = count($myReplaces)-$replaceCount-$dupLines;
						}

						// read foreign tables
						$foreign = Array();
						$altForeign = Array();
						$unique = Array();
						for (reset($fields);$key=key($fields);next($fields)) {
							$xKey = str_replace('.','',$key);
							if (isset($fields[$key]['check']) && stristr($fields[$key]['check'],'unique')) {
								$unique[$xKey] = Array();
								$query = 'pid=0 OR pid='.intval($import['pid']);
								$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($xKey,$this->PCA['table'],$query);
								if ($err=$GLOBALS['TYPO3_DB']->sql_error()) {
									t3lib_div::debug(Array('Select='=>$xKey, 'Table='=>$this->PCA['table'], "Query="=>$query, "Res="=>$res, "Error="=>$err ));
								} else if (!t3lib_div::inArray($dupFields,substr($key,0,-1))) {
									//t3lib_div::debug(Array("Query="=>$query, "Res="=>$res, "Count="=>$GLOBALS['TYPO3_DB']->sql_num_rows($res) ));
									while ($row=$GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
										$unique[$xKey][strtolower($row[$xKey])]++;
									}
								}
								//for ($i=0;$i<250;$i++) {
								for ($i=0;$i<count($aLines);$i++) {
									$tPar = explode($separator, $aLines[$i]);
									$unique[$xKey][strtolower($tPar[$fields[$key]['id'][0]-1])]++;
								}
							}
							if (isset($fields[$key]['eval']) && strcmp($fields[$key]['eval'],'foreign')==0) {
								$this->itemsObj->prepareItems($this->PCA['table'],$xKey,0,$row);
								$tmp = $this->itemsObj->getItemList($this->PCA['table'],$xKey,0);
								$foreign[$xKey] = Array();
								for (reset($tmp);$tKey=key($tmp);next($tmp)) {
									$foreign[$xKey][strtolower($tmp[$tKey])] = intval($tKey);
								}
							}
							if (isset($fields[$key]['altforeign'])) {
								$myTmp = $this->PCA['conf'][$xKey]['foreign']['label'];
								$this->PCA['conf'][$xKey]['foreign']['label'] = $fields[$key]['altforeign'];
								$this->itemsObj->prepareItems($this->PCA['table'],$xKey,0,$row);
								$tmp = $this->itemsObj->getItemList($this->PCA['table'],$xKey,0);
								$altForeign[$xKey] = Array();
								for (reset($tmp);$tKey=key($tmp);next($tmp)) {
									$altForeign[$xKey][strtolower($tmp[$tKey])] = intval($tKey);
								}
								$this->PCA['conf'][$xKey]['foreign']['label'] = $myTmp;
							}
						}
						//t3lib_div::debug(Array('$altForeign'=>$altForeign, 'File:Line'=>__FILE__.':'.__LINE__));

						// Show Headers
						$fep = $this->cObj->substituteMarkerArray($fepHeader, $mHeaders);
						$fepLCnt = 0;

						$lastId = 0;
						$eregList = Array();
						$eregCount = Array();

						if (count($this->felib->impErrors)) {
							$tmpStr = $this->constText['imp_warn_incomplete'];
							$errors['w'][$tmpStr] = count($this->felib->impErrors);
						}

						//for ($i=0;$i<250;$i++) {
						$maxC = count($aLines);
						for ($i=0;$i<$maxC;$i++) {
							$tmpMode = '???';
							$isError = FALSE;
							$isWarning = FALSE;
							$errText = Array ('e'=>'', 'w'=>'');

							$tPar = explode($separator, $aLines[$i]);
							$myData = Array();
							$mData = Array();
							$orgData = Array();
							// now create data for INSERT statement
							for (reset($fields);$key=key($fields);next($fields)) {
								$xKey = str_replace('.','',$key);
								$myVal = '';
								if (count($fields[$key]['id'])>0 && intval($fields[$key]['id'][0])>0) {
									$myVal = $this->newsGetImportFields ($fields,$key,$xKey,$tPar,$myData,$mData);
								} else {
									if (isset($fields[$key]['set'])) {

										$mySet = $fields[$key]['set'];
										if (strlen($mySet)<1) {
											$mySet = '';
										} else if (strncmp($mySet,'input',5)==0) {
											$myData[$xKey] = $import['input'][intval(substr($mySet,5)).'.'];
										} else if (strcmp($mySet,'time')==0) {
											$myData[$xKey] = $import['tstamp'];
										} else if (strcmp($mySet,'pid')==0) {
											$myData[$xKey] = $import['pid'];
										} else if (strcmp($mySet,'feuser')==0) {
											$myData[$xKey] = $import['feuser'];
										} else if (strcmp($mySet,'empty')==0) {
											$myData[$xKey] = '';
										} else if (strncmp($mySet,'CONST:',6)==0) {
											$myData[$xKey] = str_replace('###time###',date('His',$myTime),str_replace('###date###',date('Ymd',$myTime),
												substr($mySet,6)));
										} else {
											$myData[$xKey] = $mySet;
										}
									} else {
										$myData[$xKey] = '';
									}
								}

								if (isset($fields[$key]['cutLen'])) {
									$ml = intval($fields[$key]['cutLen']);
									if ($ml && strlen($myVal)>$ml) {
											$isWarning = TRUE;
											$myVal = substr($myVal,0,$ml);
											$myData[$xKey] = $myVal;
											$tmpStr = sprintf($this->constText['imp_warn_cutoffstr'],$xKey,$ml);
											$errText['w'] .= $tmpStr.'<br />';
											$tmpStr = sprintf($this->constText['imp_warn_cutoff'],$xKey);
											$errors['w'][$tmpStr]++;
									}
								}


								$mData['###org_'.$xKey.'###'] = $myVal;
								$orgData[$xKey] = $myVal;
								$myVal = str_replace("\x0b","\n",$myVal);
								if (is_array($fields[$key]['replace.'])) {
									$myVal = newsReplace($fields[$key]['replace.'],$myVal,$eregList,$eregCount);
									$myData[$xKey] = $myVal;
								}

								if (isset($fields[$key]['eval'])) {
									$myVal = str_replace("\x0b","\n",$myData[$xKey]);
									$myOldVal = str_replace("\x0b","\n",$myVal);
									switch($fields[$key]['eval']) {
										case 'foreign':
											if (strlen($myVal)<1) {
												$myVal = 0;
											} else {
												$myVal = $foreign[$xKey][strtolower($myOldVal)];
												if (intval($myVal)==0 && isset($fields[$key]['altforeign'])) {
													$myVal = $altForeign[$xKey][strtolower($myOldVal)];
												}
												if (intval($myVal)==0) {
													$tmpStr = sprintf($this->constText['imp_warn_unknown'],$xKey,$myOldVal);
													if (isset($fields[$key]['iferror'])) {
														$myVal = $fields[$key]['iferror'];
														$isWarning = TRUE;
														$errText['w'] .= $tmpStr.'<br />';
														$errors['w'][$tmpStr]++;
													} else {
														$isError = TRUE;
														$errText['e'] .= $tmpStr.'<br />';
														$errors['e'][$tmpStr]++;
													}
												}
											}
										break;
										case 'date':
											if (strlen($myVal)<1) {
												$myVal = 0;
											} else {
												$myVal = $this->felib->dateStringToTime($myVal);
												if ($myVal<1 || $this->felib->lastCheckError) {
													$tmpStr = sprintf($this->constText['imp_warn_novaliddate'],$xKey);
													if (isset($fields[$key]['iferror'])) {
														$myVal = $fields[$key]['iferror'];
														$isWarning = TRUE;
														$errText['w'] .= $tmpStr.' ("'.$myOldVal.'")<br />';
														$errors['w'][$tmpStr]++;
													} else {
														$isError = TRUE;
														$errText['e'] .= $tmpStr.' ("'.$myOldVal.'")<br />';
														$errors['e'][$tmpStr]++;
													}
												}
											}
										break;
										case 'time':
											if (strlen($myVal)<1) {
												$myVal = 0;
											} else {
												$myVal = $this->felib->timeStringToTime($myVal);
												if ($myVal<1 || $this->felib->lastCheckError) {
													$tmpStr = sprintf($this->constText['imp_warn_novalidtime'],$xKey);
													if (isset($fields[$key]['iferror'])) {
														$myVal = $fields[$key]['iferror'];
														$isWarning = TRUE;
														$errText['w'] .= $tmpStr.' ("'.$myOldVal.'")<br />';
														$errors['w'][$tmpStr]++;
													} else {
														$isError = TRUE;
														$errText['e'] .= $tmpStr.' ("'.$myOldVal.'")<br />';
														$errors['e'][$tmpStr]++;
													}
												}
											}
										break;
										default:
											$myVal .= '<font color=#c0c000>'.$fields[$key]['eval'].'</font>';
									}
									$myData[$xKey] = $myVal;
									$mData['###'.$xKey.'###'] = $myVal;
								}
								$tmp = count_chars($myVal,1);
								unset($tmp[10]);
								unset($tmp[13]);
								if (count($tmp)>0 && key($tmp)<32) {
									$isWarning = TRUE;
									$tmpStr = sprintf($this->constText['imp_warn_containsctrl'],$xKey);
									$errText['w'] .= $tmpStr.'<br />';
									$errors['w'][$tmpStr]++;
								}

								if (isset($fields[$key]['check'])) {
									$myCheck = explode(',',$fields[$key]['check']);
									for ($k=0;$k<count($myCheck);$k++) {
										//t3lib_div::debug(Array('check'=>$fields[$key]['check'], '$myCheck['.$k.']'=>$myCheck[$k], 'File:Line'=>__FILE__.':'.__LINE__));
										if (strcmp($myCheck[$k],'checkhref')==0) {
											$myHref =  newsImportHasHref($myVal);
											if ($myHref) {
												$isWarning = TRUE;
												$tmpStr = sprintf($this->constText['imp_warn_containsx'],$xKey);
												$errText['w'] .= $tmpStr.' '.htmlspecialchars($myHref).'<br />';
												$tmpStr = $tmpStr.' href';
												$errors['w'][$tmpStr]++;
											}
										}
										if (strcmp($myCheck[$k],'notempty')==0 && strlen($myVal)<1) {
											$isError = TRUE;
											$tmpStr = sprintf($this->constText['imp_error_isempty'],$xKey);
											$errText['e'] .= $tmpStr.'<br />';
											$errors['e'][$tmpStr]++;
										}
										if (strcmp($myCheck[$k],'numeric')==0 && $myVal && 'x'.intval($myVal)!='x'.$myVal) {
											$isError = TRUE;
											$tmpStr = sprintf($this->constText['imp_error_isnotnumeric'],$xKey);
											$errText['e'] .= $tmpStr.'<br />';
											$errors['e'][$tmpStr]++;
										}
										if (strcmp($myCheck[$k],'notnull')==0 && intval($myVal)==0) {
											$isError = TRUE;
											$tmpStr = sprintf($this->constText['imp_error_isnull'],$xKey);
											$errText['e'] .= $tmpStr.'<br />';
											$errors['e'][$tmpStr]++;
										}
										if (strcmp($myCheck[$k],'unique')==0 && $unique[$xKey][strtolower($myVal)]>1) {
											$isError = TRUE;
											$tmpStr = sprintf($this->constText['imp_error_isnotunique'],$xKey);
											$errText['e'] .= $tmpStr.'("'.$myVal.'")<br />';
											$errors['e'][$tmpStr]++;
										}
										if (strcmp($myCheck[$k],'email')==0) {
											if (strlen($myVal)<1) {
												$isError = TRUE;
												$tmpStr = sprintf($this->constText['imp_error_isempty'],$xKey);
												$errText['e'] .= $tmpStr.'<br />';
												$errors['e'][$tmpStr]++;
											} else if (substr_count($myVal,"@")!=1) {
												$isError = TRUE;
												$tmpStr = sprintf($this->constText['imp_error_isinvalid'],$xKey);
												$errText['e'] .= $tmpStr.' (@)<br />';
												$errors['e'][$tmpStr]++;
											}
										}
										//

									}
								}
							}
							//t3lib_div::debug(Array('$myData'=>$myData, 'File:Line'=>__FILE__.':'.__LINE__));
							$this->importPreProcess($myData,$tPar,$cfImport);

							// check if endtime is in the past
							if (intval($myData['endtime'])>0 && intval($myData['endtime'])<time()) {
								$isError = TRUE;
								$tmpStr = sprintf($this->constText['imp_error_expired']);
								$errText['e'] .= $tmpStr.' '.date('d.m.Y',intval($myData['endtime'])).'<br />';
								$errors['e'][$tmpStr]++;
							}

							if (!$isError) {
								$uidMode = FALSE;
								$s = Array();
								$q = Array();
								$u = Array();
								for (reset($myData);$key=key($myData);next($myData)) {
									$s[] = $key;
									$myVal = str_replace("\x0b",'\n',$myData[$key]);
									$q[] = QT.str_replace(QT,DQT,$myVal).QT;
									$u[$key] = $key.'='.QT.str_replace(QT,DQT,$myVal).QT;
									if (strcmp($key,'uid')==0) {
										$uidMode = TRUE;
									}
								}

								// $toReplace $noReplace;
								$doReplace = FALSE;
								$doNothing = FALSE;
								$check = 'xz/jksehd jkhjkdsfjktz78d';
								if (is_array($dupFields)) {
									$check = '';
									for ($k=0;$k<count($dupFields);$k++) {
										$check .= $myData[$dupFields[$k]];
										unset($u[$dupFields[$k]]);
									}
									if (strcmp($check,$toReplace[$check])==0) {
										$doReplace = TRUE;
									} else if (strcmp($check,$noReplace[$check])==0) {
										$doNothing = TRUE;
										$isError = TRUE;
										$tmpStr = $this->constText['imp_error_dberror'];
										$errText['e'] .= implode('/',$dupFields).' = '.$check.': '.$tmpStr.'<br />';
										$errors['e'][implode('/',$dupFields).' = '.$check.': '.$tmpStr]++;
										$tmpMode = 'DBERROR';
									}
								}
								if ($doNothing) {
									$query = '# NOTHING INTO '.$myFile.' ( '.implode(',',$s).') VALUES ( '.implode(',',$q).');';
								} else if ($doReplace) {
									$replaceCount++;
									$tmpMode = 'REPLACE';
									$query = 'UPDATE '.$myFile.' SET '.implode(',',$u).' '.
										'WHERE CONCAT('.implode('',$dupFields).')='.QT.$check.QT.$globalWhere.';';
								} else if ($uidMode || $cfImport['noUid']) {
									$tmpMode = 'INSERT';
									$cntInsert++;
									$query = 'INSERT INTO '.$myFile.' ( '.implode(',',$s).') VALUES ( '.implode(',',$q).');';
								} else {
									$tmpMode = 'INSERT';
									$cntInsert++;
									$query = 'INSERT INTO '.$myFile.' ( uid,'.implode(',',$s).') VALUES ( NULL,'.implode(',',$q).');';
								}

								if (fwrite($of, $query."\r\n")) {
									if (!$doNothing) {
										$cntSaved++;
									}

									// check mm_data
									for (reset($fields);$key=key($fields);next($fields)) {
										if (is_array($fields[$key]['mm.'])) {
											$fgTable = $fields[$key]['mm.']['table'];
											$fgLocal = $fields[$key]['mm.']['local'];
											if ($fgTable && $fgLocal) {
												$fgLocalId = intval($myData[$fgLocal]);
												$fgForId = explode (',',$myData[str_replace('.','',$key)]);
											    //t3lib_div::debug(Array('$fgForId='=>$fgForId,'$fgLocalId'=>$fgLocalId ));
												for ($i4=0;$i4<count($fgForId);$i4++) if (intval($fgForId[$i4]))  {
													$query = 'INSERT INTO '.$fgTable.' ( uid_local,uid_foreign ) '.
														'VALUES ( '.$fgLocalId.', '.$fgForId[$i4].' );';
													fwrite($of, $query."\r\n");
												}
											}
										}
									}

								} else {
									$isError = TRUE;
									$tmpStr = $this->constText['imp_error_writeerror_sql'];
									$errText['e'] = $tmpStr.'<br />';
									$errors['e'][$tmpStr]++;
								}

							} else {
								$tmpMode = 'ERROR';
							}
							if ($isError || $isWarning) {
								$ewCount++;
								if ($isError) {
									$errCount++;
								}

								$mData['###id###'] = ($i+1);
								$mData['###errorcounter###'] = $isError ? '('.$this->constObj->getWrap('hot',$errCount).')' : '';
								$mData['###errormessages###'] = '';
								if ($errText['e']) {
									$mData['###errormessages###'] .= $this->constObj->getWrap('hot',$errText['e']);
								}
								if ($errText['w']) {
									$mData['###errormessages###'] .= $this->constObj->getWrap('warn',$errText['w']);
								}

								$fep .= $this->cObj->substituteMarkerArray($fepBlock, $mData);
								$fepLCnt++;
								if (fmod($ewCount,10.0)<0.005) {
									$fep .= $this->cObj->substituteMarkerArray($fepHeader, $mHeaders);
									$fepLCnt = 0;
								}
							}
	//						if (intval($tPar[0])>0) { $lastId = substr($tPar[0],0,6); }
	//						if ($errCount>($cntSaved / 5) + 20) { $i = count($aLines)+10; }

							$tmp = sprintf ($detailsInfo,($i+1),$tmpMode);
							$line = $this->cObj->substituteMarkerArray($detailsFormat, $myData, '###|###');
							$detailsLog .= str_replace('###detailsline###',$tmp,$line).CRLF;
						}

						$cntDeleteAll = 0;
						if (strcmp(strtolower($cfImport['settings.']['deleteAll']),'own')==0) {
							$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($cfImport['noUid'] ? '*' : 'uid',$workOnTable,'crfeuser_id='.$import['feuser']);
							$cntDeleteAll = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
						} else if (strcmp(strtolower($cfImport['settings.']['deleteAll']),'all')==0) {
							$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($cfImport['noUid'] ? '*' : 'uid',$workOnTable,'1=1');
							$cntDeleteAll = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
						}
						$m['###COUNT_DELETE_ALL###'] = $cntDeleteAll;


						ksort ($errors['e']);
						$m['###COUNT_TOTAL###'] = count($aLines);
						$m['###COUNT_SAVE###'] = $cntSaved;
						$m['###COUNT_ERROR###'] = $errCount;
						$m['###COUNT_WARNING###'] = count($errors['w']);
						$m['###LIST_WARNINGS###'] = '';
						$m['###LIST_ERRORS###'] = '';
						$m['###MAILLIST_WARNINGS###'] = '';
						$m['###MAILLIST_ERRORS###'] = '';
						if (count($errors['e'])>0) {
							$m['###LIST_ERRORS###'] = '<table border=0 cellspacing=0 cellpadding=0>';
							$m['###MAILLIST_ERRORS###'] = $this->constText['imp_error_mailheader'].CRLF;
							for (reset($errors['e']);$key=key($errors['e']);next($errors['e'])) {
								$m['###LIST_ERRORS###'] .= '<tr><td valign="top" align="right">'.$errors['e'][$key].'&nbsp;</td><td>'.$key.'</td></tr>';
								$m['###MAILLIST_ERRORS###'] .= '- '.$key.': '.$errors['e'][$key].CRLF;
							}
							$m['###LIST_ERRORS###'] .= '</table>';
							$m['###MAILLIST_ERRORS###'] .= CRLF;
						}else {
							$m['###LIST_ERRORS###'] .= '&nbsp;';
						}
						if (count($errors['w'])>0) {
							$m['###LIST_WARNINGS###'] = '<table border=0 cellspacing=0 cellpadding=0>';
							$m['###MAILLIST_WARNINGS###'] = $this->constText['imp_warn_mailheader'].CRLF;
							for (reset($errors['w']);$key=key($errors['w']);next($errors['w'])) {
								 $m['###LIST_WARNINGS###'] .= '<tr><td align="right">'.$errors['w'][$key].'&nbsp;</td><td>'.$key.'</td></tr>';
								 $m['###MAILLIST_WARNINGS###'] .= '- '.$key.': '.$errors['w'][$key].CRLF;
							}
							$m['###LIST_WARNINGS###'] .= '</table>';
							$m['###MAILLIST_WARNINGS###'] .= CRLF;
						} else {
							$m['###LIST_WARNINGS###'] .= '&nbsp;';
						}


						if ($ewCount>0) {
							for (reset($foreign);$key=key($foreign);next($foreign)) {
								$content .= '<table border=1; cellspacing=0 cellpadding=0><tr><td colspan=2><b>Selectlist ('.$key.')</td></tr>';
									for (reset($foreign[$key]);$xKey=key($foreign[$key]);next($foreign[$key])) {
										$content .= '<tr><td>'.$xKey.'</td><td>'.$foreign[$key][$xKey].'</td></tr>';
									}
								$content .= '</table><br />';
							}
							for (reset($altForeign);$key=key($altForeign);next($altForeign)) {
							}
						}

						fclose ($of);


						if (count($eregList)>0) {
							$eregCont = '<table border=1; cellspacing=0 cellpadding=0><tr><td colspan=2><b>Ereg Replace-List</td></tr>';
							for (reset($eregList);$eKey=key($eregList);next($eregList)) {
								$eregCont .= '<tr><td>'.htmlspecialchars($eKey).'</td><td>'.htmlspecialchars($eregList[$eKey]).'</td></tr>';
							}
							$eregCont .= '</table><br />';

							$logfile = fopen ('typo3temp/'.$importName.'_ereg.htm','w');
							if ($logfile) {
								fwrite ($logfile,$eregCont);
								fclose ($logfile);
							}
							$content .= '<br />Show <a  target="_newereglog" href="typo3temp/'.$importName.'_ereg.htm">Logfile for Ereg-Replaces</a> in new Window.<br />'.$eregCont;
						}

						if (count($eregCount)>0) {
							ksort($eregCount);
							$eregCont = '<table border=1; cellspacing=0 cellpadding=0><tr><td colspan=2><b>Ereg Replace-Count</td></tr>';
							for (reset($eregCount);$eKey=key($eregCount);next($eregCount)) {
								$eregCont .= '<tr><td>'.htmlspecialchars($eKey).'</td><td>'.$eregCount[$eKey].'</td></tr>';
							}
							$eregCont .= '</table><br />';

							$logfile = fopen ('typo3temp/'.$importName.'_eregcount.htm','w');
							if ($logfile) {
								fwrite ($logfile,$eregCont);
								fclose ($logfile);
							}
							$content .= '<br />Show <a  target="_neweregcountlog" href="typo3temp/'.
									$importName.'_eregcount.htm">Logfile for Ereg-Replace Counter</a> in new Window.<br />'.$eregCont;
						}


					} else {
						$content .= sprintf($this->constText['imp_error_writeerror_fname'],
							'typo3temp/'.$importName.'.sql').'<br />';
						$fatalErrors = TRUE;
					}

					if ($fepLCnt>5) {
						// Show Headers
						$fep .= $this->cObj->substituteMarkerArray($fepHeader, $mHeaders);
					}


					if ($fatalErrors) {
						$m['###SUBMIT###'] = '<input type="hidden" name="impSt" value="'.
							($settings['skip3'] ? 2 : 3).'">'.
							'<input type="submit" value="'.$this->constText['imp_prev'].'">';
					} else {
						$m['###SUBMIT###'] = '<input type="hidden" name="impSt" value="5">'.
							'<input type="submit" value="'.$this->constText['imp_next'].'">';
					}

					$m['###BUTTON_SHOWLOG###'] = '';
					$m['###COUNT_INSERT###'] = $cntInsert;
					$m['###COUNT_REPLACE###'] = $replaceCount;
					$m['###COUNT_PROCESS###'] = $cntInsert+$replaceCount+$errCount;

					$m['###BLOCK_SUMMARY###'] = $this->cObj->substituteMarkerArray($sp,$m);

					$ep = $this->cObj->substituteSubpart($ep,'###PART4HEADERLINE###','');
					$ep = $this->cObj->substituteSubpart($ep,'###PART4LISTLINE###',$fep);
					$m['###BLOCK_ERRORLIST###'] = $this->cObj->substituteMarkerArray($ep,$m);

					$logfile = fopen ('typo3temp/'.$importName.'.htm','w');
					if ($logfile) {
						fwrite ($logfile,$m['###BLOCK_SUMMARY###'].'<br /><br />'.$m['###BLOCK_ERRORLIST###']);
						fclose ($logfile);
						if ($ewCount>0) {
							$m['###BUTTON_SHOWLOG###'] = sprintf($this->constText['imp_log_errorloglink'],
									'target="_newlog" href="typo3temp/'.$importName.'.htm"');
						}
					}

					$m['###SUBMIT_NOTE###'] = sprintf($this->constText['imp_finally_import'],$cntSaved).'<br />';
					if ($cntDeleteAll) {
						if (strcmp(strtolower($cfImport['settings.']['deleteAll']),'own')==0) {
							$m['###SUBMIT_NOTE###'] .= $this->constObj->getWrap('hot',sprintf($this->constText['imp_finally_deletewarning_own'],
								$cntDeleteAll,$import['feuser']));
						} else if (strcmp(strtolower($cfImport['settings.']['deleteAll']),'all')==0) {
							$m['###SUBMIT_NOTE###'] .= $this->constObj->getWrap('hot',sprintf($this->constText['imp_finally_deletewarning_all'],
								$cntDeleteAll));
						}
					}

					$sp = $this->templateObj->getSubpart($importTmpl,'###PART4###');
					$content .= $this->cObj->substituteMarkerArray($sp,$m);

					// Now prepare email to admin and importer
					$pnConf = $cfImport['notify.'];
					if (is_array($pnConf)) {
						if (strlen($pnConf['mailbody.'])>1) {
							$mailbody = $this->cObj->cObjGetSingle($pnConf['mailbody'],$pnConf['mailbody.']);
						} else if (strlen($pnConf['mailbody'])>1) {
							$mailbody = $pnConf['mailbody'];
						}

						$m['###DETAILED_LOG###'] = $detailsLog;

						$mailbody = $this->cObj->substituteMarkerArray($mailbody, $m);
						$mailbody = $this->cObj->substituteMarkerArray($mailbody, $TSFE->fe_user->user, '###feuser_|###');
						$mailfile = fopen ('typo3temp/'.$importName.'-maillog.txt','w');
						if ($mailfile) {
							fwrite ($mailfile,$mailbody);
							fclose ($mailfile);
						} else {
							$content .= $this->constObj->getWrap('hot',sprintf($this->constText['imp_error_writeerror_fname'],
								'typo3temp/'.$importName.'-maillog.txt').'<br />');
						}
						//t3lib_div::debug(Array('$mailbody'=>$mailbody, 'File:Line'=>__FILE__.':'.__LINE__));
					}
					if ($cfImport['postProcessPrepare']) {
						if (is_callable(Array($this,$cfImport['postProcessPrepare']))) {
							$content .= $this->$cfImport['postProcessPrepare']($cfImport);
						} else {
							$content .= $this->constObj->getWrap('warn','ERROR: Missing postprocessprepare-function $this->'.$cfImport['postProcessPrepare']);
						}
					}
					$content .= '<br />'.$this->importPreProcessSummary();



				} else if ($importState==5) { // ##################################################################################
					$saved = 0;
					$content .= str_replace('###STATE###',$activeState,str_replace('###MAXSTATE###',$totalStates,
						$this->cObj->stdWrap($settings['part5'],$settings['part5.'])));
					$aLines = file ('typo3temp/'.$importName.'.sql');
					$lastId = 0;
					$isError = FALSE;
					$errCount = 0;
					$commentCount = 0;
					$errText = Array();
					$import = unserialize(urldecode(t3lib_div::GPvar('moreimport')));

					if (strcmp(strtolower($cfImport['settings.']['deleteAll']),'own')==0) {
						$res = $GLOBALS['TYPO3_DB']->exec_DELETEquery($workOnTable,'crfeuser_id='.$import['feuser']);
						$cntDeleted = $GLOBALS['TYPO3_DB']->sql_affected_rows();
					} else if (strcmp(strtolower($cfImport['settings.']['deleteAll']),'all')==0) {
						$res = $GLOBALS['TYPO3_DB']->exec_DELETEquery($workOnTable,'1=1');
						$cntDeleted = $GLOBALS['TYPO3_DB']->sql_affected_rows();
					}

					for ($i=0;$i<count($aLines);$i++) {
						if (strlen(trim($aLines[$i]))>1 && substr($aLines[$i],0,1)!='#') {
							$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db,$aLines[$i]);
							//t3lib_div::debug(Array('$aLines['.$i.']'=>$aLines[$i], 'File:Line'=>__FILE__.':'.__LINE__));
							if ($tmp=$GLOBALS['TYPO3_DB']->sql_error()) {
								$isError = TRUE;
								$errCount++;
								$errText[] = Array('Query'=>$aLines[$i], 'Error'=>$tmp);
								//t3lib_div::debug(Array("Query="=>$query, "Res="=>$res, "Error="=>$tmp ));
							} else {
								$saved++;
							}
						} else if (strlen(trim($aLines[$i]))>1 && substr($aLines[$i],0,1)=='#') {
								$commentCount++;
						}
					}

					// Now email to admin and importer
					$pnConf = $cfImport['notify.'];
					if (is_array($pnConf)) {
						$mailto = str_replace('###feuser_email###',$TSFE->fe_user->user['email'],$pnConf['mailto']);
						$mailfrom = $pnConf['mailfrom'];
						$subject = $this->cObj->substituteMarkerArray($pnConf['subject'], $TSFE->fe_user->user, '###feuser_|###');
						$replyto = (strlen($pnConf['replyto'])>1) ? $pnConf['replyto'] : $mailfrom;
						$returnpath = (strlen($pnConf['returnpath'])>1) ? $pnConf['returnpath'] : $mailfrom;
						$tmp = file('typo3temp/'.$importName.'-maillog.txt');
						$mailbody = implode('',$tmp);

						if (strlen($mailto)>0) {
							$hd = "From: ".$mailfrom."\r\n"
								."Reply-To: ".$replyto."\r\n"
								."Return-Path: ".$returnpath."\r\n"
								."X-Mailer: PHP/".phpversion();
							$result = $this->felib->sendMail($mailto,$subject,$mailbody,$hd,"-f".$returnpath	);
						}
					}

					$content .= '<hr />';
					if ($cntDeleted) {
						$tmpStr = sprintf($this->constText['imp_log_result_deleted'],$cntDeleted);
						$content .= '<font color="#00c000">'.$tmpStr.'</font><br />';
					}

					if (($saved+$commentCount)==count($aLines)) {
						$tmpStr = sprintf($this->constText['imp_log_result_ok'],$saved,(count($aLines)-$commentCount));
						$content .= '<font color="#00c000">'.$tmpStr.'</font><br />';
					} else {
						$tmpStr = sprintf($this->constText['imp_log_result_error'],$saved,(count($aLines)-$commentCount));
						$content .= $this->constObj->getWrap('warn',$tmpStr.'<br />');
					}

					$content .= '<br /><hr />';
					if ($cfImport['postProcess']) {
						if (is_callable(Array($this,$cfImport['postProcess']))) {
							$content .= $this->$cfImport['postProcess']($cfImport);
						} else {
							$content .= $this->constObj->getWrap('warn','ERROR: Missing postprocess-function $this->'.$cfImport['postProcess']);
						}
					}

					if (count($errText)>0) {
						t3lib_div::debug(Array('$errText'=>$errText, 'File:Line'=>__FILE__.':'.__LINE__));

					}
				}  // ############################################################################################################
			} else {
				t3lib_div::debug(Array('ERROR'=>'ERROR import.settings or import.fields not defined', '$settings'=>$settings, '$fields'=>$fields, '$show'=>$show, 'File:Line'=>__FILE__.':'.__LINE__));
			}
			$content .= '<br /><br />Execution Time = '.(time()-$myTime).'<br />';

		}


	// ----------------------------------------------------------------------------------------------------------------
	return $content;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$text: ...
	 * @return	[type]		...
	 */
	function newsImportHasHref ($text) {
		$error = false;

		$regs = Array();
		$x = strpos($text,'href');
		if ($x) {
			$x = substr ($text,$x-3);
			//$error = strtok ($x,"/a");
			$p1 = strpos ($x,'>');
			if ($p1>0) {
				$p2 = strpos ($x,'>',($p1+2));
				if ($p2>0) {
					$error = substr($x,0,$p2+2);
				} else {
					$error = substr($x,0,$p1+2);
				}
			} else {
				$error = $x;
			}
		}

		return ($error);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$params: ...
	 * @param	[type]		$text: ...
	 * @param	[type]		$eregList: ...
	 * @param	[type]		$eregCount: ...
	 * @return	[type]		...
	 */
	function newsReplace ($params,$text,&$eregList,&$eregCount) {
		$newText = str_replace($quote, "\\'",$text);
		$newText = str_replace("\x0B", "\\r\\n",$newText);

		if (isset($params['ereg.'])) {
			$myOnly = $params['only'];
			$doEreg = TRUE;
			if ($myOnly && !stristr($text,$myOnly)) {
				$doEreg = FALSE;
				$eregCount['00 - None']++;
			} else {
				$eregCount['00 - Any '.$myOnly]++;
			}
			if ($doEreg) for (reset($params['ereg.']);$eKey=key($params['ereg.']);next($params['ereg.'])) {
				$myS = $params['ereg.'][$eKey]['from'];
				if ($myOnly && !stristr($newText,$myOnly)) {
					$myS = FALSE;
					$eregCount['01 - No more '.$myOnly.' break']++;
					break;
				} else {
					$eregCount['01 - Any more '.$myOnly.' nobreak']++;
				}
				while ($myS) {
					$myT = $params['ereg.'][$eKey]['to'];
					$f = Array();
					if (eregi($myS,$newText,$f)) {
						for ($i3=1;$i3<count($f);$i3++) {
							$myT = str_replace ("\\".$i3,trim($f[$i3]),$myT);
						}
						$eregList[$f[0]] = $myT;
						$newText = str_replace ($f[0],$myT,$newText);
						$eregCount[$eKey.' - '.$myS]++;
					} else {
						$myS = FALSE;
					}
				}
			}
		}
		//t3lib_div::debug(Array('$f'=>$f, '$myTo'=>$myTo, 'File:Line'=>__FILE__.':'.__LINE__));
		//t3lib_div::debug(Array('$params'=>$params, '$text'=>$text, '$newText'=>$newText, 'File:Line'=>__FILE__.':'.__LINE__));
		return ($newText);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$fields: ...
	 * @param	[type]		$key: ...
	 * @param	[type]		$xKey: ...
	 * @param	[type]		$tPar: ...
	 * @param	[type]		$myData: ...
	 * @param	[type]		$mData: ...
	 * @return	[type]		...
	 */
	function newsGetImportFields ($fields,$key,$xKey,$tPar,&$myData,&$mData) {
		$myVal = '';
		if (count($fields[$key]['id'])>1) {
			$implodeWith = ',';
			$implodeReplace = ';';
			if (is_array($fields[$key]['implode.'])) {
				if (isset($fields[$key]['implode.']['with'])) {
					$implodeWith = $fields[$key]['implode.']['with'];
				}
				if (isset($fields[$key]['implode.']['replace'])) {
					$implodeReplace = $fields[$key]['implode.']['replace'];
				}
			}
			$tmp = array();
			$tmp2 = array();
			for ($i2=0;$i2<count($fields[$key]['id']);$i2++) {
				if ($tPar[intval($fields[$key]['id'][$i2])-1]) {
					$t1 = $tPar[intval($fields[$key]['id'][$i2])-1];
					$t2 = str_replace($implodeWith,$implodeReplace,$t1);
					if (strcmp($t1,$t2)) {
						// Warning !!
						$isWarning = TRUE;
						$tmpStr = sprintf($this->constText['imp_warn_containssep'],$xKey);
						$errText['w'] .= $tmpStr.' ("'.htmlentities($implodeWith).'")<br />';
						$errors['w'][$tmpStr]++;
					}
					$tmp[] = $t2;
					$tmp2[] = htmlspecialchars($t2);
				}
			}
			$myVal = trim(implode($implodeWith.'<font color=red>&lt;&gt;</font>'.'<br />',$tmp2));
			$mData['###'.str_replace('.','',$key).'###'] = $myVal ? $myVal : '<font color=#008000><i>[-]</i></font>';
			$myVal = trim(implode($implodeWith,$tmp));
			$myData[str_replace('.','',$key)] = $myVal;
		} else if (intval($fields[$key]['id'][0])>0) {
			$myVal = $this->divObj->cropHtmlText(trim($tPar[intval($fields[$key]['id'][0])-1]),$fields[$key]['showcrop']);
			$mData['###'.$xKey.'###'] = $myVal ? $myVal : '<font color=#008000><i>[-]</i></font>';
			$myVal = trim($tPar[intval($fields[$key]['id'][0])-1]);
			$myData[$xKey] = $myVal;
		} else {
			$mData['###'.$xKey.'###'] = '<font color=red><i>[??]</i></font>';
		}
		return ($myVal);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$$myData: ...
	 * @param	[type]		$input: ...
	 * @param	[type]		$conf: ...
	 * @return	[type]		...
	 */
	function importPreProcess(&$myData,$input=Array(),$conf=Array()) {
		//t3lib_div::debug(Array('importPreProcess'=>$myData, 'File:Line'=>__FILE__.':'.__LINE__));
	}


	function importPreProcessSummary() {
		//t3lib_div::debug(Array('importPreProcess'=>$myData, 'File:Line'=>__FILE__.':'.__LINE__));
		return ('');
	}





}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.txsg_base_import.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.txsg_base_import.php']);
}
?>