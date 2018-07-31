<?php

/*****************************************************************************
 * Seevia 课程类型管理
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
 *这是一个名为 CourseTypesController 的控制器
 *课程类型管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class CourseTypesController extends AppController
{
    public $name = 'CourseTypes';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('CourseType');

    /**
     *显示列表.
     */
    public function index($page = 1)
    {
        $this->operation_return_url(true);
        $this->menu_path = array('root' => '/hr/', 'sub' => '/courses/');
        $this->navigations[] = array('name' => "在线学习", 'url' => '');
        $this->navigations[] = array('name' => "课程管理", 'url' => '/courses/');
        $this->navigations[] = array('name' => "课程类型管理", 'url' => '/course_types/');
        $condition = '';
        if (isset($_REQUEST['keyword']) && $_REQUEST['keyword'] != '') {
            $condition['or']['CourseType.name like'] = '%' . $_REQUEST['keyword'] . '%';
            $condition['or']['CourseType.description like'] = '%' . $_REQUEST['keyword'] . '%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        $total = $this->CourseType->find('count', array('conditions' => $condition));
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int)$this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'course_types', 'action' => 'index', 'page' => $page, 'limit' => $rownum);
        $options = array('page' => $page, 'show' => $rownum, 'total' => $total, 'modelClass' => 'CourseType');
        $this->Pagination->init($condition, $parameters, $options);
        $course_type_list = $this->CourseType->find('all', array('conditions' => $condition, 'page' => $page));
        $this->set('course_type_list', $course_type_list);
        $this->set('title_for_layout', "课程类型管理" . ' - ' . $this->ld['page'] . ' ' . $page . ' - ' . $this->configs['shop_name']);
    }

    /**
     *添加课程类型
     */
    public function add()
    {
        $this->menu_path = array('root' => '/hr/','sub' => '/courses/');
        $this->set('title_for_layout', $this->ld['add'].'-课程管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
        $this->navigations[] = array('name' => "课程类型管理",'url' => '/course_types/');
        $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        if ($this->RequestHandler->isPost()) {
            $this->CourseType->save($this->data);
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
    }

    /**
     *编辑课程类型
     */
    public function view($id)
    {
        $this->menu_path = array('root' => '/hr/','sub' => '/courses/');
        $this->set('title_for_layout', $this->ld['edit'].'-课程管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
        $this->navigations[] = array('name' => "课程类型管理",'url' => '/course_types/');
        $this->navigations[] = array('name' => $this->ld['edit'],'url' => '');
        $course_type_info=$this->CourseType->find('first',array('conditions'=>array('CourseType.id'=>$id)));
        if ($this->RequestHandler->isPost()) {
            $this->CourseType->save($this->data);
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->set('course_type_info', $course_type_info);
    }

    /**
     * 删除课程类型
     *
     *@param int $id
     */
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        $this->CourseType->deleteAll(array('id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_member_success'];
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/course_types/');
        }
    }
    
        /**
     * 检查code
     *
     */
    public function check_code()
    {
    	Configure::write('debug', 1);
        $result['code'] = 0;
        $result['msg'] = '';
        if ($this->RequestHandler->isPost()) {
            $code = isset($_POST['code']) ? $_POST['code'] : '';
            $evaluation_count = $this->CourseType->find('count', array('conditions' => array('CourseType.code' => $code, 'CourseType.status' => "1")));
            if ($evaluation_count == 0) {
                $result['code'] = 1;
            } else {
                $result['msg'] = "code已存在";
            }
            die(json_encode($result));
        } else {
            $this->redirect('/course_types');
        }
    }
}