<?php

/*****************************************************************************
 * Seevia 评测分类管理
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
 *这是一个名为 EvaluationCategoriesController 的控制器
 *评测分类管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class EvaluationCategoriesController extends AppController
{
    public $name = 'EvaluationCategories';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('EvaluationCategory');

    /**
     *显示列表.
     */
    public function index($page = 1)
    {
        $this->operation_return_url(true);
        $this->menu_path = array('root' => '/hr/', 'sub' => '/evaluations/');
        $this->navigations[] = array('name' => "在线学习", 'url' => '');
        $this->navigations[] = array('name' => "评测管理", 'url' => '/evaluations/');
        $this->navigations[] = array('name' => "评测分类管理", 'url' => '/evaluation_categories/');
        $condition = '';
        if (isset($_REQUEST['keyword']) && $_REQUEST['keyword'] != '') {
            $condition['or']['EvaluationCategory.name like'] = '%' . $_REQUEST['keyword'] . '%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        $total = $this->EvaluationCategory->find('count', array('conditions' => $condition));
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int)$this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'evaluation_categories', 'action' => 'index', 'page' => $page, 'limit' => $rownum);
        $options = array('page' => $page, 'show' => $rownum, 'total' => $total, 'modelClass' => 'EvaluationCategory');
        $this->Pagination->init($condition, $parameters, $options);
        $evaluation_categories_list = $this->EvaluationCategory->find('all', array('conditions' => $condition, 'page' => $page,'limit' => $rownum));
        $this->set('evaluation_categories_list', $evaluation_categories_list);
        $this->set('title_for_layout', "评测分类管理" . ' - ' . $this->ld['page'] . ' ' . $page . ' - ' . $this->configs['shop_name']);
    }

    /**
     *添加评测分类
     */
    public function add()
    {
        $this->menu_path = array('root' => '/hr/','sub' => '/evaluations/');
        $this->set('title_for_layout', $this->ld['add'].'-评测管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "评测管理",'url' => '/evaluations/');
        $this->navigations[] = array('name' => "评测分类管理",'url' => '/evaluation_categories/');
        $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        if ($this->RequestHandler->isPost()) {
            $this->EvaluationCategory->save($this->data);
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
    }

    /**
     *编辑评测分类
     */
    public function view($id)
    {
        $this->menu_path = array('root' => '/hr/','sub' => '/evaluations/');
        $this->set('title_for_layout', $this->ld['edit'].'-评测管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "评测管理",'url' => '/evaluations/');
        $this->navigations[] = array('name' => "评测分类管理",'url' => '/evaluation_categories/');
        $this->navigations[] = array('name' => $this->ld['edit'],'url' => '');
        $evaluation_categories_info=$this->EvaluationCategory->find('first',array('conditions'=>array('EvaluationCategory.id'=>$id)));
        if ($this->RequestHandler->isPost()) {
            $this->EvaluationCategory->save($this->data);
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->set('evaluation_categories_info', $evaluation_categories_info);
    }

    /**
     * 删除评测分类
     *
     *@param int $id
     */
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        $this->EvaluationCategory->deleteAll(array('id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_member_success'];
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/evaluation_categories/');
        }
    }
}