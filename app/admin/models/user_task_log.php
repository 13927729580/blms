<?php

/**
 * 	UserTaskLog 用户任务日志
 */
class UserTaskLog extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
	public $belongsTo = array(
        'User' => array('className' => 'User',
            'conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'user_id',
        )
    );

    /*
            任务完成情况统计
    */
    function task_log_infos($user_id=0){
        $db = &ConnectionManager::getDataSource($this->useDbConfig);
        $db_prefix=isset($db->config['prefix'])?$db->config['prefix']:'svhr_';
        $joins = array(
            array(
                'table' => $db_prefix.'user_tasks',
                'alias' => 'UserTask',
                'type' => 'inner',
                'conditions' => array('UserTask.id = UserTaskLog.user_task_id')
            )
        );
        $conditions=array();
        if($user_id!=0){
        	$conditions['UserTaskLog.user_id']=$user_id;
        	$conditions['UserTask.status']='1';
        }
        $user_task_infos=$this->find('all',array('conditions'=>$conditions,'joins'=>$joins,'fields'=>"UserTask.*,UserTaskLog.*"));
        return $user_task_infos;
    }
}