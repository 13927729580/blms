<?php

/*****************************************************************************
 * Seevia 任务记录管理
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
 *这是一个名为 UserTaskLogsController 的控制器
 *任务记录管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class UserTaskLogsController extends AppController
{
    public $name = 'UserTaskLogs';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('User','InformationResource','UserTaskLog','UserTaskGroup','UserTask','UserGroupRelation');

    /**
     *显示列表.
     */
    public function index($page = 1)
    {
    	 $this->operator_privilege('user_task_log');
        $this->operation_return_url(true);
        $this->menu_path = array('root' => '/hr/', 'sub' => '/user_tasks/');
        $this->navigations[] = array('name' => "在线学习", 'url' => '');
        $this->navigations[] = array('name' => "会员任务记录", 'url' => '/user_task_logs/');
        $condition = '';
        $option_type_code="-1";
        $start_date='';
        $end_date='';
        $type="-1";
        if (isset($this->params['url']['user_name']) && $this->params['url']['user_name'] != '') {
            $user_condition['or']['User.name like'] = '%'.$_REQUEST['user_name'] .'%';
            $user_ids=$this->User->find('list',array('fields'=>"User.id","conditions"=>$user_condition));
            $condition['and']['UserTaskLog.user_id'] = $user_ids;
            $this->set('user_name', $_REQUEST['user_name']);
        }
        if (isset($this->params['url']['option_type_code']) && $this->params['url']['option_type_code'] != '-1') {
            $group_condition['and']['UserGroupRelation.user_task_group_id'] = $this->params['url']['option_type_code'];
            $rel_ids=$this->UserGroupRelation->find('list',array('fields'=>"UserGroupRelation.id","conditions"=>$group_condition));
            $condition['and']['UserTaskLog.user_group_relation_id'] = $rel_ids;
            $option_type_code = $this->params['url']['option_type_code'];
        }
        if (isset($this->params['url']['type']) && $this->params['url']['type'] != '-1') {
            $type_condition['and']['UserGroupRelation.type'] = $this->params['url']['type'];
            $rel_ids=$this->UserGroupRelation->find('list',array('fields'=>"UserGroupRelation.id","conditions"=>$type_condition));
            $condition['and']['UserTaskLog.user_group_relation_id'] = $rel_ids;
            $type = $this->params['url']['type'];
        }
        if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
            $condition['and']['UserTaskLog.remark like'] = '%' . $_REQUEST['keyword'] . '%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        if (isset($this->params['url']['start_date']) && $this->params['url']['start_date'] != '') {
            $condition['and']['UserTaskLog.created >='] = $this->params['url']['start_date'].' 00:00:00';
            $start_date = $this->params['url']['start_date'];
        }
        if (isset($this->params['url']['end_date']) && $this->params['url']['end_date'] != '') {
            $condition['and']['UserTaskLog.created <='] = $this->params['url']['end_date'].' 23:59:59';
            $end_date = $this->params['url']['end_date'];
            $this->set('end_date', $end_date);
        }
        $total = $this->UserTaskLog->find('count', array('conditions' => $condition));
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int)$this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'user_task_logs', 'action' => 'index', 'page' => $page, 'limit' => $rownum);
        $options = array('page' => $page, 'show' => $rownum, 'total' => $total, 'modelClass' => 'UserTaskLog');
        $this->Pagination->init($condition, $parameters, $options);
        $task_log = $this->UserTaskLog->find('all', array('conditions' => $condition, 'page' => $page,'limit' => $rownum,'order' => 'created desc,id desc'));
        if($task_log){
            foreach($task_log as $k=>$v){
                $group_name=$this->UserGroupRelation->find('first', array('conditions' => array("UserGroupRelation.id"=>$v['UserTaskLog']['user_group_relation_id'])));
                $task_log[$k]['group_name']=$group_name['UserTaskGroup']['name'];
                $task_log[$k]['type']=$group_name['UserGroupRelation']['type'];
            }
        }
        $task_resource=$this->InformationResource->information_formated('task_type',$this->backend_locale,false);
        $task_group=$this->UserTaskGroup->find('all', array('conditions' =>array("UserTaskGroup.status"=>1)));
        $this->set('task_resource', $task_resource['task_type']);
        $this->set('task_group', $task_group);
        $this->set('option_type_code',$option_type_code);
        $this->set('type',$type);
        $this->set('task_log', $task_log);
        $this->set('start_date', $start_date);
        $this->set('end_date', $end_date);
        $this->set('title_for_layout', "会员任务记录" . ' - ' . $this->ld['page'] . ' ' . $page . ' - ' . $this->configs['shop_name']);
    }
}