<?php

/**
 * 	UserCourseClass 用户学习记录
 */
class UserCourseClass extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    public $belongsTo = array(
	        'Course' => array(
		        'className' => 'Course',
		        'conditions' => 'Course.id=UserCourseClass.course_id',
		        'order' => '',
		        'dependent' => true,
	        ),
	    	'User' => array('className' => 'User',
	            'conditions' => '',
	            'order' => '',
	            'dependent' => true,
	            'foreignKey' => 'user_id',
	        )
    );
}