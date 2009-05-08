<?php

class sg_browser_db extends sg_browserbase {

	function init ($conf) {
		parent::init($conf);
		$this->pluginMode = t3lib_div::_GP('plugin');
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$content: ...
	 * @param	[type]		$conf: ...
	 * @return	[type]		...
	 */
	function main ($content, $conf) {
		GLOBAL $TCA;

		$this->conf = $conf;
		$myShow = '';
		$sorting = '';
		$refType = '';
		$myPid = 0;
		if ($this->pluginMode) {
			$plugTSname = 'tx_'.str_replace('_','',$this->pluginMode).'_pi1';
			$pluginConf = $GLOBALS['TSFE']->tmpl->setup['plugin.'][$plugTSname.'.']['tagInsert.'];
			//t3lib_div::debug(Array('pluginMode'=>$this->pluginMode, '$plugTSname'=>$plugTSname, 'params'=>$this->params, '$pluginConf'=>$pluginConf, 'File:Line'=>__FILE__.':'.__LINE__));

			$myTable = $pluginConf['table'];
			$myShow = $pluginConf['show'];
			$sorting = $pluginConf['sort'];
			$refType = $pluginConf['refType'];
			$this->myInsert = $pluginConf['insert'];
		} else {
			$myTable = $this->params['foreign_table'];
			if (is_array($conf[$myTable.'.'])) {
				$sorting = ($conf[$myTable.'.']['sort'] ? $conf[$myTable.'.']['sort'] : $conf['sort']);
				$myPid = $conf[$myTable.'.']['pid'];
			}
		}

		t3lib_div::loadTCA($myTable);
		$myTCA = $TCA[$myTable];
		// t3lib_div::debug(Array('$conf'=>$conf, 'params'=>$this->params, 'File:Line'=>__FILE__.':'.__LINE__));

		$this->levelString = ($this->params['levelString']) ? $this->params['levelString'] : '&nbsp; &nbsp;' ;

		if (!$myShow) {
			$myShow = $myTCA['ctrl']['label'];
			$sorting = $myShow;
		}

		if ($this->params['table']['sort']) {
			$sorting = $this->params['table']['sort'];
		}

		if (!$sorting) {
			$sorting = (isset($myTCA['ctrl']['sort'])) ? $myTCA['ctrl']['sort'] : $myShow ;
		}

		if (!$refType) {
			$refType = $this->params['refType'];
		}

		$template = implode ('',file($this->tp));

		$content = ''; //'...Hier MESSAGE-Marker! ...<br />';
		//$content .= '<a href="#" onclick="return setElement('.QT.$ext.QT.','.QT.$vn.QT.','.QT.$entry.QT.');">Set to '.$entry.'</a><br /><br />';

		// TODO: MM-Relations
		$query = $this->getMainQuery($myShow,$myTable,$this->conf);


		$myClause = Array('deleted=0');

		if (is_array($myTCA['ctrl']['enablecolumns']) && isset($myTCA['ctrl']['enablecolumns']['disabled'])) {
			$myClause[] = $myTCA['ctrl']['enablecolumns']['disabled'].'=0';
		}

		if (isset ($this->params['table']['idrestrict'])) {
				$myClause[] = 'uid in ('.$this->params['table']['idrestrict'].")";
		}

		if (is_array($this->params['relation'])) {
			$tmp = $this->params['relation']['foreign'];
			if (strlen($tmp)>0) {
				$myClause[] = $tmp.'='.intval($this->params['relation']['localval']);
			}
		}

		if ($this->params['pid']) {
			$myClause[] = 'pid IN ('.$this->params['pid'].')';
		}

		if ($myPid) {
			$myClause[] = 'pid='.intval($myPid);
		}

		if ($this->params['where']) {
			$myClause[] = $this->params['where'];
		}

		$this->addValue = '+';
		if (strcmp($conf['addButton'],'TEXT')==0) {
			$this->addValue = $conf['addButton.']['value'];
		} else if (strcmp($conf['addButton'],'IMAGE')==0) {
			$this->addValue = '<img src="/'.$GLOBALS['TSFE']->tmpl->getFileName($conf['addButton.']['file']).'" border="0" />';
		}

		$this->addDisValue = '&nbsp;';
		if (strcmp($conf['addDisButton'],'TEXT')==0) {
			$this->addDisValue = $conf['addDisButton.']['value'];
		} else if (strcmp($conf['addDisButton'],'IMAGE')==0) {
			$this->addDisValue = '<img src="/'.$GLOBALS['TSFE']->tmpl->getFileName($conf['addDisButton.']['file']).'" border="0" />';
		}

		$this->lineTmpl = (strlen($conf['lineTemplate'])<3) ? '###dblink###<br />' : $conf['lineTemplate'];

		$query .= implode(' AND ',$myClause);
		$sorting = 'GROUP BY '.$myTable.'.uid ORDER BY '.$myTable.'.'.$sorting.'  ;';

		if ($this->params['parent']) {
			$content = $this->getRecursiveEntries ($query,$sorting,$this->params['parent'],$this->params['subtree']);
		} else {
			$content = $this->getEntries ($query,$sorting);
		}

		$m = array();
		$m['###HEADERDATA###'] = $this->jsIncludeCode;
		$m['###MESSAGE###'] = '';
		$m['###BROWSELIST###'] = $content;

		reset ($m);
		while (list($marker,$markcont)=each($m)) {
			$template = str_replace($marker,$markcont,$template);
		}

	return ($template);

	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$query: ...
	 * @param	[type]		$sorting: ...
	 * @return	[type]		...
	 */
	function getEntries ($query,$sorting) {
		$content = '';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query.' '.$sorting);
		if ($res) {
			if ($GLOBALS['TYPO3_DB']->sql_num_rows($res)>0) {
				while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
					$content .= $this->addRow ($row);
				}
			} else {
					$content .= '<br /><i>- keine verf&uuml;gbar -</i><br/>';
			}
		} else {
			echo 'QUERY: '.$query.'<br />ERROR: '.$GLOBALS['TYPO3_DB']->sql_error();
		}
	return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$query: ...
	 * @param	[type]		$sorting: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$id: ...
	 * @param	[type]		$maxRekursion: ...
	 * @return	[type]		...
	 */
	function getRecursiveEntries ($query,$sorting,$field,$id,$maxRekursion=100) {
		$content = '';
		if ($maxRekursion>0) {
			$res = $GLOBALS['TYPO3_DB']->sql_query($query.' AND '.$field.' IN ('.$id.') '.$sorting);
			if ($res) {
				if ($GLOBALS['TYPO3_DB']->sql_num_rows($res)>0) {
					while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
						$c2 = $this->getRecursiveEntries ($query,$sorting,$field,$row['uid'],$maxRekursion-1);
						$content .= $this->addRow ($row,100-$maxRekursion,(strlen($c2)==0 || $this->conf['allowNodes'])).$c2;
					}
				} else  if ($maxRekursion==100) {
					$content .= '<br /><i>- keine verf&uuml;gbar -</i><br/>';
				}
			} else {
				echo 'QUERY: '.$query.'<br />ERROR: '.$GLOBALS['TYPO3_DB']->sql_error();
			}
		}
		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$row: ...
	 * @param	[type]		$level: ...
	 * @param	[type]		$doLink: ...
	 * @return	[type]		...
	 */
	function addRow ($row,$level=0,$doLink=TRUE) {
		$mx = Array('###check###'=>'', '###add###'=>'', '###level###'=>str_repeat ($this->levelString, $level) );
		$valToShow = trim($row['myDisplayName']);
		$mx['###title###'] = $valToShow;

		if (($tmp=$this->params['table']['noLinkField'])) {
			if ($row[$tmp]) {
				$doLink = FALSE;
			}
		}
		if ($doLink) {
			if ($this->pluginMode) {
				$doInsert = $this->substituteMarkerArray($this->myInsert,$row,'###|###',0);
				$onc = 'setAutoInsert('.QT.$doInsert.QT.',0); return (false);';
				$mx['###db_a###'] = '<a href="" onclick="'.$onc.'">';
				$onc = 'setAutoInsert('.QT.$doInsert.QT.',1); return (false);';
				$tmp = '<a href="" onclick="'.$onc.'">';
			} else {
				if (strcmp($this->params['refType'],'title')==0) {
					$mx['###db_a###'] = '<a href="#" onclick="setIdTextElement('.
						QT.$this->ext.QT.','.QT.$this->vn.QT.','.QT.addslashes($valToShow).QT.','.QT.$valToShow.QT.',0); return (false);">';
					$tmp = '<a href="#" onclick="setIdTextElement('.
						QT.$this->ext.QT.','.QT.$this->vn.QT.','.QT.addslashes($valToShow).QT.','.QT.$valToShow.QT.',1);" return (false);>';
				} else {
					$mx['###db_a###'] = '<a href="#" onclick="setIdTextElement('.
						QT.$this->ext.QT.','.QT.$this->vn.QT.','.QT.addslashes($valToShow).QT.','.QT.$row['uid'].QT.',0); return (false);">';
					$tmp = '<a href="#" onclick="setIdTextElement('.
						QT.$this->ext.QT.','.QT.$this->vn.QT.','.QT.addslashes($valToShow).QT.','.QT.$row['uid'].QT.',1); return (false);">';
				}
			}
			if ($this->conf['showAddButton'] && intval($this->params['maxItems'])!=1) {
				$mx['###add###'] = $tmp.$this->addValue.'</a>';
				}
		} else {
			if ($this->conf['showAddButton'] && intval($this->params['maxItems'])!=1) {
				$mx['###add###'] = $this->addDisValue;
				}
		}
		$mx['###dblink###'] = $mx['###db_a###'].$valToShow.($doLink ? '</a>' : '');
		$content = $this->substituteMarkerArray($this->lineTmpl,$mx);
		return ($content);
	}


	function getMainQuery ($myShow,$myTable,$conf) {
		$query = 'SELECT *,'.$myShow.' AS myDisplayName FROM '.$myTable.' WHERE ';

		if (is_array($conf['MM.'])) {
			if ($conf['MM.']['table'] && $conf['MM.']['select'] && $conf['MM.']['myIds'] && is_array($conf['MM.']['select.'])) {

				$query = 'SELECT '.$myTable.'.*,'.$myShow.' AS myDisplayName '.
							'FROM '.$myTable.', '.$conf['MM.']['table'].' '.
							' WHERE '.$conf['MM.']['table'].'.'.$conf['MM.']['myIds'].'='.$myTable.'.uid AND ';
				if ($conf['MM.']['select.']['userField']) {
					if (is_object($GLOBALS['TSFE']->fe_user)) {
						if (is_array($GLOBALS['TSFE']->fe_user->user)) {
							$feUser = $GLOBALS['TSFE']->fe_user->user;
							$tmp = $feUser[$conf['MM.']['select.']['userField']];
							if ($tmp) {
								$query .=  $conf['MM.']['table'].'.'.$conf['MM.']['select'].' IN ('.$tmp.') AND ';
							}
						}
					}
				}
				// t3lib_div::debug(Array('$tmp'=>$tmp, '$conf[MM.]'=>$conf['MM.'], '$query'=>$query, 'File:Line'=>__FILE__.':'.__LINE__));
			}
		}

		return ($query);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/browser/browsedb.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/browser/browsedb.php']);
}
?>