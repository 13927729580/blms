<?php

/*****************************************************************************
 * UserEvaluationLog 评测记录
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为UserEvaluationLogsController的控制器
 *评测
 *
 *@var
 *@var
 *@var
 *@var
 */
class UserEvaluationLogsController extends AppController
{
	public $name = 'UserEvaluationLogs';
	public $helpers = array('Html','Pagination');
	public $uses = array('Evaluation','EvaluationRule','EvaluationCategory','EvaluationQuestion','EvaluationOption','UserEvaluationLog','UserEvaluationLogDetail','InformationResource','Resource','Ability','OrganizationMember','OrganizationMemberJob','OrganizationDepartment','OrganizationShare','Organization','OrganizationRelation');
	public $components = array('RequestHandler','Pagination');
	
	function index($page=1,$limit=10){
		//登录验证
        	$this->checkSessionUser();
		$_GET=$this->clean_xss($_GET);
		$this->layout = 'usercenter';//引入模版
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
			Configure::write('debug', 0);
			$this->layout = 'ajax';
		}
		$this->page_init();                        //页面初始化
        	$this->pageTitle = '我的评测 - '.$this->configs['shop_title'];
		//当前位置开始
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => '我的评测', 'url' => '');
		
		$this->loadModel('User');
		$this->loadModel('UserFans');
		$this->loadModel('Blog');
		
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
		
		$share_evaluation_ids=array();
		$share_evaluation_cond=array();
		$user_member_list = $this->OrganizationMember->find('list',array('fields'=>'OrganizationMember.organization_id,OrganizationMember.id','conditions'=>array('OrganizationMember.user_id'=>$user_id,'OrganizationMember.status'=>'1')));
		if(!empty($user_member_list)){
			$user_member_organization=array_keys($user_member_list);
			$user_member_department=$this->OrganizationMemberJob->find('list',array('fields'=>'organization_department_id','conditions'=>array('organization_department_id <>'=>0,'organization_id'=>$user_member_organization,'organization_member_id'=>$user_member_list)));
			$share_evaluation_cond['or'][]=array(
				'share_object'=>'0',
				'share_object_ids'=>$user_member_list
			);
			if(trim($user_list['User']['mobile'])!=''){
				$share_evaluation_cond['or'][]=array(
					'share_object'=>'0',
					'share_object_ids like'=>"%|".$user_list['User']['mobile']
				);
			}
			$share_evaluation_cond['or'][]=array(
				'share_object'=>'2',
				'share_object_ids'=>$user_member_organization
			);
			if(!empty($user_member_department)){
				$share_evaluation_cond['or'][]=array(
					'share_object'=>'1',
					'share_object_ids'=>$user_member_department
				);
			}
		}
		$manager_organization_ids=$this->Organization->find('list',array('fields'=>'id','conditions'=>array('manage_user'=>$user_id,'status'=>'1')));
		if(!empty($manager_organization_ids)){
			$share_evaluation_cond['or'][]=array(
				'share_object'=>'3',
				'share_object_ids'=>$manager_organization_ids
			);
		}
		if(!empty($share_evaluation_cond)){
			$share_evaluation_cond['OrganizationShare.share_type']='evaluation';
			$share_evaluation_cond['OrganizationShare.share_type_id <>']='0';
			$share_evaluation_ids=$this->OrganizationShare->find('list',array('conditions'=>$share_evaluation_cond,'fields'=>'share_type_id'));
		}
		$user_evaluation_view_cond=array();
		$user_evaluation_view_cond['UserEvaluationLog.user_id']=$user_id;
		$user_evaluation_view_cond['UserEvaluationLog.evaluation_id >']=0;
		$user_evaluation_view_ids=$this->UserEvaluationLog->find('list',array('conditions'=>$user_evaluation_view_cond,'fields'=>'evaluation_id'));

		$conditions=array();
		$conditions['or'][]['Evaluation.user_id']=$user_id;
		if(!empty($share_evaluation_ids))$conditions['or'][]['Evaluation.id']=$share_evaluation_ids;
		if(!empty($user_evaluation_view_ids))$conditions['or'][]['Evaluation.id']=$user_evaluation_view_ids;
		$parameters=array();
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'user_evaluation_logs', 'action' => 'index', 'page' => $page, 'limit' => $limit);
		//分页参数
		$options = array('page' => $page, 'show' => $limit, 'modelClass' => 'Evaluation');
		$this->Pagination->init($conditions, $parameters, $options); // Added
		$UserEvaluation_lists=$this->Evaluation->find('all',array('conditions'=>$conditions,'order'=>'Evaluation.modified desc','page'=>$page,'limit'=>$limit));
		$this->set('UserEvaluation_lists',$UserEvaluation_lists);
		if(!empty($UserEvaluation_lists)){
			$evaluation_codes=array();
			// foreach($UserEvaluation_lists as $v)$evaluation_codes[]=$v['Evaluation']['code'];
			// $evaluation_class_infos=$this->EvaluationQuestion->find('all',array('conditions'=>array('EvaluationQuestion.evaluation_codes'=>$evaluation_codes),'fields'=>'evaluation_codes.evaluation_codes,count(*) as name','group'=>'evaluation_codes'));
			// pr($evaluation_class_infos);
			// $course_class_list=array();
			// foreach($course_class_infos as $v)$course_class_list[$v['CourseClass']['course_code']]=$v[0]['class_count'];
			// $this->set('course_class_list',$course_class_list);
			if(!empty($share_evaluation_ids)){
				$share_evaluation_cond['share_type_id']=$share_evaluation_ids;
				$share_evaluation_objects=$this->OrganizationShare->find('all',array('fields'=>'share_type_id,organization_id,share_user','conditions'=>$share_evaluation_cond,'group'=>'share_type_id,organization_id,share_user','order'=>'id desc'));
				$share_evaluation_object_list=array();
				$share_evaluation_user_ids=array();$share_evaluation_organization_ids=array();
				$share_evaluation_user_list=array();$share_evaluation_organization_list=array();
				foreach($share_evaluation_objects as $v){
					$share_evaluation_object_list[$v['OrganizationShare']['share_type_id']]=$v['OrganizationShare'];
					if(!empty($v['OrganizationShare']['share_user']))$share_evaluation_user_ids[]=$v['OrganizationShare']['share_user'];
					if(!empty($v['OrganizationShare']['organization_id']))$share_evaluation_organization_ids[]=$v['OrganizationShare']['organization_id'];
				}
				if(!empty($share_evaluation_user_ids)){
					$share_evaluation_user_infos=$this->User->find('all',array('fields'=>'id,name,first_name,mobile,email','conditions'=>array('User.id'=>$share_evaluation_user_ids)));
					foreach($share_evaluation_user_infos as $v){
						$share_evaluation_user_list[$v['User']['id']]=$v['User'];
					}
				}
				if(!empty($share_evaluation_organization_ids))$share_evaluation_organization_list=$this->Organization->find('list',array('fields'=>'id,name','conditions'=>array('id'=>$share_evaluation_organization_ids)));
				$this->set('share_evaluation_user_list',$share_evaluation_user_list);
				$this->set('share_evaluation_organization_list',$share_evaluation_organization_list);
				$this->set('share_evaluation_object_list',$share_evaluation_object_list);
			}
			$this->set('share_evaluation',$share_evaluation_ids);
		}
		$this->set('user_evaluation_view_ids',$user_evaluation_view_ids);
		$evaluation_study=$this->UserEvaluationLog->find('all',array('conditions'=>$user_evaluation_view_cond,'order'=>"UserEvaluationLog.id desc"));
		$evaluation_question = $this->EvaluationQuestion->find('all',array('conditions'=>array('status'=>1)));
		$evaluation_question_list = array();
		foreach ($evaluation_question as $k => $v) {
			$evaluation_question_list[$v['EvaluationQuestion']['evaluation_code']][] = $v['EvaluationQuestion']['name'];
		}
		$this->set('evaluation_question_list',$evaluation_question_list);
		$this->set('evaluation_study',$evaluation_study);
	}

	public function get_eval_count(){
		$this->loadModel('User');
		$this->loadModel('UserFans');
		$this->loadModel('Blog');
		
		$user_id=$_SESSION['User']['User']['id'];
		//获取我的信息
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
		if ($user_list['User']['rank'] > 0) {
			$rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
			$user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
		}
		$this->set('user_list', $user_list);
		
		$share_evaluation_ids=array();
		$share_evaluation_cond=array();
		$user_member_list = $this->OrganizationMember->find('list',array('fields'=>'OrganizationMember.organization_id,OrganizationMember.id','conditions'=>array('OrganizationMember.user_id'=>$user_id,'OrganizationMember.status'=>'1')));
		if(!empty($user_member_list)){
			$user_member_organization=array_keys($user_member_list);
			$user_member_department=$this->OrganizationMemberJob->find('list',array('fields'=>'organization_department_id','conditions'=>array('organization_department_id <>'=>0,'organization_id'=>$user_member_organization,'organization_member_id'=>$user_member_list)));
			$share_evaluation_cond['or'][]=array(
				'share_object'=>'0',
				'share_object_ids'=>$user_member_list
			);
			if(!empty($user_member_department)){
				$share_evaluation_cond['or'][]=array(
					'share_object'=>'1',
					'share_object_ids'=>$user_member_department
				);
			}
		}
		$manager_organization_ids=$this->Organization->find('list',array('fields'=>'id','conditions'=>array('manage_user'=>$user_id,'status'=>'1')));
		if(!empty($manager_organization_ids)){
			$share_evaluation_cond['or'][]=array(
				'share_object'=>'2',
				'share_object_ids'=>$manager_organization_ids
			);
		}
		if(!empty($share_evaluation_cond)){
			$share_evaluation_cond['OrganizationShare.share_type']='evaluation';
			$share_evaluation_cond['OrganizationShare.share_type_id <>']='0';
			$share_evaluation_ids=$this->OrganizationShare->find('list',array('conditions'=>$share_evaluation_cond,'fields'=>'share_type_id'));
		}
		$user_evaluation_view_cond=array();
		$user_evaluation_view_cond['UserEvaluationLog.user_id']=$user_id;
		$user_evaluation_view_cond['UserEvaluationLog.evaluation_id >']=0;
		$user_evaluation_view_ids=$this->UserEvaluationLog->find('list',array('conditions'=>$user_evaluation_view_cond,'fields'=>'evaluation_id'));

		$conditions=array();
		$conditions['or'][]['Evaluation.user_id']=$user_id;
		if(!empty($share_evaluation_ids))$conditions['or'][]['Evaluation.id']=$share_evaluation_ids;
		if(!empty($user_evaluation_view_ids))$conditions['or'][]['Evaluation.id']=$user_evaluation_view_ids;
		$UserEvaluation_lists=$this->Evaluation->find('count',array('conditions'=>$conditions));
		die(json_encode($UserEvaluation_lists));
	}
	
	/**
	*	课程
	*/
	public function view($id=0){
		//登录验证
        	$this->checkSessionUser();
		$this->layout = 'default_full';
		
		$this->ur_heres[] = array('name' => '评测' , 'url' => '/evaluations/');
		
		$evaluation_log_data=$this->UserEvaluationLog->findById($id);
		$this->set('evaluation_log_data',$evaluation_log_data);
		
		$evaluation_id=isset($evaluation_log_data['UserEvaluationLog']['evaluation_id'])?$evaluation_log_data['UserEvaluationLog']['evaluation_id']:0;
		$evaluation_data=$this->Evaluation->find('first',array('conditions'=>array('Evaluation.id'=>$evaluation_id,'Evaluation.status'=>'1')));
		$this->set('evaluation_data',$evaluation_data);
		$this->pageTitle = $evaluation_data['Evaluation']['name'].' - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => $evaluation_data['Evaluation']['name'] , 'url' => '');
		$this->set('ur_heres', $this->ur_heres);
		
		$evaluation_code=$evaluation_data['Evaluation']['code'];
		$evaluation_rule_list=$this->EvaluationRule->evaluation_rule_list($evaluation_code);
		$this->set('evaluation_rule_list',$evaluation_rule_list);
		
		$evaluation_codes=array();
		$evaluation_rule_score=array();
		foreach($evaluation_rule_list as $v){
			$evaluation_codes[]=$v['EvaluationRule']['child_evaluation_code'];
			$evaluation_rule_score[$v['EvaluationRule']['child_evaluation_code']][$v['EvaluationRule']['question_type']]=$v['EvaluationRule']['score'];
		}
		$this->set('evaluation_rule_score',$evaluation_rule_score);
		$evaluation_infos=$this->Evaluation->find('list',array('fields'=>"Evaluation.code,Evaluation.name",'conditions'=>array('Evaluation.code'=>$evaluation_codes)));
		$this->set('evaluation_infos',$evaluation_infos);
		
		$evaluation_questions=$this->UserEvaluationLogDetail->find('all',array('conditions'=>array('UserEvaluationLogDetail.user_evaluation_log_id'=>$id)));
		$this->set('evaluation_questions',$evaluation_questions);
		//pr($evaluation_questions);
		
		$evaluation_question_codes=array();
        	foreach($evaluation_questions as $v){
        		$evaluation_question_codes[]=$v['EvaluationQuestion']['code'];
        	}
        	$evaluation_option_infos=$this->EvaluationOption->find('all',array('conditions'=>array('EvaluationOption.evaluation_question_code'=>$evaluation_question_codes,'EvaluationOption.status'=>'1'),'order'=>"EvaluationOption.evaluation_question_code,EvaluationOption.name"));
        	$evaluation_option_datas=array();
        	foreach($evaluation_option_infos as $v){
        		$evaluation_option_datas[$v['EvaluationOption']['evaluation_question_code']][]=$v['EvaluationOption'];
        	}
        	$this->set('evaluation_option_datas',$evaluation_option_datas);
        	
        	$evaluation_question_error_infos=$this->UserEvaluationLogDetail->find('all',array('conditions'=>array('UserEvaluationLogDetail.user_evaluation_log_id'=>$id,'UserEvaluationLogDetail.answer<>EvaluationQuestion.right_answer'),'fields'=>"EvaluationQuestion.evaluation_code,EvaluationQuestion.question_type,count(0) as total","group"=>"EvaluationQuestion.evaluation_code,EvaluationQuestion.question_type"));
        	$evaluation_question_error_data=array();
        	foreach($evaluation_question_error_infos as $v){
        		$evaluation_question_error_data[$v['EvaluationQuestion']['evaluation_code']][$v['EvaluationQuestion']['question_type']]=$v[0]['total'];
        	}
        	$this->set('evaluation_question_error_data',$evaluation_question_error_data);
        	
        	$information_data=$this->InformationResource->code_information_formated(array('question_type'), $this->locale);
		$this->set('information_data',$information_data);
        	$evaluation_question_type_infos=$this->UserEvaluationLogDetail->find('all',array('conditions'=>array('UserEvaluationLogDetail.user_evaluation_log_id'=>$id),'fields'=>"UserEvaluationLogDetail.answer,EvaluationQuestion.right_answer,EvaluationQuestion.question_type"));
        	$evaluation_question_type_groups=array();
        	if(!empty($evaluation_question_type_infos)){
        		$question_type_group_datas=array();
        		foreach($evaluation_question_type_infos as $v){
        			$question_type=$v['EvaluationQuestion']['question_type'];
        			$evaluation_type_question_count=isset($question_type_group_datas[$question_type]['total'])?$question_type_group_datas[$question_type]['total']:0;
        			if($v['UserEvaluationLogDetail']['answer']==$v['EvaluationQuestion']['right_answer']){
        				$evaluation_question_right_count=isset($question_type_group_datas[$question_type]['right'])?$question_type_group_datas[$question_type]['right']:0;
        				$evaluation_question_right_count++;
        				$question_type_group_datas[$question_type]['right']=$evaluation_question_right_count;
        			}else{
        				$evaluation_question_error_count=isset($question_type_group_datas[$question_type]['error'])?$question_type_group_datas[$question_type]['error']:0;
        				$evaluation_question_error_count++;
        				$question_type_group_datas[$question_type]['error']=$evaluation_question_error_count;
        			}
        			$evaluation_type_question_count++;
        			$question_type_group_datas[$question_type]['total']=$evaluation_type_question_count;
        		}
        		foreach($question_type_group_datas as $question_type=>$question_type_data){
        			$evaluation_question_type_groups[]=array(
        				'question_type'=>$question_type,
        				'question_type_name'=>isset($information_data['question_type'][$question_type])?$information_data['question_type'][$question_type]:'',
        				'total'=>$question_type_data['total'],
        				'right'=>isset($question_type_data['right'])?$question_type_data['right']:0,
        				'error'=>isset($question_type_data['error'])?$question_type_data['error']:0
        			);
        		}
        	}
        	$this->set('evaluation_question_type_groups',$evaluation_question_type_groups);
        	/*
        $evaluation_ability_infos=$this->UserEvaluationLogDetail->find('all',array('conditions'=>array('UserEvaluationLogDetail.user_evaluation_log_id'=>$id),'fields'=>"EvaluationQuestion.ability_code,UserEvaluationLogDetail.answer,EvaluationQuestion.right_answer,EvaluationQuestion.question_type"));
        	$ability_codes=array();
        	$evaluation_ability_datas=array();
        	foreach($evaluation_ability_infos as $v){
        		$question_type=$v['EvaluationQuestion']['question_type'];
        		$ability_code=$v['EvaluationQuestion']['ability_code'];
        		if($ability_code!='')$ability_codes[]=$ability_code;
        		$evaluation_ability_count=isset($evaluation_ability_datas[$ability_code][$question_type]['total'])?$evaluation_ability_datas[$ability_code][$question_type]['total']:0;
        		if($v['UserEvaluationLogDetail']['answer']==$v['EvaluationQuestion']['right_answer']){
        			$evaluation_ability_right_count=isset($evaluation_ability_datas[$ability_code][$question_type]['right'])?$evaluation_ability_datas[$ability_code][$question_type]['right']:0;
        			$evaluation_ability_right_count++;
        			$evaluation_ability_datas[$ability_code][$question_type]['right']=$evaluation_ability_right_count;
        		}else{
        			$evaluation_ability_error_count=isset($evaluation_ability_datas[$ability_code][$question_type]['error'])?$evaluation_ability_datas[$ability_code][$question_type]['error']:0;
        			$evaluation_ability_error_count++;
        			$evaluation_ability_datas[$ability_code][$question_type]['error']=$evaluation_ability_error_count;
        		}
        		$evaluation_ability_count++;
        		$evaluation_ability_datas[$ability_code][$question_type]['total']=$evaluation_ability_count;
        	}
        	$this->set('evaluation_ability_data',$evaluation_ability_datas);
        	
        	if(!empty($ability_codes)){
        		$ability_info=$this->Ability->find('list',array('conditions'=>array('Ability.status'=>'1','Ability.code'=>$ability_codes),'fields'=>'Ability.code,Ability.name'));
        		$this->set('ability_info',$ability_info);
        	}
        	*/
	}
}
