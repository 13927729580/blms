<?php

/**
 * 商品租赁设置模型.
 */
class ProductLease extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name WeiboRb 
     */
    public $name = 'ProductLease';
    
    public function find_svcart_lease_deposit($p_codes){
    		$lease_data=array();
    		$lease_info=$this->find('all',array("fields"=>"ProductLease.product_code,ProductLease.lease_price,ProductLease.lease_deposit,ProductLease.unit","conditions"=>array("ProductLease.product_code"=>$p_codes,"ProductLease.status"=>'1')));
    		foreach($lease_info as $v){
    			$lease_data[$v['ProductLease']['product_code']]=$v['ProductLease'];
    		}
    		return $lease_data;
    }
}
