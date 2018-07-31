<?php

/**
 * 	CourseChapter 课程章节
 */
class CourseChapter extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    public $belongsTo = array(
	        'CourseClass' => array(
		        'className' => 'CourseClass',
		        'conditions' => 'CourseChapter.course_code=CourseClass.course_code and CourseChapter.code=CourseClass.chapter_code',
		        'order' => 'CourseChapter.orderby,CourseClass.orderby',
		        'dependent' => true,
			  'foreignKey'=>''
	        )
    );
    
    function course_chapter_tree($course_code){
		$course_class_list=array();
		$conditions=array();
		$conditions['CourseChapter.status']='1';
		$conditions['CourseChapter.course_code']=$course_code;
		$conditions['or']['CourseClass.id']=NULL;
		$conditions['or']['CourseClass.status']='1';
		$course_class_data=$this->find('all',array('conditions'=>$conditions,'order'=>'CourseChapter.orderby,CourseChapter.id,CourseClass.orderby,CourseClass.id'));
		if(!empty($course_class_data)){
			foreach($course_class_data as $k=>$v){
				$course_class_list[$v['CourseChapter']['id']]['CourseChapter']=$v['CourseChapter'];
				if($v['CourseClass']['id']>0){
					$course_class_list[$v['CourseChapter']['id']]['CourseClass'][$k]=$v['CourseClass'];
					$CourseClassWare = ClassRegistry::init('CourseClassWare');
					$course_ware=$CourseClassWare->find('all',array('conditions'=>array("CourseClassWare.course_class_code"=>$v['CourseClass']['code']),'order'=>"CourseClassWare.orderby"));
					$course_class_list[$v['CourseChapter']['id']]['CourseClass'][$k]['CourseWare']=$course_ware;
				}
			}
		}
		return $course_class_list;
    }
}
