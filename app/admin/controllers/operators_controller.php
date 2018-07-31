<?php

/*****************************************************************************
 * Seevia 操作员管理
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
class OperatorsController extends AppController
{
    public $name = 'Operators';
    public $components = array('Phpexcel','Phpcsv','Captcha','Pagination','RequestHandler','Notify');
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor','Svshow');
    public $uses = array('Profile','ProfileFiled','Operator','Role','Language','OperatorLog','Action','NotifyTemplateType','OperatorChannelRelation','OperatorChannel');

    public function ajax_login()
    {
        Configure::write('debug', 1);
        $result['code'] = 0;
        //$result['message'] = "未知错误";
        if ($this->RequestHandler->isPost()) {
            $this->layout = 'ajax';
            $counts = $this->Cookie->read('count_login');
            // 判断验证码
            if ($counts >= 2 && $this->configs['admin_captcha'] == 1 && (!isset($_REQUEST['authnum']) || $this->captcha->check($_REQUEST['authnum']) == false)) {
                $result['message'] = $this->ld['verification_code_error.'].$_REQUEST['authnum'];
                $result['code'] = 100;
                $result['count_login'] = $counts;
                die(json_encode($result));
            }

            $operator = trim($_REQUEST['operator']);
            $operator_pwd = trim($_REQUEST['operator_pwd']);
            $operator = $this->Operator->findbyname($operator);
            // 判断账户是否存在
            if (!$operator) {
                $result['message'] = $this->ld['user_not_exist'];
                ++$counts;
                $this->set('count_login', $counts);
                $this->Cookie->write('count_login', $counts, false,600);
                $result['code'] = 101;
                $result['count_login'] = $counts;
                die(json_encode($result));
            }
            //判断登陆次数
            if ($counts >= 5) {
                $result['message'] = $this->ld['login_time_error'];
                $result['code'] = 102;
                $result['count_login'] = $counts;
                die(json_encode($result));
            }
            // 判断密码
            if ($operator_pwd != $operator['Operator']['password']) {
                ++$counts;
                $this->set('count_login', $counts);
                $this->Cookie->write('count_login', $counts, false,600);
                $result['message'] = $this->ld['password_error'];
                $result['code'] = 103;
                $result['count_login'] = $counts;
                die(json_encode($result));
            }
            // 判断状态
            if ($operator['Operator']['status'] != 1) {
                switch ($operator['Operator']['status']) {
                    case 0:
                    $result['message'] = $this->ld['account_number'].$this->ld['account_number_invalid_state'];
                    break;
                    case 2:
                    $result['message'] = $this->ld['account_number'].$this->ld['account_number_frozen'];
                    break;
                    case 3:
                    $result['message'] = $this->ld['account_number'].$this->ld['account_number_logged_out'];
                    break;
                }
                $result['code'] = 104;
                $result['count_login'] = $counts;
                die(json_encode($result));
            }
            // 登陆成功
                $this->Cookie->delete('count_login');
            $operator['Operator']['last_login_time'] = date('Y-m-d H:i:s');
            $operator['Operator']['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
            $operator['Operator']['session'] = session_id();
            $operator['Operator']['default_lang'] = isset($_REQUEST['locale'])&&$_REQUEST['locale']!=""?$_REQUEST['locale']:$this->backend_locale;
            $this->Operator->save($operator);//更新IP地址  和  登入时间
                //管理员管理权限
                if (isset($_REQUEST['cookie_session']) && $_REQUEST['cookie_session'] != '0') {
                    $this->Cookie->write('session', session_id(), false, '15 day');
                } else {
                    //$this->Cookie->delete('session');
                }
                if (isset($_SESSION['url']) && $_SESSION['url'] != '/pages/home') {
	                    $result['url'] = $_SESSION['url'];
	                    unset($_SESSION['url']);
                } else {
                           $result['url'] = '/pages/home';
                }
        }
        die(json_encode($result));
    }

    public function index($page = 1)
    {
        $this->operator_privilege('operators_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        //设置子菜单位置
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        //定义导航显示
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operators'],'url' => '/operators/');
        /*
         $operator_name = $this->Operator->find("first",array("conditions"=>array("Operator.id"=>1)));
         if($this->admin['name']==$operator_name['Operator']['name']){
         $this->set('operator_name',$operator_name['Operator']['name']);
         }*/
         $type_id = $this->Operator->find('first', array('fields' => array('Operator.type', 'Operator.type_id', 'Operator.id', 'Operator.actions'), 'conditions' => array('Operator.name' => $this->admin['name'])));
    //	$condition ="Operator.type=>".$type;
        $condition = array();
    //	$condition["Operator.type_id"]=$type_id['Operator']['type_id'];
        $_SESSION['type_id'] = $type_id['Operator']['type_id'];
        $_SESSION['id'] = $type_id['Operator']['id'];
        $_SESSION['type'] = $type_id['Operator']['type'];
        $_SESSION['actions'] = $type_id['Operator']['actions'];
        if ($_SESSION['type'] != 'S' && !isset($this->params['url']['type']) && !isset($this->params['url']['type_id'])) {
            $this->set('type', $_SESSION['type']);
            $this->set('type_id', $_SESSION['type_id']);
        }
        if (!empty($this->params['url']['type'])) {
            if ($this->params['url']['type'] == 'S') {
                $condition['Operator.type'] = $this->params['url']['type'];
                if (isset($condition['Operator.type_id'])) {
                    unset($condition['Operator.type_id']);
                }
            }
            if ($this->params['url']['type'] == 'D' && $this->params['url']['type_id'] != '0') {
                $condition['Operator.type'] = $this->params['url']['type'];
                $condition['Operator.type_id'] = $this->params['url']['type_id'];
                $this->set('type', $this->params['url']['type']);
                $this->set('type_id', $this->params['url']['type_id']);
            }
            if ($this->params['url']['type'] == 'D' && $this->params['url']['type_id'] == '0') {
                $condition['Operator.type'] = $this->params['url']['type'];
                $condition['Operator.type_id <'] = $this->params['url']['type_id'];
                $this->set('type', $this->params['url']['type']);
                $this->set('type_id', $this->params['url']['type_id']);
            }
        }
        if (!isset($this->params['url']['type']) || !isset($this->params['url']['type'])) {
            $condition['Operator.type'] = $_SESSION['type'];
            $condition['Operator.type_id'] = isset($_SESSION['type_id']) ? $_SESSION['type_id'] : 0;
        }
//		 if(isset($this->params['url']['type'])&&$this->params['url']['type']=="D"){
//		    	$condition["Operator.type"]=$this->params['url']['type'];
//		    	$condition["Operator.type_id"]=$this->params['url']['type_id'];
//		 }
        $total = $this->Operator->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'operators','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Operator');
        $this->Pagination->init($condition, $parameters, $options);
        $url = '?';
        if (isset($this->params['url']['type'])) {
            $url .= 'type='.$this->params['url']['type'];
        }
        if (isset($this->params['url']['type_id'])) {
            $url .= '&type_id='.$this->params['url']['type_id'];
        }
        $this->set('url', $url);
        $_SESSION['index_url'] = $url;
        $operator_data = $this->Operator->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'Operator.id'));
        $this->set('operator_data', $operator_data);
        $this->set('title_for_layout', $this->ld['operators'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_export', 'Profile.status' => 1)));
   	$this->set('profile_id',$profile_id);
    }

    public function view($id = 0)
    {
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        if (empty($id)) {
            $this->operator_privilege('operators_add');
        } else {
            $this->operator_privilege('operators_edit');
        }
        if (isset($_GET['type'])) {
            $this->set('type', $_GET['type']);
        } else {
            $this->set('type', '');
        }
        if (isset($_GET['type']) && $_GET['type'] == 'S') {
            $this->set('type', $_GET['type']);
        }
        if (isset($_GET['type']) && $_GET['type'] == 'D' && $_GET['type_id'] != 0) {
            $this->set('type', $_GET['type']);
            $this->set('view_type_id', $_GET['type_id']);
        } else {
            //if(isset($_GET['type'])&&isset($_GET['type_id'])){
                $this->set('type', isset($_GET['type']) ? $_GET['type'] : '');
            $this->set('view_type_id', isset($_GET['type_id']) ? $_GET['type_id'] : '');
        //	}
        }
        if (isset($_SESSION['type'])) {
            $this->set('view_type', $_SESSION['type']);
        }
        if (isset($_SESSION['type_id'])) {
            $this->set('type_id', $_SESSION['type_id']);
        }
        if (isset($_SESSION['actions'])) {
            $this->set('actions', $_SESSION['actions']);
        }
//		$operator_zhu_actions=$this->Operator->find("first",array("fields"=>array("Operator.actions"),"conditions"=>array("Operator.name"=>$this->admin['name'])));
//		if($operator_zhu_actions=="all"){
//			$this->set('operator_zhu_actions',$operator_zhu_actions);
//		}
        $this->set('title_for_layout', $this->ld['add_edit_operator'].'- '.$this->ld['operators'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operators'],'url' => '/operators/');
        $this->navigations[] = array('name' => $this->ld['add_edit_operator'],'url' => '');
        $this->set('operator_id',$id);
        //渠道
        $operator_source_relation_info = $this->OperatorChannelRelation->find('all',array('conditions'=>array('OperatorChannelRelation.relation_type'=>0,'OperatorChannelRelation.relation_type_id'=>$id)));
        //$this->set('operator_source_relation_info',$operator_source_relation_info);
        //pr($operator_source_relation_info);
        $cons = array();
        if(isset($operator_source_relation_info)&&sizeof($operator_source_relation_info)>0){
            foreach ($operator_source_relation_info as $k => $v) {
                $cons['and']['OperatorChannel.id'][] = $v['OperatorChannelRelation']['operator_channel_id'];
                $relation_info_check[$v['OperatorChannelRelation']['operator_channel_id']] = $v;
            }
            $this->set('relation_info_check',$relation_info_check);
        }
        $op_check=array();
        $operator_source_info = $this->OperatorChannel->find('all',array('conditions'=>$cons));
        foreach ($operator_source_info as $k => $v) {
            $op_check[$v['OperatorChannel']['id']] = $v['OperatorChannel']['name'];
        }
        $this->set('op_check',$op_check);

        if ($this->RequestHandler->isPost()) {
        	
        //获取渠道数据
        if(isset($_POST['channel_relation'])){
            //pr($_POST['channel_relation']);exit();
            $channel_relation = $_POST['channel_relation'];
            if(isset($channel_relation)&&sizeof($channel_relation)>0){
                foreach ($channel_relation as $k => $v) {
                    if(isset($relation_info_check[$k])){
                        $relation_info_check[$k]['OperatorChannelRelation']['value'] = $v;
                        $this->OperatorChannelRelation->save($relation_info_check[$k]);
                    }else{
                        $relation_info['id'] = 0;
                        $relation_info['operator_channel_id'] = $k;
                        $relation_info['relation_type'] = 0;
                        $relation_info['relation_type'] = $id;
                        $relation_info['value'] = $v;
                        $this->OperatorChannelRelation->save($relation_info);
                    }
                }
            }
        }
        //渠道结束
	if ($_SESSION['type_id'] == '0' && empty($this->data['Operator']['type_id'])) {
		$this->data['Operator']['type'] = 'S';
		$this->data['Operator']['type_id'] = '0';
	} elseif ($_SESSION['type_id'] != '0' && empty($this->data['Operator']['type_id'])) {
		$this->data['Operator']['type'] = 'D';
		$this->data['Operator']['type_id'] = $_SESSION['type_id'];
	}
    //		 if($svshow->operator_privilege('dealer_list')){
                if (isset($this->data['Operator']['type_id'])) {
                    if ($this->data['Operator']['type_id'] == '0' && $this->data['Operator']['type'] != 'D') {
                        $this->data['Operator']['type'] = 'S';
                        $this->data['Operator']['type_id'] = $this->data['Operator']['type_id'];
                    } else {
                        $this->data['Operator']['type'] = 'D';
                        $this->data['Operator']['type_id'] = $this->data['Operator']['type_id'];
                    }
                }
    //	   }
        $operator_info = $this->Operator->findbyid($this->data['Operator']['id']);
            $name = isset($this->data['Operator']['name']) ? $this->data['Operator']['name'] : $operator_info['Operator']['name'];
            if (empty($this->data['Operator']['id'])) {
                $operator_name_count = $this->Operator->find('count', array('conditions' => array('Operator.name' => $name)));
                if ($operator_name_count == 1) {
                    $result_code = 1;
                /*   echo "<script>alert('用户名重复')</script>";*/
                $this->redirect('/operators/view/0/');
                }
            } else {
                $operator_count = $this->Operator->find('first', array('conditions' => array('Operator.id' => $this->data['Operator']['id'])));
                $operator_name_count = $this->Operator->find('list', array('fields' => array('Operator.id', 'Operator.name')));
                if ($operator_count['Operator']['name'] != $name && in_array($name, $operator_name_count)) {
                    $result_code = 2;
                    $this->redirect('/operators/view/'.$this->data['Operator']['id']);
                }
            }
		if (!empty($this->params['form']['newpassword']) && !empty($this->params['form']['confirmpassword'])) {
			if (!empty($id) && strcmp($this->params['form']['newpassword'], $this->params['form']['confirmpassword']) != 0) {
			} else {
				$this->data['Operator']['password'] = $this->params['form']['newpassword'];
			}
		}
		if(isset($this->data['Operator']['password'])){
			$operator_password=$this->data['Operator']['password'];
			$operator_email=trim($this->data['Operator']['email']);
			if($operator_email!=''&&isset($this->data['Operator']['send_password_mail'])&&$this->data['Operator']['send_password_mail']=='1'){
				$this->operator_password_mail($operator_email,$operator_password);
			}
			$this->data['Operator']['password']=md5($this->data['Operator']['password']);
		}
            if (empty($result_code)) {
                $this->data['Operator']['orderby'] = !empty($this->data['Operator']['orderby']) ? $this->data['Operator']['orderby'] : '50';
            //权限
            if (isset($this->params['form']['operator_role']) && !empty($this->params['form']['operator_role'])) {
	            	  $this->params['form']['operator_role']=array_merge(array(''),$this->params['form']['operator_role'],array(''));
	                $this->data['Operator']['role_id'] = implode(';', $this->params['form']['operator_role']);
            }
                if (isset($this->params['form']['Action']) && !empty($this->params['form']['Action'])) {
				$form_actions=array_merge(array(''),$this->params['form']['Action'],array(''));
				$this->data['Operator']['actions'] = implode(';', $form_actions);
                }
                $this->data['Operator']['actions'] = isset($this->data['Operator']['actions']) ? $this->data['Operator']['actions'] : '';
                $this->data['Operator']['role_id'] = isset($this->data['Operator']['role_id']) ? $this->data['Operator']['role_id'] : 0;
                
		if(isset($_FILES['OperatorAvatar'])&&$_FILES['OperatorAvatar']['error']=='0'){
			$avatar_path=pathinfo($_FILES['OperatorAvatar']['name']);
			if(isset($avatar_path['extension'])&&$avatar_path['extension']!=''){
				$avatar_root=WWW_ROOT."media/Operator/";
				$this->mkdirs($avatar_root);
				$avatar_name=$this->data['Operator']['name'].".".$avatar_path['extension'];
				$avatar_file="/media/Operator/".$avatar_name;
				if(move_uploaded_file($_FILES['OperatorAvatar']['tmp_name'],$avatar_root.$avatar_name))$this->data['Operator']['avatar']=$avatar_file;
			}
		}
                if (isset($this->data['Operator']['id']) && $this->data['Operator']['id'] != '') {
                    $this->Operator->save(array('Operator' => $this->data['Operator'])); //关联保存
                } else {
                    $this->Operator->saveAll(array('Operator' => $this->data['Operator'])); //关联保存
                }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                if ($id == 0) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add'].$this->ld['operator'].':'.$this->data['Operator']['name'], $this->admin['id']);
                } else {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_operator'].':id '.$id.' '.$this->data['Operator']['name'], $this->admin['id']);
                }
            }
                $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
            }
        }
        $operator_data = $this->Operator->find('first', array('conditions' => array('id' => $id)));
        
        $operator_data['Operator']['action_arr'] = explode(';', $operator_data['Operator']['actions']);
        $this->set('operator_data', $operator_data);

        $this->set('id', $id);
        $this->Action->set_locale($this->backend_locale);
        $Action_Data=$this->Action->tree($this->backend_locale,$this->System->modules);
        $Actions=array();
        //应用判断//判断应用是否使用
        $all_infos = $this->apps;
        foreach($Action_Data as $k=>$v){
            $SubAction=isset($v['SubAction'])?$v['SubAction']:array();
            foreach($SubAction as $kk=>$vv){
                if(isset($vv['SubAction'])&&!empty($vv['SubAction'])){
                    $SubAction[$kk]['children']=$vv['SubAction'];
                    unset($SubAction[$kk]['SubAction']);
                }
            }
            if(!empty($SubAction)){
                $Action_Data[$k]['children']=$SubAction;
                unset($Action_Data[$k]['SubAction']);
            }
        }
        foreach($Action_Data as $k=>$v){
        	if(!isset($v['Action'])||!isset($v['ActionI18n'])){
        		unset($Action_Data[$k]);
        	}
        }
        $Actions=$Action_Data;
        $dealer_actions = array();
        $dealer_actions['product']['products_view']['products_mgt'] = true;
        $dealer_actions['order']['所有']['所有'] = true;
        $dealer_actions['system']['operators_view']['所有'] = true;
        $dealer_actions['ware']['所有']['所有'] = true;
        $dealer_actions['dealers']['dealer_view']['所有'] = true;
        $this->set('dealer_actions', $dealer_actions);
        $this->set('Actions', $Actions);
        //角色
        $this->Role->set_locale($this->locale);
        $res = $this->Role->find('all');
        $operator_roles = array();
        foreach ($res as $k => $v) {
            $operator_roles[$v['Role']['id']]['Role'] = $v['Role'];
            $operator_roles[$v['Role']['id']]['Role']['name'] = '';
            $operator_roles[$v['Role']['id']]['RoleI18n'][] = $v['RoleI18n'];
            if (!empty($operator_roles[$v['Role']['id']]['RoleI18n'])) {
                foreach ($operator_roles[$v['Role']['id']]['RoleI18n'] as $vv) {
                    $operator_roles[$v['Role']['id']]['Role']['name'] = $vv['name'];
                }
            }
        }
        $this->data = $this->Operator->find('first', array('conditions' => array('id' => $id)));
        $this->data['Operator']['role_arr'] = explode(';', $this->data['Operator']['role_id']);
        $this->set('operator_roles', $operator_roles);

        $template_list = $this->Template->find('list', array('conditions' => array('Template.status' => 1), 'fields' => 'Template.name'));
        $this->set('template_list', $template_list);


    }

    //列表状态修改
    public function toggle_on_status()
    {
        $this->Operators->hasMany = array();
        $this->Operators->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Operator->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_status'], $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_operator_failure'];
        $pn = $this->Operator->find('list', array('fields' => array('Operator.id', 'Operator.name'), 'conditions' => array('Operator.id' => $id)));
        $this->Operator->deleteAll(array('id' => $id));
        $this->OperatorChannelRelation->deleteAll(array("OperatorChannelRelation.relation_type_id"=>$id,"OperatorChannelRelation.relation_type"=>0));
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_operator'].':id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_operator_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function act_view($id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $name = $_POST['name'];
        $rname = '';
        $name_code = $this->Operator->find('all', array('fields' => 'Operator.name'));
        if (isset($name_code) && !empty($name_code)) {
            foreach ($name_code as $vv) {
                $rname[] = $vv['Operator']['name'];
            }
        } else {
            $result['code'] = '1';
        }
        if ($id == 0) {
            if (isset($name) && $name != '') {
                if (in_array($name, $rname)) {
                    $result['code'] = '0';
                    //   $result['msg'] = "用户名重复";
                } else {
                    $result['code'] = '1';
                }
            }
        } else {
            $operator_count = $this->Operator->find('first', array('conditions' => array('Operator.id' => $id)));
        //      $operator_name_count=$this->Operator->find("list",array("fields"=>array("Operator.id","Operator.name")));
            if ($operator_count['Operator']['name'] != $name && in_array($name, $rname)) {
                $result['code'] = '0';
                //   $result['msg'] = "用户名重复";
            } else {
                $result['code'] = '1';
            }
        }
        die(json_encode($result));
    }

    public function act_passview($id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $user_old_password = $_POST['user_old_password'];
        $operator_count = $this->Operator->find('first', array('conditions' => array('Operator.id' => $id)));
        if (!empty($operator_count['Operator']['password']) && strcmp(md5($user_old_password), $operator_count['Operator']['password']) != 0) {
            $result['code'] = '0';
            // $result['msg'] = "旧密码不正确";
        } else {
            $result['code'] = '1';
        }
        die(json_encode($result));
    }
//批量删除
    public function batch_operations()
    {
        $user_checkboxes = $_REQUEST['checkboxes'];
        foreach ($user_checkboxes as $k => $v) {
            $this->Operator->deleteAll(array('id' => $v));
            $this->OperatorChannelRelation->deleteAll(array("OperatorChannelRelation.relation_type_id"=>$v,"OperatorChannelRelation.relation_type"=>0));
        }
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_operator_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function produce_password()
    {
        $password = $_POST['password'];
        if ($password == '1') {
            // 随机生成 8 位数字或字母
            $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
            $code = '';
            for ($i = 0; $i < 8; ++$i) {
                $code .= $pattern{mt_rand(0, 61)};
            }
            $result['code'] = $code;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    
    //发送操作员密码修改邮件
    function operator_password_mail($operator_email,$operator_password){
		$this->NotifyTemplateType->set_locale($this->backend_locale);
		$totify_template=$this->NotifyTemplateType->typeformat('modified_operator_password','email');
		if(empty($totify_template))return false;
		$shop_name = $this->configs['shop_name'];
		$subject = $totify_template['email']['NotifyTemplateTypeI18n']['title'];
		@eval("\$subject = \"$subject\";");
		$html_body = $totify_template['email']['NotifyTemplateTypeI18n']['param01'];
		@eval("\$html_body = \"$html_body\";");
		$text_body = $totify_template['email']['NotifyTemplateTypeI18n']['param02'];
		@eval("\$text_body = \"$text_body\";");
		$mailsendqueue = array(
			'sender_name' => $shop_name,//发送从姓名
			'receiver_email' => $operator_email,//接收人姓名;接收人地址
			'cc_email' => ';',//抄送人
			'bcc_email' => ';',//暗送人
			'title' => $subject,//主题 
			'html_body' => $html_body,//内容
			'text_body' => $text_body,//内容
			'sendas' => 'html',
			'flag' => 0,
			'pri' => 0
             );
        	$this->Notify->send_email($mailsendqueue, $this->configs);
    }
    
//操作员管理上传
public function operator_upload(){
	  Configure::write('debug', 0);
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        /*end*/

        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operators'],'url' => '/operators/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
      $this->set('title_for_layout', $this->ld['bulk_upload'].' - '.$this->ld['operators'].' - '.$this->ld['manage_system'].' - '.$this->configs['shop_name']);
      $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_export', 'Profile.status' => 1)));
   	$this->set('profile_id',$profile_id);
    }



//操作员管理cvs查看
 public function operator_uploadpreview()
    {
    	Configure::write('debug', 1);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                    	$this->menu_path = array('root' => '/system/','sub' => '/operators/');
			        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
			        $this->navigations[] = array('name' => $this->ld['operators'],'url' => '/operators/');
			        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
			      $this->set('title_for_layout', $this->ld['bulk_upload'].' - '.$this->ld['operators'].' - '.$this->ld['manage_system'].' - '.$this->configs['shop_name']);
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/operators/operator_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_export', 'Profile.status' => 1)));
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
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/operators/operator_upload';</script>";
						die();
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/operators/operator_upload';</script>";
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
                        $code_array=array();
                        $code_array=explode(';',$v['Operator']['actions']);
                        foreach($code_array as $code_key=>$code_val){
                        		$Action_id_array[]=$this->Action->find('first',array('fields'=>array('Action.id'),'conditions'=>array('Action.code'=>$code_val)));
                        }
                        $Action_id_str='';
			        $role_array=array();
			        $role_array=explode(';',$v['Operator']['role_id']);
			        		$action_array=$this->Role->find('list',array('fields'=>array('Role.actions'),'conditions'=>array('Role.id'=>$role_array)));
			        	//pr($action_array);
			       
                        	foreach($action_array as $ids_val){
                        		$Action_id_str.=$ids_val.";";
                        	}
                        	//	pr($Action_id_str);die();
                        
                        $Operator_first = $this->Operator->find('first', array('conditions' => array('Operator.name' =>$v['Operator']['name'])));
                        $v['Operator']['id']=isset($Operator_first['Operator']['id'])?$Operator_first['Operator']['id']:0;
                        $v['Operator']['default_lang']=isset($v['Operator']['default_lang'])&&!empty($v['Operator']['default_lang'])?$v['Operator']['default_lang']:'chi';
                        $v['Operator']['template_code']=isset($v['Operator']['template_code'])&&!empty($v['Operator']['template_code'])?$v['Operator']['template_code']:'default';
                        $v['Operator']['time_zone']=isset($v['Operator']['time_zone'])&&!empty($v['Operator']['time_zone'])?$v['Operator']['time_zone']:'-8';
                        $v['Operator']['role_id']=isset($v['Operator']['role_id'])&&!empty($v['Operator']['role_id'])?$v['Operator']['role_id']:0;
                        $v['Operator']['log_flag']=isset($v['Operator']['log_flag'])&&!empty($v['Operator']['log_flag'])?$v['Operator']['log_flag']:1;
                        if(!empty($v['Operator']['actions'])){
                        	$v['Operator']['actions'].=$Action_id_str.";";
                        }else{
                        	$v['Operator']['actions']=$Action_id_str;
                        }
                        //pr($v['Operator']['actions']);die();
                        $v['Operator']['password']=md5($v['Operator']['password']);
                        
                         $s1=$this->Operator->save($v['Operator']);
                        
                        	 if( isset($s1)&&!empty($s1)){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                    }
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/operators/operator_upload/'</script>";
		            die();
                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/operators/operator_upload/'</script>";
                    	
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



		 
//操作员管理csv
public function download_operator_csv_example($out_type = 'Operator'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_export', 'Profile.status' => 1)));
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
          $Operator_info = $this->Operator->find('all', array('order' => 'Operator.id desc','limit'=>10));
	//	pr($Role_info);die();
         
            
              //循环数组
              foreach($Operator_info as $k=>$v){
              	  $user_tmp = array();
              	  if($v['Operator']['actions']!='all'){
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                  if($fields_ks[1]=='actions'){
	                   
		                  	$action_array=explode(';',$v['Operator']['actions']);
		                  	foreach($action_array as $ac_val){
		                  		$Action_code_array[]=$this->Action->find('first',array('fields'=>array('Action.code'),'conditions'=>array('Action.id'=>$ac_val)));
		                  	}
		                  	$code_str='';
		                  	foreach($Action_code_array as $code_key=>$code_val){
		                  		$code_str.=$code_val['Action']['code'].";";
		                  	}
		                  	$user_tmp[] =$code_str;
		                
	                  }else{
	                 	 	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
	                  }
	                   }
	                 // pr($Action_code_array);die();
	                 // pr($action_array);die();
	              }
	              //pr($user_tmp);die();
	              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}
//全部导出xls
public function all_export_csv($out_type = 'Operator'){
	Configure::write('debug',0);
     $this->layout="ajax";
     $this->Operator->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_export', 'Profile.status' => 1)));
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
  	$keys1 = array_search('操作员密码', $tmp);
    	array_splice($tmp, $keys1, 1);
  	$keys2 = array_search('Operator.password', $fields_array);
    	array_splice($fields_array, $keys2, 1);
    	//	pr($tmp);die();
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $Operator_info = $this->Operator->find('all', array('fields'=>array('Operator.name','Operator.session','Operator.email','Operator.mobile','Operator.type','Operator.role_id','Operator.actions','Operator.default_lang','Operator.template_code','Operator.status','Operator.time_zone'),'order' => 'Operator.id desc'));
		//pr($Operator_info);die();
         
            
              //循环数组
              foreach($Operator_info as $k=>$v){
              	  $user_tmp = array();
              	  if($v['Operator']['actions']!='all'){
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                  if($fields_ks[1]=='actions'){
	                   
		                  	$action_array=explode(';',$v['Operator']['actions']);
		                  	foreach($action_array as $ac_val){
		                  		$Action_code_array[]=$this->Action->find('first',array('fields'=>array('Action.code'),'conditions'=>array('Action.id'=>$ac_val)));
		                  	}
		                  	$code_str='';
		                  	foreach($Action_code_array as $code_key=>$code_val){
		                  		$code_str.=$code_val['Action']['code'].";";
		                  	}
		                  	$user_tmp[] =$code_str;
		                
	                  }else{
	                  	  
	                  
	                 	 	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
	                  }
	                   }
	                 // pr($Action_code_array);die();
	                 // pr($action_array);die();
	              }
	              //pr($user_tmp);die();
	              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
        	exit;

}  

//选择导出xls
public function choice_export($out_type = 'Operator'){
Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->Operator->set_locale($this->backend_locale);
$user_checkboxes = $_REQUEST['checkboxes'];
//pr($user_checkboxes);die();
     
 
       $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_export', 'Profile.status' => 1)));
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
  		$keys1 = array_search('操作员密码', $tmp);
    	array_splice($tmp, $keys1, 1);
  	$keys2 = array_search('Operator.password', $fields_array);
    	array_splice($fields_array, $keys2, 1);
  //	pr($tmp);
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          
          $Operator_info = $this->Operator->find('all', array('fields'=>array('Operator.name','Operator.password','Operator.session','Operator.email','Operator.mobile','Operator.type','Operator.role_id','Operator.actions','Operator.default_lang','Operator.template_code','Operator.status','Operator.time_zone'),'order' => 'Operator.id desc','conditions'=>array('Operator.id'=>$user_checkboxes)));
	//	pr($Operator_info);die();
         
            
              //循环数组
              foreach($Operator_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                  if($fields_ks[1]=='actions'){
	                   
		                  	$action_array=explode(';',$v['Operator']['actions']);
		                  	foreach($action_array as $ac_val){
		                  		$Action_code_array[]=$this->Action->find('first',array('fields'=>array('Action.code'),'conditions'=>array('Action.id'=>$ac_val)));
		                  	}
		                  	$code_str='';
		                  	foreach($Action_code_array as $code_key=>$code_val){
		                  		$code_str.=$code_val['Action']['code'].";";
		                  	}
		                  	$user_tmp[] =$code_str;
		                
	                  }else{
	                 	 	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
	                  }
	                   }
	                 // pr($Action_code_array);die();
	                 // pr($action_array);die();
	              //pr($user_tmp);die();
	              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
        	exit;




}
    
    public function add_users(){
        Configure::write('debug',1);
        $this->layout='ajax';
        $result = array();
        $result['code'] = 0;
        $result['message']='';
        if ($this->RequestHandler->isPost()) {
            $email = isset($_POST['email'])?$_POST['email']:'';
            $mobile = isset($_POST['mobile'])?$_POST['mobile']:'';
            $department_id= $this ->OperatorChannelRelation->find('first',array('conditions'=>array("OperatorChannelRelation.operator_channel_id"=>$_POST['department_id'],"OperatorChannelRelation.relation_type"=>1)));
            $data_1 = array(
                'Operator'=>array(
                    'id'=>0,
                    'name'=>$_POST['name'],
                    'status'=>1,
                    'email'=>$email,
                    'mobile'=>$mobile,
                    'department_id'=>$department_id['OperatorChannelRelation']['relation_type_id']
                    )
                );
            $operator_list = $this->Operator->find('all',array('conditions'=>array('Operator.name'=>$_POST['name'])));
            if(sizeof($operator_list)>0){
                $result['message']='该员工已存在!';
            }else{
                $this->Operator->save($data_1);
                $relation_type_id = $this->Operator->find('first',array('conditions'=>array('Operator.name'=>$_POST['name'])));
                $data_2 = array(
                'OperatorChannelRelation'=>array(
                    'id'=>0,
                    'operator_channel_id'=>$_POST['operator_channel_id'],
                    'relation_type'=>0,
                    'relation_type_id'=>$relation_type_id['Operator']['id'],
                    'value'=>$_POST['value']
                    )
                );
                $this->OperatorChannelRelation->save($data_2);
                $result['message']='导入成功！';
            }
            $result['code'] = 1;
        }
        die(json_encode($result));
    }

    public function save_users(){
        Configure::write('debug',1);
        $this->layout='ajax';
        $result = array();
        $result['code'] = 0;
        $result['message']='';
        if ($this->RequestHandler->isPost()) {
            $data = array(
                'Operator'=>array(
                    'id'=>$_POST['id'],
                    'email'=>$_POST['email'],
                    'mobile'=>$_POST['mobile']
                    )
                );
            $this->Operator->save($data);
            $result['code'] = 1;
        }
        die(json_encode($result));
    }
}
