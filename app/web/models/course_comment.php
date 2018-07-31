<?php

/**
 * 	CourseComment 课程评价
 */
class CourseComment extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    public $belongsTo = array(
	        'User' => array(
				'className' => 'Brand',
				'conditions' => 'User.id=CourseComment.user_id',
				'order' => '',
				'dependent' => true
	        ),
	        'Course'=>array(
	        		'className' => 'Course',
				'conditions' => 'Course.id=CourseComment.course_id',
				'order' => '',
				'dependent' => true
	        )
    );
}
