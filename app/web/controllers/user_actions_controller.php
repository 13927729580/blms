<?php

/*****************************************************************************
 * UserWork 用户作品
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为UserWorksController的控制器
 *用户简历
 *
 *@var
 *@var
 *@var
 *@var
 */
class UserActionsController extends AppController
{
	public $name = 'UserActions';
	public $helpers = array('Html','Pagination');
	public $uses = array('User','UserFans','Blog','UserAction','InformationResource');
	public $components = array('RequestHandler','Pagination');
	    
	/**
	*	课程分类列表
	*/
	public function index($page=1,$limit=10){
		//登录验证
        	$this->checkSessionUser();
		$_GET=$this->clean_xss($_GET);
		$this->layout = 'usercenter';//引入模版
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
			Configure::write('debug', 1);
			$this->layout = 'ajax';
		}
		$this->page_init();                        //页面初始化
        	$this->pageTitle = '我的动态 - '.$this->configs['shop_title'];
		//当前位置开始
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => '我的动态', 'url' => '');
		
		$user_id=$_SESSION['User']['User']['id'];
		//获取我的信息
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
		if ($user_list['User']['rank'] > 0) {
			$rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
			$user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
		}
		$this->set('user_list', $user_list);
		//粉丝数量
		$fans = $this->UserFans->find_fanscount_byuserid($user_id);
		$this->set('fanscount', $fans);
		//日记数量
		$blog = $this->Blog->find_blogcount_byuserid($user_id);
		$this->set('blogcount', $blog);
		//关注数量
		$focus = $this->UserFans->find_focuscount_byuserid($user_id);
		$this->set('focuscount', $focus);
		
		$conditions=array();
		$conditions['UserAction.user_id']=$user_id;
		$parameters=array();
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'user_actions', 'action' => 'index', 'page' => $page, 'limit' => $limit);
		//分页参数
		$options = array('page' => $page, 'show' => $limit, 'modelClass' => 'UserAction');
		$this->Pagination->init($conditions, $parameters, $options); // Added
		$user_action_lists=$this->UserAction->find('all',array('conditions'=>$conditions,'order'=>'UserAction.modified desc','page'=>$page,'limit'=>$limit));
		if(!empty($user_action_lists)){
			foreach($user_action_lists as $k=>$v){
				if($v['UserAction']['type']=='course'){
					$user_action_lists[$k]['UserAction']['redirect_url']="/courses/view/".$v['UserAction']['type_id'];
				}else if($v['UserAction']['type']=='evaluation'){
					$user_action_lists[$k]['UserAction']['redirect_url']="/evaluations/view/".$v['UserAction']['type_id'];
				}else{
					$user_action_lists[$k]['UserAction']['redirect_url']="";
				}
			}
		}
		$this->set('user_action_lists',$user_action_lists);
	}
}
