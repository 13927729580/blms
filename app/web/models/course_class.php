<?php

/**
 * 	CourseClass 课程课时
 */
class CourseClass extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    public $belongsTo = array(
	        'CourseChapter' => array(
		        'className' => 'CourseChapter',
		        'conditions' => 'CourseChapter.course_code=CourseClass.course_code and CourseChapter.code=CourseClass.chapter_code',
		        'order' => 'CourseChapter.orderby,CourseClass.orderby',
		        'dependent' => true,
			  'foreignKey'=>''
	        ),'Course' => array(
		        'className' => 'Course',
		        'conditions' => 'Course.code=CourseClass.course_code and CourseChapter.course_code=Course.code',
		        'order' => 'Course.id',
		        'dependent' => true,
			  'foreignKey'=>''
	        )
    );
    
    function course_chapter_tree($course_code){
    		$course_class_list=array();
    		$conditions=array();
		$conditions['CourseChapter.status']='1';
		$conditions['CourseClass.status']='1';
		$conditions['CourseClass.course_code']=$course_code;
		$course_class_data=$this->find('all',array('conditions'=>$conditions,'order'=>'CourseChapter.orderby,CourseChapter.id,CourseClass.orderby,CourseClass.id'));
		if(!empty($course_class_data)){
			foreach($course_class_data as $v){
				$course_class_list[$v['CourseChapter']['id']]['CourseChapter']=$v['CourseChapter'];
				$course_class_list[$v['CourseChapter']['id']]['CourseClass'][]=$v['CourseClass'];
			}
		}
		return $course_class_list;
    }
    
}
