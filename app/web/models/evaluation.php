<?php

/**
 * 	Evaluation 评测
 */
class Evaluation extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    /*
    		评测列表
    */
    function evaluation_list($params=array()){
    		$evaluation_data=array();
		$limit = 10;
		if (isset($params['limit'])) {$limit = $params['limit'];}
		$page = 1;
		if (isset($params['page'])) {$page = $params['page'];}
		$page_controller="evaluation_categories";
		$page_action="index";
		if (isset($params['ControllerObj'])) {
			$page_controller=isset($params['ControllerObj']->params['controller'])?$params['ControllerObj']->params['controller']:$page_controller;
			$page_action=isset($params['ControllerObj']->params['action'])?$params['ControllerObj']->params['action']:$page_action;
		}
    		$conditions=array();
    		$conditions['Evaluation.status']='1';
    		if (isset($params['evaluation_category_code'])&&trim($params['evaluation_category_code'])!='') {
    			$conditions['Evaluation.evaluation_category_code']=trim($params['evaluation_category_code']);
    		}
    		$evaluation_orderby="Evaluation.modified desc,Evaluation.name";
    		if(isset($params['evaluation_orderby'])&&$params['evaluation_orderby']='clicked'){
    			$evaluation_orderby="Evaluation.clicked desc,Evaluation.name";
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
        $organization_share_conditions = array('OrganizationShare.share_type'=>'evaluation');
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
        $evaluation_share=$OrganizationShare->find('list',array('fields'=>'OrganizationShare.share_type_id','conditions'=>$organization_share_conditions));
        $evaluation_share = array_unique($evaluation_share);
		$evaluation_cansee_conditions = array();
		$evaluation_cansee_conditions['or'][] = array(
        	'Evaluation.visibility'=>0
        	);
		$evaluation_cansee_conditions['or'][] = array(
        	'Evaluation.visibility'=>1,
        	'Evaluation.user_id'=>$_SESSION['User']['User']['id']
        	);
		$evaluation_cansee_conditions['or'][] = array(
        	'Evaluation.visibility'=>2,
        	'Evaluation.user_id'=>$_SESSION['User']['User']['id']
        	);
		$evaluation_cansee_conditions['or'][] = array(
        	'Evaluation.visibility'=>2,
        	'Evaluation.id'=>$evaluation_share
        	);
		//$evaluation_list=$this->find('all',array('conditions'=>$evaluation_cansee_conditions,'order'=>$evaluation_orderby,'page'=>$page,'limit'=>$limit));
		$evaluation_list=$this->find('all',array('conditions'=>array('Evaluation.user_id'=>0,'Evaluation.visibility'=>0,'status'=>'1'),'order'=>$evaluation_orderby,'page'=>$page,'limit'=>$limit));
		// $evaluation_list=$this->find('all',array('conditions'=>$conditions,'order'=>$evaluation_orderby,'page'=>$page,'limit'=>$limit));
		$evaluation_data['evaluation_list']=$evaluation_list;
		$evaluation_data['paging']=$pages;
		if(!empty($evaluation_list)){
			$evaluation_ids=array();
			foreach($evaluation_list as $v){
				$evaluation_ids[]=$v['Evaluation']['id'];
			}
			$evaluation_log_cond=array();
			$evaluation_log_cond['UserEvaluationLog.evaluation_id']=$evaluation_ids;
			$evaluation_log_cond['UserEvaluationLog.submit_time <>']=null;
			$UserEvaluationLog = ClassRegistry::init('UserEvaluationLog');
			$evaluation_log_info=$UserEvaluationLog->find('all',array('conditions'=>$evaluation_log_cond,'fields'=>"UserEvaluationLog.evaluation_id,UserEvaluationLog.user_id",'group'=>"UserEvaluationLog.evaluation_id,UserEvaluationLog.user_id","order"=>"UserEvaluationLog.evaluation_id",'recursive'=>-1));
			$evaluation_user_total=array();
			if(!empty($evaluation_log_info)){
				$evaluationlogdata=array();
				foreach($evaluation_log_info as $v){
					$evaluationlogdata[$v['UserEvaluationLog']['evaluation_id']][]=$v['UserEvaluationLog']['user_id'];
				}
				foreach($evaluationlogdata as $k=>$v){
					$evaluation_user_total[$k]=sizeof($v);
				}
			}
			$evaluation_data['evaluation_user_total']=$evaluation_user_total;
		}
		return $evaluation_data;
    }
    
    function evaluation_detail($params=array()){
    		$evaluation_info=array();
    		$evaluation_data=array();
		if (isset($params['evaluation_data'])&&!empty($params['evaluation_data'])) {
			$evaluation_data=$params['evaluation_data'];
		}else{
			$conditions=array();
    			$conditions['Evaluation.status']='1';
    			$conditions['Evaluation.id']=isset($params['id'])?intval($params['id']):0;
    			$evaluation_data=$this->find('first',array('conditions'=>$conditions));
		}
    		if(!empty($evaluation_data)){
    			$evaluation_info['evaluation_data']=$evaluation_data['Evaluation'];
    			$evaluation_code=isset($evaluation_data['Evaluation']['code'])?$evaluation_data['Evaluation']['code']:'';
    			
    			$EvaluationRule = ClassRegistry::init('EvaluationRule');
    			$evaluation_rule_list=$EvaluationRule->evaluation_rule_list($evaluation_code);
    			$evaluation_info['evaluation_rule']=$evaluation_rule_list;
    			
    			$question_total=0;
    			if(!empty($evaluation_rule_list)){
	    			foreach($evaluation_rule_list as $v){
	    				$question_total+=$v['EvaluationRule']['proportion'];
	    			}
    			}else{
    				$EvaluationQuestion = ClassRegistry::init('EvaluationQuestion');
				$question_total=$EvaluationQuestion->find('count',array('conditions'=>array('EvaluationQuestion.evaluation_code'=>$evaluation_code)));
    			}
    			$evaluation_info['question_total']=$question_total;
    			$evaluation_info['score_total']=$question_total>0?100:0;
    		}
    		return $evaluation_info;
    }
    
    /*
    		相关评测
    */
    function related_evaluation($params=array()){
    		$evaluation_list=array();
    		$evaluation_data=array();
    		
    		$limit=10;
    		if (isset($params['limit'])) {$limit = $params['limit'];}
    		if (isset($params['evaluation_data'])&&!empty($params['evaluation_data'])) {
			$evaluation_data=$params['evaluation_data'];
		}else{
			$conditions=array();
    			$conditions['Evaluation.status']='1';
    			$conditions['Evaluation.id']=isset($params['id'])?intval($params['id']):0;
    			$evaluation_data=$this->find('first',array('conditions'=>$conditions));
		}
		if(!empty($evaluation_data)){
			$evaluation_subject_code=$evaluation_data['Evaluation']['evaluation_subject_code'];
			$evaluation_category_code=$evaluation_data['Evaluation']['evaluation_category_code'];
			
			$conditions=array();
			$conditions['and']['Evaluation.status']='1';
			$conditions['and']['Evaluation.id <>']=$evaluation_data['Evaluation']['id'];
			if($evaluation_category_code!=""){
				$conditions['and']['Evaluation.evaluation_category_code']=$evaluation_category_code;
			}
			$evaluation_list=$this->find('all',array('conditions'=>$conditions,'order'=>'Evaluation.name,Evaluation.modified desc','limit'=>$limit));
		}
		return $evaluation_list;
    }
    
    
    function import_evaluation_list($import_type='U',$import_type_id=0){
    		$conditions=array();
    		$evaluation_user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
    		$evaluation_user_ids=array(0);
    		if($import_type=='O')$evaluation_user_ids[]=$evaluation_user_id;
    		$conditions['Evaluation.user_id']=array_unique($evaluation_user_ids);
    		$conditions['Evaluation.status']='1';
    		if($import_type=='O'&&!empty($import_type_id)){
    			$OrganizationRelation = ClassRegistry::init('OrganizationRelation');
    			$organization_evaluation_ids=$OrganizationRelation->find('list',array('fields'=>'type_id','conditions'=>array('OrganizationRelation.type'=>'evaluation','OrganizationRelation.organization_id'=>$import_type_id)));
    			if(!empty($organization_evaluation_ids))$conditions['not']['Evaluation.id']=$organization_evaluation_ids;
    		}
    		$import_evaluation = $this->find('all',array('conditions'=>$conditions,'fields'=>'id,user_id,name','order'=>'user_id,id'));
    		$import_evaluation_data=array();
    		if(!empty($import_evaluation)){
	    		foreach($import_evaluation as $v){
	    			if(!empty($evaluation_user_id)&&$evaluation_user_id==$v['Evaluation']['user_id']){
	    				$import_evaluation_data['U'][]=$v['Evaluation'];
	    			}else{
	    				$import_evaluation_data['S'][]=$v['Evaluation'];
	    			}
	    		}
    		}
    		return $import_evaluation_data;
    }
}
