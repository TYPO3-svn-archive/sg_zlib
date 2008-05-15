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
 *   67: function __construct ($designator, $factoryObj, $model, $cached)
 *   86: function emptyResultAsSubpart($mode)
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

abstract class tx_sglib_viewbase  {
	protected $designator;
	protected $factoryObj;
	protected $configObj;
	protected $debugObj;
	protected $markersObj;
	protected $model;
	protected $cached;

	function __construct ($designator, $factoryObj, $model, $cached) {
		$this->designator = $designator;
		$this->factoryObj = $factoryObj;
		$this->configObj = $factoryObj->configObj;
		$this->debugObj = $factoryObj->debugObj;
		$this->constObj = $factoryObj->constObj;
		$this->paramsObj = $factoryObj->paramsObj;
		$this->cObj = $factoryObj->cObj;
		$this->model = $model;
		$this->cached = $cached;

		$this->markersObj = $factoryObj->markersObj;
		$this->markersObj->model = $this->model;

		$this->init();
	}

	abstract protected function init();

	function emptyResultAsSubpart($mode) {
		$this->flagEmptyResultAsSubpart = $mode;
	}

	abstract function getOutput();


}

?>