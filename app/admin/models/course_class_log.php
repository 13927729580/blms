<?php
/**
 * 	CourseClassLog 用户学习记录
 */
class CourseClassLog extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';

    public $belongsTo = array(
        'Course' => array(
            'className' => 'Course',
            'conditions' => 'Course.id=CourseClassLog.course_id',
            'order' => '',
            'dependent' => true,
        ),
        'User' => array(
            'className' => 'User',
            'conditions' => 'User.id=CourseClassLog.user_id',
            'order' => '',
            'dependent' => true
        ),
        'CourseClass' => array(
            'className' => 'CourseClass',
            'conditions' => 'CourseClass.id=CourseClassLog.course_class_id',
            'order' => '',
            'dependent' => true
        )
    );
}