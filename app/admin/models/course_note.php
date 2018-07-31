<?php
/**
 * 	CourseNote 课程笔记
 */
class CourseNote extends AppModel{
	/*
	* @var $useDbConfig 数据库配置
	*/
	public $useDbConfig = 'hr';
	
    	public $hasOne = array(
    		'CourseClass' => array(
				'className' => 'CourseClass',
				'conditions' => 'CourseClass.id = CourseNote.course_class_id',
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
				'conditions' => 'Course.id = CourseNote.course_id',
				'order' => '',
				'dependent' => true,
				'foreignKey' => ''
			)
    	);
    	
}