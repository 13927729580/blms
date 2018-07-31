<?php

/*****************************************************************************
 * Seevia 题目管理
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
 *这是一个名为 EvaluationQuestionsController 的控制器
 *题目管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class EvaluationQuestionsController extends AppController
{
    public $name = 'EvaluationQuestions';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('EvaluationQuestion','EvaluationOption','Profile','ProfileFiled','Evaluation','InformationResource');
    
    /**
     *编辑题目
     */
    public function view($id=0){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		
		if ($this->RequestHandler->isPost()){
			$result=array();
			$result['code']='0';
			$result['message']=$this->ld['unknown_error'];
			if(isset($this->data['EvaluationQuestion'])&&!empty($this->data['EvaluationQuestion'])){
				$evaluation_code=isset($this->data['EvaluationQuestion']['evaluation_code'])?$this->data['EvaluationQuestion']['evaluation_code']:'';
				$evaluation_info=$this->Evaluation->find('first',array('conditions'=>array('Evaluation.code'=>$evaluation_code)));
				if(!empty($evaluation_info)){
					$question_code=isset($this->data['EvaluationQuestion']['code'])?$this->data['EvaluationQuestion']['code']:'';
					$conditions=array(
						'EvaluationQuestion.id <>'=>isset($this->data['EvaluationQuestion']['id'])?$this->data['EvaluationQuestion']['id']:0,
						'EvaluationQuestion.code'=>$question_code,
					);
					$evaluation_question_total=$this->EvaluationQuestion->find('count',array('conditions'=>$conditions));
					if(empty($evaluation_question_total)){
						if(isset($this->data['EvaluationQuestion']['id'])&&!empty($this->data['EvaluationQuestion']['id'])){
							$question_detail=$this->EvaluationQuestion->find('first',array('fields'=>'EvaluationQuestion.id,EvaluationQuestion.code','conditions'=>array('EvaluationQuestion.id'=>$this->data['EvaluationQuestion']['id'])));
							if(!empty($question_detail))$old_question_code=$question_detail['EvaluationQuestion']['code'];
						}
						$this->EvaluationQuestion->save($this->data['EvaluationQuestion']);
						if(isset($old_question_code)&&trim($old_question_code)!=''){
							$this->EvaluationOption->updateAll(array('EvaluationOption.evaluation_question_code'=>"'".$question_code."'"),array('EvaluationOption.evaluation_question_code'=>$old_question_code));
						}
						$result['code']='1';
						$result['message']=$this->ld['update_successful'];
					}else{
						$result['message']=$this->ld['code_already_exists'];
					}
				}
			}
			die(json_encode($result));
		}
		
    		$evaluation_code=isset($_REQUEST['evaluation_code'])?$_REQUEST['evaluation_code']:'';
    		if($evaluation_code!=''){
    			$evaluation_info=$this->Evaluation->find('first',array('conditions'=>array('Evaluation.code'=>$evaluation_code)));
    			$this->set('evaluation_info',$evaluation_info);
    		}
    		$evaluation_question_info=$this->EvaluationQuestion->find('first',array('conditions'=>array('EvaluationQuestion.id'=>$id,'EvaluationQuestion.evaluation_code'=>$evaluation_code)));
    		$this->set('evaluation_question_info',$evaluation_question_info);
    		
		$info_resource=$this->InformationResource->information_formated('question_type',$this->backend_locale,false);
		$this->set('info_resource', $info_resource);
    }

    function upload($code){
        $this->menu_path = array('root' => '/hr/','sub' => '/evaluations/');
        $this->set('title_for_layout', $this->ld['edit'].'-评测管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "评测管理",'url' => '/evaluations/');
        $this->navigations[] = array('name' => "题库上传",'url' => '/evaluations/upload');
        $profile_code="user_question_upload";
        $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id','Profile.code'), 'conditions' => array('Profile.code' => $profile_code, 'Profile.status' => 1)));
        $this->set('profile_info',$profile_info);
        $this->set('code', $code);
    }

    /**
     * 删除题目
     *
     *@param int $id
     */
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        $evaluation_question_info = $this->EvaluationQuestion->findById($id);
        $this->EvaluationQuestion->deleteAll(array('EvaluationQuestion.id' => $id));
        $this->EvaluationOption->deleteAll(array('EvaluationOption.evaluation_question_code' => $evaluation_question_info["EvaluationQuestion"]["code"]));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id.' '.$evaluation_question_info['EvaluationQuestion']['code'], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_member_success'];
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/evaluations/');
        }
    }

    /**
     * 检查code
     *
     */
    public function check_code()
    {
        Configure::write('debug', 1);
        $result['code'] = 0;
        $result['msg'] = '';
        if ($this->RequestHandler->isPost()) {
            $code = isset($_POST['code']) ? $_POST['code'] : '';
            $question_count = $this->EvaluationQuestion->find('count', array('conditions' => array('EvaluationQuestion.code' => $code, 'EvaluationQuestion.status' => "1")));
            if ($question_count == 0) {
                $result['code'] = 1;
            } else {
                $result['msg'] = "code已存在";
            }
            die(json_encode($result));
        } else {
            $this->redirect('/evaluations');
        }
    }

    public function download_csv_example($code=""){
        Configure::write('debug',0);
        $this->layout="ajax";
        $profile_code="question_upload";
        $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id','Profile.code'), 'conditions' => array('Profile.code' => $profile_code, 'Profile.status' => 1)));
        $fields_info=array();
        if(!empty($profile_info)){
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code','ProfilesFieldI18n.description'), 'conditions' => array( 'ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1, 'ProfilesFieldI18n.locale' => $this->backend_locale), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
            foreach($profilefiled_info as $v){
                $fields_info[$v['ProfileFiled']['code']]=$v['ProfilesFieldI18n']['description'];
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
            if($k=='EvaluationOption'){
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
                foreach($fields_info as $kk=>$vv){
                    if($kk=='EvaluationOption'){
                        foreach($question_option_names as $vvv){
                            $question_data[]=isset($question_option_data[$vvv])?$question_option_data[$vvv]:'';
                        }
                    }else{
                        $field_codes=explode('.',$kk);
                        $field_model=isset($field_codes[0])?$field_codes[0]:'';
                        $field_name=isset($field_codes[1])?$field_codes[1]:'';
                        $question_data[]=isset($v[$field_model][$field_name])?$v[$field_model][$field_name]:'';
                    }
                }
                $newdatas[]=$question_data;
            }
        }
        //定义文件名称
        $nameexl = 'question'.date('Ymd').'.csv';
        $this->Phpcsv->output($nameexl, $newdatas);
        die();
    }

    function preview($code){
        $this->menu_path = array('root' => '/hr/','sub' => '/evaluations/');
        $this->set('title_for_layout', $this->ld['edit'].'-评测管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "评测管理",'url' => '/evaluations/');
        $this->navigations[] = array('name' => "题库上传",'url' => '/evaluations/upload'.$code);
        $this->set('code',$code);
        $profile_code="question_upload";
        $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id','Profile.code'), 'conditions' => array('Profile.code' => $profile_code, 'Profile.status' => 1)));
        $fields_info=array();
        $fields_desc_info=array();
        if(!empty($profile_info)){
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array( 'ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
            foreach($profilefiled_info as $v){
                $fields_info[$v['ProfileFiled']['code']]=$v['ProfilesFieldI18n']['description'];
                $fields_desc_info[$v['ProfilesFieldI18n']['description']]=$v['ProfileFiled']['code'];
            }
        }
        if(empty($profile_info))$this->redirect('upload/'.$code);
        $preview_data=array();
        if (!empty($_FILES['evaluation_question'])) {
            if ($_FILES['evaluation_question']['error'] > 0) {
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['upload'].$this->ld['failed']."');window.location.href='/admin/evaluation_questions/upload/".$code."';</script>";
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
                    if($k=='EvaluationOption'){
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
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('文件格式错误');window.location.href='/admin/evaluation_questions/upload/".$code."';</script>";
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
        if(empty($preview_data))$this->redirect('upload/'.$code);
    }

    public function batch_upload($code){
        Configure::write('debug',1);
        $this->layout="ajax";
        if ($this->RequestHandler->isPost()) {
            $upload_num=0;
            $checkboxs=isset($_POST['checkbox'])?$_POST['checkbox']:array();
            if(!empty($this->data)){
                foreach($this->data as $k=>$question_info){
                    if(!in_array($k,$checkboxs))continue;
                    $question_data=array();
                    $question_data=$question_info['EvaluationQuestion'];
                    $question_code=$question_data['code'];
                    if(trim($question_code)=='')continue;
                    $questioninfo=$this->EvaluationQuestion->find('first',array('conditions'=>array('EvaluationQuestion.code'=>$question_code)));
                    $question_data['id']=isset($questioninfo['EvaluationQuestion']['id'])?$questioninfo['EvaluationQuestion']['id']:0;
                    $question_data['create_by']='0';
                    $question_data['create_by_id']=$this->admin['id'];
                    $question_data['evaluation_code']=$code;
                    //pr($question_data);exit();
                    $this->EvaluationQuestion->save($question_data);
                    $question_id=$this->EvaluationQuestion->id;
                    $question_option=isset($question_info['EvaluationOption'])?$question_info['EvaluationOption']:array();
                    $option_names=array();
                    foreach($question_option as $option_name=>$option_desc){
                        if(trim($option_desc)=='')continue;
                        $question_option_info=$this->EvaluationOption->find('first',array('conditions'=>array('EvaluationOption.evaluation_question_code'=>$question_code,'EvaluationOption.name'=>$option_name)));
                        $question_option_data=array(
                            'id'=>isset($question_option_info['EvaluationOption'])?$question_option_info['EvaluationOption']['id']:0,
                            'evaluation_question_code'=>$question_code,
                            'name'=>$option_name,
                            'description'=>trim($option_desc),
                            'status'=>'1'
                        );
                        $this->EvaluationOption->save($question_option_data);
                        $option_names[]=$option_name;
                    }
                    if(!empty($option_names)){
                        $this->EvaluationOption->deleteAll(array('EvaluationOption.evaluation_question_code'=>$question_code,'not'=>array('EvaluationOption.name'=>$option_names)));
                    }else{
                        $this->EvaluationOption->deleteAll(array('EvaluationOption.evaluation_question_code'=>$question_code));
                    }
                    $upload_num++;
                }
            }
            if($upload_num==0){
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['upload'].'failed'."');window.location.href='/admin/evaluation_questions/upload/".$code."';</script>";
                die();
            }else{
                $upload_message="(".($upload_num).'/'.(sizeof($checkboxs)).")";
                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['upload'].'successfully'.$upload_message."');window.location.href='/admin/evaluation_questions/upload/".$code."';</script>";
                die();
            }
        }else{
            $this->redirect('upload/'.$code);
        }
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

    /**
     * 批量删除
     *
     */
    public function delete_all()
    {
        Configure::write('debug', 1);
        $result['code'] = 0;
        $result['msg'] = '系统错误';
        if ($this->RequestHandler->isPost()) {
            $ids = isset($_POST['ids']) ? $_POST['ids'] :0;
            $this->EvaluationQuestion->deleteAll(array('code' => $ids));
            $this->EvaluationOption->deleteAll(array('evaluation_question_code' => $ids));
            $result['msg'] = "删除成功";
            die(json_encode($result));
        } else {
            $this->redirect('/evaluations');
        }
    }
}