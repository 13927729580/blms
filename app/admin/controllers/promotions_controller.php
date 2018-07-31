<?php

/*****************************************************************************
 * Seevia 促销管理
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
class PromotionsController extends AppController
{
    public $name = 'Promotions';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Tinymce','Html','Ckeditor');
    public $uses = array('Promotion','PromotionI18n','PromotionProduct','Product','PromotionActivityProduct','OperatorLog');
    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('promotions_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/oms/','sub' => '/promotions/');
        /*end*/
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['promotions'],'url' => '');
        $this->Promotion->set_locale($this->locale);
        $condition = '1=1';
        $start_time = '';
        $end_time = '';
        $promotion_title = '';
        if (isset($this->params['url']['start_time']) && $this->params['url']['start_time'] != '') {
            $condition .= " and Promotion.start_time >= '".$this->params['url']['start_time']." 00:00:00'";
            $start_time = $this->params['url']['start_time'].'';
        }
        if (isset($this->params['url']['end_time']) && $this->params['url']['end_time'] != '') {
            $condition .= " and Promotion.end_time <= '".$this->params['url']['end_time']." 23:59:59'";
            $end_time = $this->params['url']['end_time'];
        }
        if (isset($this->params['url']['promotion_title']) && $this->params['url']['promotion_title'] != '') {
            //pr($this->params['url']['promotion_title']);
            $condition2['PromotionI18n.title like'] = '%'.$this->params['url']['promotion_title'].'%';
            $promotion_title = $this->params['url']['promotion_title'];

            $promotionid = $this->PromotionI18n->find('list', array('conditions' => $condition2, 'fields' => array('PromotionI18n.promotion_id')));

            $promotionid[] = 0;

            $condition .= ' and Promotion.id in ('.implode(',', $promotionid).')';
        }
        $total = $this->Promotion->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'promotions','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Promotion');
        $this->Pagination->init($condition, $parameters, $options);
        $data = $this->Promotion->find('all', array('conditions' => $condition, 'limit' => $rownum, 'page' => $page));

        foreach ($data as $k => $v) {
            switch ($v['Promotion']['type']) {
                case 0:
                $v['Promotion']['typename'] = $this->ld['relief'];
                break;
                case 1:
                $v['Promotion']['typename'] = $this->ld['discount'];
                break;
                case 2:
                $v['Promotion']['typename'] = $this->ld['specials'];
                break;
                default:
                $v['Promotion']['typename'] = $this->ld['other'];
                break;
            }
            $data[$k] = $v;
        }
        $this->set('promotions', $data);
        $this->set('start_time', $start_time);
        $this->set('end_time', $end_time);
        $this->set('promotion_title', $promotion_title);
        $this->set('title_for_layout', $this->ld['promotions'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }
    public function view($id = 0)
    {
        /*判断权限*/
        if (empty($id)) {
            $this->operator_privilege('promotions_add');
        } else {
            $this->operator_privilege('promotions_edit');
        }
        $this->menu_path = array('root' => '/oms/','sub' => '/promotions/');
        /*end*/
        //$this->pageTitle="促销活动 - 促销活动"." - ".$this->configs['shop_name'];
        $this->set('title_for_layout', $this->ld['edit_promotional_activities'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['promotion_list'],'url' => '/promotions/');
        //$this->navigations[]=array('name'=>'编辑促销活动','url' => '');

        if ($this->RequestHandler->isPost()) {
            $this->data['Promotion']['min_amount'] = !empty($this->data['Promotion']['min_amount']) ? $this->data['Promotion']['min_amount'] : 0;
            $this->data['Promotion']['max_amount'] = !empty($this->data['Promotion']['max_amount']) ? $this->data['Promotion']['max_amount'] : 0;
            $this->data['Promotion']['type_ext'] = !empty($this->data['Promotion']['type_ext']) ? $this->data['Promotion']['type_ext'] : 0;
            $this->data['Promotion']['end_time'] = date('Y-m-d', strtotime($this->data['Promotion']['end_time'])).' 23:59:59';
            if ($id == 0) {
                $this->Promotion->save($this->data); //保存
                $id = $this->Promotion->getLastInsertId();
            } else {
                $this->Promotion->save($this->data); //保存
            }

            foreach ($this->data['PromotionI18n'] as $v) {
                $promotionI18n_info = array('id' => isset($v['id']) ? $v['id'] : '','locale' => $v['locale'],'promotion_id' => $id,'title' => isset($v['title']) ? $v['title'] : '','short_desc' => $v['short_desc'],'meta_description' => isset($v['meta_description']) ? $v['meta_description'] : '');
                $this->PromotionI18n->saveall(array('PromotionI18n' => $promotionI18n_info)); //更新多语言
            }
            $this->PromotionProduct->deleteAll(array('promotion_id' => $id));
            if (isset($_REQUEST['specialpreferences']) && isset($_REQUEST['prices'])) {
                $specialpreferences = $_REQUEST['specialpreferences'];
                $prices = $_REQUEST['prices'];

                for ($i = 0;$i <= count($specialpreferences) - 1;++$i) {
                    $data['PromotionProduct']['promotion_id'] = $id;
                    $data['PromotionProduct']['product_id'] = $specialpreferences[$i];
                    $data['PromotionProduct']['price'] = $prices[$i];
                    $this->PromotionProduct->saveAll($data);
                }
            }
            $this->PromotionActivityProduct->deleteall("promotion_id = '".$id."'", false);
            if (isset($_REQUEST['specialpreferences2']) && isset($_REQUEST['prices2'])) {
                $specialpreferences2 = $_REQUEST['specialpreferences2'];
                $prices2 = $_REQUEST['prices2'];

                for ($i = 0;$i <= count($specialpreferences2) - 1;++$i) {
                    $data['PromotionActivityProduct']['promotion_id'] = $id;
                    $data['PromotionActivityProduct']['product_id'] = $specialpreferences2[$i];
                    $data['PromotionActivityProduct']['price'] = $prices2[$i];
                    $this->PromotionActivityProduct->saveAll($data);
                }
            }
            foreach ($this->data['PromotionI18n']as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['title'];
                }
            }
            $id = $this->Promotion->id;
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_promotional_activities'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->Product->hasOne = array();
        $this->Product->hasMany = array('ProductI18n' => array('className' => 'ProductI18n','conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'product_id',
        ));
        $PromotionProductlist = $this->PromotionProduct->find('all', array('conditions' => array('promotion_id' => $id)));
        $PromotionActivityProductlist = $this->PromotionActivityProduct->find('all', array('conditions' => array('promotion_id' => $id)));
        //pr($PromotionActivityProductlist);
        $condition2 = '';
        $PromotionProduct = array();
        foreach ($PromotionProductlist as $v) {
            $PromotionProduct[$v['PromotionProduct']['product_id']] = $v;
            $condition2['or'][]['id'] = $v['PromotionProduct']['product_id'];
        }
        if ($condition2 != '') {
            $products = $this->Product->find('all', array('conditions' => $condition2));
        } else {
            $products = array();
        }
        //活动商品
        $condition22 = '';
        $PromotionProduct2 = array();
        foreach ($PromotionActivityProductlist as $v) {
            $PromotionProduct2[$v['PromotionActivityProduct']['product_id']] = $v;
            $condition22['or'][]['id'] = $v['PromotionActivityProduct']['product_id'];
        }

        if ($condition22 != '') {
            $products2 = $this->Product->find('all', array('conditions' => $condition22));
        } else {
            $products2 = array();
        }
        //pr($condition22);
        foreach ($products as $kk => $vv) {
            $PromotionProduct[$vv['Product']['id']]['PromotionProduct']['name'] = '';
            $PromotionProduct[$vv['Product']['id']]['PromotionProduct']['product_code'] = $vv['Product']['code'];
            foreach ($vv['ProductI18n']as $kkk => $vvv) {
                if ($vvv['locale'] == $this->locale) {
                    $PromotionProduct[$vv['Product']['id']]['PromotionProduct']['name'] = $vvv['name'];
                }
            }
        }
            //pr($products2);
        if (!empty($products2)) {
            foreach ($products2 as $kk => $vv) {
                $PromotionProduct2[$vv['Product']['id']]['PromotionActivityProduct']['name'] = '';
                $PromotionProduct2[$vv['Product']['id']]['PromotionActivityProduct']['product_code'] = $vv['Product']['code'];
                foreach ($vv['ProductI18n']as $kkk => $vvv) {
                    if ($vvv['locale'] == $this->locale) {
                        $PromotionProduct2[$vv['Product']['id']]['PromotionActivityProduct']['name'] = $vvv['name'];
                    }
                }
            }
        } else {
            $PromotionProduct2 = array();
        }

        $promotion = $this->Promotion->localeformat($id);
        $this->data = $promotion;
        //$this->set("promotion",$promotion);
        //pr($promotion);
        $this->set('PromotionProduct', $PromotionProduct);
        $this->set('PromotionProduct2', $PromotionProduct2);
        //pr($PromotionProduct2);
        //leo20090722导航显示
        if (isset($promotion['PromotionI18n'][$this->backend_locale]['title'])) {
            $this->navigations[] = array('name' => $promotion['PromotionI18n'][$this->backend_locale]['title'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_promotion_activity'],'url' => '');
        }
    }
    public function edit($id)
    {

        /*判断权限*/
        $this->operator_privilege('promotions_edit');
        /*end*/
        //$this->pageTitle="促销活动 - 促销活动"." - ".$this->configs['shop_name'];
        $this->set('title_for_layout', $this->ld['promotions'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['promotion_list'],'url' => '/promotions/');
        $this->navigations[] = array('name' => $this->ld['edit_promotional_activities'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            $this->data['Promotion']['min_amount'] = !empty($this->data['Promotion']['min_amount']) ? $this->data['Promotion']['min_amount'] : 0;
            $this->data['Promotion']['max_amount'] = !empty($this->data['Promotion']['max_amount']) ? $this->data['Promotion']['max_amount'] : 0;
            $this->data['Promotion']['type_ext'] = !empty($this->data['Promotion']['type_ext']) ? $this->data['Promotion']['type_ext'] : 0;
            $this->data['Promotion']['end_time'] = date('Y-m-d', strtotime($this->data['Promotion']['end_time'])).' 23:59:59';

            foreach ($this->data['PromotionI18n']as $v) {
                $promotionI18n_info = array('id' => isset($v['id']) ? $v['id'] : '','locale' => $v['locale'],'promotion_id' => isset($v['promotion_id']) ? $v['promotion_id'] : $id,'title' => isset($v['title']) ? $v['title'] : '','meta_description' => $v['meta_description']);
                $this->PromotionI18n->saveall(array('PromotionI18n' => $promotionI18n_info)); //更新多语言
            }
            $this->Promotion->save($this->data); //保存
            $this->PromotionProduct->deleteall("promotion_id = '".$id."'", false);
            if (isset($_REQUEST['specialpreferences']) && isset($_REQUEST['prices'])) {
                $specialpreferences = $_REQUEST['specialpreferences'];
                $prices = $_REQUEST['prices'];

                for ($i = 0;$i <= count($specialpreferences) - 1;++$i) {
                    $data['PromotionProduct']['promotion_id'] = $id;
                    $data['PromotionProduct']['product_id'] = $specialpreferences[$i];
                    $data['PromotionProduct']['price'] = $prices[$i];
                    $this->PromotionProduct->saveAll($data);
                }
            }
            $this->PromotionActivityProduct->deleteall("promotion_id = '".$id."'", false);
            if (isset($_REQUEST['specialpreferences2']) && isset($_REQUEST['prices2'])) {
                $specialpreferences2 = $_REQUEST['specialpreferences2'];
                $prices2 = $_REQUEST['prices2'];

                for ($i = 0;$i <= count($specialpreferences2) - 1;++$i) {
                    $data['PromotionActivityProduct']['promotion_id'] = $id;
                    $data['PromotionActivityProduct']['product_id'] = $specialpreferences2[$i];
                    $data['PromotionActivityProduct']['price'] = $prices2[$i];
                    $this->PromotionActivityProduct->saveAll($data);
                }
            }
            foreach ($this->data['PromotionI18n']as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['title'];
                }
            }
            $id = $this->Promotion->id;
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['opreator'].$this->admin['name'].' '.$this->ld['edit_promotional_activities'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $this->flash($this->ld['promotions'].'  '.$userinformation_name.$this->ld['edited_promotion_activity_success'], '/promotions/edit/'.$id, 10);
        }
        $this->Product->hasOne = array();
        $this->Product->hasMany = array('ProductI18n' => array('className' => 'ProductI18n','conditions' => '',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'product_id',
        ));
        $PromotionProductlist = $this->PromotionProduct->find('all', array('conditions' => array('promotion_id' => $id)));
        $PromotionActivityProductlist = $this->PromotionActivityProduct->find('all', array('promotion_id' => $id));

        $condition2 = '';
        $PromotionProduct = array();
        foreach ($PromotionProductlist as $v) {
            $PromotionProduct[$v['PromotionProduct']['product_id']] = $v;
            $condition2['or'][]['id'] = $v['PromotionProduct']['product_id'];
        }
        if ($condition2 != '') {
            $products = $this->Product->find('all', array('conditions' => $condition2));
        } else {
            $products = array();
        }
        //活动商品
        $condition22 = '';
        $PromotionProduct2 = array();
        foreach ($PromotionActivityProductlist as $v) {
            $PromotionProduct2[$v['PromotionActivityProduct']['product_id']] = $v;
            $condition22['or'][]['id'] = $v['PromotionActivityProduct']['product_id'];
        }
        if ($condition22 != '') {
            $products2 = $this->Product->find('all', array('conditions' => $condition22));
        } else {
            $products2 = array();
        }

        foreach ($products as $kk => $vv) {
            $PromotionProduct[$vv['Product']['id']]['PromotionProduct']['name'] = '';
            $PromotionProduct[$vv['Product']['id']]['PromotionProduct']['product_code'] = $vv['Product']['code'];
            foreach ($vv['ProductI18n']as $kkk => $vvv) {
                if ($vvv['locale'] == $this->locale) {
                    $PromotionProduct[$vv['Product']['id']]['PromotionProduct']['name'] = $vvv['name'];
                }
            }
        }
            //pr($products2);
        foreach ($products2 as $kk => $vv) {
            $PromotionProduct2[$vv['Product']['id']]['PromotionActivityProduct']['name'] = '';
            $PromotionProduct2[$vv['Product']['id']]['PromotionActivityProduct']['product_code'] = $vv['Product']['code'];
            foreach ($vv['ProductI18n']as $kkk => $vvv) {
                if ($vvv['locale'] == $this->locale) {
                    $PromotionProduct2[$vv['Product']['id']]['PromotionActivityProduct']['name'] = $vvv['name'];
                }
            }
        }
        $promotion = $this->Promotion->localeformat($id);
        $this->set('promotion', $promotion);
        //pr($promotion);
        $this->set('PromotionProduct', $PromotionProduct);
        $this->set('PromotionProduct2', $PromotionProduct2);
        //pr($promotion);
        //leo20090722导航显示

        $this->navigations[] = array('name' => $promotion['PromotionI18n'][$this->locale]['title'],'url' => '');
    }
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_failure'];
        /*判断权限*/
        $this->operator_privilege('promotions_edit');
        /*end*/
        $pn = $this->PromotionI18n->find('list', array('fields' => array('PromotionI18n.promotion_id', 'PromotionI18n.title'), 'conditions' => array('PromotionI18n.promotion_id' => $id, 'PromotionI18n.locale' => $this->locale)));
        //pr($pn);
        $this->PromotionProduct->deleteall(array('promotion_id' => $id));
        $this->PromotionActivityProduct->deleteall(array('promotion_id' => $id));
        $this->Promotion->delete($id);
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_promotion'].':id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    public function add()
    {
        $this->operator_privilege('promotions_edit');
        //$this->pageTitle="添加促销活动 - 促销活动"." - ".$this->configs['shop_name'];
        $this->set('title_for_layout', $this->ld['promotions'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_orders'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['promotions'],'url' => '/promotions/');
        $this->navigations[] = array('name' => $this->ld['add_promotion_activity'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            $this->data['Promotion']['min_amount'] = !empty($this->data['Promotion']['min_amount']) ? $this->data['Promotion']['min_amount'] : 0;
            $this->data['Promotion']['max_amount'] = !empty($this->data['Promotion']['max_amount']) ? $this->data['Promotion']['max_amount'] : 0;
            $this->data['Promotion']['type_ext'] = !empty($this->data['Promotion']['type_ext']) ? $this->data['Promotion']['type_ext'] : 0;
            $this->data['Promotion']['end_time'] = date('Y-m-d', strtotime($this->data['Promotion']['end_time'])).' 23:59:59';
            $this->Promotion->save($this->data); //保存
            $id = $this->Promotion->id;
            //新增多语言
            if (is_array($this->data['PromotionI18n'])) {
                foreach ($this->data['PromotionI18n']as $k => $v) {
                    $v['promotion_id'] = $id;
                    $this->PromotionI18n->id = '';
                    $this->PromotionI18n->save($v);
                }
            }
            foreach ($this->data['PromotionI18n']as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['title'];
                }
            }
            $id = $this->Promotion->id;
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['opreator'].$this->admin['name'].' '.$this->ld['add_promotion_activity'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $this->flash($this->ld['promotions'].'  '.$userinformation_name.$this->ld['add_successful_promotion_activity'], '/promotions/edit/'.$id, 10);
            if (isset($_REQUEST['specialpreferences']) && isset($_REQUEST['prices'])) {
                $specialpreferences = $_REQUEST['specialpreferences'];
                $prices = $_REQUEST['prices'];
                for ($i = 0;$i <= count($specialpreferences) - 1;++$i) {
                    //$data["PromotionProduct"]["promotion_id"] = $datas[$total-1]['Promotion']['id'];
                    $data['PromotionProduct']['promotion_id'] = $id;
                    $data['PromotionProduct']['product_id'] = $specialpreferences[$i];
                    $data['PromotionProduct']['price'] = $prices[$i];
                    $this->PromotionProduct->saveAll($data);
                }
            }
            if (isset($_REQUEST['specialpreferences2']) && isset($_REQUEST['prices2'])) {
                $specialpreferences2 = $_REQUEST['specialpreferences2'];
                $prices2 = $_REQUEST['prices2'];
                for ($i = 0;$i <= count($specialpreferences2) - 1;++$i) {
                    $data['PromotionActivityProduct']['promotion_id'] = $id;
                    $data['PromotionActivityProduct']['product_id'] = $specialpreferences2[$i];
                    $data['PromotionActivityProduct']['price'] = $prices2[$i];
                    $this->PromotionActivityProduct->saveAll($data);
                }
            }
        }
    }

    /**
     * 删除.
     */
    public function batch_operations()
    {
        $brand_id = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        if ($brand_id != 0) {
            $condition['Promotion.id'] = $brand_id;
            $this->Promotion->deleteAll($condition);
            $this->PromotionI18n->deleteAll(array('PromotionI18n.id' => $brand_id));

            $result['flag'] = 1;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die();
    }
}
