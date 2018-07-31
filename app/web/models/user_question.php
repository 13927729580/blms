<?php
/**
 * 	UserQuestion 用户题库
 */
class UserQuestion extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    public $hasMany = array(
                        'UserQuestionOption' => array(
	                        'className' => 'UserQuestionOption',
	                        'order' => 'UserQuestionOption.name,UserQuestionOption.id',
	                        'fields' => 'UserQuestionOption.*',
	                        'dependent' => true,
	                        'foreignKey' => 'user_question_id',
                    	)
        );
}