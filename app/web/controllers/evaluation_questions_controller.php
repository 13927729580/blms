<?php

/*****************************************************************************
 * Evaluation 评测
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为EvaluationsController的控制器
 *评测
 *
 *@var
 *@var
 *@var
 *@var
 */
class EvaluationQuestionsController extends AppController
{
	public $name = 'EvaluationQuestions';
	public $helpers = array('Html','Pagination');
	public $uses = array('UserQuestion','UserQuestionOption','User','UserRank','UserFans','Blog','Profile','ProfilesField','EvaluationQuestion','EvaluationOption','Evaluation');
	public $components = array('RequestHandler','Pagination','Phpcsv');
	
	public function index($page=1,$limit=10){
		//登录验证
        	$this->checkSessionUser();
		$_GET=$this->clean_xss($_GET);
		$this->layout = 'usercenter';
		$this->pageTitle = '我的题库 - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => '我的题库', 'url' => '');
		$user_id=$_SESSION['User']['User']['id'];
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
		if ($user_list['User']['rank'] > 0) {
			$rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
			$user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
		}
		//粉丝数量
		$fans = $this->UserFans->find_fanscount_byuserid($user_id);
		$this->set('fanscount', $fans);
		//日记数量
		$blog = $this->Blog->find_blogcount_byuserid($user_id);
		$this->set('blogcount', $blog);
		//关注数量
		$focus = $this->UserFans->find_focuscount_byuserid($user_id);
		$this->set('focuscount', $focus);
		$this->set('user_list', $user_list);
		
		$page=isset($_GET['page'])?intval($_GET['page']):$page;
		$limit=isset($_GET['limit'])?intval($_GET['limit']):$limit;
		$condition = array();
		$condition['UserQuestion.user_id']=$user_id;
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'evaluation_questions','action' => 'index','page' => $page,'limit' => $limit);
		//分页参数
		$options = array('page' => $page,'show' => $limit,'modelClass' => 'UserQuestion');
		$this->Pagination->init($condition, $parameters, $options); // Added
		$question_list = $this->UserQuestion->find('all', array('fields'=>'UserQuestion.*','conditions' => $condition, 'page' => $page, 'limit' => $limit, 'order' => 'UserQuestion.created desc','recursive' => -1));
		$this->set('question_list',$question_list);
	}
	
	/**
	*	课程
	*/
	public function view($id=0){
		//登录验证
        	$this->checkSessionUser();
		$this->layout = 'usercenter';
		$this->pageTitle = '题库编辑 - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => '题库编辑', 'url' => '');
		
		$user_id=$_SESSION['User']['User']['id'];
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
		if ($user_list['User']['rank'] > 0) {
			$rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
			$user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
		}
		//粉丝数量
		$fans = $this->UserFans->find_fanscount_byuserid($user_id);
		$this->set('fanscount', $fans);
		//日记数量
		$blog = $this->Blog->find_blogcount_byuserid($user_id);
		$this->set('blogcount', $blog);
		//关注数量
		$focus = $this->UserFans->find_focuscount_byuserid($user_id);
		$this->set('focuscount', $focus);
		$this->set('user_list', $user_list);
		
		if ($this->RequestHandler->isPost()){
			if(isset($this->data['UserQuestion'])){
				$question_name=$this->data['UserQuestion']['name'];
				if(trim($question_name)=='')$this->redirect('index');
				if(empty($this->data['UserQuestion']['id'])){
				$question_info=$this->UserQuestion->find('first',array('conditions'=>array('UserQuestion.user_id'=>$user_id,'UserQuestion.name'=>$question_name)));
					$this->data['UserQuestion']['id']=isset($question_info['UserQuestion']['id'])?$question_info['UserQuestion']['id']:0;
				}
				$this->data['UserQuestion']['user_id']=$user_id;
				$this->UserQuestion->save($this->data['UserQuestion']);
				$question_id=$this->UserQuestion->id;
				$question_option_names=array();
				if(!empty($this->data['UserQuestionOption'])){
					foreach($this->data['UserQuestionOption'] as $v){
						if(trim($v['description'])=='')continue;
						$option_name=$v['name'];
						$question_option_info=$this->UserQuestionOption->find('first',array('conditions'=>array('UserQuestionOption.user_question_id'=>$question_id,'UserQuestionOption.name'=>$option_name)));
						$question_option_data=$v;
						$question_option_data['user_question_id']=$question_id;
						$question_option_data['id']=isset($question_option_info['UserQuestionOption'])?$question_option_info['UserQuestionOption']['id']:0;
						$this->UserQuestionOption->save($question_option_data);
						$question_option_names[]=$option_name;
					}
				}
				if(empty($question_option_names)){
					$this->UserQuestionOption->deleteAll(array('UserQuestionOption.user_question_id'=>$question_id));
				}else{
					$this->UserQuestionOption->deleteAll(array('UserQuestionOption.user_question_id'=>$question_id,'not'=>array('UserQuestionOption.name'=>$question_option_names)));
				}
			}
			$this->redirect('index');
		}
		$question_info = $this->UserQuestion->find('first', array('conditions' =>array('UserQuestion.id'=>$id,'UserQuestion.user_id'=>$user_id)));
		$this->set('question_info',$question_info);
	}
	
	function remove(){
		//登录验证
        	$this->checkSessionUser();
        	Configure::write('debug',1);
		$this->layout="ajax";
		$user_id=$_SESSION['User']['User']['id'];
		
		$result=array();
		$result['code']='0';
		$result['message']=$this->ld['delete_failed'];
		$question_ids=isset($_POST['question_id'])?$_POST['question_id']:0;
		$question_list = $this->UserQuestion->find('list', array('fields'=>"UserQuestion.id",'conditions' =>array('UserQuestion.id'=>$question_ids,'UserQuestion.user_id'=>$user_id)));
		if(!empty($question_list)){
			$this->UserQuestionOption->deleteAll(array('UserQuestionOption.user_question_id'=>$question_list));
			$this->UserQuestion->deleteAll(array('UserQuestion.id'=>$question_list));
			$result['code']='1';
			$result['message']=$this->ld['deleted_success'];
		}
        	die(json_encode($result));
	}
	
	function upload(){
		//登录验证
        	$this->checkSessionUser();
		$this->layout = 'usercenter';
		$this->pageTitle = '题库上传 - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => '题库上传', 'url' => '');
		
		$user_id=$_SESSION['User']['User']['id'];
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
		if ($user_list['User']['rank'] > 0) {
			$rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
			$user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
		}
		//粉丝数量
		$fans = $this->UserFans->find_fanscount_byuserid($user_id);
		$this->set('fanscount', $fans);
		//日记数量
		$blog = $this->Blog->find_blogcount_byuserid($user_id);
		$this->set('blogcount', $blog);
		//关注数量
		$focus = $this->UserFans->find_focuscount_byuserid($user_id);
		$this->set('focuscount', $focus);
		$this->set('user_list', $user_list);
		
		$profile_code="user_question_upload";
		$profile_info = $this->Profile->find('first', array('fields' => array('Profile.id','Profile.code'), 'conditions' => array('Profile.code' => $profile_code, 'Profile.status' => 1)));
		$this->set('profile_info',$profile_info);
	}
	
	function preview(){
		//登录验证
        	$this->checkSessionUser();
		$this->layout = 'usercenter';
		$this->pageTitle = '题库上传 - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => '题库上传', 'url' => '');
		
		$user_id=$_SESSION['User']['User']['id'];
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
		if ($user_list['User']['rank'] > 0) {
			$rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
			$user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
		}
		//粉丝数量
		$fans = $this->UserFans->find_fanscount_byuserid($user_id);
		$this->set('fanscount', $fans);
		//日记数量
		$blog = $this->Blog->find_blogcount_byuserid($user_id);
		$this->set('blogcount', $blog);
		//关注数量
		$focus = $this->UserFans->find_focuscount_byuserid($user_id);
		$this->set('focuscount', $focus);
		$this->set('user_list', $user_list);
		
		$profile_code="user_question_upload";
		$profile_info = $this->Profile->find('first', array('fields' => array('Profile.id','Profile.code'), 'conditions' => array('Profile.code' => $profile_code, 'Profile.status' => 1)));
		$fields_info=array();
		$fields_desc_info=array();
		if(!empty($profile_info)){
			$profilefiled_info = $this->ProfilesField->find('all', array('fields' => array('ProfilesField.code', 'ProfilesFieldI18n.description'), 'conditions' => array( 'ProfilesField.profile_id' => $profile_info['Profile']['id'], 'ProfilesField.status' => 1), 'order' => 'ProfilesField.orderby asc,ProfilesField.id'));
			foreach($profilefiled_info as $v){
				$fields_info[$v['ProfilesField']['code']]=$v['ProfilesFieldI18n']['description'];
				$fields_desc_info[$v['ProfilesFieldI18n']['description']]=$v['ProfilesField']['code'];
			}
		}
		//pr($profile_info);exit();
		if(empty($profile_info))$this->redirect('upload');
		$preview_data=array();
		if (!empty($_FILES['evaluation_question'])) {
			if ($_FILES['evaluation_question']['error'] > 0) {
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['upload'].$this->ld['failed']."');window.location.href='/evaluation_questions/upload';</script>";
				die();
			}else{
				$question_option_names=array();
				for($i='A';$i<='E';$i++){
					$question_option_names[]=$i;
					$fields_desc_info[$i]=$i;
				}
				$handle = @fopen($_FILES['evaluation_question']['tmp_name'], 'r');
				$fields_array=array();
				$fields_desc=array();
				foreach($fields_info as $k=>$v){
					if($k=='UserQuestionOption'){
						foreach($question_option_names as $vv){
							$fields_desc[]=$vv;
							$fields_array[]=$vv;
						}
					}else{
						$fields_array[]=$k;
						$fields_desc[]=$v;
					}
				}
				$preview_code=array();
				$csv_export_code = 'gb2312';
				$i = 0;
                          while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
					if ($i == 0) {
						foreach ($row as $k => $v) {
							$preview_code[]=iconv('GB2312', 'UTF-8', $v);
						}
						$check_row = $row[0];
						$row_count = sizeof($row);
						$check_row = iconv('GB2312', 'UTF-8', $check_row);
						$num_count = sizeof($fields_desc);
						++$i;
					}
					if($row_count!=$num_count){
						echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('文件格式错误');window.location.href='/evaluation_questions/upload';</script>";
						die();
					}
					$temp = array();
					foreach($row as $kk=>$vv){
						$data_key_code=isset($fields_desc_info[$preview_code[$kk]])?$fields_desc_info[$preview_code[$kk]]:'';
						$temp[$data_key_code] = $vv=='' ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $vv);
					}
                                $preview_data[] = $temp;
                          }
                          fclose($handle);
                          $this->set('fields_array', $fields_array);
                          $this->set('fields_desc', $fields_desc);
                          $this->set('preview_data', $preview_data);
			}
		}
		if(empty($preview_data))$this->redirect('upload');
	}
	
	public function batch_upload(){
		//登录验证
        	$this->checkSessionUser();
        	Configure::write('debug',1);
		$this->layout="ajax";
		$user_id=$_SESSION['User']['User']['id'];
		
		if ($this->RequestHandler->isPost()) {
			$upload_num=0;
			$checkboxs=isset($_POST['checkbox'])?$_POST['checkbox']:array();
			if(!empty($this->data)){
				foreach($this->data as $k=>$question_info){
					if(!in_array($k,$checkboxs))continue;
					$question_data=array();
					$question_data=$question_info['UserQuestion'];
					$question_name=$question_data['name'];
					if(trim($question_name)=='')continue;
				$questioninfo=$this->UserQuestion->find('first',array('conditions'=>array('UserQuestion.user_id'=>$user_id,'UserQuestion.name'=>$question_name)));
					$question_data['id']=isset($questioninfo['UserQuestion']['id'])?$questioninfo['UserQuestion']['id']:0;
					$question_data['status']='0';
					$question_data['user_id']=$user_id;
					$this->UserQuestion->save($question_data);
					$question_id=$this->UserQuestion->id;
					$question_option=isset($question_info['UserQuestionOption'])?$question_info['UserQuestionOption']:array();
					$option_names=array();
					foreach($question_option as $option_name=>$option_desc){
						if(trim($option_desc)=='')continue;
						$question_option_info=$this->UserQuestionOption->find('first',array('conditions'=>array('UserQuestionOption.user_question_id'=>$question_id,'UserQuestionOption.name'=>$option_name)));
						$question_option_data=array(
							'id'=>isset($question_option_info['UserQuestionOption'])?$question_option_info['UserQuestionOption']['id']:0,
							'user_question_id'=>$question_id,
							'name'=>$option_name,
							'description'=>trim($option_desc),
							'status'=>'1'
						);
						$this->UserQuestionOption->save($question_option_data);
						$option_names[]=$option_name;
					}
					if(!empty($option_names)){
						$this->UserQuestionOption->deleteAll(array('UserQuestionOption.user_question_id'=>$question_id,'not'=>array('UserQuestionOption.name'=>$option_names)));
					}else{
						$this->UserQuestionOption->deleteAll(array('UserQuestionOption.user_question_id'=>$question_id));
					}
					$upload_num++;
				}
			}
			if($upload_num==0){
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['upload'].$this->ld['failed']."');window.location.href='/evaluation_questions/upload';</script>";
				die();
			}else{
				$upload_message="(".($upload_num).'/'.(sizeof($checkboxs)).")";
				echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['upload'].$this->ld['successfully'].$upload_message."');window.location.href='/evaluation_questions/upload';</script>";
				die();
			}
		}else{
			$this->redirect('upload');
		}
	}
	
	public function download_csv_example($code=""){
		Configure::write('debug',1);
		$this->layout="ajax";
		$profile_code="user_question_upload";
		$profile_info = $this->Profile->find('first', array('fields' => array('Profile.id','Profile.code'), 'conditions' => array('Profile.code' => $profile_code, 'Profile.status' => 1)));
		$fields_info=array();
		if(!empty($profile_info)){
			$profilefiled_info = $this->ProfilesField->find('all', array('fields' => array('ProfilesField.code', 'ProfilesFieldI18n.description'), 'conditions' => array( 'ProfilesField.profile_id' => $profile_info['Profile']['id'], 'ProfilesField.status' => 1), 'order' => 'ProfilesField.orderby asc,ProfilesField.id'));
			foreach($profilefiled_info as $v){
				$fields_info[$v['ProfilesField']['code']]=$v['ProfilesFieldI18n']['description'];
			}
		}

		if(empty($profile_info))$this->redirect('upload');
		$question_option_names=array();
		for($i='A';$i<='E';$i++){
			$question_option_names[]=$i;
		}
		$newdatas=array();
		$tmp=array();
		foreach($fields_info as $k=>$v){
			if($k=='UserQuestionOption'){
				foreach($question_option_names as $vv){
					$tmp[]=$vv;
				}
			}else{
				$tmp[]=$v;
			}
		}
		$newdatas[]=$tmp;

		if($code!=""){
            $evaluation_question_infos=$this->EvaluationQuestion->find('all',array('conditions' => array('EvaluationQuestion.evaluation_code' => $code)));
        }else{
            $evaluation_question_infos=$this->EvaluationQuestion->find('all',array('limit'=>5));
        }
        
        if(!empty($evaluation_question_infos)){
            foreach($evaluation_question_infos as $v){
                $v['EvaluationOption']=$this->EvaluationOption->find('all',array('conditions' => array('EvaluationOption.evaluation_question_code' => $v['EvaluationQuestion']['code'])));
                $question_option_data=array();
                if(isset($v['EvaluationOption'])&&!empty($v['EvaluationOption'])){
                    foreach($v['EvaluationOption'] as $vv){
                        $question_option_data[$vv['EvaluationOption']['name']]=$vv['EvaluationOption']['description'];
                    }
                }
                $question_data=array();
                //$fields_info['[UserQuestion.tag]']=='问题编码';
                foreach($fields_info as $kk=>$vv){
                    if($kk=='UserQuestionOption'){
                        foreach($question_option_names as $vvv){
                            $question_data[]=isset($question_option_data[$vvv])?$question_option_data[$vvv]:'';
                        }
                    }else{
                        $field_codes=explode('.',$kk);
                        $field_model=isset($field_codes[0])?$field_codes[0]:'';
                        $field_name=isset($field_codes[1])?$field_codes[1]:'';
                        if($field_name=='tag'){
                        	$field_name = 'code';
                        	
                        }
                        $question_data[]=isset($v['EvaluationQuestion'][$field_name])?$v['EvaluationQuestion'][$field_name]:'';
                    }
                    
                }
                //pr($fields_info);
                $newdatas[]=$question_data;
            }
        }
		//定义文件名称
		//exit();
		$nameexl = 'question'.date('Ymd').'.csv';
		$this->Phpcsv->output($nameexl, $newdatas);
		die();
      }
      
      public function fgetcsv_reg($handle, $length = null, $d = ',', $e = '"'){
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
}
