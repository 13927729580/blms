<?php

/*****************************************************************************
 * Seevia 用户收藏
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 ArticlesController 的控制器
 *文章控制器.
 *
 *@var
 *@var
 *@var
 *@var
 *@var
 *@var
 */
/**
 *文章显示.
 *
 *对于Articles这张表的查寻
 *
 *@author   hechang 
 *
 *@version  $Id$
 */
class   UserActivitiesController extends AppController
{
    public $name="UserActivities";
    public $uses = array('Config','Activity','UserFans','Blog','ActivityUser');
    public $helpers = array('Html','Pagination');
    public $components = array('Pagination','RequestHandler','Cookie');

    public function index($page=1,$limit=10){
        //登录验证
        $this->checkSessionUser();
        $_GET=$this->clean_xss($_GET);
        $this->layout = 'usercenter';//引入模版
        $this->page_init();                        //页面初始化
        $this->pageTitle = '我的活动 - '.$this->configs['shop_title'];
        //当前位置开始
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
        $this->ur_heres[] = array('name' => '我的活动', 'url' => '');
        
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
        $conditions['ActivityUser.user_id']=$user_id;
        $conditions['Activity.status']='1';
        
        $parameters=array();
        $parameters['get'] = array();
        if(isset($_REQUEST['page'])&&intval($_REQUEST['page'])>0)$page=intval($_REQUEST['page']);
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'user_activities', 'action' => 'index', 'page' => $page, 'limit' => $limit);
        //分页参数
        $options = array('page' => $page, 'show' => $limit,'modelClass' => 'ActivityUser');
        $this->Pagination->init($conditions, $parameters, $options); // Added
        $activities_list = $this->ActivityUser->find('all',array('conditions'=>$conditions,'order'=>'ActivityUser.created desc'));
        $this->set('activities_list',$activities_list);
        
        if(!empty($activities_list)){
        	$activity_ids=array();
        	foreach($activities_list as $v){
        		$activity_ids[]=$v['ActivityUser']['activity_id'];
        	}
        	$activity_user_cond=array();
        	$activity_user_cond['ActivityUser.activity_id']=$activity_ids;
        	$activity_user_cond['Activity.status']='1';
        	$activity_user_infos = $this->ActivityUser->find('all',array('conditions'=>$activity_user_cond,'fields'=>'ActivityUser.activity_id,count(*) as activity_user','group'=>'ActivityUser.activity_id'));
        	$act_user_check=array();
		foreach ($activity_user_infos as $k => $v) {
			$act_user_check[$v['ActivityUser']['activity_id']]= $v[0]['activity_user'];
		}
		$this->set('act_user_check',$act_user_check);
        }
    }  
}//end class
