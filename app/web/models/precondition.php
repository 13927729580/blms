<?php

/**
 * 	Precondition 前置条件
 */
class Precondition extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    function pre_condition_list($object_type='',$object_code=''){
    		$conditions=array(
    			'Precondition.object'=>$object_type,
    			'Precondition.object_code'=>$object_code,
    			'Precondition.object_code <>'=>''
    		);
    		$condition_list=$this->find('list',array('fields'=>'params,value','conditions'=>$conditions,'order'=>'id'));
    		return $condition_list;
    }
}