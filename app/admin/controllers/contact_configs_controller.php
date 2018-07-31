<?php

/*****************************************************************************
 * Seevia 联系我们配置管理
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
class ContactConfigsController extends AppController
{
    public $name = 'ContactConfigs';
    public $helpers = array('Pagination','Html');
    public $components = array('Pagination','RequestHandler','Email','Phpexcel','Phpcsv');
    public $uses = array('Profile','ProfileFiled','Contact','ContactConfig','ContactConfigI18n','InformationResource','InformationResourceI18n','OperatorLog');

    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('contacts_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/contacts/');
        /*end*/
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['contacts_us'],'url' => '/contacts/');
		$this->navigations[] = array('name' => $this->ld['contact_config_management'],'url' => '/contact_configs/');

		$this->ContactConfig->set_locale($this->backend_locale);
		
        $condition = '';
		$condition['ContactConfig.contact_id']=0;
        if (isset($this->params['url']['kword_name']) && trim($this->params['url']['kword_name']) != '') {
			$kword_name=trim($this->params['url']['kword_name']);
            $condition['and']['or']['ContactConfig.code like'] = '%'.$kword_name.'%';
            $condition['and']['or']['ContactConfigI18n.name like'] = '%'.$kword_name.'%';
            $condition['and']['or']['ContactConfigI18n.description like'] = '%'.$kword_name.'%';
			$condition['and']['or']['ContactConfigI18n.contact_config_values like'] = '%'.$kword_name.'%';
            $this->set('kword_name', $kword_name);
        }
        if (isset($this->params['url']['contact_us_type']) && trim($this->params['url']['contact_us_type'])!= '') {
			$contact_us_type=trim($this->params['url']['contact_us_type']);
            $condition['and']['ContactConfig.type'] = $contact_us_type;
            $this->set('contact_us_type', $contact_us_type);
        }
        if (isset($this->params['url']['status']) && trim($this->params['url']['status']) != '') {
			$contact_status=trim($this->params['url']['status']);
            $condition['and']['ContactConfig.status'] = $contact_status;
            $this->set('contact_status', $contact_status);
        }
        $total = $this->ContactConfig->find('count', array('conditions' => $condition));
        $sortClass = 'ContactConfig';
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'contacts','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Contact');
        $this->Pagination->init($condition, $parameters, $options);
        $contact_config_info = $this->ContactConfig->find('all', array('conditions' => $condition, 'order' => 'ContactConfig.orderby,ContactConfig.id', 'page' => $page, 'limit' => $rownum));
        $this->set('contact_config_info', $contact_config_info);
        //信息库
        $Resource_info = $this->Resource->getformatcode(array('contact_us_type'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        $this->set('title_for_layout', $this->ld['contact_config_management'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $this->Profile->hasOne = array();
           $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'contact_config_export', 'Profile.status' => 1)));
		$this->set('profile_id',$profile_id);
    }

    public function view($id=0)
    {
        /*判断权限*/
        $this->operator_privilege('contacts_detail');
        $this->menu_path = array('root' => '/crm/','sub' => '/contacts/');
        /*end*/

        $this->set('title_for_layout', $this->ld['contacts_us'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['contacts_us'],'url' => '/contacts/');
		$this->navigations[] = array('name' => $this->ld['contact_config_management'],'url' => '/contact_configs/');
        $this->navigations[] = array('name' => $this->ld['add_edit'],'url' => '');
		
		if ($this->RequestHandler->isPost()) {
			
			$this->ContactConfig->save($this->data['ContactConfig']);
			$contact_config_id=$this->ContactConfig->id;
			if(!empty($this->data['ContactConfigI18n'])){
				foreach($this->data['ContactConfigI18n'] as $v){
					$v['contact_config_id']=$contact_config_id;
					$this->ContactConfigI18n->save($v);
				}				
			}
			$back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
		}
		
		$contact_config_data=$this->ContactConfig->localeformat($id);
		$this->set('contact_config_data',$contact_config_data);
		
        $Resource_info = $this->Resource->getformatcode(array('contact_us_type'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        
        $locale_google_translate_code=$this->Language->info['backend']['google_translate_code'];
        $this->set('locale_google_translate_code',$locale_google_translate_code);
    }
	
	public function ajax_contact_code_check(){
		Configure::write('debug', 0);
        $this->layout = 'ajax';
		
		$result=array();
		$result['code']="0";
		
		$contact_config_id=isset($_POST['contact_config_id'])?$_POST['contact_config_id']:0;
		$contact_config_code=isset($_POST['contact_config_code'])?$_POST['contact_config_code']:'';
		
		$data_total=$this->ContactConfig->find('total',array('conditions'=>array('ContactConfig.id <>'=>$contact_config_id,'ContactConfig.code'=>$contact_config_code),'recursive'=>'-1'));
		
		if($data_total==0){
			$result['code']="1";
		}
		die(json_encode($result));
	}

    //批量处理
    public function batch()
    {
        if (!empty($_REQUEST['checkboxes'])) {
            foreach ($_REQUEST['checkboxes'] as $k => $v) {
                $art_ids[] = $v;
            }
            $condition['ContactConfig.id'] = $art_ids;
            $this->ContactConfig->deleteAll($condition);
			$this->ContactConfigI18n->deleteAll(array('ContactConfigI18n.contact_config_id'=>$art_ids));
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        } else {
            $this->redirect('/contacts/');
        }
    }

    /**
     *删除联系我们.
     *
     *@param int $id 输入ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_route_failure'];

        $this->ContactConfig->deleteAll(array('ContactConfig.id' => $id));
		$this->ContactConfigI18n->deleteAll(array('ContactConfigI18n.contact_config_id'=>$id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_article'].' '.' id:'.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_article_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    
    
        //联系我们配置管理上传
public function contact_config_upload(){
	  Configure::write('debug', 0);
        $this->operation_return_url(true);//设置操作返回页面地址

        $this->menu_path = array('root' => '/crm/','sub' => '/contacts/');
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['contacts_us'],'url' => '/contacts/');
		$this->navigations[] = array('name' => $this->ld['contact_config_management'],'url' => '/contact_configs/');
           $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
           $this->set('title_for_layout', $this->ld['contact_config_management'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
           $this->Profile->hasOne = array();
           $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'contact_config_export', 'Profile.status' => 1)));
		$this->set('profile_id',$profile_id);
	
    }



//菜单管理cvs查看
 public function contact_config_uploadpreview()
    {
    	Configure::write('debug', 1);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/contact_configs/contact_config_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'contact_config_export', 'Profile.status' => 1)));
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
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/contact_configs/contact_config_upload';</script>";
						die();
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/contact_configs/contact_config_upload';</script>";
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
                        
                        if( isset($v['ContactConfig']['code']) && $v['ContactConfig']['code']!="" ){
                        $ContactConfig_condition='';
                        	$ContactConfig_condition['ContactConfig.code']=$v['ContactConfig']['code'];
                        
                        $ContactConfig_condition['ContactConfig.contact_id']=0;
                        $ContactConfig_first = $this->ContactConfig->find('first', array('conditions' =>$ContactConfig_condition));
                        $v['ContactConfig']['id']=isset($ContactConfig_first['ContactConfig']['id'])?$ContactConfig_first['ContactConfig']['id']:0;
                        $v['ContactConfig']['contact_id']=0;
                        $v['ContactConfig']['status']=isset($v['ContactConfig']['status'])?$v['ContactConfig']['status']:1;
                        $v['ContactConfig']['type']=isset($v['ContactConfig']['type'])?$v['ContactConfig']['type']:0;
                        $v['ContactConfig']['is_required']=isset($v['ContactConfig']['is_required'])?$v['ContactConfig']['is_required']:0;
                        $v['ContactConfig']['orderby']=isset($v['ContactConfig']['orderby']) && $v['ContactConfig']['orderby']!=''?$v['ContactConfig']['orderby']:50;
                         if($s1=$this->ContactConfig->save($v['ContactConfig'])){
                         	$ContactConfig_id=$this->ContactConfig->id;
                         }
                         $OpenKeywordAnswer_condition='';
                         if( isset($ContactConfig_id) && $ContactConfig_id!='' ){
                         	$ContactConfigI18n_condition['ContactConfigI18n.contact_config_id']=$ContactConfig_id;
                         }
                          if(isset($v['ContactConfigI18n']['locale'])){
                         	$ContactConfigI18n_condition['ContactConfigI18n.locale']=$v['ContactConfigI18n']['locale'];
                         }
                        $ContactConfigI18n_first = $this->ContactConfigI18n->find('first', array('conditions' =>$ContactConfigI18n_condition));
                        $v['ContactConfigI18n']['id']=isset($ContactConfigI18n_first['ContactConfigI18n']['id'])?$ContactConfigI18n_first['ContactConfigI18n']['id']:0;
                        $v['ContactConfigI18n']['contact_config_id']=isset($ContactConfig_id)?$ContactConfig_id:'';
                        $s2=$this->ContactConfigI18n->save($v['ContactConfigI18n']);
                        	 if( isset($s1) && !empty($s1) && isset($s2) && !empty($s2) ){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                    }
                    }
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/contact_configs/'</script>";
		            die();
                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/contact_configs/contact_config_upload/'</script>";
                    	
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



		 
//菜单管理csv
public function download_contact_config_csv_example($out_type = 'ContactConfig'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     $this->ContactConfig->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'contact_config_export', 'Profile.status' => 1)));
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
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
	   $ContactConfig_info = $this->ContactConfig->find('all', array('fields'=>array('ContactConfig.code','ContactConfig.value_type','ContactConfig.status','ContactConfig.orderby','ContactConfig.is_required','ContactConfigI18n.locale','ContactConfigI18n.name','ContactConfigI18n.description','ContactConfigI18n.contact_config_values'),'conditions'=>array('ContactConfig.contact_id'=>0),'order'=>'ContactConfig.id desc','limit'=>10));
              //循环数组
              foreach($ContactConfig_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                 
                                         $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]])?$v[$fields_ks[0]][$fields_ks[1]]:'';
	                  
	               
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
public function all_export_csv($out_type = 'ContactConfig'){
 Configure::write('debug', 1);
     $this->layout="ajax";
      $this->ContactConfig->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'contact_config_export', 'Profile.status' => 1)));
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
        	   $ContactConfig_info = $this->ContactConfig->find('all', array('fields'=>array('ContactConfig.code','ContactConfig.value_type','ContactConfig.status','ContactConfig.orderby','ContactConfig.is_required','ContactConfigI18n.locale','ContactConfigI18n.name','ContactConfigI18n.description','ContactConfigI18n.contact_config_values'),'conditions'=>array('ContactConfig.contact_id'=>0),'order'=>'ContactConfig.id desc'));

            //pr();die();
              //循环数组
              foreach($ContactConfig_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	               
                          $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';

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
public function choice_export($out_type = 'ContactConfig'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     $this->ContactConfig->set_locale($this->backend_locale);
     $user_checkboxes = $_REQUEST['checkboxes'];
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'contact_config_export', 'Profile.status' => 1)));
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
          $ContactConfig_conditions['ContactConfig.id']=$user_checkboxes; 
            	   $ContactConfig_info = $this->ContactConfig->find('all', array('fields'=>array('ContactConfig.code','ContactConfig.value_type','ContactConfig.status','ContactConfig.orderby','ContactConfig.is_required','ContactConfigI18n.locale','ContactConfigI18n.name','ContactConfigI18n.description','ContactConfigI18n.contact_config_values'),'conditions'=>array('ContactConfig.contact_id'=>0),'conditions'=>$ContactConfig_conditions,'order'=>'ContactConfig.id desc'));

              //循环数组
              foreach($ContactConfig_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
                                         $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
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
