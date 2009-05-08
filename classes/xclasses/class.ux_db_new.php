<?php
/***************************************************************
*  Copyright notice
*
*  (c) 1999-2005 Kasper Skaarhoj (kasperYYYY@typo3.com)
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
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * New database item menu
 *
 * This script lets users choose a new database element to create.
 * Includes a wizard mode for visually pointing out the position of new pages
 *
 * $Id$
 * Revised for TYPO3 3.6 November/2003 by Kasper Skaarhoj
 * XHTML compliant
 *
 * @author	Kasper Skaarhoj <kasperYYYY@typo3.com>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   54: class ux_SC_db_new extends SC_db_new
 *   60:     function regularNew()
 *
 * TOTAL FUNCTIONS: 1
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

require_once('db_new.php');

class ux_SC_db_new extends SC_db_new {
	/**
 * Create a regular new element (pages and records)
 *
 * @return	void
 */
	function regularNew()	{
		global $BE_USER,$LANG,$BACK_PATH,$TCA;

		$doNotShowFullDescr = FALSE;

			// Slight spacer from header:
		$this->code.='<img'.t3lib_iconWorks::skinImg($BACK_PATH,'gfx/ol/halfline.gif','width="18" height="8"').' alt="" /><br />';

			// Initialize array for accumulating table rows:
		$tRows = array();

			// New pages INSIDE this pages
		if ($this->newPagesInto
			&& $this->isTableAllowedForThisPage($this->pageinfo, 'pages')
			&& $BE_USER->check('tables_modify','pages')
			&& $BE_USER->workspaceCreateNewRecord($this->pageinfo['_ORIG_uid']?$this->pageinfo['_ORIG_uid']:$this->id, 'pages')
			)	{

				// Create link to new page inside:
			$t = 'pages';
			$v = $TCA[$t];
			$rowContent = '<img'.t3lib_iconWorks::skinImg($BACK_PATH,'gfx/ol/join.gif','width="18" height="16"').' alt="" />'.
					$this->linkWrap(
						'<img'.t3lib_iconWorks::skinImg($BACK_PATH,'gfx/i/'.($v['ctrl']['iconfile'] ? $v['ctrl']['iconfile'] : $t.'.gif'),'width="18" height="16"').' alt="" />'.
							$LANG->sL($v['ctrl']['title'],1).' ('.$LANG->sL('LLL:EXT:lang/locallang_core.php:db_new.php.inside',1).')',
						$t,
						$this->id).'<br/>';

				// Link to page-wizard:
			$rowContent.= '<img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/ol/line.gif','width="18" height="16"').' alt="" /><img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/ol/joinbottom.gif','width="18" height="16"').' alt="" />'.
				'<a href="'.htmlspecialchars(t3lib_div::linkThisScript(array('pagesOnly'=>1))).'">'.
				'<img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/new_page.gif','width="13" height="12"').' alt="" /> '.
				htmlspecialchars($LANG->getLL('clickForWizard')).
				'</a>';
				// Half-line:
			$rowContent.= '<br /><img'.t3lib_iconWorks::skinImg('','gfx/ol/halfline.gif','width="18" height="8"').' alt="" />';

				// Compile table row:
			$tRows[]='
				<tr>
					<td nowrap="nowrap">'.$rowContent.'</td>
					<td>'.t3lib_BEfunc::cshItem($t,'',$GLOBALS['BACK_PATH'],'',$doNotShowFullDescr).'</td>
				</tr>
			';
		}



			// New tables (but not pages) INSIDE this pages
		if ($this->newContentInto)	{
			if (is_array($TCA))	{
				foreach($TCA as $t => $v)	{
					if ($t!='pages'
							&& $this->showNewRecLink($t)
							&& $this->isTableAllowedForThisPage($this->pageinfo, $t)
							&& $BE_USER->check('tables_modify',$t)
							&& (($v['ctrl']['rootLevel'] xor $this->id) || $v['ctrl']['rootLevel']==-1)
							&& $BE_USER->workspaceCreateNewRecord($this->pageinfo['_ORIG_uid']?$this->pageinfo['_ORIG_uid']:$this->id, $t)
							)	{

							// Create new link for record:
						$rowContent = '<img'.t3lib_iconWorks::skinImg($BACK_PATH,'gfx/ol/join.gif','width="18" height="16"').' alt="" />'.
								$this->linkWrap(
								t3lib_iconWorks::getIconImage($t,array(),$BACK_PATH,'').
								$LANG->sL($v['ctrl']['title'],1)
							,$t
							,$this->id);

							// If the table is 'tt_content' (from "cms" extension), create link to wizard
						if ($t=='tt_content')	{

								// If mod.web_list.newContentWiz.overrideWithExtension is set, use that extension's wizard instead:
							$overrideExt = $this->web_list_modTSconfig['properties']['newContentWiz.']['overrideWithExtension'];
							$pathToWizard = (t3lib_extMgm::isLoaded($overrideExt)) ? (t3lib_extMgm::extRelPath($overrideExt).'mod1/db_new_content_el.php') : 'sysext/cms/layout/db_new_content_el.php';

							$href = '/typo3/alt_doc.php?edit[tt_content]['.$this->id.']=new'.
								'&defVals[tt_content][colPos]=0&defVals[tt_content][sys_language_uid]=0&defVals[tt_content][CType]=list'.
								'&returnUrl='.rawurlencode(t3lib_div::getIndpEnv('REQUEST_URI'));
							$rowContent.= '<br /><img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/ol/line.gif','width="18" height="16"').' alt="" />'.
										'<img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/ol/join.gif','width="18" height="16"').' alt="" />'.
										'<a href="'.htmlspecialchars($href).'"><img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/new_record.gif','width="16" height="12"').' alt="" /> '.'General Plugin'.
										'</a>';

							$href = $pathToWizard.'?id='.$this->id.'&returnUrl='.rawurlencode(t3lib_div::getIndpEnv('REQUEST_URI'));
							$rowContent.= '<br /><img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/ol/line.gif','width="18" height="16"').' alt="" />'.
										'<img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/ol/joinbottom.gif','width="18" height="16"').' alt="" />'.
										'<a href="'.htmlspecialchars($href).'"><img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/new_record.gif','width="16" height="12"').' alt="" /> '.
										htmlspecialchars($LANG->getLL('clickForWizard')).
										'</a>';

								// Half-line added:
							$rowContent.= '<br /><img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/ol/halfline.gif','width="18" height="8"').' alt="" />';
						}


							// Compile table row:
						$tRows[] = '
				<tr>
					<td nowrap="nowrap">'.$rowContent.'</td>
					<td>'.t3lib_BEfunc::cshItem($t,'',$GLOBALS['BACK_PATH'],'',$doNotShowFullDescr).'</td>
				</tr>
			';

					}
				}
			}
		}

			// New pages AFTER this pages
		if ($this->newPagesAfter
			&& $this->isTableAllowedForThisPage($this->pidInfo,'pages')
			&& $BE_USER->check('tables_modify','pages')
			&& $BE_USER->workspaceCreateNewRecord($this->pidInfo['uid'], 'pages')
			)	{

				// Create link to new page after
			$t = 'pages';
			$v = $TCA[$t];
			$rowContent = $this->linkWrap(
					t3lib_iconWorks::getIconImage($t,array(),$BACK_PATH,'').
						$LANG->sL($v['ctrl']['title'],1).' ('.$LANG->sL('LLL:EXT:lang/locallang_core.php:db_new.php.after',1).')',
					'pages',
					-$this->id
				);

				// Compile table row:
			$tRows[] = '
				<tr>
					<td nowrap="nowrap">'.$rowContent.'</td>
					<td>'.t3lib_BEfunc::cshItem($t,'',$GLOBALS['BACK_PATH'],'',$doNotShowFullDescr).'</td>
				</tr>
			';
		} else {
				// Compile table row:
			$tRows[]='
				<tr>
					<td><img'.t3lib_iconWorks::skinImg($BACK_PATH,'gfx/ol/stopper.gif','width="18" height="16"').' alt="" /></td>
					<td></td>
				</tr>
			';
		}

			// Make table:
		$this->code.='
			<table border="0" cellpadding="0" cellspacing="0" id="typo3-newRecord">
			'.implode('',$tRows).'
			</table>
		';

			// Create a link to the new-pages wizard.
		if ($this->showNewRecLink('pages'))	{
			$this->code.='

				<!--
					Link; create new page:
				-->
				<div id="typo3-newPageLink">
					<a href="'.htmlspecialchars(t3lib_div::linkThisScript(array('pagesOnly'=>'1'))).'">'.
					'<img'.t3lib_iconWorks::skinImg($this->doc->backPath,'gfx/new_page.gif','width="13" height="12"').' alt="" />'.
					htmlspecialchars($LANG->getLL('createNewPage')).
					'</a>
				</div>
				';
		}

			// Add CSH:
		$this->code.= t3lib_BEfunc::cshItem('xMOD_csh_corebe', 'new_regular', $GLOBALS['BACK_PATH'],'<br/>');
	}


}


