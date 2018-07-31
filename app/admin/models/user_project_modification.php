<?php
/**
 * 用户变更项目
 */
class UserProjectModification extends AppModel{
	/*
	* @var $useDbConfig 数据库配置
	*/
	public $useDbConfig = 'oms';
	public $name = 'UserProjectModification';
	
	/*
	* @var $hasOne array 关联项目表
	*/
	public $hasOne = array(
		'User'=>array(
			'className' => 'User',
			'conditions' => 'User.id=UserProjectModification.user_id',
			'fields'=>'User.id,User.name,User.first_name,User.mobile,User.email,User.img01,identity_card,identity_card_picture',
			'order' => '',
			'dependent' => true,
			'foreignKey' => ''
		)
	);
}