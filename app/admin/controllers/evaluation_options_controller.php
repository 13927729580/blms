<?php

/*****************************************************************************
 * Seevia 选项管理
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
 *这是一个名为 EvaluationOptionsController 的控制器
 *选项管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class EvaluationOptionsController extends AppController
{
    public $name = 'EvaluationOptions';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('EvaluationOption','Evaluation','EvaluationQuestion');
    
    /**
     *编辑选项
     */
    public function view(){
    		Configure::write('debug', 1);
		$this->layout = 'ajax';
		
		if ($this->RequestHandler->isPost()){
			$result=array();
			$result['code']='0';
			$result['message']=$this->ld['unknown_error'];
			$update_option_ids=array();$evaluation_question_code='';
			if(isset($this->data['EvaluationOption'])&&!empty($this->data['EvaluationOption'])){
				foreach($this->data['EvaluationOption'] as $v){
					if(isset($v['checked'])&&$v['checked']!=''){
						if(trim($v['description'])=='')continue;
						$evaluation_question_code=$v['evaluation_question_code'];
						$this->EvaluationOption->save($v);
						$update_option_ids[]=$this->EvaluationOption->id;
					}
				}
			}
			if(!empty($update_option_ids)){
				$result['code']='1';
				$result['message']=$this->ld['update_successful'];
				$this->EvaluationOption->deleteAll(array('EvaluationOption.evaluation_question_code'=>$evaluation_question_code,'not'=>array('EvaluationOption.id'=>$update_option_ids)));
			}else{
				$this->EvaluationOption->deleteAll(array('EvaluationOption.evaluation_question_code'=>$evaluation_question_code));
			}
			die(json_encode($result));
		}
		
    		$evaluation_code=isset($_REQUEST['evaluation_code'])?$_REQUEST['evaluation_code']:'';
    		if($evaluation_code!=''){
    			$evaluation_info=$this->Evaluation->find('first',array('conditions'=>array('Evaluation.code'=>$evaluation_code)));
    			$this->set('evaluation_info',$evaluation_info);
    		}
    		$question_code=isset($_REQUEST['question_code'])?$_REQUEST['question_code']:'';
    		if($question_code!=''){
    			$evaluation_question_info=$this->EvaluationQuestion->find('first',array('conditions'=>array('EvaluationQuestion.evaluation_code'=>$evaluation_code,'EvaluationQuestion.code'=>$question_code)));
    			$this->set('evaluation_question_info',$evaluation_question_info);
    		}
    		$evaluation_question_option_info=$this->EvaluationOption->find('all',array('conditions'=>array('EvaluationOption.evaluation_question_code'=>$question_code)));
    		$evaluation_question_option_list=array();
    		if(!empty($evaluation_question_option_info)){
    			foreach($evaluation_question_option_info as $v){
    				$evaluation_question_option_list[$v['EvaluationOption']['name']]=$v['EvaluationOption'];
    			}
    		}
    		$this->set('evaluation_question_option_list',$evaluation_question_option_list);
    }

    /**
     * 删除选项
     *
     *@param int $id
     */
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        $this->EvaluationOption->deleteAll(array('EvaluationOption.id' => $id));
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
}