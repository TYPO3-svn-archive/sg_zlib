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
 *   43: class tx_sglib_exception extends Exception
 *   46:     function __construct ($strMessage, $code, $description='')
 *   56:     function getDescription()
 *   65:     function __toString()
 *
 *
 *   84: class tx_sglib_viewexception extends tx_sglib_exception
 *   87:     function getDescription()
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_exception extends Exception {
	protected $exceptionType = 'Common';

	function __construct ($strMessage, $code, $description='') {
		$this->description = $description;
        parent::__construct($strMessage, $code);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getDescription() {
		return ('');
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function __toString() {
		$content = '<hr /><b>ERROR '.$this->exceptionType.'-'.$this->getCode().': "'.$this->getMessage().'"</b> in '.
			substr(strrchr($this->getFile(), '/'),1).' Line '.$this->getLine().'<br />';
		//$content .= 'File = '.$this->getFile().' / Line = '.$this->getLine().'<br />';
		$message = $this->getDescription();
		// $content .= ($message || $this->description) ? '<b>Description:</b><br />' : '';
		$content .= $message;
		$content .= ( $message && $this->description ) ? '<br />' : '';
		$content .= $this->description;
		$content .= '<hr />';

		return ($content);
	}
}

	/**
	 * [Describe function...]
	 *
	 */
class tx_sglib_viewexception extends tx_sglib_exception {
	protected $exceptionType = 'View';

	function getDescription() {
		$description = '';
		$code = ($this->getCode() & 255);
		switch ($code) {
			case 1:
				$description = 'Set one of the following lines in your TypoScript:<br />'.
					'<kbd>list.template=<br />listmode.name.template=</kbd>';
			break;
		}
		return ($description);
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sglib_exceptions.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sglib_exceptions.php']);
}
?>