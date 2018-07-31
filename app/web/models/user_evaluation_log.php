<?php
/**
 * 	UserEvaluationLog 用户评测记录
 */
class UserEvaluationLog extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    public $belongsTo = array(
        'Evaluation' => array(
	        'className' => 'Evaluation',
	        'conditions' => 'Evaluation.id=UserEvaluationLog.evaluation_id',
	        'order' => '',
	        'dependent' => true
        )
    );
    
    function user_evaluation_list($params=array()){
    		$result=array();
    		$user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
    		$conditions=array();
    		$conditions['UserEvaluationLog.user_id']=$user_id;
    		$evaluation_list=$this->find('all',array('conditions'=>$conditions,'order'=>'UserEvaluationLog.modified desc,UserEvaluationLog.id'));
    		$result['evaluation_list']=$evaluation_list;
    		return $result;
    }
}