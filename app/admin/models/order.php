<?php

/*****************************************************************************
 * svoms  订单模型
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
class order extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Order  订单菜单
     */
    public $name = 'Order';
    /*
     * @var $hasMany array 关联订单商品
     */
    public $hasMany = array('OrderProduct' => array('className' => 'OrderProduct',
                              'conditions' => '',
                              'order' => 'OrderProduct.product_id,OrderProduct.id,OrderProduct.parent_product_id',
                              'dependent' => true,
                              'foreignKey' => 'order_id',
                        ),'OrderProductValue' => array('className' => 'OrderProductValue',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'order_id',
                        ),
                  );
    /*
     * @var $belongsTo array 关联订单用户
     */
    public $belongsTo = array('User' => array('className' => 'User',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'user_id',
                        ),
                  );
    /**
     * update_order方法，更新订单状态.
     *
     * @param array $arr 需要更新的数组
     */
    public function update_order($arr)
    {
        $this->save(array('Order' => $arr));
    }
    /**
     * update_order方法，更新订单状态.
     *
     * @param array $arr 需要更新的数组
     */
    public function getOrders($codes)
    {
        $orders = $this->find('all', array('conditions' => array('Order.order_code' => $codes), 'fields' => 'Order.order_code,Order.status,Order.payment_status,Order.shipping_status,Order.money_paid'));
        $code_orders = array();
        if (!empty($orders)) {
            foreach ($orders as $v) {
                $code_orders[$v['Order']['order_code']] = $v;
            }
        }

        return $code_orders;
    }

     /**
      * calculate_total方法，更新订单金额.
      *
      * @param int $id 订单id
      */
     public function calculate_total($id)
     {
         $order = $this->find('first', array('conditions' => array('Order.id' => $id)));
         if (empty($order)) {
             return false;
         } else {
             $money_paid = 0;
             $subtotal = 0;
             $refund_status = 0;
             foreach ($order['OrderProduct'] as $k => $v) {
                 $subtotal += ($v['product_quntity'] - $v['refund_quantity']) * $v['product_price'] + $v['adjust_fee'];
                 if ($v['product_quntity'] == $v['refund_quantity']) {
                     ++$refund_status;
                 }
             }
             $total = $subtotal + $order['Order']['shipping_fee'] + $order['Order']['payment_fee'] + $order['Order']['insure_fee'] + $order['Order']['card_fee'] + $order['Order']['pack_fee'] + $order['Order']['tax'];
             if ($order['Order']['payment_status'] == 2) {
                 $save_data['money_paid'] = $total - $order['Order']['point_fee'] - $order['Order']['discount'];
             }

             $save_data['id'] = $id;
             $save_data['subtotal'] = $subtotal;
             $save_data['total'] = $total;
             if ($refund_status == count($order['OrderProduct'])) {
                 $save_data['shipping_status'] = 5;//退货
                  $save_data['status'] = 4;//退款
         // 	 	 $save_data['payment_status']=4;//
             }

             $this->save(array('Order' => $save_data));

             return $save_data;
         }
     }
     
     
    function give_order_point($order_id=0,$controller_obj=null){
    		$order_detail=$this->find('first',array('conditions'=>array('Order.id'=>$order_id)));
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
            					$Product = ClassRegistry::init('Product');
            					$Product->set_locale($controller_obj->backend_locale);
            					$product_data=$Product->find('first',array('conditions'=>array('Product.id'=>$v['OrderProduct']['product_id'])));
            					if(!empty($product_data)){
							$point_log = array(
								'id' => 0,
								'user_id' => $order_user_id,
								'point'=>$user_point,
								'point_change' => $product_data['Product']['point'] * $v['OrderProduct']['product_quntity'],
								'log_type' => 'B',
								'system_note' => '商品 '.$product_data['ProductI18n']['name'].' 送积分',
								'type_id' => $order_id
							);
	                            		$UserPointLog->save($point_log);
	                            		$UserPointLog->point_notify($point_log);
	                            		$user_point+=$v['Product']['point'] * $v['OrderProduct']['product_quntity'];
                            		}
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
