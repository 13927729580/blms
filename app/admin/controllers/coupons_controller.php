<?php

/*****************************************************************************
 * Seevia 优惠卷介绍
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
class CouponsController extends AppController
{
    public $name = 'Coupons';
    public $components = array('Pagination','RequestHandler','Notify');
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    public $uses = array('Coupon','CouponType','CouponTypeI18n','User','CategoryProduct','Brand','Product','Order','NotifyTemplateType','CouponProduct','OperatorLog');
    //var $layout="default";
    public function index($page = 1)
    {
        /*判断权限*/
        //$this->operator_privilege('topics_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/oms/','sub' => '/coupons/');
        /*end*/
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['rebate_012'],'url' => '/coupons/');

        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('coupontype'), $this->locale);
        $this->set('Resource_info', $Resource_info);
        $this->CouponType->set_locale($this->locale);
        $condition = '';
        if (isset($this->params['url']['send_type']) && $this->params['url']['send_type'] != '-1') {
            $condition['and']['CouponType.send_type'] = $this->params['url']['send_type'];
            $send_type = $this->params['url']['send_type'];
            $this->set('send_type', $send_type);
            
        }
        if (isset($this->params['url']['keywords']) && $this->params['url']['keywords'] != '') {
        	$coupon_condition=array();
        	$coupon_condition['Coupon.sn_code like']="%".$this->params['url']['keywords']."%";
        	$couponids=$this->Coupon->find('list',array('conditions'=>$coupon_condition,"fields"=>"Coupon.coupon_type_id"));
        	if(!empty($couponids)){
        		$condition['and']['or']['CouponType.id'] = $couponids;
        	}
		$condition['and']['or']['CouponTypeI18n.name like'] = '%'.$this->params['url']['keywords'].'%';
		$keywords = $this->params['url']['keywords'];
		$this->set('keywords', $keywords);
        }
        $sortClass = 'CouponType';
        $total = $this->CouponType->find('count',array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'coupons','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'CouponType');
        $this->Pagination->init($condition, $parameters, $options);
        $data = $this->CouponType->find('all', array('conditions' => $condition, 'order' => 'CouponType.id', 'limit' => $rownum, 'page' => $page));
        if (!empty($data)) {
            $coupon_ids = array();//统计类型不是coupond的优惠券Id
            $type_is_coupon = array();//统计类型是coupon的优惠券Id
            foreach ($data as $k => $v) {
                $data[$k]['CouponType']['send_type_name'] = $Resource_info['coupontype'][$v['CouponType']['send_type']];
                if ($v['CouponType']['send_type'] != 5) {
                    $coupon_ids[] = $v['CouponType']['id'];
                } else {
                    $type_is_coupon[] = $v['CouponType']['id'];
                }
            }
            $count_coupons_data = array();
            $use_coupons_data = array();
            $type_is_coupon_use_coupons_data = array();
            $count_coupons_info = $this->Coupon->find('all', array('conditions' => array('Coupon.coupon_type_id' => array_merge($coupon_ids, $type_is_coupon)), 'fields' => array("Coupon.coupon_type_id,count('Coupon.id') as 'count'"), 'group' => 'Coupon.coupon_type_id'));
            $use_coupons_info = $this->Coupon->find('all', array('conditions' => array('Coupon.coupon_type_id' => $coupon_ids, 'Coupon.used_time >' => '2008-02-02'), 'fields' => array("Coupon.coupon_type_id,count('Coupon.id') as 'count'"), 'group' => 'Coupon.coupon_type_id'));
            foreach ($count_coupons_info as $v) {
                $count_coupons_data[$v['Coupon']['coupon_type_id']] = $v[0]['count'];
            }
            foreach ($use_coupons_info as $v) {
                $use_coupons_data[$v['Coupon']['coupon_type_id']] = $v[0]['count'];
            }
            if (!empty($type_is_coupon)) {
                $type_is_coupon_use_coupons_info = $this->Coupon->find('all', array('conditions' => array('Coupon.coupon_type_id' => $type_is_coupon), 'fields' => array("Coupon.coupon_type_id,sum(Coupon.max_use_quantity) as 'max_use',sum(Coupon.max_buy_quantity) as 'count_coupon'"), 'group' => 'Coupon.coupon_type_id'));
                foreach ($type_is_coupon_use_coupons_info as $v) {
                    $type_is_coupon_use_coupons_data[$v['Coupon']['coupon_type_id']]['count_coupon'] = $v[0]['count_coupon'];
                    $type_is_coupon_use_coupons_data[$v['Coupon']['coupon_type_id']]['max_use'] = $v[0]['max_use'];
                }
            }
            $this->set('count_coupons_data', $count_coupons_data);
            $this->set('use_coupons_data', $use_coupons_data);
            $this->set('type_is_coupon_use_coupons_data', $type_is_coupon_use_coupons_data);
        }
        $this->set('coupons', $data);
        $this->set('title_for_layout', $this->ld['rebate_012'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }
    public function list_view($id = 0, $page = 1)
    {
        /*判断权限*/
        //$this->operator_privilege('coupon_operation');
        /*end*/
        $this->menu_path = array('root' => '/oms/','sub' => '/coupons/');
        $this->set('title_for_layout', $this->ld['rebate_013'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['rebate_012'],'url' => '/coupons/');
        $this->navigations[] = array('name' => $this->ld['rebate_013'],'url' => '');

        $condition['Coupon.coupon_type_id'] = $id;
        $sortClass = 'Coupon';
        $total = $this->Coupon->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'coupons','action' => 'list_view','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Coupon');
        $this->Pagination->init($condition, $parameters, $options);
        $coupons = $this->Coupon->find('all', array('conditions' => $condition, 'order' => 'Coupon.id', 'limit' => $rownum, 'page' => $page));
        $order_id = array();
        if (is_array($coupons) && sizeof($coupons) > 0) {
            foreach ($coupons as $k => $v) {
                $user = $this->User->findbyid($v['Coupon']['user_id']);
                $coupons[$k]['Coupon']['user_name'] = $user['User']['name'];
                $order_id[] = $v['Coupon']['order_id'];
            }
        }
        $this->Order->belongsTo = array();
        $this->Order->hasMany = array();
        $order_info = $this->Order->find('all', array('conditions' => array('order_code' => $order_id), 'fields' => array('id', 'order_code')));
        $coupon_type = $this->CouponType->find('first', array('conditions' => array('CouponType.id' => $id)));
        $this->set('coupons', $coupons);
        $this->set('coupon_type_id', $id);
        $this->set('coupon_type', $coupon_type['CouponType']['send_type']);
    }

    public function view($id = '', $page = 1)
    {
        /*判断权限*/
        if (empty($id)) {
            //	$this->operator_privilege('topics_add');
        } else {
            //	$this->operator_privilege('topics_edit');
        }
        $this->menu_path = array('root' => '/oms/','sub' => '/coupons/');
        /*end*/
        $this->set('title_for_layout', $this->ld['rebate_014'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['rebate_012'],'url' => '/coupons/');
        //$this->navigations[]=array('name'=>'优惠卷编辑','url' => '');

        if ($this->RequestHandler->isPost()) {
            if ($this->data['CouponType']['min_products_amount'] == '') {
                $this->data['CouponType']['min_products_amount'] = 0;
            }
            if ($this->data['CouponType']['send_start_date'] != '') {
                $this->data['CouponType']['send_start_date'] = date('Y-m-d', strtotime($this->data['CouponType']['send_start_date'])).' 00:00:00';
            }
            if ($this->data['CouponType']['send_end_date'] != '') {
                $this->data['CouponType']['send_end_date'] = date('Y-m-d', strtotime($this->data['CouponType']['send_end_date'])).' 23:59:59';
            }
            if ($this->data['CouponType']['use_start_date'] != '') {
                $this->data['CouponType']['use_start_date'] = date('Y-m-d', strtotime($this->data['CouponType']['use_start_date'])).' 00:00:00';
            }
            if ($this->data['CouponType']['use_end_date'] != '') {
                $this->data['CouponType']['use_end_date'] = date('Y-m-d', strtotime($this->data['CouponType']['use_end_date'])).' 23:59:59';
            }
            if (isset($this->data['CouponType']['id']) && $this->data['CouponType']['id'] != 0) {
                $this->CouponType->saveAll($this->data['CouponType']); //关联保存
            } else {
                $this->CouponType->saveAll($this->data['CouponType']); //关联保存
                $id = $this->CouponType->getLastInsertId();
            }
            $this->CouponTypeI18n->deleteall(array('coupon_type_id' => $this->data['CouponType']['id'])); //删除原有多语言
            foreach ($this->data['CouponTypeI18n'] as $k => $v) {
                $couponI18n_info = array(
                    'id' => isset($v['id']) ? $v['id'] : '',
                    'locale' => $k,
                    'coupon_type_id' => isset($v['coupon_type_id']) ? $v['coupon_type_id'] : $id,
                    'name' => isset($v['name']) ? $v['name'] : '',
                    'description' => isset($v['description']) ? $v['description'] : '',
                );
                $this->CouponTypeI18n->saveall(array('CouponTypeI18n' => $couponI18n_info)); //更新多语言
            }
            $id = $this->CouponType->id;
            //如果不是按商品发放的删除所有相关商品
            if ($this->data['CouponType']['send_type'] != 1) {
                $cp_info['coupon_type_id'] = $id;
                $this->CouponProduct->deleteall($cp_info);
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['rebate_015'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->data = $this->CouponType->localeformat($id);
        if (!empty($this->data['CouponTypeI18n'][$this->backend_locale]['name'])) {
            $this->navigations[] = array('name' => $this->data['CouponTypeI18n'][$this->backend_locale]['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['rebate_001'],'url' => '');
        }

        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('coupontype'), $this->locale);
        $this->set('Resource_info', $Resource_info);
        $category_tree = $this->CategoryProduct->tree('P','all', $this->locale);//分类树
        $brand_tree = $this->Brand->brand_tree($this->backend_locale);//品牌获取
        //获取当前类别下商品id的集合	    CouponProduct
        if (!empty($this->data) && $this->data['CouponType']['send_type'] == 1) {
            $pIds = $this->CouponProduct->find('list', array('conditions' => array('CouponProduct.coupon_type_id' => $this->data['CouponType']['id']), 'fields' => 'CouponProduct.product_id'));
            $this->Product->set_locale($this->backend_locale);
            $product_arr = $this->Product->find('all', array('conditions' => array('Product.id' => $pIds)));
            $this->set('product_relations', $product_arr);
        }
        $this->set('category_tree', $category_tree);
        $this->set('brand_tree', $brand_tree);
    }

    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = 'fail';
        /*判断权限*/
        //$this->operator_privilege('special_topic_operation');
        /*end*/
        $coupon_info = $this->CouponType->find('first', array('conditions' => array('CouponType.id' => $id)));
        //pr($coupon_info);
        //die();
        $this->CouponType->deleteall("CouponType.id = '".$id."'", false);
        $this->CouponTypeI18n->deleteall("CouponTypeI18n.coupon_type_id = '".$id."'", false); //删除原有多语言
        $result['flag'] = 1;
        $result['message'] = 'sucess';
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除优惠券:id '.$id.' '.$coupon_info['CouponTypeI18n']['name'], $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        //$result["flag"] = 1;
        die(json_encode($result));
    }
    //批量处理
    public function batch()
    {
        if ($this->RequestHandler->isPost()) {
            foreach ($_REQUEST['checkboxes'] as $k => $v) {
                $this->CouponType->deleteAll(array('CouponType.id' => $v));
            }
        }
        //操作员日志
        if ($this->configs['operactions-log'] && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }

    public function remove_coupon($id)
    {
        $this->Coupon->deleteAll("Coupon.id='$id'");
        $result['flag'] = 1;
        $result['message'] = 'sucess';
        //$this->flash("删除成功",'/coupons/',10);
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //批量处理
    public function batch_remove_coupon()
    {
        if ($this->RequestHandler->isPost()) {
            foreach ($_REQUEST['checkboxes'] as $k => $v) {
                $this->Coupon->deleteAll(array('Coupon.id' => $v));
            }
        }
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        $this->redirect('/coupons/list_view/'.$_REQUEST['coupon_type_id']);
    }

    public function toggle_on_status()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->CouponType->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function send($id = 0)
    {
        $this->menu_path = array('root' => '/oms/','sub' => '/coupons/');
        $this->set('title_for_layout', $this->ld['rebate_091'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['promotion_section'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['rebate_012'],'url' => '/coupons/');
        $this->navigations[] = array('name' => $this->ld['rebate_091'],'url' => '');

        $this->CouponType->set_locale($this->locale);
        $coupontype = $this->CouponType->find('first', array('conditions' => array('CouponType.id' => $id)));

        if (empty($coupontype)) {
            $this->redirect('/coupons/');
        }
        if ($coupontype['CouponType']['send_type'] == 0) {
            $user_ids = $this->Coupon->find('list', array('conditions' => array('Coupon.coupon_type_id' => $id), 'fields' => 'Coupon.user_id'));
            $user_infos = $this->User->find('all', array('conditions' => array('User.id' => $user_ids), 'fields' => 'User.id,User.name'));
            $this->set('user_relations', $user_infos);
        }

        if ($coupontype['CouponType']['send_type'] == 1) {
            $category_tree = $this->CategoryProduct->tree('P','all', $this->locale);//分类树
            $brand_tree = $this->Brand->brand_tree($this->backend_locale);//品牌获取
            $this->Product->set_locale($backend_locale);
            $product_arr = $this->Product->find('all', array('conditions' => array('Product.coupon_type_id' => $id)));
            $this->set('product_relations', $product_arr);
            $this->set('category_tree', $category_tree);
            $this->set('brand_tree', $brand_tree);
        }

        if ($coupontype['CouponType']['send_type'] == 2) {
        }

        if ($coupontype['CouponType']['send_type'] == 5) {
        }
        $this->set('coupontype', $coupontype);
    }

    public function insert_link_users($link_id, $id)
    {
        $this->CouponType->hasOne = array('CouponTypeI18n' => array('className' => 'CouponTypeI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'coupon_type_id',
                        ),
                  );
        $this->CouponType->set_locale($this->locale);
        $coupon_info = $this->CouponType->find('first', array('conditions' => array('CouponType.id' => $id)));
        $coupon_arr = $this->Coupon->find('all');
        $coupon_count = count($coupon_arr);
        $num = 0;
        if ($coupon_count > 0) {
            $num = $coupon_arr[$coupon_count - 1]['Coupon']['sn_code'];
        }

        if (isset($coupon_sn)) {
            $num = $coupon_sn;
        }

        $num = substr($num, 2, 10);
        $num = $num ? floor($num / 10000) : 100000;
        $coupon_sn = $coupon_info['CouponType']['prefix'].$num.str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $coupon = array(
                        'id' => '',
                        'coupon_type_id' => $coupon_info['CouponType']['id'],
                        'sn_code' => $coupon_sn,
                        'user_id' => $link_id,
                        );
        $this->Coupon->save($coupon);
        $coupon_id = $this->Coupon->id;
        $this->send_coupon_email($coupon_id);
        $coupon_user_infos = $this->Coupon->find('list', array('conditions' => array('Coupon.coupon_type_id' => $id), 'fields' => 'Coupon.user_id'));
        $user_infos = $this->User->find('all', array('conditions' => array('User.id' => $coupon_user_infos), 'fields' => 'User.id,User.name'));
        $content = array();
        if (!empty($user_infos)) {
            foreach ($user_infos as $k => $u) {
                $content[$k]['id'] = $u['User']['id'];
                $content[$k]['name'] = $u['User']['name'];
            }
        }
        //2 失败 1成功
        if (!empty($user_infos)) {
            $result['content'] = $content;
            $result['flag'] = 1;
        } else {
            $result['flag'] = 2;
            $result['msg'] = 'REEOR';
        }
        //页面显示
        Configure::write('debug', 0);
        $result['action'] = 'drop_link_users';
        $result['coupon_type_id'] = $id;
        die(json_encode($result));
    }

    public function drop_link_users($coupon_type_id, $user_id)
    {
        //$coupon = $this->Coupon->find('Coupon.id = '.$id.' and Coupon.user_id = '.$user_id);
        //$this->Coupon->del($coupon);
        $this->Coupon->deleteAll(array('Coupon.user_id' => $user_id, 'Coupon.coupon_type_id' => $coupon_type_id));
        Configure::write('debug', 0);
        $result['flag'] = '1';
        $result['msg'] = $user_id;
        $result['coupon_type_id'] = $coupon_type_id;
        die(json_encode($result));
    }

    public function insert_link_products($link_id, $id)
    {
        $cp_data = $this->CouponProduct->find('first', array('conditions' => array('CouponProduct.product_id' => $link_id, 'CouponProduct.coupon_type_id' => $id)));
        if (!empty($cp_data)) {
            $cp_info['id'] = $cp_data['CouponProduct']['id'];
        }
        $cp_info['product_id'] = $link_id;
        $cp_info['coupon_type_id'] = $id;
        $this->CouponProduct->saveAll($cp_info);
        //获取当前类别下商品id的集合	    CouponProduct
        $pIds = array();
        $pIds = $this->CouponProduct->find('list', array('conditions' => array('CouponProduct.coupon_type_id' => $id), 'fields' => 'CouponProduct.product_id'));
        $this->Product->set_locale($this->backend_locale);
        $product_arr = $this->Product->find('all', array('conditions' => array('Product.id' => $pIds), 'fields' => 'Product.id,Product.code,ProductI18n.name'));
        $content = array();
        if (!empty($product_arr)) {
            foreach ($product_arr as $k => $p) {
                $content[$k]['id'] = $p['Product']['id'];
                $content[$k]['name'] = $p['ProductI18n']['name'];
                $content[$k]['code'] = $p['Product']['code'];
            }
        }
        //页面显示
        Configure::write('debug', 0);
        //2 失败 1成功
        if (!empty($product_arr)) {
            $result['content'] = $content;
            $result['flag'] = 1;
        } else {
            $result['flag'] = 2;
            $result['msg'] = 'REEOR';
        }
        $result['action'] = 'drop_link_products';
        $result['coupon_id'] = $id;
        die(json_encode($result));
    }

    public function drop_link_products($id, $product_id)
    {
        //		$product = array(
//						'id' => $product_id,
//						'coupon_type_id' => 0
//						);
//		$this->Product->save($product);
        $cp_info['product_id'] = $product_id;
        $cp_info['coupon_type_id'] = $id;
        $this->CouponProduct->deleteall($cp_info);
        Configure::write('debug', 0);
        $result['flag'] = '1';
        $result['msg'] = $product_id;
        $result['coupon_id'] = $id;
        die(json_encode($result));
    }
    public function send_print()
    {
        $this->CouponType->hasOne = array('CouponTypeI18n' => array('className' => 'CouponTypeI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'coupon_type_id',
                        ),
                  );

        $times = $_POST['num'];
        $this->CouponType->set_locale($this->locale);
        $coupon_info = $this->CouponType->find('first', array('conditions' => array('CouponType.id' => $_POST['coupon_type_id'])));
        $coupon_list = $this->Coupon->find('first', array('conditions' => array('Coupon.coupon_type_id' => $_POST['coupon_type_id']), 'order' => 'Coupon.id desc'));
        if (empty($coupon_info)) {
            $this->redirect('/coupons/');
        }
        $num = $coupon_info['CouponType']['prefix'];
        $num = $num.str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
        if (!empty($coupon_list)) {
            $num_start = (substr($coupon_list['Coupon']['sn_code'], strlen($coupon_list['Coupon']['sn_code']) - 4)) + 1;
        } else {
            $num_start = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        }
        while (true) {
            if (substr($num_start, 0, 1) == 0) {
                $num_start = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            } else {
                break;
            }
        }
        for ($i = $num_start; $i < $num_start + $times; ++$i) {
            $coupon_sn = $num.$i;
            $coupon = array(
                            'id' => '',
                            'coupon_type_id' => $coupon_info['CouponType']['id'],
                            'sn_code' => $coupon_sn,
                            'user_id' => 0,
                            );
            $this->Coupon->save($coupon);
        }
        $this->redirect('/coupons/');
    }

    public function send_coupon()
    {
        $this->CouponType->hasOne = array('CouponTypeI18n' => array('className' => 'CouponTypeI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'coupon_type_id',
                        ),
                  );
        $this->CouponType->set_locale($this->locale);
        $coupon_info = $this->CouponType->find('first', array('conditions' => array('CouponType.id' => $_POST['coupon_type_id'])));
        if (empty($coupon_info)) {
            $this->redirect('/coupons/');
        }
        $num = $coupon_info['CouponType']['prefix'];
        $num = $num.str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
        $num_start = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $coupon_sn = $num.$num_start;
        $coupon = array(
                            'id' => '',
                            'coupon_type_id' => $coupon_info['CouponType']['id'],
                            'sn_code' => $coupon_sn,
                            'max_buy_quantity' => $_POST['max_buy_quantity'],
                            'user_id' => 0,
                            );
        $this->Coupon->save($coupon);
        if (isset($this->configs['open_OperatorLog']) && $this->configs['open_OperatorLog'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.'操作员'.$this->admin['name'].' '.'发放电子优惠券', $this->admin['id']);
        }
        $this->redirect('/coupons/');
    }

    public function send_coupon_email($id)
    {
		$coupon = $this->Coupon->find('first', array('conditions' => array('Coupon.id' => $id)));
		$user = $this->User->find('first', array('conditions' => array('User.id' => $coupon['Coupon']['user_id'])));
		$user_name = $user['User']['name'];//template
		$this->CouponType->set_locale($this->locale);
		$coupon_type = $this->CouponType->findbyid($coupon['Coupon']['coupon_type_id']);
		$money = $coupon_type['CouponType']['money'];//template
		$shop_name = $this->configs['shop_name'];//template
		$shop_url=$this->server_host;//template
		$send_date = date('Y-m-d H:m:s');//template
		//读模板
		$template = 'send_coupon';
		$this->NotifyTemplateType->set_locale($this->backend_locale);
		$totify_template_info=$this->NotifyTemplateType->typeformat($template);
		if(!empty($totify_template_info)){
			foreach($totify_template_info as $template_type=>$totify_template){
				if($template_type=="email"&&$user['User']['email']!=""){
					$subject = $totify_template['NotifyTemplateTypeI18n']['title'];
	                		$subject = str_replace('$shop_name', $shop_name, $subject);
	                		$html_body=addslashes($totify_template['NotifyTemplateTypeI18n']['param01']);
	                		eval("\$html_body = \"$html_body\";");
	                		$text_body = $totify_template['NotifyTemplateTypeI18n']['param02'];
        				eval("\$text_body = \"$text_body\";");
        				$mail_send_queue = array(
						'sender_name' => $shop_name,//发送从姓名
						'receiver_email' =>$user['User']['email'],//接收人姓名;接收人地址
						'cc_email' => ';',
						'bcc_email' => ';',
						'title' => $subject,
						'html_body' => $html_body,
						'text_body' => $text_body,
						'sendas' => 'html',
						'flag' => 0,
						'pri' => 0
			            	);
			            	$this->Notify->send_email($mail_send_queue, $this->configs);
				}else if($template_type=="mobile"&&$user['User']['mobile']!=""){
					$sms_content=$Notify_template['sms']['NotifyTemplateTypeI18n']['param02'];
					eval("\$sms_content = \"$sms_content\";");
					$sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
					$this->Notify->send_sms($user['User']['mobile'],$sms_content,$sms_kanal,$this->configs,false);
				}
			}
		}
            return true;
    }

    public function user_coupon_email($id)
    {
        Configure::write('debug', 1);
        if ($this->send_coupon_email($id)) {
            //修改优惠卷 邮件状态
            $this->Coupon->updateAll(
                          array('Coupon.emailed' => '1'),
                          array('Coupon.id' => $id)
                       );
            $result['flag'] = 1;
            $result['msg'] = '发送成功';
        } else {
            $result['flag'] = 0;
            $result['msg'] = '发送失败';
        }
        $this->set('result', $result);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function send_by_user_rank()
    {
        $this->CouponType->hasOne = array('CouponTypeI18n' => array('className' => 'CouponTypeI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'coupon_type_id',
                        ),
                  );

        $users = $this->User->findall('User.rank ='.$_POST['user_rank']);
        $this->CouponType->set_locale($this->locale);
        $coupon_info = $this->CouponType->find('first', array('conditions' => array('CouponType.id' => $_POST['coupon_type_id'])));
        $coupon_list = $this->Coupon->find('first', array('conditions' => array('Coupon.coupon_type_id' => $_POST['coupon_type_id']), 'order' => 'Coupon.id desc'));
        if (empty($coupon_info)) {
            $this->redirect('/coupons/');
        }
        $num = $coupon_info['CouponType']['prefix'];
        $num = $num.str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
        if (!empty($coupon_list)) {
            $num_start = (substr($coupon_list['Coupon']['sn_code'], strlen($coupon_list['Coupon']['sn_code']) - 4)) + 1;
        } else {
            $num_start = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        }
        if (is_array($users) && sizeof($users) > 0) {
            $i = num_start;
            foreach ($users as $k => $v) {
                $coupon_sn = $num.$i;
                $coupon = array(
                                    'id' => '',
                                    'coupon_type_id' => $coupon_info['CouponType']['id'],
                                    'sn_code' => $coupon_sn,
                                    'user_id' => $v['User']['id'],
                                    );
                $this->Coupon->save($coupon);
                $coupon_id = $this->Coupon->id;
                $this->send_coupon_email($coupon_id);
                ++$i;
            }
        }
        $this->redirect('/coupons/');
    }

    public function send_coupon_to_user($user_id = 0)
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            //仅限ajax访问
            Configure::write('debug', 1);
            $this->layout = 'ajax';
            $this->CouponType->set_locale($this->backend_locale);
            $coupon_list = $this->CouponType->find('all', array('conditions' => array('CouponType.send_type' => 0, 'CouponType.send_start_date <=' => date('Y-m-d H:i:s'), 'CouponType.send_end_date >=' => date('Y-m-d H:i:s'))));
            $this->set('coupon_list', $coupon_list);
            $this->set('user_id', $user_id);
        } else {
            $this->redirect('/coupons/');
        }
    }
}
