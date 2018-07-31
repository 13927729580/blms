<?php
/*****************************************************************************
 * svoms 订单发货商品
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
class OrderShipmentProduct extends AppModel{
	/*
	* @var $useDbConfig 数据库配置
	*/
	public $useDbConfig = 'oms';
	public $name = 'OrderShipmentProduct';
	public $belongsTo = array(
				'OrderProduct' => array(
				        'className' => 'OrderProduct',
				        'conditions' => 'OrderProduct.id=OrderShipmentProduct.order_product_id',
				        'fields' => 'OrderProduct.product_code,OrderProduct.product_number,OrderProduct.product_name,OrderProduct.product_quntity,OrderProduct.product_price,OrderProduct.product_attrbute',
				        'dependent' => true
			        )
                  );
}