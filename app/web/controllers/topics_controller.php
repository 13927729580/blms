<?php

/*****************************************************************************
 * Seevia 专题管理
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 TopicsController 的信息系统控制器.
 */
class TopicsController extends AppController
{
    /*
    *@var $name
    *@var $helpers
    *@var $uses
    *@var $components
    */
    public $name = 'Topics';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Html', 'Form', 'Javascript');
    public $uses = array('Topic','TopicI18n','Brand','ProductType','Product','TopicProduct','ProductLocalePrice','ProductRank','UserRank','TopicArticle','Comment');
    public $cacheQueries = true;
    public $cacheAction = '1 hour';
    /**
     *显示.
     */
    public function index($page = 1, $limit = 10, $order_field = 0, $order_type = 0)
    {
        $this->loadModel('Template');
        $template = $this->Template->find('first', array('conditions' => array('is_default' => 1)));
        if (isset($template['Template']['name']) && $template['Template']['name'] == 'seseyoyo') {
            $this->pageTitle = 'image'.' - '.sprintf($this->ld['page'], $page).' - '.$this->configs['shop_title'];
            $this->ur_heres[] = array('name' => 'image','url' => '/topics/');
        } else {
            $this->pageTitle = $this->ld['topic'].' - '.sprintf($this->ld['page'], $page).' - '.$this->configs['shop_title'];
            $this->ur_heres[] = array('name' => $this->ld['topic'],'url' => '/topics/');
        }
        $this->layout = 'default_full';

        $params['page'] = $page;
        $params['limit'] = $limit;
        $this->page_init($params);
    }
    /**
     *显示.
     *
     *@param $id
     */
    public function view($id)
    {
        $this->layout = 'default_full';
        if (!is_numeric($id) || $id < 1) {
            $this->pageTitle = $this->ld['invalid_id'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['invalid_id'], '/', 5);

            return;
        }
        $conditions = array('Topic.id' => $id,'Topic.status' => '1');
        $topic = $this->Topic->find('first', array('conditions' => $conditions));
        if (empty($topic)) {
            $this->pageTitle = $this->ld['topic'].$this->ld['home'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['topic'].$this->ld['not_exist'], '/', 5);

            return;
        } elseif (!empty($topic)) {
            $this->pageTitle = $topic['TopicI18n']['title'].' - '.$this->configs['shop_title'];
        }
        $params['id'] = $id;
        $params['topicInfo'] = $topic;
        $this->set('meta_description', $topic['TopicI18n']['meta_description'].' '.$this->configs['seo-des']);
        $this->set('meta_keywords', $topic['TopicI18n']['meta_keywords'].' '.$this->configs['seo-key']);
        $this->page_init($params);
        $this->ur_heres[] = array('name' => $this->ld['topic'],'url' => '/topics/');
        $this->ur_heres[] = array('name' => $topic['TopicI18n']['title'],'url' => '');
        $this->pageTitle = $topic['TopicI18n']['title'].' - '.$this->ld['topic'].$this->ld['home'].' - '.$this->configs['shop_title'];
    }

    public function download()
    {
        $this->pageTitle = '实玮网络客户端'.' - '.$this->configs['shop_name'];
        $this->layout = 'default_full';
        $this->page_init();
    }
    
    function topic_comment($topic_id=0,$page=1){
    		Configure::write('debug', 1);
        	$this->layout = 'ajax';
        	
        	if(!(isset($this->configs['enable_topic_comment'])&&$this->configs['enable_topic_comment']=='1')){
        		die();
        	}
        	
        	$this->set('topic_id',$topic_id);
        	
        	if(isset($_REQUEST['page'])&&intval($_REQUEST['page'])>0)$page=intval($_REQUEST['page']);
        	$limit = 10;
        	$conditions = array();
        	$conditions['Comment.parent_id'] = 0;
        	$conditions['Comment.type'] = 'T';
        	$conditions['Comment.type_id'] = $topic_id;
        	$conditions['Comment.status'] = 1;
        	$joins=array(
                    array(
				'table' => 'svoms_users',
				'alias' => 'User',
				'type' => 'inner',
				'conditions' => array('Comment.user_id = User.id')
                         )
            	);
        	$total = $this->Comment->find('count', array('conditions' => $conditions,'joins'=>$joins));
		//get参数
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'topics','action' => 'topic_comment/'.$topic_id,'page' => $page,'limit' => $limit);
		//分页参数
		$options = array('page' => $page,'show' => $limit,'modelClass' => 'Comment','total' => $total);
		$this->Pagination->init($conditions, $parameters, $options); // Added
		$comment_infos=$this->Comment->find('all', array('conditions' => $conditions, 'limit' => $limit, 'page' => $page,'joins'=>$joins, 'order' => 'Comment.created desc','fields'=>'Comment.*,User.id,User.name,User.first_name,User.last_name,User.email,User.img01'));
		$this->set('comment_infos',$comment_infos);

        if(isset($_SESSION['User'])){
            $conditions['Comment.user_id']=$_SESSION['User']['User']['id'];
             $comment_count=$this->Comment->find('count', array('conditions' => $conditions));
             $this->set('user_comment_count',$comment_count);
        }
       

        

    }
    
    function ajax_topic_comment_reply($comment_id=0){
    		Configure::write('debug', 1);
        	$this->layout = 'ajax';
        	
        	if(!(isset($this->configs['enable_topic_comment'])&&$this->configs['enable_topic_comment']=='1')){
        		die();
        	}
        	
        	$conditions = array();
        	$conditions['Comment.parent_id'] = $comment_id;
        	$conditions['Comment.status'] = 1;
        	$joins=array(
                    array(
				'table' => 'svoms_users',
				'alias' => 'User',
				'type' => 'inner',
				'conditions' => array('Comment.user_id = User.id')
                         )
            	);
        	$comment_reply=$this->Comment->find('all', array('conditions' => $conditions,'joins'=>$joins, 'order' => 'Comment.created desc','fields'=>'Comment.*,User.id,User.name,User.first_name,User.last_name,User.email,User.img01'));
		$this->set('comment_reply',$comment_reply);
    }
    
    function ajax_add_topic_comment(){
    		Configure::write('debug', 1);
        	$this->layout = 'ajax';
    		
    		$result=array();
    		$result['code']='0';
    		$result['message']=$this->ld['send_failed'];
    		
        	if(!(isset($this->configs['enable_topic_comment'])&&$this->configs['enable_topic_comment']=='1')){
        		die(json_encode($result));
        	}
    		if(isset($_SESSION['User'])&&!empty($_SESSION['User'])){
			$status = 0;
			if (isset($this->configs['enable_user_comment_check']) && $this->configs['enable_user_comment_check'] == 0) {
				$status = 1;
			}
			$this->data['Comment']['type'] = 'T';
			$this->data['Comment']['type_id'] = !empty($this->data['Comment']['type_id']) ? $this->data['Comment']['type_id'] : '0';
			$this->data['Comment']['parent_id'] =!empty($this->data['Comment']['parent_id']) ? $this->data['Comment']['parent_id'] : '0';
			$this->data['Comment']['user_id'] = $_SESSION['User']['User']['id'];//用户id
			$this->data['Comment']['content'] = !empty($this->data['Comment']['content']) ? $this->data['Comment']['content'] : '';//用户日志
			$this->data['Comment']['created'] = date('Y-m-d H:i:s');//用户创建时间
			$this->data['Comment']['modified'] = date('Y-m-d H:i:s');//用户修改时间
			$this->data['Comment']['status'] = $status;//评论审核默认状态（有效）
			$this->data['Comment']['rank'] = 5;
			$this->data['Comment']['is_public'] = !empty($this->data['Comment']['is_public']) ? $this->data['Comment']['is_public'] : '0';
			$this->data['Comment']['ipaddr'] = $this->RequestHandler->getClientIP();
			$oauth_content = $this->data['Comment']['content'];
            		$oauth_content = preg_replace("/<img.+?\/>/", '', $oauth_content);
            		$oauth_content = strlen($oauth_content) == 0 || $oauth_content == '' ?$this->server_host:$oauth_content;
			if (isset($_FILES['upfile']['tmp_name']) && !empty($_FILES['upfile']['tmp_name'])) {
				//图片上传处理
				$imgname_arr = explode('.', strtolower($_FILES['upfile']['name']));//获取文件名
				if ($imgname_arr[1] == 'jpg' || $imgname_arr[1] == 'gif' || $imgname_arr[1] == 'png' || $imgname_arr[1] == 'bmp' || $imgname_arr[1] == 'jpeg') {
					//判断文件格式（限制图片格式）
					$img_thumb_name = md5($imgname_arr[0].time());
					$image_name = $img_thumb_name.'.'.$imgname_arr[1];
					$imgaddr = WWW_ROOT.'img/comment/'.date('Ym').'/';
					$image_width = 180;
					$image_height = 180;
					$img_detail = str_replace($image_name, '', $imgaddr);
					$this->mkdirs($imgaddr);
					move_uploaded_file($_FILES['upfile']['tmp_name'], $imgaddr.$image_name);
					$this->data['Comment']['img'] = '/img/comment/'.date('Ym').'/'.$image_name;
				}
			} else {
				$this->data['Comment']['img'] = '';
			}
			$this->Comment->save($this->data['Comment']);
			$this->Comment->comment_point($this,$this->data['Comment']['type'],$this->data['Comment']['type_id']);
			$result['code']='1';
			$result['message']=$this->ld['send_success'];
    		}else{
    			$result['message']=$this->ld['time_out_relogin'];
    		}
    		die(json_encode($result));
    }
    
    //创建路径
    public function mkdirs($path, $mode = 0777)
    {
        $dirs = explode('/', $path);
        $pos = strrpos($path, '.');
        if ($pos === false) {
            $subamount = 0;
        } else {
            $subamount = 1;
        }
        for ($c = 0;$c < count($dirs) - $subamount; ++$c) {
            $thispath = '';
            for ($cc = 0; $cc <= $c; ++$cc) {
                $thispath .= $dirs[$cc].'/';
            }
            if (!file_exists($thispath)) {
                mkdir($thispath, $mode);
            }
        }
    }
}
