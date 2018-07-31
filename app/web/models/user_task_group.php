<?php
/**
 * 	UserTaskGroup 任务条件
 */
class UserTaskGroup extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    /*
     * @var $name UserTaskGroup 任务分组
     */
    public $name = 'UserTaskGroup';
    
    public $hasMany = array(
                        'UserTaskCondition' => array(
	                        'className' => 'UserTaskCondition',
	                        'conditions' => array('UserTaskCondition.params <>' => ''),
	                        'order' => 'UserTaskCondition.created',
	                        'fields' => 'UserTaskCondition.params,UserTaskCondition.value',
	                        'dependent' => true,
	                        'foreignKey' => 'task_group_id',
	                    )
        );
}
