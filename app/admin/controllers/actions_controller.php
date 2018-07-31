<?php

class ActionsController extends AppController
{
    public $name = 'Actions';
    public $components = array('Phpexcel','Phpcsv','Pagination','RequestHandler');
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    public $uses = array('Action','ActionI18n');

    public function index()
    {
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['rights_management'],'url' => '/actions/');
        $this->menu_path = array('root' => '/dev/','sub' => '/actions/');
        $condition = '';
        if (isset($this->params['url']['name']) && !empty($this->params['url']['name'])) {
            	$condition['ActionI18n.name like'] = '%'.$this->params['url']['name'].'%';
        }
        if (isset($this->params['url']['system_code']) && $this->params['url']['system_code'] != '') {
    		$condition['Action.system_code']=$this->params['url']['system_code'];
    		$this->set('system_code',$this->params['url']['system_code']);
    	}
    	if (isset($this->params['url']['module_code']) && $this->params['url']['module_code'] != '') {
    		$condition['Action.module_code']=$this->params['url']['module_code'];
    		$this->set('module_code',$this->params['url']['module_code']);
    	}
        $this->Action->set_locale($this->locale);
        $total = $this->Action->find('count', array('conditions' => $condition));
        $sortClass = 'Action';
        $page = 1;
        $rownum = isset($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters = array($rownum,$page);
        $options = array();
        $page = $this->Pagination->init($condition, $parameters, $options, $total, $rownum, $sortClass);
        $operator_action_data = $this->Action->find('all', array('conditions' => $condition, 'rownum' => $rownum, 'page' => $page, 'order' => 'Action.orderby asc'));
        $action_tree = $this->Action->alltree($condition);
        $this->set('action_tree', $action_tree);
        $this->set('operator_action_data', $operator_action_data);
        $this->set('title_for_layout', '权限管理'.' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
             
             $all_system_modules =         $this->System->modules(false);
            $all_systems=array_keys($all_system_modules);
            $this->set('all_system_modules', $all_system_modules);
            $this->set('all_systems', $all_systems);
    }
    
    
    public function view($id = 0)
    {
        $this->set('title_for_layout', '添加/编辑权限- 权限管理'.' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['rights_management'],'url' => '/actions/');
        $this->navigations[] = array('name' => $this->ld['add_edit_permissions'],'url' => '');
        $this->menu_path = array('root' => '/dev/','sub' => '/actions/');
        if ($this->RequestHandler->isPost()) {
            $this->data['Action']['orderby'] = !empty($this->data['Action']['orderby']) ? $this->data['Action']['orderby'] : '50';
            if (isset($this->data['Action']['id']) && $this->data['Action']['id'] != 0) {
                $this->Action->save(array('Action' => $this->data['Action'])); //关联保存
                $id = $this->data['Action']['id'];
            } else {
                $this->Action->saveAll(array('Action' => $this->data['Action'])); //关联保存
                $id = $this->Action->getLastInsertId();
            }
            $this->ActionI18n->deleteall(array('action_id' => $id)); //删除原有多语言
            foreach ($this->data['ActionI18n'] as $v) {
                $v['action_id'] = $id;
                $this->ActionI18n->saveAll(array('ActionI18n' => $v));//更新多语言
            }
             //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加/编辑权限:id '.$id, $this->admin['id']);
            }
            $this->redirect('/actions/');
        }

        $operator_action_data = $this->Action->localeformat($id);
        //var_dump($operator_action_data);
        $this->set('operator_action_data', $operator_action_data);
        //pr($operator_action_data);
        $this->Action->set_locale($this->locale);
        $operator_action_parent = $this->Action->find('threaded');
        $action_tree = $this->Action->tree($this->locale);
        $this->set('action_tree', $action_tree);
        //pr($operator_action_parent);
        $this->set('operator_action_parent', $operator_action_parent);
        
             $all_system_modules =         $this->System->modules(false);
            $all_systems=array_keys($all_system_modules);
            $this->set('all_system_modules', $all_system_modules);
            $this->set('all_systems', $all_systems);
    }

    //列表修改排序//无用函数
    public function update_operator_action_orderby()
    {
        $this->Action->hasMany = array();
        $this->Action->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = '请输入正确的排序数据！';
        }
        if (is_numeric($val) && $this->Action->save(array('id' => $id, 'orderby' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //列表状态修改//无用函数
    public function toggle_on_status()
    {
        $this->Action->hasMany = array();
        $this->Action->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Action->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    
    function system_modified(){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result['flag'] = 0;
		$result['content'] = $this->ld['modify_failed'];
		$action_id=isset($_POST['id'])?$_POST['id']:0;
		$system_code=isset($_POST['val'])?trim($_POST['val']):'';
		$this->Action->save(array('id'=>$action_id,'system_code'=>$system_code));
		$result['flag'] = 1;
		$result['content'] = $system_code;
		die(json_encode($result));
      }
      
      function module_modified(){
      	Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result['flag'] = 0;
		$result['content'] = $this->ld['modify_failed'];
		$action_id=isset($_POST['id'])?$_POST['id']:0;
		$module_code=isset($_POST['val'])?trim($_POST['val']):'';
		$this->Action->save(array('id'=>$action_id,'module_code'=>$module_code));
		$result['flag'] = 1;
		$result['content'] = $module_code;
		die(json_encode($result));
      }
      
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = '删除权限失败';
        $this->Action->deleteAll(array('Action.id' => $id));
        $this->ActionI18n->deleteAll(array('action_id' => $id));
        $this->removechild($id);
        $result['flag'] = 1;
        $result['message'] = '删除权限成功';
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    public function batch_operations()
    {
        $this->Action->hasOne = array();
        $user_checkboxes = $_REQUEST['checkboxes'];
        foreach ($user_checkboxes as $k => $v) {
        $ids_arr=$this->Action->find('all',array('conditions'=>array('Action.parent_id' => $v)));
        //pr($ids_arr);die();
        foreach($ids_arr as $kk =>$vv){
            $id_arr=$this->Action->find('all',array('conditions'=>array('Action.parent_id' => $vv['Action']['id'])));
        	foreach($id_arr as $kkk =>$vvv){
        		$this->Action->delete(array('Action.id' => $vvv['Action']['id']));
        		$this->ActionI18n->deleteAll(array('ActionI18n.action_id' => $vvv['Action']['id']));
        	}
        	$this->Action->delete(array('Action.id' => $vv['Action']['id']));
        	$this->ActionI18n->deleteAll(array('ActionI18n.action_id' => $vv['Action']['id']));
        }
            $this->Action->delete(array('Action.id' => $v));
            
        }
        
       
       $result['flag'] = 1;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function removechild($id = 0)
    {
        $child_actions = $this->Action->find('list', array('fields' => array('Action.id'), 'conditions' => array('Action.parent_id' => $id)));
        if (!empty($child_actions)) {
            foreach ($child_actions as $v) {
                $this->Action->deleteAll(array('Action.id' => $v));
                $this->ActionI18n->deleteAll(array('action_id' => $v));
                $this->removechild($v);
            }
        }
    }
    
     //批量上传
     public function operator_action_upload()
    {
        $this->menu_path = array('root' => '/dev/','sub' => '/actions/');
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['rights_management'],'url' => '/actions/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
        $this->set('title_for_layout', $this->ld['rights_management'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
    }
	
    public function operator_action_uploadpreview()
    {
        ////////////判断权限
        Configure::write('debug', 1);
        $success_num=0;
                if (isset($_POST['sub1']) && $_POST['sub1'] == 1 && !empty($_FILES['file'])) {
                    $this->menu_path = array('root' => '/web_application/','sub' => '/actions/');
		        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
		        $this->navigations[] = array('name' => $this->ld['rights_management'],'url' => '/actions/');
		        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
		        $this->set('title_for_layout', $this->ld['rights_management'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/actions/operator_action_upload';</script>";
                            die();
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                              $fields_array = array(
                            'Action.parent_id',
         				'Action.code',
         				'Action.section',
         					'Action.status',
         				'Action.orderby','ActionI18n.locale',
                            'ActionI18n.name',
             			'ActionI18n.operator_action_values',
                            'ActionI18n.description');

                            $fields = array(
                                $this->ld['previous_operator_action'],
                           	  $this->ld['userconfig_code'],
                           	  $this->ld['version'],
                           	  $this->ld['z_status'],
                           	  $this->ld['sort_by'],$this->ld['s_language'],
                                $this->ld['z_name'],
                                $this->ld['value'],
                                $this->ld['z_description'], );
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
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/actions/operator_action_upload';</script>";
						die();
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = empty($v) ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/actions/operator_action_upload';</script>";
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
                        if(isset($v['Action']['code']) && $v['Action']['code']!=''){
                        $Action_list = $this->Action->find('list', array('fields'=>array('code','id'),'order' => 'Action.id desc'));
			//	pr($InformationResource_list);
				$parent_id=isset($Action_list[$v['Action']['parent_id']])?$Action_list[$v['Action']['parent_id']]:0;
                        //pr($parent_id);die();
                        $Action_condition='';	
                        if(isset($parent_id)&&$parent_id!=''){
                        $Action_condition['Action.parent_id']=$parent_id;
                        }
                        if(isset($v['Action']['code'])&&$v['Action']['code']!=''){
                        $Action_condition['Action.code']=$v['Action']['code'];
                        }
                        if(isset($v['Action']['section'])&&$v['Action']['section']!=''){
                        $Action_condition['Action.section']=$v['Action']['section'];
                        }
                        if(isset($v['Action']['status'])&&$v['Action']['status']!=''){
                        $Action_condition['Action.status']=$v['Action']['status'];
                        }
                        //pr($Action_condition);
                        $Action_first = $this->Action->find('first', array('conditions' =>$Action_condition));
                        $v['Action']['id']=isset($Action_first['Action']['id'])?$Action_first['Action']['id']:0;
                        //pr($v['Action']['id']);die();
                        $v['Action']['parent_id']=isset($parent_id)?$parent_id:0;
                        $v['Action']['status']=isset($v['Action']['status'])?$v['Action']['status']:1;
                        $v['Action']['orderby']=isset($v['Action']['orderby'])?$v['Action']['orderby']:50;
					if( $s1=$this->Action->save($v['Action']) ){
                        		$Action_id=$this->Action->id;
                        	}
                        $ActionI18n_condition='';
                        if(isset($Action_id)&&$Action_id!=''){
                        	$ActionI18n_condition['ActionI18n.operator_action_id']=$Action_id;
                        	
                        if(isset($v['ActionI18n']['locale'])&&$v['ActionI18n']['locale']!=''){
                        	$ActionI18n_condition['ActionI18n.locale']=$v['ActionI18n']['locale'];
                        }
                        //pr($ActionI18n_condition);		
                        $ActionI18n_first = $this->ActionI18n->find('first', array('conditions' => $ActionI18n_condition));
                        $v['ActionI18n']['id']=isset($ActionI18n_first['ActionI18n']['id'])?$ActionI18n_first['ActionI18n']['id']:0;
                        //pr($v['ActionI18n']['id']);die();
                        $v['ActionI18n']['action_id']=isset($Action_id)?$Action_id:0;
				if(isset($v['ActionI18n']['action_id'])&&$v['ActionI18n']['action_id']!=''){
                        	$s2=$this->ActionI18n->save($v['ActionI18n']);
                		}
                		 if( isset($s1)&&!empty($s1)&&isset($s2)&&!empty($s2)){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                     }
                    }
                    }
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/actions/'</script>";

                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/actions/operator_action_upload/'</script>";
                }
    }
    //导出信息资源
      public function download_operator_action_csv_example()
      {
      	  Configure::write('debug', 1);
      	  $this->layout="ajax";
      	  $this->Action->set_locale($this->backend_locale);
              //定义一个数组
         $fields_array = array(
                            'Action.parent_id',
         				'Action.code',
         				'Action.section',
         					'Action.status',
         				'Action.orderby','ActionI18n.locale',
                            'ActionI18n.name',
             			'ActionI18n.operator_action_values',
                            'ActionI18n.description');

                            $fields = array(
                                $this->ld['previous_operator_action'],
                           	  $this->ld['userconfig_code'],
                           	  $this->ld['version'],
                           	  $this->ld['z_status'],
                           	  $this->ld['sort_by'],$this->ld['s_language'],
                                $this->ld['z_name'],
                                $this->ld['value'],
                                $this->ld['z_description']);
          $newdatas = array();
          $newdatas[] = $fields;
          //查询所有表里面所有信息 查询 10 条信息
          $Action_info = $this->Action->find('all', array('fields'=>array('ActionI18n.locale',
                            'ActionI18n.name',
             			'ActionI18n.operator_action_values',
                            'ActionI18n.description',
                            'Action.parent_id',
         				'Action.code',
         				'Action.section',
         					'Action.status',
         				'Action.orderby'),'order' => 'Action.id desc', 'limit' => 10));
          $Action_list = $this->Action->find('list', array('fields'=>array('id','code'),'order' => 'Action.id desc'));
          foreach ($Action_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  if($fields_ks[1]=='parent_id'){
                  	 $user_tmp[]= isset($Action_list[$v['Action']['parent_id']])?$Action_list[$v['Action']['parent_id']]:'';
                  }else{
                  	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
                  }
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
          $nameexl = $this->ld['rights_management'].date('Ymd').'.csv';
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
      	  $this->Action->set_locale($this->backend_locale);
              //定义一个数组
        $fields_array = array(
                            'Action.parent_id',
         				'Action.code',
         				'Action.section',
         					'Action.status',
         				'Action.orderby','ActionI18n.locale',
                            'ActionI18n.name',
             			'ActionI18n.operator_action_values',
                            'ActionI18n.description');

                            $fields = array(
                                $this->ld['previous_operator_action'],
                           	  $this->ld['userconfig_code'],
                           	  $this->ld['version'],
                           	  $this->ld['z_status'],
                           	  $this->ld['sort_by'],$this->ld['s_language'],
                                $this->ld['z_name'],
                                $this->ld['value'],
                                $this->ld['z_description'] );
          $newdatas = array();
          $newdatas[] = $fields;
          //查询所有表里面所有信息 
          $Action_info = $this->Action->find('all', array('fields'=>array('ActionI18n.locale',
                            'ActionI18n.name',
             			'ActionI18n.operator_action_values',
                            'ActionI18n.description',
                            'Action.parent_id',
         				'Action.code',
         				'Action.section',
         					'Action.status',
         				'Action.orderby'),'order' => 'Action.id desc'));
          $ActionI18n_list = $this->ActionI18n->find('list', array('fields'=>array('action_id','name'),'order' => 'ActionI18n.id desc'));

          foreach ($Action_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  if($fields_ks[1]=='parent_id'){
                  	 $user_tmp[]= isset($ActionI18n_list[$v['Action']['parent_id']])?$ActionI18n_list[$v['Action']['parent_id']]:'';
                  }else{
                  	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
                  }
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
          $nameexl = $this->ld['rights_management'].$this->ld['export'].date('Ymd').'.xls';
          $this->Phpexcel->output($nameexl, $newdatas);
          die();
      }
      
      //选择导出
      public function choice_export()
      {
      	  Configure::write('debug', 0);
      	  $this->layout="ajax";
      	  $this->Action->set_locale($this->backend_locale);
      	  $user_checkboxes = $_REQUEST['checkboxes'];
              //定义一个数组
          $fields_array = array(
                            'Action.parent_id',
         				'Action.code',
         				'Action.section',
         					'Action.status',
         				'Action.orderby','ActionI18n.locale',
                            'ActionI18n.name',
             			'ActionI18n.operator_action_values',
                            'ActionI18n.description');

                            $fields = array(
                                $this->ld['previous_operator_action'],
                           	  $this->ld['userconfig_code'],
                           	  $this->ld['version'],
                           	  $this->ld['z_status'],
                           	  $this->ld['sort_by'],$this->ld['s_language'],
                                $this->ld['z_name'],
                                $this->ld['value'],
                                $this->ld['z_description'] );
          $newdatas = array();
          $newdatas[] = $fields;
          $Action_conditions['AND']['Action.status']=1; 
          $Action_conditions['OR']['Action.parent_id']=$user_checkboxes; 
          $Action_conditions['OR']['Action.id']=$user_checkboxes; 
          $Action_info = $this->Action->find('all', array('fields'=>array('ActionI18n.locale',
                            'ActionI18n.name',
             			'ActionI18n.operator_action_values',
                            'ActionI18n.description',
                            'Action.parent_id',
         				'Action.code',
         				'Action.section',
         					'Action.status',
         				'Action.orderby'),'order' => 'Action.id desc','conditions'=>$Action_conditions));
          $ActionI18n_list = $this->ActionI18n->find('list', array('fields'=>array('action_id','name'),'order' => 'ActionI18n.id desc'));

          foreach ($Action_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  if($fields_ks[1]=='parent_id'){
                  	 $user_tmp[]= isset($ActionI18n_list[$v['Action']['parent_id']])?$ActionI18n_list[$v['Action']['parent_id']]:'';
                  }else{
                  	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
                  }
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
          $nameexl = $this->ld['rights_management'].$this->ld['export'].date('Ymd').'.xls';
          $this->Phpexcel->output($nameexl, $newdatas);
          die();
      }
    
    
    
}
