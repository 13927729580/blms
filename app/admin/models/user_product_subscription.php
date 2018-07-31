<?php

/**
 * 	用户商品订阅模型
 */
class UserProductSubscription extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'UserProductSubscription';
    
    public $belongsTo = array(
        'User' => array(
	        'className' => 'User',
	        'conditions' => '',
	        'order' => '',
	        'dependent' => true
        )
    );
    
}
