<?php

/*****************************************************************************
 * Seevia 商店设置
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
class ConfigsController extends AppController
{
    public $name = 'Configs';
    public $components = array('Phpexcel','Phpcsv','Pagination','RequestHandler'); // Added 
    public $helpers = array('Pagination','Javascript'); // Added 
    public $uses = array('Config','ConfigI18n','Dictionary','SystemResource','OperatorLog');

    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('site_settings_view');
        /*end*/
        $this->menu_path = array('root' => '/web_application/','sub' => '/configs/');
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $title = isset($this->backend_locale) && $this-> backend_locale== 'eng' ? $this->ld['shop_configs'].' '.$this->ld['region_view'] : $this->ld['shop_configs'].$this->ld['region_view'];
        $this->navigations[] = array('name' => $title,'url' => '/configs/');
        //资源库信息
        $this->SystemResource->set_locale($this->backend_locale);
        $systemresource_info = $this->SystemResource->resource_formated('configvalues',$this->backend_locale);
        $config_group_code=isset($systemresource_info['configvalues'])?$systemresource_info['configvalues']:array();
        
        $log_type = '';
        $show_name = '';
        $sub_group = '';
        $version = '';
        $condition = '';
        $config_keywords = '';     //关键字
        if (isset($this->params['url']['system_code']) && trim($this->params['url']['system_code']) != '') {
	            $condition['Config.system_code'] = $this->params['url']['system_code'];
	            $this->set('system_code', $this->params['url']['system_code']);
        }
        if (isset($this->params['url']['module_code']) && trim($this->params['url']['module_code']) != '') {
	            $condition['Config.module_code'] = $this->params['url']['module_code'];
	            $this->set('module_code', $this->params['url']['module_code']);
        }
        //关键字
        if (isset($this->params['url']['config_keywords']) && trim($this->params['url']['config_keywords']) != '') {
            $config_keywords = $this->params['url']['config_keywords'];
            $condition['and']['or']['ConfigI18n.name like'] = '%'.$config_keywords.'%';
            $condition['and']['or']['Config.code like'] = '%'.$config_keywords.'%';
        }
        //pr($this->params['url']);
        if (isset($this->params['url']['log_type']) && $this->params['url']['log_type'] != '') {
            $condition['Config.type'] = $this->params['url']['log_type'];
            $log_type = $this->params['url']['log_type'];
            $this->set('log_type', $this->params['url']['log_type']);
        }
        if (isset($this->params['url']['show_name']) && trim($this->params['url']['show_name'] )!= '') {
            $condition['Config.group_code'] = $this->params['url']['show_name'];
            $show_name = $this->params['url']['show_name'];
        }
        if (isset($this->params['url']['version']) && trim($this->params['url']['version']) != '') {
            $condition['Config.section'] = $this->params['url']['version'];
            $version = $this->params['url']['version'];
        }
        if (isset($this->params['url']['sub_group']) && trim($this->params['url']['sub_group']) != '') {
            $condition['Config.subgroup_code'] = $this->params['url']['sub_group'];
            $sub_group = $this->params['url']['sub_group'];
        }
        //$condition["Config.type !="] = "hidden";
        $this->Config->set_locale($this->backend_locale);
       // pr($this->params['url']);
       // pr($condition);
        $total = $this->Config->find('count', array('conditions' => $condition));

        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'configs','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Config');
        $this->Pagination->init($condition, $parameters, $options);
        $configs_list = $this->Config->find('all', array('conditions' => $condition, 'order' => 'Config.group_code,Config.subgroup_code,Config.orderby asc', 'limit' => $rownum, 'page' => $page));
        $this->set('configs_list', $configs_list);
        $this->set('log_type', $log_type);
        $this->set('show_name', $show_name);
        $this->set('version', $version);
        $this->set('sub_group', $sub_group);
        $this->set('config_keywords', $config_keywords);
        $this->set('config_group_code', $config_group_code);
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
        //$this->set('section',$systemresource_info["section"]);
        $this->set('title_for_layout', $title.' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
             
             $all_system_modules =         $this->System->modules(false);
            $all_systems=array_keys($all_system_modules);
            $this->set('all_system_modules', $all_system_modules);
            $this->set('all_systems', $all_systems);
    }

    public function view($id = 0){
        /*判断权限*/
        if ($id == 0) {
            $this->operator_privilege('site_settings_add');
        } else {
            $this->operator_privilege('site_settings_edit');
        }
        /*end*/
        $this->menu_path = array('root' => '/web_application/','sub' => '/configs/');
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => isset($this->backend_locale) && $this->backend_locale == 'eng' ? $this->ld['shop_configs'].' '.$this->ld['region_view'] : $this->ld['shop_configs'].$this->ld['region_view'],'url' => '/configs/');

        if ($this->RequestHandler->isPost()) {
		$config_code = !empty($this->data['Config']['code']) ? $this->data['Config']['code'] : '';
		$this->data['Config']['orderby'] = !empty($this->data['Config']['orderby']) ? $this->data['Config']['orderby'] : '50';
		$this->Config->save(array('Config' => $this->data['Config']));
		$id = $this->Config->id;
		if (is_array($this->data['ConfigI18n'])) {
			foreach ($this->data['ConfigI18n'] as $k => $v) {
				if(empty($this->data['Config']['id'])){
					$v['value']=$v['default_value'];
				}
				$v['config_id'] = $id;
				$v['config_code'] = $config_code;
				$this->ConfigI18n->save(array('ConfigI18n' => $v));
				if ($v['locale'] == $this->backend_locale) {
					$userinformation_name = $v['name'];
				}
			}
		}
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑商店设置: '.$userinformation_name, $this->admin['id']);
            }
            $this->redirect('/configs');
        }

        $this->Config->hasOne = array('ConfigI18n' => array('className' => 'ConfigI18n',
                    'conditions' => '',
                    'order' => 'Config.orderby asc',
                    'dependent' => true,
                    'foreignKey' => 'config_id',
                ),
            );

        $this->data = $this->Config->localeformat($id);

        $this->set('configs_info', $this->data);
        $title = isset($this->backend_locale) && $this->backend_locale == 'eng' ? $this->ld['shop_configs'].' '.$this->ld['region_view'] : $this->ld['shop_configs'].$this->ld['region_view'];
        $this->set('title_for_layout', $title.' - '.$this->configs['shop_name']);

        //leo20090722导航显示
        if (isset($this->data['ConfigI18n'][$this->backend_locale]['name'])) {
            $this->navigations[] = array('name' => $this->data['ConfigI18n'][$this->backend_locale]['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => isset($this->backend_locale) && $this->backend_locale == 'eng' ? $this->ld['add'].' '.$this->ld['shop_configs'] : $this->ld['add'].$this->ld['shop_configs'],'url' => '');
        }
        $this->set('navigations', $this->navigations);
        
        $this->SystemResource->set_locale($this->backend_locale);
        $systemresource_info = $this->SystemResource->resource_formated('configvalues',$this->backend_locale);
        $config_group_code=isset($systemresource_info['configvalues'])?$systemresource_info['configvalues']:array();
        $this->set('config_group_code',$config_group_code);
             
            $all_system_modules =         $this->System->modules(false);
            $all_systems=array_keys($all_system_modules);
            $this->set('all_system_modules', $all_system_modules);
            $this->set('all_systems', $all_systems);
    }

    public function add()
    {
        /*判断权限*/
        $this->operator_privilege('shop_set_add');
        /*end*/
        $this->pageTitle = '商店设置管理 - 商店设置管理'.' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => '功能管理','url' => '');
        $this->navigations[] = array('name' => '商店设置管理','url' => '/configs/');
        $this->navigations[] = array('name' => '编辑实商店设置','url' => '');
        $this->set('navigations', $this->navigations);
        $this->SystemResource->set_locale($this->locale);
        $this->set('section', $this->SystemResource->find_assoc('section'));
        if ($this->RequestHandler->isPost()) {
            $this->data['Config']['orderby'] = !empty($this->data['Config']['orderby']) ? $this->data['Config']['orderby'] : '50';
            $this->Config->saveall(array('Config' => $this->data['Config']));
            $id = $this->Config->id;
            if (is_array($this->data['ConfigI18n'])) {
                foreach ($this->data['ConfigI18n'] as $k => $v) {
                    $v['config_id'] = $id;
                    $this->ConfigI18n->saveall(array('ConfigI18n' => $v));
                    if ($v['locale'] == $this->locale) {
                        $userinformation_name = $v['name'];
                    }
                }
            }
                //操作员日志
                if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                    $this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'增加商店设置:'.$userinformation_name, 'operation');
                }
            $this->flash('商店设置  '.$userinformation_name.'  添加成功。点击这里继续编辑该商店设置。', '/configs/edit/'.$id, 10);
        }
    }

    public function remove($id)
    {
        /*判断权限*/
        $this->operator_privilege('site_settings_remove');
        /*end*/
        $this->ConfigI18n->deleteAll(array('ConfigI18n.config_id' => $id));
        $this->Config->delete(array('Config.id' => $id));
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 删除网站设置:id '.$id, $this->admin['id']);
        }
        $this->redirect('/configs/');
    }
    
    function system_modified(){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result['flag'] = 0;
		$result['content'] = $this->ld['modify_failed'];
		$config_id=isset($_POST['id'])?$_POST['id']:0;
		$system_code=isset($_POST['val'])?trim($_POST['val']):'';
		$this->Config->save(array('id'=>$config_id,'system_code'=>$system_code));
		$result['flag'] = 1;
		$result['content'] = $system_code;
		die(json_encode($result));
      }
      
      function module_modified(){
      	Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result['flag'] = 0;
		$result['content'] = $this->ld['modify_failed'];
		$config_id=isset($_POST['id'])?$_POST['id']:0;
		$module_code=isset($_POST['val'])?trim($_POST['val']):'';
		$this->Config->save(array('id'=>$config_id,'module_code'=>$module_code));
		$result['flag'] = 1;
		$result['content'] = $module_code;
		die(json_encode($result));
      }
    
    //批量删除
 public function batch_operations()
    {
    
        /*判断权限*/
        if (!$this->operator_privilege('site_settings_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        $user_checkboxes = $_REQUEST['checkboxes'];
        //pr($user_checkboxes);die();
        foreach ($user_checkboxes as $k => $v) {
        $this->ConfigI18n->deleteAll(array('ConfigI18n.config_id' => $v));
        $this->Config->delete(array('Config.id' => $v));
        }
       
        $result['flag'] = 1;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    
    
    
   //批量处理
   public function batch()
   {
       $this->Config->hasOne = array();
       $this->Config->hasOne = array('ConfigI18n' => array('className' => 'ConfigI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'config_id',
                        ),
                 );
       if (isset($this->params['url']['act_type']) && !empty($this->params['url']['checkbox'])) {
           $id_arr = $this->params['url']['checkbox'];
           $condition = '';
           for ($i = 0;$i <= count($id_arr) - 1;++$i) {
               if ($this->params['url']['act_type'] == 'delete') {
                   $condition['Config.id'] = $id_arr[$i];
               }
           }
           $this->Config->deleteAll($condition);
               //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'批量删除商店设置', 'operation');
            }
           $this->flash('删除成功', '/configs/', 10);
       } else {
           $this->flash('请选择内容', '/configs/', '');
       }
   }
    /**
     *列表只读修改.
     */
    public function toggle_on_readonly()
    {
        $this->Config->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Config->save(array('id' => $id, 'readonly' => $val))) {
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
     *列表状态修改.
     */
    public function toggle_on_status()
    {
        $this->Config->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Config->save(array('id' => $id, 'status' => $val))) {
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
    
   //AJAX修改排序
    public function update_config_orderby()
    {
        $this->Config->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->Config->updateAll(
            array('orderby' => "'".$val."'"),
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
     *	列表描述修改.
     */
    public function update_config_description(){
		Configure::write('debug', 0);
		$this->layout = 'ajax';
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
        	$val = isset($_REQUEST['val'])?trim($_REQUEST['val']):'';
        	$result = array();
        	$result['flag'] = 0;
        	$result['content'] = stripslashes($val);
        	$config_i18n_data=$this->ConfigI18n->find('first',array("conditions"=>array("ConfigI18n.locale"=>$this->backend_locale,"ConfigI18n.config_id"=>$id)));
        	if(!empty($config_i18n_data['ConfigI18n'])){
        		$request = $this->ConfigI18n->save(array(
	        				'id'=>$config_i18n_data['ConfigI18n']['id'],
	        				'description'=>$val
        				)
        			);
        	}
        	if (isset($request)&&$request) {
        		$result['flag'] = 1;
        	}
        	die(json_encode($result));
    }
    
    public function update_table_frame()
    {
        $table_array = array(
                  'svcart_advertisements',
      'svcart_advertisement_i18ns',
      'svcart_articles',
      'svcart_article_categories',
      'svcart_article_i18ns',
      'svcart_booking_products',
      'svcart_brands',
      'svcart_brand_i18ns',
      'svcart_cards',
      'svcart_card_i18ns',
      'svcart_carts',
      'svcart_categories',
      'svcart_category_i18ns',
      'svcart_comments',
      'svcart_configs',
      'svcart_config_i18ns',
      'svcart_coupons',
      'svcart_coupon_types',
      'svcart_coupon_type_i18ns',
      'svcart_departments',
      'svcart_department_i18ns',
      'svcart_flashes',
      'svcart_flash_images',
      'svcart_languages',
      'svcart_language_dictionaries',
      'svcart_links',
      'svcart_link_i18ns',
      'svcart_mail_templates',
      'svcart_mail_template_i18ns',
      'svcart_navigations',
      'svcart_navigation_i18ns',
      'svcart_newsletter_lists',
      'svcart_operators',
      'svcart_operator_actions',
      'svcart_operator_action_i18ns',
      'svcart_operator_logs',
      'svcart_operator_menus',
      'svcart_operator_menu_i18ns',
      'svcart_operator_roles',
      'svcart_operator_role_i18ns',
      'svcart_orders',
      'svcart_order_actions',
      'svcart_order_cards',
      'svcart_order_packagings',
      'svcart_order_products',
      'svcart_packagings',
      'svcart_packaging_i18ns',
      'svcart_payments',
      'svcart_payment_api_logs',
      'svcart_payment_i18ns',
      'svcart_products',
      'svcart_products_categories',
      'svcart_product_articles',
      'svcart_product_attributes',
      'svcart_product_galleries',
      'svcart_product_gallery_i18ns',
      'svcart_product_i18ns',
      'svcart_product_ranks',
      'svcart_product_relations',
      'svcart_product_types',
      'svcart_product_type_attributes',
      'svcart_product_type_attribute_i18ns',
      'svcart_product_type_i18ns',
      'svcart_promotions',
      'svcart_promotion_i18ns',
      'svcart_promotion_products',
      'svcart_providers',
      'svcart_provider_products',
      'svcart_regions',
      'svcart_region_i18ns',
      'svcart_sessions',
      'svcart_shippings',
      'svcart_shipping_areas',
      'svcart_shipping_area_i18ns',
      'svcart_shipping_area_regions',
      'svcart_shipping_i18ns',
      'svcart_stores',
      'svcart_store_i18ns',
      'svcart_store_products',
      'svcart_templates',
      'svcart_topics',
      'svcart_topic_i18ns',
      'svcart_topic_products',
      'svcart_users',
      'svcart_user_accounts',
      'svcart_user_addresses',
      'svcart_user_balance_logs',
      'svcart_user_configs',
      'svcart_user_config_i18ns',
      'svcart_user_favorites',
      'svcart_user_friends',
      'svcart_user_friend_cats',
      'svcart_user_infos',
      'svcart_user_info_i18ns',
      'svcart_user_info_values',
      'svcart_user_messages',
      'svcart_user_point_logs',
      'svcart_user_ranks',
      'svcart_user_rank_i18ns',
      'svcart_virtual_cards',

        );
        foreach ($table_array as $k => $v) {
            $table_name = $v;
            $table_name_serial = $v.'_id_seq';
            $sql = 'select count(*)+1 as count from '.$table_name;
            $infor = $this->Config->query($sql);
            $sql = 'CREATE SEQUENCE svcart_user_infos_id_seq START '.$infor[0][0]['count'];
            $this->Config->query($sql);
            $sql = 'ALTER SEQUENCE svcart_user_infos_id_seq RESTART WITH '.$infor[0][0]['count'];
            $info_info = $this->Config->query($sql);
            if (empty($info_info)) {
                echo $k.'<br />';
            } else {
            }
        }
        die();
    }
    
    function ajax_subgroup_code_get($group_code=""){
    		$this->operator_privilege('site_settings_view');
    		Configure::write('debug', 0);
        	$this->layout = 'ajax';
		$this->SystemResource->set_locale($this->backend_locale);
		$sub_group_cond['SystemResource.code'] = $group_code."_set";
		$sub_group_cond['SystemResource.status'] = "1";
        	$sub_group_ids = $this->SystemResource->find('list', array('fields' => array('SystemResource.id'), 'conditions' => $sub_group_cond));
        	$sub_group_Info=array();
        	if(!empty($sub_group_ids)){
        		$sub_group_set_cond=array();
        		$sub_group_set_cond['SystemResource.parent_id'] = $sub_group_ids;
        		$sub_group_set_cond['SystemResource.status'] = '1';
        		$sub_group_Info = $this->SystemResource->find('all', array('fields' => array('SystemResource.code', 'SystemResourceI18n.name'), 'conditions' => $sub_group_set_cond, 'order' => 'SystemResource.orderby,SystemResource.id'));
        	}
		die(json_encode($sub_group_Info));
    }
    
    
      
    //批量上传
     public function config_upload()
    {
        $this->menu_path = array('root' => '/web_application/','sub' => '/configs/');
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['shop_configs'].$this->ld['region_view'],'url' => '/configs/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
        $this->set('title_for_layout', $this->ld['shop_configs'].$this->ld['region_view'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
    }
	
    public function config_uploadpreview()
    {
        ////////////判断权限
        Configure::write('debug', 1);
        $success_num=0;
            if ($this->operator_privilege('shop_set_add')) {
                if (isset($_POST['sub1']) && $_POST['sub1'] == 1 && !empty($_FILES['file'])) {
                     $this->menu_path = array('root' => '/web_application/','sub' => '/configs/');
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['shop_configs'].$this->ld['region_view'],'url' => '/configs/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
        $this->set('title_for_layout', $this->ld['shop_configs'].$this->ld['region_view'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/configs/config_upload';</script>";
                            die();
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                             $fields_array = array(
                           'Config.group_code',
                            'Config.subgroup_code',
                            'Config.code',
                            	'Config.type',
                            'Config.readonly',
                            'Config.section',
                            'Config.status',
                            'Config.orderby','ConfigI18n.locale',
                            'ConfigI18n.name',
                            'ConfigI18n.description',
                            	'ConfigI18n.default_value',
                            	'ConfigI18n.value',
                            	'ConfigI18n.options');

                            $fields = array(
                                  $this->ld['group'],
                            $this->ld['subparameter'],
                            $this->ld['userconfig_code'],
                            $this->ld['html_type'],
                            $this->ld['readonly'],
                            $this->ld['version'],
                            $this->ld['s_status'],
                            $this->ld['sort_by'],$this->ld['s_language'],
                                $this->ld['z_name'],
                             $this->ld['z_description'],
                                $this->ld['default_value'],
                                $this->ld['value'],$this->ld['option'] );
                            $key_arr = array();
                            foreach ($fields_array as $k => $v) {
                                $key_arr[] = isset($v) ? $v : '';
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                  if($row_count!=$num_count){
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/configs/config_upload';</script>";
						die();
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = empty($v) ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/configs/config_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
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
				if(isset($v['Config']['code'])&&$v['Config']['code']!='' ){
				$Config_condition='';
				if(isset($v['Config']['code'])&&$v['Config']['code']!='' ){
				$Config_condition['Config.code']=$v['Config']['code'];
				}
                        if(isset($v['Config']['group_code'])&&$v['Config']['group_code']!=''){
                         $Config_condition['Config.group_code']=$v['Config']['group_code'];
                     	}
                     	if(isset($v['Config']['subgroup_code'])&&$v['Config']['subgroup_code']!=''){
                         $Config_condition['Config.subgroup_code']=$v['Config']['subgroup_code'];
                     	}
                     	if(isset($v['Config']['section'])&&$v['Config']['section']!=''){
                         $Config_condition['Config.section']=$v['Config']['section'];
                     	}
                     	if(isset($v['Config']['status'])&&$v['Config']['status']!=''){
                         $Config_condition['Config.status']=$v['Config']['status'];
                     	}
                     	//pr($Config_condition);
                        $Config_first = $this->Config->find('first', array('conditions' =>$Config_condition));
                        $v['Config']['id']=isset($Config_first['Config']['id'])?$Config_first['Config']['id']:0;
                        $v['Config']['readonly']=isset($v['Config']['readonly'])&&$v['Config']['readonly']!=''?$v['Config']['readonly']:0;
                        $v['Config']['status']=isset($v['Config']['status'])&&$v['Config']['status']!=''?$v['Config']['status']:1;
                        $v['Config']['orderby']=isset($v['Config']['orderby'])&&$v['Config']['orderby']!=''?$v['Config']['orderby']:50;
					if( $s1=$this->Config->save($v['Config']) ){
                        		$Config_id=$this->Config->id;
                        	}
                        $ConfigI18n_condition='';
                        if(isset($Config_id)&&$Config_id!=''){
                        	$ConfigI18n_condition['ConfigI18n.config_id']=$Config_id;	
                        }
                        if(isset($v['ConfigI18n']['locale'])&&$v['ConfigI18n']['locale']!=''){
                        	$ConfigI18n_condition['ConfigI18n.locale']=$v['ConfigI18n']['locale'];	
                        }
                        $ConfigI18n_first = $this->ConfigI18n->find('first', array('conditions' =>$ConfigI18n_condition));
                        $v['ConfigI18n']['id']=isset($ConfigI18n_first['ConfigI18n']['id'])?$ConfigI18n_first['ConfigI18n']['id']:0;
                        $v['ConfigI18n']['config_id']=isset($Config_id)?$Config_id:'';
				if(isset($v['ConfigI18n']['config_id'])&&$v['ConfigI18n']['config_id']!=''){
                        	$s2=$this->ConfigI18n->save($v['ConfigI18n']);
                		}
                		 if( isset($s1)&&!empty($s1)&&isset($s2)&&!empty($s2)){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                    }
                    }
                    //die();
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/configs/'</script>";
			
                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/configs/config_upload/'</script>";
                }
            } ///////权限判断结束
    }
    //导出信息资源
      public function download_config_csv_example()
      {
      	  Configure::write('debug', 1);
      	  $this->layout="ajax";
      	  $this->Config->set_locale($this->backend_locale);
              //定义一个数组
        $fields_array = array(
                           'Config.group_code',
                            'Config.subgroup_code',
                            'Config.code',
                            	'Config.type',
                            'Config.readonly',
                            'Config.section',
                            'Config.status',
                            'Config.orderby','ConfigI18n.locale',
                            'ConfigI18n.name',
                            'ConfigI18n.description',
                            	'ConfigI18n.default_value',
                            	'ConfigI18n.value',
                            	'ConfigI18n.options');

                            $fields = array(
                                  $this->ld['group'],
                            $this->ld['subparameter'],
                            $this->ld['userconfig_code'],
                            $this->ld['html_type'],
                            $this->ld['z_readonly'],
                            $this->ld['version'],
                            $this->ld['s_status'],
                            $this->ld['sort_by'],$this->ld['s_language'],
                                $this->ld['z_name'],
                             $this->ld['z_description'],
                                $this->ld['default_value'],
                                $this->ld['value'],$this->ld['option'] );
          $newdatas = array();
          $newdatas[] = $fields;
          //查询所有表里面所有信息 查询 10 条信息
          $Config_info = $this->Config->find('all', array('fields'=>array('ConfigI18n.locale',
                            'ConfigI18n.name',
                            'ConfigI18n.description',
                            	'ConfigI18n.default_value',
                            	'ConfigI18n.value',
                            	'ConfigI18n.options',
                           'Config.group_code',
                            'Config.subgroup_code',
                            'Config.code',
                            	'Config.type',
                            'Config.readonly',
                            'Config.section',
                            'Config.status',
                            'Config.orderby'),'order' => 'Config.id desc', 'limit' => 10));
          foreach ($Config_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
          $nameexl = $this->ld['shop_configs'].$this->ld['region_view'].date('Ymd').'.csv';
          $this->Phpcsv->output($nameexl, $newdatas);
          die();
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
      
      //全部导出
      public function all_export_csv()
      {
      	  Configure::write('debug', 1);
      	  $this->layout="ajax";
      	  $this->Config->set_locale($this->backend_locale);
              //定义一个数组
        $fields_array = array(
                           'Config.group_code',
                            'Config.subgroup_code',
                            'Config.code',
                            	'Config.type',
                            'Config.readonly',
                            'Config.section',
                            'Config.status',
                            'Config.orderby','ConfigI18n.locale',
                            'ConfigI18n.name',
                            'ConfigI18n.description',
                            	'ConfigI18n.default_value',
                            	'ConfigI18n.value',
                            	'ConfigI18n.options');

                            $fields = array(
                                  $this->ld['group'],
                            $this->ld['subparameter'],
                            $this->ld['userconfig_code'],
                            $this->ld['html_type'],
                            $this->ld['z_readonly'],
                            $this->ld['version'],
                            $this->ld['s_status'],
                            $this->ld['sort_by'],$this->ld['s_language'],
                                $this->ld['z_name'],
                             $this->ld['z_description'],
                                $this->ld['default_value'],
                                $this->ld['value'],$this->ld['option'] );
          $newdatas = array();
          $newdatas[] = $fields;
          //查询所有表里面所有信息 
          $Config_info = $this->Config->find('all', array('fields'=>array('ConfigI18n.locale',
                            'ConfigI18n.name',
                            'ConfigI18n.description',
                            	'ConfigI18n.default_value',
                            	'ConfigI18n.value',
                            	'ConfigI18n.options',
                           'Config.group_code',
                            'Config.subgroup_code',
                            'Config.code',
                            	'Config.type',
                            'Config.readonly',
                            'Config.section',
                            'Config.status',
                            'Config.orderby'),'order' => 'Config.id desc'));

          foreach ($Config_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                 
                  	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
          $nameexl = $this->ld['shop_configs'].$this->ld['region_view'].$this->ld['export'].date('Ymd').'.xls';
          $this->Phpexcel->output($nameexl, $newdatas);
          die();
      }
      
      //选择导出
      public function choice_export()
      {
      	  Configure::write('debug', 0);
      	  $this->layout="ajax";
      	  $this->Config->set_locale($this->backend_locale);
      	  $user_checkboxes = $_REQUEST['checkboxes'];
              //定义一个数组
        $fields_array = array(
                           'Config.group_code',
                            'Config.subgroup_code',
                            'Config.code',
                            	'Config.type',
                            'Config.readonly',
                            'Config.section',
                            'Config.status',
                            'Config.orderby','ConfigI18n.locale',
                            'ConfigI18n.name',
                            'ConfigI18n.description',
                            	'ConfigI18n.default_value',
                            	'ConfigI18n.value',
                            	'ConfigI18n.options');

                            $fields = array(
                                  $this->ld['group'],
                            $this->ld['subparameter'],
                            $this->ld['userconfig_code'],
                            $this->ld['html_type'],
                            $this->ld['z_readonly'],
                            $this->ld['version'],
                            $this->ld['s_status'],
                            $this->ld['sort_by'],$this->ld['s_language'],
                                $this->ld['z_name'],
                             $this->ld['z_description'],
                                $this->ld['default_value'],
                                $this->ld['value'],$this->ld['option'] );
          $newdatas = array();
          $newdatas[] = $fields;
          $Config_conditions['AND']['Config.status']=1; 
          $Config_conditions['AND']['Config.id']=$user_checkboxes; 
          $Config_info = $this->Config->find('all', array('fields'=>array('ConfigI18n.locale',
                            'ConfigI18n.name',
                            'ConfigI18n.description',
                            	'ConfigI18n.default_value',
                            	'ConfigI18n.value',
                            	'ConfigI18n.options',
                           'Config.group_code',
                            'Config.subgroup_code',
                            'Config.code',
                            	'Config.type',
                            'Config.readonly',
                            'Config.section',
                            'Config.status',
                            'Config.orderby'),'order' => 'Config.id desc','conditions'=>$Config_conditions));
          foreach ($Config_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
               
                  	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
          $nameexl = $this->ld['shop_configs'].$this->ld['region_view'].$this->ld['export'].date('Ymd').'.xls';
          $this->Phpexcel->output($nameexl, $newdatas);
          die();
      }
}
