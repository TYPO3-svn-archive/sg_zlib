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
 */



/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   63: class tx_sglib_viewdetails extends tx_sglib_viewbase
 *   66:     protected function init()
 *   74:     function getOutput()
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */


require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_viewbase.php');

class tx_sglib_viewsearchform extends tx_sglib_viewbase  {
	private $flagEmptyResultAsSubpart = '';

	protected $template;
	protected $markers;
	protected $subparts;
	protected $subpartMarkers;

	protected $searchParams;

	protected $output = NULL;


	
	protected function init() {
		$this->searchConf = $this->configObj->get('search.');

		$this->subparts = Array();
		$this->markers = $this->constObj->getMarkers();
		$this->markers = $this->markersObj->getDescriptions('',$this->markers);
		$this->subpartMarkers = Array();

		$this->references = $this->model->references;
	}


	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$params: ...
	 * @return	[type]		...
	 */
	function setSearchParams($params) {
		$this->searchParams = is_array($params) ? $params : Array();
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function renderOutput() {
		GLOBAL $TSFE;
		$this->getTemplate();

		$this->markers = $this->getDbSearchFields ($table, $this->markers);

		$this->markers['###FORM_START###']  = '<form name="txsg_search" id="txsg_search" action="index.php">'."\r\n".
			'<input type="hidden" name="'.$this->designator.'[searchformname]" value="txsg_search">'."\r\n".
			'<input type="hidden" name="id" value="'.($this->listPage ? $this->listPage : $TSFE->id).'">'."\r\n".
				(($TSFE->type>0) ? '<input type="hidden" name="type" value="'.$TSFE->type.'">'."\r\n":'').
				(t3lib_div::GPvar('L') ? '<input type="hidden" name="L" value="'.t3lib_div::GPvar('L').'">'."\r\n":'').
				(t3lib_div::GPvar('rTL') ? '<input type="hidden" name="rTL" value="1">'."\r\n":'').
				'<input type="hidden" name="'.$this->designator.'[searchmode]" value="1">'."\r\n";
		$this->markers['###FORM_END###']  = '</form>'."\r\n";

		$this->output = $this->cObj->substituteMarkerArrayCached($this->template,$this->markers,$this->subpartMarkers);
	}


	function getDbSearchFields ($table='', $markers=Array()) {
		$table = $table ? $table : $this->configObj->getTCAname();
		$fieldConf = $this->configObj->get($table.'.conf.');
		$searchConf = $this->configObj->get($table.'.search.');
		if (is_array($searchConf)) foreach ($searchConf as $key=>$searchConfKey) {
			$fieldName = substr($key,0,-1);
			// t3lib_div::debug(Array('$searchConf('.$fieldName.')'=>$searchConfKey, 'File:Line'=>__FILE__.':'.__LINE__));
			if ($fieldName=='submit') {
				$markers['###SEARCH_SUBMIT###'] = $this->getDbSearchFieldSubmit ($table,$fieldName,$fieldConf,$searchConf);
			} else if ($key=='reset') {
			} else if ($key=='clear') {
			} else if ($key=='showall') {
			} else if ($key=='listmode') {
			} else if ($key=='disabled') {
			} else if ($key=='feowner') {
			} else if ($key=='abc' && is_array($searchConf[$key])) {
			} else if (strcmp($searchConf[$key]['mode'],'usedselector')==0) {
			} else if (strcmp($searchConf[$key]['mode'],'selector')==0) {
			} else if (isset($fieldConf[$key]) || $key=='uid') {
				$markers['###SEARCH_'.strtoupper($fieldName).'###'] = $this->getDbSearchFieldDefault ($table,$fieldName,&$fieldConf,&$searchConf);
			}
		}

		return ($markers);
	}

	function getDbSearchFieldSubmit ($table,$key,&$fieldConf,&$searchConf) {
		$marker = '';

		if (intval($searchConf[$key.'.']['linkmode'])>0) {
			$marker = '<a href="#null" onclick="document.txsg_search.submit(); return false">'.
					$this->constObj->getButton('search',$this->langObj->getLLL($searchConf[$key.'.']['label'])).'</a>';
		} else if (intval($searchConf[$key.'.']['imagemode'])>0 && $this->constObj->buttonExists('search')) {
			$marker = '<input type="image" src="'.
					$this->constObj->getButton('search',$this->langObj->getLLL($searchConf[$key.'.']['label']),1).'" />';
		} else {
			$marker = '<input type="submit" value="'.$this->langObj->getLLL($searchConf[$key.'.']['label']).'" />';
		}

		return ($marker);
	}

	function getDbSearchFieldDefault ($table,$key,&$fieldConf,&$searchConf) {
		$marker = '--search-for-'.$key.'--';
		$mySearchMode = intval($searchConf[$key.'.']['type']);
		if ($mySearchMode>1) {
			$searchMode = ($mySearchMode>2) ? SGZLIB_SEARCHUSEDPLUS : SGZLIB_SEARCHUSED;
		} else {
			$searchMode = SGZLIB_SEARCHALL;
		}
		$marker = $this->getFeSingleField($table,$key,$this->searchParams,$searchMode,$fieldConf,$searchConf);
		//$marker = $this->doXajaxFieldWrap($marker,'xajax_id_'.$key);


		return ($marker);
	}


	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getOutput() {
		if (!$this->output) {
			$this->renderOutput();
		}
		return ($this->output);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	private function getTemplate() {
		$tmp = $this->cObj->fileResource($this->searchConf['template']);
		$this->template = $this->cObj->getSubpart($tmp,'###'.$this->searchConf['subpart'].'###');
	}



}

?>