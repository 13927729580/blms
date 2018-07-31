<?php

/*****************************************************************************
 * Seevia 课时管理
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
 *这是一个名为 CourseClassesController 的控制器
 *课时管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class CourseClassesController extends AppController
{
    public $name = 'CourseClasses';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('Evaluation','CourseClassWare','CourseClass','CourseChapter','InformationResource','Course','CourseLearningPlan');

    /**
     *添加课时
     */
    public function add($code)
    {
        $course_info=$this->Course->find('first',array('conditions'=>array('Course.code'=>$code)));
        $this->menu_path = array('root' => '/hr/','sub' => '/courses/');
        $this->set('title_for_layout', $this->ld['add'].'-课程管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
        $this->navigations[] = array('name' => "编辑课程",'url' => '/courses/view/'.$course_info['Course']['id']);
        $this->navigations[] = array('name' => $this->ld['add']."课时",'url' => '');
        if ($this->RequestHandler->isPost()) {
            $course_chapter_info=$this->CourseChapter->find('first',array('conditions'=>array('CourseChapter.code'=>$code)));
            $this->data["CourseClass"]["course_code"]=$course_chapter_info["CourseChapter"]["course_code"];
            $this->data["CourseClass"]["chapter_code"]=$code;
            $this->CourseClass->save($this->data);
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->set('code', $code);
        $resource_info=$this->InformationResource->information_formated('courseware_type',$this->backend_locale,false);
        $this->set('resource_info',$resource_info);
    }

    /**
     *编辑课时
     */
    public function view($id)
    {
        $course_class_info=$this->CourseClass->find('first',array('conditions'=>array('CourseClass.id'=>$id)));
        $course_info=$this->Course->find('first',array('conditions'=>array('Course.code'=>$course_class_info['CourseClass']['course_code'])));
        if ($this->RequestHandler->isPost()) {
            $this->CourseClass->save($this->data);
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->menu_path = array('root' => '/hr/','sub' => '/courses/');
        $this->set('title_for_layout', $this->ld['edit'].'-课程管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "在线学习",'url' => '');
        $this->navigations[] = array('name' => "课程管理",'url' => '/courses/');
        $this->navigations[] = array('name' => "编辑课程",'url' => '/courses/view/'.$course_info['Course']['id']);
        $this->navigations[] = array('name' => $this->ld['edit']."课时",'url' => '');
        $this->set('course_class_info', $course_class_info);
        $resource_info=$this->InformationResource->information_formated('courseware_type',$this->backend_locale,false);
        $this->set('resource_info',$resource_info);
    }

    /**
     * 删除课时
     *
     *@param int $id
     */
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        $course_class_list=$this->CourseClass->find('list',array('fields'=>'id,code','conditions'=>array('CourseClass.id'=>$id)));
        if(!empty($course_class_list)){
		$this->CourseLearningPlan->deleteAll(array('CourseLearningPlan.course_class_id'=>$id));
		$this->CourseClassWare->deleteAll(array('CourseClassWare.course_class_code' => $course_class_list));
		$this->CourseClass->deleteAll(array('CourseClass.id' => $id));
        }
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_member_success'];
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/courses/');
        }
    }

    /**
     * 删除课件
     *
     *@param int $id
     */
    public function ware_remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        $this->CourseClassWare->deleteAll(array('CourseClassWare.id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_member_success'];
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/courses/');
        }
	}

    function ajax_upload_course(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code'] = 0;
        $result['message'] = 'no file';
        if ($this->RequestHandler->isPost()) {
            $result['message'] = $this->ld['file_upload_error'];
            if(isset($_FILES['courseware'])&&sizeof($_FILES['courseware'])>0){
                $course_class_id=isset($_POST['course_class_id'])?$_POST['course_class_id']:0;
                $uplod_file_list=array();
                foreach($_FILES['courseware']['tmp_name'] as $k=>$file_tmp){
	                    if(!isset($_FILES['courseware']['error'][$k])||$_FILES['courseware']['error'][$k]!=0)continue;
	                    $filename=$_FILES['courseware']['name'][$k];
	                    $file_info = pathinfo($filename);
	                    $file_ext = isset($file_info['extension'])?$file_info['extension']:'';
	                    $file_name=md5($filename.$course_class_id.time()).".".$file_ext;
	                    $uplod_file_list[]=array(
	                        'file_tmp'=>$file_tmp,
	                        'file_name'=>$file_name
	                    );
                }
                if(!empty($uplod_file_list)){
                    $courseware_root=WWW_ROOT.'media/courseware/';
                    $this->mkdirs($courseware_root);
                    @chmod($imgaddr, 0777);
                    $uplod_files=array();
                    foreach($uplod_file_list as $v){
				$file_location=$courseware_root.$v['file_name'];
				$file_path="/media/courseware/".$v['file_name'];
				if (move_uploaded_file($v['file_tmp'], $file_location)) {
					$uplod_files[]=$file_path;
				}
                    }
                    if(!empty($uplod_files)){
                        $result['code'] = 1;
                        $result['message'] = implode(';',$uplod_files);
                    }
                }
            }
        }
        die(json_encode($result));
    }
    
    function ajax_modify(){
	        Configure::write('debug', 1);
	        $this->layout = 'ajax';
	        $result=array();
	        $result['code']='0';
	        $result['message']=$this->ld['modify_failed'];
	        $course_chapter_info=$this->CourseChapter->find('first',array('conditions'=>array('CourseChapter.code'=>$this->data["CourseClass"]["chapter_code"])));
	        $this->data["CourseClass"]["course_code"]=$course_chapter_info["CourseChapter"]["course_code"];
	        $this->CourseClass->save($this->data);
	        $course_class_id=$this->CourseClass->id;
	        $this->CourseClassWare->updateAll(array('CourseClassWare.chapter_code'=>"'".$this->data["CourseClass"]["chapter_code"]."'"),array('CourseClassWare.course_code'=>$course_chapter_info["CourseChapter"]["course_code"],'CourseClassWare.course_class_code'=>$this->data["CourseClass"]['code']));
	        $PreconditionCode=$this->data["CourseClass"]["course_code"].$course_class_id;
	        if(isset($this->data['Precondition'])&&!empty($this->data['Precondition'])){
	        	$submit_params=array();
	        	$this->loadModel('Precondition');
	        	$PreconditionList=$this->Precondition->find('list',array('fields'=>'params,id','conditions'=>array('object'=>'course_class','object_code'=>$PreconditionCode)));
	        	foreach($this->data['Precondition'] as $k=>$v){
	        		if($k=='shared_access'||$k=='share_count'){
	        			$PreconditionValue=array();
	        			$share_type_list=isset($v['share_type'])?$v['share_type']:array();
	        			$share_page_list=isset($v['share_page'])?$v['share_page']:array();
	        			$share_count_list=isset($v['share_count'])?$v['share_count']:array();
	        			foreach($share_type_list as $kk=>$vv){
	        				$PreconditionValue[]=implode(",",array(
	        						$vv,isset($share_page_list[$kk])?$share_page_list[$kk]:0,isset($share_count_list[$kk])?$share_count_list[$kk]:1
	        					));
	        			}
	        			$PreconditionValue=implode(chr(13).chr(10),$PreconditionValue);
	        		}else{
	        			$PreconditionValue=is_array($v)?implode(chr(13).chr(10),$v):$v;
	        		}
	        		$PreconditionData=array(
	        			'id'=>isset($PreconditionList[$k])?$PreconditionList[$k]:0,
	        			'object'=>'course_class',
	        			'object_code'=>$PreconditionCode,
	        			'params'=>$k,
	        			'value'=>$PreconditionValue
	        		);
	        		$this->Precondition->save($PreconditionData);
	        		$submit_params[]=$k;
	        	}
	        	if(empty($submit_params)){
	        		$this->Precondition->deleteAll(array('object'=>'course_class','object_code'=>$PreconditionCode));
	        	}else{
	        		$this->Precondition->deleteAll(array('object'=>'course_class','object_code'=>$PreconditionCode,'not'=>array('params'=>$submit_params)));
	        	}
	        }else{
	        	$this->loadModel('Precondition');
	        	$this->Precondition->deleteAll(array('object'=>'course_class','object_code'=>$PreconditionCode));
	        }
	        $result['code']='1';
	        $result['message']='操作成功';
	        die(json_encode($result));
    }
    
	function ajax_ware_modify(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $result['message']=$this->ld['modify_failed'];
        if(isset($this->data['CourseClassWare']['type'])&&$this->data["CourseClassWare"]["type"]!=""){
	        $course_class_info=$this->CourseClass->find('first',array('conditions'=>array('CourseClass.code'=>$this->data["CourseClassWare"]["course_class_code"])));
	        $this->data["CourseClassWare"]["course_code"]=$course_class_info["CourseClass"]["course_code"];
	        $this->data["CourseClassWare"]["chapter_code"]=$course_class_info["CourseClass"]["chapter_code"];
	        $this->data["CourseClassWare"]["course_class_code"]=$course_class_info["CourseClass"]["code"];
	        if($this->data["CourseClassWare"]["type"]=="evaluation" || $this->data["CourseClassWare"]["type"]=="b+api"|| $this->data["CourseClassWare"]["type"]=="activity"){
	        	$this->data["CourseClassWare"]["ware"]=isset($_POST['ware_list'])?$_POST['ware_list']:'';
	        }
	        $this->CourseClassWare->save($this->data);
	        $result['code']='1';
	        $result['message']='操作成功';
        }else{
        	$result['message']=$this->ld['type'].$this->ld['error'];
        }
        die(json_encode($result));
    }
    
	function ajax_edit($id){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $result['message']=$this->ld['modify_failed'];
        $course_class_info=$this->CourseClass->find('first',array('conditions'=>array('CourseClass.id'=>$id)));
        $result['code']='1';
        $result['data']=$course_class_info;
        die(json_encode($result));
    }
    
	function ajax_ware_edit($id){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $result['message']=$this->ld['modify_failed'];
        $course_ware_info=$this->CourseClassWare->find('first',array('conditions'=>array('CourseClassWare.id'=>$id)));
        $result['code']='1';
        $result['data']=$course_ware_info;
        die(json_encode($result));
    }
    
    function changeware($route){
        Configure::write('debug',0);
        $this->layout = 'ajax';
        $data=array();
        if ($route == 'evaluation') {
	            $s_info = $this->Evaluation->find('all', array('fields'=>'Evaluation.id,Evaluation.name','conditions' => array('Evaluation.status' => 1)));
	            if(!empty($s_info)){
	            	foreach($s_info as $k=>$v){
	            		$data[$k]['id']=$v['Evaluation']['id'];
	            		$data[$k]['val']=$v['Evaluation']['name'];
	            	}
	            }
        }
        if ($route == 'b+api') {
        	$this->loadModel('BridgeCourse');
            	$s_info = $this->BridgeCourse->find('all',array('fields'=>'BridgeCourse.bridge_id,BridgeCourse.title,BridgeCourse.loGUID'));
			if(!empty($s_info)){
	            	foreach($s_info as $k=>$v){
	            		$data[$k]['id']=$v['BridgeCourse']['bridge_id'];
	            		$data[$k]['val']=$v['BridgeCourse']['loGUID'].'-'.$v['BridgeCourse']['title'];
	            	}
	            }
        }
        if ($route == 'activity') {
        		$this->loadModel('Activity');
        		$activity_list=$this->Activity->find('all',array('conditions'=>array('Activity.status'=>'1'),'fields'=>"Activity.id,Activity.name"));
        		if(!empty($activity_list)){
        			foreach($activity_list as $k=>$v){
	            		$data[$k]['id']=$v['Activity']['id'];
	            		$data[$k]['val']=$v['Activity']['name'];
	            	}
        		}
        }
        $this->set('info', $data);
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
            $class_count = $this->CourseClass->find('count', array('conditions' => array('CourseClass.code' => $code, 'CourseClass.status' => "1")));
            if ($class_count == 0) {
                $result['code'] = 1;
            } else {
                $result['msg'] = "code已存在";
            }
            die(json_encode($result));
        } else {
            $this->redirect('/courses');
        }
    }
}