<?php
/**
 * 	UserTaskCondition 任务条件
 */
class UserTaskCondition extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    /*
     * @var $name UserTaskCondition 任务条件
     */
    public $name = 'UserTaskCondition';
    
    public $belongsTo = array(
	        'UserTaskGroup' => array(
		        'className' => 'UserTaskGroup',
		        'conditions' => 'UserTaskGroup.id=UserTaskCondition.task_group_id',
		        'order' => '',
			 'foreignKey'=>'',
		        'dependent' => false
	        ),
	    	  'UserGroupRelation' => array(
		        'className' => 'UserGroupRelation',
		        'conditions' => 'UserGroupRelation.user_task_group_id=UserTaskCondition.task_group_id',
		        'order' => '',
			 'foreignKey'=>'',
		        'dependent' => false
	        )
    );

}
