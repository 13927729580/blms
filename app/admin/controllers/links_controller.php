<?php

/*****************************************************************************
 * Seevia 外部链接
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
 *这是一个名为 LinksController 的控制器
 *后台友情链接控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class LinksController extends AppController
{
    public $name = 'Links';
    public $helpers = array('Html','Pagination');
    public $components = array('Phpexcel','Phpcsv','Pagination','RequestHandler');
    public $uses = array('Profile','ProfileFiled','Link','LinkI18n','Resource','OperatorLog');

    /**
     *显示友情链接列表.
     */
    public function index($page = 1)
    {
        $this->operator_privilege('links_view');
        $this->menu_path = array('root' => '/cms/','sub' => '/links/');
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['links'],'url' => '');

        //$this->Link->set_locale($this->backend_locale);
        $condition = array('LinkI18n.locale' => $this->backend_locale);
        $total = $this->Link->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'links','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Link');
        $this->Pagination->init($condition, $parameters, $options);
        $data = $this->Link->find('all', array('page' => $page, 'limit' => $rownum, 'conditions' => $condition, 'order' => 'Link.orderby,Link.created,Link.id'));
        $this->set('links', $data);
        $this->set('title_for_layout', $this->ld['links'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
         $this->Profile->hasOne = array();
           $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'link_export', 'Profile.status' => 1)));
		$this->set('profile_id',$profile_id);
    }

    /**
     *友情链接 新增/编辑.
     *
     *@param int $id 输入友情链接ID
     */
    public function view($id = 0)
    {
        if (empty($id)) {
            $this->operator_privilege('links_add');
        } else {
            $this->operator_privilege('links_edit');
        }
        $this->menu_path = array('root' => '/cms/','sub' => '/links/');
        $this->set('title_for_layout', $this->ld['add_edit_links_links'].' - '.$this->ld['links'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['link_list'],'url' => '/links/');
        if ($this->RequestHandler->isPost()) {
            $this->data['Link']['orderby'] = isset($this->data['Link']['orderby']) && $this->data['Link']['orderby'] != '' ? $this->data['Link']['orderby'] : 50;
            if (isset($this->data['Link']['id']) && $this->data['Link']['id'] != '') {
                $this->Link->save($this->data['Link']); //关联保存
                $id = $this->data['Link']['id'];
            } else {
                $this->Link->saveAll($this->data['Link']); //关联保存
                $id = $this->Link->getLastInsertId();
            }
            $this->LinkI18n->deleteall(array('link_id' => $id)); //删除原有多语言
            foreach ($this->data['LinkI18n'] as $v) {
                $linkI18n_info = array(
                      'locale' => $v['locale'],
                       'link_id' => $id,
                      'name' => isset($v['name']) ? $v['name'] : '',
                       'url' => $v['url'],
                      'description' => $v['description'],
                    'img01' => $v['img01'],
                );
                $this->LinkI18n->saveAll(array('LinkI18n' => $linkI18n_info));//更新多语言
            }
            foreach ($this->data['LinkI18n'] as $k => $v) {
                if ($v['locale'] == $this->backend_locale) {
                    $userinformation_name = $v['name'];
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_edite_link'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $this->redirect('/links/');
        }
        $link = $this->Link->localeformat($id);
        $this->set('link', $link);
        if (isset($link['LinkI18n'][$this->backend_locale]['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].'-'.$link['LinkI18n'][$this->backend_locale]['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_link'],'url' => '');
        }

        $Resource_info = $this->Resource->getformatcode(array('link_type'), $this->backend_locale);
        $this->set('link_type', !empty($Resource_info['link_type']) ? $Resource_info['link_type'] : array());
    }

    /**
     *友情链接列表更新名称.
     */
    public function update_link_name()
    {
        $this->Link->hasMany = array();
        $this->Link->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->LinkI18n->updateAll(
            array('name' => "'".$val."'"),
            array('link_id' => $id, 'locale' => $this->backend_locale)
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
     *友情链接列表更新超链.
     */
    public function update_link_url()
    {
        $this->Link->hasMany = array();
        $this->Link->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->LinkI18n->updateAll(
            array('url' => "'".$val."'"),
            array('link_id' => $id, 'locale' => $this->backend_locale)
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
     *友情链接列表更新超链.
     */
    public function update_link_orderby()
    {
        $this->Link->hasMany = array();
        $this->Link->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_correct_sort'];
        }
        if (is_numeric($val) && $this->Link->save(array('id' => $id, 'orderby' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *友情链接列表推荐修改.
     */
    public function toggle_on_status()
    {
        $this->Link->hasMany = array();
        $this->Link->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Link->save(array('id' => $id, 'status' => $val))) {
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

    /**
     *删除一个友情链接.
     *
     *@param $id 输入友情链接ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_failure'];
        $this->Link->deleteall(array('Link.id' => $id));
        $this->LinkI18n->deleteall(array('LinkI18n.link_id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_link'].':id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /*
        批量删除
    */
    public function removeall()
    {
        $link_checkboxes = $_REQUEST['checkboxes'];
        $link_Ids = '';
        foreach ($link_checkboxes as $k => $v) {
            $link_Ids = $link_Ids.$v.',';
            $this->Link->deleteAll(array('Link.id' => $v), false);
            $this->LinkI18n->deleteAll(array('LinkI18n.link_id' => $v), false);
        }
        if ($link_Ids != '') {
            $link_Ids = substr($link_Ids, 0, strlen($link_Ids) - 1);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_link'].':'.$link_Ids, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }
    
    public function ajax_modified_link(){
    		Configure::write('debug', 1);
        	$this->layout = 'ajax';
        	
        	$result=array();
        	$result['flag']='0';
        	$result['content']='0';
        	if ($this->RequestHandler->isPost()) {
        		if(isset($this->data['LinkI18n']['name'])){
        			$link_id=isset($this->data['Link']['id'])?$this->data['Link']['id']:0;
        			$this->Link->set_locale($this->backend_locale);
				$link_info=$this->Link->findById($link_id);
				$link_data=array(
					'id'=>$link_id,
					'status'=>isset($link_info['Link'])?$link_info['Link']['status']:'1'
				);
				$this->Link->save($link_data);
				$link_id=$this->Link->id;
				$link_i18n_data=$this->data['LinkI18n'];
				$link_i18n_data['id']=isset($link_info['LinkI18n'])?$link_info['LinkI18n']['id']:0;
				$link_i18n_data['link_id']=$link_id;
				$link_i18n_data['locale']=$this->backend_locale;
				$this->LinkI18n->save($link_i18n_data);
				
				$result['flag']='1';
        			$result['content']=$link_i18n_data['name'];
        			$result['last_id']=$link_id;
        		}
        	}
        	die(json_encode($result));
    }
    
    //外部链接上传
public function link_upload(){
	  Configure::write('debug', 0);
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        /*end*/

        $this->navigations[] = array('name' => $this->ld['manager_interface'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['links'],'url' => '/links/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
      $this->set('title_for_layout', $this->ld['bulk_upload'].' - '.$this->ld['links'].' - '.$this->ld['manager_interface'].' - '.$this->configs['shop_name']);
            $this->Profile->hasOne = array();
           $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'link_export', 'Profile.status' => 1)));
		$this->set('profile_id',$profile_id);
    }



//外部链接cvs查看
 public function link_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/links/link_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'link_export', 'Profile.status' => 1)));
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
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/links/link_upload';</script>";
						die();
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/links/link_upload';</script>";
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
			//pr($this->data);die();
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        if(isset($v['Link']['type'])&&$v['Link']['type']==''){$v['Link']['type']=0;}
                        if(!empty($v['LinkI18n']['url'])){
                        $Link_first = $this->Link->find('first', array('fields'=>array('Link.id'),'conditions' => array('LinkI18n.url' => $v['LinkI18n']['url'],'Link.type' => $v['Link']['type'])));
                        $v['Link']['id']=isset($Link_first['Link']['id'])?$Link_first['Link']['id']:0;
                        if($s1=$this->Link->save($v['Link'])){
                        	$link_id=$this->Link->id;
                        }
                        
                        
                   	$LinkI18n_first = $this->LinkI18n->find('first', array('conditions' => array('LinkI18n.link_id' => $link_id)));
				$v['LinkI18n']['id']=isset($LinkI18n_first['LinkI18n']['id'])?$LinkI18n_first['LinkI18n']['id']:0;
                        $v['LinkI18n']['link_id']=isset($link_id)?$link_id:'';
                         $s2=$this->LinkI18n->save($v['LinkI18n']);
                       
                        	 if( isset($s1)&&!empty($s1)&&isset($s2)&&!empty($s2)){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                     	}
                    }
                   
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/links/link_upload/'</script>";
		            die();
                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/links/link_upload/'</script>";
                    	
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



		 
//外部链接csv
public function download_link_csv_example($out_type = 'Link'){
 Configure::write('debug', 0);
     $this->layout="ajax";
          $this->Link->set_locale($this->backend_locale);

     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'link_export', 'Profile.status' => 1)));
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
          $Link_info = $this->Link->find('all', array('fields'=>array('Link.type','Link.contact_name','Link.contact_email','Link.contact_tele','Link.orderby','Link.status','Link.target','LinkI18n.locale','LinkI18n.name','LinkI18n.description','LinkI18n.url','LinkI18n.img01','LinkI18n.img02'),'order' => 'Link.id desc','limit'=>10));
	//	pr($Link_info);die();
         
            
              //循环数组
              foreach($Link_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
	                  }
	                 
	              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}


//全部导出xls
public function all_export_csv($out_type = 'Link'){
 Configure::write('debug', 1);
     $this->layout="ajax";
          $this->Link->set_locale($this->backend_locale);

     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'link_export', 'Profile.status' => 1)));
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
          $Link_info = $this->Link->find('all', array('fields'=>array('Link.type','Link.contact_name','Link.contact_email','Link.contact_tele','Link.orderby','Link.status','Link.target','LinkI18n.locale','LinkI18n.name','LinkI18n.description','LinkI18n.url','LinkI18n.img01','LinkI18n.img02'),'order' => 'Link.id desc'));
	//	pr($Link_info);die();
         
            
              //循环数组
              foreach($Link_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
	                  }
	                 
	              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
        	exit;
      
}


//选择导出xls
public function choice_export($out_type = 'Link'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     $this->Link->set_locale($this->backend_locale);
     $link_checkboxes = $_REQUEST['checkboxes'];
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'link_export', 'Profile.status' => 1)));
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
          $Link_info = $this->Link->find('all', array('fields'=>array('Link.type','Link.contact_name','Link.contact_email','Link.contact_tele','Link.orderby','Link.status','Link.target','LinkI18n.locale','LinkI18n.name','LinkI18n.description','LinkI18n.url','LinkI18n.img01','LinkI18n.img02'),'order' => 'Link.id desc','conditions'=>array('Link.id'=>$link_checkboxes)));
	//	pr($Link_info);die();
         
            
              //循环数组
              foreach($Link_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
	                  }
	                 
	              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
        	exit;
      
}



}
