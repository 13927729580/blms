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
 *这是一个名为 EvaluationConditionsController 的控制器
 *前置条件管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class EvaluationConditionsController extends AppController
{
    public $name = 'EvaluationConditions';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('EvaluationCondition','InformationResource','AbilityLevel','Evaluation');

    /**
     *添加前置条件
     */
    public function add($code)
    {
        $this->menu_path = array('root' => '/hr/','sub' => '/evaluations/');
        $this->set('title_for_layout', $this->ld['add'].'-评测管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "评测管理",'url' => '/evaluations/');
        $this->navigations[] = array('name' => $this->ld['add']."前置条件",'url' => '');
        $evaluation_condition=$this->EvaluationCondition->find('all',array('conditions'=>array('EvaluationCondition.evaluation_code'=>$code)));
        $level_list=$this->AbilityLevel->find('all',array('conditions'=>array('AbilityLevel.status'=>1)));
        $condition_code=$this->EvaluationCondition->find('list',array('fields'=>'EvaluationCondition.params','conditions'=>array('EvaluationCondition.evaluation_code'=>$code)));
        $condition_resource=$this->InformationResource->information_formated('evaluation_condition',$this->backend_locale,false);
        if ($this->RequestHandler->isPost()) {
            if($this->data["EvaluationCondition"]["params"]!="parent_evaluation"){
                if(is_array($this->data["EvaluationCondition"]["value"]) && $this->data["EvaluationCondition"]["params"]=="ability_level"){
                    $this->data["EvaluationCondition"]["value"]=implode(",",$this->data["EvaluationCondition"]["value"]);
                }
                $this->data["EvaluationCondition"]["evaluation_code"]=$code;
                if($this->data["EvaluationCondition"]["value"]==0){$this->data["EvaluationCondition"]["value"]="";}
                $this->EvaluationCondition->save($this->data);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->set('condition_code', $condition_code);
        $this->set('level_list', $level_list);
        $this->set('evaluation_condition', $evaluation_condition);
        $this->set('code', $code);
        $this->set('condition_resource', $condition_resource['evaluation_condition']);
    }

    /**
     *编辑前置条件
     */
    public function view($id)
    {
        $this->menu_path = array('root' => '/hr/','sub' => '/evaluations/');
        $this->set('title_for_layout', $this->ld['edit'].'-评测管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "评测管理",'url' => '/evaluations/');
        $this->navigations[] = array('name' => $this->ld['edit']."前置条件",'url' => '');
        $evaluation_condition_info=$this->EvaluationCondition->find('first',array('conditions'=>array('EvaluationCondition.id'=>$id)));
        $level_list=$this->AbilityLevel->find('all',array('conditions'=>array('AbilityLevel.status'=>1)));
        $condition_resource=$this->InformationResource->information_formated('evaluation_condition',$this->backend_locale,false);
        if ($this->RequestHandler->isPost()) {
            if(is_array($this->data["EvaluationCondition"]["value"])){
                $this->data["EvaluationCondition"]["value"]=implode(",",$this->data["EvaluationCondition"]["value"]);
            }
            $this->EvaluationCondition->save($this->data);
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        if($evaluation_condition_info['EvaluationCondition']["params"]=="parent_evaluation"){
            $condition_array=explode(",",$evaluation_condition_info['EvaluationCondition']['value']);
            $condition['and']['Evaluation.status'] = '1';
            $condition['and']['Evaluation.id'] =$condition_array;
            $fields[] = 'Evaluation.id';
            $fields[] = 'Evaluation.name';
            $evaluation_list = $this->Evaluation->find('all', array('conditions' => $condition, 'order' => 'Evaluation.id desc', 'fields' => $fields));
            $this->set('evaluation_list', $evaluation_list);
        }
        $this->set('level_list', $level_list);
        $this->set('evaluation_condition_info', $evaluation_condition_info);
        $this->set('condition_resource', $condition_resource['evaluation_condition']);
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
        $this->EvaluationCondition->deleteAll(array('EvaluationCondition.id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_member_success'];
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/evaluations/');
        }
    }

    /**
     *搜索评测
     */
    public function searchEvaluation()
    {
        $condition = '';
        //设置返回初始参数
        $result['flag'] = 2;//2 失败 1成功
        $result['content'] = $this->ld['no_data_found'];
        $evaluation_keyword = empty($_REQUEST['evaluation_keyword']) ? '' : trim($_REQUEST['evaluation_keyword']);//关键字
        $condition_id = empty($_REQUEST['condition_id']) ? '' : trim($_REQUEST['condition_id']);
        //初始化条件
        $evaluation_condition_info=$this->EvaluationCondition->find('first',array('conditions'=>array('EvaluationCondition.id'=>$condition_id)));
        if($evaluation_condition_info["EvaluationCondition"]["value"]!=""){
            $condition_value=explode(",",$evaluation_condition_info["EvaluationCondition"]["value"]);
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

    /**
     *编辑页 前置评测 添加
     */
    public function add_relation_evaluation()
    {
        //设置返回初始参数
        $result['flag'] = 2;//2 失败 1成功
        $result['content'] = $this->ld['unknown_reasons'];
        $evaluation_id = $_REQUEST['evaluation_id'];
        $condition_id = $_REQUEST['condition_id'];
        if($condition_id==0){
            $code=$_REQUEST['code'];
            $evaluation_condition_info['EvaluationCondition']['evaluation_code']=$code;
            $evaluation_condition_info['EvaluationCondition']['params']="parent_evaluation";
            $evaluation_condition_info['EvaluationCondition']['value']="0";
        }else{
            $evaluation_condition_info=$this->EvaluationCondition->find('first',array('conditions'=>array('EvaluationCondition.id'=>$condition_id)));
        }
        if($evaluation_id!=0){
            if(empty($evaluation_condition_info['EvaluationCondition']['value']) || $evaluation_condition_info['EvaluationCondition']['value']=="0"){
                $evaluation_condition_info['EvaluationCondition']['value']=$_REQUEST['evaluation_id'];
            }else{
                $evaluation_condition_info['EvaluationCondition']['value']=$evaluation_condition_info['EvaluationCondition']['value'].",".$_REQUEST['evaluation_id'];
            }
            $this->EvaluationCondition->save($evaluation_condition_info);
        }
        if($condition_id==0){
            $condition_id=$this->EvaluationCondition->getLastInsertId();
        }
        $condition_array=explode(",",$evaluation_condition_info['EvaluationCondition']['value']);
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

    /**
     *删除前置评测
     */
    public function delete_relation_evaluation()
    {
        $result['flag'] = 1;//2 失败 1成功
        $result['content'] = $this->ld['unknown_reasons'];
        $evaluation_id = $_REQUEST['evaluation_id'];
        $condition_id = $_REQUEST['condition_id'];
        $evaluation_condition_info=$this->EvaluationCondition->find('first',array('conditions'=>array('EvaluationCondition.id'=>$condition_id)));
        $condition_array=explode(",",$evaluation_condition_info['EvaluationCondition']['value']);
        foreach($condition_array as $k=>$v){
            if($v==$_REQUEST['evaluation_id']){
                unset($condition_array[$k]);
            }
        }
        $evaluation_condition_info['EvaluationCondition']['value']=implode(",",$condition_array);
        $this->EvaluationCondition->save($evaluation_condition_info);
        $condition['and']['Evaluation.status'] = '1';
        $condition['and']['Evaluation.id'] =$condition_array;
        $fields[] = 'Evaluation.id';
        $fields[] = 'Evaluation.name';
        $evaluation_list = $this->Evaluation->find('all', array('conditions' => $condition, 'order' => 'Evaluation.id desc', 'fields' => $fields));
        $result['flag'] = 1;//2 失败 1成功
        $result['content'] = $evaluation_list;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    function ajax_modify(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $result['message']=$this->ld['modify_failed'];
        if(isset($_POST['edit_params']) && !empty($_POST['edit_params'])){
        	$this->data["EvaluationCondition"]["params"]=$_POST['edit_params'];
        }
        if($this->data["EvaluationCondition"]["params"]!="parent_evaluation"){
            if(is_array($this->data["EvaluationCondition"]["value"])){
                $this->data["EvaluationCondition"]["value"]=implode(",",$this->data["EvaluationCondition"]["value"]);
            }
            if($this->data["EvaluationCondition"]["value"]==0){$this->data["EvaluationCondition"]["value"]="";}
            $this->EvaluationCondition->save($this->data);
        }
        $result['code']='1';
        $result['message']='操作成功';
        die(json_encode($result));
    }
    
	function ajax_edit($id){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $evaluation_condition_info=$this->EvaluationCondition->find('first',array('conditions'=>array('EvaluationCondition.id'=>$id)));
        $result['code']='1';
        $result['data']=$evaluation_condition_info;
        die(json_encode($result));
    }
}