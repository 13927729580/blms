<?php

/**
 * 	UserAbility 用户技能信息
 */
class UserAbility extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    public $belongsTo = array(
        'Ability' => array(
	        'className' => 'Ability',
	        'conditions' => 'Ability.id=UserAbility.ability_id',
	        'order' => '',
	        'dependent' => true
        ),
        'AbilityLevel'=>array(
		'className' => 'AbilityLevel',
		'conditions' => 'AbilityLevel.id=UserAbility.ability_level_id',
		'order' => '',
		'dependent' => true
        )
    );
    
    function experience_value_change($user_id=0,$experience_value=array()){
    		if(empty($user_id)||empty($experience_value))return false;
    		$ability_codes=array();
    		$ability_experience_values=array();
    		foreach($experience_value as $k=>$v){
    			$ability_codes[]=$k;
    			$ability_experience_values[$k]=sizeof($v);
    		}
    		$conditions=array();
    		$conditions['UserAbility.user_id']=$user_id;
    		$conditions['UserAbility.status']='1';
    		$conditions['Ability.code']=$ability_codes;
    		$conditions['Ability.status']='1';
    		$user_ability_info=$this->find('all',array('conditions'=>$conditions,'fields'=>"UserAbility.id,UserAbility.ability_level_id,UserAbility.experience_value,Ability.id,Ability.code"));
    		foreach($user_ability_info as $v){
    			$experience_value=isset($ability_experience_values[$v['Ability']['code']])?$ability_experience_values[$v['Ability']['code']]:0;
    			if($experience_value==0)continue;
    			$user_ability_data=array(
    				'id'=>$v['UserAbility']['id'],
    				'experience_value'=>$v['UserAbility']['experience_value']+$experience_value
    			);
    			$this->save($user_ability_data);
    		}
    }
    
    function check_level($user_id=0){
    		if(empty($user_id))return false;
    		$conditions=array();
    		$conditions['UserAbility.user_id']=$user_id;
    		$conditions['UserAbility.status']='1';
    		$conditions['Ability.status']='1';
    		$user_ability_info=$this->find('all',array('conditions'=>$conditions,'fields'=>"UserAbility.id,UserAbility.ability_level_id,UserAbility.experience_value,Ability.code,AbilityLevel.name"));
    		$ability_level_cond=array();
    		$ability_level_cond['AbilityLevel.status']='1';
    		foreach($user_ability_info as $v){
    			$ability_level_id=intval($v['UserAbility']['ability_level_id']);
    			$ability_level_cond['or'][]=array('AbilityLevel.ability_code'=>$v['Ability']['code'],'AbilityLevel.id <>'=>$ability_level_id,'AbilityLevel.experience_value <='=>$v['UserAbility']['experience_value']);
    		}
    		$AbilityLevel = ClassRegistry::init('AbilityLevel');
    		$next_ability_level_info=$AbilityLevel->find('all',array('fields'=>'AbilityLevel.id,AbilityLevel.name,AbilityLevel.experience_value,Ability.id,Ability.name','conditions'=>$ability_level_cond));
    		if(!empty($next_ability_level_info)){
    			$UpdateLevelInfo=array();
    			foreach($next_ability_level_info as $v){
    				$this->updateAll(array('UserAbility.ability_level_id'=>$v['AbilityLevel']['id']),array('UserAbility.user_id'=>$user_id,'UserAbility.ability_id'=>$v['Ability']['id']));
    				$UpdateLevelInfo[]=$v['Ability']['name'].$v['AbilityLevel']['name'];
    			}
    			$this->notifty_user($user_id,$UpdateLevelInfo);
    		}
    }
    
    function notifty_user($user_id=0,$update_level=array()){
    		if(empty($user_id)||empty($update_level))return false;
    		$User = ClassRegistry::init('User');
    		$SynchroUser = ClassRegistry::init('SynchroUser');
    		$user_info=$User->find('first',array('conditions'=>array('User.id'=>$user_id,'User.status'=>'1'),'fields'=>'User.id,User.first_name'));
		$synchro_user = $SynchroUser->find('first', array('conditions' => array('SynchroUser.status' => 1, 'SynchroUser.type' => 'wechat','SynchroUser.user_id'=>$user_id)));
		if(!empty($synchro_user)&&!empty($user_info)){
			$touser=$synchro_user['SynchroUser']['account'];
			$NotifyTemplateType = ClassRegistry::init('NotifyTemplateType');
			$notify_template_info=$NotifyTemplateType->typeformat("user_upgrade","wechat");
			$notify_template=isset($notify_template_info['wechat'])?$notify_template_info['wechat']:array();
			if(empty($notify_template))return false;
			$wechat_params=$NotifyTemplateType->wechatparamsformat($notify_template);
			
			$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
			$server_host = 'http://'.$host;
			$request_url=$server_host.'/users/index';
			$action_content='您已经成功升级!';
			$user_name=$user_info['User']['first_name'];
			$upgrade_date=date('Y-m-d H:i:s');
			$action_desc="点击【详情】查看!";
			App::import('Component', 'Notify');
			$Notify = new NotifyComponent();
			
			foreach($update_level as $level_name){
				$wechat_message=array();
				foreach($wechat_params as $k=>$v){
					$wechat_message[$k]=array(
						'value'=>isset($$v)?$$v:''
					);
				}
				$wechat_post=array(
		   			'touser'=>$touser,
		   			'template_id'=>$notify_template['NotifyTemplateTypeI18n']['param03'],
		   			'url'=>$request_url,
		   			'data'=>$wechat_message
		   		);
		   		$Notify->wechat_message($wechat_post);
			}
		}
    }
}
