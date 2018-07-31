<?php

/**
 * 资金日志模型.
 */
class UserPointLog extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name UserBalanceLog 资金日志
     */
    public $name = 'UserPointLog';

    public $belongsTo = array('User' => array(
                            'className' => 'User',
                            'conditions' => 'User.id=UserPointLog.user_id',
                            'order' => '',
                            'dependent' => true,
                            'foreignKey' => '',
                        ),
                    );
    
    function point_notify($point_log_detail=array()){
    		if(!empty($point_log_detail)){
    			$user_id=isset($point_log_detail['user_id'])?$point_log_detail['user_id']:0;
    			if(empty($user_id))return;
    			$SynchroUser=ClassRegistry::init('SynchroUser');
    			$user_detail=$SynchroUser->find('first',array('conditions'=>array('SynchroUser.user_id'=>$user_id,'SynchroUser.type'=>'wechat')));
    			if(empty($user_detail))return;
    			$NotifyTemplateType=ClassRegistry::init('NotifyTemplateType');
    			$notify_template_info=$NotifyTemplateType->typeformat('point_change','wechat');
    			if(empty($notify_template_info))return;
    			$notify_template=isset($notify_template_info['wechat'])?$notify_template_info['wechat']:array();
			$wechat_params=$NotifyTemplateType->wechatparamsformat($notify_template);
    			
    			$action_content="您的积分账户有新的变动，具体内容如下：";
			$point_date=date('Y-m-d H:i:s');
			$point_change=$point_log_detail['point_change'];
			$point_note=$point_log_detail['system_note'];
			$current_point=$point_log_detail['point']+$point_log_detail['point_change'];
			$action_desc="感谢您的使用,如非本人操作,请及时联系客服";
    			$wechat_message=array();
   			foreach($wechat_params as $k=>$v){
   				$wechat_message[$k]=array(
   					'value'=>isset($$v)?$$v:''
   				);
   			}
   			$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        		$server_host = 'http://'.$host;
   			$wechat_post=array(
	   			'touser'=>$user_detail['SynchroUser']['account'],
	   			'template_id'=>$notify_template['NotifyTemplateTypeI18n']['param03'],
	   			'url'=>$server_host,
	   			'data'=>$wechat_message
	   		);
	   		App::import('Component', 'Notify');
			$Notify = new NotifyComponent();
	   		$Notify->wechat_message($wechat_post);
    		}
    }
}
