<?php

/*****************************************************************************
 * Seevia 品牌管理
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
/**
 *这是一个名为 BrandsController 的控制器
 *后台品牌管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class BrandsController extends AppController
{
    public $name = 'Brands';
    public $components = array('Pagination','RequestHandler','Phpexcel','Phpcsv');
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    public $uses = array('Profile','ProfileFiled','Brand','BrandI18n','OperatorLog','CategoryType','Product');

    /**
     *显示品牌列表.
     */
    public function index($page = 1)
    {
        $this->operator_privilege('brands_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/product/','sub' => '/brands/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
//        $this->navigations[]=array('name'=>$this->ld['manage_products'],'url' => '/products/');
        $this->navigations[] = array('name' => $this->ld['manager_brands'],'url' => '');

        $condition = '';
        $brand_keywords = '';     //关键字
        //关键字
        if (isset($this->params['url']['brand_keywords']) && $this->params['url']['brand_keywords'] != '') {
            $brand_keywords = $this->params['url']['brand_keywords'];
            $condition['and']['or']['BrandI18n.name like'] = '%'.$brand_keywords.'%';
            $condition['and']['or']['Brand.code like'] = '%'.$brand_keywords.'%';
            $condition['and']['or']['BrandI18n.description like'] = '%'.$brand_keywords.'%';
            $condition['and']['or']['Brand.id like'] = '%'.$brand_keywords.'%';
        }

        $this->Brand->set_locale($this->backend_locale);
        $total = $this->Brand->find('count', array('conditions' => $condition));//统计全部品牌总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'Brand';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'brands','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Brand');
        $this->Pagination->init($condition, $parameters, $options);

        $fields[] = 'Brand.id';
        $fields[] = 'Brand.code';
        $fields[] = 'Brand.url';
        $fields[] = 'Brand.orderby';
        $fields[] = 'Brand.status';
        $fields[] = 'BrandI18n.name';
        $fields[] = 'Brand.recommand_flag';
        $brand_list = $this->Brand->find('all', array('conditions' => $condition, 'order' => 'Brand.orderby asc,Brand.created desc', 'fields' => $fields, 'limit' => $rownum, 'page' => $page));

        if (!empty($brand_list) && sizeof($brand_list) > 0) {
            foreach ($brand_list as $k => $v) {
                $brind_ids[] = $v['Brand']['id'];
            }
            $this->Product->hasOne = array();
            $product_list = $this->Product->find('all', array('conditions' => array('Product.brand_id' => $brind_ids, 'Product.status' => '1'), 'fields' => array('count(Product.id) as countnum', 'Product.brand_id'), 'group' => 'Product.brand_id'));
            $productbrand_list = array();
            foreach ($product_list as $k => $v) {
                $productbrand_list[$v['Product']['brand_id']] = isset($v[0]['countnum']) ? $v[0]['countnum'] : 0;
            }
            $this->set('productbrand_list', $productbrand_list);
        }

        if (!empty($this->params['url'])) {
            $url = $this->params['url']['url'].'?';
            //$url="";
            foreach ($this->params['url'] as $k => $v) {
                if ($k == 'url') {
                } else {
                    $url .= $k.'='.$v.'&';
                }
            }
            //pr($url);
        }
        $_SESSION['index_url'] = $url;
        $this->set('brand_list', $brand_list);//品牌列表
        $this->set('brand_keywords', $brand_keywords);//关键字选中
        $this->set('title_for_layout', $this->ld['manager_brands'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $this->set('this_page', $page);
         $this->Profile->hasOne = array();
           $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'brand_export', 'Profile.status' => 1)));
		$this->set('profile_id',$profile_id);
    }

    /**
     *品牌 新增/编辑.
     *
     *@param int $id 输入品牌ID
     */
    public function view($id = 0)
    {
        if (empty($id)) {
            $this->operator_privilege('brands_add');
        } else {
            $this->operator_privilege('brands_edit');
        }
        $this->menu_path = array('root' => '/product/','sub' => '/brands/');
        $this->set('title_for_layout', $this->ld['add_edit_brand'].'- '.$this->ld['manager_brands'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
//		$this->navigations[]=array('name'=>$this->ld['manage_products'],'url' => '/products/');
        $this->navigations[] = array('name' => $this->ld['manager_brands'],'url' => '/brands/');
        $this->CategoryType->set_locale($this->backend_locale);
        $category_type_tree = $this->CategoryType->tree();// 类目树
        $this->set('category_type_tree', $category_type_tree);
        if ($this->RequestHandler->isPost()) {
            $code = $this->data['Brand']['code'];
            $rcode = '';
            $result = '';
            $name_code = $this->Brand->find('all', array('fields' => 'Brand.code'));
            if (isset($name_code) && !empty($name_code)) {
                foreach ($name_code as $vv) {
                    $rcode[] = $vv['Brand']['code'];
                }
            } else {
                $result['code'] = '1';
            }
            if (empty($this->data['Brand']['id'])) {
                if (isset($code) && $code != '') {
                    if (in_array($code, $rcode)) {
                        $result['code'] = '0';
                    } else {
                        $result['code'] = '1';
                    }
                } else {
                    $msg = $this->ld['brand_code_empty'];
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	history.go(-1);</script>';
                    die();
                }
            } else {
                $shelf_count = $this->Brand->find('first', array('conditions' => array('Brand.id' => $this->data['Brand']['id'])));
                if ($shelf_count['Brand']['code'] != $code && in_array($code, $rcode)) {
                    $result['code'] = '0';
                } else {
                    $result['code'] = '1';
                }
            }
            if ($result['code'] == 0) {
                $msg = $this->ld['brand_code_duplication'];
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	history.go(-1);</script>';
                die();
            }
            if (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 0) {
                $this->data['Brand']['orderby'] = 1;
                // 取出所有导航的 序值加1
                $all_brand = $this->Brand->find('all', array('fields' => 'Brand.id,Brand.orderby', 'order' => 'orderby asc', 'recursive' => '-1'));
                if (!empty($all_brand)) {
                    foreach ($all_brand as $k => $v) {
                        $all_brand[$k]['Brand']['orderby'] = $v['Brand']['orderby'] + 1;
                    }
                    $this->Brand->saveAll($all_brand);
                }
            } elseif (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 1) {
                $store_last = $this->Brand->find('first', array('recursive' => '-1', 'order' => 'orderby desc', 'limit' => '1'));
                $this->data['Brand']['orderby'] = $store_last['Brand']['orderby'] + 1;
            } elseif (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 2) {
                $store_change = $this->Brand->find('first', array('conditions' => array('Brand.id' => $_REQUEST['orderby_sel'])));
                $this->data['Brand']['orderby'] = $store_change['Brand']['orderby'] + 1;
                $all_brand = $this->Brand->find('all', array('conditions' => array('Brand.orderby >' => $store_change['Brand']['orderby']), 'recursive' => '-1'));
                if (!empty($all_brand)) {
                    foreach ($all_brand as $k => $v) {
                        $all_brand[$k]['Brand']['orderby'] = $v['Brand']['orderby'] + 1;
                    }
                    $this->Brand->saveAll($all_brand);
                }
            }
            $this->data['Brand']['flash_config'] = !empty($this->data['Brand']['flash_config']) ? $this->data['Brand']['orderby'] : '0';
            if (isset($this->data['Brand']['id']) && $this->data['Brand']['id'] != '') {
                $this->Brand->save(array('Brand' => $this->data['Brand'])); //关联保存
            } else {
                $this->Brand->saveAll(array('Brand' => $this->data['Brand'])); //关联保存
                $id = $this->Brand->getLastInsertId();
            }
            $this->BrandI18n->deleteall(array('brand_id' => $this->data['Brand']['id'])); //删除原有多语言
            foreach ($this->data['BrandI18n'] as $v) {
                $brandi18n_info = array(
			'locale' => $v['locale'],
			'brand_id' => $id,
			'name' => isset($v['name']) ? $v['name'] : '',
			'meta_keywords' => isset($v['meta_keywords']) ? $v['meta_keywords'] : '',
			'meta_description' => isset($v['meta_description']) ? $v['meta_description'] : '',
			'description' => $v['description'],
			'img01' => $v['img01'],
                  );
                $this->BrandI18n->saveAll(array('BrandI18n' => $brandi18n_info));//更新多语言
            }
            foreach ($this->data['BrandI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_edit_brand'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->data = $this->Brand->localeformat($id);
        $this->set('brand_category_type_id', isset($this->data['Brand']['category_type_id']) ? $this->data['Brand']['category_type_id'] : 0);//类目选中
        //导般 名称设置
        if (!empty($this->data['BrandI18n'][$this->backend_locale]['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].(isset($this->data['BrandI18n'][$this->backend_locale]['name']) ? $this->data['BrandI18n'][$this->backend_locale]['name'] : ''),'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_brand'],'url' => '');
        }

        //获取所有的品牌的对应关系
        $this->Brand->set_locale($this->backend_locale);
        $all_brand = $this->Brand->find('all');
        $this->set('all_brand', $all_brand);
    }

    public function act_view($id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $code = $_POST['code'];
        $rname = '';
        $name_code = $this->Brand->find('all', array('fields' => 'Brand.code'));
        if (isset($name_code) && sizeof($name_code) > 0) {
            foreach ($name_code as $vv) {
                $rname[] = $vv['Brand']['code'];
            }
        } else {
            $result['code'] = '1';
        }
        if ($id == 0) {
            if (isset($code) && $code != '') {
                if (in_array($code, $rname)) {
                    $result['code'] = '0';
                } else {
                    $result['code'] = '1';
                }
            } else {
                $result['code'] = '0';
            }
        } else {
            $Brand_count = $this->Brand->find('first', array('conditions' => array('Brand.id' => $id)));
            if ($Brand_count['Brand']['code'] != $code && in_array($code, $rname)) {
                $result['code'] = '0';
                //   $result['msg'] = "用户名重复";
            } else {
                $result['code'] = '1';
            }
        }
        die(json_encode($result));
    }
    /**
     *品牌 批量操作.
     */
    public function batch_operations()
    {
        $brand_id = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        $result['flag'] = 0;
        if ($brand_id != 0) {
            $condition['Brand.id'] = $brand_id;
            $this->Brand->deleteAll($condition);
            $this->BrandI18n->deleteAll(array('BrandI18n.brand_id' => $brand_id));
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
            }
            $result['flag'] = 1;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表品牌名称修改.
     */
    public function update_brand_name()
    {
        $this->Brand->hasMany = array();
        $this->Brand->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->BrandI18n->updateAll(
            array('name' => "'".$val."'"),
            array('brand_id' => $id, 'locale' => $this->locale)
        );
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *列表品牌code修改.
     */
    public function update_brand_code()
    {
        $this->Brand->hasMany = array();
        $this->Brand->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->Brand->updateAll(
            array('code' => "'".$val."'"),
            array('id' => $id)
        );
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表品牌网址修改.
     */
    public function update_brand_url()
    {
        $this->Brand->hasMany = array();
        $this->Brand->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->Brand->updateAll(
            array('url' => "'".$val."'"),
            array('id' => $id)
        );
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表排序修改.
     */
    public function update_brand_orderby()
    {
        $this->Brand->hasMany = array();
        $this->Brand->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_correct_sort'];
        }
        if (is_numeric($val) && $this->Brand->save(array('id' => $id, 'orderby' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //列表箭头排序
    public function changeorder($updowm, $id, $page = 1){
    	 Configure::write('debug', 1);
        $this->layout = 'ajax';
        //如果值相等重新自动排序
        $a = $this->Brand->query('SELECT * 
			FROM `svoms_brands` as A inner join `svoms_brands` as B
			WHERE A.id<>B.id and A.orderby=B.orderby');
        $brand_one = $this->Brand->find('first', array('conditions' => array('Brand.id' => $id)));
        if (!empty($a)) {
            $all = $this->Brand->find('all', array('recursive' => -1));
            $i = 0;
            foreach ($all as $k => $vv) {
                $all[$k]['Brand']['orderby'] = ++$i;
            }
            $this->Brand->saveAll($all);
        }
        if ($updowm == 'up') {
            $brand_change = $this->Brand->find('first', array('conditions' => array('Brand.orderby <' => $brand_one['Brand']['orderby'],"Brand.id <>"=>$id), 'order' => 'orderby desc','recursive' => -1));
        }
        if ($updowm == 'down') {
            $brand_change = $this->Brand->find('first', array('conditions' => array('Brand.orderby >' => $brand_one['Brand']['orderby'],"Brand.id <>"=>$id), 'order' => 'orderby asc', 'recursive' => -1));
        }
        $t = $brand_one['Brand']['orderby'];
        $brand_one['Brand']['orderby'] = $brand_change['Brand']['orderby'];
        $brand_change['Brand']['orderby'] = $t;
        $this->Brand->save($brand_one);
        $this->Brand->save($brand_change);
	 
        $condition = '';
        $this->Brand->set_locale($this->backend_locale);
        $total = $this->Brand->find('count');//统计全部品牌总数
	
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'brands','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Brand');
        $this->Pagination->init($condition, $parameters, $options);

        $fields[] = 'Brand.id';
        $fields[] = 'Brand.code';
        $fields[] = 'Brand.url';
        $fields[] = 'Brand.orderby';
        $fields[] = 'Brand.status';
        $fields[] = 'BrandI18n.name';
        $fields[] = 'Brand.recommand_flag';
        $brand_list = $this->Brand->find('all', array('order' => 'Brand.orderby asc,Brand.created desc', 'fields' => $fields, 'limit' => $rownum, 'page' => $page));
        $this->set('brand_list', $brand_list);
        $this->set('this_page', $page);
	
        
        $this->render('index');
    }
    /**
     *列表推荐修改.
     */
    public function toggle_on_status()
    {
        $this->Brand->hasMany = array();
        $this->Brand->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Brand->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    
    /**
     *列表推荐修改.
     */
    public function toggle_on_recommand()
    {
    	  Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->Brand->hasMany = array();
        $this->Brand->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Brand->save(array('id' => $id, 'recommand_flag' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status']."(".$this->ld['recommend'].")", $this->admin['id']);
            }
        }
        die(json_encode($result));
    }
    
    /**
     *删除一个品牌.
     *
     *@param int $id 输入品牌ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_brand_failure'];
        $pn = $this->BrandI18n->find('list', array('fields' => array('BrandI18n.brand_id', 'BrandI18n.name'), 'conditions' => array('BrandI18n.brand_id' => $id, 'BrandI18n.locale' => $this->locale)));
        $this->Brand->deleteAll(array('Brand.id' => $id));
        $this->BrandI18n->deleteAll(array('brand_id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_brand'].':id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_brand_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //快速添加品牌
    public function doinsertbrand()
    {
        $this->data1['Brand']['id'] = '';
        $this->data1['Brand']['code'] = isset($_POST['BrandCode']) ? $_POST['BrandCode'] : '';
        $this->Brand->saveAll($this->data1); //关联保存
        $id = $this->Brand->getLastInsertId();
        $this->BrandI18n->deleteall(array('brand_id' => $id)); //删除原有多语言
        foreach ($_POST['data1']['BrandI18n'] as $v) {
            $brandi18n_info = array(
                      'locale' => $v['locale'],
                      'brand_id' => $id,
                       'name' => isset($v['name']) ? $v['name'] : '',
                  );
            $a = $this->BrandI18n->saveAll(array('BrandI18n' => $brandi18n_info));//更新多语言
        }
        if ($a) {
            $result['message'] = $this->ld['complete_success'];
            $result['flag'] = 1;
            $brand_tree = $this->Brand->brand_tree($this->locale);//品牌获取
            $result['brand'] = $brand_tree;
            $result['last_brand'] = $id;
            //操作员日志
            if (isset($this->configs['open_OperatorLog']) && $this->configs['open_OperatorLog'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['quick_add_brand'].':'.$quick_brand_name, $this->admin['id']);
            }
        } else {
            $result['message'] = $this->ld['complete_failure'];
            $result['flag'] = 2;
        }
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //检查品牌名唯一
    public function check_unique($brands_id = 0)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $name = $_POST['name'];
        $brands_id = $_POST['brands_id'];
        $rname = '';
        $this->Brand->set_locale($this->model_locale['brand']);
        $name_code = $this->Brand->find('all', array('fields' => 'BrandI18n.name'));
        if (isset($name_code) && !empty($name_code)) {
            foreach ($name_code as $vv) {
                $rname[] = $vv['BrandI18n']['name'];
            }
        } else {
            $result['code'] = '0';
        }
        if ($brands_id == 0) {
            if (isset($name) && $name != '') {
                if (in_array($name, $rname)) {
                    $result['code'] = '1';
                    //   $result['msg'] = "品牌重复";
                } else {
                    $result['code'] = '0';
                }
            }
        } else {
            $brand_count = $this->Brand->find('first', array('conditions' => array('Brand.id' => $brands_id)));
            if ($brand_count['Operator']['name'] != $name && in_array($name, $rname)) {
                $result['code'] = '1';
            } else {
                $result['code'] = '0';
            }
        }
        die(json_encode($result));
    }

    public function search_brand()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['content'] = $this->ld['relevant_brand'];
        $keyword = $_REQUEST['val'];
        $keyword = str_replace('_', '/_', $keyword);
        $keyword = str_replace('%', '/%', $keyword);
        $condition['or']['Brand.code like'] = '%'.$keyword.'%';
        $condition['or']['BrandI18n.name like'] = '%'.$keyword.'%';
        $condition['Brand.status'] = 1;
        $condition['BrandI18n.locale'] = $this->backend_locale;
        $brand_list = $this->Brand->find('all', array('conditions' => $condition, 'fields' => array('Brand.id', 'BrandI18n.name', 'Brand.code')));
        if (!empty($brand_list)) {
            $result['flag'] = 1;
            $result['content'] = $brand_list;
        }
        die(json_encode($result));
    }

    public function doinsertbrand2()
    {
        $this->data1['Brand']['id'] = '';
        $this->data1['Brand']['code'] = isset($_POST['BrandCode']) ? $_POST['BrandCode'] : '';
        $this->Brand->saveAll($this->data1); //关联保存
        $id = $this->Brand->getLastInsertId();
        $this->BrandI18n->deleteall(array('brand_id' => $id)); //删除原有多语言
        foreach ($_POST['data1']['BrandI18n'] as $v) {
            $brandi18n_info = array(
                      'locale' => $v['locale'],
                      'brand_id' => $id,
                       'name' => isset($v['name']) ? $v['name'] : '',
                  );
            $a = $this->BrandI18n->saveAll(array('BrandI18n' => $brandi18n_info));//更新多语言
        }
        if ($a) {
            $result['message'] = $this->ld['complete_success'];
            $result['flag'] = 1;
            $result['code'] = $this->data1['Brand']['code'];
            //操作员日志
            if (isset($this->configs['open_OperatorLog']) && $this->configs['open_OperatorLog'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['quick_add_brand'].':'.$_POST['data1']['BrandI18n'][0]['name'], $this->admin['id']);
            }
        } else {
            $result['message'] = $this->ld['complete_failure'];
            $result['flag'] = 2;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    
       //品牌管理上传
public function brand_upload(){
	  Configure::write('debug', 0);
        $this->operation_return_url(true);//设置操作返回页面地址

           $this->menu_path = array('root' => '/product/','sub' => '/brands/');
        $this->set('title_for_layout', $this->ld['add_edit_brand'].'- '.$this->ld['manager_brands'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manager_brands'],'url' => '/brands/');
        $this->CategoryType->set_locale($this->backend_locale);
           $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
           $this->set('title_for_layout', $this->ld['manager_brands'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
           $this->Profile->hasOne = array();
           $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'brand_export', 'Profile.status' => 1)));
		$this->set('profile_id',$profile_id);
	
    }



//品牌管理cvs查看
 public function brand_uploadpreview()
    {
    	Configure::write('debug', 1);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/brands/brand_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'brand_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
                            $key_arr = array();
                            foreach($fields_array as $k=>$v){
                            	$key_arr[] = $v;
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                if($row_count!=$num_count){
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/brands/brand_upload';</script>";
						die();
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/brands/brand_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            //pr($fields);pr($key_arr);die();
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
			$upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                       
                        if( isset($v['Brand']['code']) && $v['Brand']['code']!="" ){
                        	$CategoryType_list=$this->CategoryType->find('list',array('fields'=>array('code','id'),'order'=>'CategoryType.id desc'));
                        $Brand_condition='';
                         if( isset( $CategoryType_list[$v['Brand']['category_type_id']] )  ){
					$Brand_condition['Brand.category_type_id']=$CategoryType_list[$v['Brand']['category_type_id']];
				}
                      
                        if(!empty($v['Brand']['code'])){
                        	$Brand_condition['Brand.code']=$v['Brand']['code'];
                        }
                       
                       
                        $Brand_first = $this->Brand->find('first', array('conditions' =>$Brand_condition));
                        $v['Brand']['id']=isset($Brand_first['Brand']['id'])?$Brand_first['Brand']['id']:0;
                        $v['Brand']['category_type_id']=isset($CategoryType_list[$v['Brand']['category_type_id']])?$CategoryType_list[$v['Brand']['category_type_id']]:0;
                        $v['Brand']['status']=isset($v['Brand']['status'])?$v['Brand']['status']:1;
                        $v['Brand']['orderby']=isset($v['Brand']['orderby'])?$v['Brand']['orderby']:50;
                        $v['Brand']['recommand_flag']=isset($v['Brand']['recommand_flag'])?$v['Brand']['recommand_flag']:1;
                        	if( $s1=$this->Brand->save($v['Brand']) ){
                        		$Brand_id=$this->Brand->id;
                        	}
                        $BrandI18n_condition='';
                        if(isset($Brand_id)){
                        	$BrandI18n_condition['BrandI18n.brand_id']=$Brand_id;
                        }
                       
                        if(isset($v['BrandI18n']['locale']) && !empty($v['BrandI18n']['locale'])){
                        	$BrandI18n_condition['BrandI18n.locale']= $v['BrandI18n']['locale'];
                        }
                      	$BrandI18n_first = $this->BrandI18n->find('first', array('conditions' => $BrandI18n_condition));
                        $v['BrandI18n']['id']=isset($BrandI18n_first['BrandI18n']['id'])?$BrandI18n_first['BrandI18n']['id']:0;
                        $v['BrandI18n']['brand_id']=isset($Brand_id)?$Brand_id:'';
                        if(isset($v['BrandI18n']['brand_id']) && $v['BrandI18n']['brand_id']!=''){	$s2=$this->BrandI18n->save($v['BrandI18n']); }
                        	 if( isset($s1)&&!empty($s1)&&isset($s2)&&!empty($s2)){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                    }
                    }
                    //die();
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/brands/'</script>";
		            die();
                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/brands/brand_upload/'</script>";
                    	
                }
         
    }

      /////////////////////////////////////////////
      public function fgetcsv_reg($handle, $length = null, $d = ',', $e = '"')
      {
          $d = preg_quote($d);
          $e = preg_quote($e);
          $_line = '';
          $eof = false;
          while ($eof != true) {
              $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
              $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
              if ($itemcnt % 2 == 0) {
                  $eof = true;
              }
          }
          $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
          $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
          preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
          $_csv_data = $_csv_matches[1];
          for ($_csv_i = 0; $_csv_i < count($_csv_data); ++$_csv_i) {
              $_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s', '$1', $_csv_data[$_csv_i]);
              $_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
          }

          return empty($_line) ? false : $_csv_data;
      }



		 
//品牌管理csv
public function download_brand_csv_example($out_type = 'Brand'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     $this->Brand->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'brand_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
  	//pr($tmp);pr($fields_array);die();
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $Brand_info = $this->Brand->find('all', array('fields'=>array('Brand.code','Brand.category_type_id','Brand.orderby','Brand.img01','Brand.img02','Brand.flash_config','Brand.status','Brand.recommand_flag','Brand.url','BrandI18n.locale','BrandI18n.name','BrandI18n.description','BrandI18n.meta_keywords','BrandI18n.meta_description','BrandI18n.img01','BrandI18n.img02','BrandI18n.img03'),'order' => 'Brand.orderby ','conditions'=>array('Brand.category_type_id  <>' => 0),'limit'=>10));
	//pr($Resource_info);die();
	$CategoryType_list=$this->CategoryType->find('list',array('fields'=>array('id','code'),'order'=>'CategoryType.id desc'));
              //循环数组
              foreach($Brand_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                 if($fields_ks[1]=='category_type_id'){
	                 	 $user_tmp[] = isset($CategoryType_list[$v['Brand']['category_type_id']])?$CategoryType_list[$v['Brand']['category_type_id']]:'';
	                 }else{
                          $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]])?$v[$fields_ks[0]][$fields_ks[1]]:'';
                     }
	              }
	              //pr($user_tmp);die();
	              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}
//全部导出   
public function all_export_csv($out_type = 'Brand'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     $this->Brand->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'brand_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
  //	pr($tmp);
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
            $Brand_info = $this->Brand->find('all', array('fields'=>array('Brand.code','Brand.category_type_id','Brand.orderby','Brand.img01','Brand.img02','Brand.flash_config','Brand.status','Brand.recommand_flag','Brand.url','BrandI18n.locale','BrandI18n.name','BrandI18n.description','BrandI18n.meta_keywords','BrandI18n.meta_description','BrandI18n.img01','BrandI18n.img02','BrandI18n.img03'),'order' => 'Brand.orderby'));
	//pr($Resource_info);die();
	$CategoryType_list=$this->CategoryType->find('list',array('fields'=>array('id','code'),'order'=>'CategoryType.id desc'));

            
              //循环数组
              foreach($Brand_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	               if($fields_ks[1]=='category_type_id'){
	                 	 $user_tmp[] = isset($CategoryType_list[$v['Brand']['category_type_id']])?$CategoryType_list[$v['Brand']['category_type_id']]:'';
	                 }else{
                          $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]])?$v[$fields_ks[0]][$fields_ks[1]]:'';
                     }
	                  
	                 
	              }
	              //pr($user_tmp);die();
	              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
        	exit;
      
}  
    
 //选择导出   
public function choice_export($out_type = 'Brand'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     $this->Brand->set_locale($this->backend_locale);
     $user_checkboxes = $_REQUEST['checkboxes'];
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'brand_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
  //	pr($tmp);
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $Brand_conditions='';
          $Brand_conditions['AND']['Brand.id']=$user_checkboxes; 
           $Brand_info = $this->Brand->find('all', array('fields'=>array('Brand.code','Brand.category_type_id','Brand.orderby','Brand.img01','Brand.img02','Brand.flash_config','Brand.status','Brand.recommand_flag','Brand.url','BrandI18n.locale','BrandI18n.name','BrandI18n.description','BrandI18n.meta_keywords','BrandI18n.meta_description','BrandI18n.img01','BrandI18n.img02','BrandI18n.img03'),'order' => 'Brand.orderby','conditions'=>$Brand_conditions));
	//pr($Resource_info);die();
	$CategoryType_list=$this->CategoryType->find('list',array('fields'=>array('id','code'),'order'=>'CategoryType.id desc'));

            
              //循环数组
              foreach($Brand_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                 if($fields_ks[1]=='category_type_id'){
	                 	 $user_tmp[] = isset($CategoryType_list[$v['Brand']['category_type_id']])?$CategoryType_list[$v['Brand']['category_type_id']]:'';
	                 }else{
                          $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]])?$v[$fields_ks[0]][$fields_ks[1]]:'';
                     }
	                  
	                 
	              }
	              //pr($user_tmp);die();
	              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
        	exit;
      
}  

}
