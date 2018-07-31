<?php

/*****************************************************************************
 * svsys 邮件模板
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
class MailtemplatesController extends AppController
{
    public $name = 'Mailtemplates';
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    public $components = array('Phpexcel','Phpcsv','Pagination','RequestHandler','Email');
    public $uses = array('Profile','ProfileFiled','MailTemplate','MailTemplateI18n','OperatorLog');

    public function index($page = 1)
    {
        $this->operator_privilege('mailtemplates_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/system/','sub' => '/mailtemplates/');
        $this->set('title_for_layout', $this->ld['email_template'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['email_template'],'url' => '/mailtemplates/');

        $this->MailTemplate->set_locale($this->backend_locale);

        $shop_name = $this->configs['shop_name'];
        $condition['type'] = 'template';

        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $total = $this->MailTemplate->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'mailtemplates','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'MailTemplate');
        $this->Pagination->init($condition, $parameters, $options);
        $mailtemplate_list = $this->MailTemplate->find('all', array('page' => $page, 'limit' => $rownum, 'conditions' => $condition));
        foreach ($mailtemplate_list as $k => $v) {
            $title = $v['MailTemplateI18n']['title'];
            @eval("\$title = \"$title\";");
            $v['MailTemplateI18n']['title'] = @$title;
            $mailtemplate_list[$k] = $v;
        }
        $this->set('mailtemplate_list', $mailtemplate_list);
        $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'mail_template_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }

    public function view($id = 0)
    {
        $this->operator_privilege('mailtemplates_edit');
        $this->menu_path = array('root' => '/system/','sub' => '/mailtemplates/');
        $this->set('title_for_layout', $this->ld['email_edit'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['email_template'],'url' => '/mailtemplates/');
        $shop_name = $this->configs['shop_name'];
        if ($this->RequestHandler->isPost()) {
            $this->data['MailTemplate']['type'] = 'template';
            if (isset($this->data['MailTemplate']['id']) && $this->data['MailTemplate']['id'] != '') {
                $this->MailTemplate->save(array('MailTemplate' => $this->data['MailTemplate'])); //关联保存
            } else {
                $this->MailTemplate->saveAll(array('MailTemplate' => $this->data['MailTemplate'])); //关联保存
                $id = $this->MailTemplate->getLastInsertId();
            }
            $this->MailTemplateI18n->deleteall(array('mail_template_id' => $id)); //删除原有多语言
            foreach ($this->data['MailTemplateI18n'] as $v) {
                $v['mail_template_id'] = $id;
                $this->MailTemplateI18n->saveAll(array('MailTemplateI18n' => $v));//更新多语言
            }
            foreach ($this->data['MailTemplateI18n'] as $k => $v) {
                if ($v['locale'] == $this->backend_locale) {
                    $userinformation_name = $v['title'];
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].'邮件模板:id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->data = $this->MailTemplate->localeformat($id);
        if (isset($this->data['MailTemplateI18n'][$this->backend_locale]['title'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].'-'.$this->data['MailTemplateI18n'][$this->backend_locale]['title'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_email_template'],'url' => '');
        }
    }

    /**
     *删除一个邮件模板
     *
     *@param int $id 输入邮件模板ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_email_template_failure'];
        $pn = $this->MailTemplateI18n->find('list', array('fields' => array('MailTemplateI18n.mail_template_id', 'MailTemplateI18n.title'), 'conditions' => array('MailTemplateI18n.mail_template_id' => $id, 'MailTemplateI18n.locale' => $this->backend_locale)));
        $this->MailTemplate->deleteAll(array('MailTemplate.id' => $id));
        $this->MailTemplateI18n->deleteAll(array('mail_template_id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_email_template'].':id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_email_template_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /*
        批量删除
    */
    public function removeall()
    {
        $MailTemplate_checkboxes = $_REQUEST['checkboxes'];
        $MailTemplate_Ids = '';
        foreach ($MailTemplate_checkboxes as $k => $v) {
            $MailTemplate_Ids = $MailTemplate_Ids.$v.',';
            $MailTemplate_Ids_arr[] = $v;
        }
        $id=explode(',',$MailTemplate_Ids);
        $MailTemplateI18n_ids = $this->MailTemplateI18n->find('list', array('fields' => array('MailTemplateI18n.mail_template_id', 'MailTemplateI18n.title'), 'conditions' => array('MailTemplateI18n.mail_template_id' => $id, 'MailTemplateI18n.locale' => $this->backend_locale)));

        $this->MailTemplate->deleteAll(array('MailTemplate.id' => $MailTemplate_Ids_arr));
        $this->MailTemplateI18n->deleteAll(array('mail_template_id' => $MailTemplateI18n_ids));
        $MailTemplate_Ids = substr($MailTemplate_Ids, 0, strlen($MailTemplate_Ids) - 1);

        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_email_template'].':'.$MailTemplate_Ids, $this->admin['id']);
        }
         $result['flag'] = 1;
        $result['message'] = $this->ld['delete_operator_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function install($code)
    {
        $node['config'] = 'node';
        $node['use'] = true;
        //echo $code;
        $tmp = $this->MailTemplate->find('first', array('cache' => $node, 'conditions' => array('MailTemplate.code' => $code)));
    //	var_dump($tmp);
        $save_file = array(
        'MailTemplate' => array(
            'code' => $tmp['MailTemplate']['code'],
            'status' => $tmp['MailTemplate']['status'],
            'type' => $tmp['MailTemplate']['type'],
            ),
        );
        //echo 1;
        //var_dump($save_file);
        $this->MailTemplate->save($save_file);
        $newid = $this->MailTemplate->id;
        //echo $newid;
        $save_file_i18n = array(
        'MailTemplateI18n' => array(
            'locale' => $tmp['MailTemplateI18n']['locale'],
            'mail_template_id' => $newid,
            'title' => $tmp['MailTemplateI18n']['title'],
            'description' => $tmp['MailTemplateI18n']['description'],
            'text_body' => $tmp['MailTemplateI18n']['text_body'],
            'html_body' => $tmp['MailTemplateI18n']['html_body'],
            ),
        );
        //var_dump($this->MailTemplateI18n->save($save_file_i18n));
        $this->MailTemplateI18n->save($save_file_i18n);
        $this->redirect('/mailtemplates/');
    }
    
        
    //邮件模板上传
public function mail_template_upload(){
	  Configure::write('debug', 0);
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/system/','sub' => '/mailtemplates/');
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['email_template'],'url' => '/mailtemplates/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
      $this->set('title_for_layout', $this->ld['bulk_upload'].' - '.$this->ld['email_template'].' - '.$this->ld['manage_system'].' - '.$this->configs['shop_name']);
      $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'mail_template_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//上传项目cvs查看
 public function mail_template_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/mailtemplates/mail_template_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'mail_template_export', 'Profile.status' => 1)));
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
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/mailtemplates/mail_template_upload';</script>";
						die();
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/mailtemplates/mail_template_upload';</script>";
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
                        $MailTemplate_first = $this->MailTemplate->find('first', array('conditions' => array('MailTemplate.code' => $v['MailTemplate']['code'])));
                        $v['MailTemplate']['id']=isset($MailTemplate_first['MailTemplate']['id'])?$MailTemplate_first['MailTemplate']['id']:0;
                        $v['MailTemplate']['type']='template';
                        	if( $s1=$this->MailTemplate->save($v['MailTemplate']) ){
                        		$MailTemplate_id=$this->MailTemplate->id;
                        	}
                        	$MailTemplateI18n_first = $this->MailTemplateI18n->find('first', array('conditions' => array('MailTemplateI18n.mail_template_id' =>$MailTemplate_id,'MailTemplateI18n.locale' => $v['MailTemplateI18n']['locale'])));
                        $v['MailTemplateI18n']['id']=isset($MailTemplateI18n_first['MailTemplateI18n']['id'])?$MailTemplateI18n_first['MailTemplateI18n']['id']:0;
                        $v['MailTemplateI18n']['mail_template_id']=isset($MailTemplate_id)?$MailTemplate_id:'';
                        	$s2=$this->MailTemplateI18n->save($v['MailTemplateI18n']);
                        	 if( isset($s1)&&!empty($s1)&&isset($s2)&&!empty($s2)){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                    }
                   
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/mailtemplates/mail_template_upload/'</script>";
		            die();
                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/mailtemplates/mail_template_upload/'</script>";
                    	
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



		 
//导出邮件模板csv
public function download_mail_template_csv_example($out_type = 'MailTemplate'){
 Configure::write('debug', 0);
     $this->layout="ajax";
     $this->MailTemplate->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'mail_template_export', 'Profile.status' => 1)));
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
          $MailTemplate_info = $this->MailTemplate->find('all', array('fields'=>array('MailTemplate.code','MailTemplate.status','MailTemplate.last_send','MailTemplate.type','MailTemplate.user_email_flag','MailTemplateI18n.locale','MailTemplateI18n.title','MailTemplateI18n.description','MailTemplateI18n.text_body','MailTemplateI18n.html_body','MailTemplateI18n.sms_body'),'order' => 'MailTemplate.id desc','limit'=>10));
	//	pr($MailTemplate_info);die();
         
            
              //循环数组
              foreach($MailTemplate_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
	              }
	              //pr($user_tmp);
	              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}


//全部导出xls
public function all_export_csv($out_type = 'MailTemplate'){
 Configure::write('debug', 0);
     $this->layout="ajax";
          $this->MailTemplate->set_locale($this->backend_locale);

     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'mail_template_export', 'Profile.status' => 1)));
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
          $MailTemplate_info = $this->MailTemplate->find('all', array('fields'=>array('MailTemplate.code','MailTemplate.status','MailTemplate.last_send','MailTemplate.type','MailTemplate.user_email_flag','MailTemplateI18n.locale','MailTemplateI18n.title','MailTemplateI18n.description','MailTemplateI18n.text_body','MailTemplateI18n.html_body','MailTemplateI18n.sms_body'),'order' => 'MailTemplate.id desc'));
	//	pr($MailTemplate_info);die();
         
            
              //循环数组
              foreach($MailTemplate_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
	              }
	              //pr($user_tmp);
	              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
        	exit;
      
}
 
    
 //选择导出xls
public function choice_export($out_type = 'MailTemplate'){
 Configure::write('debug', 0);
     $this->layout="ajax";
          $this->MailTemplate->set_locale($this->backend_locale);

     $MailTemplate_checkboxes = $_REQUEST['checkboxes'];
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'mail_template_export', 'Profile.status' => 1)));
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
          $MailTemplate_info = $this->MailTemplate->find('all', array('fields'=>array('MailTemplate.code','MailTemplate.status','MailTemplate.last_send','MailTemplate.type','MailTemplate.user_email_flag','MailTemplateI18n.locale','MailTemplateI18n.title','MailTemplateI18n.description','MailTemplateI18n.text_body','MailTemplateI18n.html_body','MailTemplateI18n.sms_body'),'order' => 'MailTemplate.id desc','conditions'=>array('MailTemplate.id'=>$MailTemplate_checkboxes)));
	//	pr($MailTemplate_info);die();
         
            
              //循环数组
              foreach($MailTemplate_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
	              }
	              //pr($user_tmp);
	              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
        	exit;
      
}   
    
    
}
