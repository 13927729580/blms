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
     * @var $name OrderProduct 订单商品信息表
     */
    public $name = 'OrderProduct';
    /*
     * @var $hasOne array 关联选择商品信息表和商品语言分类表
     */
    public $hasOne = array(
        'Product' => array(
            'className' => 'Product',
            'conditions' => "Product.id=OrderProduct.product_id and OrderProduct.item_type=''",
            'order' => '',
            'dependent' => true,
            'foreignKey' => '',
        ),
        'ProductI18n' => array(
            'className' => 'ProductI18n',
            'conditions' => "ProductI18n.product_id=OrderProduct.product_id and OrderProduct.item_type=''",
            'order' => '',
            'dependent' => true,
            'foreignKey' => '',
        ),
        'Order' => array('className' => 'Order',
                   'conditions' => "Order.id = OrderProduct.order_id",
                   'order' => '',
                   'dependent' => true,
                   'foreignKey' => '',
                        ),
    );

    public function get_orders_products($condition, $orderby, $rownum, $page)
    {
        $my_orders_products = $this->find('all', array('conditions' => array($condition),
                    'fields' => array('OrderProduct.id', 'OrderProduct.order_id', 'OrderProduct.product_id', 'Product.img_thumb', 'Product.img_detail', 'Product.market_price', 'Product.shop_price', 'Product.code', 'Product.id', 'Product.brand_id', 'OrderProduct.created', 'OrderProduct.product_price', 'ProductI18n.name','OrderProduct.lease_type','OrderProduct.lease_unit','OrderProduct.expire_date'
                    ),
                    'order' => array("Product.$orderby"), 'limit' => $rownum, 'page' => $page, ));

        return $my_orders_products;
    }
    public function get_my_orders_products($condition)
    {
        $get_my_orders_products = $this->find('all', array('conditions' => array($condition),
                    'fields' => array('OrderProduct.id', 'OrderProduct.order_id', 'OrderProduct.product_id', 'OrderProduct.product_quntity', 'OrderProduct.refund_quantity', 'Product.img_thumb', 'Product.img_detail', 'Product.market_price', 'Product.shop_price', 'Product.code', 'Product.id', 'Product.brand_id', 'OrderProduct.created', 'OrderProduct.product_price', 'ProductI18n.name','OrderProduct.lease_type','OrderProduct.lease_unit','OrderProduct.expire_date'
                    ), 'order' => 'OrderProduct.id desc'));

        return $get_my_orders_products;
    }
    public function get_order_product($order_product_conditions)
    {
        $order_product = $this->find('all', array('recursive' => -1,
                    'conditions' => $order_product_conditions, ));

        return $order_product;
    }
    public function get_orders_product_ids()
    {
        $order_product = $this->find('all', array('fields' => 'OrderProduct.product_id'));
        $pids = array();
        if (!empty($order_product)) {
            foreach ($order_product as $v) {
                $pids[] = $v['OrderProduct']['product_id'];
            }
        }

        return array_unique($pids);
    }
    public function get_frozen_product_list()
    {
        $products = $this->find('all', array('conditions' => array('Order.status' => 1, 'Order.payment_status' => 2, 'Order.shipping_status' => 0, 'ProductI18n.locale' => 'chi'), 'group' => 'product_code', 'fields' => array('OrderProduct.product_code', 'SUM(OrderProduct.product_quntity) AS num')));
        $return_list = array();
        foreach ($products as $k => $v) {
            $return_list[$v['OrderProduct']['product_code']] = $v[0]['num'];
        }

        return $return_list;
    }
}
