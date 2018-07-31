<?php
/**
* 	CourseAssignmentScore 作业评分
*/
class CourseAssignmentScore extends AppModel{
	/*
	* @var $useDbConfig 数据库配置
	*/
    	public $useDbConfig = 'hr';
    	
    	public $belongsTo = array(
    		'CourseAssignment' => array('className' => 'CourseAssignment',
	            'conditions' => "CourseAssignmentScore.course_assignment_id=CourseAssignment.id",
	            'order' => '',
	            'dependent' => true,
	            'foreignKey' => ''
        	)
    	);
}