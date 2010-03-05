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
 *   46: class tx_sglib_debug
 *   74:     private function init(tx_sglib_factory $factoryObj)
 *   95:     private function _fCount ($name=NULL)
 *  118:     function __destruct()
 *  132:     function isDebug ($when,$field='',$onlyIf=TRUE,$alwaysIf=FALSE)
 *  150:     function debug($what,$view=false)
 *  176:     function debugIf ($when,$what,$onlyIf=TRUE,$alwaysIf=FALSE)
 *  192:     function displayIf ($when,$what,$onlyIf=TRUE,$alwaysIf=FALSE)
 *  211:     function debugVal ($when,$field,$what,$condition=TRUE,$alwaysIf=FALSE)
 *  225:     function shortBacktrace ($count=5,$minimize=0)
 *  265:     function showError ($shortcut, $message, $mode=0, $data=array(),$file='',$line=0)
 *
 * TOTAL FUNCTIONS: 10
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_debug {
	private static $instance = Array();

	private $factoryObj = NULL;
	private $dodebug=Array();
	private $dbg = 0;
	private $debugCnt = 0;
	private $confObj;
	private $defaultDesignator;

	protected function __construct() {}

	private function __clone() {}

	/**
	 * Returns a singlton instance of tx_sglib_debug
	 *
	 * @param	string				Designator
	 * @param	tx_sglib_factory	FactoryObj
	 * @return	tx_sglib_debug	Instantiated Object
	 */
	
	public static function getInstance($designator, tx_sglib_factory $factoryObj) {
		if (!isset(self::$instance[$designator])) {
			self::$instance[$designator] = new tx_sglib_debug();
			self::$instance[$designator]->init($factoryObj);
		}
		return (self::$instance[$designator]);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$tx_sglib_config $confObj: ...
	 * @return	[type]		...
	 */
	private function init(tx_sglib_factory $factoryObj) {
		$this->_fCount(__FUNCTION__);
		$this->factoryObj = $factoryObj;
		$this->confObj = $this->factoryObj->getConfObj();
		$this->defaultDesignator =$this->factoryObj->getDesignator();

		$conf = $this->confObj->dodebug;
		if (is_array($conf)) foreach ($conf as $key=>$value) {
			$this->dodebug[strtolower($key)] = strtolower($value);
		}
		$this->dodebug['1'] = 1;
		$this->dbg = $this->confObj->getFFvalue('fieldDebugMode','sDefault');
		$this->debugIf('dodebug',Array('dbg (global)'=>$this->dbg, 'dodebug.'=>$this->dodebug));
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
		$this->debugIf('callCount',Array('Class '.__CLASS__ => $this->_fCount()));
	}


	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$when: ...
	 * @param	[type]		$condition: only debug if this is true (example: only show debug, if a special fieldvalue matches)
	 * @param	[type]		$condition: always debug, if true (example: set this to mysql-error; so error will debug; else only if debug enabled)
	 * @param	[type]		$alwaysIf: ...
	 * @return	[boolean]
	 */
	function isDebug ($when,$field='',$onlyIf=TRUE,$alwaysIf=FALSE) {
		$this->_fCount(__FUNCTION__);
		if (($alwaysIf && $this->dbg>=-1) || ($this->dbg>=0 && $onlyIf &&
				($when==1  || $this->dbg>0||
					( (strlen($field)<1 && $this->dodebug[strtolower($when)]) || (strlen($field)>0 && strcmp($this->dodebug[$when],strtolower($field))==0) ) ) ) ) {
			return (true);
		} else {
			return (false);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$what: Array of values to debug
	 * @param	[type]		$view: ...
	 * @return	[type]		void
	 */
	function debug($what,$view=false) {
		$this->_fCount(__FUNCTION__);
		if (!is_array($what)) {
			$what = Array('-'=>$what, 'File:Line'=>$this->shortBacktrace (5,1));
		}
		if (!isset($what['File:Line'])) {
			$tmp = debug_backtrace();
			$what['File:Line'] = $tmp[1]['file'].':'.$tmp[1]['line'].' - dodebug.'.$when;
		}
		$this->debugCnt++;
		if (!$view) {
			t3lib_div::debug($what);
		} else {
			return (t3lib_div::view_array($what));
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$when: Debug only if set to 1 or if found in dodebug-array
	 * @param	[type]		$what: Array of values to debug
	 * @param	[type]		$condition: only debug if this is true (example: only show debug, if a special fieldvalue matches)
	 * @param	[type]		$condition: always debug, if true (example: set this to mysql-error; so error will debug; else only if debug enabled)
	 * @return	[type]		...
	 */
	function debugIf ($when,$what,$onlyIf=TRUE,$alwaysIf=FALSE) {
		$this->_fCount(__FUNCTION__);
		if (($alwaysIf && $this->dbg>=-1) || ($this->dbg>=0 && $onlyIf && ($when==1 || $this->dodebug[strtolower($when)] || $this->dbg>0) ) ) {
			$this->debug($what);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$when: Debug only if set to 1 or if found in dodebug-array
	 * @param	[type]		$what: Array of values to debug
	 * @param	[type]		$condition: only debug if this is true (example: only show debug, if a special fieldvalue matches)
	 * @param	[type]		$condition: always debug, if true (example: set this to mysql-error; so error will debug; else only if debug enabled)
	 * @return	[type]		...
	 */
	function displayIf ($when,$what,$onlyIf=TRUE,$alwaysIf=FALSE) {
		$this->_fCount(__FUNCTION__);
		if (($alwaysIf && $this->dbg>=-1) || ($this->dbg>=0 && $onlyIf && ($when==1 || $this->dodebug[strtolower($when)] || $this->dbg>0) ) ) {
			return($this->debug($what,TRUE));
		} else {
			return ('');
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$when: ...
	 * @param	[type]		$field: ...
	 * @param	[type]		$what: ...
	 * @param	[type]		$condition: ...
	 * @param	[type]		$alwaysIf: ...
	 * @return	[type]		...
	 */
	function debugVal ($when,$field,$what,$condition=TRUE,$alwaysIf=FALSE) {
		$this->_fCount(__FUNCTION__);
		if (($alwaysIf && $this->dbg>=-1) || ($condition && ($when==1 || $this->dodebug[$when]==1 || strcmp($this->dodebug[$when],strtolower($field))==0 || $this->dbg>0) ) ) {
			$this->debug($what);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$count: ...
	 * @param	[type]		$minimize: ...
	 * @return	[type]		...
	 */
	function shortBacktrace ($count=5,$minimize=0) {
		$this->_fCount(__FUNCTION__);
		$r = Array();
		$tmp = debug_backtrace();
		$count = (count($tmp)<$count) ? count($tmp)-1 : $count;
		for ($i=$count;$i>0;$i--) {
			if ($minimize) {
				$r[$i] = '=>'.$tmp[$i]['file'].':'.$tmp[$i]['line'];
			} else {
				$arg = Array();
				for ($j=0;$j<count($tmp[$i]['args']);$j++) {
					if (is_array($tmp[$i]['args'][$j])) {
						$arg[] = 'array['.count($tmp[$i]['args'][$j]).']';
					} else if (is_string($tmp[$i]['args'][$j])) {
						$arg[] = QT.substr($tmp[$i]['args'][$j],0,11).(strlen($tmp[$i]['args'][$j])>11?'...':'').QT;
					} else if (is_integer($tmp[$i]['args'][$j])) {
						$arg[] = $tmp[$i]['args'][$j];
					} else {
						$arg[] = gettype($tmp[$i]['args'][$j]);
					}
				}
				$r[$i] = Array('File:Line'=>$tmp[$i]['file'].':'.$tmp[$i]['line'],
				'Func'=>$tmp[$i]['class'].$tmp[$i]['type'].$tmp[$i]['function'].'('.implode(',',$arg).')',
				);
			}
		}
		return ($r);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$shortcut: ...
	 * @param	[type]		$message: ...
	 * @param	[type]		$mode: ...
	 * @param	[type]		$data: ...
	 * @param	[type]		$file: ...
	 * @param	[type]		$line: ...
	 * @return	[type]		...
	 */
	function showError ($shortcut, $message, $mode=0, $data=array(),$file='',$line=0) {
		echo ('<hr />');
		$tmp = debug_backtrace();
		if (!$file && ($i = count($tmp))>0) {
			$file = $tmp[$line]['file'];
			$line = $tmp[$line]['line'];
		}
		if ($file) {
			echo ('<b>ERROR in -------------------------------- '.$file.':'.$line.'</b><br />');
		}
		$messageLine = explode('<br />',$message);
		foreach ($messageLine as $line) {
			echo ('<b>ERROR: '.$line.'</b><br />');
		}
		if (is_array($data) && count($data)) {
			echo t3lib_div::view_array($data);
		}
		if ($mode>=SGZLIB_FATALERROR) {
			echo ('<hr />');
			die ();
		}
	}

}


?>