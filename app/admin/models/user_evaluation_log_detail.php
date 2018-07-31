<?php
/**
 * 	UserEvaluationLogDetail 用户评测记录详情
 */
class UserEvaluationLogDetail extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    public $belongsTo = array(
        'EvaluationQuestion' => array(
	        'className' => 'EvaluationQuestion',
	        'conditions' => 'UserEvaluationLogDetail.evaluation_question_id=EvaluationQuestion.id',
	        'order' => '',
	        'dependent' => true
        )
    );
}