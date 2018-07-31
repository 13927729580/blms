<?php

/*****************************************************************************
 * CourseCcategory 课程分类
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为CourseCategoriesController的控制器
 *课程分类
 *
 *@var
 *@var
 *@var
 *@var
 */
class CourseCategoriesController extends AppController
{
	public $name = 'CourseCategories';
	public $helpers = array('Html','Pagination');
	public $uses = array('CourseType','CourseCategory','InformationResource','Resource');
	public $components = array('RequestHandler','Pagination');
	    
	/**
	*	课程分类列表
	*/
	public function index($page=1,$limit=15){
		$_GET=$this->clean_xss($_GET);
		$this->layout = 'default_full';
		$this->pageTitle = '课程分类 - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '课程分类' , 'url' => '/course_categories/');
        	$this->set('ur_heres', $this->ur_heres);
        	
		$course_category_data=$this->CourseCategory->course_category_list();
		$this->set('course_category_data',$course_category_data);
		$course_type_data=$this->CourseType->course_type_list();
		$this->set('course_type_data',$course_type_data);
		$params=array();
		$params['page']=$page;
		$params['limit']=$limit;
		$params['ControllerObj']=$this;
		if(isset($_GET['course_category_code'])&&$_GET['course_category_code']!=""){
			$params['course_category_code']=$_GET['course_category_code'];
			$this->set('course_category_code',$_GET['course_category_code']);
		}
		if(isset($_GET['course_type_code'])&&$_GET['course_type_code']!=""){
			$params['course_type_code']=$_GET['course_type_code'];
			$this->set('course_type_code',$_GET['course_type_code']);
		}
		if(isset($_GET['course_orderby'])&&$_GET['course_orderby']!=""){
			$params['course_orderby']=trim($_GET['course_orderby']);
			$this->set('course_orderby',$_GET['course_orderby']);
		}
		$this->page_init($params);
	}
	
	
	function view($category_id=0,$page=1,$limit=15){
		$_GET=$this->clean_xss($_GET);
		$this->layout = 'default_full';
		
		$category_detail=$this->CourseCategory->find('first',array('conditions'=>array('CourseCategory.status'=>'1','CourseCategory.id'=>$category_id)));
		if(empty($category_detail))$this->rediret('/');
		$this->pageTitle = $category_detail['CourseCategory']['name'].' - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => $category_detail['CourseCategory']['name'] , 'url' => '');
		$this->set('ur_heres', $this->ur_heres);
		$this->set('category_detail',$category_detail);
		
		$params=array();
		$params['id']=$category_id;
		$params['page']=$page;
		$params['limit']=$limit;
		$params['flash_type']='course';
		$params['ControllerObj']=$this;
		$this->page_init($params);
	}
}