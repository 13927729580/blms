<?php

/*****************************************************************************
 * Seevia
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/*
*这是一个名为 SearchsController 的控制器
*搜索控制器
*@var $name
*@var $components
*@var $helpers
*@var $uses
*@var $cacheQueries
*@var $cacheAction
*/
/**
 * 搜索.
 *
 *	针对关键字搜索进行处理
 *
 *@author   zhaoyincheng 
 *
 *@version  $Id$
 */
class SearchsController extends AppController
{
    public $name = 'Searchs';
    public $components = array('Pagination');
    public $uses = array('Article','Product','Topic','Flash','BrandI18n','Tag','Template','ProductAttribute','Attribute','Language','CategoryProductI18n');
    public $helpers = array('Pagination','Time','Xml','Rss','Text','Flash');

    public function index($keyword='',$limit=12){
    		$keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : $keyword;
    		$keyword=$this->clean_xss($keyword);
    		$limit = isset($this->configs['search_autocomplete_number']) ? $this->configs['search_autocomplete_number'] : $limit;
    		//请求来源
    		$referer_url=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'/';
		if(trim($keyword)==''){
			$this->flash($this->ld['xperia13'],$referer_url,5);
			return;
		}
		$this->set('search_keyword',$keyword);
		
		$this->ur_heres[] = array('name' => $this->ld['search'].chr(91).$keyword.chr(93),'url' => '/searchs/index/'.$keyword);
		$this->pageTitle = $this->ld['search'].chr(91).$keyword.chr(93).' - '.$this->configs['shop_title'];
		
		$page=isset($_REQUEST['page'])?$_REQUEST['page']:1;
		$product_page=isset($_REQUEST['product_page'])?$_REQUEST['product_page']:$page;
		$article_page=isset($_REQUEST['article_page'])?$_REQUEST['article_page']:$page;
		$course_page=isset($_REQUEST['course_page'])?$_REQUEST['course_page']:$page;
		$evaluation_page=isset($_REQUEST['evaluation_page'])?$_REQUEST['evaluation_page']:$page;
		$activity_page=isset($_REQUEST['activity_page'])?$_REQUEST['activity_page']:$page;
		
		$search_result=false;
		
		$page_parameters=array();
		$default_page_parameters_get=array('keyword' => $keyword,'limit'=>$limit);
		$page_parameters['route']=array('controller' => 'searchs','action' => 'index');
		
		$product_conditions=array();
		$product_conditions['and']['Product.status'] = 1;
		$product_conditions['and']['Product.forsale'] = 1;
		$product_conditions['and']['Product.bestbefore'] = 0;
		//$product_conditions['and']['or']['Product.code like'] = "%{$keyword}%";
		$product_conditions['and']['or']['ProductI18n.name like'] = "%{$keyword}%";
		$product_conditions['and']['or']['ProductI18n.meta_keywords like'] = "%{$keyword}%";
		$product_total = $this->Product->find('count', array('conditions' => $product_conditions));
		if(!empty($product_total)){
			$search_result=true;
			//分页参数
			$page_parameters_get=array('article_page'=>$article_page,'course_page'=>$course_page,'evaluation_page'=>$evaluation_page,'activity_page'=>$activity_page);
			$page_parameters['get']=array_merge($default_page_parameters_get,$page_parameters_get);
			$product_page_options = array('page' => $product_page,'total'=>$product_total,'show' => $limit,'modelClass' => 'Product');
			$product_page_list = $this->Pagination->init($product_conditions, $page_parameters, $product_page_options); // Added
			$this->set('product_page_list',$product_page_list);
			
			$product_options = array();
			$product_options['conditions'] = $product_conditions;
			$product_options['page'] = $product_page;
			$product_options['limit'] = $limit;
			$product_options['order'] = 'Product.modified desc,Product.id';
			$product_list = $this->Product->find_all_products($product_options);
			$this->set('product_list',$product_list);
		}
		
		$article_conditions=array();
		$article_conditions['and']['Article.status'] = 1;
		$article_conditions['and']['or']['ArticleI18n.title like'] = "%{$keyword}%";
		$article_conditions['and']['or']['ArticleI18n.subtitle like'] = "%{$keyword}%";
		$article_conditions['and']['or']['ArticleI18n.meta_description like'] = "%{$keyword}%";
		$article_total = $this->Article->find('count', array('conditions' => $article_conditions));
		if(!empty($article_total)){
			$search_result=true;
			//分页参数
			$page_parameters_get=array('product_page'=>$product_page,'course_page'=>$course_page,'evaluation_page'=>$evaluation_page,'activity_page'=>$activity_page);
			$page_parameters['get']=array_merge($default_page_parameters_get,$page_parameters_get);
			$article_page_options = array('page' => $article_page,'total'=>$article_total,'show' => $limit,'modelClass' => 'Article');
			$article_page_list = $this->Pagination->init($article_conditions, $page_parameters, $article_page_options); // Added
			$this->set('article_page_list',$article_page_list);
			//分页end
			$article_options = array();
			$article_options['conditions'] = $article_conditions;
			$article_options['limit'] = $limit;
			$article_options['page'] = $article_page;
			$article_options['order'] = 'Article.modified desc,Article.id';
			$article_list = $this->Article->find('all', $article_options); //model
			$this->set('article_list',$article_list);
		}
		
		$this->loadModel('Course');
		$course_conditions=array();
		$course_conditions['and']['Course.status'] = 1;
		$course_conditions['and']['Course.user_id'] = 0;
		$course_conditions['and']['Course.visibility'] = 0;
		$course_conditions['and']['or']['Course.name like'] = "%{$keyword}%";
		$course_conditions['and']['or']['Course.description like'] = "%{$keyword}%";
		$course_total = $this->Course->find('count', array('conditions' => $course_conditions));
		if(!empty($course_total)){
			$search_result=true;
			//分页参数
			$page_parameters_get=array('article_page'=>$article_page,'product_page'=>$product_page,'evaluation_page'=>$evaluation_page,'activity_page'=>$activity_page);
			$page_parameters['get']=array_merge($default_page_parameters_get,$page_parameters_get);
			$course_page_options = array('page' => $course_page,'total'=>$course_total,'show' => $limit,'modelClass' => 'Course');
			$course_page_list = $this->Pagination->init($course_conditions, $page_parameters, $course_page_options); // Added
			$this->set('course_page_list',$course_page_list);
			//分页end
			$course_options = array();
			$course_options['conditions'] = $course_conditions;
			$course_options['limit'] = $limit;
			$course_options['page'] = $course_page;
			$course_options['order'] = 'Course.modified desc,Course.id';
			$course_list = $this->Course->find('all', $course_options); //model
			$this->set('course_list',$course_list);
		}
		
		$this->loadModel('Evaluation');
		$evaluation_conditions=array();
		$evaluation_conditions['and']['Evaluation.status'] = 1;
		$evaluation_conditions['and']['Evaluation.user_id'] = 0;
		$evaluation_conditions['and']['Evaluation.visibility'] = 0;
		$evaluation_conditions['and']['or']['Evaluation.name like'] = "%{$keyword}%";
		$evaluation_conditions['and']['or']['Evaluation.description like'] = "%{$keyword}%";
		$evaluation_total = $this->Evaluation->find('count', array('conditions' => $evaluation_conditions));
		if(!empty($evaluation_total)){
			$search_result=true;
			//分页参数
			$page_parameters_get=array('article_page'=>$article_page,'product_page'=>$product_page,'course_page'=>$course_page,'activity_page'=>$activity_page);
			$page_parameters['get']=array_merge($default_page_parameters_get,$page_parameters_get);
			$evaluation_page_options = array('page' => $evaluation_page,'total'=>$evaluation_total,'show' => $limit,'modelClass' => 'Evaluation');
			$evaluation_page_list = $this->Pagination->init($evaluation_conditions, $page_parameters, $evaluation_page_options); // Added
			$this->set('evaluation_page_list',$evaluation_page_list);
			//分页end
			$evaluation_options = array();
			$evaluation_options['conditions'] = $evaluation_conditions;
			$evaluation_options['limit'] = $limit;
			$evaluation_options['page'] = $evaluation_page;
			$evaluation_options['order'] = 'Evaluation.modified desc,Evaluation.id';
			$evaluation_list = $this->Evaluation->find('all', $evaluation_options); //model
			$this->set('evaluation_list',$evaluation_list);
		}
		
		$this->loadModel('Activity');
		$this->loadModel('ActivityTag');
		$activity_conditions=array();
		$activity_conditions['and']['Activity.status'] = 1;
		$activity_conditions['and']['Activity.start_date <='] = date('Y-m-d 23:59:59');
		$activity_conditions['and']['Activity.end_date >='] = date('Y-m-d 00:00:00');
		$activity_conditions['and']['or']['Activity.name like'] = "%{$keyword}%";
		$activity_conditions['and']['or']['Activity.description like'] = "%{$keyword}%";
		$activity_tags=$this->ActivityTag->find('list',array('fields'=>'id,activity_id','conditions'=>array('tag_name like'=>"%{$keyword}%")));
		if(!empty($activity_tags)){
			$activity_conditions['and']['or']['Activity.id'] =$activity_tags;
		}
		$activity_total = $this->Activity->find('count', array('conditions' => $activity_conditions));
		if(!empty($activity_total)){
			$search_result=true;
			//分页参数
			$page_parameters_get=array('article_page'=>$article_page,'product_page'=>$product_page,'course_page'=>$course_page,'evaluation_page'=>$evaluation_page);
			$page_parameters['get']=array_merge($default_page_parameters_get,$page_parameters_get);
			$activity_page_options = array('page' => $activity_page,'total'=>$activity_total,'show' => $limit,'modelClass' => 'Activity');
			$activity_page_list = $this->Pagination->init($activity_conditions, $page_parameters, $activity_page_options); // Added
			$this->set('activity_page_list',$activity_page_list);
			//分页end
			$activity_options = array();
			$activity_options['conditions'] = $activity_conditions;
			$activity_options['limit'] = $limit;
			$activity_options['page'] = $activity_page;
			$activity_options['order'] = 'Activity.start_date,Activity.id';
			$activity_list = $this->Activity->find('all', $activity_options); //model
			$this->set('activity_list',$activity_list);
			if(!empty($activity_list)){
				$activity_ids=array();
				foreach($activity_list as $v)$activity_ids[]=$v['Activity']['id'];
				$activity_tag_list=$this->ActivityTag->find('list',array('fields'=>'id,tag_name,activity_id','conditions'=>array('activity_id'=>$activity_ids,'tag_name <>'=>'')));
				$this->set('activity_tag_list',$activity_tag_list);
			}
		}
		
		if(!$search_result){
			$this->flash($this->ld['keyword_not_searched'],$referer_url,5);
			return;
		}
    }

    /**
     *	显示微信搜索结果页.
     *
     *	@param $page 输入分页
     */
    public function keyword($keyword = '', $page = 1, $a_page = 1, $limit = 12, $type = '')
    {
		//搜索内容 0:所有，1:商品，2:文章，3:专题，4:页面
		$search_content=isset($this->configs['search_content'])?explode(';',$this->configs['search_content']):array();
		
		$_GET=$this->clean_xss($_GET);
		$_REQUEST=$this->clean_xss($_REQUEST);
		$searchtype = isset($_REQUEST['searchtype']) ? $_REQUEST['searchtype'] : '';
		$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : $type;
		$limit = isset($this->configs['search_autocomplete_number']) ? $this->configs['search_autocomplete_number'] : $limit;
		$keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : $keyword;
		
		$keyword=$this->clean_xss($keyword);
		$type=$this->clean_xss($type);
		$page=intval($page);
		$a_page=intval($a_page);
		$limit=intval($limit);
		if ($keyword == 'All') {
			$keyword = '';
		}
		$strkeyword = $keyword;
		$this->set('keyword', $strkeyword);
		$url_get = 'keyword='.$strkeyword;
		
		$title_txt = !empty($type) && !empty($this->ld[$type]) ? $this->ld[$type] : $strkeyword;
		//面包屑
		$this->ur_heres[] = array('name' => $this->ld['search'].':'.$title_txt, 'url' => '');
		$this->pageTitle = $this->ld['search'].' - '.$title_txt.' - '.$this->configs['shop_title'];
		
		if (trim($keyword) != '') {
			$keyword = preg_split('#\s+#', $keyword);
		}
		// 商品搜索
		if(in_array('0',$search_content)||in_array('1',$search_content)){
			$order_fields = array('Product.created','Product.sale_stat','Product.shop_price');
			if (!empty($order_field) && in_array($order_field, $order_fields)) {
				
			} else {
				$order_field = 'Product.sale_stat';
				$order_type = 'desc';
			}
			$conditions=array();
			$conditions['and']['Product.status'] = 1;
			$conditions['and']['Product.forsale'] = 1;
			$conditions['and']['Product.bestbefore'] = 0;
			$conditions2=array();
			$conditions2['or']['and']['Product.status'] = 1;
			$conditions2['or']['and']['Product.forsale'] = 1;
			$conditions2['or']['and']['Product.bestbefore'] = 0;
			$conditions2['or']['or']['Product.recommand_flag'] = 1;
			$conditions2['or']['or']['Product.promotion_status'] = 1;
			
			if ($type == 'new_arrival') {
				$order = 'Product.created desc';
			} elseif ($type == 'recommend') {
				$conditions['and']['Product.recommand_flag'] = 1;
			}
			//模糊搜索
			if (isset($this->configs['product_search_type']) && $this->configs['product_search_type'] == '0') {
				if (is_array($keyword) && sizeof($keyword) > 0) {
					$brand_conditions=array();//品牌搜索
					$brand_conditions['BrandI18n.locale']=$this->locale;
					$tag_conditions = array();//标签搜索
					$tag_conditions['and']['type'] = 'P';
					foreach ($keyword as $k => $v) {
						$conditions['and']['or'][0]['or'][$k]['Product.code like'] = "%$v%";
						$conditions['and']['or'][1]['or'][$k]['ProductI18n.name like'] = "%$v%";
						$conditions['and']['or'][2]['or'][$k]['ProductI18n.meta_keywords like'] = "%$v%";
						$brand_conditions['and']['or'][$k]['BrandI18n.name like']="%{$v}%";
						$tag_conditions['and']['or'][$k]['name like'] = "%$v%";
					}
					$brand_ids= $this->BrandI18n->find('list', array('fields' => array('BrandI18n.brand_id'), 'conditions' => $brand_conditions));
					if(!empty($brand_ids)){
						$conditions['and']['or'][3]['or']['Product.brand_id'] = $brand_ids;
					}
					$tag_infos = $this->Tag->find('all', array('conditions' => $tag_conditions, 'fields' => 'Tag.type_id', 'group' => 'Tag.type_id'));
					if (!empty($tag_infos)) {
						$tag_pids=array();
						foreach($tag_infos as $v){
							$tag_pids[]=$v['Tag']['type_id'];
						}
						$conditions['and']['or'][4]['or']['Product.id'] = $tag_pids;
					}
				}
			} elseif (isset($this->configs['product_search_type']) && $this->configs['product_search_type'] == '1') {
				//绝对搜索
				if (is_array($keyword) && sizeof($keyword) > 0) {
					$brand_conditions=array();//品牌搜索
					$brand_conditions['BrandI18n.locale']=$this->locale;
					$tag_conditions = array();//标签搜索
					$tag_conditions['and']['type'] = 'P';
					foreach ($keyword as $k => $v) {
						$conditions['and']['or'][0]['and'][$k]['Product.code like'] = "%$v%";
						$conditions['and']['or'][1]['and'][$k]['ProductI18n.name like'] = "%$v%";
						$conditions['and']['or'][2]['and'][$k]['ProductI18n.meta_keywords like'] = "%$v%";
						$brand_conditions['and']['or'][$k]['BrandI18n.name like']="%{$v}%";
						$tag_conditions['and']['or'][$k]['name like'] = "%$v%";
					}
					$brand_ids= $this->BrandI18n->find('list', array('fields' => array('BrandI18n.brand_id'), 'conditions' => $brand_conditions));
					if(!empty($brand_ids)){
						$conditions['and']['or'][3]['or']['Product.brand_id'] = $brand_ids;
					}
					$tag_infos = $this->Tag->find('all', array('conditions' => $tag_conditions, 'fields' => 'Tag.type_id', 'group' => 'Tag.type_id'));
					if (!empty($tag_infos)) {
						$tag_pids=array();
						foreach($tag_infos as $v){
							$tag_pids[]=$v['Tag']['type_id'];
						}
						$conditions['and']['or'][4]['or']['Product.id'] = $tag_pids;
					}
				}
			}
			if (isset($_GET['search_categories']) && trim($_GET['search_categories']) != '') {
				$search_categories_str = split(';', $_GET['search_categories']);
				$conditions['and']['Product.category_id'] = $search_categories_str[0];
				$this->set('search_categories', $_GET['search_categories']);
				$url_get .= '&search_categories='.$_GET['search_categories'];
			}
			if (isset($_GET['search_brand']) && trim($_GET['search_brand']) != '') {
				$search_brand_str = split(';', $_GET['search_brand']);
				$conditions['AND']['Product.brand_id'] = $search_brand_str[0];
				$this->set('search_brand', $_GET['search_brand']);
				$url_get .= '&search_brand='.$_GET['search_brand'];
			}
			if (isset($_GET['search_attribute']) && !empty($_GET['search_attribute'])) {
				$search_attribute_arr = $_GET['search_attribute'];
				$search_attribute = array();
				$search_attribute_ids = array();
				$search_attribute_vals = array();
				if(is_array($search_attribute_arr)){
					foreach ($search_attribute_arr as $v) {
						$search_attribute_str = array();
						$search_attribute_str = split(';', $v);
						$search_attribute_ids[] = $search_attribute_str[0];
						$search_attribute_vals[] = $search_attribute_str[1];
						$search_attribute[$search_attribute_str[0]] = $search_attribute_str[1];
						$url_get .= ('&search_attribute[]='.($search_attribute_str[0].';'.$search_attribute_str[1]));
					}
				}
				$search_pro_list = $this->ProductAttribute->find('list', array('conditions' => array('ProductAttribute.attribute_id' => $search_attribute_ids, 'ProductAttribute.attribute_value' => $search_attribute_vals), 'fields' => array('ProductAttribute.product_id')));
				if (!empty($search_pro_list)) {
					$conditions['and']['Product.id'] = $search_pro_list;
				}
				$this->set('search_attribute', $search_attribute);
			}
			if (isset($_GET['search_price_start']) && trim($_GET['search_price_start']) != '') {
				$conditions['and']['Product.shop_price >='] = floatval($_GET['search_price_start']);
				$this->set('search_price_start', floatval($_GET['search_price_start']));
				$url_get .= '&search_price_start='.floatval($_GET['search_price_start']);
			}
			if (isset($_GET['search_price_end']) && trim($_GET['search_price_end']) != '') {
				$conditions['and']['Product.shop_price <='] = floatval($_GET['search_price_end']);
				$this->set('search_price_end', floatval($_GET['search_price_end']));
				$url_get .= '&search_price_end='.floatval($_GET['search_price_end']);
			}
			$options = array();
			$options['conditions'] = $conditions;
			$options2 = array();
			$options2['conditions'] = $conditions2;
			//排序的判断
			if (isset($this->configs['product_order']) && !empty($this->configs['product_order']) && $this->configs['product_order'] !== 'forsale' && $this->configs['product_order'] !== 'category') {
				$options['order'] = $this->configs['product_order'];
			} elseif (isset($this->configs['product_order']) && !empty($this->configs['product_order']) && $this->configs['product_order'] == 'category') {
				$options['order'] = 'category_id';
			} else {
				$options['order'] = $order_field.' '.$order_type;
			}
			$options['limit'] = $limit;
			$options['page'] = $page;
			$pro_total = $this->Product->find('count', array('conditions' => $conditions));
			$product_list = $this->Product->find_all_products($options);
			//get参数
			$parameters['get'] = array($url_get);
			//地址路由参数（和control,action的参数对应）
			$parameters['route'] = array('controller' => 'searchs','action' => 'keyword','keyword' => $strkeyword,'page' => $page,'a_page' => $a_page,'limit' => $limit,'type' => $type);
			//分页参数
			$page_options = array('page' => $page,'show' => $limit,'modelClass' => 'Product');
			$page = $this->Pagination->init($conditions, $parameters, $page_options); // Added
			//分页end
			$this->set('pages_list', $page);
			
			if (!empty($product_list)) {
				$pro_search_ids = array();
				$brand_array = array();
				$category_array = array();
				$brand_ids = array();
				$category_ids = array();
				$brand_names = array();
				$categories = array();
				foreach ($product_list as $k => $v) {
					$pro_search_ids[] = $v['Product']['id'];
					if (!in_array($v['Product']['brand_id'], $brand_ids)&&$v['Product']['brand_id']>0) {
						$brand_ids[] = $v['Product']['brand_id'];
					}
					if (!in_array($v['Product']['category_id'], $category_ids)&&$v['Product']['category_id']>0) {
						$category_ids[] = $v['Product']['category_id'];
					}
				}
				$attr_infos = $this->ProductAttribute->find('list', array('conditions' => array('ProductAttribute.product_id' => $pro_search_ids), 'fields' => array('ProductAttribute.attribute_id', 'ProductAttribute.attribute_value'), 'order' => 'ProductAttribute.attribute_id', 'order' => 'ProductAttribute.attribute_id,ProductAttribute.attribute_value'));
				$this->set('attr_infos', $attr_infos);
				$pro_arr_info = $this->ProductAttribute->find('all', array('fields' => array('ProductAttribute.attribute_id', 'count(*) as `datacount`'), 'conditions' => array('ProductAttribute.locale' => $this->locale, 'ProductAttribute.product_id' => $pro_search_ids, 'ProductAttribute.attribute_value !=' => ''), 'group' => 'ProductAttribute.attribute_id'));
				$attribute_ids = array();
				$product_attribute_datas = array();
				foreach ($pro_arr_info as $v) {
					if ($v[0]['datacount'] == $pro_total) {
						$attribute_ids[] = $v['ProductAttribute']['attribute_id'];
					}
				}
				$product_attribute_option = $this->ProductAttribute->find('all', array('fields' => array('ProductAttribute.attribute_id', 'ProductAttribute.attribute_value'), 'conditions' => array('ProductAttribute.locale' => $this->locale, 'ProductAttribute.product_id' => $pro_search_ids, 'ProductAttribute.attribute_id' => $attribute_ids, 'ProductAttribute.attribute_value !=' => ''), 'order' => 'ProductAttribute.attribute_value'));
				$product_attribute_options = array();
				foreach ($product_attribute_option as $v) {
					$product_attribute_options[$v['ProductAttribute']['attribute_id']][$v['ProductAttribute']['attribute_value']] = $v['ProductAttribute']['attribute_value'];
				}
				$attribute_infos = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $attribute_ids, 'Attribute.status' => 1)));
				foreach ($attribute_infos as $v) {
					$product_attribute_datas[$v['Attribute']['id']]['id'] = $v['Attribute']['id'];
					$product_attribute_datas[$v['Attribute']['id']]['name'] = $v['AttributeI18n']['name'];
					$product_attribute_datas[$v['Attribute']['id']]['option'] = $product_attribute_options[$v['Attribute']['id']];
				}
				$this->set('product_attribute_datas', $product_attribute_datas);
				
				if (!empty($brand_ids)) {
					$brand_cond=array();
					$brand_cond['BrandI18n.locale']=$this->locale;
					$brand_cond['BrandI18n.brand_id']=$brand_ids;
					$brand_names = $this->BrandI18n->find('list', array('fields' => 'BrandI18n.brand_id,BrandI18n.name', 'conditions' => $brand_cond));
				}
				if (!empty($category_ids)) {
					$category_cond=array();
					$category_cond['CategoryProductI18n.locale']=$this->locale;
					$category_cond['CategoryProductI18n.category_id']=$category_ids;
					$categories = $this->CategoryProductI18n->find('list', array('conditions' => $category_cond, 'fields' => 'CategoryProductI18n.category_id,CategoryProductI18n.name'));
				}
				$this->set('brand_names', $brand_names);
				$this->set('categories', $categories);
				
				$UserLike_data=array();
				$UserFavorite_data=array();
				if(isset($_SESSION['User'])&&!empty($pro_search_ids)){
					$this->loadModel('UserLike');
					$this->loadModel('UserFavorite');
					$user_id=$_SESSION['User']['User']['id'];
				$UserLike_data=$this->UserLike->find('list',array('fields'=>'type_id,id','conditions'=>array('UserLike.user_id'=>$user_id,'UserLike.action'=>'like','UserLike.type'=>'P','UserLike.type_id'=>$pro_search_ids)));
				$UserFavorite_data=$this->UserFavorite->find('list',array('fields'=>'type_id,id','conditions'=>array('UserFavorite.user_id'=>$user_id,'UserFavorite.status'=>'1','UserFavorite.type'=>'P','UserFavorite.type_id'=>$pro_search_ids)));
					foreach($product_list as $k=>$v){
						$product_list[$k]['UserLike']=isset($UserLike_data[$v['Product']['id']])?'1':'0';
						$product_list[$k]['UserFavorite']=isset($UserFavorite_data[$v['Product']['id']])?'1':'0';
					}
				}
			}
			$this->set('products', $product_list);
		}
		
		// 文章搜索
		if(in_array('0',$search_content)||in_array('2',$search_content)){
			$article_cond=array();
			$article_cond['and']['Article.status'] = 1;
			if ($strkeyword != '') {
				$article_cond['and']['or']['ArticleI18n.title like'] = "%{$strkeyword}%";
				$article_cond['and']['or']['ArticleI18n.meta_description like'] = "%{$strkeyword}%";
			}
			if (is_array($keyword) && sizeof($keyword) > 0) {
				$tag_conditions=array();
				$tag_conditions['and']['type'] = 'A';
				foreach ($keyword as $k => $v) {
					$article_cond['and']['or'][0]['and'][$k]['ArticleI18n.title like'] = "%$v%";
					$article_cond['and']['or'][1]['and'][$k]['ArticleI18n.meta_description like'] = "%$v%";
					$tag_conditions['and']['or'][]['name like'] = "%$v%";
				}
				$tag_infos = $this->Tag->find('all', array('conditions' => $tag_conditions, 'fields' => 'Tag.type_id', 'group' => 'Tag.type_id'));
				if (!empty($tag_infos)) {
					$tag_aids = array();
					foreach ($tag_infos as $t) {
						$tag_aids[] = $t['Tag']['type_id'];
					}
					$article_cond['or']['Article.id'] = $tag_aids;
				}
			}else if($strkeyword != ''){
				$article_cond['and']['or']['ArticleI18n.title like'] = "%{$strkeyword}%";
				$article_cond['and']['or']['ArticleI18n.meta_description like'] = "%{$strkeyword}%";
			}
			//分页start
			//get参数
			$a_parameters['get'] = array($url_get);
			//地址路由参数（和control,action的参数对应）
			$a_parameters['route'] = array('controller' => 'searchs','action' => 'keyword','keyword' => $strkeyword,'page' => $page,'a_page' => $a_page,'limit' => $limit,'type' => $type);
			//分页参数
			$a_options = array('page' => $a_page,'show' => $limit,'modelClass' => 'Article');
			$article_page = $this->Pagination->init($article_cond, $a_parameters, $a_options); // Added
			//分页end
			$a_options = array();
			$a_options['conditions'] = $article_cond;
			$a_options['limit'] = $limit;
			$a_options['page'] = $article_page;
			$articles = $this->Article->find('all', $a_options); //model
			$this->set('articles', $articles);
		}
        	$this->render('both_search');
    }

    public function both_search($keyword = '', $page = 1, $a_page = 1, $limit = 12, $type = 0)
    {
    	 $_GET=$this->clean_xss($_GET);
    	 $_REQUEST=$this->clean_xss($_REQUEST);
    	 
        $article_keyword = $keyword;
        $this->page_init();
        $limit = UrlDecode($limit);

       //带冒号的关键字，对GET过来的参数做替代处理
           if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
               $keyword = $_GET['keyword'];
           }
        if ($keyword == 'All') {
            $keyword = '';
        }
        $strkeyword = $keyword;
        if ($strkeyword == '') {
            $strkeyword = 'All Products';
        }
//       	$this->product_order_field=$order_field;
        if (trim($keyword) != '') {
            $keyword = preg_split('#\s+#', $keyword);
        }
        //面包屑
        $this->ur_heres[] = array('name' => $this->ld['search'].':'.$strkeyword, 'url' => '');
        //搜索轮播
        $flash_condition['flash_type'] = 'AS';
        $flash_list = $this->Flash->get_module_infos($flash_condition);
        $this->set('flash_list', $flash_list);
        $this->set('meta_description', $strkeyword);
        $this->set('meta_keywords', $strkeyword);
//        if($order_field=='Product.created'){
//        	$this->pageTitle = '新品上架 - '.sprintf($this->ld['page'],$page).' - '.$this->configs['shop_title'];
//        }elseif($order_field=='Product.sale_stat'){
//        	$this->pageTitle = '火爆团购- '.sprintf($this->ld['page'],$page).' - '.$this->configs['shop_title'];
//        }else{
//        	$this->pageTitle = $strkeyword.' - '.sprintf($this->ld['page'],$page).' - '.$this->configs['shop_title'];
//        }
        if (empty($limit)) {
            $limit = isset($this->configs['products_category_page_size']) ? $this->configs['products_category_page_size'] : ((!empty($limit)) ? $limit : 20);
        }
//        if (empty($showtype)) {
//            $showtype = isset($this->configs['products_list_showtype']) ? $this->configs['products_list_showtype'] : ((!empty($showtype)) ? $showtype : 'L');
//        }
        $order_fields = array('Product.created','Product.sale_stat','Product.shop_price');
        if (!empty($order_field) && in_array($order_field, $order_fields)) {
        } else {
            $order_field = 'Product.sale_stat';
            $order_type = 'desc';
        }
        if ($limit == 'all') {
            $limit = 99999;
        }
//        $this->set('search_eye',1);
        $conditions['AND']['Product.status'] = 1;
        $conditions['AND']['Product.forsale'] = 1;
        $conditions['AND']['Product.bestbefore'] = 0;
        $conditions2['OR']['AND']['Product.status'] = 1;
        $conditions2['OR']['AND']['Product.forsale'] = 1;
        $conditions2['OR']['AND']['Product.bestbefore'] = 0;
        $conditions2['OR']['OR']['Product.recommand_flag'] = 1;
        $conditions2['OR']['OR']['Product.promotion_status'] = 1;
        if ($type == 'promotion') {//促销商品
        } elseif ($type == 'new_arrival') {
            //新品
            $order = 'Product.created desc';
        } elseif ($type == 'recommend') {
            //推荐
            $conditions['AND']['Product.recommand_flag'] = 1;
        } else {
            //type不合法时跳转报错
            //$this->render('/errors/missing_controller.ctp');
        }
    //	$bran_sel=array();
    // $bran_sel=$this->Brand->find('list',array('fields'=>array('Brand.id','BrandI18n.name'));
        //模糊搜索
        if (isset($this->configs['product_search_type']) && $this->configs['product_search_type'] == '0') {
            if (is_array($keyword) && sizeof($keyword) > 0) {
                foreach ($keyword as $k => $v) {
                    $conditions['AND']['OR'][0]['OR'][$k]['Product.code like'] = "%$v%";
                    $conditions['AND']['OR'][1]['OR'][$k]['ProductI18n.name like'] = "%$v%";
                    $conditions['AND']['OR'][2]['OR'][$k]['ProductI18n.meta_keywords like'] = "%$v%";
                    $brand_ids_array = $this->BrandI18n->find('all', array('fields' => array('BrandI18n.brand_id'), 'conditions' => array('BrandI18n.name like' => "%$v%")));
                    if (is_array($brand_ids_array) && isset($brand_ids_array) && !empty($brand_ids_array) && sizeof($brand_ids_array) > 0) {
                        $brand_ids = array();
                        foreach ($brand_ids_array as $kk => $vv) {
                            $brand_ids[$kk] = $vv['BrandI18n']['brand_id'];
                        }
                        $conditions['AND']['OR'][3]['OR'][$k]['Product.brand_id'] = $brand_ids;
                    }
                    $tag_conditions['and']['OR'][$k]['name like'] = "%$v%";
                }
                $tag_in = $this->Tag->find('first');
                $tag_conditions = array();
                $keywords = array();
                if (is_array($keyword)) {
                    foreach ($keyword as $k => $v) {
                        $tag_conditions['and']['or'][$k]['TagI18n.name like'] = "%$v%";
                    }
                }
                $tag_conditions['and']['type'] = 'P';
                //  $tag_conditions['and']['TagI18n.name like'] ="%$keyword%";
                $tag_infos = $this->Tag->find('all', array('conditions' => $tag_conditions, 'fields' => 'Tag.type_id', 'group' => 'Tag.type_id'));
                if (!empty($tag_infos)) {
                    $pids = array();
                    foreach ($tag_infos as $t) {
                        $pids[] = $t['Tag']['type_id'];
                    }
                    $conditions['AND']['OR'][4]['OR']['Product.id'] = $pids;
                }
            }
            $this->set('keyword', $keyword);
        } elseif (isset($this->configs['product_search_type']) && $this->configs['product_search_type'] == '1') {
            //绝对搜索
             if (is_array($keyword) && sizeof($keyword) > 0) {
                 foreach ($keyword as $k => $v) {
                     $conditions['AND']['OR'][0]['and'][$k]['Product.code like'] = "%$v%";
                     $conditions['AND']['OR'][1]['and'][$k]['ProductI18n.name like'] = "%$v%";
                     $conditions['AND']['OR'][2]['and'][$k]['ProductI18n.meta_keywords like'] = "%$v%";
                     $brand_ids_array = $this->BrandI18n->find('all', array('fields' => array('BrandI18n.brand_id'), 'conditions' => array('BrandI18n.name like' => "%$v%")));
                     if (is_array($brand_ids_array) && isset($brand_ids_array) && !empty($brand_ids_array) && sizeof($brand_ids_array) > 0) {
                         $brand_ids = array();
                         foreach ($brand_ids_array as $kk => $vv) {
                             $brand_ids[$kk] = $vv['BrandI18n']['brand_id'];
                         }
                         $conditions['AND']['OR'][3]['and'][$k]['Product.brand_id'] = $brand_ids;
                     }
                     $tag_conditions['and']['OR'][$k]['name'] = $v;
                 }
                 $tag_in = $this->Tag->find('first');
                 $tag_conditions = array();
                 $keywords = array();
                 if (is_array($keyword)) {
                     foreach ($keyword as $k => $v) {
                         $tag_conditions['and']['or'][$k]['TagI18n.name'] = $v;
                     }
                 }
                //$tag_conditions['and']['or']['type'] ='P';
                //$tag_conditions['and']['TagI18n.name like'] ="%$keyword%";
                $tag_infos = $this->Tag->find('all', array('conditions' => $tag_conditions, 'fields' => 'Tag.type_id', 'group' => 'Tag.type_id'));
                 if (!empty($tag_infos)) {
                     $pids = array();
                     foreach ($tag_infos as $t) {
                         $pids[] = $t['Tag']['type_id'];
                     }
                     $conditions['AND']['OR'][4]['Product.id'] = $pids;
                 }
             }
            $this->set('keyword', $keyword);
        }
        if ($strkeyword == 'All Products') {
            $this->set('keyword', '');
        } else {
            $this->set('keyword', $strkeyword);
        }
        if (isset($keyword) && !empty($keyword)) {
            $tag_in = $this->Tag->find('first');
            $tag_conditions = array();
            $keywords = array();
            if (is_array($keyword)) {
                foreach ($keyword as $k => $v) {
                    $tag_conditions['and']['or'][$k]['TagI18n.name like'] = "%$v%";
                }
            }
            $tag_conditions['and']['type'] = 'P';
            //$tag_conditions['and']['TagI18n.name like'] ="%$keyword%";
            $tag_infos = $this->Tag->find('all', array('conditions' => $tag_conditions, 'fields' => 'Tag.type_id', 'group' => 'Tag.type_id'));
            if (!empty($tag_infos)) {
                $pids = array();
                foreach ($tag_infos as $t) {
                    $pids[] = $t['Tag']['type_id'];
                }
                $conditions['AND']['OR'][4]['Product.id'] = $pids;
            }
        }
        $options = array();
        $options['conditions'] = $conditions;
        $options2 = array();
        $options2['conditions'] = $conditions2;
        //排序的判断
        if (isset($this->configs['product_order']) && !empty($this->configs['product_order']) && $this->configs['product_order'] !== 'forsale' && $this->configs['product_order'] !== 'category') {
            $options['order'] = $this->configs['product_order'];
        } elseif (isset($this->configs['product_order']) && !empty($this->configs['product_order']) && $this->configs['product_order'] == 'category') {
            $options['order'] = 'category_id';
        } else {
            $options['order'] = $order_field.' '.$order_type;
        }
       // $options['order'] =$this->configs['product_order'];
        $options['limit'] = $limit;
        $options['page'] = $page;
        $pro = $this->Product->find_all_products($options);
        $options['set'] = 'products';
        $options2['set2'] = 'products2';
//        if($min_price!=0 || $max_price!=0){
//        	$conditions['AND']['Product.shop_price >=']=$min_price;
//        	$conditions['AND']['Product.shop_price <=']=$max_price;
//        }
        //var_dump($pro);
        //品牌1
//        if($brand_id!=0){
//			$conditions['AND']['Brand.id'] = $brand_id;
//			$this->set('cat_eye',$brand_id);
//		}
        $options['conditions'] = $conditions;
        //$options['conditions'] = $conditions;
        $this->Product->find_all_products($options);
        //$this->Product->find_all_products($options2);
         //分页start
        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'searchs','action' => 'both_search','keyword' => $strkeyword,'page' => $page,'a_page' => $a_page,'limit' => $limit);
        //分页参数
        $page_options = array('page' => $page,'show' => $limit,'modelClass' => 'Product');
        $page = $this->Pagination->init($conditions, $parameters, $page_options); // Added
        //分页end
        $this->set('pages_list', $page);
        if (!empty($pro)) {
            $brand_array = array();
            $category_array = array();
            $brand_ids = array();
            $category_ids = array();
            $brand_names = array();
            $categories = array();
            foreach ($pro as $k => $v) {
                if (!in_array($v['Product']['brand_id'], $brand_ids)) {
                    $brand_ids[] = $v['Product']['brand_id'];
                }
                if (!in_array($v['Product']['category_id'], $category_ids)) {
                    $category_ids[] = $v['Product']['category_id'];
                }
            }
            $brand_array = $this->Brand->find('all', array('fields' => 'Brand.id,BrandI18n.name'));
            foreach ($brand_array as $b) {
                $brand_names[$b['Brand']['id']] = $b['BrandI18n']['name'];
            }
            if (!empty($category_ids)) {
                $category_array = $this->CategoryProduct->find('all', array('conditions' => array('CategoryProduct.id' => $category_ids), 'fields' => 'CategoryProduct.id,CategoryProductI18n.name'));
                foreach ($category_array as $b) {
                    $categories[$b['CategoryProduct']['id']] = $b['CategoryProductI18n']['name'];
                }
            }
            $this->set('brand_names', $brand_names);
            $this->set('categories', $categories);
        }
        //文章搜索开始
        $a_cond['AND']['Article.status'] = 1;
        if ($article_keyword != '') {
            $a_cond['AND']['OR']['ArticleI18n.title like'] = "%$article_keyword%";
            $a_cond['AND']['OR']['ArticleI18n.meta_description like'] = "%$article_keyword%";
        }
        if (is_array($keyword) && sizeof($keyword) > 0) {
            foreach ($keyword as $k => $v) {
                $tag_conditions['and']['or'][]['name like'] = "%$v%";
            }
        }
        $tag_conditions['and']['type'] = 'A';
        $tag_infos = $this->Tag->find('all', array('conditions' => $tag_conditions, 'fields' => 'Tag.type_id', 'group' => 'Tag.type_id'));
        if (!empty($tag_infos)) {
            $aids = array();
            foreach ($tag_infos as $t) {
                $aids[] = $t['Tag']['type_id'];
            }
            $tag_conditions['OR']['Article.id'] = $aids;
        }
        //分页start
        //get参数
        $a_parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $a_parameters['route'] = array('controller' => 'searchs','action' => 'both_search','keyword' => $article_keyword,'page' => $page,'a_page' => $a_page,'limit' => $limit);
        //分页参数
        $a_options = array('page' => $a_page,'show' => $limit,'modelClass' => 'Article');
        $article_page = $this->Pagination->init($a_cond, $a_parameters, $a_options); // Added
        //分页end
        $a_options = array();
        $a_options['conditions'] = $a_cond;
//        $a_options['order'] = $order_field.' '.$order_type;
        $a_options['limit'] = $limit;
        $a_options['page'] = $article_page;

        $articles = $this->Article->find('all', $a_options); //model
        //pr($a_options['conditions']);pr($articles);
        $this->set('articles', $articles);

        $this->layout = 'default_search';
        if (isset($_POST['flag']) && $_POST['flag'] == 1) {
            //执行导出(高级搜索)

            $limit1 = 99999;
            $options = array();
            $options['conditions'] = $conditions;
            if ($this->configs['product_order'] == 'category') {
                $options['order'] = 'category_id';
            } else {
                $options['order'] = $this->configs['product_order'];
            }
            $options['limit'] = $limit1;
            $options['page'] = 1;
           // $options['set'] = 'products';
               $pro1 = $this->Product->find_all_products($options);//搜索结果
               $this->Attribute->set_locale(LOCALE);
            $public_attr_ids = $this->ProductTypeAttribute->getattrids(0);
            $pubile_attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $public_attr_ids, 'Attribute.status' => 1), 'fields' => 'Attribute.id,AttributeI18n.name'));
            $pat = array();
            if (!empty($pubile_attr_info)) {
                foreach ($pubile_attr_info as $k => $p) {
                    $pat[$p['Attribute']['id']] = $p['AttributeI18n']['name'];
                }
            }
            //TODO 改成后台可以定义 列值，属性应用需要判断
                $allproduct = array();
            Configure::write('debug', 0);
            $data = array();
            $data[] = array('Description','Part No.','Mfg','Qty','D/C','USD','Delivery','Notes');
            $allproduct = $data;
            $ii = 0;
            foreach ($pro1 as $k => $v) {
                ++$ii;
                $allproducts = array();
                $pab = array();
                foreach ($v['ProductAttribute' ] as $pa) {
                    if ($pa[ 'attribute_id'] == $attr_id_infos[ 'dc' ] && !empty($pa['attribute_value'])) {
                        $pab[$attr_id_infos[ 'dc' ]] = $pa['attribute_value'];
                    }
                    if ($pa[ 'attribute_id'] == $attr_id_infos[ 'delivery' ] && !empty($pa['attribute_value'])) {
                        $pab[$attr_id_infos[ 'delivery' ]] = $pa['attribute_value'];
                    }
                    if ($pa[ 'attribute_id'] == $attr_id_infos[ 'notes' ] && !empty($pa['attribute_value'])) {
                        $pab[$attr_id_infos[ 'notes' ]] = $pa['attribute_value'];
                    }
                }
                $product = $this->ProductI18n->find('first', array('conditions' => array('ProductI18n.product_id ' => $v['Product']['id']), 'recursive' => -1));
                $allproducts[] = $product['ProductI18n']['name'];
                $allproducts[] = $v['Product']['code'];
                $allproducts[] = (isset($brand_names[$v['Product']['brand_id']]) ? $brand_names[$v['Product']['brand_id']] : '-');//mfg
                $allproducts[] = $v['Product']['quantity'];
                $allproducts[] = (isset($pab[$attr_id_infos[ 'dc' ]]) ? $pab[$attr_id_infos[ 'dc' ]] : '-');//dc
                $allproducts[] = (isset($v['Product']['custom_price']) && $v['Product']['custom_price'] != '' ? $v['Product']['custom_price'] : $v['Product']['shop_price']);
                $allproducts[] = (isset($pab[$attr_id_infos[ 'delivery' ]]) ? $pab[$attr_id_infos[ 'delivery' ]] : '-');//delivery
                $allproducts[] = (isset($pab[$attr_id_infos['notes']]) ? $pab[$attr_id_infos['notes']] : '-');//notes
                $allproduct[] = $allproducts;
            }
            $this->Phpexcel->output('products_export_'.date('YmdHis').'.xls', $allproduct);
            exit();
            die;
        }
    }
}
