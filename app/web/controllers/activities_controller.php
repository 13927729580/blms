<?php
class ActivitiesController extends  AppController{
	public $name="Activities";
	public $uses = array('Config','Activity','Organization','ActivityUser','ActivityConfig','ActivityUserConfig','ActivityTag','ActivityPublisher','Course','Evaluation','OrganizationMember','OrganizationUser','OrganizationUserTag','OrganizationDepartment','OrganizationManager');
	public $helpers = array('Html','Pagination');
	public $components = array('Pagination','RequestHandler','Cookie','Notify');
	
	function index($page=1,$limit=20){
		$this->redirect('activity_centers');
		$this->ur_heres[] = array('name' => $this->ld['activity'], 'url' => '');
		$_GET=$this->clean_xss($_GET);
		$page=isset($_GET['page'])?intval($_GET['page']):$page;
		$limit=isset($_GET['limit'])?intval($_GET['limit']):$limit;
		if($page>1){
			$this->pageTitle = $this->ld['activity'].' - '.sprintf($this->ld['page'], $page).' - '.$this->configs['shop_title'];//
		}else{
			$this->pageTitle = $this->ld['activity'].' - '.$this->configs['shop_title'];
		}
		$condition = array();
		$condition['Activity.status']='1';
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'activities','action' => 'index','page' => $page,'limit' => $limit);
		//分页参数
		$options = array('page' => $page,'show' => $limit,'modelClass' => 'Activity');
		$this->Pagination->init($condition, $parameters, $options); // Added
		$activity_list = $this->Activity->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $limit, 'order' => 'Activity.start_date desc'));
		$this->set('activity_list',$activity_list);
		if(!empty($activity_list)){
			$activity_type_list=array();
			$activity_type_infos=array();
			$activity_type_code_infos=array();
			foreach($activity_list  as $v){
				$activity_type_list[$v['Activity']['type']][]=$v['Activity']['type_id'];
			}
			foreach($activity_type_list as $activity_type=>$activity_type_ids){
				if($activity_type=='A'){
						$this->loadModel('Article');
						$article_info=$this->Article->find('all',array('fields'=>'Article.id,ArticleI18n.title','conditions'=>array('Article.id'=>$activity_type_ids)));
						if(!empty($article_info)){
							foreach($article_info as $v){
								$activity_type_infos[$activity_type][$v['Article']['id']]=$v['ArticleI18n']['title'];
							}
						}
				}else if($activity_type=="P"){
						$this->loadModel('Product');
						$product_info=$this->Product->find('all',array('fields'=>'Product.id,Product.code,ProductI18n.name','conditions'=>array('Product.id'=>$activity_type_ids)));
						if(!empty($product_info)){
							foreach($product_info as $v){
								$activity_type_infos[$activity_type][$v['Product']['id']]=$v['ProductI18n']['name'];
								$activity_type_code_infos[$activity_type][$v['Product']['id']]=$v['Product']['code'];
							}
						}
				}else if($activity_type=="T"){
						$this->loadModel('Topic');
						$topic_info=$this->Topic->find('all',array('fields'=>'Topic.id,TopicI18n.title','conditions'=>array('Topic.id'=>$activity_type_ids)));
						if(!empty($topic_info)){
							foreach($topic_info as $v){
								$activity_type_infos[$activity_type][$v['Topic']['id']]=$v['TopicI18n']['title'];
							}
						}
				}else if($activity_type=="AC"){
						$this->loadModel('CategoryArticle');
						$category_info=$this->CategoryArticle->find('all',array('fields'=>'CategoryArticle.id,CategoryArticle.code,CategoryArticleI18n.name','conditions'=>array('CategoryArticle.id'=>$activity_type_ids)));
						if(!empty($category_info)){
							foreach($category_info as $v){
								$activity_type_infos[$activity_type][$v['CategoryArticle']['id']]=$v['CategoryArticleI18n']['name'];
								$activity_type_code_infos[$activity_type][$v['CategoryArticle']['id']]=$v['CategoryArticle']['code'];
							}
						}
				}else if($activity_type=="PC"){
						$this->loadModel('CategoryProduct');
						$category_info=$this->CategoryProduct->find('all',array('fields'=>'CategoryProduct.id,CategoryProduct.code,CategoryProductI18n.name','conditions'=>array('CategoryProduct.id'=>$activity_type_ids)));
						if(!empty($category_info)){
							foreach($category_info as $v){
								$activity_type_infos[$activity_type][$v['CategoryProduct']['id']]=$v['CategoryProductI18n']['name'];
								$activity_type_code_infos[$activity_type][$v['CategoryProduct']['id']]=$v['CategoryProduct']['code'];
							}
						}
				}
			}
			$this->set('activity_type_infos',$activity_type_infos);
			$this->set('activity_type_code_infos',$activity_type_code_infos);
		}
	}
	
    	function activity_comment($page=1){
    		Configure::write('debug', 1);
        	$this->layout = 'ajax';
        	
        	$this->loadModel('Comment');
        	if(isset($_REQUEST['page'])&&intval($_REQUEST['page'])>0)$page=intval($_REQUEST['page']);
        	$limit = 10;
        	$conditions = array();
        	$conditions['Comment.parent_id'] = 0;
        	$conditions['Comment.type'] = 'AT';
        	if(isset($_REQUEST['activity_id'])&&intval($_REQUEST['activity_id'])>0){
        		$conditions['Comment.type_id'] = $_REQUEST['activity_id'];
        		$activity_info=$this->Activity->find('first',array('conditions'=>array('status'=>'1','id'=>$_REQUEST['activity_id'])));
        		if(!empty($activity_info)){
        			$this->set('activity_id',$activity_info['Activity']['id']);
        		}
        	}else{
        		$conditions['Comment.type_id'] = 0;
        	}
        	$conditions['Comment.status'] = 1;
        	$joins=array(
                    array(
				'table' => 'svoms_users',
				'alias' => 'User',
				'type' => 'inner',
				'conditions' => array('Comment.user_id = User.id')
                         )
            	);
        	$total = $this->Comment->find('count', array('conditions' => $conditions,'joins'=>$joins));
		//get参数
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'activities','action' => 'activity_comment/','page' => $page,'limit' => $limit);
		//分页参数
		$options = array('page' => $page,'show' => $limit,'modelClass' => 'Comment','total' => $total);
		$this->Pagination->init($conditions, $parameters, $options); // Added
		$comment_infos=$this->Comment->find('all', array('conditions' => $conditions, 'limit' => $limit, 'page' => $page,'joins'=>$joins, 'order' => 'Comment.created desc','fields'=>'Comment.*,User.id,User.name,User.first_name,User.last_name,User.email,User.img01'));
		$this->set('comment_infos',$comment_infos);
		if(isset($_SESSION['User'])){
			$conditions['Comment.user_id']=$_SESSION['User']['User']['id'];
			$comment_count=$this->Comment->find('count', array('conditions' => $conditions));
			$this->set('user_comment_count',$comment_count);
			
			if(isset($activity_info)&&!empty($activity_info)){
				$activity_end=date('Y-m-d 23:59:59',strtotime($activity_info['Activity']['end_date']));
				if(time()>strtotime($activity_end)){
					$activity_user=$this->ActivityUser->find('first',array('conditions'=>array('Activity.status'=>'1','ActivityUser.status'=>'1','payment_status'=>'1','user_id'=>$_SESSION['User']['User']['id'],'activity_id'=>$activity_info['Activity']['id'])));
					if(!empty($activity_user)){
						$this->set('activity_user',$activity_user);
					}
				}
			}
		}
    }

    function activity_user($organization_id=0,$page=1,$limit=10){
		$this->checkSessionUser();
		$user_id = $_SESSION['User']['User']['id'];
		$org_info = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organization_id)));
		if(empty($org_info))$this->redirect('/organizations/index');
		$organization_actions=$this->Organization->manager_operator($organization_id,$user_id);
		$this->set('organization_actions',$organization_actions);
		$this->pageTitle = '我的客户 - '.$this->configs['shop_title'];
		$activity_cond=array('publisher_type'=>'O','status'=>'1','publisher'=>$organization_id);
		if(!in_array('customer',$organization_actions)){
			$activity_cond['created_user']=$user_id;
		}
		$activity_list=$this->Activity->find('list',array('fields'=>'id','conditions'=>$activity_cond));
		$activity_user_conditions=array();
		$search_content = '';
		if(isset($_GET['search_content'])&&$_GET['search_content']!=''){
			$tag_conditions = array();
			$tag_conditions['OrganizationUserTag.organization_id'] = $organization_id;
			$tag_conditions['OrganizationUserTag.tag_name like'] = '%'.$_GET['search_content'].'%';
			$tag_users = $this->OrganizationUserTag->find('list',array('fields'=>'user_id','conditions'=>$tag_conditions));
			if(is_array($tag_users)&&sizeof($tag_users)>0){
				$activity_user_conditions['or']['ActivityUser.user_id']=$tag_users;
			}
			$search_content = $_GET['search_content'];
		}
		$this->set('search_content',$search_content);
		if(!empty($activity_list)){
			$activity_user_conditions['ActivityUser.activity_id']=$activity_list;
		}else{
			$activity_user_conditions['ActivityUser.activity_id']=0;
		}
		if(isset($_GET['search_content'])&&$_GET['search_content']!=''){
			$activity_user_conditions['or']['User.name like'] = '%'.$_GET['search_content'].'%';
			$activity_user_conditions['or']['User.mobile like'] = '%'.$_GET['search_content'].'%';
		}
		$joins=array(
                    array(
				'table' => 'svoms_users',
				'alias' => 'User',
				'type' => 'inner',
				'conditions' => array('ActivityUser.user_id = User.id')
                         )
            	);
		$fields=array('DISTINCT ActivityUser.user_id','ActivityUser.*','User.id','User.name','User.first_name','User.img01','User.email','User.mobile');
		//分页start
		$parameters=array();
		$parameters['get'] = array();
		if(isset($_REQUEST['page'])&&intval($_REQUEST['page'])>0)$page=intval($_REQUEST['page']);
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'activities', 'action' => 'activity_user/'.$organization_id, 'page' => $page, 'limit' => $limit);
		//分页参数
		$activity_user_total=$this->ActivityUser->find('count',array('joins'=>$joins,'conditions'=>$activity_user_conditions));
		$options = array('page' => $page, 'show' => $limit,'modelClass' => 'ActivityUser','total'=>$activity_user_total);
		$this->Pagination->init($activity_user_conditions, $parameters, $options); // Added
		//分页end   
		$activity_user_list=$this->ActivityUser->find('all',array('fields'=>$fields,'joins'=>$joins,'conditions'=>$activity_user_conditions,'order'=>'ActivityUser.created desc'));
		
	    	$this->set('orga_id',$org_info['Organization']['id']);
	    	$this->set('organizations_id',$org_info['Organization']['id']);
	    	$this->set('organization_info',$org_info);
	    	// start
	        $org_id = $org_info['Organization']['id'];
	        //pr($org_id);
	        $organization_info = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$org_id)));
	        $manager_ids[]=$organization_info['Organization']['manage_user'];
	        $org_ma = $this->OrganizationManager->find('all',array('conditions'=>array('OrganizationManager.organization_id'=>$org_id,'OrganizationManager.manager_type'=>0)));
	        //pr($org_ma);
	        $cond = array();
	        if(isset($org_ma)&&count($org_ma)>0){
	            foreach ($org_ma as $k => $v) {
	                $cond['OrganizationMember.id'][]=$v['OrganizationManager']['organization_member_id'];
	            }
	        }
	        if(!empty($cond)){
	        	$org_ma = $this->OrganizationMember->find('all',array('conditions'=>$cond));
	        }
	        if(isset($org_ma)&&count($org_ma)>0){
	        	foreach ($org_ma as $k => $v) {
	            	$manager_ids[]= $v['OrganizationMember']['user_id'];
	        	}
	        }
	        $this->ur_heres[] = array('name' => $org_info['Organization']['name'], 'url' => '/organizations/view/'.$organization_id);
			$this->ur_heres[] = array('name' => '我的客户', 'url' => '');
	        //pr($manager_ids);
	        $this->set('org_manager',$manager_ids);
	       
	        $manage = $this->OrganizationManager->find('all',array('conditions'=>array('OrganizationManager.organization_id'=>$org_id)));
	        $conn = array();
	        if(isset($manage)&&count($manage)>0){
	            foreach ($manage as $k => $v) {
	                $conn['OrganizationMember.id'][]=$v['OrganizationManager']['organization_member_id'];
	            }
	        }
	        if(!empty($conn)){
	            $manages = $this->OrganizationMember->find('all',array('conditions'=>$conn));
	        }
	        $manage_ids = array();
	        if(isset($manages)&&count($manages)>0){
	            foreach ($manages as $k => $v) {
	                $manage_ids[]=$v['OrganizationMember']['user_id'];
	                $ma_check[$v['OrganizationMember']['id']] = $v['OrganizationMember']['user_id'];
	            }
	        }
	        $manage_ids[]=$org_info['Organization']['manage_user'];
	        $this->set('manager_ids',$manage_ids);
	        // end
		
		$activity_ids=array();
		$activity_user_ids=array();
		if(isset($activity_user_list)&&count($activity_user_list)>0){
			foreach ($activity_user_list as $k => $v) {
				$activity_user_ids[]=$v['User']['id'];
				$activity_ids[]=$v['ActivityUser']['activity_id'];
			}
		}
		$activity_info = array();
		if(!empty($activity_ids)){
			$activity_info = $this->Activity->find('all',array('conditions'=>array('id'=>$activity_ids,'status'=>'1')));
		}
		$activity_tag_info =array();
		if(!empty($activity_user_ids)){
			$activity_tag_info = $this->OrganizationUserTag->find('all',array('conditions'=>array('organization_id'=>$organization_id,'user_id'=>$activity_user_ids)));
		}
		if(is_array($activity_info)&&count($activity_info)>0){
			foreach ($activity_info as $k => $v) {
				$act_check[$v['Activity']['id']] = $v;
			}
		}
		//pr($activity_tag_info);
		$tag_check = '';
		if(is_array($activity_tag_info)&&count($activity_tag_info)>0){
			foreach ($activity_tag_info as $k => $v) {
				$tag_check[$v['OrganizationUserTag']['user_id']][]=$v['OrganizationUserTag']['tag_name'];
			}
		}
		if(is_array($tag_check)&&count($tag_check)>0){
			foreach ($tag_check as $k => $v) {
				if(is_array($v)){
					$tag_check[$k] = implode(',',$v);
				}else{
					$tag_check[$k] = $v;
				}
			}
		}
		//pr($tag_check);
		//pr($activity_user_list);exit();
		if(isset($activity_user_list)&&count($activity_user_list)>0){
			foreach ($activity_user_list as $k => $v) {
				if(isset($act_check[$v['ActivityUser']['activity_id']])){
					$activity_user_list[$k]['ActivityUser']['activity_name'] = $act_check[$v['ActivityUser']['activity_id']]['Activity']['name'];
					if(isset($tag_check[$v['User']['id']])){
						$activity_user_list[$k]['User']['tag'] = $tag_check[$v['User']['id']];
					}
				}
			}
			foreach ($activity_user_list as $k => $v) {
				if(isset($v['User']['tag'])&&is_array($v['User']['tag'])){
					$activity_user_list[$k]['User']['tag'] = implode(',',$v['User']['tag']);
				}
			}
		}
		$this->set('activity_user_list',$activity_user_list);
	}
	
	function activity_user_detail($activity_user_id=0){
		$this->checkSessionUser();
		$user_id = $_SESSION['User']['User']['id'];
		$activity_user_detail=$this->ActivityUser->find('first',array('conditions'=>array('ActivityUser.id'=>$activity_user_id)));
		if(!empty($activity_user_detail)){
			$this->set('activity_user_detail',$activity_user_detail);
			
			$activity_id=$activity_user_detail['ActivityUser']['activity_id'];
			$user_id=$activity_user_detail['ActivityUser']['user_id'];
			$activity_organization=array();
			$activity_detail=$this->Activity->find('first',array('conditions'=>array('id'=>$activity_id,'status'=>'1','publisher_type'=>'O')));
			if(!empty($activity_detail)){
				$activity_organization=$this->Organization->find('first',array('conditions'=>array('id'=>$activity_detail['Activity']['publisher'])));
			}
			if(!empty($activity_organization)){
				$organization_id=$activity_organization['Organization']['id'];
				$this->set('organization_info',$activity_organization);
				
				if(empty($activity_organization))$this->redirect('/organizations/index');
				$organization_actions=$this->Organization->manager_operator($organization_id,$user_id);
				$this->set('organization_actions',$organization_actions);
				if(!in_array('customer',$organization_actions))$this->redirect('/organizations/view/'.$organization_id);
				
				$this->ur_heres[] = array('name' => $activity_organization['Organization']['name'], 'url' => '/organizations/view/'.$activity_organization['Organization']['id']);
				$this->ur_heres[] = array('name' => '我的客户', 'url' => '/activities/activity_user/'.$activity_organization['Organization']['id']);
				$this->pageTitle = '我的客户 - '.$this->configs['shop_title'];
				if(isset($activity_user_detail['ActivityUser']['name'])&&trim($activity_user_detail['ActivityUser']['name'])!=''){
					$this->ur_heres[] = array('name' => $activity_user_detail['ActivityUser']['name'], 'url' => '');
					$this->pageTitle = $activity_user_detail['ActivityUser']['name'].' - 我的客户 - '.$this->configs['shop_title'];
				}
				$organization_user_detail=$this->OrganizationUser->find('first',array('conditions'=>array('organization_id'=>$organization_id,'user_id'=>$user_id)));
				$this->set('organization_user_detail',$organization_user_detail);
				
				$organization_user_tags=$this->OrganizationUserTag->find('all',array('conditions'=>array('organization_id'=>$organization_id,'user_id'=>$user_id),'orderby'=>'tag_name'));
				$this->set('organization_user_tags',$organization_user_tags);
				
	        		$organization_member_infos = $this->OrganizationManager->find('list',array('fields'=>'id,organization_member_id,manager_type','conditions'=>array('OrganizationManager.organization_id'=>$organization_id,'OrganizationManager.manager_type'=>0)));
				if(!empty($organization_member_infos)){
					$organization_member_ids=array();
					foreach($organization_member_infos as $v)$organization_member_ids=array_merge($organization_member_ids,$v);
					$organization_manager_members = $this->OrganizationMember->find('list',array('fields'=>'id,user_id','conditions'=>array('organization_id'=>$organization_id,'id'=>$organization_member_ids)));
					$manager_members=array();
					$manager_members[]=$activity_organization['Organization']['manage_user'];
					$organization_manager_members[]=$activity_organization['Organization']['manage_user'];
					if(isset($organization_member_infos[0])&&!empty($organization_member_infos[0])){
						foreach($organization_member_infos[0] as $v){
							if(!isset($organization_manager_members[$v])||$organization_manager_members[$v]<=0)continue;
							$manager_members[]=$organization_manager_members[$v];
						}
					}
					$this->set('manager_ids',$organization_manager_members);
					$this->set('org_manager',$organization_manager_members);
				}
			}
		}
		if(!isset($organization_user_detail)||empty($organization_user_detail))$this->redirect('/users/index');
	}
	
	function ajax_activity_user_modify(){
		Configure::write('debug', 1);
        	$this->layout = 'ajax';
        	
        	$result=array();
        	$result['code']='0';
        	$result['message']=$this->ld['invalid_operation'];
        	if($this->RequestHandler->isPost()){
        		if(isset($this->data['OrganizationUser']['remark'])){
        			$this->OrganizationUser->save($this->data['OrganizationUser']);
        		}
        		$organization_user_tag_ids=array();
        		if(isset($this->data['OrganizationUserTag']['tag_name'])&&is_array($this->data['OrganizationUserTag']['tag_name'])&&sizeof($this->data['OrganizationUserTag']['tag_name'])>0){
        			foreach($this->data['OrganizationUserTag']['tag_name'] as $k=>$v){
        				if(trim($v)=='')continue;
        				$organization_user_tag=array();
        				$organization_user_tag['id']=isset($this->data['OrganizationUserTag']['id'][$k])?$this->data['OrganizationUserTag']['id'][$k]:0;
        				$organization_user_tag['user_id']=$this->data['OrganizationUserTag']['user_id'];
        				$organization_user_tag['organization_id']=$this->data['OrganizationUserTag']['organization_id'];
        				$organization_user_tag['tag_name']=trim($v);
        				$this->OrganizationUserTag->save($organization_user_tag);
        				$organization_user_tag_ids[]=$this->OrganizationUserTag->id;
        				$result['organization_id']=$this->data['OrganizationUserTag']['organization_id'];
        			}
        		}
        		if(!empty($organization_user_tag_ids)){
        			$this->OrganizationUserTag->deleteAll(array('organization_id'=>$this->data['OrganizationUserTag']['organization_id'],'user_id'=>$this->data['OrganizationUserTag']['user_id'],'not'=>array('id'=>$organization_user_tag_ids)));
        		}
        		$result['code']='1';
        		$result['message']=$this->ld['saved_successfully'];
        	}
        	die(json_encode($result));
	}
    
    function ajax_activity_comment_reply($comment_id=0){
    		Configure::write('debug', 1);
        	$this->layout = 'ajax';
        	
        	$this->loadModel('Comment');
        	
        	$conditions = array();
        	$conditions['Comment.parent_id'] = $comment_id;
        	$conditions['Comment.status'] = 1;
        	$joins=array(
                    array(
				'table' => 'svoms_users',
				'alias' => 'User',
				'type' => 'inner',
				'conditions' => array('Comment.user_id = User.id')
                         )
            	);
        	$comment_reply=$this->Comment->find('all', array('conditions' => $conditions,'joins'=>$joins, 'order' => 'Comment.created desc','fields'=>'Comment.*,User.id,User.name,User.first_name,User.last_name,User.email,User.img01'));
		$this->set('comment_reply',$comment_reply);
    }
    
    function ajax_add_activity_comment(){
    		Configure::write('debug', 1);
        	$this->layout = 'ajax';
    		
    		$this->loadModel('Comment');
    		
    		$result=array();
    		$result['code']='0';
    		$result['message']=$this->ld['send_failed'];
    		if(isset($_SESSION['User'])&&!empty($_SESSION['User'])){
			$status = 0;
			if (isset($this->configs['enable_user_comment_check']) && $this->configs['enable_user_comment_check'] == 0) {
				$status = 1;
			}
			$this->data['Comment']['type'] = 'AT';
			$this->data['Comment']['type_id'] = !empty($this->data['Comment']['type_id']) ? $this->data['Comment']['type_id'] : '0';
			$this->data['Comment']['parent_id'] =!empty($this->data['Comment']['parent_id']) ? $this->data['Comment']['parent_id'] : '0';
			$this->data['Comment']['user_id'] = $_SESSION['User']['User']['id'];//用户id
			$this->data['Comment']['content'] = !empty($this->data['Comment']['content']) ? $this->data['Comment']['content'] : '';//用户日志
			$this->data['Comment']['created'] = date('Y-m-d H:i:s');//用户创建时间
			$this->data['Comment']['modified'] = date('Y-m-d H:i:s');//用户修改时间
			$this->data['Comment']['status'] = $status;//评论审核默认状态（有效）
			$this->data['Comment']['rank'] = 5;
			$this->data['Comment']['is_public'] = !empty($this->data['Comment']['is_public']) ? $this->data['Comment']['is_public'] : '0';
			$this->data['Comment']['ipaddr'] = $this->RequestHandler->getClientIP();
			$oauth_content = $this->data['Comment']['content'];
            		$oauth_content = preg_replace("/<img.+?\/>/", '', $oauth_content);
            		$oauth_content = strlen($oauth_content) == 0 || $oauth_content == '' ?$this->server_host:$oauth_content;
			if (isset($_FILES['upfile']['tmp_name']) && !empty($_FILES['upfile']['tmp_name'])) {
				//图片上传处理
				$imgname_arr = explode('.', strtolower($_FILES['upfile']['name']));//获取文件名
				if ($imgname_arr[1] == 'jpg' || $imgname_arr[1] == 'gif' || $imgname_arr[1] == 'png' || $imgname_arr[1] == 'bmp' || $imgname_arr[1] == 'jpeg') {
					//判断文件格式（限制图片格式）
					$img_thumb_name = md5($imgname_arr[0].time());
					$image_name = $img_thumb_name.'.'.$imgname_arr[1];
					$imgaddr = WWW_ROOT.'img/comment/'.date('Ym').'/';
					$image_width = 180;
					$image_height = 180;
					$img_detail = str_replace($image_name, '', $imgaddr);
					$this->mkdirs($imgaddr);
					move_uploaded_file($_FILES['upfile']['tmp_name'], $imgaddr.$image_name);
					$this->data['Comment']['img'] = '/img/comment/'.date('Ym').'/'.$image_name;
				}
			} else {
				$this->data['Comment']['img'] = '';
			}
			$this->Comment->save($this->data['Comment']);
			$this->Comment->comment_point($this,$this->data['Comment']['type'],$this->data['Comment']['type_id']);
			$result['code']='1';
			$result['message']=$this->ld['send_success'];
    		}else{
    			$result['message']=$this->ld['time_out_relogin'];
    		}
    		die(json_encode($result));
    }
    
    //创建路径
    public function mkdirs($path, $mode = 0777)
    {
        $dirs = explode('/', $path);
        $pos = strrpos($path, '.');
        if ($pos === false) {
            $subamount = 0;
        } else {
            $subamount = 1;
        }
        for ($c = 0;$c < count($dirs) - $subamount; ++$c) {
            $thispath = '';
            for ($cc = 0; $cc <= $c; ++$cc) {
                $thispath .= $dirs[$cc].'/';
            }
            if (!file_exists($thispath)) {
                mkdir($thispath, $mode);
            }
        }
    }

    public function org_index($page=1,$limit=10){
		$this->checkSessionUser();
		$user_id = $_SESSION['User']['User']['id'];
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
		$this->set('user_list', $user_list);
		$organization_id=isset($_GET['organization_id'])?$_GET['organization_id']:0;
		$org_info = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organization_id)));
		if(empty($org_info))$this->redirect('/organizations/index');
		$organization_actions=$this->Organization->manager_operator($organization_id,$user_id);
		$this->set('organization_actions',$organization_actions);
		if(!in_array('activity',$organization_actions))$this->redirect('/organizations/view/'.$organization_id);
		
		$this->set('orga_id',$organization_id);
		$this->set('organization_info',$org_info);
		$manager_ids[]=$org_info['Organization']['manage_user'];
		$org_id = $_GET['organization_id'];
		$organization_info = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$org_id)));
		$manager_ids[]=$organization_info['Organization']['manage_user'];
		$org_ma = $this->OrganizationManager->find('all',array('conditions'=>array('OrganizationManager.organization_id'=>$org_id,'OrganizationManager.manager_type'=>0)));
		$cond = array();
		if(isset($org_ma)&&count($org_ma)>0){
			foreach ($org_ma as $k => $v) {
				$cond['OrganizationMember.id'][]=$v['OrganizationManager']['organization_member_id'];
			}
		}
		if(!empty($cond)){
			$org_ma = $this->OrganizationMember->find('all',array('conditions'=>$cond));
		}
		if(isset($org_ma)&&count($org_ma)>0){
			foreach ($org_ma as $k => $v) {
				$manager_ids[]= $v['OrganizationMember']['user_id'];
			}
		}

		//pr($manager_ids);
		$this->set('org_manager',$manager_ids);

		$manage = $this->OrganizationManager->find('all',array('conditions'=>array('OrganizationManager.organization_id'=>$org_id)));
		$conn = array();
		if(isset($manage)&&count($manage)>0){
			foreach ($manage as $k => $v) {
				$conn['OrganizationMember.id'][]=$v['OrganizationManager']['organization_member_id'];
			}
		}
		if(!empty($conn)){
			$manages = $this->OrganizationMember->find('all',array('conditions'=>$conn));
		}
		$manage_ids = array();
		if(isset($manages)&&count($manages)>0){
			foreach ($manages as $k => $v) {
				$manage_ids[]=$v['OrganizationMember']['user_id'];
				$ma_check[$v['OrganizationMember']['id']] = $v['OrganizationMember']['user_id'];
			}
		}
		$manage_ids[]=$org_info['Organization']['manage_user'];
		$this->set('manager_ids',$manage_ids);
		// end
		$this->ur_heres[] = array('name' => $org_info['Organization']['name'], 'url' => '/organizations/view/'.$organization_id);
		$this->ur_heres[] = array('name' => '活动列表', 'url' => '');
		//$this->layout = 'usercenter';//引入模版
		$_GET=$this->clean_xss($_GET);
		$page=isset($_GET['page'])?intval($_GET['page']):$page;
		$limit=isset($_GET['limit'])?intval($_GET['limit']):$limit;
		if($page>1){
			$this->pageTitle = $this->ld['activity'].' - '.sprintf($this->ld['page'], $page).' - '.$this->configs['shop_title'];//
		}else{
			$this->pageTitle = $this->ld['activity'].' - '.$this->configs['shop_title'];
		}
		$condition = array();
		if(isset($_GET['organization_id'])&&$_GET['organization_id']!=''){
			$this->set('organizations_id',$_GET['organization_id']);
			
			$condition['Activity.publisher_type']='O';
			$condition['Activity.publisher']=$_GET['organization_id'];
		}
		//$condition['Activity.status']='1';
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'activities','action' => 'index','page' => $page,'limit' => $limit);
		//分页参数
		$options = array('page' => $page,'show' => $limit,'modelClass' => 'Activity');
		$this->Pagination->init($condition, $parameters, $options); // Added
		$activity_list = $this->Activity->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $limit, 'order' => 'Activity.start_date desc'));
		//pr($activity_list);
		$con = array();
		foreach ($activity_list as $k => $v) {
			$con['ActivityUser.activity_id'][]=$v['Activity']['id'];
		}
		$act_user_info = $this->ActivityUser->find('all',array('fields'=>array('activity_id','count(*) as activity_count'),'conditions'=>$con,'group'=>'activity_id'));
		$act_user_check=array();
		foreach ($act_user_info as $k => $v) {
			$act_user_check[$v['ActivityUser']['activity_id']]= $v[0]['activity_count'];
		}
		$this->set('act_user_check',$act_user_check);
		$this->set('activity_list',$activity_list);
		
		if(isset($_GET['get_activity_count'])&&$_GET['get_activity_count']!=''){
			die(json_encode(count($activity_list)));
		}
    }

    public function org_activity_user($id=0,$page=1,$limit=20){
    	$this->pageTitle = '参与人员列表'.' - '.$this->configs['shop_title'];
    	$this->checkSessionUser();
    	$this->layout = 'default';//引入模版
    	$user_id = $_SESSION['User']['User']['id'];
    	if(isset($_GET['organization_id'])&&$_GET['organization_id']!=''){
    		$org_info = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$_GET['organization_id'])));
    		$this->set('org_info',$org_info);
    	}
    	$user_info = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
    	if($id!=''){
    		$activity_info = $this->ActivityUser->find('all',array('conditions'=>array('ActivityUser.activity_id'=>$id)));
    		$this->set('activity_id',$id);
    		$act_info = $this->Activity->find('first',array('conditions'=>array('Activity.id'=>$id)));
    		$this->set('act_info',$act_info);
    		//pr($act_info);
    		if($act_info['Activity']['publisher_type'] == 'O'){
    			if(isset($org_info)&&!empty($org_info)){
    				$this->ur_heres[] = array('name' => $org_info['Organization']['name'] , 'url' => '/activities/org_index/?organization_id='.$act_info['Activity']['publisher']);
    			}
    				
    			$this->ur_heres[] = array('name' => $act_info['Activity']['name'] , 'url' => '/activities/org_view/'.$act_info['Activity']['id'].'?organization_id='.$act_info['Activity']['publisher']);
    		}else{
    			$this->ur_heres[] = array('name' => $user_info['User']['name'] , 'url' => '/activities/user_index/');
    			$this->ur_heres[] = array('name' => $act_info['Activity']['name'] , 'url' => '/activities/'.$act_info['Activity']['id']);
    		}
    		$this->ur_heres[] = array('name' => '参与人员列表', 'url' => '');
    		//pr($activity_info);
    		$condition = '';
    		if(!empty($activity_info)){
    			foreach ($activity_info as $k => $v) {
    				$activity_check[$v['ActivityUser']['user_id']] = $v;
	    		}
	    		$this->set('activity_check',$activity_check);
	    		
	    		foreach ($activity_info as $k => $v) {
	    			$condition['User.id'][]=$v['ActivityUser']['user_id'];
	    		}
	    		$parameters['get'] = array();
			//地址路由参数（和control,action的参数对应）
			$parameters['route'] = array('controller' => 'users','action' => 'org_activity_user','page' => $page,'limit' => $limit);
			//分页参数
			$options = array('page' => $page,'show' => $limit,'modelClass' => 'User');
			$this->Pagination->init($condition, $parameters, $options); // Added
			$user_info = $this->User->find('all',array('conditions'=>$condition));
    			$this->set('user_info',$user_info);
    		}
    	}
    }

    public function org_activity_user_view($id){
    	$this->layout = 'default';//引入模版
    	$user_id = $_SESSION['User']['User']['id'];
    	$result = array();
    	$result['code'] = 0;
    	if(isset($_GET['activity_id'])&&$_GET['activity_id']!=''){
    		$activity_info = $this->ActivityUser->find('all',array('conditions'=>array('ActivityUser.activity_id'=>$_GET['activity_id'])));
    		//pr($activity_info);
    		$con = '';
    		$activity_config_info = $this->ActivityConfig->find('all',array('conditions'=>array('ActivityConfig.activity_id'=>$_GET['activity_id'])));
    		$this->set('activity_config_info',$activity_config_info);
    		$activity_user_config_info = $this->ActivityUserConfig->find('all',array('conditions'=>array('ActivityUserConfig.activity_id'=>$_GET['activity_id'],'ActivityUserConfig.activity_user_id'=>$id)));
    		//pr($activity_user_config_info);
    		foreach ($activity_user_config_info as $k => $v) {
    			$config_value_info[$v['ActivityUserConfig']['activity_config_id']] = $v;
    		}
    		//pr($config_value_info);
    		if(isset($config_value_info)){
    			$this->set('config_value_info',$config_value_info);
    		}
    		
    	}
    }

    public function config_sub($id){
    	$this->checkSessionUser();
    	Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result = array();
        $result['code'] = 0;
        if(!empty($_POST)){
        	//pr($_POST);
        	$config_info = $this->ActivityConfig->find('first',array('conditions'=>array('ActivityConfig.id'=>$id)));
        	$config_info['ActivityConfig']['id'] = $id;
        	$config_info['ActivityConfig']['activity_id'] = $_POST['activity_id'];
        	if(isset($_POST['config_name'])&&$_POST['config_name']!=''){
        		$config_info['ActivityConfig']['name'] = $_POST['config_name'];
        	}
        	if(isset($_POST['config_type'])&&$_POST['config_type']!=''){
        		$config_info['ActivityConfig']['type'] = $_POST['config_type'];
        	}
        	if(isset($_POST['config_option'])&&$_POST['config_option']!=''){
        		$config_info['ActivityConfig']['options'] = $_POST['config_option'];
        	}
        	if(isset($_POST['config_is_required'])&&$_POST['config_is_required']!=''){
        		$config_info['ActivityConfig']['is_required'] = $_POST['config_is_required'];
        	}
        	if(isset($_POST['config_status'])&&$_POST['config_status']!=''){
        		$config_info['ActivityConfig']['status'] = $_POST['config_status'];
        	}
        	if(isset($_POST['config_orderby'])&&$_POST['config_orderby']!=''){
        		$config_info['ActivityConfig']['orderby'] = $_POST['config_orderby'];
        	}
        	$this->ActivityConfig->save($config_info);
        	$result['code'] = 1;
        	die(json_encode($result));
        }
    }

    public function config_delete($id){
    	$this->checkSessionUser();
    	Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result = array();
        $result['code'] = 0;
        $this->ActivityConfig->deleteAll(array('ActivityConfig.id'=>$id));
        $this->ActivityUserConfig->deleteAll(array('ActivityUserConfig.activity_config_id'=>$id));
        $result['code'] = 1;
        die(json_encode($result));
    }

    public function org_view($id=0){
    	$this->checkSessionUser();
    	$this->layout = 'default';//引入模版
    	$user_id = $_SESSION['User']['User']['id'];
    	$result = array();
    	$result['code'] = 0;
    	$this->pageTitle = '编辑活动 - '.$this->configs['shop_title'];
    	$this->set('act_id',$id);
    	$organization_id=isset($_GET['organization_id'])?$_GET['organization_id']:0;
    	$org_info = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organization_id)));
	if(empty($org_info))$this->redirect('/organizations/index');
	$organization_actions=$this->Organization->manager_operator($organization_id,$user_id);
	$this->set('organization_actions',$organization_actions);
	if(!in_array('activity',$organization_actions))$this->redirect('/organizations/view/'.$organization_id);
	
    	$this->set('orga_id',$org_info['Organization']['id']);
    	$this->set('organization_info',$org_info);
    	// start
        $org_id = $org_info['Organization']['id'];
        //pr($org_id);
        $organization_info = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$org_id)));
        $manager_ids[]=$organization_info['Organization']['manage_user'];
        $org_ma = $this->OrganizationManager->find('all',array('conditions'=>array('OrganizationManager.organization_id'=>$org_id,'OrganizationManager.manager_type'=>0)));
        //pr($org_ma);
        $cond = array();
        if(isset($org_ma)&&count($org_ma)>0){
            foreach ($org_ma as $k => $v) {
                $cond['OrganizationMember.id'][]=$v['OrganizationManager']['organization_member_id'];
            }
        }
        if(!empty($cond)){
        	$org_ma = $this->OrganizationMember->find('all',array('conditions'=>$cond));
        }
        if(isset($org_ma)&&count($org_ma)>0){
        	foreach ($org_ma as $k => $v) {
            	$manager_ids[]= $v['OrganizationMember']['user_id'];
        	}
        }
        
        //pr($manager_ids);
        $this->set('org_manager',$manager_ids);
       
        $manage = $this->OrganizationManager->find('all',array('conditions'=>array('OrganizationManager.organization_id'=>$org_id)));
        $conn = array();
        if(isset($manage)&&count($manage)>0){
            foreach ($manage as $k => $v) {
                $conn['OrganizationMember.id'][]=$v['OrganizationManager']['organization_member_id'];
            }
        }
        if(!empty($conn)){
            $manages = $this->OrganizationMember->find('all',array('conditions'=>$conn));
        }
        $manage_ids = array();
        if(isset($manages)&&count($manages)>0){
            foreach ($manages as $k => $v) {
                $manage_ids[]=$v['OrganizationMember']['user_id'];
                $ma_check[$v['OrganizationMember']['id']] = $v['OrganizationMember']['user_id'];
            }
        }
        $manage_ids[]=$org_info['Organization']['manage_user'];
        $this->set('manager_ids',$manage_ids);
        // end
    	$manager_ids[]=$org_info['Organization']['manage_user'];
    	$this->ur_heres[] = array('name' => $org_info['Organization']['name'], 'url' => '/organizations/view/'.$_GET['organization_id']);
		$this->ur_heres[] = array('name' => '活动列表', 'url' => '/activities/org_index?organization_id='.$_GET['organization_id']);
		$this->ur_heres[] = array('name' => '编辑活动', 'url' => '');
    	if(isset($_GET['organization_id'])&&$_GET['organization_id']!=''){
    		$organization_id = $_GET['organization_id'];
    		$this->set('organizations_id',$organization_id);
    		$organization_info = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organization_id)));
    		$this->set('organization_info',$organization_info);
			$activity_info = $this->Activity->find('first',array('conditions'=>array('Activity.id'=>$id)));
			$activity_publisher_info = $this->ActivityPublisher->find('first',array('conditions'=>array('ActivityPublisher.activity_id'=>$id)));
			//pr($activity_publisher_info);
			$this->set('activity_publisher_info',$activity_publisher_info);
			//pr($activity_info);
			if($id!=0){
				$activity_config_info = $this->ActivityConfig->find('all',array('conditions'=>array('ActivityConfig.activity_id'=>$id)));
				$this->set('activity_config_info',$activity_config_info);
				$activity_tag_info = $this->ActivityTag->find('all',array('conditions'=>array('ActivityTag.activity_id'=>$id),'order'=>'ActivityTag.created'));
				$this->set('activity_tag_info',$activity_tag_info);
			}
			$this->set('activity_info',$activity_info);
			//pr($activity_config_info);
			if(!empty($_GET)){
				if(isset($_GET['activity_type'])&&$_GET['activity_type']!=''){
					$activity_info['Activity']['type'] = $_GET['activity_type'];
					if(isset($_GET['activity_type_id'])&&$_GET['activity_type_id']!=''){
					$activity_info['Activity']['type_id'] = $_GET['activity_type_id'];
					}
				}else if(isset($_GET['activity_type'])&&$_GET['activity_type'] == ''){
					$activity_info['Activity']['type'] = '';
					$activity_info['Activity']['type_id'] = '0';
				}
				if(isset($_GET['channel'])&&$_GET['channel']!=''){
					$activity_info['Activity']['channel'] = $_GET['channel'];
				}
				if(isset($_GET['activity_name'])&&$_GET['activity_name']!=''){
					$activity_info['Activity']['name'] = $_GET['activity_name'];
				}
				if(isset($_GET['activity_desc'])&&$_GET['activity_desc']!=''){
					$activity_info['Activity']['description'] = $_GET['activity_desc'];
				}
				if(isset($_GET['activity_picture'])&&$_GET['activity_picture']!=''){
					$activity_info['Activity']['image'] = $_GET['activity_picture'];
				}
				if(isset($_GET['activity_address'])&&$_GET['activity_address']!=''){
					$activity_info['Activity']['address'] = $_GET['activity_address'];
				}
				if(isset($_GET['start_date'])&&$_GET['start_date']!=''){
					$activity_info['Activity']['start_date'] = $_GET['start_date'];
				}
				if(isset($_GET['end_date'])&&$_GET['end_date']!=''){
					$activity_info['Activity']['end_date'] = $_GET['end_date'];
				}
				if(isset($_GET['activity_price'])&&$_GET['activity_price']!=''){
					$activity_info['Activity']['price'] = $_GET['activity_price'];
				}
				if(isset($_GET['activity_status'])&&$_GET['activity_status']!=''){
					$activity_info['Activity']['status'] = $_GET['activity_status'];
					if(empty($activity_info['Activity']['id']))$activity_info['Activity']['created_user']=$user_id;
					$this->Activity->save($activity_info);
					$id=$this->Activity->id;
					if(isset($_GET['publisher_type'])&&$_GET['publisher_type']!=''){
						$activity_info['Activity']['publisher_type'] = $_GET['publisher_type'];
						if($_GET['publisher_type'] == 'O'){
							$activity_info['Activity']['publisher'] = $organization_id;

						}else if($_GET['publisher_type'] == 'U'){
							$activity_info['Activity']['publisher'] = $user_id;
							$activity_publisher_info['ActivityPublisher']['activity_id'] = $id;
							$activity_publisher_info['ActivityPublisher']['description'] = $_GET['publisher_desc'];
							$this->ActivityPublisher->save($activity_publisher_info);
						}
					}
					$result['code'] = 1;
					die(json_encode($result));
				}

			}
    	}
    }

    public function set_activity_confit($id){
    	if(!empty($_POST)){
    		if(isset($_POST['activity_id'])&&$_POST['activity_id']!=''){
    			$activity_info = $this->ActivityConfig->find('first',array('conditions'=>array('ActivityConfig.id'=>$id,'ActivityConfig.activity_id'=>$_POST['activity_id'])));
    			die(json_encode($activity_info));
    		}
    	}
    }

    public function delete_activity(){
    	$this->checkSessionUser();
    	Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result = array();
        $result['code'] = 0;
        if(isset($_POST['activity_id'])&&$_POST['activity_id']!=''){
        	$this->Activity->deleteAll(array('Activity.id'=>$_POST['activity_id']));
        	$result['code'] = 1;
        	die(json_encode($result));
        }
    }

    public function activity_centers($page = 1,$limit=12){
    	$this->layout="default_full";
    	$this->pageTitle = '推荐活动 - '.$this->configs['shop_title'];
    	$this->ur_heres[] = array('name' => '推荐活动', 'url' => '');

    	$condition = '';
    	$condition['Activity.status'] = 1;
    	if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
            $condition['or']['Activity.name like'] = '%' . $_REQUEST['keyword'] . '%';
            $condition['or']['Activity.address like'] = '%' . $_REQUEST['keyword'] . '%';
            $condition_tag = $this->ActivityTag->find('all',array('conditions'=>array('ActivityTag.tag_name like'=>'%' . $_REQUEST['keyword'] . '%'))); 
            $this->set('keyword', $_REQUEST['keyword']);
        }
        $condition_tag_list = array();
        if(isset($condition_tag)&&sizeof($condition_tag)>0){
        	foreach ($condition_tag as $k => $v) {
        		$condition_tag_list[] = $v['ActivityTag']['activity_id'];
        	}
        	$condition_tag_list = array_unique($condition_tag_list);
        	$condition['or']['Activity.id'] = $condition_tag_list;
        }
        //地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'Activities', 'action' => 'activity_centers', 'page' => $page, 'limit' => $limit);
		//分页参数
		$options = array('page' => $page, 'show' => $limit, 'modelClass' => 'Activity');
		$this->Pagination->init($condition, $parameters, $options); // Added
        $activities_list = $this->Activity->find('all',array('conditions'=>$condition,'page'=>$page,'limit'=>$limit,'order'=>'Activity.start_date'));
        $this->set('activities_list',$activities_list);
        //pr($activities_list);
        $activities_tags = $this->ActivityTag->find('all',array());
        $tag_list=array();
		foreach ($activities_tags as $v) {
			$tag_list[$v['ActivityTag']['activity_id']][]=$v['ActivityTag']['tag_name'];
		}
        $this->set('tag_list',$tag_list);
    }

    public function view($id = 0){
        $activities_info = $this->Activity->find('first',array('conditions'=>array('Activity.id'=>$id,'Activity.status'=>'1')));
        if(isset($_REQUEST['ajax'])&&$_REQUEST['ajax']=='1'){
        	if(!empty($activities_info)){
        		$this->layout='ajax';
        	}else{
        		die();
        	}
        }else{
	        if(empty($activities_info['Activity'])){
	        	$this->redirect('/activities/activity_centers');
	        }
        }
        $this->page_init(array());
        $activity_publisher=array();
        $activity_publisher_detail=$this->ActivityPublisher->find('first',array('conditions'=>array('ActivityPublisher.activity_id'=>$id)));
        if($activities_info['Activity']['publisher_type']=='O'){
            	$organization_list = $this->Organization->find('first',array('fields'=>"Organization.name,Organization.logo",'conditions'=>array('Organization.id'=>$activities_info['Activity']['publisher'])));
            	if(!empty($organization_list['Organization'])){
            		$activity_publisher=$organization_list['Organization'];
            	}
        }else if($activities_info['Activity']['publisher_type']=='U'){
        	$user_list = $this->User->find('first',array('fields'=>"User.id,User.name,User.first_name,User.img01",'conditions'=>array('User.id'=>$activities_info['Activity']['publisher'])));
        	if(!empty($organization_list['Organization'])){
        		$activity_publisher=array(
        			'name'=>trim($user_list['User']['first_name'])!=''?$user_list['User']['first_name']:$user_list['User']['name'],
        			'logo'=>$user_list['User']['img01'],
        			'description'=>isset($activity_publisher_detail['ActivityPublisher'])?$activity_publisher_detail['ActivityPublisher']['description']:''
        		);
        	}
        }else if(!empty($activity_publisher_detail)&&isset($activity_publisher_detail['ActivityPublisher']['name'])&& $activity_publisher_detail['ActivityPublisher']['name']!=''){
        	$activity_publisher=array(
    			'name'=>$activity_publisher_detail['ActivityPublisher']['name'],
    			'logo'=>$activity_publisher_detail['ActivityPublisher']['logo'],
    			'description'=>$activity_publisher_detail['ActivityPublisher']['description']
    		);
        }
        if(!empty($activity_publisher))$this->set('activity_publisher',$activity_publisher);
        
        $activities_user = $this->ActivityUser->find('list',array('fields'=>'ActivityUser.user_id','conditions'=>array('ActivityUser.activity_id'=>$id)));
        $activities_user_list_condition = array(
        	'User.id'=>empty($activities_user)?0:$activities_user,
        	'User.status'=>'1'
        );
        $activities_user_list = $this->User->find('all',array('conditions'=>$activities_user_list_condition));
        $activities_user_pay_list = $this->ActivityUser->find('all',array('conditions'=>array('ActivityUser.activity_id'=>$id,'ActivityUser.payment_status'=>0)));
        $this->set('activities_info',$activities_info);
        $this->set('activities_user_list',$activities_user_list);
        $this->set('activities_user_pay_list',$activities_user_pay_list);

        if($activities_info['Activity']['type']=='C'){
        	$course_list = $this->Course->find('all',array('conditions'=>array('id'=>$activities_info['Activity']['type_id'])));
        	$this->set('course_list',$course_list);
        }
        if($activities_info['Activity']['type']=='E'){
        	$evaluation_list = $this->Evaluation->find('all',array('conditions'=>array('id'=>$activities_info['Activity']['type_id'])));
        	$this->set('evaluation_list',$evaluation_list);
        }

        if(isset($_SESSION['User']['User']['id'])&&$_SESSION['User']['User']['id']!=''){
        	$pay_judge = $this->ActivityUser->find('first',array('conditions'=>array('ActivityUser.activity_id'=>$id,'ActivityUser.user_id'=>$_SESSION['User']['User']['id'])));
        	$this->set('pay_judge',$pay_judge);
        }
		
	        $tap_list = $this->ActivityTag->find('all',array('conditions'=>array('activity_id'=>$id)));
	        $this->set('tap_list',$tap_list);
        
	        $need_buy=$activities_info['Activity']['price']>0?true:false;
	        $user_id = isset($_SESSION['User']['User']['id'])?$_SESSION['User']['User']['id']:0;
	        if($need_buy&&!empty($user_id)){
			$this->loadModel('OrderProduct');
			$order_cond=array();
			$order_cond['Order.user_id']=$user_id;
			$order_cond['Order.status']='1';
			$order_cond['Order.payment_status']='2';
			$order_cond['OrderProduct.item_type']='activity';
			$order_cond['OrderProduct.product_id']=$id;
			$order_info=$this->OrderProduct->find('count',array('conditions'=>$order_cond));
			if(!empty($order_info))$need_buy=false;
		}
		$this->set('need_buy',$need_buy);
		
		if(!empty($user_id)){
			$ActivityUserTotal = $this->ActivityUser->find('count',array('conditions'=>array('ActivityUser.activity_id'=>$id,'ActivityUser.user_id <>'=>$user_id)));
			$max_activity_user=intval(Configure::read('HR.max_activity_user'));
			if($ActivityUserTotal>$max_activity_user){
				$this->set('max_activity_user',$max_activity_user);
			}
		}
		
		$this->pageTitle = $activities_info['Activity']['name'].' - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '推荐活动', 'url' => '/activities/activity_centers');
		$this->ur_heres[] = array('name' => $activities_info['Activity']['name'], 'url' => '');
    }

    public function activity_user_edit($id=0){
    		$this->checkSessionUser();
		$activity_configs = $this->ActivityConfig->find('all',array('conditions'=>array('ActivityConfig.activity_id'=>$id,'ActivityConfig.status'=>1),'order'=>'orderby,id'));
		if(isset($_GET['user_id'])&&$_GET['user_id']>0){
			$user_activitiy = $this->ActivityUser->find('first',array('conditions'=>array('activity_id'=>$id,'user_id'=>$_GET['user_id'])));
			$this->set('user_activitiy',$user_activitiy);
			
			$activity_user_configs = $this->ActivityUserConfig->find('all',array('conditions'=>array('activity_id'=>$id,'activity_user_id'=>$_GET['user_id'])));
			$activity_user_config_datas=array();
			foreach ($activity_user_configs as $v) {
				$activity_user_config_datas[$v['ActivityUserConfig']['activity_config_id']]=$v['ActivityUserConfig'];
			}
			$this->set('activity_id',$id);
			$this->set('activity_configs',$activity_configs);
			$this->set('activity_user_config_datas',$activity_user_config_datas);
			
			if(!isset($user_activitiy)||empty($user_activitiy)){
				$this->redirect('/activities/view/'.$id);
			}
		}else{
			$user_activitiy = $this->ActivityUser->find('first',array('conditions'=>array('activity_id'=>$id,'user_id'=>$_SESSION['User']['User']['id'])));
			$this->set('user_activitiy',$user_activitiy);
			$activity_user_configs = $this->ActivityUserConfig->find('all',array('conditions'=>array('activity_id'=>$id,'activity_user_id'=>$_SESSION['User']['User']['id'])));
			$activity_user_config_datas=array();
			foreach ($activity_user_configs as $v) {
				$activity_user_config_datas[$v['ActivityUserConfig']['activity_config_id']]=$v['ActivityUserConfig'];
			}
			$this->set('activity_id',$id);
			$this->set('activity_configs',$activity_configs);
			$this->set('activity_user_config_datas',$activity_user_config_datas);
			$this->set('pay_judge',$user_activitiy);
		}
		$activities_info = $this->Activity->find('first',array('conditions'=>array('Activity.id'=>$id)));
		if(empty($activities_info))$this->redirect('/');
		$this->set('activities_info',$activities_info);
		
		$ActivityUserTotal = $this->ActivityUser->find('count',array('conditions'=>array('ActivityUser.activity_id'=>$id,'ActivityUser.user_id <>'=>$_SESSION['User']['User']['id'])));
		$max_activity_user=intval(Configure::read('HR.max_activity_user'));
		if($ActivityUserTotal>$max_activity_user){
			$this->redirect('/activities/view/'.$id);
		}
		
		$this->pageTitle = '推荐活动 - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '推荐活动', 'url' => '/activities/activity_centers');
		$this->ur_heres[] = array('name' => $activities_info['Activity']['name'], 'url' => '/activities/view/'.$id);
		if(!isset($_GET['user_id'])){
			$this->ur_heres[] = array('name' => '活动报名', 'url' => '');
		}else{
			if(isset($_GET['user_info'])){
				$this->ur_heres[] = array('name' => '我的报名信息', 'url' => '');
			}else{
				$this->ur_heres[] = array('name' => '报名信息', 'url' => '');
			}
		}
    }
    
    public function activity_user_check($activity_id=0,$user_id=0){
    		$this->pageTitle = '活动用户 - '.$this->configs['shop_title'];
    		$this->ur_heres[] = array('name' => '活动用户', 'url' => '');
    		
    		$activity_data=$this->Activity->find('first',array('conditions'=>array('Activity.id'=>$activity_id)));
    		if(!empty($activity_data)){
    			$this->set('activity_data',$activity_data);
    			$activity_configs=$this->ActivityConfig->find('list',array('fields'=>'id,name','conditions'=>array('ActivityConfig.activity_id'=>$activity_id,'ActivityConfig.status'=>'1'),'order'=>'orderby,id'));
    			$this->set('activity_configs',$activity_configs);
    			
    			$activity_user_data=$this->ActivityUser->find('first',array('conditions'=>array('ActivityUser.user_id'=>$user_id,'ActivityUser.activity_id'=>$activity_id,'ActivityUser.user_id <>'=>0)));
    			if(!empty($activity_user_data)){
    				$this->set('activity_user_data',$activity_user_data);
    				
    				$activity_user_configs=$this->ActivityUserConfig->find('list',array('fields'=>'activity_config_id,config_value','conditions'=>array('ActivityUserConfig.activity_user_id'=>$user_id,'ActivityUserConfig.activity_id'=>$activity_id)));
    				$this->set('activity_user_configs',$activity_user_configs);
    			}
    		}
    }

    public function activity_user_sub($id=0){
    		$this->checkSessionUser();
		Configure::write('debug', 1);
    		$this->layout = 'ajax';
		$result=array();
		$result['code']='0';
		$result['message']='';
		if($this->RequestHandler->isPost()){
			$con = array();
			$con['ActivityUserConfig.activity_id'] = $_POST['data']['activity_id'];
			$con['ActivityUserConfig.activity_user_id'] = $_POST['data']['user_id'];
			$user_config_info = $this->ActivityUserConfig->find('all',array('conditions'=>$con));
			foreach ($user_config_info as $k => $v) {
				$user_config_check[$v['ActivityUserConfig']['activity_config_id']] = $v;
			}
			$condition = array();
			if(isset($_POST['data']['ActivityUserConfig'])){
				foreach ($_POST['data']['ActivityUserConfig'] as $k => $v) {
					if(is_array($v)){
						$v = implode(',',$v);
					}
					$add_data = array(
						'id'=>$user_config_check[$k]['ActivityUserConfig']['id'],
						'activity_id'=>$_POST['data']['activity_id'],
						'activity_user_id'=>$_POST['data']['user_id'],
						'activity_config_id'=>$k,
						'config_value'=>$v
					);
					$this->ActivityUserConfig->saveAll($add_data);
					$condition['NOT']['ActivityUserConfig.activity_config_id'][]=$k;
				}
			}
			$activity_user = $this->ActivityUser->find('first',array('conditions'=>array('activity_id'=>$_POST['data']['activity_id'],'user_id'=>$_GET['user_id'])));
			$save_user = array(
				'id'=>isset($activity_user['ActivityUser']['id'])?$activity_user['ActivityUser']['id']:0,
				'user_id'=>$_GET['user_id'],
				'activity_id'=>$_POST['data']['activity_id'],
				'name'=>$_POST['ActivityUser_name'],
				'mobile'=>$_POST['ActivityUser_mobile']
			);
			$this->ActivityUser->save($save_user);
			$activity_detail=$this->Activity->find('first',array('conditions'=>array('id'=>$_POST['data']['activity_id'],'status'=>'1')));
			if(!empty($activity_detail)&&$activity_detail['Activity']['publisher_type']=='O'&&intval($activity_detail['Activity']['publisher'])>0){
				$this->loadModel('OrganizationUser');
				$organization_user_detail=$this->OrganizationUser->find('first',array('conditions'=>array('organization_id'=>$activity_detail['Activity']['publisher'],'user_id'=>$_GET['user_id'])));
				if(empty($organization_user_detail)){
					$organization_user_data=array(
						'id'=>0,
						'organization_id'=>$activity_detail['Activity']['publisher'],
						'user_id'=>$_GET['user_id']
					);
					$this->OrganizationUser->save($organization_user_data);
				}
			}
			$condition['ActivityUserConfig.activity_id'] = $_POST['data']['activity_id'];
			$condition['ActivityUserConfig.activity_user_id'] = $_POST['data']['user_id'];
			$this->ActivityUserConfig->deleteAll($condition);
			$result['code']='1';
		}
		die(json_encode($result));
    }

    public function activity_user_add($id=0){
    		$this->checkSessionUser();
		Configure::write('debug', 1);
    		$this->layout = 'ajax';
		$result=array();
		$result['code']='0';
		$result['message']='';
		if($this->RequestHandler->isPost()){
			$user_activity_config_cond=array(
				'ActivityUserConfig.activity_id'=>$_POST['data']['activity_id'],
				'ActivityUserConfig.activity_user_id'=>$_SESSION['User']['User']['id']
			);
			$user_activity_config_list=$this->ActivityUserConfig->find('list',array('conditions'=>$user_activity_config_cond,'fields'=>'ActivityUserConfig.activity_config_id,ActivityUserConfig.id'));
			if(isset($_POST['data']['ActivityUserConfig'])){
				foreach ($_POST['data']['ActivityUserConfig'] as $k => $v) {
					if(is_array($v))$v = implode(',',$v);
					$add_data = array(
						'id'=>isset($user_activity_config_list[$k])?$user_activity_config_list[$k]:0,
						'activity_id'=>$_POST['data']['activity_id'],
						'activity_user_id'=>$_SESSION['User']['User']['id'],
						'activity_config_id'=>$k,
						'config_value'=>$v
					);
					$this->ActivityUserConfig->saveAll($add_data);
				}
			}
			$user_activity_cond=array(
				'ActivityUser.activity_id'=>$_POST['data']['activity_id'],
				'ActivityUser.user_id'=>$_SESSION['User']['User']['id']
			);
			$user_activity_detail=$this->ActivityUser->find('first',array('conditions'=>$user_activity_cond,'fields'=>'ActivityUser.id'));
			$activities_info = $this->Activity->find('first',array('conditions'=>array('Activity.id'=>$_POST['data']['activity_id'])));
			if($activities_info['Activity']['price']==0){
				$add_user = array(
					'id'=>isset($user_activity_detail['ActivityUser'])?$user_activity_detail['ActivityUser']['id']:0,
					'activity_id'=>$_POST['data']['activity_id'],
					'user_id'=>$_SESSION['User']['User']['id'],
					'name'=>$_POST['ActivityUser_name'],
					'mobile'=>$_POST['ActivityUser_mobile'],
					'status'=>1,
					'payment_status'=>1
				);
				$result['message']='payment_status_1';
			}else{
				$add_user = array(
					'id'=>isset($user_activity_detail['ActivityUser'])?$user_activity_detail['ActivityUser']['id']:0,
					'activity_id'=>$_POST['data']['activity_id'],
					'user_id'=>$_SESSION['User']['User']['id'],
					'name'=>$_POST['ActivityUser_name'],
					'mobile'=>$_POST['ActivityUser_mobile'],
					'status'=>1,
					'payment_status'=>0
				);
				$this->loadModel('OrderProduct');
				$order_cond=array();
				$order_cond['Order.user_id']=$_SESSION['User']['User']['id'];
				$order_cond['Order.status']='1';
				$order_cond['OrderProduct.item_type']='activity';
				$order_cond['OrderProduct.product_id']=$_POST['data']['activity_id'];
				$order_cond['Order.payment_status']='2';
				$activity_order=$this->OrderProduct->find('first',array('conditions'=>$order_cond));
				if(!empty($activity_order)){
					$add_user['payment_status']='2';
					$result['message']='payment_status_1';
				}else{
					$result['message']='payment_status_0';
					
					$this->loadModel('CourseClassWare');
					$activity_course_cond=array(
						'CourseClassWare.status'=>'1',
						'CourseClassWare.course_code <>'=>'',
						'CourseClassWare.course_class_code <>'=>'',
						'CourseClassWare.type'=>'activity',
						'CourseClassWare.ware'=>$_POST['data']['activity_id']
					);
					$activity_course_infos=$this->CourseClassWare->find('list',array('fields'=>'CourseClassWare.id,CourseClassWare.course_class_code','conditions'=>$activity_course_cond));
			    		if(!empty($activity_course_infos)){
			    			$this->loadModel('CourseClass');
			    			$activity_course_class=$this->CourseClass->find('all',array('fields'=>"CourseClass.id,CourseClass.price,Course.id,Course.price",
			    				'conditions'=>array(
			    				'CourseClass.code'=>$activity_course_infos,
			    				'CourseClass.status'=>'1'
			    			)));
			    			if(!empty($activity_course_class)){
			    				$order_course_cond=array();
			    				$not_need_buy=array();
			    				foreach($activity_course_class as $v){
			    					if($v['CourseClass']['price']>0){
			    						$order_course_cond['or'][]=array('OrderProduct.item_type'=>'course_class','OrderProduct.product_id'=>$v['CourseClass']['id']);
			    					}else if($v['Course']['price']>0){
			    						$order_course_cond['or'][]=array('OrderProduct.item_type'=>'course','OrderProduct.product_id'=>$v['Course']['id']);
			    					}else{
			    						$not_need_buy[]=$v['CourseClass']['id'];
			    					}
			    				}
			    				if(empty($not_need_buy)&&!empty($order_course_cond)){
					    			$order_course_cond['Order.user_id']=$_SESSION['User']['User']['id'];
								$order_course_cond['Order.status']='1';
								$order_course_cond['Order.payment_status']='2';
								$this->loadModel('OrderProduct');
								$order_course_list=$this->OrderProduct->find('count',array('conditions'=>$order_course_cond));
								if(!empty($order_course_list))$result['message']='payment_status_1';
							}else if(!empty($not_need_buy)){
								$result['message']='payment_status_1';
							}
			    			}
			    		}
				}
			}
			$this->ActivityUser->save($add_user);
			$activity_detail=$this->Activity->find('first',array('conditions'=>array('id'=>$_POST['data']['activity_id'],'status'=>'1')));
			if(!empty($activity_detail)&&$activity_detail['Activity']['publisher_type']=='O'&&intval($activity_detail['Activity']['publisher'])>0){
				$this->loadModel('OrganizationUser');
				$organization_user_detail=$this->OrganizationUser->find('first',array('conditions'=>array('organization_id'=>$activity_detail['Activity']['publisher'],'user_id'=>$_SESSION['User']['User']['id'])));
				if(empty($organization_user_detail)){
					$organization_user_data=array(
						'id'=>0,
						'organization_id'=>$_POST['data']['activity_id'],
						'user_id'=>$_SESSION['User']['User']['id']
					);
					$this->OrganizationUser->save($organization_user_data);
				}
			}
			$result['code']='1';
		}
		die(json_encode($result));
    }

    public function sub_tag($id=0){
    	$this->checkSessionUser();
    	Configure::write('debug', 1);
    	$this->layout = 'ajax';
		$result=array();
		$result['code']='0';
		if(!empty($_POST)){
			if(isset($_POST['tag_name'])&&$_POST['tag_name']!=''){
				$tg = $this->ActivityTag->find('first',array('conditions'=>array('ActivityTag.tag_name'=>$_POST['tag_name'])));
				if(!empty($tg)){
					$tag_info['ActivityTag']['id'] = $tg['ActivityTag']['id'];
				}else{
					$tag_info['ActivityTag']['id'] = $id;
				}
				$tag_info['ActivityTag']['activity_id'] = $_POST['activity_id'];
				$tag_info['ActivityTag']['tag_name'] = $_POST['tag_name'];
				$this->ActivityTag->save($tag_info);
				$result['code']='1';
				die(json_encode($result));
			}
		}
    }

    public function delete_tag($id=0){
    	$this->checkSessionUser();
    	Configure::write('debug', 1);
    	$this->layout = 'ajax';
		$result=array();
		$result['code']='0';

			if($id!=0){
				$this->ActivityTag->deleteAll(array('ActivityTag.id'=>$id));
				$result['code']=1;
				die(json_encode($result));
			}
		
    }

    public function user_index($page=1,$limit=5){
	    	$this->checkSessionUser();
	    	$this->ur_heres[] = array('name' => '用户中心', 'url' => '/users/index/');
	    	$this->ur_heres[] = array('name' => '活动列表', 'url' => '');
	    	$this->layout = 'usercenter';//引入模版
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
			Configure::write('debug', 0);
			$this->layout = 'ajax';
		}
		$_GET=$this->clean_xss($_GET);
		$user_id = $_SESSION['User']['User']['id'];
		$user_list = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
		$this->set('user_list',$user_list);
		$page=isset($_GET['page'])?intval($_GET['page']):$page;
		$limit=isset($_GET['limit'])?intval($_GET['limit']):$limit;
		if($page>1){
			$this->pageTitle = $this->ld['activity'].'列表'.' - '.sprintf($this->ld['page'], $page).'列表'.' - '.$this->configs['shop_title'];//
		}else{
			$this->pageTitle = $this->ld['activity'].'列表'.' - '.$this->configs['shop_title'];
		}
		$condition = array();
		$condition['Activity.publisher_type']='U';
		$condition['Activity.publisher']=$user_id;

		//$condition['Activity.status']='1';
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'activities','action' => 'user_index','page' => $page,'limit' => $limit);
		//分页参数
		$options = array('page' => $page,'show' => $limit,'modelClass' => 'Activity');
		$this->Pagination->init($condition, $parameters, $options); // Added
		
		$activity_list = $this->Activity->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $limit, 'order' => 'Activity.start_date desc'));
		if(!empty($activity_list)){
			$con = array();
			$con['ActivityUser.user_id >']=0;
			foreach ($activity_list as $k => $v) {
				$con['ActivityUser.activity_id'][]=$v['Activity']['id'];
			}
			$act_user_info = $this->ActivityUser->find('all',array('conditions'=>$con,'fields'=>'ActivityUser.activity_id,count(*) as activity_user','group'=>'ActivityUser.activity_id'));
			if(is_array($act_user_info)&&count($act_user_info)>0){
				foreach ($act_user_info as $k => $v) {
					$act_user_check[$v['ActivityUser']['activity_id']]= $v[0]['activity_user'];
				}
				$this->set('act_user_check',$act_user_check);
			}
		}
		$this->set('activity_list',$activity_list);
    }
	
    public function ajax_get_activity_count(){
    	$this->checkSessionUser();
    	$this->layout = 'usercenter';//引入模版
    	$user_id = $_SESSION['User']['User']['id'];
    	$result = array();
    	$result['code'] = 0;
    	$activity_count = $this->ActivityUser->find('count',array('conditions'=>array('ActivityUser.user_id'=>$user_id)));
    	die(json_encode($activity_count?$activity_count:0));
    }

    public function check_code(){
		$this->checkSessionUser();
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result=array();
		$result['code']='0';
		$activity_id = isset($_POST['activity_id'])?$_POST['activity_id']:0;
		$activity_user_id = isset($_POST['user_id'])?$_POST['user_id']:0;
		$check_info = $this->ActivityUser->find('first',array('conditions'=>array('ActivityUser.activity_id'=>$activity_id,'ActivityUser.user_id'=>$activity_user_id,'ActivityUser.payment_status'=>1)));
		$activity_info = $this->Activity->find('first',array('conditions'=>array('Activity.id'=>$activity_id,'Activity.status'=>'1')));
		if(!empty($check_info)&&!empty($activity_info)){
			$result['code']='1';
			if($activity_info['Activity']['publisher_type']=='O'){
				$user_activity_tag_list = $this->OrganizationUserTag->find('list',array('fields'=>'id,tag_name','conditions'=>array('organization_id'=>$activity_info['Activity']['publisher'],'OrganizationUserTag.user_id'=>$activity_user_id,'tag_name <>'=>'')));
				$activity_tag_cond=array();
				$activity_tag_cond['ActivityTag.activity_id']=$activity_id;
				$activity_tag_cond['ActivityTag.tag_name <>']='';
				if(!empty($user_activity_tag_list)){
					$activity_tag_cond['not']['ActivityTag.tag_name']=$user_activity_tag_list;
				}
				$activity_tag_list = $this->ActivityTag->find('list',array('fields'=>'id,tag_name','conditions'=>$activity_tag_cond));
				if(!empty($activity_tag_list)){
					foreach($activity_tag_list as $v){
						$user_activity_tag=array(
							'id'=>0,
							'user_id'=>$activity_user_id,
							'organization_id'=>$activity_info['Activity']['publisher'],
							'tag_name'=>$v
						);
						$this->OrganizationUserTag->save($user_activity_tag);
					}
				}
			}
		}
		die(json_encode($result));
    }

    public function user_view($id=0){
	    	$this->checkSessionUser();
	    	$this->layout = 'usercenter';//引入模版
	    	$user_id = $_SESSION['User']['User']['id'];
	    	$this->pageTitle = '编辑活动 - '.$this->configs['shop_title'];
	    	$user_id = $_SESSION['User']['User']['id'];
	    	$user_list = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
	    	$this->set('user_list',$user_list);
	    	$this->ur_heres[] = array('name' => '用户中心', 'url' => '/users/index/');
		$this->ur_heres[] = array('name' => '活动列表', 'url' => '/activities/user_index');
		$this->ur_heres[] = array('name' => '编辑活动', 'url' => '');
		
		if ($this->RequestHandler->isPost()) {
			Configure::write('debug', 1);
			$this->layout = 'ajax';
			$result=array();
			$result['code'] = 0;
			if(isset($this->data['Activity'])){
				$this->data['Activity']['publisher'] = $user_id;
				$this->data['Activity']['created_user'] = $user_id;
				$this->Activity->save($this->data['Activity']);
				$activity_id=$this->Activity->id;
				if(isset($this->data['ActivityPublisher'])){
					$this->data['ActivityPublisher']['activity_id'] = $activity_id;
					$this->ActivityPublisher->save($this->data['ActivityPublisher']);
				}
				$result['code'] = 1;
			}
			die(json_encode($result));
		}
		$activity_info = $this->Activity->find('first',array('conditions'=>array('Activity.id'=>$id,'created_user'=>$user_id)));
		$this->set('activity_info',$activity_info);
		if(!empty($activity_info)){
			$activity_publisher_info = $this->ActivityPublisher->find('first',array('conditions'=>array('ActivityPublisher.activity_id'=>$id)));
			$this->set('activity_publisher_info',$activity_publisher_info);
			$activity_config_info = $this->ActivityConfig->find('all',array('conditions'=>array('ActivityConfig.activity_id'=>$id)));
			$this->set('activity_config_info',$activity_config_info);
			$activity_tag_info = $this->ActivityTag->find('all',array('conditions'=>array('ActivityTag.activity_id'=>$id),'order'=>'ActivityTag.created'));
			$this->set('activity_tag_info',$activity_tag_info);
		}
    }

    public function activity_send_out(){
    		$this->checkSessionUser();
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result=array();
		$result['code']='0';
		$user_id=isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
		$activity_id=isset($_REQUEST['activity_id'])?$_REQUEST['activity_id']:0;
		$activity_user_detail=$this->ActivityUser->find('first',array('conditions'=>array('user_id'=>$user_id,'activity_id'=>$activity_id,'status'=>'1','payment_status'=>'1')));
		if(!empty($activity_user_detail)){
			$activity_detail=$this->Activity->find('first',array('conditions'=>array('Activity.status'=>'1','Activity.id'=>$activity_id)));
			$user_info=$this->User->find('first',array('conditions'=>array('User.id'=>$user_id,'User.status'=>'1')));
			if(!empty($activity_detail)&&!empty($user_info)){
				$activity_name=$activity_detail['Activity']['name'];
				$user_mobile=isset($user_info['User']['mobile'])?$user_info['User']['mobile']:'';
				if($activity_detail['Activity']['publisher_type']=='O'&&$activity_detail['Activity']['publisher']>0){
					$this->loadModel('OrganizationMember');
					$this->loadModel('OrganizationAppRelation');
					$organization_user_detail=$this->OrganizationMember->find('first',array('conditions'=>array('status'=>'1','user_id'=>$user_id,'organization_id'=>$activity_detail['Activity']['publisher'])));
					if(!empty($organization_user_detail)){
						$wxwork_user=$this->OrganizationAppRelation->find('first',array('conditions'=>array('type'=>'member','organization_id'=>$activity_detail['Activity']['publisher'],'organization_type_id'=>$organization_user_detail['OrganizationMember']['id'])));
					}
				}
				$notify_content="";
				$this->loadModel('NotifyTemplateType');
				$notify_template=$this->NotifyTemplateType->typeformat('registration_activities','sms');
				if(!empty($notify_template)){
					$notify_content=$notify_template['sms']['NotifyTemplateTypeI18n']['param02'];
				}
				$activity_check_link=$this->server_host.'/activities/activity_user_qrcode/'.$activity_id.'/'.$user_id;
				if(isset($wxwork_user)&&!empty($wxwork_user)){
					$activity_check_link='';
					@eval("\$notify_content = \"$notify_content\";");
					$activity_logo=$activity_detail['Activity']['image'];
					if(!preg_match('#^http#i',$activity_logo)){
						$activity_logo=$this->server_host.$activity_logo;
					}
					$articles=array();
					$articles[]=array(
						'title'=>isset($notify_template['sms']['NotifyTemplateTypeI18n']['title'])?$notify_template['sms']['NotifyTemplateTypeI18n']['title']:'报名活动',
						'description'=>$notify_content,
						'url'=>$this->server_host.'/activities/activity_user_qrcode/'.$activity_id.'/'.$user_id,
						'picurl'=>$activity_logo,
						'btntxt'=>'详情'
					);
					$this->api_send_message($activity_detail['Activity']['publisher'],$wxwork_user['OrganizationAppRelation']['organization_type_id'],$articles);
					$result['code']='1';
				}else if($user_mobile!=''){
					@eval("\$notify_content = \"$notify_content\";");
					$sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
                			$sms_result=$this->Notify->send_sms($user_mobile,$notify_content,$sms_kanal,$this->configs);
                			$result['code']='1';
				}
			}
		}
		die(json_encode($result));
    }
    
    function activity_user_qrcode($activity_id=0,$user_id=0){
    		Configure::write('debug', 0);
		$this->layout = 'ajax';
		$activity_user_detail=$this->ActivityUser->find('first',array('conditions'=>array('user_id'=>$user_id,'activity_id'=>$activity_id,'status'=>'1','payment_status'=>'1')));
		if(!empty($activity_user_detail)){
			App::import('Vendor', 'phpqrcode', array('file' => 'phpqrcode.php'));
			$phpqrcode_content=$user_id;
			$phpqrcode_size=isset($_REQUEST['size'])?intval($_REQUEST['size']):5;
			$phpqrcode_size=$phpqrcode_size>0?$phpqrcode_size:5;
			$level = 'L';
			QRcode::png($phpqrcode_content,false,$level,$phpqrcode_size);
			exit();
		}else{
			$this->redirect('/pages/home');
		}
    }
    
    function api_send_message($organization_id=0,$member_ids,$post_data){
    		$this->loadModel('OrganizationApp');
    		$this->loadModel('OrganizationAppConfigValue');
    		
    		$message_send_success=array();
    		$conditions=array();
    		$organization_ids=array(0,$organization_id);
    		$conditions['OrganizationApp.organization_id']=$organization_ids;
    		$conditions['OrganizationApp.type']='QYWechat';
    		$conditions['OrganizationApp.status']='1';
    		if(!empty($member_ids)){
    			$organization_app=$this->OrganizationApp->find('list',array('fields'=>'organization_id,id','conditions'=>$conditions,'order'=>'organization_id'));
    		}
    		if(!empty($organization_app)&&sizeof($organization_app)==2){
    			$system_organization_app_id=$organization_app[0];
    			$organization_app_id=$organization_app[$organization_id];
    			$organization_configs=$this->OrganizationAppConfigValue->find('list',array('fields'=>array('config_code','config_value','organization_app_id'),'conditions'=>array('organization_app_id'=>$organization_app)));
    			$corp_token=isset($organization_configs[$organization_app_id]['AuthToken'])?$organization_configs[$organization_app_id]['AuthToken']:'';
    			$corp_token_expire_time=isset($organization_configs[$organization_app_id]['AuthTokenExpireTime'])?$organization_configs[$organization_app_id]['AuthTokenExpireTime']:'';
    			if($corp_token==''||intval($corp_token_expire_time)<(time()-180)){
	    			$token_params=array(
	    				'SuiteId'=>isset($organization_configs[$system_organization_app_id]['SuiteId'])?$organization_configs[$system_organization_app_id]['SuiteId']:'',
	    				'SuiteToken'=>isset($organization_configs[$system_organization_app_id]['SuiteToken'])?$organization_configs[$system_organization_app_id]['SuiteToken']:'',
	    				'PermanentCode'=>isset($organization_configs[$organization_app_id]['PermanentCode'])?$organization_configs[$organization_app_id]['PermanentCode']:'',
	    				'AuthCorpid'=>isset($organization_configs[$organization_app_id]['AuthCorpid'])?$organization_configs[$organization_app_id]['AuthCorpid']:''
	    			);
	    			$corp_token=$this->api_update_channel_token($organization_app_id,'QYWechat',$token_params);
	    			$organization_configs[$organization_app_id]['AuthToken']=$corp_token;
    			}
    			$agentid=isset($organization_configs[$organization_app_id]['AgentId'])?$organization_configs[$organization_app_id]['AgentId']:'';
    			if($corp_token!=''&&$agentid!=''){
    				$touser_cond=array(
    					'organization_id'=>$organization_id,
    					'organization_app_id'=>$organization_app_id,
    					'type'=>'member',
    					'organization_type_id'=>$member_ids,
    					'type_id <>'=>''
    				);
				$touser_list=$this->OrganizationAppRelation->find('list',array('conditions'=>$touser_cond,'fields'=>'organization_type_id,type_id'));
				if(!empty($touser_list)){
					$organization_info=$this->Organization->find('first',array('conditions'=>array('status'=>'1')));
					$organization_logo=$organization_info['Organization']['logo'];
					if(!preg_match('#^http#i',$organization_logo)){
						$organization_logo=$this->server_host.$organization_logo;
					}
					$touser=implode('|',$touser_list);
					$msg_params=array(
						'touser'=>$touser,
						'msgtype'=>'news',
						'agentid'=>$agentid,
						'news'=>array(
							'articles'=>$post_data
						)
					);
					$messagesend_request_url="https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token={$corp_token}";
					$result = $this->https_request($messagesend_request_url, $this->to_josn($msg_params));
					$qywechat_result=isset($result['errcode'])&&$result['errcode']=='0'?true:false;
				}
    			}
    		}
    		return $message_send_success;
    }
    
    
    public function ajax_upload_media(){
        $this->checkSessionUser();
         $this->layout = 'ajax';
        Configure::write('debug', 1);
         
        if(isset($_POST['org_id'])&&$_POST['org_id']!=''){
            $img_root = 'media/organizations/'.$_POST['org_id'].'/';
            $imgaddr = WWW_ROOT.'media/organizations/'.$_POST['org_id'].'/';
        }
        if(isset($_POST['org_code'])&&$_POST['org_code']!=''){
            $org_code = $_POST['org_code'];
        }
        //pr($org_code);exit();
        $this->mkdirs($imgaddr);
        @chmod($imgaddr, 0777);
        $result['code'] = '0';
        $result['error'] = '文件不存在';
        $error = '';
        //pr()
        if ($this->RequestHandler->isPost()) {
            //pr($result);exit();
            if (isset($_FILES[$org_code])) {
                //pr($result);exit();
                if ((!empty($_FILES[$org_code])) && ($_FILES[$org_code]['error'] == 0)) {

                    $userfile_name = $_FILES[$org_code]['name'];
                    $userfile_tmp = $_FILES[$org_code]['tmp_name'];
                    $userfile_size = $_FILES[$org_code]['size'];
                    $userfile_type = $_FILES[$org_code]['type'];
                    $filename = basename($_FILES[$org_code]['name']);
                    $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
                    
                } else {
                    $error = '上传失败';
                }
                //pr($user_id);exit();
                if (strlen($error) == 0) {
                    $image_location = $imgaddr.md5(date('Y-m-d h:i:s').$userfile_name).'.'.$file_ext;
                    $image_name = '/'.$img_root.md5(date('Y-m-d h:i:s').$userfile_name).'.'.$file_ext;

                    if (move_uploaded_file($userfile_tmp, $image_location)) {
                        //$width = $this->getWidth($image_location);
                        //$height = $this->getHeight($image_location);
                            $scale = 1;
                            //$uploaded = $this->resizeImage($image_location, $width, $height, $scale);
                            //$width = $this->getWidth($image_location);
                            //$height = $this->getHeight($image_location);

                            $result['code'] = '1';
                            $result['img_url'] = $image_name;
                           // $result['width'] = $width;
                            //$result['height'] = $height;
                        }
                    } else {
                        $error = '上传失败';
                    }
            }
            $result['error'] = $error;
        }
        die(json_encode($result));
    }
    
    /*
        调用接口
    */
    private function https_request($url, $data = null){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		if (!empty($data)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return json_decode($output, true);
    }
    
    /*
        去除字符串空格
    */
    private function emptyreplace($str){
        $str = trim($str);
        $str = strip_tags($str, '');
        $str = ereg_replace("\t", '', $str);
        $str = ereg_replace("\r\n", '', $str);
        $str = ereg_replace("\r", '', $str);
        $str = ereg_replace("\n", '', $str);
        $str = ereg_replace(' ', ' ', $str);

        return trim($str);
    }

    /*
        $data   需要转换josn提交的数据
    */
    private function to_josn($data){
	        $this->arrayRecursive($data, 'urlencode');
	        $json = json_encode($data);
	        return urldecode($json);
    }
    
    
    /**************************************************************
     * 对数组中所有元素做处理,保留中文
     * @param string &$array 要处理的数组
     * @param string $function 要执行的函数
     * @return boolean $apply_to_keys_also 是否也应用到key上
     * @access public
     *
     *************************************************************/
    public function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }
            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        --$recursive_counter;
    }
}