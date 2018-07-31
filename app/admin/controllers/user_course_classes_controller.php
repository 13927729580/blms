<?php

/*****************************************************************************
 * Seevia 会员课程管理
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
/**
 *这是一个名为 UserCourseClassesController 的控制器
 *会员课程管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class UserCourseClassesController extends AppController
{
    public $name = 'UserCourseClasses';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('UserCourseClass','CourseType','User','CourseClass','CourseComment','InformationResource');

    /**
     *显示列表.
     */
    public function index($page = 1)
    {
        $this->operation_return_url(true);
        $this->menu_path = array('root' => '/hr/', 'sub' => '/user_course_classes/');
        $this->navigations[] = array('name' => "在线学习", 'url' => '');
        $this->navigations[] = array('name' => "会员课程学习", 'url' => '/user_course_classes/');
        $condition = '';
        $option_type_code="-1";
        $status="-1";
        if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
            $condition['or']['Course.name like'] = '%' . $_REQUEST['keyword'] . '%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        if (isset($this->params['url']['user_name']) && $this->params['url']['user_name'] != '') {
            $user_condition['or']['User.name like'] = '%' . $_REQUEST['user_name'] . '%';
            $user_ids=$this->User->find('list',array('fields'=>"User.id","conditions"=>$user_condition));
            $condition['and']['UserCourseClass.user_id'] = $user_ids;
            $this->set('user_name', $_REQUEST['user_name']);
        }
        if (isset($this->params['url']['option_type_code']) && $this->params['url']['option_type_code'] != '-1') {
            $condition['and']['Course.course_type_code'] = $this->params['url']['option_type_code'];
            $option_type_code = $this->params['url']['option_type_code'];
        }
        if (isset($this->params['url']['status']) && $this->params['url']['status'] != '-1') {
            $condition['and']['UserCourseClass.status'] = $this->params['url']['status'];
            $status = $this->params['url']['status'];
        }
        if (isset($this->params['url']['start_date_time']) && $this->params['url']['start_date_time'] != '') {
            $condition['and']['UserCourseClass.modified >='] = $this->params['url']['start_date_time'].' 00:00:00';
            $start_date_time = $this->params['url']['start_date_time'];
            $this->set('start_date_time', $start_date_time);
        }
        if (isset($this->params['url']['end_date_time']) && $this->params['url']['end_date_time'] != '') {
            $condition['and']['UserCourseClass.modified <='] = $this->params['url']['end_date_time'].' 23:59:59';
            $end_date_time = $this->params['url']['end_date_time'];
            $this->set('end_date_time', $end_date_time);
        }
        $total = $this->UserCourseClass->find('count', array('conditions' => $condition));
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int)$this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'user_course_classes', 'action' => 'index', 'page' => $page, 'limit' => $rownum);
        $options = array('page' => $page, 'show' => $rownum, 'total' => $total, 'modelClass' => 'UserCourseClass');
        $this->Pagination->init($condition, $parameters, $options);
        $course_list = $this->UserCourseClass->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum,'order' => 'UserCourseClass.created desc,UserCourseClass.id desc'));
        $course_type=$this->CourseType->course_type_list();
        $this->set('course_type', $course_type);
        $this->set('option_type_code', $option_type_code);
        $this->set('course_list', $course_list);
        $this->set('status', $status);
        $this->set('title_for_layout', "会员课程学习" . ' - ' . $this->ld['page'] . ' ' . $page . ' - ' . $this->configs['shop_name']);
    }

    /**
     *查看详情
     */
    public function view($id)
    {
        $this->menu_path = array('root' => '/hr/','sub' => '/user_course_classes/');
        $this->set('title_for_layout', $this->ld['view'].'-会员课程学习- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "会员课程学习",'url' => '/user_course_classes/');
        $this->navigations[] = array('name' => $this->ld['view'],'url' => '');
        $course_class_info=$this->UserCourseClass->find('first',array('conditions'=>array('UserCourseClass.id'=>$id)));
        $class_ids=explode(",",$course_class_info['UserCourseClass']['class_ids']);
        $class_list=$this->CourseClass->find('all',array('conditions'=>array('CourseClass.id'=>$class_ids)));
        $comment_list=$this->CourseComment->find('first',array('conditions'=>array('CourseComment.course_id'=>$course_class_info['UserCourseClass']['course_id'])));
        $informationresource_infos = $this->InformationResource->information_formated(array('course_comment'), $this->locale);
        $this->set('informationresource_infos', $informationresource_infos);
        $this->set('comment_list', $comment_list);
        $this->set('class_list', $class_list);
        $this->set('course_class_info', $course_class_info);
    }
}