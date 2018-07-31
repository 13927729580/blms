<?php

/**
 * 	CourseType 课程类型
 */
class CourseType extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    function course_type_list(){
    		$conditions=array();
    		$conditions['CourseType.status']='1';
            $conditions['CourseType.status']='1';
            $conditions['or'][]=array('CourseType.user_id'=>0);
            if(isset($_SESSION['User']['User']['id'])){
                $conditions['or'][]=array('CourseType.user_id'=>$_SESSION['User']['User']['id']);
            }
    		$course_categorys=$this->find('all',array('conditions'=>$conditions,'order'=>"CourseType.name"));
    		return $course_categorys;
    }
}
