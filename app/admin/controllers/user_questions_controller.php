<?php

/*****************************************************************************
 * Seevia 用户题库管理
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
 *这是一个名为 UserQuestionsController 的控制器
 *用户题库管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class UserQuestionsController extends AppController
{
    public $name = 'UserQuestions';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('UserQuestion','EvaluationQuestion','EvaluationOption','User','UserQuestionOption');

    /**
     *显示列表.
     */
    public function index($page = 1)
    {
    	 $this->operator_privilege('user_question_import');
        $this->operation_return_url(true);
        $this->menu_path = array('root' => '/hr/', 'sub' => '/user_questions/');
        $this->navigations[] = array('name' => "在线学习", 'url' => '');
        $this->navigations[] = array('name' => "会员提交题库", 'url' => '/user_questions/');
        $condition = '';
        $question_type="-1";
        $status="-1";
        if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
            $condition['or']['UserQuestion.name like'] = '%' . $_REQUEST['keyword'] . '%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        if (isset($this->params['url']['user_name']) && $this->params['url']['user_name'] != '') {
            $user_condition['or']['User.name like'] = '%'.$_REQUEST['user_name'] .'%';
            $user_ids=$this->User->find('list',array('fields'=>"User.id","conditions"=>$user_condition));
            $condition['and']['UserQuestion.user_id'] = $user_ids;
            $this->set('user_name', $_REQUEST['user_name']);
        }
        if (isset($this->params['url']['question_type']) && $this->params['url']['question_type'] != '-1') {
            $condition['and']['UserQuestion.question_type'] = $this->params['url']['question_type'];
            $question_type = $this->params['url']['question_type'];
        }
        if (isset($this->params['url']['tag']) && $this->params['url']['tag'] != '') {
            $condition['and']['UserQuestion.tag like'] = '%'.$this->params['url']['tag'].'%';
            $tag = $this->params['url']['tag'];
            $this->set('tag', $_REQUEST['tag']);
        }
        if (isset($this->params['url']['status']) && $this->params['url']['status'] != '-1') {
            $condition['and']['UserQuestion.status'] = $this->params['url']['status'];
            $status = $this->params['url']['status'];
        }
        if (isset($this->params['url']['start_date_time']) && $this->params['url']['start_date_time'] != '') {
            $condition['and']['UserQuestion.modified >='] = $this->params['url']['start_date_time'].' 00:00:00';
            $start_date_time = $this->params['url']['start_date_time'];
            $this->set('start_date_time', $start_date_time);
        }
        if (isset($this->params['url']['end_date_time']) && $this->params['url']['end_date_time'] != '') {
            $condition['and']['UserQuestion.modified <='] = $this->params['url']['end_date_time'].' 23:59:59';
            $end_date_time = $this->params['url']['end_date_time'];
            $this->set('end_date_time', $end_date_time);
        }
        $total = $this->UserQuestion->find('count', array('conditions' => $condition));
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int)$this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'user_questions', 'action' => 'index', 'page' => $page, 'limit' => $rownum);
        $options = array('page' => $page, 'show' => $rownum, 'total' => $total, 'modelClass' => 'UserQuestion');
        $this->Pagination->init($condition, $parameters, $options);
        $question_list = $this->UserQuestion->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum,'order' => 'created desc,id desc'));
        $this->set('question_list', $question_list);
        $this->set('question_type', $question_type);
        $this->set('status', $status);
        $this->set('title_for_layout', "会员提交题库" . ' - ' . $this->ld['page'] . ' ' . $page . ' - ' . $this->configs['shop_name']);
    }

    /**
     *查看详情
     */
    public function view($id)
    {
    	 $this->operator_privilege('user_question_import');
        $this->menu_path = array('root' => '/hr/','sub' => '/user_questions/');
        $this->set('title_for_layout', $this->ld['view'].'-会员提交题库- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "会员提交题库",'url' => '/user_questions/');
        $this->navigations[] = array('name' => $this->ld['view'],'url' => '');
        $user_question_info=$this->UserQuestion->find('first',array('conditions'=>array('UserQuestion.id'=>$id)));
        $this->set('user_question_info', $user_question_info);
    }

    /**
     * 批量取消
     *
     */
    public function changeStatus()
    {
        Configure::write('debug', 1);
        $result['code'] = 0;
        $result['msg'] = '系统错误';
        if ($this->RequestHandler->isPost()&&$this->operator_privilege('user_question_import',false)) {
            $ids = isset($_POST['ids']) ? $_POST['ids'] :0;
            $this->UserQuestion->updateAll(array('status' => '0'), array('id' => $ids));
            $this->EvaluationQuestion->deleteAll(array('code' => $ids));
            $this->EvaluationOption->deleteAll(array('evaluation_question_code' => $ids));
            $result['msg'] = "修改成功";
            die(json_encode($result));
        } else {
            $this->redirect('/user_questions');
        }
    }

    /**
     * 批量删除
     *
     */
    public function delete_all()
    {
        Configure::write('debug', 1);
        $result['code'] = 0;
        $result['msg'] = '系统错误';
        if ($this->RequestHandler->isPost()&&$this->operator_privilege('user_question_import',false)) {
            $ids = isset($_POST['ids']) ? $_POST['ids'] :0;
            $this->UserQuestion->deleteAll(array('id' => $ids));
            $this->UserQuestionOption->deleteAll(array('user_question_id' => $ids));
            $result['msg'] = "删除成功";
            die(json_encode($result));
        } else {
            $this->redirect('/user_questions');
        }
    }

    /**
     * 批量导入题库
     *
     */
    public function set_question()
    {
        Configure::write('debug', 1);
        $result['code'] = 0;
        $res_count=0;
        $result['msg'] = '';
        if ($this->RequestHandler->isPost()&&$this->operator_privilege('user_question_import',false)) {
            $ids = $_POST['ids'];
            $data["EvaluationQuestion"]["evaluation_code"]=$_POST['evaluation_code'];
            $qusetion_list=$this->UserQuestion->find('all',array('conditions'=>array('UserQuestion.id'=>$ids)));
            foreach($qusetion_list as $k=>$v){
                $qusetion_one=$this->EvaluationQuestion->find('first',array('conditions'=>array('EvaluationQuestion.code'=>$v["UserQuestion"]["id"])));
                if(empty($qusetion_one) && $v["UserQuestion"]["status"]==0){
                    $data["EvaluationQuestion"]["code"]=$v["UserQuestion"]["id"];
                    $option_data["EvaluationOption"]["evaluation_question_code"]=$v["UserQuestion"]["id"];
                    $data["EvaluationQuestion"]["name"]=$v["UserQuestion"]["name"];
                    $data["EvaluationQuestion"]["question_type"]=$v["UserQuestion"]["question_type"];
                    $data["EvaluationQuestion"]["right_answer"]=$v["UserQuestion"]["right_answer"];
                    $data["EvaluationQuestion"]["analyze"]=$v["UserQuestion"]["analyze"];
                    $data["EvaluationQuestion"]["status"]=1;
                    $data["EvaluationQuestion"]["create_by"]=1;
                    $data["EvaluationQuestion"]["create_by_id"]=$v["UserQuestion"]["user_id"];
                    $v["UserQuestion"]["status"]=1;
                    $this->UserQuestion->saveAll($v);
                    $this->EvaluationQuestion->saveAll($data);
                    foreach($v["UserQuestionOption"] as $kk=>$vv){
                        $option_data["EvaluationOption"]["name"]=$vv["name"];
                        $option_data["EvaluationOption"]["description"]=$vv["description"];
                        $option_data["EvaluationOption"]["status"]=1;
                        $option_data["EvaluationOption"]["orderby"]=$vv["orderby"];
                        $this->EvaluationOption->saveAll($option_data);
                    }
            		$res_count++;
                }else{
                    $message=$v["UserQuestion"]["id"]."已存在。";
                    $result['msg'].=$message;
                    continue;
                }
            }
            $result['msg']="导入成功".$res_count."条。".$result['msg'];
            die(json_encode($result));
        } else {
            $this->redirect('index');
        }
    }
}