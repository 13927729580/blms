<?php

/*****************************************************************************
 * Seevia 任务分组管理
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
 *这是一个名为 UserTaskGroupsController 的控制器
 *任务分组管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class UserTaskGroupsController extends AppController
{
    public $name = 'UserTaskGroups';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('UserTaskGroup','UserTaskCondition','AbilityLevel','InformationResource','UserGroupRelation','UserTask','Course','Evaluation');

    /**
     *显示列表.
     */
    public function index($page = 1)
    {
    	 $this->operator_privilege('task_group_view');
        $this->operation_return_url(true);
        $this->menu_path = array('root' => '/hr/', 'sub' => '/user_tasks/');
        $this->navigations[] = array('name' => "在线学习", 'url' => '');
        $this->navigations[] = array('name' => "任务管理", 'url' => '/user_tasks/');
        $this->navigations[] = array('name' => "任务分组管理", 'url' => '/user_task_groups/');
        $condition = '';
        $status="-1";
        if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
            $condition['or']['UserTaskGroup.name like'] = '%' . $_REQUEST['keyword'] . '%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        if (isset($this->params['url']['start_date_time']) && $this->params['url']['start_date_time'] != '') {
            $condition['and']['UserTaskGroup.start_time >='] = $this->params['url']['start_date_time'].' 00:00:00';
            $start_date_time = $this->params['url']['start_date_time'];
            $this->set('start_date_time', $start_date_time);
        }
        if (isset($this->params['url']['end_date_time']) && $this->params['url']['end_date_time'] != '') {
            $condition['and']['UserTaskGroup.end_time <='] = $this->params['url']['end_date_time'].' 23:59:59';
            $end_date_time = $this->params['url']['end_date_time'];
            $this->set('end_date_time', $end_date_time);
        }
        if (isset($this->params['url']['status']) && $this->params['url']['status'] != '-1') {
            $condition['and']['UserTaskGroup.status'] = $this->params['url']['status'];
            $status = $this->params['url']['status'];
        }
        $total = $this->UserTaskGroup->find('count', array('conditions' => $condition));
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int)$this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'user_task_groups', 'action' => 'index', 'page' => $page, 'limit' => $rownum);
        $options = array('page' => $page, 'show' => $rownum, 'total' => $total, 'modelClass' => 'UserTaskGroup');
        $this->Pagination->init($condition, $parameters, $options);
        $task_group = $this->UserTaskGroup->find('all', array('conditions' => $condition, 'page' => $page,'limit' => $rownum,'order' => 'created desc,id desc'));
        if(!empty($task_group)){
        	$result=array();
        	foreach($task_group as $k=>$v){
        		$task_condition=$this->UserTaskCondition->find('all',array('conditions'=>array('UserTaskCondition.task_group_id'=>$v['UserTaskGroup']['id'])));
		        if(!empty($task_condition)){
		        	foreach($task_condition as $kk=>$vv){
					    $result[$vv['UserTaskCondition']['params']]=$vv;
					}
        			$task_group[$k]["task_condition"]=$result;
		        }
        	}
        }
        $condition_resource=$this->InformationResource->information_formated('task_condition',$this->backend_locale,false);
        $this->set('condition_resource', $condition_resource['task_condition']);
        $this->set('task_group', $task_group);
        $this->set('status', $status);
        $this->set('title_for_layout', "任务分组管理" . ' - ' . $this->ld['page'] . ' ' . $page . ' - ' . $this->configs['shop_name']);
    }

    /**
     *添加任务
     */
    public function add()
    {
    	 $this->operator_privilege('task_group_add');
        $this->menu_path = array('root' => '/hr/','sub' => '/user_tasks/');
        $this->set('title_for_layout', $this->ld['add'].'-任务管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "任务管理",'url' => '/user_tasks/');
        $this->navigations[] = array('name' => "任务分组管理", 'url' => '/user_task_groups/');
        $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        if ($this->RequestHandler->isPost()) {
            $this->UserTaskGroup->save($this->data);
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
    }

    /**
     *编辑任务
     */
    public function view($id)
    {
    	 $this->operator_privilege('task_group_edit');
        $this->operation_return_url(true);
        $this->menu_path = array('root' => '/hr/','sub' => '/user_tasks/');
        $this->set('title_for_layout', $this->ld['edit'].'-任务管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "任务管理",'url' => '/user_tasks/');
        $this->navigations[] = array('name' => "任务分组管理", 'url' => '/user_task_groups/');
        $this->navigations[] = array('name' => $this->ld['edit'],'url' => '');
        $task_condition=$this->UserTaskCondition->find('all',array('conditions'=>array('UserTaskCondition.task_group_id'=>$id)));
        $task_group=$this->UserTaskGroup->find('first',array('conditions'=>array('UserTaskGroup.id'=>$id)));
        $condition_code=$this->UserTaskCondition->find('list',array('fields'=>'UserTaskCondition.params','conditions'=>array('UserTaskCondition.task_group_id'=>$id)));
        $condition_resource=$this->InformationResource->information_formated('task_condition',$this->backend_locale,false);
        $task_resource=$this->InformationResource->information_formated('task_type',$this->backend_locale,false);
        $type_list=$this->UserGroupRelation->find('all',array("conditions"=>array('UserGroupRelation.user_task_group_id'=>$id)));
        foreach($type_list as $kk=>$vv){
        	if($vv["UserGroupRelation"]["type"]=="task"){
        		$task=$this->UserTask->find('first',array('conditions'=>array('UserTask.id'=>$vv["UserGroupRelation"]["type_id"])));
        		$type_list[$kk]["name"]=$task['UserTask']['name'];
        	}elseif($vv["UserGroupRelation"]["type"]=="course"){
        		$task=$this->Course->find('first',array('conditions'=>array('Course.id'=>$vv["UserGroupRelation"]["type_id"])));
        		$type_list[$kk]["name"]=$task['Course']['name'];
        	}elseif($vv["UserGroupRelation"]["type"]=="evaluation"){
        		$task=$this->Evaluation->find('first',array('conditions'=>array('Evaluation.id'=>$vv["UserGroupRelation"]["type_id"])));
        		$type_list[$kk]["name"]=$task['Evaluation']['name'];
        	}
        }
        if ($this->RequestHandler->isPost()) {
            $this->UserTaskGroup->save($this->data);
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $level_list=$this->AbilityLevel->find('all',array('conditions'=>array('AbilityLevel.status'=>1)));
        if(!empty($level_list)){
        	foreach($level_list as $k=>$v){
		    $result[$v['Ability']['code']][]=$v;
		}
        }
        $this->set('level_list', $result);
        $this->set('condition_resource', $condition_resource['task_condition']);
        $this->set('task_resource', $task_resource['task_type']);
        $this->set('condition_code', $condition_code);
        $this->set('task_group', $task_group);
        $this->set('type_list', $type_list);
        $this->set('task_condition', $task_condition);
    }

    /**
     * 删除任务
     *
     *@param int $id
     */
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        if($this->operator_privilege('task_group_remove',false)){
	        $this->UserTaskGroup->deleteAll(array('id' => $id));
	        $this->UserGroupRelation->deleteAll(array('UserGroupRelation.user_task_group_id' => $id));
	        //操作员日志
	        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
	            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id, $this->admin['id']);
	        }
	        $result['flag'] = 1;
	        $result['message'] = $this->ld['delete_member_success'];
        }
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/user_task_groups/');
        }
    }
    
    /**
     * 删除关系
     *
     *@param int $id
     */
    public function rel_remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        if($this->operator_privilege('task_group_add',false)||$this->operator_privilege('task_group_edit',false)){
	        $this->UserGroupRelation->deleteAll(array('UserGroupRelation.id' => $id));
	        //操作员日志
	        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
	            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id, $this->admin['id']);
	        }
	        $result['flag'] = 1;
	        $result['message'] = $this->ld['delete_member_success'];
        }
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/user_task_groups/');
        }
    }
    
    /**
     * 关联项目
     *
     */
    public function set_group($id)
    {
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		if($this->operator_privilege('task_group_add',false)||$this->operator_privilege('task_group_edit',false)){
			$type=$_POST['type'];
			$type_id=$_POST['type_id'];
			$rel['UserGroupRelation']['user_task_group_id']=$id;
			foreach($type as $kk=>$vv){
				if(!empty($vv!="" && $type_id[$kk]!="")){
					$rel['UserGroupRelation']['type_id']=$type_id[$kk];
					$rel['UserGroupRelation']['type']=$vv;
					$this->UserGroupRelation->saveAll(array('UserGroupRelation' => $rel["UserGroupRelation"]));
				}
			}
		}
		$back_url = $this->operation_return_url();//获取操作返回页面地址
		$this->redirect($back_url);
    }
}