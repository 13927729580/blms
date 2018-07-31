<?php

/**
 *这是一个名为 StaitcPagesController 的控制器
 *后台首页控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class AccountInformationsController extends AppController
{
    public $name = 'AccountInformations';
    public $components = array('RequestHandler','Pagination','Phpexcel');
    public $helpers = array('Html','Javascript','Pagination');
    public $uses = array('Operator','Config','AccountInformation','Payment','PaymentI18n','InformationResource','Resource');
    
    /**
     *显示后台首页.
     */
    public function index($page = 1){
	        /*判断权限*/
	        $this->operator_privilege('account_view');
	        $this->operation_return_url(true);//设置操作返回页面地址
	        //$this->menu_path = array('root' => '/cms/','sub' => '/static_pages/');
	        /*end*/
	        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
	        $this->navigations[] = array('name' => '财务','url' => '/account_informations/index');
	        
	        $condition = array();
	        $project_cond=array();
	        if (isset($this->params['url']['account_category']) && $this->params['url']['account_category'] != '') {
	        		$account_category=trim($this->params['url']['account_category']);
	        		if(strstr($account_category,'user_project')){
	        			$user_project_id=ltrim($account_category,'user_project_');
	        			$project_cond['UserProject.project_code']=$user_project_id;
	        		}else{
	    				$condition['AccountInformation.account_category'] = $account_category;
	    			}
	        		$this->set('account_category', $this->params['url']['account_category']);
	        }
	        if (isset($this->params['url']['transaction_category']) && $this->params['url']['transaction_category'] != '') {
	    			$condition['AccountInformation.transaction_category'] = $this->params['url']['transaction_category'];
	        		$this->set('transaction_category', $this->params['url']['transaction_category']);
	        }
	        if (isset($this->params['url']['payment_id']) && $this->params['url']['payment_id'] != '0') {
	    			$condition['AccountInformation.payment_id'] = $this->params['url']['payment_id'];
	        		$this->set('payment_id', $this->params['url']['payment_id']);
	        }
	        if (isset($this->params['url']['account_type']) && $this->params['url']['account_type'] != '') {
	    			$condition['AccountInformation.account_type'] = $this->params['url']['account_type'];
	        		$this->set('account_type', $this->params['url']['account_type']);
	        }
	        if (isset($this->params['url']['payer']) && trim($this->params['url']['payer']) != '') {
		        	$payer=trim($this->params['url']['payer']);
		    		$condition['or']['AccountInformation.payer like'] = "%{$payer}%";
		    		$condition['or']['AccountInformation.payer_account like'] = "%{$payer}%";
		        	$this->set('payer', $this->params['url']['payer']);
	        }
	        if (isset($this->params['url']['payee']) && trim($this->params['url']['payee']) != '') {
		        	$payee=trim($this->params['url']['payee']);
		    		$condition['or']['AccountInformation.payee like'] = "%{$payee}%";
		    		$condition['or']['AccountInformation.receipt_account like'] = "%{$payee}%";
		        	$this->set('payee', $payee);
	        }
	        if(!isset($this->params['url']['status']))$this->params['url']['status']='0';
	        if (isset($this->params['url']['status']) && $this->params['url']['status'] != '') {
	    			$condition['AccountInformation.status'] = $this->params['url']['status'];
	        		$this->set('status', $this->params['url']['status']);
	        }
	        if (isset($this->params['url']['amount_start']) && trim($this->params['url']['amount_start']) != '') {
		        	$amount_start=number_format(floatval($this->params['url']['amount_start']),2,'.','');
		    		$condition['AccountInformation.payment_amount >='] = $amount_start;
		        	$this->set('amount_start', $amount_start);
	        }
	        if (isset($this->params['url']['amount_end']) && trim($this->params['url']['amount_end']) != '') {
		        	$amount_end=number_format(floatval($this->params['url']['amount_end']),2,'.','');
		    		$condition['AccountInformation.payment_amount <='] = $amount_end;
		        	$this->set('amount_end', $amount_end);
	        }
	        if (isset($this->params['url']['payment_time_start']) && trim($this->params['url']['payment_time_start']) != '') {
		        	$payment_time_start=trim($this->params['url']['payment_time_start']);
		    		$condition['AccountInformation.payment_time >='] = date("Y-m-d H:i:s",strtotime($payment_time_start));
		        	$this->set('payment_time_start', $payment_time_start);
	        }
	        if (isset($this->params['url']['payment_time_end']) && trim($this->params['url']['payment_time_end']) != '') {
		        	$payment_time_end=trim($this->params['url']['payment_time_end']);
		    		$condition['AccountInformation.payment_time <='] = date("Y-m-d H:i:s",strtotime($payment_time_end));
		        	$this->set('payment_time_end', $payment_time_end);
	        }
	        if (isset($this->params['url']['check_operator']) && intval($this->params['url']['check_operator'])>0) {
	        		$condition['AccountInformation.check_operator'] = $this->params['url']['check_operator'];
	        		$this->set('check_operator', $this->params['url']['check_operator']);
	        }
	        if (isset($this->params['url']['operator']) && intval($this->params['url']['operator'])>0) {
	        		$condition['or'][]['AccountInformation.operator'] = $this->params['url']['operator'];
	        		$project_cond['UserProject.manager']=$this->params['url']['operator'];
	        		$this->set('operator', $this->params['url']['operator']);
	        }
	        if(!empty($project_cond)){
	        	App::import('Model', 'UserProject');//加载Model
	        	if(class_exists('UserProject')){
	        		$this->loadModel('UserProject');
	        		$user_projects=$this->UserProject->find('list',array('fields'=>'UserProject.id','conditions'=>$project_cond));
	        		if(!empty($user_projects)){
	        			foreach($user_projects as $v){
	        				$condition['or'][]['AccountInformation.account_category']="user_project_{$v}";
	        			}
	        		}
	        	}
	        }
	        $total = $this->AccountInformation->find('count', array('conditions'=>$condition));
	        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
	        if (isset($_GET['page']) && $_GET['page'] != '') {
	            		$page = $_GET['page'];
	        }
	        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
	        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
	        $parameters['get'] = array();
	        //地址路由参数（和control,action的参数对应）
	        $parameters['route'] = array('controller' => 'account_informations','action' => 'index','page' => $page,'limit' => $rownum);
	        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'AccountInformation');
		
	        $this->Pagination->init($condition, $parameters, $options);
	        $account_information_list = $this->AccountInformation->find('all', array('conditions' => $condition, 'order' => 'AccountInformation.created desc', 'limit' => $rownum, 'page' => $page));
	        $this->set('account_information_list',$account_information_list);
	        
	        if(!empty($account_information_list)){
	        	$user_project_ids=array();
	        	foreach($account_information_list as $v){
	        		if(strstr($v['AccountInformation']['account_category'],'user_project'))$user_project_ids[]=ltrim($v['AccountInformation']['account_category'],'user_project_');
	        	}
	        	if(!empty($user_project_ids)){
	        		App::import('Model', 'UserProject');//加载公共控制器
	        		if(class_exists('UserProject')){
	        			$this->loadModel('UserProject');
	        			$this->loadModel('UserProjectModification');
		        		$user_project_infos=$this->UserProject->find('all',array('fields'=>'UserProject.id,UserProject.project_code,UserProject.user_id,UserProject.manager','conditions'=>array('UserProject.id'=>$user_project_ids)));
		        		if(!empty($user_project_infos)){
			        		$user_project_list=array();
			        		foreach($user_project_infos as $v){
			        			$user_project_list[$v['UserProject']['id']]=$v['UserProject'];
			        		}
			        		$this->set('user_project_list',$user_project_list);
		        		}
		        		$modify_project_infos=$this->UserProjectModification->find('list',array('fields'=>'UserProjectModification.user_project_id,UserProjectModification.old_user_project_id','conditions'=>array('UserProjectModification.user_project_id'=>$user_project_ids,'UserProjectModification.check_status <>'=>'1')));
		        		$this->set('modify_project_infos',$modify_project_infos);
		        		
		        		$old_modify_project_infos=array_flip($modify_project_infos);
		        		$this->set('old_modify_project_infos',$old_modify_project_infos);
	        		}
	        	}
	        }
	        $this->Payment->set_locale($this->backend_locale);
	        $payment_info = $this->Payment->find('all', array('fields' => array('Payment.id','PaymentI18n.name'),'conditions'=>array('Payment.status'=>'1','PaymentI18n.name <>'=>'','parent_id <>'=>0),'order'=>'Payment.parent_id,Payment.id'));
	        $this->set('payment_info',$payment_info);
	        $payment_list=array();
	        if(!empty($payment_info)){
	        	foreach($payment_info as $v)$payment_list[$v['Payment']['id']]=$v['PaymentI18n']['name'];
	        }
	 	 $this->set('payment_list',$payment_list);
		
		$info_resource = $this->InformationResource->information_formated(array('user_project'), $this->locale);
	        if(isset($info_resource['user_project'])&&!empty($info_resource['user_project'])){
	    		$sub_user_project=array_keys($info_resource['user_project']);
			$sub_info_resource = $this->InformationResource->information_formated($sub_user_project,$this->backend_locale,false);
			$info_resource['all_user_project']=$info_resource['user_project'];
			foreach($info_resource['all_user_project'] as $k=>$v){
				if(isset($sub_info_resource[$k])&&!empty($sub_info_resource[$k])){
					unset($info_resource['all_user_project'][$k]);
					foreach($sub_info_resource[$k] as $kk=>$vv)$info_resource['all_user_project'][$kk]=$vv;
				}
			}
			ksort($info_resource['all_user_project']);
			$info_resource=array_merge($info_resource,$sub_info_resource);
	        }
		 $this->set('info_resource',$info_resource);
		
		$Resource_info = $this->Resource->getformatcode(array('transaction_category'), $this->backend_locale);
		$this->set('Resource_info', $Resource_info);
	        
	        $OperatorList=$this->Operator->find('list',array('fields'=>'id,name','conditions'=>array('status'=>'1')));
	        $this->set('OperatorList', $OperatorList);
	        
	        $this->set('title_for_layout', '财务 - '.$this->configs['shop_name']);
    }
    
    function view($id=0){
		/*判断权限*/
		$this->operator_privilege('account_view');
		//$this->menu_path = array('root' => '/cms/','sub' => '/static_pages/');
		/*end*/
		$this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
		$this->navigations[] = array('name' => '财务','url' => '/account_informations/index');
		$this->navigations[] = array('name' => '财务详情','url' => '');
		
		$account_information = $this->AccountInformation->find('first', array('conditions' => array('AccountInformation.id'=>$id)));
		$this->set('account_information',$account_information);
		
		$this->Payment->set_locale($this->backend_locale);
		$payment_info = $this->Payment->find('all', array('fields' => array('Payment.id','PaymentI18n.name'),'conditions'=>array('Payment.status'=>'1','PaymentI18n.name <>'=>'')));
		$this->set('payment_info',$payment_info);
		
		$info_resource=$this->InformationResource->information_formated(array('user_project'),$this->backend_locale,false);
		$this->set('info_resource',$info_resource);
		
		$Resource_info = $this->Resource->getformatcode(array('transaction_category'), $this->backend_locale);
		$this->set('Resource_info', $Resource_info);
		
		$this->set('title_for_layout', '财务详情 - '.$this->configs['shop_name']);
    }

    function account_informations_add(){
    		Configure::write('debug',1);
		$this->layout="ajax";
		$result=array();
		$result['code']="0";
		$result['message']='添加成功';
		if ($this->RequestHandler->isPost()) {
			$this->data['AccountInformation']['operator']=$this->admin['id'];
			$this->AccountInformation->save($this->data);
			$result['code']="1";
		}
		die(json_encode($result));
    }

    public function remove($id){
	        Configure::write('debug', 1);
	        $this->layout = 'ajax';
	        $result['flag'] = 2;
	        $result['message'] = $this->ld['delete_member_failure'];
	        $this->AccountInformation->deleteAll(array('id' => $id));
	        $result['flag'] = 1;
	        $result['message'] = $this->ld['delete_member_success'];
	        if ($this->RequestHandler->isPost()) {
	            	die(json_encode($result));
	            	$this->redirect('/courses/');
	        } else {
	            	$this->redirect('/courses/');
	        }
    }

    function account_informations_status(){
    		Configure::write('debug',1);
		$this->layout="ajax";
		$result=array();
		$result['code']="0";
		$result['message']=$this->ld['operation_failed'];
		if ($this->RequestHandler->isPost()) {
			$account_information_detail=$this->AccountInformation->findById($_POST['id']);
			if(!empty($account_information_detail)){
				$nowtime=date("Y-m-d H:i:s");
				$account_information_data=array(
					'id' => $_POST['id'],
					'status' => $_POST['status'],
					'check_operator'=>$this->admin['id']
				);
				$this->AccountInformation->save($account_information_data);
				$account_category=$account_information_detail['AccountInformation']['account_category'];
				if(strstr($account_category,'user_project')){
					$this->loadModel('UserProject');
					$this->loadModel('UserProjectFee');
					$user_project_id=ltrim($account_category,'user_project_');
					$user_project_detail=$this->UserProject->find('first',array('conditions'=>array('UserProject.id'=>$user_project_id)));
					if(!empty($user_project_detail)){
						$user_project_fee=$account_information_detail['AccountInformation']['transaction_category'];
						if($_POST['status']=='1'){//审核
							if($user_project_fee=='0'){
								$this->UserProject->updateAll(array('status'=>"'2'"),array('id'=>$user_project_id,'status'=>array('1','4','6')));
							}
							$this->UserProjectFee->updateAll(array('check_status'=>"'1'"),array('user_project_id'=>$user_project_id,'fee_type'=>$user_project_fee,'check_status'=>'0'));
						}else if($_POST['status']=='0'||$_POST['status']=='2'){//取消
							$this->UserProjectFee->updateAll(array('check_status'=>"'0'"),array('user_project_id'=>$user_project_id,'fee_type'=>$user_project_fee,'check_status'=>'1'));
						}
					}
				}
		            	$result['code']="1";
		            	$result['message']='操作成功';
		        }
        	}
		die(json_encode($result));
    }
    
    function batch_operate(){
    		Configure::write('debug',1);
		$this->layout="ajax";
		
		if ($this->RequestHandler->isPost()) {
			$result=array();
			$result['code']='0';
			$result['message']=$this->ld['operation_failed'];
			$batch_operate=isset($_POST['batch_operate'])?$_POST['batch_operate']:'';
			$batch_export_type=isset($_POST['batch_export_type'])?$_POST['batch_export_type']:'';
			$account_information_ids=isset($_POST['checkbox'])?$_POST['checkbox']:0;
			if($batch_operate=='batch_export'){
				$resut['code']='1';
				$resut['message']=$this->ld['export'];
				if($batch_export_type=='all_export'){
					$this->batch_export(array());
				}else if($batch_export_type=='choice_export'){
					$condition = array();
					$condition['AccountInformation.id']=$account_information_ids;
					$this->batch_export($condition);
				}
			}else if($batch_operate=='batch_check'){
				$account_information_list=$this->AccountInformation->find('list',array('fields'=>'AccountInformation.account_category,AccountInformation.transaction_category','conditions'=>array('AccountInformation.account_category like'=>"user_project_%",'AccountInformation.id'=>$account_information_ids,'AccountInformation.status <>'=>'1')));
				if(!empty($account_information_list)){
					$this->loadModel('UserProject');
					$this->loadModel('UserProjectFee');
					foreach($account_information_list as $k=>$v){
						$user_project_id=ltrim($k,'user_project_');
						if($v=='0'){
							$this->UserProject->updateAll(array('status'=>"'2'"),array('id'=>$user_project_id,'status'=>array('1','4','6')));
						}
						$this->UserProjectFee->updateAll(array('check_status'=>"'1'"),array('user_project_id'=>$user_project_id,'fee_type'=>$v,'check_status'=>'0'));
					}
				}
				$this->AccountInformation->updateAll(array('AccountInformation.status'=>"'1'",'check_operator'=>$this->admin['id']),array('AccountInformation.id'=>$account_information_ids,'AccountInformation.status <>'=>'1'));
				$result['code']='1';
				$result['message']=$this->ld['update_successful'];
			}else if($batch_operate=='batch_uncheck'){
				$account_information_list=$this->AccountInformation->find('list',array('fields'=>'AccountInformation.account_category,AccountInformation.transaction_category','conditions'=>array('AccountInformation.account_category like'=>"user_project_%",'AccountInformation.id'=>$account_information_ids,'AccountInformation.status <>'=>'1')));
				if(!empty($account_information_list)){
					$this->loadModel('UserProjectFee');
					foreach($account_information_list as $k=>$v){
						$user_project_id=ltrim($k,'user_project_');
						$this->UserProjectFee->updateAll(array('check_status'=>"'0'"),array('user_project_id'=>$user_project_id,'fee_type'=>$v,'check_status'=>'1'));
						if($v=='0'){
							$this->UserProject->updateAll(array('status'=>"'1'"),array('id'=>$user_project_id,'status'=>array('2')));
						}
					}
				}
				$this->AccountInformation->updateAll(array('AccountInformation.status'=>"'0'"),array('AccountInformation.id'=>$account_information_ids,'AccountInformation.status'=>'1'));
				$result['code']='1';
				$result['message']=$this->ld['update_successful'];
			}
			die(json_encode($result));
		}else{
			$batch_operate=isset($_REQUEST['batch_operate'])?$_REQUEST['batch_operate']:'';
			$batch_export_type=isset($_REQUEST['batch_export_type'])?$_REQUEST['batch_export_type']:'';
			$condition = array();$project_cond=array();
		        if (isset($this->params['url']['account_category']) && $this->params['url']['account_category'] != '') {
		    			$account_category=trim($this->params['url']['account_category']);
		        		if(strstr($account_category,'user_project')){
		        			$user_project_id=ltrim($account_category,'user_project_');
		        			$project_cond['UserProject.project_code']=$user_project_id;
		        		}else{
		    				$condition['AccountInformation.account_category'] = $account_category;
		    			}
		        }
		        if (isset($this->params['url']['transaction_category']) && $this->params['url']['transaction_category'] != '') {
		    			$condition['AccountInformation.transaction_category'] = $this->params['url']['transaction_category'];
		        }
			if (isset($this->params['url']['account_type']) && $this->params['url']['account_type'] != '') {
		    			$condition['AccountInformation.account_type'] = $this->params['url']['account_type'];
		        }
		        if (isset($this->params['url']['payer']) && trim($this->params['url']['payer']) != '') {
			        	$payer=trim($this->params['url']['payer']);
			    		$condition['or']['AccountInformation.payer like'] = "%{$payer}%";
			    		$condition['or']['AccountInformation.payer_account like'] = "%{$payer}%";
		        }
		        if (isset($this->params['url']['payee']) && trim($this->params['url']['payee']) != '') {
			        	$payee=trim($this->params['url']['payee']);
			    		$condition['or']['AccountInformation.payee like'] = "%{$payee}%";
			    		$condition['or']['AccountInformation.receipt_account like'] = "%{$payee}%";
		        }
		        if (isset($this->params['url']['status']) && $this->params['url']['status'] != '') {
		    			$condition['AccountInformation.status'] = $this->params['url']['status'];
		        }
		        if (isset($this->params['url']['amount_start']) && trim($this->params['url']['amount_start']) != '') {
			        	$amount_start=number_format(floatval($this->params['url']['amount_start']),2,'.','');
			    		$condition['AccountInformation.payment_amount >='] = $amount_start;
		        }
		        if (isset($this->params['url']['amount_end']) && trim($this->params['url']['amount_end']) != '') {
			        	$amount_end=number_format(floatval($this->params['url']['amount_end']),2,'.','');
			    		$condition['AccountInformation.payment_amount <='] = $amount_end;
		        }
		        if (isset($this->params['url']['payment_time_start']) && trim($this->params['url']['payment_time_start']) != '') {
			        	$payment_time_start=trim($this->params['url']['payment_time_start']);
			    		$condition['AccountInformation.payment_time >='] = date("Y-m-d H:i:s",strtotime($payment_time_start));
		        }
		        if (isset($this->params['url']['payment_time_end']) && trim($this->params['url']['payment_time_end']) != '') {
			        	$payment_time_end=trim($this->params['url']['payment_time_end']);
			    		$condition['AccountInformation.payment_time <='] = date("Y-m-d H:i:s",strtotime($payment_time_end));
		        }
		        if (isset($this->params['url']['operator']) && intval($this->params['url']['operator'])>0) {
		        		$project_cond['UserProject.manager']=$this->params['url']['operator'];
		        		$this->set('operator', $this->params['url']['operator']);
		        }
		        if(!empty($project_cond)){
		        	App::import('Model', 'UserProject');//加载Model
		        	if(class_exists('UserProject')){
		        		$this->loadModel('UserProject');
		        		$user_projects=$this->UserProject->find('list',array('fields'=>'UserProject.id','conditions'=>$project_cond));
		        		if(!empty($user_projects)){
		        			foreach($user_projects as $v){
		        				$condition['or'][]['AccountInformation.account_category']="user_project_{$v}";
		        			}
		        		}
		        	}
		        }
		        if($batch_operate=='batch_export'&&$batch_export_type=='search_export')$this->batch_export($condition);
		}
		$this->redirect('index');
    }
    
    function batch_export($export_cond){
    		$export_data=array();
    		$export_fields=array(
    			$this->ld['classification'],
    			$this->ld['category'],
    			'付款人',
    			$this->ld['amount'],
    			$this->ld['payment'],
    			'收据编号',
    			$this->ld['time_of_payment'],
    			'收款人',
    			$this->ld['type'],
    			$this->ld['approval status'],
    			'审核时间',
    			'审核人'
    		);
    		$export_data[]=$export_fields;
    		$account_information_list = $this->AccountInformation->find('all', array('conditions' => $export_cond, 'order' => 'AccountInformation.created desc'));
    		if(!empty($account_information_list)){
    			$OperatorList=$this->Operator->find('list',array('fields'=>'id,name','conditions'=>array('status'=>'1')));
    			$payment_list = $this->PaymentI18n->find('list', array('fields' => array('PaymentI18n.payment_id','PaymentI18n.name'),'conditions'=>array('PaymentI18n.locale'=>$this->backend_locale,'PaymentI18n.name <>'=>'')));
			$info_resource=$this->InformationResource->information_formated(array('user_project'),$this->backend_locale,false);
			$Resource_info = $this->Resource->getformatcode(array('transaction_category'), $this->backend_locale);
			
			$user_project_ids=array();
	        	foreach($account_information_list as $v){
	        		if(strstr($v['AccountInformation']['account_category'],'user_project'))$user_project_ids[]=ltrim($v['AccountInformation']['account_category'],'user_project_');
	        	}
	        	if(!empty($user_project_ids)){
	        		App::import('Model', 'UserProject');//加载公共控制器
	        		if(class_exists('UserProject')){
	        			$this->loadModel('UserProject');
	        			$this->loadModel('UserProjectModification');
		        		$user_project_infos=$this->UserProject->find('all',array('fields'=>'UserProject.id,UserProject.project_code,UserProject.user_id,UserProject.manager','conditions'=>array('UserProject.id'=>$user_project_ids)));
		        		if(!empty($user_project_infos)){
			        		$user_project_list=array();
			        		foreach($user_project_infos as $v){
			        			$user_project_list[$v['UserProject']['id']]=$v['UserProject'];
			        		}
		        		}
		        		$modify_project_infos=$this->UserProjectModification->find('list',array('fields'=>'UserProjectModification.user_project_id,UserProjectModification.old_user_project_id','conditions'=>array('UserProjectModification.user_project_id'=>$user_project_ids,'UserProjectModification.check_status <>'=>'1')));
		        		$old_modify_project_infos=array_flip($modify_project_infos);
	        		}
	        	}
			foreach($account_information_list as $v){
				$accont_data=array();
				if(strstr($v['AccountInformation']['account_category'],'user_project')){
					$user_project_id=ltrim($v['AccountInformation']['account_category'],'user_project_');
					$user_project_code=isset($user_project_list[$user_project_id]['project_code'])?$user_project_list[$user_project_id]['project_code']:'';
					$accont_data[]=isset($info_resource['user_project'][$user_project_code])?$info_resource['user_project'][$user_project_code]:$v['AccountInformation']['account_category'];
				}else{
					$accont_data[]=isset($ld[$v['AccountInformation']['account_category']])?$ld[$v['AccountInformation']['account_category']]:$v['AccountInformation']['account_category'];
				}
				$accont_data[]=isset($Resource_info['transaction_category'][$v['AccountInformation']['transaction_category']])?$Resource_info['transaction_category'][$v['AccountInformation']['transaction_category']]:$v['AccountInformation']['transaction_category'];
				$accont_data[]=$v['AccountInformation']['payer'];
				$accont_data[]=$v['AccountInformation']['account_type']=='1'?(abs($v['AccountInformation']['payment_amount'])*-1):$v['AccountInformation']['payment_amount'];
				$accont_data[]=isset($payment_list[$v['AccountInformation']['payment_id']])?$payment_list[$v['AccountInformation']['payment_id']]:$v['AccountInformation']['payment_id'];
				$accont_data[]=$v['AccountInformation']['transaction'];
				$accont_data[]=strstr($v['AccountInformation']['payment_time'],'0000')?'':date('Y-m-d',strtotime($v['AccountInformation']['payment_time']));
				if(strstr($v['AccountInformation']['account_category'],'user_project')){
					$accont_data[]=isset($user_project_list[$user_project_id]['manager'])?(isset($OperatorList[$user_project_list[$user_project_id]['manager']])?$OperatorList[$user_project_list[$user_project_id]['manager']]:''):'';
				}else{
					$accont_data[]=isset($OperatorList[$v['AccountInformation']['operator']])?$OperatorList[$v['AccountInformation']['operator']]:$this->ld['system'];
				}
				$accont_data[]=$v['AccountInformation']['account_type']=='0'?'收入':'支出';
				if(strstr($v['AccountInformation']['account_category'],'user_project')&&(isset($modify_project_infos[$user_project_id])||(isset($old_modify_project_infos[$user_project_id])&&$v['AccountInformation']['account_type']=='1'))){
					$accont_data[]='变更待审核';
					$accont_data[]='';
					$accont_data[]='';
				}else{
					$accont_data[]=$v['AccountInformation']['status']=='0'?'待审核':($v['AccountInformation']['status']=='1'?'已审核':'已取消');
					$accont_data[]=$v['AccountInformation']['status']=='1'?date('Y-m-d',strtotime($v['AccountInformation']['modified'])):'';
				$accont_data[]=$v['AccountInformation']['status']=='1'?(isset($OperatorList[$v['AccountInformation']['check_operator']])?$OperatorList[$v['AccountInformation']['check_operator']]:$this->ld['system']):'';
				}
				$export_data[]=$accont_data;
			}
    		}
    		$this->Phpexcel->output('account'.date('YmdH').'.xls', $export_data);
    		die();
    }
}