<?php

/**
 * Seevia 专题管理.
 *
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
/**
 *这是一个名为 ShopsController 的商店控制器.
 */
class ShopsController extends AppController
{
    /*
    *@var $name
    *@var $components
    *@var $helpers
    *@var $uses
    *@var $cacheQueries
    *@var $cacheAction
    */
    public $name = 'Shops';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Html', 'Form', 'Javascript');
    public $uses = array('Store');
    public $cacheQueries = true;
    public $cacheAction = '1 hour';
    /**
     *显示.
     */
    public function index()
    {
        //	$now = date('Y-m-d H:i:s');
        $this->ur_heres[] = array('name' => $this->ld['shop_list'] , 'url' => '/shops');
        $this->set('ur_heres', $this->ur_heres);
        $this->page_init();
        $this->pageTitle = $this->ld['shop_list'].' - '.$this->configs['shop_title'];
        $this->layout = 'default_full';

//		$stores = $this->Store->get_all_stores(LOCALE);
        $stores = $this->Store->get_all_stores($this->locale);
        //pr($stores);
        $this->set('stores', $stores);
    }
    /**
     *显示.
     *
     *@param $id
     */
    public function view($id)
    {
        $tmp = isset($this->ld['ur_store']) ? $this->ld['ur_store'] : $this->ld['shop_list'];
        $this->ur_heres[] = array('name' => $tmp , 'url' => '/shops');//ur_store
        $this->set('ur_heres', $this->ur_heres);
        $this->page_init();
        $this->pageTitle = $tmp.' - '.$this->configs['shop_title'];
        $this->layout = 'default_full';
        //$now = date('Y-m-d H:i:s');
//		$store_info = $this->Store->find("first",array("conditions"=>array("Store.id"=>$id,'Store.status' => 1,'Store.store_type' => 1)));
        $store_info = $this->Store->find('first', array('conditions' => array('Store.id' => $id, 'Store.store_type' => 1)));
//		$stores = $this->Store->get_all_stores(LOCALE);
        $stores = $this->Store->get_all_stores($this->locale);
        $this->set('stores', $stores);
        if (!empty($store_info)) {
            $this->set('store_info', $store_info);
            $store_info['StoreI18n']['meta_description'] = !empty($store_info['StoreI18n']['meta_description']) ? $store_info['StoreI18n']['meta_description'] : '';
            $store_info['StoreI18n']['meta_keywords'] = !empty($store_info['StoreI18n']['meta_keywords']) ? $store_info['StoreI18n']['meta_keywords'] : '';
            $this->set('meta_description', $store_info['StoreI18n']['meta_description']);
            $this->set('meta_keywords', $store_info['StoreI18n']['meta_keywords']);
        //leo20090722导航显示
        $this->ur_heres[] = array('name' => $store_info['StoreI18n']['name'],'url' => '');
            $this->set('ur_heres', $this->ur_heres);
        } else {
            $this->redirect('/');
        }
    }
}
