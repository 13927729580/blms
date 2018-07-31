<?php

/**
 * 促销关联商品.
 */
class PromotionProduct extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name 用来解决PHP4中的一些奇怪的类名
     */
    public $name = 'PromotionProduct';

    public function get_promotion_products($promotion_id_conditions)
    {
        $promotion_products = $this->find('all', array('conditions' => $promotion_id_conditions,
                    'fields' => array('PromotionProduct.product_id',
                        'PromotionProduct.price',
                        'PromotionProduct.id', ), ));

        return $promotion_products;
    }
}
