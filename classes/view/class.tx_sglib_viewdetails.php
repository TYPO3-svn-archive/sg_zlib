<?php
/**
 *
 * PHP versions 5
 *
 *  (c) 2007-2008 Stefan Geith (typo3devYYYY@geithware.de)
 *
 * LICENSE:
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 * *
 * @package    TYPO3
 * @subpackage sg_lib
 * @author     Stefan Geith (typo3devYYYY@geithware.de)
 * @copyright  2008 Stefan Geith
 * @license    http://www.opensource.org/licenses/lgpl-license.php LGPL
 *
 * -----------------------------------------------------------------------
 *
 * On Creation of this Object, we need the following Data:
 * - Information, if parent is cached (=1) or not
 * - plugin.tx_myplugin.tx_mytable - Information
 *
 * Then we need this mandatory Information:
 * - The Template(s)
 * - The Model
 *
 * Now the output can be rendered:
 *  - respect ###NO/owneronly###  (this part must be uncached!!)
 *
 */



/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   65: class tx_sglib_viewdetails extends tx_sglib_viewbase
 *   68:     protected function init()
 *   82:     function renderOutput()
 *   99:     protected function getTemplate()
 *  110:     protected function renderRecord($record,$markers=Array())
 *
 * TOTAL FUNCTIONS: 4
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(t3lib_extMgm::extPath('sg_zlib').'classes/view/class.tx_sglib_viewbase.php');

class tx_sglib_viewdetails extends tx_sglib_viewbase  {
	private $flagEmptyResultAsSubpart = '';

	protected function init() {
		$this->dConf = $this->confObj->details;
		$this->subparts = Array();
		$this->markers = $this->constObj->getMarkers();
		$this->markers = $this->markersObj->getDescriptions('',$this->markers);
		$this->markers = $this->markersObj->getTtContent($this->listConf['tt_content.'],$this->markers);
		$this->subpartMarkers = Array();
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function renderOutput() {
		$this->getTemplate();
		$record = $this->model->getSingleRecord();
		if ($record) {
			$this->markers = $this->renderRecord($record,$this->markers);
			$this->output = $this->cObj->substituteMarkerArrayCached($this->template,$this->markers,$this->subpartMarkers);
		} else {
			// Error Output!
			$this->output = '--NO RECORD FOUND!--';
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function getTemplate() {
		$this->getTemplateSubpart($this->dConf['template'],  $this->dConf['subpart']);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$record: ...
	 * @param	[type]		$markers: ...
	 * @return	[type]		...
	 */
	protected function renderRecord($record,$markers=Array()) {
		if (is_array($this->registeredFunctions['processSingleDataRow']))
			foreach ($this->registeredFunctions['processSingleDataRow'] as $theFunction) {
			//t3lib_div::debug(Array('$theFunction'=>$theFunction, 'File:Line'=>__FILE__.':'.__LINE__));
			$obj = $theFunction[0];
			$func = $theFunction[1];
			$markers = $obj->$func($record, $markers);
		}

		$markers = $this->markersObj->getDescriptions('',$markers);
		$markers = $this->getRow($record,$markers);

		return ($markers);
	}

}

?>