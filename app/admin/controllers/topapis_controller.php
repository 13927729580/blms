<?php

//App::import('Controller', 'Soap');
App::import('Vendor', 'Topapi', array('file' => 'Topapi.class.php'));
class TaobaoshopsController extends AppController
{
    public $name = 'Taobaoshops';
    public $uses = array('TaobaoItem','TaobaoItemimg','TaobaoShop','Product','ProductI18n','CategoryProduct','CategoryProductI18n','TaobaoSellercat','TaobaoSellercatsRelationCategorie','ProductsCategorie','ProductGallery','Config','OperatorLog');

    /**
     * A soap call 'Topapi' is handled here.
     *
     * @param string $Token 验证码 
     *
     * @return result
     */
    public function Topapi($Token)
    {
        $result = new result();
        $result->code = '0';
        $result->message = '未知错误';

        //判断Token 是否有效
        $key_arr = array(md5(Configure::read('nodekey')));
        if (!in_array($Token, $key_arr)) {
            $result->code = '0'; // 1：成功 0:失败
            $result->message = $Token.'验证码错误'.serialize($key_arr);// 信息原因
            return $result;
        }

        //分类导入
        $this->sellercats_get();    //1.抓取淘宝的自定义到我们自己的淘宝自定义
//		$this->update_sellers();    //2.更新官网淘宝自定义表的父子关系	
        $this->category_get();      //3.淘宝自定义复制到分类表
        $this->update_category();   //4.更新官网分类表的父子关系
//		$this->delete_category();   //5.淘宝删除的分类，删除对应的官网分类

        //商品导入
        $result->message = $this->taobao_item_onsale_get();  //获得在售所有商品
        $this->top_api_update_item();      //更新商品描述，seller_cid,taobao_product_id	
        $this->product_get();             //2.抓取淘宝的商品到我们自己的淘宝商品 
//		$this->top_api_update_item_modified();

        $result->code = '1';
        $result->message = '导入成功';

        return $result;
        die();
    }

///////////////////////////////////////////////////////////////////////////////////////
//获取自定义类目 
    private function sellercats_get()
    {
        $this_time = time();
        $Taoapi = new Topapi();//实例化top类

        //把所有分类都置为删除状态
        $this->TaobaoSellercat->updateAll(array('TaobaoSellercat.status' => 3), array('TaobaoSellercat.status !=' => 2));

        $taobao_shop_data = $this->TaobaoShop->find('all', array('conditions' => array('status' => 1)));//获取所有店铺信息
        foreach ($taobao_shop_data as $taobao_shop_k => $taobao_shop_info) {
            //	echo $taobao_shop_info["TaobaoShop"]["nick"];

            $SellercatsSellercatGetParam['api_key'] = TaobaoAppKey;
            $SellercatsSellercatGetParam['nick'] = $taobao_shop_info['TaobaoShop']['nick'];
            $SellercatsSellercatGetParam['app_secret'] = TaobaoAppSecret;
            $SellercatsSellercatGetParam['session'] = $taobao_shop_info['TaobaoShop']['top_session'];//设置SESSION

            $result = $this->taobao_sellercats_get($SellercatsSellercatGetParam);

            if (sizeof($result['seller_cats']['seller_cat']) > 0) {
                if (empty($result['seller_cats']['seller_cat'][0])) {
                    $tmp_array = $result['seller_cats']['seller_cat'];
                    $result['seller_cats']['seller_cat'] = array();
                    $result['seller_cats']['seller_cat'][0] = $tmp_array;
                }
                foreach ($result['seller_cats']['seller_cat'] as $k => $v) {
                    $seller_cat = $this->TaobaoSellercat->find('first', array('conditions' => array('TaobaoSellercat.taobao_cid' => $v['cid'])));
                    //	pr($seller_cat);  
                if (isset($seller_cat['TaobaoSellercat']['id']) && $seller_cat['TaobaoSellercat']['id'] > 0) {  //存在分类
                    $seller_cat_data = array('TaobaoSellercat.status' => 1);
                    $category = $this->TaobaoSellercatsRelationCategorie->find('first', array('conditions' => array('TaobaoSellercatsRelationCategorie.seller_cid' => $v['cid'])));          //   pr($category);
                        if ($seller_cat['TaobaoSellercat']['name'] != $v['name']) {
                            $seller_cat_data['name'] = "'".$v['name']."'";
                        //	echo "cid:".$v['cid']."名称"."[".$seller_cat['TaobaoSellercat']['name']."]"."->[".$v['name']."]"."\r\n";
                            $this->CategoryProductI18n->updateAll(array('CategoryProductI18n.name' => "'".$v['name']."'"), array('CategoryProductI18n.category_id' => $category['TaobaoSellercatsRelationCategorie']['category_id']));
                        }
                    if ($seller_cat['TaobaoSellercat']['sort_order'] != $v['sort_order'] || $category['CategoryProduct']['orderby'] = $v['sort_order']) {
                        //修改名称和排序
                        //	echo "cid:".$v['cid']."名称:".$v['name']."[".$seller_cat['TaobaoSellercat']['sort_order']."]"."->[".$v['sort_order']."]"."\r\n";
                            $seller_cat_data['sort_order'] = $v['sort_order'];
                    //	$new_order = $taobao_shop_info['TaobaoShop']['orderby']*100+$v['sort_order'];
                        $new_order = $v['sort_order'];
                        $this->CategoryProduct->updateAll(array('orderby' => $new_order), array('CategoryProduct.id' => $category['TaobaoSellercatsRelationCategorie']['category_id']));
                    }

                        //改成有效
                        $this->TaobaoSellercat->updateAll($seller_cat_data, array('TaobaoSellercat.taobao_cid' => $v['cid']));
                } else {
                    $v['id'] = '';
                    $v['nick'] = $SellercatsSellercatGetParam['nick'];
                    $v['taobao_cid'] = $v['cid'];
                    $v['taobao_parent_cid'] = $v['parent_cid'];
                    $v['cid'] = 0;
                    if (!is_string($v['pic_url'])) {
                        if (sizeof($v['pic_url']) > 0) {
                            foreach ($v['pic_url'] as $k => $v) {                                    //数组都是空，暂不取
                            }
                        } else {
                            $v['pic_url'] = '';
                        }
                    }
                        //pr($v);
                        $this->TaobaoSellercat->save($v);
                }
                }
            }
        }
    }
    //导淘宝自定义类目
private function taobao_sellercats_get($SellercatsSellercatGetParam)
{
    $Taoapi = new Topapi();//实例化top类
        $ShopParam['api_key'] = TaobaoAppKey;
    $ShopParam['app_secret'] = TaobaoAppSecret;
    $ShopParam['method'] = 'taobao.sellercats.list.get';
    $ShopParam['nick'] = $SellercatsSellercatGetParam['nick'];
    $ShopParam['fields'] = 'type,cid,parent_cid,name,pic_url,sort_order,created,modified';
    $Taoapi->setUserParam($ShopParam);//传入参数
        $result = $Taoapi->call();
//		$this->TaobaoUpdateLog->save(
//			array(
//					"nick"=>$SellercatsSellercatGetParam["nick"],
//					"method"=>$ShopParam['method'],
//					"post_data"=>$ShopParam,
//					"return_data"=>$result,
//					"remark"=>""
//				)
//			);

        return $result;
}
///////////////////////////////////////////////////////////////////////////////////////		

//更新自定义类目父子关系
private function update_sellers()
{
    $seller_list = $this->TaobaoSellercat->find('all', array('conditions' => array('TaobaoSellercat.taobao_parent_cid <>' => '0', 'TaobaoSellercat.is_new' => '2'), 'fields' => 'TaobaoSellercat.id,TaobaoSellercat.taobao_parent_cid'));

    foreach ($seller_list as $k => $v) {
        $seller_detile = $this->TaobaoSellercat->findbytaobao_cid($v['TaobaoSellercat']['taobao_parent_cid']);
        $this->TaobaoSellercat->updateAll(array('TaobaoSellercat.parent_id' => $seller_detile['TaobaoSellercat']['id']), array('TaobaoSellercat.id' => $v['TaobaoSellercat']['id']));
    }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'更新自定义类目父子关系 ', $this->admin['id']);
            }
}

///////////////////////////////////////////////////////////////////////////////////////		
    //淘宝自定义类目导入官网分类表
private function category_get()
{
    $result = $this->TaobaoSellercat->find('all', array('conditions' => array('TaobaoSellercat.is_new' => 2)));
    $msg = '';
    foreach ($result as $k => $v) {
        //pr($result);
            $new_cate = array();
        $new_cate['CategoryProduct']['id'] = '';
        $new_cate['CategoryProduct']['type'] = 'P';
        $new_cate['CategoryProduct']['status'] = $v['TaobaoSellercat']['status'];
        $new_cate['CategoryProduct']['parent_id'] = 0;
        $new_cate['CategoryProduct']['img01'] = $v['TaobaoSellercat']['pic_url'];
        $new_cate['CategoryProduct']['orderby'] = (isset($v['TaobaoSellercat']['sort_order']) && $v['TaobaoSellercat']['sort_order'] > 0) ? $v['TaobaoSellercat']['sort_order'] : 0;

        $this->CategoryProduct->actsAs = array();
        $this->CategoryProduct->save($new_cate['CategoryProduct']);

        $msg .= '导入分类：'.$v['TaobaoSellercat']['name'];
        if ($this->CategoryProduct->id) {
            $new_cate['CategoryProductI18n']['id'] = '';
            $new_cate['CategoryProductI18n']['name'] = $v['TaobaoSellercat']['name'];
            $new_cate['CategoryProductI18n']['category_id'] = $this->CategoryProduct->id;
            $new_cate['CategoryProductI18n']['locale'] = 'chi';
            $this->CategoryProductI18n->save($new_cate['CategoryProductI18n']);

            $new_cate['TaobaoSellercatsRelationCategorie']['id'] = '';
            $new_cate['TaobaoSellercatsRelationCategorie']['seller_cid'] = $v['TaobaoSellercat']['taobao_cid'];
            $new_cate['TaobaoSellercatsRelationCategorie']['category_id'] = $this->CategoryProduct->id;
            $this->TaobaoSellercatsRelationCategorie->save($new_cate['TaobaoSellercatsRelationCategorie']);
            $this->TaobaoSellercat->updateAll(array('TaobaoSellercat.is_new' => 1), array('TaobaoSellercat.id' => $v['TaobaoSellercat']['id']));
        }
    }

    //    	echo $msg;
//			$this->TaobaoUpdateLog->save(
//				array(
//					"nick"=>"-",
//					"method"=>"category_get",
//					"status"=>1,
//					"post_data"=>"",
//					"return_data"=>"",
//					"remark"=>$msg
//				),
//				1
//			);
}

    ////////////////////////////////////////////////////////////////////////////////////////////

    //更新官网的父子关系
private function update_category()
{
    $selercat_all = $this->TaobaoSellercat->find('all', array('conditions' => array('TaobaoSellercat.taobao_parent_cid <>' => '0', 'TaobaoSellercat.is_new' => 1), 'fields' => 'TaobaoSellercat.id,TaobaoSellercat.taobao_cid,TaobaoSellercat.taobao_parent_cid'));
    //	 pr($selercat_all);
         foreach ($selercat_all as $k => $v) {
             $relationcate = $this->TaobaoSellercatsRelationCategorie->find('first', array('conditions' => array('TaobaoSellercatsRelationCategorie.seller_cid' => $v['TaobaoSellercat']['taobao_cid']), 'fields' => 'TaobaoSellercatsRelationCategorie.category_id'));
             $cate_id = $this->TaobaoSellercatsRelationCategorie->find('first', array('conditions' => array('TaobaoSellercatsRelationCategorie.seller_cid' => $v['TaobaoSellercat']['taobao_parent_cid']), 'fields' => 'TaobaoSellercatsRelationCategorie.category_id'));
                //更新描述字段
                    $this->CategoryProduct->updateAll(
                    array('CategoryProduct.parent_id' => $cate_id['TaobaoSellercatsRelationCategorie']['category_id']),
                    array('CategoryProduct.id' => $relationcate['TaobaoSellercatsRelationCategorie']['category_id'])
                    );
             $this->TaobaoSellercat->updateAll(array('TaobaoSellercat.is_new' => 0), array('TaobaoSellercat.id' => $v['TaobaoSellercat']['id']));
                    //操作员日志
                    if ($this->configs['operactions-log'] == 1) {
                        $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'更新官网父子关系 ', $this->admin['id']);
                    }
         }
}

    /////////////////////////////////////////////////////////////////////////////
//淘宝删除的分类，删除对应的官网分类	
private function delete_category()
{
    $selercat_todel = $this->TaobaoSellercat->find('all', array('conditions' => array('TaobaoSellercat.status ' => '3'), 'fields' => 'TaobaoSellercat.id,TaobaoSellercat.taobao_cid,TaobaoSellercat.taobao_parent_cid'));
    //	 pr($selercat_todel);
         foreach ($selercat_todel as $k => $v) {
             $relationcate = $this->TaobaoSellercatsRelationCategorie->find('first', array('conditions' => array('TaobaoSellercatsRelationCategorie.seller_cid' => $v['TaobaoSellercat']['taobao_cid']), 'fields' => 'TaobaoSellercatsRelationCategorie.category_id'));
             $cate_link_count = $this->TaobaoSellercatsRelationCategorie->find('count', array('conditions' => array('TaobaoSellercatsRelationCategorie.seller_cid !=' => $v['TaobaoSellercat']['taobao_cid'], 'TaobaoSellercatsRelationCategorie.category_id' => $relationcate['TaobaoSellercatsRelationCategorie']['category_id'])));
                //更新描述字段
        //		echo "淘宝分类:".$v['TaobaoSellercat']['taobao_cid']."被删除".$cate_link_count."\r\n";
                if ($cate_link_count == 0) {
                    $this->CategoryProduct->updateAll(
                        array('CategoryProduct.status' => 3),
                        array('CategoryProduct.id' => $relationcate['TaobaoSellercatsRelationCategorie']['category_id'], 'CategoryProduct.status ' => 1)
                        );
               //		 echo "官网分类:".$relationcate['TaobaoSellercatsRelationCategorie']['category_id']."被删除"."\r\n";
                }

             $this->TaobaoSellercat->updateAll(array('TaobaoSellercat.status' => 2), array('TaobaoSellercat.taobao_cid' => $v['TaobaoSellercat']['taobao_cid']));
         }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除分类 ', $this->admin['id']);
            }
}

    ///////////////////////////////////////////////////////////////////////////////////////
        //获得在售商品
private function taobao_item_onsale_get()
{
    $taobao_shops = $this->TaobaoShop->find('all', array('conditions' => array('status' => 1)));//获取所有店铺信息
        foreach ($taobao_shops as $tsk => $taobao_shop_info) {
            $ItemsOnSaleGetParam = array();
            //pr($taobao_shop_info);
            //修改开始时间
            $ItemsOnSaleGetParam['start_modified'] = (isset($taobao_shop_info['TaobaoShop']['update_item_time']) && $taobao_shop_info['TaobaoShop']['update_item_time'] != '' && $taobao_shop_info['TaobaoShop']['update_item_time'] != '0000-00-00 00:00:00') ? $taobao_shop_info['TaobaoShop']['update_item_time'] : '2008-01-01 00:00:00';//淘宝更新时用。的修改时间

            $max_time = 3600 * 24 * 30;
            //修改结束时间
            $end_modified_limit = strtotime($ItemsOnSaleGetParam['start_modified']) + $max_time;
            //echo $end_modified_limit;
            $now = time();
            $end_modified_time = ($now > $end_modified_limit) ? $end_modified_limit : $now;
            $ItemsOnSaleGetParam['end_modified'] = date('Y-m-d H:i:s', $end_modified_time);//淘宝更新时用。的修改时间
            //pr($ItemsOnSaleGetParam);
            //获取接口数据
            $this->taobao_item_onsale_apigetall($taobao_shop_info['TaobaoShop'], $ItemsOnSaleGetParam);

            //pr($ItemsOnSaleIncrementGetParam);break;

            //更新这次结束时间
            $taobao_shop_save['TaobaoShop']['id'] = $taobao_shop_info['TaobaoShop']['id'];
            $taobao_shop_save['TaobaoShop']['update_item_time'] = $ItemsOnSaleGetParam['end_modified'];
        //	$this->TaobaoShop->save($taobao_shop_save);
        }
}

    private function taobao_item_onsale_apigetall($TaobaoShop, $ItemsOnSaleParam)
    {
        $page = 1;
        $page_size = 200;

        $msg = '';
        $flag = 0;
        while (true) {
            $result = $this->taobao_item_onsale_apiget($TaobaoShop, $ItemsOnSaleParam, $page, $page_size);

            if (!empty($result['code'])) {
                break;
            }
            if (!isset($result['total_results']) || !($result['total_results'] > 0)) {
                break;
            }

            //pr($result);
            //echo $result['total_results'];
            $code_array = array('0' => '未知错误','1' => '新增','2' => '更新');

            if ($result['total_results'] == 1) {
                $item = $result['items']['item'];
                $code = $this->onsale($result['items']['item']);
                $msg .= '修改商品:'.$item['num_iid'].':'.$item['title'].$code_array[$code]."\r\n";
            } elseif ($result['total_results'] > 1) {
                foreach ($result['items']['item'] as $kk => $vv) {
                    //	pr($vv);
                    $item = $vv;
                    $code = $this->onsale($item);
                    $msg .= '修改商品:'.$item['num_iid'].':'.$item['title'].'-'.$code_array[$code]."\r\n";
                }
            }
            $flag = 1;

            //echo "total_results:".$result['total_results'].";"."\r\n";
            $total_page = ceil($result['total_results'] / $page_size);
            //$total_page=1;
            //echo "total_page:".$total_page.";"."\r\n";
            //echo "page:".$page.";"."\r\n";

//			if($page>=$total_page)			
//				break;
//			else
//				$page++;
            break;
        }
    //	echo "\r\n".$TaobaoShop["nick"].":";
        $msg = $ItemsOnSaleParam['start_modified'].' --> '.$ItemsOnSaleParam['end_modified']."\r\n".$msg;
    //	echo $msg;
//		$this->TaobaoUpdateLog->save(
//				array(
//					"nick"=>$TaobaoShop["nick"],
//					"method"=>"taobao_item_onsale_apigetall",
//					"status"=>1,
//					"post_data"=>"",
//					"return_data"=>"",
//					"remark"=>$msg
//				),
//				$flag
//			);
    }

    //分页获取在售的淘宝商品
    private function taobao_item_onsale_apiget($TaobaoShop, $ItemsOnSaleParam, $page, $page_size)
    {
        $Param['api_key'] = TaobaoAppKey;
        $Param['app_secret'] = TaobaoAppSecret;
        $Param['session'] = $TaobaoShop['top_session'];
        $Param['method'] = 'taobao.items.onsale.get';
        $Param['page_no'] = $page;
        $Param['page_size'] = $page_size;
//			$Param['start_modified'] =$ItemsOnSaleParam['start_modified'];
//			$Param['end_modified'] =$ItemsOnSaleParam['end_modified'];
            $Param['fields'] = 'approve_status,num_iid,title,nick,type,cid,pic_url,num,props,valid_thru,list_time,price,has_discount,has_invoice,has_warranty,has_showcase, modified,delist_time,postage_id,seller_cids,outer_id,location';

        $Taoapi = new Topapi();//实例化top类
            $Taoapi->setUserParam($Param);//传入参数
            $result = $Taoapi->call();
//			$this->TaobaoUpdateLog->save(
//				array(
//					"nick"=>$TaobaoShop["nick"],
//					"method"=>$Param['method'],
//					"post_data"=>$Param,
//					"return_data"=>$result,
//					"remark"=>""
//				)
//			);

            return $result;
    }

    private function onsale($item)
    {
        //	pr($item); 
        $seller_counts = $this->TaobaoItem->find('count', array('conditions' => array('TaobaoItem.num_iid' => $item['num_iid'])));
        $code = 0;
        if ($seller_counts == 0) { //新增
            if (isset($item['location']) && is_array($item['location']) && sizeof($item['location']) > 0) {
                $item['location_city'] = $item['location']['city'];
                $item['location_state'] = $item['location']['state'];
            }
            $item['pic_path'] = isset($item['pic_url']) ? $item['pic_url'] : '';
            $item['sale'] = 0;
            $item['is_update_product'] = 0;
            $item['id'] = '';
            $item['is_new'] = 1;
            $item['taobao_modified'] = $item['modified'];
            unset($item['modified']);
            if ($this->TaobaoItem->save($item)) {
                $code = 1;
            }
        } else {
            //更新描述字段
            if ($this->TaobaoItem->updateAll(
                array(
                    'TaobaoItem.sale' => '0',
                    'TaobaoItem.is_new' => '1',
                    'TaobaoItem.taobao_modified' => "'".$item['modified']."'",
                    'TaobaoItem.is_update_product' => 0, ),
                array('TaobaoItem.num_iid' => $item['num_iid'])
                )) {
                $code = 2;
            }
        }

        return $code;
    }
///////////////////////////////////////////////////////////////////////////////////////
        //更新TaobaoItem表的描述,seller_cids字段
private function top_api_update_item()
{
    $items = $this->TaobaoItem->find('all', array('conditions' => array('TaobaoItem.is_new' => '1')));
//		pr($items);
        if (is_array($items) && sizeof($items) > 0) {
            $msg = '';
            $flag = 1;
            foreach ($items as $k => $v) {
                $taobao_shop_info = $this->TaobaoShop->findbynick($v['TaobaoItem']['nick']);//获取所有店铺信息 

                    $ItemsGetParam = array();
                $ItemsGetParam['num_iid'] = $v['TaobaoItem']['num_iid'];
                    //获取淘宝商品详细信息
                    $msg .= 'num_iid:'.$v['TaobaoItem']['num_iid']."获取\r\n";
                $item = $this->taobao_item_get($taobao_shop_info['TaobaoShop'], $ItemsGetParam);
                $item['item']['location_state'] = isset($item['item']['location']['state']) ? $item['item']['location']['state'] : '';
                $item['item']['location_city'] = isset($item['item']['location']['city']) ? $item['item']['location']['city'] : '';
                $item['item']['taobao_product_id'] = isset($item['item']['product_id']) ? $item['item']['product_id'] : 0;
                $item['item']['id'] = $v['TaobaoItem']['id'];
                $item['item']['taobao_modified'] = $v['TaobaoItem']['modified'];
                $item['item']['is_new'] = 0;
                unset($item['item']['product_id']);
                $this->TaobaoItem->save($item['item']);

                   //	item_img相册
                       if (isset($item['item']['item_imgs']) && sizeof($item['item']['item_imgs']['item_img'] > 0)) {
                           $this->TaobaoItemimg->deleteAll(array('num_iid' => $item['item']['num_iid']));
                           if (empty($item['item']['item_imgs']['item_img'][0])) {
                               $item_img = $item['item']['item_imgs']['item_img'];
                               $item_img['iid'] = $item_img['id'];
                               $item_img['num_iid'] = $item['item']['num_iid'];
                               $this->TaobaoItemimg->saveAll($item_img);
                           } else {
                               foreach ($item['item']['item_imgs']['item_img'] as $k => $v) {
                                   $v['iid'] = $v['id'];
                                   $v['num_iid'] = $item['item']['num_iid'];
                                   $this->TaobaoItemimg->saveAll($v);
                               }
                           }
                       }
            }
           //    echo $msg;
//		       	$this->TaobaoUpdateLog->save(
//				array(
//					"nick"=>"-",
//					"method"=>"top_api_update_item",
//					"status"=>1,
//					"post_data"=>"",
//					"return_data"=>"",
//					"remark"=>$msg
//				),
//				$flag
//			);
        }
}

        //获取单个商品
private function taobao_item_get($TaobaoShop, $ItemsGetParam)
{
    $Taoapi = new Topapi();//实例化top类

        $Param['api_key'] = TaobaoAppKey;
    $Param['app_secret'] = TaobaoAppSecret;
    $Param['nicks'] = $TaobaoShop['nick'];
    $Param['session'] = $TaobaoShop['top_session'];//设置SESSION
        $Param['method'] = 'taobao.item.get';
    $Param['num_iid'] = $ItemsGetParam['num_iid'];
    $Param['fields'] = 'detail_url,num_iid,title,nick,type,skus,props_name,promoted_service,cid,seller_cids,props,input_pids,input_str,desc,pic_url,num,valid_thru,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,has_invoice,has_warranty,has_showcase,modified,increment,auto_repost,approve_status,postage_id,product_id,auction_point,property_alias,item_img.url,item_img.id,item_img.position,item_img.num_iid,item_img.iid,prop_imgs,outer_id,is_virtual,is_taobao,is_ex,is_timing,videos,is_3D,score,volume,one_station,second_kill,auto_fill,violation,appkey,callbackUrl,created,is_prepay,ww_status,after_sale_id,cod_postage_id,sell_promise';
    $Taoapi->setUserParam($Param);//传入参数
        $result = $Taoapi->call();

//		$this->TaobaoUpdateLog->save(
//				array(
//					"nick"=>$TaobaoShop["nick"],
//					"method"=>$Param['method'],
//					"post_data"=>$Param,
//					"return_data"=>$result,
//					"remark"=>""
//				)
//			);
//		pr($result);
        return $result;
}

/////////////////////////////////////////////////////////////////////////////////
    //TaobaoItem表复制到Product表
private function product_get()
{
    $configs_all = $this->Config->getformatcode_all();
    $this->locale = 'chi';
    $this->configs = $configs_all[$this->locale];

    $conditions['and'] = array('TaobaoItem.is_new' => 0);
//		$conditions['or'][] = array("TaobaoItem.outer_id"=>"");
//		$conditions['or'][] = array("TaobaoItem.outer_id"=>null);

        $items = $this->TaobaoItem->find('all', array('conditions' => $conditions));

    foreach ($items as $k => $v) {
        //unset($v["TaobaoItem"]["desc"]);
                //	echo $v["TaobaoItem"]["num_iid"].":".$v["TaobaoItem"]["sale"];
                    $v['Product']['id'] = '';
        $v['Product']['category_id'] = 0;
        $v['Product']['bestbefore'] = $v['TaobaoItem']['sale'];
        $v['Product']['img_thumb'] = $v['TaobaoItem']['pic_path'];
        $v['Product']['img_detail'] = $v['TaobaoItem']['pic_path'];
        $v['Product']['img_original'] = $v['TaobaoItem']['pic_path'];
        $v['Product']['market_price'] = $v['TaobaoItem']['price'];
        $v['Product']['shop_price'] = $v['TaobaoItem']['price'];
        $v['Product']['quantity'] = $v['TaobaoItem']['num'];
        $v['Product']['code'] = empty($v['TaobaoItem']['outer_id']) ? '' : $v['TaobaoItem']['outer_id'];
                    //获取分类
                    $cate_id = array();
        if ($v['TaobaoItem']['seller_cids'] != '') {  //获取官网分类
                        $sellers = explode(',', $v['TaobaoItem']['seller_cids']);
                        //pr($sellers);
                        foreach ($sellers as $kk => $vv) {
                            if (!empty($vv)) {
                                $sellers_cate = $this->TaobaoSellercatsRelationCategorie->findbyseller_cid($vv);
                                $cate_id[] = $sellers_cate['TaobaoSellercatsRelationCategorie']['category_id'];
                            }
                        }
        }

        $v['Product']['category_id'] = (isset($cate_id[0]) && $cate_id[0] > 0) ? $cate_id[0] : 0;

        $this->Product->save($v['Product']);
        if ($this->Product->id > 0) {
            foreach ($cate_id as $ck => $cv) {
                if ($ck > 0 && $cv > 0) {
                    $v['ProductsCategorie'][$ck]['id'] = '';
                    $v['ProductsCategorie'][$ck]['product_id'] = $this->Product->id;
                    $v['ProductsCategorie'][$ck]['category_id'] = $cv;
                    $this->ProductsCategorie->save($v['ProductsCategorie'][$ck]);
                }
            }

                        //修改商品的分类编号
                        if (empty($v['Product']['code'])) {
                            $this->Product->updateAll(array('Product.code' => "'".$this->configs['products_code_prefix'].$this->Product->id."'"), array('Product.id' => $this->Product->id));
                        //修改TaobaoItem的分类编号
                           $this->TaobaoItem->updateAll(array('TaobaoItem.outer_id' => "'".$this->configs['products_code_prefix'].$this->Product->id."'", 'is_update_product' => 1), array('TaobaoItem.id' => $v['TaobaoItem']['id']));
                        }
                       //	echo $this->configs['products_code_prefix'].$this->Product->id."\r\n";

                        $v['ProductI18n']['id'] = '';
            $v['ProductI18n']['name'] = $v['TaobaoItem']['title'];
            $v['ProductI18n']['locale'] = 'chi';
            $v['ProductI18n']['product_id'] = $this->Product->id;
            $v['ProductI18n']['market_price'] = $v['TaobaoItem']['price'];
            $v['ProductI18n']['shop_price'] = $v['TaobaoItem']['price'];

                        //修改处理方法，分解描述内容，保存到分类（如果是空的话），
                        $v['TaobaoItem']['desc'] = str_replace("\r", '', $v['TaobaoItem']['desc']);
            $v['TaobaoItem']['desc'] = str_replace("\n", '', $v['TaobaoItem']['desc']);
            preg_match("/<table bordercolor=\"#c1c2c3\"(.+?)>(.+?)<\/table>/ui", $v['TaobaoItem']['desc'], $p_desc);
                        //pr($p_desc); //0 是商品描述
                        if (isset($p_desc['0'])  && $p_desc['0'] != '') {

                            //$product=$this->Product->findbycode($item['TaobaoItem']['outer_id']);

                            //$product['ProductI18n']['description']=$p_desc['0'];
                            //$this->ProductI18n->save($product['ProductI18n']);
                            $v['ProductI18n']['description'] = $p_desc['0'];
                            //pr($product['Product']['category_id']);
                            $category = $this->CategoryProduct->findbyid($v['Product']['category_id']);
                            //pr($category);
                            if ($category['CategoryProductI18n']['tb_desup'] == '' || $category['CategoryProductI18n']['tb_desdown'] == '') {
                                preg_match("/(.+)<table bordercolor=\"#c1c2c3\"(.+?)>(.+?)<\/table>(.+)/ui", $v['TaobaoItem']['desc'], $c_desc);
                                //pr($c_desc); //1 是淘宝分类顶部描述 4 是淘宝分类底部描述
                                if ($category['CategoryProductI18n']['tb_desup'] == '' && isset($c_desc['1'])) {
                                    $category['CategoryProductI18n']['tb_desup'] = $c_desc['1'];
                                }

                                if ($category['CategoryProductI18n']['tb_desdown'] == ''  && isset($c_desc['4'])) {
                                    $category['CategoryProductI18n']['tb_desdown'] = $c_desc['4'];
                                }
                                $this->CategoryProductI18n->save($category['CategoryProductI18n']);
                                $msg .= '分类描述'.$v['Product']['category_id'].'更新'.'|';
                            }
                        } else {
                            $v['ProductI18n']['description'] = $v['TaobaoItem']['desc'];
                        }
            $this->ProductI18n->save($v['ProductI18n']);

                        //初始相册
                        $this->update_product_img($v, $this->Product->id);
        }
    }
}

    private function update_product_img($item, $product_id = 1)
    {
        $msg = '';
        $taobao_img_list = $this->TaobaoItemimg->find('all', array('conditions' => array('num_iid' => $item['TaobaoItem']['num_iid']), 'fields' => array('url', 'position')));
        //	$taobao_img_list = $this->TaobaoItemimg->find('all',array('conditions'=>array('num_iid'=>'12228812539'),'fields'=>array('url','position')));
            ;
        if (!empty($taobao_img_list)) {
            $this->ProductGallery->deleteAll(array('product_id' => $product_id));
            if (empty($taobao_img_list[0])) {
                $product_gallery = array(
                                    'product_id' => $product_id,
                                    'img_thumb' => $taobao_img_list['TaobaoItemimg']['url'],
                                    'img_detail' => $taobao_img_list['TaobaoItemimg']['url'],
                                    'img_original' => $taobao_img_list['TaobaoItemimg']['url'],
                                    'orderby' => $taobao_img_list['TaobaoItemimg']['position'],
                                    );
                $this->ProductGallery->saveAll($product_gallery);
                $msg .= $item['TaobaoItem']['num_iid'].'  ';
            } else {
                foreach ($taobao_img_list as $k => $v) {
                    $product_gallery = array(
                                    'product_id' => $product_id,
                                    'img_thumb' => $v['TaobaoItemimg']['url'],
                                    'img_detail' => $v['TaobaoItemimg']['url'],
                                    'img_original' => $v['TaobaoItemimg']['url'],
                                    'orderby' => $v['TaobaoItemimg']['position'],
                                    );
                    $this->ProductGallery->saveAll($product_gallery);
                }
                $msg .= $item['TaobaoItem']['num_iid'].'  ';
            }

//					if($msg!=""){
//						$this->TaobaoUpdateLog->save(
//								array(
//									"nick"=>'-',
//									"method"=>"top_api_update_item_modified",
//									"status"=>1,
//									"post_data"=>"",
//									"return_data"=>"",
//									"remark"=>$msg
//								),
//								1
//							);
//					}
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////
private function top_api_update_item_modified()
{
    $items = $this->TaobaoItem->find('all', array('conditions' => array('TaobaoItem.is_update_product' => '0', 'TaobaoItem.is_new' => 0, 'TaobaoItem.outer_id <>' => '')));
    $msg = '';
    if (sizeof($items) > 0) {
        foreach ($items as $k => $v) {
            $msg .= $v['TaobaoItem']['nick'].'-'.$v['TaobaoItem']['num_iid'].':'.$v['TaobaoItem']['title'].'=';
            if ($v['TaobaoItem']['outer_id'] == '' || $v['TaobaoItem']['outer_id'] == null) {
                //新增商品
                    $is_update_product = 0; //临时
            } else {
                $product = $this->Product->findbycode($v['TaobaoItem']['outer_id']);

                    //获取分类
                    $cate_id = array();
                if ($v['TaobaoItem']['seller_cids'] != '') {  //获取官网分类
                        $sellers = explode(',', $v['TaobaoItem']['seller_cids']);
                    foreach ($sellers as $kk => $vv) {
                        if (!empty($vv)) {
                            $sellers_cate = $this->TaobaoSellercatsRelationCategorie->findbyseller_cid($vv);
                            $cate_id[] = $sellers_cate['TaobaoSellercatsRelationCategorie']['category_id'];
                        }
                    }
                }

                $product['Product']['category_id'] = (isset($cate_id[0]) && $cate_id[0] > 0) ? $cate_id[0] : 0;

                $this->Product->save($product['Product']);
                $this->ProductsCategorie->deleteall(array('product_id' => $this->Product->id));
                foreach ($cate_id as $ck => $cv) {
                    if ($ck > 0) {
                        $pc['ProductsCategorie'][$ck]['id'] = '';
                        $pc['ProductsCategorie'][$ck]['product_id'] = $this->Product->id;
                        $pc['ProductsCategorie'][$ck]['category_id'] = $cv;
                        $this->ProductsCategorie->save($pc['ProductsCategorie'][$ck]);
                    }
                }

                    //pr($product);
                    $info = array(); //需要更新的信息
                    if ($v['TaobaoItem']['sale'] != $product['Product']['bestbefore']) { //设为过往精品
                        $info['Product']['bestbefore'] = $v['TaobaoItem']['sale'];
                        $msg .= '过往:'.$info['Product']['bestbefore'].'|';
                    }

                    //内容分解后判断
                    $v['TaobaoItem']['desc'] = str_replace("\r", '', $v['TaobaoItem']['desc']);
                $v['TaobaoItem']['desc'] = str_replace("\n", '', $v['TaobaoItem']['desc']);
                preg_match("/<table bordercolor=\"#c1c2c3\"(.+?)>(.+?)<\/table>/ui", $v['TaobaoItem']['desc'], $p_desc);
                if (isset($p_desc['0'])  && $p_desc['0'] != '') {
                    $product_desc = $p_desc['0'];

                    $category = $this->CategoryProduct->findbyid($product['Product']['category_id']);
                            //pr($category);
                            if (isset($category['CategoryProductI18n'])) {
                                if ($category['CategoryProductI18n']['tb_desup'] == '' || $category['CategoryProductI18n']['tb_desdown'] == '') {
                                    preg_match("/(.+)<table bordercolor=\"#c1c2c3\"(.+?)>(.+?)<\/table>(.+)/ui", $v['TaobaoItem']['desc'], $c_desc);
                                    if ($category['CategoryProductI18n']['tb_desup'] == '' && isset($c_desc['1'])) {
                                        $category['CategoryProductI18n']['tb_desup'] = $c_desc['1'];
                                    }

                                    if ($category['CategoryProductI18n']['tb_desdown'] == '' && isset($c_desc['4'])) {
                                        $category['CategoryProductI18n']['tb_desdown'] = $c_desc['4'];
                                    }
                                    $this->CategoryProductI18n->save($category['CategoryProductI18n']);
                                    $msg .= '分类描述'.$product['Product']['category_id'].'更新'.'|';
                                }
                            } else {
                                $msg .= '分类描述'.$product['Product']['category_id'].'未找到'.'|';
                            }
                } else {
                    $product_desc = $v['TaobaoItem']['desc'];
                }

                if ($product_desc != $product['ProductI18n']['description']) { //更新描述，处理相册
                        $info['ProductI18n']['description'] = "'".$product_desc."'";
                        //重组相册
                        $this->update_product_img($v, $product['Product']['id']);
                    $msg .= '描述不同'.'|';
                }
                if ($v['TaobaoItem']['num'] != $product['Product']['quantity']) { //更新描述，处理相册
                        $info['Product']['quantity'] = $v['TaobaoItem']['num'];
                    $msg .= '库存不同:'.$info['Product']['quantity'].'|';
                }
                if ($v['TaobaoItem']['title'] != $product['ProductI18n']['name']) { //更新描述，处理相册
                        $info['ProductI18n']['name'] = "'".$v['TaobaoItem']['title']."'";
                    $msg .= '名称不同:'.$info['ProductI18n']['name'].'|';
                }

                if (isset($info['Product']) && sizeof($info['Product']) > 0) {
                    $msg .= '更新'.'|';
                    $this->Product->updateAll(
                            $info['Product'],
                            array('Product.code' => $v['TaobaoItem']['outer_id'])
                        );
                }
                if (isset($info['ProductI18n']) && sizeof($info['ProductI18n']) > 0) {
                    $msg .= '更新I18n'.'|';
                    $this->ProductI18n->updateAll(
                            $info['ProductI18n'],
                            array('ProductI18n.product_id' => $product['Product']['id'])
                        );
                }
                $is_update_product = 1;
                $msg .= "\r\n";
            }

                //更新标志位
            //	echo "标志位为:".$is_update_product."\r\n";
                $this->TaobaoItem->updateAll(
                    array('TaobaoItem.is_update_product' => $is_update_product),
                    array('TaobaoItem.id' => $v['TaobaoItem']['id'])
                );
        }
    }

//		echo $msg;
        if ($msg != '') {
            //			$this->TaobaoUpdateLog->save(
//					array(
//						"nick"=>"-",
//						"method"=>"top_api_update_item_modified",
//						"status"=>1,
//						"post_data"=>"",
//						"return_data"=>"",
//						"remark"=>$msg
//					),
//					1
//				);
        }
}
    //////////////////////////////////////////////////////////////////////////

        //检查淘宝上被删除的商品，变成过往
private function item_delete_check()
{
    $now_date = date('Y-m-d H:i:s');
    $items = $this->TaobaoItem->find('all', array('conditions' => array('TaobaoItem.sale' => '0', 'TaobaoItem.delist_time <' => $now_date)));
        //pr($items);
        if (is_array($items) && sizeof($items) > 0) {
            $msg = '';
            $flag = 1;
            foreach ($items as $k => $v) {
                $taobao_shop_info = $this->TaobaoShop->findbynick($v['TaobaoItem']['nick']);//获取所有店铺信息 

                    $ItemsGetParam = array();
                $ItemsGetParam['num_iid'] = $v['TaobaoItem']['num_iid'];
                    //获取淘宝商品详细信息
                    $msg .= 'num_iid:'.$v['TaobaoItem']['num_iid'].$v['TaobaoItem']['title'].' 获取';
                $item = $this->taobao_item_get($taobao_shop_info['TaobaoShop'], $ItemsGetParam);
                if (isset($item['code']) && isset($item['sub_code']) && ($item['sub_code'] == 'isv.item-is-delete:invalid-numIid' || $item['sub_code'] == 'isv.item-get-service-error:ITEM_NOT_FOUND')) {
                    //pr($item);
                        $v['TaobaoItem']['sale'] = 1;
                    $v['TaobaoItem']['is_update_product'] = 0;

                    $this->TaobaoItem->updateAll(
                    array('TaobaoItem.is_update_product' => 0, 'TaobaoItem.sale' => 1),
                    array('TaobaoItem.id' => $v['TaobaoItem']['id'])
                );
                    $msg .= "失败，过往处理\r\n";
                } elseif (isset($item['code'])) {
                    $msg .= '错误'.$item['sub_code'].':'.$item['sub_msg']."\r\n";
                } else {
                    $msg .= '正常'.$item['item']['approve_status'].$item['item']['delist_time'].'='.$v['TaobaoItem']['delist_time'].'=='.$item['item']['modified'].'='.$v['TaobaoItem']['taobao_modified']."\r\n";
                    $this->TaobaoItem->updateAll(
                    array('TaobaoItem.delist_time' => "'".$item['item']['delist_time']."'"),
                    array('TaobaoItem.id' => $v['TaobaoItem']['id'])
                    );
                        //pr($item);
                }

                       //unset($item["item"]["desc"]);unset($item["item"]["wap_desc"]);
                    //pr($item["item"]);
            }
        //       echo $msg;
//		       	$this->TaobaoUpdateLog->save(
//				array(
//					"nick"=>"-",
//					"method"=>"item_delete_check",
//					"status"=>1,
//					"post_data"=>"",
//					"return_data"=>"",
//					"remark"=>$msg
//				),
//				$flag
//			);
        }
}
}
////////////////////////////////////////////////////////////////////


class result
{
    /** @var string */
    public $code;

    /** @var string */
    public $message;
}
