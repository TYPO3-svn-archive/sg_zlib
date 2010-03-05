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
 *   46: class tx_sglib_links
 *   75:     private function init(tx_sglib_factory $factoryObj)
 *   94:     private function _fCount ($name=NULL)
 *  117:     function __destruct()
 *  126:     function getPluginlinks()
 *  135:     function getSearchmode()
 *  144:     function getSearch()
 *  153:     function getUid()
 *  162:     function getListMode()
 *  182:     function getListResultsPerPage()
 *  196:     function getListActivePage()
 *
 * TOTAL FUNCTIONS: 10
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_links {
	private static $instance = NULL;
	protected $factoryObj = NULL;

	var $tagAttributes = array();       // setting attributes for the tag in general
	var $classString = '';              // tags class attribute
	var $idString = '';                 // tags id attribute
	var $cObject;                       // instance of tslib_cObj
	var $destination = '';              // page id, alias, external link, etc.
	var $labelString = '';              // tags label
	var $labelHasAlreadyHtmlSpecialChars = false; // is the label already HSC?
	var $noCacheBoolean = false;        // don't make a cHash
	var $noHashBoolean = false;         // add a no_cache=1 parameter
	var $overruledParameters = array(); // parameters overruled by $parameters
	var $parameters = array();		    // parameters of the link
	var $globalParameters = array();    // global parameters of the link (not prefixed)
	var $designatorString = '';         // parameter array name (prefixId) as controller namespace
	var $anchorString = '';             // section anchor as url target
	var $targetString = '';             // tags target attribute
	var $externalTargetString = '_blank'; // external target defaults to new window
	var $titleString = '';              // tags title attribute
	var $titleHasAlreadyHtmlSpecialChars = false; //is title attribute already HSC?

	protected function __construct() {}

	private function __clone() {}

	/**
	 * Returns a singlton instance of tx_sglib_links
	 *
	 * @param	string				Designator
	 * @param	tx_sglib_factory	FactoryObj
	 * @return	tx_sglib_links	Instantiated Object
	 */
	
	public static function getInstance($designator='default', $factoryObj=NULL) {
		if (!isset(self::$instance)) {
			self::$instance = new tx_sglib_links();
			self::$instance->factoryObj = $factoryObj;
			self::$instance->cObject = t3lib_div::makeInstance('tslib_cObj');
		}
		self::$instance->init($designator);
		return (self::$instance);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$designator
	 * @return	[type]		...
	 */
	public function init($designator='', $destination='') {
		$this->designatorString = $designator;
		$this->tagAttributes = array();
		$this->classString = '';
		$this->idString = '';
		$this->destination = $destination ? $destination : $GLOBALS['TSFE']->id;
		$this->labelString = '';
		$this->labelHasAlreadyHtmlSpecialChars = false;
		$this->noCacheBoolean = false;
		$this->noHashBoolean = false;
		$this->overruledParameters = array();
		$this->parameters = array();
		$this->globalParameters = array();
		$this->anchorString = '';
		$this->targetString = '';
		$this->externalTargetString = '_blank';
		$this->titleString = '';
		$this->titleHasAlreadyHtmlSpecialChars = false;

		return ($this);
	}


	// -------------------------------------------------------------------------------------
	// Setters
	// -------------------------------------------------------------------------------------

	/**
	 * Set the section anchor of the url
	 *
	 * Anchor of page as url target.
	 *
	 * @param	string		the anchor
	 * @return	object		self
	 */
	function anchor($anchorString) {
		$this->anchorString = $anchorString;
		return $this;
	}

	/**
	 * Set the designator (parameter array name) as controler namespace
	 *
	 * Put the parameters into this array.
	 * <samp>Example: &tx_example[parameterName]=parameterValue</samp>
	 * tx_example is the designator, parameterName is the key,
	 * pararmeterValue is the value of one array element.
	 *
	 * @param	string		parameter array name
	 * @return	object		self
	 */
	function designator($designatorString) {
		$this->designatorString = $designatorString;
		return $this;
	}

	/**
	 * Set the id attribute of the tag
	 *
	 * @param	string		id attribute
	 * @return	object		self
	 */
	function idAttribute($idString) {
		$this->idString = $idString;
		return $this;
	}

	/**
	 * Set the class attribute of the tag
	 *
	 * @param	string		class name
	 * @return	object		self
	 */
	function classAttribute($classString) {
		$this->classString = $classString;
		return $this;
	}

	/**
	 * Set the links destination
	 *
	 * @param	mixed		pageId, page alias, external url, etc.
	 * @param	boolean		if true don't parse through htmlspecialchars()
	 * @return	object		self
	 * @see		TSref => typolink => parameter
	 * @see		tslib_cObj::typoLink()
	 */
	function destination($destination) {
		$this->destination = $destination;
		return $this;
	}

	/**
	 * Add no_cache=1 and disable the cHash parameter
	 *
	 * @param	boolean		if true don't make a cHash, set no_cache=1
	 * @return	object		self
	 */
	function noCache() {
		$this->noCacheBoolean = true;
		return $this;
	}

	/**
	 * Disable the cHash parameter
	 *
	 * @param	boolean		if true don't make a cHash
	 * @return	object		self
	 */
	function noHash() {
		$this->noHashBoolean = true;
		return $this;
	}

	/**
	 * Set the links label
	 *
	 * By default the label will be parsed through htmlspecialchars().
	 *
	 * @param	string		the label
	 * @param	boolean		if true don't parse through htmlspecialchars()
	 * @return	object		self
	 */
	function label($labelString, $hasAlreadyHtmlSpecialChars = false) {
		$this->labelString = $labelString;
		$this->labelHasAlreadyHtmlSpecialChars = $hasAlreadyHtmlSpecialChars;
		return $this;
	}

	/**
	 * Set array of parameters to be overruled by parameters
	 *
	 * The parameters will create a common array with the name $this->designatorString.
	 * <samp>Example: &tx_example[parameterName]=parameterValue</samp>
	 * tx_example is the designator, parameterName is the key,
	 * pararmeterValue is the value of one array element.
	 *
	 * Usually you set the incomming piVars here you wan't to forward.
	 * Like in tslib_pibase::pi_linkTP_keepPIvars the element DATA is unset during processing.
	 *
	 * @param	mixed		parameters
	 * @return	object		self
	 */
	function overruled($overruledParameters = array()) {
		if(is_object($overruledParameters)) {
			$overruledParameters = $overruledParameters->getArrayCopy();
		}
		$this->overruledParameters = $overruledParameters;
		return $this;
	}

	/**
	 * Set array of new parameters to add to the link url
	 *
	 * The parameters will create a common array with the name $this->designatorString.
	 * <samp>Example: &tx_example[parameterName]=parameterValue</samp>
	 * tx_example is the designator, parameterName is the key,
	 * pararmeterValue is the value of one array element.
	 *
	 * This parameters overrule parameters in $this->baseParameters.
	 *
	 * @param	mixed		parameters
	 * @return	object		self
	 */
	function parameters($parameters = array()) {
		if(is_object($parameters)) {
			$parameters = $parameters->getArrayCopy();
		}
		$this->parameters = $parameters;
		return $this;
	}

	/**
	 * Set array of new parameters to add to the link url
	 *
	 * The parameters will create a common array with the name $this->designatorString.
	 * <samp>Example: &tx_example[parameterName]=parameterValue</samp>
	 * tx_example is the designator, parameterName is the key,
	 * pararmeterValue is the value of one array element.
	 *
	 * This parameters overrule parameters in $this->baseParameters.
	 *
	 * @param	mixed		parameters
	 * @return	object		self
	 */
	function globalParameters($globalParameters = array()) {
		if(is_object($globalParameters)) {
			$globalParameters = $globalParameters->getArrayCopy();
		}
		$this->globalParameters = $globalParameters;
		return $this;
	}

	/**
	 * Set the attributes of the tag
	 *
	 * This is a general approach to set tag attributes by an array hash.
	 *
	 * @see	classAttribute()
	 * @see	titleAttribute()
	 * @see	targetAttribute()
	 *
	 * @param	array		key value pairs
	 * @return	object		self
	 */
	function attributes($tagAttributes = array()) {
		$this->tagAttributes = $tagAttributes;
		return $this;
	}

	/**
	 * Set target attribute of the tag
	 *
	 * @param	string		target attribute
	 * @return	object		self
	 */
	function target($targetString) {
		$this->targetString = $targetString;
		return $this;
	}

	/**
	 * Set external target attribute of the tag
	 * Defaults to _blank
	 *
	 * @param	string		external target attribute
	 * @return	object		self
	 */
	function externalTargetAttribute($targetString) {
		$this->externalTargetString = $targetString;
		return $this;
	}


	/**
	 * Set title attribute of the tag
	 *
	 * @param	string		title attribute
	 * @param	boolean		if true don't apply htmlspecialchars() again
	 * @return	object		self
	 */
	function title($titleString, $hasAlreadyHtmlSpecialChars = false) {
		$this->titleString = $titleString;
		$this->titleHasAlreadyHtmlSpecialChars = $hasAlreadyHtmlSpecialChars;
		return $this;
	}

	// -------------------------------------------------------------------------------------
	// Getters
	// -------------------------------------------------------------------------------------

	/**
	 * Return the link as tag
	 *
	 * @return	string		the link tag
	 */
	function makeTag() {
		return $this->cObject->typolink(
			$this->_makeLabel(),
			$this->_makeConfig('tag')
		);
	}

	/**
	 * Return the link as url
	 *
	 * @param	boolean		set to true to run htmlspecialchars() on generated url
	 * @return	string		the link url
	 */
	function makeUrl($applyHtmlspecialchars = TRUE) {
		$url = $this->cObject->typolink(null, $this->_makeConfig('url'));
		return $applyHtmlspecialchars ? htmlspecialchars($url) : $url;
	}

	/**
	 * Redirect the page to the url
	 *
	 * @return	void
	 */
	function redirect() {
		session_write_close();
		header('Location: ' . t3lib_div::getIndpEnv('TYPO3_REQUEST_DIR').$this->cObject->typolink(null, $this->_makeConfig('url')));
		exit();
	}

	// -------------------------------------------------------------------------------------
	// Private functions
	// -------------------------------------------------------------------------------------

	/**
	 * Make the full configuration for the typolink function
	 *
	 * @param	string		$type: tag oder url
	 * @return	array		the configuration
	 * @access	private
	 */
	function _makeConfig($type) {
		$conf = Array();
		$this->parameters = is_array($this->parameters) ?
			$this->parameters : array();
		$this->overruledParameters = is_array($this->overruledParameters) ?
			$this->overruledParameters : array();
		unset($this->overruledParameters['DATA']);
		$parameters
			= t3lib_div::array_merge_recursive_overrule($this->overruledParameters,
					$this->parameters);
		foreach((array) $this->globalParameters as $key => $value) {
			if(!is_array($value)) {   // TODO handle arrays
				$conf['additionalParams'] .= '&' . rawurlencode($key) . '=' . rawurlencode($value);
			}
		}
		foreach((array) $parameters as $key => $value) {
			if(!is_array($value)) {   // TODO handle arrays
				if($this->designatorString) {
					$conf['additionalParams']
						.= '&' . rawurlencode( $this->designatorString . '[' . $key . ']') . '=' . rawurlencode($value);
				} else {
					$conf['additionalParams'] .= '&' . rawurlencode($key) . '=' . rawurlencode($value);
				}
			}
		}
		if($this->noHashBoolean ) {
			$conf['useCacheHash'] = 0;
		} else {
			$conf['useCacheHash'] = 1;
		}
		if($this->noCacheBoolean) {
			$conf['no_cache'] = 1;
			$conf['useCacheHash'] = 0;
		} else {
			$conf['no_cache'] = 0;
		}
		if($this->destination !== '')
			$conf['parameter'] = $this->destination;
		if($type == 'url') {
			$conf['returnLast'] = 'url';
		}
		if($this->anchorString) {
			$conf['section'] = $this->anchorString;
		}
		if($this->targetString) {
			$conf['target'] = $this->targetString;
		}
		if($this->externalTargetString) {
			$conf['extTarget'] = $this->externalTargetString;
		}
		if($this->classString) {
			$conf['ATagParams'] .= 'class="' . $this->classString . '" ';
		}
		if($this->idString) {
			$conf['ATagParams'] .= 'id="' . $this->idString . '" ';
		}
		if($this->titleString) {
			$title = ($this->titleHasAlreadyHtmlSpecialChars) ? $this->titleString
				: htmlspecialchars($this->titleString);
			$conf['ATagParams'] .= 'title="' . $title . '" ';
		}
		if(is_array($this->tagAttributes)
				&& (count($this->tagAttributes) > 0)) {
			foreach($this->tagAttributes as $key => $value) {
				$conf['ATagParams'] .= ' ' .  $key . '="' . htmlspecialchars($value) . '" ';
			}
		}
		return $conf;
	}

	/**
	 * Make the label for the link
	 *
	 * @return	string		the label
	 * @access	private
	 */
	function _makeLabel() {
		return ($this->labelHasAlreadyHtmlSpecialChars) ? $this->labelString : htmlspecialchars($this->labelString);
	}



	// -------------------------------------------------------------------------------------
	// pibase replace functions
	// -------------------------------------------------------------------------------------

	/**
	 * Make the full configuration for the typolink function
	 *
	 * @param	string		$type: tag oder url
	 * @return	array		the configuration
	 * @access	private
	 */

	/***********************************************************************************************
	 *
	 * Magic Methods
	 *
	 ***********************************************************************************************/

	public function __call ($name, array $arguments=Array()) {
		t3lib_div::debug(Array('ERROR'=>'Function "$name" not implemented', 'Class'=>get_class($this), 'File:Line'=>__FILE__.':'.__LINE__));
		return ('ERROR: method "'.get_class($this).'->'.$name.'(...)" does not exist. ');
	}



}


?>