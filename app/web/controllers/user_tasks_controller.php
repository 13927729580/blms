<?php

/*****************************************************************************
 * UserWork 用户作品
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为UserWorksController的控制器
 *用户简历
 *
 *@var
 *@var
 *@var
 *@var
 */
class UserTasksController extends AppController
{
	public $name = 'UserTasks';
	public $helpers = array('Html','Pagination');
	public $uses = array('User','UserFans','Blog','UserTask','UserTaskGroup','UserTaskCondition','UserGroupRelation','UserTaskLog','UserAbility','Ability','AbilityLevel');
	public $components = array('RequestHandler','Pagination');
	    
	/**
	*	课程分类列表
	*/
	public function index($page=1,$limit=10){
		//登录验证
        	$this->checkSessionUser();
		$_GET=$this->clean_xss($_GET);
		$this->layout = 'usercenter';//引入模版
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
			Configure::write('debug', 0);
			$this->layout = 'ajax';
		}
		$this->page_init();                        //页面初始化
        	$this->pageTitle = '我的任务 - '.$this->configs['shop_title'];
		//当前位置开始
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => '我的任务', 'url' => '');
		
		$user_id=$_SESSION['User']['User']['id'];
		//获取我的信息
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
		if ($user_list['User']['rank'] > 0) {
			$rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
			$user_list['User']['rank_name'] = isset($rank_list['UserRankI18n']['name'])?$rank_list['UserRankI18n']['name']:'';
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
		
		$ability_level_infos=$this->UserAbility->find('all',array('conditions'=>array('UserAbility.user_id'=>$user_id,'UserAbility.status'=>'1')));
		$this->set('ability_level_infos',$ability_level_infos);
		
		$ability_level_ids=array();
		$ability_level_ids[]=0;
		foreach($ability_level_infos as $v)$ability_level_ids[]=$v['UserAbility']['ability_level_id'];
		$ability_level_ids=array_unique($ability_level_ids);
		$conditions=array();
		$conditions['UserTaskGroup.status']='1';
		$conditions['UserTaskGroup.start_time <=']=date('Y-m-d 00:00:00');
		$conditions['UserTaskGroup.end_time >=']=date('Y-m-d 23:59:59');
		$conditions['UserGroupRelation.type <>']='';
		$conditions['UserGroupRelation.type_id >']=0;
		if(!empty($ability_level_ids)){
			foreach($ability_level_ids as $v){
				$conditions['or'][]=array(
					'UserTaskCondition.params'=>'ability_level',
					'UserTaskCondition.value like'=>"{$v},%"
				);
				$conditions['or'][]=array(
					'UserTaskCondition.params'=>'ability_level',
					'UserTaskCondition.value like'=>"%,{$v},%"
				);
				$conditions['or'][]=array(
					'UserTaskCondition.params'=>'ability_level',
					'UserTaskCondition.value like'=>"%,{$v}"
				);
			}
		}
		$user_task_condition_info=$this->UserTaskCondition->find('all',array('fields'=>'UserGroupRelation.type,UserGroupRelation.type_id,UserTaskGroup.id,UserTaskGroup.name','conditions'=>$conditions));
		$user_task_ids=array();
		if(!empty($user_task_condition_info)){
			foreach($user_task_condition_info as $v){
				if($v['UserGroupRelation']['type']=='task')$user_task_ids[]=$v['UserGroupRelation']['type_id'];
			}
		}
		//获取已完成的任务编号
		$completed_task_ids=$this->UserTaskLog->task_log_infos($user_id);
		$conditions=array();
		if(!empty($completed_task_ids)){
			$conditions['not']['UserTask.id']=$completed_task_ids;
		}
		if(!empty($user_task_ids)){
			$conditions['and']['UserTask.id']=$user_task_ids;
		}else{
			$conditions['and']['UserTask.id']=0;
		}
		$conditions['and']['UserTask.status']='1';
		$parameters=array();
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'user_tasks', 'action' => 'index', 'page' => $page, 'limit' => $limit);
		//分页参数
		$options = array('page' => $page, 'show' => $limit, 'modelClass' => 'UserTask');
		$this->Pagination->init($conditions, $parameters, $options); // Added
		$UserTask_lists=$this->UserTask->find('all',array('conditions'=>$conditions,'order'=>'UserTask.modified','page'=>$page,'limit'=>$limit));
		$this->set('UserTask_lists',$UserTask_lists);
	}
	
	function view($id=0){
		//登录验证
        	$this->checkSessionUser();
		$_GET=$this->clean_xss($_GET);
		$this->layout = 'usercenter';//引入模版
		$this->page_init();                        //页面初始化
        	$this->pageTitle = '我的任务 - '.$this->configs['shop_title'];
		//当前位置开始
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => '我的任务', 'url' => '');
		
		$user_id=$_SESSION['User']['User']['id'];
		//获取我的信息
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
		if ($user_list['User']['rank'] > 0) {
			$rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
			$user_list['User']['rank_name'] = isset($rank_list['UserRankI18n']['name'])?$rank_list['UserRankI18n']['name']:'';
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
		
		$ability_level_ids=$this->UserAbility->find('list',array('fields'=>'UserAbility.id,UserAbility.ability_level_id','conditions'=>array('UserAbility.user_id'=>$user_id,'UserAbility.status'=>'1')));
		$ability_level_ids[]=0;
		$ability_level_ids=array_unique($ability_level_ids);
		
		$conditions=array();
		$conditions['UserTaskGroup.status']='1';
		$conditions['UserTaskGroup.start_time <=']=date('Y-m-d 00:00:00');
		$conditions['UserTaskGroup.end_time >=']=date('Y-m-d 23:59:59');
		$conditions['UserGroupRelation.type <>']='';
		$conditions['UserGroupRelation.type_id >']=0;
		if(!empty($ability_level_ids)){
			foreach($ability_level_ids as $v){
				$conditions['or'][]=array(
					'UserTaskCondition.params'=>'ability_level',
					'UserTaskCondition.value like'=>"{$v},%"
				);
				$conditions['or'][]=array(
					'UserTaskCondition.params'=>'ability_level',
					'UserTaskCondition.value like'=>"%,{$v},%"
				);
				$conditions['or'][]=array(
					'UserTaskCondition.params'=>'ability_level',
					'UserTaskCondition.value like'=>"%,{$v}"
				);
			}
		}
		$user_task_condition_info=$this->UserTaskCondition->find('all',array('fields'=>'UserGroupRelation.type,UserGroupRelation.type_id,UserTaskGroup.id,UserTaskGroup.name','conditions'=>$conditions));
		$conditions=array();
		if(!empty($user_task_condition_info)){
			$conditions['UserTask.id']=$id;
		}else{
			$conditions['UserTask.id']=0;
		}
		$conditions['UserTask.status']='1';
		$UserTask_lnfo=$this->UserTask->find('first',array('conditions'=>$conditions));
		$this->set('UserTask_lnfo',$UserTask_lnfo);
		
		if(empty($UserTask_lnfo))$this->redirect('index');
		
		//获取已完成的任务编号
		$completed_task_ids=$this->UserTaskLog->task_log_infos($user_id);
		$this->set('task_complete',in_array($id,$completed_task_ids));
	}
	
	function ajax_task_list(){
		//登录验证
        	$this->checkSessionUser();
        	if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
	        	Configure::write('debug', 0);
			$this->layout = 'ajax';
	        	
	        	//获取我的信息
	        	$user_id=$_SESSION['User']['User']['id'];
	        	$user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
	        	
$ability_level_ids=$this->UserAbility->find('list',array('fields'=>'UserAbility.id,UserAbility.ability_level_id','conditions'=>array('UserAbility.user_id'=>$user_id,'UserAbility.status'=>'1')));
			$ability_level_ids[]=0;
	        	$user_level_code=isset($user_level_data['UserLevel']['code'])?$user_level_data['UserLevel']['code']:'';
	        	$task_cond=array();
			$task_cond['UserTask.status']='1';
			$task_cond['UserTask.task_url <>']='';
			$task_cond['UserTask.ability_level_id']=$ability_level_ids;
			$completed_task_ids=$this->UserTaskLog->task_log_infos($user_id);
			if(!empty($completed_task_ids)){
				$task_cond['not']['UserTask.id']=$completed_task_ids;
			}
			$user_task_list=$this->UserTask->find('all',array('conditions'=>$task_cond,'order'=>'UserTask.task_type'));
			$this->set('user_task_list',$user_task_list);
		}else{
			die();
		}
	}
}
