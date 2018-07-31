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
class OperatorDepartment extends AppModel
{
    /*
     * @var $name OperatorDepartment  组织菜单
     */
    public $name = 'OperatorDepartment';
    
    function child_deparment_operator($operator_id=0){
    		$DepartmentOperatorList=array();
    		$Department = ClassRegistry::init('Department');
    		$condition=array();
    		$condition['Department.status']='1';
    		$condition[]['Department.manager like']="%,{$operator_id},%";
    		$DepartmentList=$Department->find('list',array('fields'=>'Department.id','conditions'=>$condition));
    		if(!empty($DepartmentList)){
    			$DepartmentOperatorList=$this->find('list',array('fields'=>'OperatorDepartment.operator_id','conditions'=>array('OperatorDepartment.department_id'=>$DepartmentList)));
    		}
    		return $DepartmentOperatorList;
    }
}