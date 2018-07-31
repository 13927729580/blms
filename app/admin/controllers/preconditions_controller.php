<?php

/*****************************************************************************
 * Seevia 前置条件管理
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
 *这是一个名为 PreconditionsController 的控制器
 *前置条件管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class PreconditionsController extends AppController
{
    public $name = 'Preconditions';
    public $components = array('Pagination', 'RequestHandler');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript');
    public $uses = array('Precondition','InformationResource','AbilityLevel','Evaluation','Course');

    /**
     *添加前置条件
     */
    public function add($object_type='',$object_code=''){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		
		if ($this->RequestHandler->isPost()){
			$result=array();
			$result['code']='0';
			$result['message']=$this->ld['operation_failed'];
			if(isset($this->data['Precondition'])&&!empty($this->data)){
				$conditions=array();
				$conditions['Precondition.object']=$this->data['Precondition']['object'];
				$conditions['Precondition.object_code']=$this->data['Precondition']['object_code'];
				$conditions['Precondition.params']=$this->data['Precondition']['params'];
				$Precondition_data=$this->Precondition->find('first',array('conditions'=>$conditions));
				if(!empty($Precondition_data))$this->data['Precondition']['id']=$Precondition_data['Precondition']['id'];
				if(isset($this->data['Precondition']['value'])&&is_array($this->data['Precondition']['value']))$this->data['Precondition']['value']=implode(',',$this->data['Precondition']['value']);
				$this->Precondition->save($this->data['Precondition']);
				$result['code']='1';
				$result['message']=$this->ld['add_successful'];
			}
			die(json_encode($result));
		}
		$this->set('object_type',$object_type);
		$this->set('object_code',$object_code);
		
		$ability_level_list=$this->AbilityLevel->find('all',array('conditions'=>array('AbilityLevel.status'=>1)));
		$this->set('ability_level_list',$ability_level_list);
		
		$condition_resource=array();
		if($object_type=='evaluation'){
			$information_resource=$this->InformationResource->information_formated('evaluation_condition',$this->backend_locale,false);
			$condition_resource=isset($information_resource['evaluation_condition'])?$information_resource['evaluation_condition']:array();
		}else if($object_type=='course'){
			$information_resource=$this->InformationResource->information_formated('course_condition',$this->backend_locale,false);
			$condition_resource=isset($information_resource['course_condition'])?$information_resource['course_condition']:array();
		}
		$this->set('condition_resource',$condition_resource);
    }
    
    function ajax_pre_object(){
    		Configure::write('debug', 1);
		$this->layout = 'ajax';
		
		$object_type=isset($_POST['object_type'])?$_POST['object_type']:'';
		$object_code=isset($_POST['object_code'])?$_POST['object_code']:'';
		$object_keyword=isset($_POST['object_keyword'])?$_POST['object_keyword']:'';
		
		$result=array();
		$result['code']='0';
		if($object_type=='evaluation'){
			$conditions=array('Evaluation.code <>'=>$object_code,'Evaluation.status'=>'1','Evaluation.name <>'=>'');
			if($object_keyword!=''){
				$conditions['or']['Evaluation.code like']="%{$object_keyword}%";
				$conditions['or']['Evaluation.name like']="%{$object_keyword}%";
			}
			$EvaluationList=$this->Evaluation->find('list',array('fields'=>"id,name",'conditions'=>$conditions));
			if(!empty($EvaluationList)){
				$result['code']='1';
				$result['data']=$EvaluationList;
			}
		}else if($object_type=='course'){
			$conditions=array('Course.code <>'=>$object_code,'Course.status'=>'1','Course.name <>'=>'');
			if($object_keyword!=''){
				$conditions['or']['Course.code like']="%{$object_keyword}%";
				$conditions['or']['Course.name like']="%{$object_keyword}%";
			}
			$CourseList=$this->Course->find('list',array('fields'=>"id,name",'conditions'=>$conditions));
			if(!empty($CourseList)){
				$result['code']='1';
				$result['data']=$CourseList;
			}
		}
		die(json_encode($result));
    }

    /**
     *编辑前置条件
     */
    public function view($id=0){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
    		
    		$precondition_info=$this->Precondition->find('first',array('conditions'=>array('Precondition.id'=>$id)));
    		$this->set('precondition_info', $precondition_info);
    		
    		if(!empty($precondition_info)){
    			$object_type=isset($precondition_info['Precondition'])?$precondition_info['Precondition']['object']:'';
    			$object_params=isset($precondition_info['Precondition'])?$precondition_info['Precondition']['params']:'';
			$PreconditionValue=isset($precondition_info['Precondition'])?$precondition_info['Precondition']['value']:'';
			$PreconditionValueList=$PreconditionValue!=''?explode(',',$PreconditionValue):array();
			
			if($object_type=='evaluation'&&$object_params=='parent_evaluation'){
				if(!empty($PreconditionValueList)){
					$conditions=array('Evaluation.id'=>$PreconditionValueList,'Evaluation.status'=>'1','Evaluation.name <>'=>'');
					$pre_evaluation_list=$this->Evaluation->find('list',array('fields'=>"id,name",'conditions'=>$conditions));
					$this->set('pre_evaluation_list',$pre_evaluation_list);
				}
			}else if($object_type=='course'&&$object_params=='parent_course'){
				if(!empty($PreconditionValueList)){
					$conditions=array('Course.id'=>$PreconditionValueList,'Course.status'=>'1','Course.name <>'=>'');
					$pre_course_list=$this->Course->find('list',array('fields'=>"id,name",'conditions'=>$conditions));
					$this->set('pre_course_list',$pre_course_list);
				}
			}
		}
		
		$ability_level_list=$this->AbilityLevel->find('all',array('conditions'=>array('AbilityLevel.status'=>1)));
		$this->set('ability_level_list',$ability_level_list);
		
		$condition_resource=array();
		if($object_type=='evaluation'){
			$information_resource=$this->InformationResource->information_formated('evaluation_condition',$this->backend_locale,false);
			$condition_resource=isset($information_resource['evaluation_condition'])?$information_resource['evaluation_condition']:array();
		}else if($object_type=='course'){
			$information_resource=$this->InformationResource->information_formated('course_condition',$this->backend_locale,false);
			$condition_resource=isset($information_resource['course_condition'])?$information_resource['course_condition']:array();
		}
		$this->set('condition_resource',$condition_resource);
    }
    
    /**
     * 删除前置条件
     *
     *@param int $id
     */
    public function remove($id){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result['flag'] = 2;
		$result['message'] = $this->ld['delete_member_failure'];
		$this->Precondition->deleteAll(array('Precondition.id' => $id));
		//操作员日志
		if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
			$this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id, $this->admin['id']);
		}
		$result['flag'] = 1;
		$result['message'] = $this->ld['delete_member_success'];
		die(json_encode($result));
    }
}