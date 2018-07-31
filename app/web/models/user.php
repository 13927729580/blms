<?php

/**
 * 用户模型.
 */
class user extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'User';
//    public $hasOne = array('UserConfig' => array(
//            'className' => 'UserConfig',
//            'order' => '',
//            'dependent' => true,
//            'foreignKey' => 'user_id',
//        ),
//    );

    public $belongsTo = array(
        'UserRank' => array(
        'className' => 'UserRank',
        'conditions' => 'UserRank.id=User.rank',
        'order' => '',
        'dependent' => true,
        'foreignKey' => '',
        ), );

    public function find_user_by_id($user_id)
    {
        $user = $this->findbyid($user_id); //标记
        return $user;
    }

    /*
    	更新登录用户的$_session['User']信息
    */
    public function changeUserSession()
    {
        $userInfo = array();
        if (isset($_SESSION['User'])) {
            $user_id = $_SESSION['User']['User']['id'];
            $userInfo = $this->find('first', array('conditions' => array('User.id' => $user_id)));
            $_SESSION['User'] = $userInfo;
        }

        return $userInfo;
    }

    public function get_user_list($users_conditions)
    {
        $users = $this->find('list', array('conditions' => $users_conditions,
                    'fields' => array('User.id'), ));

        return $users;
    }

    public function get_user_all($user_conditions)
    {
        $users = $this->find('all', array('conditions' => $user_conditions,
                    'fields' => array('User.id', 'User.name', 'User.img01'), ));

        return $users;
    }
    /**
     * get_module_infos方法，获取模块数据.
     *
     * @param  查询参数集合
     *
     * @return $user_infos 根据param，返回数组
     */
    public function get_module_infos($params){
	    $conditions = '';
	    $limit = 10;
	    if (isset($params['limit'])) {
	        $limit = $params['limit'];
	    }
	    $order = 'created Desc';
	    if (isset($params['order'])) {
	        $order = $params['order'];
	    }
	    $conditions['User.status'] = 1;
	    $user_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'User.'.$order, 'fields' => 'User.id,User.name,User.img01,User.created'));
	    return $user_infos;
    }
    
    function register_notify($user_id=0,$controller_obj=null){
    		$user_detail=$this->findById($user_id);
    		if(empty($user_detail)||$user_detail['User']['status']=='0')return;
    		
    		extract($user_detail['User'],EXTR_PREFIX_ALL,'User');
    		
    		$SynchroUser = ClassRegistry::init('SynchroUser');
    		$NotifyTemplateType = ClassRegistry::init('NotifyTemplateType');
    		
    		$notify_template=$NotifyTemplateType->typeformat('register_validate');
    		if(empty($notify_template))return;
    		
    		$server_host=isset($controller_obj->server_host)?$controller_obj->server_host:'';
		if($server_host==''){
			$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
			$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
			$post=isset($_SERVER['SERVER_PORT'])?$_SERVER['SERVER_PORT']:'80';
			$server_host = $http_type.$host.($post!='80'&&$post!='443'?(":".$post):'');
		}
    		App::import('Component', 'Notify');
		$Notify = new NotifyComponent();
    		
    		$WechatUser_detail=$SynchroUser->find('list',array('fields'=>'SynchroUser.id,SynchroUser.account','conditions'=>array('SynchroUser.type'=>'wechat','SynchroUser.user_id'=>$user_id,'SynchroUser.status'=>'1')));
    		if(!empty($WechatUser_detail)){
    			//pr($WechatUser_detail);
    		}else if(trim($user_detail['User']['email'])!=''){
    			$notify_template_detail=isset($notify_template['email'])?$notify_template['email']:array();
    			if(empty($notify_template_detail))return;
    			
			$subject=$notify_template_detail['NotifyTemplateTypeI18n']['title'];
			@eval("\$subject = \"$subject\";");
			$html_body = addslashes($notify_template_detail['NotifyTemplateTypeI18n']['param01']);
			@eval("\$html_body = \"$html_body\";");
			$text_body = $notify_template_detail['NotifyTemplateTypeI18n']['param02'];
			@eval("\$text_body = \"$text_body\";");
    			$mail_send_queue = array(
		                'id' => '',
		                'sender_name' => isset($controller_obj->configs['shop_name'])?$controller_obj->configs['shop_name']:'',
		                'receiver_email' => $User_email,//接收人姓名;接收人地址
		                'cc_email' => "",
		                'bcc_email' => "",
		                'title' => $subject,
		                'html_body' => $html_body,
		                'text_body' => $text_body,
		                'sendas' => 'html',
		                'flag' => 0,
		                'pri' => 0
	        	);
	        	$Notify->send_email($mail_send_queue, isset($controller_obj->configs)?$controller_obj->configs:array());
    		}else if(trim($user_detail['User']['mobile'])!=''){
    			
    		}
    }
    
    function user_detail(){
    		$result=array();
    		$user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
    		if(!empty($user_id))$result=$_SESSION['User'];
    		$UserApp = ClassRegistry::init('UserApp');
    		$app_share = $UserApp->app_status();
        	if(!empty($app_share))$result['app_share']=$app_share;
        	return $result;
    }
}
