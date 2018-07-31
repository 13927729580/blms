<?php

/*****************************************************************************
 * Course 课程
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
/**
 *这是一个名为CoursesController的控制器
 *课程
 *
 *@var
 *@var
 *@var
 *@var
 */
class CoursesController extends AppController
{
    public $name = 'Courses';
    public $helpers = array('Html','Pagination');
    public $uses = array('CourseClassWare','Course','CourseClass','CourseType','CourseChapter','CourseCategory','InformationResource','Resource','UserCourseClass','User','UserFans','Blog','UserAction','CourseComment','Organization','OrganizationMember','OrganizationJob','OrganizationDepartment','OrganizationMemberJob','NotifyTemplateType','OrganizationShare','Profile','ProfileFiled','CourseNote','OrganizationRelation','OrganizationManager','UserCourseClassDetail','Precondition');
    public $components = array('RequestHandler','Pagination','Notify','Phpcsv');

    public function index(){
		$_GET=$this->clean_xss($_GET);
		$this->layout = 'default_full';
		$this->pageTitle = '在线学习 - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '在线学习' , 'url' => '/courses/');
		$this->set('ur_heres', $this->ur_heres);
		$params=array();
		$params['ControllerObj']=$this;
		$params['flash_type']='course';
		$this->page_init($params);
    }

    /**
     *	课程
     */
    public function view($id=0){
        $this->layout = 'default_full';
        $course_data=$this->Course->find('first',array('conditions'=>array('Course.id'=>$id,'Course.status'=>'1')));
        $this->set('course_data',$course_data);
        if(empty($course_data)){$this->redirect('/pages/home');}
        $this->set('meta_description',$course_data['Course']['meta_description']);
        $this->pageTitle = $course_data['Course']['name'].' - '.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => '课程' , 'url' => '/courses/');
        $this->ur_heres[] = array('name' => $course_data['Course']['name'] , 'url' => '/courses/view/'.$id);
        $this->set('ur_heres', $this->ur_heres);
        $course_type_code=$course_data['Course']['course_type_code'];
        $course_category_code=$course_data['Course']['course_category_code'];
        if($course_category_code!=""){
            $course_category_data=$this->CourseCategory->find('first',array('conditions'=>array('CourseCategory.code'=>$course_category_code,'CourseCategory.status'=>'1')));
            $this->set('course_category_data',$course_category_data);
        }
        if($course_type_code!=""){
            $course_type_data=$this->CourseType->find('first',array('conditions'=>array('CourseType.code'=>$course_type_code,'CourseType.status'=>'1')));
            $this->set('course_type_data',$course_type_data);
        }
        $params=array();
        $params['id']=$id;
        $params['course_code']=$course_data['Course']['code'];
        $params['course_data']=$course_data;
        $params['ControllerObj']=$this;
        $this->page_init($params);
        //资源库信息
        $informationresource_infos = $this->InformationResource->code_information_formated(array('course_comment','course_level'), $this->locale);
        $this->set('informationresource_infos', $informationresource_infos);
        if(isset($_SESSION['User'])){
	            $user_id=$_SESSION['User']['User']['id'];
	            $user_detail=$this->User->find('first',array('fields'=>'User.id,User.mobile','conditions'=>array('User.id'=>$user_id,'User.mobile <>'=>'')));
	            $course_id=$course_data['Course']['id'];
	            $user_course_comment=$this->CourseComment->find('first',array('conditions'=>array('CourseComment.user_id'=>$user_id,'CourseComment.course_id'=>$course_id)));
	            if(!empty($user_course_comment['CourseComment'])){
	                	$this->set('user_course_comment',$user_course_comment);
	            }
        }
        $access_result=$this->Course->access_permission($this,$id,0,false);
        $this->set('access_result',$access_result);
    }
    
    function ajax_access_permission($course_id=0,$course_class_id=0){
    		Configure::write('debug',1);
		$this->layout = 'ajax';
    		$course_id=isset($_REQUEST['course_id'])?$_REQUEST['course_id']:$course_id;
    		$course_class_id=isset($_REQUEST['course_class_id'])?$_REQUEST['course_class_id']:$course_class_id;
    		$access_result=$this->Course->access_permission($this,$course_id,$course_class_id);
		$this->set('access_result',$access_result);
		
		$this->set('course_id',$course_id);
		$this->set('course_class_id',$course_class_id);
		
		$redirect_url=$this->server_host.$this->webroot."/courses/detail/".$course_id.'/'.$course_class_id;
		$this->set('redirect_url',$redirect_url);
    }
    
    public function detail($id=0,$course_class_id=0){
		$this->layout = 'default_full';
		$this->set('course_id',$id);
		$this->set('course_class_id',$course_class_id);
		$course_data=$this->Course->find('first',array('conditions'=>array('Course.id'=>$id,'Course.status'=>'1')));
		$this->set('course_data',$course_data);
		if(empty($course_data))$this->redirect('/');
		
		if(isset($course_data['Course']['allow_learning'])&&$course_data['Course']['allow_learning']=='2'){
			if(isset($_SESSION['User']))$user_id=$_SESSION['User']['User']['id'];
		}else{
			//登录验证
			$this->checkSessionUser();
			$user_id=$_SESSION['User']['User']['id'];
		}
		if(isset($course_data['Course']['allow_learning'])&&$course_data['Course']['allow_learning']=='0'){
			$CourseClassLog_info=$this->UserCourseClass->find('first',array('conditions'=>array('UserCourseClass.user_id'=>$user_id,'UserCourseClass.course_id'=>$id,'UserCourseClass.status <>'=>'0')));
			if(empty($CourseClassLog_info))$this->redirect('/courses/view/'.$id);
		}
		$this->set('meta_description',$course_data['Course']['meta_description']);
		$course_detail=$this->Course->course_detail(array('course_data'=>$course_data));
		$this->set('course_detail',$course_detail);
		if(empty($course_class_id)&&isset($user_id)){
			$error_course_class=array();
			if(isset($course_detail['class_access_permission'])&&sizeof($course_detail['class_access_permission'])>0){
				foreach($course_detail['class_access_permission'] as $k=>$v){
					if($v['code']=='0')$error_course_class[]=$k;
				}
			}
			$conditions=array();
			$conditions['UserCourseClass.user_id']=$user_id;
			$conditions['UserCourseClass.course_id']=$id;
			$conditions['UserCourseClass.status <>']='0';
			$conditions['UserCourseClassDetail.status']='0';
			$conditions['UserCourseClassDetail.course_class_id <>']=0;
			if(!empty($error_course_class)){
				$conditions['not']['UserCourseClassDetail.course_class_id']=$error_course_class;
			}
			$conditions['CourseClass.status']=1;
			$last_course_class=$this->UserCourseClassDetail->find('all',array('fields'=>array('UserCourseClassDetail.id','UserCourseClassDetail.course_class_id','UserCourseClass.status'),'conditions'=>$conditions,'order'=>'UserCourseClassDetail.created'));
			if(!empty($last_course_class)){
				$last_course_class_ids=array();
				foreach($last_course_class as $v)$last_course_class_ids[]=$v['UserCourseClassDetail']['course_class_id'];
				$conditions=array();
				$conditions['CourseChapter.status']='1';
				$conditions['CourseClass.status']='1';
				$conditions['CourseClass.course_code']=$course_data['Course']['code'];
				$conditions['CourseClass.id']=$last_course_class_ids;
				$next_course_class_data=$this->CourseClass->find('first',array('fields'=>'CourseClass.id,CourseClass.name','conditions'=>$conditions,'order'=>'CourseChapter.orderby,CourseChapter.id,CourseClass.orderby,CourseClass.id'));
				if(!empty($next_course_class_data)){
					$course_class_id=$next_course_class_data['CourseClass']['id'];
				}else{
					$conditions=array();
					$conditions['CourseChapter.status']='1';
					$conditions['CourseClass.status']='1';
					$conditions['CourseClass.course_code']=$course_data['Course']['code'];
					$next_course_class_data=$this->CourseClass->find('first',array('fields'=>'CourseClass.id,CourseClass.name','conditions'=>$conditions,'order'=>'CourseChapter.orderby,CourseChapter.id,CourseClass.orderby,CourseClass.id'));
					$course_class_id=isset($next_course_class_data['CourseClass'])?$next_course_class_data['CourseClass']['id']:$course_class_id;
				}
			}else{
				$conditions=array();
				$conditions['CourseChapter.status']='1';
				$conditions['CourseClass.status']='1';
				$conditions['CourseClass.course_code']=$course_data['Course']['code'];
				$user_course_class_infos=$this->UserCourseClassDetail->find('all',array('fields'=>'UserCourseClassDetail.id,UserCourseClassDetail.course_class_id','conditions'=>array('UserCourseClass.user_id'=>$user_id,'UserCourseClass.course_id'=>$id,'UserCourseClassDetail.status'=>'1')));
				if(!empty($user_course_class_infos)){
					$user_course_class_list=array();
					foreach($user_course_class_infos as $v)$user_course_class_list[]=$v['UserCourseClassDetail']['course_class_id'];
					$user_course_class_list=array_unique($user_course_class_list);
					$conditions['not']['CourseClass.id']=$user_course_class_list;
				}
				$next_course_class_data=$this->CourseClass->find('first',array('fields'=>'CourseClass.id,CourseClass.name','conditions'=>$conditions,'order'=>'CourseChapter.orderby,CourseChapter.id,CourseClass.orderby,CourseClass.id'));
				$course_class_id=isset($next_course_class_data['CourseClass'])?$next_course_class_data['CourseClass']['id']:$course_class_id;
			}
		}else if(empty($course_class_id)){
			$conditions=array();
			$conditions['CourseChapter.status']='1';
			$conditions['CourseClass.status']='1';
			$conditions['CourseClass.course_code']=$course_data['Course']['code'];
			$next_course_class_data=$this->CourseClass->find('first',array('fields'=>'CourseClass.id,CourseClass.name','conditions'=>$conditions,'order'=>'CourseChapter.orderby,CourseChapter.id,CourseClass.orderby,CourseClass.id'));
			$course_class_id=isset($next_course_class_data['CourseClass'])?$next_course_class_data['CourseClass']['id']:$course_class_id;
		}
		$course_code=$course_data['Course']['code'];
		$course_class_detail=$this->CourseClass->find('first',array('conditions'=>array('CourseClass.id'=>$course_class_id,'CourseClass.course_code'=>$course_code,'CourseClass.status'=>'1')));
		if(empty($course_class_detail))$this->redirect('/courses/view/'.$id);
		$this->set('course_class_detail',$course_class_detail);
		
		$access_result=$this->Course->access_permission($this,$id,$course_class_id,false);
		$this->set('access_result',$access_result);
		
		if(isset($access_result['code'])&&$access_result['code']=='1'){
		$course_ware_list=$this->CourseClassWare->find('all',array('conditions'=>array('CourseClassWare.course_code'=>$course_code,'CourseClassWare.status'=>'1','CourseClassWare.course_class_code'=>$course_class_detail['CourseClass']['code']),'order'=>'CourseClassWare.orderby,CourseClassWare.id'));
			if(!empty($course_ware_list)){
				$user_assignment_ids=array();
				foreach($course_ware_list as $k=>$v){
					if($v['CourseClassWare']['type']=='evaluation'&&$v['CourseClassWare']['ware']!=''){
						$this->loadModel('Evaluation');
						$evaluation_detail=$this->Evaluation->evaluation_detail(array('id'=>$v['CourseClassWare']['ware']));
						if(!empty($evaluation_detail)){
							$course_ware_list[$k]['evaluation_detail']=$evaluation_detail;
						}
					}else if($v['CourseClassWare']['type']=='assignment'){
						$this->loadModel('CourseAssignment');
						if(isset($user_id)){
							$conditions=array();
			        			$conditions['CourseAssignment.course_id']=$id;
			        			$conditions['CourseAssignment.course_ware_id']=$v['CourseClassWare']['id'];
			        			$conditions['CourseAssignment.user_id']=$user_id;
			        			$UserCourseAssignmentDetail=$this->CourseAssignment->find('first',array('conditions'=>$conditions));
							if(!empty($UserCourseAssignmentDetail)){
								$user_assignment_ids[]=$UserCourseAssignmentDetail['CourseAssignment']['id'];
								$course_ware_list[$k]['assignment_detail']=$UserCourseAssignmentDetail['CourseAssignment'];
							}
						}
					}else if($v['CourseClassWare']['type']=='activity'){
						$this->loadModel('Activity');
						$conditions=array();
		        			$conditions['Activity.status']='1';
		        			$conditions['Activity.id']=$v['CourseClassWare']['ware'];
		        			$activity_fields="Activity.id,Activity.name,Activity.image,Activity.description";
		        			$activity_detail=$this->Activity->find('first',array('conditions'=>$conditions,'fields'=>$activity_fields));
		        			if(!empty($activity_detail)){
		        				$course_ware_list[$k]['activity_detail']=$activity_detail['Activity'];
		        			}
					}else if($v['CourseClassWare']['type']=='gallery'){
						$gallery_list=explode(';',$v['CourseClassWare']['ware']);
						$gallery_total=0;
						if(!empty($gallery_list)){
							foreach($gallery_list as $vv){
								$mime_type=mime_content_type(WWW_ROOT.$vv);
								if($mime_type=='application/pdf'){
									$gallery_total+=$this->pdf2pngSize(WWW_ROOT.$vv);
								}else if(preg_match("/(image|IMAGE)\/(.*)$/",$mime_type)){
									$gallery_total++;
								}
							}
						}
						$course_ware_list[$k]['gallery_total']=$gallery_total;
					}
				}
				if(!empty($user_assignment_ids)){
					$this->loadModel('CourseAssignmentScore');
					$score_cond=array();
					$score_cond['CourseAssignmentScore.course_assignment_id']=$user_assignment_ids;
					$CourseAssignmentScoreList=$this->CourseAssignmentScore->find('all',array('conditions'=>$score_cond,'order'=>'CourseAssignmentScore.modified'));
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
							$this->loadModel('Operator');
							$reply_operator_list=$this->Operator->find('list',array('fields'=>'Operator.id,Operator.name','conditions'=>array('Operator.id'=>$reply_operator_ids)));
							$this->set('reply_operator_list',$reply_operator_list);
						}
					}
				}
			}
			$this->set('course_ware_list',$course_ware_list);
		}else{
			$this->redirect('/courses/view/'.$id);
		}
		
		$course_chapter_id=$course_class_detail['CourseChapter']['id'];
		$course_chapter_list=isset($course_detail['course_chapter'][$course_chapter_id]['CourseClass'])?$course_detail['course_chapter'][$course_chapter_id]['CourseClass']:array();
		$this->set('course_chapter_list',$course_chapter_list);
		$this->pageTitle = $course_class_detail['CourseClass']['name'].' - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '课程' , 'url' => '/courses/');
		$this->ur_heres[] = array('name' => $course_data['Course']['name'] , 'url' => '/courses/view/'.$id);
		$this->ur_heres[] = array('name' => $course_class_detail['CourseClass']['name'] , 'url' => '');
		$this->set('ur_heres', $this->ur_heres);
		
		if(isset($access_result['code'])&&$access_result['code']=='1'&&isset($user_id)){
			$CourseClassLog_info=$this->UserCourseClass->find('first',array('conditions'=>array('UserCourseClass.user_id'=>$user_id,'UserCourseClass.course_id'=>$id)));
			if(empty($CourseClassLog_info)){
				$CourseClassLog_data=array(
					'id'=>0,
					'user_id'=>$user_id,
					'course_id'=>$id
				);
				$this->UserCourseClass->save($CourseClassLog_data);
				$CourseClassLog_id=$this->UserCourseClass->id;
			}else{
				$CourseClassLog_id=$CourseClassLog_info['UserCourseClass']['id'];
			}
			$conditions=array();
			$conditions['UserCourseClass.user_id']=$user_id;
			$conditions['UserCourseClass.course_id']=$id;
			//$conditions['UserCourseClass.status <>']='0';
			$conditions['UserCourseClassDetail.course_class_id']=$course_class_id;
			$user_course_class=$this->UserCourseClassDetail->find('first',array('fields'=>array('UserCourseClassDetail.id','UserCourseClassDetail.status','UserCourseClassDetail.read_time'),'conditions'=>$conditions));
		       if(empty($user_course_class)){
		       	$this->UserCourseClassDetail->save(array(
		       		'id'=>isset($user_course_class['UserCourseClassDetail'])?$user_course_class['UserCourseClassDetail']['id']:0,
		       		'user_course_class_id'=>$CourseClassLog_id,
		       		'course_class_id'=>$course_class_id
		       	));
		       }else{
		       	$this->set('user_course_class',$user_course_class);
		       	$cookie_key="course_read_time{$id}{$course_class_id}";
		       	setcookie ($cookie_key, $user_course_class['UserCourseClassDetail']['read_time'], time()+3600);
		       }
		        $course_log_total=$this->UserCourseClassDetail->find('count',array('conditions'=>array('user_course_class_id'=>$CourseClassLog_id,'course_class_id >'=>0)));
		        $course_class_total=$this->CourseClass->find('count',array('conditions'=>array('CourseClass.course_code'=>$course_code,'CourseClass.status'=>'1')));
		        if($course_log_total==$course_class_total){
		            	$experience_value=$course_data['Course']['experience_value'];
		            	$this->UserCourseClass->updateAll(array('UserCourseClass.status'=>'2','UserCourseClass.modified'=>"'".date('Y-m-d H:i:s')."'"),array('UserCourseClass.id'=>$CourseClassLog_id));
		        }
		        $user_action_data=array(
		            'id'=>0,
		            'user_id'=>$user_id,
		            'type'=>'course',
		            'type_id'=>$id,
		            'content'=>$course_data['Course']['name']." - ".$course_class_detail['CourseChapter']['name'].' - '.$course_class_detail['CourseClass']['name']
		        );
		        $this->UserAction->save($user_action_data);
	    	}
		$last_course_class_condtions=array();
		$last_course_class_condtions['CourseChapter.status']='1';
		$last_course_class_condtions['CourseClass.status']='1';
		$last_course_class_condtions['CourseClass.course_code']=$course_data['Course']['code'];
		$last_course_class_condtions['or'][]=array(
			'CourseClass.chapter_code'=>$course_class_detail['CourseClass']['chapter_code'],
			'CourseClass.orderby'=>$course_class_detail['CourseClass']['orderby'],
			'CourseClass.id <'=>$course_class_detail['CourseClass']['id']
		);
		$last_course_class_condtions['or'][]=array(
			'CourseClass.chapter_code'=>$course_class_detail['CourseClass']['chapter_code'],
			'CourseClass.orderby <'=>$course_class_detail['CourseClass']['orderby']
		);
		$last_course_class_condtions['or'][]=array(
			'CourseChapter.orderby'=>$course_class_detail['CourseChapter']['orderby'],
			'CourseChapter.id <'=>$course_class_detail['CourseChapter']['id']
		);
		$last_course_class_condtions['or'][]=array(
			'CourseChapter.orderby <'=>$course_class_detail['CourseChapter']['orderby']
		);
		$last_course_class_data=$this->CourseClass->find('first',array('fields'=>'CourseClass.id,CourseClass.name','conditions'=>$last_course_class_condtions,'order'=>'CourseChapter.orderby desc,CourseChapter.id desc,CourseClass.orderby desc,CourseClass.id desc'));
		$this->set('last_course_class_data',$last_course_class_data);
		
		$next_course_class_condtions=array();
		$next_course_class_condtions['CourseChapter.status']='1';
		$next_course_class_condtions['CourseClass.status']='1';
		$next_course_class_condtions['CourseClass.course_code']=$course_data['Course']['code'];
		$next_course_class_condtions['or'][]=array(
			'CourseClass.chapter_code'=>$course_class_detail['CourseClass']['chapter_code'],
			'CourseClass.orderby'=>$course_class_detail['CourseClass']['orderby'],
			'CourseClass.id >'=>$course_class_detail['CourseClass']['id']
		);
		$next_course_class_condtions['or'][]=array(
			'CourseClass.chapter_code'=>$course_class_detail['CourseClass']['chapter_code'],
			'CourseClass.orderby >'=>$course_class_detail['CourseClass']['orderby']
		);
		$next_course_class_condtions['or'][]=array(
			'CourseChapter.orderby'=>$course_class_detail['CourseChapter']['orderby'],
			'CourseChapter.id >'=>$course_class_detail['CourseChapter']['id']
		);
		$next_course_class_condtions['or'][]=array(
			'CourseChapter.orderby >'=>$course_class_detail['CourseChapter']['orderby']
		);
		$next_course_class_data=$this->CourseClass->find('first',array('fields'=>'CourseClass.id,CourseClass.name','conditions'=>$next_course_class_condtions,'order'=>'CourseChapter.orderby,CourseChapter.id,CourseClass.orderby,CourseClass.id'));
		$this->set('next_course_class_data',$next_course_class_data);
    }
    
    function scorm($course_id=0,$course_class_id=0,$course_ware_id=0){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		
		$course_data=$this->Course->find('first',array('conditions'=>array('Course.id'=>$course_id,'Course.status'=>'1')));
		if(empty($course_data)){
			header('HTTP/1.1 404 Not Found');
			exit();
		}
		$access_result=$this->Course->access_permission($this,$course_id,$course_class_id,false);
		if(isset($access_result['code'])&&$access_result['code']=='1'){
			$couse_ware_detail=$this->CourseClassWare->find('first',array('conditions'=>array('CourseClassWare.id'=>$course_ware_id,'CourseClassWare.status'=>'1')));
			if(!empty($couse_ware_detail)){
				$wareDir="";
				$oldwareDir=trim($couse_ware_detail['CourseClassWare']['ware_tmp'])!=''?WWW_ROOT.($couse_ware_detail['CourseClassWare']['ware_tmp']):'';
				$oldwareTerm=trim($couse_ware_detail['CourseClassWare']['ware_term'])!=''?strtotime($couse_ware_detail['CourseClassWare']['ware_term']):strtotime('-1 days');
				if($oldwareTerm<=time()&&file_exists($oldwareDir)&&is_dir($oldwareDir)){
					$this->removeScromDir($oldwareDir);
				}
				if($oldwareTerm>=time()&&file_exists($oldwareDir)&&is_dir($oldwareDir)){
					$wareDir=$oldwareDir;
					$warePath=file_exists($wareDir."/imsmanifest.xml")&&is_file($wareDir."/imsmanifest.xml")?$wareDir."/imsmanifest.xml":'';
				}else if(trim($couse_ware_detail['CourseClassWare']['ware'])!=''){
					$wareRoot=WWW_ROOT."media/SCROM/";
					$this->mkdirs($wareRoot);
					$wareDir=$wareRoot.md5(md5($couse_ware_detail['CourseClassWare']['code']).$this->shortGuid());
					if(file_exists($wareDir))$wareDir=$wareRoot.md5(md5($couse_ware_detail['CourseClassWare']['code']).$this->shortGuid());
					$wareFile=WWW_ROOT.$couse_ware_detail['CourseClassWare']['ware'];
					if(file_exists($wareFile)&&is_file($wareFile)&&mime_content_type($wareFile)=='application/zip'){
						if($this->ScromZip($wareFile,$wareDir)){
							$warePath=file_exists($wareDir."/imsmanifest.xml")&&is_file($wareDir."/imsmanifest.xml")?$wareDir."/imsmanifest.xml":'';
							$this->CourseClassWare->save(array(
								'id'=>$couse_ware_detail['CourseClassWare']['id'],
								'ware_tmp'=>str_replace(WWW_ROOT,'/',$wareDir),
								'ware_term'=>date('Y-m-d 23:59:59')
							));
							$couse_ware_detail['CourseClassWare']['ware_tmp']=str_replace(WWW_ROOT,'/',$wareDir);
							$couse_ware_detail['CourseClassWare']['ware_term']=date('Y-m-d 23:59:59');
						}
					}
				}
				if(isset($warePath)&&file_exists($warePath)&&is_file($warePath)){
					$warePath=str_replace(WWW_ROOT,'/',$warePath);
					$couse_ware_detail['CourseClassWare']['ware']=$warePath;
				}
				$this->set('couse_ware_detail',$couse_ware_detail);
			}else{
				header('HTTP/1.1 404 Not Found');
				exit();
			}
		}else{
			header('HTTP/1.1 404 Not Found');
			exit();
		}
    }
    
    function ajax_course_scrom(){
		Configure::write('debug',0);
		$this->layout = 'ajax';
		
		$result = array();
        	$result['code'] = 0;
        	if ($this->RequestHandler->isPost()) {
        		$login_user=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
        		$scorm_user=isset($_POST['cmi_core_student_id'])?intval($_POST['cmi_core_student_id']):0;
        		$scorm_ware=isset($_POST['cmi_core_ware_id'])?intval($_POST['cmi_core_ware_id']):0;
        		
        		if(!empty($scorm_user)&&!empty($scorm_ware)&&$login_user==$scorm_user){
        			$this->loadModel('CourseScormLog');
        			$scorm_data=array(
        				'id'=>0,
        				'course_ware_id'=>$scorm_ware,
        				'user_id'=>$scorm_user,
        				'scorm_data'=>json_encode($_POST),
        			);
        			$this->CourseScormLog->save($scorm_data);
        			$result['code'] = 1;
        		}
        	}
		die(json_encode($result));
    }
    
    function ajax_favorite_like(){
    		Configure::write('debug', 0);
		$this->layout = 'ajax';
        	
        	$result=array();
        	$result['code']='0';
        	$user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
        	$course_id=isset($_POST['course_id'])?$_POST['course_id']:0;
        	$this->loadModel('UserFavorite');
        	$course_favourite_info=$this->UserFavorite->find('first',array('fields'=>'id,status','conditions'=>array(
			'UserFavorite.user_id'=>$user_id,
			'UserFavorite.type'=>'c',
			'UserFavorite.type_id'=>$course_id,
			'UserFavorite.user_id <>'=>0,
			'UserFavorite.type_id <>'=>0
        	)));
        	if(!empty($course_favourite_info)){
        		if($course_favourite_info['UserFavorite']['status']!='1'){
        			$this->UserFavorite->save(array('id'=>$course_favourite_info['UserFavorite']['id'],'status'=>'1'));
        		}else{
        			$this->UserFavorite->save(array('id'=>$course_favourite_info['UserFavorite']['id'],'status'=>'0'));
        		}
        		$result['code']='1';
        	}else if(!empty($user_id)&&!empty($course_id)){
        		$course_favourite_data=array(
        			'id'=>0,
				'user_id'=>$user_id,
				'type'=>'c',
				'type_id'=>$course_id,
				'status'=>'1'
	        	);
	        	$this->UserFavorite->save($course_favourite_data);
	        	$result['code']='1';
        	}
        	die(json_encode($result));
    }
    
    function ajax_complete_course_class(){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
        	
        	$result=array();
        	$result['code']='0';
        	$result['message']=$this->ld['invalid_operation'];
        	
        	$user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
        	$course_id=isset($_POST['course_id'])?$_POST['course_id']:0;
        	$course_class_id=isset($_POST['course_class_id'])?$_POST['course_class_id']:0;
        	
        	$user_course_log=$this->UserCourseClassDetail->find('first',array('conditions'=>array(
        		'UserCourseClass.user_id'=>$user_id,
        		'UserCourseClass.course_id'=>$course_id,
        		'UserCourseClassDetail.course_class_id'=>$course_class_id
        	)));
        	if(!empty($user_course_log)){
        		$is_forcible_operation=isset($_POST['forcible_operation'])?$_POST['forcible_operation']:0;
        		$course_hour=isset($user_course_log['CourseClass']['courseware_hour'])?intval($user_course_log['CourseClass']['courseware_hour']):0;
        		$user_read_time=isset($user_course_log['UserCourseClassDetail']['read_time'])?intval($user_course_log['UserCourseClassDetail']['read_time']):0;
        		$user_read_time=$user_read_time>0?intval($user_read_time/60):0;
        		if($is_forcible_operation=='0'&&$course_hour>0&&$user_read_time<($course_hour/2)){
        			$result['code']='2';
        			$result['message']="当前学习时长不足";
        		}else{
        			$this->UserCourseClassDetail->save(array('id'=>$user_course_log['UserCourseClassDetail']['id'],'status'=>'1'));
        			$result['code']='1';
        			$result['message']=$this->ld['successfully'];
        			
				$next_course_class=array();
				$conditions=array();
				$conditions['UserCourseClass.user_id']=$user_id;
				$conditions['UserCourseClass.course_id']=$course_id;
				$conditions['UserCourseClass.status <>']='0';
				$conditions['UserCourseClassDetail.status']='0';
				$conditions['UserCourseClassDetail.course_class_id <>']=0;
				$conditions['CourseClass.status']='1';
				$last_course_class=$this->UserCourseClassDetail->find('all',array('fields'=>array('UserCourseClassDetail.id','UserCourseClassDetail.course_class_id'),'conditions'=>$conditions,'order'=>'UserCourseClassDetail.created'));
				if(!empty($last_course_class)){
					$last_course_class_ids=array();
					foreach($last_course_class as $v)$last_course_class_ids[]=$v['UserCourseClassDetail']['course_class_id'];
					
					$conditions=array();
					$conditions['CourseChapter.status']='1';
					$conditions['CourseClass.status']='1';
					$conditions['CourseClass.course_code']=$user_course_log['CourseClass']['course_code'];
					$conditions['CourseClass.id']=$last_course_class_ids;
					$next_course_class=$this->CourseClass->find('first',array('fields'=>'CourseClass.id,CourseClass.name','conditions'=>$conditions,'order'=>'CourseChapter.orderby,CourseChapter.id,CourseClass.orderby,CourseClass.id'));
				}else{
					$conditions=array();
					$conditions['CourseChapter.status']='1';
					$conditions['CourseClass.status']='1';
					$conditions['CourseClass.course_code']=$user_course_log['CourseClass']['course_code'];
					$conditions['not']['CourseClass.id']=$this->UserCourseClassDetail->find('list',array('fields'=>'id,course_class_id','conditions'=>array('UserCourseClassDetail.user_course_class_id'=>$user_course_log['UserCourseClassDetail']['user_course_class_id'],'UserCourseClassDetail.status'=>'1')));
					$next_course_class=$this->CourseClass->find('first',array('fields'=>'CourseClass.id,CourseClass.name','conditions'=>$conditions,'order'=>'CourseChapter.orderby,CourseChapter.id,CourseClass.orderby,CourseClass.id'));
				}
				if(!empty($next_course_class))$result['next_course_class']=$next_course_class['CourseClass'];
			}
        	}
        	die(json_encode($result));
    }

    function course_log($page=1,$limit=10){
        //登录验证
        $this->checkSessionUser();
        $_GET=$this->clean_xss($_GET);
        $this->layout = 'usercenter';//引入模版
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            	Configure::write('debug', 0);
            	$this->layout = 'ajax';
        }
        $this->page_init();                        //页面初始化
        $this->pageTitle = '我的课程 - '.$this->configs['shop_title'];
        //当前位置开始
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
        $this->ur_heres[] = array('name' => '我的课程', 'url' => '');

        $user_id=$_SESSION['User']['User']['id'];
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        if ($user_list['User']['rank'] > 0) {
            $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
            $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
        }
        $this->set('user_list', $user_list);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($user_id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($user_id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($user_id);
        $this->set('focuscount', $focus);
        $share_couser_ids=array();
        $share_couser_cond=array();
        $user_member_list = $this->OrganizationMember->find('list',array('fields'=>'OrganizationMember.organization_id,OrganizationMember.id','conditions'=>array('OrganizationMember.user_id'=>$user_id,'OrganizationMember.status'=>'1')));
        if(!empty($user_member_list)){
            $user_member_organization=array_keys($user_member_list);
            $user_member_department=$this->OrganizationMemberJob->find('list',array('fields'=>'organization_department_id','conditions'=>array('organization_department_id <>'=>0,'organization_id'=>$user_member_organization,'organization_member_id'=>$user_member_list)));
            $share_couser_cond['or'][]=array(
                'share_object'=>'0',
                'share_object_ids'=>$user_member_list
            );
            if(trim($user_list['User']['mobile'])!=''){
                $share_couser_cond['or'][]=array(
                    'share_object'=>'0',
                    'share_object_ids like'=>"%|".$user_list['User']['mobile']
                );
            }
            $share_couser_cond['or'][]=array(
                'share_object'=>'2',
                'share_object_ids'=>$user_member_organization
            );
            if(!empty($user_member_department)){
                $share_couser_cond['or'][]=array(
                    'share_object'=>'1',
                    'share_object_ids'=>$user_member_department
                );
            }
        }
        $manager_organization_ids=$this->Organization->find('list',array('fields'=>'id','conditions'=>array('manage_user'=>$user_id,'status'=>'1')));
        if(!empty($manager_organization_ids)){
            $share_couser_cond['or'][]=array(
                'share_object'=>'3',
                'share_object_ids'=>$manager_organization_ids
            );
        }
        if(!empty($share_couser_cond)){
            $share_couser_cond['OrganizationShare.share_type']='course';
            $share_couser_cond['OrganizationShare.share_type_id <>']='0';
            $share_couser_ids=$this->OrganizationShare->find('list',array('conditions'=>$share_couser_cond,'fields'=>'share_type_id'));
        }
	$couse_class_detail_infos=$this->UserCourseClass->find('list',array('fields'=>'UserCourseClass.course_id','conditions'=>array('UserCourseClass.user_id'=>$user_id,'UserCourseClass.status <>'=>'0')));
        $conditions=array();
        $conditions['or'][]['Course.user_id']=$user_id;
        $conditions['Course.status']='1';
        if(!empty($share_couser_ids))$conditions['or'][]['Course.id']=$share_couser_ids;
        if(!empty($couse_class_detail_infos)){
        	$conditions['or'][]['Course.id']=$couse_class_detail_infos;
        	$user_course_view_infos=$this->UserCourseClassDetail->find('all',array('fields'=>"UserCourseClass.course_id,count(*) as read_count",'conditions'=>array('UserCourseClass.course_id'=>$couse_class_detail_infos,'UserCourseClass.user_id'=>$user_id),'group'=>'UserCourseClass.course_id'));
        	$user_course_view_list=array();
        	foreach($user_course_view_infos as $v)$user_course_view_list[$v['UserCourseClass']['course_id']]=$v[0]['read_count'];
        	$this->set('user_course_view_list',$user_course_view_list);
        }
        $parameters=array();
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'courses', 'action' => 'course_log', 'page' => $page, 'limit' => $limit);
        //分页参数
        $options = array('page' => $page, 'show' => $limit, 'modelClass' => 'Course');
        $this->Pagination->init($conditions, $parameters, $options); // Added
        $UserCourse_lists=$this->Course->find('all',array('conditions'=>$conditions,'order'=>'Course.modified desc','page'=>$page,'limit'=>$limit));
        $this->set('UserCourse_lists',$UserCourse_lists);
        if(!empty($UserCourse_lists)){
            $course_codes=array();
            foreach($UserCourse_lists as $v)$course_codes[]=$v['Course']['code'];
            $course_class_infos=$this->CourseClass->find('all',array('conditions'=>array('CourseClass.course_code'=>$course_codes),'fields'=>'CourseClass.course_code,count(*) as class_count','group'=>'course_code'));
            $course_class_list=array();
            foreach($course_class_infos as $v)$course_class_list[$v['CourseClass']['course_code']]=$v[0]['class_count'];
            $this->set('course_class_list',$course_class_list);
            if(!empty($share_couser_ids)){
                $share_couser_cond['share_type_id']=$share_couser_ids;
                $share_course_objects=$this->OrganizationShare->find('all',array('fields'=>'share_type_id,organization_id,share_user','conditions'=>$share_couser_cond,'group'=>'share_type_id,organization_id,share_user','order'=>'id desc'));
                $share_course_object_list=array();
                $share_course_user_ids=array();$share_course_organization_ids=array();
                $share_course_user_list=array();$share_course_organization_list=array();
                foreach($share_course_objects as $v){
                    $share_course_object_list[$v['OrganizationShare']['share_type_id']]=$v['OrganizationShare'];
                    if(!empty($v['OrganizationShare']['share_user']))$share_course_user_ids[]=$v['OrganizationShare']['share_user'];
                    if(!empty($v['OrganizationShare']['organization_id']))$share_course_organization_ids[]=$v['OrganizationShare']['organization_id'];
                }
                if(!empty($share_course_user_ids)){
                    $share_course_user_infos=$this->User->find('all',array('fields'=>'id,name,first_name,mobile,email','conditions'=>array('User.id'=>$share_course_user_ids)));
                    foreach($share_course_user_infos as $v){
                        $share_course_user_list[$v['User']['id']]=$v['User'];
                    }
                }
                if(!empty($share_course_organization_ids))$share_course_organization_list=$this->Organization->find('list',array('fields'=>'id,name','conditions'=>array('id'=>$share_course_organization_ids)));
                $this->set('share_course_user_list',$share_course_user_list);
                $this->set('share_course_organization_list',$share_course_organization_list);
                $this->set('share_course_object_list',$share_course_object_list);
            }
            $this->set('share_course',$share_couser_ids);
        }
        $this->set('user_course_view_ids',$couse_class_detail_infos);
    }

    function get_course_count(){
        $user_id = $_SESSION['User']['User']['id'];
        $share_couser_ids=array();
        $share_couser_cond=array();
        $user_member_list = $this->OrganizationMember->find('list',array('fields'=>'OrganizationMember.organization_id,OrganizationMember.id','conditions'=>array('OrganizationMember.user_id'=>$user_id,'OrganizationMember.status'=>'1')));
        if(!empty($user_member_list)){
            $user_member_organization=array_keys($user_member_list);
            $user_member_department=$this->OrganizationMemberJob->find('list',array('fields'=>'organization_department_id','conditions'=>array('organization_department_id <>'=>0,'organization_id'=>$user_member_organization,'organization_member_id'=>$user_member_list)));
            $share_couser_cond['or'][]=array(
                'share_object'=>'0',
                'share_object_ids'=>$user_member_list
            );
            if(!empty($user_member_department)){
                $share_couser_cond['or'][]=array(
                    'share_object'=>'1',
                    'share_object_ids'=>$user_member_department
                );
            }
        }
        $manager_organization_ids=$this->Organization->find('list',array('fields'=>'id','conditions'=>array('manage_user'=>$user_id,'status'=>'1')));
        if(!empty($manager_organization_ids)){
            $share_couser_cond['or'][]=array(
                'share_object'=>'2',
                'share_object_ids'=>$manager_organization_ids
            );
        }
        if(!empty($share_couser_cond)){
            $share_couser_cond['OrganizationShare.share_type']='course';
            $share_couser_cond['OrganizationShare.share_type_id <>']='0';
            $share_couser_ids=$this->OrganizationShare->find('list',array('conditions'=>$share_couser_cond,'fields'=>'share_type_id'));
        }
        $user_course_view_cond=array();
        $user_course_view_cond['UserCourseClass.status <>']='0';
        $user_course_view_cond['UserCourseClass.user_id']=$user_id;
        $user_course_view_cond['UserCourseClass.course_id >']=0;
        $user_course_view_list=$this->UserCourseClass->find('list',array('conditions'=>$user_course_view_cond,'fields'=>'course_id,id'));
        $user_course_view_ids=array_keys($user_course_view_list);
        $conditions=array();
        $conditions['or'][]['Course.user_id']=$user_id;
        $conditions['Course.status']='1';
        if(!empty($share_couser_ids))$conditions['or'][]['Course.id']=$share_couser_ids;
        if(!empty($user_course_view_ids))$conditions['or'][]['Course.id']=$user_course_view_ids;
        $UserCourse_lists=$this->Course->find('count',array('conditions'=>$conditions));
        die(json_encode($UserCourse_lists));
    }

    function ajax_course_complete(){
		//登录验证
		$this->checkSessionUser();
		Configure::write('debug',1);
		$this->layout="ajax";
		$result=array();
		$result['code']="0";
		$result['message']=$this->ld['submit'].$this->ld['failed'];
		$post_data=isset($_POST['data']['CourseComment'])?$_POST['data']['CourseComment']:array();
		if(!empty($post_data)){
			$user_id=$_SESSION['User']['User']['id'];
			$course_id=$post_data['course_id'];
			$course_comment_info=$this->CourseComment->find('first',array('conditions'=>array('CourseComment.user_id'=>$user_id,'CourseComment.course_id'=>$course_id)));
			$post_data['id']=isset($course_comment_info['CourseComment'])?$course_comment_info['CourseComment']['id']:0;
			$post_data['user_id']=$_SESSION['User']['User']['id'];
			$this->CourseComment->save($post_data);
			$this->UserCourseClass->updateAll(array('UserCourseClass.status'=>'3'),array('UserCourseClass.user_id'=>$_SESSION['User']['User']['id'],'UserCourseClass.course_id'=>$course_id));
			$result['code']="1";
			$result['message']=$this->ld['submit'].$this->ld['successfully'];
		}
		die(json_encode($result));
    }

    //课程管理页
    function course_management(){
        //登录验证
        $this->checkSessionUser();
        $_GET=$this->clean_xss($_GET);
        //$this->layout = 'usercenter';//引入模版
        $this->page_init();                        //页面初始化
        $this->pageTitle = '课程管理 - '.$this->configs['shop_title'];
        //当前位置开始
        if(isset($_GET['organizations_id'])){
            $this->ur_heres[] = array('name' => '用户中心', 'url' => '/users/index');
            $this->ur_heres[] = array('name' => '我的组织', 'url' => '/organizations/');
            $this->ur_heres[] = array('name' => '课程管理', 'url' => '');
        }else{
            $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
            $this->ur_heres[] = array('name' => '我的课程', 'url' => '');
        }
        $user_id=$_SESSION['User']['User']['id'];
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        if ($user_list['User']['rank'] > 0) {
            $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
            $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
        }
        $this->set('user_list', $user_list);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($user_id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($user_id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($user_id);
        $this->set('focuscount', $focus);
        $organizations_id = isset($_GET['organizations_id'])?$_GET['organizations_id']:'0';
        $user_member_list = $this->OrganizationMember->find('list',array('fields'=>'OrganizationMember.id','conditions'=>array('OrganizationMember.user_id'=>$user_id)));
        $my_jobs = $this->OrganizationMemberJob->find('list',array('fields'=>'OrganizationMemberJob.organization_department_id','conditions'=>array('OrganizationMemberJob.organization_member_id'=>$user_member_list)));
        $my_jobs = array_unique($my_jobs);
        $user_manage_list = $this->OrganizationDepartment->find('list',array('fields'=>'OrganizationDepartment.id','conditions'=>array('OrganizationDepartment.id'=>$my_jobs)));
        $user_organization_list = $this->Organization->find('list',array('fields'=>'Organization.id','conditions'=>array('Organization.manage_user'=>$user_id)));
        $organization_share_conditions = array('OrganizationShare.share_type'=>'course');
        if(sizeof($user_member_list)>0){
            $organization_share_conditions['or'][] = array(
                'OrganizationShare.organization_id'=>$organizations_id,
                'OrganizationShare.share_object'=>0,
                'OrganizationShare.share_object_ids'=>$user_member_list
            );
        }
        if(sizeof($user_manage_list)>0){
            $organization_share_conditions['or'][] = array(
                'OrganizationShare.organization_id'=>$organizations_id,
                'OrganizationShare.share_object'=>1,
                'OrganizationShare.share_object_ids'=>$user_manage_list
            );
        }
        if(sizeof($user_organization_list)>0){
            $organization_share_conditions['or'][] = array(
                'OrganizationShare.share_object'=>2,
                'OrganizationShare.share_object_ids'=>$organizations_id
            );
        }
        $this->set('organizations_id', $organizations_id);
        $organizations_name = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organizations_id)));
        $this->set('organizations_name', $organizations_name);
        $course_share=$this->OrganizationShare->find('list',array('fields'=>'OrganizationShare.share_type_id','conditions'=>$organization_share_conditions));
        $course_share = array_unique($course_share);
        $organization_relations = $this->OrganizationRelation->find('list',array('fields'=>'type_id','conditions'=>array('OrganizationRelation.organization_id'=>$organizations_id,'OrganizationRelation.type'=>'course')));
        $course_cansee_conditions = array('Course.status'=>1);
        if(!empty($organization_relations)){
            $course_cansee_conditions['or'][]=array(
                'Course.id'=>$organization_relations
            );
        }
        $organization_share_conditions = array('OrganizationShare.share_type'=>'course');
        $course_cansee_conditions['or'][] = array(
            'Course.visibility'=>0,
            'Course.id'=>$course_share
        );
        $course_cansee_conditions['or'][] = array(
            'Course.visibility'=>0,
            'Course.user_id'=>$organizations_name['Organization']['manage_user']
        );
        $course_cansee_conditions['or'][] = array(
            'Course.visibility'=>1,
            'Course.user_id'=>$_SESSION['User']['User']['id']
        );
        if($organizations_name['Organization']['manage_user']==$_SESSION['User']['User']['id']){
            $course_cansee_conditions['or'][] = array(
                'Course.visibility'=>2,
                'Course.user_id'=>$organizations_name['Organization']['manage_user']
            );
        }
        $course_cansee_conditions['or'][] = array(
            'Course.visibility'=>2,
            'Course.id'=>$course_share
        );
        $course_list = $this->Course->find('all', array('order' => 'created desc','conditions'=>$course_cansee_conditions));
        if(!empty($course_list)){
            foreach($course_list as $k=>$v){
                $course_list[$k]['Course']['class_count']=$this->CourseClass->find('count', array('conditions' =>array("CourseClass.course_code"=>$v['Course']['code'])));
                $course_list[$k]['Course']['chapter_count']=$this->CourseChapter->find('count', array('conditions' =>array("CourseChapter.course_code"=>$v['Course']['code'])));
            }
        }
        $this->set('course_list', $course_list);
        if(isset($_GET['get_course_num'])&&$_GET['get_course_num'] == 1){
            Configure::write('debug',1);
            $this->layout = 'ajax';
            die(json_encode(count($course_list)));
        }
        if(empty($organizations_name))$this->redirect('/organizations/index');
        $organization_actions=$this->Organization->manager_operator($organizations_id,$user_id);
        $this->set('organization_actions',$organization_actions);
        if(!in_array('course',$organization_actions))$this->redirect('/organizations/view/'.$organizations_id);
        $import_course_list = $this->Course->import_course_list('O',$organizations_id);
        $this->set('import_course_list', $import_course_list);
        //课程分享
        $user_id = $_SESSION['User']['User']['id'];
        $org_info = $this->Organization->find('all',array('conditions'=>array('Organization.manage_user'=>$user_id)));
        foreach ($org_info as $k => $v) {
            $org_info_check[$v['Organization']['id']] = $v;
        }
        $jorg_info = $this->OrganizationMember->find('all',array('conditions'=>array('OrganizationMember.user_id'=>$user_id,'OrganizationMember.status'=>1)));
        if(isset($jorg_info)&&count($jorg_info)>0){
            $cons = array();
            foreach ($jorg_info as $k11 => $v11) {
                $cons['and']['Organization.id'][] = $v11['OrganizationMember']['organization_id'];
            }
            $org_infomation = $this->Organization->find('all',array('conditions'=>$cons));
        }
        if(isset($org_infomation)&&count($org_infomation)>0){
            foreach ($org_infomation as $k2 => $v2) {
                if(isset($org_info_check[$v2['Organization']['id']]) == false){
                    $org_info[]=$v2;
                }
            }
        }
        $this->set('org_info',$org_info);
        foreach ($org_info as $kk1 => $vv1) {
            $check_org[$vv1['Organization']['id']] = $vv1['Organization']['name'];
        }
        if(isset($check_org)){
            $this->set('check_org',$check_org);
        }
        $org_id = $organizations_id;
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
        $manage_ids[]=$organization_info['Organization']['manage_user'];
        $this->set('manager_ids',$manage_ids);
        $this->set('orga_id',$organizations_id);
        $organization_info = $this->Organization->find('first', array('conditions' => array('Organization.id' => $organizations_id)));
        $this->set('organization_info',$organization_info);
    }

    //添加课程
    function add(){
        //登录验证
        $this->checkSessionUser();
        $_GET=$this->clean_xss($_GET);
        //$this->layout = 'usercenter';//引入模版
        $this->page_init();                        //页面初始化
        $this->pageTitle = '我的课程 - '.$this->configs['shop_title'];
        //当前位置开始
        if(isset($_GET['organizations_id'])){
            $this->ur_heres[] = array('name' => '我的组织', 'url' => '/organizations/');
            $this->ur_heres[] = array('name' => '课程管理', 'url' => '/courses/course_management?organizations_id='.$_GET['organizations_id']);
            $this->ur_heres[] = array('name' => '课程添加', 'url' => '');
        }else{
            $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
            $this->ur_heres[] = array('name' => '我的课程', 'url' => '/courses/course_log');
            $this->ur_heres[] = array('name' => '课程添加', 'url' => '');
        }
        $user_id=$_SESSION['User']['User']['id'];
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        if ($user_list['User']['rank'] > 0) {
            $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
            $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
        }
        $this->set('user_list', $user_list);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($user_id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($user_id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($user_id);
        $this->set('focuscount', $focus);
        $this->menu_path = array('root' => '/hr/','sub' => '/courses/');
        $this->set('title_for_layout', $this->ld['add'].'-课程管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "资源开发",'url' => '');
        $this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
        $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        $course_type=$this->CourseType->course_type_list();
        $course_category=$this->CourseCategory->find('all',array('conditions'=>array('CourseCategory.status'=>'1')));
        $organizations_id = isset($_GET['organizations_id'])?$_GET['organizations_id']:'';
        $organizations_name = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organizations_id)));
        $this->set('organizations_name', $organizations_name);
        $this->set('organizations_id', $organizations_id);
        if(!empty($organizations_id)){
            if(empty($organizations_name))$this->redirect('/organizations/index');
            $organization_actions=$this->Organization->manager_operator($organizations_id,$user_id);
            $this->set('organization_actions',$organization_actions);
            if(!in_array('course',$organization_actions))$this->redirect('/organizations/view/'.$organizations_id);
        }
        if ($this->RequestHandler->isPost()){
            if($this->data['Course']['course_type_code']=='-1'){
                $course_type_add['CourseType'] = array(
                    'user_id'=>$_SESSION['User']['User']['id'],
                    'code'=>$_POST['course_type_code_1'],
                    'name'=>$_POST['course_type_code_1'],
                    'status'=>1
                );
                $this->CourseType->save($course_type_add);
                $this->data['Course']['course_type_code'] = $_POST['course_type_code_1'];
            }
            if($this->data['Course']['course_category_code']=='-1'){
                $course_categorie_add['CourseCategory'] = array(
                    'user_id'=>$_SESSION['User']['User']['id'],
                    'parent_id'=>0,
                    'code'=>$_POST['course_category_code_1'],
                    'name'=>$_POST['course_category_code_1'],
                    'status'=>1
                );
                $this->CourseCategory->save($course_categorie_add);
                $this->data['Course']['course_category_code'] = $_POST['course_category_code_1'];
            }
            $this->Course->save($this->data);
            $course_id=$this->Course->id;
            if($organizations_id!=''){
                $organization_relations['OrganizationRelation'] = array(
                    'id'=>0,
                    'organization_id'=>$organizations_id,
                    'type'=>'course',
                    'type_id'=>$course_id
                );
                $this->OrganizationRelation->save($organization_relations);
            }
            if(empty($this->data['Course']['id'])){
                $course_code='course_'.$course_id;
                $this->Course->updateAll(array('code'=>"'".$course_code."'"),array('id'=>$course_id));
            }
            if(isset($_GET['organizations_id'])){
                $this->redirect('/courses/edit/'.$course_id.'?organizations_id='.$organizations_id);
            }else{
                $this->redirect('/courses/edit/'.$course_id);
            }
        }
        $resource_info=$this->InformationResource->code_information_formated('course_level',$this->locale,false);
        $this->set('resource_info',$resource_info);
        $this->set('course_type', $course_type);
        $this->set('course_category', $course_category);
        $manager_ids[]=$organizations_name['Organization']['manage_user'];
        $this->set('org_manager',$organizations_name['Organization']['manage_user']);
        $departs = $this->OrganizationDepartment->find('all',array('conditions'=>array('OrganizationDepartment.organization_id'=>$organizations_id)));
        if(isset($departs)&&count($departs)>0){
            foreach ($departs as $k => $v) {
                if($v['OrganizationDepartment']['department_manage'] != 0){
                    $manager_ids[]=$v['OrganizationDepartment']['department_manage'];
                }
            }
        }
        $this->set('manager_ids',$manager_ids);
        $this->set('orga_id',$organizations_id);
        $organization_info = $this->Organization->find('first', array('conditions' => array('Organization.id' => $organizations_id)));
        $this->set('organization_info',$organization_info);
        $this->get_manager($organizations_id);
    }

    //删除课程
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $course_info = $this->Course->findById($id);
        $this->OrganizationRelation->deleteAll(array('type' => 'course','type_id'=>$id));
        $this->Course->deleteAll(array('id' => $id));
        $this->CourseChapter->deleteAll(array('CourseChapter.course_code' => $course_info["Course"]["code"]));
        $this->CourseClass->deleteAll(array('CourseClass.course_code' => $course_info["Course"]["code"]));
        //操作员日志
        $result['flag'] = 1;
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/courses/');
        }
    }

    //删除学习课程
    public function remove_study($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        //操作员日志
        $UserCourseClass_id = $this->UserCourseClass->find('first',array('conditions'=>array('UserCourseClass.course_id' => $id,'UserCourseClass.user_id'=>$_SESSION['User']['User']['id'])));
        $this->UserCourseClass->deleteAll(array('UserCourseClass.id' => $UserCourseClass_id['UserCourseClass']['id']));
        $this->UserCourseClassDetail->deleteAll(array('UserCourseClassDetail.user_course_class_id' => $UserCourseClass_id['UserCourseClass']['id']));
        $result['flag'] = 1;
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/courses/');
        }
    }

    //删除课程分享记录
    public function remove_share($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $member = $this->OrganizationMember->find('list',array('fields'=>'id','conditions'=>array('OrganizationMember.user_id'=>$_SESSION['User']['User']['id'])));
        $course_share = $this->OrganizationShare->find('all',array('conditions'=>array('OrganizationShare.share_type'=>'course','OrganizationShare.share_type_id'=>$id,'OrganizationShare.share_object_ids'=>$member)));
        $remove_share = array();
        foreach ($course_share as $k => $v) {
            $remove_share[] = $v['OrganizationShare']['id'];
        }
        $this->OrganizationShare->deleteAll(array('OrganizationShare.id' => $remove_share));
        //操作员日志
        $result['flag'] = 1;
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/courses/');
        }
    }

    //课程编辑
    public function edit($id){
        //登录验证
        $this->checkSessionUser();
        $_GET=$this->clean_xss($_GET);
        //$this->layout = 'usercenter';//引入模版
        $this->page_init();                        //页面初始化
        $this->pageTitle = '我的课程 - '.$this->configs['shop_title'];
        //当前位置开始
        $user_id=$_SESSION['User']['User']['id'];
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        if ($user_list['User']['rank'] > 0) {
            $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
            $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
        }
        $this->set('user_list', $user_list);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($user_id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($user_id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($user_id);
        $this->set('focuscount', $focus);
        $course_type=$this->CourseType->course_type_list();
        $course_category=$this->CourseCategory->course_category_list();
        $course_info=$this->Course->find('first',array('conditions'=>array('Course.id'=>$id)));
        $course_chapter_info=$this->CourseChapter->find('all',array('conditions'=>array('CourseChapter.course_code'=>$course_info["Course"]["code"]),'order' => 'CourseChapter.orderby asc'));
        $course_chapter_data=array();
        if(!empty($course_chapter_info)){
            foreach ($course_chapter_info as $kk=>$vv) {
                $course_chapter_data[$vv['CourseChapter']['code']]['CourseChapter']=$vv['CourseChapter'];
                $course_chapter_data[$vv['CourseChapter']['code']]['CourseClass'][]=$vv['CourseClass'];
            }
        }
        $organizations_id = isset($_GET['organizations_id'])?$_GET['organizations_id']:'0';
        $organizations_name = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organizations_id)));
        $this->set('organizations_name', $organizations_name);
        $this->set('organizations_id',$organizations_id);
        $organization_actions=$this->Organization->manager_operator($organizations_id,$user_id);
        $this->set('organization_actions',$organization_actions);
        if(isset($_GET['organizations_id'])&&!empty($_GET['organizations_id'])){
            if(empty($organizations_name))$this->redirect('/organizations/index');
            if(!in_array('course',$organization_actions))$this->redirect('/organizations/view/'.$organizations_id);
        }
        $course_class_log=$this->UserCourseClass->find('all',array('conditions'=>array('UserCourseClass.course_id'=>$id)));
        if ($this->RequestHandler->isPost()) {
            if($this->data['Course']['course_type_code']=='-1'){
                $course_type_add['CourseType'] = array(
                    'user_id'=>$_SESSION['User']['User']['id'],
                    'code'=>$_POST['course_type_code_1'],
                    'name'=>$_POST['course_type_code_1'],
                    'status'=>1
                );
                $this->CourseType->save($course_type_add);
                $this->data['Course']['course_type_code'] = $_POST['course_type_code_1'];
            }
            if($this->data['Course']['course_category_code']=='-1'){
                $course_categorie_add['CourseCategory'] = array(
                    'user_id'=>$_SESSION['User']['User']['id'],
                    'parent_id'=>0,
                    'code'=>$_POST['course_category_code_1'],
                    'name'=>$_POST['course_category_code_1'],
                    'status'=>1
                );
                $this->CourseCategory->save($course_categorie_add);
                $this->data['Course']['course_category_code'] = $_POST['course_category_code_1'];
            }
            $this->Course->save($this->data);
            if(isset($_GET['organizations_id'])){
                $this->redirect('/courses/course_management/?organizations_id='.$organizations_id);
            }else{
                $this->redirect('/courses/course_log/');
            }
        }
        $resource_info=$this->InformationResource->information_formated(array('courseware_type','course_level'),false);
        $profile_code="course_class_upload";
        $users_list = $this->User->find('all');
        $member = $this->OrganizationMember->find('all');
        $this->set('resource_info',$resource_info);
        $this->set('course_class_log', $course_class_log);
        $this->set('course_chapter_info', $course_chapter_info);
        $this->set('course_info', $course_info);
        $this->set('course_type', $course_type);
        $this->set('course_category', $course_category);
        $this->set('users_list', $users_list);
        $this->set('course_chapter_data', $course_chapter_data);
        $this->set('member', $member);
        
        $manager_ids[]=$organizations_name['Organization']['manage_user'];
        $this->set('org_manager',$organizations_name['Organization']['manage_user']);
        $departs = $this->OrganizationDepartment->find('all',array('conditions'=>array('OrganizationDepartment.organization_id'=>$organizations_id)));
        if(isset($departs)&&count($departs)>0){
            foreach ($departs as $k => $v) {
                if($v['OrganizationDepartment']['department_manage'] != 0){
                    $manager_ids[]=$v['OrganizationDepartment']['department_manage'];
                }
            }
        }
        $this->set('manager_ids',$manager_ids);
        $this->set('orga_id',$organizations_id);
        $organization_info = $this->Organization->find('first', array('conditions' => array('Organization.id' => $organizations_id)));
        $this->set('organization_info',$organization_info);
        if(isset($_GET['organizations_id'])){
            $this->ur_heres[] = array('name' => '我的组织', 'url' => '/organizations/');
            $this->ur_heres[] = array('name' => '课程管理', 'url' => '/courses/course_management?organizations_id='.$_GET['organizations_id']);
            $this->ur_heres[] = array('name' => $course_info['Course']['name'], 'url' => '');
        }else{
            $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
            $this->ur_heres[] = array('name' => '我的课程', 'url' => '/courses/course_log');
            $this->ur_heres[] = array('name' => $course_info['Course']['name'], 'url' => '');
        }
    }
    //新增章节

    public function get_depart(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!empty($_POST)){
            if(isset($_POST['org_id'])&&$_POST['org_id']!=''){
                $org_id = $_POST['org_id'];
                //pr($org_id);exit();
                $cons['and']['OrganizationDepartment.organization_id'] = $org_id;
                $con['and']['OrganizationMember.organization_id'] = $org_id;
                $depart_info = $this->OrganizationDepartment->find('all',array('conditions'=>$cons));
                $mem_info = $this->OrganizationMember->find('all',array('conditions'=>$con));
            }
        }
        die(json_encode($depart_info));
    }

    public function get_job(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!empty($_POST)){
            if(isset($_POST['depart_id'])&&$_POST['depart_id']!=''){
                $depart_id = $_POST['depart_id'];
                $cons['and']['OrganizationJob.organization_department_id'] = $depart_id;
                $job_info = $this->OrganizationJob->find('all',array('conditions'=>$cons,'order'=>'OrganizationJob.organization_department_id'));
            }
        }
        die(json_encode($job_info));
    }

    public function download_share_csv_example()
    {
        $newdatas = array();
        $mem_info = $this->OrganizationMember->find('all', array('limit' => 5));
        $ch[]='姓名';
        $ch[]='手机号';
        $newdatas[] = $ch;
        $filename = '课程分享'.date('Ymd').'.csv';
        foreach ($mem_info as $k => $v) {
            $ch = '';
            $ch[]=$v['OrganizationMember']['name'];
            $ch[]=$v['OrganizationMember']['mobile'];
            $newdatas[]=$ch;
        }
        $this->Phpcsv->output($filename, $newdatas);
        exit;
    }

    public function get_mem(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $cons = array();
        $mem_info = array();
        if(!empty($_POST)){
            if(isset($_POST['org_id'])&&$_POST['org_id']!=''){
                $org_id = $_POST['org_id'];
                $con['and']['OrganizationMember.organization_id'] = $org_id;
                $con['and']['OrganizationMember.status'] = 1;
                $mem_info = $this->OrganizationMember->find('all',array('conditions'=>$con,'order'=>'OrganizationMember.organization_id'));
                $cons['OrganizationMemberJob.organization_id'] = $org_id;
                foreach ($mem_info as $k => $v) {
                    $cons['OrganizationMemberJob.organization_member_id'][] = $v['OrganizationMember']['id'];
                }
                $mem_job_info = $this->OrganizationMemberJob->find('all',array('conditions'=>$cons));
                $job_info = $this->OrganizationDepartment->find('all',array('conditions'=>array('OrganizationDepartment.organization_id'=>$org_id)));
                foreach ($job_info as $k => $v) {
                    $job_info_c[$v['OrganizationDepartment']['id']] = $v;
                }
                foreach ($mem_job_info as $k => $v) {
                    $mem_job_info[$k]['OrganizationMemberJob']['department'] = $job_info_c[$v['OrganizationMemberJob']['organization_department_id']]['OrganizationDepartment']['name'];
                }
                $job_check = array();
                foreach ($mem_job_info as $k => $v) {
                    $job_check[$v['OrganizationMemberJob']['organization_member_id']][]= $v['OrganizationMemberJob']['department'];
                }
                foreach ($job_check as $k => $v) {
                    if(is_array($v)){
                        $job_check[$k] = array_unique($v);
                    }
                }
                foreach ($job_check as $k => $v) {
                    $job_check[$k] = implode(',',$v);
                }
                foreach ($mem_info as $k => $v) {
                    $mem_info[$k]['OrganizationMember']['depart'] = isset($job_check[$v['OrganizationMember']['id']])?$job_check[$v['OrganizationMember']['id']]:'';
                }
            }
            if(isset($_POST['depart_id'])&&$_POST['depart_id']!=''){
                $depart_id = $_POST['depart_id'];
                $orga_info = $this->OrganizationDepartment->find('first',array('conditions'=>array('OrganizationDepartment.id'=>$depart_id)));
                $org_id = $orga_info['OrganizationDepartment']['organization_id'];
                $con['and']['OrganizationMemberJob.organization_department_id'] = $depart_id;
                $mem = $this->OrganizationMemberJob->find('all',array('conditions'=>$con));
                foreach ($mem as $kk => $vv) {
                    $cons['and']['OrganizationMember.id'][] = $vv['OrganizationMemberJob']['organization_member_id'];
                }
                $cons['and']['OrganizationMember.status'] = 1;
                if(isset($cons)&&count($cons)>0){
                    $mem_info = $this->OrganizationMember->find('all',array('conditions'=>$cons,'order'=>'OrganizationMember.organization_id'));
                }else{
                    $mem_info = '';
                }
                $conss['OrganizationMemberJob.organization_id'] = $org_id;
                foreach ($mem_info as $k => $v) {
                    $conss['OrganizationMemberJob.organization_member_id'][] = $v['OrganizationMember']['id'];
                }
                $mem_job_info = $this->OrganizationMemberJob->find('all',array('conditions'=>$conss));
                $job_info = $this->OrganizationDepartment->find('all',array('conditions'=>array('OrganizationDepartment.organization_id'=>$org_id)));
                foreach ($job_info as $k => $v) {
                    $job_info_c[$v['OrganizationDepartment']['id']] = $v;
                }
                foreach ($mem_job_info as $k => $v) {
                    $mem_job_info[$k]['OrganizationMemberJob']['department'] = $job_info_c[$v['OrganizationMemberJob']['organization_department_id']]['OrganizationDepartment']['name'];
                }
                foreach ($mem_job_info as $k => $v) {
                    $job_check[$v['OrganizationMemberJob']['organization_member_id']][]= $v['OrganizationMemberJob']['department'];
                }
                foreach ($job_check as $k => $v) {
                    if(is_array($v)){
                        $job_check[$k] = array_unique($v);
                    }
                }
                foreach ($job_check as $k => $v) {
                    $job_check[$k] = implode(',',$v);
                }
                foreach ($mem_info as $k => $v) {
                    $mem_info[$k]['OrganizationMember']['depart'] = isset($job_check[$v['OrganizationMember']['id']])?$job_check[$v['OrganizationMember']['id']]:'';
                }
            }
            if(isset($_POST['job_id'])&&$_POST['job_id']!=''){
                $job_id = $_POST['job_id'];
                $orga_info = $this->OrganizationJob->find('first',array('conditions'=>array('OrganizationJob.id'=>$job_id)));
                $org_id = $orga_info['OrganizationJob']['organization_id'];
                $con['and']['OrganizationMemberJob.organization_job_id'] = $job_id;
                $mem = $this->OrganizationMemberJob->find('all',array('conditions'=>$con));
                foreach ($mem as $kk => $vv) {
                    $cons['and']['OrganizationMember.id'][] = $vv['OrganizationMemberJob']['organization_member_id'];
                }
                if(isset($cons)&&count($cons)>0){
                    $mem_info = $this->OrganizationMember->find('all',array('conditions'=>$cons,'order'=>'OrganizationMember.organization_id'));
                }else{
                    $mem_info = '';
                }
                $conss['OrganizationMemberJob.organization_id'] = $org_id;
                foreach ($mem_info as $k => $v) {
                    $conss['OrganizationMemberJob.organization_member_id'][] = $v['OrganizationMember']['id'];
                }
                $mem_job_info = $this->OrganizationMemberJob->find('all',array('conditions'=>$conss));
                $job_info = $this->OrganizationDepartment->find('all',array('conditions'=>array('OrganizationDepartment.organization_id'=>$org_id)));
                foreach ($job_info as $k => $v) {
                    $job_info_c[$v['OrganizationDepartment']['id']] = $v;
                }
                foreach ($mem_job_info as $k => $v) {
                    $mem_job_info[$k]['OrganizationMemberJob']['department'] = $job_info_c[$v['OrganizationMemberJob']['organization_department_id']]['OrganizationDepartment']['name'];
                }
                foreach ($mem_job_info as $k => $v) {
                    $job_check[$v['OrganizationMemberJob']['organization_member_id']][]= $v['OrganizationMemberJob']['department'];
                }
                foreach ($job_check as $k => $v) {
                    if(is_array($v)){
                        $job_check[$k] = array_unique($v);
                    }

                }
                foreach ($job_check as $k => $v) {
                    $job_check[$k] = implode(',',$v);
                }
                foreach ($mem_info as $k => $v) {
                    $mem_info[$k]['OrganizationMember']['depart'] = isset($job_check[$v['OrganizationMember']['id']])?$job_check[$v['OrganizationMember']['id']]:'';
                }
            }
        }
        die(json_encode($mem_info));
    }

    function share(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result = array();
        $result['code'] = 0;
        $user_id = $_SESSION['User']['User']['id'];
        if(!empty($_POST)){
            if(isset($_POST['mem_id'])&&$_POST['mem_id']!=''){
                $cons['and']['OrganizationMember.id'] = $_POST['mem_id'];
                $mem_info = $this->OrganizationMember->find('all',array('conditions'=>$cons));
                foreach ($mem_info as $kk => $vv) {
                    $con['and']['Organization.id'][] = $vv['OrganizationMember']['organization_id'];
                    $or_info[$vv['OrganizationMember']['organization_id']][] = $vv['OrganizationMember']['id'];
                }
                $org_info = $this->Organization->find('all',array('conditions'=>$con));
                foreach ($org_info as $k1 => $v1) {
                    $o_info[$v1['Organization']['id']] = $v1['Organization']['abbreviation'];
                }
                foreach ($mem_info as $kkk => $vvv){
                    $mem_name = $vvv['OrganizationMember']['name'];
                    $org_name = $o_info[$vvv['OrganizationMember']['organization_id']];
                    $surl = $this->server_host;
                    $mem_mobile = $vvv['OrganizationMember']['mobile'];
                    $url = $surl.'/courses/view/'.$_POST['course_id'];
                    if($vvv['OrganizationMember']['email'] !=''){
                        $email = $vvv['OrganizationMember']['email'];
                        $Notify_template=$this->NotifyTemplateType->typeformat('course_invite_member','email');
                        if(!empty($Notify_template)){
                            $subject=$Notify_template['email']['NotifyTemplateTypeI18n']['title'];
                            @eval("\$subject = \"$subject\";");
                            $html_body = addslashes($Notify_template['email']['NotifyTemplateTypeI18n']['param01']);
                            @eval("\$html_body = \"$html_body\";");
                            $text_body = $Notify_template['email']['NotifyTemplateTypeI18n']['param02'];
                            @eval("\$text_body = \"$text_body\";");
                        }
                        $mail_send_queue = array(
                            'id' => '',
                            'sender_name' => $this->configs['shop_name'],
                            'receiver_email' => $email,//接收人姓名;接收人地址
                            'cc_email' => "",
                            'bcc_email' => "",
                            'title' => $subject,
                            'html_body' => $html_body,
                            'text_body' => $text_body,
                            'sendas' => 'html',
                            'flag' => 0,
                            'pri' => 0,
                        );
                        $mail_result=$this->Notify->send_email($mail_send_queue, $this->configs);
                    }else{
                        // $Notify_template=$this->NotifyTemplateType->typeformat('course_invite_member','sms');
                        // if(!empty($Notify_template)){
                        //       $content=$Notify_template['sms']['NotifyTemplateTypeI18n']['param02'];
                        //       @eval("\$content = \"$content\";");
                        //   	}
                        //   	//pr($content);exit();
                        // $sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
                        // $sms_result=$this->Notify->send_sms($vvv['OrganizationMember']['mobile'],$content,$sms_kanal,$this->configs);
                    }
                }
                foreach ($or_info as $kk1 => $vv1) {
                    $conss['and']['OrganizationMember.organization_id'][]=$kk1;
                }
                $me_info = $this->OrganizationMember->find('all',array('conditions'=>$conss));
                foreach ($me_info as $kk2 => $vv2) {
                    $memb_info[$vv2['OrganizationMember']['id']] = $vv2['OrganizationMember']['user_id'];
                }
                foreach ($or_info as $k4 => $v4) {
                    $share_info['OrganizationShare']['share_object_ids'] = '';
                    $share_info['OrganizationShare']['id'] = 0;
                    $share_info['OrganizationShare']['organization_id'] = $k4;
                    $share_info['OrganizationShare']['share_user'] = $user_id;
                    $share_info['OrganizationShare']['share_type'] = 'course';
                    $share_info['OrganizationShare']['share_type_id'] = $_POST['course_id'];
                    $share_info['OrganizationShare']['share_object'] = 0;
                    foreach ($v4 as $kk4 => $vv4) {
                        $share_info['OrganizationShare']['share_object_ids'] = $vv4;
                        $this->OrganizationShare->save($share_info);
                    }
                    $result['code'] = 1;
                }
            }
            if(isset($_POST['depart_id'])&&$_POST['depart_id']!=''){
                $depart_info = $this->OrganizationDepartment->find('first',array('conditions'=>array('OrganizationDepartment.id'=>$_POST['depart_id'])));
                $org_info = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$depart_info['OrganizationDepartment']['organization_id'])));
                $con = '';
                if(isset($_POST['depart_mem_id'])&&count($_POST['depart_mem_id'])>0){
                    foreach ($_POST['depart_mem_id'] as $k => $v) {
                        $con['OrganizationMember.id'][]=$v;
                    }
                }
                $depart_mem_info = $this->OrganizationMember->find('all',array('conditions'=>$con));
                $org_name = $org_info['Organization']['name'];
                $surl = $this->server_host;
                $url = $surl.'/courses/view/'.$_POST['course_id'];
                foreach ($depart_mem_info as $k => $v) {
                    $mem_name = $v['OrganizationMember']['name'];
                    if($v['OrganizationMember']['email']!=''){
                        $email = $v['OrganizationMember']['email'];
                        $Notify_template=$this->NotifyTemplateType->typeformat('course_invite_member','email');
                        if(!empty($Notify_template)){
                            $subject=$Notify_template['email']['NotifyTemplateTypeI18n']['title'];
                            @eval("\$subject = \"$subject\";");
                            $html_body = addslashes($Notify_template['email']['NotifyTemplateTypeI18n']['param01']);
                            @eval("\$html_body = \"$html_body\";");
                            $text_body = $Notify_template['email']['NotifyTemplateTypeI18n']['param02'];
                            @eval("\$text_body = \"$text_body\";");
                        }
                        $mail_send_queue = array(
                            'id' => '',
                            'sender_name' => $this->configs['shop_name'],
                            'receiver_email' => $email,//接收人姓名;接收人地址
                            'cc_email' => "",
                            'bcc_email' => "",
                            'title' => $subject,
                            'html_body' => $html_body,
                            'text_body' => $text_body,
                            'sendas' => 'html',
                            'flag' => 0,
                            'pri' => 0,
                        );
                        $mail_result=$this->Notify->send_email($mail_send_queue, $this->configs);
                    }else{
                        // $Notify_template=$this->NotifyTemplateType->typeformat('course_invite_member','sms');
                        // if(!empty($Notify_template)){
                        //       $content=$Notify_template['sms']['NotifyTemplateTypeI18n']['param02'];
                        //       @eval("\$content = \"$content\";");
                        //   	}
                        //   	//pr($content);exit();
                        // $sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
                        // $sms_result=$this->Notify->send_sms($v['OrganizationMember']['mobile'],$content,$sms_kanal,$this->configs);
                    }

                }
                $share_info['OrganizationShare']['id'] = 0;
                $share_info['OrganizationShare']['organization_id'] = $org_info['Organization']['id'];
                $share_info['OrganizationShare']['share_user'] = $user_id;
                $share_info['OrganizationShare']['share_type'] = 'course';
                $share_info['OrganizationShare']['share_type_id'] = $_POST['course_id'];
                $share_info['OrganizationShare']['share_object'] = 1;
                $share_info['OrganizationShare']['share_object_ids'] = $_POST['depart_id'];
                $this->OrganizationShare->save($share_info);
                $result['code'] = 1;
            }
            if(isset($_POST['org_id'])&&$_POST['org_id']!=''){
                $organ_info = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$_POST['org_id'])));
                $organ_mem_info = $this->OrganizationMember->find('all',array('conditions'=>array('OrganizationMember.organization_id'=>$_POST['org_id'],'OrganizationMember.status'=>1)));
                if(isset($organ_info)&&$organ_info!=''){
                    $org_name = $organ_info['Organization']['name'];
                    $surl = $this->server_host;
                    $url = $surl.'/courses/view/'.$_POST['course_id'];
                    foreach ($organ_mem_info as $k => $v) {
                        if($v['OrganizationMember']['email']!=''){
                            $mem_name = $v['OrganizationMember']['name'];
                            $email = $v['OrganizationMember']['email'];
                            $Notify_template=$this->NotifyTemplateType->typeformat('course_invite_member','email');
                            if(!empty($Notify_template)){
                                $subject=$Notify_template['email']['NotifyTemplateTypeI18n']['title'];
                                @eval("\$subject = \"$subject\";");
                                $html_body = addslashes($Notify_template['email']['NotifyTemplateTypeI18n']['param01']);
                                @eval("\$html_body = \"$html_body\";");
                                $text_body = $Notify_template['email']['NotifyTemplateTypeI18n']['param02'];
                                @eval("\$text_body = \"$text_body\";");
                            }
                            $mail_send_queue = array(
                                'id' => '',
                                'sender_name' => $this->configs['shop_name'],
                                'receiver_email' => $email,//接收人姓名;接收人地址
                                'cc_email' => "",
                                'bcc_email' => "",
                                'title' => $subject,
                                'html_body' => $html_body,
                                'text_body' => $text_body,
                                'sendas' => 'html',
                                'flag' => 0,
                                'pri' => 0,
                            );
                            $mail_result=$this->Notify->send_email($mail_send_queue, $this->configs);
                        }else{
                            // $Notify_template=$this->NotifyTemplateType->typeformat('course_invite_member','sms');
                            // if(!empty($Notify_template)){
                            //       $content=$Notify_template['sms']['NotifyTemplateTypeI18n']['param02'];
                            //       @eval("\$content = \"$content\";");
                            //   	}
                            //   	//pr($content);exit();
                            // $sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
                            // $sms_result=$this->Notify->send_sms($v['OrganizationMember']['mobile'],$content,$sms_kanal,$this->configs);
                        }

                    }
                    $share_info['OrganizationShare']['id'] = 0;
                    $share_info['OrganizationShare']['organization_id'] = $organ_info['Organization']['id'];
                    $share_info['OrganizationShare']['share_user'] = $user_id;
                    $share_info['OrganizationShare']['share_type'] = 'course';
                    $share_info['OrganizationShare']['share_type_id'] = $_POST['course_id'];
                    $share_info['OrganizationShare']['share_object'] = 2;
                    $share_info['OrganizationShare']['share_object_ids'] = $organ_info['Organization']['id'];
                    $this->OrganizationShare->save($share_info);
                    $result['code'] = 1;
                }
            }
        }
        die(json_encode($result));
    }

    function preview($code){
        $this->menu_path = array('root' => '/hr/','sub' => '/courses/');
        $this->set('title_for_layout', $this->ld['edit'].'-课程管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "资源开发",'url' => '');
        $this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
        $this->navigations[] = array('name' => "上传",'url' => '');
        $this->set('code',$code);
        $profile_code="course_class_upload";
        $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id','Profile.code'), 'conditions' => array('Profile.code' => $profile_code, 'Profile.status' => 1)));
        $fields_info=array();
        $fields_desc_info=array();
        if(!empty($profile_info)){
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array( 'ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
            foreach($profilefiled_info as $v){
                $fields_info[$v['ProfileFiled']['code']]=$v['ProfilesFieldI18n']['description'];
                $fields_desc_info[$v['ProfilesFieldI18n']['description']]=$v['ProfileFiled']['code'];
            }
        }
        if(empty($profile_info))$this->redirect('upload/'.$code);
        $preview_data=array();
        if (!empty($_FILES['course_class'])) {
            if ($_FILES['course_class']['error'] > 0) {
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['upload'].$this->ld['failed']."');window.location.href='/courses/course_management';</script>";
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
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('文件格式错误');window.location.href='/courses/course_management';</script>";
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

    public function batch_upload($code){
        Configure::write('debug',1);
        $this->layout="ajax";
        if ($this->RequestHandler->isPost()) {
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
                    $upload_num++;
                }
            }
            if($upload_num==0){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['upload'].'失败'."');window.location.href='/courses/course_management';</script>";
                die();
            }else{
                $upload_message="(".($upload_num).'/'.(sizeof($checkboxs)).")";
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['upload'].'成功'.$upload_message."');window.location.href='/courses/course_management';</script>";
                die();
            }
        }else{
            $this->redirect('/');
        }
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

    public function download_csv_example($code=""){
        Configure::write('debug',1);
        $this->layout="ajax";
        $profile_code="course_class_upload";
        $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id','Profile.code'), 'conditions' => array('Profile.code' => $profile_code, 'Profile.status' => 1)));
        $fields_info=array();
        if(!empty($profile_info)){
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code','ProfilesFieldI18n.description'), 'conditions' => array( 'ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
            foreach($profilefiled_info as $v){
                if($v['ProfilesFieldI18n']['description']!=''){
                    $fields_info[$v['ProfileFiled']['code']]=$v['ProfilesFieldI18n']['description'];
                }
            }
        }
        if(empty($profile_info))$this->redirect('upload');
        $newdatas=array();
        $tmp=array();
        foreach($fields_info as $k=>$v){
            $tmp[]=$v;
        }
        $newdatas[]=$tmp;
        if($code!=""){
            $chapter_info=$this->CourseChapter->find('all',array('conditions' => array('CourseChapter.course_code' => $code)));
        }else{
            $chapter_info=$this->CourseChapter->find('all',array('limit'=>5));
        }
        $data=array();
        if(!empty($chapter_info)){
            $i=0;
            foreach($chapter_info as $v){
                $class_info=$this->CourseClass->find('all',array('conditions' => array('CourseClass.chapter_code' => $v['CourseChapter']['code'])));
                if(isset($class_info)&&!empty($class_info)){
                    foreach($class_info as $kk=>$vv){
                        $data[$i]['CourseChapter']['code']=$v['CourseChapter']['code'];
                        $data[$i]['CourseChapter']['name']=$v['CourseChapter']['name'];
                        $data[$i]['CourseClass']['code']=$vv['CourseClass']['code'];
                        $data[$i]['CourseClass']['name']=$vv['CourseClass']['name'];
                        $i++;
                    }
                }
            }
            foreach($data as $k=>$v){
                $course_data=array();
                foreach($fields_info as $kk=>$vv){
                    $field_codes=explode('.',$kk);
                    $field_model=isset($field_codes[0])?$field_codes[0]:'';
                    $field_name=isset($field_codes[1])?$field_codes[1]:'';
                    $course_data[]=isset($v[$field_model][$field_name])?$v[$field_model][$field_name]:'';
                }
                $newdatas[]=$course_data;
            }
        }
        //定义文件名称
        $nameexl = 'course'.date('Ymd').'.csv';
        $this->Phpcsv->output($nameexl, $newdatas);
        die();
    }

    function courses_comment(){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$course_id=isset($_POST['course_id'])?$_POST['course_id']:0;
		$course_class_id=isset($_POST['course_class_id'])?$_POST['course_class_id']:0;
		$this->set('course_id',$course_id);
		$this->set('course_class_id',$course_class_id);
		$user_id = isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
		
		$course_data=$this->Course->find('first',array('conditions'=>array('Course.id'=>$course_id,'Course.status'=>'1')));
		if(!empty($course_data)){
			$course_code=$course_data['Course']['code'];
			$course_class_detail=$this->CourseClass->find('first',array('fields'=>'CourseClass.id,CourseClass.name','conditions'=>array('CourseClass.id'=>$course_class_id,'CourseClass.course_code'=>$course_code,'CourseClass.status'=>'1')));
			$this->set('course_class_detail',$course_class_detail);
		}
		
		$note_conditions=array();
		$note_conditions['and']['CourseNote.course_id']=$course_id;
		$note_conditions['and']['CourseNote.course_class_id']=$course_class_id;
		$note_conditions['and']['CourseNote.user_id >']=0;
		$note_conditions['and']['or'][]=array(
			'CourseNote.user_id'=>$user_id
		);
		$note_conditions['and']['or'][]=array(
			'CourseNote.user_id <>'=>$user_id,
			'CourseNote.is_public'=>'0'
		);
		$course_note = $this->CourseNote->find('all',array('conditions'=>$note_conditions,'order'=>'CourseNote.id'));
		if(!empty($course_note)){
			$course_note_user = array();
			foreach ($course_note as $k => $v) {
				$course_note_user[] = $v['CourseNote']['user_id'];
			}
			$user_list = $this->User->find('all',array('fields'=>'id,name,img01','conditions'=>array('User.id'=>$course_note_user)));
			$user_note_list = array();
			foreach ($user_list as $k => $v) {
				$user_note_list[$v['User']['id']] = array('name'=>$v['User']['name'],'img01'=>$v['User']['img01']);
			}
			$this->set('user_note_list',$user_note_list);
		}
		$this->set('course_note',$course_note);
		
		$conditions=array();
		$conditions['UserCourseClass.user_id']=$user_id;
		$conditions['UserCourseClass.course_id']=$course_id;
		$conditions['UserCourseClass.status <>']='0';
		$conditions['UserCourseClassDetail.course_class_id']=$course_class_id;
		$user_course_class=$this->UserCourseClassDetail->find('first',array('fields'=>array('UserCourseClassDetail.id','UserCourseClassDetail.status','UserCourseClassDetail.read_time','UserCourseClass.course_id','UserCourseClassDetail.course_class_id'),'conditions'=>$conditions));
		if(!empty($user_course_class))$this->set('user_course_class',$user_course_class);
    }

    function ajax_add_course_note(){
	        Configure::write('debug', 1);
	        $this->layout = 'ajax';
	        $this->loadModel('CourseNote');
	        $result=array();
	        $result['code']='0';
	        $result['message']=$this->ld['send_failed'];
	        $post_data=isset($_POST['data']['CourseNote'])?$_POST['data']['CourseNote']:array();
	        
	        if(!empty($post_data)&&isset($_SESSION)){
			if(isset($_FILES['CourseNoteMedia'])&&$_FILES['CourseNoteMedia']['error']=='0'){
				$mediaInfo=pathinfo($_FILES['CourseNoteMedia']['name']);
				$mediaName=md5($mediaInfo['filename'].time()).".".$mediaInfo['extension'];
				$media_root=WWW_ROOT.'media/CourseNoteMedia/';
				$this->mkdirs($media_root);
				if (move_uploaded_file($_FILES['CourseNoteMedia']['tmp_name'], $media_root.$mediaName)) {
					$media_path = '/media/CourseNoteMedia/'.$mediaName;
					$post_data['media']=$media_path;
				}
			}
			$this->CourseNote->save($post_data);
			$result['code']='1';
			$result['message']=$this->ld['send_success'];
	        }
	        die(json_encode($result));
    }

    public function remove_note($id=0){
	        Configure::write('debug', 1);
	        $this->layout = 'ajax';
	        $result['flag'] = 2;
	        $user_id = isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
	        $note_detail=$this->CourseNote->find('first',array('conditions'=>array('CourseNote.id'=>$id,'CourseNote.user_id >'=>0,'CourseNote.user_id'=>$user_id)));
	        if(!empty($note_detail)){
	        	if(isset($note_detail['CourseNote']['media'])&&trim($note_detail['CourseNote']['media'])!=''){
	        		$media_root=WWW_ROOT.$note_detail['CourseNote']['media'];
	        		if(file_exists($media_root)&&is_file($media_root))@unlink($media_root);
	        	}
	        	$this->CourseNote->deleteAll(array('id' => $id));
	        	$result['flag'] = 1;
	        }
	        die(json_encode($result));
    }

    function delete_share($id=0){
		Configure::write('debug',1);
		$this->layout = 'ajax';
		$result = array();
		$result['code'] = 0;
		$user_id = isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
		$share_detail=$this->OrganizationShare->find('first',array('conditions'=>array('OrganizationShare.id'=>$id,'OrganizationShare.share_user >'=>0,'OrganizationShare.share_user'=>$user_id)));
		if(!empty($share_detail)){
			$this->OrganizationShare->deleteAll(array('id' => $id));
			$result['code'] = 1;
		}
		die(json_encode($result));
    }

    function ajax_user_share($id=0){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result = array();
        $user_id = isset($_SESSION['User']['User'])?$_SESSION['User']['User']['id']:0;
        $course_id = $id;
        $user_info = array();
        if(!empty($_POST)&&!empty($user_id)){
            foreach ($this->data['user_mobile'] as $k1 => $v1) {
                if($v1 != ''){
                    $user_inf = array();
                    $user_inf['name'] = $this->data['user_name'][$k1];
                    $user_inf['mobile'] = $v1;
                    $user_info[]=$user_inf;
                }
            }
            foreach ($user_info as $k => $v) {
                $mem_name = $v['name'];
                $user_in = $this->User->find('first',array('conditions'=>array('User.mobile'=>$v['mobile'])));
                $user_mem = $this->OrganizationMember->find('first',array('conditions'=>array('OrganizationMember.user_id'=>$user_in['User']['id'])));
                $org_info = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
                $org_name = $org_info['User']['name'];
                $mem_name = $v['name'];
                if(isset($user_in)&&!empty($user_in)){
                    $surl = $this->server_host;
                    $url = $surl.'/courses/view/'.$course_id;
                    $Notify_template=$this->NotifyTemplateType->typeformat('course_invite_member','sms');
                    if(!empty($Notify_template)){
                        $content=$Notify_template['sms']['NotifyTemplateTypeI18n']['param02'];
                        @eval("\$content = \"$content\";");
                    }
                    $sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
                    $sms_result=$this->Notify->send_sms($v['mobile'],$content,$sms_kanal,$this->configs);
                    $sh_info['OrganizationShare']['id'] = 0;
                    $sh_info['OrganizationShare']['organization_id'] = 0;
                    $sh_info['OrganizationShare']['share_user'] = $user_id;
                    $sh_info['OrganizationShare']['share_type'] = 'course';
                    $sh_info['OrganizationShare']['share_type_id'] = $course_id;
                    $sh_info['OrganizationShare']['share_object'] = 0;
                    $sh_info['OrganizationShare']['share_object_ids'] = isset($user_mem['OrganizationMember']['id'])&&$user_mem['OrganizationMember']['id']!=''?$user_mem['OrganizationMember']['id']:$v['name'].'|'.$v['mobile'];
                    $this->OrganizationShare->save($sh_info);
                }else{
                    $surl = $this->server_host;
                    $url = $surl.'/courses/view/'.$course_id;
                    $Notify_template=$this->NotifyTemplateType->typeformat('course_invite_member','sms');
                    if(!empty($Notify_template)){
                        $content=$Notify_template['sms']['NotifyTemplateTypeI18n']['param02'];
                        @eval("\$content = \"$content\";");
                    }
                    $sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
                    $sms_result=$this->Notify->send_sms($v['mobile'],$content,$sms_kanal,$this->configs);
                    $sh_info['OrganizationShare']['id'] = 0;
                    $sh_info['OrganizationShare']['organization_id'] = 0;
                    $sh_info['OrganizationShare']['share_user'] = $user_id;
                    $sh_info['OrganizationShare']['share_type'] = 'course';
                    $sh_info['OrganizationShare']['share_type_id'] = $course_id;
                    $sh_info['OrganizationShare']['share_object'] = 0;
                    $sh_info['OrganizationShare']['share_object_ids'] = $v['name'].'|'.$v['mobile'];
                    $this->OrganizationShare->save($sh_info);
                }
            }
        }
        $re = array();
        $re['message'] = '';
        if(count($result)>0){
            	$re['message'] ='手机号'.chr(13).chr(10).implode(chr(13).chr(10), $result).chr(13).chr(10).'尚未注册，邀请失败！';
        }
        die(json_encode($re));
    }

    public function batch_share(){
        $this->layout = 'ajax';//引入模版
        $this->page_init();
        $user_id=$_SESSION['User']['User']['id'];
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        $this->set('user_list',$user_list);
        if ($this->RequestHandler->isPost()) {
            $course_id = $_POST['course_id'];
            $this->set('course_id',$course_id);
            if (!empty($_FILES['file'])){
                if ($_FILES['file']['error'] > 0) {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/orders/uploaddelivery'</script>";
                    die();
                } else {
                    $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                    $csv_export_code = 'gb2312';
                    $i = 0;
                    while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                        if ($i == 0) {
                            $check_row = $row[0];
                            $row_count = count($row);
                            $check_row = iconv('GB2312', 'UTF-8', $check_row);
                            // if ($check_row != $this->ld['order_code']) {
                            //     echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('上传文件格式不标准');window.location.href='/evaluations/evaluation_management';</script>";
                            // }
                            ++$i;
                        }
                        $temp = array();
                        foreach ($row as $k => $v) {
                            $temp[] = empty($v) ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                        }
                        if (!isset($temp) || empty($temp)) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/orders/uploaddelivery';</script>";
                        }
                        $data[] = $temp;
                    }
                    fclose($handle);
                    foreach ($data as $k => $v) {
                        if ($k == 0) {
                            continue;
                        }
                    }
                    $this->set('uploads_list', $data);
                }
            }
        }
    }

    public function ajax_batch_share($id=0){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $user_mobile = '';
        $mem_name='';
        $eval_id = $id;
        $result = array();
        $user_id = $_SESSION['User']['User']['id'];
        if(!empty($_POST)){
            foreach ($this->data as $k1 => $v1) {
                if($v1[1] != ''){
                    $con['and']['User.mobile'][] = $v1[1];
                }
            }
            if(isset($_POST['checkbox'])&&count($_POST['checkbox'])>0){
                foreach ($_POST['checkbox'] as $key => $value) {
                    if(isset($this->data[$value][1])){
                        $user_mobile = $this->data[$value][1];
                    }
                    if(isset($this->data[$value][0])){
                        $mem_name = $this->data[$value][0];
                    }
                    $user_info = $this->User->find('first',array('conditions'=>array('User.mobile'=>$user_mobile)));
                    $user_mem = $this->OrganizationMember->find('first',array('conditions'=>array('OrganizationMember.user_id'=>$user_info['User']['id'])));
                    $org_info = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
                    $org_name = $org_info['User']['name'];
                    $surl = $this->server_host;
                    $url = $surl.'/courses/view/'.$eval_id;
                    $Notify_template=$this->NotifyTemplateType->typeformat('course_invite_member','sms');
                    if(!empty($Notify_template)){
                        $content=$Notify_template['sms']['NotifyTemplateTypeI18n']['param02'];
                        @eval("\$content = \"$content\";");
                    }
                    $sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
                    $sms_result=$this->Notify->send_sms($user_mobile,$content,$sms_kanal,$this->configs);
                    $sh_info['OrganizationShare']['id'] = 0;
                    $sh_info['OrganizationShare']['organization_id'] = 0;
                    $sh_info['OrganizationShare']['share_user'] = $user_id;
                    $sh_info['OrganizationShare']['share_type'] = 'course';
                    $sh_info['OrganizationShare']['share_type_id'] = $eval_id;
                    $sh_info['OrganizationShare']['share_object'] = 0;
                    $sh_info['OrganizationShare']['share_object_ids'] = isset($user_mem['OrganizationMember']['id'])&&$user_mem['OrganizationMember']['id']!=''?$user_mem['OrganizationMember']['id']:$mem_name.'|'.$user_mobile;
                    $this->OrganizationShare->save($sh_info);

                }
            }
            $re = array();
            $re['message'] = '';
            if(count($result)>0){
                $re['message'] ='手机号'.chr(13).chr(10).implode(chr(13).chr(10), $result).chr(13).chr(10).'尚未注册，邀请失败！';
            }
            die(json_encode($re));
        }
    }

    function ajax_search_org(){
        Configure::write('debug',1);
        $this->layout = 'ajax';
        $result = array();
        $result['code'] = 0;
        $mobile = '';
        $con = array();
        if(!empty($_POST)){
            if(isset($_POST['mobile'])&&$_POST['mobile']!=''){
                $mobile = $_POST['mobile'];
            }
            $user_info = $this->OrganizationMember->find('all',array('conditions'=>array('OrganizationMember.mobile'=>$mobile)));
            foreach ($user_info as $k => $v) {
                $con['Organization.manage_user'][]=$v['OrganizationMember']['user_id'];
            }
            $org_info = $this->Organization->find('all',array('conditions'=>$con));
            die(json_encode($org_info));
        }
    }

    function org_manager_invite(){
        Configure::write('debug',1);
        $this->layout = 'ajax';
        $result = array();
        $result['code'] = 0;
        $user_id = $_SESSION['User']['User']['id'];
        if(!empty($_POST)){
            if(isset($_POST['org_id'])&&$_POST['org_id']!=''){
                $org_id = $_POST['org_id'];
            }
            if(isset($_POST['course_id'])&&$_POST['course_id']!=''){
                $course_id = $_POST['course_id'];
            }
            $org_manager = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$org_id)));
            $user_info = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
            //pr($org_manager);exit();
            $mem_name = $org_manager['Organization']['contacts'];
            $org_name = $user_info['User']['name'];
            $url = $this->server_host.'/courses/view/'.$course_id;
            $email = $org_manager['Organization']['contact_email'];
            $Notify_template=$this->NotifyTemplateType->typeformat('course_invite_member','email');
            if(!empty($Notify_template)){
                $subject=$Notify_template['email']['NotifyTemplateTypeI18n']['title'];
                @eval("\$subject = \"$subject\";");
                $html_body = addslashes($Notify_template['email']['NotifyTemplateTypeI18n']['param01']);
                @eval("\$html_body = \"$html_body\";");
                $text_body = $Notify_template['email']['NotifyTemplateTypeI18n']['param02'];
                @eval("\$text_body = \"$text_body\";");
            }
            $mail_send_queue = array(
                'id' => '',
                'sender_name' => $this->configs['shop_name'],
                'receiver_email' => $email,//接收人姓名;接收人地址
                'cc_email' => "",
                'bcc_email' => "",
                'title' => $subject,
                'html_body' => $html_body,
                'text_body' => $text_body,
                'sendas' => 'html',
                'flag' => 0,
                'pri' => 0,
            );
            $mail_result=$this->Notify->send_email($mail_send_queue, $this->configs);
            if($mail_result==true){
                $share_info['OrganizationShare']['id'] = 0;
                $share_info['OrganizationShare']['organization_id'] = $_POST['org_id'];
                $share_info['OrganizationShare']['share_user'] = $user_id;
                $share_info['OrganizationShare']['share_type'] = 'course';
                $share_info['OrganizationShare']['share_type_id'] = $course_id;
                $share_info['OrganizationShare']['share_object'] = 3;
                $share_info['OrganizationShare']['share_object_ids'] = $org_id;
                $this->OrganizationShare->save($share_info);
                $result['code'] = 1;
            }else{
                $result['message']='邀请失败！';
            }
            die(json_encode($result));
        }
    }

    function get_invite_mem(){
        Configure::write('debug',1);
        $this->layout = 'ajax';
        $result = array();
        $result['code'] = 0;
        $user_id = $_SESSION['User']['User']['id'];
        $mem_info = $this->OrganizationMember->find('all',array('conditions'=>array('OrganizationMember.user_id'=>$user_id)));
        $con = '';
        foreach ($mem_info as $k => $v) {
            $con['Organization.id'][]=$v['OrganizationMember']['organization_id'];
        }
        $org_info = $this->Organization->find('all',array('conditions'=>$con));
        die(json_encode($org_info));
    }

    function org_name_invite(){
        Configure::write('debug',1);
        $this->layout = 'ajax';
        $result = array();
        $result['code'] = 0;
        $result['message'] = '';
        $org_name = '';
        $course_id = '';
        $user_id = $_SESSION['User']['User']['id'];
        $user_info = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
        if(!empty($_POST)){
            if(isset($_POST['org_name'])&&$_POST['org_name']!=''){
                $org_name = $_POST['org_name'];
            }
            if(isset($_POST['course_id'])&&$_POST['course_id']!=''){
                $course_id = $_POST['course_id'];
            }
            $org_info = $this->Organization->find('first',array('conditions'=>array('Organization.name'=>$org_name)));
            if($org_info == ''){
                $result['message'] = '您邀请的公司不存在！';
                die(json_encode($result));
            }else{
                $mem_name = $org_info['Organization']['contacts'];
                $org_name = $user_info['User']['name'];
                $url = $this->server_host.'/courses/view/'.$course_id;
                $email = $org_info['Organization']['contact_way'];
                $Notify_template=$this->NotifyTemplateType->typeformat('course_invite_member','email');
                if(!empty($Notify_template)){
                    $subject=$Notify_template['email']['NotifyTemplateTypeI18n']['title'];
                    @eval("\$subject = \"$subject\";");
                    $html_body = addslashes($Notify_template['email']['NotifyTemplateTypeI18n']['param01']);
                    @eval("\$html_body = \"$html_body\";");
                    $text_body = $Notify_template['email']['NotifyTemplateTypeI18n']['param02'];
                    @eval("\$text_body = \"$text_body\";");
                }
                $mail_send_queue = array(
                    'id' => '',
                    'sender_name' => $this->configs['shop_name'],
                    'receiver_email' => $email,//接收人姓名;接收人地址
                    'cc_email' => "",
                    'bcc_email' => "",
                    'title' => $subject,
                    'html_body' => $html_body,
                    'text_body' => $text_body,
                    'sendas' => 'html',
                    'flag' => 0,
                    'pri' => 0,
                );
                $mail_result=$this->Notify->send_email($mail_send_queue, $this->configs);
                if($mail_result==true){
                    $share_info['OrganizationShare']['id'] = 0;
                    $share_info['OrganizationShare']['organization_id'] = $org_info['Organization']['id'];
                    $share_info['OrganizationShare']['share_user'] = $user_id;
                    $share_info['OrganizationShare']['share_type'] = 'course';
                    $share_info['OrganizationShare']['share_type_id'] = $course_id;
                    $share_info['OrganizationShare']['share_object'] = 2;
                    $share_info['OrganizationShare']['share_object_ids'] = $org_info['Organization']['id'];
                    $this->OrganizationShare->save($share_info);
                    $result['code'] = 1;
                }else{
                    $result['message']='邀请失败！';
                }
                die(json_encode($result));
            }
        }
    }

    function course_study($id){
        //登录验证
        $this->checkSessionUser();
        $_GET=$this->clean_xss($_GET);
        //$this->layout = 'usercenter';//引入模版
        $this->page_init();                        //页面初始化
        $this->pageTitle = '我的课程 - '.$this->configs['shop_title'];
        //当前位置开始
        $user_id=$_SESSION['User']['User']['id'];
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        if ($user_list['User']['rank'] > 0) {
            $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
            $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
        }
        $this->set('user_list', $user_list);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($user_id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($user_id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($user_id);
        $this->set('focuscount', $focus);
        $organizations_id = isset($_GET['organizations_id'])?$_GET['organizations_id']:'0';
        $this->get_manager($organizations_id);
        $organizations_name = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organizations_id)));
        $this->set('organizations_name', $organizations_name);
        $this->set('organizations_id',$organizations_id);
        $organization_actions=$this->Organization->manager_operator($organizations_id,$user_id);
        $this->set('organization_actions',$organization_actions);
        $course_info=$this->Course->find('first',array('conditions'=>array('Course.id'=>$id)));
        $this->set('course_info',$course_info);
        $course_class_log=$this->UserCourseClass->find('all',array('conditions'=>array('UserCourseClass.course_id'=>$id)));
        $this->set('course_class_log',$course_class_log);
        $study_user = array();
        foreach ($course_class_log as $k => $v) {
            	$study_user[] = $v['UserCourseClass']['user_id'];
        }
        $course_user = $this->User->find('list',array('fields'=>'id,name','conditions'=>array('User.id'=>$study_user,'User.status'=>'1')));
        $this->set('course_user',$course_user);
        
	$courselog_data=array();
	$conditions=array();
	$conditions['UserCourseClass.user_id']=$study_user;
	$conditions['UserCourseClass.course_id']=$id;
	$conditions['UserCourseClass.status <>']='0';
	$user_course_class=$this->UserCourseClassDetail->find('all',array('fields'=>array('UserCourseClass.user_id','count(*) as read_count'),'conditions'=>$conditions,'group'=>"UserCourseClass.user_id"));
        if(!empty($user_course_class)){
        	$courselog_data=array();
        	foreach($user_course_class as $v)$courselog_data[$v['UserCourseClass']['user_id']]=$v[0]['read_count'];
        }
        $this->set('courselog_data',$courselog_data);
        
        $courseclass_total=$this->CourseClass->find('count',array('conditions'=>array('CourseClass.course_code'=>$course_info['Course']['code'],'CourseClass.status'=>'1')));
        $this->set('courseclass_total',$courseclass_total);
        
        $organization_info = $this->Organization->find('first', array('conditions' => array('Organization.id' => $organizations_id)));
        $this->set('organization_info',$organization_info);
        
        
        if(isset($_GET['organizations_id'])){
            $this->ur_heres[] = array('name' => '我的组织', 'url' => '/organizations/');
            $this->ur_heres[] = array('name' => '课程管理', 'url' => '/courses/course_management?organizations_id='.$_GET['organizations_id']);
            $this->ur_heres[] = array('name' => $course_info['Course']['name'], 'url' => '/courses/edit/'.$course_info['Course']['id'].'?organizations_id='.$organizations_id);
            $this->ur_heres[] = array('name' => '学习情况', 'url' => '');
        }else{
            $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
            $this->ur_heres[] = array('name' => '我的课程', 'url' => '/courses/course_log');
            $this->ur_heres[] = array('name' => $course_info['Course']['name'], 'url' => '/courses/edit/'.$course_info['Course']['id']);
            $this->ur_heres[] = array('name' => '学习情况', 'url' => '');
        }
    }

    function course_share($id){
        //登录验证
        $this->checkSessionUser();
        $_GET=$this->clean_xss($_GET);
        //$this->layout = 'usercenter';//引入模版
        $this->page_init();                        //页面初始化
        $this->pageTitle = '我的课程 - '.$this->configs['shop_title'];
        //当前位置开始
        $user_id=$_SESSION['User']['User']['id'];
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        if ($user_list['User']['rank'] > 0) {
            $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
            $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
        }
        $this->set('user_list', $user_list);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($user_id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($user_id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($user_id);
        $this->set('focuscount', $focus);
        $organizations_id = isset($_GET['organizations_id'])?$_GET['organizations_id']:'0';
        $this->get_manager($organizations_id);
        $organizations_name = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organizations_id)));
        $this->set('organizations_name', $organizations_name);
        $this->set('organizations_id',$organizations_id);
        $organization_actions=$this->Organization->manager_operator($organizations_id,$user_id);
        $this->set('organization_actions',$organization_actions);
        $course_info=$this->Course->find('first',array('conditions'=>array('Course.id'=>$id)));
        $this->set('course_info',$course_info);
        //分享搜索条件
        $user_member_list = $this->OrganizationMember->find('list',array('fields'=>'OrganizationMember.id','conditions'=>array('OrganizationMember.user_id'=>$_SESSION['User']['User']['id'],'OrganizationMember.status'=>1)));
        $user_manage_list = $this->OrganizationDepartment->find('list',array('fields'=>'OrganizationDepartment.id','conditions'=>array('OrganizationDepartment.department_manage'=>$_SESSION['User']['User']['id'],'OrganizationDepartment.status'=>1)));
        $user_organization_list = $this->Organization->find('list',array('fields'=>'Organization.id','conditions'=>array('Organization.manage_user'=>$_SESSION['User']['User']['id'],'Organization.status'=>1)));
        $organization_share_conditions = array(
            'OrganizationShare.share_type'=>'course',
            'OrganizationShare.share_type_id'=>$id,
        );
        $organization_share_conditions['or'][] = array(
            'OrganizationShare.share_user'=>$_SESSION['User']['User']['id']
        );
        if(sizeof($user_member_list)>0){
            $organization_share_conditions['or'][] = array(
                'OrganizationShare.share_object'=>0,
                'OrganizationShare.share_object_ids'=>$user_member_list
            );
        }
        if(sizeof($user_manage_list)>0){
            $organization_share_conditions['or'][] = array(
                'OrganizationShare.share_object'=>1,
                'OrganizationShare.share_object_ids'=>$user_manage_list
            );
        }
        if(sizeof($user_organization_list)>0){
            $organization_share_conditions['or'][] = array(
                'OrganizationShare.share_object'=>2,
                'OrganizationShare.share_object_ids'=>$user_organization_list
            );
        }
        $organization_share=$this->OrganizationShare->find('all',array('conditions'=>$organization_share_conditions,'order'=>'OrganizationShare.created desc'));
        $this->set('organization_share',$organization_share);
        $organization_share_object_ids = array();
        foreach ($organization_share as $k => $v) {
            $organization_share_object_ids[$v['OrganizationShare']['share_object']][] = $v['OrganizationShare']['share_object_ids'];
        }
        //个人
        if(isset($organization_share_object_ids[0])){
            $members_list = $this->OrganizationMember->find('all',array('conditions'=>array('OrganizationMember.id'=>$organization_share_object_ids[0])));
            $members_list_name = array();
            foreach ($members_list as $k => $v) {
                $members_list_name[$v['OrganizationMember']['id']] = $v['OrganizationMember']['name'];
            }
            $this->set('members_list_name', $members_list_name);
        }
        //组织
        if(isset($organization_share_object_ids[1])){
            $department_list = $this->OrganizationDepartment->find('all',array('conditions'=>array('OrganizationDepartment.id'=>$organization_share_object_ids[1])));
            $department_list_name = array();
            $department_organzation_list = array();
            foreach ($department_list as $k => $v) {
                $department_list_name[$v['OrganizationDepartment']['id']] = $v['OrganizationDepartment']['name'];
                $department_organzation_list[] = $v['OrganizationDepartment']['organization_id'];
            }
            $department_organzation_list_name = $this->Organization->find('list',array('fields'=>'Organization.id,Organization.name','conditions'=>array('Organization.id'=>$department_organzation_list)));
            $this->set('department_organzation_list_name', $department_organzation_list_name);
            $this->set('department_list_name', $department_list_name);
        }
        //公司
        if(isset($organization_share_object_ids[2])){
            $organization_list = $this->Organization->find('all',array('conditions'=>array('Organization.id'=>$organization_share_object_ids[2])));
            $organization_list_name = array();
            foreach ($organization_list as $k => $v) {
                $organization_list_name[$v['Organization']['id']] = $v['Organization']['name'];
            }
            $this->set('organization_list_name', $organization_list_name);
        }
        $share_user = array();
        $organization_share_list_condition = array();
        foreach ($organization_share as $k => $v) {
            $share_user[] = $k['OrganizationShare']['share_user'];
            $organization_share_list_condition[] = $v['OrganizationShare']['organization_id'];
        }
        $users_list = $this->User->find('all',array('conditions'=>$share_user));
        $this->set('users_list',$users_list);
        $organization_share_list = $this->Organization->find('list',array('conditions'=>array('id'=>$organization_share_list_condition),'fields'=>'id,name'));
        $this->set('organization_share_list',$organization_share_list);
        $this->set('orga_id',$organizations_id);
        $organization_info = $this->Organization->find('first', array('conditions' => array('Organization.id' => $organizations_id)));
        $this->set('organization_info',$organization_info);
        if(isset($_GET['organizations_id'])){
            $this->ur_heres[] = array('name' => '我的组织', 'url' => '/organizations/');
            $this->ur_heres[] = array('name' => '课程管理', 'url' => '/courses/course_management?organizations_id='.$_GET['organizations_id']);
            $this->ur_heres[] = array('name' => $course_info['Course']['name'], 'url' => '/courses/edit/'.$course_info['Course']['id'].'?organizations_id='.$organizations_id);
            $this->ur_heres[] = array('name' => '分享记录', 'url' => '');
        }else{
            $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
            $this->ur_heres[] = array('name' => '我的课程', 'url' => '/courses/course_log');
            $this->ur_heres[] = array('name' => $course_info['Course']['name'], 'url' => '/courses/edit/'.$course_info['Course']['id']);
            $this->ur_heres[] = array('name' => '分享记录', 'url' => '');
        }
    }

    function course_note($id){
        //登录验证
        $this->checkSessionUser();
        $_GET=$this->clean_xss($_GET);
        //$this->layout = 'usercenter';//引入模版
        $this->page_init();                        //页面初始化
        $this->pageTitle = '我的课程 - '.$this->configs['shop_title'];
        //当前位置开始
        $user_id=$_SESSION['User']['User']['id'];
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        if ($user_list['User']['rank'] > 0) {
            $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
            $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
        }
        $this->set('user_list', $user_list);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($user_id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($user_id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($user_id);
        $this->set('focuscount', $focus);
        $organizations_id = isset($_GET['organizations_id'])?$_GET['organizations_id']:'';
        $this->get_manager($organizations_id);
        $organizations_name = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organizations_id)));
        $this->set('organizations_name', $organizations_name);
        $this->set('organizations_id',$organizations_id);
        $organization_actions=$this->Organization->manager_operator($organizations_id,$user_id);
        $this->set('organization_actions',$organization_actions);
        $course_info=$this->Course->find('first',array('conditions'=>array('Course.id'=>$id)));
        $this->set('course_info',$course_info);
        $course_note = $this->CourseNote->find('all',array('conditions'=>array('CourseNote.course_id'=>$id,'CourseNote.user_id'=>$user_id)));
        $course_note_user = array();
        $course_class_list = array();
        foreach ($course_note as $k => $v) {
            $course_note_user[] = $v['CourseNote']['user_id'];
            $course_class_list[] = $v['CourseNote']['course_class_id'];
        }
        $user_list = $this->User->find('all',array('conditions'=>array('User.id'=>$course_note_user)));
        $user_note_list = array();
        foreach ($user_list as $k => $v) {
            $user_note_list[$v['User']['id']] = array('name'=>$v['User']['name'],'img01'=>$v['User']['img01']);
        }
        $course_class_note_list=$this->CourseClass->find('all',array('conditions'=>array('CourseClass.id'=>$course_class_list)));
        $class_note_list = array();
        foreach ($course_class_note_list as $k => $v) {
            $class_note_list[$v['CourseClass']['id']] = array('name'=>$v['CourseClass']['name'],'chapter_code'=>$v['CourseClass']['chapter_code']);
        }
        $this->set('course_note',$course_note);
        $this->set('user_note_list',$user_note_list);
        $this->set('class_note_list',$class_note_list);
        $this->set('orga_id',$organizations_id);
        $organization_info = $this->Organization->find('first', array('conditions' => array('Organization.id' => $organizations_id)));
        $this->set('organization_info',$organization_info);
        if(isset($_GET['organizations_id'])){
            $this->ur_heres[] = array('name' => '我的组织', 'url' => '/organizations/');
            $this->ur_heres[] = array('name' => '课程管理', 'url' => '/courses/course_management?organizations_id='.$_GET['organizations_id']);
            $this->ur_heres[] = array('name' => $course_info['Course']['name'], 'url' => '/courses/edit/'.$course_info['Course']['id'].'?organizations_id='.$organizations_id);
            $this->ur_heres[] = array('name' => '笔记记录', 'url' => '');
        }else{
            $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
            $this->ur_heres[] = array('name' => '我的课程', 'url' => '/courses/course_log');
            $this->ur_heres[] = array('name' => $course_info['Course']['name'], 'url' => '/courses/edit/'.$course_info['Course']['id']);
            $this->ur_heres[] = array('name' => '笔记记录', 'url' => '');
        }
    }

    function adduce(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $result['message']='';
        $user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
        $course_id=isset($_POST['course_id'])?$_POST['course_id']:0;
        $course_cond=array();
        $course_cond['Course.user_id']=0;
        $course_cond['Course.status']='1';
        $course_cond['Course.visibility']='0';
        $course_cond['or'][]['Course.price']='0';
        if(!empty($user_id)){
            $this->loadModel('OrderProduct');
            $order_cond=array();
            $order_cond['Order.user_id']=$user_id;
            $order_cond['Order.status']='1';
            $order_cond['Order.payment_status']='2';
            $order_cond['OrderProduct.item_type']='course';
            $order_cond['OrderProduct.product_id >']='0';
            $having_buy_courses=$this->OrderProduct->find('all',array('fields'=>'OrderProduct.product_id','conditions'=>$order_cond));
            if(!empty($having_buy_courses)){
                $having_buy_course_ids=array();
                foreach($having_buy_courses as $v)$having_buy_course_ids=$v['OrderProduct']['product_id'];
                $course_cond['or'][]['Course.id']=$having_buy_course_ids;
            }
        }
        if(empty($course_id)){
            $course_list=$this->Course->find('all',array('fields'=>'id,name,img,price,description','conditions'=>$course_cond,'order'=>'modified desc'));
            if(!empty($course_list)){
                $result['code']='1';
                $result['message']=$course_list;
            }
        }else{
            $course_cond['Course.id']=$course_id;
            $course_info=$this->Course->find('first',array('conditions'=>$course_cond));
            if(!empty($course_info)&&!empty($user_id)){
                $course_data=$course_info['Course'];
                $course_code=$course_info['Course']['code'];
                $course_chapter_list=$this->CourseChapter->find('all',array('conditions'=>array('CourseChapter.course_code'=>$course_code,'CourseChapter.status'=>'1'),'order'=>'CourseChapter.orderby', 'recursive' => -1));
                $course_class_infos=$this->CourseClass->find('all',array('conditions'=>array('CourseClass.course_code'=>$course_code,'CourseClass.status'=>'1'),'order'=>'chapter_code,CourseClass.orderby','recursive' => -1));
                $course_class_list=array();
                if(!empty($course_class_infos)){
                    foreach($course_class_infos as $v){
                        $course_class_list[$v['CourseClass']['chapter_code']][]=$v['CourseClass'];
                    }
                }
                if(isset($course_data['created']))unset($course_data['created']);
                if(isset($course_data['modified']))unset($course_data['modified']);
                $course_data['id']=0;
                $course_data['user_id']=$user_id;
                $course_data['code']='';
                $course_data['course_type_code']='';
                $course_data['course_category_code']='';
                $this->Course->save($course_data);
                $new_course_id=$this->Course->id;
                $new_course_code='course_'.$new_course_id;
                $this->Course->updateAll(array('code'=>"'".$new_course_code."'"),array('id'=>$new_course_id));
                foreach($course_chapter_list as $v){
                    $course_chapter_data=$v['CourseChapter'];
                    $chapter_code=$course_chapter_data['code'];
                    $course_chapter_data['id']=0;
                    $course_chapter_data['course_code']=$new_course_code;
                    $course_chapter_data['code']='';
                    if(isset($course_chapter_data['created']))unset($course_chapter_data['created']);
                    if(isset($course_chapter_data['modified']))unset($course_chapter_data['modified']);
                    $this->CourseChapter->save($course_chapter_data);
                    $new_chapter_id=$this->CourseChapter->id;
                    $new_chapter_code='chapter_'.$new_chapter_id;
                    $this->CourseChapter->updateAll(array('CourseChapter.code'=>"'".$new_chapter_code."'"),array('CourseChapter.id'=>$new_chapter_id));
                    if(isset($course_class_list[$chapter_code])&&!empty($course_class_list[$chapter_code])){
                        foreach($course_class_list[$chapter_code] as $course_class_data){
                            $course_class_data['id']=0;
                            $course_class_data['course_code']=$new_course_code;
                            $course_class_data['chapter_code']=$new_chapter_code;
                            $course_class_data['code']='';
                            if(isset($course_class_data['created']))unset($course_class_data['created']);
                            if(isset($course_class_data['modified']))unset($course_class_data['modified']);
                            $this->CourseClass->save($course_class_data);
                            $new_class_id=$this->CourseClass->id;
                            $new_class_code='class_'.$new_class_id;
                            $this->CourseClass->updateAll(array('CourseClass.code'=>"'".$new_class_code."'"),array('CourseClass.id'=>$new_class_id));
                        }
                    }
                }
                $result['code']='1';
                $result['message']=$new_course_id;
            }
        }
        die(json_encode($result));
    }

    public function ajax_upload_media(){
        $this->checkSessionUser();
        $this->layout = 'ajax';
        Configure::write('debug', 1);
        if(isset($_POST['org_id'])&&$_POST['org_id']!=''){
            $img_root = 'media/courses/'.$_POST['org_id'].'/';
            $imgaddr = WWW_ROOT.'media/courses/'.$_POST['org_id'].'/';
        }
        if(isset($_POST['org_code'])&&$_POST['org_code']!=''){
            $org_code = $_POST['org_code'];
        }
        $this->mkdirs($imgaddr);
        @chmod($imgaddr, 0777);
        $result['code'] = '0';
        $result['error'] = '文件不存在';
        $error = '';
        if ($this->RequestHandler->isPost()) {
            if (isset($_FILES[$org_code])) {
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
                if (strlen($error) == 0) {
                    $image_location = $imgaddr.md5(date('Y-m-d h:i:s').$userfile_name).'.'.$file_ext;
                    $image_name = '/'.$img_root.md5(date('Y-m-d h:i:s').$userfile_name).'.'.$file_ext;

                    if (move_uploaded_file($userfile_tmp, $image_location)) {
                        //pr($_POST['org_code']);exit();
                        $scale = 1;
                        $result['code'] = '1';
                        $result['img_url'] = $image_name;
                    }
                } else {
                    $error = '上传失败';
                }
            }
            $result['error'] = $error;
        }
        die(json_encode($result));
    }

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
                chmod($thispath, $mode);
            } else {
                @chmod($thispath, $mode);
            }
        }
    }

    public function import_course(){
        Configure::write('debug',1);
        $this->layout = 'ajax';
        $result = array();
        $result['code'] = 0;
        if ($this->RequestHandler->isPost()) {
            $relation_course=$this->OrganizationRelation->find('first',array('conditions'=>array('organization_id'=>$_POST['organizations_id'],'type'=>'course','type_id'=>$_POST['id'])));
            if(empty($relation_course)){
                $add_course['OrganizationRelation'] = array(
                    'organization_id'=>$_POST['organizations_id'],
                    'type'=>'course',
                    'type_id'=>$_POST['id']
                );
                $this->OrganizationRelation->saveAll($add_course);
            }
            $result['code'] = 1;
        }
        die(json_encode($result));
    }

    function get_activity_type_id(){
    	 Configure::write('debug',1);
        $this->layout = 'ajax';
        if(isset($_GET['type'])&&$_GET['type'] == 'organization'){
            $organizations_id = isset($_GET['organizations_id'])?$_GET['organizations_id']:'';
            $user_member_list = $this->OrganizationMember->find('list',array('fields'=>'OrganizationMember.id','conditions'=>array('OrganizationMember.user_id'=>$_SESSION['User']['User']['id'])));
            $my_jobs = $this->OrganizationMemberJob->find('list',array('fields'=>'OrganizationMemberJob.organization_department_id','conditions'=>array('OrganizationMemberJob.organization_member_id'=>$user_member_list)));
            $my_jobs = array_unique($my_jobs);
            $user_manage_list = $this->OrganizationDepartment->find('list',array('fields'=>'OrganizationDepartment.id','conditions'=>array('OrganizationDepartment.id'=>$my_jobs)));
            $user_organization_list = $this->Organization->find('list',array('fields'=>'Organization.id','conditions'=>array('Organization.manage_user'=>$_SESSION['User']['User']['id'])));
            $organization_share_conditions = array('OrganizationShare.share_type'=>'course');
            if(sizeof($user_member_list)>0){
                $organization_share_conditions['or'][] = array(
                    'OrganizationShare.organization_id'=>$organizations_id,
                    'OrganizationShare.share_object'=>0,
                    'OrganizationShare.share_object_ids'=>$user_member_list
                );
            }
            if(sizeof($user_manage_list)>0){
                $organization_share_conditions['or'][] = array(
                    'OrganizationShare.organization_id'=>$organizations_id,
                    'OrganizationShare.share_object'=>1,
                    'OrganizationShare.share_object_ids'=>$user_manage_list
                );
            }
            if(sizeof($user_organization_list)>0){
                $organization_share_conditions['or'][] = array(
                    'OrganizationShare.share_object'=>2,
                    'OrganizationShare.share_object_ids'=>$organizations_id
                );
            }
            $this->set('organizations_id', $organizations_id);
            $organizations_name = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organizations_id)));
            $this->set('organizations_name', $organizations_name);
            $course_share=$this->OrganizationShare->find('list',array('fields'=>'OrganizationShare.share_type_id','conditions'=>$organization_share_conditions));
            $course_share = array_unique($course_share);
            $organization_relations = $this->OrganizationRelation->find('list',array('fields'=>'type_id','conditions'=>array('OrganizationRelation.organization_id'=>$organizations_id,'OrganizationRelation.type'=>'course')));
            $course_cansee_conditions=array('Course.status'=>'1');
            if(!empty($organization_relations))$course_cansee_conditions['or'][] =array('Course.id'=>$organization_relations);
            $organization_share_conditions = array('OrganizationShare.share_type'=>'course');
            $course_cansee_conditions['or'][] = array(
                'Course.visibility'=>0,
                'Course.id'=>$course_share
            );
            $course_cansee_conditions['or'][] = array(
                'Course.visibility'=>0,
                'Course.user_id'=>$organizations_name['Organization']['manage_user']
            );
            $course_cansee_conditions['or'][] = array(
                'Course.visibility'=>1,
                'Course.user_id'=>$_SESSION['User']['User']['id'],
                //'Course.user_id'=>$organizations_name['Organization']['manage_user']
            );
            if($organizations_name['Organization']['manage_user']==$_SESSION['User']['User']['id']){
                $course_cansee_conditions['or'][] = array(
                    'Course.visibility'=>2,
                    'Course.user_id'=>$organizations_name['Organization']['manage_user']
                );
            }
            $course_cansee_conditions['or'][] = array(
                'Course.visibility'=>2,
                'Course.id'=>$course_share
            );
            $course_list = $this->Course->find('all', array('order' => 'created desc','conditions'=>$course_cansee_conditions));
            if(!empty($course_list)){
                foreach($course_list as $k=>$v){
                    $course_list[$k]['Course']['class_count']=$this->CourseClass->find('count', array('conditions' =>array("CourseClass.course_code"=>$v['Course']['code'])));
                    $course_list[$k]['Course']['chapter_count']=$this->CourseChapter->find('count', array('conditions' =>array("CourseChapter.course_code"=>$v['Course']['code'])));
                }
            }
            die(json_encode($course_list));
        }else{
            $user_id = $_SESSION['User']['User']['id'];
            $user_course_view_list = array();
            if(isset($user_id)&&$user_id!=''){
                $user_course_view_list = $this->Course->find('all',array('conditions'=>array('Course.user_id'=>$user_id)));
            }
            die(json_encode($user_course_view_list));
        }
    }

    function courseware($course_ware_id=0,$file_key=0){
        Configure::write('debug',1);
        $this->layout = 'ajax';
        $course_ware_detail=$this->CourseClassWare->find('first',array('conditions'=>array('CourseClassWare.id'=>$course_ware_id,'CourseClassWare.ware <>'=>'','CourseClassWare.status'=>'1')));
        if(!empty($course_ware_detail)){
        	$course_detail=$this->Course->find('first',array('conditions'=>array('Course.status'=>'1','Course.code'=>$course_ware_detail['CourseClassWare']['course_code'])));
            $course_class_detail=$this->CourseClass->find('first',array('conditions'=>array('CourseClass.status'=>'1','CourseClass.code'=>$course_ware_detail['CourseClassWare']['course_class_code'],'CourseClass.course_code'=>$course_ware_detail['CourseClassWare']['course_code'])));
            $access_result=$this->Course->access_permission($this,isset($course_detail['Course'])?$course_detail['Course']['id']:0,isset($course_class_detail['CourseClass'])?$course_class_detail['CourseClass']['id']:0,false);
            if(isset($access_result['code'])&&$access_result['code']=='1'){
                if($course_ware_detail['CourseClassWare']['type']=='gallery'){
				$courseware_list=explode(';',$course_ware_detail['CourseClassWare']['ware']);
				foreach($courseware_list as $v){
					$file_courseware=WWW_ROOT.$v;
					if(is_file($file_courseware)&&file_exists($file_courseware)){
						$mime_type=mime_content_type($file_courseware);
						if($mime_type=='application/pdf'){
							//header('Content-type: image/png');
							echo $this->pdf2png($file_courseware,$file_key);
							exit();
						}
					}else if($v!=''){
						ob_start();
						readfile($courseware_file);   
						$pdfcontent = ob_get_contents();   
						ob_end_clean();
						$temp_file = tempnam(sys_get_temp_dir(), 'WareGallery');
						$fp = fopen($temp_file , "a");
						fwrite($fp, $pdfcontent); 
						fclose($fp);
						$mime_type=mime_content_type($temp_file);
						if($mime_type=='application/pdf'){
							header('Content-type: image/png');
							echo $this->pdf2png($temp_file,$file_key);
							@unlink($temp_file);
							exit();
						}
					}
				}
				$courseware_file=isset($courseware_list[$file_key])?$courseware_list[$file_key]:'';
				$file_courseware=WWW_ROOT.$courseware_file;
				if(is_file($file_courseware)&&file_exists($file_courseware)){
					header("Content-type:".mime_content_type($file_courseware));
					readfile($file_courseware);
				}else if(trim($courseware_file)!=''){
					ob_start();
					readfile($courseware_file);   
					$pdfcontent = ob_get_contents();   
					ob_end_clean();
					$temp_file = tempnam(sys_get_temp_dir(), 'WareGallery');
					$fp = fopen($temp_file , "a");
					fwrite($fp, $pdfcontent); 
					fclose($fp);
					header("Content-type:".mime_content_type($temp_file));
					@unlink($temp_file);
					readfile($courseware_file);
				}
                }else if($course_ware_detail['CourseClassWare']['type']=='pdf'){
                    $file_courseware=WWW_ROOT.$course_ware_detail['CourseClassWare']['ware'];
                    if(is_file($file_courseware)&&file_exists($file_courseware)){
				header("Content-type:".mime_content_type($file_courseware));
				readfile($file_courseware);
				//echo $this->pdf2png($file_courseware,$file_key);
				exit();
                    }else if(trim($course_ware_detail['CourseClassWare']['ware'])!=''){
				ob_start();
				readfile($course_ware_detail['CourseClassWare']['ware']);   
				$pdfcontent = ob_get_contents();   
				ob_end_clean();
				$temp_file = tempnam(sys_get_temp_dir(), 'WarePDF');
				$fp = fopen($temp_file , "a");
				fwrite($fp, $pdfcontent); 
				header("Content-type:".mime_content_type($temp_file));
				fclose($temp_file);
				@unlink($temp_file);
				readfile($courseware_file);
//				fclose($temp_file);
//				header('Content-type: image/png');
//				echo $this->pdf2png($temp_file,$file_key);
//				@unlink($temp_file);
				exit();
                    }
                }else{
			$file_courseware=WWW_ROOT.$course_ware_detail['CourseClassWare']['ware'];
			if(is_file($file_courseware)&&file_exists($file_courseware)){
				header("Content-type:".mime_content_type($file_courseware));
				readfile($file_courseware);
			}else if(trim($course_ware_detail['CourseClassWare']['ware'])!=''){
				ob_start();
				readfile($course_ware_detail['CourseClassWare']['ware']);   
				$pdfcontent = ob_get_contents();   
				ob_end_clean();
				$temp_file = tempnam(sys_get_temp_dir(), 'WareFile');
				$fp = fopen($temp_file , "a");
				fwrite($fp, $pdfcontent); 
				fclose($fp);
				header("Content-type:".mime_content_type($temp_file));
				@unlink($temp_file);
				readfile($courseware_file);
			}
                }
            }
        }
        Header("HTTP/1.1 404 Not Found");
        header("status: 404 Not Found");
        exit();
    }

    function pdf2png($pdf,$page=-1){
        $pdf_img=false;
        if(!extension_loaded('imagick')){
            return false;
        }
        if(!is_file($pdf)||!file_exists($pdf)){
            return false;
        }
        $im = new Imagick();
        $im->setResolution(120,120);
        $im->setCompressionQuality(100);
        if($page==-1)
            $im->readImage($pdf);
        else
            $im->readImage($pdf."[".$page."]");
        foreach ($im as $Key => $Var){
            $Var->setImageFormat('png');
            $image_file=WWW_ROOT.'media/'.md5($Key.time()).'.png';
            if($im->writeImage($image_file) == true){
                $pdf_img=file_get_contents($image_file);
                unlink($image_file);
            }
        }
        return $pdf_img;
    }

    public function get_manager($organization_id=0){
        $manager_ids = array();
        $organization_info = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organization_id)));
        $manager_ids[]=$organization_info['Organization']['manage_user'];
        $org_ma = $this->OrganizationManager->find('all',array('conditions'=>array('OrganizationManager.organization_id'=>$organization_id,'OrganizationManager.manager_type'=>0)));
        $cond = array();
        if(isset($org_ma)&&is_array($org_ma)&&count($org_ma)>0){
            foreach ($org_ma as $k => $v) {
                $cond['OrganizationMember.id'][]=$v['OrganizationManager']['organization_member_id'];
            }
        }
        if(!empty($cond)){
            $org_ma = $this->OrganizationMember->find('all',array('conditions'=>$cond));
        }
        if(isset($org_ma)&&is_array($org_ma)&&count($org_ma)>0){
            foreach ($org_ma as $k => $v) {
                $manager_ids[] = $v['OrganizationMember']['user_id'];
            }
        }
        $this->set('org_manager',$manager_ids);
        $manage = $this->OrganizationManager->find('all',array('conditions'=>array('OrganizationManager.organization_id'=>$organization_id)));
        $conn = array();
        if(isset($manage)&&is_array($manage)&&count($manage)>0){
            foreach ($manage as $k => $v) {
                $conn['OrganizationMember.id'][]=$v['OrganizationManager']['organization_member_id'];
            }
        }
        if(!empty($conn)){
            $manages = $this->OrganizationMember->find('all',array('conditions'=>$conn));
        }
        $manage_ids = array();
        $ma_check = '';
        if(isset($manages)&&is_array($manages)&&count($manages)>0){
            foreach ($manages as $k => $v) {
                $manage_ids[]=$v['OrganizationMember']['user_id'];
                $ma_check[$v['OrganizationMember']['id']] = $v['OrganizationMember']['user_id'];
            }
        }
        $manage_ids[]=$organization_info['Organization']['manage_user'];
        $this->set('manager_ids',$manage_ids);
        $dep_manage = '';
        if(isset($manage)&&is_array($manage)&&count($manage)>0){
            foreach ($manage as $k => $v) {
                if(isset($ma_check[$v['OrganizationManager']['organization_member_id']])){
                    $dep_manage[$v['OrganizationManager']['manager_type']][]=$ma_check[$v['OrganizationManager']['organization_member_id']];
                }
            }
        }
        $this->set('dep_managers',$dep_manage);
    }
    
    /*
    		记录学习时间
    */
    function ajax_course_read_time(){
    		Configure::write('debug',1);
        	$this->layout = 'ajax';
        	
        	$result=array();
        	$result['code']='0';
        	
        	$course_id=isset($_POST['course_id'])?intval($_POST['course_id']):0;
        	$course_class_id=isset($_POST['course_class_id'])?intval($_POST['course_class_id']):0;
        	$user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
        	$course_read_time=isset($_POST['course_read_time'])?$_POST['course_read_time']:0;
        	
        	if(!empty($user_id)){
	        	$class_conditions=array();
	        	$class_conditions['UserCourseClass.user_id']=$user_id;
	        	$class_conditions['UserCourseClass.course_id']=$course_id;
	        	$class_conditions['UserCourseClass.status <>']='0';
	        	$user_course_class=$this->UserCourseClass->find('first',array('fields'=>array('UserCourseClass.id'),'conditions'=>$class_conditions));
	        	if(isset($user_course_class['UserCourseClass']['id'])){
		        	$detail_conditions['UserCourseClassDetail.user_course_class_id']=$user_course_class['UserCourseClass']['id'];
		        	$detail_conditions['UserCourseClassDetail.course_class_id']=$course_class_id;
		        	$detail_conditions['UserCourseClass.status <>']='0';
		        	$user_course_class_detail=$this->UserCourseClassDetail->find('first',array('fields'=>array('UserCourseClassDetail.id','UserCourseClassDetail.read_time'),'conditions'=>$detail_conditions));
		        	if(!empty($user_course_class_detail['UserCourseClassDetail'])){
					$user_course_class_detail['UserCourseClassDetail']['read_time'] +=$course_read_time;
		        	}else{
		        		$user_course_class_detail['UserCourseClassDetail'] = array(
		        		'id'=>'0',
		        		'user_course_class_id' =>$user_course_class['UserCourseClass']['id'],
		        		'course_class_id'=>$course_class_id,
		        		'status'=>'0',
		        		'read_time'=>$course_read_time
		        		);
		        	}
		        		$this->UserCourseClassDetail->save($user_course_class_detail['UserCourseClassDetail']);
		        		$result['code']='1';
		        		$result['messgae']="Total second:".$user_course_class_detail['UserCourseClassDetail']['read_time'];
	        	}
        	}
        	die(json_encode($result));
    }
    
    /*
    		学习情况
    */
    function ajax_user_course_log(){
    		Configure::write('debug',1);
        	$this->layout = 'ajax';
        	
        	$result=array();
        	$result['code']='0';
        	
        	$course_id=isset($_POST['course_id'])?intval($_POST['course_id']):0;
        	$user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
        	if(!empty($user_id)){
        		$conditions=array();
	        	$conditions['UserCourseClass.user_id']=$user_id;
	        	$conditions['UserCourseClass.course_id']=$course_id;
	        	$conditions['UserCourseClass.status <>']='0';
	        	$conditions['Course.status']='1';
	        	$user_course_class=$this->UserCourseClass->find('first',array('conditions'=>$conditions));
        		if(!empty($user_course_class)){
        			$user_course_class_details=$this->UserCourseClassDetail->find('all',array('conditions'=>array('user_course_class_id'=>$user_course_class['UserCourseClass']['id'],'CourseClass.status'=>'1'),'order'=>'UserCourseClassDetail.id'));
        			$result['code']='1';
        			$result['data']=$user_course_class_details;
        		}
        	}
        	die(json_encode($result));
    }
    
    function ajax_course_assignment(){
    		Configure::write('debug',1);
        	$this->layout = 'ajax';
        	
		$result=array();
		$result['code']='0';
		$result['message']=$this->ld['send_failed'];
		$post_data=isset($_POST['data']['CourseNote'])?$_POST['data']['CourseNote']:array();
        	$user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
        	
        	if ($this->RequestHandler->isPost()){
        		if(isset($this->data['CourseAssignment'])&&!empty($this->data['CourseAssignment'])&&!empty($user_id)){
        			$this->loadModel('CourseAssignment');
        			$conditions=array();
        			$conditions['CourseAssignment.course_id']=$this->data['CourseAssignment']['course_id'];
        			$conditions['CourseAssignment.course_ware_id']=$this->data['CourseAssignment']['course_ware_id'];
        			$conditions['CourseAssignment.user_id']=$user_id;
        			$UserCourseAssignment=$this->CourseAssignment->find('first',array('conditions'=>$conditions));
        			if(isset($_FILES['AssignmentMedia'])&&$_FILES['AssignmentMedia']['error']=='0'){
        				if(!empty($UserCourseAssignment)&&trim($UserCourseAssignment['CourseAssignment']['media'])!=''&&file_exists(WWW_ROOT.$UserCourseAssignment['CourseAssignment']['media'])&&is_file(WWW_ROOT.$UserCourseAssignment['CourseAssignment']['media'])){
        					@unlink(WWW_ROOT.$UserCourseAssignment['CourseAssignment']['media']);
        				}
					$mediaInfo=pathinfo($_FILES['AssignmentMedia']['name']);
					$mediaName=md5($mediaInfo['filename'].time()).".".$mediaInfo['extension'];
					$media_root=WWW_ROOT.'media/CourseAssignmentMedia/';
					$this->mkdirs($media_root);
					if (move_uploaded_file($_FILES['AssignmentMedia']['tmp_name'], $media_root.$mediaName)) {
						$media_path = '/media/CourseAssignmentMedia/'.$mediaName;
						$this->data['CourseAssignment']['media']=$media_path;
					}
				}
        			$this->data['CourseAssignment']['id']=isset($UserCourseAssignment['CourseAssignment'])?$UserCourseAssignment['CourseAssignment']['id']:0;
        			$this->data['CourseAssignment']['user_id']=$user_id;
        			$this->CourseAssignment->save($this->data['CourseAssignment']);
        			
        			$result['code']='1';
				$result['message']=$this->ld['saved_successfully'];
        		}
        	}
    		die(json_encode($result));
    }
    
    function user_course_note($page=1,$limit=10){
		//登录验证
		$this->checkSessionUser();
		$_GET=$this->clean_xss($_GET);
		$this->layout = 'usercenter';//引入模版
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
			Configure::write('debug', 0);
			$this->layout = 'ajax';
		}
		$this->page_init();                        //页面初始化
		$this->pageTitle = '课程笔记 - '.$this->configs['shop_title'];
		//当前位置开始
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => '我的课程', 'url' => '/courses/course_log');
		$this->ur_heres[] = array('name' => '课程笔记', 'url' => '');
    		
		$user_id=$_SESSION['User']['User']['id'];
		//获取我的信息
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
		if ($user_list['User']['rank'] > 0) {
			$rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
			$user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
		}
		$this->set('user_list', $user_list);
		//粉丝数量
		$fans = $this->UserFans->find_fanscount_byuserid($user_id);
		$this->set('fanscount', $fans);
		//日记数量
		$blog = $this->Blog->find_blogcount_byuserid($user_id);
		$this->set('blogcount', $blog);
		//关注数量
		$focus = $this->UserFans->find_focuscount_byuserid($user_id);
		$this->set('focuscount', $focus);
    		
    		$joins=array(
	                array(
					'table' => 'svhr_courses',
					'alias' => 'Course',
					'type' => 'left',
					'conditions' => array('CourseNote.course_id = Course.id')
	                ),
	                array(
	            		'table' => 'svhr_course_classes',
					'alias' => 'CourseClass',
					'type' => 'left',
					'conditions' => array('CourseNote.course_class_id = CourseClass.id and CourseClass.course_code=Course.code')
	            	),
	                array(
	            		'table' => 'svhr_course_chapters',
					'alias' => 'CourseChapter',
					'type' => 'left',
					'conditions' => array('CourseChapter.course_code = Course.code and CourseChapter.code=CourseClass.chapter_code')
	            	)
        	);
    		$conditions=array();
    		$conditions['CourseNote.user_id']=$user_id;
    		if(isset($_GET['course_id'])&&intval($_GET['course_id'])>0){
    			$conditions['CourseNote.course_id']=intval($_GET['course_id']);
    		}
    		$conditions['Course.status']='1';
    		$conditions['CourseClass.status']='1';
    		$user_note_total=$this->CourseNote->find('count',array('conditions'=>$conditions,'joins'=>$joins));
		$parameters=array();
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'courses', 'action' => 'user_course_note', 'page' => $page, 'limit' => $limit);
		//分页参数
		$options = array('page' => $page, 'show' => $limit, 'modelClass' => 'CourseNote','total'=>$user_note_total);
		$this->Pagination->init($conditions, $parameters, $options); // Added
    		$user_note_list=$this->CourseNote->find('all',array('fields'=>'CourseNote.*,Course.name,Course.img,Course.meta_description,CourseClass.name,CourseChapter.name','conditions'=>$conditions,'joins'=>$joins,'order'=>'CourseNote.created desc'));
    		$this->set('user_note_list',$user_note_list);
    		
    		if(!empty($user_note_list)){
    			$this->loadModel('CourseNoteReply');
    			$course_note_ids=array();
    			foreach($user_note_list as $v)$course_note_ids[]=$v['CourseNote']['id'];
    			$course_note_reply_infos=$this->CourseNoteReply->find('all',array('fields'=>'CourseNoteReply.course_note_id,count(*) as note_reply_count','conditions'=>array('CourseNoteReply.course_note_id'=>$course_note_ids),'group'=>'CourseNoteReply.course_note_id'));
			if(!empty($course_note_reply_infos)){
				$course_note_reply_list=array();
				foreach($course_note_reply_infos as $v)$course_note_reply_list[$v['CourseNoteReply']['course_note_id']]=$v[0]['note_reply_count'];
				$this->set('course_note_reply_list',$course_note_reply_list);
			}
    		}
    }
    
    function user_course_note_detail($course_note_id=0){
    		//登录验证
		$this->checkSessionUser();
		$_GET=$this->clean_xss($_GET);
		$this->layout = 'usercenter';//引入模版
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
			Configure::write('debug', 0);
			$this->layout = 'ajax';
		}
		$this->page_init();                        //页面初始化
		$this->pageTitle = '课程笔记 - '.$this->configs['shop_title'];
		//当前位置开始
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => '我的课程', 'url' => '/courses/course_log');
		$this->ur_heres[] = array('name' => '课程笔记', 'url' => '/courses/user_course_note');
    		
		$user_id=$_SESSION['User']['User']['id'];
		//获取我的信息
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
		if ($user_list['User']['rank'] > 0) {
			$rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
			$user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
		}
		$this->set('user_list', $user_list);
		//粉丝数量
		$fans = $this->UserFans->find_fanscount_byuserid($user_id);
		$this->set('fanscount', $fans);
		//日记数量
		$blog = $this->Blog->find_blogcount_byuserid($user_id);
		$this->set('blogcount', $blog);
		//关注数量
		$focus = $this->UserFans->find_focuscount_byuserid($user_id);
		$this->set('focuscount', $focus);
		
		$joins=array(
	                array(
					'table' => 'svhr_courses',
					'alias' => 'Course',
					'type' => 'left',
					'conditions' => array('CourseNote.course_id = Course.id')
	                ),
	                array(
	            		'table' => 'svhr_course_classes',
					'alias' => 'CourseClass',
					'type' => 'left',
					'conditions' => array('CourseNote.course_class_id = CourseClass.id and CourseClass.course_code=Course.code')
	            	),
	                array(
	            		'table' => 'svhr_course_chapters',
					'alias' => 'CourseChapter',
					'type' => 'left',
					'conditions' => array('CourseChapter.course_code = Course.code and CourseChapter.code=CourseClass.chapter_code')
	            	)
        	);
    		$conditions=array();
    		$conditions['CourseNote.id']=$course_note_id;
    		$conditions['CourseNote.user_id']=$user_id;
    		$conditions['Course.status']='1';
    		$conditions['CourseClass.status']='1';
    		$user_note_detail=$this->CourseNote->find('first',array('fields'=>'CourseNote.*,Course.name,Course.img,Course.meta_description,CourseClass.name,CourseChapter.name','conditions'=>$conditions,'joins'=>$joins));
    		if(empty($user_note_detail))$this->redirect('/courses/user_course_note');
    		$this->set('user_note_detail',$user_note_detail);
    		
    		$this->loadModel('CourseNoteReply');
    		$reply_conditions=array();
    		$reply_conditions['CourseNoteReply.course_note_id']=$course_note_id;
    		$note_reply_list=$this->CourseNoteReply->find('all',array('conditions'=>$reply_conditions,'order'=>'CourseNoteReply.id desc'));
    		$this->set('note_reply_list',$note_reply_list);
    		if(!empty($note_reply_list)){
    			$reply_operator_ids=array();
    			$reply_user_ids=array();
    			foreach($note_reply_list as $v){
    				if($v['CourseNoteReply']['reply_from']=='0'){
    					$reply_user_ids[]=$v['CourseNoteReply']['reply_from_id'];
    				}else if($v['CourseNoteReply']['reply_from']=='1'){
    					$reply_operator_ids[]=$v['CourseNoteReply']['reply_from_id'];
    				}
    			}
    			if(!empty($reply_operator_ids)){
    				$this->loadModel('Operator');
    				$ReplyOperatorList=$this->Operator->find('list',array('fields'=>'Operator.id,Operator.name','conditions'=>array('Operator.status'=>'1','Operator.id'=>$reply_operator_ids)));
    				$this->set('ReplyOperatorList',$ReplyOperatorList);
    			}
    			if(!empty($reply_user_ids)){
    				$this->loadModel('User');
    				$ReplyUserInfos=$this->User->find('all',array('fields'=>'User.id,User.name,User.first_name','conditions'=>array('User.status'=>'1','User.id'=>$reply_user_ids)));
    				$ReplyUserList=array();
    				foreach($ReplyUserInfos as $v)$ReplyUserList[$v['User']['id']]=$v['User'];
    				$this->set('ReplyUserList',$ReplyUserList);
    			}
    		}
    	}
    	
    	private function pdf2pngSize($path){
		$pdf_length=0;
		if(!file_exists($path)) return $pdf_length;
		if(!is_readable($path)) return $pdf_length;
		$fp=@fopen($path,"r");
		if (!$fp) {
			return $pdf_length;
		}else {
			while(!feof($fp)) {
				$line = fgets($fp,255);
				if (preg_match('/\/Count [0-9]+/', $line, $matches)){
					preg_match('/[0-9]+/',$matches[0], $matches2);
					if ($pdf_length<$matches2[0]) $pdf_length=$matches2[0];
				}
			}
			fclose($fp);
			return $pdf_length;
		}
	}
	
	
    private function ScromZip($zipfile,$zipdir){
    		$zip = new ZipArchive();
		$res = $zip->open($zipfile); 
		if ($res === TRUE) {
			$zip->extractTo($zipdir);
			$zip->close();
			return true;
		}else{
			return false;
		}
    }
    
    private function removeScromDir($dirpath) {
	    if (!is_dir($dirpath)) {
	        return false;
	    }
	    $handle = opendir($dirpath);
	    while (($file = readdir($handle)) !== false) {
	        if ($file != "." && $file != "..") {
	            is_dir("$dirpath/$file") ? $this->removeScromDir("$dirpath/$file") : @unlink("$dirpath/$file");
	        }
	    }
	    if (readdir($handle) == false) {
	        closedir($handle);
	        @rmdir($dirpath);
	    }
    }
    
    private function shortGuid(){
		$data = openssl_random_pseudo_bytes(16);
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
		$guid=base64_encode($data);
		$guid=str_replace('/','_',$guid);
		$guid=str_replace('+','-',$guid);
		$shortguid = substr($guid,0,22);
		return $shortguid;
    }
}