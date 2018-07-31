<?php

/*****************************************************************************
 * Seevia 关键字回复管理
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
 *关键字回复管理.
 *
 *对于OpenKeywords这张表的增删改查
 *
 *@author   weizhngye 
 *
 *@version  $Id$
 */
class OpenKeywordsController extends AppController
{
    /*
    *控制器的名字
    */
    public $name = 'OpenKeywords';
    /*
    *引用的助手
    */
    public $helpers = array('Html','Pagination','Tinymce','fck','Form','Javascript','Ckeditor');
    /*
    *引用的组件
    */
    public $components = array('Pagination','RequestHandler','Email','Phpexcel','Phpcsv');
    /*
    *引用的model
    */
    public $uses = array('Profile','ProfileFiled','OpenKeyword','OpenKeywordAnswer','OpenElement','Resource','InformationResource','Template','Template','OperatorLog','OpenKeywordError','OpenModel');

    /**
     *OpenKeywords主页列表.
     *
     *呈现数据库表OpenKeywords的数据
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function index($page = 1)
    {
        //判断权限
        $this->operator_privilege('open_keywords_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/open_model/','sub' => '/open_keywords/');
        //end
        $this->set('title_for_layout', $this->ld['open_call_keywords'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['open_model'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['open_call_keywords'],'url' => '/open_keywords/');
        $conditions = array();
        if (isset($_REQUEST['keywords']) && $_REQUEST['keywords'] != '') {
            $conditions['and']['or']['OpenKeyword.keyword like'] = '%'.$_REQUEST['keywords'].'%';
            $this->set('keywords', $_REQUEST['keywords']);
        }
        if (isset($_REQUEST['openType']) && $_REQUEST['openType'] != '') {
            $conditions['and']['OpenKeyword.open_type'] = $_REQUEST['openType'];
            $this->set('openType', $_REQUEST['openType']);
        }
        if (isset($_REQUEST['open_type_id']) && $_REQUEST['open_type_id'] != '') {
            $conditions['and']['OpenKeyword.open_type_id'] = $_REQUEST['open_type_id'];
            $this->set('open_type_id', $_REQUEST['open_type_id']);
        }
        $cond['conditions'] = $conditions;
        //分页
        $total = $this->OpenKeyword->find('count', $cond);//获取总记录数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
            $page = $_REQUEST['page'];//当前页
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';//默认显示记录数
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'OpenKeyword','action' => 'view','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'OpenKeyword');
        $this->Pagination->init($conditions, $parameters, $options);
        $cond['limit'] = $rownum;
        $cond['page'] = $page;
        $cond['order'] = 'OpenKeyword.created desc';
        $key_list = $this->OpenKeyword->find('all', $cond);
        $this->set('key_list', $key_list);
        $openmodel_list = $this->OpenModel->find('all', array('conditions' => array('status' => 1, 'verify_status' => 1)));
        $this->set('openmodel_list', $openmodel_list);
        $this->Profile->hasOne = array();
           $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'open_keyword_export', 'Profile.status' => 1)));
		$this->set('profile_id',$profile_id);
    }

    /**
     *OpenKeywords修改页和添加页.
     *
     *增加和修改数据库表OpenKeywords的记录
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function view($id = 0)
    {
        //判断权限
        if (empty($id)) {
            $this->operator_privilege('open_keywords_add');
        } else {
            $this->operator_privilege('open_keywords_edit');
        }
        $this->menu_path = array('root' => '/open_model/','sub' => '/open_keywords/');
        $this->set('title_for_layout', $this->ld['add_edit_page'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['open_model'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['open_call_keywords'],'url' => '/open_keywords/');
        $this->navigations[] = array('name' => $this->ld['add_edit_page'],'url' => '/open_keywords/');
        if ($this->RequestHandler->isPost()) {
            $this->OpenKeyword->save($this->data);
            //操作员日志
            //记录更新添加的情况	
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add'].$this->ld['keyword'].':id '.$id, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->data = $this->OpenKeyword->find('first', array('conditions' => array('OpenKeyword.id' => $id)));
        $key_list = array();
        if (!empty($this->data)) {
            $keyword = $this->data['OpenKeyword']['keyword'];//关键字
            $key_list = $this->OpenKeywordAnswer->find('all', array('conditions' => array('OpenKeywordAnswer.keyword_id' => $id), 'order' => 'OpenKeywordAnswer.created desc'));
            foreach ($key_list as $k => $v) {
                //2个都为空不显示
                if (empty($v['OpenKeywordAnswer']['message']) && empty($v['OpenKeywordAnswer']['element_id'])) {
                    unset($key_list[$k]);
                }
                //OpenElement的素材标题
                if (!empty($v['OpenKeywordAnswer']['element_id'])) {
                    $etitle = $this->OpenElement->find('first', array('conditions' => array('OpenElement.id' => $v['OpenKeywordAnswer']['element_id'])));
                    $key_list[$k]['title'] = $etitle['OpenElement']['title'];
                    $key_list[$k]['img'] = $etitle['OpenElement']['media_url'];
                    $key_list[$k]['opid'] = $etitle['OpenElement']['id'];
                    $key_list[$k]['type'] = $etitle['OpenElement']['element_type'];
                }
            }

            //==================回复编辑

            //表情数组
            $Expression = array('/微笑','/撇嘴','/好色','/发呆','/得意','/流泪','/害羞','/睡觉','/尴尬','/呲牙','/惊讶','/冷汗','/抓狂','/偷笑','/可爱','/傲慢','/犯困','/流汗','/大兵','/咒骂','/折磨/','/衰','/擦汗','/抠鼻','/鼓掌','/坏笑','/左哼哼','/右哼哼','/鄙视','/委屈','/阴险','/亲亲','/可怜','/爱情','/飞吻','/怄火','/回头','/献吻','/左太极');
            $this->set('Expression', $Expression);
            //搜索素材管理所有素材
            $material_list = $this->OpenElement->find('all', array('conditions' => array('OpenElement.parent_id' => 0)));
            $this->set('material_list', $material_list);
            $this->set('keyword', $keyword);
        }
        $this->set('key_list', $key_list);
        $this->set('id', $id);

        $openmodel_list = $this->OpenModel->find('all', array('conditions' => array('status' => 1, 'verify_status' => 1)));
        $this->set('openmodel_list', $openmodel_list);
    }

    /**
     *OpenKeyword删除的方法.
     *
     *删除OpenKeyword的记录
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function remove()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        //判断权限
        if (!$this->operator_privilege('open_keywords_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        $id = $_REQUEST['id'];
        if (!isset($id)) {
            $keyword_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
            if ($keyword_ids != 0) {
                foreach ($keyword_ids as $k => $v) {
                    $this->OpenKeywordAnswer->deleteAll(array('OpenKeywordAnswer.keyword_id' => $v));
                    $this->OpenKeyword->deleteAll(array('OpenKeyword.id' => $v));
                }
            }
            //操作员日志
            //记录删除的情况
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除首次关注:id '.$id, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        } else {
            $this->OpenKeywordAnswer->deleteAll(array('OpenKeywordAnswer.keyword_id' => $id));
            $this->OpenKeyword->deleteAll(array('OpenKeyword.id' => $id));
            //操作员日志
            //记录删除的情况
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除关键字回复:id '.$id, $this->admin['id']);
            }
        }
        if (isset($id)) {
            $result['flag'] = 1;
            $result['message'] = $this->ld['deleted_success'];
            die(json_encode($result));
        }
    }

    /**
     *OpenKeyword回复列表删除的方法.
     *
     *删除OpenKeyword的单条记录
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function removeanswer()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        //判断权限
        if (!$this->operator_privilege('open_keywords_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        $id = $_REQUEST['id'];
        if (!isset($id)) {
            $keyword_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
            if ($keyword_ids != 0) {
                foreach ($keyword_ids as $k => $v) {
                    $this->OpenKeywordAnswer->deleteAll(array('id' => $v));
                }
            }
            //操作员日志
            //记录删除的情况
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除关键字:id '.json_encode($keyword_ids), $this->admin['id']);
            }
            $open_keyword_id = $_REQUEST['OpenKeyword_id'];
            $this->redirect('/open_keywords/view/'.$open_keyword_id);
        } else {
            $this->OpenKeywordAnswer->deleteAll(array('OpenKeywordAnswer.id' => $id));
        }
        //操作员日志
        //记录删除的情况
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除关键字:id '.$id, $this->admin['id']);
        }
        if (isset($id)) {
            $result['flag'] = 1;
            $result['message'] = $this->ld['deleted_success'];
            die(json_encode($result));
        }
    }

    public function toggle_on_status()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        //判断权限
        if (!$this->operator_privilege('open_keywords_edit', false)) {
            die(json_encode(array('flag' => 2, 'content' => $this->ld['have_no_operation_perform'])));
        }
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $open_keyword_data['id'] = $id;
        $open_keyword_data['status'] = $val;
        $open_keyword_info = $this->OpenKeyword->find('first', array('fields' => array('OpenKeyword.id', 'OpenKeyword.keyword'), 'conditions' => array('OpenKeyword.id' => $id)));
        $result = array();
        if (!empty($open_keyword_info) && is_numeric($val) && $this->OpenKeyword->save(array('OpenKeyword' => $open_keyword_data))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑关键字['.$open_keyword_info['OpenKeyword']['keyword'].']状态：'.$val.'.'.$val, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *OpenKeyword回复编辑的方法.
     *
     *呈现OpenKeyword的回复记录的增加和编辑
     *id是回复列表的id,sid是回复编辑的id
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function viewanswer($id = 0, $sid = 0)
    {
        if ($this->RequestHandler->isPost()) {
            if ($id != 0) {
                $this->OpenKeywordAnswer->save($this->data);

                $keyworddata = $this->OpenKeyword->find('first', array('conditions' => array('OpenKeyword.id' => $id)));
                if ($keyworddata['OpenKeyword']['keyword'] == '首次关注') {
                    //操作员日志 记录增加和编辑的情况
                    if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                        $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加/编辑首次关注:id '.$sid, $this->admin['id']);
                    }
                    $this->redirect('/open_keywords/view/0/'.$keyworddata['OpenKeyword']['keyword']);
                } else {
                    //操作员日志 记录增加和编辑的情况
                    if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                        $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加/编辑关键字回复:id '.$sid, $this->admin['id']);
                    }
                    $this->redirect('/open_keywords/view/'.$id);
                }
            } else {
                $this->redirect('/open_keywords/');
            }
        }
    }

    /**
     *判断OpenKeyword编辑提交的时候keyword是否重名.
     *
     *ajax方法，去页面判断
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function distinctkeyword()
    {
        $keyword = $_REQUEST['keyword'];
        $id = $_REQUEST['id'];
        if ($id == 0) {
            $results = $this->OpenKeyword->find('all', array('conditions' => array('OpenKeyword.keyword' => $keyword)));
        } else {
            $results = $this->OpenKeyword->find('all', array('conditions' => array('OpenKeyword.keyword' => $keyword, 'OpenKeyword.id !=' => $id)));
        }
        if (!empty($results)) {
            $result['flag'] = 1;//有相同的关键字
        } else {
            $result['flag'] = 2;
        }
        $result['message'] = $this->ld['deleted_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *	关键字回复状态修改.
     *
     *	关键字回复状态修改
     *
     *@author  zhaoyincheng 
     *
     *@version  $Id$
     */
    public function toggle_on_answer_status()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        //判断权限
        if (!$this->operator_privilege('open_keywords_edit', false)) {
            die(json_encode(array('flag' => 2, 'content' => $this->ld['have_no_operation_perform'])));
        }
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $open_keyword_answer_data['id'] = $id;
        $open_keyword_answer_data['status'] = $val;
        $open_keyword_answer_info = $this->OpenKeywordAnswer->find('first', array('fields' => array('OpenKeywordAnswer.id', 'OpenKeywordAnswer.keyword_id', 'OpenKeywordAnswer.message'), 'conditions' => array('OpenKeywordAnswer.id' => $id)));
        $result = array();
        if (!empty($open_keyword_answer_info) && is_numeric($val) && $this->OpenKeywordAnswer->save(array('OpenKeywordAnswer' => $open_keyword_answer_data))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑关键字['.$open_keyword_answer_info['OpenKeywordAnswer']['keyword_id'].'] - ['.$open_keyword_answer_info['OpenKeywordAnswer']['message'].'] 回复状态：'.$val.'.'.$val, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *	未搜索到的关键字列表.
     *
     *	显示未搜索到的关键字
     *
     *@author   zhaoyincheng 
     *
     *@version  $Id$
     */
    public function nottosearchkeyword($page = 1)
    {
        //判断权限
        $this->operator_privilege('open_keywords_error');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/open_model/','sub' => '/open_keywords/nottosearchkeyword');
        //end
        $this->set('title_for_layout', $this->ld['not_to_search_keywords'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['open_model'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['not_to_search_keywords'],'url' => '');
        $condition = array();
        $selectstatus = '';
        if (isset($_REQUEST['keywords']) && $_REQUEST['keywords'] != '') {
            $condition['and']['or']['OpenKeywordError.keyword like'] = '%'.$_REQUEST['keywords'].'%';
            $this->set('keywords', $_REQUEST['keywords']);
        }
        if (isset($_REQUEST['selectstatus']) && $_REQUEST['selectstatus'] != '') {
            $condition['and']['OpenKeywordError.status'] = $_REQUEST['selectstatus'];
            $selectstatus = $_REQUEST['selectstatus'];
        }
        $this->set('selectstatus', $selectstatus);
        if (isset($this->params['url']['start_date']) && $this->params['url']['start_date'] != '') {
            $condition['and']['OpenKeywordError.created >='] = $this->params['url']['start_date'].' 00:00:00';
            $start_date = $this->params['url']['start_date'];
            $this->set('start_date', $start_date);
        }
        if (isset($this->params['url']['end_date']) && $this->params['url']['end_date'] != '') {
            $condition['and']['OpenKeywordError.created <='] = $this->params['url']['end_date'].' 23:59:59';
            $end_date = $this->params['url']['end_date'];
            $this->set('end_date', $end_date);
        }
        $total = $this->OpenKeywordError->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'open_keywords','action' => 'nottosearchkeyword','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'OpenKeywordError');
        $this->Pagination->init($condition, $parameters, $options);
        $joins = array(
            array('table' => 'svsns_open_users',
                  'alias' => 'OpenUser',
                  'type' => 'inner',
                  'conditions' => array('OpenUser.id = OpenKeywordError.open_user_id'),
                 ), );
        $fields = array('OpenKeywordError.*','OpenUser.nickname','OpenUser.id');
        $open_keyword_list = $this->OpenKeywordError->find('all', array('conditions' => $condition, 'fields' => $fields, 'joins' => $joins, 'order' => 'OpenKeywordError.created desc', 'limit' => $rownum, 'page' => $page));
        $this->set('open_keyword_list', $open_keyword_list);
    }

    public function removesearchkeyword()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        //判断权限
        if (!$this->operator_privilege('delete_open_keywords_error', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        $this->OpenKeywordError->delete(array('OpenKeywordError.id' => $id));
        //操作员日志
        //记录删除的情况
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除未回复关键字:id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        die(json_encode($result));
    }

    public function removesearchkeywordAll()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        //判断权限
        if (!$this->operator_privilege('delete_open_keywords_error', false)) {
            $this->redirect('/open_keywords/nottosearchkeyword/');
        }
        $keyword_ids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        $this->OpenKeywordError->deleteAll(array('OpenKeywordError.id' => $keyword_ids));
        //操作员日志
        //记录删除的情况
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除未回复关键字:id '.json_encode($keyword_ids), $this->admin['id']);
        }
        $this->redirect('/open_keywords/nottosearchkeyword/');
    }
       //关键字管理上传
public function open_keyword_upload(){
	  Configure::write('debug', 0);
        $this->operation_return_url(true);//设置操作返回页面地址

          $this->menu_path = array('root' => '/open_model/','sub' => '/open_keywords/');
        $this->set('title_for_layout', $this->ld['add_edit_page'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['open_model'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['open_call_keywords'],'url' => '/open_keywords/');
           $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
           $this->set('title_for_layout', $this->ld['menu_manage'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
           $this->Profile->hasOne = array();
           $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'open_keyword_export', 'Profile.status' => 1)));
		$this->set('profile_id',$profile_id);
	
    }



//菜单管理cvs查看
 public function open_keyword_uploadpreview()
    {
    	Configure::write('debug', 1);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/open_keywords/open_keyword_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'open_keyword_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
                            $key_arr = array();
                            foreach($fields_array as $k=>$v){
                            	$key_arr[] = $v;
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                if($row_count!=$num_count){
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/open_keywords/open_keyword_upload';</script>";
						die();
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/open_keywords/open_keyword_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            //pr($fields);pr($key_arr);die();
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
			$upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        
                        if( $v['OpenKeyword']['open_type']!="" && $v['OpenKeyword']['keyword']!="" && $v['OpenKeyword']['open_type_id']!="" ){
                        $OpenKeyword_condition='';
                        	$OpenKeyword_condition['OpenKeyword.open_type']=$v['OpenKeyword']['open_type'];
                        	$OpenKeyword_condition['OpenKeyword.keyword']=$v['OpenKeyword']['keyword'];
                        $OpenKeyword_condition['OpenKeyword.open_type_id']=$v['OpenKeyword']['open_type_id'];
                        $OpenKeyword_first = $this->OpenKeyword->find('first', array('conditions' =>$OpenKeyword_condition));
                        $v['OpenKeyword']['id']=isset($OpenKeyword_first['OpenKeyword']['id'])?$OpenKeyword_first['OpenKeyword']['id']:0;
                        $v['OpenKeyword']['match_type']=isset($v['OpenKeyword']['match_type'])?$v['OpenKeyword']['match_type']:0;
                        $v['OpenKeyword']['status']=isset($v['OpenKeyword']['status'])?$v['OpenKeyword']['status']:1;
                         if($s1=$this->OpenKeyword->save($v['OpenKeyword'])){
                         	$OpenKeyword_id=$this->OpenKeyword->id;
                         }
                         $OpenKeywordAnswer_condition='';
                         if(isset($OpenKeyword_id)){
                         	$OpenKeywordAnswer_condition['OpenKeywordAnswer.keyword_id']=$OpenKeyword_id;
                         }
                          if(isset($v['OpenKeywordAnswer']['message'])){
                         	$OpenKeywordAnswer_condition['OpenKeywordAnswer.message']=$v['OpenKeywordAnswer']['message'];
                         }
                        $OpenKeywordAnswer_first = $this->OpenKeywordAnswer->find('first', array('conditions' =>$OpenKeywordAnswer_condition));
                        $v['OpenKeywordAnswer']['id']=isset($OpenKeywordAnswer_first['OpenKeywordAnswer']['id'])?$OpenKeywordAnswer_first['OpenKeywordAnswer']['id']:0;
                        $v['OpenKeywordAnswer']['keyword_id']=isset($OpenKeyword_id)?$OpenKeyword_id:'';
                        $s2=$this->OpenKeywordAnswer->save($v['OpenKeywordAnswer']);
                        	 if( isset($s1) && !empty($s1) && isset($s2) && !empty($s2) ){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                    }
                    }
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/open_keywords/'</script>";
		            die();
                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/open_keywords/open_keyword_upload/'</script>";
                    	
                }
         
    }

      /////////////////////////////////////////////
      public function fgetcsv_reg($handle, $length = null, $d = ',', $e = '"')
      {
          $d = preg_quote($d);
          $e = preg_quote($e);
          $_line = '';
          $eof = false;
          while ($eof != true) {
              $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
              $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
              if ($itemcnt % 2 == 0) {
                  $eof = true;
              }
          }
          $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
          $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
          preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
          $_csv_data = $_csv_matches[1];
          for ($_csv_i = 0; $_csv_i < count($_csv_data); ++$_csv_i) {
              $_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s', '$1', $_csv_data[$_csv_i]);
              $_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
          }

          return empty($_line) ? false : $_csv_data;
      }



		 
//菜单管理csv
public function download_open_keyword_csv_example($out_type = 'OpenKeyword'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     //$this->OpenKeyword->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'open_keyword_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
  	//pr($tmp);pr($fields_array);die();
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          //$OpenKeyword_info = $this->OpenKeyword->find('all', array('fields'=>array('OpenKeyword.open_type','OpenKeyword.open_type_id','OpenKeyword.keyword','OpenKeyword.match_type','OpenKeyword.status'),'order' => 'OpenKeyword.id desc','limit'=>10));
          //pr($OpenKeyword_id_info);
	   $OpenKeyword_info = $this->OpenKeyword->find('all', array('fields'=>array('OpenKeyword.id','OpenKeyword.open_type','OpenKeyword.open_type_id','OpenKeyword.keyword','OpenKeyword.match_type','OpenKeyword.status'),'order'=>'OpenKeyword.id desc','limit'=>10));
           $new_open_keyword_info=array();
          	foreach($OpenKeyword_info as $k =>$v){
          			$OpenKeywordAnswer_info=$this->OpenKeywordAnswer->find('all',array('fields'=>array('OpenKeywordAnswer.msgtype','OpenKeywordAnswer.message','OpenKeywordAnswer.status'),'conditions'=>array('OpenKeywordAnswer.keyword_id'=>$v['OpenKeyword']['id'])));
          		if( sizeof($OpenKeywordAnswer_info)>0 ){
          			foreach($OpenKeywordAnswer_info as $kk=>$vv){
          				$new_open_keyword_info[$k][$kk]['OpenKeywordAnswer']=$vv['OpenKeywordAnswer'];
          				$new_open_keyword_info[$k][$kk]['OpenKeyword']=$v['OpenKeyword'];
          			}
          		}else{
          			$new_open_keyword_info[$k][0]['OpenKeyword']=$v['OpenKeyword'];
          		
          		}
          	
          	}
          	//pr($OpenKeywordAnswer_info);die();
              //循环数组
              foreach($new_open_keyword_info as $keys=>$vals){
              foreach($vals as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                 
                                         $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]])?$v[$fields_ks[0]][$fields_ks[1]]:'';
	                  
	               
	              }
	              //pr($user_tmp);die();
	              $newdatas[] = $user_tmp;
	          }
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}
//全部导出   
public function all_export_csv($out_type = 'OpenKeyword'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'open_keyword_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
  //	pr($tmp);
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          //$OpenKeyword_info = $this->OpenKeyword->find('all', array('fields'=>array('OpenKeyword.open_type','OpenKeyword.open_type_id','OpenKeyword.keyword','OpenKeyword.match_type','OpenKeyword.status'),'order' => 'OpenKeyword.id desc'));
		//pr($OperatorRole_info);die();
	       $OpenKeyword_info = $this->OpenKeyword->find('all', array('fields'=>array('OpenKeyword.id','OpenKeyword.open_type','OpenKeyword.open_type_id','OpenKeyword.keyword','OpenKeyword.match_type','OpenKeyword.status'),'order'=>'OpenKeyword.id desc'));
           $new_open_keyword_info=array();
          	foreach($OpenKeyword_info as $k =>$v){
          			$OpenKeywordAnswer_info=$this->OpenKeywordAnswer->find('all',array('fields'=>array('OpenKeywordAnswer.msgtype','OpenKeywordAnswer.message','OpenKeywordAnswer.status'),'conditions'=>array('OpenKeywordAnswer.keyword_id'=>$v['OpenKeyword']['id'])));
          		if( sizeof($OpenKeywordAnswer_info)>0 ){
          			foreach($OpenKeywordAnswer_info as $kk=>$vv){
          				$new_open_keyword_info[$k][$kk]['OpenKeywordAnswer']=$vv['OpenKeywordAnswer'];
          				$new_open_keyword_info[$k][$kk]['OpenKeyword']=$v['OpenKeyword'];
          			}
          		}else{
          			$new_open_keyword_info[$k][0]['OpenKeyword']=$v['OpenKeyword'];
          		
          		}
          	
          	}
            //pr();die();
              //循环数组
              foreach($new_open_keyword_info as $keys=>$vals){
              foreach($vals as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	               
                          $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';

	              }
	         
	              //pr($user_tmp);die();
	              $newdatas[] = $user_tmp;
	         }
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
        	exit;
      
}  
    
 //选择导出   
public function choice_export($out_type = 'OpenKeyword'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     //$this->OpenKeyword->set_locale($this->backend_locale);
     $user_checkboxes = $_REQUEST['checkboxes'];
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'open_keyword_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
  //	pr($tmp);
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $OpenKeyword_conditions['AND']['OpenKeyword.id']=$user_checkboxes; 
	$OpenKeyword_info = $this->OpenKeyword->find('all', array('fields'=>array('OpenKeyword.id','OpenKeyword.open_type','OpenKeyword.open_type_id','OpenKeyword.keyword','OpenKeyword.match_type','OpenKeyword.status'),'conditions'=>$OpenKeyword_conditions,'order'=>'OpenKeyword.id desc'));
          $new_open_keyword_info=array();
          	foreach($OpenKeyword_info as $k =>$v){
          			$OpenKeywordAnswer_info=$this->OpenKeywordAnswer->find('all',array('fields'=>array('OpenKeywordAnswer.msgtype','OpenKeywordAnswer.message','OpenKeywordAnswer.status'),'conditions'=>array('OpenKeywordAnswer.keyword_id'=>$v['OpenKeyword']['id'])));
          		if( sizeof($OpenKeywordAnswer_info)>0 ){
          			foreach($OpenKeywordAnswer_info as $kk=>$vv){
          				$new_open_keyword_info[$k][$kk]['OpenKeywordAnswer']=$vv['OpenKeywordAnswer'];
          				$new_open_keyword_info[$k][$kk]['OpenKeyword']=$v['OpenKeyword'];
          			}
          		}else{
          			$new_open_keyword_info[$k][0]['OpenKeyword']=$v['OpenKeyword'];
          		}
          		
          	}		//pr($OperatorRole_info);die();

            //pr($new_open_keyword_info);die();
              //循环数组
              foreach($new_open_keyword_info as $keys=>$vals){
              foreach($vals as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
                                         $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
	              }
	              //pr($user_tmp);die();
	              $newdatas[] = $user_tmp;
          }
	   }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
        	exit;
      
}  
    
    
}
