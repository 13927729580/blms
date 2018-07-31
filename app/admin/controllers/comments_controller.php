<?php

/*****************************************************************************
 * Seevia 评论查询
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
class CommentsController extends AppController
{
    public $name = 'Comments';

    public $components = array('Pagination','RequestHandler','Email'); // Added 
    public $helpers = array('Pagination','Javascript'); // Added 
    public $uses = array('Comment','UserRank','User','ProductType','OperatorLog');

    public function index()
    {
        /*判断权限*/
        $this->operator_privilege('comments_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/crm/','sub' => '/comments/');
        /*end*/
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['reviews_search'],'url' => '/comments/');

        $condition = '';
        if (isset($this->params['url']['ctype']) && $this->params['url']['ctype'] != '') {
            $condition['Comment.type'] = $this->params['url']['ctype'];
            $this->set('ctype', $this->params['url']['ctype']);
        }
        if (isset($this->params['url']['content']) && $this->params['url']['content'] != '') {
            $condition['Comment.content LIKE'] = '%'.$this->params['url']['content'].'%';
//            pr($condition);
//            $cond['or']['User.name like'] = '%'.$this->params['url']['content'].'%';
//            $cond['or']['User.email like'] = '%'.$this->params['url']['content'].'%';
//            $cond['or']['User.first_name like'] = '%'.$this->params['url']['content'].'%';
//            $cond['or']['User.last_name like'] = '%'.$this->params['url']['content'].'%';
//            $user_ids = $this->User->find('list', array('fields' => 'User.id', 'conditions' => $cond));
//            $condition['or']['Comment.user_id'] = $user_ids;
            $this->set('content', $this->params['url']['content']);
            //pr($cond);
        }
        if (isset($this->params['url']['cstatus']) && $this->params['url']['cstatus'] != '') {
            $condition['Comment.status'] = $this->params['url']['cstatus'];
            $this->set('cstatus', $this->params['url']['cstatus']);
        }
        if (isset($this->params['url']['end_time']) && $this->params['url']['end_time'] != '') {
            $condition['Comment.created <'] = $this->params['url']['end_time'];
            $this->set('end_time', $this->params['url']['end_time']);
        }

        if (isset($this->params['url']['start_time']) && $this->params['url']['start_time'] != '') {
            $condition['Comment.created >'] = $this->params['url']['start_time'];
            $this->set('start_time', $this->params['url']['start_time']);
        }
        $condition['Comment.parent_id'] = 0;
        $total = $this->Comment->find('count', array('conditions' => $condition));

        $sortClass = 'Comment';
        $page = 1;
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['route'] = array('controller' => 'comments','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Comment');
        $this->Pagination->init($condition, $parameters, $options);
        //pr($condition);
        $this->data = $this->Comment->find('all', array('conditions' => $condition, 'order' => 'Comment.created desc', 'limit' => $rownum, 'page' => $page));
        //pr($this->data);
        $user_ids = array();
        $product_ids = array();
        $article_ids = array();
        $topic_ids=array();
        $product_category_ids=array();
        $page_ids=array();
        foreach ($this->data as $k => $v) {
			$user_ids[] = $v['Comment']['user_id'];
			if ($v['Comment']['type'] == 'P')$product_ids[] = $v['Comment']['type_id'];
			if ($v['Comment']['type'] == 'A')$article_ids[] = $v['Comment']['type_id'];
			if ($v['Comment']['type'] == 'T')$topic_ids[] = $v['Comment']['type_id'];
			if ($v['Comment']['type'] == 'CP')$product_category_ids[] = $v['Comment']['type_id'];
			if ($v['Comment']['type'] == 'SP')$page_ids[] = $v['Comment']['type_id'];
        }
        if(!empty($product_ids)){
        	 	$this->loadModel('ProductI18n');
	        	$product_list = $this->ProductI18n->find('list', array('conditions' =>array('ProductI18n.product_id'=>$product_ids,'ProductI18n.locale'=>$this->backend_locale,'ProductI18n.name <>'=>''), 'fields' => array('ProductI18n.product_id','ProductI18n.name')));
        }
        if(!empty($article_ids)){
        	 	$this->loadModel('ArticleI18n');
	        	$article_list = $this->ArticleI18n->find('list', array('conditions' =>array('ArticleI18n.article_id'=>$article_ids,'ArticleI18n.locale'=>$this->backend_locale,'ArticleI18n.title <>'=>''), 'fields' => array('ArticleI18n.article_id','ArticleI18n.title')));
        }
        if(!empty($topic_ids)){
        	 	$this->loadModel('TopicI18n');
	        	$topic_list = $this->TopicI18n->find('list', array('conditions' =>array('TopicI18n.topic_id'=>$topic_ids,'TopicI18n.locale'=>$this->backend_locale,'TopicI18n.title <>'=>''), 'fields' => array('TopicI18n.topic_id','TopicI18n.title')));
        }
        if(!empty($product_category_ids)){
        	 	$this->loadModel('CategoryProductI18n');
	        	$product_catgegory_list = $this->CategoryProductI18n->find('list', array('conditions' =>array('CategoryProductI18n.category_id'=>$topic_ids,'CategoryProductI18n.locale'=>$this->backend_locale,'CategoryProductI18n.name <>'=>''), 'fields' => array('CategoryProductI18n.category_id','CategoryProductI18n.name')));
        }
        if(!empty($page_ids)){
        	 	$this->loadModel('PageI18n');
	        	$page_list = $this->PageI18n->find('list', array('conditions' =>array('PageI18n.page_id'=>$page_ids,'PageI18n.locale'=>$this->backend_locale,'PageI18n.title <>'=>''), 'fields' => array('PageI18n.page_id','PageI18n.title')));
        }
        $user_list = $this->User->find('list', array('fields' => array('User.id', 'User.name'), 'conditions' => array('User.id' => $user_ids)));
        foreach ($this->data as $k => $v) {
	            $this->data[$k]['Comment']['object'] = '';
	            if ($v['Comment']['type'] == 'P') {
	                	$this->data[$k]['Comment']['type_name'] = $this->ld['product'];
	                	$this->data[$k]['Comment']['object'] = isset($product_list[$v['Comment']['type_id']]) ? $product_list[$v['Comment']['type_id']] : '';
	            }
	            if ($v['Comment']['type'] == 'A') {
	                	$this->data[$k]['Comment']['type_name'] = $this->ld['article'];
	                	$this->data[$k]['Comment']['object'] = isset($article_list[$v['Comment']['type_id']]) ? $article_list[$v['Comment']['type_id']] : '';
	            }
	            if ($v['Comment']['type'] == 'T') {
	                	$this->data[$k]['Comment']['type_name'] = $this->ld['topics'];
	                	$this->data[$k]['Comment']['object'] = isset($topic_list[$v['Comment']['type_id']]) ? $topic_list[$v['Comment']['type_id']] : '';
	            }
	            if ($v['Comment']['type'] == 'CP') {
	                	$this->data[$k]['Comment']['type_name'] = $this->ld['category_product'];
	                	$this->data[$k]['Comment']['object'] = isset($product_catgegory_list[$v['Comment']['type_id']]) ? $product_catgegory_list[$v['Comment']['type_id']] : '';
	            }
	            if ($v['Comment']['type'] == 'SP') {
	                	$this->data[$k]['Comment']['type_name'] = $this->ld['category_product'];
	                	$this->data[$k]['Comment']['object'] = isset($page_list[$v['Comment']['type_id']]) ? $page_list[$v['Comment']['type_id']] : '';
	            }
	            if ($v['Comment']['type'] == 'AT') {
	                	$this->data[$k]['Comment']['type_name'] = $this->ld['activity'];
	                	$this->data[$k]['Comment']['object'] = '';
	            }
        }
        $this->set('user_list', $user_list);
        $this->set('comments_info', $this->data);
        $this->set('title_for_layout', $this->ld['reviews_search'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_message_failure'];
        $this->Comment->deleteAll(array('id' => $id));
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_message_success'];
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_product_comment'].':id '.$id, $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //空方法 未调用
    public function searchremove($id)
    {
        $this->Comment->deleteAll("Comment.id='".$id."'");
    }

    public function edit($id)
    {
        /*判断权限*/
        $this->operator_privilege('comments_edit');
        $this->menu_path = array('root' => '/crm/','sub' => '/comments/');
        /*end*/
        $this->set('title_for_layout', $this->ld['reply_comments'].' - '.$this->ld['reviews_search'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['reviews_search'],'url' => '/comments/');
        $this->navigations[] = array('name' => $this->ld['reply_comments'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            if ($this->data['Comment']['content'] != '') {
                $this->Comment->deleteAll(array('Comment.parent_id' => $id));
                $this->data['Comment']['ipaddr'] = $_SERVER['REMOTE_ADDR'];
                $this->Comment->save($this->data);
                $this->Comment->updateAll(
                          array('Comment.status' => '1'),
                          array('Comment.id' => $id)
                       );
                //操作员日志
                if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['reply_comments'].':id '.$id, $this->admin['id']);
                }
                $back_url = $this->operation_return_url();//获取操作返回页面地址
                $this->redirect($back_url);
            }
        }

        $comment = $this->Comment->find('first', array('conditions' => array('Comment.id' => $id)));
        if(empty($comment))$this->redirect('index');
        $comment['Comment']['type_name'] = '';
        if($comment['Comment']['type'] == 'P'){
        	 $this->loadModel('ProductI18n');
	        $product_list = $this->ProductI18n->find('first', array('conditions' =>array('ProductI18n.product_id'=>$comment['Comment']['type_id'],'ProductI18n.locale'=>$this->backend_locale,'ProductI18n.name <>'=>''), 'fields' => array('ProductI18n.product_id','ProductI18n.name')));
	        $comment['Comment']['type_name'] = isset($product_list['ProductI18n']['title'])?$product_list['ProductI18n']['name']:'';
        }
        if($comment['Comment']['type'] == 'A'){
        	 	$this->loadModel('ArticleI18n');
	        	$article_list = $this->ArticleI18n->find('first', array('conditions' =>array('ArticleI18n.article_id'=>$comment['Comment']['type_id'],'ArticleI18n.locale'=>$this->backend_locale,'ArticleI18n.title <>'=>''), 'fields' => array('ArticleI18n.article_id','ArticleI18n.title')));
	        	$comment['Comment']['type_name'] = isset($article_list['ArticleI18n']['title'])?$article_list['ArticleI18n']['title']:'';
        }
        if($comment['Comment']['type'] == 'T'){
        	 	$this->loadModel('TopicI18n');
	        	$topic_list = $this->TopicI18n->find('first', array('conditions' =>array('TopicI18n.topic_id'=>$comment['Comment']['type_id'],'TopicI18n.locale'=>$this->backend_locale,'TopicI18n.title <>'=>''), 'fields' => array('TopicI18n.topic_id','TopicI18n.title')));
	        	$comment['Comment']['type_name'] = isset($topic_list['TopicI18n']['title'])?$topic_list['TopicI18n']['title']:'';
        }
        if($comment['Comment']['type'] == 'CP'){
        	 	$this->loadModel('CategoryProductI18n');
	        	$product_catgegory_list = $this->CategoryProductI18n->find('first', array('conditions' =>array('CategoryProductI18n.category_id'=>$comment['Comment']['type_id'],'CategoryProductI18n.locale'=>$this->backend_locale,'CategoryProductI18n.name <>'=>''), 'fields' => array('CategoryProductI18n.category_id','CategoryProductI18n.name')));
	        	$comment['Comment']['type_name'] = isset($product_catgegory_list['CategoryProductI18n']['name'])?$topic_list['CategoryProductI18n']['name']:'';
        }
        if($comment['Comment']['type'] == 'SP'){
        	 	$this->loadModel('PageI18n');
	        	$page_list = $this->PageI18n->find('first', array('conditions' =>array('PageI18n.page_id'=>$comment['Comment']['type_id'],'PageI18n.locale'=>$this->backend_locale,'PageI18n.title <>'=>''), 'fields' => array('PageI18n.page_id','PageI18n.title')));
	        	$comment['Comment']['type_name'] = isset($page_list['PageI18n']['title'])?$page_list['PageI18n']['title']:'';
        }
        if($comment['Comment']['type'] == 'AT'){
        		$comment['Comment']['type_name'] = $this->ld['activity'];
        }
        $userInfo = $this->User->find('first', array('conditions' => array('User.id' => $comment['Comment']['user_id'])));
        if (!empty($comment)) {
            $comment['User'] = $userInfo['User'];
        }
        $wh['parent_id'] = $comment['Comment']['id'];
        $restore = $this->Comment->find('all', array('conditions' => $wh));

        $this->set('comment', $comment);
        if (!empty($restore)) {
            $this->set('restore', $restore);
        }
        //leo20090722导航显示
        $this->navigations[] = array('name' => $comment['Comment']['name'],'url' => '');
    }

    //批量处理
    public function batch()
    {
        if ($this->RequestHandler->isPost()) {
            foreach ($_REQUEST['checkboxes'] as $k => $v) {
                $this->Comment->deleteAll(array('Comment.id' => $v));
            }
        }
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }
    public function commentverify($id, $status)
    {
        $a = $this->Comment->updateAll(
                          array('Comment.status' => $status),
                          array('Comment.id' => $id)
                       );
        if ($a) {
            $result['message'] = $this->ld['modified_successfully'];
            $result['flag'] = 1;
        } else {
            $result['message'] = $this->ld['modify_failed'];
            $result['flag'] = 2;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
