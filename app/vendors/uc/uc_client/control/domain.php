<?php

/*
	[UCenter] (C)2001-2008 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id$
*/

!defined('IN_UC') && exit('Access Denied');

class domaincontrol extends base {

	function domaincontrol() {
		$this->base();
		$this->load('domain');
	}

	function onls() {
		return $_ENV['domain']->get_list(1, 9999, 9999);
	}
}

?>