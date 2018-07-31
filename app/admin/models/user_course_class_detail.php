<?php

/**
 * 	UserCourseClass 用户学习记录
 */
class UserCourseClassDetail extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    public $belongsTo = array(
	        'UserCourseClass' => array(
		        'className' => 'UserCourseClass',
		        'conditions' => 'UserCourseClassDetail.user_course_class_id=UserCourseClass.id',
		        'order' => '',
		        'dependent' => false
	        ),'CourseClass' => array(
		        'className' => 'CourseClass',
		        'conditions' => 'CourseClass.id=UserCourseClassDetail.course_class_id',
		        'order' => '',
		        'dependent' => false
	        )
    );
}