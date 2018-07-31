<?php

/*****************************************************************************
 * svoms  订单模型
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
class Department extends AppModel{
	/*
	* @var $name Department  组织菜单
	*/
	public $name = 'Department';

	/*
	* @var $hasOne array 关联分类多语言表
	*/
	public $hasOne = array(
		'DepartmentI18n' => array(
			'className' => 'DepartmentI18n',
			'conditions' => '',
			'order' => '',
			'dependent' => true,
			'foreignKey' => 'department_id'
		)
	);
	
	/**
	* set_locale方法，设置语言环境.
	*
	* @param string $locale
	*/
	public function set_locale($locale){
		$conditions = "DepartmentI18n.locale = '".$locale."'";
		$this->hasOne['DepartmentI18n']['conditions'] = $conditions;
	}
	
	/**
	* localeformat方法，数组结构调整.
	*
	* @param int $id 输入分类编号
	*
	* @return array $lists_formated 返回发票所有语言的信息
	*/
    	public function localeformat($id){
	        $lists = $this->find('all', array('conditions' => array('Department.id' => $id)));
	        $lists_formated = array();
	        foreach ($lists as $k => $v) {
	            $lists_formated['Department'] = $v['Department'];
	            $lists_formated['DepartmentI18n'][] = $v['DepartmentI18n'];
	            foreach ($lists_formated['DepartmentI18n'] as $key => $val) {
	                	$lists_formated['DepartmentI18n'][$val['locale']] = $val;
	            }
	        }
	        return $lists_formated;
    	}
    
    function tree(){
		$depart_list=$this->find('all',array('conditions'=>array('status'=>'0')));
		$depart_group=array();
		foreach($depart_list as $v){
			$depart_group[$v['Department']['parent_id']][]=$v['Department'];
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
		return $department_tree;
    }
}