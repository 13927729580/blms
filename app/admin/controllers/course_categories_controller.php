<?php

/*****************************************************************************
 * Seevia 课程分类管理
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
 *这是一个名为 CourseCategoriesController 的控制器
 *课程分类管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class CourseCategoriesController extends AppController
{
    public $name = 'CourseCategories';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('CourseCategory','Route');

    /**
     *显示列表.
     */
    public function index($page = 1)
    {
        $this->operation_return_url(true);
        $this->menu_path = array('root' => '/hr/', 'sub' => '/courses/');
        $this->navigations[] = array('name' => "在线学习", 'url' => '');
        $this->navigations[] = array('name' => "课程管理", 'url' => '/courses/');
        $this->navigations[] = array('name' => "课程分类管理", 'url' => '/course_categories/');
        $condition = '';
        if (isset($_REQUEST['keyword']) && $_REQUEST['keyword'] != '') {
            $condition['or']['CourseCategory.name like'] = '%' . $_REQUEST['keyword'] . '%';
            $condition['or']['CourseCategory.description like'] = '%' . $_REQUEST['keyword'] . '%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        $course_category_list = $this->CourseCategory->find('all', array('conditions' => $condition,'order' => 'CourseCategory.orderby DESC'));
        $course_category_list=$this->CourseCategory->getTree($course_category_list,0);
        $this->set('course_category_list', $course_category_list);
        $this->set('title_for_layout', "课程分类管理" .' - ' . $this->configs['shop_name']);
    }

    /**
     *添加课程分类
     */
    public function add()
    {
        $this->menu_path = array('root' => '/hr/','sub' => '/courses/');
        $this->set('title_for_layout', $this->ld['add'].'-课程管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
        $this->navigations[] = array('name' => "课程分类管理",'url' => '/course_categories/');
        $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        if ($this->RequestHandler->isPost()) {
            $this->CourseCategory->save($this->data);
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
    }

    /**
     *编辑课程分类
     */
    public function view($id)
    {
        if (empty($id)) {

        } else {

            //查找映射路径的内容
            $conditions = array('Route.controller' => 'courses','Route.action' => 'category','Route.model_id' => $id);
            $content = $this->Route->find('first', array('conditions' => $conditions));
            $this->set('routecontent', $content);
        }
        $this->menu_path = array('root' => '/hr/','sub' => '/courses/');
        $this->set('title_for_layout', $this->ld['edit'].'-课程管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
        $this->navigations[] = array('name' => "课程分类管理",'url' => '/course_categories/');
        $this->navigations[] = array('name' => $this->ld['edit'],'url' => '');
        if (!empty($this->data['Route'])) {
            //判断添加的内容是否为空
            $conditions = array('Route.controller' => 'courses','Route.action' => 'category','Route.model_id' => $id);
            $routeurl = $this->Route->find('first', array('conditions' => $conditions));
            $condit = array('Route.url' => $this->data['Route']['url']);//用来判断添加的url不能重复
            $rurl = $this->Route->find('first', array('conditions' => $condit));
            if (empty($rurl)) {
                //判断里面是否添加相同的数据
                if (empty($id)) {
                    if ($routeurl['Route']['url'] != $this->data['Route']['url']) {
                        $this->data['Route']['controller'] = 'courses';
                        $this->data['Route']['url'] = $this->data['Route']['url'];
                        $this->data['Route']['action'] = 'category';
                        $this->data['Route']['model_id'] = $id;
                        $this->Route->save(array('Route' => $this->data['Route']));
                    }
                } else {
                    if ($routeurl['Route']['url'] != $this->data['Route']['url']) {
                        $this->data['Route']['controller'] = 'courses';
                        $this->data['Route']['url'] = $this->data['Route']['url'];
                        $this->data['Route']['action'] = 'category';
                        $this->data['Route']['model_id'] = $id;
                        $this->data['Route']['id'] = $routeurl['Route']['id'];
                        $this->Route->save(array('Route' => $this->data['Route']));
                    }
                }
            }
        }
        if ($this->RequestHandler->isPost()) {
            if (isset($this->data['CourseCategory']['id']) && $this->data['CourseCategory']['id'] != '') {
                $this->CourseCategory->save(array('CourseCategory' => $this->data['CourseCategory'])); //保存主表信息
                $id = $this->data['CourseCategory']['id'];
            } else {
                $this->CourseCategory->saveAll(array('CourseCategory' => $this->data['CourseCategory'])); //保存主表信息
                $id = $this->CourseCategory->getLastInsertId();
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].'课程分类:id '.$id.' '.$this->data['CourseCategory']['name'], $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->data=$this->CourseCategory->find('first',array('conditions'=>array('CourseCategory.id'=>$id)));
        $course_category_list = $this->CourseCategory->find('all');
        $course_category_list=$this->CourseCategory->getTree($course_category_list,0);
        $this->set('categories_tree', $course_category_list);
        $Resource_info = $this->Resource->getformatcode(array('course_category_template','course_tamplate'), $this->backend_locale, false);//资源库信息
        $this->set('Resource_info', $Resource_info);
    }

    /**
     * 删除课程分类
     *
     *@param int $id
     */
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        $this->CourseCategory->deleteAll(array('id' => $id));
        $category_data = $this->CourseCategory->find('all', array('conditions' => array('parent_id' => $id)));
        foreach ($category_data as $k => $v) {
            $this->CourseCategory->save(array('CourseCategory' => array('id' => $v['CourseCategory']['id'], 'parent_id' => 0)));
        }
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_member_success'];
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/course_categories/');
        }
    }
}