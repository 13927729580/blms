<?php
/*****************************************************************************
 * svoms 用户日志
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
class UserActionLog extends AppModel{
	/*
	* @var $useDbConfig 数据库配置
	*/
	public $useDbConfig = 'oms';
	public $name = 'UserActionLog';
	
	/*
	 * @var $hasOne array 操作员
	 */
	public $belongsTo = array(
	        'Operator' => array(
		        'className' => 'Operator',
		        'conditions' => 'UserActionLog.operator_id=Operator.id',
		        'fields' => 'Operator.id,Operator.name',
		        'dependent' => true
	    	)
	);
	
	
	/**
	* update_action方法,新增用户操作日志
	*
	* @param array    $action_data	用户日志数据
	*
	*/
    	public function update_action($action_data,$controller){
    		$this->saveAll(array('UserActionLog' => $action_data));
    		if(isset($controller->configs['user_log_send_mail'])&&$controller->configs['user_log_send_mail']=='1'){
        		$User = ClassRegistry::init('User');
        		$user_data=$User->find('first',array('fields'=>'User.id,User.name,User.first_name,User.operator_id','conditions'=>array('User.id'=>$action_data['user_id'],'User.operator_id >'=>0),'recursive' => -1));
        		if(!empty($user_data)){
        			$user_manager=$user_data['User']['operator_id'];
        			$Operator = ClassRegistry::init('Operator');
				$operator_info=$Operator->find('first',array('fields'=>'name,email','conditions'=>array('id'=>$user_manager,'email <>'=>'')));
				$NotifyTemplateType = ClassRegistry::init('NotifyTemplateType');
 				$notify_template=$NotifyTemplateType->typeformat('user_action_log','email');
				if(!empty($operator_info)&&!empty($notify_template)){
					$admin_info=$Operator->check_login();
					$modify_name=$admin_info['name'];
					$operator_name=$operator_info['Operator']['name'];
					$user_link=$controller->server_host."/admin/users/view/".$action_data['user_id'];
					$action_remark=date("Y-m-d H:i:s").$modify_name.$action_data['remark'];
					$shop_name = $controller->configs['shop_name'];
					$subject = $notify_template['email']['NotifyTemplateTypeI18n']['title'];
	        			@eval("\$subject = \"$subject\";");
					$html_body = $notify_template['email']['NotifyTemplateTypeI18n']['param01'];
	        			eval("\$html_body = \"$html_body\";");
					$text_body = $notify_template['email']['NotifyTemplateTypeI18n']['param02'];
	        			@eval("\$text_body = \"$text_body\";");
	        			$receiver_email=$operator_info['Operator']['email'];
					$mailsendqueue = array(
						'sender_name' => $shop_name,//发送从姓名
						'receiver_email' => $receiver_email,//接收人姓名;接收人地址
						'cc_email' => ';',//抄送人
						'bcc_email' => ';',//暗送人
						'title' => $subject,//主题 
						'html_body' => $html_body,//内容
						'text_body' => trim($text_body)==''?$html_body:$text_body,//内容
						'sendas' => 'html',
						'flag' => 0,
						'pri' => 0
		                	);
		            		App::import('Component', 'NotifyComponent');
        				$Notify = new NotifyComponent();
					$Notify->send_email($mailsendqueue, $controller->configs);
	            		}
        		}
	        }
    	}
}