<?php

/**
 * 商品模型.
 */

class Quote extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */

    public $useDbConfig = 'oms';
    public $name = 'Quote';
    
    public $hasMany = array(
                        'QuoteProduct' => array(
                        'className' => 'QuoteProduct',
                        'conditions' =>"",
                        'order' => 'QuoteProduct.id',
                        'dependent' => true,
                        'foreignKey' => 'quote_id',
                    ),
        );
}
