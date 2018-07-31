<?php

/*****************************************************************************
 * Seevia 兑换券
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为VouchersController的控制器
 * 控制卡券调用
 *
 */
class VouchersController extends AppController
{
    public $name = 'Vouchers';
    public $helpers = array('Html');
    public $uses = array('MailTemplate','User','MailSendQueue','InformationResource','Resource','Voucher','VoucherEntityCard','VoucherOperation','UserFans','Blog','UserAddress','PackageProduct','Region','Payment');
    public $components = array('RequestHandler','Notify','captcha');
    
    /**
     *	兑换券申请使用
     */
    public function index()
    {
		//登录验证
		$this->checkSessionUser();
		$this->layout = 'default_full';            //引入模版
		$this->pageTitle = '兑换券/使用 - '.$this->configs['shop_title'];
		//当前位置开始
        	$this->ur_heres[] = array('name' => '兑换券/使用', 'url' => '');
        	$this->set('ur_heres', $this->ur_heres);
        	$this->page_init();                    //页面初始化
        	$id=$_SESSION['User']['User']['id'];
		//获取我的信息
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
		$_SESSION['User'] = $user_list;
		if ($user_list['User']['address_id'] != '0') {
			//获取我的地址
			$user_list['User']['UserAddress'] = $this->UserAddress->find('first', array('conditions' => array('id' => $user_list['User']['address_id']), 'fields' => 'id,zipcode,address_type,address,mobile,regions,sign_building'));
		}
		$this->set('user_list', $user_list);
		//粉丝数量
		$fans = $this->UserFans->find_fanscount_byuserid($id);
		$this->set('fanscount', $fans);
		//日记数量
		$blog = $this->Blog->find_blogcount_byuserid($id);
		$this->set('blogcount', $blog);
		//关注数量
		$focus = $this->UserFans->find_focuscount_byuserid($id);
		$this->set('focuscount', $focus);
		
		if ($this->RequestHandler->isPost()) {
			if (isset($this->data['authnum'])&&isset($this->data['authnum'])&&trim($this->data['ck_authnum'])!="") {
				if($this->captcha->check($this->data['authnum']) == false){
					$this->flash($this->ld['incorrect_verification_code'], '/vouchers/index', '');
				}
			}
			$card_sn=isset($this->data['card_sn'])?$this->data['card_sn']:'';
    			$card_password=isset($this->data['card_password'])?$this->data['card_password']:'';
    			$cond['not']['VoucherEntityCard.status']='0';
    			$cond['VoucherEntityCard.batch_sn <>']='';
    			$cond['VoucherEntityCard.card_sn']=$card_sn;
    			$cond['VoucherEntityCard.card_password']=$card_password;
    			$EntityCard_Data=$this->VoucherEntityCard->find('first',array('conditions'=>$cond));
    			if(!empty($EntityCard_Data)){
    				$error_message="";
    				if($EntityCard_Data['VoucherEntityCard']['status']=='2'){
    					$error_message='卡号已使用';
    				}else if($EntityCard_Data['VoucherEntityCard']['status']=='3'){
    					$error_message='卡号已作废';
    				}else if($EntityCard_Data['VoucherEntityCard']['status']=='4'){
    					$error_message='卡号已冻结';
    				}
    				if(empty($error_message)){
	    				$batch_sn=$EntityCard_Data['VoucherEntityCard']['batch_sn'];
					if(strtotime($EntityCard_Data['VoucherEntityCard']['start_date']." 00:00:00")<=time()&&strtotime($EntityCard_Data['VoucherEntityCard']['end_date']." 23:59:59")>=time()){
						$Voucher_Info=$EntityCard_Data;
						setcookie('Voucher_Info', serialize($Voucher_Info), time() + 60 * 30, '/');
						$this->redirect('/vouchers/receiver_info');
					}else if(strtotime($EntityCard_Data['VoucherEntityCard']['start_date']." 00:00:00")>time()){
						$this->flash('兑换活动尚未开始', '/vouchers/index', '');
					}else{
						$this->flash('兑换券已过期', '/vouchers/index', '');
					}
				}else{
					$this->flash($error_message, '/vouchers/index', '');
				}
    			}else{
    				$this->flash('无效兑换券,或密码错误', '/vouchers/index', '');
    			}
		}
    }
    
    /**
     *	收货人信息
     */
    public function receiver_info()
    {
    		//登录验证
		$this->checkSessionUser();
		$this->layout = 'default_full';            //引入模版
    		$this->pageTitle = '兑换券/收货人信息 - '.$this->configs['shop_title'];
    		
    		//当前位置开始
        	$this->ur_heres[] = array('name' => '兑换券/收货人信息', 'url' => '');
        	$this->set('ur_heres', $this->ur_heres);
        	$this->page_init();                    //页面初始化
        	$id=$_SESSION['User']['User']['id'];
		//获取我的信息
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
		$_SESSION['User'] = $user_list;
		if ($user_list['User']['address_id'] != '0') {
			//获取我的地址
			$user_list['User']['UserAddress'] = $this->UserAddress->find('first', array('conditions' => array('id' => $user_list['User']['address_id']), 'fields' => 'id,zipcode,address_type,address,mobile,regions,sign_building'));
		}
		$this->set('user_list', $user_list);
		//粉丝数量
		$fans = $this->UserFans->find_fanscount_byuserid($id);
		$this->set('fanscount', $fans);
		//日记数量
		$blog = $this->Blog->find_blogcount_byuserid($id);
		$this->set('blogcount', $blog);
		//关注数量
		$focus = $this->UserFans->find_focuscount_byuserid($id);
		$this->set('focuscount', $focus);
		
		if(isset($_COOKIE['Voucher_Info'])&&!empty($_COOKIE['Voucher_Info'])){
			$Voucher_Info=unserialize(stripslashes($_COOKIE['Voucher_Info']));
		}else{
			$this->redirect('/vouchers/index');
		}
    }
    
    
    /**
     *	信息确认
     */
    public function info_confirm(){
    		//登录验证
		$this->checkSessionUser();
		$this->layout = 'default_full';            //引入模版
    		$this->pageTitle = '兑换券/信息确认 - '.$this->configs['shop_title'];
    		
    		//当前位置开始
        	$this->ur_heres[] = array('name' => '兑换券/信息确认', 'url' => '');
        	$this->set('ur_heres', $this->ur_heres);
        	$this->page_init();                    //页面初始化
        	$id=$_SESSION['User']['User']['id'];
		//获取我的信息
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
		$_SESSION['User'] = $user_list;
		if ($user_list['User']['address_id'] != '0') {
			//获取我的地址
			$user_list['User']['UserAddress'] = $this->UserAddress->find('first', array('conditions' => array('id' => $user_list['User']['address_id']), 'fields' => 'id,zipcode,address_type,address,mobile,regions,sign_building'));
		}
		$this->set('user_list', $user_list);
		//粉丝数量
		$fans = $this->UserFans->find_fanscount_byuserid($id);
		$this->set('fanscount', $fans);
		//日记数量
		$blog = $this->Blog->find_blogcount_byuserid($id);
		$this->set('blogcount', $blog);
		//关注数量
		$focus = $this->UserFans->find_focuscount_byuserid($id);
		$this->set('focuscount', $focus);
		
		if(isset($_COOKIE['Voucher_Info'])&&!empty($_COOKIE['Voucher_Info'])){
			$Voucher_Info=unserialize(stripslashes($_COOKIE['Voucher_Info']));
		}else{
			$this->redirect('/vouchers/index');
		}
		if ($this->RequestHandler->isPost()) {
			$this->loadModel('RegionI18n');
			$this->loadModel('Shipping');
            		$this->loadModel('Order');
            		$this->loadModel('OrderProduct');
			if(isset($_POST['order_created'])&&$_POST['order_created']=='1'){
				$this->data=json_decode($_POST['receiver_data'],true);
				$VoucherEntityCard_info=$Voucher_Info['VoucherEntityCard'];
	    			$EntityCard_Data=$this->VoucherEntityCard->find('first',array('conditions'=>array('VoucherEntityCard.id'=>$VoucherEntityCard_info['id'],'VoucherEntityCard.status <>'=>'0')));
				if(empty($EntityCard_Data)||$EntityCard_Data['VoucherEntityCard']['status']=='3'){
					$this->flash('当前兑换券已失效', '/vouchers/index', 5);
					return;
				}else if($EntityCard_Data['VoucherEntityCard']['status']=='2'){
					$this->flash('当前兑换券已使用', '/vouchers/index', 5);
					return;
				}else if($EntityCard_Data['VoucherEntityCard']['status']=='4'){
					$this->flash('当前兑换券已冻结', '/vouchers/index', 5);
					return;
				}
				$voucher_card_sn=$EntityCard_Data['VoucherEntityCard']['card_sn'];
				$product_code=$EntityCard_Data['VoucherEntityCard']['product_code'];
				$product_info=$this->Product->find('first',array('conditions'=>array('Product.code'=>$product_code)));
				if(!empty($product_info)&&$product_info['Product']['quantity']>0){
					$payment_data=$this->Payment->find('first',array('conditions'=>array('Payment.id'=>'23','Payment.status'=>'1')));
					$shipping_data=$this->Shipping->find('first',array('conditions'=>array('Shipping.id'=>'2','Shipping.status'=>'1')));
					
					$order_data=array();
					$order_data['id']='0';
					$order_data['order_code'] = $this->Order->get_order_code();
					$order_code = $this->Order->findbyorder_code($order_data['order_code']);
					if (isset($order_code) && count($order_code) > 0) {
						$order_data['order_code'] = $this->Order->get_order_code();
					}
					$order_code=$order_data['order_code'];
					$order_data['user_id']=$_SESSION['User']['User']['id'];
					$order_data['status']='1';
					$order_data['shipping_id']='2';
					$order_data['shipping_name']=isset($shipping_data['Shipping']['name'])?$shipping_data['Shipping']['name']:'快递';
					
					$order_data['payment_id']='23';
					$order_data['payment_status']='2';
					$order_data['payment_name']=isset($payment_data['Payment']['name'])?$payment_data['Payment']['name']:'在线支付';
					$order_data['payment_time']=date("Y-m-d H:i:s");
					$order_data['payment_fee']='0.00';
					$order_data['money_paid']=$product_info['Product']['shop_price'];
					$order_data['total']=$product_info['Product']['shop_price'];
					$order_data['subtotal']=$product_info['Product']['shop_price'];
					
					$order_data['consignee']=$this->data['consignee'];
					$order_data['address']=$this->data['address'];
					$order_data['zipcode']='';
					$order_data['telephone']='';
					$order_data['mobile']=$this->data['mobile'];
					$order_data['email']='';
					$order_data['note']="兑换券[{$voucher_card_sn}]抵扣";
					$regions="";
					if(isset($this->data['Address']['RegionUpdate'])){
						$country_id=isset($this->data['Address']['RegionUpdate'][0])?$this->data['Address']['RegionUpdate'][0]:0;
						$province_id=isset($this->data['Address']['RegionUpdate'][1])?$this->data['Address']['RegionUpdate'][1]:0;
						$city_id=isset($this->data['Address']['RegionUpdate'][2])?$this->data['Address']['RegionUpdate'][2]:0;
						
						$region_data=$this->RegionI18n->find('list',array('fields'=>array('RegionI18n.region_id','RegionI18n.name'),'conditions'=>array('RegionI18n.region_id'=>array($country_id,$province_id,$city_id),'RegionI18n.locale'=>LOCALE)));
						
						$order_data['country']=isset($region_data[$country_id])?$region_data[$country_id]:'';
						$order_data['province']=isset($region_data[$province_id])?$region_data[$province_id]:'';
						$order_data['city']=isset($region_data[$city_id])?$region_data[$city_id]:'';
						$regions=$order_data['country']." ".$order_data['province']." ".$order_data['city'];
					}
					$order_data['regions']=$regions;
					$this->Order->save($order_data);
					$order_id=$this->Order->id;
					
					$order_message="订单创建失败";
					if(isset($order_id)&&!empty($order_id)){
						$order_product_data=array();
						$order_product_data['id']=0;
						$order_product_data['order_id']=$order_id;
						$order_product_data['product_id']=$product_info['Product']['id'];
						$order_product_data['product_name']=$product_info['ProductI18n']['name'];
						$order_product_data['product_code']=$product_code;
						$order_product_data['product_quntity']='1';
						$order_product_data['product_price']=$product_info['Product']['shop_price'];
						$this->OrderProduct->saveAll($order_product_data);
						
						$update_proudct=$product_info;
						$DisupdateList_code = ClassRegistry::init('DisupdateList')->find('list', array('fields' => array('DisupdateList.product_code')));
						if (!in_array($update_proudct['Product']['code'], $DisupdateList_code) && isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 1) {
							$update_proudct['Product']['frozen_quantity'] += 1;
			                            $update_proudct['Product']['quantity'] = $update_proudct['Product']['quantity'] - 1;
			                            $this->Product->updateAll(array('Product.frozen_quantity' => $update_proudct['Product']['frozen_quantity'], 'Product.quantity' => $update_proudct['Product']['quantity']), array('Product.id' => $update_proudct['Product']['id']));
			                            $this->Product->updateskupro($product_code, 1, true);
						}
						
						if($product_info['Product']['option_type_id']=='1'){
							$package_cond=array();
							$package_cond['or']['PackageProduct.product_code']=$product_code;
							$package_cond['or']['PackageProduct.product_id']=$product_info['Product']['id'];
							$package_info=$this->PackageProduct->find('all',array('conditions'=>$package_cond));
							
							foreach ($package_info as $package_k => $package_v) {
								$update_proudct=$this->Product->find('first',array('conditions'=>array('Product.code'=>$package_v['PackageProduct']['package_product_code'])));
								if(empty($update_proudct)){continue;}
								$order_product_data=array();
								$order_product_data['id']=0;
								$order_product_data['order_id']=$order_id;
								$order_product_data['product_id']=$update_proudct['Product']['id'];
								$order_product_data['product_name']=$package_v['PackageProduct']['package_product_name'];
								$order_product_data['product_code']=$package_v['PackageProduct']['product_code'];
								$order_product_data['product_quntity'] = $package_v['PackageProduct']['package_product_qty'];
								$this->OrderProduct->saveAll($order_product_data);
								
								if (!in_array($update_proudct['Product']['code'], $DisupdateList_code) && isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 1) {
									$update_proudct['Product']['frozen_quantity'] += $package_v['PackageProduct']['package_product_qty'];
					                            $update_proudct['Product']['quantity'] = $update_proudct['Product']['quantity'] - $package_v['PackageProduct']['package_product_qty'];
					                            $this->Product->updateAll(array('Product.frozen_quantity' => $update_proudct['Product']['frozen_quantity'], 'Product.quantity' => $update_proudct['Product']['quantity']), array('Product.id' => $update_proudct['Product']['id']));
					                            $this->Product->updateskupro($product_code, $package_v['PackageProduct']['package_product_qty'], true);
								}
							}
						}
						
						$VoucherEntityCard_data=array();
						$VoucherEntityCard_data['id']=$VoucherEntityCard_info['id'];
						$VoucherEntityCard_data['status']='2';
						$VoucherEntityCard_data['use_time']=date("Y-m-d H:i:s");
						$VoucherEntityCard_data['ipaddress']=$this->real_ip();
						$VoucherEntityCard_data['order_id']=$order_id;
						$this->VoucherEntityCard->save($VoucherEntityCard_data);
						$order_message="订单创建成功,订单号:{$order_code}";
						if(isset($_COOKIE['Voucher_Info'])&&!empty($_COOKIE['Voucher_Info'])){
			    				setcookie('Voucher_Info', '', time() - 60 * 60 * 24 * 14, '/');
			    			}
			    			$this->flash($order_message, '/orders/view/'.$order_id, 5);
					}else{
						echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$order_message.'");	window.history.go(-1);</script>';
            					die();
					}
				}else{
					$this->flash('该商品已下架或库存不足', '/vouchers/index', 5);
				}
			}else{
				if(isset($this->data)){
					$country_id=isset($this->data['Address']['RegionUpdate'][0])?$this->data['Address']['RegionUpdate'][0]:0;
					$province_id=isset($this->data['Address']['RegionUpdate'][1])?$this->data['Address']['RegionUpdate'][1]:0;
					$city_id=isset($this->data['Address']['RegionUpdate'][2])?$this->data['Address']['RegionUpdate'][2]:0;
					
					$region_data=$this->RegionI18n->find('list',array('fields'=>array('RegionI18n.region_id','RegionI18n.name'),'conditions'=>array('RegionI18n.region_id'=>array($country_id,$province_id,$city_id),'RegionI18n.locale'=>LOCALE)));
					
					$this->set('region_data',$region_data);
					$this->set('receiver_data',$this->data);
					
					$product_code=$Voucher_Info['VoucherEntityCard']['product_code'];
					$product_info=$this->Product->find('first',array('conditions'=>array('Product.code'=>$product_code)));
					if(!empty($product_info)&&$product_info['Product']['quantity']>0){
						$pro_cond=array();
						if($product_info['Product']['option_type_id']=='1'){
							$package_cond=array();
							$package_cond['or']['PackageProduct.product_code']=$product_code;
							$package_cond['or']['PackageProduct.product_id']=$product_info['Product']['id'];
							$package_info=$this->PackageProduct->find('list',array('fields'=>array('PackageProduct.id','PackageProduct.package_product_code'),'conditions'=>$package_cond));
							if(!empty($package_info)){
								$pro_cond=$package_info;
							}
						}
						$pro_cond[]=$product_code;
						$product_list=$this->Product->find('all',array('fields'=>array('Product.*','ProductI18n.name'),'conditions'=>array('Product.code'=>$pro_cond)));
						$this->set('product_list',$product_list);
					}else{
						$this->flash('该商品已下架或库存不足', '/vouchers/index', 5);
					}
				}else{
					$this->redirect('/vouchers/index');
				}
			}
		}else{
			$this->redirect('/vouchers/index');
		}
    }
    
    function ajax_info_confirm(){
    		Configure::write('debug', 1);
    		$this->layout = 'layout';
    		if ($this->RequestHandler->isPost()) {
    			$result=array();
    			$result['flag']="-1";
    			$result['message']="Data Error";
    			
    			$this->loadModel('RegionI18n');
			$this->loadModel('Shipping');
            		$this->loadModel('Order');
            		$this->loadModel('OrderProduct');
            		
            		$Voucher_Info=array();
            		if(isset($_COOKIE['Voucher_Info'])&&!empty($_COOKIE['Voucher_Info'])){
				$Voucher_Info=unserialize(stripslashes($_COOKIE['Voucher_Info']));
			}
			if(isset($_POST['order_created'])&&$_POST['order_created']=='1'&&!empty($_POST['receiver_data'])&&!empty($Voucher_Info)){
				$result['flag']="0";
				$receiver_data=isset($_POST['receiver_data'])?$_POST['receiver_data']:array();
				$this->data=json_decode($receiver_data,true);
				$VoucherEntityCard_info=$Voucher_Info['VoucherEntityCard'];
	    			$EntityCard_Data=$this->VoucherEntityCard->find('first',array('conditions'=>array('VoucherEntityCard.id'=>$VoucherEntityCard_info['id'],'VoucherEntityCard.status <>'=>'0')));
				if(empty($EntityCard_Data)||$EntityCard_Data['VoucherEntityCard']['status']=='3'){
					$result['data']='当前兑换券已失效';
					die(json_encode($result));
				}else if($EntityCard_Data['VoucherEntityCard']['status']=='2'){
					$result['data']='当前兑换券已使用';
					die(json_encode($result));
				}else if($EntityCard_Data['VoucherEntityCard']['status']=='4'){
					$result['data']='当前兑换券已冻结';
					die(json_encode($result));
				}
				$voucher_card_sn=$EntityCard_Data['VoucherEntityCard']['card_sn'];
				$product_code=$EntityCard_Data['VoucherEntityCard']['product_code'];
				$product_info=$this->Product->find('first',array('conditions'=>array('Product.code'=>$product_code)));
				if(!empty($product_info)&&$product_info['Product']['quantity']>0){
					$payment_data=$this->Payment->find('first',array('conditions'=>array('Payment.id'=>'23','Payment.status'=>'1')));
					$shipping_data=$this->Shipping->find('first',array('conditions'=>array('Shipping.id'=>'2','Shipping.status'=>'1')));
					
					$order_data=array();
					$order_data['id']='0';
					$order_data['order_code'] = $this->Order->get_order_code();
					$order_code = $this->Order->findbyorder_code($order_data['order_code']);
					if (isset($order_code) && count($order_code) > 0) {
						$order_data['order_code'] = $this->Order->get_order_code();
					}
					$order_code=$order_data['order_code'];
					$order_data['user_id']=$_SESSION['User']['User']['id'];
					$order_data['status']='1';
					$order_data['shipping_id']='2';
					$order_data['shipping_name']=isset($shipping_data['Shipping']['name'])?$shipping_data['Shipping']['name']:'快递';
					
					$order_data['payment_id']='23';
					$order_data['payment_status']='2';
					$order_data['payment_name']=isset($payment_data['Payment']['name'])?$payment_data['Payment']['name']:'在线支付';
					$order_data['payment_time']=date("Y-m-d H:i:s");
					$order_data['payment_fee']='0.00';
					$order_data['money_paid']=$product_info['Product']['shop_price'];
					$order_data['total']=$product_info['Product']['shop_price'];
					$order_data['subtotal']=$product_info['Product']['shop_price'];
					
					$order_data['consignee']=$this->data['consignee'];
					$order_data['address']=$this->data['address'];
					$order_data['zipcode']='';
					$order_data['telephone']='';
					$order_data['mobile']=$this->data['mobile'];
					$order_data['email']='';
					$order_data['note']="兑换券[{$voucher_card_sn}]抵扣";
					$regions="";
					if(isset($this->data['Address']['RegionUpdate'])){
						$country_id=isset($this->data['Address']['RegionUpdate'][0])?$this->data['Address']['RegionUpdate'][0]:0;
						$province_id=isset($this->data['Address']['RegionUpdate'][1])?$this->data['Address']['RegionUpdate'][1]:0;
						$city_id=isset($this->data['Address']['RegionUpdate'][2])?$this->data['Address']['RegionUpdate'][2]:0;
						
						$region_data=$this->RegionI18n->find('list',array('fields'=>array('RegionI18n.region_id','RegionI18n.name'),'conditions'=>array('RegionI18n.region_id'=>array($country_id,$province_id,$city_id),'RegionI18n.locale'=>LOCALE)));
						
						$order_data['country']=isset($region_data[$country_id])?$region_data[$country_id]:'';
						$order_data['province']=isset($region_data[$province_id])?$region_data[$province_id]:'';
						$order_data['city']=isset($region_data[$city_id])?$region_data[$city_id]:'';
						$regions=$order_data['country']." ".$order_data['province']." ".$order_data['city'];
					}
					$order_data['regions']=$regions;
					$this->Order->save($order_data);
					$order_id=$this->Order->id;
					
					$order_message="订单创建失败,请重新兑换";
					if(isset($order_id)&&!empty($order_id)){
						$order_product_data=array();
						$order_product_data['id']=0;
						$order_product_data['order_id']=$order_id;
						$order_product_data['product_id']=$product_info['Product']['id'];
						$order_product_data['product_name']=$product_info['ProductI18n']['name'];
						$order_product_data['product_code']=$product_code;
						$order_product_data['product_quntity']='1';
						$order_product_data['product_price']=$product_info['Product']['shop_price'];
						$this->OrderProduct->saveAll($order_product_data);
						
						$update_proudct=$product_info;
						$DisupdateList_code = ClassRegistry::init('DisupdateList')->find('list', array('fields' => array('DisupdateList.product_code')));
						if (!in_array($update_proudct['Product']['code'], $DisupdateList_code) && isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 1) {
							$update_proudct['Product']['frozen_quantity'] += 1;
			                            $update_proudct['Product']['quantity'] = $update_proudct['Product']['quantity'] - 1;
			                            $this->Product->updateAll(array('Product.frozen_quantity' => $update_proudct['Product']['frozen_quantity'], 'Product.quantity' => $update_proudct['Product']['quantity']), array('Product.id' => $update_proudct['Product']['id']));
			                            $this->Product->updateskupro($product_code, 1, true);
						}
						
						if($product_info['Product']['option_type_id']=='1'){
							$package_cond=array();
							$package_cond['or']['PackageProduct.product_code']=$product_code;
							$package_cond['or']['PackageProduct.product_id']=$product_info['Product']['id'];
							$package_info=$this->PackageProduct->find('all',array('conditions'=>$package_cond));
							
							foreach ($package_info as $package_k => $package_v) {
								$update_proudct=$this->Product->find('first',array('conditions'=>array('Product.code'=>$package_v['PackageProduct']['package_product_code'])));
								if(empty($update_proudct)){continue;}
								$order_product_data=array();
								$order_product_data['id']=0;
								$order_product_data['order_id']=$order_id;
								$order_product_data['product_id']=$update_proudct['Product']['id'];
								$order_product_data['product_name']=$package_v['PackageProduct']['package_product_name'];
								$order_product_data['product_code']=$package_v['PackageProduct']['product_code'];
								$order_product_data['product_quntity'] = $package_v['PackageProduct']['package_product_qty'];
								$this->OrderProduct->saveAll($order_product_data);
								
								if (!in_array($update_proudct['Product']['code'], $DisupdateList_code) && isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 1) {
									$update_proudct['Product']['frozen_quantity'] += $package_v['PackageProduct']['package_product_qty'];
					                            $update_proudct['Product']['quantity'] = $update_proudct['Product']['quantity'] - $package_v['PackageProduct']['package_product_qty'];
					                            $this->Product->updateAll(array('Product.frozen_quantity' => $update_proudct['Product']['frozen_quantity'], 'Product.quantity' => $update_proudct['Product']['quantity']), array('Product.id' => $update_proudct['Product']['id']));
					                            $this->Product->updateskupro($product_code, $package_v['PackageProduct']['package_product_qty'], true);
								}
							}
						}
						
						$VoucherEntityCard_data=array();
						$VoucherEntityCard_data['id']=$VoucherEntityCard_info['id'];
						$VoucherEntityCard_data['status']='2';
						$VoucherEntityCard_data['use_time']=date("Y-m-d H:i:s");
						$VoucherEntityCard_data['ipaddress']=$this->real_ip();
						$VoucherEntityCard_data['order_id']=$order_id;
						$this->VoucherEntityCard->save($VoucherEntityCard_data);
						$order_message="订单创建成功,订单号:{$order_code}";
						if(isset($_COOKIE['Voucher_Info'])&&!empty($_COOKIE['Voucher_Info'])){
			    				setcookie('Voucher_Info', '', time() - 60 * 60 * 24 * 14, '/');
			    			}
			    			$result['flag']="1";
			    			$result['order_id']=$order_id;
			    			$this->notify_voucher($order_data['user_id'],$order_id,$voucher_card_sn);
					}
					$result['message']=$order_message;
				}else{
					$result['data']='该商品已下架或库存不足';
				}
			}
			die(json_encode($result));
    		}else{
			$this->redirect('/vouchers/index');
		}
    }
    
    
    function voucher_check(){
    		Configure::write('debug', 0);
    		$this->layout = 'layout';
    		$error_counts = $this->Cookie->read('voucher_error');
    		$result=array();
    		$result['flag']='0';
    		$result['message']=$this->ld['failed'];
    		if ($this->RequestHandler->isPost()) {
    			$card_sn=isset($this->data['card_sn'])?$this->data['card_sn']:'';
    			$card_password=isset($this->data['card_password'])?$this->data['card_password']:'';
    			$cond['not']['VoucherEntityCard.status']='0';
    			$cond['VoucherEntityCard.batch_sn <>']='';
    			$cond['VoucherEntityCard.card_sn']=$card_sn;
    			$cond['VoucherEntityCard.card_password']=$card_password;
    			$EntityCard_Data=$this->VoucherEntityCard->find('first',array('conditions'=>$cond));
    			if(!empty($EntityCard_Data)){
    				$error_counts=0;
    				$message="";
    				if($EntityCard_Data['VoucherEntityCard']['status']=='1'){
    					$result['flag']='1';
    					$message=$this->ld['successfully'];
    				}else if($EntityCard_Data['VoucherEntityCard']['status']=='2'){
    					$result['flag']='2';
    					$message='卡号已使用';
    				}else if($EntityCard_Data['VoucherEntityCard']['status']=='3'){
    					$result['flag']='2';
    					$message='卡号已作废';
    				}else if($EntityCard_Data['VoucherEntityCard']['status']=='4'){
    					$result['flag']='2';
    					$message='卡号已冻结';
    				}
    			}else{
    				$error_counts++;
    				$message='无效兑换券,或密码错误';
    			}
    			$result['message']=$message;
    			$this->Cookie->write('voucher_error', $error_counts, false, time() + 600);
    			if($error_counts>=10){
    				//$this->VoucherEntityCard->updateAll(array('VoucherEntityCard.status'=>'3','VoucherEntityCard.frozen_time'=>"'".date("Y-m-d H:i:s")."'"),array('VoucherEntityCard.card_sn'=>$card_sn));
    				if(isset($_COOKIE['Voucher_Info'])&&!empty($_COOKIE['Voucher_Info'])){
	    				setcookie('Voucher_Info', '', time() - 60 * 60 * 24 * 14, '/');
	    			}
    			}
    		}
    		die(json_encode($result));
    }
    
    function search_amount(){
    		Configure::write('debug', 1);
    		$this->layout = 'layout';
    		$card_sn=isset($_POST['card_sn'])?$_POST['card_sn']:'';
    		$VoucherEntityCard_data=$this->VoucherEntityCard->find('first',array('conditions'=>array("VoucherEntityCard.card_sn"=>$card_sn,'VoucherEntityCard.status <>'=>'0')));
    		
    		$result=array();
    		$result['flag']='0';
    		$result['data']=array();
    		if(!empty($VoucherEntityCard_data)){
    			$result['flag']='1';
    			$result['data']=$VoucherEntityCard_data['VoucherEntityCard'];
    			
    			$product_code=$VoucherEntityCard_data['VoucherEntityCard']['product_code'];
    			$product_info=$this->Product->find('first',array('conditions'=>array('Product.code'=>$product_code)));
    			$result['product_info']=$product_info;
    		}
    		die(json_encode($result));
    }
    
    function notify_voucher($user_id,$order_id,$voucher_card_sn){
    		$user_data = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
    		if(!empty($user_data)){
    			$this->loadModel('Order');
    			$this->loadModel('OrderProduct');
			$this->loadModel('SynchroUser');
			$this->loadModel('NotifyTemplateType');
    			$synchro_user = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.status' => 1, 'SynchroUser.type' => 'wechat','SynchroUser.user_id'=>$user_id)));
    			if(empty($synchro_user))return false;
    			$order_data=$this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id,'Order.user_id'=>$user_id)));
    			if(empty($order_data))return false;
    			$touser=$synchro_user['SynchroUser']['account'];
    			$notify_template_info=$this->NotifyTemplateType->typeformat("voucher_success","wechat");
			$notify_template=isset($notify_template_info['wechat'])?$notify_template_info['wechat']:array();
			$wechat_params=$this->NotifyTemplateType->wechatparamsformat($notify_template);
			$action_content="您已成功使用兑换券".$voucher_card_sn;
			$order_code=$order_data['Order']['order_code'];
			$product_name=isset($order_data['OrderProduct'][0]['product_name'])?$order_data['OrderProduct'][0]['product_name']:'';
			$product_quantity=isset($order_data['OrderProduct'][0]['product_quntity'])?$order_data['OrderProduct'][0]['product_quntity']:1;
			$action_time=date('Y-m-d H:i:s');
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
	   			'url'=>$this->server_host.'/orders/view/'.$order_id,
	   			'data'=>$wechat_message
	   		);
	   		$this->Notify->wechat_message($wechat_post);
    		}
    }
    
    public function real_ip(){
            static $realip = null;
            if ($realip !== null) {
                return $realip;
            }

            if (isset($_SERVER)) {
                if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr as $ip) {
                    $ip = trim($ip);

                    if ($ip != 'unknown') {
                        $realip = $ip;

                        break;
                    }
                }
                } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                    $realip = $_SERVER['HTTP_CLIENT_IP'];
                } else {
                    if (isset($_SERVER['REMOTE_ADDR'])) {
                        $realip = $_SERVER['REMOTE_ADDR'];
                    } else {
                        $realip = '0.0.0.0';
                    }
                }
            } else {
                if (getenv('HTTP_X_FORWARDED_FOR')) {
                    $realip = getenv('HTTP_X_FORWARDED_FOR');
                } elseif (getenv('HTTP_CLIENT_IP')) {
                    $realip = getenv('HTTP_CLIENT_IP');
                } else {
                    $realip = getenv('REMOTE_ADDR');
                }
            }
            preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
            $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
            return $realip;
        }
}
