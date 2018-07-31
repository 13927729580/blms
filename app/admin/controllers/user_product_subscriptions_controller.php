<?php
/**
 *这是一个名为 UserProductSubscriptionsController 的商品订阅控制器.
 *
 *@var
 *@var
 *@var
 *@var
 *@var
 *@var
 */
class UserProductSubscriptionsController extends AppController
{
    public $name = 'UserProductSubscriptions';
    public $helpers = array('Html','Flash','Cache','Pagination');
    public $uses = array('Brand','CategoryProduct','ProductType','Attribute','ProductTypeAttribute','UserProductSubscription','InformationResource','User','Operator','UserCategory','UserActionLog');
    public $components = array('RequestHandler','Cookie','Session','Captcha','Notify','Pagination');
    
    public function index($page=1){
		$this->operator_privilege('product_subscription_view');
		$this->operation_return_url(true);//设置操作返回页面地址
		$this->menu_path = array('root' => '/system/','sub' => '/notify_templates/');
		$this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
		$this->navigations[] = array('name' => $this->ld['product_subscription'],'url' => '/user_product_subscriptions/');
		
		$condition=array();
		$condition['UserProductSubscription.user_id <>']=0;
		if (isset($_REQUEST['keyword']) && trim($_REQUEST['keyword'])!='') {
			$keyword=trim($_REQUEST['keyword']);
			$condition['or']['UserProductSubscription.name like']="%".$keyword."%";
			$condition['or']['User.email like']="%".$keyword."%";
			$condition['or']['User.first_name like']="%".$keyword."%";
			$condition['or']['User.mobile like']="%".$keyword."%";
			$this->set('keyword',$keyword);
		}
		if($this->operator_privilege('users_advanced',false)){
			if (isset($_REQUEST['user_manager']) && trim($_REQUEST['user_manager'])!='') {
				$user_manager=trim($_REQUEST['user_manager']);
				$condition['User.operator_id']=$user_manager;
				$this->set('user_manager',$user_manager);
			}
		}else{
			$condition['User.operator_id']=$this->admin['id'];
		}
		if (isset($_REQUEST['user_category']) && trim($_REQUEST['user_category'])!='') {
			$user_category=trim($_REQUEST['user_category']);
			$condition['User.category_id']=$user_category;
			$this->set('user_category',$user_category);
		}
		if (isset($_REQUEST['brand_id']) && !empty($_REQUEST['brand_id'])) {
			$brand_id=$_REQUEST['brand_id'];
			$brand_id_txt=implode(",",$brand_id);
			$condition['UserProductSubscription.brand like']="%".$brand_id_txt."%";
			$this->set('brand_id',$brand_id);
		}
		if (isset($_REQUEST['category_id']) && !empty($_REQUEST['category_id'])) {
			$category_id=$_REQUEST['category_id'];
			$category_id_txt=implode(",",$category_id);
			$condition['UserProductSubscription.category like']="%".$category_id_txt."%";
			$this->set('category_id',$category_id);
		}
		if (isset($_REQUEST['product_type_id']) && !empty($_REQUEST['product_type_id'])) {
			$product_type_id=$_REQUEST['product_type_id'];
			$product_type_id_txt=implode(",",$product_type_id);
			$condition['UserProductSubscription.product_type like']="%".$product_type_id_txt."%";
			$this->set('product_type_id',$product_type_id);
		}
		if (isset($_REQUEST['attribute_value']) && !empty($_REQUEST['attribute_value'])) {
			$attribute_value=$_REQUEST['attribute_value'];
			$attribute_value_txt=implode(chr(13).chr(10),$attribute_value);
			$condition['UserProductSubscription.attribute_value like']="%".$attribute_value_txt."%";
			$this->set('attribute_value',$attribute_value);
		}
		if (isset($_REQUEST['status']) &&$_REQUEST['status']!='') {
			$status=$_REQUEST['status'];
			$condition['UserProductSubscription.status']=$status;
			$this->set('status',$status);
		}
		if (isset($_REQUEST['send_time']) && !empty($_REQUEST['send_time'])) {
			$send_time=$_REQUEST['send_time'];
			$condition['UserProductSubscription.send_time']=$send_time;
			$this->set('send_time',$send_time);
		}
		if (isset($_GET['page']) && $_GET['page'] != '') {
            	$page = $_GET['page'];
        	}
		$total = $this->UserProductSubscription->find('count', array('conditions' => $condition));
		$this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
		$rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'user_product_subscriptions','action' => 'index','page' => $page,'limit' => $rownum);
		$options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserProductSubscription');
		$this->Pagination->init($condition, $parameters, $options);
		$fields="UserProductSubscription.*,User.user_sn,User.name,User.first_name,User.email,User.category_id,User.operator_id";
		$subscription_data = $this->UserProductSubscription->find('all', array('fields'=>$fields,'page' => $page, 'limit' => $rownum, 'conditions' => $condition,"order"=>'UserProductSubscription.id desc'));
		$this->set('subscription_data',$subscription_data);
		
		$this->Brand->set_locale($this->backend_locale);
		$brand_infos=$this->Brand->find('all', array('fields' => 'Brand.id,BrandI18n.name', 'conditions' => array('Brand.status' =>'1')));
		$category_tree = $this->CategoryProduct->tree('P','all',$this->backend_locale);
		$this->ProductType->set_locale($this->backend_locale);
		$product_type_infos=$this->ProductType->find('all',array('fields' => 'ProductType.id,ProductTypeI18n.name','conditions'=>array('ProductType.status'=>'1')));
		$public_attribute_ids=$this->ProductTypeAttribute->find('list',array('fields'=>"ProductTypeAttribute.id,attribute_id",'conditions'=>array('ProductTypeAttribute.product_type_id'=>0)));
		$public_attribute_infos=array();
		if(!empty($public_attribute_ids)){
			$this->Attribute->set_locale($this->backend_locale);
			$public_attribute_infos=$this->Attribute->find('all',array('fields' => 'Attribute.id,AttributeI18n.name', 'conditions'=>array('Attribute.id'=>$public_attribute_ids,"Attribute.status"=>'1'),"order"=>"Attribute.id"));
		}
		$this->set('brand_data',$brand_infos);
		$this->set('category_data',$category_tree);
		$this->set('product_type_data',$product_type_infos);
		$this->set('public_attribute_data',$public_attribute_infos);
		
		$informationresource_infos = $this->InformationResource->information_formated(array('product_subscription'), $this->backend_locale,false);
		$this->set('informationresource_infos',$informationresource_infos);
		
		//用户管理员
		$Operator_list = $this->Operator->find('list',array('fields'=>"Operator.id,Operator.name","order"=>"Operator.name"));
		$this->set('Operator_list', $Operator_list);
		
		$UserCategory_data=$this->UserCategory->find('list',array('conditions'=>array('UserCategory.status'=>'1'),'fields'=>"UserCategory.id,UserCategory.name"));
        	$this->set('UserCategory_data',$UserCategory_data);
		
		$this->set('title_for_layout', $this->ld['product_subscription'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }
    
    function view($id=0){
    		if(empty($id)){
    			$this->operator_privilege('product_subscription_add');
    		}else{
    			$this->operator_privilege('product_subscription_edit');
    		}
		$this->menu_path = array('root' => '/system/','sub' => '/notify_templates/');
		$this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
		$this->navigations[] = array('name' => $this->ld['product_subscription'],'url' => '/user_product_subscriptions/');
		if(empty($id)){
			$this->navigations[] = array('name' => $this->ld['add'],'url' => '');
		}else{
			$this->navigations[] = array('name' => $this->ld['edit'],'url' => '');
		}
		if ($this->RequestHandler->isPost()) {
			if(!empty($this->data['UserProductSubscription'])){
				if(isset($this->data['UserProductSubscription']['brand'])){
					$this->data['UserProductSubscription']['brand']=implode(",",$this->data['UserProductSubscription']['brand']);
				}else{
					$this->data['UserProductSubscription']['brand']="";
				}
				if(isset($this->data['UserProductSubscription']['category'])){
					$this->data['UserProductSubscription']['category']=implode(",",$this->data['UserProductSubscription']['category']);
				}else{
					$this->data['UserProductSubscription']['category']="";
				}
				if(isset($this->data['UserProductSubscription']['product_type'])){
					$this->data['UserProductSubscription']['product_type']=implode(",",$this->data['UserProductSubscription']['product_type']);
				}else{
					$this->data['UserProductSubscription']['product_type']="";
				}
				if(isset($this->data['UserProductSubscription']['attribute_value'])){
					$this->data['UserProductSubscription']['attribute_value']=implode(chr(13).chr(10),$this->data['UserProductSubscription']['attribute_value']);
				}else{
					$this->data['UserProductSubscription']['attribute_value']="";
				}
				$this->UserProductSubscription->save($this->data['UserProductSubscription']);
				$UserProductSubscription_id=$this->UserProductSubscription->id;
				if(isset($this->configs['enable_user_log'])&&$this->configs['enable_user_log']=='1'){
					$this->UserActionLog->update_action(array(
						'user_id'=>$this->data['UserProductSubscription']['user_id'],
						'operator_id'=>$this->admin['id'],
						'remark'=>$this->ld['product_subscription']
					),$this,'/user_product_subscriptions/view/'.$UserProductSubscription_id);
				}
			}
			$back_url = $this->operation_return_url();//获取操作返回页面地址
            		$this->redirect($back_url);
		}
		$condition=array();
		$condition['UserProductSubscription.id']=$id;
		if(!$this->operator_privilege('users_advanced',false)){
			$condition['UserProductSubscription.operator_id']=$this->admin['id'];
		}
		$subscription_data = $this->UserProductSubscription->find('first', array('conditions' =>$condition));
		if(!empty($id)&&empty($subscription_data)&&!$this->operator_privilege('users_advanced',false)){
			$this->redirect('index');
		}
		if(!empty($subscription_data)){
			$subscription_data['UserProductSubscription']['brand']=explode(',', $subscription_data['UserProductSubscription']['brand']);
			$subscription_data['UserProductSubscription']['product_type'] = explode(',',$subscription_data['UserProductSubscription']['product_type']);
			$subscription_data['UserProductSubscription']['category'] =  explode(',',$subscription_data['UserProductSubscription']['category']);
			$subscription_data['UserProductSubscription']['attribute_value'] = explode(chr(13).chr(10),$subscription_data['UserProductSubscription']['attribute_value']);
		}
		$this->set('subscription_data',$subscription_data);
		
		$this->Brand->set_locale($this->backend_locale);
		$brand_infos=$this->Brand->find('all', array('fields' => 'Brand.id,BrandI18n.name', 'conditions' => array('Brand.status' =>'1')));
		$category_tree = $this->CategoryProduct->tree('P','all',$this->backend_locale);
		$this->ProductType->set_locale($this->backend_locale);
		$product_type_infos=$this->ProductType->find('all',array('fields' => 'ProductType.id,ProductTypeI18n.name','conditions'=>array('ProductType.status'=>'1')));
		$public_attribute_ids=$this->ProductTypeAttribute->find('list',array('fields'=>"ProductTypeAttribute.id,attribute_id",'conditions'=>array('ProductTypeAttribute.product_type_id'=>0)));
		$public_attribute_infos=array();
		if(!empty($public_attribute_ids)){
			$this->Attribute->set_locale($this->backend_locale);
			$public_attribute_infos=$this->Attribute->find('all',array('fields' => 'Attribute.id,AttributeI18n.name', 'conditions'=>array('Attribute.id'=>$public_attribute_ids,"Attribute.status"=>'1'),"order"=>"Attribute.id"));
		}
		$this->set('brand_data',$brand_infos);
		$this->set('category_data',$category_tree);
		$this->set('product_type_data',$product_type_infos);
		$this->set('public_attribute_data',$public_attribute_infos);
		
		$informationresource_infos = $this->InformationResource->information_formated(array('product_subscription'), $this->backend_locale,false);
		$this->set('informationresource_infos',$informationresource_infos);
		
		//用户管理员
		$Operator_list = $this->Operator->find('list',array('fields'=>"Operator.id,Operator.name","order"=>"Operator.name"));
		$this->set('Operator_list', $Operator_list);
		
		$this->set('title_for_layout', $this->ld['product_subscription'].' - '.$this->ld['add_edit'].' - '.$this->configs['shop_name']);
    }
    
    function remove($id=0){
    		$this->operator_privilege('product_subscription_remove');
		Configure::write('debug', 1);
		$this->layout="ajax";
		$this->UserProductSubscription->deleteAll(array('UserProductSubscription.id'=>$id));
		$back_url = $this->operation_return_url();//获取操作返回页面地址
		$this->redirect($back_url);
    }
    
    function toggle_on_status(){
    		$this->operator_privilege('product_subscription_edit');
    		Configure::write('debug', 1);
    		$this->layout="ajax";
    		$this->UserProductSubscription->belongsTo = array();
    		$result=array();
    		$result['flag']='0';
    		$result['content']="";
    		$id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
        	$val = isset($_REQUEST['val'])?$_REQUEST['val']:0;
        	if (is_numeric($val) && $this->UserProductSubscription->save(array('id' => $id, 'status' => $val))) {
    			$result['flag'] = 1;
            	$result['content'] = stripslashes($val);
    		}
    		die(json_encode($result));
    }
    
    function ajax_product_attribute(){
    		Configure::write('debug', 1);
    		$this->layout="ajax";
    		$result=array();
    		$result['code']='0';
    		$result['data']="";
    		$product_type=isset($_POST['product_type_id'])?$_POST['product_type_id']:"";
    		$product_type_id=explode(",",$product_type);
    		$product_type_id[]=0;
    		$product_attribute_ids=$this->ProductTypeAttribute->find('list',array('fields'=>"ProductTypeAttribute.id,attribute_id",'conditions'=>array('ProductTypeAttribute.product_type_id'=>$product_type_id)));
    		$this->Attribute->set_locale($this->backend_locale);
    		$attribute_infos=$this->Attribute->find('all',array('fields' => 'Attribute.id,AttributeI18n.name', 'conditions'=>array('Attribute.id'=>$product_attribute_ids,"Attribute.status"=>'1'),"order"=>"Attribute.id"));
		if(!empty($attribute_infos)){
			$result['code']='1';
    			$result['data']=$attribute_infos;
		}
		die(json_encode($result));
    }
}
