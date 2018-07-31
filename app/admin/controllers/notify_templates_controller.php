<?php

/*****************************************************************************
 * svsys 通知模板
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
class NotifyTemplatesController extends AppController
{
    public $name = 'NotifyTemplates';
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    public $components = array('Phpexcel','Phpcsv','Pagination','RequestHandler','Notify');
    public $uses = array('Profile','ProfileFiled','NotifyTemplate','NotifyTemplateType','NotifyTemplateTypeI18n','OperatorLog','Resource','OpenModel','OpenUser','OpenUser','Config','ConfigI18n');

    public function index($page = 1){
        $this->operator_privilege('notify_template_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/system/','sub' => '/notify_templates/');
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['notify_template'],'url' => '/notify_templates/');
        
        $shop_name = $this->configs['shop_name'];
        $condition=array();
        if (isset($this->params['url']['system_code']) && $this->params['url']['system_code'] != '') {
	            $condition['NotifyTemplate.system_code'] = $this->params['url']['system_code'];
	            $this->set('system_code', $this->params['url']['system_code']);
        }
        if (isset($this->params['url']['module_code']) && $this->params['url']['module_code'] != '') {
	            $condition['NotifyTemplate.module_code'] = $this->params['url']['module_code'];
	            $this->set('module_code', $this->params['url']['module_code']);
        }
        if (isset($_GET['page']) && $_GET['page'] != '') {
            	$page = $_GET['page'];
        }
        $total = $this->NotifyTemplate->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'notify_templates','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'NotifyTemplate');
        $this->Pagination->init($condition, $parameters, $options);
        $notify_template_list = $this->NotifyTemplate->find('all', array('page' => $page, 'limit' => $rownum, 'conditions' => $condition,"order"=>'NotifyTemplate.id'));
        $this->set('notify_template_list', $notify_template_list);
        
        $this->set('title_for_layout', $this->ld['notify_template'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
            $all_system_modules =         $this->System->modules(false);
            $all_systems=array_keys($all_system_modules);
            $this->set('all_system_modules', $all_system_modules);
            $this->set('all_systems', $all_systems);
    }

    public function view($id = 0){
        $this->operator_privilege('notify_template_add');
        $this->menu_path = array('root' => '/system/','sub' => '/notify_templates/');
        $this->set('title_for_layout', $this->ld['add_edit'].' - '.$this->ld['notify_template'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['notify_template'],'url' => '/notify_templates/');
        
        if ($this->RequestHandler->isPost()) {
        	if(isset($this->data['NotifyTemplate'])){
        		$this->NotifyTemplate->save($this->data['NotifyTemplate']);
        		$template_code=$this->data['NotifyTemplate']['code'];
			//操作员日志
			if ($this->configs['operactions-log'] == 1) {
				$this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].$this->ld['notify_template'].":".$template_code,$this->admin['id']);
			}
        	}
		$back_url = $this->operation_return_url();//获取操作返回页面地址
		$this->redirect($back_url);
        }
        $this->data = $this->NotifyTemplate->findbyId($id);
        if (isset($this->data['NotifyTemplate']['code'])) {
            	$this->navigations[] = array('name' => $this->ld['edit'].' - '.$this->data['NotifyTemplate']['code'],'url' => '');
            	$notify_template_code=$this->data['NotifyTemplate']['code'];
            	$this->NotifyTemplateType->set_locale($this->backend_locale);
            	$notify_template_type_list=$this->NotifyTemplateType->find('all',array('conditions'=>array('NotifyTemplateType.notify_template_code'=>$notify_template_code),"order"=>"NotifyTemplateType.id"));
            	$this->set('notify_template_type_list', $notify_template_type_list);
        } else {
            	$this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        }
        $Resource_info = $this->Resource->getformatcode(array('notity_type'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        
        $open_type = $this->OpenModel->find('all', array('conditions' => array('OpenModel.status' => 1,'OpenModel.verify_status' => 1)));
        $this->set('open_type', $open_type);
             
            $all_system_modules =         $this->System->modules(false);
            $all_systems=array_keys($all_system_modules);
            $this->set('all_system_modules', $all_system_modules);
            $this->set('all_systems', $all_systems);
    }
    
	function system_modified(){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result['flag'] = 0;
		$result['content'] = $this->ld['modify_failed'];
		$template_id=isset($_POST['id'])?$_POST['id']:0;
		$system_code=isset($_POST['val'])?trim($_POST['val']):'';
		$this->NotifyTemplate->save(array('id'=>$template_id,'system_code'=>$system_code));
		$result['flag'] = 1;
		$result['content'] = $system_code;
		die(json_encode($result));
	}
	
	function module_modified(){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result['flag'] = 0;
		$result['content'] = $this->ld['modify_failed'];
		$template_id=isset($_POST['id'])?$_POST['id']:0;
		$module_code=isset($_POST['val'])?trim($_POST['val']):'';
		$this->NotifyTemplate->save(array('id'=>$template_id,'module_code'=>$module_code));
		$result['flag'] = 1;
		$result['content'] = $module_code;
		die(json_encode($result));
	}
    
    function ajax_check_template_code(){
    		Configure::write('debug',0);
        	$this->layout = 'ajax';
        	$result=array();
        	$result['code']='1';
        	$result['message']='';
        	$template_id=isset($_POST['template_id'])?$_POST['template_id']:0;
        	$template_code=isset($_POST['template_code'])?trim($_POST['template_code']):"";
        	$template_total=$this->NotifyTemplate->find('count',array('conditions'=>array('NotifyTemplate.code'=>$template_code,'NotifyTemplate.id <>'=>$template_id)));
        	if($template_total>0){
        		$result['code']='0';
        		$result['message']=$this->ld['code_already_exists'];
        	}
        	die(json_encode($result));
    }
    
    function ajax_notify_template_type($id=0){
    		Configure::write('debug', 1);
        	$this->layout = 'ajax';
        	$result=array();
        	$result['code']='0';
        	$result['message']='';
        	if ($this->RequestHandler->isPost()){
        		if(isset($this->data['NotifyTemplateType'])){
        			$template_code=isset($_POST['template_code'])?trim($_POST['template_code']):$this->data['NotifyTemplateType']['notify_template_code'];
        			$template_type_id=isset($this->data['NotifyTemplateType']['id'])?intval($this->data['NotifyTemplateType']['id']):0;
        			$template_type=$this->data['NotifyTemplateType']['type'];
        			$template_type_info=$this->NotifyTemplateType->find('count',array('conditions'=>array('NotifyTemplateType.type'=>$template_type,"NotifyTemplateType.notify_template_code"=>$template_code,'NotifyTemplateType.id <>'=>$template_type_id)));
        			if($template_type_info>0){
        				$result['message']=$template_code." - ".$template_type." ".$this->ld['this_option_already_exists'];
        			}else{
	        			$this->data['NotifyTemplateType']['notify_template_code']=$template_code;
		        		$this->NotifyTemplateType->save($this->data['NotifyTemplateType']);
		        		$notify_template_type_id=$this->NotifyTemplateType->id;
		        		if(isset($this->data['NotifyTemplateTypeI18n'])){
						foreach ($this->data['NotifyTemplateTypeI18n'] as $v) {
							$v['notify_template_type_id'] = $notify_template_type_id;
							$this->NotifyTemplateTypeI18n->saveAll(array('NotifyTemplateTypeI18n' => $v));//更新多语言
						}
		        		}
					//操作员日志
					if ($this->configs['operactions-log'] == 1) {
						$this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].$this->ld['notify_template'].":".$template_code."/".$template_type,$this->admin['id']);
					}
					$result['code']='1';
					if($template_type_id>0){
        					$result['message']=$this->ld['modified_successfully'];
        				}else{
        					$result['message']=$this->ld['add_successful'];
        				}
				}
	        	}
        		die(json_encode($result));
        	}
        	$notify_template_type_data=$this->NotifyTemplateType->localeformat($id);
        	if(!empty($notify_template_type_data)){
        		$result['code']='1';
        		$result['data']=$notify_template_type_data;
        		$result['backend_locales']=$this->backend_locales;
        	}
        	die(json_encode($result));
    }
    
    public function notify_config($subgroup_code=''){
		$this->operator_privilege('configvalues_view');
		$this->menu_path = array('root' => '/system/','sub' => '/notify_templates/');
		$this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
		$this->navigations[] = array('name' => $this->ld['notify_template'],'url' => '/notify_templates/');
		if ($this->RequestHandler->isPost()) {
			if (!empty($this->data)) {
				foreach ($this->data as $vv) {
					$data = array();
					$vv['value'] = isset($vv['value']) ? $vv['value'] : 0;
					$data = $vv;
					$this->ConfigI18n->saveAll($data);
				}
			}
		}
		$Resource_info = $this->Resource->find('first', array('conditions' => array('Resource.code' => 'website_set', 'Resource.status' => 1)));
		if (!empty($Resource_info)) {
			$resource_cond=array();
			$resource_cond['Resource.parent_id'] = $Resource_info['Resource']['id'];
			$resource_cond['Resource.status'] = 1;
			$resource_cond['ResourceI18n.locale'] = $this->backend_locale;
			$Resource_list_info = $this->Resource->find('all', array('conditions' => $resource_cond, 'order' => 'orderby'));
			$resource_list = array();
			foreach ($Resource_list_info as $v) {
				$resource_list[$v['Resource']['code']] = $v['ResourceI18n']['name'];
			}
			if(!isset($resource_list[$subgroup_code]))$this->redirect('index');
			
			$this->navigations[] = array('name' => $resource_list[$subgroup_code],'url' => '');
			$this->set('title_for_layout', $resource_list[$subgroup_code].' - '.$this->ld['notify_template'].' - '.$this->configs['shop_name']);
			$this->Config->hasOne = array();
			$this->Config->hasMany = array('ConfigI18n' => array('className' => 'ConfigI18n',
				'conditions' => '',
					'order' => '',
					'dependent' => true,
					'foreignKey' => 'config_id'
				)
			);
			$conditions=array();
			$conditions['Config.group_code'] = 'website';
			$conditions['Config.subgroup_code'] = $subgroup_code;
			$conditions['Config.status'] = 1;
			$conditions['Config.readonly'] = 0;
			$configs = $this->Config->find('all', array('conditions' => $conditions, 'order' => 'Config.orderby,Config.group_code'));
			$config_group_list = array();
			$val = array();
			foreach ($configs as $k => $v) {
				$val['Config'] = $v['Config'];
				foreach ($v['ConfigI18n'] as $kk => $vv) {
					if ($vv['locale'] == $this->backend_locale) {
						$val['Config']['name'] = @$vv['name'];
					}
					$val['ConfigI18n'][$vv['locale']] = $vv;
					if ($v['Config']['type'] == 'radio' || $v['Config']['type'] == 'checkbox' || $v['Config']['type'] == 'image') {
						$val['ConfigI18n'][$vv['locale']]['options'] = explode("\n", $vv['options']);
					}
				}
				$config_groups[$v['Config']['subgroup_code']][] = $val;
			}
			$this->set('subgroup_code', $subgroup_code);
			$this->set('resource_list', $resource_list);
			$this->set('config_groups', $config_groups);
		}else{
			$this->redirect('index');
		}
    }
    
    /**
     *删除一个模板
     *
     *@param int $id 输入模板ID
     */
    public function remove($id)
    {
	    	 Configure::write('debug', 0);
	        $this->layout = 'ajax';
	        $result['flag'] = 2;
	        $result['message'] = $this->ld['delete_failure'];
	        $template_info=$this->NotifyTemplate->find('first', array('fields' => array('NotifyTemplate.id', 'NotifyTemplate.code'), 'conditions' => array('NotifyTemplate.id' => $id)));
	        if(!empty($template_info)){
		        $template_type_ids=$this->NotifyTemplateType->find('list',array('fields'=>"NotifyTemplateType.id","conditions"=>array("NotifyTemplateType.notify_template_code"=>$template_info['NotifyTemplate']['code'])));
		        if(!empty($template_type_ids)){
		        	$this->NotifyTemplateTypeI18n->deleteAll(array('NotifyTemplateTypeI18n.notify_template_type_id' => $template_type_ids));
		        	$this->NotifyTemplateType->deleteAll(array('NotifyTemplateType.id' => $template_type_ids));
		        }
		        $this->NotifyTemplate->deleteAll(array('NotifyTemplate.id' => $id));
		        //操作员日志
		        if ($this->configs['operactions-log'] == 1) {
		            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete'].$this->ld['notify_template'].':'.$template_info['NotifyTemplate']['code'], $this->admin['id']);
		        }
		        $result['flag'] = 1;
		        $result['message'] = $this->ld['deleted_success'];
	        }
	        die(json_encode($result));
    }

    /*
        批量删除
    */
    public function batch_remove()
    {
	    	 Configure::write('debug', 0);
	        $this->layout = 'ajax';
	        $result['flag'] = 2;
	        $result['message'] = $this->ld['delete_failure'];
	        $template_checkboxes = isset($_REQUEST['checkboxes'])?$_REQUEST['checkboxes']:array();
	        $template_codes=$this->NotifyTemplate->find('list', array('fields' => array('NotifyTemplate.id', 'NotifyTemplate.code'), 'conditions' => array('NotifyTemplate.id' => $template_checkboxes)));
	        if(!empty($template_codes)){
	        	$template_type_ids=$this->NotifyTemplateType->find('list',array('fields'=>"NotifyTemplateType.id","conditions"=>array("NotifyTemplateType.notify_template_code"=>$template_codes)));
	        	if(!empty($template_type_ids)){
		        	$this->NotifyTemplateTypeI18n->deleteAll(array('NotifyTemplateTypeI18n.notify_template_type_id' => $template_type_ids));
		        	$this->NotifyTemplateType->deleteAll(array('NotifyTemplateType.id' => $template_type_ids));
		        }
		        $this->NotifyTemplate->deleteAll(array('NotifyTemplate.id' => $template_checkboxes));
		        $template_codes_txt=implode(";",$template_codes);
		        //操作员日志
		        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
		            	$this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete'].$this->ld['notify_template'].':'.$template_codes_txt, $this->admin['id']);
		        }
		        $result['flag'] = 1;
			 $result['message'] = $this->ld['deleted_success'];
	        }
	        die(json_encode($result));
    }
    
    function ajax_notify_template_type_remove($id=0){
    		 Configure::write('debug', 1);
	        $this->layout = 'ajax';
	        $result['flag'] = 2;
	        $result['message'] = $this->ld['delete_failure'];
	        $template_type_ids=$this->NotifyTemplateType->find('first', array('fields' => array('NotifyTemplateType.id', 'NotifyTemplateType.notify_template_code','NotifyTemplateType.type'), 'conditions' => array('NotifyTemplateType.id' => $id)));
	        if(!empty($template_type_ids)){
        		$this->NotifyTemplateTypeI18n->deleteAll(array('NotifyTemplateTypeI18n.notify_template_type_id' => $id));
        		$this->NotifyTemplateType->deleteAll(array('NotifyTemplateType.id' => $id));
		        //操作员日志
		        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
		            		$this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete'].$this->ld['notify_template'].':'.$template_type_ids['NotifyTemplateType']['notify_template_code']."/".$template_type_ids['NotifyTemplateType']['type'], $this->admin['id']);
		        }
		        $result['flag'] = 1;
			 $result['message'] = $this->ld['deleted_success'];
	        }
	        die(json_encode($result));
    }
    
    function toggle_on_status(){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
		$val = isset($_REQUEST['val'])?$_REQUEST['val']:'';
		$result = array();
		$result['flag'] = 0;
		$result['content'] = stripslashes($val);
		$template_info=$this->NotifyTemplate->find('first', array('fields' => array('NotifyTemplate.id', 'NotifyTemplate.code'), 'conditions' => array('NotifyTemplate.id' => $id)));
        	if (!empty($template_info)&&is_numeric($val) && $this->NotifyTemplate->save(array('id' => $id, 'status' => $val))){
			$result['flag'] = 1;
			$result['content'] = stripslashes($val);
			if ($this->configs['operactions-log'] == 1) {
				$this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].$this->ld['notify_template'].':'.$template_info['NotifyTemplate']['code'].",".$this->ld['status'].":".$val, $this->admin['id']);
			}
		}
		die(json_encode($result));
    }
    
    function ajax_notify_template_type_status(){
    		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
		$val = isset($_REQUEST['val'])?$_REQUEST['val']:'';
		$result = array();
		$result['flag'] = 0;
		$result['content'] = stripslashes($val);
		$template_type_data=$this->NotifyTemplateType->find('first', array('fields' => array('NotifyTemplateType.id', 'NotifyTemplateType.notify_template_code','NotifyTemplateType.type'), 'conditions' => array('NotifyTemplateType.id' => $id)));
        	if (!empty($template_type_data)&&is_numeric($val) && $this->NotifyTemplateType->save(array('id' => $id, 'status' => $val))){
			$result['flag'] = 1;
			$result['content'] = stripslashes($val);
			if ($this->configs['operactions-log'] == 1) {
				$this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].$this->ld['notify_template'].':'.$template_type_data['NotifyTemplateType']['notify_template_code'].'/'.$template_type_data['NotifyTemplateType']['type'].",".$this->ld['status'].":".$val, $this->admin['id']);
			}
		}
		die(json_encode($result));
    }
    
    function wechat_template(){
    		$this->operator_privilege('mailtemplates_view');
    		$this->operation_return_url(true);//设置操作返回页面地址
		$this->menu_path = array('root' => '/system/','sub' => '/notify_templates/');
		$this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
		$this->navigations[] = array('name' => $this->ld['notify_template'],'url' => '/notify_templates/');
		$this->navigations[] = array('name' => $this->ld['wechat'].$this->ld['templates'],'url' => '');
		$this->set('title_for_layout', $this->ld['wechat'].$this->ld['templates']);
		
		$open_type = $this->OpenModel->find('all', array('conditions' => array('OpenModel.status' => 1,'OpenModel.verify_status' => 1)));
        	$this->set('open_type', $open_type);
        	
        	$open_type_ids=array();
        	$open_type_data=array();
        	foreach($open_type as $v){
        		$open_type_ids[]=$v['OpenModel']['id'];
        		$open_type_data[$v['OpenModel']['id']]=$v;
        	}
		$open_type_id=isset($open_type[0]['OpenModel'])?$open_type[0]['OpenModel']['id']:0;
		if(isset($_REQUEST['open_type_id'])&&in_array($_REQUEST['open_type_id'],$open_type_ids)){
			$open_type_id=$_REQUEST['open_type_id'];
		}
		$this->set('open_type_id',$open_type_id);
		$openmodelinfo = isset($open_type_data[$open_type_id])?$open_type_data[$open_type_id]:array();
		$template_list=array();
		if(!empty($openmodelinfo)){
			if (!$this->OpenModel->validateToken($openmodelinfo)) {
				$openType = empty($openmodelinfo['OpenModel']['open_type']) ? '' : $openmodelinfo['OpenModel']['open_type'];
				$appId = $openmodelinfo['OpenModel']['app_id'];
				$appSecret = $openmodelinfo['OpenModel']['app_secret']; 
				//无效重新获取
				$accessToken = $this->OpenModel->getAccessToken($appId, $appSecret, $openType);
				$openmodelinfo['OpenModel']['token'] = $accessToken;
				$this->OpenModel->save($openmodelinfo);
	             }
	             $access_token = $openmodelinfo['OpenModel']['token'];
			$request_url="https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=".$access_token;
			$wechat_result=$this->https_request($request_url);
			$template_list=isset($wechat_result['template_list'])?$wechat_result['template_list']:array();
		}
		$this->set('template_list',$template_list);
    }
    
    function debugging($template_code=''){
		$this->operator_privilege('mailtemplates_view');
		$this->operation_return_url(true);//设置操作返回页面地址
		$this->menu_path = array('root' => '/system/','sub' => '/notify_templates/');
		$this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
		$this->navigations[] = array('name' => $this->ld['notify_template'],'url' => '/notify_templates/');
		$this->navigations[] = array('name' => $this->ld['send_and_test'],'url' => '');
		$template_info=$this->NotifyTemplate->find('first',array('conditions'=>array('NotifyTemplate.code'=>$template_code)));
		if($template_code==''||empty($template_info)){
			$this->redirect('index');
		}
		$this->navigations[] = array('name' =>($template_info['NotifyTemplate']['description']==''?$template_code:$template_info['NotifyTemplate']['description']),'url' => '/notify_templates/view/'.$template_info['NotifyTemplate']['id']);
		$this->NotifyTemplateType->set_locale($this->backend_locale);
            	$notify_template_type_list=$this->NotifyTemplateType->find('all',array('conditions'=>array('NotifyTemplateType.notify_template_code'=>$template_code,"NotifyTemplateType.status"=>'1'),"order"=>"NotifyTemplateType.id"));
            	$this->set('notify_template_type_list',$notify_template_type_list);
            	$this->set('template_code',$template_code);
            	
		if ($this->RequestHandler->isPost()){
			Configure::write('debug', 1);
			$this->layout = 'ajax';
			$result=array();
			$result['code']="0";
			$result['message']="Data Error";
			$error_message=array();
			$notify_template_type_data=array();
			foreach($notify_template_type_list as $v){
				$notify_template_type_data[$v['NotifyTemplateType']['type']]=$v;
			}
			if(isset($_POST['send_to'])&&!empty($_POST['send_to'])){
				$send_content_txt=isset($_POST['send_content'])?$_POST['send_content']:'';
				$send_content=split("\r\n",$send_content_txt);
				foreach($send_content as $v){
					if(trim($v)==""||!strpos($v,'='))continue;
					$send_content_arr=explode("=",$v);
					$send_content_key=$send_content_arr[0];
					unset($send_content_arr[0]);
					$send_content_value=implode("=",$send_content_arr);
					if(!strpos($v,'.DATA')){
						$$send_content_key=$send_content_value;
					}else{
						$send_content_key_arr=explode('.',$send_content_key);
						$send_content_key=$send_content_key_arr[0];
						$$send_content_key=$send_content_value;
					}
				}
				$wechat_params=array();
				if(isset($notify_template_type_data['wechat']['NotifyTemplateTypeI18n']['param04'])){
					$wechat_params_txt=$notify_template_type_data['wechat']['NotifyTemplateTypeI18n']['param04'];
					@eval("\$wechat_params_txt = \"$wechat_params_txt\";");
					$wechat_params_arr=explode("\r\n",$wechat_params_txt);
					foreach($wechat_params_arr as $v){
						if(trim($v)==""||!strpos($v,'='))continue;
						$send_content_arr=explode("=",$v);
						$send_content_key=$send_content_arr[0];
						unset($send_content_arr[0]);
						$send_content_value=implode("=",$send_content_arr);
						$send_content_key_arr=explode('.',$send_content_key);
						$send_content_key=$send_content_key_arr[0];
						$$send_content_key=$send_content_value;
						$wechat_params[$send_content_key]['value']=$send_content_value;
						$wechat_params[$send_content_key]['color']="#000000";
					}
				}
				$shop_name = $this->configs['shop_name'];
				foreach($_POST['send_to'] as $send_type=>$send_to){
					if(trim($send_to)==""||trim($send_to)=="0")continue;
					if($send_type=="email"){
						$email_title=$notify_template_type_data[$send_type]['NotifyTemplateTypeI18n']['title'];
						$html_body=$notify_template_type_data[$send_type]['NotifyTemplateTypeI18n']['param01'];
						$text_body=$notify_template_type_data[$send_type]['NotifyTemplateTypeI18n']['param02'];
						@eval("\$email_title = \"$email_title\";");
						@eval("\$html_body = \"$html_body\";");
						@eval("\$text_body = \"$text_body\";");
						$mail_send_queue = array(
	                                        'id' => '',
	                                        'sender_name' => $shop_name,
	                                        'receiver_email' => $send_to,
	                                        'cc_email' => ';',
	                                        'bcc_email' => ';',
	                                        'title' => $email_title,
	                                        'html_body' => $html_body,
	                                        'text_body' => $text_body,
	                                        'sendas' => 'html',
	                                        'flag' => 0,
	                                        'pri' => 0
	                                    );
	                    		$email_result=$this->Notify->send_email($mail_send_queue,$this->configs);
	                    		if(!$email_result){
	                    			$error_message[]=$send_to.":".$this->ld['send_mail_failed'];
	                    		}else{
	                    			$error_message[]=$send_to.":".$this->ld['send_success'];
	                    		}
					}else if($send_type=="sms"){
						$sms_content=$notify_template_type_data[$send_type]['NotifyTemplateTypeI18n']['param02'];
						@eval("\$sms_content = \"$sms_content\";");
						$sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
						$sms_result=$this->Notify->send_sms($send_to,$sms_content,$sms_kanal,$this->configs,false);
						if($sms_result['code']!='1'){
	                    			$error_message[]=$send_to.":".$this->ld['send_failed'].','.$sms_result['message'];
	                    		}else{
	                    			$error_message[]=$send_to.":".$this->ld['send_success'];
	                    		}
					}else if($send_type=="wechat"){
						$template_id=$notify_template_type_data[$send_type]['NotifyTemplateTypeI18n']['param03'];
						if(trim($template_id)=="")continue;
						$wechat_open_type_id=isset($_POST['wechat_open_type_id'])?$_POST['wechat_open_type_id']:"";
						$openmodelinfo = $this->OpenModel->find('first', array('conditions' => array('OpenModel.status' => 1,'OpenModel.verify_status' => 1,"OpenModel.open_type_id"=>$wechat_open_type_id)));
						if(empty($openmodelinfo))continue;
						if (!$this->OpenModel->validateToken($openmodelinfo)) {
							$openType = empty($openmodelinfo['OpenModel']['open_type']) ? '' : $openmodelinfo['OpenModel']['open_type'];
							$appId = $openmodelinfo['OpenModel']['app_id'];
							$appSecret = $openmodelinfo['OpenModel']['app_secret']; 
							//无效重新获取
							$accessToken = $this->OpenModel->getAccessToken($appId, $appSecret, $openType);
							$openmodelinfo['OpenModel']['token'] = $accessToken;
							$this->OpenModel->save($openmodelinfo);
				             }
				             $access_token = $openmodelinfo['OpenModel']['token'];
						$wechat_data=array(
							'touser'=>$send_to,
							'template_id'=>$template_id,
							'url'=>$this->server_host,
							'data'=>$wechat_params
						);
						$wechat_data=$this->to_josn($wechat_data);
						$request_url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
						$wechat_result=$this->https_request($request_url, $wechat_data);
						if(isset($wechat_result['errcode'])&&$wechat_result['errcode']!='0'){
							$error_message[]=$this->ld['wechat'].":".$this->ld['send_failed'].','.$wechat_result['errmsg'];
						}else{
	                    			$error_message[]=$this->ld['wechat'].":".$this->ld['send_success'];
	                    		}
					}
				}
				if(!empty($error_message)){
					$result['code']="1";
					$result['message']=implode(";",$error_message);
				}
			}
			die(json_encode($result));
		}
            $Resource_info = $this->Resource->getformatcode(array('notity_type'), $this->backend_locale);
        	$this->set('Resource_info', $Resource_info);
        	
        	$open_type = $this->OpenModel->find('all', array('conditions' => array('OpenModel.status' => 1,'OpenModel.verify_status' => 1)));
        	$this->set('open_type', $open_type);
        	
        	$this->set('title_for_layout', ($template_info['NotifyTemplate']['description']==''?$template_code:$template_info['NotifyTemplate']['description']).' - '.$this->configs['shop_name']);
    }
    
    function ajax_open_wechat_user(){
    		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result="";
		$open_type_id=isset($_REQUEST['open_type_id']) ? $_REQUEST['open_type_id'] : '';
		$condition=array(
			'OpenUser.open_type_id'=>$open_type_id
		);
		$open_user_list = $this->OpenUser->find('all', array("fields"=>"openid,nickname",'conditions' => $condition,'order' => 'OpenUser.created desc'));
		if(!empty($open_user_list)){
			$open_user_data=array();
			foreach($open_user_list  as $v){
				$v['OpenUser']['nickname']=urldecode($v['OpenUser']['nickname']);
				$result.=$v['OpenUser']['openid'].chr(13).chr(10).$v['OpenUser']['nickname'].chr(13).chr(10).chr(13).chr(10);
			}
		}
		die($result);
    }
    
    
    /*
        调用接口
    */
    public function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);

        return json_decode($output, true);
    }

    /*
        $data   需要转换josn提交的数据
    */
    public function to_josn($data)
    {
        $this->arrayRecursive($data, 'urlencode');
        $json = json_encode($data);

        return urldecode($json);
    }

    /************************************************************** 
    * 对数组中所有元素做处理,保留中文 
    * @param string &$array 要处理的数组
    * @param string $function 要执行的函数 
    * @return boolean $apply_to_keys_also 是否也应用到key上 
    * @access public 
    * 
    *************************************************************/
    public function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }
            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        --$recursive_counter;
    }
}
