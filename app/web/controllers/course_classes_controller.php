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
    public $uses = array('CourseClass','CourseChapter','InformationResource','Course');

    /**
     *添加课时
     */
    public function add($code)
    {
        $course_info=$this->Course->find('first',array('conditions'=>array('Course.code'=>$code)));
        $this->menu_path = array('root' => '/hr/','sub' => '/courses/');
        $this->set('title_for_layout', $this->ld['add'].'-课程管理- '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => "资源开发",'url' => '');
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
        $this->navigations[] = array('name' => "资源开发",'url' => '');
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
        //$result['message'] = $this->ld['delete_member_failure'];
        $course_class_info=$this->CourseClass->find('first',array('conditions'=>array('CourseClass.id'=>$id)));
        $this->CourseClass->deleteAll(array('CourseClass.id' => $id));
        if(!empty($course_class_info)){
        	$sum_courseware_hour_info=$this->CourseClass->find('first',array('fields'=>'sum(CourseClass.courseware_hour) as courseware_hour','conditions'=>array('CourseClass.course_code'=>$course_class_info["CourseClass"]["course_code"])));
	 	$sum_courseware_hour=isset($sum_courseware_hour_info[0]['courseware_hour'])?$sum_courseware_hour_info[0]['courseware_hour']:0;
	 	$this->Course->updateAll(array('Course.hour'=>"'".$sum_courseware_hour."'"),array('Course.code'=>$course_class_info["CourseClass"]["course_code"]));
        }
        //操作员日志
        $result['flag'] = 1;
        //$result['message'] = $this->ld['delete_member_success'];
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
            $result['message'] = '文件上传错误';
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
        //$result['message']=$this->ld['modify_failed'];
        $course_chapter_info=$this->CourseChapter->find('first',array('conditions'=>array('CourseChapter.code'=>$this->data["CourseClass"]["chapter_code"])));
        $this->data["CourseClass"]["course_code"]=$course_chapter_info["CourseChapter"]["course_code"];
        $this->CourseClass->save($this->data);
	$class_id=$this->CourseClass->id;
	if(empty($this->data['CourseClass']['id'])){
		$class_code='class_'.$class_id;
		$this->CourseClass->updateAll(array('CourseClass.code'=>"'".$class_code."'"),array('CourseClass.id'=>$class_id));
	 }
	 $sum_courseware_hour_info=$this->CourseClass->find('first',array('fields'=>'sum(CourseClass.courseware_hour) as courseware_hour','conditions'=>array('CourseClass.course_code'=>$course_chapter_info["CourseChapter"]["course_code"])));
	 $sum_courseware_hour=isset($sum_courseware_hour_info[0]['courseware_hour'])?$sum_courseware_hour_info[0]['courseware_hour']:0;
	 $this->Course->updateAll(array('Course.hour'=>"'".$sum_courseware_hour."'"),array('Course.code'=>$course_chapter_info["CourseChapter"]["course_code"]));
        $result['code']='1';
        $result['message']='操作成功';
        die(json_encode($result));
    }
    
	function ajax_edit($id){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        //$result['message']=$this->ld['modify_failed'];
        $course_class_info=$this->CourseClass->find('first',array('conditions'=>array('CourseClass.id'=>$id)));
        $result['code']='1';
        $result['data']=$course_class_info;
        die(json_encode($result));
    }

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