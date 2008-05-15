<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2007 Stefan Geith <typo3devYYYY@geithware.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
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

require_once(t3lib_extMgm::extPath('sg_zlib').'class.txsg_cachedbase.php');
require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_viewdetails.php');
require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_viewlist.php');
require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_viewsearchform.php');

/**
 * Plugin 'Sartorius Mechatronics PDFs' for the 'sartorius_mech_pdf' extension.
 *
 * @author	Stefan Geith <typo3devYYYY@geithware.de>
 * @package	TYPO3
 * @subpackage	tx_sartoriusmechpdf
 */
class txsg_cached_base_standard extends txsg_cached_base {


	function doSearch () {
		return ('FUNCTION=doSearch <hr />');
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function doList () {
		$listConf = $this->configObj->get('list.');
		$listMode = $this->paramsObj->getListMode();
		$tmp = $this->configObj->get('listmode.'.$listMode.'.');
		if (is_array($tmp)) {
			$listConf = t3lib_div::array_merge_recursive_overrule($listConf,$tmp);
		}

		$model = $this->factoryObj->getModel('tx_sglib_modelbase', $this->prefixId, $this->conf['cached']);
		$model->readReferenceTables('*','');
		$model->showAllIfEmptySearch($listConf['showAllIfEmptySearch']);
		$model->setSearchMode($this->paramsObj->getSearchmode());
		$model->setSearchParams($this->paramsObj->getSearch());
		$model->setListMode($listMode);
		$model->setResultsPerPage($this->paramsObj->getListResultsPerPage());
		$model->setActivePage($this->paramsObj->getListActivePage());

		// $model->performSearch();
		$data = $model->getResult(); // contains performSearch, if not already called manually
		//t3lib_div::debug($data->getDebugArray(4));
		//t3lib_div::debug(Array('total='=>$model->getTotalCount(), 'count='=>count($data), 'File:Line'=>__FILE__.':'.__LINE__));

		$view = $this->factoryObj->getView('tx_sglib_viewlist', $this->prefixId, $model, $this->conf['cached']);
		$view->showAllIfNoSearch($listConf['showAllIfNoSearch']);
		$view->emptyResultAsSubpart($listConf['emptyResultAsSubpart']);
		$view->setListGroup($listConf['listGroup']);
		return ($view->getOutput());
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function doDetails () {
		$detailsConf = $this->configObj->get('details.');
		$model = $this->factoryObj->getModel('tx_sglib_modelbase', $this->prefixId, $this->conf['cached']);
		$view = $this->factoryObj->getView('tx_sglib_viewdetails', $this->prefixId, $model, $this->conf['cached']);
		// TODO: get uid-parameter
		// TODO: set uid-parameter to view;
		return ($view->getOutput());
	}



}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.txsg_cached.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.txsg_cached.php']);
}

?>