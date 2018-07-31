<?php

/*****************************************************************************
 * Seevia 兑换券
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为VouchersController的控制器
 * 控制卡券调用
 *
 */
class VouchersController extends AppController
{
    public $name = 'Vouchers';
    public $components = array('Pagination','RequestHandler','Email','Phpcsv');
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    public $uses = array('MailTemplate','User','MailSendQueue','InformationResource','Resource','Voucher','VoucherEntityCard','VoucherOperation','Product','Profile','OperatorLog');
    
    var $entity_card_status_resource=array("0"=>'未开通','1'=>'激活','2'=>'已使用','3'=>'作废','4'=>'冻结');
    
    /**
     *	兑换券列表
     */
    public function index($page=1)
    {
		$this->operator_privilege('voucher_view');
		$this->operation_return_url(true);//设置操作返回页面地址
		$this->menu_path = array('root' => '/oms/','sub' => '/vouchers/');
		
		$this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
		$this->navigations[] = array('name' => $this->ld['voucher'],'url' => '/vouchers/');
		
		$this->set('entity_card_status_resource',$this->entity_card_status_resource);
		
		$condition = '';
		$entity_card_condition="";
		if (isset($this->params['url']['keywords']) && trim($this->params['url']['keywords']) != '') {
			$keywords=trim($this->params['url']['keywords']);
			$condition['and']['or']['Voucher.batch_sn like']="%".$keywords."%";
			$condition['and']['or']['Voucher.name like']="%".$keywords."%";
			
			$entity_card_condition['and']['or']['VoucherEntityCard.card_sn like']="%".$keywords."%";
			
			$Voucher_pro_search_cond['Product.status']="1";
			$Voucher_pro_search_cond['Product.forsale']="1";
			$Voucher_pro_search_cond['or']['Product.code like']="%".$keywords."%";
			$Voucher_pro_search_cond['or']['ProductI18n.name like']="%".$keywords."%";
			$Voucher_pro_search_info=$this->Product->find('all',array('fields'=>array('Product.code'),'conditions'=>$Voucher_pro_search_cond));
			$Voucher_pro_search_data=array();
			foreach($Voucher_pro_search_info as $v){
				$Voucher_pro_search_data[]=$v['Product']['code'];
			}
			if(!empty($Voucher_pro_search_data)){
				$entity_card_condition['and']['VoucherEntityCard.product_code']=$Voucher_pro_search_data;
			}
			$this->set('keywords',$keywords);
		}
		if (isset($this->params['url']['start_date_from']) && trim($this->params['url']['start_date_from']) != '') {
			$start_date_from=trim($this->params['url']['start_date_from']);
			$entity_card_condition['and']['VoucherEntityCard.start_date >=']=$start_date_from." 00:00:00";
			$this->set('start_date_from',$start_date_from);
		}
		if (isset($this->params['url']['start_date_to']) && trim($this->params['url']['start_date_to']) != '') {
			$start_date_to=trim($this->params['url']['start_date_to']);
			$entity_card_condition['and']['VoucherEntityCard.start_date <=']=$start_date_to." 23:59:59";
			$this->set('start_date_to',$start_date_to);
		}
		if (isset($this->params['url']['min_price']) && trim($this->params['url']['min_price']) != '') {
			$min_price=trim($this->params['url']['min_price']);
			$entity_card_condition['and']['VoucherEntityCard.amount >=']=$min_price;
			$this->set('min_price',$min_price);
		}
		if (isset($this->params['url']['max_price']) && trim($this->params['url']['max_price']) != '') {
			$max_price=trim($this->params['url']['max_price']);
			$condition['and']['Voucher.amount <=']=$max_price;
			$this->set('max_price',$max_price);
		}
		if (isset($this->params['url']['search_status']) && trim($this->params['url']['search_status']) != '') {
			$search_status=trim($this->params['url']['search_status']);
			$condition['and']['Voucher.status']=$search_status;
			$this->set('search_status',$search_status);
		}
		if(!empty($entity_card_condition)){
			$entity_card_list=array();
			$entity_card_info=$this->VoucherEntityCard->find('all',array('fields'=>"VoucherEntityCard.batch_sn",'conditions'=>$entity_card_condition,'group'=>'VoucherEntityCard.batch_sn'));
			foreach($entity_card_info as $k=>$v){
				$entity_card_list[]=$v['VoucherEntityCard']['batch_sn'];
			}
			if(!empty($entity_card_list)){
				$condition['and']['or']['Voucher.batch_sn']=$entity_card_list;
			}
		}
		$total = $this->Voucher->find('count',array('conditions'=>$condition));
		$this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
	        if (isset($_GET['page']) && $_GET['page'] != '') {
	            $page = $_GET['page'];
	        }
        	 $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        	 $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        	 $parameters['get'] = array();
	        //地址路由参数（和control,action的参数对应）
	        $parameters['route'] = array('controller' => 'vouchers','action' => 'index','page' => $page,'limit' => $rownum);
	        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Voucher');
	        $this->Pagination->init($condition, $parameters, $options);
	        $voucher_list = $this->Voucher->find('all', array('conditions' => $condition, 'order' => 'Voucher.id desc', 'limit' => $rownum, 'page' => $page));
	        $this->set('voucher_list',$voucher_list);
	        
	        $product_codes=array();
	        $voucher_batch_sn=array();
	        if(!empty($voucher_list)){
	        	foreach($voucher_list as $v){
	        		$product_codes[]=$v['Voucher']['product_code'];
	        		$voucher_batch_sn[]=$v['Voucher']['batch_sn'];
	        	}
	        	$pro_cond['Product.status']='1';
	        	$pro_cond['Product.forsale']='1';
	        	$pro_cond['Product.code']=$product_codes;
	        	$product_infos=$this->Product->find('all',array('fields'=>array('Product.code','ProductI18n.name'),'conditions'=>$pro_cond));
	        	
	        	$product_list=array();
	        	foreach($product_infos as $v){
	        		$product_list[$v['Product']['code']]=$v['ProductI18n']['name'];
	        	}
	        	$this->set('product_list',$product_list);
	        	
	        	$voucher_card_data_info=$this->VoucherEntityCard->find('all',array('fields'=>array('VoucherEntityCard.batch_sn','count(*) as data_count'),'conditions'=>array('VoucherEntityCard.batch_sn'=>$voucher_batch_sn),'group'=>'VoucherEntityCard.batch_sn'));
	        	$voucher_card_datas=array();
	        	foreach($voucher_card_data_info as $v){
	        		$voucher_card_datas[$v['VoucherEntityCard']['batch_sn']]=$v[0]['data_count'];
	        	}
	        	$this->set('voucher_card_datas',$voucher_card_datas);
	        	
	        	$voucher_card_status_info=$this->VoucherEntityCard->find('all',array('fields'=>array('VoucherEntityCard.batch_sn','VoucherEntityCard.status','count(*) as status_count'),'conditions'=>array('VoucherEntityCard.batch_sn'=>$voucher_batch_sn),'group'=>'VoucherEntityCard.batch_sn,VoucherEntityCard.status'));
	        	$voucher_card_status_data=array();
	        	foreach($voucher_card_status_info as $v){
	        		$voucher_card_status_data[$v['VoucherEntityCard']['batch_sn']][$v['VoucherEntityCard']['status']]=$v[0]['status_count'];
	        	}
	        	$this->set('voucher_card_status_data',$voucher_card_status_data);
	        }
	        $this->set('title_for_layout', $this->ld['voucher'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }
    
    /**
     *	兑换券编辑
     */
    function view($id=0){
    		if($id==0){
    			$this->operator_privilege('voucher_add');
    		}else{
    			$this->operator_privilege('voucher_edit');
    		}
    		$this->menu_path = array('root' => '/oms/','sub' => '/vouchers/');
        	$this->set('title_for_layout', $this->ld['voucher'].' - '.$this->configs['shop_name']);
        	$this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        	$this->navigations[] = array('name' => $this->ld['voucher'],'url' => '/vouchers/');
        	$this->navigations[] = array('name' => $this->ld['add_edit'],'url' => '');
        	
        	if ($this->RequestHandler->isPost()) {
        		$this->Voucher->save($this->data['Voucher']);
			if ($this->configs['operactions-log'] == 1) {
				$voucher_id=$this->Voucher->id;
				$this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_edit'].$this->ld['voucher'].":".$voucher_id, $this->admin['id']);
			}
        		$this->redirect('/vouchers/');
        	}
        	$voucher_data=$this->Voucher->find('first',array('conditions'=>array('Voucher.id'=>$id)));
        	$this->set('voucher_data',$voucher_data);
        	if(!empty($voucher_data)){
        		$batch_sn=$voucher_data['Voucher']['batch_sn'];
        		$voucher_operation_list=$this->VoucherOperation->find('all',array('conditions'=>array('VoucherOperation.batch_sn'=>$batch_sn),'order'=>'VoucherOperation.id desc'));
        		$this->set('voucher_operation_list',$voucher_operation_list);
        		if(!empty($voucher_operation_list)){
        			$operatot_ids=array();
	        		foreach($voucher_operation_list as $v){
	        			$operatot_ids[]=$v['VoucherOperation']['operator_id'];
	        		}
	        		$voucher_operator_list=$this->Operator->find('list',array('fields'=>array('Operator.id','Operator.name'),'conditions'=>array('Operator.id'=>$operatot_ids)));
	        		$this->set('voucher_operator_list',$voucher_operator_list);
        		}
        	}
    }
    
    function entity_card_auto_add($voucher_id=0){
    		$this->operator_privilege('voucher_edit');
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result=array();
		$result['flag']='0';
		$result['message']='';
		$batch_sn=isset($_POST['batch_sn'])?$_POST['batch_sn']:'';
		$card_count=isset($_POST['card_count'])?intval($_POST['card_count']):0;
		$entity_card_auto_amount=isset($_POST['entity_card_auto_amount'])?$_POST['entity_card_auto_amount']:0;
		$entity_card_auto_product=isset($_POST['entity_card_auto_product'])?$_POST['entity_card_auto_product']:'';
		$entity_card_auto_start_time=isset($_POST['entity_card_auto_start_time'])?$_POST['entity_card_auto_start_time']:'';
		$entity_card_auto_end_time=isset($_POST['entity_card_auto_end_time'])?$_POST['entity_card_auto_end_time']:'';
    		$voucher_data=$this->Voucher->find('first',array('conditions'=>array('Voucher.batch_sn'=>$batch_sn)));
        	if(empty($voucher_data)){
        		$result['message']='兑换券批次不存在';
        		die(json_encode($result));
        	}
        	$voucher_entity_card = $this->VoucherEntityCard->find('first', array('fields'=>array("Max(VoucherEntityCard.card_sn) as max_card_sn"),'conditions'=>array('VoucherEntityCard.batch_sn'=>$batch_sn)));
        	$card_start='00000';
        	if(!empty($voucher_entity_card)){
        		$card_start=$voucher_entity_card[0]['max_card_sn'];
        		$card_start_index=strpos($card_start,$batch_sn);
        		if($card_start_index>=0){
        			$card_start=str_replace($batch_sn,'',$card_start);
        		}
        		$card_start=preg_replace('/\D/','',$card_start);
        		$card_start=substr($card_start,strlen($card_start)-8,6);
        		$card_start=$card_start==""?'00000':$card_start;
        	}
        	$card_start+=1;
        	$card_sn_data=array();
        	for($i=$card_start;$i<($card_start+$card_count);$i++){
        		$card_sn_data[]=$batch_sn.sprintf('%05s', $i);
        	}
        	$voucher_entity_card_data=$this->VoucherEntityCard->find('list',array('fields'=>array('VoucherEntityCard.card_sn','VoucherEntityCard.card_password'),'conditions'=>array('VoucherEntityCard.card_sn'=>$card_sn_data)));
        	$card_total=0;
        	foreach($card_sn_data as $v){
        		if(!isset($voucher_entity_card_data[$v])){
        			srand((double)microtime()*1000000);
        			$ychar="0,1,2,3,4,5,6,7,8,9";
        			$pwd_list=explode(",",$ychar);
        			$card_password="";
        			$card_password=$pwd_list[rand(1,9)];
        			for($i=0;$i<5;$i++){
					$randnum=rand(0,9);
					$card_password.=$pwd_list[$randnum];
				}
        			$card_data=array();
        			$card_data['id']='0';
        			$card_data['batch_sn']=$batch_sn;
				$card_data['card_sn']=$v;
				$card_data['card_password']=$card_password;
				$card_data['status']='0';
				$card_data['product_code']=$entity_card_auto_product;
				$card_data['amount']=$entity_card_auto_amount;
				$card_data['start_date']=$entity_card_auto_start_time;
				$card_data['end_date']=$entity_card_auto_end_time;
				$this->VoucherEntityCard->save($card_data);
				$card_total++;
        		}
        	}
        	if($card_total==$card_count){
        		$result['flag']='1';
        	}
		$result['message']="共计生成{$card_total}张";
    		$this->VoucherOperation->save(array(
    			'batch_sn'=>$batch_sn,
    			'operator_id'=>$this->admin['id'],
    			'num'=>$card_total,
    			'ip_address'=>'',
    			'macaddr'=>'',
    			'remark'=>"自动生成{$card_total}张"
    		));
        	die(json_encode($result));
    }
    
    function entity_card($batch_sn='',$page=1){
    		$this->operator_privilege('voucher_edit');
    		Configure::write('debug', 1);
		$this->layout = 'ajax';
		
		$this->set('entity_card_status_resource',$this->entity_card_status_resource);
		
    		$batch_sn=isset($_REQUEST['batch_sn'])?$_REQUEST['batch_sn']:$batch_sn;
    		$condition = '';
    		$condition['VoucherEntityCard.batch_sn']=$batch_sn;
    		if(isset($_REQUEST['entity_card_status'])&&$_REQUEST['entity_card_status']!=""){
    			$entity_card_status=$_REQUEST['entity_card_status'];
    			$condition['and']['VoucherEntityCard.status']=$entity_card_status;
    			$this->set('entity_card_status',$entity_card_status);
    		}
    		if(isset($_REQUEST['entity_card_keywords'])&&trim($_REQUEST['entity_card_keywords'])!=""){
    			$entity_card_keywords=trim($_REQUEST['entity_card_keywords']);
    			$condition['and']['or']['VoucherEntityCard.card_sn like']="%".$entity_card_keywords."%";
    			
    			$voucher_pro_search_cond=array();
    			$voucher_pro_search_cond['Product.status']="1";
			$voucher_pro_search_cond['Product.forsale']="1";
			$voucher_pro_search_cond['or']['Product.code like']="%".$entity_card_keywords."%";
			$voucher_pro_search_cond['or']['ProductI18n.name like']="%".$entity_card_keywords."%";
			$voucher_pro_search_info=$this->Product->find('all',array('fields'=>array('Product.code'),'conditions'=>$voucher_pro_search_cond));
			$voucher_pro_search_data=array();
			foreach($voucher_pro_search_info as $v){
				$voucher_pro_search_data[]=$v['Product']['code'];
			}
			if(!empty($voucher_pro_search_data)){
				$condition['and']['or']['VoucherEntityCard.product_code']=$voucher_pro_search_data;
			}
    			$this->set('entity_card_keywords',$entity_card_keywords);
    		}
    		if(isset($_REQUEST['entity_card_sn_start'])&&$_REQUEST['entity_card_sn_start']!=""){
    			$entity_card_sn_start=$_REQUEST['entity_card_sn_start'];
    			$condition['and']['VoucherEntityCard.card_sn >=']=$entity_card_sn_start;
    			$this->set('entity_card_sn_start',$entity_card_sn_start);
    		}
    		if(isset($_REQUEST['entity_card_sn_end'])&&$_REQUEST['entity_card_sn_end']!=""){
    			$entity_card_sn_end=$_REQUEST['entity_card_sn_end'];
    			$condition['and']['VoucherEntityCard.card_sn <=']=$entity_card_sn_end;
    			$this->set('entity_card_sn_end',$entity_card_sn_end);
    		}
    		$total = $this->VoucherEntityCard->find('count',array('conditions'=>$condition));
		$this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
	        if (isset($_GET['page']) && $_GET['page'] != '') {
	            $page = $_GET['page'];
	        }
        	 $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        	 $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        	 $parameters['get'] = array();
	        //地址路由参数（和control,action的参数对应）
	        $parameters['route'] = array('controller' => 'vouchers','action' => 'entity_card','page' => $page,'limit' => $rownum);
	        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'VoucherEntityCard');
	        $this->Pagination->init($condition, $parameters, $options);
	        $voucher_entity_card_list = $this->VoucherEntityCard->find('all', array('conditions' => $condition, 'order' => 'VoucherEntityCard.status,VoucherEntityCard.id', 'limit' => $rownum, 'page' => $page));
	        $this->set('voucher_entity_card_list',$voucher_entity_card_list);
	        
	        $voucher_data=$this->Voucher->find('first',array('conditions'=>array('Voucher.batch_sn'=>$batch_sn)));
	        $this->set('voucher_data',$voucher_data);
	        
	        $product_codes=array();
	        $voucher_batch_sn=array();
	        if(!empty($voucher_entity_card_list)){
	        	foreach($voucher_entity_card_list as $v){
	        		$product_codes[]=$v['VoucherEntityCard']['product_code'];
	        	}
	        	$pro_cond['Product.status']='1';
	        	$pro_cond['Product.forsale']='1';
	        	$pro_cond['Product.code']=$product_codes;
	        	$product_infos=$this->Product->find('all',array('fields'=>array('Product.code','ProductI18n.name'),'conditions'=>$pro_cond));
	        	
	        	$product_list=array();
	        	foreach($product_infos as $v){
	        		$product_list[$v['Product']['code']]=$v['ProductI18n']['name'];
	        	}
	        	$this->set('product_list',$product_list);
	        }
    }
    
    function entity_card_view($card_id=0,$voucher_id=0){
    		$this->operator_privilege('voucher_edit');
    		$this->menu_path = array('root' => '/oms/','sub' => '/vouchers/');
        	$this->set('title_for_layout', $this->ld['voucher'].' - '.$this->configs['shop_name']);
        	$this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        	$this->navigations[] = array('name' => $this->ld['voucher'],'url' => '/vouchers/');
    		$voucher_data=$this->Voucher->find('first',array('conditions'=>array('Voucher.id'=>$voucher_id)));
        	if(empty($voucher_data)){
        		$this->redirect('/vouchers/');
        	}
        	$this->navigations[] = array('name' => $voucher_data['Voucher']['name'],'url' => '/vouchers/view/'.$voucher_id);
        	$this->navigations[] = array('name' => $this->ld['add_edit'],'url' => '');
        	
        	$this->set('entity_card_status_resource',$this->entity_card_status_resource);
        	
    		if ($this->RequestHandler->isPost()) {
    			if($this->data['VoucherEntityCard']['status']=='0'||$this->data['VoucherEntityCard']['status']=='1'){
    				$this->data['VoucherEntityCard']['use_time']="0000-00-00 00:00:00";
    				$this->data['VoucherEntityCard']['frozen_time']="0000-00-00 00:00:00";
    				$this->data['VoucherEntityCard']['ipaddress']="";
    				$this->data['VoucherEntityCard']['order_id']="0";
    			}
        		$this->VoucherEntityCard->save($this->data['VoucherEntityCard']);
        		$this->VoucherOperation->save(array(
				    			'batch_sn'=>$voucher_data['Voucher']['batch_sn'],
				    			'operator_id'=>$this->admin['id'],
				    			'num'=>1,
				    			'ip_address'=>'',
				    			'macaddr'=>'',
				    			'remark'=>"编辑实体卡:".$this->data['VoucherEntityCard']['card_sn']." 信息"
				    		));
        		$this->redirect('/vouchers/view/'.$voucher_id);
        	}
        	$this->set('voucher_data',$voucher_data);
    		$voucher_entity_card_data=$this->VoucherEntityCard->find('first',array('conditions'=>array('VoucherEntityCard.id'=>$card_id)));
    		$this->set('voucher_entity_card_data',$voucher_entity_card_data);
    		if(!empty($voucher_entity_card_data['VoucherEntityCard']['order_id'])){
    			$this->loadModel('Order');
    			$order_data=$this->Order->find('first',array('conditions'=>array('Order.id'=>$voucher_entity_card_data['VoucherEntityCard']['order_id'])));
    			$this->set('order_data',$order_data);
    		}
    }
    
    function upload($batch_sn=''){
    		$this->operator_privilege('voucher_add');
    		$this->menu_path = array('root' => '/oms/','sub' => '/vouchers/');
        	$this->set('title_for_layout', $this->ld['bulk_upload'].' - '.$this->ld['voucher'].' - '.$this->configs['shop_name']);
        	$this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        	$this->navigations[] = array('name' => $this->ld['voucher'],'url' => '/vouchers/');
        	$this->navigations[] = array('name' => $this->ld['import'],'url' => '/vouchers/upload/');
        	
        	$voucher_data=$this->Voucher->find('first',array('conditions'=>array('Voucher.batch_sn'=>$batch_sn)));
        	if(empty($voucher_data)){
        		$this->redirect('/vouchers/');
        	}
        	$this->set('batch_sn',$batch_sn);
        	
        	$flag_code="voucher_import";
        	$this->Profile->set_locale($this->backend_locale);
        	$profilefiled_codes = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
        	if (isset($profilefiled_codes) && !empty($profilefiled_codes)) {
			$this->set('profilefiled_codes', $profilefiled_codes);
	       }
    }
    
    function uploadpreview($batch_sn=''){
    		$this->operator_privilege('voucher_add');
		$this->menu_path = array('root' => '/oms/','sub' => '/vouchers/');
        	$this->set('title_for_layout', $this->ld['preview'].' - '.$this->ld['voucher'].' - '.$this->configs['shop_name']);
        	$this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        	$this->navigations[] = array('name' => $this->ld['voucher'],'url' => '/vouchers/');
        	$this->navigations[] = array('name' => $this->ld['import'],'url' => '/vouchers/upload/');
        	$this->navigations[] = array('name' => $this->ld['preview'],'url' => '');
        	
		$voucher_data=$this->Voucher->find('first',array('conditions'=>array('Voucher.batch_sn'=>$batch_sn)));
        	if(empty($voucher_data)){
        		$this->redirect('/vouchers/');
        	}
        	$this->set('batch_sn',$batch_sn);
        	
		if ($this->RequestHandler->isPost()) {
			$flag_code = 'voucher_import';
			$this->loadModel('ProfileFiled');
            		$this->Profile->set_locale($this->backend_locale);
            		set_time_limit(300);
            		if (!empty($_FILES['file'])) {
            			if ($_FILES['file']['error'] > 0) {
                    		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/vouchers/upload';</script>";
                    		die();
                		} else {
					$handle = @fopen($_FILES['file']['tmp_name'], 'r');
					$profile_info = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
					$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
					if (empty($profilefiled_info)) {
						echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/vouchers/upload';</script>";
						die();
					}
					$key_arr = array();
					$key_desc=array();
					$key_code=array();
					foreach ($profilefiled_info as $k => $v) {
						$fields_k=array();
						$fields_k = explode('.', $v['ProfileFiled']['code']);
						$key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
						$key_desc[]= $v['ProfilesFieldI18n']['description'];
						$key_code[$v['ProfilesFieldI18n']['description']]=isset($fields_k[1]) ? $fields_k[1] : '';
					}
					$this->set('key_code',$key_code);
					$preview_key=array();
					$csv_export_code = 'gb2312';
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
							if ($row_count > $num_count || $check_row != $profilefiled_info[0]['ProfilesFieldI18n']['description']) {
								echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('上传文件格式不标准');window.location.href='/admin/vouchers/upload';</script>";
								die();
							}
							++$i;
						}
						$temp = array();
						foreach ($row as $k => $v) {
							$data_key_code=isset($key_code[$preview_key[$k]])?$key_code[$preview_key[$k]]:'';
							$temp[$preview_key[$k]] = ($v == '' ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v));
							if (isset($key_arr[$k]) && !empty($key_arr[$k])) {
								$temp[$data_key_code] = ($v == '' ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v));
							}
						}
		                        	if (!isset($temp) || empty($temp)) {
		                            	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/vouchers/upload';</script>";
		                            	die();
		                        	}
		                        	$data[] = $temp;
		                     }
		                     fclose($handle);
					$this->set('profilefiled_info', $profilefiled_info);
					$card_sn_data=array();
					foreach($data as $k=>$v){
						if($k==0)continue;
						$card_sn_data[]=$v['card_sn'];
					}
					if(!empty($card_sn_data)){
						$voucher_entity_card_data=$this->VoucherEntityCard->find('list',array('fields'=>array('VoucherEntityCard.card_sn','VoucherEntityCard.card_password'),'conditions'=>array('VoucherEntityCard.card_sn'=>$card_sn_data)));
						foreach($data as $k=>$v){
							if($k==0)continue;
							if(isset($voucher_entity_card_data[$v['card_sn']])){
								$data[$k]['upload_data_unique']='1';
							}else{
								$data[$k]['upload_data_unique']='0';
							}
						}
					}
					$this->set('uploads_list', $data);
                		}
            		}
		}else{
			$this->redirect('/vouchers/upload/');
		}
    }
    
    function batch_add($batch_sn=''){
    		$this->operator_privilege('voucher_add');
    		$voucher_data=$this->Voucher->find('first',array('conditions'=>array('Voucher.batch_sn'=>$batch_sn)));
        	if(empty($voucher_data)){
        		$this->redirect('/vouchers/');
        	}
    		if (!empty($this->data)) {
    			$checkbox_arr = $_REQUEST['checkbox'];
    			foreach ($this->data as $key => $data) {
				if (!in_array($key, $checkbox_arr)) {
					continue;
				}
				if(empty($data['card_sn'])){
					continue;
				}
				if(trim($data['card_sn'])==""){
					continue;
				}
				$card_info = $this->VoucherEntityCard->find('first', array('conditions' => array('VoucherEntityCard.card_sn' => trim($data['card_sn']))));
				if(!empty($card_info)){
					continue;
				}
				if(trim($data['card_password'])==""){
					continue;
				}
				
				$card_data=array();
				$card_data=$data;
				$card_data['id']='0';
				$card_data['status']='0';
				$card_data['batch_sn']=$batch_sn;
				$this->VoucherEntityCard->save($card_data);
    			}
    		}
    		$this->redirect('/vouchers/');
    }
    
    function download_csv_example(){
    		Configure::write('debug',0);
		$this->loadModel('ProfileFiled');
        	$this->Profile->set_locale($this->backend_locale);
        	$flag_code = 'voucher_import';
        	$profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1),'recursive'=>-1));
		$tmp = array();
		$fields_array = array();
		$newdatas = array();
        	if (isset($profile_id) && !empty($profile_id)) {
        		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
			foreach ($profilefiled_info as $k => $v) {
				$tmp[] = $v['ProfilesFieldI18n']['description'];
				$fields_array[] = $v['ProfileFiled']['code'];
			}
        	}
        	$newdatas[] = $tmp;
        	
        	$entity_card_data = $this->VoucherEntityCard->find('all', array('order' => 'VoucherEntityCard.id desc', 'limit' => 10));
        	foreach($entity_card_data  as $v){
        		$entity_card_tmp = array();
			foreach ($fields_array as $kk => $vv){
				$fields_kk = explode('.', $vv);
				$entity_card_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
			}
			$newdatas[] = $entity_card_tmp;
        	}
        	$filename = $this->ld['voucher'].'import'.date('Ymd').'.csv';
        	$this->Phpcsv->output($filename, $newdatas);
        	exit;
    }
    
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
    
    function check_batch_sn(){
    		Configure::write('debug',0);
    		$this->layout='ajax';
    		$data_count=-1;
    		if ($this->RequestHandler->isPost()) {
    			$voucher_id=isset($_POST['voucher_id'])?$_POST['voucher_id']:0;
    			$batch_sn=isset($_POST['batch_sn'])?trim($_POST['batch_sn']):'';
    			$data_count=$this->Voucher->find('count',array('conditions'=>array('Voucher.batch_sn'=>$batch_sn,'Voucher.id <>'=>$voucher_id)));
    		}
    		echo $data_count;
    		die();
    }
    
    function check_card_sn(){
    		Configure::write('debug',0);
    		$this->layout='ajax';
    		$data_count=-1;
    		if ($this->RequestHandler->isPost()) {
    			$entity_card_id=isset($_POST['entity_card_id'])?$_POST['entity_card_id']:0;
    			$card_sn=isset($_POST['card_sn'])?trim($_POST['card_sn']):'';
    			$data_count=$this->VoucherEntityCard->find('count',array('conditions'=>array('VoucherEntityCard.card_sn'=>$card_sn,'VoucherEntityCard.id <>'=>$entity_card_id)));
    		}
    		echo $data_count;
    		die();
    }
    
    function remove($voucher_id=0){
    		$this->operator_privilege('voucher_remove');
    		Configure::write('debug',0);
    		$this->layout="ajax";
    		$result['flag'] = 2;
        	$result['message'] = $this->ld['delete_failure'];
    		$voucher_data=$this->Voucher->find('first',array('conditions'=>array('Voucher.id'=>$voucher_id)));
        	if(!empty($voucher_data)){
        		$batch_sn=$voucher_data['Voucher']['batch_sn'];
        		$this->VoucherEntityCard->deleteAll(array('VoucherEntityCard.batch_sn'=>$batch_sn));
        		$this->VoucherOperation->deleteAll(array('VoucherOperation.batch_sn'=>$batch_sn));
        		$this->Voucher->deleteAll(array('Voucher.id'=>$voucher_id));
        		$result['flag'] = 1;
        		$result['message'] = $this->ld['deleted_success'];
        	}
        	die(json_encode($result));
    }
    
    function batch_remove(){
    		$this->operator_privilege('voucher_remove');
    		Configure::write('debug',0);
    		$this->layout="ajax";
    		if ($this->RequestHandler->isPost()) {
    			$voucher_ids=isset($_POST['checkboxes'])?$_POST['checkboxes']:'0';
    			$batch_sn_list=$this->Voucher->find('list',array('fields'=>array('Voucher.id','Voucher.batch_sn'),'conditions'=>array('Voucher.id'=>$voucher_ids)));
    			if(!empty($batch_sn_list)){
    				$this->VoucherEntityCard->deleteAll(array('VoucherEntityCard.batch_sn'=>$batch_sn_list));
        			$this->VoucherOperation->deleteAll(array('VoucherOperation.batch_sn'=>$batch_sn_list));
        			$this->Voucher->deleteAll(array('Voucher.id'=>$voucher_ids));
    			}
    		}
    		$back_url = $this->operation_return_url();
            	$this->redirect($back_url);
    }
    
    function batch_voucher_remark(){
    		$this->operator_privilege('voucher_edit');
    		Configure::write('debug',1);
    		$this->layout="ajax";
    		
    		$result['flag'] = 0;
        	$result['message'] = $this->ld['operation_failed'];
        	$voucher_ids=isset($_POST['voucher_ids'])?$_POST['voucher_ids']:'';
        	$voucher_remark=isset($_POST['voucher_remark'])?$_POST['voucher_remark']:'';
        	$voucher_id=split(",",$voucher_ids);
        	if(!empty($voucher_id)&&$voucher_remark!=''){
        		foreach($voucher_id as $v){
        			$this->Voucher->save(array("id"=>$v,"remark"=>$voucher_remark));
        		}
        		$result['flag'] = 1;
        		$result['message'] = $this->ld['operation_success'];
        	}
    		die(json_encode($result));
    }
    
    function entity_card_remove($entity_card=0){
    		$this->operator_privilege('voucher_remove');
    		Configure::write('debug',0);
    		$this->layout="ajax";
    		$result['flag'] = 2;
        	$result['message'] = $this->ld['delete_failure'];
    		$this->VoucherEntityCard->deleteAll(array('VoucherEntityCard.id'=>$entity_card));
    		$result['flag'] = 1;
    		$result['message'] = $this->ld['deleted_success'];
        	die(json_encode($result));
    }
    
    function entity_card_batch($voucher_id=0){
    		Configure::write('debug',1);
    		$this->layout="ajax";
    		if ($this->RequestHandler->isPost()) {
    			$batch_sn=isset($_POST['batch_sn'])?$_POST['batch_sn']:'';
	    		if(isset($_POST['entity_card_batch_type'])&&!empty($_POST['entity_card_batch_type'])){
	    			$entity_card_batch_type=trim($_POST['entity_card_batch_type']);
	    			$entity_card_ids=isset($_POST['entity_card_checkboxes'])?$_POST['entity_card_checkboxes']:0;
	    			$entity_cards=$this->VoucherEntityCard->find('list',array('fields'=>"VoucherEntityCard.card_sn",'conditions'=>array('VoucherEntityCard.id'=>$entity_card_ids)));
	    			if($entity_card_batch_type=='batch_remove'){
	    				$this->operator_privilege('voucher_remove');
	    				$this->VoucherEntityCard->deleteAll(array('VoucherEntityCard.id'=>$entity_card_ids));
	    				$this->VoucherOperation->save(array(
			    			'batch_sn'=>$batch_sn,
			    			'operator_id'=>$this->admin['id'],
			    			'num'=>sizeof($entity_card_ids),
			    			'ip_address'=>'',
			    			'macaddr'=>'',
			    			'remark'=>"批量删除".sizeof($entity_card_ids)."张实体卡 ".implode(";",$entity_cards)
			    		));
	    			}else if($entity_card_batch_type=='batch_export'){
	    				$this->entity_card_export($entity_card_ids);
	    			}else if($entity_card_batch_type=='batch_status'){
	    				$entity_card_batch_status=isset($_POST['entity_card_batch_status'])?$_POST['entity_card_batch_status']:'';
	    				if($entity_card_batch_status!=''){
	    					$update_data=array();
	    					$update_data['VoucherEntityCard.status']=$entity_card_batch_status;
	    					if($entity_card_batch_status=='0'||$entity_card_batch_status=='1'){
	    						$update_data['VoucherEntityCard.use_time']="'0000-00-00 00:00:00'";
    							$update_data['VoucherEntityCard.frozen_time']="'0000-00-00 00:00:00'";
    							$update_data['VoucherEntityCard.ipaddress']=NULL;
    							$update_data['VoucherEntityCard.order_id']="'0'";
	    					}
	    					$this->VoucherEntityCard->updateAll($update_data,array("VoucherEntityCard.id"=>$entity_card_ids,'VoucherEntityCard.status <>'=>'2'));
	    					$this->VoucherOperation->save(array(
				    			'batch_sn'=>$batch_sn,
				    			'operator_id'=>$this->admin['id'],
				    			'num'=>sizeof($entity_card_ids),
				    			'ip_address'=>'',
				    			'macaddr'=>'',
				    			'remark'=>"批量修改".sizeof($entity_card_ids)."张实体卡状态为:".(isset($this->entity_card_status_resource[$entity_card_batch_status])?$this->entity_card_status_resource[$entity_card_batch_status]:$entity_card_batch_status)." ".implode(";",$entity_cards)
				    		));
	    					
	    				}
	    			}else if($entity_card_batch_type=='batch_edit'){
	    				$update_txt=array();
	    				$update_data=array();
	    				if(isset($_POST['entity_card_batch_amount'])&&!empty($_POST['entity_card_batch_amount'])){
	    					$update_data['VoucherEntityCard.amount']="'".$_POST['entity_card_batch_amount']."'";
	    					$update_txt[]=$this->ld['denomination'].":".$_POST['entity_card_batch_amount'];
	    				}
	    				if(isset($_POST['entity_card_batch_product'])&&!empty($_POST['entity_card_batch_product'])){
	    					$update_data['VoucherEntityCard.product_code']="'".$_POST['entity_card_batch_product']."'";
	    					$update_txt[]=$this->ld['exchange_item'].":".$_POST['entity_card_batch_product'];
	    				}
	    				if(isset($_POST['entity_card_batch_start_time'])&&!empty($_POST['entity_card_batch_start_time'])){
	    					$update_data['VoucherEntityCard.start_date']="'".$_POST['entity_card_batch_start_time']."'";
	    					$update_txt[]=$this->ld['variable_start_time'].":".$_POST['entity_card_batch_start_time'];
	    				}
	    				if(isset($_POST['entity_card_batch_end_time'])&&!empty($_POST['entity_card_batch_end_time'])){
	    					$update_data['VoucherEntityCard.end_date']="'".$_POST['entity_card_batch_end_time']."'";
	    					$update_txt[]=$this->ld['variable_end_time'].":".$_POST['entity_card_batch_end_time'];
	    				}
	    				if(isset($_POST['entity_card_batch_remark'])&&trim($_POST['entity_card_batch_remark'])!=""){
	    					$update_data['VoucherEntityCard.remark']="'".trim($_POST['entity_card_batch_remark'])."'";
	    					$update_txt[]=$this->ld['remarks_notes'].":".trim($_POST['entity_card_batch_remark']);
	    				}
	    				if(!empty($update_data)){
	    					$result=$this->VoucherEntityCard->updateAll($update_data,array("VoucherEntityCard.id"=>$entity_card_ids,'VoucherEntityCard.status'=>array('0','1')));
	    					$this->VoucherOperation->save(array(
				    			'batch_sn'=>$batch_sn,
				    			'operator_id'=>$this->admin['id'],
				    			'num'=>sizeof($entity_card_ids),
				    			'ip_address'=>'',
				    			'macaddr'=>'',
				    			'remark'=>"批量修改".sizeof($entity_card_ids)."张实体卡信息 ".implode(";",$entity_cards).' '.implode(";",$update_txt)
				    		));
	    				}
	    				die("SUCCESS");
	    			}
	    		}
    		}
    		die();
    }
    
    function entity_card_export($entity_card_id=0){
    		Configure::write('debug',0);
    		$condition="";
    		if(!empty($entity_card_id)){
    			$condition['VoucherEntityCard.id']=$entity_card_id;
    		}
    		$voucher_card_info = $this->VoucherEntityCard->find('all', array('conditions' => $condition,'order'=>'VoucherEntityCard.batch_sn,VoucherEntityCard.id'));
    		if(!empty($voucher_card_info)){
    			$this->loadModel('ProfileFiled');
        		$this->Profile->set_locale($this->backend_locale);
        		$flag_code = 'voucher_import';
        		$profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1),'recursive'=>-1));
	        	if (isset($profile_id) && !empty($profile_id)) {
				$tmp = array();
				$fields_array = array();
				$newdatas = array();
	        		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
				foreach ($profilefiled_info as $k => $v) {
					$tmp[] = $v['ProfilesFieldI18n']['description'];
					$fields_array[] = $v['ProfileFiled']['code'];
				}
				$newdatas[] = $tmp;
				foreach($voucher_card_info  as $v){
		        		$entity_card_tmp = array();
					foreach ($fields_array as $kk => $vv){
						$fields_kk = explode('.', $vv);
						$entity_card_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
					}
					$newdatas[] = $entity_card_tmp;
		        	}
		        	$filename = $this->ld['voucher'].'export'.date('Ymd').'.csv';
		        	$this->Phpcsv->output($filename, $newdatas);
		        	exit();
	        	}else{
	        		$this->redirect('/vouchers/');
	        	}
    		}
    }
}
