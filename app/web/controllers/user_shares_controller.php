<?php

/**
 *这是一个名为 UserSharesController 的控制器
 *后台首页控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class UserSharesController extends AppController
{
	public $name = 'UserShares';
	public $components = array('RequestHandler','Pagination');
	public $helpers = array('Html','Javascript','Pagination');
	public $uses = array('User','UserFans','UserShareLog');
	
	public function index($page = 1){
		$this->checkSessionUser();
		$this->layout = 'usercenter';            //引入模版
	        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
	            	Configure::write('debug', 0);
	            	$this->layout = 'ajax';
	        }
		$this->pageTitle = $this->ld['myshare'].' - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['myshare'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);
		$user_id = $_SESSION['User']['User']['id'];
		//pr($user_id);
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
		//pr($user_list);
		$this->set('user_list', $user_list);
		$conditions = array();
		$condition['UserShareLog.user_id'] = $user_id;
		 // 日期
        if (isset($this->params['url']['date1']) && $this->params['url']['date1'] != '' ) {
             $condition['and']['UserShareLog.created >='] = $this->params['url']['date1'].'00:00:00';
             $this->set('dates1', $this->params['url']['date1']);
        }
        if (isset($this->params['url']['date2']) && $this->params['url']['date2'] != '' ) {
             $condition['and']['UserShareLog.created <='] = $this->params['url']['date2'].'23:59:59';
             $this->set('dates2', $this->params['url']['date2']);
        }
		//分页start
        //get参数
        $limit = 20;
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'user_shares', 'action' => 'index', 'page' => $page, 'limit' => $limit);
        //分页参数
        $options = array('page' => $page, 'show' => $limit, 'modelClass' => 'UserShareLog');
        $page = $this->Pagination->init($condition, $parameters, $options); // Added
        //分页end 
		
		$my_share_logs = $this->UserShareLog->find('all', array('conditions' => $condition,'order'=>'UserShareLog.created desc'));//做完之后添加条件
        //pr($my_share_logs);
        $this->set('my_share_logs', $my_share_logs);
	}
	
	/*
		用户分享记录
	*/
	function ajax_share_log(){
		if($this->RequestHandler->isPost()){
			Configure::write('debug', 1);
			$this->layout = 'ajax';
			$result=array(
				'code'=>'0',
				'message'=>$this->ld['time_out_relogin']
			);
			if(isset($_SESSION['User'])&&!empty($_SESSION['User'])){
				$user_id=$_SESSION['User']['User']['id'];
				$share_title=isset($_POST['share_title'])?$_POST['share_title']:'';
				$share_link=isset($_POST['share_link'])?trim($_POST['share_link']):'';
				if($share_link!=''){
					$share_info=$this->UserShareLog->find('first',array('conditions'=>array('UserShareLog.user_id'=>$user_id,'UserShareLog.share_link'=>$share_link)));
					if(empty($share_info)){
						$share_data=array(
							'id'=>0,
							'user_id'=>$user_id,
							'share_title'=>$share_title,
							'is_give_point'=>isset($this->configs['user_share_points'])&&intval($this->configs['user_share_points'])>0?'1':0,
							'share_link'=>$share_link
						);
						$this->UserShareLog->save($share_data);
						//分享兑换赠送积分
						$Page_HTTP_REFERER=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
						if((strstr($Page_HTTP_REFERER,'carts/done')||strstr($Page_HTTP_REFERER,'orders/view'))&&isset($this->configs['share_exchange_points'])&&intval($this->configs['share_exchange_points'])>0){
							$user_info=$this->User->findById($user_id);
							if(!empty($user_info)){
								$this->loadModel('UserPointLog');
								$this->User->save(array('id'=>$user_id,'point'=>intval($user_info['User']['point'])+intval($this->configs['share_exchange_points'])));
								$point_log_data = array(
									'id' => 0,
									'user_id' => $user_id,
									'log_type'=>'S',
									'point'=>$user_info['User']['point'],
									'point_change' =>$this->configs['share_exchange_points'],
									'system_note' => $this->ld['share']." ".$share_link
								);
                						$this->UserPointLog->save($point_log_data);
                						$this->UserPointLog->point_notify($point_log_data);
							}
						}
					}
					$result['code']='1';
					$result['message']=$this->ld['share'].$this->ld['successfully'];
				}else{
					$result['message']=$this->ld['j_wrong_address'];
				}
			}
			die(json_encode($result));
        	}else{
        		$this->redirect('/pages/home');
        	}
	}
}