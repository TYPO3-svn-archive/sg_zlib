<?php

	/*
		$this->config hold a TS-Array: If a value is an array, then the key ends with a dot.
	*/


	// E.g. called by ->get1('tx_sguniv3c.','conf.','subcategory.','relation.','local')
	function get1() {
		$retVal = $this->config;
		$args = func_get_args();
		for ($i=0;$i<func_num_args();$i++) {
			$retVal = $retVal[$args[$i]];
		}
		return ($retVal);
	}
	// 10000 times = 99ms

	// E.g. called by ->get2('tx_sguniv3c.','conf.','subcategory.','relation.','local')
	function get2() {
		$retVal =& $this->config;
		$args = func_get_args();
		for ($i=0;$i<func_num_args();$i++) {
			$retVal =& $retVal[$args[$i]];
		}
		return ($retVal);
	}
	// 10000 times = 101ms

	// E.g. called by ->get3('tx_sguniv3c.conf.subcategory.relation.local')
	function get3($name) {
		$retVal = $this->config;
		$tmp = explode('.',trim($name));
		for ($i=0;$i<count($tmp)-1;$i++) {
			$retVal = $retVal[$tmp[$i].'.'];
		}
		if ($tmp[$i]) {
			$retVal = $retVal[$tmp[$i]];
		}
		return ($retVal);
	}
	// 10000 times = 170ms







//		$t = $this->divObj->getMicroSec();
//		t3lib_div::debug(Array('$t'=>$t, 'File:Line'=>__FILE__.':'.__LINE__));
//		t3lib_div::debug(Array('$this->conf'=>$this->conf, 'File:Line'=>__FILE__.':'.__LINE__));
//		$t = $this->divObj->getMicroDur($t);
//		t3lib_div::debug(Array('$t'=>$t, 'File:Line'=>__FILE__.':'.__LINE__));

		$t = $this->divObj->getMicroSec();
		$x = 0;
		for ($i=0;$i<10000;$i++) {
			$x++;
		}
		$t = $this->divObj->getMicroDur($t);
		t3lib_div::debug(Array('10000x  x++'=>$t, '$x'=>$x, 'File:Line'=>__FILE__.':'.__LINE__));
		
		$t = $this->divObj->getMicroSec();
		for ($i=0;$i<10000;$i++) {
			$val = $this->configObj->get1('tx_sguniv3c.','conf.','subcategory.','relation.','local');
		}
		$t = $this->divObj->getMicroDur($t);
		t3lib_div::debug(Array('10000xget1'=>$t, '$val'=>$val, 'File:Line'=>__FILE__.':'.__LINE__));
		
		$t = $this->divObj->getMicroSec();
		for ($i=0;$i<10000;$i++) {
			$val = $this->configObj->get1('list.','returnEditToList');
		}
		$t = $this->divObj->getMicroDur($t);
		t3lib_div::debug(Array('10000xget1'=>$t, '$val'=>$val, 'File:Line'=>__FILE__.':'.__LINE__));



		$t = $this->divObj->getMicroSec();
		for ($i=0;$i<10000;$i++) {
			$val = $this->configObj->get2('tx_sguniv3c.','conf.','subcategory.','relation.','local');
		}
		$t = $this->divObj->getMicroDur($t);
		t3lib_div::debug(Array('10000xget2'=>$t, '$val'=>$val, 'File:Line'=>__FILE__.':'.__LINE__));
		
		$t = $this->divObj->getMicroSec();
		for ($i=0;$i<10000;$i++) {
			$val = $this->configObj->get3('tx_sguniv3c.conf.subcategory.relation.local');
		}
		$t = $this->divObj->getMicroDur($t);
		t3lib_div::debug(Array('10000xget3'=>$t, '$val'=>$val, 'File:Line'=>__FILE__.':'.__LINE__));
		


?>