<?php
	echo $form->create('notify_templates',array('action'=>'notify_config/'.$subgroup_code));
	echo $this->element('config');
	echo $form->end();
?>