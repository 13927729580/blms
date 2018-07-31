<?php

/*****************************************************************************
 * svoms  OrderProductValue 订单商品属性值表 模型
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
class OrderProductValue extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Product 商品
     */
    public $name = 'OrderProductValue';

    public function get_order_product_value($order_id)
    {
        $order_product_value_list = array();
        $data_list = $this->find('all', array('conditions' => array('OrderProductValue.order_id' => $order_id), 'order' => 'OrderProductValue.id'));
        foreach ($data_list as $v) {
            $order_product_value_list[$v['OrderProductValue']['order_product_id']][] = $v;
        }

        return $order_product_value_list;
    }
}
