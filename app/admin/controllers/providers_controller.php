<?php

/*****************************************************************************
 * Seevia 供应商
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
class ProvidersController extends AppController
{
    public $name = 'Providers';

    public $components = array('Pagination','RequestHandler','Email'); // Added
    public $helpers = array('Pagination','Ckeditor'); // Added
    public $uses = array('Provider','Product','ProductI18n','ProductType','ProviderProduct','SeoKeyword','OperatorLog');

    public function index($page = 1)
    {
        $this->operator_privilege('provider_list');
        /*判断权限*/
        //$this->operator_privilege('supplier_view');
        /*end*/
        $this->pageTitle = $this->ld['supply_001'].' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => $this->ld['supply_002'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['supply_001'],'url' => '/providers/');

        $this->set('title_for_layout', $this->ld['supply_001'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $condition = array();
        if (isset($_REQUEST['keywords'])) {
            $keywords = trim($_REQUEST['keywords']);
            $condition['and']['or']['Provider.meta_keywords LIKE'] = '%'.$keywords.'%';
            $condition['and']['or']['Provider.name LIKE'] = '%'.$keywords.'%';
            $condition['and']['or']['Provider.contact_email LIKE'] = '%'.$keywords.'%';
            $condition['and']['or']['Provider.contact_tele LIKE'] = '%'.$keywords.'%';
            $condition['and']['or']['Provider.contact_name LIKE'] = '%'.$keywords.'%';
            $this->set('keywords', $keywords);
        }
        if (isset($_REQUEST['status']) && $_REQUEST['status'] != '-1') {
            $keywords = trim($_REQUEST['keywords']);
            $condition['and']['Provider.status'] = $_REQUEST['status'];
            $this->set('status', $_REQUEST['status']);
        }
        if (isset($_REQUEST['start_time']) && $_REQUEST['start_time'] != '') {
            $condition['and']['Provider.created >'] = $_REQUEST['start_time'];
            $this->set('start_time', $_REQUEST['start_time']);
        }
        if (isset($_REQUEST['end_time']) && $_REQUEST['end_time'] != '') {
            $condition['and']['Provider.created <'] = $_REQUEST['end_time'];
            $this->set('end_time', $_REQUEST['end_time']);
        }
        $total = $this->Provider->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'providers','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Provider');
        $this->Pagination->init($condition, $parameters, $options);
        $provider_list = $this->Provider->find('all', array('conditions' => $condition, 'order' => 'Provider.orderby asc'));
        $this->set('provider_list', $provider_list);
        if (!empty($this->params['url'])) {
            $url = $this->params['url']['url'].'?';
            //$url="";
            foreach ($this->params['url'] as $k => $v) {
                if ($k == 'url') {
                } else {
                    $url .= $k.'='.$v.'&';
                }
            }
        }
        $_SESSION['index_url'] = $url;
    }

    public function view($id = 0)
    {
        if (empty($id)) {
            $this->operator_privilege('provider_add');
        } else {
            $this->operator_privilege('provider_edit');
        }
        /*判断权限*/
        //$this->operator_privilege('supplier_operation');
        /*end*/
        $this->pageTitle = $this->ld['supply_003'].' - '.$this->ld['supply_001'].' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => $this->ld['supply_003'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['supply_001'],'url' => '/providers/');
        $this->navigations[] = array('name' => $this->ld['supply_003'],'url' => '');

        $this->set('title_for_layout', $this->ld['supply_003'].' - '.$this->configs['shop_name']);
        if ($this->RequestHandler->isPost()) {
            if (!empty($this->data['Provider']['name'])) {
                $this->data['Provider']['orderby'] = !empty($this->data['Provider']['orderby']) ? $this->data['Provider']['orderby'] : 50;
                $this->Provider->saveall($this->data); //保存
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加/编辑供应商：id '.$id.$this->data['Provider']['name'], $this->admin['id']);
            }
            //	$this->redirect('/providers');
               $this->redirect('/'.$_SESSION['index_url']);
            }
        }
        $this->data = $this->Provider->find('first', array('conditions' => array('Provider.id' => $id)));
    }
    public function toggle_on_status()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Provider->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    public function trash($id, $pid)
    {
        $this->Product->updateAll(
                          array('Product.status' => 2),
                          array('Product.id' => $id)
                       );
        $pn = $this->ProductI18n->find('list', array('fields' => array('ProductI18n.product_id', 'ProductI18n.name'), 'conditions' => array('ProductI18n.product_id' => $id, 'ProductI18n.locale' => $this->locale)));
        $prn = $this->Provider->find('list', array('fields' => array('Provider.id', 'Provider.name'), 'conditions' => array('Provider.id' => $pid)));
         //操作员日志
         if ($this->configs['operactions-log'] == 1) {
             $this->OperatorLog->log(date('H:i:s').' '.'操作员'.$this->admin['name'].' '.'回收供应商 '.$prn[$pid].' 商品:'.$pn[$id], $this->admin['id']);
         }
        $this->flash('该商品已经进入回收站', '/providers/', 10);
    }

    public function remove($id)
    {
        /*判断权限*/
        $this->operator_privilege('provider_remove');
        //$this->operator_privilege('supplier_operation');
        /*end*/
        $pn = $this->Provider->find('list', array('fields' => array('Provider.id', 'Provider.name'), 'conditions' => array('Provider.id' => $id)));
        $this->Provider->deleteAll(array('Provider.id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.'操作员'.$this->admin['name'].' '.'删除供应商:'.$pn[$id], $this->admin['id']);
        }
        $result['flag'] = 1;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    public function batch_operations()
    {
        $this->operator_privilege('provider_batch_remove');
        $user_checkboxes = $_REQUEST['checkboxes'];
        $this->Provider->deleteAll(array('id' => $user_checkboxes));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }
    public function product()
    {
        /*判断权限*/
        $this->operator_privilege('providers_product_view');
        /*end*/
        $this->navigations[] = array('name' => '进销存','url' => '');
        $this->navigations[] = array('name' => '供应商商品','url' => '/providers/product');

        $condition = '';
        if (isset($this->params['url']['providerkeywords']) && $this->params['url']['providerkeywords'] != '') {
            $providerkeywords = $this->params['url']['providerkeywords'];
            $condition['and']['or']['Provider.name like'] = "%$providerkeywords%";
            $condition['and']['or']['Provider.description like'] = "%$providerkeywords%";
            $condition['and']['or']['Provider.meta_keywords like'] = "%$providerkeywords%";
            $condition['and']['or']['Provider.meta_description like'] = "%$providerkeywords%";
            $condition['and']['or']['Provider.contact_name like'] = "%$providerkeywords%";
            $condition['and']['or']['Provider.contact_email like'] = "%$providerkeywords%";
            $condition['and']['or']['Provider.contact_address like'] = "%$providerkeywords%";
            $this->set('providerkeywords', $this->params['url']['providerkeywords']);
        }
        if (isset($this->params['url']['productkeywords']) && $this->params['url']['productkeywords'] != '') {
            $productkeywords = $this->params['url']['productkeywords'];
            $condition['and']['or']['Product.code like'] = "%$productkeywords%";
            $condition['and']['or']['ProductI18n.name like'] = "%$productkeywords%";
            $condition['and']['or']['ProductI18n.description like'] = "%$productkeywords%";
            $condition['and']['or']['Product.id like'] = "%$productkeywords%";
            $this->set('providerkeywords', $this->params['url']['providerkeywords']);
        }
        $total = $this->ProviderProduct->findCount($condition, 0);
        $sortClass = 'ProviderProduct';
        $page = 1;
        $rownum = isset($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters = array($rownum,$page);
        $options = array();
        $page = $this->Pagination->init($condition, $parameters, $options, $total, $rownum, $sortClass);
        $ProviderProduct_list = $this->ProviderProduct->find('all', array('conditions' => $condition, 'order' => 'Provider.orderby', 'page' => $page, 'limit' => $rownum));
        $ProviderProduct_list_new = array();
        $product_id = array();
        foreach ($ProviderProduct_list as $k => $v) {
            $product_id[] = $v['Product']['id'];
            $ProviderProduct_list_new[$v['Product']['id']] = $v;
        }
        $product_i18n = $this->ProductI18n->find('all', array('conditions' => array('product_id' => $product_id, 'locale' => $this->locale)));
        foreach ($product_i18n as $k => $v) {
            $ProviderProduct_list_new[$v['ProductI18n']['product_id']]['ProductI18n'] = $v['ProductI18n'];
        }

        $this->set('ProviderProduct_list', $ProviderProduct_list_new);
        $this->pageTitle = '供应商商品 - 供应商商品'.' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name'];
    }
    public function product_add()
    {
        $this->pageTitle = '供应商商品 - 供应商商品'.' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => '进销存','url' => '');
        $this->navigations[] = array('name' => '供应商商品','url' => '/providers/product');
        $this->navigations[] = array('name' => '新增供应商商品','url' => '');

        if ($this->RequestHandler->isPost()) {
            $this->ProviderProduct->hasOne = array();
            $this->ProviderProduct->saveAll($this->data['ProviderProduct']); //保存
                //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加供应商商品', $this->admin['id']);
            }
            $this->flash('供应商商品 添加成功。点击这里继续编辑该供应商商品。', '/providers/product_edit/'.$this->ProviderProduct->getLastInsertId(), 10);
        }
        $this->Product->hasMany = array();
        $this->Product->hasOne = array('ProductI18n' => array(
                                                  'className' => 'ProductI18n',
                                                  'order' => '',
                                                  'dependent' => true,
                                                  'foreignKey' => 'product_id',
                                                 ),
                        );
        $fields[] = 'Product.id';
        $fields[] = 'ProductI18n.name';
        $products_list = $this->Product->find('all', array('fields' => $fields));
        $this->set('products_list', $products_list);
        $this->Provider->hasMany = array();
        $Provider_list = $this->Provider->find('all');
        $this->set('Provider_list', $Provider_list);
    }
    public function product_edit($id)
    {
        $this->pageTitle = '供应商商品 - 供应商商品'.' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => '进销存','url' => '');
        $this->navigations[] = array('name' => '供应商商品','url' => '/providers/product');
        $this->navigations[] = array('name' => '编辑供应商商品','url' => '');

        if ($this->RequestHandler->isPost()) {
            $this->data['ProviderProduct']['id'] = $id;
            $this->ProviderProduct->hasOne = array();
            $this->ProviderProduct->saveAll($this->data['ProviderProduct']); //保存
    //        //操作员日志
    //		if($this->configs['operactions-log']== 1){
    //			$this->OperatorLog->log(date("H:i:s").' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑商品',$this->admin['id']);
    //		}
    //		$this->flash("供应商商品  编辑成功。点击这里继续编辑该供应商商品。",'/providers/product_edit/'.$id,10);
        }
        $this->Product->hasMany = array();
        $this->Product->hasOne = array('ProductI18n' => array(
                                                  'className' => 'ProductI18n',
                                                  'order' => '',
                                                  'dependent' => true,
                                                  'foreignKey' => 'product_id',
                                                 ),
                        );
        $fields[] = 'Product.id';
        $fields[] = 'ProductI18n.name';
        $products_list = $this->Product->find('all', array('fields' => $fields));
        $this->set('products_list', $products_list);
        $this->Provider->hasMany = array();
        $Provider_list = $this->Provider->find('all');
        $this->set('Provider_list', $Provider_list);
        $ProviderProduct_info = $this->ProviderProduct->findById($id);
        $this->set('ProviderProduct_info', $ProviderProduct_info);
    }
    public function product_remove($id)
    {
        $this->ProviderProduct->del($id);
        die();
    }
}
