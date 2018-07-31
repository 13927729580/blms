<?php

/*****************************************************************************
 * Reservation 预约
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为ReservationsController的控制器
 *预约
 *
 *@var
 *@var
 *@var
 *@var
 */
class AppointmentOrdersController extends AppController{
	public $name = 'AppointmentOrders';
	public $helpers = array('Html','Pagination');
	public $uses = array('User','UserAddress','InformationResource','Order','OrderProduct','OrderAction','Region','RegionI18n','Shipping','Payment','ShippingArea','ShippingAreaRegion');
	public $components = array('RequestHandler','Pagination','Notify');
	
	function index(){
		//登录验证
        	$this->checkSessionUser();
		$this->layout = 'default_full';
		$this->pageTitle = '预约 - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '预约' , 'url' => '');
        	$this->set('ur_heres', $this->ur_heres);
        	
        	$user_id=$_SESSION['User']['User']['id'];
        	$user_info=$this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
        	if(isset($_REQUEST['select_address_id'])&&intval($_REQUEST['select_address_id'])>0){
        		$address_id=$_REQUEST['select_address_id'];
        		$_SESSION['appointment_address_id']=$address_id;
        	}else if(isset($_SESSION['appointment_address_id'])&&intval($_SESSION['appointment_address_id'])>0){
        		$address_id=$_SESSION['appointment_address_id'];
        	}else{
        		$address_id=$user_info['User']['address_id'];
        	}
        	$this->set('user_info',$user_info);
        	$user_address_info=$this->UserAddress->find('first',array('conditions'=>array('UserAddress.user_id'=>$user_id,'UserAddress.id'=>$address_id)));
        	$this->set('user_address_info',$user_address_info);
        	
        	if(!empty($user_address_info)){
        		$region_ids=array();
        		if(intval($user_address_info['UserAddress']['country'])>0)$region_ids[]=$user_address_info['UserAddress']['country'];
        		if(intval($user_address_info['UserAddress']['province'])>0)$region_ids[]=$user_address_info['UserAddress']['province'];
        		if(intval($user_address_info['UserAddress']['city'])>0)$region_ids[]=$user_address_info['UserAddress']['city'];
        		if(intval($user_address_info['UserAddress']['district'])>0)$region_ids[]=$user_address_info['UserAddress']['district'];
        		if(!empty($region_ids)){
        			$region_list=$this->RegionI18n->find('list',array('fields'=>'RegionI18n.region_id,RegionI18n.name','conditions'=>array('RegionI18n.locale'=>LOCALE,'RegionI18n.region_id'=>$region_ids)));
        			$this->set('region_list',$region_list);
        		}
        	}
        	
        	
        	$informationresource_infos = $this->InformationResource->code_information_formated(array('best_time'), $this->locale);
		$this->set('informationresource_infos', $informationresource_infos);
		
		$shipping_info=$this->Shipping->find('first',array('conditions'=>array('Shipping.code'=>'pickup','Shipping.status'=>'1')));
        	if(!empty($shipping_info)){
        		$this->set('shipping_info',$shipping_info);
        	}
	}
	
	function checkout_order(){
		//登录验证
        	$this->checkSessionUser();
		$this->layout = 'default_full';
		$this->pageTitle = '预约 - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '预约' , 'url' => '');
        	$this->set('ur_heres', $this->ur_heres);
        	
        	$user_id=$_SESSION['User']['User']['id'];
        	if ($this->RequestHandler->isPost()){
        		$user_info=$this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
        		$this->set('user_info',$user_info);
        		
        		$order_message="";
        		$shipping_info=$this->Shipping->find('first',array('conditions'=>array('Shipping.code'=>'pickup','Shipping.status'=>'1')));
        		$payment_info=$this->Payment->find('first',array('conditions'=>array('Payment.code'=>'online_payment','Payment.status'=>'1')));
        		$order_data=array();
			$order_data['id']='0';
			$order_data['service_type']='appointment';
			$order_data['order_code'] = $this->Order->get_order_code();
			$order_code = $this->Order->findbyorder_code($order_data['order_code']);
			if (isset($order_code) && count($order_code) > 0) {
				$order_data['order_code'] = $this->Order->get_order_code();
			}
			$order_code=$order_data['order_code'];
			$order_data['user_id']=$user_id;
			$order_data['order_date']=date('Y-m-d H:i:s');
			
			$orders_list = $this->Order->find('all', array('conditions'=>array('Order.shipping_status'=>'6')));
			$orders_list_manager = array();
			foreach ($orders_list as $k => $v) {
				$orders_list_manager[] = $v['Order']['order_manager'];
			}
	        	$orders_list_manager = array_unique($orders_list_manager);
			if(isset($user_info['User']['operator_id'])&&intval($user_info['User']['operator_id'])>0){
				$order_manager = intval($user_info['User']['operator_id']);
				foreach ($orders_list_manager as $k => $v) {
					if($order_manager == $v){
						$order_data['order_manager']== 0;
					}else{
						$order_data['order_manager']== $order_manager;
					}
				}
				//$order_data['order_manager'] = intval($user_info['User']['operator_id']);
        			$order_data['status'] = 0;
			}else{
				$order_data['status']='9';
			}
			$order_data['payment_id']=isset($payment_info['Payment'])?$payment_info['Payment']['id']:0;
			$order_data['payment_name']=isset($payment_info['PaymentI18n'])?$payment_info['PaymentI18n']['name']:'在线支付';
			$order_data['payment_status']='0';
			$order_data['shipping_status']='6';
			$order_data['postscript']=isset($_POST['service_type'])?$_POST['service_type']:'';
			$order_data['order_locale'] = LOCALE;//订单语言
			$order_data['type'] = 'website';
            		$order_data['type_id'] = 'front';
			$address_id=isset($_POST['address_id'])?$_POST['address_id']:0;
			$user_address_info=$this->UserAddress->find('first',array('conditions'=>array('UserAddress.user_id'=>$user_id,'UserAddress.id'=>$address_id)));
			if(!empty($user_address_info)&&!empty($shipping_info['Shipping'])){
				$shipping_id=$shipping_info['Shipping']['id'];
				$shipping_area_ids = $this->ShippingArea->find('list', array('conditions'=>array('ShippingArea.shipping_id'=>$shipping_id,'ShippingArea.status'=>'1'),'fields' => 'id'));
				$shipping_area_region_ids=$this->ShippingAreaRegion->find('list',array('fields'=>'ShippingAreaRegion.region_id','conditions'=>array('ShippingAreaRegion.shipping_area_id'=>$shipping_area_ids)));
				if(!empty($shipping_area_region_ids)){
		        		$child_region_ids1=$this->Region->find('list',array('fields'=>'Region.id','conditions'=>array('Region.parent_id'=>$shipping_area_region_ids)));
		        		if(!empty($child_region_ids1)){
		        			$child_region_ids2=$this->Region->find('list',array('fields'=>'Region.id','conditions'=>array('Region.parent_id'=>$child_region_ids1)));
		        			$shipping_area_region_ids=array_merge($child_region_ids1,$shipping_area_region_ids);
		        			$shipping_area_region_ids=array_merge($child_region_ids2,$shipping_area_region_ids);
		        		}
		        		$parent_region_ids1=$this->Region->find('list',array('fields'=>'Region.id,Region.parent_id','conditions'=>array('Region.id'=>$shipping_area_region_ids,'Region.parent_id >'=>0)));
		        		if(!empty($parent_region_ids1)){
		        			$parent_region_ids2=$this->Region->find('list',array('fields'=>'Region.id,Region.parent_id','conditions'=>array('Region.id'=>$parent_region_ids1,'Region.parent_id >'=>0)));
		        			$shipping_area_region_ids=array_merge($parent_region_ids1,$shipping_area_region_ids);
		        			$shipping_area_region_ids=array_merge($parent_region_ids2,$shipping_area_region_ids);
		        		}
		        	}
		        	$shipping_area_region_ids=array_unique($shipping_area_region_ids);
				$order_data['shipping_id']=isset($shipping_info['Shipping']['id'])?$shipping_info['Shipping']['id']:'0';
				$order_data['shipping_name']=isset($shipping_info['ShippingI18n']['name'])?$shipping_info['ShippingI18n']['name']:'';
				$order_data['consignee']=$user_address_info['UserAddress']['consignee'];
				$order_data['address']=$user_address_info['UserAddress']['address'];
				$order_data['zipcode']=$user_address_info['UserAddress']['zipcode'];
				$order_data['telephone']=$user_address_info['UserAddress']['telephone'];
				$order_data['mobile']=$user_address_info['UserAddress']['mobile'];
				$order_data['email']=$user_address_info['UserAddress']['email'];
				$region_ids=explode(' ',$user_address_info['UserAddress']['regions']);
				$region_data=$this->RegionI18n->find('list',array('fields'=>array('RegionI18n.region_id','RegionI18n.name'),'conditions'=>array('RegionI18n.region_id'=>$region_ids,'RegionI18n.locale'=>LOCALE)));
				$error_region=array();
		        	foreach($region_ids as $v){
		        		if($v==0)continue;
		        		if(!in_array($v,$shipping_area_region_ids)){
		        			$error_region[]=isset($region_data[$v])?$region_data[$v]:$v;
		        		}
		        	}
		        	if(!empty($error_region)){
		        		$order_message="暂不支持以下地区:".implode("",$error_region);
		        	}
				$country_id=isset($user_address_info['UserAddress']['country'])?$user_address_info['UserAddress']['country']:0;
				$province_id=isset($user_address_info['UserAddress']['province'])?$user_address_info['UserAddress']['province']:0;
				$city_id=isset($user_address_info['UserAddress']['city'])?$user_address_info['UserAddress']['city']:0;
				$order_data['country']=isset($region_data[$country_id])?$region_data[$country_id]:'';
				$order_data['province']=isset($region_data[$province_id])?$region_data[$province_id]:'';
				$order_data['city']=isset($region_data[$city_id])?$region_data[$city_id]:'';
				$order_data['regions']=$country_id." ".$province_id." ".$city_id;
				$this->set('user_address_info',$user_address_info);
				$this->set('shipping_info',$shipping_info);
			}else{
				$order_message="联系人信息不完整";
			}
			if(isset($this->configs['use_point'])&&$this->configs['use_point']=='1'&&isset($_POST['use_point'])&&intval($_POST['use_point'])>0){
				$order_data['point_fee'] = isset($this->configs['point-equal'])?$this->configs['point-equal']*$_POST['use_point']:0;
                    	$order_data['point_use'] = intval($_POST['use_point']);
			}
			$order_data['shipping_fee']=isset($shipping_info['Shipping'])?$shipping_info['Shipping']['insure_fee']:0;
			$order_data['total']=$order_data['shipping_fee'];
			$order_data['best_time']=trim(implode(' ',isset($_POST['appointment_date'])?$_POST['appointment_date']:array()));
			$this->set('order_message',$order_message);
			
			if(isset($_POST['is_submit'])&&$_POST['is_submit']=='1'){
				Configure::write('debug',1);
				$this->layout="ajax";
				$result=array();
				if($order_message==''){
					$this->Order->save($order_data);
					$order_id=$this->Order->id;
					$order_info=$this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id)));
					if(empty($order_info)){
						$result['code']='0';
						$result['message']='订单生成失败';
					}else{
						$this->OrderAction->saveAll(array('OrderAction' => array(
					                'order_id' => $order_id,
					                'from_operator_id' => 0,
					                'user_id' => $user_id,
					                'order_status' => $order_info['Order']['status'],
					                'payment_status' => $order_info['Order']['payment_status'],
					                'shipping_status' => $order_info['Order']['shipping_status'],
					                'action_note' => $this->ld['submit_order']
			            		)));
			            		if (isset($this->configs['shop-email']) && !empty($this->configs['shop-email']) && isset($this->configs['shop-email-status']) && $this->configs['shop-email-status'] == 1) {
				                    $send_date = date('Y-m-d');
				                    $shop_name = $this->configs['shop_name'];
				                    $this->loadModel('MailTemplate');
				                    $template = $this->MailTemplate->find('first',array('conditions'=>array('code'=>'order_confirm','status'=>'1')));
				                    if(!empty($template)){
					                    $template_str = $template['MailTemplateI18n']['html_body'];
					                    $template_str = str_replace('$consignee', $_SESSION['User']['User']['name'], $template_str);
					                    $template_str = str_replace('$formated_add_time', DateTime, $template_str);
								$email_product_info = array();
								$email_product_info[]=$order_info['Order']['consignee'];
								$email_product_info[]=$order_info['Order']['mobile'];
								$email_product_info[]=$order_info['Order']['address'];
								$email_product_info[]=$order_info['Order']['best_time'];
					                    $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
					                    $shop_url = '<a href="'.$this->server_host.$this->base.'">'.$host.'</a>';
					                    $template_str = str_replace('$order_code ', $order['order_code'], $template_str);
					                    $template_str = str_replace('$shop_name', $shop_name, $template_str);
					                    $template_str = str_replace('$sent_date', $send_date, $template_str);
					                    $template_str = str_replace('$products_info', implode(' ',$email_product_info), $template_str);
					                    $template_str = str_replace('$shop_url', $shop_url, $template_str);
					                    $subject = $template['MailTemplateI18n']['title'];
					                    $mail_send_queue = array(
					                        'id' => '',
					                        'sender_name' => $shop_name,
					                        'receiver_email' => $this->configs['shop-email'],
					                        'cc_email' => $_SESSION['User']['User']['name'].';'.$_SESSION['User']['User']['email'],
					                        'bcc_email' => ';',
					                        'title' => $subject,
					                        'html_body' => $template_str,
					                        'text_body' => $template_str,
					                        'sendas' => 'html',
					                        'flag' => 0,
					                        'pri' => 0,
					                    );
					                    $this->Notify->send_email($mail_send_queue,$this->configs);
				                    }
				              }
				              if(isset($order_info['Order']['point_use'])&&intval($order_info['Order']['point_use'])>0){
				              	$this->User->save(array('id'=>$user_info['User']['id'],'point'=>$user_info['User']['point']-$order_info['Order']['point_use']));
				              	$this->loadModel('UserPointLog');
					              $point_log = array(
								'id' => '0',
								'user_id' => $order_info['Order']['user_id'],
								'point'=>$user_info['User']['point'],
								'point_change' => "-".intval($order_info['Order']['point_use']),
								'log_type' => 'O',
								'system_note' => '订单消费:'.$order_info['Order']['order_code'],
								'type_id' => $order_id
							);
			                	 	$this->UserPointLog->save($point_log);
			                	 	$this->UserPointLog->point_notify($point_log);
				              }
				            if(isset($this->configs['recommend_registration_redpacket'])&&trim($this->configs['recommend_registration_redpacket'])!=''){
				            		//推荐注册赠送红包
				            		$share_identification=isset($_SESSION['share_identification'])?$_SESSION['share_identification']:(isset($_COOKIE['share_identification'])?$_COOKIE['share_identification']:'');
				            		if($share_identification!=''){
				            			$this->loadModel('ShareAffiliateLog');
				            			$share_affiliate_log=$this->ShareAffiliateLog->find('first',array('conditions'=>array('ShareAffiliateLog.user_id >'=>0,'ShareAffiliateLog.identification'=>$share_identification)));
				            			if(!empty($share_affiliate_log)){
					            			$registration_redpacket=explode('/',trim($this->configs['recommend_registration_redpacket']));
						            		$redpacket_min=intval($registration_redpacket[0]);
						            		$redpacket_max=isset($registration_redpacket[1])?intval($registration_redpacket[1]):$redpacket_min;
						            		$redpacket_data=array(
						            			'user_id'=>$share_affiliate_log['ShareAffiliateLog']['user_id'],
						            			'money'=>round($redpacket_min,$redpacket_max),
						            			'act_name'=>$this->ld['recommend_friend'],
						            			'remark'=>$this->ld['recommend_friend']
						            		);
						            		$this->Payment->wechat_redpacket($redpacket_data);
					            		}
				            		}
				            }
				              
						$result['code']='1';
						$result['message']='订单生成成功';
						$result['order_detail']=$order_info['Order'];
					}
				}else{
					$result['code']='0';
					$result['message']=$order_message;
				}
				die(json_encode($result));
			}
        	}else{
        		$this->redirect('index');
        	}
        	$user_address_list=$this->UserAddress->find('all');
        	if(!empty($user_address_list)){
        		$region_ids=array();
        		foreach($user_address_list as $v){
        			if(intval($v['UserAddress']['country'])>0)$region_ids[]=$v['UserAddress']['country'];
        			if(intval($v['UserAddress']['province'])>0)$region_ids[]=$v['UserAddress']['province'];
        			if(intval($v['UserAddress']['city'])>0)$region_ids[]=$v['UserAddress']['city'];
        			if(intval($v['UserAddress']['district'])>0)$region_ids[]=$v['UserAddress']['district'];
        		}
        		if(!empty($region_ids)){
        			$region_list=$this->RegionI18n->find('list',array('fields'=>'RegionI18n.region_id,RegionI18n.name','conditions'=>array('RegionI18n.locale'=>LOCALE,'RegionI18n.region_id'=>$region_ids)));
        			$this->set('region_list',$region_list);
        		}
        	}
	}
	
	function select_address(){
		//登录验证
        	$this->checkSessionUser();
		$this->layout = 'default_full';
		$this->pageTitle = '预约 - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '预约' , 'url' => '');
        	$this->set('ur_heres', $this->ur_heres);
        	
        	$user_id=$_SESSION['User']['User']['id'];
        	$joins = array(
                    array('table' => 'svoms_users',
                          'alias' => 'User',
                          'type' => 'inner',
                          'conditions' => array('UserAddress.user_id = User.id'),
                         )
            );
        	$user_address_list=$this->UserAddress->find('all',array('fields'=>'UserAddress.*,User.address_id','conditions'=>array('UserAddress.user_id'=>$user_id),'joins'=>$joins));
        	$this->set('user_address_list',$user_address_list);
        	if(!empty($user_address_list)){
        		$region_ids=array();
        		foreach($user_address_list as $v){
        			if(intval($v['UserAddress']['country'])>0)$region_ids[]=$v['UserAddress']['country'];
        			if(intval($v['UserAddress']['province'])>0)$region_ids[]=$v['UserAddress']['province'];
        			if(intval($v['UserAddress']['city'])>0)$region_ids[]=$v['UserAddress']['city'];
        			if(intval($v['UserAddress']['district'])>0)$region_ids[]=$v['UserAddress']['district'];
        		}
        		if(!empty($region_ids)){
        			$region_list=$this->RegionI18n->find('list',array('fields'=>'RegionI18n.region_id,RegionI18n.name','conditions'=>array('RegionI18n.locale'=>LOCALE,'RegionI18n.region_id'=>$region_ids)));
        			$this->set('region_list',$region_list);
        		}
        	}
	}
	
	function address_list(){
		//登录验证
        	$this->checkSessionUser();
		$this->layout = 'default_full';
		$this->pageTitle = '预约 - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '预约' , 'url' => '');
        	$this->set('ur_heres', $this->ur_heres);
        	
        	$user_id=$_SESSION['User']['User']['id'];
        	
        	$joins = array(
                    array('table' => 'svoms_users',
                          'alias' => 'User',
                          'type' => 'inner',
                          'conditions' => array('UserAddress.user_id = User.id'),
                         )
            );
        	$user_address_list=$this->UserAddress->find('all',array('fields'=>'UserAddress.*,User.address_id','conditions'=>array('UserAddress.user_id'=>$user_id),'joins'=>$joins));
        	$this->set('user_address_list',$user_address_list);
        	if(!empty($user_address_list)){
        		$region_ids=array();
        		foreach($user_address_list as $v){
        			if(intval($v['UserAddress']['country'])>0)$region_ids[]=$v['UserAddress']['country'];
        			if(intval($v['UserAddress']['province'])>0)$region_ids[]=$v['UserAddress']['province'];
        			if(intval($v['UserAddress']['city'])>0)$region_ids[]=$v['UserAddress']['city'];
        			if(intval($v['UserAddress']['district'])>0)$region_ids[]=$v['UserAddress']['district'];
        		}
        		if(!empty($region_ids)){
        			$region_list=$this->RegionI18n->find('list',array('fields'=>'RegionI18n.region_id,RegionI18n.name','conditions'=>array('RegionI18n.locale'=>LOCALE,'RegionI18n.region_id'=>$region_ids)));
        			$this->set('region_list',$region_list);
        		}
        	}
	}
	
	function address_view($address_id=0){
		//登录验证
        	$this->checkSessionUser();
		$this->layout = 'default_full';
		$this->pageTitle = '预约 - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '预约' , 'url' => '');
        	$this->set('ur_heres', $this->ur_heres);
        	
        	$user_id=$_SESSION['User']['User']['id'];
        	if ($this->RequestHandler->isPost()) {
			Configure::write('debug',1);
			$this->layout="ajax";
			$result=array();
			$result['code']='0';
			$result['message']=$this->ld['failed'];
        		if(isset($this->data['UserAddress'])){
        			$this->data['UserAddress']['user_id']=$user_id;
        			$this->data['UserAddress']['country'] = isset($this->data['UserAddress']['regions'][0]) ? $this->data['UserAddress']['regions'][0] : '';
                    	$this->data['UserAddress']['province'] = isset($this->data['UserAddress']['regions'][1]) ? $this->data['UserAddress']['regions'][1] : '';
                    	$this->data['UserAddress']['city'] = isset($this->data['UserAddress']['regions'][2]) ? $this->data['UserAddress']['regions'][2] : '';
                    	$this->data['UserAddress']['regions']=isset($this->data['UserAddress']['regions'])&&is_array($this->data['UserAddress']['regions'])?implode(' ',$this->data['UserAddress']['regions']):'';
                    	$this->UserAddress->save($this->data['UserAddress']);
                    	$address_id=$this->UserAddress->id;
                    	$this->User->updateAll(array('User.address_id'=>$address_id),array('User.id'=>$user_id,'User.address_id'=>0));
                    	$result['code']='1';
				$result['message']=$this->ld['saved_successfully'];
        		}
        		die(json_encode($result));
        	}
        	$user_address_info=$this->UserAddress->find('first',array('conditions'=>array('UserAddress.user_id'=>$user_id,'UserAddress.id'=>$address_id)));
        	$this->set('user_address_info',$user_address_info);
        	
        	$shipping_info=$this->Shipping->find('first',array('conditions'=>array('Shipping.code'=>'pickup','Shipping.status'=>'1')));
        	if(!empty($shipping_info)){
        		$shipping_id=$shipping_info['Shipping']['id'];
	        	$shipping_area_ids = $this->ShippingArea->find('list', array('conditions'=>array('ShippingArea.shipping_id'=>$shipping_id,'ShippingArea.status'=>'1'),'fields' => 'id'));
	        	$shipping_area_region_ids=$this->ShippingAreaRegion->find('list',array('fields'=>'ShippingAreaRegion.region_id','conditions'=>array('ShippingAreaRegion.shipping_area_id'=>$shipping_area_ids)));
	        	if(!empty($shipping_area_region_ids)){
	        		$child_region_ids1=$this->Region->find('list',array('fields'=>'Region.id','conditions'=>array('Region.parent_id'=>$shipping_area_region_ids)));
	        		if(!empty($child_region_ids1)){
	        			$child_region_ids2=$this->Region->find('list',array('fields'=>'Region.id','conditions'=>array('Region.parent_id'=>$child_region_ids1)));
	        			$shipping_area_region_ids=array_merge($child_region_ids1,$shipping_area_region_ids);
	        			$shipping_area_region_ids=array_merge($child_region_ids2,$shipping_area_region_ids);
	        		}
	        		$parent_region_ids1=$this->Region->find('list',array('fields'=>'Region.id,Region.parent_id','conditions'=>array('Region.id'=>$shipping_area_region_ids,'Region.parent_id >'=>0)));
	        		if(!empty($parent_region_ids1)){
	        			$parent_region_ids2=$this->Region->find('list',array('fields'=>'Region.id,Region.parent_id','conditions'=>array('Region.id'=>$parent_region_ids1,'Region.parent_id >'=>0)));
	        			$shipping_area_region_ids=array_merge($parent_region_ids1,$shipping_area_region_ids);
	        			$shipping_area_region_ids=array_merge($parent_region_ids2,$shipping_area_region_ids);
	        		}
	        	}
	        	$shipping_area_region_ids=array_unique($shipping_area_region_ids);
	        	$this->set('shipping_area_region_ids',$shipping_area_region_ids);
        	}
	}
	
	function ajax_default_address(){
		Configure::write('debug',1);
		$this->layout="ajax";
		
		$result=array();
		$result['code']='0';
		$result['message']=$this->ld['failed'];
		$user_id=isset($_SESSION['User']['User']['id'])?$_SESSION['User']['User']['id']:0;
		$address_id=isset($_POST['address_id'])?$_POST['address_id']:0;
		$user_address_info=$this->UserAddress->find('first',array('conditions'=>array('UserAddress.user_id'=>$user_id,'UserAddress.id'=>$address_id)));
		if(!empty($user_address_info)){
			$this->User->updateAll(array('User.address_id'=>$address_id),array('User.id'=>$user_id));
			$result['code']='1';
			$result['message']=$this->ld['tips_edit_success'];
		}
		die(json_encode($result));
	}
}