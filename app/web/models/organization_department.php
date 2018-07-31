<?php

class OrganizationDepartment extends AppModel{
	public $useDbConfig = 'hr';
	public $name = 'OrganizationDepartment';
	
	function tree($organization_id=0){
		$depart_list=$this->find('all',array('conditions'=>array('organization_id'=>$organization_id,'status'=>'1')));
    		$depart_group=array();
    		foreach($depart_list as $v){
    			$depart_group[$v['OrganizationDepartment']['parent_department']][]=$v['OrganizationDepartment'];
    		}
    		return $this->department_tree(0,$depart_group);
	}
	
	function department_tree($parent_id,$department_infos){
    		$department_tree=array();
    		$department_data = isset($department_infos[$parent_id])?$department_infos[$parent_id]:array();
		if(!empty($department_data)){
			foreach ($department_data as $v) {
				$child = $this ->department_tree($v['id'],$department_infos);
				if(!empty($child))$v['child_department']=$child;
				$department_tree[] = $v;
			}
		}
		//pr($department_tree);
		return $department_tree;
    }
}