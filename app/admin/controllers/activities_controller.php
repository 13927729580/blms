<?php
class ActivitiesController extends  AppController{
	public $name="Activities";
	public $uses = array('Activity','OperatorLog','ActivityUser','ActivityUserConfig','ActivityConfig','ActivityPublisher','ActivityTag');
	public $helpers = array('Html','Pagination');
	public $components = array('Pagination','RequestHandler','Cookie');
	
	function index($page=1){
		$this->operator_privilege('activity_view');
        	$this->operation_return_url(true);//设置操作返回页面地址
        	$this->menu_path = array('root' => '/cms/','sub' => '/activities/');
        	$this->navigations[] = array('name' => '在线学习','url' => '');
        	$this->navigations[] = array('name' => $this->ld['activity'],'url' => '/activities/');
        	
        	$condition = '';
        	if (isset($this->params['url']['activity_type']) && $this->params['url']['activity_type'] != '') {
        		$condition['Activity.type']=$this->params['url']['activity_type'];
        		$this->set('activity_type',$this->params['url']['activity_type']);
        	}
        	if (isset($this->params['url']['activity_channel']) && $this->params['url']['activity_channel'] != '-1') {
        		$condition['Activity.channel']=$this->params['url']['activity_channel'];
        		$this->set('activity_channel',$this->params['url']['activity_channel']);
        	}
        	if (isset($this->params['url']['activity_start_date']) && $this->params['url']['activity_start_date'] != '') {
        		$activity_start_date=date('Y-m-d 00:00:00',strtotime($this->params['url']['activity_start_date']));
        		$condition['Activity.start_date >=']=$activity_start_date;
        		$this->set('activity_start_date',$this->params['url']['activity_start_date']);
        	}
        	if (isset($this->params['url']['activity_end_date']) && $this->params['url']['activity_end_date'] != '') {
        		$activity_end_date=date('Y-m-d 00:00:00',strtotime($this->params['url']['activity_end_date']));
        		$condition['Activity.start_date <=']=$activity_end_date;
        		$this->set('activity_end_date',$this->params['url']['activity_end_date']);
        	}
        	if (isset($this->params['url']['activity_status']) && $this->params['url']['activity_status'] != '') {
        		$condition['Activity.status']=$this->params['url']['activity_status'];
        		$this->set('activity_status',$this->params['url']['activity_status']);
        	}
        	if (isset($this->params['url']['activity_keyword']) && $this->params['url']['activity_keyword'] != '') {
        		$activity_keyword=trim($this->params['url']['activity_keyword']);
        		$condition['or']['Activity.name like']="%{$activity_keyword}%";
        		$condition['or']['Activity.description like']="%{$activity_keyword}%";
        		$this->set('activity_keyword',$activity_keyword);
        	}
        	if (isset($this->params['url']['page']) && intval($this->params['url']['page'])>0)$page=intval($this->params['url']['page']);
        	$total = $this->Activity->find('count', array('conditions' => $condition));
        	//活动总数限制
        	$max_activity_total=intval(Configure::read('HR.max_activity_total'));
        	$this->set('can_to_add',$max_activity_total>$total);
        	
        	$this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        	$this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
		$rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'activities','action' => 'index','page' => $page,'limit' => $rownum);
		$options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Activity');
		$this->Pagination->init($condition, $parameters, $options);
		$activity_list = $this->Activity->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'modified desc'));
		$this->set('activity_list',$activity_list);
		
		if(!empty($activity_list)){
			$activity_type_list=array();
			$activity_type_infos=array();
			foreach($activity_list  as $v){
				$activity_type_list[$v['Activity']['type']][]=$v['Activity']['type_id'];
			}
			foreach($activity_type_list as $activity_type=>$activity_type_ids){
				if($activity_type=='A'){
						$this->loadModel('Article');
						$this->Article->set_locale($this->backend_locale);
						$article_info=$this->Article->find('all',array('fields'=>'Article.id,ArticleI18n.title','conditions'=>array('Article.id'=>$activity_type_ids)));
						if(!empty($article_info)){
							foreach($article_info as $v){
								$activity_type_infos[$activity_type][$v['Article']['id']]=$v['ArticleI18n']['title'];
							}
						}
				}else if($activity_type=="P"){
						$this->loadModel('Product');
						$this->Product->set_locale($this->backend_locale);
						$product_info=$this->Product->find('all',array('fields'=>'Product.id,ProductI18n.name','conditions'=>array('Product.id'=>$activity_type_ids)));
						if(!empty($product_info)){
							foreach($product_info as $v){
								$activity_type_infos[$activity_type][$v['Product']['id']]=$v['ProductI18n']['name'];
							}
						}
				}else if($activity_type=="T"){
						$this->loadModel('Topic');
						$this->Topic->set_locale($this->backend_locale);
						$topic_info=$this->Topic->find('all',array('fields'=>'Topic.id,TopicI18n.title','conditions'=>array('Topic.id'=>$activity_type_ids)));
						if(!empty($topic_info)){
							foreach($topic_info as $v){
								$activity_type_infos[$activity_type][$v['Topic']['id']]=$v['TopicI18n']['title'];
							}
						}
				}else if($activity_type=="AC"){
						$this->loadModel('CategoryArticle');
						$this->CategoryArticle->set_locale($this->backend_locale);
						$category_info=$this->CategoryArticle->find('all',array('fields'=>'CategoryArticle.id,CategoryArticleI18n.name','conditions'=>array('CategoryArticle.id'=>$activity_type_ids)));
						if(!empty($category_info)){
							foreach($category_info as $v){
								$activity_type_infos[$activity_type][$v['CategoryArticle']['id']]=$v['CategoryArticleI18n']['name'];
							}
						}
				}else if($activity_type=="PC"){
						$this->loadModel('CategoryProduct');
						$this->CategoryProduct->set_locale($this->backend_locale);
						$category_info=$this->CategoryProduct->find('all',array('fields'=>'CategoryProduct.id,CategoryProductI18n.name','conditions'=>array('CategoryProduct.id'=>$activity_type_ids)));
						if(!empty($category_info)){
							foreach($category_info as $v){
								$activity_type_infos[$activity_type][$v['CategoryProduct']['id']]=$v['CategoryProductI18n']['name'];
							}
						}
				}
			}
			$this->set('activity_type_infos',$activity_type_infos);
		}
		$this->set('title_for_layout', $this->ld['activity'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
	}
	
	function view($id=0){
		if(!empty($id)){
			$this->operator_privilege('activity_edit');
		}else{
			$this->operator_privilege('activity_add');
		}
        	$this->operation_return_url(true);//设置操作返回页面地址
        	$this->menu_path = array('root' => '/cms/','sub' => '/activities/');
        	$this->navigations[] = array('name' => '在线学习','url' => '');
        	$this->navigations[] = array('name' => $this->ld['activity'],'url' => '/activities/');
        	
		if ($this->RequestHandler->isPost()) {
			if(!empty($this->data['Activity'])){
				$this->Activity->save($this->data['Activity']);
				$activity_id=$this->Activity->id;
				
				if(isset($this->data['ActivityPublisher'])&&!empty($this->data['ActivityPublisher'])){
					$this->data['ActivityPublisher']['activity_id']=$activity_id;
					$this->ActivityPublisher->save($this->data['ActivityPublisher']);
				}
			}
			$this->redirect('index');
		}
		$activity_info = $this->Activity->find('first', array('conditions' =>array('Activity.id'=>$id)));
		$this->set('activity_info',$activity_info);
		
		if(!empty($activity_info['Activity'])){
			$activity_publisher_detail=$this->ActivityPublisher->find('first',array('conditions'=>array('activity_id'=>$id)));
			$this->set('activity_publisher_detail',$activity_publisher_detail);
			
			$activity_type=$activity_info['Activity']['type'];
			$activity_type_id=$activity_info['Activity']['type_id'];
			switch($activity_type){
				case "A":
					$this->loadModel('Article');
					$this->Article->set_locale($this->backend_locale);
					$article_info=$this->Article->find('first',array('fields'=>'Article.id,ArticleI18n.title','conditions'=>array('Article.id'=>$activity_type_id)));
					$this->set('article_info',$article_info);
					break;
				case "P":
					$this->loadModel('Product');
					$this->Product->set_locale($this->backend_locale);
					$product_info=$this->Product->find('first',array('fields'=>'Product.id,ProductI18n.name','conditions'=>array('Product.id'=>$activity_type_id)));
					$this->set('product_info',$product_info);
					break;
				case "T":
					$this->loadModel('Topic');
					$this->Topic->set_locale($this->backend_locale);
					$topic_info=$this->Topic->find('first',array('fields'=>'Topic.id,TopicI18n.title','conditions'=>array('Topic.id'=>$activity_type_id)));
					$this->set('topic_info',$topic_info);
					break;
				case "AC":
					$this->loadModel('CategoryArticle');
					$this->CategoryArticle->set_locale($this->backend_locale);
					$category_info=$this->CategoryArticle->find('first',array('fields'=>'CategoryArticle.id,CategoryArticleI18n.name','conditions'=>array('CategoryArticle.id'=>$activity_type_id)));
					$this->set('category_info',$category_info);
					break;
				case "PC":
					$this->loadModel('CategoryProduct');
					$this->CategoryProduct->set_locale($this->backend_locale);
					$category_info=$this->CategoryProduct->find('first',array('fields'=>'CategoryProduct.id,CategoryProductI18n.name','conditions'=>array('CategoryProduct.id'=>$activity_type_id)));
					$this->set('category_info',$category_info);
					break;
			}
		}
		if(!empty($activity_info['Activity'])){
			$this->navigations[] = array('name' => $this->ld['edit'],'url' => '');
			$this->set('title_for_layout', $this->ld['edit'].' - '.$this->ld['activity'].' - '.$this->configs['shop_name']);
		}else{
			$this->navigations[] = array('name' => $this->ld['add'],'url' => '');
			$this->set('title_for_layout', $this->ld['add'].' - '.$this->ld['activity'].' - '.$this->configs['shop_name']);
		}
	}
	
	function ajax_activity_config($activity_id=0){
		$this->operator_privilege('activity_view');
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		
		$activity_config_list=$this->ActivityConfig->find('all',array('conditions'=>array('ActivityConfig.activity_id'=>$activity_id,'ActivityConfig.status'=>'1'),'order'=>'ActivityConfig.orderby,ActivityConfig.id'));
		$this->set('activity_config_list',$activity_config_list);
	}
	
	function ajax_activity_config_detail($activity_config_id=0){
		$this->operator_privilege('activity_view');
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		
		if ($this->RequestHandler->isPost()) {
			$result=array();
			$result['code']='0';
			$result['message']=$this->ld['unknown_error'];
			if(isset($this->data['ActivityConfig'])&&!empty($this->data['ActivityConfig'])){
				$this->ActivityConfig->save($this->data['ActivityConfig']);
				$result['code']='1';
				$result['message']=$this->ld['j_feedback_success'];
			}
			die(json_encode($result));
		}
		
		$conditions=array();
		$conditions['ActivityConfig.id']=$activity_config_id;
		if(isset($_REQUEST['activity_id'])&&intval($_REQUEST['activity_id'])>0){
			$conditions['ActivityConfig.activity_id']=$_REQUEST['activity_id'];
		}
		$activity_config_detail=$this->ActivityConfig->find('first',array('conditions'=>$conditions));
		$this->set('activity_config_detail',$activity_config_detail);
	}
	
	function ajax_activity_config_remove(){
		$this->operator_privilege('activity_view');
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result=array();
		$result['code']='0';
		$result['message']=$this->ld['unknown_error'];
		if(isset($_POST['activity_config_id'])&&intval($_POST['activity_config_id'])>0){
			$this->ActivityConfig->deleteAll(array('ActivityConfig.id'=>$_POST['activity_config_id']));
			$this->ActivityUserConfig->deleteAll(array('ActivityUserConfig.activity_config_id'=>$_POST['activity_config_id']));
			$result['code']='1';
			$result['message']=$this->ld['deleted_success'];
		}
		die(json_encode($result));
	}
	
	function remove($id=0){
		Configure::write('debug', 0);
		$this->layout = 'ajax';
		if (!$this->operator_privilege('activity_remove', false)) {
			die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
		}
		$result['flag'] = 2;
		$result['message'] = $this->ld['delete_article_failure'];
		$activity_info = $this->Activity->findById($id);
		if(!empty($activity_info)){
			$this->ActivityConfig->deleteAll(array('ActivityConfig.activity_id'=>$id));
			$this->ActivityUserConfig->deleteAll(array('ActivityUserConfig.activity_id'=>$id));
			$this->ActivityUser->deleteAll(array('ActivityUser.activity_id'=>$id));
			$this->Activity->deleteAll(array('Activity.id'=>$id));
			//操作员日志
			if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
				$this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete_article_failure'].':id '.$id.' '.$activity_info['Activity']['name'], $this->admin['id']);
			}
			$result['flag'] = 1;
			$result['message'] = $this->ld['delete_article_success'];
		}
		die(json_encode($result));
	}
	
	function toggle_on_status(){
		Configure::write('debug', 0);
		$this->layout = 'ajax';
		if (!$this->operator_privilege('activity_edit', false)) {
			die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
		}
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
		$val = isset($_REQUEST['val'])?$_REQUEST['val']:0;
		$result = array();
		if (!empty($id) && $this->Activity->save(array('id' => $id, 'status' => $val))) {
			$result['flag'] = 1;
			$result['content'] = stripslashes($val);
		}
		die(json_encode($result));
	}
	
	function batch_operate(){
		Configure::write('debug', 0);
		$this->layout = 'ajax';
		
		$act_type=isset($_REQUEST['act_type'])?trim($_REQUEST['act_type']):'';
		$checkbox=isset($_REQUEST['checkbox'])?$_REQUEST['checkbox']:'0';
		$modified_status=isset($_REQUEST['modified_status'])?trim($_REQUEST['modified_status']):'';
		
		if($act_type=='delete'){
			$this->operator_privilege('activity_remove');
			$this->Activity->deleteAll(array('Activity.id'=>$checkbox));
		}else if($act_type=='modified_status'&&$modified_status!=''){
			$this->operator_privilege('activity_edit');
			$this->Activity->updateAll(array('Activity.status'=>"'{$modified_status}'"),array('Activity.id'=>$checkbox));
		}
		$this->redirect('index');
	}
	
	function activity_user($page=1){
		$this->operator_privilege('activity_view');
        	$this->operation_return_url(true);//设置操作返回页面地址
        	$this->menu_path = array('root' => '/cms/','sub' => '/activities/');
        	$this->navigations[] = array('name' => '在线学习','url' => '');
        	$this->navigations[] = array('name' => $this->ld['activity'],'url' => '/activities/');
        	$this->navigations[] = array('name' => '活动用户','url' => '/activities/activity_user');
        	
		$this->loadModel('User');
		
        	$condition=array();
        	$condition['Activity.status']='1';
        	$condition['ActivityUser.user_id >']='0';
        	if (isset($this->params['url']['activity_keyword']) && $this->params['url']['activity_keyword'] != '') {
        		$condition['or']['Activity.name like']="%".trim($this->params['url']['activity_keyword'])."%";
        		$this->set('activity_keyword',$this->params['url']['activity_keyword']);
        	}
        	if (isset($this->params['url']['user_keyword']) && $this->params['url']['user_keyword'] != '') {
        		$condition['or']['ActivityUser.name like']="%".trim($this->params['url']['activity_keyword'])."%";
        		$condition['or']['ActivityUser.mobile like']="%".trim($this->params['url']['activity_keyword'])."%";
        		$this->set('activity_keyword',$this->params['url']['activity_keyword']);
        	}
        	if (isset($this->params['url']['activity_start_date']) && $this->params['url']['activity_start_date'] != '') {
        		$condition['ActivityUser.modified >=']=date('Y-m-d 00:00:00',strtotime($this->params['url']['activity_start_date']));
        		$this->set('activity_start_date',$this->params['url']['activity_start_date']);
        	}
        	if (isset($this->params['url']['activity_end_date']) && $this->params['url']['activity_end_date'] != '') {
        		$condition['ActivityUser.modified <=']=date('Y-m-d 23:59:59',strtotime($this->params['url']['activity_end_date']));
        		$this->set('activity_end_date',$this->params['url']['activity_end_date']);
        	}
        	if (isset($this->params['url']['page']) && intval($this->params['url']['page'])>0)$page=intval($this->params['url']['page']);
        	$total = $this->ActivityUser->find('count', array('conditions' => $condition));
        	$this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        	$this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
		$rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'activities','action' => 'activity_user','page' => $page,'limit' => $rownum);
		$options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'ActivityUser');
		$this->Pagination->init($condition, $parameters, $options);
		
		$activity_user_list = $this->ActivityUser->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'ActivityUser.modified desc'));
		$this->set('activity_user_list',$activity_user_list);
		
		$this->set('title_for_layout', '活动用户 - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
	}
	
	function activity_user_detail($activity_user_id=0){
		$this->operator_privilege('activity_view');
        	$this->operation_return_url(true);//设置操作返回页面地址
        	$this->menu_path = array('root' => '/cms/','sub' => '/activities/');
        	$this->navigations[] = array('name' => '在线学习','url' => '');
        	$this->navigations[] = array('name' => $this->ld['activity'],'url' => '/activities/');
        	$this->navigations[] = array('name' => '活动用户','url' => '/activities/activity_user');
        	$this->navigations[] = array('name' => '活动用户详情','url' =>'');
		$this->set('title_for_layout', '活动用户详情 - '.$this->configs['shop_name']);
        	
        	$activity_user_detail = $this->ActivityUser->find('first', array('conditions' => array('ActivityUser.id'=>$activity_user_id,'ActivityUser.status'=>'1','Activity.status'=>'1')));
        	if(empty($activity_user_detail))$this->redirect('/activities/activity_user');
        	$this->set('activity_user_detail',$activity_user_detail);
        	
        	$activity_config_list=$this->ActivityConfig->find('all',array('conditions'=>array('ActivityConfig.activity_id'=>$activity_user_detail['Activity']['id'],'ActivityConfig.status'=>'1'),'order'=>'orderby,id'));
        	$this->set('activity_config_list',$activity_config_list);
        	
        	$user_activity_config_list=$this->ActivityUserConfig->find('list',array('fields'=>'ActivityUserConfig.activity_config_id,ActivityUserConfig.config_value','conditions'=>array('ActivityUserConfig.activity_id'=>$activity_user_detail['Activity']['id'],'ActivityUserConfig.activity_user_id'=>$activity_user_detail['ActivityUser']['user_id'])));
        	$this->set('user_activity_config_list',$user_activity_config_list);
	}
	
	function ajax_activity_tag($activity_id=0){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		
		
		
		$activity_tags=$this->ActivityTag->find('all',array('conditions'=>array('activity_id'=>$activity_id),'order'=>'ActivityTag.modified'));
		$this->set('activity_tags',$activity_tags);
	}
}