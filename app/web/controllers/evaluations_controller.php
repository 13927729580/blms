<?php

/*****************************************************************************
 * Evaluation 评测
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为EvaluationsController的控制器
 *评测
 *
 *@var
 *@var
 *@var
 *@var
 */
class EvaluationsController extends AppController
{
	public $name = 'Evaluations';
	public $helpers = array('Html','Pagination');
	public $uses = array('Evaluation','Precondition','EvaluationRule','EvaluationCategory','EvaluationQuestion','EvaluationOption','UserEvaluationLog','UserEvaluationLogDetail','InformationResource','Resource','User','UserAction','SynchroUser','NotifyTemplateType','UserAbility','AbilityLevel','UserFans','Blog','Organization','OrganizationMember','OrganizationJob','OrganizationDepartment','OrganizationMemberJob','NotifyTemplateType','OrganizationShare','Profile','ProfileFiled','OrganizationRelation','OrganizationManager');
	public $components = array('RequestHandler','Pagination','Notify','Phpcsv');
	
	public function index(){
		$_GET=$this->clean_xss($_GET);
		$this->layout = 'default_full';
		$this->pageTitle = '评测 - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '评测' , 'url' => '/evaluations/');
        	$this->set('ur_heres', $this->ur_heres);
		$params=array();
		$params['ControllerObj']=$this;
		$this->page_init($params);
	}
	
	/**
	*	课程
	*/
	public function view($id=0){
		$this->layout = 'default_full';
		
		$evaluation_data=$this->Evaluation->find('first',array('conditions'=>array('Evaluation.id'=>$id,'Evaluation.status'=>'1')));
		$this->set('evaluation_data',$evaluation_data);
		if(empty($evaluation_data)){$this->redirect('/pages/home');}
		
		//评测点击率记录
		$evaluation_clicked=isset($_COOKIE['evaluation_clicked']) && !empty($_COOKIE['evaluation_clicked'])?unserialize(stripslashes($_COOKIE['evaluation_clicked'])):array();
		if(empty($evaluation_clicked)||!in_array($id,$evaluation_clicked)){
			$evaluation_clicked[]=$id;
			setcookie('evaluation_clicked', serialize($evaluation_clicked), time() + 60 * 60 * 24, '/');
			$evaluation_data['Evaluation']['clicked']++;
			$this->Evaluation->updateAll(array('Evaluation.clicked'=>$evaluation_data['Evaluation']['clicked']),array('Evaluation.id'=>$id));
		}
		$this->pageTitle = $evaluation_data['Evaluation']['name'].' - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '评测' , 'url' => '/evaluations/');
		$this->ur_heres[] = array('name' => $evaluation_data['Evaluation']['name'] , 'url' => '');
        	$this->set('ur_heres', $this->ur_heres);
        	
		$evaluation_category_code=$evaluation_data['Evaluation']['evaluation_category_code'];
		
		if($evaluation_category_code!=""){
		$evaluation_category_data=$this->EvaluationCategory->find('first',array('conditions'=>array('EvaluationCategory.code'=>$evaluation_category_code,'EvaluationCategory.status'=>'1')));
			$this->set('evaluation_category_data',$evaluation_category_data);
		}
		
		$params=array();
		$params['id']=$id;
		$params['evaluation_code']=$evaluation_data['Evaluation']['code'];
		$params['evaluation_data']=$evaluation_data;
		$params['ControllerObj']=$this;
		$this->page_init($params);
		
		$information_data=$this->InformationResource->code_information_formated(array('question_type','evaluation_condition'), $this->locale);
		$this->set('information_data',$information_data);
		
		$evaluation_log_total=0;
		if(isset($_SESSION['User'])){
			$user_id=$_SESSION['User']['User']['id'];
			$user_detail=$this->User->find('first',array('fields'=>'User.id,User.mobile','conditions'=>array('User.id'=>$user_id,'User.mobile <>'=>'')));
			
			$conditions=array();
			$conditions['UserEvaluationLog.evaluation_id']=$id;
	        	$conditions['UserEvaluationLog.user_id <>']=$user_id;
	        	$UserEvaluationInfo=$this->UserEvaluationLog->find('all',array('fields'=>"user_id,count(*) as examination_total",'conditions'=>$conditions,'group'=>'evaluation_id'));
	        	$UserEvaluationTotal=sizeof($UserEvaluationInfo);
	        	$max_evaluation_examination=intval(Configure::read('HR.max_evaluation_examination'));
	        	if($UserEvaluationTotal>$max_evaluation_examination){
    				$this->set('max_evaluation_examination',true);
    			}
			$conditions=array();
			$conditions['UserEvaluationLog.evaluation_id']=$id;
	        	$conditions['UserEvaluationLog.user_id']=$user_id;
	        	$conditions['UserEvaluationLog.submit_time <>']=null;
			$last_evaluation_log=$this->UserEvaluationLog->find('first',array('conditions'=>$conditions,'order'=>'UserEvaluationLog.id desc'));
			if(!empty($last_evaluation_log['UserEvaluationLog'])){
				$evaluation_log_total=1;
				$this->set('last_evaluation_log',$last_evaluation_log);
			}
			$EvaluationCondition_info=$this->Precondition->pre_condition_list('evaluation',$evaluation_data['Evaluation']['code']);
			if(!empty($EvaluationCondition_info)){
				$evaluation_condition=array();
				$this->loadModel('User');
				$user_id=$_SESSION['User']['User']['id'];
				foreach($EvaluationCondition_info as $k=>$v){
					if($k=='ability_level'){
						$ability_level_values=explode(',',$v);
						$ability_level_info=$this->AbilityLevel->find('all',array('fields'=>"AbilityLevel.id,Ability.name,AbilityLevel.name",'conditions'=>array('AbilityLevel.id'=>$ability_level_values,'AbilityLevel.status'=>'1')));
						$ability_level_ids=array();
						$ability_level_list=array();
						if(!empty($ability_level_info)){
							foreach($ability_level_info as $v){
								$ability_level_ids[]=$v['AbilityLevel']['id'];
								$ability_level_list[$v['AbilityLevel']['id']]=$v['Ability']['name'].$v['AbilityLevel']['name'];
							}
						}
						
						$user_ability_list=$this->UserAbility->find('list',array('fields'=>'UserAbility.ability_level_id','conditions'=>array('UserAbility.user_id'=>$user_id,'UserAbility.ability_level_id'=>$ability_level_ids,'UserAbility.status'=>'1')));
						if(sizeof($ability_level_ids)!=sizeof($user_ability_list)){
							$ability_diff_ids=array_diff($ability_level_ids, $user_ability_list);
							$ability_diff=array();
							foreach($ability_diff_ids as $kk=>$vv){
								if(isset($ability_level_list[$vv])){
									$ability_diff[$vv]=$ability_level_list[$vv];
								}
							}
							$evaluation_condition=array('type'=>'ability_level','data'=>$ability_diff);
							break;
						}
					}else if($k=='cycle'){
						$evaluation_cycle=intval($v);
						if($evaluation_cycle>0){
							$conditions=array();
							$conditions['UserEvaluationLog.evaluation_id']=$id;
					        	$conditions['UserEvaluationLog.user_id']=$user_id;
					        	$conditions['UserEvaluationLog.submit_time <>']=null;
							$last_evaluation_log=$this->UserEvaluationLog->find('first',array('conditions'=>$conditions,'order'=>'UserEvaluationLog.id desc'));
							if(isset($last_evaluation_log['UserEvaluationLog'])){
								$last_evaluation_timediff=strtotime($last_evaluation_log['UserEvaluationLog']['start_time'])-time();
								$last_evaluation=intval($last_evaluation_timediff/86400);
								
								$next_evaluation_time=strtotime($last_evaluation_log['UserEvaluationLog']['start_time'])+intval($evaluation_cycle*86400);
								$next_evaluation_timediff=$next_evaluation_time-time();
								$next_evaluation=intval($next_evaluation_timediff/86400);
								if($next_evaluation==0&&date('Y-m-d')!=date('Y-m-d',$next_evaluation_time)){
									$next_evaluation=1;
								}
								if($next_evaluation>=1){
									$evaluation_condition=array('type'=>'cycle','data'=>$next_evaluation);
									break;
								}
							}
						}
					}else if($k=='parent_evaluation'){
						$parent_evaluation_id_infos=explode(',',$v);
						$parent_evaluation_infos=$this->Evaluation->find('all',array('fields'=>'Evaluation.id,Evaluation.pass_score,Evaluation.name','conditions'=>array('Evaluation.id'=>$parent_evaluation_id_infos,'Evaluation.status'=>'1')));
						if(!empty($parent_evaluation_infos)){
							$parent_evaluation_lists=array();
							$parent_evaluation_ids=array();
							foreach($parent_evaluation_infos as $v){
								$parent_evaluation_ids[$v['Evaluation']['id']]=$v['Evaluation']['pass_score'];
								$parent_evaluation_lists[$v['Evaluation']['id']]=$v['Evaluation']['name'];
							}
							$conditions=array();
					        	$conditions['UserEvaluationLog.user_id']=$user_id;
					        	$conditions['UserEvaluationLog.submit_time <>']=null;
					        	foreach($parent_evaluation_ids as $k=>$v){
					        		$conditions['and'][]=array('UserEvaluationLog.evaluation_id'=>$k,'UserEvaluationLog.score >='=>$v);
					        	}
					        	$parent_evaluation_log=$this->UserEvaluationLog->find('all',array('conditions'=>$conditions,'group'=>'UserEvaluationLog.evaluation_id desc'));
					        	if(sizeof($parent_evaluation_infos)!=sizeof($parent_evaluation_log)){
					        		foreach($parent_evaluation_log as $v){
					        			if(isset($parent_evaluation_lists[$v['UserEvaluationLog']['evaluation_id']])){
					        				unset($parent_evaluation_lists[$v['UserEvaluationLog']['evaluation_id']]);
					        			}
					        		}
					        		
					        		if(!empty($parent_evaluation_lists)){
					        			$evaluation_condition=array('type'=>'parent_evaluation','data'=>$parent_evaluation_lists);
					        		}else{
					        			$evaluation_condition=array('type'=>'parent_evaluation','data'=>array());
					        		}
								break;
							}
						}
					}
				}
				if(!empty($evaluation_condition)){
					$this->set('evaluation_condition',$evaluation_condition);
				}
			}
		}
		$this->set('evaluation_log_total',$evaluation_log_total);
		
		$evaluation_view_action=0;
		$need_buy=$evaluation_data['Evaluation']['price']>0?true:false;
		$evaluation_visibility=$evaluation_data['Evaluation']['visibility'];
		$evaluation_user=$evaluation_data['Evaluation']['user_id'];
		$user_id=isset($user_id)?$user_id:0;
		if($user_id!=$evaluation_user){
			if($evaluation_visibility=='1'&&intval($evaluation_user)>0){
				$evaluation_view_action=1;
			}else if($evaluation_visibility=='2'){
				$share_user_ids=array();
				$this->loadModel('OrganizationShare');
				$share_cond=array();
				$share_cond['OrganizationShare.share_type']='evaluation';
				$share_cond['OrganizationShare.share_type_id']=$id;
				$share_cond['OrganizationShare.share_object']=array('0','1','2');
				$share_group_list=$this->OrganizationShare->find('list',array('conditions'=>$share_cond,'fields'=>'id,share_object_ids,share_object'));
				if(!empty($share_group_list)){
					$share_menbers=array();
					$share_menber_users=array();
					$this->loadModel('OrganizationMember');
					$share_organization_conds=array();
					$share_menber_lists=isset($share_group_list[0])?$share_group_list[0]:array();
					if(!empty($share_menber_lists)){
						$share_menber_ids=array();
						foreach($share_menber_lists as $menber_id_txt){
							$menber_id_arr=explode(',',$menber_id_txt);
							$share_menber_ids=array_merge($share_menber_ids,$menber_id_arr);
						}
						if(!empty($share_menber_ids))$share_menber_ids=array_unique($share_menber_ids);
						$share_menbers=array_merge($share_menbers,$share_menber_ids);
					}
					$share_department_list=isset($share_group_list[1])?$share_group_list[1]:array();
					if(!empty($share_department_list)){
						$this->loadModel('OrganizationMemberJob');
						$share_department_ids=array();
						foreach($share_department_list as $share_department_txt){
							$share_department_id_arr=explode(',',$share_department_txt);
							$share_department_ids=array_merge($share_department_ids,$share_department_id_arr);
						}
						if(!empty($share_department_ids))$share_department_ids=array_unique($share_department_ids);
						$share_department_menber_conds=array();
						$share_department_menber_conds['OrganizationMemberJob.organization_department_id']=$share_department_ids;
						$share_department_menber_conds['OrganizationMemberJob.organization_member_id >']=0;
						$share_department_menbers=$this->OrganizationMemberJob->find('list',array('conditions'=>$share_department_menber_conds,'fields'=>'id,organization_member_id'));
						if(!empty($share_department_menbers)){
							$share_menbers=array_merge($share_menbers,$share_department_menbers);
						}
					}
					$share_organization_list=isset($share_group_list[2])?$share_group_list[2]:array();
					if(!empty($share_organization_list)){
						$share_organization_ids=array();
						foreach($share_organization_list as $share_organization_txt){
							$share_organization_id_arr=explode(',',$share_organization_txt);
							$share_organization_ids=array_merge($share_organization_ids,$share_organization_id_arr);
						}
						if(!empty($share_organization_ids))$share_organization_ids=array_unique($share_organization_ids);
						$share_organization_conds['or']['OrganizationMember.organization_id']=$share_organization_ids;
					}
					if(!empty($share_menbers))$share_organization_conds['or']['OrganizationMember.id']=$share_menbers;
					if($share_organization_conds){
						$share_organization_conds['OrganizationMember.user_id']=$user_id;
						$share_menber_users=$this->OrganizationMember->find('first',array('conditions'=>$share_organization_conds));
					}
				}
				$share_cond=array();
				$share_cond['OrganizationShare.share_type']='evaluation';
				$share_cond['OrganizationShare.share_type_id']=$id;
				$share_cond['or'][]=array(
						'OrganizationShare.share_object'=>3,
						'OrganizationShare.share_object_ids'=>$user_id
				);
				if(!empty($user_detail)){
					$share_cond['or'][]=array(
							'OrganizationShare.share_object'=>0,
							'OrganizationShare.share_object_ids like'=>"%|".$user_detail['User']['mobile']
					);
				}
				$other_share_infos=$this->OrganizationShare->find('count',array('conditions'=>$share_cond));
				if(empty($share_menber_users)&&empty($other_share_infos)){
					$evaluation_view_action='2';
				}
			}
		}else{
			$need_buy=false;
		}
		if($need_buy&&isset($user_id)){
			$this->loadModel('OrderProduct');
			$order_cond=array();
			$order_cond['Order.user_id']=$user_id;
			$order_cond['Order.status']='1';
			$order_cond['Order.payment_status']='2';
			$order_cond['OrderProduct.item_type']='evaluation';
			$order_cond['OrderProduct.product_id']=$id;
			$order_info=$this->OrderProduct->find('count',array('conditions'=>$order_cond));
			if(!empty($order_info))$need_buy=false;
		}
		$this->set('evaluation_view_action',$evaluation_view_action);
		$this->set('need_buy',$need_buy);
	}
	
	function start_evaluation($id=0){
       	if(isset($_REQUEST['ajax'])&&$_REQUEST['ajax']=='1'){
			if(isset($_SESSION['User'])){
				$this->layout='ajax';
			}else{
				die();
			}
       	}else{
       		//登录验证
       		$this->checkSessionUser();
			$this->layout = 'default_full';
       	}
		$user_id=$_SESSION['User']['User']['id'];
		
		$evaluation_data=$this->Evaluation->find('first',array('conditions'=>array('Evaluation.id'=>$id,'Evaluation.status'=>'1')));
		$this->set('evaluation_data',$evaluation_data);
		
		if(empty($evaluation_data)){$this->redirect('/pages/home');}else{
			$need_buy=$evaluation_data['Evaluation']['price']>0?true:false;
			$evaluation_view_action=0;
			$user_id=isset($_SESSION['User']['User']['id'])?$_SESSION['User']['User']['id']:0;
			$user_detail=$this->User->find('first',array('fields'=>'User.id,User.mobile','conditions'=>array('User.id'=>$user_id,'User.mobile <>'=>'')));
			$evaluation_visibility=$evaluation_data['Evaluation']['visibility'];
			$evaluation_user=$evaluation_data['Evaluation']['user_id'];
			if($user_id!=$evaluation_user){
				$conditions=array();
				$conditions['UserEvaluationLog.evaluation_id']=$id;
				$conditions['UserEvaluationLog.user_id <>']=$user_id;
				$UserEvaluationInfo=$this->UserEvaluationLog->find('all',array('fields'=>"user_id,count(*) as examination_total",'conditions'=>$conditions,'group'=>'evaluation_id'));
				$UserEvaluationTotal=sizeof($UserEvaluationInfo);
				$max_evaluation_examination=intval(Configure::read('HR.max_evaluation_examination'));
				if($UserEvaluationTotal>$max_evaluation_examination){
					$this->redirect('/evaluations/view/'.$id);
				}
				if($evaluation_visibility=='1'&&intval($evaluation_user)>0){
					$this->redirect('/evaluations/view/'.$id);
				}else if($evaluation_visibility=='2'){
					$share_user_ids=array();
					$this->loadModel('OrganizationShare');
					$share_cond=array();
					$share_cond['OrganizationShare.share_type']='evaluation';
					$share_cond['OrganizationShare.share_type_id']=$id;
					$share_cond['OrganizationShare.share_object']=array('0','1','2');
					$share_group_list=$this->OrganizationShare->find('list',array('conditions'=>$share_cond,'fields'=>'id,share_object_ids,share_object'));
					if(!empty($share_group_list)){
						$share_menbers=array();
						$share_menber_users=array();
						$this->loadModel('OrganizationMember');
						$share_organization_conds=array();
						$share_menber_lists=isset($share_group_list[0])?$share_group_list[0]:array();
						if(!empty($share_menber_lists)){
							$share_menber_ids=array();
							foreach($share_menber_lists as $menber_id_txt){
								$menber_id_arr=explode(',',$menber_id_txt);
								$share_menber_ids=array_merge($share_menber_ids,$menber_id_arr);
							}
							if(!empty($share_menber_ids))$share_menber_ids=array_unique($share_menber_ids);
							$share_menbers=array_merge($share_menbers,$share_menber_ids);
						}
						$share_department_list=isset($share_group_list[1])?$share_group_list[1]:array();
						if(!empty($share_department_list)){
							$this->loadModel('OrganizationMemberJob');
							$share_department_ids=array();
							foreach($share_department_list as $share_department_txt){
								$share_department_id_arr=explode(',',$share_department_txt);
								$share_department_ids=array_merge($share_department_ids,$share_department_id_arr);
							}
							if(!empty($share_department_ids))$share_department_ids=array_unique($share_department_ids);
							$share_department_menber_conds=array();
							$share_department_menber_conds['OrganizationMemberJob.organization_department_id']=$share_department_ids;
							$share_department_menber_conds['OrganizationMemberJob.organization_member_id >']=0;
							$share_department_menbers=$this->OrganizationMemberJob->find('list',array('conditions'=>$share_department_menber_conds,'fields'=>'id,organization_member_id'));
							if(!empty($share_department_menbers)){
								$share_menbers=array_merge($share_menbers,$share_department_menbers);
							}
						}
						$share_organization_list=isset($share_group_list[2])?$share_group_list[2]:array();
						if(!empty($share_organization_list)){
							$share_organization_ids=array();
							foreach($share_organization_list as $share_organization_txt){
								$share_organization_id_arr=explode(',',$share_organization_txt);
								$share_organization_ids=array_merge($share_organization_ids,$share_organization_id_arr);
							}
							if(!empty($share_organization_ids))$share_organization_ids=array_unique($share_organization_ids);
							$share_organization_conds['or']['OrganizationMember.organization_id']=$share_organization_ids;
						}
						if(!empty($share_menbers))$share_organization_conds['or']['OrganizationMember.id']=$share_menbers;
						if($share_organization_conds){
							$share_organization_conds['OrganizationMember.user_id']=$user_id;
							$share_menber_users=$this->OrganizationMember->find('first',array('conditions'=>$share_organization_conds));
						}
					}
					$share_cond=array();
					$share_cond['OrganizationShare.share_type']='evaluation';
					$share_cond['OrganizationShare.share_type_id']=$id;
					$share_cond['or'][]=array(
							'OrganizationShare.share_object'=>3,
							'OrganizationShare.share_object_ids'=>$user_id
					);
					if(!empty($user_detail)){
						$share_cond['or'][]=array(
								'OrganizationShare.share_object'=>0,
								'OrganizationShare.share_object_ids like'=>"%|".$user_detail['User']['mobile']
						);
					}
					$other_share_infos=$this->OrganizationShare->find('count',array('conditions'=>$share_cond));
					if(empty($share_menber_users)&&empty($other_share_infos))$this->redirect('/evaluations/view/'.$id);
				}
			}else{
				$need_buy=false;
			}
			if($need_buy&&isset($user_id)){
				$this->loadModel('OrderProduct');
				$order_cond=array();
				$order_cond['Order.user_id']=$user_id;
				$order_cond['Order.status']='1';
				$order_cond['Order.payment_status']='2';
				$order_cond['OrderProduct.item_type']='evaluation';
				$order_cond['OrderProduct.product_id']=$id;
				$order_info=$this->OrderProduct->find('first',array('conditions'=>$order_cond));
				if(!empty($order_info))$need_buy=false;
			}
			if($need_buy)$this->redirect('/evaluations/view/'.$id);
		}
		
		$this->pageTitle = $evaluation_data['Evaluation']['name'].' - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '评测' , 'url' => '/evaluations/');
		$this->ur_heres[] = array('name' => $evaluation_data['Evaluation']['name'] , 'url' => '/evaluations/view/'.$id);
		$this->ur_heres[] = array('name' => '开始评测' , 'url' => '');
        	$this->set('ur_heres', $this->ur_heres);
		
		$evaluation_code=$evaluation_data['Evaluation']['code'];
		$evaluation_rule_list=$this->EvaluationRule->evaluation_rule_list($evaluation_code);
		$this->set('evaluation_rule_list',$evaluation_rule_list);
		
		/*
		$evaluation_score_total=0;
		$evaluation_rule_score=array();
		if(!empty($evaluation_rule_list)){
			foreach($evaluation_rule_list as $v){
				$question_score=$v['EvaluationRule']['score']/$v['EvaluationRule']['proportion'];
				$evaluation_rule_score[$v['EvaluationRule']['child_evaluation_code']][$v['EvaluationRule']['question_type']]=$question_score;
				$evaluation_score_total+=$v['EvaluationRule']['score'];
			}
		}
		*/
		//自动计算分数
		$conditions=array();
        	$conditions['UserEvaluationLog.user_id']=$user_id;
        	$conditions['UserEvaluationLog.evaluation_id']=$id;
        	$conditions['UserEvaluationLog.submit_time']=null;
        	$conditions['UserEvaluationLog.end_time <']=date('Y-m-d H:i:s');
        	$user_evaluation_lists=$this->UserEvaluationLog->find('list',array('conditions'=>$conditions,'fields'=>'UserEvaluationLog.end_time'));
        	foreach($user_evaluation_lists as $evaluation_log_id=>$evaluation_end_time){
        		$evaluation_score_data=$this->UserEvaluationLogDetail->find('first',array('fields'=>'SUM(EvaluationQuestion.score) as score_total','conditions'=>array('UserEvaluationLogDetail.user_evaluation_log_id'=>$evaluation_log_id,'EvaluationQuestion.score <>'=>0)));
			$evaluation_score_total=isset($evaluation_score_data[0]['score_total'])?$evaluation_score_data[0]['score_total']:0;
        		$Txt_evaluation_questions=$this->UserEvaluationLogDetail->find('count',array('conditions'=>array('UserEvaluationLogDetail.user_evaluation_log_id'=>$evaluation_log_id,'EvaluationQuestion.question_type'=>'2')));
        		if(!empty($Txt_evaluation_questions))continue;
        		$user_evaluation_details=$this->UserEvaluationLogDetail->find('all',array('conditions'=>array('UserEvaluationLogDetail.user_evaluation_log_id'=>$evaluation_log_id,'UserEvaluationLogDetail.answer=EvaluationQuestion.right_answer')));
        		$evaluation_score=0;
        		$ability_experience_value=array();
        		if(!empty($user_evaluation_details)){
				foreach($user_evaluation_details as $v){
					/*$rule_score=isset($evaluation_rule_score[$v['EvaluationQuestion']['evaluation_code']][$v['EvaluationQuestion']['question_type']])?$evaluation_rule_score[$v['EvaluationQuestion']['evaluation_code']][$v['EvaluationQuestion']['question_type']]:1;*/
					$evaluation_score+=$v['EvaluationQuestion']['score'];
					$ability_experience_value[$v['EvaluationQuestion']['ability_code']][]=$v['UserEvaluationLogDetail']['id'];
				}
			}
			$score_rule=$evaluation_score/$evaluation_score_total*100;
			$evaluation_log_data=array(
				'id'=>$evaluation_log_id,
				'status'=>'1',
				'score'=>$score_rule,
				'submit_time'=>$evaluation_end_time,
				'ipaddress'=>$this->real_ip(),
				'system'=>$this->get_os(),
				'browser'=>$this->getbrowser()
			);
			$this->UserEvaluationLog->save($evaluation_log_data);
			$experience_value=isset($this->configs['evaluation_experience_value'])?intval($this->configs['evaluation_experience_value']):0;
			if(isset($evaluation_data['Evaluation']['pass_score'])&&intval($evaluation_data['Evaluation']['pass_score'])>=$evaluation_score){
				//评测合格
				$experience_value+=$experience_value;
			}
			//$this->User->experience_value_change($user_id,$experience_value);
			$this->UserAbility->experience_value_change($user_id,$ability_experience_value);
        	}
        	
        	$information_data=$this->InformationResource->code_information_formated(array('question_type','evaluation_condition'), $this->locale);
		$this->set('information_data',$information_data);
		$EvaluationCondition_info=$this->Precondition->pre_condition_list('evaluation',$evaluation_data['Evaluation']['code']);
		if(!empty($EvaluationCondition_info)){
			$evaluation_condition=array();
			foreach($EvaluationCondition_info as $k=>$v){
				if($k=='ability_level'){
					$ability_level_values=explode(',',$v);
					$ability_level_info=$this->AbilityLevel->find('all',array('fields'=>"AbilityLevel.id,Ability.name,AbilityLevel.name",'conditions'=>array('AbilityLevel.id'=>$ability_level_values,'AbilityLevel.status'=>'1')));
					$ability_level_ids=array();
					$ability_level_list=array();
					if(!empty($ability_level_info)){
						foreach($ability_level_info as $v){
							$ability_level_ids[]=$v['AbilityLevel']['id'];
							$ability_level_list[$v['AbilityLevel']['id']]=$v['Ability']['name'].$v['AbilityLevel']['name'];
						}
					}
					
					$user_ability_list=$this->UserAbility->find('list',array('fields'=>'UserAbility.ability_level_id','conditions'=>array('UserAbility.user_id'=>$user_id,'UserAbility.ability_level_id'=>$ability_level_ids,'UserAbility.status'=>'1')));
					if(sizeof($ability_level_ids)!=sizeof($user_ability_list)){
						$ability_diff_ids=array_diff($ability_level_ids, $user_ability_list);
						$ability_diff=array();
						foreach($ability_diff_ids as $kk=>$vv){
							if(isset($ability_level_list[$vv])){
								$ability_diff[$vv]=$ability_level_list[$vv];
							}
						}
						$evaluation_condition=array('type'=>'ability_level','data'=>$ability_diff);
						break;
					}
				}else if($k=='cycle'){
					$evaluation_cycle=intval($v);
					if($evaluation_cycle>0){
						$conditions=array();
						$conditions['UserEvaluationLog.evaluation_id']=$id;
				        	$conditions['UserEvaluationLog.user_id']=$user_id;
				        	$conditions['UserEvaluationLog.submit_time <>']=null;
						$last_evaluation_log=$this->UserEvaluationLog->find('first',array('conditions'=>$conditions,'order'=>'UserEvaluationLog.id desc'));
						if(isset($last_evaluation_log['UserEvaluationLog'])){
							$last_evaluation_timediff=strtotime($last_evaluation_log['UserEvaluationLog']['start_time'])-time();
							$last_evaluation=intval($last_evaluation_timediff/86400);
							
							$next_evaluation_time=strtotime($last_evaluation_log['UserEvaluationLog']['start_time'])+intval($evaluation_cycle*86400);
							$next_evaluation_timediff=$next_evaluation_time-time();
							$next_evaluation=intval($next_evaluation_timediff/86400);
							if($next_evaluation==0&&date('Y-m-d')!=date('Y-m-d',$next_evaluation_time)){
								$next_evaluation=1;
							}
							if($next_evaluation>=1){
								$evaluation_condition=array('type'=>'cycle','data'=>$next_evaluation);
								break;
							}
						}
					}
				}else if($k=='parent_evaluation'){
					$parent_evaluation_id_infos=explode(',',$v);
					$parent_evaluation_infos=$this->Evaluation->find('all',array('fields'=>'Evaluation.id,Evaluation.pass_score,Evaluation.name','conditions'=>array('Evaluation.id'=>$parent_evaluation_id_infos,'Evaluation.status'=>'1')));
					if(!empty($parent_evaluation_infos)){
						$parent_evaluation_lists=array();
						$parent_evaluation_ids=array();
						foreach($parent_evaluation_infos as $v){
							$parent_evaluation_ids[$v['Evaluation']['id']]=$v['Evaluation']['pass_score'];
							$parent_evaluation_lists[$v['Evaluation']['id']]=$v['Evaluation']['name'];
						}
						$conditions=array();
				        	$conditions['UserEvaluationLog.user_id']=$user_id;
				        	$conditions['UserEvaluationLog.submit_time <>']=null;
				        	foreach($parent_evaluation_ids as $k=>$v){
				        		$conditions['and'][]=array('UserEvaluationLog.evaluation_id'=>$k,'UserEvaluationLog.score >='=>$v);
				        	}
				        	$parent_evaluation_log=$this->UserEvaluationLog->find('all',array('conditions'=>$conditions,'group'=>'UserEvaluationLog.evaluation_id desc'));
				        	if(sizeof($parent_evaluation_infos)!=sizeof($parent_evaluation_log)){
				        		foreach($parent_evaluation_log as $v){
				        			if(isset($parent_evaluation_lists[$v['UserEvaluationLog']['evaluation_id']])){
				        				unset($parent_evaluation_lists[$v['UserEvaluationLog']['evaluation_id']]);
				        			}
				        		}
				        		
				        		if(!empty($parent_evaluation_lists)){
				        			$evaluation_condition=array('type'=>'parent_evaluation','data'=>$parent_evaluation_lists);
				        		}else{
				        			$evaluation_condition=array('type'=>'parent_evaluation','data'=>array());
				        		}
							break;
						}
					}
				}
			}
			if(!empty($evaluation_condition)){
				$this->set('evaluation_condition',$evaluation_condition);
			}
		}
	}
	
	function examination($id=0){
		//登录验证
        	$this->checkSessionUser();
		$this->layout = 'default_full';
		
		$information_data=$this->InformationResource->code_information_formated(array('question_type','evaluation_condition'), $this->locale);
		$this->set('information_data',$information_data);
		
		$evaluation_data=$this->Evaluation->find('first',array('conditions'=>array('Evaluation.id'=>$id,'Evaluation.status'=>'1')));
		$this->set('evaluation_data',$evaluation_data);
        	
        	if(empty($evaluation_data)){
        		$this->redirect('/pages/home');
        	}else{
        		$need_buy=$evaluation_data['Evaluation']['price']>0?true:false;
        		$evaluation_view_action=0;
			$user_id=isset($_SESSION['User']['User']['id'])?$_SESSION['User']['User']['id']:0;
			$user_detail=$this->User->find('first',array('fields'=>'User.id,User.mobile','conditions'=>array('User.id'=>$user_id,'User.mobile <>'=>'')));
			$evaluation_visibility=$evaluation_data['Evaluation']['visibility'];
			$evaluation_user=$evaluation_data['Evaluation']['user_id'];
			if($user_id!=$evaluation_user){
				$conditions=array();
				$conditions['UserEvaluationLog.evaluation_id']=$id;
				$conditions['UserEvaluationLog.user_id <>']=$user_id;
				$UserEvaluationInfo=$this->UserEvaluationLog->find('all',array('fields'=>"user_id,count(*) as examination_total",'conditions'=>$conditions,'group'=>'evaluation_id'));
				$UserEvaluationTotal=sizeof($UserEvaluationInfo);
				$max_evaluation_examination=intval(Configure::read('HR.max_evaluation_examination'));
				if($UserEvaluationTotal>$max_evaluation_examination){
					$this->redirect('/evaluations/view/'.$id);
				}
				if($evaluation_visibility=='1'&&intval($evaluation_user)>0){
					$this->redirect('/evaluations/view/'.$id);
				}else if($evaluation_visibility=='2'){
					$share_user_ids=array();
					$this->loadModel('OrganizationShare');
					$share_cond=array();
					$share_cond['OrganizationShare.share_type']='evaluation';
					$share_cond['OrganizationShare.share_type_id']=$id;
					$share_cond['OrganizationShare.share_object']=array('0','1','2');
					$share_group_list=$this->OrganizationShare->find('list',array('conditions'=>$share_cond,'fields'=>'id,share_object_ids,share_object'));
					if(!empty($share_group_list)){
						$share_menbers=array();
						$share_menber_users=array();
						$this->loadModel('OrganizationMember');
						$share_organization_conds=array();
						$share_menber_lists=isset($share_group_list[0])?$share_group_list[0]:array();
						if(!empty($share_menber_lists)){
							$share_menber_ids=array();
							foreach($share_menber_lists as $menber_id_txt){
								$menber_id_arr=explode(',',$menber_id_txt);
								$share_menber_ids=array_merge($share_menber_ids,$menber_id_arr);
							}
							if(!empty($share_menber_ids))$share_menber_ids=array_unique($share_menber_ids);
							$share_menbers=array_merge($share_menbers,$share_menber_ids);
						}
						$share_department_list=isset($share_group_list[1])?$share_group_list[1]:array();
						if(!empty($share_department_list)){
							$this->loadModel('OrganizationMemberJob');
							$share_department_ids=array();
							foreach($share_department_list as $share_department_txt){
								$share_department_id_arr=explode(',',$share_department_txt);
								$share_department_ids=array_merge($share_department_ids,$share_department_id_arr);
							}
							if(!empty($share_department_ids))$share_department_ids=array_unique($share_department_ids);
							$share_department_menber_conds=array();
							$share_department_menber_conds['OrganizationMemberJob.organization_department_id']=$share_department_ids;
							$share_department_menber_conds['OrganizationMemberJob.organization_member_id >']=0;
							$share_department_menbers=$this->OrganizationMemberJob->find('list',array('conditions'=>$share_department_menber_conds,'fields'=>'id,organization_member_id'));
							if(!empty($share_department_menbers)){
								$share_menbers=array_merge($share_menbers,$share_department_menbers);
							}
						}
						$share_organization_list=isset($share_group_list[2])?$share_group_list[2]:array();
						if(!empty($share_organization_list)){
							$share_organization_ids=array();
							foreach($share_organization_list as $share_organization_txt){
								$share_organization_id_arr=explode(',',$share_organization_txt);
								$share_organization_ids=array_merge($share_organization_ids,$share_organization_id_arr);
							}
							if(!empty($share_organization_ids))$share_organization_ids=array_unique($share_organization_ids);
							$share_organization_conds['or']['OrganizationMember.organization_id']=$share_organization_ids;
						}
						if(!empty($share_menbers))$share_organization_conds['or']['OrganizationMember.id']=$share_menbers;
						if($share_organization_conds){
							$share_organization_conds['OrganizationMember.user_id']=$user_id;
							$share_menber_users=$this->OrganizationMember->find('first',array('conditions'=>$share_organization_conds));
						}
					}
					$share_cond=array();
					$share_cond['OrganizationShare.share_type']='evaluation';
					$share_cond['OrganizationShare.share_type_id']=$id;
					$share_cond['or'][]=array(
							'OrganizationShare.share_object'=>3,
							'OrganizationShare.share_object_ids'=>$user_id
					);
					if(!empty($user_detail)){
						$share_cond['or'][]=array(
								'OrganizationShare.share_object'=>0,
								'OrganizationShare.share_object_ids like'=>"%|".$user_detail['User']['mobile']
						);
					}
					$other_share_infos=$this->OrganizationShare->find('count',array('conditions'=>$share_cond));
					if(empty($share_menber_users)&&empty($other_share_infos))$this->redirect('/evaluations/view/'.$id);
				}
			}else{
				$need_buy=false;
			}
			if($need_buy&&isset($user_id)){
				$this->loadModel('OrderProduct');
				$order_cond=array();
				$order_cond['Order.user_id']=$user_id;
				$order_cond['Order.status']='1';
				$order_cond['Order.payment_status']='2';
				$order_cond['OrderProduct.item_type']='evaluation';
				$order_cond['OrderProduct.product_id']=$id;
				$order_info=$this->OrderProduct->find('count',array('conditions'=>$order_cond));
				if(!empty($order_info))$need_buy=false;
			}
			if($need_buy)$this->redirect('/evaluations/view/'.$id);
			
			$EvaluationCondition_info=$this->Precondition->pre_condition_list('evaluation',$evaluation_data['Evaluation']['code']);
			if(!empty($EvaluationCondition_info)){
				$evaluation_condition=array();
				foreach($EvaluationCondition_info as $k=>$v){
					if($k=='ability_level'){
						$ability_level_values=explode(';',$v);
						$ability_level_info=$this->AbilityLevel->find('all',array('fields'=>"AbilityLevel.id,Ability.name,AbilityLevel.name",'conditions'=>array('AbilityLevel.id'=>$ability_level_values,'AbilityLevel.status'=>'1')));
						$ability_level_ids=array();
						$ability_level_list=array();
						if(!empty($ability_level_info)){
							foreach($ability_level_info as $v){
								$ability_level_ids[]=$v['AbilityLevel']['id'];
								$ability_level_list[$v['AbilityLevel']['id']]=$v['Ability']['name'].$v['AbilityLevel']['name'];
							}
						}
						
						$user_ability_list=$this->UserAbility->find('list',array('fields'=>'UserAbility.ability_level_id','conditions'=>array('UserAbility.user_id'=>$user_id,'UserAbility.ability_level_id'=>$ability_level_ids,'UserAbility.status'=>'1')));
						if(sizeof($ability_level_ids)!=sizeof($user_ability_list)){
							$ability_diff_ids=array_diff($ability_level_ids, $user_ability_list);
							$ability_diff=array();
							foreach($ability_diff_ids as $kk=>$vv){
								if(isset($ability_level_list[$vv])){
									$ability_diff[$vv]=$ability_level_list[$vv];
								}
							}
							$evaluation_condition=array('type'=>'ability_level','data'=>$ability_diff);
							break;
						}
					}else if($k=='cycle'){
						$evaluation_cycle=intval($v);
						if($evaluation_cycle>0){
							$conditions=array();
							$conditions['UserEvaluationLog.evaluation_id']=$id;
					        	$conditions['UserEvaluationLog.user_id']=$user_id;
					        	$conditions['UserEvaluationLog.submit_time <>']=null;
							$last_evaluation_log=$this->UserEvaluationLog->find('first',array('conditions'=>$conditions,'order'=>'UserEvaluationLog.id desc'));
							if(isset($last_evaluation_log['UserEvaluationLog'])){
								$last_evaluation_timediff=strtotime($last_evaluation_log['UserEvaluationLog']['start_time'])-time();
								$last_evaluation=intval($last_evaluation_timediff/86400);
								
								$next_evaluation_time=strtotime($last_evaluation_log['UserEvaluationLog']['start_time'])+intval($evaluation_cycle*86400);
								$next_evaluation_timediff=$next_evaluation_time-time();
								$next_evaluation=intval($next_evaluation_timediff/86400);
								if($next_evaluation==0&&date('Y-m-d')!=date('Y-m-d',$next_evaluation_time)){
									$next_evaluation=1;
								}
								if($next_evaluation>=1){
									$evaluation_condition=array('type'=>'cycle','data'=>$next_evaluation);
									break;
								}
							}
						}
					}else if($k=='parent_evaluation'){
						$parent_evaluation_codes=explode(';',$v);
						$parent_evaluation_infos=$this->Evaluation->find('all',array('fields'=>'Evaluation.id,Evaluation.pass_score,Evaluation.name','conditions'=>array('Evaluation.code'=>$parent_evaluation_codes,'Evaluation.status'=>'1')));
						if(!empty($parent_evaluation_infos)){
							$parent_evaluation_lists=array();
							$parent_evaluation_ids=array();
							foreach($parent_evaluation_infos as $v){
								$parent_evaluation_ids[$v['Evaluation']['id']]=$v['Evaluation']['pass_score'];
								$parent_evaluation_lists[$v['Evaluation']['id']]=$v['Evaluation']['name'];
							}
							$conditions=array();
					        	$conditions['UserEvaluationLog.user_id']=$user_id;
					        	$conditions['UserEvaluationLog.submit_time <>']=null;
					        	foreach($parent_evaluation_ids as $k=>$v){
					        		$conditions['and'][]=array('UserEvaluationLog.evaluation_id'=>$k,'UserEvaluationLog.score >='=>$v);
					        	}
					        	$parent_evaluation_log=$this->UserEvaluationLog->find('all',array('conditions'=>$conditions,'group'=>'UserEvaluationLog.evaluation_id desc'));
					        	if(sizeof($parent_evaluation_infos)!=sizeof($parent_evaluation_log)){
					        		foreach($parent_evaluation_log as $v){
					        			if(isset($parent_evaluation_lists[$v['UserEvaluationLog']['evaluation_id']])){
					        				unset($parent_evaluation_lists[$v['UserEvaluationLog']['evaluation_id']]);
					        			}
					        		}
					        		
					        		if(!empty($parent_evaluation_lists)){
					        			$evaluation_condition=array('type'=>'parent_evaluation','data'=>$parent_evaluation_lists);
					        		}else{
					        			$evaluation_condition=array('type'=>'parent_evaluation','data'=>array());
					        		}
								break;
							}
						}
					}
				}
				if(!empty($evaluation_condition)){
					$this->redirect('/evaluations/view/'.$id);
				}
			}
        	}
		$this->pageTitle = $evaluation_data['Evaluation']['name'].' - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '评测' , 'url' => '/evaluations/');
		$this->ur_heres[] = array('name' => $evaluation_data['Evaluation']['name'] , 'url' => '/evaluations/view/'.$id);
		$this->ur_heres[] = array('name' => '开始评测' , 'url' => '');
        	$this->set('ur_heres', $this->ur_heres);
        	
        	$evaluation_code=$evaluation_data['Evaluation']['code'];
		$evaluation_rule_list=$this->EvaluationRule->evaluation_rule_list($evaluation_code);
		$this->set('evaluation_rule_list',$evaluation_rule_list);
		
        	$evaluation_time=intval($evaluation_data['Evaluation']['evaluation_time']);
        	$this->set('evaluation_time',$evaluation_time);
        	
        	$conditions=array();
        	$conditions['UserEvaluationLog.user_id']=$user_id;
        	$conditions['UserEvaluationLog.evaluation_id']=$id;
        	$conditions['UserEvaluationLog.start_time >=']=date('Y-m-d H:i:s',time()-1800);
        	$conditions['and']['or']['UserEvaluationLog.submit_time <=']=date('Y-m-d 00:00:00');
        	$conditions['and']['or']['UserEvaluationLog.submit_time']=null;
        	$user_evaluation_log_data=$this->UserEvaluationLog->find('first',array('conditions'=>$conditions));
        	
		if(empty($user_evaluation_log_data)){
			$conditions=array();
			$conditions['EvaluationRule.evaluation_code']=$evaluation_code;
			$conditions['EvaluationRule.child_evaluation_code <>']='';
				$evaluation_rule_info=$this->EvaluationRule->find('all',array('fields'=>'child_evaluation_code,question_type,proportion,orderby','conditions'=>$conditions,'order'=>'EvaluationRule.orderby,EvaluationRule.id'));
			$conditions=array();
			$conditions['EvaluationQuestion.status']='1';
			$evaluation_question_datas=array();
			if(!empty($evaluation_rule_info)){
				$question_orderby_list=array();
				$evaluation_rule_orderby=array();
				foreach($evaluation_rule_info as $v){
					$evaluation_rule_orderby[$v['EvaluationRule']['child_evaluation_code']]=$v['EvaluationRule']['orderby'];
					$conditions['EvaluationQuestion.evaluation_code']=$v['EvaluationRule']['child_evaluation_code'];
		        		$conditions['EvaluationQuestion.question_type']=$v['EvaluationRule']['question_type'];
		        		$evaluation_question_infos=$this->EvaluationQuestion->find('all',array('conditions'=>$conditions,'limit'=>$v['EvaluationRule']['proportion'],'order'=>'RAND()'));
		        		$evaluation_question_datas=array_merge($evaluation_question_datas,$evaluation_question_infos);
				}
				if(!isset($evaluation_rule_orderby[$evaluation_code])){
					$evaluation_question_infos=$this->EvaluationQuestion->find('all',array('conditions'=>array('EvaluationQuestion.evaluation_code'=>$evaluation_code,'EvaluationQuestion.status'=>'1'),'order'=>'RAND()'));
					$evaluation_question_datas=array_merge($evaluation_question_datas,$evaluation_question_infos);
				}
				foreach($evaluation_question_datas as $k=>$v){
					$parent_evaluation_code=$v['EvaluationQuestion']['evaluation_code'];
					if(isset($evaluation_rule_orderby[$parent_evaluation_code])){
						$orderby_value=$evaluation_rule_orderby[$parent_evaluation_code].'.'.$v['EvaluationQuestion']['question_type'].".".$v['EvaluationQuestion']['id'];
						$evaluation_question_datas[$k]['EvaluationQuestion']['orderby']=$orderby_value;
						$v['EvaluationQuestion']['orderby']=$orderby_value;
					}
					$question_orderby_list[]=$v['EvaluationQuestion']['orderby'].".".$v['EvaluationQuestion']['question_type'].".".$v['EvaluationQuestion']['id'];
				}
				array_multisort($question_orderby_list,SORT_ASC,SORT_NUMERIC,$evaluation_question_datas);
			}else{
				$conditions['EvaluationQuestion.evaluation_code']=$evaluation_code;
		        	$evaluation_question_datas=$this->EvaluationQuestion->find('all',array('conditions'=>$conditions,'order'=>'orderby,question_type,id'));
			}
	        	$this->set('evaluation_question_datas',$evaluation_question_datas);
	        	$timestamp=isset($_REQUEST['timestamp'])?intval($this->clean_xss($_REQUEST['timestamp']))/1000+5:time();
	        	if($timestamp<=strtotime(date('Y-m-d 00:00:00')))$timestamp=time();
	        	$user_evaluation_log_info=array(
	        		'id'=>0,
	        		'user_id'=>$user_id,
	        		'evaluation_id'=>$id,
	        		'start_time'=>date('Y-m-d H:i:s',$timestamp),
	        		'end_time'=>date('Y-m-d H:i:s',$timestamp+$evaluation_time*60),
	        		'submit_time'=>null,
	        		'score'=>0
	        	);
	        	$this->UserEvaluationLog->save($user_evaluation_log_info);
	        	$user_evaluation_log_id=$this->UserEvaluationLog->id;
	        	
	        	foreach($evaluation_question_datas as $v){
	        		$user_evaluation_log_detail=array(
	        			'id'=>0,
	        			'user_evaluation_log_id'=>$user_evaluation_log_id,
	        			'evaluation_question_id'=>$v['EvaluationQuestion']['id']
	        		);
	        		$this->UserEvaluationLogDetail->save($user_evaluation_log_detail);
	        	}
	        	$user_evaluation_log_data=$this->UserEvaluationLog->findById($user_evaluation_log_id);
        	}else{
        		$user_evaluation_log_id=$user_evaluation_log_data['UserEvaluationLog']['id'];
        	}
        	$this->set('user_evaluation_log_data',$user_evaluation_log_data);
        	$evaluation_questions=$this->UserEvaluationLogDetail->find('all',array('conditions'=>array('UserEvaluationLogDetail.user_evaluation_log_id'=>$user_evaluation_log_id),'order'=>'UserEvaluationLogDetail.id'));
        	$this->set('evaluation_questions',$evaluation_questions);
        	
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
        	
		$evaluation_rule_score=array();
		foreach($evaluation_rule_list as $v){
			$evaluation_rule_score[$v['EvaluationRule']['child_evaluation_code']][$v['EvaluationRule']['question_type']]=$v['EvaluationRule']['score'];
		}
		$this->set('evaluation_rule_score',$evaluation_rule_score);
	}
	
	function ajax_submit_answer(){
		//登录验证
        	$this->checkSessionUser();
        	Configure::write('debug', 1);
        	$this->layout="ajax";
        	$result=array();
        	$result['code']='0';
        	$result['message']='Data Error';
        	if ($this->RequestHandler->isPost()) {
        		$this->data=$this->clean_xss($this->data);
        		if(!empty($this->data['UserEvaluationLogDetail'])){
        			foreach($this->data['UserEvaluationLogDetail'] as $v){
        				$answer_data=array(
        					'id'=>$v['id'],
        					'marks'=>$v['marks'],
        					'answer'=>isset($v['answer'])&&is_array($v['answer'])?implode(',',$v['answer']):(isset($v['answer'])?$v['answer']:'')
        				);
        				$this->UserEvaluationLogDetail->save($answer_data);
        			}
        			$result['code']='1';
        			$result['message']=$this->ld['saved_successfully'];
        		}
        	}
        	die(json_encode($result));
	}
	
	function score_result($evaluation_log_id=0){
		//登录验证
        	$this->checkSessionUser();
		$this->layout = 'default_full';
		
		$this->pageTitle = '评测结果 - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '评测' , 'url' => '/evaluations/');
		$this->ur_heres[] = array('name' => '评测结果' , 'url' => '');
		$this->set('ur_heres', $this->ur_heres);
		
		if ($this->RequestHandler->isPost()){
			$this->data=$this->clean_xss($this->data);
			if(!empty($this->data['UserEvaluationLog'])){
				$Evaluation_data=$this->data['UserEvaluationLog'];
				$evaluation_log_id=$Evaluation_data['id'];
				$Evaluation_info=$this->UserEvaluationLog->findById($evaluation_log_id);
				
				if(empty($Evaluation_info)||$Evaluation_info['UserEvaluationLog']['submit_time']!='')$this->redirect('index');
				if(isset($Evaluation_data['submit_time'])&&$Evaluation_data['submit_time']>0){
					$Evaluation_data['submit_time']=date("Y-m-d H:i:s",$Evaluation_data['submit_time']/1000);
				}else{
					$Evaluation_data['submit_time']=date("Y-m-d H:i:s");
				}
				$Evaluation_data['end_time']=$Evaluation_data['submit_time'];
				$evaluation_id=isset($Evaluation_info['UserEvaluationLog']['evaluation_id'])?$Evaluation_info['UserEvaluationLog']['evaluation_id']:0;
				$evaluation_data=$this->Evaluation->find('first',array('conditions'=>array('Evaluation.id'=>$evaluation_id,'Evaluation.status'=>'1')));
				
				$evaluation_code=$evaluation_data['Evaluation']['code'];
				$evaluation_rule_list=$this->EvaluationRule->evaluation_rule_list($evaluation_code);
				
				$evaluation_score_data=$this->UserEvaluationLogDetail->find('first',array('fields'=>'SUM(EvaluationQuestion.score) as score_total','conditions'=>array('UserEvaluationLogDetail.user_evaluation_log_id'=>$evaluation_log_id,'EvaluationQuestion.score <>'=>0)));
				$evaluation_score_total=isset($evaluation_score_data[0]['score_total'])?$evaluation_score_data[0]['score_total']:0;
				$Txt_evaluation_questions=$this->UserEvaluationLogDetail->find('count',array('conditions'=>array('UserEvaluationLogDetail.user_evaluation_log_id'=>$evaluation_log_id,'EvaluationQuestion.question_type'=>'2')));
				if(!empty($Txt_evaluation_questions)){
					$Evaluation_data['ipaddress']=$this->real_ip();
					$Evaluation_data['system']=$this->get_os();
					$Evaluation_data['browser']=$this->getbrowser();
					$this->UserEvaluationLog->save($Evaluation_data);
					$this->redirect("/evaluations/score_result/".$evaluation_log_id);
				}
				$score=0;
				$ability_experience_value=array();
				$evaluation_questions=$this->UserEvaluationLogDetail->find('all',array('conditions'=>array('UserEvaluationLogDetail.user_evaluation_log_id'=>$evaluation_log_id,'UserEvaluationLogDetail.answer=EvaluationQuestion.right_answer')));
				if(!empty($evaluation_questions)){
					foreach($evaluation_questions as $v){
						$score+=$v['EvaluationQuestion']['score'];
						$ability_experience_value[$v['EvaluationQuestion']['ability_code']][]=$v['UserEvaluationLogDetail']['id'];
					}
				}
				$score_rule=$score/$evaluation_score_total*100;
				$Evaluation_data['score']=$score_rule;
				$Evaluation_data['status']='1';
				$Evaluation_data['ipaddress']=$this->real_ip();
				$Evaluation_data['system']=$this->get_os();
				$Evaluation_data['browser']=$this->getbrowser();
				$this->UserEvaluationLog->save($Evaluation_data);
				$user_id=$Evaluation_info['UserEvaluationLog']['user_id'];
				$EvaluationResult=false;
				$experience_value=isset($this->configs['evaluation_experience_value'])?intval($this->configs['evaluation_experience_value']):0;
				if(isset($evaluation_data['Evaluation']['pass_score'])&&intval($evaluation_data['Evaluation']['pass_score'])<=$score){
					//评测合格
					$experience_value+=$experience_value;
					$EvaluationResult=true;
				}
				$this->UserAbility->experience_value_change($user_id,$ability_experience_value);
				$user_action_data=array(
					'id'=>0,
					'user_id'=>$user_id,
					'type'=>'evaluation',
					'type_id'=>$evaluation_id,
					'content'=>$evaluation_data['Evaluation']['name']
				);
				$this->UserAction->save($user_action_data);
				if($EvaluationResult){
					$synchro_user = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.status' => 1, 'SynchroUser.type' => 'wechat','SynchroUser.user_id'=>$user_id)));
					if(!empty($synchro_user)){
						$touser=$synchro_user['SynchroUser']['account'];
						$evaluation_level_code=trim($evaluation_data['Evaluation']['user_level_code']);
						if($evaluation_level_code!=''){
							$level_info=$this->UserLevel->find('first',array('conditions'=>array('UserLevel.code'=>$evaluation_level_code,'UserLevel.status'=>'1')));
						}
						$user_info=$this->User->find('first',array('conditions'=>array('User.id'=>$user_id,'User.status'=>'1')));
						
	    					$notify_template_info=$this->NotifyTemplateType->typeformat("evaluation_through","wechat");
	    					if(isset($notify_template_info['wechat'])){
		    					$notify_template=isset($notify_template_info['wechat'])?$notify_template_info['wechat']:array();
		    					$wechat_params=$this->NotifyTemplateType->wechatparamsformat($notify_template);
			    				$action_content='评测通过';
			    				$user_name=isset($user_info['User']['name'])?$user_info['User']['name']:$user_info['User']['mobile'];
			    				$user_ip=$this->real_ip();
			    				$evaluation_date=date('Y-m-d H:i:s');
			    				$evaluation_score=$score;
			    				$evaluation_level=isset($level_info['UserLevel'])?$level_info['UserLevel']['name']:'-';
			    				$action_desc="点击【详情】查看!";
			    				$wechat_message=array();
							foreach($wechat_params as $k=>$v){
								$wechat_message[$k]=array(
									'value'=>isset($$v)?$$v:''
								);
							}
							$request_url=$this->server_host.'/user_evaluation_logs/view/'.$evaluation_log_id;
							$wechat_post=array(
					   			'touser'=>$touser,
					   			'template_id'=>$notify_template['NotifyTemplateTypeI18n']['param03'],
					   			'url'=>$request_url,
					   			'data'=>$wechat_message
					   		);
					   		$this->Notify->wechat_message($wechat_post);
					   	}
					}
				}
				$this->redirect("/evaluations/score_result/".$evaluation_log_id);
			}else{
				$this->redirect('index');
			}
		}
		$user_id=$_SESSION['User']['User']['id'];
		$evaluation_log_data=$this->UserEvaluationLog->find('first',array('conditions'=>array('UserEvaluationLog.id'=>$evaluation_log_id,'UserEvaluationLog.user_id'=>$user_id)));
		$this->set('evaluation_log_data',$evaluation_log_data);
		if(empty($evaluation_log_data)||$evaluation_log_data['UserEvaluationLog']['submit_time']=='')$this->redirect('index');
		$evaluation_id=isset($evaluation_log_data['UserEvaluationLog']['evaluation_id'])?$evaluation_log_data['UserEvaluationLog']['evaluation_id']:0;
		$evaluation_data=$this->Evaluation->find('first',array('conditions'=>array('Evaluation.id'=>$evaluation_id,'Evaluation.status'=>'1')));
		$this->set('evaluation_data',$evaluation_data);
		
		$evaluation_score=$evaluation_log_data['UserEvaluationLog']['score'];
		$this->set('evaluation_score',$evaluation_score);
	}
	
	 /**
         *实际id.
         */
	public function real_ip(){
            static $realip = null;
            if ($realip !== null) {
                	return $realip;
            }
            if (isset($_SERVER)) {
                if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr as $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realip = $ip;
                        break;
                    }
                }
                } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                    $realip = $_SERVER['HTTP_CLIENT_IP'];
                } else {
                    if (isset($_SERVER['REMOTE_ADDR'])) {
                        $realip = $_SERVER['REMOTE_ADDR'];
                    } else {
                        $realip = '0.0.0.0';
                    }
                }
            } else {
                if (getenv('HTTP_X_FORWARDED_FOR')) {
                    $realip = getenv('HTTP_X_FORWARDED_FOR');
                } elseif (getenv('HTTP_CLIENT_IP')) {
                    $realip = getenv('HTTP_CLIENT_IP');
                } else {
                    $realip = getenv('REMOTE_ADDR');
                }
            }
            preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
            $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
            return $realip;
	}
	
	/**
	*	获得游览器.
	*/
    	public function getbrowser(){
		global $_SERVER;
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$browser = '';
		$browser_ver = '';
		
		if (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs)) {
			$browser = 'OmniWeb';
			$browser_ver = $regs[2];
		}

		if (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs)) {
			$browser = 'Netscape';
			$browser_ver = $regs[2];
		}

		if (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
			$browser = 'Safari';
			$browser_ver = $regs[1];
		}

		if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
			$browser = 'Internet Explorer';
			$browser_ver = $regs[1];
		}

		if (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
			$browser = 'Opera';
			$browser_ver = $regs[1];
		}

		if (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs)) {
			$browser = '(Internet Explorer '.$browser_ver.') NetCaptor';
			$browser_ver = $regs[1];
		}

		if (preg_match('/Maxthon/i', $agent, $regs)) {
			$browser = '(Internet Explorer '.$browser_ver.') Maxthon';
			$browser_ver = '';
		}

		if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
			$browser = 'FireFox';
			$browser_ver = $regs[1];
		}

		if (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs)) {
			$browser = 'Lynx';
			$browser_ver = $regs[1];
		}
		
		if (preg_match('/Chrome\/([^\s]+)/i', $agent, $regs)) {
			$browser = 'Chrome';
			$browser_ver = $regs[1];
		}
		
		if (preg_match('/QQBrowser\/([^\s]+)/i',$agent,$regs)){
			$browser = 'QQ';
			$browser_ver = $regs[1];
		}

		if ($browser != '') {
			return $browser.' '.$browser_ver;
		} else {
			return 'Unknow browser';
		}
	}
	
	/**
	*	获得用户操作系统
	*/
	function get_os(){
		global $_SERVER;
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$os = false;
		if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent)){
			$os = 'Windows Vista';
		}else if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent)){
			$os = 'Windows 7';
		}else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent)){
			$os = 'Windows 8';
		}else if(preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent)){
			$os = 'Windows 10';#添加win10判断
		}else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent)){
			$os = 'Windows XP';
		}else if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent)){
			$os = 'Windows 2000';
		}else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent)){
			$os = 'Windows NT';
		}else if (preg_match('/win/i', $agent)&&preg_match('/95/i', $agent)){
			$os = 'Windows 95';
		}else if (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90')){
			$os = 'Windows ME';
		}else if (preg_match('/win/i', $agent) && preg_match('/98/i', $agent)){
			$os = 'Windows 98';
		}else if (preg_match('/win/i', $agent) && preg_match('/32/i', $agent)){
			$os = 'Windows 32';
		}else if(preg_match('/win/i', $agent)){
			$os = 'Windows';
		}else if (preg_match('/linux/i', $agent)){
			$os = 'Linux';
		}else if (preg_match('/unix/i', $agent)){
			$os = 'Unix';
		}else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent)){
			$os = 'SunOS';
		}else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent)){
			$os = 'IBM OS/2';
		}else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent)){
			$os = 'Macintosh';
		}else if (preg_match('/PowerPC/i', $agent)){
			$os = 'PowerPC';
		}else if (preg_match('/AIX/i', $agent)){
			$os = 'AIX';
		}else if (preg_match('/HPUX/i', $agent)){
			$os = 'HPUX';
		}else if (preg_match('/NetBSD/i', $agent)){
			$os = 'NetBSD';
		}else if (preg_match('/BSD/i', $agent)){
			$os = 'BSD';
		}else if (preg_match('/OSF1/i', $agent)){
			$os = 'OSF1';
		}else if (preg_match('/IRIX/i', $agent)){
			$os = 'IRIX';
		}else if (preg_match('/FreeBSD/i', $agent)){
			$os = 'FreeBSD';
		}else if (preg_match('/teleport/i', $agent)){
			$os = 'teleport';
		}else if (preg_match('/flashget/i', $agent)){
			$os = 'flashget';
		}else if (preg_match('/webzip/i', $agent)){
			$os = 'webzip';
		}else if (preg_match('/offline/i', $agent)){
			$os = 'offline';
		}else{
			$os = '未知操作系统';
		}
		return $os;
	}

	//评测管理
	public function evaluation_management($page=1,$limit=10){
        	//登录验证
        	$this->checkSessionUser();
		$_GET=$this->clean_xss($_GET);
		//$this->layout = 'usercenter';//引入模版
		$this->page_init();                        //页面初始化
        	$this->pageTitle = '评测管理 - '.$this->configs['shop_title'];
		//当前位置开始
		if(isset($_GET['organizations_id'])){
			$this->ur_heres[] = array('name' => '用户中心', 'url' => '/users/index');
			$this->ur_heres[] = array('name' => '我的组织', 'url' => '/organizations/');
			$this->ur_heres[] = array('name' => '评测管理', 'url' => '/courses/course_management?organizations_id='.$_GET['organizations_id']);
		}else{
			$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
			$this->ur_heres[] = array('name' => '我的评测', 'url' => '/user_evaluation_logs/index');
		}
		$user_id=$_SESSION['User']['User']['id'];
		//获取我的信息
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
		if ($user_list['User']['rank'] > 0) {
			$rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
			$user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
		}
		$this->set('user_list', $user_list);
		
		$organizations_id = isset($_GET['organizations_id'])?$_GET['organizations_id']:'';
		$this->set('organizations_id', $organizations_id);
		$organizations_name = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organizations_id)));
		$this->set('organizations_name', $organizations_name);
		$user_member_list = $this->OrganizationMember->find('list',array('fields'=>'OrganizationMember.id','conditions'=>array('OrganizationMember.user_id'=>$_SESSION['User']['User']['id'])));
        	// $user_manage_list = $this->OrganizationDepartment->find('list',array('fields'=>'OrganizationDepartment.id','conditions'=>array('OrganizationDepartment.department_manage'=>$_SESSION['User']['User']['id'])));
        	$my_jobs = $this->OrganizationMemberJob->find('list',array('fields'=>'OrganizationMemberJob.organization_department_id','conditions'=>array('OrganizationMemberJob.organization_member_id'=>$user_member_list)));
	        $my_jobs = array_unique($my_jobs);
	        $user_manage_list = $this->OrganizationDepartment->find('list',array('fields'=>'OrganizationDepartment.id','conditions'=>array('OrganizationDepartment.id'=>$my_jobs)));
	        $user_organization_list = $this->Organization->find('list',array('fields'=>'Organization.id','conditions'=>array('Organization.manage_user'=>$_SESSION['User']['User']['id'])));
	        $organization_share_conditions = array('OrganizationShare.share_type'=>'evaluation');
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
	        $evaluation_share=$this->OrganizationShare->find('list',array('fields'=>'OrganizationShare.share_type_id','conditions'=>$organization_share_conditions));
	        $evaluation_share = array_unique($evaluation_share);
        	
		$organization_relations = $this->OrganizationRelation->find('list',array('fields'=>'type_id','conditions'=>array('OrganizationRelation.organization_id'=>$organizations_id,'OrganizationRelation.type'=>'evaluation')));
		$evaluation_cansee_conditions = array('Evaluation.status'=>1);
		if(!empty($organization_relations))$evaluation_cansee_conditions['or'][]=array('Evaluation.id'=>$organization_relations);
		$evaluation_cansee_conditions['or'][] = array(
	        	'Evaluation.visibility'=>0,
	        	'Evaluation.id'=>$evaluation_share
        	);
		$evaluation_cansee_conditions['or'][] = array(
	        	'Evaluation.visibility'=>0,
	        	'Evaluation.user_id'=>$organizations_name['Organization']['manage_user']
        	);
		$evaluation_cansee_conditions['or'][] = array(
	        	'Evaluation.visibility'=>1,
	        	'Evaluation.user_id'=>$_SESSION['User']['User']['id']
        	);
		if($organizations_name['Organization']['manage_user']==$_SESSION['User']['User']['id']){
			$evaluation_cansee_conditions['or'][] = array(
	        	'Evaluation.visibility'=>2,
	        	'Evaluation.user_id'=>$organizations_name['Organization']['manage_user']
	        	);
		}
		$evaluation_cansee_conditions['or'][] = array(
	        	'Evaluation.visibility'=>2,
	        	'Evaluation.id'=>$evaluation_share
        	);
		$evaluation_list = $this->Evaluation->find('all', array('order' => 'created desc','conditions'=>$evaluation_cansee_conditions));
		if(!empty($evaluation_list)){
	        	foreach($evaluation_list as $kk=>$vv){
		        	$question_count = $this->EvaluationQuestion->find('count',array('conditions'=>array('EvaluationQuestion.evaluation_code'=>$vv["Evaluation"]["code"])));
		        	$rule_list=$this->EvaluationRule->evaluation_rule_list($vv["Evaluation"]["code"]);
		        	if(!empty($rule_list)){
		        		foreach($rule_list as $kkk=>$vvv){
		        			$question_count+=$vvv["EvaluationRule"]["proportion"];
		        		}
		        	}
		        	$evaluation_list[$kk]["Evaluation"]["question_count"]=$question_count;
		        }
        	}
		$this->set('evaluation_list', $evaluation_list);
		if(isset($_GET['get_eval_num'])&&$_GET['get_eval_num']!=''){
			Configure::write('debug',1);
        		$this->layout = 'ajax';
			die(json_encode(count($evaluation_list)));
		}
		if(empty($organizations_name))$this->redirect('/organizations/index');
		$organization_actions=$this->Organization->manager_operator($organizations_id,$user_id);
		$this->set('organization_actions',$organization_actions);
		if(!in_array('evaluation',$organization_actions))$this->redirect('/organizations/view/'.$organizations_id);
		$import_evaluation_list = $this->Evaluation->import_evaluation_list('O',$organizations_id);
		$this->set('import_evaluation_list', $import_evaluation_list);
        	$org_info = $this->Organization->find('all',array('conditions'=>array('Organization.manage_user'=>$user_id)));
        	foreach ($org_info as $k => $v) {
        		$org_info_check[$v['Organization']['id']] = $v;
        	}
        $jorg_info = $this->OrganizationMember->find('all',array('conditions'=>array('OrganizationMember.user_id'=>$user_id,'OrganizationMember.status'=>1)));
        //pr($jorg_info);
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
        //pr($org_info);
        $this->set('org_info',$org_info);
        foreach ($org_info as $kk1 => $vv1) {
        	$check_org[$vv1['Organization']['id']] = $vv1['Organization']['name'];
        }
        if(isset($check_org)){
        	$this->set('check_org',$check_org);
        }
        //pr($evaluation_list);
        $conditions=array();
		$conditions['Evaluation.created <>']='';
		
        $parameters=array();
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'evaluations', 'action' => 'index', 'page' => $page, 'limit' => $limit);
		//分页参数
		$options = array('page' => $page, 'show' => $limit, 'modelClass' => 'Evaluation');
		$this->Pagination->init($conditions, $parameters, $options); // Added
		$user_evaluation_log_lists=$this->Evaluation->find('all',array('conditions'=>$conditions,'order'=>'Evaluation.created desc','page'=>$page,'limit'=>$limit));

		// start
            $org_id = $_GET['organizations_id'];
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
        $manage_ids[]=$organization_info['Organization']['manage_user'];
        $this->set('manager_ids',$manage_ids);
        // end
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
    }

    //添加评测
	function add(){
    		//登录验证
        	$this->checkSessionUser();
		$_GET=$this->clean_xss($_GET);
		//$this->layout = 'usercenter';//引入模版
		$this->page_init();                        //页面初始化
        	$this->pageTitle = '评测管理 - '.$this->configs['shop_title'];
		//当前位置开始
		if(isset($_GET['organizations_id'])){
			$this->ur_heres[] = array('name' => '用户中心', 'url' => '/users/index');
			$this->ur_heres[] = array('name' => '我的组织', 'url' => '/organizations/');
			$this->ur_heres[] = array('name' => '评测管理', 'url' => '/courses/course_management?organizations_id='.$_GET['organizations_id']);
			$this->ur_heres[] = array('name' => '添加评测', 'url' => '');
		}else{
			$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
			$this->ur_heres[] = array('name' => '我的评测', 'url' => '/user_evaluation_logs/index');
			$this->ur_heres[] = array('name' => '添加评测', 'url' => '');
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

		$organizations_id = isset($_GET['organizations_id'])?$_GET['organizations_id']:'';
        $organizations_name = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organizations_id)));
	    $this->set('organizations_name', $organizations_name);
        $this->set('organizations_id',$organizations_id);

		$evaluation_category=$this->EvaluationCategory->evaluation_category_list();
        if ($this->RequestHandler->isPost()) {
        	if($this->data['Evaluation']['evaluation_category_code']=='-1'){
        		$evaluation_categorie_add['EvaluationCategory'] = array(
        			'user_id'=>$_SESSION['User']['User']['id'],
        			'parent_id'=>0,
        			'code'=>$_POST['evaluation_category_code_1'],
        			'name'=>$_POST['evaluation_category_code_1'],
        			'status'=>1
        			);
        		$this->EvaluationCategory->save($evaluation_categorie_add);
        		$this->data['Evaluation']['evaluation_category_code'] = $_POST['evaluation_category_code_1'];
        	}
        	if($this->data['Evaluation']['code']==''){
        		$this->data['Evaluation']['code'] = $this->data['Evaluation']['name'].'_code';
        	}
            $this->Evaluation->save($this->data);
            $evaluation_id=$this->Evaluation->id;
            if($organizations_id!=''){
        		$organization_relations['OrganizationRelation'] = array(
        			'id'=>0,
        			'organization_id'=>$organizations_id,
        			'type'=>'evaluation',
        			'type_id'=>$evaluation_id
        			);
        		$this->OrganizationRelation->save($organization_relations);
        	}
        	if(empty($this->data['Evaluation']['id'])){
        		$evaluation_code='evaluation_'.$evaluation_id;
        		$this->Evaluation->updateAll(array('code'=>"'".$evaluation_code."'"),array('id'=>$evaluation_id));
            }
            //$evaluation_add = $this->Evaluation->find('first',array('conditions'=>array('Evaluation.code'=>$this->data['Evaluation']['code'])));
            if(isset($_GET['organizations_id'])){
            	$this->redirect('/evaluations/edit/'.$evaluation_id.'?organizations_id='.$organizations_id);
            }else{
            	$this->redirect('/evaluations/edit/'.$evaluation_id);
            }
            //$this->Evaluation->save($this->data);
            //$back_url = $this->operation_return_url();//获取操作返回页面地址
            //$this->redirect('/user_evaluation_logs/index');
            //echo '<script language="javascript">history.go(-2);</script>';
        }
        $this->set('evaluation_category', $evaluation_category);

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
		$this->get_manager($organizations_id);
		$this->set('organization_info',$organization_info);
    }
    /**
     * 检查code
     *
     */
    function ajax_check_code()
    {
    	Configure::write('debug', 1);
        $result['code'] = 0;
        $result['msg'] = '';
        if ($this->RequestHandler->isPost()) {
            $code = isset($_POST['code']) ? $_POST['code'] : '';
            $evaluation_count = $this->Evaluation->find('count', array('conditions' => array('Evaluation.code' => $code, 'Evaluation.status' => "1")));
            if ($evaluation_count == 0) {
                $result['code'] = 1;
            } else {
                $result['msg'] = "code已存在";
            }
            die(json_encode($result));
        } else {
            $this->redirect('/evaluations');
        }
    }

    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        //$result['message'] = $this->ld['delete_member_failure'];
        $evaluation_info = $this->Evaluation->findById($id);
        $this->OrganizationRelation->deleteAll(array('type' => 'evaluation','type_id'=>$id));
        $this->Evaluation->deleteAll(array('id' => $id));
        $this->EvaluationQuestion->deleteAll(array('EvaluationQuestion.evaluation_code' => $evaluation_info["Evaluation"]["code"]));
        $this->EvaluationRule->deleteAll(array('EvaluationRule.evaluation_code' => $evaluation_info["Evaluation"]["code"]));
        //操作员日志
        // if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
        //     $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id.' '.$evaluation_info['Evaluation']['code'], $this->admin['id']);
        // }
        $result['flag'] = 1;
        //$result['message'] = $this->ld['delete_member_success'];
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/evaluations/');
        }
    }
    //删除学习评测
    public function remove_study($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        //操作员日志
        $UserEvaluationLog_detail = $this->UserEvaluationLog->find('first',array('conditions'=>array('UserEvaluationLog.id' => $id,'UserEvaluationLog.user_id'=>$_SESSION['User']['User']['id'])));
        if(!empty($UserEvaluationLog_detail)){
        	$this->UserEvaluationLog->deleteAll(array('UserEvaluationLog.id' => $id));
        	$this->UserEvaluationLogDetail->deleteAll(array('UserEvaluationLogDetail.user_evaluation_log_id' => $id));
        }
        $result['flag'] = 1;
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/evaluations/');
        }
    }
    /**
     *编辑评测
     */
    public function edit($id)
    {
    		//登录验证
        	$this->checkSessionUser();
		$_GET=$this->clean_xss($_GET);
		//$this->layout = 'usercenter';//引入模版
		$this->page_init();                        //页面初始化
        	$this->pageTitle = '评测管理 - '.$this->configs['shop_title'];
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
		$organizations_name = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organizations_id)));
		$this->set('organizations_name', $organizations_name);
		$this->set('organizations_id',$organizations_id);
		
		$organization_actions=$this->Organization->manager_operator($organizations_id,$user_id);
		$this->set('organization_actions',$organization_actions);
		if(isset($_GET['organizations_id'])&&!empty($_GET['organizations_id'])){
			if(empty($organizations_name))$this->redirect('/organizations/index');
			if(!in_array('evaluation',$organization_actions))$this->redirect('/organizations/view/'.$organizations_id);
		}
		
		if ($this->RequestHandler->isPost()) {
			//pr($_POST);exit();
        	if($this->data['Evaluation']['evaluation_category_code']=='-1'){
        		$evaluation_categorie_add['EvaluationCategory'] = array(
        			'user_id'=>0,
        			'parent_id'=>0,
        			'code'=>$_POST['evaluation_category_code_1'],
        			'name'=>$_POST['evaluation_category_code_1'],
        			'status'=>1
        			);
        		$this->EvaluationCategory->save($evaluation_categorie_add);
        		$this->data['Evaluation']['evaluation_category_code'] = $_POST['evaluation_category_code_1'];
        	}
        	if($this->data['Evaluation']['code']==''){
        		$this->data['Evaluation']['code'] = $this->data['Evaluation']['name'].'_code';
        	}
            $this->Evaluation->save($this->data);
            if(isset($_GET['organizations_id'])){
            	$this->redirect('/evaluations/evaluation_management/?organizations_id='.$organizations_id);
            }else{
            	$this->redirect('/user_evaluation_logs/index/');
            }
            //$this->Evaluation->save($this->data);
            //$back_url = $this->operation_return_url();//获取操作返回页面地址
            //$this->redirect('/evaluations/evaluation_management');
            //echo '<script language="javascript">history.go(-2);</script>';
        }

		$evaluation_info=$this->Evaluation->find('first',array('conditions'=>array('Evaluation.id'=>$id)));
		$users_list = $this->User->find('all');
		//pr($users_list);
		$condition = '';
        $start_score_time = '';
        $end_score_time = '';
        $start_date_time = '';
        $end_date_time = '';
        $score="";
        if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
            $condition_user['or']['User.name like'] = '%' . $_REQUEST['keyword'] . '%';
            $condition_user['or']['User.email like'] = '%' . $_REQUEST['keyword'] . '%';
            $condition_user['or']['User.mobile like'] = '%' . $_REQUEST['keyword'] . '%';
            $users_list = $this->User->find('all',array('conditions'=>$condition_user));
            $users_id = $users_list[0]['User']['id'];
            $condition['or']['UserEvaluationLog.user_id'] = $users_id;
            $this->set('keyword', $_REQUEST['keyword']);
        }
        if (isset($this->params['url']['score']) && $this->params['url']['score'] != '') {
            $condition['and']['UserEvaluationLog.score'] = $this->params['url']['score'];
            $score = $this->params['url']['score'];
            $this->set('score', $score);
        }
        if (isset($this->params['url']['start_score_time']) && $this->params['url']['start_score_time'] != '') {
            $condition['and']['UserEvaluationLog.score >='] = $this->params['url']['start_score_time'];
            $start_score_time = $this->params['url']['start_score_time'];
            $this->set('start_score_time', $start_score_time);
        }
        if (isset($this->params['url']['end_score_time']) && $this->params['url']['end_score_time'] != '') {
            $condition['and']['UserEvaluationLog.score <='] = $this->params['url']['end_score_time'];
            $end_score_time = $this->params['url']['end_score_time'];
            $this->set('end_score_time', $end_score_time);
        }
        if (isset($this->params['url']['start_date_time']) && $this->params['url']['start_date_time'] != '') {
            $condition['and']['UserEvaluationLog.created >='] = $this->params['url']['start_date_time'].' 00:00:00';
            $start_date_time = $this->params['url']['start_date_time'];
            $this->set('start_date_time', $start_date_time);
        }
        if (isset($this->params['url']['end_date_time']) && $this->params['url']['end_date_time'] != '') {
            $condition['and']['UserEvaluationLog.created <='] = $this->params['url']['end_date_time'].' 23:59:59';
            $end_date_time = $this->params['url']['end_date_time'];
            $this->set('end_date_time', $end_date_time);
        }
        $condition['and']['UserEvaluationLog.evaluation_id'] = $id;
		$user_evaluation=$this->UserEvaluationLog->find('all',array('conditions'=>$condition,'order' => 'start_time desc'));
		//$info_resource=$this->InformationResource->information_formated(false,'question_type');
		$info_resource = array(
				'0'=>'单选',
				'1'=>'多选'
			);

		$evaluation_category=$this->EvaluationCategory->evaluation_category_list();
		$evaluation_rule_info=$this->EvaluationRule->evaluation_rule_list($evaluation_info["Evaluation"]["code"]);
		$evaluation_question_info=$this->EvaluationQuestion->find('all',array('conditions'=>array('EvaluationQuestion.evaluation_code'=>$evaluation_info["Evaluation"]["code"])));
		
		$evaluation_condition=$this->Precondition->find('all',array('conditions'=>array('Precondition.object'=>'evaluation','Precondition.object_code'=>$evaluation_info["Evaluation"]["code"])));
		$level_list=$this->AbilityLevel->find('all',array('conditions'=>array('AbilityLevel.status'=>1)));
		$condition_code=array();
        $parent_name="";
        //$condition_resource=$this->InformationResource->information_formated('evaluation_condition',$this->backend_locale,'false');
        $condition_resource = array(
				'cycle'=>'周期',
				'parent_evaluation'=>'前置评测',
				'ability_level'=>'能力等级'
			);
        if(!empty($evaluation_condition)){
        	foreach($evaluation_condition as $kk=>$vv){
        		$condition_code[]=$vv['Precondition']['params'];
        		if($vv['Precondition']['params']=="parent_evaluation"){
        			$parent_ids=explode(',',$vv['Precondition']['value']);
        			$parent_info=$this->Evaluation->find('list',array("fields"=>"Evaluation.name",'conditions'=>array('Evaluation.id'=>$parent_ids)));
        			$parent_name=implode(",",$parent_info);
        		}
        	}
        }
        
        //分享搜索条件
        $user_member_list = $this->OrganizationMember->find('list',array('fields'=>'OrganizationMember.id','conditions'=>array('OrganizationMember.user_id'=>$_SESSION['User']['User']['id'],'OrganizationMember.status'=>1)));
        $user_manage_list = $this->OrganizationDepartment->find('list',array('fields'=>'OrganizationDepartment.id','conditions'=>array('OrganizationDepartment.department_manage'=>$_SESSION['User']['User']['id'],'OrganizationDepartment.status'=>1)));
        $user_organization_list = $this->Organization->find('list',array('fields'=>'Organization.id','conditions'=>array('Organization.manage_user'=>$_SESSION['User']['User']['id'],'Organization.status'=>1)));
        $organization_share_conditions = array(
        	'OrganizationShare.share_type'=>'evaluation',
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
        $organization_share=$this->OrganizationShare->find('all',array('conditions'=>$organization_share_conditions));

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

        $profile_code="user_question_upload";
        $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id','Profile.code'), 'conditions' => array('Profile.code' => $profile_code, 'Profile.status' => 1)));

        $organizations_id = isset($_GET['organizations_id'])?$_GET['organizations_id']:'';
        $organizations_name = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organizations_id)));
	    $this->set('organizations_name', $organizations_name);
        $this->set('organizations_id',$organizations_id);

        $this->set('evaluations_id',$id);
        $this->set('evaluation_category',$evaluation_category);
        $this->set('profile_info',$profile_info);
        $this->set('level_list', $level_list);
		$this->set('evaluation_info', $evaluation_info);
		$this->set('info_resource', $info_resource);
		$this->set('user_evaluation', $user_evaluation);
		$this->set('parent_name', $parent_name);
        $this->set('condition_code', $condition_code);
        $this->set('evaluation_rule_info', $evaluation_rule_info);
        $this->set('evaluation_question_info', $evaluation_question_info);
        $this->set('evaluation_condition', $evaluation_condition);
        $this->set('condition_resource', $condition_resource);
        $this->set('organization_share', $organization_share);
        $this->set('users_list', $users_list);

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
			$this->ur_heres[] = array('name' => '评测管理', 'url' => '/courses/course_management?organizations_id='.$_GET['organizations_id']);
			$this->ur_heres[] = array('name' => $evaluation_info['Evaluation']['name'], 'url' => '');
        }else{
        	$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
			$this->ur_heres[] = array('name' => '我的评测', 'url' => '/user_evaluation_logs/index');
			$this->ur_heres[] = array('name' => $evaluation_info['Evaluation']['name'], 'url' => '');
        }
    }

    function ajax_modify(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        if(isset($_POST['edit_params']) && !empty($_POST['edit_params'])){
        	$this->data["Precondition"]["params"]=$_POST['edit_params'];
        }
        if($this->data["Precondition"]["params"]!="parent_evaluation"){
		if(is_array($this->data["Precondition"]["value"])){
			$this->data["Precondition"]["value"]=implode(",",$this->data["EvaluationCondition"]["value"]);
		}
		if($this->data["Precondition"]["value"]==0){$this->data["EvaluationCondition"]["value"]="";}
		$this->data['Precondition']['object']='evaluation';
		$this->Precondition->save($this->data);
        }
        $result['code']='1';
        $result['message']='操作成功';
        die(json_encode($result));
    }

    function remove_condition($id){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $this->Precondition->deleteAll(array('Precondition.id' => $id));
        $result['flag'] = 1;
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/evaluations/');
        }
    }

    function ajax_modify_rule(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $this->EvaluationRule->save($this->data);
        $result['code']='1';
        $result['message']='操作成功';
        die(json_encode($result));
    }

    function remove_rule($id){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $this->EvaluationRule->deleteAll(array('EvaluationRule.id' => $id));
        $result['flag'] = 1;
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/evaluations/');
        }
    }

    function ajax_modify_question(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $this->EvaluationQuestion->save($this->data);
        $evaluation_question_id=$this->EvaluationQuestion->id;
        $evaluation_question_code['EvaluationQuestion'] = array(
        	'id'=>$evaluation_question_id,
        	'code'=>'evaluation_question_'.$evaluation_question_id
        	);
        $this->EvaluationQuestion->save($evaluation_question_code);
        
        $result['code']='1';
        $result['message']='操作成功';
        die(json_encode($result));
    }

    function remove_question($id){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $this->EvaluationQuestion->deleteAll(array('EvaluationQuestion.id' => $id));
        $result['flag'] = 1;
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/evaluations/');
        }
    }

    public function searchEvaluation()
    {
        $condition = '';
        //设置返回初始参数
        $result['flag'] = 2;//2 失败 1成功
        $evaluation_keyword = empty($_REQUEST['evaluation_keyword']) ? '' : trim($_REQUEST['evaluation_keyword']);//关键字
        $condition_id = empty($_REQUEST['condition_id']) ? '' : trim($_REQUEST['condition_id']);
        //初始化条件
        $evaluation_condition_info=$this->Precondition->find('first',array('conditions'=>array('EvaluationCondition.id'=>$condition_id)));
        if($evaluation_condition_info["Precondition"]["value"]!=""){
            $condition_value=explode(",",$evaluation_condition_info["Precondition"]["value"]);
            foreach($condition_value as $k=>$v){
                $condition['and'][]['Evaluation.id !='] = $v;
            }
        }
        $condition['and']['Evaluation.status'] = '1';
        $condition['or']['Evaluation.name like'] = '%' .$evaluation_keyword. '%';
        $condition['or']['Evaluation.description like'] = '%' .$evaluation_keyword. '%';
        $fields[] = 'Evaluation.id';
        $fields[] = 'Evaluation.name';
        $fields[] = 'Evaluation.code';
        $evaluation_list = $this->Evaluation->find('all', array('conditions' => $condition, 'order' => 'Evaluation.id desc', 'fields' => $fields));
        if (count($evaluation_list) > 0) {
            $result['flag'] = 1;
            $result['content'] = $evaluation_list;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function add_relation_evaluation()
    {
        //设置返回初始参数
        $result['flag'] = 2;//2 失败 1成功
        //$result['content'] = $this->ld['unknown_reasons'];
        $evaluation_id = $_REQUEST['evaluation_id'];
        $condition_id = $_REQUEST['condition_id'];
        if($condition_id==0){
            $code=$_REQUEST['code'];
            $evaluation_condition_info['Precondition']['object']='evaluation';
            $evaluation_condition_info['Precondition']['object_code']=$code;
            $evaluation_condition_info['Precondition']['params']="parent_evaluation";
            $evaluation_condition_info['Precondition']['value']="0";
        }else{
            $evaluation_condition_info=$this->Precondition->find('first',array('conditions'=>array('Precondition.id'=>$condition_id)));
        }
        if($evaluation_id!=0){
            if(empty($evaluation_condition_info['Precondition']['value']) || $evaluation_condition_info['Precondition']['value']=="0"){
                $evaluation_condition_info['Precondition']['value']=$_REQUEST['evaluation_id'];
            }else{
                $evaluation_condition_info['Precondition']['value']=$evaluation_condition_info['Precondition']['value'].",".$_REQUEST['evaluation_id'];
            }
            $this->Precondition->save($evaluation_condition_info);
        }
        if($condition_id==0){
            $condition_id=$this->Precondition->id;
        }
        $condition_array=explode(",",$evaluation_condition_info['Precondition']['value']);
        $condition['and']['Evaluation.status'] = '1';
        $condition['and']['Evaluation.id'] =$condition_array;
        $fields[] = 'Evaluation.id';
        $fields[] = 'Evaluation.name';
        $evaluation_list = $this->Evaluation->find('all', array('conditions' => $condition, 'order' => 'Evaluation.id desc', 'fields' => $fields));
        $result['flag'] = 1;//2 失败 1成功
        $result['content'] = $evaluation_list;
        $result['condition_id'] = $condition_id;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    function ajax_condition_edit($id){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $evaluation_condition_info=$this->Precondition->find('first',array('conditions'=>array('Precondition.id'=>$id)));
        $result['code']='1';
        $result['data']=$evaluation_condition_info;
        die(json_encode($result));
    }

    function ajax_rule_edit($id){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $evaluation_rule_info=$this->EvaluationRule->find('first',array('conditions'=>array('EvaluationRule.id'=>$id)));
        $result['code']='1';
        $result['data']=$evaluation_rule_info;
        die(json_encode($result));
    }

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
        		//pr($depart_info);exit();
        		//$this->set('depart_info',$depart_info);
        	}
        }
        die(json_encode($depart_info));
        //json_encode($mem_info);exit();
    }

    public function get_job(){
    	Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!empty($_POST)){
        	if(isset($_POST['depart_id'])&&$_POST['depart_id']!=''){
        		$depart_id = $_POST['depart_id'];
        		//pr($org_id);exit();
        		$cons['and']['OrganizationJob.organization_department_id'] = $depart_id;
        		$job_info = $this->OrganizationJob->find('all',array('conditions'=>$cons,'order'=>'OrganizationJob.organization_department_id'));
        		//pr($job_info);exit();
        		//$this->set('job_info',$job_info);
        	}
        }
        die(json_encode($job_info));
    }

    public function get_mem(){
    	Configure::write('debug', 1);
        $this->layout = 'ajax';
        $cons = array();
        $mem_info = array();
        if(!empty($_POST)){
        	if(isset($_POST['org_id'])&&$_POST['org_id']!=''){
        		$org_id = $_POST['org_id'];
        		//pr($org_id);exit();
        		$con['and']['OrganizationMember.organization_id'] = $org_id;
        		$con['and']['OrganizationMember.status'] = 1;
        		$mem_info = $this->OrganizationMember->find('all',array('conditions'=>$con,'order'=>'OrganizationMember.organization_id'));
        		$cons['OrganizationMemberJob.organization_id'] = $org_id;
        		foreach ($mem_info as $k => $v) {
        			$cons['OrganizationMemberJob.organization_member_id'][] = $v['OrganizationMember']['id'];
        		}
        		$mem_job_info = $this->OrganizationMemberJob->find('all',array('conditions'=>$cons));
        		//pr($mem_job_info);exit();
        		$job_info = $this->OrganizationDepartment->find('all',array('conditions'=>array('OrganizationDepartment.organization_id'=>$org_id)));
        		//pr($job_info);exit();
        		foreach ($job_info as $k => $v) {
        			$job_info_c[$v['OrganizationDepartment']['id']] = $v;
        		}
        		//pr($job_info_c);exit();
        		foreach ($mem_job_info as $k => $v) {
        			$mem_job_info[$k]['OrganizationMemberJob']['department'] = $job_info_c[$v['OrganizationMemberJob']['organization_department_id']]['OrganizationDepartment']['name'];
        		}
        		//pr($mem_job_info);exit();
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
        		//pr($job_check);exit();
        		//$this->set('depart_info',$depart_info);
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
        		//pr($mem_info);exit();

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
        		//pr($mem_job_info);exit();
        		$job_info = $this->OrganizationDepartment->find('all',array('conditions'=>array('OrganizationDepartment.organization_id'=>$org_id)));
        		//pr($job_info);exit();
        		foreach ($job_info as $k => $v) {
        			$job_info_c[$v['OrganizationDepartment']['id']] = $v;
        		}
        		//pr($job_info_c);exit();
        		foreach ($mem_job_info as $k => $v) {
        			$mem_job_info[$k]['OrganizationMemberJob']['department'] = $job_info_c[$v['OrganizationMemberJob']['organization_department_id']]['OrganizationDepartment']['name'];
        		}
        		//pr($mem_job_info);exit();
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
        		//pr($job_check);exit();
        		//$this->set('depart_info',$depart_info);
        		foreach ($mem_info as $k => $v) {
        			$mem_info[$k]['OrganizationMember']['depart'] = isset($job_check[$v['OrganizationMember']['id']])?$job_check[$v['OrganizationMember']['id']]:'';
        		}
        		//pr($mem);exit();
        	}
        	if(isset($_POST['job_id'])&&$_POST['job_id']!=''){
        		$job_id = $_POST['job_id'];
        		$orga_info = $this->OrganizationJob->find('first',array('conditions'=>array('OrganizationJob.id'=>$job_id)));
        		$org_id = $orga_info['OrganizationJob']['organization_id'];
        		$con['and']['OrganizationMemberJob.organization_job_id'] = $job_id;
        		$mem = $this->OrganizationMemberJob->find('all',array('conditions'=>$con));
        		//pr($mem_info);exit();
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
        		//pr($mem_job_info);exit();
        		$job_info = $this->OrganizationDepartment->find('all',array('conditions'=>array('OrganizationDepartment.organization_id'=>$org_id)));
        		//pr($job_info);exit();
        		foreach ($job_info as $k => $v) {
        			$job_info_c[$v['OrganizationDepartment']['id']] = $v;
        		}
        		//pr($job_info_c);exit();
        		foreach ($mem_job_info as $k => $v) {
        			$mem_job_info[$k]['OrganizationMemberJob']['department'] = $job_info_c[$v['OrganizationMemberJob']['organization_department_id']]['OrganizationDepartment']['name'];
        		}
        		//pr($mem_job_info);exit();
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
        		//pr($job_check);exit();
        		//$this->set('depart_info',$depart_info);
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
        		//pr($mem_info);exit();

        		foreach ($mem_info as $kk => $vv) {
        			$con['and']['Organization.id'][] = $vv['OrganizationMember']['organization_id'];
        			$or_info[$vv['OrganizationMember']['organization_id']][] = $vv['OrganizationMember']['id'];
        		}
        		//pr($or_info);exit();
        		$org_info = $this->Organization->find('all',array('conditions'=>$con));
        		//pr($org_info);exit();
        		foreach ($org_info as $k1 => $v1) {
        			$o_info[$v1['Organization']['id']] = $v1['Organization']['name'];
        		}
        		foreach ($mem_info as $kkk => $vvv){
        			$mem_name = $vvv['OrganizationMember']['name'];
        			$org_name = $o_info[$vvv['OrganizationMember']['organization_id']];
        			$surl = $this->server_host;
        			//pr($surl);exit();
        			$mem_mobile = $vvv['OrganizationMember']['mobile'];
        			$url = $surl.'/evaluations/view/'.$_POST['eval_id'];
        			if($vvv['OrganizationMember']['email'] !=''){
        			$email = $vvv['OrganizationMember']['email'];
        			//pr($url);exit();
        			$Notify_template=$this->NotifyTemplateType->typeformat('evaluation_invite_member','email');
            		if(!empty($Notify_template)){
                    	$subject=$Notify_template['email']['NotifyTemplateTypeI18n']['title'];
		    				@eval("\$subject = \"$subject\";");
		    				$html_body = addslashes($Notify_template['email']['NotifyTemplateTypeI18n']['param01']);
						@eval("\$html_body = \"$html_body\";");
						$text_body = $Notify_template['email']['NotifyTemplateTypeI18n']['param02'];
						@eval("\$text_body = \"$text_body\";");
                	}
                	//pr($content);
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
            // 			$Notify_template=$this->NotifyTemplateType->typeformat('evaluation_invite_member','sms');
		        		// if(!empty($Notify_template)){
		          //       $content=$Notify_template['sms']['NotifyTemplateTypeI18n']['param02'];
		          //       @eval("\$content = \"$content\";");
		          //   	}
		          //   	//pr($content);exit();
		        		// $sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
		        		// $sms_result=$this->Notify->send_sms($vvv['OrganizationMember']['mobile'],$content,$sms_kanal,$this->configs);
            		}
        		}
        		//pr($or_info);
        		foreach ($or_info as $kk1 => $vv1) {
        			$conss['and']['OrganizationMember.organization_id'][]=$kk1;
        		}
        		$me_info = $this->OrganizationMember->find('all',array('conditions'=>$conss));
        		//pr($me_info);exit();
        		foreach ($me_info as $kk2 => $vv2) {
        			$memb_info[$vv2['OrganizationMember']['id']] = $vv2['OrganizationMember']['user_id'];
        		}
        		//pr($or_info);exit();
        		foreach ($or_info as $k4 => $v4) {
        			$share_info['OrganizationShare']['share_object_ids'] = '';
        			$share_info['OrganizationShare']['id'] = 0;
            		$share_info['OrganizationShare']['organization_id'] = $k4;
            		$share_info['OrganizationShare']['share_user'] = $user_id;
            		$share_info['OrganizationShare']['share_type'] = 'evaluation';
            		$share_info['OrganizationShare']['share_type_id'] = $_POST['eval_id'];
            		$share_info['OrganizationShare']['share_object'] = 0;
            		foreach ($v4 as $kk4 => $vv4) {
            			$share_info['OrganizationShare']['share_object_ids'] = $vv4;
            			$this->OrganizationShare->save($share_info);
            		}
        		} 
        		$result['code'] = 1;
        	}

        	if(isset($_POST['depart_id'])&&$_POST['depart_id']!=''){
        		$depart_info = $this->OrganizationDepartment->find('first',array('conditions'=>array('OrganizationDepartment.id'=>$_POST['depart_id'])));
        		$org_info = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$depart_info['OrganizationDepartment']['organization_id'])));
        		//pr($_POST['depart_mem_id']);exit();
        		$con = '';
        		if(isset($_POST['depart_mem_id'])&&count($_POST['depart_mem_id'])>0){
        			foreach ($_POST['depart_mem_id'] as $k => $v) {
        				$con['OrganizationMember.id'][]=$v;
        			}
        		}
        		$depart_mem_info = $this->OrganizationMember->find('all',array('conditions'=>$con));
        		//pr($depart_mem_info);exit();
        		$org_name = $org_info['Organization']['name'];
        		$surl = $this->server_host;
        		$url = $surl.'/evaluations/view/'.$_POST['eval_id'];
        		foreach ($depart_mem_info as $k => $v) {
        			$mem_name = $v['OrganizationMember']['name'];
        			if($v['OrganizationMember']['email']!=''){
        			$email = $v['OrganizationMember']['email'];
    			$Notify_template=$this->NotifyTemplateType->typeformat('evaluation_invite_member','email');
            		if(!empty($Notify_template)){
                    	$subject=$Notify_template['email']['NotifyTemplateTypeI18n']['title'];
		    				@eval("\$subject = \"$subject\";");
		    				$html_body = addslashes($Notify_template['email']['NotifyTemplateTypeI18n']['param01']);
						@eval("\$html_body = \"$html_body\";");
						$text_body = $Notify_template['email']['NotifyTemplateTypeI18n']['param02'];
						@eval("\$text_body = \"$text_body\";");
                	}
                	//pr($content);
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
		        	//pr($text_body);exit();
            		$mail_result=$this->Notify->send_email($mail_send_queue, $this->configs);
            	}else{
        			// $Notify_template=$this->NotifyTemplateType->typeformat('evaluation_invite_member','sms');
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
        		$share_info['OrganizationShare']['share_type'] = 'evaluation';
        		$share_info['OrganizationShare']['share_type_id'] = $_POST['eval_id'];
        		$share_info['OrganizationShare']['share_object'] = 1;
        		$share_info['OrganizationShare']['share_object_ids'] = $_POST['depart_id'];
        		//pr($share_info);
        		$this->OrganizationShare->save($share_info);
        		$result['code'] = 1;
        	}

        	if(isset($_POST['org_id'])&&$_POST['org_id']!=''){
        		$organ_info = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$_POST['org_id'])));
        		$organ_mem_info = $this->OrganizationMember->find('all',array('conditions'=>array('OrganizationMember.organization_id'=>$_POST['org_id'],'OrganizationMember.status'=>1)));
        		if(isset($organ_info)&&$organ_info!=''){
        			$org_name = $organ_info['Organization']['name'];
        			$surl = $this->server_host;
        			$url = $surl.'/evaluations/view/'.$_POST['eval_id'];
        			foreach ($organ_mem_info as $k => $v) {
        				if($v['OrganizationMember']['email']!=''){
        					$mem_name = $v['OrganizationMember']['name'];
        				$email = $v['OrganizationMember']['email'];
        				$Notify_template=$this->NotifyTemplateType->typeformat('evaluation_invite_member','email');
	            		if(!empty($Notify_template)){
	                    	$subject=$Notify_template['email']['NotifyTemplateTypeI18n']['title'];
			    				@eval("\$subject = \"$subject\";");
			    				$html_body = addslashes($Notify_template['email']['NotifyTemplateTypeI18n']['param01']);
							@eval("\$html_body = \"$html_body\";");
							$text_body = $Notify_template['email']['NotifyTemplateTypeI18n']['param02'];
							@eval("\$text_body = \"$text_body\";");
	                	}
	                	//pr($content);
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
			        	//pr($text_body);exit();
	            		$mail_result=$this->Notify->send_email($mail_send_queue, $this->configs);
	            	}else{
            // 			$Notify_template=$this->NotifyTemplateType->typeformat('evaluation_invite_member','sms');
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
	            		$share_info['OrganizationShare']['share_type'] = 'evaluation';
	            		$share_info['OrganizationShare']['share_type_id'] = $_POST['eval_id'];
	            		$share_info['OrganizationShare']['share_object'] = 2;
	            		$share_info['OrganizationShare']['share_object_ids'] = $organ_info['Organization']['id'];
	            		//pr($share_info);
	            		$this->OrganizationShare->save($share_info);
	            		$result['code'] = 1;
        		}
        	}
        }
        die(json_encode($result));
    }

    function ajax_user_share($id=0){
    	Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result = array();
        $user_id = $_SESSION['User']['User']['id'];
        $eval_id = $id;
        $user_info = array();
        if(!empty($_POST)){
        	//pr($this->data);exit();
        	foreach ($this->data['user_mobile'] as $k1 => $v1) {
        		if($v1 != ''){
        			$user_inf = array();
	        		$user_inf['name'] = $this->data['user_name'][$k1];
	        		$user_inf['mobile'] = $v1;
	        		//pr($user_info);exit();
	        		$user_info[]=$user_inf;
        		}
        	}
        	//pr($user_info);exit();
        	foreach ($user_info as $k => $v) {
        		$user_in = $this->User->find('first',array('conditions'=>array('User.mobile'=>$v['mobile'])));
        		$user_mem = $this->OrganizationMember->find('first',array('conditions'=>array('OrganizationMember.user_id'=>$user_in['User']['id'])));
	        	$org_info = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
	        	$org_name = $org_info['User']['name'];
	        	$mem_name = $v['name'];
	        	//pr($user_info);exit();
	        	if(isset($user_in)&&!empty($user_in)){
	        		$surl = $this->server_host;
	        		$url = $surl.'/evaluations/view/'.$eval_id;
	        		//pr($url);exit();
	    			$Notify_template=$this->NotifyTemplateType->typeformat('evaluation_invite_member','sms');
	        		if(!empty($Notify_template)){
	                $content=$Notify_template['sms']['NotifyTemplateTypeI18n']['param02'];
	                @eval("\$content = \"$content\";");
	            	}
	            	//pr($content);exit();
	        		$sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
	        		$sms_result=$this->Notify->send_sms($v['mobile'],$content,$sms_kanal,$this->configs);
	        		$sh_info['OrganizationShare']['id'] = 0;
	        		$sh_info['OrganizationShare']['organization_id'] = 0;
	        		$sh_info['OrganizationShare']['share_user'] = $user_id;
	        		$sh_info['OrganizationShare']['share_type'] = 'evaluation';
	        		$sh_info['OrganizationShare']['share_type_id'] = $eval_id;
	        		$sh_info['OrganizationShare']['share_object'] = 0;
	        		$sh_info['OrganizationShare']['share_object_ids'] = isset($user_mem['OrganizationMember']['id'])&&$user_mem['OrganizationMember']['id']!=''?$user_mem['OrganizationMember']['id']:$v['name'].'|'.$v['mobile'];
	        		$this->OrganizationShare->save($sh_info);
	        	}else{
	        		$surl = $this->server_host;
	        		$url = $surl.'/evaluations/view/'.$eval_id;
	        		//pr($url);exit();
	    			$Notify_template=$this->NotifyTemplateType->typeformat('evaluation_invite_member','sms');
	        		if(!empty($Notify_template)){
	                $content=$Notify_template['sms']['NotifyTemplateTypeI18n']['param02'];
	                @eval("\$content = \"$content\";");
	            	}
	            	//pr($content);exit();
	        		$sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
	        		$sms_result=$this->Notify->send_sms($v['mobile'],$content,$sms_kanal,$this->configs);
	        		$sh_info['OrganizationShare']['id'] = 0;
	        		$sh_info['OrganizationShare']['organization_id'] = 0;
	        		$sh_info['OrganizationShare']['share_user'] = $user_id;
	        		$sh_info['OrganizationShare']['share_type'] = 'evaluation';
	        		$sh_info['OrganizationShare']['share_type_id'] = $eval_id;
	        		$sh_info['OrganizationShare']['share_object'] = 0;
	        		$sh_info['OrganizationShare']['share_object_ids'] = $v['name'].'|'.$v['mobile'];
	        		$this->OrganizationShare->save($sh_info);
	        	}
        	}
        	
        }
        $re = array();
        $re['message'] = '';
        if(isset($result)&&count($result)>0){
        	$re['message'] ='手机号'.chr(13).chr(10).implode(chr(13).chr(10), $result).chr(13).chr(10).'尚未注册，邀请失败！';
        }
        die(json_encode($re));
    }

    function preview($code){
        $this->set('code',$code);
        $profile_code="user_question_upload";
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
        //pr($profilefiled_info);exit();
        if(empty($profile_info))$this->redirect('/evaluations/evaluation_management');
        $preview_data=array();
        if (!empty($_FILES['evaluation_question'])) {
            if ($_FILES['evaluation_question']['error'] > 0) {
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['upload'].$this->ld['failed']."');window.location.href=history.go(-1);</script>";
                die();
            }else{
                $question_option_names=array();
                for($i='A';$i<='E';$i++){
                    $question_option_names[]=$i;
                    $fields_desc_info[$i]=$i;
                }
                $handle = @fopen($_FILES['evaluation_question']['tmp_name'], 'r');
                $fields_array=array();
                $fields_desc=array();
                foreach($fields_info as $k=>$v){
                    if($k=='UserQuestionOption'){
                        foreach($question_option_names as $vv){
                            $fields_desc[]=$vv;
                            $fields_array[]=$vv;
                        }
                    }else{
                        $fields_array[]=$k;
                        $fields_desc[]=$v;
                    }
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
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('文件格式错误');window.location.href=history.go(-1);</script>";
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
        if(empty($preview_data))$this->redirect('/evaluations/');
    }

    public function batch_upload($code){
        Configure::write('debug',1);
        $this->layout="ajax";
        if ($this->RequestHandler->isPost()) {
            $upload_num=0;
            $checkboxs=isset($_POST['checkbox'])?$_POST['checkbox']:array();
            if(!empty($this->data)){
                foreach($this->data as $k=>$question_info){
                    if(!in_array($k,$checkboxs))continue;
                    $question_data=array();
                    $question_data=$question_info;
                    //$question_code=$question_data['UserQuestion']['tag'];
                    //if(trim($question_code)=='')continue;
                    //$questioninfo=$this->EvaluationQuestion->find('first',array('conditions'=>array('EvaluationQuestion.code'=>$question_code)));
                    //$question_data['code']='';
                    $question_data['id']=isset($questioninfo['EvaluationQuestion']['id'])?$questioninfo['EvaluationQuestion']['id']:0;
                    $question_data['create_by']='0';
                    $question_data['create_by_id']=$_SESSION['User']['User']['id'];
                    $question_data['evaluation_code']=$code;

                    $question_data['EvaluationQuestion'] = array(
                    	'id'=>0,
                    	'evaluation_code'=>$code,
                    	'tag'=>$question_data['UserQuestion']['tag'],
                    	'name'=>$question_info['UserQuestion']['name'],
                    	'question_type'=>$question_info['UserQuestion']['question_type'],
                    	'right_answer'=>$question_info['UserQuestion']['right_answer'],
                    	'analyze'=>$question_info['UserQuestion']['analyze'],
                    	'code'=>''
                    	);
                    $this->EvaluationQuestion->save($question_data['EvaluationQuestion']);
                    $question_id=$this->EvaluationQuestion->id;
                    $question_add = array(
                    	'id'=>$question_id,
                    	'code'=>'question_code_'.$question_id
                    	);
                    $question = $this->EvaluationQuestion->save($question_add);
                    $question_option=isset($question_info['EvaluationOption'])?$question_info['EvaluationOption']:array();
                    $option_names=array();
                    foreach($question_option as $option_name=>$option_desc){
                        if(trim($option_desc)=='')continue;
                        $question_option_info=$this->EvaluationOption->find('first',array('conditions'=>array('EvaluationOption.evaluation_question_code'=>'question_code_'.$question_id,'EvaluationOption.name'=>$option_name)));
                        $question_option_data=array(
                            'id'=>isset($question_option_info['EvaluationOption'])?$question_option_info['EvaluationOption']['id']:0,
                            'evaluation_question_code'=>'question_code_'.$question_id,
                            'name'=>$option_name,
                            'description'=>trim($option_desc),
                            'status'=>'1'
                        );
                        //pr($question_option_data);exit();
                        $this->EvaluationOption->save($question_option_data);
                        $option_names[]=$option_name;
                    }
                    if(!empty($option_names)){
                        $this->EvaluationOption->deleteAll(array('EvaluationOption.evaluation_question_code'=>'question_code_'.$question_id,'not'=>array('EvaluationOption.name'=>$option_names)));
                    }else{
                        $this->EvaluationOption->deleteAll(array('EvaluationOption.evaluation_question_code'=>'question_code_'.$question_id));
                    }
                    $upload_num++;
                }
            }
            if($upload_num==0){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['upload'].'失败'."');window.location.href=history.go(-2);</script>";
                die();
            }else{
                $upload_message="(".($upload_num).'/'.(sizeof($checkboxs)).")";
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['upload'].'成功'.$upload_message."');window.location.href=history.go(-2);</script>";
                die();
            }
        }else{
            //$this->redirect('/evaluations/evaluation_management');
            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['upload'].'成功'.$upload_message."');window.location.href=history.go(-2);</script>";
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

    public function evaluation_questions_view($id){
    	//登录验证
        	$this->checkSessionUser();
		$_GET=$this->clean_xss($_GET);
		//$this->layout = 'usercenter';//引入模版
		$this->page_init();                        //页面初始化
        	$this->pageTitle = '评测管理 - '.$this->configs['shop_title'];
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
        $organizations_name = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organizations_id)));
     	$this->set('organizations_name', $organizations_name);
     	$this->set('organizations_id', $organizations_id);
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

		//$this->operation_return_url(true);
        $evaluation_question_info=$this->EvaluationQuestion->find('first',array('conditions'=>array('EvaluationQuestion.id'=>$id)));
        
        $evaluation_info=$this->Evaluation->find('first',array('conditions'=>array('Evaluation.code'=>$evaluation_question_info['EvaluationQuestion']['evaluation_code'])));
        $evaluation_option_info=$this->EvaluationOption->find('all',array('conditions'=>array('EvaluationOption.evaluation_question_code'=>$evaluation_question_info["EvaluationQuestion"]["code"])));
        if ($this->RequestHandler->isPost()) {
            $this->EvaluationQuestion->save($this->data["EvaluationQuestion"]);
            if(isset($this->data["EvaluationOption"]) && is_array($this->data["EvaluationOption"])){
            	foreach($this->data["EvaluationOption"] as $k=>$v){
	                if($v["name"]!=""){
	                    $v["evaluation_question_code"]=$this->data["EvaluationQuestion"]["code"];
	                    $this->EvaluationOption->saveAll($v);
	                }
	            }
            }
            //$back_url = $this->operation_return_url();//获取操作返回页面地址
            if(isset($_GET['organizations_id'])){
            	$this->redirect('/evaluations/edit/'.$evaluation_info['Evaluation']['id'].'?organizations_id='.$organizations_id);
            }else{
            	$this->redirect('/evaluations/edit/'.$evaluation_info['Evaluation']['id']);
            }
        }
        $this->menu_path = array('root' => '/hr/','sub' => '/evaluations/');
        $this->set('title_for_layout', $this->ld['edit'].'-评测管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "资源开发",'url' => '');
        $this->navigations[] = array('name' => "评测管理",'url' => '/evaluations/');
        $this->navigations[] = array('name' => "编辑评测",'url' => '/evaluations/view/'.$evaluation_info['Evaluation']['id']);
        $this->navigations[] = array('name' => $evaluation_question_info['EvaluationQuestion']['name'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['edit']."题目",'url' => '');
        $this->set('evaluation_question_info', $evaluation_question_info);
        $this->set('evaluation_option_info', $evaluation_option_info);

        $organization_info = $this->Organization->find('first', array('conditions' => array('Organization.id' => $organizations_id)));
		$this->set('organization_info',$organization_info);

        if(isset($_GET['organizations_id'])){
        	$this->ur_heres[] = array('name' => '我的组织', 'url' => '/organizations/');
			$this->ur_heres[] = array('name' => '评测管理', 'url' => '/evaluations/evaluation_management?organizations_id='.$_GET['organizations_id']);
			$this->ur_heres[] = array('name' => $evaluation_question_info['EvaluationQuestion']['name'], 'url' => '');
        }else{
        	$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
			$this->ur_heres[] = array('name' => '我的评测', 'url' => '/user_evaluation_logs/index');
			$this->ur_heres[] = array('name' => $evaluation_question_info['EvaluationQuestion']['name'], 'url' => '');
        }
    }

    public function download_share_csv_example()
    {
        $tmp = array();
        $fields_array = array();
        $newdatas = array();
        $mem_info = $this->OrganizationMember->find('all', array('limit' => 5));
        //pr($mem_info);exit();
        $ch[]='姓名';
        $ch[]='手机号';
        $newdatas[] = $ch;
        $filename = '评测邀请'.date('Ymd').'.csv';
        foreach ($mem_info as $k => $v) {
            $ch = '';
            $ch[]=$v['OrganizationMember']['name'];
            $ch[]=$v['OrganizationMember']['mobile'];
            $newdatas[]=$ch;
        }

        //pr($filename);
        //pr($newdatas);exit();
        $this->Phpcsv->output($filename, $newdatas);
        exit;
    }

    public function check_code()
    {
        Configure::write('debug', 1);
        $result['code'] = 0;
        $result['msg'] = '';
        if ($this->RequestHandler->isPost()) {
            $code = isset($_POST['code']) ? $_POST['code'] : '';
            $question_count = $this->EvaluationQuestion->find('count', array('conditions' => array('EvaluationQuestion.code' => $code, 'EvaluationQuestion.status' => "1")));
            if ($question_count == 0) {
                $result['code'] = 1;
            } else {
                $result['msg'] = "code已存在";
            }
            die(json_encode($result));
        } else {
            $this->redirect('/evaluations');
        }
    }

    function ajax_modify_option(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $result['message']='操作失败';
        //pr($_POST["checkboxes"]);exit();
		foreach($_POST["checkboxes"] as $k=>$v){
			if($this->data[$v]["EvaluationOption"]["name"]!=""){
				$this->EvaluationOption->saveAll($this->data[$v]);
			}
		}
		
        $result['code']='1';
        $result['message']='操作成功';
        die(json_encode($result));
    }

    public function evaluation_option_remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        //$result['message'] = $this->ld['delete_member_failure'];
        $this->EvaluationOption->deleteAll(array('EvaluationOption.id' => $id));
        $result['flag'] = 1;
        //$result['message'] = $this->ld['delete_member_success'];
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/evaluations/');
        }
    }

     public function batch_share(){
     	$this->layout = 'ajax';//引入模版
		$this->page_init();   
		$user_id=$_SESSION['User']['User']['id'];   
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
		$this->set('user_list',$user_list); 
		//pr($_FILES);exit();	     
        if ($this->RequestHandler->isPost()) {
        	//pr($_FILES);exit();
        	//$this->set('uploads_list', '');
        	//die();
        	$eval_id = $_POST['eval_id'];
        	//pr($eval_id);exit();
        	$this->set('eval_id',$eval_id); 
            if (!empty($_FILES['file'])){
            	//die();
                if ($_FILES['file']['error'] > 0) {

                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/orders/uploaddelivery'</script>";
                    die();
                } else {
                	//exit();
                	//$this->set('uploads_list', '');
                    $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                    $csv_export_code = 'gb2312';
                    $i = 0;
                    $key_arr = array();
                    //exit();
                    while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                    	//pr($row);
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
                        //pr($temp);exit();
                        if (!isset($temp) || empty($temp)) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/orders/uploaddelivery';</script>";
                        }
                        $data[] = $temp;
                        //pr($data);
                    }
                    fclose($handle);
                    foreach ($data as $k => $v) {
                        if ($k == 0) {
                            continue;
                        }
                    }
                    //pr($data);exit();
                    $this->set('uploads_list', $data);
                }
            }
        }
    }

    public function ajax_batch_share($id=0){
    	Configure::write('debug', 1);
        $this->layout = 'ajax';
        //pr($this->data);exit();
        $user_mobile = '';
        $org_info = '';
        $mem_name='';
        $eval_id = $id;
        $result = array();
        $user_id = $_SESSION['User']['User']['id'];
    	if(!empty($_POST)){
    		//pr($this->data);
    		//pr($_POST['checkbox']);
    		foreach ($this->data as $k1 => $v1) {
        		if($v1[1] != ''){
	        		$con['and']['User.mobile'][] = $v1[1];
        		}
        	}
        	//pr($con);exit();
        	//$u_info = $this->User->find('all',array('conditions'=>$con));
        	//pr($u_info);exit();
        	
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
	        		$url = $surl.'/evaluations/edit/'.$eval_id;
	        		//pr($url);exit();
	    			$Notify_template=$this->NotifyTemplateType->typeformat('evaluation_invite_member','sms');
	        		if(!empty($Notify_template)){
		                $content=$Notify_template['sms']['NotifyTemplateTypeI18n']['param02'];
		                @eval("\$content = \"$content\";");
	            	}
	            	//pr($content);exit();
	        		$sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
	        		$sms_result=$this->Notify->send_sms($user_mobile,$content,$sms_kanal,$this->configs);
	        		$sh_info['OrganizationShare']['id'] = 0;
	        		$sh_info['OrganizationShare']['organization_id'] = 0;
	        		$sh_info['OrganizationShare']['share_user'] = $user_id;
	        		$sh_info['OrganizationShare']['share_type'] = 'evaluation';
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
        if(!empty($_POST)){
            if(isset($_POST['mobile'])&&$_POST['mobile']!=''){
                $mobile = $_POST['mobile'];
            }
            $user_info = $this->OrganizationMember->find('all',array('conditions'=>array('OrganizationMember.mobile'=>$mobile)));
            //pr($user_info);exit();
          foreach ($user_info as $k => $v) {
            	$con['Organization.manage_user'][]=$v['OrganizationMember']['user_id'];
            }
            //pr($con);exit();
            $org_info = $this->Organization->find('all',array('conditions'=>$con));
            //pr($org_info);exit();
            //$this->set('org_info',$org_info);
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
        	if(isset($_POST['eval_id'])&&$_POST['eval_id']!=''){
        		$eval_id = $_POST['eval_id'];
        	}
        	$org_manager = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$org_id)));
        	$user_info = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
    		//pr($org_manager);exit();
    		$mem_name = $org_manager['Organization']['contacts'];
    		$org_name = $user_info['User']['name'];
    		$url = $this->server_host.'/evaluations/view/'.$eval_id;
    		//pr($url);exit();
    		$email = $org_manager['Organization']['contact_way'];
    		//pr($email);exit();
        	$Notify_template=$this->NotifyTemplateType->typeformat('evaluation_invite_member','email');
        	//pr($Notify_template);exit();
        	if(!empty($Notify_template)){
				$subject=$Notify_template['email']['NotifyTemplateTypeI18n']['title'];
    				@eval("\$subject = \"$subject\";");
    				$html_body = addslashes($Notify_template['email']['NotifyTemplateTypeI18n']['param01']);
				@eval("\$html_body = \"$html_body\";");
				$text_body = $Notify_template['email']['NotifyTemplateTypeI18n']['param02'];
				@eval("\$text_body = \"$text_body\";");
			}
			//pr($html_body);
			//pr($text_body);exit();
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
	        //pr($mail_send_queue);
	        //pr($this->configs);exit();
			$mail_result=$this->Notify->send_email($mail_send_queue, $this->configs);
			if($mail_result==true){
				$share_info['OrganizationShare']['id'] = 0;
	    		$share_info['OrganizationShare']['organization_id'] = $_POST['org_id'];
	    		$share_info['OrganizationShare']['share_user'] = $user_id;
	    		$share_info['OrganizationShare']['share_type'] = 'evaluation';
	    		$share_info['OrganizationShare']['share_type_id'] = $eval_id;
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

    function org_name_invite(){
    	Configure::write('debug',1);
        $this->layout = 'ajax';
        $result = array();
        $result['code'] = 0;
        $result['message'] = '';
        $org_name = '';
        $eval_id = '';
        $user_id = $_SESSION['User']['User']['id'];
        $user_info = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
        if(!empty($_POST)){
        	if(isset($_POST['org_name'])&&$_POST['org_name']!=''){
        		$org_name = $_POST['org_name'];
        	}
        	if(isset($_POST['eval_id'])&&$_POST['eval_id']!=''){
        		$eval_id = $_POST['eval_id'];
        	}
        	$org_info = $this->Organization->find('first',array('conditions'=>array('Organization.name'=>$org_name)));
        	if($org_info == ''){
        		$result['message'] = '您邀请的公司不存在！';
        		die(json_encode($result));
        	}else{
        		$mem_name = $org_info['Organization']['contacts'];
	    		$org_name = $user_info['User']['name'];
	    		$url = $this->server_host.'/evaluations/view/'.$eval_id;
	    		//pr($url);exit();
	    		$email = $org_info['Organization']['contact_way'];
	    		//pr($email);exit();
	        	$Notify_template=$this->NotifyTemplateType->typeformat('evaluation_invite_member','email');
	        	//pr($Notify_template);exit();
	        	if(!empty($Notify_template)){
					$subject=$Notify_template['email']['NotifyTemplateTypeI18n']['title'];
	    				@eval("\$subject = \"$subject\";");
	    				$html_body = addslashes($Notify_template['email']['NotifyTemplateTypeI18n']['param01']);
					@eval("\$html_body = \"$html_body\";");
					$text_body = $Notify_template['email']['NotifyTemplateTypeI18n']['param02'];
					@eval("\$text_body = \"$text_body\";");
				}
				//pr($html_body);
				//pr($text_body);exit();
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
		    		$share_info['OrganizationShare']['share_type'] = 'evaluation';
		    		$share_info['OrganizationShare']['share_type_id'] = $eval_id;
		    		$share_info['OrganizationShare']['share_object'] = 0;
		    		$share_info['OrganizationShare']['share_object_ids'] = $org_info['Organization']['manage_user'];
		    		$this->OrganizationShare->save($share_info);
		    		$result['code'] = 1;
				}else{
					$result['message']='邀请失败！';
				}
	    		die(json_encode($result));
        	}
        }
    }

    function delete_share($id){
    	Configure::write('debug',1);
        $this->layout = 'ajax';
        $result = array();
        $result['code'] = 0;
        if($id!=0){
        	$this->OrganizationShare->deleteAll(array('id' => $id));
        	$result['code'] = 1;
        }
        die(json_encode($result));
    }

    function evaluation_study($id){
    		//登录验证
        	$this->checkSessionUser();
		$_GET=$this->clean_xss($_GET);
		//$this->layout = 'usercenter';//引入模版
		$this->page_init();                        //页面初始化
        	$this->pageTitle = '评测管理 - '.$this->configs['shop_title'];
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
		$evaluation_info=$this->Evaluation->find('first',array('conditions'=>array('Evaluation.id'=>$id)));
		$this->set('evaluation_info',$evaluation_info);
        	
        	$organization_actions=$this->Organization->manager_operator($organizations_id,$user_id);
		$this->set('organization_actions',$organization_actions);

        $condition = '';
        $start_score_time = '';
        $end_score_time = '';
        $start_date_time = '';
        $end_date_time = '';
        $score="";
        if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
            $condition_user['or']['User.name like'] = '%' . $_REQUEST['keyword'] . '%';
            $condition_user['or']['User.email like'] = '%' . $_REQUEST['keyword'] . '%';
            $condition_user['or']['User.mobile like'] = '%' . $_REQUEST['keyword'] . '%';
            $users_list = $this->User->find('all',array('conditions'=>$condition_user));
            if(sizeof($users_list)>0){
            	$users_id = $users_list[0]['User']['id'];
            	$condition['or']['UserEvaluationLog.user_id'] = $users_id;
            }else{
				$condition['or']['UserEvaluationLog.user_id'] = 'qwe';
            }
            $this->set('keyword', $_REQUEST['keyword']);
        }
        if (isset($this->params['url']['score']) && $this->params['url']['score'] != '') {
            $condition['and']['UserEvaluationLog.score'] = $this->params['url']['score'];
            $score = $this->params['url']['score'];
            $this->set('score', $score);
        }
        if (isset($this->params['url']['start_score_time']) && $this->params['url']['start_score_time'] != '') {
            $condition['and']['UserEvaluationLog.score >='] = $this->params['url']['start_score_time'];
            $start_score_time = $this->params['url']['start_score_time'];
            $this->set('start_score_time', $start_score_time);
        }
        if (isset($this->params['url']['end_score_time']) && $this->params['url']['end_score_time'] != '') {
            $condition['and']['UserEvaluationLog.score <='] = $this->params['url']['end_score_time'];
            $end_score_time = $this->params['url']['end_score_time'];
            $this->set('end_score_time', $end_score_time);
        }
        if (isset($this->params['url']['start_date_time']) && $this->params['url']['start_date_time'] != '') {
            $condition['and']['UserEvaluationLog.created >='] = $this->params['url']['start_date_time'].' 00:00:00';
            $start_date_time = $this->params['url']['start_date_time'];
            $this->set('start_date_time', $start_date_time);
        }
        if (isset($this->params['url']['end_date_time']) && $this->params['url']['end_date_time'] != '') {
            $condition['and']['UserEvaluationLog.created <='] = $this->params['url']['end_date_time'].' 23:59:59';
            $end_date_time = $this->params['url']['end_date_time'];
            $this->set('end_date_time', $end_date_time);
        }
        $condition['and']['UserEvaluationLog.evaluation_id'] = $id;
		$user_evaluation=$this->UserEvaluationLog->find('all',array('conditions'=>$condition,'order' => 'start_time desc'));
		$this->set('user_evaluation', $user_evaluation);
		$this->set('evaluations_id',$id);
		$users_list = $this->User->find('all',array());
	    $this->set('users_list', $users_list);

        $this->set('orga_id',$organizations_id);
		$organization_info = $this->Organization->find('first', array('conditions' => array('Organization.id' => $organizations_id)));
		$this->set('organization_info',$organization_info);

		if(isset($_GET['organizations_id'])){
        	$this->ur_heres[] = array('name' => '我的组织', 'url' => '/organizations/');
			$this->ur_heres[] = array('name' => '评测管理', 'url' => '/courses/course_management?organizations_id='.$_GET['organizations_id']);
			$this->ur_heres[] = array('name' => $evaluation_info['Evaluation']['name'], 'url' => '/evaluations/edit/'.$evaluation_info['Evaluation']['id'].'?organizations_id='.$organizations_id);
			$this->ur_heres[] = array('name' => '评测记录', 'url' => '');
        }else{
        	$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
			$this->ur_heres[] = array('name' => '我的评测', 'url' => '/user_evaluation_logs/index');
			$this->ur_heres[] = array('name' => $evaluation_info['Evaluation']['name'], 'url' => '/evaluations/edit/'.$evaluation_info['Evaluation']['id']);
			$this->ur_heres[] = array('name' => '评测记录', 'url' => '');
        }
    }

    function evaluation_share($id){
    		//登录验证
        	$this->checkSessionUser();
		$_GET=$this->clean_xss($_GET);
		//$this->layout = 'usercenter';//引入模版
		$this->page_init();                        //页面初始化
        	$this->pageTitle = '评测管理 - '.$this->configs['shop_title'];
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
		
		$evaluation_info=$this->Evaluation->find('first',array('conditions'=>array('Evaluation.id'=>$id)));
		$this->set('evaluation_info',$evaluation_info);

        //分享搜索条件
        $user_member_list = $this->OrganizationMember->find('list',array('fields'=>'OrganizationMember.id','conditions'=>array('OrganizationMember.user_id'=>$_SESSION['User']['User']['id'],'OrganizationMember.status'=>1)));
        $user_manage_list = $this->OrganizationDepartment->find('list',array('fields'=>'OrganizationDepartment.id','conditions'=>array('OrganizationDepartment.department_manage'=>$_SESSION['User']['User']['id'],'OrganizationDepartment.status'=>1)));
        $user_organization_list = $this->Organization->find('list',array('fields'=>'Organization.id','conditions'=>array('Organization.manage_user'=>$_SESSION['User']['User']['id'],'Organization.status'=>1)));
        $organization_share_conditions = array(
        	'OrganizationShare.share_type'=>'evaluation',
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
	    $users_list = $this->User->find('all',array());
	    $this->set('organization_share', $organization_share);
	    $this->set('users_list', $users_list);

	    $organization_share_list_condition = array();
        foreach ($organization_share as $k => $v) {
        	$organization_share_list_condition[] = $v['OrganizationShare']['organization_id'];
        }
	    $organization_share_list = $this->Organization->find('list',array('conditions'=>array('id'=>$organization_share_list_condition),'fields'=>'id,name'));
		$this->set('organization_share_list',$organization_share_list);

        $this->set('orga_id',$organizations_id);
		$organization_info = $this->Organization->find('first', array('conditions' => array('Organization.id' => $organizations_id)));
		$this->set('organization_info',$organization_info);

		if(isset($_GET['organizations_id'])){
        	$this->ur_heres[] = array('name' => '我的组织', 'url' => '/organizations/');
			$this->ur_heres[] = array('name' => '评测管理', 'url' => '/courses/course_management?organizations_id='.$_GET['organizations_id']);
			$this->ur_heres[] = array('name' => $evaluation_info['Evaluation']['name'], 'url' => '/evaluations/edit/'.$evaluation_info['Evaluation']['id'].'?organizations_id='.$organizations_id);
			$this->ur_heres[] = array('name' => '分享记录', 'url' => '');
        }else{
        	$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
			$this->ur_heres[] = array('name' => '我的评测', 'url' => '/user_evaluation_logs/index');
			$this->ur_heres[] = array('name' => $evaluation_info['Evaluation']['name'], 'url' => '/evaluations/edit/'.$evaluation_info['Evaluation']['id']);
			$this->ur_heres[] = array('name' => '分享记录', 'url' => '');
        }
    }
    
    
    function adduce(){
    		Configure::write('debug', 1);
		$this->layout = 'ajax';
		
		$result=array();
		$result['code']='0';
		$result['message']='';
		$user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
		$evaluation_id=isset($_POST['evaluation_id'])?$_POST['evaluation_id']:0;
		
		$evaluation_cond=array();
		$evaluation_cond['Evaluation.user_id']=0;
		$evaluation_cond['Evaluation.status']='1';
		$evaluation_cond['Evaluation.visibility']='0';
		$evaluation_cond['or'][]['Evaluation.price']='0';
		if(!empty($user_id)){
			$this->loadModel('OrderProduct');
			$order_cond=array();
			$order_cond['Order.user_id']=$user_id;
			$order_cond['Order.status']='1';
			$order_cond['Order.payment_status']='2';
			$order_cond['OrderProduct.item_type']='evaluation';
			$order_cond['OrderProduct.product_id >']='0';
			$having_buy_evaluations=$this->OrderProduct->find('all',array('fields'=>'OrderProduct.product_id','conditions'=>$order_cond));
			if(!empty($having_buy_evaluations)){
				$having_buy_evaluation_ids=array();
				foreach($having_buy_evaluations as $v)$having_buy_evaluation_ids=$v['OrderProduct']['product_id'];
				$evaluation_cond['or'][]['Evaluation.id']=$having_buy_evaluation_ids;
			}
		}
		if(empty($evaluation_id)){
			$evaluation_list=$this->Evaluation->find('all',array('fields'=>'id,name,img,price,description','conditions'=>$evaluation_cond,'order'=>'modified desc'));
			if(!empty($evaluation_list)){
				$result['code']='1';
				$result['message']=$evaluation_list;
			}
		}else{
			$evaluation_cond['Evaluation.id']=$evaluation_id;
			$evaluation_info=$this->Evaluation->find('first',array('conditions'=>$evaluation_cond));
			if(!empty($evaluation_info)&&!empty($user_id)){
				$evaluation_data=$evaluation_info['Evaluation'];
				$evaluation_code=$evaluation_info['Evaluation']['code'];
				$evaluation_question_list=$this->EvaluationQuestion->find('all',array('conditions'=>array('EvaluationQuestion.evaluation_code'=>$evaluation_code,'EvaluationQuestion.status'=>'1'),'order'=>'EvaluationQuestion.id','recursive' => -1));
				$evaluation_question_codes=array();
				foreach($evaluation_question_list as $v)$evaluation_question_codes[]=$v['EvaluationQuestion']['code'];
				$evaluation_option_infos=$this->EvaluationOption->find('all',array('conditions'=>array('EvaluationOption.evaluation_question_code'=>$evaluation_question_codes,'EvaluationOption.status'=>'1'),'order'=>'evaluation_question_code,EvaluationOption.name,EvaluationOption.orderby','recursive' => -1));
				$evaluation_option_lists=array();
				if(!empty($evaluation_option_infos)){
					foreach($evaluation_option_infos as $v){
						$evaluation_option_lists[$v['EvaluationOption']['evaluation_question_code']][]=$v['EvaluationOption'];
					}
				}
				if(isset($evaluation_data['created']))unset($evaluation_data['created']);
				if(isset($evaluation_data['modified']))unset($evaluation_data['modified']);
				$evaluation_data['id']=0;
				$evaluation_data['user_id']=$user_id;
				$evaluation_data['code']='';
				$evaluation_data['evaluation_category_code']='';
				$this->Evaluation->save($evaluation_data);
				$new_evaluation_id=$this->Evaluation->id;
				$new_evaluation_code='evaluation_'.$new_evaluation_id;
				$this->Evaluation->updateAll(array('code'=>"'".$new_evaluation_code."'"),array('id'=>$new_evaluation_id));
				foreach($evaluation_question_list as $v){
					$evaluation_question_data=$v['EvaluationQuestion'];
					$question_code=$evaluation_question_data['code'];
					$evaluation_question_data['id']=0;
					$evaluation_question_data['evaluation_code']=$new_evaluation_code;
					$evaluation_question_data['code']='';
					if(isset($evaluation_question_data['created']))unset($evaluation_question_data['created']);
					if(isset($evaluation_question_data['modified']))unset($evaluation_question_data['modified']);
					$this->EvaluationQuestion->save($evaluation_question_data);
					$new_question_id=$this->EvaluationQuestion->id;
					$new_question_code='question_'.$new_question_id;
					$this->EvaluationQuestion->updateAll(array('EvaluationQuestion.code'=>"'".$new_question_code."'"),array('EvaluationQuestion.id'=>$new_question_id));
					if(isset($evaluation_option_lists[$question_code])&&!empty($evaluation_option_lists[$question_code])){
						foreach($evaluation_option_lists[$question_code] as $option_data){
							$option_data['id']=0;
							$option_data['evaluation_question_code']=$new_question_code;
							if(isset($option_data['created']))unset($option_data['created']);
							if(isset($option_data['modified']))unset($option_data['modified']);
							$this->EvaluationOption->save($option_data);
						}
					}
				}
				$result['code']='1';
				$result['message']=$new_evaluation_id;
			}
		}
		die(json_encode($result));
    }

    public function ajax_upload_media(){
        $this->checkSessionUser();
         $this->layout = 'ajax';
        Configure::write('debug', 1);
        
        if(isset($_POST['org_id'])&&$_POST['org_id']!=''){
            $img_root = 'media/evaluations/'.$_POST['org_id'].'/';
            $imgaddr = WWW_ROOT.'media/evaluations/'.$_POST['org_id'].'/';
        }
        if(isset($_POST['org_code'])&&$_POST['org_code']!=''){
            $org_code = $_POST['org_code'];
        }
        //pr($_POST['org_code']);exit();
        $this->mkdirs($imgaddr);
        @chmod($imgaddr, 0777);
        $result['code'] = '0';
        $result['error'] = '文件不存在';
        $error = '';
        //pr($_POST['org_code']);exit();
        if ($this->RequestHandler->isPost()) {
        	//pr($POST);exit();
        	//pr($_POST['org_code']);exit();
            if (isset($_FILES[$org_code])) {
            	//pr($_POST['org_code']);exit();
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
                    //pr($result);exit();
            	}
            $result['error'] = $error;
        }//pr($result);exit();
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

    public function import_evaluation(){
    	Configure::write('debug',1);
        $this->layout = 'ajax';
        $result = array();
        $result['code'] = 0;
        if ($this->RequestHandler->isPost()) {
        	$relation_evaluation=$this->OrganizationRelation->find('first',array('conditions'=>array('organization_id'=>$_POST['organizations_id'],'type'=>'evaluation','type_id'=>$_POST['id'])));
        	if(empty($relation_evaluation)){
	        	$add_evaluation['OrganizationRelation'] = array(
	        		'id'=>0,
	        		'organization_id'=>$_POST['organizations_id'],
	        		'type'=>'evaluation',
	        		'type_id'=>$_POST['id']
	        		);
	        	$this->OrganizationRelation->save($add_evaluation);
        	}
        	$result['code'] = 1;
        }
        die(json_encode($result));
    }

    function get_activity_type_id(){
	Configure::write('debug', 1);
	$this->layout = 'ajax';
    	if(isset($_GET['type'])&&$_GET['type'] == 'organization'){
    	$organizations_id = isset($_GET['organizations_id'])?$_GET['organizations_id']:'';
		$this->set('organizations_id', $organizations_id);
	    $organizations_name = $this->Organization->find('first',array('conditions'=>array('Organization.id'=>$organizations_id)));
	    $this->set('organizations_name', $organizations_name);
		$user_member_list = $this->OrganizationMember->find('list',array('fields'=>'OrganizationMember.id','conditions'=>array('OrganizationMember.user_id'=>$_SESSION['User']['User']['id'])));
        // $user_manage_list = $this->OrganizationDepartment->find('list',array('fields'=>'OrganizationDepartment.id','conditions'=>array('OrganizationDepartment.department_manage'=>$_SESSION['User']['User']['id'])));
        $my_jobs = $this->OrganizationMemberJob->find('list',array('fields'=>'OrganizationMemberJob.organization_department_id','conditions'=>array('OrganizationMemberJob.organization_member_id'=>$user_member_list)));
        $my_jobs = array_unique($my_jobs);
        $user_manage_list = $this->OrganizationDepartment->find('list',array('fields'=>'OrganizationDepartment.id','conditions'=>array('OrganizationDepartment.id'=>$my_jobs)));
        $user_organization_list = $this->Organization->find('list',array('fields'=>'Organization.id','conditions'=>array('Organization.manage_user'=>$_SESSION['User']['User']['id'])));
        $organization_share_conditions = array('OrganizationShare.share_type'=>'evaluation');
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
    	// if(sizeof($user_organization_list)>0){
	    //     $organization_share_conditions['or'][] = array(
	    //     	'OrganizationShare.share_object'=>2,
	    //     	'OrganizationShare.share_object_ids'=>$user_organization_list
	    //     	);
	    // }
	    
	    if(sizeof($user_organization_list)>0){
	        $organization_share_conditions['or'][] = array(
	        	'OrganizationShare.share_object'=>2,
	        	'OrganizationShare.share_object_ids'=>$organizations_id
	        	);
	    }
	    //pr($organization_share_conditions);
        $evaluation_share=$this->OrganizationShare->find('list',array('fields'=>'OrganizationShare.share_type_id','conditions'=>$organization_share_conditions));
        $evaluation_share = array_unique($evaluation_share);
        
		$organization_relations = $this->OrganizationRelation->find('list',array('fields'=>'type_id','conditions'=>array('OrganizationRelation.organization_id'=>$organizations_id,'OrganizationRelation.type'=>'evaluation')));
		$evaluation_cansee_conditions = array('Evaluation.status'=>1);
		if(!empty($organization_relations))$evaluation_cansee_conditions['or'][]=array('Evaluation.id'=>$organization_relations);
		$evaluation_cansee_conditions['or'][] = array(
        	'Evaluation.visibility'=>0,
        	'Evaluation.id'=>$evaluation_share
        	);
		$evaluation_cansee_conditions['or'][] = array(
        	'Evaluation.visibility'=>0,
        	'Evaluation.user_id'=>$organizations_name['Organization']['manage_user']
        	);
		$evaluation_cansee_conditions['or'][] = array(
        	'Evaluation.visibility'=>1,
        	'Evaluation.user_id'=>$_SESSION['User']['User']['id'],
        	// 'Evaluation.user_id'=>$organizations_name['Organization']['manage_user']
        	);
		if($organizations_name['Organization']['manage_user']==$_SESSION['User']['User']['id']){
			$evaluation_cansee_conditions['or'][] = array(
	        	'Evaluation.visibility'=>2,
	        	'Evaluation.user_id'=>$organizations_name['Organization']['manage_user']
	        	);
		}
		$evaluation_cansee_conditions['or'][] = array(
        	'Evaluation.visibility'=>2,
        	'Evaluation.id'=>$evaluation_share
        	);
		$evaluation_list = $this->Evaluation->find('all', array('order' => 'created desc','conditions'=>$evaluation_cansee_conditions));
		if(!empty($evaluation_list)){
        	foreach($evaluation_list as $kk=>$vv){
	        	$question_count = $this->EvaluationQuestion->find('count',array('conditions'=>array('EvaluationQuestion.evaluation_code'=>$vv["Evaluation"]["code"])));
	        	$rule_list=$this->EvaluationRule->evaluation_rule_list($vv["Evaluation"]["code"]);
	        	if(!empty($rule_list)){
	        		foreach($rule_list as $kkk=>$vvv){
	        			$question_count+=$vvv["EvaluationRule"]["proportion"];
	        		}
	        	}
	        	$evaluation_list[$kk]["Evaluation"]["question_count"]=$question_count;
	        }
        }
        	die(json_encode($evaluation_list));
	    }else{
	    	$user_id = $_SESSION['User']['User']['id'];
    		$user_course_view_list = array();
    		if(isset($user_id)&&$user_id!=''){
    			$user_course_view_list = $this->Evaluation->find('all',array('conditions'=>array('Evaluation.user_id'=>$user_id)));
    		}
    		die(json_encode($user_course_view_list));
	    }
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
        //pr($manager_ids);
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
        //pr($dep_manage);
        $this->set('dep_managers',$dep_manage);
    }
}
