<?php

/*****************************************************************************
 * Seevia 物流公司管理控制器
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
 *这是一个名为 LogisticsCompaniesController 的控制器
 *后台物流公司管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class LogisticsCompaniesController extends AppController
{
    public $name = 'LogisticsCompanies';
    public $components = array('Pagination','RequestHandler','Phpexcel','Phpcsv'); // Added 
    public $helpers = array('Pagination'); // Added 
    public $uses = array('Profile','ProfileFiled','LogisticsCompany','Shipping','ShippingI18n','ShippingArea','ShippingAreaI18n','ShippingAreaRegion','OperatorLog','LogisticsMapping');

    /**
     *显示物流公司列表.
     */
    public function index($page = 1)
    {
        $this->operator_privilege('logistics_companies_view');
        $this->menu_path = array('root' => '/oms/','sub' => '/logistics_companies/');
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_logistics_company'],'url' => '');

        $condition = '';
        $sortClass = 'LogisticsCompany';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'logistics_companies','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'modelClass' => 'LogisticsCompany');
        $this->Pagination->init($condition, $parameters, $options);
        $data = $this->LogisticsCompany->find('all', array('page' => $page, 'limit' => $rownum, 'conditions' => $condition, 'order' => 'LogisticsCompany.id'));
        $this->set('LogisticsCompanies', $data);
        $this->set('title_for_layout', $this->ld['manage_logistics_company'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $this->Profile->hasOne = array();
           $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'logistics_company_export', 'Profile.status' => 1)));
		$this->set('profile_id',$profile_id);
        
    }

    /**
     *物流公司 新增/编辑.
     *
     *@param int $id 输入物流公司ID
     */
    public function view($id = 0)
    {
        if (empty($id)) {
            $this->operator_privilege('logistics_companies_add');
        } else {
            $this->operator_privilege('logistics_companies_edit');
        }
        $this->menu_path = array('root' => '/oms/','sub' => '/logistics_companies/');
        $this->set('title_for_layout', $this->ld['add_logistics_management'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['logistics_list'],'url' => '/logistics_companies/');
        $this->navigations[] = array('name' => $this->ld['edit_logistics_company'],'url' => '');
        
        if ($this->RequestHandler->isPost()) {
            $code = empty($this->params['form']['check']) ? array() : $this->params['form']['check'];
            $this->data['LogisticsCompany']['type'] = implode(';', $code);
            if (!empty($this->data)){
                if ($this->LogisticsCompany->save($this->data)) {
                    $logistics_company_id=$this->LogisticsCompany->id;
                    if(!empty($this->data['LogisticsMapping']['type'])){
                        foreach($this->data['LogisticsMapping']['type'] as $k=>$v){
                            $LogisticsMapping_data=array();
                            $LogisticsMapping_info=$this->LogisticsMapping->find('first',array('conditions'=>array('LogisticsMapping.logistics_company_id'=>$logistics_company_id,'LogisticsMapping.type'=>$v)));
                            $LogisticsMapping_data['id']=isset($LogisticsMapping_info['LogisticsMapping']['id'])?$LogisticsMapping_info['LogisticsMapping']['id']:0;
                            $LogisticsMapping_data['type']=$v;
                            $LogisticsMapping_data['logistics_company_id']=$logistics_company_id;
                            $LogisticsMapping_data['logistics_id']=isset($this->data['LogisticsMapping']['logistics_id'][$k])?$this->data['LogisticsMapping']['logistics_id'][$k]:'';
                            $this->LogisticsMapping->save($LogisticsMapping_data);
                        }
                    }
                    //操作员日志
                    if ($this->configs['operactions-log'] == 1) {
                        $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_logistics_company'].':id '.$logistics_company_id, $this->admin['id']);
                    }
                    $this->redirect(array('action' => 'index'));
                }
            }
        }
        $this->Shipping->set_locale($this->locale);
        $this->data = $this->LogisticsCompany->find('first', array('conditions' => array('id' => $id)));
        $this->navigations[] = array('name' => $this->data['LogisticsCompany']['name'],'url' => '');
        $app_sp = $this->Shipping->find('all', array('fields' => array('Shipping.code'), 'recursive' => -1));
        if (!empty($app_sp)) {
            foreach ($app_sp as $k => $v) {
                $aa[] = 'APP-DSP-'.strtoupper($v['Shipping']['code']);
                $app_groupby = $this->Application->find('first', array('conditions' => array('Application.code' => $aa)));
                if (empty($app_groupby['Application']['groupby'])) {
                    $appgroupby = 139;
                } else {
                    $appgroupby = $app_groupby['Application']['groupby'];
                }
                $this->set('app_groupby', $app_groupby['Application']['groupby']);
                $xx[] = $v['Shipping']['code'];
            }
        }
        
        $condition = array();
        $condition = array('Shipping.code' => $xx);
        $data = $this->Shipping->find('all', array('conditions' => $condition, 'order' => 'Shipping.created,Shipping.id'));
        $this->set('shippings', $data);
        
        /////物流公司配置
        $LogisticsMapping_list=array();
        $LogisticsMapping_list=$this->LogisticsMapping->find('list',array('fields'=>array('LogisticsMapping.type','LogisticsMapping.logistics_id'),'conditions'=>array('LogisticsMapping.logistics_company_id'=>$id)));
        $this->set('LogisticsMapping_list',$LogisticsMapping_list);
        
        $Resource_Info = $this->Resource->getformatcode(array('shop_channel'), $this->backend_locale);
        $this->set('Resource_Info',$Resource_Info);
    }

    /**
     *列表推荐修改.
     */
    public function toggle_on_status()
    {
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->LogisticsCompany->save(array('id' => $id, 'fettle' => $val))) {
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
     *删除一个物流公司.
     *
     *@param int $id 输入物流公司ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_logistics_companies_failure'];
        $this->LogisticsMapping->deleteAll(array('LogisticsMapping.logistics_company_id'=>$id));
        $this->LogisticsCompany->deleteAll(array('id' => $id));
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_logistics_companies_success'];
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_logistics_company'].':id '.$id, $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *删除多个物流公司.
     */
    public function delall()
    {
        $Company_id = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        $this->LogisticsMapping->deleteAll(array('LogisticsMapping.logistics_company_id'=>$Company_id));
        $condition['LogisticsCompany.id'] = $Company_id;
        $this->LogisticsCompany->deleteAll($condition);
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        $this->redirect(array('action' => 'index'));
        die();
    }
    
        //关键字管理上传
public function logistics_company_upload(){
	  Configure::write('debug', 0);
        $this->operation_return_url(true);//设置操作返回页面地址

         $this->menu_path = array('root' => '/oms/','sub' => '/logistics_companies/');
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_logistics_company'],'url' => '/logistics_companies/');
           $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
           $this->set('title_for_layout', $this->ld['manage_logistics_company'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
           $this->Profile->hasOne = array();
           $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'logistics_company_export', 'Profile.status' => 1)));
		$this->set('profile_id',$profile_id);
	
    }



//菜单管理cvs查看
 public function logistics_company_uploadpreview()
    {
    	Configure::write('debug', 1);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/logistics_companies/logistics_company_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'logistics_company_export', 'Profile.status' => 1)));
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
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/logistics_companies/logistics_company_upload';</script>";
						die();
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/logistics_companies/logistics_company_upload';</script>";
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
                        
                        if( isset($v['LogisticsCompany']['code']) && $v['LogisticsCompany']['code']!=""  ){
                        $LogisticsCompany_condition='';
                        	$LogisticsCompany_condition['LogisticsCompany.code']=$v['LogisticsCompany']['code'];
                        
                        $LogisticsCompany_first = $this->LogisticsCompany->find('first', array('conditions' =>$LogisticsCompany_condition));
                        $v['LogisticsCompany']['id']=isset($LogisticsCompany_first['LogisticsCompany']['id'])?$LogisticsCompany_first['LogisticsCompany']['id']:0;
                        $v['LogisticsCompany']['fettle']=isset($v['LogisticsCompany']['fettle'])&&$v['LogisticsCompany']['fettle']!=''?$v['LogisticsCompany']['fettle']:1;
                         if($s1=$this->LogisticsCompany->save($v['LogisticsCompany'])){
                         	$LogisticsCompany_id=$this->LogisticsCompany->id;
                         }
                         $LogisticsMapping_condition='';
                         if(isset($LogisticsCompany_id)){
                         	$LogisticsMapping_condition['LogisticsMapping.logistics_company_id']=$LogisticsCompany_id;
                         }
                          if(isset($v['LogisticsMapping']['type'])){
                         	$LogisticsMapping_condition['LogisticsMapping.type']=$v['LogisticsMapping']['type'];
                         }
                        $LogisticsMapping_first = $this->LogisticsMapping->find('first', array('conditions' =>$LogisticsMapping_condition));
                        $v['LogisticsMapping']['id']=isset($LogisticsMapping_first['LogisticsMapping']['id'])?$LogisticsMapping_first['LogisticsMapping']['id']:0;
                        $v['LogisticsMapping']['logistics_company_id']=isset($LogisticsCompany_id)?$LogisticsCompany_id:'';
                        $s2=$this->LogisticsMapping->save($v['LogisticsMapping']);
                        	 if( isset($s1) && !empty($s1) && isset($s2) && !empty($s2) ){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                    }
                    }
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/logistics_companies/'</script>";
		            die();
                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/logistics_companies/logistics_company_upload/'</script>";
                    	
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
public function download_logistics_company_csv_example($out_type = 'LogisticsCompany'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'logistics_company_export', 'Profile.status' => 1)));
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
          //pr($OpenKeyword_id_info);
	   $LogisticsCompany_info = $this->LogisticsCompany->find('all', array('fields'=>array('LogisticsCompany.id','LogisticsCompany.code','LogisticsCompany.express_code','LogisticsCompany.name','LogisticsCompany.contact_name','LogisticsCompany.contact_phone','LogisticsCompany.address','LogisticsCompany.hotline','LogisticsCompany.inquiry','LogisticsCompany.complaint','LogisticsCompany.website','LogisticsCompany.fettle','LogisticsCompany.type','LogisticsCompany.php_code'),'order'=>'LogisticsCompany.id desc','limit'=>10));
           $new_logistic_company_info=array();
          	foreach($LogisticsCompany_info as $k =>$v){
          			$LogisticsMapping_info=$this->LogisticsMapping->find('all',array('fields'=>array('LogisticsMapping.type','LogisticsMapping.logistics_id'),'conditions'=>array('LogisticsMapping.logistics_company_id'=>$v['LogisticsCompany']['id'])));
          		if( sizeof($LogisticsMapping_info)>0 ){
          			foreach($LogisticsMapping_info as $kk=>$vv){
          				$new_logistic_company_info[$k][$kk]['LogisticsMapping']=$vv['LogisticsMapping'];
          				$new_logistic_company_info[$k][$kk]['LogisticsCompany']=$v['LogisticsCompany'];
          			}
          		}else{
          			$new_logistic_company_info[$k][0]['LogisticsCompany']=$v['LogisticsCompany'];
          		
          		}
          	
          	}
          	//pr($OpenKeywordAnswer_info);die();
              //循环数组
              foreach($new_logistic_company_info as $keys=>$vals){
              foreach($vals as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                 
                                         $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]])?$v[$fields_ks[0]][$fields_ks[1]]:'';
	                  
	               
	              }
	              //pr($user_tmp);die();
	              $newdatas[] = $user_tmp;
	          }
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}
//全部导出   
public function all_export_csv($out_type = 'LogisticsCompany'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'logistics_company_export', 'Profile.status' => 1)));
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
  //	pr($fields_array);
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
	      $LogisticsCompany_info = $this->LogisticsCompany->find('all', array('fields'=>array('LogisticsCompany.id','LogisticsCompany.code','LogisticsCompany.express_code','LogisticsCompany.name','LogisticsCompany.contact_name','LogisticsCompany.contact_phone','LogisticsCompany.address','LogisticsCompany.hotline','LogisticsCompany.inquiry','LogisticsCompany.complaint','LogisticsCompany.website','LogisticsCompany.fettle','LogisticsCompany.type','LogisticsCompany.php_code'),'order'=>'LogisticsCompany.id desc'));
           $new_logistic_company_info=array();
          	foreach($LogisticsCompany_info as $k =>$v){
          			$LogisticsMapping_info=$this->LogisticsMapping->find('all',array('fields'=>array('LogisticsMapping.type','LogisticsMapping.logistics_id'),'conditions'=>array('LogisticsMapping.logistics_company_id'=>$v['LogisticsCompany']['id'])));
          		if( sizeof($LogisticsMapping_info)>0 ){
          			foreach($LogisticsMapping_info as $kk=>$vv){
          				$new_logistic_company_info[$k][$kk]['LogisticsMapping']=$vv['LogisticsMapping'];
          				$new_logistic_company_info[$k][$kk]['LogisticsCompany']=$v['LogisticsCompany'];
          			}
          		}else{
          			$new_logistic_company_info[$k][0]['LogisticsCompany']=$v['LogisticsCompany'];
          		}
          	}
            //pr($new_logistic_company_info);die();
              //循环数组
              foreach($new_logistic_company_info as $keys=>$vals){
              foreach($vals as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
                          $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
	              }
	              $newdatas[] = $user_tmp;
	         }
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
        	die();
      
}  
    
 //选择导出   
public function choice_export($out_type = 'LogisticsCompany'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     $user_checkboxes = $_REQUEST['checkboxes'];
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'logistics_company_export', 'Profile.status' => 1)));
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
          $LogisticsCompany_conditions['AND']['LogisticsCompany.id']=$user_checkboxes; 
 $LogisticsCompany_info = $this->LogisticsCompany->find('all', array('fields'=>array('LogisticsCompany.id','LogisticsCompany.code','LogisticsCompany.express_code','LogisticsCompany.name','LogisticsCompany.contact_name','LogisticsCompany.contact_phone','LogisticsCompany.address','LogisticsCompany.hotline','LogisticsCompany.inquiry','LogisticsCompany.complaint','LogisticsCompany.website','LogisticsCompany.fettle','LogisticsCompany.type','LogisticsCompany.php_code'),'conditions'=>$LogisticsCompany_conditions,'order'=>'LogisticsCompany.id desc'));
           $new_logistic_company_info=array();
          	foreach($LogisticsCompany_info as $k =>$v){
          			$LogisticsMapping_info=$this->LogisticsMapping->find('all',array('fields'=>array('LogisticsMapping.type','LogisticsMapping.logistics_id'),'conditions'=>array('LogisticsMapping.logistics_company_id'=>$v['LogisticsCompany']['id']) ));
          		if( sizeof($LogisticsMapping_info)>0 ){
          			foreach($LogisticsMapping_info as $kk=>$vv){
          				$new_logistic_company_info[$k][$kk]['LogisticsMapping']=$vv['LogisticsMapping'];
          				$new_logistic_company_info[$k][$kk]['LogisticsCompany']=$v['LogisticsCompany'];
          			}
          		}else{
          			$new_logistic_company_info[$k][0]['LogisticsCompany']=$v['LogisticsCompany'];
          		
          		}
          	
          	}
           // pr($new_logistic_company_info);die();
              //循环数组
              foreach($new_logistic_company_info as $keys=>$vals){
              foreach($vals as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
                                         $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
	              }
	              $newdatas[] = $user_tmp;
          }
	   }
          //定义文件名称
   //      pr($newdatas);die();
    //        $newdatas=array();
           $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
//           pr($this->Phpexcel);
        	die();
      
}  
    
}