<?php

/**
 *这是一个名为 InformationResourcesController 的控制器
 *后台订单管理控制器.
 *
 *@var
 *@var
 *@var
 */
class InformationResourcesController extends AppController
{
    public $name = 'InformationResources';
    public $components = array('Phpexcel','Phpcsv','Pagination','RequestHandler');
    public $uses = array('InformationResource','InformationResourceI18n');
    public $helpers = array('Pagination');

    /**
     *查询资源表的数据.
     *
     *@param string code 
     */
    public function searchInforationresources()
    {
        $this->InformationResource->set_locale($this->locale);
        $info = $this->InformationResource->find('first', array('conditions' => array('InformationResource.code' => $_REQUEST['code']), 'fields' => 'InformationResource.id,InformationResource.code,InformationResourceI18n.name'));
        $this->set('name', $info['InformationResourceI18n']['name']);
        $this->set('parent_id', $info['InformationResource']['id']);
        //资源库信息
        $this->InformationResource->hasOne = array();
        $this->InformationResource->hasMany = array('InformationResourceI18n' => array('className' => 'InformationResourceI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'information_resource_id',
                        ),
                    );

        $informationresource_info = $this->InformationResource->all_information_formated($_REQUEST['code']);
        if (!empty($informationresource_info)) {
            $this->set('informationresource_info', $informationresource_info[$_REQUEST['code']]);
            $this->set('informationresource_id_info', $informationresource_info[$_REQUEST['code'].'_id_array']);
        }
        $this->set('code', $_REQUEST['code']);
        Configure::write('debug', 0);
        $this->layout = 'ajax';
    }
    /**
     *resource主页列表.
     *
     *呈现数据库表resource的数据
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function index($page = 1)
    {
        /*判断权限*/
        //$this->operator_privilege('resources_view');
        /*end*/
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/web_application/','sub' => '/information_resources/');
        $this->set('title_for_layout', $this->ld['information_resource_manage'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['information_resource_manage'],'url' => '/information_resources/');
        $conditions = array();
        if (isset($_REQUEST['keywords']) && $_REQUEST['keywords'] != '') {
            $conditions['and']['or']['InformationResourceI18n.name like'] = '%'.$_REQUEST['keywords'].'%';
            $conditions['and']['or']['InformationResource.code like'] = '%'.$_REQUEST['keywords'].'%';
            $conditions['and']['or']['InformationResource.information_value like'] = '%'.$_REQUEST['keywords'].'%';
            $this->set('keywords', $_REQUEST['keywords']);
        }
        if (isset($this->params['url']['system_code']) && $this->params['url']['system_code'] != '') {
	            $conditions['InformationResource.system_code'] = $this->params['url']['system_code'];
	            $this->set('system_code', $this->params['url']['system_code']);
	    }
	    if (isset($this->params['url']['module_code']) && $this->params['url']['module_code'] != '') {
	            $conditions['InformationResource.module_code'] = $this->params['url']['module_code'];
	            $this->set('module_code', $this->params['url']['module_code']);
	    }
        $this->InformationResource->set_locale($this->backend_locale);
        $cond = array();
        $cond['conditions'] = $conditions;
        $cond['order'] = 'InformationResource.created desc';
        $resource = $this->InformationResource->tree($cond);//取所有资源

        $total = sizeof($resource);
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'information_resources','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'InformationResource');
        $this->Pagination->init($cond, $parameters, $options);
        $start = ($page * $rownum) - $rownum;//当前页开始位置
        $resource = array_slice($resource, $start, $rownum);
        $this->set('resource', $resource);
                   $all_system_modules =         $this->System->modules(false);
            $all_systems=array_keys($all_system_modules);
            $this->set('all_system_modules', $all_system_modules);
            $this->set('all_systems', $all_systems);
    }

    /**
     *resource编辑.
     *
     *呈现数据库表resource的增加和更改
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function view($id = 0)
    {
        /*判断权限*/
        if ($id == 0) {
            //$this->operator_privilege('resources_add');
        } else {
            //$this->operator_privilege('resources_edit');
        }
        /*end*/
        $this->menu_path = array('root' => '/web_application/','sub' => '/information_resources/');
        $this->pageTitle = '编辑资源 - 信息资源管理'.' - '.$this->configs['shop_name'];
        $this->set('title_for_layout', $this->pageTitle);
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => '信息资源管理','url' => '/information_resources/');
        $this->set('navigations', $this->navigations);
        $userinformation_name = '';
        if ($this->RequestHandler->isPost()) {
            if ($id != 0) {
                $this->data['InformationResource']['orderby'] = !empty($this->data['InformationResource']['orderby']) ? $this->data['InformationResource']['orderby'] : 50;
                $this->InformationResource->save($this->data);
                foreach ($this->data['InformationResourceI18n'] as $v) {
                    $resourceI18n_info = array(
                                 'id' => isset($v['id']) ? $v['id'] : 'null',
                                   'locale' => $v['locale'],
                                   'information_resource_id' => $id ,
                                   'name' => isset($v['name']) ? $v['name'] : '',
                                'description' => isset($v['description']) ? $v['description'] : '',
                                'modified' => date('Y-m-d H:i:s'),
                 );
                    $this->InformationResourceI18n->saveAll(array('InformationResourceI18n' => $resourceI18n_info));//更新多语言
                }
                foreach ($this->data['InformationResourceI18n'] as $k => $v) {
                    if ($v['locale'] == $this->backend_locales) {
                        $userinformation_name = $v['name'];
                    }
                }
            /*
            //操作员日志
            if(isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1){
            $this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'编辑系统资源:'.$userinformation_name ,'operation');
            }
            */
        //	$this->flash("资源 ".$userinformation_name." 编辑成功。点击这里继续编辑该菜单。",'/Resources/view/'.$id,10);
            $this->redirect('/information_resources/');
            } else {
                $this->data['InformationResource']['orderby'] = !empty($this->data['InformationResource']['orderby']) ? $this->data['InformationResource']['orderby'] : 50;
                $this->InformationResource->saveAll(array('InformationResource' => $this->data['InformationResource']));
                $id = $this->InformationResource->getLastInsertId();
                foreach ($this->data['InformationResourceI18n'] as $k => $v) {
                    $v['information_resource_id'] = $id;
                    $this->InformationResourceI18n->saveAll(array('InformationResourceI18n' => $v));
                }
                foreach ($this->data['InformationResourceI18n'] as $k => $v) {
                    if ($v['locale'] == $this->backend_locales) {
                        $userinformation_name = $v['name'];
                    }
                }
            /*
            //操作员日志
            if(isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1){
            $this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'添加系统资源:'.$userinformation_name ,'operation');
            }
            */
        //	$this->flash("资源 ".$userinformation_name."  添加成功。点击这里继续编辑该资源。",'/resources/view/'.$id,10);
            $this->redirect('/information_resources/');
            }
        }
        $this->data = $this->InformationResource->localeformat($id);
        $this->InformationResource->set_locale($this->backend_locale);
        $parentmenu = $this->InformationResource->find('all', array('conditions' => array('InformationResource.parent_id' => '0')));
        $this->set('parentmenu', $parentmenu);
        if ($id != 0) {
            $this->navigations[] = array('name' => $this->data['InformationResourceI18n'][$this->backend_locale]['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => '添加资源','url' => '');
        }
        $this->set('navigations', $this->navigations);
            $all_system_modules =         $this->System->modules(false);
            $all_systems=array_keys($all_system_modules);
            $this->set('all_system_modules', $all_system_modules);
            $this->set('all_systems', $all_systems);
    }

    /**
     *删除资源表的数据.
     *
     *@param int id 
     */
    public function remove($id = 0)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        /*判断权限*/
        if (!$this->operator_privilege('resources_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        /*end*/
        $system_info = $this->InformationResource->findById($id);
        $res = $this->InformationResource->find('count', array('conditions' => array('InformationResource.parent_id' => $id)));
        $result = array();
        if ($res > 0) {
            //$this->re('删除失败，该资源还有子资源','/Resources/','');
            //$this->redirect('/resources/');
            $result['message'] = '删除失败，该资源还有子资源';
            $result['flag'] = 2;
        } else {
            $this->InformationResourceI18n->deleteAll(array('InformationResourceI18n.information_resource_id' => $id));
            $this->InformationResource->delete(array('InformationResource.id' => $id));
            //操作员日志
  //  	    if(isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1){
 //   	    $this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'删除系统资源:'.$system_info['SystemResourceI18n']['name'] ,'operation');
  //  	    }
            //$this->redirect('/resources/');
            $result['flag'] = 1;
        }
        die(json_encode($result));
    }
    
    
    function system_modified(){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result['flag'] = 0;
		$result['content'] = $this->ld['modify_failed'];
		$resource_id=isset($_POST['id'])?$_POST['id']:0;
		$system_code=isset($_POST['val'])?trim($_POST['val']):'';
		$this->InformationResource->save(array('id'=>$resource_id,'system_code'=>$system_code));
		$result['flag'] = 1;
		$result['content'] = $system_code;
		die(json_encode($result));
      }
      
      function module_modified(){
      	Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result['flag'] = 0;
		$result['content'] = $this->ld['modify_failed'];
		$resource_id=isset($_POST['id'])?$_POST['id']:0;
		$module_code=isset($_POST['val'])?trim($_POST['val']):'';
		$this->InformationResource->save(array('id'=>$resource_id,'module_code'=>$module_code));
		$result['flag'] = 1;
		$result['content'] = $module_code;
		die(json_encode($result));
      }

//批量删除
 public function batch_operations()
    {
    
        /*判断权限*/
        if (!$this->operator_privilege('resources_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        $user_checkboxes = $_REQUEST['checkboxes'];
        //pr($user_checkboxes);die();
        foreach ($user_checkboxes as $k => $v) {
        $ids_arr=$this->InformationResource->find('all',array('conditions'=>array('InformationResource.parent_id' => $v)));
        //pr($ids_arr);die();
        foreach($ids_arr as $kk =>$vv){
        	$this->InformationResource->delete(array('InformationResource.id' => $vv['InformationResource']['id']));
        	$this->InformationResourceI18n->deleteAll(array('InformationResourceI18n.information_resource_id' => $vv['InformationResource']['id']));
        }
            $this->InformationResource->delete(array('InformationResource.id' => $v));
            
        }
       
        $result['flag'] = 1;
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    
    
    /**
     *删除资源表的数据.
     *
     *@param int id 
     */
    public function removeInforationresources()
    {
        $informationresource_info = $this->InformationResourceI18n->find('first', array('conditions' => array('InformationResourceI18n.id' => $_REQUEST['id'])));
        if (!empty($informationresource_info)) {
            $parent_id = $informationresource_info['InformationResourceI18n']['information_resource_id'];
        }
        $this->InformationResource->deleteAll(array('InformationResource.id' => $parent_id));
        $this->InformationResourceI18n->deleteAll(array('InformationResourceI18n.information_resource_id' => $parent_id));
        $result['flag'] = 1;
        $result['msg'] = 'success';
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *编辑 新增 资源表的数据.
     *
     *@param int id 
     */
    public function editInforationresources()
    {
        if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
            $name = $_REQUEST['name'];
            $informationresource_info = $this->InformationResourceI18n->find('first', array('conditions' => array('InformationResourceI18n.id' => $_REQUEST['id'])));
            $informationresource_info['InformationResourceI18n']['name'] = $_REQUEST['name'];
            $this->InformationResourceI18n->save($informationresource_info);
        } else {
            $last_info = $this->InformationResource->find('first', array('conditions' => array('InformationResource.parent_id' => $_REQUEST['parent_id']), 'fields' => 'InformationResource.information_value', 'order' => 'InformationResource.information_value desc', 'limit' => 1));
            $informationresource_info = array();
            $informationresource_info['InformationResource']['parent_id'] = $_REQUEST['parent_id'];
            if (!empty($last_info)) {
                $informationresource_info['InformationResource']['information_value'] = $last_info['InformationResource']['information_value'] + 1;
            } else {
                $informationresource_info['InformationResource']['information_value'] = 1;
            }
            $this->InformationResource->save($informationresource_info);
            $id = $this->InformationResource->getLastInsertId();
            $resource_i18n_array = array();
            foreach ($this->front_locales as $k => $l) {
                if (isset($_REQUEST[$l['Language']['locale']]) && $_REQUEST[$l['Language']['locale']] != '') {
                    $resource_i18n_array[$k]['locale'] = $l['Language']['locale'];
                    $resource_i18n_array[$k]['name'] = $_REQUEST[$l['Language']['locale']];
                    $resource_i18n_array[$k]['information_resource_id'] = $id;
                }
            }
            if (!empty($resource_i18n_array)) {
                $this->InformationResourceI18n->saveAll($resource_i18n_array);
            }
        }
        $result['flag'] = 1;
        $result['msg'] = 'success';
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *获取最新的 资源表的数据.
     *
     *@param int id 
     */
    public function updateInformationresources()
    {
        $informationresource_info = $this->InformationResource->information_formated($_REQUEST['code'], $this->locale);
        if (!empty($informationresource_info) && !empty($informationresource_info[$_REQUEST['code']])) {
            $result['flag'] = 1;
            $result['data'] = $informationresource_info[$_REQUEST['code']];
        } else {
            $result['flag'] = 0;
            $result['msg'] = 'success';
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    
    
    //批量上传
     public function information_resource_upload()
    {
        $this->menu_path = array('root' => '/web_application/','sub' => '/information_resources/');
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['information_resource_manage'],'url' => '/information_resources/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
        $this->set('title_for_layout', $this->ld['information_resource_manage'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
    }
	
    public function information_resource_uploadpreview()
    {
        ////////////判断权限
        Configure::write('debug', 1);
        $success_num=0;
            if ($this->operator_privilege('resources_add')) {
                if (isset($_POST['sub1']) && $_POST['sub1'] == 1 && !empty($_FILES['file'])) {
                     $this->menu_path = array('root' => '/web_application/','sub' => '/information_resources/');
                   $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        			$this->navigations[] = array('name' => $this->ld['information_resource_manage'],'url' => '/information_resources/');
                    $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
                    $this->set('title_for_layout', $this->ld['information_resource_manage'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/information_resources/information_resource_upload';</script>";
                            die();
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                            $fields_array = array(
                            'InformationResource.parent_id',
         				'InformationResource.code',
         				'InformationResource.information_value',
         					'InformationResource.status',
         				'InformationResource.orderby','InformationResourceI18n.locale',
                            'InformationResourceI18n.name',
                            'InformationResourceI18n.description');

                            $fields = array(
                                $this->ld['parent_resource'],
                           	  $this->ld['resource_code'],
                           	  $this->ld['z_resource_value'],
                           	  $this->ld['status'],
                           	  $this->ld['sort_by'],$this->ld['s_language'],
                                $this->ld['z_name'],
                                $this->ld['z_description'] );
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
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/information_resources/information_resource_upload';</script>";
						die();
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = empty($v) ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/information_resources/information_resource_upload';</script>";
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
                    //	pr($this->data);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }

                        $InformationResource_list = $this->InformationResource->find('list', array('fields'=>array('code','id'),'conditions'=>array('InformationResource.parent_id '=>0),'order' => 'InformationResource.id desc'));
				//pr($InformationResource_list);
				$parent_id=isset($InformationResource_list[$v['InformationResource']['parent_id']])?$InformationResource_list[$v['InformationResource']['parent_id']]:0;
                        //pr($parent_id);die();
                        $InformationResource_condition='';
                        if(isset($parent_id)&&$parent_id!=''){
                       	 $InformationResource_condition['InformationResource.parent_id']=$parent_id;
                        }
                         if(isset($v['InformationResource']['code'])&&$v['InformationResource']['code']!=''){
                         	$InformationResource_condition['InformationResource.code']=$v['InformationResource']['code'];
                         }
                          if(isset($v['InformationResource']['information_value'])&&$v['InformationResource']['information_value']!=''){
                         	$InformationResource_condition['InformationResource.information_value']=$v['InformationResource']['information_value'];
                         }
                          if(isset($v['InformationResource']['status'])&&$v['InformationResource']['status']!=''){
                         	$InformationResource_condition['InformationResource.status']=$v['InformationResource']['status'];
                         }
                         //pr($InformationResource_condition);
                        $InformationResource_first = $this->InformationResource->find('first', array('conditions' =>$InformationResource_condition));
                        $v['InformationResource']['id']=isset($InformationResource_first['InformationResource']['id'])?$InformationResource_first['InformationResource']['id']:0;
                        //pr($v['InformationResource']['id']);
                        $v['InformationResource']['parent_id']=isset($parent_id)?$parent_id:0;
                        $v['InformationResource']['status']=isset($v['InformationResource']['status'])?$v['InformationResource']['status']:1;
                        $v['InformationResource']['orderby']=isset($v['InformationResource']['orderby'])?$v['InformationResource']['orderby']:50;
					if( $s1=$this->InformationResource->save($v['InformationResource']) ){
                        		$InformationResource_id=$this->InformationResource->id;
                        		}
                        $InformationResourceI18n_condition='';
                        if(isset($InformationResource_id)&&$InformationResource_id!=''){
                        	$InformationResourceI18n_condition['InformationResourceI18n.information_resource_id']=$InformationResource_id;
                        }
                         if(isset($v['InformationResourceI18n']['locale'])&&$v['InformationResourceI18n']['locale']!=''){
                        	$InformationResourceI18n_condition['InformationResourceI18n.locale']=$v['InformationResourceI18n']['locale'];
                        }
                        //pr($InformationResourceI18n_condition);//die();
                        $InformationResourceI18n_first = $this->InformationResourceI18n->find('first', array('conditions' => $InformationResourceI18n_condition));
                        $v['InformationResourceI18n']['id']=isset($InformationResourceI18n_first['InformationResourceI18n']['id'])?$InformationResourceI18n_first['InformationResourceI18n']['id']:0;
                        //pr($v['InformationResourceI18n']['id']);
                        $v['InformationResourceI18n']['information_resource_id']=isset($InformationResource_id)?$InformationResource_id:0;
				if(isset($v['InformationResourceI18n']['information_resource_id'])&&$v['InformationResourceI18n']['information_resource_id']!=''){
                        	$s2=$this->InformationResourceI18n->save($v['InformationResourceI18n']);
                		}
                		 if( isset($s1)&&!empty($s1)&&isset($s2)&&!empty($s2)){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                 }
                 //die();
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/information_resources/'</script>";
				die();
                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/information_resources/information_resource_upload/'</script>";
                }
            } ///////权限判断结束
    }
    //导出信息资源
      public function download_information_resource_csv_example()
      {
      	  Configure::write('debug', 1);
      	  $this->layout="ajax";
      	 $this->InformationResource->set_locale($this->backend_locale);
              //定义一个数组
         $fields_array = array(
                            'InformationResource.parent_id',
         				'InformationResource.code',
         				'InformationResource.information_value',
         					'InformationResource.status',
         				'InformationResource.orderby','InformationResourceI18n.locale',
                            'InformationResourceI18n.name',
                            'InformationResourceI18n.description');
$this->set('fields_array',$fields_array);
                            $fields = array(
                                $this->ld['parent_resource'],
                           	  $this->ld['resource_code'],
                           	  $this->ld['z_resource_value'],
                           	  $this->ld['z_availability'],
                           	  $this->ld['sort_by'],$this->ld['s_language'],
                                $this->ld['z_name'],
                                $this->ld['z_description'] );
                            $this->set('fields',$fields);
          $newdatas = array();
          $newdatas[] = $fields;
          //查询所有表里面所有信息 查询 10 条信息
          $InformationResource_info = $this->InformationResource->find('all', array('fields'=>array('InformationResourceI18n.locale',
                            'InformationResourceI18n.name',
                            'InformationResourceI18n.description',
                            'InformationResource.parent_id',
         				'InformationResource.code',
         				'InformationResource.information_value',
         					'InformationResource.status',
         				'InformationResource.orderby'),'order' => 'InformationResource.id desc', 'limit' => 10,'conditions'=>array('InformationResourceI18n.locale'=>'chi')));
          $InformationResource_list = $this->InformationResource->find('list', array('fields'=>array('id','code'),'conditions'=>array('InformationResource.parent_id '=>0),'order' => 'InformationResource.id desc'));
          foreach ($InformationResource_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  if($fields_ks[1]=='parent_id'){
                  	 $user_tmp[]= isset($InformationResource_list[$v['InformationResource']['parent_id']])?$InformationResource_list[$v['InformationResource']['parent_id']]:'';
                  }else{
                  	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
                  }
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
          $nameexl = $this->ld['information_resource_manage'].date('Ymd').'.csv';
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
      	  Configure::write('debug', 0);
      	  $this->layout="ajax";
      	  $this->InformationResource->set_locale($this->backend_locale);
              //定义一个数组
       $fields_array = array(
                            'InformationResource.parent_id',
         				'InformationResource.code',
         				'InformationResource.information_value',
         					'InformationResource.status',
         				'InformationResource.orderby','InformationResourceI18n.locale',
                            'InformationResourceI18n.name',
                            'InformationResourceI18n.description');

                            $fields = array(
                                $this->ld['parent_resource'],
                           	  $this->ld['resource_code'],
                           	  $this->ld['z_resource_value'],
                           	  $this->ld['z_availability'],
                           	  $this->ld['sort_by'],$this->ld['s_language'],
                                $this->ld['z_name'],
                                $this->ld['z_description'] );
          $newdatas = array();
          $newdatas[] = $fields;
          //查询所有表里面所有信息 
          $InformationResource_info = $this->InformationResource->find('all', array('fields'=>array('InformationResourceI18n.locale',
                            'InformationResourceI18n.name',
                            'InformationResourceI18n.description',
                            'InformationResource.parent_id',
         				'InformationResource.code',
         				'InformationResource.information_value',
         					'InformationResource.status',
         				'InformationResource.orderby'),'order' => 'InformationResource.id desc'));
          $InformationResourceI18n_list = $this->InformationResourceI18n->find('list', array('fields'=>array('information_resource_id','name'),'order' => 'InformationResourceI18n.id desc','conditions'=>array('InformationResourceI18n.information_resource_id <>'=>0)));

          foreach ($InformationResource_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  if($fields_ks[1]=='parent_id'){
                  	 $user_tmp[]= isset($InformationResourceI18n_list[$v['InformationResource']['parent_id']])?$InformationResourceI18n_list[$v['InformationResource']['parent_id']]:'';
                  }else{
                  	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
                  }
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
          $nameexl = $this->ld['information_resource_manage'].$this->ld['export'].date('Ymd').'.xls';
          $this->Phpexcel->output($nameexl, $newdatas);
          die();
      }
      
      //选择导出
      public function choice_export()
      {
      	  Configure::write('debug', 0);
      	  $this->layout="ajax";
      	  $this->InformationResource->set_locale($this->backend_locale);
      	  $user_checkboxes = $_REQUEST['checkboxes'];
              //定义一个数组
       $fields_array = array(
                            'InformationResource.parent_id',
         				'InformationResource.code',
         				'InformationResource.information_value',
         					'InformationResource.status',
         				'InformationResource.orderby','InformationResourceI18n.locale',
                            'InformationResourceI18n.name',
                            'InformationResourceI18n.description');

                            $fields = array(
                                $this->ld['parent_resource'],
                           	  $this->ld['resource_code'],
                           	  $this->ld['z_resource_value'],
                           	  $this->ld['z_availability'],
                           	  $this->ld['sort_by'],$this->ld['s_language'],
                                $this->ld['z_name'],
                                $this->ld['z_description'] );
          $newdatas = array();
          $newdatas[] = $fields;
          $InformationResource_conditions['AND']['InformationResource.status']=1; 
          $InformationResource_conditions['OR']['InformationResource.parent_id']=$user_checkboxes; 
          $InformationResource_conditions['OR']['InformationResource.id']=$user_checkboxes; 
          $InformationResource_info = $this->InformationResource->find('all', array('fields'=>array('InformationResourceI18n.locale',
                            'InformationResourceI18n.name',
                            'InformationResourceI18n.description',
                            'InformationResource.parent_id',
         				'InformationResource.code',
         				'InformationResource.information_value',
         					'InformationResource.status',
         				'InformationResource.orderby'),'order' => 'InformationResource.id desc','conditions'=>$InformationResource_conditions));
          $InformationResourceI18n_list = $this->InformationResourceI18n->find('list', array('fields'=>array('information_resource_id','name'),'order' => 'InformationResourceI18n.id desc','conditions'=>array('InformationResourceI18n.information_resource_id <>'=>0)));

          foreach ($InformationResource_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  if($fields_ks[1]=='parent_id'){
                  	 $user_tmp[]= isset($InformationResourceI18n_list[$v['InformationResource']['parent_id']])?$InformationResourceI18n_list[$v['InformationResource']['parent_id']]:'';
                  }else{
                  	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
                  }
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
          $nameexl = $this->ld['information_resource_manage'].$this->ld['export'].date('Ymd').'.xls';
          $this->Phpexcel->output($nameexl, $newdatas);
          die();
      }
    
    
    
}
