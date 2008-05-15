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
 * Then we need optional information:
 * - Order-Parameters (default empty) if we should show a categorized Listing
 * - Should we show empty list or special subpart, if model is empty ?
 * - Alternating row properties (e.g. colors)
 * - Information about ListMode
 * - Information about Search-Parameters (e.g. for highlighting ABC-Entries)
 * - divide/segment paramters (default none)
 *
 * Now the output can be rendered:
 *  - respect optional alternating row properties (e.g. colors)
 *  - create markers for Listmodes (e.g. sorting)
 *  - create markers for abc-key/range
 *  - respect ###NO/owneronly###  (this part must be uncached!!)
 *  - respect divide/segment
 *  - respect order (seperate headers for each order-level)
 *
 */


/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   86: class tx_sglib_viewlist extends tx_sglib_viewbase
 *  104:     protected function init()
 *  126:     function emptyResultAsSubpart($mode)
 *  136:     function showAllIfNoSearch($mode)
 *  146:     function setListGroup($listGroup)
 *  158:     function renderOutput()
 *  179:     protected function renderList($data)
 *  199:     protected function segmentCheckPreset()
 *  224:     protected function segmentCheckFinish()
 *  246:     protected function catlistCheckPreset()
 *  262:     protected function catlistCheckBeforeLine($record)
 *  297:     protected function catlistOutputLine($record)
 *  332:     protected function catlistCheckFinish()
 *  356:     private function getTemplate()
 *
 * TOTAL FUNCTIONS: 13
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once(t3lib_extMgm::extPath('sg_zlib').'class.tx_sglib_viewbase.php');

class tx_sglib_viewlist extends tx_sglib_viewbase  {
	protected $flagEmptyResultAsSubpart = '';
	protected $listConf;
	protected $listMode;

	protected $showAllIfNoSearch;
	protected $references;
	protected $listGroup;
	protected $listGroupValues;

	protected $template;
	protected $markers;
	protected $subparts;
	protected $subpartMarkers;

	protected $output = NULL;


	protected function init() {

		$this->listConf = $this->confObj->list;
		$this->listMode = $this->paramsObj->getListMode();
		$tmp = $this->confObj->listmode[$this->listMode.'.'];
		if (is_array($tmp)) {
			$this->listConf = t3lib_div::array_merge_recursive_overrule($this->listConf,$tmp);
		}
		$this->subparts = Array();
		$this->markers = $this->constObj->getMarkers();
		$this->markers = $this->markersObj->getDescriptions('',$this->markers);
		$this->markers = $this->markersObj->getTtContent($this->listConf['tt_content.'],$this->markers);
		$this->subpartMarkers = Array();
	}


	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$mode: ...
	 * @return	[type]		...
	 */
	function emptyResultAsSubpart($mode) {
		$this->flagEmptyResultAsSubpart = $mode;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$mode: ...
	 * @return	[type]		...
	 */
	function showAllIfNoSearch($mode) {
		$this->showAllIfNoSearch = $mode;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$listGroup: ...
	 * @return	[type]		...
	 */
	function setListGroup($listGroup) {
		if (!is_array($listGroup)) {
			$listGroup = t3lib_div::trimExplode(',',$listGroup);
		}
		$this->listGroup = $listGroup;
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function renderOutput() {
		$this->getTemplate();
		$this->markers['###PAGEBROWSER###'] = '---TODO:-Page-Browser--';
		$this->subpartMarkers['###LINE###'] = $this->renderList($this->model->data);
		$this->output = $this->cObj->substituteMarkerArrayCached($this->template,$this->markers,$this->subpartMarkers);
		$removeComments=Array('<!-- ###HEADER### -->'=>'', '<!-- ###FOOTER### -->'=>'');
		if (!is_array($this->listConf['segment.'])) {
			$removeComments['<!-- ###REMOVE_ON_SEGMENT### -->'] = '';
		}
		if (!is_array($this->listConf['divide.'])) {
			$removeComments['<!-- ###REMOVE_ON_DIVIDE### -->'] = '';
		}
		$this->output = $this->cObj->substituteMarkerArrayCached($this->output,$removeComments);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$data: ...
	 * @return	[type]		...
	 */
	protected function renderList($data) {
		$content = '';

		$content .= $this->catlistCheckPreset();
		$content .= $this->segmentCheckPreset();
		foreach ($data as $record) {
			$content .= $this->catlistCheckBeforeLine($record);
			$content .= $this->catlistOutputLine($record);
		}
		$content .= $this->segmentCheckFinish();
		$content .= $this->catlistCheckFinish();

		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function segmentCheckPreset() {
		$content = '';

		if (is_array($this->listConf['segment.'])) {
			if (intval($this->listConf['segment.']['parts'])<2) {
				$this->listConf['segment.']['parts'] = 2;
			}
			$this->wrapSegList = t3lib_div::trimExplode('|',$this->listConf['segment.']['wrap']);
			$this->wrapSegFirst = isset($this->listConf['segment.']['wrapFirstPart']) ?
							t3lib_div::trimExplode('|',$this->listConf['segment.']['wrapFirstPart']): $this->wrapSegList;
			$this->wrapSegTail = isset($this->listConf['segment.']['wrapTailPart']) ?
							t3lib_div::trimExplode('|', $this->listConf['segment.']['wrapTailPart']): $this->wrapSegList;
			$this->wrapSegLast =  isset($this->listConf['segment.']['wrapLastPart']) ?
							t3lib_div::trimExplode('|',$this->listConf['segment.']['wrapLastPart']): $this->wrapSegTail;
		}
		$this->segmentCount = 0;

		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function segmentCheckFinish() {
		$content = '';

		if (is_array($this->listConf['segment.']) && $this->segmentCount>0) {
			for (;$this->segmentCount < intval($this->listConf['segment.']['parts']);$this->segmentCount++) {
				if ($this->segmentCount>=(intval($this->listConf['segment.']['parts'])-1)) {
					$content .= $this->wrapSegLast[0].$this->listConf['segment.']['fill'].$this->wrapSegLast[1]."\n";
				} else {
					$content .= $this->wrapSegTail[0].$this->listConf['segment.']['fill'].$this->wrapSegTail[1]."\n";
				}
			}
			$this->segmentCount = 0;
		}

		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function catlistCheckPreset() {
		$content = '';

		if (is_array($this->listGroup)) {
			$this->listGroupValues = Array();
		}

		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$record: ...
	 * @return	[type]		...
	 */
	protected function catlistCheckBeforeLine($record) {
		$content = '';

		if (is_array($this->listGroup)) {
			for ($i=count($this->listGroup)-1;$i>=0;$i--) {
				if (strcmp($this->listGroupValues[$i],$record[$this->listGroup[$i]])) {
					if ($this->listGroupValues[$i]) {
						$this->segmentCheckFinish();
						$tmpl = $this->subparts['footer_ref_'.$this->listGroup[$i]];
						$tmpl = $this->cObj->substituteMarkerArray($tmpl,$this->listGroupMarkers[$i]);
						$content .= $this->cObj->substituteMarkerArray($tmpl,array(),'###TEXT_|###',1);
					}
				}
			}
			for ($i=0;$i<count($this->listGroup);$i++) {
				if (strcmp($this->listGroupValues[$i],$record[$this->listGroup[$i]])) {
					$this->listGroupValues[$i] = $record[$this->listGroup[$i]];
					$tmpl = $this->subparts['header_ref_'.$this->listGroup[$i]];
					$this->listGroupMarkers[$i] = $this->markersObj->getRefValues
						($this->listGroupValues[$i],$this->confObj->references['table'][$this->listGroup[$i]],$this->markers);
					$tmpl = $this->cObj->substituteMarkerArray($tmpl,$this->listGroupMarkers[$i]);
					$content .= $this->cObj->substituteMarkerArray($tmpl,array(),'###TEXT_|###',1);
				}
			}
		}

		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$record: ...
	 * @return	[type]		...
	 */
	protected function catlistOutputLine($record) {
		$content = '';

		$tmpl = $this->cObj->substituteMarkerArray($this->subparts['line'],$this->markers,'###TEXT_|###',1);
		$lineMarkers = $this->markersObj->getRefValues($record);
		if (is_array($this->registeredFunctions['processSingleDataRow']))
			foreach ($this->registeredFunctions['processSingleDataRow'] as $theFunction) {
			// t3lib_div::debug(Array('$theFunction'=>$theFunction, 'File:Line'=>__FILE__.':'.__LINE__));
			$obj = $theFunction[0];
			$func = $theFunction[1];
			$lineMarkers = $obj->$func($record, $lineMarkers);
		}
		$output = $this->cObj->substituteMarkerArray($tmpl,$lineMarkers);
		if (is_array($this->listConf['segment.'])) {
			if (!$this->segmentCount) {
				$output = $this->wrapSegFirst[0].$output.$this->wrapSegFirst[1];
				$this->segmentCount++;
			} else if ($this->segmentCount>=(intval($this->listConf['segment.']['parts'])-1)) {
				$output = $this->wrapSegLast[0].$output.$this->wrapSegLast[1];
				$this->segmentCount = 0;
			} else {
				$output = $this->wrapSegTail[0].$output.$this->wrapSegTail[1];
				$this->segmentCount++;
			}
		}
		$content .= $output."\n";

		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	protected function catlistCheckFinish() {
		$content = '';

		if (is_array($this->listGroup)) {
			for ($i=count($this->listGroup)-1;$i>=0;$i--) {
				if (strcmp($this->listGroupValues[$i],$record[$this->listGroup[$i]])) {
					if ($this->listGroupValues[$i]) {
						$tmpl = $this->subparts['footer_ref_'.$this->listGroup[$i]];
						$tmpl = $this->cObj->substituteMarkerArray($tmpl,$this->listGroupMarkers[$i]);
						$content .= $this->cObj->substituteMarkerArray($tmpl,array(),'###TEXT_|###',1);
						$this->listGroupValues[$i] = NULL;
					}
				}
			}
		}

		return ($content);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	private function getTemplate() {
		$this->getTemplateSubpart($this->listConf['template'],  $this->listConf['subpart']);

		if (is_array($this->listConf['segment.'])) {
			$this->template = $this->cObj->substituteSubpart($this->template,'###REMOVE_ON_SEGMENT###','',1);
		}
		if (is_array($this->listConf['divide.'])) {
			$this->template = $this->cObj->substituteSubpart($this->template,'###REMOVE_ON_DIVIDE###','',1);
		}

		$this->subparts['header'] = $this->cObj->getSubpart($this->template,'###HEADER###');
		$this->subparts['footer'] = $this->cObj->getSubpart($this->template,'###FOOTER###');
		$this->subparts['line'] = $this->cObj->getSubpart($this->template,'###LINE###');
		$this->subpartMarkers['###LINE###'] = '';

		if (is_array($this->confObj->references['field'])) foreach ($this->confObj->references['field'] as $fieldName) {
			$name = 'header_ref_'.$fieldName;
			$tmp = $this->cObj->getSubpart($this->template,'###'.strtoupper($name).'###');
			if ($tmp) {
				$this->subparts[$name] = $tmp;
				$this->subpartMarkers['###'.strtoupper($name).'###'] = '';
			}
			$name = 'footer_ref_'.$fieldName;
			$tmp = $this->cObj->getSubpart($this->template,'###'.strtoupper($name).'###');
			if ($tmp) {
				$this->subparts[$name] = $tmp;
				$this->subpartMarkers['###'.strtoupper($name).'###'] = '';
			}
		}
		// t3lib_div::debug(Array('$this->subparts'=>$this->subparts, 'File:Line'=>__FILE__.':'.__LINE__));
	}


}

?>