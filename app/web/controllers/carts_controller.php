<?php

/*****************************************************************************
 * Seevia 用户收藏
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
/**
 *这是一个名为 CartsController 的控制器
 *购物车控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class CartsController extends AppController
{
    public $name = 'Carts';
    public $helpers = array('Html');
    public $uses = array('ProductLease','ProductLeasePrice','UserAccount','PromotionActivityProduct','PackageProduct','ProductAlsobought','CategoryProductI18n','Product','ProductI18n','Region','Shipping','ShippingArea','ShippingAreaRegion','Payment','User','UserAddress','Order','OrderProduct','Packaging','Card','OrderPackaging','OrderCard','Promotion','PromotionProduct','UserRank','ProductRank','PaymentApiLog','UserBalanceLog','UserPointLog','OrderCard','OrderPackaging','Coupon','CouponType','ProductShippingFee','Attribute','ProductAttribute','ProductTypeAttribute','Attribute','ProductLocalePrice','Cart','ProductVolume','AffiliateLog','InvoiceType','ProductRelation','Topic','RegionI18n','MailTemplate','UserLike','SkuProduct','CartProductValue','OrderProductValue','UserStyle','OrderAction');
    public $components = array('RequestHandler','Cookie','Session','Captcha','Notify');
    public $cacheQueries = false;
    public $cacheAction = false;

    /**
     *显示页.
     */
    public function index()
    {
        $this->page_init();
        $this->layout = 'default_full';
        $this->pageTitle = $this->ld['cart'].' - '.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => $this->ld['cart'],'url' => '/carts/');
        $this->set('ur_heres', $this->ur_heres);
        /*判断购物车中商品保存的时间*/
        $this->check_cart_time();
        if (isset($_SESSION['User']['User']['id'])) {
            $this->Cart->updateAll(array('Cart.user_id' => $_SESSION['User']['User']['id']), array('Cart.session_id' => session_id(), 'Cart.user_id' => '0'));
            $cart_products = $this->Cart->find('all', array('conditions' => 'Cart.user_id = '.$_SESSION['User']['User']['id'].'', 'order' => 'Cart.created desc'), $this->locale);
        } else {
            $cart_products = $this->Cart->find('all', array('conditions' => array('Cart.session_id' => session_id(), 'Cart.user_id' => '<>0'), 'order' => 'Cart.created desc'), $this->locale);
        }
        if (isset($cart_products) && sizeof($cart_products) > 0) {
            unset($_SESSION['svcart']['products']);
            unset($_SESSION['svcart']['bespoke']);
            unset($_SESSION['product']);
            $p_ids = $this->Product->get_product_ids($cart_products);
            $p_codes = $this->Product->get_product_codes($cart_products);
            $product_attr_type_lists = $this->Attribute->find_product_attr_type_list();
            $product_attr_lists = $this->ProductAttribute->find_product_attr_list($p_ids, $this->model_locale['product']);
            $svcart_products_list = $this->Product->find_svcart_products_list($p_ids);
            $svcart_products_price_list = $this->Product->getOrderProductPriceList($p_ids, $p_codes);
            $svcart_products_lease_list = $this->ProductLease->find_svcart_lease_deposit($p_codes);
            $sku_pro_codes = array();
            if (!empty($this->Product->sku_arr)) {
                foreach ($cart_products as $v) {
                    foreach ($this->Product->sku_arr as $vv) {
                        if ($v['Cart']['product_id'] == $vv) {
                            $sku_pro_codes[$v['Cart']['product_id']][] = $v['Cart']['product_code'];
                        }
                    }
                }
                $sku_product = $this->SkuProduct->sale_sku_product($sku_pro_codes);
            }
            foreach ($cart_products as $k => $v) {
                if (isset($svcart_products_list[$v['Cart']['product_id']])) {
                    $cart_product_type_id = $svcart_products_list[$v['Cart']['product_id']]['Product']['product_type_id'];
                    if ($v['Cart']['product_attrbute'] == '' && ($v['Cart']['user_style_id'] == '0' || $v['Cart']['user_style_id'] == '') && $v['Cart']['shipping_type'] == '') {
                        $new_id = $v['Cart']['product_id'];
                    } else {
                        $new_id = $v['Cart']['product_id'].$v['Cart']['product_code'];
                        $attributes = $v['Cart']['product_attrbute'];
                        if (isset($sku_product[$new_id][$v['Cart']['product_code']])) {
                            $new_id = $v['Cart']['product_id'].$v['Cart']['product_code'];
                        } else {
                            $this_attr = explode('<br />', $v['Cart']['product_attrbute']);
                            foreach ($this_attr as $val) {
                                $val_arr = explode(':', $val);
                                if (isset($val_arr[0]) && trim($val) != '' && isset($product_attr_type_lists[$cart_product_type_id][trim($val_arr[0])]) && isset($product_attr_lists[$v['Cart']['product_id']][$product_attr_type_lists[$cart_product_type_id][trim($val_arr[0])]])   && isset($product_attr_lists[$v['Cart']['product_id']][$product_attr_type_lists[$cart_product_type_id][trim($val_arr[0])]][trim($val_arr[1])]) && !empty($product_attr_lists[$v['Cart']['product_id']][$product_attr_type_lists[$cart_product_type_id][trim($val_arr[0])]][trim($val_arr[1])])) {
                                    //$new_id.= '.'.$product_attr_lists[$v['Cart']['product_id']][$product_attr_type_lists[$cart_product_type_id][trim($val_arr[0])]][trim($val_arr[1])]['ProductAttribute']['id'];
                                }
                            }
                        }
                        if (!empty($v['CartProductValue'])) {
                            foreach ($v['CartProductValue'] as $cpv) {
                                $new_id .= $cpv['attribute_id'].':'.$cpv['attribute_value'].';';
                            }
                            $new_id = substr($new_id, 0, strlen($new_id) - 1);
                            $new_id = md5($new_id);
                        }
                        if ($v['Cart']['shipping_type'] == 'bespoke') {
                            $_SESSION['svcart']['bespoke'][$new_id]['attributes'] = $attributes;
                        } else {
                            $_SESSION['svcart']['products'][$new_id]['attributes'] = $attributes;
                        }
                    }
                    if (!empty($v['Cart']['file_url'])) {
                        $file_types = explode('/', $v['Cart']['file_url']);
                        $file_name_type = isset($file_types[count($file_types) - 1]) ? $file_types[count($file_types) - 1] : '';
                        $file_name = explode('.', $file_name_type);
                        $file_name_id = isset($file_name[0]) ? $file_name[0] : '';
                        $new_id = $new_id.'_'.$file_name_id;
                    }
                    //租赁
                    $is_lease=0;
                    $lease_price=0;
                    $lease_day=0;
                    $lease_deposit=0;
                    if($v['Cart']['type']=="L"){
                        $is_lease=1;
                        $lease_price=$v['Cart']['product_price'];
                        $lease_day=$v['Cart']['unit'];
                        $lease_deposit=isset($svcart_products_lease_list[$v['Cart']['product_code']]['lease_deposit'])?$svcart_products_lease_list[$v['Cart']['product_code']]['lease_deposit']:0;//保证金
                    }
                    if ($v['Cart']['shipping_type'] == 'bespoke') {
                        $_SESSION['svcart']['bespoke'][$new_id]['Product'] = array(
                            'id' => $svcart_products_list[$v['Cart']['product_id']]['Product']['id'],
                            'code' => $v['Cart']['product_code'],
                            'lease_price'=>$lease_price,
                            'is_lease'=>$is_lease,
                            'lease_day'=>$lease_day,
                            'lease_deposit'=>$lease_deposit,
                            'weight' => $svcart_products_list[$v['Cart']['product_id']]['Product']['weight'],
                            'market_price' => $svcart_products_list[$v['Cart']['product_id']]['Product']['market_price'] + $v['Cart']['product_price'] - $svcart_products_price_list[$v['Cart']['product_id'].$v['Cart']['product_code']],
                            'shop_price' => $svcart_products_price_list[$v['Cart']['product_id'].$v['Cart']['product_code']],
                            'promotion_price' => $svcart_products_list[$v['Cart']['product_id']]['Product']['promotion_price'],
                            'promotion_start' => $svcart_products_list[$v['Cart']['product_id']]['Product']['promotion_start'],
                            'promotion_end' => $svcart_products_list[$v['Cart']['product_id']]['Product']['promotion_end'],
                            'promotion_status' => $svcart_products_list[$v['Cart']['product_id']]['Product']['promotion_status'],
                            'product_rank_id' => $svcart_products_list[$v['Cart']['product_id']]['Product']['product_rank_id'],
                            'extension_code' => $svcart_products_list[$v['Cart']['product_id']]['Product']['extension_code'],
                            'frozen_quantity' => $svcart_products_list[$v['Cart']['product_id']]['Product']['frozen_quantity'],
                            'product_type_id' => $svcart_products_list[$v['Cart']['product_id']]['Product']['product_type_id'],
                            'brand_id' => $svcart_products_list[$v['Cart']['product_id']]['Product']['brand_id'],
                            'coupon_type_id' => $svcart_products_list[$v['Cart']['product_id']]['Product']['coupon_type_id'],
                            'point' => $svcart_products_list[$v['Cart']['product_id']]['Product']['point'],
                            'img_thumb' => $svcart_products_list[$v['Cart']['product_id']]['Product']['img_thumb'],
                            'img_detail' => $svcart_products_list[$v['Cart']['product_id']]['Product']['img_detail'],
                            'file_url' => $v['Cart']['file_url'],
                            'point_fee' => $svcart_products_list[$v['Cart']['product_id']]['Product']['point_fee'],
                            'freeshopping' => $svcart_products_list[$v['Cart']['product_id']]['Product']['freeshopping'],//免运费
                            'wholesale' => $svcart_products_list[$v['Cart']['product_id']]['Product']['wholesale'],//批发
                        );
                        $_SESSION['svcart']['bespoke'][$new_id]['PackageProduct'] = $svcart_products_list[$v['Cart']['product_id']]['package_product'];
                        $_SESSION['svcart']['bespoke'][$new_id]['PackageProduct_total'] = $svcart_products_list[$v['Cart']['product_id']]['package_product_total'];
                        $_SESSION['svcart']['bespoke'][$new_id]['quantity'] = $v['Cart']['product_quantity'];
                        $_SESSION['svcart']['bespoke'][$new_id]['category_name'] = isset($this->CategoryProduct->allinfo['P']['assoc'][$svcart_products_list[$v['Cart']['product_id']]['Product']['category_id']]) ? $this->CategoryProduct->allinfo['P']['assoc'][$svcart_products_list[$v['Cart']['product_id']]['Product']['category_id']]['CategoryProductI18n']['name'] : '';
                        $_SESSION['svcart']['bespoke'][$new_id]['category_id'] = $svcart_products_list[$v['Cart']['product_id']]['Product']['category_id'];
                        $_SESSION['svcart']['bespoke'][$new_id]['use_point'] = 0;
                        $_SESSION['svcart']['bespoke'][$new_id]['save_cart'] = $v['Cart']['id'];
                        $_SESSION['svcart']['bespoke'][$new_id]['ProductI18n'] = array('name' => $svcart_products_list[$v['Cart']['product_id']]['ProductI18n']['name']);
                        $_SESSION['svcart']['bespoke'][$new_id]['CartProductValue'] = !empty($v['CartProductValue']) ? $v['CartProductValue'] : array();
                        $_SESSION['svcart']['bespoke'][$new_id]['AccessoryPrice'] = !empty($v['AccessoryPrice']) ? $v['AccessoryPrice'] : array();
                        $_SESSION['svcart']['bespoke'][$new_id]['user_style_id'] = $v['Cart']['user_style_id'] != '' ? $v['Cart']['user_style_id'] : '';
                        $_SESSION['svcart']['bespoke'][$new_id]['schedule_date'] = $v['Cart']['schedule_date'] != '2008-01-01' ? $v['Cart']['schedule_date'] : '2008-01-01';
                        $_SESSION['svcart']['bespoke'][$new_id]['schedule_time'] = $v['Cart']['schedule_time'] != '' ? $v['Cart']['schedule_time'] : '';
                        $_SESSION['svcart']['bespoke'][$new_id]['shipping_type'] = $v['Cart']['shipping_type'] != '' ? $v['Cart']['shipping_type'] : '';
                        $_SESSION['svcart']['bespoke'][$new_id]['note'] = $v['Cart']['note'];
                    } else {
                        $_SESSION['svcart']['products'][$new_id]['Product'] = array(
                            'id' => $svcart_products_list[$v['Cart']['product_id']]['Product']['id'],
                            'code' => $v['Cart']['product_code'],
                            'lease_price'=>$lease_price,
                            'is_lease'=>$is_lease,
                            'lease_day'=>$lease_day,
                            'lease_deposit'=>$lease_deposit,
                            'weight' => $svcart_products_list[$v['Cart']['product_id']]['Product']['weight'],
                            'market_price' => $svcart_products_list[$v['Cart']['product_id']]['Product']['market_price'] + $v['Cart']['product_price'] - $svcart_products_price_list[$v['Cart']['product_id'].$v['Cart']['product_code']],
                            'shop_price' => $svcart_products_price_list[$v['Cart']['product_id'].$v['Cart']['product_code']],
                            'promotion_price' => $svcart_products_list[$v['Cart']['product_id']]['Product']['promotion_price'],
                            'promotion_start' => $svcart_products_list[$v['Cart']['product_id']]['Product']['promotion_start'],
                            'promotion_end' => $svcart_products_list[$v['Cart']['product_id']]['Product']['promotion_end'],
                            'promotion_status' => $svcart_products_list[$v['Cart']['product_id']]['Product']['promotion_status'],
                            'product_rank_id' => $svcart_products_list[$v['Cart']['product_id']]['Product']['product_rank_id'],
                            'extension_code' => $svcart_products_list[$v['Cart']['product_id']]['Product']['extension_code'],
                            'frozen_quantity' => $svcart_products_list[$v['Cart']['product_id']]['Product']['frozen_quantity'],
                            'product_type_id' => $svcart_products_list[$v['Cart']['product_id']]['Product']['product_type_id'],
                            'brand_id' => $svcart_products_list[$v['Cart']['product_id']]['Product']['brand_id'],
                            'coupon_type_id' => $svcart_products_list[$v['Cart']['product_id']]['Product']['coupon_type_id'],
                            'point' => $svcart_products_list[$v['Cart']['product_id']]['Product']['point'],
                            'img_thumb' => $svcart_products_list[$v['Cart']['product_id']]['Product']['img_thumb'],
                            'img_detail' => $svcart_products_list[$v['Cart']['product_id']]['Product']['img_detail'],
                            'file_url' => $v['Cart']['file_url'],
                            'point_fee' => $svcart_products_list[$v['Cart']['product_id']]['Product']['point_fee'],
                            'freeshopping' => $svcart_products_list[$v['Cart']['product_id']]['Product']['freeshopping'],//免运费
                            'wholesale' => $svcart_products_list[$v['Cart']['product_id']]['Product']['wholesale'],//批发
                        );
                        $_SESSION['svcart']['products'][$new_id]['PackageProduct'] = $svcart_products_list[$v['Cart']['product_id']]['package_product'];
                        $_SESSION['svcart']['products'][$new_id]['PackageProduct_total'] = $svcart_products_list[$v['Cart']['product_id']]['package_product_total'];
                        if (isset($this->configs['volume_setting']) && $this->configs['volume_setting']) {
                            $product_volume = $this->ProductVolume->find('first', array('conditions' => array('ProductVolume.product_id' => $v['Cart']['product_id'], 'ProductVolume.volume_number <=' => $v['Cart']['product_quantity']), 'limit' => 1, 'order' => 'ProductVolume.volume_number desc'));
                        }
                        $attr_total = 0;
                        if (isset($product_volume['ProductVolume'])&&$v['Cart']['type']=="P") {
                            $volume_price = $product_volume['ProductVolume']['volume_price'];
                            if (($v['Cart']['product_price'] - $volume_price) > 0) {
                                $attr_total = $v['Cart']['product_price'] - $volume_price;
                                $_SESSION['svcart']['products'][$new_id]['adjust_fee'] = $volume_price;
                            }
                        }
                        if (isset($attr_total) && $attr_total > 0) {
                            $_SESSION['svcart']['products'][$new_id]['attributes_total'] = $attr_total;
                        }
                        $_SESSION['svcart']['products'][$new_id]['quantity'] = $v['Cart']['product_quantity'];
                        $_SESSION['svcart']['products'][$new_id]['category_name'] = isset($this->CategoryProduct->allinfo['P']['assoc'][$svcart_products_list[$v['Cart']['product_id']]['Product']['category_id']]) ? $this->CategoryProduct->allinfo['P']['assoc'][$svcart_products_list[$v['Cart']['product_id']]['Product']['category_id']]['CategoryProductI18n']['name'] : '';
                        $_SESSION['svcart']['products'][$new_id]['category_id'] = $svcart_products_list[$v['Cart']['product_id']]['Product']['category_id'];
                        $_SESSION['svcart']['products'][$new_id]['use_point'] = 0;
                        $_SESSION['svcart']['products'][$new_id]['save_cart'] = $v['Cart']['id'];
                        $_SESSION['svcart']['products'][$new_id]['ProductI18n'] = array('name' => $svcart_products_list[$v['Cart']['product_id']]['ProductI18n']['name']);
                        $_SESSION['svcart']['products'][$new_id]['CartProductValue'] = !empty($v['CartProductValue']) ? $v['CartProductValue'] : array();
                        $_SESSION['svcart']['products'][$new_id]['AccessoryPrice'] = !empty($v['AccessoryPrice']) ? $v['AccessoryPrice'] : array();
                        $_SESSION['svcart']['products'][$new_id]['user_style_id'] = $v['Cart']['user_style_id'] != '' ? $v['Cart']['user_style_id'] : '';
                        $_SESSION['svcart']['products'][$new_id]['schedule_date'] = $v['Cart']['schedule_date'] != '2008-01-01' ? $v['Cart']['schedule_date'] : '2008-01-01';
                        $_SESSION['svcart']['products'][$new_id]['schedule_time'] = $v['Cart']['schedule_time'] != '' ? $v['Cart']['schedule_time'] : '';
                        $_SESSION['svcart']['products'][$new_id]['shipping_type'] = $v['Cart']['shipping_type'] != '' ? $v['Cart']['shipping_type'] : '';
                        $_SESSION['svcart']['products'][$new_id]['note'] = $v['Cart']['note'];
                    }
                }
                if ($v['Cart']['shipping_type'] != 'bespoke') {
                    if (isset($sku_product[$v['Cart']['product_id']])) {
                        $_SESSION['svcart']['products'][$new_id]['sku_product'] = $sku_product[$v['Cart']['product_id']][$v['Cart']['product_code']]['sku_product'];
                        $_SESSION['svcart']['products'][$new_id]['parent_product_id'] = isset($sku_product[$new_id][$v['Cart']['product_code']]['parent_product_id']) ? $sku_product[$new_id][$v['Cart']['product_code']]['parent_product_id'] : '';
                    }
                }
            }
        }
        if (!isset($_SESSION['svcart']['products']) && isset($_COOKIE['CakeCookie']['cart_cookie'])) {
            $_SESSION['svcart'] = @unserialize(StripSlashes($this->Cookie->read('cart_cookie')));
        }
        $this->statistic_svcart();
        $this->order_price();
        //输出Seevia里的信息
        if (isset($_SESSION['svcart']['products']) || isset($_SESSION['svcart']['bespoke'])) {
            $this->statistic_svcart();
//            foreach($_SESSION['svcart']['products'] as $k=>$v) {
//                if(isset($v['attributes_total'])&&!empty($v['attributes_total'])){
//                	$_SESSION['svcart']['products'][$k]['Product']['shop_price']=$v['attributes_total']+$v['Product']['shop_price'];
//                	$_SESSION['svcart']['products'][$k]['Product']['market_price']=$v['attributes_total']+$v['Product']['market_price'];
//                	unset($_SESSION['svcart']['products'][$k]['attributes_total']);
//               	}
//            }
            $this->set('all_virtual', $_SESSION['svcart']['cart_info']['all_virtual']);
            if (isset($this->configs['category_link_type']) && $this->configs['category_link_type'] == 1) {
                foreach ($_SESSION['svcart']['products'] as $k => $v) {
                    $info = $this->CategoryProductI18n->findbyid($v['category_id']);
                    $_SESSION['svcart']['products'][$k]['use_sku'] = 1;
                    if ($info['CategoryProductI18n']['parent_id'] > 0) {
                        $parent_info = $this->CategoryProductI18n->findbyid($info['CategoryProductI18n']['parent_id']);
                        if (isset($parent_info['CategoryProductI18n'])) {
                            $parent_info['CategoryProductI18nI18n']['name'] = str_replace(' ', '-', $parent_info['CategoryProductI18nI18n']['name']);
                            $parent_info['CategoryProductI18nI18n']['name'] = str_replace('/', '-', $parent_info['CategoryProductI18nI18n']['name']);
                            $_SESSION['svcart']['products'][$k]['parent'] = $parent_info['CategoryProductI18nI18n']['name'];
                        }
                    }
                }
            }
            //已购买商品的相关商品显示
            if (isset($this->configs['cart_product_relation']) && $this->configs['cart_product_relation'] == '1') {
                $product_ids = array();
                $product_ids_bak = array();
                if (isset($_SESSION['svcart']['products'])) {
                    foreach ($_SESSION['svcart']['products'] as $k => $v) {
                        $product_ids_bak[] = $v['Product']['id'];
                        $conditions = array(
                            'AND' => array('Product.status' => '1','Product.forsale' => '1'),
                            'OR' => array('ProductRelation.product_id ' => $v['Product']['id'],'ProductRelation.related_product_id ' => $v['Product']['id']),
                        );
                        $relation_ids = $this->ProductRelation->find_relation_ids($conditions);//model调用
                        $product_ids += $relation_ids;
                    }
                }
                if (isset($_SESSION['svcart']['bespoke'])) {
                    foreach ($_SESSION['svcart']['bespoke'] as $k => $v) {
                        $product_ids_bak[] = $v['Product']['id'];
                        $conditions = array(
                            'AND' => array('Product.status' => '1','Product.forsale' => '1'),
                            'OR' => array('ProductRelation.product_id ' => $v['Product']['id'],'ProductRelation.related_product_id ' => $v['Product']['id']),
                        );
                        $relation_ids = $this->ProductRelation->find_relation_ids($conditions);//model调用
                        $product_ids += $relation_ids;
                    }
                }
                if (sizeof($product_ids) > 0) {
                    $relation_ids_list = array();
                    foreach ($relation_ids as $k => $v) {
                        if (!in_array($v['ProductRelation']['product_id'], $product_ids_bak)) {
                            $relation_ids_list[] = $v['ProductRelation']['product_id'];
                        }
                        if (!in_array($v['ProductRelation']['related_product_id'], $product_ids_bak)) {
                            $relation_ids_list[] = $v['ProductRelation']['related_product_id'];
                        }
                    }
                    $relation_products = $this->Product->find('all', array('conditions' => array('Product.id' => $relation_ids_list, 'Product.status' => 1, 'Product.forsale' => 1)));
                    if (isset($this->configs['cart_product_relation_number']) && $this->configs['cart_product_relation_number'] > 0) {
                        $relation_products = array_slice($relation_products, '0', $this->configs['cart_product_relation_number']);
                    }
                    foreach ($relation_products as $k => $v) {
                        if (isset($this->configs['mlti_currency_module']) && $this->configs['mlti_currency_module'] == 1 && isset($v['ProductLocalePrice']['product_price'])) {
                            $relation_products[$k]['Product']['shop_price'] = $v['ProductLocalePrice']['product_price'];
                        }
                        if (isset($relation_products[$k]['ProductI18n']['name']) && isset($this->configs['products_name_length']) && $this->configs['products_name_length'] > 0) {
                            $relation_products[$k]['ProductI18n']['sub_name'] = $this->Product->sub_str($relation_products[$k]['ProductI18n']['name'], $this->configs['products_name_length']);
                        } else {
                            $relation_products[$k]['ProductI18n']['sub_name'] = $relation_products[$k]['ProductI18n']['name'];
                        }
                    }
                    $this->data['relation_products'] = $relation_products;
                    $this->set('relation_products', $relation_products);
                }
            }
            $this->set('svcart', $_SESSION['svcart']);
        }
        //查询所有属性信息
        $this->Attribute->set_locale(LOCALE);
        $all_attr_list = array();
        $all_attr_info = $this->Attribute->find('all', array('fields' => array('Attribute.id,AttributeI18n.name'), array('conditions' => array('Attribute.status' => 1))));
        foreach ($all_attr_info as $v) {
            $all_attr_list[$v['Attribute']['id']] = $v['AttributeI18n']['name'];
        }
        $this->set('all_attr_list', $all_attr_list);
        $topics = $this->Topic->find('all', array('conditions' => array('Topic.start_time <=' => DateTime, 'Topic.end_time >=' => DateTime),
            'order' => 'Topic.created DESC',
            'fields' => array('Topic.id', 'TopicI18n.title'), ));
        $this->set('topics', $topics);
        $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        $this->set('p_url', $host);
        if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
            $this->layout = 'ajax';
            $this->render('cart_content');
        }
    }

    /**
     *订单价格页.
     */
    public function order_price()
    {
        //统计商品价格
        $_SESSION['svcart']['cart_info']['sum_subtotal'] = 0;
        $_SESSION['svcart']['cart_info']['sum_market_subtotal'] = 0;
        $_SESSION['svcart']['cart_info']['shop_subtotal'] = 0;
        if (isset($_SESSION['svcart']['products']) && sizeof($_SESSION['svcart']['products']) > 0) {
            foreach ($_SESSION['svcart']['products'] as $k => $v) {
                //租赁
                if($v["Product"]["is_lease"]==1){
                    continue;
                }
                $_SESSION['svcart']['cart_info']['sum_subtotal'] += $v['subtotal'];
                $_SESSION['svcart']['cart_info']['shop_subtotal'] += $v['Product']['shop_price'];
                $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $v['market_subtotal'];
            }
            $_SESSION['svcart']['cart_info']['all_product'] = $_SESSION['svcart']['cart_info']['sum_subtotal'];
        }
        if (!isset($_SESSION['svcart']['products']) && isset($_SESSION['svcart']['bespoke']) && sizeof($_SESSION['svcart']['bespoke']) > 0) {
            foreach ($_SESSION['svcart']['bespoke'] as $k => $v) {
                $_SESSION['svcart']['cart_info']['sum_subtotal'] += $v['subtotal'];
                $_SESSION['svcart']['cart_info']['shop_subtotal'] += $v['Product']['shop_price'];
                $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $v['market_subtotal'];
            }
            $_SESSION['svcart']['cart_info']['all_product'] = $_SESSION['svcart']['cart_info']['sum_subtotal'];
        }
        if (isset($_SESSION['svcart']['cards']) && sizeof($_SESSION['svcart']['cards']) > 0) {
            foreach ($_SESSION['svcart']['cards'] as $k => $v) {
                $_SESSION['svcart']['cart_info']['sum_subtotal'] += $v['subtotal'];
                $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $v['subtotal'];
            }
        }
        if (isset($_SESSION['svcart']['packagings']) && sizeof($_SESSION['svcart']['packagings']) > 0) {
            foreach ($_SESSION['svcart']['packagings'] as $k => $v) {
                $_SESSION['svcart']['cart_info']['sum_subtotal'] += $v['subtotal'];
                $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $v['subtotal'];
            }
        }
        if ($_SESSION['svcart']['cart_info']['sum_market_subtotal'] > 0) {
            $_SESSION['svcart']['cart_info']['discount_rate'] = round($_SESSION['svcart']['cart_info']['sum_subtotal'] / $_SESSION['svcart']['cart_info']['sum_market_subtotal'] * 100);
        } else {
            $_SESSION['svcart']['cart_info']['discount_rate'] = 100;
        }
        $_SESSION['svcart']['cart_info']['discount_price'] = $_SESSION['svcart']['cart_info']['sum_market_subtotal'] - $_SESSION['svcart']['cart_info']['shop_subtotal'];
        $_SESSION['svcart']['cart_info']['total'] = $_SESSION['svcart']['cart_info']['sum_subtotal'];
        if (isset($_SESSION['svcart']['shipping']['shipping_fee']) && $_SESSION['svcart']['shipping']['shipping_fee'] != '') {
            $_SESSION['svcart']['cart_info']['total'] = $this->fee_format($_SESSION['svcart']['shipping']['shipping_fee'], $_SESSION['svcart']['cart_info']['total']);
        }
        if (isset($_SESSION['svcart']['shipping']['insure_fee_confirm']) && $_SESSION['svcart']['shipping']['shipping_fee'] != '') {
            $_SESSION['svcart']['cart_info']['total'] = $this->fee_format($_SESSION['svcart']['shipping']['insure_fee_confirm'], $_SESSION['svcart']['cart_info']['total']);
        }
        if (isset($_SESSION['svcart']['payment']['payment_fee']) && $_SESSION['svcart']['payment']['payment_fee'] != '') {
            $_SESSION['svcart']['payment']['payment_fee'] = $this->fee_format_no_price($_SESSION['svcart']['payment']['payment_fee'], $_SESSION['svcart']['cart_info']['total']);
            $_SESSION['svcart']['cart_info']['total'] = $this->fee_format($_SESSION['svcart']['payment']['payment_fee'], $_SESSION['svcart']['cart_info']['total']);
        }
        if (isset($_SESSION['svcart']['point']['fee'])) {
            $_SESSION['svcart']['cart_info']['total']=$_SESSION['svcart']['cart_info']['total']-$_SESSION['svcart']['point']['fee'];
        }
        //如果有优惠券的话  chenfan 2012/05/30
        if (isset($_SESSION['svcart']['coupon']) && sizeof($_SESSION['svcart']['coupon']) > 0) {
            $old_total = $_SESSION['svcart']['cart_info']['total'];
            $total = $_SESSION['svcart']['cart_info']['total'];
            foreach ($_SESSION['svcart']['coupon'] as $sc) {
                $total = ($total - $sc['fee']) * $sc['discount'] / 100;
            }
            $_SESSION['svcart']['cart_info']['coupon_del'] = round($old_total - $total, 2);
            $_SESSION['svcart']['cart_info']['total'] = $total;
        }
        if (isset($_SESSION['svcart']['invoice']['tax_point'])) {
            $_SESSION['svcart']['invoice']['fee'] = round($_SESSION['svcart']['cart_info']['sum_subtotal'] * $_SESSION['svcart']['invoice']['tax_point'] / 100, 2);
            $_SESSION['svcart']['cart_info']['total'] += $_SESSION['svcart']['invoice']['fee'];
        }
        $this->auto_confirm_promotion();
    }

    /**
     *用ajax处理价格秩序页.
     */
    public function ajax_order_price()
    {
        //统计商品价格
        $_SESSION['svcart']['cart_info']['sum_subtotal'] = 0;
        $_SESSION['svcart']['cart_info']['sum_market_subtotal'] = 0;
        if (isset($_SESSION['svcart']['products']) && sizeof($_SESSION['svcart']['products']) > 0) {
            foreach ($_SESSION['svcart']['products'] as $k => $v) {
                $_SESSION['svcart']['cart_info']['sum_subtotal'] += $v['subtotal'];
                $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $v['market_subtotal'];
            }
            $_SESSION['svcart']['cart_info']['all_product'] = $_SESSION['svcart']['cart_info']['sum_subtotal'];
        }
        if (isset($_SESSION['svcart']['cards']) && sizeof($_SESSION['svcart']['cards']) > 0) {
            foreach ($_SESSION['svcart']['cards'] as $k => $v) {
                $_SESSION['svcart']['cart_info']['sum_subtotal'] += $v['subtotal'];
                $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $v['subtotal'];
            }
        }
        if (isset($_SESSION['svcart']['packagings']) && sizeof($_SESSION['svcart']['packagings']) > 0) {
            foreach ($_SESSION['svcart']['packagings'] as $k => $v) {
                $_SESSION['svcart']['cart_info']['sum_subtotal'] += $v['subtotal'];
                $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $v['subtotal'];
            }
        }
        if ($_SESSION['svcart']['cart_info']['sum_market_subtotal'] > 0) {
            $_SESSION['svcart']['cart_info']['discount_rate'] = round($_SESSION['svcart']['cart_info']['sum_subtotal'] / $_SESSION['svcart']['cart_info']['sum_market_subtotal'] * 100);
        } else {
            $_SESSION['svcart']['cart_info']['discount_rate'] = 100;
        }
        $_SESSION['svcart']['cart_info']['discount_price'] = $_SESSION['svcart']['cart_info']['sum_market_subtotal'] - $_SESSION['svcart']['cart_info']['sum_subtotal'];
        $_SESSION['svcart']['cart_info']['total'] = $_SESSION['svcart']['cart_info']['sum_subtotal'];
        if (isset($_SESSION['svcart']['shipping']['shipping_fee'])) {
            $_SESSION['svcart']['cart_info']['total'] += $_SESSION['svcart']['shipping']['shipping_fee'];
        }
        if (isset($_SESSION['svcart']['shipping']['insure_fee_confirm'])) {
            $_SESSION['svcart']['cart_info']['total']  += $_SESSION['svcart']['shipping']['insure_fee_confirm'];
        }
        if (isset($_SESSION['svcart']['payment']['payment_fee'])) {
            $_SESSION['svcart']['payment']['payment_fee'] = $this->fee_format_no_price($_SESSION['svcart']['payment']['payment_fee'], $_SESSION['svcart']['cart_info']['total']);
            $_SESSION['svcart']['cart_info']['total'] = $this->fee_format($_SESSION['svcart']['payment']['payment_fee'], $_SESSION['svcart']['cart_info']['total']);
        }
        if (isset($_SESSION['svcart']['promotion'])) {
            if ($_SESSION['svcart']['promotion']['type'] == 1) {
                //		$_SESSION['svcart']['cart_info']['total'] = round($_SESSION['svcart']['cart_info']['total']*$_SESSION['svcart']['promotion']['promotion_fee']/100,2);
                $_SESSION['svcart']['cart_info']['all_product'] = round($_SESSION['svcart']['cart_info']['all_product'] * $_SESSION['svcart']['promotion']['promotion_fee'] / 100, 2);
                $_SESSION['svcart']['cart_info']['total'] -= $_SESSION['svcart']['cart_info']['all_product'];
            }
            if ($_SESSION['svcart']['promotion']['type'] == 0) {
                $_SESSION['svcart']['cart_info']['total'] -= $_SESSION['svcart']['promotion']['promotion_fee'];
            }

            if ($_SESSION['svcart']['promotion']['type'] == 2 && isset($_SESSION['svcart']['promotion']['product_fee'])) {
                //foreach(){
                $_SESSION['svcart']['cart_info']['total'] += $_SESSION['svcart']['promotion']['product_fee'];
                //}
            }
        }
        if (isset($_SESSION['svcart']['point']['fee'])) {
            $_SESSION['svcart']['cart_info']['total'] -= $_SESSION['svcart']['point']['fee'];
        }
        if (isset($_SESSION['svcart']['coupon']['fee'])) {
            $_SESSION['svcart']['cart_info']['total'] -= $_SESSION['svcart']['coupon']['fee'];
        }
        $this->set('svcart', $_SESSION['svcart']);
    }

    /**
     *checkout价格秩序页.
     */
    public function checkout_order_price()
    {
        //统计商品价格
        $_SESSION['checkout']['cart_info']['sum_subtotal'] = 0;
        $_SESSION['checkout']['cart_info']['sum_market_subtotal'] = 0;
        $_SESSION['svcart']['cart_info']['lease_quantity']=0;
        $_SESSION['svcart']['cart_info']['lease_subtotal']=0;
        $_SESSION['svcart']['cart_info']['lease_total']=0;
        $_SESSION['svcart']['cart_info']['insure_fee']=0;
        if (isset($_SESSION['checkout']['products']) && sizeof($_SESSION['checkout']['products']) > 0) {
            foreach ($_SESSION['checkout']['products'] as $k => $v) {
                //租赁
                if($v["Product"]["is_lease"]==1){
                    $_SESSION['svcart']['cart_info']['lease_quantity'] += $v['quantity'];
                    $_SESSION['svcart']['cart_info']['lease_subtotal'] += $v['Product']['lease_price'] * $v['quantity'];
                    $_SESSION['svcart']['cart_info']['lease_total'] += $v['Product']['shop_price'] * $v['quantity'];
                    continue;
                }
                $_SESSION['checkout']['cart_info']['sum_subtotal'] += $v['subtotal'];
                $_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $v['market_subtotal'];
            }
            $_SESSION['checkout']['cart_info']['all_product'] = $_SESSION['checkout']['cart_info']['sum_subtotal'];
        }
        if (isset($_SESSION['checkout']['bespoke']) && sizeof($_SESSION['checkout']['bespoke']) > 0) {
            foreach ($_SESSION['checkout']['bespoke'] as $k => $v) {
                //定制属性价格计算
                $temp_sum_subtotal = 0;
                $temp_sum_market_subtotal = 0;
                if (isset($v['CartProductValue']) && !empty($v['CartProductValue'])) {
                    foreach ($v['CartProductValue'] as $ak => $av) {
                        $temp_sum_subtotal += $av['attr_price'];
                        $temp_sum_market_subtotal += $av['attr_price'];
                    }
                }
                $_SESSION['checkout']['cart_info']['sum_subtotal'] += ($v['subtotal'] + $temp_sum_subtotal);
                $_SESSION['checkout']['cart_info']['sum_market_subtotal'] += ($v['market_subtotal'] + $temp_sum_market_subtotal);
            }
            $_SESSION['checkout']['cart_info']['all_product'] = $_SESSION['checkout']['cart_info']['sum_subtotal'];
        }
        //租赁保证金
        if($_SESSION['svcart']['cart_info']['lease_total']>0){
        	$insure_fee_rule=$this->ProductLeasePrice->find('first',array("conditions"=>array("ProductLeasePrice.price >="=>0,"ProductLeasePrice.price <="=>$_SESSION['svcart']['cart_info']['lease_total']),"order"=>"ProductLeasePrice.price desc"));
        	if($insure_fee_rule['ProductLeasePrice']){
        		if($_SESSION['svcart']['cart_info']['lease_total']>$insure_fee_rule['ProductLeasePrice']['price']){
        			$insure_fee=$insure_fee_rule['ProductLeasePrice']['lease_deposit_base']+($_SESSION['svcart']['cart_info']['lease_total']-$insure_fee_rule['ProductLeasePrice']['price'])*($insure_fee_rule['ProductLeasePrice']['lease_deposit_increase_percent']/100);
        		}else{
        			$insure_fee=$insure_fee_rule['ProductLeasePrice']['lease_deposit_base'];
        		}
        	}
        	$_SESSION['svcart']['cart_info']['insure_fee']=$insure_fee;
        }
        if (isset($_SESSION['checkout']['cards']) && sizeof($_SESSION['checkout']['cards']) > 0) {
            foreach ($_SESSION['checkout']['cards'] as $k => $v) {
                $_SESSION['checkout']['cart_info']['sum_subtotal'] += $v['subtotal'];
                $_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $v['subtotal'];
            }
        }
        if (isset($_SESSION['checkout']['packagings']) && sizeof($_SESSION['checkout']['packagings']) > 0) {
            foreach ($_SESSION['checkout']['packagings'] as $k => $v) {
                $_SESSION['checkout']['cart_info']['sum_subtotal'] += $v['subtotal'];
                $_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $v['subtotal'];
            }
        }
        if ($_SESSION['checkout']['cart_info']['sum_market_subtotal'] > 0) {
            $_SESSION['checkout']['cart_info']['discount_rate'] = round($_SESSION['checkout']['cart_info']['sum_subtotal'] / $_SESSION['checkout']['cart_info']['sum_market_subtotal'] * 100);
        } else {
            $_SESSION['checkout']['cart_info']['discount_rate'] = 100;
        }
        $_SESSION['checkout']['cart_info']['discount_price'] = $_SESSION['checkout']['cart_info']['sum_market_subtotal'] - $_SESSION['checkout']['cart_info']['sum_subtotal'];
        $_SESSION['checkout']['cart_info']['total'] = $_SESSION['checkout']['cart_info']['sum_subtotal']+$_SESSION['svcart']['cart_info']['lease_subtotal']+$_SESSION['svcart']['cart_info']['insure_fee'];
        //如果有优惠券的话  chenfan 2012/05/30
        if (isset($_SESSION['checkout']['coupon']) && sizeof($_SESSION['checkout']['coupon']) > 0) {
            $old_total = $_SESSION['checkout']['cart_info']['total'];
            $total = $_SESSION['checkout']['cart_info']['total'];
            foreach ($_SESSION['checkout']['coupon'] as $sc) {
                $total = ($total - $sc['fee']) * $sc['discount'] / 100;
            }
            $_SESSION['checkout']['cart_info']['coupon_del'] = round($old_total - $total, 2);
            $_SESSION['checkout']['cart_info']['total'] = $total;
        }
        if (isset($_SESSION['checkout']['shipping']['shipping_fee'])) {
            $_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['shipping']['shipping_fee'];
        }
        if (isset($_SESSION['checkout']['shipping']['insure_fee_confirm'])) {
            $_SESSION['checkout']['cart_info']['total']  += $_SESSION['checkout']['shipping']['insure_fee_confirm'];
        }
        if (isset($_SESSION['checkout']['payment']['payment_fee'])) {
            $_SESSION['checkout']['payment']['payment_fee'] = $this->fee_format_no_price($_SESSION['checkout']['payment']['payment_fee'], $_SESSION['checkout']['cart_info']['total']);
            $_SESSION['checkout']['cart_info']['total'] = $this->fee_format($_SESSION['checkout']['payment']['payment_fee'], $_SESSION['checkout']['cart_info']['total']);
        }
        if (isset($_SESSION['checkout']['promotion'])) {
            if (isset($_SESSION['checkout']['promotion']['type']) && $_SESSION['checkout']['promotion']['type'] == 1) {
                //		$_SESSION['checkout']['cart_info']['total'] = round($_SESSION['checkout']['cart_info']['total']*$_SESSION['checkout']['promotion']['promotion_fee']/100,2);
                $_SESSION['checkout']['cart_info']['all_product'] = round($_SESSION['checkout']['cart_info']['all_product'] * $_SESSION['checkout']['promotion']['promotion_fee'] / 100, 2);
                $_SESSION['checkout']['cart_info']['total'] -= $_SESSION['checkout']['cart_info']['all_product'];
            }
            if (isset($_SESSION['checkout']['promotion']['type']) && $_SESSION['checkout']['promotion']['type'] == 0) {
                $_SESSION['checkout']['cart_info']['total'] -= $_SESSION['checkout']['promotion']['promotion_fee'];
            }

            if (isset($_SESSION['checkout']['promotion']['type']) && $_SESSION['checkout']['promotion']['type'] == 2 && isset($_SESSION['checkout']['promotion']['product_fee'])) {
                //foreach(){
                $_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['promotion']['product_fee'];
                //}
            }
        }
        if (isset($_SESSION['checkout']['invoice']['tax_point'])) {
            $_SESSION['checkout']['invoice']['fee'] = round($_SESSION['checkout']['cart_info']['sum_subtotal'] * $_SESSION['checkout']['invoice']['tax_point'] / 100, 2);
            $_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['invoice']['fee'];
        }
        if (isset($_SESSION['checkout']['point']['fee'])) {
            $_SESSION['checkout']['cart_info']['total'] -= $_SESSION['checkout']['point']['fee'];
        }
        if (isset($_SESSION['checkout']['coupon']['fee'])) {
            $_SESSION['checkout']['cart_info']['total'] -= $_SESSION['checkout']['coupon']['fee'];
        }
        $this->set('checkout', $_SESSION['checkout']);
    }

    /**
     *点券处理页.
     */
    public function get_point_and_coupon()
    {
        $send_point = array();
        $total_point = 0;
        
        $order_type='';//交易类型
	if(isset($_SESSION['checkout']['cart_info']['sum_quantity'])&&$_SESSION['checkout']['cart_info']['sum_quantity']!='0'){//购物
		$order_type='P';
	}else if(isset($_SESSION['checkout']['cart_info']['lease_quantity'])&&$_SESSION['checkout']['cart_info']['lease_quantity']!='0'){//租赁
		$order_type='L';
	}
	$can_use_point=false;//积分是否可用
	if(isset($this->configs['use_point'])&&$this->configs['use_point']=='1'){
		$points_occasions=isset($this->configs['points_occasions'])?$this->configs['points_occasions']:'';//积分使用场合
        	if($order_type=='P'&&in_array($points_occasions,array('0','2'))){
        		$can_use_point=true;
        	}else if($order_type=='L'&&in_array($points_occasions,array('0','3'))){
        		$can_use_point=true;
        	}
       }
        if($can_use_point){
            if ($this->configs['order_smallest'] <= $_SESSION['checkout']['cart_info']['sum_subtotal']) {
                $send_point['order_smallest'] = $this->configs['out_order_points'];
                $send_point['order_smallest_fee'] = $this->configs['order_smallest'];
                $total_point += $this->configs['out_order_points'];
            }
        }
        $now = date('Y-m-d H:i:s');
        $product_point = array();
        $send_coupon = array();
        if (isset($_SESSION['checkout']['products'])) {
            foreach ($_SESSION['checkout']['products'] as $k => $v) {
                $product_point[$k]['name'] = $v['ProductI18n']['name'];
                $product_point[$k]['point'] = $v['Product']['point'] * $v['quantity'];
                $total_point += $product_point[$k]['point'];
                if ($v['Product']['coupon_type_id'] > 0) {
                    //	for($i =0;$i<$v['quantity'];$i++){
                    $send_coupon[$k]['coupon'] = $v['Product']['coupon_type_id'];
                    $send_coupon[$k]['name'] = $v['ProductI18n']['name'];
                    $send_coupon[$k]['quantity'] = $v['quantity'];
                    //		}
                }
            }
        }
        if (isset($_SESSION['checkout']['bespoke'])) {
            foreach ($_SESSION['checkout']['bespoke'] as $k => $v) {
                $product_point[$k]['name'] = $v['ProductI18n']['name'];
                $product_point[$k]['point'] = $v['Product']['point'] * $v['quantity'];
                $total_point += $product_point[$k]['point'];
                if ($v['Product']['coupon_type_id'] > 0) {
                    //	for($i =0;$i<$v['quantity'];$i++){
                    $send_coupon[$k]['coupon'] = $v['Product']['coupon_type_id'];
                    $send_coupon[$k]['name'] = $v['ProductI18n']['name'];
                    $send_coupon[$k]['quantity'] = $v['quantity'];
                    //		}
                }
            }
        }
        $this->set('checkout_total_point', $total_point);
        $this->set('send_point', $send_point);
        $this->set('product_point', $product_point);
        $cache_key = md5('find_coupon_types'.'_'.LOCALE);
        $coupon_types = cache::read($cache_key);
        if (!$coupon_types) {
            $coupon_type_arr = $this->CouponType->find_coupon_type_arr();//model调用
            $coupon_types = array();
            if (is_array($coupon_type_arr) && sizeof($coupon_type_arr) > 0) {
                foreach ($coupon_type_arr as $k => $v) {
                    $coupon_types[$v['CouponType']['id']] = $v;
                }
            }
            cache::write($cache_key, $coupon_types);
        }
        if (isset($this->configs['send_coupons']) && $this->configs['send_coupons'] == 1) {
            $order_coupon = array();
            //	$order_coupon_type = $this->CouponType->findall("CouponType.send_type = '2' and CouponType.send_start_date <= '".$now."' and CouponType.send_end_date >= '".$now."'");
            $order_coupon_type = $this->CouponType->find_order_coupon_type($now);//model调用
            if (is_array($order_coupon_type) && sizeof($order_coupon_type) > 0) {
                foreach ($order_coupon_type as $k => $v) {
                    if ($v['CouponType']['min_products_amount'] < $_SESSION['checkout']['cart_info']['sum_subtotal']) {
                        //	$send_coupon_count++;
                        $order_coupon[$k]['name'] = $v['CouponTypeI18n']['name'];
                        $order_coupon[$k]['fee'] = $v['CouponType']['money'];
                    }
                }
            }
            $this->set('order_coupon', $order_coupon);
            // order send end
            $product_coupon = array();
            if (is_array($send_coupon) && sizeof($send_coupon) > 0) {
                foreach ($send_coupon as $key => $value) {
                    if (isset($coupon_types[$value['coupon']])) {
                        //$pro_coupon_type = $this->CouponType->findbyid($value['coupon']);
                        $pro_coupon_type = $coupon_types[$value['coupon']];
                        $product_coupon[$key]['name'] = $value['name'];
                        $product_coupon[$key]['fee'] = $pro_coupon_type['CouponType']['money'];
                        $product_coupon[$key]['quantity'] = $value['quantity'];
                    }
                }
            }
            $this->set('product_coupon', $product_coupon);
        }
    }

    /**
     *结账页.
     */
    public function checkout()
    {
        $_REQUEST=$this->clean_xss($_REQUEST);
        //租赁
        if(isset($_SESSION["checkout"]["cart_info"]["lease_subtotal"])&&isset($_SESSION["checkout"]["cart_info"]["sum_subtotal"])&&$_SESSION["checkout"]["cart_info"]["lease_subtotal"]!=0&&$_SESSION["checkout"]["cart_info"]["sum_subtotal"]!=0) {
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("不能同时购买普通商品与租赁商品");location.href="'.$this->base.'/carts/"</script>';
            exit();
        }
	$order_type='';//交易类型
	if(isset($_SESSION['checkout']['cart_info']['sum_quantity'])&&$_SESSION['checkout']['cart_info']['sum_quantity']!='0'){//购物
		$order_type='P';
	}else if(isset($_SESSION['checkout']['cart_info']['lease_quantity'])&&$_SESSION['checkout']['cart_info']['lease_quantity']!='0'){//租赁
		$order_type='L';
	}
        if (isset($_SESSION['checkout']['cart_info']['lease_subtotal'])) {
            $_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['cart_info']['lease_subtotal'];
        }
        //判断购物金额是否大于最小购物金额
        if (isset($this->configs['shop-min']) && $this->configs['shop-min'] > 0 && $_SESSION['checkout']['cart_info']['total'] < $this->configs['shop-min']) {
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$this->ld['order_amount_under_min'].'");location.href="'.$this->base.'/carts/"</script>';
            exit();
        }
        $this->page_init();
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['checkout_center'],'url' => '/carts/checkout');
        $this->set('ur_heres', $this->ur_heres);
        $c = 0;
        if (!isset($_SESSION['User']['User']['id'])) {
            $this->redirect('/carts/');
        }
        if (isset($_POST['promotion_id']) && !isset($_POST['svcart_theme'])) {
            if (isset($_SESSION['checkout']['promotion'])) {
                unset($_SESSION['checkout']['promotion']);
            }
            $promotions = $this->findpromotions($_POST['promotion_id']);
            if (isset($promotions[0]['Promotion']['id'])) {
                if ($promotions[0]['Promotion']['type'] == 2) {
                    if (isset($_POST['product_id'][$_POST['promotion_id']]) && sizeof($_POST['product_id'][$_POST['promotion_id']]) > 0) {
                        foreach ($_POST['product_id'][$_POST['promotion_id']] as $k => $v) {
                            $set_promotion = array(
                                'promotion_id' => $_POST['promotion_id'],
                                'product_id' => $v,
                            );
                        }
                    }
                } else {
                    $set_promotion = array(
                        'type_ext' => $promotions[0]['Promotion']['type_ext'],
                        'meta_description' => $promotions[0]['PromotionI18n']['meta_description'],
                        'type' => $promotions[0]['Promotion']['type'],
                        'title' => $promotions[0]['PromotionI18n']['title'],
                    );
                }
            }
        }
        if (isset($_POST['order_note']) && trim($_POST['order_note']) != '') {
            $_SESSION['checkout']['order_note'] = trim($_POST['order_note']);
        }
        $this->statistic_checkout();
        //取包装数据
        if (isset($this->configs['enable_buy_packing']) && $this->configs['enable_buy_packing'] == 1) {
            //取得包装信息
            $packaging_lists = $this->Packaging->find_packaging_lists();//model调用
            $this->set('packages', $packaging_lists);
        }
        //取包装数据 end
        if (isset($_SESSION['User'])) {
            /* 结算流程 */
            if (true) {
                $user_info = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
                if (empty($_SESSION['checkout']['shipping']['shipping_id'])) {
                    if (empty($user_info['User']['shipping_id'])) {
                        $this->redirect('/carts/check_shipping');
                        exit;
                    } else {
                        $_SESSION['checkout']['shipping']['shipping_id'] = $user_info['User']['shipping_id'];
                    }
                }
                $shippings = $this->show_shipping_by_address($_SESSION['checkout']['cart_info']['sum_weight'], 0);
                if (is_array($shippings) && sizeof($shippings) > 0) {
                    foreach ($shippings as $k => $v) {
                        if (isset($_SESSION['checkout']['shipping']['shipping_id']) && $_SESSION['checkout']['shipping']['shipping_id'] == $v['Shipping']['id']) {
                            $select_shipping = array(
                                'shipping_id' => $v['Shipping']['id'],
                                'shipping_code' => $v['Shipping']['code'],
                                'shipping_fee' => $v['ShippingArea']['fee'],
                                'free_subtotal' => $v['ShippingArea']['free_subtotal'],
                                'support_cod' => $v['Shipping']['support_cod'],
                            );
                        } elseif (!isset($_SESSION['checkout']['products']) && isset($_SESSION['checkout']['bespoke'])) {
                            //定制预约商品的配送方式
                            $select_shipping = array(
                                'shipping_id' => 13,
                                'shipping_code' => 'bespoke',
                                'shipping_fee' => 0,
                                'free_subtotal' => '',
                                'support_cod' => '',
                            );
                        }
                    }
                }
                if (!empty($select_shipping)) {
                    $this->confirm_shipping($select_shipping);
                } else {
                    $this->redirect($this->server_host.'/carts/check_shipping');
                    exit;
                }
                if (empty($_SESSION['checkout']['address'])) {
                    if (empty($user_info['User']['address_id'])) {
                        $this->redirect('/carts/check_shipping');
                        exit;
                    } else {
                        $address_result = $this->confirm_address($user_info['User']['address_id'], 2);
                        if ($address_result) {
                            $this->redirect('/carts/check_shipping');
                            exit;
                        }
                    }
                }
                if (empty($_SESSION['checkout']['payment']) && empty($_REQUEST['payment'])) {
                    if (empty($user_info['User']['payment_id'])) {
//                        $this->redirect($this->server_host.$this->webroot.'carts/check_payment');
//                        exit;
                    } else {
                        $up_info = $this->Payment->find('first', array('conditions' => array('Payment.id' => $user_info['User']['payment_id'], 'Payment.status' => 1)));
                        if (!empty($up_info)) {
                            $used_code = $this->Payment->find('first', array('conditions' => array('Payment.id' => $user_info['User']['payment_id']), 'fields' => array('Payment.code')));
                            if (empty($used_code) || $used_code = 'AuthorizeNet_AIM') {
                                //                        		$this->redirect($this->server_host.$this->webroot.'carts/check_payment');
//                       			exit;
                            }
                            $_REQUEST['payment'] = $user_info['User']['payment_id'];
                        } else {
                            $all_info = $this->Payment->find('all', array('conditions' => array('Payment.status' => 1)));
                            if (count($all_info) == 1) {
                                $_REQUEST['payment'] = $all_info[0]['Payment']['id'];
                            } else {
//                                $this->redirect($this->server_host.$this->webroot.'carts/check_payment');
//                                exit;
                            }
                        }
                    }
                } else {
                    $_REQUEST['payment'] = empty($_REQUEST['payment']) ? (isset($_SESSION['checkout']['payment']['payment_id'])?$_SESSION['checkout']['payment']['payment_id']:0): $_REQUEST['payment'];//当变量$_REQUEST["payment"]存在的时候payment都会重新取。。。
                }
            }
            if($order_type=='L'&&isset($_SESSION['checkout']['payment']['code'])&&$_SESSION['checkout']['payment']['code']=='cod'){
        		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("租赁无法使用货到付款");location.href="'.$this->base.'/carts/check_payment"</script>';
        		exit();
            }
            if (isset($_SESSION['checkout']['cart_info']) && !isset($_SESSION['checkout']['cart_info']['total'])) {
                $_SESSION['checkout']['cart_info']['total'] = $_SESSION['checkout']['cart_info']['sum_subtotal']+$_SESSION['checkout']['cart_info']['lease_subtotal'];
            } elseif (!isset($_SESSION['checkout']['products']) && isset($_COOKIE['CakeCookie']['cart_cookie'])) {
                $_SESSION['checkout'] = @unserialize($this->Cookie->read('cart_cookie'));
            }
            $this->checkout_order_price();
            if (!(isset($_SESSION['checkout']['products']) && sizeof($_SESSION['checkout']['products']) > 0) && !(isset($_SESSION['checkout']['bespoke']) && sizeof($_SESSION['checkout']['bespoke']) > 0)) {
                $this->pageTitle = $this->ld['no_products_in_cart'].' - '.$this->configs['shop_title'];
                $this->flash($this->ld['no_products_in_cart'], '/carts');
            } else {
                $this->get_point_and_coupon();
                //初始化session
                $this->statistic_checkout();
                $this->set('all_virtual', $_SESSION['checkout']['cart_info']['all_virtual']);
                //取得地址簿
                /* 判断是否需要显示配送方式 */
                if ((isset($_SESSION['checkout']['cart_info']['all_virtual']) && $_SESSION['checkout']['cart_info']['all_virtual'] == 0)
                    || (isset($_SESSION['checkout']['promotion']['all_virtual']) && $_SESSION['checkout']['promotion']['all_virtual'] == 0)) {
                    $this->set('all_virtual', 0);
                    $all_virtual = 0;
                } else {
                    $this->set('all_virtual', 1);
                    $all_virtual = 1;
                }
                /*
                    是否使用发票
                    InvoiceType
                */
                if (isset($this->configs['enable_invoice']) && $this->configs['enable_invoice'] == '1') {
                    $invoice_type = $this->InvoiceType->get_cache_invoice_type(LOCALE);//model调用
                    $this->set('invoice_type', $invoice_type);
                }
                $addresses_count = $this->UserAddress->find_count_addresses($_SESSION['User']['User']['id']);//model调用
                $user_address_id = $this->User->findbyid($_SESSION['User']['User']['id']);
                $need_new_address = 0;
                $address = $this->UserAddress->findbyid($user_address_id['User']['address_id']);
                if ($user_address_id['User']['address_id']  > 0 && !isset($_SESSION['checkout']['address']) && isset($address['UserAddress'])) {
                    $checkout_address = 'confirm_address';
                    $address = $this->UserAddress->findbyid($user_address_id['User']['address_id']);
                    $_SESSION['checkout']['address'] = $address['UserAddress'];
                    $region_array = explode(' ', trim($address['UserAddress']['regions']));
                    if (is_array($region_array) && sizeof($region_array) > 0) {
                        foreach ($region_array as $a => $b) {
                            if ($b == $this->ld['please_select']) {
                                unset($region_array[$a]);
                            }
                        }
                    } else {
                        $region_array[] = 0;
                    }
                    $address['UserAddress']['regions'] = '';
                    $region_name_arr = $this->Region->find_region_name_arr($region_array);//model调用
                    if (is_array($region_name_arr) && sizeof($region_name_arr) > 0) {
                        foreach ($region_name_arr as $k => $v) {
                            $address['UserAddress']['regions'] .= isset($v['RegionI18n']['name']) ? $v['RegionI18n']['name'].' ' : '';
                        }
                    }
                    if ((!isset($address['UserAddress']['mobile']) || !isset($address['UserAddress']['telephone']) || !isset($address['UserAddress']['address'])) || ($address['UserAddress']['mobile'] == '' &&  $address['UserAddress']['telephone'] == '' &&  $address['UserAddress']['address'] == '' && $all_virtual == 0)) {
                        $need_new_address = 1;
                        unset($_SESSION['checkout']['address']);
                    }
                    $_SESSION['checkout']['address']['regionI18n'] = $address['UserAddress']['regions'];
                    $save_cookie = $_SESSION['checkout'];
                    unset($save_cookie['products']);
                    unset($save_cookie['promotion']['products']);
                } elseif ($addresses_count == 0) {
                    unset($_SESSION['checkout']['address']);
                    $checkout_address = 'new_address';
                    $address['UserAddress']['id'] = 'null';
                    $need_new_address = 1;
                } elseif ($addresses_count == 1) {
                    $checkout_address = 'confirm_address';
                    $address = $this->UserAddress->findbyuser_id($_SESSION['User']['User']['id']);
                    $_SESSION['checkout']['address'] = $address['UserAddress'];
                    $region_array = explode(' ', trim($address['UserAddress']['regions']));
                    if (is_array($region_array) && sizeof($region_array) > 0) {
                        foreach ($region_array as $a => $b) {
                            if ($b == $this->ld['please_select']) {
                                unset($region_array[$a]);
                            }
                        }
                    } else {
                        $region_array[] = 0;
                    }
                    $address['UserAddress']['regions'] = '';
                    $region_name_arr = $this->Region->find_region_name_arr($region_array);//model调用
                    if (is_array($region_name_arr) && sizeof($region_name_arr) > 0) {
                        foreach ($region_name_arr as $k => $v) {
                            $address['UserAddress']['regions'] .= isset($v['RegionI18n']['name']) ? $v['RegionI18n']['name'].' ' : '';
                        }
                    }
                    if ($address['UserAddress']['mobile'] == '' &&  $address['UserAddress']['telephone'] == '' &&  $address['UserAddress']['address'] == '' && $all_virtual == 0) {
                        $need_new_address = 1;
                        unset($_SESSION['checkout']['address']);
                    }
                    $_SESSION['checkout']['address']['regionI18n'] = $address['UserAddress']['regions'];
                    $save_cookie = $_SESSION['checkout'];
                    unset($save_cookie['products']);
                    unset($save_cookie['promotion']['products']);
                } else {
                    $checkout_address = 'select_address';
                    $addresses = $this->UserAddress->findAllbyuser_id($_SESSION['User']['User']['id']);
                    foreach ($addresses as $key => $address) {
                        if (isset($region_array) && sizeof($region_array) > 0) {
                            foreach ($region_array as $a => $b) {
                                if ($b == $this->ld['please_select']) {
                                    unset($region_array[$a]);
                                }
                            }
                        } else {
                            $region_array[] = 0;
                        }
                        $region_array = explode(' ', trim($address['UserAddress']['regions']));
                        $addresses[$key]['UserAddress']['regions'] = '';
                        $region_name_arr = $this->Region->find_region_name_arr($region_array);//model调用

                        if (is_array($region_name_arr) && sizeof($region_name_arr) > 0) {
                            foreach ($region_name_arr as $k => $v) {
                                $addresses[$key]['UserAddress']['regions'] .= isset($v['RegionI18n']['name']) ? $v['RegionI18n']['name'].' ' : '';
                            }
                        }
                    }
                    $this->set('addresses', $addresses);
                    $address['UserAddress']['id'] = 'null';
                }
                $this->set('checkout_address', $checkout_address);
                $this->set('address', $address);
                $this->set('addresses_count', $addresses_count);
                $this->set('shipping_type', 0);
                if (isset($_SESSION['checkout']['products']) && sizeof($_SESSION['checkout']['products']) > 0) {
                    $weight = 0;
                    foreach ($_SESSION['checkout']['products'] as $k => $v) {
                        $weight += $v['Product']['weight'];
                    }
                }
                if (isset($_SESSION['checkout']['bespoke']) && sizeof($_SESSION['checkout']['bespoke']) > 0) {
                    $weight = 0;
                    foreach ($_SESSION['checkout']['bespoke'] as $k => $v) {
                        $weight += $v['Product']['weight'];
                    }
                }
                if ($checkout_address == 'confirm_address' && ((isset($_SESSION['checkout']['address']['telephone']) && $_SESSION['checkout']['address']['telephone'] != '') || (isset($_SESSION['checkout']['address']['mobile']) && $_SESSION['checkout']['address']['mobile'] != ''))) {
                    $address = $this->UserAddress->findbyuser_id($_SESSION['User']['User']['id']);
                    if (trim($address['UserAddress']['regions']) != '' && trim($address['UserAddress']['regions']) != $this->ld['please_select'] && !isset($_SESSION['checkout']['shipping']['shipping_fee'])) {
                        $this->show_shipping_by_address($weight);
                    }
                } elseif (isset($_SESSION['checkout']['address']['id']) && trim($_SESSION['checkout']['address']['regions']) != '' &&  trim($_SESSION['checkout']['address']['regions']) != $this->ld['please_select'] && ((isset($_SESSION['checkout']['address']['telephone']) && $_SESSION['checkout']['address']['telephone'] != '') || (isset($_SESSION['checkout']['address']['mobile']) && $_SESSION['checkout']['address']['mobile'] != '')) && !isset($_SESSION['checkout']['shipping']['shipping_fee'])) {
                    $this->show_shipping_by_address($weight);
                }
                if (isset($_SESSION['checkout']['address']['id']) && trim($_SESSION['checkout']['address']['regions']) != '' &&  trim($_SESSION['checkout']['address']['regions']) != $this->ld['please_select']) {
                    $this->show_shipping_by_address($weight);
                }
                //取得可用的支付方式
                $payments = $this->Payment->getOrderPayments();
                $sub_payment = array();
                if (isset($_SESSION['checkout']['payment']['sub_pay'])) {
                    $sub_payment = $_SESSION['checkout']['payment']['sub_pay'];
                }
                $user_info = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
                if (!empty($_REQUEST['payment'])) {
                    $payments = $this->Payment->find('all', array('conditions' => array('Payment.id' => $_REQUEST['payment'])));
                    if($order_type=='L'&&isset($payments[0])&&$payments[0]['Payment']['code']=='cod'){
	        		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("租赁无法使用货到付款");location.href="'.$this->base.'/carts/check_payment"</script>';
	        		exit();
	             }
                }
                if (isset($payments) && sizeof($payments) == 1 && (!isset($_SESSION['checkout']['cart_info']['all_virtual']) || $_SESSION['checkout']['cart_info']['all_virtual'] == 0)) {
                    if ($payments[0]['Payment']['code'] == 'account_pay' && $_SESSION['checkout']['cart_info']['total'] <= $user_info['User']['balance']) {
                        $_SESSION['checkout']['payment'] = array(
                            'payment_id' => $payments[0]['Payment']['id'],
                            'payment_fee' => $payments[0]['Payment']['fee'],
                            'payment_name' => $payments[0]['PaymentI18n']['name'],
                            'payment_description' => $payments[0]['PaymentI18n']['description'],
                            'not_show_change' => '1',
                            'is_cod' => $payments[0]['Payment']['is_cod'],
                            'code' => $payments[0]['Payment']['code'],
                        );
                    } else {
                        $_SESSION['checkout']['payment'] = array(
                            'payment_id' => $payments[0]['Payment']['id'],
                            'payment_fee' => $payments[0]['Payment']['fee'],
                            'payment_name' => $payments[0]['PaymentI18n']['name'],
                            'payment_description' => $payments[0]['PaymentI18n']['description'],
                            'not_show_change' => '1',
                            'is_cod' => $payments[0]['Payment']['is_cod'],
                            'code' => $payments[0]['Payment']['code'],
                            'sub_pay' => $sub_payment,
                        );
                        if ($payments[0]['Payment']['code'] == 'AuthorizeNet_AIM' && isset($_SESSION['aim'])) {
                            $_SESSION['checkout']['payment']['card_num'] = $_SESSION['aim']['card_num'];
                            $_SESSION['checkout']['payment']['card_name'] = $_SESSION['aim']['card_name'];
                            $_SESSION['checkout']['payment']['card_cavv'] = $_SESSION['aim']['card_name'];
                            $_SESSION['checkout']['payment']['cdate'] = $_SESSION['aim']['cdate'];
                        }
                        if ($payments[0]['Payment']['code'] != 'AuthorizeNet_AIM') {
                            unset($_SESSION['aim']);
                        }
                        if (isset($_SESSION['checkout']['payment']['sub_pay'])) {
                            $payment_sub_pay = $_SESSION['checkout']['payment']['sub_pay'];
                            if (isset($payment_sub_pay['Payment']) && $payment_sub_pay['Payment']['parent_id'] != $_SESSION['checkout']['payment']['payment_id']) {
                                unset($_SESSION['checkout']['payment']['sub_pay']);
                            }
                        }
                        if (isset($_REQUEST['sub_pay'])) {
                            $payment_sub_pay = $this->Payment->find('first', array('conditions' => array('Payment.id' => $_REQUEST['sub_pay'])));
                            if (!empty($payment_sub_pay)) {
                                $_SESSION['checkout']['payment']['payment_fee'] += $payment_sub_pay['Payment']['fee'];
                                $_SESSION['checkout']['payment']['sub_pay'] = $payment_sub_pay;
                            }
                        }
                        /*
                        if($payments[0]['Payment']['code']=='bank_trans'){
                            if(isset($_POST['bank_sub_payment']))
                                $_SESSION['checkout']['payment']['sub_pay']=$_POST['bank_sub_payment'];
                            else
                                $this->redirect('/carts/check_payment');
                        }
                        if($payments[0]['Payment']['code']=='pos_pay'){
                            $_SESSION['checkout']['payment']['sub_pay']="";
                            if(isset($_POST['pos_sub_payment']))
                                $_SESSION['checkout']['payment']['sub_pay']=$_POST['pos_sub_payment'];
                            else
                                $this->redirect('/carts/check_payment');
                        }
                        */
                    }
                    /* $everprice=0;
                     foreach($_SESSION['checkout']['products'] as $k=>$v){
                         $everprice+=$v['Product']['shop_price']*$v['quantity'];
                     }
                     //echo $everprice;
                     //只有第一次进入购物车时价格会不加上支付方式设置的手续费，再刷新就会加上，这里做了判断暂时解决该问题
                     if($_SESSION['checkout']['cart_info']['total']-$_SESSION['checkout']['payment']['payment_fee']!=$everprice)
                            $_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['payment']['payment_fee'];
                        */
                    // $this->order_price();
                    $save_cookie = $_SESSION['checkout'];
                    unset($save_cookie['products']);
                    unset($save_cookie['promotion']['products']);
                    //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
                } else {
                    $_SESSION['checkout']['payment']['not_show_change'] = '0';
                }
                $this->checkout_order_price();
                $this->set('need_new_address', $need_new_address);
                $this->set('payments', $payments);
                
		$can_use_point=false;//积分是否可用
		if(isset($this->configs['use_point'])&&$this->configs['use_point']=='1'){
			$points_occasions=isset($this->configs['point-use-status'])?$this->configs['point-use-status']:-1;
	        	if($order_type=='P'&&in_array($points_occasions,array('0','2'))){
	        		$can_use_point=true;
	        	}else if($order_type=='L'&&in_array($points_occasions,array('0','3'))){
	        		$can_use_point=true;
	        	}
	       }
             if($can_use_point){
                    $use_point = round($_SESSION['checkout']['cart_info']['sum_subtotal'] / 100 * $this->configs['proportion_point']);
                    $product_use_point = 0;
                    if (isset($_SESSION['checkout']['products'])) {
                        foreach ($_SESSION['checkout']['products'] as $k => $v) {
                            $product_use_point += $v['Product']['point_fee'] * $v['quantity'];
                        }
                    }
                    if (isset($_SESSION['checkout']['bespoke'])) {
                        foreach ($_SESSION['checkout']['bespoke'] as $k => $v) {
                            $product_use_point += $v['Product']['point_fee'] * $v['quantity'];
                        }
                    }
                    if ($product_use_point < $use_point) {
                        $this->set('can_use_point', $product_use_point);
                    } else {
                        $this->set('can_use_point', $use_point);
                    }
                }
                //我的优惠券
                $cache_key = md5('find_coupon_types'.'_'.LOCALE);
                $coupon_types = cache::read($cache_key);
                if (!$coupon_types) {
                    $coupon_type_arr = $this->CouponType->find_coupon_type_arr();//model调用
                    $coupon_types = array();
                    if (is_array($coupon_type_arr) && sizeof($coupon_type_arr) > 0) {
                        foreach ($coupon_type_arr as $k => $v) {
                            $coupon_types[$v['CouponType']['id']] = $v;
                        }
                    }
                    cache::write($cache_key, $coupon_types);
                }
                $coupons = $this->Coupon->find('all', array('conditions' => 'Coupon.user_id ='.$_SESSION['User']['User']['id']." and Coupon.order_id = '0'"));
                $now = date('Y-m-d H:i:s');
                if (is_array($coupons) && sizeof($coupons) > 0) {
                    foreach ($coupons as $k => $v) {
                        //	$coupon_type = $this->CouponType->findbyid($v['Coupon']['coupon_type_id']);
                        //	if(isset($coupon_types[$value['coupon']])){
                        if (isset($coupon_types[$v['Coupon']['coupon_type_id']]) && $coupon_types[$v['Coupon']['coupon_type_id']]['CouponType']['use_start_date'] <= $now && $coupon_types[$v['Coupon']['coupon_type_id']]['CouponType']['use_end_date'] >= $now) {
                            $coupons[$k]['Coupon']['name'] = $coupon_types[$v['Coupon']['coupon_type_id']]['CouponTypeI18n']['name'];
                            $coupons[$k]['Coupon']['fee'] = $coupon_types[$v['Coupon']['coupon_type_id']]['CouponType']['money'];
                        } else {
                            unset($coupons[$k]);
                        }
                    }
                    $this->set('coupons', $coupons);
                }
                $this->set('user_info', $user_info);
                //如果是aim  且没有信息的 返回
                if (isset($_SESSION['checkout']['payment']['code']) && $_SESSION['checkout']['payment']['code'] == 'AuthorizeNet_AIM' && !isset($_SESSION['aim'])) {
                    $this->redirect($this->server_host.$this->webroot.'carts/check_payment');
                }
                //配送和支付方式的判断
                $s_info = $this->Shipping->find('first', array('conditions' => array('Shipping.id' => $_SESSION['checkout']['shipping']['shipping_id'], 'Shipping.status' => 1)));
                if (empty($s_info)) {
                    $this->redirect('/carts/check_shipping');
                    exit;
                }
                if(isset($_SESSION['checkout']['payment']['payment_id'])){
	                $p_info = $this->Payment->find('first', array('conditions' => array('Payment.id' => $_SESSION['checkout']['payment']['payment_id'], 'Payment.status' => 1)));
	                if (empty($p_info)) {
	                    $this->redirect('/carts/check_payment');
	                    exit;
	                }
                }
                $this->set('checkout', $_SESSION['checkout']);
            }
        } else {
            $this->checkSessionUser();
            exit;
        }
        $this->pageTitle = $this->ld['checkout_center'].' - '.$this->configs['shop_title'];
        $this->layout = 'default_full';
        $js_languages = array('address_label_not_empty' => $this->ld['address'].$this->ld['label'].$this->ld['can_not_empty'],
            'consignee_name_not_empty' => $this->ld['consignee'].$this->ld['user_name'].$this->ld['can_not_empty'],
            'address_detail_not_empty' => $this->ld['address'].$this->ld['can_not_empty'],
            'mobile_phone_not_empty' => $this->ld['mobile'].$this->ld['can_not_empty'],
            'tel_number_not_empty' => $this->ld['telephone'].$this->ld['can_not_empty'],
            'zip_code_not_empty' => $this->ld['zip'].$this->ld['can_not_empty'],
            'order_note_not_empty' => $this->ld['order'].$this->ld['remark'].$this->ld['can_not_empty'],
            'invalid_email' => $this->ld['email'].$this->ld['not_correct'],
            'invalid_tel' => $this->ld['telephone'].$this->ld['not_correct'],
            'please_choose' => $this->ld['please_select'],
            'please_choose_invoice_type' => $this->ld['please_select'].$this->ld['invoice_type'],
            'please_choose_payment' => $this->ld['please_select'].$this->ld['payment'],
            'please_choose_shipping' => $this->ld['please_select'].$this->ld['shipping_method'],
            'choose_area' => $this->ld['please_select'].$this->ld['area'],
            'invalid_tel_number' => $this->ld['telephone'].$this->ld['not_correct'],
            'not_less_eight_characters' => $this->ld['not_less_eight_characters'],
            'telephone_or_mobile' => $this->ld['telephone_or_mobile'],
            'exceed_max_value_can_use ' => $this->ld['exceed_max_value_can_use'],
            'point_not_empty' => $this->ld['point'].$this->ld['can_not_empty'],
            'coupon_phone_not_empty' => $this->ld['coupon'].$this->ld['can_not_empty'],
            'invalid_mobile_number' => $this->ld['mobile'].$this->ld['not_correct'],
            'cart_cancel' => $this->ld['cancel'],
            'cart_confirm' => $this->ld['confirm'],
            //"updating_please_wait" => $this->ld['updating_please_wait'],
            'orders_submitting_please_wait' => $this->ld['orders_submitting_please_wait'],
            'please_wait_consignee_information' => sprintf($this->ld['updating_please_wait'], $this->ld['consignee'].$this->ld['information']),
            'please_wait_shipping_method' => sprintf($this->ld['updating_please_wait'], $this->ld['shipping_method']),
            'please_wait_payment' => sprintf($this->ld['updating_please_wait'], $this->ld['payment']),
            'please_wait_invoice' => sprintf($this->ld['updating_please_wait'], $this->ld['invoice']),
            'please_wait_point' => sprintf($this->ld['updating_please_wait'], $this->ld['point']),
            'please_wait_coupon' => sprintf($this->ld['updating_please_wait'], $this->ld['coupon']),
            'support_value_or_not' => $this->ld['support_value_or_not'],
            'first_name_empty' => $this->ld['first_name'].$this->ld['can_not_empty'],
            'last_name_empty' => $this->ld['last_name'].$this->ld['can_not_empty'],
            'provice_empty' => $this->ld['province'].$this->ld['can_not_empty'],
            'city_empty' => $this->ld['city'].$this->ld['can_not_empty'],
        );
        if (isset($_SESSION['checkout']['payment']['code'])&&$_SESSION['checkout']['payment']['code'] == 'AuthorizeNet_AIM') {
            $foo = $this->aim_card_split($_SESSION['checkout']['payment']['card_num']);
            $this->set('aim_card', $foo);
        }
        $this->set('js_languages', $js_languages);
        //查询所有属性信息
        $this->Attribute->set_locale(LOCALE);
        $all_attr_list = array();
        $all_attr_info = $this->Attribute->find('all', array('fields' => array('Attribute.id,AttributeI18n.name'), array('conditions' => array('Attribute.status' => 1))));
        foreach ($all_attr_info as $v) {
            $all_attr_list[$v['Attribute']['id']] = $v['AttributeI18n']['name'];
        }
        $this->set('all_attr_list', $all_attr_list);
    }

    /**
     *现在购买.
     */
    public function buy_now()
    {
        /*
    		登录的用户将商品添加至购物车时，记录该用户喜欢该商品
    	*/
        if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
            $svcart_keng = 1;//判断该商品有没有商品属性
            $pro_id = $_REQUEST['id'];
            //查找对应商品信息
            $product_info = $this->ProductAttribute->find('first', array('conditions' => array('product_id' => $pro_id)));
            if (isset($product_info) && $product_info != '') {
                if (!(isset($_REQUEST['product_attr_buy_0']) || isset($_REQUEST['product_attr_basic_0']) || isset($_REQUEST['product_attr_buy_1']))) {
                    $svcart_keng = 2;
                    $this->layout = 'ajax';
                    $svcart = $svcart_keng;
                    $this->set('svcart', $svcart_keng);
                    $this->render('pro_cart');
                    die;
                }
            }
        }
        if (isset($_SESSION['User']) && isset($_POST['id'])) {
        		$UserLikeInfo=$this->UserLike->find('count',array('conditions'=>array('user_id'=>$_SESSION['User']['User']['id'],'type_id'=>$_POST['id'],'type'=>'P','action'=>'cart')));
        		if(empty($UserLikeInfo)){
        			$userLike['id'] = 0;
        			$userLike['user_id'] = $_SESSION['User']['User']['id'];//用户id
				$userLike['type_id'] = $_POST['id'];
				$userLike['type'] = 'P';
				$userLike['action'] = 'cart';
				$this->UserLike->save($userLike);
			}
        }
        $result = array();
        $result['is_refresh'] = 0;
        if ($this->RequestHandler->isPost()) {
            if (isset($this->configs['enable_guest_buy']) && $this->configs['enable_guest_buy'] == 0) {
                if (!isset($_SESSION['User']['User'])) {
                    $flag = 0;
                } else {
                    $flag = 1;
                }
            } else {
                $flag = 1;
            }
            if ($flag) {
                //加商品
                if ($_POST['type'] == 'product') {
                    if (isset($this->configs['cart_confirm_notice']) && $this->configs['cart_confirm_notice'] == 0 && !(isset($_POST['sure']))) {
                        $product_info = $this->Product->find('first', array('conditions' => array('Product.id' => $_POST['id'])));//商品属性待处理！
                        $product_info['quantity'] = $_POST['quantity'];
                        $i = 0;
                        while (true) {
                            if (!isset($_POST['attributes_'.$i])) {
                                break;
                            }
                            $product_attributes = $this->ProductAttribute->find('first', array('conditions' => array('ProductAttribute.id' => $_POST['attributes_'.$i])));
                            $attributes[$i] = $_POST['attributes_'.$i];
                            ++$i;
                        }
                        if (isset($attributes)) {
                            $this->set('attributes', $attributes);
                        }
                        if (isset($_POST['is_exchange'])) {
                            $this->set('is_exchange', $_POST['is_exchange']);   //是否从积分商场 购买
                        }
                        if ($this->is_promotion($product_info)) {
                            $product_info['is_promotion'] = 1;
                        }
                        $this->set('product_info', $product_info);
                        $result['type'] = 4;
                    } else {
                        //取得商品信息
                        $product_info = $this->Product->find('first', array('conditions' => array('Product.id' => $_POST['id'])));
                        $product_info['Product']['lease_price']=0;
                        $product_info['Product']['is_lease']=0;
                        $product_info['Product']['lease_day']=0;
                        if(isset($_POST["is_lease"])&&$_POST["is_lease"]==1){
                            //租赁
                            $product_info['Product']['is_lease']=1;
                            $pro_lease = $this->ProductLease->find('first', array('conditions' => array('ProductLease.product_code' => $product_info['Product']['code'],'ProductLease.status'=>1)));
                            $product_info['Product']['lease_price']=$pro_lease['ProductLease']['lease_price']*$_REQUEST['day_num'];
                            $product_info['Product']['lease_day']=$pro_lease['ProductLease']['unit']*$_REQUEST['day_num'];
                            $product_info['Product']['day_num']=$_REQUEST['day_num'];
                        }
                        $product_info['Product']['attributes_str'] = '';
                        $product_info['Product']['attributes_total'] = 0;
                        if ($product_info['Product']['option_type_id'] == '2') {
                            //商品销售属性
                            $sku_pro_info = $this->Product->find('first', array('conditions' => array('Product.code' => $_POST['code'])));
                            $product_info['Product']['code'] = $_POST['code'];
                            $product_type_ids = array();//统计购买属性
                            $product_type_ids = $this->Attribute->find('list', array('conditions' => array('Attribute.type' => 'buy')));
                            $sku_pro_attr = $this->ProductAttribute->find('all', array('conditions' => array('ProductAttribute.product_id' => $sku_pro_info['Product']['id'], 'ProductAttribute.locale' => LOCALE, 'ProductAttribute.attribute_id' => $product_type_ids), 'order' => 'ProductAttribute.id'));
                            $product_sku_attr_buy = array();
                            $i = 0;
                            while (true) {
                                if (!isset($_POST['product_sku_attr_buy_'.$i])) {
                                    break;
                                }
                                $product_sku_attr_buy[] = $_POST['product_sku_attr_buy_'.$i];
                                ++$i;
                            }
                            $sku_attr_info = array();
                            $this->Attribute->set_locale(LOCALE);
                            $sku_product_type_attribute = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $product_sku_attr_buy)));
                            foreach ($sku_product_type_attribute as $v) {
                                $sku_attr_info[$v['Attribute']['id']] = $v['AttributeI18n']['name'];
                            }
                            foreach ($sku_pro_attr as $k => $v) {
                                if (!isset($sku_attr_info[$v['ProductAttribute']['attribute_id']])) {
                                    continue;
                                }
                                $product_info['Product']['attributes'][$v['ProductAttribute']['id']] = $v['ProductAttribute']['attribute_value'];
                                $product_info['Product']['attributes_str'] .= $sku_attr_info[$v['ProductAttribute']['attribute_id']].':'.$v['ProductAttribute']['attribute_value'].' <br />';
                                $product_info['Product']['attributes_total'] += $v['ProductAttribute']['attribute_price'];
                            }
                        } elseif ($product_info['Product']['option_type_id'] == 0 && !empty($product_info['ProductAttribute'])) {
                            $this->Attribute->set_locale(LOCALE);
                            $public_attr_ids = $this->ProductTypeAttribute->getattrids(0);
                            $attr_cond['Attribute.type'] = 'buy';
                            $attr_cond['Attribute.id'] = $public_attr_ids;
                            $comm_pro_attr = $this->Attribute->find('all', array('conditions' => $attr_cond));
                            if (!empty($comm_pro_attr)) {
                                $comm_pro_attr_ids = array();
                                $comm_pro_attr_list = array();
                                foreach ($comm_pro_attr as $v) {
                                    $comm_pro_attr_ids[] = $v['Attribute']['id'];
                                    $comm_pro_attr_list[$v['Attribute']['id']] = $v['AttributeI18n']['name'];
                                }
                                foreach ($product_info['ProductAttribute'] as $v) {
                                    if (in_array($v['attribute_id'], $comm_pro_attr_ids)) {
                                        $product_info['Product']['attributes'][$v['id']] = $v['attribute_value'];
                                        $product_info['Product']['attributes_str'] .= $comm_pro_attr_list[$v['attribute_id']].':'.$v['attribute_value'].' <br />';
                                        $product_info['Product']['attributes_total'] += $v['attribute_price'];
                                    }
                                }
                            }
                        }
                        $product_info['Product']['shop_price'] = $this->Product->getOrderProductPrice($product_info['Product']['id'], $product_info['Product']['code']);
                        if (isset($this->configs['mlti_currency_module']) && $this->configs['mlti_currency_module'] == 1) {
                            //$product_info['Product']['shop_price'] = $product_info['ProductLocalePrice']['product_price'];
                        }
                        $i = 0;
                        while (true) {
                            if (!isset($_POST['product_attr_buy_'.$i])) {
                                break;
                            }
                            $product_attributes = $this->ProductAttribute->find('first', array('conditions' => array('ProductAttribute.id' => $_POST['product_attr_buy_'.$i])));
                            $product_type_attribute = $this->Attribute->find('first', array('conditions' => array('Attribute.id' => $product_attributes['Attribute']['attribute_id'])));
                            $product_info['Product']['attributes_str'] .= $product_type_attribute['AttributeI18n']['name'].':'.$product_attributes['ProductAttribute']['attribute_value'].' <br />';
                            $product_info['Product']['attributes'][$_POST['product_attr_buy_'.$i]] = $product_attributes['ProductAttribute']['attribute_value'];
                            $product_info['Product']['attributes_total'] += $product_attributes['ProductAttribute']['attribute_price'];
                            ++$i;
                        }
                        $i = 0;
                        while (true) {
                            if (!isset($_POST['product_attr_special_'.$i])) {
                                break;
                            }
                            if (isset($_POST['img_path_'.$i]) && !empty($_POST['img_path_'.$i])) {
                                $product_attributes = $this->ProductAttribute->find('first', array('conditions' => array('ProductAttribute.id' => $_POST['product_attr_special_'.$i])));
                                $product_type_attribute = $this->Attribute->find('first', array('conditions' => array('Attribute.id' => $product_attributes['ProductAttribute']['attribute_id'])));
                                $product_info['Product']['attributes_str'] .= $product_type_attribute['AttributeI18n']['name'].':'.$_POST['img_path_'.$i].' <br />';
                                $product_info['Product']['attributes'][$_POST['product_attr_special_'.$i]] = $_POST['img_path_'.$i];
                            }
                            ++$i;
                        }
                        if (isset($product_info['Product']['attributes'])  && sizeof($product_info['Product']['attributes']) > 0) {
                            $p_id = $_POST['id'].$_POST['code'];
                            $product_info['attributes'] = ' ';
                            $attr_num = 0;
                            foreach ($product_info['Product']['attributes'] as $k => $v) {
                                $product_info['attributes'] = $product_info['Product']['attributes_str'];
                                ++$attr_num;
                            }
                        } else {
                            $p_id = $_POST['id'];
                        }
                        if ($product_info['Product']['option_type_id'] == '1') {
                            //							$pkg_pro_info=$this->PackageProduct->find('all',array('conditions'=>array('PackageProduct.product_code'=>$_POST['code'])));
//							$pkg_ids=array();
//							foreach($pkg_pro_info as $pk=>$pv){
//								array_push($pkg_ids,$pv['PackageProduct']['package_product_id']);
//							}
                            $product_info['product_attrbute'] = isset($_POST['package_pro_attr_buy'])?$_POST['package_pro_attr_buy']:'';
                        }
                        //记录商品定制属性修改值
                        if (isset($_POST['cart_product_customize'][$_POST['code']]) && $_POST['cart_product_customize'][$_POST['code']] == 1 && isset($_POST['CartProductValue'][$_POST['code']]) && !empty($_POST['CartProductValue'][$_POST['code']])) {
                            $CartProductValueData = $_POST['CartProductValue'][$_POST['code']];
                            $svcart_product_value_data = array();
                            foreach ($CartProductValueData as $k => $v) {
                                $product_value_data['attribute_id'] = $k;
                                $product_value_data['attribute_value'] = $v;
                                $svcart_product_value_data[] = $product_value_data;
                                $p_id .= $k.':'.$v.';';
                            }
                            $p_id = substr($p_id, 0, strlen($p_id) - 1);
                            $p_id = md5($p_id);
                            $product_info['CartProductValue'] = $svcart_product_value_data;
                            if (isset($_POST['CartProductNote'][$_POST['code']]) && !empty($_POST['CartProductNote'][$_POST['code']])) {
                                $product_info['CartProductNote'] = $_POST['CartProductNote'][$_POST['code']];
                            }
                            //获取定制属性的价格;
                            if (isset($_POST['AccessoryPrice'][$_POST['code']]) && !empty($_POST['AccessoryPrice'][$_POST['code']])) {
                                $product_info['AccessoryPrice'] = $_POST['AccessoryPrice'][$_POST['code']];
                            }
                            //获取定制商品的用户模板id;
                            if (isset($_POST['user_style_id']) && !empty($_POST['user_style_id'])) {
                                $product_info['user_style_id'] = '';
                                if (is_array($_POST['user_style_id'])) {
                                    foreach ($_POST['user_style_id'] as $uk => $uv) {
                                        $product_info['user_style_id'] .= $uk.':'.(!empty($uv) ? $uv : 0).';';
                                    }
                                } else {
                                    $product_info['user_style_id'] = $_POST['user_style_id'];
                                }
                            }
                            //获取预约日期
                            if (isset($_POST['data']['Cart']['schedule_date']) && !empty($_POST['data']['Cart']['schedule_date'])) {
                                $product_info['schedule_date'] = $_POST['data']['Cart']['schedule_date'];
                            }
                            //获取预约时间
                            if (isset($_POST['data']['Cart']['schedule_time']) && !empty($_POST['data']['Cart']['schedule_time'])) {
                                $product_info['schedule_time'] = $_POST['data']['Cart']['schedule_time'];
                            }
                            //获取预约状态
                            if (isset($_POST['data']['Cart']['shipping_type']) && !empty($_POST['data']['Cart']['shipping_type'])) {
                                $product_info['shipping_type'] = $_POST['data']['Cart']['shipping_type'];
                            }
                        }
                        if ($this->is_promotion($product_info)) {
                            $product_info['is_promotion'] = 1;
                        }
                        if (isset($_POST['file_url']) && !empty($_POST['file_url'])) {
                            $_SESSION['product'][$p_id]['file_url'] = $_POST['file_url'];
                            $_SESSION['product'][$p_id]['file_show_flag'] = 1;
                        }
                        //套装商品图片数量
                        $params['id'] = $_POST['id'];
                        $package_product_list=$this->Product->get_product_package_list($params);
                        $product_info['package_product'] = $package_product_list;
                        $package_product_total=0;
				if(!empty($package_product_list)){
					foreach($package_product_list as $vv){
						$package_product_total+=$vv['Product']['shop_price'];
					}
				}
				$product_info['package_product_total'] = $package_product_total;
                        //添加到SVCART
                        $result = $this->addto_svcart($product_info, $_POST['quantity'],isset($_POST["is_lease"])?$_POST["is_lease"]:0);
                        $result['is_refresh'] = 0;
                        if (isset($_SESSION['product'][$p_id]['file_url']) && isset($_SESSION['product'][$p_id]['file_show_flag']) && $_SESSION['product'][$p_id]['file_show_flag'] == 1) {
                            $file_types = explode('/', $_SESSION['product'][$p_id]['file_url']);
                            $file_count = count($file_types) - 1;
                            $file_name_type = isset($file_types[$file_count]) ? $file_types[$file_count] : '';
                            $file_name = explode('.', $file_name_type);
                            $file_name_id = isset($file_name[0]) ? $file_name[0] : '';
                            $p_id = $p_id.'_'.$file_name_id;
                        }
                        if (isset($_SESSION['svcart']['products'][$p_id])) {
                            /* 获取老标记 */
                            $old_tag = isset($_SESSION['svcart']['cart_info']['all_virtual']) ? $_SESSION['svcart']['cart_info']['all_virtual'] : '';
                            $this->statistic_svcart();    //计算金额

                            if (!isset($_SESSION['svcart']['products'][$p_id]['save_cart'])) {
                                $this->save_cart($_SESSION['svcart']['products'][$p_id], $p_id);
                            }
                            /* 纯虚拟商品标记的改变需要刷新页面 */
                            if (sizeof($_SESSION['svcart']['products']) == 1 || $old_tag != $_SESSION['svcart']['cart_info']['all_virtual']) {
                                $result['is_refresh'] = 1;
                            }
                            $_SESSION['svcart']['products'][$p_id]['use_point'] = isset($_SESSION['svcart']['products'][$p_id]['use_point']) ? $_SESSION['svcart']['products'][$p_id]['use_point'] : 0;
                            //是否从积分商场 购买
                            if (isset($_POST['is_exchange'])&&isset($this->configs['use_point'])&&$this->configs['use_point']=='1'&&isset($this->configs['point-use-status'])&&in_array($this->configs['point-use-status'],array('0','2'))) {
                                if (isset($_SESSION['User']['User']['id'])) {
                                    $user_info = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
                                    if (!isset($_SESSION['svcart']['point']['fee']) || !isset($_SESSION['svcart']['point']['point'])) {
                                        $_SESSION['svcart']['point']['fee'] = 0;
                                        $_SESSION['svcart']['point']['point'] = 0;
                                    }
                                    //可以用的 积分
                                    $can_use_point = round($_SESSION['svcart']['products'][$p_id]['subtotal'] / $_SESSION['svcart']['products'][$p_id]['quantity'] / 100 * $this->configs['proportion_point']);
                                    if ($_SESSION['svcart']['products'][$p_id]['use_point'] > 0 && ($_SESSION['svcart']['products'][$p_id]['use_point'] + $_SESSION['svcart']['products'][$p_id]['Product']['point_fee']) > $can_use_point) {
                                        $buy_point = $can_use_point - $_SESSION['svcart']['products'][$p_id]['use_point'];
                                    } elseif (($_SESSION['svcart']['products'][$p_id]['Product']['point_fee'] + $_SESSION['svcart']['point']['point']) <= $can_use_point) {
                                        $buy_point = $_SESSION['svcart']['products'][$p_id]['Product']['point_fee'];
                                    } else {
                                        $buy_point = $can_use_point - $_SESSION['svcart']['point']['point'];
                                    }
//                                    $point_fee = round($buy_point / 100 * $this->configs['conversion_ratio_point']);
//                                    if ($user_info['User']['point'] >= $buy_point) {
//                                        $_SESSION['svcart']['point']['point'] += $buy_point;
//                                        $_SESSION['svcart']['point']['fee'] += $point_fee;
//                                        $_SESSION['svcart']['products'][$p_id]['use_point'] += $buy_point;
//                                    } else {
//                                        $_SESSION['svcart']['point']['point'] += $user_info['User']['point'];
//                                        $_SESSION['svcart']['point']['fee'] += round($user_info['User']['point'] / 100 * $this->configs['conversion_ratio_point']);
//                                        $_SESSION['svcart']['products'][$p_id]['use_point'] += $user_info['User']['point'];
//                                    }
                                } else {
                                    $_SESSION['svcart']['products'][$p_id]['is_exchange'] = 1;
                                }
                            }
                            $this->set('svcart', $_SESSION['svcart']);
                            $this->set('product_id', $_POST['id']);
                            $this->set('product_info', $_SESSION['svcart']['products'][$p_id]);
                            $save_cookie = $_SESSION['svcart'];
                            unset($save_cookie['products']);
                            unset($save_cookie['promotion']['products']);
                        } else {
                            $this->set('product_info', $product_info);
                        }
                        if (isset($_POST['page']) && $_POST['page'] == 'cart') {
                            $result['page'] = $_POST['page'];
                            $this->ajax_page_init();
                        }
                        $result['buy_id'] = $_POST['id'];
                        $result['buy_type'] = 'product';
                    }
                }
                //加包装
                if ($_POST['type'] == 'packaging') {
                    //取得包装信息
                    $product_info = $this->Packaging->findbyid($_POST['id']);//包装属性待处理！
                    //添加到SVCART
                    $result = $this->addto_svcart($product_info, $_POST['quantity']);
                    if (isset($_SESSION['svcart']['packagings'][$_POST['id']])) {
                        $this->statistic_svcart('packaging');
                        $this->set('svcart', $_SESSION['svcart']);
                        $this->set('packaging_id', $_POST['id']);
                        $this->set('product_info', $_SESSION['svcart']['packagings'][$_POST['id']]);
                    } else {
                        $this->set('product_info', $product_info);
                    }
                    if (isset($_POST['page']) && $_POST['page'] == 'cart') {
                        $result['page'] = $_POST['page'];
                        $this->ajax_page_init();
                    }
                    $result['buy_id'] = $_POST['id'];
                    $result['buy_type'] = 'packaging';
                }
                //加贺卡
                if ($_POST['type'] == 'card') {
                    //取得贺卡信息
                    $product_info = $this->Card->findbyid($_POST['id']);//贺卡属性待处理！
                    //添加到SVCART
                    $result = $this->addto_svcart($product_info, $_POST['quantity']);
                    if (isset($_SESSION['svcart']['cards'][$_POST['id']])) {
                        $this->statistic_svcart('card');
                        $this->set('svcart', $_SESSION['svcart']);
                        $this->set('card_id', $_POST['id']);
                        $this->set('product_info', $_SESSION['svcart']['cards'][$_POST['id']]);
                    } else {
                        $this->set('product_info', $product_info);
                    }
                    if (isset($_POST['page']) && $_POST['page'] == 'cart') {
                        $result['page'] = $_POST['page'];
                        $this->ajax_page_init();
                    }
                    $result['buy_id'] = $_POST['id'];
                    $result['buy_type'] = 'card';
                }
                if (isset($this->configs['enable_one_step_buy']) && $this->configs['enable_one_step_buy'] == 1) {
                    $js_languages = array('enable_one_step_buy' => '1');
                    $this->set('js_languages', $js_languages);
                }
            } else {
                $result['type'] = 5;
                $result['message'] = $this->ld['time_out_relogin'];
            }
            $this->set('type', $_POST['type']);
            $result['header_msg'] = '';
            if (isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  LOCALE != '' && isset($this->data['currencies'][$this->currencie][LOCALE])) {
                $sv_price_format = $this->data['currencies'][$this->currencie][LOCALE]['Currency']['format'];
            } else {
                $sv_price_format = $this->configs['price_format'];
            }
            if (isset($_SESSION['svcart']['products']) && sizeof($_SESSION['svcart']['products']) > 0) {
                $cart['quantity'] = 0;
                $cart['total'] = 0;
                foreach ($_SESSION['svcart']['products'] as $k => $v) {
                    $cart['quantity'] += $v['quantity'];
                    $cart['total'] += isset($v['subtotal']) ? $v['subtotal'] : 0;
                }
                $cart['sizeof'] = sizeof($_SESSION['svcart']['products']);
                if (isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  LOCALE != '' && isset($this->data['currencies'][$this->currencie][LOCALE])) {
                    $sv_head_total = isset($cart['total']) ? $cart['total'] : 0;
                    $sv_head_total = $sv_head_total * $this->data['currencies'][$this->currencie][LOCALE]['Currency']['rate'];
                } else {
                    $sv_head_total = isset($cart['total']) ? $cart['total'] : 0;
                }
                $result['header_msg'] = sprintf($this->ld['cart_total_product'], "<strong class='number'>".$cart['sizeof'].'</strong>', isset($cart['quantity']) ? "<strong class='number'>".
                        $cart['quantity'].'</strong>' : "<strong class='number'>0</strong>").'<strong>'.
                    sprintf($sv_price_format, $sv_head_total).'</strong>';
                $_SESSION['header_cart'] = $cart;
            } else {
                $_SESSION['header_cart'] = array('sizeof' => 0,'quantity' => 0,'total' => 0);
                $result['header_msg'] = sprintf($this->ld['cart_total_product'], "<strong class='number'>0</strong>", "<strong class='number'>0</strong>").'<strong>'.
                    sprintf($sv_price_format, 0).'</strong>';
            }
            $this->set('result', $result);
            if (isset($_GET['ajax']) && ($_GET['ajax'] == 2 || $_GET['ajax'] == 1)) {
                $svcart_keng = 1;
                $this->layout = 'ajax';
                $this->set('svcart', $svcart_keng);
                $this->render('cart_content');
                die;
            }
            $this->layout = 'ajax';
            if (!isset($_POST['is_ajax'])) {
                if ($result['type'] < 1 || $result['type'] == 2) {
                    //header("Location:".$this->server_host.$this->webroot."carts");
                    $this->redirect($this->server_host.$this->webroot.'carts');
                } else {
                    $this->redirect($this->server_host.$this->webroot.'carts');
                    $this->page_init();
                    $this->pageTitle = isset($result['message']) ? $result['message'] : ''.' - '.$this->configs['shop_title'];
                    $this->flash(isset($result['message']) ? $result['message'] : '', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'.$id);
                }
            }
        }
    }

    /**
     *取值.
     *
     *@param $product_info
     *@param $quantity
     *
     *@return $result
     */
    public function addto_svcart($product_info, $quantity,$is_lease=0)
    {
        if ($_POST['type'] == 'product') {
            $result['type'] = 2;
            //判断状态
            if ($product_info['Product']['status'] != 1 || $product_info['Product']['forsale'] != 1) {
                $result['type'] = 1;
                $result['message'] = $this->ld['products_add_cart_failed'];
                return $result;
            }
            if (isset($this->configs['enable_stock_manage']) && $this->configs['enable_stock_manage'] == 1) {
                if ($quantity > $product_info['Product']['quantity']) {
                    $result['type'] = 1;
                    $result['message'] = $this->ld['stock_is_not_enough'];
                    return $result;
                }
            }
            /*if(isset($this->configs['enable_out_of_stock_handle']) && sizeof($this->configs['enable_out_of_stock_handle'])>0) {
             $can_by_quantity = ($product_info['Product']['quantity']-$product_info['Product']['frozen_quantity']);
         }else {
             $can_by_quantity = $product_info['Product']['quantity'];
         }*/
            //$can_by_quantity = $product_info['Product']['quantity']-$product_info['Product']['frozen_quantity'];
            $can_by_quantity = $product_info['Product']['quantity'];
            //判断是否在购物车
            if (isset($product_info['Product']['attributes'])  && sizeof($product_info['Product']['attributes']) > 0) {
                $p_id = $product_info['Product']['id'].(isset($_POST['code']) ? $_POST['code'] : '');
            } else {
                $p_id = $product_info['Product']['id'];
                $product_info['Product']['attributes'] = array();
            }
            //记录商品套装规格
            if (isset($product_info['product_attrbute']) && !empty($product_info['product_attrbute'])) {
                $cart_info['product_attrbute'] = $product_info['product_attrbute'];
            }
            if (isset($_POST['cart_product_customize'][$_POST['code']]) && $_POST['cart_product_customize'][$_POST['code']] == 1 && isset($_POST['CartProductValue'][$_POST['code']]) && !empty($_POST['CartProductValue'][$_POST['code']])) {
                $CartProductValueData = $_POST['CartProductValue'][$_POST['code']];
                foreach ($CartProductValueData as $k => $v) {
                    $p_id .= $k.':'.$v.';';
                }
                $p_id = substr($p_id, 0, strlen($p_id) - 1);
                $p_id = md5($p_id);
            }
            $cart_file_url = array();
            if (!empty($_SESSION['svcart']['products'])) {
                foreach ($_SESSION['svcart']['products'] as $k => $v) {
                    if ($v['Product']['id'] == $product_info['Product']['id']) {
                        if (!empty($v['Product']['file_url'])) {
                            $cart_file_url[] = $v['Product']['file_url'];
                        }
                    }
                }
            }
            $is_chongfu_cart = 0;
            if (isset($_SESSION['product'][$p_id]['file_url']) && isset($_SESSION['product'][$p_id]['file_show_flag']) && $_SESSION['product'][$p_id]['file_show_flag'] == 1) {
                $file_url = $_SESSION['product'][$p_id]['file_url'];
                $file_types = explode('/', $file_url);
                $file_count = count($file_types) - 1;
                $file_name_type = isset($file_types[$file_count]) ? $file_types[$file_count] : '';
                $file_name = explode('.', $file_name_type);
                $file_name_id = isset($file_name[0]) ? $file_name[0] : '';
                $p_id = $p_id.'_'.$file_name_id;
                if (in_array($file_url, $cart_file_url) && $this->in_svcart($p_id, $product_info['Product']['attributes'])) {
                    $is_chongfu_cart = 1;
                }
            } else {
                if ($this->in_svcart($p_id, $product_info['Product']['attributes'])) {
                    $is_chongfu_cart = 1;
                }
                $file_url = '';
            }
            //$is_chongfu_cart=1;
            if ((isset($is_chongfu_cart) && $is_chongfu_cart == 1)) {
                $num = 0;
                foreach ($_SESSION['svcart']['products'] as $k => $v) {
                    if ($v['Product']['id'] == $product_info['Product']['id']) {
                        $num += $v['quantity'];
                    }
                }
                if ($_SESSION['svcart']['products'][$p_id]['quantity'] + $quantity > $product_info['Product']['max_buy']) {
                    $result['type'] = 1;
                    $result['message'] = $this->ld['expand_max_number'];
                    return $result;
                } elseif ($_SESSION['svcart']['products'][$p_id]['quantity'] + $quantity > $can_by_quantity) {
                    $result['type'] = 1;
                    $result['message'] = $this->ld['stock_is_not_enough'];
                    return $result;
                } else {
                    $_SESSION['svcart']['products'][$p_id]['quantity'] += $quantity;
                    $pnum_info = $this->Product->find('first', array('conditions' => array('Product.code' => $product_info['Product']['code'])));
//                  	if(isset($this->configs['enable_decrease_stock_time']) && $this->configs['enable_decrease_stock_time'] == 0){
//                        $product_quantity = $pnum_info['Product']['quantity'] - $quantity;
//                        $pnum_info['Product']['quantity'] = $product_quantity;
//                        $this->Product->save($pnum_info);
//                        $this->Product->updateskupro($pnum_info["Product"]["code"],$quantity,true);
//                    }
                    if (isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 2) {
                        //加入购物车时冻结商品库存
                        $pnum_info['Product']['frozen_quantity'] += $quantity;
                        $product_quantity = $pnum_info['Product']['quantity'] - $quantity;
                        $pnum_info['Product']['quantity'] = $product_quantity;
                        $this->Product->save($pnum_info);
                        $this->Product->updateskupro($pnum_info['Product']['code'], $quantity, true);
                    }
                    /*
                    $result['type']=0;
                    if(isset($this->configs['enable_out_of_stock_handle']) && sizeof($this->configs['enable_out_of_stock_handle'])>0) {
                        $product_info['Product']['frozen_quantity'] += $quantity;
                        $this->Product->save($product_info['Product']);
                    }
                    */
                    $cart_info['product_quantity'] = $_SESSION['svcart']['products'][$p_id]['quantity'];
                    if (isset($this->configs['volume_setting']) && $this->configs['volume_setting']) {
                        $product_volume = $this->ProductVolume->find('first', array('conditions' => array('ProductVolume.product_id' => $v['Product']['id'], 'ProductVolume.volume_number <=' => $_SESSION['svcart']['products'][$p_id]['quantity']), 'limit' => 1, 'order' => 'ProductVolume.volume_number desc'));
                        if (isset($product_volume['ProductVolume'])&&$_SESSION['svcart']['products'][$p_id]['Product']["is_lease"]==0) {
                            $cart_info['product_price'] = $product_volume['ProductVolume']['volume_price'];
                            if (isset($_SESSION['svcart']['products'][$p_id]['attributes_total'])) {
                                $cart_info['product_price'] += $_SESSION['svcart']['products'][$p_id]['attributes_total'];
                            }
                        }
                    }
                    //记录购物车商品属性值
                    if (isset($product_info['CartProductValue']) && !empty($product_info['CartProductValue'])) {
                        $_SESSION['svcart']['products'][$p_id]['CartProductValue'] = $product_info['CartProductValue'];
                    } else {
                        if (isset($_SESSION['svcart']['products'][$p_id]['CartProductValue'])) {
                            unset($_SESSION['svcart']['products'][$p_id]['CartProductValue']);//取消记录定制属性修改值
                        }
                    }
                    //记录购物车商品定制属性的价格
                    if (isset($product_info['AccessoryPrice']) && !empty($product_info['AccessoryPrice'])) {
                        $_SESSION['svcart']['products'][$p_id]['AccessoryPrice'] = $product_info['AccessoryPrice'];
                    } else {
                        if (isset($_SESSION['svcart']['products'][$p_id]['AccessoryPrice'])) {
                            unset($_SESSION['svcart']['products'][$p_id]['AccessoryPrice']);//取消记录定制属性的价格
                        }
                    }
                    if (isset($product_info['product_attrbute']) && !empty($product_info['product_attrbute'])) {
                        $_SESSION['svcart']['products'][$p_id]['attributes'] = $product_info['product_attrbute'];
                    } else {
                        if (isset($_SESSION['svcart']['products'][$p_id]['product_attrbute'])) {
                            unset($_SESSION['svcart']['products'][$p_id]['attributes']);//取消记录套装规格
                        }
                    }
                    //记录购物车商品用户模板
                    if (isset($product_info['user_style_id']) && !empty($product_info['user_style_id'])) {
                        $_SESSION['svcart']['products'][$p_id]['user_style_id'] = $product_info['user_style_id'];
                        $cart_info['user_style_id'] = $product_info['user_style_id'];
                    } else {
                        if (isset($_SESSION['svcart']['products'][$p_id]['user_style_id'])) {
                            unset($_SESSION['svcart']['products'][$p_id]['user_style_id']);//取消记录商品用户模板
                        }
                    }
                    //记录预约日期
                    if (isset($product_info['schedule_date']) && !empty($product_info['schedule_date'])) {
                        $_SESSION['svcart']['products'][$p_id]['schedule_date'] = $product_info['schedule_date'];
                        $cart_info['schedule_date'] = $product_info['schedule_date'];
                    } else {
                        if (isset($_SESSION['svcart']['products'][$p_id]['schedule_date'])) {
                            unset($_SESSION['svcart']['products'][$p_id]['schedule_date']);//取消记录商品用户模板
                        }
                    }
                    //记录预约时间
                    if (isset($product_info['schedule_time']) && !empty($product_info['schedule_time'])) {
                        $_SESSION['svcart']['products'][$p_id]['schedule_time'] = $product_info['schedule_time'];
                        $cart_info['schedule_time'] = $product_info['schedule_time'];
                    } else {
                        if (isset($_SESSION['svcart']['products'][$p_id]['schedule_time'])) {
                            unset($_SESSION['svcart']['products'][$p_id]['schedule_time']);//取消记录商品用户模板
                        }
                    }
                    //记录预约状态
                    if (isset($product_info['shipping_type']) && !empty($product_info['shipping_type'])) {
                        $_SESSION['svcart']['products'][$p_id]['shipping_type'] = $product_info['shipping_type'];
                        $cart_info['shipping_type'] = $product_info['shipping_type'];
                    } else {
                        if (isset($_SESSION['svcart']['products'][$p_id]['shipping_type'])) {
                            unset($_SESSION['svcart']['products'][$p_id]['shipping_type']);//取消记录商品用户模板
                        }
                    }
                    //租赁
                    if($is_lease!=$_SESSION['svcart']['products'][$p_id]['Product']['is_lease']){
        				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("不能同时购买普通商品与租赁商品");location.href="'.$this->base.'/carts/"</script>';
            			exit();
                    }
                    if (!empty($_SESSION['svcart']['products'][$p_id]['save_cart'])) {
                        if (isset($_SESSION['svcart']['products'][$p_id]['CartProductNote']) && !empty($_SESSION['svcart']['products'][$p_id]['CartProductNote'])) {
                            $cart_info['note'] = $_SESSION['svcart']['products'][$p_id]['CartProductNote'];
                        }
                        $cart_info['id'] = $_SESSION['svcart']['products'][$p_id]['save_cart'];
                        $this->Cart->save($cart_info);
                        $this->CartProductValue->deleteAll(array('cart_id' => $cart_info['id']));
                        $accessory_price = isset($_SESSION['svcart']['products'][$p_id]['AccessoryPrice']) ? $_SESSION['svcart']['products'][$p_id]['AccessoryPrice'] : array();
                        if (!empty($_SESSION['svcart']['products'][$p_id]['CartProductValue'])) {
                            foreach ($_SESSION['svcart']['products'][$p_id]['CartProductValue'] as $vv) {
                                $CartProductValueData = array(
                                    'cart_id' => $cart_info['id'],
                                    'attribute_id' => $vv['attribute_id'],
                                    'attribute_value' => $vv['attribute_value'],
                                    'attr_price' => isset($accessory_price[$vv['attribute_id']]) ? $accessory_price[$vv['attribute_id']] : 0,
                                );
                                $this->CartProductValue->saveAll($CartProductValueData);
                            }
                        }
                    }
                }
            } else {
                if ($quantity < $product_info['Product']['min_buy']) {
                    $result['type'] = 1;
                    $result['message'] = $this->ld['least_number'].$product_info['Product']['min_buy'];
                    return $result;
                } elseif ($quantity > $can_by_quantity) {
                    $result['type'] = 1;
                    $result['message'] = $this->ld['stock_is_not_enough'];
                    return $result;
                } else {
                    if (empty($product_info['attributes']) && !empty($product_info['product_attrbute'])) {
                        $product_info['attributes'] = $product_info['product_attrbute'];
                    }
                    //租赁
                    $lease_price=0;
                    $lease_day=0;
                    if($is_lease==1){
                    	$pro_lease = $this->ProductLease->find('first', array('conditions' => array('ProductLease.product_code' => $product_info['Product']['code'],'ProductLease.status'=>1)));
                        $lease_price=$pro_lease['ProductLease']['lease_price']*$product_info['Product']['day_num'];
                        $lease_day=$pro_lease['ProductLease']['unit']*$product_info['Product']['day_num'];
                    }
                    $_SESSION['svcart']['products'][$p_id] = array(
                        'Product' => array('id' => $product_info['Product']['id'],
                            'code' => $product_info['Product']['code'],
                            'lease_price'=>$lease_price,
                        	'lease_day'=>$lease_day,
                            'is_lease'=>$is_lease,
                            'weight' => $product_info['Product']['weight'],
                            'market_price' => $product_info['Product']['market_price'],
                            'shop_price' => $this->Product->getOrderProductPrice($product_info['Product']['id'], $product_info['Product']['code']),
                            'promotion_price' => $product_info['Product']['promotion_price'],
                            'promotion_start' => $product_info['Product']['promotion_start'],
                            'promotion_end' => $product_info['Product']['promotion_end'],
                            'promotion_status' => $product_info['Product']['promotion_status'],
                            //'promotion'=>isset($product_info['is_promotion'])?$product_info['is_promotion']:,
                            'product_rank_id' => $product_info['Product']['product_rank_id'],
                            'extension_code' => $product_info['Product']['extension_code'],
                            'frozen_quantity' => $product_info['Product']['frozen_quantity'],
                            'product_type_id' => $product_info['Product']['product_type_id'],
                            'brand_id' => $product_info['Product']['brand_id'],
                            'coupon_type_id' => $product_info['Product']['coupon_type_id'],
                            'point' => $product_info['Product']['point'],
                            'img_thumb' => $product_info['Product']['img_thumb'],
                            'buy_time' => date('Y-m-d H:i:s'),
                            'category_id' => $product_info['Product']['category_id'],
                            'point_fee' => $product_info['Product']['point_fee'],
                        ),
                        'attributes' => isset($product_info['attributes']) ? $product_info['attributes'] : '',
                        'attributes_total' => isset($product_info['Product']['attributes_total']) ? $product_info['Product']['attributes_total'] : '',
                        'file_url' => $file_url,
                        'ProductI18n' => array('name' => $product_info['ProductI18n']['name']),
                        'PackageProduct' => isset($product_info['package_product']) ? $product_info['package_product'] : '',
                        'PackageProduct_total' => isset($product_info['package_product_total']) ? $product_info['package_product_total'] : 0,
                    );
                    //记录购物车商品属性值
                    if (isset($product_info['CartProductValue']) && !empty($product_info['CartProductValue'])) {
                        $_SESSION['svcart']['products'][$p_id]['CartProductValue'] = $product_info['CartProductValue'];
                        if (isset($product_info['CartProductNote']) && !empty($product_info['CartProductNote'])) {
                            $_SESSION['svcart']['products'][$p_id]['CartProductNote'] = $product_info['CartProductNote'];
                        }
                    }
                    //记录购物车商品定制属性的价格
                    if (isset($product_info['AccessoryPrice']) && !empty($product_info['AccessoryPrice'])) {
                        $_SESSION['svcart']['products'][$p_id]['AccessoryPrice'] = $product_info['AccessoryPrice'];
                    }
                    //记录购物车商品用户模板
                    if (isset($product_info['user_style_id']) && !empty($product_info['user_style_id'])) {
                        $_SESSION['svcart']['products'][$p_id]['user_style_id'] = $product_info['user_style_id'];
                    }
                    //记录预约日期
                    if (isset($product_info['schedule_date']) && !empty($product_info['schedule_date'])) {
                        $_SESSION['svcart']['products'][$p_id]['schedule_date'] = $product_info['schedule_date'];
                    }
                    //记录预约时间
                    if (isset($product_info['schedule_time']) && !empty($product_info['schedule_time'])) {
                        $_SESSION['svcart']['products'][$p_id]['schedule_time'] = $product_info['schedule_time'];
                    }
                    //记录预约状态
                    if (isset($product_info['shipping_type']) && !empty($product_info['shipping_type'])) {
                        $_SESSION['svcart']['products'][$p_id]['shipping_type'] = $product_info['shipping_type'];
                    }
//					$sku_product=$this->SkuProduct->sale_sku_product(array($product_info['Product']['id']=>$product_info['Product']['code']));
//					if(isset($sku_product)&&sizeof($sku_product)>0) {
//						$_SESSION['svcart']['products'][$p_id]['sku_product']=$sku_product[$p_id]['sku_product'];
//						$_SESSION['svcart']['products'][$p_id]['parent_product_id']=$sku_product[$p_id]['parent_product_id'];
//					}
                    $_SESSION['svcart']['products'][$p_id]['quantity'] = $quantity;
                    $_SESSION['svcart']['products'][$p_id]['Product']['quantity'] = $quantity;
                    $categorys = $this->CategoryProductI18n->findbyid($product_info['Product']['category_id']);
                    if (isset($categorys['CategoryProductI18n']['name'])) {
                        $_SESSION['svcart']['products'][$p_id]['category_name'] = $categorys['CategoryProductI18n']['name'];
                        $_SESSION['svcart']['products'][$p_id]['category_id'] = $categorys['CategoryProductI18n']['id'];
                    }
                    $save_cookie = $_SESSION['svcart'];
                    unset($save_cookie['products']);
                    unset($save_cookie['promotion']['products']);
                    $pnum_info = $this->Product->find('first', array('conditions' => array('Product.code' => $product_info['Product']['code'])));
//                  	if(isset($this->configs['enable_decrease_stock_time']) && $this->configs['enable_decrease_stock_time'] == 0){
//                        $product_quantity = $pnum_info['Product']['quantity'] - $quantity;
//                        $pnum_info['Product']['quantity'] = $product_quantity;
//                        $this->Product->save($pnum_info);
//                        $this->Product->updateskupro($pnum_info["Product"]["code"],$quantity,true);
//                    }
                    if (isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 2) {
                        //加入购物车时冻结商品库存
                        $pnum_info['Product']['frozen_quantity'] += $quantity;
                        $product_quantity = $pnum_info['Product']['quantity'] - $quantity;
                        $pnum_info['Product']['quantity'] = $product_quantity;
                        $this->Product->save($pnum_info);
                        $this->Product->updateskupro($pnum_info['Product']['code'], $quantity, true);
                    }
                    /*
                    if(isset($this->configs['enable_out_of_stock_handle']) && sizeof($this->configs['enable_out_of_stock_handle'])>0) {
                        $product_info['Product']['frozen_quantity'] += $quantity;
                        $this->Product->save($product_info['Product']);
                    }*/
                    //pr($_SESSION['svcart']['products'][$p_id]);die;
                    $result['type'] = 0;
                }
            }
        }
        if ($_POST['type'] == 'packaging') {
            $result['type'] = 0;
            //判断状态
            if ($product_info['Packaging']['status'] != 1) {
                $result['type'] = 1;
                $result['message'] = $this->ld['package_add_cart_failed'];
                return $result;
            }
            $_SESSION['svcart']['packagings'][$product_info['Packaging']['id']] = $product_info;
            $_SESSION['svcart']['packagings'][$product_info['Packaging']['id']]['quantity'] = $quantity;
            $_SESSION['svcart']['packagings'][$product_info['Packaging']['id']]['Packaging']['quantity'] = $quantity;
            $save_cookie = $_SESSION['svcart'];
            unset($save_cookie['products']);
            unset($save_cookie['promotion']['products']);
            //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
            $result['type'] = 0;
        }
        if ($_POST['type'] == 'card') {
            $result['type'] = 0;
            //判断状态
            if ($product_info['Card']['status'] != 1) {
                $result['type'] = 1;
                $result['message'] = $this->ld['package_add_cart_failed'];
                return $result;
            }
            $_SESSION['svcart']['cards'][$product_info['Card']['id']] = $product_info;
            $_SESSION['svcart']['cards'][$product_info['Card']['id']]['quantity'] = $quantity;
            $_SESSION['svcart']['cards'][$product_info['Card']['id']]['Card']['quantity'] = $quantity;
            $save_cookie = $_SESSION['svcart'];
            unset($save_cookie['products']);
            unset($save_cookie['promotion']['products']);
            //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
            $result['type'] = 0;
        }
        return $result;
    }

    /**
     *删除.
     */
    public function remove()
    {
        $result = array();
        if ($this->RequestHandler->isPost()) {
            if ($_POST['type'] == 'product') {
                if (isset($_SESSION['svcart']['products'][$_POST['product_id']])) {
                    $this->set('product_info', $_SESSION['svcart']['products'][$_POST['product_id']]);
                    $this->set('product_info_id', $_POST['product_id']);
                    $result['type'] = 0;
                } else {
                    $result['type'] = 1;
                    $result['message'] = $this->ld['product_not_in_cart'];
                }
                $result['type'] = $_POST['type'];
            }
            if ($_POST['type'] == 'packaging') {
                if (isset($_SESSION['svcart']['packagings'][$_POST['product_id']])) {
                    $this->set('product_info', $_SESSION['svcart']['packagings'][$_POST['product_id']]);
                    $result['type'] = 0;
                } else {
                    $result['type'] = 1;
                    $result['message'] = $this->ld['product_not_in_cart'];
                }
                $result['type'] = $_POST['type'];
            }
            if ($_POST['type'] == 'card') {
                if (isset($_SESSION['svcart']['cards'][$_POST['product_id']])) {
                    $this->set('product_info', $_SESSION['svcart']['cards'][$_POST['product_id']]);
                    $result['type'] = 0;
                } else {
                    $result['type'] = 1;
                    $result['message'] = $this->ld['product_not_in_cart'];
                }
                $result['type'] = $_POST['type'];
            }
            if (isset($_POST['act']) && $_POST['act'] == 'checkout') {
                $result['act'] = $_POST['act'];
            }
        }
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    /**
     *行为删除.
     */
    public function act_remove($type = '', $pid = '')
    {
        if ($type != '' || $pid != '') {
            $is_ajax = 0;
            $_POST['type'] = $type;
            $_POST['product_id'] = $pid;
        } else {
            $is_ajax = 1;
        }
        $result = array();
        $result['is_refresh'] = 0;
        //	if($this->RequestHandler->isPost()){
        $result['no_product'] = 1;
        if ($_POST['type'] == 'product') {
            $_POST['product_id'] = explode(',', $_POST['product_id']);
            if (isset($_POST['product_id'][1])) {
                //批量删除
                foreach ($_POST['product_id'] as $k => $v) {
                    if (isset($_SESSION['svcart']['products'][$v])) {
                        $result['type'] = 0;
                        $this->set('product_info', $_SESSION['svcart']['products'][$v]);
                        $this->ajax_page_init();
                        //删除商品使用的积分
                        /*if(isset($this->configs['use_point'])&&$this->configs['use_point']=='1'&&isset($this->configs['point-use-status'])&&$this->configs['point-use-status']=='1'){
                            if (isset($_SESSION['svcart']['products'][$v]['use_point']) && isset($_SESSION['svcart']['point']['point']) && $_SESSION['svcart']['products'][$v]['use_point'] <= $_SESSION['svcart']['point']['point']) {
                                $_SESSION['svcart']['point']['point'] -= $_SESSION['svcart']['products'][$v]['use_point'];
                                $_SESSION['svcart']['point']['fee'] -= round($_SESSION['svcart']['products'][$v]['use_point'] / 100 * $this->configs['conversion_ratio_point']);
                            }
                        }*/
                        if (isset($_SESSION['svcart']['products'][$v]['save_cart'])) {
                            //	$cart_info = $this->Cart->findbyid($_SESSION['svcart']['products'][$v]['save_cart']);
                            $condition = array('Cart.id' => $_SESSION['svcart']['products'][$v]['save_cart']);
                            $this->Cart->deleteAll($condition);
                            $this->CartProductValue->deleteAll(array('CartProductValue.cart_id' => $_SESSION['svcart']['products'][$v]['save_cart']));
                            //$this->redirect('/carts/');
                        }
                        $product_info = $this->Product->findbycode($_SESSION['svcart']['products'][$v]['Product']['code']);
//		                if(isset($this->configs['enable_decrease_stock_time']) && $this->configs['enable_decrease_stock_time'] == 0){
//		                	$product_info['Product']['quantity'] += $_SESSION['svcart']['products'][$v]['quantity'];
//		                	$this->Product->save($product_info['Product']);
//		                	$this->Product->updateskupro($_SESSION['svcart']['products'][$v]['Product']['code'],$_SESSION['svcart']['products'][$v]['quantity'],false);
//		                }
                        // if(isset($this->configs['enable_out_of_stock_handle']) && sizeof($this->configs['enable_out_of_stock_handle'])>0) {
                        //    $product_info['Product']['frozen_quantity'] -= $_SESSION['svcart']['products'][$v]['quantity'];
                        //     $this->Product->save($product_info['Product']);
                        // }
                        if (isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 2) {
                            //加入购物车时冻结商品库存
                            $product_info['Product']['frozen_quantity'] -= $_SESSION['svcart']['products'][$v]['quantity'];
                            $product_quantity = $product_info['Product']['quantity'] + $_SESSION['svcart']['products'][$v]['quantity'];
                            $product_info['Product']['quantity'] = $product_quantity;
                            $this->Product->save($product_info);
                            $this->Product->updateskupro($_SESSION['svcart']['products'][$v]['Product']['code'], $_SESSION['svcart']['products'][$v]['quantity'], false);
                        }
                        if (count($_SESSION['svcart']['products']) > 1) {
                            unset($_SESSION['svcart']['products'][$v]);
                            $save_cookie = $_SESSION['svcart'];
                            unset($save_cookie['products']);
                            unset($save_cookie['promotion']['products']);
                            //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
                        } else {
                            unset($_SESSION['svcart']['products']);
                            $save_cookie = $_SESSION['svcart'];
                            unset($save_cookie['products']);
                            unset($save_cookie['promotion']['products']);
                            //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
                            $result['no_product'] = 0;
                        }
                    } elseif (isset($_SESSION['svcart']['bespoke'][$v])) {
                        $result['type'] = 0;
                        $this->set('product_info', $_SESSION['svcart']['bespoke'][$v]);
                        $this->ajax_page_init();
                        //删除商品使用的积分
                        /*if(isset($this->configs['use_point'])&&$this->configs['use_point']=='1'&&isset($this->configs['point-use-status'])&&$this->configs['point-use-status']=='1'){
                            if (isset($_SESSION['svcart']['bespoke'][$v]['use_point']) && isset($_SESSION['svcart']['point']['point']) && $_SESSION['svcart']['bespoke'][$v]['use_point'] <= $_SESSION['svcart']['point']['point']) {
                                $_SESSION['svcart']['point']['point'] -= $_SESSION['svcart']['bespoke'][$v]['use_point'];
                                $_SESSION['svcart']['point']['fee'] -= round($_SESSION['svcart']['bespoke'][$v]['use_point'] / 100 * $this->configs['conversion_ratio_point']);
                            }
                        }*/
                        if (isset($_SESSION['svcart']['bespoke'][$v]['save_cart'])) {
                            //	$cart_info = $this->Cart->findbyid($_SESSION['svcart']['products'][$v]['save_cart']);
                            $condition = array('Cart.id' => $_SESSION['svcart']['bespoke'][$v]['save_cart']);
                            $this->Cart->deleteAll($condition);
                            $this->CartProductValue->deleteAll(array('CartProductValue.cart_id' => $_SESSION['svcart']['bespoke'][$v]['save_cart']));
                            //$this->redirect('/carts/');
                        }
                        $product_info = $this->Product->findbycode($_SESSION['svcart']['bespoke'][$v]['Product']['code']);
//		                if(isset($this->configs['enable_decrease_stock_time']) && $this->configs['enable_decrease_stock_time'] == 0){
//		                	$product_info['Product']['quantity'] += $_SESSION['svcart']['bespoke'][$v]['quantity'];
//		                	$this->Product->save($product_info['Product']);
//		                	$this->Product->updateskupro($_SESSION['svcart']['bespoke'][$v]['Product']['code'],$_SESSION['svcart']['bespoke'][$v]['quantity'],false);
//		                }
                        if (isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 2) {
                            //加入购物车时冻结商品库存
                            $product_info['Product']['frozen_quantity'] -= $_SESSION['svcart']['bespoke'][$v]['quantity'];
                            $product_quantity = $product_info['Product']['quantity'] + $_SESSION['svcart']['bespoke'][$v]['quantity'];
                            $product_info['Product']['quantity'] = $product_quantity;
                            $this->Product->save($product_info);
                            $this->Product->updateskupro($_SESSION['svcart']['bespoke'][$v]['Product']['code'], $_SESSION['svcart']['bespoke'][$v]['quantity'], false);
                        }

                        // if(isset($this->configs['enable_out_of_stock_handle']) && sizeof($this->configs['enable_out_of_stock_handle'])>0) {
                        //    $product_info['Product']['frozen_quantity'] -= $_SESSION['svcart']['products'][$v]['quantity'];
                        //     $this->Product->save($product_info['Product']);
                        // }
                        if (count($_SESSION['svcart']['bespoke']) > 1) {
                            unset($_SESSION['svcart']['bespoke'][$v]);
                            $save_cookie = $_SESSION['svcart'];
                            unset($save_cookie['products']);
                            unset($save_cookie['promotion']['products']);
                            //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
                        } else {
                            unset($_SESSION['svcart']['bespoke']);
                            $save_cookie = $_SESSION['svcart'];
                            unset($save_cookie['products']);
                            unset($save_cookie['promotion']['products']);
                            //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
                            $result['no_product'] = 0;
                        }
                    } else {
                        $result['type'] = 1;
                        $result['message'] = $this->ld['product_not_in_cart'];
                    }
                }
            } else {
                //将商品从Seevia中删除
                if (isset($_SESSION['svcart']['products'][$_POST['product_id'][0]])) {
                    $result['type'] = 0;
                    $this->set('product_info', $_SESSION['svcart']['products'][$_POST['product_id'][0]]);
                    $this->ajax_page_init();
                    //删除商品使用的积分
                    /*if(isset($this->configs['use_point'])&&$this->configs['use_point']=='1'&&isset($this->configs['point-use-status'])&&$this->configs['point-use-status']=='1'){
                        if (isset($_SESSION['svcart']['products'][$_POST['product_id'][0]]['use_point']) && isset($_SESSION['svcart']['point']['point']) && $_SESSION['svcart']['products'][$_POST['product_id'][0]]['use_point'] <= $_SESSION['svcart']['point']['point']) {
                            $_SESSION['svcart']['point']['point'] -= $_SESSION['svcart']['products'][$_POST['product_id'][0]]['use_point'];
                            $_SESSION['svcart']['point']['fee'] -= round($_SESSION['svcart']['products'][$_POST['product_id'][0]]['use_point'] / 100 * $this->configs['conversion_ratio_point']);
                        }
                    }*/
                    if (isset($_SESSION['svcart']['products'][$_POST['product_id'][0]]['save_cart'])) {
                        //	$cart_info = $this->Cart->findbyid($_SESSION['svcart']['products'][$_POST['product_id'][0]]['save_cart']);
                        $condition = array('Cart.id' => $_SESSION['svcart']['products'][$_POST['product_id'][0]]['save_cart']);
                        $this->Cart->deleteAll($condition);
                        $this->CartProductValue->deleteAll(array('CartProductValue.cart_id' => $_SESSION['svcart']['products'][$_POST['product_id'][0]]['save_cart']));
                        //$this->redirect('/carts/');
                    }
                    $product_info = $this->Product->findbycode($_SESSION['svcart']['products'][$_POST['product_id'][0]]['Product']['code']);
//	                if(isset($this->configs['enable_decrease_stock_time']) && $this->configs['enable_decrease_stock_time'] == 0){
//	                	$product_info['Product']['quantity'] += $_SESSION['svcart']['products'][$_POST['product_id'][0]]['quantity'];
//	                	$this->Product->save($product_info['Product']);
//	                	$this->Product->updateskupro($_SESSION['svcart']['products'][$_POST['product_id'][0]]['Product']['code'],$_SESSION['svcart']['products'][$_POST['product_id'][0]]['quantity'],false);
//	                }
                    if (isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 2) {
                        //加入购物车时冻结商品库存
                        $product_info['Product']['frozen_quantity'] -= $_SESSION['svcart']['products'][$_POST['product_id'][0]]['quantity'];
                        $product_quantity = $product_info['Product']['quantity'] + $_SESSION['svcart']['products'][$_POST['product_id'][0]]['quantity'];
                        $product_info['Product']['quantity'] = $product_quantity;
                        $this->Product->save($product_info);
                        $this->Product->updateskupro($_SESSION['svcart']['products'][$_POST['product_id'][0]]['Product']['code'], $_SESSION['svcart']['products'][$_POST['product_id'][0]]['quantity'], false);
                    }
                    //$product_info = $this->Product->findbyid($_SESSION['svcart']['products'][$_POST['product_id'][0]]['Product']['id']);
                    // if(isset($this->configs['enable_out_of_stock_handle']) && sizeof($this->configs['enable_out_of_stock_handle'])>0) {
                    //    $product_info['Product']['frozen_quantity'] -= $_SESSION['svcart']['products'][$_POST['product_id'][0]]['quantity'];
                    //     $this->Product->save($product_info['Product']);
                    // }
                    if (count($_SESSION['svcart']['products']) > 1) {
                        unset($_SESSION['svcart']['products'][$_POST['product_id'][0]]);
                        $save_cookie = $_SESSION['svcart'];
                        unset($save_cookie['products']);
                        unset($save_cookie['promotion']['products']);
                        //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
                    } else {
                        unset($_SESSION['svcart']['products']);
                        $save_cookie = $_SESSION['svcart'];
                        unset($save_cookie['products']);
                        unset($save_cookie['promotion']['products']);
                        //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
                        $result['no_product'] = 0;
                    }
                } elseif (isset($_SESSION['svcart']['bespoke'][$_POST['product_id'][0]])) {
                    $result['type'] = 0;
                    $this->set('product_info', $_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]);
                    $this->ajax_page_init();
                    //删除商品使用的积分
                    /*if(isset($this->configs['use_point'])&&$this->configs['use_point']=='1'&&isset($this->configs['point-use-status'])&&$this->configs['point-use-status']=='1'){
                        if (isset($_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]['use_point']) && isset($_SESSION['svcart']['point']['point']) && $_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]['use_point'] <= $_SESSION['svcart']['point']['point']) {
                            $_SESSION['svcart']['point']['point'] -= $_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]['use_point'];
                            $_SESSION['svcart']['point']['fee'] -= round($_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]['use_point'] / 100 * $this->configs['conversion_ratio_point']);
                        }
                    }*/
                    if (isset($_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]['save_cart'])) {
                        //	$cart_info = $this->Cart->findbyid($_SESSION['svcart']['products'][$_POST['product_id'][0]]['save_cart']);
                        $condition = array('Cart.id' => $_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]['save_cart']);
                        $this->Cart->deleteAll($condition);
                        $this->CartProductValue->deleteAll(array('CartProductValue.cart_id' => $_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]['save_cart']));
                        //$this->redirect('/carts/');
                    }
                    $product_info = $this->Product->findbycode($_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]['Product']['code']);
//	                if(isset($this->configs['enable_decrease_stock_time']) && $this->configs['enable_decrease_stock_time'] == 0){
//	                	$product_info['Product']['quantity'] += $_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]['quantity'];
//	                	$this->Product->save($product_info['Product']);
//	                	$this->Product->updateskupro($_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]['Product']['code'],$_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]['quantity'],false);
//	                }
                    if (isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 2) {
                        //加入购物车时冻结商品库存
                        $product_info['Product']['frozen_quantity'] -= $_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]['quantity'];
                        $product_quantity = $product_info['Product']['quantity'] + $_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]['quantity'];
                        $product_info['Product']['quantity'] = $product_quantity;
                        $this->Product->save($product_info);
                        $this->Product->updateskupro($_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]['Product']['code'], $_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]['quantity'], false);
                    }
                    //$product_info = $this->Product->findbyid($_SESSION['svcart']['products'][$_POST['product_id'][0]]['Product']['id']);
                    // if(isset($this->configs['enable_out_of_stock_handle']) && sizeof($this->configs['enable_out_of_stock_handle'])>0) {
                    //    $product_info['Product']['frozen_quantity'] -= $_SESSION['svcart']['products'][$_POST['product_id'][0]]['quantity'];
                    //     $this->Product->save($product_info['Product']);
                    // }
                    if (count($_SESSION['svcart']['bespoke']) > 1) {
                        unset($_SESSION['svcart']['bespoke'][$_POST['product_id'][0]]);
                        $save_cookie = $_SESSION['svcart'];
                        unset($save_cookie['products']);
                        unset($save_cookie['promotion']['products']);
                        //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
                    } else {
                        unset($_SESSION['svcart']['bespoke']);
                        $save_cookie = $_SESSION['svcart'];
                        unset($save_cookie['products']);
                        unset($save_cookie['promotion']['products']);
                        //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
                        $result['no_product'] = 0;
                    }
                } else {
                    $result['type'] = 1;
                    $result['message'] = $this->ld['product_not_in_cart'];
                }
            }
            //Seevia里的信息
            $old_tag = isset($_SESSION['svcart']['cart_info']['all_virtual']) ? $_SESSION['svcart']['cart_info']['all_virtual'] : '';
            $this->statistic_svcart();
            /* 纯虚拟商品标记的改变需要刷新页面 */
            if ($old_tag != $_SESSION['svcart']['cart_info']['all_virtual']) {
                $result['is_refresh'] = 1;
            }
            $this->statistic_svcart('product');
            if (isset($_SESSION['svcart'])) {
                $this->set('svcart', $_SESSION['svcart']);
            }
        }
        if ($_POST['type'] == 'packaging') {
            //将包装从Seevia中删除
            if (isset($_SESSION['svcart']['packagings'][$_POST['product_id']])) {
                $result['type'] = 0;
                $this->set('product_info', $_SESSION['svcart']['packagings'][$_POST['product_id']]);
                $this->ajax_page_init();
                if (count($_SESSION['svcart']['packagings']) > 1) {
                    unset($_SESSION['svcart']['packagings'][$_POST['product_id']]);
                } else {
                    unset($_SESSION['svcart']['packagings']);
                }
                $save_cookie = $_SESSION['svcart'];
                unset($save_cookie['products']);
                unset($save_cookie['promotion']['products']);
                //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
            } else {
                $result['type'] = 1;
                $result['message'] = $this->ld['product_not_in_cart'];
            }
            //Seevia里的信息
            $this->statistic_svcart('packaging');
            if (isset($_SESSION['svcart'])) {
                $this->set('svcart', $_SESSION['svcart']);
            }
        }
        if ($_POST['type'] == 'card') {
            //将贺卡从Seevia中删除
            if (isset($_SESSION['svcart']['cards'][$_POST['product_id']])) {
                $result['type'] = 0;
                $this->set('product_info', $_SESSION['svcart']['cards'][$_POST['product_id']]);
                $this->ajax_page_init('card');
                if (count($_SESSION['svcart']['cards']) > 1) {
                    unset($_SESSION['svcart']['cards'][$_POST['product_id']]);
                } else {
                    unset($_SESSION['svcart']['cards']);
                }
                $save_cookie = $_SESSION['svcart'];
                unset($save_cookie['products']);
                unset($save_cookie['promotion']['products']);
                //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
            } else {
                $result['type'] = 1;
                $result['message'] = $this->ld['product_not_in_cart'];
            }
            //Seevia里的信息
            $this->statistic_svcart('card');
            if (isset($_SESSION['svcart'])) {
                $this->set('svcart', $_SESSION['svcart']);
            }
        }
        //	}
        /* 如果全部为虚拟商品删除包装和贺卡 */
        if (!empty($_SESSION['svcart']['cart_info']['all_virtual'])) {
            if (isset($_SESSION['svcart']['cards'])) {
                unset($_SESSION['svcart']['cards']);
            }
            if (isset($_SESSION['svcart']['packagings'])) {
                unset($_SESSION['svcart']['packagings']);
            }
        }
        if ($is_ajax == 0) {
            $this->page_init();
            $this->pageTitle = isset($result['message']) ? $result['message'] : $this->ld['deleted_success'].' - '.$this->configs['shop_title'];
            $this->redirect('/carts/');
            //$this->flash(isset($result['message'])?$result['message']:$this->ld['deleted_success'],isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"/carts/",2);
        }
        $this->set('type', $_POST['type']);
        $result['header_msg'] = '';
        if (isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  LOCALE != '' && isset($this->data['currencies'][$this->currencie][LOCALE])) {
            $sv_price_format = $this->data['currencies'][$this->currencie][LOCALE]['Currency']['format'];
        } else {
            $sv_price_format = $this->configs['price_format'];
        }
        if (isset($_SESSION['svcart']['products']) && sizeof($_SESSION['svcart']['products']) > 0) {
            $cart['quantity'] = 0;
            $cart['total'] = 0;
            foreach ($_SESSION['svcart']['products'] as $k => $v) {
                $cart['quantity'] += $v['quantity'];
                $cart['total'] += $v['subtotal'];
            }
            $cart['sizeof'] = sizeof($_SESSION['svcart']['products']);
            if (isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  LOCALE != '' && isset($this->data['currencies'][$this->currencie][LOCALE])) {
                $sv_head_total = isset($cart['total']) ? $cart['total'] : 0;
                $sv_head_total = $sv_head_total * $this->data['currencies'][$this->currencie][LOCALE]['Currency']['rate'];
            } else {
                $sv_head_total = isset($cart['total']) ? $cart['total'] : 0;
            }

            $result['header_msg'] = sprintf($this->ld['cart_total_product'], "<strong class='number'>".$cart['sizeof'].'</strong>', isset($cart['quantity']) ? "<strong class='number'>".
                    $cart['quantity'].'</strong>' : "<strong class='number'>0</strong>").'<strong>'.
                sprintf($sv_price_format, $sv_head_total).'</strong>';
        } else {
            $result['header_msg'] = sprintf($this->ld['cart_total_product'], "<strong class='number'>0</strong>", "<strong class='number'>0</strong>").'<strong>'.
                sprintf($sv_price_format, 0).'</strong>';
        }
        $this->set('svcart', $_SESSION['svcart']);
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    /**
     *行为变量.
     *
     *@param $id
     *@param $q
     *@param $type
     *
     *@return $result['type']
     */
    public function act_quantity_change($id = '', $q = '', $type = '')
    {
        if ($id != '' || $q != '' || $type != '') {
            $_POST['type'] = $type;
            $_POST['product_id'] = $id;
            $_POST['quantity'] = $q;
            $is_ajax = 0;
        } else {
            $is_ajax = 1;
        }
        $result = array();
        if (isset($_POST['action'])) {
            $result['action'] = $_POST['action'];
        }
        if ($this->RequestHandler->isPost()) {
            if ($_POST['type'] == 'product') {
                //将商品从Seevia中删除
                //		if($this->in_svcart($_POST['product_id'])){
                if (isset($_SESSION['svcart']['products'][$_POST['product_id']])) {
                    $product_info = $this->Product->findbyid($_SESSION['svcart']['products'][$_POST['product_id']]['Product']['id']);//商品属性待处理！
                    if (isset($this->configs['enable_out_of_stock_handle']) && sizeof($this->configs['enable_out_of_stock_handle']) > 0) {
                        	if (isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 2) {//加入购物车冻结库存
                        		$can_by_quantity = ($product_info['Product']['quantity'] - $product_info['Product']['frozen_quantity']);
                        	}else{
                        		$can_by_quantity = $product_info['Product']['quantity'];
                        	}
                    } else {
                        	$can_by_quantity = $product_info['Product']['quantity'];
                    }
                    //$_SESSION['svcart']['products'][$product_id]['quantity']
                    if ($_POST['quantity'] > $product_info['Product']['max_buy']) {
                        $result['type'] = 1;
                        $result['message'] = $this->ld['expand_max_number'];
                        $result['name'] = $product_info['ProductI18n']['name'];
                        $result['message2'] = $this->ld['cart_price_most'].sprintf($this->ld['home_pieces'], $product_info['Product']['max_buy']);
                    } elseif ($_POST['quantity'] < $product_info['Product']['min_buy']) {
                        $result['type'] = 1;
                        $result['message'] = $this->ld['least_number'].$product_info['Product']['min_buy'];
                        $result['name'] = $product_info['ProductI18n']['name'];
                        $result['message2'] = $this->ld['cart_price_least'].sprintf($this->ld['home_pieces'], $product_info['Product']['min_buy']);
                    } elseif ($_POST['quantity'] > $can_by_quantity) {
                        $result['type'] = 1;
                        $result['message'] = $this->ld['stock_is_not_enough'];

                        $result['name'] = $product_info['ProductI18n']['name'];
                        $result['message2'] = $this->ld['cart_price_most'].sprintf($this->ld['home_pieces'], $can_by_quantity);
                    } else {
                        $result['type'] = 0;
                        if ($_SESSION['svcart']['products'][$_POST['product_id']]['quantity'] < $_POST['quantity']) {
                            $act_type = 'is_add';
                            $change = $_POST['quantity'] - $_SESSION['svcart']['products'][$_POST['product_id']]['quantity'];
                        }
                        $change = $_POST['quantity'] - $_SESSION['svcart']['products'][$_POST['product_id']]['quantity'];

                        $_SESSION['svcart']['products'][$_POST['product_id']]['quantity'] = $_POST['quantity'];
                        if (isset($_SESSION['svcart']['products'][$_POST['product_id']]['save_cart'])) {
                            if (isset($this->configs['volume_setting']) && $this->configs['volume_setting']) {
                                $product_volume = $this->ProductVolume->find('first', array('conditions' => array('ProductVolume.product_id' => $product_info['Product']['id'], 'ProductVolume.volume_number <=' => $_POST['quantity']), 'limit' => 1, 'order' => 'ProductVolume.volume_number desc'));
                                if (isset($product_volume['ProductVolume'])&&$_SESSION['svcart']['products'][$_POST['product_id']]['Product']["is_lease"]==0) {
                                    $cart_info['product_price'] = $product_volume['ProductVolume']['volume_price'];
                                    if (isset($_SESSION['svcart']['products'][$_POST['product_id']]['attributes_total'])) {
                                        $cart_info['product_price'] += $_SESSION['svcart']['products'][$_POST['product_id']]['attributes_total'];
                                    }
                                }
                            }
                            //	$cart_info = $this->Cart->findbyid($_SESSION['svcart']['products'][$_POST['product_id']]['save_cart']);
                            $cart_info['product_quantity'] = $_POST['quantity'];
                            $cart_info['id'] = $_SESSION['svcart']['products'][$_POST['product_id']]['save_cart'];
                            $this->Cart->save($cart_info);
                        }
//						if(isset($this->configs['enable_out_of_stock_handle']) && sizeof($this->configs['enable_out_of_stock_handle'])>0){
//							$product_info['Product']['frozen_quantity'] += $change;
//							$this->Product->save($product_info['Product']);
//						}
                        if (isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 2) {
                            //加入购物车时冻结商品库存
                            $pnum_info = $this->Product->find('first', array('conditions' => array('Product.code' => $product_info['Product']['code'])));
                            $pnum_info['Product']['frozen_quantity'] += $change;
                            $product_quantity = $pnum_info['Product']['quantity'] - $change;
                            $pnum_info['Product']['quantity'] = $product_quantity;
                            $this->Product->save($pnum_info);
                            $this->Product->updateskupro($product_info['Product']['code'], $change, false);
                        }
                        $this->ajax_page_init();
                    }
                } else {
                    $result['type'] = 1;
                    $result['message'] = $this->ld['no_products_in_cart'];
                }
                //Seevia里的信息
                if (isset($_SESSION['svcart']['products']) && sizeof($_SESSION['svcart']['products']) > 0) {
                    $this->statistic_svcart();
                    if ($result['type'] == 0&&isset($this->configs['use_point'])&&$this->configs['use_point']=='1'&&isset($this->configs['point-use-status'])&&in_array($this->configs['point-use-status'],array('0','2'))) {
                        if (!isset($_SESSION['svcart']['point']['fee']) || !isset($_SESSION['svcart']['point']['point'])) {
                            $_SESSION['svcart']['point']['fee'] = 0;
                            $_SESSION['svcart']['point']['point'] = 0;
                        }
                        if (isset($_SESSION['User']['User']['id']) && isset($_SESSION['svcart']['products'][$_POST['product_id']]['use_point']) && $_SESSION['svcart']['products'][$_POST['product_id']]['use_point'] > 0 && isset($act_type) && $act_type == 'is_add') {
                            $user_info = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
                            //可以用的 积分
                            $can_use_point = round($_SESSION['svcart']['products'][$p_id]['subtotal'] / $_SESSION['svcart']['products'][$_POST['product_id']]['quantity'] / 100 * $this->configs['proportion_point']);
                            //	$can_use_point = round($_SESSION['svcart']['cart_info']['sum_subtotal']/100*$this->configs['proportion_point']);
                            $_SESSION['svcart']['products'][$_POST['product_id']]['use_point'] = isset($_SESSION['svcart']['products'][$_POST['product_id']]['use_point']) ? $_SESSION['svcart']['products'][$_POST['product_id']]['use_point'] : 0;
                            //1 该商品已使用的积分是否大于可用积分
                            //2 商品
                            //3
                            if ($_SESSION['svcart']['products'][$_POST['product_id']]['use_point'] > 0 && ($_SESSION['svcart']['products'][$_POST['product_id']]['use_point'] + $_SESSION['svcart']['products'][$_POST['product_id']]['Product']['point_fee']) > $can_use_point) {
                                $buy_point = $can_use_point - $_SESSION['svcart']['products'][$_POST['product_id']]['use_point'];
                            } elseif (($_SESSION['svcart']['products'][$_POST['product_id']]['Product']['point_fee'] + $_SESSION['svcart']['point']['point']) <= $can_use_point) {
                                $buy_point = $_SESSION['svcart']['products'][$_POST['product_id']]['Product']['point_fee'];
                            } else {
                                $buy_point = $can_use_point - $_SESSION['svcart']['point']['point'];
                            }
                            /*$point_fee = round($buy_point / 100 * $this->configs['conversion_ratio_point']);
                            if ($user_info['User']['point'] >= $buy_point) {
                                $_SESSION['svcart']['point']['point'] += $buy_point;
                                $_SESSION['svcart']['point']['fee'] += $point_fee;
                                $_SESSION['svcart']['products'][$_POST['product_id']]['use_point'] += $buy_point;
                            } else {
                                $_SESSION['svcart']['point']['point'] += $user_info['User']['point'];
                                $_SESSION['svcart']['point']['fee'] += round($user_info['User']['point'] / 100 * $this->configs['conversion_ratio_point']);
                                $_SESSION['svcart']['products'][$_POST['product_id']]['use_point'] += $user_info['User']['point'];
                            }*/
                        } else {
                            //		if(){
//                            if (isset($_SESSION['svcart']['products'][$_POST['product_id']]['use_point']) && $_SESSION['svcart']['products'][$_POST['product_id']]['use_point'] > 0 && $_SESSION['svcart']['products'][$_POST['product_id']]['use_point'] >= $_SESSION['svcart']['products'][$_POST['product_id']]['Product']['point_fee']) {
//                                $_SESSION['svcart']['products'][$_POST['product_id']]['use_point'] -= $_SESSION['svcart']['products'][$_POST['product_id']]['Product']['point_fee'];
//                                $_SESSION['svcart']['point']['point'] -= $_SESSION['svcart']['products'][$_POST['product_id']]['Product']['point_fee'];
//                                $_SESSION['svcart']['point']['fee'] -= round($_SESSION['svcart']['products'][$_POST['product_id']]['Product']['point_fee'] / 100 * $this->configs['conversion_ratio_point']);
//                            } else {
//                                $_SESSION['svcart']['products'][$_POST['product_id']]['use_point'] = 0;
//                                $_SESSION['svcart']['point']['point'] -= $_SESSION['svcart']['products'][$_POST['product_id']]['use_point'];
//                                $_SESSION['svcart']['point']['fee'] -= round($_SESSION['svcart']['products'][$_POST['product_id']]['use_point'] / 100 * $this->configs['conversion_ratio_point']);
//                            }
                            //		}
                        }
                    }
                    $this->set('svcart', $_SESSION['svcart']);
                } else {
                    unset($_SESSION['svcart']['products']);
                }
            }
            if ($_POST['type'] == 'packaging') {
                //将商品从Seevia中删除
                if ($this->in_svcart_packaging($_POST['product_id'])) {
                    $product_info = $this->Packaging->findbyid($_POST['product_id']);//商品属性待处理！
                    $result['type'] = 0;
                    $_SESSION['svcart']['packagings'][$_POST['product_id']]['quantity'] = $_POST['quantity'];
                    //$save_cookie = $_SESSION['svcart'];unset($save_cookie['products']);unset($save_cookie['promotion']['products']);$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);

                    $this->ajax_page_init();
                } else {
                    $result['type'] = 1;
                    $result['message'] = $this->ld['product_not_in_cart'];
                }
                //Seevia里的信息
                if (isset($_SESSION['svcart']['packagings']) && sizeof($_SESSION['svcart']['packagings']) > 0) {
                    $this->statistic_svcart('packaging');
                    $this->set('svcart', $_SESSION['svcart']);
                } else {
                    unset($_SESSION['svcart']['packagings']);
                }
            }
            if ($_POST['type'] == 'card') {
                //将商品从Seevia中删除
                if ($this->in_svcart_card($_POST['product_id'])) {
                    $product_info = $this->Card->findbyid($_POST['product_id']);//商品属性待处理！
                    $result['type'] = 0;
                    $_SESSION['svcart']['cards'][$_POST['product_id']]['quantity'] = $_POST['quantity'];
                    //$save_cookie = $_SESSION['svcart'];unset($save_cookie['products']);unset($save_cookie['promotion']['products']);$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
                    $this->ajax_page_init();
                } else {
                    $result['type'] = 1;
                    $result['message'] = $this->ld['product_not_in_cart'];
                }
                //Seevia里的信息
                if (isset($_SESSION['svcart']['cards']) && sizeof($_SESSION['svcart']['cards']) > 0) {
                    $this->statistic_svcart('card');
                    $this->set('svcart', $_SESSION['svcart']);
                } else {
                    unset($_SESSION['svcart']['cards']);
                }
            }
        }
        if (isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  $this->locale != '' && isset($this->data['currencies'][$this->currencie][$this->locale])) {
            $sv_price_format = $this->data['currencies'][$this->currencie][$this->locale]['Currency']['format'];
        } else {
            $sv_price_format = $this->data['configs']['price_format'];
        }
        $result['header_msg'] = '';
        if (isset($_SESSION['svcart']['products']) && sizeof($_SESSION['svcart']['products']) > 0) {
            $cart['quantity'] = 0;
            $cart['total'] = 0;
            $cart['le_total'] = 0;
            foreach ($_SESSION['svcart']['products'] as $k => $v) {
                //租赁
                if($v["Product"]["is_lease"]==1){
                    $cart['quantity'] += $v['quantity'];
                    $cart['le_total'] += $v["Product"]["lease_price"]*$v['quantity'];
                    continue;
                }
                $cart['quantity'] += $v['quantity'];
                $cart['total'] += $v['subtotal'];
            }
            $cart['sizeof'] = sizeof($_SESSION['svcart']['products']);
            if (isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  $this->locale != '' && isset($this->data['currencies'][$this->currencie][$this->locale])) {
                $sv_head_total = isset($cart['total']) ? $cart['total'] : 0;
                $sv_head_total = $sv_head_total * $this->data['currencies'][$this->currencie][$this->locale]['Currency']['rate'];
            } else {
                $sv_head_total = isset($cart['total']) ? $cart['total'] : 0;
            }
            $result['header_msg'] = sprintf($this->ld['cart_total_product'], "<strong class='number'>".$cart['sizeof'].'</strong>', isset($cart['quantity']) ? "<strong class='number'>".
                    $cart['quantity'].'</strong>' : "<strong class='number'>0</strong>").'<strong>'.
                sprintf($sv_price_format, $sv_head_total).'</strong>';
        } else {
            $result['header_msg'] = sprintf($this->ld['cart_total_product'], "<strong class='number'>0</strong>", "<strong class='number'>0</strong>").'<strong>'.
                sprintf($sv_price_format, 0).'</strong>';
        }
        $this->set('result', $result);
        if ($is_ajax == 1) {
            $this->layout = 'ajax';
        } else {
            return $result;
        }
    }

    /**
     *权限判断.
     *
     *@param $product_id
     *@param $attributes
     *
     *@return $TRUEorFALSE
     */
    public function in_svcart($product_id, $attributes = '')
    {
        if (empty($attributes)) {
            if (isset($_SESSION['svcart']['products'][$product_id]) && $_SESSION['svcart']['products'][$product_id]['quantity'] > 0) {
                if (isset($_SESSION['svcart']['products'][$product_id]['Product']['attributes']) && sizeof($_SESSION['svcart']['products'][$product_id]['Product']['attributes']) > 0) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            if (isset($_SESSION['svcart']['products'][$product_id]) && $_SESSION['svcart']['products'][$product_id]['quantity'] > 0) {
                if (isset($_SESSION['svcart']['products'][$product_id]['Product']['attributes']) && sizeof($_SESSION['svcart']['products'][$product_id]['Product']['attributes']) > 0 && is_array($attributes) && sizeof($attributes) > 0) {
                    $is_attributes = 0;
                    foreach ($attributes as $k => $v) {
                        if ($v == $_SESSION['svcart']['products'][$product_id]['Product']['attributes']) {
                            ++$is_attributes;
                        }
                    }
                    if ($is_attributes == sizeof($_SESSION['svcart']['products'][$product_id]['Product']['attributes'])) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
    }

    /**
     *权限判断.
     *
     *@param $product_id
     *
     *@return $(isset($_SESSION['svcart']['packagings'][$product_id]) && $_SESSION['svcart']['packagings'][$product_id]['quantity']>0)
     */
    public function in_svcart_packaging($product_id)
    {
        return (isset($_SESSION['svcart']['packagings'][$product_id]) && $_SESSION['svcart']['packagings'][$product_id]['quantity'] > 0);
    }

    /**
     *权限判断.
     *
     *@param $product_id
     *
     *@return $(isset($_SESSION['svcart']['cards'][$product_id]) && $_SESSION['svcart']['cards'][$product_id]['quantity']>0)
     */
    public function in_svcart_card($product_id)
    {
        return (isset($_SESSION['svcart']['cards'][$product_id]) && $_SESSION['svcart']['cards'][$product_id]['quantity'] > 0);
    }

    /**
     *权限判断.
     *
     *@param $product_info
     *
     *@return $($product_info['Product']['promotion_status'] == '1' && $product_info['Product']['promotion_start'] <= date("Y-m-d H:i:s") && $product_info['Product']['promotion_end'] >= date("Y-m-d H:i:s"))
     */
    public function is_promotion($product_info)
    {
        return ($product_info['Product']['promotion_status'] == '1' && $product_info['Product']['promotion_start'] <= date('Y-m-d H:i:s') && $product_info['Product']['promotion_end'] >= date('Y-m-d H:i:s'));
    }

    /**
     *统计
     *
     *@param $type
     */
    public function statistic_svcart($type = 'product')
    {
        //总现合计
        $_SESSION['svcart']['cart_info']['sum_subtotal'] = 0;
        //总原合计
        $_SESSION['svcart']['cart_info']['sum_market_subtotal'] = 0;
        $_SESSION['svcart']['cart_info']['sum_weight'] = 0;
        $_SESSION['svcart']['cart_info']['sum_quantity'] = 0;
        $_SESSION['svcart']['cart_info']['lease_quantity']=0;
        $_SESSION['svcart']['cart_info']['lease_subtotal']=0;
        if ($type == 'product') {
            if (isset($_SESSION['User']['User'])) {
                $user_rank_list = $this->UserRank->findrank();
            }
            $_SESSION['svcart']['cart_info']['product_subtotal'] = 0;
            //是否全为虚拟商品
            $_SESSION['svcart']['cart_info']['all_virtual'] = 1;
            if (isset($_SESSION['svcart']['products'])) {
                foreach ($_SESSION['svcart']['products'] as $i => $p) {
                    //租赁
                    if($p["Product"]["is_lease"]==1){
                        $_SESSION['svcart']['cart_info']['lease_quantity'] += $p['quantity'];
                        $_SESSION['svcart']['cart_info']['lease_subtotal'] += $p['Product']['lease_price'] * $p['quantity'];
                        continue;
                    }
                    if (isset($volume_price)) {unset($volume_price);}
                    $proInfo = $this->Product->find('first', array('conditions' => array('Product.id' => $p['Product']['id']), 'recursive' => -1));
                    $shop_price = $this->Product->getOrderProductPrice($p['Product']['id'], $p['Product']['code']);
                    $market_price = $proInfo['Product']['market_price'];
                    if (isset($this->configs['volume_setting']) && $this->configs['volume_setting']) {
                        $product_volume = $this->ProductVolume->find('first', array('conditions' => array('ProductVolume.product_id' => $p['Product']['id'], 'ProductVolume.volume_number <=' => $p['quantity']), 'limit' => 1, 'order' => 'ProductVolume.volume_number desc'));//none table
                        if (isset($product_volume['ProductVolume'])&&$_SESSION['svcart']['products'][$i]['Product']["is_lease"]==0) {
                            $volume_price = $product_volume['ProductVolume']['volume_price'];
                        }
                    }
                    $_SESSION['svcart']['cart_info']['sum_weight'] += $p['Product']['weight'] * $p['quantity'];
                    $_SESSION['svcart']['cart_info']['sum_quantity'] += $p['quantity'];
                    if (empty($p['Product']['extension_code'])) {
                        $_SESSION['svcart']['cart_info']['all_virtual'] = 0;
                    }
                    //获得是否有会员价
                    if (isset($_SESSION['User'])) {
                        $product_ranks = $this->ProductRank->find_rank_by_product_ids($p['Product']['id']);
                        if (isset($product_ranks[$p['Product']['id']]) && isset($_SESSION['User']['User']['rank']) && isset($product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']])) {
                            if (isset($product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank']) && $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank'] == 0) {
                                $p['Product']['product_rank_price'] = $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['product_price'];
                            } elseif (isset($user_rank_list[$_SESSION['User']['User']['rank']])) {
                                $p['Product']['product_rank_price'] = ($user_rank_list[$_SESSION['User']['User']['rank']]['UserRank']['discount'] / 100) * ($shop_price);
                            }
                        }
                        //	$p['Product']['product_rank_price'] = 	$this->Product->user_price($i,$p,$this);
                    } else {
                        //如果会员未登录 删除SESSION中残留的product_rank_price
                        if (isset($p['Product']['product_rank_price']) || isset($_SESSION['svcart']['products'][$i]['product_rank_price'])) {
                            unset($p['Product']['product_rank_price']);
                            unset($_SESSION['svcart']['products'][$i]['product_rank_price']);
                        }
                    }
                    //$svcart_products_list = $this->Product->find_svcart_products_list($p_ids);
                    //有会员价
                    if (isset($volume_price)) {
                        $promotion_price = $volume_price;
                        // $_SESSION['svcart']['cart_info']['sum_subtotal'] += $volume_price*$p['quantity'];
                    } elseif (isset($p['Product']['product_rank_price'])) {
                        $promotion_price = $p['Product']['product_rank_price'];
                        $_SESSION['svcart']['products'][$i]['product_rank_price'] = $promotion_price;
                    } else {
                        if ($this->is_promotion($p)) {
                            //该商品现价
                            $promotion_price = $p['Product']['promotion_price'];
                            $_SESSION['svcart']['products'][$i]['is_promotion'] = 1;
                        } else {
                            $promotion_price = $shop_price;
                            $_SESSION['svcart']['products'][$i]['is_promotion'] = 0;
                        }
                    }
                    $_SESSION['svcart']['cart_info']['product_subtotal'] += $promotion_price * $p['quantity'];
                    //当前价格合计   有无属性
                    if (isset($p['attributes_total']) && $p['attributes_total'] != '') {
                        $promotion_price += $p['attributes_total'];
                        $market_price += $p['attributes_total'];
                    }
                    //定制属性价格计算
                    if (isset($p['AccessoryPrice']) && !empty($p['AccessoryPrice'])) {
                        foreach ($p['AccessoryPrice'] as $ak => $av) {
                            $promotion_price += $av;
                            $market_price += $av;
                        }
                    }
                    // $_SESSION['svcart']['products'][$i]['Product']['shop_price']=$promotion_price;
                    $_SESSION['svcart']['products'][$i]['subtotal'] = $promotion_price * $p['quantity'];
                    $_SESSION['svcart']['products'][$i]['Product']['market_price'] = $market_price;
                    $_SESSION['svcart']['cart_info']['sum_subtotal'] += $promotion_price * $p['quantity'];
                    //该商品市场价合计
                    $_SESSION['svcart']['products'][$i]['market_subtotal'] = $market_price * $p['quantity'];
                    //全部商品市场价合计
                    $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $_SESSION['svcart']['products'][$i]['market_subtotal'];
                    //该商品差价
                    $_SESSION['svcart']['products'][$i]['discount_price'] = $market_price - $promotion_price;
                    //该商品折扣%?
                    if ($promotion_price > 0 && $p['Product']['market_price'] > 0) {
                        //		$_SESSION['svcart']['products'][$i]['discount_rate'] = round($promotion_price/$p['Product']['market_price'],2)*100 ;
                        $_SESSION['svcart']['products'][$i]['discount_rate'] = round($promotion_price / $p['Product']['market_price'], 2) * 100;
                    } else {
                        $_SESSION['svcart']['products'][$i]['discount_rate'] = 100;
                    }
                }
            }
            if (isset($_SESSION['svcart']['bespoke']) && !isset($_SESSION['svcart']['products'])) {
                //pr($_SESSION['svcart']['bespoke']);die;
                //只有预约量体定制商品时计算购物车价格
                foreach ($_SESSION['svcart']['bespoke'] as $i => $p) {
                    $proInfo = $this->Product->find('first', array('conditions' => array('Product.id' => $p['Product']['id']), 'recursive' => -1));
                    $shop_price = $this->Product->getOrderProductPrice($p['Product']['id'], $p['Product']['code']);
                    $market_price = $proInfo['Product']['market_price'];
                    if (isset($this->configs['volume_setting']) && $this->configs['volume_setting']) {
                        $product_volume = $this->ProductVolume->find('first', array('conditions' => array('ProductVolume.product_id' => $p['Product']['id'], 'ProductVolume.volume_number <=' => $p['quantity']), 'limit' => 1, 'order' => 'ProductVolume.volume_number desc'));//none table
                        if (isset($product_volume['ProductVolume'])&&$_SESSION['svcart']['products'][$i]['Product']["is_lease"]==0) {
                            $volume_price = $product_volume['ProductVolume']['volume_price'];
                        }
                    }
                    $_SESSION['svcart']['cart_info']['sum_weight'] += $p['Product']['weight'] * $p['quantity'];
                    $_SESSION['svcart']['cart_info']['sum_quantity'] += $p['quantity'];
                    if (empty($p['Product']['extension_code'])) {
                        $_SESSION['svcart']['cart_info']['all_virtual'] = 0;
                    }
                    //获得是否有会员价
                    if (isset($_SESSION['User'])) {
                        $product_ranks = $this->ProductRank->find_rank_by_product_ids($p['Product']['id']);
                        if (isset($product_ranks[$p['Product']['id']]) && isset($_SESSION['User']['User']['rank']) && isset($product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']])) {
                            if (isset($product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank']) && $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank'] == 0) {
                                $p['Product']['product_rank_price'] = $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['product_price'];
                            } elseif (isset($user_rank_list[$_SESSION['User']['User']['rank']])) {
                                $p['Product']['product_rank_price'] = ($user_rank_list[$_SESSION['User']['User']['rank']]['UserRank']['discount'] / 100) * ($shop_price);
                            }
                        }
                        //	$p['Product']['product_rank_price'] = 	$this->Product->user_price($i,$p,$this);
                    } else {
                        //如果会员未登录 删除SESSION中残留的product_rank_price
                        if (isset($p['Product']['product_rank_price']) || isset($_SESSION['svcart']['bespoke'][$i]['product_rank_price'])) {
                            unset($p['Product']['product_rank_price']);
                            unset($_SESSION['svcart']['bespoke'][$i]['product_rank_price']);
                        }
                    }
                    //$svcart_products_list = $this->Product->find_svcart_products_list($p_ids);
                    //有会员价
                    if (isset($volume_price)) {
                        $promotion_price = $volume_price;
                        // $_SESSION['svcart']['cart_info']['sum_subtotal'] += $volume_price*$p['quantity'];
                    } elseif (isset($p['Product']['product_rank_price'])) {
                        $promotion_price = $p['Product']['product_rank_price'];
                        $_SESSION['svcart']['bespoke'][$i]['product_rank_price'] = $promotion_price;
                    } else {
                        if ($this->is_promotion($p)) {
                            //该商品现价
                            $promotion_price = $p['Product']['promotion_price'];
                            $_SESSION['svcart']['bespoke'][$i]['is_promotion'] = 1;
                        } else {
                            $promotion_price = $shop_price;
                            $_SESSION['svcart']['bespoke'][$i]['is_promotion'] = 0;
                        }
                    }
                    $_SESSION['svcart']['cart_info']['product_subtotal'] += $promotion_price * $p['quantity'];
                    //当前价格合计   有无属性
                    if (isset($p['attributes_total']) && $p['attributes_total'] != '') {
                        $promotion_price += $p['attributes_total'];
                        $market_price += $p['attributes_total'];
                    }
                    //定制属性价格计算
                    if (isset($p['CartProductValue']) && !empty($p['CartProductValue'])) {
                        foreach ($p['CartProductValue'] as $ak => $av) {
                            $promotion_price += $av['attr_price'];
                            $market_price += $av['attr_price'];
                        }
                    }
                    // $_SESSION['svcart']['products'][$i]['Product']['shop_price']=$promotion_price;
                    $_SESSION['svcart']['bespoke'][$i]['subtotal'] = $promotion_price * $p['quantity'];

                    $_SESSION['svcart']['bespoke'][$i]['Product']['market_price'] = $market_price;
                    $_SESSION['svcart']['cart_info']['sum_subtotal'] += $promotion_price * $p['quantity'];
                    //该商品市场价合计
                    $_SESSION['svcart']['bespoke'][$i]['market_subtotal'] = $market_price * $p['quantity'];
                    //全部商品市场价合计
                    $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $_SESSION['svcart']['bespoke'][$i]['market_subtotal'];
                    //该商品差价
                    $_SESSION['svcart']['bespoke'][$i]['discount_price'] = $market_price - $promotion_price;
                    //该商品折扣%?
                    if ($promotion_price > 0 && $p['Product']['market_price'] > 0) {
                        //		$_SESSION['svcart']['bespoke'][$i]['discount_rate'] = round($promotion_price/$p['Product']['market_price'],2)*100 ;
                        $_SESSION['svcart']['bespoke'][$i]['discount_rate'] = round($promotion_price / $p['Product']['market_price'], 2) * 100;
                    } else {
                        $_SESSION['svcart']['bespoke'][$i]['discount_rate'] = 100;
                    }
                }
            }
            if (isset($_SESSION['svcart']['cart_info']['all_virtual'])) {
                $save_cookie = $_SESSION['svcart'];
                unset($save_cookie['products']);
                unset($save_cookie['promotion']['products']);
                //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
            }
            //判断是否有贺卡和包装
            if (isset($_SESSION['svcart']['packagings'])) {
                foreach ($_SESSION['svcart']['packagings'] as $i => $p) {
                    //包装小计
                    // 免费额度的 多货币
                    //$p['Packaging']['free_money'];
                    $packaging_curr_free_money = $p['Packaging']['free_money'];
                    if (isset($_SESSION['svcart']['cart_info']['product_subtotal']) && ($p['Packaging']['free_money'] == 0 || $_SESSION['svcart']['cart_info']['product_subtotal'] < $packaging_curr_free_money)) {
                        //	$_SESSION['svcart']['packagings'][$i]['subtotal'] = $p['Packaging']['fee']*$p['quantity'];
                        $_SESSION['svcart']['packagings'][$i]['subtotal'] = $p['Packaging']['fee'] * $p['quantity'];
                        //加上包装费的总价
                        $_SESSION['svcart']['cart_info']['sum_subtotal'] += $_SESSION['svcart']['packagings'][$i]['subtotal'];
                        $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $_SESSION['svcart']['packagings'][$i]['subtotal'];
                        unset($_SESSION['svcart']['packagings'][$i]['Packaging']['fee_free']);
                    } else {
                        $_SESSION['svcart']['packagings'][$i]['Packaging']['fee_free'] = 0;
                        $_SESSION['svcart']['packagings'][$i]['subtotal'] = 0;
                    }
                }
            }
            if (isset($_SESSION['svcart']['cards'])) {
                foreach ($_SESSION['svcart']['cards'] as $i => $p) {
                    $card_curr_free_money = $p['Card']['free_money'];
                    if (isset($_SESSION['svcart']['cart_info']['product_subtotal']) && ($p['Card']['free_money'] == 0 || $_SESSION['svcart']['cart_info']['product_subtotal'] < $card_curr_free_money)) {
                        //贺卡小计
                        //$_SESSION['svcart']['cards'][$i]['subtotal'] = $p['Card']['fee']*$p['quantity'];
                        $_SESSION['svcart']['cards'][$i]['subtotal'] = $p['Card']['fee'] * $p['quantity'];
                        //加上贺卡费的总价
                        $_SESSION['svcart']['cart_info']['sum_subtotal'] += $_SESSION['svcart']['cards'][$i]['subtotal'];
                        $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $_SESSION['svcart']['cards'][$i]['subtotal'];
                        unset($_SESSION['svcart']['cards'][$i]['Card']['fee_free']);
                    } else {
                        $_SESSION['svcart']['cards'][$i]['Card']['fee_free'] = 0;
                        $_SESSION['svcart']['cards'][$i]['subtotal'] = 0;
                    }
                }
            }
            if ($_SESSION['svcart']['cart_info']['sum_subtotal'] == 0 || $_SESSION['svcart']['cart_info']['sum_market_subtotal'] == 0) {
                $_SESSION['svcart']['cart_info']['discount_rate'] = 0;
            } else {
                $_SESSION['svcart']['cart_info']['discount_rate'] = round($_SESSION['svcart']['cart_info']['sum_subtotal'] / $_SESSION['svcart']['cart_info']['sum_market_subtotal'], 2) * 100;
            }
            $_SESSION['svcart']['cart_info']['discount_price'] = $_SESSION['svcart']['cart_info']['sum_market_subtotal'] - $_SESSION['svcart']['cart_info']['sum_subtotal'];
        }
        if ($type == 'packaging') {
            if (isset($_SESSION['svcart']['packagings'])) {
                foreach ($_SESSION['svcart']['packagings'] as $i => $p) {
                    $packaging_curr_free_money = $p['Packaging']['free_money'];
                    if (isset($_SESSION['svcart']['cart_info']['product_subtotal']) && ($p['Packaging']['free_money'] == 0 || $_SESSION['svcart']['cart_info']['product_subtotal'] < $packaging_curr_free_money)) {
                        //包装小计
                        $_SESSION['svcart']['packagings'][$i]['subtotal'] = $p['Packaging']['fee'] * $p['quantity'];
                        $_SESSION['svcart']['packagings'][$i]['is_promotion'] = 0;
                        //总现合计
                        $_SESSION['svcart']['cart_info']['sum_subtotal'] += $_SESSION['svcart']['packagings'][$i]['subtotal'];
                        //总原合计
                        $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $_SESSION['svcart']['packagings'][$i]['subtotal'];
                        unset($_SESSION['svcart']['packagings'][$i]['Packaging']['fee_free']);
                    } else {
                        $_SESSION['svcart']['packagings'][$i]['Packaging']['fee_free'] = 0;
                        $_SESSION['svcart']['packagings'][$i]['subtotal'] = 0;
                        $_SESSION['svcart']['packagings'][$i]['is_promotion'] = 0;
                    }
                }
            }
            //判断是否有商品和贺卡
            if (isset($_SESSION['svcart']['products'])) {
                if (isset($_SESSION['User']['User'])) {
                    $user_rank_list = $this->UserRank->findrank();
                }
                $_SESSION['svcart']['cart_info']['product_subtotal'] = 0;
                //是否全为虚拟商品
                $_SESSION['svcart']['cart_info']['all_virtual'] = 1;
                if (isset($_SESSION['svcart']['products'])) {
                    foreach ($_SESSION['svcart']['products'] as $i => $p) {
                        if(isset($volume_price)){unset($volume_price);}
                        if (isset($this->configs['volume_setting']) && $this->configs['volume_setting']) {
                            $product_volume = $this->ProductVolume->find(array('ProductVolume.product_id' => $p['Product']['id'], 'ProductVolume.volume_number ' => $p['quantity']));//none table
                            if (isset($product_volume['ProductVolume'])&&$_SESSION['svcart']['products'][$i]['Product']["is_lease"]==0) {
                                $volume_price = $product_volume['ProductVolume']['volume_price'];
                            }
                        }
                        $_SESSION['svcart']['cart_info']['sum_weight'] += $p['Product']['weight'] * $p['quantity'];
                        if (empty($p['Product']['extension_code'])) {
                            $_SESSION['svcart']['cart_info']['all_virtual'] = 0;
                        }
                        //获得是否有会员价
                        if (isset($_SESSION['User'])) {
                            $product_ranks = $this->ProductRank->find_rank_by_product_ids($p['Product']['id']);
                            if (isset($product_ranks[$p['Product']['id']]) && isset($_SESSION['User']['User']['rank']) && isset($product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']])) {
                                if (isset($product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank']) && $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank'] == 0) {
                                    $p['Product']['product_rank_price'] = $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['product_price'];
                                } elseif (isset($user_rank_list[$_SESSION['User']['User']['rank']])) {
                                    $p['Product']['product_rank_price'] = ($user_rank_list[$_SESSION['User']['User']['rank']]['UserRank']['discount'] / 100) * ($p['Product']['shop_price']);
                                }
                            }
                            //	$p['Product']['product_rank_price'] = 	$this->Product->user_price($i,$p,$this);
                        } else {
                            //如果会员未登录 删除SESSION中残留的product_rank_price
                            if (isset($p['Product']['product_rank_price']) || isset($_SESSION['svcart']['products'][$i]['product_rank_price'])) {
                                unset($p['Product']['product_rank_price']);
                                unset($_SESSION['svcart']['products'][$i]['product_rank_price']);
                            }
                        }
                        //有会员价
                        if (isset($volume_price)&&$_SESSION['svcart']['products'][$i]['Product']["is_lease"]==0) {
                            $promotion_price = $volume_price;
                            $_SESSION['svcart']['cart_info']['sum_subtotal'] += $volume_price * $p['quantity'];
                        } elseif (isset($p['Product']['product_rank_price'])) {
                            $promotion_price = $p['Product']['product_rank_price'];
                            $_SESSION['svcart']['products'][$i]['product_rank_price'] = $promotion_price;

                            $_SESSION['svcart']['cart_info']['sum_subtotal'] += $p['Product']['product_rank_price'] * $p['quantity'];
                        } else {
                            if ($this->is_promotion($p)) {
                                //该商品现价
                                $promotion_price = $p['Product']['promotion_price'];
                                //全部商品现价合计
                                //$_SESSION['svcart']['cart_info']['sum_subtotal'] += $p['Product']['promotion_price']*$p['quantity'];
                                $_SESSION['svcart']['cart_info']['sum_subtotal'] += $p['Product']['promotion_price'] * $p['quantity'];
                                $_SESSION['svcart']['products'][$i]['is_promotion'] = 1;
                            } else {
                                $promotion_price = $p['Product']['shop_price'];
                                $promotion_price = $p['Product']['shop_price'];
                                //总现合计
                                //	$_SESSION['svcart']['cart_info']['sum_subtotal'] += $p['Product']['shop_price']*$p['quantity'];
                                $_SESSION['svcart']['cart_info']['sum_subtotal'] += $p['Product']['shop_price'] * $p['quantity'];
                                $_SESSION['svcart']['products'][$i]['is_promotion'] = 0;
                            }
                        }
                        //该商品原价
                        //	$_SESSION['svcart']['products'][$i]['market_subtotal'] = $p['Product']['market_price']*$p['quantity'];
                        $_SESSION['svcart']['products'][$i]['market_subtotal'] = $p['Product']['market_price'] * $p['quantity'];
                        //该商品小计
                        $_SESSION['svcart']['products'][$i]['subtotal'] = $promotion_price * $p['quantity'];
                        $_SESSION['svcart']['cart_info']['product_subtotal'] += $promotion_price * $p['quantity'];
                        //全部商品原价合计
                        $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $_SESSION['svcart']['products'][$i]['market_subtotal'];
                        //该商品差价
                        //$_SESSION['svcart']['products'][$i]['discount_price'] = $p['Product']['market_price'] - $promotion_price;
                        $_SESSION['svcart']['products'][$i]['discount_price'] = $p['Product']['market_price'] - $promotion_price;
                        //该商品折扣%?
                        if ($promotion_price > 0) {
                            $_SESSION['svcart']['products'][$i]['discount_rate'] = round($promotion_price / $p['Product']['market_price'], 2) * 100;
                        } else {
                            $_SESSION['svcart']['products'][$i]['discount_rate'] = 100;
                        }
                    }
                }
            }
            if (isset($_SESSION['svcart']['cards'])) {
                foreach ($_SESSION['svcart']['cards'] as $i => $p) {
                    $card_curr_free_money = $p['Card']['free_money'];
                    if (isset($_SESSION['svcart']['cart_info']['product_subtotal']) && ($p['Card']['free_money'] == 0 || $_SESSION['svcart']['cart_info']['product_subtotal'] < $card_curr_free_money)) {
                        //	$_SESSION['svcart']['cards'][$i]['subtotal'] = $p['Card']['fee']*$p['quantity'];//小计
                        $_SESSION['svcart']['cards'][$i]['subtotal'] = $p['Card']['fee'] * $p['quantity'];
                        $_SESSION['svcart']['cart_info']['sum_subtotal'] += $_SESSION['svcart']['cards'][$i]['subtotal'];
                        $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $_SESSION['svcart']['cards'][$i]['subtotal'];
                        unset($_SESSION['svcart']['cards'][$i]['Card']['fee_free']);
                    } else {
                        $_SESSION['svcart']['cards'][$i]['subtotal'] = 0;//小计
                        $_SESSION['svcart']['cards'][$i]['Card']['fee_free'] = 0;
                    }
                }
            }
        }
        if ($type == 'card') {
            if (isset($_SESSION['svcart']['cards'])) {
                foreach ($_SESSION['svcart']['cards'] as $i => $p) {
                    $card_curr_free_money = $p['Card']['free_money'];
                    if (isset($_SESSION['svcart']['cart_info']['product_subtotal']) && ($p['Card']['free_money'] == 0 || $_SESSION['svcart']['cart_info']['product_subtotal'] < $card_curr_free_money)) {
                        //	$_SESSION['svcart']['cards'][$i]['subtotal'] = $p['Card']['fee']*$p['quantity'];//小计
                        $_SESSION['svcart']['cards'][$i]['subtotal'] = $p['Card']['fee'] * $p['quantity'];
                        $_SESSION['svcart']['cards'][$i]['is_promotion'] = 0;
                        //总现合计
                        $_SESSION['svcart']['cart_info']['sum_subtotal'] += $_SESSION['svcart']['cards'][$i]['subtotal'];
                        //总原合计
                        $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $_SESSION['svcart']['cards'][$i]['subtotal'];
                        unset($_SESSION['svcart']['cards'][$i]['Card']['fee_free']);
                    } else {
                        $_SESSION['svcart']['cards'][$i]['subtotal'] = 0;//小计
                        $_SESSION['svcart']['cards'][$i]['is_promotion'] = 0;
                        $_SESSION['svcart']['cards'][$i]['Card']['fee_free'] = 0;
                    }
                }
            }
            if (isset($_SESSION['svcart']['products'])) {
                if (isset($_SESSION['User']['User'])) {
                    $user_rank_list = $this->UserRank->findrank();
                }
                $_SESSION['svcart']['cart_info']['product_subtotal'] = 0;
                //是否全为虚拟商品
                $_SESSION['svcart']['cart_info']['all_virtual'] = 1;
                if (isset($_SESSION['svcart']['products'])) {
                    foreach ($_SESSION['svcart']['products'] as $i => $p) {
                        if(isset($volume_price)){unset($volume_price);}
                        if (isset($this->configs['volume_setting']) && $this->configs['volume_setting'] == 1) {
                            $product_volume = $this->ProductVolume->find(array('ProductVolume.product_id' => $p['Product']['id'], 'ProductVolume.volume_number ' => $p['quantity']));
                            if (isset($product_volume['ProductVolume'])&&$_SESSION['svcart']['products'][$i]['Product']["is_lease"]==0) {
                                $volume_price = $product_volume['ProductVolume']['volume_price'];
                            }
                        }
                        $_SESSION['svcart']['cart_info']['sum_weight'] += $p['Product']['weight'] * $p['quantity'];
                        if (empty($p['Product']['extension_code'])) {
                            $_SESSION['svcart']['cart_info']['all_virtual'] = 0;
                        }
                        //获得是否有会员价
                        if (isset($_SESSION['User'])) {
                            $product_ranks = $this->ProductRank->find_rank_by_product_ids($p['Product']['id']);
                            if (isset($product_ranks[$p['Product']['id']]) && isset($_SESSION['User']['User']['rank']) && isset($product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']])) {
                                if (isset($product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank']) && $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank'] == 0) {
                                    //$p['Product']['product_rank_price']= $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['product_price'];
                                    $p['Product']['product_rank_price'] = $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['product_price'];
                                } elseif (isset($user_rank_list[$_SESSION['User']['User']['rank']])) {
                                    //$p['Product']['product_rank_price']=($user_rank_list[$_SESSION['User']['User']['rank']]['UserRank']['discount']/100)*($p['Product']['shop_price']);
                                    $p['Product']['product_rank_price'] = ($user_rank_list[$_SESSION['User']['User']['rank']]['UserRank']['discount'] / 100) * ($p['Product']['shop_price']);
                                }
                            }
                            //	$p['Product']['product_rank_price'] = 	$this->Product->user_price($i,$p,$this);
                        } else {
                            //如果会员未登录 删除SESSION中残留的product_rank_price
                            if (isset($p['Product']['product_rank_price']) || isset($_SESSION['svcart']['products'][$i]['product_rank_price'])) {
                                unset($p['Product']['product_rank_price']);
                                unset($_SESSION['svcart']['products'][$i]['product_rank_price']);
                            }
                        }
                        //有会员价
                        if (isset($volume_price)&&$_SESSION['svcart']['products'][$i]['Product']["is_lease"]==0) {
                            $promotion_price = $volume_price;
                            $_SESSION['svcart']['cart_info']['sum_subtotal'] += $volume_price * $p['quantity'];
                        } elseif (isset($p['Product']['product_rank_price'])) {
                            $promotion_price = $p['Product']['product_rank_price'];
                            $_SESSION['svcart']['products'][$i]['product_rank_price'] = $promotion_price;
                            $_SESSION['svcart']['cart_info']['sum_subtotal'] += $p['Product']['product_rank_price'] * $p['quantity'];
                        } else {
                            if ($this->is_promotion($p)) {
                                //该商品现价
                                $promotion_price = $p['Product']['promotion_price'];
                                //全部商品现价合计
                                $_SESSION['svcart']['cart_info']['sum_subtotal'] += $p['Product']['promotion_price'] * $p['quantity'];
                                $_SESSION['svcart']['products'][$i]['is_promotion'] = 1;
                            } else {
                                $promotion_price = $p['Product']['shop_price'];
                                //总现合计
                                //	$_SESSION['svcart']['cart_info']['sum_subtotal'] += $p['Product']['shop_price']*$p['quantity'];
                                $_SESSION['svcart']['cart_info']['sum_subtotal'] += $p['Product']['shop_price'] * $p['quantity'];
                                $_SESSION['svcart']['products'][$i]['is_promotion'] = 0;
                            }
                        }
                        //该商品原价
                        $_SESSION['svcart']['products'][$i]['market_subtotal'] = $p['Product']['market_price'] * $p['quantity'];
                        //该商品小计
                        $_SESSION['svcart']['products'][$i]['subtotal'] = $promotion_price * $p['quantity'];
                        $_SESSION['svcart']['cart_info']['product_subtotal'] += $promotion_price * $p['quantity'];
                        //全部商品原价合计
                        $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $_SESSION['svcart']['products'][$i]['market_subtotal'];
                        //该商品差价
                        //$_SESSION['svcart']['products'][$i]['discount_price'] = $p['Product']['market_price'] - $promotion_price;
                        $_SESSION['svcart']['products'][$i]['discount_price'] = $p['Product']['market_price'] - $promotion_price;
                        //该商品折扣%?
                        if ($promotion_price > 0) {
                            //		$_SESSION['svcart']['products'][$i]['discount_rate'] = round($promotion_price/$p['Product']['market_price'],2)*100 ;
                            $_SESSION['svcart']['products'][$i]['discount_rate'] = round($promotion_price / $p['Product']['market_price'], 2) * 100;
                        } else {
                            $_SESSION['svcart']['products'][$i]['discount_rate'] = 100;
                        }
                    }
                }
            }
            if (isset($_SESSION['svcart']['packagings'])) {
                foreach ($_SESSION['svcart']['packagings'] as $i => $p) {
                    $packaging_curr_free_money = $p['Packaging']['free_money'];
                    if (isset($_SESSION['svcart']['cart_info']['product_subtotal']) && ($p['Packaging']['free_money'] > 0 || $_SESSION['svcart']['cart_info']['product_subtotal'] < $packaging_curr_free_money)) {
                        $_SESSION['svcart']['packagings'][$i]['subtotal'] = $p['Packaging']['fee'] * $p['quantity'];
                        $_SESSION['svcart']['cart_info']['sum_subtotal'] += $_SESSION['svcart']['packagings'][$i]['subtotal'];
                        $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $_SESSION['svcart']['packagings'][$i]['subtotal'];
                        unset($_SESSION['svcart']['packagings'][$i]['Packaging']['fee_free']);
                    } else {
                        $_SESSION['svcart']['packagings'][$i]['subtotal'] = 0;
                        $_SESSION['svcart']['packagings'][$i]['Packaging']['fee_free'] = 0;
                    }
                }
            }
        }
        //节省
        $_SESSION['svcart']['cart_info']['discount_price'] = $_SESSION['svcart']['cart_info']['sum_market_subtotal'] - $_SESSION['svcart']['cart_info']['sum_subtotal'];
        $save_cookie = $_SESSION['svcart'];
        unset($save_cookie['products']);
        unset($save_cookie['promotion']['products']);
        //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
        $this->auto_confirm_promotion();
    }

    public function statistic_checkout($type = 'product')
    {
        //总现合计
        $_SESSION['checkout']['cart_info']['sum_subtotal'] = 0;
        //总原合计
        $_SESSION['checkout']['cart_info']['sum_market_subtotal'] = 0;
        $_SESSION['checkout']['cart_info']['sum_weight'] = 0;
        $_SESSION['checkout']['cart_info']['sum_quantity'] = 0;
        $_SESSION['checkout']['cart_info']['lease_quantity'] = 0;
        $_SESSION['checkout']['cart_info']['lease_subtotal'] = 0;
        $_SESSION['checkout']['cart_info']['lease_total'] = 0;
        $_SESSION['checkout']['cart_info']['insure_fee'] = 0;
        if ($type == 'product') {
            if (isset($_SESSION['User']['User'])) {
                $user_rank_list = $this->UserRank->findrank();
            }
            $_SESSION['checkout']['cart_info']['product_subtotal'] = 0;
            //是否全为虚拟商品
            $_SESSION['checkout']['cart_info']['all_virtual'] = 1;
            if (isset($_SESSION['checkout']['products'])) {
                foreach ($_SESSION['checkout']['products'] as $i => $p) {
                    //租赁
                    if($p["Product"]["is_lease"]==1){
	                        $_SESSION['checkout']['cart_info']['lease_quantity'] += $p['quantity'];
	                        $_SESSION['checkout']['cart_info']['lease_subtotal'] += $p['Product']['lease_price'] * $p['quantity'];
	                        $_SESSION['checkout']['cart_info']['all_virtual'] = 0;
	                        $_SESSION['checkout']['cart_info']['lease_total']+=$p['Product']['shop_price'] * $p['quantity'];
	                        continue;
                    }
                    if(isset($volume_price)){unset($volume_price);}
                    $proInfo = $this->Product->find('first', array('conditions' => array('Product.id' => $p['Product']['id']), 'recursive' => -1));
                    $shop_price = $this->Product->getOrderProductPrice($p['Product']['id'], $p['Product']['code']);
                    $market_price = $proInfo['Product']['market_price'];
                    if (isset($this->configs['volume_setting']) && $this->configs['volume_setting'] == 1) {
                        $product_volume = $this->ProductVolume->find('first', array('conditions' => array('ProductVolume.product_id' => $p['Product']['id'], 'ProductVolume.volume_number <=' => $p['quantity']), 'limit' => 1, 'order' => 'ProductVolume.volume_number desc'));//none table
                        if (isset($product_volume['ProductVolume'])&&$_SESSION['svcart']['products'][$i]['Product']["is_lease"]==0) {
                            $volume_price = $product_volume['ProductVolume']['volume_price'];
                        }
                    }
                    $_SESSION['checkout']['cart_info']['sum_weight'] += $p['Product']['weight'] * $p['quantity'];
                    $_SESSION['checkout']['cart_info']['sum_quantity'] += $p['quantity'];
                    if (empty($p['Product']['extension_code'])) {
                        $_SESSION['checkout']['cart_info']['all_virtual'] = 0;
                    }
                    //获得是否有会员价
                    if (isset($_SESSION['User'])) {
                        $product_ranks = $this->ProductRank->find_rank_by_product_ids($p['Product']['id']);
                        if (isset($product_ranks[$p['Product']['id']]) && isset($_SESSION['User']['User']['rank']) && isset($product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']])) {
                            if (isset($product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank']) && $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank'] == 0) {
                                $p['Product']['product_rank_price'] = $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['product_price'];
                            } elseif (isset($user_rank_list[$_SESSION['User']['User']['rank']])) {
                                $p['Product']['product_rank_price'] = ($user_rank_list[$_SESSION['User']['User']['rank']]['UserRank']['discount'] / 100) * ($shop_price);
                            }
                        }
                        //	$p['Product']['product_rank_price'] = 	$this->Product->user_price($i,$p,$this);
                    } else {
                        //如果会员未登录 删除SESSION中残留的product_rank_price
                        if (isset($p['Product']['product_rank_price']) || isset($_SESSION['checkout']['products'][$i]['product_rank_price'])) {
                            unset($p['Product']['product_rank_price']);
                            unset($_SESSION['checkout']['products'][$i]['product_rank_price']);
                        }
                    }
                    //$checkout_products_list = $this->Product->find_checkout_products_list($p_ids);
                    //有会员价
                    if (isset($volume_price)) {
                        $promotion_price = $volume_price;
                        // $_SESSION['checkout']['cart_info']['sum_subtotal'] += $volume_price*$p['quantity'];
                    } elseif (isset($p['Product']['product_rank_price'])) {
                        $promotion_price = $p['Product']['product_rank_price'];
                        $_SESSION['checkout']['products'][$i]['product_rank_price'] = $promotion_price;
                    } else {
                        if ($this->is_promotion($p)) {
                            //该商品现价
                            $promotion_price = $p['Product']['promotion_price'];
                            $_SESSION['checkout']['products'][$i]['is_promotion'] = 1;
                        } else {
                            $promotion_price = $shop_price;
                            $_SESSION['checkout']['products'][$i]['is_promotion'] = 0;
                        }
                    }
                    $_SESSION['checkout']['cart_info']['product_subtotal'] += $promotion_price * $p['quantity'];
                    //当前价格合计   有无属性
                    if (isset($p['attributes_total']) && $p['attributes_total'] != '') {
                        $promotion_price += $p['attributes_total'];
                        $market_price += $p['attributes_total'];
                    }
                    // $_SESSION['checkout']['products'][$i]['Product']['shop_price']=$promotion_price;
                    $_SESSION['checkout']['products'][$i]['subtotal'] = $promotion_price * $p['quantity'];
                    $_SESSION['checkout']['products'][$i]['Product']['market_price'] = $market_price;
                    $_SESSION['checkout']['cart_info']['sum_subtotal'] += $promotion_price * $p['quantity'];
                    //该商品市场价合计
                    $_SESSION['checkout']['products'][$i]['market_subtotal'] = $market_price * $p['quantity'];
                    //全部商品市场价合计
                    $_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $_SESSION['checkout']['products'][$i]['market_subtotal'];
                    //该商品差价
                    $_SESSION['checkout']['products'][$i]['discount_price'] = $market_price - $promotion_price;
                    //该商品折扣%?
                    if ($promotion_price > 0 && $p['Product']['market_price'] > 0) {
                        //		$_SESSION['checkout']['products'][$i]['discount_rate'] = round($promotion_price/$p['Product']['market_price'],2)*100 ;
                        $_SESSION['checkout']['products'][$i]['discount_rate'] = round($promotion_price / $p['Product']['market_price'], 2) * 100;
                    } else {
                        $_SESSION['checkout']['products'][$i]['discount_rate'] = 100;
                    }
                }
            }
            if (isset($_SESSION['checkout']['bespoke'])) {
                foreach ($_SESSION['checkout']['bespoke'] as $i => $p) {
                    if(isset($volume_price)){unset($volume_price);}
                    $proInfo = $this->Product->find('first', array('conditions' => array('Product.id' => $p['Product']['id']), 'recursive' => -1));
                    $shop_price = $this->Product->getOrderProductPrice($p['Product']['id'], $p['Product']['code']);
                    $market_price = $proInfo['Product']['market_price'];
                    if (isset($this->configs['volume_setting']) && $this->configs['volume_setting'] == 1) {
                        $product_volume = $this->ProductVolume->find('first', array('conditions' => array('ProductVolume.product_id' => $p['Product']['id'], 'ProductVolume.volume_number <=' => $p['quantity']), 'limit' => 1, 'order' => 'ProductVolume.volume_number desc'));//none table
                        if (isset($product_volume['ProductVolume'])&&$_SESSION['svcart']['products'][$i]['Product']["is_lease"]==0) {
                            $volume_price = $product_volume['ProductVolume']['volume_price'];
                        }
                    }
                    $_SESSION['checkout']['cart_info']['sum_weight'] += $p['Product']['weight'] * $p['quantity'];
                    $_SESSION['checkout']['cart_info']['sum_quantity'] += $p['quantity'];
                    if (empty($p['Product']['extension_code'])) {
                        $_SESSION['checkout']['cart_info']['all_virtual'] = 0;
                    }
                    //获得是否有会员价
                    if (isset($_SESSION['User'])) {
                        $product_ranks = $this->ProductRank->find_rank_by_product_ids($p['Product']['id']);
                        if (isset($product_ranks[$p['Product']['id']]) && isset($_SESSION['User']['User']['rank']) && isset($product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']])) {
                            if (isset($product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank']) && $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank'] == 0) {
                                $p['Product']['product_rank_price'] = $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['product_price'];
                            } elseif (isset($user_rank_list[$_SESSION['User']['User']['rank']])) {
                                $p['Product']['product_rank_price'] = ($user_rank_list[$_SESSION['User']['User']['rank']]['UserRank']['discount'] / 100) * ($shop_price);
                            }
                        }
                        //	$p['Product']['product_rank_price'] = 	$this->Product->user_price($i,$p,$this);
                    } else {
                        //如果会员未登录 删除SESSION中残留的product_rank_price
                        if (isset($p['Product']['product_rank_price']) || isset($_SESSION['checkout']['bespoke'][$i]['product_rank_price'])) {
                            unset($p['Product']['product_rank_price']);
                            unset($_SESSION['checkout']['bespoke'][$i]['product_rank_price']);
                        }
                    }
                    //$checkout_products_list = $this->Product->find_checkout_products_list($p_ids);
                    //有会员价
                    if (isset($volume_price)) {
                        $promotion_price = $volume_price;
                        // $_SESSION['checkout']['cart_info']['sum_subtotal'] += $volume_price*$p['quantity'];
                    } elseif (isset($p['Product']['product_rank_price'])) {
                        $promotion_price = $p['Product']['product_rank_price'];
                        $_SESSION['checkout']['bespoke'][$i]['product_rank_price'] = $promotion_price;
                    } else {
                        if ($this->is_promotion($p)) {
                            //该商品现价
                            $promotion_price = $p['Product']['promotion_price'];
                            $_SESSION['checkout']['bespoke'][$i]['is_promotion'] = 1;
                        } else {
                            $promotion_price = $shop_price;
                            $_SESSION['checkout']['bespoke'][$i]['is_promotion'] = 0;
                        }
                    }
                    $_SESSION['checkout']['cart_info']['product_subtotal'] += $promotion_price * $p['quantity'];
                    //当前价格合计   有无属性
                    if (isset($p['attributes_total']) && $p['attributes_total'] != '') {
                        $promotion_price += $p['attributes_total'];
                        $market_price += $p['attributes_total'];
                    }
                    // $_SESSION['checkout']['products'][$i]['Product']['shop_price']=$promotion_price;
                    $_SESSION['checkout']['bespoke'][$i]['subtotal'] = $promotion_price * $p['quantity'];
                    $_SESSION['checkout']['bespoke'][$i]['Product']['market_price'] = $market_price;
                    $_SESSION['checkout']['cart_info']['sum_subtotal'] += $promotion_price * $p['quantity'];
                    //该商品市场价合计
                    $_SESSION['checkout']['bespoke'][$i]['market_subtotal'] = $market_price * $p['quantity'];
                    //全部商品市场价合计
                    $_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $_SESSION['checkout']['bespoke'][$i]['market_subtotal'];
                    //该商品差价
                    $_SESSION['checkout']['bespoke'][$i]['discount_price'] = $market_price - $promotion_price;
                    //该商品折扣%?
                    if ($promotion_price > 0 && $p['Product']['market_price'] > 0) {
                        //		$_SESSION['checkout']['products'][$i]['discount_rate'] = round($promotion_price/$p['Product']['market_price'],2)*100 ;
                        $_SESSION['checkout']['bespoke'][$i]['discount_rate'] = round($promotion_price / $p['Product']['market_price'], 2) * 100;
                    } else {
                        $_SESSION['checkout']['bespoke'][$i]['discount_rate'] = 100;
                    }
                }
            }
	if($_SESSION['checkout']['cart_info']['lease_total']>0){
		$insure_fee_rule=$this->ProductLeasePrice->find('first',array("conditions"=>array("ProductLeasePrice.price >="=>0,"ProductLeasePrice.price <="=>$_SESSION['checkout']['cart_info']['lease_total']),"order"=>"ProductLeasePrice.price desc"));
		if($insure_fee_rule['ProductLeasePrice']){
			if($_SESSION['checkout']['cart_info']['lease_total']>$insure_fee_rule['ProductLeasePrice']['price']){
				$insure_fee=$insure_fee_rule['ProductLeasePrice']['lease_deposit_base']+($_SESSION['checkout']['cart_info']['lease_total']-$insure_fee_rule['ProductLeasePrice']['price'])*($insure_fee_rule['ProductLeasePrice']['lease_deposit_increase_percent']/100);
			}else{
				$insure_fee=$insure_fee_rule['ProductLeasePrice']['lease_deposit_base'];
			}
		}
		$_SESSION['checkout']['cart_info']['insure_fee']=$insure_fee;
	}
            if (isset($_SESSION['checkout']['cart_info']['all_virtual'])) {
                $save_cookie = $_SESSION['checkout'];
                unset($save_cookie['products']);
                unset($save_cookie['promotion']['products']);
                //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
            }
            //判断是否有贺卡和包装
            if (isset($_SESSION['checkout']['packagings'])) {
                foreach ($_SESSION['checkout']['packagings'] as $i => $p) {
                    //包装小计
                    // 免费额度的 多货币
                    //$p['Packaging']['free_money'];
                    $packaging_curr_free_money = $p['Packaging']['free_money'];
                    if (isset($_SESSION['checkout']['cart_info']['product_subtotal']) && ($p['Packaging']['free_money'] == 0 || $_SESSION['checkout']['cart_info']['product_subtotal'] < $packaging_curr_free_money)) {
                        //	$_SESSION['checkout']['packagings'][$i]['subtotal'] = $p['Packaging']['fee']*$p['quantity'];
                        $_SESSION['checkout']['packagings'][$i]['subtotal'] = $p['Packaging']['fee'] * $p['quantity'];
                        //加上包装费的总价
                        $_SESSION['checkout']['cart_info']['sum_subtotal'] += $_SESSION['checkout']['packagings'][$i]['subtotal'];
                        $_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $_SESSION['checkout']['packagings'][$i]['subtotal'];
                        unset($_SESSION['checkout']['packagings'][$i]['Packaging']['fee_free']);
                    } else {
                        $_SESSION['checkout']['packagings'][$i]['Packaging']['fee_free'] = 0;
                        $_SESSION['checkout']['packagings'][$i]['subtotal'] = 0;
                    }
                }
            }
            if (isset($_SESSION['checkout']['cards'])) {
                foreach ($_SESSION['checkout']['cards'] as $i => $p) {
                    $card_curr_free_money = $p['Card']['free_money'];
                    if (isset($_SESSION['checkout']['cart_info']['product_subtotal']) && ($p['Card']['free_money'] == 0 || $_SESSION['checkout']['cart_info']['product_subtotal'] < $card_curr_free_money)) {
                        //贺卡小计
                        //$_SESSION['checkout']['cards'][$i]['subtotal'] = $p['Card']['fee']*$p['quantity'];
                        $_SESSION['checkout']['cards'][$i]['subtotal'] = $p['Card']['fee'] * $p['quantity'];
                        //加上贺卡费的总价
                        $_SESSION['checkout']['cart_info']['sum_subtotal'] += $_SESSION['checkout']['cards'][$i]['subtotal'];
                        $_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $_SESSION['checkout']['cards'][$i]['subtotal'];
                        unset($_SESSION['checkout']['cards'][$i]['Card']['fee_free']);
                    } else {
                        $_SESSION['checkout']['cards'][$i]['Card']['fee_free'] = 0;
                        $_SESSION['checkout']['cards'][$i]['subtotal'] = 0;
                    }
                }
            }
            if ($_SESSION['checkout']['cart_info']['sum_subtotal'] == 0 || $_SESSION['checkout']['cart_info']['sum_market_subtotal'] == 0) {
                $_SESSION['checkout']['cart_info']['discount_rate'] = 0;
            } else {
                $_SESSION['checkout']['cart_info']['discount_rate'] = round($_SESSION['checkout']['cart_info']['sum_subtotal'] / $_SESSION['checkout']['cart_info']['sum_market_subtotal'], 2) * 100;
            }
            $_SESSION['checkout']['cart_info']['discount_price'] = $_SESSION['checkout']['cart_info']['sum_market_subtotal'] - $_SESSION['checkout']['cart_info']['sum_subtotal'];
        }
        if ($type == 'packaging') {
            if (isset($_SESSION['checkout']['packagings'])) {
                foreach ($_SESSION['checkout']['packagings'] as $i => $p) {
                    $packaging_curr_free_money = $p['Packaging']['free_money'];
                    if (isset($_SESSION['checkout']['cart_info']['product_subtotal']) && ($p['Packaging']['free_money'] == 0 || $_SESSION['checkout']['cart_info']['product_subtotal'] < $packaging_curr_free_money)) {
                        //包装小计
                        $_SESSION['checkout']['packagings'][$i]['subtotal'] = $p['Packaging']['fee'] * $p['quantity'];
                        $_SESSION['checkout']['packagings'][$i]['is_promotion'] = 0;
                        //总现合计
                        $_SESSION['checkout']['cart_info']['sum_subtotal'] += $_SESSION['checkout']['packagings'][$i]['subtotal'];
                        //总原合计
                        $_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $_SESSION['checkout']['packagings'][$i]['subtotal'];
                        unset($_SESSION['checkout']['packagings'][$i]['Packaging']['fee_free']);
                    } else {
                        $_SESSION['checkout']['packagings'][$i]['Packaging']['fee_free'] = 0;
                        $_SESSION['checkout']['packagings'][$i]['subtotal'] = 0;
                        $_SESSION['checkout']['packagings'][$i]['is_promotion'] = 0;
                    }
                }
            }
            //判断是否有商品和贺卡
            if (isset($_SESSION['checkout']['products'])) {
                if (isset($_SESSION['User']['User'])) {
                    $user_rank_list = $this->UserRank->findrank();
                }
                $_SESSION['checkout']['cart_info']['product_subtotal'] = 0;
                //是否全为虚拟商品
                $_SESSION['checkout']['cart_info']['all_virtual'] = 1;
                if (isset($_SESSION['checkout']['products'])) {
                    foreach ($_SESSION['checkout']['products'] as $i => $p) {
                        if (isset($volume_price)) {unset($volume_price);}
                        if (isset($this->configs['volume_setting']) && $this->configs['volume_setting'] == 1) {
                            $product_volume = $this->ProductVolume->find(array('ProductVolume.product_id' => $p['Product']['id'], 'ProductVolume.volume_number ' => $p['quantity']));//none table
                            if (isset($product_volume['ProductVolume'])&&$_SESSION['svcart']['products'][$i]['Product']["is_lease"]==0) {
                                $volume_price = $product_volume['ProductVolume']['volume_price'];
                            }
                        }
                        $_SESSION['checkout']['cart_info']['sum_weight'] += $p['Product']['weight'] * $p['quantity'];
                        if (empty($p['Product']['extension_code'])) {
                            $_SESSION['checkout']['cart_info']['all_virtual'] = 0;
                        }
                        //获得是否有会员价
                        if (isset($_SESSION['User'])) {
                            $product_ranks = $this->ProductRank->find_rank_by_product_ids($p['Product']['id']);
                            if (isset($product_ranks[$p['Product']['id']]) && isset($_SESSION['User']['User']['rank']) && isset($product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']])) {
                                if (isset($product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank']) && $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank'] == 0) {
                                    $p['Product']['product_rank_price'] = $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['product_price'];
                                } elseif (isset($user_rank_list[$_SESSION['User']['User']['rank']])) {
                                    $p['Product']['product_rank_price'] = ($user_rank_list[$_SESSION['User']['User']['rank']]['UserRank']['discount'] / 100) * ($p['Product']['shop_price']);
                                }
                            }
                            //	$p['Product']['product_rank_price'] = 	$this->Product->user_price($i,$p,$this);
                        } else {
                            //如果会员未登录 删除SESSION中残留的product_rank_price
                            if (isset($p['Product']['product_rank_price']) || isset($_SESSION['checkout']['products'][$i]['product_rank_price'])) {
                                unset($p['Product']['product_rank_price']);
                                unset($_SESSION['checkout']['products'][$i]['product_rank_price']);
                            }
                        }
                        //有会员价
                        if (isset($volume_price)&&$_SESSION['svcart']['products'][$i]['Product']["is_lease"]==0) {
                            $promotion_price = $volume_price;
                            $_SESSION['checkout']['cart_info']['sum_subtotal'] += $volume_price * $p['quantity'];
                        } elseif (isset($p['Product']['product_rank_price'])) {
                            $promotion_price = $p['Product']['product_rank_price'];
                            $_SESSION['checkout']['products'][$i]['product_rank_price'] = $promotion_price;

                            $_SESSION['checkout']['cart_info']['sum_subtotal'] += $p['Product']['product_rank_price'] * $p['quantity'];
                        } else {
                            if ($this->is_promotion($p)) {
                                //该商品现价
                                $promotion_price = $p['Product']['promotion_price'];
                                //全部商品现价合计
                                //$_SESSION['checkout']['cart_info']['sum_subtotal'] += $p['Product']['promotion_price']*$p['quantity'];
                                $_SESSION['checkout']['cart_info']['sum_subtotal'] += $p['Product']['promotion_price'] * $p['quantity'];
                                $_SESSION['checkout']['products'][$i]['is_promotion'] = 1;
                            } else {
                                $promotion_price = $p['Product']['shop_price'];
                                $promotion_price = $p['Product']['shop_price'];
                                //总现合计
                                //	$_SESSION['checkout']['cart_info']['sum_subtotal'] += $p['Product']['shop_price']*$p['quantity'];
                                $_SESSION['checkout']['cart_info']['sum_subtotal'] += $p['Product']['shop_price'] * $p['quantity'];
                                $_SESSION['checkout']['products'][$i]['is_promotion'] = 0;
                            }
                        }
                        //该商品原价
                        //	$_SESSION['checkout']['products'][$i]['market_subtotal'] = $p['Product']['market_price']*$p['quantity'];
                        $_SESSION['checkout']['products'][$i]['market_subtotal'] = $p['Product']['market_price'] * $p['quantity'];
                        //该商品小计
                        $_SESSION['checkout']['products'][$i]['subtotal'] = $promotion_price * $p['quantity'];
                        $_SESSION['checkout']['cart_info']['product_subtotal'] += $promotion_price * $p['quantity'];
                        //全部商品原价合计
                        $_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $_SESSION['checkout']['products'][$i]['market_subtotal'];
                        //该商品差价
                        //$_SESSION['checkout']['products'][$i]['discount_price'] = $p['Product']['market_price'] - $promotion_price;
                        $_SESSION['checkout']['products'][$i]['discount_price'] = $p['Product']['market_price'] - $promotion_price;
                        //该商品折扣%?
                        if ($promotion_price > 0) {
                            $_SESSION['checkout']['products'][$i]['discount_rate'] = round($promotion_price / $p['Product']['market_price'], 2) * 100;
                        } else {
                            $_SESSION['checkout']['products'][$i]['discount_rate'] = 100;
                        }
                    }
                }
            }
            if (isset($_SESSION['checkout']['cards'])) {
                foreach ($_SESSION['checkout']['cards'] as $i => $p) {
                    $card_curr_free_money = $p['Card']['free_money'];
                    if (isset($_SESSION['checkout']['cart_info']['product_subtotal']) && ($p['Card']['free_money'] == 0 || $_SESSION['checkout']['cart_info']['product_subtotal'] < $card_curr_free_money)) {
                        //	$_SESSION['checkout']['cards'][$i]['subtotal'] = $p['Card']['fee']*$p['quantity'];//小计
                        $_SESSION['checkout']['cards'][$i]['subtotal'] = $p['Card']['fee'] * $p['quantity'];
                        $_SESSION['checkout']['cart_info']['sum_subtotal'] += $_SESSION['checkout']['cards'][$i]['subtotal'];
                        $_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $_SESSION['checkout']['cards'][$i]['subtotal'];
                        unset($_SESSION['checkout']['cards'][$i]['Card']['fee_free']);
                    } else {
                        $_SESSION['checkout']['cards'][$i]['subtotal'] = 0;//小计
                        $_SESSION['checkout']['cards'][$i]['Card']['fee_free'] = 0;
                    }
                }
            }
        }
        if ($type == 'card') {
            if (isset($_SESSION['checkout']['cards'])) {
                foreach ($_SESSION['checkout']['cards'] as $i => $p) {
                    $card_curr_free_money = $p['Card']['free_money'];
                    if (isset($_SESSION['checkout']['cart_info']['product_subtotal']) && ($p['Card']['free_money'] == 0 || $_SESSION['checkout']['cart_info']['product_subtotal'] < $card_curr_free_money)) {
                        //	$_SESSION['checkout']['cards'][$i]['subtotal'] = $p['Card']['fee']*$p['quantity'];//小计
                        $_SESSION['checkout']['cards'][$i]['subtotal'] = $p['Card']['fee'] * $p['quantity'];
                        $_SESSION['checkout']['cards'][$i]['is_promotion'] = 0;
                        //总现合计
                        $_SESSION['checkout']['cart_info']['sum_subtotal'] += $_SESSION['checkout']['cards'][$i]['subtotal'];
                        //总原合计
                        $_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $_SESSION['checkout']['cards'][$i]['subtotal'];
                        unset($_SESSION['checkout']['cards'][$i]['Card']['fee_free']);
                    } else {
                        $_SESSION['checkout']['cards'][$i]['subtotal'] = 0;//小计
                        $_SESSION['checkout']['cards'][$i]['is_promotion'] = 0;
                        $_SESSION['checkout']['cards'][$i]['Card']['fee_free'] = 0;
                    }
                }
            }
            if (isset($_SESSION['checkout']['products'])) {
                if (isset($_SESSION['User']['User'])) {
                    $user_rank_list = $this->UserRank->findrank();
                }
                $_SESSION['checkout']['cart_info']['product_subtotal'] = 0;
                //是否全为虚拟商品
                $_SESSION['checkout']['cart_info']['all_virtual'] = 1;
                if (isset($_SESSION['checkout']['products'])) {
                    foreach ($_SESSION['checkout']['products'] as $i => $p) {
                        if (isset($this->configs['volume_setting']) && $this->configs['volume_setting'] == 1) {
                            $product_volume = $this->ProductVolume->find(array('ProductVolume.product_id' => $p['Product']['id'], 'ProductVolume.volume_number ' => $p['quantity']));//none table
                            if (isset($product_volume['ProductVolume'])&&$_SESSION['svcart']['products'][$p['Product']['id']]['Product']["is_lease"]==0) {
                                $volume_price = $product_volume['ProductVolume']['volume_price'];
                            }
                        }
                        $_SESSION['checkout']['cart_info']['sum_weight'] += $p['Product']['weight'] * $p['quantity'];
                        if (empty($p['Product']['extension_code'])) {
                            $_SESSION['checkout']['cart_info']['all_virtual'] = 0;
                        }
                        //获得是否有会员价
                        if (isset($_SESSION['User'])) {
                            $product_ranks = $this->ProductRank->find_rank_by_product_ids($p['Product']['id']);
                            if (isset($product_ranks[$p['Product']['id']]) && isset($_SESSION['User']['User']['rank']) && isset($product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']])) {
                                if (isset($product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank']) && $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank'] == 0) {
                                    //$p['Product']['product_rank_price']= $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['product_price'];
                                    $p['Product']['product_rank_price'] = $product_ranks[$p['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['product_price'];
                                } elseif (isset($user_rank_list[$_SESSION['User']['User']['rank']])) {
                                    //$p['Product']['product_rank_price']=($user_rank_list[$_SESSION['User']['User']['rank']]['UserRank']['discount']/100)*($p['Product']['shop_price']);
                                    $p['Product']['product_rank_price'] = ($user_rank_list[$_SESSION['User']['User']['rank']]['UserRank']['discount'] / 100) * ($p['Product']['shop_price']);
                                }
                            }
                            //	$p['Product']['product_rank_price'] = 	$this->Product->user_price($i,$p,$this);
                        } else {
                            //如果会员未登录 删除SESSION中残留的product_rank_price
                            if (isset($p['Product']['product_rank_price']) || isset($_SESSION['checkout']['products'][$i]['product_rank_price'])) {
                                unset($p['Product']['product_rank_price']);
                                unset($_SESSION['checkout']['products'][$i]['product_rank_price']);
                            }
                        }
                        //有会员价
                        if (isset($volume_price)&&$_SESSION['svcart']['products'][$i]['Product']["is_lease"]==0) {
                            $promotion_price = $volume_price;
                            $_SESSION['checkout']['cart_info']['sum_subtotal'] += $volume_price * $p['quantity'];
                        } elseif (isset($p['Product']['product_rank_price'])) {
                            $promotion_price = $p['Product']['product_rank_price'];
                            $_SESSION['checkout']['products'][$i]['product_rank_price'] = $promotion_price;
                            $_SESSION['checkout']['cart_info']['sum_subtotal'] += $p['Product']['product_rank_price'] * $p['quantity'];
                        } else {
                            if ($this->is_promotion($p)) {
                                //该商品现价
                                $promotion_price = $p['Product']['promotion_price'];
                                //全部商品现价合计
                                $_SESSION['checkout']['cart_info']['sum_subtotal'] += $p['Product']['promotion_price'] * $p['quantity'];
                                $_SESSION['checkout']['products'][$i]['is_promotion'] = 1;
                            } else {
                                $promotion_price = $p['Product']['shop_price'];
                                //总现合计
                                //	$_SESSION['checkout']['cart_info']['sum_subtotal'] += $p['Product']['shop_price']*$p['quantity'];
                                $_SESSION['checkout']['cart_info']['sum_subtotal'] += $p['Product']['shop_price'] * $p['quantity'];
                                $_SESSION['checkout']['products'][$i]['is_promotion'] = 0;
                            }
                        }
                        //该商品原价
                        $_SESSION['checkout']['products'][$i]['market_subtotal'] = $p['Product']['market_price'] * $p['quantity'];
                        //该商品小计
                        $_SESSION['checkout']['products'][$i]['subtotal'] = $promotion_price * $p['quantity'];
                        $_SESSION['checkout']['cart_info']['product_subtotal'] += $promotion_price * $p['quantity'];
                        //全部商品原价合计
                        $_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $_SESSION['checkout']['products'][$i]['market_subtotal'];
                        //该商品差价
                        //$_SESSION['checkout']['products'][$i]['discount_price'] = $p['Product']['market_price'] - $promotion_price;
                        $_SESSION['checkout']['products'][$i]['discount_price'] = $p['Product']['market_price'] - $promotion_price;
                        //该商品折扣%?
                        if ($promotion_price > 0) {
                            //		$_SESSION['checkout']['products'][$i]['discount_rate'] = round($promotion_price/$p['Product']['market_price'],2)*100 ;
                            $_SESSION['checkout']['products'][$i]['discount_rate'] = round($promotion_price / $p['Product']['market_price'], 2) * 100;
                        } else {
                            $_SESSION['checkout']['products'][$i]['discount_rate'] = 100;
                        }
                    }
                }
            }
            if (isset($_SESSION['checkout']['packagings'])) {
                foreach ($_SESSION['checkout']['packagings'] as $i => $p) {
                    $packaging_curr_free_money = $p['Packaging']['free_money'];
                    if (isset($_SESSION['checkout']['cart_info']['product_subtotal']) && ($p['Packaging']['free_money'] > 0 || $_SESSION['checkout']['cart_info']['product_subtotal'] < $packaging_curr_free_money)) {
                        $_SESSION['checkout']['packagings'][$i]['subtotal'] = $p['Packaging']['fee'] * $p['quantity'];
                        $_SESSION['checkout']['cart_info']['sum_subtotal'] += $_SESSION['checkout']['packagings'][$i]['subtotal'];
                        $_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $_SESSION['checkout']['packagings'][$i]['subtotal'];
                        unset($_SESSION['checkout']['packagings'][$i]['Packaging']['fee_free']);
                    } else {
                        $_SESSION['checkout']['packagings'][$i]['subtotal'] = 0;
                        $_SESSION['checkout']['packagings'][$i]['Packaging']['fee_free'] = 0;
                    }
                }
            }
        }
        //节省
        $_SESSION['checkout']['cart_info']['discount_price'] = $_SESSION['checkout']['cart_info']['sum_market_subtotal'] - $_SESSION['checkout']['cart_info']['sum_subtotal'];
        $save_cookie = $_SESSION['checkout'];
        unset($save_cookie['products']);
        unset($save_cookie['promotion']['products']);
        //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
        $this->checkout_confirm_promotion();
    }

    /**
     *处理完成.
     */
    public function done($order_code = '')
    {
        //登录验证
        $this->checkSessionUser();
        $this->layout = 'default_full';
        $weight = 0;
        if ($order_code != '') {
            $order_info = $this->Order->find('first', array('conditions' => array('Order.order_code' => $order_code)));
            if (!empty($order_info)) {
                if ($order_info['Order']['user_id'] != $_SESSION['User']['User']['id']) {
                    	$this->redirect('/');
                }
                $order_product_infos=$this->OrderProduct->find('all',array('conditions'=>array('OrderProduct.order_id'=>$order_info['Order']['id'])));
                $need_pay = $order_info['Order']['total'] - $order_info['Order']['point_fee'] - $order_info['Order']['discount'] - $order_info['Order']['coupon_fee'] - $order_info['Order']['user_balance']- $order_info['Order']['money_paid'];
                $this->set('order_product_infos', $order_product_infos);
                $this->set('need_pay', $need_pay);
                $this->set('order_code', $order_info['Order']['order_code']);
                $this->set('order_id', $order_info['Order']['id']);
                $this->set('order_info', $order_info);
                $this->set('order_data', $order_info['Order']);
                $this->Order->order_notify('order_submission',$order_info['Order']['id'],$this);
                $payment_id = empty($order_info['Order']['payment_id']) ? $order_info['Order']['payment_id'] : 0;
                $payment_info = $this->Payment->find('first', array('conditions' => array('Payment.id' => $order_info['Order']['payment_id'])));
                if (isset($payment_info) && !empty($payment_info)) {
                    $this->set('payment_info',$payment_info);
                    if ($order_info['Order']['payment_status'] == 0 && $payment_info['Payment']['is_cod'] == 0 && $order_info['Order']['status'] != 2) {
                        $sub_paylist = $this->Payment->getOrderChildPayments($order_info['Order']['payment_id']);
                        if (!empty($sub_paylist)) {
                            $this->set('sub_paylist', $sub_paylist);
                        } else {
                            $this->set('pay_message', $this->ld['payment_no_installed']);
                        }
                    }
                }
            } else {
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("ERROR");location.href="'.$this->base.'/carts/checkout"</script>';
                die();
            }
        } else {
            $languages_dictionaries = array();
            $languages_dictionaries['alipay_pay_immedia'] = '马上支付';
            $this->set('languages_dictionaries', $languages_dictionaries);
            if (isset($_POST['no_ajax']) && isset($_SESSION['checkout']['products'])) {
                if (isset($_SESSION['checkout']['products']) && sizeof($_SESSION['checkout']['products']) > 0) {
                    foreach ($_SESSION['checkout']['products'] as $k => $v) {
                        //free shipping
                        if (!empty($v['Product']['freeshopping'])) {
                            continue;
                        }
                        $weight += $v['Product']['weight'];
                    }
                }
                if (isset($_SESSION['checkout']['packagings']) && sizeof($_SESSION['checkout']['packagings']) > 0) {
                    foreach ($_SESSION['checkout']['packagings'] as $k => $v) {
                        if (isset($_POST['packaging'][$v['Packaging']['id']])) {
                            $_SESSION['checkout']['packagings'][$k]['Packaging']['note'] = $_POST['packaging'][$v['Packaging']['id']];
                        }
                    }
                }
                if (isset($_SESSION['checkout']['cards']) && sizeof($_SESSION['checkout']['cards']) > 0) {
                    foreach ($_SESSION['checkout']['cards'] as $k => $v) {
                        if (isset($_POST['card'][$v['Card']['id']])) {
                            $_SESSION['checkout']['cards'][$k]['Card']['note'] = $_POST['card'][$v['Card']['id']];
                        }
                    }
                }
                if (isset($_POST['payment_id']) && $_POST['payment_id'] > 0) {
                    $this->confirm_payment($_POST['payment_id']);
                }
                if (isset($_POST['use_point']) && $_POST['use_point'] > 0) {
                    $_SESSION['checkout']['point']['point'] = $_POST['use_point'];
                    $this->usepoint($_POST['use_point']);
                }
                if (isset($_POST['select_coupon']) && $_POST['select_coupon'] > 0) {
                    $this->usecoupon($_POST['select_coupon'], 'is_id');
                } elseif (isset($_POST['use_coupon']) && $_POST['use_coupon'] != '') {
                    $this->usecoupon($_POST['use_coupon'], 'is_sn');
                }
                if (isset($_POST['shipping_id']) && $_POST['shipping_id'] > 0) {
                    $shippings = $this->show_shipping_by_address($weight, $is_ajax = 0);
                    if (is_array($shippings) && sizeof($shippings) > 0) {
                        foreach ($shippings as $k => $v) {
                            if ($_POST['shipping_id'] == $v['Shipping']['id']) {
                                $select_shipping = array(
                                    'shipping_id' => $v['Shipping']['id'],
                                    'shipping_fee' => $v['ShippingArea']['fee'],
                                    'free_subtotal' => $v['ShippingArea']['free_subtotal'],
                                    'support_cod' => $v['Shipping']['support_cod'],
                                );
                                if (isset($_POST['shipping_id_insure']) && $_POST['shipping_id_insure'] == $_POST['shipping_id']) {
                                    $select_shipping['insure_fee'] = $v['Shipping']['insure_fee'];
                                } else {
                                    $select_shipping['insure_fee'] = 0;
                                }
                            }
                        }
                    }
                    $this->confirm_shipping($select_shipping);
                }
                if (!empty($_POST['package'])) {
                    //取得包装信息
                    $product_info = $this->Packaging->findbyid($_POST['package']);//包装属性待处理！
                    //添加到SVCART
                    $_POST['type'] = 'packaging';//for function addto_svcart;
                    $this->addto_svcart($product_info, 1);
                    if (isset($_SESSION['checkout']['packagings'][$_POST['package']])) {
                        $this->statistic_checkout('packaging');
                    }
                }
            }
            //header("Cache-Control: no-cache, must-revalidate");
            $do_action = 1;
            $this->order_price();
            if (!(isset($_SESSION['User']))) {
                $_SESSION['login_back'] = $this->server_host.$this->webroot.'carts/checkout/';
                $this->redirect($this->server_host.'/');
                exit;
            }
            $error_arr = array();
            if (!(isset($_SESSION['checkout']['products'])) && !(isset($_SESSION['checkout']['bespoke']))) {
                $error_arr[] = $this->ld['no_products_in_cart'];
                $this->set('fail', 1);
                $do_action = 0;
            }
            if (!isset($_SESSION['checkout']['shipping']['shipping_id']) && empty($_SESSION['checkout']['cart_info']['all_virtual'])) {
                $error_arr[] = $this->ld['please_select'].$this->ld['shipping_method'];
                $this->set('fail', 1);
                $do_action = 0;
            }
            if (!isset($_SESSION['checkout']['payment']['payment_id'])) {
            		$cart_need_pay = $_SESSION['checkout']['cart_info']['total'];
                	if($cart_need_pay>0){
	                	if (isset($_POST['payment_id'])) {
		                    $post_pay = $this->Payment->findbyid($_POST['payment_id']);
		                    if (isset($post_pay['Payment']['code']) && $post_pay['Payment']['code'] == 'account_pay') {
		                        $error_arr[] = $this->ld['lack_balance_supply_first'];
		                    } else {
		                        $error_arr[] = $this->ld['please_select'].$this->ld['payment'];
		                    }
		                } else {
		                    $error_arr[] = $this->ld['please_select'].$this->ld['payment'];
		                }
		                $this->set('fail', 1);
		                $do_action = 0;
	                }
            }
            //使用余额支付
            if (!empty($_POST['use_balance_flag']) && $_POST['use_balance_flag'] == '1' && !empty($_POST['user_balance'])) {
                $_SESSION['checkout']['user_balance'] = $_POST['user_balance'];
            } else {
                if (isset($_SESSION['checkout']['user_balance'])) {
                    unset($_SESSION['checkout']['user_balance']);
                }
            }
            //订单最小金额改到 应用中
            /*if(isset($this->configs['min_buy_amount']) && isset($_SESSION['checkout']['cart_info']['total'])) {
                if($_SESSION['checkout']['cart_info']['total'] < $this->configs['min_buy_amount']) {
                    //$this->flash("订单金额低于最小购物金额"," ","/",5);
                    $error_arr[] = $this->ld['order_amount_under_min'];
                    $this->set('fail',1);
                    $do_action = 0;
                }
            }*/
            $user_info = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
            if (isset($_SESSION['checkout']['payment']['payment_id']) && isset($_SESSION['checkout']['products']) && $_SESSION['checkout']['payment']['code'] == 'account_pay' && $_SESSION['checkout']['cart_info']['total'] > $user_info['User']['balance']) {
                $error_arr[] = $this->ld['lack_balance_supply_first'];
                $this->set('fail', 1);
                $do_action = 0;
            }
            //检测验证码
            if(isset($this->config['settlement_verification_code'])&&$this->config['settlement_verification_code']=='1'){
                if (isset($_POST['check_captcha']) && $this->captcha->check($_POST['check_captcha']) == false) {
                    $error_arr[] = $this->ld['verify_code'].$this->ld['not_correct'];
                    $this->set('fail', 1);
                    $do_action = 0;
                }else if(!isset($_POST['check_captcha'])){
                    $error_arr[] = $this->ld['verify_code'].$this->ld['not_correct'];
                    $this->set('fail', 1);
                    $do_action = 0;
                }
            }
            if (!empty($error_arr)) {
                $this->flash($error_arr[0], '/carts/checkout');
                return;
            }
            if ($this->RequestHandler->isPost() && $do_action) {
			if(isset($_SESSION['checkout']['payment']['payment_id'])){
				$payment_info = $this->Payment->findbyid($_SESSION['checkout']['payment']['payment_id']);
			}
                $user_info = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
                $order = array();
                $order['user_id'] = $_SESSION['User']['User']['id'];
                $order['status'] = 0;                                                        //订单状态-应该去系统参数里面取
                $order['consignee'] = $_SESSION['checkout']['address']['consignee'];
                $order['is_separate'] = 0;
                $now = date('Y-m-d H:i:s');
                $order['created'] = $now;
                if (isset($_SESSION['checkout']['stock_handle']) && $_SESSION['checkout']['stock_handle'] != '') {
                    $order['how_oos'] = $_SESSION['checkout']['stock_handle'];
                }
                $rnames = $this->RegionI18n->getNames($this->locale);
                /* 判断是否需要配送方式 */
                if ((isset($_SESSION['checkout']['cart_info']['all_virtual']) && $_SESSION['checkout']['cart_info']['all_virtual'] == 0) || (isset($_SESSION['checkout']['promotion']['all_virtual']) && $_SESSION['checkout']['promotion']['all_virtual'] == 0)) {
                    $order['shipping_id'] = isset($_SESSION['checkout']['shipping']['shipping_id']) ? $_SESSION['checkout']['shipping']['shipping_id'] : -1;
                    $order['shipping_name'] = isset($_SESSION['checkout']['shipping']['shipping_name']) ? $_SESSION['checkout']['shipping']['shipping_name'] : '';
                    $order['shipping_fee'] = isset($_SESSION['checkout']['shipping']['shipping_fee']) ? $_SESSION['checkout']['shipping']['shipping_fee'] : 0;
                    $order['regions'] = $_SESSION['checkout']['address']['regions'];
                    $region_arr2 = explode(' ', $_SESSION['checkout']['address']['regions']);
                    $order['country'] = isset($region_arr2[0]) && isset($rnames[$region_arr2[0]]) ? $rnames[$region_arr2[0]] : '';
                    $order['province'] = isset($region_arr2[1]) && isset($rnames[$region_arr2[1]]) ? $rnames[$region_arr2[1]] : '';
                    $order['city'] = isset($region_arr2[2]) && isset($rnames[$region_arr2[2]]) ? $rnames[$region_arr2[2]] : '';
                    $order['district'] = $_SESSION['checkout']['address']['address'];
                    $order['address'] = $_SESSION['checkout']['address']['address'];
                    $order['zipcode'] = $_SESSION['checkout']['address']['zipcode'];
                    $order['best_time'] = $_SESSION['checkout']['address']['best_time'];
                    $order['sign_building'] = $_SESSION['checkout']['address']['sign_building'];
                } else {
                    $order['shipping_id'] = -1;
                }
                $order['payment_id'] = isset($payment_info['Payment']['id'])?$payment_info['Payment']['id']:0;
                $order['payment_name'] = isset($payment_info['PaymentI18n']['name'])?$payment_info['PaymentI18n']['name']:'';
//            if(isset($_POST['point_del'])&&$_POST['point_del']!=""){
//			    $_SESSION['checkout']['cart_info']['total']=$_SESSION['checkout']['cart_info']['total']-$_POST['point_del'];
//			}
                //	}
                //	if(isset($order['shipping_fee'])){
                //	if(isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  LOCALE != '' && isset($this->data['currencies'][$this->currencie][LOCALE])){
                //		$order['shipping_fee'] = round($order['shipping_fee']*$this->data['currencies'][$this->currencie][LOCALE]['Currency']['rate'],2);
                //	}
                //	}
                $user_balance = 0;
                if (isset($_SESSION['checkout']['user_balance'])) {
                    $user_balance = $_SESSION['checkout']['user_balance'];
                    $user_info['User']['balance'] = $user_info['User']['balance'] - $user_balance;
                }
                $order['user_balance'] = $user_balance;
                $order['point_fee'] = 0;
                if (isset($_SESSION['checkout']['point'])) {
                    $old_point=$user_info['User']['point'];
                    $order['point_fee'] = isset($_SESSION['checkout']['point']['fee']) ? $_SESSION['checkout']['point']['fee'] : 0;
                    $order['point_use'] = isset($_SESSION['checkout']['point']['point']) ? $_SESSION['checkout']['point']['point'] : 0;
                    $user_info['User']['point'] = $user_info['User']['point'] - $order['point_use'];
                }
                $order['discount'] = 0;
                if (isset($_SESSION['checkout']['cart_info']['sum_discount']) && isset($_SESSION['checkout']['cart_info']['discount_price'])) {
                    $order['discount'] = $_SESSION['checkout']['cart_info']['sum_discount'] - $_SESSION['checkout']['cart_info']['discount_price'];
                }
                $order['coupon_fee'] = 0;
                if (isset($_SESSION['checkout']['coupon']) && sizeof($_SESSION['checkout']['coupon']) > 0) {
                    foreach ($_SESSION['checkout']['coupon'] as $sk => $sc) {
                        $coupon_sn_arr[] = $sk;
                    }
                    $coupon_list = $this->Coupon->find('list', array('conditions' => array('Coupon.sn_code' => $coupon_sn_arr), 'fields' => 'Coupon.id'));
                    $coupon_id_str = implode(',', $coupon_list);
                    $order['coupon_id'] = $coupon_id_str;
                    $order['coupon_fee'] = isset($_SESSION['checkout']['cart_info']['coupon_del']) ? $_SESSION['checkout']['cart_info']['coupon_del'] : 0;
                }
                $need_pay = $_SESSION['checkout']['cart_info']['total'];
                $need_pay = $need_pay - $order['point_fee'] - $order['discount'] - $order['coupon_fee'] - $order['user_balance'];
                $this->set('need_pay', $need_pay);
                if (isset($_SESSION['checkout']['cart_info']['coupon_del'])) {
                    $_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['cart_info']['coupon_del'];
                }
                if (isset($_SESSION['checkout']['cart_info']['point_del'])) {
                    $_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['cart_info']['point_del'];
                }
                $_SESSION['checkout']['cart_info']['total'] += $order['discount'];
                $order['total'] = $_SESSION['checkout']['cart_info']['total'];
                $order['insure_fee'] = isset($_SESSION['checkout']['cart_info']['insure_fee'])?$_SESSION['checkout']['cart_info']['insure_fee']:0;//保证金费用
                //余额支付 修改支付状态
                if (isset($_SESSION['checkout']['payment']['code'])&&$_SESSION['checkout']['payment']['code'] == 'account_pay') {
                    $order['payment_status'] = 2;                                                //支付状态-应该去系统参数里面取
                    $order['status'] = 1;
                    $order['payment_time'] = date('Y-m-d H:i:s');                                //支付时间-应该根据具体支付方法来设
                    $order['money_paid'] = $_SESSION['checkout']['cart_info']['total'];
                } elseif ($need_pay <= 0) { //需支付的金额小于等于0,默认设置为已支付
                    $order['status'] = 1;
                    $order['payment_status'] = 2;
                    $order['payment_time'] = date('Y-m-d H:i:s');
                    $order['money_paid'] = 0;
                } else {
                    $order['payment_status'] = 0;
                    $order['money_paid'] = $user_balance;//已支付的金额
                }
                if (isset($_SESSION['checkout']['payment']['sub_pay']['Payment'])) {
                    $order['sub_pay'] = $_SESSION['checkout']['payment']['sub_pay']['Payment']['id'];
                }
//            if($_SESSION['checkout']['payment']['code'] == "pos_pay" || $_SESSION['checkout']['payment']['code'] == 'bank_trans'){
//            	$bank=@unserialize($pay['Payment']['config']);
//            	if(isset($bank['bank']['bb']))
//            		unset($bank['bank']['bb']);
//            	$order['sub_pay']=isset($bank['bank'][$_SESSION['checkout']['payment']['sub_pay']])?$bank['bank'][$_SESSION['checkout']['payment']['sub_pay']]:"";
//            }
                if (isset($_SESSION['checkout']['shipping']['insure_fee_confirm'])) {
                    //	$order['insure_fee']  = $_SESSION['checkout']['shipping']['insure_fee'];

                    //	if(isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  LOCALE != '' && isset($this->data['currencies'][$this->currencie][LOCALE])){
                    //		$order['insure_fee'] = round($_SESSION['checkout']['shipping']['insure_fee']*$this->data['currencies'][$this->currencie][LOCALE]['Currency']['rate'],2);
                    //	}else{
                    $order['insure_fee'] = $_SESSION['checkout']['shipping']['insure_fee_confirm'];
                    //	}
                }
                $order['order_locale'] = LOCALE;//订单语言
                if (isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  LOCALE != '' && isset($this->data['currencies'][$this->currencie][LOCALE])) {
                    $order['order_currency'] = $this->currencie;        //订单货币
                    $order_currency = $this->currencie;
                } else {
                    $pay_type = $this->Payment->find('first', array('conditions' => array('Payment.code' => 'paypal')));
                    //eval($pay_type['Payment']['config']);
                    if (@isset($payment_arr['languages_type']['value'][LOCALE]['value'])) {
                        $order['order_currency'] = $payment_arr['languages_type']['value'][LOCALE]['value'];        //订单货币
                        $order_currency = $payment_arr['languages_type']['value'][LOCALE]['value'];
                    }
                }
                $order['order_domain'] = $this->server_host;                //订单域名
                //$order['payment_fee'] 				= $payment_info['Payment']['fee'];
                //	if(isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  LOCALE != '' && isset($this->data['currencies'][$this->currencie][LOCALE])){
                //			$order['payment_fee'] = round($payment_info['Payment']['fee']*$this->data['currencies'][$this->currencie][LOCALE]['Currency']['rate'],2);
                //	}else{
                $order['payment_fee'] = $this->fee_format_no_price(isset($payment_info['Payment']['fee'])?$payment_info['Payment']['fee']:0, $order['total']);
                //	}
                $order['telephone'] = $_SESSION['checkout']['address']['telephone'];
                $order['mobile'] = $_SESSION['checkout']['address']['mobile'];
                $order['email'] = empty($_SESSION['checkout']['address']['email']) ? $_SESSION['User']['User']['email'] : $_SESSION['checkout']['address']['email'];
//invoice_payee 发票抬头 invoice_type 发票类型  invoice_content 发票内容
                if (isset($_SESSION['checkout']['invoice'])) {
                    $order['invoice_type'] = $_SESSION['checkout']['invoice']['id'];
                    $order['tax'] = empty($_SESSION['checkout']['invoice']['fee']) ? 0 : $_SESSION['checkout']['invoice']['fee'];
                    $order['invoice_payee'] = $_SESSION['checkout']['invoice']['invoice_title'];
                    $order['invoice_content'] = empty($_SESSION['checkout']['invoice']['direction']) ? '' : $_SESSION['checkout']['invoice']['direction'];
                }
                //	if(isset($_POST['isfp']) && $_POST['isfp'] == 1){
                //	$order['invoice_payee'] 				= isset($_POST['fptt'])?$_POST['fptt']:'';
                //	$order['invoice_type'] 					= isset($_POST['fptt'])?$_POST['fptt']:'';
                //	$order['invoice_content'] 				= isset($_POST['fpcontent'])?$_POST['fpcontent']:'';
                //	}
//			$order['postscript'] 				= '';													//不知道去哪取
//			$order['invoice_no'] 				= '';													//不知道去哪取
//			$order['note'] 						= '';													//暂时没有
//			$order['money_paid'] 				= 0;													//已付金额
//			$order['discount'] 					= $_SESSION['checkout']['cart_info']['discount_rate'];	//折扣
                //	$order['subtotal'] 					= $_SESSION['checkout']['cart_info']['sum_subtotal'];		//纯商品总计
                //	if(isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  LOCALE != '' && isset($this->data['currencies'][$this->currencie][LOCALE])){
                //			$order['subtotal'] = round($_SESSION['checkout']['cart_info']['sum_subtotal']*$this->data['currencies'][$this->currencie][LOCALE]['Currency']['rate'],2);
                //		}else{
                if(isset($_SESSION['checkout']['cart_info']['lease_subtotal'])&&$_SESSION['checkout']['cart_info']['lease_subtotal']!=0){
                	$order['subtotal'] = $_SESSION['checkout']['cart_info']['lease_subtotal'];
                	$order['lease_type'] = 'L';
                }else{
                	$order['subtotal'] = $_SESSION['checkout']['cart_info']['sum_subtotal'];
                }
                //		}
//			$order['from_ad'] 					= '';													//广告来源
                if (isset($_COOKIE['CakeCookie']['referer'])) {
                    $order['referer'] = $_COOKIE['CakeCookie']['referer'];                    //订单来源
                }
                if ($this->Cookie->read('union_source')) {
                    $order['union_user_id'] = $this->Cookie->read('union_source');
                } else {
                    $order['union_user_id'] = 0;
                }
                $order['note'] = '';
                $order['order_code'] = $this->Order->get_order_code();
                $order_code = $this->Order->findbyorder_code($order['order_code']);
                if (isset($order_code) && count($order_code) > 0) {
                    $order['order_code'] = $this->Order->get_order_code();
                }
                //获取备注
                if (isset($_SESSION['checkout']['remark']) && $_SESSION['checkout']['remark'] != '') {
                    $order['note'] = $_SESSION['checkout']['remark'];
                }
                if (isset($_SESSION['checkout']['promotion']) && $_SESSION['checkout']['promotion'] != '') {
                    $order['note'] .= '促销活动:';
                    foreach ($_SESSION['checkout']['promotion'] as $v) {
                        $order['note'] .= $v['title'].';';
                    }
                }
                //检查商品库存是否够
                $isEnough = '';
                if (isset($_SESSION['checkout']['bespoke'])) {
                    $_SESSION['checkout']['products'] = $_SESSION['checkout']['bespoke'];
                }
                foreach ($_SESSION['checkout']['products'] as $k => $v) {
                    //套装子商品库存(子商品库存<购买数量*套装中子商品的数量+子商品冻结库存数量)提示库存不足
                    $package_info = $this->PackageProduct->find('all', array('conditions' => array('PackageProduct.product_id' => $v['Product']['id'])));
                    if (!empty($package_info)) {
                        foreach ($package_info as $package_k => $package_v) {
                            $pnum_info = $this->Product->find('first', array('conditions' => array('Product.id' => $package_v['PackageProduct']['package_product_id'])));
                            if ($pnum_info['Product']['quantity'] < ($v['quantity'] * $package_v['PackageProduct']['package_product_qty'])&&isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] != 2) {
                                $isEnough .= $package_v['PackageProduct']['package_product_name'].$this->ld['understock'];
                            } elseif ($_SESSION['checkout']['payment']['code'] == 'account_pay' && isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 1) {
                                //余额支付 存冻结库存
                                $pnum_info['Product']['frozen_quantity'] = $pnum_info['Product']['frozen_quantity'] + $v['quantity'] * $package_v['PackageProduct']['package_product_qty'];
                                $pnum_info['Product']['quantity'] = $pnum_info['Product']['quantity'] - $v['quantity'] * $package_v['PackageProduct']['package_product_qty'];
                                $this->Product->updateAll(array('Product.frozen_quantity' => $pnum_info['Product']['frozen_quantity'], 'Product.quantity' => $pnum_info['Product']['quantity']), array('Product.id' => $pnum_info['Product']['id']));
                            }
                        }
                    }
                    $pnum_info = $this->Product->find('first', array('conditions' => array('Product.code' => $v['Product']['code'])));
                    if (($pnum_info['Product']['quantity'] < $v['quantity'])&&isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] != 2) {
                        $isEnough .= $v['ProductI18n']['name'].$this->ld['understock'];
                    } elseif (isset($_SESSION['checkout']['payment']['code'])&&$_SESSION['checkout']['payment']['code'] == 'account_pay' && isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 1) {
                        //余额支付 存冻结库存
                        $pnum_info['Product']['frozen_quantity'] = $pnum_info['Product']['frozen_quantity'] + $v['quantity'];
                        $pnum_info['Product']['quantity'] = $pnum_info['Product']['quantity'] - $v['quantity'];
                        $this->Product->updateAll(array('Product.frozen_quantity' => $pnum_info['Product']['frozen_quantity'], 'Product.quantity' => $pnum_info['Product']['quantity']), array('Product.id' => $pnum_info['Product']['id']));
                        $this->Product->updateskupro($pnum_info['Product']['code'], $v['quantity'], true);
                        $this->Product->save(array('Product' => $pnum_info['Product']));
                    }
                    if (isset($v['shipping_type']) && $v['shipping_type'] == 'bespoke') {
                        $order['shipping_id'] = 13;
                        //获取订单预约日期，预约时间，预约状态
                        if (isset($v['schedule_date']) && $v['schedule_date'] != '') {
                            $order['schedule_date'] = $v['schedule_date'];
                        }
                        if (isset($v['schedule_time']) && $v['schedule_time'] != '') {
                            $order['schedule_time'] = $v['schedule_time'];
                        }
                    }
                }
                if ($isEnough != '') {
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$isEnough.'");location.href="'.$this->base.'/carts/checkout"</script>';
                    die();
                }
                $order['type'] = 'website';
                $order['type_id'] = 'front';
                $this->Order->save($order);
                $order_id = $this->Order->id;
                $this->User->save($user_info);
                $this->set('order_code', $order['order_code']);
                $this->set('order_data', $order);
                //积分使用日志
                if(isset($old_point)&&isset($order['point_use'])){
				$point_log = array(
					'id' => '',
					'user_id' => $user_info['User']['id'],
					'point'=>$old_point,
					'point_change' => "-".$order['point_use'],
					'log_type' => 'O',
					'system_note' => '订单消费:'.$order['order_code'],
					'type_id' => $order_id
				);
                	 	$this->UserPointLog->save($point_log);
                	 	$this->UserPointLog->point_notify($point_log);
                }
                //余额记录支付记录
                if (!empty($user_balance) && $user_balance > 0) {
                    $balance_log = array(
                        'user_id' => $user_info['User']['id'],
                        'amount' => $user_balance,
                        'log_type' => 'O',
                        'system_note' => '订单消费:'.$order['order_code'],
                        'type_id' => $order_id,
                    );
                    $this->UserBalanceLog->save($balance_log);
                }
                //更新优惠券状态
                if (isset($coupon_list) && !empty($coupon_list)) {
                    //$this->Coupon->updateAll(array('Coupon.order_id'=>$order['order_code'],'Coupon.used_time'=>$now),array('Coupon.id'=>$coupon_list));
                    $coupon_type_list = $this->Coupon->find('list', array('conditions' => array('Coupon.id' => $coupon_list), 'fields' => 'Coupon.coupon_type_id'));
                    $coupon_send_type_list = $this->CouponType->find('list', array('conditions' => array('CouponType.id' => $coupon_type_list), 'fields' => 'CouponType.send_type'));
                    foreach ($coupon_list as $cl) {
                        $coupon = $this->Coupon->find('first', array('conditions' => array('Coupon.id' => $cl)));
                        if ($coupon_send_type_list[$coupon_type_list[$cl]] == 5) {
                            $coupon['Coupon']['max_use_quantity'] += 1;
                        } else {
                            $coupon['Coupon']['user_id'] = $_SESSION['User']['User']['id'];
                            $coupon['Coupon']['order_id'] = $order['order_code'];
                            $coupon['Coupon']['used_time'] = $now;
                        }
                        $this->Coupon->save($coupon['Coupon']);
                    }
                }
                $need_change_order_id = $order_id;
                $order['id'] = $order_id;
                $this->set('order_id', $order_id);
                if (isset($_SESSION['checkout']['packagings'])) {
                    $sum_packagings = 0;
                    foreach ($_SESSION['checkout']['packagings'] as $k => $v) {
                        $orderpackaging = array();
                        $orderpackaging['id'] = '';
                        $orderpackaging['order_id'] = $order_id;
                        $orderpackaging['packaging_id'] = $v['Packaging']['id'];
                        $orderpackaging['packaging_name'] = $v['PackagingI18n']['name'];
                        if ($_SESSION['checkout']['cart_info']['sum_subtotal'] >=  $v['Packaging']['free_money'] && $v['Packaging']['free_money'] > 0) {
                            $orderpackaging['packaging_fee'] = 0;
                        } else {
                            //	$orderpackaging['packaging_fee'] = $v['Packaging']['fee'];
                            //	if(isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  LOCALE != '' && isset($this->data['currencies'][$this->currencie][LOCALE])){
                            //		$orderpackaging['packaging_fee'] = round($v['Packaging']['fee']*$this->data['currencies'][$this->currencie][LOCALE]['Currency']['rate'],2);
                            //	}else{
                            $orderpackaging['packaging_fee'] = $v['Packaging']['fee'];
                            //	}
                        }
                        $orderpackaging['packaging_quantity'] = $v['quantity'];
                        $sum_packagings += $orderpackaging['packaging_fee'];
                        if (isset($v['Packaging']['note'])) {
                            $orderpackaging['note'] = $v['Packaging']['note'];
                        }
                        $this->OrderPackaging->save(array('OrderPackaging' => $orderpackaging));
                        unset($orderpackaging);
                    }
                }
                if (isset($_SESSION['checkout']['cards'])) {
                    $sum_cards = 0;
                    foreach ($_SESSION['checkout']['cards'] as $k => $v) {
                        $ordercard = array();
                        $ordercard['id'] = '';
                        $ordercard['order_id'] = $order_id;
                        $ordercard['card_id'] = $v['Card']['id'];
                        $ordercard['card_name'] = $v['CardI18n']['name'];
                        //$ordercard['card_fee'] = $v['Card']['fee'];
                        if ($_SESSION['checkout']['cart_info']['sum_subtotal'] >=  $v['Card']['free_money'] && $v['Card']['free_money'] > 0) {
                            $ordercard['card_fee'] = 0;
                        } else {
                            //	$ordercard['card_fee'] = $v['Card']['fee'];
                            //	if(isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  LOCALE != '' && isset($this->data['currencies'][$this->currencie][LOCALE])){
                            //		$ordercard['card_fee'] = round($v['Card']['fee']*$this->data['currencies'][$this->currencie][LOCALE]['Currency']['rate'],2);
                            //	}else{
                            $ordercard['card_fee'] = $v['Card']['fee'];
                            //	}
                        }
                        $sum_cards += $ordercard['card_fee'];
                        $ordercard['card_quantity'] = $v['quantity'];
                        if (isset($v['Card']['note'])) {
                            $ordercard['note'] = $v['Card']['note'];
                        }
                        $this->OrderCard->save(array('OrderCard' => $ordercard));
                        unset($ordercard);
                    }
                }
                $product_point = array();
                $is_show_virtual_msg = 0;
                $send_coupon = array();
                $product_size = sizeof($_SESSION['checkout']['products']);
                $product_alsobought = array();
                $mun = 0;
                $affiliate_user_id = $this->Cookie->read('affiliate_user_id');
                $affiliate_product_id = $this->Cookie->read('affiliate_product_id');
                $order_product_ids=array();
                $orderproduct = array();
                foreach ($_SESSION['checkout']['products'] as $k => $v) {
                    if (isset($affiliate_user_id) && isset($affiliate_product_id) && $affiliate_product_id != '' && $affiliate_user_id != '' && $v['Product']['id'] == $affiliate_product_id) {
                        $is_order_affiliate = 1;
                    }
                    //ProductAlsobought
                    if ($product_size > 0 && $mun > 0) {
                        $product_alsobought[$mun] = array('id' => '','product_id' => $_SESSION['checkout']['products'][$also]['Product']['id'],'alsobought_product_id' => $v['Product']['id']);
                    } else {
                        $also = $k;
                    }
                    ++$mun;
                    $product_point[$k] = array(
                        'point' => $v['Product']['point'] * $v['quantity'],
                        'name' => $v['ProductI18n']['name'],
                    );
                    $orderproduct[$mun]['id'] = '';
                    $orderproduct[$mun]['order_id'] = $order_id;
                    $orderproduct[$mun]['product_id'] = $v['Product']['id'];
                    $order_product_ids[]=$v['Product']['id'];
                    $orderproduct[$mun]['product_name'] = $v['ProductI18n']['name'];
                    $orderproduct[$mun]['product_code'] = $v['Product']['code'];
                    $orderproduct[$mun]['product_quntity'] = $v['quantity'];
                    $orderproduct[$mun]['product_weight'] = $v['quantity'] * $v['Product']['weight'];
                    $orderproduct[$mun]['extension_code'] = $v['Product']['extension_code'];
                    //存拼图
                    $orderproduct[$mun]['file_url'] = isset($v['Product']['file_url']) ? $v['Product']['file_url'] : '';
                    //记录备注
                    $orderproduct[$mun]['note'] = isset($v['note']) ? $v['note'] : '';
                    if ($v['Product']['coupon_type_id'] > 0) {
                        $send_coupon[] = $v['Product']['coupon_type_id'];
                    }
                    if ($v['Product']['extension_code'] == 'virtual_card') {
                        $check_virtual_product = $this->Product->findbyid($v['Product']['id']);
                        if ($check_virtual_product['Product']['quantity'] < $v['quantity']) {
                            $is_show_virtual_msg = 1;
                        }
                    }
                    if (isset($v['Product']['note'])) {
                        $orderproduct[$mun]['note'] = $v['Product']['note'];
                    }
                    if (isset($v['product_rank_price'])) {
                        $price = $v['product_rank_price'];
                    } elseif (isset($v['is_promotion'])) {
                        if ($v['is_promotion'] == 1) {
                            $price = $v['Product']['shop_price'];
                            $adjust_fee = $v['Product']['promotion_price'] - $v['Product']['shop_price'];
                        } else {
                            $price = $v['Product']['shop_price'];
                        }
                    } else {
                        $price = $v['Product']['shop_price'];
                    }
                    if (isset($v['CartProductValue']) && !empty($v['CartProductValue'])) {
                        $attr_price = 0;
                        foreach ($v['CartProductValue'] as $cpk => $cpv) {
                            $attr_price += $cpv['attr_price'];
                        }
                        $price = $v['Product']['shop_price'] + $attr_price;
                    }
                    //租赁
                    if($v['Product']['is_lease']==1){
                    	$orderproduct[$mun]['product_price'] = $v['Product']['lease_price'];
                    	$orderproduct[$mun]['lease_type']="L";
                    	$orderproduct[$mun]['lease_unit'] = $v['Product']['lease_day'];
                    	$orderproduct[$mun]['purchase_price'] = $v['Product']['shop_price'];
                    }else{
                    	$orderproduct[$mun]['product_price'] = $price;
                    	$orderproduct[$mun]['purchase_price'] = $price;
                    }
                    $orderproduct[$mun]['adjust_fee'] = isset($adjust_fee) ? $adjust_fee : 0;
                    //套装子商品库存
                    $package_info = $this->PackageProduct->find('all', array('conditions' => array('PackageProduct.product_id' => $v['Product']['id'])));
                    if (!empty($package_info)) {
                    	$package_product_data=array();
                    	$PackageProduct_total=0;
                    	foreach ($package_info as $package_k => $package_v) {
                    		$package_product_info=$this->Product->findbyid($package_v['PackageProduct']['package_product_id']);
                    		if(empty($package_product_info))continue;
                    		$PackageProduct_total+=$package_product_info['Product']['shop_price'];
                    		$package_product_data[$package_v['PackageProduct']['package_product_id']]=$package_product_info;
                    	}
                        $p_mun = 0;
                        $PackageProduct_proportion=$orderproduct[$mun]['product_price']/$PackageProduct_total;
                        $PackageProduct_sutotal=0;
                        foreach ($package_info as $package_k => $package_v) {
                        	   $package_product_id=$package_v['PackageProduct']['package_product_id'];
				   $update_proudct = isset($package_product_data[$package_product_id])?$package_product_data[$package_product_id]:array();
				   if(empty($update_proudct))continue;
				   if($p_mun<sizeof($package_info)-1){
						$PackageProduct_price=$PackageProduct_proportion*$update_proudct['Product']['shop_price'];
						$PackageProduct_price = number_format($PackageProduct_price, 2, '.', ' '); //套装单品价格
						$PackageProduct_sutotal+=$PackageProduct_price;
				   }else{
				   		$PackageProduct_price=$orderproduct[$mun]['product_price']-$PackageProduct_sutotal;
				   }
                            $child_orderproduct = array();
                            //存订单套装商品
                            ++$p_mun;
                            $child_orderproduct[$p_mun]['id'] = '';
                            $child_orderproduct[$p_mun]['order_id'] = $order_id;
                            $child_orderproduct[$p_mun]['product_id'] = $package_v['PackageProduct']['package_product_id'];
                            $child_orderproduct[$p_mun]['product_name'] = $package_v['PackageProduct']['package_product_name'];
                            $child_orderproduct[$p_mun]['product_code'] = $package_v['PackageProduct']['package_product_code'];
                            $child_orderproduct[$p_mun]['product_price'] = $PackageProduct_price;
                            if (!empty($v['attributes'])) {
                                //根据子商品货号查询规格属性货号
                                $sku_products = $this->SkuProduct->find('all', array('conditions' => array('SkuProduct.product_code' => $package_v['PackageProduct']['package_product_code']), 'fields' => 'SkuProduct.sku_product_code'));
                                $sku_pro_id = array();
                                $sku_pro_code = array();
                                foreach ($sku_products as $sk => $sv) {
                                    $pro = $this->Product->find('first', array('conditions' => array('Product.code' => $sv['SkuProduct']['sku_product_code']), 'fields' => 'Product.id'));
                                    $sku_pro_id[] = $pro['Product']['id'];
                                    $sku_pro_code[$pro['Product']['id']] = $sv['SkuProduct']['sku_product_code'];
                                }
                                $pro_attr = $this->ProductAttribute->find('all', array('conditions' => array('ProductAttribute.product_id' => $sku_pro_id)));
                                foreach ($pro_attr as $ak => $av) {
                                    if ($av['ProductAttribute']['attribute_value'] == $v['attributes']) {
                                        $child_orderproduct[$p_mun]['product_code'] = $sku_pro_code[$av['ProductAttribute']['product_id']];
                                    }
                                }
                            }
                            $child_orderproduct_quntity=$package_v['PackageProduct']['package_product_qty']*$v['quantity'];
                            $child_orderproduct[$p_mun]['product_quntity'] = $child_orderproduct_quntity;
                            if (isset($v['CartProductValue']) && !empty($v['CartProductValue'])) {
                                $child_orderproduct[$p_mun]['product_quntity'] = 0;
                            }
                            $child_orderproduct[$p_mun]['parent_product_id'] = $v['Product']['id'];
                            //添加子商品的用户模板
                            if (isset($v['user_style_id']) && !empty($v['user_style_id'])) {
                                $user_style_id = explode(';', $v['user_style_id']);
                                $user_style_arr = array();
                                foreach ($user_style_id as $uk => $uv) {
                                    if ($uv != '') {
                                        $temp_arr = explode(':', $uv);
                                        $user_style_arr[$temp_arr[0]] = $temp_arr[1];
                                    }
                                }
                                foreach ($user_style_arr as $ak => $av) {
                                    if ($update_proudct['Product']['product_type_id'] == $ak) {
                                        $child_orderproduct[$p_mun]['user_style_id'] = $av;
                                    }
                                }
                            }
                            //保存套装子商品的定制属性
                            $this->OrderProduct->saveAll($child_orderproduct);
                            $order_product_id = $this->OrderProduct->id;
                            $attr_ids = $this->ProductTypeAttribute->getattrids($update_proudct['Product']['product_type_id']);
                            if (isset($v['CartProductValue']) && !empty($v['CartProductValue'])) {
                                foreach ($v['CartProductValue'] as $ck => $cv) {
                                    if (in_array($cv['attribute_id'], $attr_ids)) {
                                        $order_product_data = array(
                                            'order_id' => $order_id,
                                            'order_product_id' => $order_product_id,
                                            'attribute_id' => $cv['attribute_id'],
                                            'attribute_value' => $cv['attribute_value'],
                                            'attr_price' => $cv['attr_price'],
                                        );
                                        $this->OrderProductValue->saveAll($order_product_data);
                                    }
                                }
                            }
                            $x = array();
                            $x = ClassRegistry::init('DisupdateList')->find('list', array('fields' => array('DisupdateList.product_code')));
                            //订单产生的时候 加冻结库存 不减库存
                            if (!in_array($update_proudct['Product']['code'], $x) && isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 1) {
                                $update_proudct['Product']['frozen_quantity'] += $v['quantity'];
                                $update_proudct['Product']['quantity'] = $update_proudct['Product']['quantity'] - $v['quantity'];
                                $this->Product->updateAll(array('Product.frozen_quantity' => $update_proudct['Product']['frozen_quantity'], 'Product.quantity' => $update_proudct['Product']['quantity']), array('Product.id' => $package_v['PackageProduct']['package_product_id']));
                                $this->Product->updateskupro($update_proudct['Product']['code'], $v['quantity'], true);
                            }
                            //订单产生的时候 加冻结材料
                            if (!in_array($update_proudct['Product']['code'], $x) && isset($this->configs['shop-frozen-materials']) && $this->configs['shop-frozen-materials'] == 1 && $child_orderproduct[$p_mun]['parent_product_id'] != 0) {
                                //查询使用材料
                                $pro_material = ClassRegistry::init('ProductMaterial')->find('all', array('conditions' => array('ProductMaterial.product_code' => $update_proudct['Product']['code'])));
                                //减材料库存
                                if (!empty($pro_material)) {
                                    foreach ($pro_material as $mk => $mv) {
                                        $material = ClassRegistry::init('Material');
                                        $material_info = $material->find('first', array('conditions' => array('Material.code' => $mv['ProductMaterial']['product_material_code'])));
                                        $order_material_product_data = array(
                                            'order_id' => $order_id,
                                            'order_product_id' => $order_product_id,
                                            'product_code' => $mv['ProductMaterial']['product_code'],
                                            'material_product_code' => $mv['ProductMaterial']['product_material_code'],
                                            'material_qty' => $mv['ProductMaterial']['quantity'],
                                        );
                                        $om_product = ClassRegistry::init('OrderMaterialProduct');
                                        $result_om_product = $om_product->check_order_pro_material($order_id, $order_product_id, $mv['ProductMaterial']['product_code'], $mv['ProductMaterial']['product_material_code']);
                                        if ($result_om_product) {
                                            $om_product->saveAll($order_material_product_data);
                                        }
                                        $material_info['Material']['frozen_quantity'] += $mv['ProductMaterial']['quantity'];
                                        $material_info['Material']['quantity'] -= $mv['ProductMaterial']['quantity'];
                                        $material->updateAll(array('Material.frozen_quantity' => $material_info['Material']['frozen_quantity'], 'Material.quantity' => $material_info['Material']['quantity']), array('Material.code' => $mv['ProductMaterial']['product_material_code']));
                                    }
                                }
                            }
                            $this->Product->updateAll(array('Product.sale_stat' => $update_proudct['Product']['sale_stat'] + $v['quantity']), array('Product.id' => $package_v['PackageProduct']['package_product_id']));
                        }
                        //$this->OrderProduct->saveAll($child_orderproduct);
                    }
                    $update_proudct = $this->Product->find('first', array('conditions' => array('Product.code' => $v['Product']['code'])));
                    $x = array();
                    $x = ClassRegistry::init('DisupdateList')->find('list', array('fields' => array('DisupdateList.product_code')));
                    //订单产生的时候 加冻结库存 不减库存
                    if (!in_array($v['Product']['code'], $x) && isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 1) {
                        $update_proudct['Product']['frozen_quantity'] += $v['quantity'];
                        $update_proudct['Product']['quantity'] = $update_proudct['Product']['quantity'] - $v['quantity'];
                        $this->Product->updateAll(array('Product.frozen_quantity' => $update_proudct['Product']['frozen_quantity'], 'Product.quantity' => $update_proudct['Product']['quantity']), array('Product.id' => $update_proudct['Product']['id']));
                        $this->Product->updateskupro($update_proudct['Product']['code'], $v['quantity'], true);
                    }
                    if (isset($this->configs['enable_decrease_stock_time']) && $this->configs['enable_decrease_stock_time'] == 1) {
                        $update_proudct['Product']['quantity'] = $update_proudct['Product']['quantity'] - $v['quantity'];
                        $this->Product->updateAll(array('Product.frozen_quantity' => $update_proudct['Product']['frozen_quantity'], 'Product.quantity' => $update_proudct['Product']['quantity']), array('Product.id' => $update_proudct['Product']['id']));
                        $this->Product->updateskupro($update_proudct['Product']['code'], $v['quantity'], true);
                    }
                    $this->Product->updateAll(array('Product.sale_stat' => $update_proudct['Product']['sale_stat'] + $v['quantity']), array('Product.id' => $update_proudct['Product']['id']));
                    if (isset($v['attributes'])) {
                        $orderproduct[$mun]['product_attrbute'] = $v['attributes'];
                    }
                    if (isset($_SESSION['svcart']['products'][$k]['user_style_id']) && $_SESSION['svcart']['products'][$k]['user_style_id'] != 0) {
                        $orderproduct_user_style_id = $_SESSION['svcart']['products'][$k]['user_style_id'];
                        $orderproduct_user_style_arr = split(':', $orderproduct_user_style_id);
                        $orderproduct[$mun]['user_style_id'] = isset($orderproduct_user_style_arr[1]) ? intval($orderproduct_user_style_arr[1]) : 0;
                    }
                    $result = $this->OrderProduct->saveAll($orderproduct[$mun]);
                    //订单产生的时候 加冻结材料
                    if (!in_array($v['Product']['code'], $x) && isset($this->configs['shop-frozen-materials']) && $this->configs['shop-frozen-materials'] == 1) {
                        //查询使用材料
                        $pro_material = ClassRegistry::init('ProductMaterial')->find('all', array('conditions' => array('ProductMaterial.product_code' => $v['Product']['code'])));
                        //减材料库存
                        if (!empty($pro_material)) {
                            $order_product_id = $this->OrderProduct->id;
                            foreach ($pro_material as $mk => $mv) {
                                $material = ClassRegistry::init('Material');
                                $material_info = $material->find('first', array('conditions' => array('Material.code' => $mv['ProductMaterial']['product_material_code'])));
                                $order_material_product_data = array(
                                    'order_id' => $order_id,
                                    'order_product_id' => $order_product_id,
                                    'product_code' => $mv['ProductMaterial']['product_code'],
                                    'material_product_code' => $mv['ProductMaterial']['product_material_code'],
                                    'material_qty' => $mv['ProductMaterial']['quantity'],
                                );
                                $om_product = ClassRegistry::init('OrderMaterialProduct');
                                $result_om_product = $om_product->check_order_pro_material($order_id, $order_product_id, $mv['ProductMaterial']['product_code'], $mv['ProductMaterial']['product_material_code']);
                                if ($result_om_product) {
                                    $om_product->saveAll($order_material_product_data);
                                }
                                $material_info['Material']['frozen_quantity'] += $mv['ProductMaterial']['quantity'];
                                $material_info['Material']['quantity'] -= $mv['ProductMaterial']['quantity'];
                                $material->updateAll(array('Material.frozen_quantity' => $material_info['Material']['frozen_quantity'], 'Material.quantity' => $material_info['Material']['quantity']), array('Material.code' => $mv['ProductMaterial']['product_material_code']));
                            }
                        }
                    }
                    if ($result && isset($v['CartProductValue']) && !empty($v['CartProductValue'])) {
                        $order_product_id = $this->OrderProduct->id;
                        $CartProductValueData = $v['CartProductValue'];
                        foreach ($CartProductValueData as $cpk => $cpv) {
                            $order_product_data = array(
                                'order_id' => $order_id,
                                'order_product_id' => $order_product_id,
                                'attribute_id' => $cpv['attribute_id'],
                                'attribute_value' => $cpv['attribute_value'],
                                'attr_price' => $cpv['attr_price'],
                            );
                            $this->OrderProductValue->saveAll($order_product_data);
                        }
                    }
                }
                if(!empty($order_product_ids)){
                		$cart_ids=$this->Cart->find('list',array('conditions'=>array('user_id'=>$_SESSION['User']['User']['id'],'product_id'=>$order_product_ids)));
                		if(!empty($cart_ids)){
                			$this->CartProductValue->deleteAll(array('cart_id'=>$cart_ids));
                			$this->Cart->deleteAll(array('id'=>$cart_ids));
                		}
                }
                if (isset($this->configs['shop-email']) && !empty($this->configs['shop-email']) && isset($this->configs['shop-email-status']) && $this->configs['shop-email-status'] == 1) {
                    $send_date = date('Y-m-d');
                    $shop_name = $this->configs['shop_name'];
                    $template = $this->MailTemplate->find("code = 'order_confirm' and status = 1");
                    $template_str = $template['MailTemplateI18n']['html_body'];
                    $template_str = str_replace('$consignee', $_SESSION['User']['User']['name'], $template_str);
                    $template_str = str_replace('$formated_add_time', DateTime, $template_str);
                    $email_product_info = '<table cellpadding="0" cellspacing="0" width="100%"><tbody><tr class="thead">';
                    $email_product_info .= '<th class="pname">'.$this->ld['product_name'].'</th><th class="checkprice">'.$this->ld['price'].'</th><th class="checkprice"><span>'.$this->ld['offer'].'</span></th>';
                    $email_product_info .= '<th class="points">'.$this->ld['single-product_integration'].'</th>';
                    $email_product_info .= '<th>'.$this->ld['qty'].'</th><th colspan="2" class="subtotal">'.$this->ld['subtotal'].'</th></tr>';
                    foreach ($_SESSION['checkout']['products'] as $v) {
                        $email_product_info .= '<tr><td style="text-align:center">'.$v['ProductI18n']['name'];
                        if (isset($v['attributes']) && $v['attributes'] != '') {
                            $email_product_info .= '<span>'.$v['attributes'].'</span>';
                        }
                        $email_product_info .= '</td><td style="text-align:center">';
                        if (isset($v['is_promotion']) && $v['is_promotion'] == 1) {
                            $email_product_info .= $v['Product']['promotion_price'];
                        } else {
                            $email_product_info .= $v['Product']['shop_price'];
                        }
                        $email_product_info .= '</td><td style="text-align:center">';
                        if (isset($v['is_promotion']) && $v['is_promotion'] == 1) {
                            if (($v['Product']['market_price'] - $v['Product']['shop_price']) > 0) {
                                $email_product_info .= $v['Product']['market_price'] - $v['Product']['promotion_price'];
                            } else {
                                $email_product_info .= '--';
                            }
                        } else {
                            if (($v['Product']['market_price'] - $v['Product']['promotion_price']) > 0) {
                                $email_product_info .= $v['Product']['market_price'] - $v['Product']['shop_price'];
                            } else {
                                $email_product_info .= '--';
                            }
                        }
                        $email_product_info .= '</td>';
                        $email_product_info .= '<td style="text-align:center" >';
                        if ($v['Product']['point'] == 0) {
                            $email_product_info .= '--';
                        } else {
                            $email_product_info .= $v['Product']['point'];
                        }
                        $email_product_info .= '</td>';
                        $email_product_info .= '<td style="text-align:center">'.$v['quantity'].'</td><td colspan="2" style="text-align:center">'.$v['subtotal'].'</td></tr>';
                    }
                    $email_product_info .= '</tbody></table>';
                    $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
                    $shop_url = '<a href="http://'.$host.$this->base.'">'.$host.'</a>';
                    $template_str = str_replace('$order_code ', $order['order_code'], $template_str);
                    $template_str = str_replace('$shop_name', $shop_name, $template_str);
                    $template_str = str_replace('$sent_date', $send_date, $template_str);
                    $template_str = str_replace('$products_info', $email_product_info, $template_str);
                    $template_str = str_replace('$shop_url', $shop_url, $template_str);
                    $subject = $template['MailTemplateI18n']['title'];
                    $mail_send_queue = array(
                        'id' => '',
                        'sender_name' => $shop_name,
                        'receiver_email' => $_SESSION['User']['User']['name'].';'.$_SESSION['User']['User']['email'],
                        'cc_email' => $this->configs['shop-email'],
                        'bcc_email' => ';',
                        'title' => $subject,
                        'html_body' => $template_str,
                        'text_body' => $template_str,
                        'sendas' => 'html',
                        'flag' => 0,
                        'pri' => 0,
                    );
                    $this->Notify->send_email($mail_send_queue,$this->configs);
                }
                $this->set('is_show_virtual_msg', $is_show_virtual_msg);
                //订单分成
                if (isset($is_order_affiliate) && $_SESSION['User']['User']['parent_id'] == 0) {
                    $parent_user = $this->User->findbyid($affiliate_user_id);
                    $change_order = array('id' => $order_id,'parent_id' => $affiliate_user_id);
                    $this->Order->save($change_order);
                    if (isset($parent_user['User'])) {
                        $affiliate_log = array(
                            'id' => '',
                            'order_id' => $order_id,
                            'user_id' => $parent_user['User']['id'],
                            'user_name' => $parent_user['User']['name'],
                            'point' => 0,
                            'separate_type' => 1,
                        );
                        $this->AffiliateLog->save($affiliate_log);
                    }
                } elseif (isset($_SESSION['User']['User']['parent_id']) && $_SESSION['User']['User']['parent_id'] > 0) {
                    //		$order['parent_id'] 				= $_SESSION['User']['User']['parent_id'];
                    //注册分成
                    $parent_user = $this->User->findbyid($_SESSION['User']['User']['parent_id']);
                    if (isset($parent_user['User'])) {
                        $affiliate_log = array(
                            'id' => '',
                            'order_id' => $order_id,
                            'user_id' => $parent_user['User']['id'],
                            'user_name' => $parent_user['User']['name'],
                            'point' => 0,
                            'separate_type' => 0,
                        );
                        $this->AffiliateLog->save($affiliate_log);
                    }
                }
//            if(isset($_SESSION['checkout']['coupon']['coupon'])) {
//                $coupon = $this->Coupon->findbyid($_SESSION['checkout']['coupon']['coupon']);
//
//                $coupon_type = $this->CouponType->findbyid($coupon['Coupon']['coupon_type_id']);
//                if($coupon_type['CouponType']['send_type'] == 5) {
//                    $coupon['Coupon']['max_use_quantity'] += 1;
//                }else {
//                    $coupon['Coupon']['user_id'] = $_SESSION['User']['User']['id'];
//                    $coupon['Coupon']['order_id'] = $order_id;
//                    $coupon['Coupon']['used_time'] = $now;
//                }
//                $this->Coupon->save($coupon['Coupon']);
//            }
                $update_order = $this->Order->findbyid($order_id);
                if (isset($sum_cards)) {
                    $update_order['Order']['card_fee'] = $sum_cards;
                    //				if(isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  LOCALE != '' && isset($this->data['currencies'][$this->currencie][LOCALE])){
                    //					$update_order['Order']['card_fee'] = round($sum_cards*$this->data['currencies'][$this->currencie][LOCALE]['Currency']['rate'],2);
                    //				}else{
                    //					$update_order['Order']['card_fee'] = $sum_cards;
                    //				}
                }
                if (isset($sum_packagings)) {
                    $update_order['Order']['pack_fee'] = $sum_packagings;
                    /*	if(isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  LOCALE != '' && isset($this->data['currencies'][$this->currencie][LOCALE])){
                        $update_order['Order']['pack_fee'] = round($sum_packagings*$this->data['currencies'][$this->currencie][LOCALE]['Currency']['rate'],2);
                    }else{
                        $update_order['Order']['pack_fee'] = $sum_packagings;
                    }				*/
                }
                $this->Order->save($update_order);
                //促销活动 商品
                if (isset($_SESSION['checkout']['Product_by_Promotion']) && count($_SESSION['checkout']['Product_by_Promotion']) > 0) {
                    foreach ($_SESSION['checkout']['Product_by_Promotion'] as $k => $vv) {
                        foreach ($vv as $v) {
                            $orderproduct = array();
                            $orderproduct['id'] = '';
                            $orderproduct['order_id'] = $order_id;
                            $orderproduct['product_id'] = $v['Product']['id'];
                            if (isset($orderproduct['product_id'])) {
                                //$pro_product =$this->Product->findbyid($orderproduct['product_id']);
                                $orderproduct['product_name'] = $v['ProductI18n']['name'];
                            }
                            $orderproduct['product_code'] = $v['Product']['code'];
                            $orderproduct['product_quntity'] = '1';  //暂时为1
                            //			$orderproduct['product_price'] = $v['Product']['now_fee'];

                            //	if(isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  LOCALE != '' && isset($this->data['currencies'][$this->currencie][LOCALE])){
                            //			$orderproduct['product_price'] = round($v['Product']['now_fee']*$this->data['currencies'][$this->currencie][LOCALE]['Currency']['rate'],2);
                            //		}else{
                            $orderproduct['product_price'] = $v['Product']['now_fee'];
                            //		}
                            $orderproduct['product_attrbute'] = isset($v['Product']['attr']) ? $v['Product']['attr'] : '';
                            //$order['subtotal'] +=	$orderproduct['product_price'];
                            $this->OrderProduct->save(array('OrderProduct' => $orderproduct));
                            unset($orderproduct);
                        }
                    }
                    //$this->Order->save($order);
                }
                if (isset($_SESSION['checkout']['payment']['code'])&&$_SESSION['checkout']['payment']['code'] == 'account_pay') {
                    //减用户金额
                    $user_info = $this->User->findbyid($update_order['Order']['user_id']);
                    //		$user_info['User']['balance'] -= $_SESSION['checkout']['cart_info']['total'];
                    //	if(isset($this->data['configs']['currencies_setting']) && $this->data['configs']['currencies_setting'] == 1 && $this->currencie != '' &&  LOCALE != '' && isset($this->data['currencies'][$this->currencie][LOCALE])){
                    ///		$user_info['User']['balance'] -= round($_SESSION['checkout']['cart_info']['total']*$this->data['currencies'][$this->currencie][LOCALE]['Currency']['rate'],2);
                    //		$fee_user = round($_SESSION['checkout']['cart_info']['total']*$this->data['currencies'][$this->currencie][LOCALE]['Currency']['rate'],2);
                    //	}else{
                    $user_info['User']['balance'] -= $_SESSION['checkout']['cart_info']['total'];
                    $fee_user = $_SESSION['checkout']['cart_info']['total'];
                    //	}
                    $this->User->save($user_info);
                    //UserBalanceLog
                    $balance_log = array(
                        'id' => '',
                        'user_id' => $user_info['User']['id'],
                        'amount' => 0 - $fee_user,
                        'log_type' => 'O',
                        'system_note' => '订单消费',
                        'type_id' => $order_id,
                    );
                    $this->UserBalanceLog->save($balance_log);
                    //如果是余额支付  付款送积分
                    if (isset($product_alsobought) && sizeof($product_alsobought) > 0) {
                        $this->ProductAlsobought->saveall($product_alsobought);
                    }
                    
                    if(isset($this->configs['use_point'])&&$this->configs['use_point']=='1'){
                    	$points_awarded_occasion=isset($this->configs['points_awarded_occasion'])?$this->configs['points_awarded_occasion']:'';
                    	//下单积分赠送
                    	if(in_array($points_awarded_occasion,array('0','3'))){//租赁订单
                    		if(isset($configs['lease_order_points'])&&$this->configs['lease_order_points']>0){
                    			$user_info = $this->User->findbyid($update_order['Order']['user_id']);
                    			$old_point=$user_info['User']['point'];
                    			$user_info['User']['point'] += $this->configs['lease_order_points'];
                        			$user_info['User']['user_point'] += $this->configs['lease_order_points'];
                        			$this->User->save($user_info);
                        			$point_log = array('id' => '',
		                            'user_id' => $update_order['Order']['user_id'],
		                            'point' => $old_point,
		                            'point_change' => $this->configs['lease_order_points'],
		                            'log_type' => 'B',
		                            'system_note' => '下单送积分',
		                            'type_id' => $update_order['Order']['id'],
		                        	);
		                        $this->UserPointLog->save($point_log);
		                        $this->UserPointLog->point_notify($point_log);
                    		}
                    	}else{//购物订单
                    		if(in_array($points_awarded_occasion,array('0','2'))&&$this->configs['order_points']>0){
                    			$user_info = $this->User->findbyid($update_order['Order']['user_id']);
                    			$old_point=$user_info['User']['point'];
                    			$user_info['User']['point'] += $this->configs['order_points'];
                        			$user_info['User']['user_point'] += $this->configs['order_points'];
                        			$this->User->save($user_info);
                        			$point_log = array('id' => '',
		                            'user_id' => $update_order['Order']['user_id'],
		                            'point' => $old_point,
		                            'point_change' => $this->configs['order_points'],
		                            'log_type' => 'B',
		                            'system_note' => '下单送积分',
		                            'type_id' => $update_order['Order']['id'],
		                        	);
		                        $this->UserPointLog->save($point_log);
		                        $this->UserPointLog->point_notify($point_log);
                    		}
                    	}
                    	
                    	//超过订单金额赠送积分
                    	$config_order_smallest = isset($this->configs['order_smallest'])?$this->configs['order_smallest']:0;
                    	if(isset($update_order['Order']['lease_type'])&&$update_order['Order']['lease_type']=='L'){//租赁订单
                    		$config_order_smallest=isset($this->configs['lease_order_smallest'])?$this->configs['lease_order_smallest']:0;
                    		if (in_array($points_awarded_occasion,array('0','3'))&&$config_order_smallest <= $update_order['Order']['subtotal']&&$this->configs['out_lease_order_points']>0) {
                    			$user_info = $this->User->findbyid($update_order['Order']['user_id']);
						$old_point=$user_info['User']['point'];
						$user_info['User']['point'] += $this->configs['out_lease_order_points'];
						$user_info['User']['user_point'] += $this->configs['out_lease_order_points'];
						$this->User->save($user_info);
						$point_log = array('id' => '',
							'user_id' => $update_order['Order']['user_id'],
							'point' => $old_point,
							'point_change' => $this->configs['out_lease_order_points'],
							'log_type' => 'B',
							'system_note' => '超过订单金额 '.$this->configs['order_smallest'].' 赠送积分',
							'type_id' => $update_order['Order']['id'],
						);
						$this->UserPointLog->save($point_log);
						$this->UserPointLog->point_notify($point_log);
                    		}
                    	}else{
                    		if(in_array($points_awarded_occasion,array('0','2'))&&$config_order_smallest <= $update_order['Order']['subtotal']&&$this->configs['out_order_points'] > 0){
						$user_info = $this->User->findbyid($update_order['Order']['user_id']);
						$old_point=$user_info['User']['point'];
						$user_info['User']['point'] += $this->configs['out_order_points'];
						$user_info['User']['user_point'] += $this->configs['out_order_points'];
						$this->User->save($user_info);
						$point_log = array('id' => '',
							'user_id' => $update_order['Order']['user_id'],
							'point' => $old_point,
							'point_change' => $this->configs['out_order_points'],
							'log_type' => 'B',
							'system_note' => '超过订单金额 '.$this->configs['order_smallest'].' 赠送积分',
							'type_id' => $update_order['Order']['id'],
						);
						$this->UserPointLog->save($point_log);
						$this->UserPointLog->point_notify($point_log);
                    		}
                    	}
                    }
                    //商品下单后不直接送积分 而是确认收获后再付积分 将改订单可获得的积分存放在订单表里面
                    //是否送优惠券
                }
                $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order['id'])));
                //保存默认支付和配送方式
                $user_info = $this->User->findbyid($order['user_id']);
                $user_info['User']['payment_id'] = $order_info['Order']['payment_id'];
                $user_info['User']['shipping_id'] = $order_info['Order']['shipping_id'];
                $this->User->save($user_info);
                $this->set('order_info', $order_info);
            } else {
                $this->set('error_arr', $error_arr);
                $this->redirect('/carts/checkout');
            }
            $pay_info = $order;
            //$this->Region->set_locale('eng');
            // $city=$this->Region->find('first',array('conditions'=>array('Region.id'=>$order['city'])));
            $state = $this->Region->find('first', array('conditions' => array('Region.id' => $_SESSION['checkout']['address']['province'])));
            $country = $this->Region->find('first', array('conditions' => array('Region.id' => $_SESSION['checkout']['address']['country'])));
            if (!empty($state)) {
                $pay_info = array_merge($pay_info, $state);
            }
            if (!empty($country)) {
                $pay_info = array_merge($pay_info, $country);
            }
            $modified = date('Y-m-d H:i:s');
            $user_id = $_SESSION['User']['User']['id'];
            $user_info = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
            $user_money = $user_info['User']['balance'] + $order['total'];
            $amount_money = $order['total'];
            $account_id = $this->UserAccount->id;
            $payment_id = $order['payment_id'];
            $this->Cookie->write('pay_type', $payment_id);
            $this->del_cart_product('done');
            //产生订单操作记录
            $this->OrderAction->saveAll(array('OrderAction' => array(
                'order_id' => $order_id,
                'from_operator_id' => 0,
                'user_id' => $_SESSION['User']['User']['id'],
                'order_status' => $order_info['Order']['status'],
                'payment_status' => $order_info['Order']['payment_status'],
                'shipping_status' => $order_info['Order']['shipping_status'],
                'action_note' => $this->ld['submit_order'],
            )));
            $this->redirect('/carts/done/'.$order_info['Order']['order_code']);
        }
        $this->ur_heres[] = array('name' => $this->ld['settlement'],'url' => '/carts/');
        $this->ur_heres[] = array('name' => $this->ld['order_submit'],'url' => '');
        $this->page_init();
        $this->pageTitle = $this->ld['order_submit'].' - '.$this->configs['shop_title'];
        $this->layout = 'default_full';
    }

    //已付款 未发货的商品库存处理
    public function frozen_quantity($order_id)
    {
        if (isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 0) {
            $order_data = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id), 'fields' => 'id,shipping_status'));
            if (isset($order_data['Order']['shipping_status']) && $order_data['Order']['shipping_status'] == 0) {
                $order_products = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $order_id)));
                foreach ($order_products as $opk => $opv) {
                    //套装子商品库存
                    $package_info = $this->PackageProduct->find('all', array('conditions' => array('PackageProduct.product_id' => $opv['OrderProduct']['product_id'])));
                    if (!empty($package_info)) {
                        foreach ($package_info as $package_k => $package_v) {
                            //已付款 未发货的商品冻结库存处理
                            $product_frozen = $this->Product->find('first', array('conditions' => array('Product.id' => $package_v['PackageProduct']['package_product_id']), 'fields' => 'Product.id,Product.code,Product.frozen_quantity,Product.quantity'));
                            if (!empty($product_frozen)) {
                                $product_frozen['Product']['frozen_quantity'] = $product_frozen['Product']['frozen_quantity'] + $opv['OrderProduct']['product_quntity'];
                                $product_frozen['Product']['quantity'] = $product_frozen['Product']['quantity'] - $opv['OrderProduct']['product_quntity'];
                                $this->Product->save(array('Product' => $product_frozen['Product']));
                            }
                        }
                    }
                    //已付款 未发货的商品冻结库存处理
                    if (!empty($opv['OrderProduct']['product_code'])) {
                        $product_frozen = $this->Product->find('first', array('conditions' => array('Product.code' => $opv['OrderProduct']['product_code']), 'fields' => 'Product.id,Product.code,Product.frozen_quantity,Product.quantity'));
                        if (!empty($product_frozen)) {
                            $product_frozen['Product']['frozen_quantity'] = $product_frozen['Product']['frozen_quantity'] + $opv['OrderProduct']['product_quntity'];
                            $product_frozen['Product']['quantity'] = $product_frozen['Product']['quantity'] - $opv['OrderProduct']['product_quntity'];
                            $this->Product->save(array('Product' => $product_frozen['Product']));
                        }
                    }
                }
            }
        }
        //已付款，冻结材料
// 		if(isset($this->configs['shop-frozen-materials'])&& $this->configs['shop-frozen-materials'] ==0){
//	 		$order_data = $this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id),"fields"=>"id,shipping_status"));
//			if(isset($order_data['Order']['shipping_status'])&&$order_data['Order']['shipping_status']==0){
//		 		$order_products=$this->OrderProduct->find('all',array("conditions"=>array("OrderProduct.order_id"=>$order_id)));
//				foreach($order_products as $opk=>$opv ){
//					//套装子商品材料
//					$package_info=$this->PackageProduct->find('all',array('conditions'=>array('PackageProduct.product_id'=>$opv['OrderProduct']['product_id'])));
//					if(!empty($package_info)){
//						foreach($package_info as $package_k=>$package_v){
//							//查询使用材料
//	            			$pro_material=ClassRegistry::init("ProductMaterial")->find('all' ,array('conditions'=>array('ProductMaterial.product_code'=>$package_v['PackageProduct']['package_product_code'])));
//			            	//减材料库存
//			            	if(!empty($pro_material)){
//			            		$order_product_id=$opv['OrderProduct']['id'];
//			            		foreach($pro_material as $mk=>$mv){
//			            			$material=ClassRegistry::init("Material");
//			            			$material_info=$material->find("first" ,array('conditions'=>array("Material.code"=>$mv['ProductMaterial']['product_material_code'])));
//			            			$order_material_product_data=array(
//				            			'order_id'=>$order_id,
//				            			'order_product_id'=>$order_product_id,
//				            			'product_code'=>$mv['ProductMaterial']['product_code'],
//				            			'material_product_code'=>$mv['ProductMaterial']['product_material_code'],
//				            			'material_qty'=>$mv['ProductMaterial']['quantity']
//				            		);
//				            		$om_product=ClassRegistry::init("OrderMaterialProduct");
//				            		$result_om_product = $om_product->check_order_pro_material($order_id,$order_product_id,$mv['ProductMaterial']['product_code'],$mv['ProductMaterial']['product_material_code']);
//				            		if($result_om_product){
//				            			$om_product->saveAll($order_material_product_data);
//				            		}
//				            		$material_info['Material']['frozen_quantity'] +=$mv['ProductMaterial']['quantity'];
//									$material_info['Material']['quantity']-=$mv['ProductMaterial']['quantity'];
//				            		$material->updateAll(array('Material.frozen_quantity'=>$material_info['Material']['frozen_quantity'] ,'Material.quantity'=>$material_info['Material']['quantity']) ,array('Material.code'=>$mv['ProductMaterial']['product_material_code']));
//
//			            		}
//			            	}
//						}
//					}
//					//已付款 未发货的商品冻结材料处理
//					if(!empty($opv["OrderProduct"]["product_code"])){
//						//查询使用材料
//            			$pro_material=ClassRegistry::init("ProductMaterial")->find('all' ,array('conditions'=>array('ProductMaterial.product_code'=>$opv["OrderProduct"]["product_code"])));
//		            	//减材料库存
//		            	if(!empty($pro_material)){
//		            		$order_product_id=$opv['OrderProduct']['id'];
//		            		foreach($pro_material as $mk=>$mv){
//		            			$material=ClassRegistry::init("Material");
//		            			$material_info=$material->find("first" ,array('conditions'=>array("Material.code"=>$mv['ProductMaterial']['product_material_code'])));
//		            			$order_material_product_data=array(
//			            			'order_id'=>$order_id,
//			            			'order_product_id'=>$order_product_id,
//			            			'product_code'=>$mv['ProductMaterial']['product_code'],
//			            			'material_product_code'=>$mv['ProductMaterial']['product_material_code'],
//			            			'material_qty'=>$mv['ProductMaterial']['quantity']
//			            		);
//			            		ClassRegistry::init("OrderMaterialProduct")->saveAll($order_material_product_data);
//			            		$om_product=ClassRegistry::init("OrderMaterialProduct");
//			            		$result_om_product = $om_product->check_order_pro_material($order_id,$order_product_id,$mv['ProductMaterial']['product_code'],$mv['ProductMaterial']['product_material_code']);
//			            		if($result_om_product){
//			            			$om_product->saveAll($order_material_product_data);
//			            		}
//			            		$material_info['Material']['frozen_quantity'] +=$mv['ProductMaterial']['quantity'];
//								$material_info['Material']['quantity']-=$mv['ProductMaterial']['quantity'];
//			            		$material->updateAll(array('Material.frozen_quantity'=>$material_info['Material']['frozen_quantity'] ,'Material.quantity'=>$material_info['Material']['quantity']) ,array('Material.code'=>$mv['ProductMaterial']['product_material_code']));
//
//		            		}
//		            	}
//					}
//				}
//			}
//		}
    }

    public function aim_go()
    {
        App::import('Vendor', 'payments/'.'authorizenet_aim');
        if ($this->RequestHandler->isPost()) {
            $x = unserialize(base64_decode($_SESSION['aim']));
            //	$x=$_SESSION['aim'];
            //	$x=(authorizenet_aim)$x;
            $x->real_go($this->data['Users']['card_num']);
            $_SESSION['aim'] = base64_encode(serialize($x));
            //pr($x->response);
            //$this->redirect("http://".$_SERVER['HTTP_HOST']."/responds/return_code/".'authorizenet_aim');
        }
    }

    /**
     *保存.
     */
    public function svcart_save()
    {
        $svcart = array();
        $svcart['products'] = $_SESSION['svcart']['products'];
        $svcart['cart']['subtotal'] = 0; //商品现价小计
        $svcart['cart']['market_subtotal'] = 0; //商品市场价小计
        if (is_array($svcart['products'])) {
            $categories = $this->CategoryProductI18n->findassoc($this->locae);
            $brands = $this->Brand->findassoc(LOCALE);
            foreach ($svcart['products'] as $k => $p) {
                $_SESSION['svcart']['products'][$product_id]['CategoryProductI18nInfo'] = $this->CategoryProductI18n->findbyid($product_info['ProductsCategoryProductI18n']['id']);
                $_SESSION['svcart']['products'][$product_id]['BrandInfo'] = $this->Brand->findbyid($product_info['Product']['brand_id']);
                if ($product_info['is_promotion'] == 1) {
                    $_SESSION['svcart']['products'][$product_id]['subtotal'] = $product_info['Product']['promotion_price'] * $product_info['quantity'];
                }//小计
                else {
                    $_SESSION['svcart']['products'][$product_id]['subtotal'] = $product_info['Product']['shop_price'] * $product_info['quantity'];
                }//小计
                //原合计
                $_SESSION['svcart']['products'][$product_id]['market_subtotal'] = $product_info['Product']['market_price'] * $product_info['quantity'];
                //总现合计
                $_SESSION['svcart']['cart_info']['now_count_fee'] += $_SESSION['svcart']['products'][$product_id]['subtotal'];
                //总原合计
                $_SESSION['svcart']['cart_info']['market_count_fee'] += $_SESSION['svcart']['products'][$product_id]['market_subtotal'];
            }
        }
        //总折扣
        $_SESSION['svcart']['cart_info']['discount_price'] = round($_SESSION['svcart']['cart_info']['now_count_fee'] / $_SESSION['svcart']['cart_info']['market_count_fee'], 2) * 100;
        //总节省
        $_SESSION['svcart']['cart_info']['save_price'] = $_SESSION['svcart']['cart_info']['market_count_fee'] - $_SESSION['svcart']['cart_info']['now_count_fee'];
    }

    /**
     *Ajax页面初始化.
     */
    public function ajax_page_init()
    {
        //分类信息
        $this->CategoryProduct->tree('P', 0, LOCALE);
        $this->set('categories', $this->CategoryProduct->allinfo['P']['assoc']);
        //品牌信息
        $this->set('brands', $this->Brand->findassoc(LOCALE));
    }

    /**
     *确认地址.
     *
     *@param $type
     */
    public function confirm_address($a_id = '', $type = 0)
    {
        //	if(isset($this->configs['use_ajax']) && $this->configs['use_ajax'] == 0){
        if ($a_id != '') {
            $_POST['address_id'] = $a_id;
            $is_ajax = 0;
        } else {
            $is_ajax = 1;
        }
//		}
        //header("Cache-Control: no-cache, must-revalidate");
        //	$_POST['address_id']=41;
        $result = array();
        if (isset($_SESSION['User']['User']['id'])) {
            $address = $this->UserAddress->findbyid($_POST['address_id']);
            if (!empty($address) && $address['UserAddress']['user_id'] == $_SESSION['User']['User']['id']) {
                //保存默认用户地址
                $user_info['User']['id'] = $_SESSION['User']['User']['id'];
                $user_info['User']['address_id'] = $_POST['address_id'];
                $this->User->save($user_info);
                $addresses_count = $this->UserAddress->find_count_addresses($_SESSION['User']['User']['id']);//model调用
                $result['type'] = 0;
                $this->set('need_new_address', 0);
                $region_array = explode(' ', trim($address['UserAddress']['regions']));
                $address['UserAddress']['regionI18n'] = '';
                foreach ($region_array as $k => $region_id) {
                    $region_info = $this->Region->findbyid($region_id);
                    if ($k < sizeof($region_array) - 1) {
                        $address['UserAddress']['regionI18n'] .= $region_info['RegionI18n']['name'].' ';
                    } else {
                        $address['UserAddress']['regionI18n'] .= $region_info['RegionI18n']['name'];
                    }
                }
                $_SESSION['checkout']['address'] = $address['UserAddress'];
                $_SESSION['checkout']['billing_address'] = $address['UserAddress'];
                $save_cookie = $_SESSION['checkout'];
                unset($save_cookie['products']);
                unset($save_cookie['promotion']['products']);
                //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
                $weight = 0;
                if (isset($_SESSION['checkout']['products']) && sizeof($_SESSION['checkout']['products']) > 0) {
                    $weight = 0;
                    foreach ($_SESSION['checkout']['products'] as $k => $v) {
                        $weight += $v['Product']['weight'];
                    }
                }
                if (isset($_SESSION['checkout']['bespoke']) && sizeof($_SESSION['checkout']['bespoke']) > 0) {
                    $weight = 0;
                    foreach ($_SESSION['checkout']['bespoke'] as $k => $v) {
                        $weight += $v['Product']['weight'];
                    }
                }
                //通过地址找配送方式
                if ($is_ajax == 0) {
                    $shippings = $this->show_shipping_by_address($weight, $is_ajax); //confirm_address
                }
                $this->set('address', $address);
                $this->set('checkout', $_SESSION['checkout']);
                $this->set('addresses_count', $addresses_count);
            } else {
                $result['type'] = 1;
                $result['message'] = $this->ld['invalid'];
            }
            /* 判断是否需要显示配送方式 */
            if ((isset($_SESSION['checkout']['cart_info']['all_virtual']) && $_SESSION['checkout']['cart_info']['all_virtual'] == 0)
                || (isset($_SESSION['checkout']['promotion']['all_virtual']) && $_SESSION['checkout']['promotion']['all_virtual'] == 0)) {
                $this->set('all_virtual', 0);
            } else {
                $this->set('all_virtual', 1);
            }
            //	pr($_SESSION['checkout']['address']);
            $this->set('result', $result);
            $this->layout = 'ajax';
        }
        if ($type == 1) {
            $this->redirect('/carts/checkout');
        }
        if ($type == 2) {
            return $result['type'];
        }
    }

    /**
     *确认投保费.
     */
    public function confirm_insure_fee()
    {
        $result = array();
        if (isset($_SESSION['User']['User']['id'])) {
            //insure_fee_confirm
            if ($_POST['type'] == 1) {
                $_SESSION['checkout']['cart_info']['total'] += $_POST['insure_fee'];
                $_SESSION['checkout']['shipping']['insure_fee_confirm'] = $_POST['insure_fee'];
            } elseif ($_POST['type'] == 2) {
                $_SESSION['checkout']['cart_info']['total'] -= $_POST['insure_fee'];
                unset($_SESSION['checkout']['shipping']['insure_fee_confirm']);
            }
            $result['type'] = 0;
            $this->set('shipping', $_SESSION['checkout']['shipping']);
        }
        $this->order_price();
        $this->set('checkout', $_SESSION['checkout']);
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    /**
     *运货方式.
     *
     *@param $s_id
     */
    /**
     *运货方式.
     *
     *@param $s_id
     */
    public function confirm_shipping($s_id = '')
    {
        if ($s_id != '') {
            $post_shipping = $s_id;
            $is_ajax = 0;
        } else {
            $is_ajax = 1;
            $post_shipping = $_POST;
        }
        //header("Cache-Control: no-cache, must-revalidate");
        //	$_POST['address_id']=41;
        $result = array();
        if (isset($_SESSION['User']['User']['id'])) {
            $payment_fee = 0;
            $shipping_type = 0;
            //		if(isset($_SESSION['checkout']['payment']['payment_fee'])){
            //			$payment_fee = $_SESSION['checkout']['payment']['payment_fee'];
            //		}
            //		$_SESSION['checkout']['cart_info']['shipping_fee'] = $_SESSION['checkout']['shipping']['shipping_fee'];
            //		$_SESSION['checkout']['cart_info']['total'] = $_SESSION['checkout']['shipping']['shipping_fee']+$_SESSION['checkout']['cart_info']['sum_subtotal']+$payment_fee;
            //		pr($_POST['support_cod']."-".$_SESSION['checkout']['payment']['is_cod']);
            $this->Shipping->set_locale($this->locale);

            $shipping = $this->Shipping->findbyid($post_shipping['shipping_id']);
            //print_r($shipping);
            $result['change_payment'] = 0;
            if (!isset($_SESSION['checkout']['payment']['is_cod'])) {
                $result['change_payment'] = 1;
            } elseif (isset($_SESSION['checkout']['payment']['is_cod']) &&  $_SESSION['checkout']['payment']['is_cod'] == 1) {
                $result['change_payment'] = 1;
                //		$result['message'] = $this->languages['shipping_no_support'];
            }
            //	}else{
            $_SESSION['checkout']['shipping'] = $post_shipping;
            $_SESSION['checkout']['shipping']['shipping_code'] = $shipping['Shipping']['code'];
            $_SESSION['checkout']['shipping']['shipping_name'] = $shipping['ShippingI18n']['name'];
            $_SESSION['checkout']['shipping']['shipping_description'] = $shipping['ShippingI18n']['description'];

            if ($_SESSION['checkout']['shipping']['free_subtotal'] > 0 && $_SESSION['checkout']['shipping']['free_subtotal'] < $_SESSION['checkout']['cart_info']['sum_subtotal']) {
                $_SESSION['checkout']['shipping']['shipping_fee'] = 0;
                //	$_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['shipping']['insure_fee'];
            } else {
                //	$_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['shipping']['shipping_fee'];
                //	$_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['shipping']['insure_fee'];
            }
            $result['type'] = 0;
            //$save_cookie = $_SESSION['checkout'];unset($save_cookie['products']);unset($save_cookie['promotion']['products']);$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
            $this->set('shipping_type', $shipping_type);
            //	}
        }
        $this->order_price();
        $this->set('checkout', $_SESSION['checkout']);
        $this->get_point_and_coupon();
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    /**
     *编辑地址.
     */
    public function edit_address()
    {
        //header("Cache-Control: no-cache, must-revalidate");
        if ($this->RequestHandler->isPost()) {
            $address = $this->UserAddress->findbyid($_POST['id']);
            //$tel = $address['UserAddress']['telephone'];
            $result['type'] = 1;
            if (isset($address)) {
                $result['id'] = $_POST['id'];
                $result['type'] = 0;
                $result['str'] = $address['UserAddress']['regions'];
                $this->set('address', $address);
            }
            /* 判断是否需要显示配送方式 */
            if ((isset($_SESSION['checkout']['cart_info']['all_virtual']) && $_SESSION['checkout']['cart_info']['all_virtual'] == 0)
                || (isset($_SESSION['checkout']['promotion']['all_virtual']) && $_SESSION['checkout']['promotion']['all_virtual'] == 0)) {
                $result['all_virtual'] = 0;
            } else {
                $result['all_virtual'] = 1;
            }
            $this->set('result', $result);
            $this->layout = 'ajax';
        }
    }

    /**
     *编辑地址行为.
     */
    public function edit_address_act()
    {
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 1);
            //$this->layout = 'ajax';
            $this->page_init();
            $no_error = 1;
            $result['type'] = 1;
            $region_arr = array();
            if (isset($_POST['data']['Address']['RegionUpdate'])) {
                $region_arr = $_POST['data']['Address']['RegionUpdate'];
            }
            if (isset($_POST['data']['Address']['Region'])) {
                $region_arr = $_POST['data']['Address']['Region'];
            }
            if (in_array($this->ld['please_select'], $region_arr)) {
                $region_error = 1;
            } elseif (!empty($region_arr)) {
                $region_info = $this->Region->find('first', array('conditions' => array('Region.parent_id' => $region_arr[count($region_arr) - 1])));
                if (isset($region_info['Region'])) {
                    $region_error = 1;
                }
            }
            if (trim($_POST['data']['address']['name']) == '' && !isset($_POST['is_vancl'])) {
                $msg = ''.$this->ld['address'].$this->ld['can_not_empty'].'';
                $no_error = 0;
            } elseif (isset($_POST['data']['address']['consignee']) && trim($_POST['data']['address']['consignee']) == '') {
                if (LOCALE == 'eng' && trim($_POST['data']['address']['first_name']) == '') {
                    $msg = 'first name'.$this->ld['can_not_empty'].'';
                    $no_error = 0;
                } elseif (LOCALE == 'eng' && trim($_POST['data']['address']['last_name']) == '') {
                    $msg = 'last name'.$this->ld['can_not_empty'].'';
                    $no_error = 0;
                } elseif (LOCALE != 'eng') {
                    $msg = ''.$this->ld['consignee'].$this->ld['can_not_empty'].'';
                    $no_error = 0;
                } elseif (LOCALE == 'eng' && !empty($_POST['data']['address']['first_name']) && !empty($_POST['data']['address']['last_name'])) {
                    $_POST['data']['address']['consignee'] = $_POST['data']['address']['first_name'].' '.$_POST['data']['address']['last_name'];
                }
            } elseif (!isset($_POST['data']['address']['consignee'])) {
                if (LOCALE == 'eng' && trim($_POST['data']['address']['first_name']) == '') {
                    $msg = 'first name'.$this->ld['can_not_empty'].'';
                    $no_error = 0;
                } elseif (LOCALE == 'eng' && trim($_POST['data']['address']['last_name']) == '') {
                    $msg = 'last name'.$this->ld['can_not_empty'].'';
                    $no_error = 0;
                } elseif (LOCALE == 'eng' && !empty($_POST['data']['address']['first_name']) && !empty($_POST['data']['address']['last_name'])) {
                    $_POST['data']['address']['consignee'] = $_POST['data']['address']['first_name'].' '.$_POST['data']['address']['last_name'];
                }
            } elseif (trim($_POST['data']['address']['email']) == '' && !isset($_POST['is_vancl'])) {
                $msg = ''.$this->ld['e-mail_empty'].'';
                $no_error = 0;
            } elseif (!ereg("^[-a-zA-Z0-9_.]+@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$", $_POST['data']['address']['email']) && !isset($_POST['is_vancl'])) {
                $msg = ''.$this->ld['email'].$this->ld['format'].$this->ld['not_correct'].'';
                $no_error = 0;
            }
            $telephone = $_POST['user_tel0'];
            $regions = implode(' ', $region_arr);
            $address = array(
                'id' => isset($_POST['data']['address']['id']) ? $_POST['data']['address']['id'] : '',
                'user_id' => $_SESSION['User']['User']['id'],
                'name' => $_POST['data']['address']['name'],
                'consignee' => isset($_POST['data']['address']['consignee']) ? $_POST['data']['address']['consignee'] : '',
                'first_name' => isset($_POST['data']['address']['first_name']) ? $_POST['data']['address']['first_name'] : '',
                'last_name' => isset($_POST['data']['address']['last_name']) ? $_POST['data']['address']['last_name'] : '',
                'email' => $_POST['data']['address']['email'],
                'address' => isset($_POST['data']['address']['address']) ? $_POST['data']['address']['address'] : '',
                'sign_building' => isset($_POST['data']['address']['sign_building']) ? $_POST['data']['address']['sign_building'] : '',
                'zipcode' => isset($_POST['data']['address']['zipcode']) ? $_POST['data']['address']['zipcode'] : '',
                'mobile' => $_POST['data']['address']['mobile'],
                'best_time' => $_POST['data']['address']['best_time'],
                'telephone' => $telephone,
                'regions' => $regions,
                'country' => isset($region_arr[0]) ? $region_arr[0] : '',
                'province' => isset($region_arr[1]) ? $region_arr[1] : '',
                'city' => isset($region_arr[2]) ? $region_arr[2] : '',
            );
            if (isset($_SESSION['checkout']['address'])) {
                $_SESSION['checkout']['address']['country'] = isset($region_arr[0]) ? $region_arr[0] : '';
                $_SESSION['checkout']['address']['province'] = isset($region_arr[1]) ? $region_arr[1] : '';
                $_SESSION['checkout']['address']['city'] = isset($region_arr[2]) ? $region_arr[2] : '';
                $_SESSION['checkout']['address']['address'] = isset($_POST['data']['address']['address']) ? $_POST['data']['address']['address'] : '';
            }
            if (isset($address) && $no_error) {
                $address['user_id'] = $_SESSION['User']['User']['id'];
                //如果用户信息里面的名字是空 切是第一保存收获地址 默认为收货人的名字
                $user_info = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
                if (empty($user_info['User']['first_name'])) {
                    $count = $this->UserAddress->find('count', array('conditions' => array('UserAddress.user_id' => $_SESSION['User']['User']['id'])));
                    if ($count == 0) {
                        $user_info['User']['first_name'] = $address['consignee'];
                        $this->User->save($user_info);
                    }
                }
                $this->UserAddress->save($address);
                $result['type'] = 0;
                $result['id'] = $this->UserAddress->id;
                if (!isset($is_ajax)) {
                    $this->confirm_address($result['id'], 0);
                }
                $msg = $this->ld['tips_edit_success'];
            }
            $result['msg'] = $msg;
            if (!isset($_POST['is_ajax'])) {
                $this->pageTitle = $msg.'-'.$this->configs['shop_name'];
                if ($no_error) {
                    if (isset($_POST['is_vancl'])) {
                        header('Location:'.$this->server_host.$this->webroot.'carts/checkout');
                        $url = $this->base.'/carts/checkout';
                    } else {
                        header('Location:'.$this->server_host.$this->webroot.'carts/checkout');
                        $url = $this->base.'/carts/checkout';
                    }
                } else {
                    if (isset($_POST['is_vancl'])) {
                        $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $this->base.'/carts/check_shipping';
                    } else {
                        $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $this->base.'/carts/checkout';
                    }
                }
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="'.$url.'"</script>';
                die();
            } else {
                $this->layout = 'ajax';
                $cart_address=isset($_SESSION['checkout']['address'])?$_SESSION['checkout']['address']:array();
                $result['cart_address']=$cart_address;
                die(json_encode($result));
            }
        }else{
            $this->redirect('/carts/checkout');
        }
    }

    /**
     *加地址.
     */
    //加地址
    public function checkout_address_add()
    {
        $result = array();
        if ($this->RequestHandler->isPost()) {
            if (isset($_SESSION['User']['User']['id'])) {
                $address = (array) json_decode(StripSlashes($_POST['address']));
                $address['user_id'] = $_SESSION['User']['User']['id'];

                $this->UserAddress->save($address);
                $result['type'] = 0;
                $result['id'] = $this->UserAddress->id;
                //	$save_cookie = $_SESSION['checkout'];unset($save_cookie['products']);unset($save_cookie['promotion']['products']);//$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
            } else {
                $result['type'] = 1;
                $result['message'] = $this->ld['time_out_relogin'];
            }
        }
        if (isset($_SESSION['checkout']['shipping']['shipping_fee'])) {
            unset($_SESSION['checkout']['shipping']['shipping_fee']);
            $save_cookie = $_SESSION['checkout'];
            unset($save_cookie['products']);
            unset($save_cookie['promotion']['products']);
        }
        $this->set('checkout', $_SESSION['checkout']);
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    /**
     *加地址.
     *
     *@param $id
     *@param $weight
     *@param $is_ajax
     *
     *@return $null or $shippings
     */
    public function show_shipping_by_address($weight, $is_ajax = 1){
	$ShippingArea_conditions=array();
	$address_id=isset($_SESSION['checkout']['address']['id'])?$_SESSION['checkout']['address']['id']:0;
	$user_id=isset($_SESSION['User']['User'])?$_SESSION['User']['User']['id']:0;
	$address = $this->UserAddress->find('first',array('conditions'=>array('UserAddress.id'=>$address_id,'UserAddress.user_id'=>$user_id)));
	if(!empty($address)){
		$region_ids = explode(" ",trim($address['UserAddress']['regions']));
		$shipping_area_region_ids = $this->ShippingAreaRegion->find_shipping_area_region_ids($region_ids);//model调用
		$shipping_area_regions =  $this->ShippingAreaRegion->find_shipping_area_regions($shipping_area_region_ids);//model调用
		$shipping_area_region_lists = array();
		if(is_array($shipping_area_regions) && sizeof($shipping_area_regions) > 0 ) {
			foreach($shipping_area_regions as $k=>$v)$shipping_area_region_lists[$v['ShippingAreaRegion']['id']] = $v;
		}
		foreach($shipping_area_region_ids as $shipping_area_region_id) {
			if(!isset($shipping_area_region_lists[$shipping_area_region_id]))continue;
			$shipping_area_region =  $shipping_area_region_lists[$shipping_area_region_id];
			$shipping_area_ids[$shipping_area_region_id] = $shipping_area_region['ShippingAreaRegion']['shipping_area_id'];
		}
		$ShippingArea_conditions['ShippingArea.id']=$shipping_area_ids;
	}
        $shipping_areas = $this->ShippingArea->find('all', array('conditions'=>$ShippingArea_conditions,'order' => 'shipping_id,orderby'));
        $shipping_areas_distinct = array();
        $shipping_ids = array();
        if (isset($shipping_areas)) {
            foreach ($shipping_areas as $k => $v) {
                $shipping_ids[$v['ShippingArea']['shipping_id']] = $v['ShippingArea']['shipping_id'];
                $shipping_areas_distinct[$v['ShippingArea']['shipping_id']] = $v['ShippingArea'];
            }
        }
        $shippings_arr = $this->Shipping->find('all', array('conditions' => array('Shipping.status' => '1', 'Shipping.id' => $shipping_ids), 'order' => 'Shipping.orderby asc'));
        if (isset($shippings_arr) && sizeof($shippings_arr) > 0) {
            $shippings = array();
            foreach ($shippings_arr as $k => $v) {
                $shippings[$v['Shipping']['id']] = $v;
            }
            foreach ($shippings as $k => $v) {
                //  $shippings[$k]['ShippingArea'] => 改为二
                $shippings[$k]['ShippingArea'] = $shipping_areas_distinct[$v['Shipping']['id']];
                //		if($v['Shipping']['code'] == 'usps'){
                //			$php_code = unserialize(StripSlashes($v['Shipping']['php_code']));
                //		    $shippings[$k]['ShippingArea']['fee'] =	$this->Shipping->USPSParcelRate($weight,$address['UserAddress']['zipcode'],$php_code['Usps']['value'],$php_code['Password']['value']);
                //		}else{
                $shippings[$k]['ShippingArea']['fee'] = $this->ShippingArea->fee_calculation($weight, $shipping_areas_distinct[$v['Shipping']['id']], $_SESSION['checkout']['cart_info']['sum_subtotal']);
                //			}
            }
            //单独商品的 运费
            if (isset($_SESSION['checkout']['products']) && $this->configs['use_product_shipping_fee'] == 1) {
                foreach ($_SESSION['checkout']['products'] as $k => $v) {
                    $shipping_sql = " ProductShippingFee.status = '1'  and ProductShippingFee.product_id = ".$v['Product']['id'];
                    if (isset($this->configs['mlti_currency_module']) && $this->configs['mlti_currency_module'] == 1) {
                        $shipping_sql .= " and ProductShippingFee.locale = '".LOCALE."'";
                    }

                    $fee_info = $this->ProductShippingFee->find('all', array('conditions' => $shipping_sql));
                    if (is_array($fee_info) && sizeof($fee_info) > 0) {
                        foreach ($fee_info as $k => $v) {
                            if (isset($shippings[$v['ProductShippingFee']['shipping_id']])) {
                                $shippings[$v['ProductShippingFee']['shipping_id']]['ShippingArea']['fee'] += $v['ProductShippingFee']['shipping_fee'];
                                if (isset($shippings[$v['ProductShippingFee']['shipping_id']]['ProductShippingFee']['shipping_fee'])) {
                                    $shippings[$v['ProductShippingFee']['shipping_id']]['ProductShippingFee']['shipping_fee'] += $v['ProductShippingFee']['shipping_fee'];
                                } else {
                                    $shippings[$v['ProductShippingFee']['shipping_id']]['ProductShippingFee']['shipping_fee'] = $v['ProductShippingFee']['shipping_fee'];
                                }
                            }
                        }
                    }
                }
            }
            if (isset($shippings) && sizeof($shippings) == 1) {
                foreach ($shippings as $s => $p) {
                    $_SESSION['checkout']['shipping'] = array(
                        'shipping_id' => $shippings[$s]['Shipping']['id'],
                        'shipping_code' => $shippings[$s]['Shipping']['code'],
                        'shipping_fee' => $shippings[$s]['ShippingArea']['fee'],
                        'shipping_name' => $shippings[$s]['ShippingI18n']['name'],
                        'free_subtotal' => $shippings[$s]['ShippingArea']['free_subtotal'],
                        'support_cod' => $shippings[$s]['Shipping']['support_cod'],
                        'insure_fee' => $shippings[$s]['Shipping']['insure_fee'],
                        'not_show_change' => '1',
                        'shipping_description' => $shippings[$s]['ShippingI18n']['description'],
                    );
                }
                if ($_SESSION['checkout']['shipping']['free_subtotal'] > 0 && $_SESSION['checkout']['shipping']['free_subtotal'] < $_SESSION['checkout']['cart_info']['sum_subtotal']) {
                    $_SESSION['checkout']['shipping']['shipping_fee'] = 0;
                } else {
                    // $_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['shipping']['shipping_fee'];
                }
                $save_cookie = $_SESSION['checkout'];
                unset($save_cookie['products']);
                unset($save_cookie['promotion']['products']);
                //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
            } else {
                $_SESSION['checkout']['shipping']['not_show_change'] = '0';
            }
            $this->set('shippings', $shippings);
        } else {
            $this->set('shippings', 'nothing');
        }
        if ($is_ajax == 0) {
            if (isset($shippings)) {
                return $shippings;
            } else {
                return;
            }
        }
    }

    /**
     *确认付款.
     *
     *@param $p_id
     */
    public function confirm_payment($p_id = '', $return = '')
    {
        if ($p_id != '') {
            $is_ajax = 0;
            $_POST['payment_id'] = $p_id;
        } else {
            $is_ajax = 1;
        }
        //header("Cache-Control: no-cache, must-revalidate");
        $result = array();
        if (isset($_SESSION['User']['User']['id'])) {
            //	$payment_total = $_POST;  ??
            $shipping_fee = 0;
            //	if(isset($_SESSION['checkout']['shipping']['shipping_fee'])){
            //		$shipping_fee = $_SESSION['checkout']['shipping']['shipping_fee'];
            //	}
            //	$_SESSION['checkout']['cart_info']['payment_fee'] = $_SESSION['checkout']['payment']['payment_fee'];
            //	$_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['payment']['payment_fee']+$_SESSION['checkout']['cart_info']['sum_subtotal']+$shipping_fee;
            /* 该判断增加了全部购买物品为虚拟物品的判断 */
            //||( $_POST['is_cod']>0 && $_SESSION['checkout']['cart_info']['all_virtual'])
            $payment = $this->Payment->findbyid($_POST['payment_id']);
            if ($payment['Payment']['is_cod'] > 0 && $_SESSION['checkout']['cart_info']['all_virtual']) {
                $result['type'] = 1;
                $result['message'] = $this->ld['payment_no_support'];
            } elseif ((isset($_SESSION['checkout']['shipping']['support_cod']) && $payment['Payment']['is_cod'] == 1 && $_SESSION['checkout']['shipping']['support_cod'] == 0)) {
                $result['type'] = 1;
                $result['message'] = $this->ld['payment_no_support'];
            } elseif ($payment['Payment']['code'] == 'account_pay') {
                $user_info = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
                if ($_SESSION['checkout']['cart_info']['total'] <= $user_info['User']['balance']) {
                    $_SESSION['checkout']['payment']['payment_id'] = $payment['Payment']['id'];
                    $_SESSION['checkout']['payment']['payment_fee'] = $payment['Payment']['fee'];
                    $_SESSION['checkout']['payment']['payment_name'] = $payment['PaymentI18n']['name'];
                    $_SESSION['checkout']['payment']['payment_description'] = $payment['PaymentI18n']['description'];
                    $_SESSION['checkout']['payment']['is_cod'] = $payment['Payment']['is_cod'];
                    $_SESSION['checkout']['payment']['code'] = $payment['Payment']['code'];
                    $_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['payment']['payment_fee'];
                    $save_cookie = $_SESSION['checkout'];
                    unset($save_cookie['products']);
                    unset($save_cookie['promotion']['products']);
                    //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
                    $result['type'] = 0;
                    $this->set('checkout', $_SESSION['checkout']);
                } else {
                    $result['type'] = 1;
                    $result['message'] = $this->ld['lack_balance_supply_first'];
                }
            } else {
                $_SESSION['checkout']['payment']['payment_id'] = $payment['Payment']['id'];
                $_SESSION['checkout']['payment']['payment_fee'] = $payment['Payment']['fee'];
                $_SESSION['checkout']['payment']['payment_name'] = $payment['PaymentI18n']['name'];
                $_SESSION['checkout']['payment']['payment_description'] = $payment['PaymentI18n']['description'];
                $_SESSION['checkout']['payment']['is_cod'] = $payment['Payment']['is_cod'];
                $_SESSION['checkout']['payment']['code'] = $payment['Payment']['code'];
                $_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['payment']['payment_fee'];
                $save_cookie = $_SESSION['checkout'];
                unset($save_cookie['products']);
                unset($save_cookie['promotion']['products']);
                //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
                $result['type'] = 0;
                $this->set('checkout', $_SESSION['checkout']);
            }
        }
        if ($return) {
            if ($result['type'] = 0) {
                return true;
            } else {
                return false;
            }
        }
        $this->order_price();
        $this->get_point_and_coupon();
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    /**
     *改变运送方式.
     */
    public function change_shipping()
    {
        //header("Cache-Control: no-cache, must-revalidate");
        if (isset($_SESSION['User']['User']['id'])) {
            //echo $this->webroot;die();
            if (isset($_SESSION['checkout']['shipping']['shipping_fee'])) {
                if ($_SESSION['checkout']['shipping']['free_subtotal'] > 0 && $_SESSION['checkout']['shipping']['free_subtotal'] < $_SESSION['checkout']['cart_info']['sum_subtotal']) {
                    $_SESSION['checkout']['shipping']['shipping_fee'] = 0;
                    $_SESSION['checkout']['cart_info']['total'] -=  isset($_SESSION['checkout']['shipping']['insure_fee_confirm']) ? $_SESSION['checkout']['shipping']['insure_fee_confirm'] : 0;
                } else {
                    $_SESSION['checkout']['cart_info']['total'] -= $_SESSION['checkout']['shipping']['shipping_fee'];
                    $_SESSION['checkout']['cart_info']['total'] -=  isset($_SESSION['checkout']['shipping']['insure_fee_confirm']) ? $_SESSION['checkout']['shipping']['insure_fee_confirm'] : 0;
                }
                //$_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['shipping']['free_subtotal'];
                unset($_SESSION['checkout']['shipping']);
                $save_cookie = $_SESSION['checkout'];
                unset($save_cookie['products']);
                unset($save_cookie['promotion']['products']);
                //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
            }
            if (isset($_SESSION['checkout']['address'])) {
                if (isset($_SESSION['checkout']['products']) && sizeof($_SESSION['checkout']['products']) > 0) {
                    $weight = 0;
                    foreach ($_SESSION['checkout']['products'] as $k => $v) {
                        $weight += $v['Product']['weight'];
                    }
                }
                $this->show_shipping_by_address($weight); //change_shipping
                $result['type'] = 0;
            } else {
                $result['type'] = 1;
                $result['message'] = $this->ld['no_shipping_method'];
            }
        }
        $this->order_price();
        $this->get_point_and_coupon();
        $this->set('checkout', $_SESSION['checkout']);
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    /**
     *改变付款方式.
     */
    public function change_payment()
    {
        //header("Cache-Control: no-cache, must-revalidate");
        if (isset($_SESSION['User']['User']['id'])) {
            if (isset($_SESSION['checkout']['payment']['payment_fee'])) {
                $_SESSION['checkout']['cart_info']['total'] -= $_SESSION['checkout']['payment']['payment_fee'];
                unset($_SESSION['checkout']['payment']);
            }
            $payments = $this->Payment->availables();
            if (isset($payments)) {
                $result['type'] = 0;
                $this->set('payments', $payments);
            } else {
                $result['type'] = 1;
                $result['message'] = $this->ld['no_paying_method'];
            }
        }
        if (isset($_SESSION['checkout']['payment']['payment_fee'])) {
            unset($_SESSION['checkout']['payment']);
            $save_cookie = $_SESSION['checkout'];
            unset($save_cookie['products']);
            unset($save_cookie['promotion']['products']);
            //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
        }
        $this->order_price();
        $this->get_point_and_coupon();
        $this->set('checkout', $_SESSION['checkout']);
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    /**
     *补充说明.
     */
    public function add_note()
    {
        //header("Cache-Control: no-cache, must-revalidate");
        if ($this->RequestHandler->isPost()) {
            if ($_POST['type'] == 'product') {
                $_SESSION['checkout']['products'][$_POST['id']]['Product']['note'] = $_POST['note'];
                $result['type'] = 0;
            }
            if ($_POST['type'] == 'packaging') {
                $_SESSION['checkout']['packagings'][$_POST['id']]['Packaging']['note'] = $_POST['note'];
                $result['type'] = 0;
            }
            if ($_POST['type'] == 'card') {
                $_SESSION['checkout']['cards'][$_POST['id']]['Card']['note'] = $_POST['note'];
                $result['type'] = 0;
            }
        }
        $this->get_point_and_coupon();
        $this->set('result', $result);
        $this->set('checkout', $_SESSION['checkout']);
        $this->layout = 'ajax';
    }

    /**
     *查询促销.
     *
     *@param $id
     */
    public function findpromotions($id = '')
    {
        if (!empty($_SESSION['svcart']['cart_info']['sum_subtotal'])) {
            $info_subtotal = $_SESSION['svcart']['cart_info']['sum_subtotal'];
            //  $info_total = $_SESSION['svcart']['cart_info']['total'];
            $conditions = "1=1 and Promotion.status = '1' ";
            $now_time = date('Y-m-d H:i:s');
            $conditions .= " and Promotion.start_time <= '".$now_time."'";
            $conditions .= " and Promotion.end_time >= '".$now_time."'";
            $conditions .= " and ((Promotion.min_amount <= $info_subtotal ";
            $conditions .= " and Promotion.max_amount >= $info_subtotal )";
            $conditions .= " or (Promotion.max_amount = '0' and Promotion.min_amount <= $info_subtotal))";
            if ($id != '') {
                $conditions .= " and Promotion.id = $id ";
            }
            $this->Promotion->set_locale($this->locale);
            //$promotions = $this->Promotion->findall($conditions,"","Promotion.orderby asc");
            $promotions = $this->Promotion->find_promotions($conditions);//model调用
            //特惠品信息
            if (isset($promotions) && count($promotions) > 0) {
                foreach ($promotions as $k => $v) {
                    //取关联产品
                    $related_product_ids = $this->PromotionActivityProduct->find('list', array('conditions' => array('PromotionActivityProduct.promotion_id' => $v['Promotion']['id'], 'PromotionActivityProduct.status' => 1), 'fields' => array('PromotionActivityProduct.product_id')));
                    $available = false;
                    $related_product_taotal = 0;
                    $mult = 1;//最小金额倍数
                    if (!empty($related_product_ids)) {
                        foreach ($_SESSION['svcart']['products'] as $vv) {
                            if (in_array($vv['Product']['id'], $related_product_ids)) {
                                $available = true;
                                $related_product_taotal += $vv['Product']['shop_price'] * $vv['quantity'];
                            }
                        }
                        if ($v['Promotion']['min_amount'] > 0) {
                            $mult = floor($related_product_taotal / $v['Promotion']['min_amount']);
                        }
                        if ($v['Promotion']['min_amount'] > 0 && $related_product_taotal < $v['Promotion']['min_amount']) {
                            //echo $related_product_taotal . "-".$v['Promotion']['min_amount']."<br/>";
                            $available = false;
                        }
                        if ($v['Promotion']['max_amount'] > 0 && $related_product_taotal > $v['Promotion']['max_amount']) {
                            $available = false;
                        }
                    } else {
                        $available = true;
                    }
                    //if($)todo
                    if ($available == true) {
                        $promotions[$k]['related_product_ids'] = $related_product_ids;
                        $promotions[$k]['pro_ids'] = array();
                        if ($v['Promotion']['type'] == 2) {
                            //特惠品
                            $PromotionProducts[$k] = $this->PromotionProduct->findallbypromotion_id($v['Promotion']['id']);
                            if (isset($PromotionProducts[$k]) && count($PromotionProducts[$k]) > 0) {
                                $pro_ids = array();
                                foreach ($PromotionProducts[$k] as $key => $value) {
                                    $pro_ids[] = $value['PromotionProduct']['product_id'];
                                }
                                $promotions[$k]['pro_ids'] = $pro_ids;
                                if (!empty($pro_ids)) {
                                    $this->Product->set_locale($this->locale);
                                    $pro_products = $this->Product->find_pro_products($pro_ids);//model调用
                                    $pro_products_list = array();
                                    if (isset($pro_products) && sizeof($pro_products) > 0) {
                                        foreach ($pro_products as $kk => $vv) {
                                            $pro_products_list[$vv['Product']['id']] = $vv;
                                        }
                                    }
                                }
                                foreach ($PromotionProducts[$k] as $key => $value) {
                                    if (isset($pro_products_list[$value['PromotionProduct']['product_id']])) {
                                        $promotions[$k]['products'][$value['PromotionProduct']['product_id']] = $pro_products_list[$value['PromotionProduct']['product_id']];
                                        $promotions[$k]['products'][$value['PromotionProduct']['product_id']]['Product']['now_fee'] = $value['PromotionProduct']['price'];
                                    }
                                    //	$promotions[$k]['products'][$value['PromotionProduct']['product_id']] = $this->Product->findbyid($value['PromotionProduct']['product_id']);
                                }
                            }
                        } elseif ($v['Promotion']['type'] == 0) {
                            //减免
                            //$promotions[$k]['Promotion']['type_ext'] = $promotions[$k]['Promotion']['type_ext']*$mult;
                            $promotions[$k]['Promotion']['type_ext'] = $promotions[$k]['Promotion']['type_ext'];
                        }
                    } else {
                        unset($promotions[$k]);
                    }
                }
            }
            return $promotions;
        }
    }

    /**
     *查询促销.
     *
     *@param $id
     */
    public function checkout_findpromotions($id = '')
    {
        if (!empty($_SESSION['checkout']['cart_info']['sum_subtotal'])) {
            $info_subtotal = $_SESSION['checkout']['cart_info']['sum_subtotal'];
            //  $info_total = $_SESSION['checkout']['cart_info']['total'];
            $conditions = "1=1 and Promotion.status = '1' ";
            $now_time = date('Y-m-d H:i:s');
            $conditions .= " and Promotion.start_time <= '".$now_time."'";
            $conditions .= " and Promotion.end_time >= '".$now_time."'";
            $conditions .= " and ((Promotion.min_amount <= $info_subtotal ";
            $conditions .= " and Promotion.max_amount >= $info_subtotal )";
            $conditions .= " or (Promotion.max_amount = '0' and Promotion.min_amount <= $info_subtotal))";
            if ($id != '') {
                $conditions .= " and Promotion.id = $id ";
            }
            $this->Promotion->set_locale($this->locale);
            //$promotions = $this->Promotion->findall($conditions,"","Promotion.orderby asc");
            $promotions = $this->Promotion->find_promotions($conditions);//model调用
            //特惠品信息
            if (isset($promotions) && count($promotions) > 0) {
                foreach ($promotions as $k => $v) {
                    //取关联产品
                    $related_product_ids = $this->PromotionActivityProduct->find('list', array('conditions' => array('PromotionActivityProduct.promotion_id' => $v['Promotion']['id'], 'PromotionActivityProduct.status' => 1), 'fields' => array('PromotionActivityProduct.product_id')));
                    $available = false;
                    $related_product_taotal = 0;
                    $mult = 1;//最小金额倍数
                    if (!empty($related_product_ids)) {
                        foreach ($_SESSION['checkout']['products'] as $vv) {
                            if (in_array($vv['Product']['id'], $related_product_ids)) {
                                $available = true;
                                $related_product_taotal += $vv['Product']['shop_price'] * $vv['quantity'];
                            }
                        }
                        if ($v['Promotion']['min_amount'] > 0) {
                            $mult = floor($related_product_taotal / $v['Promotion']['min_amount']);
                        }

                        if ($v['Promotion']['min_amount'] > 0 && $related_product_taotal < $v['Promotion']['min_amount']) {
                            //echo $related_product_taotal . "-".$v['Promotion']['min_amount']."<br/>";
                            $available = false;
                        }
                        if ($v['Promotion']['max_amount'] > 0 && $related_product_taotal > $v['Promotion']['max_amount']) {
                            $available = false;
                        }
                    } else {
                        $available = true;
                    }
                    //if($)todo
                    if ($available == true) {
                        $promotions[$k]['related_product_ids'] = $related_product_ids;
                        $promotions[$k]['pro_ids'] = array();
                        if ($v['Promotion']['type'] == 2) {
                            //特惠品
                            $PromotionProducts[$k] = $this->PromotionProduct->findallbypromotion_id($v['Promotion']['id']);
                            if (isset($PromotionProducts[$k]) && count($PromotionProducts[$k]) > 0) {
                                $pro_ids = array();
                                foreach ($PromotionProducts[$k] as $key => $value) {
                                    $pro_ids[] = $value['PromotionProduct']['product_id'];
                                }
                                $promotions[$k]['pro_ids'] = $pro_ids;
                                if (!empty($pro_ids)) {
                                    $this->Product->set_locale($this->locale);
                                    $pro_products = $this->Product->find_pro_products($pro_ids);//model调用
                                    $pro_products_list = array();
                                    if (isset($pro_products) && sizeof($pro_products) > 0) {
                                        foreach ($pro_products as $kk => $vv) {
                                            $pro_products_list[$vv['Product']['id']] = $vv;
                                        }
                                    }
                                }
                                foreach ($PromotionProducts[$k] as $key => $value) {
                                    if (isset($pro_products_list[$value['PromotionProduct']['product_id']])) {
                                        $promotions[$k]['products'][$value['PromotionProduct']['product_id']] = $pro_products_list[$value['PromotionProduct']['product_id']];
                                        $promotions[$k]['products'][$value['PromotionProduct']['product_id']]['Product']['now_fee'] = $value['PromotionProduct']['price'];
                                    }
                                    //	$promotions[$k]['products'][$value['PromotionProduct']['product_id']] = $this->Product->findbyid($value['PromotionProduct']['product_id']);
                                }
                            }
                        } elseif ($v['Promotion']['type'] == 0) {
                            //减免
                            //$promotions[$k]['Promotion']['type_ext'] = $promotions[$k]['Promotion']['type_ext']*$mult;
                            $promotions[$k]['Promotion']['type_ext'] = $promotions[$k]['Promotion']['type_ext'];
                        }
                    } else {
                        unset($promotions[$k]);
                    }
                }
            }
            return $promotions;
        }
    }

    /**
     *确认推广
     *
     *@param $set_promotion
     */
    public function confirm_promotion($set_promotion = '')
    {
        //header("Cache-Control: no-cache, must-revalidate");
        if ($set_promotion != '') {
            $is_ajax = 0;
            $_POST = $set_promotion;
        } else {
            $is_ajax = 1;
        }
        //	if($this->RequestHandler->isPost()){
        if ($_POST['type'] == 0) {
            $_SESSION['checkout']['cart_info']['total'] -= $_POST['type_ext'];
            $result['type'] = 0;
        }
        if ($_POST['type'] == 1) {
            if (isset($_SESSION['checkout']['payment']['payment_fee'])) {
                $_SESSION['checkout']['cart_info']['total'] -= $_SESSION['checkout']['payment']['payment_fee'];
            }
            if (isset($_SESSION['checkout']['shipping']['shipping_fee'])) {
                $_SESSION['checkout']['cart_info']['total'] -= $_SESSION['checkout']['shipping']['shipping_fee'];
            }
            if (isset($_SESSION['checkout']['point']['fee'])) {
                $_SESSION['checkout']['cart_info']['total'] -= $_SESSION['checkout']['point']['fee'];
            }
            $_SESSION['checkout']['cart_info']['old_total'] = $_SESSION['checkout']['cart_info']['total'];
            $_SESSION['checkout']['cart_info']['total'] = round($_SESSION['checkout']['cart_info']['total'] * $_POST['type_ext'] / 100, 2);
            if (isset($_SESSION['checkout']['payment']['payment_fee'])) {
                $_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['payment']['payment_fee'];
            }
            if (isset($_SESSION['checkout']['shipping']['shipping_fee'])) {
                $_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['shipping']['shipping_fee'];
            }
            $result['type'] = 0;
        }
        $save_cookie = $_SESSION['checkout'];
        unset($save_cookie['products']);
        unset($save_cookie['promotion']['products']);
        //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
        $result['promotion']['title'] = $_POST['title'];
        $result['promotion']['type'] = $_POST['type'];
        $result['promotion']['promotion_fee'] = $_POST['type_ext'];
        $result['promotion']['meta_description'] = $_POST['meta_description'];
        $_SESSION['checkout']['promotion'] = $result['promotion'];
        //	$_SESSION['checkout']['promotion']['promotion_fee'] = $_POST['type_ext'];
//		}
        $this->set('checkout', $_SESSION['checkout']);
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    /**
     *改变推广
     */
    public function change_promotion()
    {
        //header("Cache-Control: no-cache, must-revalidate");
        if ($this->RequestHandler->isPost()) {
            $promotions = $this->findpromotions();
            if (isset($promotions) && sizeof($promotions) > 0) {
                foreach ($promotions as $k => $v) {
                    if ($v['Promotion']['type'] == '2' && isset($v['products']) && sizeof($v['products']) > 0) {
                        foreach ($v['products'] as $a => $b) {
                            $promotions_product_id[] = $b['Product']['id'];
                        }
                    }
                }
            }
            if (isset($promotions_product_id) && !empty($promotions_product_id)) {
                $promotion_product_attribute = $this->ProductAttribute->find_promotion_product_attribute($promotions_product_id);//model调用
            }
            $product_type_atts = $this->Attribute->find_all_att(LOCALE);
            $format_product_attributes = array();
            $product_attributes_name = array();
            $format_product_attributes_id = array();
            $promotion_product_attribute_lists = array();
            if (is_array($promotion_product_attribute) && sizeof($promotion_product_attribute) > 0) {
                foreach ($promotion_product_attribute as $k => $v) {
                    $promotion_product_attribute_lists[$v['ProductAttribute']['product_id']][$product_type_atts[$v['ProductAttribute']['attribute_id']]['AttributeI18n']['name']][] = $v;
                }
            }
            $this->set('promotion_product_attribute_lists', $promotion_product_attribute_lists);
            $result['type'] = 3;
            if (isset($_SESSION['checkout']['promotion']['type'])) {
                if ($_SESSION['checkout']['promotion']['type'] == 0) {
                    $_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['promotion']['promotion_fee'];
                    $result['type'] = 0;
                }
                if ($_SESSION['checkout']['promotion']['type'] == 1) {
                    $_SESSION['checkout']['cart_info']['total'] = $_SESSION['checkout']['cart_info']['old_total'];
                    if (isset($_SESSION['checkout']['payment']['payment_fee'])) {
                        $_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['payment']['payment_fee'];
                    }
                    if (isset($_SESSION['checkout']['shipping']['shipping_fee'])) {
                        $_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['shipping']['shipping_fee'];
                    }
                    if (isset($_SESSION['checkout']['point']['fee'])) {
                        $_SESSION['checkout']['cart_info']['total'] -= $_SESSION['checkout']['point']['fee'];
                    }
                    unset($_SESSION['checkout']['cart_info']['old_total']);
                    $result['type'] = 0;
                }
                if ($_SESSION['checkout']['promotion']['type'] == 2) {
                    if (isset($_SESSION['checkout']['Product_by_Promotion'])) {
                        $_SESSION['checkout']['promotion']['product_fee'] = 0;
                        foreach ($_SESSION['checkout']['Product_by_Promotion'] as $kkk => $vvv) {
                            $_SESSION['checkout']['cart_info']['total'] -= $vvv['Product']['now_fee'];
                        }
                    }
                    unset($_SESSION['checkout']['Product_by_Promotion']);
                    $result['type'] = 0;
                }
            } else {
                $result['type'] = 3;
            }
        }
        unset($_SESSION['checkout']['promotion']);
        $save_cookie = $_SESSION['checkout'];
        unset($save_cookie['products']);
        unset($save_cookie['promotion']['products']);
        //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
        /* 判断是否需要显示配送方式 */
        if ((isset($_SESSION['checkout']['cart_info']['all_virtual']) && $_SESSION['checkout']['cart_info']['all_virtual'] == 0)
            || (isset($_SESSION['checkout']['promotion']['all_virtual']) && $_SESSION['checkout']['promotion']['all_virtual'] == 0)) {
            $result['shipping_display'] = 1;
        } else {
            $result['shipping_display'] = 0;
        }
        $this->set('promotions', $promotions);
        $this->set('checkout', $_SESSION['checkout']);
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    /**
     *改变地址.
     */
    public function change_address()
    {
        //header("Cache-Control: no-cache, must-revalidate");
        //Configure::write('debug',2);
        if (isset($_SESSION['User']['User']['id'])) {
            if ($this->RequestHandler->isPost()) {
                $addresses_count = $this->UserAddress->find_count_addresses($_SESSION['User']['User']['id']);//model调用
                if ($addresses_count == 1) {
                    $result['type'] = 3;
                } else {
                    $addresses = $this->UserAddress->findAllbyuser_id($_SESSION['User']['User']['id']);
                    foreach ($addresses as $key => $address) {
                        $region_array = explode(' ', trim($address['UserAddress']['regions']));
                        $addresses[$key]['UserAddress']['regions'] = '';
                        foreach ($region_array as $k => $region_id) {
                            $region_info = $this->Region->findbyid($region_id);
                            if ($k < sizeof($region_array) - 1) {
                                $addresses[$key]['UserAddress']['regions'] .= $region_info['RegionI18n']['name'].' ';
                            } else {
                                $addresses[$key]['UserAddress']['regions'] .= $region_info['RegionI18n']['name'];
                            }
                        }
                    }
                    $result['type'] = 0;
                    $this->set('addresses', $addresses);
                    unset($_SESSION['checkout']['address']);
                    $save_cookie = $_SESSION['checkout'];
                    unset($save_cookie['products']);
                    unset($save_cookie['promotion']['products']);
                    //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
                    if (isset($_SESSION['checkout']['shipping']['shipping_fee'])) {
                        $_SESSION['checkout']['cart_info']['total'] -= $_SESSION['checkout']['shipping']['shipping_fee'];
                        unset($_SESSION['checkout']['shipping']);
                        $save_cookie = $_SESSION['checkout'];
                        unset($save_cookie['products']);
                        unset($save_cookie['promotion']['products']);
                        //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
                    }
                }
                $this->set('checkout', $_SESSION['checkout']);
                $this->set('result', $result);
                $this->layout = 'ajax';
            }
        }
    }

    /**
     *选优惠品.
     *
     *@param $promotion_arr
     */
    //选优惠品
    public function add_promotion_product($promotion_arr = '')
    {
        if ($promotion_arr != '') {
            $is_ajax = 0;
            $_POST = $promotion_arr;
        } else {
            $is_ajax = 1;
        }
        //header("Cache-Control: no-cache, must-revalidate");
        //	if(isset($_SESSION['User']['User']['id'])){
        //	if($this->RequestHandler->isPost()){
        $result['type'] = 2;
        $promotions = $this->findpromotions($_POST['promotion_id']);
        foreach ($promotions as $k => $v) {
            if ($v['Promotion']['id'] == $_POST['promotion_id']) {
                $result['promotion'] = $v;
                $result['promotion']['id'] = $v['Promotion']['id'];
                $result['promotion']['title'] = $v['PromotionI18n']['title'];
                $result['promotion']['meta_description'] = $v['PromotionI18n']['meta_description'];
                $result['promotion']['type'] = $v['Promotion']['type'];
                $_SESSION['checkout']['promotion'] = $result['promotion'];
                if (isset($_SESSION['checkout']['Product_by_Promotion']) && count($_SESSION['checkout']['Product_by_Promotion']) > 0) {
                    foreach ($_SESSION['checkout']['Product_by_Promotion'] as $kk => $vv) {
                        unset($result['promotion']['products'][$vv['Product']['id']]);
                    }
                }
                if (isset($_SESSION['checkout']['Product_by_Promotion'])) {
                    if (count($_SESSION['checkout']['Product_by_Promotion']) + 1 <= $v['Promotion']['type_ext']) {
                        foreach ($v['products'] as $key => $value) {
                            if ($value['Product']['id'] == $_POST['product_id']) {
                                $_SESSION['checkout']['Product_by_Promotion'][$key] = $value;
                                $_SESSION['checkout']['Product_by_Promotion'][$key]['Product']['attr'] = isset($_POST['attr']) ? $_POST['attr'] : '';
                                $_SESSION['checkout']['cart_info']['total'] += $value['Product']['now_fee'];
                                unset($result['promotion']['products'][$key]);
                            } elseif (count($_SESSION['checkout']['Product_by_Promotion']) + 1 >= $v['Promotion']['type_ext']) {
                                unset($result['promotion']['products'][$key]);
                            }
                        }
                        if (isset($_SESSION['checkout']['Product_by_Promotion'])) {
                            $_SESSION['checkout']['promotion']['product_fee'] = 0;
                            $_SESSION['checkout']['promotion']['all_virtual'] = 1;//纯虚拟商品标记
                            foreach ($_SESSION['checkout']['Product_by_Promotion'] as $kkk => $vvv) {
                                $_SESSION['checkout']['promotion']['product_fee'] += $vvv['Product']['now_fee'];
                                $result['type'] = 0;
                                //$save_cookie = $_SESSION['checkout'];unset($save_cookie['products']);unset($save_cookie['promotion']['products']);//$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
                                if (empty($vvv['Product']['extension_code'])) {
                                    $_SESSION['checkout']['promotion']['all_virtual'] = 0;//纯虚拟商品标记
                                }
                            }
                        }
                    } else {
                        $result['type'] = 3;
                    }
                } else {
                    foreach ($v['products'] as $key => $value) {
                        if ($value['Product']['id'] == $_POST['product_id']) {
                            $_SESSION['checkout']['Product_by_Promotion'][$key] = $value;
                            $_SESSION['checkout']['Product_by_Promotion'][$key]['Product']['attr'] = isset($_POST['attr']) ? $_POST['attr'] : '';
                            $_SESSION['checkout']['cart_info']['total'] += $value['Product']['now_fee'];
                            unset($result['promotion']['products'][$key]);
                        } elseif (1 >= $v['Promotion']['type_ext']) {
                            unset($result['promotion']['products'][$key]);
                        }
                    }
                    if (isset($_SESSION['checkout']['Product_by_Promotion'])) {
                        $_SESSION['checkout']['promotion']['product_fee'] = 0;
                        $_SESSION['checkout']['promotion']['all_virtual'] = 1;//纯虚拟商品标记
                        foreach ($_SESSION['checkout']['Product_by_Promotion'] as $kkk => $vvv) {
                            $_SESSION['checkout']['promotion']['product_fee'] += $vvv['Product']['now_fee'];
                            $result['type'] = 0;
                            //	$save_cookie = $_SESSION['checkout'];unset($save_cookie['products']);unset($save_cookie['promotion']['products']);//$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);

                            if (empty($vvv['Product']['extension_code'])) {
                                $_SESSION['checkout']['promotion']['all_virtual'] = 0;
                            }//纯虚拟商品标记
                        }
                    }
                }
            }
            if ($v['Promotion']['type'] == '2' && isset($v['products']) && sizeof($v['products']) > 0) {
                foreach ($v['products'] as $a => $b) {
                    $promotions_product_id[] = $b['Product']['id'];
                }
            }
        }
        if (isset($promotions_product_id) && !empty($promotions_product_id)) {
            $promotion_product_attribute = $this->ProductAttribute->find_promotion_product_attribute($promotions_product_id);//model调用
        }
        $product_type_atts = $this->Attribute->find_all_att(LOCALE);
        $format_product_attributes = array();
        $product_attributes_name = array();
        $format_product_attributes_id = array();
        $promotion_product_attribute_lists = array();
        if (is_array($promotion_product_attribute) && sizeof($promotion_product_attribute) > 0) {
            foreach ($promotion_product_attribute as $k => $v) {
                $promotion_product_attribute_lists[$v['ProductAttribute']['product_id']][$product_type_atts[$v['ProductAttribute']['attribute_id']]['AttributeI18n']['name']][] = $v;
            }
        }
        $this->set('promotion_product_attribute_lists', $promotion_product_attribute_lists);
        /* 判断是否需要显示配送方式 */
        if ((isset($_SESSION['checkout']['cart_info']['all_virtual']) && $_SESSION['checkout']['cart_info']['all_virtual'] == 0)
            || (isset($_SESSION['checkout']['promotion']['all_virtual']) && $_SESSION['checkout']['promotion']['all_virtual'] == 0)) {
            $result['shipping_display'] = 1;
        } else {
            $result['shipping_display'] = 0;
        }
        $_SESSION['checkout']['promotion']['products'] = $result['promotion']['products'];
        $this->set('checkout', $_SESSION['checkout']);
        //	}
        //    }
        $promotions = $this->findpromotions();
        $this->set('promotions', $promotions);
        if (isset($promotions) && sizeof($promotions) > 0) {
            $promotions_product_id = array();
            foreach ($promotions as $k => $v) {
                if ($v['Promotion']['type'] == '2' && isset($v['products']) && sizeof($v['products']) > 0) {
                    foreach ($v['products'] as $a => $b) {
                        $promotions_product_id[] = $b['Product']['id'];
                    }
                }
            }
            if (!empty($promotions_product_id)) {
                $promotion_product_attribute = $this->ProductAttribute->find_promotion_product_attribute($promotions_product_id);//model调用
            }
            $product_type_atts = $this->Attribute->find_all_att(LOCALE);
            $promotion_product_attribute_lists = array();
            if (isset($promotion_product_attribute) && sizeof($promotion_product_attribute) > 0) {
                foreach ($promotion_product_attribute as $k => $v) {
                    //	if(isset($promotion_product_attribute_lists[$v['ProductAttribute']['product_id']])){
                    $promotion_product_attribute_lists[$v['ProductAttribute']['product_id']][$product_type_atts[$v['ProductAttribute']['attribute_id']]['AttributeI18n']['name']][] = $v;
                    //	}
                }
            }
            $this->set('promotion_product_attribute_lists', $promotion_product_attribute_lists);
        }
        $this->set('result', $result);
        //	$save_cookie = $_SESSION['checkout'];unset($save_cookie['products']);unset($save_cookie['promotion']['products']);//$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
        $this->layout = 'ajax';
    }

    /**
     *使用点.
     *
     *@param $po
     */
    public function usepoint($po = '')
    {
        $this->layout = 'ajax';
        Configure::write('debug',1);
        if ($po != '') {
            $is_ajax = 0;
            $_POST['point'] = $po;
        } else {
            $is_ajax = 1;
        }
        $result['type'] = 2;
        $result['msg'] = '使用积分失败';
        
        $order_type='';//交易类型
        if(isset($_SESSION['checkout']['cart_info']['sum_quantity'])&&$_SESSION['checkout']['cart_info']['sum_quantity']!='0'){//购物
        	$order_type='P';
        }else if(isset($_SESSION['checkout']['cart_info']['lease_quantity'])&&$_SESSION['checkout']['cart_info']['lease_quantity']!='0'){//租赁
        	$order_type='L';
        }
        if(isset($this->configs['use_point'])&&$this->configs['use_point']=='1'){
        	//使用积分支付比例，计算购买时可用积分范围
        	$cart_product_subtotal=!empty($_SESSION['checkout']['cart_info']['sum_subtotal'])?$_SESSION['checkout']['cart_info']['sum_subtotal']:(!empty($_SESSION['checkout']['cart_info']['lease_subtotal'])?$_SESSION['checkout']['cart_info']['lease_subtotal']:0);
		if (isset($_SESSION['checkout']['shipping']['shipping_fee']) && $_SESSION['checkout']['shipping']['shipping_fee'] != '') {
			$cart_product_subtotal = $this->fee_format($_SESSION['checkout']['shipping']['shipping_fee'], $cart_product_subtotal);
		}
		if (isset($_SESSION['checkout']['shipping']['insure_fee_confirm']) && $_SESSION['checkout']['shipping']['shipping_fee'] != '') {
			$cart_product_subtotal = $this->fee_format($_SESSION['checkout']['shipping']['insure_fee_confirm'], $cart_product_subtotal);
		}
		if (isset($_SESSION['checkout']['payment']['payment_fee']) && $_SESSION['checkout']['payment']['payment_fee'] != '') {
			$cart_payment_fee = $this->fee_format_no_price($_SESSION['checkout']['payment']['payment_fee'], $cart_product_subtotal);
			$cart_product_subtotal = $this->fee_format($cart_payment_fee,$cart_product_subtotal);
		}
		//如果有优惠券的话  chenfan 2012/05/30
		if (isset($_SESSION['checkout']['coupon']) && sizeof($_SESSION['checkout']['coupon']) > 0) {
			$old_total = $cart_product_subtotal;
			$cart_total = $cart_product_subtotal;
			foreach ($_SESSION['checkout']['coupon'] as $sc) {
				$cart_total = ($cart_total - $sc['fee']) * $sc['discount'] / 100;
			}
			$cart_product_subtotal = $cart_total;
		}
		if (isset($_SESSION['checkout']['invoice']['tax_point'])) {
			$card_invoice_fee = round($cart_product_subtotal * $_SESSION['checkout']['invoice']['tax_point'] / 100, 2);
			$cart_product_subtotal += $card_invoice_fee;
		}
        	$can_use_point_fee = round($cart_product_subtotal / 100 * $this->configs['proportion_point']);
        	
        	$can_use_point=$can_use_point_fee*$this->configs['point-equal'];
		$product_use_point = 0;
		foreach ($_SESSION['checkout']['products'] as $k => $v) {
			$product_use_point += $v['Product']['point_fee'] * $v['quantity'];
		}
		$max_use_point=0;
		if ($product_use_point < $can_use_point) {
			$max_use_point=$product_use_point;
		} else {
			$max_use_point=$can_use_point;
		}
		if(isset($_SESSION['User']['User'])){
			$user_id=$_SESSION['User']['User']['id'];
			$user_info=$this->User->find('first',array('fields'=>'User.id,User.point','conditions'=>array('User.id'=>$user_id)));
			if(!empty($user_info['User'])){
				$use_point=intval($user_info['User']['point']);
				if($max_use_point>$use_point){
					$max_use_point=$use_point;
				}
			}
		}
		$result['can_use_point'] = $max_use_point;
		$this->set('can_use_point', $max_use_point);
		$_SESSION['checkout']['point']['can_use_point']=$can_use_point;
		$can_use_point_flag=false;
		$points_occasions=isset($this->configs['points_occasions'])?$this->configs['points_occasions']:'';//积分使用场合
        	if($order_type=='P'&&in_array($points_occasions,array('0','2'))){
        		$can_use_point_flag=true;
        	}else if($order_type=='L'&&in_array($points_occasions,array('0','3'))){
        		$can_use_point_flag=true;
        	}
        	if($can_use_point_flag){//积分可使用
			$result['point'] = $_POST['point'];
			$result['fee'] = $_POST['point'] / $this->configs['point-equal'];
			if ($max_use_point>=$result['point']&&$cart_product_subtotal - $result['fee'] >= 0) {
				$_SESSION['checkout']['cart_info']['point_del'] = $_POST['point'] / $this->configs['point-equal'];
                		$_SESSION['checkout']['point']['point'] = $_POST['point'];
                		$_SESSION['checkout']['point']['fee'] = $_POST['point'] / $this->configs['point-equal'];
                		$result['type'] = 1;
                		$result['msg'] = $this->ld['set_successfully'];
            		}elseif($max_use_point<$result['point']){
            			$result['msg'] = "使用失败,当前".$this->ld['available_points'].":".$max_use_point;
            		}else{
            			$result['msg'] = "积分不足";
            			$result['CA1'] = $max_use_point>=$result['point'];
            			$result['CA2'] = $_SESSION['checkout']['cart_info']['total'] - $result['fee'];
            		}
		}else{
			$result['type'] = 0;
                	$result['msg'] = '积分不可用';
		}
		$this->order_price();
		$this->checkout_order_price();
		$result['point_del'] = isset($_SESSION['checkout']['cart_info']['point_del']) ? $_SESSION['checkout']['cart_info']['point_del'] : 0;
		$result['format_point_del'] = sprintf($this->configs['price_format'], $result['point_del']);
		$result['format_total'] = sprintf($this->configs['price_format'], $_SESSION['checkout']['cart_info']['total']);
        }
        $this->set('result', $result);
        die(json_encode($result));
    }

    /**
     *使用优惠券.
     *
     *@param $c_id
     *@param $type
     *chenfan chagne 2012/05/27
     */
    public function usecoupon($c_id = '', $type = '')
    {
        if ($c_id != '') {
            $_POST['coupon'] = $c_id;

            if ($type = 'is_id') {
                $_POST['is_id'] = 1;
            }
            $is_ajax = 0;
        } else {
            $is_ajax = 1;
        }
        $result['type'] = 2;
        $result['msg'] = $this->ld['use'].$this->ld['coupon'].$this->ld['failed'];
        if ($this->RequestHandler->isPost()) {
            //检测当前优惠券
            if (isset($_POST['allcoupons'])) {
                $coupon_arr = array();
                $coupon_arr = explode(',', $_POST['allcoupons']);
                if (isset($_SESSION['checkout']['coupon']) && sizeof($_SESSION['checkout']['coupon']) > 0) {
                    foreach ($_SESSION['checkout']['coupon'] as $sk => $sc) {
                        if (!in_array($sk, $coupon_arr)) {
                            unset($_SESSION['checkout']['coupon'][$sk]);
                        }
                    }
                }
            }
            //$user_info = $this->User->find('first',array('condtions'=>array('User.id'=>$_SESSION['User']['User']['id'])));
            $coupon = $this->Coupon->find('first', array('conditions' => array('Coupon.sn_code' => $_POST['coupon'], 'Coupon.order_id' => 0)));
            if (isset($_SESSION['checkout']['cart_info']['total']) && isset($_SESSION['checkout']['cart_info']['coupon_del'])) {
                $_SESSION['checkout']['cart_info']['total'] = $_SESSION['checkout']['cart_info']['total'] + $_SESSION['checkout']['cart_info']['coupon_del'];
                unset($_SESSION['checkout']['cart_info']['coupon_del']);
            }
            if (isset($coupon['Coupon'])) {
                $now = date('Y-m-d H:i:s');
                $coupon_type = $this->CouponType->find('first', array('conditions' => array('CouponType.id' => $coupon['Coupon']['coupon_type_id'])));
                //查看是折扣 还是减免
                $flag = true;
                if ($coupon_type['CouponType']['type'] == 1) { //折扣
                    $type = 1;
                } else {
                    $type = 2;
                }
                if ($coupon_type['CouponType']['send_type'] == 5 && $coupon['Coupon']['max_buy_quantity'] <= $coupon['Coupon']['max_use_quantity']) {
                    $result['type'] = 0;
                    $result['msg'] = $this->ld['rebate_087'];
                } elseif ($coupon_type['CouponType']['send_type'] == 3 && $coupon['Coupon']['order_id'] > 0) {
                    $result['type'] = 0;
                    $result['msg'] = $this->ld['rebate_087'];
                } elseif ($coupon_type['CouponType']['use_start_date'] <= $now && $coupon_type['CouponType']['use_end_date'] >= $now  && $_SESSION['checkout']['cart_info']['sum_subtotal'] >= $coupon_type['CouponType']['min_amount']) {
                    $result['point'] = $_POST['coupon'];
                    if ($coupon_type['CouponType']['send_type'] == 5) {
                        $flag = true;
                    } else {
                        if ($coupon['Coupon']['user_id'] != $_SESSION['User']['User']['id']) {
                            $flag = false;
                            $result['type'] = 0;
                            $result['msg'] = $this->ld['rebate_087'];
                        }
                    }
                    if ($flag) {
                        if (($type == 2 && $_SESSION['checkout']['cart_info']['total'] >= $coupon_type['CouponType']['min_amount']) || $type == 1) {
                            $result['type'] = 1;
                            $_SESSION['checkout']['coupon'][$coupon['Coupon']['sn_code']]['cha_fee'] = 0;
                            $_SESSION['checkout']['coupon'][$coupon['Coupon']['sn_code']]['coupon'] = $coupon['Coupon']['id'];
                            $_SESSION['checkout']['coupon'][$coupon['Coupon']['sn_code']]['sn_code'] = $coupon['Coupon']['sn_code'];
                            if ($type == 1) {
                                $_SESSION['checkout']['coupon'][$coupon['Coupon']['sn_code']]['discount'] = $coupon_type['CouponType']['money'];
                                $_SESSION['checkout']['coupon'][$coupon['Coupon']['sn_code']]['fee'] = 0;
                            } else {
                                $_SESSION['checkout']['coupon'][$coupon['Coupon']['sn_code']]['discount'] = 100;
                                $_SESSION['checkout']['coupon'][$coupon['Coupon']['sn_code']]['fee'] = $coupon_type['CouponType']['money'];
                            }
                            $result['msg'] = $this->ld['rebate_088'];
                        } else {
                            $result['type'] = 0;
                            $result['msg'] = $this->ld['exceed_max_value_can_use'];
                        }
                    }
                } else {
                    $result['type'] = 0;
                    if ($_SESSION['checkout']['cart_info']['sum_subtotal'] < $coupon_type['CouponType']['min_amount']) {
                        $result['msg'] = $this->ld['rebate_089'].sprintf($coupon_type['CouponType']['min_amount'], $this->configs['price_format']);
                    } else {
                        $result['msg'] = $this->ld['rebate_090'];
                    }
                }
            } else {
                $result['type'] = 0;
                $result['msg'] = $this->ld['rebate_087'];
            }
        }
        $this->checkout_order_price();
        $result['coupon_del'] = isset($_SESSION['checkout']['cart_info']['coupon_del']) ? $_SESSION['checkout']['cart_info']['coupon_del'] : 0;
        $result['format_coupon_del'] = sprintf($this->configs['price_format'], $result['coupon_del']);
        $result['format_total'] = sprintf($this->configs['price_format'], $_SESSION['checkout']['cart_info']['total']);
        $result['coupon_list'] = isset($_SESSION['checkout']['coupon'])?$_SESSION['checkout']['coupon']:array();
        $this->set('checkout', $_SESSION['checkout']);
        $this->set('result', $result);
        $this->layout = 'ajax';
        Configure::write('debug', 1);
        die(json_encode($result));
    }

    /**
     *购买订单.
     */
    public function checkout_order()
    {
        $result['type'] = 0;
        $error_arr = array();
        if ($this->RequestHandler->isPost()) {
            if (isset($_SESSION['User'])) {
                $this->order_price();
                if (!(isset($_SESSION['checkout']['products']))) {
                    $result['type'] = 2;
                    $error_arr[] = $this->ld['no_products_in_cart'];
                }
                /* 增加纯虚拟商品判断 */
                if (!isset($_SESSION['checkout']['shipping']['shipping_id']) && (empty($_SESSION['checkout']['cart_info']['all_virtual']) || (isset($_SESSION['checkout']['promotion']['all_virtual']) && empty($_SESSION['checkout']['promotion']['all_virtual'])))) {
                    $result['type'] = 2;
                    $error_arr[] = $this->ld['please_select'].$this->ld['shipping_method'];
                }
                if (!isset($_SESSION['checkout']['payment']['payment_id'])) {
                    $result['type'] = 2;
                    $error_arr[] = $this->ld['please_select'].$this->ld['payment'];
                }
                if (isset($this->configs['min_buy_amount'])) {
                    if ($_SESSION['checkout']['cart_info']['total'] < $this->configs['min_buy_amount']) {
                        $result['type'] = 2;
                        $error_arr[] = $this->ld['order_amount_under_min'];
                    }
                }
                $user_info = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
                if (isset($_SESSION['checkout']['payment']['payment_id']) && isset($_SESSION['checkout']['products']) && $_SESSION['checkout']['payment']['code'] == 'account_pay' && $_SESSION['checkout']['cart_info']['total'] > $user_info['User']['balance']) {
                    $error_arr[] = $this->ld['lack_balance_supply_first'];
                    $result['type'] = 2;
                } elseif (isset($_SESSION['checkout']['payment']['payment_id']) && isset($_SESSION['checkout']['products']) && $_SESSION['checkout']['payment']['code'] == 'account_pay') {
                    $pay = $this->Payment->findbyid($_SESSION['checkout']['payment']['payment_id']);
                    @eval($pay['Payment']['config']);
                    if (@isset($payment_arr['currency_code']['value']) && $payment_arr['currency_code']['value'] == $this->currencie) {
                        $pay_can_use = 1;
                    } else {
                        $error_arr[] = '余额支付不支持此货币,请切换其他货币！';
                        $result['type'] = 2;
                    }
                }
                if (empty($_SESSION['checkout']['address']) || $_SESSION['checkout']['address']['consignee'] == '') {
                    //虚拟商品同样处理
                    $error_arr[] = $this->ld['consignee'].$this->ld['user_name'].$this->ld['can_not_empty'];
                    $result['type'] = 2;
                }
                if (isset($_SESSION['checkout']['address']) && $_SESSION['checkout']['address']['regions'] == '' && (empty($_SESSION['checkout']['cart_info']['all_virtual']) || (isset($_SESSION['checkout']['promotion']['all_virtual']) && empty($_SESSION['checkout']['promotion']['all_virtual'])))) {
                    $error_arr[] = $this->ld['please_select'].$this->ld['area'];
                    $result['type'] = 2;
                } elseif (!isset($_SESSION['checkout']['shipping']['shipping_id'])
                    && (empty($_SESSION['checkout']['cart_info']['all_virtual']) || (isset($_SESSION['checkout']['promotion']['all_virtual']) && empty($_SESSION['checkout']['promotion']['all_virtual'])))) {
                    if (isset($_SESSION['checkout']['address']['regions'])) {
                        $region_array = explode(' ', trim($_SESSION['checkout']['address']['regions']));
                        if (in_array($this->ld['please_select'], $region_array)) {
                            $error_arr[] = $this->ld['please_select'].$this->ld['area'];
                            $result['type'] = 2;
                        } else {
                            if ($region_array[count($region_array) - 1] == '' || $region_array[count($region_array) - 1] == $this->ld['please_select']) {
                                $error_arr[] = $this->ld['please_select'].$this->ld['area'];
                                $result['type'] = 2;
                            } else {
                                $region_info = $this->Region->findbyparent_id($region_array[count($region_array) - 1]);
                                if (isset($region_info['Region'])) {
                                    $error_arr[] = $this->ld['please_select'].$this->ld['area'];
                                    $result['type'] = 2;
                                }
                            }
                        }
                    }
                }
                if (isset($_SESSION['checkout']['coupon']['coupon'])) {
                    $coupon = $this->Coupon->findbyid($_SESSION['checkout']['coupon']['coupon']);

                    $coupon_type = $this->CouponType->findbyid($coupon['Coupon']['coupon_type_id']);
                    $now = date('Y-m-d H:i:s');
                    if ($coupon_type['CouponType']['send_type'] == 5 && $coupon['Coupon']['max_buy_quantity'] <= $coupon['Coupon']['max_use_quantity']) {
                        $error_arr[] = $this->ld['rebate_087'];
                        $result['type'] = 2;
                    } elseif ($coupon_type['CouponType']['send_type'] == 3 && $coupon['Coupon']['order_id'] > 0) {
                        $error_arr[] = $this->ld['rebate_087'];
                        $result['type'] = 2;
                    } elseif ($coupon['Coupon']['order_id'] > 0) {
                        $error_arr[] = $this->ld['rebate_087'];
                        $result['type'] = 2;
                    }
                }
                if (isset($_SESSION['checkout']['point']['point'])) {
                    $user_info = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
                    if ($user_info['User']['point'] < $_SESSION['checkout']['point']['point']) {
                        $error_arr[] = $this->ld['exceed_max_value_can_use'];
                        $result['type'] = 2;
                    }
                }
            } else {
                $result['type'] = 1;
                $result['message'] .= $this->ld['time_out_relogin'];
            }
        }
        $this->set('error_arr', $error_arr);
        $this->set('result', $result);
        if ($result['type'] == 0) {
            $this->done();
        }
        $this->layout = 'ajax';
    }

    /**
     *删除产品.
     *
     *@param $type
     */
    public function del_cart_product($type = '')
    {
        //	if($this->RequestHandler->isPost()){
        //	Configure::write('debug', 0);
        if (isset($_SESSION['checkout']['products']) && sizeof($_SESSION['checkout']['products']) > 0) {
            foreach ($_SESSION['checkout']['products'] as $k => $v) {
                if (isset($v['save_cart'])) {
                    $condition = array('Cart.id' => $v['save_cart']);
                    $this->Cart->deleteAll($condition);
                    $this->CartProductValue->deleteAll(array('CartProductValue.cart_id' => $v['save_cart']));
                }
            }
            foreach ($_SESSION['checkout']['products'] as $k => $v) {
                if (isset($_SESSION['svcart']['products'])) {
                    if (array_key_exists($k, $_SESSION['svcart']['products'])) {
                        unset($_SESSION['svcart']['products'][$k]);
                    }
                }
                if (isset($_SESSION['svcart']['bespoke'])) {
                    if (array_key_exists($k, $_SESSION['svcart']['bespoke'])) {
                        unset($_SESSION['svcart']['bespoke'][$k]);
                    }
                }
            }
            unset($_SESSION['checkout']);
            //$this->Cookie->del('cart_cookie');
            $result['type'] = 1;
            if ($type != 'done') {
                if (!isset($_POST['is_ajax'])) {
                    $this->page_init();
                    $this->pageTitle = isset($result['message']) ? $result['message'] : $this->ld['deleted_success'].' - '.$this->configs['shop_title'];
                    $this->flash(isset($result['message']) ? $result['message'] : $this->ld['deleted_success'], isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/');
                } else {
                    die($result);
                }
            }
        }
    }

    /**
     *保存购物车.
     *
     *@param $product_info
     *@param $p_id
     */
    public function save_cart($product_info, $p_id)
    {
        $product_ranks = $this->ProductRank->find_rank_by_product_ids($product_info['Product']['id']);
        if (isset($_SESSION['User']['User'])) {
            $user_rank_list = $this->UserRank->findrank();
        }
        // 存入 cart 表
        //if((!isset($product_info['save_cart'])) && isset($this->configs['enable_out_of_stock_handle']) && sizeof($this->configs['enable_out_of_stock_handle'])>0) {
        if ((!isset($product_info['save_cart']))) {
            //            if(isset($product_ranks[$product_info['Product']['id']]) && isset($_SESSION['User']['User']['rank']) && isset($product_ranks[$product_info['Product']['id']][$_SESSION['User']['User']['rank']])) {
//                if(isset($product_ranks[$product_info['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank']) && $product_ranks[$product_info['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['is_default_rank'] == 0) {
//                    $price_user= $product_ranks[$product_info['Product']['id']][$_SESSION['User']['User']['rank']]['ProductRank']['product_price'];
//                }else if(isset($user_rank_list[$_SESSION['User']['User']['rank']])) {
//                    $price_user=($user_rank_list[$_SESSION['User']['User']['rank']]['UserRank']['discount']/100)*($product_info['Product']['shop_price']);
//                }
//            }
            //$price_user = 	$this->Product->user_price(0,$product_info,$this);
            if (isset($price_user) && (!empty($price_user))) {
                $cart_price = $price_user;
            } else {
                $cart_price = $product_info['Product']['shop_price'];
            }
            if (isset($product_info['attributes_total']) && !empty($product_info['attributes_total'])) {
                $cart_price += $product_info['attributes_total'];
            }
            if (isset($product_info['AccessoryPrice']) && !empty($product_info['AccessoryPrice'])) {
                foreach ($product_info['AccessoryPrice'] as $ak => $av) {
                    $product_info['Product']['shop_price'] += $av;
                }
            }
            //租赁
            $type="P";
            $unit=0;
            $price=$product_info['Product']['shop_price'];
            if($product_info['Product']['is_lease']==1){
                $type="L";
                $price=$product_info['Product']['lease_price'];
                $unit=$product_info['Product']['lease_day'];
            }
            $cart = array(
                'id' => '',
                'session_id' => session_id(),
                'user_id' => isset($_SESSION['User']['User']['id']) ? $_SESSION['User']['User']['id'] : '0',
                'store_id' => 0,
                'type'=>$type,
                'product_id' => $product_info['Product']['id'],
                'product_code' => $product_info['Product']['code'],
                'product_name' => $product_info['ProductI18n']['name'],
                'product_price' => $price,
                'unit'=>$unit,
                'product_quantity' => $product_info['quantity'],
                'product_attrbute' => isset($product_info['attributes']) ? $product_info['attributes'] : '',
                'user_style_id' => isset($product_info['user_style_id']) ? $product_info['user_style_id'] : 0,
                'schedule_date' => isset($product_info['schedule_date']) ? $product_info['schedule_date'] : '',
                'schedule_time' => isset($product_info['schedule_time']) ? $product_info['schedule_time'] : '',
                'shipping_type' => isset($product_info['shipping_type']) ? $product_info['shipping_type'] : '',
                'file_url' => isset($product_info['file_url']) ? $product_info['file_url'] : '',
                'extension_code' => $product_info['Product']['extension_code'],
                'note' => !empty($product_info['CartProductNote']) ? $product_info['CartProductNote'] : '',
            );
            $this->Cart->save($cart);
            $cart_id = $this->Cart->id;
            if (!empty($product_info['CartProductValue'])) {
                $this->CartProductValue->deleteAll(array('cart_id' => $cart_id));
                $accessory_price = isset($product_info['AccessoryPrice']) ? $product_info['AccessoryPrice'] : array();
                foreach ($product_info['CartProductValue'] as $vv) {
                    $CartProductValueData = array(
                        'cart_id' => $cart_id,
                        'attribute_id' => $vv['attribute_id'],
                        'attribute_value' => $vv['attribute_value'],
                        'attr_price' => isset($accessory_price[$vv['attribute_id']]) ? $accessory_price[$vv['attribute_id']] : 0,
                    );
                    $this->CartProductValue->saveAll($CartProductValueData);
                }
            }
            $_SESSION['checkout']['products'][$p_id]['save_cart'] = $cart_id;
        }
    }

    /**
     *更新数量.
     */
    public function update_num()
    {
        if(isset($_POST)){
            $_POST=$this->clean_xss($_POST);
        }
        $error_num = 0;
        $msg = '';
        $pro_qty = '';
        $pro_total = '';
        $return_qty = '1';
        if (isset($_POST['product_num'])&&is_array($_POST['product_num'])&&sizeof($_POST['product_num'])>0) {
            $error_arr = array();
            foreach ($_POST['product_num'] as $k => $v) {
                if (isset($_SESSION['svcart']['products'][$k]) && intval($v) > 0) {
                    if (isset($_SESSION['svcart']['products'][$k]['sku_product']['Product']['quantity'])){
                        if ($v <= $_SESSION['svcart']['products'][$k]['sku_product']['Product']['quantity']) {
                            $temp = $this->act_quantity_change($k, intval($v), 'product');
                            if ($temp['type']) {
                                $msg = $temp['message'];
                                ++$error_num;
                                $error_arr[] = $temp;
                                $return_qty = $_SESSION['svcart']['products'][$k]['quantity'];
                            }
                            $pro_qty = $v;
                            $pro_total = $_SESSION['svcart']['products'][$k]['subtotal'];
                        } else {
                            $msg = $_SESSION['svcart']['products'][$k]['ProductI18n']['name'].$this->ld['understock'];
                            $return_qty = $_SESSION['svcart']['products'][$k]['quantity'];
                            ++$error_num;
                        }
                    } else {
                        $pid = $_SESSION['svcart']['products'][$k]['Product']['id'];
                        $quantity = $this->Product->find('first', array('conditions' => array('Product.id' => $pid), 'fields' => 'Product.id,Product.quantity'));
                        if ($v <= $quantity['Product']['quantity']) {
                            $temp = $this->act_quantity_change($k, intval($v), 'product');
                            if ($temp['type']) {
                                $msg = $temp['message'];
                                ++$error_num;
                                $error_arr[] = $temp;
                                $return_qty = $_SESSION['svcart']['products'][$k]['quantity'];
                            }
                            $pro_qty = $v;
                            //租赁
                            if($_SESSION['svcart']['products'][$k]['Product']["is_lease"]==1){
                                $pro_total = $_SESSION['svcart']['products'][$k]['Product']["lease_price"]*$pro_qty;
                            }else{
                                $pro_total = $_SESSION['svcart']['products'][$k]['subtotal'];
                            }
                        } else {
                            $msg = $_SESSION['svcart']['products'][$k]['ProductI18n']['name'].$this->ld['understock'];
                            $return_qty = $_SESSION['svcart']['products'][$k]['quantity'];
                            ++$error_num;
                        }
                    }
                } else {
                    ++$error_num;
                }
            }
        }
        if (isset($_POST['packaging_num']) > 0) {
            foreach ($_POST['packaging_num'] as $k => $v) {
                if (isset($_SESSION['svcart']['packagings'][$k]) && intval($v) > 0) {
                    $temp = $this->act_quantity_change($k, intval($v), 'packaging');
                    if ($temp['type']) {
                        ++$error_num;
                        $error_arr[] = $temp;
                        $return_qty = $_SESSION['svcart']['packagings'][$k]['quantity'];
                    }
                } else {
                    ++$error_num;
                }
            }
        }
        if (isset($_POST['card_num']) > 0) {
            foreach ($_POST['card_num'] as $k => $v) {
                if (isset($_SESSION['svcart']['cards'][$k])  && intval($v) > 0) {
                    $temp = $this->act_quantity_change($k, intval($v), 'card');
                    if ($temp['type']) {
                        $error_arr[] = $temp;
                        $return_qty = $_SESSION['svcart']['cards'][$k]['quantity'];
                    }
                } else {
                    ++$error_num;
                }
            }
        }
        $result['type'] = 0;
        if (!empty($error_arr)) {
            if ($error_num > 0) {
                $result['type'] = 1;
                $result['msg'] = $msg;
                $result['return_qty'] = $return_qty;
            }
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            die(json_encode($result));
        } else {
            $result['type'] = 0;
            $sum_market_subtotal = sprintf($this->configs['price_format'], $_SESSION['svcart']['cart_info']['sum_market_subtotal']);
            $discount_price = sprintf($this->configs['price_format'], $_SESSION['svcart']['cart_info']['discount_price']);
            $sum_subtotal = sprintf($this->configs['price_format'], $_SESSION['svcart']['cart_info']['sum_subtotal']);
            $result['sum_market_subtotal'] = $sum_market_subtotal;
            $result['discount_price'] = $discount_price;
            $result['sum_subtotal'] = $sum_subtotal;
            $result['pro_qty'] = $pro_qty;
            $result['pro_total'] = sprintf($this->configs['price_format'], $pro_total);
            if ($error_num > 0) {
                $result['type'] = 1;
                $result['msg'] = $msg;
                $result['return_qty'] = $return_qty;
            }
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            die(json_encode($result));
            //$this->redirect($this->server_host.$this->webroot.'carts/');
            //die();
        }
        $this->page_init();
        $this->pageTitle = $msg.' - '.$this->configs['shop_title'];
        $this->flash($msg, $this->server_host.$this->webroot.'carts/');
    }

    /**
     *确认收货人.
     *
     *@param $id
     */
    public function confirm_consignee($id)
    {

    }

    /**
     *收货人.
     *
     *@param $id
     */
    public function consignee($id = '')
    {
        $this->page_init();
        $this->pageTitle = '修改收货人信息'.' - '.$this->configs['shop_title'];
        if (isset($_SESSION['User']['User'])) {
            $addresses = $this->UserAddress->findAllbyuser_id($_SESSION['User']['User']['id']);
            foreach ($addresses as $key => $address) {
                $region_array = explode(' ', trim($address['UserAddress']['regions']));
                $addresses[$key]['UserAddress']['regions'] = '';
                foreach ($region_array as $k => $region_id) {
                    if ($region_id != '' && $region_id != $this->ld['please_select']) {
                        $region_info = $this->Region->findbyid($region_id);
                        if ($k < sizeof($region_array) - 1) {
                            $addresses[$key]['UserAddress']['regions'] .= $region_info['RegionI18n']['name'].' ';
                        } else {
                            $addresses[$key]['UserAddress']['regions'] .= $region_info['RegionI18n']['name'];
                        }
                    }
                }
            }
            $this->set('addresses', $addresses);
            if ($id > 0) {
                $address = $this->UserAddress->find_same_address($id, $_SESSION['User']['User']['id']);//model调用
                if (isset($address['UserAddress'])) {
                    $this->set('address', $address);
                }
            }
        } else {
            $_SESSION['login_back'] = $this->server_host.$this->webroot.'carts/consignee/';
            $this->redirect($this->server_host.$this->user_webroot.'login/');
            exit;
        }
        $this->layout = 'default_full';
    }

    //vancl 购物流程
    /**
     *删除地址.
     *
     *@param $id
     */
    public function del_address($id)
    {
        if (!isset($_SESSION['User']['User'])) {
            $_SESSION['login_back'] = $this->server_host.$this->webroot.'carts/check_shipping/';
            $this->redirect($this->server_host.$this->user_webroot.'login/');
        } else {
            $this->UserAddress->deleteAll('UserAddress.user_id = '.$_SESSION['User']['User']['id'].' and UserAddress.id='.$id);
        }
        $this->redirect($this->server_host.$this->webroot.'carts/check_shipping/');
    }

    /**
     *检查报告.
     *
     *@param $id
     */
    public function check_address($id = '')
    {
        pr($_REQUEST);
        pr($_SERVER);
        $this->redirect('/carts/check_shipping/');
        die();
        $this->ur_heres[] = array('name' => $this->ld['settlement'],'url' => '/carts');
        $this->ur_heres[] = array('name' => $this->ld['contact_information'],'url' => '');
        $this->page_init();
        $this->pageTitle = $this->ld['contact_information'].' - '.$this->configs['shop_title'];
        if (!empty($_POST['shipping'])) {
            $this->confirm_shipping($_POST);
        }
        if (!isset($_SESSION['User']['User'])) {
            $_SESSION['login_back'] = $this->server_host.$this->webroot.'carts/check_address/';
            $this->redirect($this->server_host.$this->user_webroot);
        } elseif (!(isset($_SESSION['checkout']['products']) && sizeof($_SESSION['checkout']['products']) > 0) && !(isset($_SESSION['checkout']['bespoke']) && sizeof($_SESSION['checkout']['bespoke']) > 0)) {
            $this->pageTitle = $this->ld['no_products_in_cart'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['no_products_in_cart'], '/carts/');
        } elseif (!isset($_SESSION['checkout']['shipping'])) {
            $this->pageTitle = $this->ld['please_select'].$this->ld['shipping_method'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['please_select'].$this->ld['shipping_method'], $this->server_host.$this->webroot.'carts/check_shipping/');
        } else {
            $addresses = $this->UserAddress->findAllbyuser_id($_SESSION['User']['User']['id']);
            //如果有购买的历史直接使用上次的值
            if (!isset($_SESSION['checkout']['address'])) {
                $user_info = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id']), 'recursive' => -1));
                if ($user_info['User']['address_id'] != '' && $user_info['User']['address_id'] != 0) {
                    $address = $this->UserAddress->findbyid($user_info['User']['address_id']);
                    if (isset($address) && !empty($address)) {
                        if ($address['UserAddress']['user_id'] == $_SESSION['User']['User']['id']) {
                            $_SESSION['checkout']['address'] = $address['UserAddress'];
                            $this->redirect($this->server_host.$this->webroot.'carts/checkout');
                            exit();
                        }
                    }
                }
            }
            if (isset($addresses) && sizeof($addresses) > 0) {
                foreach ($addresses as $key => $address) {
                    if (isset($region_array) && sizeof($region_array) > 0) {
                        foreach ($region_array as $a => $b) {
                            if ($b == $this->ld['please_select']) {
                                unset($region_array[$a]);
                            }
                        }
                    } else {
                        $region_array[] = 0;
                    }
                    $region_array = explode(' ', trim($address['UserAddress']['regions']));
                    $addresses[$key]['UserAddress']['regions'] = '';

                    $region_name_arr = $this->Region->find_region_name_arr($region_array);//model调用
                    //pr($region_name_arr);
                    if (is_array($region_name_arr) && sizeof($region_name_arr) > 0) {
                        foreach ($region_name_arr as $k => $v) {
                            $addresses[$key]['UserAddress']['regions'] .= isset($v['RegionI18n']['name']) ? $v['RegionI18n']['name'].' ' : '';
                        }
                    }
                }
                $this->set('addresses', $addresses);
            }
            if ($id > 0) {
                $address = $this->UserAddress->find_same_address($id, $_SESSION['User']['User']['id']);//model调用
                if (isset($address['UserAddress'])) {
                    $this->set('address', $address);
                }
            }
        }
        //用户的地址数量
        $add_num = $this->UserAddress->find('count', array('conditions' => array('UserAddress.user_id' => $_SESSION['User']['User']['id'])));
        $this->set('add_num', $add_num);
        $this->layout = 'default_full';
    }

    /**
     *检查购物车.
     *
     *@param $id
     */
    public function check_shipping($address_id=0)
    {
        $this->layout = 'default_full';
        $this->ur_heres[] = array('name' => $this->ld['settlement'],'url' => '/carts');
        $this->ur_heres[] = array('name' => $this->ld['shipping_method'],'url' => '');
        $this->page_init();
        $this->pageTitle = $this->ld['shipping_method'].' - '.$this->configs['shop_title'];
        $this->checkout_order_price();
        $this->statistic_checkout();                //计算金额
        if (!isset($_SESSION['User']['User'])) {
            $_SESSION['login_back'] = $this->server_host.$this->webroot.'carts/index';
            $this->redirect($this->server_host.$this->user_webroot);
        } elseif (!(isset($_SESSION['checkout']['products']) && sizeof($_SESSION['checkout']['products']) > 0) && !(isset($_SESSION['checkout']['bespoke']) && sizeof($_SESSION['checkout']['bespoke']) > 0)) {
            $this->pageTitle = $this->ld['no_products_in_cart'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['no_products_in_cart'], '/carts/');
        } else {
            $weight = 0;
            if (isset($_SESSION['checkout']['products']) && sizeof($_SESSION['checkout']['products']) > 0) {
                foreach ($_SESSION['checkout']['products'] as $k => $v) {
                    $weight += $v['Product']['weight'];
                }
            }
            $shippings = $this->show_shipping_by_address($weight, $is_ajax = 0);
            //var_dump($shippings);
            $isHave = $this->Shipping->find('all', array('conditions' => array('Shipping.status' => 1)));
            $this->set('isHave', $isHave);
            $this->set('shippings', $shippings);
            //配送方式为预约量体
            if (!isset($_SESSION['checkout']['products']) && isset($_SESSION['checkout']['bespoke']) && sizeof($_SESSION['checkout']['bespoke']) > 0) {
                $this->set('bespoke', true);
            }
            //如果只有一中配送方式直接默认
            if (count($shippings) == 1 && !isset($_SESSION['checkout']['shipping']['shipping_id'])) {
                foreach ($shippings as $v) {
                    $shipping_id['shipping_id'] = $v['Shipping']['id'];
                }
                $this->confirm_shipping($shipping_id);
            }
        }
        //用户的地址数量
        $add_num = $this->UserAddress->find('count', array('conditions' => array('UserAddress.user_id' => $_SESSION['User']['User']['id'])));
        $this->set('add_num', $add_num);
        //用户地址信息
        $addresses = $this->UserAddress->findAllbyuser_id($_SESSION['User']['User']['id']);
        if (isset($addresses) && sizeof($addresses) > 0) {
            foreach ($addresses as $key => $address) {
                if (isset($region_array) && sizeof($region_array) > 0) {
                    foreach ($region_array as $a => $b) {
                        if ($b == $this->ld['please_select']) {
                            unset($region_array[$a]);
                        }
                    }
                } else {
                    $region_array[] = 0;
                }
                $region_array = explode(' ', trim($address['UserAddress']['regions']));
                $addresses[$key]['UserAddress']['regions'] = '';
                $region_name_arr = $this->Region->find_region_name_arr($region_array);//model调用
                if (is_array($region_name_arr) && sizeof($region_name_arr) > 0) {
                    foreach ($region_name_arr as $k => $v) {
                        $addresses[$key]['UserAddress']['regions'] .= isset($v['RegionI18n']['name']) ? $v['RegionI18n']['name'].' ' : '';
                    }
                }
            }
            $this->set('addresses', $addresses);
        }
        if ($address_id > 0) {
            $address = $this->UserAddress->find_same_address($address_id, $_SESSION['User']['User']['id']);//model调用
            if (isset($address['UserAddress'])) {
                $this->set('address', $address);
            }
        }
    }

    function ajax_check_shipping(){
        Configure::write('debug',0);
        $this->layout = 'ajax';
        $result=array();
        $result['code']=0;
        $result['msg']='Error';
        if ($this->RequestHandler->isPost()) {
            if(isset($_POST['shipping'])&&!empty($_POST['shipping'])){
                $result['code']=1;
                $result['msg']=$this->ld['saved_successfully'];
                $this->confirm_shipping($_POST);
                $cart_shipping=isset($_SESSION['checkout']['shipping'])?$_SESSION['checkout']['shipping']:array();
                $result['cart_shipping']=$cart_shipping;
            }else{
                $result['msg']=$this->ld['failed'];
            }
        }
        die(json_encode($result));
    }

    /**
     *检查支付.
     */
    public function check_payment()
    {
        $this->layout = 'default_full';
        $this->ur_heres[] = array('name' => $this->ld['settlement'],'url' => '/carts');
        $this->ur_heres[] = array('name' => $this->ld['payment_method'],'url' => '');
        $this->page_init();
        $this->checkout_order_price();
        $this->pageTitle = $this->ld['payment_method'].' - '.$this->configs['shop_title'];
        $this->statistic_checkout();                //计算金额
        if (!isset($_SESSION['User']['User'])) {
            $_SESSION['login_back'] = $this->server_host.$this->webroot.'carts/index';
            $this->redirect($this->server_host.$this->user_webroot);
        } elseif (!(isset($_SESSION['checkout']['products']) && sizeof($_SESSION['checkout']['products']) > 0) && !(isset($_SESSION['checkout']['bespoke']) && sizeof($_SESSION['checkout']['bespoke']) > 0)) {
            $this->pageTitle = $this->ld['no_products_in_cart'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['no_products_in_cart'], '/carts/');
            $error = 1;
        }
        if (isset($_POST['remark'])) {
            $_SESSION['checkout']['remark'] = $_POST['remark'];
        }
        if (!isset($error)) {
            $weight = 0;
            if (isset($_SESSION['checkout']['products']) && sizeof($_SESSION['checkout']['products']) > 0) {
                foreach ($_SESSION['checkout']['products'] as $k => $v) {
                    $weight += $v['Product']['weight'];
                }
            }
            $shippings = $this->show_shipping_by_address($weight, $is_ajax = 0);
            $this->set('shippings', $shippings);
            if (!isset($_SESSION['checkout']['shipping'])) {
                $this->pageTitle = $this->ld['please_select'].$this->ld['shipping_method'].' - '.$this->configs['shop_title'];
                $this->flash($this->ld['please_select'].$this->ld['shipping_method'], $this->server_host.$this->webroot.'carts/check_shipping/');
            } elseif (!isset($_SESSION['checkout']['address'])) {
                $this->redirect($this->server_host.$this->webroot.'carts/check_address');
            } else {
                if (isset($_SESSION['checkout']['shipping']['shipping_code']) && $_SESSION['checkout']['shipping']['shipping_code'] != 'cac' && $_SESSION['checkout']['shipping']['shipping_code'] != 'bespoke') {
                    //门店取货
                    if ($_SESSION['checkout']['address']['address'] == '') {
                        $this->redirect($this->server_host.$this->webroot.'carts/check_shipping');
                    }
                }
                $payments = $this->Payment->getOrderPayments();
                if (isset($_SESSION['payment_tp']) && !empty($_SESSION['payment_tp'])) {
                    $payments_t = $this->Payment->find('all', array('conditions' => array('Payment.code' => $_SESSION['payment_tp'], 'Payment.status' => 1, 'Payment.order_use_flag' => 1), 'fields' => array('Payment.id', 'Payment.code', 'Payment.version', 'PaymentI18n.status', 'Payment.fee', 'Payment.config', 'Payment.is_cod', 'Payment.is_online', 'Payment.supply_use_flag', 'Payment.order_use_flag', 'PaymentI18n.name', 'PaymentI18n.description'), ));
                    $_SESSION['checkout']['payment'] = $payments_t;
                    $this->redirect($this->server_host.$this->webroot.'carts/checkout');
                }
                $this->set('payments', $payments);
                if (!empty($payments) && sizeof($payments) == 1 && !isset($_SESSION['checkout']['payment']['payment_id'])) {
                    foreach ($payments as $v) {
                        $payment_id = $v['Payment']['id'];
                    }
                    $this->confirm_payment($payment_id);
                    $this->redirect($this->server_host.$this->webroot.'carts/checkout');
                }
            }
        }
    }

    /**
     *检查类型.
     */
    public function check_order()
    {
        $this->pageTitle = $this->ld['checkout'].' - '.$this->configs['shop_title'];
        $this->checkout_order_price();
        $this->statistic_checkout();                //计算金额
        if (!isset($_SESSION['User']['User'])) {
            $_SESSION['login_back'] = $this->server_host.$this->webroot.'carts/check_address/';
            $this->redirect($this->server_host.$this->user_webroot);
        } elseif (!(isset($_SESSION['checkout']['products']) && sizeof($_SESSION['checkout']['products']) > 0)) {
            $this->pageTitle = $this->ld['no_products_in_cart'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['no_products_in_cart'], '/carts/');
            $error = 1;
        }
        if (!isset($error)) {
            if (isset($_POST['payment'])) {
                $this->confirm_payment($_POST['payment']);
            }
            $weight = 0;
            if (isset($_SESSION['checkout']['products']) && sizeof($_SESSION['checkout']['products']) > 0) {
                foreach ($_SESSION['checkout']['products'] as $k => $v) {
                    $weight += $v['Product']['weight'];
                }
            }
            if (isset($_SESSION['checkout']['address']['id'])) {
                $shippings = $this->show_shipping_by_address($weight, $is_ajax = 0);
                $this->set('shippings', $shippings);
            }

            $payments = $this->Payment->availables();
            $this->set('payments', $payments);
        }
        $this->layout = 'default_full';
    }

    /**
     *确认发票.
     */
    public function confirm_invoice()
    {
        $result['type'] = 2;
        $result['msg'] = $this->ld['invoice'].$this->ld['failed'];
        if ($this->RequestHandler->isPost()) {
            $result['type'] = 0;
            $invoice_type = $this->InvoiceType->findbyid($_POST['invoice_id']);
            $result['tax_point'] = $invoice_type['InvoiceType']['tax_point'];
            $result['invoice_type'] = $invoice_type['InvoiceTypeI18n']['name'];
            $result['invoice_title'] = $_POST['invoice_title'];
            $_SESSION['checkout']['invoice']['id'] = $invoice_type['InvoiceType']['id'];
            $_SESSION['checkout']['invoice']['tax_point'] = $invoice_type['InvoiceType']['tax_point'];
            $_SESSION['checkout']['invoice']['invoice_type'] = $invoice_type['InvoiceTypeI18n']['name'];
            $_SESSION['checkout']['invoice']['direction'] = $invoice_type['InvoiceTypeI18n']['direction'];
            $_SESSION['checkout']['invoice']['invoice_title'] = $_POST['invoice_title'];
            $this->checkout_order_price();
            $this->get_point_and_coupon();
            $save_cookie = $_SESSION['checkout'];
            unset($save_cookie['products']);
            unset($save_cookie['promotion']['products']);
            //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
        }
        $this->set('checkout', $_SESSION['checkout']);
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    /**
     *改变发票.
     */
    public function change_invoice()
    {
        $result['type'] = 2;
        $result['msg'] = $this->ld['invoice'].$this->ld['failed'];
        if ($this->RequestHandler->isPost()) {
            $result['type'] = 0;
            $invoice_type = $this->InvoiceType->get_cache_invoice_type(LOCALE);//model调用
            unset($_SESSION['checkout']['invoice']);
            $this->checkout_order_price();
            $this->get_point_and_coupon();
            $this->set('invoice_type', $invoice_type);
            $save_cookie = $_SESSION['checkout'];
            unset($save_cookie['products']);
            unset($save_cookie['promotion']['products']);
            //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
        }
        $this->set('checkout', $_SESSION['checkout']);
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    /**
     *确认存货.
     */
    public function confirm_stock()
    {
        $result['type'] = 2;
        $result['msg'] = $this->ld['out_of_stock_process'].$this->ld['failed'];
        if ($this->RequestHandler->isPost()) {
            $result['type'] = 0;
            $result['stock_handle'] = $_POST['stock'];
            $_SESSION['checkout']['stock_handle'] = $_POST['stock'];
            $save_cookie = $_SESSION['checkout'];
            unset($save_cookie['products']);
            unset($save_cookie['promotion']['products']);
            //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
        }
        $this->set('checkout', $_SESSION['checkout']);
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    /**
     *改变库存.
     */
    public function change_stock()
    {
        $result['type'] = 2;
        $result['msg'] = $this->ld['out_of_stock_process'].$this->ld['failed'];
        if ($this->RequestHandler->isPost()) {
            $result['type'] = 0;
            unset($_SESSION['checkout']['stock_handle']);
            $save_cookie = $_SESSION['checkout'];
            unset($save_cookie['products']);
            unset($save_cookie['promotion']['products']);
            //$this->Cookie->write('cart_cookie',serialize($save_cookie),false,3600 * 24);
        }
        $this->set('checkout', $_SESSION['checkout']);
        $this->set('result', $result);
        $this->layout = 'ajax';
    }

//    //检查依赖关系
//    function check_dependent($pid){
//    	ProductDependent
//
//    }

    //解析ip返回内容
    public function set_city()
    {
        //$ip=$this->real_ip();
        $ip = '122.6.2.23';
        $area = $this->get_ip_place($ip);
        $sel_area = array();
        if (!empty($area)) {
            if ($area['ret'] == -1) {
                //ip为局域网
                $sel_area['x'] = -1;
            } else {
                //拿信息
                if ($area['country'] == '中国') {
                    $sel_area['x'] = 1;
                    $sel_area['y'] = $this->RegionI18n->find('first', array('fields' => array('RegionI18n.region_id'), 'conditions' => array('RegionI18n.name' => $area['province'])));
                    if ($area['province'] != $area['city']) {
                        $sel_area['z'] = $this->RegionI18n->find('first', array('fields' => array('RegionI18n.region_id'), 'conditions' => array('RegionI18n.name' => $area['city'])));
                    }
                } else {
                    $tmp_city_lv1 = $this->RegionI18n->find('first', array('fields' => array('RegionI18n.region_id'), 'conditions' => array('RegionI18n.name' => $area['country'])));
                    $sel_area['x'] = $tmp_city_lv1['RegionI18n']['region_id'];
                    $sel_area['y'] = $this->RegionI18n->find('first', array('fields' => array('RegionI18n.region_id'), 'conditions' => array('RegionI18n.name' => $area['province'])));
                    if ($area['province'] != $area['city']) {
                        $sel_area['z'] = $this->RegionI18n->find('first', array('fields' => array('RegionI18n.region_id'), 'conditions' => array('RegionI18n.name' => $area['city'])));
                    }
                }
            }
        }
        return $sel_area;
    }

    //ip地址查询(sina json格式)
    public function get_ip_place($ip)
    {
        try {
            $ip = file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip='.$ip);
            preg_match('/var remote_ip_info = (.*);/', $ip, $m);
            $tt = json_decode($m[1]);
            $tt = $this->object_array($tt);
        } catch (Exception $e) {
            $tt = $e->getMessage();
        }
        //$ip=json_decode($ip);
        return $tt;
    }

    //把json数据转换为array
    public function object_array($array)
    {
        if (is_object($array)) {
            $array = (array) $array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
    }

    public function real_ip()
    {
        static $realip = null;
        if ($realip !== null) {
            return $realip;
        }
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr as $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realip = $ip;
                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $realip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $realip = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }
        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
        return $realip;
    }

    //促销入口函数
    public function auto_confirm_promotion()
    {
        $promotions = $this->findpromotions();
        unset($_SESSION['svcart']['promotion']);
        if (!empty($promotions)) {
            foreach ($promotions as $k => $v) {
                //添加促销
                if ($v['Promotion']['type'] == 0) {
                    $promotion_info = array(
                        'id' => $v['Promotion']['id'],
                        'promotion_fee' => $v['Promotion']['type_ext'],
                        'meta_description' => $v['PromotionI18n']['meta_description'],
                        'type' => $v['Promotion']['type'],
                        'title' => $v['PromotionI18n']['title'],
                        'point_multiples' => $v['Promotion']['point_multiples'],
                        //'products'=>$v['products'],
                        //	'related_product_ids'=>$v['related_product_ids'],
                        //	'pro_ids'=>$v['pro_ids']
                    );
                } else {
                    $promotion_info = array(
                        'id' => $v['Promotion']['id'],
                        'promotion_fee' => $v['Promotion']['type_ext'],
                        'meta_description' => $v['PromotionI18n']['meta_description'],
                        'type' => $v['Promotion']['type'],
                        'title' => $v['PromotionI18n']['title'],
                        'point_multiples' => $v['Promotion']['point_multiples'],
                        //'products'=>$v['products'],
                        'related_product_ids' => $v['related_product_ids'],
                        'pro_ids' => $v['pro_ids'],
                    );
                }
                $_SESSION['svcart']['promotion'][$v['Promotion']['id']] = $promotion_info;
                //unset($_SESSION['svcart']);
                if ($v['Promotion']['type'] == '2' && isset($v['products']) && sizeof($v['products']) > 0) {
                    //特惠品
                    $_SESSION['svcart']['promotion'][$v['Promotion']['id']]['promotion_fee'] = 0;
                    /*
                    foreach($v['products'] as $a=>$b){
                        $product_promotion = array(
                                            'promotion_id' => $v['Promotion']['id'],
                                            'product_id' => $b['Product']['id']
                        );
                        $this->add_promotion_product($product_promotion);
                    }
                    */
                } elseif ($v['Promotion']['type'] == '1') {
                    //折扣
                } elseif ($v['Promotion']['type'] == '0') {
                    //减免
                }
            }
        }
        $this->static_promotion();
    }

    //促销入口函数
    public function checkout_confirm_promotion()
    {
        $promotions = $this->checkout_findpromotions();
        unset($_SESSION['checkout']['promotion']);
        if (!empty($promotions)) {
            foreach ($promotions as $k => $v) {
                //添加促销
                if ($v['Promotion']['type'] == 0) {
                    $promotion_info = array(
                        'id' => $v['Promotion']['id'],
                        'promotion_fee' => $v['Promotion']['type_ext'],
                        'meta_description' => $v['PromotionI18n']['meta_description'],
                        'type' => $v['Promotion']['type'],
                        'title' => $v['PromotionI18n']['title'],
                        'point_multiples' => $v['Promotion']['point_multiples'],
                        //'products'=>$v['products'],
                        //	'related_product_ids'=>$v['related_product_ids'],
                        //	'pro_ids'=>$v['pro_ids']
                    );
                } else {
                    $promotion_info = array(
                        'id' => $v['Promotion']['id'],
                        'promotion_fee' => $v['Promotion']['type_ext'],
                        'meta_description' => $v['PromotionI18n']['meta_description'],
                        'type' => $v['Promotion']['type'],
                        'title' => $v['PromotionI18n']['title'],
                        'point_multiples' => $v['Promotion']['point_multiples'],
                        //'products'=>$v['products'],
                        'related_product_ids' => $v['related_product_ids'],
                        'pro_ids' => $v['pro_ids'],
                    );
                }
                $_SESSION['checkout']['promotion'][$v['Promotion']['id']] = $promotion_info;
                //unset($_SESSION['checkout']);
                if ($v['Promotion']['type'] == '2' && isset($v['products']) && sizeof($v['products']) > 0) {
                    //特惠品
                    $_SESSION['checkout']['promotion'][$v['Promotion']['id']]['promotion_fee'] = 0;
                    /*
                    foreach($v['products'] as $a=>$b){
                        $product_promotion = array(
                                            'promotion_id' => $v['Promotion']['id'],
                                            'product_id' => $b['Product']['id']
                        );
                        $this->add_promotion_product($product_promotion);
                    }
                    */
                } elseif ($v['Promotion']['type'] == '1') {
                    //折扣
                } elseif ($v['Promotion']['type'] == '0') {
                    //减免
                }
            }
        }
        $this->static_promotion_checkout();
    }

    //促销特惠品选择变更（ajax）
    public function promotion_product_change()
    {
        $this->layout = 'ajax';
        //Configure::write('debug',0);
        if (!empty($_POST['type'])) {
            $product_promotion = array(
                'promotion_id' => $_POST['promotion_id'],
                'product_id' => $_POST['product_id'],
            );
            if ($_POST['type'] == 'del') {
                $this->del_promotion_product($product_promotion);
            } else {
                $this->add_promotion_product1($product_promotion);
            }
        }
        $this->static_promotion();
    }

    //添加促销特惠品
    public function add_promotion_product1($promotion_arr = '')
    {
        $this->Promotion->set_locale($this->locale);
        $promotions = $this->findpromotions($promotion_arr['promotion_id']);
        foreach ($promotions as $k => $v) {
            if ($v['Promotion']['id'] == $promotion_arr['promotion_id'] && !empty($_SESSION['svcart']['promotion'][$v['Promotion']['id']])) {
                foreach ($v['products'] as $key => $value) {
                    if ($value['Product']['id'] == $promotion_arr['product_id']) {
                        $_SESSION['svcart']['Product_by_Promotion'][$promotion_arr['promotion_id']][$key] = $value;
                    }
                }
            }
        }
    }

    //删除促销特惠品
    public function del_promotion_product($product_promotion)
    {
        //pr($_SESSION['svcart']['Product_by_Promotion'][$product_promotion['promotion_id']]);
        if (!empty($_SESSION['svcart']['Product_by_Promotion'][$product_promotion['promotion_id']][$product_promotion['product_id']])) {
            if (!empty($_SESSION['svcart']['promotion']['product_fee']) && $_SESSION['svcart']['Product_by_Promotion'][$product_promotion['promotion_id']][$product_promotion['product_id']]['Product']['now_fee'] > 0 && $_SESSION['svcart']['promotion']['product_fee'] >= $_SESSION['svcart']['Product_by_Promotion'][$product_promotion['promotion_id']][$product_promotion['product_id']]['Product']['now_fee']) {
                $_SESSION['svcart']['promotion']['product_fee'] -= $_SESSION['svcart']['Product_by_Promotion'][$product_promotion['promotion_id']][$product_promotion['product_id']]['Product']['now_fee'];
                $_SESSION['svcart']['cart_info']['total'] -= $_SESSION['svcart']['Product_by_Promotion'][$product_promotion['promotion_id']][$product_promotion['product_id']]['Product']['now_fee'];
            }
            unset($_SESSION['svcart']['Product_by_Promotion'][$product_promotion['promotion_id']][$product_promotion['product_id']]);
        }
    }

    //促销清算
    public function static_promotion()
    {
        //检查特惠品，去除不再享有的特惠品
        if (!empty($_SESSION['svcart']['Product_by_Promotion'])) {
            foreach ($_SESSION['svcart']['Product_by_Promotion'] as $k => $v) {
                if (empty($_SESSION['svcart']['promotion'][$k])) {
                    unset($_SESSION['svcart']['Product_by_Promotion'][$k]);
                } else {
                    foreach ($v as $kk => $vv) {
                        //pr($vv);pr($_SESSION['svcart']['promotion'][$k]);
                        if (!in_array($vv['Product']['id'], $_SESSION['svcart']['promotion'][$k]['pro_ids'])) {
                            unset($_SESSION['svcart']['Product_by_Promotion'][$k][$kk]);
                        }
                    }
                }
            }
        }
        //检查特惠品，去除不再享有的特惠品
        if (!empty($_SESSION['svcart']['products'])) {
            foreach ($_SESSION['svcart']['products'] as $k => $v) {
                if (isset($_SESSION['svcart']['products'][$k]['promotion_discount'])) {
                    unset($_SESSION['svcart']['products'][$k]['promotion_discount']);
                }
                if (isset($_SESSION['svcart']['products'][$k]['promotion_price'])) {
                    unset($_SESSION['svcart']['products'][$k]['promotion_price']);
                }
                if (isset($_SESSION['svcart']['products'][$k]['promotion_point_multiples'])) {
                    unset($_SESSION['svcart']['products'][$k]['promotion_point_multiples']);
                }
                if (isset($_SESSION['svcart']['products'][$k]['promotions_name_arr'])) {
                    unset($_SESSION['svcart']['products'][$k]['promotions_name_arr']);
                }
            }
        }
        if (!empty($_SESSION['svcart']['promotion'])) {
            $_SESSION['svcart']['cart_info']['sum_market_subtotal'] = 0;
            foreach ($_SESSION['svcart']['promotion'] as $k => $v) {
                if ($v['type'] == 2) {
                    //特惠品
                    $_SESSION['svcart']['promotion'][$k]['promotion_fee'] = 0;
                    if (!empty($_SESSION['svcart']['Product_by_Promotion'][$v['id']])) {
                        foreach ($_SESSION['svcart']['Product_by_Promotion'][$v['id']] as $pro_product) {
                            $_SESSION['svcart']['promotion'][$k]['promotion_fee'] += $pro_product['Product']['now_fee'];
                            $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $pro_product['Product']['market_price'];
                            //加上特惠品优惠金额
                            //$_SESSION['svcart']['cart_info']['sum_discount'] += $pro_product['Product']['market_price']-$pro_product['Product']['now_fee'];
                        }
                    }
                } elseif ($v['type'] == 1) {
                    //折扣（算入商品小计）
                    foreach ($_SESSION['svcart']['products'] as $kk => $vv) {
                        if (empty($v['related_product_ids']) || in_array($vv['Product']['id'], $v['related_product_ids'])) {
                            $_SESSION['svcart']['products'][$kk]['promotion_discount'] = $v['promotion_fee'];
                            $_SESSION['svcart']['products'][$kk]['promotion_price'] = $vv['Product']['shop_price'] * $v['promotion_fee'] / 100;
                            $_SESSION['svcart']['products'][$kk]['subtotal'] = $_SESSION['svcart']['products'][$kk]['promotion_price'] * $vv['quantity'];
                            $_SESSION['svcart']['products'][$kk]['promotions_name_arr'][$v['id']] = array('title' => $v['title'],'type' => $v['type']);
                        }
                    }
                } elseif ($v['type'] == 0) {
                    //减免
                    foreach ($_SESSION['svcart']['products'] as $kk => $vv) {
                        if (empty($v['related_product_ids']) || in_array($vv['Product']['id'], $v['related_product_ids'])) {
                            //$_SESSION['svcart']['products'][$kk]['promotion_discount'] = $v['promotion_fee'];
                            $_SESSION['svcart']['products'][$kk]['promotions_name_arr'][$v['id']] = array('title' => $v['title'],'type' => $v['type']);
                            //加上减免品减免金额
                            //$_SESSION['svcart']['cart_info']['sum_discount'] += $pro_product['Product']['market_price']-$_SESSION['svcart']['promotion'][$k]['promotion_fee'];
                        }
                    }
                }
                //积分特惠
                if ($v['point_multiples'] > 1) {
                    foreach ($_SESSION['svcart']['products'] as $kk => $vv) {
                        if (in_array($vv['Product']['id'], $v['related_product_ids'])) {
                            $_SESSION['svcart']['products'][$kk]['promotion_point_multiples'] = $v['point_multiples'];
                            //$_SESSION['svcart']['products'][$kk]['promotion_point_multiples'] = $v['point_multiples']*$_SESSION['svcart']['products'][$kk][''];
                            $_SESSION['svcart']['products'][$kk]['promotions_name_arr'][$v['id']] = array('title' => $v['title'],'type' => $v['type']);
                        }
                    }
                }
            }
            $_SESSION['svcart']['cart_info']['product_subtotal'] = 0;
            $_SESSION['svcart']['cart_info']['sum_discount'] = 0;
            foreach ($_SESSION['svcart']['products'] as $kk => $vv) {
                $_SESSION['svcart']['cart_info']['product_subtotal'] += $_SESSION['svcart']['products'][$kk]['subtotal'];
                $_SESSION['svcart']['cart_info']['sum_market_subtotal'] += $_SESSION['svcart']['products'][$kk]['Product']['market_price'] * $_SESSION['svcart']['products'][$kk]['quantity'];
            }
            foreach ($_SESSION['svcart']['promotion'] as $k => $v) {
                if ($v['type'] == 2) {
                    //特惠品
                    $_SESSION['svcart']['cart_info']['product_subtotal'] += $_SESSION['svcart']['promotion'][$k]['promotion_fee'];
                    $_SESSION['svcart']['cart_info']['total'] += $_SESSION['svcart']['promotion'][$k]['promotion_fee'];
                } elseif ($v['type'] == 1) {//折扣
                } elseif ($v['type'] == 0) {
                    //减免
                    $_SESSION['svcart']['cart_info']['total'] = $_SESSION['svcart']['cart_info']['total'] - $_SESSION['svcart']['promotion'][$k]['promotion_fee'];
                    $_SESSION['svcart']['cart_info']['sum_discount'] += $_SESSION['svcart']['promotion'][$k]['promotion_fee'];
                }
            }
            $_SESSION['svcart']['cart_info']['sum_subtotal'] = $_SESSION['svcart']['cart_info']['product_subtotal'];
            $_SESSION['svcart']['cart_info']['sum_discount'] += $_SESSION['svcart']['cart_info']['sum_market_subtotal'] - $_SESSION['svcart']['cart_info']['sum_subtotal'];
        }
    }

    public function aim_check($isPost = '', $card_num = '', $card_name = '', $card_cavv = '', $cdate = '')
    {
        $this->page_init();
        $this->layout = 'ajax';
        if (!empty($card_num) && !empty($card_name) && !empty($cdate) && !empty($card_cavv) && !empty($isPost)) {
            $cdate = explode('_', $cdate);
            //判断过期时间
            if (($cdate[0] > date('m') && $cdate[1] == date('Y')) || $cdate[1] > date('Y')) {
                App::import('Vendor', 'payments/authorizenet_aim');
                //判断卡号规范
                $aim = new authorizenet_aim();
                if ($aim->validateCreditcard_number($card_num) == 'This is a valid credit card number') {
                    $_SESSION['aim']['card_num'] = $card_num;
                    $_SESSION['aim']['card_name'] = $card_name;
                    $_SESSION['aim']['cdate'] = $cdate[0].substr($cdate[1], -2);
                    $_SESSION['aim']['month'] = $cdate[0];
                    $_SESSION['aim']['year'] = $cdate[1];
                    $_SESSION['aim']['card_cavv'] = $card_cavv;
                    $this->set('x', 1);
                } else {
                    $this->set('x', 3);
                }
            } else {
                $this->set('x', 2);
            }
        } elseif (!empty($isPost)) {
            $this->set('x', 0);
        }
    }

    public function aim_card_split($aim)
    {
        $foo = str_split($aim, 4);
        $aim_str = $foo[0].'-xxxx-xxxx-'.$foo[3];
        return $aim_str;
    }

    public function fee_format($fee, $price)
    {
        $foo = substr($fee, -1);
        if ($foo == '%') {
            $fee = $fee / 100;
            $price += $fee * $price;
        } else {
            $price += $fee;
        }
        return $price;
    }

    public function fee_format_no_price($fee, $price)
    {
        $foo = substr($fee, -1);
        if ($foo == '%') {
            $fee = $fee / 100;
            $fee = $fee * $price;
        }
        return $fee;
    }

    //促销清算
    public function static_promotion_checkout()
    {
        //检查特惠品，去除不再享有的特惠品
        if (!empty($_SESSION['checkout']['Product_by_Promotion'])) {
            foreach ($_SESSION['checkout']['Product_by_Promotion'] as $k => $v) {
                if (empty($_SESSION['checkout']['promotion'][$k])) {
                    unset($_SESSION['checkout']['Product_by_Promotion'][$k]);
                } else {
                    foreach ($v as $kk => $vv) {
                        if (!in_array($vv['Product']['id'], $_SESSION['checkout']['promotion'][$k]['pro_ids'])) {
                            unset($_SESSION['checkout']['Product_by_Promotion'][$k][$kk]);
                        }
                    }
                }
            }
        }
        //检查特惠品，去除不再享有的特惠品
        if (!empty($_SESSION['checkout']['products'])) {
            foreach ($_SESSION['checkout']['products'] as $k => $v) {
                if (isset($_SESSION['checkout']['products'][$k]['promotion_discount'])) {
                    unset($_SESSION['checkout']['products'][$k]['promotion_discount']);
                }
                if (isset($_SESSION['checkout']['products'][$k]['promotion_price'])) {
                    unset($_SESSION['checkout']['products'][$k]['promotion_price']);
                }
                if (isset($_SESSION['checkout']['products'][$k]['promotion_point_multiples'])) {
                    unset($_SESSION['checkout']['products'][$k]['promotion_point_multiples']);
                }
                if (isset($_SESSION['checkout']['products'][$k]['promotions_name_arr'])) {
                    unset($_SESSION['checkout']['products'][$k]['promotions_name_arr']);
                }
            }
        }
        if (!empty($_SESSION['checkout']['promotion'])) {
            $_SESSION['checkout']['cart_info']['sum_market_subtotal'] = 0;
            foreach ($_SESSION['checkout']['promotion'] as $k => $v) {
                if ($v['type'] == 2) {
                    //特惠品
                    $_SESSION['checkout']['promotion'][$k]['promotion_fee'] = 0;
                    if (!empty($_SESSION['checkout']['Product_by_Promotion'][$v['id']])) {
                        foreach ($_SESSION['checkout']['Product_by_Promotion'][$v['id']] as $pro_product) {
                            $_SESSION['checkout']['promotion'][$k]['promotion_fee'] += $pro_product['Product']['now_fee'];
                            $_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $pro_product['Product']['market_price'];
                            //加上特惠品优惠金额
                            //$_SESSION['checkout']['cart_info']['sum_discount'] += $pro_product['Product']['market_price']-$pro_product['Product']['now_fee'];
                        }
                    }
                } elseif ($v['type'] == 1) {
                    //折扣（算入商品小计）
                    foreach ($_SESSION['checkout']['products'] as $kk => $vv) {
                        if (empty($v['related_product_ids']) || in_array($vv['Product']['id'], $v['related_product_ids'])) {
                            $_SESSION['checkout']['products'][$kk]['promotion_discount'] = $v['promotion_fee'];
                            $_SESSION['checkout']['products'][$kk]['promotion_price'] = $vv['Product']['shop_price'] * $v['promotion_fee'] / 100;
                            $_SESSION['checkout']['products'][$kk]['subtotal'] = $_SESSION['checkout']['products'][$kk]['promotion_price'] * $vv['quantity'];
                            $_SESSION['checkout']['products'][$kk]['promotions_name_arr'][$v['id']] = array('title' => $v['title'],'type' => $v['type']);
                        }
                    }
                } elseif ($v['type'] == 0) {
                    //减免
                    foreach ($_SESSION['checkout']['products'] as $kk => $vv) {
                        if (empty($v['related_product_ids']) || in_array($vv['Product']['id'], $v['related_product_ids'])) {
                            $_SESSION['checkout']['products'][$kk]['promotions_name_arr'][$v['id']] = array('title' => $v['title'],'type' => $v['type']);
                        }
                    }
                }
                //积分特惠
                if ($v['point_multiples'] > 1) {
                    foreach ($_SESSION['checkout']['products'] as $kk => $vv) {
                        if (in_array($vv['Product']['id'], $v['related_product_ids'])) {
                            $_SESSION['checkout']['products'][$kk]['promotion_point_multiples'] = $v['point_multiples'];
                            //$_SESSION['checkout']['products'][$kk]['promotion_point_multiples'] = $v['point_multiples']*$_SESSION['checkout']['products'][$kk][''];
                            $_SESSION['checkout']['products'][$kk]['promotions_name_arr'][$v['id']] = array('title' => $v['title'],'type' => $v['type']);
                        }
                    }
                }
            }
            $_SESSION['checkout']['cart_info']['product_subtotal'] = 0;
            $_SESSION['checkout']['cart_info']['sum_discount'] = 0;
            foreach ($_SESSION['checkout']['products'] as $kk => $vv) {
                $_SESSION['checkout']['cart_info']['product_subtotal'] += $_SESSION['checkout']['products'][$kk]['subtotal'];
                $_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $_SESSION['checkout']['products'][$kk]['Product']['market_price'] * $_SESSION['checkout']['products'][$kk]['quantity'];
            }
            foreach ($_SESSION['checkout']['promotion'] as $k => $v) {
                if ($v['type'] == 2) {
                    //特惠品
                    $_SESSION['checkout']['cart_info']['product_subtotal'] += $_SESSION['checkout']['promotion'][$k]['promotion_fee'];
                    $_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['promotion'][$k]['promotion_fee'];
                } elseif ($v['type'] == 1) {//折扣
                } elseif ($v['type'] == 0) {
                    //减免
                    $_SESSION['checkout']['cart_info']['total'] = $_SESSION['checkout']['cart_info']['total'] - $_SESSION['checkout']['promotion'][$k]['promotion_fee'];
                    $_SESSION['checkout']['cart_info']['sum_discount'] += $_SESSION['checkout']['promotion'][$k]['promotion_fee'];
                }
            }
            $_SESSION['checkout']['cart_info']['sum_subtotal'] = $_SESSION['checkout']['cart_info']['product_subtotal'];
            $_SESSION['checkout']['cart_info']['sum_discount'] += $_SESSION['checkout']['cart_info']['sum_market_subtotal'] - $_SESSION['checkout']['cart_info']['sum_subtotal'];
        }
    }

    public function changeCart()
    {
        Configure::write('debug',0);
        $this->layout = 'ajax';
        $selectedIds = $_POST['selectedIds'];
        $selectedIds = explode(',', $selectedIds);
        //统计商品价格
//		$_SESSION['checkout']['cart_info']['sum_quantity']=0;
//		$_SESSION['checkout']['cart_info']['sum_subtotal'] = 0;
//		$_SESSION['checkout']['cart_info']['sum_discount'] = 0;
//		$_SESSION['checkout']['cart_info']['sum_market_subtotal']=0;
        unset($_SESSION['checkout']['products']);
        if (isset($_SESSION['svcart']['products']) && sizeof($_SESSION['svcart']['products']) > 0) {
            foreach ($_SESSION['svcart']['products'] as $k => $v) {
                if (in_array($k, $selectedIds)) {
                    $_SESSION['checkout']['products'][$k] = $v;
//					$_SESSION['checkout']['cart_info']['sum_weight'] += $v['Product']['weight']*$v['quantity'];
//	                $_SESSION['checkout']['cart_info']['sum_quantity'] += $v['quantity'];
                }
            }
            $this->statistic_checkout();
            $this->checkout_order_price();
//			$_SESSION['checkout']['cart_info']['all_product'] = $_SESSION['svcart']['cart_info']['sum_subtotal'];
        }
        unset($_SESSION['checkout']['bespoke']);
        if (isset($_SESSION['svcart']['bespoke']) && sizeof($_SESSION['svcart']['bespoke']) > 0) {
            foreach ($_SESSION['svcart']['bespoke'] as $k => $v) {
                if (in_array($k, $selectedIds)) {
                    $_SESSION['checkout']['bespoke'][$k] = $v;
//					$_SESSION['checkout']['cart_info']['sum_weight'] += $v['Product']['weight']*$v['quantity'];
//	                $_SESSION['checkout']['cart_info']['sum_quantity'] += $v['quantity'];
                }
            }
            $this->statistic_checkout();
            $this->checkout_order_price();
//			$_SESSION['checkout']['cart_info']['all_product'] = $_SESSION['svcart']['cart_info']['sum_subtotal'];
        }
//		if(isset($_SESSION['checkout']['products'])){
//			$this->static_promotion_checkout();
//		}
//		if(isset($_SESSION['checkout']['cards']) && sizeof($_SESSION['checkout']['cards'])>0){
//			foreach($_SESSION['checkout']['cards'] as $k=>$v){
//				$_SESSION['checkout']['cart_info']['sum_subtotal'] += $v['subtotal'] ;
//				$_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $v['subtotal'] ;
//			}
//		}
//		if(isset($_SESSION['checkout']['packagings']) && sizeof($_SESSION['checkout']['packagings'])>0){
//			foreach($_SESSION['checkout']['packagings'] as $k=>$v){
//				$_SESSION['checkout']['cart_info']['sum_subtotal'] += $v['subtotal'] ;
//				$_SESSION['checkout']['cart_info']['sum_market_subtotal'] += $v['subtotal'] ;
//			}
//		}
//		if($_SESSION['checkout']['cart_info']['sum_market_subtotal'] > 0){
//			$_SESSION['checkout']['cart_info']['discount_rate'] = round($_SESSION['checkout']['cart_info']['sum_subtotal']/$_SESSION['checkout']['cart_info']['sum_market_subtotal']*100);
//		}else{
//			$_SESSION['checkout']['cart_info']['discount_rate'] = 100;
//		}
//		$_SESSION['checkout']['cart_info']['discount_price'] = $_SESSION['checkout']['cart_info']['sum_market_subtotal']-$_SESSION['checkout']['cart_info']['shop_subtotal'];
//		$_SESSION['checkout']['cart_info']['total'] = $_SESSION['checkout']['cart_info']['sum_subtotal'];
//	///	echo $_SESSION['checkout']['cart_info']['total'].'a<br>';
//		if(isset($_SESSION['checkout']['shipping']['shipping_fee'])&&$_SESSION['checkout']['shipping']['shipping_fee']!=""){
//
//			$_SESSION['checkout']['cart_info']['total'] =$this->fee_format($_SESSION['checkout']['shipping']['shipping_fee'],$_SESSION['checkout']['cart_info']['total']);
//		}
//		if(isset($_SESSION['checkout']['shipping']['insure_fee_confirm'])&&$_SESSION['checkout']['shipping']['shipping_fee']!=""){
//			$_SESSION['checkout']['cart_info']['total']  =$this->fee_format($_SESSION['checkout']['shipping']['insure_fee_confirm'],$_SESSION['checkout']['cart_info']['total']);
//		}
//		if(isset($_SESSION['checkout']['payment']['payment_fee'])&&$_SESSION['checkout']['payment']['payment_fee']!=""){
//			$_SESSION['checkout']['payment']['payment_fee']=$this->fee_format_no_price($_SESSION['checkout']['payment']['payment_fee'],$_SESSION['checkout']['cart_info']['total']);
//			$_SESSION['checkout']['cart_info']['total'] =$this->fee_format($_SESSION['checkout']['payment']['payment_fee'],$_SESSION['checkout']['cart_info']['total']);
//		}
//		if(isset($_SESSION['checkout']['point']['fee'])){
//			$_SESSION['checkout']['cart_info']['total'] -= $_SESSION['checkout']['point']['fee'];
//		}
//		//如果有优惠券的话  chenfan 2012/05/30
//
//		if(isset($_SESSION['checkout']['coupon']) && sizeof($_SESSION['checkout']['coupon'])>0){
//			$old_total = $_SESSION['checkout']['cart_info']['total'];
//			$total = $_SESSION['checkout']['cart_info']['total'];
//			foreach($_SESSION['checkout']['coupon'] as $sc){
//				$total = ($total-$sc['fee'])*$sc['discount']/100;
//			}
//			$_SESSION['checkout']['cart_info']['coupon_del'] = round($old_total-$total,2);
//			$_SESSION['checkout']['cart_info']['total'] = $total;
//		}
//
//		if(isset($_SESSION['checkout']['invoice']['tax_point'])){
//			$_SESSION['checkout']['invoice']['fee'] = round($_SESSION['checkout']['cart_info']['sum_subtotal']*$_SESSION['checkout']['invoice']['tax_point']/100,2);
//			$_SESSION['checkout']['cart_info']['total'] += $_SESSION['checkout']['invoice']['fee'];
//		}
        $this->set('sum_discount_ajax', isset($_SESSION['checkout']['cart_info']['sum_discount']) ? $_SESSION['checkout']['cart_info']['sum_discount'] : $_SESSION['checkout']['cart_info']['discount_price']);
        //租赁
        $this->set('les_subtotal_ajax', $_SESSION['checkout']['cart_info']['lease_subtotal']);
        $this->set('sum_subtotal_ajax', $_SESSION['checkout']['cart_info']['sum_subtotal']);
        $this->set('sum_market_subtotal', $_SESSION['checkout']['cart_info']['sum_market_subtotal']);
        $this->render('ajax_cart');
    }

    /*判断购物车中商品保存的时间*/
    public function check_cart_time()
    {
        if (isset($this->configs['cart_time'])) {
            //$time=date("Y-m-d H:i:s",strtotime("-1 day"));
            if ($this->configs['cart_time'] == 0) {
                //保留1天
                $cart_time = date('Y-m-d H:i:s', strtotime('-1 day'));
                $condition = array('Cart.modified <' => $cart_time);
                $this->Cart->deleteAll($condition);
                $this->CartProductValue->deleteAll(array('CartProductValue.modified <' => $cart_time));
            } elseif ($this->configs['cart_time'] == 1) {
                //Configure::write('debug',2);
                //保留1周
                $cart_time = date('Y-m-d H:i:s', strtotime('-7 day'));
                $condition = array('Cart.modified <' => $cart_time);
                $this->Cart->deleteAll($condition);
                $this->CartProductValue->deleteAll(array('CartProductValue.modified <' => $cart_time));
                //die;
            } elseif ($this->configs['cart_time'] == 2) {
                //保留1月
                $cart_time = date('Y-m-d H:i:s', strtotime('-30 day'));
                $condition = array('Cart.modified <' => $cart_time);
                $this->Cart->deleteAll($condition);
                $this->CartProductValue->deleteAll(array('CartProductValue.modified <' => $cart_time));
            }
        }
    }
}