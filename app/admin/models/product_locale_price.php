<?php

/*****************************************************************************
 * svoms  上传文件模型
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
class ProductLocalePrice extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Product 商品
     */
    public $name = 'ProductLocalePrice';
    
	/*
		获取地区价格列表
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
