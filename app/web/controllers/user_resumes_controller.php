<?php

/*****************************************************************************
 * UserResume 用户简历
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为UserResumesController的控制器
 *用户简历
 *
 *@var
 *@var
 *@var
 *@var
 */
class UserResumesController extends AppController
{
	public $name = 'UserResumes';
	public $helpers = array('Html','Pagination');
	public $uses = array('User','UserFans','Blog','UserEducation','UserExperience','InformationResource');
	public $components = array('RequestHandler','Pagination');
	    
	/**
	*	课程分类列表
	*/
	public function index(){
		//登录验证
        	$this->checkSessionUser();
		$_GET=$this->clean_xss($_GET);
		$this->layout = 'usercenter';//引入模版
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
			Configure::write('debug', 0);
			$this->layout = 'ajax';
		}
		$this->page_init();                        //页面初始化
        	$this->pageTitle = $this->ld['fill_in_personal_resume'].' - '.$this->configs['shop_title'];
		//当前位置开始
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => $this->ld['fill_in_personal_resume'], 'url' => '');
		
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
		$UserEducation_list=$this->UserEducation->find('all',array('conditions'=>array('UserEducation.user_id'=>$user_id),'order'=>'UserEducation.start_time'));
		//pr($UserEducation_list);
		$this->set('UserEducation_list',$UserEducation_list);
		
		$UserExperience_list=$this->UserExperience->find('all',array('conditions'=>array('UserExperience.user_id'=>$user_id),'order'=>'UserExperience.start_time'));
		//pr($UserExperience_list);
		$this->set('UserExperience_list',$UserExperience_list);
		
		//资源库信息
		$informationresource_infos = $this->InformationResource->code_information_formated(array('language_master_type', 'language_type', 'certificate_type', 'education_type', 'experience_type', 'department_type', 'job_type', 'education_type'), $this->locale);
		//pr($informationresource_infos);
		$this->set('informationresource_infos', $informationresource_infos);
	}
	
	function view($id=0){
		//登录验证
        	$this->checkSessionUser();
		Configure::write('debug',1);
		$this->layout='ajax';
		
		$user_id=$_SESSION['User']['User']['id'];
		//获取我的信息
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
		
		if ($this->RequestHandler->isPost()) {
			$result=array();
			$result['code']='0';
			if(isset($this->data['UserExperience'])&&!empty($this->data['UserExperience'])){
				if(isset($this->data['UserExperience']['start_time']['year'])){
					$start_time=$this->data['UserExperience']['start_time']['year'].'-'.$this->data['UserExperience']['start_time']['month'];
				}else{
					$start_time=$this->data['UserExperience']['start_time'];
				}
				if(isset($this->data['UserExperience']['end_time']['year'])){
					$end_time=$this->data['UserExperience']['end_time']['year'].'-'.$this->data['UserExperience']['end_time']['month'];
				}else{
					$end_time=$this->data['UserExperience']['end_time'];
				}
				$UserExperience_data=$this->data['UserExperience'];
				$UserExperience_data['start_time']=$start_time;
				$UserExperience_data['end_time']=$end_time;
				$UserExperience_data['user_id']=$user_id;
				$this->UserExperience->save($UserExperience_data);
				$result['code']='1';
			}
			if(isset($this->data['UserEducation'])&&!empty($this->data['UserEducation'])){
				if(isset($this->data['UserEducation']['start_time']['year'])){
					$start_time=$this->data['UserEducation']['start_time']['year'].(isset($this->data['UserEducation']['start_time']['month'])?('-'.$this->data['UserEducation']['start_time']['month']):'');
				}else{
					$start_time=$this->data['UserExperience']['start_time'];
				}
				if(isset($this->data['UserEducation']['end_time']['year'])){
					$end_time=$this->data['UserEducation']['end_time']['year'].(isset($this->data['UserEducation']['end_time']['month'])?('-'.$this->data['UserEducation']['end_time']['month']):'');
				}else{
					$end_time=$this->data['UserEducation']['end_time'];
				}
				//pr($this->data['UserEducation']);
				$UserEducation_data=$this->data['UserEducation'];
				//pr($UserEducation_data);
				$UserEducation_data['start_time']=$start_time;
				$UserEducation_data['end_time']=$end_time;
				$UserEducation_data['user_id']=$user_id;
				$this->UserEducation->save($UserEducation_data);
				$result['code']='1';
			}
			die(json_encode($result));
		}
		
		//资源库信息
		$informationresource_infos = $this->InformationResource->code_information_formated(array('language_master_type', 'language_type', 'certificate_type', 'education_type', 'experience_type', 'department_type', 'job_type', 'education_type'), $this->locale);
		$this->set('informationresource_infos', $informationresource_infos);
		
		$page_type=isset($_REQUEST['page_type'])?$_REQUEST['page_type']:'';
		if($page_type=='experience'){
			$UserExperience_data=$this->UserExperience->find('first',array('conditions'=>array('UserExperience.id'=>$id,'UserExperience.user_id'=>$user_id)));
			$this->set('UserExperience_data',$UserExperience_data);
		}else if($page_type=='education'){
			$UserEducation_data=$this->UserEducation->find('first',array('conditions'=>array('UserEducation.id'=>$id,'UserEducation.user_id'=>$user_id)));
			$this->set('UserEducation_data',$UserEducation_data);
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
		$page_type=isset($_REQUEST['page_type'])?$_REQUEST['page_type']:'';
		if($page_type=='experience'){
			$this->UserExperience->deleteAll(array('UserExperience.id'=>$id,'UserExperience.user_id'=>$user_id));
			$result['code']='1';
			$result['message']=$this->ld['deleted_success'];
		}else if($page_type=='education'){
			$this->UserEducation->deleteAll(array('UserEducation.id'=>$id,'UserEducation.user_id'=>$user_id));
			$result['code']='1';
			$result['message']=$this->ld['deleted_success'];
		}
		die(json_encode($result));
	}
}
