<?php

/*****************************************************************************
 * svoms  �ϴ��ļ�ģ��
 * ===========================================================================
 * ��Ȩ����  �Ϻ�ʵ������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�ã�
 * ������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ===========================================================================
 * $����: �Ϻ�ʵ��$
 * $Id$
*****************************************************************************/
class ProductLocalePrice extends AppModel
{
    /*
     * @var $useDbConfig ���ݿ�����
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Product ��Ʒ
     */
    public $name = 'ProductLocalePrice';
    
	/*
		��ȡ�����۸��б�
	*/
	function get_pro_city_price_list($params=array()){
		$pro_city_price_list=array();
		$pro_id_list=array();
		
		if(!empty($params['product_id'])){
			$cond['ProductLocalePrice.product_id']=$params['product_id'];
		}else{
			$cond="";
		}
		if(!empty($params['fields'])){
			$fields=$params['fields'];
		}else{
			$fields=array("ProductLocalePrice.*");
		}
		$pro_city_price=$this->find("all",array("conditions"=>$cond,"fields"=>$fields));
		
		if(!empty($pro_city_price)){
			foreach($pro_city_price as $k=>$v){
				$pro_city_price_list[$v['ProductLocalePrice']['product_id']][$v['ProductLocalePrice']['locale']]=$v;
			}
		}
		return $pro_city_price_list;
	}
}
