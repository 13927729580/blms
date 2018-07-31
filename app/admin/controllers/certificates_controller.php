<?php
/*****************************************************************************
 * Seevia 证书
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/*
 *这是一个名为 CertificatesController 的控制器
 *文章控制器.

*/
class CertificatesController extends AppController{
	public $name = 'Certificates';
	public $components = array('Pagination','Phpcsv');
	public $uses = array('InformationResource','Certificate','Profile','ProfileFiled');
	public $helpers = array('Pagination');
	
	function index($page=1){
		$this->operator_privilege('certificate_view');
		$this->pageTitle = '证书查询 - '.$this->configs['shop_title'];
		$this->navigations[] = array('name' => '证书','url' => '');
        	$this->navigations[] = array('name' => '证书查询','url' => '/certificates/');
		
		$condition = '';
		if (isset($this->params['url']['user_keywords']) && $this->params['url']['user_keywords'] != '') {
			$condition['Certificate.name like'] = '%'.$this->params['url']['user_keywords'].'%';
			$this->set('user_keywords', $this->params['url']['user_keywords']);
		}
		if (isset($this->params['url']['identity_no']) && $this->params['url']['identity_no'] != '') {
			$condition['Certificate.identity_no like'] = '%'.$this->params['url']['identity_no'].'%';
			$this->set('identity_no', $this->params['url']['identity_no']);
		}
		if (isset($this->params['url']['certificate_type']) && $this->params['url']['certificate_type'] != '') {
			$condition['Certificate.type'] = $this->params['url']['certificate_type'];
			$this->set('certificate_type', $this->params['url']['certificate_type']);
		}
		if (isset($this->params['url']['certificate_number']) && $this->params['url']['certificate_number'] != '') {
			$condition['Certificate.certificate_number like'] = "%".$this->params['url']['certificate_number']."%";
			$this->set('certificate_number', $this->params['url']['certificate_number']);
		}
		if (isset($this->params['url']['register_date_start']) && $this->params['url']['register_date_start'] != '') {
			$condition['Certificate.register_date >='] = $this->params['url']['register_date_start'];
			$this->set('register_date_start', $this->params['url']['register_date_start']);
		}
		if (isset($this->params['url']['register_date_end']) && $this->params['url']['register_date_end'] != '') {
			$condition['Certificate.register_date <='] = $this->params['url']['register_date_end'];
			$this->set('register_date_end', $this->params['url']['register_date_end']);
		}
		$total = $this->Certificate->find('count', array('conditions'=>$condition));
		$this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
		if (isset($_GET['page']) && $_GET['page'] != '') {
			$page = $_GET['page'];
		}
		$this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
		$rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'certificates','action' => 'index','page' => $page,'limit' => $rownum);
		$options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Certificate');
		$this->Pagination->init($condition, $parameters, $options);
		$certificate_infos = $this->Certificate->find('all', array('conditions' => $condition, 'order' => 'Certificate.certificate_number,Certificate.register_date desc', 'limit' => $rownum, 'page' => $page));
		$this->set('certificate_infos',$certificate_infos);
		
		$informationresource_info = $this->InformationResource->information_formated(array('certificatetype'), $this->backend_locale);
        	$this->set('informationresource_info', $informationresource_info);
        	
		$this->set('title_for_layout', '证书查询 - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
	}
	
	function view($id=0){
		if(empty($id)){
			$this->operator_privilege('certificate_add');
		}else{
			$this->operator_privilege('certificate_edit');
		}
		$this->pageTitle = '证书添加/编辑 - '.$this->configs['shop_title'];
		$this->navigations[] = array('name' => '证书','url' => '');
        	$this->navigations[] = array('name' => '证书查询','url' => '/certificates/');
        	$this->navigations[] = array('name' => '证书','url' => '证书添加/编辑');
        	
        	if ($this->RequestHandler->isPost()) {
        		if(isset($this->data['Certificate'])){
        			$this->Certificate->save($this->data['Certificate']);
        		}
        		$this->redirect('index');
        	}
        	$certificate_data = $this->Certificate->find('first', array('conditions' =>array('Certificate.id'=>$id)));
        	$this->set('certificate_data',$certificate_data);
        	
		$informationresource_info = $this->InformationResource->information_formated(array('certificatetype'), $this->backend_locale);
        	$this->set('informationresource_info', $informationresource_info);
		
		$this->set('title_for_layout', '证书添加/编辑 - '.$this->configs['shop_name']);
	}
	
	function remove($id=0){
		Configure::write('debug', 1);
        	$this->layout = 'ajax';
		$result['flag'] = 2;
		$result['message'] = $this->ld['delete_article_failure'];
		if (!$this->operator_privilege('certificate_remove', false)) {
			die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
		}
		$this->Certificate->deleteAll(array('Certificate.id'=>$id));
		$result['flag'] = 1;
        	$result['message'] = $this->ld['delete_article_success'];
        	die(json_encode($result));
	}
	
	function upload(){
		$this->operator_privilege('certificate_add');
		$this->pageTitle = $this->ld['bulk_upload'].' - 证书 - '.$this->configs['shop_title'];
		$this->set('title_for_layout', $this->ld['bulk_upload'].' - 证书 - '.$this->configs['shop_title']);
		$this->navigations[] = array('name' => '证书','url' => '');
        	$this->navigations[] = array('name' => '证书查询','url' => '/certificates/');
        	$this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' =>'');
        	$this->Profile->set_locale($this->backend_locale);
		$this->Profile->hasOne = array();
		$flag_code = 'certificate_import';
		$profilefiled_codes = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
		if (isset($profilefiled_codes) && !empty($profilefiled_codes)) {
			$this->set('profilefiled_codes', $profilefiled_codes);
		}
	}
	
	function batch_add(){
		$this->operator_privilege('certificate_add');
		$this->pageTitle = $this->ld['bulk_upload'].' - 证书 - '.$this->configs['shop_title'];
		$this->set('title_for_layout', $this->ld['bulk_upload'].' - 证书 - '.$this->configs['shop_title']);
		$this->navigations[] = array('name' => '证书','url' => '');
        	$this->navigations[] = array('name' => '证书查询','url' => '/certificates/');
        	$this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' =>'');
        	$flag_code = 'certificate_import';
		$profilefiled_codes = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
        	$profile_id=isset($profilefiled_codes['Profile']['id'])?$profilefiled_codes['Profile']['id']:0;
        	$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id, 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
        	if(empty($profilefiled_info)){
        		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['configration'].$this->ld['error']."');window.location.href='/admin/certificates/upload';</script>";
		       die();
        	}
        	if ($this->RequestHandler->isPost()){
        		set_time_limit(300);
        		if (!empty($_FILES['file'])) {
        			if ($_FILES['file']['error'] > 0) {
		                    	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/certificates/upload';</script>";
		                    	die();
		             } else {
		             	 	$field_list=array();
		             	 	$field_desc=array();
		             	 	$field_code=array();
						foreach ($profilefiled_info as $k => $v) {
							$fields_k=array();
							$fields_k = explode('.', $v['ProfileFiled']['code']);
							$field_list[] = isset($fields_k[1]) ? $fields_k[1] : '';
							$field_desc[]= $v['ProfilesFieldI18n']['description'];
							$field_code[$v['ProfilesFieldI18n']['description']]=isset($fields_k[1]) ? $fields_k[1] : '';
						}
						$this->set('key_code',$field_code);
						$preview_key=array();
						$csv_export_code = 'gb2312';
						$handle = @fopen($_FILES['file']['tmp_name'], 'r');
						$i = 0;
						while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
							if ($i == 0) {
								foreach ($row as $k => $v) {
									$preview_key[]=iconv('GB2312', 'UTF-8', $v);
									if (isset($key_arr[$k]) && !empty($key_arr[$k])) {
										continue;
									} 
								}
								$check_row = $row[0];
								$row_count = count($row);
								$check_row = iconv('GB2312', 'UTF-8', $check_row);
								$num_count = count($profilefiled_info);
								if ($row_count > $num_count) {
									echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/certificates/upload';</script>";
									die();
								}
								++$i;
							}
							$temp = array();
							foreach ($row as $k => $v) {
								$data_key_code=isset($field_code[$preview_key[$k]])?$field_code[$preview_key[$k]]:'';
								$temp[$preview_key[$k]] = ($v == '' ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v));
								if (isset($key_arr[$k]) && !empty($key_arr[$k])) {
									$temp[$data_key_code] = ($v == '' ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v));
								}
							}
							if (!isset($temp) || empty($temp)) {
								echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/certificates/upload';</script>";
								die();
							}
							$data[] = $temp;
						}
						fclose($handle);
                    			$this->set('profilefiled_info', $profilefiled_info);
                    			$this->set('uploads_list', $data);
		             }
        		}else{
        			if(isset($this->data)&&!empty($this->data)){
        				$informationresource_info = $this->InformationResource->information_formated(array('certificatetype'),$this->backend_locale);
        				$certificatetype_list=isset($informationresource_info['certificatetype'])?$informationresource_info['certificatetype']:array();
        				$having_certificate=array();
        				$certificate_list=array();
        				foreach($this->data as $v){
        					$certificate_number=isset($v['certificate_number'])?trim($v['certificate_number']):'';
        					$certificate_type=array_search($v['type'],$certificatetype_list);
        					$v['type']=$certificate_type===false?'':$certificate_type;
        					$is_null_value=array_search('',$v);
        					if($certificate_type===false)continue;
        					$certificate_total=$this->Certificate->find('count',array('conditions'=>array('Certificate.certificate_number'=>$certificate_number)));
        					if($certificate_total>0){
        						$having_certificate[]=$certificate_number;
        						continue;
        					}
        					$certificate_data=$v;
        					$certificate_data['id']=0;
        					$this->Certificate->save($certificate_data);
        					$certificate_list[]=$certificate_number;
        				}
        				$upload_message=$this->ld['upload'];
        				if(!empty($having_certificate)&&sizeof($having_certificate)==sizeof($this->data)){
        					$upload_message.=$this->ld['failed'];
        				}else{
        					$upload_message.=$this->ld['succeed']."".sizeof($certificate_list)."条";
        				}
        				$upload_message.=!empty($having_certificate)?(",以下证书已存在:".(implode('\n',array_merge(array(''),$having_certificate)))):'';
        				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$upload_message."');window.location.href='/admin/certificates/index';</script>";
					die();
        			}else{
		        		$this->redirect('index');
		        	}
        		}
        	}else{
        		$this->redirect('index');
        	}
	}
	
	function download_csv_example(){
		Configure::write('debug', 0);
		$this->layout = 'ajax';
		$this->Profile->set_locale($this->backend_locale);
		$this->Profile->hasOne = array();
		$flag_code = 'certificate_import';
		$profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
		if(!empty($profile_id)){
			$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
			$output_data=array();
			$tmp = array();
        		$fields_array = array();
			foreach ($profilefiled_info as $k => $v) {
				$tmp[] = $v['ProfilesFieldI18n']['description'];
				$fields_array[] = $v['ProfileFiled']['code'];
			}
			$output_data[] = $tmp;
			$informationresource_info = $this->InformationResource->information_formated(array('certificatetype'),$this->backend_locale);
        		$certificatetype_list=isset($informationresource_info['certificatetype'])?$informationresource_info['certificatetype']:array();
			$CertificateList = $this->Certificate->find('all', array('fields' => $fields_array, 'conditions' => array('Certificate.certificate_number <>' =>''), 'limit' => 10));
			foreach ($CertificateList as $k => $v) {
				$certificate_tmp = array();
				foreach ($fields_array as $kk => $vv) {
					$fields_kk = explode('.', $vv);
					if($vv=='Certificate.type'){
						$certificate_type=isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
						$certificate_tmp[] = isset($certificatetype_list[$certificate_type]) ? $certificatetype_list[$certificate_type] : '';
					}else{
						$certificate_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
					}
				}
				$output_data[] = $certificate_tmp;
			}
			$filename = 'certificate_import'.date('Ymd').'.csv';
			$this->Phpcsv->output($filename, $output_data);
			die();
		}else{
			$this->redirect('index');
		}
	}
	
	function ajax_check_certificate_number(){
		Configure::write('debug', 1);
        	$this->layout = 'ajax';
        	
        	$result=array();
        	$result['code']='0';
        	$result['message']=$this->ld['unknown_error'];
        	
        	$certificate_id=isset($_POST['certificate_id'])?intval($_POST['certificate_id']):0;
        	$certificate_number=isset($_POST['certificate_number'])?trim($_POST['certificate_number']):'';
        	if($certificate_number!=''){
	        	$certificate_total=$this->Certificate->find('count',array('conditions'=>array('Certificate.id <>'=>$certificate_id,'Certificate.certificate_number'=>$certificate_number)));
	        	if($certificate_total==0){
	        		$result['code']='1';
	        		$result['message']=$this->ld['valid'];
	        	}else{
	        		$result['message']=$this->ld['code_already_exists'];
	        	}
        	}
        	die(json_encode($result));
	}
	
    	public function fgetcsv_reg($handle, $length = null, $d = ',', $e = '"'){
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
}