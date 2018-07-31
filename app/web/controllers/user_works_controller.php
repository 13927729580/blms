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
class UserWorksController extends AppController
{
	public $name = 'UserWorks';
	public $helpers = array('Html','Pagination');
	public $uses = array('User','UserFans','Blog','UserWork','UserWorksAnnex','InformationResource');
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
        	$this->pageTitle = '作品 - '.$this->configs['shop_title'];
		//当前位置开始
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => '作品', 'url' => '');
		
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
		$conditions['UserWork.user_id']=$user_id;
		$parameters=array();
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'user_works', 'action' => 'index', 'page' => $page, 'limit' => $limit);
		//分页参数
		$options = array('page' => $page, 'show' => $limit, 'modelClass' => 'UserWork');
		$this->Pagination->init($conditions, $parameters, $options); // Added
		$Userwork_lists=$this->UserWork->find('all',array('conditions'=>$conditions,'order'=>'UserWork.modified desc','page'=>$page,'limit'=>$limit));
		$this->set('Userwork_lists',$Userwork_lists);
	}
	
	function view($id=0){
		//登录验证
        	$this->checkSessionUser();
		$_GET=$this->clean_xss($_GET);
		$this->layout = 'usercenter';//引入模版
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
			Configure::write('debug', 1);
			$this->layout = 'ajax';
		}
		$this->page_init();                        //页面初始化
        	$this->pageTitle = '作品 - '.$this->configs['shop_title'];
		//当前位置开始
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => '作品', 'url' => '/user_works/index');
		$this->ur_heres[] = array('name' => '作品', 'url' => '');
		
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
		
		if ($this->RequestHandler->isPost()) {
			$result=array();
			$result['code']='0';
			$backup=isset($_POST['backup'])&&trim($_POST['backup'])!=''?trim($_POST['backup']):'index';
			if(!empty($this->data['UserWork'])){
				$this->data['UserWork']['user_id']=$user_id;
				$this->UserWork->save($this->data['UserWork']);
				$result['code']='1';
				$user_works_id=$this->UserWork->id;
				if(isset($this->data['UserWorksAnnex'])&&!empty($this->data['UserWorksAnnex'])){
					$UserWorksAnnex_ids=array();
					$annex_key=0;
					foreach($this->data['UserWorksAnnex'] as $v){
						$v['works_id']=$user_works_id;
						$v['orderby']=$annex_key;
						$this->UserWorksAnnex->save($v);
						$UserWorksAnnex_ids[]=$this->UserWorksAnnex->id;
						if($annex_key==0){
							$this->UserWork->updateAll(array("UserWork.works_img"=>"'".$v['file_url']."'"),array("UserWork.id"=>$user_works_id));
						}
						$annex_key++;
					}
					$this->UserWorksAnnex->deleteAll(array('UserWorksAnnex.works_id'=>$user_works_id,'not'=>array('UserWorksAnnex.id'=>$UserWorksAnnex_ids)));
				}else{
					$this->UserWorksAnnex->deleteAll(array('UserWorksAnnex.works_id'=>$user_works_id));
				}
			}
			if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
				die(json_encode($result));
			}
			$this->redirect($backup);
		}
		
		$UserWork_data=$this->UserWork->find('first',array('conditions'=>array('UserWork.id'=>$id,'UserWork.user_id'=>$user_id)));
		$this->set('UserWork_data', $UserWork_data);
		if(!empty($UserWork_data['UserWork'])){
			$UserWorksAnnex_list=$this->UserWorksAnnex->find('all',array('conditions'=>array('UserWorksAnnex.works_id'=>$id),'order'=>'UserWorksAnnex.orderby,UserWorksAnnex.id'));
			$this->set('UserWorksAnnex_list',$UserWorksAnnex_list);
		}
	}
	
	function remove($id=0){
		//登录验证
        	$this->checkSessionUser();
		Configure::write('debug',1);
		$this->layout='ajax';
		$user_id=$_SESSION['User']['User']['id'];
		$result=array();
		$result['code']='0';
		$result['message']=$this->ld['delete_failed'];
		$this->UserWork->deleteAll(array('UserWork.id'=>$id,'UserWork.user_id'=>$user_id));
		$result['code']='1';
		$result['message']=$this->ld['deleted_success'];
		die(json_encode($result));
	}
	
	function ajax_upload_files(){
		//登录验证
        	$this->checkSessionUser();
		Configure::write('debug',1);
		$this->layout='ajax';
		$user_id=$_SESSION['User']['User']['id'];
		
		$file_root = 'media/user_works/'.date('Ym').'/';
		$fileaddr = WWW_ROOT.'media/user_works/'.date('Ym').'/';
		$this->mkdirs($fileaddr);
		@chmod($fileaddr, 0777);
		
		$result=array();
		$result['code']='0';
		$result['message']=$this->ld['upload'].$this->ld['failed'];
		$upload_file=isset($_FILES['works_img'])?$_FILES['works_img']:array();
		if(!empty($upload_file)&&isset($upload_file['error'])&&is_string($upload_file['error'])&&$upload_file['error'] == 0){
			$file_name = $upload_file['name'];
			$file_tmp = $upload_file['tmp_name'];
			$file_size = $upload_file['size'];
			$file_type = $upload_file['type'];
			$filename = basename($file_name);
			$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
			
			$file_location = $fileaddr.md5(date('Y-m-d h:i:s').$user_id.$file_name).'.'.$file_ext;
			$file_name = '/'.$file_root.md5(date('Y-m-d h:i:s').$user_id.$file_name).'.'.$file_ext;
			
			if (move_uploaded_file($file_tmp, $file_location)) {
				$fileinfo=pathinfo($file_location);
				$result['code']='1';
				$result['file_path']=$file_name;
				$result['file_info']=$fileinfo;
				$result['message']=$this->ld['upload'].$this->ld['successfully'];
			}
		}else if(!empty($upload_file)&&isset($upload_file['error'])&&is_array($upload_file['error'])){
			$file_list=array();
			foreach($upload_file['error'] as $k=>$v){
				if($v!=0)continue;
				$file_name = $upload_file['name'][$k];
				$file_tmp = $upload_file['tmp_name'][$k];
				$file_size = $upload_file['size'][$k];
				$file_type = $upload_file['type'][$k];
				$filename = basename($file_name);
				$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
				
				$file_location = $fileaddr.md5(date('Y-m-d h:i:s').$user_id.$file_name).'.'.$file_ext;
				$file_name = '/'.$file_root.md5(date('Y-m-d h:i:s').$user_id.$file_name).'.'.$file_ext;
				
				if (move_uploaded_file($file_tmp, $file_location)) {
					$fileinfo=pathinfo($file_location);
					$file_list[]=array('file_path'=>$file_name,'file_info'=>$fileinfo);
				}
			}
			if(!empty($file_list)&&sizeof($file_list)==sizeof($upload_file['error'])){
				$result['code']='1';
				$result['file_list']=$file_list;
				$result['message']=$this->ld['upload'].$this->ld['successfully'];
			}
		}
		die(json_encode($result));
	}
	
	//创建路径
	public function mkdirs($path, $mode = 0777){
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
	                chmod($thispath, $mode);
	            } else {
	                @chmod($thispath, $mode);
	            }
	        }
	}
}
