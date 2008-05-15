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

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   54: class tx_sglib_const
 *   87:     private function init(tx_sglib_factory $factoryObj)
 *  109:     private function _fCount ($name=NULL)
 *  132:     function __destruct()
 *  142:     private function _initWraps($conf)
 *  157:     private function _initConst($conf)
 *  182:     private function _initMoreConst()
 *  232:     private function _initIcons($conf)
 *  246:     private function _initButtons($conf)
 *  259:     function getWrap ($name=NULL,$part='')
 *  278:     function getConst ($name=NULL)
 *  293:     function getIcon ($name)
 *  308:     function isIconTypeText ($name)
 *  323:     function getIconResource ($name)
 *  337:     function getMarkers()
 *  357:     function getButton($name,$defaultText='',$asResource=FALSE)
 *  400:     function buttonExists($name)
 *  411:     function getButtonType($name)
 *  422:     function getButtonConf($name)
 *
 * TOTAL FUNCTIONS: 18
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_const {
	private static $instance = Array();

	private $factoryObj = NULL;
	private $confObj;
	private $debugObj;
	private $defaultDesignator;

	private $conf=Array();

	private $wraps = Array();
	private $const = Array();
	private $icons = Array();
	private $buttons = Array();

	protected function __construct() {}

	private function __clone() {}

	public static function getInstance($designator, tx_sglib_factory $factoryObj) {
		if (!isset(self::$instance[$designator])) {
			self::$instance[$designator] = new tx_sglib_const();
			self::$instance[$designator]->init($factoryObj);
		}
		return (self::$instance[$designator]);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$factoryObj: ...
	 * @return	[type]		...
	 */
	private function init(tx_sglib_factory $factoryObj) {
		$this->_fCount(__FUNCTION__);
		$this->factoryObj = $factoryObj;
		$this->confObj = $factoryObj->confObj;
		$this->defaultDesignator = $this->confObj->getDesignator();
		$this->debugObj = $factoryObj->debugObj;
		$this->conf = (array) $this->confObj->constants;
		$this->debugObj->debugIf('constConf',Array('conf(constants.)'=>$this->conf, 'File:Line'=>__FILE__.':'.__LINE__));
		$this->langObj = $factoryObj->langObj;

		$this->_initWraps($this->conf['wraps.']);
		$this->_initConst($this->conf['const.']);
		$this->_initIcons($this->conf['icons.']);
		$this->_initButtons($this->conf['buttons.']);
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
	private function _initWraps($conf) {
		$this->_fCount(__FUNCTION__);
		if (is_array($conf)) foreach ($conf as $key=>$value) if(substr($key,-1)!='.') {
			$tmp = $this->confObj->TSobj($value,$conf[$key.'.']);
			$this->wraps[$key] = explode ('|', $tmp ,2);
		}
		$this->debugObj->debugIf('constConf',Array('constants/wraps'=>$this->wraps, 'File:Line'=>__FILE__.':'.__LINE__));
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$conf: ...
	 * @return	[type]		...
	 */
	private function _initConst($conf) {
		$this->_fCount(__FUNCTION__);
		$indpEnv = t3lib_div::getIndpEnv('_ARRAY');
		for (reset($indpEnv);$key=key($indpEnv);next($indpEnv)) {
			$this->const[$key] = $indpEnv[$key];
		}
		$this->const['TYPO3_LISTMODE_URL'] = 
			preg_replace ('/\&*'.$this->defaultDesignator.'\[listmode\]\=[A-Za-z0-9]*/', '', $this->const['TYPO3_REQUEST_URL']);
		if (strpos($this->const['TYPO3_LISTMODE_URL'],'?')===FALSE) {
			$this->const['TYPO3_LISTMODE_URL'] .= '?';
		}
		
		$this->const['PAGE_URL'] = $this->factoryObj->cObj->TypoLink_URL(array('parameter'=>$GLOBALS['TSFE']->id, 'target'=>'_self'));
		$this->const['PAGE_URL'] .= (strpos($this->const['PAGE_URL'], '?')>0) ? '' : '?';

		$this->_initMoreConst();

		if (is_array($conf)) foreach ($conf as $key=>$value) if(substr($key,-1)!='.') {
			$this->const[$key] = $this->confObj->TSObj($conf[$key],$conf[$key.'.']);
		}
		//t3lib_div::debug(Array('$this->const'=>$this->const, 'File:Line'=>__FILE__.':'.__LINE__));

		$this->debugObj->debugIf('constConf',Array('constants/const'=>$this->const, 'File:Line'=>__FILE__.':'.__LINE__));
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	private function _initMoreConst() {
		$this->_fCount(__FUNCTION__);
		$myTime = time();
		$this->const['time'] = $myTime;
		$this->const['sys_tstamp'] = $myTime;
		$this->const['sys_datetimesec'] = date('Ymd-His',$myTime);
		$this->const['sys_datetime'] = date('Ymd-Hi',$myTime);
		$this->const['sys_date'] = date('Ymd',$myTime);
		$this->const['sys_timesec'] = date('His',$myTime);
		$this->const['tsfe_id'] = $GLOBALS['TSFE']->id;
		$this->const['tsfe_lang'] = $GLOBALS['TSFE']->lang;
		$this->const['tsfe_type'] = $GLOBALS['TSFE']->type;
		$this->const['tsfe_L'] = $GLOBALS['TSFE']->sys_language_uid;

		$this->user = Array();
		if ($GLOBALS['TSFE']->beUserLogin && is_object($GLOBALS['BE_USER'])) {
			if (is_array($GLOBALS['BE_USER']->user)) {
				$this->user = $GLOBALS['BE_USER']->user;
			}
		}
		$this->const['beuser_uid'] = intval($this->user['uid']);
		$this->const['beuser_username'] = $this->user['username'];
		$this->const['beuser_realname'] = $this->user['realname'];

		$this->user = Array();
		if (is_object($GLOBALS['TSFE']->fe_user)) {
			if (is_array($GLOBALS['TSFE']->fe_user->user)) {
				$this->user = $GLOBALS['TSFE']->fe_user->user;
			}
		}
		$this->const['feuser_uid'] = intval($this->user['uid']);
		$this->const['feuser_username'] = $this->user['username'];
		$this->const['feuser_name'] = $this->user['name'];
		$this->const['feuser_firstname'] = $this->user['firstname'];
		$this->const['feuser_email'] = $this->user['email'];
		$this->const['feuser_address'] = $this->user['address'];
		$this->const['feuser_company'] = $this->user['company'];
		$this->const['feuser_zip'] = $this->user['zip'];
		$this->const['feuser_city'] = $this->user['city'];
		$this->const['feuser_country'] = $this->user['country'];

		$this->const['gpvar_uid'] = intval(t3lib_div::GPvar('uid'));
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$conf: ...
	 * @return	[type]		...
	 */
	private function _initIcons($conf) {
		$this->_fCount(__FUNCTION__);
		if (is_array($conf)) foreach ($conf as $key=>$value) if(substr($key,-1)!='.') {
			$this->icons[$key] = $this->confObj->TSobj($value,$conf[$key.'.']);
		}
		$this->debugObj->debugIf('constConf',Array('constants/icons'=>$this->icons, 'File:Line'=>__FILE__.':'.__LINE__));
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$conf: ...
	 * @return	[type]		...
	 */
	private function _initButtons($conf) {
		$this->_fCount(__FUNCTION__);

		$this->debugObj->debugIf('constConf',Array('constants/buttons'=>$this->buttons, 'File:Line'=>__FILE__.':'.__LINE__));
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @param	[type]		$part: ...
	 * @return	[type]		...
	 */
	function getWrap ($name=NULL,$part='') {
		$this->_fCount(__FUNCTION__);
		if (isset($name)) {
			if ($part===0 || $part===1) {
				return($this->wraps[$name][$part ? 1 : 0]);
			} else {
				return($this->wraps[$name][0].$part.$this->wraps[$name][1]);
			}
		} else {
			return($this->wraps);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	function getConst ($name=NULL) {
		$this->_fCount(__FUNCTION__);
		if (isset($name)) {
			return(isset($this->const[$name]) ? $this->const[$name] : '***CONST-'.$name.'-UNDEFINED***');
		} else {
			return($this->const);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	function getIcon ($name) {
		$this->_fCount(__FUNCTION__);
		if (isset($this->icons[$name])) {
			return($this->icons[$name]);
		} else {
			return('['.strtoupper($name).']');
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	function isIconTypeText ($name) {
		$this->_fCount(__FUNCTION__);
		if (isset($this->icons[$name])) {
			return(  strcmp($this->conf['icons.'][$name],'IMAGE') );
		} else {
			return(true);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	function getIconResource ($name) {
		$this->_fCount(__FUNCTION__);
		if ($this->isIconTypeText($name)) {
			return($this->getIcon($name));
		} else {
			return($this->factoryObj->cObj->IMG_RESOURCE($this->conf['icons.'][$name.'.']));
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getMarkers() {
		static $markers = Array();
		$this->_fCount(__FUNCTION__);
		if (!is_array($markers[$this->defaultDesignator])) {
			$markers[$this->defaultDesignator] = Array();
			foreach ($this->const as $key=>$value) {
				$markers[$this->defaultDesignator]['###CONST_'.strtoupper($key).'###'] = $this->langObj->getLLL($value);
			}
		}
		return ($markers[$this->defaultDesignator]);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @param	[type]		$defaultText: ...
	 * @param	[type]		$asResource: ...
	 * @return	[type]		...
	 */
	function getButton($name,$defaultText='',$asResource=FALSE) {
		$this->_fCount(__FUNCTION__);
		static $buttons = Array();
		static $buttonResources = Array();
		if (!is_array($buttons[$this->defaultDesignator])) {
			$buttons[$this->defaultDesignator] = Array();
		}
		if (!is_array($buttonResources[$this->defaultDesignator])) {
			$buttonResources[$this->defaultDesignator] = Array();
		}
		if ($asResource && strcmp($this->conf['buttons.'][$name],'IMAGE')==0) {
			if (!isset($buttonResources[$this->defaultDesignator][$name])) {
				$myConf = $this->conf['buttons.'][$name.'.'];
				$buttonCode = (strlen($defaultText)>0) ? $defaultText : '[['.$name.']]';
				$this->factoryObj->cObj->setCurrentVal($buttonCode);
				$buttonResources[$this->defaultDesignator][$name] = $this->factoryObj->cObj->IMG_RESOURCE($myConf);
			}
			return ($buttonResources[$this->defaultDesignator][$name]);
		} else {
			if (!isset($buttons[$this->defaultDesignator][$name])) {
				if (!isset($this->conf['buttons.'][$name])) {
					$buttons[$this->defaultDesignator][$name] = '[['.strtoupper($name).']]';
				} else {
					$myType = $this->conf['buttons.'][$name];
					$myConf = $this->conf['buttons.'][$name.'.'];

					$buttons[$this->defaultDesignator][$name] = (strlen($defaultText)>0) ? $defaultText : '[['.$name.']]';
					$this->factoryObj->cObj->setCurrentVal($buttons[$name]);
					if (strlen($myType)>0) {
						$buttons[$this->defaultDesignator][$name] = $this->confObj->TSobj($myType,$myConf);
					}
				}
			}
			return ($buttons[$this->defaultDesignator][$name]);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	function buttonExists($name) {
		$this->_fCount(__FUNCTION__);
		return (strlen($this->conf['buttons.'][$name])>1);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	function getButtonType($name) {
		$this->_fCount(__FUNCTION__);
		return ($this->conf['buttons.'][$name]);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	function getButtonConf($name) {
		$this->_fCount(__FUNCTION__);
		return ($this->conf['buttons.'][$name.'.']);
	}

}


?>