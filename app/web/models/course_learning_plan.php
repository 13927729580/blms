<?php
/**
 * 	CourseLearningPlan
 */
class CourseLearningPlan extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    public $hasOne = array(
    		'CourseClass' => array(
				'className' => 'CourseClass',
				'conditions' => 'CourseClass.id = CourseLearningPlan.course_class_id',
				'order' => '',
				'dependent' => true,
				'foreignKey' => ''
                    ),
		'CourseChapter'=>array(
				'className' => 'CourseChapter',
				'conditions' => 'CourseChapter.code = CourseClass.chapter_code and CourseChapter.course_code=CourseClass.course_code',
				'order' => '',
				'dependent' => true,
				'foreignKey' => ''
			)
    );
    
}