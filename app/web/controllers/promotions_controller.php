<?php

/*****************************************************************************
 * Seevia 促销控制
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 PromotionsController 的促销商品控制器.
 */
class PromotionsController extends AppController
{
    /*
    *@var $name
    *@var $components
    *@var $helpers
    *@var $uses
    *@var $cacheQueries
    *@var $cacheAction
    */
    public $name = 'Promotions';
    public $components = array('Pagination','Cookie');
    public $helpers = array('html','Pagination','Flash');
    public $uses = array('Product','Flash','Promotion','PromotionI18n','PromotionProduct','Brand','PromotionActivityProduct');
    public $cacheQueries = true;
    public $cacheAction = '1 hour';
    /**
     *显示促销.
     *
     *@param $page
     *@param $orderby
     *@param $rownum
     */
    public function index($page = 0, $orderby = 'orderby', $rownum = 0)
    {
        $this->layout = 'default_full';
        $this->pageTitle = '促销活动 - '.$this->configs['shop_title'];
        //$this->ur_heres[] = array('name'=>$this->ld['topic'],'url'=>"/topics/");
        $this->ur_heres[] = array('name' => '促销活动','url' => '/promotions/');
        //return;
          if (isset($this->configs['promotions_page_list_number'])) {
              $rownum = $this->configs['promotions_page_list_number'];
          } else {
              $rownum = 10;
          }
        if (isset($this->configs['promotions_list_orderby'])) {
            $orderby = $this->configs['promotions_list_orderby'];
        } else {
            $orderby = 'created';
        }
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $_GET['page'] = $page;
        }
        $this->data['get_page'] = $page;
        $this->data['orderby'] = $orderby;
        $this->data['rownum'] = $rownum;

        $this->page_init();
        $promotions = $this->Promotion->find('all', array());
        $total = count($promotions);
       // date_default_timezone_set('PRC'); 
           $now = date('Y-m-d H:i:s');
        $yestoday = date('Y-m-d H:i:s', strtotime('-1 day'));
        $filter = '1=1';
        $filter .= " and  Promotion.status = '1' and Promotion.created <= '".$now."' and  Promotion.created >='".$yestoday."'";

        $filter_conditions = array($filter);
        $one_day_promotions = $this->Promotion->get_one_day_promotions($filter_conditions);
        $this->set('one_day_time', count($one_day_promotions));
        $condition = '1=1';
        $sortClass = 'Promotion';
        $parameters = array($orderby,$rownum,$page);
        $options = array();
        $options = array('page' => $page,'show' => $rownum,'modelClass' => 'Promotion');
       //	$promotions = $this->Promotion->findAll($condition,''," Promotion.$orderby asc ","$rownum",$page);
           //$page = $this->Pagination->init($condition,$parameters,$options,$total,$rownum,$sortClass);
           $pages = $this->Pagination->init($condition, $parameters, $options);
        $promotion_conditions = array($condition);
        $promotions = $this->Promotion->get_promotions($orderby, $promotion_conditions, $rownum, $pages);

        $ur_heres[] = array('name' => $this->ld['home'],'url' => '/');
        $ur_heres[] = array('name' => $this->ld['promotion'].$this->ld['home'],'url' => '');
        $this->data['pages_url_1'] = $this->server_host.$this->webroot.'promotions/index/';
        $this->data['pages_url_2'] = '/'.$this->data['orderby'].'/'.$this->data['rownum'];
        $this->set('promotions', $promotions);
        //pr($promotions);
        //pr("asfd-=-=-=-=-=");
        $this->set('ur_heres', $ur_heres);
        $this->set('orderby', $orderby);
        $this->set('rownum', $rownum);
        $this->set('total', $total);
        //page_number_expand_max
        $js_languages = array('page_number_expand_max' => $this->ld['page_number'].$this->ld['not_exist']);
        $this->set('js_languages', $js_languages);

        //$this->set();
        $this->pageTitle = $this->ld['promotion'].$this->ld['home'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_title'];
    }
    /**
     *显示.
     *
     *@param $id
     */
    public function view($id)
    {
        $this->layout = 'default_full';
        $this->page_init();
        $promotion = $this->Promotion->find('first', array('conditions' => array('Promotion.id' => $id)));

        $product_id_arr = $this->PromotionActivityProduct->find('list', array('conditions' => array('promotion_id' => $id), 'fields' => 'product_id'));
        $this->data['products'] = $this->Product->find('all', array('conditions' => array('Product.id' => $product_id_arr)));

        //pr($this->data['products']);
        $flag = 1;
        if (!is_numeric($id) || $id < 1) {
            $this->pageTitle = $this->ld['invalid_id'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['invalid_id'], '/', 5);
            $flag = 0;
        }
        if (empty($promotion)) {
            $this->pageTitle = $this->ld['promotion'].$this->ld['home'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['promotion'].$this->ld['not_exist'], '/', 5);
            $flag = 0;
        } elseif ($promotion['Promotion']['status'] == 0) {
            $this->pageTitle = $this->ld['promotion_forbidden'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['promotion_forbidden'], '/', 5);
            $flag = 0;
        }
        if ($flag) {
            /*时间格式化*/
        $promotion['Promotion']['created'] = substr($promotion['Promotion']['start_time'], 0, 10);
            $promotion['Promotion']['modified'] = substr($promotion['Promotion']['end_time'], 0, 10);
        //商品
    //	$promotion_products = $this->PromotionProduct->findallbypromotion_id($id);
        $promotion_id_conditions = array('PromotionProduct.promotion_id' => $id);
            $promotion_products = $this->PromotionProduct->get_promotion_products($promotion_id_conditions);
   // 	pr($promotion_products);
        $product_ids = array();
            if (isset($promotion_products) && count($promotion_products) > 0) {
                foreach ($promotion_products as $k => $v) {
                    $product_ids[] = $v['PromotionProduct']['product_id'];
                //$products[$v['PromotionProduct']['product_id']] = $this->Product->findbyid($v['PromotionProduct']['product_id']);
                //$products[$v['PromotionProduct']['product_id']]['Product']['shop_price'] = $v['PromotionProduct']['price'];
                }
            }
            if (!empty($product_ids)) {
                $products_list = $this->Product->get_list(implode(',', $product_ids), LOCALE);
                $products = array();
                if (isset($products_list) && sizeof($products_list) > 0) {
                    foreach ($products_list as $k => $v) {
                        $products[$v['Product']['id']] = $v;
                    }
                }
            }
            if (isset($promotion_products) && count($promotion_products) > 0) {
                foreach ($promotion_products as $k => $v) {
                    $products[$v['PromotionProduct']['product_id']]['Product']['shop_price'] = $v['PromotionProduct']['price'];
                }
            }
    //	pr($products);
        $this->ur_heres[] = array('name' => $this->ld['promotion'].$this->ld['home'],'url' => '/promotions/');
            $this->ur_heres[] = array('name' => $promotion['PromotionI18n']['title'],'url' => '');
        //$this->set('ur_heres',$ur_heres);
        //$this->set('neighbours',$this->Promotion->findNeighbours('',array('id','PromotionI18n.title'),$id));
        if (isset($products)) {
            $this->data['cache_products'] = $products;
            $this->set('products', $products);
            //pr($products);
        }
            $this->set('promotion', $promotion);
            $js_languages = array('comment' => $this->ld['reviews'],
                            'waitting_for_check' => $this->ld['waitting_for_check'],
                        );
            $this->set('js_languages', $js_languages);
            $this->pageTitle = $promotion['PromotionI18n']['title'].' - '.$this->ld['promotion'].$this->ld['home'].' - '.$this->configs['shop_title'];
            $this->set('meta_description', $promotion['PromotionI18n']['title']);
            $this->set('meta_keywords', $promotion['PromotionI18n']['title']);
        }
    }
}
