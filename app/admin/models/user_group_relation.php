<?php
/**
 * 	UserGroupRelation 任务分组及任务关联关系
 */
class UserGroupRelation extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    /*
     * @var $name UserGroupRelation 任务分组及任务关联关系
     */
    public $name = 'UserGroupRelation';
    
	public $belongsTo = array(
        'UserTaskGroup' => array('className' => 'UserTaskGroup',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'user_task_group_id',
        )
    );
}
