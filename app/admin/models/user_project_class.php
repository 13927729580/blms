<?php
/**
 * 用户项目
 */
class UserProjectClass extends AppModel{
	/*
	* @var $useDbConfig 数据库配置
	*/
	public $useDbConfig = 'oms';
	public $name = 'UserProjectClass';
	
    /*
     * @var $belongsTo array 关联管理员
     */
    public $belongsTo = array('Operator' => array('className' => 'Operator',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'manager',
                        ),
                  );
}