<?php
class CourseAssignment extends AppModel{
	public $useDbConfig = 'hr';
	public $name = 'CourseAssignment';
	
    	public $hasOne = array(
    		'CourseClassWare' => array(
				'className' => 'CourseClassWare',
				'conditions' => 'CourseClassWare.id = CourseAssignment.course_ware_id',
				'order' => '',
				'dependent' => true,
				'foreignKey' => ''
                    ),
    		'CourseClass' => array(
				'className' => 'CourseClass',
				'conditions' => 'CourseClass.code = CourseClassWare.course_class_code',
				'order' => '',
				'dependent' => true,
				'foreignKey' => ''
                    ),
		'CourseChapter'=>array(
				'className' => 'CourseChapter',
				'conditions' => 'CourseClass.chapter_code = CourseChapter.code',
				'order' => '',
				'dependent' => true,
				'foreignKey' => ''
			),
		'Course'=>array(
				'className' => 'Course',
				'conditions' => 'Course.id = CourseAssignment.course_id',
				'order' => '',
				'dependent' => true,
				'foreignKey' => ''
			)
    	);
}