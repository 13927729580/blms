<?php

/*****************************************************************************
 * EvaluationCategory 课程分类
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为EvaluationCategoriesController的控制器
 *评测分类
 *
 *@var
 *@var
 *@var
 *@var
 */
class EvaluationCategoriesController extends AppController
{
	public $name = 'EvaluationCategories';
	public $helpers = array('Html','Pagination');
	public $uses = array('EvaluationCategory','InformationResource','Resource');
	public $components = array('RequestHandler','Pagination');
	    
	/**
	*	课程分类列表
	*/
	public function index($page=1,$limit=15){
		$_GET=$this->clean_xss($_GET);
		$this->layout = 'default_full';
		$this->pageTitle = '评测分类 - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '评测分类' , 'url' => '/evaluation_categories/');
        	$this->set('ur_heres', $this->ur_heres);
		
		$evaluation_category_data=$this->EvaluationCategory->evaluation_category_list();
		$this->set('evaluation_category_data',$evaluation_category_data);

		$params=array();
		$params['page']=$page;
		$params['limit']=$limit;
		$params['ControllerObj']=$this;
		if(isset($_GET['evaluation_category_code'])&&$_GET['evaluation_category_code']!=""){
			$params['evaluation_category_code']=$_GET['evaluation_category_code'];
			$this->set('evaluation_category_code',$_GET['evaluation_category_code']);
		}
		if(isset($_GET['evaluation_orderby'])&&$_GET['evaluation_orderby']!=""){
			$params['evaluation_orderby']=trim($_GET['evaluation_orderby']);
			$this->set('evaluation_orderby',$_GET['evaluation_orderby']);
		}
		$this->page_init($params);
	}
}
