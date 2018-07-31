<?php

/**
 *这是一个名为 StaitcPagesController 的控制器
 *后台首页控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class DepartmentsController extends AppController
{
    public $name = 'Departments';
    public $components = array('RequestHandler','Pagination');
    public $helpers = array('Html','Javascript','Pagination','Ckeditor');
    public $uses = array('Department','DepartmentI18n','OperatorDepartment','OperatorChannelRelation','OperatorChannel','Operator');

    /**
     *显示后台首页.
     */
    public function index($page = 1){
    		$this->operator_privilege('department_view');
		$this->operation_return_url(true);
		$this->menu_path = array('root' => '/hr/', 'sub' => '/departments/');
		$this->navigations[] = array('name' => "系统管理", 'url' => '');
		$this->navigations[] = array('name' => "操作员部门", 'url' => '/departments/');
		$this->set('title_for_layout', "操作员部门" . ' - ' . $this->ld['page'] . ' ' . $page . ' - ' . $this->configs['shop_name']);
		
		$this->Department->set_locale($this->backend_locale);
		
		$condition = array();
		if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
			$condition['and']['or']['Department.name like'] = '%' . $_REQUEST['keyword'] . '%';
			$this->set('keyword', $_REQUEST['keyword']);
		}
		$departments = $this->Department->find('all', array('conditions' => $condition, 'page' => $page,'order' => 'Department.created desc,Department.id desc'));
		$this->set('departments', $departments);
		if(!empty($departments)){
			$department_ids=array();$department_managers=array();
			foreach($departments as $v){
				$department_ids[]=$v['Department']['id'];
				$department_managers=array_merge($department_managers,trim($v['Department']['manager'])!=''?explode(',',$v['Department']['manager']):array());
			}
			$department_operators=$this->OperatorDepartment->find('list',array('fields'=>'id,operator_id,department_id','conditions' => array("OperatorDepartment.department_id" => $department_ids)));
			if(!empty($department_operators)){
				foreach($department_operators as $v)$department_managers=array_merge($v,$department_managers);
			}
			$this->set('department_operators',$department_operators);
			
			$department_managers=array_unique($department_managers);
			if(!empty($department_managers)){
				$operator_data = $this->Operator->find('list', array('conditions' =>array('Operator.status'=>'1','Operator.id'=>$department_managers),'fields'=>'id,name'));
				$this->set('operator_data',$operator_data);
			}
		}
    }
    
    public function view($id = 0){
        if (empty($id)) {
            	$this->operator_privilege('department_add');
        } else {
            	$this->operator_privilege('department_edit');
        }

        $this->operation_return_url(true);
        $this->menu_path = array('root' => '/hr/','sub' => '/departments/');
        $this->set('title_for_layout', $this->ld['edit'].'-操作员部门- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "系统管理",'url' => '');
        $this->navigations[] = array('name' => "操作员部门",'url' => '/departments/');
        $this->navigations[] = array('name' => $this->ld['edit'],'url' => '');
	  
        //渠道
        $operator_source_relation_info = $this->OperatorChannelRelation->find('all',array('conditions'=>array('OperatorChannelRelation.relation_type'=>1,'OperatorChannelRelation.relation_type_id'=>$id)));
        $cons = array();
        if(isset($operator_source_relation_info)&&sizeof($operator_source_relation_info)>0){
            foreach ($operator_source_relation_info as $k => $v) {
                $cons['and']['OperatorChannel.id'][] = $v['OperatorChannelRelation']['operator_channel_id'];
                $relation_info_check[$v['OperatorChannelRelation']['operator_channel_id']] = $v;
            }
            $this->set('relation_info_check',$relation_info_check);
        }
        $op_check=array();
        $operator_source_info = $this->OperatorChannel->find('all',array('conditions'=>$cons));
        foreach ($operator_source_info as $k => $v) {
            $op_check[$v['OperatorChannel']['id']] = $v['OperatorChannel']['name'];
        }
        $this->set('op_check',$op_check);
        $this->set('depart_id',$id);
        if ($this->RequestHandler->isPost()) {
		$form_manager=isset($this->data['Department']['manager'])&&is_array($this->data['Department']['manager'])&&sizeof($this->data['Department']['manager'])>0?$this->data['Department']['manager']:array();
		if(!empty($form_manager))$form_manager=array_merge(array(''),$form_manager,array(''));
		$this->data['Department']['manager']=implode(',',$form_manager);
            $this->data['Department']['status'] = isset($this->data['Department']['status']) ? $this->data['Department']['status'] : 0;
            if (isset($this->data['Department']['id']) && $this->data['Department']['id'] != 0) {
                	$this->Department->save(array('Department' => $this->data['Department'])); //关联保存
            } else {
                	$this->Department->save(array('Department' => $this->data['Department'])); //关联保存
            }
            $department_id=$this->Department->id;
            if(isset($this->data['DepartmentI18n'])&&sizeof($this->data['DepartmentI18n'])>0){
            	foreach($this->data['DepartmentI18n'] as $v){
            		$v['department_id']=$department_id;
            		$this->DepartmentI18n->save($v);
            	}
            }else{
            	$this->DepartmentI18n->deleteAll(array('DepartmentI18n.department_id'=>$department_id));
            }
            if(isset($this->data['OperatorDepartment']['operator_id'])){
			$operator_add_success = array();
			$operator_add_list = $this->data['OperatorDepartment']['operator_id'];
			$operator_departments = $this->OperatorDepartment->find('list',array('conditions' => array("OperatorDepartment.department_id" => $department_id),'fields'=>'operator_id,id'));
			foreach ($operator_add_list as $k => $v) {
				$operator_save = array(
					'id'=>isset($operator_departments[$v])?$operator_departments[$v]:0,
					'department_id'=>$department_id,
					'operator_id'=>$v
				);
				$this->OperatorDepartment->save(array('OperatorDepartment' => $operator_save));
				$operator_add_success[] = $this->OperatorDepartment->id;
			}
			$this->OperatorDepartment->deleteAll(array('OperatorDepartment.department_id' => $department_id,'not'=>array('OperatorDepartment.id'=>$operator_add_success)));
            }else{
                	$this->OperatorDepartment->deleteAll(array('OperatorDepartment.department_id' => $department_id));
            }
            //获取渠道数据
            if(isset($_POST['channel_relation'])){
			$channel_relation = $_POST['channel_relation'];
			if(isset($channel_relation)&&sizeof($channel_relation)>0){
				foreach ($channel_relation as $k => $v) {
					if(isset($relation_info_check[$k])){
						$relation_info_check[$k]['OperatorChannelRelation']['value'] = $v;
						$this->OperatorChannelRelation->save($relation_info_check[$k]);
					}else{
						$relation_info['id'] = 0;
						$relation_info['operator_channel_id'] = $k;
						$relation_info['relation_type'] = 1;
						$relation_info['relation_type_id'] = $department_id;
						$relation_info['value'] = $v;
						$this->OperatorChannelRelation->save($relation_info);
					}
				}
			}
            }
            $this->redirect('index');
        }
        $departments = $this->Department->localeformat($id);
        $operator_departments = $this->OperatorDepartment->find('all',array('conditions' => array("OperatorDepartment.department_id" => $id)));
        $operator_data = $this->Operator->find('list', array('conditions' =>array('Operator.status'=>'1'),'fields'=>'id,name'));
        
        $condition=array('Operator.status'=>'1');
        if(!empty($operator_departments)){
        		$department_operator_ids=array();
        		foreach($operator_departments as $v)$department_operator_ids[]=$v['OperatorDepartment']['operator_id'];
        		$condition['not']['Operator.id']=$department_operator_ids;
        }
        if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
            	$condition['and']['or']['Operator.name like'] = '%' . $_REQUEST['keyword'] . '%';
            	$condition['and']['or']['Operator.mobile like'] = '%' . $_REQUEST['keyword'] . '%';
            	$this->set('keyword', $_REQUEST['keyword']);
        }
        $operator_list=$this->Operator->find('list', array('conditions' =>$condition,'fields'=>'id,name'));
        $this->set('departments', $departments);
        $this->set('departments_id', $id);
        $this->set('operator_departments', $operator_departments);
        $this->set('operator_data', $operator_data);
        $this->set('operator_list', $operator_list);
    }

    public function remove($id){
    		
	        Configure::write('debug', 0);
	        $this->layout = 'ajax'; //避免引入头部信息和尾部信息
	        $result['flag'] = 2;
	        $result['message'] = $this->ld['delete_article_failure'];
	        if (!$this->operator_privilege('department_remove', false)) {
	            	die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
	        }
	        $this->DepartmentI18n->deleteAll(array('DepartmentI18n.department_id'=>$id));
	        $this->Department->deleteAll(array("Department.id"=>$id));
	        $this->OperatorDepartment->deleteAll(array('OperatorDepartment.department_id'=>$id));
	        $this->OperatorChannelRelation->deleteAll(array("OperatorChannelRelation.relation_type_id"=>$id,"OperatorChannelRelation.relation_type"=>1));
	        $result['flag'] = 1;
	        $result['message'] = $this->ld['delete_article_success'];
	        die(json_encode($result));
    }
    
    //批量处理
    public function batch(){
    	  $this->operator_privilege('department_remove');
        $page_ids = !empty($_GET['checkboxes']) ? $_GET['checkboxes'] : 0;
        if (isset($this->params['url']['act_type']) && $this->params['url']['act_type'] != '0') {
            if ($this->params['url']['act_type'] == 'delete') {
                foreach ($page_ids as $k => $v) {
				$this->DepartmentI18n->deleteAll(array('DepartmentI18n.department_id'=>$v));
				$this->Department->deleteAll(array("Department.id"=>$v));
				$this->OperatorDepartment->deleteAll(array('OperatorDepartment.department_id'=>$v));
				$this->OperatorChannelRelation->deleteAll(array("OperatorChannelRelation.relation_type_id"=>$v,"OperatorChannelRelation.relation_type"=>1));
                }
            }
            
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }
    
    public function add_relation_operator(){
		$this->operator_privilege('department_view');
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		//设置返回初始参数
		$result['flag'] = 2;//2 失败 1成功
		$result['content'] = '';
		$departments_id = $_REQUEST['departments_id'];
		$operator_id = $_REQUEST['operator_id'];
		$operator_add = $this->Operator->find('first', array('conditions' => array("Operator.id" => $operator_id)));
		$result['flag'] = 1;//2 失败 1成功
		$result['content'] = $operator_add;
		die(json_encode($result));
    }
}