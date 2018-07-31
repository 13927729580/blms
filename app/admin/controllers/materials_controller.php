<?php

/*****************************************************************************
 * 材料管理
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id:
*****************************************************************************/
/**
 *这是一个名为MaterialsController的控制器
 *控制材料管理显示处理.
 *
 *@var
 *@var
 *@var
 *@var
 */
class MaterialsController extends AppController
{
    public $name = 'Materials';
    public $components = array('Pagination','RequestHandler','Phpexcel','Phpcsv');
    public $helpers = array('Html','Javascript','Form','Pagination');
    public $uses = array('Profile','ProfileFiled','Material','MaterialI18n');

    public function index($page = 1)
    {
        $this->operator_privilege('material_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/product/','sub' => '/materials/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['material_manage'],'url' => '/materials/');

        $condition = '';
        $name_keywords = '';     //关键字
        $code_keywords = '';
        $material_keywords = '';
        $condition = array('MaterialI18n.locale' => $this->backend_locale);
        //关键字
        if (isset($this->params['url']['material_keywords']) && $this->params['url']['material_keywords'] != '') {
            $material_keywords = $this->params['url']['material_keywords'];
            $condition['and']['or']['Material.code like'] = '%'.$material_keywords.'%';
            //$condition["and"]["or"]["Material.quantity like"] = $material_keywords;
            $condition['and']['or']['MaterialI18n.name like'] = '%'.$material_keywords.'%';
        }

        $total = $this->Material->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        $sortClass = 'Material';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->Material->set_locale($this->backend_locale);
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'materials','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Material');
        $this->Pagination->init($condition, $parameters, $options);

	$result = $this->Material->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'Material.orderby'));
	$this->set('material_keywords', $material_keywords);//关键字选中
	$this->set('result', $result);
	$this->set('title_for_layout', $this->ld['material_manage'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
	$this->Profile->hasOne = array();
           $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'material_export', 'Profile.status' => 1)));
		$this->set('profile_id',$profile_id);
    }

    public function view($id = 0)
    {
        if (empty($id)) {
            $this->operator_privilege('material_add');
        } else {
            $this->operator_privilege('material_edit');
        }
        $this->menu_path = array('root' => '/product/','sub' => '/materials/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['material_manage'],'url' => '/materials/');
        if ($this->RequestHandler->isPost()) {
            $this->data['Material']['orderby'] = !empty($this->data['Material']['orderby']) ? $this->data['Material']['orderby'] : 50;
            $this->data['Material']['quantity'] = !empty($this->data['Material']['quantity']) ? $this->data['Material']['quantity'] : 0;
            if (isset($this->data['Material']['id']) && $this->data['Material']['id'] != '') {
                $this->Material->save($this->data['Material']); //关联保存
                $id = $this->data['Material']['id'];
            } else {
                $this->Material->saveAll($this->data['Material']); //关联保存
                $id = $this->Material->getLastInsertId();
            }
            $this->MaterialI18n->deleteall(array('product_material_id' => $id)); //删除原有多语言
            foreach ($this->data['MaterialI18n'] as $v) {
                $materialI18n_info = array(
                    'locale' => $v['locale'],
                       'product_material_id' => $id,
                       'description' => $v['description'],
                      'name' => isset($v['name']) ? $v['name'] : '',
                );
                $this->MaterialI18n->saveAll(array('MaterialI18n' => $materialI18n_info));//更新多语言
            }
            $this->redirect('/materials/');
        }
        $this->data = $this->Material->localeformat($id);
        if (isset($this->data['MaterialI18n'][$this->backend_locale]['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].'-'.$this->data['MaterialI18n'][$this->backend_locale]['name'],'url' => '');
            $this->set('title_for_layout', $this->ld['edit'].'-'.$this->data['MaterialI18n'][$this->backend_locale]['name'].' - '.$this->configs['shop_name']);
        } else {
            $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
            $this->set('title_for_layout', $this->ld['add']." - ".$this->ld['material_manage'].' - '.$this->configs['shop_name']);
        }
        $this->set('Mater', $this->data);
    }

    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_failure'];
        $this->Material->deleteall(array('Material.id' => $id));
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

    public function batch_operations()
    {
        $material_checkboxes = $_REQUEST['checkboxes'];
        $material_Ids = '';
        foreach ($material_checkboxes as $k => $v) {
            $material_Ids = $material_Ids.$v.',';
            $this->Material->deleteAll(array('Material.id' => $v), false);
            $this->MaterialI18n->deleteAll(array('MaterialI18n.product_material_id' => $v));
        }
        if ($material_Ids != '') {
            $material_Ids = substr($material_Ids, 0, strlen($material_Ids) - 1);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_link'].':'.$material_Ids, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    
       //品牌管理上传
public function material_upload(){
	  Configure::write('debug', 0);
        $this->operation_return_url(true);//设置操作返回页面地址

        $this->menu_path = array('root' => '/product/','sub' => '/materials/');
        $this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['material_manage'],'url' => '/materials/');
           $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
           $this->set('title_for_layout', $this->ld['material_manage'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
           $this->Profile->hasOne = array();
           $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'material_export', 'Profile.status' => 1)));
		$this->set('profile_id',$profile_id);
	
    }



//品牌管理cvs查看
 public function material_uploadpreview()
    {
    	Configure::write('debug', 1);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/materials/material_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'material_export', 'Profile.status' => 1)));
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
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/materials/material_upload';</script>";
						die();
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/materials/material_upload';</script>";
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
                       
                        if( isset($v['Material']['code']) && $v['Material']['code']!="" ){
                        $Material_condition='';
                        
                        if(!empty($v['Material']['code'])){
                        	$Material_condition['Material.code']=$v['Material']['code'];
                        }
                       
                        //pr($ProductType_condition);
                        $Material_first = $this->Material->find('first', array('conditions' =>$Material_condition));
                        $v['Material']['id']=isset($Material_first['Material']['id'])?$Material_first['Material']['id']:0;
                        $v['Material']['orderby']=isset($v['Material']['orderby'])?$v['Material']['orderby']:50;
                        $v['Material']['status']=isset($v['Material']['status'])?$v['Material']['status']:1;
                        $v['Material']['quantity']=isset($v['Material']['quantity'])?$v['Material']['quantity']:0.00;
                        $v['Material']['frozen_quantity']=isset($v['Material']['frozen_quantity'])?$v['Material']['frozen_quantity']:0.00;
                        	if( $s1=$this->Material->save($v['Material']) ){
                        		$Material_id=$this->Material->id;
                        	}
                        $MaterialI18n_condition='';
                        if(isset($Material_id)){
                        	$MaterialI18n_condition['MaterialI18n.product_material_id']=$Material_id;
                        }
                       
                        if(isset($v['MaterialI18n']['locale']) && !empty($v['MaterialI18n']['locale'])){
                        	$MaterialI18n_condition['MaterialI18n.locale']= $v['MaterialI18n']['locale'];
                        }
                      	$MaterialI18n_first = $this->MaterialI18n->find('first', array('conditions' => $MaterialI18n_condition));
                        $v['MaterialI18n']['id']=isset($MaterialI18n_first['MaterialI18n']['id'])?$MaterialI18n_first['MaterialI18n']['id']:0;
                        $v['MaterialI18n']['product_material_id']=isset($Material_id)?$Material_id:'';
                        if(isset($v['MaterialI18n']['product_material_id']) && $v['MaterialI18n']['product_material_id']!=''){	$s2=$this->MaterialI18n->save($v['MaterialI18n']); }
                        	 if( isset($s1)&&!empty($s1)&&isset($s2)&&!empty($s2)){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                    }
                    }
                    //die();
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/materials/'</script>";
		            die();
                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/materials/material_upload/'</script>";
                    	
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



		 
//品牌管理csv
public function download_material_csv_example($out_type = 'Material'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     $this->Material->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'material_export', 'Profile.status' => 1)));
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
          $Material_info = $this->Material->find('all', array('fields'=>array('Material.code','Material.quantity','Material.frozen_quantity','Material.status','Material.unit','Material.orderby','MaterialI18n.locale','MaterialI18n.name','MaterialI18n.description'),'order' => 'Material.orderby','limit'=>10));
	//pr($Resource_info);die();
              //循环数组
              foreach($Material_info as $k=>$v){
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
public function all_export_csv($out_type = 'Material'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     $this->Material->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'material_export', 'Profile.status' => 1)));
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
          $Material_info = $this->Material->find('all', array('fields'=>array('Material.code','Material.quantity','Material.frozen_quantity','Material.status','Material.unit','Material.orderby','MaterialI18n.locale','MaterialI18n.name','MaterialI18n.description'),'order' => 'Material.orderby'));
	//pr($Resource_info);die();

            
              //循环数组
              foreach($Material_info as $k=>$v){
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
           $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
        	exit;
      
}  
    
 //选择导出   
public function choice_export($out_type = 'Material'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     $this->Material->set_locale($this->backend_locale);
     $user_checkboxes = $_REQUEST['checkboxes'];
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'material_export', 'Profile.status' => 1)));
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
          $Material_conditions='';
          $Material_conditions['AND']['Material.id']=$user_checkboxes; 
          $Material_info = $this->Material->find('all', array('fields'=>array('Material.code','Material.quantity','Material.frozen_quantity','Material.status','Material.unit','Material.orderby','MaterialI18n.locale','MaterialI18n.name','MaterialI18n.description'),'order' => 'Material.orderby','conditions'=>$Material_conditions));
	//pr($Resource_info);die();

            
              //循环数组
              foreach($Material_info as $k=>$v){
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
           $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
        	exit;
      
}  
    
}
