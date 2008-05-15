<?php

class sg_browser_file extends sg_browserbase {

	function init ($conf) {
		parent::init($conf);

		$this->folder = t3lib_div::getFileAbsFileName($this->params['uploadfolder']);
		if (strcmp(substr($this->folder,-1),'/')) {
			$this->folder .= '/';
		}
		//t3lib_div::debug(Array('$this->fTypes'=>$this->fTypes, 'listmode='=>$this->params['listmode'], 'File:Line'=>__FILE__.':'.__LINE__));
		$this->fTypes = 'files';
		if ($this->params['listmode']=='webimg') {
			$this->fTypes = 'images';
			$this->bparams = 'gif,jpg,jpeg,png';
			$this->accept = 'image/gif,image/jpg,image/jpeg,image/png';
		} else if ($this->params['listmode']=='pdf') {
			$this->bparams = 'pdf';
			$this->accept = 'application/pdf';
		} else if ($this->params['listmode']=='files') {
			$this->bparams = '*';
			$this->accept = '';
		} else {
			$this->bparams = $this->params['listmode'];
			$this->accept = 'image/jpeg,image/gif';
		}
		if (strlen(trim($conf['accept']))) {
			$this->bparams = trim($conf['ext']);
			$this->accept = trim($conf['accept']);
		}

	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$content: ...
	 * @param	[type]		$conf: ...
	 * @return	[type]		...
	 */
	function main ($content, $conf) {
		GLOBAL $HTTP_POST_FILES;
		GLOBAL $TCA;

		$myMaxSize = (intval($this->params['maxSize'])>1) ? intval($this->params['maxSize']) : 500;

		$content = '';
		$message = '';
		$upload = '';

		$template = implode ('',file($this->tp));
		//t3lib_div::debug(Array('folder'=>$this->folder, 'params'=>$this->params, '$brClass'=>$brClass, '$this->bparams'=>$this->bparams, '$this->accept'=>$this->accept, 'File:Line'=>__FILE__.':'.__LINE__));

		$cntFilesUpload = 1;
		if (intval(t3lib_div::GPvar('mFU'))>0) {
			$cntFilesUpload = intval(t3lib_div::GPvar('mFU'));
		}

		$myShowMax = intval($conf['showMax'])>0 ? intval($conf['showMax']) : 10;
		if (intval($this->params['maxItems'])>0 && $myShowMax>intval($this->params['maxItems'])) {
			$myShowMax = intval($this->params['maxItems']);
		}
		$myShowMin = intval($conf['myShowMin'])>0 ? intval($conf['myShowMin']) : 1;
		if (intval($this->params['maxItems'])>0 && $myShowMin>intval($this->params['maxItems'])) {
			$myShowMin = intval($this->params['maxItems']);
		}

		if ($cntFilesUpload>$myShowMax) {
			$cntFilesUpload = $myShowMax;
		}
		if ($cntFilesUpload<$myShowMin) {
			$cntFilesUpload = $myShowMin;
		}

		$errCount = 0;
		$doUpload = t3lib_div::GPvar('doUpload');
		$fl = $HTTP_POST_FILES['fileList'];
		if ($this->params['imageallow']!=2 && $doUpload>0 && is_array($fl) && is_array($fl['name'])) {
			for ($i=0;$i<count($fl['name']);$i++) {
				if (strlen($fl['name'][$i])>0) {
					//echo 'Upload Protokoll:<br />';
					//echo 'FileName = '.$HTTP_POST_FILES['myFile']['name'].'<br />';
					//echo 'FileType = '.$HTTP_POST_FILES['myFile']['type'].'<br />';
					//echo 'FileSize = '.$HTTP_POST_FILES['myFile']['size'].'<br />';
					//echo 'TempName = '.$HTTP_POST_FILES['myFile']['tmp_name'].'<br />';

					if (is_uploaded_file($fl['tmp_name'][$i])) {
						$myFilesize = filesize($fl['tmp_name'][$i]);
						$myFileExtension = false;
						if (preg_match('/\.([^\.]+)$/', $fl['name'][$i], $match)) {
							$myFileExtension = $match[1];
						}
						if ($myFilesize > $myMaxSize * 1024) {
							unlink ($fl['tmp_name'][$i]);
							$message .= 'File "'.$fl['name'][$i].'" NOT uploaded !!<br />';
							$message .= '.... Max Filesize exeeded: '.intval($myFilesize/1024).'kB &gt; '.$myMaxSize.'kB<br />';
							$errCount++;
						} elseif (!$this->extOK($myFileExtension)) {
							unlink ($fl['tmp_name'][$i]);
							$message .= 'File "'.$fl['name'][$i].'" NOT uploaded !!<br />';
							$message .= $this->extError;
							$errCount++;
						} else {
							$fl['name'][$i] = str_replace(' ','_',$fl['name'][$i]);
							$toName = $this->folder.$this->params['own'].$fl['name'][$i];
							$res = move_uploaded_file($fl['tmp_name'][$i], $toName);
							if ($res) {
								chmod ($toName,0664);
								$message .= 'File "'.$fl['name'][$i].'" was uploaded<br />';
								$message .= '<script type="text/javascript">'.$crlf;
								//$message .= 'document.write ("set to value = '.$this->params['owr'].$fl['name'][$i].'<br>");'.$crlf;
								$message .= 'setElement("'.$this->ext.'","'.$this->vn.'","'.$this->params['own'].$fl['name'][$i].'",'.
									(($i+1)==count($fl['name']) && $errCount==0 ? intval($this->params['noAutoClose']) : 1).')'.$crlf;
								$message .= '</script>'.$crlf;
							} else {
								$message .= 'Upload Error : '.$fl['error'][$i].'<br />';
								$errCount++;
							}
						}
					} else {
						$message .= 'Upload File Error : '.$fl['error'][$i].'<br />';
					}
				}
			}
		}


		$doDelete = t3lib_div::GPvar('doDelete');
		$myFiles = t3lib_div::GPvar('myFiles');

		$doContinue = TRUE;
		if ($doDelete==1 && count($myFiles)>0) {
			$message .=  '<form name="fileDelete" method="post">';
			$message .=  '<input type="hidden" name="doDelete" value="2">';
			$message .=  count($myFiles).' '.$this->fTypes.' to delete:<br />';
			for (reset($myFiles);$key=key($myFiles);next($myFiles)) {
				if ($myFiles[$key]==1) {
					$message .=  '<input type="hidden" name="myFiles['.$key.']" value="'.$key.'"/>';
					$message .=  $key.'<br />';
				}
			}
			$message .=  '<br /><input type="submit" value="REALLY DELETE">';
			$message .=  '</form><form name="dontDelete" method="post">';
			$message .=  '<input type="hidden" name="doDelete" value="0">';
			$message .=  '<input type="submit" value="Cancel">';
			$message .=  '</form>';

			$doContinue = FALSE;
		}

		if ($doDelete==2 && count($myFiles)>0) {
			$message .=  count($myFiles).' Deleted '.$this->fTypes.' :<br />';
			for (reset($myFiles);$key=key($myFiles);next($myFiles)) {
				unlink($this->folder.$key);
				$message .=  $key.'<br />';
			}
		}

		if ($doContinue) {

			if ($conf['showCntSelector']) {
				$upload .= '<select name="mFU" onchange="browserReload(this.options[this.selectedIndex].value,'.QT.t3lib_div::getIndpEnv('TYPO3_REQUEST_URL').QT.');">';
				for ($i=$myShowMin;$i<$myShowMax+1;$i++) {
					$upload .= '<option value="'.($i).'"'.
						(($i)==$cntFilesUpload ? ' selected="selected" ' : '').
						'>'.($i).' files</option> ';
				}
				$upload .= '</select><br />';
			}

			if ($this->params['imageallow']!=2) {
				$upload .=  '<form name="fileUpload" enctype="multipart/form-data" method="post">';
				$upload .=  '<input type="hidden" name="doUpload" value="1">';
				for ($i=0;$i<$cntFilesUpload;$i++) {
					$upload .=
						'<input type="file" size="36" maxlength="'.($myMaxSize*1024).'" name="fileList[]" accept="'.$this->accept.'"><br />';
				}
				$upload .=  'File <input type="submit" value="Upload">';
				$upload .=  '</form>';
			}

			if ($this->params['imageallow'] & 1) {
				$content .=  '<form name="fileDelete" method="post">';
				$content .=  '<input type="hidden" name="doDelete" value="1">';
			}
			$content .=  'List of '.$this->fTypes.' (Type='.$this->bparams.')<br />';

			$this->addValue = '+';
			if (strcmp($conf['addButton'],'TEXT')==0) {
				$this->addValue = $conf['addButton.']['value'];
			} else if (strcmp($conf['addButton'],'IMAGE')==0) {
				$this->addValue = '<img src="/'.$GLOBALS['TSFE']->tmpl->getFileName($conf['addButton.']['file']).'" border="0" />';
			}

			$lineTmpl = (strlen($conf['lineTemplate'])<3) ? '###check### ###filelink###<br />' : $conf['lineTemplate'];
			$d = dir($this->folder);
			if ($d) {
				$entry = Array();
				while($tmp=$d->read()) {
					$extension = strrchr ($tmp, '.');
					if (strlen($extension)>1 && strcmp(substr($extension,0,4),'.php')) {
						$entry[strtolower($tmp)] = $tmp;
					}
				}
				ksort($entry);
				for(reset($entry);$key=key($entry);next($entry)) {
					$extension = ','.strtolower(substr(strrchr($entry[$key], "."), 1)).',';
					$mx = Array('###check###'=>'', '###add###'=>'');
					if (strcmp(substr($entry[$key],0,strlen($this->params['own'])),$this->params['own'])==0) {
						if ($this->extOK($extension)) {
							if ($this->params['imageallow'] & 1) {
								$mx['###check###'] = '<input type="checkbox" name="myFiles['.$entry[$key].']" value="1"/>';
							}
							$mx['###file###'] = $entry[$key];
							$mx['###file_a###'] = '<a href="#" onclick="return setElement('.QT.$this->ext.QT.','.QT.$this->vn.QT.','.QT.$entry[$key].QT.','.QT.$this->params['noAutoClose'].QT.');">';
							$mx['###filelink###'] = $mx['###file_a###'].$entry[$key].'</a>';
							if ($conf['showAddButton'] && intval($this->params['maxItems'])!=1) {
								$mx['###add###'] = '<a href="#" onclick="return setElement('.QT.$this->ext.QT.','.QT.$this->vn.QT.','.QT.$entry[$key].QT.',1);">'.$this->addValue.'</a>';
								}
							$content .= $this->substituteMarkerArray($lineTmpl,$mx);
						}
					}
				}
			}
			$d->close();

			if ($this->params['imageallow'] & 1) {
				$content .=  '<br /><input type="submit" value="Delete marked">';
				$content .=  '</form>';
			}

		}



		$m = array();
		$m['###HEADERDATA###'] = $this->jsIncludeCode;
		$m['###MESSAGE###'] = $message . (strlen($message)>1 ? '<hr />' : '');
		$m['###UPLOAD###'] = $upload . (strlen($upload)>1 ? '<hr />' : '');
		$m['###FILELIST###'] = $content;

		reset ($m);
		while (list($marker,$markcont)=each($m)) {
			$template = str_replace($marker,$markcont,$template);
		}



		return ($template);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$extension: ...
	 * @return	[type]		...
	 */
	function extOK ($extension) {
		$ok = false;
		$this->extError = 'No files without extension allowed !<br />';
		if ($extension && strlen($extension)>1) {
			if (strcmp($this->bparams,'*')) {
				if (strpos(','.strtolower($this->bparams).',', strtolower($extension))>0) {
					$ok = true;
				} else {
					$this->extError = '.... Invalid Extension: only '.$this->bparams.' allowed !!<br />';
				}
			} else {
				$ok = true;
			}

			if (strpos(',php,php3,php4,php5,inc,', strtolower($extension))>0) {
				$ok = false;
				$this->extError = '.... Invalid Extension: *.php and *.inc are NOT allowed !!<br />';
			}
		}

		return ($ok);
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zfelib/browser/browsefile.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zfelib/browser/browsefile.php']);
}
?>
