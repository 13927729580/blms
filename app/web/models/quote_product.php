<?php

/**
 * 商品模型.
 */

class QuoteProduct extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'QuoteProduct';
    
    public $hasOne = array(
	        'Quote' => array('className' => 'Quote',
	                   'conditions' => 'Quote.id = QuoteProduct.quote_id',
	                   'order' => '',
	                   'dependent' => true,
	                   'foreignKey' => ''
	        )
    );
}
