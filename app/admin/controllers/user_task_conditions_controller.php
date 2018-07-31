<?php

/*****************************************************************************
 * Seevia 任务分组前置条件管理
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
 *这是一个名为 UserTaskConditionsController 的控制器
 *任务分组前置条件管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class UserTaskConditionsController extends AppController
{
    public $name = 'UserTaskConditions';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('UserTaskCondition','InformationResource','AbilityLevel');

    /**
     *添加前置条件
     */
    public function add($code)
    {
        $this->menu_path = array('root' => '/hr/','sub' => '/user_tasks/');
        $this->set('title_for_layout', $this->ld['add'].'-任务管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "任务管理",'url' => '/user_tasks/');
        $this->navigations[] = array('name' => "任务分组管理", 'url' => '/user_task_groups/');
        $this->navigations[] = array('name' => $this->ld['add']."前置条件",'url' => '');
        $level_list=$this->AbilityLevel->find('all',array('conditions'=>array('AbilityLevel.status'=>1)));
        $condition_code=$this->UserTaskCondition->find('list',array('fields'=>'UserTaskCondition.params','conditions'=>array('UserTaskCondition.task_group_id'=>$code)));
        $condition_resource=$this->InformationResource->information_formated('task_condition',$this->backend_locale,false);
        if ($this->RequestHandler->isPost()) {
        	if(is_array($this->data["UserTaskCondition"]["value"]) && $this->data["UserTaskCondition"]["params"]=="ability_level"){
	        	$this->data["UserTaskCondition"]["value"]=implode(",",$this->data["UserTaskCondition"]["value"]);
	        }
	        $this->data["UserTaskCondition"]["task_group_id"]=$code;
            $this->UserTaskCondition->save($this->data);
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->set('level_list', $level_list);
        $this->set('condition_code', $condition_code);
        $this->set('condition_resource', $condition_resource['task_condition']);
        $this->set('code', $code);
    }

    /**
     *编辑前置条件
     */
    public function view($id)
    {
        $this->menu_path = array('root' => '/hr/','sub' => '/user_tasks/');
        $this->set('title_for_layout', $this->ld['edit'].'-任务管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "任务管理",'url' => '/user_tasks/');
        $this->navigations[] = array('name' => "任务分组管理", 'url' => '/user_task_groups/');
        $this->navigations[] = array('name' => $this->ld['edit']."前置条件",'url' => '');
        $level_list=$this->AbilityLevel->find('all',array('conditions'=>array('AbilityLevel.status'=>1)));
        if(!empty($level_list)){
		foreach($level_list as $k=>$v){
			$result[$v['Ability']['code']][]=$v;
		}
        }
        $condition_array=array();
        $condition_resource=$this->InformationResource->information_formated('task_condition',$this->backend_locale,false);
        $task_group=$this->UserTaskCondition->find('first',array('conditions'=>array('UserTaskCondition.id'=>$id)));
        if($task_group['UserTaskCondition']["params"]=="ability_level"){
            $condition_array=explode(",",$task_group['UserTaskCondition']['value']);
            $this->set('condition_array', $condition_array);
        }
        if ($this->RequestHandler->isPost()) {
            if(is_array($this->data["UserTaskCondition"]["value"]) && $this->data["UserTaskCondition"]["params"]=="ability_level"){
	        	$this->data["UserTaskCondition"]["value"]=implode(",",$this->data["UserTaskCondition"]["value"]);
	        }
            $this->UserTaskCondition->save($this->data);
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->set('level_list', $result);
        $this->set('condition_resource', $condition_resource['task_condition']);
        $this->set('task_group', $task_group);
    }

    /**
     * 删除前置条件
     *
     *@param int $id
     */
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        $this->UserTaskCondition->deleteAll(array('id' => $id));;
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_member_success'];
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/user_task_groups/');
        }
    }
    
    function ajax_modify(){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result=array();
		$result['code']='0';
		$result['message']=$this->ld['modify_failed'];
		$condition_params=isset($this->data['UserTaskCondition']['params'])?$this->data['UserTaskCondition']['params']:'';
		if($condition_params!=''){
			if(isset($this->data["UserTaskCondition"][$condition_params])){
				$this->data["UserTaskCondition"]["value"]=$this->data["UserTaskCondition"][$condition_params];
			}
			if(is_array($this->data["UserTaskCondition"]["value"]) && $this->data["UserTaskCondition"]["params"]=="ability_level"){
				$this->data["UserTaskCondition"]["value"]=implode(",",$this->data["UserTaskCondition"]["value"]);
			}
			$this->UserTaskCondition->save($this->data);
		}
		$result['code']='1';
		$result['message']='添加成功';
		die(json_encode($result));
    }
}