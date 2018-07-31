<?php

/*****************************************************************************
 * Seevia 用户管理
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
class NewsletterListsController extends AppController
{
    public $name = 'NewsletterLists';
    public $components = array('Pagination','RequestHandler','Email','Phpexcel','Phpcsv');
    public $helpers = array('Pagination','Html','Form');
    public $uses = array('NewsletterList','Resource','OperatorLog','UserGroup','Profile','ProfileFiled');
    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('newsletter_lists_view');
        /*end*/
        $this->menu_path = array('root' => '/crm/','sub' => '/email_lists/');
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['subscription'],'url' => '/email_lists/');
        $this->navigations[] = array('name' => $this->ld['magazine_user'],'url' => '/newsletter_lists/');

        $condition = '';
        if (isset($this->params['url']['email']) && $this->params['url']['email'] != '') {
            $condition['NewsletterList.email like'] = '%'.$this->params['url']['email'].'%';
            $this->set('email', $this->params['url']['email']);
        }
        if (isset($this->params['url']['group_id']) && $this->params['url']['group_id'] != '') {
            $condition['NewsletterList.group_id'] = $this->params['url']['group_id'];
            $this->set('group_id', $this->params['url']['group_id']);
        }
        if (isset($this->params['url']['mystatus']) && $this->params['url']['mystatus'] != '') {
            $condition['NewsletterList.status'] = $this->params['url']['mystatus'];
            $this->set('mystatus', $this->params['url']['mystatus']);
        }
        if (isset($this->params['url']['date']) && $this->params['url']['date'] != '') {
            $condition['NewsletterList.created  >='] = $this->params['url']['date'];
            $this->set('date', $this->params['url']['date']);
        }
        if (isset($this->params['url']['date2']) && $this->params['url']['date2'] != '') {
            $condition['NewsletterList.created  <='] = $this->params['url']['date2'].' 23:59:59';
            $this->set('date2', $this->params['url']['date2']);
        }
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $total = count($this->NewsletterList->find('all', array('conditions' => $condition, 'fields' => 'DISTINCT NewsletterList.id')));
        $sortClass = 'NewsletterList';
        $rownum = isset($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'newsletter_lists','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'NewsletterList');
        $this->Pagination->init($condition, $parameters, $options);
        $newsletterlist_data = $this->NewsletterList->find('all', array('conditions' => $condition, 'limit' => $rownum, 'page' => $page));
        $group_list = $this->UserGroup->find('list', array('conditions' => array('UserGroup.status' => 1), 'fields' => 'UserGroup.id,UserGroup.name'));
        if (empty($newsletterlist_data) && $page > 1) {
            $this->redirect('/newsletter_lists/');
        }
        foreach ($newsletterlist_data as $nk => $nv) {
            foreach ($group_list as $gk => $gv) {
                if ($nv['NewsletterList']['group_id'] == $gk) {
                    $newsletterlist_data[$nk]['NewsletterList']['group'] = $gv;
                }
            }
        }
        //资源库信息
        $this->Resource->set_locale($this->locale);
        $Resource_info = $this->Resource->getformatcode('newsletter_lis', $this->locale, false);
        $this->set('Resource_info', $Resource_info);
        $this->set('group_list', $group_list);//绑定分组搜索下拉
        $this->set('newsletterlist_data', $newsletterlist_data);
        $this->set('title_for_layout', $this->ld['magazine_user'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        
          $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'newsletter_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }
    /**
     *定时器编辑/新增.
     *
     *@param int $id 输入定时器ID，新增时不传
     */
    public function view($id = 0)
    {
        $this->menu_path = array('root' => '/crm/','sub' => '/email_lists/');
        $group_list = $this->UserGroup->find('list', array('conditions' => array('UserGroup.status' => 1), 'fields' => 'UserGroup.id,UserGroup.name'));
        $this->set('group_list', $group_list);
        if (empty($id)) {
            $this->operator_privilege('newsletter_add');
            $this->set('title_for_layout', $this->ld['add'].'- '.$this->ld['magazine_user'].' - '.$this->configs['shop_name']);
            $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['subscription'],'url' => '/email_lists/');
            $this->navigations[] = array('name' => $this->ld['magazine_user'],'url' => '/newsletter_lists/');
            if ($this->RequestHandler->isPost()) {
                $this->data['NewsletterList']['email'] = !empty($this->data['NewsletterList']['email']) ? $this->data['NewsletterList']['email'] : '';//email
            $this->data['NewsletterList']['mobile'] = !empty($this->data['NewsletterList']['mobile']) ? $this->data['NewsletterList']['mobile'] : '';//手机
            //checkbox数据处理
            $this->data['NewsletterList']['status'] = !empty($this->data['NewsletterList']['status']) ? $this->data['NewsletterList']['status'] : '0';//有效状态*/
            $this->NewsletterList->save(array('NewsletterList' => $this->data['NewsletterList']));
            //操作员日志

            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 新增订阅'.$this->ld['vip'], $this->admin['id']);
            }
                $this->redirect('/newsletter_lists/');
            }
        } else {
            $this->operator_privilege('newsletter_lists_edit');
            $this->set('title_for_layout', $this->ld['edit'].'-'.$this->ld['magazine_user'].' - '.$this->configs['shop_name']);
            $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['subscription'],'url' => '/email_lists/');
            $this->navigations[] = array('name' => $this->ld['magazine_user'],'url' => '/newsletter_lists/');
            $cronjob_info = $this->NewsletterList->find('first', array('conditions' => array('NewsletterList.id' => $id)));
            $this->set('cronjob_info', $cronjob_info);
            if ($this->RequestHandler->isPost()) {
                //pr($this->data["NewsletterList"]);die;
                $this->NewsletterList->save(array('NewsletterList' => $this->data['NewsletterList']));
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 编辑订阅'.$this->ld['vip'].' id:'.$id, $this->admin['id']);
                }
                $this->redirect('/newsletter_lists/');
            }
        }
    }
    public function export()
    {
        $condition = '';
        if (isset($this->params['url']['group_id']) && $this->params['url']['group_id'] != '') {
            $condition['NewsletterList.group_id'] = $this->params['url']['group_id'];
            $this->set('group_id', $this->params['url']['group_id']);
        }
        if (isset($this->params['url']['email']) && $this->params['url']['email'] != '') {
            $condition['NewsletterList.email like'] = '%'.$this->params['url']['email'].'%';
            $this->set('email', $this->params['url']['email']);
        }
        if (isset($this->params['url']['date']) && $this->params['url']['date'] != '') {
            $condition['NewsletterList.created  >='] = $this->params['url']['date'];
            $this->set('date', $this->params['url']['date']);
        }
        if (isset($this->params['url']['date2']) && $this->params['url']['date2'] != '') {
            $condition['NewsletterList.created  <='] = $this->params['url']['date2'].' 23:59:59';
            $this->set('date2', $this->params['url']['date2']);
        }
        //pr($condition);die;
        $newsletterlist_data = $this->NewsletterList->find('all', array('conditions' => $condition));
        //pr($newsletterlist_data);die();
        $UserGroup_list=$this->UserGroup->find('list',array('fields'=>array('id','name'),'order'=>'UserGroup.id desc'));
        $out = '邮箱,手机,用户分组'."\n";
        foreach ($newsletterlist_data as $key => $val) {
        	$val_group_id=isset($UserGroup_list[$val['NewsletterList']['group_id']])?$UserGroup_list[$val['NewsletterList']['group_id']]:'';
            $out .= $val['NewsletterList']['email'].',';
            $out .= $val['NewsletterList']['mobile'].',';
            $out .= $val_group_id."\n";
        }
        header('Content-type: application/vnd.ms-excel;charset=gbk');
        header('Content-Disposition: attachment; filename=email_list.csv');
        echo iconv('utf-8', 'gbk//IGNORE', $out."\n");
        Configure::write('debug', 1);
        exit;
    }
    public function change_status($status)
    {
        foreach ($_REQUEST['checkboxes'] as $k => $v) {
            if ($status == 'unsubscribe') {
                $order_info = array(
                    'status' => '2',
                    'id' => $v,
                );

                $this->NewsletterList->save($order_info);
            }
            if ($status == 'remove') {
                $this->NewsletterList->deleteAll(array('NewsletterList.id' => $v));
            }
            if ($status == 'confirm') {
                $order_info = array(
                    'status' => '1',
                    'id' => $v,
                );

                $this->NewsletterList->save($order_info);
            }
        }
        //$this->flash("邮件订阅操作成功，点击这里返回列表页。",'/newsletter_lists/',10);
        $this->redirect('/newsletter_lists/');
    }
    public function unsubscribe($id)
    {
        $order_info = array(
            'status' => '2',
            'id' => $id,
            );
        $this->NewsletterList->save($order_info);
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 取消订阅:id '.$id, $this->admin['id']);
        }
        $this->redirect('/newsletter_lists/');
    }
    public function confirm($id)
    {
        $order_info = array(
            'status' => '1',
            'id' => $id,
            );
        $this->NewsletterList->save($order_info);
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 确认订阅:id '.$id, $this->admin['id']);
        }
        $this->redirect('/newsletter_lists/');
    }
    public function remove($id)
    {
        $this->NewsletterList->deleteAll(array('NewsletterList.id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' 删除订阅用户:id '.$id, $this->admin['id']);
        }
        $this->redirect('/newsletter_lists/');
    }



//全部导出   
public function all_export_csv($out_type = '杂志订阅用户导出'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     //$this->Newsletter->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'newsletter_export', 'Profile.status' => 1)));
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
          $NewsletterList_info = $this->NewsletterList->find('all', array('fields'=>array('NewsletterList.email','NewsletterList.mobile','NewsletterList.status','NewsletterList.group_id'),'order' => 'NewsletterList.id  desc'));
	//pr($Resource_info);die();

            
              //循环数组
              foreach($NewsletterList_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	              $UserGroup_list=$this->UserGroup->find('list',array('fields'=>array('id','name'),'order'=>'UserGroup.id desc'));
	                 if($fields_ks[1]=='group_id'){
	                 		$user_tmp[] =isset($UserGroup_list[$v['NewsletterList']['group_id']])?$UserGroup_list[$v['NewsletterList']['group_id']]:'';
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
public function choice_export($out_type = '杂志订阅用户导出'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     //$this->Newsletter->set_locale($this->backend_locale);
     $user_checkboxes = $_REQUEST['checkboxes'];
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'newsletter_export', 'Profile.status' => 1)));
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
          $Newsletter_conditions='';
          $Newsletter_conditions['AND']['NewsletterList.id']=$user_checkboxes; 
          $NewsletterList_info = $this->NewsletterList->find('all', array('fields'=>array('NewsletterList.email','NewsletterList.mobile','NewsletterList.status','NewsletterList.group_id'),'order' => 'NewsletterList.id desc','conditions'=>$Newsletter_conditions));
	//pr($Resource_info);die();

            
              //循环数组
              foreach($NewsletterList_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                
                           $UserGroup_list=$this->UserGroup->find('list',array('fields'=>array('id','name'),'order'=>'UserGroup.id desc'));
	                 if($fields_ks[1]=='group_id'){
	                 		$user_tmp[] =isset($UserGroup_list[$v['NewsletterList']['group_id']])?$UserGroup_list[$v['NewsletterList']['group_id']]:'';
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

  
  
   
   
    
    
    
           //订阅用户配置管理上传
public function newsletter_list_upload(){
	  Configure::write('debug', 1);
        $this->operation_return_url(true);//设置操作返回页面地址

        $this->menu_path = array('root' => '/crm/','sub' => '/email_lists/');
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['subscription'],'url' => '/email_lists/');
        $this->navigations[] = array('name' => $this->ld['magazine_user'],'url' => '/newsletter_lists/');
           $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
           $this->set('title_for_layout', $this->ld['magazine_user'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
           $this->Profile->hasOne = array();
           $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'newsletter_export', 'Profile.status' => 1)));
		$this->set('profile_id',$profile_id);
	
    }



//菜单管理cvs查看
 public function newsletter_list_uploadpreview()
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
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'newsletter_export', 'Profile.status' => 1)));
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
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/newsletter_lists/newsletter_list_upload';</script>";
						die();
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/newsletter_lists/newsletter_list_upload';</script>";
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
                        
                        if( isset($v['NewsletterList']['email']) && $v['NewsletterList']['email']!="" ){
                        $NewsletterList_condition='';
                        $NewsletterList_condition['NewsletterList.email']=$v['NewsletterList']['email'];

                        $NewsletterList_first = $this->NewsletterList->find('first', array('conditions' =>$NewsletterList_condition));
                        $UserGroup_list=$this->UserGroup->find('list',array('fields'=>array('name','id'),'order'=>'UserGroup.id desc'));
                        $v['NewsletterList']['id']=isset($NewsletterList_first['NewsletterList']['id'])?$NewsletterList_first['NewsletterList']['id']:0;
                        $v['NewsletterList']['status']=isset($v['NewsletterList']['status'])&& $v['NewsletterList']['status']!=''?$v['NewsletterList']['status']:1;
                        $v['NewsletterList']['group_id']=isset($UserGroup_list[$v['NewsletterList']['group_id']])?$UserGroup_list[$v['NewsletterList']['group_id']]:'';
                       
                         $s=$this->NewsletterList->save($v['NewsletterList']);
                         
                         
                        	 if( isset($s) && !empty($s) ){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                    }
                    }
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/newsletter_lists/'</script>";
		            die();
                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/newsletter_lists/newsletter_list_upload/'</script>";
                    	
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



		 
//订阅用户csv
public function download_newsletter_list_csv_example($out_type = 'NewsletterList'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     //$this->NewsletterList->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'newsletter_export', 'Profile.status' => 1)));
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
	   $NewsletterList_info = $this->NewsletterList->find('all', array('fields'=>array('NewsletterList.email','NewsletterList.mobile','NewsletterList.status','NewsletterList.group_id'),'order'=>'NewsletterList.id desc','limit'=>10));
              //循环数组
              foreach($NewsletterList_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                  $UserGroup_list=$this->UserGroup->find('list',array('fields'=>array('id','name'),'order'=>'UserGroup.id desc'));
	                 if($fields_ks[1]=='group_id'){
	                 		$user_tmp[] =isset($UserGroup_list[$v['NewsletterList']['group_id']])?$UserGroup_list[$v['NewsletterList']['group_id']]:'';
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

}
