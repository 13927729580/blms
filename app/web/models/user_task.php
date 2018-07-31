<?php

/**
 * 	UserTask 用户任务
 */
class UserTask extends AppModel{
	    /*
	     * @var $useDbConfig 数据库配置
	     */
	    public $useDbConfig = 'hr';
	    
	    function user_task_list(){
	    		$user_task_result=array();
	    		
	    		$conditions=array();
	    		$conditions['UserTask.status']='1';
	    		$user_task_infos=$this->find('all',array('conditions'=>$conditions));
	    		if(!empty($user_task_infos)){
	    			foreach($user_task_infos as $v){
	    				
	    			}
	    		}
	    }
	    
	    /**
	     * task_completed方法，任务完成处理
	     *
	     * @task_code 任务编码
	     * @user_id 用户id
	     *
	     */
	    function task_completed($task_code,$user_id,$controller_obj=null){
	    		return false;
	    		$UserTaskLog = ClassRegistry::init('UserTaskLog');
	    		$User = ClassRegistry::init('User');
	    		$user_task_info=$this->find('first',array('conditions'=>array('UserTask.code'=>$task_code,'UserTask.status'=>'1')));
	    		if(!empty($user_task_info['UserTask'])&&!empty($user_id)){
	    			$task_type=$user_task_info['UserTask']['task_type'];
	    			$effective_task=true;//任务完成是否有效
	    			if($task_type=='1'){
	    				$user_task_log_count=$UserTaskLog->find('count',array('conditions'=>array('UserTaskLog.user_id'=>$user_id,'user_task_id'=>$user_task_info['UserTask']['id'],'UserTaskLog.created >='=>date('Y-m-d 00:00:00'))));
	    				$effective_task=$user_task_log_count>0?false:true;
	    			}else if($task_type=='2'){
	    				$user_task_log_count=$UserTaskLog->find('count',array('conditions'=>array('UserTaskLog.user_id'=>$user_id,'user_task_id'=>$user_task_info['UserTask']['id'])));
	    				$effective_task=$user_task_log_count>0?false:true;
	    			}
	    			if($effective_task){
		    			$user_task_log=array(
		    				'id'=>0,
		    				'user_id'=>$user_id,
		    				'user_task_id'=>$user_task_info['UserTask']['id'],
		    				'operator'=>0,
		    				'remark'=>'Task Completed'
		    			);
		    			$UserTaskLog->save($user_task_log);
		    			$task_experience_value=intval($user_task_info['UserTask']['task_experience_value']);
		    			if($task_experience_value>0){
		    				$User->experience_value_change($user_id,$task_experience_value);
		    			}
		    			$SynchroUser = ClassRegistry::init('SynchroUser');
		    			$synchro_user = $SynchroUser->find('first', array('conditions' => array('SynchroUser.status' => 1, 'SynchroUser.type' => 'wechat','SynchroUser.user_id'=>$user_id)));
		    			if(!empty($synchro_user)){
		    				$touser=$synchro_user['SynchroUser']['account'];
		    				$NotifyTemplateType = ClassRegistry::init('NotifyTemplateType');
		    				$notify_template_info=$NotifyTemplateType->typeformat("task_completed","wechat");
						$notify_template=isset($notify_template_info['wechat'])?$notify_template_info['wechat']:array();
						if(empty($notify_template))return false;
						$wechat_params=$NotifyTemplateType->wechatparamsformat($notify_template);
		    				$action_content='您的任务已完成';
		    				$task_name=$user_task_info['UserTask']['name'];
		    				$task_completed_date=date('Y-m-d H:i:s');
		    				$task_experience_value=$task_experience_value.'个经验值';
		    				$action_desc="此项任务的经验值奖励已存入您的账户内，点击【详情】查看!";
		    				$wechat_message=array();
						foreach($wechat_params as $k=>$v){
							$wechat_message[$k]=array(
								'value'=>isset($$v)?$$v:''
							);
						}
						$server_host=isset($controller_obj->server_host)?$controller_obj->server_host:'';
						if($server_host==''){
							$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
							$server_host = 'http://'.$host;
						}
						$request_url=$server_host.'/users/index';
						$wechat_post=array(
				   			'touser'=>$touser,
				   			'template_id'=>$notify_template['NotifyTemplateTypeI18n']['param03'],
				   			'url'=>$request_url,
				   			'data'=>$wechat_message
				   		);
				   		App::import('Component', 'Notify');
				   		$Notify = new NotifyComponent();
				   		$Notify->wechat_message($wechat_post);
		    			}
	    			}
	    		}
	    }
}
