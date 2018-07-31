<?php
/*****************************************************************************
 * Seevia 课程管理
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
 *这是一个名为 CoursesController 的控制器
 *课程管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class CoursesController extends AppController
{
    public $name = 'Courses';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('Course','CourseType','CourseChapter','CourseCategory','CourseClass','CourseClassWare','UserCourseClass','InformationResource','Profile','ProfileFiled','CourseLearningPlan','UserCourseClassDetail','Precondition','CourseNote','CourseNoteReply');

    /**
     *显示列表.
     */
    public function index($page = 1)
    {
    	 $this->operator_privilege('course_view');
        $this->operation_return_url(true);
        $this->menu_path = array('root' => '/hr/', 'sub' => '/courses/');
        $this->navigations[] = array('name' => "在线学习", 'url' => '');
        $this->navigations[] = array('name' => "课程管理", 'url' => '/courses/');
        $condition = '';
        $option_type_code="-1";
        $start_date_time = '';
        $end_date_time = '';
        $status="-1";
        if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
            $condition['or']['Course.name like'] = '%' . $this->params['url']['keyword'] . '%';
            $condition['or']['Course.description like'] = '%' . $this->params['url']['keyword'] . '%';
            $this->set('keyword', $this->params['url']['keyword']);
        }
        if (isset($this->params['url']['option_type_code']) && $this->params['url']['option_type_code'] != '-1') {
            $condition['and']['Course.course_type_code'] = $this->params['url']['option_type_code'];
            $option_type_code = $this->params['url']['option_type_code'];
        }
        if (isset($this->params['url']['status']) && $this->params['url']['status'] != '-1') {
            $condition['and']['Course.status'] = $this->params['url']['status'];
            $status = $this->params['url']['status'];
        }
        if (isset($this->params['url']['start_date_time']) && $this->params['url']['start_date_time'] != '') {
            $condition['and']['Course.modified >='] = $this->params['url']['start_date_time'].' 00:00:00';
            $start_date_time = $this->params['url']['start_date_time'];
            $this->set('start_date_time', $start_date_time);
        }
        if (isset($this->params['url']['end_date_time']) && $this->params['url']['end_date_time'] != '') {
            $condition['and']['Course.modified <='] = $this->params['url']['end_date_time'].' 23:59:59';
            $end_date_time = $this->params['url']['end_date_time'];
            $this->set('end_date_time', $end_date_time);
        }
        $total = $this->Course->find('count', array('conditions' => $condition));
        //课程总数限制
        $max_course_total=intval(Configure::read('HR.max_course_total'));
        $this->set('can_to_add',$max_course_total>$total);
        
        if (isset($_GET['page']) && $_GET['page'] != '') {
            		$page = $_GET['page'];
        }
        $this->configs['show_count'] = (int)$this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'courses', 'action' => 'index', 'page' => $page, 'limit' => $rownum);
        $options = array('page' => $page, 'show' => $rownum, 'total' => $total, 'modelClass' => 'Course');
        $this->Pagination->init($condition, $parameters, $options);
        $course_list = $this->Course->find('all', array('conditions' => $condition, 'page' => $page,'limit' => $rownum,'order' => 'created desc'));
        if(!empty($course_list)){
		$course_ids=array();
		foreach($course_list as $k=>$v){
			$course_ids[]=$v['Course']['id'];
			$course_list[$k]['Course']['class_count']=$this->CourseClass->find('count', array('conditions' =>array("CourseClass.course_code"=>$v['Course']['code'])));
			$course_list[$k]['Course']['chapter_count']=$this->CourseChapter->find('count', array('conditions' =>array("CourseChapter.course_code"=>$v['Course']['code'])));
		}
		$user_course_infos=$this->UserCourseClass->find('all',array('fields'=>'UserCourseClass.course_id,count(*) as course_user_total','conditions'=>array('UserCourseClass.course_id'=>$course_ids,'UserCourseClass.user_id >'=>0),'group'=>'UserCourseClass.course_id'));
		if(!empty($user_course_infos)){
			$user_course_list=array();
			$max_course_read=intval(Configure::read('HR.max_course_read'));
			foreach($user_course_infos as $v){
				if($max_course_read<=$v[0]['course_user_total'])continue;
				$user_course_list[$v['UserCourseClass']['course_id']]=$v[0]['course_user_total'];
			}
			$this->set('user_course_list',$user_course_list);
		}
        }
        $course_type=$this->CourseType->course_type_list();
        $this->set('status', $status);
        $this->set('course_type', $course_type);
        $this->set('course_list', $course_list);
        $this->set('option_type_code', $option_type_code);
        $this->set('title_for_layout', "课程管理" . ' - ' . $this->ld['page'] . ' ' . $page . ' - ' . $this->configs['shop_name']);
    }

    /**
     *添加课程
     */
    public function add()
    {
    	 $this->operator_privilege('course_add');
        $this->menu_path = array('root' => '/hr/','sub' => '/courses/');
        $this->set('title_for_layout', $this->ld['add'].'-课程管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
        $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        $course_type=$this->CourseType->course_type_list();
        $course_category=$this->CourseCategory->find('all',array('conditions'=>array('CourseCategory.status'=>'1')));
        if ($this->RequestHandler->isPost()) {
            $this->Course->save($this->data);
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $resource_info=$this->InformationResource->information_formated('course_level',$this->backend_locale,false);
        $this->set('resource_info',$resource_info);
        $this->set('course_type', $course_type);
        $this->set('course_category', $course_category);
    }

    /**
     *编辑课程
     */
    public function view($id=0){
    	 $this->operator_privilege('course_edit');
        $this->operation_return_url(true);
        $this->menu_path = array('root' => '/hr/','sub' => '/courses/');
        $this->set('title_for_layout', $this->ld['edit'].'-课程管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
        $this->navigations[] = array('name' => $this->ld['edit'],'url' => '');
        
        if ($this->RequestHandler->isPost()) {
	            $this->Course->save($this->data);
	            $back_url = $this->operation_return_url();//获取操作返回页面地址
	            $this->redirect($back_url);
        }
        
        $course_type=$this->CourseType->course_type_list();
        $course_category=$this->CourseCategory->find('all',array('conditions'=>array('CourseCategory.status'=>'1')));
        $course_info=$this->Course->find('first',array('conditions'=>array('Course.id'=>$id)));
        if(empty($course_info))$this->redirect('index');
        
        if(!empty($course_chapter_info)){
    			$course_class_hours=$this->CourseClass->find('first',array('fields'=>"sum(CourseClass.courseware_hour) as courseware_hour",'conditions'=>array('CourseClass.course_code'=>$course_info["Course"]["code"],'CourseClass.status'=>'1')));
    			$this->set('courseware_hour',isset($course_class_hours[0]['courseware_hour'])?$course_class_hours[0]['courseware_hour']:0);
        }
        
        if(!empty($course_info['Course'])){
        		$this->loadModel('Brand');
        		$this->Brand->set_locale($this->backend_locale);
        		$BrandList=$this->Brand->find('all',array('conditions'=>array('Brand.status'=>'1','Brand.code <>'=>'','BrandI18n.name <>'=>''),'fields'=>'Brand.code,BrandI18n.name'));
        		$this->set('BrandList',$BrandList);
        		
        		$user_course_total=$this->UserCourseClass->find('count',array('conditions'=>array('UserCourseClass.course_id'=>$id,'UserCourseClass.user_id >'=>0)));
        		$max_course_read=intval(Configure::read('HR.max_course_read'));
        		$this->set('can_to_read',$max_course_read>$user_course_total);
        }
        
        $course_class_log_cond=array();
        $course_class_log_cond['UserCourseClass.course_id']=$id;
        $course_class_log_cond['UserCourseClass.status <>']=0;
        $course_class_log_cond['UserCourseClass.user_id >']=0;
        $course_class_log=$this->UserCourseClass->find('all',array('conditions'=>$course_class_log_cond,'order'=>'UserCourseClass.id desc','limit'=>'20'));
        if(!empty($course_class_log)){
        	$user_course_class_ids=array();
        	foreach($course_class_log as $v)$user_course_class_ids[]=$v['UserCourseClass']['id'];
		$couse_class_detail_infos=$this->UserCourseClassDetail->find('all',array('fields'=>'UserCourseClass.user_id,count(*) as user_class_total,max(UserCourseClassDetail.created) as  last_read,min(UserCourseClassDetail.created) as first_read','conditions'=>array('CourseClass.status'=>'1','UserCourseClass.user_id >'=>0,'UserCourseClassDetail.user_course_class_id'=>$user_course_class_ids),'group'=>'UserCourseClass.user_id'));
		$couse_class_detail_list=array();$user_read_detail=array();
		foreach($couse_class_detail_infos as $v){
			$couse_class_detail_list[$v['UserCourseClass']['user_id']]=$v[0]['user_class_total'];
			$user_read_detail[$v['UserCourseClass']['user_id']]=$v[0];
		}
		$this->set('couse_class_detail_list',$couse_class_detail_list);
		$this->set('user_read_detail',$user_read_detail);
		
		$couse_class_total=$this->CourseClass->find('count',array('conditions'=>array('CourseClass.course_code'=>$course_info["Course"]["code"],'CourseClass.status'=>'1')));
		$this->set('couse_class_total',$couse_class_total);
	}
	$course_condition=$this->Precondition->pre_condition_list('course',$course_info["Course"]["code"]);
	$resource_info=$this->InformationResource->information_formated(array('courseware_type','course_level','course_condition','course_class_condition'),$this->backend_locale,false);
	
	if(!empty($course_condition)){
        	foreach($course_condition as $kk=>$vv){
        		if($vv['Precondition']['params']=="parent_course"){
        			$parent_ids=explode(',',$vv['Precondition']['value']);
        			$parent_course_info=$this->Course->find('list',array("fields"=>"Course.name",'conditions'=>array('Course.id'=>$parent_ids)));
        			$parent_course_list=implode(",",$parent_course_info);
        			$this->set('parent_course_list',$parent_course_list);
        		}else if($vv['Precondition']['params']=="ability_level"){
        			$this->loadModel('AbilityLevel');
        			$ability_level_ids=explode(',',$vv['Precondition']['value']);
        			$ability_level_list=array();
        			$ability_level_infos=$this->AbilityLevel->find('all',array('fields'=>'AbilityLevel.id,AbilityLevel.name,Ability.name','conditions'=>array('AbilityLevel.id'=>$ability_level_ids,'AbilityLevel.status'=>1)));
        			foreach($ability_level_infos as $v)$ability_level_list[$v['AbilityLevel']['id']]=$v['Ability']['name'].$v['AbilityLevel']['name'];
        			$this->set('ability_level_list', $ability_level_list);
        		}
        	}
        }
	 $this->set('course_condition',$course_condition);
	 
        $profile_code="course_class_upload";
        $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id','Profile.code'), 'conditions' => array('Profile.code' => $profile_code, 'Profile.status' => 1)));
        $this->set('profile_info',$profile_info);
        $this->set('resource_info',$resource_info);
        $this->set('course_class_log', $course_class_log);
        $this->set('course_info', $course_info);
        $this->set('course_type', $course_type);
        $this->set('course_category', $course_category);
    }
    
    function ajax_course_detail(){
    		Configure::write('debug',1);
		$this->layout = 'ajax';
		
		$course_code=isset($_POST['course_code'])?$_POST['course_code']:'';
		$view_model=isset($_POST['view_model'])?$_POST['view_model']:'ware';
		$this->set('view_model',$view_model);
		
		if($view_model=='chapter'){
			$course_chapter_info=$this->CourseChapter->find('all',array('conditions'=>array('CourseChapter.course_code'=>$course_code,'CourseChapter.course_code <>'=>''),'order' => 'CourseChapter.orderby,CourseChapter.id','recursive' => -1));
		        if(!empty($course_chapter_info)){
	        		$course_class_list=$this->CourseClass->find('all',array('conditions'=>array('CourseClass.course_code'=>$course_code,'CourseClass.course_code <>'=>''),'order' => 'CourseClass.chapter_code,CourseClass.orderby,CourseClass.id','recursive' => -1));
	        		$course_class_ware_list=$this->CourseClassWare->find('all',array('conditions'=>array('CourseClassWare.course_code'=>$course_code,'CourseClassWare.course_code <>'=>''),'order' => 'CourseClassWare.course_class_code,CourseClassWare.orderby,CourseClassWare.id','recursive' => -1));
	        		$course_class_infos=array();$course_class_ware_infos=array();
	        		foreach($course_class_ware_list as $v)$course_class_ware_infos[$v['CourseClassWare']['course_class_code']][]=$v;
	        		foreach($course_class_list as $v){
	        			$v['CourseClassWare']=isset($course_class_ware_infos[$v['CourseClass']['code']])?$course_class_ware_infos[$v['CourseClass']['code']]:array();
	        			$course_class_infos[$v['CourseClass']['chapter_code']][]=$v;
	        		}
	        		foreach($course_chapter_info as $k=>$v)$course_chapter_info[$k]["CourseClass"]=isset($course_class_infos[$v['CourseChapter']['code']])?$course_class_infos[$v['CourseChapter']['code']]:array();
		        }
		        $this->set('course_chapter_info',$course_chapter_info);
	    	}else if($view_model=='ware'){
			$joins = array(
				array('table' => 'svhr_course_classes',
					'alias' => 'CourseClass',
					'type' => 'left',
					'conditions' => array('CourseClass.code = CourseClassWare.course_class_code and CourseClass.course_code=CourseClassWare.course_code')
				),
				array('table' => 'svhr_course_chapters',
					'alias' => 'CourseChapter',
					'type' => 'left',
					'conditions' => array('CourseChapter.code = CourseClassWare.chapter_code and CourseChapter.course_code=CourseClassWare.course_code')
				)
			);
			$fields=array(
				'CourseClassWare.*',
				'CourseClass.id','CourseClass.name',
				'CourseChapter.id','CourseChapter.name'
			);
			$conditions=array('CourseClassWare.course_code'=>$course_code,'CourseClassWare.course_code <>'=>'');
			$orderby="if(isnull(CourseChapter.orderby),0,CourseChapter.orderby),if(isnull(CourseChapter.id),0,CourseChapter.id),if(isnull(CourseChapter.orderby),0,CourseChapter.orderby),if(isnull(CourseChapter.id),0,CourseChapter.id),CourseClassWare.orderby,CourseClassWare.id";
	    		$course_class_ware_list=$this->CourseClassWare->find('all',array('fields'=>$fields,'conditions'=>$conditions,'order' => $orderby,'joins'=>$joins));
	    		$this->set('course_class_ware_list',$course_class_ware_list);
	    		
	    		$resource_info=$this->InformationResource->information_formated(array('courseware_type'),$this->backend_locale,false);
	    		$this->set('resource_info',$resource_info);
	    	}else if($view_model=='course_class_condition'){
	    		$system_modules =  $this->System->modules();
	    		$resource_info=$this->InformationResource->information_formated(array('course_class_condition'),$this->backend_locale,false,$system_modules);
	    		//pr($resource_info);
	    		$this->set('resource_info',$resource_info);
	    		
	    		$course_class_id=isset($_POST['course_class_id'])?$_POST['course_class_id']:0;
	    		
	    		$course_class_condition=$this->Precondition->find('list',array('fields'=>'params,value','conditions'=>array('object'=>'course_class','object_code'=>$course_code.$course_class_id)));
	    		$this->set('course_class_condition',$course_class_condition);
	    		
	    		if(!empty($course_class_condition)){
	    			$share_page_list=array();$share_page_data=array();
	    			foreach($course_class_condition as $k=>$v){
	    				if($k=='shared_access'||$k=='share_registration'||$k=='share_count'){
	    					$share_cond_info=trim($v)!=''?explode(chr(13).chr(10),$v):array();
	    					if(!empty($share_cond_info)){
	    						foreach($share_cond_info as $vv){
	    							$share_cond_data=explode(',',$vv);
			    					if(isset($share_cond_data[1])&&!empty($share_cond_data[1])){
			    						$share_page_list[$share_cond_data[0]][]=$share_cond_data[1];
			    					}
	    						}
	    					}
	    				}
	    			}
	    			if(!empty($share_page_list)){
	    				if(isset($share_page_list['course_class'])&&!empty($share_page_list['course_class'])){
			    			$course_class_infos=$this->CourseClass->find('list',array('conditions'=>array('CourseClass.status'=>'1','CourseClass.name <>'=>'','CourseClass.id'=>$share_page_list['course_class']),'fields'=>'CourseClass.id,CourseClass.name'));
			    			if(!empty($course_class_infos)){
			    				$share_page_data['course_class']=$course_class_infos;
			    			}
	    				}
	    				if(isset($share_page_list['page'])&&!empty($share_page_list['page'])){
	    					$this->loadModel('Page');
			    			$this->Page->set_locale($this->backend_locale);
			    			$page_infos=$this->Page->find('all',array('conditions'=>array('Page.status'=>'1','PageI18n.title <>'=>'','Page.id'=>$share_page_list['page']),'fields'=>'Page.id,PageI18n.title'));
			    			if(!empty($page_infos)){
			    				foreach($page_infos as $v){
			    					$share_page_data['page'][$v['Page']['id']]=$v['PageI18n']['title'];
			    				}
			    			}
	    				}
	    				if(isset($share_page_list['article'])&&!empty($share_page_list['article'])){
	    					$this->loadModel('Article');
			    			$this->Article->set_locale($this->backend_locale);
			    			$article_infos=$this->Article->find('all',array('conditions'=>array('Article.status'=>'1','ArticleI18n.title <>'=>'','Article.id'=>$share_page_list['article']),'fields'=>'Article.id,ArticleI18n.title'));
			    			if(!empty($article_infos)){
			    				foreach($article_infos as $v){
			    					$share_page_data['article'][$v['Article']['id']]=$v['ArticleI18n']['title'];
			    				}
			    			}
	    				}
	    				if(isset($share_page_list['topic'])&&!empty($share_page_list['topic'])){
	    					$this->loadModel('Topic');
			    			$this->Topic->set_locale($this->backend_locale);
			    			$topic_infos=$this->Topic->find('all',array('conditions'=>array('Topic.status'=>'1','TopicI18n.title <>'=>'','Topic.id'=>$share_page_list['topic']),'fields'=>'Topic.id,TopicI18n.title'));
			    			if(!empty($topic_infos)){
			    				foreach($topic_infos as $v){
			    					$share_page_data['topic'][$v['Topic']['id']]=$v['TopicI18n']['title'];
			    				}
			    			}
	    				}
	    				$this->set('share_page_data',$share_page_data);
	    			}
	    		}
	    		
	    		
	    		$conditions=array('CourseClass.course_code'=>$course_code,'CourseClass.status'=>'1','CourseChapter.status'=>'1');
	        	if(!empty($course_class_id))$conditions['CourseClass.id <>']=$course_class_id;
	    		$course_chapter_list=$this->CourseChapter->find('all',array('fields'=>'CourseChapter.id,CourseChapter.code,CourseChapter.name','conditions'=>array('CourseChapter.status'=>'1','CourseChapter.course_code'=>$course_code),'order'=>'CourseChapter.orderby,CourseChapter.id'));
	        	$course_class_infos=$this->CourseClass->find('all',array('fields'=>'CourseClass.id,CourseClass.code,CourseClass.name,CourseChapter.id,CourseChapter.code,CourseChapter.name','conditions'=>$conditions,'order'=>'CourseChapter.orderby,CourseClass.orderby,CourseClass.id'));
	        	if(!empty($course_chapter_list)){
		        	$course_class_list=array();
		        	foreach($course_class_infos as $v){
		        		$course_class_list[$v['CourseChapter']['id']][]=$v['CourseClass'];
		        	}
		        	foreach($course_chapter_list as $k=>$v)$course_chapter_list[$k]['CourseClass']=isset($course_class_list[$v['CourseChapter']['id']])?$course_class_list[$v['CourseChapter']['id']]:array();
		        	if(!empty($course_chapter_list))$course_chapter_list=array_values($course_chapter_list);
	    		}
	    		$this->set('course_chapter_list',$course_chapter_list);
	    		
	    		$this->loadModel('UserTask');
	    		$user_task_infos=$this->UserTask->find('list',array('fields'=>'id,name','conditions'=>array('UserTask.status'=>'1')));
	    		$this->set('user_task_infos',$user_task_infos);
	    		
	    	}else if($view_model=='course_share_type'){
	    		$result=array();
	    		$result['code']='0';
	    		$result['data']=array();
	    		
	    		$share_type=isset($_POST['share_type'])?$_POST['share_type']:'';
	    		if($share_type=='page'){
	    			$this->loadModel('Page');
	    			$this->Page->set_locale($this->backend_locale);
	    			$page_infos=$this->Page->find('all',array('conditions'=>array('Page.status'=>'1','PageI18n.title <>'=>''),'fields'=>'Page.id,PageI18n.title'));
	    			if(!empty($page_infos)){
	    				$result['code']='1';
	    				foreach($page_infos as $v){
	    					$result['data'][]=array(
	    						'key'=>$v['Page']['id'],
	    						'value'=>$v['PageI18n']['title']
	    					);
	    				}
	    			}
	    		}else if($share_type=='article'){
	    			$this->loadModel('Article');
	    			$this->Article->set_locale($this->backend_locale);
	    			$article_infos=$this->Article->find('all',array('conditions'=>array('Article.status'=>'1','ArticleI18n.title <>'=>''),'fields'=>'Article.id,ArticleI18n.title'));
	    			if(!empty($article_infos)){
	    				$result['code']='1';
	    				foreach($article_infos as $v){
	    					$result['data'][]=array(
	    						'key'=>$v['Article']['id'],
	    						'value'=>$v['ArticleI18n']['title']
	    					);
	    				}
	    			}
	    		}else if($share_type=='topic'){
	    			$this->loadModel('Topic');
	    			$this->Topic->set_locale($this->backend_locale);
	    			$topic_infos=$this->Topic->find('all',array('conditions'=>array('Topic.status'=>'1','TopicI18n.title <>'=>''),'fields'=>'Topic.id,TopicI18n.title'));
	    			if(!empty($topic_infos)){
	    				$result['code']='1';
	    				foreach($topic_infos as $v){
	    					$result['data'][]=array(
	    						'key'=>$v['Topic']['id'],
	    						'value'=>$v['TopicI18n']['title']
	    					);
	    				}
	    			}
	    		}
	    		
	    		
	    		
	    		
	    		die(json_encode($result));
	    	}
	}
    
    function ajax_course_class(){
		Configure::write('debug', 0);
		$this->layout = 'ajax';
	        
		$course_code=isset($_POST['course_code'])?$_POST['course_code']:'';
		$result=array();
		$result['code']='0';
	        
	        if($course_code!=''){
	        		$conditions=array('CourseClass.course_code'=>$course_code,'CourseClass.status'=>'1','CourseChapter.status'=>'1');
	        		if(isset($_POST['course_class_id'])&&!empty($_POST['course_class_id']))$conditions['CourseClass.id <>']=$_POST['course_class_id'];
	        		
	        		$course_chapter_list=$this->CourseChapter->find('all',array('fields'=>'CourseChapter.id,CourseChapter.code,CourseChapter.name','conditions'=>array('CourseChapter.status'=>'1','CourseChapter.course_code'=>$course_code),'order'=>'CourseChapter.orderby,CourseChapter.id'));
	        	$course_class_infos=$this->CourseClass->find('all',array('fields'=>'CourseClass.id,CourseClass.code,CourseClass.name,CourseChapter.id,CourseChapter.code,CourseChapter.name','conditions'=>$conditions,'order'=>'CourseChapter.orderby,CourseClass.orderby,CourseClass.id'));
	        	if(!empty($course_chapter_list)){
		        	$course_class_list=array();
		        	foreach($course_class_infos as $v){
		        		$course_class_list[$v['CourseChapter']['id']][]=$v['CourseClass'];
		        	}
		        	foreach($course_chapter_list as $k=>$v)$course_chapter_list[$k]['CourseClass']=isset($course_class_list[$v['CourseChapter']['id']])?$course_class_list[$v['CourseChapter']['id']]:array();
		        	if(!empty($course_chapter_list))$course_chapter_list=array_values($course_chapter_list);
		        	$result['code']='1';
		        	$result['data']=$course_chapter_list;
	        	}
	        }
	        die(json_encode($result));
    }
    
    function user_course_detail($page=1){
    	       $this->operator_privilege('user_learning');
		$this->operation_return_url(true);
		$this->menu_path = array('root' => '/hr/','sub' => '/courses/');
		$this->set('title_for_layout', '学习进度 - '.$this->configs['shop_name']);
		$this->navigations[] = array('name' => "在线学习",'url' => '');
		$this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
		$this->navigations[] = array('name' => "学习进度",'url' => '/courses/user_course_detail');
		
		$condition=array();
		$condition['Course.id >']=0;
		$condition['UserCourseClass.user_id >']=0;
		$condition['UserCourseClass.status <>']=0;
		if (isset($this->params['url']['course_keyword']) && $this->params['url']['course_keyword'] != '') {
			$condition['or']['Course.name like'] = '%' . $this->params['url']['course_keyword'] . '%';
			$condition['or']['Course.description like'] = '%' . $this->params['url']['course_keyword'] . '%';
			$this->set('course_keyword', $this->params['url']['course_keyword']);
		}
		if (isset($this->params['url']['start_date_time']) && $this->params['url']['start_date_time'] != '') {
			$condition['UserCourseClass.created >='] =date('Y-m-d 00:00:00',strtotime($this->params['url']['start_date_time']));
			$this->set('start_date_time', $this->params['url']['start_date_time']);
		}
		if (isset($this->params['url']['end_date_time']) && $this->params['url']['end_date_time'] != '') {
			$condition['UserCourseClass.created <='] =date('Y-m-d 23:59:59',strtotime($this->params['url']['end_date_time']));
			$this->set('end_date_time', $this->params['url']['end_date_time']);
		}
		$user_course_detail_cond=array();
		if (isset($this->params['url']['last_read_date_start']) && $this->params['url']['last_read_date_start'] != '') {
			$user_course_detail_cond['UserCourseClassDetail.modified >=']=date('Y-m-d 00:00:00',strtotime($this->params['url']['last_read_date_start']));
			$this->set('last_read_date_start', $this->params['url']['last_read_date_start']);
		}
		if (isset($this->params['url']['last_read_date_end']) && $this->params['url']['last_read_date_end'] != '') {
			$user_course_detail_cond['UserCourseClassDetail.modified <=']=date('Y-m-d 23:59:59',strtotime($this->params['url']['last_read_date_end']));
			$this->set('last_read_date_end', $this->params['url']['last_read_date_end']);
		}
		if(!empty($user_course_detail_cond)){
			$user_course_read_ids=$this->UserCourseClassDetail->find('list',array('conditions'=>$user_course_detail_cond,'fields'=>'UserCourseClassDetail.user_course_class_id'));
			if(!empty($user_course_read_ids)){
				$condition['UserCourseClass.id'] =$user_course_read_ids;
			}else{
				$condition['UserCourseClass.id'] = 0;
			}
		}
		if (isset($this->params['url']['user_keyword']) && $this->params['url']['user_keyword'] != '') {
			$this->loadModel('User');
			$user_cond=array();
			$user_cond['or']['User.name like']= '%' . $this->params['url']['user_keyword'] . '%';
			$user_cond['or']['User.email like']= '%' . $this->params['url']['user_keyword'] . '%';
			$user_cond['or']['User.mobile like']= '%' . $this->params['url']['user_keyword'] . '%';
			$user_cond['or']['User.first_name like']= '%' . $this->params['url']['user_keyword'] . '%';
			$user_list=$this->User->find('list',array('fields'=>'id','conditions'=>$user_cond));
			$condition['UserCourseClass.user_id'] =$user_list;
			$this->set('user_keyword', $this->params['url']['user_keyword']);
		}
		$total = $this->UserCourseClass->find('count', array('conditions' => $condition));
		if (isset($_GET['page']) && $_GET['page'] != '') {
			$page = $_GET['page'];
		}
		$this->configs['show_count'] = (int)$this->configs['show_count'] ? $this->configs['show_count'] : '20';
		$rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'courses', 'action' => 'user_course_detail', 'page' => $page, 'limit' => $rownum);
		$options = array('page' => $page, 'show' => $rownum, 'total' => $total, 'modelClass' => 'UserCourseClass');
		$this->Pagination->init($condition, $parameters, $options);
		$user_course_list = $this->UserCourseClass->find('all', array('conditions' => $condition, 'page' => $page,'limit' => $rownum,'order' => 'UserCourseClass.id desc'));
		if(!empty($user_course_list)){
			$couse_ids=array();$couse_codes=array();
			foreach($user_course_list as $v){
				$couse_ids[]=$v['Course']['id'];
				$couse_codes[]=$v['Course']['code'];
			}
			$couse_class_infos=$this->CourseClass->find('all',array('fields'=>'CourseClass.course_code,count(*) as class_total','conditions'=>array('CourseClass.status'=>'1','CourseClass.course_code'=>$couse_codes),'group'=>'CourseClass.course_code'));
			$couse_class_list=array();
			foreach($couse_class_infos as $v)$couse_class_list[$v['CourseClass']['course_code']]=$v[0]['class_total'];
			$this->set('couse_class_list',$couse_class_list);
			$couse_class_detail_infos=$this->UserCourseClassDetail->find('all',array('fields'=>'UserCourseClass.user_id,UserCourseClass.course_id,count(*) as user_class_total,max(UserCourseClassDetail.created) as  last_read,min(UserCourseClassDetail.created) as first_read','conditions'=>array('CourseClass.status'=>'1','UserCourseClass.user_id >'=>0,'UserCourseClass.course_id'=>$couse_ids),'group'=>'UserCourseClass.user_id,UserCourseClass.course_id'));
			$couse_class_detail_list=array();$user_read_detail=array();
			foreach($couse_class_detail_infos as $v){
				$couse_class_detail_list[$v['UserCourseClass']['user_id']][$v['UserCourseClass']['course_id']]=$v[0]['user_class_total'];
				$user_read_detail[$v['UserCourseClass']['user_id']][$v['UserCourseClass']['course_id']]=$v[0];
			}
			$this->set('couse_class_detail_list',$couse_class_detail_list);
			$this->set('user_read_detail',$user_read_detail);
			$user_complete_course=array();
			$couse_class_detail_complete_infos=$this->UserCourseClassDetail->find('all',array('fields'=>'UserCourseClass.user_id,UserCourseClass.course_id,count(*) as user_class_total','conditions'=>array('CourseClass.status'=>'1','UserCourseClass.user_id >'=>0,'UserCourseClass.course_id'=>$couse_ids,'UserCourseClassDetail.status'=>'1'),'group'=>'UserCourseClass.user_id,UserCourseClass.course_id'));
			foreach($couse_class_detail_complete_infos as $v)$user_complete_course[$v['UserCourseClass']['user_id']][$v['UserCourseClass']['course_id']]=$v[0]['user_class_total'];
			$this->set('user_complete_course',$user_complete_course);
		}
		$this->set('user_course_list',$user_course_list);
    }

    /**
     * 删除课程
     *
     *@param int $id
     */
    public function remove($id)
    {
	        Configure::write('debug', 1);
	        $this->layout = 'ajax';
	        $result['flag'] = 2;
	        $result['message'] = $this->ld['delete_member_failure'];
	        if($this->operator_privilege('course_remove',false)){
		        $course_info = $this->Course->findById($id);
		        $this->Course->deleteAll(array('id' => $id));
		        if(!empty($course_info)){
			        $this->CourseChapter->deleteAll(array('CourseChapter.course_code' => $course_info["Course"]["code"]));
			        $this->CourseClass->deleteAll(array('CourseClass.course_code' => $course_info["Course"]["code"]));
			        $this->CourseClassWare->deleteAll(array('CourseClassWare.course_code' => $course_info["Course"]["code"]));
		        }
		        $this->CourseLearningPlan->deleteAll(array('CourseLearningPlan.course_id'=>$id));
		        //操作员日志
		        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
		            	$this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id.' '.$course_info['Course']['code'], $this->admin['id']);
		        }
		        $result['flag'] = 1;
		        $result['message'] = $this->ld['delete_member_success'];
	        }
	        
	        if ($this->RequestHandler->isPost()) {
	            die(json_encode($result));
	        } else {
	            $this->redirect('/courses/');
	        }
    }
    
    public function export_course($code=""){
    		Configure::write('debug',1);
        	$this->layout="ajax";
        	$profile_code="course_class_upload";
        	$profile_info = $this->Profile->find('first', array('fields' => array('Profile.id','Profile.code'), 'conditions' => array('Profile.code' => $profile_code, 'Profile.status' => 1)));
		if(!empty($profile_info)){
			$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code','ProfilesFieldI18n.description'), 'conditions' => array( 'ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1, 'ProfilesFieldI18n.locale' => $this->backend_locale), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
			foreach($profilefiled_info as $v){
				if($v['ProfileFiled']['code']=='CourseClassWare.type'){
	            		$resource_info=$this->InformationResource->information_formated(array('courseware_type'),$this->backend_locale,false);
	            		$courseware_type=array();
	            		if(isset($resource_info['courseware_type'])&&sizeof($resource_info['courseware_type'])>0){
	            			foreach($resource_info['courseware_type'] as $kk=>$vv){
	            				$courseware_type[]=$kk.":".$vv;
	            			}
	            		}
	            		$v['ProfilesFieldI18n']['description']="课件类型".(!empty($courseware_type)?"(".implode(',',$courseware_type).")":'');
	            	}
				$fields_info[$v['ProfileFiled']['code']]=$v['ProfilesFieldI18n']['description'];
			}
		}
		if(empty($fields_info))$this->redirect('index');
		$newdatas=array();
		$tmp=array();
		foreach($fields_info as $k=>$v)$tmp[]=$v;
		$newdatas[]=$tmp;
	        if($code!=""){
	            	$course_class_info=$this->CourseChapter->find('all',array('conditions' => array('CourseChapter.course_code' => $code)));
	        }else{
	            	$course_class_info=$this->CourseChapter->find('all',array('limit' => 5));
	        }
	        if(!empty($course_class_info)){
			$chapter_code_list=array();
			foreach($course_class_info as $v)$chapter_code_list[]=$v['CourseChapter']['code'];
			$course_class_info=$this->CourseClass->find('all',array('conditions' => array('CourseClass.chapter_code' => $chapter_code_list)));
			$course_ware_info=$this->CourseClassWare->find('all',array('conditions' => array('CourseClassWare.chapter_code' => $chapter_code_list,'CourseClassWare.type <>'=>'')));
			
			$course_chapter_list=array();
			foreach($course_class_info as $v)$course_chapter_list[$v['CourseClass']['code']]=$v;
			$course_ware_list=array();
			foreach($course_ware_info as $v)$course_ware_list[$v['CourseClassWare']['course_class_code']]=$v;
			foreach($course_class_info as $k=>$v){
				$course_class=isset($course_chapter_list[$v['CourseClass']['code']])?$course_chapter_list[$v['CourseClass']['code']]:array();
				$course_class_ware=isset($course_ware_list[$v['CourseClass']['code']])?$course_ware_list[$v['CourseClass']['code']]:array();
				$export_data=array_merge($v,$course_class,$course_class_ware);
				$course_data=array();
				foreach($fields_info as $kk=>$vv){
					$field_codes=explode('.',$kk);
					$field_model=isset($field_codes[0])?$field_codes[0]:'';
					$field_name=isset($field_codes[1])?$field_codes[1]:'';
					$course_data[]=isset($export_data[$field_model][$field_name])?$export_data[$field_model][$field_name]:'';
				}
				$newdatas[]=$course_data;
			}
		}
		$nameexl = 'course'.date('Ymd').'.xls';
		$this->Phpexcel->output($nameexl, $newdatas);
		exit();
    }
    
    public function download_csv_example($code=""){
        Configure::write('debug',1);
        $this->layout="ajax";
        $profile_code="course_class_upload";
        $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id','Profile.code'), 'conditions' => array('Profile.code' => $profile_code, 'Profile.status' => 1)));
        $fields_info=array();
        if(!empty($profile_info)){
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code','ProfilesFieldI18n.description'), 'conditions' => array( 'ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1, 'ProfilesFieldI18n.locale' => $this->backend_locale), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
            foreach($profilefiled_info as $v){
            	if($v['ProfileFiled']['code']=='CourseClassWare.type'){
            		$resource_info=$this->InformationResource->information_formated(array('courseware_type'),$this->backend_locale,false);
            		$courseware_type=array();
            		if(isset($resource_info['courseware_type'])&&sizeof($resource_info['courseware_type'])>0){
            			foreach($resource_info['courseware_type'] as $kk=>$vv){
            				$courseware_type[]=$kk.":".$vv;
            			}
            		}
            		$v['ProfilesFieldI18n']['description']="课件类型".(!empty($courseware_type)?"(".implode(',',$courseware_type).")":'');
            	}
                	$fields_info[$v['ProfileFiled']['code']]=$v['ProfilesFieldI18n']['description'];
            }
        }
        if(empty($fields_info))$this->redirect('upload');
        $newdatas=array();
        $tmp=array();
        foreach($fields_info as $k=>$v){
            	$tmp[]=$v;
        }
        $newdatas[]=$tmp;
        if($code!=""){
            	$course_class_info=$this->CourseChapter->find('all',array('conditions' => array('CourseChapter.course_code' => $code)));
        }else{
            	$course_class_info=$this->CourseChapter->find('all',array('limit' => 5));
        }
        if(!empty($course_class_info)){
		$chapter_code_list=array();
		foreach($course_class_info as $v)$chapter_code_list[]=$v['CourseChapter']['code'];
		$course_class_info=$this->CourseClass->find('all',array('conditions' => array('CourseClass.chapter_code' => $chapter_code_list)));
		$course_ware_info=$this->CourseClassWare->find('all',array('conditions' => array('CourseClassWare.chapter_code' => $chapter_code_list,'CourseClassWare.type <>'=>'')));
		
		$course_chapter_list=array();
		foreach($course_class_info as $v)$course_chapter_list[$v['CourseClass']['code']]=$v;
		$course_ware_list=array();
		foreach($course_ware_info as $v)$course_ware_list[$v['CourseClassWare']['course_class_code']]=$v;
		foreach($course_class_info as $k=>$v){
			$course_class=isset($course_chapter_list[$v['CourseClass']['code']])?$course_chapter_list[$v['CourseClass']['code']]:array();
			$course_class_ware=isset($course_ware_list[$v['CourseClass']['code']])?$course_ware_list[$v['CourseClass']['code']]:array();
			$export_data=array_merge($v,$course_class,$course_class_ware);
			$course_data=array();
			foreach($fields_info as $kk=>$vv){
				$field_codes=explode('.',$kk);
				$field_model=isset($field_codes[0])?$field_codes[0]:'';
				$field_name=isset($field_codes[1])?$field_codes[1]:'';
				$course_data[]=isset($export_data[$field_model][$field_name])?$export_data[$field_model][$field_name]:'';
			}
			$newdatas[]=$course_data;
		}
	}
        //定义文件名称
        $nameexl = 'course'.date('Ymd').'.csv';
        $this->Phpcsv->output($nameexl, $newdatas);
        die();
    }

    function preview($code){
    	 if(!($this->operator_privilege('course_add',false)||$this->operator_privilege('course_edit',false))){
    	 		$this->redirect('index');
    	 }
        $this->menu_path = array('root' => '/hr/','sub' => '/courses/');
        $this->set('title_for_layout', $this->ld['edit'].'-课程管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
        $this->navigations[] = array('name' => "上传",'url' => '');
        $CourseInfo=$this->Course->find('first',array('conditions'=>array('code'=>$code)));
        if(empty($CourseInfo))$this->redirect('index');
        $this->set('code',$code);
        $profile_code="course_class_upload";
        $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id','Profile.code'), 'conditions' => array('Profile.code' => $profile_code, 'Profile.status' => 1)));
        $fields_info=array();
        $fields_desc_info=array();
        if(!empty($profile_info)){
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array( 'ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1, 'ProfilesFieldI18n.locale' => $this->backend_locale), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
            foreach($profilefiled_info as $v){
            	if($v['ProfileFiled']['code']=='CourseClassWare.type'){
            		$resource_info=$this->InformationResource->information_formated(array('courseware_type'),$this->backend_locale,false);
            		$courseware_type=array();
            		if(isset($resource_info['courseware_type'])&&sizeof($resource_info['courseware_type'])>0){
            			foreach($resource_info['courseware_type'] as $kk=>$vv){
            				$courseware_type[]=$kk.":".$vv;
            			}
            		}
            		$v['ProfilesFieldI18n']['description']="课件类型".(!empty($courseware_type)?"(".implode(',',$courseware_type).")":'');
            	}
                	$fields_info[$v['ProfileFiled']['code']]=$v['ProfilesFieldI18n']['description'];
                	$fields_desc_info[$v['ProfilesFieldI18n']['description']]=$v['ProfileFiled']['code'];
            }
        }
        if(empty($profile_info))$this->redirect('upload/'.$code);
        $preview_data=array();
        if (!empty($_FILES['course_class'])) {
            if ($_FILES['course_class']['error'] > 0) {
            	Configure::write('debug',0);
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['upload'].$this->ld['failed']."');window.location.href='/admin/courses/view/".$CourseInfo['Course']['id']."';</script>";
                die();
            }else{
                $handle = @fopen($_FILES['course_class']['tmp_name'], 'r');
                $fields_array=array();
                $fields_desc=array();
                foreach($fields_info as $k=>$v){
                    $fields_array[]=$k;
                    $fields_desc[]=$v;
                }
                $preview_code=array();
                $csv_export_code = 'gb2312';
                $i = 0;
                while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                    if ($i == 0) {
                        foreach ($row as $k => $v) {
                            $preview_code[]=iconv('GB2312', 'UTF-8', $v);
                        }
                        $check_row = $row[0];
                        $row_count = sizeof($row);
                        $check_row = iconv('GB2312', 'UTF-8', $check_row);
                        $num_count = sizeof($fields_desc);
                        ++$i;
                    }
                    if($row_count!=$num_count){
				Configure::write('debug',0);
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('文件格式错误');window.location.href='/admin/courses/view/".$CourseInfo['Course']['id']."';</script>";
				die();
                    }
                    $temp = array();
                    foreach($row as $kk=>$vv){
                        $data_key_code=isset($fields_desc_info[$preview_code[$kk]])?$fields_desc_info[$preview_code[$kk]]:'';
                        $temp[$data_key_code] = $vv=='' ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $vv);
                    }
                    $preview_data[] = $temp;
                }
                fclose($handle);
                $this->set('fields_array', $fields_array);
                $this->set('fields_desc', $fields_desc);
                $this->set('preview_data', $preview_data);
            }
        }
        if(empty($preview_data))$this->redirect('/');
    }
    
    function ajax_batch_remove_chapter(){
		Configure::write('debug',1);
        	$this->layout="ajax";
        	
        	$result=array();
        	$result['code']='0';
        	$result['message']=$this->ld['delete_failure'];
        	if ($this->RequestHandler->isPost()&&$this->operator_privilege('course_edit')){
        		$course_id=isset($_POST['course_id'])?$_POST['course_id']:0;
        		$course_chapter_ids=isset($_POST['chapter_ids'])?$_POST['chapter_ids']:array();
        		$course_class_ids=isset($_POST['class_ids'])?$_POST['class_ids']:array();
        		$course_ware_ids=isset($_POST['ware_ids'])?$_POST['ware_ids']:array();
        		
        		$course_data=$this->Course->findById($course_id);
        		if(!empty($course_data)){
        			$course_code=$course_data['Course']['code'];
        			if(!empty($course_ware_ids)){
        				$this->CourseClassWare->deleteAll(array('CourseClassWare.course_code'=>$course_code,'CourseClassWare.id'=>$course_ware_ids));
        			}
        			if(!empty($course_class_ids)){
        				$course_class_list=$this->CourseClass->find('list',array('fields'=>'id,code','conditions'=>array('course_code'=>$course_code,'id'=>$course_class_ids)));
        				if(!empty($course_class_list)){
        					$this->CourseClassWare->deleteAll(array('CourseClassWare.course_code'=>$course_code,'CourseClassWare.course_class_code'=>$course_class_list));
        					$this->CourseClass->deleteAll(array('CourseClass.course_code'=>$course_code,'CourseClass.id'=>$course_class_ids));
        				}
        			}
        			if(!empty($course_chapter_ids)){
        				$course_chapter_list=$this->CourseChapter->find('list',array('fields'=>'id,code','conditions'=>array('course_code'=>$course_code,'id'=>$course_chapter_ids)));
        				if(!empty($course_chapter_list)){
        					$this->CourseClassWare->deleteAll(array('CourseClassWare.course_code'=>$course_code,'CourseClassWare.chapter_code'=>$course_chapter_list));
        					$this->CourseClass->deleteAll(array('CourseClass.course_code'=>$course_code,'CourseClass.chapter_code'=>$course_chapter_list));
        					$this->CourseChapter->deleteAll(array('CourseChapter.course_code'=>$course_code,'CourseChapter.id'=>$course_chapter_ids));
        				}
        			}
        			$result['code']='1';
        			$result['message']=$this->ld['deleted_success'];
        		}
        	}
        	die(json_encode($result));
    }

    public function batch_upload($code=''){
        Configure::write('debug',1);
        $this->layout="ajax";
        if ($this->RequestHandler->isPost()&&($this->operator_privilege('course_add',false)||$this->operator_privilege('course_edit',false))) {
		$CourseInfo=$this->Course->find('first',array('conditions'=>array('code'=>$code)));
		$back_url="/admin/courses/index";
		if(!empty($CourseInfo))$back_url="/admin/courses/view/".$CourseInfo['Course']['id'];
            $upload_num=0;
            $checkboxs=isset($_POST['checkbox'])?$_POST['checkbox']:array();
            if(!empty($this->data)){
                foreach($this->data as $k=>$course_info){
                    if(!in_array($k,$checkboxs))continue;
                    $chapter_data=$course_info['CourseChapter'];
                    $chapter_code=$chapter_data['code'];
                    if(trim($chapter_code)=='')continue;
                    $chapterinfo=$this->CourseChapter->find('first',array('conditions'=>array('CourseChapter.code'=>$chapter_code)));
                    $chapter_data['id']=isset($chapterinfo['CourseChapter']['id'])?$chapterinfo['CourseChapter']['id']:0;
                    $chapter_data['course_code']=$code;
                    $this->CourseChapter->save($chapter_data);
                    $class_data=$course_info['CourseClass'];
                    $class_code=$class_data['code'];
                    if(trim($class_code)=='')continue;
                    $classinfo=$this->CourseClass->find('first',array('conditions'=>array('CourseClass.code'=>$class_code)));
                    $class_data['id']=isset($classinfo['CourseClass']['id'])?$classinfo['CourseClass']['id']:0;
                    $class_data['chapter_code']=$chapter_code;
                    $class_data['course_code']=$code;
                    $this->CourseClass->save($class_data);
                    if(isset($course_info['CourseClassWare'])){
                    	$class_ware_data=$course_info['CourseClassWare'];
                    	$ware_cond=array();
                    	$ware_cond['course_code']=$code;
                    	$ware_cond['chapter_code']=$chapter_code;
                    	$ware_cond['course_class_code']=$class_code;
                    	if(isset($class_ware_data['type'])&&$class_ware_data['type']!='')$ware_cond['CourseClassWare.type']=$class_ware_data['type'];
                    	$class_ware_info=$this->CourseClassWare->find('first',array('conditions'=>$ware_cond,'recursive' => -1));
                 		$class_ware_data['id']=isset($class_ware_info['CourseClassWare']['id'])?$class_ware_info['CourseClassWare']['id']:0;
                    	$class_ware_data['course_code']=$code;
                    	$class_ware_data['chapter_code']=$chapter_code;
                    	$class_ware_data['course_class_code']=$class_code;
                 		$this->CourseClassWare->save($class_ware_data);
                    }
                    $upload_num++;
                }
            }
            if($upload_num==0){
                	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['upload'].'failed'."');window.location.href='{$back_url}';</script>";
                	die();
            }else{
	                $upload_message="(".($upload_num).'/'.(sizeof($checkboxs)).")";
	                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['upload'].'success'.$upload_message."');window.location.href='{$back_url}';</script>";
	                die();
            }
        }else{
            $this->redirect('/');
        }
    }

    function learning_plan($course_id=0){
        Configure::write('debug',1);
        $this->layout="ajax";

        $course_data=$this->Course->find('first',array('conditions'=>array('id'=>$course_id)));
        if ($this->RequestHandler->isPost()&&($this->operator_privilege('course_add',false)||$this->operator_privilege('course_edit',false))){
            $result=array();
            $result['code']='0';
            $result['message']=$this->ld['add_failure'];

            $course_class_id=isset($_POST['course_class_id'])?$_POST['course_class_id']:0;
            $learning_plan_day=isset($_POST['learning_plan_day'])&&intval($_POST['learning_plan_day'])>0?intval($_POST['learning_plan_day']):1;
            if(!empty($course_data)){
                $course_class_detail=$this->CourseClass->find('list',array('fields'=>'CourseClass.id','conditions'=>array('CourseClass.id'=>$course_class_id,'CourseClass.course_code'=>$course_data['Course']['code'])));
                if(!empty($course_class_detail)){
                    $course_learning_plan=$this->CourseLearningPlan->find('list',array('fields'=>'CourseLearningPlan.course_class_id,CourseLearningPlan.id','conditions'=>array('course_id'=>$course_id,'course_class_id'=>$course_class_id)));
                    foreach($course_class_detail as $v){
                        $course_learning_plan_data=array(
	                            'id'=>isset($course_learning_plan[$v])?$course_learning_plan[$v]:0,
	                            'course_id'=>$course_id,
	                            'course_class_id'=>$v,
	                            'week'=>$learning_plan_day,
	                            'status'=>'1'
                        );
                        $this->CourseLearningPlan->save($course_learning_plan_data);
                    }
                    $result['code']='1';
                    $result['message']=$this->ld['add_successful'];
                }
            }
            die(json_encode($result));
        }
        if(empty($course_data))$this->redirect('index');
        $this->set('course_data',$course_data);

        $course_class_tree=$this->CourseClass->course_chapter_tree($course_data['Course']['code']);
        $this->set('course_class_tree',$course_class_tree);

        $learning_plan_class_ids=array();$course_learning_plan_list=array();
        $course_learning_plan_infos=$this->CourseLearningPlan->find('all',array('conditions'=>array('course_id'=>$course_id),'order'=>'CourseLearningPlan.week,CourseLearningPlan.orderby,CourseChapter.orderby,CourseChapter.id,CourseClass.orderby,CourseClass.id'));
        if(!empty($course_learning_plan_infos)){
	        foreach($course_learning_plan_infos as $v){
	        	$learning_plan_class_ids[]=$v['CourseLearningPlan']['course_class_id'];
	        	$course_learning_plan_list[$v['CourseLearningPlan']['week']][]=$v;
	        }
        }
        $this->set('course_learning_plan_list',$course_learning_plan_list);
        $this->set('learning_plan_class_ids',$learning_plan_class_ids);
    }

    function modify_learning_plan($course_id=0){
	        Configure::write('debug',1);
	        $this->layout="ajax";
	        
	        $result=array();
	        $result['code']='0';
	        $result['message']=$this->ld['modify_failed'];
		 if($this->operator_privilege('course_add',false)||$this->operator_privilege('course_edit',false)){
		        $learning_plan_id=isset($_POST['learning_plan_id'])?$_POST['learning_plan_id']:0;
		        $learning_field=isset($_POST['learning_field'])?$_POST['learning_field']:'';
		        $learning_field_value=isset($_POST['learning_field_value'])?$_POST['learning_field_value']:'';
		        $course_learning_plan=$this->CourseLearningPlan->find('first',array('conditions'=>array('CourseLearningPlan.course_id'=>$course_id,'CourseLearningPlan.id'=>$learning_plan_id)));
		        if(!empty($course_learning_plan)&&isset($course_learning_plan['CourseLearningPlan'][$learning_field])){
		            $course_learning_plan_data=array(
		                'id'=>$learning_plan_id,
		                "{$learning_field}"=>$learning_field_value
		            );
		            $this->CourseLearningPlan->save($course_learning_plan_data);

		            $result['code']='1';
		            $result['message']=$this->ld['modified_successfully'];
		        }
	     	 }
	        die(json_encode($result));
    }

    function remove_learning_plan($course_id=0){
	        Configure::write('debug',1);
	        $this->layout="ajax";

		$result=array();
		$result['code']='0';
		$result['message']=$this->ld['delete_failure'];
		if($this->operator_privilege('course_add',false)||$this->operator_privilege('course_edit',false)){
			$learning_plan_id=isset($_POST['learning_plan_id'])?$_POST['learning_plan_id']:0;
			if(!empty($learning_plan_id)){
				$this->CourseLearningPlan->hasOne=array();
				$this->CourseLearningPlan->deleteAll(array('CourseLearningPlan.id'=>$learning_plan_id,'CourseLearningPlan.course_id'=>$course_id));

				$result['code']='1';
				$result['message']=$this->ld['deleted_success'];
			}
		}
        	die(json_encode($result));
    }
    
    function course_log_detail($user_course_class_id=0){
    		Configure::write('debug',1);
        	$this->layout="ajax";
        	
        	$result=array();
        	$result['code']='0';
    		$user_course_class_info=$this->UserCourseClass->findById($user_course_class_id);
    		if(!empty($user_course_class_info)){
    			$user_course_class_details=$this->UserCourseClassDetail->find('all',array('conditions'=>array('UserCourseClassDetail.user_course_class_id'=>$user_course_class_id,'CourseClass.status'=>'1'),'order'=>'UserCourseClassDetail.id'));
    			$result['code']='1';
    			$result['user_course_class']=$user_course_class_details;
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
    
	/**
     * 检查code
     *
     */
    public function check_code()
    {
    	Configure::write('debug', 1);
        $result['code'] = 0;
        $result['msg'] = '';
        if ($this->RequestHandler->isPost()) {
            $code = isset($_POST['code']) ? $_POST['code'] : '';
            $course_count = $this->Course->find('count', array('conditions' => array('Course.code' => $code, 'Course.status' => "1")));
            if ($course_count == 0) {
                $result['code'] = 1;
            } else {
                $result['msg'] = "code已存在";
            }
            die(json_encode($result));
        } else {
            $this->redirect('/courses');
        }
    }
    
    function course_assignment($page=1){
    		$this->operator_privilege('user_learning');
    		$this->operation_return_url(true);
		$this->menu_path = array('root' => '/hr/','sub' => '/courses/');
		$this->set('title_for_layout', '课程作业 - '.$this->configs['shop_name']);
		$this->navigations[] = array('name' => "在线学习",'url' => '');
		$this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
		$this->navigations[] = array('name' => "学习进度",'url' => '/courses/user_course_detail');
		$this->navigations[] = array('name' => "课程作业",'url' => '/courses/course_assignment');
		
		$this->loadModel('CourseAssignment');
		$this->loadModel('User');
		
		$conditions=array();
		$conditions['Course.status']='1';
		$conditions['CourseChapter.status']='1';
		$conditions['CourseClass.status']='1';
		$conditions['CourseClassWare.status']='1';
		if (isset($this->params['url']['course_keyword']) && $this->params['url']['course_keyword'] != '') {
			$condition['or']['Course.name like'] = '%' . $this->params['url']['course_keyword'] . '%';
			$condition['or']['CourseChapter.name like'] = '%' . $this->params['url']['course_keyword'] . '%';
			$condition['or']['CourseClass.name like'] = '%' . $this->params['url']['course_keyword'] . '%';
			$this->set('course_keyword', $this->params['url']['course_keyword']);
		}
		if (isset($this->params['url']['user_keyword']) && $this->params['url']['user_keyword'] != '') {
			$user_cond=array();
			$user_cond['User.status']='1';
			$user_cond['or']['User.name like'] = '%' . $this->params['url']['user_keyword'] . '%';
			$user_cond['or']['User.mobile like'] = '%' . $this->params['url']['user_keyword'] . '%';
			$user_cond['or']['User.first_name like'] = '%' . $this->params['url']['user_keyword'] . '%';
			$user_cond['or']['User.email like'] = '%' . $this->params['url']['user_keyword'] . '%';
			$user_kewword_list=$this->User->find('list',array('conditions'=>$user_cond,'fields'=>'User.id'));
			if(!empty($user_kewword_list)){
				$conditions['CourseAssignment.user_id']=$user_kewword_list;
			}else{
				$conditions['CourseAssignment.user_id']=0;
			}
			$this->set('user_keyword', $this->params['url']['user_keyword']);
		}
		if (isset($this->params['url']['note_content']) && $this->params['url']['note_content'] != '') {
			$conditions['CourseAssignment.content like']='%' . $this->params['url']['note_content'] . '%';
			$this->set('note_content', $this->params['url']['note_content']);
		}
		if (isset($this->params['url']['start_date_time']) && $this->params['url']['start_date_time'] != '') {
			$conditions['CourseAssignment.modified >='] = date('Y-m-d 00:00:00',strtotime($this->params['url']['start_date_time']));
			$this->set('start_date_time', $this->params['url']['start_date_time']);
		}
		if (isset($this->params['url']['end_date_time']) && $this->params['url']['end_date_time'] != '') {
			$conditions['CourseAssignment.modified <='] = date('Y-m-d 23:59:59',strtotime($this->params['url']['end_date_time']));
			$this->set('end_date_time', $this->params['url']['end_date_time']);
		}
		if (isset($this->params['url']['page']) && intval($this->params['url']['page'])>0) {
			$page=$this->params['url']['page'];
		}
		$total=$this->CourseAssignment->find('count',array('conditions'=>$conditions));
		$this->configs['show_count'] = (int)$this->configs['show_count'] ? $this->configs['show_count'] : '20';
		$rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'courses', 'action' => 'course_assignment', 'page' => $page, 'limit' => $rownum);
		$options = array('page' => $page, 'show' => $rownum, 'total' => $total, 'modelClass' => 'CourseAssignment');
		$this->Pagination->init($conditions, $parameters, $options);
		$fields=array(
			'CourseAssignment.*',
			'CourseClassWare.name',
			'CourseClass.name',
			'CourseChapter.name',
			'Course.name'
		);
		$course_assignment_list=$this->CourseAssignment->find('all',array('fields'=>$fields,'conditions'=>$conditions,'order'=>'CourseAssignment.created desc,CourseAssignment.id','page' => $page, 'limit' => $rownum));
		$this->set('course_assignment_list',$course_assignment_list);
		if(!empty($course_assignment_list)){
			$course_assignment_user_ids=array();
			foreach($course_assignment_list as $v)$course_assignment_user_ids[]=$v['CourseAssignment']['user_id'];
			$course_assignment_user_infos=$this->User->find('all',array('conditions'=>array('User.id'=>$course_assignment_user_ids,'User.status'=>'1'),'fields'=>'User.id,User.first_name,User.name'));
			$course_assignment_user_list=array();
			if(!empty($course_assignment_user_infos)){
				foreach($course_assignment_user_infos as $v)$course_assignment_user_list[$v['User']['id']]=$v['User'];
			}
			$this->set('course_assignment_user_list',$course_assignment_user_list);
		}
    }
    
    function course_assignment_detail($course_assignment_id=0){
    		$this->operator_privilege('user_learning');
    		$this->operation_return_url(true);
		$this->menu_path = array('root' => '/hr/','sub' => '/courses/');
		$this->set('title_for_layout', '课程笔记 - '.$this->configs['shop_name']);
		$this->navigations[] = array('name' => "在线学习",'url' => '');
		$this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
		$this->navigations[] = array('name' => "学习进度",'url' => '/courses/user_course_detail');
		$this->navigations[] = array('name' => "课程作业",'url' => '/courses/course_assignment');
		$this->navigations[] = array('name' => "课程作业详情",'url' => '');
		
		$this->loadModel('CourseAssignment');
		$this->loadModel('User');
		$this->loadModel('CourseAssignmentScore');
		
		$conditions=array();
		$conditions['CourseAssignment.id']=$course_assignment_id;
		$conditions['Course.status']='1';
		$conditions['CourseChapter.status']='1';
		$conditions['CourseClass.status']='1';
		$conditions['CourseClassWare.status']='1';
		$fields=array(
			'CourseAssignment.*',
			'CourseClassWare.name',
			'CourseClass.name',
			'CourseChapter.name',
			'Course.name'
		);
		$course_assignment_info=$this->CourseAssignment->find('first',array('fields'=>$fields,'conditions'=>$conditions));
		if($this->RequestHandler->isPost()){
			Configure::write('debug',1);
			$this->layout = 'ajax';
			$result=array();
			$result['code'] = 0;
			$result['message'] = $this->ld['unknown_error'];
			if(isset($this->data['CourseAssignmentScore'])&&!empty($this->data['CourseAssignmentScore'])&&!empty($course_assignment_info)){
				$score_cond=array();
				$score_cond['CourseAssignmentScore.course_assignment_id']=$course_assignment_id;
				$score_cond['CourseAssignmentScore.reply_from']='1';
				$score_cond['CourseAssignmentScore.reply_from_id']=$this->admin['id'];
				$CourseAssignmentScoreInfo=$this->CourseAssignmentScore->find('first',array('conditions'=>$score_cond));
				$this->data['CourseAssignmentScore']['id']=isset($CourseAssignmentScoreInfo['CourseAssignmentScore'])?$CourseAssignmentScoreInfo['CourseAssignmentScore']['id']:0;
				$this->data['CourseAssignmentScore']['reply_from']='1';
				$this->data['CourseAssignmentScore']['reply_from_id']=$this->admin['id'];
				$this->CourseAssignmentScore->save($this->data['CourseAssignmentScore']);
				$result['code'] = 1;
				$result['message'] = $this->ld['reply_success'];
			}
			die(json_encode($result));
		}
		if(empty($course_assignment_info))$this->redirect('/courses/course_assignment');
		
		$this->set('course_assignment_info',$course_assignment_info);
		$course_assignment_user_info=$this->User->find('first',array('conditions'=>array('User.id'=>$course_assignment_info['CourseAssignment']['user_id'],'User.status'=>'1'),'fields'=>'User.id,User.first_name,User.name'));
		$this->set('course_assignment_user_info',$course_assignment_user_info);
		
		$score_cond=array();
		$score_cond['CourseAssignmentScore.course_assignment_id']=$course_assignment_id;
		$CourseAssignmentScoreList=$this->CourseAssignmentScore->find('all',array('conditions'=>$score_cond,'order'=>'CourseAssignmentScore.modified desc'));
		$this->set('CourseAssignmentScoreList',$CourseAssignmentScoreList);
		if(!empty($CourseAssignmentScoreList)){
			$reply_user_ids=array();$reply_operator_ids=array();
			foreach($CourseAssignmentScoreList as $v){
				if($v['CourseAssignmentScore']['reply_from']=='0')$reply_user_ids[]=$v['CourseAssignmentScore']['reply_from_id'];
				if($v['CourseAssignmentScore']['reply_from']=='1')$reply_operator_ids[]=$v['CourseAssignmentScore']['reply_from_id'];
			}
			if(!empty($reply_user_ids)){
				$reply_user_infos=$this->User->find('all',array('fields'=>'User.id,User.name,User.first_name','conditions'=>array('User.id'=>$reply_user_ids)));
				if(!empty($reply_user_infos)){
					$reply_user_list=array();
					foreach($reply_user_infos as $v){
						$reply_user_list[$v['User']['id']]=$v['User'];
					}
					$this->set('reply_user_list',$reply_user_list);
				}
			}
			if(!empty($reply_operator_ids)){
				$reply_operator_list=$this->Operator->find('list',array('fields'=>'Operator.id,Operator.name','conditions'=>array('Operator.id'=>$reply_operator_ids)));
				$this->set('reply_operator_list',$reply_operator_list);
			}
		}
    }
    
    function course_note($page=1){
    	       $this->operator_privilege('user_learning');
    		$this->operation_return_url(true);
		$this->menu_path = array('root' => '/hr/','sub' => '/courses/');
		$this->set('title_for_layout', '课程笔记 - '.$this->configs['shop_name']);
		$this->navigations[] = array('name' => "在线学习",'url' => '');
		$this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
		$this->navigations[] = array('name' => "学习进度",'url' => '/courses/user_course_detail');
		$this->navigations[] = array('name' => "课程笔记",'url' => '/courses/course_note');
		
		$this->loadModel('User');
		
		$conditions=array();
		$conditions['Course.status']='1';
		$conditions['CourseChapter.status']='1';
		$conditions['CourseClass.status']='1';
		if (isset($this->params['url']['course_keyword']) && $this->params['url']['course_keyword'] != '') {
			$conditions['or']['Course.name like'] = '%' . $this->params['url']['course_keyword'] . '%';
			$conditions['or']['CourseChapter.name like'] = '%' . $this->params['url']['course_keyword'] . '%';
			$conditions['or']['CourseClass.name like'] = '%' . $this->params['url']['course_keyword'] . '%';
			$this->set('course_keyword', $this->params['url']['course_keyword']);
		}
		if (isset($this->params['url']['user_keyword']) && $this->params['url']['user_keyword'] != '') {
			$user_cond=array();
			$user_cond['User.status']='1';
			$user_cond['or']['User.name like'] = '%' . $this->params['url']['user_keyword'] . '%';
			$user_cond['or']['User.mobile like'] = '%' . $this->params['url']['user_keyword'] . '%';
			$user_cond['or']['User.first_name like'] = '%' . $this->params['url']['user_keyword'] . '%';
			$user_cond['or']['User.email like'] = '%' . $this->params['url']['user_keyword'] . '%';
			$user_kewword_list=$this->User->find('list',array('conditions'=>$user_cond,'fields'=>'User.id'));
			if(!empty($user_kewword_list)){
				$conditions['CourseNote.user_id']=$user_kewword_list;
			}else{
				$conditions['CourseNote.user_id']=0;
			}
			$this->set('user_keyword', $this->params['url']['user_keyword']);
		}
		if (isset($this->params['url']['note_content']) && $this->params['url']['note_content'] != '') {
			$conditions['CourseNote.note like']='%' . $this->params['url']['note_content'] . '%';
			$this->set('note_content', $this->params['url']['note_content']);
		}
		if (isset($this->params['url']['start_date_time']) && $this->params['url']['start_date_time'] != '') {
			$conditions['CourseNote.modified >='] = date('Y-m-d 00:00:00',strtotime($this->params['url']['start_date_time']));
			$this->set('start_date_time', $this->params['url']['start_date_time']);
		}
		if (isset($this->params['url']['end_date_time']) && $this->params['url']['end_date_time'] != '') {
			$conditions['CourseNote.modified <='] = date('Y-m-d 23:59:59',strtotime($this->params['url']['end_date_time']));
			$this->set('end_date_time', $this->params['url']['end_date_time']);
		}
		if (isset($this->params['url']['page']) && intval($this->params['url']['page'])>0) {
			$page=$this->params['url']['page'];
		}
		$total=$this->CourseNote->find('count',array('conditions'=>$conditions));
		$this->configs['show_count'] = (int)$this->configs['show_count'] ? $this->configs['show_count'] : '20';
		$rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'courses', 'action' => 'course_note', 'page' => $page, 'limit' => $rownum);
		$options = array('page' => $page, 'show' => $rownum, 'total' => $total, 'modelClass' => 'CourseNote');
		$this->Pagination->init($conditions, $parameters, $options);
		$fields=array(
			'CourseNote.*',
			'CourseClass.name',
			'CourseChapter.name',
			'Course.name'
		);
		$course_note_list=$this->CourseNote->find('all',array('fields'=>$fields,'conditions'=>$conditions,'order'=>'CourseNote.created desc,CourseNote.id','page' => $page, 'limit' => $rownum));
		$this->set('course_note_list',$course_note_list);
		if(!empty($course_note_list)){
			$course_note_user_ids=array();$course_note_ids=array();
			foreach($course_note_list as $v){
				$course_note_user_ids[]=$v['CourseNote']['user_id'];
				$course_note_ids[]=$v['CourseNote']['id'];
			}
			$course_note_reply_infos=$this->CourseNoteReply->find('all',array('fields'=>'CourseNoteReply.course_note_id,count(*) as note_reply_count','conditions'=>array('CourseNoteReply.course_note_id'=>$course_note_ids),'group'=>'CourseNoteReply.course_note_id'));
			if(!empty($course_note_reply_infos)){
				$course_note_reply_list=array();
				foreach($course_note_reply_infos as $v)$course_note_reply_list[$v['CourseNoteReply']['course_note_id']]=$v[0]['note_reply_count'];
				$this->set('course_note_reply_list',$course_note_reply_list);
			}
			$course_note_user_infos=$this->User->find('all',array('conditions'=>array('User.id'=>$course_note_user_ids,'User.status'=>'1'),'fields'=>'User.id,User.first_name,User.name'));
			$course_note_user_list=array();
			if(!empty($course_note_user_infos)){
				foreach($course_note_user_infos as $v)$course_note_user_list[$v['User']['id']]=$v['User'];
			}
			$this->set('course_note_user_list',$course_note_user_list);
		}
    }
    
    function course_note_detail($course_note_id=0){
    	       $this->operator_privilege('user_learning');
    		$this->operation_return_url(true);
		$this->menu_path = array('root' => '/hr/','sub' => '/courses/');
		$this->set('title_for_layout', '课程笔记 - '.$this->configs['shop_name']);
		$this->navigations[] = array('name' => "在线学习",'url' => '');
		$this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
		$this->navigations[] = array('name' => "学习进度",'url' => '/courses/user_course_detail');
		$this->navigations[] = array('name' => "课程笔记",'url' => '/courses/course_note');
		$this->navigations[] = array('name' => "课程笔记详情",'url' => '');
		
		$course_note_info=$this->CourseNote->findById($course_note_id);
		if(empty($course_note_info)){
			$this->redirect('/courses/course_note');
		}else{
			$this->set('course_note_info',$course_note_info);
			
			$this->loadModel('User');
			$course_note_user_info=$this->User->find('first',array('conditions'=>array('User.id'=>$course_note_info['CourseNote']['user_id'],'User.status'=>'1'),'fields'=>'User.id,User.first_name,User.name'));
			$this->set('course_note_user_info',$course_note_user_info);
			
			$course_note_reply_list=$this->CourseNoteReply->find('all',array('conditions'=>array('CourseNoteReply.course_note_id'=>$course_note_info['CourseNote']['id']),'order'=>'CourseNoteReply.created desc'));
			$this->set('course_note_reply_list',$course_note_reply_list);
			
			if(!empty($course_note_reply_list)){
				$course_note_reply_from_list=array();
				$course_note_reply_user_ids=array();
				$course_note_reply_opertor_ids=array();
				foreach($course_note_reply_list as $v){
					if($v['CourseNoteReply']['reply_from']=='0'){
						$course_note_reply_user_ids[]=$v['CourseNoteReply']['reply_from_id'];
					}else{
						$course_note_reply_opertor_ids[]=$v['CourseNoteReply']['reply_from_id'];
					}
				}
				if(!empty($course_note_reply_user_ids)){
					$course_note_reply_user_infos=$this->User->find('all',array('conditions'=>array('User.id'=>$course_note_reply_user_ids,'User.status'=>'1'),'fields'=>'User.id,User.first_name,User.name'));
					foreach($course_note_reply_user_infos as $v)$course_note_reply_from_list['User'][$v['User']['id']]=$v['User'];
				}
				if(!empty($course_note_reply_opertor_ids)){
					$course_note_reply_operators=$this->Operator->find('list',array('conditions'=>array('Operator.id'=>$course_note_reply_opertor_ids,'Operator.status'=>'1'),'fields'=>'Operator.id,Operator.name'));
					$course_note_reply_from_list['Operator']=$course_note_reply_operators;
				}
				$this->set('course_note_reply_from_list',$course_note_reply_from_list);
			}
			
		}
    }
    
    function ajax_course_note_reply(){
    		Configure::write('debug',1);
        	$this->layout="ajax";
        	
        	$result=array();
        	$result['code']='0';
        	$result['message']=$this->ld['operation_failed'];
        	
        	if ($this->RequestHandler->isPost()&&$this->operator_privilege('user_learning',false)) {
        		$this->data['CourseNoteReply']['reply_from']='1';
        		$this->data['CourseNoteReply']['reply_from_id']=$this->admin['id'];
        		if(isset($_FILES['CourseNoteReply_media'])&&$_FILES['CourseNoteReply_media']['error']=='0'){
				$mediaInfo=pathinfo($_FILES['CourseNoteReply_media']['name']);
				$mediaName=md5($mediaInfo['filename'].time()).".".$mediaInfo['extension'];
				$media_root=WWW_ROOT.'media/CourseNoteReplyMedia/';
				$this->mkdirs($media_root);
				if (move_uploaded_file($_FILES['CourseNoteReply_media']['tmp_name'], $media_root.$mediaName)) {
					$media_path = '/media/CourseNoteReplyMedia/'.$mediaName;
					$this->data['CourseNoteReply']['media']=$media_path;
				}
			}
			$this->CourseNoteReply->save($this->data['CourseNoteReply']);
			$result['code']='1';
        		$result['message']=$this->ld['reply_success'];
        	}
        	die(json_encode($result));
    }
    
    function bridge_course_log(){
    		Configure::write('debug',1);
        	$this->layout="ajax";
        	
    		$this->loadModel('BridgeCourseLog');
		App::import('Vendor', 'bridgeplus', array('file' => 'bridgeplus.php'));
		$Bridgeplus=new Bridgeplus();
		$page=1;
		$pagesize=50;
		while(true){
			$params=array(
				'page'=>$page,
				'pagesize'=>$pagesize
			);
			$result=$Bridgeplus->execution('course_progrerss_list',$params);
			if(isset($result['result']['data'])&&sizeof($result['result']['data'])>0){
				foreach($result['result']['data'] as $v){
					$course_log=$this->BridgeCourseLog->find('first',array('fields'=>'id','conditions'=>array('bridge_id'=>$v['course_id'],'user_id'=>$v['user_id'])));
					$log_data=array(
						'id'=>isset($course_log['BridgeCourseLog'])?$course_log['BridgeCourseLog']['id']:0,
						'bridge_id'=>$v['course_id'],
						'user_id'=>$v['user_id'],
						'last_sync_date'=>date('Y-m-d H:i:s',strtotime($v['last_sync_date'])),
						'progress'=>$v['progress'],
						'score'=>$v['score'],
						'time_spent'=>$v['timeSpent']
					);
					$this->BridgeCourseLog->save($log_data);
				}
				$maxPage=ceil($result['result']['total']/$pagesize);
				if($page+1>$maxPage)break;
			}else{
				break;
			}
			$page++;
		}
		$this->redirect('index');
	}
	
	
	function user_ware_scorm($page=1){
		$this->operator_privilege('user_learning');
    		$this->operation_return_url(true);
		$this->menu_path = array('root' => '/hr/','sub' => '/courses/');
		$this->set('title_for_layout', 'Scorm课件学习情况 - '.$this->configs['shop_name']);
		$this->navigations[] = array('name' => "在线学习",'url' => '');
		$this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
		$this->navigations[] = array('name' => "学习进度",'url' => '/courses/user_course_detail');
		$this->navigations[] = array('name' => "Scorm课件学习情况",'url' => '/courses/user_ware_scorm');
		
		$this->loadModel('CourseScormLog');
		
		$conditions=array();
		$conditions['CourseScormLog.user_id <>']='0';
		$conditions['CourseClassWare.status']='1';
		$conditions['CourseClass.status']='1';
		$conditions['CourseChapter.status']='1';
		$conditions['Course.status']='1';
		if (isset($this->params['url']['course_keyword']) && $this->params['url']['course_keyword'] != '') {
			$conditions['or']['Course.name like'] = '%' . $this->params['url']['course_keyword'] . '%';
			$conditions['or']['CourseChapter.name like'] = '%' . $this->params['url']['course_keyword'] . '%';
			$conditions['or']['CourseClass.name like'] = '%' . $this->params['url']['course_keyword'] . '%';
			$conditions['or']['CourseClassWare.name like'] = '%' . $this->params['url']['course_keyword'] . '%';
			$this->set('course_keyword', $this->params['url']['course_keyword']);
		}
		if (isset($this->params['url']['user_keyword']) && $this->params['url']['user_keyword'] != '') {
			$conditions['or']['User.name like'] = '%' . $this->params['url']['user_keyword'] . '%';
			$conditions['or']['User.first_name like'] = '%' . $this->params['url']['user_keyword'] . '%';
			$this->set('user_keyword', $this->params['url']['user_keyword']);
		}
		if (isset($this->params['url']['start_date_time']) && $this->params['url']['start_date_time'] != '') {
			$conditions['CourseScormLog.modified >='] = date('Y-m-d 00:00:00',strtotime($this->params['url']['start_date_time']));
			$this->set('start_date_time', $this->params['url']['start_date_time']);
		}
		if (isset($this->params['url']['end_date_time']) && $this->params['url']['end_date_time'] != '') {
			$conditions['CourseScormLog.modified <='] = date('Y-m-d 23:59:59',strtotime($this->params['url']['end_date_time']));
			$this->set('end_date_time', $this->params['url']['end_date_time']);
		}
		if (isset($this->params['url']['page']) && intval($this->params['url']['page'])>0) {
			$page=$this->params['url']['page'];
		}
		$fields=array(
			'CourseScormLog.course_ware_id',
			'CourseScormLog.user_id',
			'Max(CourseScormLog.id) as last_read'
		);
		$joins = array(
			array('table' => 'svhr_course_class_wares',
				'alias' => 'CourseClassWare',
				'type' => 'left',
				'conditions' => array('CourseClassWare.id = CourseScormLog.course_ware_id')
			),
			array('table' => 'svhr_course_classes',
				'alias' => 'CourseClass',
				'type' => 'left',
				'conditions' => array('CourseClass.code = CourseClassWare.course_class_code and CourseClass.course_code=CourseClassWare.course_code')
			),
			array('table' => 'svhr_course_chapters',
				'alias' => 'CourseChapter',
				'type' => 'left',
				'conditions' => array('CourseChapter.code = CourseClassWare.chapter_code and CourseChapter.course_code=CourseClassWare.course_code')
			),
			array('table' => 'svhr_courses',
				'alias' => 'Course',
				'type' => 'left',
				'conditions' => array('Course.code = CourseClassWare.course_code')
			),
			array(
				'table' => 'svoms_users',
				'alias' => 'User',
				'type' => 'left',
				'conditions' => array('CourseScormLog.user_id = User.id')
			)
		);
		$course_scorm_list=$this->CourseScormLog->find('all',array('fields'=>$fields,'conditions'=>$conditions,'joins'=>$joins,'group'=>'CourseScormLog.course_ware_id,CourseScormLog.user_id'));
		if(!empty($course_scorm_list)){
			$total=sizeof($course_scorm_list);
			$this->configs['show_count'] = (int)$this->configs['show_count'] ? $this->configs['show_count'] : '20';
			$rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
			$parameters['get'] = array();
			//地址路由参数（和control,action的参数对应）
			$parameters['route'] = array('controller' => 'courses', 'action' => 'user_ware_scorm', 'page' => $page, 'limit' => $rownum);
			$options = array('page' => $page, 'show' => $rownum, 'total' => $total, 'modelClass' => 'CourseScormLog');
			$this->Pagination->init($conditions, $parameters, $options);
			$course_scorm_infos=$this->CourseScormLog->find('all',array('fields'=>$fields,'conditions'=>$conditions,'joins'=>$joins,'group'=>'CourseScormLog.course_ware_id,CourseScormLog.user_id','order'=>'CourseScormLog.created desc,CourseScormLog.id','page' => $page, 'limit' => $rownum));
			$course_scorm_ids=array();
			foreach($course_scorm_infos as $v){
				$course_scorm_ids[]=$v[0]['last_read'];
			}
			$list_fields=array(
				'CourseScormLog.*',
				'CourseClassWare.name',
				'CourseClass.name',
				'CourseChapter.name',
				'Course.name',
				'User.name',
				'User.first_name'
			);
			$course_scorm_list=$this->CourseScormLog->find('all',array('fields'=>$list_fields,'conditions'=>array('CourseScormLog.id'=>$course_scorm_ids),'joins'=>$joins,'order'=>'CourseScormLog.created desc,CourseScormLog.id'));
			$this->set('course_scorm_list',$course_scorm_list);
		}
	}
	
	function ajax_user_ware_scorm(){
		Configure::write('debug',1);
        	$this->layout="ajax";
        	
        	$result=array();
        	$result['code']='0';
        	$course_ware_id=isset($_POST['ware_id'])?$_POST['ware_id']:0;
        	$user_id=isset($_POST['user_id'])?$_POST['user_id']:0;
        	$this->loadModel('CourseScormLog');
        	$course_scorm_detail=$this->CourseScormLog->find('all',array('conditions'=>array('CourseScormLog.course_ware_id'=>$course_ware_id,'CourseScormLog.user_id'=>$user_id),'order'=>'CourseScormLog.created desc,CourseScormLog.id'));
        	if(!empty($course_scorm_detail)){
        		$result['code']='1';
        		$result['data']=$course_scorm_detail;
        	}
        	die(json_encode($result));
	}
	
	function ajax_ware_scorm_detail(){
		Configure::write('debug',1);
        	$this->layout="ajax";
        	
        	$result=array();
        	$result['code']='0';
        	$course_scorm_id=isset($_POST['course_scorm_id'])?$_POST['course_scorm_id']:0;
        	$this->loadModel('CourseScormLog');
        	$course_scorm_detail=$this->CourseScormLog->find('first',array('conditions'=>array('CourseScormLog.id'=>$course_scorm_id)));
        	if(!empty($course_scorm_detail)){
        		$course_scorm_detail['CourseScormLog']['scorm_data']=trim($course_scorm_detail['CourseScormLog']['scorm_data'])!=''?json_decode($course_scorm_detail['CourseScormLog']['scorm_data'],true):array();
        		if(!empty($course_scorm_detail['CourseScormLog']['scorm_data'])){
        			foreach($course_scorm_detail['CourseScormLog']['scorm_data'] as $kk=>$vv){
	        			$interaction_reg="/cmi_interactions_(?P<value>(\d+))_(?P<field_key>(\w+)).*/i";
	        			preg_match($interaction_reg, $kk, $field_matches);
	        			if(!empty($field_matches)&&isset($field_matches['value'])&&isset($field_matches['field_key']))unset($course_scorm_detail['CourseScormLog']['scorm_data'][$kk]);
        			}
        		}
        		$result['code']='1';
        		$result['data']=$course_scorm_detail['CourseScormLog'];
        		$result['scorm_interaction']=$this->CourseScormLog->scorm_interaction($result['data']['course_ware_id'],$result['data']['user_id']);
        	}
        	die(json_encode($result));
	}
	
	function ajax_inivate_user_list(){
		Configure::write('debug',1);
        	$this->layout="ajax";
        	
        	$result=array();
        	$result['code']='0';
        	$user_mobile=isset($_POST['user_mobile'])?$_POST['user_mobile']:'';
        	if($user_mobile!=''){
        		$this->loadModel('User');
        		$conditions=array();
        		$conditions['User.mobile like']="%{$user_mobile}%";
        		$conditions['User.status']='1';
        		$user_infos=$this->User->find('all',array('conditions'=>$conditions,'fields'=>'User.id,User.name,User.first_name,User.mobile'));
        		$user_list=array();
        		if(!empty($user_infos)){
        			foreach($user_infos as $v){
        				$user_list[]=$v['User'];
        			}
        			$result['code']='1';
        			$result['data']=$user_list;
        		}
        	}
        	die(json_encode($result));
	}
	
	function ajax_inivate_user(){
		Configure::write('debug',1);
        	$this->layout="ajax";
        	
        	$result=array();
        	$result['code']='0';
        	$result['message']='邀请失败';
        	
        	$course_id=isset($_POST['user_mobile'])?intval($_POST['course_id']):0;
        	$user_mobile=isset($_POST['user_mobile'])?$_POST['user_mobile']:'';
        	$user_id=isset($_POST['user_id'])?$_POST['user_id']:0;
        	
        	$condition=array();
        	if(!empty($user_id)&&$user_id>0){
        		$conditions['User.id']=$user_id;
        	}else if($user_mobile!=''){
        		$conditions['User.mobile']=$user_mobile;
        	}
        	if(!empty($conditions)){
        		$this->loadModel('User');
        		$conditions['User.status']='1';
        		$user_detail=$this->User->find('first',array('conditions'=>$conditions));
        		if(!empty($user_detail)){
        			$inivate_user_id=$user_detail['User']['id'];
        		}else if($user_mobile!=''){
        			$user_name=isset($_POST['user_name'])?$_POST['user_name']:'';
        			$user_data=array(
        				'id'=>0,
        				'user_sn'=>$user_mobile,
        				'name'=>$user_mobile,
        				'first_name'=>$user_name,
        				'mobile'=>$user_mobile,
        				'password'=>md5($this->configs['password-defult'])
        			);
        			$this->User->save($user_data);
        			$inivate_user_id=$this->User->id;
        		}
        		if(isset($inivate_user_id)&&!empty($inivate_user_id)){
        			$this->loadModel('UserCourseClass');
        			$conditions=array(
        				'UserCourseClass.user_id'=>$inivate_user_id,
        				'UserCourseClass.course_id'=>$course_id
        			);
        			$user_course_detail=$this->UserCourseClass->find('first',array('conditions'=>$conditions));
        			if(empty($user_course_detail)){
        				$user_course_data=array(
        					'id'=>0,
        					'course_id'=>$course_id,
        					'user_id'=>$inivate_user_id
        				);
        				$this->UserCourseClass->save($user_course_data);
        				
			        	$result['code']='1';
			        	$result['message']='邀请成功';
        			}else{
        				$result['message']='当前用户已在学习中';
        			}
        		}
        	}
        	die(json_encode($result));
	}
	
	function ajax_category_report(){
		Configure::write('debug',1);
        	$this->layout="ajax";
        	
        	$conditions=array();
        	$conditions['Course.status']='1';
        	$course_infos=$this->Course->find('all',array('conditions'=>$conditions,'fields'=>'Course.id,Course.course_category_code,Course.name'));
        	if(!empty($course_infos)){
        		$course_list=array();$category_courses=array();
        		foreach($course_infos as $v){
        			$course_list[$v['Course']['id']]=$v['Course']['name'];
        			$category_courses[$v['Course']['course_category_code']][]=$v['Course'];
        		}
        		$this->set('category_courses',$category_courses);
        		$course_category_infos=$this->CourseCategory->find('list',array('fields'=>'CourseCategory.code,CourseCategory.name','conditions'=>array('status'=>'1')));
        		$this->set('course_category_infos',$course_category_infos);
        		
        		$course_ids=array_keys($course_list);
        		$user_course_infos=$this->UserCourseClassDetail->find('all',array('fields'=>'UserCourseClass.course_id,UserCourseClass.user_id','conditions'=>array('UserCourseClass.course_id'=>$course_ids,'CourseClass.status'=>'1'),'group'=>'UserCourseClass.course_id,UserCourseClass.user_id'));
        		
        		$user_course_list=array();
        		if(!empty($user_course_infos)){
        			foreach($user_course_infos as $v){
        				$user_course_list[$v['UserCourseClass']['course_id']][]=$v['UserCourseClass']['user_id'];
        			}
        			foreach($user_course_list as $k=>$v)$user_course_list[$k]=sizeof($v);
        		}
        		$this->set('user_course_list',$user_course_list);
        	}
	}
}