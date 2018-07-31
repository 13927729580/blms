<?php

/**
 * 订单模型.
 */
class order extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Order 订单基本信息表
     */
    public $name = 'Order';
    /*
     * @var $hasMany array 订单商品详细信息表
     */
    public $hasMany = array('OrderProduct' => array('className' => 'OrderProduct',
            'conditions' => '',
            'order' => 'OrderProduct.product_id DESC',
            'limit' => '',
            'foreignKey' => 'order_id',
            'dependent' => true,
            'exclusive' => false,
            'finderQuery' => '',
            'joinTable' => 'svcart_order_products',
            'fields' => array(
                        'OrderProduct.id',
                        'OrderProduct.order_id',
                        'OrderProduct.item_type',
                        'OrderProduct.product_id',
                        'OrderProduct.product_name',
                        'OrderProduct.product_code',
                        'OrderProduct.product_quntity',
                        'OrderProduct.product_price',
                        'OrderProduct.product_attrbute',
                        'OrderProduct.product_weight',
                        'OrderProduct.note',
                        'OrderProduct.status',
                        'OrderProduct.adjust_fee',
                        'OrderProduct.delivery_note',
                        'OrderProduct.extension_code',
                        'OrderProduct.send_quntity',
                        'OrderProduct.provider_send_quantity',
                        'OrderProduct.provider_return_quantity',
                        'OrderProduct.provider_send_modified',
                        'OrderProduct.provider_return_modified',
                        ),
        ),
    );

    /**
     * time_orders方法，时间排序.
     *
     * @param $start_time
     * @param $end_time
     *
     * @return $orders
     */
    public function time_orders($start_time, $end_time)
    {
        $start_time = (!isset($start_time)) ? date('Y-m-d H:m:s') : $start_time;
        $middle_time = (strtotime($start_time)) - (30 * 24 * 60 * 60);
        $end_time = date('Y-m-d H:m:s', $middle_time);
        $condition = " Order.created >= '".$end_time."' and Order.created <= '".$start_time."' and Order.user_id=".$_SESSION['User']['User']['id'];
        $orders = $this->findCount($condition);

        return $orders;
    }

    /**
     * new_orders方法，新秩序.
     *
     * @param $start_time
     * @param $end_time
     *
     * @return $orders
     */
    public function new_orders($start_time, $end_time)
    {
        $condition = 'Order.user_id='.$_SESSION['User']['User']['id'];
        $orders = $this->find('all', array('conditions' => $condition, 'order' => 'Order.created DESC', 'recursive' => -1, 'fields' => array('Order.id', 'Order.order_code', 'Order.status', 'Order.payment_status', 'Order.shipping_status'), 'limit' => 4));

        return $orders;
    }

    /**
     * findassoc方法，找到联想.
     *
     * @param $orders_id
     *
     * @return $lists_formated
     */
    public function findassoc($orders_id)
    {
        $condition = array('Order.id' => $orders_id);

        $lists = $this->find('all', array('conditions' => $condition));
        $lists_formated = array();
        if (is_array($lists)) {
            foreach ($lists as $k => $v) {
                $lists_formated[$v['Order']['id']] = $v;
            }
        }

        return $lists_formated;
    }

    public function all_order($conditions)
    {
        $all_order = $this->find('all', array('conditions' => $conditions,
                    'fields' => array('Order.id', 'Order.order_code', 'Order.total', 'Order.is_separate'),
                    'recursive' => -1, ));

        return $all_order;
    }

    public function get_order_infos($conditions)
    {
        $order_infos = $this->find('all', array('conditions' => $conditions));

        return $order_infos;
    }

    public function order_infos($conditions)
    {
        $order_infos = $this->find('all', array('fields' => array('Order.id', 'Order.order_code', 'Order.total'),
                    'conditions' => $conditions, ));

        return $order_infos;
    }

    public function get_order_exist($order, $user_id, $product)
    {
        $conditions = "Order.payment_status='2' and Order.id='".$order."'and Order.user_id='".$user_id."' and OrderProduct.product_id='".$product."'";
        $order_exist = $this->find($conditions);

        return $order_exist;
    }

    public function find_orders($o_ids)
    {
        $orders = $this->find('all', array(
                    'fields' => array('Order.id', 'Order.order_code'),
                    'conditions' => array('Order.id' => $o_ids), ));

        return $orders;
    }

    public function my_list($condition, $rownum, $page)
    {
        $my_orders = $this->find('all', array('conditions' => array($condition),
                    'order' => 'Order.created DESC',
                    'recursive' => -1,
                    'limit' => $rownum,
                    'page' => $page, ));

        return $my_orders;
    }

//    function get_latest_order_products($rownum) {
//        $latest_orders = $this->find('all', array('conditions' => array("Order.shipping_status='1'"), 'order' => 'Order.shipping_time DESC', 'limit' => $rownum));
//        //pr($latest_orders);
//        $latest_order_products = array();
//        if (!empty($latest_orders)) {
//            foreach ($latest_orders as $k => $value) {
//                if (!empty($value['OrderProduct']['0']))
//                    $latest_order_products[] = array('product_id' => $value['OrderProduct']['0']['product_id'], 'product_name' => $value['OrderProduct']['0']['product_name'], 'shipping_address' => $value['Order']['address']);
//            }
//        }
//        return $latest_order_products;
//    }
    public function get_latest_order_products($rownum)
    {
        $latest_orders = $this->find('all', array('order' => 'Order.shipping_time DESC', 'limit' => $rownum));
        //pr($latest_orders);
        $latest_order_products = array();
        if (!empty($latest_orders)) {
            foreach ($latest_orders as $k => $value) {
                $latest_order_products[] = array('product_id' => empty($value['OrderProduct'][0]['product_id']) ? '' : $value['OrderProduct'][0]['product_id'],'product_name' => empty($value['OrderProduct'][0]['product_name']) ? '' : $value['OrderProduct'][0]['product_name'],'product_code' => empty($value['OrderProduct'][0]['product_code']) ? '' : $value['OrderProduct'][0]['product_code'],'address' => $value['Order']['address'],'regions' => $value['Order']['regions'],'region_city' => $value['Order']['city']);
            }
        }
        //pr($latest_order_products);
        return $latest_order_products;
    }

    //判断是不是自己的订单
    public function is_mine($o_ids, $u_ids)
    {
        $flag = false;
        $orders = $this->find('first', array(
                    'fields' => array('Order.user_id'),
                    'conditions' => array('Order.id' => $o_ids), ));
        $flag = $orders['Order']['user_id'] == $u_ids ? true : false;

        return $flag;
    }
    /**
     *获取代码.
     */
    public function get_order_code()
    {
     	 $order_code="";
    	 mt_srand((double) microtime() * 1000000);
        $sn = date('Ymd').str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $a = 0;
        $b = 0;
        $c = 0;
        for ($i = 1;$i <= 12;++$i) {
            if ($i % 2) {
                $b += substr($sn, $i - 1, 1);
            } else {
                $a += substr($sn, $i - 1, 1);
            }
        }
        $c = (10 - ($a * 3 + $b) % 10) % 10;
	$order_code=$sn.$c;
	while(true){
		$order_count=$this->find('count',array('conditions'=>array("Order.order_code"=>$order_code),'recursive' => -1));
		if($order_count>0){
			mt_srand((double) microtime() * 1000000);
		        $sn = date('Ymd').str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
		        $a = 0;
		        $b = 0;
		        $c = 0;
		        for ($i = 1;$i <= 12;++$i) {
		            if ($i % 2) {
		                $b += substr($sn, $i - 1, 1);
		            } else {
		                $a += substr($sn, $i - 1, 1);
		            }
		        }
		        $c = (10 - ($a * 3 + $b) % 10) % 10;
			$order_code=$sn.$c;
		}else{
			break;
		}
	}
        return $order_code;
    }
    
    
    function order_notify($notify_code='',$order_id=0,$controller_obj=null){
    		$order_detail=$this->find('first',array('conditions'=>array('id'=>$order_id)));
    		if(!empty($order_detail)){
    			$User = ClassRegistry::init('User');
    			$SynchroUser = ClassRegistry::init('SynchroUser');
    			$NotifyTemplateType = ClassRegistry::init('NotifyTemplateType');
    			
    			extract($order_detail['Order'],EXTR_PREFIX_ALL,'Order');
    			
    			$notify_template=$NotifyTemplateType->typeformat($notify_code,array('wechat','sms'));
    			if(empty($notify_template))return;
    			
    			$server_host=isset($controller_obj->server_host)?$controller_obj->server_host:'';
			if($server_host==''){
				$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
				$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
				$post=isset($_SERVER['SERVER_PORT'])?$_SERVER['SERVER_PORT']:'80';
				$server_host = $http_type.$host.($post!='80'&&$post!='443'?(":".$post):'');
			}
    			$order_user_id=$order_detail['Order']['user_id'];
    			$order_user_detail=$User->find('first',array('fields'=>'User.id,User.name,User.first_name,User.mobile','conditions'=>array('User.id'=>$order_user_id)));
    			$WechatUser_detail=$SynchroUser->find('first',array('fields'=>'SynchroUser.id,SynchroUser.account','conditions'=>array('SynchroUser.user_id'=>$order_user_id,'SynchroUser.status'=>'1')));
    			if(!empty($order_user_detail)){
    				extract($order_user_detail['User'],EXTR_PREFIX_ALL,'User');
    			}
    			if(!empty($order_user_detail)&&!empty($WechatUser_detail)){
    				$notify_template_detail=isset($notify_template['wechat'])?$notify_template['wechat']:array();
    				if(empty($notify_template_detail))return;
    				$action_content=trim(strip_tags($notify_template_detail['NotifyTemplateTypeI18n']['param01']));
    				@eval("\$action_content = \"$action_content\";");
				$action_desc=trim(strip_tags($notify_template_detail['NotifyTemplateTypeI18n']['param02']));
    				@eval("\$action_desc = \"$action_desc\";");
    				$wechat_params=$NotifyTemplateType->wechatparamsformat($notify_template_detail);
    				$wechat_message=array();
	   			foreach($wechat_params as $k=>$v){
	   				$wechat_message[$k]=array(
	   					'value'=>isset($$v)?$$v:''
	   				);
	   			}
    				$wechat_post=array(
		   			'touser'=>$WechatUser_detail['SynchroUser']['account'],
		   			'template_id'=>$notify_template_detail['NotifyTemplateTypeI18n']['param03'],
		   			'url'=>$server_host.'/orders/view/'.$order_id,
		   			'data'=>$wechat_message
		   		);
				App::import('Component', 'Notify');
				$Notify = new NotifyComponent();
				$Notify->wechat_message($wechat_post);
    			}else if(!empty($order_user_detail)&&trim($order_user_detail['User']['mobile'])!=''){
    				$notify_template_detail=isset($notify_template['sms'])?$notify_template['sms']:array();
    				if(empty($notify_template_detail))return;
    				$sms_content=trim(strip_tags($notify_template_detail['NotifyTemplateTypeI18n']['param02']));
    				@eval("\$sms_content = \"$sms_content\";");
    				$sms_kanal=isset($controller_obj->configs['sms_kanal'])?$controller_obj->configs['sms_kanal']:'0';
    				App::import('Component', 'Notify');
				$Notify = new NotifyComponent();
				$sms_result=$Notify->send_sms($order_user_detail['User']['mobile'],$sms_content,$sms_kanal,isset($controller_obj->configs)?$controller_obj->configs:array());
    			}
    		}
    }
    
    function give_order_point($order_id=0,$controller_obj=null){
    		$order_detail=$this->find('first',array('conditions'=>array('id'=>$order_id)));
    		if(!empty($order_detail)){
    			$shop_configs=isset($controller_obj->configs)?$controller_obj->configs:array();
    			
    			$User = ClassRegistry::init('User');
    			$UserPointLog = ClassRegistry::init('UserPointLog');
    			
    			$order_user_id=$order_detail['Order']['user_id'];
    			$order_user_detail=$User->find('first',array('fields'=>'User.id,User.name,User.first_name,User.mobile,User.user_point','conditions'=>array('User.id'=>$order_user_id)));
    			
    			if(isset($shop_configs['order_point_give_method'])&&$shop_configs['order_point_give_method']=='1'){
    				$OrderProduct = ClassRegistry::init('OrderProduct');
    				$product_ids = $OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $order_id)));//标注
    				$product_point = array();
    				if (isset($product_ids) && sizeof($product_ids) > 0) {
    					$user_point=$order_user_detail['User']['user_point'];
            				foreach ($product_ids as $k => $v) {
						$point_log = array(
							'id' => 0,
							'user_id' => $order_user_id,
							'point'=>$user_point,
							'point_change' => $v['Product']['point'] * $v['OrderProduct']['product_quntity'],
							'log_type' => 'B',
							'system_note' => '商品 '.$v['ProductI18n']['name'].' 送积分',
							'type_id' => $order_id
						);
                            		$UserPointLog->save($point_log);
                            		$UserPointLog->point_notify($point_log);
                            		$user_point+=$v['Product']['point'] * $v['OrderProduct']['product_quntity'];
	    				}
	    				$User->save(array('id'=>$order_user_id,'user_point'=>$user_point));
    				}
    			}else if(isset($shop_configs['order_point_give_method'])&&$shop_configs['order_point_give_method']=='2'){
    				$user_point=$order_user_detail['User']['user_point'];
    				$give_point_proportion=isset($shop_configs['order_total_give_point_proportion'])?$shop_configs['order_total_give_point_proportion']:0;
    				$give_point=$order_detail['Order']['money_paid']*$give_point_proportion;
    				if($give_point>0){
	    				$point_log = array(
						'id' => 0,
						'user_id' => $order_user_id,
						'point'=>$user_point,
						'point_change' => $give_point,
						'log_type' => 'B',
						'system_note' => '订单 '.$order_detail['Order']['order_code'].' 送积分',
						'type_id' => $order_id
					);
	                		$UserPointLog->save($point_log);
	                		$UserPointLog->point_notify($point_log);
	                		$user_point+=$give_point;
	                		$User->save(array('id'=>$order_user_id,'user_point'=>$user_point));
    				}
    			}
    		}
    }
}
