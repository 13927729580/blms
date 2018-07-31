<?php

/*****************************************************************************
 * Seevia 规则管理
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
 *这是一个名为 EvaluationRulesController 的控制器
 *规则管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class EvaluationRulesController extends AppController
{
    public $name = 'EvaluationRules';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('EvaluationRule','Evaluation');

    /**
     *添加规则
     */
    public function add($code)
    {
        $this->menu_path = array('root' => '/hr/','sub' => '/evaluations/');
        $this->set('title_for_layout', $this->ld['add'].'-评测管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "评测管理",'url' => '/evaluations/');
        $this->navigations[] = array('name' => $this->ld['add']."规则",'url' => '');
        $evaluation_list=$this->Evaluation->find('all');
        if ($this->RequestHandler->isPost()) {
            $this->data["EvaluationRule"]["evaluation_code"]=$code;
            $this->EvaluationRule->save($this->data);
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->set('evaluation_list', $evaluation_list);
        $this->set('code', $code);
    }

    /**
     *编辑规则
     */
    public function view($id)
    {
        $this->menu_path = array('root' => '/hr/','sub' => '/evaluations/');
        $this->set('title_for_layout', $this->ld['edit'].'-评测管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "评测管理",'url' => '/evaluations/');
        $this->navigations[] = array('name' => $this->ld['edit']."规则",'url' => '');
        $evaluation_rule_info=$this->EvaluationRule->find('first',array('conditions'=>array('EvaluationRule.id'=>$id)));
        $evaluation_list=$this->Evaluation->find('all',array('conditions'=>array('Evaluation.status'=>1)));
        if ($this->RequestHandler->isPost()) {
            $this->EvaluationRule->save($this->data);
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->set('evaluation_rule_info', $evaluation_rule_info);
        $this->set('evaluation_list', $evaluation_list);
    }

    /**
     * 删除规则
     *
     *@param int $id
     */
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        $this->EvaluationRule->deleteAll(array('EvaluationRule.id' => $id));
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
    
	function ajax_modify(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $result['message']=$this->ld['modify_failed'];
        $this->EvaluationRule->save($this->data);
        $result['code']='1';
        $result['message']='操作成功';
        die(json_encode($result));
    }
    
	function ajax_edit($id){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $result['message']=$this->ld['modify_failed'];
        $evaluation_rule_info=$this->EvaluationRule->find('first',array('conditions'=>array('EvaluationRule.id'=>$id)));
        $result['code']='1';
        $result['data']=$evaluation_rule_info;
        die(json_encode($result));
    }
}