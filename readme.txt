If you change your plugins to use sg_zlib instead of sg_zfelib, then please check/change this:

Version 0.1.100 and above:
- change "$PCA['xxx']" or "$this->PCA['xxx']" to "$this->configObj->getBase('xxx')"
  and change also the usage of the array: keys of arrays now end with a dot (as in ts !!!)
- You can also use 
  e.g. "$this->configObj->get('tx_table.','conf.','hidden.','label')"
  or   "$this->configObj->getTbl('conf.','hidden.','label')"
  to get former PCA-Value PCA['conf']['hidden']['label']   
- 'xxx' is 'xajax', 'view', 'list', 'latest', 'cat', 'subparts'

Version 0.1.102 and above:
- change use of debug; only use $this->debugObj->debugIf($when,$what,$OnlyIf,$AlwaysIf)

Version 0.1.110 and above:
- change TS:
  xxxWrap =       to   constants.wrap.xxx =
  const.xxx =     to   constants.const.xxx =
  iconBig.xxx =   to   constants.icon.xxx = IMAGE; constants.icon.xxx.file = 
- remove from TS:
  icon.xxx =
  alignIcon =
  alignBigIcon =
- If you want to have Images for the Icons by default, then Include static (from extensions):
  "SG_zLib - Default Icon Images"

Version 0.1.111 and above
- change TS:
  button.xxx =    to   constants.buttons.xxx =
- If you want to have Images for the Buttons by default, then Include static (from extensions):
  "SG_zLib - Default Button Images"

Version 0.1.120 and above
- change TS:
  templateXxx    to   templates.files.xxx
  subparts.xxx   to   templates.subparts.xxx
- New:
  templates.named.name = templateXxx:subPartName

Version 0.1.130 and above
- Change TS:
  edit =         to   permit.edit =
  allow.xx		 to   permit.allow.xx
  beEdit =		 to   permit.beEdit =
  beAllow.xx	 to   permit.beAllow.xx
  groupSubParts.xx    to   permit.groupSubParts.xx  
  beAdminIsNoFeAdmin  to   permit.beAdminIsNoFeAdmin

Version 0.1.500 and above
- Change calls to $PCA['conf'][$key]		to	$this->confObj->mainConf[$key.'.']
- Change calls to $PCA['ctrl'][$key]		to	$this->confObj->mainCtrl
- Change calls to $this->pi_getLL(xy)		to	$this->langObj->getLL(xy)
- Change calls to $this->cObj				to	$this->factoryObj->cObj