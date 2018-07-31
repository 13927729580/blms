<?php

uses('sanitize');

/**
 * 这是一个名为 OrdersController 的订单控制器.
 */
class OrdersController extends AppController
{
    /*
     * @var $name
     * @var $components
     * @var $helpers
     * @var $uses
     */
    public $name = 'Orders';
    public $components = array('Pagination', 'RequestHandler','Notify'); // Added
    public $helpers = array('Pagination'); // Added
    public $uses = array('Shipping','Application', 'Order', 'OrderProduct','OrderProductValue','OrderAction','Product', 'Payment', 'PaymentApiLog', 'UserPointLog', 'UserBalanceLog','LogisticsCompany','CouponType','CouponProduct','Coupon','UserAddress','UserFans','Blog','UserApp','Comment','Attribute','OrderProductValue','OrderShipment','OrderShipmentProduct');
    public $layout = 'default_user';

    /* 函数 index 订单列表
     * @param $page
     * @param $limit
     */
    public function index($page = 1, $limit = 5)
    {
        //登录验证
        $this->checkSessionUser();
        $this->page_init();      //页面初始化
        $this->layout = 'usercenter';
	if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
		Configure::write('debug', 0);
		$this->layout = 'ajax';
	}
        //页面标题
        $this->pageTitle = $this->ld['order_list'].' - '.sprintf($this->ld['page'], $page).' - '.$this->configs['shop_title'];
        //当前位置开始
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['account_orders'], 'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        
        //取得我的订单
        $condition=array();
        $condition['Order.user_id'] = $_SESSION['User']['User']['id'];
        
        //分页start
        //get参数
        $limit = $limit;
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'orders', 'action' => 'index', 'page' => $page, 'limit' => $limit);
        //分页参数
        $options = array('page' => $page, 'show' => $limit, 'modelClass' => 'Order');
        $pages = $this->Pagination->init($condition, $parameters, $options); // Added
        //分页end
        //payment_status
        
        if(isset($_GET['payment_status'])){
        	$condition['Order.payment_status']=$_GET['payment_status'];
        }
        //pr($condition);
        $my_orders = $this->Order->my_list($condition, $limit, $page);
        //pr($my_orders);

        if (!empty($my_orders)) {
            $my_order_payment_ids = array();
            $my_order_user_ids = array();
            $my_order_ids = array();
            foreach ($my_orders as $k => $v) {
                $my_order_payment_ids[] = $v['Order']['payment_id'];
                $my_order_payment_ids[] = $v['Order']['sub_pay'];
                $my_order_user_ids = $v['Order']['user_id'];
                $my_order_ids[$k] = $v['Order']['id'];
                //去掉优惠后，我需要付款的总额
                $my_orders[$k]['Order']['need_paid'] = number_format($v['Order']['total'] - $v['Order']['money_paid'] - $v['Order']['point_fee'] - $v['Order']['discount'] - $v['Order']['coupon_fee'], 2, '.', '') + 0;
            }

            $op_condition = array('OrderProduct.order_id' => $my_order_ids,'ProductI18n.locale' => $this->locale);
            $order_product = $this->OrderProduct->get_my_orders_products($op_condition);
            $payment_Info_list = $this->Payment->find('all', array('fields' => array('Payment.id,Payment.is_cod,PaymentI18n.name'), 'conditions' => array('Payment.id' => $my_order_payment_ids)));

            $order_address_list = $this->UserAddress->find('all', array('fields' => array('UserAddress.id', 'UserAddress.consignee', 'UserAddress.user_id'), 'conditions' => array('UserAddress.user_id' => $my_order_user_ids), 'order' => 'UserAddress.created desc', 'group' => 'UserAddress.user_id'));
            foreach ($my_orders as $k => $v) {
                $my_orders[$k]['Order']['sub_pay_name'] = '';
                 //获取该订单使用的付款方式
                 foreach ($payment_Info_list as $kk => $vv) {
                     if ($v['Order']['payment_id'] == $vv['Payment']['id']) {
                         $my_orders[$k]['Order']['payment_name'] = $vv['PaymentI18n']['name'];
                         $my_orders[$k]['Order']['payment_is_cod'] = $vv['Payment']['is_cod'];
                     }
                     if ($v['Order']['sub_pay'] == $vv['Payment']['id']) {
                         $my_orders[$k]['Order']['sub_pay_name'] = $vv['PaymentI18n']['name'];
                     }
                 }
                if (empty($v['Order']['consignee'])) {
                    //获取该订单的收货人
                    foreach ($order_address_list as $kkk => $vvv) {
                        if ($v['Order']['user_id'] == $vvv['UserAddress']['id']) {
                            $my_orders[$k]['Order']['consignee'] = $vvv['UserAddress']['consignee'];
                        }
                    }
                }
                if (count($order_product) > 0) {
                    foreach ($order_product as $ok => $ov) {
                        if ($v['Order']['id'] == $ov['OrderProduct']['order_id']) {
                            $ov['Product']['quntity'] = $ov['OrderProduct']['product_quntity'] - $ov['OrderProduct']['refund_quantity'];
                            $my_orders[$k]['OrderProduct'][] = $ov['OrderProduct'];
                            $my_orders[$k]['Product'][] = $ov['Product'];
                            $my_orders[$k]['ProductI18n'][] = $ov['ProductI18n'];
                        }
                    }
                }
            }
        }
        if (isset($_SESSION['User']['User']['id'])) {
            $id = $_SESSION['User']['User']['id'];
            $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
            $this->set('user_list', $user_list);
            //分享绑定显示判断
            $app_share = $this->UserApp->app_status();
            $this->set('app_share', $app_share);
            //粉丝数量
            $fans = $this->UserFans->find_fanscount_byuserid($id);
            $this->set('fanscount', $fans);
            //日记数量
            $blog = $this->Blog->find_blogcount_byuserid($id);
            $this->set('blogcount', $blog);
            //关注数量
            $focus = $this->UserFans->find_focuscount_byuserid($id);
            $this->set('focuscount', $focus);
        }

        $payment_status_order = array();
        $need_pay = array();
        foreach ($my_orders as $k => $v) {
            if($v['Order']['payment_status']==0){
                $payment_status_order[] = $v['Order']['payment_status'];
            }
            $need_pay_a = number_format($v['Order']['total'] - $v['Order']['point_fee'] - $v['Order']['discount'] - $v['Order']['coupon_fee'] - $v['Order']['user_balance']-$v['Order']['money_paid'], 2, '.', '') + 0;
            if($need_pay_a!=0){
                $need_pay[$v['Order']['id']] = $need_pay_a;
            }
        }
        //pr($my_orders);
        //pr($need_pay);
        $this->set('need_pay', $need_pay);
        $this->set('my_orders', $my_orders);
    }

    /**
     * 函数 view 订单详细.
     *
     * @param $id
     */
    public function view($id)
    {
        //登录验证
        $this->checkSessionUser();

        $this->page_init();      //页面初始化
        $this->layout = 'usercenter';
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
			Configure::write('debug', 0);
			$this->layout = 'ajax';
		}
        $flash_url = '/orders';
        //订单详细 /*****订单部分的处理********/
        $condition['Order.id'] = $id;
        $condition['Order.user_id'] = $_SESSION['User']['User']['id'];
        $order_info = $this->Order->find('first', array('conditions' => $condition, 'recursive' => '-1'));
        if (empty($order_info)) {
            $this->redirect('/orders');
        }
        $shipping_info = $this->Shipping->find('first', array('conditions' => array('Shipping.id' => $order_info['Order']['shipping_id'])));
        $this->set('shipping_info', $shipping_info);
        $this->Region->set_locale($this->locale);
        $regions_info = $this->Region->find('all');
	
        //页面标题
        $this->pageTitle = $this->ld['account_orders'].' - '.$this->configs['shop_title'];

        //当前位置开始
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['account_orders'], 'url' => '/orders');
        $this->ur_heres[] = array('name' => $order_info['Order']['order_code'], 'url' => '');
        $this->set('ur_heres', $this->ur_heres);

        /* 只有未确认才允许用户修改订单地址 */
        $order_info['Order']['allow_update_address'] = $order_info['Order']['status'] == 0 ? '1' : '0'; //允许修改收货地址
        //支付方式信息
        $payment_info = array();
        $payment_info = $this->Payment->find('first', array('conditions' => array('Payment.id' => $order_info['Order']['payment_id'])));
	  $this->set('payment_info', $payment_info);
        $sub_pay_name = array();
        if (!empty($payment_info)) {
            $order_info['Order']['payment_is_cod'] = $payment_info['Payment']['is_cod'];
		$sub_paylist = $this->Payment->getOrderChildPayments($order_info['Order']['payment_id']);
		$this->set('sub_paylist', $sub_paylist);
            foreach ($sub_paylist as $v) {
                $sub_pay_name[$v['Payment']['id']] = $v['PaymentI18n']['name'];
            }
        }
        $this->set('sub_pay_name', $sub_pay_name);
        /* 支付时间 发货时间 */
        //如果已付款
        $order_info['Order']['payment_time'] = $order_info['Order']['payment_status'] != 0 ? sprintf($this->ld['pay_in'], $order_info['Order']['payment_time']) : '';

        //如果已发货
        $order_info['Order']['shipping_time'] = in_array($order_info['Order']['shipping_status'], array(1, 2)) ? sprintf($this->ld['shipping_in'], $order_info['Order']['shipping_time']) : '';

        //是否使用积分
        if ($order_info['Order']['point_use'] > 0) {
            $point_log_filter = '1=1';
            $point_log_filter .= ' and UserPointLog.type_id = '.$id.' and UserPointLog.user_id = '.$_SESSION['User']['User']['id']." and UserPointLog.log_type = 'O'";
            $point_log = $this->UserPointLog->find($point_log_filter);
            $this->set('point_log', $point_log);
        }
        //是否参加促销活动 优惠了多少

        //是否使用余额
        if ($payment_info['Payment']['code'] == 'account_pay') {
            $balance_log_filter = '1=1';
            $balance_log_filter .= ' and UserBalanceLog.type_id = '.$id.' and UserBalanceLog.user_id = '.$_SESSION['User']['User']['id']." and UserBalanceLog.log_type = 'O'";
            $balance_log = $this->UserBalanceLog->find($balance_log_filter);
            $this->set('balance_log', $balance_log);
        }
        //订单商品详细  /*****订单商品部分的处理********/
        $condition = array("OrderProduct.order_id"=>$order_info['Order']['id']);
        $order_products = $this->OrderProduct->get_order_product($condition);
        $order_product_value = $this->OrderProductValue->get_order_product_value($order_info['Order']['id']);
        if (!empty($order_product_value)) {
            //查询所有属性信息
            $this->Attribute->set_locale(LOCALE);
            $all_attr_list = array();
            $all_attr_options=array();
            $all_attr_info = $this->Attribute->find('all', array('fields' => array('Attribute.id,AttributeI18n.name'), array('conditions' => array('Attribute.status' => 1))));
            foreach ($all_attr_info as $v) {
                	$all_attr_list[$v['Attribute']['id']] = $v['AttributeI18n']['name'];
                	if(!empty($v['AttributeOption'])){
	                	foreach($v['AttributeOption'] as $vv){
	                		$all_attr_options[$vv['attribute_id']][$vv['option_value']]=$vv['option_name'];
	                	}
                	}
            }
            $this->set('all_attr_list', $all_attr_list);
            $this->set('all_attr_options', $all_attr_options);
        }
        $this->set('order_product_value', $order_product_value);
        $this->loadModel('OrderProductMedia');
        $order_product_medias=$this->OrderProductMedia->find('all',array('conditions'=>array('OrderProductMedia.order_id'=>$id,'OrderProductMedia.media_group'=>2)));
        $this->set('order_product_medias',$order_product_medias);
        $extension_code_count = 0;
        $order_product_ids = array();
        $order_virtual_products = array();
        $order_package_products = array();
        if (isset($order_products) && sizeof($order_products) > 0) {
            foreach ($order_products as $k => $v) {
            		if(isset($v['OrderProduct']['item_type'])&&$v['OrderProduct']['item_type']==''||!isset($v['OrderProduct']['item_type'])){
				//获取该订单的商品ID
				$order_product_ids[] = $v['OrderProduct']['product_id'];
				$order_product_code[$v['OrderProduct']['product_id']][$v['OrderProduct']['product_code']] = $v['OrderProduct']['product_code'];
			}
            }
            //如果该订单不为空，根据ID取出商品
            if (!empty($order_product_ids)) {
                	$product_list = $this->Product->return_lists($order_product_ids);
            }
            if (!empty($order_product_code)) {
                $this->loadModel('SkuProduct');
                $sku_product_list = $this->SkuProduct->sale_sku_product($order_product_code);
            }
            foreach ($order_products as $k => $v) {
			if(isset($product_list[$v['OrderProduct']['product_id']]['Product'])){
				$product_list[$v['OrderProduct']['product_id']]['Product']['shop_price'] = $v['OrderProduct']['product_price'];
				$product_list[$v['OrderProduct']['product_id']]['Product']['product_attrbute'] = $v['OrderProduct']['product_attrbute'];
				$order_products[$k]['Product'] = $product_list[$v['OrderProduct']['product_id']]['Product'];
			}
			if(isset($product_list[$v['OrderProduct']['product_id']]['ProductI18n']))$order_products[$k]['ProductI18n'] = $product_list[$v['OrderProduct']['product_id']]['ProductI18n'];
			//套装子商品显示处理
			if (isset($product_list[$v['OrderProduct']['product_id']]['Product'])&&$v['OrderProduct']['parent_product_id'] != 0) {
				$order_package_products[$v['OrderProduct']['parent_product_id']][$k] = $order_products[$k];
				unset($order_products[$k]);
				continue;
			}
			if (isset($product_list[$v['OrderProduct']['product_id']]['Product'])&&isset($sku_product_list[$v['OrderProduct']['product_id']]) && !empty($sku_product_list[$v['OrderProduct']['product_id']]['sku_product'])) {
				//销售属性显示处理
				$order_products[$k]['Product']['sku_product'] = $sku_product_list[$v['OrderProduct']['product_id']]['sku_product'];
			}
            }
        }
        
        $need_pay = number_format($order_info['Order']['total'] - $order_info['Order']['point_fee'] - $order_info['Order']['discount'] - $order_info['Order']['coupon_fee'] - $order_info['Order']['user_balance']-$order_info['Order']['money_paid'], 2, '.', '') + 0;
        $this->set('need_pay', $need_pay);
        //该订单需要付的总价格
        $order_info['Order']['need_paid'] = $need_pay;
        $this->set('order_info', $order_info);
        $this->set('order_products', $order_products);
        $this->set('order_package_products', $order_package_products);
        $company_info = $this->LogisticsCompany->find('first', array('conditions' => array('LogisticsCompany.id' => $order_info['Order']['logistics_company_id'])));
        $this->set('company_info', $company_info);
        if (isset($_SESSION['User']['User']['id'])) {
            //pr($_SESSION['User']['User']['id']);
            $user_id = $_SESSION['User']['User']['id'];
            $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
            $this->set('user_list', $user_list);
            //粉丝数量
            $fans = $this->UserFans->find_fanscount_byuserid($user_id);
            $this->set('fanscount', $fans);
            //日记数量
            $blog = $this->Blog->find_blogcount_byuserid($user_id);
            $this->set('blogcount', $blog);
            //关注数量
            $focus = $this->UserFans->find_focuscount_byuserid($user_id);
            $this->set('focuscount', $focus);
        }
        if ($order_info['Order']['coupon_id'] != '') {
            $coupon_arr = explode(',', $order_info['Order']['coupon_id']);
            if (!empty($coupon_arr)) {
                $coupon_infos = $this->Coupon->find('list', array('conditions' => array('Coupon.id' => $coupon_arr), 'fields' => 'Coupon.coupon_type_id'));
                $this->set('coupon_infos', $coupon_infos);
                $coupon_type_infos = $this->CouponType->getCouponName($coupon_infos);
                $coupon_name_arr = array();
                foreach ($coupon_infos as $ci) {
                    if (!in_array($coupon_type_infos[$ci], $coupon_name_arr)) {
                        $coupon_name_arr[] = $coupon_type_infos[$ci];
                    }
                }
                $this->set('coupon_name_arr', $coupon_name_arr);
            }
        }
    }
    
    public function product_view($order_product_id=0){
		//登录验证
		$this->checkSessionUser();
		$this->page_init();      //页面初始化
		$this->layout = 'usercenter';
		
		$user_id = $_SESSION['User']['User']['id'];
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
		$this->set('user_list', $user_list);
		//粉丝数量
		$fans = $this->UserFans->find_fanscount_byuserid($user_id);
		$this->set('fanscount', $fans);
		//日记数量
		$blog = $this->Blog->find_blogcount_byuserid($user_id);
		$this->set('blogcount', $blog);
		//关注数量
		$focus = $this->UserFans->find_focuscount_byuserid($user_id);
		$this->set('focuscount', $focus);
		
		$conditions=array();
		$conditions['Order.user_id']=$user_id;
		$conditions['OrderProduct.id']=$order_product_id;
		$conditions['OrderProduct.del_status']='1';
		$order_product_info=$this->OrderProduct->find('first',array('conditions'=>$conditions));
		if(empty($order_product_info))$this->redirect('index');
		if($order_product_info['Order']['service_type']=='virtual'){
			if($order_product_info['OrderProduct']['item_type']=='course')$this->redirect('/courses/view/'.$order_product_info['OrderProduct']['product_id']);
			if($order_product_info['OrderProduct']['item_type']=='evaluation')$this->redirect('/evaluations/view/'.$order_product_info['OrderProduct']['product_id']);
			if($order_product_info['OrderProduct']['item_type']=='activity')$this->redirect('/activities/view/'.$order_product_info['OrderProduct']['product_id']);
			if($order_product_info['OrderProduct']['item_type']=='course_class'){
				$this->loadModel('CourseClass');
				$course_class_detail=$this->CourseClass->find('first',array('fields'=>'CourseClass.id,Course.id','conditions'=>array('CourseClass.status'=>'1','CourseClass.id'=>$order_product_info['OrderProduct']['product_id'])));
				if(!empty($course_class_detail))$this->redirect('/courses/detail/'.$course_class_detail['Course']['id'].'/'.$order_product_info['OrderProduct']['product_id']);
			}
		}
		$order_info=$order_product_info['Order'];
		$this->set('order_info',$order_info);
		
		$order_product_data=$order_product_info;
		unset($order_product_data['Order']);
		$this->set('order_product_data',$order_product_data);
		
		$this->loadModel('OrderProductMedia');
		$order_product_medias=$this->OrderProductMedia->find('all',array('fields'=>'order_product_id,media','conditions'=>array('OrderProductMedia.order_id'=>$order_info['id'],'OrderProductMedia.order_product_id'=>$order_product_id,'type'=>'image','media <>'=>'')));
        $this->set('order_product_medias',$order_product_medias);
		
		$order_product_value = $this->OrderProductValue->get_order_product_value($order_info['id']);
		$this->set('order_product_value',$order_product_value);
		if (!empty($order_product_value)) {
			//查询所有属性信息
			$this->Attribute->set_locale(LOCALE);
			$all_attr_list = array();
			$all_attr_options=array();
			$all_attr_info = $this->Attribute->find('all', array('fields' => array('Attribute.id,AttributeI18n.name'), array('conditions' => array('Attribute.status' => 1))));
			foreach ($all_attr_info as $v) {
				$all_attr_list[$v['Attribute']['id']] = $v['AttributeI18n']['name'];
				if(!empty($v['AttributeOption'])){
					foreach($v['AttributeOption'] as $vv){
						$all_attr_options[$vv['attribute_id']][$vv['option_value']]=$vv['option_name'];
					}
				}
			}
			$this->set('all_attr_list', $all_attr_list);
			$this->set('all_attr_options', $all_attr_options);
		}
        	$company_info = $this->LogisticsCompany->find('first', array('conditions' => array('LogisticsCompany.id' => $order_product_info['Order']['logistics_company_id'])));
        	$this->set('company_info', $company_info);

		$order_product_medias_1=$this->OrderProductMedia->find('all',array('conditions'=>array('OrderProductMedia.order_id'=>$order_info['id'],'OrderProductMedia.order_product_id'=>$order_product_id,'OrderProductMedia.location <>'=>'','OrderProductMedia.media_group'=>'1')));
		$order_product_medias_2=$this->OrderProductMedia->find('all',array('conditions'=>array('OrderProductMedia.order_id'=>$order_info['id'],'OrderProductMedia.order_product_id'=>$order_product_id,'OrderProductMedia.media_group'=>'2')));
		$this->set('order_product_medias_1', $order_product_medias_1);
		$this->set('order_product_medias_2', $order_product_medias_2);

		$order_shipment_product_detail=$this->OrderShipmentProduct->find('first',array('conditions'=>array('OrderShipmentProduct.order_product_id'=>$order_product_id)));
		$shipment_conditions=array();
		$shipment_conditions['OrderShipment.order_id']=$order_info['id'];
		if(!empty($order_shipment_product_detail)){
			$shipment_conditions['OrderShipment.id']=$order_shipment_product_detail['OrderShipmentProduct']['order_shipment_id'];
		}
		$order_shipment_detail=$this->OrderShipment->find('first',array('conditions'=>$shipment_conditions));
		$this->set('order_shipment_detail',$order_shipment_detail);
		
		$informationresource_infos = $this->InformationResource->code_information_formated(array('clothes_location'), $this->locale);
		$this->set('informationresource_infos', $informationresource_infos);
    }
    
    function finish_order_product($order_product_id=0){
    		Configure::write('debug', 1);
		$this->layout = 'ajax';
    		//登录验证
		$this->checkSessionUser();
		$user_id = $_SESSION['User']['User']['id'];
		
		$conditions=array();
		$conditions['Order.user_id']=$user_id;
		$conditions['OrderProduct.id']=$order_product_id;
		$conditions['OrderProduct.del_status']='1';
		$conditions['OrderProduct.del_status']='1';
		$order_product_info=$this->OrderProduct->find('first',array('conditions'=>$conditions));
    }
    
    /**
     * 函数 user_cancle_order 取消订单.
     *
     * @param $order_id
     */
    public function cancle_order($order_id)
    {
	Configure::write('debug', 1);
	$this->layout = 'ajax';
        //登录验证
        $this->checkSessionUser();
        $user_id = $_SESSION['User']['User']['id'];
        if ($this->Order->is_mine($order_id, $user_id)) {
            $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));
            if($order_info['Order']['shipping_status']!="0"&&$order_info['Order']['shipping_status']!="6"){
            		if(isset($_POST['is_ajax'])&&$_POST['is_ajax']=='1'){
				$result=array('code'=>'0','message'=>'订单已无法取消');
				die(json_encode($result));
			}else{
				//显示的页面
				$this->redirect('/orders');
			}
            }
            
            $order_code=$order_info['Order']['order_code'];
            $users = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
            $order = array(
                'id' => $order_id,
                'status' => 2,
            );
            $this->Order->save($order);
	     
	     //产生订单操作记录
            $this->OrderAction->saveAll(array('OrderAction' => array(
	            'order_id' => $order_id,
	            'from_operator_id' => 0,
	            'user_id' => $_SESSION['User']['User']['id'],
	            'order_status' => 2,
	            'payment_status' => $order_info['Order']['payment_status'],
	            'shipping_status' => $order_info['Order']['shipping_status'],
	            'action_note' => $this->ld['cancel_order'],
            )));
            //数量还原
            $products = array();
            $products = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $order_id,'OrderProduct.status' => 1)));
            $products_info = '';
            foreach ($products as $k => $v) {
                $product_id = $v['OrderProduct']['product_id'];
                if ($v['Order']['shipping_status']!= 1&&isset($v['Product']['quantity'])) {
                    //未发货的情况 未付款的
                    $product_quntity = $v['Product']['quantity'] + $v['OrderProduct']['product_quntity'];
                    $product_frozen_quantity = $v['Product']['frozen_quantity'] - $v['OrderProduct']['product_quntity'];
                    $update_product = array('id' => $product_id,'frozen_quantity' => $product_frozen_quantity,'quantity'=>$product_quntity);
                    $this->Product->save($update_product);
                }
            }
            //还原下单时抵扣掉的积分
            $point_use=$order_info['Order']['point_use'];
            if (!empty($point_use)) {
                $user_point = ($users['User']['point'] + $point_use);
                $order = array(
                    'id' => $user_id,
                    'point' => $user_point
                );
                $this->User->save($order);
                $point_log_new = array('id' => '', 'user_id' => $_SESSION['User']['User']['id'],'point'=>$users['User']['point'], 'point_change' =>$point_use, 'log_type' => 'O', 'system_note' => $this->ld['cancel_order'].":".$order_code, 'type_id' => $order_id);
                $this->UserPointLog->save($point_log_new);
                $this->UserPointLog->point_notify($point_log_new);
            }
		
            //还原用户余额支付的金额
            if (!empty($order_info['Order']['user_balance']) && $order_info['Order']['user_balance'] > 0) {
                $user_balance = $users['User']['balance'] + $order_info['Order']['user_balance'];
                $order_user = array(
                    'id' => $user_id,
                    'balance' => $user_balance,
                );
                $this->User->save($order_user);
                //余额记录支付记录
                if (!empty($user_balance) && $user_balance > 0) {
                    $balance_log = array(
                            'user_id' => $user_id,
                            'amount' => $order_info['Order']['user_balance'],
                            'log_type' => 'O',
                            'system_note' => '订单退款:'.$order_info['Order']['order_code'],
                            'type_id' => $order_id,
                    );
                    $this->UserBalanceLog->save($balance_log);
                }
            }
            		$this->notify_order_cancel($order_id);
			if(isset($_POST['is_ajax'])&&$_POST['is_ajax']=='1'){
				$result=array('code'=>'1','message'=>$this->ld['canceled']);
				die(json_encode($result));
			}else{
				//显示的页面
				$this->redirect('/orders/view/'.$order_id.'?order_status=2');
			}
        } else {
			if(isset($_POST['is_ajax'])&&$_POST['is_ajax']=='1'){
				$result=array('code'=>'0','message'=>'对不起，请选择自己的订单');
				die(json_encode($result));
			}else{
				//跳转到提示页
				$this->flash('对不起，请选择自己的订单', '/orders', '');
			}
        }
    }

    //确认收货  如果有积分则将积分插入到用户表中并产生相应的日志
    public function receiving_order($order_id, $where = 0)
    {
        //登录验证
        $this->checkSessionUser();
        if ($this->Order->is_mine($order_id, $_SESSION['User']['User']['id'])) {
        	$order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));
        	if($order_info['Order']['shipping_status']=='2'){
			if ($where == 1) {
				$this->redirect('/orders/view/'.$order_id);
			} else {
				$this->redirect('/orders');
			}
        	}
            $order = array(
                'id' => $order_id,
                'shipping_status' => 2,
            );
            $this->Order->save($order);
            //产生订单操作记录
            $this->OrderAction->saveAll(array('OrderAction' => array(
            'order_id' => $order_id,
            'from_operator_id' => 0,
            'user_id' => $_SESSION['User']['User']['id'],
            'order_status' => $order_info['Order']['status'],
            'payment_status' => $order_info['Order']['payment_status'],
            'shipping_status' => 2,
            'action_note' => $this->ld['confirm_receipt'],
            )));
            $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));
            //如果有积分的话
            $this->check_point($order_info);
            //如果有优惠券的话
            $this->check_coupon($order_info);
		
            $order_product_ids = array();
            foreach ($order_info['OrderProduct'] as $v) {
                $order_product_ids[] = $v['id'];
            }
            $OrderProductValue_count = $this->OrderProductValue->find('count', array('conditions' => array('OrderProductValue.order_product_id' => $order_product_ids)));
            if ($OrderProductValue_count > 0) {
                $this->loadModel('UserRank');
                $this->UserRank->user_upgrade_vip($_SESSION['User']['User']['id']);
            }
            $this->notify_confirm_receipt($order_id);
            //显示的页面
            if ($where == 1) {
                $this->redirect('/orders/view/'.$order_id);
            } else {
                $this->redirect('/orders');
            }
        } else {
            //跳转到提示页
            $this->flash('对不起，请选择自己的订单', '/orders', '');
        }
    }
    /**
     * 函数 check_coupon 修改支付方式.
     *
     * @param $order_info
     *
     * @author chenfan 2012/05/29
     */
    public function check_coupon($order_info)
    {
        $product_coupon_type = array();
        $order_coupon_type = array();
        $now = date('Y-m-d H:i:s');
          //取出order优惠券
        $order_coupon_type = $this->CouponType->find('all', array('conditions' => "CouponType.send_type = 2 and CouponType.send_start_date <= '".$now."' and CouponType.send_end_date >= '".$now."'"));
        //取出商品的优惠券
        if (isset($order_info['OrderProduct']) && sizeof($order_info['OrderProduct']) > 0) {
            $p_ids = array();
            $p_nums = array();
            $coupon_nums = array();
            foreach ($order_info['OrderProduct'] as $op) {
                $p_ids[] = $op['product_id'];
                $p_nums[$op['product_id']] = $op['product_quntity'];
            }
            if (!empty($p_ids)) {
                $coupon_type_ids = $this->CouponProduct->find('list', array('conditions' => array('CouponProduct.product_id' => $p_ids), 'fields' => 'CouponProduct.product_id,CouponProduct.coupon_type_id'));
                if (!empty($coupon_type_ids)) {
                    foreach ($coupon_type_ids as $k => $v) {
                        if (!isset($coupon_nums[$v])) {
                            $coupon_nums[$v] = 0;
                        }
                        $coupon_nums[$v] += $p_nums[$k];
                    }
                    $conditions = array();
                    $conditions['CouponType.id'] = $coupon_type_ids;
                    $conditions['CouponType.send_start_date <='] = $now;
                    $conditions['CouponType.send_end_date >='] = $now;
                    $product_coupon_type = $this->CouponType->find('all', array('conditions' => $conditions));
                }
            }
        }
        $coupon_types = array_merge($order_coupon_type, $product_coupon_type);
        if (is_array($coupon_types) && sizeof($coupon_types) > 0) {
            $coupon_arr_list = $this->Coupon->find('list', array('conditions' => array('1=1'), 'fields' => array('Coupon.sn_code')));//标注
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
            $order_coupon = array();
            foreach ($coupon_types as $k => $v) {
                if ($v['CouponType']['min_products_amount'] < $order_info['Order']['subtotal']) {
                    if (isset($coupon_sn)) {
                        $num = $coupon_sn;
                    }
                    $num = substr($num, 2, 10);
                    $num = $num ? floor($num / 10000) : 100000;
                    $coupon_sn = $v['CouponType']['prefix'].$num.str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                    if (isset($coupon_nums[$v['CouponType']['id']]) && $coupon_nums[$v['CouponType']['id']] > 0) {
                        for ($i = 1;$i <= $coupon_nums[$v['CouponType']['id']];++$i) {
                            $order_coupon[] = array(
                                        'id' => '',
                                        'coupon_type_id' => $v['CouponType']['id'],
                                        'user_id' => $order_info['Order']['user_id'],
                                        'sn_code' => $coupon_sn,
                                        );
                            $coupon_sn = $v['CouponType']['prefix'].$num.str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                        }
                    } else {
                        $order_coupon[] = array(
                                        'id' => '',
                                        'coupon_type_id' => $v['CouponType']['id'],
                                        'user_id' => $order_info['Order']['user_id'],
                                        'sn_code' => $coupon_sn,
                                        );
                    }
                }
            }
            $this->Coupon->saveAll($order_coupon);
        }
    }
    
    /**
     * 函数 check_point 修改支付方式.
     *
     * @param $order_info
     *
     * @author chenfan 2012/05/29
     */
    public function check_point($order_info)
    {
    	 $payment_info = $this->Payment->find('first', array('conditions' => array('Payment.id' => $order_info['Order']['payment_id'], 'Payment.status' => 1)));
    	 $is_cod = $payment_info['Payment']['is_cod']== 1;
    	 if($is_cod){
        	$points_awarded_occasion=isset($this->configs['points_awarded_occasion'])?$this->configs['points_awarded_occasion']:'';//积分赠送场合
	 	if($order_info['Order']['lease_type']!='L'){
	 		$config_order_smallest=isset($this->configs['lease_order_smallest'])?$this->configs['lease_order_smallest']:0;
	 		if(in_array($points_awarded_occasion,array('0','3'))&&isset($this->configs['order_points'])&&$this->configs['out_lease_order_points'] > 0){
	    			$user_info = $this->User->findbyid($order_info['Order']['user_id']);
	    			$old_point=$user_info['User']['point'];
	    			$user_info['User']['point'] += $this->configs['order_points'];
	    			$user_info['User']['user_point'] += $this->configs['out_lease_order_points'];
	    			$this->User->save($user_info);
	    			$point_log = array('id' => '',
	                        'user_id' => $order_info['Order']['user_id'],
	                        'point' => $old_point,
	                        'point_change' => $this->configs['out_lease_order_points'],
	                        'log_type' => 'B',
	                        'system_note' => '下单送积分',
	                        'type_id' => $order_info['Order']['id'],
	                	);
	                	$this->UserPointLog->save($point_log);
	                	$this->UserPointLog->point_notify($point_log);
	    		}
	    		
	    		if(in_array($points_awarded_occasion,array('0','2'))&&$config_order_smallest <= $order_info['Order']['subtotal']&&$this->configs['out_order_points'] > 0){
	    			$user_info = $this->User->findbyid($order_info['Order']['user_id']);
				$old_point=$user_info['User']['point'];
				$user_info['User']['point'] += $this->configs['out_order_points'];
				$user_info['User']['user_point'] += $this->configs['out_order_points'];
				$this->User->save($user_info);
				$point_log = array('id' => '',
					'user_id' => $order_info['Order']['user_id'],
					'point' => $old_point,
					'point_change' => $this->configs['out_order_points'],
					'log_type' => 'B',
					'system_note' => '超过订单金额 '.$this->configs['order_smallest'].' 赠送积分',
					'type_id' => $order_info['Order']['id'],
				);
				$this->UserPointLog->save($point_log);
				$this->UserPointLog->point_notify($point_log);
		    	}
	 	}
	 }
	 
        $p = $this->Product->find('list',array("fields"=>"Product.id,Product.point"));
        $product_point = '';
        foreach ($order_info['OrderProduct'] as $v) {
            $product_point[] = array(
                    'point' => isset($p[$v['product_id']])?$p[$v['product_id']] * $v['product_quntity']:0,
                    'name' => $v['product_name'],
                    );
        }
        if (is_array($product_point) && sizeof($product_point) > 0) {
            foreach ($product_point as $k => $v) {
                if ($v['point'] > 0) {
                    $user_info = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
                    $old_point=$user_info['User']['point'];
                    $user_info['User']['point'] += $v['point'];
                    $user_info['User']['user_point'] += $v['point'];
                    $this->User->save($user_info);
                    $point_log = array('id' => '',
                            'user_id' => $_SESSION['User']['User']['id'],
                            'point' => $old_point,
                        	  'point_change'=>$v['point'],
                            'log_type' => 'B',
                            'system_note' => '商品 '.$v['name'].' 送积分',
                            'type_id' => $order_id,
                    );
                    $this->UserPointLog->save($point_log);
                    $this->UserPointLog->point_notify($point_log);
                }
            }
        }
    }
    /**
     * 函数 user_change_payment 修改支付方式.
     *
     * @param $payment_id
     * @param $order_id
     */
    public function user_change_payment($payment_id, $order_id)
    {
        //登录验证
        $this->checkSessionUser();
        if ($this->Order->is_mine($order_id, $_SESSION['User']['User']['id'])) {
            $payment_info = $this->Payment->find("Payment.id='".$payment_id."'");
            $order_info = array(
                'id' => $order_id,
                'payment_id' => $payment_id,
                'payment_name' => $payment_info['PaymentI18n']['name'],
            );

            $pay_log = $this->PaymentApiLog->findbytype_id($order_id);
            $pay_log['PaymentApiLog']['payment_code'] = $payment_info['Payment']['code'];
            $this->PaymentApiLog->save($pay_log);
            $order = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));
            $pay = $payment_info;
            if ($payment_info['Payment']['code'] == 'post') {
                $order_info['payment_status'] = 1;
            } elseif ($payment_info['Payment']['code'] == 'bank') {
                $order_info['payment_status'] = 1;
            } else {
                $order_info['payment_status'] = 0;
            }
            $order_pr = $order['Order'];
            $order_pr['log_id'] = $pay_log['PaymentApiLog']['id'];
            $result['msg'] = $this->ld['pay'];
            $pay_php = $pay['Payment']['php_code'];
            /* -start- */
            if ($pay['Payment']['code'] == 'alipay') {  //支付宝
                eval($pay_php); // - -! 执行数据库取出的php代码
                $pay_class = new alipay();
                $url = $pay_class->get_code($order_pr, $pay, $this);
                $this->set('pay_button', $url);
            }

            if ($pay['Payment']['code'] == 'chinapay') {  //银联电子支付
                eval($pay_php);
                $pay_class = new chinapay();
                $form = $pay_class->get_code($order_pr, $pay);
                $this->set('pay_form', $form);
            }

            if ($pay['Payment']['code'] == 'bank') {  //银行转账
                eval($pay_php);
                $pay_message = $pay['PaymentI18n']['description'];
                $this->set('pay_message', $pay_message);
            }

            if ($pay['Payment']['code'] == 'post') {  //邮局汇款
                eval($pay_php);
                $pay_message = $pay['PaymentI18n']['description'];
                $this->set('pay_message', $pay_message);
            }
            if ($pay['Payment']['code'] == 'paypal') {  //贝宝
                eval($pay_php);
                $pay_message = $pay['PaymentI18n']['description'];
                $pay_class = new paypal();
                $form = $pay_class->get_code($order_pr, $pay, $this);
                $this->set('pay_form', $form);
            }
            if ($pay['Payment']['code'] == 'paypalcn') {  //贝宝
                eval($pay_php);
                $pay_message = $pay['PaymentI18n']['description'];
                $pay_class = new paypal();
                $form = $pay_class->get_code($order_pr, $pay, $this);
                $this->set('pay_form', $form);
            } /* -end- */
            if ($this->Order->save(array('Order' => $order_info))) {
                $message = array(
                    'msg' => $this->ld['modify'].$this->ld['payment'].$this->ld['successfully'],
                    'url' => '',
                );
            } else {
                $message = array(
                    'msg' => $this->ld['modify'].$this->ld['payment'].$this->ld['failed'],
                    'url' => '',
                );
            }
            $this->set('result', $message);
            $this->layout = 'ajax';
        } else {
            //跳转到提示页
            $this->flash('对不起，请选择自己的订单', '/orders', '');
        }
    }

    /**
     * 函数 user_order_pay 用于支付.
     *
     * @param $oid
     * @param $fun
     */
    public function user_order_pay($oid = 0, $fun = '')
    {
        //登录验证
        $this->checkSessionUser();

        if ($this->Order->is_mine($oid, $_SESSION['User']['User']['id'])) {
            if ($oid != 0) {
                $_POST['id'] = $oid;
                $is_ajax = 0;
            } else {
                $is_ajax = 1;
            }
            $url_format = '';
    //		if($this->RequestHandler->isPost()){

            $order = $this->Order->find('first', array('conditions' => array('Order.id' => $_POST['id'])));
            $pay_log = $this->PaymentApiLog->findbytype_id($_POST['id']);
            $pay = $this->Payment->findbycode($pay_log['PaymentApiLog']['payment_code']);
            $order_pr = $order['Order'];
            $order_pr['currency_code'] = $order['Order']['order_currency'];
            $order_pr['log_id'] = $pay_log['PaymentApiLog']['id'];
            $result['msg'] = $this->ld['pay'];
            $pay_php = $pay['Payment']['php_code'];

            //	$order['Order']['need_paid'] = number_format($order['Order']['total']-$order['Order']['money_paid']-$order['Order']['point_fee']-$order['Order']['coupon_fee']-$order['Order']['discount'],2 ,'.','')+0;
            $order_pr['total'] = $pay_log['PaymentApiLog']['amount'];
            $str = '$pay_class = new '.$pay['Payment']['code'].'();';
            if ($pay['Payment']['code'] == 'bank' || $pay['Payment']['code'] == 'post' || $pay['Payment']['code'] == 'COD' || $pay['Payment']['code'] == 'account_pay') {
                $pay_message = $pay['PaymentI18n']['description'];
                $url_format = $pay_message;
                $this->set('pay_message', $pay_message);
            } elseif ($pay['Payment']['code'] == 'alipay') {
                eval($pay_php);
                @eval($str);
                if (isset($pay_class)) {
                    $url = $pay_class->get_code($order_pr, $pay, $this);
                    $url_format = "<input type=\"button\" onclick=\"window.open('".$url."')\" value=\"".$this->ld['alipay_pay_immedia'].'" />';
                    $this->set('url_format', $url_format);
                    $this->set('pay_button', $url);
                }
            } else {
                eval($pay_php);
                @eval($str);
                if (isset($pay_class)) {
                    $url = $pay_class->get_code($order_pr, $pay, $this);
                    $url_format = $url;
                    $this->set('pay_message', $url);
                }
            }
            $result['msg'] = $this->ld['payment'].':'.$pay['PaymentI18n']['name'];

            $result['type'] = 0;

            if ($is_ajax == 1) {
                $this->set('result', $result);
                $this->layout = 'ajax';
            } else {
                if ($fun != '') {
                    return $url_format;
                } else {
                    $flash_url = $this->server_host.$this->user_webroot.'orders';
                    $this->page_init();
                    $this->pageTitle = isset($result['msg']) ? $result['msg'] : '';
                    $this->set('$url_format', '1');
                    $this->flash($this->ld['pay'], $flash_url, '');
                }
            }
        } else {
            //跳转到提示页
            $this->flash('对不起，请选择自己的订单', '/orders', '');
        }
    }

    /**
     * 函数 user_confirm_order 用于确认订单信息.
     *
     * @param $id
     */
    public function user_confirm_order($id = '')
    {
        //登录验证
        $this->checkSessionUser();

        if ($this->Order->is_mine($id, $_SESSION['User']['User']['id'])) {
            if ($id != '') {
                $_POST['id'] = $id;
            }
            $result['type'] = 1;
            $result['msg'] = '';
            //	if($this->RequestHandler->isPost()){
            $flash_url = $this->server_host.$this->user_webroot.'orders';
            $order_info = array(
                'id' => $_POST['id'],
                'shipping_status' => 2,
            );
            $this->Order->save($order_info);
            //if($order_info['Order']['union_user_id'] != '0'){
            $plugin_union = $this->Plugin->find_union();
            if (isset($plugin_union['Plugin'])) {
                $this->requestAction('/user/union_orders/add_union_refferer_orders/'.$_POST['id']);
            }
            //}
            $result['type'] = 0;
            $result['msg'] = $this->ld['thanks_for_purchase'];
            //	}
            if (!isset($_POST['is_ajax'])) {
                $this->page_init();
                $this->pageTitle = ''.$result['msg'].'';
                $this->flash($result['msg'], $flash_url, 10);
            }
            $this->set('result', $result);
            $this->layout = 'ajax';
        } else {
            //跳转到提示页
            $this->flash('对不起，请选择自己的订单', '/orders', '');
        }
    }

    public function orderpay($order_id = 0)
    {
        Configure::write('debug',0);
        $this->layout = 'ajax';
        $action_flag = 0;
        //登录验证
        $this->checkSessionUser();
        $user_id = $_SESSION['User']['User']['id'];
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id, 'Order.user_id' => $user_id,'Order.status'=>array('0','1'))));
        if (!empty($order_info)) {
            $payment_id = $order_info['Order']['payment_id'];
            $payment_info = $this->Payment->find('first', array('conditions' => array('Payment.id' => $payment_id)));
            if (isset($payment_info['Payment'])&&$order_info['Order']['payment_status'] == 0 && $payment_info['Payment']['is_cod'] == 0) {
                $need_pay = number_format($order_info['Order']['total'] - $order_info['Order']['point_fee'] - $order_info['Order']['discount'] - $order_info['Order']['coupon_fee'] - $order_info['Order']['user_balance'], 2, '.', '') + 0;
                $this->set('need_pay', $need_pay);
                //该订单需要付的总价格
                $order_info['Order']['need_paid'] = $need_pay;
                $sub_paylist = $this->Payment->getOrderChildPayments($order_info['Order']['payment_id']);
                $this->set('sub_paylist', $sub_paylist);
                $this->set('order_info', $order_info);
                $this->set('payment_info', $payment_info);
                $action_flag = 1;
            }
        }
        $this->set('action_flag', $action_flag);
    }
    
    
    /*
    		订单确认收货
    */
    function notify_confirm_receipt($order_id=0){
    		$this->loadModel('NotifyTemplateType');
		$this->loadModel('SynchroUser');
		$order_data=$this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id)));
		if(empty($order_data))return false;
		$synchro_user = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.status' => 1, 'SynchroUser.type' => 'wechat','SynchroUser.user_id'=>$order_data['Order']['user_id'])));
		if(empty($synchro_user))return false;
		$touser=$synchro_user['SynchroUser']['account'];
		$notify_template_info=$this->NotifyTemplateType->typeformat("confirm_receipt","wechat");
		$notify_template=isset($notify_template_info['wechat'])?$notify_template_info['wechat']:array();
		$wechat_params=$this->NotifyTemplateType->wechatparamsformat($notify_template);
		$action_content="您的订单已经确认收货";
		$order_code=$order_data['Order']['order_code'];
		$order_subtotal=$order_data['Order']['total'];
		$product_name=isset($order_data['OrderProduct'][0]['product_name'])?$order_data['OrderProduct'][0]['product_name']:'';
		$logistics_company_id=$order_data['Order']['logistics_company_id'];
		$logistics_company_info = $this->LogisticsCompany->find('first', array('conditions' => array('LogisticsCompany.id' => $logistics_company_id)));
		$logistics_company=isset($logistics_company_info['LogisticsCompany'])?$logistics_company_info['LogisticsCompany']['name']:'';
		$invoice_no=$order_data['Order']['invoice_no'];
		$action_desc="感谢您的支持，点击进入评价";
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
    
    
    /*
    		订单取消通知
    */
    function notify_order_cancel($order_id=0){
    		$this->loadModel('NotifyTemplateType');
		$this->loadModel('SynchroUser');
		$order_data=$this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id)));
		if(empty($order_data))return false;
		$synchro_user = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.status' => 1, 'SynchroUser.type' => 'wechat','SynchroUser.user_id'=>$order_data['Order']['user_id'])));
		if(empty($synchro_user))return false;
		$touser=$synchro_user['SynchroUser']['account'];
		$notify_template_info=$this->NotifyTemplateType->typeformat("order_cancel","wechat");
		$notify_template=isset($notify_template_info['wechat'])?$notify_template_info['wechat']:array();
		$wechat_params=$this->NotifyTemplateType->wechatparamsformat($notify_template);
		$action_content="您的订单已取消";
		$order_code=$order_data['Order']['order_code'];
		$order_subtotal=$order_data['Order']['total'];
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
    
    function order_point_pay(){
    		Configure::write('debug',1);
        	$this->layout = 'ajax';
        	
        	$result=array();
        	$result['code']='0';
        	$result['message']=$this->ld['invalid_operation'];
        	
        	$user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
        	$order_id=isset($_POST['order_id'])?$_POST['order_id']:0;
        	$order_info=$this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id,'Order.user_id'=>$user_id)));
        	if(!empty($order_info)){
        		if($order_info['Order']['status']!='2'){
        			$use_point=isset($_POST['use_point'])?$_POST['use_point']:0;
        			$need_pay = number_format($order_info['Order']['total'] - $order_info['Order']['point_fee'] - $order_info['Order']['discount'] - $order_info['Order']['coupon_fee'] - $order_info['Order']['user_balance']-$order_info['Order']['money_paid'], 2, '.', '') + 0;
        			$can_use_point = $need_pay * $this->configs['point-equal'];
        			$order_user=$this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
        			$order_user_point=isset($order_user['User'])?$order_user['User']['point']:0;
        			if($use_point>0&&$use_point<=$order_user_point&&$use_point<=$can_use_point){
        				$point_fee=round($use_point / $this->configs['point-equal'],2);
        				$new_point_fee=$order_info['Order']['point_fee']+$point_fee;
        				$new_need_pay=number_format($order_info['Order']['total'] - $new_point_fee - $order_info['Order']['discount'] - $order_info['Order']['coupon_fee'] - $order_info['Order']['user_balance']-$order_info['Order']['money_paid'], 2, '.', '') + 0;
        				$order_data=array(
        					'id'=>$order_id,
        					'point_fee'=>$new_point_fee,
        					'point_use'=>$order_info['Order']['point_use']+$use_point,
        					'payment_status'=>$new_need_pay==0?'2':$order_info['Order']['payment_status'],
        					'status'=>$new_need_pay==0?($order_info['Order']['status']=='0'?'1':$order_info['Order']['status']):$order_info['Order']['status']
        				);
        				if($order_data['payment_status']=='2'){
        					$order_data['payment_time']=date('Y-m-d H:i:s');
        					if($order_info['Order']['service_type']=='virtual'){
							$order_data['shipping_status']='1';
							$order_data['shipping_time']=date('Y-m-d H:i:s');
							$order_data['shipping_id']=0;
							$order_data['shipping_name']='无需物流';
							$order_info['Order']['shipping_status']='1';
						}
        					$this->OrderAction->save(array('OrderAction' => array(
        						  'id'=>0,
					                'order_id' => $order_id,
					                'from_operator_id' => 0,
					                'user_id' => $user_id,
					                'order_status' => $order_data['status'],
					                'payment_status' => $order_data['payment_status'],
					                'shipping_status' => $order_info['Order']['shipping_status'],
					                'action_note' => $this->ld['point'].' '.$this->ld['pay_now']
			            		)));
        				}
        				$this->Order->save($order_data);
        				
        				$point_log = array(
						'id' => '',
						'user_id' => $user_id,
						'point'=>$order_user_point,
						'point_change' => "-".$use_point,
						'log_type' => 'O',
						'system_note' => '订单消费:'.$order_info['Order']['order_code'],
						'type_id' => $order_id
					);
	                	 	$this->UserPointLog->save($point_log);
	                	 	$this->UserPointLog->point_notify($point_log);
	                	 	
	                	 	if(!empty($order_user)){
	                	 		$user_data=array(
	                	 			'id'=>$user_id,
	                	 			'point'=>$order_user_point-$use_point
	                	 		);
	                	 		$this->User->save($user_data);
	                	 	}
	                	 	$result['code']='1';
        				$result['message']=$this->ld['successful_to_pay'];
        				$result['need_pay']=$new_need_pay;
        			}else{
        				$result['message']=$this->ld['exceed_max_value_can_use'];
        			}
        		}
        	}
        	die(json_encode($result));
    }
    
    function order_balance_pay(){
    		Configure::write('debug',1);
        	$this->layout = 'ajax';
        	
        	$result=array();
        	$result['code']='0';
        	$result['message']=$this->ld['invalid_operation'];
        	
        	$user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
        	$order_id=isset($_POST['order_id'])?$_POST['order_id']:0;
        	$order_info=$this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id,'Order.user_id'=>$user_id)));
        	if(!empty($order_info)){
        		if($order_info['Order']['status']!='2'){
        			$use_balance=isset($_POST['use_balance'])?$_POST['use_balance']:0;
        			$need_pay = number_format($order_info['Order']['total'] - $order_info['Order']['point_fee'] - $order_info['Order']['discount'] - $order_info['Order']['coupon_fee'] - $order_info['Order']['user_balance']-$order_info['Order']['money_paid'], 2, '.', '') + 0;
        			$order_user=$this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
        			$order_user_balance=isset($order_user['User'])?$order_user['User']['balance']:0;
        			if($use_balance>0&&$use_balance<=$need_pay&&$use_balance<=$order_user_balance){
        				$new_user_balance=$order_info['Order']['user_balance']+$use_balance;
        				$new_need_pay=number_format($order_info['Order']['total'] - $new_user_balance - $order_info['Order']['discount'] - $order_info['Order']['coupon_fee'] - $order_info['Order']['point_fee']-$order_info['Order']['money_paid'], 2, '.', '') + 0;
        				$order_data=array(
        					'id'=>$order_id,
        					'user_balance'=>$new_user_balance,
        					'payment_status'=>$new_need_pay==0?'2':$order_info['Order']['payment_status'],
        					'status'=>$new_need_pay==0?($order_info['Order']['status']=='0'?'1':$order_info['Order']['status']):$order_info['Order']['status']
        				);
        				if($order_data['payment_status']=='2'){
        					$order_data['payment_time']=date('Y-m-d H:i:s');
        					if($order_info['Order']['service_type']=='virtual'){
							$order_data['shipping_status']='1';
							$order_data['shipping_time']=date('Y-m-d H:i:s');
							$order_data['shipping_id']=0;
							$order_data['shipping_name']='无需物流';
							$order_info['Order']['shipping_status']='1';
						}
        					$this->OrderAction->save(array('OrderAction' => array(
        						  'id'=>0,
					                'order_id' => $order_id,
					                'from_operator_id' => 0,
					                'user_id' => $user_id,
					                'order_status' => $order_data['status'],
					                'payment_status' => $order_data['payment_status'],
					                'shipping_status' => $order_info['Order']['shipping_status'],
					                'action_note' => $this->ld['use_balance_of_payments']
			            		)));
        				}
        				$this->Order->save($order_data);
        				
        				$this->loadModel('UserBalanceLog');
					$balance_log = array(
						'user_id' => $order_user['User']['id'],
						'amount' => $use_balance,
						'log_type' => 'O',
						'system_note' => '订单消费:'.$order_info['Order']['order_code'],
						'type_id' => $order_id
					);
					$this->UserBalanceLog->save($balance_log);
					
	                	 	if(!empty($order_user)){
	                	 		$user_data=array(
	                	 			'id'=>$user_id,
	                	 			'balance'=>$order_user_balance-$use_balance
	                	 		);
	                	 		$this->User->save($user_data);
	                	 	}
        				$result['code']='1';
        				$result['message']=$this->ld['successful_to_pay'];
        				$result['need_pay']=$new_need_pay;
        			}else{
        				$result['message']=$this->ld['exceed_max_value_can_use'];
        			}
        		}
        	}
        	die(json_encode($result));
    }
}
