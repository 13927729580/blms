<?php

/**
 * 	CourseCategory 课程分类
 */
class CourseCategory extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    /*
     * @var $categories_parent_format array 关联课程分类格式
     */
    public $categories_parent_format = array();
    
    function course_category_list(){
		$conditions=array();
		$conditions['CourseCategory.status']='1';
		$conditions['or'][]=array('CourseCategory.user_id'=>0);
		if(isset($_SESSION['User']['User']['id'])){
			$conditions['or'][]=array('CourseCategory.user_id'=>$_SESSION['User']['User']['id']);
		}
		$course_categorys=$this->find('all',array('conditions'=>$conditions,'order'=>"CourseCategory.name"));
		return $course_categorys;
    }
    
    function tree($category_id=0){
    		$conditions=array();
		$conditions['CourseCategory.status']='1';
		$conditions['or'][]=array('CourseCategory.user_id'=>0);
		if(isset($_SESSION['User']['User']['id'])){
			$conditions['or'][]=array('CourseCategory.user_id'=>$_SESSION['User']['User']['id']);
		}
		$course_categorys=$this->find('all',array('conditions'=>$conditions,'order'=>"CourseCategory.name"));
		if(!empty($course_categorys)){
			foreach ($course_categorys as $k => $v) {
				$this->categories_parent_format[$v['CourseCategory']['parent_id']][] = $v;
			}
		}
		return $this->subcat_get($category_id);
    }
    
    /**
     * subcat_get方法，获得subcat.
     *
     * @param int $category_id 输入id
     *
     * @return array $subcat 根据id检索相对应的数据并返回
     */
    public function subcat_get($category_id){
	        $subcat = array();
	        if (isset($this->categories_parent_format[$category_id]) && is_array($this->categories_parent_format[$category_id])) {
	            //判断parent_id = 0 的数据
	            foreach ($this->categories_parent_format[$category_id] as $k => $v) {
	                $category = $v; //parent_id 为 0 的数据
	                if (isset($this->categories_parent_format[$v['CourseCategory']['id']]) && is_array($this->categories_parent_format[$v['CourseCategory']['id']])) {
	                    $category['SubCategory'] = $this->subcat_get($v['CourseCategory']['id']);
	                }
	                $subcat[$k] = $category;
	                $this->all_subcat[$v['CourseCategory']['id']][] = $v['CourseCategory']['id'];
	                if (isset($this->all_subcat[$v['CourseCategory']['parent_id']])) {
	                    $this->all_subcat[$v['CourseCategory']['parent_id']] = array_merge($this->all_subcat[$v['CourseCategory']['parent_id']], $this->all_subcat[$v['CourseCategory']['id']]);
	                } else {
	                    $this->all_subcat[$v['CourseCategory']['parent_id']] = $this->all_subcat[$v['CourseCategory']['id']];
	                }
	            }
	        }
        	return $subcat;
    }
    
    function category_course($params=array()){
    		$result=array();
    		$category_id=isset($params['id'])?$params['id']:0;
    		$page=isset($params['page'])?$params['page']:1;
    		$limit=isset($params['limit'])?$params['limit']:10;
    		
    		$category_detail=$this->find('first',array('conditions'=>array('CourseCategory.status'=>'1','CourseCategory.id'=>$category_id)));
    		$category_tree=$this->tree($category_id);
    		$result['category_detail']=$category_detail;
    		$conditions=array();
    		$conditions['Course.status']='1';
    		$conditions['or'][]=array('Course.user_id'=>0);
		if(isset($_SESSION['User']['User']['id'])){
			$conditions['or'][]=array('Course.user_id'=>$_SESSION['User']['User']['id']);
		}
    		if(!empty($category_tree)){
    			$category_codes=array();
			foreach($category_tree as $v){
				$category_codes[$v['CourseCategory']['code']]=$v['CourseCategory']['id'];
			}
    			$child_category_ids=array_values($category_codes);
			$sub_category_codes=$this->find('list',array('fields'=>'code,parent_id','conditions'=>array('CourseCategory.status'=>'1','CourseCategory.parent_id'=>array_values($child_category_ids))));
			$conditions['Course.course_category_code']=array_merge(array_keys($category_codes),array_keys($sub_category_codes));
    		}else if(!empty($category_detail)){
    			$conditions['Course.course_category_code']=$category_detail['CourseCategory']['code'];
    		}else{
    			$conditions['Course.id']='0';
    		}
    		$Course = ClassRegistry::init('Course');
	    	if(!empty($category_tree)){
	    		$result['category_tree']=$category_tree;
	    		$course_list=$Course->find('all',array('conditions'=>$conditions,'order'=>'Course.course_category_code,Course.id'));
	    		if(!empty($course_list)){
	    			$course_tree=array();
	    			$course_ids=array();
	    			foreach($course_list as $v){
	    				$course_category_code=$v['Course']['course_category_code'];
	    				$parent_category_id=isset($category_codes[$course_category_code])?$category_codes[$course_category_code]:(isset($sub_category_codes[$course_category_code])?$sub_category_codes[$course_category_code]:0);
	    				$course_tree[$parent_category_id][]=$v;
	    				$course_ids[]=$v['Course']['id'];
	    			}
	    			$result['category_course_tree']=$course_tree;
	    			$UserCourseClass = ClassRegistry::init('UserCourseClass');
	    			$course_read_infos=$UserCourseClass->find('all',array('fields'=>'course_id,count(*) as user_total','conditions'=>array('course_id'=>$course_ids,'UserCourseClass.user_id <>'=>0),'group'=>'course_id'));
	    			$course_read_list=array();
	    			foreach($course_read_infos as $v){
	    				$course_read_list[$v['UserCourseClass']['course_id']]=$v[0]['user_total'];
	    			}
	    			$result['course_read_list']=$course_read_list;
	    		}
	    	}else if(!empty($category_detail)){
    			$total = $Course->find('count', array('conditions' => $conditions));
			App::import('Component', 'Paginationmodel');
			$pagination = new PaginationModelComponent();
			//get参数
			$parameters['get'] = array();
			//地址路由参数（和control,action的参数对应）
			$parameters['route'] = array('controller' => 'course_categories','action' => 'view/'.$category_id,'page' => $page,'limit' => $limit);
			//分页参数
			$options = array('page' => $page,'show' => $limit,'modelClass' => 'Course','total' => $total);
			$pages = $pagination->init($conditions, $parameters, $options); // Added
			$course_list=$Course->find('all',array('conditions'=>$conditions,'order'=>'Course.course_category_code,Course.id','page'=>$page,'limit'=>$limit));
			$result['course_list']=$course_list;
			$result['paging'] = $pages;
			if(!empty($course_list)){
				$course_ids=array();
				foreach($course_list as $v)$course_ids[]=$v['Course']['id'];
				$UserCourseClass = ClassRegistry::init('UserCourseClass');
	    			$course_read_infos=$UserCourseClass->find('all',array('fields'=>'course_id,count(*) as user_total','conditions'=>array('course_id'=>$course_ids,'UserCourseClass.user_id <>'=>0),'group'=>'course_id'));
	    			$course_read_list=array();
	    			foreach($course_read_infos as $v){
	    				$course_read_list[$v['UserCourseClass']['course_id']]=$v[0]['user_total'];
	    			}
	    			$result['course_read_list']=$course_read_list;
			}
    		}
    		return $result;
    }
}