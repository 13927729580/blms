<?php

/*****************************************************************************
 * Seevia 资源管理
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
 *系统资源管理.
 *
 *对于resource这张表的增删改查
 *
 *@author   weizhngye 
 *
 *@version  $Id$
 */
class ResourcesController extends AppController
{
    public $name = 'Resources';
//	var $helpers = array('Html');
    public $uses = array('Profile','ProfileFiled','Resource','ResourceI18n');
    public $components = array('Pagination','RequestHandler','Phpexcel','Phpcsv'); // Added 
    public $helpers = array('Pagination');

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
        $this->operator_privilege('resources_view');
        /*end*/
        $this->menu_path = array('root' => '/web_application/','sub' => '/resources/');
        $this->set('title_for_layout', $this->ld['resource_manage'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['resource_manage'],'url' => '/resources/');
        $conditions = array();
        //$conditions['and'][]['Resource.parent_id'] = 0;
        if (isset($_REQUEST['keywords']) && $_REQUEST['keywords'] != '') {
            $conditions['and']['or']['ResourceI18n.name like'] = '%'.$_REQUEST['keywords'].'%';
            $conditions['and']['or']['Resource.code like'] = '%'.$_REQUEST['keywords'].'%';
            $conditions['and']['or']['Resource.resource_value like'] = '%'.$_REQUEST['keywords'].'%';
            $this->set('keywords', $_REQUEST['keywords']);
        }
	if (isset($this->params['url']['system_code']) && $this->params['url']['system_code'] != '') {
		$conditions['Resource.system_code']=$this->params['url']['system_code'];
		$this->set('system_code',$this->params['url']['system_code']);
	}
	if (isset($this->params['url']['module_code']) && $this->params['url']['module_code'] != '') {
		$conditions['Resource.module_code']=$this->params['url']['module_code'];
		$this->set('module_code',$this->params['url']['module_code']);
	}
        $this->Resource->set_locale($this->backend_locale);
        $cond = array();
        $cond['conditions'] = $conditions;
        $cond['order'] = 'Resource.created desc';
        $resource = $this->Resource->tree($cond);//取所有资源

        $total = sizeof($resource);
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'resources','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Resource');
        $this->Pagination->init($cond, $parameters, $options);

        $start = ($page * $rownum) - $rownum;//当前页开始位置

        $resource = array_slice($resource, $start, $rownum);
        $this->set('resource', $resource);
        $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'resource_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
       
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
            $this->operator_privilege('resources_add');
        } else {
            $this->operator_privilege('resources_edit');
        }
        /*end*/
        $this->menu_path = array('root' => '/web_application/','sub' => '/resources/');
        $this->pageTitle = '编辑资源 - '.$this->ld['resource_manage'].' - '.$this->configs['shop_name'];
        $this->set('title_for_layout', $this->pageTitle);
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['resource_manage'],'url' => '/resources/');
        //$this->navigations[] = array('name'=>'编辑资源','url'=>'');
        $this->set('navigations', $this->navigations);
        $userinformation_name = '';
        if ($this->RequestHandler->isPost()) {
            if ($id != 0) {
                $this->data['Resource']['orderby'] = !empty($this->data['Resource']['orderby']) ? $this->data['Resource']['orderby'] : 50;
                $this->Resource->save($this->data);
                foreach ($this->data['ResourceI18n'] as $v) {
                    $resourceI18n_info = array(
                                 'id' => isset($v['id']) ? $v['id'] : 'null',
                                   'locale' => $v['locale'],
                                   'resource_id' => $id ,
                                   'name' => isset($v['name']) ? $v['name'] : '',
                                'description' => isset($v['description']) ? $v['description'] : '',
                                'modified' => date('Y-m-d H:i:s'),
                 );
                    $this->ResourceI18n->saveAll(array('ResourceI18n' => $resourceI18n_info));//更新多语言
                }
                foreach ($this->data['ResourceI18n'] as $k => $v) {
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
            $this->redirect('/resources/');
            } else {
                $this->data['Resource']['orderby'] = !empty($this->data['Resource']['orderby']) ? $this->data['Resource']['orderby'] : 50;
                $this->Resource->saveAll(array('Resource' => $this->data['Resource']));
                $id = $this->Resource->getLastInsertId();
                foreach ($this->data['ResourceI18n'] as $k => $v) {
                    $v['resource_id'] = $id;
                    $this->ResourceI18n->saveAll(array('ResourceI18n' => $v));
                }
                foreach ($this->data['ResourceI18n'] as $k => $v) {
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
            $this->redirect('/resources/');
            }
        }
        $this->data = $this->Resource->localeformat($id);
        $this->Resource->set_locale($this->backend_locale);
        $parentmenu = $this->Resource->find('all', array('conditions' => array('Resource.parent_id' => '0')));
        $this->set('parentmenu', $parentmenu);
        //leo20090722导航显示
        if ($id != 0) {
            $this->navigations[] = array('name' => $this->data['ResourceI18n'][$this->backend_locale]['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => '添加资源','url' => '');
        }
        $this->set('navigations', $this->navigations);
        //取版本标识
        $this->Resource->set_locale($this->backend_locale);
        $this->set('section', $this->Resource->find_assoc('section'));
             $all_system_modules =         $this->System->modules(false);
            $all_systems=array_keys($all_system_modules);
//            pr($all_system_modules);pr($all_systems);
            $this->set('all_system_modules', $all_system_modules);
            $this->set('all_systems', $all_systems);
    }
    
    function system_modified(){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result['flag'] = 0;
		$result['content'] = $this->ld['modify_failed'];
		$resource_id=isset($_POST['id'])?$_POST['id']:0;
		$system_code=isset($_POST['val'])?trim($_POST['val']):'';
		$this->Resource->save(array('id'=>$resource_id,'system_code'=>$system_code));
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
		$this->Resource->save(array('id'=>$resource_id,'module_code'=>$module_code));
		$result['flag'] = 1;
		$result['content'] = $module_code;
		die(json_encode($result));
      }
      
    /**
     *resource删除的方法.
     *
     *呈现数据库表resource的删除
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        /*判断权限*/
        if (!$this->operator_privilege('resources_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        /*end*/
        $system_info = $this->Resource->findById($id);
        $res = $this->Resource->find('count', array('conditions' => array('Resource.parent_id' => $id)));
        $result = array();
        if ($res > 0) {
            $result['message'] = '删除失败，该资源还有子资源';
            $result['flag'] = 2;
        } else {
            $this->ResourceI18n->deleteAll(array('ResourceI18n.resource_id' => $id));
            $this->Resource->delete(array('Resource.id' => $id));
            //操作员日志
  //  	    if(isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1){
 //   	    $this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'删除系统资源:'.$system_info['SystemResourceI18n']['name'] ,'operation');
  //  	    }
            //$this->redirect('/resources/');
            $result['flag'] = 1;
        }
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
        $ids_arr=$this->Resource->find('all',array('conditions'=>array('Resource.parent_id' => $v)));
        //pr($ids_arr);die();
        foreach($ids_arr as $kk =>$vv){
        	$this->Resource->delete(array('Resource.id' => $vv['Resource']['id']));
        	$this->ResourceI18n->deleteAll(array('ResourceI18n.resource_id' => $vv['Resource']['id']));
        }
            $this->Resource->delete(array('Resource.id' => $v));
            
        }
      
       
        $result['flag'] = 1;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
   
//资源管理上传
public function resource_upload(){
	  Configure::write('debug', 0);
        $this->operation_return_url(true);//设置操作返回页面地址

           $this->menu_path = array('root' => '/web_application/','sub' => '/dictionaries/');
           $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
           $this->navigations[] = array('name' => $this->ld['resource_manage'],'url' => '/resources/');
           $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
           $this->set('title_for_layout', $this->ld['resource_manage'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
           $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'resource_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//资源管理cvs查看
 public function resource_uploadpreview()
    {
    	Configure::write('debug', 1);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                    	$this->menu_path = array('root' => '/web_application/','sub' => '/operator_actions/');
		        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
		        $this->navigations[] = array('name' => $this->ld['resource_manage'],'url' => '/resources/');
		        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
		        $this->set('title_for_layout', $this->ld['resource_manage'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/resources/resource_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'resource_export', 'Profile.status' => 1)));
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
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/resources/resource_upload';</script>";
						die();
                                }
                                $temp = array();
                               
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp) ) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/resources/resource_upload';</script>";
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
                        $parent_id_resource = $this->Resource->find('list', array('fields' => array('code', 'id'), 'order' => 'Resource.id desc'));
				$parent_id=isset($v['Resource']['parent_id'])&&isset($parent_id_resource[$v['Resource']['parent_id']])?$parent_id_resource[$v['Resource']['parent_id']]:0;
                      	$resource_condition='';
                      	$resource_condition['Resource.parent_id']=$parent_id;
                      	if(!empty($v['Resource']['code'])){
                      		$resource_condition['Resource.code']=$v['Resource']['code'];
                      	}
                      	if(!empty($v['Resource']['resource_value'])){
                      		$resource_condition['Resource.resource_value']=$v['Resource']['resource_value'];
                      	}
                        $Resource_first = $this->Resource->find('first', array('conditions' =>$resource_condition));
                        $v['Resource']['id']=isset($Resource_first['Resource']['id'])?$Resource_first['Resource']['id']:0;
                        $v['Resource']['parent_id']=isset($parent_id)?$parent_id:0;
                        $v['Resource']['section']=isset($v['Resource']['section'])?$v['Resource']['section']:'免费版';
                        $v['Resource']['orderby']=isset($v['Resource']['orderby'])?$v['Resource']['orderby']:50;
                        $v['Resource']['status']=isset($v['Resource']['status'])?$v['Resource']['status']:1;
                       
                        	if( $s1=$this->Resource->save($v['Resource']) ){
                        		$Resource_id=$this->Resource->id;
                        	}
                       
                        	$ResourceI18n_first = $this->ResourceI18n->find('first', array('conditions' => array('ResourceI18n.resource_id' =>$Resource_id, 'ResourceI18n.locale' => $v['ResourceI18n']['locale'])));
                        $v['ResourceI18n']['id']=isset($ResourceI18n_first['ResourceI18n']['id'])?$ResourceI18n_first['ResourceI18n']['id']:0;
                        $v['ResourceI18n']['resource_id']=isset($Resource_id)?$Resource_id:'';
                        if(isset($v['ResourceI18n']['resource_id'])&&$v['ResourceI18n']['resource_id']!=''){	$s2=$this->ResourceI18n->save($v['ResourceI18n']); }
                        	 if( isset($s1)&&!empty($s1)&&isset($s2)&&!empty($s2)){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                    }
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/resources/'</script>";
		            die();
                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/resources/resource_upload/'</script>";
                    	
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



		 
//资源管理csv
public function download_resource_csv_example($out_type = 'Resource'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     $this->Resource->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'resource_export', 'Profile.status' => 1)));
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
          $Resource_info = $this->Resource->find('all', array('fields'=>array('Resource.parent_id','Resource.code','Resource.resource_value','Resource.status','Resource.section','Resource.orderby','ResourceI18n.locale','ResourceI18n.name','ResourceI18n.description'),'order' => 'Resource.id desc','limit'=>10));
	//pr($Resource_info);die();
    $parent_id_resource = $this->Resource->find('list', array('fields' => array('id', 'code'), 'order' => 'Resource.id desc'));
            
              //循环数组
              foreach($Resource_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                  if ($fields_ks[1] == 'parent_id') {
                                         $user_tmp[] = isset($parent_id_resource[$v['Resource']['parent_id']])?$parent_id_resource[$v['Resource']['parent_id']]:'';
                                     } else {
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
public function all_export_csv($out_type = 'Resource'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     $this->Resource->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'resource_export', 'Profile.status' => 1)));
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
          $Resource_info = $this->Resource->find('all', array('fields'=>array('Resource.parent_id','Resource.code','Resource.resource_value','Resource.status','Resource.section','Resource.orderby','ResourceI18n.locale','ResourceI18n.name','ResourceI18n.description'),'order' => 'Resource.id desc'));
		//pr($OperatorRole_info);die();
    $name_id_resource = $this->ResourceI18n->find('list', array('fields' => array('resource_id', 'name'), 'order' => 'ResourceI18n.id desc'));

            
              //循环数组
              foreach($Resource_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                if ($fields_ks[1] == 'parent_id') {
                                         $user_tmp[] = isset($name_id_resource[$v['Resource']['parent_id']]) ? $name_id_resource[$v['Resource']['parent_id']] : '';
                                     } else {
                                         $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
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
public function choice_export($out_type = 'Resource'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     $user_checkboxes = $_REQUEST['checkboxes'];
	$this->Resource->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'resource_export', 'Profile.status' => 1)));
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
          $Resource_conditions['OR']['Resource.parent_id']=$user_checkboxes; 
          $Resource_conditions['OR']['Resource.id']=$user_checkboxes; 
          
          $Resource_info = $this->Resource->find('all', array('fields'=>array('Resource.parent_id','Resource.code','Resource.resource_value','Resource.status','Resource.section','Resource.orderby','ResourceI18n.locale','ResourceI18n.name','ResourceI18n.description'),'order' => 'Resource.id desc','conditions'=>$Resource_conditions));
//	pr($Resource_info);die();
    $name_id_resource = $this->ResourceI18n->find('list', array('fields' => array('resource_id', 'name'), 'order' => 'ResourceI18n.id desc'));

            
              //循环数组
              foreach($Resource_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                if ($fields_ks[1] == 'parent_id') {
                                         $user_tmp[] = isset($name_id_resource[$v['Resource']['parent_id']]) ? $name_id_resource[$v['Resource']['parent_id']] : '';
                                     } else {
                                         $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
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
