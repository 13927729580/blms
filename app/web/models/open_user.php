<?php

/**
 * 公众平台 关注用户模型.
 */
class OpenUser extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'sns';
    /*
     * @var $name OpenUser
     */
    public $name = 'OpenUser';

    public function getUserIdByOpenId($openId){
	        $info = $this->find('first', array('conditions' => array('openid' => $openId)));
	        return isset($info['OpenUser']['id']) ? $info['OpenUser']['id'] : '0';
    }

    public function getInfoByOpenId($openId){
	        $data = $this->find('first', array('conditions' => array('openid' => $openId)));
	        return $data;
    }
    
    public function subscribe_point($openId='',$give_point=0,$give_type=''){
    		if($openId==''||$give_point==0||$give_type=='')return false;
    		$subscribeUser=$this->find('first', array('conditions' => array('openid' => $openId)));
    		if($give_type=='S'){//关注操作
    			if(!empty($subscribeUser))return false;
    		}else if($give_type=='R'){//注册操作
    			if(empty($subscribeUser)||$subscribeUser['OpenUser']['subscribe']=='0')return false;
    		}else{
    			return false;
    		}
    		$SynchroUser = ClassRegistry::init('SynchroUser');
    		$wechatUser=$SynchroUser->find('first',array('conditions'=>array('SynchroUser.type'=>'wechat','SynchroUser.account'=>$openId,'SynchroUser.status'=>'1')));
    		if(empty($wechatUser))return false;
    		$wechatUserId=$wechatUser['SynchroUser']['user_id'];
    		$User = ClassRegistry::init('User');
    		$registerUser=$User->find('first',array('conditions'=>array('User.id'=>$wechatUserId,'User.status'=>'1')));
    		if(empty($registerUser))return false;
    		$UserPointLog = ClassRegistry::init('UserPointLog');
		$user_point=$registerUser['User']['point'];
		$point_log = array(
			'id' => 0,
			'user_id' => $wechatUserId,
			'point'=>$user_point,
			'point_change' => $give_point,
			'log_type' => 'G',
			'system_note' => '关注送积分',
			'type_id' => 0
		);
    		$UserPointLog->save($point_log);
    		$UserPointLog->point_notify($point_log);
    		$user_point+=$give_point;
    		$User->save(array('id'=>$wechatUserId,'point'=>$user_point));
    		return true;
    }
}
