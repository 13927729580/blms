<?php

/**
 * 	UserTaskLog 用户任务日志
 */
class UserTaskLog extends AppModel{
	    /*
	     * @var $useDbConfig 数据库配置
	     */
	    public $useDbConfig = 'hr';
	    
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
			$conditions['UserTask.status']='1';
			$conditions['UserTaskLog.user_id']=$user_id;
        		$conditions['UserTaskLog.created >=']=date('Y-m-d 00:00:00');
        		$user_task_ids=$this->find('list',array('conditions'=>$conditions,'joins'=>$joins,'fields'=>"UserTaskLog.user_task_id"));
        		return $user_task_ids;
	    }
}
