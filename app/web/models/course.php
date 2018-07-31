<?php

/**
 * 	Course 课程
 */
class Course extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';

    /*
    		课程列表
    */
    function course_list($params=array()){
        $limit = 10;
        if (isset($params['limit'])) {$limit = $params['limit'];}
        $page = 1;
        if (isset($params['page'])) {$page = $params['page'];}
        $page_controller="course_categories";
        $page_action="index";
        if (isset($params['ControllerObj'])) {
            $page_controller=isset($params['ControllerObj']->params['controller'])?$params['ControllerObj']->params['controller']:$page_controller;
            $page_action=isset($params['ControllerObj']->params['action'])?$params['ControllerObj']->params['action']:$page_action;
        }
        $conditions=array();
        $conditions['Course.status']='1';
        $conditions['Course.user_id']='0';
        $conditions['Course.visibility']='0';
        $conditions['Course.allow_public']='1';
        if (isset($params['course_type_code'])&&trim($params['course_type_code'])!='') {
            $conditions['Course.course_type_code']=trim($params['course_type_code']);
        }
        if (isset($params['course_category_code'])&&trim($params['course_category_code'])!='') {
            $conditions['Course.course_category_code']=trim($params['course_category_code']);
        }
        $course_orderby="Course.modified desc,Course.name";
        if(isset($params['course_orderby'])&&$params['course_orderby']='clicked'){
            $course_orderby="Course.clicked desc,Course.name";
        }
        $total = $this->find('count', array('conditions' => $conditions));
        App::import('Component', 'Paginationmodel');
        $pagination = new PaginationModelComponent();
        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => $page_controller,'action' => $page_action,'page' => $page,'limit' => $limit);
        //分页参数
        $options = array('page' => $page,'show' => $limit,'modelClass' => $this->name,'total' => $total);
        $pages = $pagination->init($conditions, $parameters, $options); // Added
        $OrganizationMember = ClassRegistry::init('OrganizationMember');
        $OrganizationDepartment = ClassRegistry::init('OrganizationDepartment');
        $Organization = ClassRegistry::init('Organization');
        $OrganizationShare = ClassRegistry::init('OrganizationShare');
        $OrganizationMemberJob = ClassRegistry::init('OrganizationMemberJob');
        $user_member_list = $OrganizationMember->find('list',array('fields'=>'OrganizationMember.id','conditions'=>array('OrganizationMember.user_id'=>$_SESSION['User']['User']['id'])));
        // $user_manage_list = $OrganizationDepartment->find('list',array('fields'=>'OrganizationDepartment.id','conditions'=>array('OrganizationDepartment.department_manage'=>$_SESSION['User']['User']['id'])));
        $my_jobs = $OrganizationMemberJob->find('list',array('fields'=>'OrganizationMemberJob.organization_department_id','conditions'=>array('OrganizationMemberJob.organization_member_id'=>$user_member_list)));
        $my_jobs = array_unique($my_jobs);
        $user_manage_list = $OrganizationDepartment->find('list',array('fields'=>'OrganizationDepartment.id','conditions'=>array('OrganizationDepartment.id'=>$my_jobs)));
        $user_organization_list = $Organization->find('list',array('fields'=>'Organization.id','conditions'=>array('Organization.manage_user'=>$_SESSION['User']['User']['id'])));
        $organization_share_conditions = array('OrganizationShare.share_type'=>'course');
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
        $course_share=$OrganizationShare->find('list',array('fields'=>'OrganizationShare.share_type_id','conditions'=>$organization_share_conditions));
        $course_share = array_unique($course_share);
        $course_cansee_conditions = array();
        $course_cansee_conditions['or'][] = array(
            'Course.visibility'=>0
        );
        $course_cansee_conditions['or'][] = array(
            'Course.visibility'=>1,
            'Course.user_id'=>$_SESSION['User']['User']['id']
        );
        $course_cansee_conditions['or'][] = array(
            'Course.visibility'=>2,
            'Course.user_id'=>$_SESSION['User']['User']['id']
        );
        $course_cansee_conditions['or'][] = array(
            'Course.visibility'=>2,
            'Course.id'=>$course_share
        );
        $course_list=$this->find('all',array('conditions'=>$conditions,'order'=>$course_orderby,'page'=>$page,'limit'=>$limit));
        if(!empty($course_list)){
            $UserCourseClassDetail = ClassRegistry::init('UserCourseClassDetail');
            $course_ids=array();
            foreach($course_list as $v){
                $course_ids[]=$v['Course']['id'];
            }
            $course_log_total_info=$UserCourseClassDetail->find('all',array('conditions'=>array('UserCourseClass.course_id'=>$course_ids,'UserCourseClass.user_id >'=>0,'UserCourseClass.status <>'=>'0'),'fields'=>'UserCourseClass.course_id,UserCourseClass.user_id','group'=>'UserCourseClass.course_id,UserCourseClass.user_id'));
            $course_user_total=array();
            foreach($course_log_total_info as $v){
                $course_user_total[$v['UserCourseClass']['course_id']][$v['UserCourseClass']['user_id']]=$v['UserCourseClass']['user_id'];
            }
            foreach($course_user_total as $k=>$v){
                $course_user_total[$k]=sizeof($v);
            }
            $course_datas['course_user']=$course_user_total;
        }
        $course_datas['course_list']=$course_list;
        $course_datas['paging']=$pages;
        return $course_datas;
    }

    /*
    		推荐课程
    */
    function  recommend_course(){
        $limit = 10;
        if (isset($params['limit'])) {$limit = $params['limit'];}
        $page = 1;
        $page_controller="courses";
        $page_action="index";
        if (isset($params['ControllerObj'])) {
            $page_controller=isset($params['ControllerObj']->params['controller'])?$params['ControllerObj']->params['controller']:$page_controller;
            $page_action=isset($params['ControllerObj']->params['action'])?$params['ControllerObj']->params['action']:$page_action;
        }
        $conditions=array();
        $conditions['Course.status']='1';
        $conditions['Course.recommend_flag']='1';
        $conditions['Course.user_id']='0';
        $conditions['Course.visibility']='0';
        $conditions['Course.allow_public']='1';
        if (isset($params['course_type_code'])&&trim($params['course_type_code'])!='') {
            $conditions['Course.course_type_code']=trim($params['course_type_code']);
        }
        if (isset($params['course_category_code'])&&trim($params['course_category_code'])!='') {
            $conditions['Course.course_category_code']=trim($params['course_category_code']);
        }
        $course_orderby="Course.modified desc,Course.name";
        if(isset($params['course_orderby'])&&$params['course_orderby']='clicked'){
            $course_orderby="Course.clicked desc,Course.name";
        }
        $course_datas=$this->find('all',array('conditions'=>$conditions,'order'=>$course_orderby,'page'=>$page,'limit'=>$limit));
        if(!empty($course_datas)){
            $UserCourseClassDetail = ClassRegistry::init('UserCourseClassDetail');
            $course_ids=array();
            foreach($course_datas as $v){
                $course_ids[]=$v['Course']['id'];
            }
            $course_log_total_info=$UserCourseClassDetail->find('all',array('conditions'=>array('UserCourseClass.course_id'=>$course_ids,'UserCourseClass.user_id >'=>0,'UserCourseClass.status <>'=>'0'),'fields'=>'UserCourseClass.course_id,UserCourseClass.user_id','group'=>'UserCourseClass.course_id,UserCourseClass.user_id'));
            $course_user_total=array();
            foreach($course_log_total_info as $v){
                	$course_user_total[$v['UserCourseClass']['course_id']][$v['UserCourseClass']['user_id']]=$v['UserCourseClass']['user_id'];
            }
            foreach($course_datas as $k=>$v){
                	$course_datas[$k]['course_user']=isset($course_user_total[$v['Course']['id']])?sizeof($course_user_total[$v['Course']['id']]):0;
            		$course_datas[$k]['course_user_list']=isset($course_user_total[$v['Course']['id']])?$course_user_total[$v['Course']['id']]:array();
            }
        }
        return $course_datas;
    }

    function course_detail($params=array()){
        $course_info=array();
        if (isset($params['course_data'])&&!empty($params['course_data'])) {
        		$course_data=$params['course_data'];
        }else{
			$conditions=array();
			$conditions['Course.status']='1';
			$conditions['Course.id']=isset($params['id'])?intval($params['id']):0;
			$course_data=$this->find('first',array('conditions'=>$conditions));
        }
        if(!empty($course_data)){
            $course_info['course_data']=$course_data['Course'];
            $course_id=isset($course_data['Course']['id'])?$course_data['Course']['id']:'0';
            $course_code=isset($course_data['Course']['code'])?$course_data['Course']['code']:'';
            $CourseChapter = ClassRegistry::init('CourseChapter');
            $UserCourseClassDetail = ClassRegistry::init('UserCourseClassDetail');
            $CourseLearningPlan = ClassRegistry::init('CourseLearningPlan');
            $CourseNote = ClassRegistry::init('CourseNote');
            $course_chapter=$CourseChapter->course_chapter_tree($course_code);
            $course_info['course_chapter']=$course_chapter;
            $course_chapter_total=0;
            $course_class_list=array();
            $course_class_infos=array();
            $course_class_brands=array();
            $course_class_wares=array();
            $course_class_access_permission=array();
            foreach($course_chapter as $v){
                	$course_chapter_total+=isset($v['CourseClass'])?sizeof($v['CourseClass']):0;
                	if(!empty($v['CourseClass'])){
                		foreach($v['CourseClass'] as $vv){
                			$course_class_access_permission[$vv['id']]=$this->access_permission(isset($params['ControllerObj'])?$params['ControllerObj']:null,$course_id,$vv['id'],false);
                			$course_class_list[$vv['code']]=$vv['id'];
                			$course_class_infos[$vv['id']]=$vv['name'];
                			if(trim($vv['brand_code'])!='')$course_class_brands[]=$vv['brand_code'];
                			if(isset($vv['CourseWare'])){
                				foreach($vv['CourseWare'] as $vvv){
                					if($vvv['CourseClassWare']['type']=='evaluation'){
                						$course_class_wares['evaluation'][]=$vvv['CourseClassWare']['ware'];
	                				}else if($vvv['CourseClassWare']['type']=='assignment'){
	                					$course_class_wares['assignment'][]=$vvv['CourseClassWare']['id'];
	                				}
                				}
                			}
                		}
                	}
            }
            if(!empty($course_class_brands)){
            		$Brand = ClassRegistry::init('Brand');
            		$BrandInfo=$Brand->find('all',array('conditions'=>array('Brand.status'=>'1','Brand.code'=>$course_class_brands,'Brand.code <>'=>'','BrandI18n.name <>'=>''),'fields'=>'Brand.code,BrandI18n.name'));
            		if(!empty($BrandInfo)){
            			$BrandList=array();
            			foreach($BrandInfo as $v)$BrandList[$v['Brand']['code']]=$v['BrandI18n']['name'];
            			$course_info['CourseBrand']=$BrandList;
            		}
            }
            $course_info['class_access_permission']=$course_class_access_permission;
            $course_info['course_class_list']=$course_class_infos;
            $course_info['course_chapter_total']=$course_chapter_total;
            if(isset($_SESSION['User']) || isset($params['user_id'])){
			if(isset($params['user_id'])){
				$user_id=$params['user_id'];
			}else{
				$user_id=$_SESSION['User']['User']['id'];
			}
			if(isset($course_class_list)&&!empty($course_class_list)){
				$OrderProduct = ClassRegistry::init('OrderProduct');
				$order_cond=array();
				$order_cond['Order.user_id']=$user_id;
				$order_cond['Order.status']='1';
				$order_cond['Order.payment_status']='2';
				$order_cond['OrderProduct.item_type']='course_class';
				$order_cond['OrderProduct.product_id']=$course_class_list;
				$already_purchased_class_infos=$OrderProduct->find('all',array('conditions'=>$order_cond,'fields'=>'OrderProduct.product_id,OrderProduct.order_id,Order.total'));
				if(!empty($already_purchased_class_infos)){
					$already_purchased_class_list=array();
					foreach($already_purchased_class_infos as $v)$already_purchased_class_list[$v['OrderProduct']['product_id']]=$v;
					$course_info['already_purchased_class']=$already_purchased_class_list;
				}
			}
                $UserCourseClass = ClassRegistry::init('UserCourseClass');
                $course_log=$UserCourseClass->find('first',array('conditions'=>array('UserCourseClass.course_id'=>$course_data['Course']['id'],'UserCourseClass.user_id'=>$user_id,'UserCourseClass.status <>'=>0)));
                $course_info['course_log']=$course_log;
                if(!empty($course_log)){
                		$course_log_id=$course_log['UserCourseClass']['id'];
                		$course_log_detail_infos=$UserCourseClassDetail->find('all',array('fields'=>'UserCourseClassDetail.course_class_id,UserCourseClassDetail.id,UserCourseClassDetail.status,UserCourseClassDetail.created,UserCourseClassDetail.modified','conditions'=>array('UserCourseClassDetail.user_course_class_id'=>$course_log_id,'UserCourseClassDetail.course_class_id'=>$course_class_list)));
                		$course_log_detail=array();$course_log_data=array();
                		$course_start_date_list=array();
                		foreach($course_log_detail_infos as $v){
                			$course_start_date_list[$v['UserCourseClassDetail']['course_class_id']]=strtotime($v['UserCourseClassDetail']['created']);
                			if($v['UserCourseClassDetail']['status']=='1')$course_log_detail[$v['UserCourseClassDetail']['course_class_id']]=$v['UserCourseClassDetail']['id'];
                			$course_log_data[$v['UserCourseClassDetail']['course_class_id']]=$v['UserCourseClassDetail'];
                			if($course_log_data[$v['UserCourseClassDetail']['course_class_id']]['status']==0){
                				$week=$CourseLearningPlan->find('first',array('fields'=>'CourseLearningPlan.week','conditions'=>array('CourseLearningPlan.course_class_id'=>$v['UserCourseClassDetail']['course_class_id'],'CourseLearningPlan.status'=>1)));
                				if(!empty($week)){
                					$day=intval($week['CourseLearningPlan']['week'])*7;
                					$d=$course_log_data[$v['UserCourseClassDetail']['course_class_id']]['created'];
                					$course_log_data[$v['UserCourseClassDetail']['course_class_id']]['modified']=date("Y-m-d",strtotime("$d +$day day"));
                				}else{
                					$course_log_data[$v['UserCourseClassDetail']['course_class_id']]['modified']="";
                				}
                			}
                			$note_cond=array();
                			$note_cond['CourseNote.course_class_id']=$v['UserCourseClassDetail']['course_class_id'];
                			$note_cond['CourseNote.is_public']='0';
                			if(isset($params['user_id'])&&!empty($params['user_id']))$note_cond['CourseNote.user_id']=$params['user_id'];
                			$notes=$CourseNote->find('all',array('fields'=>'CourseNote.id','conditions'=>$note_cond));
                			$course_notes[$v['UserCourseClassDetail']['course_class_id']]=count($notes);
                		}
                		if(isset($course_notes)){
                			$course_info['course_notes']=$course_notes;
                		}
                		$course_info['course_log_detail']=$course_log_detail;
                		$course_info['course_log_data']=$course_log_data;
                		$course_info['course_start_date_list']=$course_start_date_list;
                }
                if(!empty($CourseLearningPlan)){
                    $course_learning_plan_list=$CourseLearningPlan->find('all',array('conditions'=>array('CourseLearningPlan.course_id'=>$course_id,'CourseLearningPlan.status'=>'1','CourseClass.status'=>'1'),'order'=>'CourseLearningPlan.week,CourseLearningPlan.orderby,CourseChapter.orderby,CourseChapter.id,CourseClass.orderby,CourseClass.id'));
                    if(!empty($course_learning_plan_list)){
				$course_learning_plan_data=array();$learning_plan_class_ids=array();
				foreach($course_learning_plan_list as $v){
					$learning_plan_class_ids[]=$v['CourseLearningPlan']['course_class_id'];
					$course_learning_plan_data[$v['CourseLearningPlan']['week']][]=$v;
				}
				$course_info['learning_plan']=$course_learning_plan_data;
				$course_info['learning_plan_total']=sizeof($course_learning_plan_list);
				if(!empty($course_log)){
					$course_log_id=$course_log['UserCourseClass']['id'];
					$learning_plan_status_info=$UserCourseClassDetail->find('all',array('fields'=>'UserCourseClassDetail.status,count(*) as course_class_status_total','conditions'=>array('UserCourseClassDetail.user_course_class_id'=>$course_log_id,'UserCourseClassDetail.course_class_id'=>$learning_plan_class_ids),'group'=>'UserCourseClassDetail.status'));
					if(!empty($learning_plan_status_info)){
						$learning_plan_status_list=array();
						foreach($learning_plan_status_info as $v)$learning_plan_status_list[$v['UserCourseClassDetail']['status']]=$v[0]['course_class_status_total'];
					}
					$course_info['learning_plan_status_list']=$learning_plan_status_list;
				}
                    }
                }
			$UserFavorite = ClassRegistry::init('UserFavorite');
			$course_favorite_info=$UserFavorite->find('first',array('fields'=>'id,user_id,created','conditions'=>array(
				'UserFavorite.user_id'=>$user_id,
				'UserFavorite.type'=>'C',
				'UserFavorite.type_id'=>$course_id,
				'UserFavorite.user_id <>'=>0,
				'UserFavorite.type_id <>'=>0,
				'UserFavorite.status'=>'1'
			)));
			if(!empty($course_favorite_info))$course_info['course_favorite']=$course_favorite_info;
			
			if(!empty($course_class_wares)){
				if(isset($course_class_wares['evaluation'])&&!empty($course_class_wares['evaluation'])){
					$UserEvaluationLog = ClassRegistry::init('UserEvaluationLog');
					$ware_evaluation_cond=array(
						'UserEvaluationLog.user_id'=>$user_id,
						'UserEvaluationLog.evaluation_id'=>$course_class_wares['evaluation'],
						'UserEvaluationLog.status'=>'1'
					);
					$ware_evaluation_infos=$UserEvaluationLog->find('all',array('conditions'=>$ware_evaluation_cond,'fields'=>'evaluation_id,Max(UserEvaluationLog.score) as evaluation_score,Min(Evaluation.pass_score) as pass_score','group'=>'evaluation_id'));
					if(!empty($ware_evaluation_infos)){
						$ware_evaluation=array();
						foreach($ware_evaluation_infos as $v){
							if($v[0]['pass_score']>$v[0]['evaluation_score']){
								$ware_evaluation[$v['UserEvaluationLog']['evaluation_id']]='1';
							}else{
								$ware_evaluation[$v['UserEvaluationLog']['evaluation_id']]='0';
							}
						}
						$course_info['ware_result']['ware_evaluation']=$ware_evaluation;
					}
				}
				if(isset($course_class_wares['assignment'])&&!empty($course_class_wares['assignment'])){
					$CourseAssignmentScore = ClassRegistry::init('CourseAssignmentScore');
					$ware_assignment_cond=array(
						'CourseAssignmentScore.score >'=>0,
						'CourseAssignmentScore.status'=>'1',
						'CourseAssignment.course_ware_id'=>$course_class_wares['assignment'],
						'CourseAssignment.course_id'=>$course_id,
						'CourseAssignment.status'=>'1'
					);
					$ware_assignment_infos=$CourseAssignmentScore->find('all',array('conditions'=>$ware_assignment_cond,'fields'=>'CourseAssignment.course_ware_id,Max(CourseAssignmentScore.score) as assignment_score','group'=>'CourseAssignment.course_ware_id'));
					if(!empty($ware_assignment_infos)){
						$ware_assignment=array();
						foreach($ware_assignment_infos as $v){
							$ware_assignment[$v['CourseAssignment']['course_ware_id']]=$v[0]['assignment_score'];
						}
						$course_info['ware_result']['ware_assignment']=$ware_assignment;
					}
				}
			}
            }
            $course_user_list=$UserCourseClassDetail->find('all',array('fields'=>'DISTINCT UserCourseClass.user_id','conditions'=>array('UserCourseClass.course_id'=>$course_data['Course']['id'],'UserCourseClassDetail.course_class_id'=>$course_class_list)));
            $course_info['course_user_total']=sizeof($course_user_list);
            
            $course_type_code=trim($course_data['Course']['course_type_code']);
            if(trim($course_type_code)!=''){
	            $CourseType = ClassRegistry::init('CourseType');
	            $CourseTypeInfo=$CourseType->find('first',array('conditions'=>array('code'=>$course_type_code,'status'=>'1')));
	            if(!empty($CourseTypeInfo))$course_info['CourseTypeInfo']=$CourseTypeInfo['CourseType'];
            }
            $CourseComment = ClassRegistry::init('CourseComment');
            $CourseCommentScore=$CourseComment->find('first',array('fields'=>'SUM(params01+params02+params03)/3/count(*) as score_sum','conditions'=>array('course_id'=>$course_data['Course']['id'],'CourseComment.user_id >'=>0),'recursive' => -1));
            $course_info['course_avg']=floatval($CourseCommentScore[0]['score_sum'])>0?floatval($CourseCommentScore[0]['score_sum'])/15*10:10;
        }
        return $course_info;
    }

    function course_chapter($params=array()){
        if (isset($params['course_code'])&&$params['course_code']!='') {
            $course_code=$params['course_data'];
        }else{
            $conditions=array();
            $conditions['Course.status']='1';
            $conditions['Course.id']=isset($params['id'])?intval($params['id']):0;
            $course_data=$this->find('first',array('conditions'=>$conditions));
            $course_code=isset($course_data['Course']['code'])?$course_data['Course']['code']:'';
        }
        $CourseClass = ClassRegistry::init('CourseClass');
        $course_chapter=$CourseClass->course_chapter_tree($course_code);
        return $course_chapter;
    }

    function import_course_list($import_type='U',$import_type_id=0){
        $conditions=array();
        $course_user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
        $course_user_ids=array(0);
        if($import_type=='O')$course_user_ids[]=$course_user_id;
        $conditions['Course.user_id']=array_unique($course_user_ids);
        $conditions['Course.status']='1';
        if($import_type=='O'&&!empty($import_type_id)){
            $OrganizationRelation = ClassRegistry::init('OrganizationRelation');
            $organization_course_ids=$OrganizationRelation->find('list',array('fields'=>'type_id','conditions'=>array('OrganizationRelation.type'=>'course','OrganizationRelation.organization_id'=>$import_type_id)));
            if(!empty($organization_course_ids))$conditions['not']['Course.id']=$organization_course_ids;
        }
        $import_course = $this->find('all',array('conditions'=>$conditions,'fields'=>'id,user_id,name','order'=>'user_id,id'));
        $import_course_data=array();
        if(!empty($import_course)){
            foreach($import_course as $v){
                if(!empty($course_user_id)&&$course_user_id==$v['Course']['user_id']){
                    $import_course_data['U'][]=$v['Course'];
                }else{
                    $import_course_data['S'][]=$v['Course'];
                }
            }
        }
        return $import_course_data;
    }
    
    function access_permission($controller=null,$course_id=0,$course_class_id=0,$detail_flag=true){
    		$result=array(
    			'code'=>'0',
    			'message'=>"Course not found"
    		);
    		$course_data=$this->find('first',array('conditions'=>array('Course.id'=>$course_id,'Course.status'=>'1')));
    		if(!empty($course_data)){
    			$result['message']='';
    			$access_message=array();
    			$success_message=array();
    			
    			if($course_data['Course']['allow_learning']=='2'){
    				$result['code']='1';
    				return $result;
    			}
    			$user_id=isset($_SESSION['User']['User']['id'])?$_SESSION['User']['User']['id']:0;
    			$course_user_id=$course_data['Course']['user_id'];
    			if(empty($user_id)){
    				$result['code']='-1';
    				$result['message']="Please login";
    				return $result;
    			}else if(!empty($course_user_id)&&$course_user_id==$user_id){
    				$result['code']='1';
    				return $result;
    			}
    			$UserCourseClass = ClassRegistry::init('UserCourseClass');
    			$CourseUserTotal=$UserCourseClass->find('count',array('conditions'=>array('UserCourseClass.course_id'=>$course_id,'UserCourseClass.user_id <>'=>$user_id)));
    			$max_course_read=intval(Configure::read('HR.max_course_read'));
    			
    			if($CourseUserTotal>$max_course_read){
    				$result['message']=array(
    					'max_course_read'=>$max_course_read."/".$CourseUserTotal
    				);
    				return $result;
    			}
    			$CourseClass = ClassRegistry::init('CourseClass');
    			$Precondition = ClassRegistry::init('Precondition');
    			
			//课程
			$PreconditionList=$Precondition->pre_condition_list('course',$course_data['Course']['code']);
			if(!empty($PreconditionList)){
				foreach($PreconditionList as $k=>$v){
					if($k=='parent_course'){
						$parent_course_ids=explode(',',$v);
						$parent_course_list=$this->find('list',array('fields'=>'Course.id,Course.name','conditions'=>array('Course.status'=>'1','Course.id'=>$parent_course_ids)));
						if(!empty($parent_course_list)){
							$parent_course_ids=array_keys($parent_course_list);
							$conditions=array();
							$conditions['UserCourseClass.user_id >']=0;
							$conditions['UserCourseClass.user_id']=$user_id;
							$conditions['UserCourseClass.course_id']=$parent_course_ids;
							$conditions['UserCourseClass.status']='1';
							$parent_course_infos=$UserCourseClass->find('list',array('fields'=>'UserCourseClass.course_id,UserCourseClass.status','conditions'=>$conditions));
							if(empty($parent_course_infos)){
								$access_message['parent_course']=$parent_course_list;
							}else if(!empty($parent_course_infos)&&sizeof($parent_course_infos)<sizeof($parent_course_list)){
								$parent_complete_course=array_keys($parent_course_infos);
								foreach($parent_course_list as $kk=>$vv){
									if(!in_array($kk,$parent_complete_course)){
										$access_message['parent_course'][$kk]=$vv;
									}else{
										$success_message['parent_course'][$kk]=$vv;
									}
								}
							}else{
								$success_message['parent_course']=$parent_course_list;
							}
						}
					}
				}
			}
			if(empty($PreconditionList)&&floatval($course_data['Course']['price'])>0||!empty($PreconditionList)&&isset($course_data['Course']['must_buy'])&&floatval($course_data['Course']['price'])>0){
				$OrderProduct = ClassRegistry::init('OrderProduct');
				$order_cond=array();
				$order_cond['Order.user_id']=$user_id;
				$order_cond['Order.status']='1';
				$order_cond['Order.payment_status']='2';
				$order_cond['OrderProduct.item_type']='course';
				$order_cond['OrderProduct.product_id']=$course_id;
				$course_order=$OrderProduct->find('count',array('conditions'=>$order_cond));
				if(empty($course_order)){
		    			$conditions=array();
					$conditions['UserCourseClass.user_id >']=0;
					$conditions['UserCourseClass.user_id']=$user_id;
					$conditions['UserCourseClass.course_id']=$course_id;
					$conditions['UserCourseClass.status']='1';
					$user_course_info=$UserCourseClass->find('list',array('fields'=>'UserCourseClass.course_id,UserCourseClass.status','conditions'=>$conditions));
		    			if(empty($user_course_info)){
						$access_message['buy']=floatval($course_data['Course']['price']);
		    			}else{
						$course_class_list=$CourseClass->find('list',array('fields'=>'CourseClass.id','conditions'=>array('CourseClass.course_code'=>$course_data['Course']['code'],'CourseClass.status'=>'1','CourseClass.price >'=>0)));
						if(!empty($course_class_list)){
							$order_cond=array();
							$order_cond['Order.user_id']=$user_id;
							$order_cond['Order.status']='1';
							$order_cond['Order.payment_status']='2';
							$order_cond['OrderProduct.item_type']='course_class';
							$order_cond['OrderProduct.product_id']=$course_class_list;
							$course_class_order=$OrderProduct->find('all',array('fields'=>'OrderProduct.product_id','conditions'=>$order_cond,'group'=>'OrderProduct.product_id'));
							if(sizeof($course_class_order)==sizeof($course_class_list)){
								if(isset($access_message['buy']))unset($access_message['buy']);
							}
						}
					}
				}else if(!empty($course_order)&&isset($course_data['Course']['must_buy'])&&$course_data['Course']['must_buy']!='1'){
					$result['code']='1';
					return $result;
				}else if(!empty($course_order)){
					$success_message['buy']=floatval($course_data['Course']['price']);
				}
			}
    			if(!empty($course_class_id)){
    				//if(isset($access_message['buy']))unset($access_message['buy']);
    				//单个课时
    				$course_class_detail=$CourseClass->find('first',array('conditions'=>array('CourseClass.course_code'=>$course_data['Course']['code'],'CourseClass.id'=>$course_class_id,'CourseClass.status'=>'1')));
    				if(!empty($course_class_detail)){
    					if(isset($course_class_detail['CourseClass']['is_probation'])&&$course_class_detail['CourseClass']['is_probation']=='1'){//试读
    						$result['code']='1';
    						return $result;
    					}
    					$PreconditionList=$Precondition->pre_condition_list('course_class',$course_data['Course']['code'].$course_class_id);
    					if(!empty($PreconditionList)){
        					$server_host = isset($controller->server_host)?$controller->server_host:'';
						$webroot = isset($controller->webroot)?$controller->webroot:'';
    						foreach($PreconditionList as $k=>$v){
    							if($k=='parent_course_class'&&trim($v)!=''){//前置课时
    								$UserCourseClassDetail = ClassRegistry::init('UserCourseClassDetail');
    								$parent_course_class_ids=explode(',',$v);
    								$parent_course_class_list=$CourseClass->find('list',array('fields'=>'CourseClass.id,CourseClass.name','conditions'=>array('CourseClass.status'=>'1','CourseClass.id'=>$parent_course_class_ids)));
    								if(!empty($parent_course_class_list)){
    									$parent_course_class_ids=array_keys($parent_course_class_list);
    									$conditions=array();
									$conditions['UserCourseClass.user_id >']=0;
									$conditions['UserCourseClass.user_id']=$user_id;
									$conditions['UserCourseClass.course_id']=$course_id;
									$conditions['UserCourseClassDetail.status']='1';
									$conditions['UserCourseClassDetail.course_class_id']=$parent_course_class_ids;
									$parent_course_class_infos=$UserCourseClassDetail->find('all',array('fields'=>'UserCourseClassDetail.course_class_id,UserCourseClassDetail.status','conditions'=>$conditions));
    									if(empty($parent_course_class_infos)){
    										$access_message['parent_course_class']=array_values($parent_course_class_list);
    									}else if(!empty($parent_course_class_infos)&&sizeof($parent_course_class_infos)<sizeof($parent_course_class_list)){
    										$parent_complete_class=array();
    										foreach($parent_course_class_infos as $vv){
    											$parent_complete_class[]=$vv['UserCourseClassDetail']['course_class_id'];
    										}
    										foreach($parent_course_class_list as $kk=>$vv){
    											if(!in_array($kk,$parent_complete_class)){
    												$access_message['parent_course_class'][$kk]=$vv;
    											}else{
    												$success_message['parent_course_class'][$kk]=$vv;
    											}
    										}
    									}else{
    										$success_message['parent_course_class']=$parent_course_class_infos;
    									}
    								}
    							}else if($k=='share_count'&&trim($v)!=''){//分享次数
    								$share_condition=explode(chr(13).chr(10),$v);
    								$UserShareLog = ClassRegistry::init('UserShareLog');
    								$conditions=array();
    								$conditions['UserShareLog.user_id']=$user_id;
    								$conditions['UserShareLog.share_link <>']='';
    								foreach($share_condition as $vv){
    									$share_condition_data=explode(',',$vv);
    									$share_type=isset($share_condition_data[0])?$share_condition_data[0]:'';
    									$share_page=isset($share_condition_data[1])?$share_condition_data[1]:'';
    									$share_count=isset($share_condition_data[2])?$share_condition_data[2]:0;
    									if($share_type=='course_class'){
    										$conditions['UserShareLog.share_link like']=$server_host.$webroot."courses/detail/{$course_id}/{$share_page}?share_from%";
    									}else if($share_type=='home'){
    										$conditions['or'][]['UserShareLog.share_link like']=$server_host."?share_from%";
    										$conditions['or'][]['UserShareLog.share_link like']=$server_host.$webroot."?share_from%";
    									}else if($share_type=='article'){
    										$conditions['or'][]['UserShareLog.share_link like']=$server_host.$webroot."articles/".$share_page."?share_from%";
    										$conditions['or'][]['UserShareLog.share_link like']=$server_host.$webroot."articles/view/".$share_page."?share_from%";
    									}else if($share_type=='page'){
    										$conditions['or'][]['UserShareLog.share_link like']=$server_host.$webroot."pages/".$share_page."?share_from%";
    										$conditions['or'][]['UserShareLog.share_link like']=$server_host.$webroot."pages/view/".$share_page."?share_from%";
    									}else if($share_type=='topic'){
    										$conditions['or'][]['UserShareLog.share_link like']=$server_host.$webroot."topics/".$share_page."?share_from%";
    										$conditions['or'][]['UserShareLog.share_link like']=$server_host.$webroot."topics/view/".$share_page."?share_from%";
    									}else{
    										continue;
    									}
    									$user_share_total=$UserShareLog->find('count',array('conditions'=>$conditions));
    									$link_source="";$link_page="";
    									if($share_type=='course_class'){
    										$share_course_class=$CourseClass->find('first',array('fields'=>'CourseClass.id,CourseClass.name','conditions'=>array('CourseClass.id'=>$share_page,'CourseClass.status'=>'1')));
    										if(!empty($share_course_class)){
    											$link_source=$share_course_class['CourseClass']['name'];
    											$link_page=$webroot."courses/detail/{$course_id}/{$share_page}";
    										}
    									}else if($share_type=='home'){
    										$link_source=isset($controller->ld['home'])?$controller->ld['home']:'首页';
    										$link_page=$webroot;
    									}else if($share_type=='article'){
    										$Article = ClassRegistry::init('Article');
    										$article_info=$Article->find('first',array('fields'=>'Article.id,ArticleI18n.title','conditions'=>array('Article.id'=>$share_page,'Article.status'=>'1','ArticleI18n.title <>'=>'')));
    										if(!empty($article_info)){
    											$link_source=$article_info['ArticleI18n']['title'];
    											$link_page=$webroot.'articles/view/'.$share_page;
    										}
    									}else if($share_type=='page'){
    										$Page = ClassRegistry::init('Page');
    										$page_info=$Page->find('first',array('fields'=>'Page.id,PageI18n.title','conditions'=>array('Page.id'=>$share_page,'Page.status'=>'1','PageI18n.title <>'=>'')));
    										if(!empty($page_info)){
    											$link_source=$page_info['PageI18n']['title'];
    											$link_page=$webroot.'pages/view/'.$share_page;
    										}
    									}else if($share_type=='topic'){
    										$Topic = ClassRegistry::init('Topic');
    										$topic_info=$Topic->find('first',array('fields'=>'Topic.id,TopicI18n.title','conditions'=>array('Topic.id'=>$share_page,'Topic.status'=>'1','TopicI18n.title <>'=>'')));
    										if(!empty($topic_info)){
    											$link_source=$topic_info['TopicI18n']['title'];
    											$link_page=$webroot.'topics/view/'.$share_page;
    										}
    									}
    									if($link_source==''||$link_page=='')continue;
    									
    									if($user_share_total<intval($share_count)){
    										$access_message['share_count'][]=array(
	    										$link_source,$link_page,
	    										$user_share_total."/".$share_count,
	    										$share_type
	    									);
	    								}else{
	    									$success_message['share_count'][]=array(
	    										$link_source,$link_page,
	    										$user_share_total."/".$share_count,
	    										$share_type
	    									);
	    								}
    								}
    							}else if($k=='shared_access'&&trim($v)!=''){//分享访问数
    								$share_condition=explode(chr(13).chr(10),$v);
    								$ShareAffiliateLog = ClassRegistry::init('ShareAffiliateLog');
    								$conditions=array();
    								$conditions['ShareAffiliateLog.user_id']=$user_id;
    								$conditions['ShareAffiliateLog.identification <>']='';
    								foreach($share_condition as $vv){
    									$share_condition_data=explode(',',$vv);
    									$share_type=isset($share_condition_data[0])?$share_condition_data[0]:'';
    									$share_page=isset($share_condition_data[1])?$share_condition_data[1]:'';
    									$share_count=isset($share_condition_data[2])?$share_condition_data[2]:0;
    									if($share_type=='course_class'){
    										$conditions['ShareAffiliateLog.link_source like']=$server_host.$webroot."courses/detail/{$course_id}/{$share_page}?share_from%";
    									}else if($share_type=='home'){
    										$conditions['or'][]['ShareAffiliateLog.link_source like']=$server_host."?share_from%";
    										$conditions['or'][]['ShareAffiliateLog.link_source like']=$server_host.$webroot."?share_from%";
    									}else if($share_type=='article'){
    										$conditions['or'][]['ShareAffiliateLog.link_source like']=$server_host.$webroot."articles/".$share_page."?share_from%";
    										$conditions['or'][]['ShareAffiliateLog.link_source like']=$server_host.$webroot."articles/view/".$share_page."?share_from%";
    									}else if($share_type=='page'){
    										$conditions['or'][]['ShareAffiliateLog.link_source like']=$server_host.$webroot."pages/".$share_page."?share_from%";
    										$conditions['or'][]['ShareAffiliateLog.link_source like']=$server_host.$webroot."pages/view/".$share_page."?share_from%";
    									}else if($share_type=='topic'){
    										$conditions['or'][]['ShareAffiliateLog.link_source like']=$server_host.$webroot."topics/".$share_page."?share_from%";
    										$conditions['or'][]['ShareAffiliateLog.link_source like']=$server_host.$webroot."topics/view/".$share_page."?share_from%";
    									}else{
    										continue;
    									}
	    								$share_affiliate_list=$ShareAffiliateLog->find('all',array('fields'=>'DISTINCT ShareAffiliateLog.identification','conditions'=>$conditions));
	    								$share_affiliate_total=sizeof($share_affiliate_list);
    									$link_source="";$link_page="";
    									if($share_type=='course_class'){
    										$share_course_class=$CourseClass->find('first',array('fields'=>'CourseClass.id,CourseClass.name','conditions'=>array('CourseClass.id'=>$share_page,'CourseClass.status'=>'1')));
    										if(!empty($share_course_class)){
    											$link_source=$share_course_class['CourseClass']['name'];
    											$link_page=$webroot."courses/detail/{$course_id}/{$share_page}";
    										}
    									}else if($share_type=='home'){
    										$link_source=isset($controller->ld['home'])?$controller->ld['home']:'首页';
    										$link_page=$webroot;
    									}else if($share_type=='article'){
    										$Article = ClassRegistry::init('Article');
    										$article_info=$Article->find('first',array('fields'=>'Article.id,ArticleI18n.title','conditions'=>array('Article.id'=>$share_page,'Article.status'=>'1','ArticleI18n.title <>'=>'')));
    										if(!empty($article_info)){
    											$link_source=$article_info['ArticleI18n']['title'];
    											$link_page=$webroot.'articles/view/'.$share_page;
    										}
    									}else if($share_type=='page'){
    										$Page = ClassRegistry::init('Page');
    										$page_info=$Page->find('first',array('fields'=>'Page.id,PageI18n.title','conditions'=>array('Page.id'=>$share_page,'Page.status'=>'1','PageI18n.title <>'=>'')));
    										if(!empty($page_info)){
    											$link_source=$page_info['PageI18n']['title'];
    											$link_page=$webroot.'pages/view/'.$share_page;
    										}
    									}else if($share_type=='topic'){
    										$Topic = ClassRegistry::init('Topic');
    										$topic_info=$Topic->find('first',array('fields'=>'Topic.id,TopicI18n.title','conditions'=>array('Topic.id'=>$share_page,'Topic.status'=>'1','TopicI18n.title <>'=>'')));
    										if(!empty($topic_info)){
    											$link_source=$topic_info['TopicI18n']['title'];
    											$link_page=$webroot.'topics/view/'.$share_page;
    										}
    									}
    									if($link_source==''||$link_page=='')continue;
	    								if($share_affiliate_total<$share_count){
	    									$access_message['shared_access'][]=array(
	    										$link_source,$link_page,
	    										$share_affiliate_total."/".$share_count,
	    										$share_type
	    									);
	    								}else{
	    									$success_message['shared_access'][]=array(
	    										$link_source,$link_page,
	    										$share_affiliate_total."/".$share_count,
	    										$share_type
	    									);
	    								}
    								}
    							}else if($k=='share_registration'&&intval($v)>0){//分享注册数
    								$User = ClassRegistry::init('User');
    								$child_register_total=$User->find('count',array('conditions'=>array('User.parent_id'=>$user_id,'User.status'=>'1')));
    								if($child_register_total<intval($v)){
    									$access_message['share_registration']=$child_register_total."/".intval($v);
    								}else{
    									$success_message['share_registration']=$child_register_total."/".intval($v);
    								}
    							}else if($k=='shared_consumption_number'&&floatval($v)>0){//分享消费数
    								$User = ClassRegistry::init('User');
    								$Order = ClassRegistry::init('Order');
    								$child_register_user=$User->find('list',array('fields'=>'User.id','conditions'=>array('User.parent_id'=>$user_id,'User.status'=>'1')));
    								$order_cond=array();
								$order_cond['Order.status']='1';
								$order_cond['Order.payment_status']='2';
    								if(!empty($child_register_user)){
    									$order_cond['Order.user_id']=$child_register_user;
    								}else{
    									$order_cond['Order.user_id <']=0;
    								}
    								$share_order_data=$Order->find('first',array('fields'=>array("SUM(Order.user_balance) as balance_toal","SUM(Order.money_paid) as payment_total"),'conditions'=>$order_cond,'recursive' => -1));
    								if(!empty($share_order_data)&&!empty($share_order_data[0])>0){
    									$shared_consumption_number=floatval(array_sum($share_order_data[0]));
    									if($shared_consumption_number<floatval($v)){
    										$access_message['shared_consumption_number']=$shared_consumption_number."/".floatval($v);
    									}else{
    										$success_message['shared_consumption_number']=$shared_consumption_number."/".floatval($v);
    									}
    								}else{
    									$access_message['shared_consumption_number']="0/".floatval($v);
    								}
	    						}else if($k=='number_of_apprentice'&&intval($v)>0){//收徒数量
    								$UserRelationship = ClassRegistry::init('UserRelationship');
    								$apprentice_total = $UserRelationship->find('count', array('conditions' => array('UserRelationship.parent_user_id' => $user_id,'UserRelationship.status' =>1)));
    								if($apprentice_total<intval($v)){
    									$access_message['number_of_apprentice']=$apprentice_total."/".intval($v);
    								}else{
    									$success_message['number_of_apprentice']=$apprentice_total."/".intval($v);
    								}
    							}
    						}
    					}
    					if(empty($PreconditionList)&&floatval($course_class_detail['CourseClass']['price'])>0&&!isset($success_message['buy'])||!empty($PreconditionList)&&isset($course_class_detail['CourseClass']['must_buy'])&&floatval($course_class_detail['CourseClass']['price'])>0&&!isset($success_message['buy'])){
    						$OrderProduct = ClassRegistry::init('OrderProduct');
    						$order_cond=array();
						$order_cond['Order.user_id']=$user_id;
						$order_cond['Order.status']='1';
						$order_cond['Order.payment_status']='2';
						$order_cond['OrderProduct.item_type']='course_class';
						$order_cond['OrderProduct.product_id']=$course_class_id;
						$course_order=$OrderProduct->find('count',array('conditions'=>$order_cond));
						if(empty($course_order)){
							$conditions=array();
							$conditions['UserCourseClass.user_id >']=0;
							$conditions['UserCourseClass.user_id']=$user_id;
							$conditions['UserCourseClass.course_id']=$course_id;
							$conditions['UserCourseClass.status']='1';
							$user_course_info=$UserCourseClass->find('list',array('fields'=>'UserCourseClass.course_id,UserCourseClass.status','conditions'=>$conditions));
							if(empty($user_course_info)){
								$access_message['buy']=floatval($course_class_detail['CourseClass']['price'])>0?floatval($course_class_detail['CourseClass']['price']):floatval($course_data['Course']['price']);
							}
						}else if(!empty($course_order)&&isset($course_class_detail['CourseClass']['must_buy'])&&$course_class_detail['CourseClass']['must_buy']!='1'){
							$result['code']='1';
    							return $result;
						}else if(!empty($course_order)){
							$success_message['buy']=floatval($course_class_detail['CourseClass']['price'])>0?floatval($course_class_detail['CourseClass']['price']):floatval($course_data['Course']['price']);
						}
    					}
    				}
			}
    			if(isset($access_message)&&!empty($access_message)){
    				$result['message']=$access_message;
    				if(!empty($success_message)){
    					$result['access']=$success_message;
    				}
    				if($detail_flag){
	    				$result['course_data']=$course_data['Course'];
	    				if(isset($course_class_detail['CourseClass'])){
	    					$result['course_class_detail']=$course_class_detail['CourseClass'];
	    				}
    				}
    			}else{
    				$result['code']='1';
    			}
    		}
    		return $result;
    }
}