<?php

/*****************************************************************************
 * Seevia 任务管理
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
 *这是一个名为 UserTasksController 的控制器
 *任务管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class UserTasksController extends AppController
{
    public $name = 'UserTasks';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('UserTask','UserTaskCondition','UserTaskGroup','UserGroupRelation');

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
        $condition = '';
        $option_type_code="-1";
        $status="-1";
        if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
            $condition['or']['UserTask.name like'] = '%' . $_REQUEST['keyword'] . '%';
            $condition['or']['UserTask.description like'] = '%' . $_REQUEST['keyword'] . '%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        if (isset($this->params['url']['status']) && $this->params['url']['status'] != '-1') {
            $condition['and']['UserTask.status'] = $this->params['url']['status'];
            $status = $this->params['url']['status'];
        }
        if (isset($this->params['url']['option_type_code']) && $this->params['url']['option_type_code'] != '-1') {
            $group_condition['and']['UserGroupRelation.type'] = "task";
            $group_condition['and']['UserGroupRelation.user_task_group_id'] = $this->params['url']['option_type_code'];
            $task_ids=$this->UserGroupRelation->find('list',array('fields'=>"UserGroupRelation.type_id","conditions"=>$group_condition));
            $condition['and']['UserTask.id'] = $task_ids;
            $option_type_code = $this->params['url']['option_type_code'];
        }
        if (isset($this->params['url']['start_date_time']) && $this->params['url']['start_date_time'] != '') {
            $condition['and']['UserTask.modified >='] = $this->params['url']['start_date_time'].' 00:00:00';
            $start_date_time = $this->params['url']['start_date_time'];
            $this->set('start_date_time', $start_date_time);
        }
        if (isset($this->params['url']['end_date_time']) && $this->params['url']['end_date_time'] != '') {
            $condition['and']['UserTask.modified <='] = $this->params['url']['end_date_time'].' 23:59:59';
            $end_date_time = $this->params['url']['end_date_time'];
            $this->set('end_date_time', $end_date_time);
        }
        $total = $this->UserTask->find('count', array('conditions' => $condition));
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int)$this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'user_tasks', 'action' => 'index', 'page' => $page, 'limit' => $rownum);
        $options = array('page' => $page, 'show' => $rownum, 'total' => $total, 'modelClass' => 'UserTask');
        $this->Pagination->init($condition, $parameters, $options);
        $task_list = $this->UserTask->find('all', array('conditions' => $condition, 'page' => $page,'limit' => $rownum,'order' => 'created desc,id desc'));
        if(!empty($task_list)){
        	foreach($task_list as $k=>$v){
	        	$group = $this->UserGroupRelation->find('all',array('conditions'=>array('UserGroupRelation.type_id'=>$v["UserTask"]["id"],'UserGroupRelation.type'=>'task')));
	        	$task_list[$k]["UserTask"]["group_name"]="";
	        	if(!empty($group)){
		        	foreach($group as $kk=>$vv){
		        		$group_name = $this->UserTaskGroup->find('first',array('conditions'=>array('UserTaskGroup.id'=>$vv["UserGroupRelation"]["user_task_group_id"])));
		        		if($task_list[$k]["UserTask"]["group_name"]!=""){
		        			$task_list[$k]["UserTask"]["group_name"].="，";
		        		}
		        		$task_list[$k]["UserTask"]["group_name"].=isset($group_name["UserTaskGroup"]["name"])?$group_name["UserTaskGroup"]["name"]:"";
		        	}
	        	}
	        }
        }
        $task_group=$this->UserTaskGroup->find('all', array('conditions' =>array("UserTaskGroup.status"=>1)));
        $this->set('status', $status);
        $this->set('task_group', $task_group);
        $this->set('task_list', $task_list);
        $this->set('option_type_code', $option_type_code);
        $this->set('title_for_layout', "任务管理" . ' - ' . $this->ld['page'] . ' ' . $page . ' - ' . $this->configs['shop_name']);
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
        $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        $task_group=$this->UserTaskGroup->find('all', array('conditions' =>array("UserTaskGroup.status"=>1)));
        if ($this->RequestHandler->isPost()) {
            $this->UserTask->save($this->data);
            $task_id=$this->UserTask->getLastInsertId();
            if(!empty($this->data['UserTaskGroup']['id'])){
        		$rel['UserGroupRelation']['type_id']=$task_id;
        		$rel['UserGroupRelation']['type']="task";
        		foreach($this->data['UserTaskGroup']['id'] as $kk=>$vv){
        			$rel['UserGroupRelation']['user_task_group_id']=$vv;
        			$this->UserGroupRelation->saveAll(array('UserGroupRelation' => $rel["UserGroupRelation"]));
        		}
        	}
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->set('task_group', $task_group);
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
        $this->navigations[] = array('name' => $this->ld['edit'],'url' => '');
        $task_group=$this->UserTaskGroup->find('all', array('conditions' =>array("UserTaskGroup.status"=>1)));
        $task_group_info=$this->UserGroupRelation->find('list', array("fields"=>"UserGroupRelation.id,UserGroupRelation.user_task_group_id",'conditions' =>array("UserGroupRelation.type_id"=>$id,'UserGroupRelation.type'=>'task')));
        $task_info=$this->UserTask->find('first',array('conditions'=>array('UserTask.id'=>$id)));
        if ($this->RequestHandler->isPost()) {
            $this->UserTask->save($this->data);
            if(!empty($this->data['UserTaskGroup']['id'])){
            	$this->UserGroupRelation->deleteAll(array('UserGroupRelation.type_id' => $id,'UserGroupRelation.type'=>'task'));
        		$rel['UserGroupRelation']['type_id']=$id;
        		$rel['UserGroupRelation']['type']="task";
        		foreach($this->data['UserTaskGroup']['id'] as $kk=>$vv){
        			$rel['UserGroupRelation']['user_task_group_id']=$vv;
        			$this->UserGroupRelation->saveAll(array('UserGroupRelation' => $rel["UserGroupRelation"]));
        		}
        	}
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->set('task_info', $task_info);
        $this->set('task_group', $task_group);
        $this->set('task_group_info', $task_group_info);
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
	        $task_info = $this->UserTask->findById($id);
	        $this->UserTask->deleteAll(array('id' => $id));;
	        //操作员日志
	        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
	            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id.' '.$task_info['UserTask']['code'], $this->admin['id']);
	        }
	        $result['flag'] = 1;
	        $result['message'] = $this->ld['delete_member_success'];
        }
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/user_tasks/');
        }
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
            $task_count = $this->UserTask->find('count', array('conditions' => array('UserTask.code' => $code, 'UserTask.status' => "1")));
            if ($task_count == 0) {
                $result['code'] = 1;
            } else {
                $result['msg'] = "code已存在";
            }
            die(json_encode($result));
        } else {
            $this->redirect('/user_tasks');
        }
    }
}