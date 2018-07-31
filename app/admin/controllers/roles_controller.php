<?php

/*****************************************************************************
 * Seevia 角色管理
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
class RolesController extends AppController
{
    public $name = 'Roles';
    public $helpers = array('Html','Pagination');
    public $components = array('Phpexcel','Phpcsv','Pagination','RequestHandler','Email'); // Added
    public $uses = array('Profile','ProfileFiled','Role','RoleI18n','Operator','Action','ActionI18n','Application','Language','OperatorLog');

    public function index($page=1)
    {
        /*判断权限*/
        $this->operator_privilege('operator_roles_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        /*end*/

        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operator_roles'],'url' => '/roles/');
        $this->Role->set_locale($this->locale);
        $condition = '';
        //角色搜索筛选条件
        $role_name = '';
        if (isset($this->params['url']['role_name']) && !empty($this->params['url']['role_name'])) {
            $condition['RoleI18n.name like'] = '%'.$this->params['url']['role_name'].'%';
            $role_name = $this->params['url']['role_name'];
        }
        $total = $this->Role->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            	$page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'roles','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Role');
        $this->Pagination->init($condition, $parameters, $options);
        $res = $this->Role->find('all', array('conditions' => $condition, 'rownum' => $rownum, 'page' => $page, 'order' => 'Role.created DESC'));

        $roles = $this->Operator->find('all',array('conditions'=>array('Operator.status'=>'1')));
        $role_list = array();
        if (!empty($res) && sizeof($res) > 0) {
            $operactions_ids = array();
            foreach ($res as $k => $v) {
                $role_list[$v['Role']['id']]['Role'] = $v['Role'];
                if (is_array($v['RoleI18n'])) {
                    $role_list[$v['Role']['id']]['RoleI18n'] = $v['RoleI18n'];
                }
                $action_lists = explode(';', $v['Role']['actions']);
                if (!empty($action_lists) && sizeof($role_list) > 0) {
                    foreach ($action_lists as $kk => $vv) {
                        $operactions_ids[$vv] = $vv;
                    }
                }

                $i = 1;
                foreach ($roles as $key => $value) {
                    $role_id = $value['Operator']['role_id'];
                    $arr = explode(';', $role_id);
                    if (in_array($role_list[$v['Role']['id']]['Role']['id'], $arr)) {
                        ++$i;
                    }
                }
                $role_list[$v['Role']['id']]['Role']['number'] = $i;
            }

            $this->Action->set_locale($this->backend_locale);
            $actionInfos = $this->Action->find('all', array('conditions' => array('Action.id' => $operactions_ids)));
            if (!empty($actionInfos) && sizeof($actionInfos) > 0) {
                $actionlist = array();
                foreach ($actionInfos as $k => $v) {
                    $actionlist[$v['Action']['id']] = $v['ActionI18n']['name'];
                }

                foreach ($res as $k => $v) {
                    $action_lists = explode(';', $v['Role']['actions']);
                    $actiontxt = '';
                    if (!empty($action_lists) && sizeof($role_list) > 0) {
                        foreach ($action_lists as $kk => $vv) {
                            $actiontxt .= isset($actionlist[$vv]) ? $actionlist[$vv].';' : '';
                        }
                    }
                    if ($actiontxt != '') {
                        $actiontxt = substr($actiontxt, 0, strlen($actiontxt) - 1);
                    }
                    $role_list[$v['Role']['id']]['Role']['actionses'] = $actiontxt;
                }
            }
        }
        $this->set('role_list', $role_list);
        $this->set('role_name', $role_name);
        $this->set('title_for_layout', $this->ld['operator_roles'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_role_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }

    public function edit($id)
    {
        /*判断权限*/
        $this->operator_privilege('operator_roles_edit');
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        /*end*/
        $this->set('title_for_layout', $this->ld['role_edit_role'].' - '.$this->ld['operator_roles'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operator_roles'],'url' => '/roles/');
        $this->navigations[] = array('name' => $this->ld['role_edit_role'],'url' => '');
        $operators = $this->Operator->find('all');//取得操作员列表
        $this->set('operators', $operators);
        $this->set('role_id', $id);
        if ($this->RequestHandler->isPost()) {
            $this->data['Role']['orderby'] = !empty($this->data['Role']['orderby']) ? $this->data['Role']['orderby'] : 50;
            if (isset($_REQUEST['competence'])) {
                $competence = $_REQUEST['competence'];
                $competence = implode(';', $competence);
                $this->data['Role']['actions'] = $competence;
            }
            $this->Role->save($this->data); //保存
                foreach ($this->data['RoleI18n'] as $v) {
                    $Rolei18n_info = array(
                                //   'id'=>	isset($v['id'])?$v['id']:'',
                                   'id' => $v['id'],
                                   'locale' => $v['locale'],
                                   'role_id' => isset($v['role_id']) ? $v['role_id'] : $this->data['Role']['id'],
                                   'name' => isset($v['name']) ? $v['name'] : '',
                             );
                    $this->RoleI18n->saveall(array('RoleI18n' => $Rolei18n_info));//更新多语言
                }
            foreach ($operators as $k => $v) {
                if ($v['Operator']['role_id'] == 0) {
                    if (isset($_REQUEST['operators']) && count($_REQUEST['operators']) > 0) {
                        if (in_array($v['Operator']['id'], $_REQUEST['operators'])) {
                            $operators[$k]['Operator']['role_id'] = $this->data['Role']['id'];
                            $this->Operator->save($operators[$k]);
                        }
                    }
                } else {
                    $role_ids = explode(';', $v['Operator']['role_id'].';');
                    foreach ($role_ids as $key => $vaule) {
                        if (empty($vaule)) {
                            unset($role_ids[$key]);
                        }
                    }
                    if ($v['Operator']['id'] == 13) {
                    }
                    if (in_array($this->data['Role']['id'], $role_ids)) {
                        if (isset($_REQUEST['operators']) && count($_REQUEST['operators']) > 0) {
                            if (in_array($v['Operator']['id'], $_REQUEST['operators'])) {
                            } else {
                                foreach ($role_ids as $kkk => $vvv) {
                                    if ($vvv == $this->data['Role']['id']) {
                                        unset($role_ids[$kkk]);
                                    }
                                }
                                $operators[$k]['Operator']['role_id'] = implode(';', $role_ids);
                                $this->Operator->save($operators[$k]);
                            }
                        }
                    } else {
                        if (isset($_REQUEST['operators']) && count($_REQUEST['operators']) > 0) {
                            if (in_array($v['Operator']['id'], $_REQUEST['operators'])) {
                                $operators[$k]['Operator']['role_id'] .= ';'.$this->data['Role']['id'];
                                $this->Operator->save($operators[$k]);
                            }
                        }
                    }
                }
            }
            foreach ($this->data['RoleI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_role'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
                }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
                $this->redirect($back_url);
        }
        $this->data = $this->Role->localeformat($id);
        $this->Action->set_locale($this->backend_locale);
        $Action = $this->Action->alltree_hasname();
        $this->set('actions_arr', explode(';', $this->data['Role']['actions']));
        $this->set('Role', $this->data);
        //应用判断
        /*
        $all_infos = $this->apps['codes'];
        foreach ($Action as $k => $v) {
            if ($v['Action']['app_code'] != '' && !in_array($v['Action']['app_code'], $all_infos)) {
                unset($Action[$k]);
            }
            if (isset($v['SubAction']) && count($v['SubAction']) > 0) {
                foreach ($v['SubAction'] as $kk => $vv) {
                    if (isset($vv['Action'])) {
                        if ($vv['Action']['app_code'] != '' && !in_array($vv['Action']['app_code'], $all_infos)) {
                            unset($Action[$k]['SubAction'][$kk]);
                        }
                        if ($vv['Action']['code'] == 'applications_view' && isset($this->configs['use_app']) && $this->configs['use_app'] == 0 && (!isset($_SESSION['use_app']) || $_SESSION['use_app'] != 1)) {
                            unset($Action[$k]['SubAction'][$kk]);
                        }
                        if ($vv['Action']['code'] == 'languages_view' && (($this->Language->find('count')) <= 0)) {
                            unset($Action[$k]['SubAction'][$kk]);
                        }
                        if ($vv['Action']['code'] == 'payments_view' && (($this->Payment->find('count', array('conditions' => array('Payment.status' => 1)))) == 0)) {
                            unset($Action[$k]['SubAction'][$kk]);
                        }
                    }
                }
            }
        }
        */
        $this->set('Action', $Action);
    }

    public function add()
    {
        /*判断权限*/
        $this->operator_privilege('operator_roles_add');
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        /*end*/
        $this->set('title_for_layout', $this->ld['role_add_role'].' - '.$this->ld['operator_roles'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operator_roles'],'url' => '/roles/');
        $this->navigations[] = array('name' => $this->ld['role_add_role'],'url' => '');
        $operators = $this->Operator->find('all');//取得操作员列表
        $this->set('operators', $operators);
        if ($this->RequestHandler->isPost()) {
            $this->data['Role']['orderby'] = !empty($this->data['Role']['orderby']) ? $this->data['Role']['orderby'] : 50;
            $this->data['Role']['store_id'] = !empty($this->data['Role']['store_id']) ? $this->data['Role']['store_id'] : 0;
            $this->data['Role']['actions'] = !empty($this->data['Role']['actions']) ? $this->data['Role']['actions'] : 0;
            if (isset($_REQUEST['competence'])) {
                $competence = $_REQUEST['competence'];
                $competence = implode(';', $competence);
                $this->data['Role']['actions'] = $competence;
            }
            $this->Role->saveall($this->data['Role']); //保存
                  $id = $this->Role->id;
                  //新增角色多语言
                  if (is_array($this->data['RoleI18n'])) {
                      foreach ($this->data['RoleI18n'] as $k => $v) {
                          $v['role_id'] = $id;
                          $this->RoleI18n->id = '';
                          $this->RoleI18n->saveall(array('RoleI18n' => $v));
                      }
                  }
            if (isset($_REQUEST['operators']) && count($_REQUEST['operators']) > 0) {
                foreach ($_REQUEST['operators'] as $k => $v) {
                    $operator = $this->Operator->findbyid($v);
                    if (!empty($operator['Operator']['role_id'])) {
                        $operator['Operator']['role_id'] .= ';'.$id;
                    } else {
                        $operator['Operator']['role_id'] = $id;
                    }
                    $this->Operator->save($operator);
                }
            }
            foreach ($this->data['RoleI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_role'].':'.$userinformation_name, $this->admin['id']);
                }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
                $this->redirect($back_url);
        }
        $this->Action->set_locale($this->backend_locale);
        $Action = $this->Action->alltree_hasname();
        foreach ($Action as $k => $v) {
            $Action[$k]['Action']['name'] = $v['ActionI18n']['name'];
            if (isset($v['SubAction'])) {
                foreach ($v['SubAction'] as $kk => $vv) {
                    $Action[$k]['SubAction'][$kk]['Action']['name'] = $vv['ActionI18n']['name'];
                }
            }
        }
         //应用判断
        $all_infos = $this->apps['codes'];
        foreach ($Action as $k => $v) {
            if (isset($v['SubAction']) && count($v['SubAction']) > 0) {
                foreach ($v['SubAction'] as $kk => $vv) {
                    if (isset($vv['Action'])) {
                        if ($vv['Action']['code'] == 'applications_view' && isset($this->configs['use_app']) && $this->configs['use_app'] == 0 && (!isset($_SESSION['use_app']) || $_SESSION['use_app'] != 1)) {
                            unset($Action[$k]['SubAction'][$kk]);
                        }
                        if ($vv['Action']['code'] == 'languages_view' && (($this->Language->find('count')) <= 0)) {
                            unset($Action[$k]['SubAction'][$kk]);
                        }
                        if ($vv['Action']['code'] == 'payments_view' && (($this->Payment->find('count', array('conditions' => array('Payment.status' => 1)))) == 0)) {
                            unset($Action[$k]['SubAction'][$kk]);
                        }
                    }
                }
            }
        }
        $this->set('Action', $Action);
    }

    public function remove($id)
    {
        /*判断权限*/
        $this->operator_privilege('operator_roles_remove');
        /*end*/
        $pn = $this->RoleI18n->find('list', array('fields' => array('RoleI18n.role_id', 'RoleI18n.name'), 'conditions' => array('RoleI18n.role_id' => $id, 'RoleI18n.locale' => $this->locale)));
        $this->Role->deleteAll(array('Role.id' => $id));
        $this->RoleI18n->deleteAll(array('RoleI18n.role_id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_role'].':id '.' '.$pn[$id], $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }

    public function batch_operations()
    {
        $user_checkboxes = $_REQUEST['checkboxes'];
        foreach ($user_checkboxes as $k => $v) {
            $this->Role->deleteAll(array('Role.id' => $v));
            $this->RoleI18n->deleteAll(array('RoleI18n.role_id' => $v));
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

    public function getActionByRole(){
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $result['code'] = 0;
        $result['msg'] = 'No Data!';
        if ($this->RequestHandler->isPost()) {
            $operator_role_ids_str = $_POST['operator_role_ids'];
            $operator_role_ids = explode(';', $operator_role_ids_str);
            $this->Role->set_locale($this->backend_locale);
            $opera_data = $this->Role->find('list', array('conditions' => array('Role.id' => $operator_role_ids), 'fields' => array('Role.id', 'Role.actions')));
            $operator_action_ids_str = '';
            $operator_action_ids = array();
            foreach ($opera_data as $k => $v) {
			if (!empty($v) && $v != '') {
				$operator_action_id = explode(';', $v);
				foreach ($operator_action_id as $vv) {
					$operator_action_ids[$vv] = $vv;
				}
			}
            }
            if (!empty($operator_action_ids)) {
                	$operator_action_ids_str = implode(';', $operator_action_ids);
            }
            $result['code'] = $operator_action_ids_str == '' ? 1 : 2;
            $result['msg'] = $operator_action_ids_str;
        }
        die(json_encode($result));
    }
    
    
      //角色管理上传
public function role_upload(){
	  Configure::write('debug', 0);
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        /*end*/

        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operator_roles'],'url' => '/roles/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
      $this->set('title_for_layout', $this->ld['bulk_upload'].' - '.$this->ld['operator_roles'].' - '.$this->ld['manage_system'].' - '.$this->configs['shop_name']);
   $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_role_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//角色管理cvs查看
 public function role_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/roles/role_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_role_export', 'Profile.status' => 1)));
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
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/roles/role_upload';</script>";
						die();
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/roles/role_upload';</script>";
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
                        $code_array=explode(';',$v['Role']['actions']);
                        foreach($code_array as $code_key=>$code_val){
                        		$Action_id_array[]=$this->Action->find('first',array('fields'=>array('Action.id'),'conditions'=>array('Action.code'=>$code_val)));
                        }
                        $Action_id_str='';
                        foreach($Action_id_array as $id_val){
                        	if(isset($id_val['Action']['id'])){
                        	$Action_id_str.=$id_val['Action']['id'].";";
                        	}
                        }
                        $Role_first = $this->Role->find('first', array('conditions' => array('Role.actions' =>$Action_id_str)));
                        $v['Role']['id']=isset($Role_first['Role']['id'])?$Role_first['Role']['id']:0;
                        $v['Role']['orderby']=isset($v['Role']['orderby'])?$v['Role']['orderby']:50;
                        $v['Role']['actions']=$Action_id_str;
                        $v['Role']['store_id']=0;
                        	if( $s1=$this->Role->save($v['Role']) ){
                        		$Role_id=$this->Role->id;
                        	}
                        	$RoleI18n_first = $this->RoleI18n->find('first', array('conditions' => array('RoleI18n.role_id' =>$Role_id, 'RoleI18n.locale' => $v['RoleI18n']['locale'])));
                        $v['RoleI18n']['id']=isset($RoleI18n_first['RoleI18n']['id'])?$RoleI18n_first['RoleI18n']['id']:0;
                        $v['RoleI18n']['role_id']=isset($Role_id)?$Role_id:'';
                        	$s2=$this->RoleI18n->save($v['RoleI18n']);
                        	 if( isset($s1)&&!empty($s1)&&isset($s2)&&!empty($s2)){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                    }
                   
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/roles/role_upload/'</script>";
		            die();
                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/roles/role_upload/'</script>";
                    	
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



		 
//角色管理csv
public function download_role_csv_example($out_type = 'Role'){
 Configure::write('debug', 0);
     $this->layout="ajax";
     $this->Role->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_role_export', 'Profile.status' => 1)));
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
          $Role_info = $this->Role->find('all', array('fields'=>array('Role.actions','Role.status','Role.orderby','RoleI18n.locale','RoleI18n.name'),'order' => 'Role.id desc','limit'=>10));
		//pr($Role_info);die();
         
            
              //循环数组
              foreach($Role_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                  if($fields_ks[1]=='actions'){
	                  	 
		                  	$action_array=explode(';',$v['Role']['actions']);
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
//全部导出   
public function all_export_csv($out_type = 'Role'){
 Configure::write('debug', 0);
     $this->layout="ajax";
          $this->Role->set_locale($this->backend_locale);

     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_role_export', 'Profile.status' => 1)));
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
          $Role_info = $this->Role->find('all', array('fields'=>array('Role.actions','Role.status','Role.orderby','RoleI18n.locale','RoleI18n.name'),'order' => 'Role.id desc'));
		//pr($Role_info);die();
         
            
              //循环数组
              foreach($Role_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                  if($fields_ks[1]=='actions'){
	                  	 
		                  	$action_array=explode(';',$v['Role']['actions']);
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

//选择导出   
public function choice_export($out_type = 'Role'){
 Configure::write('debug', 0);
     $this->layout="ajax";
          $this->Role->set_locale($this->backend_locale);

     $user_checkboxes = $_REQUEST['checkboxes'];

     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_role_export', 'Profile.status' => 1)));
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
          $Role_info = $this->Role->find('all', array('fields'=>array('Role.actions','Role.status','Role.orderby','RoleI18n.locale','RoleI18n.name'),'order' => 'Role.id desc','conditions'=>array('Role.id'=>$user_checkboxes)));
	//	pr($Role_info);die();
         
            
              //循环数组
              foreach($Role_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                  if($fields_ks[1]=='actions'){
	                  	 
		                  	$action_array=explode(';',$v['Role']['actions']);
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
    
}
