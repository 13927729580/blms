<?php

/**
 * 订单商品模型.
 */
class OrderProduct extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';

    /*
     * @var $name OrderProduct 订单商品
     */
    public $name = 'OrderProduct';

    /*
     * @var $hasOne array 关联商品和订单
     */
    public $hasOne = array('Product' => array('className' => 'Product',
                              'conditions' => 'Product.code = OrderProduct.product_code',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => '',
                        ),
                        'Order' => array('className' => 'Order',
                              'conditions' => 'Order.id = OrderProduct.order_id',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => '',
                        ),
                  );
    public function get_frozen_product_list()
    {
        $products = $this->find('all', array('conditions' => array('Order.payment_status' => 2, 'Order.status' => 1, 'Order.shipping_status' => 0), 'group' => 'product_code', 'fields' => array('OrderProduct.product_code', 'SUM(OrderProduct.product_quntity) AS num')));
        $return_list = array();
        foreach ($products as $k => $v) {
            $return_list[$v['OrderProduct']['product_code']] = $v[0]['num'];
        }

        return $return_list;
    }

    public function get_frozen_product_num($code)
    {
        $products = $this->find('first', array('conditions' => array('Order.status' => 1, 'Order.payment_status' => 2, 'Order.shipping_status' => 0, 'OrderProduct.product_code' => $code), 'group' => 'product_code', 'fields' => array('SUM(OrderProduct.product_quntity) AS num')));
        $frozen_num = isset($products['0']['num']) ? $products['0']['num'] : 0;

        return $frozen_num;
    }
    public function get_frozen()
    {
        $products = $this->query("SELECT `OrderProduct`.`product_code`, SUM(`OrderProduct`.`product_quntity`) AS num 
FROM `svcart_order_products` AS `OrderProduct` 
LEFT JOIN `svcart_products` AS `Product` ON (`Product`.`id` = `OrderProduct`.`product_id`) 
LEFT JOIN `svcart_orders` AS `Order` ON (`Order`.`id` = `OrderProduct`.`order_id`) WHERE `Order`.`status` = '1' AND `Order`.`payment_status` = '2' AND `Order`.`shipping_status` = '0' GROUP BY product_code ");//获取冻结数
         $return_list = array();
        foreach ($products as $k => $v) {
            $return_list[$v['OrderProduct']['product_code']] = $v[0]['num'];
        }

        return $return_list;
    }
    
    function order_product_notify($notify_code='',$backend_locale='chi',$order_product_id=0,$controller_obj=null){
    		$order_detail=$this->find('first',array('conditions'=>array('OrderProduct.id'=>$order_product_id)));
    		if(!empty($order_detail)){
    			$User = ClassRegistry::init('User');
    			$SynchroUser = ClassRegistry::init('SynchroUser');
    			$NotifyTemplateType = ClassRegistry::init('NotifyTemplateType');
    			$Resource = ClassRegistry::init('Resource');
    			
    			$resource_code="order_product_status";
                	$Resource_info = $Resource->getformatcode($resource_code,$backend_locale);
                	$resource_info=isset($Resource_info[$resource_code])?$Resource_info[$resource_code]:array();
                	
    			$modify_time=date('Y-m-d H:i:s');
    			extract($order_detail['Order'],EXTR_PREFIX_ALL,'Order');
    			extract($order_detail['OrderProduct'],EXTR_PREFIX_ALL,'OrderProduct');
    			
                	$delivery_status=isset($resource_info[$OrderProduct_delivery_status])?$resource_info[$OrderProduct_delivery_status]:'处理中';
    			
    			$NotifyTemplateType->set_locale($backend_locale);
    			$notify_template=$NotifyTemplateType->typeformat($notify_code,array('wechat','sms'));
    			if(empty($notify_template))return;
    			
    			$server_host=isset($controller_obj->server_host)?$controller_obj->server_host:'';
			if($server_host==''){
				$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
				$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
				$post=isset($_SERVER['SERVER_PORT'])?$_SERVER['SERVER_PORT']:'80';
				$server_host = $http_type.$host.($post!='80'&&$post!='443'?(":".$post):'');
			}
			$order_id=$order_detail['Order']['id'];
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
}
