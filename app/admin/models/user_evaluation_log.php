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
        ),
        'User' => array(
        	'className' => 'User',
        	'conditions' => 'User.id=UserEvaluationLog.user_id',
        	'order' => '',
        	'dependent' => true
        )
    );
}