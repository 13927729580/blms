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
	        )
    );
    
    function user_course_list(){
    		$result=array();
    		$user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
    		$conditions=array();
    		$conditions['Course.status']='1';
    		$conditions['UserCourseClass.user_id']=$user_id;
    		$course_list=$this->find('all',array('conditions'=>$conditions,'order'=>'UserCourseClass.modified desc,UserCourseClass.id'));
    		$result['course_list']=$course_list;
    		if(!empty($course_list)){
    			$course_ids=array();$course_codes=array();
    			foreach($course_list as $v){
    				$course_ids[]=$v['Course']['id'];
    				$course_codes[]=$v['Course']['code'];
    			}
    			$CourseClass = ClassRegistry::init('CourseClass');
    			$course_class_totals=$CourseClass->find('all',array('fields'=>array("course_code","count(*) as class_count"),'conditions'=>array('course_code'=>$course_codes),'group'=>'course_code', 'recursive' => -1));
    			if(!empty($course_class_totals)){
    				$course_class_total_list=array();
    				foreach($course_class_totals as $v)$course_class_total_list[$v['CourseClass']['course_code']]=$v[0]['class_count'];
    				$result['course_class_total']=$course_class_total_list;
    			}
    		}
    		return $result;
    }
}