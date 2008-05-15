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
 *   63: class tx_sglib_permit
 *   95:     private function init(tx_sglib_factory $factoryObj)
 *  135:     private function _fCount ($name=NULL)
 *  158:     function __destruct()
 *
 *              SECTION: User Functions
 *  171:     function getActiveFeUser()
 *  192:     function getActiveFeGroups()
 *  209:     function getActiveBeUser()
 *  230:     function getActiveBeGroups()
 *  249:     function getFeUid()
 *  259:     function getBeUid()
 *  270:     function getFeUser($name=NULL)
 *  285:     function getBeUser($name=NULL)
 *  299:     function getFeGroups()
 *  309:     function getBeGroups()
 *  321:     function checkFeUserIn ($chkUserList,$chkGroupList)
 *  368:     function checkBeUserIn ($chkUserList,$chkGroupList)
 *
 *              SECTION: FE-Editing permissons
 *  419:     function allowed($property)
 *  428:     function useEditMode()
 *  437:     function getEditPropertiesAsText ()
 *  447:     function processTemplate($template)
 *
 *              SECTION: Private functions
 *  506:     function _getEditMode ()
 *  814:     private function _getListOfEditors($myAllow)
 *
 * TOTAL FUNCTIONS: 21
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_sglib_permit {
	private static $instance = Array();

	private $factoryObj = NULL;
	private $confObj;
	private $debugObj;
	private $constObj;
	private $langObj;
	private $cObj;
	private $conf=Array();
	private $defaultDesignator;

	private $allow;

	protected function __construct() {}

	private function __clone() {}

	public static function getInstance($designator, tx_sglib_factory $factoryObj) {
		if (!isset(self::$instance[$designator])) {
			self::$instance[$designator] = new tx_sglib_permit();
			self::$instance[$designator]->init($factoryObj);
		}
		return (self::$instance[$designator]);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$tx_sglib_config $confObj, tx_sglib_debug $debugObj, tx_sglib_const $constObj, tx_sglib_lang $langObj: ...
	 * @return	[type]		...
	 */
	private function init(tx_sglib_factory $factoryObj) {
		$this->_fCount(__FUNCTION__);
		$this->factoryObj = $factoryObj;
		$this->confObj = $factoryObj->confObj;
		$this->defaultDesignator = $this->confObj->getDesignator();
		$this->debugObj = $factoryObj->debugObj;
		$this->constObj = $factoryObj->constObj;
		$this->langObj = $factoryObj->langObj;
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');

		$this->conf = $this->confObj->permit;
		if (!is_array($this->conf)) {
		$this->conf = Array(
			'edit'=>$this->confObj->edit,
			'allow.'=>$this->confObj->allow,
			'beEdit'=>$this->confObj->beEdit,
			'beAllow.'=>$this->confObj->beAllow,
			'groupSubParts.'=>$this->confObj->groupSubParts,
			'beAdminIsNoFeAdmin'=>$this->confObj->beAdminIsNoFeAdmin
			);
		}
		$this->debugObj->debugIf('permitConf',Array('$this->conf'=>$this->conf));

		$this->getActiveFeUser();
		$this->getActiveFeGroups();
		$this->getActiveBeUser();
		$this->getActiveBeGroups();

		$this->_getEditMode();

		$this->debugObj->debugIf('permitConf',Array('feUid'=>$this->feUid, 'feUser'=>$this->feUser, 'feGroups'=>$this->feGroups));
		$this->debugObj->debugIf('permitConf',Array('beUid'=>$this->beUid, 'beUser'=>$this->beUser, 'beGroups'=>$this->beGroups));
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
	 * ********************************************************************************************
	 *
	 * User Functions
	 *
	 * *********************************************************************************************/
	/**
	 * @return	[type]		...
	 */
	function getActiveFeUser() {
		static $feUser = NULL;
		$this->_fCount(__FUNCTION__);
		if (!isset($feUser)) {
			$feUser = Array();
			if (is_object($GLOBALS['TSFE']->fe_user)) {
				if (is_array($GLOBALS['TSFE']->fe_user->user)) {
					$feUser = $GLOBALS['TSFE']->fe_user->user;
				}
			}
		}
		$this->feUser = $feUser;
		$this->feUid = intval($feUser['uid']);
		return ($feUser);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getActiveFeGroups() {
		static $feGroups = NULL;
		$this->_fCount(__FUNCTION__);
		if (!isset($feGroups)) {
			if (is_array($GLOBALS['TSFE']->fe_user->groupData['uid'])) {
				$feGroups = $GLOBALS['TSFE']->fe_user->groupData['uid'];
			}
		}
		$this->feGroups = $feGroups;
		return ($feGroups);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getActiveBeUser() {
		static $beUser = NULL;
		$this->_fCount(__FUNCTION__);
		if (!isset($beUser)) {
			$beUser = Array();
			if ($GLOBALS['TSFE']->beUserLogin && is_object($GLOBALS['BE_USER'])) {
				if (is_array($GLOBALS['BE_USER']->user)) {
					$beUser = $GLOBALS['BE_USER']->user;
				}
			}
		}
		$this->beUser = $beUser;
		$this->beUid = intval($beUser['uid']);
		return ($beUser);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getActiveBeGroups() {
		static $beGroups = NULL;
		$this->_fCount(__FUNCTION__);
		if (!isset($beGroups)) {
			if (is_array($GLOBALS['BE_USER']->userGroupsUID)) {
				for ($i=0;$i<count($GLOBALS['BE_USER']->userGroupsUID);$i++) {
					$beGroups[$GLOBALS['BE_USER']->userGroupsUID[$i]] = $GLOBALS['BE_USER']->userGroupsUID[$i];
				}
			}
		}
		$this->beGroups = $beGroups;
		return ($beGroups);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getFeUid() {
		$this->_fCount(__FUNCTION__);
		return($this->feUid);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getBeUid() {
		$this->_fCount(__FUNCTION__);
		return($this->beUid);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	function getFeUser($name=NULL) {
		$this->_fCount(__FUNCTION__);
		if ($name) {
			return($this->feUser[$name]);
		} else {
			return($this->feUser);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$name: ...
	 * @return	[type]		...
	 */
	function getBeUser($name=NULL) {
		$this->_fCount(__FUNCTION__);
		if ($name) {
			return($this->beUser[$name]);
		} else {
			return($this->beUser);
		}
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getFeGroups() {
		$this->_fCount(__FUNCTION__);
		return($this->feGroups);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getBeGroups() {
		$this->_fCount(__FUNCTION__);
		return($this->beGroups);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$chkUserList: ...
	 * @param	[type]		$chkGroupList: ...
	 * @return	[type]		...
	 */
	function checkFeUserIn ($chkUserList,$chkGroupList) {
		$this->_fCount(__FUNCTION__);
		$retcode = FALSE;

		// Check only if ANY user is logged in
		if (count($this->feUser)>0) {
			$chkUser = explode (',',$chkUserList);
			for ($i=0; $i<count($chkUser); $i++) {
				if (intval($chkUser[$i])>0) {
					if (intval($this->feUser['uid'])==intval($chkUser[$i])) { $retcode = TRUE; }
				} else if (strcmp($chkUser[$i],'0')!=0) {
					if ( strcmp($this->feUser['username'],trim($chkUser[$i]))==0) { $retcode = TRUE; }
				}
			}
			$chkGroup = explode (',',$chkGroupList);
			for ($i=0; $i<count($chkGroup); $i++) {
				if (intval($chkGroup[$i])>0) {
					//if (t3lib_div::inList($this->feUser['user  group'],trim($chkGroup[$i]))) { $retcode = TRUE; }
					if ( $this->feGroups[intval($chkGroup[$i])]>0  ) { $retcode = TRUE; }
				} else if (strcmp(trim($chkGroup[$i]),'0')!=0) {
					// check for groupname
					if (  !isset($this->idOfFeGroupName[ trim($chkGroup[$i]) ])  ) {
						// GroupID zum zug. Namen suchen und cachen !
						$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,title','fe_groups',
							"deleted=0 AND title='".trim($chkGroup[$i])."'");
						if ($res && $GLOBALS['TYPO3_DB']->sql_num_rows($res)>0) {
							$myRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
							$this->idOfFeGroupName[ trim($chkGroup[$i]) ] = $myRow['uid'];
						}
					}
					$g = $this->idOfFeGroupName[ trim($chkGroup[$i]) ];
					//if ($g>0 && t3lib_div::inList($this->feUser['user  group'],$g) ) { $retcode = TRUE; }
					if ($g>0 && $this->feGroups[intval($g)]>0 ) { $retcode = TRUE; }
				}
			}
		}

		return $retcode;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$chkUserList: ...
	 * @param	[type]		$chkGroupList: ...
	 * @return	[type]		...
	 */
	function checkBeUserIn ($chkUserList,$chkGroupList) {
		$this->_fCount(__FUNCTION__);
		$retcode = FALSE;

		// Check only if ANY user is logged in
		if (count($this->beUser)>0) {
			$chkUser = explode (',',$chkUserList);
			for ($i=0; $i<count($chkUser); $i++) {
				if (intval($chkUser[$i])>0) {
					if (intval($this->beUser['uid'])==intval($chkUser[$i])) { $retcode = TRUE; }
				} else if (strcmp($chkUser[$i],'0')!=0) {
					if ( strcmp($this->beUser['username'],trim($chkUser[$i]))==0) { $retcode = TRUE; }
				}
			}

			$chkGroup = explode (',',$chkGroupList);
			for ($i=0; $i<count($chkGroup); $i++) {
				if (intval($chkGroup[$i])>0) {
					//if (t3lib_div::inList($this->beUser['user  group'],trim($chkGroup[$i]))) { $retcode = TRUE; }
					if ( $this->beGrouplist[intval($chkGroup[$i])]>0  ) { $retcode = TRUE; }
				} else if (strcmp(trim($chkGroup[$i]),'0')!=0) {
					// check for groupname
					if (  !isset($this->idOfBeGroupName[ trim($chkGroup[$i]) ])  ) {
						// GroupID zum zug. Namen suchen und cachen !
						$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,title','be_groups',"title='".trim($chkGroup[$i])."'");
						if ($res && $GLOBALS['TYPO3_DB']->sql_num_rows($res)>0) {
							$myRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
							$this->idOfBeGroupName[ trim($chkGroup[$i]) ] = $myRow['uid'];
						}
					}
					$g = $this->idOfBeGroupName[ trim($chkGroup[$i]) ];
					//if ($g>0 && t3lib_div::inList($this->beUser['user  group'],$g) ) { $retcode = TRUE; }
					if ($g>0 && $this->beGrouplist[intval($g)]>0 ) { $retcode = TRUE; }
				}
			}
		}

		return $retcode;
	}


	/**
	 * ********************************************************************************************
	 *
	 * FE-Editing permissons
	 *
	 * *********************************************************************************************/
	/**
	 * @param	[type]		$property: ...
	 * @return	[type]		...
	 */
	function allowed($property) {
		return $this->allow[$property];
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function useEditMode() {
		return $this->useEditTemplates;
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function getEditPropertiesAsText () {
		return $this->txtUserProps;
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$template: ...
	 * @return	[type]		...
	 */
	function processTemplate($template) {
		$this->_fCount(__FUNCTION__);

		if ($this->allow['admin']) {
			$template = $this->cObj->substituteSubpart ($template,'###NONADMINONLY###','');
			$template = str_replace('<!-- ###ADMINONLY### -->','',$template);
		} else {
			$template = $this->cObj->substituteSubpart ($template,'###ADMINONLY###','');
			$template = str_replace('<!-- ###NONADMINONLY### -->','',$template);
		}

		if ($this->useEditTemplates) {
			$template = $this->cObj->substituteSubpart ($template,'###VIEWERONLY###','');
			$template = str_replace('<!-- ###EDITORONLY### -->','',$template);
		} else {
			$template = $this->cObj->substituteSubpart ($template,'###EDITORONLY###','');
			$template = str_replace('<!-- ###VIEWERONLY### -->','',$template);
		}

		$gConf = $this->conf['groupSubParts.'];
		if (count($gConf)) {
			for (reset($gConf);$key=key($gConf);next($gConf)) {
				$inGroup = FALSE;
				if ($gConf[$key]['group']) {
					$inGroup = $this->checkFeUserIn ('',$gConf[$key]['group']);
				} else if ($gConf[$key]['groupBe']) {
					$inGroup = $this->checkBeUserIn ('',$gConf[$key]['groupBe']);
				}
				if (strlen($gConf[$key]['allowMarker'])>7) {
					if (!$inGroup) {
						$template = $this->cObj->substituteSubpart ($template,$gConf[$key]['allowMarker'],'');
					} else {
						$template = str_replace('<!-- '.$gConf[$key]['allowMarker'].' -->','',$template);
					}
				}
				if (strlen($gConf[$key]['denyMarker'])>7) {
					if ($inGroup) {
						$template = $this->cObj->substituteSubpart ($template,$gConf[$key]['denyMarker'],'');
					} else {
						$template = str_replace('<!-- '.$gConf[$key]['denyMarker'].' -->','',$template);
					}
				}

			}
		}

		return ($template);
	}

	/***********************************************************************************************
	 *
	 * Private functions
	 *
	 **********************************************************************************************/

	/**
	 * @param	[type]		$myConf: ...
	 * @return	[type]		...
	 */
	function _getEditMode () {
		$this->_fCount(__FUNCTION__);
		$editMode = 0;
		$myType = $GLOBALS['TSFE']->type;

		if ($myType<90) {
			$myEdit = intval($this->conf['edit']);
			$myAllow = $this->conf['allow.'];
			$myBeEdit = intval($this->conf['beEdit']);
			$myBeAllow = $this->conf['beAllow.'];
			//t3lib_div::debug (Array('Edit='=>$myEdit, 'Allow='=>$myAllow,'BeEdit='=>$myBeEdit, 'BeAllow='=>$myBeAllow));

			if (!$myBeEdit) {
				unset ($this->beUid);
			}
			$this->editProps = '';
			$this->allow = Array();

			$this->allow['showOnlyOwnEntries'] = intval($myAllow['showOnlyOwnEntries']) ? TRUE : FALSE;
			$this->allow['showOnlyOwnAndPublicEntries'] = intval($myAllow['showOnlyOwnAndPublicEntries']) ? TRUE : FALSE;
			$this->allow['showOnlyOwnDetails'] = intval($myAllow['showOnlyOwnDetails']) ? TRUE : FALSE;

			$this->beEditor = 0;

			// EditMode only available, if any user is logged in !!
			if (count($this->beUser)>0) {
				// check, if Admin
				$isBeAdmin = $this->checkBeUserIn($myBeAllow['admin.']['user'],$myBeAllow['admin.']['group'])
					|| ( intval($this->beUser['admin'])>0 && intval($this->conf['beAdminIsNoFeAdmin'])<1  );

				if ($myBeEdit==2 || ($isBeAdmin && $myBeEdit==1) ) {
					// EditMode = 2 ==> Any User is Admin; Access controled by Typo3-Access per Page/Content
					// EditMode = 1 && isAdmin ==> AdminUser for this plugin is set
					$editMode = $myBeEdit;
					$this->allow['admin'] = TRUE;
					$this->allow['seeAllHidden'] = TRUE;
					$this->allow['addEntry'] = TRUE;
					$this->allow['uploadImage'] = TRUE;
					$this->allow['unhideAll'] = TRUE;
					$this->allow['editAll'] = TRUE;
					$this->allow['deleteEntry'] = TRUE;
					$this->allow['deleteImage'] = TRUE;
					$this->allow['editOnlyHidden'] = FALSE;
					$this->txtUserProps = $this->constObj->getWrap('hot',$this->langObj->get('isadmin'));
					$this->beEditor = 2;
				} else if ($myBeEdit==1) {
					$this->txtUserProps = '';
					$this->allow['admin'] = FALSE;
					$this->allow['editOnlyHidden'] = FALSE;

					$addEntry = $this->checkBeUserIn($myBeAllow['addEntry.']['user'],$myBeAllow['addEntry.']['group']);
					$seeAllHidden = 1;//$this->checkBeUserIn($myBeAllow['seeAllHidden.']['user'],$myBeAllow['seeAllHidden.']['group']);
					$uploadImage = $this->checkBeUserIn($myBeAllow['uploadImage.']['user'],$myBeAllow['uploadImage.']['group']);
					$unhideAll = $this->checkBeUserIn($myBeAllow['unhideAll.']['user'],$myBeAllow['unhideAll.']['group']);
					$unhideOwn = $this->checkBeUserIn($myBeAllow['unhideOwn.']['user'],$myBeAllow['unhideOwn.']['group']);
					$editAll = $this->checkBeUserIn($myBeAllow['editAll.']['user'],$myBeAllow['editAll.']['group']);
					$editOwn = $this->checkBeUserIn($myBeAllow['editOwn.']['user'],$myBeAllow['editOwn.']['group']);
					$deleteEntry = $editAll &&
						$this->checkBeUserIn($myBeAllow['deleteEntry.']['user'],$myBeAllow['deleteEntry.']['group']);
					$deleteOwn = ($editOwn || $editAll) &&
						$this->checkBeUserIn($myBeAllow['deleteOwn.']['user'],$myBeAllow['deleteOwn.']['group']);
					$deleteImage = $this->checkBeUserIn($myBeAllow['deleteImage.']['user'],$myBeAllow['deleteImage.']['group']);

					if ($seeAllHidden) {
						$this->allow['seeAllHidden'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('seeAllHidden');
					}

					if ($addEntry) {
						$editMode = $myBeEdit;
						$this->allow['addEntry'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('addEntry');
					}

					if ($unhideAll) {
						$editMode = $myBeEdit;
						$this->allow['unhideAll'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('unhideAll');
					} else if ($unhideOwn) {
						$editMode = $myBeEdit;
						$this->allow['unhideOwn'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('unhideOwn');
					}

					if ($editAll) {
						$editMode = $myBeEdit;
						$this->allow['editAll'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('editAll');
					} else if ($editOwn) {
						$editMode = $myBeEdit;
						$this->allow['editOwn'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('editOwn');
					}

					if ($deleteEntry) {
						$editMode = $myBeEdit;
						$this->allow['deleteEntry'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('deleteEntry');
					} else if ($deleteOwn) {
						$editMode = $myBeEdit;
						$this->allow['deleteOwn'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('deleteOwn');
					}

					if ($uploadImage) {
						$editMode = $myBeEdit;
						$this->allow['uploadImage'] = TRUE;
					}
					if ($deleteImage) {
						$editMode = $myBeEdit;
						$this->allow['deleteImage'] = TRUE;
					}
					if ($uploadImage && $deleteImage) {
						$this->txtUserProps .= $this->langObj->get('uploadDeleteImage');
					} else {
						if ($uploadImage) { $this->txtUserProps .= $this->langObj->get('uploadImage'); }
						if ($deleteImage) { $this->txtUserProps .= $this->langObj->get('deleteImage'); }
					}
				$this->beEditor = $editMode;

				}
			} else {
				$myBeEdit = 0;
			}

			// EditMode only available, if any user is logged in !!
			if (count($this->feUser)>0 && $editMode<2) {
				// check, if Admin
				$isAdmin = $this->checkFeUserIn($myAllow['admin.']['user'],$myAllow['admin.']['group']);

				if ($myEdit==2 || ($isAdmin && $myEdit==1) ) {
					// EditMode = 2 ==> Any User is Admin; Access controled by Typo3-Access per Page/Content
					// EditMode = 1 && isAdmin ==> AdminUser for this plugin is set
					$editMode = $myEdit;
					$this->allow['admin'] = TRUE;
					$this->allow['seeAllHidden'] = TRUE;
					$this->allow['addEntry'] = TRUE;
					$this->allow['uploadImage'] = TRUE;
					$this->allow['unhideAll'] = TRUE;
					$this->allow['editAll'] = TRUE;
					$this->allow['deleteEntry'] = TRUE;
					$this->allow['deleteImage'] = TRUE;
					$this->allow['editOnlyHidden'] = FALSE;
					$this->txtUserProps = $this->constObj->getWrap('hot',$this->langObj->get('isadmin'));
				} else if ($myEdit==1) {
					$this->txtUserProps = '';
					$this->allow['admin'] = FALSE;

					if ($myBeEdit<1 && intval($myAllow['onlyOwnImages'])!=0) {
						$this->allow['onlyOwnImages'] = TRUE;
					}
					if ($myBeEdit<1 && intval($myAllow['showOnlyOwnEntries'])!=0) {
						$this->allow['showOnlyOwnEntries'] = TRUE;
					}
					if ($myBeEdit<1 && intval($myAllow['showOnlyOwnAndPublicEntries'])!=0) {
						$this->allow['showOnlyOwnAndPublicEntries'] = TRUE;
					}
					if ($myBeEdit<1 && intval($myAllow['showOnlyOwnDetails'])!=0) {
						$this->allow['showOnlyOwnDetails'] = TRUE;
					}

					$this->allow['userlistUserfield'] = $myAllow['userlistUserfield'];
					$this->allow['catlistUserfield'] = $myAllow['catlistUserfield'];
					$this->allow['catlistCatfield'] = $myAllow['catlistCatfield'];

					$editOnlyHidden = $this->checkFeUserIn($myAllow['editOnlyHidden.']['user'],$myAllow['editOnlyHidden.']['group']);
					if ($editOnlyHidden) {
						$this->allow['editOnlyHidden'] = TRUE;
					}

					$addEntry = $this->checkFeUserIn($myAllow['addEntry.']['user'],$myAllow['addEntry.']['group']);
					$seeAllHidden = $this->checkFeUserIn($myAllow['seeAllHidden.']['user'],$myAllow['seeAllHidden.']['group']);
					$uploadImage = $this->checkFeUserIn($myAllow['uploadImage.']['user'],$myAllow['uploadImage.']['group']);
					$unhideAll = $this->checkFeUserIn($myAllow['unhideAll.']['user'],$myAllow['unhideAll.']['group']);
					$unhideOwn = $this->checkFeUserIn($myAllow['unhideOwn.']['user'],$myAllow['unhideOwn.']['group']);
					$unhideFixedOwn = $this->checkFeUserIn($myAllow['unhideFixedOwn.']['user'],$myAllow['unhideFixedOwn.']['group']);
					$editAll = $this->checkFeUserIn($myAllow['editAll.']['user'],$myAllow['editAll.']['group']);
					$editOwn = $this->checkFeUserIn($myAllow['editOwn.']['user'],$myAllow['editOwn.']['group']);
					$editFixedOwn = $this->checkFeUserIn($myAllow['editFixedOwn.']['user'],$myAllow['editFixedOwn.']['group']);
					$deleteEntry = $editAll && $this->checkFeUserIn($myAllow['deleteEntry.']['user'],$myAllow['deleteEntry.']['group']);
					$deleteOwn = ($editOwn || $editAll) && $this->checkFeUserIn($myAllow['deleteOwn.']['user'],$myAllow['deleteOwn.']['group']);
					$deleteImage = $this->checkFeUserIn($myAllow['deleteImage.']['user'],$myAllow['deleteImage.']['group']);

					if ($seeAllHidden && !$this->allow['seeAllHidden']) {
						$editMode = $myEdit;
						$this->allow['seeAllHidden'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('seeAllHidden');
					}

					if ($addEntry && !$this->allow['addEntry']) {
						$editMode = $myEdit;
						$this->allow['addEntry'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('addEntry');
					}

					if ($unhideAll && !$this->allow['unhideAll']) {
						$editMode = $myEdit;
						$this->allow['unhideAll'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('unhideAll');
					} else if ($unhideOwn && !$this->allow['unhideOwn']) {
						$editMode = $myEdit;
						$this->allow['unhideOwn'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('unhideOwn');
					}
					if ($unhideFixedOwn && !$this->allow['unhideFixedOwn']) {
						$editMode = $myEdit;
						$this->allow['unhideFixedOwn'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('unhideFixedOwn');
					}

					if ($editAll && !$this->allow['editAll']) {
						$editMode = $myEdit;
						$this->allow['editAll'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('editAll');
					} else if ($editOwn && !$this->allow['editOwn']) {
						$editMode = $myEdit;
						$this->allow['editOwn'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('editOwn');
					}
					if ($editFixedOwn && !$this->allow['editFixedOwn']) {
						$editMode = $myEdit;
						$this->allow['editFixedOwn'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('editFixedOwn');
					}

					if ($deleteEntry && !$this->allow['deleteEntry']) {
						$editMode = $myEdit;
						$this->allow['deleteEntry'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('deleteEntry');
					} else if ($deleteOwn && !$this->allow['deleteOwn']) {
						$editMode = $myEdit;
						$this->allow['deleteOwn'] = TRUE;
						$this->txtUserProps .= $this->langObj->get('deleteOwn');
					}

					if ($uploadImage && $deleteImage && !$this->allow['uploadImage']  && !$this->allow['deleteImage']) {
						$this->txtUserProps .= $this->langObj->get('uploadDeleteImage');
					} else {
						if ($uploadImage && !$this->allow['uploadImage']) { $this->txtUserProps .= $this->langObj->get('uploadImage'); }
						if ($deleteImage && !$this->allow['deleteImage']) { $this->txtUserProps .= $this->langObj->get('deleteImage'); }
					}
					if ($uploadImage) {
						$editMode = $myEdit;
						$this->allow['uploadImage'] = TRUE;
					}
					if ($deleteImage) {
						$editMode = $myEdit;
						$this->allow['deleteImage'] = TRUE;
					}

					if ($this->allow['userlistUserfield']) {
						if ($this->checkFeUserIn($myAllow['editUserlist.']['user'],$myAllow['editUserlist.']['group'])) {
							$editMode = $myEdit;
							$this->allow['editUserlist'] = $this->feUser[$this->allow['userlistUserfield']];
						}
						if ($this->checkFeUserIn($myAllow['unhideUserlist.']['user'],$myAllow['unhideUserlist.']['group'])) {
							$editMode = $myEdit;
							$this->allow['unhideUserlist'] = $this->feUser[$this->allow['userlistUserfield']];
						}
						if ($this->checkFeUserIn($myAllow['deleteUserlist.']['user'],$myAllow['deleteUserlist.']['group'])) {
							$editMode = $myEdit;
							$this->allow['deleteUserlist'] = $this->feUser[$this->allow['userlistUserfield']];
						}
					}
					if ($this->allow['catlistUserfield'] && $this->allow['catlistCatfield']) {
						if ($this->checkFeUserIn($myAllow['editCatlist.']['user'],$myAllow['editCatlist.']['group'])) {
							$editMode = $myEdit;
							$this->allow['editCatlist'] = $this->feUser[$this->allow['catlistUserfield']];
						}
						if ($this->checkFeUserIn($myAllow['unhideCatlist.']['user'],$myAllow['unhideCatlist.']['group'])) {
							$editMode = $myEdit;
							$this->allow['unhideCatlist'] = $this->feUser[$this->allow['catlistUserfield']];
						}
						if ($this->checkFeUserIn($myAllow['deleteCatlist.']['user'],$myAllow['deleteCatlist.']['group'])) {
							$editMode = $myEdit;
							$this->allow['deleteCatlist'] = $this->feUser[$this->allow['catlistUserfield']];
						}
					}

				}

			}

			if ($editMode) {
				if (strlen($this->txtUserProps)>1) {
					$this->txtUserProps = $this->constObj->getWrap('warn',$this->langObj->get('userauthorities').$this->txtUserProps);
				}
				$this->_getListOfEditors($myAllow);
			}

		}
		$this->useEditTemplates = $editMode;

		if ($this->allow['admin']) {
			$this->allow['showOnlyOwnEntries'] =  FALSE;
			$this->allow['showOnlyOwnAndPublicEntries'] =  FALSE;
			$this->allow['showOnlyOwnDetails'] =  FALSE;
		}
		$this->debugObj->debugIf('permitConf',Array('$editMode'=>$editMode, 'allow'=>$this->allow, '$myAllow'=>$myAllow, 'useEditTemplates'=>$this->useEditTemplates));
		return ($editMode);
	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$myAllow: ...
	 * @return	[type]		...
	 */
    private function _getListOfEditors($myAllow) {
		$this->_fCount(__FUNCTION__);
		if (is_array($myAllow)) for (reset($myAllow);$key=key($myAllow);next($myAllow)) {
			$chkUser = explode (',',$myAllow[$key]['user']);
			for ($i=0; $i<count($chkUser); $i++) {
				if (intval($chkUser[$i])>0) {
					$this->usersIdFe[$chkUser[$i]] = $chkUser[$i];
				} else if (strcmp($chkUser[$i],'0')!=0) {
					$this->usersNameFe[$chkUser[$i]] = "'".$chkUser[$i]."'";
				}
			}
			$chkGroup = explode (',',$myAllow[$key]['group']);
			for ($i=0; $i<count($chkGroup); $i++) {
				if (intval($chkGroup[$i])>0) {
					$this->groupsIdFe[$chkGroup[$i]] = $chkGroup[$i];
				} else if (strcmp(trim($chkGroup[$i]),'0')!=0) {
					// check for groupname
					if (  !isset($this->idOfFeGroupName[ trim($chkGroup[$i]) ])  ) {
						// GroupID zum zug. Namen suchen und cachen !
						$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,title','fe_groups',
							"deleted=0 AND title='".trim($chkGroup[$i])."'");
						if ($res && $GLOBALS['TYPO3_DB']->sql_num_rows($res)>0) {
							$myRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
							$this->idOfFeGroupName[ trim($chkGroup[$i]) ] = $myRow['uid'];
						}
					}
				}
			}
		}


		if (count($this->usersIdFe)>0) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,username','fe_users',
							'deleted=0 AND uid IN ('.implode(',',$this->usersIdFe).')');
			if ($res) {
				while ($myRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
					$this->usersIdFe[$myRow['uid']] = $myRow['username'];
				}
			}
		}
		if (count($this->usersNameFe)>0)  {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,username','fe_users',
							'deleted=0 AND username IN ('.implode(',',$this->usersNameFe).')');
			if ($res) {
				while ($myRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
					$this->usersIdFe[$myRow['uid']] = $myRow['username'];
				}
			}
		}
		unset ($this->usersNameFe);

		if (count($this->groupsIdFe)>0) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,title','fe_groups',
							'deleted=0 AND uid IN ('.implode(',',$this->groupsIdFe).')');
			if ($res) {
				while ($myRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
					$this->groupsIdFe[$myRow['uid']] = $myRow['title'];
				}
			}
		}

		if (is_array($this->idOfFeGroupName)) for (reset($this->idOfFeGroupName);$key=key($this->idOfFeGroupName);next($this->idOfFeGroupName)) {
			$this->groupsIdFe[$this->idOfFeGroupName[$key]] = $key;
		}

		for (reset($this->groupsIdFe);$key=key($this->groupsIdFe);next($this->groupsIdFe)) {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid,username,usergroup','fe_users',
							'deleted=0 AND usergroup '."REGEXP '^$key$|,$key,|^$key,|,$key$'");
			if ($res) {
				while ($myRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
					$this->usersIdFe[$myRow['uid']] = $myRow['username'];
				}
			} else {
				t3lib_div::debug(Array('query='=>$query,'res='=>$res,'error='=>$GLOBALS['TYPO3_DB']->sql_error()));
			}
		}

		asort ($this->usersIdFe);
	}




}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sglib_permit.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sg_zlib/class.tx_sglib_permit.php']);
}
?>