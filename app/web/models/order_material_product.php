<?php

/**
 * 订单材料商品模型.
 */
class OrderMaterialProduct extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'OrderMaterialProduct';
    /*是否存在订单材料商品数据*/
    public function check_order_pro_material($order_id = 0, $order_product_id = 0, $product_code = '', $material_product_code = '')
    {
        $order_pro_material = $this->find('first', array('conditions' => array('OrderMaterialProduct.order_id' => $order_id, 'OrderMaterialProduct.order_product_id' => $order_product_id, 'OrderMaterialProduct.product_code' => $product_code, 'OrderMaterialProduct.material_product_code' => $material_product_code)));
        if (empty($order_pro_material)) {
            return true;
        } else {
            return false;
        }

        return true;
    }
}
