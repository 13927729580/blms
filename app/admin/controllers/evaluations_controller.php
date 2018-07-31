<?php

/*****************************************************************************
 * Seevia 评测管理
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
 *这是一个名为 EvaluationsController 的控制器
 *评测管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class EvaluationsController extends AppController
{
    public $name = 'Evaluations';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('Profile','AbilityLevel','Evaluation','EvaluationCategory','EvaluationQuestion','EvaluationRule','UserEvaluationLog','InformationResource','Precondition');

    /**
     *显示列表.
     */
    public function index($page = 1)
    {
    	 $this->operator_privilege('evaluation_view');
        $this->operation_return_url(true);
        $this->menu_path = array('root' => '/hr/', 'sub' => '/evaluations/');
        $this->navigations[] = array('name' => "在线学习", 'url' => '');
        $this->navigations[] = array('name' => "评测管理", 'url' => '/evaluations/');
        $condition = '';
        $option_type_code="-1";
        $start_date_time = '';
        $end_date_time = '';
        $status="-1";
        if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
            $condition['or']['Evaluation.name like'] = '%' . $_REQUEST['keyword'] . '%';
            $condition['or']['Evaluation.description like'] = '%' . $_REQUEST['keyword'] . '%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        if (isset($this->params['url']['status']) && $this->params['url']['status'] != '-1') {
            $condition['and']['Evaluation.status'] = $this->params['url']['status'];
            $status = $this->params['url']['status'];
        }
        if (isset($this->params['url']['option_type_code']) && $this->params['url']['option_type_code'] != '-1') {
            $condition['and']['Evaluation.evaluation_category_code'] = $this->params['url']['option_type_code'];
            $option_type_code = $this->params['url']['option_type_code'];
        }
        if (isset($this->params['url']['start_date_time']) && $this->params['url']['start_date_time'] != '') {
            $condition['and']['Evaluation.modified >='] = $this->params['url']['start_date_time'].' 00:00:00';
            $start_date_time = $this->params['url']['start_date_time'];
            $this->set('start_date_time', $start_date_time);
        }
        if (isset($this->params['url']['end_date_time']) && $this->params['url']['end_date_time'] != '') {
            $condition['and']['Evaluation.modified <='] = $this->params['url']['end_date_time'].' 23:59:59';
            $end_date_time = $this->params['url']['end_date_time'];
            $this->set('end_date_time', $end_date_time);
        }
        $total = $this->Evaluation->find('count', array('conditions' => $condition));
        //评测总数限制
        $max_evaluation_total=intval(Configure::read('HR.max_evaluation_total'));
        $this->set('can_to_add',$max_evaluation_total>$total);
        
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int)$this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'evaluations', 'action' => 'index', 'page' => $page, 'limit' => $rownum);
        $options = array('page' => $page, 'show' => $rownum, 'total' => $total, 'modelClass' => 'Evaluation');
        $this->Pagination->init($condition, $parameters, $options);
        $evaluation_list = $this->Evaluation->find('all', array('conditions' => $condition, 'page' => $page,'limit' => $rownum,'order' => 'created desc,id desc'));
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
        $evaluation_category=$this->EvaluationCategory->evaluation_category_list();
        $this->set('status', $status);
        $this->set('evaluation_category', $evaluation_category);
        $this->set('evaluation_list', $evaluation_list);
        $this->set('option_type_code', $option_type_code);
        $this->set('title_for_layout', "评测管理" . ' - ' . $this->ld['page'] . ' ' . $page . ' - ' . $this->configs['shop_name']);
    }

    /**
     *添加评测
     */
    public function add()
    {
    	 $this->operator_privilege('evaluation_add');
        $this->menu_path = array('root' => '/hr/','sub' => '/evaluations/');
        $this->set('title_for_layout', $this->ld['add'].'-评测管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "评测管理",'url' => '/evaluations/');
        $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        $evaluation_category=$this->EvaluationCategory->evaluation_category_list();
        if ($this->RequestHandler->isPost()) {
			$this->Evaluation->save($this->data);
			$back_url = $this->operation_return_url();//获取操作返回页面地址
			$this->redirect($back_url);
        }
        $this->set('evaluation_category', $evaluation_category);
    }

    /**
     *编辑评测
     */
    public function view($id)
    {
    	 $this->operator_privilege('evaluation_edit');
        $this->operation_return_url(true);
        $this->menu_path = array('root' => '/hr/','sub' => '/evaluations/');
        $this->set('title_for_layout', $this->ld['edit'].'-评测管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "评测管理",'url' => '/evaluations/');
        $this->navigations[] = array('name' => $this->ld['edit'],'url' => '');
        $evaluation_category=$this->EvaluationCategory->evaluation_category_list();
        $evaluation_info=$this->Evaluation->find('first',array('conditions'=>array('Evaluation.id'=>$id)));
        $evaluation_rule_info=$this->EvaluationRule->evaluation_rule_list($evaluation_info["Evaluation"]["code"]);
        $info_resource=$this->InformationResource->information_formated('question_type',$this->backend_locale,false);
        $evaluation_question_info=$this->EvaluationQuestion->find('all',array('conditions'=>array('EvaluationQuestion.evaluation_code'=>$evaluation_info["Evaluation"]["code"])));
        $user_evaluation=$this->UserEvaluationLog->find('all',array('conditions'=>array('UserEvaluationLog.evaluation_id'=>$id),'order' => 'start_time desc'));
        $evaluation_condition=$this->Precondition->pre_condition_list('evaluation',$evaluation_info["Evaluation"]["code"]);
        $condition_code=array();
        $parent_name="";
        $condition_resource=$this->InformationResource->information_formated('evaluation_condition',$this->backend_locale,'false');
        if(!empty($evaluation_condition)){
        	foreach($evaluation_condition as $kk=>$vv){
        		$condition_code[]=$vv['Precondition']['params'];
        		if($vv['Precondition']['params']=="parent_evaluation"){
        			$parent_ids=explode(',',$vv['Precondition']['value']);
        			$parent_info=$this->Evaluation->find('list',array("fields"=>"Evaluation.name",'conditions'=>array('Evaluation.id'=>$parent_ids)));
        			$parent_name=implode(",",$parent_info);
        		}else if($vv['Precondition']['params']=="ability_level"){
        			$ability_level_ids=explode(',',$vv['Precondition']['value']);
        			$ability_level_list=array();
        			$ability_level_infos=$this->AbilityLevel->find('all',array('fields'=>'AbilityLevel.id,AbilityLevel.name,Ability.name','conditions'=>array('AbilityLevel.id'=>$ability_level_ids,'AbilityLevel.status'=>1)));
        			foreach($ability_level_infos as $v)$ability_level_list[$v['AbilityLevel']['id']]=$v['Ability']['name'].$v['AbilityLevel']['name'];
        			$this->set('ability_level_list', $ability_level_list);
        		}
        	}
        }
        if ($this->RequestHandler->isPost()) {
        		if(!empty($evaluation_info)&&!empty($this->data['Evaluation'])){
        			$this->EvaluationQuestion->updateAll(array('EvaluationQuestion.evaluation_code'=>"'".$this->data['Evaluation']['code']."'"),array('EvaluationQuestion.evaluation_code'=>$evaluation_info['Evaluation']['code']));
        				$this->EvaluationRule->updateAll(array('EvaluationRule.evaluation_code'=>"'".$this->data['Evaluation']['code']."'"),array('EvaluationRule.evaluation_code'=>$evaluation_info['Evaluation']['code']));
        				$this->EvaluationRule->updateAll(array('EvaluationRule.child_evaluation_code'=>"'".$this->data['Evaluation']['code']."'"),array('EvaluationRule.child_evaluation_code'=>$evaluation_info['Evaluation']['code']));
        		}
			$this->Evaluation->save($this->data);
			$back_url = $this->operation_return_url();//获取操作返回页面地址
			$this->redirect($back_url);
        }
        $level_list=$this->AbilityLevel->find('all',array('conditions'=>array('AbilityLevel.status'=>1)));
        $evaluation_list=$this->Evaluation->find('all');
        $profile_code="user_question_upload";
	 $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id','Profile.code'), 'conditions' => array('Profile.code' => $profile_code, 'Profile.status' => 1)));
        $this->set('profile_info',$profile_info);
        $this->set('evaluation_list', $evaluation_list);
        $this->set('level_list', $level_list);
        $this->set('parent_name', $parent_name);
        $this->set('condition_code', $condition_code);
        $this->set('condition_resource', $condition_resource['evaluation_condition']);
        $this->set('evaluation_condition', $evaluation_condition);
        $this->set('info_resource', $info_resource);
        $this->set('evaluation_rule_info', $evaluation_rule_info);
        $this->set('evaluation_question_info', $evaluation_question_info);
        $this->set('user_evaluation', $user_evaluation);
        $this->set('evaluation_info', $evaluation_info);
        $this->set('evaluation_category', $evaluation_category);
    }

    /**
     * 删除评测
     *
     *@param int $id
     */
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        if($this->operator_privilege('evaluation_remove',false)){
	        $evaluation_info = $this->Evaluation->findById($id);
	        $this->Evaluation->deleteAll(array('id' => $id));
	        $this->EvaluationQuestion->deleteAll(array('EvaluationQuestion.evaluation_code' => $evaluation_info["Evaluation"]["code"]));
	        $this->EvaluationRule->deleteAll(array('EvaluationRule.evaluation_code' => $evaluation_info["Evaluation"]["code"]));
	        //操作员日志
	        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
	            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id.' '.$evaluation_info['Evaluation']['code'], $this->admin['id']);
	        }
	        $result['flag'] = 1;
	        $result['message'] = $this->ld['delete_member_success'];
        }
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/evaluations/');
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
    
    function ajax_category_report(){
    		Configure::write('debug',1);
        	$this->layout="ajax";
        	
        	$conditions=array();
        	$conditions['Evaluation.status']='1';
        	$evaluation_infos=$this->Evaluation->find('all',array('conditions'=>$conditions,'fields'=>'Evaluation.id,Evaluation.evaluation_category_code,Evaluation.name'));
        	if(!empty($evaluation_infos)){
        		$evaluation_list=array();$category_evaluations=array();
        		foreach($evaluation_infos as $v){
        			$evaluation_list[$v['Evaluation']['id']]=$v['Evaluation']['name'];
        			$category_evaluations[$v['Evaluation']['evaluation_category_code']][]=$v['Evaluation'];
        		}
        		$this->set('category_evaluations',$category_evaluations);
        		$evaluation_category_infos=$this->EvaluationCategory->find('list',array('fields'=>'EvaluationCategory.code,EvaluationCategory.name','conditions'=>array('status'=>'1')));
        		$this->set('evaluation_category_infos',$evaluation_category_infos);
        		
        		$evaluation_ids=array_keys($evaluation_list);
        		$user_evaluation_infos=$this->UserEvaluationLog->find('all',array('fields'=>'UserEvaluationLog.evaluation_id,UserEvaluationLog.user_id','conditions'=>array('UserEvaluationLog.evaluation_id'=>$evaluation_ids),'group'=>'UserEvaluationLog.evaluation_id,UserEvaluationLog.user_id'));
        		
        		$user_evaluation_list=array();
        		if(!empty($user_evaluation_infos)){
        			foreach($user_evaluation_infos as $v){
        				$user_evaluation_list[$v['UserEvaluationLog']['evaluation_id']][]=$v['UserEvaluationLog']['user_id'];
        			}
        			foreach($user_evaluation_list as $k=>$v)$user_evaluation_list[$k]=sizeof($v);
        		}
        		$this->set('user_evaluation_list',$user_evaluation_list);
        	}
    }
}