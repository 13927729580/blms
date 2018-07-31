<?php
/**
 * 用户变更项目
 */
class UserProjectModificationDetail extends AppModel{
	/*
	* @var $useDbConfig 数据库配置
	*/
	public $useDbConfig = 'oms';
	public $name = 'UserProjectModificationDetail';
	
	/*
	* @var $hasOne array 关联项目表
	*/
	public $hasOne = array(
		'UserProject' => array(
			'className' => 'UserProject',
			'conditions' => 'UserProject.id=UserProjectModificationDetail.user_project_id',
			'fields'=>'UserProject.user_id,UserProject.manager,UserProject.project_class_id',
			'order' => '',
			'dependent' => true,
			'foreignKey' => ''
		),'UserProjectModification' => array(
			'className' => 'UserProjectModification',
			'conditions' => 'UserProjectModification.id=UserProjectModificationDetail.user_project_modification_id',
			'fields'=>'UserProjectModification.operator_id,UserProjectModification.check_status,UserProjectModification.check_time,,UserProjectModification.check_operator',
			'order' => '',
			'dependent' => true,
			'foreignKey' => ''
		)
	);
}