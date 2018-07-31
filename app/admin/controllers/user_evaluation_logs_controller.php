<?php

/*****************************************************************************
 * Seevia 会员评测管理
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
 *这是一个名为 UserEvaluationLogsController 的控制器
 *会员评测管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class UserEvaluationLogsController extends AppController
{
    public $name = 'UserEvaluationLogs';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('UserEvaluationLog','EvaluationCategory','UserEvaluationLogDetail','User','EvaluationRule');

    /**
     *显示列表.
     */
    public function index($page = 1)
    {
    	 $this->operator_privilege('user_evaluation_result');
        $this->operation_return_url(true);
        $this->menu_path = array('root' => '/hr/', 'sub' => '/user_evaluation_logs/');
        $this->navigations[] = array('name' => "在线学习", 'url' => '');
        $this->navigations[] = array('name' => "评测结果", 'url' => '/user_evaluation_logs/');
        $condition = '';
        $option_type_code="-1";
        $status="-1";
        $start_date_time = '';
        $end_date_time = '';
        if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
            $condition['or']['Evaluation.name like'] = '%' . $_REQUEST['keyword'] . '%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        if (isset($this->params['url']['user_name']) && $this->params['url']['user_name'] != '') {
            $user_condition['or']['User.name like'] = '%'.$_REQUEST['user_name']. '%';
            $user_ids=$this->User->find('list',array('fields'=>"User.id","conditions"=>$user_condition));
            $condition['and']['UserEvaluationLog.user_id'] = $user_ids;
            $this->set('user_name', $_REQUEST['user_name']);
        }
        if (isset($this->params['url']['option_type_code']) && $this->params['url']['option_type_code'] != '-1') {
            $condition['and']['Evaluation.evaluation_category_code'] = $this->params['url']['option_type_code'];
            $option_type_code = $this->params['url']['option_type_code'];
        }
        if (isset($this->params['url']['start_date_time']) && $this->params['url']['start_date_time'] != '') {
            $condition['and']['UserEvaluationLog.submit_time >='] = $this->params['url']['start_date_time'].' 00:00:00';
            $start_date_time = $this->params['url']['start_date_time'];
            $this->set('start_date_time', $start_date_time);
        }
        if (isset($this->params['url']['end_date_time']) && $this->params['url']['end_date_time'] != '') {
            $condition['and']['UserEvaluationLog.submit_time <='] = $this->params['url']['end_date_time'].' 23:59:59';
            $end_date_time = $this->params['url']['end_date_time'];
            $this->set('end_date_time', $end_date_time);
        }
        $total = $this->UserEvaluationLog->find('count', array('conditions' => $condition));
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int)$this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'user_evaluation_logs', 'action' => 'index', 'page' => $page, 'limit' => $rownum);
        $options = array('page' => $page, 'show' => $rownum, 'total' => $total, 'modelClass' => 'UserEvaluationLog');
        $this->Pagination->init($condition, $parameters, $options);
        $evaluation_list = $this->UserEvaluationLog->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum,'order' => 'UserEvaluationLog.created desc,UserEvaluationLog.id desc'));
        $evaluation_category=$this->EvaluationCategory->evaluation_category_list();
        $this->set('evaluation_category', $evaluation_category);
        $this->set('option_type_code', $option_type_code);
        $this->set('evaluation_list', $evaluation_list);
        $this->set('status', $status);
        $this->set('title_for_layout', "评测结果" . ' - ' . $this->ld['page'] . ' ' . $page . ' - ' . $this->configs['shop_name']);
    }

    /**
     *查看详情
     */
    public function view($id)
    {
        $this->menu_path = array('root' => '/hr/','sub' => '/user_evaluation_logs/');
        $this->set('title_for_layout', $this->ld['view'].'-评测结果- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "评测结果",'url' => '/user_evaluation_logs/');
        $this->navigations[] = array('name' => $this->ld['view'],'url' => '');
        if ($this->RequestHandler->isPost()){
        		$evaluation_info=$this->UserEvaluationLog->find('first',array('conditions'=>array('UserEvaluationLog.id'=>$id)));
        		$evaluation_rule_list=$this->EvaluationRule->evaluation_rule_list($evaluation_info["Evaluation"]["code"]);
        		
        		$evaluation_score_data=$this->UserEvaluationLogDetail->find('first',array('fields'=>'SUM(EvaluationQuestion.score) as score_total','conditions'=>array('UserEvaluationLogDetail.user_evaluation_log_id'=>$id,'EvaluationQuestion.score <>'=>0)));
			$evaluation_score_total=isset($evaluation_score_data[0]['score_total'])?$evaluation_score_data[0]['score_total']:0;
        		/*
        		$evaluation_rule_score=array();$evaluation_score_total=0;
			if(!empty($evaluation_rule_list)){
				foreach($evaluation_rule_list as $v){
					$question_score=$v['EvaluationRule']['score']/$v['EvaluationRule']['proportion'];
					$evaluation_rule_score[$v['EvaluationRule']['child_evaluation_code']][$v['EvaluationRule']['question_type']]=$question_score;
					$evaluation_score_total+=$v['EvaluationRule']['score'];
				}
			}
			$evaluation_score_total=empty($evaluation_score_total)?100:$evaluation_score_total;
			*/
	    		$Txt_question_infos=isset($this->data['UserEvaluationLogDetail'])?$this->data['UserEvaluationLogDetail']:array();
	    		foreach($Txt_question_infos as $k=>$v)$this->UserEvaluationLogDetail->save(array('id'=>$k,'score'=>$v));
	    		
	    		$evaluation_score=0;
	    		$user_evaluation_details=$this->UserEvaluationLogDetail->find('all',array('conditions'=>array('UserEvaluationLogDetail.user_evaluation_log_id'=>$id,'EvaluationQuestion.question_type <>'=>'2','UserEvaluationLogDetail.answer=EvaluationQuestion.right_answer')));
	    		$ability_experience_value=array();
        		if(!empty($user_evaluation_details)){
				foreach($user_evaluation_details as $v){
					/*$rule_score=isset($evaluation_rule_score[$v['EvaluationQuestion']['evaluation_code']][$v['EvaluationQuestion']['question_type']])?$evaluation_rule_score[$v['EvaluationQuestion']['evaluation_code']][$v['EvaluationQuestion']['question_type']]:1;*/
					$evaluation_score+=$v['EvaluationQuestion']['score'];
				}
			}
			$score_rule=empty($evaluation_score)?($evaluation_score/$evaluation_score_total*100):0;
			$score_rule=$score_rule+array_sum($Txt_question_infos);
			$evaluation_log_data=array(
				'id'=>$id,
				'status'=>'1',
				'score'=>$score_rule
			);
			$this->UserEvaluationLog->save($evaluation_log_data);
        }
        $evaluation_info=$this->UserEvaluationLog->find('first',array('conditions'=>array('UserEvaluationLog.id'=>$id)));
        $question_list=$this->UserEvaluationLogDetail->find('all',array('conditions'=>array('UserEvaluationLogDetail.user_evaluation_log_id'=>$id),'order'=>'UserEvaluationLogDetail.id'));
        $this->set('evaluation_info', $evaluation_info);
        $this->set('question_list', $question_list);
    }
    
        /**
     * 批量删除
     *
     */
    public function delete_all()
    {
        Configure::write('debug', 1);
        $result['code'] = 0;
        $result['msg'] = '系统错误';
        if ($this->RequestHandler->isPost()) {
            $ids = isset($_POST['ids']) ? $_POST['ids'] :0;
	        $this->UserEvaluationLog->belongsTo = array();
	        $this->UserEvaluationLogDetail->belongsTo = array();
            $this->UserEvaluationLog->deleteAll(array('id' => $ids));
            $this->UserEvaluationLogDetail->deleteAll(array('user_evaluation_log_id' => $ids));
            $result['msg'] = "删除成功";
            die(json_encode($result));
        } else {
            $this->redirect('/user_evaluation_logs');
        }
    }
}