<?php

App::import('Vendor', 'Opauth', array('file' => 'Opauth.php'));
class SynchrosController extends AppController
{
    public $name = 'Synchros';
    public $helpers = array('Html');
    public $uses = array('User','SynchroUser','RegionI18n','UserApp','Template','NotifyTemplateType','OpenUser');
    public $components = array('RequestHandler','Cookie','Session','Captcha','Notify');
    public $userAppNames = array();
    
    public function beforeFilter(){
    		parent::beforeFilter();
    		//禁用注册
    		if(isset($this->configs['enable_registration_closed'])&&$this->configs['enable_registration_closed']=='1')$this->redirect('/');
    }
    
    /*
        获取API配置信息
        return $config
    */
    public function get_api_config()
    {
        $user_app_name = array('wechatauth' => '微信');
        $config = array();
        $Strategy = array();
        $key_list = array();
        $app_list = $this->UserApp->find('all', array('conditions' => array('UserApp.status' => '1','UserApp.location'=>array(0,2)), 'fields' => array('UserApp.name', 'UserApp.type', 'UserApp.app_key', 'UserApp.app_code')));
        if (!empty($app_list)) {
            foreach ($app_list as $k => $v) {
                $key_list['key'] = $v['UserApp']['app_key'];
                $key_list['secret'] = $v['UserApp']['app_code'];
                $Strategy[$v['UserApp']['type']] = $key_list;
                $key_list = array();
                $user_app_name[$v['UserApp']['type']] = $v['UserApp']['name'];
            }
        }
        if (!empty($Strategy)) {
            $this->userAppNames = $user_app_name;
            $config = array(
                'path' => '/synchros/opauth/',
                'callback_url' => '/synchros/callback/',
                'security_salt' => 'LDFmiilYf8Fyw5W10rx4W1KsVrabQCnpBzzpTBWA5vJidQKDx8pMJbmw28R1C4m',
                'Strategy' => $Strategy,
            );

            return $config;
        }

        return false;
    }

    //授权加载
    public function opauth()
    {
    	$_GET=$this->clean_xss($_GET);
        if(isset($_GET['action_code'])&&!empty($_GET['action_code'])){
            $action_code=$_GET['action_code'];
            $_SESSION['API_Action_Code']=$action_code;
        }
        $config = $this->get_api_config();
        $o2 = new Opauth($config);
    }

    //回调函数
    public function callback(){
        /*
            判断是否为手机版
        */
        $is_mobileflag = false;
        if ($this->is_mobile) {
            $is_mobileflag = true;
        }
        $_GET=$this->clean_xss($_GET);
        if (isset($_GET['code']) && isset($_GET['state']) && $_GET['state'] == 'qq') {
            //QQ互联登陆
            $this->redirect('/synchros/opauth/qq/qq_callback?code='.$_GET['code']);
        }
        if (isset($_SESSION['wechatuser'])) {
            $_SESSION['opauth'] = $_SESSION['wechatuser'];
            unset($_SESSION['wechatuser']);
        } else {
            $config = $this->get_api_config();
            $Opauth = new Opauth($config, false);
        }
        $response = array();
        $response = isset($_SESSION['opauth']) ? $_SESSION['opauth'] : array();
        if (isset($response['auth']['uid'])) {
            if(isset($_SESSION['API_Action_Code'])&&!empty($_SESSION['API_Action_Code'])){
                $action_code=$_SESSION['API_Action_Code'];
                $this->set('action_code',$_SESSION['API_Action_Code']);
                unset($_SESSION['API_Action_Code']);
            }
            $u_id = isset($response['auth']['raw']['openid']) ? $response['auth']['raw']['openid'] : $response['auth']['uid'];
            $local_me = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.account' => $u_id, 'type' => $response['auth']['provider'])));
            if (!empty($local_me)&&isset($action_code)&&$action_code=="api_bind") {
                	$this->flash("<font color='red'>该用户已被绑定</font>", array('controller' => '/'), 5);
                	return;
            }
            if (!empty($local_me)) {
                //已绑定用户
                //未登录绑定用户
                if (!isset($_SESSION['User']['User'])) {
                    $users = $this->User->find('first', array('conditions' => array('User.id' => $local_me['SynchroUser']['user_id'])));
                    $_SESSION['User'] = $users;
                    $x = $users['User']['id'];
                    $this->User->updateAll(array('User.last_login_time' => "'".gmdate('Y-m-d H:i:s', time())."'"), array('User.id' => $x));
                    setcookie('user_info', serialize($users), time() + 60 * 60 * 24 * 14, '/');
                    if(!isset($response['auth']['organization'])||$response['auth']['organization']==''){
                    	$this->SynchroUser->updateAll(array('SynchroUser.oauth_token' => "'".$response['auth']['credentials']['token']."'"), array('SynchroUser.id' => $local_me['SynchroUser']['id']));
                    }
                    if ($is_mobileflag) {
                        $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users/';
                    } else {
                        $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users/';
                    }
                    $this->redirect($back_url);
                } else {
                    $us = $this->User->find('first', array('conditions' => array('User.id' => $local_me['SynchroUser']['user_id'])));
                    if ($_SESSION['User']['User']['id'] != $us['User']['id']) {
                        $msg = '该用户已被绑定';
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");window.location.href="'.$this->base.'/users"</script>';
                        die();
                    } else {
                    	if(isset($local_me['SynchroUser']))$this->SynchroUser->updateAll(array('status'=>"'1'"),array('id'=>$local_me['SynchroUser']['id']));
                        if ($is_mobileflag) {
                            $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users/';
                        } else {
                            $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] :  '/users/';
                        }
                        $this->redirect($back_url);
                    }
                }
            } else {
                if (isset($_SESSION['User']['User'])) {
	                    $user_data = array(
	                        'user_id' => $_SESSION['User']['User']['id'],
	                        'nick'=>isset($response['auth']['info']['nickname'])?$response['auth']['info']['nickname']:(isset($response['auth']['info']['name'])?$response['auth']['info']['name']:''),
	                        'account' => $u_id,
	                        'oauth_token' => $response['auth']['credentials']['token'],
	                        'type' => $response['auth']['provider'],
	                        'oauth_token_secret' => '',
	                        'created' => date('Y-m-d H:i:s', time()),
	                    );
	                    $this->SynchroUser->save($user_data);
	                    if($response['auth']['provider']=='wechat')$this->OpenUser->subscribe_point($u_id,isset($this->configs['wechat_subscribe_point'])?$this->configs['wechat_subscribe_point']:0,'R');
                    if ($is_mobileflag) {
                        	$back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users/';
                    } else {
                        	$back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] :  '/users/';
                    }
                    $this->redirect($back_url);
                } else {
                    $this->pageTitle = '账号绑定 - '.$this->configs['shop_title'];                    //页面初始化
                    //当前位置
                    $this->ur_heres[] = array('name' => '账号绑定','url' => '');
                    $this->set('ur_heres', $this->ur_heres);
                    $this->set('u_id', $u_id);
                    $this->set('response', $response);
                    $this->set('userAppNames', $this->userAppNames);

                    if ($is_mobileflag) {
                        $this->layout = 'mobile/default_full';
                        $this->render('mobile/callback');
                        Configure::write('debug', 0);
                    }
                }
            }
        } else {
            $msg = '接口异常,丢失用户，授权失败！请稍后再试';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="'.$this->base.'/users"</script>';
            die();
        }
    }

    //检查授权状态
    public function checktoken($type)
    {
        $result['flag'] = 0;
        $result['status'] = '';
        $syn_config = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.User_id' => $_SESSION['User']['User']['id'], 'type' => $type)));
        if (!empty($syn_config)) {
            if ($syn_config['SynchroUser']['status'] == '0') {
                $this->SynchroUser->updateAll(array('SynchroUser.status' => '1'), array('SynchroUser.id' => $syn_config['SynchroUser']['id']));
                $result['status'] = 1;
            } elseif ($syn_config['SynchroUser']['status'] == '1') {
                $this->SynchroUser->updateAll(array('SynchroUser.status' => '0'), array('SynchroUser.id' => $syn_config['SynchroUser']['id']));
                $result['status'] = 0;
            }
            $result['flag'] = 1;
        }
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        die(json_encode($result));
    }

    public function checkdata()
    {
        $this->layout = 'ajax';
        Configure::write('debug', 1);
        if ($this->RequestHandler->isPost()) {
            $type = isset($_POST['type']) && $_POST['type'] != '' ? trim($_POST['type']) : '';
            $value = isset($_POST['value']) && $_POST['value'] != '' ? trim($_POST['value']) : '';
            $result['code'] = 0;
            $result['msg'] = 'Not found data';
            switch ($type) {
                case 'email':
                    $cond['or']['User.user_sn'] = $value;
                    $cond['or']['User.email'] = $value;
                    $is_email = $this->User->find('first', array('conditions' => $cond));
                    if (!empty($is_email)) {
                        $result['msg'] = $this->ld['email_already_exists'];
                    } else {
                        $result['code'] = 1;
                        $result['msg'] = '';
                    }
                    break;
                default:
                    break;
            }
            die(json_encode($result));
        }
    }

    public function apibind()
    {
        /*
            判断是否为手机版
        */
        $is_mobileflag = false;
        $mobile_status = $this->Template->find('first', array('conditions' => array('is_default' => 1), 'fields' => array('Template.mobile_status')));
        if ((isset($_SESSION['is_mobile']) && $_SESSION['is_mobile'] == '1') || (($this->RequestHandler->isMobile() && $mobile_status['Template']['mobile_status'] == '1') && !isset($_SESSION['is_mobile']))) {
            $is_mobileflag = true;
        }
        if ($this->RequestHandler->isPost()) {
            $type = isset($this->data['type']) && trim($this->data['type']) != '' ? $this->data['type'] : '';
            $api_type = isset($this->data['api_type']) && trim($this->data['api_type']) != '' ? $this->data['api_type'] : '';
            $oauth_token = isset($this->data['oauth_token']) && trim($this->data['oauth_token']) != '' ? $this->data['oauth_token'] : '';
            $u_id = isset($this->data['u_id']) && trim($this->data['u_id']) != '' ? $this->data['u_id'] : '';
            $email = isset($this->data['email']) && trim($this->data['email']) != '' ? $this->data['email'] : '';
            $password = isset($this->data['password']) && trim($this->data['password']) != '' ? md5($this->data['password']) : '';
            $back_url="/";
            if(isset($_SESSION['login_back'])){
                $back_url = $_SESSION['login_back'];
            }else if(isset($_SESSION['User']['User'])){
                $back_url = '/user_socials/index/'.$_SESSION['User']['User']['id'];
            }
            if ($type == 'register') {
                $user_name = isset($this->data['user_name']) && trim($this->data['user_name']) != '' ? $this->data['user_name'] : '';
                $user_nickname = isset($this->data['user_nickname']) && trim($this->data['user_nickname']) != '' ? $this->data['user_nickname'] : '';
                if ($user_name != '') {
                    $username = $user_name;
                } else {
                    if ($user_nickname != '') {
                        $username = $user_nickname;
                    } else {
                        $username = $email;
                    }
                }
                //$new_user['user_sn'] = $username;
                $new_user['email'] = $email;
                if(!isset($this->data['first_name'])||$this->data['first_name']==''){
                	$new_user['first_name'] = $username;
                }else{
                	$new_user['first_name'] = $this->data['first_name'];
                }
                $new_user['name'] = $username;
                $new_user['img01'] = isset($this->data['img']) ? $this->data['img'] : '';
                $this->User->save($new_user);
                $user_info = $this->User->find('first', array('conditions' => array('User.id' => $this->User->id)));
                $_SESSION['User'] = $user_info;
                setcookie('user_info', serialize($user_info), time() + 60 * 60 * 24 * 14, '/');
                $user_data = array(
	                    'user_id' => $_SESSION['User']['User']['id'],
	                    'nick'=>isset($this->data['user_name']) && trim($this->data['user_name']) != '' ? $this->data['user_name'] : '',
	                    'account' => $u_id,
	                    'oauth_token' => $oauth_token,
	                    'type' => $api_type,
	                    'oauth_token_secret' => '',
	                    'created' => date('Y-m-d H:i:s', time())
                );
                $this->SynchroUser->save($user_data);
                if(strtolower($api_type)=='wechat'){
                		$this->notify_message($_SESSION['User']['User']['id'],$u_id);
                		$this->OpenUser->subscribe_point($u_id,isset($this->configs['wechat_subscribe_point'])?$this->configs['wechat_subscribe_point']:0,'R');
                }
                if (isset($this->configs['use_point']) && $this->configs['use_point'] == 1) {
                	$this->loadModel('UserPointLog');
	                $register = isset($this->configs['point-register']) ? $this->configs['point-register'] : 0;
	                if (isset($register) && $register > 0) {
	                	 $old_point=$user_info['User']['point'];
	                    $user_info['User']['point'] = $register;
	                    $user_info['User']['user_point'] = $register;
	                    $this->User->save($user_info['User']);
	                    $user_point_log = array('id' => '',
	                              'user_id' => $user_info['User']['id'],
	                              'point' => $old_point,
	                              'point_change' => $register,
	                              'log_type' => 'R',
	                              'system_note' => $this->ld['registration_gift_points'],
	                              'type_id' => '0',
	                            );
	                    $this->UserPointLog->save($user_point_log);
	                    $this->UserPointLog->point_notify($user_point_log);
	                }
            }
            //推荐注册
            if(isset($this->configs['share_points'])&&$this->configs['share_points']=='1'){
            		$share_identification=isset($_SESSION['share_identification'])?$_SESSION['share_identification']:(isset($_COOKIE['share_identification'])?$_COOKIE['share_identification']:'');
            		if($share_identification!=''&&isset($this->configs['recommend_points'])&&intval($this->configs['recommend_points'])>0){
            			$this->loadModel('UserPointLog');
            			$this->loadModel('ShareAffiliateLog');
            			$share_affiliate_log=$this->ShareAffiliateLog->find('first',array('conditions'=>array('ShareAffiliateLog.user_id >'=>0,'ShareAffiliateLog.identification'=>$share_identification)));
            			if(!empty($share_affiliate_log)){
            				$share_user_info=$this->User->findById($share_affiliate_log['ShareAffiliateLog']['user_id']);
            				if(!empty($share_user_info)){
            					$this->User->save(array('id'=>$user_info['User']['id'],'parent_id'=>$share_affiliate_log['ShareAffiliateLog']['user_id']));
            					$this->User->save(array('id'=>$share_affiliate_log['ShareAffiliateLog']['user_id'],'point'=>intval($share_user_info['User']['point'])+intval($this->configs['recommend_points'])));
            					$point_log_data = array(
							'id' => 0,
							'log_type'=>'T',
							'user_id' => $share_affiliate_log['ShareAffiliateLog']['user_id'],
							'point'=>$share_user_info['User']['point'],
							'point_change' =>$this->configs['recommend_points'],
							'system_note' => $this->ld['recommend_friend']
						);
        					$this->UserPointLog->save($point_log_data);
        					$this->UserPointLog->point_notify($point_log_data);
            				}
            			}
            		}
            }
            //判断是否送优惠券  start chenfan 2012/05/25
            if (constant('Product') == 'AllInOne') {
                $this->loadModel('CouponType');
                $this->loadModel('Coupon');
                $now = date('Y-m-d H:i:s');
                $coupon_type = $this->CouponType->find('all', array('conditions' => "CouponType.send_type = '4' and CouponType.send_start_date <= '".$now."' and  CouponType.send_end_date >='".$now."'"));
                if (is_array($coupon_type) && sizeof($coupon_type) > 0) {
                    $coupon_arr_list = $this->Coupon->find('list', array('conditions' => array('1=1'), 'fields' => array('Coupon.sn_code')));
                    $coupon_arr = array();
                    if (is_array($coupon_arr_list) && sizeof($coupon_arr_list) > 0) {
                        foreach ($coupon_arr_list as $k => $v) {
                            $coupon_arr[] = $v;
                        }
                    }
                    $coupon_count = count($coupon_arr);
                    $num = 0;
                    if ($coupon_count > 0) {
                        $num = $coupon_arr[$coupon_count - 1];
                    }
                    foreach ($coupon_type as $k => $v) {
                        if (isset($coupon_sn)) {
                            $num = $coupon_sn;
                        }
                        $num = substr($num, 2, 10);
                        $num = $num ? floor($num / 10000) : 100000;
                        $coupon_sn = $v['CouponType']['prefix'].$num.str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                        $coupon = array(
                                  'id' => '',
                                  'coupon_type_id' => $v['CouponType']['id'],
                                  'sn_code' => $coupon_sn,
                                  'user_id' => $user_info['User']['id'],
                        );
                        $this->Coupon->save($coupon);
                    }
                }
            }
                $this->redirect($back_url);
            } elseif ($type == 'login') {
                $login_type = isset($this->data['login_type']) && trim($this->data['login_type']) != '' ? $this->data['login_type'] : 'user_sn';
                $result['code'] = 0;
                $result['msg'] = $this->ld['login_name'].'或密码错误';
                if ($login_type == 'user_sn') {
                    $user_cond['User.user_sn'] = $email;
                } elseif ($login_type == 'email') {
                    $user_cond['User.email'] = $email;
                } else {
                    $user_cond['User.mobile'] = $email;
                }
                $user_cond['User.password'] = $password;
                $userInfo = $this->User->find('first', array('conditions' => $user_cond));
                if (!empty($userInfo)) {
                    $_SESSION['User'] = $userInfo;
                    setcookie('user_info', serialize($userInfo), time() + 60 * 60 * 24 * 14, '/');
                    $user_data = array(
                        'user_id' => $userInfo['User']['id'],
                        'nick'=>isset($this->data['user_name']) && trim($this->data['user_name']) != '' ? $this->data['user_name'] : '',
                        'account' => $u_id,
                        'oauth_token' => $oauth_token,
                        'type' => $api_type,
                        'oauth_token_secret' => '',
                        'created' => date('Y-m-d H:i:s', time()),
                    );
                    $this->SynchroUser->save($user_data);
                    if($api_type=='wechat')$this->OpenUser->subscribe_point($u_id,isset($this->configs['wechat_subscribe_point'])?$this->configs['wechat_subscribe_point']:0,'R');
                    $result['code'] = 1;
                    $result['msg'] = $back_url;
                    if(strtolower($api_type)=='wechat'){
                    	$this->notify_message($userInfo['User']['id'],$u_id);
                    }
                }
                $this->layout = 'ajax';
                Configure::write('debug', 0);
                die(json_encode($result));
            } elseif ($type == 'fast_login') {
                $user_name = isset($this->data['user_name']) && trim($this->data['user_name']) != '' ? $this->data['user_name'] : '';
                $user_nickname = isset($this->data['user_nickname']) && trim($this->data['user_nickname']) != '' ? $this->data['user_nickname'] : '';
                if ($user_name != '') {
                    $username = $user_name;
                } else {
                    if ($user_nickname != '') {
                        $username = $user_nickname;
                    } else {
                        $username = $email;
                    }
                }
                //$new_user['user_sn'] = $u_id.'@'.$api_type;
                $new_user['email'] = $email;
                if(!isset($this->data['first_name'])||$this->data['first_name']==''){
                	$new_user['first_name'] = $username;
                }else{
                	$new_user['first_name'] = $this->data['first_name'];
                }
                $new_user['name'] = $username;
                $new_user['img01'] = isset($this->data['img']) ? $this->data['img'] : '';
                $this->User->save($new_user);
                $user_info = $this->User->find('first', array('conditions' => array('User.id' => $this->User->id)));
                $_SESSION['User'] = $user_info;
                setcookie('user_info', serialize($user_info), time() + 60 * 60 * 24 * 14, '/');
                $user_data = array(
                        'user_id' => $_SESSION['User']['User']['id'],
                        'nick'=>$user_name,
                        'account' => $u_id,
                        'oauth_token' => $oauth_token,
                        'type' => $api_type,
                        'oauth_token_secret' => '',
                        'created' => date('Y-m-d H:i:s', time()),
                    );
                $this->SynchroUser->save($user_data);
                if(strtolower($api_type)=='wechat'){
                	$this->notify_message($_SESSION['User']['User']['id'],$u_id);
                	$this->OpenUser->subscribe_point($u_id,isset($this->configs['wechat_subscribe_point'])?$this->configs['wechat_subscribe_point']:0,'R');
                }
                if (isset($this->configs['use_point']) && $this->configs['use_point'] == 1) {
                	$this->loadModel('UserPointLog');
	                $register = isset($this->configs['point-register']) ? $this->configs['point-register'] : 0;
	                if (isset($register) && $register > 0) {
	                	 $old_point=$user_info['User']['point'];
	                    $user_info['User']['point'] = $register;
	                    $user_info['User']['user_point'] = $register;
	                    $this->User->save($user_info['User']);
	                    $user_point_log = array('id' => '',
	                              'user_id' => $user_info['User']['id'],
	                              'point' => $old_point,
	                              'point_change' => $register,
	                              'log_type' => 'R',
	                              'system_note' => $this->ld['registration_gift_points'],
	                              'type_id' => '0',
	                            );
	                    $this->UserPointLog->save($user_point_log);
	                    $this->UserPointLog->point_notify($user_point_log);
	                }
            }
            //推荐注册
            if(isset($this->configs['share_points'])&&$this->configs['share_points']=='1'){
            		$share_identification=isset($_SESSION['share_identification'])?$_SESSION['share_identification']:(isset($_COOKIE['share_identification'])?$_COOKIE['share_identification']:'');
            		if($share_identification!=''&&isset($this->configs['recommend_points'])&&intval($this->configs['recommend_points'])>0){
            			$this->loadModel('UserPointLog');
            			$this->loadModel('ShareAffiliateLog');
            			$share_affiliate_log=$this->ShareAffiliateLog->find('first',array('conditions'=>array('ShareAffiliateLog.user_id >'=>0,'ShareAffiliateLog.identification'=>$share_identification)));
            			if(!empty($share_affiliate_log)){
            				$share_user_info=$this->User->findById($share_affiliate_log['ShareAffiliateLog']['user_id']);
            				if(!empty($share_user_info)){
            					$this->User->save(array('id'=>$user_info['User']['id'],'parent_id'=>$share_affiliate_log['ShareAffiliateLog']['user_id']));
            					$this->User->save(array('id'=>$share_affiliate_log['ShareAffiliateLog']['user_id'],'point'=>intval($share_user_info['User']['point'])+intval($this->configs['recommend_points'])));
            					$point_log_data = array(
							'id' => 0,
							'log_type'=>'T',
							'user_id' => $share_affiliate_log['ShareAffiliateLog']['user_id'],
							'point'=>$share_user_info['User']['point'],
							'point_change' =>$this->configs['recommend_points'],
							'system_note' => $this->ld['recommend_friend']
						);
        					$this->UserPointLog->save($point_log_data);
        					$this->UserPointLog->point_notify($point_log_data);
            				}
            			}
            		}
            }
            //判断是否送优惠券  start chenfan 2012/05/25
            if (constant('Product') == 'AllInOne') {
                $this->loadModel('CouponType');
                $this->loadModel('Coupon');
                $now = date('Y-m-d H:i:s');
                $coupon_type = $this->CouponType->find('all', array('conditions' => "CouponType.send_type = '4' and CouponType.send_start_date <= '".$now."' and  CouponType.send_end_date >='".$now."'"));
                if (is_array($coupon_type) && sizeof($coupon_type) > 0) {
                    $coupon_arr_list = $this->Coupon->find('list', array('conditions' => array('1=1'), 'fields' => array('Coupon.sn_code')));
                    $coupon_arr = array();
                    if (is_array($coupon_arr_list) && sizeof($coupon_arr_list) > 0) {
                        foreach ($coupon_arr_list as $k => $v) {
                            $coupon_arr[] = $v;
                        }
                    }
                    $coupon_count = count($coupon_arr);
                    $num = 0;
                    if ($coupon_count > 0) {
                        $num = $coupon_arr[$coupon_count - 1];
                    }
                    foreach ($coupon_type as $k => $v) {
                        if (isset($coupon_sn)) {
                            $num = $coupon_sn;
                        }
                        $num = substr($num, 2, 10);
                        $num = $num ? floor($num / 10000) : 100000;
                        $coupon_sn = $v['CouponType']['prefix'].$num.str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                        $coupon = array(
                                  'id' => '',
                                  'coupon_type_id' => $v['CouponType']['id'],
                                  'sn_code' => $coupon_sn,
                                  'user_id' => $user_info['User']['id'],
                        );
                        $this->Coupon->save($coupon);
                    }
                }
            }
                
                $this->redirect($back_url);
            }
        }
        $this->redirect('/');
    }
    
    /*
    		微信绑定成功后通知
    */
    function notify_message($user_id,$touser){
    		$user_data = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
    		if(!empty($user_data)){
			$notify_template_info=$this->NotifyTemplateType->typeformat("wechat_bind","wechat");
			$notify_template=isset($notify_template_info['wechat'])?$notify_template_info['wechat']:array();
			if(empty($notify_template))return;
			$wechat_params=$this->NotifyTemplateType->wechatparamsformat($notify_template);
			$action_content="您已经成功绑定了微信，以后可以用微信登陆和接收通知!";
   			$user_name=$user_data['User']['name'];
   			$action_time=date('Y-m-d H:i:s');
   			$action_status_desc="扫码绑定成功";
   			$action_desc="如非本人操作,请及时联系客服";
   			$wechat_message=array();
   			foreach($wechat_params as $k=>$v){
   				$wechat_message[$k]=array(
   					'value'=>isset($$v)?$$v:''
   				);
   			}
	   		$wechat_post=array(
	   			'touser'=>$touser,
	   			'template_id'=>$notify_template['NotifyTemplateTypeI18n']['param03'],
	   			'url'=>$this->server_host,
	   			'data'=>$wechat_message
	   		);
	   		$this->Notify->wechat_message($wechat_post);
    		}
    }

    /*
        微信扫描二维码登录返回处理
    */
    public function wechatcallback()
    {
        if (!empty($_GET['code'])){
            $code = $_GET['code'];
            $config = $this->get_api_config();
            
            $appid = $config['Strategy']['Wechat']['key'];
            $secret = $config['Strategy']['Wechat']['secret'];
            $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
            $params = array(
                'appid' => $appid,
                'secret' => $secret,
                'code' => $code,
                'grant_type' => 'authorization_code',
            );
            $results = $this->https_request($get_token_url, $params);
            if (empty($results)) {
                $error = array(
                    'code' => 'Get access token error',
                    'message' => 'Failed when attempting to get access token',
                    'raw' => array(
                        'headers' => $results,
                    ),
                );
            } else {
                if (empty($results['access_token'])) {
                    $error = array(
                        'code' => 'Get access token error',
                        'message' => 'Failed when attempting to get access token',
                        'raw' => array(
                            'headers' => $results,
                        ),
                    );
                } else {
                    $access_token = $results['access_token'];
                    $get_user_url = 'https://api.weixin.qq.com/sns/userinfo';
                    $user_results = $this->https_request($get_user_url, array('access_token' => $results['access_token'], 'openid' => $results['openid'], 'lang' => 'zh_CN'));
                    if (isset($results['openid'])) {
                        $wechatuser['auth'] = array(
                            'provider' => 'wechat',
                            'uid' => $user_results['openid'],
                            'info' => array(
                                'name' => $user_results['nickname'],
                                'sex' => $user_results['sex'],
                                'nickname' => $user_results['nickname'],
                                'image' => $user_results['headimgurl'],
                            ),
                            'credentials' => array(
                                'token' => $results['access_token'],
                                'expires' => date('c', time() + $results['expires_in']),
                            ),
                            'raw' => $results,
                        );
                    } else {
                        $error = array(
                            'code' => 'Get wechat user error',
                            'message' => 'Failed when attempting to get access token',
                            'raw' => array(
                                'headers' => $user_results,
                            ),
                        );
                    }
                }
            }
            if (isset($wechatuser)) {
                $_SESSION['wechatuser'] = $wechatuser;
                $this->redirect('/synchros/callback/');
            } else {
                $msg = isset($error) ? $error['code'].'/r/n'.$error['message'] : '接口异常,丢失用户，授权失败！请稍后再试';
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="'.$this->base.'/users/login";</script>';
                die();
            }
        } else {
            $this->redirect('/');
        }
    }
    
    function ajax_check_wechat_subscribe(){
		Configure::write('debug',0);
		$this->layout = 'ajax';
		$result=array();
		$result['code']='0';
    		$user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
    		if(!empty($user_id)){
    			$wechat_user=$this->SynchroUser->find('first',array('fields'=>'account','conditions'=>array('type'=>'wechat','user_id'=>$user_id,'status'=>'1')));
    			if(!empty($wechat_user)){
    				$wechat_account=$wechat_user['SynchroUser']['account'];
    				$open_user=$this->OpenUser->find('first',array('fields'=>'id,openid,subscribe','conditions'=>array('openid'=>$wechat_account)));
    				if(!empty($open_user)&&$open_user['OpenUser']['subscribe']=='1'){
    					$result['code']='1';
    				}
    			}
    		}
    		die(json_encode($result));
    }

    /*
        调用接口
    */
    private function https_request($url, $data = null){
	        $curl = curl_init();
	        curl_setopt($curl, CURLOPT_URL, $url);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	        if (!empty($data)) {
		            curl_setopt($curl, CURLOPT_POST, 1);
		            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	        }
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	        $output = curl_exec($curl);
	        curl_close($curl);
	        return json_decode($output, true);
    }
    
    function ajax_qywechat_config(){
    		Configure::write('debug', 1);
    		$this->layout="ajax";
    		$organization_id=isset($_POST['organization_id'])?$_POST['organization_id']:0;
    		
    		$this->loadModel('OrganizationApp');
    		$this->loadModel('OrganizationAppConfigValue');
    		$result=array();
    		$result['code']='0';
    		$result['data']=array();
    		$last_page=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:$this->server_host;
    		$organization_app_config=array();
    		$conditions=array();
    		$conditions['OrganizationApp.organization_id']=0;
    		$conditions['OrganizationApp.type']='QYWechat';
    		$conditions['OrganizationApp.status']='1';
    		$organization_app=$this->OrganizationApp->find('first',array('conditions'=>$conditions));
    		if(!empty($organization_app)){
    			$organization_app_id=$organization_app['OrganizationApp']['id'];
    			$organization_app_config=$this->OrganizationAppConfigValue->find('list',array('fields'=>array('config_code','config_value'),'conditions'=>array('organization_app_id'=>$organization_app_id)));
    		}
    		if(!empty($organization_app_config)){
    			$result['code']='1';
    			$result['data']['user_agent']=isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
    			$result['data']['Config']=$organization_app_config;
    			$SuiteId=isset($organization_app_config['SuiteId'])?$organization_app_config['SuiteId']:'';
    			$SuiteSecret=isset($organization_app_config['SuiteSecret'])?$organization_app_config['SuiteSecret']:'';
    			$pre_auth_code=isset($organization_app_config['PreAuthCode'])?$organization_app_config['PreAuthCode']:'';
    			$redirect_uri= $this->server_host.'/synchros/qywechat_auth/'.$organization_id.'?suite_id='.$SuiteId.'&suite_secret='.$SuiteSecret;
    			$request_link="https://qy.weixin.qq.com/cgi-bin/loginpage?suite_id={$SuiteId}&pre_auth_code={$pre_auth_code}&redirect_uri={$redirect_uri}&state=SEEVIA";
    			$result['data']['Authorization']=$request_link;
    		}
    		die(json_encode($result));
    }
    
    function qywechat_auth($organization_id=0){
    		Configure::write('debug', 1);
    		$this->layout="ajax";
    		
    		$this->loadModel('Organization');
    		$this->loadModel('OrganizationApp');
    		$this->loadModel('OrganizationAppConfigValue');
    		if(isset($_REQUEST['suite_id'])&&isset($_REQUEST['auth_code'])){
    			$qywechat_organization=$this->get_qywechat_organization($_REQUEST['auth_code']);
    			if(empty($qywechat_organization))$this->redirect('/pages/home');
    			if(empty($organization_id)){
    				$corp_name=$qywechat_organization['auth_corp_info']['corp_name'];
    				$corp_full_name=isset($qywechat_organization['auth_corp_info']['corp_full_name'])&&trim($qywechat_organization['auth_corp_info']['corp_full_name'])!=''?$qywechat_organization['auth_corp_info']['corp_full_name']:$corp_name;
    				$organization_cond=array();
    				$organization_cond['or']['Organization.name']=$corp_full_name;
    				$organization_cond['or']['Organization.abbreviation']=$corp_full_name;
    				$organization_cond['Organization.manage_user']=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
    				$organization_info=$this->Organization->find('first',array('conditions'=>$organization_cond));
    				if(empty($organization_info)||$organization_info['Organization']['status']=='0'){
    					$organization_data=array(
    						'id'=>isset($organization_info['Organization'])?$organization_info['Organization']['id']:0,
    						'manage_user'=>isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0,
    						'name'=>$corp_full_name,
    						'abbreviation'=>$corp_name,
    						'contacts'=>isset($qywechat_organization['auth_user_info']['name'])?$qywechat_organization['auth_user_info']['name']:'',
    						'contact_way'=>isset($qywechat_organization['auth_user_info']['email'])?$qywechat_organization['auth_user_info']['email']:'',
    						'logo'=>isset($qywechat_organization['auth_corp_info']['corp_square_logo_url'])&&$qywechat_organization['auth_corp_info']['corp_square_logo_url']!=''?$qywechat_organization['auth_corp_info']['corp_square_logo_url']:(isset($qywechat_organization['auth_corp_info']['corp_round_logo_url'])?$qywechat_organization['auth_corp_info']['corp_round_logo_url']:'')
    					);
    					$this->Organization->save($organization_data);
    					$organization_id=$this->Organization->id;
    				}else{
    					$organization_id=$organization_info['Organization']['id'];
    				}
    			}
	    		$conditions=array();
	    		$conditions['OrganizationApp.organization_id >']=0;
	    		$conditions['OrganizationApp.organization_id']=$organization_id;
	    		$conditions['OrganizationApp.type']='QYWechat';
	    		$organization_app=$this->OrganizationApp->find('first',array('conditions'=>$conditions));
	    		if(empty($organization_app)||$organization_app['OrganizationApp']['status']=='0'){
	    			$organization_app_data=array(
	    				'id'=>isset($organization_app['OrganizationApp'])?$organization_app['OrganizationApp']['id']:0,
	    				'organization_id'=>$organization_id,
	    				'type'=>'QYWechat',
	    				'status'=>'1'
	    			);
	    			$this->OrganizationApp->save($organization_app_data);
	    			$organization_app_id=$this->OrganizationApp->id;
	    		}else{
	    			$organization_app_id=$organization_app['OrganizationApp']['id'];
	    		}
    			if(!empty($qywechat_organization)){
    				$organization_configs=$this->OrganizationAppConfigValue->find('list',array('fields'=>array('config_code','id'),'conditions'=>array('organization_app_id'=>$organization_app_id)));
    				$auth_data=array(
    					'id'=>isset($organization_configs['AuthCorpid'])?$organization_configs['AuthCorpid']:0,
    					'organization_app_id'=>$organization_app_id,
    					'config_code'=>'AuthCorpid',
    					'config_value'=>$qywechat_organization['auth_corp_info']['corpid']
    				);
    				$this->OrganizationAppConfigValue->save($auth_data);
    				
    				$permanent_code_data=array(
    					'id'=>isset($organization_configs['PermanentCode'])?$organization_configs['PermanentCode']:0,
    					'organization_app_id'=>$organization_app_id,
    					'config_code'=>'PermanentCode',
    					'config_value'=>$qywechat_organization['permanent_code']
    				);
    				$this->OrganizationAppConfigValue->save($permanent_code_data);
    				
    				$auto_token_data=array(
    					'id'=>isset($organization_configs['AuthToken'])?$organization_configs['AuthToken']:0,
    					'organization_app_id'=>$organization_app_id,
    					'config_code'=>'AuthToken',
    					'config_value'=>$qywechat_organization['access_token']
    				);
    				$this->OrganizationAppConfigValue->save($auto_token_data);
    				
    				$auto_token_express_time_data=array(
    					'id'=>isset($organization_configs['AuthTokenExpireTime'])?$organization_configs['AuthTokenExpireTime']:0,
    					'organization_app_id'=>$organization_app_id,
    					'config_code'=>'AuthTokenExpireTime',
    					'config_value'=>time()+$qywechat_organization['expires_in']-660
    				);
    				$this->OrganizationAppConfigValue->save($auto_token_express_time_data);
    				
    				$AgentId=isset($qywechat_organization['auth_info']['agent'][0]['agentid'])?$qywechat_organization['auth_info']['agent'][0]['agentid']:(isset($qywechat_organization['auth_info']['agent']['agentid'])?$qywechat_organization['auth_info']['agent']['agentid']:'');
    				if($AgentId!=''){
	    				$AgentId_data=array(
	    					'id'=>isset($organization_configs['AgentId'])?$organization_configs['AgentId']:0,
	    					'organization_app_id'=>$organization_app_id,
	    					'config_code'=>'AgentId',
	    					'config_value'=>$AgentId
	    				);
	    				$this->OrganizationAppConfigValue->save($AgentId_data);
    				}
    			}else{
    				$this->OrganizationApp->updateAll(array('status'=>"'0'"),array('id'=>$organization_app_id));
    			}
    			$this->redirect('/organizations/application/'.$organization_id);
    		}
    		$this->redirect('/pages/home');
    }
    
    //获取企业授权信息
    function get_qywechat_organization($auth_code=''){
    		$qywechat_organization=array();
    		$conditions=array();
    		$conditions['OrganizationApp.organization_id']=0;
    		$conditions['OrganizationApp.type']='QYWechat';
    		$conditions['OrganizationApp.status']='1';
    		$organization_app=$this->OrganizationApp->find('first',array('conditions'=>$conditions));
    		if(empty($organization_app))return $qywechat_organization;
    		$organization_app_id=$organization_app['OrganizationApp']['id'];
    		$organization_app_config=$this->OrganizationAppConfigValue->find('list',array('fields'=>array('config_code','config_value'),'conditions'=>array('organization_app_id'=>$organization_app_id)));
    		if(empty($organization_app_config))return $qywechat_organization;
		$SuiteId=isset($organization_app_config['SuiteId'])?$organization_app_config['SuiteId']:'';
    		$suite_access_token=isset($organization_app_config['SuiteToken'])?$organization_app_config['SuiteToken']:'';
		$get_token_url = 'https://qyapi.weixin.qq.com/cgi-bin/service/get_permanent_code?suite_access_token='.$suite_access_token;
		$params = array(
			'suite_id' => $SuiteId,
			'auth_code' => $auth_code
		);
		$results = $this->https_request($get_token_url, json_encode($params));
		$permanent_code = isset($results['permanent_code'])?$results['permanent_code']:false;
		if ($permanent_code){
			$qywechat_organization=$results;
		}
		return $qywechat_organization;
    }
    
    function qywechatcallback($organization_app_id=0){
		$this->pageTitle = '账号绑定 - '.$this->configs['shop_title'];//页面初始化
		//当前位置
		$this->ur_heres[] = array('name' => '账号绑定','url' => '');
    		
    		$this->loadModel('OrganizationApp');
    		$this->loadModel('OrganizationAppConfigValue');
    		$this->loadModel('OrganizationMember');
    		
    		$conditions=array();
    		$conditions['OrganizationApp.organization_id']=0;
    		$conditions['OrganizationApp.type']='QYWechat';
    		$conditions['OrganizationApp.status']='1';
    		$organization_app=$this->OrganizationApp->find('first',array('conditions'=>$conditions,'order'=>'organization_id'));
    		if(!empty($organization_app)){
    			$login_user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
    			$back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] :  '/users/';
    			if(isset($_REQUEST['auth_code'])&&isset($_REQUEST['state'])&&$_REQUEST['state']=='SEEVIA'){
	    			$organization_app_id=$organization_app['OrganizationApp']['id'];
	    			$organization_configs=$this->OrganizationAppConfigValue->find('list',array('fields'=>array('config_code','config_value'),'conditions'=>array('organization_app_id'=>$organization_app_id)));
	    			$provider_access_token=isset($organization_configs['ProviderSecretToken'])?$organization_configs['ProviderSecretToken']:'';
	    			$login_user_url="https://qyapi.weixin.qq.com/cgi-bin/service/get_login_info?access_token={$provider_access_token}";
	    			$login_user_params=array(
	    				'auth_code'=>isset($_REQUEST['auth_code'])?$_REQUEST['auth_code']:''
	    			);
	    			$qywechat_user = $this->https_request($login_user_url, json_encode($login_user_params));
	    			if(isset($qywechat_user['user_info'])){
	    				$wechatuser['auth'] = array(
		                            'provider' => 'qywechat',
		                            'uid' => $qywechat_user['user_info']['userid'],
		                            'info' => array(
			                                'name' => $qywechat_user['user_info']['name'],
			                                'nickname' => $qywechat_user['user_info']['name'],
			                                'image' => $qywechat_user['user_info']['avatar'],
			            			    'email'=>$qywechat_user['user_info']['email']
		                            ),
		                            'credentials' => array(
		                                	'token' => $qywechat_user['user_info']['userid'],
		                                	'expires' => date('c', time()),
		                            ),
		                            'raw' => $qywechat_user,
		                     );
		                	$this->set('response',$wechatuser);
		                	$qy_corp_id=isset($qywechat_user['corp_info']['corpid'])?$qywechat_user['corp_info']['corpid']:'';
		            		$qywechat_info=$this->SynchroUser->find('first',array('conditions'=>array('type'=>'qywechat','user_id <>'=>0,'account'=>$qywechat_user['user_info']['userid'])));
		            		if(!empty($qywechat_info)){
		            			$qywechat_user_id=$qywechat_info['SynchroUser']['user_id'];
		            			$user_info=$this->User->findById($qywechat_user_id);
						if(empty($user_info)){
							$this->SynchroUser->deleteAll(array('id'=>$qywechat_info['SynchroUser']['id']));
						}else if(!empty($user_info)&&$user_info['User']['mobile']!=''){
							$_SESSION['User'] = $user_info;
					              setcookie('user_info', serialize($user_info), time() + 60 * 60 * 24 * 14, '/');
					              $this->redirect($back_url);
						}
		            		}else{
			            		$login_user=array();
			            		if(!empty($login_user_id)){
			            			$login_user=$this->User->findById($login_user_id);
			            		}
			            		if(!empty($login_user)){
			            			$qywechat_info=$this->SynchroUser->find('first',array('conditions'=>array('type'=>'qywechat','user_id'=>$login_user_id)));
			            			if(!empty($qywechat_info)&&$qywechat_info['SynchroUser']['account']==$qywechat_user['user_info']['userid']){
					              	$this->redirect($back_url);
			            			}else if(!empty($qywechat_info)){
			            				$msg = '该账户已被绑定';
                        					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");window.location.href="'.$this->base.'/users"</script>';
                        					die();
			            			}else if($login_user['User']['mobile']!=''){
			            				$qywechat_data=array(
		    							'id'=>'0',
		    							'type'=>'qywechat',
		    							'user_id'=>$login_user_id,
		    							'nick'=>$qywechat_user['user_info']['name'],
		    							'account'=>$qywechat_user['user_info']['userid'],
		    							'oauth_token'=>$qywechat_user['user_info']['userid']
		    						);
		    						$this->SynchroUser->save($qywechat_data);
					              	$this->redirect($back_url);
			            			}
			            		}
		            		}
		            		$member_detail=$this->check_Qywechat_Member($qywechat_user['user_info']['userid'],$qy_corp_id);
		            		if(!empty($member_detail['website_user'])){
		            			$this->set('website_user',$member_detail['website_user']);
		            		}
	    			}else{
	    				$this->redirect('/users/login');
	    			}
    			}else if(isset($_REQUEST['data']['api_type'])&&$_REQUEST['data']['api_type']=='qywechat'){
    				if ($this->RequestHandler->isPost()){
    					Configure::write('debug', 1);
    					$this->layout = 'ajax';
    					$result=array();
    					$result['code']='0';
    					$mobile=$this->data['mobile'];
    					$verify_code=$this->data['verify_code'];
	    				$phone_code_key="phone_code_number{$mobile}";
    					$system_verify_code=isset($_COOKIE[$phone_code_key])?$_COOKIE[$phone_code_key]:'';
    					if($verify_code!=$system_verify_code||$system_verify_code==""){
		    				$result['message'] = $this->ld['incorrect_verification_code'];
		    			}else{
		    				$login_user=array();
			            		if(!empty($login_user_id)){
			            			$login_user=$this->User->findById($login_user_id);
			            		}
		    				$qywechat_account=$this->data['u_id'];
		    				$qy_corp_id=$this->data['corpid'];
		    				$organization_member_list=array();
		    				$member_detail=$this->check_Qywechat_Member($qywechat_account,$qy_corp_id);
		    				if(!empty($member_detail['member_list'])){
		    					$organization_member_list=$member_detail['member_list'];
		    				}
		    				$mobile_user=$this->User->find('first',array('conditions'=>array('mobile'=>$mobile,'status'=>'1')));
		    				if(!empty($mobile_user)){
		    					$mobile_user_id=$mobile_user['User']['id'];
				            		if(!empty($login_user)&&$login_user_id!=$mobile_user_id){
				            			$result['message'] = '当前手机号无法绑定';
				            		}else{
			    					$qywechat_info=$this->SynchroUser->find('first',array('conditions'=>array('type'=>'qywechat','user_id'=>$mobile_user_id)));
			    					if(!empty($qywechat_info)&&$qywechat_info['SynchroUser']['account']==$qywechat_account){
			    						if(!empty($organization_member_list)){
			    						$this->OrganizationMember->updateAll(array('OrganizationMember.user_id'=>$mobile_user_id),array('OrganizationMember.id'=>$organization_member_list,'OrganizationMember.user_id'=>0));
			    							$this->OrganizationMember->updateAll(array('OrganizationMember.status'=>'1'),array('OrganizationMember.id'=>$organization_member_list,'OrganizationMember.user_id >'=>0,'OrganizationMember.status'=>0));
			    						}
			    						$result['code']='1';
			    						$result['message'] = '绑定成功';
			    						$result['back_url'] = $back_url;
			    					}else if(!empty($login_user_id)&&$qywechat_info['SynchroUser']['account']==$qywechat_account){
			    						$result['message'] = '当前账号已绑定';
			    					}else{
			    						if(!empty($organization_member_list)){
			    							$this->OrganizationMember->updateAll(array('OrganizationMember.user_id'=>$mobile_user_id),array('OrganizationMember.id'=>$organization_member_list,'OrganizationMember.user_id'=>0));
			    							$this->OrganizationMember->updateAll(array('OrganizationMember.status'=>'1'),array('OrganizationMember.id'=>$organization_member_list,'OrganizationMember.user_id >'=>0,'OrganizationMember.status'=>0));
			    						}
			    						$qywechat_data=array(
			    							'id'=>'0',
			    							'type'=>'qywechat',
			    							'nick'=>$mobile_user['User']['name'],
			    							'user_id'=>$mobile_user_id,
			    							'account'=>$qywechat_account,
			    							'oauth_token'=>$qywechat_account
			    						);
			    						$this->SynchroUser->save($qywechat_data);
			    						$_SESSION['User'] = $mobile_user;
						              	setcookie('user_info', serialize($mobile_user), time() + 60 * 60 * 24 * 14, '/');
			    						$result['code']='1';
			    						$result['message'] = '绑定成功';
			    						$result['back_url'] = $back_url;
			    					}
			    				}
		    				}else if(!empty($login_user)&&$login_user['User']['mobile']==''){
		    					$user_data=array(
		    						'id'=>$login_user_id,
		    						'mobile'=>$mobile
		    					);
		    					$this->User->save($user_data);
		    					$user_info = $this->User->find('first', array('conditions' => array('User.id' => $login_user_id)));
							$_SESSION['User'] = $user_info;
					              setcookie('user_info', serialize($user_info), time() + 60 * 60 * 24 * 14, '/');
					              $qywechat_data=array(
	    							'id'=>'0',
	    							'type'=>'qywechat',
	    							'user_id'=>$login_user_id,
	    							'nick'=>$user_info['User']['name'],
	    							'account'=>$qywechat_account,
	    							'oauth_token'=>$qywechat_account
	    						);
	    						$this->SynchroUser->save($qywechat_data);
	    						
	    						if(!empty($organization_member_list)){
	    							$this->OrganizationMember->updateAll(array('OrganizationMember.user_id'=>$login_user_id),array('OrganizationMember.id'=>$organization_member_list,'OrganizationMember.user_id'=>0));
	    							$this->OrganizationMember->updateAll(array('OrganizationMember.status'=>'1'),array('OrganizationMember.id'=>$organization_member_list,'OrganizationMember.user_id >'=>0,'OrganizationMember.status'=>0));
	    						}
					              $result['code']='1';
			    				$result['message'] = '绑定成功';
			    				$result['back_url'] = $back_url;
		    				}else{
		    					$new_user=array();
							//$new_user['user_sn'] = $qywechat_account.'@qywechat';
							$new_user['first_name'] = $this->data['user_name'];
							$new_user['name'] = $this->data['user_name'];
							$new_user['mobile'] = $mobile;
							$new_user['img01'] = $this->data['img'];
							$this->User->save($new_user);
							$user_info = $this->User->find('first', array('conditions' => array('User.id' => $this->User->id)));
							$_SESSION['User'] = $user_info;
					              setcookie('user_info', serialize($user_info), time() + 60 * 60 * 24 * 14, '/');
					                $user_data = array(
					                	   'nick'=>$this->data['user_name'],
					                        'user_id' => $user_info['User']['id'],
					                        'account' => $qywechat_account,
					                        'oauth_token' => $qywechat_account,
					                        'type' => 'qywechat',
					                        'oauth_token_secret' => ''
					                    );
					                $this->SynchroUser->save($user_data);
					                if(!empty($organization_member_list)){
					                $this->OrganizationMember->updateAll(array('OrganizationMember.user_id'=>$user_info['User']['id']),array('OrganizationMember.id'=>$organization_member_list,'OrganizationMember.user_id'=>0));
					                $this->OrganizationMember->updateAll(array('OrganizationMember.status'=>'1'),array('OrganizationMember.id'=>$organization_member_list,'OrganizationMember.user_id >'=>0,'OrganizationMember.status'=>0));
					                }
					                if (isset($this->configs['use_point']) && $this->configs['use_point'] == 1) {
					                	$this->loadModel('UserPointLog');
						                $register = isset($this->configs['point-register']) ? $this->configs['point-register'] : 0;
						                if (isset($register) && $register > 0) {
						                	 $old_point=$user_info['User']['point'];
						                    $user_info['User']['point'] = $register;
						                    $user_info['User']['user_point'] = $register;
						                    $this->User->save($user_info['User']);
						                    $user_point_log = array('id' => '',
						                              'user_id' => $user_info['User']['id'],
						                              'point' => $old_point,
						                              'point_change' => $register,
						                              'log_type' => 'R',
						                              'system_note' => $this->ld['registration_gift_points'],
						                              'type_id' => '0',
						                            );
						                    $this->UserPointLog->save($user_point_log);
						                    $this->UserPointLog->point_notify($user_point_log);
						                }
					            }
					            //推荐注册
					            if(isset($this->configs['share_points'])&&$this->configs['share_points']=='1'){
					            		$share_identification=isset($_SESSION['share_identification'])?$_SESSION['share_identification']:(isset($_COOKIE['share_identification'])?$_COOKIE['share_identification']:'');
					            		if($share_identification!=''&&isset($this->configs['recommend_points'])&&intval($this->configs['recommend_points'])>0){
					            			$this->loadModel('UserPointLog');
					            			$this->loadModel('ShareAffiliateLog');
					            			$share_affiliate_log=$this->ShareAffiliateLog->find('first',array('conditions'=>array('ShareAffiliateLog.user_id >'=>0,'ShareAffiliateLog.identification'=>$share_identification)));
					            			if(!empty($share_affiliate_log)){
					            				$share_user_info=$this->User->findById($share_affiliate_log['ShareAffiliateLog']['user_id']);
					            				if(!empty($share_user_info)){
					            					$this->User->save(array('id'=>$user_info['User']['id'],'parent_id'=>$share_affiliate_log['ShareAffiliateLog']['user_id']));
					            					$this->User->save(array('id'=>$share_affiliate_log['ShareAffiliateLog']['user_id'],'point'=>intval($share_user_info['User']['point'])+intval($this->configs['recommend_points'])));
					            					$point_log_data = array(
												'id' => 0,
												'log_type'=>'T',
												'user_id' => $share_affiliate_log['ShareAffiliateLog']['user_id'],
												'point'=>$share_user_info['User']['point'],
												'point_change' =>$this->configs['recommend_points'],
												'system_note' => $this->ld['recommend_friend']
											);
					        					$this->UserPointLog->save($point_log_data);
					        					$this->UserPointLog->point_notify($point_log_data);
					            				}
					            			}
					            		}
					            }
					            //判断是否送优惠券  start chenfan 2012/05/25
					            if (constant('Product') == 'AllInOne') {
					                $this->loadModel('CouponType');
					                $this->loadModel('Coupon');
					                $now = date('Y-m-d H:i:s');
					                $coupon_type = $this->CouponType->find('all', array('conditions' => "CouponType.send_type = '4' and CouponType.send_start_date <= '".$now."' and  CouponType.send_end_date >='".$now."'"));
					                if (is_array($coupon_type) && sizeof($coupon_type) > 0) {
					                    $coupon_arr_list = $this->Coupon->find('list', array('conditions' => array('1=1'), 'fields' => array('Coupon.sn_code')));
					                    $coupon_arr = array();
					                    if (is_array($coupon_arr_list) && sizeof($coupon_arr_list) > 0) {
					                        foreach ($coupon_arr_list as $k => $v) {
					                            $coupon_arr[] = $v;
					                        }
					                    }
					                    $coupon_count = count($coupon_arr);
					                    $num = 0;
					                    if ($coupon_count > 0) {
					                        $num = $coupon_arr[$coupon_count - 1];
					                    }
					                    foreach ($coupon_type as $k => $v) {
					                        if (isset($coupon_sn)) {
					                            $num = $coupon_sn;
					                        }
					                        $num = substr($num, 2, 10);
					                        $num = $num ? floor($num / 10000) : 100000;
					                        $coupon_sn = $v['CouponType']['prefix'].$num.str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
					                        $coupon = array(
					                                  'id' => '',
					                                  'coupon_type_id' => $v['CouponType']['id'],
					                                  'sn_code' => $coupon_sn,
					                                  'user_id' => $user_info['User']['id'],
					                        );
					                        $this->Coupon->save($coupon);
					                    }
					                }
					            }
					            $result['code']='1';
					            $result['message'] = '绑定成功';
					            $result['back_url'] = $back_url;
		    				}
		    			}
    					die(json_encode($result));
    				}else{
    					$this->redirect('/users/login');
    				}
 		       }else{
 		       	$this->redirect('/users/login');
 		       }
    		}else{
    			$this->redirect('/users/login');
    		}
    }
    
    function check_Qywechat_Member($qy_userid='',$qy_corp_id=''){
    		$member_detail=array();
    		$this->loadModel('OrganizationAppRelation');
    		$OrganizationApp_Id_info=$this->OrganizationAppRelation->find('list',array('fields'=>'organization_app_id,organization_type_id','conditions'=>array('type'=>'member','type_id'=>$qy_userid,'organization_type_id <>'=>0)));
    		if(!empty($OrganizationApp_Id_info)){
    			$organization_app_ids=array_keys($OrganizationApp_Id_info);
    			$organization_member_ids=$OrganizationApp_Id_info;
    			$organization_app_infos=$this->OrganizationApp->find('list',array('fields'=>'organization_id','conditions'=>array('id'=>$organization_app_ids,'type'=>'QYWechat','status'=>'1')));
    			if(!empty($organization_app_infos)){
    				$member_detail['member_list']=$organization_member_ids;
    				$member_cond=array();
    				$member_cond['OrganizationMember.user_id >']=0;
    				$member_cond['OrganizationMember.id']=$organization_member_ids;
    				$member_cond['OrganizationMember.organization_id']=$organization_app_infos;
    				$organization_member_user_ids=$this->OrganizationMember->find('list',array('fields'=>'user_id','conditions'=>$member_cond));
    				if(!empty($organization_member_user_ids)){
    					$organization_member_user_info=$this->User->find('first',array('fields'=>'user_sn,mobile,email','conditions'=>array('User.id'=>$organization_member_user_ids,'status'=>'1')));
    					if(!empty($organization_member_user_info)){
    						$member_detail['website_user']=$organization_member_user_info['User'];
    					}
    				}
    			}
    		}
    		return $member_detail;
    }
    
    //创建路径
    public function mkdirs($path, $mode = 0777){
        $dirs = explode('/', $path);
        $pos = strrpos($path, '.');
        if ($pos === false) {
            $subamount = 0;
        } else {
            $subamount = 1;
        }
        for ($c = 0;$c < count($dirs) - $subamount; ++$c) {
            $thispath = '';
            for ($cc = 0; $cc <= $c; ++$cc) {
                $thispath .= $dirs[$cc].'/';
            }
            if (!file_exists($thispath)) {
                mkdir($thispath, $mode);
            }
        }
    }
}
