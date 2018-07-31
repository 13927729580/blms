<?php

/**
 * 	UserTypeLevel 用户技能等级
 */
class UserTypeLevel extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    /**
     * check_level方法，检测等级
     *
     * @user_id 用户id
     *
     */
    function check_level($user_id=0){
    		return false;
    		$User = ClassRegistry::init('User');
    		$UserLevelLog = ClassRegistry::init('UserLevelLog');
    		$UserLevelRule = ClassRegistry::init('UserLevelRule');
    		$UserEvaluationLog = ClassRegistry::init('UserEvaluationLog');
    		$user_info=$User->find('first',array('conditions'=>array('User.id'=>$user_id,'User.status'=>'1'),'fields'=>'User.id,User.first_name,User.experience_value,User.user_level_id'));
    		if(!empty($user_info['User'])){
    			$user_experience_value=$user_info['User']['experience_value'];
    			$user_level_id=$user_info['User']['user_level_id'];
    			$user_level_data=$this->find('first',array('conditions'=>array('UserLevel.level_experience_value <='=>$user_experience_value,'UserLevel.status'=>'1','UserLevel.id <>'=>$user_level_id),'order'=>'UserLevel.level_experience_value'));
    			if(!empty($user_level_data['UserLevel'])&&$user_level_id!=$user_level_data['UserLevel']['id']){
    				$level_code=$user_level_data['UserLevel']['code'];
    				$level_rule_list=$UserLevelRule->find('list',array('fields'=>"UserLevelRule.evaluation_code,UserLevelRule.evaluation_score",'conditions'=>array('UserLevelRule.level_code'=>$level_code,'UserLevelRule.status'=>'1')));
    				if(!empty($level_rule_list)){
    					$level_evaluation_cond=array();
    					foreach($level_rule_list as $k=>$v){
    						$level_evaluation_cond['and'][]=array("Evaluation.code"=>$k,"UserEvaluationLog.score >="=>$v);
    					}
    					$level_evaluation_cond['Evaluation.status']='1';
    					$level_evaluation_cond['UserEvaluationLog.user_id']=$user_id;
    					$level_evaluation_log=$UserEvaluationLog->find('all',array('conditions'=>$level_evaluation_cond,'fields'=>"Evaluation.id,Evaluation.code",'group'=>"Evaluation.id,Evaluation.code"));
    					if(empty($level_evaluation_log)||sizeof($level_evaluation_log)!=sizeof($level_rule_list))return false;
    				}
    				$user_data=array(
					'id'=>$user_info['User']['id'],
					'user_level_id'=>$user_level_data['UserLevel']['id']
				);
				$User->saveAll($user_data);
				$user_level_log=array(
					'id'=>0,
					'user_id'=>$user_info['User']['id'],
					'user_level_id'=>$user_level_data['UserLevel']['id'],
					'operator'=>'0',
					'remark'=>'System'
				);
				$UserLevelLog->saveAll($user_level_log);
				$SynchroUser = ClassRegistry::init('SynchroUser');
		    		$synchro_user = $SynchroUser->find('first', array('conditions' => array('SynchroUser.status' => 1, 'SynchroUser.type' => 'wechat','SynchroUser.user_id'=>$user_id)));
		    		if(!empty($synchro_user)){
	    				$touser=$synchro_user['SynchroUser']['account'];
	    				$NotifyTemplateType = ClassRegistry::init('NotifyTemplateType');
	    				$notify_template_info=$NotifyTemplateType->typeformat("user_upgrade","wechat");
					$notify_template=isset($notify_template_info['wechat'])?$notify_template_info['wechat']:array();
					if(empty($notify_template))return false;
					$wechat_params=$NotifyTemplateType->wechatparamsformat($notify_template);
	    				$action_content='您已经成功升级!';
	    				$user_name=$user_info['User']['first_name'];
	    				$level_name=$user_level_data['UserLevel']['name'];
	    				$upgrade_date=date('Y-m-d H:i:s');
	    				$action_desc="点击【详情】查看!";
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
